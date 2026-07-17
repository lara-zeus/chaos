<?php

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosTables;
use Livewire\Component;
use Tests\Filament\Resources\PostResource;
use Tests\Models\Post;

it('registers blueprint macros', function () {
    $connection = DB::connection();
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
    expect(PostResource::getModelLabel())->toBe('post')
        ->and(PostResource::getPluralModelLabel())->toBe('posts');
});

it('tests chaos tables with null bulk actions', function () {
    $livewire = new class extends Component implements HasForms, HasTable
    {
        use InteractsWithForms;
        use InteractsWithTable;
    };
    $table = ChaosTables::getBulkActions(null, new Table($livewire));
    expect($table)->toBeEmpty();
});
