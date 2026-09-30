# DIAG-REVIEW-B (adversarial review of DIAG-A)

Paths are relative to docs/knowledgeos/chronological-read (CR). Read-only. I read the two verify reports, the evaluation, the rev3 contract, the R7 addendum, the R5 runbook and R5/R6 by grep only, and the scripts/tests. I read no run outputs.

## VERDICT: NEEDS-REVISION
The core diagnosis holds: the counts reproduce and the schemas are unreachable. The following need fixing before it becomes the basis for repair or retry:
- F1 and F8 attribution is overstated.
- The repair analysis misses a cheaper design option for F2 and F5.
- The retry question is not addressed.

## 1. Counts reproduced (independent re-derivation)
- **Totals.** 195 and 145 strings. I recomputed the families from the `failures` lists.
- **Evaluation tallies.** Every family sums to the evaluation's per-predicate totals:
  - OB0012: R7-E 120 (98 F1, 16 F2, 6 F3); R7-R 58 (52 F4, 5 F5, 1 F6).
  - OB0114: R7-E 97 (72 + 25); R7-R 12.
- **X/A split.** 172/195 and 122/145 give 294/340 = 86.5%, as DIAG-A states.
  - The A strings are F3 (6), F7 (30), F9-F13 (about 17).
  - A touches 7 runs. I confirmed this from the label and run lists.
- **The 86.5% is an upper-end estimate.**
  - It contains F1 (170) and F8 (17) in full. That is 187 of the 294 X strings (64%), and neither can be split without run outputs.
  - The robust floor is F2 + F4 + F5 + F6 = 107 strings (31.5%). DIAG-A says so, but the headline should be the floor.

## 2. X families: absence confirmed, derivability nuanced
- **Zero-occurrence spot-check reproduced.**
  - rev3 and R7 contain 0 hits each for `ack_tokens`, `pair_evidence_checks`, `records_sha256`, `edge_class` and `R1-STRUCTURAL`.
  - rev3 also has 0 hits for `claim-evidence`, `file-reading-records`, `fact_id` and `reading_state`.
  - R7 names `claim-evidence` (7 hits), `file-reading-records` (2), `fact_id` (1), `reading_state` (1) and `S3-LINT` (3), but only as names or fragments (R7:246-247, R7:143, R7:492, R7:643). No file shape is given.
- **Agents partly inferred a schema. DIAG-A should say so.**
  - F3 strings (OB0012 L03, L05) show `facts` with `fact_id`, `quote` and anchoring present. Verification evaluated the quotes.
  - The fact-id styles differ across runs (`F8` and `<S>-F3`). So agents invented the container from R7 §3.3 and §6 ("fact quote").
  - F1 is therefore "under-specified, guessed, and then wrong". It is not "no schema at all".
  - This matters for the repair: the annex must pin exact names and shapes. An existence statement is not enough.
- **F1 (claim-evidence).** Confirmed X for its stage-2 part and probably all of it.
  - Claim-id grammar is at R7:330-341.
  - `hit_kind`, `hit_key` and `term_index` are undefined anywhere in R7 (R7:339).
  - The verifier builds the id with `json.dumps` defaults, i.e. `\uXXXX` escapes (`scripts/p3b_s5_r7_universe.py:360`). An agent cannot derive that.
  - Simple `timeline:S#` claims also fail, so the stage-2 serializer is not the whole cause. The likely cause is that `claim-evidence.json` has the wrong shape or is absent.
  - The 100% failure (FOUND = failing stage-2 claims) supports a systematic cause.
  - I cannot split out agent error (for example wrong fact kind or fields) without outputs.
- **F2 (ack_tokens).** The field is unreachable, so X is correct. A second point is material (M1 below).
  - "ACK = f(page_sha)" (R7:429).
  - §6 says: "Acknowledgement tokens: consistency check only; not evidence."
  - Yet a mismatch is a hard gate failure on 41 strings.
  - The verifier already has every witnessed token, and R7:92 says agent artifacts are untrusted.
- **F4, F6.** Confirmed unreachable.
  - F6: R7:13 and R7:892 do not list edge classes among the carried-over items.
  - F4: R7:892 carries over "S1" by name only.
