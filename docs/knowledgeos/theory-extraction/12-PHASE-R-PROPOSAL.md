# Phase R — Corpus Revisit / Second-Pass Extraction · **PROPOSAL ONLY**

**2026-09-07.** Design document. **Not added to programme governance. Not scheduled. Not executed.**

> ⛔ **No programme document modified · Schema v2 unmodified · v3 not begun · `P-06`/`Q2`/`Q3`/`Q4`
> not decided · no extraction record modified · no corpus revisit performed · 3MC /
> `dimension-registry.md` / MD-017 / MD-018 / brainstorming corpus untouched · no theory adjudicated ·
> no carrier · no kernel.**

---

## 0. The three dependency types — used throughout, never conflated

| type | form | valid here? |
|---|---|:--:|
| **provenance** | *X's output is EVIDENCE for Y* | ✅ **3MC → Phase R** |
| **methodological** | *Y's method must be settled before X can be done CORRECTLY* | ✅ **`P-06` → broad extraction → Phase R** |
| **scheduling** | *X must FINISH before Y may START* | 🔴 **not established for 3MC → Phase R** |

$$\boxed{\textbf{provenance} \neq \textbf{methodological} \neq \textbf{scheduling}}$$

`[EV]` **The distinction is not academic.** 3MC's reading pass stands at **1 224 / 2 376 ≈ 52 %** and
it wrote concurrently while this session ran. **A diagram arrow that means "provenance" but is read as
"scheduling" would block Phase R on another lane's throughput.**

## 1. Purpose — a second **epistemic** pass, not a cleanup pass

$$\boxed{\begin{array}{c}\textbf{First pass asks: WHAT IS IN THE CORPUS?}\\ \textbf{Phase R asks: DID WE INTERPRET OR CLASSIFY ANYTHING WRONGLY, INCOMPLETELY, OR TOO NARROWLY?}\end{array}}$$

**Phase R must be permitted to conclude:**

- *"the first extraction was wrong"* · *"there are two competing candidates here, not one"* ·
  *"this apparently abandoned idea is a live alternative"* · *"the later theory does not justify
  rewriting the earlier brainstorming."*

`[EV]` **Why the framing matters, from this session's own record.** Every correction I made was a
**reversal, not a refinement**: `INV-9` **3 → 77** · *"neither lane cites the other"* **false** ·
`Θ` wrong **three separate ways** · **123 → 27** concept entries · **`𝒦 ≠ K`** · `Zero` `D-03` **one →
four**. **A cleanup framing would have surfaced none of them.**

⛔ **Explicitly forbidden in Phase R:** polishing the first interpretation; treating a first-pass
record as the baseline against which the corpus is judged; deleting first-pass evidence.

## 2. Revisit queue — the `revisit_required` mechanism *(design, not implementation)*

**What exists today:** v2's **append-never-edit** rule preserves history — `K`'s v1→v2 revalidation
is a worked instance where **ten definitions became four dispositions and nothing was deleted.**

**What does not exist:** a **structured queue**. Today a revisit trigger survives only as prose in
whichever record happened to notice it.

### What creates a trigger `[PROP]`

| # | trigger | instance from this session |
|---|---|---|
| **T-1** | later evidence changes an earlier record's interpretation | `K` — the containment criterion arrived after `K` was written |
| **T-2** | a schema defect is found that a record could not express | `Σ` `D-05` (`F-4`), `Zero` `D-03` (`F-5`) |
| **T-3** | a measurement is found stale | `INV-9`, the 0.7 %→52 % coverage figure |
| **T-4** | a disposition is recorded as **ambiguous** | `K`'s 4 · `Π`'s R5/R6 |
| **T-5** | a `never` is recorded whose scope later widens | any scope change |
| **T-6** | an external lane's finding bears on a record | 3MC's `05_cross-model/` appearing mid-session |

### What the trigger must preserve `[PROP]`

```
trigger_id · created_at · trigger_type (T-1…T-6)
record + field affected              ← what may be wrong
original_reading                     ← IMMUTABLE, quoted
new_evidence  → source citation      ← points at the ORIGINAL corpus, never at an extraction artifact
why_it_may_change_the_reading        ← the argument, not the conclusion
status: open | reviewed | superseded
```

⚠️ **`new_evidence` must cite the original corpus.** A trigger created by reading another extraction
record would make extraction its own evidence — forbidden by **`P-16`**.

