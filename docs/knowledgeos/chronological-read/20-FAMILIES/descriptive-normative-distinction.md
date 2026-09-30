# descriptive-normative-distinction

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D(x) !=> N(x)`, `I_71`, `I_72` · **Aliases:** `is/ought architectural version`
**Candidate group membership (NOT an identity claim):**
- **G1061** [`descriptive-normative-distinction` · `descriptive-normative-layer-distinction`] — working_label token overlap Jaccard=0.75 (shared tokens: ['descriptive', 'distinction', 'normative'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 201's Descriptive-vs-Normative distinction (an architectural is/ought separation), invariant I_71 (descriptive observations must not be silently promoted to normative rules) and I_72 (observed frequency must not be interpreted as normative validity without an explicit policy transition), directly protecting against accidental AI-derived governance.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1420] §"Policy = Allowed + Forbidden + Conditional. Thus governance is not merely: CanDo(x). It also includes: MustDo(x) and: MustNotDo(x)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1420] §"Policy = Allowed + Forbidden + Conditional. Thus governance is not merely: CanDo(x). It also includes: MustDo(x) and: MustNotDo(x)."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1420. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1420 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1420 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1420 |
| Examples | PRESENT | S1420 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1420] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Restates governance policy as Allowed+Forbidden+Conditional (not merely a permission whitelist), including explicit MustDo and MustNotDo obligations alongside CanDo permissions." (anchor: "Policy = Allowed + Forbidden + Conditional. Thus governance is not merely: CanDo(x). It also includes: MustDo(x) and: MustNotDo(x).")
- [S1420] types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Introduces the Descriptive ('what is happening?') vs Normative ('what should happen?') distinction as an architectural version of the is/ought problem: a descriptive proposition D(x) never implies a normative rule N(x)." (anchor: "Descriptive versus: Normative. ... D(x)\not\Rightarrow N(x). Knowing what is does not automatically determine what ought to be.")
- [S1420] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "New invariant I_71: descriptive observations must not be silently promoted to normative rules, illustrated by an AI observing a behavioral regularity ('most teams do X') and wrongly inferring a normative rule ('teams should do X')." (anchor: "I_{71}: Descriptive observations must not be silently promoted to normative rules. ... 'Most teams do X' must not automatically infer: 'Teams should do X.'")
- [S1420] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "Distinguishes what machine learning learns (P(Y|X), a statistical regularity) from what governance defines (Allowed(Y|X), a normative rule); new invariant I_72 forbids interpreting observed frequency as normative validity without an explicit policy transition -- a strong protection against accidental AI governance." (anchor: "Statistical regularity\neq Governance legitimacy. ... I_{72}: Observed frequency must not be interpreted as normative validity without an explicit policy transition.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
