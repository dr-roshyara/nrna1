# Theory Slice `01` — Authority

*A scientific theory document. Phase 2, job `2A` recovery → `2B` constructive completion → `2C` attack.*

| | |
|---|---|
| **Authorization** | ⭐ **PHASE-2-AUTHORIZED by L0**, 2026-09-23, entry slice `T-0014` |
| **Representation** | ⭐ **1 of 3 — the scientific theory** *(§5A.3a)*. ⛔ The knowledge and machine representations are generated from this and never substitute for it |
| ⛔ **Not touched** | `CANDIDATE-KNOWLEDGEOS-THEORY.md` **v0.9 remains FROZEN** · no Phase-1 record · no registry row |
| **Ceiling** | ⛔ **L4.** `L5` is reachable only by a human act *(§1)* |

---

## 1 · Primitive concepts

⛔ **Taken as undefined here**, before anything is defined from them:

| Primitive | |
|---|---|
| **artifact** | a governed thing that can carry an authority label |
| **origin** | the historical fact of how an artifact came to exist |
| **trust** | the degree to which a reader may rely on an artifact |
| **governance act** | a decision by a named role that changes a recorded state |

⚠️ **`origin` and `trust` are primitives, not defined terms.** The theory is about how a schema *encodes* them, not about what they are.

## 2 · Definitions

**`[C]` D1 — the value set.** `authorities.yaml` admits exactly five values:
`generated · derived · authoritative · provisional · historical`

**`[C]` D2 — the two questions.** The field's own header asks two: *"how much should I trust this **and** where did it come from?"*

**`[C]` D3 — the partition.** `F0018` assigns each value to exactly one question:

$$P=\{\textsf{generated},\ \textsf{derived}\}\qquad S=\{\textsf{authoritative},\ \textsf{provisional},\ \textsf{historical}\}$$

**`[C]` D4 — mutability.** `P` is immutable *("NEVER — origin is an immutable historical fact")*; `S` changes *"by a GOVERNANCE ACT"*.

**`[E]` D5 — the authority map.** For the artifact set $A$, write $\alpha : A \to V$ for the function assigning each artifact its single recorded authority value, $V$ the value set of **D1**.

## 3 · Assumptions

| | Assumption | Origin |
|---|---|---|
| **A1** | the field holds **exactly one** value per artifact | `[C]` — `authorities.yaml` is an enum |
| **A2** | `P` and `S` as given in **D3** are complete and correct | `[C]` `F0018` |
| **A3** | every artifact that has an origin also has a trust level, whether recorded or not | ⛔ **`[E]` — a modelling assumption, not corpus-stated** |

⚠️ **A3 is load-bearing and is the one to attack first.** If an artifact can meaningfully have origin but *no* trust level, Proposition 3 weakens.

## 4 · The formal structure

**`[E]` Notation.** $\sqcup$ disjoint union · $\times$ Cartesian product · $|X|$ cardinality · $\iota_P,\iota_S$ the canonical injections into a sum · $\pi_P,\pi_S$ the projections from a product.

**`[E]` The two candidate codomains:**

$$\textbf{SUM (as built):}\quad \alpha : A \longrightarrow P \sqcup S$$
$$\textbf{PRODUCT (as the theory reads):}\quad \alpha^{\ast} : A \longrightarrow P \times S$$

⭐ **`T-0014` states the decomposition in prose — *"authority = immutable PROVENANCE × act-changed STANDING."* Writing it as $P \times S$ is my formalization, and the whole slice turns on whether the built artifact matches it.**

## 5 · Propositions

### **Proposition 1** — the codomain is a sum, not a product `[E]` `L3`

$$|P \sqcup S| = |P| + |S| = 2 + 3 = 5 \qquad |P \times S| = |P| \cdot |S| = 2 \cdot 3 = 6$$

**The observed value set has 5 elements.** Therefore $V \cong P \sqcup S$ and $V \not\cong P \times S$.

**Proof.** By **D3** the sets are disjoint and their union is $V$, so $V$ is a coproduct of $P$ and $S$ with $|V| = 5$. A product would have $6$ elements. $5 \neq 6$. ∎
**`[T]-05`** executed: partition verified `True`, $|P|{+}|S| = 5 = |V|$, $|P|{\times}|S| = 6$.

### **Proposition 2** — the corpus's own example is unrepresentable `[E]` `L3`

**`[C]`** `F0018` states: *"a signed financial statement is **`derived`** in provenance and **`authoritative`** in standing, **simultaneously and without contradiction**."*

That artifact requires $(\textsf{derived},\textsf{authoritative}) \in P \times S$.

