# R1 — `Det` / `Det_r` Disambiguation

*(Follow-up to Phase 11 §3, which found `Det` and `Det_r` merged into one graph node.)*

## 1 · `Det_r` — confirmed single, narrow, stable

**Every** occurrence of the explicit subscript `Det_r(...)` in firewall-free primary and
contemporaneous sources (17 occurrences, 4 files, after fixing a nested-paren regex bug that
had hidden most of them) has **exactly one signature**:

$$Det_r\big(EvalReq(K,r,EC,\Gamma),\ EC\big)$$

Born **part-06 §6.18** (2026-09-06 00:39), restated verbatim in the corpus-contemporaneous
`what_is_knowlegeos_theory/` files (09-09), and reproduced in one untracked
(`UNRECORDABLE` provenance) audit file. No competing signature exists anywhere.
**Phase 11's disambiguation of `Det_r` stands unchanged — closed.**

## 2 · ⚠️ Plain `Det` was never one object — my Phase 11 node silently picked ONE of several

`Det` (no subscript) has **665** occurrences across ≥9 argument-type signatures. Clustered by
argument type (not spelling — same lesson as `C10` in Phase 5):

| Signature | n | earliest | example |
|---|---|---|---|
| `Det(K,p,EC,Γ)` | 43 | **2026-09-06**, part-03 | `Det(K,p,EC,Γ) ⟺ ∀r∈Req_p(EC,Γ): Sat(K,r)=Satisfied` |
| `Det(E,Q,Γ) → A` | 50 | 2026-09-06 | `Det(EVal,Q,Γ) → A` |
| `Det(E,Q,C,S)` | 43 | 2026-09-06 | `Det(E_t,Q_t,C_t,S_t) = 𝒜_t` |
| `Det(E)` | 48 | 2026-09-06 | `Det(E_1) = U` |
| `Det(E,Q)` | 34 | mixed | `Det(E_t,Q) = {h_1}` |
| `Det(Q)` / `Det(p)` | 14 | 2026-09-06 | — |
| ⭐ `Det(H)` | 107 | **2026-09-20** | `Det(H_Q) = {M_1,M_2}` |
| ⭐ `Det(H,Q,Γ)` | 56 | **2026-09-20** | `Det(H_1,Q,Γ) = Det(H_2,Q,Γ) ∀H_1,H_2∈[H]_O` |
| ⭐ `Det(H,Q,Γ,C_u,C_a)` | 23 | **2026-09-20** | `Det(H,Q,Γ,C_u,C_a^1) = Det(H,Q,Γ,C_u,C_a^2)` |

**Which one did Phase 11 use?** The `Det(K,p,EC,Γ)` form (part-03, 09-06) — the only one
already known to me at the time. That resolves the immediate confusion Phase 11 flagged
(`Det` vs `Det_r`), but it silently ignored the six other `Det` families listed above.

## 3 · ⭐⭐⭐ The real finding: this is two eras, not one confusable name

The **`H(hyp)`**-argument families (186 of 665 occurrences, 28%) all trace to **2026-09-20**
— none earlier — from files named `step-369` through `step-556`, living under
`brainstorming/knowledgeos_theory_research/corpus/knowledge_os_with_lina_puran/`.

$$\boxed{\text{This is not a naming clash inside the corpus I audited. It is a \textbf{new, later research era} I have not read.}}$$

That directory was added **2026-09-20** in one commit (`91ae27d92`, "752 files... across seven
passes") and contains **587 step-numbered files**, `step-001` through `step-586` — continuing
the *exact same* numbering scheme as the kernel programme (`step-285`…`step-292`) I audited in
Phase 7. It is not a side corpus; it is the direct sequel to material my v1.2 verdict rests on.

## 4 · Disambiguation verdict for R1's original scope

| Pair | Verdict |
|---|---|
| `Det_r` vs. `Det(K,p,EC,Γ)` | **DISTINCT — confirmed** (Phase 11, unchanged): different codomain, different role |
| `Det(K,p,EC,Γ)` vs. `Det(E,Q,Γ)→A` | **DISTINCT** — one is a Boolean predicate over a proposition, the other outputs a hypothesis-acceptance set `A` |
| `Det(E,Q,Γ)→A` (09-06 era) vs. `Det(H,Q,Γ)` (09-20 era) | ⚠️ **UNASSESSED** — both look like hypothesis-space determination over evidence/context, close enough in shape to be a **refinement candidate**, but I have not read the 09-20 sources closely enough to claim identity, continuity, or replacement. `IDENTITY UNWITNESSED`, not decided either way. |

**R1 as originally scoped (disambiguate `Det` against `Det_r`) is CLOSED.** What it surfaced —
a second research era using overlapping notation — is **new and out of scope for R1**; it is
recorded, not resolved, here.

## 5 · What this means for the standing v1.2 verdict

My chronological census (2,888 files) and everything built on it (Phases 1–14, the Phase 12
closure matrix) predates this material. The corpus is now **6,600 firewall-free files** — more
than double. This one `Det(H,...)` sighting is a symptom, not the finding: there is an entire
unaudited continuation of the step-numbered research programme, dated **2026-09-17 to
2026-09-20**, that could bear on every open item in Phase 12 §5 — especially the ones marked
"Entscheidung" (decision pending) or "unverändert", since a live research programme may have
moved on them in the ten days after my last pass.

**This is not resolved by R1, R3, or R5.** It needs its own triage pass before any of my Phase
12 conclusions can be called current.
