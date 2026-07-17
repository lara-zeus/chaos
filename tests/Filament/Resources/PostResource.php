<?php

namespace Tests\Filament\Resources;

use Tests\Models\Post;
use Filament\Forms\Components\TextInput;
use LaraZeus\Chaos\Filament\ChaosResource;

class PostResource extends ChaosResource
{
    protected static ?string $model = Post::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return \LaraZeus\Chaos\Filament\ChaosResource\ChaosForms::make($schema, [
            TextInput::make('title')->required(),
            \LaraZeus\Chaos\Forms\Components\MultiLang::make('content'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return \LaraZeus\Chaos\Filament\ChaosResource\ChaosTables::make(static::class, $table, [
            \Filament\Tables\Columns\TextColumn::make('title'),
        ], [
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ]);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return \LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos::make($schema, [
            \Filament\Infolists\Components\TextEntry::make('title'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Tests\Filament\Resources\PostResource\Pages\ListPosts::route('/'),
            'create' => \Tests\Filament\Resources\PostResource\Pages\CreatePost::route('/create'),
            'edit' => \Tests\Filament\Resources\PostResource\Pages\EditPost::route('/{record}/edit'),
            'view' => \Tests\Filament\Resources\PostResource\Pages\ViewPost::route('/{record}'),
        ];
    }
}
