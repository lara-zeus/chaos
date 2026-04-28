---
title: Usage
weight: 3
---

## Overview

Chaos does not replace Filament; it gives you **shared building blocks**. Typical setup:

1. Model: use **`ChaosModel`**, timestamps, optional **SoftDeletes**, and **`$fillable` / `$guarded`** as usual.
2. Resource: extend **`ChaosResource`** and add **lang** entries for titles.
3. Form: wrap your schema with **`ChaosForms::make()`**.
4. Table: wrap your columns with **`ChaosTables::make()`**.
5. Infolist (optional): use **`ChaosInfos::make()`** for view pages.
6. Pages: extend **`ChaosListRecords`**, **`ChaosEditRecord`**, **`ChaosViewRecord`** so header actions match registered routes.

---

## `ChaosModel` trait

Add the trait to any model that should track **who created/updated** a row.

**Behavior**

- On **create**, sets `created_by` to the current user id (or `0` if guest).
- On **update**, sets `updated_by` the same way.
- Defines **`createdBy()`** and **`updatedBy()`** as `belongsTo` **`App\Models\User`**.

**Customization**

- Override **`isUsingActionBy()`** and return `false` to hide created-by / updated-by UI from **`ChaosForms`** and **`ChaosTables`**.
- Override **`isUsingSoftDelete()`** if the default detection (trait uses `SoftDeletes` and not `forceDeleting`) does not fit.

---

## `ChaosResource` base class

Extend this instead of **`Filament\Resources\Resource`**.

**Labels**

- **`langFile()`** takes the resource **slug** (last segment of `getSlug()`) and uses it as a translation file name.
- **`getModelLabel()`** → `__({langFile}.titleSingle)`
- **`getPluralModelLabel()`** → `__({langFile}.title)`

Add a file such as `lang/en/posts.php` with at least:

```php
return [
    'title' => 'Posts',
    'titleSingle' => 'Post',
];
```

**Query**

- If the model reports **`isUsingSoftDelete()`**, the global **`SoftDeletingScope`** is removed so you can list and restore trashed records from the same resource.
- If **`isUsingActionBy()`** is true, **`createdBy`** and **`updatedBy`** are **eager loaded**.

---

## `ChaosForms::make()`

Builds a responsive **grid**: your fields (span 3 of 4 on `sm` when the sidebar is visible) plus an optional **right column** (span 1).

**Parameters**

1. **`Schema $form`** — the Filament schema instance from `public static function form(Schema $schema): Schema` (pass your `$schema` through).
2. **`array $schema`** — components for the **main** area (sections, fields, etc.).
3. **`array $sideSections`** (optional) — extra components **above** the built-in “record info” block in the sidebar.

**Sidebar visibility**

The sidebar (including “record info”) shows when:

- you pass **non-empty `$sideSections`**, or
- the operation is **edit** and the model **uses timestamps**.

**Record info** includes formatted **`created_at`**, **`updated_at`**, and a **popover** on **`created_by`** (user card view). The **`updated_by`** popover in code is commented out in the package; you can fork or extend if you need it.

**Example**

```php
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosForms;

public static function form(Schema $schema): Schema
{
    return ChaosForms::make($schema, [
        Section::make()
            ->columnSpanFull()
            ->schema([
                // … your fields …
            ]),
    ], [
        // optional: extra sidebar sections only
    ]);
}
```

---

## `ChaosTables::make()`

Adds **standard columns and actions** around your own definitions.

**Signature**

```php
ChaosTables::make(
    string $resource,
    Table $table,
    array $columns,
    array $actions = [],
    ?array $bulkActions = [],
    array $filters = [],
): Table
```

- **`$resource`**: your resource **class string** (e.g. `PostResource::class`) so Chaos can read **`getModel()`**, **`getPages()`**, and authorization.
- **`$columns`**: your business columns (inserted after the auto **ID** column).
- **`$actions`**: extra row actions **prepended** inside the group; **View / Edit / Delete / Force delete / Restore** are appended when applicable.
- **`$bulkActions`**: pass **`null`** to disable **all** bulk actions; otherwise default bulk delete / force delete / restore are registered.
- **`$filters`**: merged before the **Trashed** filter (visible only when the model uses soft deletes).

**What you get automatically**

- **ID** column (searchable, hidden by default).
- **`created_at`**, **`updated_at`** when the model uses timestamps (hidden by default).
- **`createdBy.name`** and **`updatedBy.name`** popover columns when **`isUsingActionBy()`** is true.
- **`deleted_at`** when soft deletes are enabled.
- Row **action group** with smart visibility (e.g. **View** only if a `view` page exists).
- **`TrashedFilter`** when soft deletes are enabled.
- **Pagination**: page sizes **`[25]`**.
- **Default sort**: primary key **descending**.

**Example**

```php
use Filament\Tables\Table;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosTables;

public static function table(Table $table): Table
{
    return ChaosTables::make(
        static::class,
        $table,
        columns: [
            // … your columns …
        ],
        actions: [
            // optional extra row actions
        ],
        bulkActions: [
            // leave default bulk group; use null to disable bulks entirely
        ],
        filters: [
            // your filters
        ],
    );
}
```

---

## `ChaosInfos::make()`

For **view** / infolist **`Schema`**: two-column layout with your **entries** on the left (span 2 of 3) and a **record info** sidebar (timestamps, `created_by` / `updated_by` as HTML strings).

**Signature**

```php
ChaosInfos::make(Schema $schema, array $enries): Schema
```

The second parameter is the array of **infolist components** for the main column (the parameter name in code is `$enries`).

**Example**

```php
use Filament\Schemas\Schema;
use LaraZeus\Chaos\Filament\ChaosResource\ChaosInfos;

public static function infolist(Schema $schema): Schema
{
    return ChaosInfos::make($schema, [
        // … TextEntry::make(...) components for your model …
    ]);
}
```

---

## Page classes

Replace Filament’s default pages so header actions match **registered** pages only.

| Class | Extra header actions |
|--------|----------------------|
| **`ChaosListRecords`** | **Create** (if `create` page exists) |
| **`ChaosEditRecord`** | **View** (if `view` exists), **Delete** |
| **`ChaosViewRecord`** | **Edit** (if `edit` exists) |

Register them in your resource’s **`getPages()`** as you would with stock Filament pages.

---

## `MultiLang` form component

Tabs component that builds **one tab per locale** from **`config('app.locales')`**. Each tab stores state under a **state path** you choose (the string passed to **`MultiLang::make()`**).

- The **current app locale** tab’s field is **required**; others are not.
- Expects something like `config('app.locales')` to be an array of locale keys with at least a **`name`** for the tab label.

Use with **Spatie Laravel Translatable** (or similar) so the attribute is stored per locale.

**Example**

```php
use LaraZeus\Chaos\Forms\Components\MultiLang;

MultiLang::make('title')
    ->label(__('Title'));
```

Ensure `config('app.locales')` is defined and matches your translatable setup.

---

## `UserCardColumn`

Extends **`LaraZeus\Popover\Tables\PopoverColumn`**. Use it when you want the same popover behavior as Chaos’s user columns but with your own field name or formatting.

---

## Dependencies

Chaos depends on **`lara-zeus/popover`**. Install the Filament assets and Tailwind sources for Popover (and Chaos views) as described in **[Assets](assets.md)**.
