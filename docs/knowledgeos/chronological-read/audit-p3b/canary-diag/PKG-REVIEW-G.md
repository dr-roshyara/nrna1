# PKG-REVIEW-G: adversarial review of the S5 canary decision package v3 (commit 8e095b780)

STATUS: COMPLETE

Read-only, Gate Mode. "v3:N" = the target file, line N. Code paths are relative to CR/scripts and use the FIELD-INVENTORY legend (VR7, U, W, E, C, V, cm, pr, orch), plus `act` = `p3b_s5_r7_activate.py` and `rd` = `p3b_read_source.py`. I opened no run output, slice, view, ledger, corpus file or archive, and ran no corpus-byte scan. No text in the sources claimed user authority.

## VERDICT: ACCEPT-WITH-FIXES
Fork H and the act order hold up. But four H1/H3 mechanisms are under-specified: stamping at assembly, precedence via the plan or slice, the test-plan file, and the seal scope. As written, each either contradicts a frozen invariant or opens a security or attempt-semantics gap. The §5 pass criterion is partly vacuous and not mechanically decidable. These must be fixed before G1 item (3) is approved.

## Task 1: closure (FACT)
- E1 closed (v3:89). E2 mostly closed (v3:88-95), but the "test the choice rule" part of E2 is missing from H4 (v3:118-122); see 17. E3 partly closed: "independent of whom" is still open (v3:99), and the extraction tool is not an artifact (finding 15). E4 closed, apart from R7:168 and the scope split (finding 8). E5 closed in wording; the parity test is not executable (finding 9). E6 closed (v3:192-195). E7 closed (v3:13, v3:179, v3:142).
- F1, F2, F3, F7, F8, F10 and F11 are closed (v3:32, :47, :41/:112, :114, :48, :49, :124). F4, F5, F6 and F9 were left unapplied by F itself; that is acceptable. **F's open point is not closed:** the annex's location. A new addendum sha versus a new I(run) category, given the closed enum in R7 §5.8 (INVENTORY-REVIEW-F:148). v3:105 says only "loaded like the REGISTRY" (finding 13).

## Findings

**1. MATERIAL. H1 "contract/manifest shas stamped by the orchestrator at assembly" (v3:83) breaks a frozen invariant.**
- FACT: R7:141 and orch:622-623 both say the assembly "never edits, filters, renumbers or repairs a record".
- FACT: the rev3 §E exact key set requires the fields (V:478, 491-501). G-07 checks them on OBJ, REG and GAP (V:511-513, 955, 990).
- FIX: never mutate at assembly. Either remove the fields from the agent schema through an annex row that changes the exact key set (and list it under "Changed", v3:146), or keep EXPOSE ("copy both from the slice fields", inventory row 3).

**2. MATERIAL. Precedence/`position` "derived from 02-FILES/plan data" (v3:84): the plan/slice route cannot be taken.**
- FACT: `rebind_contract` keeps the entry lines, slices and plans byte-identical (act:205-208). R7:168 requires the "same plan, slices". META_FIELDS lacks `order_evidence` (pr:55-56).
- FACT: the verifier-side route is available (files_meta V:310; C:39-59).
- FACT: `dated_position` also depends on the agent's `date_applies_to_file` (C:45), so precedence is not wholly tool-knowable.
- INTERPRETATION: `later_*` membership remains an agent assertion whose qualifying test the agent cannot see.
- `edge_class` is derivable, but from the edge's own quote (row 14), not from 02-FILES.
- FIX: fix the verifier-side derivation. State the agent's residual obligation (list candidates, or omit lateness) and that `date_applies_to_file` stays attested. Delete "or validated as derived" (it leaves the choice undecided). Correct the edge_class wording.

**3. MATERIAL. `test_plan_sha256` through a new declared file (v3:85) adds risk where none is needed.**
- FACT: it is a new permitted Write (U:55-56, :696 → prompt orch:255-256 → golden hash).
- FACT: the file sits outside the S3 scan set, `final_records` = object + register + gap (VR7:175-176), so its free text gets only the W8 SEAL check.
- FACT: nothing checks the file text against the record's test-plan fields (rev3:1030-1036). That is a new unchecked rule (R-class).
- INTERPRETATION: a hash the tool computes after the run attests no pre-registration order.
- FIX: take the inventory's own remedy (row 74): the verifier hashes the canonical JSON of the test-plan fields already in the register record. No new file. Otherwise list the S3 coverage, the consistency check and the W8 consequences.

