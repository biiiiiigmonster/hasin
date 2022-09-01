<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('doesntHaveIn same as doesntHave', function () {
    $doesntHave = User::doesntHave('posts')->orderBy('id')->pluck('id');
    $doesntHaveIn = User::doesntHaveIn('posts')->orderBy('id')->pluck('id');

    expect($doesntHave)->toEqual($doesntHaveIn);
});

test('nested doesntHaveIn same as nested doesntHave', function () {
    $doesntHave = User::doesntHave('posts.comments')->orderBy('id')->pluck('id');
    $doesntHaveIn = User::doesntHaveIn('posts.comments')->orderBy('id')->pluck('id');

    expect($doesntHave)->toEqual($doesntHaveIn);
});
