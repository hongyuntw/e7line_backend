<?php

use App\Coupon;
use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Coupon::truncate();
        $faker = \Faker\Factory::create();
        foreach (range(1,30) as $id){
            Coupon::create([
                'code' => str_random(10),
                'type' => rand(1,3),
                'is_used' => 0,
            ]);
        }
    }
}
