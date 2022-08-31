<?php


use BiiiiiigMonster\Hasin\Tests\Models\User;

test('orWhereHasIn same as orWhereHas', function () {
    $orWhereHas = User::where('age', '>', 18)->orWhereHas('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');
    $orWhereHasIn = User::where('age', '>', 18)->orWhereHasIn('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->pluck('id');

    expect($orWhereHas)->toEqual($orWhereHasIn);
});

test('nested orWhereHasIn same as nested orWhereHas', function () {
    $orWhereHas = User::where('age', '>', 18)->orWhereHas('posts', function ($query) {
        $query->where('votes', '>', 20)->orWhereHas('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');
    $orWhereHasIn = User::where('age', '>', 18)->orWhereHasIn('posts', function ($query) {
        $query->where('votes', '>', 20)->orWhereHasIn('comments', function ($nestQuery) {
            $nestQuery->where('status', '=', 2);
        });
    })->orderBy('id')->pluck('id');

    expect($orWhereHas)->toEqual($orWhereHasIn);
});
