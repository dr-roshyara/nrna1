# PKG-REVIEW-E: adversarial review of the S5 canary-evaluation decision package v2 (commit ce35975a6)

Read-only. Paths are relative to CR. "v2:N" = the target file line N; "D:N" = canary-diag/PKG-REVIEW-D.md line N. I opened no run output, slice, view, corpus file or archive.

## VERDICT: ACCEPT-WITH-FIXES
v2 closes D's findings almost entirely and is a clear improvement on v1. Fix before the human approves G1: the F1 sub-cause codes are made conditional on a path v2 itself does not recommend, the G2/G3 order puts the production rebind before the rehearsal that can send the design back, the R4 amendment is incomplete and too broad, and the resolver claim is an overclaim.

## 1. Closure of review D (findings 1-15 and the unreachable-keys table)
- D1 (counts, A 7 vs 5 of 11): closed (v2:34-43, both figures stated; arithmetic rechecked: 107, 140, 294, 46/42 all agree with D:11-12).
- D2 (F1 not splittable): **only partly closed**, see finding 1 below (v2:80).
- D3 (authority direction): closed in intent (v2:60, 72-76); the procedure is under-specified, see finding 3.
- D4 (unreachable keys): closed. All seven table rows are named (v2:19-25). The "named and confirmed" set (D:104: pair-evidence scale, `READ-PARTIAL`/`READ-FAILED`, `birth_kind`, `DEFINES`, `target_label`, the serializer) is covered only by "every key in D's list" (v2:76). Fix: define the annex coverage by the two-way test (v2:81), not by a list reference.
- D5 (ack/S3-LINT): closed for both, incl. R7:630 reword, FINAL_WRITES, `orchestrate.py:250-251`, golden hash, W8 (v2:68-69). Not listed: the test files D:36 names (`tests/r7_full_fixture.py`, `test_p3b_s5_r7_full.py`, `test_p3b_s5_r7_witness.py`). MINOR.
- D6 (verifier search): adopted (v2:70), but its consequences are not listed, see finding 2.
- D7: closed (v2:96). D8: closed for the choice, overclaimed in wording, see finding 5. D9: closed (counters v2:82, coverage v2:110-118); the "5-6 runs" estimate is unsupported (finding 7).
- D10 (R7:168 sameness): **partly closed**, see finding 4. D11: closed (G2 separate, v2:134); bind the code commit as well (finding 6).
- D12: closed (v2:91-93). D13: closed (v2:133). D14: closed (v2:45-50). D15: closed (v2:144).
- Unreachable-key rows that become moot under R1(c): the synthesis claim `run` (v2:23) and the claim-evidence shape. The `summary:<field>:<S>` row (v2:25) is left to the R2(b) adjudication, which is acceptable.
- Inconsistency: v2:24 says "a second, divergent POINTER regex"; v2:75 and D:26 say three (`p3b_s5_r5.py:462`, `p3b_s5_verify.py:108`, `p3b_s5_r7_reconstruction.py:34`). MINOR.

## 2. Findings

**1. MATERIAL. F1 sub-cause codes are conditional on the rejected path.** v2:80: "explicit failure ... (only if R1(c) is not adopted)". The codes in R3 are tied to the `claim-evidence.json` condition. But after R1(c) the claim check is still one existential with one message covering five distinct conditions: no fact of the claimed kind at the source, a field mismatch, a run outside the reading set, an unanchored quote, and an unmet whole-file requirement (`p3b_s5_r7_evidence.py:163-177`). The rehearsal pass criterion needs "every residual string reported with its sub-cause code" (v2:125) and "any X means return to design" (v2:127). Without codes the same blindness as D:22-23 returns for the repaired design. Fix: make per-condition codes unconditional and state them for the search form (for example NO-FACT-OF-KIND, FIELD-MISMATCH, UNANCHORED, NOT-WHOLE, RUN-NOT-READING). Keep the per-claim "candidates examined" count.

