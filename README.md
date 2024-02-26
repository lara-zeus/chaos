# Chaos
>Chaos is the mythological void state preceding the creation of the universe.

## opinionated filament setup

provide extra layer between you app and Filament, dont worry about generic column like timestamps and stuff.

and some more perks, more details coming soon.

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
