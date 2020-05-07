<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \App\Order::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        for($i=1;$i<=30;$i++){
            \App\Order::Create([
                'user_id' => rand(1,3),
                'customer_id'=>rand(1,10),
                'business_concat_person_id'=>rand(1,10),
                'note'=>$faker->realText(10),
                'status'=>rand(0,2),
                'tax_id'=>'12345678',
                'ship_to'=>$faker->address,
                'email'=>$faker->email,
                'phone_number'=>$faker->phoneNumber,
                'amount'=>0,
                'welfare_id'=>rand(1,9),
                'receive_date'=> now()->addDays(200 - $i)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'create_date'=>now(),
                'update_date'=>now(),
                'payment_method'=> rand(0,3),
                'payment_date' => now()->addDays(rand(1,4)),
//                'payment_last_five_number' => '12345',
                'e7line_account'=>'1321312@mail.com',
                'e7line_name'=>$faker->name,
            ]);
        }
    }
}
