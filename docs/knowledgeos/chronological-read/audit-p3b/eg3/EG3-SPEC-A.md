# EG-3 SPEC-A: how hub runs read their SLICE-VIEW (design only)

| | |
|---|---|
| **Kind** | authority: generated. Subagent A3 (builder/researcher) design specification. **Changes no repository file.** Nothing here is applied |
| **Read set** | rev3 contract `prompts/20260925_1204_p3b-agent-contract-r2.md` (C), R7 addendum v2.7 `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` (A, sha256 `9523c712…` = `U.ADDENDUM_SHA256`, production-bound), `scripts/**`, execution package draft (EP), EG5 v2.8 package (EG5), B1, Freeze-2 proposal, EP-01 (design only), `hub_view_profile.json` (orchestrator counts) |
| **Not read** | production slices/views, ledgers, corpus, hold-out/H-19/quarantine, reviews, F-Series. The test fixtures that convert committed S4 batches (`tests/test_p3b_s5_verify.py`, `tests/r7_full_fixture.py`) were **not run**, because they read real material and the real hub list. The probe is purely synthetic (fake S-ids `S7xxx`, `S8xxx`, `S9xxx`; fake label) |
| **Probe** | `probes/eg3_probe_required_coverage.py`. It uses the **production** `slice_view` / `slice_view_violations` from `p3b_s5_r7_universe.py` |

Labels used below: **FACT** (with file:line), **PROPOSAL**, **HUMAN DECISION**, **PENDING** (a number that needs a counts-only production run).

---

## 1. What the contract requires of a hub run (Q1)

### 1.1 Hub semantics (FACT)

- **Stage 2 is not performed** for a hub, including Stage 2A/2B (C:54-60, C:657-668).
  - Every hit-bearing absence dimension resolves `ESCALATED`, with `{field: "absences.<dim>", reason: "LOAD", hub_record: <slice hub_record>}`.
  - Such a dimension is never FOUND and never GENUINELY-UNDEFINED-AFTER-CENSUS.
  - `stage2_dispositions` stays empty (C:278).
  - A GAP on such a dimension uses `NOT-FOUND-LOAD-ESCALATED`. That value "asserts nothing about the hits" (C:865).
  - The minimum evidence is the hub-list membership, "the dimension's stage-1 search record with hits", and the full hub record line (C:617).
- **A NEGATIVE-CENSUS dimension of a hub resolves as for any label**, including the census–reading disagreement rule (C:661-662).
  - Material that step-1 reading finds for a hit-bearing dimension goes into that dimension's escalation `detail` and a P1-GAP candidate, never FOUND (C:58-59, C:662-664).
- **No step-10 read of a hub's stage-1 hit file** unless OMQ-14 already requires it at step 1 (C:59-60).
- **Every other step of §9.8 is unchanged** (C:664-665, C:421-440).
- **H-19 representation:** a hub's label-level absence predicate is `UNDETERMINED`, so the label leaves that denominator (C:665-667).

### 1.2 Derived fact: every dimension of a hub is hit-bearing

This follows from the code; it is not stated in the contract.

- `negative_label` is set **per label, not per dimension**:
  - it is `NEGATIVE-CENSUS` iff the label's whole LABEL-HITS record has zero hits (`p3b_s3b_holdout_sample_plan.py:377-385`; S3 origin `p3b_s3_mechanical_prep.py:527-541`);
  - every DIMENSION record has `hits_ref = label`.
- A hub needs more than 4,081 predicted dispositions, where dispositions = distinct hit keys × dimensions (`p3b_s5_hub_list.py:2-5, 44-55`). So a hub has at least one hit.
- Hence **a hub has no NEGATIVE-CENSUS dimension**. Every absence resolution of a hub is mechanically `ESCALATED`/`LOAD`, with `negative_label` = null copied from the DIMENSION record.
- The verifier's `hit_bearing()` computes exactly this from the LABEL-HITS record (`p3b_s5_verify.py:653-665`).

### 1.3 What each slice key is for in a hub run

Builders: `p3b_s5_prepare.py:416-434` (keys) and C:33-36.

