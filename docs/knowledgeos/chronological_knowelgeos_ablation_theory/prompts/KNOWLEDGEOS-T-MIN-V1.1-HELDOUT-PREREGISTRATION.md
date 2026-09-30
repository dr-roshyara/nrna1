# T-min v1.1: extended vocabulary and operation-specific evidence bars, with a held-out test (1ae)

| | |
|---|---|
| Status | **Frozen at commit before any held-out row is read.** Not canonical. Authority: none. v1 stays unchanged; v1.1 = v1 plus §1–§2 |
| Derived from | F-LOG-0135 (the 15-row out-of-sample sample). **Those rows are not reused** |
| Held-out sample | the next 13 of the same seeded order (sha256("F-1ab-2026-09-28" + ID), positions 16–28): **R-31, R-36, R-38, R-46, R-49, R-50, R-55, R-58, R-61, R-67, R-73, R-92, R-93** |
| Reserve | R-33, R-42, R-48, R-54, R-57, R-59, R-60, R-69, R-70, R-71, R-74, R-76, R-95. **Untouched**, kept for a later test |
| Coder | the same agent (not blind); same mitigations as in 1ab |

## 1. Extended operation vocabulary (Frame⁺ = the coordinates the operation may change directly)

| Operation | Row verbs it covers (frozen synonym table) | Frame⁺ |
|---|---|---|
| ADOPT (status) | "adopted" said of a PREPARED ruling | {status, annotation-role} |
| **ADOPT-DECISION** | "adopted"/"adoption" said of an option, path, rename or decision (first instance) | {decision-in-force} |
| **APPROVE** | "approved", "approval" (a plan, realization or rule) | {approval(target)} |
| **DEFER** | "deferred", "deferral" | {deferral(target)} |
| **OPEN-WORK** | "opened" (a work package) | {work-lifecycle} |
| **RATIFY** | "ratified" | {standing(target)} |
| **DECLARE-SCOPE** | "declared" of a scope or boundary | {norm(scope)} |
| **CONFIRM** | "confirmed" (no change) | ∅ |
| **SEAL** | "sealed" | {regime(document)} |
| **REFINE-CANDIDATE** | "candidate … refined" | {candidate-content} |
| **MODIFY-SEQUENCE** | "sequence modified" | {sequence} |
| AUTHORIZE | "authorized" | {authorization(scope)} |
| ACCEPT | "accepted" (completed work) | {acceptance, work-lifecycle} |
| SUBDIVIDE | "subdivided", "split" | {work-structure} |
| WITHDRAW / HOLD | "withdrawn" / "held" | {status(, registry)} |
| ANNOTATE | "annotated", "annotation", "redirect", "pointer" | {annotation} |
| DETERMINE / RECLASSIFY | "determined", "reclassified", "classified" | {classification, evidence-status} |
| FREEZE / LIFT | "frozen" / "lifted", "released" | {regime(scope)} |
| REJECT | "not adopted", "rejected", "declined" | ∅ |
| SUPERSEDE | "supersedes", "superseded by" | {authoritative-location} + the old record kept |
| CREATE-NORM | a rule or discipline established ("shall", "is the rule") | {norm} |
| RAISE / ALLOCATE / PERMIT / CONTRA / CORRECT-TEXT | as in v1 | as in v1 |

An act whose verb is in no row of this table is **NOT-MODELLED (verb)**. An act that fits two synonyms is coded by the performative in the row's headline.

## 2. Operation-specific evidence bars (v1.1 guard)

| Operation | Bar | Source basis |
|---|---|---|
| RAISE | ε ≥ 2 or an exception | L493-B, R-39 |
| SUPERSEDE | sufficient evidence, or a structural rule | R-77, R-83, ADR-MP |
| CREATE-NORM of new vocabulary, categories or a methodology extension | *demonstrated* need / sustained demand | R-80, R-64 |
| AUTHORIZE of a successor slice | the predecessor accepted, plus implementation evidence | R-72 |
| any reopening | *materially new* evidence | L216, R-86, R-78 |

## 3. Predictions on the held-out rows

**P-COV (the main test):** v1.1 covers **≥ 0.70** of the coded acts.
- **FALSIFIED if the held-out coverage point estimate is < 0.70.**
- v1's coverage on the same acts is reported as the baseline.

**V1′–V7′:**
- as in 1ab, using v1.1's guards and frames;
- **V6′ is regime-conditioned:** rows from R-43 onward name their authority. Rows before R-43 are reported descriptively only.

**Reporting:**
- Wilson 95% intervals;
- every VIOLATED case with its row ID and quote;
- ambiguous cases flagged;
- no revision of v1.1 in this step.
