# Hypothesis discrimination 03: the L493 experiment and the route hypothesis H6

| | |
|---|---|
| Status | research record; not canonical; authority none |
| Case | `.claude/sessions/2026-08-15.md` L483–L495 (`b5c2487c…`). The spec was frozen before the read (`9e610e412`, `e516e174…`); observation `L493-CASE/OBSERVATION.json` (`ca015569…`); result `RESULT.json` (`e1b579e4…`) |
| Instrument | `model_discrimination_r2.py` (`d14d79af…`), unchanged |
| Log | F-LOG-0115 |

## 1. Source findings (SOURCE-FACT)
The section records two acts by the same authority (PO/ARB), on the same day.

**Act A: binding vocabulary ruling (L486–491).**
- Closure must always be stated with its plane named. The ruling "applies to ALL future documentation".
- It was "applied immediately", including to existing artifacts.
- **No evidence basis is stated.** The occasion was one instance (a headline), but the section does not present that instance as the ruling's evidence.

**Act B: non-promotion (L492–493).**
- The PO/ARB observation "three distinct state planes, which are not one state machine" was **"NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one"**.
- Registered for a work item "if and when it is commissioned".

**Plural state (L487–488, L492).**
- The same item is "documentary closure: CLOSED" and "machine state: OPEN" at the same time.
- There are three planes: execution (machine-authoritative) · governance lifecycle (documentary) · findings (documentary).
- The source's own warning: "The danger would be pretending they are one state machine."

**Non-mandate (L485, L493–494).**
- Acceptance "is not a reason to reopen".
- Unifying the planes is "architecture work nobody is yet authorized to perform".
- "Nothing reopened. Nothing commissioned."

## 2. Instrument result (MODEL-DERIVED)
- **Act A:** only AUTH is PRESENT; every ordering component is NOT-RECORDED; force is UNKNOWN (the section does not say whether a vocabulary ruling falls under I-R39). → **No hypothesis is eliminated under any norm reading.** The realized MDS-U is 0.
- **Act B** is a Reject. Every Promote hypothesis permits it, and it sits outside r2's scope (r3.1 is OUT-OF-SCOPE for Reject acts).
- **H4 remains untested.** No deviation was observed under IN force.
- **Surviving:**
  - under QUAL and QUAL∨VAL: all six hypotheses (H1, H2, H3a, H3b, H4, H5);
  - under VAL and QUAL∧VAL: H2, H3a, H3b, H4, H5.

## 3. Interpretation layer (MODEL-INTERPRETATION on SOURCE-FACT)
- Act B attributes a repeated-evidence bar to ES-006.1. ES-006.1's text has no such bar (F-LOG-0107).
- The wording ("from one", "single occurrence") matches **I-CLAUDE** (instances ≥ 2; NON-NORMATIVE host), not I-R39 (contexts ≥ 2).
- r3 pattern: **T ≠ I ∧ O ⊨ I** (practice follows the interpretation drift). This is a new genealogy point, 2026-08-15, between the 08-01 and 08-16 points.
- It is also an instance of **H-circ**: a disposition grounded in a non-eligible interpretation, attributed to the text.

## 4. New hypothesis H6: two routes to normativity (HYPOTHESIS)
**Formal statement.** route(act) ∈ {RULING, PROMOTION}.
- RULING: an authority decides a rule directly.
- PROMOTION: an observation or candidate advances on the knowledge ladder.
- H6: PROMOTION ⇒ the evidence bar is met, or an exception is recorded. RULING ⇒ AUTH only; no evidence bar is required. AUTH is required on both routes.

**Why it is proposed: the L493 minimal pair.**
- A and B share the authority, the date, the section and the force status.
- Only the route differs: a rule decided directly vs an observation offered for promotion.
- A binds everything on no recorded evidence. B is refused on one occurrence.
- **Confound (disclosed):** in this pair, route coincides with the object type (rule vs observation). The pair cannot separate "act route" from "object is an observation".

**Model-integrity check (provisional).**
- Orthogonal to kind: a ruling can concern knowledge or work.
- Orthogonal to force: A and B have the same force status.
- Necessary: kind, force and authority do not separate A from B.
- Sufficient: see the retrodiction below.

**Retrodiction.** These are post-hoc fits to development and earlier cases, **not a test**.

| Case | Route (basis) | H6 |
|---|---|---|
| P1 (R-41 / ES-004.3) | RULING: "adoption by explicit PA instruction" (F-LOG-0102; READING) | consistent: the "deviation" dissolves |
| R-100 | RULING (register row) | consistent: no evidence recorded, none required |
| L493-A | RULING | consistent |
| L493-B | PROMOTION refused on one occurrence | consistent: the bar is applied |
| P2 (ES-001.3) | ARB adoption on 8 instances | consistent under either route |
| R-39 | ruling that **records an exception** | consistent, but H6 does not explain why a ruling would need an exception (tension, weak) |
| L216 | UNKNOWN | — |

**Falsifiers.**
1. A PROMOTION from one occurrence with no recorded exception.
2. A RULING refused or deferred solely for insufficient evidence.
3. A case that separates route from object type and finds that object type, not route, decides.

**Relation to H3b.** H3b places the split on the object's kind (knowledge vs work). H6 places it on the act's route. If H6 survives a test, it may be the correct form of the "adoption means different things" idea.

## 5. Further structural observations (one occurrence each)
- **H-planes (HYPOTHESIS):** the state of a governed item is a **vector over planes** (execution / governance lifecycle / findings), not a scalar.
  - This is authored by PO/ARB and explicitly not promoted.
  - It supports modelling several transition systems rather than one promotion machine, and is consistent with Force ≠ Status and event ≠ state.
- **Temporal scope:** a rule stated as prospective was applied at once to existing artifacts. This bears on the persistence / retroactivity question in the transition-system programme.
- **Non-mandate** recurs: acceptance ≠ reopening, and observation ≠ authorization. This is the third occurrence of the completion ≠ mandate family.

## 6. Next case (MODEL-DERIVED)
**Recommended: 2026-08-04 L205 "Meta-principle freeze adopted; the protected sentence recorded".** It can test two things at once:
- **H4:** it is principle-level, dated after I-R39 (07-26), and inside I-R39's "methodology principles" scope, so force is IN if that scope applies.
- **H6:** a principle adoption is a PROMOTION-route candidate, so H6 predicts the evidence bar is met or an exception is recorded.

A RULING-route case (e.g. the 08-17 L170 fork ruling) would test only falsifier 2.
