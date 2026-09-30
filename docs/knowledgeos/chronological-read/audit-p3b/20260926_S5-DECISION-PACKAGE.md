# S5 Decision Package — ANALYSIS ONLY (prepares the human decisions required before S5 authorization)

**Authority:** G-LOG-0081. **Kind:** decision preparation; **no option is ranked or recommended** (human instruction).

**Nothing authorized, executed or modified:** S5, the production contract, production batch OB0018 (PREPARED) and the frozen OB0018 result (UNDETERMINED, G-LOG-0080) are all untouched. No OB0018 re-run; no new hardening cycle; Decision A HUMAN-UNDECIDED.

**Sources (not restated in full):**
- load brief `20260925_1249_s5-load-decision-brief.md`;
- post-pilot brief `20260925_1355_s5-post-pilot-architecture-decision-brief.md` (§C, §E, §F, §G, §H);
- OB0018 readiness `20260926_OB0018-READINESS-REPORT.md` §4;
- OB0018 result `20260926_OB0018-PILOT-RESULT-REPORT.md`;
- G-LOG-0050…0054, 0079–0081.

---

## Established load facts (deterministic; readiness report §4; reused unchanged)

| Quantity | Value |
|---|---|
| labels / hubs | 1,975 / 66 |
| required bytes per label: median / p90 / p99 / max | 57,075 / 536,106 / 3,193,099 / 8,586,148 |
| labels above the demonstrated single-context capacity (1,088,267 B) | 86, carrying **43.8%** of required bytes |
| units at 600 KB (FFD) | 1: 1,790 · 2–3: 137 · 4–10: 36 · >10: 4 (max 13); 2,333 units; 358 unit boundaries |
| units at 300 KB (FFD) | 1: 1,610 · 2–3: 251 · 4–10: 91 · >10: 15 (max 25); 2,938 units; 963 boundaries |
| largest required **text** file / largest text page | 241,117 B / 25,041 B (post-pilot brief) |
| binary required files | 12 files (1.73 MB), touching 41 labels, all stage-2-only |

**Reproducibility note:** the unit-class counts sum to 1,967. Eight labels have no sized required file, so they yield zero units. No value is altered.

**New deterministic measurement for this package** (same method, metadata only; boundary effects arise only at **row-source adjacencies**, where the timeline change classes live):

| Budget | Row-source adjacencies, FFD (labels affected) | Row-source adjacencies, **row-first** packing (labels affected) | Units FFD = row-first | Labels whose row sources fit one unit |
|---|---|---|---|---|
| 300 KB | 529 (161) | **42 (28)** | 2,938 | 1,939 |
| 600 KB | 253 (82) | **7 (5)** | 2,333 | 1,962 |

- **Row-first packing:** row sources are packed contiguously in S-id order, starting a new unit only when the budget is full; the remaining files follow first-fit decreasing.
- **The OB0018 control:** its 8 row sources are 142,647 B, so under row-first packing it would have had **0** adjacencies (FFD gave 4).
- **This is engineering exposure, not fidelity.**

---

## D1. S5 load architecture (options 1–4 of post-pilot brief §H, re-evaluated)

**Two orthogonal choices:**
- (a) the **synthesis architecture** (options 1–4);
- (b) the **partition rule** (FFD vs row-first, and the budget; see D4).

Row-first packing is compatible with every option.

