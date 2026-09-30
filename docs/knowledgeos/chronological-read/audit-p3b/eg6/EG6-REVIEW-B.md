# EG-6 adversarial review (Subagent B): governed re-runs ("attempts") of a failed R7 batch

| | |
|---|---|
| Kind | Independent adversarial review of `EG6-SPEC-A.md` and `probes/probe_eg6_retire.py`. Read-only, synthetic fixtures only. |
| Method | Reran the probe myself. Read `p3b_s5_state.py`, `p3b_s5_r7_witness.py` (W2), and `p3b_s5_r7_universe.py` (namespace/legacy digest) directly, not from A's citations alone. Read `EP-01` in full (newly permitted this round). Independently checked git status and cross-document sha citations for EG-8. |

## 0. Probe re-run and non-vacuousness (task 1)

Reran `probe_eg6_retire.py` in full; output matches A's table exactly:

```
reader grammar accepts retired name as a run id: False | nested retired run path: False
canonical reader accepts --run OB9004-R7.A1 : False
baseline (attempt 1 = only attempt)                 BATCH-PASS   {}
(b) attempt 2 + retired A1 listed, digest re-frozen  BATCH-PASS   {}
(b) retired A1 present but NOT listed (no act)       BATCH-FAIL   {namespace + legacy-digest failures}
(b) listed, digest NOT re-frozen                     BATCH-FAIL   {legacy-digest failure}
(b) retired evidence edited after the act            BATCH-FAIL   {legacy-digest failure}
(b) retired evidence partly deleted                  BATCH-FAIL   {legacy-digest failure}
(a) an attempt-suffixed run dir <B>-R7.2-L01 today   BATCH-FAIL   {namespace + legacy-digest failures}
```

