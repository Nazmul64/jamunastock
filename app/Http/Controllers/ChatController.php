<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Fetch chat history for the active visitor session or logged in user.
     */
    public function fetchMessages(Request $request)
    {
        $sessionId = $request->input('session_id') ?: session()->getId();

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Store visitor/user message and trigger AI Bot response if applicable.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id') ?: session()->getId();
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : ($request->input('sender_name') ?: 'Guest Visitor');
        $userRole = $user ? ($user->role ?: 'user') : 'user';

        // Save User Message
        $userMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender_type' => 'user',
            'sender_name' => $userName,
            'user_role' => $userRole,
            'message' => trim($request->input('message')),
            'is_read' => false,
        ]);

        // Generate AI Assistant Reply
        $aiReplyText = $this->generateAiReply(trim($request->input('message')));

        if ($aiReplyText) {
            ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => null,
                'sender_type' => 'bot',
                'sender_name' => 'Jamuna AI Assistant',
                'user_role' => $userRole,
                'message' => $aiReplyText,
                'is_read' => true,
            ]);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Generate smart AI response based on keyword detection.
     */
    private function generateAiReply($text)
    {
        $lower = mb_strtolower($text, 'UTF-8');

        if (Str::contains($lower, ['stock', 'rate', 'price', 'share', 'market'])) {
            return "🤖 **Jamuna Stock Market Information:**\nVisit our 'Stock Market' page in the top menu to view daily stock prices, rates, and market updates. If you need assistance with specific stock transactions, an admin representative will assist you shortly.";
        }

        if (Str::contains($lower, ['package', 'packages', 'bazaar', 'monthly', 'grocery'])) {
            return "🤖 **Monthly Packages & Services:**\nExplore our 'Our Packages' section on the homepage to discover our membership and monthly grocery packages. Feel free to ask any specific questions here.";
        }

        if (Str::contains($lower, ['register', 'account', 'signup', 'login'])) {
            return "🤖 **Account Registration Guide:**\nTo open a new account, click the 'Sign Up / Register' option in the navigation menu. Select the appropriate role (Customer or Supplier) to access your specialized portal.";
        }

        if (Str::contains($lower, ['agent', 'partner', 'partnership', 'commission'])) {
            return "🤖 **Agent & Partnership Support:**\nWe offer specialized real-time support and benefits for registered agents and partners. For partnership details, please navigate to our Shareholders & Partnerships page.";
        }

        if (Str::contains($lower, ['contact', 'phone', 'number', 'address', 'help', 'email', 'hotline'])) {
            return "🤖 **Customer Support Hotline:**\nFor office location and direct phone contacts, please visit our 'Contact Us' page. Our team is available to assist you.";
        }

        if (Str::contains($lower, ['hi', 'hello', 'hey', 'salam'])) {
            return "🤖 **Hello & Welcome!** Welcome to Jamuna Stock Market live customer support. How may we assist you today?";
        }

        return "🤖 **Thank you for your message!** Our customer support representative will respond to your query shortly. Meanwhile, you may browse our Stock Market, Our Packages, or Contact Us pages.";
    }

    /**
     * Admin Panel Live Chat Hub - View active chats filtered by user role (user, employee, supplier, agent).
     */
    public function adminIndex(Request $request)
    {
        $selectedRole = $request->query('role'); // 'user', 'employee', 'supplier', 'agent'

        $query = ChatMessage::select('session_id')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('COUNT(id) as total_messages')
            ->selectRaw('SUM(CASE WHEN is_read = 0 AND sender_type = "user" THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('session_id');

        if (!empty($selectedRole)) {
            if ($selectedRole === 'user') {
                $query->where(function($q) {
                    $q->where('user_role', 'user')
                      ->orWhereNull('user_role')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'user');
                      });
                });
            } elseif ($selectedRole === 'employee') {
                $query->where(function($q) {
                    $q->where('user_role', 'employee')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'employee');
                      });
                });
            } elseif ($selectedRole === 'supplier') {
                $query->where(function($q) {
                    $q->where('user_role', 'supplier')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'supplier');
                      });
                });
            } elseif ($selectedRole === 'agent') {
                $query->where(function($q) {
                    $q->whereIn('user_role', ['agent', 'employee'])
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->whereIn('role', ['agent', 'employee']);
                      });
                });
            }
        }

        $sessions = $query->orderBy('last_activity', 'desc')->get();

        foreach ($sessions as $session) {
            $session->last_message = ChatMessage::where('session_id', $session->session_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $session->user_info = ChatMessage::where('session_id', $session->session_id)
                ->whereNotNull('sender_name')
                ->where('sender_type', 'user')
                ->first();

            if (!$session->user_info && $session->last_message) {
                $session->user_info = $session->last_message;
            }

            // Determine role tag for session
            $roleTag = 'user';
            if ($session->last_message && !empty($session->last_message->user_role)) {
                $roleTag = $session->last_message->user_role;
            } elseif ($session->user_info && $session->user_info->user) {
                $roleTag = $session->user_info->user->role ?: 'user';
            }
            $session->detected_role = $roleTag;
        }

        // Unread counts per role
        $counts = [
            'user' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where(function($q){ $q->where('user_role', 'user')->orWhereNull('user_role'); })->distinct('session_id')->count('session_id'),
            'employee' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where('user_role', 'employee')->distinct('session_id')->count('session_id'),
            'supplier' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where('user_role', 'supplier')->distinct('session_id')->count('session_id'),
            'agent' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->whereIn('user_role', ['agent', 'employee'])->distinct('session_id')->count('session_id'),
            'all' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->distinct('session_id')->count('session_id'),
        ];

        return view('admin.chat.index', compact('sessions', 'selectedRole', 'counts'));
    }

    /**
     * Admin fetch messages for a specific session.
     */
    public function adminFetchSession($sessionId)
    {
        // Mark as read
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'user')
            ->update(['is_read' => true]);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Admin send reply to user session.
     */
    public function adminSendReply(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id');

        // Detect user_role from existing session messages
        $existingMsg = ChatMessage::where('session_id', $sessionId)->whereNotNull('user_role')->first();
        $targetRole = $existingMsg ? $existingMsg->user_role : 'user';

        $reply = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'sender_type' => 'admin',
            'sender_name' => Auth::user() ? Auth::user()->name : 'Admin Customer Support',
            'user_role' => $targetRole,
            'message' => trim($request->input('message')),
            'is_read' => true,
        ]);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }
}