**Proof.** By **A1** and Proposition 1, $\alpha(a) \in P \sqcup S$ — a single label. The pair is an element of $P \times S$, and the canonical injections $\iota_P,\iota_S$ are the *only* maps into the sum; neither has a pair in its image. Hence no $\alpha(a)$ expresses it. ∎
**`[T]-05`**: pair expressible in $P \sqcup S$ → **`False`**.

> ### ⭐⭐ **The corpus states a paradigm example that its own schema cannot represent.**
>
> ⛔ **This is not a reading error and not a defect of the theory — it is a defect of the ENCODING, and `F0018` located it correctly while writing the example that proves it.**

### **Proposition 3** — the refinement is not information-preserving forward `[E]` `L3`

There is **no** total function $r : P \sqcup S \to P \times S$ recovering the pair.

**Proof.** Take $a$ with $\alpha(a)=\iota_P(\textsf{derived})$. Any $r$ must supply a second coordinate $s \in S$. Nothing in the datum determines $s$: **D4** constrains only how $s$ *changes*, never its initial value. Any choice is therefore a **stipulation**, not a derivation; and three distinct total $r$ exist, one per choice of default. Symmetrically for $\iota_S$. ∎

> ### ⭐ **Consequence — this is what `PM-1` actually is.**
> `F0018` asks *"should authority's two questions be separated?"* and routes it to the ARB.
> ⭐ **Proposition 3 says why it must be routed: the separation is not derivable. The missing coordinate has to be DECIDED.** The modelling is settled; a **default-standing decision** is the entire residue.

### **Proposition 4** — immutability is vacuous for most artifacts under the sum `[E]` `L3`

**`[C]` D4** asserts $\pi_P \circ \alpha^{\ast}$ is constant along every transition.

**Proof.** Under the sum, an artifact with $\alpha(a)\in S$ has **no** $P$-coordinate. The immutability assertion quantifies over a coordinate that does not exist, so it holds **vacuously** and constrains nothing. It acquires content **only** under $\alpha^{\ast}$. ∎

⭐ **So the immutability invariant — the strongest thing `T-0014` claims — is unenforceable in the current encoding.** *That is an argument **for** the product, derived rather than asserted.*

## 6 · Counterexamples

| # | Against | |
|---|---|---|
| **1** | ⭐ **the schema** | the signed financial statement *(Prop 2)* — **the corpus's own** |
| **2** | ⚠️ **assumption A3** | an artifact whose origin is recorded but for which "how much to trust it" is **not yet meaningful** — e.g. a `generated` draft never submitted. ⛔ **If such artifacts exist, the product form forces a fictitious coordinate.** *Not searched for; `RO-0060`* |
| **3** | ⛔ **against my own Prop 1** — sought and **not found** | if any artifact carried **two** authority values, $V$ would not be the codomain. `A1` says the field is single-valued; ⚠️ **verified against the schema's shape, not against instance data** |

## 7 · Statistical model

⛔ **NONE, and none is appropriate.** This slice contains no estimand, no population and no sampling. ⭐ **The cardinality argument is exact arithmetic on a declared enum, not an inference.** Introducing statistics here would be the error `§11` warns against.

## 8 · DDD interpretation

**`[S]`** The two factors are **different kinds of domain object**:

| | |
|---|---|
| **PROVENANCE** | an **immutable attribute fixed at creation** — a Value Object |
| **STANDING** | **aggregate state changed by a governance act** — mutated only through a domain event |

> ### ⭐ **One field holding both puts a Value Object and Aggregate State in the same slot.**
> That is why no state machine could be found for `authority` *(`LG-1`, withdrawn as ill-posed)*: **half of it is not state.** ⭐ `F0018` reached this conclusion; the DDD reading says *which modelling error* produced it.

⭐ **And it explains `I-4`'s persistence:** reading `derived` as implying low standing is a **type error** — comparing a Value Object to an Aggregate State.

## 9 · Computational interpretation

**`[E]`** Under the product, two mechanically checkable rules become available that are **not** expressible today:

| Rule | Checkable |
|---|---|
| every artifact carries **both** coordinates | ✅ a lint rule — presence of two fields |
| **no transition changes** $\pi_P$ | ✅ a diff over artifact history |

⛔ **Neither is implementable under the sum**: the first is ill-typed, the second vacuous *(Prop 4)*.
⚠️ **Cost:** the migration needs a coordinate for every existing artifact, and Prop 3 says it cannot be computed. ⭐ **It is a data-entry decision proportional to the artifact count, not a refactor.**

## 10 · Competing formulations — preserved, not resolved

