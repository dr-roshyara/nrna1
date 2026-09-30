# GATE 2 — Formal model comparison M0 / M1 / M2 — PRE-REGISTRATION (before any model is implemented or run)

| | |
|---|---|
| **Kind** | pre-registration. ⚠ authority: generated. Class: **FORMAL DERIVATION / EXPERIMENT DESIGN**. It is not a theory revision and not a model choice |
| **Commission** | human, 2026-09-26: *"yes, start Gate 2 with the pre-registration"* |
| **Question** | which candidate formalization of promotion can represent the historically reconstructed R-39 event while keeping the recorded properties, and at what cost? |
| **Inputs (already on record; no new corpus read)** | the frozen SPEC.md (`44c40fd4…`) and the H-F2-1-R axioms · R-39 historical facts as reproduced by two readers (SELF `R-39-EVIDENCE-REPORT.md`; BLIND-HEADLESS `answers.json` `69cb472b…`; comparison F-LOG-0056) |
| ⚠ **Circularity disclosure** | the author (SELF) knows R-39. R-39 is therefore **not a held-out test**, only a **representability test**. Mitigations: (1) every model, mapping and prediction is fixed here before any code; (2) the exception path's preconditions come **only** from source facts reproduced by both readers; (3) a non-Claude implementer verifies from a results-free spec (§8) |

---

## 1. Mapping of source terms onto model variables (fixed)

| Source term (reproduced by both readers) | Model variable | Encoding |
|---|---|---|
| evidence *"from ONE context"* vs the rule *"more than one bounded context"* | **e**, evidential position | a chain n0 < n1 < n2, with n0 = no evidence · n1 = one context · n2 = ≥ 2 contexts |
| *"the normal promotion rule … requires evidence from more than one bounded context"*, stated *"intact"* | **u**, the bar | **u = {n2}** (an up-set; A0) |
| the *Decision Authority* approval / ruling | **g**, the governance grant | g ∈ {0, 1} |
| *"Governance exception"*, *"explicit exception"* for one item | **x**, an item-specific exception record (M1); **authorization mode** (M2) | see §2 |
| *"ADOPTED"* / *"promoted"* | M0/M1: the **Promote** state predicate · M2: a state component **a** set only by a promotion event | see §2 |
| *"Expected validation: the next bounded context … confirms … or produces the amendment evidence"* | **not modelled** | the source states no consequence or revocation; recorded as a limitation (L-1) |
| *no new evidential event at R-39* (both readers) | the R-39 trajectory contains **no EVID/EVIDREF step at or after the exception act** | — |

## 2. Candidate models

All three keep the SPEC.md state components (p, s, e, g, u), the step kinds and the eleven axioms A0 … A6 unchanged. M1 and M2 add the components and axioms below; nothing else changes.

| Model | Added state | Promotion notion | Added axioms (preconditions from source facts only) |
|---|---|---|---|
| **M0** (current) | none | Promote ⟺ e ∈ u ∧ g = 1 | none |
| **M1** (exception path) | x ∈ {0, 1} | Promote₁ ⟺ (e ∈ u ∧ g = 1) ∨ (x = 1 ∧ g = 1) | **X1:** x changes only in a GOV step. **X2:** a step may set x to 1 only if e ≥ n1 (*evidence present but below the bar*; the source states one context of evidence). **X3:** A6 unchanged (the exception is not a bar change). **X4:** x never returns to 0 (no revocation is stated) |
| **M2** (eligibility / authorization / event separation) | m ∈ {NONE, RULE, EXC} (authorization mode), a ∈ {0, 1} (adopted) | Eligible ⟺ e ∈ u · **Adopted ⟺ a = 1** · a changes 0 → 1 only in a GOV step, and only if (Eligible ∧ m = RULE) ∨ (m = EXC ∧ e ≥ n1) | **Y1:** m and a change only in GOV steps. **Y2:** m = EXC may be set only if e ≥ n1 and e ∉ u (an exception is only for below-bar items). **Y3:** A6 unchanged. **Y4:** a never returns to 0 (demotion is out of scope here) |

There is **no bare exception disjunct.** An exception without evidence (e = n0) is impossible by construction in M1 (X2) and M2 (Y2). That property is tested, not assumed (P-GUARD, §3).

## 3. Properties evaluated in each model

