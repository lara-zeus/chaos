<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ChaosInfos
{
    /**
     * @throws \Exception
     */
    public static function make(Schema $infolist, array $enries): Schema
    {
        return $infolist
            ->schema([
                Grid::make(['sm' => 3])
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->schema($enries)
                            ->columnSpan(['sm' => 2]),
                        Grid::make()
                            ->schema([
                                Section::make(__('zeus-chaos::core.record_info'))
                                    ->columnSpanFull()
                                    ->compact()
                                    ->columns(2)
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
                            ->columnSpan(['sm' => 1]),
                    ]),
            ]);
    }
}
