<?php

namespace BiiiiiigMonster\Hasin\Database\Factories;

use BiiiiiigMonster\Hasin\Tests\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->address
        ];
    }
}
