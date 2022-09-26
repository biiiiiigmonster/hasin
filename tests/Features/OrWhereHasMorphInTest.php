<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('orWhereHasMorphIn same as orWhereHasMorph', function () {
    $orWhereHasMorph = Comment::where('status', '>', 2)->orWhereHasMorph('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');
    $orWhereHasMorphIn = Comment::where('status', '>', 2)->orWhereHasMorphIn('commentable', [Post::class], function ($query) {
        $query->where('title', 'like', '%code%');
    })->orderBy('id')->pluck('id');

    expect($orWhereHasMorph)->toEqual($orWhereHasMorphIn);
});
