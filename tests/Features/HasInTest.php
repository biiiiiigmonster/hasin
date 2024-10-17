<?php

use BiiiiiigMonster\Hasin\Tests\Models\Country;
use BiiiiiigMonster\Hasin\Tests\Models\Image;
use BiiiiiigMonster\Hasin\Tests\Models\Supplier;
use BiiiiiigMonster\Hasin\Tests\Models\User;
use BiiiiiigMonster\Hasin\Tests\Models\Video;

test('HasMany: hasIn same as has', function () {
    $has = User::has('posts')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('HasOne: hasIn same as has', function () {
    $has = User::has('phone')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('phone')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('BelongsTo: hasIn same as has', function () {
    $has = User::has('country')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('country')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('BelongsToMany: hasIn same as has', function () {
    $has = User::has('roles')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('roles')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('HasOneThrough: hasIn same as has', function () {
    $has = Supplier::has('userHistory')->orderBy('id')->pluck('id');
    $hasIn = Supplier::hasIn('userHistory')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('HasManyThrough: hasIn same as has', function () {
    $has = Country::has('posts')->orderBy('id')->pluck('id');
    $hasIn = Country::hasIn('posts')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('MorphOne: hasIn same as has', function () {
    $has = User::has('image')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('image')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('MorphTo: hasIn same as has', function () {
    $has = Image::has('imageable')->orderBy('id')->pluck('id');
    $hasIn = Image::hasIn('imageable')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('MorphMany: hasIn same as has', function () {
    $has = Video::has('comments')->orderBy('id')->pluck('id');
    $hasIn = Video::hasIn('comments')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('MorphToMany: hasIn same as has', function () {
    $has = Video::has('tags')->orderBy('id')->pluck('id');
    $hasIn = Video::hasIn('tags')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('HasMany: hasIn(gte 2) same as has(gte 2)', function () {
    $has = User::has('posts', '>=', 2)->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts', '>=', 2)->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('nested hasIn same as nested has', function () {
    $has = User::has('posts.comments')->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts.comments')->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});

test('nested hasIn(gte 2) same as nested has(gte 2)', function () {
    $has = User::has('posts.comments', '>=', 2)->orderBy('id')->pluck('id');
    $hasIn = User::hasIn('posts.comments', '>=', 2)->orderBy('id')->pluck('id');

    expect($has)->toEqual($hasIn);
});
