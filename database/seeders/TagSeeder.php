<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder is used to seed the tags table.
     */
    public function run(): void {
        DB::table('tags')->insert([
            [
                'name' => 'Picante',
                'icon' => 'spicy.svg',
            ],
            [
                'name' => 'Dulce',
                'icon' => 'sweet.svg',
            ],
            [
                'name' => 'Salado',
                'icon' => 'salty.svg',
            ],
            [
                'name' => 'Ácido',
                'icon' => 'acid.svg',
            ],
            [
                'name' => 'Amargo',
                'icon' => 'bitter.svg',
            ],
            [
                'name' => 'Frito',
                'icon' => 'fried.svg',
            ],
        ]);
    }
}
