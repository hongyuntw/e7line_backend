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
        \App\TaskAssignment::truncate();
        $faker = \Faker\Factory::create('zh_TW');

        for ($i = 0; $i < 30; $i++) {
            $task = \App\Task::create([
                'content' => $faker->realText(30),
                'topic' => $faker->realText(10),
                'create_date'=>now(),
                'update_date'=>now(),
            ]);

            $rand = rand(1,3);

            for($k=0;$k<$rand;$k++){
                \App\TaskAssignment::create([
                    'user_id' => rand(2,count(User::all())),
                    'status' => rand(0,2),
                    'task_id' => $task->id,
                    'create_date'=>now(),
                    'update_date'=>now(),
                ]);
            }
        }


    }
}
