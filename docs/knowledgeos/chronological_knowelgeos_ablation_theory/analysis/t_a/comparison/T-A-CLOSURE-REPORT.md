# T-A CLOSURE REPORT — three readers (class: EXPERIMENT; not validation, not refutation, not canonical)

| | |
|---|---|
| **Frozen protocol** | pre-registration r3 `be16deb7…`; `aggregate.py` `13532a5b…`; released scope F0018 plus 7 ES-006 objects (L0-DEC-31) |
| **Ledgers (each sealed before comparison)** | SELF `abfa919b…` (26 records; author, not blind) · BLIND-SECONDARY `de5f2322…` (32; Claude subagent, blind, same family) · **INDEPENDENT `95bcdf13…` (18; `deepseek-chat` via Claude Code CLI 2.1.283; human-commissioned; different model family)** |
| **Independence limitation (accepted by the human, F-LOG-0048)** | the INDEPENDENT reader's exact launch directory is not independently recorded. Global Claude Code config has no hooks and no KnowledgeOS content; the working directory was outside the repository |
| **Comparison** | `compare3.py`, rules fixed before inspection (F-LOG-0048) → `THREE-WAY.json` sha256 `17cc70af…`. Aggregates: SELF `0d003b6d…` · BLIND-SECONDARY `fbe18e69…` · INDEPENDENT `b4e6f7d9…` |

## 1. Per-ledger frozen aggregation (never averaged, no majority field)

| Axiom | SELF | BLIND-SECONDARY | INDEPENDENT |
|---|---|---|---|
| A2e | SUPPORTED | INCONCLUSIVE | SUPPORTED |
| A3g | SUPPORTED | SUPPORTED | SUPPORTED |
| A4 | SUPPORTED | SUPPORTED | SUPPORTED |
| A5e | INCONCLUSIVE | INCONCLUSIVE | INCONCLUSIVE |
| A5g | SUPPORTED | SUPPORTED | SUPPORTED |
| A6 | NOT_EVIDENCED | INCONCLUSIVE | INCONCLUSIVE |
| A0 | SUPPORTED | SUPPORTED | NOT_EVIDENCED |
| A3m | SUPPORTED | SUPPORTED | INCONCLUSIVE |
| Q-GS, Q-D4 | NOT_RUN | NOT_RUN | NOT_RUN |
| **H-F2-1a (r3 §5)** | **INCONCLUSIVE** | **INCONCLUSIVE** | **INCONCLUSIVE** |
| stability extension | NOT_REFUTED_IN_T-A | NOT_REFUTED_IN_T-A | NOT_REFUTED_IN_T-A |
| DIRECT COUNTEREXAMPLES | 0 | 0 | 0 |

## 2. Mechanical comparison (pairwise; the F-LOG-0048 classes)

| Pair | EXACT | EQUIV. WORDING | TYPING | EFFECT | AMBIGUITY | SUBSTANTIVE | PROVENANCE | EVIDENCE-ANCHOR (reader-only) |
|---|---|---|---|---|---|---|---|---|
| SELF · BLIND-SECONDARY | 0 | 11 | 4 | 3 | 1 | 0 | 0 | 20 |
| SELF · INDEPENDENT | 0 | 3 | 3 | 3 | 1 | 1 | 0 | 22 |
| BLIND-SECONDARY · INDEPENDENT | 1 | 2 | 0 | 7 | 2 | 2 | 0 | 22 |

- **Reading guide.** Most differences are **evidence-anchor** differences: the readers selected different passages for the same question. Among matched passages, most differences concern typing detail or which effect fields were filled, not the classification.
- **Substantive disagreements (4 pairs):**
  - F-A6, ES-006.1 ladder: INDEPENDENT DIRECT_SUPPORT vs SELF and BLIND NOT_EVIDENCED;
  - F-A5e, the WORK-EXECUTION row: INDEPENDENT DIRECT_SUPPORT vs BLIND NOT_EVIDENCED.
- **Ambiguity disagreements (5 pairs):**
  - F-A3m, the EVIDENTIAL row (×2);
  - F-A6, ES-006.2 (×1);
  - F-A5e, P-8 (×1);
  - F-A6, ES-006.4 (matched pairs of INDEPENDENT against the others).

## 3. Closure by category

