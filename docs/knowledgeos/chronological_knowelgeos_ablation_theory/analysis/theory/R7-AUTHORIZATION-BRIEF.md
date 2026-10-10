# R7 authorization brief: READINESS ONLY (draft r3)

**Status:** draft r3 for a human decision, 2026-10-10. r1 and r2 were each reviewed PASS_WITH_LIMITATIONS, and every required change is applied (`LOOP-1BS/REVIEW-R7-r1.md`). **This document does not authorize R7.** It starts no DDD discovery or design: no aggregates, bounded contexts, ubiquitous-language decisions, domain events or architectural commitments.

**Provenance tags:**
- **[C]** corpus evidence (frozen R3 record);
- **[F]** formal derivation (R4 r3.1);
- **[T]** test-supported (finite enumeration, R5/R6);
- **[I]** interpretation-dependent;
- **[E]** expert-derived design hypothesis;
- **[U]** unresolved.

Agreement among Claude models is **not** independent evidence.

## 1. Verified state
- **R1:** closed. H5 was adopted with the F-LOG-0190 condition **knowingly waived** (F-LOG-0197; D02).
- **R2:** closed, negative (F-LOG-0204, F-LOG-0217).
- **R3:** frozen (F-LOG-0226). 53 FROZEN, 38 QUARANTINED, B10 NOT FROZEN. B09 is superseded as reproducibility evidence. E01 is a same-family verdict with n = 1 and a failed sealed expectation.
- **R4:** sealed at r3.1 (F-LOG-0240).
- **R5:** closed (F-LOG-0226).
- **R6:** closed (F-LOG-0247). Cross-family **waived, not satisfied** (F-LOG-0245).
- **Path A (F-LOG-0247):** H-D vs H-B is non-identifiable within the authorized corpus. E-A5 / Protocol C is **not authorized**; H1 is not reopened.

**Limitations Λ1–Λ7.** They travel with every conclusion and are never strengthened. (They are named Λ to avoid a clash with the R3 evidence levels L0–L3.)
1. Cross-family validation was waived, not satisfied.
2. H-D and H-B are neither falsified nor validated.
3. Non-identifiability is bounded to the authorized corpus and depends on H5, whose condition was waived. The waiver stays visible.
4. D1's strong form is reading-relative.
5. The weak form holds only in the O-form, with the R-j (ii)-O exception. R-a is untested.
6. The evidence covers only the finite universes U and U_P2, and one corpus.
7. Every reviewer and every successful independent run is Claude-family.

## 2. What R7 could derive, by evidence class
| # | Conclusion | Class | Scope and qualifiers | Strengthened by |
|---|---|---|---|---|
| K1 | **R4's primitives are jointly satisfiable in the finite universes:** histories with actors, capability, issue/adoption acts, norm state σ, work events, records with reference and constitution; the three times d, r, f | satisfiability: [F]+[T] in U/U_P2. **Adequacy to the domain: [E]/[I]** | The definitions are **modelling choices** (Def/Asm) derived from HYP and corpus items. **d and r are equated under constitution (F2, Asm); f is never equated with d or r (Def 1.9).** **The names are not canonical terms (N8)** | V6 (does a second corpus need the same primitives?) |
| K2 | **D1 (Prop 4.1(i)):** under A1, A2 and A5, records-only observation does not separate H-D from H-B | [F] proof; [T] in U and U_P2 | Generic under its hypotheses. Strong form reading-relative (Λ4). O-form only, with the R-j (ii)-O exception; R-a untested (Λ5) | V2 (R-a); proof review outside the Claude family (Λ7) |
| K3 | **Lem 4.2:** the non-separation persists even with perfectly reliable reference and mode coding, i.e. for an observation of all attributes **except σ and f** | [F]; [T] | Reading-scoped (Rem 4.2s): it holds for the Def 3.1 readings and readings with property M (Cor 9.3; its (ii) part also needs M_q^D, N_q). It fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′ and R-j | proof review outside the family; V2 for R-a |
| K4 | **Cor 4.3: a NECESSARY condition** for separation: an observable that depends on σ or f. **Props 4.5 and 4.6: SUFFICIENT, not necessary,** conditions | [F] | Cor 4.3 is reading-scoped (Rem 4.3s) and does not derive H5 §5.2's further conditions. Props 4.5 and 4.6 rest on **external realization claims** (Rem 4.5b, 4.6a): an instrument would have to exist | an observable channel (E-A5, not authorized) |
| K5 | **Two independent limitations:** structural non-identifiability (D16) and instrument unreliability (F17) | [F] by Lem 4.2; [C] F17 | D16 is an R3 **level-L3 formal pointer** within R4's model, under A5. It is not an empirical restatement | (none) |
| K6 | **Non-identifiability of H-D vs H-B on this corpus** | [C]+[F] via H5; human act (path A) | **Carries Λ3, Λ4 and Λ5:** corpus-bounded with the H5 waiver; reading-relative; O-form only | E-A5 (unauthorized); V6 |
| K7 | **The act/norm (performative) layer is not reliably codable on this corpus** | [C] for the R2 negative result and F17. "Even as a binary distinction" comes from IF/O6. **IF is not an R3 item**, so that clause is [F]-cited, not [C] | Within the Claude family; cross-family waived (F-LOG-0201). **No ground truth exists** (F-LOG-0217) | a non-Claude coder; V6 (which may hit the same failure) |
| K8 | **Performative ambiguity** (Def 5.1, Lem 5.2) is formally defined | [F] | **Conj 5.5** is a conjecture. It is **non-identifiable on existing data (circular)** (F-LOG-0225, R6 matrix r1) | V5 with new blind data |
| K9 | The finite R5/V1b/slice values reproduce across Opus 5.5 and Sonnet 5.5 | [T] within-family | Shared interpretation untested (Λ1, Λ7). **Bundle-02:** blind content only 42 units; **licence NOT granted** (191 INCOMPLETE units and 1,425 keys mapped by hand); A9's con-only/d-only split not cross-confirmed; **39 (c) disagreements accepted by human ruling** (F-LOG-0242). **Slice:** 67/67, structurally convergent at (iii)-L | a non-Claude run (waived) |

