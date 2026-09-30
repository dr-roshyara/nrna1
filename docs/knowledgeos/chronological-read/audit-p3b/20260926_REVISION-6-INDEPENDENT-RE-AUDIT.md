# Revision 6: narrow independent adversarial re-audit (G-LOG-0084)

| | |
|---|---|
| **Kind** | Independent adversarial audit evidence. ⚠ authority: generated. It recommends; it decides nothing |
| **Authority** | G-LOG-0084: ONE narrow independent re-audit of Revision 6 as committed in `bc6fb01bb` |
| **Object** | `scripts/p3b_s5_r6.py`, `p3b_s5_r6_verify.py`, the revision ≥ 6 gate in `p3b_s5_verify.verify`, `p3b_read_source.py` (grammar, `reader_plan_check`), `p3b_s5_common.py`, and the R6 addendum. Their sha256 values equal the implementation record §7. `git diff bc6fb01bb HEAD` on these paths is empty (HEAD moved to `c0eb45558` through unrelated concurrent commits) |
| **Method** | Own synthetic fixture **OB9011**, built from S4 **PB03** material (the implementer used PB05 / OB9005). It has 3 SINGLE and 2 DECOMPOSED labels (3 + 2 units) and one EMPTY variant, with own synthetic bytes, own sizes, own page/ack derivation, own plan derivation, own claim enumeration and own expected outcomes. Every attack goes through the **production** `p3b_s5_verify.verify` with a fake resolver and synthetic bytes. The R6 test suite was run once, as regression evidence only |
| **Artifacts** | `audit-p3b/r6-audit/` (scripts, `.out` files; hashes in §8) |

**Aliases:** labels are written as L1–L5 (OB9011 manifest positions), never by name.

---

## 1. Verdict: **NEEDS-REVISION**

**Can an independently constructed invalid Revision-6 batch still obtain PASS through the actual production verification path? YES.**

Four smallest reproductions. Each is one mutation of an otherwise valid batch, and each gives `result: PASS`, gate `R6: PASS`, 0 failures:

1. **P10c:** insert a timeline point `{source_id: null, change_vs_previous: "FIRST", …}` ahead of the real first appearance. The object then asserts an evidence-free first appearance.
2. **E11:** L3's synthesis agent reads source S0794 after synthesis dispatch under run id `OB9011-R2`.
   - The reader grammar accepts that run id for an OB batch without any plan check.
   - The revision-6 verifier never opens that directory.
3. **R-NC-tsum:** a file whose record is NOT-CONSUMED (zero pages logged) is named in `timeline_summary.contradicted_by`. That is a contradiction claim which rev3 feeds into D4.
4. **EM13:** an EMPTY label names another label's file in `timeline_summary.contradicted_by`.

**What Revision 6 did close:**
- Every attack *pattern* of the Revision-5 audit now fails on fresh fixtures, as do most new variants: 162 of 206 full-path cases behave correctly, and the rest are boundaries or the defects below.
- The PASS ⇒ P∧E∧R∧T∧L∧S invariant holds for every single, pairwise, triple and six-fold violation I built (42/42 NOT-PASS).

**What stays open:** the enumerated R6 claim set does not cover every source-requiring claim, and the enumerated run namespace does not cover every reader-reachable run. The statistical functions also produce apparently valid estimates from invalid frames.

---

## 2. Attack matrix

**Legend:**
- **OK:** the verifier behaved correctly.
- **FALSE-PASS:** an invalid batch passed. Each carries a severity.
- **BND:** the verifier cannot decide the case from its inputs. It is recorded, not scored.

"Key failure line" is the first R6 line (labels aliased). The complete output is in `attacks.out`, `empty.out`, `stats.out` and `probes.out`.

### 2.1 R5-01 provenance (SOURCE → READING RECORD → FACT → CLAIM)

