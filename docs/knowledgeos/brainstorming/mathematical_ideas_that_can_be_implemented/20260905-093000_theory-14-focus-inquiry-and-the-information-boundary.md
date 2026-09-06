# Theory 14 — **Focus, Inquiry, and the Information Boundary**

**Document 14 of 15** · 2026-09-05
**Sources:** `KR-ZOOM-01-2026-09` (+ its 2026-09-04 amendment) · `KR-ZOOM-02-2026-09`
**Status:** research synthesis. **Theory v1.2 FROZEN · kernel NOT SELECTED · nothing adjudicated.**

> This document exists because a **conceptual correction** was required before further
> experiments could be meaningful. It is the correction, not a report of one.

---

## 1. The distinction that was being conflated

$$\boxed{\text{Restriction / Descent}} \qquad\neq\qquad \boxed{\text{Inquiry / Focus}}$$

| | changes | leaves |
|---|---|---|
| **Restriction** | **the information boundary** | a smaller state; the rest is gone |
| **Inquiry** | **the investigative focus** | the information boundary **intact** |

> ### `[EXP]` **Restriction changes the information boundary. Inquiry changes the investigative focus while retaining the information boundary.**

`[NEG]` **These are not two readings of one operator.** `KR-ZOOM-01` implemented restriction and
reported it as zoom; the mistake produced three "defects" that were one defect
(`KR-ZOOM-01-2026-09/RESULTS.md` §9).

---

## 2. The corrected research model

The cycle is **not** a representation-reduction operation:

$$\boxed{\;\mathrm{Observe} \to \mathrm{Focus} \to \mathrm{Explore} \to \mathrm{Assess} \to \mathrm{Determine} \to \mathrm{Update}\;}$$

$$K_t \xrightarrow{\;\mathrm{Observe}\;} O_i \xrightarrow{\;\mathrm{Focus}\;} Q_i \xrightarrow{\;\mathrm{Explore}\;} E_i \xrightarrow{\;\mathrm{Determine}\;} D_i \xrightarrow{\;\mathrm{Update}\;} K_{t+1}$$

### Three operations, kept separate

| | | |
|---|---|---|
| **Observe** | $\mathrm{Observe}(K_t) \to O_i$ | *what is the state?* — e.g. Nexus egress = 70 GB/day |
| **Focus** | $\mathrm{Focus}(K_t, O_i) \to Q_i$ | *what do I want to investigate?* — why 70 GB/day? |
| **Investigate** | $\mathrm{Investigate}(Q_i, K_t) \to E_i, \mathcal H_i$ | *what evidence explains it?* — **may discover new dimensions** |

### Two invariants

$$\boxed{\mathrm{Focus}(K_t, O_i) \text{ does not destroy } K_t}$$
$$\boxed{\mathrm{Investigate}(Q_i) \text{ may discover dimensions not present in the initial focus}}$$

---

## 3. The research candidate definition of Zoom

`[DEF]` **research candidate, not adopted:**

$$Zoom(K_t, Q) = \text{a change of investigative focus over } K_t \text{ that does not itself delete the surrounding knowledge state.}$$

$$Zoom(K_t, O_i) = \left(K_t,\ \mathrm{Focus} = O_i\right)$$

with an expandable working set

$$\mathrm{WorkingSet}_t(Q) \longrightarrow \mathrm{WorkingSet}_{t+1}(Q)$$

when evidence indicates relevance. **Contrast** $D \to D \setminus S$, which is
**restriction/elimination** and belongs to the `Zero` lane (theory doc 03), not here.

$$\boxed{Zoom \neq Reduction \qquad Zoom \neq Deletion \qquad Zoom \neq Elimination}$$

---

## 4. Three candidate principles — all `[EXP]`, none promoted

### `P1` — **Focus is not deletion**

$$\mathrm{Focus}(K, Q) \neq \mathrm{Reduction}(K, Q)$$

`[EXP]` Supported by the paired design of `KR-ZOOM-02`: the two operators, given identical cases
and identical exploration RNG, produce systematically different outcomes.

### `P2` — **Inquiry can cross the original anchor boundary**

$$\mathrm{Explore}(Q, K) \text{ may access dimensions outside the initial focus.}$$

`[EXP]` Investigation absorbed a dimension **absent from the initial ontology** in
**23.3 % / 25.7 %** of cases. *(The restriction arm's 0 % on those cases is **definitional** —
see §6.)*

