# R7 (v2.4) — the ONE independent adversarial audit (result)

| | |
|---|---|
| **Auditor** | independent agent, model **Claude Sonnet 5** (`claude-sonnet-5`), no prior context with the implementation session. Per the authorization package's recommendation ("a fresh agent ... on a different model where feasible") — same vendor family, different generation/tuning from the implementation session; a human reviewer or a cross-vendor model is a stronger independence bar and is not claimed here |
| **Date** | 2026-09-27 |
| **Contract sha256 verified** | `fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674` — matches `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` exactly (checked first, before any other step) |
| **Code commit examined** | `da078926668f7252d336cb6c26aca85bad111770` (`git rev-parse HEAD`, branch `knowelegeos-modelling`) |
| **Examined** | `scripts/p3b_s5_r7_universe.py`, `p3b_s5_r7_witness.py`, `p3b_s5_r7_evidence.py`, `p3b_s5_r7_reconstruction.py`, `p3b_s5_r7_stats.py`, `p3b_s5_r7_verify.py`, `p3b_transcript_syntax.py`, `p3b_read_source.py`, `p3b_s5_common.py`, `p3b_s5_verify.py` (R7 routing, §994–1092); the addendum in full; ADR-R7-01; the authorization package; `scripts/tests/r7_full_fixture.py`, `r7_harness_fixture.py`, `test_p3b_s5_r7_full.py`, `test_p3b_s5_r7_inv_lex.py` |
| **Not read** | corpus source files, the production ledger, `P3B-STATE`, hold-out/seal list contents, anything under H-19 (per the audit's hard rules) |

## 1. Method actually used

Per the package's §3, in this order of yield: (5) declaration-vs-observation attacks (every field a predicate trusts without re-deriving it) and (1) counterexample search by direct predicate reading, backed by (2) a small set of hand-built mutation spot-checks, then (3)/(4)/(6) as time allowed. A full run of the official 1,049-test suite against a mutated scratch copy was **not achieved**: the fixtures (`r7_full_fixture.py` → `test_p3b_s5_verify.py`) resolve CR-relative synthetic-fixture paths (e.g. `_batch_input_r2/P3B-S4-R22-MANIFEST.json`) that live outside `scripts/`, and copying enough of `chronological-read/` to satisfy them risked pulling in material the audit must not read. Mutation testing here is therefore a **small, targeted spot-check** (§4), not a suite-wide sweep; this is the main limit on this audit's coverage and is stated plainly in §6.

All reproducers ran in `/tmp/.../scratchpad/r7-audit/` against the **unmodified** repository code, mostly through the **production verifier** (`p3b_s5_verify.verify`) via the same synthetic fixtures the official R7 suite uses (no corpus, no real ledger, no repository writes).

## 2. Per-invariant results

| # | Method | Tried | Result |
|---|---|---|---|
| **I1** Universe closure | read `discover`/`type_of`/`validation_violations`/`typing_violations`/namespace/plan-binding; checked path-normalization (`.`→`·`, `[*]`), discriminator ambiguity resolution, default-deny fallthrough | manual path-collision reasoning (dict keys containing `.`/`[`/`]`, since `objects.jsonl` is agent-authored/untrusted); namespace/legacy-digest code paths | No MATERIAL counterexample found. Default-deny (untyped ⇒ `R7-U`) held in every case tried. Not exhaustively fuzzed (see §6) |
| **I2** INV-LEX / DC-1 | read `meta_violations`, `SELF_REF`/`SELF_REF_RULE`, `claims()`; confirmed `claims()` is **structurally closed** (`U.claims(V24_REG, o) == U.claims([], o)`, i.e. registry rows cannot manufacture a claim); mutation spot-check on `SELF_REF.fullmatch` (§4, mutant 3) | Unicode look-alike / whitespace / case bypass of DC-1; META-mention non-interference (own re-derivation of the T98 property against the unmodified code) | No MATERIAL counterexample. `.fullmatch()` (anchored both ends) blocks suffix/prefix/case tricks; the mutation spot-check confirms this anchor is load-bearing and correctly used |
| **I3** Witness W1–W8 | full read of `_classify`/`witness()`; declaration-vs-observation attack on the **persisted-output** class (§5.8) | built two reproducers: an isolated `_classify` call, then a full-pipeline reproducer through `p3b_s5_verify.verify` | **MATERIAL — F-01** (below) |
| **I4** Evidence anchoring | read `anchored`, `_inside`, `_nws`, `witnessed_intervals`; mutation spot-check on `_inside` (§4, mutants 1–2) | boundary/off-by-one on byte containment; cross-page contiguity merge order; N-WS offset-map correctness | No MATERIAL counterexample. Both mutants (strict `<` swap, dropped upper bound) were killed by a minimal containment oracle; the real code is stricter than either mutant |
| **I5** Reconstruction (§4) | read `summary_violations`, `dated_position`/`precedence`/`contradiction_precedence`, `lifecycle_violations`; traced the `point=None` relaxation in `dated_position` used by `file_position` | checked whether the file-level relaxation (skipping the `date_applies_to_file: CONFIRMED` test when `point is None`) could leak into point-level `precedence()` for a genuine (non-membership-violating) contradiction pair | No MATERIAL counterexample: `precedence()` is always called with `points.get(t)` from the object's OWN timeline dict, so `point is None` only occurs when `t` is not a genuine member — already independently flagged (`R7-R`) by `summary_violations`'s membership check |
| **I6** Composition (§0) | read `compose()`/`program_accepted()`; mutation spot-check (§4, mutants 1–2) | negate the F-check; reorder F/U priority | No MATERIAL counterexample; both mutants killed. Strong-Kleene conjunction matches the spec exactly |
| **I7** Binding (§2, §5.8) | read `revision_violations`, `plan_violations`, `input_manifest_violations`, hash-binding checks | manual review of hash-anchoring order (contract sha, plan sha, manifest sha, per-entry file sha) | No MATERIAL counterexample found within the time spent; not independently fuzzed (see §6) |
| **I8** Statistics (§8) | read `estimate_v7`, `realized_n`, `freeze`; noted `rate == 1` shortcut also matches Python `bool True` (`True == 1`) | traced whether this reaches `estimate_v7` un-anchored | Not exploitable as found: `estimate_v7` first checks `sha(canon(rec)) == anchor` (the governance-frozen record), so a `rate: true` value would have to already be inside the anchored, human-frozen record — an engineering-hygiene OBSERVATION, not a verifier-side bypass |
| **RI-1** (deferred; assessment only) | read the characterization report; independently traced where the verifier re-derives facts vs. where it trusts the agent's transcript view | whether a token-capped, silently-truncated Read of an I(run) input (contract/addendum/plan/slice) can propagate into a false BATCH-PASS or an invalid Evidence/Reconstruction predicate | See §5 — **does not** materially invalidate S5 Evidence within the current design; **does** remain a real, accepted research-quality/completeness gap, consistent with the characterization's own finding |

## 3. Findings table

| id | invariant | severity | reproducer | observed vs expected |
|---|---|---|---|---|
| **F-01** | I3 (Witness, W8 execution-capability closure — "persisted-output substitution") | **MATERIAL** | `/tmp/.../scratchpad/r7-audit/repro_persisted_basename.py` (isolated `_classify` call) and `/tmp/.../scratchpad/r7-audit/repro_full_pipeline_persisted.py` (full `p3b_s5_verify.verify`, via the official synthetic fixtures) | **Observed:** `BATCH-PASS`, `predicates == {"U":"T","W":"T","E":"T","R":"T"}`, zero failures, for a batch where the agent's transcript shows a `Read` of a path **outside I(run)** (standing in for a hold-out/corpus/another-run/application-code path — exactly the class §5.5 lists as violations) whose displayed content is fabricated. **Expected:** `BATCH-FAIL` / `R7-W` ("read outside I(run)"), per §5.5 ("Everything else is a violation") |
| F-02 | I2 (INV-LEX / DC-1) | OBSERVATION | `/tmp/.../scratchpad/r7-audit/mutation_spotchecks.py` (mutant 3) | Confirms (does not find a live bug): `SELF_REF.fullmatch()` is load-bearing — a hypothetical relaxation to `.match()` (prefix match) would let a value like `"timeline[S0001].order <anything>"` pass the DC-1 self-reference check. The production code correctly uses `.fullmatch()`; no bypass exists today |
| F-03 | I8 (Statistics domain) | OBSERVATION | code reading only (`p3b_s5_r7_stats.realized_n`) | `rate == 1` also matches Python `bool` `True` (`True == 1`), which would skip the `isinstance(rate, bool)` guard. Not independently exploitable: `estimate_v7` requires `sha(canon(rec)) == anchor` first, so a `rate: true` value would have to already be inside the governance-frozen, hash-anchored record. Worth a defensive `type(rate) is not bool` tightening at the freezing tool, not the verifier |

### F-01 in detail

**Location:** `p3b_s5_r7_witness.py`, `_classify()`, the `elif c.name == "Read":` branch, PERSISTED sub-branch (~line 294):

```python
elif manifest.get("persisted_outputs") == "ALLOWED" and isinstance(path, str) and os.path.basename(path) in announced:
    pp = os.path.join(tr_dir, os.path.basename(path))
    ev.append({"kind": "read-input", ..., "sha256_observed": _fsha(pp), ...,
               "content_match": os.path.isfile(pp), ...})
```

**Root cause.** The addendum (§5.8) defines the persisted-output membership class as: "`p` is a file **in the session's harness tool-results directory**, announced by the harness ..., earlier than the Read." The implementation checks only `os.path.basename(path) in announced` — the requested `path`'s **directory is never checked**, and `content_match` is computed from a file at `tr_dir/basename(path)` on disk, **never from the agent's actual Read target or its actual displayed text (`c.text`)**. Any two calls agree only on basename; the code cannot tell "the agent re-read its own persisted output" from "the agent read an unrelated file that happens to share a basename with something already announced." Because this branch is reached, the `else:` branch — which is where **all** of W8's default-deny and the corpus/hold-out SEAL check (`_hit(corpus, str(path))`) live — is **never reached at all** for this call. No `R7-W` failure is raised.

**Why it is MATERIAL, not merely a robustness gap.** The full-pipeline reproducer shows `verifier(s) = BATCH-PASS ∧ ¬VALID(s)` directly, exactly the audit's stated target: `W` is reported `T` (true) while the transcript itself, taken at face value, shows a tool call that §5.5 explicitly and unconditionally classifies as a violation ("Read of any path p ∉ I(run), including another run's or another label's files, undeclared repository files, and corpus paths").

**Boundary of the finding (stated honestly).**
- The universal SEAL check (`_hit(holdout, json.dumps(c.input))`, checked for **every** call before any per-tool dispatch) is a **separate, intact** layer: it fires whenever a hold-out **token string** (label or S-id) is literally present in the tool call's input, independent of this bug. My reproducer's path text does not contain such a token (only synthetic, non-hold-out strings), and no SEAL failure was expected or observed for it.
- What is defeated is the **path-based** W8 authorization ("everything else is a violation") for the narrow case of a basename collision with an already-announced persisted-output name. This still covers a real class the addendum lists explicitly (another run's/label's files, undeclared repository files, application code, and — if the path string happens not to spell out a recognizable hold-out token — a hold-out path too).
- I did not attempt to determine whether the real harness's persisted-output basenames are practically predictable/collidable by an agent in production (that would require reading real harness internals outside this audit's scope); the finding stands independent of that, since it is a verifier-side gap in what the witness is willing to certify as authorized, not a claim about how easy the collision is to engineer today.

