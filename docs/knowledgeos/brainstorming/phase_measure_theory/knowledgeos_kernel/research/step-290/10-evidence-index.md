# 10 — Evidence Index (mandate §12 item 13)

## Primary corpus, with the exact role each plays

| Locus | Role in the N-1 verdict | Class |
|---|---|---|
| `246 §A–D` | four relations, **non-interchangeable** — distinction ground 1 | **CORPUS** |
| **`261.1`** | six relations; *"should therefore **not** be collapsed"* — ground 2 | **CORPUS** |
| **`261.20`** | *"KnowledgeOS requires a typed **family** of relations"*; *"they should not be conflated"* — ground 3 | **CORPUS** |
| **`261.25`** | `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)` — ground 4, and the **second notation** | **CORPUS** |
| `261.19` | *"no single equality relation is adequate for all operations"* — ground 5 (presupposes plurality) | **CORPUS** |
| **`261.5`** | **defines `≈`** for histories and states | **CORPUS — definition** |
| **`261.21`** | **the candidate `≡_K`**, and the boxed `𝒪_K` caveat — **the decisive evidence** | **CORPUS — candidate** |
| **`258.8`** | the earlier proposal *"a stronger definition is observational"* | **CORPUS — proposal** |
| **`258.37`** | *"we still do not have the final `=_X` or `≡_K`"* — confirms non-assertion | **CORPUS** |
| `258.9` · `258.23` · `258.30` | congruence, **independent of `≈`** | **CORPUS** |
| `258.11` | separates label equivalence from behavioural — keeps `≈_X` out of `∼_H` | **CORPUS** |
| `261.8` | `≅_P`'s two branches — keeps `≅_λ` out of the comparison | **CORPUS** |
| `261.23` | the six conditions — impact analysis | **CORPUS** |
| `012 §35` | `≡` **not fully decidable** — bounds what any branch can achieve | **CORPUS** |
| `264.15` | `≡_D` — level separation | **CORPUS** |
| `060 §60.42` · `§60.71` | the `PureClaimSetUnion` scope and *"cannot yet assert"* — **C-7** | **CORPUS** |

## Executed

| Witness | Result |
|---|---|
| `step-290/exec/t290_n1_audit.py` + `OUT-…txt` | 20-entry register; 5 defined / 15 named; the same formula under 2 symbols; **both `≡_K` loci CANDIDATE/PROPOSAL**; distinguishability **FAILS LOUDLY** on two absent definitions; 9 negative checks | 
| carried from `step-289/exec` | 30 non-degenerate `≈_X` (not re-run, per §5) |
| carried from `step-288/exec` | `=` not a congruence; hash canonicalization-relative; `id` re-keys on withdrawal |

## Explicitly NOT used

⚠️ **No philosophical source** — no Gītā material, no Cavell, no Chalmers, no process algebra imported as
architecture. *(External literature appears nowhere in this step, not even for classification.)*
⚠️ **No governance preference.** ⚠️ **No hypothetical state pair** — §4's prohibition held; the
distinguishability witnesses were reported `UNDECIDABLE` rather than constructed.
⚠️ **No verification-lane artifact used as a definition** — only as the target of `C-7`'s correction.

## Evidence threshold check (mandate §10)

| Requirement | Met? |
|---|---|
| 1 at least one primary definition | ✅ five |
| 2 all materially conflicting primary definitions | ✅ `261.5` vs `261.21` vs `258.8` — all three, verbatim |
| 3 an explicit comparison | ✅ `02` |
| 4 no hidden normative assumption | ✅ — and the one temptation (assuming `≡` is provenance-blind, to build a witness) was **declined**, since it would have presupposed Decision 3 |
| 5 no imported philosophical/external premise | ✅ |

$$\boxed{\text{Threshold met for } N\text{-}1A. \text{ NOT met for semantic distinguishability, which is reported } \textbf{UNDECIDABLE}.}$$
