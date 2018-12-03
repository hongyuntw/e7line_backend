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
            'text_1' => '123',
            'text_2' => '456',
            'text_3' =>'789',
        ]);
        Ad::create([
            'name'=> 'default_2',
            'imagename'=> 'default_2.jpeg',
            'text_1' => '123',
            'text_2' => '456',
            'text_3' =>'789',
        ]);
        Ad::create([
            'name'=> 'default_3',
            'imagename'=> 'default_3.jpeg',
            'text_1' => '123',
            'text_2' => '456',
            'text_3' =>'789',
        ]);
    }
}
