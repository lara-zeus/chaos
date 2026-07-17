<?php

use Tests\Models\Post;

use function Pest\Laravel\get;

it('can view posts list', function () {
    Post::create(['title' => 'Test Post']);
    $response = get(route('filament.admin.resources.posts.index'));
    $response->assertStatus(200);
});

it('can view create page', function () {
    $response = get(route('filament.admin.resources.posts.create'));
    $response->assertStatus(200);
});

it('can view edit page', function () {
    $post = Post::create(['title' => 'Test Post']);
    $response = get(route('filament.admin.resources.posts.edit', ['record' => $post]));
    $response->assertStatus(200);
});

it('can view record page', function () {
    $post = Post::create(['title' => 'Test Post']);
    $response = get(route('filament.admin.resources.posts.view', ['record' => $post]));
    $response->assertStatus(200);
});