**Non-vacuous.** `show()` counts every failure in the report (not a filtered subset), and the "listed, digest re-frozen" row is a clean `BATCH-PASS {}` — so I can confirm nothing else in the batch silently broke from planting the retired tree. The mechanism under test (`legacy_digest`/`namespace_violations`) is a pure byte-hash walk over `<B>-*` directories (confirmed by rereading `p3b_s5_r7_universe.py`'s `legacy_digest`: it hashes `relpath\0sha256(bytes)` pairs, nothing schema-aware), so the probe's placeholder content (`{"attempt":1}`, empty READ-LOG) is an adequate stand-in — it exercises exactly the integrity property being tested, not a shortcut around it. Agree with A's own framing here.

**Reader/canonical-reader rejection confirmed directly, not just by citation:** both checks print `False` for the retired name, by simple regex mismatch (`R7_RUN` and `canonical_reader`'s pattern both require the `OB####-R7-L##[U##|S]` shape; `OB####-R7.A1` never matches). This is a structural guarantee, not a default-deny that could be bypassed by a differently-shaped call — good.

---

## E-01 — MATERIAL (task 1) — the multi-step retire operation has no stated crash-safety / atomicity design, and a partial retire could corrupt the *next* attempt's witness, not just leave an ambiguous ledger

A's mechanism (§2(b), steps 1–5) involves, in order: (1) move every planned-run directory plus the assembly into `<B>-R7.A<m>/`, (2) write `RETIREMENT.json`, (3) move the external archive `<archive>/<B>/` → `<archive>/<B>.A<m>/`, (4) re-freeze `legacy_dirs`/`legacy_ledger_sha256` via `rebind_manifest`, (5) append the state history entry for attempt m+1. Each individual directory rename is atomic on a POSIX filesystem, but the *sequence* — potentially a dozen-plus renames for a DECOMPOSED label's UNIT runs, then the manifest rewrite, then the state append — is not a single transaction. Nothing in the spec addresses what happens if the process is killed midway.

**Two distinct consequences, of different severity:**

1. **Ledger-only partial crash** (some run directories moved, manifest/state not yet touched). This *is* caught: a half-moved tree leaves some `<B>-*` directories that are neither planned, the assembly, nor a listed legacy dir — `namespace_violations` fires exactly as the probe demonstrates for the "not listed" row. Fail-closed, no integrity loss. Good.
2. **Archive-move partial crash, followed by a fresh session starting anyway.** This is the one I think is genuinely unaddressed and more serious. A's own §1 table states attempt m+1 must run in "a fresh orchestrator session" specifically because "a reused session would contain two effectful dispatches per run and fail W2" — I confirmed this check exists (`p3b_s5_r7_witness.py:230`: `f"R7-W W2 {run}: {len(aids)} dispatches with tool effects for one run (run FAILED)"`). The mechanism that makes a *fresh* session safe is that the witness reads from `<archive>/<batch>/main.jsonl` (a fixed path keyed only by batch, not by attempt), and retirement is supposed to have *emptied* that path (moved to `<batch>.A<m>/`) before the new session starts writing to it. If the archive move is incomplete or hasn't happened when attempt m+1's session begins — a real possibility if `retire`'s steps aren't sequenced as an all-or-nothing unit, or if an operator starts dispatch before confirming `retire` finished — attempt m+1's transcripts could land in the *same* `<archive>/<batch>/` path alongside leftover attempt-m files, and the witness would then see a blend of two attempts' dispatches for the same run ids. That is exactly the W2 double-dispatch failure the fresh-session design exists to prevent, except now it would fire *for the wrong reason* (accidental archive contamination, not a genuine reused session), and diagnosing it would require a human to notice the archive still has attempt-m files.

**Recommendation.** Specify `retire` as write-once/idempotent, mirroring the EG-5 `assemble` tool's own pattern (validate everything first, then write via tmp→rename, refuse on any partial/inconsistent existing state, and make it safe to re-invoke after a crash so it either completes the move or cleanly refuses with a diagnosis rather than leaving an ambiguous half state). At minimum, the runbook step should require verifying `<archive>/<batch>/` is *absent or empty* before a fresh session is authorized to dispatch attempt m+1 — a cheap, mechanical precondition check, not a design change. This doesn't affect the recommendation of option (b) itself, only its implementation completeness.

**Needs a HUMAN decision:** no — an implementation-completeness requirement, foldable into "Code changes... tests first" (§5) before `retire` ships.

---

## E-02 — MATERIAL (task 3, the core statistical question)

**Is RR-1…RR-7 sufficient for design-based unbiasedness?** Yes, in the narrow sense A claims: I verified against EP-01 directly (§1 "the frame is fixed before S5... does not depend on S5 outcomes"; §4 "drawn and frozen before any S5 output exists... a sampled label is never swapped out, whatever its S5 outcome"; §7 "a replacement draw is never made" for NOT-ASSESSABLE/AUDIT-FAILED, worst-case in the bound). HT/exact-bound unbiasedness depends only on the sample being a valid probability sample of the fixed frame and each sampled label's *eventual* outcome being observed honestly (including NOT-ASSESSABLE worst-case) — it does not depend on *why* a label ended up with a given outcome. RR-1 (sample-blind trigger), RR-2 (sample-blind tools), and the "declared before the canary" timing in RR-6 are exactly what's needed for that independence property. **On this narrow, technical point I agree with A: this is not a violation of design-based unbiasedness.**

**But the technical unbiasedness proof is not the whole question, and A's own text ("Interpretation. Re-running shifts what θ describes... It does not bias the estimate of that quantity") states the redefinition too generically.** Task 3 asked me to construct a concrete adversarial scenario. Here is one, built from mechanisms already present in the spec's own failure-class table (§4):

*Scenario.* Suppose difficulty of a label (large source volume, DECOMPOSED with many units, genuinely ambiguous or contradictory content) causes **both** (a) a higher rate of Class A failures on the first attempt — plausible, since a harder label gives an agent more chances to trip a W8 boundary, miss a required whole-file reading, or produce an internally-inconsistent object that fails an `R7-R S1-S3`/summary/lifecycle or `G-xx` content gate — **and** (b) genuinely higher disagreement with an independent re-analysis even among *passing* attempts, because the content really is harder to read consistently. Now apply the retry policy: a first attempt that fails a *content-consistency* gate (not a pure tool-use violation) gets discarded and retried. If an agent's second attempt on the same hard label is systematically more conservative — more escalations, fewer positive birth/timeline assertions, more `NOT-EVIDENCED`-style hedges — simply because that reduces the chance of tripping another consistency gate, then the *accepted* object for that label is not a random draw from "how S5 reads this content," it is specifically a draw from "how S5 reads this content **conditional on producing something internally consistent enough to pass gates**." Two conservative, hedging readings are mechanically *less likely* to substantively contradict each other than two committal ones, so this selection effect would tend to **lower** apparent θ_D specifically on the hardest labels — without violating the HT unbiasedness proof at all, because the proof only guarantees unbiasedness for D as measured on whatever the accepted objects turn out to be, not for D as it would have been measured on unfiltered first attempts.

This is a real risk *of interpretation*, not of estimation. A's own text gets halfway there ("θ describes... disagreement among verifier-accepted objects... under the declared retry policy") but doesn't name the specific mechanism, and treats the redefinition as a settled, self-evidently-fine fact rather than a risk that needs to be watched. I think this needs more than the current blanket approval line.

**Recommendation (two parts, both cheap, neither blocking option (b) itself):**
1. Name the mechanism explicitly in the pre-registered policy text (§6 / HD-6.2): "the accepted-object population may differ systematically from an unfiltered first-attempt population if retry-driven behavior change correlates with difficulty; θ is defined and reported as measuring the retry-surviving population, and this is a named, accepted limitation, not an oversight."
2. Add a reporting requirement: record the number of attempts per label as a covariate (this is nearly free — RR-4/E-3 already require recording attempts and causes per batch and class), and cross-tabulate adjudicated discordance against attempt count in the audit report. This doesn't prevent the effect, but makes it observable — if labels needing 2–3 attempts show markedly different discordance than 1-attempt labels, that is direct evidence the mechanism above is or isn't operating, and it costs nothing beyond data already being collected for E-3.

**Is M = 3 justified?** Not quantitatively. EP-01 §4 derives its sample sizes from explicit formulas and a stated cost/precision table ("Why these sizes"); the EG-6 spec's M = 3 (HD-6.3) has no analogous derivation anywhere — no estimate of expected Class-A/Class-X failure rates, no statement of what NOT-ASSESSABLE rate M=3 is expected to produce, no sensitivity table the way EP-01 gives one for sample sizes. Given NOT-ASSESSABLE labels count as worst-case (discordant) in the primary bound (EP-01 §7), the cap directly affects the headline confidence bound, exactly the kind of choice EP-01's own methodology treats as needing an explicit justification, not a bare default. Recommend either a provisional justification (even a rough prior estimate) or an explicit statement that M = 3 is a placeholder to be revisited once canary data gives a real failure-rate estimate, analogous to how the canary itself is explicitly "an instrument test, not evidence for any hypothesis" yet still informs later decisions.

**Needs a HUMAN decision beyond HD-6.2:** yes — recommend HD-6.2 be split or amended to include the explicit interpretation-risk statement and the attempts-per-label reporting requirement as named commitments, not left implicit in "declare the policy before the canary."

---

## E-03 — MATERIAL (task 4) — Class A bundles two epistemically different failure types, and the line between "not a measurement" and "a real, if flawed, measurement" is drawn too broadly

Task 4 asked directly: "Where is the line between an inadmissible run and a real measurement?" A's Class A list (§4) is: `W1-ZERO-TOOL, W1-DECLINED, W8 unauthorized calls with a correct prompt, R7-U object Σ/typing/META, R7-E anchoring/coverage/claim/READ-LOG, R7-R S1-S3/summary/lifecycle, the historical G-xx content gates, v2.8 §2.6 id violations`. I think this list mixes two genuinely different things:

- **Pure execution/protocol failures** — `W1-ZERO-TOOL`, `W1-DECLINED`, unauthorized tool calls with a correct prompt: here the agent never completed a valid execution of the reading task at all. Discarding and retrying these is clean — there is no "measurement" to discard, because none was taken.
- **Substantive content/internal-consistency failures** — `R7-R S1-S3`/summary/lifecycle, "the historical G-xx content gates" (G-05 statuses, G-12 pointer/register-id detection, etc.): here the agent *did* read and produce a complete object, and it failed because something about the *content* it asserted was internally inconsistent (e.g., a timeline summary that doesn't match its births, a status value not consistent with tier). This is not obviously a procedural failure — it can be the direct product of a genuine, if wrong, reading of ambiguous or difficult material. Discarding this and retrying is functionally "keep re-measuring until the reading happens to be internally consistent," which is a materially different policy from discarding runs that never took a measurement at all, and it's exactly the mechanism E-02's adversarial scenario depends on.

I'm not asking A to move these into Class D (a genuine content-consistency failure is not necessarily a repeatable defect the way a plan/contract-rule violation is) — I'm asking that the spec **name this distinction explicitly** and either justify treating group (ii) the same as group (i) for re-run purposes, or split them (e.g., a narrower Class A′ for content-consistency failures, still re-runnable but flagged and reported separately in E-3 with its own count, so a reviewer can see how much of the "protocol failure rate" is pure execution failure versus substantive-content failure). Right now the single undifferentiated bullet list makes this an invisible modeling choice rather than a stated one.

**Any class the verifier cannot distinguish mechanically?** A's own text already partially answers this — "X is set only with a recorded runbook or prompt diagnosis" — which means Class X is **not** purely mechanical: assigning it requires a human/engineering diagnosis as a precondition, and only *then* is the tool's classification mechanical (read the tag, check the recorded diagnosis exists). Everything else (A/D/S) genuinely is derivable from the verify report's failure tags alone, and "ambiguous → default to D (stop, human)" is a sound fail-closed default. I'd just make this more prominent in the write-up — as currently phrased it reads as if the whole classifier is tag-driven, when Class X specifically requires a human input as a precondition every time.

**Needs a HUMAN decision:** yes, fold into HD-6.4 — currently HD-6.4 only asks "class A re-runnable, versus only class X" (answered yes); it should also ask whether the content-consistency subset of class A needs its own visible count in E-3, given E-02's finding.

---

## E-04 — MINOR (task 5) — `check_evidence`'s run_id-based uniqueness check is structurally degenerate across attempts under option (b), but this is not currently a live confusion risk

I grepped `p3b_s5_verify.py`, `p3b_s5_r7_verify.py`, `p3b_s5_state.py`, `p3b_s5_r7_universe.py` for "attempt": **zero matches anywhere.** No verify-report field, state field, or universe concept currently distinguishes attempts. Under option (b), every attempt of a batch uses the *same* run ids (`<B>-R7-L##…`, assembly `<B>-R7`), so a verify report's `body.run_id` is identical across all attempts of the same batch — `check_evidence`'s existing check (`body.get("run_id") != run: raise`, `p3b_s5_state.py`) provides **zero discrimination** between attempt 1's and attempt 2's report on that basis alone.

I traced through whether this is a live risk and concluded it mostly isn't, structurally: by RR-1/RR-3, a batch can have **at most one** report that ever satisfies `result == PASS` across its whole attempt history (once any attempt passes, no further attempts are triggered), and only FAILED/INCOMPLETE attempts are ever retired (RR-1), so a retired attempt's own report can never itself read PASS. So the "wrong passing report from the wrong attempt" confusion A's T-05 seems aimed at can't actually arise by construction.

What *is* missing is that A's spec text ("a report's sha is never reused across attempts") doesn't name the actual mechanism. I checked and it's cheap to build: `_append()` already stores `e["evidence"] = {"path", "sha256", "kind"}` on every transition's history entry (`p3b_s5_state.py`), so the state module already has, for free, a complete history of every evidence sha256 ever accepted for the batch. A "sha not previously used for this batch" check is a straightforward addition against that existing data — no new field, no verifier change. Recommend the spec name this mechanism explicitly (cross-check against `st["history"]`'s recorded evidence shas for the batch) rather than leaving "attempt-aware evidence" as an unspecified bullet, so an implementer doesn't rely solely on the (now-degenerate) run_id check.

**Needs a HUMAN decision:** no — implementation-completeness note.

---

## E-05 — confirms EG-7 independently, and extends its scope check (task 5)

I read `check_evidence` directly (`p3b_s5_state.py`): `if body.get("result") != "PASS": raise StateError(f"{to}: evidence result is not PASS")`. Independently confirmed the defect A reports: the R7 verifier's protocol output is `BATCH-PASS | BATCH-FAIL | BATCH-UNDETERMINED` (unchanged, and correct per its own protocol), and the string `"PASS"` literally never appears as a complete `result` value for an R7 batch, so `transition B VERIFIED` is unconditionally refused for every R7 batch today. I agree the fix (accept `BATCH-PASS` for revision 7) is a pure state-module string-comparison change — it touches no verifier output and no contract text, so I agree it is non-material and can be done as a small, separate, easily-tested change, composed with (not a substitute for) the attempt-awareness work in EG-6.

**One thing I could not check:** `AUDITED` evidence goes through the *same* `check_evidence` function but against a different script (`EVIDENCE_SCRIPT["AUDITED"] = "scripts/p3b_s5_audit_record.py"`), which is outside my permitted read set this round. I can't confirm whether that script's own report format uses "PASS" or something else for its `result` field, or whether it has an analogous R7-specific vocabulary mismatch. Recommend the EG-7 backlog item explicitly check `p3b_s5_audit_record.py`'s output vocabulary too, not just the S5 verifier's, before assuming the fix is complete.

---

## E-06 — MATERIAL (task 6, EG-8) — confirmed, and worse than a prospective risk: an already-executed governance act (Freeze 1) already omits EP-01's provenance

Confirmed independently: `git status --porcelain -- audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md` returns `?? ...` — genuinely untracked, no `git log` history at all, not merely "uncommitted changes" to a tracked file. Its own header states EP-01 "specifies addendum items 17–18... consumed, not repeated" by B1, and B1 v2's header explicitly names it as **Base**.

I checked every sha-citation line I have access to for a binding of EP-01's hash: **none exists.** Most tellingly, `audit-p3b/20260928_FREEZE-2-PROPOSAL.md`'s own traceability line reads *"Rests on Freeze 1 (G-LOG-0094; B1 v2 `d6b23886…`, `prereg_v1.py` `eb6291ab…`), unchanged"* — this is a citation from an **already-executed** freeze act (per Freeze-2's own framing, Freeze 1 has already happened), and it binds B1 v2's sha and the instrument script's sha, but conspicuously omits EP-01's, even though B1 v2's own header calls EP-01 its "Base" and Freeze 1's own scope (per B1 §8) explicitly claims to fix "the decision fields" and "failed / NOT-ASSESSABLE handling" — both of which are EP-01's own content (§3, §7), not B1's. So this is not a hypothetical future risk EG-8 is flagging preemptively; it's a **gap in a governance act that has already been recorded as complete.** As it stands, EP-01 could be silently edited (a decision field added or removed, a sample-size formula changed, the failed-unit worst-case rule altered) and nothing in the chain of committed, hash-bound documents would detect it, because nothing points at EP-01's bytes.

**Minimal remedy (agree with the orchestrator's framing, with one addition):**
1. `git add` and commit `audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md` under its owner's authorship, so it at minimum gets a real history/diff trail going forward.
2. Bind its resulting sha256 in a governance act — ideally the same act should also retroactively supply the missing citation in Freeze 1's own record (or an amendment note pointing from G-LOG-0094 to the new EP-01 binding), since as it stands the *already-recorded* Freeze 1 act is the one with the gap, not just future documents that cite EP-01.

**Needs a HUMAN decision:** yes — this is exactly a provenance/governance-act completeness question, appropriately a human act, not a design or code question. Low cost, should not block EG-6's own approval, but should not be deferred indefinitely either, since every later document (B1, Freeze-2, EG-5, EG-6 itself) transitively depends on EP-01's unverified bytes.

---

## Checks that came back clean

- **Verifier/reader-neutrality (task 2).** Confirmed by rereading the code (not just A's citations): `namespace_violations`/`legacy_digest` (`p3b_s5_r7_universe.py`) are the sole mechanism, and they are pure byte-hash walks with no schema awareness — genuinely untouched by option (b). The reader's default-deny and `canonical_reader` both reject the retired name by regex mismatch, confirmed directly in the probe's own output (`False` / `False`). No verifier run-identity check (`V:300-341`) needs to change, since every attempt's active-path records still carry `run_id = <B>-R7` exactly as v2.8 §2.6 already requires — I don't see a way attempt m's *active-path* records could be mistaken for attempt m+1's, since retirement removes attempt m's footprint from the active paths entirely (modulo E-01's atomicity concern, which is about the *move* itself, not about confusion once it's complete).
- **"Retire a passing batch to fish for a different result."** Confirmed blocked at the state layer by direct inspection of the *existing*, already-in-production analog: `new_run`'s guard `if b["state"] not in ("FAILED", "INCOMPLETE"): raise StateError(...)` (`p3b_s5_state.py`). The proposed `new_attempt` is described as extending this exact pattern for revision 7, and I have high confidence it will, since this state-guard convention is already established and tested elsewhere in the same module (T-02 in A's own RED list explicitly targets this). No laundering path found.
- **W2 double-dispatch.** Confirmed directly in `p3b_s5_r7_witness.py:230`: `f"R7-W W2 {run}: {len(aids)} dispatches with tool effects for one run (run FAILED)"`. Matches A's citation exactly.
- **RR-1…RR-7 as a set, apart from E-02/E-03/E-04 above:** internally consistent with EP-01's stated design (frame fixed before S5, no replacement draws, worst-case NOT-ASSESSABLE handling, blindness extended to all attempts per RR-4, matches EP-01 §4's "the auditor never sees the S5 object... or read log" generalized correctly to "for all attempts").

---

## Verdict

**SPEC-NEEDS-REVISION.**

The core design decision — option (b), retire a failed attempt into a governed, tamper-evident Legacy(B) member with no reader or verifier change — is sound, well-evidenced (I independently reran the probe and reread every code path it exercises), and clearly superior to option (a) for the stated reasons. I found no flaw in the *mechanism itself*. The findings above are about completeness, not correctness of direction:

1. **E-01 (atomicity/crash-safety of `retire`)** — needs an explicit idempotent/resumable design before implementation, not a human decision.
2. **E-02 (retry-conservatism interpretation risk)** — needs the mechanism named explicitly in the pre-registered policy text, plus an attempts-per-label reporting requirement; recommend amending HD-6.2 rather than leaving it as a blanket approval.
3. **E-03 (Class A bundles two epistemically different failure types)** — needs the distinction stated explicitly (name it and either justify or split it), fold into HD-6.4.
4. **M = 3 (HD-6.3)** — currently undertaken with no quantitative justification; recommend a provisional derivation or an explicit "placeholder pending canary data" label.
5. **E-04 (evidence-sha reuse mechanism unspecified)** and **E-05 (EG-7's fix scope re: AUDITED evidence)** — both minor, implementation-completeness notes, no human decision needed.
6. **E-06 (EG-8)** — confirmed, and sharper than a prospective risk: an already-executed governance act (Freeze 1 / G-LOG-0094) already omits EP-01's provenance. Needs a human governance act (commit + bind sha), recommend not deferring it.

None of these require abandoning option (b) or reopening the verifier/reader. All are addressable within the same EG-6 slice before it goes forward.
