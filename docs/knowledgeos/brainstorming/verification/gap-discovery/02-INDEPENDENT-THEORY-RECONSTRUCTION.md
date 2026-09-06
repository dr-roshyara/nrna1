# 02 — Independent Theory Reconstruction

**Mandate §7:** *"If I had to explain KnowledgeOS mathematically today using only the source corpus
and executable evidence, what is the smallest coherent theory I could defend?"*

**Rule observed:** every component is labelled. Nothing here is called canonical.
This is **not** a copy of `verification/CANONICAL-KNOWLEDGEOS-THEORY.md`, which this session has not
read (INV-7). It is built from the sources named in `01-THEORY-EVOLUTION-MAP.md` §0 plus the
executed evidence in `exec/`.

---

## 0. The reconstruction rule I applied

A component is admitted only if **at least one** holds:

1. the corpus **argues** for it with a counterexample or derivation I could check (`CORPUS ESTABLISHES`);
2. I can derive it from admitted components (`DERIVED`);
3. a program produced it (`EXECUTED`);
4. the running EKP exhibits it (`IMPLEMENTED` / `EMPIRICALLY OBSERVED`).

A component is **excluded** if its only support is that a document asserts it. That rule removes a
great deal, and what survives is correspondingly small.

---

## 1. The smallest theory I can defend

### Layer 0 — Two spaces, kept apart

$$\Omega_D \quad\text{(domain: what there is to know about)} \qquad \Omega_E \quad\text{(epistemic: possible states of a knower)}$$

`CORPUS ESTABLISHES` — `kernel/20260825-184755` §1 and §13, with the argument that collapsing them
("Ω = all possible knowledge states") is what makes the filtering regime look universal.

**I keep this and the corpus dropped it.** No K definition after 2026-08-26 carries the distinction
(finding EV-A2). I re-admit it because the argument for it was never rebutted, only forgotten.

### Layer 1 — Primitives

| Symbol | Reading | Class |
|---|---|---|
| `Obs` | an observation: something registered, with a source and a time | `CORPUS ESTABLISHES` (Step 253 §253.6 "strong candidate for irreducible") |
| `Prop` | a proposition, structurally `(entity, dimension, value)` | `CORPUS ESTABLISHES` (Step 253 §253.7; Step 262) |
| `Evt` | an event: something that happened at a time | `CORPUS ESTABLISHES` (Step 253 §253.5) |
| `Time` | a total order on occurrence, with a valid-time/transaction-time pair | `CORPUS ESTABLISHES` (Step 025w) |
| `Src` | a source identifier | `EMPIRICALLY OBSERVED` (EKP `owner`, `authority`, `code_refs`) |

**Excluded from the primitives.** `Entity` and `Relation` — Step 253 leaves both as "candidates
requiring formal minimality tests", and Step 254's removal test was specified but its result is not
established. `Policy` and `Action` — Step 253 places them in the constraint and operational layers.
`Knowledge` — Step 253 §253.40 says unresolved, and I agree; see §3.

### Layer 2 — The evidence relation

$$\boxed{\;\mathrm{Evidence}(O, P, C, R)\;}$$

— observation `O` is evidence for proposition `P` in context `C` under rule `R`.

`CORPUS ESTABLISHES` (Closure-04, 2026-08-27), and the argument is a real one: the same observation
("the server returned 3.69") is evidence for `Nexus.version = 3.69`, not for `Nexus is secure`, and
not at all for `Nexus will remain secure for five years`. Four separations follow:
`Evidence ≠ Observation ≠ Source ≠ Support ≠ Truth`.

**Caveat carried forward, not resolved:** `R` includes a relevance judgement, which Step 266 places
in Class C (no decision procedure). The relation is well-formed; it is not yet computable. See
`08-EVIDENCE-GAP.md`.

### Layer 3 — Assertion

$$A = (\mathrm{id},\; P,\; e,\; c,\; t,\; \Pi)$$

