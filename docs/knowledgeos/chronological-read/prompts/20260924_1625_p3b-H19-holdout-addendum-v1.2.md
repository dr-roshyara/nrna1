# P3b H-19 ADDENDUM — sealed hold-out prediction test · v1.2

**Status: PROPOSED v1.2. Not approved.** This is a gated addendum to the frozen P3b core v1.6.5 (§9F, H-19, D-67, D-70);
the core is not changed by it.
**Supersedes:** `prompts/20260924_1625_p3b-H19-holdout-addendum-v1.1.md` and v1.0, both kept unchanged. The
verification of v1.0 found B-1 to B-3 and M-1 to M-9. The verification of v1.1 found that B-3 was still open: an
outcome field set by rule (`semantic_status` null for Tier Z), plus a baseline p0 biased toward the periphery, let a
trivial prediction confirm. v1.2 changes only §7 and the wording points listed in §10.
**Human decisions:** H19-D1 = **variant S**, taken after the corrected measurements (variant M leaked, B-1 and B-2).
H19-D2 = the minima, as revised in §7.
**Evidence:** real S3 outputs (G-LOG-0015) and the frozen P0–P3a artifacts. Every number comes from deterministic
computation and was reproduced independently. Only structure informed the design: labels, sources, groups, pairs, tiers,
P3B-R1 membership, text containment and name references. No search outcome did.

---

## 1. Hold-out and discovery population (§9F item 1)

- **Hold-out labels H**: the labels of the components selected in §3.
- **Hold-out files HF**: every `CONTENT` source linked to a label in H through a ledger row or through `files_touching`.
- **Discovery population**: the census minus HF (files) and the labels minus H. While sealed, every discovery record
  carries `population_basis: DISCOVERY-POPULATION (<seal id>)` (§3.6).
- The census stays 2,767. HF files remain census members; they are invisible to discovery until the unseal.
- **After the unseal** (S5c, S6, S6a, S7), discovery population = census again. Records made while sealed keep their
  `DISCOVERY-POPULATION` basis.

## 2. Rules over the discovery population while sealed (§9F item 2)

- **§11.4 stage 1.**
  - (a) The ledger search reads only rows whose `source_id` ∉ HF. (b) The raw search reads only discovery files.
  - S3b derives these results from the S3 per-S-id results by removing every HF key; S3 is not re-run.
  - An exhaustive negative is written `negative_label: NEGATIVE-CENSUS` with
    `population_basis: DISCOVERY-POPULATION (<seal id>)`, and `files_searched` = the discovery file count. It never
    counts as a census-basis negative. The measured effect: 2 discovery labels have all their stage-1 hits in HF.
- **§11.4 stage 2.** Whole-file reading goes only through `ContentResolver` (G-LOG-0014), restricted to discovery
  files. `GENUINELY-UNDEFINED-AFTER-CENSUS` records carry the population basis.
- **§13.10a.** Every population, X, generator input (A.6) and control set (A.7) is evaluated over discovery labels and
  files only. The plan records `population_basis`.
- **§13.9a (2).** "Census scope" reads as "discovery-population scope" while sealed. Every STATUS carries its basis,
  and a STATUS earned while sealed keeps it. A census-basis B/D after the unseal is a new record, pre-registered in a
  later run.
- **Evidence level (§13.9b).** `evidence_level` reports the `population_basis` of the STATUS it rests on. A
  THEORY-SUPPORTED resting on a DISCOVERY-POPULATION STATUS is reported with that basis and is never relabelled.
- **S3 outputs are not modified** (G-LOG-0015). S3b writes derived files:
  - `P3B-DISCOVERY-SEARCH.jsonl`: the discovery-population search records;
  - `_batch_input_r2/bundle_index_discovery.jsonl`: the S3 index without the hold-out labels' records, otherwise
    byte-identical records;
  - `P3B-HOLDOUT-SEALED-S3.jsonl`: the hold-out labels' S3 search and index records (sealed; scripts only).

  These addendum artifacts are regenerable from S3 and the seal. They join the §24.1 list in the next core revision.