### How history stays immutable, and how a second-pass result lands `[PROP]`

```
first-pass record        ← never edited; the v2 append rule already guarantees this
      │
      ├── trigger (queued, immutable once created)
      │
      └── SECOND-PASS RECORD  ── a NEW record, cross-referenced
              ├── supersedes: <first-pass record + field>
              ├── reversal: yes | no          ← ⭐ reversals recorded explicitly
              └── first-pass reading PRESERVED verbatim
```

$$\boxed{\textbf{A second-pass result NEVER rewrites a first-pass record. It creates a new one and points back.}}$$

## 3. Independence from first-pass interpretation — the anchoring safeguard

`[EV]` **The failure mode is demonstrated, by me.** I carried *"neither lane cites the other"* for
**six days** and repeated *0.7 % coverage* from a **five-day-old artifact** — in both cases because I
**re-cited instead of re-measuring**.

⚠️ **`P-16` does not cover this.** `P-16` forbids *citing* extraction artifacts as evidence. It says
nothing about **anchoring** — reading the corpus while holding one's own prior interpretation.

### Proposed safeguards `[PROP]`

| # | safeguard | rationale |
|---|---|---|
| **S-1** | ⭐ **re-measure, never re-cite.** Every quantitative claim carried into Phase R is **re-run**, with its scope re-declared | the two demonstrated failures were both re-citation |
| **S-2** | **blind re-extraction of a sample** — a subset extracted **without reading the first-pass record**, then compared | detects anchoring rather than assuming its absence |
| **S-3** | **record reversals explicitly** — `reversal: yes` is a first-class field, and reversals are **counted** | a pass with zero reversals is **evidence of anchoring**, not of first-pass quality |
| **S-4** | **the first-pass record is opened only AFTER the second reading is written** | procedural, cheap, and the only one that structurally prevents anchoring |

⚠️ **`S-2` and `S-4` are in tension with efficiency and may not both be affordable. Recorded as
alternatives, not as a set.**

## 4. Structural-diversity pilot — selection criterion

⛔ **Not by topic. Not by importance. Not by sampling.**

`[EV]` **Why:** the `K` pilot exercised *one* structural case and taught nothing about operations,
relations, overloaded glyphs or cross-domain names. **Four further concepts each broke something
different.** Topic sampling would have re-run the `K` case.

### The pilot must span these structural cases — each with its discovered instance

| # | structural case | discovered in |
|---|---|---|
| 1 | multiple definitions under one glyph | **all eight** |
| 2 | **multiple candidates** under one glyph | `K`, `δ`, `Σ`, `Π`, `Zero` |
| 3 | ⭐ **one definition, multiple implementations** | **`Qualify`** — 2 realizations, both declaring `Observation × Policy ⇀ Evidence` |
| 4 | ⭐ **implementation with no textual definition** | **`Σ` `D-05`** (`F-4`) |
| 5 | ⭐ **rival definitions implemented side by side** | **`Zero`** — `zero_strict/weak/kleene/reasoned` |
| 6 | ⭐ **implemented definition is SUPERSEDED** | **`Σ`** — `D-05` ≅ `Σ₀` |
| 7 | ambiguous glyph forms — script and subscript | `ℐ`/`𝓘` · `≡_sem` vs `≡_H` · `𝒦`/`𝕂`/`K` |
| 8 | **operation** | `δ`, `Qualify` |
| 9 | **relation** | `≡_sem`, `Zero` `D-01`–`D-05` |
| 10 | **constant** | `δ = 0.3099`, `0_i ∈ 𝒟_i` |
| 11 | **value-set** *(kind `[PROP]`)* | `Σ` |
| 12 | **register/interface** *(kind `[PROP]`)* | `ℐ` |
| 13 | ⭐ **malformed / self-referential definition** | **`Π` R7** — `Π = (X,𝒯,ℰ,~_Q,Q,Π)` |
| 14 | ⭐ **model-of vs realization** | **`ℐ`** — `"NOT ENUMERATED"` |
| 15 | **massive symbolic occurrence** | `Σ` — 143 files |
| 16 | cross-model correspondence | the Gītā register's T4 corroborations |

⚠️ **A pilot that does not cover cases 3–6, 13 and 14 has not been tested against the failures
already found.**

## 5. Corpus scope — manifest, coverage, stopping criterion

`[EV]` **The gap:** 3MC has `reading-manifest.tsv` (2 376), `progress.tsv` (3 449) and a resume
script. **Theory Extraction has eight records and no denominator.**

