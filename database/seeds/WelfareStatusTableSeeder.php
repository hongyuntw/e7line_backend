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

        $welfare_code = ['W001','W002','W003','W004','W005','W006','W007','W008','W009'];
        $welfare_name = ['春節','尾牙','端午','51勞動','中秋','生日','電影','旅遊','其他'];


        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,20)as $cusid){
            foreach (range(0,count($welfare_name)-1)as $id){
                \App\WelfareStatus::create([
                    'customer_id' => $cusid,
                    'budget' => (string)rand(1000, 10000),
                    'welfare_code' => $welfare_code[$id],
                    'welfare_name' => $welfare_name[$id],
                    'track_status'=>rand(0,2),
                    'welfare_id'=> $id,
                    'note'=>$faker->realText(rand(10,20)),
                    'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                    'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
            }
        }




    }
}
