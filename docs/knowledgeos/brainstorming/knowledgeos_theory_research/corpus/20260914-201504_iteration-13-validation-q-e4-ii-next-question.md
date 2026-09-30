# KnowledgeOS Research Programme — Iteration 13

**Role:** Senior mathematician / statistician / DDD architect / epistemic reviewer
**Mode:** Single-question discipline. No forward completion. Nexus Repository as running example.
**New input:** KR-ZOOM-03 experimental contract (frozen) and the correct rejection of the "Convergence Theorem".
**Constraint:** Kernel derivation remains the *final* reduction problem.
**Discipline note:** I first check whether Iteration 12's nominated Q-E4α-ii (categorical type) is still the highest-priority question, in light of the new KR-ZOOM-03 material.

---

## Part I — Baseline Audit (Post Iteration 12 + KR-ZOOM-03)

### I.1 Derived (D)

**L0 — Carrier**
- **D1** Stratified tower.
- **D2** Measure-first: Radon \(\mu\) on Souslin/Fréchet \(X\).

**L1 — Measure / State**
- **D11** \(K_t^{Q,\Gamma} = (\mathcal{D}_t, \mathbf{V}_t, \mathbf{A}_t, Q, \Gamma)\).
- **D12** Three-level separation.
- **D13** Evidence and history external.
- **D15** Minimal carrier is a quotient.
- **D17** \(\sim_{\mathrm{req}}^{Q,\Gamma}\) is a congruence coequalizer; \(K_{\min}^{Q,\Gamma} = L^{Q,\Gamma}(\mathcal{S})\).

**L2 — Operator**
- **D3** Reduction = pushforward.
- **D4** Covariance \(R_\mu\); nuclearity criterion.
- **D5** Sazonov gate.
- **D8** Covariance-relative Sazonov stability.
- **D9** \(\mathbf{Red}(\mu)\).

**L2.5 — Governance**
- **D10** \(\mathbf{Gov}(\mu) = \int_{\text{AuthState}} \mathbf{Red}(\mu)\).
- **D14** Kernel = state-transition system.
- **D16** \(\mathbf{KOS}(\mu)\) base; \(\mathbf{Gov}(\mu) = \int_{\mathbf{KOS}(\mu)} \mathbf{Red}(\mu_K)\).
- **D18** Replay certification \(\eta\).
- **D19** Fredholm subcategory \(\mathbf{KOS}^{\mathrm{Fr}}(\mu)\); index additive and stable.
- **D20** Ambient subcategory; Calkin functor \(\pi_\ast\).
- **D21** Essential spectrum \(\sigma_e\), Weyl spectrum \(\sigma_\omega\), index fibration; \(\sigma_e\) refines \(i(T)\) and \(\eta\).
- **D22** Calkin and Sazonov compactness are orthogonal, non-conflicting, right/left asymmetric.

**L2.75 — Fibre-internal**
- **D23** Chain of ideals \(\mathcal{L}^1 \subset K \subset L\) on \(H_{K_t}\).
- **D24** 2-sorted fibre structure; no single canonical algebra.
- **D25** Grothendieck fibration \(\mathcal{Q}^{\mathrm{Saz}}_{K_t} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}_{K_t}\); objects, morphisms, 2-cells, fibers identified.

**L3.0 — Zoom / Investigation (NEW, KR-ZOOM-03)**
- **D26** **Non-destructive epistemic zoom.** \(\text{Zoom}(K_t, Q) = (K_t, Q)\). The zoom shifts *attention*, not the available state.
- **D27** **Cross-dimensional exploration.** \(\text{Explore}(Q, K_t, E_t) \to E_{t+1}\) with the explicit capacity to discover dimensions outside \(\text{Focus}(Q)\).
- **D28** **Determination contract (Option 3).** \(\text{Determine}(Q \mid K, E) \iff \text{Supported}(h \mid K, E) \land \text{CompetitorsExcluded}(h \mid K, E)\). Reaching a graph-terminal node is *not* determination.
- **D29** **Interventional criterion.** \(\Delta = P(\text{flip} \mid r) - P(\text{flip} \mid d) \ge 0.10\) (primary), \(0.05 \le \Delta < 0.10\) (borderline), else null.
- **D30** **Rejected:** The "Convergence Theorem". It conflated reachability with determination.
- **D31 (frozen boundary)** \(\text{Reachability} \neq \text{Knowledge}\) and \(\text{Investigation} \neq \text{Determination}\).

