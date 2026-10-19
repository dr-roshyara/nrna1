# Independent review record: §1.14.1 v1 and candidate M1 (2026-10-18)

## About the review

**Reviewer.**
- A fresh read-only session of the same model family; not an external expert.
- It worked from the review packet only, and checked the baseline hash (match).
- It did not use the author's encodings as evidence, and it did not read the correction register, V3, AB or Chapter 2.

**Verdicts.**
- v1: reconstruction DEFECTIVE; Property 1 DEFECTIVE; reported defects (a)–(f) all **confirmed**.
- M1: **DEFECTIVE**.
- Theorems:
  - T1: SOUND WITH MINOR ISSUES;
  - T2: DEFECTIVE in semantics, but existence holds;
  - T3: SOUND WITH MINOR ISSUES;
  - T4: DEFECTIVE;
  - Property 2′ and Property 4: DEFECTIVE.
- Encodings: DEFECTIVE as evidence.

**How the findings were checked.**
- Every finding was judged by reasoning.
- Five witnesses (F-2, F-3, F-8, F-14, F-15) were **reproduced** in the author's encoding (`review_checks.py`): all five hold.
- No finding is rejected. Agreement between the author and the reviewer is not counted as validation; the reproductions are what count.

## A. On v1: what the review adds to or corrects in the proposal

| Item | Disposition | Effect on the proposal |
|---|---|---|
| (a) non-monotone υ bites **only under ρ = now** (or a nested-view reading). Under at-the-time with own views the system is stratified, so it has a unique solution even with the non-monotone map | **accepted** | sharpens §5.1: v1 is well defined under at-the-time with own views. v1's "no cycles under at-the-time" holds only for the own-view reading, which v1 leaves unresolved |
| Property 1 is also **undefined for every non-root citation**, because covers is undefined, and **ill-typed** under ω = intersection | **accepted** | adds two failure classes to §4 |
| Annulled adoption mapped to "not validly made" would be false (the act was validly made, then voided) | **accepted** | confirms the separate "annulled (void)" reason |
| **New conflation:** "at the time" means *x*'s **recording**, not *x*'s act. Case 10 silently assumes the act was recorded before the revocation | **accepted** | joins F-6 / Q2 |
| **New conflation:** "now" is the record at the moment of evaluation; K is not an argument | **accepted** | already in M1 (record-state argument); confirms it |
| **Delegation fidelity:** the source says delegation "permits another actor to act within a **narrower boundary**", and that authority is "not transferable: a new act is required" (AMS L190) | **accepted: new evidence** | scope narrowing is **partly [SRC]**; the proposal under-attributed it. AMS L190 supports modelling delegation as a **new act** (as M1 does) |

## B. Findings on M1 and its theorems

