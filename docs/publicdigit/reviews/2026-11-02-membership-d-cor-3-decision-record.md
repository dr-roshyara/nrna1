# Membership D-COR-3: which attributes may Correct change? Decision record

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect), on the PO's instruction
**Status:** **DECIDED by the PO (2026-11-02): dates only in the first Correct slice. The other attributes stay OPEN.**
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.

## 1. The question (as recorded)

**Correction contract** §9 (`2026-10-09-membership-correction-slice-semantic-contract-draft.md:215`):
- the question: "are the Admit initial term, End kind, provenance and authority evidence correctable by Correct?";
- the evidence: "the corpus is silent (FP §14.3 covers dates)";
- the recommendation: "dates only in the first slice; the others OPEN";
- the decider: the PO;
- it blocks: the C-2 scope.

The contract's correctable-attributes row (`:97`) says the same:
- **ratified [RAT, FP §14.3]:** Admit `effectiveAt` and End `effectiveAt`;
- **not specified by the corpus:** the initial term, End kind, provenance and authority evidence [OPEN D-COR-3];
- **never correctable:** the holder (R-13).

**In business terms:** when a membership record has a wrong date, a Correct can fix the admission date or the end date. Whether the other recorded details can also be corrected (the first term, the kind of ending, and the provenance or authority evidence of a decision) is not settled now.

## 2. Decision (PO, 2026-11-02)

**PO words:** "D-COR-3: dates only, record it and commit".

> "In the first Correct slice (COR-2), Correct may change only the Admit `effectiveAt` and the End `effectiveAt`. Correcting the Admit initial term, the End kind, provenance or authority evidence is not offered in that slice and remains OPEN. The holder is never correctable (R-13)."

**Effect on the open items:** "the others OPEN" means **not decided**, not "permanently refused". A later decision may allow them, each with its own evidence.

## 3. Consequences (recorded, not authorised)

- **COR-2 scope:** Correct over the two dates; value chains; g2, `LiveCut`, CFC+; the ER guard (BD-REV-ER = refuse).
- **COR-2 refusal behaviour:** a Correct aimed at any other attribute is refused in COR-2. The refusal name (e.g. under the contract's `TargetNotCorrectable` family, `:102`) is fixed when COR-2 is specified.
- **Blocking decisions recorded in the contract:** with BD-REV-ER and D-COR-3 decided, **no decision listed in the contract (`:223`) blocks C-2 any more**. COR-2 still needs C-1 accepted (contract `:223`, "depends on C-1"; C-1 is deferred under PBDIGIT-268), its own story, a plan and authorisation.
- **Trim representation** (relative or absolute; contract `:223`) remains a COR-2 design choice. It is **not** decided here (see §4).

## 4. Discrepancy in the adopted roadmap (recorded, not resolved here)

The roadmap (`20261016_2219_…_completion_plan.md:152`) labels **D-COR-3, D-COR-4** as "trim representation; `target_fact_id` column", with the Principal Reviewer as decider.

- That does not match the correction contract, where D-COR-3 is the correctable-attributes question and the PO decides it (`:215`). D-COR-4 is the `target_fact_id` rename (`:216`).
- This record follows the contract, which is the approved source.
- The roadmap row is annotated **R-10**. "Trim representation" stays an open COR-2 design point, for the Principal Reviewer as the roadmap says.

## 5. Not changed or authorised

- No ratified text changes; FP §14.3 already covers dates.
- No implementation. COR-2 needs C-1 acceptance, its own story, a plan and authorisation.
- CQ-3 (who may Correct) is untouched.

## 6. Traceability

- Correction contract §2 (`:97`), §9 (`:215`), §10 (`:102`, `:223`).
- BD-REV-ER decision record (2026-11-02).
- Roadmap BR-11 (R-9), `:152` (R-10), W4 `:178` (R-10).
