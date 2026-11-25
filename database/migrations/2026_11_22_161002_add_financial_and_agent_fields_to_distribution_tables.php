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
        // 1. Users Table (Agent fields)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'agent_id')) {
                $table->string('agent_id')->nullable()->unique()->after('customer_id');
            }
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable()->after('area_id');
            }
            if (!Schema::hasColumn('users', 'upazila')) {
                $table->string('upazila')->nullable()->after('district');
            }
            if (!Schema::hasColumn('users', 'branch')) {
                $table->string('branch')->nullable()->after('upazila');
            }
            if (!Schema::hasColumn('users', 'joining_date')) {
                $table->date('joining_date')->nullable()->after('branch');
            }
            if (!Schema::hasColumn('users', 'remarks')) {
                $table->text('remarks')->nullable()->after('permanent_address');
            }
        });

        // 2. Member Deliveries Table (Financial & Cycle)
        Schema::table('member_deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('member_deliveries', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('member_deliveries', 'market_month')) {
                $table->string('market_month', 7)->nullable()->after('total_amount'); // e.g. "2026-11"
            }
        });

        // 3. Member Delivery Items Table (Unit Price & Total Amount)
        Schema::table('member_delivery_items', function (Blueprint $table) {
            if (!Schema::hasColumn('member_delivery_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('member_delivery_items', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('unit_price');
            }
        });

        // 4. Central Warehouse Stocks (Distribution Unit Price & Purchase Rate)
        Schema::table('central_warehouse_stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('central_warehouse_stocks', 'distribution_unit_price')) {
                $table->decimal('distribution_unit_price', 12, 2)->default(0)->after('unit_cost');
            }
            if (!Schema::hasColumn('central_warehouse_stocks', 'purchase_rate')) {
                $table->decimal('purchase_rate', 12, 2)->default(0)->after('distribution_unit_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'agent_id', 'district', 'upazila', 'branch', 'joining_date', 'remarks']);
        });

        Schema::table('member_deliveries', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'market_month']);
        });

        Schema::table('member_delivery_items', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'total_amount']);
        });

        Schema::table('central_warehouse_stocks', function (Blueprint $table) {
            $table->dropColumn(['distribution_unit_price', 'purchase_rate']);
        });
    }
};