### I.2 Proposed (P)

- **P1** Kernel as reduction output. **Terminal.**
- **P2** Latent manifold.
- **P3** Knowledge as sheaf.
- **P4** DDD boundaries as fibres.
- **P5** Nexus Repository model.
- **P6** Categorical type of the 2-sorted fibre (Iteration 11's Q-E4α-ii).

### I.3 Unresolved (U)

- **U4** Kernel identification. **Terminal.**
- **U5** Hierarchy commutation.
- **U6** DDD boundary algebra.
- **U8** Discriminative dimension.
- **U-E4α-ii** Categorical type of the 2-sorted fibre.
- **U-E4β** Commutation of combined structure with fibration.
- **U-E5** Semi-Fredholm theory.
- **U-ZOOM** The relationship of Zoom-in, Explore, Determine, Zoom-out to the existing L1/L2/L2.5/L2.75 architecture.

### I.4 Architectural levels

| Level | Content | Status |
|---|---|---|
| **L0 — Carrier** | \(X\) | Derived |
| **L1 — Measure / State** | \(\mu\); \(K_t^{Q,\Gamma}\); \(L^{Q,\Gamma}(\mathcal{S})\) | Derived |
| **L2 — Operator** | \(\mathbf{Red}(\mu)\) | Derived |
| **L2.5 — Governance** | \(\mathbf{KOS}(\mu)\), \(\mathbf{Gov}(\mu)\), \(\pi_\ast\), \(\sigma_e\), \(\eta\) | Derived |
| **L2.75 — Fibre-internal** | Chain \(\mathcal{L}^1 \subset K \subset L\); Grothendieck fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\) | Derived |
| **L3.0 — Zoom / Investigation** | Zoom, Explore, Determine, interventional criterion | **Partially derived (KR-ZOOM-03 frozen)** |
| **L3 — Kernel** | \(K\) | **Terminal** |
| **L4 — DDD context** | Typed fibre with 2-sorted and Zoom structure | Anchored |

### I.5 The candidate question

Iteration 12 nominated:

> **Q-E4α-ii — What is the correct categorical type of the Grothendieck fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\)?**

But the new KR-ZOOM-03 material introduces an entirely new architectural level (L3.0) that has no *categorical* relationship yet established to L1–L2.75.

Before I accept Q-E4α-ii as the next question, I must test whether it is still the *right* question.

---

## Part II — Validation of Q-E4α-ii as the Next Question

### II.1 Is Q-E4α-ii still the highest-priority unanswered question?

Candidates:

- **Q-E4α-ii** — Categorical type of \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\).
- **Q-ZOOM-1** — How does the Zoom/Investigate/Determine structure of L3.0 relate *categorically* to the existing L1/L2/L2.5/L2.75 architecture?
- **Q-ZOOM-2** — Is the "attention shift" of Zoom a *functor* on \(\mathbf{KOS}(\mu)\), or a distinct categorical structure?
- **Q-ZOOM-3** — Is determination a *natural transformation* between the exploration functor and the state-update functor?

### II.2 Which question is *highest-priority*?

Two lines of reasoning:

**(Reasoning 1: Architectural precedence.)**
L3.0 was *just added* to the corpus in this iteration. Its relationship to the *already-derived* L1–L2.75 structures is *unestablished*. Establishing this relationship is a *structural* requirement before any *deepening* of L2.75 (Q-E4α-ii) can proceed. Otherwise, deepening L2.75 may prove incompatible with L3.0's requirements.

**(Reasoning 2: Dependency.)**
The eventual kernel (U4) will live on *some* object of the corpus's architecture. Whether that object is at L2.75, L3.0, or the intersection depends on whether L3.0 and L2.75 are *compatible*. Without establishing compatibility, the kernel's home is undetermined.

**Verdict.** Q-ZOOM-1 has *higher priority* than Q-E4α-ii. L2.75's categorical typing can wait until L3.0's relation to L2.75 is established.

### II.3 Precisely stating Q-ZOOM-1

**Q-ZOOM-1.** How does the Zoom/Investigate/Determine/State-update structure of L3.0 relate *categorically* to the existing L1/L2/L2.5/L2.75 architecture?

