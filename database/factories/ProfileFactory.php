<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
            'street' => $this->faker->streetAddress(),
            'phone_number' => $this->faker->phoneNumber(),
            'bio' => $this->faker->sentence(10),
            'facebook_url' => $this->faker->url(),
            'linkedin_url' => $this->faker->url(),
            'instagram_url' => $this->faker->url(),
            'github_url' => $this->faker->url(),
        ];
    }
}
