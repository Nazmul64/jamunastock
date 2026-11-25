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
        // 1. Permission Registries (Catalog of all Modules, Menus, Submenus, Actions & Routes)
        if (!Schema::hasTable('permission_registries')) {
            Schema::create('permission_registries', function (Blueprint $table) {
                $table->id();
                $table->string('module_key', 50)->index();
                $table->string('module_name', 100);
                $table->string('menu_key', 50)->index();
                $table->string('menu_name', 100);
                $table->string('submenu_key', 50)->nullable()->index();
                $table->string('submenu_name', 100)->nullable();
                $table->string('page_key', 50)->nullable();
                $table->string('action_key', 50)->default('view'); // view, view_details, create, edit, delete, restore, approve, reject, cancel, assign, transfer, import, export, print, download, settings
                $table->string('action_name', 100);
                $table->string('permission_key', 100)->unique(); // e.g. hrm.attendances.create, support.tickets.reply
                $table->string('route_name', 150)->nullable();
                $table->string('http_method', 20)->default('GET');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_retired')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Permission Audit Logs
        if (!Schema::hasTable('permission_audit_logs')) {
            Schema::create('permission_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('performed_by')->nullable()->index();
                $table->string('performer_name', 150)->nullable();
                $table->string('target_type', 50); // 'role', 'user', 'registry'
                $table->unsignedBigInteger('target_id')->nullable()->index();
                $table->string('target_name', 150)->nullable();
                $table->string('event_type', 50); // 'role_created', 'role_updated', 'role_cloned', 'role_deleted', 'permissions_synced', 'user_assigned', 'password_reset'
                $table->text('details')->nullable(); // JSON of changed permissions, old vs new
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('reason', 255)->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 3. Extend `roles` table if necessary
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'category')) {
                $table->string('category', 50)->default('custom')->after('guard_name');
            }
            if (!Schema::hasColumn('roles', 'data_scope')) {
                $table->string('data_scope', 50)->default('all')->after('category');
            }
            if (!Schema::hasColumn('roles', 'status')) {
                $table->string('status', 20)->default('active')->after('data_scope');
            }
        });

        // 4. Extend `user_roles` table for granular ABAC assignment & expiry
        Schema::table('user_roles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_roles', 'data_scope')) {
                $table->string('data_scope', 50)->default('inherited')->nullable();
            }
            if (!Schema::hasColumn('user_roles', 'scope_id')) {
                $table->unsignedBigInteger('scope_id')->nullable();
            }
            if (!Schema::hasColumn('user_roles', 'expires_at')) {
                $table->dateTime('expires_at')->nullable();
            }
            if (!Schema::hasColumn('user_roles', 'created_at')) {
                $table->timestamps();
            }
        });

        // 5. Extend `rbac_permissions` for tree hierarchy
        Schema::table('rbac_permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('rbac_permissions', 'menu')) {
                $table->string('menu', 100)->nullable()->after('module');
            }
            if (!Schema::hasColumn('rbac_permissions', 'submenu')) {
                $table->string('submenu', 100)->nullable()->after('menu');
            }
            if (!Schema::hasColumn('rbac_permissions', 'page')) {
                $table->string('page', 100)->nullable()->after('submenu');
            }
            if (!Schema::hasColumn('rbac_permissions', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
            if (!Schema::hasColumn('rbac_permissions', 'is_retired')) {
                $table->boolean('is_retired')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('rbac_permissions', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_retired');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_audit_logs');
        Schema::dropIfExists('permission_registries');
    }
};
