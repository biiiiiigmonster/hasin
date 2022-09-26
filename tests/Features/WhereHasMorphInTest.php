<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('whereHasMorphIn same as whereHasMorph', function () {
    $whereHasMorph = Comment::whereHasMorph('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');
    $whereHasMorphIn = Comment::whereHasMorphIn('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');

    expect($whereHasMorph)->toEqual($whereHasMorphIn);
});