| ID | Fresh attack | Exp | Observed / key failure line | Result | Interpretation |
|---|---|---|---|---|---|
| P1 | fact removed, claim map kept | FAIL | FAIL · `R6 P L3: claim timeline:S0794 has no valid evidence path` | OK | referential link enforced |
| P2 | wrong fact id | FAIL | FAIL · same | OK | |
| P3a | ref to a valid fact in another **label's** run | FAIL | FAIL · same | OK | reading runs are the label's own |
| P3b | fact copied into another **unit** of the same label | FAIL | FAIL · `fact 'moved': quote not found…` | OK | |
| P3c / P3d | fact of another source; ref naming the right source but a fact id that exists only under another source | FAIL | FAIL · `claim … has no valid evidence path` | OK | key is (run, source, fact) |
| P4a | CHANGES-DEFINITION and births on a READ-PARTIAL record | FAIL | FAIL · `claim timeline:S0794 …` (+ R rule) | OK | |
| P5 | fact quote not in bytes | FAIL | FAIL · `quote not found in the authoritative source bytes` | OK | |
| P6a | quote = 40 bytes of unrelated filler text from the file | BND | PASS | BND | **referential, not evidential**: the machine cannot establish entailment |
| P6b | one-character quote `"e"` | BND | PASS | BND | no minimum length or specificity rule. Hardening is possible; entailment is not |
| P7 | wrong-kind fact (BIRTH for TIMELINE) | FAIL | FAIL | OK | |
| P8 | duplicate fact id in one record | FAIL | FAIL · `missing or duplicate fact_id` | OK | |
| P9a | record files changed after validation, hashes not updated | FAIL | FAIL · `R6 T L3: unit record files changed after validation` | OK | |
| P9b | records edited after validation **and** all declared hashes rewritten | BND | PASS | BND | trust boundary (§5) |
| **P10** | extra EXTENDS point, `source_id: null` | FAIL | **PASS** (0 failures) | **FALSE-PASS · MATERIAL** | `enumerate_claims` only enumerates points with a truthy `source_id` (p3b_s5_r6.py l.180–184); the historical SCHEMA check tests the key set only |
| **P10b** | extra NOT-COMPARABLE point, `source_id: ""` | FAIL | **PASS** | **FALSE-PASS · MATERIAL** | same cause |
| **P10c** | source-less **FIRST** point inserted before the real first appearance | FAIL | **PASS** | **FALSE-PASS · MATERIAL** | an evidence-free chronology claim |
| P11 | non-object timeline entry `"S1890 EXTENDS the definition (1999)"` | FAIL | **PASS** | **FALSE-PASS · MINOR** | non-dict entries are skipped by R6 and by the historical checks alike. Malformed, not a machine-readable point (caught for EMPTY by EM15) |
| P12 | FOUND absence: `supplied_by.quote` fabricated; the fact is valid | FAIL | **PASS** | **FALSE-PASS · MINOR** | object-level quotes are outside the verifier. The production quote checker catches them (probes D: 2 misses reported) but runs outside the PASS path, and the R6 runbook step 14 names only the verifier |
| P13 | R2 edge quote fabricated; the fact is valid | FAIL | **PASS** | **FALSE-PASS · MINOR** | as P12. The R5 addendum §11 assigns this quote to "the production quote checker" |
| P14 | unit fact `change_candidate: RESTATES`, claim CHANGES-DEFINITION | BND | PASS | BND | no timeline field is compared ("right kind and fields"). Defensible, since the class is relative to the previous point, but the contract never says so |
| P15 / P15b | `timeline_summary.contradicted_by` / `superseded_by_sources` naming whole-read files with no fact | BND | PASS | BND | claim-bearing fields outside the enumeration; see M3 for the unread-file form |

### 2.2 R5-02 temporal

| ID | Fresh attack | Exp | Observed | Result |
|---|---|---|---|---|
| T1 | synthesis-dispatched `13:00+05:00` (= 08:00Z) vs validation 09:00Z | FAIL | FAIL · `synthesis not strictly after unit validation (R19)` | OK |
| T1c / T4 | valid non-UTC offsets (one stage; every time in the batch) | PASS | PASS | OK |
| T2 / T2c | fractional seconds reversed / valid | FAIL / PASS | FAIL / PASS | OK |
| T3.0–7 | space separator, no seconds, no zone, hour 24, `+0200`, Nov 31, `later`, epoch integer | FAIL | FAIL · `not strict RFC 3339` | OK ×8 |
| T3.nl | trailing newline | FAIL | FAIL | OK |
| T3.z | lowercase `t`/`z` (valid under RFC 3339 §5.6 NOTE) | BND | FAIL | conservative (COSMETIC) |
| T5 / T5c | unit read 1 s after validation / exactly at validation | FAIL / PASS | FAIL · `unit reads logged after units-validated` / PASS | OK |
| T7 / T7b | synthesis before / equal to validation | FAIL | FAIL | OK |
| T8 | produced before dispatch | FAIL | FAIL | OK |
| T9 | SINGLE: RUN-MANIFEST produced before the reader-logged reads | FAIL | FAIL · `reads logged after the object was produced` | OK |
| **T10** | two `units-validated` records for one label: 09:45Z (after dispatch), then a forged 09:00Z | FAIL | **PASS** | **FALSE-PASS · MINOR**: `stages[label][stage] = p` means the last record wins; no duplicate check and no precedence rule. T10b (the other order) FAILs |
| T11a–d | read-log entry without `utc`; stages missing; no `produced_utc`; SINGLE without RUN-MANIFEST | FAIL | FAIL | OK ×4 |
| T12 | identical read timestamps | PASS | PASS | OK |

### 2.3 R5-03 execution / run set

| ID | Fresh attack | Exp | Observed | Result |
|---|---|---|---|---|
| E1 | undeclared `OB9011-R6-L03U09` with reads | FAIL | FAIL · `not a planned run` | OK |
| E2 | L4's agent reads under L3's unit run | FAIL | FAIL · `working_label ≠ the run's plan owner` | OK |
| E3 / E6 | entry names another batch / a foreign run | FAIL | FAIL · `names another batch or run` | OK |
| E5 | `-R5-` run dir | FAIL | FAIL · `revision-5 runs are withdrawn` | OK |
| E7 | synthesis reads under a unit run id after dispatch (reader time) | FAIL | FAIL · `unit reads logged after units-validated` | OK. Detection rests on the log's `utc`; see TB2 |
| E8 | reads under the synthesis run | FAIL | FAIL · `records-only synthesis performed a read` | OK |
| E9 | per-run log missing | FAIL | FAIL | OK |
| E12 | refused entry | FAIL | FAIL · `stop condition 16` | OK |
| E13 | unit reads another unit's file | FAIL | FAIL · `not permitted for this run` | OK |
| E14 / E14b | character-mode page / no page | FAIL | FAIL | OK |
| **E11 / E11b / E11c** | L3's synthesis agent reads S0794 after dispatch under `OB9011-R2`, `-R2.1`, `-R2S` | FAIL | **PASS** (no failure, no alert) | **FALSE-PASS · MATERIAL** |
| E4 | `OB9011-R7-L03U01` dir with reads | FAIL | **PASS** | **FALSE-PASS · COSMETIC**: ignored, but the reader grammar cannot create it |
| E10 | log in the assembled `OB9011-R6/` dir | BND | PASS | not reader-writable. Ignored in rev6 (the historical `rlog` is discarded) |
| E15 | read of an OB9011 file under `OB9012-R6-L01` | BND | PASS | outside this batch's scope. The run identity is a CLI argument (§5) |

