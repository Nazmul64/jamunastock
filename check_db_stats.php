<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$stats = [
    'users' => App\Models\User::count(),
    'customers' => App\Models\User::where('role', 'user')->count(),
    'employees' => App\Models\User::where('role', 'employee')->count(),
    'buy_stocks_count' => App\Models\BuyStock::count(),
    'buy_stocks_sum' => (float)App\Models\BuyStock::sum('buyed_price'),
    'sell_stocks_count' => App\Models\SellStock::count(),
    'sell_stocks_sum' => (float)App\Models\SellStock::sum('selled_price'),
];

if (class_exists(App\Models\MonthlyBazaarOrder::class)) {
    $stats['bazaar_orders'] = App\Models\MonthlyBazaarOrder::count();
    $stats['bazaar_sum'] = (float)App\Models\MonthlyBazaarOrder::sum('total_price');
}
if (class_exists(App\Models\ContactMessage::class)) {
    $stats['contact_messages'] = App\Models\ContactMessage::count();
}
if (class_exists(App\Models\Notice::class)) {
    $stats['notices'] = App\Models\Notice::count();
}
if (class_exists(App\Models\CorporateActivity::class)) {
    $stats['corporate_activities'] = App\Models\CorporateActivity::count();
}
if (class_exists(App\Models\Career::class)) {
    $stats['careers'] = App\Models\Career::count();
}
if (class_exists(App\Models\StoreVoucher::class)) {
    $stats['vouchers_count'] = App\Models\StoreVoucher::count();
    $stats['vouchers_sum'] = (float)App\Models\StoreVoucher::sum('amount');
}
if (class_exists(App\Models\FinanceLedger::class)) {
    $stats['finance_sum'] = (float)App\Models\FinanceLedger::sum('amount');
}

echo json_encode($stats, JSON_PRETTY_PRINT);

