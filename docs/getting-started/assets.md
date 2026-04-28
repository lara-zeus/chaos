---
title: Assets
weight: 3
---

## Compiling assets

Chaos ships Blade views under the **`zeus-chaos`** namespace. If you use a **custom Filament theme** with Tailwind, include those views (and **`lara-zeus/popover`** views) so utility classes are picked up.

We use [Tailwind CSS](https://tailwindcss.com/) with Filament. On **Tailwind v4**, add **`@source`** entries in your theme/CSS entry file (not only `tailwind.config.js` `content`).

Example for `resources/css/app.css` (adjust `../../` until it reaches the app root):

```css
@import "tailwindcss";

@source "../../vendor/lara-zeus/chaos/resources/views/**/*.blade.php";
@source "../../vendor/lara-zeus/popover/resources/views/**/*.blade.php";
```