$$\boxed{\textbf{Phase R cannot revisit what was never scoped. "Second pass" presupposes a defined FIRST pass.}}$$

### Required before Phase R is executable `[PROP]`

| # | artifact | content |
|---|---|---|
| **M-1** | **extraction manifest** | every in-scope file, with its exclusion reason if excluded |
| **M-2** | **declared scope** | in-scope · out-of-scope · **and the extracting lanes excluded**, per the rule adopted at `KOS-T-0001` and extended to implementation in stress pass 2 |
| **M-3** | **coverage measurement** | `extracted / in-scope`, re-measurable, never carried |
| **M-4** | **stopping criterion** | what makes the first pass *complete* — ⚠️ **and it may not be "all files"**: it could be *"no new candidate kind in N files"*, which is a different and possibly better criterion |
| **M-5** | **not-searched treatment** | ⚠️ **overlaps `P-13`** — `P-13` proposes a field *value* on a record; `M-5` is a **corpus-level coverage category**. **Related, not duplicate** — cross-referenced, not re-proposed |

**The distinction `M-3` must preserve:**

```
corpus 4 600 · scope 3 200 · excluded 1 400 · extracted 3 050 · not yet 150
        vs
"we extracted a lot of documents"
```

## 6. Relationship to `three_model_convergence`

| | |
|---|---|
| **Phase R MAY consume** | per-file records (1 224) · concept entries (27) · file landmarks (96) · manifests and progress registers · the three model reconstructions · `05_cross-model/` — **all as EVIDENCE** |
| **Phase R MUST NOT modify** | **any** 3MC artifact — `dimension-registry.md`, MD-017, MD-018, `machine-record-schema.md`, `protocol.md`, `revisions.md`, `classification-register.tsv`, `per-file/**` |
| **Phase R MUST NOT treat as authority** | 3MC's classifications, statuses or concept entries. **3MC is archaeology; it does not decide what the theory contains** |

### Is 3MC at 100 % a prerequisite?

$$\boxed{\textbf{NOT ESTABLISHED — and this proposal does not assume it.}}$$

| dependency | verdict |
|---|---|
| **provenance** 3MC → Phase R | ✅ **yes** — Phase R uses 3MC output as evidence |
| **scheduling** 3MC completes → Phase R starts | 🔴 **no such requirement is evidenced** |

⚠️ **Two consequences, both recorded rather than resolved.** (i) Phase R reading the corpus **while**
3MC is still reading it means both lanes read the same files for different purposes — **permitted, and
it must not be mistaken for duplication.** (ii) A Phase R conclusion resting on 3MC evidence must
**state 3MC's coverage at the time of reading**, because that coverage changes.

## 7. Schema v3 compatibility gate

$$\boxed{\begin{array}{c}\textbf{v3 must be demonstrated capable of representing the EIGHT existing records}\\ \textbf{— especially } \Pi \textbf{ and } Zero \textbf{ — BEFORE any broad extraction begins.}\end{array}}$$

| record | what v3 must be shown to represent |
|---|---|
| ⭐ **`Π`** | **7 readings · ≥5 candidates · 4 kinds · a malformed reading · four `Latest` values belonging to different candidates** |
| ⭐ **`Zero`** | **6 definitions of which `D-03` is really four · two `[RF]` readings beside four live · a `constant` under the same name · four rival implementations** |
| `Σ` | a **code-only** definition, implementing a **superseded** one, plus 143 symbolic occurrences |
| `ℐ` | **`never`** implementation, a **model-of** artifact, two scripts |
| `K` | 4 confirmed + 2 disposition-2 + 4 ambiguous, under one glyph family |
| `δ` · `≡_sem` · `Qualify` | operation/constant split · base-glyph siblings · one definition with two realizations |

⛔ **If v3 cannot represent `Π` and `Zero` as already recorded, it is not ready for 3 000 documents.**

## 8. Ordering — where Phase R belongs

