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
        $welfare_code = ['W001','W002','W003','W004','W005','W006','W007','W008','W009'];
        $welfare_name = ['春節','尾牙','端午','51勞動','中秋','生日','電影','旅遊','其他'];

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,count($welfare_name)-1)as $id){
            \App\Welfare::create([
                'code' => $welfare_code[$id],
                'name' => $welfare_name[$id],
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
        }
    }
}
