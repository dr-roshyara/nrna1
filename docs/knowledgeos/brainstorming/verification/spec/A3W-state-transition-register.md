# A3-W — State & Transition Register (raw research track)

**Status:** EXTRACTED 2026-08-29 (full read of Q13, Q14, Q15, Q20, step-031, Steps 189/201/202/203/204/205).
**Structural fact:** each Q-file is a *three-layer* document — original definition → reviewer critique rejecting parts of it → (Q15/Q20 only) a revised definition. **Q13 and Q14 have no revised section: their critiques stand unresolved against their own bodies, and downstream files (Q20 §3.1) cite the *unrevised* Q13 10-tuple as authoritative.**
**Grep-verified absence:** the token "axiom"/"postulate" occurs **zero** times in all eleven files. The corpus's axiom-role objects are numbered invariants and "freeze" lists.
Everything below records corpus claims with locations; verifier observations marked ⚑OBS. Nothing reconciled.

---

## 1. `K_t` — the knowledge-state tuple family (recorded variance, refines V0 D-02)

| # | Form | Arity | Where | Status in its own file |
|---|---|---|---|---|
| K-a | `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ,𝒯,𝒢,𝒞,ℳ)` | 10 | Q13 §2.1/§8.2; Q20 §3.1/§7.1 | asserted; **rejected by Q13's own review** (Z,L,H,G "should move out") — never revised |
| K-b | `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` | 6 | Q14 §12.2; Q15 §6.1 + **Q15 revised §6 transition rules** | operative form of the "frozen" transition model |
| K-c | `(𝒫,𝒜,ℛ,ℰ,Σ,𝒞,𝒯,Π)` | 8 | Q13 review §4/§16 | reviewer counter-proposal ("hypothesize") |
| K-d | `(𝒜,ℛ,ℰ,𝒞,𝒯,Π)` | 6 | Q14 review §11 | reviewer counter-proposal |
| K-e | `(A,R,C,τ,Π)` | 5 | Q14 review §16 | "revised mathematical backbone" (same review as K-d, unreconciled) |
| K-f | `(E,A,M,C,F,V,R)` = (evidence, assertions, models, constraints, conflicts, validation, provenance) | 7 | step-031 §31.9 | "one of the most important formalizations so far"; does not cite the Q-series |
| K-g | `𝒦=(I,S,O,E,P,A,M,U,R,D,X,L)` | 12 | Step 201 §201.1 | "the canonical architecture" (concept-level, not state tuple) |
| K-h | ratified 8-primitive `𝒦=(E,S,T,O,P,R,Π,A)` | 8 | 049 §49.30 (spine, Part R D-R11) | ratified layer |

Q20's revised model keeps `K_t` as an **opaque, unparameterised** component of `S_t` — the revised system model is parameterized by an unresolved knowledge state ⚑OBS.

## 2. `S_t` — the system-state tuple family (refines V0 D-03)

| # | Form | Arity | Where |
|---|---|---|---|
| S-a | `(K,U,X,I,Q,C,N)` | 7 | Q13 §7.1 + review §15 |
| S-b | `(K,U,X,I,Q,C,N,Z,L,H)` | 10 | Q13 review §16 |
| S-c | `(K,Z,L,I,Q,C,N)` | 7 (different members) | Q15 revised §8.3 |
| S-d | `(K,U,X,I,Q,C,N,P,G)` | 9 | Q20 original §2.1 |
| S-e | `(K,U,X,I,Q,C,N,P)` | 8 | Q20 review §2 + revised §2.1 — with invariant `A_t,L_t,a_t ∉ S_t` |
| S-f | 8-fold product `𝒮=𝒦×𝒰×𝒳×𝒾×𝒬×𝒞×𝒩×𝒫` | product | Q20 revised §2.2 |
| S-g | federation `𝒮={S_D,S_E,S_G,S_Dec,S_X,S_O}` — **explicitly a set, NOT a product; monolithic state machine REJECTED** (Cartesian explosion 16,800-state example; decoupling proof ΔS_E≠0 while ΔS_D=0) | 6 spaces | Step 203 §203.1/§203.31 |
| S-h | 3-dimensional `E(p), G(p), O(p)` epistemic/governance/operational machines | 3 | Step 189 §189.3 |