```
                          NOW
                            │
                  P-06 analysis  ✅ done
                            │
                  P-06 decision dossier  ✅ done
                            │
                   ◆ HUMAN DECISION: P-06  (+ Q2, Q3; Q4 may defer)
                            │
                    Schema v3 DESIGN
                            │
              ┌─────────────▼─────────────┐
              │ v3 COMPATIBILITY GATE     │   ← §7 · the eight records, Π and Zero first
              └─────────────┬─────────────┘
                            │
              structural-diversity PILOT   ← §4 · 16 structural cases, not topics
                            │
        manifest · scope · coverage · stopping criterion · REVISIT QUEUE   ← §5, §2
                            │
                   broader extraction
                            │
              ┌─────────────┴─────────────┐
              ▼                           ▼
      revisit triggers accumulate   3MC continues INDEPENDENTLY
              │                    (provenance only — NOT a schedule gate)
              └─────────────┬─────────────┘
                            ▼
                     ◆ PHASE R  ← here
                            │
              theory candidate consolidation
                            │
              governance / adjudication   ← `OQ-1` is ONE decision here
                            │
                   canonical theory → formal spec → implementation
```

⭐ **The revisit QUEUE is built early** (with the manifest); **the PHASE runs late.** That is the
substance of the *design now, execute later* separation.

## 9. What Phase R cannot responsibly specify yet

| # | cannot specify | blocked by |
|---|---|---|
| 1 | **what a revisit trigger points AT** — a record? a candidate? a definition? an occurrence? | **`P-06`** — the queue must refer to the correct extraction unit |
| 2 | whether a trigger can target an **occurrence** | **`Q2`** |
| 3 | whether a glyph-form trigger is one trigger or several | **`Q3`** |
| 4 | whether `co-obligation` findings are triggerable | **`Q4`** |
| 5 | the **trigger record's own schema** | **Schema v3** |
| 6 | the **stopping criterion's threshold** | needs the manifest, which needs the scope, which needs v3's unit |
| 7 | whether `S-2` (blind sample) and `S-4` (open-after-writing) are **both** affordable | needs a measured extraction rate |

$$\boxed{\textbf{Phase R's DESIGN is specifiable now. Its SCHEMA is not — it depends on } P\text{-}06.}$$

## 10. Proposal register entries

**Seven new proposals registered** in `08-CONCEPTUAL-PROPOSAL-REGISTER.md` as **`P-18`…`P-24`**.
**None applied.**

| ID | proposal | overlap check |
|---|---|---|
| `P-18` | `revisit_required` trigger + queue | **new** |
| `P-19` | structural-diversity pilot criterion | **new** |
| `P-20` | anti-anchoring safeguards `S-1`…`S-4` | ⚠️ **distinct from `P-16`** — `P-16` governs *citation*, `P-20` governs *method* |
| `P-21` | extraction manifest + declared scope | **new** |
| `P-22` | coverage measurement + stopping criterion | ⚠️ **cross-references `P-13`** — `P-13` is a record field value, `P-22` a corpus coverage category. **Not duplicated** |
| `P-23` | provenance ≠ methodological ≠ scheduling dependency | **new** — governance-facing |
| `P-24` | v3 backward-compatibility gate against the eight records | **new** |

⛔ **No duplicate created.** `P-13` and `P-16` are cross-referenced, not superseded or re-proposed.

---

# **PHASE R PROPOSED — NOT SCHEDULED — NOT EXECUTED**

## Decisions required before Phase R can actually run

| # | decision | why it blocks |
|---|---|---|
| **1** | **`P-06`** — the extraction unit | the revisit queue must point at the right thing |
| **2** | **`Q2`** — occurrence: record or field? | determines whether occurrences are triggerable |
| **3** | **`Q3`** — base-vs-subscripted glyph identity | determines whether a glyph-form trigger is one or many |
| **4** | **`Q4`** — defer the obligation level, or accept one instance? | may remain deferred; **Phase R does not require it** |
| **5** | **Schema v3 designed** | the trigger record needs a schema |
| **6** | **v3 compatibility gate passed** on the eight records, `Π` and `Zero` first | otherwise broad extraction migrates twice |
| **7** | **`P-21`/`P-22`** — manifest, scope, coverage, stopping criterion | *"second pass"* is meaningless without a defined first pass |
| **8** | **`P-20`** — which anti-anchoring safeguards are adopted, and are `S-2`/`S-4` both affordable? | a pass with zero reversals would otherwise be unreadable as evidence |
| **9** | **`P-23`** — is 3MC completion a schedule gate? | ⚠️ **this proposal assumes NOT, and says so** |
| **10** | **`F-4`/`F-5` repair timing** — before or during Phase R? | both are **independent of `P-06`** and need repair under every alternative |

⛔ **Nothing above is decided here. This document is a design, not a plan, and not a programme change.**
