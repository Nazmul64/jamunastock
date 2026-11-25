<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('store_assets')) {
            Schema::create('store_assets', function (Blueprint $table) {
                $table->id();
                $table->string('asset_code', 50)->unique();
                $table->string('name', 200);
                $table->enum('category', ['furniture', 'vehicle', 'stationery', 'electronics', 'equipment', 'other'])->default('other');
                $table->string('model_no', 100)->nullable();
                $table->string('serial_no', 100)->nullable();
                $table->string('vehicle_reg_no', 100)->nullable();
                $table->decimal('quantity', 10, 2)->default(1.00);
                $table->string('unit', 50)->default('টি');
                $table->date('purchase_date')->nullable();
                $table->decimal('purchase_cost', 14, 2)->default(0.00);
                $table->decimal('current_value', 14, 2)->default(0.00);
                $table->string('vendor_name', 150)->nullable();
                $table->string('vendor_contact', 100)->nullable();
                $table->string('location', 150)->nullable();
                $table->string('assigned_to', 150)->nullable();
                $table->unsignedBigInteger('assigned_user_id')->nullable();
                $table->enum('condition', ['new', 'good', 'fair', 'needs_repair', 'damaged', 'disposed'])->default('good');
                $table->enum('status', ['in_use', 'in_store', 'under_maintenance', 'scrapped'])->default('in_use');
                $table->string('image', 255)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('store_asset_maintenances')) {
            Schema::create('store_asset_maintenances', function (Blueprint $table) {
                $table->id();
                $table->string('maintenance_no', 50)->unique();
                $table->unsignedBigInteger('asset_id');
                $table->string('title', 200);
                $table->text('description')->nullable();
                $table->date('service_date');
                $table->date('next_service_due')->nullable();
                $table->string('service_provider', 150)->nullable();
                $table->string('provider_contact', 100)->nullable();
                $table->decimal('cost', 14, 2)->default(0.00);
                $table->string('voucher_ref', 100)->nullable();
                $table->string('receipt_file', 255)->nullable();
                $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('completed');
                $table->string('performed_by', 150)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('store_assets')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('store_vouchers')) {
            Schema::create('store_vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('voucher_no', 50)->unique();
                $table->enum('type', ['debit_voucher', 'credit_voucher', 'cash_memo', 'money_receipt', 'bill'])->default('debit_voucher');
                $table->string('category', 100)->default('general_utility');
                $table->string('title', 200);
                $table->string('vendor_customer_name', 150)->nullable();
                $table->decimal('amount', 14, 2)->default(0.00);
                $table->date('date');
                $table->enum('payment_method', ['cash', 'bank', 'bkash', 'nagad', 'cheque', 'other'])->default('cash');
                $table->string('payment_ref', 100)->nullable();
                $table->text('item_details')->nullable();
                $table->text('description')->nullable();
                $table->string('attachment', 255)->nullable();
                $table->enum('status', ['paid', 'approved', 'pending', 'rejected'])->default('paid');
                $table->string('approved_by', 150)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_asset_maintenances');
        Schema::dropIfExists('store_assets');
        Schema::dropIfExists('store_vouchers');
    }
};

