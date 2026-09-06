---
name: modular-code
description: >
  Keep code modular: high cohesion, balanced coupling, thin boundaries, small
  public APIs. Use whenever adding a feature, splitting files, placing new
  types, or wiring shortcodes/views/HTTP — even if the user does not say
  "modular". Do not invent Clean Architecture / hexagonal / DDD theater.
---

# Modular code

A module is a slice you can understand without opening the whole plugin. Coupling is fine when related things sit together. Misplaced coupling is the bug.

## Place code where it already lives

Match siblings. Do not invent a new top-level folder.

| Path | Owns |
| --- | --- |
| `actCore.php` | bootstrap, constants, activation hooks |
| `includes/functions.php` | CPT, cron, Elementor queries, API import, shortcode |
| `assets/img/` | default thumbnail |

Shared helpers: only after **two** call sites need the same concept. Put them next to the owner, not a junk-drawer `utils.php`.

## What a module is

A class, a small set of files in one folder, or a focused function set. It has a name that is the concept, a short public surface, and internals outsiders never call.

Not a module: `Utils`, `Helpers`, `Misc`, `functions.php` dumping ground, `actCore` stuffed with business logic.

## Cohesion vs coupling

- **High cohesion:** group what changes together (card view vs hero card vs assets), not a new `controllers/` tree.
- **Low coupling across features:** views do not call HTTP. HTTP does not echo markup. Bootstrap does not render listings.
- **High coupling inside a feature is OK.** Do not split a 40-line helper across 5 files.

Balanced coupling:

| Strength of link | Distance | Do |
| --- | --- | --- |
| Strong (same import, same CPT) | Keep close | Same `includes/functions.php` |
| Weak (generic format, HTTP client) | Can be far | small helper next to the only caller, or core after 2 consumers |
| Strong + far | Bad | merge or introduce a real contract |
| Weak + close | Often over-abstracted | inline |

## Boundaries

- **Bootstrap (`actCore`):** wiring only — constants, require, enqueue, hook registration.
- **Shortcodes:** return HTML from post meta.
- **HTTP / data:** listing request and decode. No template HTML.
- **Import:** write posts/meta. Do not render Elementor markup here.

A shortcode method that also talks to the API, mutates listings, and prints a view is three modules pretending to be one. Split when you are already in that code **and** the file has two reasons to change — not as architecture theater.

## File rules

- New feature: new method or class rather than growing a god class past ~300 lines — unless `simple-code` says 15 lines in the existing class suffice.
- Do not create `includes/Admin/Rest/Services` unless the user asked for that structure.
- Keep boot as wiring.

## Dependencies

- Depend inward: view ← shortcode composition ← HTTP/data. Never a template importing `wp_safe_remote_post`.
- No circular `use`. If A needs B and B needs A, extract the shared bit or pass data in.
- A module may use WP APIs that belong to its job. It must not reach into another module's option keys except through that module's API.

## Public API

- Public methods are the contract. Mark the rest `private`.
- `final class` on new classes unless the plugin needs extension.
- Do not expose internals "for convenience" from templates.

## Split / don't split

**Extract when:** two+ call sites share one concept, the file has two reasons to change, or a circular include appeared.

**Do not extract when:** one call site, "might reuse later", or split would force jumping across files for a single idea (`simple-code` wins).

**No new hexagonal / DDD / CQRS layers** unless the task explicitly asks.

## Change test

If a requirement about the listings URL or search body changes, only the API helpers in `includes/functions.php` move.
If a requirement about Elementor query IDs changes, only those `elementor/query/*` hooks move.
If one tweak forces edits in bootstrap + import + Elementor + the shortcode for no shared reason, the boundary is wrong. Fix the boundary, then the feature — only when that is the task.

## Checklist

- [ ] File sits next to its feature siblings
- [ ] No new root folder
- [ ] Bootstrap does not import listings; import helpers do not render Elementor markup
- [ ] No extra interface/layer for one implementation
- [ ] Shared code moved to a helper only after a second real consumer

## Sources

Feature-first architecture; Khononov balanced coupling (strength vs distance); BDP SoC without ceremony.
