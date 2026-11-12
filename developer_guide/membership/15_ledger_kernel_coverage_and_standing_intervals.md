# 15: Ledger slice 1, step 1 — coverage, in-force and suspended intervals in the kernel

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 1, test 1) · **Decisions:** INV-COV (ratified), CR-02 / D-161 · **Commit:** `36f8304a6`

## Purpose

C1 and C2 (the membership feeds Organisation reads) report, per membership, three interval sets. This step adds them to the kernel, so the feeds can be built from one pure definition:

| Interval set | Meaning | Source |
|---|---|---|
| **coverage** | the dates a person has a membership at all: the union of period lifetimes (a lapsed period is inside it) | INV-COV, C1/C2 spec §3 |
| **inForce** | the dates the person is a member in force (`IsMember`): the terms, each already clipped to its lifetime | CR-02, D-161 |
| **suspended** | the dates a restriction applies | CR-02; **always empty until Suspend is built (release-1 slice 5)** |

## Where it fits

```text
Domain   app/Contexts/Membership/Domain/Organisational/ResolvedMember.php   pure PHP, no Laravel
```

## How it works

```php
public function coverage(): IntervalSet           // ⋃ $period->lifetime()
public function inForceIntervals(): IntervalSet   // ⋃ $period->term() (term is clipped to the lifetime)
public function suspendedIntervals(): IntervalSet // IntervalSet::empty() for now
```

`IntervalSet` normalises: adjacent periods (an End and a re-admission on the same date) join into one interval.

## Use

```php
$r = $member->knownAt($tau);    // as known at τ: only facts recorded by τ (INV-14 prefix)
$r->coverage(); $r->inForceIntervals(); $r->suspendedIntervals();
```

**No membership gives empty sets.** That means "not a member" in the domain; a feed *refusal* is a different thing (guide 18 and later).

## Testing

`tests/Unit/Contexts/Membership/Domain/Organisational/CoverageAndStandingIntervalsTest.php` (10 tests):
- coverage = {t : some lifetime ∋ t}; inForce = {t : `isMember(t)`}, at every point of a range;
- lapse inside coverage; Annul restores, Void removes, Correct moves the boundary;
- `knownAt(τ)` uses only facts recorded by τ.

## Pitfalls

- **Do not compute coverage outside the kernel** (for example in Organisation). Consumers receive it through C1 / C2.
- `suspendedIntervals()` returns empty because no Suspend fact exists yet, **not** because nobody can be suspended. Slice 5 fills it.

**Traceability:** INV-COV, CR-02, D-161, D-162 · plan task 1 · PBDIGIT-343.
