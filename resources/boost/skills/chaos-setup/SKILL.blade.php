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
* **Tables:** All index tables must structure their column definitions via `ChaosTables::make(resource: ..., table: ..., columns: [...])` to automatically inject standard pagination, popovers, and filters.
* **Infolists:** All infolists must format their content layout inside `ChaosInfos::make(schema: [...], sidebar: [...])`.
* **IMPORTANT:** In `ChaosForms::make()`, `ChaosTables::make()`, and `ChaosInfos::make()`, **always** use named arguments (e.g., `schema: $schema`, `resource: \App\Filament\Resources\PostResource::class`, `table: $table`, `columns: [...]`).

* **IMPORTANT:** Never add `id`, `created_at`, `updated_at`, `created_by`, or `updated_by` to the Filament table columns, and if they exist, remove them. `ChaosTables` automatically appends these columns.

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
```php
namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use LaraZeus\Chaos\Filament\ChaosResource;
use LaraZeus\Chaos\Filament\ChaosForms;
use LaraZeus\Chaos\Filament\ChaosTables;
use LaraZeus\Chaos\Filament\ChaosInfos;

class PostResource extends ChaosResource 
{
    protected static ?string $model = Post::class;

    public static function form(Form $form): Form
    {
        return ChaosForms::make(
            form: $form,
            schema: [TextInput::make('title')->required()]
        );
    }

    public static function table(Table $table): Table
    {
        return ChaosTables::make(
            resource: static::class,
            table: $table,
            columns: [
                TextColumn::make('title'),
            ]
        );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ChaosInfos::make(
            schema: $infolist,
            enries: [/* view details */]
        );
    }
}
```
