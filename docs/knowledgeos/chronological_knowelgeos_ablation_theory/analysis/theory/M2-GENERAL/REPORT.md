# 1t: M2 generalization test in the pre-template, human-authority regime (R-42…R-80)

| | |
|---|---|
| Status | research record. Not canonical. Authority: none. Five rows selected by a frozen category-diversity rule; not IID |
| Pre-registration | `prompts/KNOWLEDGEOS-M2-DELTA-PREREGISTRATION.md` (`3f76392f…`), frozen at `7339f3f01` **before** the reads |
| Read | `ADR-AIP-LOG-Platform-Rulings.md` (`7795c14b…`), L29, L30, L58, L63 and L65 (R-43, R-44, R-72, R-77, R-79), once each |
| Checker | `m2_check.py` (`609d63a3…`), which imports `m1_check.py` read-only and applies only the frozen deltas. Selftest 6/6. Output `RESULT.json` (`524fcbea…`) |
| Log | F-LOG-0124 |

## 1. Ledger

| Prediction | Supported | Violated | Untestable | Not modelled |
|---|---|---|---|---|
| P1: decision text never amended in place | **5** | 0 | 0 | 0 |
| P1-R43: cross-row pointer to R-53, note not in the decision text | **1** | 0 | 4 | 0 |
| P2: authorization scope-bounded | 1 | 0 | 4 | 0 |
| **P3′: no PREPARED phase** (regime-specific) | **5** | 0 | 0 | 0 |
| **P4: stated changes ⊆ Frame⁺ (M2)** | 2 | **1** | 0 | 2 |
| P5: evidence alone changes no standing | 4 | 0 | 1 | 0 |
| **P6: frame generality** (≥ 1 change and ≥ 1 explicit preservation) | **5** | 0 | 0 | 0 |

**Effective evidence note.** R-43's annotation restates R-53's content, which 1q already counted. P5 for R-43 is therefore not a new unit.

## 2. The falsification: Frame⁺(ACCEPT) = {acceptance}
- R-43 carries R-62's recording correction: "the lifecycle transition is *completed work is accepted and closed* … one transition no longer carries two types."
- So **acceptance itself closes the work**. M1/M2 had split ACCEPT and CLOSE-WORK into separate operations, and that split is refuted.
- Corroborated across regimes:
  - R-72: "closure is an ACCEPTANCE OUTCOME, NOT AN AUTHORIZATION DECISION";
  - R-87: "§WP-4 advances by acceptance … never by authorization";
  - R-66: "accepted … WP-7 IS CLOSED".
- **This is a robust falsification.** Candidate for M3: Frame⁺(ACCEPT) = {acceptance, work-lifecycle}.
- The checker also flagged "WP-7 entry conditions satisfied".
  - That is a **derived consequence**: another operation's guard becomes true on the new state.
  - It is not a direct change. In a guarded transition system, guard satisfaction is computed, never stored.
  - **Modelling refinement for M3:** the frame axiom governs *direct* effects; derivable consequences are excluded from P4 by rule.
  - This was not pre-registered, so it is recorded here and not applied retroactively.
- **Reading-dependent (R-77):** "Characterization rejected" was coded as the act's outcome. If a rejected proposal instead carries a status, then Frame⁺(REJECT) = ∅ fails as well. This needs a case that settles it.
- **Not modelled** (vocabulary gaps specific to the regime):
  - RATIFY / APPROVE / RECLASSIFY-FINDING (R-44);
  - DEFER / PERMIT (R-79).
  - PERMIT generalizes PERMIT-CONSIDERATION (R-87) into **PERMIT(activity)**.

## 3. What generalizes (first cross-regime evidence; selected cases only)

