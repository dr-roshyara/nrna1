# PBDIGIT-264: Membership Slice 1b follow-ups from the acceptance review (far-date defect, race-test gaps, adapter parity, FK)

**Type:** Story (defect + hardening, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). **Index row:** added 2026-10-09.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |
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
