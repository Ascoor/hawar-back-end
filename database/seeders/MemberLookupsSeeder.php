<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberLookupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'category' => 'MembershipType',
                'value' => 'زوجة',
            ],
            [
                'category' => 'MembershipType',
                'value' => 'والدة الزوج',
            ],
            [
                'category' => 'MembershipType',
                'value' => 'والد الزوج',
            ],
            [
                'category' => 'MembershipType',
                'value' => 'ابن',
            ],
            [
                'category' => 'MembershipType',
                'value' => 'إبنة',
            ],
            [
                'category' => 'Class',
                'value' => 'عادية',
            ],
            [
                'category' => 'Class',
                'value' => 'ذوي احتياجات خاصة',
            ],
            [
                'category' => 'Class',
                'value' => 'شباب ورياضة',
            ],
            [
                'category' => 'Class',
                'value' => 'شرطة',
            ],
            [
                'category' => 'Class',
                'value' => 'هيئة قضائية',
            ],
            [
                'category' => 'Class',
                'value' => 'قوات مسلحة',
            ],
            [
                'category' => 'Religion',
                'value' => 'مسلم',
            ],
            [
                'category' => 'Religion',
                'value' => 'مسيحى',
            ],
            [
                'category' => 'Gender',
                'value' => 'ذكر',
            ],
            [
                'category' => 'Gender',
                'value' => 'أنثى',
            ],
        ];

        DB::table('member_lookups')->insert($data);
    }
}
