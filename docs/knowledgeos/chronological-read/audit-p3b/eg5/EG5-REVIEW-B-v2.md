# EG-5 / EG-5b / EG-5c adversarial re-review, v2 (Subagent B)

| | |
|---|---|
| Kind | Independent adversarial re-review of `EG5-SPEC-A-v2.md`, responding to `EG5-REVIEW-B.md` (v1 round). Read-only, synthetic fixtures only. |
| Method | Ran `probes/probe_eg5b_options.py` myself and read it line-by-line for what it actually tests. Wrote three of my own supplementary probes (`probe_overflow_novacuous.py`, `probe_eg5c_counts.py`, and a trace through `r7_execution_fixture.build` / `test_p3b_s5_verify.write`) to check claims the given probe asserts but does not itself test. Re-derived every code citation independently (G-07, claim_violations, S3 rsid regex, `T.write`). |

## 0. Did v1's findings get fixed?

**B-01 (EG-5b blast radius): fixed, correctly.** v2 §1 now states the universal failure mode plainly ("every label that has a final run fails... both canary batches... in practice all 396 batches") and I re-ran the exact single-label, zero-collision probes myself (`dispatched id in object only` → `BATCH-FAIL {'G-07 <id>: provenance ids': 1}`; `dispatched id in register only` → `BATCH-FAIL` with 5×`rs_id not <run_id>:<batch_id>:<n>` + 5×`provenance ids`). Matches v2's claim and my own v1 probes. Agree, closed.

**B-02 (provenance vs §18): substantially addressed.** v2 §3's "model↔run binding" argument — under option (a), the whole batch's records including the EMPTY object share `run_id = <B>-R7`, and if that run is single-model (R10 enforces it), then `model_id` on the EMPTY object genuinely names *a* model of that run and the "different model ⇒ different run" sentence isn't violated in the way v1's B-02 worried about — is a real resolution, not a rationalization. See C-02 below for a residual gap in how R10 is anchored.

**B-03 (θ_D on EMPTY): substantially addressed**, with one nuance A's v2 doesn't cover (C-03 below): the hub-EMPTY label's guaranteed-zero disagreement is diluted anonymously into the *sampled* HUB stratum's own θ_D, not just the *census* EMPTY stratum's, and the reporting rule HD-5 proposes only names the latter.

---

## C-01 — MATERIAL — EG-5c is not a hypothetical risk the tool prevents; it is a gap that is *already inside the accepted verifier and the accepted T38 positive control today*, and the proposed fix (adjust the fixture) would hide that fact rather than record it

**Task question asked:** *"Is an assembly-side refusal enough, or is this a verifier weakness that must be recorded as a finding (not fixed now)?"* — I traced this to ground truth and the answer is: **it is a verifier weakness, it predates this whole spec, and it is currently masked by T38 itself, not merely "a risk the new tool must guard against."**

**What I found, independent of the probe.** `probe_eg5b_options.py` never checks EG-5c directly, so I wrote my own trace (`probe_eg5c_counts.py`):

