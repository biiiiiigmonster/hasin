<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('orHasIn same as orHas', function () {
    $orHas = User::where('age', '>', 18)->orHas('posts')->orderBy('id')->pluck('id');
    $orHasIn = User::where('age', '>', 18)->orHasIn('posts')->orderBy('id')->pluck('id');

    expect($orHas)->toEqual($orHasIn);
});

test('orHasIn(gte 2) same as orHas(gte 2)', function () {
    $orHas = User::where('age', '>', 18)->orHas('posts', '>=', 2)->orderBy('id')->pluck('id');
    $orHasIn = User::where('age', '>', 18)->orHasIn('posts', '>=', 2)->orderBy('id')->pluck('id');

    expect($orHas)->toEqual($orHasIn);
});

test('nested orHasIn same as nested orHas', function () {
    $orHas = User::where('age', '>', 18)->orHas('posts.comments')->orderBy('id')->pluck('id');
    $orHasIn = User::where('age', '>', 18)->orHasIn('posts.comments')->orderBy('id')->pluck('id');

    expect($orHas)->toEqual($orHasIn);
});

test('nested orHasIn(gte 2) same as nested orHas(gte 2)', function () {
    $orHas = User::where('age', '>', 18)->orHas('posts.comments', '>=', 2)->orderBy('id')->pluck('id');
    $orHasIn = User::where('age', '>', 18)->orHasIn('posts.comments', '>=', 2)->orderBy('id')->pluck('id');

    expect($orHas)->toEqual($orHasIn);
});