### 2a. Finite-only results (no generic statement rests on them)
- **Rem 9.5:** the V1b per-reading results. U only, one target, Claude-family, predictions not independent.
- **The 4 interpretation-sensitive slice quantities:** (iii)-L n_cex under R-g, R-g⁺ and R-h; the R-h least counterexample.
- **Uncalibrated U values:** U (i)-S and (ii)-S scope sizes and counterexample counts.
- **Rem 9.7:** under R-d, the relevance of the non-separation rests on Cap being unobserved. The SC1/SC2 recovery is **not finite-verified**.
- **Every slice and bundle-02 value.**

## 3. What must NOT become a domain invariant, business rule, aggregate constraint or canonical term
- **N1.** That norms change only through acts (H-D), or that work drives norm change (H-B) (Λ2).
- **N2.** That a record *is* the act it records (constitution). It is not reliably codable (K7), and it is unobserved in R4 (Obs O6).
- **N3.** Equating **f** (entry into force) with d or r. Also, **reading d off r**: F2 equates d = r only under constitution, which is unobserved (Rem 1.9a). Recording time would be a lower bound on f under H-D **only if con were observed (O⁺) and all effective adopters were constituted** (Prop 5.4(b)). On the actual O, con is unobserved, so **no such bound is available**.
- **N4.** Interpretation-sensitive results:
  - D1's reading-relative strong form;
  - §2a's sensitive quantities;
  - the K4b reading of H-D⁺ (a (c) case);
  - any reading-specific consequence.
- **N5.** R4's **modelling choices** presented as corpus facts (full list in §4).
- **N6.** Quarantined R3 items (38) and B10.
- **N7.** Conj 5.5 as an explanation.
- **N8.** **R4's primitive names** (actor, act, norm state, record, work event, the three times) **as canonical domain terms.** They are model vocabulary. Ubiquitous-language decisions belong to R7, if authorized, and need [E] justification.

## 4. Assumptions DDD would inherit explicitly (labelled; all of them modelling choices)
- **The base frame 𝓗 = F1 ∧ F2 (Def 1.8):**
  - F1 means entry into force;
  - F2 says con ⇒ (ρ, a) ∈ Ref and d = r. This is an **Asm**.
  - The frame assumes no completeness, no norm→work dependence and no time order.
- **A1–A9, as hypotheses of the generic results.** Lem 4.0a shows (O, 𝓗) satisfies A1–A5 and A7–A9. **A6 (P ≠ V) is a parameter, not a property of 𝓗.**
- **SC1 and SC2** for the D-st⁺ reading (Lem 3.7). Under R-d, relevance also needs Cap to be unobserved (Rem 9.7).
- **Rem 0.1 exclusions (complete list, from R4 Rem 0.1).** None of these is modelled:
  - conditional norms and provisos;
  - **norm content beyond "target ↦ value"**;
  - hold, withdraw, supersede and annotate as separate act kinds (supersede and withdraw are issue acts; hold and withdrawal before adoption are the *absence* of adoption);
  - **work outcomes, and any causal "response" relation**;
  - capability that varies over time;
  - drafting time;
  - support, frequencies and epistemic-status labels;
  - a state/transition view.

  **A DDD model that needs any of these goes beyond what R4 supports.**
- **Rem 1.11a:** texts are observed as texts. Self-assertions such as "effective immediately" are **not** observations of σ.
- **Rem 3.1a:** B-conf is read universally.
- **Rem 3.1b:** B-chg is nearly vacuous.
- **Rem 3.1c:** B-fb and "primarily" are not formalized. **H-D and H-B are formalizations of HYP, not HYP itself.**
- **The readings** (Def 3.1, §9). Every conclusion states its reading or is shown to be reading-robust.
- **One target and finite T** in the verified universes.
- **The corpus boundary and the H5 waiver** (Λ3).
- **Within-family evidence status** (Λ1, Λ7).

