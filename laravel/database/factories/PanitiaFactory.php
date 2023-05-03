<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\panitia>
 */
class PanitiaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nim' => '00000' . random_int(10000, 99999),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'program_studi' => Str::random(5),
            'angkatan' => random_int(1990, 2006),
            'vaccine_certificate' => Str::random(5) . '.jpg',
            'division_1' => fake()->jobTitle(),
            'division_2' => fake()->jobTitle(),
            'phone_number' => fake()->phoneNumber(),
            'reason_1' => fake()->text(20),
            'reason_2' => fake()->text(30),
            'portofolio' => fake()->text(),
            'id_line' => fake()->text(),
            'instagram_account' => fake()->text(),
            'city' => fake()->city(),
            'is_accepted' => random_int(0, 1),
        ];
    }
}