**2. MATERIAL (a). R1(c) does not weaken the constraints, but its audit trail and consequences are unspecified.**
- Constraint equivalence holds. The existing check requires run in the reading set, same source, same kind, all `fields` equal, anchored and whole where required (`evidence.py:163-175`). The agent's map only chose which fact; a valid fact is a valid fact. A claim cannot be satisfied by a fact of the wrong kind, source or fields. The anchoring (`_anchored`) and the quote rules stay in `facts_index`/`quote_violations` and are untouched. The d4/d5 claims carry a `quote` (`universe.py:370-371`) that `claim_violations` already ignores, so nothing is lost there.
- Weak spots that already existed but are no longer visible to anyone: `fields` is `{}` for timeline, d4/d5 claims (`universe.py:333-334`, `:370`), so any fact of the kind at that source satisfies the claim. Previously the agent's chosen ref was at least a trace of the support it relied on.
- Audit: no script in `scripts/` other than the r6/r7 verifiers reads `claim-evidence.json` (grep), so the §21 audit tooling loses no input. But a human auditor would be shown the verifier's match. "Prints the match" (v2:70) is not deterministic when several facts qualify. Fix: define the choice rule (all qualifying facts counted, the minimal (run, source, fact_id) printed), write it into the report, and test it.
- Not listed in v2 (D:47 asked for them): removal of `claim-evidence.json` from `FINAL_WRITES` (`universe.py:55`) and the prompt; the K-key (R7:247); the W6 wording "reconciliation of ... claim-evidence" (R7:69); R7:130 and R7:492; the test row T17 "claim-evidence repointed" (R7:743), which becomes untestable and must be re-expressed as "a fact on an unwitnessed page cannot satisfy a claim"; the residual write becoming W8.

**3. MATERIAL (b). R2 is not yet a finite, well-defined procedure.**
- "An independent drafter" (v2:73): independent of whom, with which inputs? The contract-first texts do not contain the values (D:49, v2:96: `ensure_ascii`, `hit_kind`/`term_index`, `SUPERSEDED`, the pointer phrases appear in none of R5, R6, the runbook). A drafter limited to rev3/R5/R6/R7 therefore cannot produce those rows. Every one becomes a "disagreement", and the human authors them, not adjudicates them.
- "Static key extraction" (v2:74) has no definition. Neither does the diff direction: keys in code but not in the draft (unreachable) and rules in the draft but not in code (`summary:`) are different cases.
- The output the human gets is unspecified. Fix: a closed table, one row per key, with the contract citation, the code behaviour (file:line), and three options (CONTRACT-WINS: code changes; CODE-WINS: the contract gains a rule; DROP). State a bound: one draft, one diff, one human pass, then the two-way test (v2:81) as the termination check, and a rule for rows the human adds.
- "CONTRACT-WINS" can change the S3 acceptance predicate (the POINTER regexes) beyond R1, so it belongs in the v2:91-93 "Changed" list.
- Timing gap: the annex-loading verifier (v2:75) cannot be engineered before the adjudication, yet the adjudication is placed after G1 and is not itself a listed act (v2:135). Add a G1.5 (adjudication), or state that G1 engineering of the annex loader is provisional.

**4. MATERIAL (c). R4: coherent with M=4 only in part, and the framing is wrong.**
- Arithmetic: counting toward M=4 leaves OB0012 attempts 3 and 4, and OB0114 attempts 2, 3 and 4. State it (v2:85).
- RR-7 (identical failure sets, class D; R7:190) is meaningless across the contract boundary: removing F2/F5 makes the attempt-3 set differ from attempt 2 by construction. State that RR-7 compares only consecutive attempts under the same bound contract sha.
- The amendment text touches only the sameness rule (R7:168). Two further bullets contradict the repair as written and are not named: "Repairs. Instrument repairs (runbook, prompt)" (R7:193 area) and "Policy (pre-registered, fixed before the canary)". The repair changes verifier and contract, not only runbook and prompt.
- Scope: v2:84 is generic ("A canary batch failed under an earlier contract version may ..."). It reads as a standing licence for post-failure contract changes in full S5. Fix: limit it to these two batches, once, bound to the reviewed v2.9 sha and the G-LOG that adopts it.
- Statistical discipline: yes, amending the retry rule after 11 of 11 canary runs failed is a post-results change. It is defensible only because (i) the canary is the designated repair gate, (ii) no label is measured and the audit is never consulted (R7 Blindness bullet), (iii) the estimation object stays "first accepted attempt", and (iv) every failure stays in E-3. Frame it as a disclosed **re-registration before S5 proper** (v2.9 supersedes v2.8 for the canary), not as a neutral "§2.8 amendment". Record the failed v2.8 attempts as such, state the W-SYS treatment as a pre-declared canary exception, and note that q* (0.12771, R7:201) was not calibrated to a population accepted under a different predicate.