| key | contents (FACT) | hub-run use (FACT) | consulted by |
|---|---|---|---|
| `bundle` | rows_verbatim, sources_verbatim, reconciliation object | step 1: rows in historical order; row sources = the run's files (`p3b_s5_r6.py:73-77`) | agent, in full |
| `family_md` | family .md | step 1 (C:423) | agent, in full |
| `p3a_pairs` | frozen P3a pair records | step 2 semantic roll-up (C:426-428) | agent, in full; verifier S1 (`p3b_s5_r7_verify.py:207`) |
| `hub`, `hub_record` | flag and the hub-list line | the LOAD escalation copies `hub_record` verbatim (C:56); verifier: membership and equality (`p3b_s5_verify.py:371-376, 789-790`) | agent, in full (13 lines) |
| `source_tracks` | S-id → track tag, d23_track | D-23 / Track-B rules; `earliest_track` (`p3b_s5_verify.py:1133-1142`) | agent, in full |
| `stage2_files` | sorted S-ids of every hit file (`p3b_s5_prepare.py:426-427`) | for a hub: only the **negative** rule "no step-10 read of a hit file not read at step 1"; the verifier checks it (`p3b_s5_verify.py:835-842`); excluded from the required file set (`p3b_s5_r6.py:76`; `p3b_s5_r7_verify.py:196`) | agent (small; kept in full); verifier |
| `search_records[0]` = LABEL-HITS | `label`, `terms`, `ledger_hits{S:[[anchor,ti]…]}`, `raw_hits{S:[[off,ti]…]}`, `population_basis`, `record` | hub: the hit **entries** have no permitted consumer. There are no dispositions (C:278), no hit-file reads, and FOUND is impossible. `terms` is used only by the census–reading disagreement (C:640-643), which a hub cannot have (§1.2) | verifier (`hit_bearing`, G-04 `expect`: `p3b_s5_verify.py:653-680`; skipped for a hub at `:751`); hub-list builder |
| `search_records[1..k]` = DIMENSION | `dimension`, `negative_label`, `hits_ref`, `scope`, `files_searched`, `firewall_skipped`, `population_basis`, `identity_summary` | **required**: one absence per dimension; `negative_label` is copied (`p3b_s5_verify.py:755-774`); ESCALATED/LOAD per dimension (`:781-790`) | agent, in full; verifier |
| `source_meta` | 02-FILES fields (`META_FIELDS`, `p3b_s5_prepare.py:55-56`) for **every S-id occurring anywhere in the slice text** (`:431-434`). For a hub these are dominated by hit-only files | provenance and dates of the S-ids the agent **cites or reads**: G-08 (`p3b_s5_verify.py:488, 591-603`); BIRTH-UNRESOLVED (`:596-599`); "S-id without source_meta → provenance unknown; never look it up" (C:44-45) | agent, **by S-id**; verifier |
| scalars (`batch_id`, `tier`, `in_checklist`, `semantic_status_mechanical`, `quarantine`, shas, …) | — | schema copies; §9C gate; rows 3/4 | agent, in full |

### 1.4 Plan: runs and files per hub label (FACT)

- A hub's required set is its row sources only (`p3b_s5_r6.py:73-77`). The path is EMPTY, SINGLE (≤ 600,000 bytes; `p3b_s5_r5.py:37, 56-60`) or DECOMPOSED (`p3b_s5_r6.py:87-101`).
- Freeze-2 reports 1 hub-EMPTY and 1 hub-DECOMPOSED-MULTIROW (Freeze-2:57). **PENDING:** the exact SINGLE/DECOMPOSED split of the other 64, and the file counts per run.
- **Every run of a label gets the whole SLICE and SLICE-VIEW** in I(run): SINGLE, each UNIT and SYNTHESIS (`p3b_s5_r7_universe.py:637-639`). Decomposing a label therefore multiplies view reading. It never shrinks it.
- A hub-EMPTY label has no run. Its object is produced by the v2.8 assembly (EG5 §2.7) with no agent view read.

### 1.5 Verifier gates on hub objects (FACT, `p3b_s5_verify.py`)

