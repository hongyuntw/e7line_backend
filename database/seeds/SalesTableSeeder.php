<?php

use App\Sale;
use App\SalesItem;
use Illuminate\Database\Seeder;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Sale::truncate();
        $faker = \Faker\Factory::create('zh_TW');

        foreach(range(1,50) as $sale_id)
        {
            $member_id = rand(1, 20);
            $order_date = now()->subDays(50 - $sale_id)->addHours(rand(1, 5))->addMinutes(rand(1, 5));
            Sale::create
            ([
                'member_id' => $member_id,
                'shipment' => $sale_id<=40 ? 1 : 0,
                'order_name' => $faker->name,
                'order_phone' => $faker->phoneNumber,
                'order_note' => $faker->realText(rand(10, 40)),
                'order_address' => $faker->realText(rand(15, 30)),
                'order_date'=> $order_date,
                'created_at' => $order_date,
                'updated_at' => now()->subDays(50 - $sale_id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);


            foreach(range(1,rand(1,5)) as $item)
            {
                SalesItem::create
                ([
                    'sale_id' => $sale_id,
                    'product_id' => rand(1,32),
                    'quantity' => rand(1,30),
                    'sale_price' => rand(900,3000),
                    'created_at' => $order_date,
                    'updated_at' => $order_date,
                ]);
            }
        }

    }
}