**Detail for E11/E11b/E11c:**
- **Reader side** (probes C): the paged and plain grammars both accept `OB####-R2`, `-R2.n` and `-R2S` for an OB batch. `reader_plan_check` applies only to the R6 grammar.
- **Verifier side:** `verify_r6` inspects only `-R5` and `-R6-*` directories. In rev6 mode, `p3b_s5_verify` sets `foreign = []` and never reads other logs.
- **Why it is material:** the addendum's claim "the reader refuses unplanned run/file pairs" is false for this namespace. Records-only synthesis and unit scoping can be violated without the verifier seeing it.
- **Mitigations outside the PASS path:** the transcript scan (`scan_level_v6` → `READER-FOREIGN-RUN-OR-BATCH`), and the resolver, which still refuses hold-out bytes.
- **Nuance:** legitimate historical `OB####-R2*` directories exist for some batches, so a repair must be plan- or time-based.

### 2.4 R5-04 label ownership

| ID | Fresh attack | Exp | Observed | Result |
|---|---|---|---|---|
| O1 | run(L1) declares working_label L2 and vice versa | FAIL | FAIL · `working_label ≠ the run's plan owner` | OK |
| O2 | plan: one run owned by two labels | FAIL | FAIL · `frozen plan ≠ independent derivation (runs)` | OK |
| O3 / O4 | plan: owner missing / foreign owner label | FAIL | FAIL | OK |
| O5 | manifest label order permuted, plan and dirs from the old order | FAIL | FAIL · `(index, runs)` | OK |
| O6 | forged `--label` on two entries of a valid run | FAIL | FAIL | OK |
| O7 | objects of L1/L2 exchanged (valid runs, malicious label metadata) | FAIL | FAIL · `R6 S2 … not a row source` | OK |

The reader-side `reader_plan_check` refuses a forged `--label`, an unplanned run, a synthesis read and an unpermitted file (probes C). Ownership is plan-derived (`L##` = manifest position). **Closed under the log-integrity assumption (§5).**

### 2.5 R5-05 plan integrity

My independent `own_plan` (sizes = byte lengths, 600,000 budget, own packing key and next-fit/FFD partition) is **byte-identical** to `plan_derive` on OB9011.

| ID | Fresh attack | Exp | Observed | Result |
|---|---|---|---|---|
| L1 | declared size altered (hash updated) | FAIL | FAIL · `(sizes)` | OK |
| L2 | resolver content grows past the budget after planning | FAIL | FAIL · `(packing_order, path, runs, sizes, units)` | OK |
| L2b | content altered, same size (plan still equal) | FAIL | FAIL · `logged page(s) fail hash` | OK |
| L3 / L4 | packing order permuted / a file swapped between units | FAIL | FAIL | OK |
| L5 | stale plan hash | FAIL | FAIL · `plan hash ≠ the manifest-bound r6_plan_sha256` | OK |
| L6 | correct hash of the wrong plan | FAIL | FAIL · `(path)` | OK |
| L7 | plan derived from stale 02-FILES metadata (dates altered) | FAIL | FAIL · `(packing_order)` | OK |
| L8 | a forged partition with **every** artifact (runs, logs, records, claim refs, hashes) made consistent with it | FAIL | FAIL · `(runs, units)` | OK: the plan cannot define its own evidence |
| L9 / L10 | no plan hash in the manifest entry / extra key in the plan | FAIL | FAIL | OK |

### 2.6 R5-06 reading state × claim

The file is L4/S2455 (DECOMPOSED). Findings are measured as the R6 delta over a state-only baseline, in which the L4 GENUINELY-UNDEFINED census was neutralized. Without that step every case fails, correctly, on R17.

| State | EXTENDS (permitted) | CHANGES-DEFINITION / -TYPE / -TERM, NARROWS, CONTRADICTS, RETRACTS, SUPERSEDES (unknown class) | birth | FOUND | R2 edge | `timeline_summary.contradicted_by` | `superseded_by_sources` |
|---|---|---|---|---|---|---|---|
| READ-PARTIAL (evidence page dropped) | PASS (OK) | FAIL ×7 (OK) | FAIL (OK) | FAIL (OK) | PASS (BND, permitted) | **PASS · FALSE-PASS MATERIAL** | **PASS · FALSE-PASS MATERIAL** |
| NOT-CONSUMED (0 pages) | PASS (OK) | FAIL ×7 | FAIL | FAIL | PASS (BND) | **PASS · MATERIAL** | **PASS · MATERIAL** |
| READ-FAILED (0 pages) | PASS (OK) | FAIL ×7 | FAIL | FAIL | PASS (BND) | **PASS · MATERIAL** | **PASS · MATERIAL** |

Also correct:
- READ-PARTIAL without a CONTRACT-DEVIATION escalation → FAIL;
- off-scale state → FAIL;
- WHOLE-FILE with page 1 missing → FAIL;
- an extra ack token → FAIL.

Default-deny holds for every class in `timeline[]`, including unknown ones.

