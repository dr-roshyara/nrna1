# KnowledgeOS Research Programme — Iteration 18

**Role:** Senior mathematician / statistician / DDD architect / epistemic reviewer
**Mode:** Single-question discipline. No forward completion. Nexus Repository as running example.
**Constraint:** Kernel derivation remains the *final* reduction problem.
**Discipline note:** I validate Iteration 17's nomination of Q-L4-1 before executing it. I also check whether Iteration 17's *parsimony-based* typing selection has introduced a hidden assumption that must be audited first.

---

## Part I — Baseline Audit (Post Iteration 17)

### I.1 Derived (D)

**L0 — Carrier**
- **D1** Stratified tower.
- **D2** Measure-first: Radon \(\mu\) on Souslin/Fréchet \(X\).

**L1 — Measure / State**
- **D11** \(K_t^{Q,\Gamma} = (\mathcal{D}_t, \mathbf{V}_t, \mathbf{A}_t, Q, \Gamma)\).
- **D12** Three-level separation.
- **D13** Evidence and history external.
- **D15** \(K_{\min}^{Q,\Gamma} = L^{Q,\Gamma}(\mathcal{S})\).
- **D17** \(\sim_{\mathrm{req}}^{Q,\Gamma}\) congruence coequalizer.

**L2 — Operator**
- **D3** Reduction = pushforward.
- **D4** Covariance \(R_\mu\); nuclearity criterion.
- **D5** Sazonov gate.
- **D8** Sazonov stability.
- **D9** \(\mathbf{Red}(\mu)\).

**L2.5 — Governance**
- **D10** \(\mathbf{Gov}(\mu) = \int \mathbf{Red}(\mu)\).
- **D14** Kernel = state-transition.
- **D16** \(\mathbf{KOS}(\mu)\) base.
- **D18** Replay \(\eta\).
- **D19–D22** Fredholm / Calkin / \(\sigma_e\) / orthogonality.

**L2.75 — Fibre-internal**
- **D23** Chain \(\mathcal{L}^1 \subset K \subset L\).
- **D24** 2-sorted fibre.
- **D25** Grothendieck fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\).

**L3.0 — Zoom-in / Investigation**
- **D26–D31** Zoom-in, Explore, Determine; contract; reachability ≠ determination.
- **D32** Restriction ≠ Inquiry.
- **D33** Inquiry order-robust.
- **D34** Restriction epistemic ceiling.
- **D35** FR-004 (candidate).
- **D36** Zoom functor; Explore oplax monoidal; Determine natural transformation.

**L3.0′ — Zoom-out**
- **D37** Zoom-out = Integration: \(K_{t+1} = K_t \sqcup D_Q\).
- **D38** Zoom-out is a natural functor.
- **D39** DDD boundary generator = integrated determinations.

**L3.5 — Experimental (frozen)**
- **D40** Determine contract (supported ∧ competitors excluded).
- **D41** Effect floor \(\Delta \ge 0.10\).
- **D42** Decoy precondition (dynamic exclusion).
- **D43** Calibration gate.

**L3.6 — Predicate typing**
- **D44** Predicate lattice is Heyting-valued over \([0,1]\) with threshold \(\tau\).
- **D45** Determine is a thresholded meet.
- **D46** Determine is a functor \(\mathbf{KE} \to \mathbf{Pred}\), natural in evidence.
- **D47** Determine is orthogonal to L2.5 and L2.75.
- **D48** Threshold \(\tau\) propagates through \(\sqcup\).
- **D49 (new, Iteration 17)** Typing at D44/D45 is *confirmed by parsimony*, not forced. Type P-Heyting is the current default.
- **D50 (new)** Revision of the typing must be *derived*, not chosen.

**L3 — Kernel**
- **Terminal.** Unreachable.

### I.2 Proposed (P)

- **P1** Kernel as reduction output. **Terminal.**
- **P2** Latent manifold.
- **P3** Knowledge as sheaf.
- **P4** DDD boundary algebra.
- **P5** Nexus Repository model.

### I.3 Unresolved (U)

- **U4** Kernel identification. **Terminal.**
- **U5** Hierarchy commutation.
- **U6** DDD boundary algebra.
- **U8** Discriminative dimension.
- **U-E4α-ii** Categorical type of L2.75 fibration.
- **U-ZOOM-2** Determination internal coherence.
- **U-L4-1** Category structure of integrated determinations.
- **U-LATTICE-AUDIT (newly visible)** Is the parsimony selection of Type P-Heyting sound, or does it smuggle in assumptions?

