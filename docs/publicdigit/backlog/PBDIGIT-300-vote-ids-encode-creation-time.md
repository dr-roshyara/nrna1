# PBDIGIT-300: Vote and result identifiers encode their creation time (anonymity gap A4)

**Type:** Security / anonymity (ADR-T11) · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from the Laravel 12 upgrade (ADR `ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md`, finding F-1). **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the PO 2026-10-16, **priority raised** |
| **Classification** | **observed:** characterization test K7 (gap A4) passes on Laravel 11 and Laravel 12 |
| **Scope** | design decision first (ADR), then implementation in an authorised slice |

## Finding
- `BaseVote` and `BaseResult` use `HasUuids`. Their ids are **time-ordered and encode the creation time**:
  - **Laravel 11:** `Str::orderedUuid()`, at 10-µs resolution.
  - **Laravel 12:** UUIDv7, at **millisecond** resolution.
- Evidence: `tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php` K7. Lexical id order equals cast order, and the creation time decodes from the first 48 bits.
- Combined with any voter-side timestamp (see gap A2 / K6: `codes.code_to_save_vote_used_at` against `votes.cast_at`), a time-ordered id narrows or completes voter → ballot linkage. The id itself is a correlation handle that survives copies and exports of the votes table.

## Proposed direction (nothing decided)
1. **Write an ADR** for ballot identifier design: random UUIDv4 for `votes` and `results` (e.g. a `newUniqueId()` override, or `HasVersion4Uuids`), or an opaque random identifier. Weigh index locality on PostgreSQL against anonymity.
2. **Decide the treatment of existing ids.** Rewriting primary keys of cast ballots is itself a sensitive operation, so it needs explicit governance.
3. **Address gap A2 (timestamp correlation)** in the same ADR. Changing only the id does not remove the timestamp link.

## Acceptance criteria
1. An approved ADR names the identifier scheme and the treatment of existing ballots.
2. K7 is inverted into a guard: new vote and result ids carry no recoverable time and do not sort in cast order.
3. No regression in the anonymity characterization and boundary suites.
