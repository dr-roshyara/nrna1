# S-Series Gate C — Research-First Strategy Comparison Report

> **n = 2 labels.** This experiment cannot establish corpus-wide recall, general superiority, universal equivalence, production scalability or any statistical generalization. It shows feasibility and failure modes of research-first discovery on two previously characterized labels only.

**Status:** non-production experiment. Production is unchanged; OB0018 was not executed; H-19 SEALED; S5c PROHIBITED; protocol v1.7 and v3.5 unchanged.

**Governance:** G-LOG-0056 (pre-registration, commit `a281083c7`), G-LOG-0057 (this report).

**Tags:** every conclusion is marked OBSERVED / INFERRED / HYPOTHESIS / UNRESOLVED.

## 1. Experimental question

Can a research-first strategy discover materially important findings without first performing the exhaustive S5 roll-up on every label?

## 2. Pre-registered hypotheses

`audit-p3b/20260925_1420_gate-c-strategy-comparison-preregistration.md`, committed before any agent ran:
- **H0:** research-first misses a materially significant portion of what exhaustive processing finds.
- **H1:** research-first recovers the materially significant findings.

**Thresholds (on MATERIAL primary reference findings):**
- H1 iff M1 ≥ 0.80, M3 ≤ 0.25 and M6 = 0;
- H0 iff M1 < 0.50;
- mixed otherwise.

## 3. Design

- **Units:** `knowledgeos-architecture-constitution-v01` and `step-verify-programme`.
- **Strategy A (reference, not re-run):** the audited pilot registers `PX0004-S01` and `PX0004-S02`.
  - 13 primary findings;
  - the 57 P1-gap records as a secondary set.
- **Strategy B:** R1 discovery from blind pre-S5 packages → a frozen trigger registry → R2 paged whole-file reading of triggered files only → R3 verification → a 3-stage independent audit.

## 4. Information available to each arm

| Agent | Saw | Did not see |
|---|---|---|
| R1 `PX0104-S01` (opus-5-5) | the two R1 packages only: P1 rows, P3a object and pairs, P2 family document, groups and notations, stage-1 search, 02-FILES metadata | any corpus file, the A registers, pilot outputs, audit files, the pre-registration's §3–§7 |
| R2/R3 `PX0104-U01`/`-U02` (opus-5-5) | the frozen candidates and triggers of their label; the triggered files, via the paged reader | the A registers, pilot outputs |
| Auditor `PX0104-A01` (**claude-fable-5-1**, a different model; same vendor family) | stage 1: label + statement of all 64 findings, merged and shuffled; stage 2: two unlabelled lists with evidence; stage 3 (after unsealing): arm key, B inputs, P1-gap set | the arm identity before stages 1–2 were committed |

## 5. Anti-hindsight controls (OBSERVED)

| Event | Time / commit |
|---|---|
| Research question and criteria registered | commit `a281083c7` |
| R1 end; trigger registry frozen | `R1-FREEZE.json` 12:20:53Z, commit `8f6b8b461` |
| First R2 read | U01 12:21:40Z; U02 12:22:00Z (after the freeze) |
| Blind audit stages 1–2 committed | `810137439`; the arm key was revealed only afterwards |

- **Mechanical checks:** 0 untriggered reads; 0 reads before the freeze; 16/16 triggered files read whole; 0 hash failures.
- **Blinding limitation:** in stage 2 the arm could be inferred from field structure, because only B items carry verification verdicts. Stage 1 (materiality) was fully blind.

## 6. Strategy-A reference limitations (OBSERVED)

- one analytical run per label;
- the same model family as B's agents;
- the target register was built through decomposition, with its 4 known cross-unit losses;
- the reference is not ground truth;
- small n.

**Consistent with these limitations (INFERRED):** the auditor classed 29 of B's findings as **Strategy-A omissions**, i.e. content in files A had read but did not register (§14).

