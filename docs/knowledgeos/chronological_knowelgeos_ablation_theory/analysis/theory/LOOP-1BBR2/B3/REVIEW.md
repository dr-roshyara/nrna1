# REVIEW of repair r2 (SPEC-r2.md / SPEC-r2.json; CHANGELOG-r2.md absent)

**Inputs used (only files in this folder):** REVIEW-TASK.md, TASK.md, ENVIRONMENT.md, HYPOTHESES.md, REVIEW-r1.md (and the head of REVIEW-r1.json), SPEC-r1.md (Part A and §0–§1), SPEC-r2.md (read in full), and SPEC-r2.json (meta, repairs, symmetry table, decision procedures and protocol blocks read; the embedded script arrays were spot-checked against the MD text, not diffed line by line). No command was run. Statements about git, bash and awk behaviour come from knowledge of those tools, and each gives its confidence.

---

## Verdict: **FAIL**

**Neither script may run.** There are five blocking issues (§6):

1. **B-1.** CHANGELOG-r2.md, a required deliverable, does not exist. SPEC-r2 points to it as the list of edits made to FIXED items.
2. **B-2.** The precedence clause of SPEC-r2 keeps every SPEC-r1 section that r2 does not restate. This brings back NIF-B, the graded fallback and the recording-time fallback in sections r2 did not restate, and they now contradict G-SYM. Read literally, P5-HD can still FIRE on third-party behaviour.
3. **B-3.** DP-P4 is not exhaustive. It has no catch-all row, which contradicts FR-N7, so D2 and N7 are only PARTIAL.
4. **B-4.** Both scripts hash `${BASH_SOURCE[0]}` *after* `cd "$TOP"`. Under the documented usage ("from anywhere inside the working clone … `bash rank0-r2.sh`"), a run from a subdirectory ends in CAPTURE_ERROR. B.8 W4 allows no rerun after that.
5. **B-5.** In the scoping script, the "executor" writer set is built by path exclusion. That set contains the writers of every non-doc candidate path, including the candidate itself. So K-STATUS data-file candidates (`*.json`, `*.yaml`, files without an extension) always get NO_AS_NIF_A. This is a negative produced by construction, and it is neither reachable-by-observation nor labelled.

Given the frozen rule FR-D5(f), kept in r2 ("all rules are frozen before any Stage 0 command runs"), the spec-level blockers B-1 to B-3 also block Stage 0. Fixing them after Stage 0 data exist would be a post-hoc change.

The core of the repair is real. G-SYM (§A.0) is a correct, symmetric rule. N2, N4, N5, N6, N8–N12 are fixed. R0-1 and R0-2 are removed by construction, and the rank-0 script is otherwise carefully built. The failure comes from (a) how r2 is merged with r1, (b) one non-exhaustive decision procedure, and (c) two script defects, each fixable in a line or two.

---

## 1. Completeness of SPEC-r2 (the disclosed interruption)

| Required by TASK.md | Present? | Notes |
|---|---|---|
| SPEC-r2.md | yes | Complete. It runs to the residual-risks section, and every in-scope item has its five parts (1)–(5). |
| SPEC-r2.json | yes | It has `repairs` (D1, D2, D5, D8, D9, N1–N13, R0-1..9, SC-1..7), `symmetry_table`, `spec_changes`, `rank0_protocol.script` and `scoping_protocol.script`. It closes properly. |
| CHANGELOG-r2.md | **no** | It was never written, as disclosed. SPEC-r2.md (line 13) and the JSON `meta` both say that the consequential edits to FIXED items "are listed in CHANGELOG-r2.md". That list does not exist. JSON `spec_changes` CH2-01..15 partly stands in for it, but it has no before/after text. |

**Coverage of in-scope items.** Every in-scope item (D1, D2, D5, D8, D9, N1–N4, and also N5–N13, R0-*, SC-*) has a section in SPEC-r2. **No item is NOT_FIXED merely for being missing.** The missing CHANGELOG is a deliverable defect (B-1). It is not a missing repair.

---

## 2. Repair status of the in-scope items

