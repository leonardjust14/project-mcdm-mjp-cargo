<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use Illuminate\Database\Seeder;

class MjpCargoSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            ['code' => 'C1', 'name' => 'Harga Beli', 'type' => 'cost', 'unit' => 'IDR', 'weight' => 0.557],
            ['code' => 'C2', 'name' => 'Efisiensi BBM', 'type' => 'benefit', 'unit' => 'km/L', 'weight' => 0.176],
            ['code' => 'C3', 'name' => 'Daya Angkut', 'type' => 'benefit', 'unit' => 'Ton', 'weight' => 0.090],
            ['code' => 'C4', 'name' => 'Ketersediaan Suku Cadang', 'type' => 'benefit', 'unit' => 'Score', 'weight' => 0.176],
        ];

        foreach ($criteria as $criterion) {
            Criteria::updateOrCreate(['code' => $criterion['code']], $criterion);
        }

        $criteriaMap = Criteria::all()->keyBy('code');

        $alternatives = [
            [
                'name' => 'Mitsubishi Fuso Canter',
                'code' => 'A1',
                'values' => [
                    'C1' => 285000000,
                    'C2' => 12.5,
                    'C3' => 3.5,
                    'C4' => 5,
                ],
            ],
            [
                'name' => 'Isuzu Elf NMR',
                'code' => 'A2',
                'values' => [
                    'C1' => 270000000,
                    'C2' => 13.0,
                    'C3' => 3.0,
                    'C4' => 4,
                ],
            ],
            [
                'name' => 'Hino Dutro',
                'code' => 'A3',
                'values' => [
                    'C1' => 295000000,
                    'C2' => 11.8,
                    'C3' => 4.0,
                    'C4' => 4,
                ],
            ],
        ];

        foreach ($alternatives as $row) {
            $alternative = Alternative::updateOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'is_active' => true]
            );

            foreach ($row['values'] as $code => $value) {
                $criterion = $criteriaMap[$code];
                AlternativeValue::updateOrCreate(
                    [
                        'alternative_id' => $alternative->id,
                        'criteria_id' => $criterion->id,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}
