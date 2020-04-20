<?php

use Illuminate\Database\Seeder;

class OrderItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \App\OrderItem::truncate();

        for ($i = 0; $i < 300; $i++){
            \App\OrderItem::Create([
                'order_id'=>rand(1,30),
                'product_relation_id'=>rand(1,50),
                'quantity'=>rand(1,20),
                'price'=>rand(500,1500),
                'create_date'=>now(),
                'update_date'=>now(),
            ]);
        }
    }
}