### `P3` — **Restrictive boundaries impose an epistemic ceiling**

$$\text{If required evidence lies outside the working boundary, additional effort inside that boundary cannot recover it.}$$

`[EXP]` **The strongest result in the programme's zoom lane.**

| probe budget | restriction (train / test) | inquiry |
|---|---|---|
| 3 | **0.079 / 0.071** | 0.511 / 0.497 |
| 8 | **0.079 / 0.071** | 0.999 / 0.998 |
| 40 | **0.079 / 0.071** | 1.000 / 1.000 |

Flat from budget 3 to 40 — **13× the effort bought exactly nothing** — and
`paired_restriction_only = 0.000` at **every** budget: in 8 000 cases, restricting the view never
once helped.

> **The problem is not insufficient effort. The problem is that the information needed to answer
> the inquiry has been excluded from the working boundary.**

`[REC]` **None of `P1`–`P3` is promoted to a Theory v1.2 axiom.** One synthetic domain, one
generator, one anomaly type.

---

## 5. A fourth candidate — order robustness

`[EXP]` Internal-state agreement under exploration-order swap:

| operator | agreement |
|---|---|
| restriction / descent | **0.000** (0 of 518, `KR-ZOOM-01`) |
| inquiry / focus | **≈ 0.92** (`KR-ZOOM-02`, binding budgets) |

**In business terms:** two engineers given the same Nexus problem who investigate different
branches end with **different states** under restriction, because each discarded the rest of the
context. Under inquiry they **converge on approximately the same working set**, because neither
destroyed it.

`[CONJ]` **Inquiry focus should be substantially order-robust under a fixed inquiry and evidence
environment.** **Refutable by** a regime with a fixed inquiry and fixed evidence in which
order-swapped investigations diverge in working set at rates comparable to descent.

⚠️ The residual **"different state, same observable"** cell persists at **3–8 %** — the third
independent occurrence of that phenomenon in this corpus. **Order still matters; it matters far
less.**

---

## 6. ⚠️ What is definitional and must never be reported as evidence

| statement | true value | why it is not evidence |
|---|---|---|
| context is preserved under inquiry-zoom | 1.000 | **the operator was designed not to delete.** This is the design, not a result |
| restriction never finds a cross-dimension cause | 0.000 | a restricted operator cannot cross the boundary that defines it |
| restriction never finds a novel-dimension cause | 0.000 | same |
| "Zero before evidence" rate | 0.87525 | **identical to the generator's cross-dimension rate** — it is that rate, relabelled |

> `context\_preserved = 1.000` must **never** be written as *"the experiment demonstrated perfect
> context preservation."* It means *"the operator was designed to preserve context."*
> **This distinction is now a standing methodological rule** — see the epistemic-status vocabulary.

---

## 7. The correction to the earlier 7 % claim

**Previously written:** *"Zoom causes determination loss ~7 %."* **Too broad.**

$$\text{Restriction/descent caused determination LOSS in } \approx 7\% \text{ of tested cases (gain: } 0\%).$$
$$\text{Inquiry-focus caused determination GAIN from } 7.9\% \to 100\% \text{ over the tested budget progression (loss: } 0\%).$$

> ### `[EXP]` The two operators move determination in **opposite directions**. The original 7 % was never evidence that epistemic zoom is harmful — it was **the cost of making the focus the information boundary.**

---

## 7a. `FR-004` **CANDIDATE** — Determination is relational, not intrinsic

**Raised by the research owner 2026-09-05 as a candidate methodological invariant. NOT promoted.**

$$\boxed{\;\mathrm{Determine} \;=\; \mathrm{Determine}\!\left(K,\;Q,\;C,\;E_C,\;S,\;R\right)\;}$$

| symbol | | |
|---|---|---|
| $K$ | the knowledge state | |
| $Q$ | the inquiry | |
| $C$ | the contract / constraints | |
| $E_C$ | the **evidence context** — what was actually acquired and assessed | |
| $S$ | the **epistemic standard** — here $\tau_s$, $\tau_m$, and the uniqueness requirement | |
| $R$ | the **reasoning / assessment regime** | |

> ### `[EXP]` **Determination is NOT an intrinsic property of $K$.** Change $S$ — the margin, the uniqueness rule — and the set of determined cases changes, with no change to the knowledge state at all.

