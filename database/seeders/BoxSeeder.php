<?php

namespace Database\Seeders;

use App\Models\Box;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Box::create([
           "name" => "Shaxmat",
            "per_liner_meter" =>15.6,
            "sort_by" => 1
        ]);

        Box::create([
           "name" => "Kapalak",
            "per_liner_meter" =>9.3,
            "sort_by" => 2
        ]);

        Box::create([
           "name" => "Voenniy",
            "per_liner_meter" =>9.3,
            "sort_by" => 3
        ]);
    }
}
