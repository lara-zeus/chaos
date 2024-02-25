<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use LaraZeus\Popover\Form\PopoverForm;

class ChaosForms
{
    public static function make(Form $form, array $schema, array $sideSections = []): Form
    {
        return $form->schema([
            Grid::make(['sm' => 4])->schema([

                Grid::make()
                    ->schema($schema)
                    ->columnSpan(function (string $operation) use ($form, $sideSections) {
                        return (! empty($sideSections) || ($operation === 'edit' && (new $form->model)->usesTimestamps())) ? 3 : 4;
                    }),

                Grid::make()->schema([
                    ...$sideSections,
                    Section::make(__('zeus-chaos::core.record_info'))
                        ->columns(1)
                        ->compact()
                        ->schema([
                            Placeholder::make('created_at')
                                ->label(__('zeus-chaos::core.created_at'))
                                ->content(fn ($record): string => $record?->created_at
                                    ? $record->created_at->translatedFormat('Y/m/d - h:i A')
                                    : '-'),

                            PopoverForm::make('created_by')
                                ->formatStateUsing(fn ($record) => $record->createdBy?->name)
                                ->placement('right')
                                ->content(fn ($record) => view('zeus-chaos::tables.columns.popover-user-card', [
                                    'user' => $record?->createdBy,
                                    'column' => 'created-by',
                                    'record' => $record,
                                ]))
                                ->label(__('zeus-chaos::core.created_by')),

                            Placeholder::make('updated_at')
                                ->label(__('zeus-chaos::core.updated_at'))
                                ->content(fn ($record): string => $record?->updated_at
                                    ? $record->updated_at->translatedFormat('Y/m/d - h:i A')
                                    : '-'),

                            PopoverForm::make('updated_by')
                                ->formatStateUsing(fn ($record) => $record->updatedBy?->name)
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
                        ->visible(function (string $operation) use ($form, $sideSections) {
                            return ! empty($sideSections) || ($operation === 'edit' && (new $form->model)->usesTimestamps());
                        }),
                ])
                    ->visible(function (string $operation) use ($sideSections, $form) {
                        return ! empty($sideSections) || ($operation === 'edit' && (new $form->model)->usesTimestamps());
                    })
                    ->columnSpan(['sm' => 1]),
            ]),
        ]);
    }
}