⚑OBS **Direct incompatibility of the two "revised" models:** Q15-revised puts Zero/Lord findings *inside* both `K_t` and `S_t`; Q20-revised excludes them from `S_t` by invariant. Step 203 rejects the very product construction Q20-revised adopts. Neither cites the other.

## 3. Transition model — recorded forms (refines V0 D-01)

### 3.1 The Q15-revised "frozen" core (most-developed candidate)

Eight theorems T1–T8: T1 Compute ⇏ state change · T2 only committed events change K · T3 history append-only · T4 rollback creates a new state · T5 Zero evaluates, does not constitute, Knowledge · T6 Lord proposes, Sārathi guides, Knower commits · **T7 `K_{t+1}=δ(K_t,e_t)`** · **T8 `K_t=Replay(K_0,H_t)`**.
Typed: `δ: 𝒦×ℰ ⇀ 𝒦` (**explicitly partial**); `Pre/Post` predicates; `δ defined ⟺ Pre`; Replay recursion `Replay(K_0,H_t‖e_t)=δ(Replay(K_0,H_t),e_t)`; 8 event types, 7 command types; 5 layers (only Commitment changes K).
⚑OBS defects recorded in-place: transition rules emit 6-tuples containing 𝒵_t,ℒ_t against the same section's T5; Rollback rule written as plain equality `= K_{τ_target}` while its own prose demands different provenance; `𝒮_strat`, `Authorized` undefined; Pre argument-order reversed between §2.4 and §5.

### 3.2 Other transition forms on record

| Form | Where | Notes |
|---|---|---|
| `Evolve(State_t, Action_t, Observation_t)` | Q13 §7.2 | Action/Observation undefined in Q13 |
| `Transition(K_t,a_t,o_t,e_t)` | Q14 review §16 | o_t, e_t undefined in Q14 |
| `Transition(K_t, Operation, Parameters)`, total `(𝒦,𝒪,𝒫)→𝒦`, 24-operation taxonomy with 18 signatures + pre/postconditions | Q15 original §2–§5 | superseded by review (Operation≠Event≠Transition; 𝒪⁺/𝒪?/𝒪q split) |
| `S_{t+1}=δ(S_t,e_t,P_t)` vs signature `δ:𝒮×ℰ⇀𝒮` | Q20 review+revised | **arity 3 application vs arity 2 signature, both halves, unresolved** |
| `K_{t+1}=Revise(K_t,e)` — non-monotonic, not union, entailment-non-preserving *by requirement* (`K_t⊨A ∧ K_{t+1}⊭A` expected), history/provenance-preserving | step-031 §31.45–48 | |
| **Core equation** `K_{t+1}=ℛ(K_t,E_t,C_t,M_t,V_t,T_t)`, ℛ **partial and constrained**, outputs {Accepted, Unknown, Underdetermined, Conflicted, Invalid, RequiresValidation} | step-031 §31.73 | `T_t` undefined in step-031 |
| `τ: S×C → S` with Pre/Post; **transition contract** `T_τ=(Pre,Input,Authority,Policy,Effect,Post,Invariant,Lineage)`; invariant preservation `I(s)∧Pre_τ(s,c) ⇒ I(τ(s,c))` | Step 204 §204.1–5 | total arrow; never references δ; second arg = context not event ⚑OBS |
| `τ: S_t→S_{t+1}` with `S_t=S_{t+1}` permitted; `Π=(τ_1,…,τ_n)`; `Process ≠ Transition` | Step 201 §201.15–17 | |
| Pipeline of **partial** functions `W→O→E→A→M→D→X` with epistemic composability (`Domain(f_M) ⊇ Range(f_A)`) and **epistemic type safety** (`UnsafeCast(Hypothesis,ValidatedClaim)=Forbidden`) | step-031 §31.37–44 | |
| Lifecycle loop `E_t→G_t→O_t→E_{t+1}` | Step 189 §189.14 | |

