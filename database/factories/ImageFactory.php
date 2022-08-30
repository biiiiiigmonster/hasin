<?php

namespace BiiiiiigMonster\Hasin\Database\Factories;

use BiiiiiigMonster\Hasin\Tests\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url
        ];
    }
}
