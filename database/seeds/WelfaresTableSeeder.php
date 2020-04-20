<?php

use Illuminate\Database\Seeder;

class WelfaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Welfare::truncate();
        $welfare_codes = ['W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009'];
        $welfare_names = ['春節', '尾牙', '端午', '51勞動', '中秋', '生日', '電影', '旅遊', '其他'];
        foreach (range(1, count($welfare_names)) as $id) {
            \App\Welfare::create([
                'welfare_code' => $welfare_codes[$id-1],
                'welfare_name' => $welfare_names[$id-1],
                'create_date' => now(),
            ]);
        }
    }
}
