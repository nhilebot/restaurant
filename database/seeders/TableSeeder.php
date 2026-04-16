<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    for ($i = 1; $i <= 20; $i++) {
        DB::table('tables')->insert([
            'id' => $i,
        ]);
    }
}
}
