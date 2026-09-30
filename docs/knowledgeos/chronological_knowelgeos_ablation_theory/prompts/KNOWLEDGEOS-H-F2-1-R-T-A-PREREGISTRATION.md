# H-F2-1-R — TEST T-A PRE-REGISTRATION (source-fidelity and locality falsification)

| | |
|---|---|
| **Kind** | pre-registration, written **before any source reading**. ⚠ authority: generated. **Status: REVIEW CANDIDATE r3, content-final for HD-1.** HD-S is resolved (§3.0). HD-1 reviews the exact sha256 recorded in F-LOG-0030. **Not frozen:** it becomes binding only when the human reviews it and it is committed as frozen, **before** the release that authorizes reading |
| **Revisions** | r0 `bc4556376` (draft) → r1 `e735aa336` → r2 `e027f1214` → **r3** (2026-09-26, HD-S closure: S-U + K-S + O-1 + SP-S). All changes are listed in §9. No question, no prediction and no §3.1 outcome definition was changed |
| **Commission** | human, 2026-09-26: "H-F2-1-R SCIENTIFIC CLOSURE PASS" §4–§12, plus *"Continue … as senior researcher, mathematician, statistician, DDD architect, expert of computer logic … also use your expertise in machine learning techniques"* |
| **Candidate under test** | H-F2-1-R (`prompts/KNOWLEDGEOS-H-F2-1-FORMAL-LOGIC-MINIMALITY-ATTACK.md` §14). **Status: F2-FORMAL-CANDIDATE / EMPIRICALLY UNVALIDATED** |
| **State of reading** | ⛔ **no source read.** Paths below were located from **registry metadata and file names only** (`FILE-REGISTRY.jsonl`, `git ls-files`) |
| **ORIGIN / tags** | [F] fact · [D] derived · [O] open · [E] expert |

---

## 1. What T-A is, and what it is not

| | |
|---|---|
| **T-A is** | a **source-fidelity test** (do the sources that H-F2-1-R was formed from actually support its locality axioms?) plus a **falsification search** in those sources |
| **T-A is not** | an out-of-sample test. H-F2-1a was formed from F0018's recorded conclusions, so agreement with F0018 is **fidelity (ARCH RA-15 A/B)**, not corroboration (RA-15 D) |
| **Partial exception** | **F-A6.** The bar's governing source (ES-006) and its revision history were **not** used to form H-F2-1-R. For A6 specifically, T-A is genuinely **out-of-sample** |
| **Out-of-sample proper** | T-B (other files); not registered here |

---

## 2. Material required, and its governance status

This list is **release preparation only. No release is requested here.**

| # | Material | Path | In the canonical registry? | Why needed | Governance prerequisite |
|---|---|---|---|---|---|
| M-1 | F0018 | `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` | **yes** (F0018) | the P-1…P-10 mechanism inventory, and the four kinds + provenance (A2e, A3g, A4, A5, H-6) | a corpus-read release. This is a **re-READ** of an already-read canonical file, under a named obligation (S2 §4.3b) |
| M-2 | authorities schema | `docs/knowledge/schema/authorities.yaml` | **no** | D4 premises (ii)/(iii); the attestation alternative; g ≡ s | a release **plus a corpus-scope decision** (S2 OQ-6: *"governance, not protocol"*) and a **version rule** (§2.1) |
| M-3 | statuses schema | `docs/knowledge/schema/statuses.yaml` | **no** | the status lifecycle vs standing; g ≡ s; H-6 | as M-2 |
| M-4 | ES-006 | `engineering/governance/ES-006-Engineering-Knowledge-Governance.md` + **its git revision history** | **no** | **F-A6 (primary):** the bar definition; whether amendments apply to pending items; F-A0 (bar monotonicity) | as M-2; the revision history is repository metadata |
| M-5 *(optional)* | F0014, F0016, F0017 | from `FILE-REGISTRY.jsonl` | **yes** | T-0014's other three sources (attestation; D4) | a corpus-read release |

**Minimum set: M-1 + M-4 for the dynamics core H-F2-1a; add M-2 + M-3 for H-F2-1b and the open questions.**

### 2.1 Version rule (pre-registered)

The repository files M-2…M-4 are **live artifacts**. Reading today's version to test a 2026-08-02 claim would be temporal contamination (MP §29).

**Rule:**
- read each file **at the last commit on or before F0018's `historical_sequence_date`** (git object read by commit, not the working tree);
- **separately** record the current version and the diff;
- for M-4, the **full revision history** is the object of F-A6.

