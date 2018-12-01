<?php

use App\Member;
use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        Member::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,20)as $id){
            Member::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->password,
                'created_at' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'updated_at' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