| Item | Status | Reason |
|---|---|---|
| **D1** | **PARTIAL** | G-SYM fixes the logic: FIRED needs NIF-A for every NS/ABS premise, and every DX class grades DISJUNCTIVE. But (i) under the precedence clause, r1 falsifier and field texts that admit NIF-B or the graded fallback stay in force (B-2, SV-1, SV-2). (ii) DX-MAK does not have one grade: on P1-HD it is NOT_APPLICABLE (SV-3). |
| **D2** | **PARTIAL** | N8 is fixed: P4 is ungated, and only S1-c, S1-d and S1-g are gated. DP-P7 and DP-C1 are exhaustive. **DP-P4 is not exhaustive** (B-3). D2's named defect ("the table is not exhaustive") therefore survives for P4. |
| **D5** | **FIXED** | (b) FR-N9 reads the DCS from root to capture, and marks POSSIBLY_LATE whether or not the act is dated. It is operational. (c) FR-N4 uses dual AF readings and scores only a grade on which they agree. (a), (d), (e) and (f) are kept. |
| **D8** | **FIXED** | The P1 rules are frozen now: eligibility by act-independent work outcomes (WO-EVID/WO-FAIL), an expected change per type, and a keyword satisfaction table with a NOT_FROZEN_CLAUSE catch-all. Caveat: §1-P1-r2 does not restate the P1 falsifiers, so the precedence question (B-2) applies here too. |
| **D9** | **FIXED** | FR-D9-r2 has V1–V4 with UNDETERMINED first, existence-before kept apart from force-before, no default from absence, and no act-exclusivity baseline. It is a function, and V2 and V3 are exclusive. The removed recording-time fallback still survives as text in r1 §0.5 and §0.11, which r2 does not restate (B-2). C1 no longer uses it. |
| **N1** | **PARTIAL** | FR-N1, ACT-TP and §0.4-r2 are correct, and DP-P4 cannot fire on ACT-TP. But the r1 P5-HD falsifier ("NIF-A **or NIF-B** shows R-94 in force"), the r1 P5 NIF requirement, and FR-D7 ("uptake … NIF-B") are not restated. By the precedence clause they remain in force (SV-1). |
| **N2** | **FIXED** | DX-ABS-INF presupposes H-D.c1a. ACT-CONTRA needs NIF-A (§0.3-r2). The "unauthorized unless shown" baseline and O5 are removed. Every H-B absence premise grades DISJUNCTIVE CROSS or UNDETERMINED. |
| **N3** | **PARTIAL** | FR-N3 is symmetric as stated. But the r1 graded fallback survives in unrestated text (r1 P2 `norm_in_force_at_t_exec⁻`, r1 FR-D8 P7/P8 bullets "NIF or the graded fallback", r1 P8 field, and r1 §0.11 "R-86 text → FIRED-CONDITIONAL premise"). P1-HD also gives DX-MAK a grade it gives nowhere else (SV-2, SV-3). |
| **N4** | **FIXED** | The classification is UNKNOWN. AF-N and AF-B are both frozen. Only a common grade is scored, otherwise UNDETERMINED(AF_READING). NIF-A overrides both. (The DP-P4 rows 5–7 that it evaluates under both readings are incomplete, which is counted under B-3.) |

Other items, for completeness. These were not asked for individually, but they were checked for new defects:

| Item | Status | Item | Status |
|---|---|---|---|
| N5 | FIXED (residual text in r1 §0.5/§0.11, see B-2) | R0-1 | FIXED (but see B-4) |
| N6 | FIXED | R0-2 | FIXED |
| N7 | **PARTIAL** (DP-P4, B-3) | R0-3 | FIXED |
| N8 | FIXED | R0-4 | FIXED |
| N9 | FIXED | R0-5 | FIXED (merge-subject caveat, R0-NB-3) |
| N10 | FIXED | R0-6 | FIXED |
| N11 | FIXED | R0-7 | FIXED (ref names and subjects are still written; R0-NB-6) |
| N12 | FIXED | R0-8 | FIXED (PAT-named refs only; R0-NB-4) |
| N13 | **PARTIAL** (NIF-B/NIF-C live on in r1 FR-D7; N-ASSERTED is classified two ways, SV-6) | R0-9 | **PARTIAL** (diff-header TAB for paths with spaces, R0-NB-1) |
| SC-1 | **PARTIAL** (B-5 re-fixes the result for data-file candidates) | SC-5 | FIXED (minor `ext` leak, SC-NB-1) |
| SC-2 | **PARTIAL** (B-5 produces a constructed NO_AS_NIF_A) | SC-6 | FIXED |
| SC-3 | FIXED | SC-7 | FIXED |
| SC-4 | FIXED | | |

