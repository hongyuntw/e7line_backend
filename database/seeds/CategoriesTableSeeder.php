<?php

use App\Category;
use App\Type;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::truncate();
        $beer = ['ALE', 'LAGER', 'LAMBIC'];
        $spirit = ['BRANDY', 'WHISKY', 'RUM', 'MEZCAL', 'GIN', 'VODKA'];
        $wine = ['SAUVIGNON BLANC', 'RIESLING', 'CABERNET SAUVIGNON', 'MERLET', 'PINOT NOIR'];
        $faker = \Faker\Factory::create('zh_TW');
        $count = 0;
        for ($i = 0; $i < count($beer); $i++) {
            Category::create([
                'type_id'=> 1,
                'name' => $beer[$i],
            ]);
        }
        for ($i = 0; $i < count($spirit); $i++) {
            Category::create([
                'type_id'=> 2,
                'name' => $spirit[$i],
            ]);
        }
        for ($i = 0; $i < count($wine); $i++) {
            Category::create([
                'type_id'=> 3,
                'name' => $wine[$i],
            ]);
        }

    }
}
