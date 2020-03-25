<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Status::truncate();

        $status_code = ['ST001','ST002','ST003','ST004','ST005'];
        $status_name = ['尚未開發','成交','培養','淺在','陌生'];


        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,4)as $id){
            \App\Status::create([
                'code' => $status_code[$id],
                'name' => $status_name[$id],
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
