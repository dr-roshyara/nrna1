# PKG-REVIEW-D: adversarial review of the S5 canary-evaluation decision package (commit 52c1ba687)

Read-only. Paths are relative to CR. I read the package, DIAG-A, DIAG-REVIEW-B, PKG-REVIEW-C, the evaluation, the two verify reports, rev3 (`prompts/20260925_1204_…-r2.md`; the brief's glob `20250925_1204_*` matches no file, see `universe.py:39`), R7, the R5/R6 addenda and the R5 runbook (grep only), `scripts/p3b_s5_r7_*.py`, `p3b_s5_verify.py`, `p3b_s5_r5.py`, `p3b_s5_r6.py`, `p3b_read_source.py`, `p3b_discovery_io.py`, `p3b_s3_mechanical_prep.py` and `scripts/tests/`. I opened nothing under `ledger-p3b-r2/`, no slice, view, corpus file or archive. Runs are written OB####-R7-L##.

## VERDICT: ACCEPT-WITH-FIXES
The X diagnosis and its counts survive falsification. The package must not be approved as one act in its current text, for four reasons: D1 is incomplete (the keys listed below); D2 cannot be run as specified, because the real reader cannot serve synthetic sources and its pass criterion cannot be decided from today's strings; D3 conflicts with R7 §2.8's definition of an attempt; and the rebind is authorized before the v2.9 text it binds exists.

## Findings

**1. MINOR. Counts confirmed; one derived statement does not hold.** I wrote 13 regexes from the string templates (F1 `^R7-E \S+: claim .* has no valid evidence path`; F2 `acknowledgement tokens ≠`; F4 `^R7-R S1 \S+: no pair-evidence check`; F5 `pre-submit lint report missing`; F6 `edge_class None`; F7 SCHEMA/G-05/G-09 `output_layer`; F8 `status_basis\.\S+\.sources\[\*\] names .* outside`; F9 G-12 / `R7-R S3 … register id or pointer`; F10 `births\.\w+=`; F11 `S-id range`; F12 `\(Edit\): W8`; F13 `W5 .*S3-LINT\.json differs`).
- They classify all 340 strings, each into exactly one family, with 0 unclassified. Per family (OB0012/OB0114): F1 98/72, F2 16/25, F3 6/0, F4 52/3, F5 5/4, F6 1/1, F7 15/15, F8 0/17, F9 0/6, F10–F13 1 each.
- 107 (31.5%), 294 (86.5%) and 46 are **confirmed**. 33 of the 94 stage-2 ids carry `\u` escapes (23/10), also confirmed.
- **"A strings touch 7 of 11 runs" (pkg:95) holds only for the lenient A = 46.**
  - The two phrase-only F9 cases are OB0114-R7-L01 and OB0114-R7-L05, the 4 strings that pkg:29 itself reclassifies.
  - Under the strict A = 42 that pkg:29 names, A touches **5 of 11** runs: OB0012-R7-L03, L04 and L05, plus OB0114-R7-L02 and L03S. That is below the ≥ 6 threshold.
  - Fix: state both figures in D3. The "can still stop on A" risk remains plausible, but it is not already met.

**2. MATERIAL. F1 cannot be split from its failure strings at all.** `claim_violations` emits one template (`evidence.py:177`) for eight distinct conditions (`evidence.py:163-173`): no refs (including an absent `claim-evidence.json`, which `p3b_s5_r7_verify.py:193` silently turns into `{}`), a run that is not a reading run, an unresolved fact, a source mismatch, a kind mismatch, a field mismatch, an unanchored quote, and an unmet whole-file requirement. What the strings do and do not show:
- **Provably X: 33 of 170 (19.4%).** These are the escaped stage-2 ids: the key can match only if the agent writes a literal `\uXXXX` sequence, which no I(run) text defines (R7:339).
- **X-likely but not provable: 61.** These are the other stage-2 ids. `hit_kind`/`hit_key`/`term_index` are undefined (R7:339), yet they are the agent's own disposition fields (`universe.py:360`), so an agent could in principle have copied them.
- **Undecidable: 76.** These are timeline 25, birth 21, absence 26, edge 2 and census 2. Their grammar is fully stated (R7:330-341).
- **What absent strings show.** There is no "≠ exactly one per permitted file", "missing or duplicate fact_id", "kind outside" or "missing field(s)" string. So the records and facts exist and are well formed, and F1 fails at the claim-map lookup or at field/anchor equality. With only 6 F3 strings, anchoring is not the general cause.
- **Consequence for D2/D3.** The D2 rule "X only when the string shows a shape or serializer mismatch" (pkg:86) and the X-attributed F1 stop in D3 (pkg:92) can never fire on today's strings: every F1 string would be classed A, and the defect would be hidden.
- **Fix.** D1 adds a sub-cause code to the F1 string, plus an explicit "claim-evidence.json absent/unparseable" failure at `p3b_s5_r7_verify.py:193`. The D2 regexes then key on those codes.

**3. MATERIAL. D1(b) inverts the authority direction R7 already established.** The registry and consumer tables are loaded **from** the frozen addendum "so the contract stays the sole authority" (`universe.py:4-6`, `:84-98`). Generating the annex "from the verifier code" (pkg:57) makes the code the authority. It would carry these code properties into the contract unexamined: three divergent POINTER regexes (`p3b_s5_r5.py:462`, `p3b_s5_verify.py:108`, `p3b_s5_r7_reconstruction.py:34`), the silent `or {}` at `p3b_s5_r7_verify.py:193`/`:205`, and the `ensure_ascii` serializer. It would also drop a contract rule that the code ignores: `summary:<field>:<S>` (R7:335) is never enumerated by `claims()` (`universe.py:331-378`). Fix:
- (a) Write the annex as a machine-read table between markers in the addendum, and have the verifier load field names and enums from it, as the REGISTRY is loaded.
- (b) Draft it contract-first: an independent reviewer writes it from the rev3/R5/R6/R7 rule text without reading the code. It is then diffed against a static key extraction, and the human adjudicates every disagreement.
- (c) Make the reachability test two-way: every verifier key or enum ⊆ annex, and every annex row has a check that a mutation test proves.

**4. MATERIAL. D1(b) omits unreachable keys and value rules.** See the separate section below. Without them a repaired canary can fail on the S1 NO path, LIFECYCLE and CONTRADICTION claims, the synthesis claim references, and the S3 pointer phrases.

**5. MINOR. D1(a) is sound; name the test and witness changes.**
- **Ack tokens.** W3's acknowledgement check is on the reader trailer (`witness.py:383`) and on sha recomputation, not on the agent field. WHOLE-FILE coverage is witnessed independently (`evidence.py:136`). Dropping `evidence.py:138-141` therefore weakens no W-rule. R7:630 ("consistency check only") must be reworded, or it describes a check that no longer exists.
- **S3-LINT.** S3 is re-run by the verifier on the same records (`verify.py`(R7):176). The assembly bytes stay bound by W7 and T129 (R7:855). Dropping `:205-207` loses nothing.
- **No R7 test currently pins either negative path.** `ack_tokens` and S3-LINT appear only as valid fixture values (`tests/r7_full_fixture.py:177`, `:223-224` with an unused `lint_override`; `tests/test_p3b_s5_r7_full.py:448`; `tests/test_p3b_s5_r7_witness.py:36-37`). There are no R7 T-rows for them.
- **What changes when S3-LINT is removed from the writes.** `FINAL_WRITES` (`universe.py:55-56`) and `permitted_writes` (`:696`), the prompt write list (`orchestrate.py:250-251`), the golden prompt hash (`tests/test_p3b_s5_r7_hub_required.py:305`), and W8 `_classify` (`witness.py:254ff`): a residual S3-LINT Write becomes W8. W5 (`witness.py:341`) is generic per file and needs no change.
- Fix: list these files in D1, and add RED tests for "ack field absent → no R7-E" and "S3-LINT written → W8".

**6. MATERIAL. A cheaper and stronger F1 repair is not considered.** By the same logic as D1(a): the agent-authored claim map adds no constraint beyond "∃ a valid fact", because the agent may cite any fact it has. Validity = run ∈ reading, kind, source, fields, anchor and whole-file (`evidence.py:165-175`), and semantic support is already a human-review boundary (`evidence.py:9-10`).

Letting the verifier search `facts_index` for a valid fact:
- removes `claim-evidence.json`, the stage-2 serializer trap and the synthesis-ref ambiguity;
- removes most of F1's X surface;
- shrinks the annex.

The verifier can still print the matched (run, source, fact) for audit. Weigh it against the parsed-tuple option (pkg:60). It is a contract change of the same class as D1(a): a K-key and a permitted write are removed.

**7. MINOR. The rejection of "R5/R6 in I(run)" (pkg:65), and of verbatim excerpts, misses the decisive reason.** R5, R6 and the runbook contain **none** of `ensure_ascii`, `json(`, a definition of `hit_kind`/`term_index`, "verified cited", the value `SUPERSEDED`, or the phrases 'register entry' and 'research register'. So neither alternative could close F1-stage2, F8, F9 or LIFECYCLE. `ack_tokens` and `records_sha256` are only in the runbook, not in the addenda, so adding the addenda would not even close F2/F5. Fix: add this as the first rejection reason. The excerpt option then reduces to authoring an annex anyway.

**8. MATERIAL. D2 cannot run "the real reader … no corpus source" (pkg:79).**
- **Where content comes from.** The reader resolves CR from `git rev-parse --show-toplevel` of the cwd (`p3b_s3_mechanical_prep.py:75-76`), so the throw-away repository does redirect the ledger and manifest. But the content comes from `ContentResolver.from_manifest`, which demands the pinned identity-manifest body sha (`:69`, `:270-275`). It reads blobs via `git -C REPO_ROOT cat-file --batch` (`:234`), and the throw-away repository holds no corpus blobs. It also needs the seal and its hold-out hash (`p3b_discovery_io.py:45-55`).
- **What follows.**
  - Synthetic sources cannot be read by the real reader.
  - Real sources in a copy repository need object alternates and reintroduce corpus and seal exposure.
  - The verifier's resolver (`p3b_s5_verify.py:426`, `c.dio.discovery_resolver`) and `plan_derive_v7` have the same dependency.
- **The precedent is weaker than cited.** `canary_precheck_v28.py` only ever ran `prepare` and `render_prompt`. It called no reader or verifier, and it passed the **production** `P3B-STATE.json` (`:48`).
- **Fix.** Before D2 is approvable, choose explicitly between two options:
  - (a) a declared test resolver (then it is not the real reader, and that must be stated), or
  - (b) real non-frame, non-hold-out sources with object alternates (then the "no corpus" claim is false, and a seal analysis is needed).

  Also add a separate archive root (`archive_root`, `verify.py`(R7):122) and a state copy.

**9. MATERIAL. The D2 pass criterion cannot be decided, and the coverage list is incomplete.**
- **"Every family's check was reached" (pkg:85) is not observable.** The verifier emits failures only, with no count of evaluated items. Fix: add per-check evaluated-item counters to the report (a verifier change inside D1).
- **(ii) (pkg:82) omits required exercises:** BIRTH, CONTRADICTION, LIFECYCLE, d4/d5 and CENSUS claims; an S1 **NO** check (the VEC register record plus the OTHER escalation); a decided binary file (§6.1); a hub run; a non-WHOLE-FILE state with CONTRACT-DEVIATION; and layer-A pointer bait for F9.
- **Arithmetic.** "About 4 runs (2 + 2 + 1)" is 5.

**10. MATERIAL. D3 conflicts with R7 §2.8.** An attempt m+1 must use the "same plan, slices, views, run ids, **prompts** and record identity" (R7:168). D1(c) changes the prompt, and D1(a)/(b) change the addendum: an I(run) input whose sha is in each frozen input manifest (`verify.py`(R7):101-111). So "OB0012 attempt 3 / OB0114 attempt 2" (pkg:90) is not an attempt in §2.8's sense. RR-6 (R7:193) admits only runbook and prompt repairs, and gives no exemption from that sameness requirement. Fix: the human act must either amend §2.8 for post-gate canary attempts (with the M-count and estimation-object consequences stated) or define the post-D1 restart as a new activation.

**11. MATERIAL. The rebind is authorized in the same act as the unwritten v2.9 (pkg:107).** `rebind_contract` binds whatever bytes `U.ADDENDUM_SHA256` names (`activate.py:217`). Approving it before the v2.9 text and its A/B review exist lets engineering accept its own work (EP-02, R-34). Fix: make the rebind a separate one-line act that cites the reviewed v2.9 sha.

**12. MINOR. "Unchanged … the evidence is the rebind's body check" (pkg:72) overreaches.**
- The body and batch identity (`activate.py:247-255`) covers the manifest entries: frame, sample and plans.
- It does not cover the estimands (EP-01) or the acceptance predicate. D1 changes BATCH-PASS itself, and therefore the accepted population that θ_D and θ_A describe (R7:192, :194).
- Fix: say "frame and sample hashes unchanged; estimand text unchanged; acceptance predicate changed (listed checks)".

**13. MINOR. W-SYS and the extra stop condition.**
- The reading of W-SYS (pkg:93) is defensible: retried canary attempts are not "post-canary" (R7:200). The human should also state whether the canary batches' accepted attempts later enter estimation.
- The extra stop condition (pkg:92) only adds stops, which is monotone. It is still inoperable until finding 2 is fixed.
- Freeze 1/2, the threshold and the recorded classes are not otherwise altered. The append-only records stay untouched (pkg:39).

**14. MINOR. Alternative explanations (task 9), with discriminating evidence that needs no outputs.**
- **Line truncation is excluded.** The longest lines are 1537 characters (rev3) and 1469 (R7), both under the 2000-character Read cutoff.
- **Context pressure does not explain the X families.** They are independent of reading volume. OB0114-R7-L02 (about 15 KB read) and OB0012-R7-L05 (about 37 KB) carry F2, F5 and F6 strings like the 300 KB runs do (`reading_bytes` in both reports).
- **Contract read coverage is not gated.** RI-1b covers the SLICE-VIEW only (R7:610-612). An RI-1b-style coverage over the CONTRACT/ADDENDUM `read-input` events in the frozen witness (`witness.py:299`) would separate "did not read" from "read, but the field is absent". It can be computed by an authorized session; I did not open it.
- **Model behaviour** explains only the guessed shapes (the F3 id styles). It cannot produce fields that appear in no I(run) text.

**15. MINOR. Hygiene.** The stage-2 claim ids inside the "evidence" verify reports embed verbatim source text (`hit_key`), so "counts from failure strings" still exposes corpus excerpts. Future packages should cite masked templates only.

## Unreachable keys not named in the package
Each is read on an agent-authored artifact. Each is absent from rev3 and R7, or present only as a name or field without its required value or format.

| Key or rule | Read at | I(run) status |
|---|---|---|
| S1 NO path: escalation `reason == "OTHER"` with `detail` matching `^VERDICT-EVIDENCE-CONFLICT: RP\d+`; register record `topics ∋ VERDICT-EVIDENCE-CONFLICT` naming the pair token | `p3b_s5_r6.py:270-271`, `:282` | the format is in the R5/R6 addenda only; R7:892 "register + OTHER escalation" names it; rev3:694 has prose only |
| LIFECYCLE fact `lifecycle == "SUPERSEDED"` | `universe.py:351`; `evidence.py:170` | R7 gives the field name only (`lifecycle`) |
| CONTRADICTION fact `contradicts_source == <T>`; CENSUS `dimension == <dim>` | `universe.py:347`, `:374`; `evidence.py:170` | field names only; the equality rule is unstated |
| TIMELINE `change_candidate`, TWO-CONCEPT `concepts`, OMQ14 `type` must be truthy (no value domain) | `evidence.py:24-26`, `:151` | field names only |
| claim-evidence `run` of a SYNTHESIS claim = the owning UNIT run (the synthesis id fails) | `verify.py`(R7):171; `evidence.py:168` | R7:130 says "the dispatched run id", which a synthesis agent reads as its own: ambiguous or contradictory |
| layer-A pointer patterns of the S3 check: `rs[-_ ]?id`, `research[- ]register`, `cf. register` (POINTER7), and G-12's `register (OBSERVATION\|GAP\|…)` | `reconstruction.py:34`; `p3b_s5_verify.py:108-109` | pkg:63 cites only `verify.py:108`; the two regexes differ |
| `summary:<field>:<S>` claim id (reverse direction: in the contract, never generated by the code) | `universe.py:331-378` | R7:335 |

Named and confirmed (not repeated here): `ack_tokens`; `pair_evidence_checks` with its scale (`NOT-DETERMINABLE` is absent); `records_sha256`; `edge_class`/`R1-STRUCTURAL`/`R2-EVIDENCED`; the `reading_state` values `READ-PARTIAL`/`READ-FAILED`; the shape of `claim-evidence.json`; the `json(hit_key)` serializer; "verified cited sources"; `birth_kind`; `finding: DEFINES`; `target_label`.