If no commit exists on or before that date, record `NOT_AVAILABLE_AT_DATE`, and the item becomes **AMBIGUOUS** for fidelity. *(r2: this sentence is **moot**, because every item resolves (table below). It is superseded by §5.0 rule 0 (NOT_RUN). AMBIGUOUS is reserved for passages with ≥ 2 readings (§3.1); an unavailable file is not a passage.)*

**r2: boundary and M-1 rule.**
- "On or before" means **committer date ≤ 2026-08-02T23:59:59+02:00**.
- The result is boundary-robust: no commit touches M-2…M-4 between 2026-07-27 and 2026-08-04.
- **M-1 is exempt from the date rule.** F0018 is a canonical corpus file, and its first commit (2026-08-04) is **after** its own commission date (2026-08-02). M-1 is read as the **manifest-pinned object**: the sha256 in `CORPUS-MANIFEST.jsonl`, the same object the reconstruction read.

**r2: resolved versions (git metadata and byte hashes only; no content read, no diff viewed; 2026-09-26):**

| Item | Object to read | Commits touching the path | Post-date commits | Working tree |
|---|---|---|---|---|
| M-1 F0018 | commit `d61bf5e84`, blob `71edaebc`; sha256 `b685f599…7bc` = manifest pin | 1 | n/a (manifest-pinned) | unmodified; blob at `d61bf5e84` = blob at HEAD |
| M-2 authorities.yaml | commit `faa8d61e2` (2026-06-27) | 1 | **0** (historical = current) | unmodified |
| M-3 statuses.yaml | commit `faa8d61e2` (2026-06-27) | 1 | **0** | unmodified |
| M-4 ES-006 | snapshot at `668cc7b22` (2026-07-26); **history = 7 commits, `aee484e9c` (2026-07-11) … `668cc7b22`** | 7 | **0** | unmodified |

**Consequence for F-A6 (a fact, pre-registered):**
- ES-006's entire recorded revision history **precedes** F0018.
- F-A6 can therefore test whether ES-006 **states** how amendments affect pending items, and whether the pre-F0018 revisions **did** alter the bar.
- It cannot observe later amendments. None exist.

---

## 3. Pre-registered falsification questions

**Each question is a universal locality claim.** One DIRECT COUNTEREXAMPLE refutes the axiom *for that mechanism*. An absence of counterexamples does not establish the axiom.

> **r3: the r2 scope flag is resolved by HD-S (§3.0).** The sentence above is read through §3.0. "For that mechanism" names **where** the counterexample was found; it does not restrict the scope, which is **S-U**.

### 3.0 Operation typing and scope (HD-S, r3)

**HD-S (human input, 2026-09-26):**
- first *"S-U + K-A + SP-S for all six"*;
- then *"K-S + O-1, lexicon as proposed"*, with the instruction to follow the accompanying prompt, which makes the lexicon **advisory only**.

This section implements that final instruction. Sources: `prompts/KNOWLEDGEOS-T-A-OPERATION-TYPING-METHODOLOGY-REVIEW.md`; F-LOG-0029, F-LOG-0030.

**(a) Operation and effect are separate objects.** Every record carries an `operation` block and an `effect` block (§4). The test order is fixed:

```text
operation → kind (K-S) → axiom applicability → source-described effect → counterexample test
```

The effect is never read to decide the kind.

**(b) Scope S-U.**
- **A2e, A3g, A4, A5e, A5g and A3m** are universal over all source-described operations whose kind is established by the K-S procedure.
- **A6 and A0** are universal over all source-described operations, typed or not, because they are kind-free.
- One counterexample refutes the axiom **as stated**. There is no in-test weakening; a narrower axiom can only re-enter later as a new registration through the §5.1 re-check rule.

**(c) K-S procedure** (deterministic order; the first applicable step decides):
1. **Source-declared type.** If the source explicitly declares the operation's type, preserve and record the declaration (`typing_basis: SOURCE_DECLARED`).
2. **Action semantics.** Otherwise type the operation from the **source-described action**: what the source says is done, not what results. Record `ACTION_SEMANTICS` with a written `typing_justification`.
3. **Actor/authority** is recorded as context only. It is **never decisive**.
4. **Object** is recorded as context only. **O-1:** the object never decides the kind when it is evidential position, grant, standing or bar. *"The board rules that X is Replicated"* is a ruling, so GOV, whatever its object.
5. The **effect** on evidential position, grant, standing, bar or promotion is **never** a typing criterion.
6. If the operation cannot be typed without interpreting its effect on H-F2-1, it is **UNKNOWN** (`typing_basis: UNKNOWN`), with an `ambiguity_reason`.
7. The original wording is always preserved.

