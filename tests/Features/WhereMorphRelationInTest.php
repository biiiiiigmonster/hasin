<?php

use BiiiiiigMonster\Hasin\Tests\Models\Comment;
use BiiiiiigMonster\Hasin\Tests\Models\Post;

test('whereMorphRelationIn same as whereMorphRelation', function () {
    $whereMorphRelation = Comment::whereMorphRelation('commentable', [Post::class], 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');
    $whereMorphRelationIn = Comment::whereMorphRelationIn('commentable', [Post::class], 'title', 'like', '%code%')
        ->orderBy('id')->pluck('id');

    expect($whereMorphRelation)->toEqual($whereMorphRelationIn);
});
