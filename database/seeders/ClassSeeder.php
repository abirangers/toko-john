<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassModel::insert([
            [
                'name' => 'X RPL 1',
                'major_id' => 1,
                'slug' => 'x-rpl-1'
            ],
            [
                'name' => 'X RPL 2',
                'major_id' => 1,
                'slug' => 'x-rpl-2'
            ],
            [
                'name' => 'X RPL 3',
                'major_id' => 1,
                'slug' => 'x-rpl-3'
            ],
            [
                'name' => 'XI RPL 1',
                'major_id' => 1,
                'slug' => 'xi-rpl-1'
            ],
            [
                'name' => 'XI RPL 2',
                'major_id' => 1,
                'slug' => 'xi-rpl-2'
            ],
            [
                'name' => 'XI RPL 3',
                'major_id' => 1,
                'slug' => 'xi-rpl-3'
            ],
            [
                'name' => 'XII RPL 1',
                'major_id' => 1,
                'slug' => 'xii-rpl-1'
            ],
            [
                'name' => 'XII RPL 2',
                'major_id' => 1,
                'slug' => 'xii-rpl-2'
            ],
            [
                'name' => 'XII RPL 3',
                'major_id' => 1,
                'slug' => 'xii-rpl-3'
            ],
        ]);
    }
}