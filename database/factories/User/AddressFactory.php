<?php

namespace Database\Factories\User;

use App\Models\User\Address;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'location_id' => null,
            'street'      => fake()->streetAddress(),
            'postal_code' => fake()->numerify('##########'),
            'phone'       => fake()->numerify('09#########'),
            'label'       => fake()->word(),
            'is_default'  => true,
        ];
    }
}
