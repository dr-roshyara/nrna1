# 00 — New-Artifact Inventory

**Measured 2026-09-02.** My lane's previous pass ended **2026-09-01 20:15**. Everything below
post-dates it or was not in my evidence base.

**Nothing in this package modifies the corpus, ratifies anything, or resolves an open question.**

---

## 1. What landed, measured

| Location | Files | Newest | Not previously in my evidence base |
|---|---:|---|---|
| `phase_measure_theory/knowledgeos_kernel/` | **265** | 2026-09-02 19:16 | ⬅ **entire tree** |
| ↳ `research/` (flat) | 62 | 2026-09-01 22:19 | `REFINED-STEP-286`…`291`, gap updates `01`–`38`, `00-INDEX.md` (1 779 lines) |
| ↳ `research/step-288…292/` | 55 | 2026-09-02 19:16 | ⬅ five sub-packages **with `exec/`** |
| ↳ `prompts/` | 117 | 2026-09-01 | mandates and reviews |
| `docs/knowledgeos/research/kernel-reduction/` | **22** | 2026-09-02 08:58 | ⬅ **ablation experiment `KR-2026-09-01`** |
| `docs/knowledgeos/research/theory-v1.1-simulation/` | **14** | 2026-09-02 09:14 | ⬅ **executable theory simulation** |
| `docs/knowledgeos/research/theory-v1.2-simulation/` | **33** | 2026-09-02 17:33 | ⬅ **26 named experiments + 2 decision records** |
| `brainstorming/three_model_convergence/` | **1 279** | 2026-09-02 22:09 | ⬅ new lane |
| `brainstorming/mathematical_ideas_that_can_be_implemented/` | **185** | 2026-09-02 21:51 | ⬅ **`ℛ_req` + `SPEC-DET` + ratification claims** |
| repo-root `research/` (**code**) | 68 `.py` · **7 384 LOC** · 122 `.json` | 2026-09-02 | ⬅ **executable, and it runs** |

## 2. `INV-9` is stale by a factor of 25 `[CORPUS]`

My `00-CORPUS-INVENTORY.md` recorded **3 `.py` files in the whole `docs/knowledgeos/` tree** and
built an argument on it. Measured today:

```
docs/knowledgeos/**.py                     77      (was 3)
repo-root research/**.py                   68      7 384 LOC
JSON result files                         122
```

**And it executes.** I ran it:

```
$ cd research/knowledgeos-sim && python3 run_v12.py
wrote results/v12_E1_zero_readings.json … v12_E5_zero_ontology.json
DONE.
```

$$\boxed{\textbf{INV-9 WITHDRAWN. An executable theory simulation exists, is reproducible, and I reproduced it.}}$$

⚠️ **What this does NOT mean.** It is a simulation **of the theory**, self-labelled
`[EXP] · not canonical architecture · not a production implementation`. My readiness verdict spoke
about a *product* implementation and that part stands — but the sentence *"nothing executable
exists"* was false when I wrote it and is now false by a wide margin.

## 3. The three lanes now writing, and their disciplines

| Lane | Where | Evidence key |
|---|---|---|
| **mine** (verification) | `brainstorming/verification/` | `[EXT]` `[CORPUS]` `[INF]` `[PROP]` + 10 evidence classes |
| **kernel research** | `phase_measure_theory/knowledgeos_kernel/` | `[S]` `[C]` `[P]` `[R]` `[H]` + `CORPUS` `DERIVED` `EXECUTED` `NORMATIVE` |
| **theory simulation** | `docs/knowledgeos/research/` | `[EXP]` `[NEG]` `[OPEN]` `[CORPUS]` `[EXT]` `[INF]` `[PROP]` |

`[INF]` **All three independently converged on the same tagging discipline and the same governing
rule** — *the strongest statement made must never exceed the strength of the available evidence.*
The kernel lane's `three_model_convergence/` protocol adopts it as non-negotiable #7. **That rule is
this repository's, from `CLAUDE.md`; three lanes reached it as a research constraint.**

## 4. Two registry hazards, both already found by the other lane and both affecting my citations

**(a) Provenance-by-directory is unreliable.** `kernel-reduction/00-INDEX.md` records a file
(`Yes. I read the full attached document,`) that appeared in its directory **during** the experiment
and belongs to another thread — *"Directory location is being used as provenance, and it is not
reliable provenance."* **My package cites by path throughout.** Every path citation in my lane now
carries this caveat.

**(b) 20 files from 2026-09-02 carry raw-first-line names**, not the
`timestamp_step_xx_short-description.md` convention established across four prior passes — including
the four most load-bearing new documents (`# Required distinction.md`, `# SPEC-DET-2026-v1.md`,
`# KNOWLEDGEOS — RATIFICATION PACKAGE`, `# KR-RREQ-2026 — Final Ratification Asse`). **Not renamed
here — no rename was commissioned.** Recorded so the citations below are traceable.

## 5. Scale note on `three_model_convergence/`

**1 279 files · 1 260 of them in `01_source-analysis/` · 13 of the 16 numbered lanes are EMPTY.**

`[INF]` A protocol with a manifest, a `progress.tsv`, a resume script and eight non-negotiables — and
its output is concentrated almost entirely in the reading stage. **It is a protocol executing, not a
result.** Its own rule says so: *"no file is permanently classified during pass 1."* **I read its
control files and its two non-empty output lanes; I did not read 1 260 source-analysis records, and
nothing in this package rests on them.**
