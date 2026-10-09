# Plan: Membership Slice 1b, PostgreSQL adapter, TDD (PBDIGIT-260)

**Status:** 🟢 APPROVED (PO, 2026-10-09) · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md`

## Steps
- [ ] 1. Refactor: the contract suite becomes a trait with a tenant hook. The in-memory results must be unchanged (14 green before and after)
- [ ] 2. RED: `PostgresMemberRepositoryTest` uses the trait (the adapter does not exist)
- [ ] 3. GREEN: migration + adapter + mappings (DateOrdinal, InstantMicros, ProvenanceJson) + fail-fast
- [ ] 4. RED → GREEN: database-specific tests T2–T10
- [ ] 5. T11: handlers + adapter vs the reference model
- [ ] 6. T12 (optional): a real race with forked processes, if `pcntl` is available
- [ ] 7. T13: architecture
- [ ] 8. regression vs the baseline `a79115054`; review; developer guide `03`; STOP

## Progress
- 2026-10-09: plan created; contract approved; migration wording clarified.