## 7. Strategy-B discovery results (OBSERVED)

**Candidates:** 51 in total, 27 constitution and 24 step-verify:
- 28 CANDIDATE, 20 OBSERVED-ONLY, 3 NOT-ESTABLISHED;
- kinds led by METHOD-ISSUE (11), CONTRADICTION (9), RELATIONSHIP and PATTERN.

**Auditor materiality:** 44 of 51 B findings are MATERIAL; 13 of 13 A findings are MATERIAL.

## 8. R2 trigger registry (OBSERVED)

- 28 triggers covering 16 distinct files, 830,188 bytes (constitution: 10 files, 488,029; step-verify: 6 files, 342,159).
- 3 candidates had no triggerable file.

## 9. R2/R3 verification results (OBSERVED)

**R3 verdicts over 51 candidates:**

| Verdict | Count |
|---|---|
| VERIFIED | 17 |
| PARTIALLY-VERIFIED | 10 |
| OBSERVED-ONLY-CONFIRMED-IN-SUBSTRATE | 19 |
| UNRESOLVED | 5 |
| NOT-VERIFIED / CONTRADICTED | 0 |

**Two auditor caveats:**
1. OBSERVED-ONLY-CONFIRMED-IN-SUBSTRATE lies outside the pre-registered scale. It is treated as neither verified nor not verified.
2. **Self-grading was lenient.** Several candidates carry disconfirming evidence against a clause (B-KAC-13, B-SVP-01, B-SVP-17, B-KAC-16) but none was rated NOT-VERIFIED.

## 10. Independent audit (OBSERVED)

**Stage 1:** 57 MATERIAL, 7 MINOR.

**Stage 2 matching (blind):**

| Class | Count |
|---|---|
| FOUND-BOTH | 2 |
| PARTIAL-MATCH | 9 pairs |
| A-only | 3 |
| B-only | 41 |
| Unsupported by own evidence | 0 |
| Unresolved | 0 |

**Stage 3 corrections, made after unsealing:**
- add L1-02↔L2-37 PARTIAL;
- add L1-13↔L2-07 PARTIAL;
- upgrade L1-05↔L2-39 to FOUND-BOTH.

## 11. A/B correspondence

- **A-only findings:** a census false negative (the stage-1 template terms); the programme's type-signature gap; births and origin earlier than S1513.
- **Partial matches:** mostly the same contradiction or gap seen from a different angle, with a residual part missing. §13 diagnoses each.

## 12. Metrics M1–M7 (computed before interpretation)

| Metric | Value | Tag |
|---|---|---|
| **M1 research recall** (blind stage 2, primary) | **0.462** = (2 + 0.5·8) / 13 | OBSERVED |
| M1 with post-unblinding corrections (sensitivity only) | 0.500 = (3 + 0.5·7) / 13 | OBSERVED |
| M1, secondary P1-gap set | 19 / 57 = 0.333 content-level; 7 / 57 = 0.123 strict. 13 of the 19 are variants of two programme rules | OBSERVED |
| **M2 B-only findings** | 41. R3: 12 VERIFIED, 8 PARTIAL, 16 OBSERVED-ONLY, 5 UNRESOLVED. Auditor: **29 Strategy-A omissions**, 1 potentially new (I1–I7 via S1030, a file A did not read), 7 re-expressions, 4 unresolved, 0 unsupported | OBSERVED |
| **M3 false/unsupported rate** | 0 / 51 nominal; **not reliable** because of the lenient self-grading and the off-scale verdict | OBSERVED + caveat |
| **M4 evidence efficiency** | A: 2,103,018 bytes / 13 register findings = 161,771 bytes per finding (plus the object records, a reconstruction by-product). B: 830,188 bytes / 17 VERIFIED = 48,835 bytes per VERIFIED finding (30,748 per VERIFIED + PARTIAL) | OBSERVED; see §15 |
| **M5 trigger efficiency** | 28 triggers / 51 candidates; 16 files | OBSERVED |
| **M6 hindsight/selection failures** | **0**, by mechanical check and by the auditor | OBSERVED |
| **M7 evidence strength** (11 pairs) | B stronger 6; A stronger 4; equal 1 | OBSERVED |

