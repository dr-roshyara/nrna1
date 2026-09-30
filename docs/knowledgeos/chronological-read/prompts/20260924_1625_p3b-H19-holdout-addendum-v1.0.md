# P3b H-19 ADDENDUM — sealed hold-out prediction test · v1.0

**Status: PROPOSED v1.0. Not approved. Gated addendum to the frozen P3b core v1.6.5 (§9F, H-19, D-67, D-70). The core
is not changed by this document.** It becomes operative only when independently verified and approved (§9F gate, §26).
**Evidence:** real S3 outputs (G-LOG-0015) and the frozen P0–P3a artifacts. The numbers below come from a deterministic
reference computation (`h19_select.py`, reproduced by the S3b script). No corpus reading and no search outcome informed
any choice here: the design uses only structure (labels, sources, groups, pairs, tiers, P3B-R1 membership).

---

## 1. Hold-out and discovery population (§9F item 1)

- **Hold-out labels H**: the labels of the components selected in §3.
- **Hold-out files HF**: every `CONTENT` source linked to a label in H by a ledger row (and, in variant S, by
  `files_touching`).
- **Discovery population**: census minus HF (files), and labels minus H. While sealed, every discovery record carries
  `population_basis: DISCOVERY-POPULATION (<seal id>)` (§3.6).
- The census stays 2,767. HF files remain census members; they are only invisible to discovery until the unseal.

## 2. Rules that run over the discovery population while sealed (§9F item 2)

- **§11.4 stage 1.** (a) the ledger search reads only rows whose `source_id` ∉ HF; (b) the raw search reads only
  discovery files. S3b derives this from the S3 per-S-id results by removing every HF key, without re-running S3, and
  recomputes each negative label. An exhaustive negative is written `negative_label: NEGATIVE-CENSUS` with
  `population_basis: DISCOVERY-POPULATION (<seal id>)`, and `files_searched` = the discovery file count. It never counts
  as a census-basis negative.
- **§11.4 stage 2.** Whole-file reading resolves through `ContentResolver` (G-LOG-0014) restricted to discovery files.
  Reading an HF file while sealed is a seal breach (§6).
- **§13.10a.** Every population, X and LEXICAL B/D search is evaluated over the discovery population. The plan records
  `population_basis`.
- **§13.9a (2).** "Census scope" reads as "discovery-population scope" while sealed. Every STATUS carries its
  `population_basis`, and a STATUS earned while sealed keeps `DISCOVERY-POPULATION` after the unseal. A census-basis
  B/D after the unseal is a new, pre-registered record in a later run.
- The hold-out labels' own S3 records (LABEL-HITS, DIMENSION) and bundles are moved by S3b to sealed files. They are
  never discovery input.

## 3. Selection algorithm (§9F items 3, 4, 8)

**Inputs (hashed into the seal):** `20-FAMILIES/_derived.json` (`reconciliation_objects`, `nodes`, `groups`),
`03-CONTRIBUTIONS.jsonl`, `02-FILES.jsonl`, `31-RECONCILIATION-PAIRS.jsonl`, `P3B-TIERS.jsonl`,
`ledger-p3b/OB0001–OB0003/objects.jsonl` (the 241 `P3B-R1` labels).

**Graph over the 2,497 labels (undirected; components by union-find):**
- E1: two labels share a source file through ledger rows (`labels[]` of a row);
- E2 (**variant S only**): two labels share a file through `files_touching`;
- E3: two labels are members of one P2a group;
- E4: two labels form a P3a pair (all 1,793 pairs).

**Eligibility.** A component is ineligible if any of the following holds; a mixed component is ineligible as a whole:
- R0 it is the giant component;
- R1 it contains a label that is not Tier U or Z (S5 cannot process Tier X before H-02);
- R2 it contains a `P3B-R1` label (§9F item 4);
- R3 it has no `CONTENT` source;
- R4 a label outside the component names one of its labels in its reconciliation object or node (whole-token match of
  the label string);
