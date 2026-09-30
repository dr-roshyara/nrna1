# P3b PASS CONTRACT — S5a cross-object pass and S5b corpus pass

**Governing documents:** frozen core **v1.7** (sha256 `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12`), H-19 addendum v1.5 (sealed HS-3d32dd44d162), approved
S5 plan v2.3.2 (sha256 `f89a16824efaeea08d54fbc857b4cf36184cb698deb482550026fe194b3c7de1`), the pass plan `audit-p3b/S5A-PASS-PLAN.json` (output_sha256 `97279830533d1fcee8f7e129468940af39095bdf4f69fb9bba653e925b7c64ae`; it must carry a human
approval entry in the governance log before any pass runs), governance log up to G-LOG-0045. The protocol text below
is verbatim; the core governs. **H-19 stays SEALED; S5c is PROHIBITED** (P3B-ESC-0001, G-LOG-0045): no pass unseals,
reads, scores or refers to hold-out material, and nothing in this contract authorizes S5c.

## A. Inputs and blinding (§9E.2 item 4)

1. **S5a analyst input: the blinded file `P3B-CROSS-BLIND.jsonl` only** (A.7; §9E.2 item 4). Each unit carries its
   `unit_key`, its member labels and their evidence pointers `{source_id, row_line, anchor}`; defining-link pointers are
   stripped. Every unit shows **exactly one pointer per member** (blinding annex M4-R1, scope A'1), and some sets are
   withheld from the file; neither carries information about role. Do not infer anything from how many pointers a
   unit shows. **You receive no object records, timelines, dependency edges, generator, role or defining property.** The
   reveal file is sealed until every unit is dispositioned (the reveal script refuses otherwise).
2. **Candidate-check reads:** at most **2 whole-file reads per analysed set**, only through
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run OA####-R2 --batch OA#### --label
   <set key> --step 10 S####`. No other file may be opened, listed or searched. No helper agents.
3. **Model:** claude-opus-5-5 (context-window variants are the same model); record the exact served id and generation
   parameters on every record.

## B. What you record

1. **One disposition per blinded unit**, exactly in the schema below (`DISPOSITION_SCHEMA`, id
   `p3b-s5a-disposition-v1`; the engine validates it and refuses anything else): `disposition` ANALYSED | DEFERRED |
   FAILED; for ANALYSED, each of the 14 classes `RECORDED` (the class is recorded at ≥ ANALYSED with evidence) |
   `NOT-RECORDED` | `UNDETERMINED`. **A class you omit is UNDETERMINED, never NOT-RECORDED.** DEFERRED and FAILED need
   a `reason` and are undetermined for every class. UNDETERMINED is handled by symmetric removal (plan §H.4). Use the
   vocabulary below and nothing else; any other structure is `UNMAPPED` research content (§9E.2 item 11).
2. **ISC routing rule (binding):** A claim that two or more members are states or versions of one object, or are the same object, is a sameness claim between members: recorded as EQUIVALENCE-CLASS (for P3a-judged pairs only as VERDICT-EVIDENCE-CONFLICT, RC-13), never as IDENTITY-STATE-CONFLATION.
3. **CROSS-OBJECT and CORPUS findings** are register records with the §9E mandatory content (checked by G-13). Their
   `control_comparison` cites `cell_id` (a list for CORPUS) and `results_sha256` of `audit-p3b/S5A-CELL-RESULTS.json`;
   the generator, class and outcome must match the cited cell(s) (for CORPUS: the §9E.2 item 8 derivation). Findings
   never alter object records or P3a verdicts.
4. **Claim scope (v1.7 §23 item 3b):** no finding may rest on a hub label's absence resolution; absence-based
   statements cover the non-hub labels only (claims A/B, never C).
5. **No STATUS in the passes' analysis step.** TESTED and STATUS follow §13.9: every CORPUS record in S5b, others in
   the separate test pass (plan §C.2). Research records are discovery artifacts, not theory components; nothing is
   canonicalized and no theory is written (§1D, G-LOG-0028).

## C. Vocabulary (binding; plan §G.1)

```json
[
 {
  "absence_based": false,
  "class": "STATE-MACHINE-CANDIDATE",
  "definition": "states plus transitions with stated triggers across members",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "ORDER-OR-LATTICE-CANDIDATE",
  "definition": "a stated preorder, partial order or lattice relation over members' elements",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "EQUIVALENCE-CLASS",
  "definition": "members stated to be the same concept or relation: a sameness claim between members",
  "identity_class": true
 },
 {
  "absence_based": false,
  "class": "COMPOSITIONAL-STRUCTURE",
  "definition": "members built from parts by a stated operation (product, sum, composition)",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "PROBABILISTIC-STRUCTURE",
  "definition": "a probability, measure or random-variable structure over members' elements",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "MEASUREMENT-SCALE",
  "definition": "a nominal, ordinal, interval or ratio scale claim for a quantity in the members",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "LOGICAL-ENTAILMENT",
  "definition": "a stated inference, entailment or implication chain between members' statements",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "DDD-BOUNDARY-CANDIDATE",
  "definition": "a bounded-context or aggregate boundary with stated ownership",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "IDENTITY-STATE-CONFLATION",
  "definition": "each member exhibits the object-scale pattern of §1D, §9D and §13.7: the object's identity is conflated with one of its state representations",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "SHARED-INVARIANT",
  "definition": "one invariant stated to hold across members",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "SHARED-TRANSITION",
  "definition": "the same transition type occurring in members",
  "identity_class": false
 },
 {
  "absence_based": true,
  "class": "MISSING-TRANSITION",
  "definition": "a transition implied by members' structure but absent (absence-based)",
  "identity_class": false
 },
 {
  "absence_based": true,
  "class": "MISSING-INVARIANT",
  "definition": "an invariant implied but absent (absence-based)",
  "identity_class": false
 },
 {
  "absence_based": false,
  "class": "DEPENDENCY-CHAIN",
  "definition": "members linked by stated dependencies into a chain or DAG",
  "identity_class": false
 }
]
```

## D. Dispositions schema (binding; exported by the engine)

```json
{
 "$schema": "https://json-schema.org/draft/2020-12/schema",
 "$id": "p3b-s5a-disposition-v1",
 "title": "S5a disposition of one blinded unit",
 "type": "object",
 "additionalProperties": false,
 "required": [
  "unit_key",
  "disposition"
 ],
 "properties": {
  "unit_key": {
   "type": "string",
   "pattern": "^U[0-9]{6}$",
   "description": "the unit_key of the blind file (P3B-CROSS-BLIND.jsonl)"
  },
  "disposition": {
   "enum": [
    "ANALYSED",
    "DEFERRED",
    "FAILED"
   ]
  },
  "reason": {
   "type": "string",
   "description": "required for DEFERRED and FAILED"
  },
  "classes": {
   "type": "object",
   "additionalProperties": false,
   "properties": {
    "STATE-MACHINE-CANDIDATE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "ORDER-OR-LATTICE-CANDIDATE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "EQUIVALENCE-CLASS": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "COMPOSITIONAL-STRUCTURE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "PROBABILISTIC-STRUCTURE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "MEASUREMENT-SCALE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "LOGICAL-ENTAILMENT": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "DDD-BOUNDARY-CANDIDATE": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "IDENTITY-STATE-CONFLATION": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "SHARED-INVARIANT": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "SHARED-TRANSITION": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "MISSING-TRANSITION": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "MISSING-INVARIANT": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    },
    "DEPENDENCY-CHAIN": {
     "enum": [
      "RECORDED",
      "NOT-RECORDED",
      "UNDETERMINED"
     ]
    }
   },
   "description": "per pre-registered class: RECORDED (at >= ANALYSED) | NOT-RECORDED | UNDETERMINED. A class missing here is UNDETERMINED, never NOT-RECORDED (G-LOG-0039)."
  }
 },
 "allOf": [
  {
   "if": {
    "properties": {
     "disposition": {
      "enum": [
       "DEFERRED",
       "FAILED"
      ]
     }
    }
   },
   "then": {
    "required": [
     "reason"
    ],
    "properties": {
     "classes": {
      "type": "object",
      "additionalProperties": {
       "const": "UNDETERMINED"
      }
     }
    }
   }
  }
 ],
 "x-semantics": [
  "a missing class is UNDETERMINED (UNDETERMINED is not 0)",
  "DEFERRED / FAILED: undetermined for every class",
  "unknown keys, dispositions, classes or values are refused, never coerced",
  "every blinded unit has exactly one disposition; duplicates and unknown unit_keys are refused",
  "UNMAPPED classes are recorded in the research register, not here"
 ]
}
```

---

# PROTOCOL TEXT (verbatim from the frozen core v1.7)

## 9D Research scales (D-34)

| Scale | Unit | Typical questions | When |
|---|---|---|---|
| **OBJECT** | one label's evidence and timeline | What does this object mean; how did its meaning change; does its type change; does it carry an invariant or a transition; is identity confused with state? | per label, S5 (§9.8, §9B) |
| **CROSS-OBJECT** | a set of labels | Do several objects share a transition structure? Are there equivalence classes, partial orders or dependency graphs? Do several objects obey the same invariant? Are two apparently different concepts isomorphic? Are bounded contexts emerging? Does A change when B changes; does B constrain C; does C measure A? | S5a / S6a (§9E) |
| **CORPUS** | the whole accepted reconstruction plus the register | Does a mathematical structure recur across domains? Is there a general state-transition algebra, a common epistemic structure, universal invariants, a general measure? Is there a recurring relation between evidence, determination, state and validation? | S5b (§9E) |

The reviewer's working names "R1/R2/R3" are **not** used, because `P3B-R1` and `-R2` already name P3b runs (§4, §5.4).
The scale is recorded in the register field `scale` (§13.6).

Corpus-scale findings are where the theory may emerge. **They are still hypotheses.** "A recurring partial-order
structure may underlie …" is a `THEORY-CANDIDATE`. "KnowledgeOS theory is a partial order" is canonicalization,
and forbidden (§1D).

The phases the reviewer named map onto the scales: **A** per-label chronological reconstruction = OBJECT;
**B** cross-label comparison and **C** cross-object mathematical/statistical/DDD analysis = CROSS-OBJECT;
**D** corpus-level candidate theory = CORPUS.

## 9E Cross-object and corpus-level research passes (D-35, corrected v1.4)

**Inputs.** Object records and timelines produced in S5, the research register, the P3a pairs (frozen, consumed),
`03-CONTRIBUTIONS.jsonl`, and whole-file reading of the admitted sources a candidate needs. **This is not a corpus
re-read** (D-04): whole files are read only to check a specific candidate. At pass start, the object records,
timelines and register are **snapshotted and hashed** (`P3B-PASS-SNAPSHOT.json`, §19.5). The pass reads only the
snapshot.

Whether the passes may use PROPOSED records or only ACCEPTED ones is **OMQ-17**. Whatever the answer, every finding
records the acceptance state of each participating record **at research time**. **Re-examination is transitive**
(RC-6): when any object record, timeline point or register record is corrected or rejected, every record listing it in
`derived_from_records[]`, directly or through a chain, is re-examined, and a new register line records the outcome.

**Step 1 — comparison candidates (mechanical)** → `P3B-CROSS-CANDIDATES.jsonl`. Sets of labels are proposed by the
generators in §9E.1. Each candidate records `generator`, `generator_version`, `parameters`, `defining_property`,
`inputs_ai_produced` (yes/no), its member labels, and matched controls (§9E.2). Candidates are hypotheses for
investigation, never findings.

**Step 2 — cross-object analysis (AI REVIEW, blinded).** Candidate sets and their matched controls reach the analyst
**interleaved and unlabelled** (§9E.2). For each set, compare timelines, states, transitions, invariants, dependencies,
terminology, types and structures through the §9A lenses. **Labels that share a P3a pair are not re-judged for
identity**: any sameness or identity claim between them is recorded only as `VERDICT-EVIDENCE-CONFLICT` (RC-13).

**Step 3 — corpus pass (AI REVIEW; after S5a).** Look across the CROSS-OBJECT findings and the register for
structures that **recur** across independent object sets (§9E.3). A CORPUS record states what the structure
**explains or predicts** that could be checked in the corpus (RC-13). **A recurring pattern is not a theory candidate
merely because it appears many times.**

**Mandatory content of every CROSS-OBJECT finding** (checked by G-13):
- `participating_objects[]`, each with its own source evidence (S-id + anchor), acceptance state at research time,
  and tier;
- `derived_from_records[]` (object records, timeline points, register records it rests on);
- `common_structure`, stated per §13.11 where it is a structure;
- `differences`: where participants do **not** fit the structure;
- `competing_explanation` (at least one);
- the disconfirmation record (§13.10);
- `falsification_condition`;
- `temporal_scope` (§14.4c);
- `generator_basis`: the generator that proposed the set, and whether the finding is **DESCRIPTIVE** (restates the
  generator's defining property, §9E.1) or **DISCOVERY**. The classification is **taken from the pre-registered
  (generator × structure class) mapping** in the H-15 pass plan, never decided after the reveal. Findings on control sets
  carry `generator_basis: CONTROL` (§9E.2 item 9);
- `control_comparison`: the pooled generator × structure-class comparison and its decision (§9E.2 items 5–7), or
  `NOT-APPLICABLE` for a DESCRIPTIVE finding;
- `research_status` (§13.9, §13.9a).

**Mandatory content of every CORPUS finding** (checked by G-13): everything above, plus:
- `participating_findings[]`: the CROSS-OBJECT records it aggregates (RC-6);
- `underlying_objects[]`: the union of their participants, resolvable to S-ids;
- `independent_occurrences`: the count and list under §9E.3, with excluded occurrences and reasons;
- `exceptions`: participant sets where the structure fails;
- `explanatory_content`: what it explains or predicts, and how that could be checked;
- `control_comparison`: **derived** from the pooled S5a comparisons for the structure class across the participating
  findings' generators (§9E.2 item 8), never generated anew and never inherited without that derivation.

**Linking, not duplicating.** A higher-scale record that restates a lower-scale record's structure links it in
`derived_from_records[]` and does not count as a separate occurrence.

**Discipline.** Disconfirmation (§13.10) and, for DISCOVERY findings, the control comparison are mandatory for every
CROSS-OBJECT or CORPUS HYPOTHESIS, STRUCTURE-CANDIDATE or THEORY-CANDIDATE. A finding with a Tier-X participant
carries `exposure: TIER-X` and cannot reach `SUPPORTED-IN-CORPUS` until H-02 is decided. Findings never alter object
records or P3a verdicts.

**Completion.** A pass is complete when every comparison candidate in the plan approved under **H-15** has been
dispositioned: `FINDING-RECORDED`, `NO-COMMON-STRUCTURE-FOUND` (with what was compared), or `DEFERRED` (with the
reason). "No structure found" is a legitimate, recorded result.

### 9E.1 Generator register — population, information, circularity, correction (RC-2)

Every generator is specified in Appendix A.6 (algorithm, parameters, version). Its **defining property** is the
property it selects on. **A finding whose structure is the generator's defining property is DESCRIPTIVE**: it restates
the selection, is recorded as such, and never counts as discovery or recurrence.

| Generator | Population | Information used | Defining property (→ DESCRIPTIVE if "found") | False-structure risk | Correction (v1.4–v1.6) |
|---|---|---|---|---|---|
| G-SHARED-GROUP | labels in a P2a group | `_derived.json` groups | shared group membership / similarity | CO-OCCURRENCE groups inflated by registry co-listing | CO-OCCURRENCE and STRING-SIMILARITY groups used only if every member has ≥ 1 PRIMARY row not from a file above the co-change cap (H-18); "equivalence class" among group members = DESCRIPTIVE; pairs already judged by P3a are used **only for non-identity structure classes** (the candidate space nearly coincides with P3a's: 1,982 vs 1,793 pairs) |
| G-DEPENDENCY | labels with P3b dependency edges | S5 object records (**AI-produced**) | edge existence / connectivity | "dependency graph" or "partial order" among edge-selected sets is tautological | graph shape = DESCRIPTIVE; discovery must concern a property **not** implied by the edges (for example a shared invariant or transition), tested against controls |
| G-NOTATION | labels sharing notation or alias | `_derived.json` notations/aliases | shared symbol | false-cognate short codes (KSME-22D: 6/15) | sameness never inferred from symbol; each member needs whole-file evidence of referent (§11.3) |
| G-COCHANGE | labels whose timelines change at the same source | S5 timelines (**AI-produced**) + `02-FILES.jsonl` | co-occurrence of change at one source | registry/census co-listing; synthesis restatement (also a hindsight route) | **only PRIMARY sources with degree ≤ cap (H-18)**; excluded sources listed per candidate |
| G-TIMELINE-SIM | labels with similar change sequences | S5 timelines (**AI-produced**) | sequence similarity ≥ θ | shared authoring sessions (mtime blocks) mimic structure | metric and θ fixed in Appendix A.6 and recorded; members from one mtime block flagged; controls matched on historical span |
| G-TYPE-SIM | labels with similar type signatures | contribution rows | normalized-signature similarity | notational convention mimics structure | metric fixed in Appendix A.6; controls matched on has-formal-rows |
| G-TOPIC / G-HYP (**SELF-DERIVED**) | labels sharing register topics, or appearing jointly in HYPOTHESIS/STRUCTURE records | the researcher's own register | the researcher's prior hypothesis | self-reinforcing loop into corpus recurrence | labelled `SELF-DERIVED`; may be used **to test** a hypothesis, **never counted** as an occurrence (§9E.3) |

Which generators run is **OMQ-18 / H-15**. Adding a generator requires this table's row (change control).

### 9E.2 Control design (RC-1)

Controls exist to detect "structure everywhere" artefacts, so they must be **comparable, blind and measured**:

1. **Eligibility.** Controls for a candidate from generator g are drawn from the **same eligible population** as g's
   candidates, **excluding only g's defining link** among members. They are not required to lack the other
   generators' links. Controls apply only to **DISCOVERY** claims, where the tested property is not g's defining
   property. They do not apply to DESCRIPTIVE findings.
2. **Matching.** Each control set is matched to its candidate on **exact arity** and on the matching variables fixed
   by **H-17**. Recommended: `row_count` band, `pair_count` band, provenance mix, historical-span band, and max
   source-file degree band of the members.
3. **Ratio and arity.** `r` controls per candidate and the candidate arities `k` (for example k = 2 and 3; transitivity
   claims need triples) are fixed by **H-17**. They are recorded per pass.
4. **Blinding (v1.5).** The analysis input interleaves candidates and controls in a seeded random order, **without**
   `generator`, candidate/control labels or defining property. Fields that directly encode a defining link (P2a
   `group_ids`, shared notation/alias lists, dependency-edge lists) are **stripped** from the blinded input. Where the
   evidence text itself still reveals the link (for example a shared symbol in a quote), the pass records
   `blinding_level: PARTIAL`, otherwise `FULL`. The phase report states the level per generator. A reveal script joins
   dispositions back after **all** sets in the pass are dispositioned (Appendix A.7). Disconfirmation and testing after
   the reveal are not blind, and the report says so.
5. **Pre-registered outcome and classes.** Before analysis, the H-15 pass plan fixes: (a) the **structure-class
   vocabulary** used for recording and pooling; (b) the **(generator × structure class) → DESCRIPTIVE/DISCOVERY
   mapping**; (c) the outcome: for each (generator × DISCOVERY class), **the proportion of sets whose analysis records
   that class at ≥ ANALYSED**, among candidates and among their matched controls.
6. **Decision rule (v1.6, W-C3; parameters under H-17).** For each pre-registered (generator × DISCOVERY class) cell
   of a pass:
   - **Unit of analysis (order of operations fixed in v1.6.1, C3).** Before any analysis, per generator:
     (1) budget sub-sample (A.6); (2) drop candidates marked NO-CONTROL-AVAILABLE (A.7); (3) order the remaining
     candidates by sorted member tuple, lexicographically, and greedily keep those sharing **no label** with a kept
     candidate. This is the **test family**, frozen before analysis. A.8 steps 1–3 are **not** applied here, because
     they depend on analysis outcomes and would be selection on the outcome. Overlapping k-subsets are never
     independent observations. Alternatively, if pre-registered, a label-cluster permutation test.
   - **Test.** The test named in the pass plan (recommended: one-sided Fisher exact on the independent family, or the
     permutation test).
   - **Multiplicity.** A correction pre-registered over **all pre-registered cells** of the pass, Holm (family-wise) or
     Benjamini–Hochberg (level q). Pre-registered cells not tested (UNDERPOWERED, PARTIAL) enter the denominator with p = 1. UNMAPPED classes are not
     pre-registered cells and never enter the denominator (v1.6.2, B4a).
     Only cells significant after correction can be DIFFERENTIATED.
   - **Minimum data.** `m` = the number of independent candidate sets in the cell's pool (after NO-CONTROL-AVAILABLE
     sets are dropped), and `x_min` = the minimum number of candidate sets showing the class. If either minimum is not
     met, the cell is `UNDERPOWERED` (not UNDIFFERENTIATED), reported with the **minimum detectable difference** at the
     observed sizes.
   - **Blinding.** A cell analysed under `blinding_level: PARTIAL` is **report-only**. It can be described, but it
     never becomes DIFFERENTIATED. **`blinding_level` is set by script, not by judgment (v1.6.1):** it is PARTIAL iff any
     stripped defining-link token (group id, shared notation or alias, edge endpoint pair) occurs in the blinded
     evidence text of any set from that generator; otherwise FULL. *Blinded evidence text* = the resolved text of
     every evidence pointer in the blinded input (the row `statement`, `type_signature` and the anchor quote), with
     tokens and text normalized and matched by the Appendix A.4 rules (NFKC; case-sensitive for single characters;
     boundary rules as in A.4) (v1.6.2, B4b). An **edge endpoint pair** token (G-DEPENDENCY) *occurs* iff a **single**
     resolved evidence item (one row statement, type signature or anchor quote) contains the A.4 terms of **both**
     endpoint labels. Each label's own name in its own evidence does not count (v1.6.3).
   - **Precedence** when several apply to a cell: PARTIAL, then UNDERPOWERED, then the test result.
   - **Outcomes:** `DIFFERENTIATED` · `UNDIFFERENTIATED` (enough data, correction not passed) · `UNDERPOWERED` ·
     `REPORT-ONLY-PARTIAL-BLIND` · `UNMAPPED` (item 11). Only DIFFERENTIATED satisfies §13.9a condition (4).
   - **UNDIFFERENTIATED and UNDERPOWERED are never evidence that a structure is absent** ("not found ≠ false").
   - **More frequent than controls is evidence of discrimination, not proof of a theory.**
7. **Reporting.** Every cell reports candidate and control counts, the two proportions, the difference, `m`, the
   number of NO-CONTROL-AVAILABLE candidates dropped, the number of controls drawn under relaxed matching,
   `blinding_level`, the test statistic, the raw and corrected p-values, and (if UNDERPOWERED) the minimum detectable
   difference.
8. **CORPUS baseline.** A CORPUS finding has exactly **one** pre-registered structure class. Its `control_comparison`
   is derived from the S5a cells of that class for the generators behind its participating findings. It is
   DIFFERENTIATED only if **at least one** contributing generator is DISCOVERY for the class, and **every** contributing
   DISCOVERY generator's cell is DIFFERENTIATED. S5b generates no new controls.
9. **Findings on control sets.** Analysis of a control set is recorded like any finding, with `generator_basis: CONTROL`.
   It feeds the control proportion, is never an occurrence (§9E.3), and never reaches SUPPORTED-IN-CORPUS.
10. **Persistence.** Seed, generator version, parameters, candidate list, control list, matching values, the class
   vocabulary and the DESCRIPTIVE/DISCOVERY mapping are written to `P3B-CROSS-CANDIDATES.jsonl` and the pass plan
   before analysis, and frozen.

11. **Unmapped classes and dual-role sets (v1.6).**
   - A structure class not in the pass's pre-registered vocabulary is recorded as `UNMAPPED`: it is research
     content, never an occurrence, and never DIFFERENTIATED. It may enter the vocabulary of the **next** pass plan.
     This is the one place where openness (§1E) is deliberately limited, so that classes cannot be invented after the
     outcome is seen.
   - A set proposed by several generators, or serving as a control for one generator and a candidate for another,
     appears **once** in the blinded file. Its reveal entry lists all roles. For each cell it counts in the role that
     cell defines. In A.8 it counts once, under the first generator in the fixed precedence order of the §9E.1 table.
   - Relaxed-match controls are pooled with exact ones, and their count is reported (item 7).

### 9E.3 Independence and recurrence at every scale (RC-2; v3.5 R9 carried upward)

v3.5 R9 ("duplicate ≠ independent evidence; repetition is not confirmation") applies at **every** scale. An
**independent occurrence** of a structure is a participant set that:
- has, **for every participant**, at least one PRIMARY source for that participant's role in the structure
  (SECONDARY-SYNTHESIS and PROVENANCE-UNRESOLVED sources do not create occurrences; they may corroborate one);
- shares **no** source with another counted occurrence, and no source that is a duplicate of one
  (`08-OVERLAP-REGISTER.jsonl`);
- shares **no** member label with another counted occurrence (overlapping candidate sets count once);
- was **not** proposed by a SELF-DERIVED generator;
- is not a DESCRIPTIVE finding for the structure in question, and not a finding on a control set.

A CORPUS record reports `independent_occurrences` with every excluded occurrence and the rule that excluded it.
**Recurrence claims use only independent occurrences.**

## 13.9a STATUS assignment rules (RC-3, new in v1.4)

A STATUS is assigned **only** by these rules. Anything not meeting them is `UNDETERMINED-FROM-CORPUS`.

| STATUS | Required |
|---|---|
| **SUPPORTED-IN-CORPUS** | (1) **A**: positive support from at least **N_support** independent PRIMARY sources (independence per §9E.3; **N_support is fixed by H-16**); for CROSS-OBJECT, **SUPPORTED-IN-CORPUS is not available at freeze** (known limitation L-1, §36): a single CROSS-OBJECT finding is at most UNDETERMINED-FROM-CORPUS, and recurrence is assessed only at CORPUS scale; for CORPUS, at least N_support independent occurrences (§9E.3); **and** (2) **B** and **D** performed at **census scope** (§13.10a: LEXICAL over the full Appendix A.4 corpus lists with `NEGATIVE-CENSUS`; STRUCTURAL-ENUMERATION over the structure's full X), on the population and temporal scope **frozen at TEST-DEFINED** and persisted in an earlier run (§13.10a), with no unresolved contradiction or counterexample (an item is *resolved* only by a recorded non-applicability check showing it does not bear on H as stated; such checks are audited, §21 item 6); **and** (3) **C**: at least one recorded item of corpus evidence that favours H over each listed competing hypothesis; **and** (4) for DISCOVERY findings, a pooled control comparison that is **DIFFERENTIATED** under the pre-registered rule (§9E.2 item 6), and for CORPUS findings the derived comparison (§9E.2 item 8). A comparison that is absent, unregistered, `UNDERPOWERED`, `UNMAPPED`, or made under `blinding_level: PARTIAL` does **not** satisfy this condition (§9E.2 item 6); **and** (5) no Tier-X participant before H-02; **and** (6) every item in the basis is `evidence_kind: CORPUS` |
| **REFUTED-IN-CORPUS** | a **verified** contradiction or counterexample from a PRIMARY source **within H's temporal scope** (§14.4c), quoted, with the check that it applies to H as stated (not to a variant) |
| **UNDETERMINED-FROM-CORPUS** | everything else, including: support below N_support; B or D not exhaustive; a competing hypothesis not discriminated; controls undifferentiated |
| **ROUTED-TO-GOVERNANCE** | a human-review routing (§17); never a truth value |

Explicit prohibitions:
- **Absence of contradiction is never confirmation.** An empty B, even exhaustive, satisfies only condition (2).
- **"Not found" is never "false".** Failing to find support gives UNDETERMINED, never REFUTED.
- **External theory never sets a corpus STATUS** (condition 6; §13.12).
- **Recurrence alone never supports.** Frequency counts only through independent occurrences, and still needs
  (2)–(4).

G-09 checks every STATUS against this table.

## 13.9b Evidence level of CORPUS theory candidates (human decision 2026-09-24; D-68)

Every CORPUS theory candidate carries `evidence_level`, **computed** from recorded results and never asserted:

| Level | Requires |
|---|---|
| `PATTERN-OBSERVED` | the record exists with its evidence (a pattern exists) |
| `RECURRENT` | ≥ N_support independent occurrences (§9E.3) |
| `DISCRIMINATING` | RECURRENT, and the derived control comparison is DIFFERENTIATED (§9E.2 item 8) |
| `PREDICTIVE` | DISCRIMINATING, and ≥ 1 hold-out prediction scored PREDICTION-CONFIRMED with none PREDICTION-FAILED (only once the H-19 addendum is approved, §9F) |
| `THEORY-SUPPORTED` | PREDICTIVE, and STATUS = SUPPORTED-IN-CORPUS (§13.9a) |

Rules:
- **`claim_type`** ∈ {`EMPIRICAL-GENERALIZATION`, `HISTORICAL`, `DEFINITIONAL`, `STRUCTURAL`, `EXPLANATORY`} (closed) is
  declared at TEST-DEFINED and frozen with the test plan (it is part of `test_plan_sha256`, §13.10a). Declaring a
  non-empirical type only lowers the attainable level, so it cannot be used to evade a test (v1.6.3).
- Levels are cumulative. A candidate whose `claim_type` is not EMPIRICAL-GENERALIZATION can reach at most
  DISCRIMINATING in P3b, because it cannot be predictive.
- **No level, including THEORY-SUPPORTED, means "verified" or canonical.** Canonical adoption remains v3.5 P5/P6,
  through recorded human acts (§1D).
- Pattern confirmation (RECURRENT) and predictive confirmation (PREDICTIVE) are always reported separately.

## 13.10 Disconfirmation discipline (D-38)

Do not search only for confirmation. For every serious hypothesis H (every HYPOTHESIS, STRUCTURE-CANDIDATE and
THEORY-CANDIDATE):

| Search | Question | Recorded as |
|---|---|---|
| **A — support** | What evidence supports H? | `supporting_evidence[]` |
| **B — contradiction** | What evidence contradicts H? | `contradicting_evidence[]`, **with the search record** (terms, scope, negative label) even when empty |
| **C — discrimination** | What evidence would distinguish H from a competing H2? Where would it be found? | `competing_hypotheses[]` + `discriminating_evidence` |
| **D — counterexample** | Can a counterexample destroy H? Where would one be? | `counterexample_search` (scope, result, negative label) |

**Every operation A–D records** (RC-8, v1.4):
`population` (the enumerated set of labels/objects/sources, or the corpus scope with its file list reference) ·
`method` ∈ {`LEXICAL` (stage-1-style search, Appendix A.4), `STRUCTURAL-ENUMERATION` (check the claimed property on
every tuple of a finite enumerated X), `WHOLE-FILE-READING` (read the listed files whole)} · `completeness` ∈
{`EXHAUSTIVE`, `SAMPLED` (with seed and size)} · `termination_bound` (the size of the population or tuple space; for
STRUCTURAL-ENUMERATION |X|ᵏ for a k-ary property) · `result` · `negative_label` where nothing was found
(NEGATIVE-BOUNDED | NEGATIVE-CENSUS). A counterexample search over a finite X is exhaustive enumeration, which
terminates. A lexical search is bounded by the corpus file list. **An unbounded search is not a valid operation.**

At `TEST-DEFINED`, B–D are **planned** (what, where). At `TESTED`, A–D are **performed**. A hypothesis with an empty
B that has no search record is **confirmation-only** and fails G-09. Elegant patterns found through selective
reading are the specific failure this rule exists to catch.

## 13.10a Pre-registration and freezing of tests (V-C1 v1.5; corrected v1.6, W-C2)

At `TEST-DEFINED` the record fixes, and hashes as `test_plan_sha256`, the **test plan**:
- the hypothesis statement, `claim_type` (§13.9b) and `competing_hypotheses[]`;
- for A–D: population, method, **search terms**;
- `temporal_scope` (computed, §14.4c);
- for structural claims, X **as a mechanical definition**;
- the decision rule for STATUS (§13.9a);
- the stopping rule (termination bound).

**After TEST-DEFINED none of these may change in that record.**

**Enforced ordering (v1.6).** The TEST-DEFINED record is persisted in an **earlier run** than any TESTED record for the
same hypothesis: a different `run_id`, with the plan hash written into that run's `P3B-INPUT-MANIFEST.json` entry
before the testing run starts. G-09 fails a TESTED record whose plan was not persisted in an earlier run. A plan written
in the same run as its test is not pre-registered.

**Search terms are not free.** LEXICAL B and D use **at least** the mechanical Appendix A.4 terms of **every
participant label** (label, notations, aliases), plus any terms the researcher adds. Dropping a mechanical term is not
permitted.

**X is not a hand list.** For STRUCTURAL-ENUMERATION, X is defined as either (a) the members of a generator candidate
set, or (b) a predicate over labels or records that a script evaluates on the snapshot (for example "all labels in
group G0174", "all labels with at least two rows whose `type_signature` is non-empty"). The script's output is X. A list of ids written by
hand is not a valid X. **A predicate may reference only fields of frozen artifacts or of generator outputs persisted before the plan**:
`02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/_derived.json`, `31-RECONCILIATION-PAIRS.jsonl`, and
non-SELF-DERIVED generator candidates. It may **not** reference register fields (topics, `related_labels`, record ids) or
any other researcher-written or agent-written field (including P3b timelines), and contains **at most one id-valued
atom**. An *id-valued atom* is a comparison of a field with **one specific group id or one specific non-SELF-DERIVED
generator candidate id**. Comparisons with label ids, source ids or pair ids are **never** permitted. Regex, substring or prefix matching on identifier-like fields (`path`, `working_label`, `source_id`,
`pair_id`, `group_id`) is not permitted at all (v1.6.3). It never lists, includes or excludes literal label or source ids. Candidates of SELF-DERIVED generators
(G-TOPIC, G-HYP) are not valid X (v1.6.2, B5).

**Census scope for B and D.** A LEXICAL B or D covers the full Appendix A.4 corpus lists and records `NEGATIVE-CENSUS`
when empty. A narrower search is `NEGATIVE-BOUNDED` and cannot satisfy §13.9a condition (2).

**Narrowing is a new record, flagged.** A narrower population, scope, term set or X after seeing results creates a
**new** hypothesis record. It cites the original and every counterexample or contradiction its narrowing excludes, and
carries `POST-HOC-NARROWED`. The original keeps its result. A POST-HOC-NARROWED record can reach SUPPORTED-IN-CORPUS
only on support evidence that is independent of the sources cited in the original record's A–D results.

## 13.11 Formal standard for structure candidates (D-39, corrected v1.4)

"There appears to be a lattice" is not enough for serious mathematical research, and **resemblance is never a
structure claim**. A `STRUCTURE-CANDIDATE` states its structure formally, **where applicable**, for its lens. Any field
it cannot fill is written as `NOT-DETERMINED`, never omitted, because the unknowns are part of the finding.

**Relation source (RC-7a).** Every relation or operation carries `relation_source`:
- `CORPUS-STATED [S-ids]`: a source defines or asserts the relation;
- `RESEARCHER-CONSTRUCTED`: the researcher defines the relation from the data (its definition is written out).

Only `CORPUS-STATED` may be described as "the corpus defines …". A `RESEARCHER-CONSTRUCTED` structure is always
described as "a structure the researcher constructed over …" (hindsight test T-H1).

**Property status (RC-7b).** Each claimed property carries exactly one of:
`STATED-BY-SOURCE [S-ids]` · `VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n, over the finite X)` ·
`INSTANCES-CONSISTENT (m of n checked; SAMPLED)` · `NOT-DETERMINED` · `VIOLATED [S-ids / tuple]`.
A universal property is never "observed". It is stated by a source, verified on a finite enumeration, or open.

**Weakest-structure rule (RC-7c).** The candidate is **named by the weakest structure its STATED/VERIFIED properties
establish**. Example: reflexive + transitive verified, antisymmetry NOT-DETERMINED → *preorder*, not partial order;
partial order with joins and meets NOT-DETERMINED → *partial order*, not lattice. Stronger structures appear only in
`competing_structures[]` (RC-7d), with the properties that would have to hold.

| Lens | Required specification |
|---|---|
| **Mathematical** | underlying set X and its elements (as corpus objects) · relation(s)/operation(s) with `relation_source` and definition · each claimed property with its RC-7b status (for example reflexive, antisymmetric, transitive, closure, identity, associativity, commutativity, joins/meets, monotonicity, fixed points) · the name by the weakest-structure rule · `competing_structures[]` · invariants · counterexamples · mapping to corpus objects · evidence for and against the mapping |
| **Statistical** | population · unit of observation · variables and measurement definitions · sampling or selection mechanism · claimed dependence or distribution · missingness · the test that would assess it, and whether the corpus can support it |
| **DDD / domain** | candidate bounded context and its language · aggregate root, entities, value objects · invariants and who enforces them · commands, events, policies · ownership and authority · boundary evidence (terminology shifts, meaning changes) · counter-evidence · `relation_source` for each boundary claim |
| **Logic / theory** | terms and definitions · axioms or premises (with `relation_source`) · the inference claimed · consistency concerns (contradictions, circularity) · necessary vs sufficient conditions · counterexamples |

Example shape (mathematical):
```
Candidate  (X, ≤)  — named: PREORDER (weakest-structure rule)
X          = { … corpus objects … }
≤          relation_source: RESEARCHER-CONSTRUCTED; defined as: …
Properties reflexive  VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           transitive VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           antisymmetric NOT-DETERMINED
Competing  partial order (needs antisymmetry) · lattice (needs antisymmetry + joins + meets)
Temporal   ACROSS-TIME (§14.4c)
Status     UNDETERMINED-FROM-CORPUS (§13.9a)
```

## 14.4c Temporal scope of structures (RC-4; corrected v1.5, V-C2; corrected v1.6, W-C1)

§1C separates research time from historical time for **statements**. Structures need the same protection. Every
HYPOTHESIS and STRUCTURE-CANDIDATE, and every CROSS-OBJECT/CORPUS finding, carries `temporal_scope`, **computed
mechanically** (Appendix A.10), never chosen by the researcher.

**Normative definition: Appendix A.10 (v1.6.1).** This section explains; A.10 defines. Where they differ, A.10
governs. That is how v1.6.1 removes the section/algorithm drift found in two review rounds.

**Dated position (v1.6; aligned with A.10 in v1.6.1).** Temporal scope uses **dates only**, never `source_id` order
(v3.5 R1: ingestion order is not argument order). A timeline point has a **dated position** iff its source's record in
`02-FILES.jsonl` has `best_historical_date_basis = EXPLICIT`, `order_evidence` other than exactly `SOURCE_ID`, and
`mtime_block` not matching `^BULK-`, **and** the timeline point carries `date_applies_to_file: CONFIRMED`, the agent's
recorded §14.2 rule-2 confirmation. A point without that confirmation is undated. Measured: 916 files meet the file-level
conditions. The position is that
calendar date (day granularity). A source with an MTIME basis, or SOURCE_ID-only order evidence, or in a block
(`mtime_block` matching `^BULK-`), has **no dated position**. Measured: 1,167 of 2,779 files have an EXPLICIT basis;
1,612 are MTIME-only. MTIME is excluded because file times were rewritten during corpus relocation. A STEP-NUMBER
orders sources only within its own series, and is not used for cross-series temporal scope.

**Definitions.**
- `birth(p)` = the dated position of participant p's earliest timeline point that has one. If p has **no** dated
  point, p is undated.
- `end(p)` = the **minimum** over all dated RETRACTS points of p and all dated sources in `superseded_by_sources[]`.
  Otherwise +∞. **If any RETRACTS point or any source in `superseded_by_sources[]` is undated, the record is
  UNORDERED** (never +∞): an undated ending could be earlier than every dated one (v1.6.2, B3).
- `evidence(R)` = the dated positions of every source cited as supporting the relation.

| Value | Condition (computed) | How it may be described |
|---|---|---|
| `UNORDERED` | any participant is undated, or any relation-evidence source has no dated position | no temporal claim at all |
| `WITHIN-SLICE [t]` | with t* = the latest of all `birth(p)` and all `evidence(R)`: every `end(p)` is **strictly later** than t* (a same-day end counts as not alive). Recorded t = t* | "at [t], the corpus shows …"; the structure coexisted historically |
| `ACROSS-TIME` | otherwise | "**across** [periods], the researcher constructed …". **Never** "the corpus had …" at any historical point |

Rules:
1. `WITHIN-SLICE` means **coexistence at a point**, including the relation's own evidence. **v1.6 deletes the v1.5
   clause "or all relation evidence in one source"**, which let a later synthesis document attribute a relation to
   an earlier point (W-C1).
2. An ACROSS-TIME structure is a **research-time construct**. It may never be described, in any field or report, as
   having existed at a historical point (hindsight test T-H6).
3. **REFUTED within scope.** For WITHIN-SLICE [t], a counterexample must come from a source with a dated position at
   or before t, or from a participant state alive at t.
   **Support within scope (v1.6.1, C2).** For WITHIN-SLICE [t], items of A (support) and C (discrimination) count toward
   §13.9a **only if their dated position is ≤ t**. Support from later or undated sources requires a **new** record,
   whose scope is recomputed, so that later evidence can never back "at [t], the corpus shows …". The rest of this rule
   continues: For ACROSS-TIME, any PRIMARY counterexample among the
   participants' timelines counts. UNORDERED hypotheses can be refuted only by a counterexample that does not depend
   on order.
4. `temporal_scope` is computed at TEST-DEFINED **on the snapshot hashed then** (§19.5), and frozen with the test plan
   (§13.10a). G-09 recomputes it on **that** snapshot. A later timeline correction does not alter the frozen value; it
   triggers transitive re-examination (§9E), which creates a new record.
5. G-09 and G-13 check the field, and flag historical-point wording ("at S####", "the corpus had", "in August …") on
   ACROSS-TIME and UNORDERED records for audit.

# 20. QUALITY GATES

| Gate | Checks | Mechanism |
|---|---|---|
| G-01 Corpus integrity | every cited S-id ∈ `02-FILES.jsonl`; no F-id; no firewalled file quoted | script |
| G-02 Identity integrity | exactly the assigned labels, each once; no unknown label; no renamed or merged label | script |
| G-03 Duplicate handling | a duplicate file (`08-OVERLAP-REGISTER.jsonl`) is never counted as independent corroboration (v3.5 R9) | script |
| G-04 Evidence completeness | every status meets §11.2 minimum evidence; every absence has a search record and negative label | script |
| G-05 Relationship classification | closed lists only (existing verifier's enums); semantic_status satisfies D1–D5 (§12.2.2) and, once decided, the H-11 choices; Tier Z records have `semantic_status: null` + note; `pair_breakdown` matches the consumed pairs exactly | script |
| G-06 Semantic interpretation | blind audit sample (§21) — disagreements individually dispositioned | AI audit + human |
| G-07 Provenance | all §18 mandatory fields present; contract hash matches | script |
| G-08 Hindsight | no birth, FOUND or CONTESTED rests only on SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or Track-B-over-Track-A sources; anti-projection answer present | script + audit |
| G-09 Register non-interference and status integrity | no research record altered a status or verdict; no record set to a governance outcome; every HYPOTHESIS/STRUCTURE-CANDIDATE/THEORY-CANDIDATE has a falsification condition, a disconfirmation plan, and `temporal_scope`; every A–D operation records population, method, completeness, termination bound and result (§13.10); **every STATUS satisfies §13.9a** (no confirmation by absence; no REFUTED from not-found; no EXTERNAL-THEORY item in a STATUS basis; SUPPORTED only with a DIFFERENTIATED comparison); every STRUCTURE-CANDIDATE has `relation_source`, RC-7b property statuses, a weakest-structure name and `competing_structures[]` (§13.11); every GAP has a §13.4 status; every SUGGESTION/DEFICIENCY has `execution_impact` ; `test_plan_sha256` unchanged between TEST-DEFINED and TESTED (§13.10a); `evidence_level` and `claim_type` present, and `evidence_level` equal to its recomputation from recorded results (§13.9b); `temporal_scope` equals its recomputation on the snapshot hashed at TEST-DEFINED (§14.4c rule 4); `population_basis` present on every search record and STATUS (§3.6) | **script** for presence, structure and recomputable fields; **audit** (§21 item 6) for the judgment conditions: §13.9a (3) "favours H", a "verified" counterexample, and fidelity to the DESCRIPTIVE/DISCOVERY mapping |
| G-12 Layer and time separation | no layer-B/C content (register ids, `research_time`, RESEARCH-OBSERVATION/SUGGESTION/HYPOTHESIS/THEORY-CANDIDATE text) in a layer-A field; no timeline point cites a register record as basis; every register record has both `historical_anchor` and `research_time`; `PROPOSED:*` topics each have a definition | script + audit (§21 item 5) |
| G-13 Cross-scale findings | every CROSS-OBJECT/CORPUS record carries the §9E mandatory content for its scale (participants with their own evidence, acceptance state and tier; `derived_from_records`; for CORPUS `participating_findings`, `underlying_objects`, `independent_occurrences` with exclusions, `exceptions`, `explanatory_content`); `generator_basis` DESCRIPTIVE/DISCOVERY; control comparison for DISCOVERY; recurrence counts use only §9E.3 independent occurrences (no SELF-DERIVED, no non-PRIMARY, no overlapping sets); `temporal_scope` present and historical-point wording flagged on ACROSS-TIME records; Tier-X participants → `exposure: TIER-X`; `P3B-CROSS-CANDIDATES.jsonl` and `P3B-PASS-SNAPSHOT.json` hashes match the pass record; every candidate in the approved plan dispositioned ; **no DESCRIPTIVE or CONTROL set counted as an occurrence** (Appendix A.8); control comparisons pooled per pre-registered generator × class, with the decision per §9E.2 item 6; `blinding_level` recorded | script |
| G-10 Reproducibility | re-running the scripts on the recorded input manifest reproduces the mechanical fields byte-for-byte | script |
| G-11 Acceptance integrity | every ledger record is `record_status: PROPOSED`; every record in `32-RECONCILIATION-OBJECTS.jsonl` has resolvable `acceptance_ref`, `verifier_ref`, `audit_ref`; no downstream artifact cites a P3b status lacking them; no `P3B-R1` record is cited as a P3b result | script |

**Batch fails** if any of G-01…G-05 or G-07…G-12 fails (G-13 applies to the S5a/S5b/S6a passes, which fail and re-run on the same terms), or if G-06 finds a disagreement dispositioned as a
protocol violation (not a judgment call).
**On failure:** the batch output is kept (never deleted), marked `FAILED` in the manifest with the failing gate,
and a new run (`OB####-R2.2`) is planned with the cause recorded. No partial acceptance of a failed batch.
**A batch can be accepted** when all gates pass and H-06 is recorded.

---
