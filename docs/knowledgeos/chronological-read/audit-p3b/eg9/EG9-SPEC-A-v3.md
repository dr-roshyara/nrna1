# EG-9 SPEC-A v3: delta to v2 (design only; authority: generated)

| | |
|---|---|
| **Author** | Subagent A4. DESIGN ONLY. No repository file was changed |
| **Responds to** | `EG9-REVIEW-B-v2.md` (SPEC-NEEDS-REVISION, narrow): items (0) and (1) |
| **Unchanged from v2** | §2 categorical argument · §3 class-H sentence · §5 EG-10 runbook · the AR engineering (E9-01…E9-12) |

Sources added this round:
- K3 §12.3 (`prompts/20260925_1204_p3b-agent-contract-r2.md`:766-770);
- GL G-LOG-0024 (:502-514);
- GL G-LOG-0025 (:516-530);
- EP:16.

---

## 0. Replaces v2 §0: the correction record under R7 is an open HUMAN decision (H-EG9-6)

### Facts

- **[F] §21 item 2:** "`AUDIT-UPHELD` (→ correction record)" (P17 §21 item 2). Item 4 governs audit **reports** only. v2 wrongly equated the two.
- **[F] The correction mechanism is K3 §12.3** (K3:766-770): "No existing record is edited or deleted. A different interpretation produces a **new** record plus a `P3B-COR-####` entry: `{target_record, target_artifact, reason, evidence, new_record_ref, author_role, date}`. Supersession is represented by the new record's `supersedes` field and the COR entry, never by removal." The entries go to `P3B-CORRECTIONS.jsonl`.
- **[F] K3 leaves one point open.** K3 §16.5 (K3:1295-1303) says nothing about how a §12.3 `supersedes` record enters the S7 build of `32-RECONCILIATION-OBJECTS.jsonl`. v2's claim that §16.5 "already arranges" this is withdrawn.
- **[F] EP-01's estimand.** Each label has "exactly one final S5 object" (EP:16). E-1 is the discordance between that object and the blind re-analysis.

### The precedent, cited faithfully (S4 pilot)

- **G-LOG-0024 (GL:510)** records the §21 audit's recommendation: "PB05 ACCEPTABLE-WITH-NOTES **conditional** (step-verify-programme timeline and births quarantined; correction record for its MTIME-selected births, AUDIT-UPHELD)".
- **G-LOG-0025 (GL:516-530)** records the human decision: PB05 **ACCEPTED-CONDITIONAL**. The affected object's timeline and births are **quarantined**, "with the audit's correction recorded as **pending and not applied**". The rest of the batch is accepted with notes. "R2-001 and R2-002 records unchanged; the quarantine is a separate marker". The quarantine "lifts only after a separately authorized supplementary read and correction record".
- **G-LOG-0024 / G-LOG-0025 give no support for applying a correction at acceptance.**
  - The precedent is a **third form**: the affected fields are neither taken as verified nor replaced by the audit's correction. They are withheld until a separate human-authorized act.
  - It is the K3 §16.5 item 4 mechanism, "named exceptions … stay PROPOSED" (K3:1304).
  - In the precedent, the AUDIT-UPHELD finding *did* bear on acceptance, because it caused the exception. It did not become the accepted content.

### The two readings asked for, plus the precedent's form