## 3. Selection algorithm (§9F items 3, 4, 8)

**Inputs (hashed into the seal):**
- `20-FAMILIES/_derived.json` (`reconciliation_objects`, `nodes`, `groups` as a dict or a list);
- `03-CONTRIBUTIONS.jsonl`, `02-FILES.jsonl`, `08-OVERLAP-REGISTER.jsonl`, `31-RECONCILIATION-PAIRS.jsonl`,
  `P3B-TIERS.jsonl`;
- `P3B-IDENTITY-MANIFEST.jsonl` (the content bytes);
- `ledger-p3b/OB0001–OB0003/objects.jsonl` (the 241 `P3B-R1` labels).

**Graph** over the 2,497 labels of `reconciliation_objects`; undirected; components by union-find:
- E1: two labels share a source file through ledger rows (`labels[]`, restricted to the 2,497);
- E2: two labels share a file through `files_touching`;
- E3: two labels are members of one P2a group;
- E4: two labels form a P3a pair (all 1,793 pairs).

The **giant component** is the largest. A tie goes to the component containing the lexicographically smallest label.

**Name matching.** A label *is named* in a record when the label string occurs in
`json.dumps(record, ensure_ascii=False)` with no label character `[A-Za-z0-9._-]` immediately before or after it. It is
matched against the label set, not by tokenising. For §6 quarantine of prose, a match is also tested after removing one
trailing `.`.

**Exclusion rules.** A component is ineligible if any rule holds; a mixed component is ineligible as a whole:

| Rule | Condition |
|---|---|
| R0 | the giant component |
| R1 | contains a label that is not Tier U or Z |
| R2 | contains a `P3B-R1` label |
| R3 | has no `CONTENT` source |
| R4 | a label outside the component names one of its labels in its reconciliation object or node |
| R5 | any field of a ledger row from a source outside the component names one of its labels |
| R6 | the `02-FILES` record of a source outside the component names one of its labels |
| R7 | one of its files is paired with an outside file in `08-OVERLAP-REGISTER.jsonl`, or ≥ 30% of its **distinct** long lines (stripped lines of ≥ 40 characters, read through the manifest) occur in a single outside `CONTENT` file |

Counts are reported both ways: by first failing rule in the order R0 → R7, and per rule independently.

**Eligible components** are those that fail no rule. **S5-eligible labels** are the labels of Tier U or Z (2,020).

**Draw.** Target = round(0.20 × 2,020) = **404** labels.
- If the labels in eligible components number ≤ the target, all eligible components are taken.
- Otherwise, components are ordered by the hex sha256 of the UTF-8 bytes of `"20260924|" + min(label in component)`,
  ascending. Each component is added whole while the running total plus its size stays ≤ the target; a component that
  would overshoot is skipped, and the scan continues.

**Result on the real inputs (variant S; no draw needed):**

| Measure | Value |
|---|--:|
| components / giant component | 31 / 2,447 labels |
| candidate components after R0–R3 | 27 |
| excluded after R3, first rule / per rule | R4 1 / R4 1, R5 0, R6 1, R7 0 |
| **hold-out components / labels / files** | **26 / 45 / 33** (2.2% of the 2,020 S5-eligible labels) |
| singleton components / components with ≥ 2 labels | 21 / 5 |
| tiers | Z 43, U 2 |
| hold-out absence dimensions / ledger rows in HF | 413 / 345 |
| groups / P3a pairs crossing the split | 0 / 0 |
| discovery ledger rows in HF; discovery objects whose `files_touching` names an HF file | 0; 0 |
| label-list sha256 (`json.dumps(sorted(H))`) | `7febd747ad2d2a494e34db40cc29c95f33fe2003a8ea5b505324b74e1e13f0fc` |
| file-list sha256 | `46cdabe271768a9db7ea06967eaa6d3963c001f11680e2e0d896153f9e2d8a9d` |

