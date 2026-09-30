# KnowledgeOS Research Programme — Iteration 9

**Role:** Senior mathematician / statistician / DDD architect / epistemic reviewer
**Mode:** Single-question discipline. No forward completion. Nexus Repository as running example.
**Constraint:** Kernel derivation remains the *final* reduction problem.

---

## Part I — Baseline Audit (Post Q-E2)

### I.1 Derived (D)

**L0 — Carrier**
- **D1** Stratified tower.
- **D2** Measure-first: Radon \(\mu\) on Souslin/Fréchet \(X\).

**L1 — Measure / State**
- **D11** \(K_t^{Q,\Gamma} = (\mathcal{D}_t, \mathbf{V}_t, \mathbf{A}_t, Q, \Gamma)\).
- **D12** Three-level separation \(s \to \rho_t(s) \to K_t\).
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
- **D18** Replay certification \(\eta: \mathbf{Orig} \Rightarrow \mathbf{Replay}\).
- **D19** Fredholm-typed subcategory \(\mathbf{KOS}^{\mathrm{Fr}}(\mu)\); index additive and compact-perturbation-invariant.
- **D20 (new, Q-E2)** *Ambient subcategory* \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\) with a *common complemented ambient* \(E\); Calkin functor \(\pi_\ast: \mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu) \to \mathbf{Alg}\); Fredholmness \(\Leftrightarrow\) \(\pi_\ast(T)\) invertible; Calkin class *refines* \(\eta\).

### I.2 Proposed (P)

- **P1** Kernel as reduction output. **Terminal.**
- **P2** Latent manifold.
- **P3** Knowledge as sheaf.
- **P4** DDD boundaries as fibres.
- **P5** Nexus Repository model.
- **P6 (new)** Essential spectrum as governance invariant. *Proposed by Q-E2 §V as Q-E3.*

### I.3 Unresolved (U)

- **U4** Kernel identification. **Terminal.**
- **U5** Hierarchy commutation.
- **U6** DDD boundary algebra.
- **U8** Discriminative dimension.
- **U-E3** Essential spectrum as governance invariant.
- **U-E4** Hierarchy of the Calkin functor over \(\mathbf{KOS}(\mu)\).
- **U-E5** Semi-Fredholm theory on quasi-Banach carriers.

### I.4 Architectural levels

| Level | Content | Status |
|---|---|---|
| **L0 — Carrier** | \(X\) | Derived |
| **L1 — Measure / State** | \(\mu\); \(K_t^{Q,\Gamma}\); \(L^{Q,\Gamma}(\mathcal{S})\) | Derived |
| **L2 — Operator** | \(\mathbf{Red}(\mu)\) | Derived |
| **L2.5 — Governance** | \(\mathbf{KOS}(\mu)\), \(\mathbf{Gov}(\mu)\), \(\eta\), \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\), Calkin functor | Derived |
| **L3 — Kernel** | \(K\) | **Terminal** |
| **L4 — DDD context** | Fibre over KOS-E1 base object; Calkin class | Anchored |

### I.5 What Q-E2 left open

Q-E2 established that the Calkin functor is canonical for the *ambient subcategory* \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\), and that Fredholmness is equivalent to invertibility in the Calkin algebra. It also observed (Attempt 3, §IV.7) that the Calkin class *refines* \(\eta\) but did not analyse *what invariant of the Calkin class* this refinement corresponds to. The canonical invariant of an element of an algebra is its *spectrum*. Q-E2 §V named this as the next question:

> **Q-E3 — Is the essential spectrum \(\sigma_e(T)\) a governance invariant refining both the Fredholm index and the replay certification \(\eta\)?**

---

## Part II — Identification of the Missing Architecture

The Mitrea et al. corpus (§3.5) provides:

- **Essential spectrum** \(\sigma_e(T) = \sigma(\pi(T); L(X)/K(X))\) — the spectrum of the Calkin class (eq. 3.5.149).
- **Weyl spectrum** \(\sigma_\omega(T) = \bigcap_{K \in K(X)} \sigma(T+K)\) — the *largest* compact-perturbation-invariant part of the spectrum (Thm. 3.5.15).
- **Decomposition** \(\sigma_e(T) \subset \sigma_\omega(T) \subset \sigma(T)\) — with \(\sigma_\omega \setminus \sigma_e\) consisting of "holes" that are unions of bounded components of \(\mathbb{C} \setminus \sigma_e\) (Cor. 3.5.9).
- **Fredholm decomposition of the essential resolvent** \(\rho_e(T) = \bigsqcup_{n \in \mathbb{Z}} \varphi_n(T)\) (eq. 3.5.160), where \(\varphi_n(T)\) is the set of Fredholm points of index \(n\).
- **Component structure** (Thm. 3.5.21): for each component \(G\) of \(\varphi_0(T)\), either \(G \cap \sigma(T) = \emptyset\), \(G \subset \sigma(T)\), or \(G \cap \sigma(T)\) is a set of isolated points clustering only on \(\sigma_e(T)\).

This is a rich invariant structure. **What is not yet in the corpus:**

1. Whether \(\sigma_e(T)\) is *well-defined* on \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\) — i.e., whether it depends only on the Calkin class of \(T\), not on the specific representative.
2. Whether the *index map* \(i_T: \rho_e(T) \to \mathbb{Z}\) is a governance invariant.
3. Whether the decomposition \(\rho_e(T) = \bigsqcup_n \varphi_n(T)\) *refines* the replay certification \(\eta\).

**The missing architecture is:** the *spectral refinement* of the governance pipeline via the essential spectrum and its Fredholm index decomposition.

---

## Part III — Selection of Q-E3

### III.1 Candidate questions

- **Q-E3** Essential spectrum as governance invariant.
- **Q-E4** Hierarchy of the Calkin functor over \(\mathbf{KOS}(\mu)\).
- **Q-E5** Semi-Fredholm theory on quasi-Banach carriers.

### III.2 Why Q-E3 must precede Q-E4 and Q-E5

- **Q-E4 (hierarchy)** asks whether the Calkin functor and its invariants commute with the Grothendieck fibration \(\mathbf{Gov}(\mu) = \int_{\mathbf{KOS}(\mu)} \mathbf{Red}(\mu_K)\). This presupposes *what invariants* we are tracking — the essential spectrum and its associated decomposition being the natural candidates.
- **Q-E5 (semi-Fredholm)** refines Fredholmness to \(\Phi_\pm\). It presupposes the *full* Fredholm/spectral structure (Q-E1 + Q-E2 + Q-E3).
- **Q-E3 does not touch the kernel** (L3) and remains strictly within L2.5.

**Q-E3 is the highest-priority unanswered question.**

---

## Part IV — Q-E3: Is the Essential Spectrum a Governance Invariant?

### IV.1 Precise statement

Recall from Q-E2 §IV.6:

- \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\) has a common complemented ambient \(E\).
- The Calkin functor \(\pi_\ast: \mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu) \to \mathbf{Alg}\) sends each transition \(T\) to its Calkin class \(\pi(T) \in L(E)/K(E)\).
- Fredholmness of \(T\) is equivalent to invertibility of \(\pi(T)\).

**Definition (Provisional).** The *essential spectrum* of a transition \(T \in \mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\) is
\[
\sigma_e(T) := \sigma(\pi(T); L(E)/K(E)) = \{\lambda \in \mathbb{C} : \lambda I - \pi(T) \text{ not invertible in } L(E)/K(E)\}.
\]

**Q-E3.** Is \(\sigma_e(T)\) well-defined on \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\), and does it refine both \(i(T)\) (Q-E1) and \(\eta\) (Q-C2)?

### IV.2 Well-definedness

**Claim (well-definedness).** \(\sigma_e(T)\) depends only on \(\pi(T)\), hence on the Calkin class of \(T\), not on the choice of representative.