| Component | Template regime (1s) | Pre-template regime (1t) | Status |
|---|---|---|---|
| Record: decision text immutable, change only by annotation or a new act | 7/7 | 5/5, plus the cross-row pointer check | **survives both regimes**. Even a *type-label error* is corrected by annotation (R-62): "Semantics unchanged — only the type label is corrected" |
| Frame structure (changes + explicit non-effects) | 5/5 (1q, selected on phrases) | **5/5 (selected on category)** | **survives both** |
| Scope-bounded authorization | 5/5 | 1/1 | survives, but thin in 1t |
| Evidence does not move standing alone | 7/7 | 4/4 | **survives both**. R-77: "not because I know T2 and T14 are compatible. Because THE BOARD HAS NOT ESTABLISHED THEY ARE INCOMPATIBLE"; "NOT SUFFICIENT TO SUPERSEDE AN ACCEPTED ADR" |
| Status model | four statuses (Chief-issued) | **no PREPARED phase, 5/5** | the status model is **regime-parametric** (by issuer), as the R-81 annotation states |
| Operation frames | ADOPT frame falsified | ACCEPT frame falsified | the frame *idea* survives; **specific frame tables do not**. They are being learned, not confirmed |

## 4. New source facts (one occurrence each unless noted)
- **Four acts separated (R-79):** "PERMISSION · AUTHORIZATION · COMMISSIONING · EXECUTION … R-79 SUPPLIES ONLY THE FIRST." This extends the non-collapse family. Related:
  - "ready and not executable";
  - "may be planned and must not be started".
- **Conditional authorization (R-72):** authorized "in principle"; "execution contingent and the contingency is unmet".
  - This is a **guard on the effect**, not a status. P3′ holds.
  - An expressiveness gap: M1's authorization relation has no condition slot.
- **An evidence guard on AUTHORIZE (R-72):** "Each is to be authorized from IMPLEMENTATION EVIDENCE rather than roadmap intent."
  - This bears on the guard question: evidence requirements attach to authorization, not only to promotion.
  - It fits operation-specific guards (the G-O spirit). It is an observation, not a test.
- **Document force ≠ governing practice (R-77):** "ADR-T14 REMAINS NORMATIVE ON PAPER, which is not the same as proving it governs implementation."
  - Source-level support for the L493-B finding that practised force differs from document force.
- **Supersession only by an explicit act (R-77):** "an explicit governance act — not inference — shall amend or supersede it."
- **Adoption is clause-granular (R-77):** "ONE CLAUSE OF THE OFFERED OPTION IS EXPRESSLY NOT ADOPTED."
- **Part ≠ whole (R-72, citing R-69):** "Accepting WP-4A did not accept §WP-4."
- **Third target pair (R-44):** "the invariant is binding; the mechanism is substitutable." The pairs so far:
  - intent vs mechanism (R-85);
  - decision vs implementation (R-91);
  - invariant vs mechanism (R-44).
- **Prediction ≠ executed result (R-44):** "'Deptrac passes unmodified' was an analytical prediction at ruling time, not an executed result."

## 5. Verdict
- **Survives both regimes (selected cases):** the record invariant; frame structure; scope-bounded authorization (thin); target-indexed evidence persistence.
- **Regime-parametric:** the status model.
- **Falsified:** the specific frame tables for ADOPT (1s) and ACCEPT (1t).
- **Still open:** the evidence-bar question (G-R/G-K/G-O/G-E). The new R-72 guard observation widens it: guards may be per operation beyond promotion.

## 6. Highest-information next step: consolidate, then attack
Stop sampling rows for now. Three tasks remain.

1. **Pre-register M3** with:
   - the ADOPT and ACCEPT frame corrections;
   - the direct-effect vs derived-consequence rule;
   - conditional authorization;
   - PERMIT(activity);
   - the four-act separation (permission / authorization / commissioning / execution) as distinct relations;
   - status parametrized by issuer.
2. **Attack M3 with computer logic.** Build an explicit-state model and check:
   - the four-act separation as invariants (no execution without authorization; no authorization inferred from permission);
   - "closure only by acceptance";
   - no self-adoption;
   - no delegation from adoption;
   - no supersession without an explicit act.

   Produce the shortest counterexample traces. These show which *new* observation would falsify M3.
3. **Then one targeted read** chosen by those counterexamples (an information-gain choice), preferably from a third source family: session logs or plans rather than the register. The aim is to break the single-host dependence (all 1q/1s/1t rows come from one register file).
