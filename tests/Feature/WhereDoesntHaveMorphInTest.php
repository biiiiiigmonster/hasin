<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('whereDoesntHaveMorphIn same as whereDoesntHaveMorph', function () {
    $whereDoesntHaveMorph = Comment::whereDoesntHaveMorph('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');
    $whereDoesntHaveMorphIn = Comment::whereDoesntHaveMorphIn('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');

    expect($whereDoesntHaveMorph)->toEqual($whereDoesntHaveMorphIn);
});
