<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('hasIn same as has', function () {
    $has = User::has('posts')->get();
    $hasIn = User::hasIn('posts')->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

test('hasIn(gte 2) same as has(gte 2)', function () {
    $has = User::has('posts', '>=', 2)->get();
    $hasIn = User::hasIn('posts', '>=', 2)->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

test('nested hasIn same as nested has', function () {
    $has = User::has('posts.comments')->get();
    $hasIn = User::hasIn('posts.comments')->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

test('nested hasIn(gte 2) same as nested has(gte 2)', function () {
    $has = User::has('posts.comments', '>=', 2)->get();
    $hasIn = User::hasIn('posts.comments', '>=', 2)->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});
