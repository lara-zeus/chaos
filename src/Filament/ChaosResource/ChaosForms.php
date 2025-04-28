<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use LaraZeus\Popover\Form\PopoverForm;

class ChaosForms
{
    public static function make(Schema $form, array $schema, array $sideSections = []): Schema
    {
        return $form->schema([
            Grid::make(['sm' => 4])
                ->columnSpanFull()
                ->schema([

                Grid::make()
                    ->columnSpanFull()
                    ->schema($schema)
                    ->columnSpan(fn (string $operation) => (static::showSideSection($operation, $sideSections, $form)) ? 3 : 4),

                Grid::make()
                    ->schema([
                        ...$sideSections,
                        Section::make(__('zeus-chaos::core.record_info'))
                            ->columnSpanFull()
                            ->columns(1)
                            ->compact()
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('zeus-chaos::core.created_at'))
                                    ->state(fn ($record): string => $record?->created_at
                                        ? $record->created_at->translatedFormat('Y/m/d - h:i A')
                                        : '-'),

                                PopoverForm::make('created_by')
                                    ->formatStateUsing(fn ($record) => $record?->createdBy?->name)
                                    ->placement('right')
                                    ->content(fn ($record) => view('zeus-chaos::tables.columns.popover-user-card', [
                                        'user' => $record?->createdBy,
                                        'column' => 'created-by',
                                        'record' => $record,
                                    ]))
                                    ->label(__('zeus-chaos::core.created_by')),

                                TextEntry::make('updated_at')
                                    ->label(__('zeus-chaos::core.updated_at'))
                                    ->state(fn ($record): string => $record?->updated_at
                                        ? $record->updated_at->translatedFormat('Y/m/d - h:i A')
                                        : '-'),

                                PopoverForm::make('updated_by')
                                    ->formatStateUsing(fn ($record) => $record?->updatedBy?->name)
                                    ->placement('right')
                                    ->content(fn ($record) => view('zeus-chaos::tables.columns.popover-user-card', [
                                        'user' => $record?->updatedBy,
                                        'column' => 'updated-by',
                                        'record' => $record,
                                    ]))
                                    ->label(__('zeus-chaos::core.updated_by')),
                            ])
                            ->icon('tabler-info-circle-filled')
                            ->collapsible()
                            ->visible(fn (string $operation) => static::showSideSection($operation, $sideSections, $form)),
                    ])
                    ->visible(fn (string $operation) => static::showSideSection($operation, $sideSections, $form))
                    ->columnSpan(['sm' => 1]),
            ]),
        ]);
    }

    public static function showSideSection(string $operation, array $sideSections, Form $form): bool
    {
        return
            ! empty($sideSections)
            || ($operation === 'edit' && (new $form->model)->usesTimestamps());
    }
}
