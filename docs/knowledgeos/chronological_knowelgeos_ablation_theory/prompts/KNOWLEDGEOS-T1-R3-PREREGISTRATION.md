# T1 r3 — Rule / Interpretation / Practice — PRE-REGISTRATION (results-free; frozen with `analysis/theory/t1r3_check.py`)

| | |
|---|---|
| Status | **r3, frozen at commit.** r0, r1 and r2 stay frozen, as an auditable sequence. No amendment after the first test case is read |
| What r3 adds over r2 | (1) **Interpretation as a separate epistemic object** (not a norm component); (2) **host admissibility** as a source property; (3) an **eligibility gate** blocking the circle practice → interpretation → norm → judge practice; (4) **conformance computed twice**, against the rule **Text** and against each **Interpretation**; (5) a **divergence matrix** (T vs I, I vs O, T vs O) |
| Development cases (never tests) | R-39 · B · P1 · P2 · the bar genealogy (F-LOG-0107/0108). Sanity only |
| **Correction to F-LOG-0108** | the drift is **plural and non-monotone**, not "monotone strengthening". Bar grades: 07-26 chair verdict 2 · R-39 3 · MEMORY 3 · CLAUDE.md (the same day) 1 · ES-001.3 1 |

## 1. Rule (normative object)
- **ρ = (id, host D, Text(ρ, t), provenance prov(ρ) = (authority, date), Applicability(ρ, x, t), Force(ρ, t))**.
  - Text is a set of **requirements** (typed, below) per version. [SOURCE: the rule's released text]
  - Force as in r2 (IN-FORCE / AMBIGUOUS / NOT-IN-FORCE / UNKNOWN). **Force ≠ Status(D)**. [SOURCE: F-LOG-0104]
- **Host admissibility adm(H)** ∈ {NORMATIVE, NON-NORMATIVE, UNKNOWN}. It is a **property of a source location**, not rule content.
  - NORMATIVE: standards (`engineering/governance/`) and the rulings register. [SOURCE: ES-004.3 roles — Decision / Reference]
  - NON-NORMATIVE: memory / hint layers ("MEMORY carries hints; the ES documents are the truth", project runtime instructions) and session logs (Historical role, ES-004.3). [SOURCE]
  - UNKNOWN: otherwise.

## 2. Interpretation (a separate epistemic object)
- **ι = (id, rule_ref, requirements, t, source (path + line), authority ∈ 𝒜 ∪ {none}, host adm, derivation_from)**.
- **Relation to Text** (computed per requirement key, MODEL-DERIVED): RESTATES (same) · NARROWS (stronger) · WIDENS (weaker) · ADDS (a key absent from Text) · UNKNOWN.
- **Normative eligibility** (the anti-circularity gate): ι may serve as a **standard for judging practice** only if **authority ∈ 𝒜 ∧ adm(host) = NORMATIVE ∧ its provenance is explicit**. Otherwise ι is **descriptive evidence about practice**. Conformance to it is still *computed and reported*, but **never used to decide a claim**.
- Authority set 𝒜 = {ARB, DA, PA, Authority} (unchanged from r2).
- **Interpretations have their own scope and date:** Applicability(ι, x) ∈ {SOURCE-STATED, SOURCE-DERIVED, NOT, UNKNOWN}, from ι's own wording (e.g. R-39's reading concerns *principles / methodology*). ι judges an act only if **ι.t ≤ t_act** and its applicability is SOURCE-STATED or SOURCE-DERIVED. Otherwise the result is reported as UNKNOWN or OUT-OF-SCOPE, never as DEVIATES.

## 3. Observed practice
- A trace O is a chronological event list, as in r2: Proposal · NecessityEvidence(attrs, e.g. `contexts`, `instances`) · Qualification · Validation · ValidationExpected · Decide(outcome, a) · Promote(target, a) · Reject(a) · FreezeAuthor · FreezeGovernance · Supersede · Refine · ExceptionRecorded · StatusChange · **Cite(ρ, ι)**.
- Each event carries a SOURCE or READING label and an order basis.

## 4. Requirement types (a small DSL; used for both Text and Interpretation)
- `before(E)`: an event of type E precedes Promote. Missing → NOT-RECORDED; only after → DEVIATES.
- `before_if_high(E)`: as `before`, applied only if the target rung ≥ Engineering Standard; an unknown rung → UNKNOWN.
- `decision`: Promote names an authority. Missing → NOT-RECORDED.
- `attr_min(E, attr, k)`: the attribute of E is ≥ k. Attribute absent → NOT-RECORDED; < k → DEVIATES.

## 5. Classes (never collapsed)
- **Per requirement:** CONFORMS · DEVIATES · NOT-RECORDED · UNKNOWN · OUT-OF-SCOPE (Applicability NOT) · VACUOUS (no Promote or Reject to judge).
- **Per deviation** (against Text, or against an *eligible* Interpretation): **EXCEPTION** (ExceptionRecorded) · **FORCE-AMBIGUOUS** · **FORCE-NOT-IN-FORCE** · UNEXPLAINED.
- **Missing evidence ≠ falsification:** NOT-RECORDED never becomes DEVIATES.

## 6. Divergence matrix (per case and rule; MODEL-DERIVED)
- **ΔTI:** the set of Interpretation-vs-Text relations (RESTATES / NARROWS / WIDENS / ADDS).
- **ΔTO:** conformance of O to Text.
- **ΔIO:** conformance of O to each Interpretation (with its eligibility flag).
- **Pattern labels:**
  - T = I = O: direct conformance;
  - T ≠ I ∧ O ⊨ I: practice follows interpretation drift;
  - T = I ∧ O ⊭ I: practice deviation;
  - T ≠ I ∧ O ⊭ I: layered divergence;
  - Force UNKNOWN: unresolved.

## 7. Claims (re-examined; each falsifiable on a released trace)
- **C1 decision mediation:** every StatusChange / Promote / Reject has an explicit decision by a ∈ 𝒜. [SOURCE ES-001.2]
- **C2 non-collapse:** FreezeAuthor ≠ FreezeGovernance ≠ Promote; READY is never a Decide; recommendation ≠ decision ≠ authorization ≠ execution. [SOURCE: PKS L220; ES-001.3 L44–50]
- **C3 governed deviation:** a DEVIATES against **Text** under Force = IN-FORCE, **or against an eligible Interpretation**, carries an EXCEPTION. [r2 C3, extended by the gate]
- **C4 authority parameter:** every Promote / Reject names an authority. [r2 C4]
- **Dropped or not claimed:**
  - readiness sufficiency; universal necessity;
  - **monotone interpretation drift** (refuted by the development data);
  - "no decision is grounded in a non-eligible interpretation". R-39 already grounds its exception in a composite reading, so this becomes a **descriptive hypothesis H-circ** that is measured, not claimed.

## 8. Test protocol
- One unread case per test, under an L0 release. **Rule, interpretation and trace records are encoded and committed before the checker runs.**
- Report: per-requirement classes for Text and for each Interpretation; the explanations; the divergence pattern; C1–C4 as TRIGGERED / NOT-TRIGGERED / UNDETERMINABLE; every READING dependency.
- No amendment after the first test read.
