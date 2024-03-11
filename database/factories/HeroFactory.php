<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hero>
 */
class HeroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'login' => $this->faker->unique()->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->optional()->safeEmail,
            'bio' => $this->faker->optional()->paragraph,
            'location' => $this->faker->optional()->city,
            'language' => $this->faker->randomElement(['JavaScript', 'Python', 'PHP', 'Ruby', 'Go']),
            'followers' => $this->faker->numberBetween(0, 10000),
            'repositories' => $this->faker->numberBetween(0, 100),
            'avatar_url' => $this->faker->optional()->imageUrl(640, 480, 'people'),
            'website_url' => $this->faker->optional()->url,
            'pinned_items_count' => $this->faker->optional()->numberBetween(0, 10),
            'pinned_items_stars_count' => $this->faker->optional()->numberBetween(0, 1000),
            'contributions_last_year' => $this->faker->optional()->numberBetween(0, 5000),
            'repositories_contributed_count' => $this->faker->optional()->numberBetween(0, 100),
            'notes' => $this->faker->optional()->sentences(3, true),
        ];
    }
}
