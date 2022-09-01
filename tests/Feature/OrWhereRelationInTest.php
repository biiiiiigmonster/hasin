<?php

use BiiiiiigMonster\Hasin\Tests\Models\User;

test('orWhereRelationIn same as orWhereRelation', function () {
    $orWhereRelation = User::where('age', '>', 18)
        ->orWhereRelation('posts', 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');
    $orWhereRelationIn = User::where('age', '>', 18)
        ->orWhereRelationIn('posts', 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');

    expect($orWhereRelation)->toEqual($orWhereRelationIn);
});

test('nested whereRelationIn same as nested whereRelation', function () {
    $orWhereRelation = User::where('age', '>', 18)
        ->orWhereRelation('posts.comments', 'status', '>=', '2')
        ->orderBy('id')->pluck('id');
    $orWhereRelationIn = User::where('age', '>', 18)
        ->orWhereRelationIn('posts.comments', 'status', '>=', '2')
        ->orderBy('id')->pluck('id');

    expect($orWhereRelation)->toEqual($orWhereRelationIn);
});