- The synthetic EMPTY label (`rejection-produces-hypothesis-space-not-truth`) carries **3 register + 3 P1-gap records** in `ctx["reg"]`/`ctx["gap"]` (confirmed: A's count is right).
- `plan["labels"][EMPTY_LAB]["runs"] == {}` — EMPTY has **no run at all**, confirmed (`R6:86-103`, unchanged from v1).
- `run_outputs(st)` (`tests/r7_full_fixture.py:207-226`), which is what a *real* per-run-then-assemble pipeline would write, **never emits any file for the EMPTY label** (its keys are only the 15 real run ids: `L01S`, `L01U01..11`, `L03`, `L04`, `L05` — no `<B>-R7`, no EMPTY-specific entry). If EG-5c's smuggling scenario depended only on `run_outputs`, it could never happen, because nothing ever writes the EMPTY label's inherited register/gap records anywhere.
- But `T38` does **not** go through `run_outputs` for the *assembled* files. `FX.verify()` first calls `test_p3b_s5_verify.write(root, [ctx], ...)` (`tests/test_p3b_s5_verify.py:142-169`), which writes `ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl` **directly from the full, unfiltered `ctx["objs"]`/`ctx["reg"]`/`ctx["gap"]` lists** — i.e., from a data structure that still contains the EMPTY label's 3+3 inherited records, with no `assemble` tool and no R3′-equivalent filter anywhere in between. `xf.build()` only adds the *per-run* directories and a no-op `ASSEMBLED`/`FINAL-VALIDATED` marker pair (`ls`→`ok`, `true`→`ok`) afterward; it does not touch `<B>-R7/`.
- I reran `test_T38_single_decomposed_empty_batch_passes`: **OK.**

**Conclusion.** T38 — a member of the *already-accepted* acceptance matrix (G-LOG-0090/91/92) — currently exercises exactly the EG-5c scenario (register/gap records present under an EMPTY label's `working_label` in the assembled files) and it passes today, with no tool in the loop at all. This means the R7 verifier's *acceptance*, as it stands, already tolerates this. It is not a new risk introduced by the proposed `assemble` tool; the tool's R3′ is a **new, additional** constraint that a *future compliant assembly* would have to satisfy, but it does nothing to the verifier that already grants BATCH-PASS to a batch built by any other means (by hand, by a different tool, by `T.write`-style direct construction) with the same defect.

**Why A's proposed fix (§5(3): "make_empty also drops the EMPTY label's register and gap records... T38 must still pass") is the wrong shape of fix, even though it's a reasonable engineering step.** Silently sanitizing the fixture converts a *currently-passing test that (accidentally) proves the verifier's blind spot* into a fixture that never exercises the blind spot at all. After that change, the **only** thing standing between a mislabeled register/gap record and BATCH-PASS is (a) trust in the specific `assemble` tool implementing R3′ correctly, and (b) trust that nothing else ever writes `<B>-R7/register.jsonl`/`p1-gap-capture.jsonl` by another path — both of which the addendum's own trust table (`A:92`) already places in the "trusted, not verified" column for the orchestrator. That may be an acceptable risk *given* the stated trust boundary, but it should be an **explicit, recorded decision**, not an implicit consequence of quietly editing a test fixture.

**Recommendation.**
1. Keep a regression test that demonstrates the verifier's blind spot **without** the tool (e.g. a renamed/isolated variant of what T38 currently does by accident) so the acceptance matrix continues to document, rather than erase, the fact that `RC.empty_violations`/S3/G-02 do not check "an EMPTY label's register/gap groups are empty." A's own T119 tests the *tool's* refusal; nothing in the RED list tests that the *verifier alone*, given a hand-assembled batch, still accepts a mislabeled record under an EMPTY label — that is precisely the residual-risk fact governance needs on record.
2. Promote "no verifier rule: EMPTY ⇒ no register/gap records" out of §8's bundled-defaults list (currently folded silently into "the tool invariant instead") into its own explicit decision line (see HD-6 below), since — unlike `analysis_date` or `tier_causing_pair_ids` — this is a live, demonstrated gap in the *accepted* verifier, not a convenience default.

**Needs a HUMAN decision:** yes — new (HD-6, not previously listed): accept EG-5c as a documented, tool-mitigated-not-verifier-closed residual risk (recommended: yes, given the trust boundary already accepts orchestrator dispatch acts), and require that the acceptance-matrix regression evidence for it not be deleted, only supplemented.

---

## C-02 — MINOR/NOTE — R10 anchors `--model-id` only to internal consistency (agent-object agreement), not to an independently witnessed fact; A already flags this but under-weights it

Task question: *"is `proposed_by = AI-AGENT` for a rule-fixed object honest... residual laundering risk?"*

I agree with A's core argument in v2 §3: given option (a) (single shared `run_id` across the batch) plus R10 (the tool refuses unless `--model-id` equals every agent object's `model_id`), the EMPTY object's `model_id`/`proposed_by` are consistent with R3 §18's model↔run binding, and this closes the sharper version of B-02 I raised in the v1 round (the "different experiment ⇒ different run, silently erased" argument). That is a genuine resolution, not a rationalization — I checked it against the actual text of R3:1317-1319 again and the "single-model run" framing holds up.

The residual gap is narrower than B-02 was: **R10 checks that `--model-id` agrees with what the agent objects in the same batch already say about themselves — it does not check that value against anything the orchestrator did not also get to choose.** The value ultimately traces back to a CLI argument the operator (or a wrapper script) supplies; nothing in the current design cross-checks it against the archived transcript's own model metadata. A already names exactly this ("[H-minor, default] Optionally, `freeze-final` also checks the argument against the model recorded in the archived orchestrator transcript, if the decoder exposes it (not verified here)") but files it as a footnote to an already-accepted section rather than as part of what makes R10 trustworthy. Given the addendum's trust table already puts "the orchestrator (dispatch acts)" in the trusted column, this is arguably fine by the design's own stated philosophy — I am not asking for a verifier change here, only that the write-up not imply R10 is a hard guarantee. It checks self-consistency, not truth.

**Recommendation:** keep as optional (A's own framing is fine), but change "not verified here" to state plainly that R10 alone cannot detect an operator supplying a false `--model-id` that happens to also match what was (also falsely, or via a bug) written into the agent objects — R10's protection is against *internal disagreement*, not against *externally correct* values. No verifier change implied. No new HD needed; fold into HD-2's approval text as a one-line caveat.

---

## C-03 — MINOR — the hub-EMPTY label's rule-determinism nature is only proposed to be reported for the standalone EMPTY stratum, not for its (anonymous) contribution to the sampled HUB stratum's own θ_D

Task question: *"does v2 change an estimand or only interpretation? Check against B1/Freeze-2 text."* — Confirmed: **only interpretation**, I independently re-checked B1 v2 §7/D1 (stratum precedence, unchanged) and Freeze-2 §2's table (N_h/n_h/π_h/U_h for every stratum unchanged; the EMPTY row's `U_h at d=0 = 0` already encodes an expected-zero-disagreement census design, confirming my v1 B-03 read). No frame, sample, or formula changes anywhere I could find.

One nuance A's v2 §4 doesn't carry through: **HUB is not a census** (Freeze-2 §2: `HUB N_h=66, SRSWOR, rate 0.5, n_h=33`), unlike EMPTY (`census`, `π_h=1`). The one hub-EMPTY label (`F2:57`, "1 hub label is EMPTY... sampled in HUB, not taken as a census") has only a ~50% chance of being in the actual `n_h=33` HUB draw for any given batch/freeze — and if it *is* drawn, its guaranteed-zero disagreement is one data point blended into HUB's own pooled θ_D estimate across all 33 sampled hub labels (of mixed path types: SINGLE, DECOMPOSED, EMPTY, MULTIROW-hub). Unlike the standalone EMPTY stratum, which Freeze-2 already isolates and which A's HD-5 proposes to label explicitly ("rule-determinism check... never cite as corroboration of reading reliability"), nothing in v2 proposes the analogous label for whatever fraction of HUB's own reported θ_D traces back to that one guaranteed-agree label. At `n_h=33` the effect is small (≤1/33 ≈ 3% pull on HUB's point estimate if drawn), but it is the same distortion B-03 identified, leaking into a different, unflagged number.

