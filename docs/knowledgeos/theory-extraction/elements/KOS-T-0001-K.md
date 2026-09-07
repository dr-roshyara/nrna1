# `KOS-T-0001` — `K`

**Theory element record · PILOT EXTRACTION · 2026-09-07**

> ### ⚠️ This is a **pilot extraction case**, not a theoretical priority
> `K` was selected because it **exercises many extraction mechanisms simultaneously** — multiple
> definitions, multiple glyph forms, partial implementation, unresolved inter-definition
> relationships, and an empty governed-decision field. **Not because `K` is more theoretically
> fundamental than other concepts.** *"Largest measured hole"* is **not** a prioritization principle
> for the theory, and **this first example must not become a privileged starting point.**

> ### ⛔ Adjudicates nothing
> **No canonical `K`. No definition selected. No definitions merged. No carrier chosen. `OQ-1`
> untouched.** All ten definitions remain alive. Theory v1.2 FROZEN · kernel NOT SELECTED.

| | |
|---|---|
| **ID** | `KOS-T-0001` |
| **Concept** | `K` — the knowledge state |
| **Category** | **Concepts** *(reason: `K` names an object, not a rule, a property or a claim. It is **not** `Definitions` — that category holds definitional statements; this record holds the **concept** and enumerates its ten.)* |
| **Status** | `[UN]` undefined · `[CT]` contradictory *(13-value, 3MC §3)* — **the concept is contested, not chosen** |
| **status_chain** *(MD-018)* | **`candidate`** — no stage skipped, none reached |
| **Grounding** | **mixed** — `D-01`/`D-02` architecturally-grounded and implemented; `D-03`–`D-10` are corpus-textual with no independent grounding recorded |
| **Confidence** | **high** on the enumeration and the counts; **none** on any relationship except one |

---

## Definitions — enumerated, **not** merged

| ID | definition | arity | files | with a result | implementation |
|---|---|:--:|---:|---:|---|
| **`D-01`** | `K = (𝒜, ℛ)` | 2 | **181** | **71** | ⭐ **`result-produced-on-it`** |
| **`D-02`** | `K_t = {Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` | 8 | **48** | **17** | ⚠️ **`type-exists` (partial — see below)** |
| **`D-03`** | `𝒦 = (K, H)` | 2 | 25 | 12 | `none` |
| **`D-04`** | `𝒦 = (E, S, T, O, P, R, Π, A)` | 8 | 16 | 9 | `none` |
| **`D-05`** | `𝒦 = (K, C, T, E, A)` | 5 | 12 | 6 | `none` |
| **`D-06`** | `K_t = (𝒜_t, ℰ_t, ℛ_t, 𝒞_t, ℋ_t, Γ_t)` | 6 | 7 | 5 | `none` |
| **`D-07`** | `𝕂 = {(G, σ, θ, λ, π)}` | set of 5-tuples | 5 | 4 | `none` |
| **`D-08`** | `K(t) = (E, C, I, A, P, R, U, B, M, S)` | **10** | file **0518** | — | `none` |
| **`D-09`** | `𝕂 = { admissible knowledge states }` | set, members undefined | — | — | `none` |
| **`D-10`** | `𝕂 = (Σ, M, B, D, O, δ)` | 6 | **2** | **0** | `none` |

⚠️ **Counts are citation measurements, not rankings of correctness.** A definition with two citations
may still be the right one. **This record does not rank them.**

⚠️ **The `𝒦` / `𝕂` / `K` script distinction is undeclared and load-bearing** — three definitions use
each script. **Whether the scripts denote different objects is unrecorded anywhere in the corpus.**

### Sources

