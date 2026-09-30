# GATE-INTEGRITY-AUDIT-02 — do the gates actually execute?

| | |
|---|---|
| **Kind** | focused gate-integrity audit (a successor to `governance/GOVERNANCE-INTEGRITY-AUDIT-01.md`). ⛔ No new methodology, gate framework or protocol; nothing repaired |
| **Evaluated state** | HEAD **`343f87468`**, taken as a `git archive` snapshot. Control plane (`gate-runner.py`, `gates.yaml`, `governance-state.yaml`, `gate-schema.yaml`, `fixtures/`, `.claude/hooks/`) **byte-identical** to audit-01's baseline `6cffbea5e` (`git diff --stat` empty) |
| **Method** | the full 55-run battery of audit-01 was re-run on HEAD (all runs on isolated copies). A per-commit history run of the activated gates covers 26 commits. `admit.py` got negative controls on a scratch mirror. Artifact inspection covered `PREFLIGHT-LOG.jsonl`, `RESEARCH-STATE-SYNC-01.md`, `reconstruction-records/*.gates.json` and `PROPOSED-GATES-RCI.yaml` |
| **Modified** | nothing but this file. Research, registries, gates, activation and theory are untouched (the scratch copies were verified to leave the real `evidence/` unchanged) |

Vocabulary: EXECUTED_AND_VERIFIED · EXECUTED_BUT_INCOMPLETE · METADATA_DERIVED · ARTIFACT_DERIVED · FAIL_OPEN · STALE_RESULT · NOT_IMPLEMENTED · NOT_INVOKED · UNKNOWN; REPORTED_PASS vs EXECUTED_PASS.

---

## 1. Authoritative gate inventory (existing mechanisms only)

| # | Gate / check | Authoritative source | Implementation | Invocation | Current reported state | Verified state (this audit) |
|---|---|---|---|---|---|---|
| M-1 | **KOS-G-001/002/003/010/022** (activated) | `governance/gates.yaml`; P1-Q1, Q60, §37 | `gate-runner.py` (checks at l.85–195) | `governance-preflight.sh` (door); **wired into no hook** (V). P2P §0.3: mandatory at the 2 phase boundaries; README §"Phase-1 completion verification": at the end of every Phase-1 work unit | last recorded **CLEAR** (PREFLIGHT-LOG PF-0007) | ⛔ **BLOCK at HEAD**: KOS-G-003 FAIL (§4) |
| M-2 | other AUT gates in `gates.yaml` (011, 012, 020, 021, 023–027) | same | runner | same door; **not activated**, so they measure but never block | — | measured; not activated |
| M-3 | REVIEW/HUMAN gates (013, 040, 042–046, 060, 061) | `gates.yaml` | none by design | never auto-evaluated | — | correctly never PASS |
| M-4 | **KOS-G-070…073** (`registry_matches_manifest` · `manifest_is_current` · `every_read_has_receipt` · `no_source_drift`) | `governance/PROPOSED-GATES-RCI.yaml` | ⛔ **no runner implementation**: none of the four `check:` names is in `CHECKS` (the runner has 14) | none as gates | proposed | **NOT_IMPLEMENTED** as gates. ⚠️ Comparable logic runs inside `evidence/admit.py --audit` (exit 1 today), outside the gate path |
| M-5 | **evidence admission** `evidence/admit.py --resolve/--receipt/--audit` | research-produced (`163a0f996`); **unreviewed** (GIA-9) | yes | manual, per read (receipts exist for the post-`163a0f996` reads) | — | **EXECUTED_AND_VERIFIED** for the conditions tested (§5) |
| M-6 | **§9A per-file gates** (12 per file) | P1P §9A | ⛔ **none**: `reconstruction-records/*.gates.json` are **hand-written**; no script reads them (V: no `.py` references `gates.json`) | hand evaluation by the research session | F0001/F0010 **12/12 COMPLETE**; F0026/F0027/F0032 **11/12** | **NOT_IMPLEMENTED** (machine) → every §9A result is **METADATA_DERIVED / REPORTED_PASS** |
| M-7 | P2P Q1–Q61 · P1P §37 (14 checks) · §45 Gates 0–7 | protocols | only the subset mapped into `gates.yaml` | — | various prose claims | mostly **DOCUMENTED_NOT_IMPLEMENTED** |
| M-8 | README §"Immediate task" items 9–12 (applicable gates · scripts exist · executed · actual result) | `prompts/readme.md` (committed `5bb6a98a2`; read at session start per `6fffbed4c`) | instruction | agent-followed | — | ⛔ **not followed** in `RESEARCH-STATE-SYNC-01` (§8) |

## 2. Execution path, per link (M-1, activated gates)

