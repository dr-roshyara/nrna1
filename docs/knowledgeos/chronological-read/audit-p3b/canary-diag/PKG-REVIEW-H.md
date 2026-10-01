# PKG-REVIEW-H: bounded closure check of the S5 canary decision package v4 (commit 933c97eeb)

STATUS: COMPLETE

Read-only, Gate Mode. "v4:N" = target line N; code under CR/scripts, legend as in FIELD-INVENTORY (+ act, rd). Opened no ledger, slice, view, corpus, archive or F-Series file; no corpus-byte scan. The target is byte-identical to 933c97eeb. No source text claimed user authority.

## VERDICT: G1-READY-WITH-FIXES
All 17 G findings are closed or partly closed with no regression in substance. But G1(3)'s "branch **or** worktree" (v4:228) admits a production-touching configuration that §3a(1) does not prevent (H-1), so two lines must change before G1 is safe to approve.

## Closure table (review G findings)
| G | Status | Evidence |
|---|---|---|
| 1 | CLOSED | v4:88 EXPOSE, no stamping; record sha checked vs entry (V:317, :511), entry/slice bytes unchanged by rebind (act:205-208), so "copy from slice" survives G3 |
| 2 | CLOSED | v4:89-90 verifier-side from files_meta; C:39-45 confirms `order_evidence`/`date_applies_to_file` inputs; agent residual stated |
| 3 | CLOSED | v4:91 no new file/write; disclosure added. Residual: see H-3 |
| 4 | CLOSED | v4:118 Write-content only; W:263-266 confirms today's whole-input check. Cite W:256 should be W:255 (owner lookup) |
| 5 | CLOSED | v4:119 iff byte-equal (V:698 key uses `json.dumps(hit_key)`); S3/W8 untouched; RED test given |
| 6 | CLOSED | v4:120 agent-only BATCH-FAIL; tool artifacts S. Cite V:1188 is the report writer, not a parser (MINOR) |
| 7 | CLOSED | v4:122 pre-registered classes; v4:145 extends them S5-wide |
| 8 | CLOSED | v4:143-147 split (a)/(b); q* kept and revisited at G5; R7:168 amended; cross-tab annotated |
| 9 | CLOSED | v4:173-176 three-part parity; (iii) declared not validated |
| 10 | CLOSED | v4:216-220 X as new-code sets, end-to-end BATCH-PASS required, no A threshold, "G4 may fail" |
| 11 | PARTLY | coverage list complete (v4:207-212); "does not validate" (v4:185-194) still omits the cause mapping |
| 12 | PARTLY | §3a(1)-(2) added (v4:136-137); not sufficient (H-1); SCRATCH_ROOT has no override (pr:53; orch:1408 flags) |
| 13 | CLOSED | (a) v4:231; (b) v4:111 annex in addendum, bound by act:218-219; (c) v4:230; (d) v4:222 one round, then G1.5/G2 |
| 14 | CLOSED | v4:73, :121 birth enumeration as code (U:340-343) plus META rows |
| 15 | PARTLY | tool committed (`canary-diag/tools/extract_keys.py`), but no sha is frozen, the dynamic sources are only declared in prose (v4:106), and it hard-codes the production checkout's scripts path (extract_keys.py:3); see H-4 |
| 16 | CLOSED | v4:6 provenance-limited; v4:34 "not supported"; v4:66 "most"; v4:194 and v4:235 drop the residual-A claim |
| 17 | PARTLY | orch:255-256 cite correct (v4:86); §4 cite fixed (v4:112); total order stated (v4:94); the choice-rule test is promised (v4:94) but not listed in H4 (v4:126-130), and the code precedence is unnamed |

