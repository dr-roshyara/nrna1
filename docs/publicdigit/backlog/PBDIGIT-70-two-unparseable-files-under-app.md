# PBDIGIT-70 — Two files under `app/` cannot be parsed by PHP

**Type:** Repository hygiene · **Epic:** cross-cutting · **Created:** 2026-08-12
**Found by:** IERVP `D-ENT-1` consequences package — **incidentally, while probing for a `Q3` precedent**
**Evidence:** [`../reviews/2026-08-12-d-ent-1-approval-and-consequences-package.md`](../reviews/2026-08-12-d-ent-1-approval-and-consequences-package.md)
**Status:** `OPEN — not fixed by the finding commission` (it was governance-scoped; this is code)

| | |
|---|---|
| **Customer impact** | **None observed.** Both files are unreachable at runtime |
| **Real cost** | **Any full-autoload or static-analysis pass over `app/` fails on them**, so tooling that would sweep the whole tree cannot run clean |
| **Confidence** | **High** — `php -l` on every `.php` file under `app/`: exactly 2 failures |

---

## The two files

| File | Parse error | Reachable? |
|---|---|---|
| `app/Domain/Election/Models/ElectionUser.php` | **duplicate `<?php` at line 11** — a doc comment, then a `// path` comment, then a second opening tag | **No production callers** (`grep` over `app/`, `routes/`) |
| `app/Http/Middleware/VoterSlugStep.php` | **stray backtick at line 279** | **Not registered** in any middleware stack (absent from `route:list`, `bootstrap/`, `Kernel`) |

```
$ php -l app/Domain/Election/Models/ElectionUser.php
PHP Parse error: syntax error, unexpected token "<", expecting end of file ... on line 11

$ php -l app/Http/Middleware/VoterSlugStep.php
PHP Parse error: syntax error, unexpected token "`" ... on line 279
```

## ⚠️ Why `ElectionUser` matters beyond hygiene

It is a **legacy flat voter model** (`is_voter`, `nrna_id`, `lcc`, `facebook_id`, `approvedBy`, `suspendedBy`) carrying `suspended_at`, `suspension_reason`, a `suspend()` method and `canVote()` — i.e. **voter-level suspension in exactly the overlay style that the open `Q3` architecture decision is considering.**

> **It is therefore easy to mistake for a precedent. It is not one: it is dead, unparseable, and built on `is_voter`, a flag `PBDIGIT-35` established exists in no database.**

**Recorded so a future `Q3` design does not cite it as prior art.**

## `VoterSlugStep` — a second, closer hazard

An unregistered **middleware** whose name sits one step from the live `EnsureVoterStepOrder` on every real voting route. **If it were ever wired up in the belief that it is the step-order guard, the request would fail to parse rather than fail safe.** That risk is latent, not present.

## Acceptance criteria

* [ ] **Every file under `app/` parses** — `php -l` clean across the tree.
* [ ] **Each file is either repaired or removed on an explicit decision** — repairing dead legacy code and deleting it are different choices, and **which one applies is not engineering's to assume** (`ElectionUser` may still hold reference value for the legacy migration).
* [ ] **A syntax gate exists** so this cannot regress silently.

## Explicit non-goals

* ❌ **Not deciding delete-vs-repair** — that is a disposition call, and `ElectionUser` overlaps the legacy-migration work (`PBDIGIT-58`).
* ❌ **Not wiring `VoterSlugStep` up** — nothing established that it should exist at all.
* ❌ **Not treating either file as `Q3` prior art.**

## Related

* **`Q3` / `D-ENT-1b`** — the open exercisability decision this was found while researching.
* **`PBDIGIT-35`** — the retired `is_voter`/`can_vote` flags `ElectionUser` is built on.
* **`PBDIGIT-58`** — legacy election-state migration; `ElectionUser`'s disposition likely belongs with it.
* **`PBDIGIT-51`** — error pages cannot render; a related class of "tooling cannot run clean".

---

**Traceability:** `app/Domain/Election/Models/ElectionUser.php:1-11,118-190` · `app/Http/Middleware/VoterSlugStep.php:279` · measured 2026-08-12: `php -l` over every `.php` file under `app/` → exactly **2** unparseable.
