<?php

use Illuminate\Database\Seeder;

class WelfareTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Welfare::truncate();
        //
        $welfare_code = ['W001','W002'];
        $welfare_name = ['春節禮金','尾牙'];

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,1)as $id){
            \App\Welfare::create([
                'code' => $welfare_code[$id],
                'name' => $welfare_name[$id],
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
        }
    }
}
