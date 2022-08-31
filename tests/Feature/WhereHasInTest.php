<?php


use BiiiiiigMonster\Hasin\Tests\Models\User;

test('whereHasIn same as whereHas', function () {
    $whereHas = User::whereHas('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');
    $whereHasIn = User::whereHasIn('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');

    expect($whereHas)->toEqual($whereHasIn);
});

test('nested whereHasIn same as nested whereHas', function () {
    $whereHas = User::whereHas('posts', function ($query) {
        $query->where('votes', '>', 20)->whereHas('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');
    $whereHasIn = User::whereHasIn('posts', function ($query) {
        $query->where('votes', '>', 20)->whereHasIn('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');

    expect($whereHas)->toEqual($whereHasIn);
});
