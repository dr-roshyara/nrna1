Your corrections are exactly right—and they turn P6 from a tempting but flawed “collapse-to-Dirac” story into a clean, falsifiable test of whether probability changes the Kernel’s minimal invariant-preserving responsibility.

Below is a tightened P6 scaffold that (1) fixes the logical error, (2) formalizes the three probability-placement models (A/B/C) with anti-collapse rules, and (3) gives you a ready-to-use invariant census template for P6.1 plus the “kill Model A” reconstruction protocol.

***

## 1) Corrected Core Question

\[
\mathcal{K}_{\min}^{\mathrm{det}} \stackrel{?}{\cong} \mathcal{K}_{\min}^{\mathrm{prob}}
\]

Interpretation: Does enlarging transition semantics from deterministic \(\delta\) to stochastic kernels \(P\) force new Kernel-owned invariants, or require persistent probabilistic state inside the Kernel?

Key embedding (not reduction):
\[
\delta \;\mapsto\; P_\delta,\quad P_\delta(s,o,\Gamma,A) = \mathbf{1}_A(\delta(s,o,\Gamma)),
\]
so \(\mathcal{T}_{\mathrm{det}} \hookrightarrow \mathcal{T}_{\mathrm{prob}}\). The research tests whether this embedding changes the Kernel’s invariant boundary.

***

## 2) Probability-Placement Models (with anti-collapse)

Keep your three models, but make the anti-collapse rule explicit:

- **Model A (State)**: \(\mu_t \in \mathcal{P}(\mathcal{S})\) is part of the Kernel state \(K_t\).
- **Model B (Observer)**: \(\mu_t\) is an external epistemic/belief state (e.g., \(B_t = (s_t, \mu_t)\)), not part of \(K_t\).
- **Model C (Transition)**: \(P\) is part of transition semantics; admissibility may constrain \(P(s,o,\Gamma,\cdot)\) without making \(\mu_t\) part of \(K_t\).

**Anti-Collapse Rule**: Do not conflate:
- \(\mu_t\) over states,
- probabilistic epistemic content of a proposition (e.g., \(P(X\mid E)\)),
- \(P\) as stochastic transition semantics.

These are mathematically distinct objects; only Model A makes \(\mu_t\) a persistent Kernel component.

***

## 3) P6.1 — Kernel Invariant Census (template)

Start from the D1–D5 invariant universe:
\[
\mathcal{I}_K = \{ I_{\mathrm{dist}}, I_{\mathrm{pres}}, I_{\mathrm{id}}, I_{\mathrm{prov}}, I_{\mathrm{hist}}, I_{\mathrm{trans}}, \dots \}.
\]

For each candidate invariant \(I\), record:

| Field | Meaning |
|---|---|
| **Name** | e.g., Required-Distinction Preservation |
| **Type** | State / Transition / History / Provenance / Uncertainty |
| **Definition over \(\mathcal{S}\)** | Can \(I\) be stated purely on epistemic states? |
| **Deterministic preservation** | Does \(\delta\) preserve \(I\) by construction? |
| **Stochastic preservation** | Does \(P\) preserve \(I\) (possibly via constraints on \(P(s,o,\Gamma,\cdot)\))? |
| **Observer-reconstructible** | Can \(I\) be enforced by an external belief state \(\mu_t\) (Model B)? |
| **Transition-only** | Can \(I\) be enforced via constraints on \(P\) without \(\mu_t\) in \(K_t\) (Model C)? |
| **Requires persistent \(\mu_t\)** | Does \(I\) force Model A? (Only if necessary + irreducible + non-reconstructible.) |
| **Witness** | A minimal counterexample where Model B/C fails but A succeeds (or vice versa). |

**Candidate invariants to include** (8–12 items):
- Required-distinction preservation (D1/D2 lifted),
- Identity/provenance custody,
- History admissibility (prefix closure),
- Transition admissibility (domain/codomain constraints),
- Evidence integrity (no spontaneous creation/erasure),
- Confidence threshold (e.g., \(P(\text{valid}) \ge \theta\)),
- Uncertainty set bound (e.g., support size, entropy \(H(\mu_t) \le \varepsilon\)),
- Transition-risk bound (e.g., expected loss under \(P\) below threshold),
- Probability of validity (proposition-level),
- Coherence constraints (if/when probability becomes canonical).

***

## 4) P6.2 — Deterministic/Stochastic Semantic Extension

