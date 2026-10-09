# Plan: Membership Slice 1b, PostgreSQL adapter, TDD (PBDIGIT-260)

**Status:** ✅ **IMPLEMENTED, GREEN** (review `docs/publicdigit/reviews/2026-10-09-membership-slice1b-implementation-review.md`) · approved by the PO, 2026-10-09 · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md`

## Steps
- [x] 1. Refactor: the contract suite becomes a trait with a tenant hook. The in-memory results must be unchanged (14 green before and after)
- [x] 2. RED: `PostgresMemberRepositoryTest` uses the trait (the adapter does not exist)
- [x] 3. GREEN: migration + adapter + mappings (DateOrdinal, InstantMicros, ProvenanceJson) + fail-fast
- [x] 4. RED → GREEN: database-specific tests T2–T10
- [x] 5. T11: handlers + adapter vs the reference model
- [x] 6. T12 (optional): a real race with forked processes, if `pcntl` is available
- [x] 7. T13: architecture
- [x] 8. regression vs the baseline `a79115054`; review; developer guide `03`; STOP

## Progress
- 2026-10-09: plan created; contract approved; migration wording clarified.
- 2026-10-09: `896f91bc8`…`eb29153c7`.
  - T1–T13 green.
  - The real race (T12) found an IDEM-3 defect: interleaving A fixed in the adapter; interleaving B fixed in `DecideAndAppend` (PO-authorised).
  - The regression set of failing tests equals the baseline.