| | **1. Deterministic decomposition only** | **2. + mechanical consistency pass** | **3. + selective whole-file re-read** | **4. Hierarchical synthesis** |
|---|---|---|---|---|
| Architecture | units read files whole, emit file-reading records; one synthesizer forms the label object from records only | as 1, plus a deterministic invariant report (conflicts, calibration pairs, endpoints, adjacencies) the synthesizer must resolve | as 2, plus mandatory whole re-reads at adjacencies (and conflicts) within a frozen budget | unit → sub-label syntheses over adjacent units → label synthesis |
| Context/load | single-unit labels: identical to today; multi-unit: ≤ budget per unit + one records-only synthesis | as 1 + a negligible checker | as 2 + re-read bytes ∝ adjacencies (row-first 600 KB: 7 adjacencies population-wide) | as 1 + extra synthesis contexts per label |
| Decomposition boundaries | the partition rule (D4) | same | same | same + synthesis tree |
| Synthesis mechanism | records only | records + resolution of flagged items | records + page-proven re-reads | multi-level records |
| Re-read requirement | none | none | mandatory at adjacencies | none by default |
| Expected failure modes | cross-unit change classes; calibration; pair context (mechanism 5); out-of-row birth promotion (6) | same, minus detectable inconsistencies; risk of over-reaction to flags | same, minus adjacency classes; re-reads not aimed at pair evidence (OB0018: C missed RP1619) | compounding synthesis error; untested |
| Engineering cost | lowest (exists: pilot tooling) | low (exists: OB0018 `invariants`) | medium (exists: OB0018 arm C checks) | high (new) |
| Review cost | audit of synthesized objects | + review of resolutions | + re-read evidence | + intermediate objects |
| Provenance complexity | SOURCE → PAGE → RECORD → FIELD | + INVARIANT item → resolution | + RE-READ PAGE → FIELD | + intermediate layers |
| Implementation complexity | low | low | medium | high |
| Scalability to the tail (max 13 units at 600 KB) | scales; loss exposure ∝ adjacencies (row-first: 5 labels) | same | re-read cost small under row-first | depth grows with units |
| **OB0018 supports** (descriptive; frozen result UNDETERMINED) | **not refuted on this label** on judgment fields (arm A: 0 BASELINE-UPHELD); failed only on compliance (range notation, G-12) | not refuted on consistency metrics; **two birth errors** on this label | as B; the adjacency re-reads were upheld; **missed the pair-evidence escalation** | nothing (not tested) |
| **OB0018 does NOT establish** | validation; population reliability; heavy-tail (10–13-unit) behaviour; model independence | same | same | everything |
| Unresolved risks | mechanisms 5 and 6 unless safeguards (D8) apply; the untested tail | the same | the re-read target rule (adjacency vs pair evidence) | untested design |

**The three evidential levels are kept distinct:**
- *"not refuted on this label"* (what OB0018 descriptively shows for option 1 on judgment fields);
- *"validated"* (requires a determinate FL-1 result, which OB0018 did not produce);
- *"population reliable"* (requires FL-2).

## D2. OB0018 evidence integration

| Layer | Content |
|---|---|
| **A. Frozen result** | **UNDETERMINED** (G-LOG-0080): level-1 feasibility failed on the U02 `--help` transcript classification. No architecture decision is licensed by the frozen rules |
| **B. Mechanical evidence** | 34/34 files whole in units and in a single context; 0 hash failures; 0 foreign log entries; ledger unchanged; no seal breach; arms A/B read nothing; arm C re-read 5/5 adjacency files (70,594 B) |
| **C. Descriptive semantic evidence** | 80 blind dispositions. Judgment-field BASELINE-UPHELD: A 0; B 2; C 2 (births.conceptual/operational). All arms upheld on births.lexical (the baseline broke §11.2/§14.2) and on adjacency.S1629. 0 quote misses in all four objects |
| **D. Observed mechanisms** | brief #1–#3 not observed; #4 (non-label edge endpoints) present in unit records and contained at synthesis; **new #5 pair-context loss** (RP1619 escalation missed by every arm); **new #6 out-of-row birth-candidate promotion** (B, C); compliance defects (S-id ranges, G-12) caught only at verification |
| **E. Statistical limitations** | n = 1 label, 3 units, 4 adjacencies; same-family agents and auditor; partial blindness; the baseline is fallible; exact 95% upper bound after one clean label = 0.95 |

**The pilot is not called successful.**

## D3. Binary-file policy (12 files, 41 labels; none chosen)

| Policy | Provenance | Completeness | Byte budget | Extraction | Hold-out integrity | S5 scalability | Human review |
|---|---|---|---|---|---|---|---|
| read as text | false (decoded bytes are not text) | cannot complete | ~1.8M tokens for the largest file | meaningless | unchanged | blocks 41 labels | none |
| exclusion by mechanical rule (signature / NUL / non-UTF-8) | rule recorded per file | redefined as "all text required files" (a §26 change) | −1.73 MB | none | unchanged | unblocks 41 labels | rule only |
| NOT-CONSUMED-ESCALATED (D5) | honest non-consumption record | incomplete; G-04 fails for 41 labels | 0 | none | unchanged | 41 labels permanently fail G-04 | none |
| FALSE-HIT when the hit offset lies in binary content | offset → binary region, mechanically verifiable | dispositioned without reading | 0 | none | unchanged | unblocks; needs an F2 rule extension (§26) | per rule |
| binary-specific extraction (metadata / OCR) | a new evidence type | could complete | depends | new method, outside the frozen methodology | new surface | new tooling | per file |
| human decision per file | strongest | complete once decided | per decision | per decision | unchanged | 12 decisions | 12 files |

**Common consequence:** any option other than NOT-CONSUMED-ESCALATED changes a frozen rule (§26); NOT-CONSUMED-ESCALATED changes the outcome (41 labels never pass).