```
RULE (gates.yaml) ✅ → IMPLEMENTATION (runner) ✅ → INVOCATION ⚠️ manual only; last logged run PF-0007
→ INPUTS ✅ working tree → CHECKS ⚠️ partly fail-open → EXIT ✅ runner 0/1/3
→ INTERPRETATION ⚠️ door maps 3→"BLOCK" → RECORDED STATE ⛔ hand-appended JSONL, no commit binding
```

## 3. Is PASS based on execution? (classification of every reported PASS)

| Reported PASS / CLEAR | Where | Basis | Classification |
|---|---|---|---|
| PF-0001…PF-0007 "CLEAR" | `PREFLIGHT-LOG.jsonl` | the door was run at the time (verdict strings copied), **with no commit hash or input digest** | **STALE_RESULT**: the state has changed since PF-0007 (v0.9) through ≥ 26 commits; the current execution is BLOCK |
| §9A "12/12 COMPLETE" (F0001, F0010) and all "SATISFIED" rows | `reconstruction-records/*.gates.json`; `PHASE1-CONFORMANCE.json` | hand-written by the executing session; no script | **METADATA_DERIVED** (REPORTED_PASS) |
| "Phase 1 COMPLETE" for F0001/F0010 | `343f87468`; `RESEARCH-STATE-SYNC-01.md` | §9A sheets only; **the activated machine suite was not run and was failing** | **REPORTED_PASS, not EXECUTED_PASS** |
| gate PASS for missing/empty inputs (G-010, G-022, G-002) | runner | `_jsonl()` returns `[]` for missing files | **FAIL_OPEN** (re-confirmed at HEAD: B01, B04, B06, B07, B09) |
| CLEAR with every activated gate re-statused `proposed` | runner | activated-but-inactive gates count as "applicable" without evaluation | **FAIL_OPEN / silent CLEAR** (D09, re-confirmed **at a genuinely failing state**, §6) |

## 4. The current state fails an activated gate, and nobody ran it

| Fact | Evidence |
|---|---|
| At HEAD, **KOS-G-003 FAIL**: `dangling={"GAPS.jsonl": ["F2841","F2843","F2844","F2845","F2847"]}` | runner `--json` on the snapshot |
| **First failing commit: `41d2f7af6`** ("F2847 — the corpus contains a COMPLETED theory", 03:20). Every commit before it is CLEAR; **all 15 after it are BLOCK** | the activated gates run on each of 26 commits |
| The five IDs are **correct canonical IDs** of files not in the research registry. This is **the G02 false-FAIL class** predicted by audit-01 (§7 FF-1), now **live** | `list_of_files_to_read.log`; audit-01 G02 |
| **No preflight was logged after PF-0007**, and no research artifact after `41d2f7af6` mentions the runner, the door, a KOS-G result or `MACHINE_GATE_CHECK` | `PREFLIGHT-LOG.jsonl` (7 rows); grep over the diff `41d2f7af6..HEAD` |
| P2P v3.2 §0.3 requires the door **only at the phase boundaries**, and no boundary was crossed, so **the protocol was not violated**. The **README** requires gate execution at the end of **every Phase-1 work unit** and in the Immediate-task items 9–12 | P2P §0.3; README |

## 5. Negative controls

| Mechanism | Valid → | Violation → | Unverifiable → | Discriminates? |
|---|---|---|---|---|
| KOS-G-001 | PASS | FAIL on malformed (B10/B11) | ERROR on binary (B25) | ✅; ⚠️ **misses nested `.jsonl`** (G01) |
| KOS-G-002 | PASS | FAIL on duplicate object (B15) | **PASS** on missing objects (B06) | ⚠️ fail-open |
| KOS-G-003 | (was PASS until `41d2f7af6`) | FAIL on T-9999 (B16) | FAIL on missing registry (incidental) | ✅; ⛔ **false FAIL on correct canonical IDs** (G02, and HEAD itself); misses `.md` (B17) |
| KOS-G-010 | PASS | FAIL on a missing entry (B19) | **PASS 0/0** on missing or empty registry (B01, B07) | ⚠️ fail-open |
| KOS-G-022 | PASS | FAIL on an untraced seed (B22) | **PASS** on missing or empty seed (B04, B09); a malformed seed ID is silently excluded (B21) | ⚠️ fail-open |
| **admit.py --resolve** | ADMIT, exit 0 (F0026) | STOP, exit 3: `NOT_IN_MANIFEST` (F9999, bare path) · `SHA_MISMATCH` (tampered F0041) | STOP, exit 3: `FILE_ABSENT` (F0043) · `MANIFEST_ABSENT` | ✅ **discriminates and fails closed on every condition tested** |
| admit.py --audit | — | exit 1 on HEAD (9 divergent IDs, as documented) | — | ✅ (not a gate; not wired) |
| §9A sheets | — | — | — | ⛔ **not testable: nothing executes them** |