| | **R-I "verified-bytes"** | **R-II "corrected-object"** |
|---|---|---|
| Definition | The estimation object for EP-01, θ_D, θ_A and RR-3 is always the verified assembly. §21 AUDIT-UPHELD correction records (§12.3: a new record plus P3B-COR-####, with `supersedes`) are stored and reported but do not change the estimation object | The S5 outcome is the record after the accepted §12.3 corrections are applied |
| EP-01 E-1, "the label's single final S5 object" (EP:16) | The verified record. The "final S5 object" is the S5 reading | The corrected record. E-1 then measures S5 **plus §21 correction** against the re-analysis |
| θ_D blindness | Preserved. No audit content enters the measured object | The θ_D auditor still never sees the object (EP:84). But a same-family auditor's content judgment **enters the measured object**, and only for the records that the §21 sample happened to reach (about 10% per batch, AS:9). That gives unequal review intensity across labels, the risk EP:134-139 names, and it biases θ_D toward agreement for corrected labels |
| Reproducibility / witness binding | Holds. The object is the witness-verified bytes (AD §5; RR-3) | Broken for corrected labels. The corrected record is not witness-verified bytes: it is written after the run, outside W1-W8. It needs its own verification path, which R7 does not define |
| RR-3 (EG5:274) | Holds literally: the first passing, accepted attempt's bytes | Needs amendment ("… as corrected by the accepted §12.3 corrections"). This is pre-registered policy text, so it is a v2.8 change before the canary |
| S7 rule, when a P3B-COR entry targets a record of an ACCEPTED R7 batch | **S7 carries the §12.3 new record into `32-RECONCILIATION-OBJECTS.jsonl` only with its own `acceptance_ref` from a separate human act, keeps `supersedes` and `correction_ref`, and records the verified record's assembly sha256 as `estimation_object_sha256`. EP-01 reads only the latter.** | **S7 writes the corrected record as the label's object, and the label is assessable under EP-01 only after that record has passed a defined verification of its own. Until then it is NOT-ASSESSABLE (EP:126).** |

**The precedent's own form (R-III, "exception"), for completeness.**
- Labels with a pending AUDIT-UPHELD correction are accepted *with a named exception*. The excepted content stays PROPOSED (K3:1304), as in G-LOG-0025.
- The label then has no accepted object for the excepted content. Under EP-01 it becomes NOT-ASSESSABLE and is worst-cased (EP:20, 126) until a separately authorized correction is accepted.
- This is compatible with R-I's measurement semantics and conservative in the bound. Its cost: each exception adds worst-case mass.

### Recommendation [D], a HUMAN decision (H-EG9-6)

**R-I**, combined with the precedent's rule that a correction is applied only by a separate human act.

1. It is the only reading that keeps E-1 a measurement of the S5 reading (B1/EP-01's estimand), keeps θ_D blind and uniform in review intensity, and keeps the estimation object witness-bound.
2. It uses §12.3 as written: a new record with supersession and no removal. §12.3 only states that a correction is a separate superseding record; it does not say that record redefines what an experiment measured.
3. It follows the precedent: in G-LOG-0025 the correction was "pending and not applied", and a separate act governed it.

- **Disclosure:** under R-I, `32-RECONCILIATION-OBJECTS.jsonl` can carry a corrected record while EP-01 reports on the verified record for the same label. Both shas are recorded.
- **Alternative:** if the human prefers the precedent's exact form, choose R-III, at the stated bound cost.
- **R-II is not recommended**, for the blindness, intensity and witness reasons above.

## 1. Tranche binding: additions to v2 §1

**Check (b′), heading uniqueness.**
- Before (b), count the lines of `P3B-GOVERNANCE-LOG.md` that match `^## <reference> —`. Refuse unless the count is **exactly 1**.
- Check (b) then parses that single section, up to the next `^## `.

**Check (d), attempt field: a forward dependency.**
- **[F]** `new_attempt` does not exist in `scripts/`, and `new_run` refuses every R7 batch (ST:209-210). So today the attempt is **always 1**, and check (d)'s attempt comparison is vacuous but correct.
- **[D]** When EG-6 part (d) lands, (d) must read the attempt from the attempt-aware history (`run_history(..., attempt=…)`, EG6:139). It must not stay a literal 1. The EG-6 (d) test list gains T-16.

**Residual, rewritten honestly.**
- `check_tranche` is a **point-in-time** consistency check. Its trust anchor, `P3B-GOVERNANCE-LOG.md`, has **no code-level append-only enforcement**.
- Compare the other anchors: `P3B-STATE.json` is hash-chained (ST:97-116); audit reports are write-once (AR:65-67). "Append-only" for the governance log is stated discipline only.
- Consequence: a later commit that rewrites both the tranche file and its governance section passes a re-run of `check_tranche`. The rewrite is undetectable by the tool, and visible only in git history.
- Authorship of the section also remains unproven (v2 residual, unchanged).

**Cheapest mitigation, in EG-9: capture at acceptance.**
- The acceptance record also stores three fields:
  - `glog_section_sha256`: the sha256 of the extracted section text;
  - `glog_commit`: `git rev-parse HEAD`, with the governance log required unmodified in the working tree: `git diff --quiet HEAD -- P3B-GOVERNANCE-LOG.md`;
  - `tranche_commit`: the commit that last touched the tranche file.
- A whole-file blob sha of the log would change on every later append, so the section sha is the comparable value.
- Re-verification extracts the section at `glog_commit` and at HEAD, and compares both with `glog_section_sha256`. Any later rewrite is detectable, because `S5-ACCEPTANCE.jsonl` holds the snapshot.
- Placement: capturing three fields in the record AC already writes is EG-9 scope (tests T-15, T-17). A re-verification *command* is optional.

**Separate item, not EG-9: governance-log tamper evidence.**
- Hash-chaining `P3B-GOVERNANCE-LOG.md` entries, or an entry-level write-once rule, would protect every governance act, not only H-06.
- It is a **governance-integrity** item for a backlog entry and a human decision. It is out of EG-9 scope.

**RED tests added:**

| # | Case | Expected |
|---|---|---|
| T-14 | the `## <reference> —` heading occurs 0 times, or 2 times (a duplicate later append) | refused; state and acceptance log byte-identical |
| T-15 | valid acceptance → the record carries `glog_section_sha256`, `glog_commit`, `tranche_commit`; recomputing the section at `glog_commit` gives the stored sha | as stated |
| T-16 (lands with EG-6 (d)) | attempt 2 of a batch: a tranche listing attempt 1 → refused; listing attempt 2 → accepted | as stated |
| T-17 | the governance log is modified-uncommitted at acceptance time | refused |

## 2. Human decisions: changes to v2 §6 (still separable part (g) of v2.8)

- **H-EG9-6 (replaced):** choose the estimation object when a §21 AUDIT-UPHELD correction exists:
  - **R-I** verified bytes, with corrections applied only by a separate act (recommended);
  - **R-II** corrected object;
  - **R-III** exception form (G-LOG-0025).
  If R-II is chosen, RR-3 text changes and must be in v2.8 before the canary.
- **H-EG9-3 (amended):** bound tranches now include heading uniqueness and the three capture fields at acceptance (T-14, T-15, T-17).
- **Backlog item (new, not EG-9):** tamper evidence for `P3B-GOVERNANCE-LOG.md`, a governance-integrity decision.

**Traceability:** K3:766-770, 1295-1304 · GL:502-530 (G-LOG-0024, G-LOG-0025) · EP:16, 20, 84, 126, 134-139 · EG5:274 · EG6:139 · ST:97-116, 209-210 · AR:65-67 · AS:9 · EG9-REVIEW-B-v2 §0-§1.
