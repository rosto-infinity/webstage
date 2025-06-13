<?php

namespace Database\Seeders;

use App\Models\Presence;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Génère 100 présences
        Presence::factory()->count(100)->create();
    }
}