- Slice-level: `hub` = list membership, `hub_record` = list line, `hub_batch` coherence (`:371-376, :391-392`).
- Record-level: record `hub` = slice `hub` (`:502-503`); no LOAD escalation on a non-hub (`:539-540`).
- Stage 2: `stage2_dispositions` empty (`:690-691`); G-04 disposition coverage skipped for a hub (`:751`).
- Absences: every dimension present; `negative_label` = the stage-1 record; hit-bearing → ESCALATED with a LOAD entry carrying `hub_record` (`:752-790`).
- Read discipline: no step-7 or Stage-2A log entries; no step-10 read of a hit file not whole at step 1; GAP statuses (`:834-863`); no P1-gap `found_via STAGE-2` (`:996-997`).
- R7 layer: hub `stage2_files` excluded from the binary-decision set (`p3b_s5_r7_verify.py:196`).
- **No gate checks what the agent displayed of its inputs.**
  - The witness records `read-input` events with `displayed_range = [min,max]` and `content_match` (`p3b_s5_r7_witness.py:82-92, 296-301`). It collects them (`:224, :242`), but **no code consumes them**: RI-1b is not implemented (grep: no consumer of `displayed_range`/`input_reads`).
  - The 25-line cap is stated in A:511 and in the rendered prompt only (`p3b_s5_r7_orchestrate.py:114-115`). No verifier enforces it.

---

## 2. Does any obligation require reading all of search_records or source_meta? (Q2)

**FACT: no contract or addendum text requires it.**

- C requires the agent to "consume the stage-1 search record" (C:431). For a hub, consumption is the dimension list, `negative_label` and the hit-bearing fact, all rule-determined (§1.2).
- No obligation ranges over the hit entries:
  - there are no dispositions (C:278);
  - there is no census claim (no NEGATIVE-CENSUS dimension exists on a hub);
  - the only "absence" statements are ESCALATED/LOAD, which "assert nothing about the hits" (C:865).
- A:511 says agents read the view "paging with at most 25 lines per Read". It does **not** say "whole".
- The whole-display obligation exists **only** in two places:
  - the orchestrator's rendered prompt: "Page every input with offset/limit until the whole file has been displayed" (`p3b_s5_r7_orchestrate.py:114`). Its own docstring says it "restates no contract rule" (`:105-106`);
  - EP's RI-1b recommendation (EP "Also recommended"; report, not fail).
- `source_meta` is needed **per cited or read S-id** (C:44-45; G-08). A hit-only file may not be read by a hub run (plan-bound reader; C:59-60), so it can be evidence for nothing.

**The exact subset a hub run consumes (FACT, derived):**
- every key except `search_records` and `source_meta`;
- `search_records[i]` for i ≥ 1 (the DIMENSION records);
- `search_records[0]` without its `ledger_hits`/`raw_hits` subtrees (`label`, `population_basis`, `record`, `terms`);
- `source_meta[S]` for the S-ids the run can cite.

§4 defines the last part mechanically as T(L).

---

## 3. Options (Q3)

Cost basis (FACT, counts file):
- reads at 25 lines: all keys median 318 / p90 1,735 / max 5,950;
- without `search_records` and `source_meta`: median 29 / p90 70 / max 108.

Character volume (probe, synthetic, production `slice_view`; 8-character Read line prefix included):
- ≈ **59 characters per displayed line** for hub-dominated views;
- so all keys ≈ median 0.47 M / p90 2.6 M / **max 8.8 M characters** per run.

