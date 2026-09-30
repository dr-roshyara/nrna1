# S5 final human-decision package (pre-execution gate)

| Field | Value |
|---|---|
| Status | **Decision input only. No decision is recorded here.** No S5 batch, no S5a/S5b/S5c, no test pass, no unseal. Nothing was changed in the S5 methodology or infrastructure to produce this package |
| Reconstructed from | G-LOG-0041, G-LOG-0042, G-LOG-0043; session log `.claude/sessions/2026-09-25.md`; `.claude/CONTEXT.md`; git (HEAD `f7fd3b509`) |
| Verification run for this package | 2026-09-25, 03:23 local; results below. Hold-out identifiers were never printed |

## 1. Reconstructed state

### A. Frozen
- **Core v1.7:** `prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md`, sha256 `38021aa4…502d12` (G-LOG-0034).
- **H-19 addendum v1.5;** seal **HS-3d32dd44d162, SEALED**.
- **Hub list** `P3B-S5-HUBS.jsonl`: 66 hubs, body `58aed960…`; T4 > 4,081 (G-LOG-0033).
- **S5 plan v2.3.2:** `audit-p3b/20260925_0106_s5-plan-v2.3.md`, sha256 `f89a1682…c7de1` (§P approved, G-LOG-0041).
- **K = 64;** ISC is not an identity class (G-LOG-0041).

### B. Approved (G-LOG-0041)
- MAJOR-1 symmetric removal · MAJOR-2 label-cluster permutation test · MAJOR-4 provenance chain · MAJOR-5 OMQ-07 mapping (2).
- H-12 limits · P-1 re-run of the pilot labels · orchestrator-flags extract (if the quarantine gate passes) · model rule.
- OMQ-16 · H-11a–d · H-16 · H-15 (five generators, 14 classes) · H-17 · H-18 · OMQ-17 · OMQ-18 · §K.
- **Authorization:** infrastructure construction and review **only**.

### C. Verified (G-LOG-0043, re-checked for this package)

| Check | Result |
|---|---|
| Independent review verdict | `INFRASTRUCTURE: VERIFIED EXCEPT M4-RESIDUAL (HUMAN RULING)` |
| Test suites | operations 105 OK; verifier / quotes / snapshot / audit-record 62 OK; S5a 7 + 19 + 7 + 1 + 2 all passing (including fresh brute-force exact-p) |
| Manifest | `prepare --check` IDENTICAL: body `88b5b8b4…`, 396 batches (382 + 14 hub), 1,975 labels exactly once |
| Guard `seal` | SEALED |
| Guard `grep` | 0 violations over 48 in-scope files. 15 pre-seal-module findings are reported separately and not in scope |
| Guard `inputs` | pass plan, O-25 and manifest all allowlisted (exit 0) |
| Allowlist refusals | 9 of 9 adversarial paths refused, with messages carrying counts only |
| Consistency: pass plan | K = 64 (per generator 13/12/13/13/13); order SHARED-GROUP, DEPENDENCY, NOTATION, COCHANGE, TYPE-SIM; G-TIMELINE-SIM omitted; k ∈ {2, 3}; r = 2; H-18 cap 10 with 50 files; ISC non-identity; gate "blinding FULL"; BH q = 0.10 over the 64 cells; plan and protocol hashes match |
| Consistency: contracts | batch contract `b9302363…` carries the hub exception; pass contract `22d79fca…` is blind-file-only with the dispositions schema |
| Consistency: O-25 | produced by a committed script; hash matches the pass plan |

### D. Open human decisions
Sections 2–5 below, plus: approve the pass plan O-12 and the operations spec (O-8/9/11/18/21), record the frozen hashes, and authorize execution.

### E. Forbidden until execution is authorized
- dispatching OB0004-R2 or any batch;
- materializing production slices into the repository;
- running S5a / S5b / the test pass;
- unsealing H-19 or running S5c;
- scoring predictions;
- changing the frozen methodology, K, the BH family or q.

## 2. H-19 exposure incident: decision record (human ruling required)

**Facts** (G-LOG-0042; mechanically re-checked; counts and booleans only):

