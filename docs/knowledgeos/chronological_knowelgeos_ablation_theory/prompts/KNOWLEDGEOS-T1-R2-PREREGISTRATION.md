# T1 r2 — norm / observed / explanation ontology — PRE-REGISTRATION (results-free; frozen before P2 is read)

| | |
|---|---|
| Status | **r2, frozen at commit, together with its instrument `analysis/theory/t1r2_check.py`.** T1 r0 and r1 stay frozen as history. No amendment after the first test case is read |
| Replaces | T1 r1's universal claim "PROMOTE ⇒ READY_E" (falsified as a claim about practice by P1, F-LOG-0103). r2 **does not assert it** |
| Built from (released text only) | ES-001.2 (L26) · ES-006 L3/L5 (Status, Scope) · ES-006.1 (L14–21) · ES-006.2–.4 (L22–49) · R-37 (L23) · R-39 (L25) · R-41 (L27) · ES-004.3 (L29–64) · session log 2026-07-30 (L1–41) · Phase-02.6 L32/L155–161 · PKS L216–221 · Layer Verification Rule (header, §4, §6) · ES-006 status history (F-LOG-0104) |
| **Development cases (not tests)** | **R-39, B (Layer Verification Rule), P1 (R-41 / ES-004.3).** r2's structure was learned from them, so running r2 on them is a *sanity check only* and never counts as confirmation |
| Test cases | unread only; the first is **P2 = ES-001.3** (adopted 2026-08-16) |

## 1. Primitives
- **Object** x: `id`, `version`, `kind`, `derived_from`.
- **Rule** ρ: `id`, host document D(ρ), **provenance** prov(ρ) = (authority, date) as stated in the rule's own header (e.g. ES-006.1 "(ARB, refined 2026-07-11)"). [SOURCE pattern: ES-006.1 L14; ES-001.2 L26; ES-004.3 L29]
- **Document** D: Status(D, t) as stated in its header history (e.g. ES-006 PROPOSED in all 7 versions). [SOURCE: F-LOG-0104]
- **Authority set** 𝒜 = {ARB, Decision Authority (DA), Principal Architect (PA), "the Authority"}: the authorities named as decision-makers in released text. [SOURCE: ES-001.2; R-39; R-41; PKS L218]

## 2. Normative layer N (indexed by rule, never by document)
- **Force(ρ, t)** ∈ {IN-FORCE, NOT-IN-FORCE, AMBIGUOUS, UNKNOWN}:
  - IN-FORCE: prov(ρ) is an explicit decision dated ≤ t **and** Status(D(ρ), t) is adopted or ratified;
  - **AMBIGUOUS:** prov(ρ) is an explicit decision dated ≤ t **and** Status(D(ρ), t) = PROPOSED [the F-LOG-0104 situation];
  - NOT-IN-FORCE: no decision provenance for ρ and Status(D(ρ), t) = PROPOSED;
  - UNKNOWN: otherwise.
  - **Force(ρ, t) ≠ Status(D(ρ), t).** These are two predicates, never merged.
- **Applicability(ρ, x)** ∈ {SOURCE-STATED, SOURCE-DERIVED, NOT, UNKNOWN}, decided only from ρ's (or D's) scope text.
- **Normative promotion requirements of ES-006.1** (kind: engineering knowledge; bar excluded, as in r1):
  - **R-ev:** NecessityEvidence precedes Promote [ES-006.1 L20];
  - **R-q:** Qualification or Validation precedes Promote if the target rung ≥ Engineering Standard [ES-006.1 L17 order];
  - **R-dec:** Promote is an explicit decision by a named a ∈ 𝒜 [ES-001.2];
  - **R-prop:** Proposal precedes Promote ("Authors propose; the authority adopts") [ES-001.2].

## 3. Observed layer O
- A trace τ is an ordered event list. Each event carries (a) a provenance label (SOURCE or READING) and (b) an order basis (a date, an append-only position, or "unordered").
- **Events:** Proposal · NecessityEvidence · Qualification · Validation · ValidationExpected · Decide(outcome ∈ {Approve, Reject, Defer}, a) · Promote(target, a) · FreezeAuthor · FreezeGovernance · Supersede · Refine · ExceptionRecorded · StatusChange.
- **FreezeAuthor ≠ FreezeGovernance** [SOURCE: Layer Verification Rule header vs Phase-02.6 L32].
- **ProvisionalPromote** := Promote followed by a recorded ValidationExpected obligation [SOURCE pattern: R-39 "Expected validation"]. It is a transition type, **not** a deviation by itself.

## 4. Conformance, per (τ, requirement r of ρ)
- **OUT-OF-SCOPE** if Applicability = NOT.
- **VACUOUS** if τ has no Promote.
- **NOT-RECORDED** if r fails *only* because an event is absent from τ. **Absence is never a deviation.**
- **UNDETERMINED** if a needed attribute (e.g. the target rung) is UNKNOWN.
- **DEVIATES** if τ positively records an order or attribute that contradicts r (e.g. the only recorded Validation comes *after* Promote).
- **CONFORMS** otherwise.

## 5. Explanation layer (only for DEVIATES), in fixed order
1. **EXCEPTION-RECORDED:** an ExceptionRecorded event covers the promotion;
2. **FORCE-NOT-IN-FORCE:** Force(ρ, t_promote) = NOT-IN-FORCE;
3. **FORCE-AMBIGUOUS:** Force(ρ, t_promote) = AMBIGUOUS;
4. **VERSION-DIFFERENCE:** ρ's text at t_promote demonstrably differs from the text tested (needs rule history; otherwise not assignable);
5. **UNEXPLAINED.**

## 6. Claims of T1 r2 (falsifiable; each a predicate over a released trace)
- **C1 decision mediation:** every StatusChange or Promote in O has an explicit Decide/Promote by a named a ∈ 𝒜. *Falsified by* a status change with no decision.
- **C2 non-collapse:** FreezeAuthor is never treated as Promote; READY (derived) is never recorded as a decision. *Falsified by* such a record.
- **C3 governed deviation:** every DEVIATES against a rule with Force = IN-FORCE is EXCEPTION-RECORDED. *Falsified by* DEVIATES ∧ IN-FORCE ∧ UNEXPLAINED. (DEVIATES under AMBIGUOUS or NOT-IN-FORCE force does **not** falsify C3; it is reported.)
- **C4 authority parameter:** every Promote names an authority in 𝒜. *Falsified by* a Promote naming none. (An authority outside 𝒜 is reported as NEW-AUTHORITY, not as a falsification.)
- **Not claimed:** readiness sufficiency; universal necessity (PROMOTE ⇒ READY_E); the evidence bar.

## 7. Test protocol (fixed now)
- **Input:** one unread case, read under an L0 release, encoded as a trace τ with provenance labels and order bases **before** the checker is run.
- **Per claim:** TRIGGERED / NOT-TRIGGERED / UNDETERMINABLE. **Per requirement:** the §4 class plus the §5 explanation.
- Required reports: Applicability; Force(ρ, t) together with Status(D, t); every READING-labelled dependency.
- **No amendment of §§1–6 or of the instrument after the first test case is read.**
