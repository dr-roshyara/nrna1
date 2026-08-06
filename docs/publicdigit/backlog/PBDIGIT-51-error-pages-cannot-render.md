# PBDIGIT-51 — Error pages cannot render, so every 500 is opaque

**Type:** Defect (environment / dependency) · **Epic:** cross-cutting · **Created:** 2026-08-06
**Found by:** `/vote/create` returning 500 while diagnosing `PBDIGIT-47`

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | None directly. **The cost is diagnostic: every 500 hides its own cause**, which lengthens every investigation |

---

## The evidence

```
GET /vote/create  ->  500

include(.../vendor/composer/../symfony/config/ConfigCache.php): Failed to open stream
Uncaught ErrorException: include(.../symfony/config/ConfigCache.php)
```

**`symfony/config` is not installed.** The detailed error-page renderer needs it, so **when any request fails, the failure handler fails too** — and the logged exception is the *renderer's*, not the application's.

**Independently confirmed earlier in the same session:** setting `APP_DEBUG=true` in the testing environment produced the identical `symfony/config` error and a 1.4 MB response body, masking the real exception.

## Why it matters more than it looks

**The reported `/vote/create` 500 is still undiagnosed** — not because it is hard, but because **its cause is unreadable**. Any 500 on this environment reports the renderer's failure instead of the application's.

> **A broken error path costs more than the errors it hides: it converts every future 500 into an investigation.**

## What is NOT established

* **Whether `/vote/create` has a real defect.** It may be a genuine bug, or a legitimate redirect-turned-error. **Unknown, and it stays unknown until the renderer works.**
* **Whether production is affected.** Only this environment was observed. **If production runs with `APP_DEBUG=false` and a package set that includes `symfony/config`, it may be fine** — untested.

## Acceptance criteria

* [ ] `composer require --dev symfony/config` (or whichever package provides it), then confirm a deliberately failing route renders a readable error.
* [ ] **Re-diagnose `/vote/create` once errors are legible** — and file whatever it turns out to be as its own ticket.
* [ ] Establish whether the production dependency set has the same gap.
* [ ] Consider whether `composer install` should fail loudly when the error renderer's dependencies are absent — **a broken error path should not be a silent state.**

---

**Traceability:** `/vote/create` 500 observed 2026-08-06 (dev, port 8000) · the identical failure under `APP_DEBUG=true` in the testing environment earlier the same day · `PBDIGIT-47` (the investigation that surfaced it)
