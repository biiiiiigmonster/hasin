<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('hasMorphIn same as hasMorph', function () {
    $hasMorph = Comment::hasMorph('commentable', [Post::class])->orderBy('id')->pluck('id');
    $hasMorphIn = Comment::hasMorphIn('commentable', [Post::class])->orderBy('id')->pluck('id');

    expect($hasMorph)->toEqual($hasMorphIn);
});

test('hasMorphIn(gte 2) same as hasMorph(gte 2)', function () {
    $hasMorph = Comment::hasMorph('commentable', [Post::class], '>=', 2)->orderBy('id')->pluck('id');
    $hasMorphIn = Comment::hasMorphIn('commentable', [Post::class], '>=', 2)->orderBy('id')->pluck('id');

    expect($hasMorph)->toEqual($hasMorphIn);
});
