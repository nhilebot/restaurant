<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            MenuSeeder::class, 
            RoleSeeder::class,
            UserSeeder::class,
            TableSeeder::class,
            CategorySeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            ReservationSeeder::class,
            CartSeeder::class,
        ]);
    }
}