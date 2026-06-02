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
        // ─────────────────────────────────────────────────────────────
        // BOBOT AHP — Scenario D (CR = 0.033, KONSISTEN)
        // Hierarki: C3 (Daya Angkut) > C2 (BBM) > C4 (Suku Cadang) > C1 (Harga)
        // ─────────────────────────────────────────────────────────────
        $criteria = [
            ['code' => 'C1', 'name' => 'Harga Beli',                   'type' => 'cost',    'unit' => 'Juta IDR', 'weight' => 0.0463],
            ['code' => 'C2', 'name' => 'Efisiensi BBM',                'type' => 'benefit', 'unit' => 'km/L',     'weight' => 0.2744],
            ['code' => 'C3', 'name' => 'Daya Angkut',                  'type' => 'benefit', 'unit' => 'Ton',      'weight' => 0.5660],
            ['code' => 'C4', 'name' => 'Ketersediaan Suku Cadang',     'type' => 'benefit', 'unit' => 'Skor',     'weight' => 0.1133],
        ];

        foreach ($criteria as $criterion) {
            Criteria::updateOrCreate(['code' => $criterion['code']], $criterion);
        }

        $criteriaMap = Criteria::all()->keyBy('code');

        // ─────────────────────────────────────────────────────────────
        // ALTERNATIF — Data refined dari dealer resmi + wawancara lapangan
        //   C1 Harga    : OTR dealer 2025 + estimasi kirim ke Manado (Juta IDR)
        //   C2 BBM      : estimasi real-world wawancara owner & sopir (km/L)
        //   C3 GVW      : spesifikasi resmi pabrikan (Ton)
        //   C4 Suku Cadang : skor ketersediaan di Manado, skala 1-10
        // ─────────────────────────────────────────────────────────────
        $alternatives = [
            [
                'name' => 'Mitsubishi Fuso Canter FE 74',
                'code' => 'A1',
                'values' => [
                    'C1' => 485,    // Juta IDR
                    'C2' => 10,     // km/L
                    'C3' => 7.5,    // Ton GVW
                    'C4' => 9,      // jaringan suku cadang terluas di Indonesia Timur
                ],
            ],
            [
                'name' => 'Isuzu Elf NMR 71',
                'code' => 'A2',
                'values' => [
                    'C1' => 495,    // Juta IDR (tertinggi, OTR ~488jt Jawa + ongkir Manado)
                    'C2' => 12,     // km/L (paling irit)
                    'C3' => 8.25,   // Ton GVW (tertinggi)
                    'C4' => 8,      // ketersediaan baik
                ],
            ],
            [
                'name' => 'Hino Dutro 130 MD',
                'code' => 'A3',
                'values' => [
                    'C1' => 470,    // Juta IDR (termurah dari 3 alternatif awal)
                    'C2' => 9,      // km/L (boros)
                    'C3' => 8.0,    // Ton GVW
                    'C4' => 7,      // jaringan terbatas di wilayah remote
                ],
            ],
            [
                'name' => 'Isuzu Elf NMR 71 L (Long Chassis)',
                'code' => 'A4',
                'values' => [
                    'C1' => 515,    // Juta IDR (long chassis, lebih mahal)
                    'C2' => 11,     // km/L
                    'C3' => 8.0,    // Ton GVW (kapasitas efektif turun dikit vs NMR 71 std)
                    'C4' => 8,      // ketersediaan baik (sama jaringan Isuzu)
                ],
            ],
            [
                'name' => 'Hino Dutro 136 HD',
                'code' => 'A5',
                'values' => [
                    'C1' => 542,    // Juta IDR
                    'C2' => 9,      // km/L (boros)
                    'C3' => 8.2,    // Ton GVW
                    'C4' => 7,      // jaringan Hino sedang
                ],
            ],
            [
                'name' => 'Toyota Dyna 130 HT',
                'code' => 'A6',
                'values' => [
                    'C1' => 502,    // Juta IDR
                    'C2' => 10,     // km/L
                    'C3' => 7.8,    // Ton GVW
                    'C4' => 6,      // suku cadang Dyna paling lemah di Indonesia Timur
                ],
            ],
            [
                'name' => 'Mitsubishi Fuso Canter FE 71',
                'code' => 'A7',
                'values' => [
                    'C1' => 455,    // Juta IDR (murah)
                    'C2' => 11,     // km/L
                    'C3' => 6.5,    // Ton GVW (kapasitas kecil)
                    'C4' => 9,      // jaringan Fuso terluas
                ],
            ],
            [
                'name' => 'Isuzu Elf NLR 71 T (Engkel)',
                'code' => 'A8',
                'values' => [
                    'C1' => 430,    // Juta IDR (termurah)
                    'C2' => 13,     // km/L (paling irit, mesin kecil)
                    'C3' => 5.1,    // Ton GVW (paling kecil, truk engkel 4 ban)
                    'C4' => 8,      // ketersediaan Isuzu baik
                ],
            ],
            [
                'name' => 'Hino Dutro 110 SDL',
                'code' => 'A9',
                'values' => [
                    'C1' => 447,    // Juta IDR (entry Hino)
                    'C2' => 10,     // km/L
                    'C3' => 6.0,    // Ton GVW
                    'C4' => 7,      // jaringan Hino sedang
                ],
            ],
            [
                'name' => 'Mitsubishi Fuso Canter FE 84 SHDX',
                'code' => 'A10',
                'values' => [
                    'C1' => 543,    // Juta IDR (termahal)
                    'C2' => 9,      // km/L (boros)
                    'C3' => 8.0,    // Ton GVW
                    'C4' => 9,      // jaringan Fuso terluas, suku cadang top
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
                        'criteria_id'    => $criterion->id,
                    ],
                    ['value' => $value]
                );
            }
        }
    }
}