<?php

use App\Ad;
use Illuminate\Database\Seeder;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Ad::truncate();

        $faker = \Faker\Factory::create('zh_TW');
//        foreach (range(1, 100) as $i) {
//            Cart::create([
//                'member_id' => $i,
//                'product_id' => rand(1, 40),
//                'quantity' => rand(1, 20),
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]);
//        }
//        $carts = Cart::all();
//        foreach ($carts as $cart){
//            $cart->price = $cart->product->saleprice;
//            $cart->update();
//        }
        Ad::create([
            'name'=> 'default_1',
            'imagename'=> 'default_1.jpeg',
            'text_1' => 'John 99',
            'text_2' => 'The best Liquor Distributor',
            'text_3' =>'In the world.',
        ]);
        Ad::create([
            'name'=> 'default_2',
            'imagename'=> 'default_2.jpeg',
            'text_1' => 'Tonight I will have',
            'text_2' => 'JUST ONE DRINK',
            'text_3' =>'hahaha',
        ]);
        Ad::create([
            'name'=> 'default_3',
            'imagename'=> 'default_3.jpeg',
            'text_1' => 'Enjoy your bartending',
            'text_2' => 'at home',
            'text_3' =>'BuLuBuLu...',
        ]);
    }
}
