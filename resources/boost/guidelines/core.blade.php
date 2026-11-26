## Lara Zeus Chaos (`lara-zeus/chaos`)

Lara Zeus Chaos provides standardized Filament v5 Resource, Page, Form, Infolist, Table, and Eloquent model audit conventions (`created_by`, `updated_by`, `SoftDeletes`, popover user cards, and locale-based labels).

### Key Classes & Macros
- Service provider: `LaraZeus\Chaos\ChaosServiceProvider` (config: `config/zeus-chaos.php`, tag: `zeus-chaos-translations`)
- Blueprint macros: `$table->actionBy()` (adds nullable `created_by` and `updated_by` string columns) and `$table->dropActionBy()`
- Model trait: `LaraZeus\Chaos\Concerns\ChaosModel` (populates `created_by`/`updated_by`, defines `createdBy()`, `updatedBy()`, `isUsingActionBy()`, `isUsingSoftDelete()`)
- Resource base & helpers:
  - `LaraZeus\Chaos\Filament\ChaosResource`
  - `LaraZeus\Chaos\Filament\ChaosResource\ChaosForms::make(Schema $form, array $schema, array $sideSections = [])`
  - `LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos::make(Schema $schema, array $enries, array $sideSection = [])`
  - `LaraZeus\Chaos\Filament\ChaosResource\ChaosTables::make(string $resource, Table $table, array $columns, array $actions = [], ?array $bulkActions = [], array $filters = [])`
- Page base classes (`LaraZeus\Chaos\Filament\ChaosResource\Pages\*`): `ChaosListRecords`, `ChaosCreateRecord`, `ChaosEditRecord`, `ChaosViewRecord`
- Form/Table components (`LaraZeus\Chaos\Forms\Components\*`): `MultiLang`, `UserCardColumn`

### Rules & Anti-Patterns
- Always pass named arguments to `ChaosForms::make()`, `ChaosInfos::make()` (note parameter spelling `enries:` and `sideSection:`), and `ChaosTables::make()`.
- Do not manually add `id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `TrashedFilter`, or standard View/Edit/Delete/Restore/ForceDelete actions when using `ChaosTables::make()` — it injects them automatically.
- Do not override `getModelLabel()` or `getPluralModelLabel()` on `ChaosResource` subclasses when `zeus-chaos.use_locale` is `true`; provide `titleSingle` and `title` in the matching lang file instead.
- Use `LaraZeus\Chaos\Concerns\ChaosModel` (trait), not a non-existent `Models\ChaosModel` class.
