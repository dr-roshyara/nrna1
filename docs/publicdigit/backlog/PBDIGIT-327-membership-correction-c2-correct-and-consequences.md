# PBDIGIT-327: Membership correction slice C-2 (COR-2): Correct and its consequences, in the kernel (TDD-first)

**Type:** Story (domain kernel, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-03 · **Raised by:** the PO's instruction "prepare the COR-2 story and plan" (2026-11-03). Roadmap W4, first step (COR-2 → COR-4 → COR-3).

| | |
|---|---|
| **Status** | **`ACCEPTED`** (PO, 2026-11-03: "accept PBDIGIT-327; 1–3 as recommended; F-3/F-4 as contract questions"), **kernel only**, with the documented residual risks.<br>• P-2: absolute representation, the distinction nominal.<br>• ER on every decision.<br>• Guide 12 → PBDIGIT-328.<br>• OQ-C2-1 / OQ-C2-2 open in contract §9.<br>No COR-3 / COR-4 is authorised; ARB-MEM-01 stays. Record §4.1.<br>*(Was: `REVIEWED — CONDITIONS CLOSED, AWAITING PO ACCEPTANCE` (2026-11-03).)* |
| *(history)* | `REVIEWED — CONDITIONS CLOSED, AWAITING PO ACCEPTANCE` (2026-11-03). The independent review recommended ACCEPT WITH CONDITIONS C-1…C-3 and found no production defect. PO: option (A). The conditions are closed by tests and records only. Record: `docs/publicdigit/reviews/2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md`. *(Was: `IMPLEMENTED — AWAITING INDEPENDENT REVIEW` (2026-11-03).)* Steps 1–7 done, kernel only, database-free (commits `abb81b1c5`, `58b89ab44`, `739875e1c`, `e5836a816`, `01fcbc9e3`, plus step 7). Evidence: plan §10. **Not accepted**: next are the independent review, then the PO verdict. *(Was: `APPROVED` (PO, 2026-11-03), plan approved, P-1…P-6 decided as recommended.)* |
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
