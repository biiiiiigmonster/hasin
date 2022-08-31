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
        $this->migration->down();
        $this->migration->up();
    }

    protected function destroyDatabaseMigrations()
    {
//        $this->migration->down();
    }

    protected function defineDatabaseSeeders()
    {
        $tags = Tag::factory(20)->create();
        $countries = Country::factory(15)->create();
        $suppliers = Supplier::factory(15)->create();
        $roles = Role::factory(10)->create();

        $users = User::factory(15)
            ->has(History::factory())
            ->has(Phone::factory())
            ->has(Image::factory(3))
            ->hasAttached($roles->random(5))
            ->sequence(fn () => ['country_id' => $countries->pluck('id')->random()])
            ->sequence(fn () => ['supplier_id' => $suppliers->pluck('id')->random()])
            ->create();

        Post::factory(15)
            ->sequence(fn () => ['user_id' => $users->pluck('id')->random()])
            ->has(Comment::factory(3))
            ->has(Image::factory(2))
            ->hasAttached($tags->random(15))
            ->create();

        Video::factory(15)->hasAttached($tags->random(15))->create();
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
