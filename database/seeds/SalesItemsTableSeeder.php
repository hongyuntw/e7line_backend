<?php

use App\SalesItem;
use Illuminate\Database\Seeder;

class SalesItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SalesItem::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,20)as $id){
            SalesItem::create([
                'sale_id' => rand(1, 20),
                'product_id' => rand(1,30),
                'quantity' => rand(1,1000),
                'sale_price' => rand(200,20000),
                'created_at' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'updated_at' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
