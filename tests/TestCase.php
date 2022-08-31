<?php

namespace BiiiiiigMonster\Hasin\Tests;

use BiiiiiigMonster\Hasin\HasinServiceProvider;
use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Country;
use BiiiiiigMonster\Hasin\Tests\Models\History;
use BiiiiiigMonster\Hasin\Tests\Models\Image;
use BiiiiiigMonster\Hasin\Tests\Models\Phone;
use BiiiiiigMonster\Hasin\Tests\Models\Post;
use BiiiiiigMonster\Hasin\Tests\Models\Role;
use BiiiiiigMonster\Hasin\Tests\Models\Supplier;
use BiiiiiigMonster\Hasin\Tests\Models\Tag;
use BiiiiiigMonster\Hasin\Tests\Models\User;
use BiiiiiigMonster\Hasin\Tests\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    private Migration $migration;

    protected function getPackageProviders($app)
    {
        return [
            HasinServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->migration->up();
    }

    protected function destroyDatabaseMigrations()
    {
        $this->migration->down();
    }

    protected function defineDatabaseSeeders()
    {
        $tags = Tag::factory(4)->create();
        $country = Country::factory(3)->create();
        $supplier = Supplier::factory(3)->create();

        $postFactory = Post::factory(3)
            ->has(Comment::factory(3))
            ->has(Image::factory(2))
            ->hasAttached($tags->random(3));

        User::factory(3)
            ->has($postFactory)
            ->has(History::factory())
            ->has(Phone::factory())
            ->has(Image::factory(3))
            ->hasAttached(Role::factory(3))
            ->for($country->random())
            ->for($supplier->random())
            ->create();

        Video::factory(3)->hasAttached($tags->random(3))->create();
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.connections.mysql.prefix', 'hasin_test_');

        Schema::defaultStringLength(191);
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BiiiiiigMonster\\Hasin\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->migration = include __DIR__.'/../database/migrations/create_hasin_test_table.php';
    }
}
