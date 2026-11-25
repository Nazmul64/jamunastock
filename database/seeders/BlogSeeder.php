<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'কৃষি ও বিনিয়োগ বিশ্লেষণ',
                'slug' => 'agriculture-investment-analysis',
                'description' => 'কৃষিভিত্তিক শেয়ার মার্কেট ও যৌথ বিনিয়োগের আধুনিক সম্ভাবনা এবং বিশ্লেষণ।',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'সফলতার গল্প ও অভিজ্ঞতা',
                'slug' => 'success-stories',
                'description' => 'যমুনা স্টক পরিবার ও সফল খামারি ও গ্রাহকদের বাস্তব জীবনের গল্প।',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'ব্যবসায়িক আপডেট ও পলিসি',
                'slug' => 'business-updates-and-policy',
                'description' => 'কোম্পানির সর্বশেষ ব্যবসায়িক সিদ্ধান্ত, প্রজেক্ট আপডেট এবং বিনিয়োগ পলিসি।',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'টেকসই খাদ্য নিরাপত্তা',
                'slug' => 'sustainable-food-security',
                'description' => 'স্বাস্থ্যসম্মত খাদ্য উৎপাদন, সংরক্ষণ এবং ডিস্ট্রিবিউশন গাইডলাইন।',
                'status' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $catData) {
            $cat = BlogCategory::firstOrCreate(['slug' => $catData['slug']], $catData);

            if ($cat->slug === 'agriculture-investment-analysis') {
                BlogPost::firstOrCreate(
                    ['slug' => 'future-of-agro-investment-bangladesh'],
                    [
                        'category_id' => $cat->id,
                        'title' => 'বাংলাদেশে কৃষিভিত্তিক বিনিয়োগ ও শেয়ার বাজারের নতুন দিগন্ত',
                        'summary' => 'কৃষি উৎপাদন ও সাপ্লাই চেইনে পাবলিক পার্টিসিপেশনের মাধ্যমে বিনিয়োগে নিরাপত্তা ও নিয়মিত মুনাফা অর্জনের বৈজ্ঞানিক উপায়।',
                        'content' => '<p>বাংলাদেশের অর্থনীতির মূল চালিকাশক্তি কৃষি। সনাতন পদ্ধতির বাইরে গিয়ে এখন কৃষি রূপ নিয়েছে বৃহৎ বাণিজ্যিক এগ্রো-ইন্ডাস্ট্রিতে। যমুনা স্টক মার্কেট পিএলসি সেই আধুনিক ধারণাকে সাধারণ মানুষের দোরগোড়ায় নিয়ে এসেছে।</p><h5>নিরাপদ বিনিয়োগের মূল ভিত্তি:</h5><p>১. FMCG এবং নিত্যপ্রয়োজনীয় ভোগ্যপণ্যের সারা বছরের অবিরাম চাহিদা।<br>২. বৈজ্ঞানিক পদ্ধতিতে গবাদিপশু ও মৎস্য খামার পরিচালনা।<br>৩. স্বচ্ছ মনিটরিং ও ত্রৈমাসিক/বার্ষিক মুনাফা প্রদান ব্যবস্থা।</p><p>ক্ষুদ্র ও মাঝারি বিনিয়োগকারীদের একত্র করে শক্তিশালী এগ্রো-ইকোসিস্টেম গড়ার এই উদ্যোগে অংশ নিয়ে দেশের উন্নয়নে অংশীদার হোন।</p>',
                        'author_name' => 'যমুনা এডিটরিয়াল টিম',
                        'views_count' => 152,
                        'is_featured' => true,
                        'status' => 'published',
                        'published_at' => now()->subDays(2),
                    ]
                );
            }

            if ($cat->slug === 'business-updates-and-policy') {
                BlogPost::firstOrCreate(
                    ['slug' => 'fmcg-and-real-estate-sector-expansion-2026'],
                    [
                        'category_id' => $cat->id,
                        'title' => '২০২৬ সালে যমুনার ৯টি ইনভেস্টমেন্ট সেক্টরের সম্প্রসারণ পরিকল্পনা',
                        'summary' => 'ভোগ্যপণ্য, হাউজিং, মৎস্য, ডেইরি ও রিসোর্ট প্রকল্পের আওতায় নতুন কর্মসংস্থান ও সর্বোচ্চ রিটার্ন নিশ্চিতকরণে গৃহীত পদক্ষেপ।',
                        'content' => '<p>যমুনা স্টক মার্কেট পিএলসি গ্রাহকদের আস্থার প্রতিদান দিতে ৯টি আলাদা সেক্টরে বিশেষ বিনিয়োগ কর্মসূচি চালু করেছে। এর মধ্যে ভোগ্যপণ্য (FMCG) এবং আবাসন প্রকল্পে সবচেয়ে বেশি প্রবৃদ্ধি লক্ষ্য করা গেছে।</p><p>গ্রাহকদের জন্য নিয়মিত ডিজিটাল রসিদ, অনলাইন ইনকোয়ারি ট্র্যাকিং এবং ব্যাংকিং চ্যানেলে স্বয়ংক্রিয় মুনাফা প্রদান প্রক্রিয়াকে সম্পূর্ণরূপে অটোমেশনের আওতায় আনা হয়েছে।</p>',
                        'author_name' => 'রিসার্চ অ্যান্ড অ্যানালাইসিস সেল',
                        'views_count' => 98,
                        'is_featured' => true,
                        'status' => 'published',
                        'published_at' => now()->subDay(),
                    ]
                );
            }
        }
    }
}
