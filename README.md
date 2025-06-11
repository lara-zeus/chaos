# Chaos
>Chaos is the mythological void state preceding the creation of the universe.

<p align="center">
<a href="https://larazeus.com"><img src="https://larazeus.com/images/lara-zeus-chaos.webp?v=3" /></a>
</p>

<p align="center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lara-zeus/chaos.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/chaos)
[![Tests](https://img.shields.io/github/actions/workflow/status/lara-zeus/chaos/run-tests.yml?label=tests&style=flat-square&branch=1.x)](https://github.com/lara-zeus/chaos/actions?query=workflow%3Arun-tests+branch%3A1.x)
[![Code Style](https://img.shields.io/github/actions/workflow/status/lara-zeus/chaos/fix-php-code-style-issues.yml?label=code-style&flat-square)](https://github.com/lara-zeus/chaos/actions?query=workflow%3Afix-php-code-style-issues+branch%3A1.x)
[![Total Downloads](https://img.shields.io/packagist/dt/lara-zeus/chaos.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/chaos)
[![Total Downloads](https://img.shields.io/github/stars/lara-zeus/chaos?style=flat-square)](https://github.com/lara-zeus/chaos)

</p>

_💖 if you think this has potential, star ⭐️ the project to let me know :)_

## Support Filament

<a href="https://github.com/sponsors/danharrin">
<img alt="filament-logo" src="https://larazeus.com/images/filament-sponsor-banner.webp">
</a>

# Opinionated Filament Setup

provide extra layer between you app and Filament, dont worry about generic column like timestamps and stuff.

and some more perks, more details coming soon.

## Installation:
```bash
composer require lara-zeus/chaos
```

### `ChaosModel` trait:
  - add action by
  - checks for `isUsingActionBy` and `isUsingSoftDelete`

### 'ChaosResource' to extend all resources
  - set lang file per resource
  - set the Model Label and Plural Model Label
  - add the soft delete scope
  - lazy load the actions by relations

### `ChaosForms` class
  - all forms will have the same looks and functionalty
  - add a side column for timestamps and action by
  - you can add side section
  - usage:

```php
public static function form(Form $form): Form
{
    return ChaosForms::make($form, [
        Section::make()
            ->columnSpanFull()
            ->columns()
            ->schema([
                //
            ]),
    ]);
}
```

### ChaosInfos



### ChaosTables

  - add timestamps columns, hidden by default
  - add actions by with popover for user info
  - add all defualt actions per row
    - view
    - edit
    - delete
    - force delete
    - restore
  - add soft delete  filters
  - set pagination 25
  - set default sort by id desc
  - set bulk actions
  - usage:

```php
public static function table(Table $table): Table
{
    return ChaosTables::make(
        static::class,
        $table,
        columns:[
            //
        ],
        actions: [
            //
        ],
        bulkActions: [
            //
        ],
        filters: [
            //
        ]
    );
}
```

### `ChaosEditRecord` class
    
- add header actions:
  - view and delete

### `ChaosListRecords` class
    
- add header actions:
  - create

### `ChaosViewRecord` class
    
- add header actions:
  - edit