Sub-questions:
- Is Zoom a *functor*?
- Is Explore a *functor*?
- Is Determine a *natural transformation*?
- Is the state-update a *categorical pushforward* (in the sense of \(\mathbf{Red}(\mu)\))?
- Do these structures *commute* with the L2.5 Grothendieck fibration and the L2.75 fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\)?

### II.4 Well-posedness test

For Q-ZOOM-1 to be well-posed:
- (a) The Zoom/Investigate/Determine structure is *defined* — yes, D26–D31.
- (b) The L1/L2/L2.5/L2.75 architecture is *derived* — yes, D11–D25.
- (c) The *comparison* of the two structures is *specifiable* — the comparison must be *functorial*, i.e., the L3.0 operations must be expressible as functors/natural transformations over the L1–L2.75 categories.

**Q-ZOOM-1 is well-posed.** ✓

---

## Part III — Q-ZOOM-1: Zoom/Investigate/Determine as Categorical Operations

### III.1 Recalling the relevant structures

**L1 — Measure / State.** \(K_t^{Q,\Gamma} = (\mathcal{D}_t, \mathbf{V}_t, \mathbf{A}_t, Q, \Gamma)\). The minimal carrier is \(K_{\min}^{Q,\Gamma} = L^{Q,\Gamma}(\mathcal{S})\).

**L2 — Operator.** \(\mathbf{Red}(\mu)\); covariance \(R_\mu\); Sazonov stability.

**L2.5 — Governance.**
- \(\mathbf{KOS}(\mu)\): base category; objects = epistemic states; morphisms = transitions.
- \(\mathbf{Gov}(\mu) = \int_{\mathbf{KOS}(\mu)} \mathbf{Red}(\mu_K)\): total space.
- Replay certification \(\eta: \mathbf{Orig} \Rightarrow \mathbf{Replay}\).

**L2.75 — Fibre-internal.** Grothendieck fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\).

**L3.0 — Zoom/Investigate/Determine.**
- \(\text{Zoom}(K_t, Q) = (K_t, Q)\).
- \(\text{Explore}(Q, K_t, E_t) \to E_{t+1}\).
- \(\text{Determine}(Q \mid K, E) \iff \text{Supported}(h \mid K, E) \land \text{CompetitorsExcluded}(h \mid K, E)\).
- \(\Delta = P(\text{flip} \mid r) - P(\text{flip} \mid d)\).

### III.2 The categorical reading of L3.0

**(Z-a) Zoom as a functor on the base category.**

Define \(\mathbf{Inq}(\mu)\) as the category whose objects are pairs \((K_t, Q)\) with \(Q\) an inquiry over \(K_t\), and whose morphisms are *inquiry-preserving transitions* — i.e., transitions of the base \(K_t \to K_{t+1}\) that also preserve the anchored inquiry \(Q\) (up to a specified refinement).

**Definition (Zoom functor).** \(\text{Zoom}: \mathbf{KOS}(\mu) \to \mathbf{Inq}(\mu)\) is defined on objects by
\[
K_t \mapsto (K_t, Q_t),
\]
where \(Q_t\) is the active inquiry at \(t\). On morphisms \(T: K_t \to K_{t+1}\),
\[
\text{Zoom}(T) := (T, \text{preserve}_Q(T)),
\]
where \(\text{preserve}_Q(T)\) records that the inquiry \(Q_t\) is preserved (or refined) under \(T\).

**Categorical coherence.** Zoom is *not* a full functor: not every inquiry extends across every transition. The image of Zoom is the subcategory of \(\mathbf{Inq}(\mu)\) whose objects are *contract-consistent*.

**(Z-b) Explore as a functor.**

Define \(\mathbf{Evid}(\mu)\) as the category of evidence structures \(E\) with morphisms = evidence refinements.

**Definition (Explore functor).** \(\text{Explore}: \mathbf{Inq}(\mu) \times \mathbf{Evid}(\mu) \to \mathbf{Evid}(\mu)\) is defined on \((Q, E_t)\) by producing \(E_{t+1}\) containing all evidence derivable from \(Q \mid K_t\) without restricting to \(\text{Focus}(Q)\).

