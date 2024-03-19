<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\Popover\Tables\PopoverColumn;

class ChaosTables
{
    public static function resourceHasPage(string $resource, string $page): bool
    {
        return array_key_exists($page, $resource::getPages());
    }

    public static function make(
        string $resource,
        Table $table,
        array $columns,
        array $actions = [],
        ?array $bulkActions = [],
        array $filters = []
    ): Table {

        $model = (new ($resource::getModel()));

        return $table
            ->columns([
                TextColumn::make($model->getKeyName())
                    ->searchable(query: fn (Builder $query, string $search) => $query->orWhere($model->getTable() . '.' . $model->getKeyName(), 'like', '%' . $search . '%'))
                    ->label(__('zeus-chaos::core.id'))
                    ->toggleable(isToggledHiddenByDefault: true),

                ...$columns,

                TextColumn::make('created_at')
                    ->label(__('zeus-chaos::core.created_at'))
                    ->dateTime()
                    ->visible($model->usesTimestamps())
                    ->toggleable(isToggledHiddenByDefault: true),

                PopoverColumn::make('createdBy.name')
                    ->placement('bottom')
                    ->searchable(['name'])
                    ->content(fn ($record) => view('zeus-chaos::tables.columns.popover-user-card', [
                        'user' => $record->createdBy,
                        'column' => 'created-by',
                        'record' => $record,
                    ]))
                    ->label(__('zeus-chaos::core.created_by'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($model::isUsingActionBy()),

                TextColumn::make('updated_at')
                    ->label(__('zeus-chaos::core.updated_at'))
                    ->visible($model->usesTimestamps())
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                PopoverColumn::make('updatedBy.name')
                    ->placement('right')
                    ->searchable(['name'])
                    ->content(fn ($record) => view('zeus-chaos::tables.columns.popover-user-card', [
                        'user' => $record?->updatedBy,
                        'column' => 'updated-by',
                        'record' => $record,
                    ]))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('zeus-chaos::core.updated_by'))
                    ->visible($model::isUsingActionBy()),

                TextColumn::make('deleted_at')
                    ->visible($model::isUsingSoftDelete())
                    ->label(__('zeus-chaos::core.deleted_at'))
                    ->dateTime()
                    ->searchable(false)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ...$actions,
                    Tables\Actions\ViewAction::make()
                        /*->visible(function() use($resource){
                            return false;
                            dd($resource::getPages(),static::resourceHasPage($resource, 'view'));
                        })*/,
                    Tables\Actions\EditAction::make()->color('info')->visible(static::resourceHasPage($resource, 'edit')),
                    Tables\Actions\DeleteAction::make()
                        ->visible(
                            collect($actions)->filter(function ($utem) {
                                return $utem instanceof Tables\Actions\DeleteAction;
                            })->isEmpty()
                            && $resource::authorize('delete')->allowed()
                        ),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->filters([
                ...$filters,
                TrashedFilter::make()->visible($model::isUsingSoftDelete()),
            ])
            ->paginated([25])
            ->defaultSort($model->getKeyName(), 'desc')
            ->bulkActions(static::getBulkActions($bulkActions, $table));
    }

    public static function getBulkActions(?array $bulkActions, Table $table): array
    {
        if ($bulkActions === null) {
            return [];
        }

        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make()->visible($table->getModel()::isUsingSoftDelete()),
                Tables\Actions\RestoreBulkAction::make()->visible($table->getModel()::isUsingSoftDelete()),
            ]),
        ];
    }
}