## 5. The options compared
**Note:** the order adopted in F-LOG-0247 is "V2/V5/V6 by information gain, **then** R7". **Choosing "R7 now" skips that order,** and needs an explicit human ruling to do so.

| Criterion | **V2** (two-target slice, R-a) | **V5** (Conj 5.5) | **V6** (second corpus) | **R7 now** (bounded) |
|---|---|---|---|---|
| Uncertainty reduced | Whether D1 and its weak form survive R-a and more than one target (part of Λ5 and Λ6) | Whether performative ambiguity explains the R2 failure | Whether the primitives (K1), non-identifiability (K6) and the coding failure (K7) are corpus-specific (Λ3, Λ6) | None. It converts bounded conclusions into design hypotheses |
| Information gain | Moderate and targeted | **None on existing data (circular).** It needs new blind data | Potentially high. **But given K7, a second corpus may hit the same coding failure and be uninformative** | Low for theory; high for design exploration |
| Cost | **Moderate to high.** The full-scale V2 universe is ≈ 4 × 10¹¹ histories (F-LOG-0225), so only **targeted slices** are feasible. For comparison, the one-target universes are U ≈ 6.7 × 10⁵ and U_P2 ≈ 2.2 × 10⁶. A slice needs a new spec, comparator adaptation and review rounds; the bundle-02 comparator alone needed r1–r12 | High: new data acquisition | High: corpus selection and authorization, reconstruction, coding (with K7's limits) | Moderate, plus traceability overhead |
| New assumptions | The encoding of a second target (Rem 3.1b) | The design of the new data | The corpus choice and comparability criteria; possibly a new evidence channel | Expert design hypotheses [E], bounded by N1–N8 |
| Risk of false generalization | Low | Low | Lowers it downstream, if informative | **Highest:** one-corpus rules may generalize silently |
| Reversibility | Full | Full | Reversible; costly midway | Reversible while hypothesis-labelled and pre-architecture |
| Leaves untouched | Corpus generality; the shared interpretation; H-D vs H-B; **readings invisible in U, and combinations of readings** (R6 §8). It does **not** close every untested reading | Everything except Conj 5.5 | The shared interpretation (unless a non-Claude implementer is used); H-D vs H-B (unless a new channel) | All of Λ1–Λ7 |

**Without V2 or V6:**
- R7 is valid only for one corpus and the tested readings and universes.
- Multi-target norm concepts rest on an untested reading (R-a).
- No domain generality may be claimed.

## 6. Proposed R7 boundary (only if authorized)
1. **Inputs:** only K1–K9, with their class tags, scopes, Λ1–Λ7 and §2a. Nothing from quarantined items, B10, or Conj 5.5 as fact.
2. **Outputs are hypotheses [E].** Each comes with a **traceability row:** the concept, the R4 statement or R3 item it rests on, the inherited assumptions (§4), and its reading sensitivity.
3. **Guardrail:** anything touching N1–N8 is admissible only as an *open question* or *optional policy*. It is never an invariant.
4. **Mandatory independent review** of every R7 artifact (overclaiming, provenance, N-list, waived-as-satisfied).
5. **Out of scope:** architecture, implementation and canonicalization (R8). Each needs its own human act.
6. **Stop condition:** R7 ends with a candidate model and its traceability table, for human review. No adoption by implication.

## 7. Human decision
| Choice | Benefit | Remaining uncertainty | Commitment |
|---|---|---|---|
| **Authorize R7 under bounded claims** | Moves to design, with every bounded claim traceable | All of Λ1–Λ7; generality unknown; R-a untested | A human act for R7 with boundary §6, **explicitly overriding the F-LOG-0247 order (V2/V5/V6 first)**. R7 outputs are one-corpus hypotheses |
| **Run V2 first** | Tests R-a and multiple targets on a **targeted slice** (full scale ≈ 4 × 10¹¹ is infeasible) with the existing machinery | Readings invisible in U and combinations of readings; whatever the slice does not cover; corpus generality; the shared interpretation; H-D vs H-B | A human act for a V2 slice spec, blind runs (Claude-family unless a non-Claude run is arranged) and review. Moderate-to-high cost |
| **Authorize a V6 second-corpus investigation first** | The only route to claims beyond one corpus | It may be uninformative if the coding failure (K7) recurs; the shared interpretation; H-D vs H-B | A human act for a V6 decision brief first (corpus, scope, evidence-channel authorization). Highest cost |
| **Defer further work** | No cost; every option stays open | Unchanged | None. The record stays at R6-closed / path A |

*(V5 is not offered as a choice: it is non-identifiable without new blind data.)*
