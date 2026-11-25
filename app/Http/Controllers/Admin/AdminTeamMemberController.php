<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Illuminate\Support\Facades\File;

class AdminTeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('order_num', 'asc')->orderBy('id', 'desc')->paginate(20);
        return view('admin.team_members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team_members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($request->hasFile('photo')) {
            $destPath = public_path('uploads/team');
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move($destPath, $imageName);
            $validated['photo'] = 'uploads/team/' . $imageName;
        }

        $validated['order_num'] = $validated['order_num'] ?? ((TeamMember::max('order_num') ?? 0) + 1);
        $validated['is_active'] = $request->has('is_active') ? true : true;

        TeamMember::create($validated);

        return redirect()->route('admin.team_members.index')->with('success', 'Team Member added successfully.');
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        return view('admin.team_members.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && File::exists(public_path($member->photo))) {
                File::delete(public_path($member->photo));
            }
            $destPath = public_path('uploads/team');
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move($destPath, $imageName);
            $validated['photo'] = 'uploads/team/' . $imageName;
        }

        $validated['order_num'] = $validated['order_num'] ?? $member->order_num;
        $validated['is_active'] = $request->has('is_active');

        $member->update($validated);

        return redirect()->route('admin.team_members.index')->with('success', 'Team Member updated successfully.');
    }

    public function destroy($id)
    {
        $member = TeamMember::findOrFail($id);
        if ($member->photo && File::exists(public_path($member->photo))) {
            File::delete(public_path($member->photo));
        }
        $member->delete();

        return redirect()->route('admin.team_members.index')->with('success', 'Team Member deleted successfully.');
    }
}
