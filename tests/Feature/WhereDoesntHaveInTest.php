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
    $whereDoesntHave = User::whereDoesntHave('posts', function ($query) {
        $query->where('votes', '>', 20)->whereDoesntHave('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');
    $whereDoesntHaveIn = User::whereDoesntHaveIn('posts', function ($query) {
        $query->where('votes', '>', 20)->whereDoesntHaveIn('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');

    expect($whereDoesntHave)->toEqual($whereDoesntHaveIn);
});
