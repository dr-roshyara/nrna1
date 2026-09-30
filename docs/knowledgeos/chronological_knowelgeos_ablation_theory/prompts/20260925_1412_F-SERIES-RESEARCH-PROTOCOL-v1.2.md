# F-SERIES RESEARCH PROTOCOL — v1.2

**Status: FROZEN FOR INDEPENDENT RE-AUDIT (F-LOG-0008). NOT APPROVED FOR EXECUTION.**
Execution is mechanically impossible until a human records `APPROVED-FOR-EXECUTION: <path> <sha256>` lines in
`F-GOVERNANCE-LOG.md` for this protocol, the runbook and the contracts index (§F-12, F-20).
**Supersedes:** `20260925_1333_F-SERIES-RESEARCH-PROTOCOL-v1.1.md` (kept unchanged). v1.1 sections not restated here
stay in force as written: §F-1 purpose, §F-1A three levels, §F-2 population and order, §F-3.1 matrix. v1.0 §F-4 and
§F-5, and rulings RL-01…RL-10, also stay in force.
**Input:** the independent audit `audits/20260925_1500_F-SERIES-v1.1-INDEPENDENT-AUDIT.md` (F-LOG-0007), review notes
RN-01…RN-10, the human commission "F-SERIES — v1.2 REMEDIATION AND RE-AUDIT BEFORE F3082" (2026-09-25).
**Decisions per finding:** `prompts/20260925_1412_F-SERIES-v1.2-REMEDIATION-MATRIX.md` (every finding ACCEPT /
MODIFY / REJECT / DEFER, with control, contract, code, test and residual risk).
**Companions:** runbook `prompts/20260925_1412_F-SERIES-AGENT-CONTRACT-v1.2.md` · contracts
`prompts/contracts-v1.2/00-CONTRACTS-IN-FORCE.md` (C01–C15).

> **First make L1 evidence trustworthy and immutable. Then permit L2 analysis. Then permit L3 research.**
> **A transition is an evidence-producing event; an event is a link in a hash chain; a stage's artifacts are frozen by
> its event.**
> **Coverage is not semantic completeness.**

---

## §F-3.3 v1.2 additions to the rule matrix (F-SPECIFIC unless stated)

| # | Rule | Source | Treatment |
|---|---|---|---|
| 38 | hash-chained state ledger; pre-v1.2 prefix sealed by `F-STATE-ANCHOR.json` (4,556 lines) | F-07 | F-SPECIFIC |
| 39 | each stage event freezes `{artifact: sha256}`; later gates and the audit re-verify every freeze in force | F-01 | F-SPECIFIC |
| 40 | legality is checked first; the run id must be the previous event's; re-entry keeps the run and re-freezes | F-17 | F-SPECIFIC |
| 41 | AUDITED/AUDIT-FAILED run the audit themselves; AUDIT-FAILED is re-entered, never audited | F-07, F-17 | F-SPECIFIC |
| 42 | execution approval lines per governing document hash | F-20 | F-SPECIFIC |
| 43 | exceptions after READ-COMPLETE and READ-PARTIAL/READ-FAILED need a human reference naming the F-ID | F-08 | F-SPECIFIC |
| 44 | the reader enforces processing-order eligibility | F-13 | F-SPECIFIC |
| 45 | semantic floor of the inventory: per-unit quoting, ≥ 20-char/whole-unit quotes, contiguity, ≤ 6 consecutive units, content ≠ quote; category-bound coverage for definition cues, math-bearing units and code; release records for detector false positives | F-03, F-04, F-05 | F-SPECIFIC |
| 46 | independent L1 audit at CONTENT-EXTRACTED (first text F-ID, every fifth): the auditor's own read, inventory and attestation; the comparison computed by script; human acceptance | F-06 | ADAPTED from v3.5 A8 |
| 47 | isolation contract C15 + extractor/auditor attestations | F-02 | F-SPECIFIC |
| 48 | Level-2 reasoning, prescription guard, interpretation frame, DDD `SOURCE-DESCRIBED`, correctness `analytical_status` + `basis` | F-09, RN-03…RN-07 | F-SPECIFIC |
| 49 | Level-3 binding (refs), outcome-key ban, per-file THEORY-CANDIDATE/CORPUS refused, `disconfirmation_plan` planned only, pre-registration with hold-out basis, confirmatory checkpoint, family, multiplicity rule | F-10, F-11, F-15 | ADAPTED from P3B §13.10, §13.10a |
| 50 | hypothesis registration and checkpoint tests blocked while HDR-2/HDR-3 are undecided (`F-DECISIONS.json`) | F-12, F-15 | F-SPECIFIC (HUMAN DECISION REQUIRED) |
| 51 | checkpoints: READING refused when due; OPEN snapshots populations (F-ID, unique-content, F-specific, CONTENT-IDENTICAL-TO-S, near-duplicate signal, historical-time evidence) and hypotheses; MONITORING-ONLY until HDR-2/3 | F-16, RN-01, RN-02, RN-08, RN-09 | F-SPECIFIC |
| 52 | identifier-shaped S-lane references refused in every analyst-authored field; CONTENT-IDENTICAL-TO-S propagated into cross-file candidates | F-14 | F-SPECIFIC |
| 53 | one lens vocabulary; hashed candidate ids; CATEGORY-CHECK and research-time/contract-hash validation | F-19 | F-SPECIFIC |