### 3.3 Step 204 transition algebra — properties actually addressed

Composition law stated **two non-equivalent ways in one paragraph**: `Post_{τ1} ⇒ Pre_{τ2}` and `Composable ⟺ Post(τ1) ⊇ Pre(τ2)` ⚑OBS (implication and superset run in opposite directions under the states-set reading; unreconciled — candidate L1 finding).
Also: validity non-inheritance under composition · reversibility mostly denied (`Rollback ≠ Reversal`; compensation `Payment→Refund`, `Refund ≠ Payment⁻¹`) · idempotency as a per-transition declared flag · commutativity generally fails (`Approve∘Reject ≠ Reject∘Approve`) · **causal partial order `(𝒯,≺)` preferred over total order; concurrency = absence of causal dependency** · temporal ≠ causal · validity vector `(Semantic, Authority, Policy, Execution)` with `execution failure ≠ decision invalidity` · risk `Risk(τ)=𝔼[L(Y)|τ,C]` with "never invent a threshold merely because statistics provides one" · 10-property transition taxonomy · three transition kinds (epistemic/governance/operational) that "must not be collapsed into one workflow".
⚑OBS Q13/Q14/Q15/Q20/step-031 never address idempotency, commutativity, compensation, or partial order — the "frozen" transition model and the transition algebra are disjoint developments with different signatures.

## 4. Type systems on record

- **Q14 universe:** 13 types `{𝓔,𝓓,𝓥,𝓟,𝓐,𝓡,𝓔_v,Σ,𝓗,𝓩,𝓛,𝓚,𝓘}` with constructors; epistemic state `Σ=(A,S,R,V,C)`, `|𝒮|=7·5·4·4·4=2240`; claimed "epistemic lattice" `Σ₁⪯Σ₂` — **rejected by its own review** (Resolution not naturally ordered; product order ≠ lattice). Evidence-support scalar `Supports∈[−1,1]` rejected by review in favor of `(Bearing,Strength)`. Documented notation errors flagged by the file itself: `∼` used for both Consistent and Normative Conflict; `⇒` used for Causal.
- **step-031 universe:** 9+ typed sets (𝒪,𝓔,𝓐,𝓜,𝒞,𝒟,𝒳,𝒱,𝓕 + 𝓘,𝓑,𝒯,𝓟,𝓥_p,𝓔v); assertion `a=(i,p,v,b,τ,σ)`; consistency predicate 6-fold decomposition; `Consistent(K)=True ⇏ True(K)` (§31.16); typed probability object; typed uncertainty object `U(H)=(type,value,model,scope,source)`.
- **Step 201 vocabulary:** 17-entry frozen table (16 frozen + KnowledgeArtifact/SemanticElevation provisional; Policy added by Step 202 → 19 names total). Semantic-elevation chain `Information ⇏ Evidence ⇏ Truth ⇏ Authority ⇏ Decision ⇏ Outcome` with licensed arrows; 6 rejected vocabulary smells; "Knowledge" overloaded across 7 senses (flagged in-corpus).
- **Step 202:** 10-kind relation alphabet; 17-edge permitted-relation matrix ("not yet normative — our current hypothesis"); ~19 named relations; `Policy: Context×Action→{Must,May,MustNot,Conditional}`; decision validity vector `V_D=(E_D,G_D,P_D)`; `Fact ≠ Norm ≠ Authority`; 10 provisional inequalities.
- ⚑OBS **Vocabulary schism:** Step 201 freezes **Assessment** and has no **Assertion**; Q13/Q14/Q15/Q20/step-031 build everything on **Assertion** and never use Assessment — violating Step 201's own one-word-one-concept rule across the corpus (I.21). `Determination` exists only in Step 189 (I.23).

## 5. The corpus's strongest formal results (candidates for PROVEN at L1)

