---
name: zeus-chaos-setup
description: Rules for migrating a project to lara-zeus/chaos architecture, covering migrations, model traits, and Filament resource components.
compatible_agents:
  - Claude Code
  - Cursor
tags:
  - laravel
  - filament
  - chaos
  - architecture
---

# Chaos Setup and Standardization

## Context
This skill outlines the strict architectural conversion required when onboarding an existing Laravel/Filament project onto the `lara-zeus/chaos` convention layer. Follow these rules to standardize audit tracking, soft deletes, and user interface schemas across the entire codebase.

## Rules

### 1. Database & Migrations Standardization
* Scan all existing database tables for a `deleted_at` timestamp.
* If any table lacks soft deletes, create exactly **one new migration file** to add `$table->softDeletes();` to all missing tables.
* Create exactly **one new migration file** that appends the Chaos audit columns via `$table->actionBy();` to all tables in the database.
* Include corresponding drop macros (`$table->dropActionBy();`) in the migration's `down()` method.
* for any new migration file, always add: `$table->softDeletes();`, `$table->actionBy();` in `up` and `$table->dropActionBy();` in `down()` methods.

### 2. Model Blueprint Overrides
* Every Eloquent model in the application must use the `LaraZeus\Chaos\Models\ChaosModel` trait to enable implicit audit observers (`created_by` / `updated_by`).
* Ensure the `Illuminate\Database\Eloquent\SoftDeletes` trait is present on all models. If it is missing, append it immediately.

### 3. Filament Core Class Inheritance
* Change all structural resource declarations to inherit from Chaos wrapper classes. Do not use Filament base components.
* Main Resource classes must extend `LaraZeus\Chaos\Filament\ChaosResource`.
* List views must extend `LaraZeus\Chaos\Filament\Pages\ChaosListRecords`.
* Edit views must extend `LaraZeus\Chaos\Filament\Pages\ChaosEditRecord`.
* View views must extend `LaraZeus\Chaos\Filament\Pages\ChaosViewRecord`.

### 4. Layout & Schema Constraints
* **Forms:** All forms inside your resources must encapsulate their schema arrays via `ChaosForms::make(schema: [...], sidebar: [...])`.
* **Tables:** All index tables must structure their column definitions via `ChaosTables::make(resource: ..., table: ..., columns: [...])` to automatically inject standard pagination, popovers, and filters. This applies to **both Resources and RelationManagers**.
* **Infolists:** All infolists must format their content layout inside `ChaosInfos::make(schema: [...], sidebar: [...])`.
* **IMPORTANT:** In `ChaosForms::make()`, `ChaosTables::make()`, and `ChaosInfos::make()`, **always** use named arguments (e.g., `schema: $schema`, `resource: \App\Filament\Resources\PostResource::class`, `table: $table`, `columns: [...]`).
* **IMPORTANT:** For RelationManagers using `ChaosTables::make()`, ensure the `resource` argument matches the parent Resource where the relation is displayed, or the specific Resource that defines the related model.
* **IMPORTANT:** When using `ChaosTables`, there is no need to manually add `EditAction`, `DeleteAction`, `DeleteBulkAction`, or `CreateAction` to the table configuration unless explicitly asked for or if you need to modify these actions. `ChaosTables` handles standard record and bulk actions automatically.


Separate the **Chaos wrappers** (which live in the Resource) from the **component/column/filter definitions** (which live in dedicated Schema and Table classes). The Resource's `form()`, `table()`, and `infolist()` methods contain ONLY the Chaos wrapper call and delegate the actual arrays to dedicated classes.

* **Where things live:**
    * `Schemas/{Model}Form.php` — form fields.
    * `Schemas/{Model}Infolist.php` — infolist entries.
    * `Tables/{Model}Table.php` — table columns and filters.
* **Dedicated classes return plain arrays** via static methods. They do NOT receive `$schema`/`$table` and do NOT call the Chaos wrappers themselves:
    * `configure(): array` — the primary array (form schema, infolist entries, or table columns).
    * `sideSections(): array` — form side `Section` components (maps to the `ChaosForms` `sideSections:` argument).
    * `sideSection(): array` — infolist side `Section` entries (maps to the `ChaosInfos` `sideSection:` argument).
    * `filters(): array` — table filters (maps to the `ChaosTables` `filters:` argument).
    * Only add `sideSections()`, `sideSection()`, or `filters()` when the resource actually needs them.
* **Correct Chaos namespaces** (all under `ChaosResource`):
    * `LaraZeus\Chaos\Filament\ChaosResource\ChaosForms`
    * `LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos`
    * `LaraZeus\Chaos\Filament\ChaosResource\ChaosTables`
