<?php

use Illuminate\Database\Seeder;

class WelfareTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\WelfareType::truncate();
        //
        $welfare_name = ['提貨卷','禮卷','泡湯卷','電影票','點卷','點數','其他'];

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,300)as $id){
            $i =  rand(0,6);
            \App\WelfareType::create([
                'name' => $welfare_name[$i],
                'welfare_type_company_relation_id'=>rand(1,50),
                'welfare_status_id' =>rand(1,100),
                'code'=>$i,


            ]);
        }
    }
}
