# 11 — Mandatory negative tests `P1`–`P10`

**A failed correspondence is a valuable result.** Each verdict carries its independence label.

| # | proposition | verdict | basis |
|---|---|---|---|
| **P1** | Situation = KnowledgeOS state | **REFUTED** | `A1` executed; **`KR-HISTORY` T2 independently** — *no function of `K_t` separates the pair*. **KnowledgeOS-DERIVED, corroborated by Reiter** |
| **P2** | Action = KnowledgeOS event | **REFUTED as stated** | the mandate itself forbids assuming `a = e`. The corpus has **event · operation · command · observation · authority act** as *candidate* correspondents and **decides none**. `OPEN` |
| **P3** | Reiter's `δ` completely defines KnowledgeOS `δ` | **REFUTED** | the SSA presupposes **causal completeness** and **consistent effect axioms**; `A2` shows it **silently resolves `γ⁺ ∧ γ⁻` in favour of `γ⁺`**. KnowledgeOS treats contradiction as first-class (`KR-CONTR-*`), so it cannot adopt a transition that cannot see it |
| **P4** | Reiter solves `Qualify` | **REFUTED** | `Poss(a,s)` is a **precondition gate** (`A4`). It says whether an action may occur, **not whether a qualification holds of a knowledge item**. Different type, different position in the pipeline. `Qualify` remains `OPEN` |
| **P5** | Reiter resolves KnowledgeOS equality | **REFUTED** | unique-names axioms give **syntactic** identity of situations. `≡_sem` asks about **semantic** equivalence of knowledge content. Reiter supplies no bridge. `≡_sem` remains `OPEN` and **NOT refuted** |
| **P6** | Reiter makes `K_t` history-complete | **REFUTED** | the opposite: `A1` + `A5` show observationally equivalent situations with different histories. **State is history-INcomplete by construction**, which is the point |
| **P7** | Reiter makes provenance unnecessary | **REFUTED** | a situation records *which actions*, not *on whose authority, from which source, in which channel*. The corpus carries provenance in **32** files and `KR-M2O` shows **channel independence is load-bearing** (0.75 % vs 99.3 %). Reiter is silent on it |
| **P8** | Reiter's knowledge operator = KnowledgeOS Knowledge | **REFUTED** | Reiter's `Knows` is accessibility-relation based and **factive by construction**. KnowledgeOS **decided `R1`**: its object is `A_t`, an **attribution**, and *no component may assert `Knows`*. **They are different predicates by governance decision** |
| **P9** | Golog is directly usable as the KnowledgeOS workflow model | **REFUTED as "directly"** | Golog's `Do` macro presupposes a **basic action theory** — SSA + preconditions + unique names + `S₀` — **none of which KnowledgeOS has**. Golog is unreachable until `P3` is resolved. `OPEN`, **not** refuted as a long-term candidate |
| **P10** | progression is sufficient for KnowledgeOS state evolution | **REFUTED** | progression computes `K_{t+1}` and **discards the history** (`A1`). `KR-HISTORY` establishes that KnowledgeOS **writes** history. Progression alone would satisfy the kernel's read behaviour (0 reads) but **not its write obligation** |

## The pattern in these ten

**Nine of ten are refuted, and none is refuted because Reiter is wrong.** Each fails because a Reiter
construct is **well-defined relative to assumptions KnowledgeOS does not satisfy** — causal
completeness, consistent effect axioms, factive knowledge, an existing action theory.

> **The correct reading is not "Reiter does not transfer."** It is: **Reiter's machinery is available
> exactly to the extent that KnowledgeOS supplies the assumptions it requires — and the assumptions
> are precisely the programme's open items.**
