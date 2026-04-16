<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ QUAN TRỌNG

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'seafood',
            'special',
            'salad',
            'dessert',
            'drink',
            'vietnamese'
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}