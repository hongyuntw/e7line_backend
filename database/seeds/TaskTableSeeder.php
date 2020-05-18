<?php

use App\User;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Task::truncate();
        $faker = \Faker\Factory::create('zh_TW');

        for ($i = 0; $i < 30; $i++) {
            $task = \App\Task::create([
                'content' => $faker->realText(30),
                'status' => rand(0,2),
                'topic' => $faker->realText(10),
                'create_date'=>now(),
                'update_date'=>now(),
            ]);

        }


    }
}
