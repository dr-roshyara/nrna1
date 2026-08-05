# 09 — The Evidence Anchor Resolver (WP-7B-R1)

**Slice:** WP-7B-R1 — Interim Anchor Extraction · **R-60** opened it · **R-70** authorized it
**Constraint that shaped everything below:** **no externally observable behaviour changes.**

---

## Purpose

Constitutional Policy 2 defines an Evidence Preservation Window as

```
EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
```

**It fixes the three durations. It does not fix the date they are measured from.** Which date anchors the window is an **open Q-2 decision**.

Until 7B-R1 that interim answer lived in a **private method** — `ResolvesEvidencePreservationWindow::anchorOf()`. A provisional rule in a private method can only be replaced by **editing the service**. Behind a port it is replaced by **swapping a binding**.

> **That is the whole slice. It changes no behaviour and buys one thing: when Q-2 rules, the ruling touches a container binding instead of retention logic.**

## Where it fits

| Layer | Class |
|---|---|
| **Application / Port** | `EvidenceAnchorResolver` — `anchorFor(Election): ?DateTimeImmutable` |
| **Infrastructure / Config** | `TemporaryDefaultAnchorResolver` — the interim rule |
| **Application / Service** | `ResolvesEvidencePreservationWindow` — now injected with the port |
| **Infrastructure / Providers** | `ElectionServiceProvider` — the binding |

**The shape is deliberately identical to its one-slice-older sibling**, `EvidencePreservationDurations` → `ConfiguredEvidencePreservationDurations`. Same seam, same layers, same provider. Nothing new was invented for this.

## How it works

```php
// Application — the service asks, and never decides.
public function isOpenFor(Election $election, DateTimeImmutable $at): bool
{
    $anchor = $this->anchors->anchorFor($election);

    if ($anchor === null) {
        return true;          // fail closed — see "the rule that stayed"
    }

    return EvidencePreservationWindow::forElection(
        $anchor,
        $this->durations->contestationWindow($electionType, $organisationId),
        $this->durations->maximumAdjudicationDuration($electionType, $organisationId),
        $this->durations->legalSafetyMargin($electionType, $organisationId),
    )->isOpenAt($at);
}
```

```php
// Infrastructure — the interim rule, MOVED not revised.
private const CANDIDATES = ['results_published_at', 'end_date', 'archived_at'];
```

**Narrowest to broadest, first match wins, no candidate ⇒ `null`.** Byte-for-byte the rule that was there before.

## The rule that stayed behind — and why

**`null` anchor ⇒ the window is reported OPEN.** That rule did **not** move into the resolver, and the split is the interesting design point:

- **The resolver reports what it found.** Absence is an answer, not a failure; it substitutes nothing.
- **The service decides what absence means.** That is use-case policy (**P7B-2**), and it **fails closed** (**AP-1**) — evidence must never be treated as expired on the strength of a missing fact.

**Put the fallback in the resolver and every future resolver has to remember to fail closed. Leave it in the service and the policy is stated once, where the use case lives.**

## How to apply Q-2 when it rules

1. Write the replacement, e.g. `RuledEvidenceAnchorResolver`, reading the one named field Q-2 chose.
2. Change **one line** in `ElectionServiceProvider`.
3. Delete `TemporaryDefaultAnchorResolver`.

**No service, no value object, no retention logic, and no test of the deletion guard is touched.** **The class is named `Temporary` because it is expected to be deleted, not extended.**

## Testing

| Suite | Role |
|---|---|
| `tests/Feature/Contexts/Election/EvidenceAnchorResolutionTest.php` | **the new seam** — binding · delegation · candidate order · absence |
| `EvidencePreservationWindowResolutionTest` · `EvidencePreservationDurationsTest` · `EvidencePreservationWindowTest` · `tests/Feature/Audit/AuditCleanupTest.php` | **the behaviour-preservation harness — all four must stay green UNMODIFIED (R-70)** |

**The discriminating test is `r1k2`.** A stub resolver returns an anchor from 2020 while the election's own `end_date` is 2026; if the service still consulted its former private method the window would be open, and it must be closed. **Without it, the binding test would prove only that a class exists somewhere unused.**

> ⚠️ **`composer merge-gate` does not execute `tests/Feature/Audit/`.** The gate's `GreenfieldCore` suite covers `tests/Unit/Contexts/*`, `tests/Feature/Contexts/*` and `tests/Replay`. **`AuditCleanupTest` is part of this slice's harness, so run it directly:**
>
> ```bash
> php artisan test tests/Feature/Audit/AuditCleanupTest.php
> ```

## Pitfalls

- **Do not decide the anchor here.** Adding a fourth candidate, or reordering the three, is a **Q-2 act**, not a refactor.
- **Do not move the fail-closed rule into a resolver.** It is use-case policy; see above.
- **Do not let the resolver invent a date.** `null` is the correct answer when no candidate exists — a substituted date silently shortens a legally-mandated retention period.
- **Do not read `type`/`organisation_id` here.** Those narrow the *durations*, not the anchor, and they already have a home.

---

**Traceability:** **R-60** (opened) · **R-70** (authorized; behaviour-preservation is the acceptance boundary) · **R-59** (Slice 7B) · **R-65 / R-66** (Slice 7C, the deletion guard that consumes this answer) · **Constitutional Policy 2** · **AP-1** (fail closed) · **AP-2** (one home per parameter) · **P7B-2** (the fallback's seat) · **Q-2** (open — the anchor's value) · `engineering/verification/reports/2026-08-02-wp7b-r1-authorization-readiness.md`.