**5. MATERIAL (d). "Real dispatch, witness, prompt and verifier code" (v2:104) is an overclaim as written.**
- The reader hard-codes `dio.discovery_resolver()` (`p3b_read_source.py:130`, `:222`); there is no injection point. A test resolver needs a shim, a patched copy, or a changed entry point, so the command the agent runs and the witnessed trailer are not the production ones unless the shim is byte-parity tested.
- The verifier takes the resolver as a parameter (`p3b_s5_verify.py:426`, with the seal and corpus checks `R7_SEAL_CHECK`, `R7_CORPUS_CHECK`), and the plan from `plan_derive_v7` must hash to the manifest entry's `r7_plan_sha256` (R7:116). The rehearsal therefore needs a synthetic manifest and plan too.
- The rehearsal validates: agent comprehension of the contract and annex, witness decoding of reader events (if trailers are identical), and the verifier's checks over consistent synthetic bytes. It does not validate: the reader, the seal and hold-out refusal, the identity-manifest pin, the real plan hash, the rebind, or behaviour on real texts (length, encodings). Real hit keys with non-ASCII text are what produced the escaped stage-2 ids, so synthetic sources must include them.
- Fix: say "real code except the content source, through a declared seam", list the non-validated items, require a parity test of reader output and trailer across both resolvers on one fixture, and add non-ASCII and size spread to the coverage list.

**6. MATERIAL (e). G1 scope and the G2/G3 order.**
- "Tests and copies only, no production mutation" (v2:133) is not separated from the files R1-R5 edit: `universe.py`, `p3b_s5_r7_verify.py`, `evidence.py`, `orchestrate.py`, the golden hash and the addendum are production paths. Define "copies" as a branch/worktree outside the active path, with no change to the activated `ADDENDUM_SHA256` (`universe.py:25`; enforced at `orchestrate.py:641`, `activate.py:213-218`) and none to the state file.
- The resolver seam (finding 5) may need a change to the production reader. If so, G1 does require production-path edits, even if only on a branch. Say so, or confine it to a harness copy.
- The order is wrong: G2 (production rebind) comes before G3 (rehearsal, v2:135-136), yet the rehearsal uses an isolated root, state copy and archive (v2:105) and its outcome "any X means a return to design" (v2:127). A failed rehearsal after the rebind leaves production bound to a rejected v2.9. Fix: rehearsal first, then the rebind, then the counted restart.
- G2 should bind the reviewed code commit as well as the text sha, because `rebind_contract` binds whatever bytes the constant names (`activate.py:217`) while the verifier behaviour is what changes the predicate.

**7. MINOR. Residual overclaims.**
- v2:13 and v2:7: "confirmed by A, B, C and D independently". Review D read A, B and C (D:3). Say "re-derived by B and D", as v2:32 does.
- v2:120: "about 5-6 runs" is not derived. A DECOMPOSED label alone needs two unit runs plus a synthesis run; with the hub, S1 YES and NO, binary, non-WHOLE-FILE and bait cases the count is likely higher. State the derivation or drop the number.
- v2:70 "The agent's claim map adds no constraint": true for validity, not for auditability (finding 2).
- v2:93 "No batch has been accepted yet, so no data is affected" is true, but the predicate also changes what the agent is told (annex), so even the first accepted population is not comparable to the canary attempts. State it with the R4 framing.

## 3. Verified correct (no action)
- R1(a): the ack check is not a W-rule input; dropping `evidence.py:138-141` weakens nothing, and the S3 check is re-run by the verifier on the same records (`p3b_s5_r7_verify.py` S3 call, then the binding at ~205-207).
- Quantification and the staged-act table reproduce D's figures; the recorded classes stay append-only (v2:54).
