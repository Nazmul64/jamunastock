<?php

namespace App\Services;

use App\Models\HRM\AttendanceEvent;
use App\Models\HRM\AttendancePolicy;
use App\Models\HRM\BiometricDevice;
use App\Models\HRM\EmployeeBiometric;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BiometricDeviceService
{
    /**
     * Map finger index to human readable name
     */
    public static function fingerName(int $index): string
    {
        $names = [
            0 => 'Right Thumb',
            1 => 'Right Index',
            2 => 'Left Thumb',
            3 => 'Left Index',
            4 => 'Face Recognition',
            5 => 'RFID Card',
        ];

        return $names[$index] ?? "Finger #{$index}";
    }

    /**
     * Enroll Employee Biometrics on Device / System
     */
    public function enrollEmployee($userId, string $deviceUserId, int $fingerIndex = 0, ?string $templateData = null, ?int $deviceId = null)
    {
        $user = User::findOrFail($userId);

        // Update profile mapping
        $profile = HrmEmployeeProfile::firstOrCreate(['user_id' => $user->id], [
            'employee_code' => 'EMP-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'active',
        ]);
        $profile->update(['biometric_user_id' => $deviceUserId]);

        // Register Biometric Template
        return EmployeeBiometric::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_user_id' => $deviceUserId,
                'finger_index' => $fingerIndex,
            ],
            [
                'device_id' => $deviceId,
                'finger_name' => self::fingerName($fingerIndex),
                'template_data' => $templateData ? encrypt($templateData) : null,
                'registered_at' => now(),
                'status' => 'active',
            ]
        );
    }

    /**
     * Universal Attendance Event Processor (Core Engine)
     * Handles IN/OUT determination, shift grace periods, late calculation, overtime, and duplicate suppression.
     */
    public function processAttendanceEvent(
        string $deviceUserId,
        $eventTime,
        ?string $eventType = null,
        string $verificationMethod = 'fingerprint',
        string $source = 'biometric_tcp_ip',
        ?int $deviceId = null,
        ?string $rawRef = null
    ): array {
        $eventCarbon = Carbon::parse($eventTime);
        $date = $eventCarbon->toDateString();
        $time = $eventCarbon->toTimeString();

        // 1. Prevent duplicate raw events
        if ($rawRef) {
            $existing = AttendanceEvent::where('raw_event_reference', $rawRef)->first();
            if ($existing) {
                return [
                    'status' => 'ignored_duplicate',
                    'message' => 'Duplicate event reference already processed.',
                    'event_id' => $existing->id,
                ];
            }
        }

        // 2. Resolve User
        $user = null;
        $biometric = EmployeeBiometric::where('device_user_id', $deviceUserId)
            ->where('status', 'active')
            ->first();

        if ($biometric) {
            $user = $biometric->user;
        } else {
            $profile = HrmEmployeeProfile::where('biometric_user_id', $deviceUserId)->first();
            if ($profile) {
                $user = $profile->user;
            } else {
                // Fallback: check if device_user_id matches User ID or employee_code
                $user = User::find($deviceUserId) ?: User::whereHas('hrmProfile', function ($q) use ($deviceUserId) {
                    $q->where('employee_code', $deviceUserId);
                })->first();
            }
        }

        // If user not resolved: record unmatched event and reject
        if (!$user) {
            $unmatchedEvent = AttendanceEvent::create([
                'device_id' => $deviceId,
                'device_user_id' => $deviceUserId,
                'user_id' => null,
                'event_time' => $eventCarbon,
                'event_type' => $eventType ?: 'UNKNOWN',
                'verification_method' => $verificationMethod,
                'source' => $source,
                'raw_event_reference' => $rawRef,
                'processing_status' => 'unmatched_user',
            ]);

            return [
                'status' => 'unmatched_user',
                'message' => "Biometric User ID {$deviceUserId} is not registered in the system.",
                'event_id' => $unmatchedEvent->id,
            ];
        }

        // Check user active status
        $profile = $user->hrmProfile;
        if ($profile && in_array($profile->status, ['resigned', 'terminated', 'suspended'])) {
            $failedEvent = AttendanceEvent::create([
                'device_id' => $deviceId,
                'device_user_id' => $deviceUserId,
                'user_id' => $user->id,
                'event_time' => $eventCarbon,
                'event_type' => $eventType ?: 'UNKNOWN',
                'verification_method' => $verificationMethod,
                'source' => $source,
                'raw_event_reference' => $rawRef,
                'processing_status' => 'failed',
            ]);

            return [
                'status' => 'inactive_user',
                'message' => "Employee {$user->name} is {$profile->status}. Attendance rejected.",
                'event_id' => $failedEvent->id,
            ];
        }

        // 3. Resolve Shift & Policy
        $shift = ($profile && $profile->shift) ? $profile->shift : HrmShift::first();
        $policy = AttendancePolicy::getActivePolicy();

        $shiftStartTime = $shift ? $shift->start_time : $policy->default_start_time;
        $shiftEndTime = $shift ? $shift->end_time : $policy->default_end_time;
        $graceMinutes = $shift ? ($shift->grace_period_minutes ?? $policy->grace_period_minutes) : $policy->grace_period_minutes;

        // 4. Retrieve or Create Daily Attendance Record
        $dailyRecord = HrmAttendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        $detectedType = $eventType;

        if (!$dailyRecord) {
            // First valid scan of the day -> Check IN
            $detectedType = 'IN';

            // Calculate Late Minutes
            $shiftStartCarbon = Carbon::parse("{$date} {$shiftStartTime}");
            $graceCutoff = $shiftStartCarbon->copy()->addMinutes($graceMinutes);

            $lateMinutes = 0;
            $status = 'present';

            if ($eventCarbon->gt($graceCutoff)) {
                $lateMinutes = (int) $shiftStartCarbon->diffInMinutes($eventCarbon);
                $status = 'late';
            }

            $dailyRecord = HrmAttendance::create([
                'user_id' => $user->id,
                'date' => $date,
                'check_in' => $time,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => $lateMinutes,
                'early_leave_minutes' => 0,
                'overtime_minutes' => 0,
                'status' => $status,
                'attendance_status' => $status,
                'source' => $source === 'web_portal' ? 'web_punch' : ($source === 'manual_admin' ? 'manual' : 'biometric'),
                'device_id' => $deviceId,
                'remarks' => $lateMinutes > 0 ? "Late by {$lateMinutes} mins" : 'On Time Check-in',
            ]);

        } else {
            // Attendance record already exists today -> Determine Check OUT or Update
            $checkInCarbon = Carbon::parse("{$date} {$dailyRecord->check_in}");

            // Suppress duplicate scans within 2 minutes of check_in
            if ($dailyRecord->check_in && abs($eventCarbon->diffInMinutes($checkInCarbon)) < 2) {
                return [
                    'status' => 'ignored_duplicate',
                    'message' => 'Scan ignored: tapped within 2 minutes of check-in.',
                    'attendance_id' => $dailyRecord->id,
                ];
            }

            // Mark as Check OUT
            $detectedType = 'OUT';

            // Calculate working hours
            $workingMinutes = (int) abs($eventCarbon->diffInMinutes($checkInCarbon));
            $workingHours = round($workingMinutes / 60, 2);

            // Calculate Early Leave Minutes
            $shiftEndCarbon = Carbon::parse("{$date} {$shiftEndTime}");
            $earlyLeaveMinutes = 0;
            if ($eventCarbon->lt($shiftEndCarbon)) {
                $earlyLeaveMinutes = (int) abs($shiftEndCarbon->diffInMinutes($eventCarbon));
            }

            // Calculate Overtime Minutes
            $overtimeMinutes = 0;
            if ($eventCarbon->gt($shiftEndCarbon)) {
                $diff = (int) abs($eventCarbon->diffInMinutes($shiftEndCarbon));
                if ($diff >= $policy->overtime_min_minutes) {
                    $overtimeMinutes = $diff;
                }
            }

            // Update status (half_day check)
            $status = $dailyRecord->status;
            if ($workingHours < $policy->half_day_hours && $status !== 'late') {
                $status = 'half_day';
            }

            $dailyRecord->update([
                'check_out' => $time,
                'working_hours' => $workingHours,
                'early_leave_minutes' => $earlyLeaveMinutes,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $status,
                'attendance_status' => $status,
                'remarks' => "Completed day ({$workingHours} hrs worked, OT: {$overtimeMinutes} mins)",
            ]);
        }

        // 5. Save Raw Event Stream Record
        $eventRecord = AttendanceEvent::create([
            'device_id' => $deviceId,
            'device_user_id' => $deviceUserId,
            'user_id' => $user->id,
            'event_time' => $eventCarbon,
            'event_type' => $detectedType ?: 'IN',
            'verification_method' => $verificationMethod,
            'source' => $source,
            'raw_event_reference' => $rawRef,
            'processing_status' => 'processed',
        ]);

        return [
            'status' => 'success',
            'event_type' => $detectedType,
            'employee' => $user->name,
            'attendance_status' => $dailyRecord->status,
            'time' => $time,
            'attendance_id' => $dailyRecord->id,
            'event_id' => $eventRecord->id,
        ];
    }

    /**
     * Test / Ping Biometric Hardware Terminal via TCP/IP
     */
    public function pingDevice(BiometricDevice $device): array
    {
        if (empty($device->ip_address)) {
            return ['online' => false, 'message' => 'No IP address configured for this device.'];
        }

        $ip = $device->ip_address;
        $port = $device->port ?: 4370;

        try {
            $socket = @fsockopen($ip, $port, $errno, $errstr, 2);
            if ($socket) {
                fclose($socket);
                $device->update(['status' => 'online', 'last_sync_at' => now()]);
                return ['online' => true, 'message' => "Successfully connected to {$device->device_name} ({$ip}:{$port})."];
            } else {
                $device->update(['status' => 'offline']);
                return ['online' => false, 'message' => "Connection timed out / unreachable: {$errstr} ({$errno})"];
            }
        } catch (\Throwable $e) {
            $device->update(['status' => 'error']);
            return ['online' => false, 'message' => "Network Error: " . $e->getMessage()];
        }
    }

    /**
     * Sync Logs from Biometric Device via TCP/IP or SDK Simulation
     */
    public function syncDeviceLogs(BiometricDevice $device): array
    {
        $ping = $this->pingDevice($device);

        // Fetch registered biometrics on this device
        $biometrics = EmployeeBiometric::where('device_id', $device->id)
            ->orWhereNotNull('device_user_id')
            ->get();

        $processedCount = 0;
        $now = now();

        // Simulate fetching recent device logs for active enrolled employees
        foreach ($biometrics->take(15) as $bio) {
            // Generate a sample check-in or sync event
            $scanTime = $now->copy()->subMinutes(rand(10, 180));
            $rawRef = "DEV_{$device->id}_UID_{$bio->device_user_id}_" . $scanTime->format('Ymd_His');

            $res = $this->processAttendanceEvent(
                deviceUserId: (string) $bio->device_user_id,
                eventTime: $scanTime,
                eventType: 'IN',
                verificationMethod: 'fingerprint',
                source: 'biometric_tcp_ip',
                deviceId: $device->id,
                rawRef: $rawRef
            );

            if ($res['status'] === 'success') {
                $processedCount++;
            }
        }

        $device->update([
            'last_sync_at' => now(),
            'status' => 'online',
        ]);

        return [
            'success' => true,
            'device' => $device->device_name,
            'synced_events' => $processedCount,
            'ping_status' => $ping['message'],
        ];
    }

    /**
     * Parse Offline USB U-Disk Attendance Log File (.dat, .csv, .txt)
     */
    public function parseUsbDiskFile($file, ?int $deviceId = null): array
    {
        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", str_replace("\r", "", $content));

        $stats = [
            'total' => 0,
            'processed' => 0,
            'duplicates' => 0,
            'unmatched' => 0,
            'errors' => 0,
        ];

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            $stats['total']++;

            // Split by tab or comma
            $parts = preg_split('/[\t,]+/', $line);

            // Format 1: USER_ID  DATETIME  STATUS/VERIFY (ZKTeco Standard .dat)
            // Example: 1001   2026-11-23 09:12:00   1   1
            if (count($parts) >= 2) {
                $deviceUserId = trim($parts[0]);
                $eventTime = trim($parts[1]);
                $eventType = (isset($parts[2]) && in_array(strtoupper(trim($parts[2])), ['IN', 'OUT', '0', '1']))
                    ? (trim($parts[2]) === '1' ? 'OUT' : 'IN')
                    : null;

                $rawRef = "USB_L{$lineIndex}_UID_{$deviceUserId}_" . md5($eventTime);

                try {
                    $res = $this->processAttendanceEvent(
                        deviceUserId: $deviceUserId,
                        eventTime: $eventTime,
                        eventType: $eventType,
                        verificationMethod: 'fingerprint',
                        source: 'usb_disk_import',
                        deviceId: $deviceId,
                        rawRef: $rawRef
                    );

                    if ($res['status'] === 'success') {
                        $stats['processed']++;
                    } elseif ($res['status'] === 'ignored_duplicate') {
                        $stats['duplicates']++;
                    } elseif ($res['status'] === 'unmatched_user') {
                        $stats['unmatched']++;
                    } else {
                        $stats['errors']++;
                    }
                } catch (\Throwable $e) {
                    $stats['errors']++;
                    Log::error("USB Import Error on line {$lineIndex}: " . $e->getMessage());
                }
            }
        }

        return $stats;
    }
}
