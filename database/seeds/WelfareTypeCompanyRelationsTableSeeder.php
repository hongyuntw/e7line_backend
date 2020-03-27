<?php

use Illuminate\Database\Seeder;

class WelfareTypeCompanyRelationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\WelfareTypeCompanyRelation::truncate();
        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(1,20)as $id){
            \App\WelfareTypeCompanyRelation::create([
                'welfare_company_id'=>rand(1,6),
                'welfare_type_id'=>rand(1,6),
            ]);
        }
    }
}