The SPEC.md semantics apply: "holds" = for every step relation satisfying the axioms, from every admissible start. "Promotion" means Promote (M0), Promote₁ (M1) or Adopted (M2).

| Id | Property |
|---|---|
| D1 … D6, D3+, NV | as SPEC.md, with "Promote" read as the model's promotion notion (D1 and D2 are unchanged: they concern e ∈ u and g) |
| **REP** (representability of R-39) | **there exists** a trajectory from (e = n1, g = 0, u = {n2}, x = 0 / m = NONE, a = 0) to promotion, in which **u is constant**, **no EVID/EVIDREF step occurs**, and at least one GOV step occurs |
| **P-GUARD** | from any state with e = n0 and no promotion, every trajectory reaching promotion contains ≥ 1 EVID step (the source requires evidence to be present) |
| **P-BAR** | promotion with e ∉ u implies an exception record (x = 1 or m = EXC) along the trajectory; the ordinary rule is intact |
| **D3-scope** | D3 restricted to starts with e = n0 (distinguishes "no evidence at all" from "evidence below the bar") |

**Instances:** chain3 (primary, the mapping of §1), plus V and diamond for the order-generic properties, and antichain2 as the vacuity control.

**Ablation:** every added axiom (X1–X4, Y1–Y4) is also removed one at a time, as in SPEC.md.

## 4. Predictions (reasoned by hand now, before computation; recorded so that mismatches are visible)

| Property | M0 | M1 | M2 |
|---|---|---|---|
| REP (A6 in force) | **false** (promotion requires e ∈ u = {n2}; there is no EVID step and u is constant) | **true** (GOV sets x = 1 at e = n1, with g = 1) | **true** (GOV sets m = EXC, then a GOV event sets a = 1) |
| REP with A6 removed | true (the −A6 pattern: GOV moves the bar onto the item). This reproduces the blind reader's reading B | true | true |
| D1 | holds | holds (unchanged: it concerns e ∈ u) | holds |
| D2 | holds | holds | holds |
| D3 (all starts) | holds | **fails** (from e = n1, g = 0 there is promotion with no EVID step in the trajectory) | **fails** (same) |
| D3-scope (starts e = n0) | holds | **holds** (X2 forces an EVID step to reach n1 first) | **holds** (Y2) |
| D3+ / D5 | hold | D3+ fails like D3; D5 holds | D3+ fails like D3; D5 holds on Adopted (a never returns to 0) |
| D6 | holds | holds | holds |
| P-GUARD | holds (vacuously relevant) | holds, **fails without X2** | holds, **fails without Y2** |
| P-BAR | n/a | holds | holds |
| NV | true | true | true |

