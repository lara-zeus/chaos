<?php

namespace Tests\Filament\Resources;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\Chaos\Filament\ChaosResource;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosForms;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosTables;
use LaraZeus\Chaos\Forms\Components\MultiLang;
use Tests\Filament\Resources\PostResource\Pages\CreatePost;
use Tests\Filament\Resources\PostResource\Pages\EditPost;
use Tests\Filament\Resources\PostResource\Pages\ListPosts;
use Tests\Filament\Resources\PostResource\Pages\ViewPost;
use Tests\Models\Post;

class PostResource extends ChaosResource
{
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return ChaosForms::make($schema, [
            TextInput::make('title')->required(),
            MultiLang::make('content'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return ChaosTables::make(static::class, $table, [
            TextColumn::make('title'),
        ], [
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChaosInfos::make($schema, [
            TextEntry::make('title'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
            'view' => ViewPost::route('/{record}'),
        ];
    }
}