## Findings
**H-1. BLOCKING. G1(3) "branch or worktree" plus §3a(1) "explicit --root" does not keep engineering off production.**
- FACT: CR is `git rev-parse --show-toplevel` of the cwd + CR_REL (s3 mech_prep:74-76; cm:32). `--root` does not override CR-anchored paths.
- FACT: the reader appends READ-LOG to `dio.s3.CR/ledger-p3b-r2/<run>/` (rd:102, :110) and reads the manifest and plan from CR (rd:156, :182). The state default and the orchestrator's `_under(..., CR)` guards also anchor on CR (orch:55, :383, :970). P3B-STATE.json and the manifest are tracked files in that checkout.
- INTERPRETATION: on a *branch in the production checkout*, a harness-reader call (needed for parity (ii)) or any default-root command writes or reads production paths. In a separate worktree or clone, CR resolves inside that copy, so everything stays isolated.
- FIX: in v4:228 replace "on a branch or worktree" with "in a separate git worktree or throw-away clone whose top level is not the production checkout". Add §3a(1a): "the cwd's git top level ≠ the production checkout, asserted before every command (the dispatch_root_check pattern, orch:316)".

**H-2. ENGINEERING-PHASE. SCRATCH_ROOT (pr:53) is a constant with no CLI flag, so §3a(1) "passes explicit … scratch paths" cannot be met without a code change.** FIX: §3a(1) should read "overridden (code change, RED-tested) or proven unused on the exercised path".

**H-3. MINOR. Status of the `test_plan_sha256` field after H1 (v4:91).** v4 does not say whether the agent's exact key set still contains the field. If it does, the agent must still emit an uncomputable value. FIX, one sentence: "the field leaves the agent key set by an annex row (listed under Changed, §4)", or "the agent omits it and the verifier fills its own report".

**H-4. ENGINEERING-PHASE. Run the extraction tool against the branch code, not production.** `extract_keys.py:3` fixes the production scripts path, so a run from a worktree diffs the production code instead of the branch code. Under H2 step 2, parameterise the path, add the dynamic sources (U:76-110, pr:88-158, f-strings) and record the tool sha at freeze.

**H-5. ENGINEERING-PHASE. The label-aware SEAL needs a new interface.** The witness check is a one-argument callable by design, so that the module never holds the hold-out lists (W:50-53; U:56). The change must stay a count-only common helper that takes (text, label); W must not receive the allowed-mention map. This preserves the H-19 guard and matches v4:118's intent.

**H-6. MINOR. §3a(2) "do not read … the hold-out sets" (v4:137) collides with tool-mediated count-only use.** The mandated quarantine scan (§7; cm:152-156, :211) and every default `files_meta()` call (cm:255-258) load the seal. FIX: "no person or agent reads or prints them; count-only common helpers are permitted for the quarantine scan; tests use the synthetic holder (V:115-117)".

**H-7. MINOR. Make the §3a(3) "exactly as specified" rule workable.** Points v4 leaves open (H-3, the claim-code precedence in G17, the later_* filtering, which silently drops agent assertions, v4:89) should go to G1.5 as table rows rather than be settled by builders. Add this as §3a(5).

**H-8. ENGINEERING-PHASE. Re-verifying retired attempts.** The new addendum sha (U:25, :86) means current code cannot re-verify a canary attempt under v2.8 (the U class: "restore and re-verify", R7:186). Keep the v2.8 verifier commit pinned for retired attempts.

## Task 2: new contradictions (frozen invariants, W8 seal, hold-out sets, quarantine, attempt semantics, §6 order)
- None found that blocks G1 apart from H-1, which is an isolation hole and not a design contradiction.
- The seal change narrows the exemption to Write content and keeps label names strict.
- Body identity holds under the rebind, because the annex lives in the addendum and changes only the header sha (act:218-219, :234-236).
- Attempt semantics: M = 4 and RR-7 per sha are unchanged.
- §6 order: rehearsal before rebind; G3 binds the rehearsed sha and commit.

## Task 3: G1 safety
Once H-1 is fixed (and H-3 and H-6 preferably), G1 is safe to approve:
- **It approves no artifact that does not yet exist.** Item (1) is an adjudication. Item (2) is a choice. Item (4) is the draft at v4:157-163, which exists. Item (5) is policy in principle, binding only at G3. The annex, brief, code and v2.9 sha are bound later, at G1.5, G2 and G3.
- **It touches no production state.** No dispatch, retire, rebind, state or manifest change is possible under it.
