# Phase A: findings baseline (traceability before any M2 work)

*Status: complete for Phase A. **No finding is marked resolved here.** A change of code or a passing test does not establish resolution. The "resolution evidence" column states what **would** establish it.*

**Label codes:**
- **Origin:** RV = independent review (`REVIEW-RECORD.md`); V1 = original v1 defect; NEW = additional problem.
- **Type:** MATH = mathematical defect · MEAN = change of meaning · FID = source fidelity · TERM = terminology · POL = policy question.
- **"Reproduced"** = re-derived in the author's own encoding.

## 1. The original six v1 defects (confirmed by the review)

| ID | Severity | Claim or formula under review | Counterexample or derivation | Type | Required correction | Resolution evidence | Depends on |
|---|---|---|---|---|---|---|---|
| V1-a | high | "the least fixed point exists and is unique (Knaster–Tarski)" (v1 L135) | mutual annulment under υ = "does not count", ρ = now: iteration oscillates; the fixed points (T,F) and (F,T) are incomparable (computed, `witnesses_v1.py` W1; the reviewer computed it too). The info order has no top. **Bites only under ρ = now or nested views** (review) | MATH | a monotone operator, or a stratified definition; cite Kleene | a proof of monotonicity, or stratification for every υ value retained | υ semantics (RD 6), ρ (RD 2) |
| V1-b | high | regress "stops at the root"; safeguard 2 | a grant issued by an act citing nothing still works (W4) | MATH | grants as products of checked issuing acts | a proof that every T authorization chains to a root act; a regression case | C1 (κ_g) |
| V1-c | high | case 11: the parent's ending reaches the child "through P" | P acts only on claims (condition 4), never on a grant window (W5) | MATH | delegation in the formula, separated from independent authority | proofs for both cascade options; regression cases | RD 1 (P, cascade) |
| V1-d | high | Property 1 (totality) | (i) no least fixed point (V1-a); (ii) C1-b with no adoption: value F, headline "cannot determine"; (iii) annulled adoption: no reason; (iv) **covers undefined**, so every non-root citation is undefined; (v) ω = intersection ill-typed | MATH | the value and the headline from one computation; covers defined; ω handled | a proof of totality over the stated domain | V1-a, F-12, F-13, F-19 |
| V1-e | medium | case 3, "expired permission" | the recorded `to` never closes the window; π is unused | MATH | the period inside the window; π wired in | a proof plus boundary cases under both conventions | interval convention (policy) |
| V1-f | medium | "all comparisons of acts use the total order"; ρ; the timing note | (1) window in act time vs order (t, s) gives a same-moment race; (2) ρ not wired into conditions 1 and 3; (3) nested views unspecified; (4) **"at the time" = at x's recording, not its act** (review); (5) **"now" = the record at evaluation, K not an argument** (review); (6) the timing note contradicts itself for an ending dated before x but recorded after it | MATH / MEAN | explicit domains and comparisons; a record-state argument; the view defined | a proof that every comparison is domain-correct; cases per view | RD 2 (ρ) |

## 2. Additional problems

| ID | Severity | Claim | Evidence | Type | Required | Resolution evidence | Depends on |
|---|---|---|---|---|---|---|---|
| NEW-1 | major | v1 Property 2: adding an adoption or a basis never turns T into F | ρ = now: a late-recorded, earlier-dated adoption consumes the single-use grant: T → F (`prop2_check.py`) | MATH | restate with scope | a proof of the restated property; regression case | ρ, consumption |
| NEW-2 | major | the timing note covers late-recorded endings | an ending dated before x, recorded after it, never reaches x's product | MATH / POL | define the reach of unseen endings | cases under each option | RD 2 |
| NEW-3 | minor | v1 case 4: "if another act consumed it, the result depends on P" | vacuous for single-use grants | MATH | restate (meaningful only for quota grants) | — | F-21 |
| RV-C1 | minor | delegation conditions are all [DER/ASM] | "narrower boundary" and "not transferable — a new act is required" are **source-stated** (AMS, via the review; AMS L190) | FID | re-attribute | source re-read by the author | — |

## 3. The 21 review findings on M1

