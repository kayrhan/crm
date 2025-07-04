<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory {
    public function definition() {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
            "remember_token" => Str::random(10),
        ];
    }

    public function unverified() {
        return $this->state(function(array $attributes) {
            return [
                "email_verified_at" => null
            ];
        });
    }
}