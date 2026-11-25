<?php

namespace Database\Seeders;

use App\Models\StoreAsset;
use App\Models\StoreAssetMaintenance;
use App\Models\StoreVoucher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StoreAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (StoreAsset::count() > 0) {
            return;
        }

        // 1. Furniture
        $a1 = StoreAsset::create([
            'asset_code' => 'FUR-001',
            'name' => 'ম্যানেজিং ডিরেক্টর এক্সিকিউটিভ ডেক্স ও চেয়ার সেট',
            'category' => 'furniture',
            'model_no' => 'Otobi Regal Executive Set',
            'quantity' => 1,
            'unit' => 'সেট',
            'purchase_date' => Carbon::now()->subMonths(6),
            'purchase_cost' => 65000.00,
            'current_value' => 60000.00,
            'vendor_name' => 'অটোবি ফার্নিচার লিমিটেড',
            'vendor_contact' => '01711223344',
            'location' => 'প্রধান কার্যালয়, এমডি কক্ষ (রুম ৩০১)',
            'assigned_to' => 'ম্যানেজিং ডিরেক্টর (MD)',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => 'মেহগনি কাঠের তৈরি প্রিমিয়াম এক্সিকিউটিভ টেবিল ও লেদার চেয়ার।',
        ]);

        $a2 = StoreAsset::create([
            'asset_code' => 'FUR-002',
            'name' => 'কনফারেন্স রুম টেবিল ও ১২টি ভিজিটর চেয়ার',
            'category' => 'furniture',
            'model_no' => 'Hatil Conference Table 12-Seater',
            'quantity' => 1,
            'unit' => 'সেট',
            'purchase_date' => Carbon::now()->subMonths(5),
            'purchase_cost' => 85000.00,
            'current_value' => 80000.00,
            'vendor_name' => 'হাতিল ফার্নিচার',
            'vendor_contact' => '01811445566',
            'location' => 'প্রধান কার্যালয়, বোর্ড রুম (২য় তলা)',
            'assigned_to' => 'বোর্ড অব ডিরেক্টরস',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => '১২ সিটের বড় কনফারেন্স টেবিল ও আরামদায়ক রিভলভিং চেয়ার।',
        ]);

        $a3 = StoreAsset::create([
            'asset_code' => 'FUR-003',
            'name' => '৪ ড্রয়ার বিশিষ্ট মেটাল ফাইল ক্যাবিনেট (আলমারি)',
            'category' => 'furniture',
            'model_no' => 'Godrej Heavy Duty 4-Drawer',
            'quantity' => 3,
            'unit' => 'টি',
            'purchase_date' => Carbon::now()->subMonths(4),
            'purchase_cost' => 45000.00,
            'current_value' => 42000.00,
            'vendor_name' => 'মেসার্স ভাই ভাই স্টিল কর্পোরেশন',
            'vendor_contact' => '01911778899',
            'location' => 'অ্যাকাউন্টস ও ফিন্যান্স বিভাগ',
            'assigned_to' => 'হেড অব অ্যাকাউন্টস',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => 'গুরুত্বপূর্ণ ভাউচার, চুক্তিপত্র ও ফাইল সংরক্ষণের জন্য ব্যবহৃত।',
        ]);

        // 2. Vehicles
        $a4 = StoreAsset::create([
            'asset_code' => 'VEH-001',
            'name' => 'টয়োটা নোয়া ৮-সিটার মাইক্রোবাস (অফিসিয়াল)',
            'category' => 'vehicle',
            'model_no' => 'Toyota Noah Super GL 2018',
            'vehicle_reg_no' => 'ঢাকা মেট্রো-চ ৫৩-৮৮৯২',
            'quantity' => 1,
            'unit' => 'টি',
            'purchase_date' => Carbon::now()->subMonths(8),
            'purchase_cost' => 2450000.00,
            'current_value' => 2350000.00,
            'vendor_name' => 'আল-বারাকা অটোমোবাইলস',
            'vendor_contact' => '01712998877',
            'location' => 'প্রধান কার্যালয় গ্যারেজ',
            'assigned_to' => 'ড্রাইভার মো: কাশেম মিয়া / অফিস পুল',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => 'পরিচালনা পর্ষদ ও অফিসিয়াল ফিল্ড ভিজিটের জন্য নিয়োজিত।',
        ]);

        $a5 = StoreAsset::create([
            'asset_code' => 'VEH-002',
            'name' => 'বাজাজ ডিসকভার ১২৫সিসি মোটরসাইকেল (মার্কেটিং)',
            'category' => 'vehicle',
            'model_no' => 'Bajaj Discover 125 Disc',
            'vehicle_reg_no' => 'ঢাকা মেট্রো-হ ২৯-৩৪১২',
            'quantity' => 1,
            'unit' => 'টি',
            'purchase_date' => Carbon::now()->subMonths(3),
            'purchase_cost' => 145000.00,
            'current_value' => 135000.00,
            'vendor_name' => 'উত্তরা মোটরস ডিলার',
            'vendor_contact' => '01819332211',
            'location' => 'মার্কেটিং ও ফিল্ড এরিয়া',
            'assigned_to' => 'মার্কেটিং এক্সিকিউটিভ সজীব আহমেদ',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => 'ফিল্ডে মাসিক বাজার ও পণ্য সরবরাহের এজেন্ট তদারকির দায়িত্বে ব্যবহৃত।',
        ]);

        // 3. Stationery & Books
        $a6 = StoreAsset::create([
            'asset_code' => 'STN-001',
            'name' => 'অফিসিয়াল লেজার খাতা ও হিসাব ক্যাশ বুক (২০০ পেজ)',
            'category' => 'stationery',
            'model_no' => 'Hard Cover Ledger 200P',
            'quantity' => 20,
            'unit' => 'টি',
            'purchase_date' => Carbon::now()->subMonths(1),
            'purchase_cost' => 8000.00,
            'current_value' => 8000.00,
            'vendor_name' => 'বাংলাবাজার পেপার মার্ট',
            'vendor_contact' => '01722001122',
            'location' => 'সেন্ট্রাল স্টোর রুম (শেলফ এ-১)',
            'assigned_to' => 'স্টোর কিপার',
            'condition' => 'new',
            'status' => 'in_store',
            'notes' => 'অফিসের আয়-ব্যয় ও হিসাবরক্ষণের জন্য মজুত লেজার খাতা।',
        ]);

        $a7 = StoreAsset::create([
            'asset_code' => 'STN-002',
            'name' => 'ডাবল-এ (Double-A) এ৪ সাইজ প্রিন্টার পেপার বক্স',
            'category' => 'stationery',
            'model_no' => '80 GSM Premium White 5 Reams/Box',
            'quantity' => 10,
            'unit' => 'বক্স',
            'purchase_date' => Carbon::now()->subDays(15),
            'purchase_cost' => 18500.00,
            'current_value' => 18500.00,
            'vendor_name' => 'মেসার্স সততা স্টেশনারি',
            'vendor_contact' => '01611887766',
            'location' => 'সেন্ট্রাল স্টোর রুম (শেলফ বি-২)',
            'assigned_to' => 'স্টোর কিপার',
            'condition' => 'new',
            'status' => 'in_store',
            'notes' => 'সকল বিভাগের প্রিন্টিং কাজের জন্য মোট ১০ বক্স (৫০ রিম) কাগজ।',
        ]);

        // 4. Electronics
        $a8 = StoreAsset::create([
            'asset_code' => 'ELC-001',
            'name' => 'ডেল কোর-আই৫ ডেস্কটপ কম্পিউটার সেট ও মনিটর',
            'category' => 'electronics',
            'model_no' => 'Dell OptiPlex Core i5 12th Gen',
            'serial_no' => 'DL-OPT-2026-9812',
            'quantity' => 4,
            'unit' => 'সেট',
            'purchase_date' => Carbon::now()->subMonths(4),
            'purchase_cost' => 260000.00,
            'current_value' => 240000.00,
            'vendor_name' => 'স্টার টেক অ্যান্ড ইঞ্জিনিয়ারিং',
            'vendor_contact' => '01700998811',
            'location' => 'আইটি ও অ্যাকাউন্টস শাখা',
            'assigned_to' => 'আইটি ও সিস্টেম অ্যাডমিন',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => '১৬ জিবি র‍্যাম, ৫১২ জিবি এসএসডি ও ডেল ২২ ইঞ্চি আইপিএস মনিটর।',
        ]);

        $a9 = StoreAsset::create([
            'asset_code' => 'ELC-002',
            'name' => 'গ্রে ১.৫ টন ইনভার্টার স্প্লিট এয়ার কন্ডিশনার (AC)',
            'category' => 'electronics',
            'model_no' => 'Gree Inverter 1.5 Ton Fairy Series',
            'serial_no' => 'GR-AC-15T-7721',
            'quantity' => 2,
            'unit' => 'টি',
            'purchase_date' => Carbon::now()->subMonths(7),
            'purchase_cost' => 140000.00,
            'current_value' => 130000.00,
            'vendor_name' => 'ইলেকট্রো মার্ট লিমিটেড',
            'vendor_contact' => '01811002299',
            'location' => 'বোর্ড মিটিং রুম ও একাউন্টস রুম',
            'assigned_to' => 'এডমিন ডিপার্টমেন্ট',
            'condition' => 'good',
            'status' => 'in_use',
            'notes' => 'নিয়মিত গ্যাস ও ফিল্টার সার্ভিসিং প্রয়োজন।',
        ]);

        // 5. Maintenances
        StoreAssetMaintenance::create([
            'maintenance_no' => 'MNT-2026-0001',
            'asset_id' => $a4->id, // Noah Microbus
            'title' => 'ইঞ্জিন অয়েল, মোবিল ফিল্টার ও ব্রেক প্যাড পরিবর্তন',
            'description' => 'মাইক্রোবাসের নিয়মিত ৫০০০ কিমি রুটিন সার্ভিসিং ও ফ্রন্ট ব্রেক প্যাড পরিবর্তন করা হয়েছে।',
            'service_date' => Carbon::now()->subDays(10),
            'next_service_due' => Carbon::now()->addMonths(3),
            'service_provider' => 'মা মোটরস অ্যান্ড সার্ভিস সেন্টার',
            'provider_contact' => '01715443322',
            'cost' => 12500.00,
            'voucher_ref' => 'VOUCH-2026-0045',
            'status' => 'completed',
            'performed_by' => 'ড্রাইভার মো: কাশেম মিয়া',
        ]);

        StoreAssetMaintenance::create([
            'maintenance_no' => 'MNT-2026-0002',
            'asset_id' => $a9->id, // AC
            'title' => 'বোর্ড রুম এসির ফিল্টার ওয়াশ ও গ্যাস প্রেশার চেক',
            'description' => 'ইনডোর ও আউটডোর ইউনিটের ডিপ ক্লিনিং এবং কুলিং পারফরম্যান্স বাড়ানো হয়েছে।',
            'service_date' => Carbon::now()->subDays(20),
            'next_service_due' => Carbon::now()->addMonths(6),
            'service_provider' => 'কুলিং টেক এসি সার্ভিসিং',
            'provider_contact' => '01912887766',
            'cost' => 3500.00,
            'voucher_ref' => 'VOUCH-2026-0038',
            'status' => 'completed',
            'performed_by' => 'অফিস সহকারী',
        ]);

        // 6. Vouchers, Memos, Money Receipts
        StoreVoucher::create([
            'voucher_no' => 'VOUCH-2026-0001',
            'type' => 'debit_voucher',
            'category' => 'stationery',
            'title' => 'অফিসের মাসিক খাতা, ফাইল ফোল্ডার ও স্টেশনারি ক্রয়',
            'vendor_customer_name' => 'মেসার্স সততা স্টেশনারি',
            'amount' => 18500.00,
            'date' => Carbon::now()->subDays(15),
            'payment_method' => 'cash',
            'payment_ref' => 'CASH-PAY-091',
            'item_details' => "১. ডাবল-এ পেপার ৫ বক্স: ৳৯,২৫০\n২. লেজার খাতা ১০টি: ৳৪,০০০\n৩. বলপেন ৫ বক্স: ৳১,২৫০\n৪. প্লাস্টিক ফাইল ফোল্ডার ৫০টি: ৳৪,০০০",
            'description' => 'সকল বিভাগের দাপ্তরিক কাজের প্রয়োজনীয় মালামাল ক্রয়ের ব্যয় পরিশোধ।',
            'status' => 'paid',
            'approved_by' => 'ম্যানেজিং ডিরেক্টর',
        ]);

        StoreVoucher::create([
            'voucher_no' => 'MEMO-2026-0001',
            'type' => 'cash_memo',
            'category' => 'furniture',
            'title' => 'হিসাব বিভাগের জন্য ২টি ভিজিটর চেয়ার ক্যাশ মেমো',
            'vendor_customer_name' => 'অটোবি ফার্নিচার আউটলেট',
            'amount' => 14000.00,
            'date' => Carbon::now()->subDays(12),
            'payment_method' => 'bkash',
            'payment_ref' => 'TRX8829910BKS',
            'item_details' => "১. রিভলভিং ভিজিটর চেয়ার: ২ পিস x ৳৭,০০০ = ৳১৪,০০০",
            'description' => 'অ্যাকাউন্টস ডিপার্টমেন্টে আগত অডিটর ও মেহমানদের বসার জন্য।',
            'status' => 'paid',
            'approved_by' => 'হেড অব অ্যাকাউন্টস',
        ]);

        StoreVoucher::create([
            'voucher_no' => 'MR-2026-0001',
            'type' => 'money_receipt',
            'category' => 'store_purchase',
            'title' => 'পুরোনো ব্যাটারি ও ভাঙা ফার্নিচার বিক্রয় বাবদ জমা',
            'vendor_customer_name' => 'আলমগীর মেটাল অ্যান্ড স্ক্র্যাপ মার্চেন্ট',
            'amount' => 8500.00,
            'date' => Carbon::now()->subDays(8),
            'payment_method' => 'cash',
            'payment_ref' => 'CASH-REC-012',
            'item_details' => "১. মাইক্রোবাসের পুরোনো ব্যাটারি ১টি: ৳২,৫০০\n২. পুরোনো ভাঙা কাঠের চেয়ার ও টেবিল: ৳৬,০০০",
            'description' => 'স্টোরে অকেজো হয়ে পড়া পুরোনো মালামাল নিলামে বিক্রয় বাবদ ক্যাশ গ্রহণ।',
            'status' => 'approved',
            'approved_by' => 'হেড অব অ্যাকাউন্টস',
        ]);

        StoreVoucher::create([
            'voucher_no' => 'BILL-2026-0001',
            'type' => 'bill',
            'category' => 'vehicle_maintenance',
            'title' => 'অফিস মাইক্রোবাস ঢাকা-কুমিল্লা ফিল্ড ট্রিপ ফুয়েল ও টোল বিল',
            'vendor_customer_name' => 'মেঘনা ফিলিং স্টেশন',
            'amount' => 9200.00,
            'date' => Carbon::now()->subDays(5),
            'payment_method' => 'cash',
            'payment_ref' => 'BILL-OCTANE-981',
            'item_details' => "১. অকটেন ৬০ লিটার @ ৳১২৫ = ৳৭,৫০০\n২. এক্সপ্রেসওয়ে ও সেতু টোল: ৳১,৭০০",
            'description' => 'কুমিল্লা প্রজেক্ট এলাকা পরিদর্শন উপলক্ষে গাড়ির জ্বালানি ও ব্রিজ টোল বিল।',
            'status' => 'paid',
            'approved_by' => 'ম্যানেজিং ডিরেক্টর',
        ]);
    }
}

