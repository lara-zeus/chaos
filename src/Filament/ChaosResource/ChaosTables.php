<?php

namespace LaraZeus\Chaos\Filament\ChaosResource;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

        $model = new ($table->getModel());

        return $table
            ->columns([
                TextColumn::make($model->getKeyName())
                    ->searchable(query: fn (
                        Builder $query,
                        string $search
                    ) => $query->orWhere(DB::raw($model->getTable() . '.' . $model->getKeyName()), 'like', '%' . $search . '%'))
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
                    ->content(function ($record) {
                        /** @var view-string $view */
                        $view = 'zeus-chaos::tables.columns.popover-user-card';

                        return view($view, [
                            'user' => $record->createdBy,
                            'column' => 'created-by',
                            'record' => $record,
                        ]);
                    })
                    ->label(__('zeus-chaos::core.created_by'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(method_exists($model, 'isUsingActionBy') && $model::isUsingActionBy()),

                TextColumn::make('updated_at')
                    ->label(__('zeus-chaos::core.updated_at'))
                    ->visible($model->usesTimestamps())
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                PopoverColumn::make('updatedBy.name')
                    ->placement('right')
                    ->searchable(['name'])
                    ->content(function ($record) {
                        /** @var view-string $view */
                        $view = 'zeus-chaos::tables.columns.popover-user-card';

                        return view($view, [
                            'user' => $record?->updatedBy,
                            'column' => 'updated-by',
                            'record' => $record,
                        ]);
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('zeus-chaos::core.updated_by'))
                    ->visible(method_exists($model, 'isUsingActionBy') && $model::isUsingActionBy()),

                TextColumn::make('deleted_at')
                    ->visible(method_exists($model, 'isUsingSoftDelete') && $model::isUsingSoftDelete())
                    ->label(__('zeus-chaos::core.deleted_at'))
                    ->dateTime()
                    ->searchable(false)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    ...$actions,
                    ViewAction::make()
                        ->visible(fn () => static::resourceHasPage($resource, 'view')),
                    EditAction::make()->color('info')->visible(static::resourceHasPage($resource, 'edit')),
                    DeleteAction::make()
                        ->visible(function ($record) use ($actions, $resource) {
                            return collect($actions)->filter(function ($utem) {
                                return $utem instanceof DeleteAction;
                            })->isEmpty()
                                && $resource::authorize('delete', $record)->allowed();
                        }),
                    ...(method_exists($model, 'isUsingSoftDelete') && $model::isUsingSoftDelete() ? [
                        ForceDeleteAction::make(),
                        RestoreAction::make(),
                    ] : []),
                ]),
            ])
            ->filters([
                ...$filters,
                TrashedFilter::make()->visible(method_exists($model, 'isUsingSoftDelete') && $model::isUsingSoftDelete()),
            ])
            ->paginated([25])
            ->defaultSort($model->getTable() . '.' . $model->getKeyName(), 'desc')
            ->toolbarActions(static::getBulkActions($bulkActions, $table));
    }

    public static function getBulkActions(?array $bulkActions, Table $table): array
    {
        if ($bulkActions === null) {
            return [];
        }

        $model = $table->getModel();

        return [
            BulkActionGroup::make([
                ...$bulkActions,
                DeleteBulkAction::make(),
                // @phpstan-ignore-next-line
                ForceDeleteBulkAction::make()->visible(method_exists($model, 'isUsingSoftDelete') && $model::isUsingSoftDelete()),
                // @phpstan-ignore-next-line
                RestoreBulkAction::make()->visible(method_exists($model, 'isUsingSoftDelete') && $model::isUsingSoftDelete()),
            ]),
        ];
    }
}
