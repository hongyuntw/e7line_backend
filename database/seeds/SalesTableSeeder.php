<?php

use App\Sale;
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
        foreach (range(1,20)as $id){
            Sale::create([
                'user_id' => rand(0, 100),
                'order_name' => $faker->name,
                'order_phone' => $faker->phoneNumber,
                'order_note' => $faker->realText(rand(50, 100)),
                'order_address' => $faker->realText(rand(50, 100)),
                'order_date'=> now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'created_at' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'updated_at' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