- R5 a ledger row of a source outside the component names one of its labels in `statement` or `type_signature`
  (whole-token match).

**Draw.** Target = 20% of the S5-eligible labels, round(0.20 × 2,020) = **404**. If the eligible labels number ≤ the
target, **all eligible components are taken**. Otherwise, components are ordered by
`sha256(seed || min(label in component))` with seed 20260924. Each component is added whole if the running total plus
its size stays ≤ target; one that would overshoot is skipped and the scan continues.

**Result on the real inputs (both below the target, so no draw is needed):**

| | **Variant M** (E1+E3+E4) | **Variant S** (E1+E2+E3+E4) |
|---|--:|--:|
| components / giant component | 96 / 2,255 labels | 31 / 2,447 labels |
| exclusions R0 / R2 / R3 / R4 | 1 / 7 / 7 / 10 | 1 / 1 / 2 / 1 |
| **hold-out labels / components** | **183 / 71** (9.1% of S5-eligible) | **45 / 26** (2.2%) |
| tiers | Z 173, U 10 | Z 43, U 2 |
| **hold-out files HF** / discovery files | **85** / 2,682 | **33** / 2,734 |
| hold-out absence dimensions / ledger rows in HF | 1,547 / 948 | 413 / 345 |
| groups / P3a pairs crossing the split | 0 / 0 | 0 / 0 |
| discovery ledger rows in HF | 0 | 0 |
| discovery labels whose `files_touching` names an HF file | **57** (handled in §4) | 0 |
| label-list sha256 | `6ba9f00c71d0180250f934886ec3526a727f0f396107e861dcd1dcc3f0449dd0` | `7febd747ad2d2a494e34db40cc29c95f33fe2003a8ea5b505324b74e1e13f0fc` |
| file-list sha256 | `8382804ffaec6417a786dce81f106dc78f27ef296558292c52089cbfd3137f97` | `46cdabe271768a9db7ea06967eaa6d3963c001f11680e2e0d896153f9e2d8a9d` |

S3 counts the design rests on (§9F item 8): 2,767 CONTENT files; 2,497 labels (2,493 with absence dimensions); 20,107
absence dimensions; 6,163 census-basis NEGATIVE-CENSUS dimensions at stage 1; 1,364,733 label-term matches (G-LOG-0015).

## 4. Removals from discovery inputs (§9F item 5)

- **Groups and P3a pairs:** none cross the split in either variant, because E3 and E4 are graph edges. S3b asserts 0
  and FAILS otherwise.
- **Terms:** hold-out labels' terms are not searched during discovery. Co-member notations are not used (S3-1).
- **Variant M only:** HF S-ids are removed from `files_touching` of the 57 discovery reconciliation objects in the
  discovery bundles. This is the filtering that §19.1 S3b allows; the frozen `_derived.json` is untouched. The removal
  is listed per label in the seal record.

## 5. Seal

S3b writes `P3B-HOLDOUT-SEAL.json` once, containing:
- the addendum sha256, the S3b script blob, the input hashes and the variant;
- the sorted label and file lists with their sha256, the component ids (smallest label per component), counts per
  exclusion rule, and the §4 removals;
- `state: SEALED`, set with a timestamp.

The lists are for scripts only; dispatch and pass tooling filter by them and never pass them to an agent. Only `state`
ever changes, at the unseal.

## 6. Quarantine (§9F item 6)

A script moves to `P3B-HOLDOUT-QUARANTINE.jsonl` (append-only) every discovery record that either
- names a hold-out label (whole token, any field), or
- cites an HF S-id.

Quarantined records are counted and reported, and never feed discovery. Reading an HF file, or a hold-out record
reaching an agent input, is recorded as a **seal breach** (`P3B-ESC`). The human then decides whether the unseal
result may still be scored.

## 7. Predictions, unseal, scoring (§9F item 7)

