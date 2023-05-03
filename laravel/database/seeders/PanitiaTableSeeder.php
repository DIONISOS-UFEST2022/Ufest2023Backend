<?php

namespace Database\Seeders;

use App\Models\panitia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PanitiaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        panitia::factory()->count(50)->create();
    }
}