**COMP** means one source-described indivisible act whose action semantics span two kinds. This is the direct F-A4 test.

**(d) Splitting, SP-S.**
- Split only where the source itself describes distinct operations or sequential steps. Quote the basis (`split_basis: SOURCE_EXPLICIT`, `split_quote`).
- Never split to rescue an axiom. No reader-created splitting.
- One indivisible act stays one operation.

**(e) UNKNOWN is an epistemic status, not a sixth kind.** No new formal state; no three-valued logic. For an UNKNOWN operation on a kind-specific test:
1. enumerate the semantically admissible kinds (`admissible_kinds`, ≥ 2);
2. record the outcome under each kind (`outcome_by_kind`: DIRECT_SUPPORT | DIRECT_COUNTEREXAMPLE | NOT_BEARING);
3. if all outcomes are equal, that common result is the classification (NOT_BEARING → NOT_EVIDENCED);
4. if they differ, the classification is **AMBIGUOUS**, with `competing_classification` = the most adverse outcome (DIRECT_COUNTEREXAMPLE if any). By §5.0 rule 2 this gives INCONCLUSIVE plus a reconstruction obligation;
5. UNKNOWN is never converted directly into support or a counterexample.

On the kind-free tests (F-A6, F-A0), UNKNOWN needs no enumeration.

**(f) The lexicon is ADVISORY only.** It was written 2026-09-26 from the axiom semantics alone, before any reading (typing review §D-3). It suggests a **candidate** kind (`lexicon_candidate`), which the §(c) procedure then adjudicates in context. It is **never** a mapping from verb to kind, and it is not a `typing_basis`.

| Candidate | Verbs |
|---|---|
| GOV | rule, decide, approve, ratify, adopt, authorize, grant, promote, amend (a rule) |
| EVID | observe, measure, test, replicate, verify-by-observation |
| EVIDREF | refute, falsify, fail to replicate, contradict-by-observation |
| WORK | execute, process, implement, schedule, elapse, expire |
| context-dependent (no default) | confirm, validate, accept, record, certify, review |

Example of context-dependence:
- "the committee *confirms* the ruling" is a GOV candidate;
- "the second run *confirms* the measurement" is an EVID candidate;
- only §(c) decides.

**(g) Counterexample (procedural).** A record is a DIRECT_COUNTEREXAMPLE only if:
1. the source describes an operation;
2. its kind was established by the frozen §(c) procedure (any kind, for A6/A0);
3. the typing was recorded **before** the effect was evaluated;
4. any splitting follows SP-S;
5. the source **explicitly describes** the transition on the constrained component;
6. that transition violates the axiom.

No reader is required to prove that "no alternative interpretation exists". A genuinely admissible alternative is recorded through AMBIGUOUS (§(e), §3.1).

The kind/component pairs are fixed:

| Axiom | Kind | Constrained component |
|---|---|---|
| A2e | GOV | evidential |
| A3g | EVID/EVIDREF | grant |
| A4 | COMP | two or more of evidential/grant/standing |
| A5e | WORK | evidential |
| A5g | WORK | grant |
| A3m | EVID/EVIDREF | evidential |
| A6 | any | bar |
| A0 | any | bar |

**(h) Coverage** is reported per test: `N_typed` and `N_unknown`. These are counts only.

**(i) H-1′ (typing totality).** D3 and D3+ are asserted for trajectories every step of which is typed. D1, D2, D5 and D6 do not depend on it; A0, A1 and A6 are kind-free. This is a declarative note; the proofs are unchanged (attack document, appended note).

| # | Question | Axiom | Priority |
|---|---|---|---|
| **F-A2e** | Can a governance or ruling operation **directly** change evidential position? | A2e | core |
| **F-A3g** | Can an evidential operation **directly** create, establish or confer the grant? | A3g | core |
| **F-A4** | Does any source model a composite operation as **one atomic** transition that changes both standing/grant and evidential position? | A4 | core |
| **F-A5e / F-A5g** | Can work, time, expiry, processing or execution **directly** change evidential position (A5e) or the grant (A5g)? | A5e, A5g | core |
| **F-A6** | Can a change of canon, bar or rule alter the qualification of an **already-existing or pending** object **without a new evidential event**? | A6 | **PRIMARY** |
| **F-A0 / F-A3m** | Does any source describe promotion **through refutation**, or loss of qualification **through additional evidence**? Is any recorded bar **not upward-closed**? | A0, A3m | extension |
| **Q-H6** | Is promotion described as a **state** (qualification holds) or as a **governance event** (an act of promoting)? Or both? | H-6 (open) | interpretive |
| **Q-GS** | Is there grant without authoritative standing, authoritative standing without grant, or a separate lifecycle or provenance for each? | g ≡ s (open) | interpretive |
| **Q-D4** | Is `authority` single-valued with range P ⊔ S, or does another field (e.g. attestation) carry the second coordinate? | D4 premises (ii)/(iii) | H-F2-1b |

