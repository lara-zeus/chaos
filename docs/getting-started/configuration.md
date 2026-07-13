---
title: Configuration
weight: 2
---

## Configuration

If you need to customize the configuration, publish it using:

```bash
php artisan vendor:publish --tag=zeus-chaos-config
```

### use_locale

Use lang file for model labels in ChaosResource.
When disabled, it will fall back to Filament's default resource labels.

```php
'use_locale' => true,
```
