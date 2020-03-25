<?php

use Illuminate\Database\Seeder;

class WelfareStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \App\WelfareStatus::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,200)as $id){
            \App\WelfareStatus::create([
                'customer_id' => rand(1,20),
                'welfare_id' => rand(1,2),
                'budget' => rand(1000, 10000),
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
        }
    }
}
