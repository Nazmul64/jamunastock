<?php

use App\Http\Controllers\Admin\AdminAgentPointController;
use App\Http\Controllers\Admin\AdminAgentLedgerController;
use App\Http\Controllers\Admin\AdminCardBenefitController;
use App\Http\Controllers\Admin\AdminCardNumberController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminDepositController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\Admin\AdminCareerController;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\Admin\AdminTeamMemberController;
use App\Http\Controllers\Admin\AdminCorporateController;
use App\Http\Controllers\Admin\AdminInvestmentController;
use App\Http\Controllers\Admin\Investment\AdminEnterpriseInvestmentController;
use App\Http\Controllers\Admin\Investment\AdminInvestmentInquiryController;
use App\Http\Controllers\Frontend\PublicInvestmentInquiryController;
use App\Http\Controllers\Admin\AdminShareholderController;
use App\Http\Controllers\Admin\AdminBillsVouchersController;
use App\Http\Controllers\Admin\AdminFinanceController;
use App\Http\Controllers\Admin\AdminNoticeController;
use App\Http\Controllers\Admin\AdminStoreAssetController;
use App\Http\Controllers\Admin\AdminRolePermissionController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminOurPackageController;
use App\Http\Controllers\Admin\AdminMonthlyBazaarController;
use App\Http\Controllers\Admin\AdminPaymentSystemController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminStockPresetController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\Admin\HRM\HrmAnnouncementController;
use App\Http\Controllers\Admin\HRM\HrmAssetController;
use App\Http\Controllers\Admin\HRM\HrmAttendanceController;
use App\Http\Controllers\Admin\HRM\HrmBiometricDeviceController;
use App\Http\Controllers\Admin\HRM\HrmBranchController;
use App\Http\Controllers\Admin\HRM\HrmDashboardController;
use App\Http\Controllers\Admin\HRM\HrmDepartmentController;
use App\Http\Controllers\Admin\HRM\HrmDesignationController;
use App\Http\Controllers\Admin\HRM\HrmEmployeeManagementController;
use App\Http\Controllers\Admin\HRM\HrmEmployeeSelfServiceController;
use App\Http\Controllers\Admin\HRM\HrmHolidayController;
use App\Http\Controllers\Admin\HRM\HrmLeaveController;
use App\Http\Controllers\Admin\HRM\HrmLoanAdvanceController;
use App\Http\Controllers\Admin\HRM\HrmPayrollController;
use App\Http\Controllers\Admin\HRM\HrmPerformanceController;
use App\Http\Controllers\Admin\HRM\HrmPolicyController;
use App\Http\Controllers\Admin\HRM\HrmRecruitmentController;
use App\Http\Controllers\Admin\HRM\HrmReportController;
use App\Http\Controllers\Admin\HRM\HrmShiftController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeStockLedgerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSearchController;
use App\Http\Controllers\Supplier\SupplierSupplyController;
use App\Http\Controllers\Users\UserCartController;
use App\Http\Controllers\Users\UserDepositController;
use App\Http\Controllers\Users\UserMonthlyBazaarController;
use App\Http\Controllers\Users\UserPaymentSystemController;
use App\Http\Controllers\Users\UserStockController;
use App\Http\Controllers\Users\UserWalletController;
use App\Http\Controllers\Users\WithdrawController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('frontend.index');})->name('/');
Route::get('/about', [CorporateController::class, 'about'])->name('about');
Route::get('/corporate-profile', [CorporateController::class, 'about'])->name('corporate.about');
Route::get('/board-of-directors', [CorporateController::class, 'boardMembers'])->name('corporate.board');
Route::get('/team-members', [TeamMemberController::class, 'index'])->name('team.members');
Route::get('/our-team', [TeamMemberController::class, 'index'])->name('our.team');
Route::get('/leadership-speeches', [CorporateController::class, 'leadershipSpeeches'])->name('corporate.speeches');
Route::get('/projects-activities', [CorporateController::class, 'activities'])->name('corporate.activities');
Route::get('/projects-activities/{id}', [CorporateController::class, 'activityDetail'])->name('corporate.activity_detail');

Route::get('/membership-cards', [MembershipCardController::class, 'index'])->name('membership.cards');
Route::post('/membership-cards/apply', [MembershipCardController::class, 'apply'])->name('membership.apply');

Route::get('/investment-sectors', [InvestmentController::class, 'sectors'])->name('investments.sectors');
Route::get('/investment-sectors/{id}', [InvestmentController::class, 'sectorDetail'])->name('investments.sector_detail');
Route::get('/investment-projects', [InvestmentController::class, 'projects'])->name('investments.projects');
Route::get('/investment-projects/{id}', [InvestmentController::class, 'projectDetail'])->name('investments.project_detail');
Route::post('/investment-projects/invest', [InvestmentController::class, 'storeInvestment'])->name('investments.invest_post');

// Enterprise 9-Sectors Frontend Showcase & Online Inquiries (Sections 11, 12, 13)
Route::get('/investments/showcase', [PublicInvestmentInquiryController::class, 'sectors'])->name('frontend.investments.showcase');
Route::get('/investment/{slug}', [PublicInvestmentInquiryController::class, 'sectorDetail'])->name('frontend.investments.sector_detail');
Route::post('/investment/inquiry/submit', [PublicInvestmentInquiryController::class, 'submitInquiry'])->name('frontend.investments.inquiry.submit');