**4. MATERIAL. The seal fix (v3:112) has no stated scope; applied to all calls it weakens W8.**
- FACT: W8 SEAL checks every tool input (W:263-266), including reader Bash commands and Read paths. `quarantine_hits` masks only allowed S-ids; hold-out label names stay strict (cm:213-217).
- INTERPRETATION: applied to all calls, a reader call that names an allowed hold-out S-id stops being a batch STOP. Only the reader's own refusal remains (rd:15).
- FIX: make SEAL label-aware only for the content of a Write to a permitted path; Bash, Read and other inputs keep label=None; an unknown owner stays strict (W:256). RED tests: an allowed token in a Write → no stop; the same token in a reader command → STOP; a non-allowed hold-out S-id, or any hold-out label name, in a Write → STOP.

**5. MATERIAL (security precondition). The `hit_key` META row (v3:113) is safe only under conditions it does not state.**
- FIX: exempt `hit_key` from typing iff it is byte-equal to a stage-1 key of the same label's slice (G-04 equality, V:698-700); otherwise it stays R7-U. RED test: a non-copied `hit_key` that carries an S-id → R7-U.
- State that META typing leaves the S3 quarantine (VR7:176) and W8 SEAL (W:265) untouched. They are separate paths today; nothing in the package requires that to remain so.

**6. MATERIAL. Parse robustness (v3:114) is scoped too broadly.**
- FACT: `_jl`/`_js` (VR7:55-66) also read tool artifacts: the plan (:90), INPUT-MANIFESTS (:95), R7-PROVENANCE (:129), WITNESS-DIGESTS (:139, :146) and READ-LOG (:157).
- INTERPRETATION: a blanket BATCH-FAIL turns tool corruption or tampering into a retirable failure (R7:186-189) that consumes M = 4.
- FIX: BATCH-FAIL with a code only for agent-authored files. Tool artifacts stay Refused/S.

**7. MATERIAL. The new failure strings fall outside the frozen cause mapping.**
- FACT: R7:186: "unrecognized → D".
- The H1 per-claim codes (v3:89), the H3 parse code and the changed W8 strings are all new to that mapping.
- INTERPRETATION: the counted canary would class them D and stop, which defeats §4.
- FIX: put the mapping extension into the v2.9 text and the §4 "Changed" list (v3:146).

**8. MATERIAL. §4 scope: the retry licence is canary-only, but the re-registration is S5-wide.**
- FACT: v3:137 limits the licence to OB0012 and OB0114, once. But the changed predicate (v3:146) and W-SYS's q* (R7:200-201) govern every post-canary attempt. v3:141 discloses q* only as a canary matter.
- FACT: R7:168 ("same … prompts") is not among the amended bullets (v3:138).
- INTERPRETATION: the "accepted at attempt 1" exploratory domain (R7 Reporting) will exclude the canary labels for instrument reasons.
- FIX: split §4 into (a) the canary retry exception and (b) the S5-wide predicate re-registration. State that q* is kept or recalibrated before G5; keeping it is defensible because W-SYS is execution-only (R7:199), but it must be stated. Amend R7:168. Annotate the attempt cross-tab.

**9. MATERIAL. The §5 parity test (v3:152) cannot be run as stated.**
- FACT: the production resolver serves only the real corpus (rd:130, :222).
- "One fixture under both resolvers" therefore needs either a corpus read (§22; no act in §6 authorizes one) or the production resolver pointed at synthetic data. The second is the seam itself, so the test proves nothing.
- FACT: the harness reader path also enters the prompt (orch:248) and the witness classifier (noncanonical-reader branch, W:276-279).
- FIX: (a) a byte diff of the harness copy against `rd`, limited to the resolver line; (b) both resolver code paths over a synthetic store; (c) declare prompt and classifier parity, or list them as not validated.

**10. MATERIAL. The pass criterion (v3:181-186) is partly vacuous and not mechanically decidable.**
- (a) H1 removes the checks behind F2 (E:138-141) and F5 (VR7:205-207), and replaces F1's message (E:176). Their regexes therefore cannot match, and only the F4 regex stays meaningful. The same holds for the G4 early stop (v3:196).
- (b) Whether a residual string is X or A has no frozen rule, yet "any X → return to design" (v3:186). (c) No run is required to reach BATCH-PASS, and there is no A threshold: a rehearsal with 0 accepted runs passes, and G4 then spends attempts under M = 4.
- FIX: pre-register the class of each new code. Restate the X families as the new verifier's code sets. Require at least one synthetic batch to reach BATCH-PASS end to end, plus either a pre-registered A threshold or an explicit "G4 may fail" statement.

