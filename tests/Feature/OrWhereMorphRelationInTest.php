<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('orWhereMorphRelationIn same as orWhereMorphRelationIn', function () {
    $orWhereMorphRelation = Comment::where('status', '>=', 2)
        ->orWhereMorphRelation('commentable', [Post::class], 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');
    $orWhereMorphRelationIn = Comment::where('status', '>=', 2)
        ->orWhereMorphRelationIn('commentable', [Post::class], 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');

    expect($orWhereMorphRelation)->toEqual($orWhereMorphRelationIn);
});