S3 counts the design rests on (§9F item 8): 2,767 CONTENT files; 2,497 labels (2,493 with absence dimensions); 20,107
absence dimensions; 6,163 census-basis NEGATIVE-CENSUS dimensions at stage 1; 1,364,733 label-term matches.

## 4. Removals from discovery inputs (§9F item 5)

- **Groups and P3a pairs:** none cross the split, because E3 and E4 are edges. S3b asserts 0 and fails otherwise.
- **Labels:** no discovery input names a hold-out label (R4 to R6). S3b asserts this over every discovery bundle and
  fails otherwise.
- **Terms:** hold-out labels' terms are not searched during discovery. Co-member notations are not used (S3-1).
- **Discovery bundles are not redacted.** S-id *mentions* of HF files that remain in discovery metadata stay unedited.
  The v1.0 verification measured 2 `pairs_touching` entries, 1 row lineage text and 13 bundles through 02-FILES
  records. They are listed in the seal and give no access: every read goes through `ContentResolver`, and S3b adds the
  seal check that makes it refuse HF files while sealed (the G-LOG-0014 resolver does not know the seal yet).
- **Author and agent inputs.** The committed S3 outputs (`P3B-ABSENCE-SEARCH.jsonl`,
  `_batch_input_r2/bundle_index.jsonl`) and the frozen `_derived.json`, `03-CONTRIBUTIONS.jsonl` and `02-FILES.jsonl`
  still contain hold-out records in clear text. **No agent input and no prediction author's input may contain a
  hold-out label's records from them.** Discovery tooling serves only the derived discovery files (§2) and
  discovery-filtered views. A violation is a seal breach (§6). The human researchers' earlier exposure remains L-11.

## 5. Seal

S3b writes `P3B-HOLDOUT-SEAL.json` once, containing:
- the addendum sha256, the S3b script blob, the input hashes, and `variant: S`;
- the sorted label and file lists with their sha256, the component ids (smallest label per component), and the
  exclusion counts both ways;
- the §4 lists of S-id mentions;
- `state: SEALED`, with a timestamp.

The lists are for scripts only. Dispatch, pass and resolver tooling filter by them and never pass them to an agent.
Only `state` ever changes, at the unseal.

## 6. Quarantine (§9F item 6)

A script moves to `P3B-HOLDOUT-QUARANTINE.jsonl` (append-only) every discovery record or agent input that either
- names a hold-out label (§3 name matching, any field), or
- cites an HF S-id outside the listed §4 mentions.

Quarantined records are counted and reported, and never feed discovery. Reading an HF file while sealed, or a hold-out
record reaching an agent input, is a **seal breach** (`P3B-ESC`). The human then decides whether predictions may still
be scored.

## 7. Predictions, unseal, scoring (§9F item 7)

**Format.** Each prediction sits in the TEST-DEFINED plan of a CORPUS theory candidate with
`claim_type = EMPIRICAL-GENERALIZATION`, persisted in an earlier run (§13.10a). It states:
- `prediction_id` and `rs_id`;
- a target predicate **P** over frozen-artifact fields (§13.10a restrictions);
- an outcome predicate **O** over the **O-allowlist** only. These are agent-judgment fields of the hold-out labels'
  S5 records, which exist only after the unseal:
  - `type_status`, `mathematical_status`, `primary_layer`, `secondary_roles`;
  - `dependency_edges` (count, kinds);
  - the birth resolutions (`ESTABLISHED-*` / `MOVED` / `UNORDERED-BLOCK` / `NOT-EVIDENCED-IN-CAPTURE`);
  - the absence resolutions of dimensions **whose stage-1 result had hits** (FOUND vs GENUINELY-UNDEFINED-AFTER-CENSUS
    after reading).

  **Excluded by name:**
  - `working_label`;
  - `semantic_status`, its note and `pair_breakdown` (set by rule; null for Tier Z);
  - every free-text field;
  - timeline fields;
  - every field copied from P1, P2 or S3;
  - the absence outcome of any dimension whose stage-1 result was NEGATIVE-CENSUS (mechanically GENUINELY-UNDEFINED).

  The scoring script checks O's field references against this allowlist; this check replaces "evaluable on frozen
  artifacts". The allowlist is an explicit exception to §13.10a's allowed-field list, for O only;
