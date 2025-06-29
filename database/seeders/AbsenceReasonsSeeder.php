<?php

namespace Database\Seeders;

use App\Models\AbsenceReason;
use Illuminate\Database\Seeder;

class AbsenceReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            'Maladie',
            'Transport',
            'Familial',
            'Autre',
        ];

        foreach ($reasons as $reason) {
            AbsenceReason::create(['name' => $reason]);
        }
    }
}