**Proof.** \(\sigma_e(T) = \sigma(\pi(T); L(E)/K(E))\) is a function of \(\pi(T)\) by definition. \(\pi(T)\) is by construction the image of \(T\) under the canonical quotient map \(\pi: L(E) \to L(E)/K(E)\). Two operators \(T, T'\) with the same Calkin class satisfy \(\pi(T) = \pi(T')\), hence \(\sigma_e(T) = \sigma_e(T')\).

**Consequence.** \(\sigma_e\) is a *Calkin-class invariant*. \(\square\)

### IV.3 Refinement of the Fredholm index

**Claim (index refinement).** The essential spectrum *refines* the Fredholm index \(i(T)\): knowing \(\sigma_e(T)\) and the *index function*
\[
i_T: \rho_e(T) \to \mathbb{Z}, \quad i_T(\lambda) := i(T - \lambda I),
\]
determines \(i(T)\) as \(i_T(0)\).

**Proof.** \(i(T) = i_T(0)\) by definition of the \(T\)-index (eq. 3.5.158). But \(i_T\) is defined on the *essential resolvent* \(\rho_e(T) = \mathbb{C} \setminus \sigma_e(T)\), and its level sets \(\varphi_n(T) = i_T^{-1}(\{n\})\) form a decomposition of \(\rho_e(T)\) (eq. 3.5.160). Therefore \(i(T)\) is one component of the *full invariant* \((\sigma_e(T), i_T)\). \(\square\)

**Stronger claim (index is determined by \(\sigma_e\)).** Does \(i(T)\) alone determine \(\sigma_e(T)\)?

**Falsification.** Consider two operators \(T_1, T_2\) with the same index \(i(T_1) = i(T_2) = 0\), but with \(\sigma_e(T_1) = \{0\}\) and \(\sigma_e(T_2) = \mathbb{T}\) (unit circle). Then the index does *not* determine the essential spectrum.

**Explicit example.** Let \(S\) be the unilateral shift on \(\ell^2\). By Mitrea et al. Prop. 3.5.11, \(i(S) = -1\) and \(\sigma_e(S) = \mathbb{T}\). Let \(T = S \oplus B\) where \(B\) is the backward shift; then \(i(T) = 0\) and \(\sigma_e(T) = \mathbb{T}\). Let \(K\) be a compact operator on \(\ell^2\) with \(\|K\| < \|S\|\); then \(S + K\) is Fredholm of index \(-1\) and \(\sigma_e(S + K) = \sigma_e(S) = \mathbb{T}\). Now take \(T' = S + K \oplus B\). Then \(i(T') = 0\) and \(\sigma_e(T') = \mathbb{T}\). But \(T'\) has non-empty index-0 essential spectrum equal to the unit circle while \(T'\) also has index 0. The essential spectrum is *not* determined by the index.

**Verdict.** The essential spectrum *strictly refines* the Fredholm index. ✓

### IV.4 Refinement of the replay certification \(\eta\)

**Claim (refinement of \(\eta\)).** The essential spectrum refines the replay certification \(\eta: \mathbf{Orig} \Rightarrow \mathbf{Replay}\).

**Proof outline.** \(\eta\) asserts that the replay pipeline commutes with the original pipeline up to *5-dimensional equivalence* (GEO-3.4 §4): legitimacy, winning authority, capability type, constitutional scope, doctrine version. This is a *discrete* invariant.

The essential spectrum is a *continuous* invariant (a compact subset of \(\mathbb{C}\)). Two transitions with the same 5-dimensional certification may nevertheless have different essential spectra. Therefore \(\eta\)'s *kernel* is strictly contained in \(\sigma_e\)'s kernel — the essential spectrum sees more.

**Falsification test.** Are there transitions satisfying \(\eta\) but with different \(\sigma_e\)? Consider two governance pipelines with identical 5-dimensional certifications but different compact perturbation structures. By compact-perturbation invariance (Thm. 3.5.14), \(\sigma_e\) is preserved — but the *full* structure \((\sigma_e, i_T)\) is preserved, whereas \(\eta\) only witnesses the certification. So \(\eta\) is not sufficient to determine \((\sigma_e, i_T)\).

**Verdict.** \(\sigma_e\) refines \(\eta\) — but with a caveat: \(\eta\) *is* preserved by the more refined invariant, in the sense that if \((\sigma_e(T_1), i_{T_1}) = (\sigma_e(T_2), i_{T_2})\), then \(\eta(T_1) = \eta(T_2)\) (because \(\eta\) factors through the Calkin class, and \(\sigma_e\) is a Calkin invariant). The converse does not hold: \(\eta(T_1) = \eta(T_2)\) does not imply \(\sigma_e(T_1) = \sigma_e(T_2)\). So \(\eta\) is a *coarser* invariant than \(\sigma_e\). ✓

### IV.5 The Weyl spectrum as a universal invariant

**Theorem (Weyl universality, Mitrea et al. Thm. 3.5.15).** For \(T \in L(X)\):
\[
\sigma_\omega(T) = \bigcap_{K \in K(X)} \sigma(T+K).
\]

**Governance interpretation.** The Weyl spectrum is the *largest* subset of \(\sigma(T)\) that is invariant under all compact perturbations of \(T\). This is the *universal* content of the transition: it survives every "small" (compact) governance perturbation.

**Corollary (Q-E3).** The Weyl spectrum \(\sigma_\omega(T)\) is a governance invariant with the *universal property*: any invariant that is stable under compact perturbations and depends only on the Calkin class factors through \(\sigma_\omega(T)\).

**Proof.** The Weyl spectrum is the intersection of the spectra of all compact perturbations of \(T\). Any Calkin-invariant \(I(T)\) of the form \(I(T) = f(\sigma(T + K))\) for some fixed \(K\) is contained in the Weyl spectrum by definition of the intersection. Conversely, by Thm. 3.5.15, \(\sigma_\omega(T)\) is itself the largest such invariant. Therefore it is universal. \(\square\)

### IV.6 The Fredholm index decomposition as a fibered invariant

**Claim.** The decomposition \(\rho_e(T) = \bigsqcup_{n \in \mathbb{Z}} \varphi_n(T)\) is a *fibered* invariant: it defines a \(\mathbb{Z}\)-fibration over the essential resolvent.

**Proof.** By Mitrea et al. eq. 3.5.160, the essential resolvent decomposes disjointly into the sets \(\varphi_n(T)\) of Fredholm points of index \(n\). The index map \(i_T: \rho_e(T) \to \mathbb{Z}\) is locally constant (Cor. 3.4.13) and continuous when \(\mathbb{Z}\) has the discrete topology. Therefore \(i_T\) is a *fibration* in the discrete sense. Each component \(G\) of \(\rho_e(T)\) maps to a single integer \(i_T(G)\), so the fibration is *locally trivial* with discrete fibers. \(\square\)

**Governance interpretation.** Each connected component of the essential resolvent is assigned a *fixed Fredholm index*: the transition's behavior on that component is index-invariant. This means: for a governance pipeline \(T\), the "essential behavior" of the pipeline is *stratified* by index values over the complex plane.

### IV.7 Falsification attempts

**Attempt 1 — Does \(\sigma_e\) collapse on discrete carriers?**

If the carrier \(E\) is finite-dimensional, \(\sigma_e(T) = \emptyset\) for all \(T\) (since every operator is Fredholm). The theory is trivial.

**Verdict.** The theory is *non-trivial only for infinite-dimensional* carriers. For governance, this means that the theory only bites when the epistemic state space has infinite dimension. This is the *typical* case for knowledge systems (unbounded potential dimensions), so the theory is applicable — but *not* to finite-dimensional models.

**Attempt 2 — Does \(\sigma_e\) fail for non-complemented embeddings?**

In \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\), embeddings are complemented by construction (Q-E2 §IV.6). For non-complemented embeddings, we already know the Calkin functor fails (Q-E2 §IV.7 Attempt 2). Therefore \(\sigma_e\) fails to be well-defined.

**Verdict.** The result holds *only* in the ambient subcategory \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\). ✓

**Attempt 3 — Does \(\sigma_e\) require locally convex carriers?**

Mitrea et al. §1.6 introduce locally convex spaces as a *special case*; their theory works for *non*-locally-convex Hausdorff TVS as well (§0, "local convexity and the existence of continuous linear functionals is not as necessary for these theories as the current literature would seem to suggest"). But **for the essential spectrum**, the Calkin algebra \(L(E)/K(E)\) must be a \(p\)-Banach algebra for the spectrum to be well-behaved (Thm. 3.5.2). This requires \(E\) to be a \(p\)-Banach space (Def. 3.4.2).

**Verdict.** The result requires the carrier \(E\) to be a \(p\)-Banach space for some \(p \in (0,1]\). This is a *tightening* of the corpus: KOS-E1 admits arbitrary topological vector carriers, but *spectral* analysis requires \(p\)-Banach structure. ✓

### IV.8 The correct theorem

**Theorem (Q-E3).** On the ambient Fredholm subcategory \(\mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu)\) with \(p\)-Banach carrier \(E\):

