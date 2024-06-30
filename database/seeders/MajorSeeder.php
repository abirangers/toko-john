<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Major::insert([
            [
                'name' => 'Rekayasa Perangkat Lunak',
                'short_name' => 'RPL',
                'slug' => 'rekayasa-perangkat-lunak'
            ],
            [
                'name' => 'Teknik Jaringan Dasar',
                'short_name' => 'TKJ',
                'slug' => 'teknik-jaringan-dasar'
            ],
            [
                'name' => 'Multimedia',
                'short_name' => 'MM',
                'slug' => 'multimedia'
            ],
            [
                'name' => 'Perbankan dan Keuangan Mikro',
                'short_name' => 'PKM',
                'slug' => 'perbankan-dan-keuangan-mikro'
            ]
        ]);
    }
}