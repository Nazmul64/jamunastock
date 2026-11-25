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
        // 1. Enrich hrm_branches with operational metadata if missing
        Schema::table('hrm_branches', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_branches', 'established_date')) {
                $table->date('established_date')->nullable()->after('address');
            }
            if (!Schema::hasColumn('hrm_branches', 'opening_time')) {
                $table->string('opening_time', 20)->default('09:00 AM')->after('established_date');
            }
            if (!Schema::hasColumn('hrm_branches', 'closing_time')) {
                $table->string('closing_time', 20)->default('06:00 PM')->after('opening_time');
            }
            if (!Schema::hasColumn('hrm_branches', 'weekly_holiday')) {
                $table->string('weekly_holiday', 50)->default('Friday')->after('closing_time');
            }
            if (!Schema::hasColumn('hrm_branches', 'emergency_phone')) {
                $table->string('emergency_phone', 30)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('hrm_branches', 'trade_license')) {
                $table->string('trade_license', 100)->nullable()->after('emergency_phone');
            }
            if (!Schema::hasColumn('hrm_branches', 'district')) {
                $table->string('district', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('hrm_branches', 'upazila')) {
                $table->string('upazila', 100)->nullable()->after('district');
            }
            if (!Schema::hasColumn('hrm_branches', 'bank_account_name')) {
                $table->string('bank_account_name', 150)->nullable()->after('upazila');
            }
            if (!Schema::hasColumn('hrm_branches', 'bank_account_no')) {
                $table->string('bank_account_no', 100)->nullable()->after('bank_account_name');
            }
            if (!Schema::hasColumn('hrm_branches', 'bank_name')) {
                $table->string('bank_name', 100)->nullable()->after('bank_account_no');
            }
            if (!Schema::hasColumn('hrm_branches', 'branch_name_bank')) {
                $table->string('branch_name_bank', 100)->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('hrm_branches', 'routing_no')) {
                $table->string('routing_no', 50)->nullable()->after('branch_name_bank');
            }
            if (!Schema::hasColumn('hrm_branches', 'petty_cash_limit')) {
                $table->decimal('petty_cash_limit', 15, 2)->default(50000.00)->after('routing_no');
            }
            if (!Schema::hasColumn('hrm_branches', 'current_cash_balance')) {
                $table->decimal('current_cash_balance', 15, 2)->default(0.00)->after('petty_cash_limit');
            }
        });

        // 2. Branch Commissions (C - Commission Management)
        if (!Schema::hasTable('branch_commissions')) {
            Schema::create('branch_commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->unsignedBigInteger('director_id')->nullable()->index();
                $table->string('source_type', 50)->index(); // product_sales, fmcg, property, membership, business, investment_referral, bonus
                $table->string('reference_no', 100)->nullable()->index();
                $table->string('reference_title');
                $table->decimal('gross_amount', 15, 2)->default(0);
                $table->decimal('commission_rate', 8, 2)->default(0);
                $table->decimal('commission_amount', 15, 2)->default(0);
                $table->string('status', 30)->default('pending'); // pending, approved, paid
                $table->date('payout_date')->nullable();
                $table->string('payment_method', 50)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Branch Daily Reports (D - Daily Reporting)
        if (!Schema::hasTable('branch_daily_reports')) {
            Schema::create('branch_daily_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->unsignedBigInteger('submitted_by')->index();
                $table->date('report_date')->index();
                $table->decimal('total_sales', 15, 2)->default(0);
                $table->decimal('total_income', 15, 2)->default(0);
                $table->decimal('total_expense', 15, 2)->default(0);
                $table->decimal('net_balance', 15, 2)->default(0);
                $table->decimal('cash_in_hand', 15, 2)->default(0);
                $table->integer('present_employees')->default(0);
                $table->integer('absent_employees')->default(0);
                $table->integer('new_customers')->default(0);
                $table->integer('new_memberships')->default(0);
                $table->integer('new_leads')->default(0);
                $table->text('daily_highlights')->nullable();
                $table->text('challenges_faced')->nullable();
                $table->text('tomorrow_plan')->nullable();
                $table->string('status', 30)->default('submitted'); // submitted, reviewed, rejected
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->text('reviewer_notes')->nullable();
                $table->timestamps();
            });
        }

        // 4. Branch Cashbook (F - Financial Management & Cashbook)
        if (!Schema::hasTable('branch_cashbooks')) {
            Schema::create('branch_cashbooks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->date('transaction_date')->index();
                $table->string('transaction_type', 20)->index(); // income, expense
                $table->string('category', 60)->index();
                $table->string('voucher_no', 100)->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method', 40)->default('cash');
                $table->text('particulars');
                $table->string('received_by_or_paid_to', 150)->nullable();
                $table->string('receipt_attachment')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();
            });
        }

        // 5. Branch Tasks & Daily Operations (B - Branch Operations)
        if (!Schema::hasTable('branch_tasks')) {
            Schema::create('branch_tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->unsignedBigInteger('assigned_to')->nullable()->index();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
                $table->date('due_date')->nullable();
                $table->string('status', 30)->default('pending'); // pending, in_progress, completed, cancelled
                $table->text('completion_notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 6. Branch KPI Targets (K - KPI & Growth)
        if (!Schema::hasTable('branch_kpi_targets')) {
            Schema::create('branch_kpi_targets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->string('target_month', 20)->index(); // e.g. '2026-11'
                $table->decimal('sales_target', 15, 2)->default(0);
                $table->decimal('sales_achieved', 15, 2)->default(0);
                $table->integer('customer_target')->default(0);
                $table->integer('customer_achieved')->default(0);
                $table->integer('membership_target')->default(0);
                $table->integer('membership_achieved')->default(0);
                $table->decimal('attendance_score', 5, 2)->default(0);
                $table->decimal('reporting_score', 5, 2)->default(0);
                $table->decimal('overall_score', 5, 2)->default(0);
                $table->string('grade', 10)->default('A');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 7. Branch Assets & Inventory (I - Inventory Management)
        if (!Schema::hasTable('branch_assets')) {
            Schema::create('branch_assets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->string('asset_code', 50)->nullable();
                $table->string('name');
                $table->string('category', 50); // computer_electronics, furniture, stationary, appliances, vehicle, others
                $table->integer('quantity')->default(1);
                $table->decimal('purchase_cost', 12, 2)->default(0);
                $table->date('purchase_date')->nullable();
                $table->string('condition', 30)->default('good'); // good, fair, needs_repair, damaged, disposed
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->string('location_in_office', 100)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 8. Branch Business Leads (N - New Business Development)
        if (!Schema::hasTable('branch_business_leads')) {
            Schema::create('branch_business_leads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('lead_name');
                $table->string('organization_name')->nullable();
                $table->string('phone', 30);
                $table->string('email', 150)->nullable();
                $table->string('lead_type', 50); // fmcg_wholesale, property_investor, corporate_membership, agro_supply, other
                $table->decimal('estimated_value', 15, 2)->default(0);
                $table->string('stage', 40)->default('new_lead'); // new_lead, contacted, meeting_done, proposal_submitted, approved_by_hq, closed_won, closed_lost
                $table->text('details')->nullable();
                $table->string('hq_status', 30)->default('pending'); // pending, approved, rejected
                $table->text('hq_remarks')->nullable();
                $table->timestamps();
            });
        }

        // 9. Branch Complaints & Disciplinary (Z - Zero Tolerance)
        if (!Schema::hasTable('branch_complaints')) {
            Schema::create('branch_complaints', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->string('complainant_type', 30)->default('customer'); // customer, employee, public
                $table->string('complainant_name');
                $table->string('phone', 30)->nullable();
                $table->string('subject');
                $table->text('complaint_details');
                $table->string('severity', 20)->default('medium'); // low, medium, high, critical
                $table->string('status', 30)->default('pending'); // pending, investigating, resolved, rejected
                $table->text('resolution_notes')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // 10. Branch Documents (L - Legal & Compliance)
        if (!Schema::hasTable('branch_documents')) {
            Schema::create('branch_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->index();
                $table->string('title');
                $table->string('document_type', 50); // policy, agreement, trade_license, audit_report, circular, meeting_minutes, other
                $table->string('file_path')->nullable();
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_documents');
        Schema::dropIfExists('branch_complaints');
        Schema::dropIfExists('branch_business_leads');
        Schema::dropIfExists('branch_assets');
        Schema::dropIfExists('branch_kpi_targets');
        Schema::dropIfExists('branch_tasks');
        Schema::dropIfExists('branch_cashbooks');
        Schema::dropIfExists('branch_daily_reports');
        Schema::dropIfExists('branch_commissions');
    }
};