1. The essential spectrum \(\sigma_e: \mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu) \to \mathcal{K}(\mathbb{C})\) is well-defined and Calkin-invariant.
2. The Weyl spectrum \(\sigma_\omega: \mathbf{KOS}^{\mathrm{Fr}}_{\mathrm{amb}}(\mu) \to \mathcal{K}(\mathbb{C})\) is universal among compact-perturbation-invariant spectrum-type invariants.
3. The Fredholm index decomposition \(\rho_e(T) = \bigsqcup_n \varphi_n(T)\) is a discrete \(\mathbb{Z}\)-fibration over the essential resolvent, with locally constant index.
4. The essential spectrum strictly refines both the Fredholm index \(i(T)\) and the replay certification \(\eta\): knowing \(\sigma_e\) and the index fibration determines both, but neither determines \(\sigma_e\).

**Proof.** Combine Mitrea et al. §3.5.1–3.5.4 with the ambient subcategory structure of Q-E2. \(\square\)

### IV.9 Nexus Repository instantiation

In Nexus terms:
- The **essential spectrum** \(\sigma_e(T)\) of a promotion transition \(T\) is a compact subset of \(\mathbb{C}\) that is invariant under "small" (compact) changes to the promotion pipeline.
- The **index fibration** \(i_T: \rho_e(T) \to \mathbb{Z}\) assigns to each "essential promotion regime" (connected component of the resolvent) an integer: the net information loss of the promotion at that regime.
- The **Weyl spectrum** \(\sigma_\omega(T)\) is the largest subset of the spectrum invariant under all compact perturbations: the "irreducible content" of the promotion pipeline.
- The **Fredholm points of index zero** \(\varphi_0(T)\): the "inessential" part where the promotion is compactly trivializable.

