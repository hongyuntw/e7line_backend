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
//        $this->call(StatusTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(ConcatRecordsTableSeeder::class);
        $this->call(WelfareTableSeeder::class);
        $this->call(WelfareStatusTableSeeder::class);
        $this->call(BusinessConcatPersonsTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
//        $this->call(ProductsTableSeeder_Real::class);
//        $this->call(CategoriesTableSeeder::class);
//        $this->call(SalesTableSeeder::class);
        //$this->call(SalesItemsTableSeeder::class);
//        $this->call(MembersTableSeeder::class);
//        $this->call(CartsTableSeeder::class);
//        $this->call(TypesTableSeeder::class);
//        $this->call(AdsTableSeeder::class);
//        $this->call(CouponsTableSeeder::class);

    }
}
