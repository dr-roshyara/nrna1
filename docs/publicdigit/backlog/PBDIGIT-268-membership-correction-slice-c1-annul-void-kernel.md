# PBDIGIT-268: Membership correction slice C-1, Annul and Void in the existing kernel (TDD-first)

**Type:** Story (domain kernel, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-10 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the PO's authorisation to prepare the C-1 story and plan (2026-10-10). **Index row:** pending (`backlog/README.md` carries other sessions' uncommitted edits).

| | |
|---|---|
| **Status** | `IN PROGRESS`. Plan approved in principle by the PO 2026-10-10 (conditions incorporated); gate re-evaluated 2026-10-10: **all 7 conditions closed, C-1 eligible**; TDD-first |
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
