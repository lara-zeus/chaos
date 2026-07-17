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
use Livewire\Livewire;
use Tests\Filament\Resources\PostResource;
use Tests\Filament\Resources\PostResource\Pages\ListPosts;
use Tests\Models\Post;
use Tests\Models\User;

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

it('renders createdBy and updatedBy popover contents in ChaosTables', function () {
    $post = Post::create(['title' => 'Test Post']);
    $user = User::create(['name' => 'Test User', 'email' => 'test@test.com', 'password' => 'password']);
    $post->createdBy()->associate($user);
    $post->updatedBy()->associate($user);
    $post->save();

    $livewire = Livewire::test(ListPosts::class);
    $table = $livewire->instance()->getTable('table');

    $createdCol = $table->getColumn('createdBy.name')->record($post);
    $createdCol->evaluate($createdCol->getContent());

    $updatedCol = $table->getColumn('updatedBy.name')->record($post);
    $updatedCol->evaluate($updatedCol->getContent());

    expect(true)->toBeTrue();
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
