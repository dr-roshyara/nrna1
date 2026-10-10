# PBDIGIT-268: Membership correction slice C-1, Annul and Void in the existing kernel (TDD-first)

**Type:** Story (domain kernel, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-10 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the PO's authorisation to prepare the C-1 story and plan (2026-10-10). **Index row:** added 2026-10-10 (H-5).

| | |
|---|---|
| **Status** | `IMPLEMENTED, AWAITING REVIEW` (2026-10-10). Plan steps 0 – 15 done, kernel only. **TDD-first except K-R1 / K-R2 / K-R3 / K-G1 / K-H1, which had no RED phase** (behaviour preceded the tests; the deviation was accepted as documented, H-2; see the acceptance review §4); **not accepted**: acceptance belongs to the PR / PO (EP-02). Review: `docs/publicdigit/reviews/2026-10-10-membership-c1-implementation-review.md` (decisions requested: F-1 adapter typing / C-4 dependency, F-2 `VoidFact` name) |
| **Contract** | `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`: **APPROVED by the PO 2026-10-10** |
| **Gate** | `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md` |
| **Plan** | `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` (approved in principle by the PO 2026-10-10, with conditions) |
| **Decisions** | D-COR-2 (PO + PR concurrence); D-COR-5 (A) (Human ARB, 2026-10-10); ARB-MEM-01 (correction before cutover) |
| **Depends on** | `PBDIGIT-258` (Slice 0 kernel) |

## Scope (PO, 2026-10-10)

"limited to Annul/Void in the existing Membership kernel."

**Excluded:**
- Correct and correction-generated consequences;
- persistence changes outside the approved slice;
- the application layer and authority changes;
- legacy replacement, cutover, live writes.

## Acceptance criteria

Contract §14.3, K-A1 … K-X2, and the Definition of Done §15.7, all **TDD-first**.

## Progress

- 2026-10-10: **PO decisions** (acceptance review H-1 … H-6): correction plan `docs/plans/20261010-1144-membership-c1-acceptance-correction-plan.md` and CP-1 approved; TDD deviation accepted as documented (H-2); F-1 → C-4 (H-3); `VoidFact` confirmed (H-4); minimal docs + index row (H-5); formal acceptance deferred to the independent review (H-6).
- 2026-10-10: **acceptance correction done (test-only)**: K-E1, the three guard-order pairs, the K-H1 property; sensitivity kit M0 to M9 all killed. Commits `9ae69b7bc`, `fccf5b778`, `e96d12a5d`. No production change. Evidence: acceptance review §11.

- 2026-10-10: implemented per the approved plan. Commits `f7c251354` (P-1 refactor) … `1e85fbfc0` (properties); one RED and one GREEN commit for each new behaviour (steps 2 – 6, 10).
- Evidence: unit 117 tests (93 before) and architecture 12, all green; exhaustive differential over 15530 decisions (H = 5, L <= 5) equal to the g6 port; properties within bounds; mutation MSI 92 % (covered 95 %), with no killable survivor in C-1 code.
- Developer guide: `developer_guide/membership/04_correction_c1_annul_void.md`.
- **Next:** PR review, then PO acceptance. C-2 / C-3 / C-4 need separate authorisation.