**The measurement that forced it.** `KR-ZOOM-OUT-01`'s determined population is a property of
**(domain × contract)**, not of the domain. It cannot be quoted as *"what the system knows"*.

### Convergent, and that is why it is only a candidate

The same relational shape has now appeared independently in six places:

| | |
|---|---|
| inquiry-relative **adequacy** | theory doc 04 — adequacy is $(T, Q, \text{population})$-relative |
| **`Zero` is contract- and reference-relative** | theory doc 03 — 495/4 049 and 541/4 260 verdict flips |
| **epistemic standards** | $\tau_s, \tau_m$ decide the determined set |
| **reasoning regimes** | composition rules differ on the same evidence (doc 08) |
| **determination is set-valued** | uniqueness can fail with no state change |
| **Zoom-out**, now | the `M0`/`M1` split moves entirely with the contract |

`[REC]` **Do NOT promote to Theory v1.2.** The programme's standing rule holds and is the right one:
**experimental convergence does not automatically become theory.** Six convergent instances make
`FR-004` a strong *design principle*; a theorem needs a proof, and none exists.

---

## 7b. `[EXP]` Reachability ≠ Determination ≠ Knowledge

$$\boxed{\;\text{Reachability} \;\neq\; \text{Determination} \;\neq\; \text{Knowledge}\;}$$

**The measurement** (`KR-ZOOM-OUT-02-SELECTION-AUDIT-2026-09.md`):

$$P_{\text{reach}} = P_{\text{all}} \quad\text{(every root reachable within budget)} \qquad\text{yet}\qquad P(\mathrm{Determine} \mid \mathrm{Reach}) = 0.367 \,/\, 0.368$$

> **63 % of cases were fully reachable and still not determined.** Determination failed at the
> **contract** level — the uniqueness and margin conditions — **not** at the search level.

This is a third instance of a separation the programme keeps re-finding, alongside
$\text{Information} \neq \text{Evidence}$ and $\text{Evidence} \neq \text{Determination}$, and it
strengthens the three-question separation of theory doc 02 from a new direction.

`[REC]` **`[EXP]`, not theory.** One domain, one contract, one budget.

---

## 8. What remains `[OPEN]`

**`H3` is well-posed for the first time and still untested.** With $K_t$ preserved and $Q$ fixed,
*"does $\mathrm{Zero}_Q(d)$ change when investigation brings evidence from another dimension?"* is
a legitimate question — the Nexus story exactly: the CI/CD configuration is irrelevant to egress
**until evidence makes it the cause.**

`[NEG]` **`KR-ZOOM-02` did not test it.** Its `zero_for_inquiry()` is a reachability **lookup**,
not an intervention, and returns the generator's cross-dimension rate by construction.

$$\boxed{0.87525 \;\neq\; \text{evidence that inquiry changes Zero status}}$$

**`KR-ZOOM-03` is designed to test exactly this** (`verification/zero-algebra/KR-ZOOM-03-DESIGN-2026-09.md`).

---

## 8a. **STANDING POSITION** — adjudicated 2026-09-05

> ### The method has been validated enough to **use** Zoom-in/Zoom-out as engineering/investigation operations. It has **not** proved a universal epistemological definition of either.

### 8a.1 The status table

| concept | status |
|---|---|
| **Zoom-in as inquiry/focus operation** | **Supported candidate · operationally usable** |
| Restriction/descent as Zoom-in | **Empirically distinguishable — must NOT be called epistemic Zoom-in** |
| Focus preserves surrounding context | **Strong candidate, experimentally supported in the tested regime** |
| Investigation can cross the initial dimension boundary | **Supported candidate, tested** (23–26 %) |
| **Zoom-out definition** | **`[OPEN]` — UNDEFINED** |
| Zoom-out = abstraction | **Not established** |
| Zoom-out = restoration | **Not established** |
| Zoom-out = integration / revision | **Plausible candidate, not established** |
| `O-F*` | **Accepted research-governance control** |
| Theory v1.2 · kernel · `FR-004` | **Frozen · untouched · candidate, prediction failed** |

### 8a.2 What the experiments did establish

$$\text{restriction / descent} \longrightarrow \text{plateau} \qquad\text{vs}\qquad \text{inquiry-zoom} \longrightarrow \text{strongly increasing determination}$$

At the tested budgets, **7.9 % vs ~100 %**. `[EXP]` **These are different operations, not two
implementations of one Zoom-in.**

