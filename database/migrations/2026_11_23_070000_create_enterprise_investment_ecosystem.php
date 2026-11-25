<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update investment_sectors with required enterprise fields
        if (Schema::hasTable('investment_sectors')) {
            Schema::table('investment_sectors', function (Blueprint $table) {
                if (!Schema::hasColumn('investment_sectors', 'slug')) {
                    $table->string('slug')->nullable()->unique()->after('name_en');
                }
                if (!Schema::hasColumn('investment_sectors', 'code')) {
                    $table->string('code')->nullable()->unique()->after('slug');
                }
                if (!Schema::hasColumn('investment_sectors', 'receipt_prefix')) {
                    $table->string('receipt_prefix')->nullable()->after('code');
                }
                if (!Schema::hasColumn('investment_sectors', 'banner_image')) {
                    $table->string('banner_image')->nullable()->after('image');
                }
                if (!Schema::hasColumn('investment_sectors', 'features')) {
                    $table->json('features')->nullable()->after('profit_loss_policy');
                }
                if (!Schema::hasColumn('investment_sectors', 'faq')) {
                    $table->json('faq')->nullable()->after('features');
                }
                if (!Schema::hasColumn('investment_sectors', 'terms')) {
                    $table->text('terms')->nullable()->after('faq');
                }
            });
        }

        // 2. Investment Plans
        if (!Schema::hasTable('investment_plans')) {
            Schema::create('investment_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sector_id');
                $table->string('name');
                $table->integer('duration_years')->default(1);
                $table->string('profit_type')->default('percentage'); // percentage, fixed, contractual
                $table->decimal('profit_rate', 8, 2)->default(0); // e.g. 12%
                $table->string('profit_frequency')->default('yearly'); // monthly, quarterly, half_yearly, yearly, at_maturity
                $table->decimal('min_amount', 15, 2)->default(100000);
                $table->decimal('max_amount', 15, 2)->nullable();
                $table->string('principal_return_type')->default('at_maturity'); // at_maturity, monthly, yearly
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('order_num')->default(1);
                $table->timestamps();

                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
            });
        }

        // 3. Investment Customers (Global Customer Profile with Sector Accounts)
        if (!Schema::hasTable('investment_customers')) {
            Schema::create('investment_customers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('customer_code')->unique(); // e.g. CUS-000125
                $table->string('name');
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->string('phone');
                $table->string('alt_phone')->nullable();
                $table->string('email')->nullable();
                $table->string('nid_number')->nullable();
                $table->string('nid_front')->nullable();
                $table->string('nid_back')->nullable();
                $table->string('customer_photo')->nullable();
                $table->text('present_address')->nullable();
                $table->text('permanent_address')->nullable();
                $table->string('occupation')->nullable();
                // Nominee info
                $table->string('nominee_name')->nullable();
                $table->string('nominee_phone')->nullable();
                $table->string('nominee_nid')->nullable();
                $table->string('nominee_relation')->nullable();
                $table->string('nominee_photo')->nullable();
                $table->string('status')->default('active'); // active, inactive
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 4. Investment Inquiries (Online CRM Pipeline)
        if (!Schema::hasTable('investment_inquiries')) {
            Schema::create('investment_inquiries', function (Blueprint $table) {
                $table->id();
                $table->string('inquiry_code')->unique(); // e.g. INQ-2027-000125
                $table->unsignedBigInteger('sector_id');
                $table->unsignedBigInteger('plan_id')->nullable();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->decimal('proposed_amount', 15, 2)->nullable();
                $table->integer('duration_years')->nullable();
                $table->string('preferred_plan')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('new'); 
                // Statuses: new, pending_contact, contacted, interested, meeting_scheduled, meeting_completed, agreement_pending, converted, rejected, cancelled, follow_up
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->unsignedBigInteger('converted_investment_id')->nullable();
                $table->timestamp('converted_at')->nullable();
                $table->timestamps();

                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
                $table->foreign('plan_id')->references('id')->on('investment_plans')->onDelete('set null');
                $table->foreign('customer_id')->references('id')->on('investment_customers')->onDelete('set null');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 5. Official Investments (Agreements & Accounts)
        if (!Schema::hasTable('enterprise_investments')) {
            Schema::create('enterprise_investments', function (Blueprint $table) {
                $table->id();
                $table->string('investment_code')->unique(); // INV-2027-000001
                $table->string('agreement_number')->unique(); // AGR-2027-LIVE-001
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('sector_id');
                $table->unsignedBigInteger('plan_id')->nullable();
                $table->unsignedBigInteger('inquiry_id')->nullable();
                $table->decimal('amount', 15, 2);
                $table->integer('duration_years')->default(1);
                $table->date('start_date');
                $table->date('maturity_date');
                $table->string('profit_type')->default('contractual'); // fixed, percentage, contractual
                $table->decimal('profit_rate', 8, 2)->default(0);
                $table->string('profit_frequency')->default('at_maturity'); // monthly, quarterly, half_yearly, yearly, at_maturity
                $table->decimal('expected_profit_amount', 15, 2)->default(0);
                $table->decimal('total_expected_return', 15, 2)->default(0);
                $table->string('principal_return_type')->default('at_maturity');
                $table->string('agreement_file')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->string('payment_method')->default('Cash'); // Cash, Bank Transfer, Cheque
                $table->string('payment_status')->default('received'); // received, pending, partial
                $table->string('status')->default('active'); // active, matured, settled, early_settled, cancelled
                $table->decimal('paid_profit_total', 15, 2)->default(0);
                $table->decimal('principal_refunded_total', 15, 2)->default(0);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('investment_customers')->onDelete('cascade');
                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
                $table->foreign('plan_id')->references('id')->on('investment_plans')->onDelete('set null');
                $table->foreign('inquiry_id')->references('id')->on('investment_inquiries')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 6. Investment Profit Schedules (Yearly / Monthly Installments)
        if (!Schema::hasTable('investment_profit_schedules')) {
            Schema::create('investment_profit_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('investment_id');
                $table->integer('installment_no')->default(1);
                $table->string('period_label'); // e.g. Year 1, Year 2, Month 1
                $table->date('due_date');
                $table->decimal('expected_profit', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('due_amount', 15, 2)->default(0);
                $table->string('status')->default('upcoming'); // upcoming, due, paid, waived
                $table->date('paid_at')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('transaction_ref')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->foreign('investment_id')->references('id')->on('enterprise_investments')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 7. Official Investment Receipts (Print / PDF / Download / Immutable)
        if (!Schema::hasTable('investment_receipts')) {
            Schema::create('investment_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_number')->unique(); // e.g. LIVE-RCP-2027-00001
                $table->string('receipt_type')->default('investment_deposit'); // investment_deposit, profit_payout, principal_refund, settlement
                $table->unsignedBigInteger('investment_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('sector_id');
                $table->unsignedBigInteger('profit_schedule_id')->nullable();
                $table->decimal('amount', 15, 2);
                $table->string('amount_in_words')->nullable();
                $table->string('payment_method')->default('Cash');
                $table->string('transaction_ref')->nullable();
                $table->string('received_by')->nullable();
                $table->boolean('is_void')->default(false);
                $table->text('void_reason')->nullable();
                $table->unsignedBigInteger('voided_by')->nullable();
                $table->timestamp('voided_at')->nullable();
                $table->integer('print_count')->default(0);
                $table->timestamp('last_printed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('investment_id')->references('id')->on('enterprise_investments')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('investment_customers')->onDelete('cascade');
                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
                $table->foreign('profit_schedule_id')->references('id')->on('investment_profit_schedules')->onDelete('set null');
                $table->foreign('voided_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 8. Receipt Audit & Reprint Logs
        if (!Schema::hasTable('investment_receipt_logs')) {
            Schema::create('investment_receipt_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('receipt_id');
                $table->string('action'); // created, printed, reprinted, voided, pdf_download
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('details')->nullable();
                $table->timestamps();

                $table->foreign('receipt_id')->references('id')->on('investment_receipts')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 9. Early Settlement & Maturity Settlements
        if (!Schema::hasTable('investment_settlements')) {
            Schema::create('investment_settlements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('investment_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('sector_id');
                $table->string('settlement_type')->default('maturity'); // maturity, early_withdrawal, cancellation
                $table->date('settlement_date');
                $table->decimal('original_amount', 15, 2);
                $table->decimal('total_profit_paid', 15, 2)->default(0);
                $table->decimal('penalty_or_deduction', 15, 2)->default(0);
                $table->decimal('net_refund_amount', 15, 2)->default(0);
                $table->text('deduction_reason')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected, paid
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('receipt_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('investment_id')->references('id')->on('enterprise_investments')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('investment_customers')->onDelete('cascade');
                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('receipt_id')->references('id')->on('investment_receipts')->onDelete('set null');
            });
        }

        // 10. Sector-Wise & Customer-Wise Financial Ledgers (Double-Entry Balance)
        if (!Schema::hasTable('investment_ledgers')) {
            Schema::create('investment_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sector_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('investment_id')->nullable();
                $table->unsignedBigInteger('receipt_id')->nullable();
                $table->string('transaction_type'); // investment_received, profit_paid, principal_refunded, penalty_deduction, fee_charge
                $table->date('transaction_date');
                $table->string('receipt_number')->nullable();
                $table->string('payment_method')->nullable();
                $table->decimal('credit', 15, 2)->default(0); // Inflow
                $table->decimal('debit', 15, 2)->default(0); // Outflow
                $table->decimal('balance', 15, 2)->default(0); // Running balance
                $table->string('description');
                $table->unsignedBigInteger('entry_by')->nullable();
                $table->timestamps();

                $table->foreign('sector_id')->references('id')->on('investment_sectors')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('investment_customers')->onDelete('cascade');
                $table->foreign('investment_id')->references('id')->on('enterprise_investments')->onDelete('set null');
                $table->foreign('receipt_id')->references('id')->on('investment_receipts')->onDelete('set null');
                $table->foreign('entry_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_ledgers');
        Schema::dropIfExists('investment_settlements');
        Schema::dropIfExists('investment_receipt_logs');
        Schema::dropIfExists('investment_receipts');
        Schema::dropIfExists('investment_profit_schedules');
        Schema::dropIfExists('enterprise_investments');
        Schema::dropIfExists('investment_inquiries');
        Schema::dropIfExists('investment_customers');
        Schema::dropIfExists('investment_plans');
    }
};