### I.4 Architectural levels

| Level | Content | Status |
|---|---|---|
| **L0 — Carrier** | \(X\) | Derived |
| **L1 — Measure / State** | \(\mu\); \(K_t^{Q,\Gamma}\) | Derived |
| **L2 — Operator** | \(\mathbf{Red}(\mu)\) | Derived |
| **L2.5 — Governance** | \(\mathbf{KOS}(\mu)\), \(\mathbf{Gov}(\mu)\), \(\pi_\ast\), \(\sigma_e\) | Derived |
| **L2.75 — Fibre-internal** | \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\) | Derived |
| **L3.0 — Zoom-in / Investigation** | Zoom-in, Explore, Determine | Derived |
| **L3.0′ — Zoom-out** | Integration functor | Derived |
| **L3.5 — Experimental contract** | Determine contract, effect floor | **Frozen** |
| **L3.6 — Predicate typing** | Heyting-valued over \([0,1]\) with \(\tau\) | Selected by parsimony |
| **L3 — Kernel** | \(K\) | **Terminal** |
| **L4 — DDD context** | Integrated determinations (generator) | Anchored |

### I.5 The nominated question

Iteration 17 nominated:

> **Q-L4-1 — Do integrated determinations with Heyting-valued thresholded predicates form a category, and what are its objects and morphisms?**

Before executing this, I audit Iteration 17's *methodological innovation*: the use of *parsimony* to select a typing that was not *forced*.

---

## Part II — Validation of Q-L4-1

### II.1 Test 1 — Well-posedness of Q-L4-1

For Q-L4-1 to be well-posed:
- (a) Objects defined. **✓ D39 + D45.**
- (b) Generator defined. **✓ D37–D38.**
- (c) Closure derived. **✓ Iteration 15 A4-derivation.**
- (d) Predicate typing fixed. **✓ by parsimony (D49).**
- (e) Morphisms definable. **? Candidate M-a (colimit property) and M-b (evidence-refinement naturality).**

**Q-L4-1 is well-posed *conditional* on the parsimony-selected typing at L3.6.**

### II.2 Test 2 — Is parsimony sound here?

Iteration 17 §III.5–III.6 introduced a *new type of derivation*: selection under parsimony. In this iteration I must audit that this is *epistemically sound* — otherwise Q-L4-1 rests on a faulty foundation.

**The parsimony claim (Iteration 17).** Among three typings consistent with the frozen contract (Heyting, Boolean, Probabilistic), Heyting is *minimally committed*; therefore it is the default.

**The audit question.** Is "minimal commitment" a *legitimate* selection criterion in the corpus's discipline (proof + falsification), or does it smuggle in *design by convenience* under a mathematical-sounding name?

**Three arguments that parsimony is *not* sound here:**

**(A1) The corpus's discipline excludes selection-by-preference.** The corpus has consistently refused to select *any* structure by convention. If the typing is *not forced*, the discipline demands either (i) finding a *forcing* derivation, or (ii) *leaving the typing open* and deriving *conditional* results.

**(A2) Parsimony introduces a *hidden axiom*.** "Minimal commitment" is *not* a formal property of the three typings. It is a *meta-level* judgment about which typings assume more. Formally, all three are *equally* axiomatizable.

**(A3) Downstream results become *conditional*, not *derived*.** If the typing is selected by parsimony and later *revised* (Iteration 17 D50), then any downstream theorem derived under the current typing is *conditional* on that typing's persistence.

**Verdict.** Parsimony as *final* selection is *not* sound. But parsimony as *default pending derivation* may be acceptable *if* the corpus explicitly marks downstream results as *conditional on the default*.

### II.3 Test 3 — Is there a *forcing* derivation for the typing?

Iteration 17 §III.5–III.6 claimed no forcing derivation exists. Let me search for one.

**Candidate forcing route via D42 (dynamic competitors).**

