<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BoardMember;
use App\Models\CorporateProfile;
use App\Models\CorporateActivity;
use App\Models\AuditLog;
use Illuminate\Support\Facades\File;

class AdminCorporateController extends Controller
{
    // ==========================================
    // 1. BOARD MEMBERS
    // ==========================================
    public function boardIndex()
    {
        $members = BoardMember::orderBy('order_num')->paginate(20);
        return view('admin.corporate.board_members.index', compact('members'));
    }

    public function boardCreate()
    {
        return view('admin.corporate.board_members.create');
    }

    public function boardStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'designation' => 'required|string|max:255',
            'designation_bn' => 'nullable|string|max:255',
            'role_type' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'administrative_role' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'speech_title' => 'nullable|string',
            'speech_message' => 'nullable|string',
            'order_num' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('uploads/board'), $imageName);
            $validated['photo'] = 'uploads/board/' . $imageName;
        }

        $validated['order_num'] = $validated['order_num'] ?? ((BoardMember::max('order_num') ?? 0) + 1);
        $validated['is_active'] = $request->has('is_active') ? true : true;

        $member = BoardMember::create($validated);
        AuditLog::log('board_member_created', 'BoardMember', $member->id, 'Created board member ' . $member->name);

        return redirect()->route('admin.corporate.board.index')->with('success', 'পরিচালনা পর্ষদের সদস্য সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function boardEdit($id)
    {
        $member = BoardMember::findOrFail($id);
        return view('admin.corporate.board_members.edit', compact('member'));
    }

    public function boardUpdate(Request $request, $id)
    {
        $member = BoardMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'designation' => 'required|string|max:255',
            'designation_bn' => 'nullable|string|max:255',
            'role_type' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'administrative_role' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'speech_title' => 'nullable|string',
            'speech_message' => 'nullable|string',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && File::exists(public_path($member->photo))) {
                File::delete(public_path($member->photo));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('uploads/board'), $imageName);
            $validated['photo'] = 'uploads/board/' . $imageName;
        }

        $validated['is_active'] = $request->has('is_active');
        $before = $member->toArray();
        $member->update($validated);

        AuditLog::log('board_member_updated', 'BoardMember', $member->id, 'Updated board member ' . $member->name, $before, $member->toArray());

        return redirect()->route('admin.corporate.board.index')->with('success', 'সদস্যের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function boardDestroy($id)
    {
        $member = BoardMember::findOrFail($id);
        if ($member->photo && File::exists(public_path($member->photo))) {
            File::delete(public_path($member->photo));
        }
        $member->delete();

        AuditLog::log('board_member_deleted', 'BoardMember', $id, 'Deleted board member ' . $member->name);
        return redirect()->route('admin.corporate.board.index')->with('success', 'সদস্য সফলভাবে মুছে ফেলা হয়েছে।');
    }

    // ==========================================
    // 2. CORPORATE PROFILES & SPEECHES
    // ==========================================
    public function profileIndex()
    {
        $profiles = CorporateProfile::all();
        $speeches = BoardMember::whereNotNull('speech_message')
            ->orWhereIn('role_type', ['chairman', 'md', 'ceo', 'marketing_head', 'finance_head'])
            ->orderBy('order_num')
            ->get();

        return view('admin.corporate.profile.index', compact('profiles', 'speeches'));
    }

    public function profileUpdate(Request $request, $id)
    {
        $profile = CorporateProfile::findOrFail($id);
        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_bn' => 'required|string',
            'content_en' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($profile->featured_image && File::exists(public_path($profile->featured_image))) {
                File::delete(public_path($profile->featured_image));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('uploads/corporate'), $imageName);
            $validated['featured_image'] = 'uploads/corporate/' . $imageName;
        }

        $profile->update($validated);
        return redirect()->route('admin.corporate.profile.index')->with('success', 'প্রতিষ্ঠানের প্রোফাইল তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function speechUpdate(Request $request, $id)
    {
        $member = BoardMember::findOrFail($id);
        $validated = $request->validate([
            'speech_title' => 'required|string|max:255',
            'speech_message' => 'required|string',
        ]);

        $member->update($validated);
        return redirect()->route('admin.corporate.profile.index')->with('success', 'নেতৃবৃন্দের বাণী সফলভাবে আপডেট হয়েছে।');
    }

    // ==========================================
    // 3. ACTIVITIES & PROJECTS
    // ==========================================
    public function activitiesIndex(Request $request)
    {
        $category = $request->query('category');
        $query = CorporateActivity::query();

        if ($category) {
            $query->where('category', $category);
        }

        $activities = $query->orderByDesc('id')->paginate(15);
        return view('admin.corporate.activities.index', compact('activities', 'category'));
    }

    public function activitiesCreate()
    {
        return view('admin.corporate.activities.create');
    }

    public function activitiesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:ongoing,completed,planned',
            'sector_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'budget' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'field_report' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|string',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $validated['project_code'] = 'ACT-' . strtoupper(uniqid());

        if ($request->hasFile('featured_image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('uploads/activities'), $imageName);
            $validated['featured_image'] = 'uploads/activities/' . $imageName;
        }

        $validated['is_featured'] = $request->has('is_featured');

        $activity = CorporateActivity::create($validated);
        AuditLog::log('activity_created', 'CorporateActivity', $activity->id, 'Created activity ' . $activity->title);

        return redirect()->route('admin.corporate.activities.index')->with('success', 'কার্যক্রম সফলভাবে তৈরি হয়েছে।');
    }

    public function activitiesEdit($id)
    {
        $activity = CorporateActivity::findOrFail($id);
        return view('admin.corporate.activities.edit', compact('activity'));
    }

    public function activitiesUpdate(Request $request, $id)
    {
        $activity = CorporateActivity::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:ongoing,completed,planned',
            'sector_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'budget' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'field_report' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|string',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($activity->featured_image && File::exists(public_path($activity->featured_image))) {
                File::delete(public_path($activity->featured_image));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('uploads/activities'), $imageName);
            $validated['featured_image'] = 'uploads/activities/' . $imageName;
        }

        $validated['is_featured'] = $request->has('is_featured');
        $activity->update($validated);

        return redirect()->route('admin.corporate.activities.index')->with('success', 'কার্যক্রম সফলভাবে আপডেট হয়েছে।');
    }

    public function activitiesDestroy($id)
    {
        $activity = CorporateActivity::findOrFail($id);
        if ($activity->featured_image && File::exists(public_path($activity->featured_image))) {
            File::delete(public_path($activity->featured_image));
        }
        $activity->delete();

        return redirect()->route('admin.corporate.activities.index')->with('success', 'কার্যক্রম সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