- **F5.** Confirmed. See §5.
- **F8.** Medium-confidence X is right, but it is weakly attributed.
  - The rule is "⊆ verified cited sources" (R7:300-302). The term is undefined.
  - DIAG-A notes that it may be downstream of F1/F2 (the synthesis read unit records with no facts). If so it is not independent, and repairing F1 would shrink it.
  - Count it as "X-or-downstream".
- **F9 is classed A, but it is partly hidden-regex.**
  - The rs_id case is A (G-12).
  - The phrases 'register entry' and 'research register' are matched by the POINTER regex (`scripts/p3b_s5_verify.py:108-109`).
  - Neither phrase appears as a prohibition in rev3 or R7. R7:892 only says "normalized pointer matching".
  - The grep for both phrases in the contracts finds 'research register' only as legitimate positive vocabulary (rev3:348, 359, 360).
  - Two of the three F9 pairs are therefore under-specified. F9 should be "A for rs_id, X-light for the two phrases".
  - This does not change the order of the totals.
- **F7, F10, F11, F12.**
  - F7: A is confirmed (rev3 §D, §E and the §13.2 table).
  - F10 and F11: A is plausible.
  - F12: the prompt whitelist (`scripts/p3b_s5_r7_orchestrate.py:255-260`) says "Write tool" but does not name Edit. The A classification is right but thin.

## 3. Alternative explanation (agents did not read fully)
- **Not supported.**
  - RI-1b is COVERED for all 11 runs, with 0 uncovered lines (evaluation line 30).
  - Three agents themselves reported a missing schema. They are unverified, but they are consistent with the structural facts.
- **What RI-1b does not test.** It tests that the agent paged the slice view. It does not test whether the contract was read, or whether the contract reading was sufficient.
  - The contract and the addendum are read by the agent's Read tool, but no gate checks the contract reads.
  - This cannot produce the observed pattern: 11/11 failing on fields that are in no reachable text.
  - So "insufficient reading" is at most a contributor to the small A families.

## 4. Why every prior review and the synthetic suite missed it (meta)
- **Test fixtures use the verifier's own schema.**
  - Fixtures are authored from verifier code (for example `tests/test_p3b_s5_r6_verify.py:137`, `tests/test_p3b_s5_r5_verify.py:98`: `ack_tokens`, `facts`).
  - They build "agent output" from the verifier's field list. So they prove the verifier accepts its own schema, not that an agent could emit it.
- **The spec and the reader were never cross-checked.**
  - Earlier reviews checked that the verifier implements the contract (forward). Nobody checked that the verifier requires nothing beyond what the agent sees (reverse).
- **R7 introduced the reachability restriction only at W8 and I(run).**
  - R5 and R6 were driven by an orchestrator agent that had Bash and repo access, so addenda and `s3_lint` were reachable (runbook step 10, `prompts/20260926_2200_p3b-s5-r5-batch-runbook.md:37`).
  - R7 cut that off in W8, and the "by reference" carry-over (R7:13, R7:892) was never re-tested under the new restriction.
- **The missing test: an agent-reachability check.**
  - Static: extract every key the verifier reads on an agent-authored artifact (`get("...")` and `["..."]` in `scripts/p3b_s5_r7_*.py`, `p3b_s5_r6.py`, `p3b_s5_r5.py`, `p3b_s5_verify.py`), and require that each appears in the text of exactly the I(run) documents. Unknown keys fail. Also list every derived value (hash, id serializer) and require it be either derivable or not agent-produced.
  - Dynamic: a dry run with a cold agent on a toy label through the real dispatch. The restart canary was that dry run, which is why it was the first test.
- **Is this a necessary new instrument test before any retry? Yes.**
  - Recommend the static check plus a round-trip check: the annex is generated from the verifier's code, and fixtures are built from the annex text, not from the verifier.
  - The dry run has to be non-counting and on a toy label.

## 5. Repairs (criticism of DIAG-A section 4)
- **(M1) A schema annex is necessary but not the minimal repair for F2 and F5.** Some X strings should be removed by design, not documented:
  - `ack_tokens` (F2, 41 strings): a function of the witnessed page sha. Drop the verifier's `ack_tokens` equality check (`scripts/p3b_s5_r7_evidence.py:138-141`), or have the tool fill it. This is a verifier change and needs re-audit, but it removes the field rather than adding a rule agents must copy. An annex requirement would add an exact dedupe rule ("read at step 1 and again at step 7: list once") that agents could break in new ways.
  - `S3-LINT.records_sha256` (F5): the verifier already runs the S3 checks itself (`scripts/p3b_s5_r7_verify.py:205-207`, plus the S3 strings in both reports). The attestation adds nothing.