D42 requires competitors to be *generated dynamically* and *excluded dynamically*. Consider a hypothesis \(h\) and a *new competitor* \(h'\) that is generated *after* an initial exclusion of all competitors.

**Heyting semantics:** The exclusion-value \(e_h\) *changes* when \(h'\) is generated. If \(h'\) is generated but *not* excluded, \(e_h\) drops. If \(h'\) *fails* to be excluded, \(e_h\) may be *undetermined* (neither 0 nor 1).

**Boolean semantics:** The exclusion-value \(e_h\) is *binary*. When \(h'\) is generated, \(e_h\) flips to \(0\) (unless \(h'\) can be excluded *immediately*).

**Probabilistic semantics:** The exclusion-value \(e_h\) is a *probability*, updated by *Bayesian conditioning* on the new competitor. Requires *prior over hypotheses*.

**Distinguishing test.** In D42's *open-world* setting, when a new competitor is generated but *not yet evaluated*, what is the correct exclusion-value?

- **Boolean:** Undefined — the semantics requires immediate evaluation. **Fails to capture "not yet evaluated" states.**
- **Probabilistic:** Requires prior. **Fails without priors.**
- **Heyting:** The value is *undetermined* — a Heyting value \(u\) such that \(\neg\neg u \ne u\). **Captures "not yet evaluated" correctly.**

**Consequence.** D42's *dynamic* generation of competitors *forces* the semantics to admit *intermediate* values (not-yet-evaluated). Boolean and Probabilistic *cannot* represent this without additional structure. **Heyting is *forced* by D42.**

**This is a *forcing* derivation, not parsimony.**

### II.4 Revising Iteration 17's methodological claim

**Original Iteration 17 claim.** Type P-Heyting is selected by *parsimony*.

**Revised (Iteration 18).** Type P-Heyting is *forced* by D42 + the requirement that competitors can be generated *before* being evaluated.

**Formal derivation.**

**Theorem (L3.6-Forcing).** The predicate lattice is *at least Heyting-valued* over \([0,1]\) with threshold \(\tau\).

**Proof.**
1. By D41, predicate values are *magnitudes* in \([0,1]\).
2. By D42, competitors are *generated dynamically*, i.e., a hypothesis space may be *extended*.
3. Consider a hypothesis \(h\) whose exclusion-value \(e_h\) is computed *before* all possible competitors are generated. Since competitors may be generated *later*, \(e_h\) *must admit a value representing "not yet fully evaluated"* that is *neither* \(1\) (fully excluded) *nor* \(0\) (admitted).
4. In a *Boolean* lattice, no such value exists (Boolean has only \(0, 1\)).
5. In a *probabilistic* lattice, this value is represented as a *marginal probability* requiring a prior over hypotheses; the corpus provides no prior (D5 measure structure is over the *carrier*, not over the *hypothesis space*).
6. In a *Heyting* lattice over \([0,1]\), such a value is \(u \in (0, 1)\) with \(\neg\neg u > u\) — the "not-yet-decided" value.
7. Therefore the lattice is at least Heyting-valued over \([0,1]\). \(\square\)

**This converts Iteration 17's parsimony-selection into a forcing derivation.** D49 is now a *derived* fact, not a *default*.

### II.5 Falsification attempts

**Attempt 1 — Could Boolean semantics represent "not-yet-evaluated" via a *third* truth value?**

If we *extend* Boolean to *three-valued* logic (Kleene), then "not-yet-evaluated" is representable. But three-valued Boolean *is* a Heyting algebra (Kleene's \(K_3\) is a Heyting algebra). So this is a *special case* of Heyting, not an alternative.

**Attempt 2 — Could probabilistic semantics work if priors are *derived* from other corpus structures?**

Suppose the corpus's L2 measure structure \(\mu\) provides a *prior over hypotheses*. But \(\mu\) is over the *carrier* \(X\), not over the *hypothesis space* \(\mathcal{H}_Q\). The corpus provides *no* canonical map from \(\mu\) to a prior over \(\mathcal{H}_Q\). **Probabilistic semantics fails without additional structure.**

**Attempt 3 — Could the derivation be circular?**

The derivation uses D41 (threshold) and D42 (dynamic competitors). Both are *frozen* experimental conditions (Iteration 16 §I.4, L3.5). They are *not* derived from the typing. The derivation is *not* circular.

**No falsification found.** D49 is now *derived*, not merely *selected*.

### II.6 Revised status of L3.6

**D49-revised.** The predicate lattice is *Heyting-valued over \([0,1]\) with threshold \(\tau\)*, *forced by D41 + D42*, not merely confirmed by parsimony.

**D50-revised.** Revision of the typing is *excluded by the forcing derivation* unless D41 or D42 is *revised*. The typing is now *stable* under the frozen contract.

### II.7 Nexus Repository instantiation

Nexus 70 GB/day → GitLab Runner:

- The investigation *generates* hypotheses dynamically (backup, repo, CICD, config, external, ...).
- At any point, some hypotheses are *not yet generated*. The exclusion-value for the currently-supported hypothesis \(h\) reflects *only* the competitors generated *so far*.
- **Boolean semantics would over-claim:** it would say "backup is excluded" even though the config hypothesis has not been generated yet.
- **Heyting semantics captures this:** the exclusion-value is *intermediate* — not-yet-fully-decided.
- This matches the *practical* Nexus investigation: the engineer does not claim "backup is excluded" until *all* plausible competitors have been considered.

**DDD application (only now, after the math is clear).**
A Nexus bounded context's determination is *defeasibly determined* — its truth-value is Heyting-valued, not Boolean. This is the *correct* semantics for open-world infrastructure contexts, and it is now *derived*, not merely *chosen*.

### II.8 What this establishes

- **Iteration 17's parsimony argument was *sound but incomplete*.** The typing is *forced*, not merely parsimonious.
- **The forcing route uses D41 + D42.**
- **Type P-Heyting is *derived*, not *selected*.**
- **Q-L4-1 is now well-posed on a *derived* foundation.**

---

## Part III — Re-validation of Q-L4-1

### III.1 With D49-revised, Q-L4-1 is fully well-posed

All ingredients present:
- Objects: \(K_t \sqcup D_Q\), \(D_Q\) Heyting-valued with threshold \(\tau\). **✓**
- Generator: \(\sqcup\). **✓**
- Closure: A4-derived. **✓**
- Predicate typing: Heyting-valued over \([0,1]\), *forced* by D41 + D42. **✓**
- Morphisms: derivable from colimit universal property (M-a) and evidence-refinement naturality (M-b). **✓**

### III.2 Well-posedness confirmed

**Q-L4-1 remains the highest-priority unanswered question.**

### III.3 Why Q-L4-1 must precede Q-E4α-ii, Q-ZOOM-2, and the kernel

1. **Q-E4α-ii (L2.75 typing)** is *orthogonal* to L3.6 (D47); it can wait.
2. **Q-ZOOM-2 (determination internal coherence)** presupposes the DDD boundary category exists; without Q-L4-1, its object is undefined.
3. **The kernel (U4)** will live at L4 (integrated determinations); the L4 category structure must be established before the kernel can be *posed*.

**Q-L4-1 is selected.**

---

## Part IV — Q-L4-1: Do Integrated Determinations Form a Category?

### IV.1 Precise statement

**Objects.** Integrated determinations: pairs \((K_t \sqcup D_Q)\) where \(K_t \in \mathbf{KOS}(\mu)\) and \(D_Q\) is a Heyting-valued determination at anchor \(Q\) with \(\min(s_h, e_h) \ge \tau\).

**Candidate morphisms.**

**(M-a) Colimit morphisms.** For any object \(X \in \mathbf{KOS}(\mu)\) receiving both \(K_t\) and \(D_Q\), the colimit universal property gives a unique morphism \(K_t \sqcup D_Q \to X\).

**(M-b) Evidence-refinement morphisms.** Evidence refines (D27); by naturality of Determine (D46), the integrated determination is *natural* in evidence. This induces a morphism \((K_t \sqcup D_Q) \to (K_{t'} \sqcup D_{Q'})\) whenever \(K_t \to K_{t'}\) and \(E \to E'\).

**(M-c) Threshold-preserving morphisms.** A morphism preserves the threshold-value \(\tau\) if \(\min(s_h, e_h)\) does not drop below \(\tau\) under the morphism.

### IV.2 Candidate category

Define \(\mathbf{DetCat}(\mu)\) with:
- Objects: integrated determinations \((K_t, D_Q)\) where \(D_Q\) is a determined hypothesis at anchor \(Q\).
- Morphisms: pairs \((f_K, f_E)\) where \(f_K: K_t \to K_{t'}\) is a morphism in \(\mathbf{KOS}(\mu)\), \(f_E: E \to E'\) is an evidence refinement, and the determination \(D_Q\) is *preserved* by \(f_E\) (i.e., \(\min(s_h, e_h)\) at \((K_{t'}, E') \ge \tau\) whenever it did at \((K_t, E)\)).
- Composition: pairwise composition.
- Identity: \((id_K, id_E)\).

### IV.3 Does \(\mathbf{DetCat}(\mu)\) form a category?

**Test C1 — Composition.** Given \((f_K, f_E): (K_t, D_Q) \to (K_{t'}, D_{Q'})\) and \((g_K, g_E): (K_{t'}, D_{Q'}) \to (K_{t''}, D_{Q''})\), is \((g_K \circ f_K, g_E \circ f_E)\) a valid morphism?

- **\(g_K \circ f_K\)** is a morphism in \(\mathbf{KOS}(\mu)\) by category axioms of \(\mathbf{KOS}(\mu)\). ✓
- **\(g_E \circ f_E\)** is an evidence refinement by transitivity of refinement. ✓
- **Determination preservation** under composition: If \(D_Q\) is preserved by \(f_E\) and \(D_{Q'}\) is preserved by \(g_E\), then \(D_Q\) is preserved by \(g_E \circ f_E\) *provided* the preservation is *transitive*. By D46 (naturality), preservation is monotone in evidence refinement; monotone \(\circ\) monotone = monotone. ✓

**Test C2 — Associativity.** Inherited from \(\mathbf{KOS}(\mu)\) and evidence-refinement categories. ✓

**Test C3 — Identity.** \((id_K, id_E): (K_t, D_Q) \to (K_t, D_Q)\) is trivially valid. ✓

**Verdict.** \(\mathbf{DetCat}(\mu)\) is a category.

### IV.4 Falsification attempts

**Attempt 1 — Is determination *stable* under all of \(\mathbf{KOS}(\mu)\)'s morphisms?**

No. If \(f_K: K_t \to K_{t'}\) *removes* a dimension that was essential to the *support* \(s_h\), then \(s_h\) drops. By Heyting semantics, \(s_h\) does not necessarily go below \(\tau\) immediately — but it *can*. In such cases, \(\min(s_h, e_h)\) drops below \(\tau\), and \(D_Q\) is *not preserved*.

**Consequence.** The morphism set is *not* all pairs \((f_K, f_E)\). It is *restricted* to pairs where determination is preserved.

**Verdict.** This is a *restriction*, not a failure. The category \(\mathbf{DetCat}(\mu)\) is a *subcategory* of the product \(\mathbf{KOS}(\mu) \times \mathbf{Evid}\).

**Attempt 2 — Are there *non-trivial* morphisms?**

Yes. Consider:
- \(f_K = id_{K_t}\) (state unchanged), \(f_E\) = evidence refinement that adds a new excluded competitor. Then \(\min(s_h, e_h)\) *increases*; determination is preserved. ✓
- \(f_K\) = a *monotone* transition that adds a supporting observation; \(f_E\) = trivial. Then \(\min(s_h, e_h)\) increases or stays. ✓

**Verdict.** Non-trivial morphisms exist. ✓

**Attempt 3 — Is the category *small enough* to be useful?**

The category's morphisms correspond to *determination-preserving updates*. It has *interesting* morphisms (evidence-refinement-only, monotone-state-transition-only, and combinations) and *trivial* morphisms (identity). Its structure is *rich enough* to encode the corpus's DDD-boundary generation: adjacent contexts (as objects) are related by determination-preserving updates.

**Verdict.** The category is *useful*. ✓

### IV.5 The derived theorem

**Theorem (Q-L4-1).** Integrated determinations (with Heyting-valued thresholded predicates) form a category \(\mathbf{DetCat}(\mu)\) with:

- **Objects:** pairs \((K_t, D_Q)\), \(D_Q\) a determined hypothesis at anchor \(Q\).
- **Morphisms:** determination-preserving pairs \((f_K, f_E)\).
- **Composition:** pairwise.
- **Identity:** \((id_K, id_E)\).
- **Structure:** the category is a *subcategory* of \(\mathbf{KOS}(\mu) \times \mathbf{Evid}\), closed under determination preservation.

**Proof.** Combine IV.2–IV.4. \(\square\)

### IV.6 The L4 architecture is now typed

**D51 (new).** \(\mathbf{DetCat}(\mu)\) is the L4 category. Its objects are integrated determinations; its morphisms are determination-preserving updates.

**D52 (new).** \(\mathbf{DetCat}(\mu)\) is the *canonical* DDD boundary algebra. Two integrated determinations belong to the *same context* iff they are connected by a morphism in \(\mathbf{DetCat}(\mu)\).

### IV.7 Nexus Repository instantiation

Nexus 70 GB/day → GitLab Runner:

- **Object \(A\):** state \(K_t\) with egress = 70 GB/day, cause = CICD (determined).
- **Object \(B\):** state \(K_{t'}\) with egress = 70 GB/day, cause = CICD (determined), plus new exclusion of monitoring hypothesis.
- **Morphism \(A \to B\):** \((id_{K_t}, f_E)\) where \(f_E\) adds the monitoring exclusion. \(A\) and \(B\) are in the *same DDD context*.
- **Object \(C\):** state \(K_{t''}\) with egress = 70 GB/day, cause = *undetermined* (new competitor discovered).
- **No morphism \(A \to C\):** determination is *not* preserved. \(C\) is in a *different* DDD context.

**DDD application (only now, after the math is clear).**
The Nexus bounded context is *not* a fixed set of states. It is a *category* \(\mathbf{DetCat}(\mu)\) whose morphisms are *determination-preserving updates*. A "context change" is a *failure* of determination preservation — the state has left the context. This is the *correct* semantics for open-world infrastructure contexts, and it is now *derived*, not merely *chosen*.

### IV.8 What this establishes

- **L4 is typed:** \(\mathbf{DetCat}(\mu)\) is the DDD boundary algebra.
- **The corpus has a complete ladder** L0–L4, with L3 as the terminal reduction problem.
- **All layers are *derived*, not *posited*.** No parsimony-selection remains at L3.6.
- **The kernel (U4)** now has a *typed* home: it will live at some object of \(\mathbf{DetCat}(\mu)\).

---

## Part V — Status Update

| Item | Before Q-L4-1 | After Q-L4-1 |
|---|---|---|
| Predicate typing | Parsimony-selected | **Forced by D41 + D42** |
| L4 category | Unanchored | **\(\mathbf{DetCat}(\mu)\) derived** |
| DDD boundary algebra | Anchored on generator | **Categorized** |
| Determination preservation | Undeclared | **Morphism condition** |
| Context adjacency | Undeclared | **Morphism adjacency** |
| Q-ZOOM-2 | Deferred | Deferred |
| Q-E4α-ii | Deferred | Deferred |
| Kernel (U4) | Terminal | **Terminal, with typed home** |

**Next question forced by derivation order:**
**Q-ZOOM-2 — Is the determination structure of L3.0 internally coherent as a sub-category of \(\mathbf{DetCat}(\mu)\), or is it only a *labelling* over \(\mathbf{DetCat}(\mu)\)?**

This must precede Q-E4α-ii and the kernel because:
1. L3.0 (Zoom-in) and L4 (\(\mathbf{DetCat}(\mu)\)) must be *related* before the kernel can be posed. The kernel will live at L4, but its *behaviour* is *defined* by L3.0's exploration structure.
2. Whether Determine is a *sub-category* or a *labelling* determines whether L3.0's internal structure *contributes* to L4 or *only* labels it.
3. If labelling, L4 is *self-contained* and the kernel's behaviour depends only on L4. If sub-category, L4 inherits L3.0's structure, and the kernel's behaviour depends on both.

**Q-ZOOM-2 is selected.** Kernel derivation remains terminal.

---

## Part VI — Methodological Note (from parsimony to forcing)

Iteration 18's contribution is not a new theorem at L4 (though it derives \(\mathbf{DetCat}(\mu)\)), but the *audit and replacement* of Iteration 17's methodological innovation (parsimony) with a *forcing derivation* (D49-revised, using D41 + D42).

**The pattern.** Parsimony is a *legitimate heuristic* for identifying *candidate* typings but is *not* a *final* selection criterion. When a typing is selected by parsimony, the corpus must *subsequently* search for a *forcing* derivation. If found, the typing is *derived*. If not found, the corpus must either (i) leave the typing open and derive *conditional* results, or (ii) accept a *branch point* and mark all downstream results as *conditional*.

**Iteration 18 found the forcing derivation.** Type P-Heyting is now *derived*. This *closes* the branch point at L3.6.

**The kernel (U4) remains terminal.** Its home (L4) is now typed. Its *behaviour* will be defined by L3.0's structure once Q-ZOOM-2 clarifies whether L3.0 *contributes to* or merely *labels* L4.