<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('orHasMorphIn same as orHasMorph', function () {
    $orHasMorph = Comment::where('status', '>', 2)->orHasMorph('commentable', [Post::class])->orderBy('id')->pluck('id');
    $orHasMorphIn = Comment::where('status', '>', 2)->orHasMorphIn('commentable', [Post::class])->orderBy('id')->pluck('id');

    expect($orHasMorph)->toEqual($orHasMorphIn);
});

test('orHasMorphIn(gte 2) same as orHasMorph(gte 2)', function () {
    $orHasMorph = Comment::where('status', '>', 2)->orHasMorph('commentable', [Post::class], '>=', 2)->orderBy('id')->pluck('id');
    $orHasMorphIn = Comment::where('status', '>', 2)->orHasMorphIn('commentable', [Post::class], '>=', 2)->orderBy('id')->pluck('id');

    expect($orHasMorph)->toEqual($orHasMorphIn);
});
