# R-S4 Independent Adversarial Review — EG-9 (audit_record R7 / accept tranche)

**Scope:** `scripts/p3b_s5_audit_record.py`, `scripts/p3b_s5_accept.py` (uncommitted diff) +
`scripts/tests/test_p3b_s5_audit_record_r7.py`, `scripts/tests/test_p3b_s5_accept_tranche.py`.
Tests run: 99/99 pass (`test_p3b_s5_audit_record_r7`, `test_p3b_s5_accept_tranche`,
`test_p3b_s5_audit_record`, `test_p3b_s5_ops_state`, `test_p3b_s5_ops_audit_sample`).

## Verdict: **IMPL-NEEDS-REVISION**

Two MATERIAL findings, both fixable without redesign. Test suite is otherwise thorough,
non-vacuous, and green; R2/pre-R7 behaviour is byte-identical (confirmed by
`test_r2_forms_unchanged`, `T12RevisionBelow7`).

## MATERIAL findings

### M1 — Correction records go to an unapproved file/id namespace (audit_record.py:47,140-152; docstring :19-22)

The approved spec text is explicit and singular. `EG5-V2.8-DECISION-PACKAGE.md:402` (§17, ratified
by `G-LOG-0106` part (g), "v2.8 package parts (a)–(g) approved"): *"§21 item 2 makes AUDIT-UPHELD
produce a correction record (**the rev3 §12.3 mechanism, P3B-CORRECTIONS.jsonl**)."* K3 §12.3 itself
(`prompts/20260925_1204_p3b-agent-contract-r2.md:768-769`) and the protocol's file-class table
(`prompts/20260924_2311_...v1.7.md:2072`) both name the artifact `P3B-CORRECTIONS.jsonl`, id
`P3B-COR-####`.

The diff instead writes to a **new** file `audit-p3b/S5-AUDIT-CORRECTIONS.jsonl` (constant
`CORRECTIONS`, audit_record.py:47) with a **new** id scheme `f"{run}-COR-{k:03d}"`
(audit_record.py:145, e.g. `OB0004-R7-COR-001`), not `P3B-COR-####`. Nothing in the diff, its
docstring, or the two new test files discloses this as a deviation from the approved text — it is
presented as if it *is* "the K3 §12.3 mechanism" (docstring line 19-20: "K3 §12.3 fields... in
`audit-p3b/S5-AUDIT-CORRECTIONS.jsonl`").

This is not cosmetic. I confirmed empirically (`ops.check_paths`) that `P3B-CORRECTIONS.jsonl` is
**not** on the S5 tool allowlist (`p3b_s5_common.py` `ALLOWLIST`/`ALLOW_PATTERNS`), while
`audit-p3b/S5-AUDIT-CORRECTIONS.jsonl` **is** (it matches the `audit-p3b/S5A?-....jsonl` pattern).
That is the most plausible reason for the substitution — but it is exactly the kind of
implementation-driven, silent redefinition of an approved artifact that EP-01/EP-02 and this
repo's own discipline (§21 dispositions "recorded, never applied... via the rev3 §12.3 mechanism")
exist to prevent. Note also that EG9-SPEC-A-v2's "under `audit-p3b/`" language (which this diff
superficially matches) was itself sourced from a **misreading** of P17 item 4 that EG9-SPEC-A-v3 §0
explicitly withdrew ("v2 wrongly equated the two"); the corrected, final, human-approved text is
the §17 line above, naming `P3B-CORRECTIONS.jsonl`.

**Fix:** either (a) write correction records to `P3B-CORRECTIONS.jsonl` with `P3B-COR-####` ids as
approved, adding the necessary allowlist pattern to `p3b_s5_common.py` in the same slice (disclosed,
reviewable), or (b) if `audit-p3b/S5-AUDIT-CORRECTIONS.jsonl` is genuinely preferred for firewall/
containment reasons, that is a new human decision, not an engineering substitution — raise it
explicitly (H-EG9-6 addendum) before landing. Ship neither silently.

### M2 — `check_tranche` reads its two inputs (`--tranche`, `--glog`) without `ops.check_paths` (accept.py:88-101)

Every other S5-tool input in this codebase (evidence files in `p3b_s5_state.check_evidence`, the
`--sample` and assembly files in `audit_record.bind_sample`/`bind_assembly`) is passed through
`ops.check_paths()` — "Plan §M item 6", the allowlist/forbidden-name gate that is this codebase's
stated firewall discipline. `check_tranche` opens `tp` (tranche) and `gp` (governance log) directly
via `open()` (accept.py:97-101) and never calls `ops.check_paths`.