`D-01` `verification/CLAUDE-CHATGPT-RECONCILIATION.md` · `D-02` `C-022`/step-049 (*"50 attack
classes, no counterexample"*) · `D-03` `gap-discovery/14-FALSIFICATION-RESULTS.md` ·
`D-04` `verification/findings/TV-F-034-039-steps-041-055-findings.md` ·
`D-05` `verification/prompts/20260830_1021_prompts.md` · `D-06` *"the corpus's FIRST `K_t` tuple"* ·
`D-07` `verification/KERNEL-RECONCILIATION-230-236.md` ·
**`D-08` `dimension-registry.md` file-landmark 0518** · `D-09` `verification/prompts/20260830_0952_prompt.md` ·
`D-10` `three_model_convergence/…/per-file/2283.yaml`

---

## Relationships — MD-017, per pair

$$\boxed{\textbf{1 of 45 pairs is evidenced. 44 are } \texttt{unresolved\_equivalence} \textbf{ — the protocol's default.}}$$

| pair | relationship | evidence |
|---|---|---|
| **`D-01` ~ `D-02`** | ⭐ **`refinement`** — a lossy semantic projection | `(𝒜,ℛ) =_semantic π_K(K_t)` after unpacking `Assertion`, modulo a **declared** drop of `{Event, Policy, Action}`; **explicitly NOT `=_structural`, NOT `=_observational`**; **definable but not computable** (`Qualify` has no body); `replay`, `policy-eval`, `authorize` unanswerable in `(𝒜,ℛ)`. *(Step 285, kernel research lane)* |
| **all other 44 pairs** | **`unresolved_equivalence`** | **none either way.** Not asserted equivalent, not asserted distinct |

⚠️ **`D-10` is the only definition with 0 result-bearing files.** That is a **citation observation**;
calling it a rejection candidate would be adjudication and is **not** done here.

⚠️ **`D-08` (ten components) and `D-04` (eight) have the same shape as `D-02` (eight) — a labelled
tuple of state components — yet share no component names with it.** Recorded; not interpreted.

---

## `Latest` — all four sub-fields, uncollapsed

| reading | value |
|---|---|
| **latest mention** | **2026-09-06** — `reviews/2026-09-06-KOS-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` |
| **latest refinement** | ⚠️ **ambiguous, and the ambiguity is the finding.** *Newest definition introduced:* `D-08` (file 0518). *Newest relationship established:* `D-01 ~ D-02` via `π_K`, **2026-08-31**. *Newest analysis citing one:* 2026-09-06. **Three different answers under three readings of "refinement."** |
| **latest implementation** | **2026-08-31** — `step-280/281/282/exec/kosmodel.py`, `reviews/synthesis/commission-operation-registry/exec/rm.py` |
| **latest governed decision** | 🔴 **`never`** *(applicable · searched `governance/` · no instance found)* — Verified: `governance/` holds **no act deciding `K`**. Its only 2026-09-04 entry, `EPISTEMIC-STATUS-VOCABULARY.md`, is a **methodological rule** whose sole `K`-adjacent line is an `[EXP]` scope note |

$$\boxed{\begin{array}{c}K \textbf{ is mentioned today, was last implemented on 2026-08-31,}\\ \textbf{and has NEVER been decided.}\end{array}}$$

`[REC]` **`EXT-05`'s recommendation is confirmed by this record:** even *"latest refinement"* needs a
declared rule (**introduce · alter · cite**) before it can be populated. **Carry the sub-readings; do
not collapse them.**

### ⚠️ A measurement defect found while populating this field

The first `latest mention` measurement returned **my own artifacts** —
`registry-extension-proposal/README.md`, `theory-extraction/01-BOUNDARY…`, `gap-discovery/INDEX.md`.

$$\boxed{\textbf{The extraction project contaminates its own measurement unless the SOURCE SCOPE is declared.}}$$

**Rule adopted for this project:** every corpus measurement declares its scope and **excludes
`theory-extraction/` and `verification/gap-discovery/`** — the extracting lanes are not corpus.

---

## Implementation — four-level granularity

| level | which definitions |
|---|---|
| **`result-produced-on-it`** | ⭐ **`D-01` only.** `class K: A: FrozenSet[Assertion]; R: FrozenSet[Tuple[str,str,RelationType]]` — docstring *"`K = (A, R)`. The corpus's terminal knowledge state."* — in `gap-discovery/exec/kos_kernel.py`, with operations (`mk`, `eq_structural`, `eq_content`, `T`, `replay`), reproduced in `step-280/281/282/exec/kosmodel.py`, and **8 result transcripts** |
| **`type-exists`** | ⚠️ **`D-02`, PARTIALLY and with a caveat** |
| **`none`** | **`D-03`–`D-10`** — eight of ten definitions have no code at all |

### ⚠️ A correction to my own earlier claim about `D-02`

The `OQ-1` brief said *"Carrier C implements both `K=(𝒜,ℛ)` **and** ratified-primitive types."* **True
as written and misleading as attribution.** Measured precisely:

```
class K:  A: FrozenSet[Assertion];  R: FrozenSet[...]        ← this is D-01
class Proposition / Observation / Evidence / Dimension       ← primitive TYPES exist
```

$$\boxed{\begin{array}{c}\textbf{No code builds } K \textbf{ from the eight primitives as its fields.}\\ \textbf{The primitives exist as the CONTENTS of } \mathcal A \textbf{, not as the components of } K.\\ \textbf{So } D\text{-}02 \textbf{ is NOT implemented AS A } K\textbf{-DEFINITION.}\end{array}}$$

`[INF]` **And this is exactly why `Implementation` needed four levels rather than a boolean.** A
boolean would have recorded `D-02` as *implemented* and hidden the distinction that matters.

⛔ **Hard rule applied:** *implementation is not architectural truth.* **`D-01` having code and the
other nine not having code is not an argument that `D-01` is correct.**

---

## Dependencies — what `K` presupposes

| dependency | status in the corpus |
|---|---|
| `Assertion` | implemented `(id, P, e, c, t, Π, σ)`; **`TG-08`: no identity, claim index `q` dropped** |
| `ℛ` / relation types | implemented; **acyclicity unenforced** |
| `Σ` epistemic status | **4 competing definitions** — a sibling element, not settled |
| equality / identity | **9 registers, 5 names for one structural relation**; `≡_sem` **OPEN** (`CR-4`) |
| `δ` transition | **OPEN** (`CR-3`) — **and `δ` has no commit case: executed, `K₁ is K₀`** |
| `ℐ` invariants | **7 candidates, 0 established**; blocked in **66 of 66** worlds |
| `Provenance` / `Π` | entry 10 in 3MC, **contested**; the glyph carries **6 meanings** |

⚠️ **Six of seven dependencies are themselves unsettled.** `[INF]` **A definition of `K` cannot be
more settled than what it is built from** — recorded as an observation about extraction order, **not**
as an argument for any sequencing decision.

## Conflicts

`D-01`…`D-10` are **not shown to conflict** — 44 pairs are simply unevaluated. **What *is* measured:**
three arities appear under the same glyph family (2, 5, 6, 8, 10) · **the script distinction is
undeclared** · `D-10` has zero result-bearing files.

## Gaps

**No concept entry for `K` exists in 3MC's registry** (27 concept entries, none is `K`) ·
**no governed decision** · **no rule for `𝒦` vs `𝕂` vs `K`** · **44 unevaluated pairs** ·
**`D-09` has undefined members** — a schema, not a definition.

## Open questions

1. Do `𝒦`, `𝕂` and `K` denote different objects? **Undeclared and load-bearing.**
2. Is `D-08`'s ten-component state a supersession, a parallel model, or a different concept?
3. Are `D-03`…`D-07` **historical** (superseded by their own authors) or **local** (bounded-context)? — **the corpus records no supersession for any of them.**
4. Does `D-09` (*"admissible knowledge states"*) belong in `Concepts` at all, or in `Constraints`?
5. Is `D-01 ~ D-02`'s projection the *only* evidenced relationship, or the only one **looked for**?

## Implementation consequence

**Two independent engineers implementing `K` from the corpus today would choose different
definitions** — nine of ten give them no code to follow, one gives them a working type, and **nothing
tells them the tenth is the intended one.** ⚠️ **Stated as an extraction finding, not as an argument
for ratifying `D-01`.**

---

## Provenance of this record

**Evidence consumed:** 3MC (27 concept entries, 96 file landmarks, 1 224 per-file records — **as
evidence, never as authority**) · this lane's `V1`/`V3` and the `OQ-1` brief · `research/` and
`verification/**/exec/` (**168 `.py`, 3 estates**) · `governance/`.

**Reproduce:**

```bash
cd docs/knowledgeos
for p in '\(𝒜, *ℛ\)' '\{ *Entity, *State, *Event' '𝒦 *= *\(K, *H\)' '𝒦=\(E,S,T,O,P,R,Π,A\)' \
         '𝒦 *= *\(K,C,T,E,A\)' '𝒜_t,ℰ_t,ℛ_t' '𝕂 *= *\{\(G,σ,θ,λ,π\)' '𝕂=\(Σ,M,B,D,O,δ\)'; do
  n=$(grep -rlE -- "$p" --include='*.md' . | wc -l)
  r=$(grep -rlE -- "$p" --include='*.md' . | xargs -r grep -lE '\[EXP\]|EXECUTED|witness' | wc -l)
  echo "$n $r $p"; done
grep -rl "^class K\b" --include='*.py' . | xargs -r ls -t | head -1        # latest implementation
grep -rlE '\bK_t\b|carrier' governance/*.md                               # governed decision → rule only
```

**Four hard rules honoured:** 3MC consumed as evidence, not authority · extraction is not
adjudication — **ten definitions remain alive** · implementation is not architectural truth —
**`D-01`'s code proves nothing about `D-01`'s correctness** · canonicalization comes last — **there is
no canonical `K` in this record.**

---

# Schema v2 revalidation — **appended 2026-09-07, v1 text above unchanged**

`kind:` **`object`** · `Category` (v1) `Concepts`, unchanged and uncontested.

## Definition dispositions (Defect C) — ⭐ **10 → 4**

| disposition | entries | basis |
|---|---|---|
| **1 · definitions of `K`** | **`D-01`, `D-02`, `D-06`, `D-08`** | all use the `K` glyph |
| **2 · different candidate** | **`D-03`, `D-05`** → **`𝒦`**, ID reserved **`KOS-T-0006`** | ⭐ **containment: `𝒦=(K,H)` and `𝒦=(K,C,T,E,A)` contain `K`, so `𝒦 ≠ K`** |
| **3 · ambiguous identity** | **`D-04`** (`𝒦`), **`D-07`, `D-09`, `D-10`** (`𝕂`) | script undeclared; **no containment evidence either way** |

⚠️ **Nothing deleted.** Unevidenced pairs to evaluate: **45 → 6**.
⚠️ **Consequence:** *"how many definitions does `K` have?"* **has no answer** until the
`𝒦`/`𝕂`/`K` script question is settled.

## Implementation (Defect A)

| | level | provenance | selection |
|---|---|:--:|:--:|
| `D-01` | `result-produced-on-it` | **`stipulated`** — `FrozenSet`, the relation triple and `RelationType` are encoding choices `(𝒜,ℛ)` does not fix | **`stipulated`** — chosen from ten, no act |
| `D-02` | 🔴 **`none`** *(reclassified `R-1`; v1 said `type-exists` partial)* | `n/a` | `n/a` |

**Governing rule applies:** *`D-01`'s code does not ratify `D-01`.*
