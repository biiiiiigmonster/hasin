<?php

namespace BiiiiiigMonster\Hasin\Database\Factories;

use BiiiiiigMonster\Hasin\Tests\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title
        ];
    }
}