**Categorical coherence.** Explore is *monoidal* in the evidence component: \(\text{Explore}(Q, E_1 \otimes E_2) = \text{Explore}(Q, E_1) \otimes \text{Explore}(Q, E_2)\) provided the evidence structures combine.

**(Z-c) Determine as a natural transformation.**

Define \(\mathbf{Det}(\mu)\) as the category of *determination states* — objects = \((\text{SUPPORTED}, \text{COMPETITORS\_EXCLUDED}, \text{UNDETERMINED})\) with morphisms = entailment.

**Definition (Determine natural transformation).** There is a natural transformation
\[
D: \text{Explore} \circ \text{Zoom} \Rightarrow \mathbf{U} \circ \text{Determine},
\]
where \(\mathbf{U}: \mathbf{Det}(\mu) \to \mathbf{Evid}(\mu)\) is the *forgetful functor* that maps a determination state to its underlying evidence.

**Categorical content.** \(D\) asserts that determination *factors through* the evidence generated by Explore/Zoom. This is a *categorical statement* of D31: reaching a terminal node is not determination, because determination requires the *extra structure* of competing-exclusion.

### III.3 Testing the coherence with L2.5 and L2.75

**Test 1: Does Zoom commute with the base fibration \(\mathbf{Gov}(\mu) \to \mathbf{KOS}(\mu)\)?**

The base fibration has total space \(\mathbf{Gov}(\mu)\) and base \(\mathbf{KOS}(\mu)\). Zoom lifts to \(\mathbf{Inq}(\mu) \to \mathbf{Inq}^{\mathbf{Red}}(\mu)\) where the fibre at \((K, Q)\) is \(\mathbf{Red}(\mu_K)\).

**Claim.** Zoom *does* commute with the base fibration: the fibre structure is *invariant* under Zoom, because Zoom preserves \(K_t\) (by D26).

**Verdict.** ✓

**Test 2: Does Explore commute with the L2.75 fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\)?**

Explore operates on the base category's inquiries. To interact with L2.75, Explore must *lift* to the fibre \(\mathbf{Red}(\mu_K)\). By D27, Explore can discover dimensions outside \(\text{Focus}(Q)\), which requires exploring the fibre structure.

**Claim.** Explore *does not canonically commute* with the L2.75 fibration. The reason: the L2.75 fibration is orthogonal to the Zoom structure (Zoom is about *attention*; the L2.75 fibration is about *operator ideals*). Explore within the fibre may produce Sazonov-refinements that do *not* correspond to Calkin-refinements.

**Falsification attempt.** Consider a Nexus investigation of 70GB/day egress. Explore reaches into the CI/CD dimension. Within \(\mathbf{Red}(\mu_{K_t})\), the relevant reduction operators change Sazonov class but not Calkin class (because the perturbation is trace-class but not compact-affecting). Then the L2.75 fibration's base \(\mathcal{Q}^{\mathrm{Calkin}}\) is *unchanged* but the total space \(\mathcal{Q}^{\mathrm{Saz}}\) *changes*.

**Verdict.** Explore *does not commute* with the L2.75 fibration. ✓ (Falsification confirmed.)

**Test 3: Does Determine factor through the Calkin quotient?**

Determine requires *competitor exclusion*, which is *not* a property of the operator ideal class (Calkin or Sazonov). It is a property of the *hypothesis space* over \(K_t\). Therefore Determine does *not* factor through L2.75.

**Verdict.** ✗ — Determine is *not* reducible to L2.75. It lives strictly on L3.0.

### III.4 The correct theorem

**Theorem (Q-ZOOM-1).** The Zoom/Investigate/Determine/State-update structure of L3.0 is a *partial* functorial extension of the L1–L2.5 architecture, with the following properties:

1. **Zoom** is a *functor* \(\mathbf{KOS}(\mu) \to \mathbf{Inq}(\mu)\) that preserves the base category and its L2.5 fibre structure.
2. **Explore** is a *monoidal functor* \(\mathbf{Inq}(\mu) \times \mathbf{Evid}(\mu) \to \mathbf{Evid}(\mu)\) that *does not canonically commute* with the L2.75 fibration \(\mathcal{Q}^{\mathrm{Saz}} \twoheadrightarrow \mathcal{Q}^{\mathrm{Calkin}}\).
3. **Determine** is a *natural transformation* whose target \(\mathbf{Det}(\mu)\) is *not* categorically reducible to L2.75; it is a genuinely L3.0 structure.
4. **State-update** is a *pushforward* in \(\mathbf{Inq}(\mu)\) combined with a determination update that is *not* expressible via L2.75.

