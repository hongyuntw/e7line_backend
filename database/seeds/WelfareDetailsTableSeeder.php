<?php

use Illuminate\Database\Seeder;

class WelfareDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        \App\WelfareDetail::truncate();
        //
        $welfare_name = ['50面額','150面額','250面額','350面額','泡湯卷','300點','電影票','其他','平日午餐','平日晚餐'];

        $faker = \Faker\Factory::create('zh_TW');

        foreach (range(0,300)as $id){
            \App\WelfareDetail::create([
                'name' => $welfare_name[rand(0,9)],
                'welfare_company_id'=>rand(1,10),
            ]);
        }
    }
}