**Observation (the EXTENDS column):** the TIMELINE fact's quote lay on a page the owning run **never logged**, and in the NOT-CONSUMED row no page at all was logged, yet it was accepted. Fact quotes are matched against the whole content, not against the pages the run read. The R6 text permits this for the four non-change classes. It conflicts with rev4's "NOT-CONSUMED never counts as reading". It is recorded as a governance question (MINOR hardening: bind quotes to logged spans).

### 2.7 R5-07 S1

| ID | Attack | Exp | Observed | Result | Machine-detectable? |
|---|---|---|---|---|---|
| S1a | correct YES + quote | PASS | PASS | OK | — |
| S1b | YES + fabricated quote | FAIL | FAIL · `YES quote not found in the authoritative bytes` | OK | yes |
| S1c | YES + quote of another source | FAIL | FAIL | OK | yes (unless the text collides) |
| S1d | YES + real quote of the file about another pair | BND | PASS | BND | **no**: semantics, honesty-dependent |
| S1e | YES + substring collision (`lorem`) | BND | PASS | BND | **no**: no specificity rule |
| S1f | NO under the wrong pair id | FAIL | FAIL · `no pair-evidence check for pair RP1041` | OK | yes |
| S1g | NO + register, no OTHER escalation | FAIL | FAIL | OK | yes |
| S1h | NO + escalation naming `RP10410` (wrong token) | FAIL | FAIL | OK | yes (exact tokens) |
| S1h2 | NO + escalation, no VEC register record | FAIL | FAIL | OK | yes. The historical fixture already carried a VEC record for RP1041; I stripped it first |
| S1i | complete NO | PASS | PASS | OK | — |
| S1j / S1k | NOT-DETERMINABLE; blanket NOT-DETERMINABLE (F2) | BND | PASS | BND | **no. F2 is not machine-closed** |
| **S1l** | duplicate pair id in one record: an unescalated NO with a fabricated quote, then a valid YES | FAIL | **PASS** | **FALSE-PASS · MINOR** | yes: `got[(pid, sid)] = ck` keeps the last entry, so the NO and its fabricated quote are never examined |
| S1m / S1n | off-scale support; required check missing | FAIL | FAIL | OK | yes |

### 2.8 R5-08 EMPTY (full path; L2 made genuinely EMPTY, plan re-derived)

| ID | Content added to the EMPTY object | Exp | Observed | Result |
|---|---|---|---|---|
| EM0 | none (valid EMPTY) | PASS | PASS | OK |
| EM1 / EM7 / EM15 | timeline point on the former file / on another label's file / a non-object timeline entry | FAIL | FAIL · `R6 S L2: EMPTY object with timeline points` | OK |
| EM2 | S-id birth | FAIL | FAIL · `R6 S2 … / must be NOT-EVIDENCED-IN-CAPTURE` | OK |
| EM3 / EM4 | FOUND / GENUINELY-UNDEFINED | FAIL | FAIL | OK |
| EM5 / EM6 | R1 edge / R2 edge with a true quote | FAIL | FAIL · `EMPTY object with dependency edges` | OK |
| EM8 | a stage-2 disposition (claim) | FAIL | FAIL | OK |
| EM9 | the EMPTY label reads under its former run id | FAIL | FAIL · `not a planned run` | OK |
| EM10 / EM11 | S-id range / layer-A pointer | FAIL | FAIL (S3) | OK |
| EM12 | EMPTY-REQUIRED-SET escalation missing | FAIL | FAIL | OK |
| **EM13** | `timeline_summary.contradicted_by = [another label's file]` | FAIL | **PASS** | **FALSE-PASS · MATERIAL** (M3) |
| **EM14** | `superseded_by_sources = [another label's file]` | FAIL | **PASS** | **FALSE-PASS · MATERIAL** (M3) |

### 2.9 H3 layer-A binding

**Position:** `final_records = [obj] + register + P1-gap` is assembled **by the verifier**, and `layer_a={0}` (p3b_s5_r6_verify.py l.159/163). The object is therefore always at index 0 by construction; I found no path that moves it.

**Function level** (probes B), with the pointer caught in each case:
- keys stripped, renamed or missing, and no `working_label`, at position 0;
- an object-shaped record placed in a register slot (key fallback).

**Full path:**

| ID | Pointer text in a layer-A string field | Result |
|---|---|---|
| H3.0–5 | `see the Register record…`, `cf. register`, `rs_id …`, `OB9011:7`, `OB9011-R6:OB9011:7`, `OB9011#7` | caught (OK ×6) |
| H3.6–11 | `register` + double space + `record`; `register` + NBSP + `entry`; `rs-id`; `OB9011 : 7`; `OB9011` + fullwidth colon + `7`; `see` + zero-width space + ` the register` | **PASS · FALSE-PASS MINOR ×6**: lexical spelling variants (the R5-09 class). Neither the R6 lint nor historical G-12 normalizes whitespace or Unicode |

**Conclusion:** the H3 repair (binding by position) is effective and cannot be bypassed by key changes. Lexical coverage of pointer spellings remains incomplete (MINOR).

### 2.10 PASS ⇒ P∧E∧R∧T∧L∧S (fresh construction)

The single violations, each with the others valid:
- **P:** a claim ref to an unknown fact;
- **E:** an unplanned `-R6-L02U01` dir;
- **R:** a duplicate ack token;
- **T:** synthesis before validation;
- **L:** a packing order altered with its hash updated;
- **S:** a fabricated S1 quote.

Results:
- **I1-P … I1-S: 6/6 FAIL**, each on its own tag (for example `R6 E run directory OB9011-R6-L02U01: not a planned run`).
- All 15 pairs, all 20 triples and the six-fold combination FAIL, so 42/42.
- Positive control C0 PASSes.

