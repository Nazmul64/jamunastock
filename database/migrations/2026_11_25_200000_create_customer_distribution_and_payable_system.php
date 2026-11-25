<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Stock Movements Ledger (SRD Section 15)
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_id')->index();
                $table->unsignedBigInteger('agent_point_id')->nullable()->index();
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->string('product_name')->index();
                $table->string('unit', 50)->default('Kg');
                $table->string('movement_type', 40)->index(); // supplier_received, customer_distribution, stock_adjustment, damage, return_in, return_out, transfer_in, transfer_out
                $table->enum('direction', ['in', 'out']);
                $table->decimal('quantity', 12, 2);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->string('reference_type', 60)->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('reference_code', 60)->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->date('movement_date')->index();
                $table->timestamps();
            });
        }

        // 2. Customer Distributions (SRD Section 2, 3, 12)
        if (!Schema::hasTable('customer_distributions')) {
            Schema::create('customer_distributions', function (Blueprint $table) {
                $table->id();
                $table->string('distribution_code', 50)->unique(); // DST-2026-00001
                $table->unsignedBigInteger('agent_id')->index();
                $table->unsignedBigInteger('agent_point_id')->nullable()->index();
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index(); // FK to users.id if registered
                $table->string('customer_code', 60)->nullable()->index(); // CUS-00125 / Card no
                $table->string('customer_name');
                $table->string('customer_phone', 30)->nullable()->index();
                $table->date('distribution_date')->index();
                $table->string('distribution_type', 30)->default('free'); // free, paid, subsidized, credit
                $table->string('payment_status', 30)->default('not_applicable'); // paid, unpaid, partial, not_applicable
                $table->decimal('total_quantity', 12, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('due_amount', 15, 2)->default(0);
                $table->text('note')->nullable();
                $table->string('status', 30)->default('completed'); // completed, cancelled
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 3. Customer Distribution Items (Multi-product per distribution)
        if (!Schema::hasTable('customer_distribution_items')) {
            Schema::create('customer_distribution_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('distribution_id')->index();
                $table->string('product_name')->index();
                $table->string('category')->nullable();
                $table->decimal('quantity', 12, 2);
                $table->string('unit', 50)->default('Kg');
                $table->decimal('rate', 12, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->timestamps();

                $table->foreign('distribution_id')->references('id')->on('customer_distributions')->onDelete('cascade');
            });
        }

        // 4. Supplier Payables (Invoice / Supply-wise Payable Tracking - SRD Section 7, 8, 10)
        if (!Schema::hasTable('supplier_payables')) {
            Schema::create('supplier_payables', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_id')->index();
                $table->unsignedBigInteger('supplier_supply_id')->unique();
                $table->string('supply_code', 50)->nullable()->index();
                $table->string('invoice_no', 120)->nullable()->index();
                $table->decimal('payable_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('due_amount', 15, 2)->default(0);
                $table->string('payment_status', 30)->default('unpaid')->index(); // unpaid, partial, paid
                $table->date('payable_date')->nullable();
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 5. Upgrade supplier_payments for bKash/Nagad/Rocket/Bank/Cash + attachment & reference
        DB::statement("ALTER TABLE supplier_payments MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'cash'");
        Schema::table('supplier_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_payments', 'payment_no')) {
                $table->string('payment_no', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('supplier_payments', 'reference_no')) {
                $table->string('reference_no', 120)->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('supplier_payments', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('reference_no');
            }
        });

        // 6. Supplier Payment Allocations (Which payment settles which invoice/supply)
        if (!Schema::hasTable('supplier_payment_allocations')) {
            Schema::create('supplier_payment_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_payment_id')->index();
                $table->unsignedBigInteger('supplier_payable_id')->index();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->decimal('allocated_amount', 15, 2)->default(0);
                $table->timestamps();

                $table->foreign('supplier_payment_id')->references('id')->on('supplier_payments')->onDelete('cascade');
                $table->foreign('supplier_payable_id')->references('id')->on('supplier_payables')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payment_allocations');
        Schema::dropIfExists('supplier_payables');
        Schema::dropIfExists('customer_distribution_items');
        Schema::dropIfExists('customer_distributions');
        Schema::dropIfExists('stock_movements');
    }
};