Route::get('/shareholder-overview', [ShareholderController::class, 'index'])->name('shareholders.index');
Route::post('/shareholder-overview/apply', [ShareholderController::class, 'apply'])->name('shareholders.apply');
Route::get('/partnerships', [ShareholderController::class, 'partnerships'])->name('shareholders.partnerships');
Route::post('/partnerships/inquiry', [ShareholderController::class, 'storePartnershipInquiry'])->name('shareholders.partnerships.inquiry');

Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{id}', [NoticeController::class, 'show'])->name('notices.show');
Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery');
Route::get('/videos', [\App\Http\Controllers\VideoGalleryController::class, 'index'])->name('videos.index');
Route::get('/our-videos', [\App\Http\Controllers\VideoGalleryController::class, 'index'])->name('videos.alias');
Route::get('/contact', function () {return view('frontend.contact');})->name('contact');

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['bn', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Frontend Blog Routes
Route::get('/blogs', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blogs.show');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.send');
Route::get('/career', [CareerController::class, 'index'])->name('career');
Route::post('/career/apply', [CareerController::class, 'store'])->name('career.apply');
Route::get('/terms', function () {return view('frontend.terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('frontend.privacy');})->name('privacy');
Route::get('/stock-market', [PublicSearchController::class, 'stocks'])->name('frontend.stocks');
Route::get('/search', [PublicSearchController::class, 'search'])->name('frontend.search');
Route::get('/api/live-search', [PublicSearchController::class, 'liveSearch'])->name('api.live_search');

Auth::routes();
// Dedicated Role Login Routes
Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::get('/supplier/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('supplier.login');
Route::get('/agent/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('agent.login');
Route::get('/employee/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('employee.login');
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('register.referlink');
Route::post('/user/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Supplier Self Registration Routes
Route::get('/supplier/register', [RegisterController::class, 'showSupplierRegistrationForm'])->name('supplier.register');
Route::post('/supplier/register', [RegisterController::class, 'supplierRegisterPost'])->name('supplier.register.post');




// Common Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('profile', 'App\Http\Controllers\ProfileController');
Route::post('/profile/change', [ProfileController::class, 'changePasswordPost'])->name('profile.password.change');


// Admin Route
Route::group(['prefix' => 'admin', 'middleware' => 'AdminCheck'], function () {
    Route::get('/users', [AdminUserController::class, 'alluser'])->name('alluser');
    Route::post('/user/unlock-balance/{id}', [AdminUserController::class, 'unlockBalance'])->name('admin.user.unlock_balance');
    Route::post('/user/lock-balance/{id}', [AdminUserController::class, 'lockBalance'])->name('admin.user.lock_balance');
    Route::get('/user/destroy/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.user.destroy');

    // 12-Digit Number Generator Routes
    Route::get('/card-numbers', [AdminCardNumberController::class, 'index'])->name('admin.card_numbers.index');
    Route::post('/card-numbers/store', [AdminCardNumberController::class, 'store'])->name('admin.card_numbers.store');
    Route::post('/card-numbers/update/{id}', [AdminCardNumberController::class, 'update'])->name('admin.card_numbers.update');
    Route::get('/card-numbers/destroy/{id}', [AdminCardNumberController::class, 'destroy'])->name('admin.card_numbers.destroy');

    Route::get('/create-employee/', [AdminEmployeeController::class, 'index'])->name('admin.employee.index');
    Route::post('/create-employee/post', [AdminEmployeeController::class, 'store'])->name('admin.employee.post');
    Route::post('/create-employee/destroy/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.employee.destroy');
    Route::get('/employee/edit/{id}', [AdminEmployeeController::class, 'Edit'])->name('admin.employee.edit');
    Route::post('/employee/edit/', [AdminEmployeeController::class, 'EditPost'])->name('admin.employee.edit.post');
    Route::get('/employee/view/{id}', [AdminEmployeeController::class, 'ViewEmployee'])->name('admin.employee.view');
    Route::get('/employee/referal/view/{id}', [AdminEmployeeController::class, 'ViewReferalUser'])->name('admin.employee.referaluser');
    Route::get('/employee/permissions/{id}', [AdminEmployeeController::class, 'managePermissions'])->name('admin.employee.permissions');
    Route::post('/employee/permissions/update/{id}', [AdminEmployeeController::class, 'updatePermissions'])->name('admin.employee.permissions.update');

    // Marketing Agent Live Stock & Ledger Routes
    Route::get('/agent-ledger', [AdminAgentLedgerController::class, 'index'])->name('admin.agent_ledger.index');
    Route::get('/agent-ledger/view/{agent_id}', [AdminAgentLedgerController::class, 'show'])->name('admin.agent_ledger.show');
    Route::post('/agent-ledger/allocate', [AdminAgentLedgerController::class, 'allocateStock'])->name('admin.agent_ledger.allocate');



    Route::get('/setting', [SiteSettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/update', [SiteSettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/logo/delete', [SiteSettingController::class, 'deleteLogo'])->name('setting.logo.delete');
    Route::get('/setting/favicon/delete', [SiteSettingController::class, 'deleteFavicon'])->name('setting.favicon.delete');
    Route::get('/setting/slider', [SiteSettingController::class, 'SliderSetting'])->name('setting.slider');
    Route::post('/setting/slider/post', [SiteSettingController::class, 'SliderSettingPost'])->name('setting.slider.post');
    Route::get('/setting/slider/delete/{num}', [SiteSettingController::class, 'SliderDelete'])->name('setting.slider.delete');
    Route::get('/setting/slider/delete-all', [SiteSettingController::class, 'deleteAllSliders'])->name('setting.slider.delete_all');
    Route::get('/setting/offer-banner', [SiteSettingController::class, 'OfferBannerSetting'])->name('setting.offer_banner');
    Route::post('/setting/offer-banner/post', [SiteSettingController::class, 'OfferBannerPost'])->name('setting.offer_banner.post');
    Route::get('/setting/offer-banner/delete', [SiteSettingController::class, 'OfferBannerDelete'])->name('setting.offer_banner.delete');

    // ==========================================
    // PHOTO GALLERY MANAGEMENT (public/uploads/gallery)
    // ==========================================
    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'index'])->name('admin.gallery.index');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'store'])->name('admin.gallery.store');
        Route::post('/update/{id}', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'update'])->name('admin.gallery.update');
        Route::get('/toggle/{id}', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'toggleStatus'])->name('admin.gallery.toggle');
        Route::match(['POST', 'DELETE', 'GET'], '/destroy/{id}', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'destroy'])->name('admin.gallery.destroy');
        Route::match(['POST', 'DELETE'], '/bulk-delete', [\App\Http\Controllers\Admin\AdminGalleryController::class, 'bulkDelete'])->name('admin.gallery.bulk_delete');
    });

    // ==========================================
    // VIDEO SECTION MANAGEMENT (YouTube Videos)
    // ==========================================
    Route::group(['prefix' => 'videos'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'index'])->name('admin.videos.index');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'store'])->name('admin.videos.store');
        Route::post('/update/{id}', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'update'])->name('admin.videos.update');
        Route::get('/toggle/{id}', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'toggleStatus'])->name('admin.videos.toggle');
        Route::match(['POST', 'DELETE', 'GET'], '/destroy/{id}', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'destroy'])->name('admin.videos.destroy');
        Route::match(['POST', 'DELETE'], '/bulk-delete', [\App\Http\Controllers\Admin\AdminVideoGalleryController::class, 'bulkDelete'])->name('admin.videos.bulk_delete');
    });

    // ==========================================
    // A-Z BRANCH MANAGEMENT POLICY & OPERATIONAL SYSTEM
    // ==========================================
    Route::group(['prefix' => 'branch-management'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'dashboard'])->name('admin.branch.dashboard');
        Route::get('/profile', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'branchProfile'])->name('admin.branch.profile');
        Route::post('/profile/update', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'updateBranchProfile'])->name('admin.branch.profile.update');
        
        Route::get('/tasks', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'tasks'])->name('admin.branch.tasks');
        Route::post('/tasks/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeTask'])->name('admin.branch.tasks.store');
        Route::post('/tasks/update/{id}', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'updateTaskStatus'])->name('admin.branch.tasks.update');

        Route::get('/commissions', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'commissions'])->name('admin.branch.commissions');
        Route::post('/commissions/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeCommission'])->name('admin.branch.commissions.store');

        Route::get('/daily-reports', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'dailyReports'])->name('admin.branch.daily_reports');
        Route::get('/daily-reports/create', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'createDailyReport'])->name('admin.branch.daily_reports.create');
        Route::post('/daily-reports/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeDailyReport'])->name('admin.branch.daily_reports.store');
        Route::get('/daily-reports/show/{id}', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'showDailyReport'])->name('admin.branch.daily_reports.show');

        Route::get('/employees', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'employees'])->name('admin.branch.employees');
        Route::post('/employees/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeEmployee'])->name('admin.branch.employees.store');

        Route::get('/cashbook', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'cashbook'])->name('admin.branch.cashbook');
        Route::post('/income/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeIncome'])->name('admin.branch.income.store');
        Route::post('/expense/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeExpense'])->name('admin.branch.expense.store');

        Route::get('/attendance', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'attendance'])->name('admin.branch.attendance');
        Route::post('/attendance/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeAttendance'])->name('admin.branch.attendance.store');

        Route::get('/inventory', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'inventory'])->name('admin.branch.inventory');
        Route::post('/inventory/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeAsset'])->name('admin.branch.inventory.store');

        Route::get('/kpi', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'kpiPerformance'])->name('admin.branch.kpi');

        Route::get('/membership', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'membership'])->name('admin.branch.membership');
        Route::post('/membership/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeMembership'])->name('admin.branch.membership.store');

        Route::get('/business-leads', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'businessLeads'])->name('admin.branch.business_leads');
        Route::post('/business-leads/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeBusinessLead'])->name('admin.branch.business_leads.store');

        Route::get('/property', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'property'])->name('admin.branch.property');
        Route::get('/sales', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'sales'])->name('admin.branch.sales');

        Route::get('/complaints', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'complaints'])->name('admin.branch.complaints');
        Route::post('/complaints/store', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'storeComplaint'])->name('admin.branch.complaints.store');
        Route::post('/complaints/resolve/{id}', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'resolveComplaint'])->name('admin.branch.complaints.resolve');

        Route::get('/documents', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'documents'])->name('admin.branch.documents');

        Route::get('/policy-az', [\App\Http\Controllers\Admin\Branch\BranchDirectorController::class, 'policyAZ'])->name('admin.branch.policy_az');
    });

    // ==========================================
    // THEME & COLOR CUSTOMIZER
    // ==========================================
    Route::group(['prefix' => 'settings/theme'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminThemeSettingsController::class, 'index'])->name('admin.settings.theme.index');
        Route::post('/update', [\App\Http\Controllers\Admin\AdminThemeSettingsController::class, 'update'])->name('admin.settings.theme.update');
        Route::post('/reset', [\App\Http\Controllers\Admin\AdminThemeSettingsController::class, 'reset'])->name('admin.settings.theme.reset');
    });

    // ==========================================
    // BLOG MANAGEMENT (Categories & Posts)
    // ==========================================
    Route::group(['prefix' => 'blogs'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminBlogController::class, 'index'])->name('admin.blogs.index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminBlogController::class, 'create'])->name('admin.blogs.create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminBlogController::class, 'store'])->name('admin.blogs.store');
        Route::get('/edit/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'edit'])->name('admin.blogs.edit');
        Route::put('/update/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'update'])->name('admin.blogs.update');
        Route::delete('/destroy/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy');
        Route::get('/toggle/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'toggleStatus'])->name('admin.blogs.toggle');

        Route::get('/categories', [\App\Http\Controllers\Admin\AdminBlogController::class, 'categories'])->name('admin.blogs.categories.index');
        Route::post('/categories/store', [\App\Http\Controllers\Admin\AdminBlogController::class, 'storeCategory'])->name('admin.blogs.categories.store');
        Route::put('/categories/update/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'updateCategory'])->name('admin.blogs.categories.update');
        Route::delete('/categories/destroy/{id}', [\App\Http\Controllers\Admin\AdminBlogController::class, 'destroyCategory'])->name('admin.blogs.categories.destroy');
    });

    Route::get('/features', [AdminFeatureController::class, 'index'])->name('admin.feature.index');
    Route::post('/features/store', [AdminFeatureController::class, 'store'])->name('admin.feature.store');
    Route::get('/features/edit/{id}', [AdminFeatureController::class, 'edit'])->name('admin.feature.edit');
    Route::post('/features/update/{id}', [AdminFeatureController::class, 'update'])->name('admin.feature.update');
    Route::get('/features/delete/{id}', [AdminFeatureController::class, 'destroy'])->name('admin.feature.delete');

    Route::get('/payment-system', [AdminPaymentSystemController::class, 'index'])->name('admin.payment.index');
    Route::post('/payment-system/post', [AdminPaymentSystemController::class, 'store'])->name('admin.payment.post');
    Route::get('/payment-system/destroy/{id}', [AdminPaymentSystemController::class, 'destroy'])->name('admin.payment.destroy');

    // Agent Points (সংগ্রহ এজেন্ট পয়েন্টসমূহ) Routes
    Route::get('/agent-points', [AdminAgentPointController::class, 'index'])->name('admin.agent_points.index');
    Route::post('/agent-points/store', [AdminAgentPointController::class, 'store'])->name('admin.agent_points.store');
    Route::get('/agent-points/destroy/{id}', [AdminAgentPointController::class, 'destroy'])->name('admin.agent_points.destroy');




    Route::get('/all/withdraw', [AdminWithdrawController::class, 'index'])->name('admin.all.withdraw');
    Route::get('/all/withdraw/request', [AdminWithdrawController::class, 'WithdrawRequset'])->name('admin.all.withdraw.request');
    Route::get('/withdraw/aprove/{id}', [AdminWithdrawController::class, 'AprovedWithdraw'])->name('admin.all.withdraw.aprove');
    Route::get('/withdraw/reject/{id}', [AdminWithdrawController::class, 'RejectedWithdraw'])->name('admin.all.withdraw.reject');






    Route::get('/stocks', [AdminStockController::class, 'index'])->name('admin.stock.index');
    Route::post('/stock/post', [AdminStockController::class, 'store'])->name('admin.stock.post');
    Route::get('/stock-presets', [AdminStockPresetController::class, 'index'])->name('admin.stock_preset.index');
    Route::post('/stock-presets/store', [AdminStockPresetController::class, 'store'])->name('admin.stock_preset.store');
    Route::get('/stock-presets/edit/{id}', [AdminStockPresetController::class, 'edit'])->name('admin.stock_preset.edit');
    Route::post('/stock-presets/update/{id}', [AdminStockPresetController::class, 'update'])->name('admin.stock_preset.update');
    Route::get('/stock-presets/destroy/{id}', [AdminStockPresetController::class, 'destroy'])->name('admin.stock_preset.destroy');
    Route::get('/stock/pricing-list', [AdminStockController::class, 'list'])->name('admin.stock.list');
    Route::get('/stock/pricing/{id}', [AdminStockController::class, 'pricing'])->name('admin.stock.add.price');
    Route::post('/stock/pricing/post', [AdminStockController::class, 'pricingPost'])->name('admin.stock.pricing.post');

    Route::get('/stock/image/delete/{id}', [AdminStockController::class, 'imageDelete'])->name('admin.stock.image.delete');



    Route::get('/stock/allStock', [AdminStockController::class, 'allStock'])->name('admin.stock.allStock');
    Route::get('/stock/detials/{id}', [AdminStockController::class, 'adminStockDetials'])->name('admin.stock.detials');
    Route::get('/stock/edit/{id}', [AdminStockController::class, 'adminStockEdit'])->name('admin.stock.edit');
    Route::post('/stock/edit/post/{id}', [AdminStockController::class, 'adminStockEditPost'])->name('admin.stock.edit.post');
    Route::get('/stock/delete/{id}', [AdminStockController::class, 'stockDelete'])->name('admin.stock.delete');


    Route::get('/buy-request-stock/list', [AdminStockController::class, 'ByuRequestList'])->name('admin.stock.buyrequest.list');
    Route::get('/buy-request-stock/aproved/{id}', [AdminStockController::class, 'ByuRequestAproved'])->name('admin.stock.buyrequest.aproved');
    Route::get('/buy-request-stock/rejected/{id}', [AdminStockController::class, 'ByuRequestrejected'])->name('admin.stock.buyrequest.rejected');
    Route::get('/buy-stock/list', [AdminStockController::class, 'ByuList'])->name('admin.buy.stock.list');

    Route::get('/sell-request-stock/list', [AdminStockController::class, 'SellRequestList'])->name('admin.stock.sellrequest.list');
    Route::get('/sell-request-stock/aproved/{id}', [AdminStockController::class, 'SellRequestAproved'])->name('admin.stock.sellrequest.aproved');
    Route::get('/sell-request-stock/rejected/{id}', [AdminStockController::class, 'SellRequestRejected'])->name('admin.stock.sellrequest.rejected');
    Route::get('/sell-stock/list', [AdminStockController::class, 'SellList'])->name('admin.sell.stock.list');

    // Monthly Bazaar Admin Routes
    Route::get('/monthly-bazaar', [AdminMonthlyBazaarController::class, 'index'])->name('admin.monthly_bazaar.index');
    Route::post('/monthly-bazaar/store', [AdminMonthlyBazaarController::class, 'store'])->name('admin.monthly_bazaar.store');
    Route::get('/monthly-bazaar/edit/{id}', [AdminMonthlyBazaarController::class, 'edit'])->name('admin.monthly_bazaar.edit');
    Route::post('/monthly-bazaar/update/{id}', [AdminMonthlyBazaarController::class, 'update'])->name('admin.monthly_bazaar.update');
    Route::get('/monthly-bazaar/destroy/{id}', [AdminMonthlyBazaarController::class, 'destroy'])->name('admin.monthly_bazaar.destroy');

    Route::get('/monthly-bazaar/orders', [AdminMonthlyBazaarController::class, 'orders'])->name('admin.monthly_bazaar.orders');
    Route::get('/monthly-bazaar/order/approve/{id}', [AdminMonthlyBazaarController::class, 'approveOrder'])->name('admin.monthly_bazaar.order.approve');
    Route::get('/monthly-bazaar/order/reject/{id}', [AdminMonthlyBazaarController::class, 'rejectOrder'])->name('admin.monthly_bazaar.order.reject');
    Route::post('/monthly-bazaar/order/update-allocation/{id}', [AdminMonthlyBazaarController::class, 'updateAllocation'])->name('admin.monthly_bazaar.order.update_allocation');
    Route::get('/monthly-bazaar/distribution-reports', [AdminMonthlyBazaarController::class, 'distributionReports'])->name('admin.monthly_bazaar.distribution_reports');

    // Dynamic Area Management Routes (Section 3)
    Route::get('/areas', [\App\Http\Controllers\Admin\AdminAreaController::class, 'index'])->name('admin.areas.index');
    Route::post('/areas/store', [\App\Http\Controllers\Admin\AdminAreaController::class, 'store'])->name('admin.areas.store');
    Route::post('/areas/update/{id}', [\App\Http\Controllers\Admin\AdminAreaController::class, 'update'])->name('admin.areas.update');
    Route::get('/areas/destroy/{id}', [\App\Http\Controllers\Admin\AdminAreaController::class, 'destroy'])->name('admin.areas.destroy');

    // Dedicated Agent Management Module (SRS Section 33.2, 33.8, 33.9)
    Route::get('/agents', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'index'])->name('admin.agents.index');
    Route::get('/agents/create', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'create'])->name('admin.agents.create');
    Route::post('/agents/store', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'store'])->name('admin.agents.store');
    Route::get('/agents/{id}/edit', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'edit'])->name('admin.agents.edit');
    Route::put('/agents/{id}', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'update'])->name('admin.agents.update');
    Route::get('/agents/{id}/toggle-status', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'toggleStatus'])->name('admin.agents.toggle_status');
    Route::get('/agents/{id}', [\App\Http\Controllers\Admin\AdminAgentManagementController::class, 'show'])->name('admin.agents.show');

    // Central Distribution & Agent Monitoring Ecosystem Routes (SRS Sections 7, 10, 11, 17, 18, 20, 24)
    Route::get('/agent-monitoring', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'agentMonitoring'])->name('admin.agent_monitoring');
    Route::get('/agent-monitoring/index', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'agentMonitoring'])->name('admin.agent_monitoring.index');
    Route::get('/agent-monitoring/details/{id}', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'agentDetails'])->name('admin.agent_monitoring.details');
    Route::get('/distribution/demand', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'demandIndex'])->name('admin.distribution.demand');
    Route::get('/distribution/allocate', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'allocateIndex'])->name('admin.distribution.allocate');
    Route::post('/distribution/dispatch/store', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'storeDispatch'])->name('admin.distribution.dispatch.store');
    Route::post('/distribution/allocate/store', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'storeDispatch'])->name('admin.distribution.allocate.store');
    Route::get('/warehouse/stock', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'warehouseStockIndex'])->name('admin.warehouse.stock');
    Route::get('/distribution/warehouse-stock', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'warehouseStockIndex'])->name('admin.distribution.warehouse_stock');
    Route::post('/warehouse/stock/adjust', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'adjustWarehouseStock'])->name('admin.warehouse.stock.adjust');
    Route::post('/distribution/warehouse-stock/adjust', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'adjustWarehouseStock'])->name('admin.distribution.warehouse_stock.adjust');
    Route::get('/agent-deliveries', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'deliveriesHistory'])->name('admin.agent_deliveries.index');
    Route::get('/distribution/deliveries-history', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'deliveriesHistory'])->name('admin.distribution.deliveries_history');
    Route::get('/distribution/reports', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'reportsIndex'])->name('admin.distribution.reports');
    Route::get('/monthly-closing', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'monthlyClosingIndex'])->name('admin.monthly_closing.index');
    Route::post('/monthly-closing/execute', [\App\Http\Controllers\Admin\AdminDistributionController::class, 'executeMonthlyClosing'])->name('admin.monthly_closing.execute');

    // Deposit System Admin Routes
    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('admin.deposit.index');
    Route::get('/deposit/approve/{id}', [AdminDepositController::class, 'approve'])->name('admin.deposit.approve');
    Route::get('/deposit/reject/{id}', [AdminDepositController::class, 'reject'])->name('admin.deposit.reject');

    // Admin Reports & Analytics Routes
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');

    // Supplier Management Admin Routes
    Route::get('/suppliers', [AdminSupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::get('/suppliers/create', [AdminSupplierController::class, 'create'])->name('admin.suppliers.create');
    Route::post('/suppliers/store', [AdminSupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::get('/suppliers/show/{id}', [AdminSupplierController::class, 'show'])->name('admin.suppliers.show');
    Route::get('/suppliers/verify-supplies', [AdminSupplierController::class, 'pendingSupplies'])->name('admin.suppliers.pending_supplies');
    Route::get('/suppliers/supplies/{id}', [AdminSupplierController::class, 'showSupply'])->name('admin.suppliers.supplies.show');
    Route::post('/suppliers/supplies/{id}/approve-assign', [AdminSupplierController::class, 'approveAndAssign'])->name('admin.suppliers.supplies.approve_assign');
    Route::post('/suppliers/supplies/{id}/cancel-assignment', [AdminSupplierController::class, 'cancelAssignment'])->name('admin.suppliers.supplies.cancel_assignment');
    Route::get('/suppliers/supply/approve/{id}', [AdminSupplierController::class, 'approveSupply'])->name('admin.suppliers.supply.approve');
    Route::get('/suppliers/supply/reject/{id}', [AdminSupplierController::class, 'rejectSupply'])->name('admin.suppliers.supply.reject');
    Route::post('/suppliers/supply/reject/{id}', [AdminSupplierController::class, 'rejectSupply'])->name('admin.suppliers.supply.reject_post');
    Route::get('/suppliers/units', [AdminSupplierController::class, 'unitsIndex'])->name('admin.suppliers.units.index');
    Route::post('/suppliers/units/store', [AdminSupplierController::class, 'unitsStore'])->name('admin.suppliers.units.store');
    Route::post('/suppliers/units/{id}/toggle', [AdminSupplierController::class, 'unitsToggle'])->name('admin.suppliers.units.toggle');
    Route::post('/suppliers/categories/store', [AdminSupplierController::class, 'categoriesStore'])->name('admin.suppliers.categories.store');
    Route::post('/suppliers/categories/{id}/toggle', [AdminSupplierController::class, 'categoriesToggle'])->name('admin.suppliers.categories.toggle');
    Route::get('/suppliers/audit-logs', [AdminSupplierController::class, 'auditLogs'])->name('admin.suppliers.audit_logs');
    Route::get('/suppliers/notifications', [AdminSupplierController::class, 'notificationsIndex'])->name('admin.suppliers.notifications');
    Route::get('/suppliers/notifications/{id}/read', [AdminSupplierController::class, 'markNotificationRead'])->name('admin.suppliers.notifications.read');
    Route::get('/suppliers/notifications/read-all', [AdminSupplierController::class, 'markAllNotificationsRead'])->name('admin.suppliers.notifications.read_all_get');
    Route::post('/suppliers/notifications/read-all', [AdminSupplierController::class, 'markAllNotificationsRead'])->name('admin.suppliers.notifications.read_all');
    Route::get('/suppliers/customer-distributions', [AdminSupplierController::class, 'customerDistributions'])->name('admin.suppliers.customer_distributions');
    Route::get('/suppliers/payments', [AdminSupplierController::class, 'supplierPaymentsIndex'])->name('admin.suppliers.payments.index');
    Route::post('/suppliers/payments/store-allocated', [AdminSupplierController::class, 'storeSupplierPaymentAllocated'])->name('admin.suppliers.payments.store_allocated');
    Route::get('/suppliers/master-accounting', [AdminSupplierController::class, 'masterAccountingDashboard'])->name('admin.suppliers.master_dashboard');
    Route::post('/suppliers/payment/store', [AdminSupplierController::class, 'storePayment'])->name('admin.suppliers.payment.store');
    Route::get('/suppliers/reports', [AdminSupplierController::class, 'reports'])->name('admin.suppliers.reports');
    Route::get('/suppliers/statement/print/{id}', [AdminSupplierController::class, 'printStatement'])->name('admin.suppliers.statement.print');
    Route::get('/suppliers/invoice/print/{id}', [AdminSupplierController::class, 'printInvoice'])->name('admin.suppliers.invoice.print');

    // Our Packages Admin Routes
    Route::get('/our-packages', [AdminOurPackageController::class, 'index'])->name('admin.our_packages.index');
    Route::post('/our-packages/store', [AdminOurPackageController::class, 'store'])->name('admin.our_packages.store');
    Route::get('/our-packages/edit/{id}', [AdminOurPackageController::class, 'edit'])->name('admin.our_packages.edit');
    Route::post('/our-packages/update/{id}', [AdminOurPackageController::class, 'update'])->name('admin.our_packages.update');
    Route::get('/our-packages/destroy/{id}', [AdminOurPackageController::class, 'destroy'])->name('admin.our_packages.destroy');

    // Card Benefits Admin Routes
    Route::get('/card-benefits', [AdminCardBenefitController::class, 'index'])->name('admin.card_benefits.index');
    Route::post('/card-benefits/store', [AdminCardBenefitController::class, 'store'])->name('admin.card_benefits.store');
    Route::get('/card-benefits/edit/{id}', [AdminCardBenefitController::class, 'edit'])->name('admin.card_benefits.edit');
    Route::post('/card-benefits/update/{id}', [AdminCardBenefitController::class, 'update'])->name('admin.card_benefits.update');
    Route::get('/card-benefits/destroy/{id}', [AdminCardBenefitController::class, 'destroy'])->name('admin.card_benefits.delete');
    Route::post('/card-benefits/toggle-status/{id}', [AdminCardBenefitController::class, 'toggleStatus'])->name('admin.card_benefits.toggle_status');

    // Admin Live Chat Support Hub Routes
    Route::get('/chat-support', [\App\Http\Controllers\ChatController::class, 'adminIndex'])->name('admin.chat.index');
    Route::get('/chat-support/session/{sessionId}', [\App\Http\Controllers\ChatController::class, 'adminFetchSession'])->name('admin.chat.session');
    Route::post('/chat-support/reply', [\App\Http\Controllers\ChatController::class, 'adminSendReply'])->name('admin.chat.reply');

    // Admin Contact Messages Routes
    Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact_messages.index');
    Route::get('/contact-messages/show/{id}', [AdminContactMessageController::class, 'show'])->name('admin.contact_messages.show');
    Route::post('/contact-messages/update/{id}', [AdminContactMessageController::class, 'update'])->name('admin.contact_messages.update');
    Route::get('/contact-messages/destroy/{id}', [AdminContactMessageController::class, 'destroy'])->name('admin.contact_messages.destroy');

    // Admin Career Applications Routes
    Route::get('/careers', [AdminCareerController::class, 'index'])->name('admin.careers.index');
    Route::get('/careers/show/{id}', [AdminCareerController::class, 'show'])->name('admin.careers.show');
    Route::post('/careers/update-status/{id}', [AdminCareerController::class, 'updateStatus'])->name('admin.careers.update_status');
    Route::get('/careers/download/{id}', [AdminCareerController::class, 'downloadResume'])->name('admin.careers.download');
    Route::get('/careers/destroy/{id}', [AdminCareerController::class, 'destroy'])->name('admin.careers.destroy');

    // Admin Full HRM (Human Resource Management) System Routes
    Route::group(['prefix' => 'hrm'], function () {
        Route::get('/dashboard', [HrmDashboardController::class, 'index'])->name('admin.hrm.dashboard');

        // Departments
        Route::get('/departments', [HrmDepartmentController::class, 'index'])->name('admin.hrm.departments.index');
        Route::post('/departments/store', [HrmDepartmentController::class, 'store'])->name('admin.hrm.departments.store');
        Route::put('/departments/update/{id}', [HrmDepartmentController::class, 'update'])->name('admin.hrm.departments.update');
        Route::delete('/departments/destroy/{id}', [HrmDepartmentController::class, 'destroy'])->name('admin.hrm.departments.destroy');

        // Designations
        Route::get('/designations', [HrmDesignationController::class, 'index'])->name('admin.hrm.designations.index');
        Route::post('/designations/store', [HrmDesignationController::class, 'store'])->name('admin.hrm.designations.store');
        Route::put('/designations/update/{id}', [HrmDesignationController::class, 'update'])->name('admin.hrm.designations.update');
        Route::delete('/designations/destroy/{id}', [HrmDesignationController::class, 'destroy'])->name('admin.hrm.designations.destroy');

        // Branches
        Route::get('/branches', [HrmBranchController::class, 'index'])->name('admin.hrm.branches.index');
        Route::post('/branches/store', [HrmBranchController::class, 'store'])->name('admin.hrm.branches.store');
        Route::put('/branches/update/{id}', [HrmBranchController::class, 'update'])->name('admin.hrm.branches.update');
        Route::delete('/branches/destroy/{id}', [HrmBranchController::class, 'destroy'])->name('admin.hrm.branches.destroy');

        // Shifts
        Route::get('/shifts', [HrmShiftController::class, 'index'])->name('admin.hrm.shifts.index');
        Route::post('/shifts/store', [HrmShiftController::class, 'store'])->name('admin.hrm.shifts.store');
        Route::put('/shifts/update/{id}', [HrmShiftController::class, 'update'])->name('admin.hrm.shifts.update');
        Route::delete('/shifts/destroy/{id}', [HrmShiftController::class, 'destroy'])->name('admin.hrm.shifts.destroy');

        // Holidays
        Route::get('/holidays', [HrmHolidayController::class, 'index'])->name('admin.hrm.holidays.index');
        Route::post('/holidays/store', [HrmHolidayController::class, 'store'])->name('admin.hrm.holidays.store');
        Route::put('/holidays/update/{id}', [HrmHolidayController::class, 'update'])->name('admin.hrm.holidays.update');
        Route::delete('/holidays/destroy/{id}', [HrmHolidayController::class, 'destroy'])->name('admin.hrm.holidays.destroy');

        // Employees & 9-Stage H2R Lifecycle
        Route::get('/employees', [HrmEmployeeManagementController::class, 'index'])->name('admin.hrm.employees.index');
        Route::post('/employees/store', [HrmEmployeeManagementController::class, 'store'])->name('admin.hrm.employees.store');
        Route::get('/employees/show/{id}', [HrmEmployeeManagementController::class, 'show'])->name('admin.hrm.employees.show');
        Route::put('/employees/update-profile/{id}', [HrmEmployeeManagementController::class, 'updateProfile'])->name('admin.hrm.employees.update_profile');
        Route::post('/employees/{id}/upload-document', [HrmEmployeeManagementController::class, 'uploadDocument'])->name('admin.hrm.employees.upload_document');
        Route::post('/employees/{id}/disciplinary', [HrmEmployeeManagementController::class, 'storeDisciplinary'])->name('admin.hrm.employees.disciplinary');
        Route::post('/employees/{id}/exit-settlement', [HrmEmployeeManagementController::class, 'storeExitSettlement'])->name('admin.hrm.employees.exit_settlement');

        // Biometric Devices & Fingerprint System (ZKTeco, Suprema, uAttend)
        Route::get('/biometric/devices', [HrmBiometricDeviceController::class, 'index'])->name('admin.hrm.biometric.devices');
        Route::post('/biometric/devices/store', [HrmBiometricDeviceController::class, 'store'])->name('admin.hrm.biometric.devices.store');
        Route::put('/biometric/devices/update/{id}', [HrmBiometricDeviceController::class, 'update'])->name('admin.hrm.biometric.devices.update');
        Route::delete('/biometric/devices/destroy/{id}', [HrmBiometricDeviceController::class, 'destroy'])->name('admin.hrm.biometric.devices.destroy');
        Route::post('/biometric/devices/ping/{id}', [HrmBiometricDeviceController::class, 'ping'])->name('admin.hrm.biometric.devices.ping');
        Route::post('/biometric/devices/sync/{id}', [HrmBiometricDeviceController::class, 'sync'])->name('admin.hrm.biometric.devices.sync');
        Route::post('/biometric/devices/upload-usb', [HrmBiometricDeviceController::class, 'uploadUsbLogs'])->name('admin.hrm.biometric.devices.upload_usb');
        Route::get('/biometric/enrollment', [HrmBiometricDeviceController::class, 'enrollmentIndex'])->name('admin.hrm.biometric.enrollment');
        Route::post('/biometric/enrollment/store', [HrmBiometricDeviceController::class, 'storeEnrollment'])->name('admin.hrm.biometric.enrollment.store');
        Route::delete('/biometric/enrollment/destroy/{id}', [HrmBiometricDeviceController::class, 'destroyEnrollment'])->name('admin.hrm.biometric.enrollment.destroy');
        Route::get('/biometric/events', [HrmBiometricDeviceController::class, 'eventsLog'])->name('admin.hrm.biometric.events');

        // Attendance Register, Monthly Matrix, Corrections & Policy
        Route::get('/attendance', [HrmAttendanceController::class, 'index'])->name('admin.hrm.attendance.index');
        Route::get('/attendance/monthly', [HrmAttendanceController::class, 'monthly'])->name('admin.hrm.attendance.monthly');
        Route::post('/attendance/store', [HrmAttendanceController::class, 'store'])->name('admin.hrm.attendance.store');
        Route::get('/attendance/corrections', [HrmAttendanceController::class, 'corrections'])->name('admin.hrm.attendance.corrections');
        Route::put('/attendance/corrections/approve/{id}', [HrmAttendanceController::class, 'approveCorrection'])->name('admin.hrm.attendance.corrections.approve');
        Route::put('/attendance/corrections/reject/{id}', [HrmAttendanceController::class, 'rejectCorrection'])->name('admin.hrm.attendance.corrections.reject');
        Route::get('/attendance/policy', [HrmPolicyController::class, 'index'])->name('admin.hrm.attendance.policy');
        Route::put('/attendance/policy/update', [HrmPolicyController::class, 'update'])->name('admin.hrm.attendance.policy.update');

        // ESS (Admin Preview)
        Route::get('/portal', [HrmEmployeeSelfServiceController::class, 'dashboard'])->name('admin.hrm.ess.dashboard');

        // Leave
        Route::get('/leave', [HrmLeaveController::class, 'index'])->name('admin.hrm.leave.index');
        Route::post('/leave/store-type', [HrmLeaveController::class, 'storeType'])->name('admin.hrm.leave.store_type');
        Route::put('/leave/update-status/{id}', [HrmLeaveController::class, 'updateStatus'])->name('admin.hrm.leave.update_status');

        // Payroll
        Route::get('/payroll', [HrmPayrollController::class, 'index'])->name('admin.hrm.payroll.index');
        Route::post('/payroll/generate', [HrmPayrollController::class, 'generatePayroll'])->name('admin.hrm.payroll.generate');
        Route::put('/payroll/mark-paid/{itemId}', [HrmPayrollController::class, 'markPaid'])->name('admin.hrm.payroll.mark_paid');
        Route::get('/payroll/payslip/{itemId}', [HrmPayrollController::class, 'showPayslip'])->name('admin.hrm.payroll.payslip');

        // Loans & Advance
        Route::get('/loans', [HrmLoanAdvanceController::class, 'index'])->name('admin.hrm.loans.index');
        Route::post('/loans/store-loan', [HrmLoanAdvanceController::class, 'storeLoan'])->name('admin.hrm.loans.store_loan');
        Route::post('/loans/store-advance', [HrmLoanAdvanceController::class, 'storeAdvance'])->name('admin.hrm.loans.store_advance');

        // Recruitment
        Route::get('/recruitment', [HrmRecruitmentController::class, 'index'])->name('admin.hrm.recruitment.index');
        Route::post('/recruitment/store-job', [HrmRecruitmentController::class, 'storeJob'])->name('admin.hrm.recruitment.store_job');
        Route::get('/recruitment/applicants/{jobId}', [HrmRecruitmentController::class, 'applicants'])->name('admin.hrm.recruitment.applicants');
        Route::put('/recruitment/update-applicant-status/{applicantId}', [HrmRecruitmentController::class, 'updateApplicantStatus'])->name('admin.hrm.recruitment.update_applicant_status');

        // Performance
        Route::get('/performance', [HrmPerformanceController::class, 'index'])->name('admin.hrm.performance.index');
        Route::post('/performance/store', [HrmPerformanceController::class, 'store'])->name('admin.hrm.performance.store');

        // Company Assets
        Route::get('/assets', [HrmAssetController::class, 'index'])->name('admin.hrm.assets.index');
        Route::post('/assets/store', [HrmAssetController::class, 'store'])->name('admin.hrm.assets.store');
        Route::put('/assets/assign/{id}', [HrmAssetController::class, 'assign'])->name('admin.hrm.assets.assign');

        // Announcements
        Route::get('/announcements', [HrmAnnouncementController::class, 'index'])->name('admin.hrm.announcements.index');
        Route::post('/announcements/store', [HrmAnnouncementController::class, 'store'])->name('admin.hrm.announcements.store');

        // Reports
        Route::get('/reports', [HrmReportController::class, 'index'])->name('admin.hrm.reports.index');
    });

    // ==========================================
    // 1. CORPORATE & BOARD MANAGEMENT
    // ==========================================
    Route::group(['prefix' => 'corporate'], function () {
        // Board of Directors
        Route::get('/board', [AdminCorporateController::class, 'boardIndex'])->name('admin.corporate.board.index');
        Route::get('/board/create', [AdminCorporateController::class, 'boardCreate'])->name('admin.corporate.board.create');
        Route::post('/board/store', [AdminCorporateController::class, 'boardStore'])->name('admin.corporate.board.store');
        Route::get('/board/edit/{id}', [AdminCorporateController::class, 'boardEdit'])->name('admin.corporate.board.edit');
        Route::post('/board/update/{id}', [AdminCorporateController::class, 'boardUpdate'])->name('admin.corporate.board.update');
        Route::get('/board/destroy/{id}', [AdminCorporateController::class, 'boardDestroy'])->name('admin.corporate.board.destroy');

        // Team Members
        Route::get('/team-members', [AdminTeamMemberController::class, 'index'])->name('admin.team_members.index');
        Route::get('/team-members/create', [AdminTeamMemberController::class, 'create'])->name('admin.team_members.create');
        Route::post('/team-members/store', [AdminTeamMemberController::class, 'store'])->name('admin.team_members.store');
        Route::get('/team-members/edit/{id}', [AdminTeamMemberController::class, 'edit'])->name('admin.team_members.edit');
        Route::post('/team-members/update/{id}', [AdminTeamMemberController::class, 'update'])->name('admin.team_members.update');
        Route::get('/team-members/destroy/{id}', [AdminTeamMemberController::class, 'destroy'])->name('admin.team_members.destroy');

        // Corporate Profile & Speeches
        Route::get('/profile', [AdminCorporateController::class, 'profileIndex'])->name('admin.corporate.profile.index');
        Route::post('/profile/update/{id}', [AdminCorporateController::class, 'profileUpdate'])->name('admin.corporate.profile.update');
        Route::post('/speeches/update/{id}', [AdminCorporateController::class, 'speechUpdate'])->name('admin.corporate.speeches.update');

        // Activities & Field Projects
        Route::get('/activities', [AdminCorporateController::class, 'activitiesIndex'])->name('admin.corporate.activities.index');
        Route::get('/activities/create', [AdminCorporateController::class, 'activitiesCreate'])->name('admin.corporate.activities.create');
        Route::post('/activities/store', [AdminCorporateController::class, 'activitiesStore'])->name('admin.corporate.activities.store');
        Route::get('/activities/edit/{id}', [AdminCorporateController::class, 'activitiesEdit'])->name('admin.corporate.activities.edit');
        Route::post('/activities/update/{id}', [AdminCorporateController::class, 'activitiesUpdate'])->name('admin.corporate.activities.update');
        Route::get('/activities/destroy/{id}', [AdminCorporateController::class, 'activitiesDestroy'])->name('admin.corporate.activities.destroy');
    });

    // ==========================================
    // 2. INVESTMENT MANAGEMENT (10 Sectors, Projects, Dividends)
    // ==========================================
    Route::group(['prefix' => 'investments'], function () {
        // Sectors
        Route::get('/sectors', [AdminInvestmentController::class, 'sectorsIndex'])->name('admin.investments.sectors.index');
        Route::get('/sectors/edit/{id}', [AdminInvestmentController::class, 'sectorsEdit'])->name('admin.investments.sectors.edit');
        Route::post('/sectors/update/{id}', [AdminInvestmentController::class, 'sectorsUpdate'])->name('admin.investments.sectors.update');

        // Projects
        Route::get('/projects', [AdminInvestmentController::class, 'projectsIndex'])->name('admin.investments.projects.index');
        Route::get('/projects/create', [AdminInvestmentController::class, 'projectsCreate'])->name('admin.investments.projects.create');
        Route::post('/projects/store', [AdminInvestmentController::class, 'projectsStore'])->name('admin.investments.projects.store');
        Route::get('/projects/edit/{id}', [AdminInvestmentController::class, 'projectsEdit'])->name('admin.investments.projects.edit');
        Route::post('/projects/update/{id}', [AdminInvestmentController::class, 'projectsUpdate'])->name('admin.investments.projects.update');
        Route::get('/projects/destroy/{id}', [AdminInvestmentController::class, 'projectsDestroy'])->name('admin.investments.projects.destroy');

        // User Investments
        Route::get('/user-investments', [AdminInvestmentController::class, 'userInvestmentsIndex'])->name('admin.investments.user_investments.index');
        Route::post('/user-investments/store', [AdminInvestmentController::class, 'storeUserInvestment'])->name('admin.investments.user_investments.store');
        Route::post('/user-investments/status/{id}', [AdminInvestmentController::class, 'updateInvestmentStatus'])->name('admin.investments.user_investments.status');

        // Dividends
        Route::get('/dividends', [AdminInvestmentController::class, 'dividendsIndex'])->name('admin.investments.dividends.index');
        Route::post('/dividends/store', [AdminInvestmentController::class, 'storeDividend'])->name('admin.investments.dividends.store');
        Route::post('/dividends/mark-paid/{id}', [AdminInvestmentController::class, 'markDividendPaid'])->name('admin.investments.dividends.mark_paid');

        // Withdrawals
        Route::get('/withdrawals', [AdminInvestmentController::class, 'withdrawalsIndex'])->name('admin.investments.withdrawals.index');
        Route::post('/withdrawals/status/{id}', [AdminInvestmentController::class, 'updateWithdrawalStatus'])->name('admin.investments.withdrawals.status');

        // ==========================================
        // ENTERPRISE 9-SECTOR INVESTMENT SYSTEM
        // ==========================================
        // 1. Central Multi-Sector Dashboard (Section 10)
        Route::get('/central-dashboard', [AdminEnterpriseInvestmentController::class, 'centralDashboard'])->name('admin.investments.central.dashboard');

        // 2. Sector-specific Isolated Dashboard (Section 2)
        Route::get('/sector/{slug}', [AdminEnterpriseInvestmentController::class, 'sectorDashboard'])->name('admin.investments.sector.dashboard');
        Route::get('/sector/{slug}/customers', [AdminEnterpriseInvestmentController::class, 'sectorCustomers'])->name('admin.investments.sector.customers');
        Route::get('/customer/{id}', [AdminEnterpriseInvestmentController::class, 'customerProfile'])->name('admin.investments.customer.profile');

        // 3. Official Investments CRUD (Section 4 & 5)
        Route::get('/all/{slug?}', [AdminEnterpriseInvestmentController::class, 'investmentsIndex'])->name('admin.investments.index');
        Route::get('/create/{slug?}', [AdminEnterpriseInvestmentController::class, 'createInvestment'])->name('admin.investments.create');
        Route::post('/store-investment', [AdminEnterpriseInvestmentController::class, 'storeInvestment'])->name('admin.investments.store');
        Route::get('/view/{id}', [AdminEnterpriseInvestmentController::class, 'showInvestment'])->name('admin.investments.show');

        // 4. Profit Management & Installments (Section 7)
        Route::post('/profit/pay/{scheduleId}', [AdminEnterpriseInvestmentController::class, 'payProfitInstallment'])->name('admin.investments.profit.pay');

        // 5. Early Settlement & Maturity (Section 6)
        Route::get('/settlement/preview/{id}', [AdminEnterpriseInvestmentController::class, 'settlementPreview'])->name('admin.investments.settlement.preview');
        Route::post('/settlement/process/{id}', [AdminEnterpriseInvestmentController::class, 'processSettlement'])->name('admin.investments.settlement.process');

        // 6. Official Receipts Registry (Section 8 & 17)
        Route::get('/receipts', [AdminEnterpriseInvestmentController::class, 'receiptsIndex'])->name('admin.investments.receipts.index');
        Route::get('/receipts/view/{id}', [AdminEnterpriseInvestmentController::class, 'viewReceipt'])->name('admin.investments.receipts.view');
        Route::post('/receipts/void/{id}', [AdminEnterpriseInvestmentController::class, 'voidReceipt'])->name('admin.investments.receipts.void');

        // 7. Sector Financial Ledger (Section 9)
        Route::get('/ledger/{slug?}', [AdminEnterpriseInvestmentController::class, 'ledgerIndex'])->name('admin.investments.ledger.index');

        // 8. CRM Inquiry Pipeline (Section 13, 14, 15, 16)
        Route::get('/inquiries', [AdminInvestmentInquiryController::class, 'index'])->name('admin.investments.inquiries.index');
        Route::get('/inquiries/{id}', [AdminInvestmentInquiryController::class, 'show'])->name('admin.investments.inquiries.show');
        Route::post('/inquiries/status/{id}', [AdminInvestmentInquiryController::class, 'updateStatus'])->name('admin.investments.inquiries.update_status');
        Route::get('/inquiries/convert/{id}', [AdminInvestmentInquiryController::class, 'convertToInvestment'])->name('admin.investments.inquiries.convert');
    });

    // ==========================================
    // 3. SHAREHOLDER & PARTNERSHIP MANAGEMENT
    // ==========================================
    Route::group(['prefix' => 'shareholders'], function () {
        Route::get('/settings', [AdminShareholderController::class, 'settingsIndex'])->name('admin.shareholders.settings.index');
        Route::post('/settings/update', [AdminShareholderController::class, 'settingsUpdate'])->name('admin.shareholders.settings.update');
        Route::get('/list', [AdminShareholderController::class, 'index'])->name('admin.shareholders.index');
        Route::post('/store', [AdminShareholderController::class, 'storeShareholder'])->name('admin.shareholders.store');
        Route::post('/status/{id}', [AdminShareholderController::class, 'updateStatus'])->name('admin.shareholders.status');
        Route::get('/partnerships', [AdminShareholderController::class, 'partnershipsIndex'])->name('admin.shareholders.partnerships.index');
        Route::post('/partnerships/store', [AdminShareholderController::class, 'storePartnership'])->name('admin.shareholders.partnerships.store');
        Route::post('/partnerships/status/{id}', [AdminShareholderController::class, 'updatePartnershipStatus'])->name('admin.shareholders.partnerships.status');
    });

    // ==========================================
    // 4. CENTRAL FINANCE & UNIFIED ACCOUNTS
    // ==========================================
    Route::group(['prefix' => 'finance'], function () {
        Route::get('/central', [AdminFinanceController::class, 'index'])->name('admin.finance.index');
        Route::post('/central/store', [AdminFinanceController::class, 'storeEntry'])->name('admin.finance.store');
        Route::get('/unified-accounts', [AdminFinanceController::class, 'unifiedAccounts'])->name('admin.finance.unified_accounts');
    });

    // ==========================================
    // 5. NOTICES MANAGEMENT
    // ==========================================
    Route::group(['prefix' => 'notices'], function () {
        Route::get('/', [AdminNoticeController::class, 'index'])->name('admin.notices.index');
        Route::get('/create', [AdminNoticeController::class, 'create'])->name('admin.notices.create');
        Route::post('/store', [AdminNoticeController::class, 'store'])->name('admin.notices.store');
        Route::get('/edit/{id}', [AdminNoticeController::class, 'edit'])->name('admin.notices.edit');
        Route::post('/update/{id}', [AdminNoticeController::class, 'update'])->name('admin.notices.update');
        Route::get('/destroy/{id}', [AdminNoticeController::class, 'destroy'])->name('admin.notices.destroy');
    });

    // ==========================================
    // 6. STORE & ASSET MANAGEMENT ERP (মালামাল, স্টোর, আসবাবপত্র, গাড়ি, রক্ষণাবেক্ষণ, অবচয়, অডিট ও স্ক্র্যাপ)
    // ==========================================
    Route::group(['prefix' => 'store-assets'], function () {
        Route::get('/dashboard', [AdminStoreAssetController::class, 'dashboard'])->name('admin.store.dashboard');

        // Stores & Warehouse Locations
        Route::get('/stores', [AdminStoreAssetController::class, 'storesIndex'])->name('admin.store.stores.index');
        Route::post('/stores/store', [AdminStoreAssetController::class, 'storeStore'])->name('admin.store.stores.store');
        Route::post('/stores/locations/store', [AdminStoreAssetController::class, 'storeLocationStore'])->name('admin.store.stores.locations.store');

        // Items & Stock Inventory Master
        Route::get('/items', [AdminStoreAssetController::class, 'itemsIndex'])->name('admin.store.items.index');
        Route::get('/items/create', [AdminStoreAssetController::class, 'itemCreate'])->name('admin.store.items.create');
        Route::post('/items/store', [AdminStoreAssetController::class, 'itemStore'])->name('admin.store.items.store');
        Route::get('/items/movements', [AdminStoreAssetController::class, 'stockMovements'])->name('admin.store.items.movements');
        Route::post('/items/stock-in', [AdminStoreAssetController::class, 'stockIn'])->name('admin.store.items.stock_in');
        Route::post('/items/stock-out', [AdminStoreAssetController::class, 'stockOut'])->name('admin.store.items.stock_out');

        // Fixed Assets & Equipment Register
        Route::get('/', [AdminStoreAssetController::class, 'index'])->name('admin.store.assets.index');
        Route::get('/create', [AdminStoreAssetController::class, 'create'])->name('admin.store.assets.create');
        Route::post('/store', [AdminStoreAssetController::class, 'store'])->name('admin.store.assets.store');
        Route::get('/show/{id}', [AdminStoreAssetController::class, 'show'])->name('admin.store.assets.show');
        Route::get('/edit/{id}', [AdminStoreAssetController::class, 'edit'])->name('admin.store.assets.edit');
        Route::post('/update/{id}', [AdminStoreAssetController::class, 'update'])->name('admin.store.assets.update');
        Route::get('/destroy/{id}', [AdminStoreAssetController::class, 'destroy'])->name('admin.store.assets.destroy');

        // Vehicles & Fleet Management
        Route::get('/vehicles', [AdminStoreAssetController::class, 'vehiclesIndex'])->name('admin.store.vehicles.index');
        Route::post('/vehicles/store', [AdminStoreAssetController::class, 'vehicleStore'])->name('admin.store.vehicles.store');
        Route::get('/vehicles/show/{id}', [AdminStoreAssetController::class, 'vehicleShow'])->name('admin.store.vehicles.show');
        Route::post('/vehicles/trips/store', [AdminStoreAssetController::class, 'vehicleTripStore'])->name('admin.store.vehicles.trips.store');
        Route::post('/vehicles/fuel/store', [AdminStoreAssetController::class, 'vehicleFuelStore'])->name('admin.store.vehicles.fuel.store');

        // Maintenance & Repair Work Orders (AMC & Preventive)
        Route::get('/work-orders', [AdminStoreAssetController::class, 'workOrders'])->name('admin.store.work_orders.index');
        Route::post('/work-orders/store', [AdminStoreAssetController::class, 'workOrderStore'])->name('admin.store.work_orders.store');
        Route::get('/maintenances', [AdminStoreAssetController::class, 'maintenances'])->name('admin.store.maintenances.index');
        Route::post('/maintenances/store', [AdminStoreAssetController::class, 'storeMaintenance'])->name('admin.store.maintenances.store');
        Route::get('/maintenances/destroy/{id}', [AdminStoreAssetController::class, 'deleteMaintenance'])->name('admin.store.maintenances.destroy');

        // Asset Depreciation Schedule & Calculation
        Route::get('/depreciations', [AdminStoreAssetController::class, 'depreciations'])->name('admin.store.depreciations.index');
        Route::post('/depreciations/calculate', [AdminStoreAssetController::class, 'depreciationCalculate'])->name('admin.store.depreciations.calculate');

        // Stock Audits & Physical Verification
        Route::get('/audits', [AdminStoreAssetController::class, 'audits'])->name('admin.store.audits.index');
        Route::post('/audits/store', [AdminStoreAssetController::class, 'auditStore'])->name('admin.store.audits.store');

        // Damage, Loss & Asset Disposals (Scrap)
        Route::get('/disposals', [AdminStoreAssetController::class, 'disposals'])->name('admin.store.disposals.index');
        Route::post('/disposals/store', [AdminStoreAssetController::class, 'disposalStore'])->name('admin.store.disposals.store');

        // Vouchers & Bills (Money Receipt, Cash Memo, Debit/Credit)
        Route::get('/vouchers', [AdminStoreAssetController::class, 'vouchers'])->name('admin.store.vouchers.index');
        Route::get('/cash-memos', [AdminStoreAssetController::class, 'cashMemos'])->name('admin.store.vouchers.cash_memos');
        Route::post('/vouchers/store', [AdminStoreAssetController::class, 'storeVoucher'])->name('admin.store.vouchers.store');
        Route::get('/vouchers/destroy/{id}', [AdminStoreAssetController::class, 'deleteVoucher'])->name('admin.store.vouchers.destroy');
        Route::get('/vouchers/print/{id}', [AdminStoreAssetController::class, 'printVoucher'])->name('admin.store.vouchers.print');

        // Reports
        Route::get('/reports', [AdminStoreAssetController::class, 'reports'])->name('admin.store.reports');
    });

    // ==========================================
    // ENTERPRISE ROLE & PERMISSION MANAGEMENT ROUTES
    // ==========================================
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [AdminRolePermissionController::class, 'index'])->name('admin.roles.index');
        Route::post('/store', [AdminRolePermissionController::class, 'storeRole'])->name('admin.roles.store');
        Route::post('/update/{id}', [AdminRolePermissionController::class, 'updateRole'])->name('admin.roles.update');
        Route::get('/delete/{id}', [AdminRolePermissionController::class, 'deleteRole'])->name('admin.roles.delete');
        Route::get('/clone/{id}', [AdminRolePermissionController::class, 'cloneRole'])->name('admin.roles.clone');
        Route::get('/toggle-status/{id}', [AdminRolePermissionController::class, 'toggleRoleStatus'])->name('admin.roles.toggle_status');
        Route::post('/sync-registry', [AdminRolePermissionController::class, 'syncRegistry'])->name('admin.roles.sync_registry');
        Route::get('/audit-logs', [AdminRolePermissionController::class, 'auditLogs'])->name('admin.roles.audit_logs');
        Route::get('/matrix', [AdminRolePermissionController::class, 'matrix'])->name('admin.roles.matrix');
        Route::post('/matrix/update', [AdminRolePermissionController::class, 'updateMatrix'])->name('admin.roles.matrix.update');
        Route::get('/user-assignment', [AdminRolePermissionController::class, 'userAssignment'])->name('admin.roles.user_assignment');
        Route::post('/user-assignment/store-user', [AdminRolePermissionController::class, 'storeUser'])->name('admin.roles.user.store');
        Route::post('/user-assignment/reset-password/{id}', [AdminRolePermissionController::class, 'resetUserPassword'])->name('admin.roles.user.reset_password');
        Route::post('/user-assignment/{id}', [AdminRolePermissionController::class, 'updateUserRole'])->name('admin.roles.user_assignment.update');
        Route::get('/simulator', [AdminRolePermissionController::class, 'simulator'])->name('admin.roles.simulator');
    });

    // ==========================================
    // ENTERPRISE BILLS & VOUCHERS MANAGEMENT (A-Z DOUBLE-ENTRY ERP)
    // ==========================================
    Route::group(['prefix' => 'bills-vouchers'], function () {
        Route::get('/dashboard', [AdminBillsVouchersController::class, 'dashboard'])->name('admin.bills_vouchers.dashboard');

        // Vouchers Management
        Route::get('/vouchers', [AdminBillsVouchersController::class, 'vouchersIndex'])->name('admin.bills_vouchers.vouchers.index');
        Route::get('/vouchers/create', [AdminBillsVouchersController::class, 'voucherCreate'])->name('admin.bills_vouchers.vouchers.create');
        Route::post('/vouchers/store', [AdminBillsVouchersController::class, 'voucherStore'])->name('admin.bills_vouchers.vouchers.store');
        Route::get('/vouchers/show/{id}', [AdminBillsVouchersController::class, 'voucherShow'])->name('admin.bills_vouchers.vouchers.show');
        Route::get('/vouchers/print/{id}', [AdminBillsVouchersController::class, 'voucherPrint'])->name('admin.bills_vouchers.vouchers.print');
        Route::post('/vouchers/approve/{id}', [AdminBillsVouchersController::class, 'voucherApprove'])->name('admin.bills_vouchers.vouchers.approve');
        Route::post('/vouchers/post/{id}', [AdminBillsVouchersController::class, 'voucherPost'])->name('admin.bills_vouchers.vouchers.post');
        Route::post('/vouchers/reverse/{id}', [AdminBillsVouchersController::class, 'voucherReverse'])->name('admin.bills_vouchers.vouchers.reverse');

        // Bills Management
        Route::get('/bills', [AdminBillsVouchersController::class, 'billsIndex'])->name('admin.bills_vouchers.bills.index');
        Route::get('/bills/create', [AdminBillsVouchersController::class, 'billCreate'])->name('admin.bills_vouchers.bills.create');
        Route::post('/bills/store', [AdminBillsVouchersController::class, 'billStore'])->name('admin.bills_vouchers.bills.store');
        Route::get('/bills/show/{id}', [AdminBillsVouchersController::class, 'billShow'])->name('admin.bills_vouchers.bills.show');

        // Chart of Accounts (COA)
        Route::get('/chart-of-accounts', [AdminBillsVouchersController::class, 'chartOfAccounts'])->name('admin.bills_vouchers.chart_of_accounts');
        Route::post('/chart-of-accounts/store', [AdminBillsVouchersController::class, 'chartOfAccountsStore'])->name('admin.bills_vouchers.chart_of_accounts.store');

        // Financial Reports
        Route::get('/reports/general-ledger', [AdminBillsVouchersController::class, 'generalLedger'])->name('admin.bills_vouchers.reports.general_ledger');
        Route::get('/reports/trial-balance', [AdminBillsVouchersController::class, 'trialBalance'])->name('admin.bills_vouchers.reports.trial_balance');
    });

});

