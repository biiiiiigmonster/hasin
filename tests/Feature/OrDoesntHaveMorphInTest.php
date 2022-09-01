<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('orDoesntHaveMorphIn same as orDoesntHaveMorph', function () {
    $orDoesntHaveMorph = Comment::where('status', '>', 2)->orDoesntHaveMorph('commentable', [Post::class])->orderBy('id')->pluck('id');
    $orDoesntHaveMorphIn = Comment::where('status', '>', 2)->orDoesntHaveMorphIn('commentable', [Post::class])->orderBy('id')->pluck('id');

    expect($orDoesntHaveMorph)->toEqual($orDoesntHaveMorphIn);
});