| ID | Sev. | Claim or formula | Counterexample or derivation | Type | Required correction or investigation | Resolution evidence | Depends on |
|---|---|---|---|---|---|---|---|
| F-1 | major | W4 = ⋁ over parent bases; root-issued: parents ∅ | ⋁∅ = F: under δ = yes nothing is authorized (the encoding hid it) | MATH | parents(root) = {∅} | a proof that a root-issued grant's W4 = T; regression case | — |
| F-2 | major | valid(y) uses A(z) for annulments | annulment of an annulment of a revocation: "current" instead of "revoked" (**reproduced**) | MATH | recurse on valid(z) | a proof that the chains are well-founded or in the fixed point; regression | — |
| F-3 | major | υ stage 2 suppresses all U-endings | changes a well-defined v1 answer F → T (**reproduced**) | MATH / MEAN | a suppression rule that agrees with v1 where v1 is defined | a proof of agreement with v1's stratified semantics under at-the-time; regression | RD 6 |
| F-4 | moderate | υ covers endings only | an unverifiable use freezes a single-use grant | FID / POL | decide whether υ covers consumption | cases | RD 6, consumption |
| F-5 | major | effective "by t": the embedding of t is unstated | (t, −∞) flips v1 case 12 (revoked → current); the encoding used (t, +∞) silently | MATH / MEAN | state the embedding; check against v1 case 12 | derivation from v1's cases, or a decision | possibly derivable from v1 (to be investigated) |
| F-6 | major | own view v(x) = s_x | x's validity depends on its own recording delay | MATH / POL | compare the views (Phase C) | Phase C comparison | RD 2 |
| F-7 | moderate | late endings count from recording time; late adoptions from act time | an unstated bias toward "current" | POL / MEAN | symmetric treatment or an explicit choice; investigate a bitemporal question (t, k) | cases | RD 2 |
| F-8 | moderate | single-use under at-the-time | two authorized uses of one single-use grant (**reproduced**) | POL | explicit rule | cases per option | RD 2, consumption |
| F-9 | minor | "use of g" | undefined for alternative bases | MATH / POL | define debit through the satisfied base | case | consumption |
| F-10 | moderate | covers for issuance; order of checks; ω | no activity containment; holder-U overrides a definite F; ω dropped | MATH / FID | Act(child) ⊆ Act(parent) (supported by "narrower boundary"); Kleene order; ω | cases; source check | delegation |
| F-11 | major | κ_g "tied to C1", no values | F inverts C1-a; is the human-act reference field the issuing act? | FID / POL | values per C1; resolve the reference-field question | cases per C1 | RD (C1), today's data |
| F-12 | major | ε, "no adoption on record" | ambiguous; one reading breaks T4 | MATH | define as "no adoption with act time ≤ t" | totality proof | C1 |
| F-13 | moderate | headline over the union of reasons | "not validly made" for a claim that counted and was revoked (also in v1) | MATH / POL | per-disjunct aggregation, or keep the union as an option | proof plus case | C8 |
| F-14 | major | Property 2′ "adding a basis" | T → F under at-the-time (**reproduced**) | MATH | restrict to adding adoption acts (the reviewer's proof) | a proof of the restricted statement; regression | — |
| F-15 | major | Property 4 carries over | views differ with no late entry (**reproduced**); the premise is circular | MATH | restate with a static dependency premise, or withdraw | proof or counterexample | ρ |
| F-16 | minor | T3: "cyclic issuance stays U" | mostly F; U only in the degenerate root case | MATH | correct the statement | proof | — |
| F-17 | minor | T1 "unique" | unique **least** fixed point; inherits F-1; the view in the C layer is unspecified | TERM / MATH | wording; specify the view | — | F-1 |
| F-18 | — | [SRC] merges book and source; λ used twice; second numbering; unverifiable references; half-open fixed while called reserved | — | TERM | split [BOOK]/[SRC]; rename; map onto reserved decisions; parameterize the convention | review of the text | Phase D |
| F-19 | moderate | ω silently whole-or-nothing | per-atom evaluation is possible; T4 then per atom | FID / POL | a scope argument, or an explicit exclusion | proof per atom | — |
| F-20 | moderate | C6 "later prevails" | addable monotonically; T4 needs a reason; 2′ must be scoped | MATH / POL | parameter plus reason | proof plus cases | C6 |
| F-21 | major | quota grants | at-the-time over-consumes; 2′ fails; U-uses freeze the quota | MATH / POL | Kleene threshold; debit rule; υ for uses | proof plus cases | consumption, RD 2, RD 6 |

## 4. Dependency summary

Policy questions that recur across findings:
- **ρ / reserved decision 2:** V1-a, V1-f, NEW-2, F-6, F-7, F-8, F-15, F-21;
- **υ / reserved decision 6:** V1-a, F-3, F-4, F-21;
- **P / reserved decision 1:** V1-c (cascade);
- **C1:** V1-b, F-11, F-12;
- **consumption (C13):** F-4, F-8, F-9, F-21.

**Purely mathematical:**
- F-1, F-2, F-12, F-14, F-16, F-17;
- the "defined" part of V1-b, V1-d, V1-e and F-10;
- possibly F-5, if v1 case 12 determines the embedding.
