<?php

namespace Database\Seeders;

use App\Models\Statistics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statistics::create([
            'doctors' => 5000,
            'visits' => 5000,
            'specialties' => 40,
            'appointments' => 5000
        ]);
    }
}