`CORPUS ESTABLISHES` for the shape (Step 267 §267.6); `EMPIRICALLY OBSERVED` for every field —
the EKP knowledge card carries `knowledge_id`, a claim, `code_refs`/`test_refs` (evidence),
`bounded_context`, review dates, and `owner`/`authority` (provenance). See `exec/OUT-ekp-bridge.txt`.

`Π` (provenance) sits **inside** the assertion — Step 265's verdict. **But** EXP-2 shows this is not
what it is usually credited with: two states agreeing on every proposition *and* every proposition's
provenance are still distinguished by `withdraw()`. The work is done by the relation set, not by `Π`.
Classification of Step 265's placement: `PROPOSED`, and orthogonal to sufficiency.

### Layer 4 — State

$$K = (\mathcal A, \mathcal R), \qquad \mathcal R \subseteq \mathcal A \times \mathcal A \times \mathrm{RelType}$$

`EXECUTED` **as an instantiation**: the EKP's governed-document graph is exactly this shape —
`|𝒜| = 40`, `|ℛ| = 59`, six relation types in live use (`exec/OUT-ekp-bridge.txt`, EXP-11).
This is the strongest empirical support any object in the theory has.

`UNRESOLVED` **as a minimality or sufficiency claim** — EXP-3 shows the answer depends on an
operation set the corpus never closes.

### Layer 5 — Two status axes, and a third that is missing

$$\Sigma_{\text{lifecycle}} \;\perp\; \Sigma_{\text{source-trust}}$$

`IMPLEMENTED` and `EMPIRICALLY OBSERVED`: the EKP declares the orthogonality in its own schema
comments and exhibits **7 distinct (status, authority) pairs** over 5 statuses and 5 authority
ranks (EXP-12) — a product, not a single axis.

$$\Sigma_{\text{epistemic}} \;=\; ?$$

**`UNRESOLVED`, and this is a finding rather than an omission.** The running system has *no field
recording whether a claim is supported by evidence*. `knowledge-lint` can emit 16 rule
identifiers; not one mentions evidence, support, refutation or conflict (EXP-14). The theory needs
three axes; reality supplies two.

### Layer 6 — Transformation

$$K_{t+1} = T(K_t, o), \qquad o \in \mathcal O$$

`EXECUTED` for a four-operation `𝒪` (`assert`, `relate`, `withdraw`, `restatus`) — the reference
kernel in `exec/kos_kernel.py` runs it, and `replay` is deterministic over it.

`UNRESOLVED` in general: `𝒪` is not enumerated anywhere in the corpus (Step 266: operation registry
missing), and every property that quantifies over `𝒪` — sufficiency, minimality, congruence,
equality — inherits that.

### Layer 7 — What is *outside* K

