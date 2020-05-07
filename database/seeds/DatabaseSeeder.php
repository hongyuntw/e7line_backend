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
        $this->call(WelfaresTableSeeder::class);

        $this->call(CustomersTableSeeder::class);
        $this->call(ConcatRecordsTableSeeder::class);
        $this->call(WelfareStatusTableSeeder::class);
        $this->call(BusinessConcatPersonsTableSeeder::class);

        $this->call(WelfareTypeNamesSeeder::class);
        $this->call(WelfareTypesTableSeeder::class);

//        product
        $this->call(ProductRelationTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(ProductDetailSeeder::class);

//        order
        $this->call(OrderTableSeeder::class);
        $this->call(OrderItemTableSeeder::class);


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
