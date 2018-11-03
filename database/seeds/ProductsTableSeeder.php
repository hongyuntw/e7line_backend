<?php

use App\Category;
use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        Product::truncate();

        $faker = \Faker\Factory::create('zh_TW');
        $total = 20;
        $units = ['個', '顆', '盒', '台', '粒'];

        foreach(range(1, 3) as $i) {
            $category = Category::create([
                'name' => '分類 '.$i,
            ]);

            foreach (range(1, $total) as $id) {
                Product::create([
                    'name' => $faker->realText(rand(10, 15)),
                    'price' => rand(20, 10000),
                    'unit' => $units[rand(0, 4)],
                    'description' => $faker->realText(rand(100, 200)),
                    'category_id' => $category->id,
                    'created_at' => now()->subDays($total - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                    'updated_at' => now()->subDays($total - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
            }
        }
    }
}
