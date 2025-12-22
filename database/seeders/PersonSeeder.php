<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $total = 1000000;
        $batchSize = 1000;

        for ($i = 0; $i < $total; $i += $batchSize) {
            Person::factory()
                ->count($batchSize)
                ->create();

            echo "Inserted: " . ($i + $batchSize) . PHP_EOL;
        }
    }
}
