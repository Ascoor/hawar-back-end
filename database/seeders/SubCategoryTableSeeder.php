<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            // Assuming you have the ID of the 'memberType' category as 1
            $memberTypeSubCategories = [
                [
                    'category_id' => 1,
                    'name_en' => 'working',
                    'name_ar' => 'عامل',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'affiliate',
                    'name_ar' => 'تابع',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'founding',
                    'name_ar' => 'مؤسس',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'honory',
                    'name_ar' => 'فخري',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'seasonal',
                    'name_ar' => 'موسمي',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'athelitic',
                    'name_ar' => 'رياضي',
                ],
                [
                    'category_id' => 1,
                    'name_en' => 'A permit',
                    'name_ar' => 'تصريح',
                ],
            ];

            // Assuming you have the ID of the 'relation' category as 2
            $relationSubCategories = [
                [
                    'category_id' => 2,
                    'name_en' => 'owner',
                    'name_ar' => 'العضو الأساسي',
                ],
                [
                    'category_id' => 2,
                    'name_en' => 'husband',
                    'name_ar' => 'زوجة',
                ],
                [
                    'category_id' => 2,
                    'name_en' => 'wife',
                    'name_ar' => 'زوج',
                ],
                [
                    'category_id' => 2,
                    'name_en' => 'son',
                    'name_ar' => 'ابن',
                ],
                [
                    'category_id' => 2,
                    'name_en' => 'daughter',
                    'name_ar' => 'إبنة',
                ],
                [
                    'category_id' => 2,
                    'name_en' => "husband's father",
                    'name_ar' => 'والد الزوج',
                ],
                [
                    'category_id' => 2,
                    'name_en' => "husband's mother",
                    'name_ar' => 'والدة الزوج',
                ],
                [
                    'category_id' => 2,
                    'name_en' => "wife's father",
                    'name_ar' => 'والد الزوجة',
                ],
                [
                    'category_id' => 2,
                    'name_en' => "wife's mother",
                    'name_ar' => 'والدة الزوجة',
                ],

            ];

            // Assuming you have the ID of the 'status' category as 3
                $statusSubCategories = [
                    [
                        'category_id' => 3,
                        'name_en' => 'active',
                        'name_ar' => 'فعال',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'dropped',
                        'name_ar' => 'غير نشط',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'expired',
                        'name_ar' => 'مسقط',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'suspended',
                        'name_ar' => 'موقوف',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'sons over 25',
                        'name_ar' => 'ابن فوق 25',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'dead',
                        'name_ar' => 'متوفى',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'paid',
                        'name_ar' => 'مسدد',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'unpaid',
                        'name_ar' => 'غير مسدد',
                    ],
                    [
                        'category_id' => 3,
                        'name_en' => 'delete',
                        'name_ar' => 'محذوف',
                    ],
                ];

            // Assuming you have the ID of the 'excludedCategories' category as 4
            $excludedCategorySubCategories = [
                [
                    'category_id' => 4,
                    'name_en' => 'Normal',
                    'name_ar' => 'عادية',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'Police',
                    'name_ar' => 'شرطة',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'Judges',
                    'name_ar' => 'القضاه',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'jurnalists',
                    'name_ar' => 'صحفيين',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'Warrior Forces',
                    'name_ar' => 'ق محاربين',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'Sports Affairs',
                    'name_ar' => 'ش رياضة',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'Armed Forces',
                    'name_ar' => 'قوات مسلحة',
                ],
                [
                    'category_id' => 4,
                    'name_en' => 'People with Needs',
                    'name_ar' => 'ذوي إحتياجات',
                ],

            ];

            // Assuming you have the ID of the 'gender' category as 5
            $genderSubCategories = [
                [
                    'category_id' => 5,
                    'name_en' => 'male',
                    'name_ar' => 'ذكر',
                ],
                [
                    'category_id' => 5,
                    'name_en' => 'female',
                    'name_ar' => 'أنثى',
                ],
                // Add more subcategories for 'gender' as needed
            ];
            // Assuming you have the ID of the 'religion' category as 6
            $religionSubCategories = [
                [
                    'category_id' => 6,
                    'name_en' => 'muslim',
                    'name_ar' => 'مسلم',
                ],
                [
                    'category_id' => 6,
                    'name_en' => 'christian',
                    'name_ar' => 'مسيحي',
                ],
                // Add more subcategories for 'religion' as needed
            ];
             // Assuming you have the ID of the 'RenewalStatus' category as 7
            $RenewalStatusSubCategories = [
                [
                    'category_id' => 7,
                    'name_en' => 'renewal',
                    'name_ar' => 'مجدد',
                ],
                [
                    'category_id' => 7,
                    'name_en' => 'unrenewal',
                    'name_ar' => 'غير مجدد',
                ],
                // Add more subcategories for 'RenewalStatus' as needed
            ];

            foreach ($memberTypeSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }

            foreach ($relationSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }

            foreach ($statusSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }
            foreach ($excludedCategorySubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }

            foreach ($genderSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }
            foreach ($religionSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }
            foreach ($RenewalStatusSubCategories as $subCategory) {
                SubCategory::create($subCategory);
            }
        }
    }