**Minimization.** One call sequence, one hook, one `pre_freeze` file write; no corpus, no real hold-out data, no repository writes. This is close to the smallest possible counterexample: removing either the `manifest.get("persisted_outputs") == "ALLOWED"` precondition or the basename collision restores the correct `BATCH-FAIL`.

## 4. Mutation testing summary

Full-suite mutation testing (copy `scripts/` + `scripts/tests/`, run the official suite) was not achievable in this environment (see §1); a **5-mutant spot-check** was run instead, each against a purpose-built minimal oracle, source-patched in memory (no file on disk touched):

| mutant | target | result |
|---|---|---|
| 1 | `p3b_s5_r7_verify.compose()`: negate the `"F" in values.values()` check | **KILLED** |
| 2 | `compose()`: reorder so `"U"` is checked before `"F"` (removes F-dominance) | **KILLED** |
| 3 | `p3b_s5_r7_evidence._inside()`: `<=`/`<=` → `<`/`<` | **KILLED** |
| 4 | `_inside()`: drop the upper-bound check entirely | **KILLED** |
| 5 | `p3b_s5_r7_universe.meta_violations()`: `SELF_REF.fullmatch` → `SELF_REF.match` | **SURVIVED** against my minimal oracle — but this is a **confirmation**, not a live finding: the mutant demonstrates why `.fullmatch()` is necessary, and the **real, unmutated code already uses `.fullmatch()`** (verified directly). Recorded as F-02 (OBSERVATION) |