### 3.1 Operational definitions of the four outcomes

These apply to every question.

| Outcome | Definition |
|---|---|
| **DIRECT SUPPORT** | a passage **states** the locality property, or **describes a mechanism** whose stated effect satisfies it, in the source's own words. Example for A2e: *"no ruling makes an observation Replicated"*-type wording |
| **DIRECT COUNTEREXAMPLE** | a passage **describes** a mechanism whose **stated effect** violates the property. Example for A6: *"the new threshold applies to all pending items"*. **The source must describe the effect**, not merely use different wording |
| **AMBIGUOUS** | a passage bears on the property, but admits ≥ 2 readings with different verdicts. **Both readings are recorded** |
| **NOT EVIDENCED** | a complete reading of the authorized material finds no passage bearing on the property |

**Non-inference rules:**
- NOT EVIDENCED ≠ support.
- No counterexample ≠ true.
- Different terminology ≠ counterexample.
- One source's silence ≠ another source's claim.
- A mechanism's *name* is not its *effect*: effects must be stated.

### 3.2 Pre-declared expected findings

This follows S2 §4.3b / Q34. The predictions are declared **so that a reader who only ever confirms them can be detected**. They are **not** evidence.

| # | Expected finding (the reader's prior, declared now) |
|---|---|
| F-A2e | SUPPORT in M-1 (the recorded T-0013 wording suggests it); no counterexample |
| F-A3g | SUPPORT or NOT EVIDENCED in M-1 |
| F-A4 | **AMBIGUOUS:** COMPOSITE is defined as *"crossing the above"*, which may mean sequence or atomic combination |
| F-A5e/g | NOT EVIDENCED for A5g; **AMBIGUOUS** for A5e (WORK-EXECUTION *"has a clock"*; expiry semantics unknown) |
| **F-A6** | **AMBIGUOUS or COUNTEREXAMPLE.** A governance canon plausibly applies amendments forward to pending items; this is the prediction most likely to fail H-F2-1a |
| F-A0/A3m | NOT EVIDENCED for pathologies; SUPPORT for monotonicity of `n ≥ 2` |
| Q-H6 | both readings present |
| Q-GS | g and s distinct (standing has more values than a grant flag) |
| Q-D4 | single field (T-0014 statement), with the attestation question AMBIGUOUS |

---

## 4. Evidence extraction rule (applies only after an authorized release)

**One record per relevant passage:**

```yaml
ta_evidence:
  test:               # F-A2e | F-A3g | F-A4 | F-A5e | F-A5g | F-A6 | F-A0 | F-A3m | Q-H6 | Q-GS | Q-D4
  source_id:          # canonical file_id, or the repo path + commit for M-2..M-4
  location:           # section / heading / verbatim anchor (never line numbers alone)
  original_wording:   # verbatim
  reconstructed_interpretation:
  competing_interpretation:   # mandatory when the classification is AMBIGUOUS
  classification:     # DIRECT_SUPPORT | DIRECT_COUNTEREXAMPLE | AMBIGUOUS | NOT_EVIDENCED
  mechanism_ref:      # P-1..P-10, or the source's own mechanism name, quoted
  confidence:         # HIGH | MEDIUM | LOW (of the classification, not of the axiom)
  reader:             # identity + independence class
  expected_finding_matched:   # r2: NOT filled by the reader. Computed after classification by analysis/t_a/aggregate.py
  countermodel_effect_stated: # true | false | n/a — only for DIRECT_COUNTEREXAMPLE (§5.1, r1)
  discovery_channel:          # COMPLETE_READING | R1 | R2 | R3 | R4 (§6, r1)
  source_version:             # r2: git commit + blob id of the object read (§2.1)
  independence_class:         # r2: SELF | SECONDARY_REVIEW | INDEPENDENT (split out of `reader`)
  competing_classification:   # r2: mandatory when AMBIGUOUS — the verdict under the competing reading:
                              #     DIRECT_SUPPORT | DIRECT_COUNTEREXAMPLE | NOT_BEARING
  a6_level:                   # r2: F-A6 only — OBJECT_LEVEL | META_LEVEL | NEITHER_OR_UNCLEAR (reading rules)
  effect_instantiates:        # r2: when countermodel_effect_stated — the propositions whose countermodel the stated
                              #     effect instantiates. Must be a subset of the §5.1 row for the test's axiom
  # r3 (§3.0): the operation is typed first, the effect is recorded second
  operation:
    actor:                    # source wording; context only
    action:                   # source wording (what is done)
    object:                   # source wording; context only (O-1)
    authority_context:        # source wording, if any
    source_declared_type:     # verbatim, if the source declares one; else null
    lexicon_candidate:        # advisory only (§3.0 f); never a basis
    assigned_kind:            # GOV | EVID | EVIDREF | WORK | COMP | UNKNOWN
    typing_basis:             # SOURCE_DECLARED | ACTION_SEMANTICS | UNKNOWN (never actor, object, lexicon or effect)
    typing_justification:     # mandatory for ACTION_SEMANTICS
    ambiguity_reason:         # mandatory for UNKNOWN
    admissible_kinds:         # UNKNOWN on a kind-specific test: >= 2 kinds
    outcome_by_kind:          # UNKNOWN on a kind-specific test: {kind: DIRECT_SUPPORT | DIRECT_COUNTEREXAMPLE | NOT_BEARING}
    split_basis:              # NONE | SOURCE_EXPLICIT
    split_quote:              # verbatim, when SOURCE_EXPLICIT
    typing_recorded_before_effect: true
  effect:                     # source-described consequences, verbatim or null
    evidential:
    grant:
    standing:
    bar:
    promotion:
    other:
```

