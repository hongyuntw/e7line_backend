<?php

use Illuminate\Database\Seeder;
class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $status_name = ['尚未開發','成交','培養','淺在','陌生'];
        \App\Customer::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,50)as $id){
            \App\Customer::create([
                'name' => $faker->name,
                'tax_id' => '12345678',
                'capital' => (string)rand(1000, 10000),
                'scales' => rand(10,500),
                'phone_number' => $faker->phoneNumber,
                'fax_number'=> $faker->phoneNumber,
                'city'=> $faker->city,
                'area'=> $faker->country,
                'address'=>$faker->address,
                'user_id'=> rand(1,4),
                'already_set_sales' => false,
                'is_deleted' => false,
                'status' => rand(1,5),
                'active_status'=>rand(0,1),
                'create_date' => now()->subDays(20 - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'update_date' => now()->subDays(20 - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
                ]);
        }

        $customers = \App\Customer::all();
        foreach ($customers as $customer){
            if($customer->user_id>1){
                $customer->already_set_sales = true;
            }
            $customer->update();
        }


    }
}