0 of these 5 spot-checked mutants exposed an unguarded invariant in the shipped code; mutant 5 exposed what an unguarded invariant **would** look like, and the shipped code is not that. **F-01 was found by direct predicate reading and declaration-vs-observation reasoning, not by this mutation spot-check** — mutation testing did not independently surface it because the gap is a **missing** check (no directory/content verification), not a **wrong** condition a small local mutation would recreate.

## 5. RI-1 assessment

**Question:** can an incomplete input Read (harness token-cap truncation) materially invalidate S5 Evidence?

**My independent tracing (not merely restating the characterization report):**
- The **verifier's own** derivation of plans, required-file sets, hashes and manifests (`p3b_s5_r7_universe.plan_derive_v7`, `input_manifest`, `_js`/`_jl` loaders in `p3b_s5_r7_verify.py`) reads files with plain Python `open()`, never through the harness Read tool — so verifier-side structural facts are **never** subject to the harness's token cap. Truncation can only affect what the **agent** saw during its own execution, never what the **verifier** later re-derives from the same bytes.
- Evidence (§6) is built exclusively from **`read` events** (the canonical reader Bash tool, full byte-paged, hash-verified) — never from `read-input` events (the Read tool over I(run) files, where RI-1 lives). `anchored()`/`facts_index()`/`claim_violations()` never consult `read-input` records. This matches the addendum's own T96 rule ("input reads are never evidence") and my earlier code reading of §6 confirms it structurally, not just by citation.
- If a truncated Read of its own SLICE caused an agent to omit a required file's whole-file reading entirely, `E.record_violations` independently catches it (`set(seen) != set(permitted)` — permitted comes from the **verifier's own**, untruncated plan derivation), so a truncation-driven *omission* of required corpus reading is caught, not silently accepted.
- The genuine residual risk is at the **semantic/interpretive layer** (PF-12: "does a quote support a claim," "what an object means") that R7 explicitly and by design never certifies — an agent could act on a stale/partial understanding of its contract, addendum, plan or slice text (e.g., missing an escalation nuance appearing late in a very long single-line JSON file) without any mechanical signal, and produce a **well-formed but research-poorer** object. This is a real, accepted quality gap, not a soundness gap in what `BATCH-PASS`/`STATISTICS-VALID` claim to certify.