- **F5 options.**
  - An orchestrator-produced S3-LINT is possible. It is a contract-plus-universe change, not free:
    - remove `S3-LINT.json` from agent permitted writes (`scripts/p3b_s5_r7_universe.py:56`; R7:492);
    - amend R7:143 and the §0 trust table (R7:92).
    - The lint would then be post-hoc and not "pre-submit". That is harmless because the agents cannot run it anyway.
  - Either way the change is material. It alters the Universe permitted-writes set, so it needs a rebind and re-audit. It is cheaper than making agents hash.
  - Dropping the binding check outright is the lowest-surface option.
- **Annex versus I(run) categories.**
  - A self-contained annex in R7 is better than adding R5/R6 to I(run), for three reasons:
    - adding R5/R6 changes the Universe categories and so the audited universe;
    - R5/R6 contain superseded rules (for example RANGE5/RANGE6, replaced at R7:892), which agents could follow, and their edge-class text is contradicted by R7:13;
    - it would enlarge the W8 read surface.
  - **Conditions.** The annex must:
    - be generated from verifier code (one source of truth);
    - state every shape and every serializer exactly: `claim-evidence.json` dict shape; `json.dumps` default with `ensure_ascii` for `hit_key`; `hit_kind` and `term_index` semantics; fact-id uniqueness; value domains for `birth_kind` and `finding: DEFINES`;
    - state `edge_class`;
    - define "verified cited sources" (F8).
  - A cheaper alternative for the serializer: have the verifier match stage-2 claims on the parsed tuple. That is a verifier change and needs re-audit.
- **F12/F13.** A prompt line "Edit/MultiEdit forbidden; re-Write in full" (RR-6) is needed. It is cheap, and the prompt restating a rule is a pointer, not a new rule. It needs a rebind of the prompt.

## 6. Retry
- A retry after repair is sensible only as a non-counting schema rehearsal, then a counted canary.
- **Masking risk.** The A strings already touch 7 of 11 runs (threshold 6), and the X families mask whole layers that have never been exercised: quote anchoring for stage-2 claims, pair checks (S1 YES/NO with exact tokens), S3 layer-A phrases.
  - Expect new A strings in F1-F4 once those checks actually run.
  - A strict "≥ 6 of 11 fail" stop rule could fire again.
- **Pre-register the expected outcome.**
  - State which families must be zero (the X families) and which A rate is acceptable.
  - State whether the stop rule counts only residual A.
- **Independent of repair.** The F7-type omission (register `output_layer` in 2 runs) is an agent error that a one-line prompt pointer can reduce, but it cannot be guaranteed away.

## MATERIAL findings
1. M1 (`scripts/p3b_s5_r7_evidence.py:138-141`, `scripts/p3b_s5_r7_verify.py:205-207`; R7:429): two X families (F2 and F5, 46 strings) are agent-attested values that are a function of witnessed data. Prefer removal or tool-production over documenting them. DIAG-A offers the annex first and mentions the verifier-computes option only for F5.
2. M2 (DIAG-A sections 1, 2 and 5): the 86.5% headline and "100% stage-2 failed" rest on F1 and F8 (64% of X), which are attribution by inference. Report the 31.5% floor as the robust figure. Note that agents inferred part of the schema (F3), which changes the annex requirement from "exists" to "exact".
3. M3 (R7:339): `hit_kind`, `hit_key` and `term_index` are undefined, and the verifier serializer uses `ensure_ascii` (`scripts/p3b_s5_r7_universe.py:360`). An annex must fix them. A schema annex alone would miss this if it only describes file shapes.
4. M4 (`scripts/p3b_s5_verify.py:108-109`): F9 phrases 'register entry' and 'research register' are hidden in a regex. Reclassify the two phrase cases as under-specified.
5. M5 (meta): the reachability test is a necessary gate before any retry. Generate fixtures from the annex, not the verifier (`tests/test_p3b_s5_r6_verify.py:137`).
6. M6: the retry decision needs a pre-registered plan for residual A and for the stop-rule rule, because masked A failures are likely.