**Pre-registered classification:**
- **primary M1 = 0.462 < 0.50, which is the H0 rule;**
- sensitivity M1 = 0.500, exactly at the boundary (mixed);
- **H1 is not met** in either reading.

## 13. A-only diagnosis (why B missed it; auditor, stage 3)

12 entries: 3 A-only findings and 9 partial residuals.

| Cause | Count | What |
|---|---|---|
| **candidate-generation-failed** | 7 | The information **was in R1**, but R1 formed no candidate: the census template terms, the type-signature gaps (both labels), the births before S1513 (conceptual birth null plus earlier-dated files visible in metadata), CORRECTION typing, the 156A revival, and the redundancy inference |
| not-represented-in-R1 | 1 | S1444's objective change, S1551's reflexive rule, S1413 and S1566 |
| matching-failed | 2 | corrected in stage 3 |
| A-finding-questionable | 1 | A declined an edge that B's reading of S1018 supports |

**INFERRED:** B's misses are mainly a **candidate-generation weakness**, not a blind spot of the substrate. The exception is **reconstruction content in untriggered early files**: births and the programme's self-description.

## 14. B-only diagnosis (is it value or difference?)

- **OBSERVED (auditor):** 29 of the 41 B-only findings are **Strategy-A omissions**: A had read the files, but its registers lack the finding. Of these, R3 rated 11 VERIFIED and 7 PARTIAL.
- 1 finding is potentially new (from S1030, not in A's reading set). 7 are re-expressions; 4 unresolved; 0 unsupported.
- **Caveats:**
  - A's **object records** (which the auditor did not see) may hold some of these;
  - several rest on S1520–S1522 content, which A's target unit read but which is absent from A's **constitution** register. That is consistent with the decomposition losses found in the pilot.

## 15. Evidence-efficiency comparison

- **OBSERVED:** B read 830 KB, about 40% of A's 2.1 MB, and produced more verified findings.
- **INFERRED, with a qualification.** A's reading also produced reconstruction records for both labels: births, timeline, absences, statuses. B produced none, and none was asked of it. The two are therefore **not like-for-like**:
  - per **research finding**, B was more efficient on these labels;
  - per **reconstruction obligation**, B produced nothing.

## 16. Hindsight and selection analysis

- **OBSERVED:** M6 = 0 on every count: no untriggered reads, no reads before the freeze, no reference to A outputs in B's material, and disconfirming evidence recorded.
- **UNRESOLVED:** whether the R1 agent's choice of what to trigger embeds its own selection bias. For example, it triggered files about the constitution–programme relation and not the early programme files that bear on births. Pre-registered triggers make this **visible** but do not remove it.

## 17. Failure modes

1. **Candidate generation.** R1 exposed pipeline defects and gaps (template-term false negatives, null type signatures, null birth candidates) but did not turn them into candidates.
2. **Untriggered files.** Reconstruction-type findings live in files no trigger selected (34 of the 38 unrecovered P1-gap records are in untriggered files).
3. **Lenient R3 self-grading**, and an off-scale verdict.
4. **Blinding is imperfect** in stage 2.
5. **The reference is incomplete** (29 A omissions), which depresses M1 as a recall estimate in both directions.

## 18. What the experiment establishes (on these two labels)

- **OBSERVED:** research-first discovery with registered triggers ran with **0 hindsight/selection failures** and read about 40% of the bytes.
- **OBSERVED:** it produced **many material findings that the exhaustive arm did not register**: 29 A omissions, 11 of them R3-VERIFIED, with stronger evidence in 6 of 11 matched pairs.
- **OBSERVED:** it recovered the exhaustive arm's material findings **only partly** (M1 = 0.462 blind), so the **pre-registered H0 rule applies**.
- **INFERRED:** the misses cluster in (a) findings that R1 exposed but never made into candidates, and (b) reconstruction content in untriggered files.

## 19. What it does NOT establish

- corpus-wide recall, superiority or equivalence, and scalability;
- whether improved candidate generation would raise recall (HYPOTHESIS);
- whether the A omissions reflect exhaustive processing's register practice generally or this reference specifically (UNRESOLVED);
- anything about absence and negative-evidence claims beyond these cases;
- model independence (same vendor family).

## 20. Implications for S-Series architecture

**Evidence bearing on each outcome, not ranked:**

| Outcome | Evidence for | Evidence against |
|---|---|---|
| **1. Research-first appears viable on the tested labels** | 0 hindsight failures; 29 A omissions surfaced; stronger evidence in 6/11 pairs; about 40% of the bytes | M1 = 0.462 < 0.80, and the H0 rule fires; births and early-file content missed |
| **2. Research-first loses important findings and needs stronger discovery/extraction** | 7 of 12 misses are candidate-generation failures on information already in R1; the secondary recall is 0.333 | the misses are fixable in principle (HYPOTHESIS), not a substrate limit |
| **3. Viable for some claim types, not others** | strong on analytical/structural critique of the files it reads (contradictions, method issues); weak on reconstruction claims (births, the programme's self-description in untriggered early files) and on pipeline-gap findings | only 2 labels |
| **4. Inconclusive; a larger controlled experiment is required** | n = 2; the reference is incomplete; lenient R3; imperfect stage-2 blinding; M1 at or near the H0 boundary (0.462 / 0.500) | the direction of the failure modes is consistent across the diagnoses |
| **5. Another methodological issue was discovered** | **the exhaustive reference's register omitted 29 findings from files it had read**. Whole-file reading did not guarantee registration, which bears on the value of Strategy A's research output as such. Also, the decomposition loss is visible (S1520–S1522 content missing from the constitution register) | the A object records were not examined |

**PROPOSED CHANGE — HUMAN DECISION REQUIRED (if the human pursues research-first):**
- mandatory **gap and absence candidate generation** from R1's structured nulls and template hits;
- **mandatory triggers for the earliest-dated files and for birth-candidate files**, to cover reconstruction content;
- **R3 grading restricted to the pre-registered scale**, with a rule that any disconfirming clause yields PARTIAL or NOT-VERIFIED.

## 21. Does OB0018 remain necessary?

- **INFERRED:** unchanged from the gate. OB0018 informs the **engineering of exhaustive processing** (Strategy A on the 82 heavy labels).
- This experiment shows exhaustive processing **still recovers reconstruction content that research-first missed**: births, early files.
- It also shows that exhaustive processing's **register omitted many findings**. OB0018 would not address that.

## 22. Human decision required

1. **Objective:** keep the dual objective with the exhaustive floor; move toward research-first; or keep both tracks separately. The evidence suggests the two arms are complementary: B stronger on analytical findings, A needed for reconstruction content.
2. Whether to run a **larger controlled comparison** with the proposed changes (improved candidate generation, reconstruction triggers, strict R3 scale, a different-vendor auditor if available).
3. Whether to proceed with **OB0018** for the engineering problem.
4. **Independent of the above:** the binary policy, byte-bounded paging and NOT-CONSUMED-ESCALATED (post-pilot brief).

**Artifacts:**
- `pilot-s5-decomp/gatec/`: packages, candidates, triggers, freeze, R3 files, blind lists, arm key, materiality map;
- `pilot-s5-decomp/PX0104-U01/`, `-U02/`: read logs;
- `pilot-s5-decomp/PX0104-A01/`: stage 1–3 audit files.

**State:** experiment complete and stopped; no production change; no batch authorized.
