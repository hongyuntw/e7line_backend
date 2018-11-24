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
        $units = ['瓶', '個'];
//        $alcohol = [
//            'beer' => ['啤酒'],
//            'spirit' => ['烈酒'],
//            'wine' => ['紅酒'],
//            'drinkware' => ['酒具'],
//        ];
//        foreach (range(1, 100) as $id) {
//            $type=rand(0,3);
//            $myunit='';
//            if($type==3){
//                $myunit='個';
//            }
//            else{
//                $myunit='瓶';
//            }
//            $category=0;
//            $product= Product::create([
//                'name'=> $alcohol[rand(0,3)][0],
//                'price' => rand(20, 10000),
//                'unit'=> $myunit,
//                'description' => $faker->realText(rand(100, 200)),
//                'created_at' => now()->subDays($total - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
//                'updated_at' => now()->subDays($total - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
//                'category_id' => rand(1,10),
//            ]);
//        }
        foreach (range(1, 50) as $id) {
            Product::create([
                'category_id' => rand(1, 14), // 酒有10種
                'name' => $faker->realText(rand(10, 15)),
                'listprice' => rand(20, 10000),
                'saleprice' => rand(40, 50000),
                'unit' => $units[rand(0, 1)],
                'description' => $faker->realText(rand(100, 200)),
                'created_at' => now()->subDays($total - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'updated_at' => now()->subDays($total - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