1. **Impossibility (step-031 §31.19, verbatim):** if `Ω(W₁)=Ω(W₂)` while `W₁≠W₂`, KnowledgeOS cannot distinguish them from observations alone — "No algorithm can recover information that the observation function destroys."
2. **Identifiability criterion (§31.20):** `g` identifiable from Ω iff `Ω(W₁)=Ω(W₂) ⇒ g(W₁)=g(W₂)`; failure → system must be allowed to return `Underdetermined` (§31.21). ⚑OBS These are standard information-theoretic facts, correctly stated; the corpus calls them "one of the strongest mathematical foundations".
3. **State-machine federation argument (Step 203):** Cartesian-product rejection + the ΔS_E≠0/ΔS_D=0 decoupling demonstration.
4. **Epistemic type safety (step-031 §31.41–44):** composability via domain/range containment; forbidden casts.
5. step-031's 20 numbered counterexamples (§31.49–68), all PASS *conceptually* — ⚑OBS conceptual, not executed; §31.69 grades J (uncertainty propagation), K (proof calculus), L (complexity) OPEN.

## 6. Invariant/assumption inventory (raw track, this family)

- **Numbered invariants present:** I₃₃ (189) · I₇₀–I₇₃ (201) · I₇₄ (202) · I₇₅ (203). **Numbering gap I₃₄–I₆₉ not present in these files** (I₆₄–I₆₈ live in the uncertainty family — see A6). Unnumbered: I_E, I_G (204), I_D, I_A, I_KA (205).
- **Freeze lists (assumption-by-fiat):** Step 201 §201.40 (16+2) · Q14 review §18 ("freeze now" 9 / "do NOT freeze" 5 — the do-not-freeze list includes `P=(E,D,V)`, the Σ-lattice, `Supports∈[−1,1]`, `K_t=(A,R,E,H,Z,L)`) · Q20 review §25 (11 boxed invariants incl. `S_t=Replay(S_0,H_t)` *conditional on determinism + policy/version refs*) · Step 203 `Event≠State`.
- **Explicit provisos:** Replay requires deterministic δ + policy/version references retained; risk thresholds only where policy defines them; commutativity "often"; idempotency "may".
- **Self-limitations:** step-031 §31.75 "Formal completeness: NOT YET", 3 OPEN areas; Q20 review denies "complete mathematical specification" — **which the Q20 revised half then reinstates** (I.12); Gītā = conceptual lens only, stated in 189/201/202/204.

## 7. Recorded contradictions (I-series; feed checkpoint §F, joining A6's K-series)

