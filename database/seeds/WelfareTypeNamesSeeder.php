<?php

use Illuminate\Database\Seeder;

class WelfareTypeNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\WelfareTypeName::truncate();

        $welfare_name = ['提貨卷','禮卷','泡湯卷','電影票','點卷','點數','其他'];

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,6)as $id){
            \App\WelfareTypeName::create([
                'name' => $welfare_name[$id],
            ]);
        }
    }
}