| Category | Items | Basis |
|---|---|---|
| **Supported (all three readers)** | **A3g, A4, A5g** | each reader's verdict SUPPORTED, from partly different passages. ⚠ This is **source fidelity to F0018**, from which H-F2-1-R was formed, **not corroboration** |
| **Inconclusive, unanimous** | **A5e** | all three readers classify F0018 §5 *"P-7 produces P-10 — work produces evidence"* as AMBIGUOUS with a live DIRECT_COUNTEREXAMPLE reading |
| **Inconclusive, replicated by both blind readers** | **A6** | both BLIND-SECONDARY and INDEPENDENT independently identified ES-006 `668cc7b22` *"ADOPTED via explicit DA early-promotion exception R-39"* as AMBIGUOUS with a live DIRECT_COUNTEREXAMPLE reading. **SELF missed this passage.** INDEPENDENT also reads ES-006.1/.2/.3 as DIRECT_SUPPORT for A6, where SELF and BLIND did not; under §5.0 rule 2 the live counterexample reading dominates |
| **Unresolved reader disagreement** | **A2e** | SUPPORTED ×2; INCONCLUSIVE ×1 (BLIND-SECONDARY only: P-3 *"demotion-by-decision"* read as possibly moving an evidential position). Neither other reader recorded that reading under A2e |
| | **A0** | SUPPORTED ×2 (F0018 P-10 *"n≥2"* threshold); NOT_EVIDENCED ×1 (INDEPENDENT recorded no F-A0 passage). An evidence-anchor difference |
| | **A3m** | SUPPORTED ×2; INCONCLUSIVE ×1 (INDEPENDENT: *"only by refutation"* read as a possible A3m violation). **Protocol-wording observation (not an adjudication):** formal A3m *permits* refutation (EVIDREF) to decrease e, while the blind packet's F-A0/F-A3m question is worded *"loss of qualification through additional evidence"*. The competing reading may reflect that wording. Recorded as a possible packet-clarity issue for a future revision; the frozen packet is not changed |
| **Contradicted** | **none** | 0 DIRECT_COUNTEREXAMPLE records in any ledger |
| **Provenance discrepancy** | **OBS-SF-1** (source fidelity) | recorded by SELF only: F0018 attributes an *n≥2 / "never promote from a single occurrence"* bar to ES-006.1, which the released ES-006 text does not contain. INDEPENDENT quotes the F0018 phrase (P-3) without assessing it; the others did not test it. **Unresolved** |
| | **reader provenance** | INDEPENDENT launch-directory limitation (accepted). No PROVENANCE_OR_DATA_DISCREPANCY row in the mechanical comparison |
| **Not evidenced / not run** | Q-GS, Q-D4 | M-2/M-3 not released |

## 4. T-A outcome

> ## **T-A CLOSES: INCONCLUSIVE** (the same category in all three ledgers; 0 direct counterexamples; never averaged)

**What the three-reader evidence establishes:**
- The pre-registered test reproduces at the outcome level across three readers, including one from a different model family.
- No reader found a direct counterexample to any axiom in the released sources.
- A3g, A4 and A5g agree with F0018 for all readers (fidelity).
- **The two decisive open questions are reproducible, not reader artefacts:**
  - A5e (*"work produces evidence"*) is ambiguous for all three readers;
  - A6 has a live counterexample reading (R-39) found independently by both blind readers.
- Since D1 and D3 depend on A6, **R-39 is the most consequential open item**.
- The author-reader under-detected adverse evidence (R-39) relative to both blind readers.

**What it does NOT establish:**
- That H-F2-1-R is true, validated, general or canonical.
- That it is refuted: no counterexample was found, only live counterexample *readings*.
- Any meaning of R-39: bar changed, bar bypassed, evidence unstated, or composite. It is unread beyond the released pointer row.
- Anything outside F0018 plus the 7 ES-006 objects.
- Corroboration: agreement with F0018 is fidelity.
- Resolution of the A2e, A0 and A3m reader disagreements.

The T-0056 provenance caveat applies to every statement above.

## 5. Stopped

No change to H-F2-1-R or the axioms. R-39 and CAP-001 not read. A5e and A6 not investigated beyond the released material. No T-B, no ML, no canonicalization.

**Next:** a separate L0 decision on the targeted evidence release (R-39 → CAP-001 §9 → A2e demotion → A5e), each authorized separately. The A3m packet-wording observation is a candidate item for any future protocol revision, not for this one.