---

## 3. Symmetry

**Method.** I rechecked every falsifier: P1-HD, P1-HB-1a/1b, P2-HB (including C1), P4-HD, P5-HD, P5-HB via P2, P7-HB, P8-HB, and P7-HD/P8-HD (which route to P1). For each one I checked G-SYM, the DP procedures, and the r1 text that the precedence clause still keeps in force. I asked two questions:
- Can any premise that the other hypothesis explains, or that presupposes a hypothesis clause, still *fire* (FIRED or FIRED-CONDITIONAL)?
- Is any source class graded differently depending on which hypothesis it cuts against?

**Inside G-SYM and the DP procedures, no dual-explainable premise can fire.**
- FIRED and FIRED-CONDITIONAL need every NS and ABS premise to come from NIF-A.
- The only allowed conditions (SOURCE_STATED_TIME, ACTOR_SET_ID, WEAK_TRACE) are neutral.
- Behaviour (DX-BEH), the maker's records (DX-MAK), absences (DX-ABS, DX-ABS-INF) and assertions (DX-ASSERT) all end in DISJUNCTIVE or UNDETERMINED, on both sides.

That is the substance of N1–N3, and it is correct. The remaining violations come from the merge with r1, and from grading asymmetries that never fire.

| # | Type | Falsifier | Premise / source | Violation |
|---|---|---|---|---|
| **SV-1** | **firing** (under the literal precedence rule) | **P5-HD** (and P1-HD, if the r1 P1 falsifier text survives) | "R-94 in force" by **NIF-B** = ACT-TP = DX-BEH, presupposing H-B.c2 | The r1 P5 falsifier and NIF requirement ("NIF-A or NIF-B shows R-94 in force"; "effective branch needs NIF-A or NIF-B … applied as binding by a third party") are not restated. "Any SPEC-r1 section that is not restated … remains in force unchanged." So a falsifier of H-D can fire on behaviour that H-B explains. This conflicts with FR-N1 and FR-D1-r2, and no tie-break rule is given. |
| **SV-2** | **firing** (FIRED-CONDITIONAL, under the literal precedence rule) | **P2-HB** (executions other than C1), **P5-HB** via P2, **P8-HB** | "not permitted at t⁻", "R-94 not in force", "active target", "remediation in force" from the **maker's records** (DX-MAK, presupposing H-D.c2) | These r1 texts are not restated, and they still allow "NIF, or the graded fallback (maker's texts) → at most FIRED-CONDITIONAL": r1 P2 field `norm_in_force_at_t_exec⁻`, FR-D8 P7/P8 bullets, P8 `remediation_or_deferral_in_force`, and §0.11 "'Adopted' R-86 → R-86 text → FIRED-CONDITIONAL premise". C1 and P7 are protected by FR-N7 (DP-only scoring). The other H-B falsifiers are not. |
| SV-3 | grading (non-firing) | P1-HD | S1/S2 from the maker's records | Graded **NOT_APPLICABLE** ("a change recorded by the maker is itself an act"). Everywhere else DX-MAK is DISJUNCTIVE (or NON-DISCRIMINATING when it acquits). This contradicts FR-N3 ("DX-MAK … DISJUNCTIVE on every falsifier") and the G-SYM order: NOT_APPLICABLE is for preconditions that "cannot hold", and here the precondition can hold. It also presupposes that a maker's record of a state is an act, which is an H-D reading of the record. The summary row "DX-MAK → DISJUNCTIVE (SELF) or NOT_APPLICABLE" admits the second grade. |
| SV-4 | grading (non-firing) | P4-HD | AD = T (the maker's records show an adoption form), with F89 from DX-BEH | DP-P4 row 7 does not test AD. It returns DISJUNCTIVE{H-D.c2; H-B.c2} CROSS, which asserts that one of the two clauses fails, although the falsifier condition ("in force ∧ not adopted") is false on the maker's records. In the mirror case on the H-B side, a DX acquittal gives NON-DISCRIMINATING (DP-P7 rows 5–7 with grade D; DP-C1 row 3). |
| SV-5 | evidential gating (non-firing) | C1 (P2-HB) vs P4-HD and P7-HB | NIF-A route | DP-C1 puts row 1 (V_text ∈ {V1, V4} → UNDETERMINED) **before** the NIF-A row. So a missing maker's text, or an unshown proviso (DX-MAK/TXT matters), blocks both FIRED and NOT-FIRED on NIF-A for the H-B falsifier. DP-P4 and DP-P7 let NIF-A decide first, and only S1-a is a prerequisite in DP-P4. |
| SV-6 | classification inconsistency | P4-HD, P5-HD | third-party citation (N-ASSERTED, formerly NIF-C) | G-SYM(2) lists citation under **DX-BEH, presupposing H-B.c2**, which gives CROSS. The symmetry table grades it "SOURCE_ACCURACY → DISJUNCTIVE NEUTRAL". DP-P4 row 7 would apply CROSS. The same source therefore gets two tags. |

**Residual (PLAUSIBLE, non-blocking).** G-SYM declares NIF-A to be hypothesis-neutral (RECORDER_ACCURACY only). It does not require that the recorder's own *criterion* for "in force" be neutral. A status tracker that sets `in_force` on adoption would encode H-D.c2. Whether I2 (derivation) excludes such a recorder cannot be checked from r2, because C.4 says "as in r1".

**Omissions in the table (non-blocking).**
- The P1-HD premise "no OUT_OF_CHANNEL_ACT" is missing.
- The P8 "active target" premise has an ABS part: "no closure, acceptance, withdrawal or hold act in force", which is DX-ABS-INF.
- For P5-HB, "execution attested by the revision that introduced line 58" is graded neutral OCC. But a progress-file revision is a *report*, and B.3 says "reports are never traces". Its execution content is SOURCE_ACCURACY, and only its recording time is OCC.

---

## 4. Rank-0 script (`rank0-r2.sh`, B.7): read line by line

**Would it run to completion in ENVIRONMENT.md?** Yes, if it is invoked from the toplevel or by an absolute path (confidence: high). In every other case it completes but returns **CAPTURE_ERROR** (B-4):
- `cd -- "$TOP"` (line 1004) runs before `SS="$(sha256sum < "${BASH_SOURCE[0]}")"` (line 1051).
- With `bash rank0-r2.sh` run from a subdirectory, BASH_SOURCE is relative, and the redirection fails with exit 1.
- That exit 1 is recorded in `00_errors.tsv`, so the status is CAPTURE_ERROR and the script exits 1.
- B.8 W4 makes exit 1 final ("1: the result is CAPTURE_ERROR"), and only exit 2 may be rerun.
- The usage line invites exactly this invocation. The fix is to resolve the script path, or hash it, before `cd`.

Everything else checked out:
- **Bare guard and preconditions.** `--is-inside-work-tree`, `--is-bare-repository` and `--show-toplevel` all run before anything else, with exit 2 on failure.
- **OUT outside the repository.** The `case` patterns are quoted, so they match literally.
- **Shallow and partial gate.** `extensions.partialClone` and the `remote.*.promisor|partialclonefilter` keys are checked; exit 3.
- **Capture.** `for-each-ref`, with tags peeled one level and non-commit refs listed in `01b`.
- **Searches.** `rev-list --stdin`, `log --stdin --grep -E`, `-G` (ERE in pickaxe), `--diff-merges=first-parent` pickaxe on merges, and `--diff-merges=combined --name-only` for own paths (files that differ from every parent).
- **Ref-exclusive commits.** `rev-list --stdin` with `^tip` lines.
- **Trailers.** `%(trailers:only,unfold,valueonly)` is supported at 2.55.
- **Ancestry.** `merge-base --is-ancestor`, with 0 and 1 allowed. `local rc=$?` captures the right status.
- **awk `-v` values.** They contain no backslashes, so `-v` escape processing is harmless.
- **Order labels.** The AWK_ORDER BEGIN-only program, with `getline` and `close`, is correct.

**Non-zero exits.** Every git call is classified by `ok` (config 0/1, `merge-base --is-ancestor` 0/1, everything else 0). Pipelines use `PIPESTATUS`. There is no `set -e`. Script exits: 0, 1, 2 and 3. **Handled.**

Minor: an empty control gives CAPTURE_INVALID with **exit 0**, while B.0 lists "3 = CAPTURE_INVALID". W4 still routes it correctly through `80_status.tsv`.

**Deterministic and hashed.** **Yes.**
- `LC_ALL=C` and `TZ=UTC`, global and system config ignored, `-c` overrides, and `--no-ext-diff` / `--no-textconv`.
- Every set is sorted, and the loops read sorted files.
- Temporary files (`.t`, `.c`, `*.tmp`) are removed before `finish`.
- `SHA256SUMS` covers every file, its own hash is printed, and there is a canonical key.
- The byte-identity claim is limited to the same refs and the same git version, which is correct.
- Residual: repository-local `.git/config` still applies, for example `diff.algorithm` or attribute-driven `-diff`. `-diff`/binary files are invisible to `-G`.

**Target matching.** **Precise for the declared PAT.**
- HY is an explicit alternation of byte strings, matched bytewise.
- Alphanumeric boundaries exclude `WP-4C-10`, `WP-4C-1a` and `XWP-4C-1`.
- VAR is reported and never counted.
- Ref names are matched.
- Residuals:
  - Lowercase branch names (`feature/wp-4c-1`) are VAR only, and no ref-exclusive set is computed for VAR refs (R0-NB-4).
  - `WPANY`, used for `fnt`, has no left boundary.
  - `WP-4C-1.2` would match. Whether such sub-identifiers exist is UNKNOWN.
  - The control `WP-4B RED` is ASCII-only, while PAT accepts typographic hyphens.

**Trace vs mention, including merges.** **Operational.**
- The own-path rule uses the combined diff.
- `al` counts only on own paths.
- A tokened merge with no own change is M-MERGE.
- Side commits are not promoted.
- Comment-only lines give M-COMMENT.
- Governance-stem data files are DOC_GOV.
- `fnt` demotes a subject hit.

Non-blocking defects are listed below.

**M-TIME.** **Respected.**
- Git times are SOURCE_STATED, and ancestry gives RECORDING_ORDER.
- CLOCK_ANOMALY is computed over every ancestry pair found.
- SOURCE_STATED_ORDER is given only when author order and committer order agree.
- No git time is used as a decision time or force time.

Spec-level gap (non-blocking): DP-P7 row 8 and DP-C1 row 2 compare a git t_w with an NIF-A state time ("not in force at t_w⁻") and grade the result FIRED-CONDITIONAL(SOURCE_STATED_TIME). B.4 says that order relative to force is "always NOT_IDENTIFIABLE from this channel". The rule for combining the two (INFERRED_ORDER with a written chain) is not stated.

**Absence.** **Correct.**
- NOT-EVIDENCED-IN-CAPTURE holds only when the control is non-empty, there are no unexpected exit codes, and there is no ET.
- ABSENT_IN_COMPLETE_CHANNEL cannot be output.
- `completeness NOT_SHOWN` is written to `80_status.tsv`.

**Non-blocking (rank-0):**
- **R0-NB-1.** Git appends a TAB to `--- a/…` and `+++ b/…` headers when the path contains a space. `np` therefore ends in TAB, and the `94_lines.tsv` columns shift: `$4` becomes empty, so a COMMENT line is counted as CODE (`al`). The result is a false E2b for comment-only hits in paths with spaces. (Confidence: high.) R0-9 is PARTIAL.
- **R0-NB-2.** A message line that contains a TAB is truncated at `$3` in `92_msg.tsv`. A `--grep` hit after the TAB can then lose its S/T/B feature, which gives under-classification.
- **R0-NB-3.** An auto-generated merge subject naming the branch ("Merge branch 'feature/WP-4C-1'", or "Merge pull request #n from …/WP-4C-1") together with a conflict-resolution own change becomes ET-1, so an integration mention becomes t_w. Conversely, untokened side commits are invisible even though the merge names them (the latter is disclosed).
- **R0-NB-4.** Ref-exclusive commits are computed only for PAT-named refs. Lowercase package branches go to VAR.
- **R0-NB-5.** WEAK_TRACE is global ("every minimal ET is ET-2"). DP-P7 evaluates each ET separately, so a minimal ET-2 does not carry WEAK_TRACE when some other minimal ET is ET-1.
- **R0-NB-6.** `01_refs.tsv`, `15`, `16` and `92` contain ref names and commit messages, which may include personal names. This contradicts the R0-7 text "no … name is written". It is not a TASK requirement for rank-0.
- **R0-NB-7.** The ancestry loops are O(n²) process spawns. They are bounded, but slow if there are many ETs or anchors.
- **R0-NB-8.** The bash version is not stated in ENVIRONMENT.md. The heredocs inside `$(…)` contain unbalanced quotes (`'''`, `"""`). That is fine on bash ≥ 4 and risky on 3.2.

---

## 5. Scoping script (`scope-r2.sh`, C.3)

| Check | Result |
|---|---|
| Bounded | **Yes.** A fixed channel list, one script, a stop rule, and host channels not queried. |
| Existence-only | **Essentially yes.** Declared exceptions: commit and tag headers (signature presence), and CODEOWNERS blobs reduced to counts. The CODEOWNERS read is a content read. It is disclosed, and no identity leaves TMP. |
| All outcomes reachable or labelled ND | **No (B-5).** The writers of a candidate path are always in the "executor" set when the path is not doc-excluded, because EXC excludes only doc extensions and doc directories. So `oe > 0` for every K-STATUS (or K-SESS/K-PROG) candidate with a data extension or no extension, and Q3 = INCLUDES_EXECUTOR_WRITERS, Q5 = NO_AS_NIF_A[ACTOR_SET_ID] and I1 = FAILS are forced. The channel aggregate inherits this from any one such path. Machine-readable status files are the most likely NIF-A candidates, so OPEN_LOCAL_CANDIDATES is close to fixed before the run. That is SC-1 again, in a new form. It also makes NO_AS_NIF_A a constructed negative, which contradicts SC-2's claim that "every remaining 'no' is … positively observed". Rank-0's DOC_GOV and scoping's EXC also define "executor" differently. |
| "Not determinable" never reported as a negative | Yes, apart from B-5. The headline is fixed as NIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK, Q1 has no NO, and host channels are ND(HOST_NOT_AUTHORIZED). |
| History paths covered | **Yes.** The path universe is `log -m --name-only` over all tips (root commits shown), and presence at every tip comes from `ls-tree`. Residual: the per-path history uses `--no-merges`, so a path touched only by merges has nc = 0 and dates "-", which gives K2 = NOT_INDICATED. |
| No personal identities | **Yes, essentially.** Emails stay in TMP, only counts and 16-hex ids are written, stderr is reduced to a line count, and an `@` guard exists. Residuals (SC-NB-1): `ext` writes up to 8 characters of a path's extension, which is a path fragment, for CI, hook or CODEOWNERS paths with odd extensions. The `@` guard does not delete leaked files from OUT. |
| Exit handling | Yes. grep 0/1, `cat-file -e` 0/1/128, ls 0/2, config 0/1. No `set -e`. |
| Deterministic and hashed | Yes. The TMP name is never written, outputs are sorted, and there is a SHA256SUMS file. |
| Runs to completion | **Same B-4 defect.** `cd "$TOP"` (line 1431) runs before `sha256sum < "${BASH_SOURCE[0]}"` (line 1445). The script also needs bash ≥ 4 (`declare -A`) and GNU `date -d` / `xargs -d`. The tag loop splits on IFS = TAB, and TAB is IFS whitespace, so empty fields collapse. It works for annotated tags only by accident, and lightweight tags are counted separately, so the result is correct. |

---

## 6. Blocking issues

| # | Where | Issue | Failure scenario |
|---|---|---|---|
| **B-1** | deliverables | CHANGELOG-r2.md is missing. SPEC-r2 and the JSON say the consequential edits to FIXED items are listed there. | The out-of-scope audit has no authoritative record. The frozen package references a file that does not exist. |
| **B-2** | SPEC-r2 precedence clause (line 17) and Part A2 | Unrestated r1 sections remain in force: r1 §1 P2/P4/P5/P7/P8 fields and falsifiers, FR-D8 P7/P8, §0.5 (entry into force "not earlier than recording time"), §0.11 (R-86 text → FIRED-CONDITIONAL; recording-time fallback), and FR-D7 (NIF-B uptake). They contradict FR-D1-r2, FR-N1, FR-N3 and FR-N5, and no tie-break exists. §1-P1-r2 replaces r1 P1 without restating its falsifiers. | Stage 1 scores P5-HD FIRED on a third-party application (SV-1), or P8-HB FIRED-CONDITIONAL on the maker's authorization text (SV-2). Alternatively the scorer must choose a rule after seeing the data, which breaches FR-D5(f). |
| **B-3** | DP-P4 (§5-r2) | It has no unconditional final row. It lacks rows for: F89 by NIF-A with AD not by NIF-A; AD = T (an adoption form in the DCS); AD NOT_IDENTIFIABLE (POSSIBLY_LATE); and the AF-B branch when the assertion makes AD = T. | Inputs that can occur get no grade, or the wrong one (SV-4). This contradicts FR-N7's "ends with a catch-all", so D2 and N7 remain PARTIAL. |
| **B-4** | rank0-r2.sh line 1051; scope-r2.sh line 1445 | The script hash is taken through a relative `${BASH_SOURCE[0]}` after `cd "$TOP"`. | Documented usage from a subdirectory gives exit 1 and CAPTURE_ERROR, and W4 allows no rerun. The search data exist but are unusable. |
| **B-5** | scope-r2.sh S5/S6 (EXC writer set) | A candidate's own writers are counted as executor writers. | Any `status.json` or `queue.yaml` candidate gets NO_AS_NIF_A[ACTOR_SET_ID] whatever the data are. OPEN_LOCAL_CANDIDATES is under-reported, and the rank-1 human decision (§4-r2) is taken on an artefact of the protocol. |

---

## 7. Changes outside the repair scope

| Change | Assessment |
|---|---|
| FR-D13 item (2) **[CONSEQUENTIAL N1]** | Disclosed and justified (REVIEW-r1: "N1 carries over"). Acceptable. |
| FR-D4: the S1-b read range **[CONSEQUENTIAL N9/N10]** | Disclosed. The window itself is unchanged. Acceptable. |
| **FR-D10 (FIXED):** P1-HB 1a now returns UNDETERMINED(EC_NOT_COMPLETE), and its domain becomes "targets with system-recorded outcomes" (via N12) | **Not marked CONSEQUENTIAL.** It changes the outcome semantics of a FIXED item ("not instantiated in W" becomes unreachable). The edit should have been disclosed. |
| **FR-D11 / §0.11 (FIXED):** not restated | It is not changed, but it is now inconsistent with N3 and N5 (part of B-2). |
| **FR-D7 (FIXED):** still names NIF-B and NIF-C | It is not changed, but its vocabulary is now undefined (N13). |
| §4 ranking (FR-D3 was FIXED) | Rewritten under SC-1 and SC-4, as REVIEW-r1 §7.7 requested. In scope. |
| SC-6 and SC-7 repaired | TASK item 4 lists SC-1..SC-5, but item 2 covers "any further … defects in REVIEW-r1". Benign. |
| Rank-0 capture narrowed from a mirror to the local ref set | Required by TASK and ENVIRONMENT. In scope. |

---

## 8. Required for r3 (minimal)

1. Write CHANGELOG-r2 (or r3) with before/after text for every FIXED-item edit.
2. Replace the precedence clause with an explicit statement that G-SYM and FR-N1..N4 override every r1 clause naming NIF-B, NIF-C, the graded fallback or the recording-time fallback. Alternatively, restate r1 §1 P1/P2/P4/P5/P7/P8, FR-D7, FR-D8, §0.5 and §0.11.
3. Give DP-P4 rows for AD = T, AD = U and F89 = A with AD ≠ A, plus an unconditional final row. Fix the P1-HD DX-MAK grade (SV-3) and the N-ASSERTED class (SV-6). Decide whether DP-C1 consults NIF-A before V_text (SV-5).
4. In both scripts, compute the script hash (or `realpath` of BASH_SOURCE) **before** `cd`.
5. In scope-r2.sh, exclude each candidate's own path (or all candidate paths) from the EXC writer set, or label the overlap SELF_OVERLAP / ND. Align EXC with rank-0 DOC_GOV.
6. Cheap, non-blocking fixes: strip the trailing TAB from diff-header paths (R0-NB-1), compute WEAK_TRACE per ET (R0-NB-5), and state the INFERRED_ORDER rule for comparing t_w with NIF-A.