**Prediction format** (in the TEST-DEFINED plan of a CORPUS theory candidate with
`claim_type = EMPIRICAL-GENERALIZATION`, persisted in an earlier run, §13.10a):
- `prediction_id`, `rs_id`;
- target predicate **P** and outcome predicate **O**, both over fields of the hold-out labels' S5 records and of the
  frozen artifacts, with the §13.10a predicate restrictions;
- `rule`, one of:
  - `THRESHOLD`: rate(O | P) ≥ θ;
  - `CONTRAST`: rate(O | P) − rate(O | Q) ≥ δ, with a contrast predicate Q disjoint from P;
- θ or δ, and the disconfirmation condition (the negation of the rule).

**Minima (pre-registered constants):** n(P) ≥ 5 labels from ≥ 2 distinct hold-out components (components share no
file); for CONTRAST also n(Q) ≥ 5.

**Scoring by script** into `P3B-PREDICTION-SCORES.jsonl`:

| Outcome | When |
|---|---|
| `NOT-SCOREABLE` | P, O or Q references a field absent from the hold-out S5 records, or O is undetermined for more than 20% of P |
| `UNDERPOWERED` | a minimum is not met; or, for CONTRAST, the difference is ≥ δ but the one-sided Fisher exact test is not significant at 0.05 after Holm correction over all CONTRAST predictions of the pass |
| `PREDICTION-CONFIRMED` | THRESHOLD: the rate is ≥ θ. CONTRAST: the difference is ≥ δ and significant after Holm |
| `PREDICTION-FAILED` | minima met and the observed value is below θ (or δ) |

Evaluation follows the precedence NOT-SCOREABLE → UNDERPOWERED → the test.

**Unseal (S5c).** Only after every prediction of the pass is persisted. It is a human act recorded in
`P3B-GOVERNANCE-LOG.md`, and `state` becomes `UNSEALED`. The hold-out labels then get standard S5 processing under the
same contract, and no prediction is shown to the agents. Then scoring runs.

**One unseal, no changes after.** Predictions, scoring rules, the hold-out set and the variant are frozen. No second
subset may be drawn. A PREDICTION-FAILED counts as a disconfirmation of its candidate (§13.9b).

## 8. Limitations (reported with every PREDICTIVE result)

- **Periphery (L-9, now measured):** the hold-out is 95% Tier Z (labels with no P3a pair), from small components.
  Predictions are tested on the corpus periphery, not its core. A representative file-level hold-out remains deferred to
  P5 (D-67).
- **Power (L-4):** 71 components / 183 labels (variant M) or 26 / 45 (variant S). Many predictions may be UNDERPOWERED,
  which is never evidence of absence.
- **Prior exposure (L-11):** P1–P3a already processed all material, and the researchers have seen the corpus. The seal
  prevents P3b discovery inputs from containing hold-out material; it cannot erase earlier exposure.
- **Conceptual overlap:** in variant M, P1 linked 57 discovery labels to HF files. The seal keeps those files out of
  discovery; it does not make the hold-out conceptually independent. Predictions test generalization, not isolation.

## 9. Decisions for the human

| # | Decision | Recommendation |
|---|---|---|
| **H19-D1** | Variant **M** (183 labels, 85 files; `files_touching` links to HF redacted from 57 discovery objects) or **S** (45 labels, 33 files; no redaction; strictest seal) | **M**: S leaves too little power to score predictions (26 components). M keeps every reading path sealed (no HF file readable, no hold-out label visible); the redaction removes metadata only |
| H19-D2 | The minima (n ≥ 5 labels from ≥ 2 components; 20% undetermined cut-off; Holm at 0.05 for CONTRAST) | adopt as written |
| H19-D3 | Approve this addendum after independent verification (§9F gate) | — |

**Unchanged:** the frozen core v1.6.5 · the census (2,767) · A.4 · S0–S3 outputs · P3a · H-04.
