# Theory Candidates — pilot F0001–F0005

**No candidate here is selected.** Each one is scored against the five-file corpus only.

**Warning about elegance bias:** T2 and T4 are the most mathematically attractive. The scores below rest on evidence counts, not on elegance.

---

## T1 — KnowledgeOS as a governance system

**Model:**
- A rulebook (ES standards, rulings, PGP) plus authorities (ARB, DA).
- Knowledge matters only insofar as it is ruled.

**What it explains:**
- the definitional guard (Canonical = human-made);
- the docket as agenda;
- Product Primacy and AIP-14;
- the enforcement modalities (O33).

**What it fails to explain:**
- the evidence axis: why count n, grade sources, or split method from binding (P1, P4);
- why *uncanonical* knowledge is so carefully tracked.

**Simplicity:** high.

**Testability:** weak, because it predicts nothing about evidence.

---

## T2 — KnowledgeOS as a knowledge-state system (an epistemic ledger)

**Model:**
- The atoms are **claims** (propositions), not concepts; C3 forces this.
- Each claim has a state `(evidence e ∈ E, standing s ∈ S, realization r ∈ R)`, where E, S and R are partially ordered.
- Transitions are typed events (EvidenceRecorded, AuthorityRuled, Demoted).
- There are invariants:
  - **I-a (ceiling):** s may rise only if e ≥ θ(s) (P1).
  - **I-b (authority):** s may rise only through AuthorityRuled, and never through generated work (P2).
  - **I-c (asymmetry, speculative):** demotion needs no evidence (H2a).
- Documents are projections of this state.

**What it explains:**
- all five files at the level of behaviour (O38);
- the buckets, the dashboard, the corrections;
- the counts, and why counts are contested (C1: a measure defined per level);
- reflexivity (P6).

**What it fails to explain:**
- *generation* (T3's domain);
- P4, the decomposition, beyond calling it a classification.

**Formal fit:**
- a product of posets E × S × R. F0003's four partial orders (authority · production · realization · containment) are a candidate for **exactly this product plus containment**. That is an INTERPRETATION to test.
- The dynamics: a labelled transition system or an event-sourced aggregate.

**Testability:** high. It makes predictions H1, H2, H2a and H3.

---

## T3 — KnowledgeOS as a transformation system (a generator or compiler)

**Model:**
- `KnowledgeOS : Method → Binding → PKS-frame` (F0001: "a compiler is not a program; it produces one").
- The kernel is the set of closed METHOD terms (P4).
- Extraction is λ-abstraction over bindings.
- `init` is the generator's entry point.

**What it explains:**
- "creates, not contains";
- the Portability Ladder;
- why grep fails (the bound/constant distinction);
- the kernel/PKS/business allocation (F0005 §7–9).

**What it fails to explain:**
- **its own central operation is at n=0 in every source** (H5);
- it is absent from F0002–F0004;
- it has nothing to say about standing or authority.

**Status of evidence:** this is the *espoused* theory (O38). It is the least evidenced as behaviour.

**Testability:** high, but the tests have not yet been run.

---

## T4 — KnowledgeOS as an epistemic operating system (layered composition)

**Model:**
- T2 as the kernel (state and invariants).
- T1 as the policy layer (who may emit AuthorityRuled).
- The runtime adapter as the mechanism layer (enforcement; O32).
- T3 as an application running on top (generation).
- The "OS" analogy draws on policy/mechanism separation, which F0002 L33 itself cites.

**What it explains:** the most of any candidate. It includes the enforcement inversion as "policy defined, mechanism not bound".

**Weakness:**
- It has the most assumptions and is the easiest to fit after the fact.
- It includes T3's unevidenced layer.
- The word "OS" is in the product name, which is a naming bias.

**Testability:** only as the conjunction of T1, T2 and T3's tests.

---

## Comparison (current corpus only)

| Criterion | T1 Governance | T2 Knowledge state | T3 Generator | T4 Epistemic OS |
|---|---|---|---|---|
| Explanatory coverage | medium | **high** | low–medium | highest |
| Evidence at the behavioural level | medium | **high (5/5 files)** | **low (n=0)** | mixed |
| Contradictions it absorbs | few | C1, C3, C7, C8, C9 | none | most |
| Simplicity | high | medium | medium | low |
| Testability | low | **high** | high (untested) | low |
| Explains apparently unrelated observations | partly | **yes** (counts, grades, projections, corrections) | partly | yes, by construction |

**Current reading, not a verdict:**
- The corpus suggests that **T2 best explains what the documents actually do**, and that **T3 is what they say KnowledgeOS is for**.
- The gap between those two is itself one of the pilot's main findings (O38).
- T4 is the T2+T3 synthesis. It becomes worth considering only if T3's edges ever reach n≥1.

---

## Falsification plan per candidate

**T1 is weakened if:**
- the evidence axis turns out to govern outcomes independently of any ruling, e.g. evidence-triggered automatic demotion.

**T2 is falsified if:**
- standing can be derived from evidence (H1 fails);
- or state changes happen routinely without any recorded event (H2 fails);
- or the atoms cannot be made propositional (C3 unresolvable).

**T3 is falsified or weakened if:**
- `generates → PKS` stays at n=0 after a sanctioned attempt (a Stream 4 / MVP-type run);
- or METHOD/BINDING/EVIDENCE disappears from later kernel work (H4).

**T4:** no independent test; it is falsified if any one of its layers is.