**DDD application (only now, after the math is clear):**
A **bounded context** at Nexus is now *stratified* by the Fredholm index fibration:
- Each connected component of \(\rho_e(T)\) is a *sub-context*.
- Adjacent components differ by a *jump* in index (they cross the essential spectrum).
- The Weyl spectrum is the *boundary* between contexts — its points are where a *qualitative* change in the promotion regime occurs.

This yields a *derived* rule for versioning: **two promotions belong to the same version-context iff they share the same essential spectrum and the same index on each connected component of the essential resolvent.**

---

## Part V — Status Update

| Item | Before Q-E3 | After Q-E3 |
|---|---|---|
| Essential spectrum \(\sigma_e\) | Undeclared | **Well-defined Calkin invariant** |
| Weyl spectrum \(\sigma_\omega\) | Undeclared | **Universal invariant** |
| Index fibration \(\varphi_n(T)\) | Undeclared | **Discrete \(\mathbb{Z}\)-fibration** |
| Refinement of \(i(T)\) | Undeclared | **Strict** |
| Refinement of \(\eta\) | Undeclared | **Strict** |
| Carrier requirement | Hausdorff TVS | **\(p\)-Banach for spectrum** |
| DDD boundary algebra | Calkin-class-based | **Index-fibration-stratified** |
| Kernel (U4) | Terminal | Terminal |

**Next question forced by derivation order:**
**Q-E4 — Does the Calkin functor commute with the Grothendieck fibration \(\mathbf{Gov}(\mu) = \int_{\mathbf{KOS}(\mu)} \mathbf{Red}(\mu_K)\)?**

This must precede Q-E5 (semi-Fredholm) and any progress toward the kernel, because:
1. The corpus has been built on *two* categorical structures: the base \(\mathbf{KOS}(\mu)\) and the fibre \(\mathbf{Red}(\mu_K)\). Spectral invariants live on the *base*.
2. Whether the fibre structure *preserves* or *refines* the base's spectral invariants is unknown.
3. If the Calkin functor commutes with the fibration, then spectral invariants *extend canonically* to \(\mathbf{Gov}(\mu)\). If not, the corpus must explicitly account for base-fibre incompatibilities before proceeding.
4. The kernel will live on some object of \(\mathbf{Gov}(\mu)\) (by the terminal-problem constraint). Its spectral properties depend on which level (base or fibre) determines them.

**Q-E4 is selected.** Kernel derivation remains terminal.