No inherited S rule is changed. The inherited XC keeps `USED-UNSTATED` assumptions and free-text fields (F-21); the
identifier guard now also covers them.

---

## §F-6 State machine (v1.2)

The v1.1 main line is unchanged. **What changes is the meaning of each transition:**

| Aspect | v1.2 rule | Code |
|---|---|---|
| History | every new event carries `prev_hash` + `hash`; `verify_chain()` checks the anchor, links, per-F `seq`, `from` = previous state, legality; a broken chain refuses every transition and `f_status` | `f_integrity.verify_chain`, `f_common.record_event` |
| Evidence | each stage event (READ-COMPLETE, CONTENT-EXTRACTED, RECONSTRUCTED, ANALYZED, RESEARCHED) records `frozen = {file: sha256}`; a transition into stage X re-verifies every freeze of the stages before X; the audit re-verifies all | `f_integrity.artifact_hashes/verify_frozen` |
| Runs | a stage transition must use the run of the previous event; READING starts a run | `f_transition` |
| Re-extraction | CONTENT-EXTRACTED → CONTENT-EXTRACTED only with `--human-ref` (after an independent-audit DISCREPANCY) | `f_transition` |
| Audit | AUDITED / AUDIT-FAILED invoke `f_audit.run_audit()`; AUDIT-FAILED → re-entry at READING, CONTENT-EXTRACTED, RECONSTRUCTED, ANALYZED or RESEARCHED; **AUDIT-FAILED → AUDITED does not exist** | `f_common.ALLOWED_FROM`, `f_audit` |
| Exceptions | PLACEHOLDER, CONTENT-EXTRACTION-UNRESOLVED, RECONSTRUCTION-UNRESOLVED, ANALYSIS-UNRESOLVED, RESEARCH-UNRESOLVED, READ-PARTIAL, READ-FAILED need `--human-ref F-LOG-####` whose section names the F-ID; the audit re-checks it | `f_integrity.human_ref_ok` |
| Approval | every transition beyond F-P0, every page read, every independent-audit write requires the approval lines | `f_integrity.require_approval` |
| Checkpoints | READING is refused while a checkpoint is due and not opened | `f_checkpoint.due_unopened` |

---

## §F-8 Per-file lifecycle (v1.2)

```
C  READ                  reader (approval + look-ahead enforced) → page digests → READ-COMPLETE (freezes PAGE-DIGESTS)
D1 CONTENT EXTRACTION    isolated extractor (C15) → UNITS, INVENTORY, DISPOSITIONS, CATEGORY-CHECK, ISOLATION-ATTESTATION
                         → CONTENT-EXTRACTED (freezes all five)                                      ── STOP (run rule) ──
   INDEPENDENT L1 AUDIT  when due: fresh auditor (C15), own read + inventory + attestation → f_compare_inventory
                         → INDEPENDENT-AUDIT-L1 (CONFIRMED + human acceptance, or DISCREPANCY → re-extraction)
D2 RECONSTRUCTION        XC contributions carrying every item → RECONSTRUCTED (freezes L1 records + the L1 audit)
E1 ANALYSIS (L2)         lens checklist, cross-file dispositions → ANALYZED (freezes)
E2 RESEARCH (L3)         per-file suggestions; hypotheses only after HDR-2/HDR-3 → RESEARCHED (freezes research.jsonl)
G  AUDIT                 AUDITED (runs the audit) — irreversible
```

**Run rule R-COMMIT:** after every gate that succeeds on a real F-file, the lane is committed and the commit named in
the session log. Git is the second, independent record of the chain.

**Semantic-completeness controls (F-03).** Mechanical coverage is a floor. The things that bite are:
- per-unit quoting;
- category-bound coverage of definition cues, math-bearing units and code;
- release records, each reviewed by the audit;
- anti-catch-all bounds;
- the independent L1 audit's own inventory and the computed comparison;
- human acceptance of L1 for every audited file.

None of this proves semantic completeness. It makes obvious empty compliance fail, and semantic loss detectable.