### 8a.3 What the Zoom-out experiments did **NOT** establish

**They did not establish a definition of Zoom-out.** `KR-ZOOM-OUT-01` failed its calibration gate;
`-02` diagnosed the insensitive grid and stopped; `-03`'s primary estimand was **structurally
forced to zero**. Their useful output was **negative and methodological**: the `M1` artifact,
the `FR-004` prediction failure, the structurally invariant `S/S'` component, and `O-F*` itself.

> **We must not say the Zoom-out experiments proved what Zoom-out is.**

### 8a.4 Operational disposition — what may be used, and how labelled

**Zoom-in — USABLE NOW**, as a **candidate operational pattern**, never as a kernel primitive:

$$\boxed{ZoomIn(K_t, Q) = \text{focus an inquiry on part of } K_t \text{ while PRESERVING the broader state as context}}$$

$$K_t^{\text{focused}} \neq K_t^{\text{before}} \qquad\text{and}\qquad K_t^{\text{focused}} \text{ does NOT replace or destroy } K_t^{\text{before}}$$

Its purpose is **investigation, not deletion**, and the investigation **may discover information
outside the initial focus.**

**Zoom-out — USABLE ONLY AS AN EXPLICIT WORKFLOW ACTION** ("return to / update the broader
context"). **NOT encoded as a canonical operation.** Treat it as a family and let the use case pick:

$$\boxed{ZoomOut \in \{\,\text{ContextRestore},\ \text{Abstraction},\ \text{Integration},\ \text{Revision}\,\}}$$

`[CONJ]` In the Nexus instance — investigate 70 GB/day → determine GitLab Runner → the broader state
now carries the causal information — **the natural move looks more like INTEGRATION/REVISION of the
broader Knowledge State than "undoing a zoom."** *Plausible candidate, not established.*

### 8a.5 The separation the experiments earned

$$\boxed{\;ZoomIn \;\neq\; ZoomOut \;\neq\; RepresentationReduction\;}$$

**This is the durable contribution of the zoom lane so far** — not a definition of either operation,
but the demonstration that three things previously run together are distinct.

---

## 9. Register

| statement | status |
|---|---|
| Restriction $\neq$ Inquiry; they change different things | `[EXP]` |
| $Zoom(K_t,Q)$ = focus change without deletion | `[DEF]` — research candidate, **not adopted** |
| Focus does not destroy $K_t$ | `[DEF]` invariant of the candidate |
| Investigation may discover dimensions outside the initial focus | `[EXP]` — 23–26 % |
| `P1` Focus is not deletion | `[EXP]` |
| `P2` Inquiry can cross the anchor boundary | `[EXP]` |
| `P3` Restrictive boundaries impose an epistemic ceiling | `[EXP]` — the budget plateau |
| Inquiry focus is substantially order-robust | `[CONJ]` — refutation condition stated |
| "different state, same observable" | `[EXP]` — third independent occurrence |
| Zoom is harmful to determination | **`[NEG]`** — withdrawn; it was restriction that was harmful |
| $\mathrm{Zero}_Q(d)$ changes under inquiry evidence | **`[OPEN]`** — well-posed, untested |
| any of `P1`–`P3` as a Theory v1.2 axiom | **`[NEG]`** — explicitly not promoted |
| Zoom-in as inquiry/focus is **operationally usable** | **`[EXP]` supported candidate** — labelled a candidate operational pattern, **never a kernel primitive** |
| Restriction/descent may be called epistemic Zoom-in | **`[NEG]`** — empirically distinguishable, 7.9 % vs ~100 % |
| Zoom-out has a definition | **`[OPEN]` — UNDEFINED.** The Zoom-out experiments did **not** establish one |
| Zoom-out = abstraction · = restoration | **Not established** |
| Zoom-out = integration / revision | **`[CONJ]` plausible candidate, not established** |
| $ZoomIn \neq ZoomOut \neq RepresentationReduction$ | **`[EXP]`** — the durable contribution of the zoom lane |
| `FR-004` — determination is relational, $\mathrm{Determine}(K,Q,C,E_C,S,R)$ | **`[EXP]` CANDIDATE** — six convergent instances; **explicitly not promoted** |
| Reachability $\neq$ Determination $\neq$ Knowledge | **`[EXP]`** — 63 % reachable-but-undetermined |
| `FR-004` or 7b as a Theory v1.2 axiom | **`[NEG]`** — convergence is not proof |
