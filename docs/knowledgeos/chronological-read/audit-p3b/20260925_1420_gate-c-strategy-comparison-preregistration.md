# Gate C — research-first strategy comparison: pre-registration (frozen before execution)

**Authority:** human instruction "S-SERIES — GATE C: RESEARCH-FIRST STRATEGY COMPARISON" (2026-09-25), recorded as G-LOG-0056. It is an experiment only, and non-production.

**Isolation:**
- Namespace: batch `PX0104`, runs `PX0104-S01` (R1 discovery), `PX0104-U01…` (R2/R3 verification) and `PX0104-A01` (audit).
- Reader logs go to `pilot-s5-decomp/PX0104-*/`; other artifacts to `pilot-s5-decomp/gatec/`.
- No production reader, manifest, contract, state or verifier change. OB0018 is not executed. H-19 is not accessed and S5c is not started. Protocol v1.7 and v3.5 are unchanged.

**This document is committed before any Gate-C agent runs; its criteria are not changed afterwards.**

## 1. Hypotheses

- **H0:** the research-first strategy misses a materially significant portion of the research findings that exhaustive processing identifies.
- **H1:** the research-first strategy recovers the materially significant findings through broad extraction plus registered, targeted deep verification, without exhaustive deep processing of every label.

## 2. Units

`knowledgeos-architecture-constitution-v01` and `step-verify-programme`. No other label, and no unrelated corpus material.

## 3. Arms

**Strategy A (reference, not re-run).** The audited decomposition-pilot outputs:
- **Primary reference set:** the research-register records of `pilot-s5-decomp/PX0004-S01/register.jsonl` (7) and `PX0004-S02/register.jsonl` (6), 13 in total.
- **Secondary reference set:** the P1-gap records (S01: 8; S02: 49), content that P1 extraction missed. By construction it lies **outside** R1 unless R2 reads the file. It is reported separately, as a measure of B's structural blind spot.
- **Limitations, stated now:**
  - one prior analytical run per label;
  - same model family as the discovery agents;
  - the reference is not ground truth;
  - the target reference was built through decomposition (4 known cross-unit losses);
  - small n.

**Strategy B (research-first, fresh).**
- **R1 discovery agent (`PX0104-S01`).** Its inputs are **only** `pilot-s5-decomp/gatec/R1-PACKAGE-<label>.json`, both built from pre-S5 layers:

  | Package | sha256 |
  |---|---|
  | constitution | `f745a471…` |
  | step-verify | `9287db5b…` |

  They contain P1 contribution rows, the P3a reconciliation object and pairs, the P2 family document, P2a groups and notations, stage-1 search records, 02-FILES source metadata, and tracks. The agent **reads no corpus file**. It must not open the pilot directories, registers, reports, governance log or this pre-registration's §5–§7.
- **R2/R3 verification agent(s) (`PX0104-U01`…).** Its inputs are the frozen trigger registry and the paged reader for triggered files only. It does not see the Strategy-A outputs.

## 4. Sequence and trigger freeze (anti-hindsight)

**R1 → trigger registry → freeze → R2 → R3 → audit.**

1. **R1** writes `pilot-s5-decomp/gatec/R1-CANDIDATES.jsonl` and `R1-TRIGGERS.jsonl`. Each trigger has: `trigger_id, candidate_id, label, research_question, escalation_reason, source_id, file identity (sha256 from 02-FILES if present, else S-id), evidence_type_required (WHOLE-FILE), expected_claim, verification_criterion, disconfirmation_criterion`.
2. **Freeze:** the orchestrator records the sha256 of both files in a freeze note and commits it **before** any R2 read. R2 may read **only** S-ids named in frozen triggers.
3. **R2** reads each triggered file whole with the paged reader (`--run PX0104-U0n --batch PX0104 --label <label> --step 10 --page k S####`) and records reading state (page coverage is verified by hash, as in the pilot).
   - **Budget:** at most 800,000 bytes per verification agent. If the distinct triggered files exceed 800 KB, the triggers are split deterministically by label into U01 and U02.
   - A file that looks interesting but was not triggered is recorded as `UNTRIGGERED-OBSERVATION` and **not read**.
