# PBDIGIT-227 — Vote ids encode their creation time and order (A4)

**Type:** Defect — **constitutional (ballot secrecy)** · **Severity:** Medium–High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR); unchanged until then** (PO, 2026-10-05, roadmap §2a). |
| **Defect** | `BaseVote` uses Laravel `HasUuids`; `newUniqueId()` = `Str::orderedUuid()` (Ramsey `CombGenerator` + `TimestampFirstCombCodec`). The first 12 hex digits are `unix_seconds × 100000 + 10-µs fraction`: lexical id order is cast order and the creation time is recoverable to 10 µs — even if all timestamp columns were removed. |
| **Evidence (FACT, synthetic data)** | `BallotAnonymityCharacterizationTest::test_k7_known_gap_…` — ids sort in cast order; decoded times lie within the test's measured cast window. Sources: `app/Models/BaseVote.php:32`; `vendor/laravel/framework/src/Illuminate/Support/Str.php:1811-1829`; `vendor/ramsey/uuid/src/Generator/CombGenerator.php:109-114`. |
| **Affected boundary** | ballot identifiers |
| **Constitutional impact** | Combined with voter-side times (PBDIGIT-226) or logs, ids enable correlation. |
| **Proposed phase** | Phase 2.2 · **ADR required** (identifier strategy for ballots only: random, non-time-correlated; voter-side ids may stay). |
