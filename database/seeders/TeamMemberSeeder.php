<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        if (TeamMember::count() == 0) {
            TeamMember::create([
                'name' => 'MD. Sakibul Hasan',
                'designation' => 'Chief Executive Officer (CEO)',
                'photo' => 'upload/employee/1681068709iEoZI.png',
                'order_num' => 1,
                'is_active' => true,
            ]);

            TeamMember::create([
                'name' => 'Khandaker Rafiqul Islam',
                'designation' => 'Head of Operations',
                'photo' => 'upload/employee/1681068709mUqO8.png',
                'order_num' => 2,
                'is_active' => true,
            ]);

            TeamMember::create([
                'name' => 'Tahmina Akter',
                'designation' => 'Chief Financial Officer (CFO)',
                'photo' => 'upload/employee/1681068709TBQtp.png',
                'order_num' => 3,
                'is_active' => true,
            ]);

            TeamMember::create([
                'name' => 'Tanvir Ahmed',
                'designation' => 'Chief Technology Officer (CTO)',
                'photo' => 'upload/employee/1681069008oi1CY.jpg',
                'order_num' => 4,
                'is_active' => true,
            ]);
        }
    }
}