| Object | Placement | Class |
|---|---|---|
| History `H = (K₀, T₁ … T_t)` | external to `K` | `CORPUS ESTABLISHES` (Step 247) — and EXP-3 shows a history-sensitive predicate cannot be answered from `K` alone |
| Policy / AssessmentPolicy | external pluggable regime | `PROPOSED` (Step 270's own recommendation) |
| Probability, statistics, filtering | external regime | `CORPUS ESTABLISHES` (2026-08-25, re-derived 2026-08-30) |
| Human judgement (relevance, adequacy, authority, truth) | the **judgement boundary** | `CORPUS ESTABLISHES` (Step 266 §266.31) |

---

## 2. The theory in one diagram

```
        Ω_D  (domain)                        Ω_E  (epistemic states)
          │                                          ▲
          │  Obs (Src, t)                            │
          ▼                                          │
      OBSERVATION ───Evidence(O,P,C,R)───▶  ASSERTION A=(id,P,e,c,t,Π)
                             │                       │
                    [relevance: NOT computable]      │
                                                     ▼
                                          K = (𝒜, ℛ)      ◀── T(K,o), o∈𝒪
                                            │  │              (𝒪 not enumerated)
                          Σ_lifecycle ──────┘  └────── Σ_source-trust
                            (IMPLEMENTED)               (IMPLEMENTED)
                                     Σ_epistemic  =  ABSENT

        OUTSIDE K:  History H · Policy · Probability regimes · Judgement boundary
```

---

## 3. What I could NOT reconstruct, and why

### 3.1 "Knowledge" itself

Q7 ("What is Knowledge Itself?") offers a layered definition ending in a JTB-shaped atom
`K = (A, J, T_A)`. I cannot admit it:

- it is a **different kind of object** from every other `K` in the corpus (EV-B2): one justified
  belief, not a state;
- `T_A` (truth assessment) requires `Truth(A)`, which Step 266 classifies as meaningful but not
  computable;
- the corpus's own Step 253 §253.40 leaves "Knowledge" unresolved.

**The defensible position: KnowledgeOS does not need a definition of *knowledge*.** It needs
assertions, an evidence relation, statuses and a transformation. Every operation in Layer 6 is
definable without ever saying what knowledge *is*. This is a `DERIVED` conclusion of the
reconstruction, and it is a substantive one — it says the corpus's title question is not on its
critical path.

### 3.2 A minimal K

Step 260's `K* = ℋ/≡` requires `≡` to be a congruence for `𝒯`. `𝒯` is open. Therefore `K*` is not
constructible today, and `EXP-3` shows the question is not even well-posed until `𝒪` is fixed.
`UNRESOLVED`.

### 3.3 Any quantitative epistemic measure

Every numeric proposal I tested fails a measurement-theory admissibility check
(`exec/OUT-measurement.txt`): means over the ordinal status ladder flip under admissible
re-encodings (EXP-4); `AggregateSupport = Σs/(1+log n)` is unbounded and non-idempotent (EXP-5);
`IndependenceFactor = 1/(1+depth)` assigns a verbatim copy the same weight as an independent
observation (EXP-6); per-assertion "confidence in [0,1]" is not a probability because no common
`(Ω,𝓕,P)` is declared (EXP-7). **No quantitative layer survives.** `REFUTED` for each named formula
as a *measurement*; each remains available as an admitted engineering heuristic.

---

## 4. Honest scorecard of the reconstruction

| Layer | Best class achieved |
|---|---|
| Ω_D / Ω_E separation | `CORPUS ESTABLISHES` (abandoned by the corpus; re-admitted here) |
| Primitives Obs / Prop / Evt / Time / Src | `CORPUS ESTABLISHES` (candidate status per Step 253) |
| `Evidence(O,P,C,R)` | `CORPUS ESTABLISHES` — with a non-computable predicate inside |
| Assertion `(id,P,e,c,t,Π)` | `CORPUS ESTABLISHES` + `EMPIRICALLY OBSERVED` |
| `K = (𝒜,ℛ)` as a **shape** | `EXECUTED` (40 assertions, 59 typed relations, live) |
| `K = (𝒜,ℛ)` as **minimal/sufficient** | `UNRESOLVED` |
| `Σ_lifecycle ⊥ Σ_source-trust` | `IMPLEMENTED` + `EMPIRICALLY OBSERVED` |
| `Σ_epistemic` | **`UNRESOLVED` — absent from the implementation entirely** |
| `T` over a fixed small `𝒪` | `EXECUTED` |
| `T` in general, `𝒪`, `K*`, `≡` | `UNRESOLVED` |
| Any quantitative measure | `REFUTED` as measurement |
| "Knowledge" | not required; excluded |

**This is the whole of it.** A two-space separation, five primitive kinds, one relation, one
record type, one graph, two status axes, and a transformation over an operation set that has not
been written down.

---

**Next:** `03-FOUNDATIONAL-ONTOLOGY.md` — the primitive dependency graph, and whether it is acyclic.