* **Forms:** wrap in the Resource with `ChaosForms::make(form: $schema, schema: {Model}Form::configure(), sideSections: {Model}Form::sideSections())`.
* **Infolists:** wrap in the Resource with `ChaosInfos::make(schema: $schema, enries: {Model}Infolist::configure(), sideSection: {Model}Infolist::sideSection())`. Note the argument is spelled `enries` (package spelling) and the side argument is singular `sideSection`.
* **Tables:** wrap in the Resource with `ChaosTables::make(resource: self::class, table: $table, columns: {Model}Table::configure(), filters: {Model}Table::filters())` to automatically inject standard pagination, popovers, actions, and filters. This applies to **both Resources and RelationManagers**.
* **Table-level modifiers:** chain `->defaultSort(...)`, `->modifyQueryUsing(...)`, etc. on the `ChaosTables::make()` result **inside the Resource**, not inside the Table class.
* **IMPORTANT:** In `ChaosForms::make()`, `ChaosTables::make()`, and `ChaosInfos::make()`, **always** use named arguments (e.g., `schema: $schema`, `resource: self::class`, `table: $table`, `columns: [...]`).
* **IMPORTANT:** For RelationManagers using `ChaosTables::make()`, ensure the `resource` argument matches the parent Resource where the relation is displayed, or the specific Resource that defines the related model.
* **IMPORTANT:** When using `ChaosTables`, there is no need to manually add `EditAction`, `DeleteAction`, `DeleteBulkAction`, or `CreateAction` to the table configuration unless explicitly asked for or if you need to modify these actions. `ChaosTables` handles standard record and bulk actions automatically.
* **IMPORTANT:** Never add `id`, `created_at`, `updated_at`, `created_by`, or `updated_by` to the Filament table columns, and if they exist, remove them. `ChaosTables` automatically appends these columns.

### 5. Translations
* Do not define `getModelLabel` or `getPluralModelLabel` in resources that extend `ChaosResource`.
* Do not override `langFile()` method in the resource unless explicitly requested. The default `langFile()` resolution must be used. Ensure the translation file path matches this default resolution (e.g., in root `lang/{locale}/` rather than deep `filament/resources/` paths depending on parent configuration).
* Ensure the translation file contains `titleSingle` and `title` keys instead of custom nested structures.

---

## Examples

### Complete Migration Integration
```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Example batch update for existing tables
        foreach (['users', 'posts', 'orders'] as $tableName) {
            Schema::table($tableName, function (Blueprint table) {
                if (! Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }
                $table->actionBy();
            });
        }
    }

    public function down(): void
    {
        foreach (['users', 'posts', 'orders'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropActionBy();
                $table->dropSoftDeletes();
            });
        }
    }
};
```

### Model Structure Conversion
```php
namespace App\Models;

use LaraZeus\Chaos\Models\ChaosModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends ChaosModel 
{
    use SoftDeletes;

    protected $fillable = ['title', 'content'];
}
```

### Filament Resource Layout Wrapping

Keep the Resource thin: it only wraps arrays supplied by the dedicated Schema/Table classes with the Chaos helpers.

```php
// app/Filament/Resources/Posts/PostResource.php
namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Schemas\PostInfolist;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\Chaos\Filament\ChaosResource;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosForms;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosTables;

class PostResource extends ChaosResource
{
    protected static ?string $model = Post::class;

    public static function form(Schema $schema): Schema
    {
        return ChaosForms::make(
            form: $schema,
            schema: PostForm::configure(),
            sideSections: PostForm::sideSections(),
        );
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChaosInfos::make(
            schema: $schema,
            enries: PostInfolist::configure(),
            sideSection: PostInfolist::sideSection(),
        );
    }

    public static function table(Table $table): Table
    {
        return ChaosTables::make(
            resource: self::class,
            table: $table,
            columns: PostsTable::configure(),
            filters: PostsTable::filters(),
        )
            ->defaultSort('id', 'desc');
    }
}
```

The dedicated classes only return arrays of components/columns/filters:

```php
// app/Filament/Resources/Posts/Schemas/PostForm.php
namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PostForm
{
    public static function configure(): array
    {
        return [
            Section::make()
                ->hiddenLabel()
                ->columnSpanFull()
                ->schema([
                    TextInput::make('title')->required(),
                ]),
        ];
    }

    // Only add this when the form needs a side column.
    public static function sideSections(): array
    {
        return [
            Section::make(__('posts.sections.meta'))
                ->columnSpanFull()
                ->schema([
                    TextInput::make('slug'),
                ]),
        ];
    }
}
```

```php
// app/Filament/Resources/Posts/Schemas/PostInfolist.php
namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class PostInfolist
{
    public static function configure(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('title'),
                ]),
        ];
    }

    // Only add this when the infolist needs a side column.
    public static function sideSection(): array
    {
        return [
            Section::make(__('posts.sections.meta'))
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('slug'),
                ]),
        ];
    }
}
```

```php
// app/Filament/Resources/Posts/Tables/PostsTable.php
namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PostsTable
{
    public static function configure(): array
    {
        return [
            TextColumn::make('title')
                ->searchable()
                ->sortable(),
        ];
    }

    // Only add this when the table needs filters.
    public static function filters(): array
    {
        return [
            SelectFilter::make('status'),
        ];
    }
}
```
