<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'memberType',

            ],
            [
                'name' => 'relation',

            ],
            [
                'name' => 'status',

            ],
            [
                'name' => 'excluded category',

            ],
            [
                'name' => 'gender',

            ],
            [
                'name' => 'religion',

            ],
            [
                'name' => 'RenewalStatus',

            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