4. **R3** classifies every candidate as VERIFIED / PARTIALLY-VERIFIED / NOT-VERIFIED / CONTRADICTED / UNRESOLVED, with confirming and disconfirming evidence (S-id, anchor, verbatim quote). An absence claim without census evidence is UNRESOLVED ("not found" is never "does not exist").
5. **The timeline is recorded:** registration of the research question (this document), R1 end, trigger freeze commit, first R2 read (read log utc), and the moment Strategy-A results become visible to the auditor.

## 5. Materiality rubric (applied by the auditor to every reference and candidate finding before matching)

- **MATERIAL** if the finding (i) asserts a contradiction or tension, a methodological deficiency, a definition/type/absence gap, a structure or relation between concepts or labels, or a verdict–evidence conflict; or (ii) would change a status, a birth, a dependency edge or a downstream research decision.
- **MINOR** otherwise: a restatement, a provenance or bookkeeping note, or a single-quote observation with no relation or consequence.

## 6. Matching rule (auditor)

Every finding in either arm is classified as FOUND-BOTH / A-ONLY / B-ONLY / B-FALSE (unsupported by its own evidence) / PARTIAL-MATCH / UNRESOLVED-MATCH.
- The decision rests on **substantive meaning, evidence, provenance and research relevance**, not wording.
- One A finding can match at most one B finding and vice versa. A many-to-one case is PARTIAL-MATCH.

## 7. Metrics and thresholds (computed before interpretation)

- **M1 research recall** = (FOUND-BOTH + 0.5·PARTIAL-MATCH) / |MATERIAL primary reference findings|. It is also reported over all primary findings, and separately over the P1-gap secondary set.
- **M2** = the count of B-ONLY findings that R3 VERIFIED, broken down by the auditor's §16 classes.
- **M3 false/unsupported rate** = (NOT-VERIFIED + CONTRADICTED + B-FALSE) / |B candidates|.
- **M4 evidence efficiency** = bytes deep-read per VERIFIED finding.
  - A: 2,065,395 unit bytes + 37,623 synthesis re-read bytes = 2,103,018 bytes, over its register findings (13). Object records are also reported as a by-product.
  - B: R2 bytes over VERIFIED candidates.
- **M5 trigger efficiency** = triggers / candidates, plus the distinct files triggered.
- **M6 hindsight/selection failures** = the count of: R2 reads of untriggered S-ids (from the read log); triggers created or changed after the freeze; B access to A outputs (from agent self-report and the orchestrator's file-access instructions); selective evidence flagged by the auditor.
- **M7 evidence strength** of each matched pair: A-stronger / equal / B-stronger (auditor).

**Pre-registered interpretation, on the MATERIAL primary set:**
- **Supports H1 on these labels** iff M1 ≥ 0.80 **and** M3 ≤ 0.25 **and** M6 = 0.
- **Supports H0** iff M1 < 0.50.
- **Otherwise mixed:** the report states which claim types were missed (Outcome 3 or 4).

## 8. Auditor

- **Independence:** one independent agent (`PX0104-A01`), **run on a different model than the discovery agents** (the discovery agents use claude-opus-5-5; the auditor uses a different available model). That is the same vendor family, and the limitation is recorded.
- **Order of work:** it first applies §5 materiality to all findings of both arms, **blind to arm labels**. Findings are presented as two unlabelled lists; the mapping is revealed only for metric computation. Then it matches (§6) and rates M7.
- **Evidence:** it may read files named in either arm's evidence through the paged reader under `PX0104-A01`, and it records its reads.

## 9. Not claimable from this experiment

Corpus-wide recall; general superiority; universal equivalence; production scalability; any statistical generalization. n = 2 labels.