**Interpretation:** within the invariants as the verifier defines them, conjunction holds. The false PASSes above are not failures of conjunction. They are **gaps in what P, E and R cover** (M1–M3).

### 2.11 Trust boundary

| ID | Attack | Exp | Observed | Interpretation |
|---|---|---|---|---|
| TB1 | READ-LOG hand-written (`stdout: "written-by-agent"`, no sha256, extra field) | BND | PASS | the log is not bound to the reader |
| TB2 | E7 with a back-dated `utc` | BND | PASS | temporal and execution closure hold only if logs are unedited |
| TB3 | R6-PROVENANCE rewritten | BND | PASS | stage times are declarations |
| TB4 | manifest header revision 5 | FAIL | FAIL · `revision 5 is withdrawn` | OK |
| TB5 | header revision 7 | BND | PASS (routes to R6) | the revision comes from the manifest header and is not tied to the contract hash |
| TB6 | header revision `"6"` (string) | FAIL | exception (TypeError) = NOT-PASS | COSMETIC |

### 2.12 Statistical specification (stats.py, function level, synthetic labels)

| ID | Attack | Expected | Observed | Result |
|---|---|---|---|---|
| ST1 | overlapping strata → `draw_sample_v6` | refused | `ValueError: strata not mutually exclusive` | OK |
| ST2 | a label with no stratum | refused | `ValueError: … not collectively exhaustive` | OK |
| **ST3** | **missing stratum:** 6 of 20 labels allocated to an extra name `TIER-X` | refused / reported | `validate_strata → []`; the sample covers N = 14; census "estimate" share 0.214, CI (0.214, 0.214) against a true 0.150 | **DEFECT · MATERIAL** |
| **ST4** | rate missing for SINGLE (N = 12 of 20), so n = 0 | combined bound must allow up to 0.60, or be declared unestimable | share 0.0, **CI (0.0, 0.0)**; only the per-stratum `zero_bound` says 1.0 | **DEFECT · MATERIAL** |
| **ST5** | n_SINGLE = 1 < N = 12 | "n_h ≤ 1 is reported as unestimable" (spec) | var 0.0, HT total 12, overall CI (0.6, 0.6); no unestimable marker | **DEFECT · MATERIAL** |
| **ST6** | frozen-sample guard: tampered sample with an ML-injected discordant label | refused | **ACCEPTED** (HT total 4.00, true 0). `frozen_sha != sample_sha` compares two caller-supplied strings and never hashes the `sample` argument; omitting them disables the check | **DEFECT · MATERIAL** (the ML-exclusion claim is unenforced) |
| **ST7** | overlapping sample fed to `ht_with_variance` | refused | **ACCEPTED**: HT total 6.00 for one discordant label | **DEFECT · MATERIAL** |
| ST8 | zero bounds vs independent computation (n=0; census; hypergeometric; binomial) | equal | equal (5/5) | OK |
| ST9 | E[HT] and E[var-hat] by exact enumeration (N=8, n=3, D=3) | 3; 3.5714 | 3.0000; 3.5714 | OK: the estimator and its variance are correct |
| ST10 | `assign_strata` with a declared `multirow` flag on a SINGLE label | derived, not declared | goes to MULTIROW | MINOR |
| ST11 | seed freezing | reproducible | reproducible; the record freezes seed, labels, spec sha and Python version, but **not** strata membership, rates or a population hash | MINOR |
| ST12 | rate 0; rate 1.7 | n = 0; refused or capped | n = 0; silently capped to a census | COSMETIC |

**Coherence of the text** (population, sampling unit, estimand, SRSWOR, π_h, HT, SRSWOR variance, one-sided bounds, TIER-X as a domain) is sound. The defects are in the **functions' guards**: frame completeness against the population, n_h ≤ 1 and n_h = 0 handling in the combined CI, and the frozen-sample check.

**ML (inspection only):**
- No ML, embedding or similarity code sits on the evidence or sampling path (probes E).
- `p3b_s5_common` imports `numpy` only for `SeedSequence` seed spawning and version recording.
- **But** the one code guard of the ML boundary (ST6) is vacuous.

---

## 3. Closure by kind

**Machine-verified closure** (fresh fixtures, production path):
- R5-02 ordering and strict time parsing;
- R5-04 plan-derived ownership;
- R5-05 plan equality with independent derivation;
- R5-06 for `timeline[]`, births, FOUND and stage-2;
- R5-07 quote-in-bytes, exact tokens, OTHER escalation and register;
- R5-08 for every enumerated claim type;
- H3 positional binding;
- conjunction of the invariants;
- the referential chain claim → (run, source, fact) → record → quote-in-bytes.

