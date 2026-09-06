---
name: simple-code
description: >
  Write the smallest code that solves the stated problem. Enforces KISS, YAGNI,
  Rule of Three, and anti-overengineering. Use whenever writing, changing,
  reviewing, or refactoring code — even if the user does not say "simple".
---

# Simple code

**Minimum code that solves the asked problem. Nothing speculative.**

LLMs over-abstract. Bias hard toward the boring, project-native version.

## Decision order

1. Correctness
2. Safety
3. **Smallest understandable design**
4. Scope (only what was asked)
5. Then cleanliness / modularity / functional style

If a "better" design adds files, interfaces, config, or layers the task does not need — do not ship it.

## Decision ladder

Stop at the first rung that holds.

1. **Does this need to exist?** If not required by the current request, skip it (YAGNI).
2. **Already in this plugin?** Reuse it. Match nearby style (`actCore.php`, `includes/functions.php`, `wp_actualized_*`).
3. **WordPress already does it?** Use that API (`WP_Query`, Settings API, Transients, HTTP API).
4. **Can it be a few clear lines in the existing file?** Write those lines.
5. **Nothing above held** → smallest new function/file that works.

## Rules

- No features beyond the request.
- No abstraction for one call site (no Strategy / Factory / Manager for a single case).
- No "flexibility", extra flags, or config nobody asked for.
- No error handling for states that cannot happen.
- No new dependency, queue, event, or background job unless required now.
- Do not introduce a framework, event bus, or DI container inside this plugin.
- Do not add a new file if 15 clear lines in an existing module suffice.
- If 200 lines can be 50 without losing clarity, rewrite to 50.
- Follow existing folder/naming. Do not invent Plugin Boilerplate folders in one task.

## KISS

Start with the most direct solution. Prefer a clear function over a pattern.

Avoid cleverness, hidden magic, `extract()`, variable-variables, and solving a general problem when the user asked for one concrete case.

## YAGNI

Do not add unused extension points, extra shortcodes, extra options, or "we might need this later".

YAGNI is not an excuse for missing sanitization, unreadable names, or skipping a check the current path needs.

## Rule of Three (DRY, delayed)

- 1–2 similar copies: leave them. Coincidence is not duplication.
- 3rd copy of the **same concept** that must change together: extract.
- Extract knowledge (rules, validation, calculations, constants) — not look-alike markup.

Two similar component files beat a premature helper that couples unrelated templates.

## Delete-first

Before finishing, scan the diff for unused params, speculative `if (future)` branches, wrappers with no extra behavior, and commented-out code. If it can be deleted without breaking the request, delete it.

## Self-test (before shipping)

> Would a senior on this repo call this overcomplicated?

If yes, simplify. Fallback: write the boring version first. Abstract when the second **concrete** need exists, not before.

## Conflict with other quality skills

- Beats `modular-code` and `functional-code` when those would add layers or interfaces for a single implementation.
- Loses to safety/correctness and to `wordpress` / `wordpress-best-practices` security rules.
- `clean-code` still applies: short does not mean cryptic names or magic numbers.

## Sources

Karpathy-style minimum viable code; BDP KISS/YAGNI/DRY; Rule of Three.