// Public Live Chat & AI Bot API Routes
Route::post('/chat/fetch', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

// Supplier Portal Routes
Route::group(['prefix' => 'supplier', 'middleware' => 'SupplierChecker'], function () {
    Route::get('/dashboard', [SupplierSupplyController::class, 'dashboard'])->name('supplier.dashboard');
    Route::get('/supplies', [SupplierSupplyController::class, 'index'])->name('supplier.supplies.index');
    Route::get('/supplies/create', [SupplierSupplyController::class, 'create'])->name('supplier.supplies.create');
    Route::post('/supplies/store', [SupplierSupplyController::class, 'store'])->name('supplier.supplies.store');
    Route::get('/supplies/{id}', [SupplierSupplyController::class, 'show'])->name('supplier.supplies.show');
    Route::post('/supplies/{id}/dispatch', [SupplierSupplyController::class, 'dispatchSupply'])->name('supplier.supplies.dispatch');
    Route::post('/supplies/{id}/submit-draft', [SupplierSupplyController::class, 'submitDraft'])->name('supplier.supplies.submit_draft');
    Route::post('/notifications/{id}/read', [SupplierSupplyController::class, 'markNotificationRead'])->name('supplier.notifications.read');
    Route::get('/statement', [SupplierSupplyController::class, 'statement'])->name('supplier.statement');
    Route::get('/statement/print', [SupplierSupplyController::class, 'printStatement'])->name('supplier.statement.print');
    Route::get('/invoice/print/{id}', [SupplierSupplyController::class, 'printInvoice'])->name('supplier.invoice.print');
});

// Agent Portal Main URL Entry (http://127.0.0.1:8000/agent - Section 33.1)
Route::get('/agent', function () {
    if (Auth::check() && Auth::user()->isAgent()) {
        return redirect()->route('agent.dashboard');
    }
    return redirect()->route('agent.login');
});

// Marketing & Distribution Agent Portal Routes (Strict RBAC & Agent Point Isolation - SRS Section 12-16)
Route::group(['prefix' => 'agent', 'middleware' => 'AgentChecker'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Agent\AgentPortalController::class, 'dashboard'])->name('agent.dashboard');
    Route::post('/verify-member', [\App\Http\Controllers\Agent\AgentPortalController::class, 'verifyMember'])->name('agent.verify_member');
    Route::post('/delivery/store', [\App\Http\Controllers\Agent\AgentPortalController::class, 'storeDelivery'])->name('agent.delivery.store');
    Route::get('/stock', [\App\Http\Controllers\Agent\AgentPortalController::class, 'stockIndex'])->name('agent.stock');
    Route::get('/supplies', [\App\Http\Controllers\Agent\AgentPortalController::class, 'incomingSupplies'])->name('agent.supplies.index');
    Route::get('/supplies/{id}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'showIncomingSupply'])->name('agent.supplies.show');
    Route::post('/supplies/{id}/receive', [\App\Http\Controllers\Agent\AgentPortalController::class, 'receiveIncomingSupply'])->name('agent.supplies.receive');
    Route::get('/distributions', [\App\Http\Controllers\Agent\AgentPortalController::class, 'customerDistributions'])->name('agent.distributions.index');
    Route::get('/distributions/create', [\App\Http\Controllers\Agent\AgentPortalController::class, 'createCustomerDistribution'])->name('agent.distributions.create');
    Route::post('/distributions/store', [\App\Http\Controllers\Agent\AgentPortalController::class, 'storeCustomerDistribution'])->name('agent.distributions.store');
    Route::get('/distributions/customer-history', [\App\Http\Controllers\Agent\AgentPortalController::class, 'customerHistory'])->name('agent.distributions.customer_history');
    Route::get('/distributions/{id}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'showCustomerDistribution'])->name('agent.distributions.show');
    Route::get('/dispatches', [\App\Http\Controllers\Agent\AgentPortalController::class, 'dispatches'])->name('agent.dispatches');
    Route::post('/dispatches/receive/{id}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'confirmReceiveDispatch'])->name('agent.dispatches.receive');
    Route::get('/members', [\App\Http\Controllers\Agent\AgentPortalController::class, 'members'])->name('agent.members');
    Route::get('/requests', [\App\Http\Controllers\Agent\AgentPortalController::class, 'requests'])->name('agent.requests');
    Route::get('/delivery-history', [\App\Http\Controllers\Agent\AgentPortalController::class, 'deliveryHistory'])->name('agent.delivery_history');
    Route::get('/receipt/{id}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'receipt'])->name('agent.receipt');
});






// employee Route
Route::group(['prefix' => 'employee', 'middleware' => 'EmployeeChacker'], function () {
    Route::get('/referal', [EmployeeController::class, 'Referal'])->name('my.referal');
    Route::get('/profile/business', [EmployeeController::class, 'profileBusiness'])->name('profile.business');
    Route::post('/profile/business/submit', [EmployeeController::class, 'profileBusinessSubmit'])->name('profile.business.submit');

    // Marketing Agent Live Stock & Ledger Employee Routes
    Route::get('/stock-ledger', [EmployeeStockLedgerController::class, 'index'])->name('employee.stock_ledger.index');
    Route::post('/stock-ledger/sell', [EmployeeStockLedgerController::class, 'sellStock'])->name('employee.stock_ledger.sell');
});





// user Route
Route::group(['prefix' => 'user', 'middleware' => 'UserChacker'], function () {
    Route::get('/stock', [UserStockController::class, 'index'])->name('stock.index');
    Route::get('/show/stock/{id}', [UserStockController::class, 'show'])->name('stock.detials');
    Route::get('/stock/details/{id}', [UserStockController::class, 'show'])->name('stock.details');
    Route::get('/sell-stock-list', [UserStockController::class, 'userSellStockList'])->name('usersellstocklist');
    Route::get('/buy-stock-list', [UserStockController::class, 'userBuyStockList'])->name('userbuystocklist');
    Route::get('/stock/sell-request/{id}', [UserStockController::class, 'SellRequest'])->name('user.stock.sell.request');

    Route::get('/payment-system', [UserPaymentSystemController::class, 'index'])->name('payment.index');
    Route::post('/payment-system/post', [UserPaymentSystemController::class, 'store'])->name('payment.post');
    Route::get('/payment-system/destroy/{id}', [UserPaymentSystemController::class, 'destroy'])->name('payment.destroy');

    Route::get('/cart', [UserCartController::class, 'index'])->name('my.cart');
    Route::get('/cart/count', [UserCartController::class, 'getCartCount'])->name('my.cart.count');
    Route::get('/add/cart/{id}', [UserCartController::class, 'AddCart'])->name('my.cart.add');
    Route::get('/remove/cart/{id}', [UserCartController::class, 'removeCartItem'])->name('my.cart.remove');

    Route::post('/cart/post', [UserCartController::class, 'CartPost'])->name('my.cart.post');


    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::get('/withdraw/request', [WithdrawController::class, 'create'])->name('withdraw.form');
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.post');
    Route::get('/withdraw/destroy/{id}', [WithdrawController::class, 'destroy'])->name('user.withdraw.destroy');

    // Monthly Bazaar User Routes
    Route::get('/monthly-bazaar', [UserMonthlyBazaarController::class, 'index'])->name('user.monthly_bazaar.index');
    Route::post('/monthly-bazaar/order', [UserMonthlyBazaarController::class, 'storeOrder'])->name('user.monthly_bazaar.order.post');
    Route::get('/monthly-bazaar/my-orders', [UserMonthlyBazaarController::class, 'myOrders'])->name('user.monthly_bazaar.my_orders');

    // Customer Deposit & Wallet Ledger Routes
    Route::get('/deposit', [UserDepositController::class, 'index'])->name('user.deposit.index');
    Route::post('/deposit/post', [UserDepositController::class, 'store'])->name('user.deposit.post');
    Route::get('/wallet/ledger', [UserWalletController::class, 'ledger'])->name('user.wallet.ledger');

    // User Investment & Dividend Routes
    Route::get('/my-investments', [InvestmentController::class, 'myInvestments'])->name('user.investments.index');
    Route::post('/withdraw-investment', [InvestmentController::class, 'requestWithdrawal'])->name('user.investments.withdraw');

});

// Employee Self-Service (ESS) Portal Routes (Mobile & Web Punch In/Out, Corrections, Leaves)
Route::group(['prefix' => 'hrm-portal', 'middleware' => ['auth']], function () {
    Route::get('/', [HrmEmployeeSelfServiceController::class, 'dashboard'])->name('hrm.ess.dashboard');
    Route::post('/web-punch', [HrmEmployeeSelfServiceController::class, 'webPunch'])->name('hrm.ess.web_punch');
    Route::post('/correction', [HrmEmployeeSelfServiceController::class, 'storeCorrection'])->name('hrm.ess.store_correction');
    Route::post('/leave', [HrmEmployeeSelfServiceController::class, 'storeLeave'])->name('hrm.ess.store_leave');
    Route::post('/advance', [HrmEmployeeSelfServiceController::class, 'storeAdvance'])->name('hrm.ess.store_advance');
});














