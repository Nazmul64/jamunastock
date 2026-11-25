<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreAssetErpSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Stores
        $stores = [
            [
                'name' => 'কেন্দ্রীয় প্রধান ভাণ্ডার (Main Central Store)',
                'code' => 'STORE-MAIN-01',
                'location' => 'প্রধান কার্যালয়, গ্রাউন্ড ফ্লোর, ঢাকা',
                'manager_name' => 'মো: ইকবাল হোসেন',
                'contact' => '01711002233',
                'store_type' => 'main',
                'capacity' => '5,000 Sqft',
                'status' => true,
            ],
            [
                'name' => 'স্টেশনারি ও অফিস সাপ্লাই স্টোর',
                'code' => 'STORE-STAT-01',
                'location' => 'ভবন ১, রুম ২০৩',
                'manager_name' => 'ফারহানা আহমেদ',
                'contact' => '01811223344',
                'store_type' => 'stationery',
                'capacity' => '500 Sqft',
                'status' => true,
            ],
            [
                'name' => 'আইটি ও ইলেকট্রনিক্স ইকুইপমেন্ট স্টোর',
                'code' => 'STORE-IT-01',
                'location' => 'আইটি বিভাগ, ৩য় তলা',
                'manager_name' => 'তানভীর হাসান',
                'contact' => '01911334455',
                'store_type' => 'it',
                'capacity' => '800 Sqft',
                'status' => true,
            ],
            [
                'name' => 'আসবাবপত্র ও ফার্নিচার গুদাম',
                'code' => 'STORE-FURN-01',
                'location' => 'বেসমেন্ট ২, নরসিংদী প্রধান কমপ্লেক্স',
                'manager_name' => 'আব্দুর রাজ্জাক',
                'contact' => '01611445566',
                'store_type' => 'furniture',
                'capacity' => '3,000 Sqft',
                'status' => true,
            ],
            [
                'name' => 'যানবাহন ও স্পেয়ার পার্টস গ্যারেজ',
                'code' => 'STORE-VEHI-01',
                'location' => 'পরিবহন ডিপো, শেড ৩',
                'manager_name' => 'সাইফুল ইসলাম',
                'contact' => '01511556677',
                'store_type' => 'vehicle_parts',
                'capacity' => '2,500 Sqft',
                'status' => true,
            ],
            [
                'name' => 'ইলেকট্রিক্যাল ও মেনটেনেন্স স্টোর',
                'code' => 'STORE-ELEC-01',
                'location' => 'ইউটিলিটি ভবন, নিচতলা',
                'manager_name' => 'আরিফুল হক',
                'contact' => '01711667788',
                'store_type' => 'electrical',
                'capacity' => '1,000 Sqft',
                'status' => true,
            ],
        ];

        foreach ($stores as $st) {
            DB::table('erp_stores')->updateOrInsert(['code' => $st['code']], array_merge($st, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 2. Seed Store Locations
        $mainStore = DB::table('erp_stores')->where('code', 'STORE-MAIN-01')->first();
        if ($mainStore) {
            $locations = [
                ['store_id' => $mainStore->id, 'floor' => 'Floor 1', 'room_number' => '101', 'rack_code' => 'RACK-A', 'shelf_number' => 'SHELF-01', 'box_bin' => 'BIN-01', 'description' => 'প্রিন্টার পেপার ও ভারী স্টেশনারি'],
                ['store_id' => $mainStore->id, 'floor' => 'Floor 1', 'room_number' => '101', 'rack_code' => 'RACK-A', 'shelf_number' => 'SHELF-02', 'box_bin' => 'BIN-02', 'description' => 'ফাইল ও খাতা রেজিস্টার'],
                ['store_id' => $mainStore->id, 'floor' => 'Floor 1', 'room_number' => '102', 'rack_code' => 'RACK-B', 'shelf_number' => 'SHELF-01', 'box_bin' => 'BIN-05', 'description' => 'নেটওয়ার্কিং এক্সেসরিজ ও সুইচ'],
                ['store_id' => $mainStore->id, 'floor' => 'Floor 2', 'room_number' => '205', 'rack_code' => 'RACK-C', 'shelf_number' => 'SHELF-03', 'box_bin' => 'BIN-10', 'description' => 'টুলস ও স্পেয়ার পার্টস'],
            ];
            foreach ($locations as $loc) {
                DB::table('erp_store_locations')->insertOrIgnore(array_merge($loc, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // 3. Seed 8 Categories (A to H)
        $categories = [
            ['code' => 'CAT-STAT', 'name_bn' => 'স্টেশনারি ও অফিস সাপ্লাই', 'name_en' => 'Stationery & Office Supplies', 'icon' => 'fa-pencil-square-o', 'is_asset' => false, 'description' => 'খাতা, কলম, পেপার, ফাইল, টোনার ও রেজিস্টার'],
            ['code' => 'CAT-FURN', 'name_bn' => 'আসবাবপত্র ও ফার্নিচার', 'name_en' => 'Furniture & Fixtures', 'icon' => 'fa-cube', 'is_asset' => true, 'description' => 'টেবিল, চেয়ার, সোফা, আলমারি, কনফারেন্স টেবিল'],
            ['code' => 'CAT-ITEL', 'name_bn' => 'আইটি ও ইলেকট্রনিক্স', 'name_en' => 'IT & Electronics', 'icon' => 'fa-laptop', 'is_asset' => true, 'description' => 'কম্পিউটার, ল্যাপটপ, মনিটর, প্রিন্টার, সার্ভার, রাউটার'],
            ['code' => 'CAT-VEHI', 'name_bn' => 'যানবাহন ও পরিবহন', 'name_en' => 'Vehicles & Transport', 'icon' => 'fa-car', 'is_asset' => true, 'description' => 'কার, মাইক্রোবাস, পিকআপ, ট্রাক, মোটরসাইকেল'],
            ['code' => 'CAT-MACH', 'name_bn' => 'যন্ত্রপাতি ও ইকুইপমেন্ট', 'name_en' => 'Machinery & Equipment', 'icon' => 'fa-cogs', 'is_asset' => true, 'description' => 'মেশিন, জেনারেটর, মোটর, পাম্প, কম্প্রেসর'],
            ['code' => 'CAT-ELEC', 'name_bn' => 'বৈদ্যুতিক ও ইউটিলিটি', 'name_en' => 'Electrical & Utilities', 'icon' => 'fa-bolt', 'is_asset' => true, 'description' => 'ফ্যান, লাইট, AC, সুইচ, সার্কিট ব্রেকার, IPS'],
            ['code' => 'CAT-CLEN', 'name_bn' => 'ক্লিনিং ও পরিচ্ছন্নতা সামগ্রী', 'name_en' => 'Cleaning & Maintenance', 'icon' => 'fa-trash-o', 'is_asset' => false, 'description' => 'ক্লিনিং সামগ্রী, জীবাণুনাশক, ঝাড়ু, মপ, গ্লাভস'],
            ['code' => 'CAT-SAFE', 'name_bn' => 'নিরাপত্তা ও সুরক্ষা সরঞ্জাম', 'name_en' => 'Safety & Security', 'icon' => 'fa-shield', 'is_asset' => true, 'description' => 'হেলমেট, সেফটি জুতা, ফায়ার এক্সটিংগুইশার, সিসিটিভি'],
        ];

        foreach ($categories as $cat) {
            DB::table('erp_item_categories')->updateOrInsert(['code' => $cat['code']], array_merge($cat, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Seed Item Master
        $statCat = DB::table('erp_item_categories')->where('code', 'CAT-STAT')->first();
        $furnCat = DB::table('erp_item_categories')->where('code', 'CAT-FURN')->first();
        $itCat = DB::table('erp_item_categories')->where('code', 'CAT-ITEL')->first();
        $vehiCat = DB::table('erp_item_categories')->where('code', 'CAT-VEHI')->first();
        $elecCat = DB::table('erp_item_categories')->where('code', 'CAT-ELEC')->first();

        $items = [
            [
                'item_code' => 'ITEM-PAP-001',
                'name_bn' => 'A4 সাইজ ফটোকপি পেপার (80 GSM)',
                'name_en' => 'Double A A4 Copy Paper 80GSM',
                'category_id' => $statCat?->id,
                'category_code' => 'CAT-STAT',
                'brand' => 'Double A',
                'model_no' => 'A4-80',
                'unit' => 'Ream',
                'item_type' => 'consumable',
                'purchase_price' => 450.00,
                'average_cost' => 450.00,
                'current_stock' => 120.00,
                'min_stock' => 20.00,
                'max_stock' => 500.00,
                'reorder_level' => 30.00,
                'barcode' => '894123400001',
                'status' => true,
            ],
            [
                'item_code' => 'ITEM-PEN-002',
                'name_bn' => 'ম্যাটাডোর বলপেন (ব্ল্যাক ও ব্লু)',
                'name_en' => 'Matador Hi-School Ballpoint Pen Box',
                'category_id' => $statCat?->id,
                'category_code' => 'CAT-STAT',
                'brand' => 'Matador',
                'model_no' => 'Hi-School',
                'unit' => 'Box',
                'item_type' => 'consumable',
                'purchase_price' => 220.00,
                'average_cost' => 220.00,
                'current_stock' => 45.00,
                'min_stock' => 10.00,
                'max_stock' => 200.00,
                'reorder_level' => 15.00,
                'barcode' => '894123400002',
                'status' => true,
            ],
            [
                'item_code' => 'ITEM-TON-003',
                'name_bn' => 'এইচপি লেজারজেট টোনার কার্ট্রিজ ৮৫এ',
                'name_en' => 'HP LaserJet 85A Black Toner Cartridge',
                'category_id' => $statCat?->id,
                'category_code' => 'CAT-STAT',
                'brand' => 'HP',
                'model_no' => 'CE285A',
                'unit' => 'Pcs',
                'item_type' => 'consumable',
                'purchase_price' => 1850.00,
                'average_cost' => 1850.00,
                'current_stock' => 8.00,
                'min_stock' => 3.00,
                'max_stock' => 25.00,
                'reorder_level' => 5.00,
                'barcode' => '894123400003',
                'status' => true,
            ],
            [
                'item_code' => 'ITEM-CHR-004',
                'name_bn' => 'এক্সিকিউটিভ রিভলভিং চেয়ার (হাই ব্যাক)',
                'name_en' => 'Otobi Executive High-Back Mesh Revolving Chair',
                'category_id' => $furnCat?->id,
                'category_code' => 'CAT-FURN',
                'brand' => 'Otobi',
                'model_no' => 'EX-HB-2026',
                'unit' => 'Pcs',
                'item_type' => 'fixed_asset',
                'purchase_price' => 12500.00,
                'average_cost' => 12500.00,
                'current_stock' => 24.00,
                'min_stock' => 2.00,
                'max_stock' => 50.00,
                'reorder_level' => 4.00,
                'warranty_months' => 24,
                'barcode' => '894123400004',
                'status' => true,
            ],
            [
                'item_code' => 'ITEM-LAP-005',
                'name_bn' => 'ডেল ল্যাটিটিউড কোর আই-৭ বিজনেস ল্যাপটপ',
                'name_en' => 'Dell Latitude 5440 Core i7 13th Gen, 16GB RAM, 512GB SSD',
                'category_id' => $itCat?->id,
                'category_code' => 'CAT-ITEL',
                'brand' => 'Dell',
                'model_no' => 'Latitude 5440',
                'unit' => 'Pcs',
                'item_type' => 'fixed_asset',
                'purchase_price' => 95000.00,
                'average_cost' => 95000.00,
                'current_stock' => 15.00,
                'min_stock' => 2.00,
                'max_stock' => 30.00,
                'reorder_level' => 3.00,
                'warranty_months' => 36,
                'barcode' => '894123400005',
                'status' => true,
            ],
            [
                'item_code' => 'ITEM-AC-006',
                'name_bn' => 'গ্রী ২ টন ইনভার্টার স্প্লিট এসি',
                'name_en' => 'Gree 2.0 Ton Inverter Split Air Conditioner',
                'category_id' => $elecCat?->id,
                'category_code' => 'CAT-ELEC',
                'brand' => 'Gree',
                'model_no' => 'GS-24XFV32',
                'unit' => 'Set',
                'item_type' => 'fixed_asset',
                'purchase_price' => 88000.00,
                'average_cost' => 88000.00,
                'current_stock' => 6.00,
                'min_stock' => 1.00,
                'max_stock' => 10.00,
                'reorder_level' => 2.00,
                'warranty_months' => 60,
                'barcode' => '894123400006',
                'status' => true,
            ],
        ];

        foreach ($items as $item) {
            DB::table('erp_items')->updateOrInsert(['item_code' => $item['item_code']], array_merge($item, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Seed Vehicles
        $vehicles = [
            [
                'reg_no' => 'ঢাকা মেট্রো-চ ১৫-৯৮৭৬',
                'vehicle_name' => 'টয়োটা নোয়া মাইক্রোবাস (অফিস পুল)',
                'vehicle_type' => 'microbus',
                'brand' => 'Toyota',
                'model' => 'Noah Hybrid 2022',
                'manufacturing_year' => '2022',
                'chassis_no' => 'ZRR80-0198765',
                'engine_no' => '3ZR-FAE-45678',
                'fuel_type' => 'Octane/Hybrid',
                'driver_name' => 'মো: জসিম উদ্দিন',
                'driver_phone' => '01715998877',
                'driver_license' => 'DL-DH-123456',
                'current_mileage' => 42500.00,
                'fitness_expiry' => now()->addMonths(8),
                'tax_token_expiry' => now()->addMonths(5),
                'insurance_expiry' => now()->addMonths(11),
                'status' => 'active',
            ],
            [
                'reg_no' => 'ঢাকা মেট্রো-গ ২৩-৪৫৬৭',
                'vehicle_name' => 'টয়োটা প্রিমিও সিডান (ব্যবস্থাপনা পরিচালক)',
                'vehicle_type' => 'car',
                'brand' => 'Toyota',
                'model' => 'Premio F-EX 2021',
                'manufacturing_year' => '2021',
                'chassis_no' => 'NZT260-032145',
                'engine_no' => '1NZ-FE-78901',
                'fuel_type' => 'Octane',
                'driver_name' => 'মো: কালাম মোল্লা',
                'driver_phone' => '01815887766',
                'driver_license' => 'DL-DH-654321',
                'current_mileage' => 28300.00,
                'fitness_expiry' => now()->addMonths(4),
                'tax_token_expiry' => now()->addMonths(2),
                'insurance_expiry' => now()->addMonths(9),
                'status' => 'active',
            ],
            [
                'reg_no' => 'ঢাকা মেট্রো-ন ১১-২২৩৩',
                'vehicle_name' => 'মাহিন্দ্রা বোলেরো পিকআপ (মালামাল পরিবহন)',
                'vehicle_type' => 'pickup',
                'brand' => 'Mahindra',
                'model' => 'Bolero Maxi Truck Plus',
                'manufacturing_year' => '2023',
                'chassis_no' => 'MAH-TRK-778899',
                'engine_no' => 'M2DICR-554433',
                'fuel_type' => 'Diesel',
                'driver_name' => 'মো: মিজানুর রহমান',
                'driver_phone' => '01915776655',
                'driver_license' => 'DL-DH-987654',
                'current_mileage' => 61200.00,
                'fitness_expiry' => now()->addMonths(3),
                'tax_token_expiry' => now()->addMonths(1),
                'insurance_expiry' => now()->addMonths(6),
                'status' => 'active',
            ],
        ];

        foreach ($vehicles as $vh) {
            DB::table('erp_vehicles')->updateOrInsert(['reg_no' => $vh['reg_no']], array_merge($vh, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 6. Seed Sample Maintenance Work Orders
        $workOrders = [
            [
                'work_order_no' => 'MWO-2026-0001',
                'category' => 'ac',
                'problem_title' => 'প্রধান কনফারেন্স রুমের গ্রী এসির গ্যাস রিফিল ও জেনারেল সার্ভিসিং',
                'problem_description' => 'এসি পর্যাপ্ত ঠান্ডা হচ্ছে না এবং ফিল্টারে প্রচুর ধুলাবালি জমেছে।',
                'priority' => 'high',
                'service_provider' => 'কুল পয়েন্ট এসি সার্ভিসেস',
                'provider_contact' => '01712334455',
                'is_amc' => false,
                'spare_parts_cost' => 3500.00,
                'labor_cost' => 1500.00,
                'other_cost' => 0.00,
                'total_cost' => 5000.00,
                'start_date' => now()->subDays(5),
                'completion_date' => now()->subDays(4),
                'status' => 'completed',
                'preventive_interval_months' => 3,
                'next_due_date' => now()->addMonths(3),
                'notes' => 'কাজ সফলভাবে সম্পন্ন এবং কুলিং চেক করা হয়েছে।',
            ],
            [
                'work_order_no' => 'MWO-2026-0002',
                'category' => 'vehicle',
                'problem_title' => 'টয়োটা নোয়া মাইক্রোবাসের ইঞ্জিন অয়েল ও ফিল্টার পরিবর্তন',
                'problem_description' => '৪০,০০০ কিমি অতিক্রম করায় রুটিন মোবিল এবং এয়ার ফিল্টার পরিবর্তন আবশ্যক।',
                'priority' => 'normal',
                'service_provider' => 'নাভানা অটো ওয়ার্কশপ',
                'provider_contact' => '01912445566',
                'is_amc' => true,
                'spare_parts_cost' => 6500.00,
                'labor_cost' => 1000.00,
                'other_cost' => 0.00,
                'total_cost' => 7500.00,
                'start_date' => now()->subDays(2),
                'completion_date' => now()->subDay(),
                'status' => 'completed',
                'preventive_interval_months' => 6,
                'next_due_date' => now()->addMonths(6),
                'notes' => 'সিন্থেটিক ইঞ্জিন অয়েল এবং অরিজিনাল টয়োটা ফিল্টার প্রতিস্থাপন করা হয়েছে।',
            ],
        ];

        foreach ($workOrders as $wo) {
            DB::table('erp_maintenance_work_orders')->updateOrInsert(['work_order_no' => $wo['work_order_no']], array_merge($wo, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 7. Seed Sample Purchase Requisitions & Orders
        $pr = [
            'pr_no' => 'PR-2026-0001',
            'requested_by' => 'মো: আরিফুল ইসলাম (আইটি অফিসার)',
            'department' => 'IT Department',
            'priority' => 'normal',
            'purpose' => 'নতুন নিযুক্ত কর্মীদের জন্য ৩টি ল্যাপটপ ও মাউস ক্রয়',
            'estimated_cost' => 285000.00,
            'status' => 'approved',
            'notes' => 'ম্যানেজিং ডিরেক্টর কর্তৃক অনুমোদিত।',
        ];
        DB::table('erp_purchase_requisitions')->updateOrInsert(['pr_no' => $pr['pr_no']], array_merge($pr, ['created_at' => now(), 'updated_at' => now()]));

        // 8. Seed Sample Material Issue
        $min = [
            'min_no' => 'MIN-2026-0001',
            'issue_date' => now()->subDays(3),
            'department' => 'Accounts & Finance',
            'employee_name' => 'সাদিয়া জাহান (হিসাব কর্মকর্তা)',
            'purpose' => 'মাসিক ভাউচার প্রিন্ট ও অফিসের নিয়মিত কাজ',
            'issue_type' => 'consumable',
            'total_items' => 5,
            'total_value' => 2250.00,
            'issued_by' => 'ফারহানা আহমেদ (স্টোর কিপার)',
            'status' => 'issued',
            'notes' => '৫ রিম A4 পেপার সরবরাহ করা হলো।',
        ];
        DB::table('erp_material_issues')->updateOrInsert(['min_no' => $min['min_no']], array_merge($min, ['created_at' => now(), 'updated_at' => now()]));

        // 9. Seed Sample Stock Audit
        $audit = [
            'audit_no' => 'AUDIT-2026-Q1',
            'audit_date' => now()->subDays(10),
            'auditor_name' => 'রহমান এন্ড কোং (অভ্যন্তরীণ নিরীক্ষক)',
            'total_items_checked' => 45,
            'total_variance_items' => 0,
            'total_variance_value' => 0.00,
            'status' => 'approved',
            'notes' => 'প্রথম প্রান্তিকের ফিজিক্যাল স্টক ভেরিফিকেশন সম্পন্ন। কোনো ঘাটতি বা অমিল পাওয়া যায়নি।',
        ];
        DB::table('erp_stock_audits')->updateOrInsert(['audit_no' => $audit['audit_no']], array_merge($audit, ['created_at' => now(), 'updated_at' => now()]));
    }
}