## 6. Fail-closed and instrument integrity (re-confirmed at HEAD)

| Case | At HEAD | |
|---|---|---|
| malformed `gates.yaml` / state (D01, D02b), unknown activated ID (D03) | `GOVERNANCE_INOPERATIVE` (runner exit 3) → door **exit 2 "BLOCK"** | ✅ fails closed; ⚠️ mislabelled |
| **all activated gates re-statused `proposed` (D09)** | ⛔ **CLEAR, door exit 0, with KOS-G-003 genuinely failing underneath** | **silent CLEAR** |
| activated gate re-tiered to B plus a violation (D10) | KOS-G-002 FAIL **non-blocking** (the suite blocks only because of G-003) | silent at gate level |
| research edit empties activation (D13) | `NO_ACTIVE_GOVERNANCE_CONTROLS`, exit 0 | proceeds; honestly labelled |
| self-test 30/31, fixtures deleted, schema corrupted, duplicate gate ID (D04/D06/D07) | not linked to evaluation (unchanged since audit-01) | **FAIL_OPEN** on instrument validity |
| runner missing (D18) | door exit 3 | ✅ |

**Exit-status handling:**
- The runner returns 1 on BLOCK and 3 on an unreadable rule book.
- The door reads `status=$?` after command substitution, correctly, but maps **every** non-zero code to "BLOCK" (exit 2), so INOPERATIVE is indistinguishable by exit code.
- No `|| true` in the door. The runner catches exceptions per check as `ERROR` (blocking when activated), and nothing converts them to PASS.

## 7. State binding

| Result store | Bound to | Classification |
|---|---|---|
| `PREFLIGHT-LOG.jsonl` | date and unit text only; **no commit, no input hash** | **STALE-PASS-DEMONSTRATED**: PF-0007 CLEAR against current BLOCK |
| §9A `gates.json`, conformance ledger | nothing machine-checkable | **STALE-PASS-POSSIBLE** (and METADATA_DERIVED) |
| runner `--json` | emits `integrity` digests of governance files, **not** of the evaluated inputs or commit | STALE-PASS-POSSIBLE if persisted |

## 8. False confidence (REPORTED_PASS presented where EXECUTED_PASS is implied)

| Statement | File | Evidence only establishes |
|---|---|---|
| *"F0001 SATISFIED 12/12 COMPLETE · F0010 SATISFIED 12/12 COMPLETE"*; "the first files to reach dossier_status COMPLETE" | `343f87468` message; `RESEARCH-STATE-SYNC-01.md`; `F0001/F0010.gates.json` | a **hand-evaluated** §9A sheet (REPORTED_PASS). The activated machine suite was **BLOCK** at that commit and was not run. `MACHINE_GATE_CHECK: NOT_AVAILABLE` (README-required when no script exists) is not reported |
| *"Ran prompts/readme.md 'Immediate task' — the 8-item state inspection"* | `343f87468` | the README's Immediate task has **12** items. **Items 9–12 (the gates) do not appear** in the state sync |
| PREFLIGHT-LOG "CLEAR" as a standing status | `PREFLIGHT-LOG.jsonl` | a past execution (STALE) |

## 9. Gate-integrity matrix

| Gate | Rule | Implementation | Invocation | Actually executed | Negative test | Fail-closed | State-bound | Result |
|---|---|---|---|---|---|---|---|---|
| KOS-G-001 | ✅ | ✅ | manual door; not run since PF-0007 | ✅ (by this audit) | ✅ / ⚠️ nested | ✅ binary · ⚠️ missing file not detected | ⛔ | **EXECUTED_BUT_INCOMPLETE** |
| KOS-G-002 | ✅ | ✅ | same | ✅ | ✅ | ⛔ missing → PASS | ⛔ | **FAIL_OPEN** |
| KOS-G-003 | ✅ | ✅ | same | ✅ → **FAIL at HEAD** | ✅ | ✅ (incidental) | ⛔ | **EXECUTED_BUT_INCOMPLETE**: false FAIL on canonical IDs; `.md` not scanned |
| KOS-G-010 | ✅ | ✅ | same | ✅ | ✅ | ⛔ 0/0 → PASS | ⛔ | **FAIL_OPEN** |
| KOS-G-022 | ✅ | ✅ | same | ✅ | ✅ | ⛔ missing seed → PASS | ⛔ | **FAIL_OPEN** |
| suite result (door) | ✅ | ✅ | **NOT_INVOKED** for 15 commits | ✅ (by this audit) | D09 **silent CLEAR** | ⚠️ mislabelled | ⛔ **STALE** | **FAIL_OPEN via redefinition** |
| KOS-G-070…073 | proposed | ⛔ | — | — | — | — | — | **NOT_IMPLEMENTED** |
| admit.py | research-produced | ✅ | manual per read | ✅ | ✅ | ✅ | receipts hash-bound ✅ | **EXECUTED_AND_VERIFIED** (unreviewed) |
| §9A per-file gates | ✅ (P1P) | ⛔ | hand | ⛔ | ⛔ | ⛔ | ⛔ | **NOT_IMPLEMENTED → METADATA_DERIVED** |

