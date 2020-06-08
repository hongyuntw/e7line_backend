<?php

use Illuminate\Database\Seeder;

class ConcatRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\ConcatRecord::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,70)as $id){
            \App\ConcatRecord::create([
                'user_id'=> rand(2,6),
                'customer_id'=>rand(1,10),
                'status' => rand(0,2),
                'development_content'=>$faker->realText(rand(10,100)),
                'track_content'=>$faker->realText(rand(10,100)),
                'track_date'=> now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'create_date' => now(),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
