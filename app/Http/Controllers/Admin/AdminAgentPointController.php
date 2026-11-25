<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAgentPointController extends Controller
{
    /**
     * Section 3: Agent Point Management with Dynamic Areas and Users
     */
    public function index()
    {
        $agent_points = AgentPoint::with(['areaModel', 'agentUser'])
            ->withCount('cardHolders')
            ->latest('id')
            ->get();

        $areas = Area::where('status', 'active')->orderBy('name')->get();
        $agentUsers = User::where(function ($q) {
            $q->where('role', 'agent')
              ->orWhere('user_type', 'agent');
        })->orderBy('name')->get();

        return view('admin.agent_points.index', compact('agent_points', 'areas', 'agentUsers'));
    }

    /**
     * Store or Update Agent Point
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
            'code' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:50',
            'agent_user_id' => 'nullable|exists:users,id',
            'capacity' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $areaName = $request->area;
        if ($request->area_id) {
            $areaObj = Area::find($request->area_id);
            if ($areaObj) $areaName = $areaObj->name;
        }

        $code = $request->code ?: ('AP-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->name), 0, 3)) . '-' . rand(10, 99));

        $data = [
            'name' => $request->name,
            'code' => $code,
            'area' => $areaName ?: 'নওগাঁ',
            'area_id' => $request->area_id,
            'location' => $request->location ?: $areaName,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'agent_email' => $request->agent_email,
            'agent_user_id' => $request->agent_user_id,
            'capacity' => $request->capacity ?: 1000,
            'status' => $request->status,
        ];

        if ($request->id) {
            $point = AgentPoint::findOrFail($request->id);
            $point->update($data);
            $msg = 'সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে আপডেট করা হয়েছে!';
        } else {
            $point = AgentPoint::create($data);
            $msg = 'নতুন সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে যুক্ত করা হয়েছে!';
        }

        // If an agent user is assigned, link their account directly to this Agent Point
        if ($request->agent_user_id) {
            User::where('id', $request->agent_user_id)->update([
                'agent_point_id' => $point->id,
                'area_id' => $point->area_id
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Delete Agent Point
     */
    public function destroy($id)
    {
        $point = AgentPoint::findOrFail($id);

        if ($point->cardHolders()->count() > 0) {
            return redirect()->back()->with('error', 'এই এজেন্ট পয়েন্টে সক্রিয় কার্ড হোল্ডার রয়েছে, তাই ডিলিট করা যাবে না।');
        }

        $point->delete();
        return redirect()->back()->with('success', 'সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
