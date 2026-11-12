# 22: Ledger slice 1, step 8 — C2 `CoverageKnownAt`, complete or refused

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 8, D-7, D-8; tests 11, 12, 18) · **Decisions:** CR-01 C2, D-77 (`personRefs[]`), D-160, D-161, D-162 · **Commit:** `e3f1f8b5f`

## Purpose

C2 answers: **"what did the Membership ledger know about this membership at knowledge time τ?"**
- **Complete, or refused. Never partial.**
- **A refusal is never "not a member"** (spec K-5). A consumer that gets a refusal must refuse its own act.
- C2 returns **historical knowledge**, not an eligibility decision: the consumer evaluates its own business date against the intervals (spec §6, "no `asOf`").

## Where it fits (hexagonal)

```text
Application     Organisational\CoverageKnownAt                the query service (pure PHP)
                Organisational\Ports\LedgerBoundaryPort        closing step (committed boundary)
                Organisational\Ports\MembershipSourceOfTruthPort   gate C-1 (D-160)
                Organisational\Ports\WorkingOrganisationPort   the trusted working organisation (INV-ORG-SCOPE-1)
                Organisational\UnknownMembershipId             caller error
                PublishedLanguage\CoverageKnownAtAnswer / …Item / …Refusal / CoverageInterval
Infrastructure  LedgerClockCloserBoundary                      adapter: the port is LedgerClockCloser (guide 20)
                FailClosedMembershipSourceOfTruth              default: no organisation is a ledger organisation yet
```

**No production binding exists yet.** The real source-of-truth flag and working-organisation adapter come with Platform (slice 2). Organisation's consumer comes in slice 3.

## How it works

| # | Step | Outcome on failure |
|---|---|---|
| 0 | more than **1 000** keys | `InvalidArgumentException` (page, with the same τ) |
| 1 | the working organisation must equal `organisationId` | `CROSS_ORGANISATION` |
| 2 | the source must be the ledger | `SOURCE_NOT_LEDGER` |
| 3 | **close, committed** (`LedgerBoundaryPort::closeOrRead`) | — |
| 4 | τ after the boundary, or never closed | `BOUNDARY_NOT_CLOSED` **with the boundary** |
| 5 | **only now** load each Member (new statements) and resolve with the kernel's `knownAt(τ)` | an unknown `membershipId` → `UnknownMembershipId` (whole call) |

**Per key:**
- `seq` = the highest seq recorded by τ;
- `coverage`, `inForce`, `suspended` as calendar dates `[from, to)`, with `to` null when open-ended (`suspended` is empty until slice 5).

**An unknown `personRef` → empty sets, `seq` 0:** "not a member", which is different from a refusal.

**Units:** τ and `closedThrough` are microsecond instants; intervals are dates. **For a current answer, use the boundary as τ, not the application clock** (plan D-8).

## Use

```php
$answer = $c2->byMembershipIds($organisation, [MemberId::fromString('M1')], $tau);   // or byPersonRefs(...)
if (!$answer->isComplete()) {
    // $answer->refusal(): BoundaryNotClosed | SourceNotLedger | CrossOrganisation → refuse the dependent act
}
foreach ($answer->items() as $item) { /* $item->coverage, ->inForce, ->suspended, ->seq */ }
```

`items()` throws on a refused answer, so a refusal cannot be read as an empty result by mistake.

## Testing

| Test | What it proves |
|---|---|
| `tests/Unit/…/Application/Organisational/CoverageKnownAtTest.php` (12) | <ul><li>**the CR-01 §7 scenario:** member at the cut-off, not a member on "10 December", member again after the annulment;</li><li>the contract fields; `personRefs` and an unknown person;</li><li>an unknown membership id fails the whole call;</li><li>each refusal, **and in what order the ports are called** (cross-organisation before anything; source before close; close before any load);</li><li>1 000 keys answered, 1 001 refused;</li><li>Published Language dates agree with `DateOrdinal`</li></ul> |
| `tests/Feature/…/PostgresCoverageKnownAtTest.php` (4) | <ul><li>the scenario end to end with the real repository and closer;</li><li>the default source port refuses;</li><li>**a stale boundary (busy clock row) only refuses beyond it, and answers fully within it**;</li><li>C2 never writes a fact or an event</li></ul> |
| `tests/Architecture/MembershipLedgerClockArchitectureTest.php` | C2 and the Published Language contain no `Illuminate` and no Infrastructure |

## Pitfalls

- **Never treat a refusal as empty coverage.**
- **Never read Membership tables from another context.** Consume C2 through your own port and adapter.
- **Never call C2 inside your own transaction:** its closing step refuses nesting (guide 20).
- `CoverageInterval::listFrom` duplicates `DateOrdinal::toDate`'s rule (days since 1970-01-01, UTC), so Application needs no Infrastructure. A unit test keeps the two in agreement.

**Traceability:** CR-01 C2 and §7, D-77, D-160, D-161, D-162, spec §6 / §11 · plan task 8 · PBDIGIT-343.
