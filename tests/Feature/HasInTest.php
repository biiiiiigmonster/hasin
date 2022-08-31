<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('hasIn same as has', function () {
    $has = User::has('posts')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('hasIn(gte 2) same as has(gte 2)', function () {
    $has = User::has('posts', '>=', 2)->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts', '>=', 2)->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('nested hasIn same as nested has', function () {
    $has = User::has('posts.comments')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts.comments')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('nested hasIn(gte 2) same as nested has(gte 2)', function () {
    $has = User::has('posts.comments', '>=', 2)->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts.comments', '>=', 2)->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});
