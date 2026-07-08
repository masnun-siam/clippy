<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clip>
 */
class ClipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'type' => 'url',
            'url' => fake()->url(),
            'html' => null,
            'password' => null,
            'expires_at' => null,
            'clicks_count' => 0,
        ];
    }

    public function html(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'html',
            'url' => null,
            'html' => '<h1>Hello World</h1>',
        ]);
    }

    public function passwordProtected(?string $password = 'secret'): static
    {
        return $this->state(fn (array $attributes) => [
            'password' => bcrypt($password),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }
}
