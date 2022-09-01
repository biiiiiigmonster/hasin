<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('whereRelationIn same as whereRelation', function () {
    $whereRelation = User::whereRelation('posts', 'title', 'like', '%code%')->orderBy('id')->pluck('id');
    $whereRelationIn = User::whereRelationIn('posts', 'title', 'like', '%code%')->orderBy('id')->pluck('id');

    expect($whereRelation)->toEqual($whereRelationIn);
});

test('nested whereRelationIn same as nested whereRelation', function () {
    $whereRelation = User::whereRelation('posts.comments', 'status', '>=', '2')->orderBy('id')->pluck('id');
    $whereRelationIn = User::whereRelationIn('posts.comments', 'status', '>=', '2')->orderBy('id')->pluck('id');

    expect($whereRelation)->toEqual($whereRelationIn);
});
