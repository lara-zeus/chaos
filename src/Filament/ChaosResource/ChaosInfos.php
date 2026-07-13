<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Exception;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ChaosInfos
{
    /**
     * @throws Exception
     */
    public static function make(Schema $schema, array $enries, array $sideSection = []): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->schema($enries)
                            ->columnSpan(2),
                        Grid::make()
                            ->schema([
                                ...$sideSection,
                                Section::make(__('zeus-chaos::core.record_info'))
                                    ->columnSpanFull()
                                    ->compact()
                                    ->columns()
                                    ->iconColor('secondary')
                                    ->icon('tabler-info-circle-filled')
                                    ->collapsible()
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label(__('zeus-chaos::core.created_at'))
                                            ->dateTime('Y/m/d - h:i A'),
                                        TextEntry::make('created_by')
                                            ->label(__('zeus-chaos::core.created_by'))
                                            ->getStateUsing(fn ($record) => $record?->created_by ? new HtmlString($record->created_by) : '-'),

                                        TextEntry::make('updated_at')
                                            ->label(__('zeus-chaos::core.updated_at'))
                                            ->dateTime('Y/m/d - h:i A'),
                                        TextEntry::make('updated_by')
                                            ->label(__('zeus-chaos::core.updated_by'))
                                            ->state(fn ($record) => $record?->updated_by ? new HtmlString($record->updated_by) : '-'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