## D4. Paging and partition

**Two layers, decided separately:**
- the **page** (display-level delivery; post-pilot brief §F);
- the **unit** (context-level partition).

**Page layer:**

| Option | Behaviour | Tail effect |
|---|---|---|
| current: 20,000-character pages | max text page 25,041 B; binary pages can exceed the display limit | binary files only |
| byte-bounded 24,000 B pages on UTF-8 boundaries + binary refusal | every delivered page fits the display; binary refused with `BINARY-CONTENT` | removes the display failure mode; binary goes to the D3 policy |
| + per-page acknowledgement token | "saw the page end" becomes checkable | none on load; a small record overhead |

**Unit layer** (all deterministic; no performance coefficients asserted):

| Option | Definition | Measured consequence |
|---|---|---|
| fixed byte budget, FFD | 600 KB (or 300 KB), first-fit decreasing | 2,333 units / 358 boundaries / **253 row adjacencies** at 600 KB |
| adaptive byte budget | budget per label from its size (e.g. smallest budget giving ≤ k units) | unmeasured; makes unit count label-dependent; comparability across labels decreases |
| unit-count cap + byte bound | at most k units per label; the byte budget rises for tail labels | caps boundaries but can exceed demonstrated context capacity (1.09 MB) for the 86 heavy labels, whose max is 8.59 MB |
| **hybrid boundary-aware (row-first)** | row sources contiguous first; then FFD | **same 2,333 units**; row adjacencies **253 → 7** (82 → 5 labels) at 600 KB; 529 → 42 at 300 KB |

**Heavy tail:** the 4 labels with >10 units at 600 KB carry the residual adjacencies. Every option must still decompose them; none makes them single-context.

## D5. Production schema for unread files (pilot permission ≠ production permission)

- **Current status:** NOT-CONSUMED-ESCALATED is implemented behind the revision-4 gate and authorized for pilots only (G-LOG-0052). **It is not promoted by this package.**
- **Distinctions a production schema must keep separate:**

| Concept | Meaning | May support |
|---|---|---|
| historical fact | a statement with a verified quote from a completely read file | FOUND, births, change points |
| **not read** (READ-PARTIAL / READ-FAILED / NOT-CONSUMED) | the obligation was not discharged | nothing; forces ESCALATED plus a CONTRACT-DEVIATION naming the S-id |
| **not found** (NOT-FOUND-IN-CAPTURE / -BOUNDED) | searched, absent within the stated scope | a scoped absence claim only |
| **undefined after census** (GENUINELY-UNDEFINED-AFTER-CENSUS) | every required reading completed; absent | a label-level absence, only with a complete census |
| dependency/edge uncertainty | an endpoint unresolved or evidence type disputed (D6) | a candidate edge record, never an edge |

**Required before production adoption:**
1. a P3b §26 production contract revision (closed values, schema E, verifier gate);
2. a human decision: the reading-state field is separate or folded into the escalation detail;
3. the **one-directional rule**: only WHOLE-FILE supports whole-file results;
4. verifier tests proving that no non-WHOLE-FILE state can yield FOUND or GENUINELY-UNDEFINED;
5. a statement that G-04 is never thinned (41 binary labels, D3).

## D6. Co-label dependency-edge rule (none chosen; both readings reported)

| Reading | Rule | Consequence | OB0018 evidence |
|---|---|---|---|
| **R1: co-labels count** | an edge may rest on row co-labels between labels | more edges; risk of structural rather than stated dependency | arms vs baseline Jaccard **0.50** |
| **R2: quote required** | an edge requires a quote stating the dependency (deterministic proxy: the quote names the target) | fewer edges; only textually stated dependency | arms vs baseline Jaccard **1.00** |
| dual reporting | keep both edge sets, labelled | no loss; the downstream graph must choose a layer | as OB0018 |

**Downstream effect:** the P4+ dependency graph and every graph-derived diagnostic depend on this rule. **Edges never become historical fact without the chosen rule** (R-boundary).

## D7. Scan-rule clarification (future contract improvement; OB0018 result unchanged)

**What happened:** the U02 event (`p3b_read_source.py --help`) invoked the reader executable and read **no source**. The frozen OB0018 result stays UNDETERMINED.

**Proposed four-level taxonomy for a future scanner:**

