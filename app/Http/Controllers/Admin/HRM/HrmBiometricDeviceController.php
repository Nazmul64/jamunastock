<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceEvent;
use App\Models\HRM\BiometricDevice;
use App\Models\HRM\EmployeeBiometric;
use App\Models\HRM\HrmBranch;
use App\Models\User;
use App\Services\BiometricDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HrmBiometricDeviceController extends Controller
{
    protected BiometricDeviceService $biometricService;

    public function __construct(BiometricDeviceService $biometricService)
    {
        $this->biometricService = $biometricService;
    }

    /**
     * Devices List & Management Dashboard
     */
    public function index()
    {
        $devices = BiometricDevice::with('branch')->withCount(['events', 'biometrics'])->latest()->get();
        $branches = HrmBranch::where('status', 'active')->get();
        $totalDevices = $devices->count();
        $onlineDevices = $devices->where('status', 'online')->count();
        $offlineDevices = $devices->where('status', 'offline')->count();
        $totalEvents = AttendanceEvent::count();

        return view('admin.hrm.biometric.devices', compact(
            'devices',
            'branches',
            'totalDevices',
            'onlineDevices',
            'offlineDevices',
            'totalEvents'
        ));
    }

    /**
     * Store New Biometric Terminal
     */
    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:biometric_devices,serial_number',
            'device_model' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:50',
            'port' => 'nullable|integer',
            'branch_id' => 'nullable|exists:hrm_branches,id',
            'device_location' => 'nullable|string|max:100',
            'connection_type' => 'required|in:tcp_ip,usb_disk,cloud_push,sdk',
        ]);

        $apiKey = 'bio_' . Str::random(40);

        BiometricDevice::create([
            'device_name' => $request->device_name,
            'serial_number' => $request->serial_number,
            'device_model' => $request->device_model ?: 'ZKTeco K40',
            'ip_address' => $request->ip_address,
            'port' => $request->port ?: 4370,
            'branch_id' => $request->branch_id,
            'device_location' => $request->device_location,
            'connection_type' => $request->connection_type,
            'api_key' => $apiKey,
            'status' => 'online',
            'notes' => $request->notes,
        ]);

        return back()->with('success', "বায়োমেট্রিক ডিভাইস '{$request->device_name}' সফলভাবে যুক্ত করা হয়েছে।");
    }

    /**
     * Update Device Configuration
     */
    public function update(Request $request, $id)
    {
        $device = BiometricDevice::findOrFail($id);

        $request->validate([
            'device_name' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:biometric_devices,serial_number,' . $device->id,
            'device_model' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:50',
            'port' => 'nullable|integer',
            'branch_id' => 'nullable|exists:hrm_branches,id',
            'device_location' => 'nullable|string|max:100',
            'connection_type' => 'required|in:tcp_ip,usb_disk,cloud_push,sdk',
            'status' => 'required|in:online,offline,error,sync_pending,disabled',
        ]);

        $device->update($request->only([
            'device_name', 'serial_number', 'device_model', 'ip_address', 'port',
            'branch_id', 'device_location', 'connection_type', 'status', 'notes'
        ]));

        return back()->with('success', "ডিভাইস '{$device->device_name}' সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Delete Device
     */
    public function destroy($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $device->delete();

        return back()->with('success', 'ডিভাইস মুছে ফেলা হয়েছে।');
    }

    /**
     * Test / Ping Device Connection
     */
    public function ping($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $res = $this->biometricService->pingDevice($device);

        if ($res['online']) {
            return back()->with('success', '✓ ' . $res['message']);
        }

        return back()->with('error', '✗ ' . $res['message']);
    }

    /**
     * Trigger TCP/IP LAN Device Sync
     */
    public function sync($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $res = $this->biometricService->syncDeviceLogs($device);

        return back()->with('success', "✓ ডিভাইস '{$device->device_name}' এর সাথে সিঙ্ক সম্পন্ন! মোট {$res['synced_events']} টি নতুন হাজিরা ইভেন্ট প্রসেস হয়েছে।");
    }

    /**
     * Upload & Import USB U-Disk Attendance Log File (.dat, .csv, .txt)
     */
    public function uploadUsbLogs(Request $request)
    {
        $request->validate([
            'log_file' => 'required|file|mimes:txt,csv,dat|max:10240',
            'device_id' => 'nullable|exists:biometric_devices,id',
        ]);

        $file = $request->file('log_file');
        $stats = $this->biometricService->parseUsbDiskFile($file, $request->device_id);

        return back()->with('success', "✓ USB লগ ফাইল সফলভাবে ইমপোর্ট হয়েছে! মোট রেকর্ড: {$stats['total']}, সফল: {$stats['processed']}, ডুপ্লিকেট: {$stats['duplicates']}, অমিল কর্মী: {$stats['unmatched']}।");
    }

    /**
     * Fingerprint Enrollment & Device User ID Mapping Page
     */
    public function enrollmentIndex(Request $request)
    {
        $employees = User::whereIn('role', ['employee', 'staff', 'manager', 'admin'])
            ->with(['hrmProfile.department', 'hrmProfile.designation', 'biometrics.device'])
            ->get();

        $devices = BiometricDevice::where('status', 'online')->get();
        $biometrics = EmployeeBiometric::with(['user', 'device'])->latest()->paginate(20);

        return view('admin.hrm.biometric.enrollment', compact('employees', 'devices', 'biometrics'));
    }

    /**
     * Save Biometric Enrollment
     */
    public function storeEnrollment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_user_id' => 'required|string|max:50',
            'finger_index' => 'required|integer|between:0,5',
            'device_id' => 'nullable|exists:biometric_devices,id',
        ]);

        $this->biometricService->enrollEmployee(
            userId: $request->user_id,
            deviceUserId: $request->device_user_id,
            fingerIndex: (int) $request->finger_index,
            templateData: $request->template_data,
            deviceId: $request->device_id
        );

        $user = User::find($request->user_id);
        return back()->with('success', "কর্মী '{$user->name}' এর ফিঙ্গারপ্রিন্ট সফলভাবে রেজিস্টার ও ডিভাইস আইডি {$request->device_user_id} এর সাথে ম্যাপ করা হয়েছে।");
    }

    /**
     * Delete Biometric Enrollment
     */
    public function destroyEnrollment($id)
    {
        $biometric = EmployeeBiometric::findOrFail($id);
        $biometric->delete();

        return back()->with('success', 'ফিঙ্গারপ্রিন্ট ম্যাপিং মুছে ফেলা হয়েছে।');
    }

    /**
     * Raw Attendance Events Stream Log
     */
    public function eventsLog(Request $request)
    {
        $query = AttendanceEvent::with(['user', 'device'])->latest();

        if ($request->filled('date')) {
            $query->whereDate('event_time', $request->date);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('device_user_id', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $events = $query->paginate(25);
        $devices = BiometricDevice::all();

        return view('admin.hrm.biometric.events', compact('events', 'devices'));
    }
}