- `rule`, one of:
  - `THRESHOLD`: the component rate of O among P-components ≥ θ;
  - `CONTRAST`: the component rate among P-components minus the rate among Q-components ≥ δ, with a contrast
    predicate Q disjoint from P;
- θ or δ, and the disconfirmation condition (the rule's negation);
- **θ**: a **margin** over the stratified baseline, θ ≥ 0.

**Unit of analysis: the component.** Labels within one component share files and are not independent.
- A **P-component** is a hold-out component with ≥ 1 label satisfying P.
- It *shows O* iff **more than half** of its P-labels with a determined O satisfy O.
- **Undetermined** means that O's field is absent, `null`, or `UNDETERMINED` for a label. Such labels leave the
  denominator.
- For CONTRAST, a component satisfying both P and Q is excluded from both arms and reported.

**Stratified baseline (replaces the v1.1 p0).** The hold-out is deliberately peripheral, so a discovery-wide rate is no
valid baseline. The baseline is computed by script at scoring time, from the discovery labels' S5 records frozen before
the unseal:
- **Strata** are built from frozen fields only: tier (U/Z) × `row_count` band (1, 2–3, 4–9, ≥ 10) × source-count band
  (1, 2–3, ≥ 4).
- r_s = the rate of O among discovery labels in stratum s that satisfy P.
- For each P-component c, p_c = the probability that more than half of its determined P-labels satisfy O, when each
  label satisfies O with the rate r_s of its own stratum. This is an exact Poisson-binomial calculation.
- A component whose stratum has no discovery label satisfying P has no baseline. It is excluded and reported. If more
  than 20% of P-components are excluded this way, the result is NOT-SCOREABLE.
- The baseline rate is p̄ = mean(p_c).

**Minima:** ≥ 5 P-components (and for CONTRAST ≥ 5 Q-components), each with ≥ 1 determined label.

**Tests:**
- THRESHOLD: the observed share of P-components showing O must be ≥ p̄ + θ. The one-sided exact Poisson-binomial
  test uses the per-component p_c.
- CONTRAST: one-sided Fisher exact test on components.
- Holm correction at α = 0.05 over the **family = every prediction persisted before the unseal**. Predictions scored
  NOT-SCOREABLE or UNDERPOWERED enter the family with p = 1.

**Scoring by script** into `P3B-PREDICTION-SCORES.jsonl`, evaluated in this precedence:

| Outcome | When |
|---|---|
| `NOT-SCOREABLE` | P, Q or O references an absent field; O references a field outside the O-allowlist; O is undetermined for more than 20% of P-labels; or more than 20% of P-components have no baseline |
| `UNDERPOWERED` | a minimum is not met; or the observed share is ≥ p̄ + θ (CONTRAST: the difference is ≥ δ) but not significant after Holm |
| `PREDICTION-CONFIRMED` | observed share ≥ p̄ + θ (difference ≥ δ) **and** significant after Holm |
| `PREDICTION-FAILED` | minima met and observed share < p̄ + θ (difference < δ) |

**Unseal (S5c).** Only after every prediction of the pass is persisted. It is a human act in
`P3B-GOVERNANCE-LOG.md`, and `state` becomes `UNSEALED`. The hold-out labels then get standard S5 processing under the
same contract; no prediction is shown to the agents. Then scoring runs.

**One unseal, no changes after.** Predictions, rules, the strata definition, the hold-out set and the variant are frozen, and no second
subset may be drawn. A PREDICTION-FAILED **blocks PREDICTIVE** for its candidate (§13.9b) and is recorded as a failed
prediction. It is never read as REFUTED-IN-CORPUS, which keeps its own §13.9a rule.

## 8. Limitations (reported with every PREDICTIVE result)

- **Size and power.** 26 components / 45 labels.
  - THRESHOLD predictions need ≥ 5 P-components, so only broad predictions are scoreable.
  - CONTRAST is close to unreachable: the best one-sided Fisher p with 5 vs 5 components is 0.004 before Holm.
  - UNDERPOWERED is never evidence of absence (L-4).
  - The predictive test is **weak by construction**, not merely inconvenient: the corpus admits only a small sealed
    hold-out.
- **Periphery (L-9).** 96% Tier Z, from small components. Predictions test generalization to the corpus periphery,
  not its core. A representative file-level or new-material hold-out remains deferred to P5 (D-67).
- **Prior exposure (L-11).** P1–P3a processed all material, and the researchers have seen the corpus. The seal
  controls P3b discovery inputs only.
- **Conceptual overlap.** The A.4 terms of 12 hold-out labels occur in the raw text of 124 discovery files. This cannot
  be avoided, and the seal keeps the hold-out *labels and files* unseen, not the words. There are also 6 HF rows
  (S0233) whose `candidate_of` names a discovery label; this is hold-out-side metadata and does not flow into
  discovery.

## 9. Decisions

| # | Decision | Status |
|---|---|---|
| H19-D1 | variant S | **decided** by the human after the corrected measurements |
| H19-D2 | the §7 minima and tests (component unit, more-than-half rule, ≥ 5 components, O-allowlist, stratified Poisson-binomial baseline with margin θ, Fisher, Holm at 0.05 over all persisted predictions, 20% cut-offs) | as revised here; to be confirmed with the approval |
| H19-D3 | approve this addendum after independent verification (§9F gate) | open |

## 10. Traceability of the v1.0 findings

| Finding | Fix |
|---|---|
| B-1 content containment (variant M) | R7; the chosen variant S has none |
| B-2 hold-out labels named in discovery bundles (M) | R5 over every row field, R6 over 02-FILES records; S3b assertion (§4); S has none |
| B-3 outcome computable before the unseal | v1.1: O restricted to post-unseal S5 fields. **v1.2** (still open in v1.1: a rule-set outcome field and a periphery-biased p0 let `semantic_status is null` confirm at p = 3e-6): a named O-allowlist of agent-judgment fields checked by script; rule-set and copied fields excluded; a baseline stratified on frozen fields with a per-component exact Poisson-binomial test and a margin θ; hold-out records barred from author and agent inputs (§4, §7) |
| M-1 THRESHOLD trivially easy | v1.2: stratified baseline p̄ with margin θ and an exact Poisson-binomial test, Holm (§7) |
| M-2 non-independent labels | component as the unit; minima in components (§7) |
| M-3 Holm family and "undetermined" undefined | defined (§7) |
| M-4, M-5 wording (precedence, seed encoding, "eligible", giant tie) | defined (§3) |
| M-6 HF S-id mentions in discovery metadata | listed in the seal; no access through the resolver (§4) |
| M-7 editing S3 outputs | S3 outputs untouched; derived files named (§2) |
| M-8 population after the unseal; GENUINELY-UNDEFINED basis; A.6/A.7 | defined (§1, §2) |
| M-9 THEORY-SUPPORTED basis; the meaning of FAILED | defined (§2, §7) |
| notes: unmatchable labels, flipping negatives, raw-text overlap, `candidate_of` rows | name matching against the label set (§3); reported (§2, §8) |
| v1.1 points: matched text, trailing `.`, distinct lines in R7, P∩Q components, "missing", ≥ half vs label p0, resolver seal check, S3 outputs as inputs | defined (§3, §4, §7) |

**Unchanged:** the frozen core v1.6.5 · the census (2,767) · A.4 · S0–S3 outputs · P3a · H-04.
