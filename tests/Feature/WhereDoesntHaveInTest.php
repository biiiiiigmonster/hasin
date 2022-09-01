<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('whereDoesntHaveIn same as whereDoesntHave', function () {
    $whereDoesntHave = User::whereDoesntHave('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');
    $whereDoesntHaveIn = User::whereDoesntHaveIn('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');

    expect($whereDoesntHave)->toEqual($whereDoesntHaveIn);
});

test('nested whereDoesntHaveIn same as nested whereDoesntHave', function () {
    $whereDoesntHave = User::whereDoesntHave('posts.comments', function ($query) {
        $query->where('status', '>', 2);
        ;
    })->orderBy('id')->pluck('id');
    $whereDoesntHaveIn = User::whereDoesntHaveIn('posts.comments', function ($query) {
        $query->where('status', '>', 2);
    })->orderBy('id')->pluck('id');

    expect($whereDoesntHave)->toEqual($whereDoesntHaveIn);
});
