# EG3-SPEC-A v2: delta to EG3-SPEC-A.md (revision round 1)

| | |
|---|---|
| **Kind** | authority: generated. Subagent A3. A delta: every section of `EG3-SPEC-A.md` not named here stands unchanged. No repository file changed |
| **Responds to** | `EG3-REVIEW-B.md` (SPEC-ACCEPTABLE; one MATERIAL finding: the probe did not implement rule 3) and the orchestrator's decision to **adopt rule 3 as drafted** (exclude every hit-map leaf; `hub_record` already carries the hit counts) |
| **Inputs added** | `hub_required_counts.json` (orchestrator, counts-only) |

## D1. The probe now implements rule 3 exactly (closes REVIEW-B §3)

### Changes to `probes/eg3_probe_required_coverage.py`

1. **`required()`: the first-leaf branch (`seen_first`) is removed.** Every view line under `["search_records",0,"ledger_hits",…]` or `["search_records",0,"raw_hits",…]` is excluded. That includes the E-kind line of an empty map.
2. **T(L) is computed as spec §4 defines it.** It is the regex `\bS\d{4}\b` over the canonical slice with `search_records`, `stage2_files` and `source_meta` removed. The probe asserts that T(L) equals the synthetic citable S-ids. (v1 passed the row sources directly.)
3. **E2 is added verbatim from §5.2.** For each synthetic view it checks exact set equality between R and an independent expectation, and that R contains:
   - no hit-map leaf;
   - no `source_meta[S]` with S ∉ T(L);
   - `search_records[0].{label, population_basis, record, terms}`.
4. **An E2 negative control is added.** The superseded first-leaf variant must fail E2.

### Re-run output (synthetic)

| synthetic view | view lines | FULL reads | R lines (v1 → v2) | R reads | R characters | E2 |
|---|---:|---:|---:|---:|---:|---|
| small | 3,085 | 124 | 211 → **209** | 11 | 15,475 | PASS |
| median | 6,619 | 265 | 355 → **353** | 16 | 22,419 | PASS |
| large | 135,773 | 5,431 | 2,159 → **2,157** | 88 | 118,058 | PASS |

- **Negative control:** the first-leaf variant is REJECTED. The failure message names the path `["search_records",0,"raw_hits",…]`, so E2 discriminates between the two rules.
- **Coverage scenarios are unchanged in outcome.** Full and targeted display are COVERED. Skipping the DIMENSION records, skipping all of `search_records`, and displaying hits only are each NOT-COVERED. The missing-line counts fall by 0–2 per scenario because the two hit leaves are no longer required.

**Corrected in spec §3 and §4:** the probe figures are R = 209 / 353 / 2,157 lines, still 11 / 16 / 88 reads, in 4 merged ranges. The v1 text cited the same read counts, which the change of rule does not affect.

## D2. Production figures: exact orchestrator counts replace my estimates

These are **orchestrator counts-only computations** (`hub_required_counts.json`, 2026-09-29, n = 66 hub labels). I did not re-derive them, because production views are outside my read set.

| Reads per hub label at 25 lines | median | p90 | max | sum |
|---|---:|---:|---:|---:|
| all keys (today's whole-view display) | 318 | 1,735 | 5,950 | 49,934 |
| **R(run), rule 3 as drafted, citable T(L) (the recommended option)** | **41** | **88** | **127** | **3,349** |
| rule 3, narrow T(L) (run files ∪ `source_tracks`) | 41 | 88 | 123 | 3,252 |
| first-leaf variant (superseded; citable / narrow) | 41 / 41 | 88 / 88 | 127 / 123 | 3,354 / 3,257 |

**Replaces:**
- spec §3, row (a): "Estimate ≈ 40 / ≈ 90 / ≈ 160; PENDING exact";
- spec §5 and the reply: "≈ 40 median / ≈ 160 max".

**Updated statements:**
- **Reduction:** 49,934 → 3,349 reads in total (−93.3%). The maximum falls from 5,950 to 127.
- **Characters displayed:** a derived estimate (127 × 25 lines × ≈ 59 characters) gives ≈ 0.19 M at the max. This replaces "≤ 0.15 M"; it is still an estimate, not a count.
- **HD-5 is partly discharged:** the R(run) counts now exist. The hub path split (SINGLE/DECOMPOSED counts and files per run, spec §1.4) remains PENDING.

## D3. Reviewer sharpenings

### HD-3 (T(L) scope), replacing its rationale

- R(run) is a **minimum display set**: the agent *may* display any other view line (§5.1). So neither choice of T(L) can create a completeness or correctness risk.
- The choice affects only **false NOT-COVERED noise** in the RI-1b report:
  - a narrow T(L) cannot flag a `source_meta` entry that the agent needed but skipped, for example for a MOVED or family-md citation;
  - a citable T(L) requires those entries.
- Citable T(L) costs 97 reads in total (3,349 vs 3,252) and 4 at the max (127 vs 123).
- **The recommendation (citable) is unchanged; its basis is now report fidelity**, not safety.
- *Precision:* the report can only name lines inside R as NOT-COVERED. A narrow T(L) therefore produces false "COVERED" results by omission. A citable T(L) produces NOT-COVERED only for lines the run could legitimately need.

### HD-2 (report-only), with its load-bearing reason

Report-only coverage is safe because correctness is enforced by the verifier's own **exact-equality checks against the frozen slice**, independent of what the agent displayed.

| Check | Location (`p3b_s5_verify.py`) |
|---|---|
| slice `hub_record` equals the frozen hub-list line (list body sha-checked, `p3b_s5_common.py:224-229`) | `:375` |
| no dimension of the slice left unresolved (`set(dims) - set(ab)`) | `:756-757` |
| no absence for a dimension without a search record (`set(ab) - set(dims)`) | `:758-759` |
| each `absences.<dim>.negative_label` equals the stage-1 DIMENSION record's | `:777` |
| every hit-bearing dimension is ESCALATED with a LOAD escalation | `:781-788` |
| that LOAD escalation's `hub_record` equals the slice's `hub_record` (`hub_rec_eq`, `:1123-1130`) | `:789-790` |

*Precision:* `hub_rec_eq` is **exact structural equality** (a dict `==`, or a JSON string parsed and then compared with `==`), not byte equality. It is still exact over every key and value.

**Consequence:** an agent that never displayed its DIMENSION records or `hub_record` would have to reproduce the exact dimension-name set and the full hub-record object to pass. A wrong or incomplete reproduction fails the batch. Coverage reporting adds completeness *evidence* only. This is why HD-2's recommendation (report-only now; a gate is a separate later decision) is safe.

## D4. Status

- **REVIEW-B MATERIAL finding: closed.** The rule text, E2 and the probe now agree, and the probe evidence shows E2 passes and discriminates.
- Minor observations 1 and 2 in REVIEW-B §6 are closed by D2 and D3.
- **Unchanged:** the recommendation, the §5.1 normative text (rule 3 as drafted), the RED list E1–E13, materiality, and separability as v2.8 part (f).
- **Open human decisions:** HD-1 to HD-4; HD-5 only for the hub path split.
