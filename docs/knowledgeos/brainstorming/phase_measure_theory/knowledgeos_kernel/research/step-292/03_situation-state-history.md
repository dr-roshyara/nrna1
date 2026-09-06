# 03 — Situation ≠ State · **the mandatory falsification**

> ## `P1: Situation = KnowledgeOS state` — **REFUTED.**
> ## And refuted by KnowledgeOS's own prior evidence, not by Reiter's authority.

---

## 1. What the extraction itself asserts — and why it must not be accepted

The supplied extraction's §1.2 table maps:

```
Situation  →  K_t state representation
do(a,s)    →  δ(K_t, e_t) → K_{t+1}
```

**The mandate forbids accepting this**, and it is right to: **the extraction's own next line
contradicts its own table.**

> *"Key Insight: Situations are **histories**, not states. Two situations may have identical fluent
> values but be different histories."*

**A source that maps `Situation → K_t` two lines above stating that situations are not states has
supplied a correspondence, not a derivation.** This is exactly the `correspondence ≠ derivation`
failure the mandate names.

## 2. Executed, not quoted — `A1`

```
s₁ = do(turn_on, S₀)
s₂ = do(turn_on, do(turn_off, do(turn_on, S₀)))

state(s₁) = {F}      state(s₂) = {F}      →  IDENTICAL
s₁ ≠ s₂                                    →  DISTINCT SITUATIONS
```

**`collision_demonstrated = True`.** No function of the state recovers the history. **Situation ≠
State is forced, not stipulated.**

## 3. The corpus established this INDEPENDENTLY — and earlier

`KR-HISTORY-2026-09-02` (`M-closure-event-kernel-vs-history.md`) ran the decisive configuration
**before this source was read**:

> `K_t , K_{t+1}` identical ∧ `History_1 ≠ History_2`

| test | result |
|---|---|
| **T2** — can historical closure be reconstructed from state alone? | **No** — *"identical current content, different histories; **no function of `K_t` separates them**"* |
| kernel reads of history | **0 reads, statically verified** |
| `ClosureEvent ∈ 𝒦` | **REFUTED** `[NEG]` |

> ### Independence label: **KnowledgeOS-DERIVED · CORROBORATED BY REITER.**
> **Not Reiter-derived.** KnowledgeOS reached the distinction on its own evidence; Reiter supplies a
> vocabulary and a proof architecture for it. **This is the strongest possible outcome for the
> mandate's independence requirement, and it must not be inverted in later citation.**

## 4. The candidate three-level model — only two levels are attested

The mandate asks whether the corpus supports:

```
History H  →  Situation S  →  State projection K
```

**Corpus search result:**

| concept | files in `docs/knowledgeos/research/` |
|---|---|
| `History` | **7** |
| knowledge state | 2 |
| provenance | 32 |
| identity | 16 |
| Observation | 18 |
| **"operation history"** | **0** |
| **"event history"** | **0** |

> `[NEG]` **The corpus distinguishes History from State. It has NO attested intermediate `Situation`
> level, and no distinct notions of "operation history" or "event history" at all.**
>
> **Therefore importing Reiter's three-level model would ADD a level the corpus does not have.** That
> requires independent evidence, not analogy. **Recorded as `OPEN`, not adopted.**

## 5. Would collapsing these concepts contradict corpus evidence?

**Yes — demonstrably, for `History` vs `State`.** `KR-HISTORY` T2 is a direct counterexample: the two
cannot be collapsed without losing a distinction the corpus has measured.

**Unknown for the rest.** `operation history` and `event history` have no corpus extension, so
"collapsing" them is not yet a meaningful operation. **`OPEN`.**
