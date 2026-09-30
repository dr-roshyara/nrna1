# 1s: M1 (modular) tested on the seven prospective template-era hold-out rows

| | |
|---|---|
| Status | research record, not canonical, authority none. Results are **within one template regime (R-81…R-91)**: not IID, and no population claim |
| M1 | `prompts/KNOWLEDGEOS-M1-MODULAR-PREREGISTRATION.md` (`4544310b…`), frozen at `27a09b5fd` **before** the read |
| Hold-out | `ADR-AIP-LOG-Platform-Rulings.md` (`7795c14b…`), L68–L73 (R-82…R-87) and L75 (R-89), each read once |
| Checker | `m1_check.py` (`345dd506…`), selftest 6/6 (5 mutations plus the NOT-MODELLED rule); output `RESULT.json` (`960e22b2…`) |
| Log | F-LOG-0123 |

**Process note.** The first selftest run failed because the test wrongly expected R-84 as a whole to be NOT-MODELLED. The checker had correctly returned VIOLATED, caused by the adoption annotation (see P4). Only the test was corrected. Instrument logic and the M1 rules are unchanged.

## 1. Prediction ledger

| Prediction (component) | Supported | Violated | Untestable | Not-modelled | Effective evidence units* |
|---|---|---|---|---|---|
| P1 decision text never amended in place (record) | 7 | 0 | 0 | 0 | 7 |
| P2 authorization scope-bounded (scope) | 5 | 0 | 2 | 0 | 5 |
| P3a status ∈ {PREPARED, ADOPTED, HELD, WITHDRAWN} (vocabulary) | 6 | 0 | 1 | 0 | **~3** |
| P3b status semantics s1–s6 | 7 | 0 | 0 | 0 | **~4** |
| **P4 stated changes ⊆ Frame⁺ of the performing op** | 1 | **5** | 0 | 1 | **1 cause** |
| P5 evidence changes no standing on its own (target) | 7 | 0 | 0 | 0 | 7 |

\* **Effective units.** R-82…R-85 carry one **identical** provenance/adoption annotation, pasted four times. For the status predictions that annotation is **one** observation, not four. This is the non-IID point made concrete.

## 2. The falsification (P4, component M1-operations)
- **Frame⁺(ADOPT) = {status} is FALSIFIED.** R-86's adoption act states two changes: "the five become GOVERNING" (status) **and** "their provenance annotations become HISTORICAL RECORD". Rows R-82…R-85 record the same act: "the condition it imposed is **discharged**".
- So adoption also changes the **role of the prior provenance annotation**, from *active condition* to *historical record*. That coordinate is not in ADOPT's declared frame.
- It is **one act (R-86) witnessed in five rows**: one falsification, not five.
- **Scope of the damage:** one frame declaration. P1, P2, P3a, P3b and P5 are untouched, and so are the scope, freeze, target and record components.
- **M2 candidate (not adopted):** Frame⁺(ADOPT) = {status, annotation-role}. It must be tested on new rows.
- **Vocabulary gaps (NOT-MODELLED, not violations):**
  - ALLOCATE (R-84: "WP-4B must implement §12's reconcile");
  - PERMIT-CONSIDERATION (R-87: "WP-4C may be CONSIDERED … consideration is not authorization").
- **Frame⁻ consistency:** no row states the same effect as both performed and excluded (7/7). The rows carry 2–8 explicit negative effects each, 34 in total.

## 3. What survived, and what is new
All items below are SOURCE-FACT; each counts as one occurrence unless stated.

**Status semantics (P3b), explicit and repeated:**
- s2, the Decision Authority adopts rather than the issuer: R-86, and the annotations of R-82…R-85.
- s5, no standing delegation: stated in R-86, R-87 and R-89 ("R-88's adoption was a SINGLE ACT … created no standing delegation").
- This is the third and fourth witness for **path-independence of authority**: equal status implies equal future permissions. The finite-summary model holds, and nothing yet requires unbounded history.

**Operation-specific frames written by the source itself:**
- "§WP-4 advances **by acceptance** … **never by authorization**" (R-87);
- "cannot be achieved by **SCOPING** — only by **SUPERSEDING** §12" (R-83).

These are direct statements that different operations have different frames.

**Record component beyond rulings:** the frozen catalog changes by "**version, never mutate**" (ADR-T5, cited in R-89). This is the same two-layer principle applied to a different artefact.

**Target component:** "the **intent** is sound and the **mechanism** does not reach it" (R-85) is a new target pair (intent vs mechanism). It sits beside decision vs implementation (R-91).

**Open world inside the corpus:** ENG-012 "did not block acceptance because **no evidence classifies it as a defect**" (R-87).

**Reopening needs new evidence (second occurrence, after L216):** R-86 declines to amend because "amending simply because we could would reopen a governance cycle **without new evidence**".

**Scope / non-extension:** "WP-4C-2 is NOT authorized, **not implicitly and not by adjacency**" (R-89).

**New authority name:** "ACCEPTING AUTHORITY" (R-87), extending the NEW-AUTHORITY list.

## 4. Verdict per component

| Component | Verdict (one template regime) |
|---|---|
| M1-record (P1) | survives |
| M1-scope (P2) | survives |
| M1-status (P3a vocabulary-level; P3b substantive) | survives; P3b effective evidence ≈ 4 units |
| M1-target (P5) | survives |
| M1-freeze | consistent: Batch 7 lifted for one repair (R-81), then released (R-86), each scope-bounded. Not separately predicted |
| **M1-operations** | **one frame falsified (ADOPT)**; two operations missing |

## 5. Generalization risks
- All seven rows come from one day, one template, and largely one issuer (the ARB Chief) and one adoption act (R-86).
- The annotation was copy-pasted, so the effective n is small.
- A different regime could break P2, P3b or the frame idea itself.

## 6. Highest-information next observation
A **deliberately different regime**, pre-template (R-42…R-80, 2026-07-30…08-03).
- These rows were issued by the **human authority** directly: every row up to R-80 was, according to the R-81 annotation. That makes them a different authority/process regime.
- Pick 5 by a frozen rule that maximizes operation diversity (and excludes used rows).
- Frozen predictions: P1, P2, P4 (with M2's ADOPT frame), P5, and the regime-specific P3′: **no PREPARED phase; rulings are governing at issue**.
- This tests whether the frame/scope/record theory generalizes beyond the template era.
