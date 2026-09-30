# GATE 2.5 — Temporal safety of promotion — PRE-REGISTRATION (before any model code)

| | |
|---|---|
| **Kind** | pre-registration. ⚠ authority: generated. Class: **FORMAL EXPERIMENT DESIGN**. It is not a theory revision and not a model choice |
| **Commission** | human, 2026-09-26: the "Gate 2 … Proceed … Phases A–K" prompt |
| **Question** | *What temporal semantics prevent, or intentionally permit, promotion after the evidence condition changes between authorization and promotion?* |
| **Motivation (frozen Gate 2 fact, F-LOG-0062/0064)** | in every guarded Gate 2 model (M1, M2, M3b) there is a length-3 witness: authorization at e = n1 → EVIDREF to n0 → promotion at e = ⊥ (time of check ≠ time of use) |
| **Scope** | the Gate 2 models M1/M2/M3b are **not changed**. Gate 2.5 is a **new, separate model family**. No corpus reading; no ML |
| **Disclosure** | SELF designed Gate 2 and knows its results; the Gate 2.5 predictions below are hand-derived from those results |

## 1. Semantic objects (kept separate)

| Object | Kind | Formalization |
|---|---|---|
| Evidence | state | e ∈ E (poset); ⊥ = the unique minimal element |
| Eligibility | state predicate | El ⟺ e ∈ u |
| Evidence floor | state predicate | **F ⟺ e ∈ u ∨ e ≠ ⊥** (eligible, or some evidence present) |
| Authorization | state + transition | au ∈ {0, 1}; **AuthEvent** = a GOV step with au: 0 → 1 |
| Promotion event | transition | **PromEvent** = a GOV step with ad: 0 → 1 |
| Promotion (adoption) state | state | ad ∈ {0, 1} |
| Validation | transition sequence | evidence reaching the bar after authorization (an EVID step to e ∈ u) |
| Reassessment / revocation | transition | **P1:** an explicit GOV revocation step (ad: 1 → 0 with rv = 1) · **P0:** automatic invalidation when F fails |

**Property types:**
- *state* property: over reachable states;
- *transition* property: over single steps;
- *history* property: over trajectories (existence or absence of a pattern).

## 2. Model family MT (base = SPEC.md: state (p, s, e, g, u), kinds and axioms A0 … A6)

**Added state:** au [0], ad [0], rv [0].

| Axiom | Content | In |
|---|---|---|
| **T-G1** | au changes only in GOV steps; ad: 0 → 1 only in GOV steps; rv changes only in GOV steps | all |
| **T-AUTH** | au: 0 → 1 only if F holds in the source state (authorization-time guard) | all |
| **T-AM** | au never decreases (a durable authorization) | all |
| **T-PROM** | ad: 0 → 1 only if au′ = 1 | all |
| **T-EVENT** | ad: 0 → 1 only if F holds in the source state (**promotion-time guard**) | **MT2 only** |
| **T-P1** | ad: 1 → 0 only in a GOV step with rv′ = 1; rv: 0 → 1 only together with ad: 1 → 0; rv never decreases | **P1 only** (explicit revocation) |
| **T-P0** | ad′ = 1 ⇒ F in the target state; ad: 1 → 0 only if F fails in the target state; rv stays 0 | **P0 only** (evidence-coupled adoption) |

**Models:**

| Model | Guards | Persistence |
|---|---|---|
| **MT1-P0** | authorization only | evidence-coupled |
| **MT1-P1** | authorization only | explicit revocation |
| **MT2-P0** | authorization + event | evidence-coupled |
| **MT2-P1** | authorization + event | explicit revocation |

- MT1 = "authorization snapshot" semantics; MT2 = "strict event-time" semantics. **Neither is preferred in advance.**
- **Instances:** chain3, V, diamond. antichain2 is **NOT_APPLICABLE** (F needs ⊥).
- **Scenarios:** chain3, u = {n2}.

## 3. Properties (temporal-logic notation over trajectories; decided exhaustively on the finite system)

