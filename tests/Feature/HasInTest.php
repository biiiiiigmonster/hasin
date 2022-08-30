<?php

namespace BiiiiiigMonster\Hasin\Tests\Feature;

use BiiiiiigMonster\Hasin\Tests\Models\User;

it('hasIn test', function () {
    $has = User::has('posts')->get();
    $hasIn = User::hasIn('posts')->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

it('hasIn(greater than 2) test', function () {
    $has = User::has('posts', '>', 2)->get();
    $hasIn = User::hasIn('posts', '>', 2)->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

it('nested hasIn test', function () {
    $has = User::has('posts.comments')->get();
    $hasIn = User::hasIn('posts.comments')->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});

it('nested hasIn(greater than 2) test', function () {
    $has = User::has('posts.comments', '>', 2)->get();
    $hasIn = User::hasIn('posts.comments', '>', 2)->get();

    expect($has->pluck('id'))->toEqual($hasIn->pluck('id'));
});
