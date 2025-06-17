<?php

namespace Database\Factories;

use Database\Faker\Providers\SouthAfricanIDProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new SouthAfricanIDProvider($this->faker));

        $dob = $this->faker->dateTimeBetween('-70 years', '-18 years');

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'sa_id_number' => $this->faker->southAfricanID($dob),
            'mobile_number' => $this->faker->numerify('+27#########'),
            'birth_date' => $dob,
            'language_code' => $this->faker->languageCode(),
            'interests' => $this->faker->randomElements([
                'Art', 'Photography', 'Technology', 'Music', 'Cooking', 'Travel', 'Reading', 'Gaming',
                'Sports', 'Fitness', 'Gardening', 'Writing', 'Fashion', 'DIY', 'Film',
            ], rand(1, 5)),
        ];
    }
}