| Level | Definition (deterministic) | Integrity consequence (proposal) |
|---|---|---|
| **reader invocation** | the command names the reader | none by itself |
| **source-access attempt** | the invocation names an S-id (or a corpus path or blob spec outside the reader) | checked for run/batch identity; a foreign run/batch is a violation |
| **successful source read** | a read-log entry with a verified page hash exists | counted for coverage; a foreign run/batch is a violation |
| **corpus-content exposure** | a successful read of a file not in the run's permitted set, or any hold-out access | a violation; a hold-out access is a SEAL-BREACH |

**Effect:** a `--help`, `--version` or usage invocation is a *reader invocation* only. Bypass detection (corpus paths, blob specs) is unchanged.

## D8. Three proposed safeguards (approved as proposed design requirements, G-LOG-0081; subject to the D1 ruling)

| | **S1 pair-context preservation** | **S2 row-membership filter for births** | **S3 synthesis-output lint** |
|---|---|---|---|
| Invariant | every P3a pair whose verdict rests on a file is checked against that file's content at synthesis | a birth may cite only a file that carries a row of the label | no synthesized record contains an S-id range, a layer-A register pointer, or a quarantine hit |
| Input | the slice's pair records (verdict, basis, cited source); unit records | slice rows (row sources); unit `birth_candidates` | the synthesized object, register and P1-gap records |
| Deterministic rule | (a) deliver the pair records to the synthesizer; **or** (b) mandatory whole re-read of each pair-evidence file in the synthesizer's run, with a per-pair VERDICT-EVIDENCE check recorded | at synthesis: `birth.source_id ∈ row_sources(label)`, else the candidate goes to a P1-gap record | run `quarantine_hits`, the G-12 layer check and an S-id range regex before submission; failure blocks submission |
| Failure condition | a pair with no recorded check; a verdict/evidence conflict without escalation | a birth on a non-row file | any lint hit |
| Implementation location | synthesis contract + a checker in the S5 tool | the verifier (a new G-rule) and the synthesis contract | the synthesis tool (pre-submit); the verifier re-checks |
| Audit evidence | pair-check records; re-read page ledger | the birth's source_id vs slice rows | lint report hash in provenance |
| Computational cost | (a) negligible; (b) re-read bytes ∝ pair-evidence files | negligible | negligible |
| False positive / negative | FP: a pair checked but judged conservatively; FN: evidence spread across files not listed as the basis | FP: a genuine birth in a file lacking a row (a P1-gap is the designed route); FN: none for the stated rule | FP: a legitimate "Step n–m" text matching the S-id pattern (restrict to `S\d{4}`); FN: ids written in another notation |

## D9. Statistical decision framework (n = 1 is not validation)

**Evidence ladder:**
- **FL-0 feasibility:** mechanically shown descriptively in OB0018; formally UNDETERMINED.
- **FL-1 local fidelity:** a determinate result on a label; not obtained.
- **FL-2 population robustness:** not attempted.

**FL-2 stratification dimensions (preserved):** boundary count B, dependency fan-out D, row-source count S, conflict density C. **Under row-first packing, B > 0 occurs in only 5 labels at 600 KB**, so the B stratum becomes nearly degenerate. The other dimensions remain.

**Sample-size formulas** (assumptions stated; no coefficients asserted):
- **Zero-failure bound:** with k labels independently sampled from a stratum and 0 failures, the exact one-sided (1−α) upper bound on the per-label failure probability p is 1 − α^(1/k). To reach p ≤ p₀: **k ≥ ln α / ln(1 − p₀)**. At α = 0.05: p₀ = 0.20 → k ≥ 14; p₀ = 0.10 → k ≥ 29.
  - **Assumptions:** independence across labels; a binary per-label outcome (FAITHFUL or not) under frozen rules.
- **Proportion estimate:** to estimate p within ±d at confidence 1−α, k ≈ z²·p(1−p)/d², worst case p = 0.5. For d = 0.1, k ≈ 97 per stratum. With field-level outcomes clustered in labels, multiply by the design effect 1 + (m−1)ρ (m fields per label, ρ the intra-label correlation, **unknown and to be estimated**).
- **Risk model (conceptual; §P of the OB0018 pre-registration):** logit P(error_ij) = β₀ + β_B·B_i + β_D·D_i + β_S·S_i + β_C·C_i + β_R·R_i + u_i + γ_field(j). **No coefficients exist.**
- **The decision FL-2 would inform:** whether the chosen architecture needs tail-specific safeguards. It is **decision-relevant only if** the human wants a population bound before S5, rather than monitoring during S5 (D11-H).

## D10. ML and computational optimization (never in the reconstruction decision)

**Pipeline boundary:** SOURCE → extraction → validation → computational/ML diagnostics → review queue → human adjudication → reconstruction → theory. **Never** SOURCE → ML → THEORY, or similarity → CANONICAL.

