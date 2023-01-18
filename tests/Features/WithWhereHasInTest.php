<?php


use BiiiiiigMonster\Hasin\Tests\Models\User;

test('withWhereHasIn same as withWhereHas', function () {
    $whereHas = User::withWhereHas('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->get();
    $whereHasIn = User::withWhereHasIn('posts', function ($query) {
        $query->where('votes', '>', 20);
    })->orderBy('id')->get();

    expect($whereHas->pluck('id'))->toEqual($whereHasIn->pluck('id'));
    expect($whereHas->pluck('posts.id'))->toEqual($whereHasIn->pluck('posts.id'));
});

test('nested withWhereHasIn same as nested withWhereHas', function () {
    $whereHas = User::withWhereHas('posts.comments', function ($query) {
        $query->where('status', '>', 2);
    })->orderBy('id')->get();
    $whereHasIn = User::withWhereHasIn('posts.comments', function ($query) {
        $query->where('status', '>', 2);
    })->orderBy('id')->get();

    expect($whereHas->pluck('id'))->toEqual($whereHasIn->pluck('id'));
    expect($whereHas->pluck('posts.id'))->toEqual($whereHasIn->pluck('posts.id'));
    expect($whereHas->pluck('posts.comments.id'))->toEqual($whereHasIn->pluck('posts.comments.id'));
});
