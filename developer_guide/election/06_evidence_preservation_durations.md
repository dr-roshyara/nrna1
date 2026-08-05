# WP-7 Slice 7A — Evidence Preservation durations (config · port · adapter)

**Audience:** engineers touching audit retention, or adding a duration that governance owns.
**Status:** GREEN, 2026-08-01 · **Authorized by R-47** on the plan approved by **R-46**.

---

## Purpose

Constitutional **Policy 2** defines an election's **Evidence Preservation Window**:

```
EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
```

Before 7A, **only MAD existed in the codebase.** `contestation_window` and `legal_safety_margin` returned no match anywhere in `config/` or `app/` — so Policy 2's arithmetic was unimplementable. **7A gives the two missing terms an explicit, single home, and nothing else.**

**7A is deliberately inert: nothing consumes it.** The EPW value object is **7B**; the deletion guard is **7C**. Neither is authorized yet.

## Where it fits

| Layer | File |
|---|---|
| **Application (port)** | `app/Contexts/Election/Application/Port/EvidencePreservationDurations.php` |
| **Infrastructure (adapter)** | `app/Contexts/Election/Infrastructure/Config/ConfiguredEvidencePreservationDurations.php` |
| **Configuration** | `config/election_preservation.php` |
| **Wiring** | `ElectionServiceProvider::register()` |

## Design decisions

### D1 — Election declares its **own** port (R-44)

The plan originally said *"consume Adjudication's existing `AdjudicationDurations` port."* That sentence recorded **two things at once**, and only one was binding:

| | Binding? |
|---|---|
| *"MAD has exactly one home"* | 🔒 **invariant (AP-2)** — preserved absolutely |
| *"consume Adjudication's port"* | ✍️ **mechanism** — substitutable |

Importing Adjudication's interface is a **direct cross-context code dependency**, which **TP-1 forbids and Deptrac fails**. So the port belongs to the **consumer**, in the consumer's language — ordinary Hexagonal practice.

**Reading `config/adjudication.php` is not a crossing.** MAD is not Adjudication's *data*; it is **Q-2's policy**, and both contexts are downstream of *governance*, not of each other.

### D2 — Fail closed, always (AP-1)

A missing, non-numeric or non-positive duration **throws**. No default, no clamp, no substitute.

```php
if ($days < 1) {
    throw new RuntimeException(sprintf(
        '%s.%s resolved to %d; a Policy 2 duration must be at least one day. Fix the '
        . 'configuration -- this adapter will not choose one.',
        $namespace, $key, $days,
    ));
}
```

**This is not defensive style — it is ownership.** Q-2 owns the numbers; an adapter that substitutes one has *decided business policy*. That defect (`max(1, $days)`) shipped once and passed **all four gates**.

### D3 — MAD is consumed, never copied (AP-2)

`config/election_preservation.php` carries **no MAD key**, and says so:

> *⛔ THE MAXIMUM ADJUDICATION DURATION IS DELIBERATELY ABSENT FROM THIS FILE.*

The adapter reads `adjudication.maximum_adjudication_duration_days` — MAD's one home.

### D4 — Mirror the precedent, invent nothing

`ConfiguredEvidencePreservationDurations` differs from `ConfiguredAdjudicationDurations` **only in vocabulary, owned parameters and config keys.** Same precedence, same rejection, same construction. **A second resolution algorithm would be a second thing to get wrong.**

## How it works

**Precedence: organisation → election type → default.** The narrower scope wins.

```php
$days = $this->override("{$namespace}.per_organisation.{$organisationId}", $key, $organisationId)
    ?? $this->override("{$namespace}.per_election_type.{$electionType}", $key, $electionType)
    ?? $this->configuredDefault($namespace, $key);
```

`$namespace` is the only asymmetry: `election_preservation` for CW/LSM, `adjudication` for MAD.

## How to use it

```php
public function __construct(
    private readonly EvidencePreservationDurations $durations,
) {}

$cw  = $this->durations->contestationWindow($election->type, $organisationId);
$mad = $this->durations->maximumAdjudicationDuration($election->type, $organisationId);
$lsm = $this->durations->legalSafetyMargin($election->type, $organisationId);
```

**Inject the port, never the adapter** — that is what keeps Q-2's ownership visible.

## How to extend it

**Adding a governance-owned duration:** declare the key in the owning context's config with an `INTERIM` comment naming its pending authority · add the accessor to that context's port · resolve it through the shared private helper · **add the adapter to `DurationPolicyOwnershipTest::DURATION_ADAPTERS`** — an unlisted adapter is an unguarded one.

**Never:** add a second home for a value that has one · default or clamp in the adapter · read `env()` outside a config file.

## Testing

| Test | Asserts |
|---|---|
| `EvidencePreservationDurationsTest` (8) | per-type resolution · organisation override · **fail closed** on missing/non-numeric/non-positive · MAD consumed not copied · **R-D1** both adapters agree on MAD |
| `DurationPolicyOwnershipTest` (3) | **C-1** — no clamp or substitution in any duration adapter · MAD has exactly one home · **the detector itself discriminates** |

**Why C-1 tests its own detector:** a guard asserting an absence is green from birth and proves nothing. By the platform's Methodological Fitness Rule, *a criterion that never rejects a candidate is presumed ceremonial* — so C-1c feeds it AP-1's real shape and requires rejection, and feeds it fail-closed code and requires acceptance. **A comparison is not a clamp.**

## Pitfalls

- **`DateInterval::$days` is `false`** for a constructed interval. Use **`->d`**. *(Cost WP-6 a debugging cycle.)*
- **Do not "helpfully" default a missing duration.** The throw is the feature.
- **`?? 60` is the same defect as `max(1, $days)`** — both invent a business value. C-1 rejects both.
- **C-1's reach is a hand-maintained list.** Known limitation; the ARB classifies the guard as *good enough for WP-7, not the final solution* — it should evolve toward an **AST-based** fitness function.
- **7A consumes nothing.** If you find yourself wiring it into `audit:cleanup`, you are in **7C**, which is not authorized.

---

**Traceability:** Constitutional Policy 2 (`EPIC-003 §THE FOUR DECISIONS` №2) · EPIC-004K §142 · rulings **R-44** (A-1 · consumer-side port) · **R-46** (plan) · **R-47** (slice 7A) · WP-6 findings **AP-1** / **AP-2** · **R-D1** · precedent `ConfiguredAdjudicationDurations` · RED and GREEN reports under `engineering/verification/reports/2026-08-01-wp7-slice-7a-*.md`.
