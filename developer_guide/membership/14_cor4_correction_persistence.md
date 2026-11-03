# 14: Correction slice C-4, persisting Annul, Void and Correct

**Story:** `PBDIGIT-329` · **Plan:** `docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md` (approved 2026-11-03 with Q-1…Q-7) · **Contract:** correction contract §8; Slice 1b contract, "Amendment (2026-11-03)" · **Namespaces:** `…\Domain\Organisational`, `…\Application\Organisational\Ports`, `…\Infrastructure\Organisational`

## Purpose

C-1 and C-2 let the kernel decide corrections. C-4 makes them survive storage. Every fact kind (Admit, End, Annul, Void, Correct) is stored and reloaded **exactly as decided**, on PostgreSQL and on the in-memory double:
- a Correct's trims are stored as recorded and never recomputed;
- a damaged stored history is refused on load (fail closed) instead of being resolved into a different membership truth.

C-4 adds no application command, authority check or event emission (COR-3). **Its migration runs only on the throwaway test cluster** (release gate, below).

## Where it fits

```text
Domain          Member::fromLog → requireStructurallyValid()      load validation, every load path (Q-3)
Application     Ports\StorableValidTime                           R-1a-6 also for Correct (Q-7)
Infrastructure  PostgresMemberRepository, ConsequencesJson,       encode / decode of five kinds; trims as jsonb;
                StoredMemberLogInvalid                             load failures (Q-6)
Database        2026_11_03_120000_extend_org_membership_facts_…   target_fact_id, nullable date, consequences, CHECKs
Test double     tests/Doubles/InMemoryMemberRepository            same contract; strictly increasing recordedAt (Q-2)
```

## Key files

| File | Role |
|---|---|
| `Domain/Organisational/Member.php` | `requireStructurallyValid()`, called from the constructor (so from `fromLog` too) |
| `Application/Organisational/Ports/StorableValidTime.php` | `admits()` checks a Correct's value and every bound of its consequences |
| `Infrastructure/Organisational/PostgresMemberRepository.php` | `encode()` / `decode()` for five kinds; `load()` wraps failures; `recorded_at = max(clock, last + 1 µs)` |
| `Infrastructure/Organisational/ConsequencesJson.php` | the jsonb format of a Correct's consequences, strict decode, `G2_VERSION = 1` |
| `Infrastructure/Organisational/StoredMemberLogInvalid.php` | the infrastructure failure for an unloadable history |
| `database/migrations/2026_11_03_120000_extend_org_membership_facts_for_corrections.php` | the additive schema change |

## Design decisions (PO, 2026-11-03: Q-1…Q-7 as recommended)

- **Q-1 (D-COR-4):** `period_fact_id` is renamed `target_fact_id`, one reference meaning "the fact this fact refers to". An End's period, and the target of an Annul, Void or Correct, all go there. The same-Member FK is renamed with it.
- **Q-2 (V-5):** `recordedAt` strictly increases per Member, so `knownAt` can observe the state between any two facts.
- **Q-3 (V-1, V-14, V-15):** structural validation lives in the kernel, so the adapter, the double and `fromLog` share **one rule**. It checks structure only; G\* and the guards that depend on time or authority are **not** re-run, because a stored history is the record (R-12).
- **Q-4:** Annul and Void store a NULL date; there is no invented valid time.
- **Q-5:** a Correct's consequences go in `consequences jsonb` on its own row, with `g2_version`. A trim never exists apart from its cause (FP §12.4 option C). They are absolute dates, clipped at resolution (P-2 as clarified at the COR-2 acceptance).
- **Q-6:** an unloadable history is an **infrastructure failure** (`StoredMemberLogInvalid`), not a user refusal.
- **Q-7 (V-13):** every date written must be storable (0001-01-01 … 9999-12-31), including trim bounds.

## How it works

### Load validation (`Member::requireStructurallyValid`)

In seq order, each fact may refer only to **earlier** facts of the same log:

```php
if ($fact instanceof End) {
    self::requireEarlier($earlier, $fact->period()->admitFactId(), $id, Admit::class);
}
if ($fact instanceof ReversalFact) {          // Annul, Void
    $target = self::requireEarlier($earlier, $fact->target(), $id, Fact::class);
    // Annul of a Void → refused (N-3); Annul without decision provenance → refused (R-7)
}
if ($fact instanceof Correct) {               // targets an Admit, End or Correct; one consequence per earlier Admit
    …
}
```

That also rules out a fact targeting itself, which would make `Resolution::rootOf` recurse without end (V-14).

### Writing a fact (`encode`)

`encode()` branches on the kind **before** reading any date (ACC-260 K-2: `Fact` has no `effectiveAt()`). Every row sets every kind-specific column, so the five-branch CHECK decides:

```php
if ($fact instanceof Correct) {
    return ['kind' => 'correct', 'effective_at' => DateOrdinal::toDate($fact->value()), 'target_fact_id' => $fact->target()->value(),
        'consequences' => ConsequencesJson::encode($fact->consequences()), 'g2_version' => ConsequencesJson::G2_VERSION] + $common + $none;
}
```