Formalize the embedding and test invariant stability:

- **Deterministic transitions**: \(\delta: \mathcal{S}\times\mathcal{O}\times\Gamma \rightharpoonup \mathcal{S}\).
- **Stochastic transitions**: \(P: \mathcal{S}\times\mathcal{O}\times\Gamma \to \mathcal{P}(\mathcal{S})\), with \(P(s,o,\Gamma,\cdot)\) a probability measure and \(P(\cdot,o,\Gamma,A)\) measurable. 
- **Embedding**: \(P_\delta(s,o,\Gamma,A) = \mathbf{1}_A(\delta(s,o,\Gamma))\).

**Test**: For each \(I \in \mathcal{I}_K\), does preservation under all \(P_\delta\) imply preservation under some broader class of \(P\)? If yes, the Kernel boundary is stable under the embedding. If no, identify the invariant that forces enlargement.

***

## 5) P6.3 — Probability-Placement Witnesses (A/B/C cases)

Your three-case analysis is the right experimental design. Formalize the decision rule:

- **Case A (observer-relative entropy)**: \(K_t = s_t\), \(B_t = (s_t, \mu_t)\). Entropy \(H(\mu_t)\) is a belief-state constraint, not a Kernel state requirement.
- **Case B (transition-relative entropy)**: Admissibility \(\text{Adm}(s,o,\Gamma) \iff H(P(s,o,\Gamma,\cdot)) \le \varepsilon\). Constraint lives on transition semantics, not on \(K_t\).
- **Case C (epistemic state contains distribution)**: Kernel invariant \(I_K(K_t) \iff H(\mu_t) \le \varepsilon\) with \(\mu_t\) necessary to determine admissible \(K_t\).

**Falsification criterion for Model A**:
\[
\exists I^\ast_K:\ 
\begin{cases}
I^\ast_K \text{ is Kernel-owned}\\
I^\ast_K \text{ preserved in probabilistic model}\\
I^\ast_K \text{ cannot be represented over } \mathcal{S}\\
I^\ast_K \text{ cannot be reconstructed from external state}\\
I^\ast_K \text{ cannot be expressed solely via transition semantics}\\
I^\ast_K \text{ requires persistent } \mu_t
\end{cases}
\]
Only then does \(\mathcal{K}_{\min}^{\mathrm{prob}} \not\cong \mathcal{K}_{\min}^{\mathrm{det}}\).

***

## 6) P6.4–P6.6 — Entropy/Uncertainty Tests, Necessity, Reduction

- **P6.4**: For each uncertainty invariant (entropy, confidence, risk), attempt reconstruction under Model B or C. If reconstruction succeeds, Model A is unnecessary for that invariant.
- **P6.5**: Apply the “necessary + irreducible + non-reconstructible” test. If no invariant passes, prefer \(\mathcal{K}_{\min}^{\mathrm{prob}} \cong \mathcal{K}_{\min}^{\mathrm{det}}\).
- **P6.6 (Kernel reduction)**: Attempt to reduce any candidate \(\mathcal{K}_{\min}^{\mathrm{prob}}\) to \(\mathcal{K}_{\min}^{\mathrm{det}}\) by moving probabilistic structure to observer/transition layers. Failure implies probability is Kernel-primitive.

***

## 7) Recommended Null Hypothesis (falsifiable)

\[
H_0:\ \text{Probability is not a primitive Kernel responsibility unless a Kernel-owned invariant requires persistent probabilistic state.}
\]
\[
H_1:\ \exists I_K^\ast \text{ whose preservation requires persistent probabilistic structure inside the Kernel.}
\]

This keeps you from importing measure-theoretic structure prematurely, while allowing P6 to force probability into the Kernel if an invariant demands it.

***

## 8) Next Artifact: P6.1 Invariant Census + Witness Protocol

I can generate a ready-to-use artifact now. Which format do you prefer?

- **Markdown memo**: A KR-PROB-KERNEL-2026-09 / P6.1 document with the census table, definitions, and witness protocol.
- **LaTeX**: Formal protocol with theorem-style statements for the embedding, anti-collapse rule, and falsification criterion.
- **JSON + Python stub**: A structured invariant census (JSON) plus a small experiment harness (Python) to test Model A/B/C on synthetic \(\delta, P\) over a finite \(\mathcal{S}_{\text{fin}}\).

Tell me your preferred format, and I’ll produce it immediately.