**Conclusion:** I concur with the characterization report's own finding. RI-1 does **not** threaten S5 Evidence validity or create a path to a false `BATCH-PASS`/`STATISTICS-VALID` within the current design (fail-closed for fidelity, not for completeness). It remains correctly classified as a deferred, human-decision item about research completeness, separate from — and not remediated by — anything in this audit.

## 6. Limits of this audit (stated honestly)

- **One auditor, one pass**, per the package's budget; I stopped at the first MATERIAL finding for I3 and moved on, per instruction, rather than searching I3 exhaustively for a second, independent counterexample.
- **No suite-wide mutation testing** was performed (§1, §4); the 5-mutant spot-check is a proxy, not a substitute, and its absence is the single largest coverage gap in this audit. I1 (namespace/discriminator ambiguity) and I7 (hash-binding order) received code-reading review only, with no executed counterexample search beyond manual reasoning — these are the invariants where a second, better-resourced audit pass would most plausibly find something this one did not.
- Independence is partial: same vendor/family as the implementation session (a different model generation), not a different vendor or a human reviewer, as the package's stronger recommendation would prefer.
- I did not attempt to characterize real harness persisted-output naming (out of scope, would require production-harness internals); F-01's severity as *possible* is proven, its real-world *ease of engineering* is not characterized either way.

## 7. Overall recommendation

**NEEDS-REVISION.**

Driven by **F-01 (MATERIAL)**: `p3b_s5_r7_witness.py`'s persisted-output authorization check (`_classify`, the `Read` branch's `PERSISTED` case) authorizes a Read of any path whose **basename** collides with an already-announced persisted-output basename, without checking the path's directory or the displayed content against anything real. This lets a Read of a path outside I(run) — the exact class §5.5 says is always a violation — pass through the witness with **zero** `R7-W` failure, which the full production verifier (`p3b_s5_verify.verify`) confirms end-to-end as `BATCH-PASS` with all four predicates `T`.

**Recommended scope of repair (for human/ARB decision, not authorized by this audit):** `_classify`'s PERSISTED branch should additionally require that `path`, once resolved, is itself located inside `tr_dir` (not merely basename-equal to something that is), and `content_match` should be computed against the agent's actual displayed `c.text`, the same way the declared-entry branch already does via `_content_match`.

No other MATERIAL finding was produced by this pass (I1–I2, I4–I8 code-reading and the mutation spot-checks in §4 did not surface a second false-`BATCH-PASS` path); the RI-1 deferral remains appropriate (§5). Given the coverage gaps in §6, this NEEDS-REVISION recommendation should not be read as "R7 is otherwise fully cleared" — only as "one MATERIAL false-`BATCH-PASS` path was found and is sufficient by itself to withhold ACCEPTABLE."
