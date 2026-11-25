<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = [
    'users' => App\Models\User::count(),
    'deposits' => App\Models\Deposit::count(),
    'withdraws' => App\Models\Withdraw::count(),
    'buy_stocks' => App\Models\BuyStock::count(),
    'sell_stocks' => App\Models\SellStock::count(),
    'stocks' => App\Models\Stock::count(),
    'user_investments' => App\Models\UserInvestment::count(),
    'financial_bills' => App\Models\FinancialBill::count(),
    'accounting_vouchers' => App\Models\AccountingVoucher::count(),
    'store_assets' => App\Models\StoreAsset::count(),
    'store_vouchers' => App\Models\StoreVoucher::count(),
    'erp_items' => App\Models\ErpItem::count(),
    'erp_movements' => App\Models\ErpStockMovement::count(),
    'erp_vehicles' => App\Models\ErpVehicle::count(),
    'erp_work_orders' => App\Models\ErpMaintenanceWorkOrder::count(),
    'monthly_bazaar_orders' => App\Models\MonthlyBazaarOrder::count(),
    'notices' => App\Models\Notice::count(),
    'corporate_activities' => App\Models\CorporateActivity::count(),
    'contact_messages' => App\Models\ContactMessage::count(),
];

echo json_encode($tables, JSON_PRETTY_PRINT);