**Recommendation:** extend HD-5's reporting-rule text to also flag the hub-EMPTY label's status if/when it is drawn into the HUB sample (e.g., footnote HUB's θ_D report with "N of the n_h sampled hub labels followed the deterministic EMPTY-OBJECT rule"), so a reader of the HUB stratum's audit result has the same caveat available that the EMPTY stratum's report will carry. This is a documentation-only change, not an estimand change — agreeing with A's [F/D] conclusion in §4 that no frozen estimand is touched.

**Needs a HUMAN decision:** fold into HD-5 (no new HD line needed) — same act, slightly wider scope.

---

## Checks on the probe itself (does it test what it claims?)

- **`renumber_a`** correctly realizes the proposed `n = 1000·L + k` formula (label-scoped `Counter`, 1-based `k`, remaps `derived_from_records` for register records only — correctly, since P1-gap records don't carry that field in the schema I checked, `R3:295-322`). I reran it: `BATCH-PASS`, `{}`. This is a real test of option (a), not just of "some globally-unique numbering" — I confirmed the **baseline** FX row (which already passes without `renumber_a`) uses a *different*, non-label-scoped numbering inherited wholesale from the historical single-run R2 fixture, so the baseline row alone would not have proven the `1000·L+k` formula specifically works; the separate `renumber_a` row is needed and does the real work. Good test design, not vacuous.
- **`collide`** genuinely creates cross-label collisions (`k` reset to 1 per label under a shared `<B>-R7:<B>:` prefix) and correctly triggers `G-07 repeated rs_id/gap_id values`. Not vacuous.
- **`dispatched(st, what)`** picks the first register-bearing label and swaps only the targeted field(s) to the dispatched id — a genuinely minimal single-variable mutation. I reran both variants and got the exact failures A reports. Not vacuous.
- **`claim_run_assembly`** has a `return` positioned so it mutates only the **first** claim of the **first** final-run's claim map (not "one register-bearing label" broadly, just one claim) — narrower than the docstring implies, but sufficient to demonstrate the point (one bad ref already fails `R7-E`), and I confirmed the failure text traces to `claim_violations`'s "no valid evidence path" check (`p3b_s5_r7_evidence.py:158-177`), which is exactly the mechanism A's table row claims. Correct, if narrower than advertised.
- **`overflow`**: **this is where the probe under-tests its own claim.** The function only constructs the *colliding* overflow case (`n` pushed into the range already used by the next adjacent register-bearing label), and explicitly skips (prints a message, doesn't assert) when no adjacent label exists to collide with. It does **not** test the claim in v2 §2.1 that motivates R11 in the first place — *"An out-of-range id **without** a collision passes the verifier"* — that specific, load-bearing claim is asserted in prose but never exercised by this probe. I wrote a targeted supplementary probe (`probe_overflow_novacuous.py`: one register record's `rs_id` set to `<B>-R7:<B>:999999`, far outside any label's `[1000L+1, 1000L+999]` band and colliding with nothing) and confirmed independently: **`BATCH-PASS`, no failures.** So A's underlying claim is correct — I verified it myself — but the shipped probe does not demonstrate it, which matters because it is the entire justification for R11 existing as an *assembly-side* (not verifier-side) refusal. This should be added to the RED test list explicitly (it currently is not a distinct T-number; T115 as described only covers "no collision" ambiguously — the table entry should be split into "T115a: out-of-range, no collision → BATCH-PASS at the verifier, demonstrating why R11 must be in the tool" and "T115b: out-of-range colliding with the next label → BATCH-FAIL at the verifier").

## Other RED-list / HD-list gaps (task 6, 7)

- **No test for R12** (`--date` not `YYYY-MM-DD`) appears in the RED table under any T-number (T110-T122, T-01..T-13) — R11 has T115, R10 has T122, but R12 has none listed. Minor completeness gap.
- **No test for two EMPTY labels in one batch.** The fixture (and hence every test derived from it) has exactly one EMPTY label; concatenation-order and per-label LOAD-escalation correctness with ≥2 EMPTY labels (e.g., two separate hub-EMPTY labels with different hit-bearing dimensions) is asserted by construction (manifest order) but never exercised. Minor; recommend adding before the tool ships, not before HD-1 approval.
- **HD list:** as covered in C-01, recommend adding **HD-6** (accept EG-5c as a recorded, tool-mitigated verifier gap) rather than leaving it bundled inside HD-1's "tests first, and the tool slice" and the unlabelled default in §8. Everything else in the bundled-defaults list (`analysis_date`, `tier_causing_pair_ids=[]`, absent-file-as-zero-records, no verifier check of ASSEMBLED hashes) is a genuine convenience default with low stakes and I agree those don't need separate lines.

## Checks that came back clean

- §2.1's consumer table (ledger paths/W8, reader `--run`, READ-LOG, claim-evidence `run`, file-reading records, S3-LINT hash, S3 register-id detector, G-12 pointer detector, I(run)/`input_manifest_sha256`) — I independently re-verified the two consumers most likely to break under option (a) (claim-evidence `run`, and the S3 `rsid` regex's "optional prefix" behavior against the literal string `<B>-R7:<B>:<n>`) and both check out exactly as A states. I did not find an unlisted consumer keyed on record `run_id` that would break under (a).
- Option (b)'s cost/benefit analysis (verifier change reopens the accepted audit, HD-9) is accurate per `A:10` and the trust table; recommendation of (a) over (b) is sound given the stated constraint (keep G-LOG-0092 closed).
- §6's v2.8 normative text is scoped correctly: §2.6 touches only `run_id`/`rs_id`/`gap_id` on agent-written records and explicitly preserves the dispatched id for paths/reader/READ-LOG/claim-evidence; §2.7 changes no agent behaviour. Neither touches `plan_derive`, slice content, or frame/stratum assignment (all of which live in R6/R5/UN, untouched). No hidden expansion into HD-1's stated scope found.

## Verdict

**SPEC-NEEDS-REVISION** (minor — this is a much smaller gap than the v1 round; the core design is sound and B-01/B-02/B-03 were genuinely fixed).

**Required before this goes to the human act:**
1. Add **HD-6**: explicitly record EG-5c as an accepted, tool-mitigated (not verifier-closed) residual risk, and keep — don't delete — an acceptance-matrix test that demonstrates the verifier alone (no tool) still tolerates a mislabeled register/gap record under an EMPTY label (C-01).
2. Widen HD-5's reporting-rule text to also cover the hub-EMPTY label's contribution to the *sampled* HUB stratum's θ_D, not only the *census* EMPTY stratum's (C-03).
3. One-line caveat on HD-2: R10 guards internal consistency of `--model-id`, not its truth against an independent record; the optional `freeze-final`-transcript cross-check is where real protection would come from if ever needed (C-02).
4. RED-list additions: split T115 into a no-collision/collision pair and add the missing R12 test (both minor, pre-implementation, not pre-approval blockers).

No change is needed to §1-§4's technical content, to the recommendation of option (a) over (b), or to the v2.8 normative text's scope. HD-1, HD-3, HD-4 stand as written.
