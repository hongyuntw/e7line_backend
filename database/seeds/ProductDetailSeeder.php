<?php

use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\ProductDetail::truncate();
        //
        $name = ['50面額','150面額','250面額','350面額','泡湯卷','300點','電影票','其他','平日午餐','平日晚餐'];


        foreach (range(1,10) as $id){
            \App\ProductDetail::create([
                'name' => $name[$id-1],
                'price'=> rand(100,1000),
                'create_date'=>now(),
                'update_date'=>now(),
            ]);
        }
    }
}