| Option | Reads per label (25-line pages) | Contract / verifier / plan impact | Scientific impact (HUB stratum) | Verdict |
|---|---|---|---|---|
| **(a) Normative HUB-REQUIRED rule + a line map in the prompt** (§5) | K ≈ 29 / 70 / 108, plus M ≈ ⌈22·dims/25⌉ + 1–2 + about one per S-id in T(L). **Estimate ≈ 40 / ≈ 90 / ≈ 160**; PENDING exact (the probe gives 11, 16 and 88 reads on synthetic small/median/large) | Addendum text (§5.9a) → **contract-material** → v2.8 or later rebind. Rendered-prompt change (orchestrator code, tests first). Witness unchanged. Verifier unchanged if report-only. Slices, views, plans, I(run), Freeze 1/2 unchanged | none: the omitted lines have no consumer in a hub run (§2); θ_D's re-analysis uses the same rule | **Recommended** |
| (b) Derived hash-bound per-key sub-view or index as a new I(run) category | same as (a) | the closed category enum changes (A:462-470; `CATEGORIES`, `p3b_s5_r7_universe.py:46`); new Universe check and tests like T119-T121; **reopens the audited verifier** (G-LOG-0092); new prepare artifacts (none committed yet: EG5 §9). A sub-view is by design **not** lossless with respect to the slice, so it needs its own definition | none | Heavier than (a) for the same reads. The line map needs no hash-bound file, because the verifier recomputes the required set from the view |
| (c) Plan-level decomposition of hub labels | **no reduction**: every unit and the synthesis get the whole view (`universe.py:637-639`), so reads rise to (units + 1) × view | changes frozen plans (`plan_derive_v7`), MULTIROW/DECOMPOSED membership and possibly Freeze-2 strata (A:591-592) → **HUMAN GATE**, Freeze-2 impact | risks stratum membership | **Rejected**: it splits corpus files, not slice keys |
| (d) Accept full reading | up to 5,950 Reads; ≈ 8.8 M characters at max, ≈ 2.6 M at p90 | none | none, but completion becomes infeasible for the tail | **Infeasible for the tail**. At about 3–4 characters per token the max is ≈ 2.2–2.9 M tokens, above a 1 M context before the ≤ 600,000-byte corpus budget (`r5.py:37`) and the ≈ 174 k characters of contract and addendum. p90 is at or above the limit. Auto-compaction would destroy what the agent had seen, and 5,950 tool calls also cost time. Feasible only near the median |
| (e1) Raise the per-Read line cap for hub views | calls fall, characters do not (still ≈ 8.8 M) | A:511 text | none | does not fix the binding constraint (tokens) |
| (e2) Strip hit entries from hub slices at prepare | ≈ (a) | re-prepare of the slices, new slice hashes, manifest, Freeze-2 bindings → human gate | none | heavier; no gain over (a) |
| (e3) "Consult as needed", no required set | ≈ (a) | text only | none | **unverifiable**: completeness cannot be reported. Rejected |

---

## 4. Coverage and witness (Q4)

