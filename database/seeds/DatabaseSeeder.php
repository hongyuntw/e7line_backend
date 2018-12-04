<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ProductsTableSeeder_Real::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(SalesTableSeeder::class);
        $this->call(SalesItemsTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(CartsTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(AdsTableSeeder::class);

    }
}