| # | Finding (short) | Severity · type | Verified by author? | Disposition | Required change |
|---|---|---|---|---|---|
| F-1 | root-issued grant: ⋁ over an empty parent set = F under δ = yes, so nothing is authorized | major · math | by reasoning (the encoding hid it) | **accepted** | parents(root) = {∅} |
| F-2 | annulment uses A(z), not valid(z): only one level works | major · math | **reproduced**: annulled annulment of a revocation gives "current" (should be "revoked") | **accepted** | recurse on valid(z) |
| F-3 | stage-2 υ suppresses endings that are U only because of other suppressed endings, and changes a well-defined v1 answer (F → T) | major · math/fidelity | **reproduced** (M1 gives T) | **accepted**. The reviewer's positive result is accepted too: stage-2 lfp ⊒ stage-1 lfp, so definite values never flip | choose the υ semantics: Q7 |
| F-4 | consumption is not covered by υ, so an unverifiable use can freeze a grant | moderate · fidelity/policy | by reasoning | **accepted** | Q6(d) |
| F-5 | how the question instant *t* embeds into points is unstated; (t, −∞) flips v1 case 12 | major · math/fidelity | by reasoning; the encoding used (t, +∞) silently | **accepted** | state it: Q11 |
| F-6 | own view makes *x*'s validity depend on how late *x* itself was recorded | major · math/policy | by reasoning | **accepted** | Q2: own view · as of τ ≤ *t*ₓ · now |
| F-7 | late endings count from recording time, late adoptions from act time: an unstated bias toward "current" | moderate · policy | by reasoning | **accepted** | Q3: symmetric treatment |
| F-8 | at-the-time allows two authorized uses of a single-use grant | moderate · policy | **reproduced** (both T) | **accepted** | Q6(e) |
| F-9 | "use of g" is undefined for alternative bases | minor · policy | by reasoning | **accepted** | Q6(b) |
| F-10 | issuance lacks activity containment; holder-U overrides definite mismatches; ω dropped | moderate · math/fidelity | by reasoning (the encoding does exactly this) | **accepted** | Act(child) ⊆ Act(parent); Kleene order of checks; ω as Q9 |
| F-11 | κ_g has no values, and F inverts C1-a; is the human-act reference field "the issuing act"? | major · fidelity/policy | by reasoning | **accepted** | Q1 |
| F-12 | ε ambiguous ("no adoption ever" vs "none by *t*"); one reading breaks T4 | major · math | by reasoning | **accepted** | Q13; restate ε |
| F-13 | headline aggregation over the union of reasons gives "not validly made" for a claim that did count and was then revoked (v1 has this too) | moderate · math | by reasoning | **accepted** | Q8: per-disjunct best progress |
| F-14 | Property 2′ "adding a basis" fails under at-the-time (T → F) | major · math | **reproduced** | **accepted**. The "adding an adoption act" half survives, with the reviewer's proof | restrict 2′ to adding adoption acts; scope it again for C6 |
| F-15 | Property 4 unproven under M1; it fails under M1's own record-time timing; the premise is circular | major · math | **reproduced** (now F, at-the-time T) | **accepted** | withdraw Property 4 for M1, or restate with the timing rule |
| F-16 | T3: "cyclic issuance stays U" is mostly false (one issuing act becomes F); U only in a degenerate root case | minor · math | by reasoning | **accepted** | correct T3's statement |
| F-17 | T1 sound; "unique" means unique **least** fixed point; inherits F-1; the view in the C layer is unspecified | minor · math | by reasoning | **accepted** | wording |
| F-18 | [SRC] merges baseline and source statements; λ is used twice; D-1…D-7 is a second numbering beside v1's reserved decisions; packet references could not be verified; M1 fixes half-open while calling it reserved | terminology | by reasoning | **accepted** | split [SRC] into [BOOK]/[SRC]; rename λ_e; map the D-items onto the reserved decisions; parameterize the interval convention |
| F-19 | ω: no theorem breaks, but it is silently whole-or-nothing; per-atom evaluation possible; T4 must then be per atom | moderate · fidelity | by reasoning | **accepted** | Q9 |
| F-20 | C6 "later prevails" can be added monotonically, but T4 needs a new reason, and 2′ must be scoped against conflicting claims | moderate · math/policy | by reasoning | **accepted** | Q14 |
| F-21 | quota grants: a Kleene threshold keeps T1/T2. At-the-time over-consumes (all 4 uses authorized on a quota of 2); 2′ fails; U-uses freeze quota | major · math/policy | by reasoning | **accepted** | Q6 |
| encodings | `model2.py`'s fixed point "converges" to (T,T), **which is not a fixed point**; `m1.py` derives recording time from record position (the forbidden comparison); and others | — | the (T,T) claim checked by reasoning: (T,T) gives A1 = ¬e(T) = F, so it is not a fixed point | **accepted** | encodings are not evidence; see the lesson below |

## C. What survives

- **T1:** a monotone operator on a finite pointed poset gives a unique **least** fixed point, reconstructed independently by the reviewer.
- **T3:** stratification by record position under own views, reconstructed independently.
- **T2:** existence, with the reviewer's refinement result.
- **The "adding an adoption act" half of Property 2′ under at-the-time,** proved by the reviewer.
- **v1's six defects:** all confirmed independently.

## D. What does not survive

- **M1 as written:** four silent meaning changes (F-3, F-5, F-7, F-11) and new defects (F-1, F-2, F-12, F-13).
- **T4 and Property 4 as stated.**
- **Property 2′ as stated.**
- **The decision brief.** It has seven decisions; the review shows **fourteen** genuine policy questions (Q1–Q14), and the brief uses a numbering that collides with v1's reserved decisions (F-18).

## E. Lesson (recorded once more; this time it is the author's own repair)

- The author's encodings hid **eight** defects of the author's own candidate. The causes were:
  - a convenience branch (`if iss.get('cited')`);
  - a default (`rt = s`, i.e. recording time = record position);
  - check ordering;
  - an unstated question-instant embedding.
- A 33/33 pass rate was evidence only of agreement with the author's expectations.
- The only reliable checks were reasoning and an independent reconstruction.