**FACT:**
- Each view line starts with its JSON path (A:503-505; `slice_view`, `universe.py:514-539`).
- Each `read-input` event carries `displayed_range = [min,max]` of the line numbers shown, and `content_match` (every displayed line equals the frozen file's line; `witness.py:82-92`).
- Both are hash-bound: view = `view(slice)` (A:513), and the event is in the committed witness (A:441).

**PROPOSAL: the required set is a pure function of frozen bytes.**

R(run) = the header line, plus every view line whose path p satisfies one of:
1. `p[0]` ∉ {`search_records`, `source_meta`};
2. `p[0] = "search_records"` and `p[1] ≥ 1`;
3. `p[0:2] = ["search_records", 0]` and `p[2]` ∉ {`ledger_hits`, `raw_hits`};
4. `p[0] = "source_meta"` and `p[1]` ∈ T(L).

T(L) = the S-ids (`\bS\d{4}\b`) occurring in the canonical slice with the keys `search_records`, `stage2_files` and `source_meta` removed. That is the same regex `prepare` uses (`p3b_s5_prepare.py:431-433`), applied to the citable part.

- **Coverage** = ⋃ `displayed_range` over the run's `read-input` events on its SLICE-VIEW path with `content_match = true`.
- **Covered** ⇔ R(run) ⊆ Coverage.
- The verifier or reporter recomputes R from the view bytes alone. It needs no agent input and no prompt (the prompt's map is a convenience).

**Probe (synthetic):** full display → COVERED; targeted display of exactly the map ranges → COVERED. Three omissions are each NOT-COVERED with the missing lines named:
- the DIMENSION records;
- all of `search_records`;
- the hits only.

The same holds at 3 k, 6.6 k and 136 k view lines. R forms 4 merged ranges in the probe; in production there are about 4 + |T(L)| ranges.

**Can an agent claim a scoped absence without displaying the search-record lines? FACT: yes, undetected today.**
- The gates compare the object's absences with the **slice** (`verify.py:755-790`), not with what was displayed. No consumer of `displayed_range` exists.
- For a hub the absence output is rule-determined, so a copied answer is *correct*. The gap is completeness evidence (the agent "saw its input"), not correctness.
- Under the proposal, a skipped DIMENSION record, the header of `search_records[0]`, or a T(L) `source_meta` entry is **detectable** as NOT-COVERED on the named path.

**Caveats (FACT):**
- `displayed_range` is a [min,max] pair. It is correct because a harness Read displays a contiguous run of lines, and `content_match` verifies every displayed line.
- A Read of the raw one-line SLICE is a different path, so it contributes no view coverage.

---

## 5. Recommendation (Q5)

**Option (a) with the line map in the prompt, report-only.**
- Reads per hub run ≈ 40 median / ≈ 160 max (PENDING exact), against 318 / 5,950.
- About ≤ 0.15 M characters displayed, against ≤ 8.8 M.

### 5.1 Draft normative text (PROPOSAL; addendum §5.9a, "HUB-REQUIRED view lines")

> **§5.9a Required view lines for hub runs (v2.8(f)).** For a run whose slice has `"hub": true` (SINGLE, UNIT or SYNTHESIS), the agent **must display every line of R(run)** and **may** display any other line of its SLICE-VIEW. R(run) is the header line and every view line whose path p satisfies one of:
> (i) p[0] is neither `search_records` nor `source_meta`;
> (ii) p[0] = `search_records` and p[1] ≥ 1;
> (iii) p[0..1] = [`search_records`, 0] and p[2] is neither `ledger_hits` nor `raw_hits`;
> (iv) p[0] = `source_meta` and p[1] ∈ T(L), where T(L) is the set of S-ids occurring in the canonical slice with `search_records`, `stage2_files` and `source_meta` removed.
>
> R(run) is a deterministic function of the frozen SLICE-VIEW. The dispatch prompt states it as line ranges; the ranges bind nobody, since R(run) is recomputed from the view. Lines outside R(run) are discovery metadata: stage 2 is not performed for a hub (§11.4), so no output may rest on them. A `source_meta` entry outside T(L) is never needed; if one is cited, its provenance rule (A.2) applies unchanged. **Coverage** is the union of the displayed ranges of the run's `content_match = true` reads of its SLICE-VIEW. The orchestrator **reports** per run whether R(run) ⊆ coverage (RI-1b), naming each uncovered path. This section changes no evidence, reading, absence, verdict or statistics rule. For a non-hub run, R(run) is the whole view.

**Prompt change (code, not contract).** For a hub run, `render_prompt` (`orchestrate.py:114-115`) replaces "until the whole file has been displayed" for the SLICE-VIEW with:
- `SLICE-VIEW REQUIRED LINES (§5.9a): a–b, c–d, …`;
- a statement that other view lines are optional.

The ≤ 25-lines-per-Read cap stays.

### 5.2 RED tests (tests first; synthetic fixtures only)

| # | Test | Expected |
|---|---|---|
| E1 | R(view, slice) recomputed twice, and from the view alone | identical; no agent input used |
| E2 | synthetic hub view: R excludes every `search_records[0].raw_hits/ledger_hits` leaf and every `source_meta[S]` with S ∉ T(L); includes all other keys, all DIMENSION lines, `search_records[0].{label,population_basis,record,terms}` (all chunks of a chunked string) and `source_meta[T]` | exact set equality |
| E3 | T(L): an S-id only in `raw_hits` or `stage2_files` ∉ T; an S-id only in `family_md` or `p3a_pairs` ∈ T; an S-id in `bundle` rows ∈ T | as stated |
| E4 | non-hub view | R = all lines (behaviour unchanged) |
| E5 | coverage: full / exactly the map ranges | COVERED |
| E6 | coverage: a DIMENSION record omitted; `search_records[0].terms` omitted; one `source_meta[T]` entry omitted; hits only | NOT-COVERED, naming the uncovered path |
| E7 | a `read-input` with `content_match = false`, or a Read of the raw SLICE path | contributes no view coverage |
| E8 | rendered hub prompt: the ranges block = merged ranges of R; the prompt passes `check_prompt`; no source text; non-hub prompt unchanged byte for byte | as stated |
| E9 | hub DECOMPOSED: every UNIT and SYNTHESIS run gets the same R (role-independent) | as stated |
| E10 | hub-EMPTY: no run, no R; the assembly is unaffected | as stated |
| E11 | report-only: NOT-COVERED does not change `verify_r7` predicates U/W/E/R | verdict unchanged (a positive control for "report, not fail") |
| E12 | regression: T119–T121 pass; slice and view bytes, plans and I(run) manifests unchanged (`prepare --check` IDENTICAL) | pass |
| E13 | rebind: v2.8 with §5.9a; `FreshTargetRebind` covers the new sha | pass |

### 5.3 Materiality and v2.8 joining

**Materiality: contract-material.**
- It changes what a hub agent is obliged to display, and it removes the prompt-level "whole file" obligation for hub views. So it needs addendum text, hence a rebind.
- It is **not verdict-material** and **not science-material**:
  - no gate, predicate, evidence, absence, statistic, plan, slice, view, stratum or sample changes;
  - θ_D stays a same-protocol re-analysis applying the same §5.9a.
  - For hub absences, θ_D is a rule-determinism check (all hit-bearing, ESCALATED/LOAD, §1.2). This parallels EG5 §4 for EMPTY. Hub disagreement is carried by steps 1–6 and 8–10, whose inputs are all in R.

**Separability.** It can join v2.8 as an **independent part (f)** in one self-contained section.
- (f) is needed only before the 14 **hub batches**. The canary (OB0012, OB0114) has no hub (EP §3), and EG5 §13 already scopes hub execution out.
- So (f) must not gate (a)–(e). If its review has not closed when (a)–(e) are ready, v2.8 ships without it and (f) takes a later rebind (v2.9) before any hub batch.

---

## 6. Human decisions

| Id | Decision | Recommendation |
|---|---|---|
| **HD-1** | Adopt §5.9a (option (a) plus the prompt line map) as v2.8 part (f), or defer it to a later rebind | adopt if reviewed in time; **never delay (a)–(e)** |
| **HD-2** | Coverage vs R(run): **report-only** (RI-1b; no verifier change), or a gate (a new failure such as `R7-W INPUT-COVERAGE`, which reopens the audited verifier and needs an independent review) | report-only now; a gate is a separate later decision |
| **HD-3** | T(L) scope: the citable-slice S-ids (proposed), or the narrower "run files ∪ `source_tracks`" | proposed (safer for MOVED and family-md citations; small) |
| **HD-4** | Role-independent R for the hub-DECOMPOSED label (proposed), or per-role subsets | role-independent |
| **HD-5** | Authorize a counts-only production computation of R(run) reads per hub run (median/p90/max) and the hub path split (§1.4) | yes, before HD-1 |
| (not needed) | Options (c) and (e2) would need a frozen-plan or slice human gate | not recommended |

## 7. Observations outside scope (classified, not acted on)

- **Engineering.** The rendered prompt carries a normative sentence ("until the whole file has been displayed", `orchestrate.py:114`) that no contract text states. The §5.9a proposal resolves this for hubs. For non-hubs, the same whole-display rule has no contract anchor either (RI-1b is report-only by EP).
- **Engineering.** The 600,000-byte unit budget (`r5.py:37`) counts corpus bytes only, while view and contract characters share the same context. This matters for large non-hub views too. Not assessed here.

**Traceability:** C:33-36, 44-45, 50-60, 65-66, 278, 431-432, 617, 628-668, 865 · A:115, 437, 462-495, 497-513, 530, 591-592 · `p3b_s5_verify.py` (see §1.5) · `p3b_s5_r6.py:73-101` · `p3b_s5_r7_universe.py:46, 514-539, 637-639` · `p3b_s5_r7_orchestrate.py:104-130` · `p3b_s5_r7_witness.py:82-92, 224, 296-301` · `p3b_s5_prepare.py:55-56, 416-434` · `p3b_s3b_holdout_sample_plan.py:377-385` · `p3b_s5_hub_list.py:2-5, 44-55` · EP §0, §3 · EG5 §2.7, §4, §13 · Freeze-2:50, 57 · EP-01:33.