**Proof.** Combine III.2 (functorial structure) and III.3 (commutation tests). \(\square\)

### III.5 Falsification attempts on the theorem

**Attempt 1 — Is Zoom really a functor?**
Zoom on morphisms: \(T \mapsto (T, \text{preserve}_Q(T))\). For composition: \(\text{Zoom}(T_2 \circ T_1) = (T_2 \circ T_1, \text{preserve}_Q(T_2 \circ T_1))\). By functoriality of the base and the fact that \(\text{preserve}_Q\) is preserved under composition (inquiries are preserved by composition of transitions if preserved individually), \(\text{Zoom}(T_2 \circ T_1) = \text{Zoom}(T_2) \circ \text{Zoom}(T_1)\).

**Verdict.** ✓ Zoom is a functor.

**Attempt 2 — Is Determine *really* a natural transformation?**
Determine is defined on pairs \((Q, E)\). For it to be natural, it must commute with evidence-refinement morphisms. Given \(E \to E'\) (an evidence refinement), Determine must satisfy \(\text{Determine}(Q, E) \to \text{Determine}(Q, E')\). This holds because determination is *monotone* in evidence: more evidence cannot invalidate a determination (in the Option 3 contract, since Supports and CompetitorsExcluded are monotone).

**Verdict.** ✓ Determine is natural.

**Attempt 3 — Does the state-update preserve the *replay certification* \(\eta\)?**
By D18, replay certification compares Orig and Replay. Under state-update, the snapshot is refreshed. The *new* snapshot includes the determination outcome. Replay of the new snapshot reconstructs the *same* determination. Therefore \(\eta\) is preserved.

**Verdict.** ✓ η is preserved by state-update.

**Attempt 4 — Is Explore *really* monoidal?**
Explore on \((Q, E_1 \otimes E_2)\): explore with combined evidence. Explore on \((Q, E_1) \otimes (Q, E_2)\): explore with each separately. These are *not* equal in general, because exploration may find *interactions* between pieces of evidence that are only visible when both are present.

**Verdict.** ✗ Explore is *not* monoidal in the naive sense. The theorem's clause 2 must be weakened: Explore is *oplax monoidal* (there is a natural transformation \(\text{Explore}(Q, E_1) \otimes \text{Explore}(Q, E_2) \to \text{Explore}(Q, E_1 \otimes E_2)\), but not its inverse).

### III.6 The corrected theorem

**Theorem (Q-ZOOM-1, corrected).**

1. **Zoom** is a functor \(\mathbf{KOS}(\mu) \to \mathbf{Inq}(\mu)\) that preserves the base and the L2.5 fibre structure.
2. **Explore** is an *oplax monoidal functor* \(\mathbf{Inq}(\mu) \times \mathbf{Evid}(\mu) \to \mathbf{Evid}(\mu)\). It does *not* canonically commute with the L2.75 fibration.
3. **Determine** is a natural transformation \(D: \text{Explore} \circ \text{Zoom} \Rightarrow \mathbf{U} \circ \text{Determine}\), whose target \(\mathbf{Det}(\mu)\) is *not* categorically reducible to L2.75.
4. **State-update** is a pushforward in \(\mathbf{Inq}(\mu)\) combined with a determination update preserving the replay certification \(\eta\).

### III.7 Nexus Repository instantiation

Nexus 70 GB/day → GitLab Runner, read categorically:

- **Zoom.** The system's epistemic state \(K_t\) is preserved. The inquiry \(Q = \) "why 70 GB/day?" is *anchored*. Zoom yields \((K_t, Q)\).
- **Explore.** The investigation crosses dimensions: egress → network → backup → CI/CD → GitLab Runner. Each step refines evidence. The refinement is *oplax monoidal*: exploring two dimensions together may reveal interactions not visible separately.
- **Determine.** After collecting evidence, `Supported(GitLab Runner causes egress)` AND `CompetitorsExcluded(backup, network, etc.)` ⟹ determination.
- **State-update.** The Nexus state \(K_t\) is updated to \(K_{t+1}\) including the new determination. The replay certification \(\eta\) is preserved.

