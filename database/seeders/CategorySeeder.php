<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => '食費']);
        Category::create(['name' => '日用品']);
        Category::create(['name' => '交通費']);
        Category::create(['name' => '水道・光熱費']);
        Category::create(['name' => '通信費']);
    }
}
