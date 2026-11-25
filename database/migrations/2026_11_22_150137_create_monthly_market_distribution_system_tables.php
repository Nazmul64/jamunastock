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
        // 1. Dynamic Areas Table
        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique()->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->string('union_ward')->nullable();
                $table->text('address')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        // 2. Enhance Agent Points Table
        if (Schema::hasTable('agent_points')) {
            Schema::table('agent_points', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_points', 'code')) {
                    $table->string('code')->unique()->nullable()->after('name');
                }
                if (!Schema::hasColumn('agent_points', 'location')) {
                    $table->string('location')->nullable()->after('area');
                }
                if (!Schema::hasColumn('agent_points', 'area_id')) {
                    $table->unsignedBigInteger('area_id')->nullable()->after('location');
                }
                if (!Schema::hasColumn('agent_points', 'branch_id')) {
                    $table->unsignedBigInteger('branch_id')->nullable()->after('area_id');
                }
                if (!Schema::hasColumn('agent_points', 'agent_user_id')) {
                    $table->unsignedBigInteger('agent_user_id')->nullable()->after('branch_id');
                }
                if (!Schema::hasColumn('agent_points', 'agent_email')) {
                    $table->string('agent_email')->nullable()->after('contact_number');
                }
                if (!Schema::hasColumn('agent_points', 'capacity')) {
                    $table->integer('capacity')->default(1000)->after('agent_email');
                }
            });
        }

        // 3. Enhance Users Table with Agent Point & Area Links
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'agent_point_id')) {
                    $table->unsignedBigInteger('agent_point_id')->nullable()->after('branch_id');
                }
                if (!Schema::hasColumn('users', 'area_id')) {
                    $table->unsignedBigInteger('area_id')->nullable()->after('agent_point_id');
                }
            });
        }

        // 4. Monthly Market Cycles Table
        if (!Schema::hasTable('monthly_market_cycles')) {
            Schema::create('monthly_market_cycles', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. "নভেম্বর ২০২৬ মাসিক বাজার"
                $table->string('month_year', 7); // e.g. "2026-11"
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('allow_submission')->default(true);
                $table->integer('max_products')->default(10);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. Central Warehouse Stocks Table
        if (!Schema::hasTable('central_warehouse_stocks')) {
            Schema::create('central_warehouse_stocks', function (Blueprint $table) {
                $table->id();
                $table->string('product_name');
                $table->string('category')->nullable();
                $table->string('unit')->default('KG'); // KG, Liter, Piece, MT
                $table->decimal('available_stock', 15, 2)->default(0);
                $table->decimal('reserved_stock', 15, 2)->default(0);
                $table->decimal('allocated_stock', 15, 2)->default(0);
                $table->decimal('distributed_stock', 15, 2)->default(0);
                $table->decimal('damaged_stock', 15, 2)->default(0);
                $table->decimal('unit_cost', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        // 6. Central Stock Transactions Table
        if (!Schema::hasTable('central_stock_transactions')) {
            Schema::create('central_stock_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_no')->unique()->nullable();
                $table->enum('transaction_type', [
                    'purchase_receive',
                    'stock_adjustment',
                    'central_transfer',
                    'agent_allocation',
                    'agent_dispatch',
                    'product_return',
                    'damage_loss'
                ]);
                $table->string('product_name');
                $table->decimal('quantity', 15, 2);
                $table->string('unit')->default('KG');
                $table->string('source')->nullable();
                $table->string('destination')->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('reference_type')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->date('transaction_date');
                $table->timestamps();
            });
        }

        // 7. Agent Dispatches (Admin -> Agent Point)
        if (!Schema::hasTable('agent_dispatches')) {
            Schema::create('agent_dispatches', function (Blueprint $table) {
                $table->id();
                $table->string('dispatch_no')->unique(); // e.g. "DSP-202611-001"
                $table->unsignedBigInteger('agent_point_id');
                $table->date('dispatch_date');
                $table->enum('status', ['dispatched', 'in_transit', 'received', 'discrepancy'])->default('dispatched');
                $table->string('source_warehouse')->default('Central Warehouse');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('received_by')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamps();
            });
        }

        // 8. Agent Dispatch Items
        if (!Schema::hasTable('agent_dispatch_items')) {
            Schema::create('agent_dispatch_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dispatch_id');
                $table->string('product_name');
                $table->decimal('quantity', 15, 2);
                $table->string('unit')->default('KG');
                $table->decimal('received_quantity', 15, 2)->default(0);
                $table->text('discrepancy_note')->nullable();
                $table->timestamps();
            });
        }

        // 9. Member Delivery Records (Agent -> Card Holder)
        if (!Schema::hasTable('member_deliveries')) {
            Schema::create('member_deliveries', function (Blueprint $table) {
                $table->id();
                $table->string('delivery_no')->unique(); // e.g. "EK-DLV-000125"
                $table->unsignedBigInteger('agent_point_id');
                $table->unsignedBigInteger('agent_user_id');
                $table->unsignedBigInteger('card_holder_id'); // users.id
                $table->unsignedBigInteger('order_id')->nullable(); // monthly_bazaar_orders.id
                $table->string('card_number');
                $table->string('member_name');
                $table->string('area')->nullable();
                $table->date('delivery_date');
                $table->time('delivery_time');
                $table->enum('status', ['completed', 'partial'])->default('completed');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 10. Member Delivery Items
        if (!Schema::hasTable('member_delivery_items')) {
            Schema::create('member_delivery_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('delivery_id');
                $table->string('product_name');
                $table->string('unit')->default('KG');
                $table->decimal('approved_quantity', 12, 2)->default(0);
                $table->decimal('previously_delivered', 12, 2)->default(0);
                $table->decimal('quantity', 12, 2); // delivered now
                $table->decimal('remaining_quantity', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        // 11. Monthly Closings & Reconciliation
        if (!Schema::hasTable('monthly_closings')) {
            Schema::create('monthly_closings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cycle_id')->nullable();
                $table->string('month_year', 7);
                $table->integer('total_requests')->default(0);
                $table->integer('total_approved')->default(0);
                $table->decimal('total_purchased_qty', 15, 2)->default(0);
                $table->decimal('total_central_stock', 15, 2)->default(0);
                $table->decimal('total_dispatched_qty', 15, 2)->default(0);
                $table->decimal('total_agent_received_qty', 15, 2)->default(0);
                $table->decimal('total_delivered_qty', 15, 2)->default(0);
                $table->decimal('total_pending_qty', 15, 2)->default(0);
                $table->decimal('total_damaged_qty', 15, 2)->default(0);
                $table->decimal('closing_stock_qty', 15, 2)->default(0);
                $table->enum('status', ['draft', 'closed', 'locked'])->default('closed');
                $table->unsignedBigInteger('closed_by')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 12. Enhance monthly_bazaar_orders with Cycle, Delivered & Remaining Quantities
        if (Schema::hasTable('monthly_bazaar_orders')) {
            Schema::table('monthly_bazaar_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('monthly_bazaar_orders', 'cycle_id')) {
                    $table->unsignedBigInteger('cycle_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('monthly_bazaar_orders', 'agent_point_id')) {
                    $table->unsignedBigInteger('agent_point_id')->nullable()->after('agent_point');
                }
                if (!Schema::hasColumn('monthly_bazaar_orders', 'area_id')) {
                    $table->unsignedBigInteger('area_id')->nullable()->after('request_area');
                }
                if (!Schema::hasColumn('monthly_bazaar_orders', 'delivered_quantity')) {
                    $table->decimal('delivered_quantity', 12, 2)->default(0)->after('allocated_quantity');
                }
                if (!Schema::hasColumn('monthly_bazaar_orders', 'remaining_quantity')) {
                    $table->decimal('remaining_quantity', 12, 2)->default(0)->after('delivered_quantity');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping in reverse order
        Schema::dropIfExists('monthly_closings');
        Schema::dropIfExists('member_delivery_items');
        Schema::dropIfExists('member_deliveries');
        Schema::dropIfExists('agent_dispatch_items');
        Schema::dropIfExists('agent_dispatches');
        Schema::dropIfExists('central_stock_transactions');
        Schema::dropIfExists('central_warehouse_stocks');
        Schema::dropIfExists('monthly_market_cycles');
        Schema::dropIfExists('areas');
    }
};
