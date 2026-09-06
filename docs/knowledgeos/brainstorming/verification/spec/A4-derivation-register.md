# A4 — Derivation Register: the K0 rim proofs (Wave 1 / P2)

**Author:** VERIFY SESSION (independent proofs — class B/C: formal derivations by the verifier; every premise traced to K0's frames/assumptions, which are themselves corpus-anchored). **Nothing here validates the corpus labels; each theorem states exactly what it does and does not establish.** Date 2026-08-29.

Notation: K0 frames P1–P7, assumptions KA1–KA7 (`K0-mathematical-kernel-candidate.md`).

---

## T-K1 (Observational impossibility) — **PROVEN**

**Statement.** Let `Ω: W → O`. If `Ω(W₁)=Ω(W₂)` and `W₁≠W₂`, then for every function `h: O → X` (deterministic or randomized with observation-measurable randomness), `h(Ω(W₁)) = h(Ω(W₂))`; hence no procedure whose input is the observation distinguishes `W₁` from `W₂`.

**Proof.** Immediate: `h∘Ω` is constant on `Ω`-fibers. Any algorithm computes a function of its input; if its input is `Ω(W)`, its output is a function of `Ω(W)`. ∎

**Scope.** Establishes exactly the corpus's boxed claim (031 §31.19). Does **not** preclude distinguishing via a *changed* observation channel (interventions, new sensors — i.e., replacing Ω) or via priors *about* W (which shift credence, not discrimination). The corpus does not overclaim here; its statement is correct as written.

## T-K2 (Identifiability = factorization) — **PROVEN**

**Statement.** `Identifiable(g,Ω)` (i.e. `Ω(W₁)=Ω(W₂) ⇒ g(W₁)=g(W₂)`) **iff** there exists `h: Ω(W) → V_g` with `g = h∘Ω`.

**Proof.** (⇐) trivial. (⇒) define `h(o) := g(W)` for any `W ∈ Ω⁻¹(o)`; well-defined precisely by the identifiability condition; then `g = h∘Ω` by construction. ∎
**Corollary (legitimacy of `Underdetermined`).** If `¬Identifiable(g,Ω)`, witnessed by `Ω(W₁)=Ω(W₂), g(W₁)≠g(W₂)`, then every observation-based estimator errs on at least one of `W₁,W₂`; an output value `Underdetermined` is the unique sound uniform answer on that fiber. — This grounds 031 §31.21, Freedman's `NOT_IDENTIFIABLE`, and I-14/I₆₄-adjacent discipline. **PROVEN.**

**Scope.** This is the standard quotient/factorization lemma; the corpus's "one of the strongest mathematical foundations" assessment is fair *for the statement itself*; it does not by itself deliver any positive identification method.

## T-K3 (Zero: totality, termination, complexity) — **PROVEN UNDER ASSUMPTIONS (KA1, KA2, KA5)**

**Statement.** With `R_EC` finite, `ev` total on `𝒦×R_EC×EC` and `𝒮_gap` closed, `Zero(K,EC) := {(r, ev(K,r,EC)) : r ∈ R_EC}` is a total function into finite vectors over `𝒮_gap`, computable with exactly `|R_EC|` evaluator calls (`O(|R|·cost(ev))`), and deterministic **iff every invoked evaluator is deterministic on the given inputs**.

**Proof.** Finite indexed product of oracle values; termination by finiteness of the index set; determinism inherited componentwise. ∎

**Scope.** All strength lives in the assumptions: KA2 makes evaluators oracles (HumanAuthorization included — see TV-F-004's replay boundary); KA5 is currently violated in corpus usage (C-014). Consistent with the executed witness (`zero_reference.py`); the witness is a check, not the proof.

## T-K4 (Ladder: no-skip and boundary inertness) — **PROVEN (as ruled model)**

**Statement.** Model the statuses as the finite chain `Candidate ⋖ Supported ⋖ Accepted` with transition steps permitted only along covering pairs, plus the single boundary edge `Accepted ⋖ Committed` whose transition label is of sort `Act_auth`; evidence events carry labels of sort `Ev ≠ Act_auth`. Then: (i) every admissible status path visits each intermediate status (no skip); (ii) no sequence composed solely of evidence events reaches `Committed` from any status (evidence-volume inertness of A6).

**Proof.** (i) In a finite chain, each covering step moves to the immediate successor; by induction any path is the consecutive chain segment. (ii) Type discipline: crossing the boundary requires a transition labeled `Act_auth`; a composition of `Ev`-labeled steps contains none. ∎

**Scope.** These are *design theorems*: they prove the ruled model has the ruled properties (I-12, A6), not that the model matches any richer reality. The FA layered extension (REJECTED/CONFLICTED as governed exits) is conservative over (i)–(ii) since those transitions are `Act_auth`-labeled by Arts. 7–8. **The dynamics (what triggers a covering step) remain absent (AF-F-3) — untouched by this theorem.**

## T-K5 (Admissibility conjunction laws) — **PROVEN**

**Statement.** In strong Kleene 3-valued logic with `Adm(d) = ⋀ᵢ Admᵢ(d,K,t)` and the Unknown→Block reading (only `True` admits): (i) one `False` conjunct forces `Adm=False` irrespective of all others (no averaging); (ii) no `False` and ≥1 `Unknown` forces `Adm=Unknown` (blocked); (iii) `Adm=True` iff all conjuncts `True`.

**Proof.** Kleene conjunction truth table; induction on the number of conjuncts. ∎ Matches the witness (`ladder_dc_reference.py`) and 042's no-averaging derivation.

## T-K6 (Evidence pipeline: possibility and impossibility) — **PROVEN / PROVEN UNDER ASSUMPTIONS**

**(a) Raw-level impossibility — the sharpened form of I-5's pipeline-only status.**
**Statement.** There is **no** aggregator `F: Multiset([0,1]) → (Q,≤)` (any ordered codomain) satisfying both:
(A) *duplicate invariance*: adding a duplicate of an already-present item leaves the output unchanged; and
(B) *independent corroboration*: adding an independent item of positive strength strictly increases the output —
because F's input cannot distinguish the two cases. **Proof.** Take `M = {0.7}`. Let `M₁ = M ∪ {0.7}` where the new item is a duplicate, `M₂ = M ∪ {0.7}` where it is independent. As multisets of strengths, `M₁ = M₂`, so `F(M₁)=F(M₂)`; but (A) demands `F(M₁)=F(M)` and (B) demands `F(M₂)>F(M)`. Contradiction. ∎
**Consequence.** Duplicate/dependency safety **cannot** be an operator property; it is necessarily a property of a stage with access to identity/dependency structure — the normalization `N`. This upgrades GN-46's observation ("property of the pipeline, not of any raw operator") to a **theorem**, and shows the EXP-01 raw-operator failures were inevitable, not accidental. **PROVEN.**

**(b) Pipeline-level I-5/I-6.**
**Statement.** If `N` satisfies (N1) its output depends only on the `~`-class structure and `≺`-reduced support of its input, then for every operator `⊕`, `⊕∘N` is duplicate-invariant; and dependency non-inflation holds by construction of the `≺`-discount. **Proof.** `N(M∪{e'}) = N(M)` whenever `e' ~ e ∈ M` (N1); apply `⊕`. ∎ **PROVEN UNDER ASSUMPTIONS (KA3 — `~`,`≺` exist and N1 holds).** The entire load transfers to the unconstructed identity calculus (LB-2): **I-5 is exactly as sound as `~` is well-defined.**

**(c) Signed monotonicity (from TV-F-001), per operator.**
**Statement.** Under addition of an admissible independent supporting item `e` (strength `s ∈ [0,1]`) to the support pool: SATURATING `1−∏(1−sᵢ)` is non-decreasing (multiplying by `(1−s) ≤ 1`); MAX is non-decreasing; BAYES-odds (product of odds ≥ 1 for `s ≥ ½`… in the corpus's LR formulation, LR ≥ 1 for supporting items) is non-decreasing under the supporting-item convention `LR ≥ 1`; **WEIGHTED MEAN is NOT** — counterexample `mean(0.9, 0.1) = 0.5 < 0.9` with a genuinely supporting but weak item. ∎
**Consequence.** step-004 §6's axiom is *satisfiable* and *discriminating*: it partitions the corpus's own operator candidates, ruling WM out as an admissible policy — consistent with (and explaining) WM's EXP-01 failures. **PROVEN (per-operator computations).**

## T-K7 (Scalar contradiction impossibility — with a needed qualifier) — **PROVEN, corpus statement MODIFIED**

**Statement (corrected).** (i) There is **no continuous injective** map from the conflict square `{(S⁺,S⁻) ∈ [0,1]²}` into `ℝ` — hence no continuous scalar representation preserves the two-dimensional (support, opposition) distinction. (ii) Without continuity, set-theoretic injections `[0,1]² → [0,1]` exist (digit interleaving), so the bare claim "no scalar can retain (S⁺,S⁻)" is **false as stated**; it becomes true under any of: continuity, monotone-order semantics ("equal scalar ⇒ equal epistemic treatment"), or bounded-precision representations.

**Proof.** (i) A continuous injection `[0,1]² → ℝ` restricted to a small closed disk would be a homeomorphism onto its compact image `⊂ ℝ` (continuous bijection from compact to Hausdorff); removing an interior point disconnects no compact subset of ℝ in the required way — concretely, the image of a circle around the removed point would be a compact connected subset of `ℝ∖{pt}` i.e. an interval on one side, contradicting the circle's image having to surround `pt`'s image by injectivity+continuity (or directly: invariance of domain forbids embedding ℝ² in ℝ). (ii) Standard interleaving bijection. ∎
**Consequence.** The corpus's operative claim survives in every *semantically meaningful* reading (the executed {0.9,0.9} vs {0.1,0.1} counterexample kills all order-respecting scalars), but the final theory must carry the qualifier. Criterion E's unsatisfiability by scalars: **PROVEN under the order/continuity qualifier; the unqualified sentence is REFUTED on a technicality that matters for precision, not for design.** → finding TV-F-005.

## T-K8 (Replay determinism) — **PROVEN UNDER ASSUMPTIONS (KA4 + history validity)**

**Statement.** If `δ` is deterministic and `H = (e₀,…,e_{t−1})` is valid (each `Pre` held at its step), then `Replay(K₀,H)` is defined, unique, and equals the state reached by executing `H`; moreover `Replay(K₀, H‖e) = δ(Replay(K₀,H), e)`.

**Proof.** Induction on `t`; uniqueness from functionality of `δ`; the recursion equation is the definition unfolded once. ∎
**Scope.** Everything depends on KA4: evaluator/oracle calls inside transitions must be recorded-value lookups (TV-F-004), and policy/version references must be part of the event data (the corpus's own proviso). Without those, replay is **UNVERIFIED**, not false.

## T-K9 (Decidability imports) — **PROVEN (standard results; corpus scoping verified correct)**

(i) Finite-state reachability: decidable (graph search terminates on finite graphs). (ii) Compliance under a finite rule set with terminating per-rule evaluators: decidable (finite conjunction of terminating checks — T-K5 semantics for the 3-valued case). (iii) Universal termination checking: undecidable (Turing; imported, not reproved). (iv) Bounded verification (`n ≤ N`): decidable by enumeration; **any "holds for all n" conclusion from it is an overclaim** — 089's own guard, endorsed. ∎
**Scope check.** 089's statements sampled in extraction match the standard results, including the crucial "undecidable-in-general ⇏ undecidable-per-restricted-instance". No overclaim found at the ratified or 089 level.

## T-K10 (State-space explosion) — **PROVEN (arithmetic)**

`n` independent Boolean dimensions ⇒ `|S| = 2ⁿ`; `2¹⁰⁰ ≈ 1.27×10³⁰` exceeds enumerability on any realistic hardware; hence any *system-level* "computable on a normal PC" claim is justified only for the K0 rim under KA1–KA7 and symbolic/compositional methods — confirming that 025g/025h's unqualified claims (C-037) need the later files' scoping. ∎

---

## Register outcome

| Item | Verdict | New knowledge beyond corpus |
|---|---|---|
| T-K1, T-K2 (+corollary) | PROVEN | factorization form + exact scope statement |
| T-K3 | PROVEN UNDER ASSUMPTIONS | assumption set made exact (KA1/2/5) |
| T-K4 | PROVEN (ruled model) | type-discipline formulation of A6 |
| T-K5 | PROVEN | — |
| T-K6a | **PROVEN impossibility** | raw-level I-5 unsatisfiability is a theorem, not an observation |
| T-K6b | PROVEN UNDER ASSUMPTIONS | full load on identity calculus made explicit |
| T-K6c | PROVEN | signed monotonicity **partitions the operators; WM excluded** |
| T-K7 | PROVEN with qualifier | continuity/order qualifier the corpus lacks (TV-F-005) |
| T-K8 | PROVEN UNDER ASSUMPTIONS | replay-safety boundary tied to TV-F-004 |
| T-K9 | PROVEN (imports) | 089 scoping independently confirmed |
| T-K10 | PROVEN | — |

**Net Level-1 position after Wave 1:** the rim is now **established mathematics** (10 results, 6 unconditional, 4 with explicit assumption sets). No CRITICAL defect found in the rim. The middle (η, identity calculus, transition calculus, policy floor) remains the theory's open core — unchanged by these proofs, but now *provably* the only thing standing between the rim and the full system (every conditional above discharges into LB-1…LB-5).

---

## Non-Consequences (mandatory section per directive 1514 §16) — what these theorems do NOT establish

- **T-K1/T-K2** do NOT establish that any particular KnowledgeOS quantity *is* identifiable, that Ω is known or fixed, or that the W/O sorts are constructible for real deployments; they are conditional structure theorems over an abstract observation frame.
- **T-K3** does NOT establish Zero's semantic correctness (that `ev` returns the *right* status), the closure of 𝒮_gap as used (C-014 stands), termination of any surrounding *loop* (Lord↔Sārathi, C-046), or η's existence — Zero's input EC remains unconstructed.
- **T-K4** does NOT establish the ladder's *dynamics* (AF-F-3), the adequacy of three statuses, the FA layered model's adjudication operator (MV-F-10), or that real implementations respect the type discipline (L4 is empty).
- **T-K5** does NOT establish which admissibility components the ratified DC actually requires (MV-F-5 stands), nor the semantics of Unknown beyond the Block policy.
- **T-K6a** does NOT establish that any *particular* normalization N exists or is computable — only that one is *necessary*; **T-K6b** transfers, not discharges, the load onto the identity calculus (LB-2); **T-K6c** does NOT rank the surviving operators or select one (OQ-3 untouched).
- **T-K7** does NOT forbid scalar *projections* for display/triage (the corpus's own GapCount/Coverage remain legitimate as labeled projections); it forbids only scalar *replacements* of the (S⁺,S⁻) state under continuity/order semantics.
- **T-K8** does NOT establish that KnowledgeOS histories satisfy the validity precondition, that oracles are recorded (TV-F-004), or that replay is *feasible* at scale.
- **T-K9/T-K10** do NOT establish any positive system-level computability claim; they bound what could be claimed and confirm the corpus's own negative boundaries.
- Collectively: the rim does NOT establish the existence of a coherent full KnowledgeOS theory; it establishes that *if* the middle (LB-1…LB-5) is supplied, the rim composes with it soundly at the interfaces proved here — and nothing more.
