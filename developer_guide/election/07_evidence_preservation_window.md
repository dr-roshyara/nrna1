# WP-7 Slice 7B — the Evidence Preservation Window

**Audience:** engineers working on audit retention, or on any value that answers a constitutional question.
**Status:** GREEN, 2026-08-01.

---

## Purpose

Constitutional **Policy 2**: an election's evidence must survive as long as a challenge can still be raised or resolved.

```
EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
```

7A gave those three terms a home. **7B expresses the arithmetic, once, as a value.** Nothing consumes it yet — the deletion guard is 7C.

## Where it fits

| Layer | File |
|---|---|
| **Domain** | `app/Contexts/Election/Domain/EvidencePreservationWindow.php` |
| **Application** | `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` |

**No DI binding was added.** The service depends only on the `EvidencePreservationDurations` interface, which 7A already binds, so the container auto-wires it. *(The smallest change that works is the one to make.)*

## Design decisions

### D1 — A value object, because Policy 2 says so

Policy 2 calls the window *"a single domain concept, not a configuration value."* It has no identity, it is immutable, and it answers one question: *is this window open at T?*

### D2 — Private constructor, named factory

The house idiom (`ChallengeRef::fromString`, `EvidenceSet::fromRefs`). Validity cannot be bypassed, and the name states a **fact**:

```php
EvidencePreservationWindow::forElection($anchor, $cw, $mad, $lsm);
```

### D3 — Business values only

```php
private function __construct(
    private readonly DateTimeImmutable $anchor,
    private readonly DateTimeImmutable $closesAt,
) {}
```

No port, no config, no model, no clock. **`isOpenAt()` takes the instant as an argument** — which is why the value needs no clock and stays trivially testable.

### D4 — Non-positive terms are rejected, never clamped

```php
if (!self::isPositive($term)) {
    throw new InvalidArgumentException(
        "The {$name} must be a positive duration. Fix the configuration -- "
        . 'this value will not substitute one.'
    );
}
```

A duration is Q-2's policy. **An unusable one is a configuration error, not an invitation to invent a workable value.** This is AP-1 expressed in the domain.

### D5 — The absent anchor lives in the service, not the value

**A window that requires an anchor can never be handed one that is missing.** *Absent anchor* is not an invalid state **of** the window — it is **the absence of the window**. Deciding what to do about it is use-case policy:

```php
$anchor = $this->anchorOf($election);

if ($anchor === null) {
    return true;   // fail closed: never expire evidence on a missing fact
}
```

## ⚠️ The one INTERIM in this slice

**Policy 2 defines the window's three durations but not its start.** The anchor is an open **Q-2** decision, so `anchorOf()` takes the first available of `results_published_at` → `end_date` → `archived_at`, marked INTERIM in the code.

**When Q-2 rules, that method collapses to one named field and nothing else changes.** It resolves *which date to measure from* — **it invents no duration**; all three still arrive through the port.

## Testing

| Test | Covers |
|---|---|
| `Unit/…/EvidencePreservationWindowTest` | factory-only construction · the three-term sum · open/closed/before-anchor · business-values-only constructor · non-positive rejected · immutable · no identity |
| `Feature/…/EvidencePreservationWindowResolutionTest` | MAD resolved through **Election's own** port · absent anchor ⇒ open |

**No test asserts which field is the anchor** — the unit tests pass it explicitly, and the fallback test uses the no-candidate case. **A keystone must not quietly settle a business value.**

## Pitfalls

- **`DateInterval::$days` is `false`** for a constructed interval — use `->d`. *(Cost WP-6 a cycle.)*
- **Don't add a duration default here.** The three values arrive through the port; the domain rejects unusable ones and substitutes nothing.
- **The legacy `Election` is untyped** — attributes arrive as `mixed`. Narrow with `getAttribute()` before use; casting blind fails PHPStan max and hides real nulls.
- **Nothing consumes the window yet.** If you are wiring it into `audit:cleanup`, you are in **7C**, which is not authorized.

---

**Traceability:** Constitutional Policy 2 · EPIC-004K §142 · **R-56** (plan) · **R-57** (mechanism corrected to Election's own port) · **R-58** (execution) · **AP-1** (fail closed) · **AP-2** (MAD keeps one home) · P7B-2 (the fallback's owner).