| Question | Result |
|---|---|
| Hold-out identifiers in S5-era repository artifacts (52 files changed since the S5 build began) | label names **0**; hold-out S-ids **1**, in one file: `P3B-GOVERNANCE-LOG.md` line 420, which is **G-LOG-0018** (the sealing entry, 2026-09-24): the human's scope note on the Shapiro PDF source. It predates S5 and is unrelated to the incident |
| Hold-out identifiers in S5 commit messages, the S5 session log, or the S5 block of `CONTEXT.md` | 0 / 0 / 0 |
| Hold-out identifiers in S5 infrastructure outputs (manifest, contracts, pass plan, O-25, flags extract) | 0. The only S5-era hit is the pre-S5 governance line above |
| Materialized production slice directories | **0** |
| S5 run directories (OB / OT / OA) | **0** |
| S5 research records (register, pass snapshot, cross files, state file) | **none exist** |
| Did any identifier reach an S5 production agent? | **no**: no production agent has been dispatched |
| Did any identifier reach an S5 research record? | **no**: none exist |
| Seal file unchanged since its sealing commit? | **yes** (worktree unchanged; sha256 equals the sealing commit's). State SEALED; `assert_sealed()` passes, including the approved hold-out file-list hash |
| Where did the name appear? | only in the tool output of the round-1 **infrastructure-review agent**, which has ended. That agent produced no research records. The name is not in its report |
| Cause fixed? | yes (M1 allowlist, M2 redaction; verified in round 2 and the confirm pass) |

**Alternatives** (addendum §6: *"Reading an HF file while sealed, or a hold-out record reaching an agent input, is a seal breach (P3B-ESC). The human then decides whether predictions may still be scored."*):
- **A.** Treat it as an H-19 seal breach: record a P3B-ESC, and prohibit S5c scoring unless separately re-authorized. The S5 batches, S5a and S5b could still run; the hold-out's predictive use would be lost or deferred.
- **B.** Treat it as an infrastructure-review exposure that did not contaminate the sealed hold-out for S5c. The rationale to document: no HF file content was read; the exposed item was one label **name**, not a record; the exposure was confined to a review agent with no research output; S5 research agents and records are untouched; the seal file and its hash are unchanged. This needs explicit human approval and a governance record.
- **C.** Any other interpretation, proposed and approved by the human.

## 3. M4: G-SHARED-GROUP blinding (human ruling required; no recommendation)

**Observed (pre-S5, in memory, round 2):** members share a source pointer in **177 of 530** G-SHARED-GROUP candidate sets, but in **20 of 1,024** control sets. The frozen §9E.2 item 6 PARTIAL token list names group id, notation/alias and edge endpoints, not shared source pointers. §9E.2 item 4 says fields that directly encode a defining link are stripped.

| Criterion | **A. Strip shared-source pointers** from the blinded representation | **B. Accept FULL** under the literal frozen token rule | **C. Declare G-SHARED-GROUP PARTIAL** |
|---|---|---|---|
| Candidate/control information | Candidates (and controls, symmetrically) lose shared-source pointers. Sets whose members' evidence rests only on the shared source may lose most or all of their evidence, which leads to more UNDETERMINED and symmetric removal. The count is not measured (computable pre-S5 if wanted) | unchanged | unchanged |
| Blindness | the measured role cue is removed | the role cue stays (177/530 vs 20/1,024) | recorded as not blind |
| Frozen protocol | §9E.2 item 4 ("fields that directly encode a defining link are stripped") is arguably satisfied for CO-OCCURRENCE groups, whose defining link is shared files. For the other group kinds it goes beyond the item 4 examples. The item 6 token list is unchanged | literal compliance | item 6 says blinding_level "is set by script, not by judgment". A human declaration needs a token-list extension or an explicit override |
| §26 amendment | probably **not**, if treated as an item 4 stripping implementation recorded in the pass plan (O-12); **yes** if read as changing item 6. The human decides the reading | no | **likely yes**: it changes the item 6 rule, or overrides "set by script" |
| 64-cell family | unchanged (K = 64) | unchanged | unchanged registration. The 13 G-SHARED-GROUP DISCOVERY cells become REPORT-ONLY-PARTIAL-BLIND (p = 1). With G-NOTATION and G-TYPE-SIM also PARTIAL (26 cells), **39 of 64** cells would be report-only; only G-DEPENDENCY (12) and G-COCHANGE (13) would remain testable |
| p-values | test mechanics unchanged. Analyst outcomes change because the input changes | unchanged mechanics. If analysts use the cue, outcomes may be biased towards candidate/control differences (a failure of A1), which **inflates the chance of DIFFERENTIATED** in these cells without any error in the permutation arithmetic | the 13 cells enter BH with p = 1 |
| Interpretation | a G-SHARED-GROUP DISCOVERY result rests on a blinded comparison with the shared pointers removed | a G-SHARED-GROUP DIFFERENTIATED result carries a documented role-cue caveat | no G-SHARED-GROUP DISCOVERY claim can reach DIFFERENTIATED in this pass |
| Implementation available? | **mostly**: the stripping mechanism exists for G-COCHANGE. Extending the token definition to G-SHARED-GROUP needs a small code change, tests and re-review | **yes**, current state | needs a token-rule change, or a script flag plus a governance record |
| Reproducibility | deterministic; changes blind-file content and hashes | unchanged | deterministic once specified |

## 4. G-LOG-0042 remaining rulings (decision sheet)

| # | Item | Current implementation | What the plan / frozen text fixes | Genuinely unspecified | Changes methodology? | Narrow decision statement (for approval or change) |
|---|---|---|---|---|---|---|
| 1 | Family `.md` allowlist | `FAMILY_MD`: `20-FAMILIES/<label>.md` only for S5-population labels, resolved path, full match | §9.8 step 1 names the family .md as an input; the S4 contracts supplied it; plan §M item 6 omits it from the allowlist | whether the allowlist includes it | no. It restores a frozen §9.8 input; the §6 quarantine still applies | "S5 tooling may read `20-FAMILIES/<S5-population label>.md` (the §9.8 step-1 family .md), subject to the addendum §6 quarantine check." |
| 2 | §6 "moves" | scanner writes count-only records (allowlisted path, record index, counts); the source stays in place; slices exclude the text | addendum §6: "a script moves to `P3B-HOLDOUT-QUARANTINE.jsonl` every discovery record or agent input that …" | whether "moves" requires copying record content | no effect on inference; it concerns where quarantined evidence is kept | (a) "Counts-only records plus exclusion from agent inputs satisfy §6." **or** (b) "§6 requires a verbatim copy in the quarantine file (readable only by the scanner)." |
| 3 | S-id range notation | ranges are not expanded. 16 slices carry ranges spanning 42 hold-out ids | §3 name matching; §6 "cites an HF S-id" | whether a range spanning an HF id is a citation | changes which agent inputs are quarantined; population unchanged; (b) changes slice contents and manifest hashes | (a) "Only explicitly written S-ids are citations; ranges are not expanded." **or** (b) "A range spanning an HF id counts as citing it; the affected text is quarantined." |
| 4 | G-TYPE-SIM keyword table | `S5A-TYPE-SIM-TABLE-1` (sha in the pass plan) | A.6: "a fixed, versioned keyword table in the script" (examples only) | the table content | pre-registers a generator parameter (no outcome dependence) | "Approve `S5A-TYPE-SIM-TABLE-1` (sha as recorded in the pass plan) as the A.6 table for the first pass." |
| 5 | Control draw | relax only when the pool is empty, one variable at a time, cumulative; r_s = 1 when no disjoint second control exists at that level | A.7: "if the pool is empty, relax …"; plan §H.1: "a candidate with 1 control available keeps it (r_s = 1)" | whether to relax further to find a second control instead of accepting r_s = 1 | minor (control composition) | "Confirm: relax only on an empty pool; accept r_s = 1 without further relaxation." |
| 6 | Stability bootstrap B | 2,000 | plan §K: within-group bootstrap, root 20260930 | B | no (Monte-Carlo precision of the CI only) | "Bootstrap B = 2,000." |
| 7 | Re-analysis rounding | ceil(0.2·N), `random.Random(20260929)` | plan §K: "20% of all blinded units", root 20260929 | rounding and stream | negligible | "Re-analysis sample size = ceil(0.2·N) using `random.Random(20260929)`." |
| 8 | Audit floor | max(5, floor(0.1·N)) seeded records, on top of the mandatory records | OMQ-09: "10% of records per batch (minimum 5)" | floor vs ceil | negligible | "Seeded audit records per batch = max(5, floor(0.1·N)), in addition to all §21 mandatory records." |
| 9 | S5a read run id | `OA####-R2` accepted by the reader | plan §G.3: candidate-check reads ≤ 2 per set, through the reader | the run-id form | no (operational) | "S5a/S5b candidate-check reads use run id `OA####-R2`." |

## 5. Pass plan and operations spec

- **Consistency:** consistent with v1.7, plan v2.3.2, G-LOG-0041 and G-LOG-0043 (§1C table).
- **Known frozen-rule consequence (not reinterpreted):** measured pre-S5, G-NOTATION is PARTIAL (97/240 sets) and G-TYPE-SIM is PARTIAL (573/866). Their DISCOVERY cells (13 + 13 = 26) will be REPORT-ONLY-PARTIAL-BLIND and enter BH with p = 1.
- **Inconsistency to note:** plan §G.3's cross-object cost estimate (≈ 0.42 GB) assumed analysts read object records. Under frozen A.7, which the pass contract follows, analysts receive the blind file only, so the actual reading cost is lower. The estimate is not a gate.
- **Approvals needed:** the pass plan O-12 (`audit-p3b/S5A-PASS-PLAN.json`, output `6a572655…`, PROPOSED) and the operations spec (O-8/9/11/18/21, including the §6 runbook).

## 6. Exact authorization required for OB0004-R2

A governance entry by the human that:
1. records rulings on §2 (H-19), §3 (M4) and §4 items 1–9;
2. approves the pass plan O-12 (by output_sha256) and the operations spec;
3. records the frozen hashes (manifest body `88b5b8b4…`, batch contract `b9302363…`, pass contract `22d79fca…`);
4. explicitly authorizes execution of OB0004-R2, under the §6 runbook, with the stop conditions and review cadence stated.

If a ruling requires implementation changes (for example M4 option A or C, or §4 item 3 option b), those changes are made, tested and re-reviewed **before** authorization, and the hashes above change accordingly.

**S5 EXECUTION NOT AUTHORIZED — AWAITING HUMAN GOVERNANCE DECISION**
