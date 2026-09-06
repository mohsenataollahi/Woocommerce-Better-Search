---
name: functional-code
description: >
  Write functional-light code: pure transforms, immutability, effects at the
  edge, composition over inheritance. Use whenever writing or changing logic,
  data transforms, shortcodes, services, or helpers — even if the user does not
  say "functional". Do not force academic FP or new libraries.
---

# Functional code (light)

Practical FP inside PHP and JS. Goal: testable cores, obvious edges. Not Haskell, not new FP packages.

## Reframe

Not: "which class owns this?"
Ask: "what is the pure transform, and where does the effect happen?"

Every function is one of:

| Kind | Role |
| --- | --- |
| Pure transform | Data in, data out. Same input → same output. No IO, no DB, no echo, no global. |
| Edge | HTTP, `$wpdb`, options, filesystem, `include` of a view, enqueue, `echo` |
| Composition | Wires pure + edge. Keep this thin. |

## Rules

1. **Extract the pure core.** If you deleted every side effect, what computation remains? That is a named function. Edge code calls it.
2. **Push effects outward.** Callee computes; caller reads/writes. Pass data in; do not hide `wp_safe_remote_post` / `get_option` / `gmdate` inside a calculator.
3. **Do not mutate inputs.** Return new arrays. Do not surprise-mutate listing rows inside a formatter.
4. **Declarative transforms.** Prefer `array_map` / `array_filter` when the body is a one-line transform. Use `foreach` when the body has real branching — loops are fine.
5. **Narrow inputs.** Pass only fields used, not the whole remote payload into a CSS-class picker.
6. **Fail as data.** Return `WP_Error`, `null`, or a result array from library functions. Do not `echo` and `die` from a helper. Do not invent a custom `Result<T,E>` framework — `WP_Error` is enough.
7. **Composition over inheritance.** No new `BaseManager`. Extend only when siblings already do.

## This plugin

| Layer | Kind |
| --- | --- |
| `wp_actualized_get_data_from_api` | edge (HTTP) |
| `wp_actualized_mapped_api_data` / `wp_actualized_price_drop_format` | pure (map + format) |
| `wp_actualized_import_properties` / insert / delete | edge (posts/meta) |
| `[actualized_thumbnail]` | edge (output) |

`final` service classes with small methods are ok. They are modules, not FP purism. `static` methods ok when they are pure or wrap a single option key with no shared mutable cache.

## Do not

- New `.pure.php` suffixes or FP libraries.
- Rewrite the plugin into a "pure architecture".
- Use FP to add layers `simple-code` would reject.
- Mutate in hot paths only if measured — default stays immutable.
- Hidden `$GLOBALS` writes. No changing `$wp_query` as a side quest inside a helper.

## Quick example

```text
BAD:  fetchListings() { res = http(); res[0].price += 1; echo template(res); }
GOOD: displayPrice(price, markup) { return price * markup; }   // pure
      fetchListings() { return http json; }                    // edge
      // composition: fetch, map displayPrice, render
```

## Test

Pure core is what you unit-test without bootstrapping WordPress. If a function needs WP to compute a string, it is in the wrong layer — unless that function **is** the WP adapter.

## Sources

simpleclaude think-functional (pure vs edge vs composition); Functional Light (immutability, map/filter, no academic purity).
