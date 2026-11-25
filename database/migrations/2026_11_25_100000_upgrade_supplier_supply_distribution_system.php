<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Dynamic Product Units (Managed by Admin)
        if (!Schema::hasTable('product_units')) {
            Schema::create('product_units', function (Blueprint $table) {
                $table->id();
                $table->string('name', 80); // e.g. Kg, Bag, Piece
                $table->string('bn_name', 100)->nullable(); // e.g. কেজি, বস্তা
                $table->string('symbol', 30)->nullable(); // e.g. kg, bag, pcs
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Dynamic Product Categories (Managed by Admin)
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->string('bn_name', 150)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Upgrade supplier_supplies table with full lifecycle, approval, assignment & accounting fields
        Schema::table('supplier_supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_supplies', 'supply_code')) {
                $table->string('supply_code', 50)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('supplier_supplies', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('category');
            }
            if (!Schema::hasColumn('supplier_supplies', 'unit_id')) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('supplier_supplies', 'approved_quantity')) {
                $table->decimal('approved_quantity', 12, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('supplier_supplies', 'approved_rate')) {
                $table->decimal('approved_rate', 12, 2)->nullable()->after('rate');
            }
            if (!Schema::hasColumn('supplier_supplies', 'approved_amount')) {
                $table->decimal('approved_amount', 15, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('supplier_supplies', 'actual_received_quantity')) {
                $table->decimal('actual_received_quantity', 12, 2)->default(0)->after('approved_amount');
            }
            if (!Schema::hasColumn('supplier_supplies', 'actual_received_amount')) {
                $table->decimal('actual_received_amount', 15, 2)->default(0)->after('actual_received_quantity');
            }
            if (!Schema::hasColumn('supplier_supplies', 'discrepancy_quantity')) {
                $table->decimal('discrepancy_quantity', 12, 2)->default(0)->after('actual_received_amount');
            }
            if (!Schema::hasColumn('supplier_supplies', 'discrepancy_amount')) {
                $table->decimal('discrepancy_amount', 15, 2)->default(0)->after('discrepancy_quantity');
            }
            if (!Schema::hasColumn('supplier_supplies', 'assigned_agent_id')) {
                $table->unsignedBigInteger('assigned_agent_id')->nullable()->index()->after('approved_at');
            }
            if (!Schema::hasColumn('supplier_supplies', 'assigned_agent_point_id')) {
                $table->unsignedBigInteger('assigned_agent_point_id')->nullable()->index()->after('assigned_agent_id');
            }
            if (!Schema::hasColumn('supplier_supplies', 'assigned_branch_id')) {
                $table->unsignedBigInteger('assigned_branch_id')->nullable()->index()->after('assigned_agent_point_id');
            }
            if (!Schema::hasColumn('supplier_supplies', 'destination_type')) {
                $table->string('destination_type', 50)->nullable()->after('assigned_branch_id');
            }
            if (!Schema::hasColumn('supplier_supplies', 'destination_location')) {
                $table->string('destination_location', 255)->nullable()->after('destination_type');
            }
            if (!Schema::hasColumn('supplier_supplies', 'delivery_instructions')) {
                $table->text('delivery_instructions')->nullable()->after('destination_location');
            }
            if (!Schema::hasColumn('supplier_supplies', 'expected_delivery_date')) {
                $table->date('expected_delivery_date')->nullable()->after('delivery_instructions');
            }
            if (!Schema::hasColumn('supplier_supplies', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('expected_delivery_date');
            }
            if (!Schema::hasColumn('supplier_supplies', 'dispatched_at')) {
                $table->timestamp('dispatched_at')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('supplier_supplies', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('dispatched_at');
            }
            if (!Schema::hasColumn('supplier_supplies', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('received_at');
            }
            if (!Schema::hasColumn('supplier_supplies', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('supplier_supplies', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });

        // Change status column from enum to varchar(40) to support all 10 SRD statuses
        DB::statement("ALTER TABLE supplier_supplies MODIFY COLUMN status VARCHAR(40) NOT NULL DEFAULT 'pending'");

        // 4. Supply Line Items (Supports single and multi-item supply requests)
        if (!Schema::hasTable('supplier_supply_items')) {
            Schema::create('supplier_supply_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->string('product_name');
                $table->string('category')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->decimal('quantity', 12, 2);
                $table->string('unit', 50);
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->decimal('rate', 12, 2);
                $table->decimal('total_amount', 15, 2);
                $table->decimal('approved_quantity', 12, 2)->nullable();
                $table->decimal('approved_rate', 12, 2)->nullable();
                $table->decimal('approved_amount', 15, 2)->nullable();
                $table->decimal('received_quantity', 12, 2)->default(0);
                $table->decimal('damaged_quantity', 12, 2)->default(0);
                $table->decimal('missing_quantity', 12, 2)->default(0);
                $table->decimal('received_amount', 15, 2)->default(0);
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 5. Supply Assignments (Admin -> Agent / Branch / Destination)
        if (!Schema::hasTable('supplier_supply_assignments')) {
            Schema::create('supplier_supply_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->unsignedBigInteger('agent_id')->nullable()->index();
                $table->unsignedBigInteger('agent_point_id')->nullable()->index();
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->string('destination_type', 50)->default('branch'); // branch, warehouse, distribution_center, agent_location, specific_address
                $table->string('destination_location', 255)->nullable();
                $table->text('delivery_instructions')->nullable();
                $table->date('expected_delivery_date')->nullable();
                $table->text('admin_note')->nullable();
                $table->string('status', 30)->default('active'); // active, changed, cancelled
                $table->text('cancel_reason')->nullable();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamp('assigned_at')->nullable();
                $table->unsignedBigInteger('cancelled_by')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 6. Agent Product Receiving Records
        if (!Schema::hasTable('supplier_supply_receipts')) {
            Schema::create('supplier_supply_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_no', 50)->unique();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->unsignedBigInteger('agent_id')->index();
                $table->unsignedBigInteger('agent_point_id')->nullable()->index();
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->decimal('approved_quantity', 12, 2)->default(0);
                $table->decimal('actual_received_quantity', 12, 2)->default(0);
                $table->decimal('damaged_quantity', 12, 2)->default(0);
                $table->decimal('missing_quantity', 12, 2)->default(0);
                $table->string('product_condition', 30)->default('good'); // good, damaged, partially_damaged
                $table->string('action_type', 40)->default('confirm_received'); // confirm_received, partial_received, discrepancy, rejected
                $table->date('received_date');
                $table->text('receiving_note')->nullable();
                $table->text('discrepancy_report')->nullable();
                $table->text('reject_reason')->nullable();
                $table->string('photo_path')->nullable();
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 7. Agent Product Receiving Line Items
        if (!Schema::hasTable('supplier_supply_receipt_items')) {
            Schema::create('supplier_supply_receipt_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('receipt_id')->index();
                $table->unsignedBigInteger('supply_item_id')->nullable()->index();
                $table->string('product_name');
                $table->string('unit', 50);
                $table->decimal('approved_quantity', 12, 2)->default(0);
                $table->decimal('received_quantity', 12, 2)->default(0);
                $table->decimal('damaged_quantity', 12, 2)->default(0);
                $table->decimal('missing_quantity', 12, 2)->default(0);
                $table->string('condition', 30)->default('good');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('receipt_id')->references('id')->on('supplier_supply_receipts')->onDelete('cascade');
            });
        }

        // 8. Supply Status Histories
        if (!Schema::hasTable('supplier_supply_status_histories')) {
            Schema::create('supplier_supply_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->string('from_status', 40)->nullable();
                $table->string('to_status', 40);
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->string('changed_by_role', 40)->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 9. Supply Audit Logs
        if (!Schema::hasTable('supplier_supply_audit_logs')) {
            Schema::create('supplier_supply_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_supply_id')->nullable()->index();
                $table->string('action', 80)->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('user_role', 40)->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 10. Supply Attachments
        if (!Schema::hasTable('supplier_supply_attachments')) {
            Schema::create('supplier_supply_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supplier_supply_id')->index();
                $table->string('file_type', 40)->default('invoice'); // invoice, challan, receiving_photo, other
                $table->string('file_path');
                $table->string('original_name')->nullable();
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();

                $table->foreign('supplier_supply_id')->references('id')->on('supplier_supplies')->onDelete('cascade');
            });
        }

        // 11. System Notifications (Supplier, Admin, Agent, Branch)
        if (!Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('target_role', 40)->nullable()->index(); // admin, supplier, agent, branch
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->string('title');
                $table->text('message');
                $table->string('type', 40)->default('info'); // info, success, warning, danger
                $table->string('reference_type', 60)->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('action_url')->nullable();
                $table->boolean('is_read')->default(false)->index();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('supplier_supply_attachments');
        Schema::dropIfExists('supplier_supply_audit_logs');
        Schema::dropIfExists('supplier_supply_status_histories');
        Schema::dropIfExists('supplier_supply_receipt_items');
        Schema::dropIfExists('supplier_supply_receipts');
        Schema::dropIfExists('supplier_supply_assignments');
        Schema::dropIfExists('supplier_supply_items');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_units');
    }
};
