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
        // 1. Corporate Profiles Table
        if (!Schema::hasTable('corporate_profiles')) {
            Schema::create('corporate_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('section_key')->unique();
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->longText('content_bn')->nullable();
                $table->longText('content_en')->nullable();
                $table->string('featured_image')->nullable();
                $table->json('extra_points')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Board Members Table
        if (!Schema::hasTable('board_members')) {
            Schema::create('board_members', function (Blueprint $table) {
                $table->id();
                $table->string('name_bn');
                $table->string('name_en')->nullable();
                $table->string('designation_bn');
                $table->string('designation_en')->nullable();
                $table->enum('role_type', ['chairman', 'managing_director', 'director', 'independent_director', 'advisor', 'management_team'])->default('director');
                $table->text('bio_bn')->nullable();
                $table->text('speech_message')->nullable();
                $table->string('image')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Corporate Activities & Projects Table
        if (!Schema::hasTable('corporate_activities')) {
            Schema::create('corporate_activities', function (Blueprint $table) {
                $table->id();
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->enum('category', ['ongoing', 'completed', 'planned'])->default('ongoing');
                $table->text('description_bn')->nullable();
                $table->string('featured_image')->nullable();
                $table->string('location')->nullable();
                $table->date('start_date')->nullable();
                $table->date('completion_date')->nullable();
                $table->decimal('budget', 15, 2)->nullable();
                $table->integer('progress_percentage')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Investment Sectors Table
        if (!Schema::hasTable('investment_sectors')) {
            Schema::create('investment_sectors', function (Blueprint $table) {
                $table->id();
                $table->string('name_bn');
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->text('short_description_bn')->nullable();
                $table->longText('full_description_bn')->nullable();
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->string('expected_roi')->nullable();
                $table->string('risk_level')->default('Moderate');
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Investment Projects Table
        if (!Schema::hasTable('investment_projects')) {
            Schema::create('investment_projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sector_id')->constrained('investment_sectors')->onDelete('cascade');
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->text('description_bn')->nullable();
                $table->decimal('target_amount', 15, 2)->default(0.00);
                $table->decimal('raised_amount', 15, 2)->default(0.00);
                $table->decimal('minimum_investment', 15, 2)->default(1000.00);
                $table->string('expected_return')->nullable();
                $table->string('duration')->nullable();
                $table->enum('status', ['upcoming', 'open', 'ongoing', 'closed'])->default('open');
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // 6. User Investments Table
        if (!Schema::hasTable('user_investments')) {
            Schema::create('user_investments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained('investment_projects')->onDelete('set null');
                $table->decimal('amount', 15, 2);
                $table->string('transaction_id')->nullable();
                $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
                $table->date('invested_at')->nullable();
                $table->timestamps();
            });
        }

        // 7. Investment Withdrawals Table
        if (!Schema::hasTable('investment_withdrawals')) {
            Schema::create('investment_withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('amount', 15, 2);
                $table->string('payment_method')->nullable();
                $table->text('payment_details')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }

        // 8. Investment Dividends Table
        if (!Schema::hasTable('investment_dividends')) {
            Schema::create('investment_dividends', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('amount', 15, 2);
                $table->string('title')->nullable();
                $table->date('distribution_date')->nullable();
                $table->timestamps();
            });
        }

        // 9. Share Settings Table
        if (!Schema::hasTable('share_settings')) {
            Schema::create('share_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('total_shares')->default(100);
                $table->decimal('price_per_share', 15, 2)->default(5000.00);
                $table->decimal('total_share_capital', 15, 2)->default(500000.00);
                $table->decimal('office_expense_per_share', 15, 2)->default(3000.00);
                $table->decimal('registration_fee', 15, 2)->default(2000.00);
                $table->integer('allocated_shares')->default(0);
                $table->timestamps();
            });
        }

        // 10. Shareholder Profiles Table
        if (!Schema::hasTable('shareholder_profiles')) {
            Schema::create('shareholder_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('shareholder_id')->unique();
                $table->integer('shares_count')->default(1);
                $table->decimal('total_investment', 15, 2)->default(0.00);
                $table->string('nominee_name')->nullable();
                $table->string('nominee_relation')->nullable();
                $table->string('nominee_phone')->nullable();
                $table->string('nominee_nid')->nullable();
                $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }

        // 11. Partnerships Table
        if (!Schema::hasTable('partnerships')) {
            Schema::create('partnerships', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('contact_person');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->string('business_type')->nullable();
                $table->text('proposal')->nullable();
                $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }

        // 12. Share Transactions Table
        if (!Schema::hasTable('share_transactions')) {
            Schema::create('share_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->integer('shares_count');
                $table->decimal('amount', 15, 2);
                $table->string('transaction_type')->default('buy');
                $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
                $table->timestamps();
            });
        }

        // 13. Notices Table
        if (!Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->id();
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->enum('notice_type', ['general', 'corporate', 'shareholder', 'urgent'])->default('general');
                $table->longText('description_bn')->nullable();
                $table->string('attachment')->nullable();
                $table->date('publish_date')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 14. Central Finances Table
        if (!Schema::hasTable('central_finances')) {
            Schema::create('central_finances', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type', ['income', 'expense'])->default('income');
                $table->string('category')->nullable();
                $table->decimal('amount', 15, 2);
                $table->date('transaction_date');
                $table->string('reference')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 15. Audit Logs Table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->string('module')->nullable();
                $table->text('description')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('central_finances');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('share_transactions');
        Schema::dropIfExists('partnerships');
        Schema::dropIfExists('shareholder_profiles');
        Schema::dropIfExists('share_settings');
        Schema::dropIfExists('investment_dividends');
        Schema::dropIfExists('investment_withdrawals');
        Schema::dropIfExists('user_investments');
        Schema::dropIfExists('investment_projects');
        Schema::dropIfExists('investment_sectors');
        Schema::dropIfExists('corporate_activities');
        Schema::dropIfExists('board_members');
        Schema::dropIfExists('corporate_profiles');
    }
};

