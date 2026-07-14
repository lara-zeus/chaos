---
title: Introduction
weight: 1
---

## Introduction

@zeus chaos is a thin convention layer on top of Filament. It standardizes how **resources**, **forms**, **tables**, **infolists**, and **models** handle things you repeat on every CRUD screen: audit fields (`created_by` / `updated_by`), soft deletes, timestamps, row actions, and bulk actions.

**[Github](https://github.com/lara-zeus/chaos) · [Packagist](https://packagist.org/packages/lara-zeus/chaos) · [Discord](https://discord.com/channels/883083792112300104)**

## Laravel Boost Skill

Chaos includes a skill file for [Laravel Boost](https://laravel-boost.com). This skill instructs AI assistants (like Claude Code and Cursor) on how to correctly migrate and standardize a project to the `lara-zeus/chaos` architecture. It covers database migrations (`actionBy`, `softDeletes`), model traits (`ChaosModel`), and strict usage of `ChaosResource`, `ChaosForms`, `ChaosTables`, and `ChaosInfos`.

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

## Support

Available support channels:

* Join our channel on [Discord](https://discord.com/channels/883083792112300104)
* Open an issue on [GitHub](https://github.com/lara-zeus/chaos/issues)
* Email us using the [contact center](https://larazeus.com/contact-us)