| I | Contradiction |
|---|---|
| I.1 | K_t: 7 incompatible tuple forms, none retracted (§1 above) |
| I.2 | Q15-revised transition rules carry 𝒵_t,ℒ_t inside K_t against its own T5; Zero findings appear both inside K_t and beside it in S_t |
| I.3 | Q13/Q14 bodies rebutted but never revised; Q20 cites the unrevised Q13 form as authoritative |
| I.4 | S_t: four arities; the two "revised" models disagree on where Zero/Lord live |
| I.5 | δ arity 2 (signature) vs 3 (application) inside Q20's revised section |
| I.6 | History: set-of-snapshots (`ℋ_{t+1}=ℋ_t∪{K_t}`, self-membership problem flagged in-corpus) vs event-sequence (`H_t‖e_t`) — both persist inside Q20 |
| I.7 | Rollback: time-travel (Q15 orig) vs new-state (review) vs plain-equality-with-annotation-contradicting-it (Q15 revised) vs Rollback≠Reversal (204) |
| I.8 | `Preserve` defined, declared unnecessary, silently dropped while its diagrams remain |
| I.9 | Systematic glyph collisions: 𝓔 (entity/evidence/events), 𝓟 (proposition/parameters/predicates/policy/powerset), 𝓒 (context/conflicts/commands/constraints), 𝓐 (assertion/analysis/assessment), δ (state transition/decision function), α (analysis/action-derivation), Π (provenance/process), T_τ (contract and 4-vector in one file), W_t (witness vs world state) |
| I.10 | Assertion arity 7 vs 5 within Q14 itself; Relationship 9 vs 8 |
| I.11 | Context: 7 fields (Q13) vs 6 (Q20, Purpose silently dropped) |
| I.12 | "Complete mathematical specification" claimed (Q20 orig) → denied (review) → reinstated (revised) |
| I.13 | step-031 "no contradiction detected" is scoped to itself; its K_t is incompatible with the Q-series it never cites |
| I.14 | Q15 asserts well-formedness preservation; step-031 requires entailment non-preservation; corpus never states which preservation notion transitions guarantee |
| I.15 | Transition-property matrix exists only in Step 204, whose `τ` signature differs from and never references the "frozen" δ |
| I.16 | Step 204 composition law stated as implication AND as superset — opposite directions |
| I.17 | Step 203 rejects the product state space Q20-revised adopts |
| I.18 | "Zero findings ∈ Knowledge?" decided four different ways across Q13/Q14/Q15/Q20 |
| I.19 | Zero signature: 6 variants (4-arg ×3 with different 3rd argument, 2-arg, 1-arg); Lord/Sārathi similar |
| I.20 | Q14 well-formedness requires evidence per assertion; Q14 review + Q13 gap theory require evidence-free assertions to be representable |
| I.21 | Assessment (201) vs Assertion (Q-series) vocabulary schism |
| I.22 | Two different Policy signatures (Q20 review vs Step 202), neither citing the other |
| I.23 | Determination exists only in Step 189; Step 205's chain skips it without comment |
| I.24 | Aggregate candidates: 189's list vs 205's list disagree on Lineage and Determination |
| I.25 | Step 205: Authority "Medium–High" vs "Conditional", undefined against each other |
| I.26 | Q15 Observe postcondition writes to 𝒪_t — a slot in no K_t tuple anywhere |
| I.27 | DetectConflict writes conflicts into 𝒞_t = Context (or a nonexistent slot) depending on the tuple in force |
| I.28 | Undefined symbols inside "frozen" results: 𝒫_r, 𝒯 (Q14), 𝒮_strat, Authorized (Q15), ΔU, γ (Q20), T_t, \Revision/\Validate/\Infer (031), 𝒩 (202), Measure (Q15), 𝒳/𝒰/𝒾 state spaces (Q20), 𝓕_t, W_t (189) |
| I.29 | `Coherent`/`WellTyped`/`ConflictFree` used in boxed invariants, defined nowhere |

## 8. Step 189 state machines (as stated)

- **Epistemic 𝓔** = {Unknown, Observed, Hypothesized, Supported, Conflicted, Refuted, Determined, Superseded}; transitions listed only partially (Unknown→Observed/Hypothesized; Supported→Conflicted/Refuted); no monotone-certainty requirement; `Believed` explicitly rejected as a state; support is relational `Supported(P;E,R,C,t)`; determination gated by rule+authority.
- **Governance G(p) and Operational O(p): named but state sets NEVER enumerated in Step 189** — they exist only via Step 203/204 examples ⚑OBS (gap).
- Typed relation set (10): {Supports, Contradicts, Refines, Corrects, Supersedes, Derives, Causes, Authorizes, Decides, Executes}; edges are 6-tuples `(source,target,relation,context,time,witness)`.
- Conflict predicate: `Conflict = Identity ∧ Context ∧ TemporalOverlap ∧ ¬Compatible`.
- Four invariant families (epistemic/temporal/governance/lineage), incl. `Unknown ≠ False`, `Capability ≠ Permission ≠ Authority`, `Transition ⇒ Witness`.

---

*Checkpoint feed: §1–§3 → report §C/§D (load-bearing propositions, missing definitions); §5 → kernel candidate; §6 → §E; §7 → §F. The three-layer document structure (definition/rebuttal/revision) means V1 must carry per-claim **layer provenance** — a claim's status depends on which layer asserts it.*