**Conditional closure (log integrity assumed)**, per TB1–TB3 and P9b:
- R5-02 (reads are timed by `READ-LOG.utc`);
- R5-03 (a synthesis read is detected by time);
- R5-04 (the owner is taken from the log's run directory).

**Human- or honesty-dependent, not machine-closed:**
- semantic entailment of a fact quote (P6a/b, P14);
- S1 YES about the wrong pair or on a colliding substring (S1d/e);
- NOT-DETERMINABLE and **blanket NOT-DETERMINABLE (F2), not machine-closed**;
- agent identity behind a run id (E15);
- that a WHOLE-FILE read was cognition rather than delivery.

**Not closed (defects):**
- M1 (source-less timeline points);
- M2 (legacy-grammar runs);
- M3 (claim-bearing fields outside the enumeration);
- M4 (statistical guards);
- the MINOR items.

**Untested / out of scope:**
- hub labels under R6;
- labels with STAGE-2A-2B dispositions. By inspection, scanner log entries carry no byte page and would fail E, which would be a false FAIL. Not run;
- binary-file policy;
- the transcript scan;
- the real `_batch_manifest` (revision 3; not regenerated);
- concurrency of reader appends.

---

## 4. Specific conclusions

**R5-01: PARTIALLY CLOSED.**
- *Referential* provenance is enforced for every enumerated claim; attacks P1–P9a and P3a–d all fail correctly.
- *Evidence support* (entailment, specificity) is an epistemic boundary, not closure (P6a/b, P14).
- **Open, MATERIAL (M1):** timeline points without a truthy `source_id` are not enumerated, so an evidence-free FIRST, EXTENDS or NOT-COMPARABLE point passes (P10, P10b, P10c). This is a code-vs-contract deviation: the addendum enumerates "timeline points" without qualification.
- **MINOR:**
  - P11 (non-object entries);
  - P12/P13 (object-level quotes are left to the separate quote checker, which the R6 runbook step 14 no longer names);
  - claim-id grammar exists only in code.

**R5-02: CLOSED (conditional on log integrity).**
- **MINOR:** duplicate stage records resolve last-wins with no rule (T10).
- **COSMETIC:** lowercase `t`/`z` rejected.

**R5-03: NOT CLOSED (MATERIAL, M2).**
- The allowed run set is reconstructed independently for the `-R6-` namespace, but the reader still serves content under `OB####-R2`, `-R2.n` and `-R2S` for OB batches without a plan check.
- The R6 verifier neither reads nor flags those directories (E11a–c).
- **COSMETIC:** E4.

**R5-04: CLOSED (conditional on log integrity).**

**R5-05: CLOSED.** The declared plan cannot define its evidence (L8).

**R5-06: PARTIALLY CLOSED.**
- Default-deny is correct for every `timeline[]` class, including unknown ones.
- **Open, MATERIAL (M3):**
  - `timeline_summary.contradicted_by` (rev3: feeds D4);
  - `superseded_by_sources` (rev3 §A.10: determines `end(p)`).
  These carry contradiction and retraction claims on READ-PARTIAL, NOT-CONSUMED or READ-FAILED files with no whole-file requirement and no fact.
- This is a **contract-scope** defect: the code conforms to the R6 enumeration list, but that list omits claim fields the rev3 contract gives downstream force, contrary to R5 §10's one-directional rule.
- **MINOR:** fact quotes are not bound to logged pages (EXTENDS column).

**R5-07: CLOSED for its machine-detectable part.**
- The honesty-dependent part (S1d/e, S1j/k, F2) is correctly not claimed.
- **MINOR:** duplicate pair checks overwrite (S1l).

**R5-08: CLOSED for enumerated content; OPEN through M3** (EM13, EM14).

**The `/` disagreement: the Revision-6 treatment is CONFORMANT; the R5-audit item on `/` was not.**
- **Evidence:**
  - The rev3 contract §C forbids S-id ranges because "a range is read as citing every S-id in its interval and fails the batch's quarantine check (G-LOG-0045 item 3)".
  - **G-LOG-0045's human ruling** reads: "`/`, `:` and arrows are **not** S-id ranges: quarantine only notation with sufficient semantic evidence of an interval". The canonical matcher `p3b_s5_common.SID_RANGE` implements that ruling.
- **Interpretation**, from probes A with endpoints A = S0957 and B = S2361:

| Notation | R6 S3 matcher | Canonical matcher | Reading |
|---|---|---|---|
| A, slash, B | no | no | enumeration |
| A, space-slash-space, B | no | no | enumeration |
| A, hyphen, B | yes | yes | range |
| A, two dots, B | yes | yes | range |
| A, en dash, B | yes | yes | range |
| A, comma, B | no | no | enumeration |

- The implementer did not cite G-LOG-0045 but reached the ruled position.
- **Finding (MINOR):** `RANGE6` is a divergent re-implementation of the canonical matcher (contrary to ES-005.4's "never a copy"). It misses 8 forms the canonical matcher catches:
  - double hyphen;
  - `up to`;
  - `between … and`;
  - soft hyphen;
  - U+2010;
  - fullwidth hyphen;
  - literal `–`;
  - zero-width space.

  Ranges that span hold-out ids are still caught by the canonical quarantine check in the same verifier, so only non-hold-out ranges escape S3.

**H3: CLOSED for the positional binding.** Lexical pointer variants remain (MINOR ×6).

**PASS ⇒ P∧E∧R∧T∧L∧S: HOLDS as conjunction** (42/42). The residual false PASSes lie outside what P, E and R cover. The implementer's `test_pass_requires_every_invariant` is consistent with this but was not used as evidence.

**Statistical specification:** the text is coherent; **the functions are not (MATERIAL, M4):**
- ST3 extra stratum name → missing stratum → apparently exact estimate;
- ST4 n = 0 strata enter the combined CI as exact zeros;
- ST5 n_h = 1 gets variance 0, contrary to the spec;
- ST6 the frozen-sample and ML guard is vacuous;
- ST7 the estimator accepts overlapping strata.

**MINOR:** ST10, ST11.

---

## 5. Trust-boundary findings

| Artifact | Written by | Can an agent or orchestrator forge or edit it? | What binds it |
|---|---|---|---|
| manifest entry (labels, slice hashes, `r6_plan_sha256`, header revision) | orchestrator at activation (human act) | yes, before commit | git history and governance only. The verifier does not tie the revision to the contract hash (TB5) |
| slices | prepare | — | sha256 in the manifest ✔ |
| source bytes | seal-aware resolver | no | authoritative ✔ |
| 02-FILES metadata | frozen | no | frozen hash (`verify_frozen`, in `main()`) ✔ |
| R6-PLAN.json | orchestrator | yes | manifest hash **and** equality with independent derivation ✔ (strongest link) |
| READ-LOG.jsonl (per run) | the reader (append) | **yes**: a plain ledger file; the agent has shell access | page hashes must match the bytes (proves someone had the bytes). **No hash chain, no reader MAC or signature, no append-only enforcement.** Refused lines can be deleted (defeats stop condition 16); `utc`, run, label and step are text |
| file-reading-records.jsonl | unit or SINGLE agent | yes | by hash, but only to R6-PROVENANCE and RUN-MANIFEST, which are themselves declarations (P9b) |
| R6-PROVENANCE.jsonl | orchestrator | yes | nothing (TB3); duplicates resolve last-wins (T10) |
| RUN-MANIFEST.json, claim-evidence.json, S3-LINT.json | synthesis or SINGLE agent | yes | internal consistency only. The lint is recomputed anyway, so "failure blocks submission" stays unprovable |

**Precedence between authoritative artifacts:**
- The rules, where defined:
  - **source bytes > plan** (sizes; L2, L2b);
  - **independent derivation > declared plan > manifest hash**: all three must agree (L5, L6, L8);
  - **02-FILES > plan** (packing key; L7);
  - **plan > log** (ownership, permitted files; O1, E13);
  - **log > RUN-MANIFEST** (SINGLE reads ≤ produced; T9);
  - **current record files > declared hashes** (P9a).
- The gaps:
  - Between two R6-PROVENANCE records, precedence is **undefined** (last-wins).
  - Between a run's log and "the actual run identity" there is **no** independent source.
  - The source-metadata size never enters the plan; content wins.

**Has trust moved one layer deeper? Partly yes.**
- Revision 6 moved trust **from the batch declaration to the plan (now fully derived: a genuine gain) and to the per-run logs and record files**.
- The logs are authoritative only by convention: they are not tamper-evident.
- What the verifier can know:
  - that page bytes were obtained by someone;
  - that all declarations are mutually consistent;
  - that the plan is right.
- What it cannot know:
  - who read;
  - when (beyond self-consistency);
  - whether a log was edited or truncated;
  - whether records were rewritten together with every hash.
- Closing this needs a binding mechanism, such as a reader-side hash chain or MAC with a key the agent does not hold, or reader-held logs outside the agent's write reach. That is a design question, not a defect of the R6 code.

---

## 6. Findings list

| ID | Sev | Reproduction (one line) | Root cause |
|---|---|---|---|
| **M1** | MATERIAL | `obj.timeline.insert(0, {…FIRST point…, "source_id": null})` → PASS (P10c; also P10, P10b) | `enumerate_claims` skips points with a falsy `source_id`; the schema checks keys only |
| **M2** | MATERIAL | synthesis reads S0794 under `OB9011-R2` / `-R2.1` / `-R2S` after dispatch → PASS (E11a–c) | reader grammar plus `verify_r6`'s run namespace (`-R5`, `-R6-` only) |
| **M3** | MATERIAL | S2455 NOT-CONSUMED + `timeline_summary.contradicted_by=["S2455"]` → PASS (R-NC-tsum; also -sup, the RP/RF rows, EM13, EM14) | the R6 contract's closed claim list omits `contradicted_by` / `rejected_by` / `later_*` / `first_*` / `superseded_by_sources` |
| **M4** | MATERIAL | `strata["TIER-X"]=6 labels` → validate [] → census estimate over N=14 (ST3); also ST4, ST5, ST6, ST7 | no frame-vs-population check; n_h ≤ 1 and n_h = 0 enter the CI as exact; the guard compares caller strings; the estimator does not validate the sample |
| m1 | MINOR | P11 non-object timeline entry → PASS | non-dicts skipped |
| m2 | MINOR | P12 / P13 fabricated object quotes → PASS (the quote checker catches them outside the PASS path) | quote checker not in the R6 step-14 chain |
| m3 | MINOR | T10 duplicate `units-validated`, the later forged one wins → PASS | last-wins dict |
| m4 | MINOR | S1l duplicate pair check hides a NO → PASS | last-wins dict |
| m5 | MINOR | H3.6–11 pointer spelling variants → PASS | no whitespace / Unicode normalization |
| m6 | MINOR | 8 range forms missed by `RANGE6` (probes A) | divergent copy of the canonical matcher |
| m7 | MINOR | fact quote on an unlogged page supports EXTENDS (R-NC-EXT) | quotes not bound to logged spans (governance question) |
| m8 | MINOR | reader plan path `_batch_input_r2/s5/rev6/…` ≠ the verifier path `<slice_root>/…`; the reader does not check `r6_plan_sha256` | defence in depth unbound |
| m9 | MINOR | ST10 declared `multirow`; ST11 frozen record lacks strata, rates and population hash | attributes not derived |
| m10 | MINOR | claim-id grammar defined only in code | contract gap |
| c1–c4 | COSMETIC | E4 (R7 dir ignored); T3.z (lowercase `t`/`z`); TB6 (string revision → exception); ST12 (rate > 1 capped) | — |

**Counts:**
- **Scored cases:** 234 (206 full-path in `attacks.py`, 16 full-path EMPTY, 12 statistics), plus 48 function-level probes.
- **Correct:** 162 + 14 + 6.
- **Boundary (BND):** 20.
- **False PASS / defect:**
  - **MATERIAL 19:** M1 ×3, M2 ×3, M3 ×8, M4 ×5;
  - **MINOR 12:** P11, P12, P13, T10, S1l, H3 ×6, ST10;
  - **COSMETIC 1:** E4 as a false PASS; T3.z is a conservative false FAIL.
- **False FAIL:** none among the scored full-path cases.

---

## 7. Safety baselines (before = after)

| Check | Before | After |
|---|---|---|
| sha256 `P3B-STATE.json` | `db52ac7a6fc5e653fd87d1b4bca900c69ba1c8a4fa45fc448835792ec134c21a` | identical |
| sha256 `_batch_manifest_p3b_r2.jsonl` | `1b383fbc6ff720ebd2f893a74362ce73745dcf99243bc00ced1bcae3a5475682` | identical |
| sha256 `P3B-HOLDOUT-SEAL.json` | `9b99169d30d57b2ecc1d331838652aa6b765ce722059b30095a03df077d589c0` | identical |
| `git status --short -- ledger-p3b-r2 scripts prompts audit-p3b/r5-audit <R5 report>` | only `?? prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` (pre-existing, not mine) | identical |
| H-19 seal | SEALED `HS-3d32dd44d162` | SEALED |
| H-19 grep | 58 files, 0 violations | 58 files, 0 violations |
| R6 regression suite (run once) | — | `Ran 53 tests … OK` |

**Confirmations:**
- **No corpus or hold-out access:**
  - no production corpus read;
  - the real resolver was replaced before every verify call, and the fake refuses unknown ids;
  - the reader CLI was never executed (the module was imported in probes C; `main()` was never called);
  - no hold-out content read and no hold-out identifier printed;
  - quarantine scan of every artifact file: (0, 0). The probe strings are assembled in memory so that no file text implies an interval.
- **No change to governed material:**
  - no write outside `audit-p3b/r6-audit/`, this report and scratch temp dirs (removed with `shutil.rmtree`);
  - nothing staged or committed;
  - no repair;
  - no activation, manifest regeneration, binary pre-classification, dispatch, S5c or ML.

## 8. Audit artifacts (sha256)

| File | sha256 |
|---|---|
| `r6-audit/fx.py` | `3020c5f70bf8abe620a6695aa67660d338c3f189043aa0fc81a76e58cbb56afe` |
| `r6-audit/attacks.py` | `fa3d40b02ff3fcb53ecf86e9ca35dbc6b87634f6406c448badb3d1c8a8c09c3e` |
| `r6-audit/attacks.out` | `e4392711fa9a43e5eb3bddc3000d9f3252439e1c3956ee131e2fa58cf1e9bd85` |
| `r6-audit/empty.py` | `a4f32634bcdadb35d9097f3029fbe3c52db6cc8c1b949889004f91b69df31e3c` |
| `r6-audit/empty.out` | `0be869d486f9286a63801143d38a529f6f14acfa55d4ee2604e81f43b43a8036` |
| `r6-audit/probes.py` | `605796f4b3838e540ad5470fe2dc08e9be2b0f91b6b248e07a05908de935db1f` |
| `r6-audit/probes.out` | `69224cb5ed38ffac4842a35728c8f9feb34f60a415889e1738872b5a05132114` |
| `r6-audit/stats.py` | `c63a3279d633c47942ea0e4a2ebd0a4533e085bf0ac7c98f5a84d988f3078fe4` |
| `r6-audit/stats.out` | `fa23e66cb07eba8cc089be3c40fd6e645d22cc691a5681af8209dc9a47f404ca` |

**Audited files:** they are unchanged, and their hashes equal the implementation record.
- `p3b_s5_r6.py` `7d3c9b68…2e04`
- `p3b_s5_r6_verify.py` `5e70184e…5b7a`
- `p3b_s5_verify.py` `b3c53538…ca35`
- `p3b_read_source.py` `3b634653…fea`
- `p3b_s5_common.py` `9ac0426a…090e`
- R6 addendum `b164539e…4a92`

**Run:** `cd audit-p3b/r6-audit && python3 -B -W ignore <script>.py`

## 9. Independence statement and limits

I am a fresh-context model instance with no drafting context for Revision 6. I treated the implementer's code, tests and record as claims.

**What is independent:**
- the fixture (batch id, source material, label mix, bytes, sizes);
- the plan derivation, paging, ack tokens and claim enumeration, written from the contract text;
- every mutation and every expected outcome.

**What is reused:**
- generic fixture-writing helpers (`T.s4_material`, `T.to_s5`, `T.write`);
- the testlib's synthetic hold-out sets;
- the claim-id spelling, which the contract does not define (m10).

**Limits:**
- I am **the same model family** as the implementer. This is **not** a human audit and **not** a cross-vendor audit, and correlated blind spots are possible.
- Severity is my judgment against the contract text. M3 is a contract-scope finding, and its classification as MATERIAL is open to human ruling.
- The audit is narrow by authorization (§3 lists what was not tested).
- The strongest claim made here is this: the listed reproductions, run on the committed code, give PASS through the production verifier.

REVISION 6 NARROW INDEPENDENT RE-AUDIT COMPLETE: NEEDS-REVISION. NO REPAIR, NO ACTIVATION, NO S5 DISPATCH. Control returns to the human.
