<?php


use BiiiiiigMonster\Hasin\Tests\Models\User;

test('orDoesntHaveIn same as orDoesntHave', function () {
    $orDoesntHave = User::where('age', '>', 18)->orDoesntHave('posts')->orderBy('id')->pluck('id');
    $orDoesntHaveIn = User::where('age', '>', 18)->orDoesntHaveIn('posts')->orderBy('id')->pluck('id');

    expect($orDoesntHave)->toEqual($orDoesntHaveIn);
});

test('nested orDoesntHaveIn same as nested orDoesntHave', function () {
    $orDoesntHave = User::where('age', '>', 18)->orDoesntHave('posts.comments')->orderBy('id')->pluck('id');
    $orDoesntHaveIn = User::where('age', '>', 18)->orDoesntHaveIn('posts.comments')->orderBy('id')->pluck('id');

    expect($orDoesntHave)->toEqual($orDoesntHaveIn);
});
