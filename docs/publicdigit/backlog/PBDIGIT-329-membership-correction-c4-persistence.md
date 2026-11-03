# PBDIGIT-329: Membership correction slice C-4 (COR-4): persisting Annul, Void and Correct (TDD-first)

**Type:** Story (persistence, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-03 · **Raised by:** the PO's instruction "prepare the COR-4 story and plan" (2026-11-03). Roadmap W4, second step (COR-2 → **COR-4** → COR-3).

| | |
|---|---|
| **Status** | **`ACCEPTED`** (PO, 2026-11-03: "accept PUBDIGIT-329", recorded as PBDIGIT-329), with the documented residual risks. It closes ACC-260 K-2 and roadmap V-1, V-4, V-5, V-13…V-15 and G-A2. No migration on any persistent database (release gate); ARB-MEM-01 still needs COR-3. Record §4.1. |
| *(history)* | `REVIEWED — CONDITIONS CLOSED, AWAITING PO ACCEPTANCE` (2026-11-03). The independent review recommended ACCEPT WITH CONDITIONS C-1…C-4 and found no path that lets a damaged history resolve. PO: option (A), C-2 as recommended, plus F-3 and F-7. The conditions are closed. Record: `docs/publicdigit/reviews/2026-11-03-pbdigit329-cor4-independent-review-and-acceptance.md`. |
| *(history)* | `IMPLEMENTED — AWAITING INDEPENDENT REVIEW` (2026-11-03): steps 1–8 done (commits `6da2594b3` … `96d71183e`, plus step 8). PostgreSQL only in the throwaway container; the migration is not rolled out (release gate). Evidence: plan §10 and `docs/publicdigit/membership/evidence/2026-11-03-pbdigit329-cor4/`. **Not accepted.** *(Was: `APPROVED` (PO, 2026-11-03), plan approved with Q-1…Q-7.)* |
| **Plan** | `docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md` |
| **Contract** | `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` §8 (persistence obligations), §10 (C-4 row), §14.5 (C-4 criteria and C-AMEND-1 F-1) |
| **Depends on** | `PBDIGIT-327` COR-2 (accepted 2026-11-03); `PBDIGIT-264` (accepted 2026-11-03); `PBDIGIT-324` (the isolated PostgreSQL container, accepted) |
| **Entry criteria carried here** | V-1, V-4, V-5, V-13, V-14, V-15 (roadmap verification table §3.2); ACC-260 condition **K-2** (C-1 F-1: `encode()` typing); D-COR-4 |
| **Gates** | ARB-MEM-01: correction COR-1…COR-4 accepted before any cutover, migration or live write. **The ledger-migration release gate stays closed**, and COR-4's new migration joins it |

## Business purpose

The kernel can now decide corrections: Annul, Void and Correct (C-1, C-2). They cannot yet be **stored**. The database admits only Admit and End (V-4), so no correction can survive a reload.

COR-4 makes every fact kind storable and reloadable **exactly as decided**. A Correct's trims are kept as recorded (never recomputed). A damaged or tampered stored history is **refused on load**, not silently resolved into a different membership truth (V-1).

Without COR-4, the application commands (COR-3) cannot be wired, and the correction capability cannot reach users.

## Scope

- **Schema:** one **additive** migration on `org_membership_facts`:
  - the five fact kinds;
  - the target reference (D-COR-4) with the same-Member FK;
  - no date for Annul / Void;
  - `consequences jsonb` + `g2_version` for Correct;
  - kind-specific CHECKs.
- **Adapter** `PostgresMemberRepository`:
  - encode / decode of all fact kinds, typed per kind (K-2);
  - strictly increasing `recordedAt` (V-5, if Q-2 = a).
- **Test double** `InMemoryMemberRepository`: the same behaviour, by the shared contract tests.
- **Load validation** (V-1, V-14, V-15): a structurally invalid stored history is refused on every load path.
- **`StorableValidTime`** (V-13): the corrected date and the trim bounds.
- **Tests:**
  - round trips, including a Correct trimming two periods;
  - CHECK / FK refusals;
  - decode-failure tests;
  - PostgreSQL race tests (Annul vs Correct; two Corrects; Correct vs End);
  - all on the pinned PBDIGIT-324 container, from a clean worktree.

## Not in scope

- Application commands, handlers, authority and idempotency fingerprints (COR-3; CQ-3, CQ-9).
- Event storage and emission (COR-3; G-A1).
- The `end_kind` CHECK and the legacy ⇔ attested load check (V-11, Slice 2b).
- OQ-C2-1 / OQ-C2-2.
- **Running any migration on any persistent database.**

## Acceptance criteria

Authoritative in the plan §6.
