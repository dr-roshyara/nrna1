# `KOS-T-0008` — `Π`

**`[EXP]` · stress case: *extreme glyph/polysemy collision and candidate identity*.**
**Adjudicates nothing.**

**Scope:** as `KOS-T-0007` — 4 245 in-scope files, extracting lanes excluded, **implementation too.**

| | |
|---|---|
| **kind** | 🔴 **UNRESOLVED — and that is the finding.** The six readings are of **different kinds**: `object` (provenance record), `structure` (the `(Q,C,O)` frame), `operation` (the observable map), `value-set` (Policy). ⛔ **`kind` cannot be assigned to `Π`; it can only be assigned per reading** |
| **Category** | 🔴 unassignable for the same reason |
| **Status** | `[CT]` · `status_chain: candidate` |
| **Grounding** | **per reading** — R1 architecturally-grounded; R2/R3 corpus-textual; R7 **contested/ungrounded** |

## 🔴🔴🔴 Candidate identity does NOT survive the glyph collision

The v2 schema assumes one record = one candidate, with definitions of *that* candidate. **`Π` breaks
the assumption**: its readings are not competing definitions of one thing but **different things**.

| reading | what it is | kind | in-scope | disposition |
|---|---|---|---:|---|
| **R1** | **provenance** — `id = H(P,e,c,t,Π)`, `Lineage = Π ∘ ℛ_der*`, **Decision 3: `Π ∈ ≡?`** | `object` | **34** | **1** — the anchor reading |
| **R2** | **`Π = (Q,C,O)`** — the **inquiry frame** | `structure` | **2** | **2** — different candidate |
| **R3** | **`Π = preservation contract`** | `structure` | **2** | **2** |
| **R4** | **`Π : 𝓡 → O`** — the **observable** (Theory-01) | `operation` | ⚠️ **2** | **2** |
| **R5** | **`Π_t`** — probabilistic representation layer | `object` | **29** | **3** — ambiguous |
| **R6** | **Policy** | `value-set`? | 15 | **3** — ambiguous |
| **R7** | ⚠️ **`Π = (X, 𝒯, ℰ, ~_Q, Q, Π)`** — **contains `Π` as its own last component** | 🔴 **malformed** | **4** | 🔴 **no disposition fits** |

$$\boxed{\begin{array}{c}\textbf{Four of seven readings are disposition 2 — DIFFERENT CANDIDATES sharing a glyph.}\\ \textbf{This record therefore describes a GLYPH, not a candidate.}\end{array}}$$

⭐ **`kind` had to be assigned per reading, not per record.** That is a schema-level result: **the
record's identity assumption fails when a glyph is this overloaded** — stress report §A/§B.

## 🔴 R7 — the self-containing tuple has no disposition

`Π = (X, 𝒯, ℰ, ~_Q, Q, Π)` is not disposition 1 (not a definition of `Π` — it presupposes `Π`),
not 2 (not a different candidate — it names `Π`), not 3 (identity is not merely *unsettled*), not 4
(it is presented **as** a definition).

$$\boxed{\textbf{It is either a transcription defect or a genuine recursion, and the vocabulary cannot say "malformed".}}$$

⚠️ `[PROP]` **new disposition 5 — `malformed / ill-formed as presented`.** **Not applied.** ⛔ **I do
not know which of the two it is, and guessing would manufacture a claim** — stress report §G.

## Implementation — **in-scope only**

| reading | level | provenance |
|---|---|:--:|
| **R1 provenance** | **`result-produced-on-it`** — `research/knowledgeos-sim/kos/types.py`, `kos12/evalc.py` (`provenance_state`), `kos12/distinguish.py`, `zero-algebra/KR-ZERO-ALGEBRA/code/experiments.py` (`Item.source`) | **`stipulated`** |
| **R4 observable** | **`operation-runs`** — `zero-algebra/KR-BRIDGE-02/code/observables.py` | **`stipulated`** |
| R2, R3, R5, R6, R7 | **`none`** | `n/a` |

⚠️ **Two readings are independently implemented in different estates — which strengthens rather than
resolves the collision:** the code does not agree that they are one thing either.

## Latest — four independent, **and they belong to different readings**

**mention** 2026-09-06 *(R1)* · **implementation** 2026-08-31 *(R1, R4)* · **refinement** R5's
`Π_t` layer, 2026-09-01 · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*
🔴 ⭐ **New temporal ambiguity:** the four `Latest` values **do not describe the same object** — R1's
latest mention and R4's latest implementation are facts about **different candidates**. Stress report §E.

## Dependencies · separated records

**R1** depends on `Assertion`, `id`/hash, `ℛ_der`, **and Decision 3 (`Π ∈ ≡?`) — OPEN**.
⚠️ **No sequencing implied.**

| record type | content |
|---|---|
| **proposal** | R2's `Π = (Q,C,O)`; R3's preservation contract |
| **decision** | 🔴 **none** — and **Decision 3 is explicitly OPEN**, which is the reason the collision is dangerous |
| **implementation fact** | R1 and R4 are implemented, in **different estates**, with **no cross-reference** |
