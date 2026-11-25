<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardMember;
use App\Models\CorporateProfile;
use App\Models\CorporateActivity;

class CorporateController extends Controller
{
    /**
     * Display the About Us / Corporate Profile page
     */
    public function about()
    {
        $profiles = CorporateProfile::where('is_active', true)->get()->keyBy('section_key');
        $boardMembers = BoardMember::where('is_active', true)->orderBy('order_num')->get();
        $speeches = BoardMember::where('is_active', true)
            ->whereNotNull('speech_message')
            ->where('speech_message', '!=', '')
            ->orderBy('order_num')
            ->get();
        $activities = CorporateActivity::where('status', 'active')
            ->orWhere('status', 'ongoing')
            ->orderByDesc('id')
            ->take(6)
            ->get();
        $properties = class_exists(\App\Models\Property::class) ? \App\Models\Property::where('status', 'available')->orderBy('id')->get() : collect();
        $cardBenefits = class_exists(\App\Models\CardBenefit::class) ? \App\Models\CardBenefit::where('status', 1)->orderBy('order_num')->get() : collect();

        return view('frontend.about-us', compact('profiles', 'boardMembers', 'speeches', 'activities', 'properties', 'cardBenefits'));
    }

    /**
     * Display Board of Directors & Team
     */
    public function boardMembers(Request $request)
    {
        $roleFilter = $request->query('role');
        $query = BoardMember::where('is_active', true)->orderBy('order_num');

        if ($roleFilter) {
            $query->where('role_type', $roleFilter);
        }

        $members = $query->paginate(12);
        $totalMembers = BoardMember::where('is_active', true)->count();
        $speeches = BoardMember::where('is_active', true)
            ->whereNotNull('speech_message')
            ->where('speech_message', '!=', '')
            ->orderBy('order_num')
            ->get();

        return view('frontend.board_members', compact('members', 'totalMembers', 'speeches', 'roleFilter'));
    }

    /**
     * Display Leadership Messages (Chairman, MD, CEO, Marketing Head, Finance Head)
     */
    public function leadershipSpeeches()
    {
        $speeches = BoardMember::where('is_active', true)
            ->whereNotNull('speech_message')
            ->where('speech_message', '!=', '')
            ->orderBy('order_num')
            ->get();

        return view('frontend.leadership_speeches', compact('speeches'));
    }

    /**
     * Display Activities and Projects (Completed, Ongoing, Planned)
     */
    public function activities(Request $request)
    {
        $category = $request->query('category');
        $query = CorporateActivity::query();

        if ($category && in_array($category, ['ongoing', 'completed', 'planned'])) {
            $query->where('category', $category);
        }

        $activities = $query->orderByDesc('id')->paginate(9);

        $counts = [
            'all' => CorporateActivity::count(),
            'ongoing' => CorporateActivity::where('category', 'ongoing')->count(),
            'completed' => CorporateActivity::where('category', 'completed')->count(),
            'planned' => CorporateActivity::where('category', 'planned')->count(),
        ];

        return view('frontend.activities', compact('activities', 'counts', 'category'));
    }

    /**
     * Show single activity details
     */
    public function activityDetail($id)
    {
        $activity = CorporateActivity::findOrFail($id);
        $relatedActivities = CorporateActivity::where('id', '!=', $id)
            ->where('category', $activity->category)
            ->take(3)
            ->get();

        return view('frontend.activity_detail', compact('activity', 'relatedActivities'));
    }
}

