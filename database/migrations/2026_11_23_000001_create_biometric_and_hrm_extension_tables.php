<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Biometric Devices (ZKTeco, uAttend, Suprema, Face/Fingerprint Terminals)
        if (!Schema::hasTable('biometric_devices')) {
            Schema::create('biometric_devices', function (Blueprint $table) {
                $table->id();
                $table->string('device_name');
                $table->string('serial_number')->unique();
                $table->string('device_model')->default('ZKTeco K40');
                $table->string('ip_address')->nullable();
                $table->integer('port')->default(4370);
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->string('device_location')->nullable();
                $table->enum('connection_type', ['tcp_ip', 'usb_disk', 'cloud_push', 'sdk'])->default('tcp_ip');
                $table->string('api_key', 64)->nullable()->unique();
                $table->timestamp('last_sync_at')->nullable();
                $table->enum('status', ['online', 'offline', 'error', 'sync_pending', 'disabled'])->default('online');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Employee Biometric Templates & User ID Mappings
        if (!Schema::hasTable('employee_biometrics')) {
            Schema::create('employee_biometrics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_user_id'); // e.g. "1001" mapped to employee
                $table->integer('finger_index')->default(0); // 0=Right Thumb, 1=Right Index, 2=Left Thumb, 3=Left Index, 4=Face
                $table->string('finger_name')->default('Right Thumb');
                $table->longText('template_data')->nullable(); // Encrypted biometric template / hash
                $table->timestamp('registered_at')->nullable();
                $table->enum('status', ['active', 'inactive', 'revoked'])->default('active');
                $table->timestamps();

                $table->index(['user_id', 'device_user_id']);
            });
        }

        // 3. Raw Attendance Events Stream (from Hardware TCP/IP, USB U-Disk, Cloud Push API, or Web Punch)
        if (!Schema::hasTable('attendance_events')) {
            Schema::create('attendance_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_user_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('event_time');
                $table->enum('event_type', ['IN', 'OUT', 'UNKNOWN'])->default('UNKNOWN');
                $table->enum('verification_method', ['fingerprint', 'face', 'rfid_card', 'password', 'web_punch'])->default('fingerprint');
                $table->enum('source', ['biometric_tcp_ip', 'usb_disk_import', 'cloud_push_api', 'web_portal', 'manual_admin'])->default('biometric_tcp_ip');
                $table->string('raw_event_reference')->nullable()->unique();
                $table->enum('processing_status', ['processed', 'ignored_duplicate', 'unmatched_user', 'failed'])->default('processed');
                $table->timestamps();

                $table->index(['device_user_id', 'event_time']);
            });
        }

        // 4. Attendance Corrections & Missed Punch Requests
        if (!Schema::hasTable('attendance_corrections')) {
            Schema::create('attendance_corrections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('date');
                $table->time('requested_check_in')->nullable();
                $table->time('requested_check_out')->nullable();
                $table->text('reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->text('review_remarks')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Employee Documents Management (CV, NID, Passport, Certificates, Contracts)
        if (!Schema::hasTable('hrm_employee_documents')) {
            Schema::create('hrm_employee_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('document_type'); // cv, nid, passport, certificate, appointment_letter, contract, medical, other
                $table->string('title');
                $table->string('file_path');
                $table->date('expiry_date')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 6. Employee Warning & Disciplinary Management
        if (!Schema::hasTable('hrm_disciplinary_records')) {
            Schema::create('hrm_disciplinary_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('record_type'); // late_attendance, absence, misconduct, policy_violation, poor_performance, warning_letter, suspension
                $table->date('issue_date');
                $table->text('description');
                $table->string('action_taken')->nullable();
                $table->string('evidence_file')->nullable();
                $table->unsignedBigInteger('issued_by')->nullable();
                $table->text('employee_response')->nullable();
                $table->enum('status', ['open', 'acknowledged', 'resolved'])->default('open');
                $table->timestamps();
            });
        }

        // 7. Resignation, Termination & Final Settlement (F&F)
        if (!Schema::hasTable('hrm_exit_settlements')) {
            Schema::create('hrm_exit_settlements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->enum('exit_type', ['resignation', 'termination', 'retirement'])->default('resignation');
                $table->date('notice_date')->nullable();
                $table->date('last_working_day');
                $table->text('reason')->nullable();
                $table->boolean('handover_completed')->default(false);
                $table->boolean('assets_returned')->default(false);
                $table->decimal('pending_salary', 12, 2)->default(0);
                $table->decimal('leave_encashment', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('overtime_amount', 12, 2)->default(0);
                $table->decimal('advance_deduction', 12, 2)->default(0);
                $table->decimal('loan_deduction', 12, 2)->default(0);
                $table->decimal('other_deductions', 12, 2)->default(0);
                $table->decimal('net_payable', 12, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'settled'])->default('pending');
                $table->timestamp('settled_at')->nullable();
                $table->unsignedBigInteger('settled_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 8. International Attendance Policy Engine
        if (!Schema::hasTable('attendance_policies')) {
            Schema::create('attendance_policies', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Standard Corporate Policy');
                $table->time('default_start_time')->default('09:00:00');
                $table->time('default_end_time')->default('18:00:00');
                $table->integer('grace_period_minutes')->default(10);
                $table->integer('late_threshold_minutes')->default(15);
                $table->integer('early_leave_minutes')->default(15);
                $table->decimal('half_day_hours', 4, 1)->default(4.0);
                $table->decimal('full_day_hours', 4, 1)->default(8.0);
                $table->integer('overtime_min_minutes')->default(30);
                $table->decimal('overtime_rate_multiplier', 4, 2)->default(1.5);
                $table->json('weekend_days')->nullable(); // e.g. ["Friday"]
                $table->boolean('missing_punch_penalty')->default(true);
                $table->integer('lock_attendance_after_days')->default(5);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 9. Enhance Employee Profiles with Personal, Family & Biometric fields
        Schema::table('hrm_employee_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_employee_profiles', 'biometric_user_id')) {
                $table->string('biometric_user_id')->nullable()->unique()->after('employee_code');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'father_name')) {
                $table->string('father_name')->nullable()->after('passport');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'father_phone')) {
                $table->string('father_phone')->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_phone');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'mother_phone')) {
                $table->string('mother_phone')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'spouse_name')) {
                $table->string('spouse_name')->nullable()->after('mother_phone');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('spouse_name');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'emergency_contact_relation')) {
                $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'blood_group')) {
                $table->string('blood_group', 10)->nullable()->after('emergency_contact_relation');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'marital_status')) {
                $table->string('marital_status', 20)->nullable()->after('blood_group');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'nationality')) {
                $table->string('nationality', 50)->default('Bangladeshi')->after('marital_status');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->default('male')->after('date_of_birth');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'present_address')) {
                $table->text('present_address')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('present_address');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'work_location')) {
                $table->string('work_location')->default('Head Office')->after('permanent_address');
            }
            if (!Schema::hasColumn('hrm_employee_profiles', 'probation_end_date')) {
                $table->date('probation_end_date')->nullable()->after('joining_date');
            }
        });

        // 10. Enhance Attendances with Biometric Source & Early Leave
        Schema::table('hrm_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_attendances', 'source')) {
                $table->enum('source', ['biometric', 'web_punch', 'manual', 'mobile_app'])->default('manual')->after('status');
            }
            if (!Schema::hasColumn('hrm_attendances', 'attendance_status')) {
                $table->string('attendance_status')->default('present')->after('source');
            }
            if (!Schema::hasColumn('hrm_attendances', 'early_leave_minutes')) {
                $table->integer('early_leave_minutes')->default(0)->after('late_minutes');
            }
            if (!Schema::hasColumn('hrm_attendances', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('hrm_attendances', 'device_id')) {
                $table->unsignedBigInteger('device_id')->nullable()->after('approved_by');
            }
        });

        // 11. Enhance Company Assets with Cost & Warranty
        Schema::table('hrm_company_assets', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_company_assets', 'model')) {
                $table->string('model')->nullable()->after('name');
            }
            if (!Schema::hasColumn('hrm_company_assets', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('model');
            }
            if (!Schema::hasColumn('hrm_company_assets', 'cost')) {
                $table->decimal('cost', 12, 2)->default(0)->after('value');
            }
            if (!Schema::hasColumn('hrm_company_assets', 'warranty_end_date')) {
                $table->date('warranty_end_date')->nullable()->after('purchase_date');
            }
        });

        // 12. Enhance Shifts with Grace Period & Flexible Rules
        Schema::table('hrm_shifts', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_shifts', 'grace_period_minutes')) {
                $table->integer('grace_period_minutes')->default(10)->after('break_time_minutes');
            }
            if (!Schema::hasColumn('hrm_shifts', 'late_threshold_minutes')) {
                $table->integer('late_threshold_minutes')->default(15)->after('grace_period_minutes');
            }
            if (!Schema::hasColumn('hrm_shifts', 'early_leave_threshold_minutes')) {
                $table->integer('early_leave_threshold_minutes')->default(15)->after('late_threshold_minutes');
            }
            if (!Schema::hasColumn('hrm_shifts', 'is_night_shift')) {
                $table->boolean('is_night_shift')->default(false)->after('overtime_enabled');
            }
            if (!Schema::hasColumn('hrm_shifts', 'weekly_off_days')) {
                $table->json('weekly_off_days')->nullable()->after('is_night_shift');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_policies');
        Schema::dropIfExists('hrm_exit_settlements');
        Schema::dropIfExists('hrm_disciplinary_records');
        Schema::dropIfExists('hrm_employee_documents');
        Schema::dropIfExists('attendance_corrections');
        Schema::dropIfExists('attendance_events');
        Schema::dropIfExists('employee_biometrics');
        Schema::dropIfExists('biometric_devices');
    }
};
