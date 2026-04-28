---
title: Installation
weight: 1
---

## Prerequisites

- Laravel with **Filament v5** installed and a panel configured.

## Install

```bash
composer require lara-zeus/chaos
```

The package auto-registers `ChaosServiceProvider`. It ships **translations** and **Blade views** (popover user card partials, etc.).

## Database

If you use **`ChaosModel`**, add nullable string columns for audit users and index them as you see fit:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('your_table', function (Blueprint $table) {
            $table->actionBy(); // created_by, updated_by
        });
    }

    public function down(): void
    {
        Schema::table('your_table', function (Blueprint $table) {
            $table->dropActionBy();
        });
    }
};
```

Use Laravel’s **`SoftDeletes`** on the model when you want trashed rows, **trashed filter**, and restore / force-delete actions from **`ChaosTables`**.

## Publish (optional)

Publish language files to override labels:

```bash
php artisan vendor:publish --tag=zeus-chaos-translations
```

Translation keys used by the package live under the `zeus-chaos::core.*` namespace (for example `record_info`, `created_at`, `created_by`).

## Next steps

See **[Usage](usage.md)** for how to extend `ChaosResource`, call `ChaosForms` / `ChaosTables` / `ChaosInfos`, and use the page classes.
