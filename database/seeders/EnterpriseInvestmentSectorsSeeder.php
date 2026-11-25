<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnterpriseInvestmentSectorsSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            [
                'sector_no' => '01',
                'slug' => 'fmcg',
                'code' => 'FMCG',
                'receipt_prefix' => 'FMCG-RCP',
                'name_bn' => 'ভোগ্যপণ্য ও দৈনন্দিন পণ্য ব্যবসা (FMCG)',
                'name_en' => 'Consumer Goods (FMCG)',
                'investment_range' => '১–৫০ লাখ টাকা',
                'min_amount' => 100000.00,
                'max_amount' => 5000000.00,
                'duration' => '১ থেকে ৫ বছর',
                'icon' => 'fa fa-shopping-basket',
                'image' => 'frontend/assets/images/sectors/fmcg.jpg',
                'banner_image' => 'frontend/assets/images/sectors/fmcg.jpg',
                'description' => 'দৈনন্দিন নিত্যপ্রয়োজনীয় পণ্য ও এফএমসিজি পণ্যের বৃহৎ ডিস্ট্রিবিউশন ও রিটেইল চেইন নেটওয়ার্ক। নগদ ঘূর্ণায়মান পুঁজিতে নিয়মিত ক্যাশ ফ্লো ও নির্ভরযোগ্য মুনাফা।',
                'profit_loss_policy' => 'বাস্তব পণ্য বিক্রয় ও টার্নওভার অনুযায়ী নিয়মিত লভ্যাংশ বণ্টন।',
                'is_active' => true,
                'order_num' => 1,
                'features' => json_encode([
                    'নিত্যপ্রয়োজনীয় পণ্যের সার্বক্ষণিক বাজার চাহিদা',
                    'স্বল্পমেয়াদের দ্রুত ঘূর্ণায়মান বাণিজ্যিক ক্যাশ ফ্লো',
                    'মাসিক বা ত্রৈমাসিক ভিত্তিতে মুনাফা উত্তোলনের সুযোগ'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'FMCG খাতে বিনিয়োগের সর্বনিম্ন সময়কাল কত?', 'a' => 'সর্বনিম্ন ১ বছর থেকে ৫ বছর মেয়াদে বিনিয়োগ করা যায়।'],
                    ['q' => 'মুনাফা কখন বণ্টন করা হয়?', 'a' => 'চুক্তি অনুযায়ী প্রতি মাসে বা প্রতি বছর শেষে মুনাফা প্রদান করা হয়।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'চুক্তি চলাকালীন মেয়াদান্তে মূলধন সম্পূর্ণ নিরাপদ থাকে। সময়পূর্ব উত্তোলনে নির্ধারিত পলিসি প্রযোজ্য।'
            ],
            [
                'sector_no' => '02',
                'slug' => 'real-estate',
                'code' => 'RE',
                'receipt_prefix' => 'RE-RCP',
                'name_bn' => 'আবাসন ও রিয়েল এস্টেট বিনিয়োগ',
                'name_en' => 'Housing / Real Estate',
                'investment_range' => '৫ লাখ–৫ কোটি টাকা',
                'min_amount' => 500000.00,
                'max_amount' => 50000000.00,
                'duration' => '৩ থেকে ১০ বছর',
                'icon' => 'fa fa-building',
                'image' => 'frontend/assets/images/sectors/real_estate.jpg',
                'banner_image' => 'frontend/assets/images/sectors/real_estate.jpg',
                'description' => 'আধুনিক বহুতল বাণিজ্যিক ও আবাসিক ভবন নির্মাণ প্রকল্প। দীর্ঘমেয়াদি সর্বোচ্চ মূলধন বৃদ্ধি ও নিশ্চিত প্রপার্টি ব্যাকড সম্পদ।',
                'profit_loss_policy' => 'ফ্ল্যাট ও স্পেস বিক্রয় অগ্রগতির ওপর ভিত্তি করে চুক্তিবদ্ধ লভ্যাংশ বণ্টন।',
                'is_active' => true,
                'order_num' => 2,
                'features' => json_encode([
                    'রিয়েল এস্টেট প্রপার্টি সমর্থিত শক্তিশালী বিনিয়োগ',
                    'মূলধন বৃদ্ধির পাশাপাশি বাৎসরিক আকর্ষণীয় লভ্যাংশ',
                    'মেয়াদের যেকোনো ধাপে প্রপার্টিতে রূপান্তরের অপশন'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'রিয়েল এস্টেটে প্রজেক্ট শেষ হতে কত সময় লাগে?', 'a' => 'সাধারণত ৩ থেকে ৭ বছরের প্রজেক্ট সাইকেল থাকে।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'রেজিস্ট্রি ও চুক্তিনামা সরাসরি প্রধান কার্যালয়ে সম্পাদিত হয়।'
            ],
            [
                'sector_no' => '03',
                'slug' => 'fisheries',
                'code' => 'FISH',
                'receipt_prefix' => 'FISH-RCP',
                'name_bn' => 'মৎস্য খামার ও মাছ চাষ',
                'name_en' => 'Fisheries & Aquaculture',
                'investment_range' => '২–৫০ লাখ টাকা',
                'min_amount' => 200000.00,
                'max_amount' => 5000000.00,
                'duration' => '১ থেকে ৫ বছর',
                'icon' => 'fa fa-fish',
                'image' => 'frontend/assets/images/sectors/fisheries.jpg',
                'banner_image' => 'frontend/assets/images/sectors/fisheries.jpg',
                'description' => 'বায়োফ্লক, আরএএস ও আধুনিক জলাশয়ে বাণিজ্যিক কার্প, পাঙ্গাশ, শিং ও পাবদা মাছের উন্নত চাষাবাদ ও পাইকারি সরবরাহ।',
                'profit_loss_policy' => 'মাছের হারভেস্টিং ও পাইকারি বাজারজাতকরণের পর প্রকৃত বিক্রয়লব্ধ আয় থেকে চুক্তিভিত্তিক বণ্টন।',
                'is_active' => true,
                'order_num' => 3,
                'features' => json_encode([
                    'বায়োফ্লক ও বৈজ্ঞানিক পদ্ধতিতে ঝুঁকিহীন চাষ',
                    'প্রতি ৬ মাস বা ১ বছর অন্তর হারভেস্টিং চক্র',
                    'উচ্চ আমিষ চাহিদায় বাজারমূল্য সর্বদা ঊর্ধ্বমুখী'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'মৎস্য প্রকল্পে ঝুঁকি ব্যবস্থাপনা কেমন?', 'a' => 'আধুনিক এয়ারেটর, জেনারেটর ব্যাকআপ ও ভেটেরিনারি পরামর্শক দল সার্বক্ষণিক নিয়োজিত থাকে।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'মৎস্য খামার পরিদর্শন সাপেক্ষে চুক্তি সম্পন্ন করা হয়।'
            ],
            [
                'sector_no' => '04',
                'slug' => 'livestock',
                'code' => 'LIVE',
                'receipt_prefix' => 'LIVE-RCP',
                'name_bn' => 'গবাদিপশু ও পশুপালন খামার',
                'name_en' => 'Livestock Farming',
                'investment_range' => '২–৫০ লাখ টাকা',
                'min_amount' => 200000.00,
                'max_amount' => 5000000.00,
                'duration' => '১ থেকে ১০ বছর',
                'icon' => 'fa fa-paw',
                'image' => 'frontend/assets/images/sectors/livestock.jpg',
                'banner_image' => 'frontend/assets/images/sectors/livestock.jpg',
                'description' => 'কোরবানি ও নিয়মিত বাজারের জন্য উন্নত জাতের ষাঁড় মোটাতাজাকরণ, ডেইরি দুধ উৎপাদন ও ছাগল খামার ব্যবস্থাপনা।',
                'profit_loss_policy' => '১০০ দিনের মোটাতাজাকরণ সাইকেল অথবা বাৎসরিক বিক্রির নিরীক্ষিত আয় বণ্টন।',
                'is_active' => true,
                'order_num' => 4,
                'features' => json_encode([
                    '১০০ দিনের মোটাতাজাকরণ ও দীর্ঘমেয়াদি ডেইরি সাইকেল',
                    'কোরবানি মৌসুমে বিশেষ অতিরিক্ত মুনাফার সুযোগ',
                    'বীমাকৃত গবাদিপশু ও সার্বক্ষণিক ভেটেরিনারি কেয়ার'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'পশু মারা গেলে বিনিয়োগের কী হবে?', 'a' => 'আমাদের খামারের প্রতিটি পশু বীমা আওতাভুক্ত এবং কোম্পানি নিজস্ব রিজার্ভ দ্বারা মূলধন সুরক্ষিত রাখে।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => '১০ বছর মেয়াদের ক্ষেত্রে বাৎসরিক অথবা এককালীন লাভ উত্তোলনের চুক্তি করা যায়।'
            ],
            [
                'sector_no' => '05',
                'slug' => 'farmhouse',
                'code' => 'FARM',
                'receipt_prefix' => 'FARM-RCP',
                'name_bn' => 'খামারবাড়ি ও সংশ্লিষ্ট প্রকল্প',
                'name_en' => 'Farmhouse Projects',
                'investment_range' => '৫–৫০ লাখ টাকা',
                'min_amount' => 500000.00,
                'max_amount' => 5000000.00,
                'duration' => '২ থেকে ১০ বছর',
                'icon' => 'fa fa-home',
                'image' => 'frontend/assets/images/sectors/farmhouse.jpg',
                'banner_image' => 'frontend/assets/images/sectors/farmhouse.jpg',
                'description' => 'প্রাকৃতিক পরিবেশে মনোরম খামারবাড়ি, ফলদ বাগান, মাশরুম চাষ ও ইকোট্যুরিজম কেন্দ্রিক বহুমুখী কৃষি উন্নয়ন।',
                'profit_loss_policy' => 'খামারবাড়ির ফলজ বিক্রি ও অতিথি ভাড়া আয়ের সমন্বিত বণ্টন।',
                'is_active' => true,
                'order_num' => 5,
                'features' => json_encode([
                    'কৃষি ও বিনোদন কেন্দ্রের দ্বিমুখী আয়ের সুযোগ',
                    'বিনিয়োগকারীর জন্য বিনামূল্যে খামারবাড়িতে অবকাশ যাপনের সুবিধা',
                    'সবুজ প্রকৃতির সাথে দীর্ঘমেয়াদি নিরাপদ প্রবৃদ্ধি'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'বিনিয়োগকারী কি খামারবাড়িতে যেতে পারেন?', 'a' => 'হ্যাঁ, যেকোনো সময় অগ্রিম নোটিশে ভিজিট ও স্টে করার সুবিধা রয়েছে।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'চুক্তির শর্তাবলী অনুযায়ী বার্ষিক লভ্যাংশ হিসাবভুক্ত হয়।'
            ],
            [
                'sector_no' => '06',
                'slug' => 'plot-trading',
                'code' => 'PLOT',
                'receipt_prefix' => 'PLOT-RCP',
                'name_bn' => 'জমি ক্রয়, বিক্রয় ও Land Trading',
                'name_en' => 'Plot Buying & Selling',
                'investment_range' => '৫ লাখ–৫ কোটি টাকা',
                'min_amount' => 500000.00,
                'max_amount' => 50000000.00,
                'duration' => '২ থেকে ৭ বছর',
                'icon' => 'fa fa-map-marked-alt',
                'image' => 'frontend/assets/images/sectors/plot_trading.jpg',
                'banner_image' => 'frontend/assets/images/sectors/plot_trading.jpg',
                'description' => 'দ্রুত বর্ধনশীল উপশহর ও অর্থনৈতিক অঞ্চলের সংযোগস্থলে বাণিজ্যিক ও আবাসিক প্লট ক্রয়, সীমানা প্রাচীর ও লাভজনক মূল্যে বিক্রয়।',
                'profit_loss_policy' => 'প্লট পুনরায় বিক্রির মাধ্যমে অর্জিত ক্যাপিটাল গেইনের চুক্তিভিত্তিক অংশীদারিত্ব।',
                'is_active' => true,
                'order_num' => 6,
                'features' => json_encode([
                    'নিষ্কণ্টক জমি ও শতভাগ বৈধ কাগজপত্র',
                    'অবকাঠামো উন্নয়নের সাথে সাথে জমির দ্রুত মূল্যবৃদ্ধি',
                    'প্লট হস্তান্তরের সাথে সাথে উচ্চ মুনাফা'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'প্লট কেনাবেচায় দলিল কাদের নামে হয়?', 'a' => 'কোম্পানি ট্রাস্ট বা অংশীদারিত্বের আইনি ফ্রেমওয়ার্কে রেজিস্ট্রি কার্যক্রম পরিচালিত হয়।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'জমি ক্রয় বিক্রয়ের নিট মুনাফা প্লট রেজিস্ট্রি সমাপ্তিতে নির্ধারিত হয়।'
            ],
            [
                'sector_no' => '07',
                'slug' => 'resort',
                'code' => 'RESORT',
                'receipt_prefix' => 'RESORT-RCP',
                'name_bn' => 'রিসোর্ট ও পর্যটন বিনিয়োগ',
                'name_en' => 'Resort & Eco-Tourism',
                'investment_range' => '৫ লাখ–২ কোটি টাকা',
                'min_amount' => 500000.00,
                'max_amount' => 20000000.00,
                'duration' => '৩ থেকে ১০ বছর',
                'icon' => 'fa fa-umbrella-beach',
                'image' => 'frontend/assets/images/sectors/resort.jpg',
                'banner_image' => 'frontend/assets/images/sectors/resort.jpg',
                'description' => 'হালনাগাদ পর্যটন অঞ্চল ও প্রাকৃতিক লেক/নদী তীরে ইকো কটেজ, কনফারেন্স হল, সুইমিং পুল ও লাক্সারি বিনোদন রিসোর্ট।',
                'profit_loss_policy' => 'রিসোর্টের রুম বুকিং, রেস্তোরাঁ ও অনুষ্ঠান ভেন্যু ভাড়ার নিরীক্ষিত নিট আয়ের বণ্টন।',
                'is_active' => true,
                'order_num' => 7,
                'features' => json_encode([
                    'বার্ষিক পর্যটন বৃদ্ধি ও পর্যটকদের ক্রমবর্ধমান বুকিং',
                    'বিনিয়োগকারীদের জন্য প্রতি বছর ফ্রি ফ্যামিলি স্টে সুবিধা',
                    'আজীবন প্যাসিভ ক্যাশ ফ্লো তৈরির সুযোগ'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'রিসোর্টে স্টে করার কী ডিসকাউন্ট পাবো?', 'a' => 'বিনিয়োগকারীরা বিশেষ ভিআইপি কার্ড পান যাতে ফ্রি স্টে এবং খাদ্য বিলের ওপর বিশেষ ছাড় থাকে।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'ত্রৈমাসিক বা বাৎসরিক ভিত্তিতে রিসোর্টের অডিট রিপোর্ট প্রকাশিত হয়।'
            ],
            [
                'sector_no' => '08',
                'slug' => 'priority-investment',
                'code' => 'PRI',
                'receipt_prefix' => 'PRI-RCP',
                'name_bn' => 'বিশেষ অগ্রাধিকারভিত্তিক বিনিয়োগ',
                'name_en' => 'Priority-Based Investment',
                'investment_range' => '৫ লাখ–৫০ লাখ টাকা',
                'min_amount' => 500000.00,
                'max_amount' => 5000000.00,
                'duration' => '১ থেকে ৩ বছর',
                'icon' => 'fa fa-star',
                'image' => 'frontend/assets/images/sectors/priority.jpg',
                'banner_image' => 'frontend/assets/images/sectors/priority.jpg',
                'description' => 'জরুরি মৌসুমি ফসল সংরক্ষণ, আলুর কোল্ড স্টোরেজ রিজার্ভ, পেঁয়াজ ও আদা স্টক এবং হাই-ডিমান্ড পণ্য ট্রেডিং।',
                'profit_loss_policy' => 'স্বল্পমেয়াদি চক্রাকার ট্রেডিং ও বাজার সরবরাহ আয়ের অগ্রাধিকার বণ্টন।',
                'is_active' => true,
                'order_num' => 8,
                'features' => json_encode([
                    'সর্বোচ্চ সুরক্ষাযুক্ত প্রিমিয়াম ইনভেস্টমেন্ট স্কিম',
                    'স্বল্পতম সময়ে ক্যাপিটাল রোটেশন',
                    'গ্যারান্টিযুক্ত অগ্রাধিকারভিত্তিতে রিটার্ন নিষ্পত্তির সুবিধা'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'অগ্রাধিকারভিত্তিক বিনিয়োগের সুবিধা কী?', 'a' => 'কোম্পানির যেকোনো সাধারণ খাতের চেয়ে এই খাতের ক্যাশ রোটেশন ও লভ্যাংশ প্রদান সবার আগে নিষ্পন্ন হয়।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'সীমিত সংখ্যক প্রিমিয়াম স্লটে বিনিয়োগ বরাদ্দ দেওয়া হয়।'
            ],
            [
                'sector_no' => '09',
                'slug' => 'import-export',
                'code' => 'IE',
                'receipt_prefix' => 'IE-RCP',
                'name_bn' => 'আমদানি ও রপ্তানিভিত্তিক ব্যবসা',
                'name_en' => 'Import & Export',
                'investment_range' => '১০ লাখ–৫ কোটি টাকা',
                'min_amount' => 1000000.00,
                'max_amount' => 50000000.00,
                'duration' => '১ থেকে ৫ বছর',
                'icon' => 'fa fa-ship',
                'image' => 'frontend/assets/images/sectors/import_export.jpg',
                'banner_image' => 'frontend/assets/images/sectors/import_export.jpg',
                'description' => 'কৃষিযন্ত্রাংশ, উন্নত সংকর বীজ আমদানি এবং আন্তর্জাতিক বাজারে প্রক্রিয়াজাত মসলা, শুঁটকি ও সুগন্ধি চালের নিয়মিত রপ্তানি।',
                'profit_loss_policy' => 'শিপমেন্ট ও এলসি নিষ্পত্তির পর প্রকৃত ফরেন কারেন্সি লাভ বণ্টন।',
                'is_active' => true,
                'order_num' => 9,
                'features' => json_encode([
                    'আন্তর্জাতিক বাণিজ্যের বৈদেশিক মুদ্রা আয়ের উৎস',
                    'ব্যাংক এলসি ও কাস্টমস ছাড়ের নির্ভরযোগ্য ফ্রেমওয়ার্ক',
                    'শিপমেন্ট সমাপ্তিতে নিশ্চিত ডলার মুনাফা'
                ], JSON_UNESCAPED_UNICODE),
                'faq' => json_encode([
                    ['q' => 'এলসি সংক্রান্ত কোনো ঝুঁকি আছে কি?', 'a' => 'শতভাগ কনফার্মড এলসি এবং আন্তর্জাতিক শিপিং ইন্স্যুরেন্স নিশ্চিত করে কার্যক্রম পরিচালিত হয়।']
                ], JSON_UNESCAPED_UNICODE),
                'terms' => 'আমদানি রপ্তানি নীতিমালা ও সরকারি শুল্ক আইন অনুযায়ী পরিচালিত।'
            ]
        ];

        foreach ($sectors as $sectorData) {
            $existing = DB::table('investment_sectors')->where('slug', $sectorData['slug'])->orWhere('code', $sectorData['code'])->first();
            if ($existing) {
                DB::table('investment_sectors')->where('id', $existing->id)->update($sectorData);
                $sectorId = $existing->id;
            } else {
                $sectorId = DB::table('investment_sectors')->insertGetId(array_merge($sectorData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            // Seed default plans for this sector
            $defaultPlans = [
                [
                    'sector_id' => $sectorId,
                    'name' => '১ বছর মেয়াদি বার্ষিক স্কিম',
                    'duration_years' => 1,
                    'profit_type' => 'percentage',
                    'profit_rate' => 12.00,
                    'profit_frequency' => 'yearly',
                    'min_amount' => $sectorData['min_amount'],
                    'max_amount' => $sectorData['max_amount'],
                    'principal_return_type' => 'at_maturity',
                    'description' => '১ বছর মেয়াদান্তে আসল ফেরত সহ বার্ষিক ১২% হারে প্রদেয় মুনাফা।',
                    'is_active' => true,
                    'order_num' => 1,
                ],
                [
                    'sector_id' => $sectorId,
                    'name' => '৩ বছর মেয়াদি মাসিক লভ্যাংশ স্কিম',
                    'duration_years' => 3,
                    'profit_type' => 'percentage',
                    'profit_rate' => 14.00,
                    'profit_frequency' => 'monthly',
                    'min_amount' => $sectorData['min_amount'],
                    'max_amount' => $sectorData['max_amount'],
                    'principal_return_type' => 'at_maturity',
                    'description' => '৩ বছর মেয়াদে প্রতি মাসে নিয়মিত মুনাফা গ্রহণ এবং ৩ বছর শেষে আসল ফেরত।',
                    'is_active' => true,
                    'order_num' => 2,
                ],
                [
                    'sector_id' => $sectorId,
                    'name' => '৫ বছর মেয়াদি গ্রোথ স্কিম',
                    'duration_years' => 5,
                    'profit_type' => 'percentage',
                    'profit_rate' => 16.00,
                    'profit_frequency' => 'yearly',
                    'min_amount' => $sectorData['min_amount'],
                    'max_amount' => $sectorData['max_amount'],
                    'principal_return_type' => 'at_maturity',
                    'description' => '৫ বছর মেয়াদে আকর্ষণীয় বাৎসরিক ১৬% হারে মুনাফা ও সর্বোচ্চ মূলধন নিরাপত্তা।',
                    'is_active' => true,
                    'order_num' => 3,
                ],
                [
                    'sector_id' => $sectorId,
                    'name' => '১০ বছর মেয়াদি মেগা টার্ম স্কিম (ম্যাচিউরিটি বোনাস)',
                    'duration_years' => 10,
                    'profit_type' => 'contractual',
                    'profit_rate' => 18.00,
                    'profit_frequency' => 'at_maturity',
                    'min_amount' => $sectorData['min_amount'],
                    'max_amount' => $sectorData['max_amount'],
                    'principal_return_type' => 'at_maturity',
                    'description' => '১০ বছর মেয়াদে দীর্ঘমেয়াদি মূলধন বৃদ্ধি, মেয়াদ শেষে আসল + আকর্ষণীয় মোট মুনাফা এককালীন প্রদেয়।',
                    'is_active' => true,
                    'order_num' => 4,
                ]
            ];

            foreach ($defaultPlans as $plan) {
                $planExists = DB::table('investment_plans')->where('sector_id', $sectorId)->where('name', $plan['name'])->first();
                if (!$planExists) {
                    DB::table('investment_plans')->insert(array_merge($plan, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        }
    }
}
