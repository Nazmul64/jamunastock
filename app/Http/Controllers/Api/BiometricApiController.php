<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceEvent;
use App\Models\HRM\BiometricDevice;
use App\Models\HRM\EmployeeBiometric;
use App\Models\HRM\HrmAttendance;
use App\Models\User;
use App\Services\BiometricDeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BiometricApiController extends Controller
{
    protected BiometricDeviceService $biometricService;

    public function __construct(BiometricDeviceService $biometricService)
    {
        $this->biometricService = $biometricService;
    }

    /**
     * 1. Register or Onboard a Biometric Terminal
     * POST /api/biometric/device/register
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $request->validate([
            'device_name' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:biometric_devices,serial_number',
            'device_model' => 'nullable|string|max:100',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
            'connection_type' => 'nullable|in:tcp_ip,usb_disk,cloud_push,sdk',
        ]);

        $apiKey = 'bio_' . Str::random(40);

        $device = BiometricDevice::create([
            'device_name' => $request->device_name,
            'serial_number' => $request->serial_number,
            'device_model' => $request->device_model ?? 'ZKTeco K40',
            'ip_address' => $request->ip_address,
            'port' => $request->port ?? 4370,
            'connection_type' => $request->connection_type ?? 'tcp_ip',
            'api_key' => $apiKey,
            'status' => 'online',
            'last_sync_at' => now(),
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Biometric Device registered successfully.',
            'device' => $device,
            'api_key' => $apiKey,
        ], 201);
    }

    /**
     * 2. Device Heartbeat / Online Status Ping
     * POST /api/biometric/device/heartbeat
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $serial = $request->header('X-Device-Serial') ?? $request->serial_number;
        $device = BiometricDevice::where('serial_number', $serial)->first();

        if ($device) {
            $device->update([
                'status' => 'online',
                'last_sync_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'status' => 'online',
                'server_time' => now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Device not recognized.',
        ], 404);
    }

    /**
     * 3. Receive Attendance Events Stream from Device (Push API)
     * POST /api/biometric/attendance-events
     */
    public function receiveAttendanceEvent(Request $request): JsonResponse
    {
        $request->validate([
            'employee_device_user_id' => 'required',
            'event_time' => 'required',
            'event_type' => 'nullable|in:IN,OUT,UNKNOWN',
            'verification_type' => 'nullable|string',
            'device_id' => 'nullable',
        ]);

        $deviceId = null;
        if ($request->filled('device_id')) {
            $dev = BiometricDevice::where('id', $request->device_id)
                ->orWhere('serial_number', $request->device_id)
                ->first();
            $deviceId = $dev ? $dev->id : null;
        }

        $rawRef = $request->raw_event_reference ?: ('PUSH_' . md5($request->employee_device_user_id . '_' . $request->event_time));

        $result = $this->biometricService->processAttendanceEvent(
            deviceUserId: (string) $request->employee_device_user_id,
            eventTime: $request->event_time,
            eventType: $request->event_type,
            verificationMethod: $request->verification_type ?? 'fingerprint',
            source: 'cloud_push_api',
            deviceId: $deviceId,
            rawRef: $rawRef
        );

        $httpStatus = ($result['status'] === 'success' || $result['status'] === 'ignored_duplicate') ? 200 : 422;

        return response()->json([
            'success' => $result['status'] === 'success',
            'result' => $result,
        ], $httpStatus);
    }

    /**
     * 4. List Devices
     * GET /api/biometric/devices
     */
    public function listDevices(): JsonResponse
    {
        $devices = BiometricDevice::withCount(['events', 'biometrics'])->get();
        return response()->json(['success' => true, 'devices' => $devices]);
    }

    /**
     * 5. Device Status
     * GET /api/biometric/devices/{id}/status
     */
    public function deviceStatus($id): JsonResponse
    {
        $device = BiometricDevice::findOrFail($id);
        $ping = $this->biometricService->pingDevice($device);

        return response()->json([
            'success' => true,
            'device' => $device,
            'ping' => $ping,
        ]);
    }

    /**
     * 6. Trigger Device Sync
     * POST /api/biometric/devices/{id}/sync
     */
    public function syncDevice($id): JsonResponse
    {
        $device = BiometricDevice::findOrFail($id);
        $result = $this->biometricService->syncDeviceLogs($device);

        return response()->json($result);
    }

    /**
     * 7. Enroll Employee Biometric
     * POST /api/biometric/employees/{id}/enroll
     */
    public function enrollEmployee(Request $request, $id): JsonResponse
    {
        $request->validate([
            'device_user_id' => 'required|string',
            'finger_index' => 'nullable|integer',
            'device_id' => 'nullable|exists:biometric_devices,id',
        ]);

        $enrollment = $this->biometricService->enrollEmployee(
            userId: $id,
            deviceUserId: $request->device_user_id,
            fingerIndex: $request->finger_index ?? 0,
            templateData: $request->template_data,
            deviceId: $request->device_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Fingerprint template successfully enrolled and mapped.',
            'enrollment' => $enrollment,
        ]);
    }

    /**
     * 8. Remove Biometric Enrollment
     * POST /api/biometric/employees/{id}/remove
     */
    public function removeBiometric($id): JsonResponse
    {
        EmployeeBiometric::where('user_id', $id)->delete();
        $user = User::find($id);
        if ($user && $user->hrmProfile) {
            $user->hrmProfile->update(['biometric_user_id' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee biometric credentials revoked.',
        ]);
    }

    /**
     * 9. Today's Attendance API
     * GET /api/attendance/today
     */
    public function todayAttendance(): JsonResponse
    {
        $today = date('Y-m-d');
        $attendances = HrmAttendance::with(['user.hrmProfile.department', 'user.hrmProfile.designation'])
            ->where('date', $today)
            ->get();

        return response()->json([
            'date' => $today,
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'data' => $attendances,
        ]);
    }
}