---

## §F-11 Isolation (v1.2) — what can and cannot be guaranteed (C15)

| Guarantee | Mechanism | Strength |
|---|---|---|
| F-Series writes only inside the lane | `f_common.guard` on every write | mechanical |
| No F-file is read out of processing order through the reader | reader eligibility check | mechanical (reader only) |
| No reading or transition under an unapproved protocol | approval lines | mechanical |
| S-lane identifiers never appear in analyst-authored fields | `S_LANE` guard in every stage check | mechanical, identifier-shaped only |
| The extractor/auditor declared what it received and read, within the allow-list | attestation files + allow-list check | **self-attested** — records, cannot prove |
| The extractor did not see `.claude/CONTEXT.md`, `.claude/MEMORY.md`, S ledgers, KnowledgeOS conclusions, other F-files, the quarantine, other agents' scratch | C15 prompt override + deny-list + attestation + independent audit comparison | **run rule + detection; not preventable in this harness** |
| No model prior about KnowledgeOS or Hegel influences extraction | — | **not guaranteed**; mitigated by verbatim quoting and the audit |

**HDR-1 (HUMAN DECISION REQUIRED):** accept the residual isolation risk in the last three rows before any F-file D1.

---

## §F-12 Execution gate (v1.2)

1. v1.2 is frozen for re-audit. No APPROVED-FOR-EXECUTION line exists, so every gate and the reader refuse.
2. A new independent auditor audits v1.2, read-only, in its own scratch location, and issues a fresh verdict.
3. The human decides HDR-1 and approves execution by writing the approval lines. The HDR-4 approval is recorded in
   F-GOVERNANCE-LOG.
4. Only then F3082 D1, in a fresh isolated extractor. It STOPS at CONTENT-EXTRACTED; the independent L1 audit and the
   human acceptance follow. L2 begins only after the evidence layer is accepted.
5. HDR-2 and HDR-3 do not block D1. They block hypothesis registration and checkpoint tests.

---

## §F-14 Human decisions required (v1.2)

| Id | Decision | Proposal (for the human; not adopted) |
|---|---|---|
| **HDR-1** | accept the isolation residual (§F-11) | accept, with C15 run rules, attestations and the independent L1 audit as detectors |
| **HDR-2** | temporal semantics (F-12): what "no look-ahead", "out-of-sample" and "prospective" mean when processing order ≠ historical order | name two different hold-outs, never the bare term. **Processing-order hold-out:** files AUDITED after `registered_after_f`; it guards against analyst hindsight and says nothing about historical time. **Historical-time hold-out:** files whose best historical date is later than the registering file's; it needs date evidence of known reliability (list mtime, file mtime and explicit dates are recorded per file in each checkpoint's `historical-time-evidence`). A pre-registration declares `holdout_basis`; a claim states which hold-out it rests on. `F-DECISIONS.json.temporal_semantics.allowed_bases` records the decision |
| **HDR-3** | multiplicity and repeated looks (F-15, RN-10) | one confirmatory look per hypothesis (`confirmatory_checkpoint`); every other checkpoint is monitoring only, never "confirmation"; within a pre-declared `family`: SINGLE-PRIMARY (one primary test) by default, HOLM when a family has several confirmatory comparisons; BH-FDR only for exploratory screening, labelled EXPLORATORY and never evidence. Recorded in `F-DECISIONS.json.multiplicity_rules.allowed` |
| **HDR-4** | approve v1.2 for execution after the re-audit | write the three APPROVED-FOR-EXECUTION lines (hashes in F-LOG-0008) |
| **HDR-5** | GO for F3082 D1 | after HDR-1 and HDR-4 |
| carried | FD-11 checkpoint interval | N = 50 as the initial cadence for CP-01, reviewed empirically at CP-01 (RN-01) |

---

## §F-15 Residual risks (recorded, not solved)

- **Semantic completeness:** coverage and category-binding are floors. A thin but valid item passes the gates, and
  only the independent audit and human acceptance can catch it.
- **Detector limits:** the math and definition detectors are lexical, and they have false negatives. Release records
  handle false positives and are audited.
- **Isolation:** §F-11 rows 5–7 (HDR-1).
- **Auditor independence:** the auditor is the same model family. Its independence is procedural (fresh context,
  separate scratch, no access to the extractor's L1 by rule), not statistical.
- **Ledger rewriting:** a consistent rewrite of both artifacts and the ledger is detectable only through git history
  (run rule R-COMMIT).
- **Checkpoint tests:** C11 test execution is specified but not implemented. It is blocked by HDR-2 and HDR-3.
- **Prescription guard:** it is lexical. A paraphrased prescription passes it, and only the audit and human review
  can catch it.
