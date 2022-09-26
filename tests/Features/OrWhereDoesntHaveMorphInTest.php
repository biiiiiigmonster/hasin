<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('orWhereDoesntHaveMorphIn same as orWhereDoesntHaveMorph', function () {
    $orWhereDoesntHaveMorph = Comment::where('status', '>', 2)->orWhereDoesntHaveMorph('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');
    $orWhereDoesntHaveMorphIn = Comment::where('status', '>', 2)->orWhereDoesntHaveMorphIn('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');

    expect($orWhereDoesntHaveMorph)->toEqual($orWhereDoesntHaveMorphIn);
});
