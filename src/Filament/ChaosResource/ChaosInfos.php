<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;

class ChaosInfos
{
    public static function make(Infolist $infolist, array $enries): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(['sm' => 3])
                    ->schema([
                        Grid::make()->schema($enries)->columnSpan(['sm' => 2]),
                        Grid::make()
                            ->schema([
                                Section::make(__('zeus-chaos::core.record_info'))
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
                                            ->getStateUsing(fn ($record) => $record?->updated_by ? new HtmlString($record->updated_by) : '-'),
                                    ]),
                            ])
                            ->columnSpan(['sm' => 1]),
                    ]),
            ]);
    }
}