**The prediction in words:**
- M0 cannot represent R-39 except by violating A6 (the blind reader's reading B).
- M1 and M2 represent it with A6 intact.
- M1 and M2 pay for it by **losing D3 as stated** (unrestricted starts), while **keeping D3-scope and P-GUARD**. This is the two readers' D3 disagreement, made precise: D3 survives only for items that start with no evidence.

## 5. Comparison criteria (fixed; Claude reports, it does not choose)

For each model, report:
- (C-a) REP with A6 in force;
- (C-b) which of D1 … D6, D3+, NV, P-GUARD, P-BAR and D3-scope hold;
- (C-c) the minimal axiom sets for each property that holds, including the added axioms;
- (C-d) parsimony: the number of added state components and axioms;
- (C-e) the shortest countermodels for every failing property;
- (C-f) prediction matches and mismatches (§4).

There is no scoring and no aggregation into a single number. **Model choice is a human/L0 decision after review.**

## 6. What would change the conclusion (falsifiers of this design)

- If REP is **true in M0** with A6 in force, the §1 mapping or the §4 reasoning is wrong. **STOP** and report.
- If P-GUARD **fails** in M1 or M2 with X2 / Y2 in force, the exception path admits evidence-free promotion, contrary to the source. **STOP.**
- If NV fails in any model on chain3, that model is vacuous and cannot be compared.

## 7. Limitations (declared)

- **L-1:** the expected validation is not modelled (no stated consequence).
- **L-2:** the e-scale (count of distinct contexts) is a modelling choice, supported by the source's own wording ("ONE" vs "more than one").
- **L-3:** DA vs ARB authority are merged into g / m (the blind reader flagged this relationship as undefined).
- **L-4:** demotion (P-3) is excluded (Y4 / X4).
- **L-5:** R-39 is a representability test, not a held-out prediction.

## 8. Execution plan (after this pre-registration is committed)

1. A **results-free SPEC-G2.md** (the §1–§3 definitions only; no §4 predictions).
2. A reference implementation (Claude; SECONDARY_REVIEW) reusing the SPEC.md semantics. Results, then comparison with §4.
3. **An independent implementation from SPEC-G2.md alone** by a non-Claude implementer (R-2 style), with the comparison rule fixed in advance.
4. STOP for human/L0 review.

---

# REVISION r1 (2026-09-26, before any model code; supersedes r0 §2–§5 where they differ; r0 text above is kept)

**Commission:** human, *"Proceed with Gate 2, but strengthen the preregistration before executing any model."*

**Framing:** an **R-39-informed model-adequacy experiment**. It is not theory validation and not independent empirical confirmation. SELF knows R-39.

## r1-1. Six concepts kept distinct in every model

| # | Concept | Representation |
|---|---|---|
| 1 | Evidence | e ∈ {n0, n1, n2} (no evidence / one context / ≥ 2 contexts) |
| 2 | Eligibility | Eligible ⟺ e ∈ u |
| 3 | Authorization | M0, M1, M3: the grant g · M2: the mode m ∈ {NONE, RULE, EXC} |
| 4 | Promotion event | a GOV step that switches the model's promotion notion from false to true |
| 5 | Promotion state | PromoteState ⟺ e ∈ u ∧ g = 1 (the **current** predicate, evaluated in every model) plus the model's own notion (M1 Promote₁; M2 / M3 Adopted) |
| 6 | Promotion bar | u (up-set; the ordinary rule u = {n2}) |

## r1-2. Models (base = SPEC.md state (p, s, e, g, u), step kinds and axioms A0 … A6, unchanged)

| Model | Added state | Model promotion notion | Added axioms |
|---|---|---|---|
| **M0** | — | PromoteState | — |
| **M1** bounded exception | x (exception record), v (validation pending) ∈ {0, 1} | Promote₁ ⟺ PromoteState ∨ (x = 1 ∧ g = 1) | **X1** x, v change only in GOV steps · **X2** x: 0 → 1 only if n1 ≤ e and e ∉ u (evidence exists, below the bar) · **X3** x: 0 → 1 sets v = 1 (deferred validation recorded) · **X4** x, v never decrease. *Modelling assumption (MA-1):* the "stated reason" is an attribute of the exception act and has no state effect, so it is **not discriminating** in a finite state model; it is recorded, not tested |
| **M2** eligibility / authorization / event | m ∈ {NONE, RULE, EXC}, a ∈ {0, 1} | Adopted ⟺ a = 1 | **Y1** m, a change only in GOV steps · **Y2** m: NONE → EXC only if n1 ≤ e and e ∉ u · **Y2b** m: NONE → RULE only if e ∈ u · **Y3** a: 0 → 1 only if (m′ = RULE ∧ e ∈ u) ∨ m′ = EXC · **Y4** a never decreases; once m ≠ NONE it never changes. *(Deferred validation is not represented in M2: limitation L-6)* |
| **M3** event/state null | a ∈ {0, 1} | Adopted ⟺ a = 1 | **Z1** a changes only in GOV steps · **Z2** a never decreases. **No precondition** links a to evidence: the null alternative against adding exception state |

## r1-3. Scenarios (neutral; no "bypass" or "A6" label is an input). Queries use the model's full axiom set unless stated

| Id | Start | Allowed step kinds | Required path property | Query |
|---|---|---|---|---|
| **S-R39** | e = n1, u = {n2}, g = 0, ext initial (x = v = 0 / m = NONE, a = 0 / a = 0) | GOV, WORK (no evidential step) | u constant; ≥ 1 GOV step | is the model's promotion notion reachable? (M1: also with v = 1 at the end) |
| **S0 NORMAL** | e = n2, u = {n2}, g = 0, ext initial | all | u constant | notion reachable? |
| **S1 BELOW-BAR** | e = n1, u = {n2}, g = 0, ext initial | GOV, WORK | u constant; **no exception set** (x stays 0 / m ≠ EXC) | notion reachable? |
| **S2 EXCEPTION** | = S-R39 without the v-check | GOV, WORK | u constant | notion reachable? |
| **S3 NO-EVIDENCE** | e = n0, u = {n2}, g = 0, ext initial | GOV, WORK | u constant; exception acts allowed | notion reachable? |
| **S4 BAR-CHANGE** | any admissible state | all | — | does any step with u′ ≠ u exist? Plus **S4-R39**: S2 **without** the u-constant requirement and **with A6 removed** |
| **S5 FUTURE-RULE** | the states reachable in S2 | — | — | is u = {n2} in every reachable state, and does the ordinary Eligible predicate stay unchanged? |

## r1-4. Properties (per model)

- **D1, D2, D6:** as SPEC.md.
- **D3 / D3+ / D5 / NV:** evaluated twice: on **PromoteState** and on the **model notion**.
  - Universal starts: e ∉ u, g = 0, ext initial (a fresh item).
- **D3-scope:** D3 on the model notion, from starts with e = n0.
- **P-GUARD:** from e = n0 and not promoted (ext initial), no trajectory without an EVID step reaches the notion.
- **P-BAR:** in every state reachable from fresh-item starts, notion ∧ e ∉ u ⇒ an exception record exists (x = 1 / m = EXC). N/A for M0.
- **Consistency:** S0 reachable.
- **Minimal sets:** over the **added** axioms only, with base axioms fixed (the base minimal sets are known and reproduced by R-2).
- **Ablation:** single removal of every base and added axiom.
- **False-positive exception paths:** the number of fresh-item start states from which the notion is reachable with e = n0 and no EVID step, and with e ∉ u and no exception record.

**Result classes:**
- **PRESERVED** · **REFUTED**;
- **NOT_APPLICABLE**, e.g. *"D3 NOT APPLICABLE TO THE HISTORICAL EVENT UNDER THIS MODEL"* when the S-R39 end state does not satisfy the notion D3 quantifies over;
- **NOT_REPRESENTABLE** · **AMBIGUOUS**.

"Not representable" is never converted to "false".

## r1-5. A6 interpretation per model

| Reading | Meaning |
|---|---|
| A6-A | the bar state is constant, with an exception state added |
| A6-B | an item-specific effective bar is altered |
| A6-C | no bar change, because promotion is an event outside eligibility |

Recorded per model from the results, not assumed.

## r1-6. Predictions (hand-derived now)

| | M0 | M1 | M2 | M3 |
|---|---|---|---|---|
| S-R39 / S2 reachable (A6 on) | **no** (NOT_REPRESENTABLE) | yes (with v = 1) | yes | yes (Adopted) · PromoteState no |
| S4-R39 (M0 without A6) | **yes**: A6-B | — | — | — |
| S0 | yes | yes | yes | yes |
| S1 (must be no) | no | no | no | **yes** (an escape path) |
| S3 (must be no) | no | no | no | **yes** (an escape path) |
| S4 (step with u′ ≠ u under A6) | no | no | no | no |
| S5 | n/a | yes | yes | yes |
| D1, D2, D6 | PRESERVED | PRESERVED | PRESERVED | PRESERVED |
| D3 on PromoteState | PRESERVED | PRESERVED; **NOT_APPLICABLE to the S-R39 event** | same as M1 | same as M1 |
| D3 on the model notion | = above | **REFUTED** | **REFUTED** | **REFUTED** |
| D3-scope (e = n0) on the notion | PRESERVED | PRESERVED | PRESERVED | **REFUTED** |
| D5 on the notion | PRESERVED | PRESERVED | PRESERVED | PRESERVED |
| P-GUARD | PRESERVED | PRESERVED (REFUTED without X2) | PRESERVED (REFUTED without Y2) | **REFUTED** |
| P-BAR | N/A | PRESERVED | PRESERVED | **REFUTED** |
| NV on the notion | true | true | true | true |
| A6 reading | needs A6-B to represent | A6-A | A6-A | A6-C |

## r1-7. Revision record

| Change | Why |
|---|---|
| added M3; added the six-concept split, S-R39 and S0–S5, the A6-A/B/C readings, the NOT_APPLICABLE / NOT_REPRESENTABLE classes, dual D3 evaluation, and MA-1 / L-6 | human instruction; r0 lacked the null model and the controls against an unrestricted exception path |
| minimal sets restricted to the added axioms (base fixed) | tractability; the base minimality is known and was independently reproduced (R-2) |
| universal starts for notion-based properties = fresh items (ext initial) | otherwise a state that *starts* with an exception record trivially promotes by GOV; declared, not hidden |

Execution plan unchanged (r0 §8): results-free SPEC-G2.md → reference implementation → comparison with r1-6 → a non-Claude independent implementation → STOP.

---

# ADDENDUM r2 (2026-09-26): the human's design correction. Supersedes r1 for execution; r0 and r1 are kept as history

**Commission:** the human's Gate-2 correction message, plus four structured decisions:
- M3 → *"Test both variants"*;
- D3-state → *"Test all four"*;
- A6-B → *"Add M0b"*;
- instances → *"Scenarios on chain3 only"*.

**Epistemic rule:** the experiment can establish *"model M represents the reconstructed R-39 semantics under the pre-registered mapping"*, never *"M is the correct KnowledgeOS theory"*. The **r1 exploratory run (F-LOG-0059) is not the Gate 2 result**; its two findings (the P-GUARD scope defect, and M2 state persistence under refutation) are the inputs for this correction.

## r2-1. Concepts (formal)

| Concept | Kind | Definition |
|---|---|---|
| Evidence | state | e ∈ E (poset). ⊥ = the unique minimal element, if it exists |
| Eligibility | state predicate | El ⟺ e ∈ u (u = the global bar, an up-set) |
| Authorization | state component | model-specific: g (M0, M0b, M1), mode m (M2), au (M3) |
| PromotionEvent | **transition** | a GOV step x → x′ with N(x) = false and N(x′) = true |
| PromotionState | state predicate | N (the model's notion); M0 additionally keeps PS ⟺ e ∈ u ∧ g = 1 |

## r2-2. Models (base = SPEC.md: state (p, s, e, g, u), kinds, axioms A0 … A6)

| Model | Added components [initial value] | N | Added axioms |
|---|---|---|---|
| **M0** | — | PS | — |
| **M0b** | b ∈ admissible bars [b = u] | e ∈ b ∧ g = 1 | **B6:** b′ = b (A6-B). u stays under A6 (A6-A) |
| **M1** | x [0], v [0] | PS ∨ (x = 1 ∧ g = 1) | X1 (only GOV changes x, v) · **X2:** x 0 → 1 only if e ≠ ⊥ ∧ e ∉ u · X3 (sets v = 1) · X4 (monotone) |
| **M2** | m ∈ {NONE, RULE, EXC} [NONE], ad [0] | ad = 1 | Y1 (only GOV) · **Y2:** m → EXC only if e ≠ ⊥ ∧ e ∉ u · Y2b (RULE only if e ∈ u) · Y3 (ad 0 → 1 only if (m′ = RULE ∧ e ∈ u) ∨ m′ = EXC) · Y4 (ad monotone; m fixed once set) |
| **M3a** | au [0], ad [0] | ad = 1 | W1 (only GOV changes au, ad) · W3 (ad 0 → 1 only if au′ = 1) · W4 (au, ad monotone). **No evidence precondition, no EXC** |
| **M3b** | as M3a | ad = 1 | M3a plus **W2:** au 0 → 1 only if (e ∈ u ∨ e ≠ ⊥). **An evidence floor only; no mode or exception flag** |

**Instances:** chain3, V, diamond, antichain2.
- On antichain2 (no ⊥), the floor-dependent models **M1, M2, M3b are NOT_APPLICABLE**.
- M0, M0b and M3a run on all four.
- Scenarios run on chain3 only.

## r2-3. Properties (per model, per applicable instance)

**Fresh-item starts:** e ∉ u, g = 0, added components at their initial values, N false.

| Id | Definition |
|---|---|
| D1, D2, D6 | as SPEC.md |
| D3-history[N] / [PS] | every trajectory from a fresh start reaching the notion contains ≥ 1 EVID-or-EVIDREF step and ≥ 1 GOV step (= the original D3) |
| D3+[N] · D5[N] · NV[N] | as SPEC.md on N |
| **D3-state-bar-event** | every PromotionEvent reachable from fresh starts ends in a state with e ∈ u |
| **D3-state-bar-inv** | every reachable N-state (from fresh starts) has e ∈ u |
| **D3-state-floor-event** | every reachable PromotionEvent whose target has e ∉ u has e ≠ ⊥ |
| **D3-state-floor-inv** | every reachable N-state with e ∉ u has e ≠ ⊥ |
| **P-GUARD (scope corrected)** | from fresh starts with e = ⊥ and e ∉ u, no trajectory without an EVID step reaches N |
| P-BAR | every reachable N-state with e ∉ u has an exception record (x = 1 / m = EXC). Models without records: holds iff no such state is reachable |
| **P-PERSIST** | for every reachable N-state, every EVIDREF successor still satisfies N (a descriptive property: does the state persist under refutation?) |

**Counts:** reachable states and reachable N-states from fresh starts.

**Result classes:** HOLDS / FAILS / VACUOUS (no relevant start or event is reachable) / NOT_APPLICABLE / NOT_REPRESENTABLE.

## r2-4. Scenarios (chain3; u = {n2}; fresh start; p and s free)

| Id | Start | Kinds | Path requirement | Query |
|---|---|---|---|---|
| S0 NORMAL | e = n2 | all | u constant | N reachable |
| S1 BELOW-BAR | e = n1 | GOV, WORK | u (and b) constant; no exception record; M2: m never EXC | N reachable |
| S2 EXCEPTION-CANDIDATE (= REP) | e = n1 | GOV, WORK | u (and b) constant; ≥ 1 GOV | N reachable; PS reachable |
| S3 NO-EVIDENCE | e = n0 | GOV, WORK | u (and b) constant | N reachable |
| S4 BAR-CHANGE | any | all | — | does any step change u; M0b: change b |
| S4-T | as S2 | GOV, WORK | M0: A6 removed and u free · M0b: B6 removed and b free (u constant) | N reachable |
| S5 FUTURE-VALIDATION | as S2 | GOV, WORK | — | N ∧ "validation pending" reachable. Only M1 has v; for the others **NOT_REPRESENTABLE** |
| **S6 REFUTATION-AFTER-ADOPTION** | e = n2 | all | u constant | is an N-state with e ∉ u reachable (adopted, then EVIDREF below the bar)? |

## r2-5. Per model and per property, also reported

Shortest countermodel · minimal sets over the added axioms (base fixed) · **redundant added axioms** (in no minimal set of any property that holds) · single-axiom ablation · added components, axioms and transitions · state-space size.

**No score.** A multidimensional table: representability · safety (S1/S3, P-GUARD, P-BAR) · expressiveness (S5, S6) · preservation (D1, D2, D6, D3-history[PS]) · complexity · state expansion · semantic assumptions.

## r2-6. Hand predictions (fixed before code)

| | M0 | M0b | M1 | M2 | M3a | M3b |
|---|---|---|---|---|---|---|
| REP (S2) | no | no | yes | yes | yes | yes |
| S4-T | yes | yes | — | — | — | — |
| S1 (ordinary governance below the bar) | no | no | no | no | **yes** | **yes** (no ordinary/exception distinction) |
| S3 | no | no | no | no | **yes** | no |
| D3-history[N] | holds | holds | fails | fails | fails | fails |
| D3-history[PS] | holds | holds | holds | holds | holds | holds |
| D3-state-bar-event | holds | holds | fails | fails | fails | fails |
| D3-state-floor-event | holds | holds | holds | holds | fails | holds |
| D3-state-bar-inv | holds | holds | fails | fails | fails | fails |
| D3-state-floor-inv | holds | holds | **fails** (x persists after EVIDREF to ⊥) | fails | fails | fails |
| P-GUARD | holds | holds | holds | holds | fails | holds |
| P-BAR | holds (vacuous) | holds | holds | **fails** (r1 finding 2) | fails | fails |
| P-PERSIST | fails | fails | fails (PS path) | holds | holds | holds |
| S6 | no | no | yes (exception path only) | yes | yes | yes |
| D1, D2, D6 | hold | hold | hold | hold | hold | hold |

## r2-7. Independent-verification comparison rule (fixed now)

| Criterion | Rule |
|---|---|
| C-1 | state counts and reachable counts: exact |
| C-2 | every property value per model and instance: exact |
| C-3 | every scenario answer: exact |
| C-4 | ablation truth values: exact |
| C-5 | minimal sets as sets of sets; redundant-axiom sets: exact |
| C-6 | shortest countermodel lengths: exact (trajectories may differ) |
| C-7 | declared interpretation choices: listed |

**Outcomes:** REPRODUCED / REPRODUCED UNDER A DIFFERENT READING / NOT REPRODUCED. Nothing is edited to force agreement.