| Technique | May influence | Must never decide |
|---|---|---|
| deterministic candidate retrieval (exact/normalized quote and anchor matching) | which passages are shown together to a synthesizer or reviewer | equivalence, identity, a disposition |
| MinHash/LSH (fixed seed and threshold) | near-duplicate candidate lists across units and labels (calibration pairs, §C-3) | that two passages mean the same |
| embeddings (ranking only) | the order of candidates in a review queue | identity, merges, edges, births |
| graph diagnostics (cycles, contradiction components, degree outliers over **adjudicated** edges) | which labels are prioritized for audit | historical fact; theory structure |
| anomaly detection (e.g. per-unit FOUND-rate divergence) | flags for review | that a unit is wrong |
| uncertainty-based review prioritization (calibrated on adjudicated data; split-conformal sets) | review order and depth | truth; skipping review of any required item |
| active learning (only after adjudicated labels exist; frozen test set; held-out audit) | which items humans adjudicate next | labels for its own training (never self-training) |
| randomized audit sampling (Horvitz–Thompson over unreviewed items) | the estimate of residual error in the unreviewed tail | acceptance of any individual item |

**Decision-relevance rule:** introduce a technique only where a possible outcome changes a pending decision or measurably lowers review cost at equal reliability.

## D11. Human decision matrix (unranked; no recommendation)

| # | Decision | Options | Evidence | Advantages | Risks | Unresolved uncertainty | Consequences | Required follow-up |
|---|---|---|---|---|---|---|---|---|
| **A** | synthesis architecture | 1 / 2 / 3 / 4 | D1, D2 | 1: simplest, not refuted on judgment fields (one label); 2: catches detectable inconsistencies; 3: adjacency re-reads upheld; 4: none measured | 1: mechanisms 5 and 6 unless S1 and S2; 2/3: birth errors observed on one label; 4: untested | population fidelity (FL-2); tail behaviour | defines the S5 contract and tooling | a §26 contract revision |
| **B** | partition rule and budget | FFD / row-first × 300 KB / 600 KB / adaptive / unit-cap | D4 measurement | row-first: 253 → 7 adjacencies at equal unit count | adaptive/unit-cap: comparability; capacity for the 8.59 MB label | the fidelity effect of fewer adjacencies (not measured) | determines boundary exposure | a tool change (deterministic, testable) |
| **C** | binary policy | 6 options (D3) | 12 files, 41 labels | exclusion/FALSE-HIT unblock 41 labels | exclusion changes required sets; FALSE-HIT needs an F2 extension | per-file hit validity | G-04 outcome for 41 labels | §26 rule text; per-file list |
| **D** | page layer | current / byte-bounded + binary refusal / + ack token | post-pilot §F; S2276 | display-safe delivery | reader change; versioned logs | ack-token effectiveness | the delivery guarantee | a reader revision + tests |
| **E** | unread-file schema | not adopt / adopt one-directional set (field separate or folded) | D5 | honest states without weakening WHOLE-FILE | schema/verifier change | none major | production records of non-reading | §26 revision; verifier tests |
| **F** | co-label edge rule | R1 / R2 / dual | D6; OB0018 Jaccard 0.50 / 1.00 | R2: textual evidence; dual: no loss | R1: structural edges; dual: layered graph | effect on P4+ graph | graph semantics downstream | rule text |
| **G** | scan taxonomy | adopt four levels / keep current | D7; U02 event | benign invocations no longer void runs | a mis-specified level could hide access | none major | future run validity | contract + scanner change + tests |
| **H** | tail assurance before S5 | none (proceed) / FL-2 stratified study first / monitor during S5 with randomized audit sampling | D9 | none: fastest; FL-2: population bound; monitoring: evidence accrues with production | none: unbounded tail risk; FL-2: cost (k ≥ 14 per stratum for p₀ = 0.2); monitoring: errors found after the fact (repairable before P4) | per-label error rate | S5 timeline and confidence | a design for the chosen route |
| **I** | safeguards S1–S3 | adopt each / not | D8; OB0018 mechanisms 5, 6; level-2 defects | cheap, mechanical, auditable | FP/FN profiles (D8) | effectiveness at scale | contract and verifier rules | §26 revision; tests |

**After the rulings:**
1. **one** P3b §26 production contract revision bundling the adopted items;
2. its verification (tests, verifier);
3. an S5 authorization act (a human decision);
4. then the 396-batch, 1,975-label floor under R19.

**S5 DECISION PACKAGE READY — ANALYSIS ONLY — S5 NOT AUTHORIZED**