| Id | Type | Statement | Informal |
|---|---|---|---|
| **AUTH-SAFETY** | transition | G(AuthEvent → F) | every reachable authorization happens with the floor satisfied |
| **EVENT-SAFETY** | transition | G(PromEvent → F) | every reachable promotion event happens with the floor satisfied |
| **TOCTOU** | history (existential) | F(AuthEvent ∧ X F(¬F ∧ F PromEvent)), with no re-authorization in between | can an authorization survive the loss of the floor and be used for promotion? |
| **PERSIST** | state (existential) | EF(ad = 1 ∧ ¬F) | can adoption survive evidence deterioration below the floor? |
| **AUTO-INVAL** | transition | G((ad = 1 ∧ X ¬F) → X ad = 0) | adoption is removed automatically when the floor fails |
| **REVOC-EXPLICIT** | transition | G((ad = 1 ∧ X ad = 0) → (GOV ∧ X rv = 1)) | every loss of adoption is an explicit governance revocation |
| **REVAL** | history (existential) | from a reachable state with au = 1 ∧ ¬F: (a) PromEvent reachable **without** any EVID step? (b) reachable **only after** an EVID step restoring F? | what the model permits after the evidence changes |
| **REP** | existential | as Gate 2 S2 (e = n1, u = {n2}; GOV and WORK only; u constant) → ad = 1 reachable | the R-39 shape |
| **P-GUARD** | history | from fresh states with e = ⊥ ∉ u, no trajectory without an EVID step reaches ad = 1 | as Gate 2 (scope-corrected) |
| **D1, D2, D6** | as SPEC.md | | preservation |

Classes: HOLDS / FAILS / VACUOUS / NOT_APPLICABLE / NOT_REPRESENTABLE (the Gate 2 conventions, stated explicitly in the spec).

## 4. Temporal scenarios (chain3; u = {n2}; fresh start; p and s free)

A **phase automaton** tracks: *authorized* → *changed* (the required change happened after authorization) → *promotion event*. The witness reports the state **at authorization** and **at promotion**, and whether F was checked at both times (a model property: MT2 yes, MT1 no).

| Id | Start e | Required sequence | Kinds |
|---|---|---|---|
| **T1** | n1 | AuthEvent → PromEvent, with no evidence step in between | GOV, WORK |
| **T2** | n1 | AuthEvent → EVID raising e → PromEvent | all |
| **T3** | n2 | AuthEvent → EVIDREF to n1 (F still holds) → PromEvent | all |
| **T4** | n1 | AuthEvent → EVIDREF to n0 (F fails) → PromEvent | all |
| **T5** | n1 | AuthEvent → a step changing u (**run with A6 removed**) → PromEvent | all |
| **T6** | n1 | AuthEvent → no PromEvent until an EVID step reaches e ∈ u (validation) → PromEvent | all |

## 5. Hand predictions (fixed now)

| | MT1-P0 | MT1-P1 | MT2-P0 | MT2-P1 |
|---|---|---|---|---|
| AUTH-SAFETY | holds | holds | holds | holds |
| **EVENT-SAFETY** | **holds** (T-P0 forbids ad = 1 where F fails) | **FAILS** (the TOCTOU witness) | holds | holds |
| **TOCTOU** | not reachable | **reachable** | not reachable | not reachable |
| PERSIST | not reachable | **reachable** | not reachable | **reachable** (adopted with F, then F lost, no revocation) |
| AUTO-INVAL | holds | fails | holds | fails |
| REVOC-EXPLICIT | fails (ad drops without GOV) | holds | fails | holds |
| REVAL (a) | no | **yes** | no | no |
| REVAL (b) | yes | yes | yes | yes |
| REP | yes | yes | yes | yes |
| P-GUARD | holds | holds | holds | holds |
| D1, D2, D6 | hold | hold | hold | hold |
| T1 / T2 / T3 / T6 | reachable | reachable | reachable | reachable |
| **T4** | unreachable | **reachable** | unreachable | unreachable |
| T5 | reachable | reachable | reachable | reachable |

**The prediction in words:** the TOCTOU gap is closed **either** by a promotion-time guard (MT2) **or** by evidence-coupled adoption (P0). These are two different semantic mechanisms that give the same event-safety. Persistence under deterioration remains possible in MT2-P1: event-safe, but adoption is not invalidated automatically.

## 6. Reporting (multidimensional; no score, no ranking, no selection)

