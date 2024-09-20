<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create([
            'address' => 'الجسر الأبيض',
            'location_url' => 'https://maps.app.goo.gl/eU8g2xZRswN8eagG9'
        ]);
    }
}