Confirmed empirically: `audit-p3b/H06-TRANCHE-0001.json` is **not** on the allowlist — calling
`ops.check_paths(['audit-p3b/H06-TRANCHE-0001.json'])` raises `S5Error`. So the omission is not
inert: if the standard gate had been applied (as the codebase does everywhere else), a legitimate
tranche file would be refused, because the allowlist was never extended for `H06-TRANCHE-*.json`.
The `--glog` argument is also operator-overridable and equally unchecked; `P3B-GOVERNANCE-LOG.md`
happens to already be allowlisted so that particular omission is currently benign, but the code
path itself has no defense if `--glog` is pointed elsewhere.

This does not, by itself, let ACCEPTED be reached without a genuine audit — checks (b′)/(b)/(c)-(f)
still bind the tranche's content to the live VERIFIED/AUDITED evidence and a committed, sha-anchored
governance line. But it is a real firewall/discipline gap (the one place in this diff that departs
from "every S5 tool input goes through the allowlist"), and it is why the builder could not have
used `ops.check_paths` here without first fixing the allowlist — which was not done.

**Fix:** add `ops.check_paths([tp])` / `ops.check_paths([gp])` in `check_tranche` before opening
either file, and add the corresponding pattern (e.g. `r"audit-p3b/H06-TRANCHE-\d{4}\.json"`) to
`ALLOW_PATTERNS` in `p3b_s5_common.py` in the same slice.

## Non-material / disclosed residuals (assessed, no action required to accept this slice — but should stay tracked)

- **Coverage minimums report-only (`enforced: false`) + extra `manifest_index_matches_state` check**
  (audit_record.py:129-137): faithful. H-EG9-4 is explicitly undecided in every spec revision and in
  `G-LOG-0106` (g), which does not resolve it; defaulting to report-only is the conservative reading.
  The extra seed-index check is additive, correctly short-circuited (`bid in order and ...`), and
  does not gate anything. No objection.
- **R7 refusals exit 2**: consistent with the existing R2 `main()` convention and with E9-02/E9-03 etc.
  No objection.
- **T-16 deferred, but its dependency (EG-6 (d) `current_attempt`) has already landed** in the
  concurrently-modified `p3b_s5_state.py` in this same uncommitted tree (`current_attempt`,
  `run_history(..., attempt=...)` both exist and are real, not stubs). Both `audit_record.py` and
  `accept.py` already call `stm.current_attempt` via a `getattr` fallback that now resolves to the
  live function, so check (d)'s attempt comparison is **no longer vacuous** — it is live, untested
  code for attempt ≥ 2. The test docstrings still say "T-16 lands with EG-6 part (d)" as if that were
  future work. This is a stale gap, not a correctness bug found on inspection, but it should be
  closed now rather than carried forward again — the dependency it was waiting for is already in
  the tree being reviewed. Recommend adding T-16 (attempt-2 tranche: attempt-1 listing refused,
  attempt-2 listing accepted) before landing, or explicitly re-stating why it's still out of scope.
- **(e) evidence binding is against state-stored shas, not a re-hash of the report file on disk**:
  correct by design — `check_evidence` hashed the report at the VERIFIED/AUDITED transition and
  froze that value into the hash-chained history; re-reading the file at audit/accept time would add
  nothing `P3B-STATE.json`'s chain doesn't already guarantee, and doing so is out of this slice's
  scope. Acceptable.
