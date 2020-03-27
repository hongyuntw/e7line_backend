<?php

use Illuminate\Database\Seeder;

class BusinessConcatPersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\BusinessConcatPerson::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,300)as $id){
            \App\BusinessConcatPerson::create([
                'name' => $faker->name,
                'customer_id'=>rand(1,50),
                'phone_number' => $faker->phoneNumber,
                'extension_number'=> rand(100,999),
                'email'=> $faker->email,
                'is_left' => false,
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
        }
    }
}