**Reading rules:**
- complete-file reading of each authorized item (MP §2);
- no historical rewriting;
- source terms quoted, never merged into model terms;
- A6 records must state whether the change is **object-level** (an act on an item) or **meta-level** (a change to the rule or bar).
  - r2: a third value, **NEITHER_OR_UNCLEAR**, is allowed. The source's own description is quoted first. The tag is descriptive, not a model choice (anti-anchoring, §5 A6 row).
- **r2, anti-anchoring:**
  - the reader classifies **only** from the §3.1 definitions;
  - a second reader, where available, classifies **without** access to §3.2;
  - a prediction match is not evidence for H-F2-1-R, and a mismatch is not evidence against it;
  - `expected_finding_matched` exists only to detect a reader who only ever confirms (§3.2).
- **r3, kind assignment:** by the §3.0 K-S procedure, recorded in `operation` **before** anything is written in `effect` (the r2 PENDING flag is resolved).

---

## 5. Aggregation and decision rules (fixed now)

**Per question, report counts only:**
- **N** = relevant passages;
- **S** = direct support;
- **C** = counterexamples;
- **A** = ambiguous;
- **U** = uninformative.

**No probabilities, rates or confidence intervals.** There is no population or sampling frame. The four sources are the entire authorized material, not a sample.