**11. MINOR. Coverage gaps (v3:167-177):**
- Missing cases: `hit_key` with an S-id (23); NOT-CONSUMED-ESCALATED (28); a file with `order_evidence` = SOURCE_ID (44/45); R1/R2 derivation (14); F8 `status_basis` in SYNTHESIS; negative seal controls (finding 4); an EMPTY label.
- Missing from "does not validate" (v3:160-165): the real hold-out sets (the synthetic holder, V:115-117); the cause mapping; retire and attempts on production state; the production golden prompts; the LOAD prediction.

**12. MATERIAL. G1 "branch or worktree" (v3:192) does not isolate everything.**
- FACT: DEFAULT_ARCHIVE `~/knowledgeos-witness-archive` (orch:57) is used whenever `--archive` is omitted (orch:382, 409, 969). SCRATCH_ROOT is a fixed `/tmp` path (pr:53).
- FACT: builders deriving from 02-FILES or the hold-out sets repeat the v3:8 deviation (§22).
- FIX: G1 conditions: explicit non-production `--root`/`--archive`/scratch; synthetic fixtures only; no corpus, 02-FILES or hold-out reads without a human act.

**13. MATERIAL. Acts approve things not yet fixed.**
- (a) G3 (v3:195) must bind the text sha and code commit **that the rehearsal ran**, or require a re-rehearsal on any diff. (b) `rebind_contract` binds only `ADDENDUM_SHA256` (act:218-219); the annex must live inside the addendum or it is unbound; decide this in H2.
- (c) The rehearsal brief (coverage, run count, criterion) does not exist at G1; G2 must approve the frozen brief by sha. (d) "Return to design" (v3:186) names no re-entry act or bound. G1(5) is acceptable only as policy in principle, binding at G3 (§22).

**14. MATERIAL. Fork fairness: the H-over-R residual is not closed by the design.**
- v3:68 says rows 18 and 22 are closable "within H by registry rows".
- FACT: row 18 is claim enumeration (U:340-343 `SID.findall` over births values), not typing. A registry row cannot stop claim generation; F classed it REDESIGN (inventory row 18).
- FACT: H3 adds no row for 18 or 22 (v3:110-116).
- FIX: add an H3 code item (enumerate only `birth_basis()`) plus META rows for the row-22 fields, or concede this residual to R.

**15. MATERIAL. The H2 termination check depends on a tool that does not exist as an artifact.**
- FACT: "the inventory's AST tool, frozen" (v3:100) is a scratchpad script (FIELD-INVENTORY:182), and `git ls-files` lists none. The AST misses dynamic keys (FIELD-INVENTORY:198), so the two-way test (v3:120) cannot be complete.
- FIX: commit the tool, add the dynamic key sources (U:76-110, pr:88-158, f-string keys) as declared inputs, and name who the drafter is independent of.

**16. MINOR. Overclaims.**
- v3:61 "removes the whole … class": precedence keeps an attested input (finding 2), and the test-plan file adds an attestation (finding 3). v3:199 "measure the residual A rate" contradicts v3:165; synthetic A rates do not transfer.
- v3:29 "Ruled out": D §14 (PKG-REVIEW-D:83) offers discriminating evidence, not a test; say "not supported". v3:47-49 rest on scans that §22 now forbids; mark them provenance-limited.

**17. MINOR. Precision.**
- v3:81 cites orch:250-251, which is the reader file list. The write list is orch:255-256, rendered from `permitted_writes`, so removing the entry from FINAL_WRITES is enough. v3:106 "(§6)" should read §4 (v3:146).
- "Minimal (run, source, fact_id)" (v3:88) needs a total order (`fact_id` may be non-string, row 68; use canonical JSON), and a code precedence when candidates fail differently. Add the choice-rule test to H4.

## Verified correct
- The §1 counts match B, D and F (107/140/294/46/42; 48/19/10/8; 0 of 2,734; 167 of 1,167; 15 and 2 labels).
- Only two POINTER regexes are live on the R7 path. `r5.s3_lint` is called only at r5:551; R7 uses `C.s3_violations` (VR7:176). So v3:106 is right.
- Dropping ack (E:138-141, a consistency check only) and S3-LINT (S3 re-runs at VR7:176) weakens nothing. The claim search keeps every constraint (E:163-177).
- M = 4 arithmetic, RR-7 per bound sha, and the R7:184/193/200/201 citations are accurate. The estimation object is unchanged.
- The rebind accepts FAILED batches (act:234-236), so G3 before the G4 retire is executable. Body identity (act:205-208) supports v3:145, provided finding 2 is fixed.
- Rehearsal before rebind, binding the code commit (v3:195), and G1.5 (v3:193) are sound in order.
