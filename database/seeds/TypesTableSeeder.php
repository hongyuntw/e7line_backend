<?php

use App\Type;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Type::truncate();
        $ourtype=['Beer','Spirit','Wine','Drinkware'];
        foreach (range(1,4)as $id){
            Type::create([
                'name'=> $ourtype[$id-1],
            ]);
        }
    }
}