- **p1-gap-capture.jsonl sha recorded but not freshness-checked**: `bind_assembly` hashes all three
  assembly files and records them in the report and header, but `bind_sample`'s freshness check
  (`hdr.get("input_hashes") != want`) only covers `SAMPLED_FILES = (objects.jsonl, register.jsonl)`
  — matching what `p3b_s5_audit_sample.py` actually reads. `E904AssemblyBytes.
  test_assembly_changed_after_sample_refused` confirms tampering objects/register post-sample is
  caught; it does **not** test tampering `p1-gap-capture.jsonl`, and indeed AR would not catch it —
  it would just silently record the new (tampered) hash as if correct. Freeze-final / WITNESS
  already re-checks the ASSEMBLED marker's printed hashes for all three files at freeze time (per
  `EG5-V2.8-DECISION-PACKAGE.md:73`), so this is a narrow post-VERIFIED, pre-audit tamper window, not
  an unguarded file. Worth a follow-up (cross-check `files["p1-gap-capture.jsonl"]` against
  `WITNESS.jsonl`'s printed hashes, or add it to `SAMPLED_FILES`'s freshness check even though the
  sampler doesn't read it), but not blocking.
- **at-most-once acceptance key is (batch_id, run_id), attempt-blind**: disclosed in EG9-SPEC-A v1
  ("EG6:136-137 E-04 addresses it") and benign in practice — the run_id is shared across attempts by
  design (addendum: "every attempt... keeps the run id `<B>-R7>`"), but ACCEPTED/PASS-state
  exclusivity (`RR-1`, `PASS_STATES`) and class-H non-retry already make a second accept attempt for
  the same batch unreachable. Not a live bug.
- **E9-11 "re-verify still BATCH-PASS" not exercised**: true — no test re-invokes the R7 verifier
  after AR runs. What *is* tested (`E911NonCorrective.test_writes_only_the_report`,
  `test_audit_upheld_corrections_recorded_apart_from_assembly`) is a full pre/post file-tree hash
  comparison showing AR touches nothing but its own new output(s); given the verifier is a pure
  function of file bytes, byte-identity already implies a re-verify would be unaffected. Acceptable
  as a proxy; an explicit re-verify test would be stronger but is not required to accept this slice.

## Attack surface checked, found closed

- **Reach ACCEPTED without genuine AUDITED**: no path found. `accept.record()` still requires
  `b["state"] == "AUDITED"` (pre-existing, unchanged check) before any tranche logic runs, and
  `AUDITED` still requires (via `p3b_s5_state.check_evidence`) an admissible §21 PASS report bound
  by `bind_verified_state`/`bind_sample`/`bind_assembly` to the live VERIFIED state, the current
  attempt, the current assembly bytes, and a real `p3b_s5_audit_sample.py` output. R2 forms and
  non-assembly R7 ids (`-R7-L01`, `.A2`, `-R7S`) are correctly refused (regex `rf"{bid}-R7"`
  fullmatch), confirmed by `test_non_assembly_r7_ids_refused`.
- **Class H on FAIL, interplay with concurrently-modified state.py**: `_failure_fields` in
  `p3b_s5_state.py` mechanically defaults `failure_class` to `H` whenever `frm in PASS_STATES` (i.e.
  a FAILED transition out of VERIFIED/AUDITED/ACCEPTED) and no explicit class is passed; both AR's
  runbook-driven `transition ... FAILED` and AC's NOT-ACCEPTED→FAILED path go through this
  unchanged. `RERUNNABLE_CLASSES` excludes H, so `new_attempt` is correctly refused afterward
  (`assert_no_new_attempt`, exercised in both `test_fail_report_refused_then_failed_class_h_no_rerun`
  and `T11NotAccepted`). No interplay bug found. (Minor test-hygiene note: both of those tests guard
  the class-H assertion behind `if "failure_class" in st["history"][-1]:` — currently always true, so
  not vacuous today, but it would silently stop asserting anything if that field's presence ever
  regressed. Consider asserting the key's presence outright.)
- **Firewall — §21 tools take no Freeze-2 input**: a static source-scan test
  (`E910Firewall.test_static_no_freeze2_reference`) bans specific Freeze-2 identifiers/hashes from
  `p3b_s5_audit_sample.py`/`p3b_s5_audit_record.py` source, plus a behavioural test that sampling is
  independent of a synthetic frozen-sample file placed in the assembly directory. Real, if narrow
  (name/hash-based) enforcement — acceptable given the same narrowness is already accepted for the
  existing R2 firewall tests. "The §21 auditor is never the S5 reader/θ auditor" is **not**
  code-enforced anywhere (only `auditor_model_id` is recorded) — correctly so, per the spec's own
  framing of this as a G-LOG governance ruling, not a code gate.
- **R2 behaviour byte-identical**: `test_r2_forms_unchanged` and `T12RevisionBelow7` both pass; the
  diff's `main()` and `record()` changes are additive branches (`if rev7: return main_r7(...)`,
  `if b.get("revision")==7 and not tranche: raise...`) that leave the pre-existing code paths
  untouched.
- **Tests non-vacuous**: spot-checked assertions carry concrete expected values (hashes, ids, state
  names, exact uncovered-record lists), not just "no exception" — genuine RED/GREEN tests, not
  smoke tests.

## Bottom line

Ship-blocking: **M1** (correction artifact/id namespace diverges, undisclosed, from the literal
human-approved `EG5-V2.8-DECISION-PACKAGE.md` §17 text) and **M2** (tranche/glog reads bypass the
codebase's own input allowlist gate, and the allowlist was never extended to admit the tranche
file). Both are small, mechanical fixes that do not require redesigning `check_tranche` or the
correction-record logic. Recommend fixing both, re-running the full suite, and — for M1 specifically
— getting an explicit human sign-off on whichever artifact name is finally used, since the package
text is unambiguous and was already approved.
