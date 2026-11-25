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
        // 1. Chart of Accounts Table
        if (!Schema::hasTable('chart_of_accounts')) {
            Schema::create('chart_of_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
                $table->string('category', 60)->nullable(); // current_asset, fixed_asset, current_liability, equity, direct_income, etc.
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->decimal('opening_balance', 16, 2)->default(0.00);
                $table->decimal('current_balance', 16, 2)->default(0.00);
                $table->string('currency', 10)->default('BDT');
                $table->boolean('is_reconciled')->default(false);
                $table->boolean('status')->default(true); // 1 = active, 0 = inactive
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index(['type', 'code']);
            });
        }

        // 2. Financial Bills Table (Purchase, Sales, Utility, Rent, etc.)
        if (!Schema::hasTable('financial_bills')) {
            Schema::create('financial_bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_no', 60)->unique();
                $table->string('reference_no', 100)->nullable();
                $table->enum('bill_type', [
                    'purchase', 'sales', 'service', 'utility', 'electricity', 
                    'gas', 'water', 'internet', 'rent', 'transport', 'maintenance', 'salary', 'other'
                ])->default('purchase');
                $table->date('bill_date');
                $table->date('due_date')->nullable();
                $table->string('financial_year', 20)->default('2026-2027');
                $table->string('party_type', 40)->default('supplier'); // supplier, customer, employee, vendor, utility_authority
                $table->unsignedBigInteger('party_id')->nullable();
                $table->string('party_name', 150);
                $table->string('party_phone', 40)->nullable();
                $table->text('party_address')->nullable();
                $table->decimal('subtotal', 16, 2)->default(0.00);
                $table->decimal('discount_amount', 16, 2)->default(0.00);
                $table->decimal('tax_vat_amount', 16, 2)->default(0.00);
                $table->decimal('additional_charges', 16, 2)->default(0.00);
                $table->decimal('grand_total', 16, 2)->default(0.00);
                $table->decimal('paid_amount', 16, 2)->default(0.00);
                $table->decimal('outstanding_amount', 16, 2)->default(0.00);
                $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('unpaid');
                $table->enum('approval_status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'cancelled'])->default('draft');
                $table->text('notes')->nullable();
                $table->string('attachment', 255)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                $table->index(['bill_type', 'payment_status', 'bill_date']);
            });
        }

        // 3. Bill Items Table
        if (!Schema::hasTable('financial_bill_items')) {
            Schema::create('financial_bill_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bill_id')->constrained('financial_bills')->onDelete('cascade');
                $table->string('item_name', 200);
                $table->text('description')->nullable();
                $table->decimal('quantity', 12, 2)->default(1.00);
                $table->string('unit', 30)->default('Pcs');
                $table->decimal('unit_price', 16, 2)->default(0.00);
                $table->decimal('subtotal', 16, 2)->default(0.00);
                $table->decimal('tax_percent', 6, 2)->default(0.00);
                $table->decimal('tax_amount', 16, 2)->default(0.00);
                $table->decimal('total_amount', 16, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 4. Accounting Vouchers Table (Payment, Receipt, Journal, Contra, Debit Note, Credit Note)
        if (!Schema::hasTable('accounting_vouchers')) {
            Schema::create('accounting_vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('voucher_no', 60)->unique();
                $table->enum('voucher_type', [
                    'payment', 'receipt', 'journal', 'contra', 
                    'cash_payment', 'bank_payment', 'cash_receipt', 'bank_receipt',
                    'expense', 'income', 'debit_note', 'credit_note'
                ])->default('payment');
                $table->date('voucher_date');
                $table->string('financial_year', 20)->default('2026-2027');
                $table->string('reference_no', 100)->nullable();
                $table->enum('payment_mode', ['cash', 'bank', 'cheque', 'mobile_banking', 'online', 'adjustment'])->default('cash');
                $table->string('payee_payer_name', 150)->nullable(); // Pay To / Received From
                $table->string('bank_account_name', 150)->nullable();
                $table->string('cheque_no', 80)->nullable();
                $table->date('cheque_date')->nullable();
                $table->decimal('total_debit', 16, 2)->default(0.00);
                $table->decimal('total_credit', 16, 2)->default(0.00);
                $table->text('amount_in_words')->nullable();
                $table->text('general_narration')->nullable();
                $table->enum('status', ['draft', 'submitted', 'approved', 'posted', 'reversed', 'cancelled'])->default('draft');
                $table->string('attachment', 255)->nullable();
                $table->unsignedBigInteger('prepared_by')->nullable();
                $table->unsignedBigInteger('checked_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->unsignedBigInteger('posted_by')->nullable();
                $table->timestamp('posted_at')->nullable();
                $table->boolean('is_reversed')->default(false);
                $table->unsignedBigInteger('reversal_voucher_id')->nullable();
                $table->text('reversal_reason')->nullable();
                $table->timestamps();

                $table->index(['voucher_type', 'status', 'voucher_date']);
            });
        }

        // 5. Voucher Line Entries (Double Entry lines: Debit & Credit)
        if (!Schema::hasTable('voucher_entries')) {
            Schema::create('voucher_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voucher_id')->constrained('accounting_vouchers')->onDelete('cascade');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->string('account_code', 30)->nullable();
                $table->string('account_name', 150);
                $table->enum('entry_type', ['debit', 'credit']);
                $table->decimal('amount', 16, 2)->default(0.00);
                $table->text('line_narration')->nullable();
                $table->string('cost_center', 100)->nullable();
                $table->string('reference_no', 100)->nullable();
                $table->timestamps();

                $table->index(['voucher_id', 'entry_type']);
            });
        }

        // 6. Bill Payment Allocations (Connecting Vouchers with Bills for settlement)
        if (!Schema::hasTable('bill_payment_allocations')) {
            Schema::create('bill_payment_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voucher_id')->constrained('accounting_vouchers')->onDelete('cascade');
                $table->foreignId('bill_id')->constrained('financial_bills')->onDelete('cascade');
                $table->decimal('allocated_amount', 16, 2);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 7. General Ledger Entries (Posted transactions)
        if (!Schema::hasTable('general_ledger_entries')) {
            Schema::create('general_ledger_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voucher_id')->constrained('accounting_vouchers')->onDelete('cascade');
                $table->string('voucher_no', 60);
                $table->string('voucher_type', 30);
                $table->date('transaction_date');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->string('account_code', 30)->nullable();
                $table->string('account_name', 150);
                $table->decimal('debit', 16, 2)->default(0.00);
                $table->decimal('credit', 16, 2)->default(0.00);
                $table->decimal('running_balance', 16, 2)->default(0.00);
                $table->text('narration')->nullable();
                $table->string('reference_no', 100)->nullable();
                $table->timestamps();

                $table->index(['account_id', 'transaction_date']);
                $table->index('voucher_no');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_ledger_entries');
        Schema::dropIfExists('bill_payment_allocations');
        Schema::dropIfExists('voucher_entries');
        Schema::dropIfExists('accounting_vouchers');
        Schema::dropIfExists('financial_bill_items');
        Schema::dropIfExists('financial_bills');
        Schema::dropIfExists('chart_of_accounts');
    }
};