| # | Formulation | Status |
|---|---|---|
| **F-a** | **sum** — one field, five values *(as built)* | ⭐ **what exists**. ⛔ cannot express Prop 2's example |
| **F-b** | **product** — two fields *(as `T-0014` reads)* | ⭐ expressive, ⛔ requires an undecidable coordinate *(Prop 3)* |
| **F-c** | **partial product** $P \times (S \cup \{\bot\})$ — standing may be *unassigned* | ⚠️ ⭐ **expresses Prop 2 AND avoids Prop 3's stipulation**, at the cost of a third state per artifact. ⛔ **Not in the corpus — `[E]`, offered, not preferred** |

⛔ **No resolution is proposed.** `F-c` is recorded because the evidence does not justify choosing between them, and §6 requires competitors be preserved.

## 11 · Falsification conditions

| The slice is **wrong** if | |
|---|---|
| **1** | `authorities.yaml` admits a **sixth** value, or the `P`/`S` partition is not as `F0018` gives it → **Prop 1 falsified** |
| **2** | the field is shown to be **multi-valued** in practice → **A1 falsified, Props 1–4 collapse** |
| **3** | a rule elsewhere in canon **determines** the default standing for a `P`-labelled artifact → **Prop 3 falsified**, and `PM-1` becomes derivable after all |
| **4** | an artifact is found whose **provenance changes** → **D4 falsified** |
| ⭐ **5** | a majority of artifacts have origin but **no meaningful trust level** → **A3 falsified**, and `F-c` dominates `F-b` |

## 12 · Provenance

| Layer | |
|---|---|
| **`L0` historical** | `authorities.yaml`'s five values and its two-question header · `F0018` §4's table · the signed-financial-statement example |
| **`L1` reconstruction** | `T-0014`, four sources — `F0018` · `F0016` · `F0017` · `F0014` |
| **`L2` hypothesis** | `D5` · the sum/product framing · `F-c` |
| **`L3` derivation** | Propositions 1–4 |
| **`[T]`** | **`[T]-05`** — partition and cardinality, executed, outcome recorded in §5 |

⛔ **No Phase-1 record was altered to reach any of this.**

## 13 · Epistemic status

| Item | Origin | Level |
|---|---|---|
| D1–D4, the example | `[C]` | **L0** |
| `T-0014` | `[C]` | **L1** |
| D5, the sum/product framing, `F-c` | `[E]` | **L2** |
| Propositions 1–4 | `[E]` | **L3** |
| `[T]-05` | `[T]` | supports Props 1–2 |

> ⛔ **Nothing here is `L4`.** `L3 → L4` requires *"a named test from §13 executed"*. ⭐ **`[T]-05` is an arithmetic verification, and I have not established that it is a §13-named test.** Until that is checked, **Props 1–2 stay `L3` with their test recorded** rather than being promoted on my own say-so.
> ⛔ **`L5` is unreachable from here in principle.**

## 14 · Known limitations

| | |
|---|---|
| ⛔ | **The whole slice rests on `F0018`'s partition.** If `A2` is wrong, everything above falls |
| ⛔ | **`A1` verified against the schema's shape, not instance data.** No artifact was inspected |
| ⛔ | **`A3` is mine and unattacked** — counterexample 2 is unsearched |
| ⚠️ | ⭐ **`RA-15` status D is unreachable here**, as everywhere: four sources, one programme *(`RO-0051`)*. **This slice is `SOURCE-SUPPORTED` and `RECONSTRUCTION-VALID`, not independently corroborated** |
| ⚠️ | No instance data, so the migration cost in §9 is unquantified |

---

## ⭐ The slice in one paragraph

**`T-0014` reads authority as a product of an immutable provenance and an act-changed standing. The artifact built is a sum — one field, five values, `5 = 2 + 3` and not `6 = 2 × 3`.** The gap is not cosmetic: the corpus's own paradigm example, a signed statement that is `derived` and `authoritative` at once, **cannot be written down in the schema that is supposed to hold it**, and the immutability invariant `T-0014` most relies on is **vacuous** for every artifact carrying a standing label. Refining sum to product is **not derivable** — the missing coordinate must be stipulated — **which is precisely why `PM-1` is an ARB decision and not a modelling exercise.** A third formulation, partial product with unassigned standing, expresses the example without the stipulation and is recorded as a live competitor rather than a preference.

---

*Theory Slice `01` · entry slice `T-0014` · 4 propositions at `L3` · 1 `[T]` test · 3 competing formulations preserved · 5 falsification conditions · ⛔ 0 Phase-1 records altered · **Candidate Theory v0.9 FROZEN and untouched** · ⛔ nothing above `L3`.*
