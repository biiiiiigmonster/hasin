<?php

namespace BiiiiiigMonster\Hasin\Tests;

use BiiiiiigMonster\Hasin\HasinServiceProvider;
use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Country;
use BiiiiiigMonster\Hasin\Tests\Models\History;
use BiiiiiigMonster\Hasin\Tests\Models\Phone;
use BiiiiiigMonster\Hasin\Tests\Models\Post;
use BiiiiiigMonster\Hasin\Tests\Models\Role;
use BiiiiiigMonster\Hasin\Tests\Models\Supplier;
use BiiiiiigMonster\Hasin\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BiiiiiigMonster\\Hasin\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        User::factory(3)
            ->has(Post::factory(3)->has(Comment::factory(3)))
            ->has(History::factory())
            ->has(Phone::factory())
            ->hasAttached(Role::factory(3))
            ->for(Country::factory())
            ->for(Supplier::factory())
            ->create();
    }

    protected function getPackageProviders($app)
    {
        return [
            HasinServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.connections.mysql.prefix', 'hasin_test_');

        Schema::defaultStringLength(191);

        $migration = include __DIR__.'/../database/migrations/create_hasin_test_table.php';
        $migration->down();
        $migration->up();
    }
}
