# EG-6 adversarial re-review, v2 (Subagent B)

| | |
|---|---|
| Kind | Independent adversarial review of `EG6-SPEC-A-v2.md` and the new probes `retire_model.py`, `m_cap_table.py`, responding to `EG6-REVIEW-B.md` (round 1: E-01…E-06). Read-only, synthetic fixtures only. |
| Method | Reran both new probes. Read `p3b_s5_audit_record.py` and `p3b_s5_accept.py` directly. Independently checked filesystem topology for `ledger` vs the archive root in this environment. Spot-checked new W/U-tag strings directly against `p3b_s5_r7_witness.py`/`p3b_s5_r7_evidence.py`/`p3b_s5_r7_universe.py`. |

## 0. Did round 1's findings get fixed?

All six were addressed substantively, not just reworded:
- **E-01 (crash-safety):** a genuine intent/complete two-record design with resumable, idempotent steps — see F-01 below for the one gap I found in it.
- **E-02 (retry-conservatism interpretation risk, M unjustified):** the mechanism is now named explicitly in the proposed §2.8 text, a reporting/cross-tab/domain-estimate requirement was added, and M now has a real quantitative derivation — see F-02 for what's still missing.
- **E-03 (Class A too broad):** split into A1 (no measurement taken) / A2 (measurement rejected by a content gate), exactly as I asked, with A2 reported separately.
- **E-04 (attempt-aware state, evidence-sha reuse):** the exact mechanism I said was buildable from existing data (`_append`'s recorded `evidence.sha256`) is now specified explicitly, plus an explicit "at most one PASS ever" guard.
- **E-05/EG-7 (AUDITED path):** confirmed and fixed at both blocking points (`check_evidence` and `p3b_s5_audit_record.py`'s run-id regex), not just the one I'd found.
- **E-06/EG-8:** a concrete proposed G-LOG entry text with the recomputed sha256, ready for the owner to file.

## F-01 — MATERIAL (task 1) — the "one filesystem" precondition is unnecessary as stated, unimplemented in the reference model, and the actual EXDEV failure mode is untested

**Reran `retire_model.py`:** exact match to A's claims — `10/10` crash points resume to a byte-identical final state, both adversarial cases (tamper, source-recreated) correctly REFUSED as class S, `may_start_attempt` correctly blocks throughout. Non-vacuous: the probe forces a genuine crash (a raised `Crash` exception) at every one of the 10 named points, not just at the boundaries, and verifies both that resumption completes and that a *further* re-invocation is idempotent (`ALREADY-COMPLETE`). This is real, exercised crash-safety for the steps it covers.

**What I checked beyond the probe's own claims.** Spec §1, S0: *"archive and ledger on one filesystem (else REFUSED)."* I traced through what actually needs to be atomic: S2 renames `ledger/<name>` → `ledger/<B>-R7.A<m>/<name>` (source and target are **both under the same `ledger-p3b-r2/` root**), and S4 renames `archive/<B>/` → `archive/<B>.A<m>/` (source and target are **both under the same archive root**). Neither rename ever crosses *between* the ledger and the archive — each stays entirely within its own tree. So the stated precondition ("archive and ledger [with each other] on one filesystem") doesn't correspond to the actual failure mode of either `os.rename()` call; what would actually need checking is a narrower, always-true-by-construction property (each rename's source and target share a parent, hence a device).

I confirmed this is not just a wording nitpick by grepping the reference model for any implementation: `st_dev`/`EXDEV`/`samefile` appear **nowhere** in `retire_model.py` — the only trace of this precondition is a code *comment* at S4 ("same filesystem required"), with no check and no test. `build()` always creates `ledger` and `archive` under one shared `tempfile.mkdtemp()` root, so a genuine cross-filesystem scenario is never exercised, and there's no test simulating `os.rename` raising `OSError(EXDEV)` to confirm the tool would refuse cleanly (rather than crash uncleanly or leave a partial write) if that ever did happen at a boundary the design didn't anticipate.

**Why this matters beyond pedantry.** The archive root is genuinely a different location from the repo: `p3b_s5_r7_witness.py`'s `DEFAULT_ARCHIVE = os.path.expanduser("~/knowledgeos-witness-archive")`, and the program's own recorded precheck data shows an archive_root under a **different user's home directory** than the repo checkout. I checked whether this actually creates a cross-filesystem hazard in the current environment: `stat -f` on the repo path and on that home directory both resolve to the same btrfs device here (`/dev/mapper/luks-...`, mounted at `/home`), so in *this* deployment they coincide. But that's an accident of this sandbox's layout (one shared `/home` mount for multiple user directories), not a property the design should assume holds in every deployment (a separately-mounted home directory, an NFS mount, or a different disk for the archive would all break the literal "archive and ledger on one filesystem" reading, if that reading were actually load-bearing) — and per my analysis above, it doesn't need to be, since the renames never cross that boundary anyway.

**Recommendation:** replace the S0 bullet with the precise, always-satisfiable-by-construction check (verify each planned rename's source/target parent share a device before attempting it, or simply attempt the rename and catch `OSError` with `errno == EXDEV` specifically, refusing with a clear class-S diagnosis rather than crashing), and add one test to the reference model that monkeypatches `os.rename` to raise `EXDEV` at one of the move steps, confirming the tool refuses cleanly instead of leaving a partial state. This doesn't change the design's direction — `os.rename()` on sibling directories is the right primitive, and I confirmed it (not a naive copy+delete) is what's actually used — it just closes a gap between what the spec text asserts and what the reference model actually builds and tests.

**Needs a HUMAN decision:** no — implementation-completeness, foldable into HD-6.1's "tests first."

---

## F-02 — MATERIAL (task 2) — M=4's arithmetic is independently confirmed and sound; the independence assumption is honestly flagged but has no ongoing, post-canary check

**Reran `m_cap_table.py`.** Every number matches A's tables exactly, row for row: `P(all 396 pass)` at q=0.05 gives 0.3711/0.9517/0.9975/0.9999 for M=2..5 (A: 0.371/0.952/0.998/1.000); `E[never-pass batches]` at q=0.10, M=3/4 gives 40 → 0.40/0.04 (A: "0.40 / 0.04"); the exact hypergeometric bounds reproduce F2 §2's own U(d=0) values exactly (HUB 5, DECOMPOSED 5, SINGLE 67 at α/H′ = 0.05/3), and the "one NOT-ASSESSABLE SINGLE label raises U from 77 to ≈109" claim checks out exactly (0+0+5+5+67=77 baseline; 0+0+5+5+99=109 with one SINGLE d=1). This is genuinely independent, checkable arithmetic, not a claim I have to take on trust, and it's correct. **M=4's derivation is now adequately quantitative — my round-1 "M=3 is a bare default" finding is resolved.**

**What's still missing.** The table's own caption states independence across attempts is "an optimistic assumption, since hard batches correlate" — I agree this is honest, but I looked for what happens operationally if that assumption fails, and found a gap: the only mechanism that catches a **systematic** defect after the canary is RR-7 (two consecutive attempts of the **same batch** with an identical failure-string set → class D, stop). The canary's own stop rule (≥50% of 11 runs fail, or a systematic pattern across the 2 canary batches) operates **only during the 2-batch canary**, before the other 394 batches are authorized. If a defect is content-dependent and doesn't manifest in either of the 2 canary batches — very plausible with only 2 of 396 sampled — but starts showing up as an elevated retry rate across many *different* batches later (each individually below RR-7's "identical signature twice" threshold, since the specific failing detail differs batch to batch even if the root cause is shared), nothing in the current design re-triggers a program-level stop. Each affected batch just quietly consumes its attempts individually, and (per E-02's own named mechanism) potentially biases its accepted object toward the retry-conservatism effect, with the aggregate only becoming visible at the very end via E-3's post-hoc reporting.

**Recommendation:** add an explicit, cheap, mechanical post-canary check — e.g., after every N batches (or continuously), compare the *observed* rolling failure rate q̂ against the canary-estimated q; if it exceeds a pre-declared threshold (or the canary's own q by a stated margin), trigger the same class of stop the canary rule already uses. This is a natural, sample-blind, audit-blind extension of RR-6/RR-7's existing "instrument frozen after the canary, any repair is a STOP and a human gate" principle — it doesn't touch the frame, the sample, or the estimator, only adds an early-warning trigger using data (attempts and causes per batch) the design is already collecting for E-3.

**Also worth one added sentence (minor, not a new finding):** §6's text already commits to reporting the attempt-1 domain estimate "also," alongside the primary quantity, which is the right structure to avoid a garden-of-forking-paths risk — I'd just make explicit, in one sentence, that it is never reported standalone or presented as a corrected/improved number, to remove any ambiguity for whoever writes the eventual results document.

**Needs a HUMAN decision:** fold into HD-6.2 (the pre-registration text) — not a new decision, an addition to what's already being pre-registered.

---

## Task 3 (E-03) — classifier: spot-checked, mechanical, and now correctly scoped

I independently reread the mapping table against the actual code rather than trusting citations, and grepped for tag strings I hadn't already verified in the EG-5 rounds: `W1-ZERO-TOOL`, `W1-DECLINED`, `W1-TRANSCRIPT-MISSING`, `W1-TRANSCRIPT-DUPLICATE`, `W1-PARSE`, `W1-CHAIN`, `W1-NOTIFICATION-ABSENT`, `W1-NOTIFICATION-MULTIPLE`, `W1-HARNESS-COUNT-MISSING`, `W1-COUNT-MISMATCH`, "planned run was never dispatched", `R7-E W6`, and `R7-U binary decisions` all appear **verbatim** in `p3b_s5_r7_witness.py`/`p3b_s5_r7_evidence.py`/`p3b_s5_r7_universe.py`, matching A's table exactly. `ASSEMBLY-REFUSED` doesn't exist in the codebase yet — expected and correct, since the `assemble` tool it belongs to is itself still a proposal (EG-5), not implemented.

I agree the class boundary is now drawn correctly: A2's re-runnability is the mechanism E-02's bias risk depends on, and I think A's answer (re-run it, but name the mechanism, split it out for reporting, and never treat "never re-run A2" as viable, since that makes the whole program impractical at any realistic content-rejection rate) is the right engineering trade-off — the residual concern is entirely covered by F-02 above, not a new gap here. Class H's exclusion from automatic re-run is consistent with RR-1 and with E-04's "at most one PASS/VERIFIED/AUDITED entry, ever" guard (which I re-checked covers VERIFIED and AUDITED, not just ACCEPTED — so a batch that reached VERIFIED and later got AUDIT-FAILED or H-06-rejected cannot mechanically retry either, matching Class H's stated "no automatic re-run"). Agree, no gap.

---

## Task 4 (E-04) — attempt-aware state design: confirmed buildable and sound, one minor nuance

I reread `p3b_s5_accept.py` directly. Line 51-52: `entries = stm.run_history(st, batch, b["run_id"]); evidenced = {e["to"] for e in entries if e["to"] in ("VERIFIED", "AUDITED") and e.get("evidence", {}).get("sha256")}` — confirmed attempt-blind exactly as A states (filters only by `run_id`, which is identical across attempts). I checked whether this is a live risk given A's proposed "at most one PASS ever" guard on `new_attempt` (refuses if any history entry is `VERIFIED`/`AUDITED`/`ACCEPTED`): since that guard structurally prevents more than one attempt from ever reaching VERIFIED for a given batch, `p3b_s5_accept.py`'s attempt-blind `evidenced` set can never actually contain entries from two different attempts to confuse — there is only ever at most one. So, as with the analogous `check_evidence` degeneracy I flagged in round 1, this isn't a live correctness risk given the upstream invariant; recording the accepted attempt number in `S5-ACCEPTANCE.jsonl` (which A's item 1 proposes) is good practice for provenance/audit-trail clarity, not a correctness necessity. No gap, confirmed sound.

I checked the ordering, evidence-reuse, and no-two-PASSes questions directly: "attempt = previous + 1 ≤ M" (§4 item 3) blocks skipping or reordering; the evidence-sha cross-check against all prior history entries (§4 item 4) is exactly the mechanism I said in round 1 was buildable for free from `_append`'s already-recorded `evidence.sha256` — confirmed it is (`_append(st, batch, run, frm, to, reason, actor, kind="PRIMARY", evidence=None)` stores `e["evidence"] = evidence` on every transition, I reread this in round 1 and it's unchanged). No path to two PASSes or a retired VERIFIED batch found.

---

## Task 5 (E-05/EG-7) — confirmed directly in both files, fix is non-material, one open item correctly flagged as open

**`p3b_s5_audit_record.py:53`:** `if not re.fullmatch(r"OB\d{4}", bid) or not re.fullmatch(rf"{bid}-R2(\.\d+|S)?", run): print("REFUSED: malformed batch or run id", ...)`. Confirmed exactly: this regex accepts only `<B>-R2`, `<B>-R2.<n>`, `<B>-R2S` — `<B>-R7` never matches, so no AUDITED evidence can be produced for an R7 batch today. Confirmed independently, matches A's claim precisely.

**AR's own vocabulary:** line 63, `"result": "PASS" if pv == 0 else "FAIL"` — literal `"PASS"`/`"FAIL"`, not `"BATCH-PASS"`. Confirmed this is *already* compatible with `check_evidence`'s unmodified literal-`"PASS"` check, so fix (b) (widen AR's `--run` regex to accept `<B>-R7` exactly) is genuinely independent of fix (a) (make `check_evidence` revision-aware for the *verifier's* report) — they patch two different, unrelated blocking points, as A's table correctly separates them.

**Is the fix non-material?** Yes, I agree: (a) is a string-comparison change inside `check_evidence`, keyed on the batch's recorded revision, touching neither the verifier's own output vocabulary (which stays `BATCH-PASS`/`BATCH-FAIL`/`BATCH-UNDETERMINED`, correct per its own protocol) nor any contract text; (b) is a regex-widening change to AR's own input validation, similarly touching no contract or verifier text. Both are ordinary state/tooling engineering, testable in isolation (S-01…S-08), and can land before the v2.8 rebind as A recommends.

**The one open item (whether AUDITED applies to the R7 lifecycle at all) is correctly left open, not silently resolved** — I don't see A picking a side here, and I agree it shouldn't: RB §1's PROPOSED→VERIFIED path doesn't mention AUDITED, while EP-01/B1 describe auditing happening *after* acceptance via a separate sampling mechanism, so whether the per-batch §21 audit is even a meaningful gate under the new statistical design is a real governance question, not an engineering one. Fix (b) only removes the mechanical block; it doesn't answer whether the step should be taken.

---

## Task 6 — HD list: complete and appropriately separated, no redundancy found

Six items (HD-6.1 adoption, HD-6.2 pre-registration text, HD-6.3 the cap M, HD-6.4 class re-run policy, HD-6.5 EG-7 fix + open AUDITED question, HD-6.6 EG-8 remedy) are cleanly separated by decision *type* (design adoption / statistical policy / a numeric parameter / a re-run policy / an unrelated but urgent tooling fix / a provenance-governance fix), and I don't see overlap or something that should be split further. F-01 and F-02 above don't need new HD lines — both fold naturally into HD-6.1 (tests first, for F-01) and HD-6.2 (the pre-registered policy text, for F-02's watchdog addition), consistent with how the package already treats implementation-completeness items versus genuine human decisions.

---

## Verdict

**SPEC-NEEDS-REVISION** — narrowing steadily. All six round-1 findings were substantively fixed, not just reworded, and I independently reran both new probes with exact, non-vacuous confirmation of every arithmetic and crash-safety claim I could check. The two new findings this round are both implementation-completeness gaps, not design-direction problems, and both are cheap to close before the same rebind:

1. **F-01:** correct the "archive and ledger on one filesystem" S0 precondition to the precise, always-satisfiable-by-construction check (per-rename device equality), and add a test that simulates an `EXDEV` failure to confirm clean refusal — the reference model currently has neither the check nor the test, only a comment.
2. **F-02:** M=4's derivation is now sound and independently verified — no further change needed there — but add a post-canary, ongoing systemic-failure watchdog (comparing observed q against the canary's estimate) to §2.8's pre-registered text, since RR-7 only catches repetition *within* one batch and the canary stop rule only operates during the 2-batch canary itself.

Everything else checked out: the classifier's tag-string mapping (spot-checked directly against code, not just cited), the attempt-aware state design (traced through `p3b_s5_accept.py` and confirmed both the gap A found and that it's structurally harmless given the "at most one PASS ever" guard), and the EG-7/EG-8 fixes (confirmed directly in both newly-read files, both non-material, one item correctly left open as a governance question rather than silently resolved).