Per model:
- representability (REP);
- safety (AUTH-/EVENT-SAFETY, P-GUARD);
- temporal safety (TOCTOU, T4);
- persistence (PERSIST, AUTO-INVAL, REVOC-EXPLICIT);
- traceability (explicit revocation);
- expressiveness (REVAL, T6);
- state-space size;
- added state variables · added axioms · new transition types;
- shortest counterexamples;
- ablation (single removal) and minimal sets over the added axioms (base fixed).

## 7. Independent-verification rule (fixed now)

As Gate 2 r2-7 (C-1 counts · C-2 property classes · C-3 scenarios, including the auth/promotion states' e and u · C-4 ablation · C-5 minimal and redundant sets · C-6 witness lengths · C-7 readings).

**Outcomes:** REPRODUCED / REPRODUCED UNDER A DIFFERENT READING / NOT REPRODUCED. A **non-Claude** implementer is required before any use of the result for theory decisions.

## 8. Open semantic questions (not decided here)

- **OQ-T1:** is authorization a durable entitlement (snapshot), or must the evidence condition hold at execution?
- **OQ-T2:** does adoption survive later deterioration (P1) or is it evidence-coupled (P0)?
- **OQ-T3:** does a bar/rule change after authorization affect pending promotions (T5; linked to the Gate 2 / R-39 question)?
- **OQ-T4:** is validation a condition of promotion or an expectation (R-39 "Expected validation")?

## 9. Execution (after this pre-registration and the results-free SPEC-G25.md are committed): NOT started in this commission

Reference implementation → non-Claude independent implementation → mechanical comparison → STOP.

---

# ADDENDUM r1 (2026-09-26, before any Gate 2.5 code; supersedes §1–§5 where they differ; the original text is kept)

**Commission:** the human's correction: *"SEPARATE EVIDENCE, FLOOR AND ELIGIBILITY … add MT0 … formalize t_a / t_p …"*.

## r1-1. Three predicates, never conflated

| Symbol | Definition | Meaning |
|---|---|---|
| **E0(e)** | e ≠ ⊥ | evidence exists |
| **EB(e, u)** | e ∈ u | constitutional eligibility (bar satisfied) |
| **EF(e, u)** | **EB ∨ E0**. *Modelling choice (declared):* authorization is permitted if the item is eligible **or** has some evidence. This equals r0's F | authorization evidence floor |

**Interpretation rules (binding for the report):**
- E0 does not imply EB;
- authorized does not imply EB;
- adopted does not imply that EB (or E0) holds now, unless an axiom states it.

r0's "F" is **renamed EF** throughout. The r0 axioms T-AUTH, T-EVENT and T-P0 use EF, unchanged in content.

## r1-2. Persistence, clarified

- **P0 = P0-EVIDENCE:** adoption is coupled to **EF**. It is removed only when the item is neither eligible nor has any evidence. It is **not** coupled to eligibility: n2 → n1 (below the bar, evidence present) keeps adoption.
- **P0-ELIGIBILITY** (adoption coupled to EB) is **not** part of this experiment. It is recorded as a possible later variant.
- **P1:** explicit revocation (unchanged).

## r1-3. Models

- **Primary models (unchanged):** MT1-P0, MT1-P1, MT2-P0, MT2-P1.
  - MT1 = the snapshot semantics: authorization at t_a suffices for promotion at t_p.
  - MT2 = the revalidation semantics: promotion at t_p requires EF at t_p.
  - **Neither is preferred.**
- **MT0: a diagnostic negative control, NOT a candidate:** base plus au/ad/rv with only T-G1, T-AM and T-PROM, i.e. only the mechanics (GOV-only changes, monotone authorization, promotion needs authorization).
  - No T-AUTH, T-EVENT or persistence axiom: ad may drop in any step.
  - It shows which properties come from the base transitions, which from the guards, and which from persistence.
  - **Excluded from any model-selection discussion.**

## r1-4. Temporal distinction

- **t_a** = the time of the AuthEvent; **t_p** = the time of the PromEvent. t_a < t_p is allowed, and e(t_a) ≠ e(t_p) is possible.
- **SNAPSHOT:** Auth(t_a) ⇒ CanPromote(t_p). **REVALIDATION:** Prom(t_p) ⇒ Condition(e(t_p), u(t_p)).
- The experiment reports which one each model implements. It decides neither.

## r1-5. Properties (replacing r0 §3's AUTH-SAFETY / EVENT-SAFETY; all others kept)

| Id | Type | Formula |
|---|---|---|
| AUTH-SAFETY | transition | G(AuthEvent → EF) |
| AUTH-EVIDENCE-SAFETY | transition | G(AuthEvent → E0) |
| AUTH-EVIDENCE-SAFETY-scoped | transition | G(AuthEvent ∧ ¬EB → E0) |
| AUTH-ELIGIBILITY-SAFETY | transition | G(AuthEvent → EB) |
| EVENT-FLOOR-SAFETY | transition | G(PromEvent → EF) (= r0 EVENT-SAFETY) |
| EVENT-EVIDENCE-SAFETY | transition | G(PromEvent → E0) |
| EVENT-EVIDENCE-SAFETY-scoped | transition | G(PromEvent ∧ ¬EB → E0) |
| EVENT-ELIGIBILITY-SAFETY | transition | G(PromEvent → EB) |

Kept from r0: TOCTOU (history; "the state with ¬F" now reads ¬EF), PERSIST (state; EF), AUTO-INVAL (transition; EF), REVOC-EXPLICIT (transition), REVAL-a / REVAL-b (history), REP (existential), P-GUARD (history), D1 / D2 / D6.

**Why both unscoped and scoped forms** (declared, not hidden): the admissible bar u = E (every evidential position qualifies, ⊥ included) makes the ordinary rule promote with no evidence. The **unscoped E0 forms therefore fail by construction** wherever such a bar is reachable. That is a correct formal fact, showing EB without E0. The scoped forms test whether *below-bar* promotions or authorizations carry evidence.

## r1-6. Scenario reporting (T1–T6 unchanged)

For each witness: e(t_a), u(t_a), e(t_p), u(t_p), E0(t_a), EB(t_a), E0(t_p), EB(t_p), and whether the authorization guard and the promotion-event guard are part of the model.

## r1-7. Revised predictions (hand-derived; supersede r0 §5 where they differ)

| | MT0 (control) | MT1-P0 | MT1-P1 | MT2-P0 | MT2-P1 |
|---|---|---|---|---|---|
| AUTH-SAFETY (EF) | FAILS | holds | holds | holds | holds |
| AUTH-EVIDENCE-SAFETY (unscoped) | FAILS | **FAILS** (u = E: authorization at ⊥, eligible without evidence) | FAILS | FAILS | FAILS |
| AUTH-EVIDENCE-SAFETY-scoped | FAILS | holds | holds | holds | holds |
| AUTH-ELIGIBILITY-SAFETY | FAILS | **FAILS** (authorization at n1 below the bar) | FAILS | FAILS | FAILS |
| EVENT-FLOOR-SAFETY | FAILS | holds (via T-P0) | **FAILS** (TOCTOU) | holds | holds |
| EVENT-EVIDENCE-SAFETY (unscoped) | FAILS | FAILS (u = E) | FAILS | FAILS | FAILS |
| EVENT-EVIDENCE-SAFETY-scoped | FAILS | holds | **FAILS** (TOCTOU at n0) | holds | holds |
| **EVENT-ELIGIBILITY-SAFETY** | FAILS | **FAILS** | **FAILS** | **FAILS** | **FAILS**: every primary model can promote an item that has evidence and authorization but is below the bar (the R-39 shape) |
| TOCTOU | reachable | no | **yes** | no | no |
| PERSIST (ad ∧ ¬EF) | reachable | no | yes | no | yes |
| AUTO-INVAL | FAILS | holds | FAILS | holds | FAILS |
| REVOC-EXPLICIT | FAILS | FAILS | holds | FAILS | holds |
| REVAL-a / REVAL-b | yes / yes | no / yes | yes / yes | no / yes | no / yes |
| REP | yes | yes | yes | yes | yes |
| P-GUARD | FAILS | holds | holds | holds | holds |
| D1 / D2 / D6 | hold | hold | hold | hold | hold |
| T4 (auth at n1 → n0 → promote) | reachable | no | **yes** | no | no |
| T1 / T2 / T3 / T5 / T6 | reachable | reachable | reachable | reachable | reachable |

The comparison rule (r0 §7) and the non-Claude requirement are unchanged.
