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
        // 1. Stores & Warehouses
        if (!Schema::hasTable('erp_stores')) {
            Schema::create('erp_stores', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('code', 50)->unique();
                $table->string('location', 200)->nullable();
                $table->string('manager_name', 150)->nullable();
                $table->string('contact', 50)->nullable();
                $table->enum('store_type', [
                    'main', 'general', 'stationery', 'it', 'furniture', 
                    'electrical', 'vehicle_parts', 'maintenance'
                ])->default('general');
                $table->string('capacity', 100)->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 2. Store Locations (Rack / Shelf / Room / Bin)
        if (!Schema::hasTable('erp_store_locations')) {
            Schema::create('erp_store_locations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained('erp_stores')->onDelete('cascade');
                $table->string('floor', 50)->nullable();
                $table->string('room_number', 50)->nullable();
                $table->string('rack_code', 50)->nullable();
                $table->string('shelf_number', 50)->nullable();
                $table->string('box_bin', 50)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Item Categories (A to H)
        if (!Schema::hasTable('erp_item_categories')) {
            Schema::create('erp_item_categories', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique(); // CAT-STAT, CAT-FURN, etc.
                $table->string('name_bn', 150);
                $table->string('name_en', 150);
                $table->string('icon', 100)->nullable();
                $table->boolean('is_asset')->default(false);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 4. Item Master (Consumables & Assets)
        if (!Schema::hasTable('erp_items')) {
            Schema::create('erp_items', function (Blueprint $table) {
                $table->id();
                $table->string('item_code', 60)->unique();
                $table->string('name_bn', 200);
                $table->string('name_en', 200)->nullable();
                $table->foreignId('category_id')->nullable()->constrained('erp_item_categories')->onDelete('set null');
                $table->string('category_code', 50)->nullable();
                $table->string('brand', 100)->nullable();
                $table->string('model_no', 100)->nullable();
                $table->string('unit', 30)->default('Pcs');
                $table->enum('item_type', ['consumable', 'fixed_asset'])->default('consumable');
                $table->decimal('purchase_price', 14, 2)->default(0.00);
                $table->decimal('average_cost', 14, 2)->default(0.00);
                $table->decimal('current_stock', 12, 2)->default(0.00);
                $table->decimal('min_stock', 12, 2)->default(5.00);
                $table->decimal('max_stock', 12, 2)->default(1000.00);
                $table->decimal('reorder_level', 12, 2)->default(10.00);
                $table->integer('warranty_months')->default(0);
                $table->string('barcode', 100)->nullable();
                $table->string('image', 255)->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 5. Stock Movements & Ledger (Stock In / Stock Out / Issue / Return / Adjustment)
        if (!Schema::hasTable('erp_stock_movements')) {
            Schema::create('erp_stock_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained('erp_items')->onDelete('cascade');
                $table->unsignedBigInteger('store_id')->nullable();
                $table->enum('movement_type', [
                    'opening', 'purchase_in', 'grn_in', 'issue_out', 'return_in', 
                    'adjustment', 'transfer_in', 'transfer_out', 'damage_out', 'disposal_out'
                ]);
                $table->decimal('quantity', 12, 2);
                $table->decimal('unit_price', 14, 2)->default(0.00);
                $table->decimal('total_cost', 14, 2)->default(0.00);
                $table->decimal('running_stock', 12, 2)->default(0.00);
                $table->string('reference_no', 100)->nullable();
                $table->string('department', 100)->nullable();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['item_id', 'movement_type']);
            });
        }

        // 6. Purchase Requisitions (PR)
        if (!Schema::hasTable('erp_purchase_requisitions')) {
            Schema::create('erp_purchase_requisitions', function (Blueprint $table) {
                $table->id();
                $table->string('pr_no', 60)->unique();
                $table->string('requested_by', 150);
                $table->string('department', 100);
                $table->unsignedBigInteger('store_id')->nullable();
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
                $table->text('purpose')->nullable();
                $table->decimal('estimated_cost', 14, 2)->default(0.00);
                $table->enum('status', ['pending', 'approved', 'rejected', 'po_created'])->default('pending');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 7. Purchase Orders (PO)
        if (!Schema::hasTable('erp_purchase_orders')) {
            Schema::create('erp_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_no', 60)->unique();
                $table->unsignedBigInteger('pr_id')->nullable();
                $table->string('supplier_name', 150);
                $table->string('supplier_contact', 60)->nullable();
                $table->unsignedBigInteger('store_id')->nullable();
                $table->date('order_date');
                $table->date('delivery_date')->nullable();
                $table->decimal('subtotal', 14, 2)->default(0.00);
                $table->decimal('tax_vat', 14, 2)->default(0.00);
                $table->decimal('discount', 14, 2)->default(0.00);
                $table->decimal('grand_total', 14, 2)->default(0.00);
                $table->string('payment_terms', 150)->nullable();
                $table->enum('status', ['draft', 'submitted', 'approved', 'partially_received', 'completed', 'cancelled'])->default('draft');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 8. Goods Receive Notes (GRN)
        if (!Schema::hasTable('erp_goods_receipt_notes')) {
            Schema::create('erp_goods_receipt_notes', function (Blueprint $table) {
                $table->id();
                $table->string('grn_no', 60)->unique();
                $table->unsignedBigInteger('po_id')->nullable();
                $table->string('supplier_name', 150);
                $table->unsignedBigInteger('store_id')->nullable();
                $table->date('received_date');
                $table->string('invoice_no', 100)->nullable();
                $table->string('challan_no', 100)->nullable();
                $table->decimal('total_amount', 14, 2)->default(0.00);
                $table->enum('qc_status', ['passed', 'partial', 'rejected'])->default('passed');
                $table->string('received_by', 150)->nullable();
                $table->enum('status', ['pending', 'approved'])->default('approved');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 9. Material Issue Notes (MIN)
        if (!Schema::hasTable('erp_material_issues')) {
            Schema::create('erp_material_issues', function (Blueprint $table) {
                $table->id();
                $table->string('min_no', 60)->unique();
                $table->date('issue_date');
                $table->unsignedBigInteger('store_id')->nullable();
                $table->string('department', 100);
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('employee_name', 150);
                $table->text('purpose')->nullable();
                $table->enum('issue_type', ['consumable', 'asset_assignment', 'temporary_loan'])->default('consumable');
                $table->integer('total_items')->default(1);
                $table->decimal('total_value', 14, 2)->default(0.00);
                $table->string('issued_by', 150)->nullable();
                $table->enum('status', ['issued', 'returned', 'cancelled'])->default('issued');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 10. Asset Assignments
        if (!Schema::hasTable('erp_asset_assignments')) {
            Schema::create('erp_asset_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('store_assets')->onDelete('cascade');
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('employee_name', 150);
                $table->string('department', 100);
                $table->string('branch', 100)->nullable();
                $table->date('assignment_date');
                $table->date('expected_return_date')->nullable();
                $table->date('actual_return_date')->nullable();
                $table->string('initial_condition', 50)->default('good');
                $table->string('return_condition', 50)->nullable();
                $table->enum('status', ['active', 'returned', 'transferred'])->default('active');
                $table->text('handover_notes')->nullable();
                $table->string('assigned_by', 150)->nullable();
                $table->timestamps();
            });
        }

        // 11. Asset Depreciations (Straight Line / Declining Balance)
        if (!Schema::hasTable('erp_asset_depreciations')) {
            Schema::create('erp_asset_depreciations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('store_assets')->onDelete('cascade');
                $table->string('fiscal_year', 20);
                $table->enum('method', ['straight_line', 'declining_balance'])->default('straight_line');
                $table->decimal('purchase_cost', 14, 2);
                $table->decimal('salvage_value', 14, 2)->default(0.00);
                $table->integer('useful_life_years')->default(5);
                $table->decimal('depreciation_rate', 6, 2)->default(20.00);
                $table->decimal('annual_depreciation', 14, 2);
                $table->decimal('accumulated_depreciation', 14, 2);
                $table->decimal('current_book_value', 14, 2);
                $table->boolean('posted_to_gl')->default(false);
                $table->string('voucher_ref', 60)->nullable();
                $table->date('calculated_at');
                $table->timestamps();
            });
        }

        // 12. Vehicles & Fleet Management
        if (!Schema::hasTable('erp_vehicles')) {
            Schema::create('erp_vehicles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id')->nullable();
                $table->string('reg_no', 60)->unique();
                $table->string('vehicle_name', 150);
                $table->enum('vehicle_type', [
                    'car', 'microbus', 'pickup', 'truck', 'motorcycle', 'bus', 'ambulance'
                ])->default('car');
                $table->string('brand', 80)->nullable();
                $table->string('model', 80)->nullable();
                $table->string('manufacturing_year', 10)->nullable();
                $table->string('chassis_no', 80)->nullable();
                $table->string('engine_no', 80)->nullable();
                $table->string('fuel_type', 40)->default('Octane');
                $table->string('driver_name', 150)->nullable();
                $table->string('driver_phone', 40)->nullable();
                $table->string('driver_license', 60)->nullable();
                $table->decimal('current_mileage', 12, 2)->default(0.00);
                $table->date('fitness_expiry')->nullable();
                $table->date('tax_token_expiry')->nullable();
                $table->date('insurance_expiry')->nullable();
                $table->date('route_permit_expiry')->nullable();
                $table->enum('status', ['active', 'in_trip', 'under_maintenance', 'retired'])->default('active');
                $table->string('image', 255)->nullable();
                $table->timestamps();
            });
        }

        // 13. Vehicle Trips Log
        if (!Schema::hasTable('erp_vehicle_trips')) {
            Schema::create('erp_vehicle_trips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained('erp_vehicles')->onDelete('cascade');
                $table->string('driver_name', 150);
                $table->string('start_location', 150);
                $table->string('destination', 150);
                $table->text('purpose')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time')->nullable();
                $table->decimal('start_km', 12, 2);
                $table->decimal('end_km', 12, 2)->nullable();
                $table->decimal('total_km', 12, 2)->default(0.00);
                $table->decimal('fuel_used_liters', 8, 2)->default(0.00);
                $table->decimal('trip_cost', 12, 2)->default(0.00);
                $table->enum('status', ['ongoing', 'completed', 'cancelled'])->default('ongoing');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 14. Vehicle Fuel Logs
        if (!Schema::hasTable('erp_vehicle_fuel_logs')) {
            Schema::create('erp_vehicle_fuel_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained('erp_vehicles')->onDelete('cascade');
                $table->date('log_date');
                $table->string('fuel_station', 150);
                $table->string('fuel_type', 40);
                $table->decimal('quantity_liters', 8, 2);
                $table->decimal('rate_per_liter', 10, 2);
                $table->decimal('total_cost', 12, 2);
                $table->decimal('odometer_km', 12, 2);
                $table->string('slip_no', 80)->nullable();
                $table->string('driver_name', 150)->nullable();
                $table->string('payment_method', 50)->default('cash');
                $table->timestamps();
            });
        }

        // 15. Maintenance Work Orders & Preventive Schedules
        if (!Schema::hasTable('erp_maintenance_work_orders')) {
            Schema::create('erp_maintenance_work_orders', function (Blueprint $table) {
                $table->id();
                $table->string('work_order_no', 60)->unique();
                $table->unsignedBigInteger('asset_id')->nullable();
                $table->enum('category', [
                    'building', 'electrical', 'plumbing', 'it', 'furniture', 
                    'vehicle', 'generator', 'ac', 'machinery', 'other'
                ])->default('it');
                $table->string('problem_title', 200);
                $table->text('problem_description')->nullable();
                $table->enum('priority', ['low', 'normal', 'high', 'emergency'])->default('normal');
                $table->string('service_provider', 150)->nullable();
                $table->string('provider_contact', 60)->nullable();
                $table->boolean('is_amc')->default(false);
                $table->decimal('spare_parts_cost', 14, 2)->default(0.00);
                $table->decimal('labor_cost', 14, 2)->default(0.00);
                $table->decimal('other_cost', 14, 2)->default(0.00);
                $table->decimal('total_cost', 14, 2)->default(0.00);
                $table->date('start_date')->nullable();
                $table->date('completion_date')->nullable();
                $table->enum('status', ['requested', 'approved', 'in_progress', 'completed', 'cancelled'])->default('requested');
                $table->integer('preventive_interval_months')->default(0);
                $table->date('next_due_date')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 16. Stock Audits & Physical Verification
        if (!Schema::hasTable('erp_stock_audits')) {
            Schema::create('erp_stock_audits', function (Blueprint $table) {
                $table->id();
                $table->string('audit_no', 60)->unique();
                $table->unsignedBigInteger('store_id')->nullable();
                $table->date('audit_date');
                $table->string('auditor_name', 150);
                $table->integer('total_items_checked')->default(0);
                $table->integer('total_variance_items')->default(0);
                $table->decimal('total_variance_value', 14, 2)->default(0.00);
                $table->enum('status', ['in_progress', 'submitted', 'approved', 'adjusted'])->default('in_progress');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 17. Damage, Lost & Disposal Register
        if (!Schema::hasTable('erp_damage_loss_disposals')) {
            Schema::create('erp_damage_loss_disposals', function (Blueprint $table) {
                $table->id();
                $table->string('record_no', 60)->unique();
                $table->enum('type', ['damage', 'lost', 'disposal', 'write_off']);
                $table->unsignedBigInteger('asset_id')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->decimal('quantity', 12, 2)->default(1.00);
                $table->date('incident_date');
                $table->text('reason');
                $table->decimal('estimated_loss', 14, 2)->default(0.00);
                $table->decimal('original_book_value', 14, 2)->default(0.00);
                $table->decimal('scrap_selling_price', 14, 2)->default(0.00);
                $table->decimal('net_gain_loss', 14, 2)->default(0.00);
                $table->string('responsible_person', 150)->nullable();
                $table->string('disposal_method', 100)->nullable(); // auction, scrap_sale, recycling, destroy
                $table->enum('status', ['reported', 'investigated', 'approved', 'rejected'])->default('reported');
                $table->unsignedBigInteger('approved_by')->nullable();
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
        Schema::dropIfExists('erp_damage_loss_disposals');
        Schema::dropIfExists('erp_stock_audits');
        Schema::dropIfExists('erp_maintenance_work_orders');
        Schema::dropIfExists('erp_vehicle_fuel_logs');
        Schema::dropIfExists('erp_vehicle_trips');
        Schema::dropIfExists('erp_vehicles');
        Schema::dropIfExists('erp_asset_depreciations');
        Schema::dropIfExists('erp_asset_assignments');
        Schema::dropIfExists('erp_material_issues');
        Schema::dropIfExists('erp_goods_receipt_notes');
        Schema::dropIfExists('erp_purchase_orders');
        Schema::dropIfExists('erp_purchase_requisitions');
        Schema::dropIfExists('erp_stock_movements');
        Schema::dropIfExists('erp_items');
        Schema::dropIfExists('erp_item_categories');
        Schema::dropIfExists('erp_store_locations');
        Schema::dropIfExists('erp_stores');
    }
};