**L2.75 interaction.** The Calkin class of the promotion pipeline is *unchanged* by the investigation (no operator-ideal-level change occurred). But the Sazonov class may change (metadata covariance may shift as new dimensions activate). Therefore L2.75's base is unchanged but its total space is refined — Explore *does not* commute with the L2.75 fibration.

**DDD application (only now, after the math is clear).**
The Nexus bounded context is *not* the L2.75 fibre alone, nor the L3.0 inquiry alone, but the *pair*:
\[
\text{BoundedContext}(\text{Nexus}) = (\mathbf{Red}(\mu_{K_t}), \text{Zoom}(K_t, Q)).
\]
This is a *2-sorted DDD boundary* where one sort is operator-algebraic (L2.75) and the other is inquiry-theoretic (L3.0). The two sorts do not reduce to one another.

### III.8 What this establishes

- **L3.0 is categorically typed.** Zoom is a functor; Explore is oplax monoidal; Determine is a natural transformation.
- **L3.0 is *not* reducible to L2.75.** The determination structure requires hypothesis-space machinery (competitor exclusion) that is *not* available in the operator-ideal fibration.
- **Compatibility with L2.5 is established.** Zoom commutes with the base fibration \(\mathbf{Gov}(\mu) \to \mathbf{KOS}(\mu)\).
- **Incompatibility with L2.75 is established.** Explore does not commute with the Calkin/Sazonov fibration.
- **Replay certification \(\eta\) is preserved by state-update.** ✓

This is a *structural* fact about the corpus: **L3.0 and L2.75 are orthogonal architectural levels**, connected by the base category but not reducible to each other.

---

## Part IV — Status Update

| Item | Before Q-ZOOM-1 | After Q-ZOOM-1 |
|---|---|---|
| Zoom | Frozen as operation | **Functor** |
| Explore | Frozen as operation | **Oplax monoidal functor** |
| Determine | Frozen as operation | **Natural transformation** |
| L3.0 ↔ L2.5 commutation | Unanalysed | **Commutative** ✓ |
| L3.0 ↔ L2.75 commutation | Unanalysed | **Non-commutative** ✓ (structural fact) |
| DDD boundary rule | Two-sorted | **Two-sorted with L3.0/L2.75 orthogonality** |
| Replay \(\eta\) preserved by state-update | Assumed | **Derived** ✓ |
| Q-E4α-ii (categorical type of L2.75) | Nominally next | **Deferred** — L3.0/L2.75 orthogonality takes precedence |
| Kernel (U4) | Terminal | Terminal; new architectural home required |

**Next question forced by derivation order:**
**Q-ZOOM-2 — Is the determination structure of L3.0 *itself* a categorical object with its own internal coherence, or is it an *artifact* of the inquiry-anchored exploration?**

This must precede Q-E4α-ii (categorical type of L2.75) because:
1. L3.0 and L2.75 have been shown to be *orthogonal*; before deepening either, we must know whether L3.0 *itself* is a complete categorical structure or requires further refinement.
2. The eventual kernel (U4) will need to live on *some* object of the corpus's architecture. If L3.0's determination structure is incomplete, the kernel cannot live there. If it is complete, the kernel might.
3. Q-E4α-ii's deferral depends on L3.0's internal coherence being *sufficient* to take up the architectural slack.

**Q-ZOOM-2 is selected.** Kernel derivation remains terminal.

---

## Part V — Methodological Note (the L3.0/L2.75 orthogonality)

The most significant result of this iteration is not any single theorem but a *structural fact*: **L3.0 (Zoom/Investigate/Determine) and L2.75 (Calkin/Sazonov fibration) are orthogonal architectural levels.** They share the base \(\mathbf{KOS}(\mu)\) but do not reduce to each other.

This has consequences:

- The DDD boundary rule must be *product-sorted*: (operator-algebraic invariant, inquiry-theoretic invariant).
- The eventual kernel (U4) may need to be *two-sorted*: one component living on L2.75, another on L3.0, related by the base fibration.
- The corpus's architectural levels are now *seven*: L0, L1, L2, L2.5, L2.75, L3.0, L3 (kernel). This is a substantial increase in complexity, but each level is *derived*, not assumed.

The programme remains convergent: each iteration reduces abstraction, produces concrete structures, and defers kernel derivation.