## 10. Silent PASS: can the system report PASS without executing the intended verification? **Yes.**

| Gate | Reported | How PASS is obtained | Required verification | Why bypassed | Evidence | Severity |
|---|---|---|---|---|---|---|
| **Suite (door)** | CLEAR | activated gates re-statused to `proposed` → evaluated as NOT_APPLICABLE but counted as applicable → CLEAR | run the 5 activated checks | the activation ID is not bound to a gate definition (audit-01 FP-5/IC-2/IC-3) | **D09 at HEAD: CLEAR over a real G-003 FAIL** | **CRITICAL** |
| **Suite (recorded)** | CLEAR (PF-0007) | a stale log row | execution at the current commit | no commit binding; not re-run | §4, §7 | HIGH |
| KOS-G-010 / 022 / 002 | PASS | missing or empty input read as ∅ | the check over present data | `_jsonl()` / seed loader fail-open (FP-1/FP-2) | B01, B04, B06, B07, B09 | HIGH |
| KOS-G-001 | PASS | nested `.jsonl` not scanned | every `.jsonl` | scope narrower than claim (FP-3) | G01 | MEDIUM |
| §9A per-file | SATISFIED / COMPLETE | hand-written JSON | the §9A conditions | no implementation | §8 | HIGH (it drives "Phase 1 COMPLETE") |

**Recommended correction** (not applied; existing items only): extended 1a **IC-1…IC-8** (GIA-2) · **GIA-5** (the KOS-G-003 known set, which removes the live false FAIL) · executing the README items 9–12 at every Phase-1 unit. No new rule is proposed.

## 11. Final verdict

| | |
|---|---|
| **G1 · implementation** | the 5 activated gates are **implemented** (partly fail-open). KOS-G-070…073 and all §9A gates are **NOT_IMPLEMENTED** as machine gates. admit.py is implemented |
| **G2 · invocation** | **NOT_INVOKED in practice**: the door is wired to no hook, and the last logged run is PF-0007. The research proceeded through **15 commits in a BLOCK state**. That does not breach P2P v3.2 (boundary-only), but it **contradicts the README's per-unit gate instruction**, which was not executed (items 9–12) |
| **G3 · execution behind PASS** | the recorded CLEAR is **STALE**. The §9A PASS/COMPLETE results are **METADATA_DERIVED**. No current EXECUTED_PASS exists: **execution at HEAD = BLOCK** |
| **G4 · negative controls** | each activated gate detects its obvious violation (✅). Near-misses are missed (G01, B17, B21). admit.py discriminates on every tested condition |
| **G5 · fail-closed** | the rule book and admit.py fail closed. **Gate inputs fail open** (G-002/010/022). **Instrument validity fails open** (self-test, schema, duplicates) |
| **G6 · stale results** | **STALE-PASS-DEMONSTRATED** (PF-0007 CLEAR vs HEAD BLOCK) |
| **G7 · silent PASS** | the door via **redefinition (D09, demonstrated over a real failure)** · KOS-G-002/010/022 via missing inputs · KOS-G-001 via nested files · §9A via hand-written sheets |
| **G8 · research-release impact** | ## **RESEARCH BLOCKED — GATE INTEGRITY FAILURE** |

*Why this and not "implementation missing":*
- The activated gates exist and execute.
- Their integrity fails: silent-CLEAR by redefinition, fail-open inputs, a stale recorded CLEAR, a live false FAIL on correct canonical IDs, and non-invocation for 15 commits.
- The current machine state is BLOCK.

**Unblocking is an L0 matter:** GIA-2 (extended 1a) and GIA-5. **The research must not treat the recorded CLEAR, or §9A COMPLETE, as a gate pass.**

---

*Traceability:*
- Snapshot `343f87468`.
- Battery results in the session scratchpad (`gia2/all.jsonl`, 55 runs; harness identical to audit-01 Appendix A).
- Per-commit activated-gate runs on 26 commits.
- admit.py mirror tests T1–T7.
- `git diff --stat 6cffbea5e HEAD` over the control plane (empty).
- Nothing modified outside this file.