| Result pattern | Consequence for H-F2-1-R |
|---|---|
| any C ≥ 1 on a **core** axiom (A2e, A3g, A4, A5e, A5g) | H-F2-1a **REFUTED for that mechanism**. Record which mechanism; revise (restrict the axiom's scope) or refute. **No silent patch**. **r3 (HD-S = S-U):** "for that mechanism" records *where* it was found. The axiom and H-F2-1a are **REFUTED AS STATED**. A restricted-scope axiom may re-enter only as a new registration (§5.1 re-check rule) |
| **C ≥ 1 on A6** | **r2 (replaces r0/r1 wording):** the counterexample refutes **A6 as currently formulated**. H-F2-1a as stated is **refuted** (r3: S-U). D1, D3, D3+ and D5 become **UNSUPPORTED** (§5.1). **The replacement mechanism is UNRESOLVED.** It must first be **characterized from the evidence**, then **≥ 2 alternatives** are constructed and formalized through the §5.1 re-check rule. Nothing is preselected; the candidates include two-level governance, versioned bars, temporal validity of bars, retroactive application, grandfathering, re-evaluation semantics, and a mechanism not yet known. **Still proved, unconditionally:** D1 and D3 hold on every trajectory in which the bar does not change. This is a conditional theorem, not a replacement model |
| per-question verdict SUPPORTED on every core axiom, and A6 not COUNTEREXAMPLE_FOUND | H-F2-1a **SURVIVES T-A (fidelity)**. T-B becomes the next test. *(r2: stated through the §5.0 verdicts (r3: cross-reference corrected from "§5.2"); "only S and A" removed, because it overlapped the next row)* |
| per-question verdict INCONCLUSIVE on any core axiom | **INCONCLUSIVE** for that axiom → a targeted reconstruction or search obligation (RO). *(r2: replaces the undefined "A dominant")* |
| C ≥ 1 on A0/A3m | **r2:** A0 / A3m refuted as formulated. D3+ and D5 **UNSUPPORTED** (§5.1); the stability extension *as stated* is refuted. The core D1–D3 is unaffected, because A0 and A3m are not in its minimal set |
| Q-GS shows g ≠ s | keep separate coordinates. If g ≡ s, swap A3g/A5g ↔ A3s/A5s (attack §12) and re-run the model. *(r2: the swap is a **candidate** revision only; it enters only through the §5.1 re-check rule)* |
| Q-D4: second field exists | D4 premise (ii) fails; H-F2-1b is weakened to "no constraint" |
| **r2:** a core axiom with verdict NOT_EVIDENCED or NOT_RUN, and no core COUNTEREXAMPLE_FOUND or INCONCLUSIVE | H-F2-1a **PARTIALLY UNTESTED** on that axiom. It neither survives nor is refuted; T-B may address it. *(r2: the r1 table had **no row** for this case, although §3.2 predicts NOT_EVIDENCED for A5g)* |

**r2: candidate-level precedence** (first match decides):
1. REFUTED (r3: under S-U, WEAKENED does not arise in T-A);
2. INCONCLUSIVE;
3. PARTIALLY UNTESTED;
4. SURVIVES T-A.

**r2: record-level NOT_EVIDENCED.**
- §4 allows `classification: NOT_EVIDENCED` on a record, while §3.1 defines NOT EVIDENCED as the **absence** of bearing passages.
- `aggregate.py` counts such a record as **U** (uninformative). The §3.1 definition is not changed.
- HD-1 confirms or rejects this reading.


### 5.0 Per-question verdict (r2): precedence-ordered, exhaustive, mutually exclusive

Each question is evaluated per mechanism and overall (all passages). The **first** rule that applies decides:

| # | Condition | Verdict |
|---|---|---|
| 0 | the question's required material was not released | **NOT_RUN**. Not an outcome: no count and no verdict |
| 1 | C ≥ 1 | **COUNTEREXAMPLE_FOUND** |
| 2 | some AMBIGUOUS record has `competing_classification = DIRECT_COUNTEREXAMPLE` | **INCONCLUSIVE**. A counterexample reading is live |
| 3 | S ≥ 1 | **SUPPORTED** (in T-A; this is fidelity, not corroboration) |
| 4 | A ≥ 1 | **INCONCLUSIVE** |
| 5 | otherwise (N = 0 in fully read material) | **NOT_EVIDENCED** |

- Implemented deterministically in `analysis/t_a/aggregate.py`.
- The interpretive questions Q-H6, Q-GS and Q-D4 report counts per reading and take no verdict.

### 5.1 From axiom verdicts to proposition verdicts (r1)

**Precision rule.** A counterexample to an axiom, for a mechanism:
- refutes **H-F2-1a as stated**, because the axioms are part of the hypothesis;
- makes the propositions below **UNSUPPORTED** (their proof is lost);
- does **not** make them **false**.

A proposition is recorded as **FALSIFIED** only if the source also **states the countermodel effect**. For example, for −A6: a bar change that brings a pending item across the bar with no evidential event. The evidence record keeps these as two separate fields: the axiom verdict, and whether the effect is stated.

| Axiom counterexample | Propositions made UNSUPPORTED (for that mechanism) |
|---|---|
| A2e | D1, D3, D3+ |
| A3g | D2, D3, D3+, D5 |
| A4 | D3, D3+ |
| A5e | D3, D3+ |
| A5g | D2, D3, D3+ |
| **A6** | **D1, D3, D3+, D5** |
| A0 | D3+, D5 |
| A3m | D3+, D5 |
| A1 | D6 |
| A3s, A5s | none (standing is inert) |

- **Source:** the single-removal matrix, computed identically by `analysis/h_f2_1/model.py` and `analysis/h_f2_1_verifier/verifier.py` on chain3 / V / diamond (closure pass §2.1, §4).
- **Scope:** it is relative to the 11-axiom granularity.

**Revision re-check rule (extends §6's computer-logic loop):**
- a revised axiom set is re-registered only if it satisfies both conditions on at least one instance where promotion is non-degenerate:
  - D1–D3 are re-proved (sufficiency);
  - **NV holds**, i.e. promotion is still reachable;
- a revision that makes promotion unreachable proves D3 **vacuously**, and is rejected as a repair.

**Reader protocol:**
- ≥ 1 reader performs the reading. Where possible a **second reader classifies blind** to the first reader's classifications.
- Disagreements are recorded, not averaged.
- Independence class per reader: SELF / SECONDARY_REVIEW / INDEPENDENT (S2 §10.2, §11.0).

---

## 6. ML and computational support (candidate discovery only)

**T-A itself needs no ML.** The authorized material is 2–4 files, all read completely (MP §2). Retrieval would add leakage and no information.

**Where ML earns its place: T-B**, the corpus-wide search for locality violations, especially F-A6. This is **pre-specified here as a future design**, so that it is frozen before any T-B reading.

| Stage | Method | Role | Frozen parameters | Leakage guard |
|---|---|---|---|---|
| R1 lexical | BM25 over canonical files, with pre-registered query sets per falsifier (e.g. A6: "applies to all pending", "retroactive", "grandfather", "re-evaluate", "threshold changed", "amendment takes effect") | candidate files and passages | query list, tokenizer, k | queries written now, from the axioms, not from corpus text |
| R2 semantic | sentence-embedding retrieval, using the axiom *descriptions* as queries | recall of paraphrases R1 misses | model id and version, chunking, k, similarity threshold | the pretrained model is external; version recorded |
| R3 classifier (optional) | a small classifier on **human-adjudicated** T-A passages | re-rank candidates | trained **only** on adjudicated labels; frozen before T-B | never trained on model output; T-A passages are excluded from T-B scoring |
| R4 LLM-assisted (optional, r1) | an LLM proposes **candidate passages** for a falsifier question. It never classifies them | recall of indirect descriptions | model id and version, the verbatim prompt, temperature and sampling parameters, date | the prompt is written from the axioms and falsifiers only; the output is a list of locations, not verdicts |
| Human | complete-file reading of every candidate file (MP §2, §27) | **the only evidence step** | — | ML output is never evidence |

**Recording rule (r1):**
- every stage's configuration is frozen and recorded **before** the T-B evaluation;
- every evidence record carries `discovery_channel` (§4);
- a passage found by ML and a passage found by complete reading get the **same** adjudication. The channel is recorded; it never affects the classification.

**Retrieval evaluation**, before T-B conclusions are drawn:
- **Recall of the retrieval stack.** Seed a small set of human-identified relevant passages from a random-file audit, and measure how many R1 ∪ R2 retrieves.
- **Review-cost metric.** Relevant passages found per file read, versus random-order reading.

These measure the **instrument** (review efficiency), not the axioms. They are kept separate and never combined into one score.

**A computer-logic loop for revisions:**
- any axiom revision forced by T-A (for example a restricted A2e, or a two-level A6) is re-encoded in the finite-state model;
- the D1–D3 minimal sets are recomputed **before** the revised candidate is re-registered;
- this prevents a verbal patch from silently breaking a derived proposition.

---

## 7. What must not happen

- Reading M-1…M-5 before this pre-registration is reviewed and frozen.
- Using current-version schemas in place of the version at F0018's date.
- Converting NOT EVIDENCED into support, or wording differences into counterexamples.
- Patching H-F2-1-R during T-A (revisions come **after**, as new records).
- Training any model on its own output.
- Computing any probability that an axiom is true.

---

## 8. Human decisions this pre-registration prepares (not requested)

| # | Decision |
|---|---|
| HD-1 | review and freeze this pre-registration (including §3.2 predictions and §5 rules) |
| HD-2 | a corpus-read release for **M-1** (and optionally M-5): re-READ of canonical files |
| HD-3 | a **corpus-scope decision** for **M-2…M-4** (files outside `docs/knowledgeos/`; S2 OQ-6), with the §2.1 version rule |
| **HD-S** (r2) | **given (r3):** human input 2026-09-26, S-U + K-S + O-1 + SP-S, with the lexicon advisory. Implemented in §3.0 |
| HD-4 | the reader independence class available (INDEPENDENT vs SECONDARY_REVIEW) |

---

## 9. Revision record

| Rev | Change | Why | Effect on r0 content |
|---|---|---|---|
| r1 | §5.1: axiom → proposition map, the UNSUPPORTED/FALSIFIED distinction and the NV re-check rule | closure pass §3–§4. Without the map, an axiom counterexample would be read as falsifying propositions that do not depend on it, or that are merely unproved | additive. The §5 decision table is unchanged; §5.1 states precisely what it means |
| r1 | §4: fields `countermodel_effect_stated` and `discovery_channel` | the §5.1 distinction; the commission's ML recording rule (§12: "distinguish discovery from evidence") | additive |
| r1 | §6: R4 LLM-assisted candidate retrieval, plus the recording rule | commission §12 lists "LLM-assisted candidate passage identification" | additive, T-B only. T-A still uses no ML |
| r2 | §5, A6 row: "two-level model is required" replaced by "A6 refuted as formulated; replacement mechanism UNRESOLVED; characterize → ≥ 2 alternatives → re-check rule" | commissioned anti-anchoring correction. The r1 wording preselected a replacement theory | **changes a decision rule; justified**. Predictions and outcome definitions are unchanged |
| r2 | §5.0: a precedence-ordered per-question verdict. The rows "only S and A" and "A dominant" are restated through it | the r1 rows **overlapped** (S = 1, A = 5 satisfied both SURVIVES and INCONCLUSIVE), and "dominant" was undefined. A deterministic rule set must be exhaustive and mutually exclusive | **changes a decision rule; justified** (logical error) |
| r2 | §5, A0/A3m row and Q-GS row: aligned with §5.1 (UNSUPPORTED ≠ false); the swap marked as a candidate revision | consistency with §5.1 | wording |
| r2 | §5, core row and §3: flagged **PENDING HD-S** (scope and kind assignment undefined). **Not resolved** | a STOP condition of the commission: *"an axiom's scope is undefined"*, so report, do not fix | none; flag only |
| r2 | §2.1: the date boundary, the M-1 manifest-pin rule, and the resolved versions (metadata only). The `NOT_AVAILABLE_AT_DATE → AMBIGUOUS` sentence marked moot | temporal safety. F0018 post-dates its own commission date, so the date rule cannot apply to M-1 | additive |
| r2 | §4: `source_version`, `independence_class`, `competing_classification`, `effect_instantiates`; `expected_finding_matched` computed, not filled by the reader; A6 tag gains NEITHER_OR_UNCLEAR; anti-anchoring rules | evidence-schema completeness; blinding to predictions | additive |
| r2 | §10: T-A execution checklist | commissioned deliverable C | additive |
| r3 | §3.0: HD-S (S-U; K-S + O-1; SP-S; UNKNOWN by enumeration; advisory lexicon; procedural counterexample; H-1′; coverage) replaces the r2 flag | the human's HD-S (F-LOG-0030) | resolves PENDING |
| r3 | §4: `operation` and `effect` blocks; the kind-assignment line resolved | operation/effect separation | additive |
| r3 | §5: the core-row and A6-row scope wording fixed to S-U; candidate precedence (WEAKENED does not arise in T-A); "§5.2" corrected to §5.0 | follows from S-U; a stale cross-reference | resolves PENDING; typo |
| r3 | §10: steps 3, 6, 8 and new 9a | order of typing before effect; coverage | procedural |

## 10. T-A execution checklist (deterministic; r2)

Each step's evidence is recorded before the next step starts. Any failure means STOP and report.

1. **Frozen hash.** `sha256sum` of this file equals the hash in the human's HD-1 freeze record. The freeze record exists and was written by the human.
2. **Release scope.** The L0 release (Research Release Check, L0-DEC-27) names exactly the items to be read. Any §3 question whose material is not released is marked **NOT_RUN** (§5.0 rule 0).
3. **HD-S in force.** §3.0 is part of the frozen text. `aggregate.py` is run with `--scope UNIVERSAL`.
4. **Resolve versions.**
   - Read each object by commit/blob (`git show <commit>:<path>`), never from the working tree.
   - Verify M-1's sha256 against the manifest pin.
   - Record the §2.1 table again. A mismatch means STOP.
5. **Read completely.** Read each released object in full (MP §2). Record the read in the read-integrity log.
6. **Extract.**
   - One `ta_evidence` record per relevant passage (§4), with every mandatory field.
   - Fill `operation` (§3.0 c–e) **completely before** writing any `effect` field. Set `typing_recorded_before_effect: true` only if that order was kept.
   - Then fill `effect` from the source wording only.
7. **Classify** from §3.1 only. Readers are blind to §3.2 where a second reader exists.
8. **Aggregate.**
   - Run `python3 analysis/t_a/aggregate.py --scope UNIVERSAL --released <released items> records.jsonl`.
   - The script validates the schema, applies §5.0 and §5.1, and computes `expected_finding_matched`.
   - Its output and sha256 are committed.
9. **Apply** the fixed §5 decision rules to the script output. No reinterpretation.
9a. **Coverage.** Report `N_typed` and `N_unknown` per test, alongside the verdicts.
10. **Stop.**
    - Report the verdicts and hand them to the human.
    - No revision of H-F2-1-R in the same session.
    - Revisions go through the §5.1 re-check rule, as new records.

---

*No source read. No release requested. No H-item decided. H-F2-1-R unchanged.*
