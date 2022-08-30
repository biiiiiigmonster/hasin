<?php

namespace BiiiiiigMonster\Hasin\Database\Factories;

use BiiiiiigMonster\Hasin\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName
        ];
    }
}
