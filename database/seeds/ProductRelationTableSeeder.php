<?php

use Illuminate\Database\Seeder;

class ProductRelationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\ProductRelation::truncate();
        $faker = \Faker\Factory::create('zh_TW');

        for ($i = 0; $i < 10; $i++) {
            for ($k = 0; $k < 10; $k++) {
                \App\ProductRelation::create([
                    'product_id' => $i+1,
                    'product_detail_id' => rand(1, 10),
                    'price'=> rand(100,1000),

                ]);
            }
        }

    }
}