### The consequences format

```json
[{"period": "A1", "removed": [["2026-01-01", "2026-02-01"], ["2026-03-01", null]]}]
```

`null` means ∞. `decode()` is strict:
- an unknown `g2_version`, a missing or extra key, or a wrong type → `InvalidArgumentException`;
- a malformed date → `UnexpectedValueException`;
- an empty trim → refused by `TrimConsequence`.

Keys are compared as a set, because PostgreSQL `jsonb` does not keep key order.

### Reading a history (`load`)

```php
try {
    … decode every row; return Member::fromLog(…);
} catch (\InvalidArgumentException | \UnexpectedValueException | \JsonException | \UnhandledMatchError | \ValueError $e) {
    throw StoredMemberLogInvalid::because($tenant->value(), $id->value(), $e);
}
```

Only the decoders' and the kernel's validation failures are wrapped; any other `\Error` (a programming defect) propagates.

### The schema

The table and its CHECKs are in the Slice 1b contract, "Amendment (2026-11-03)". In short:
- **admit / end / correct** carry a date; **annul / void** do not;
- **end / annul / void / correct** carry a target;
- only **correct** carries `consequences` (a jsonb array) and `g2_version` (≥ 1);
- no fact targets itself.

`down()` refuses while any correction row exists: dropping their columns would destroy history.

## How to use or extend

- **A new fact kind:**
  - extend the CHECK (a new additive migration), `encode()`, `decode()` and `requireStructurallyValid()`;
  - if it carries a `TimePoint` beyond `effectiveAt()`, also extend `StorableValidTime`. The architecture test `test_every_fact_carrying_a_time_point_is_handled_by_storable_valid_time` fails until you do.
- **A new consequence format:** raise `G2_VERSION` and keep decoding the old one; never recompute old consequences on load.
- **COR-3:**
  - bind handlers through `DecideAndAppend`; the race tests show that its re-decide loop recomputes g2 on conflict;
  - add the `MembershipAct` correction acts. The race tests use a placeholder act today.

## Testing

| Test | What it pins |
|---|---|
| `MemberLogValidationTest` (18) | the 17 structurally invalid logs are refused; a valid log of every kind loads, guards not re-run |
| `StorableValidTimeTest` (8), the architecture property test | R-1a-6 for Correct and its trim bounds; no dated fact escapes `StorableValidTime` |
| `MemberRepositoryContract` (double and PostgreSQL) | unstorable corrections refused; strictly increasing `recordedAt`; every-kind round trip (incl. a two-period Correct); `appliedCommand` for new kinds; the **reload differential** over every bounded C-2 history (double: all; PostgreSQL: every 10th) |
| `PostgresCorrectionSchemaTest` (19) | columns, every CHECK branch, the same-Member target FK, `down()` refusal and round trip |
| `PostgresCorrectionDecodeTest` (7) | damaged stored histories → `StoredMemberLogInvalid` |
| `PostgresCorrectionRaceTest` (3 × 8 rounds × 4 processes) | Annul vs Correct; two Corrects; Correct vs End (never both); stored consequences = g2 on the stored prefix |
| `ConsequencesJsonTest` (20) | the jsonb format, round trip, key order, every malformed shape, including the three that mutation testing found (a keyed object of valid trims, a scalar, a pair given as an object) |

**Database-free:**

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Application/Organisational
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Infrastructure/Organisational
```

**PostgreSQL:** run from a clean worktree, never the main tree (cached config; guide 11):

```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh
```

## Pitfalls

- **Never run this migration on a persistent database.** It joins the release gate: OQ-01, the §10.2 preflight, `lc_messages` and ARB-MEM-01.
- **The PostgreSQL suite does not wrap tests in transactions** (F-5: `append` refuses inside one). Rows from earlier tests stay committed, so a test that needs an empty table must create that state inside its own transaction and roll it back. `PostgresCorrectionSchemaTest`'s `down()` test does.
- **Do not catch `\Throwable` in `load()`.** A programming defect must not be reported as damaged data.
- **The double is near-trivially right** (it stores objects). Only the PostgreSQL runs prove the encoding.
- **"The target is earlier" is not a database constraint.** It holds only because every load goes through `Member::fromLog`.

## Traceability

PBDIGIT-329 · plan Q-1…Q-7, AC-1…AC-11 · correction contract §8 · Slice 1b contract amendment 2026-11-03 · roadmap V-1, V-4, V-5, V-13, V-14, V-15, G-A2, G-A3 · ACC-260 K-2 · FP §12.4, §16.10 · commits `6da2594b3` (step 1), `5b134f033` (2), `5572ce595` (3), `8dfc363b6` (4), `144f1e947` (5), `5304aaab7` (6), `96d71183e` (7), step 8 (this guide). Anonymity (ADR-T11) is untouched: membership facts carry no vote data.
