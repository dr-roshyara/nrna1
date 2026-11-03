# PBDIGIT-327: Membership correction slice C-2 (COR-2): Correct and its consequences, in the kernel (TDD-first)

**Type:** Story (domain kernel, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-03 · **Raised by:** the PO's instruction "prepare the COR-2 story and plan" (2026-11-03). Roadmap W4, first step (COR-2 → COR-4 → COR-3).

| | |
|---|---|
| **Status** | `APPROVED` (PO, 2026-11-03): plan approved; P-1…P-6 decided as recommended. Steps 1–7 authorised (kernel only). Nothing implemented yet |
| **Plan** | `docs/plans/20261103-0222-membership-cor2-correct-and-consequences-tdd-plan.md` |
| **Contract** | `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` (APPROVED by the PO 2026-10-10): §3.3 Correct, §4, §9, §10 (C-2 row), §10a Correct 1–10, §14.5 (C-2 criteria), §14.7 |
| **Decisions already taken** | **BD-REV-ER = refuse** (`2026-11-02-membership-bd-rev-er-decision-record.md`); **D-COR-3 = dates only in the first slice** (`2026-11-02-membership-d-cor-3-decision-record.md`); D-COR-2 = refuse (reversal of a non-live target); D-COR-5 (A) (Annul / Void carry no consequences) |
| **Depends on** | `PBDIGIT-268` C-1 (accepted 2026-11-02) |
| **Gate** | ARB-MEM-01: correction (COR-1…COR-4) accepted before any cutover, migration or live write |

## Business purpose

When a membership date was recorded wrongly (the admission date or the end date), the organisation must be able to **correct** it without deleting history. The original value stays in the audit trail, and every earlier "what did we know on date X" answer stays reproducible.

If a corrected date makes a period overlap another one, the ratified rules decide what happens:
- the overlap is **trimmed** from the other period, recorded as a consequence of the correction (BD-REV = trim, CFC+);
- unless the correction would wipe out a whole period: that is **refused** (BD-REV-ER).

## Scope (kernel only, database-free)

- a `Correct` fact: target, corrected date, provenance, `decidedAt`;
- value precedence: the latest live Correct wins; chains Correct(Correct);
- the consequences g2 (the Δ cuts, plus inherited clipped cuts), `LiveCut`, CFC+;
- the BD-REV-ER guard;
- Annul / Void **of** a Correct (deferred from C-1, contract §14.7);
- the refusals;
- the decision carries the event data (`FactCorrected`, `PeriodTrimmed`, `TrimRevived`);
- the oracle port and golden fixture, exhaustive differential tests, properties, mutation measurement.

## Not in scope

- **Persistence** (COR-4; it carries condition K-2 of ACC-260, C-1 F-1).
- **Application commands and authority** (COR-3; CQ-3, CQ-9).
- Event emission.
- Correcting anything other than the two dates (D-COR-3).
- Renew, Suspend, ChangeType.

## Acceptance criteria

Authoritative in the plan §6.
