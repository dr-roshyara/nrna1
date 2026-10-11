# PBDIGIT-264: Membership Slice 1b follow-ups from the acceptance review (far-date defect, race-test gaps, adapter parity, FK)

**Type:** Story (defect + hardening, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). **Index row:** added 2026-10-09.

| | |
|---|---|
| **Status** | `IN PROGRESS` · F-1 (with Option E) and F-3 implemented and tested on the test double; PostgreSQL unverified (Part 2 held); F-2, F-4, F-5 held; not accepted *(synced 2026-10-11; see "Progress")* |
| **Found in** | `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md` §2 (an independent review of `PBDIGIT-260`) |
| **Depends on** | `PBDIGIT-260` acceptance decision |
| **Business decisions needed** | none (F-3 needs a port-contract clarification by the Principal Reviewer) |

## Items

| ID | Class | What | Acceptance (RED first) |
|---|---|---|---|
| F-1 | **Defect** | `DateOrdinal::toDate` writes ordinal ≥ 2932897 as `10000-01-01`; `fromDate` cannot read it back, so the Member becomes permanently unloadable. Reproduced: `2932897 -> 10000-01-01 -> UnexpectedValueException`. | a fact with a date outside 0001-01-01 … 9999-12-31 is refused **before** any write; nothing is persisted |
| F-2 | Test gap | no real-process race test for an identical **Admit** retry with concurrent creation; no committed test for the cross-Member key catch path (`PostgresMemberRepository.php:137-144`) | both tests in the `membership-race` / invariants suites |
| F-3 | Parity | `create()` with an existing MemberId for **another** person: the in-memory double overwrites it, PostgreSQL raises a PK violation. The order of checks in `append` (MemberNotFound vs key) differs. The 1a port contract is silent. | the contract clause defined (recommended: an infrastructure error); a shared contract test; the double conforms |
| F-4 | Hardening | `org_membership_commands` has no FK to the fact it names | composite FK `(organisation_id, membership_id, seq)` → `org_membership_facts` PK; a raw inconsistent key row is rejected |
| F-5 | Hardening | the constraint is detected by message substring; the READ COMMITTED assumption is unstated | detect by SQLSTATE + constraint name; the isolation assumption stated in the adapter and dev guide 03 |

**Out of scope:** Slice 2 decisions, MySQL residue (`PBDIGIT-261` / `262`), test-base isolation (`PBDIGIT-263`).

**Revision 1 (2026-10-09):** the plan is refined in `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §9.
- **F-4:** a probe showed the FK on `seq` alone (F-4a) still accepts a wrong `fact_id` or `recorded_at`. The choice is now between F-4b (normalise) and F-4c (full-reference FK).
- **F-5:** `SET TRANSACTION` must be the first statement and fails inside an outer transaction (25001). Choose N1 (refuse nested use) or N2.

Still not authorised.

**Principal Reviewer dispositions (2026-10-09, plan §10):**
- F-4b (normalise) and F-5 N1 chosen; F-3 clarification approved.
- The F-4b migration preflight and rollback requirements are in §10.2.
- **Implementation still requires the Product Owner's authorisation.**

## Progress (synced 2026-10-11, from git history; earlier entries above unchanged)

- **F-1 + Option E (R-1a-6, storable valid time)** were implemented 2026-10-10 on the Human PO / ARB Option E decisions; the gate register is in `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`. Commits:
  - far-date boundary: `ff18c2a4e` (RED), `44348f99b` (GREEN);
  - storable valid time: `0e4e5ac7a` (RED), `af495c838` (GREEN);
  - correction: `fb85c6e24`;
  - documents: `3ee8c2fe4`, `240107642`.
- **F-3 (key first; a MemberId collision is an infrastructure failure)** was implemented 2026-10-10 (F3-1 … F3-4):
  - tests and fix: `d908f93c4` (RED), `d2a3f2b17` (GREEN), `688672412` (F3d);
  - documents: `52b69c4e8`, `e03e5b692`, `effbdbeac`.
- Tested on the in-memory test double. **PostgreSQL behaviour is unverified:** Part 2 is on hold.
- **F-2, F-4 (F-4b), F-5 (N1): held** with PostgreSQL Part 2.
- **Not accepted.** Developer guides 06 and 07.
