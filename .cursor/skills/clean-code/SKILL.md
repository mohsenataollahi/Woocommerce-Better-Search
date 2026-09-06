---
name: clean-code
description: >
  Write readable, maintainable code using Clean Code discipline: intention-revealing
  names, small functions, self-documenting style, SRP, Boy Scout Rule. Use whenever
  writing, changing, reviewing, or refactoring code — even if the user does not
  say "clean code".
---

# Clean code

Code is read more than written. Optimize for the next reader (human and agent).

## Names

A name answers: why it exists, what it does, how it is used.

- Intention-revealing: `isMlsActive`, not `flag` / `data` / `temp`.
- Domain language of this plugin: listing, MLS, Elementor query, hidden post type. Match existing terms.
- Functions are verbs; booleans read as predicates (`hasListings`, `canRender`).
- No noise words: drop `Data`, `Info`, `Manager`, `Processor`, `Utils`, `Helper` unless that is already the repo pattern.
- No unexplained abbreviations. `id`, `url`, `http`, `wp` OK.
- One word per concept. Do not mix `get` / `fetch` / `load` for the same kind of read in one class.

## Functions

- One responsibility. If a comment is needed to explain what it does, split it.
- Small and linear. Prefer guard clauses / early return over nested `else`.
- Max ~2 levels of nesting. Extract the inner block.
- Arguments: few. Three or more related values → small array/object (match local style).
- No flag arguments that secretly pick two functions (`process( $x, true )`). Write two names.
- No output arguments. Return the new value.
- Replace magic numbers/strings with named constants at file top or existing `ACT_*` constants.

## Comments

- Do not narrate what the code does.
- Comment **why**: API quirk, template contract, workaround, non-obvious side effect.
- Delete commented-out code. Git keeps history.
- Public shortcode / REST contract: document only what names cannot.

## Structure

- Related code together; one file, one primary reason to change.
- Hide internals; expose a small interface.
- Nested conditionals → well-named functions.
- Dead code, unused params: remove in the files you already touch.

## Errors

- Fail fast at boundaries (shortcode, REST, HTTP): validate, then work.
- Do not swallow `WP_Error` / ignore `catch`.
- User-recoverable input: clear message. Programmer / security / corrupt state: `WP_Error` or fail the request, no silent fallback that looks like success.

## Tests

When adding or fixing logic, cover the behavior change and the edge that failed. This repo has no test suite yet — do not block a small fix on inventing PHPUnit. Do add a test when the project already has one.

## Boy Scout Rule

Leave touched code slightly cleaner: rename, drop dead branch, extract one messy conditional.

Do **not** turn a small task into a plugin-wide cleanup. Out-of-scope tidy → mention, do not do.

## Checklist (before done)

- [ ] Names reveal intent in domain language
- [ ] Functions small, one job, early returns
- [ ] No magic literals
- [ ] No how-comments; why-comments only where needed
- [ ] No dead code in the diff
- [ ] Errors not swallowed

## Conflict with other quality skills

- Never "clean" by adding ceremony `simple-code` forbids (extra classes, wrappers, interfaces).
- Formatting: existing file style / WPCS wins over personal taste.

## Sources

Robert C. Martin *Clean Code* (names, functions, comments, Boy Scout); BDP conflict rules.
