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
        $beer = ['Ale', 'Lager', 'Lambic'];
        $spirit = ['Brandy', 'Whisky', 'Rum', 'Mezcal', 'Gin', 'Vodka'];
        $wine = ['Sauvignon Blanc', 'Riesling', 'Cabernet Sauvignon', 'Merlet', 'Pinot Noir'];
        $drinkwine = ['Glassware', 'Wine Bucket', 'Wine Opener'];
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
        for ($i = 0; $i < count($drinkwine); $i++) {
            Category::create([
                'type_id'=> 4,
                'name' => $wine[$i],
            ]);
        }

    }
}
