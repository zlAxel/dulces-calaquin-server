<?php

namespace Database\Seeders;

use App\Models\StatusPurchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $statusPurchases = [
            [
                'description' => 'Pagado',
            ],
            [
                'description' => 'Pendiente',
            ],
            [
                'description' => 'Cancelado',
            ],
        ];

        foreach ($statusPurchases as $statusPurchase) {
            StatusPurchase::create($statusPurchase);
        }
    }
}
