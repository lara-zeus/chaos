<?php

use Tests\Models\Post;
use Tests\Models\User;
use Illuminate\Database\Schema\Blueprint;

it('registers blueprint macros', function () {
    $connection = \Illuminate\Support\Facades\DB::connection();
    $blueprint = new Blueprint($connection, 'test_table');
    $blueprint->actionBy();
    $blueprint->dropActionBy();

    expect($blueprint->getColumns())->toHaveCount(2);
});

it('sets updated_by on update', function () {
    $post = Post::create(['title' => 'Test Post']);
    $post->update(['title' => 'Updated Post']);
    
    expect($post->updated_by)->not->toBeNull();
});

it('tests chaos resource locale config disabled', function () {
    config(['zeus-chaos.use_locale' => false]);
    expect(\Tests\Filament\Resources\PostResource::getModelLabel())->toBe('post');
    expect(\Tests\Filament\Resources\PostResource::getPluralModelLabel())->toBe('posts');
});

it('tests chaos tables with null bulk actions', function () {
    $livewire = new class extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable {
        use \Filament\Forms\Concerns\InteractsWithForms;
        use \Filament\Tables\Concerns\InteractsWithTable;
    };
    $table = \LaraZeus\Chaos\Filament\ChaosResource\ChaosTables::getBulkActions(null, new \Filament\Tables\Table($livewire));
    expect($table)->toBeEmpty();
});

