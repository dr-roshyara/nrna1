# T1 r1 — engineering-knowledge (ES-006.1) promotion — PRE-REGISTRATION (results-free; frozen before candidate B is read)

| | |
|---|---|
| Status | **r1, frozen at commit.** It supersedes nothing: T1 r0 (PKS) stays frozen. There is no amendment after the case is read |
| Built from | released wording only: ES-006.1 (L14–21), ES-006.2–.4 (L22–49), ES-001.2 (L26), R-37 (L23) |
| **Excluded on purpose** | every element known **only** from R-39 (L25): the "more than one bounded context" bar, "early promotion", "expected validation" |
| Exposure disclosure | the author has read R-39 (F-LOG-0053, 0098) and saw one matched sentence of candidate B (L182) in a locate pass. Neither was used below |

## 1. Generic ES-006.1 process (each item labelled)
1. **Stages:** Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability. [SOURCE-STATED L17]
2. **Explicit prerequisite:** "everything is promoted because operational evidence demonstrated necessity". [SOURCE-STATED L20] → read as a **necessary** condition. [SOURCE-DERIVED]
3. **Evidence burden:** "the burden-of-proof rule, R-37, applied to promotion" [SOURCE-STATED L20]. R-37: a proposal must carry the evidence; the default is rejection. [SOURCE-STATED R-37 L23]
4. **Role of qualification:**
   - it "sits between research and engineering" [SOURCE-STATED L20];
   - "'no reference architecture needed' is a SUCCESS outcome of a qualification" [SOURCE-STATED L20];
   - so **a successful qualification does not imply promotion**. [SOURCE-DERIVED]
5. **"Promotion Ready":** **not defined in ES-006.** [UNKNOWN] (It is defined only for the PKS chain, which is T1 r0.)
6. **Readiness vs decision:**
   - "engineering **promotes** it" (the actor) [SOURCE-STATED .4 L30];
   - "Authors propose; the authority adopts", with per-item Approve / Reject / Defer [SOURCE-STATED ES-001.2, governance scope].
7. **Is readiness necessary?** The evidence of necessity is (item 2). Qualification is on the path for rungs ≥ Engineering Standard [SOURCE-DERIVED from the ladder order].
8. **Is readiness sufficient?** **Not stated** [UNKNOWN]. Item 4 shows that qualification success is not sufficient. [SOURCE-DERIVED]
9. **Rejection or deferral despite readiness:** Reject and Defer are admissible decision outcomes [SOURCE-STATED ES-001.2]. Whether that holds *after* readiness is not stated. [UNKNOWN]

## 2. T1 r1 predicates (object kind = engineering-knowledge artifact, promoted under ES-006.1)
- **READY_E(x, r→r′)** :⇔ **E1** ∧ **E2** ∧ **E3**, where:
  - **E1** DemonstratedNecessity(x): operational evidence of necessity is *cited in the proposal* [SOURCE-STATED L20 + R-37];
  - **E2** Proposed(x, r′): an explicit proposal exists [SOURCE-STATED R-37 / ES-001.2];
  - **E3** (only when r′ ∈ {Engineering Standard, Stable Engineering Capability}) QualificationCompleted(x) [SOURCE-DERIVED from the ladder order].
- **BAR(x): UNDEFINED in r1.** No generic source states a count or threshold. Therefore **ELIGIBLE(x) := READY_E(x) ∧ BAR(x) is untestable in r1** and is reported as UNDETERMINED.
- **PROMOTE(x, r′)** :⇔ an explicit decision with outcome **Approve** by the adopting authority, moving x to r′ [SOURCE-STATED ES-001.2, ES-006.4].
- **PROMOTED(x, r′)**: the state after PROMOTE. Distinct from **FROZEN** and from **SUPERSEDED** [A4 non-collapse, "frozen ≠ promoted", PKS L220].

## 3. Logical relationships to test (none assumed)

| Relation | Status in r1 | Testable by one case? |
|---|---|---|
| READY_E → ELIGIBLE | untestable (BAR undefined) | no |
| ELIGIBLE → PROMOTE | untestable | no |
| **READY_E → PROMOTE** (sufficiency) | open [UNKNOWN §1.8] | **yes**: READY_E ∧ ¬PROMOTE refutes it |
| **PROMOTE → READY_E** (necessity) | the T1 r1 claim [SOURCE-DERIVED §1.2] | **yes**: PROMOTE ∧ ¬READY_E is a counterexample |

## 4. Frozen falsifiers (r1)
- **F2-E** (counterexample to T1 r1): PROMOTE(x) ∧ ¬READY_E(x), with each Ei decided from released text.
- **F7** (refutes sufficiency, not T1): READY_E(x) ∧ explicit Reject / Defer / no decision → "readiness is not sufficient". This is consistent with T1, which does not claim sufficiency.
- **F8** (the non-collapse test): x recorded as FROZEN and treated as PROMOTED, or the reverse.
- **F1 / F6** (carried over from r0): a governance-status change without an explicit decision.

## 5. Case protocol (fixed now)
- Classify: object kind · starting rung · target rung · E1 · E2 · E3 · READY_E · decision (Approve / Reject / Defer / none / unknown) · PROMOTED · FROZEN.
- Values: YES / NO / UNKNOWN, each with a quote.
- Outcomes:
  - **A** READY_E ∧ PROMOTE → survives;
  - **B** ¬READY_E ∧ PROMOTE → counterexample;
  - **C** READY_E ∧ ¬PROMOTE → readiness not sufficient;
  - **D** undeterminable → untested, name the minimum missing evidence;
  - **E** a source contradiction → preserve both statements; resolve neither by preference.
- A kind mismatch → OUT-OF-SCOPE.
- **No amendment of §§1–4 after the case is read.**
