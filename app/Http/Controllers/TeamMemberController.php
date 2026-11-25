<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamMember;

class TeamMemberController extends Controller
{
    /**
     * Display public Team Members page
     */
    public function index()
    {
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.team_members', compact('teamMembers'));
    }
}
