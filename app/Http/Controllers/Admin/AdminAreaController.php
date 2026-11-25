<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\AgentPoint;
use App\Models\User;

class AdminAreaController extends Controller
{
    /**
     * Section 3: Dynamic Area Management List
     */
    public function index()
    {
        $areas = Area::withCount(['agentPoints', 'cardHolders'])->orderBy('id', 'DESC')->paginate(15);
        return view('admin.areas.index', compact('areas'));
    }

    /**
     * Store a newly created Area
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:areas,code',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'upazila' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        $code = $request->code ?: 'AREA-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->name), 0, 4)) . '-' . rand(10, 99);

        Area::create([
            'name' => $request->name,
            'code' => $code,
            'division' => $request->division,
            'district' => $request->district,
            'upazila' => $request->upazila,
            'union_ward' => $request->union_ward,
            'address' => $request->address,
            'status' => 'active',
        ]);

        return back()->with('success', '✓ নতুন এলাকা সফলভাবে তৈরি করা হয়েছে।');
    }

    /**
     * Update Area
     */
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:areas,code,' . $id,
        ]);

        $area->update([
            'name' => $request->name,
            'code' => $request->code ?: $area->code,
            'division' => $request->division,
            'district' => $request->district,
            'upazila' => $request->upazila,
            'union_ward' => $request->union_ward,
            'address' => $request->address,
            'status' => $request->status ?: $area->status,
        ]);

        return back()->with('success', '✓ এলাকার তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete Area
     */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);

        if ($area->agentPoints()->count() > 0 || $area->cardHolders()->count() > 0) {
            return back()->with('error', 'এই এলাকার সাথে এজেন্ট পয়েন্ট বা সদস্য সংযুক্ত রয়েছে, তাই মুছে ফেলা সম্ভব নয়।');
        }

        $area->delete();
        return back()->with('success', '✓ এলাকাটি মুছে ফেলা হয়েছে।');
    }
}
