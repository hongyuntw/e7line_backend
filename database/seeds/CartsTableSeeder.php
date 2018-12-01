<?php

use App\Cart;
use Illuminate\Database\Seeder;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Cart::truncate();

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1, 100) as $i) {
            Cart::create([
                'member_id' => $i,
                'product_id' => rand(1, 40),
                'quantity' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $carts = Cart::all();
        foreach ($carts as $cart){
            $cart->price = $cart->product->saleprice;
            $cart->update();
        }
    }
}
