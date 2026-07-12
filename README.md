<p align="center">
<a href="https://larazeus.com"><img src="https://larazeus.com/images/lara-zeus-chaos.webp?v=3" /></a>
</p>

# Chaos
>Chaos is the mythological void state preceding the creation of the universe.



<p align="center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lara-zeus/chaos.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/chaos)
[![Tests](https://img.shields.io/github/actions/workflow/status/lara-zeus/chaos/run-tests.yml?label=tests&style=flat-square&branch=1.x)](https://github.com/lara-zeus/chaos/actions?query=workflow%3Arun-tests+branch%3A1.x)
[![Code Style](https://img.shields.io/github/actions/workflow/status/lara-zeus/chaos/fix-php-code-style-issues.yml?label=code-style&flat-square)](https://github.com/lara-zeus/chaos/actions?query=workflow%3Afix-php-code-style-issues+branch%3A1.x)
[![Total Downloads](https://img.shields.io/packagist/dt/lara-zeus/chaos.svg?style=flat-square)](https://packagist.org/packages/lara-zeus/chaos)
[![Total Downloads](https://img.shields.io/github/stars/lara-zeus/chaos?style=flat-square)](https://github.com/lara-zeus/chaos)

</p>

_💖 if you think this has potential, star ⭐️ the project to let me know :)_

## Versions

| Plugin Version | Filament Version |
| --- |------------------|
| `v1.x` | `v3.x`           |
| `v2.x` | `v4.x`           |
| `v3.x` | `v5.x`           |

## Support Filament

<a href="https://github.com/sponsors/danharrin">
<img alt="filament-logo" src="https://larazeus.com/images/filament-sponsor-banner.webp">
</a>

# Opinionated Filament Setup

zeus chaos is a thin convention layer on top of Filament. It standardizes how **resources**, **forms**, **tables**, **infolists**, and **models** handle things you repeat on every CRUD screen.

## Features

- **`ChaosModel`** — sets `created_by` / `updated_by` on save, defines `createdBy()` / `updatedBy()` relations, and exposes `isUsingActionBy()` / `isUsingSoftDelete()` so UI code can turn columns on or off.
- **Blueprint macros** — `actionBy()` and `dropActionBy()` for migrations.
- **`ChaosResource`** — derives model labels from a **lang file** named from the resource slug; adjusts the Eloquent query (soft-delete scope, eager-loading `createdBy` / `updatedBy` when enabled).
- **`ChaosForms`** — shared **grid layout** with your schema on the main area and an optional **sidebar** (your sections + collapsible “record info”: timestamps, created-by popover via [Popover](https://github.com/lara-zeus/popover)).
- **`ChaosTables`** — ID + **timestamp** columns, **created by / updated by** popover columns, **deleted at** when soft deletes are on, **trashed filter**, default **pagination 25** and **sort by primary key desc**, row **action group** (view / edit / delete / force delete / restore when allowed), and **bulk** delete / force delete / restore.
- **`ChaosInfos`** — infolist/`Schema` layout with **record info** sidebar (created/updated timestamps and by-fields).
- **`ChaosEditRecord`**, **`ChaosListRecords`**, **`ChaosViewRecord`** — header actions wired to **View** / **Delete** / **Create** / **Edit** only when the resource actually registers those pages.
- **`MultiLang`** — **tabs** per `config('app.locales')` for a translatable attribute (pairs well with Spatie Translatable).
- **`UserCardColumn`** — thin alias of Popover’s `PopoverColumn` for user detail popovers in tables.

## Full Documentation

> Visit our website to get the complete documentation: https://larazeus.com/docs/chaos

## Support

Available support channels:

* Join our channel on [Discord](https://discord.com/channels/883083792112300104)
* Open an issue on [GitHub](https://github.com/lara-zeus/chaos/issues)
* Email us using the [contact center](https://larazeus.com/contact-us)
