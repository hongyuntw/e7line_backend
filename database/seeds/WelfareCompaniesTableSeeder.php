<?php

use Illuminate\Database\Seeder;

class WelfareCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\WelfareCompany::truncate();
        //
        $welfare_name = ['711','王品','全家','Apple','威秀','礁溪','電影票','漢來','其他','響食天堂'];

        $faker = \Faker\Factory::create('zh_TW');
        foreach (range(0,count($welfare_name)-1)as $id){
            \App\WelfareCompany::create([
                'name' => $welfare_name[$id],
                'welfare_type_company_relation_id'=>rand(1,100),
            ]);
        }
    }
}
