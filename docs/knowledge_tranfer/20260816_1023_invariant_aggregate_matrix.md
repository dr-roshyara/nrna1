# Invariant-to-Aggregate Matrix — Decision Instrument v2

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — the artifact named by `20260816_0922_aggregate_boundaries.md` §10 |
| **Restructured** | 2026-08-16 — from catalog to **decision instrument**, per the Matrix Structure input (conversation, 2026-08-16). v1 (catalog form, 25 rows) is replaced in place: a second matrix file would be the `DUPLICATES` violation this instrument exists to detect |
| **External citations in the input** | `informit.com` · `software.tektite.studio` · `deepwiki.com` — recorded as **input, evidence-graded by their authors, not verified here**, per the convention the SLR file establishes |
| **Companions** | `..._0841_target_architecture_v3.md` · `..._0905_architecture_conformance.md` · `..._0914_rule_model_and_conflict_analysis.md` · `..._0922_aggregate_boundaries.md` |
| **Date** | 2026-08-16 |

> **The instrument's purpose, adopted verbatim from the input:**
> *"The matrix should be treated as the **evidence for aggregate boundaries**. If an aggregate has no invariant that it uniquely protects, it is probably only a data grouping or projection."*
>
> §6 applies that test to this matrix's own aggregates. **Two fail it.**

---

## 1. The record — 14 fields

The `InvariantRecord` is the canonical form. The scan table in §4 is a **projection of it**, and `DR-1` applies to the projection as to any other: nothing may depend on the scan table, and it is rebuildable from the records.

```text
InvariantRecord
├── invariant_id
├── domain_statement
├── knowledge_type
├── trigger_command
├── required_state
├── candidate_owner
├── atomicity
├── enforcement_mechanism
├── referenced_aggregates
├── failure_behavior
├── rationale
├── test_reference
├── status
├── reconstructable_as_of        ← added; see below
├── hardness                     ← added 10:29; HARD | SOFT | ADVISORY
└── enforcement_layer            ← added 10:29; AGGREGATE | VALUE_OBJECT |
                                    DOMAIN_SERVICE | APPLICATION_SERVICE |
                                    GOVERNANCE_WORKFLOW | PROJECTION_PROCESS
```

> **`hardness` and `enforcement_layer` are specified in `20260816_1029_invariant_allocation.md` §3 and §1.** `hardness` is orthogonal to `atomicity`: `atomicity` says *when* a condition can be evaluated, `hardness` says *whether the business wants it to block*. Conflating them is how an advisory check silently becomes a gate.

### The added field, and why

The thirteen supplied fields capture **enforcement at command time**. They do not capture **reconstructability at audit time**, and in this corpus that is not a refinement — it is the difference between a record and a claim.

`INV-EXC-003` (an exception's validity may not exceed its approver's authority) is checkable at issuance by an authority evaluator. But if that authority grant is later revoked or lapses, the question *"was this exception validly approved?"* becomes unanswerable — unless the version of the authority grant consulted at approval was captured.

This is `INV-A1` (*a report of an act is not the act*) applied to invariant checking: **a stored verdict that an invariant held is a report; the reconstructable state is the act.** `how_to_work_with_sessions` §7 rules that a report cannot substitute for the act. So:

> **`reconstructable_as_of`** — the referent versions that must be pinned at the governing command so the invariant can be re-evaluated later against the state as it actually was.

`0922` §3 makes this cheap: if the PKS remains specifications, every pinned version is a commit SHA.

---

## 2. Controlled vocabularies

### 2.1 `atomicity`

Adopted as supplied, with one normalization and one addition.

| Value | Meaning | Typical mechanism |
|---|---|---|
| `ATOMICALLY_REQUIRED` | Cannot be false at commit | Aggregate invariant |
| `SAME_CONTEXT_TRANSACTION` | Several objects in one context change together | Aggregate or transactional domain service |
| `EVENTUALLY_CONSISTENT` | Temporary divergence acceptable; converges | Domain event + projection update |
| `ANALYTICAL` | Computed from a snapshot; never transactional state | Analysis service |
| `GOVERNANCE_DECISION` | Requires an authorized human or organizational act | Workflow + authority policy |
| `EXTERNAL_PRECONDITION` | Depends on another system or an observed fact | Port · assessment · async verification |
| **`PINNED_AT_DECISION`** | **Holds against a captured version of a referent, permanently — proposed addition** | **Version capture at the governing command** |

**Normalization:** the input spells the first value `ATOMically_REQUIRED`. Normalized to `ATOMICALLY_REQUIRED`. Under `G-7 Term` — *"a ubiquitous-language entry; changes are first-class governed events"* — the spelling of a controlled value is a governed act, so the correction is recorded rather than made silently.

**Why `PINNED_AT_DECISION` is a seventh value and not a case of an existing one.** It is not `EVENTUALLY_CONSISTENT`: nothing converges, because the pinned version never changes. It is not `ANALYTICAL`: the result is authoritative, not computed. It is not `GOVERNANCE_DECISION` alone: that classifies *who decides at issuance*, whereas pinning is what makes the decision *checkable afterwards*. The two compose — `INV-EXC-003` is `GOVERNANCE_DECISION` at command time **and** `PINNED_AT_DECISION` for audit. Recorded as `OQ-21`.

### 2.2 `failure_behavior`

Supplied as prose; made a controlled vocabulary here, because an uncontrolled failure column is where instruments quietly lose their meaning.

| Value | Meaning |
|---|---|
| `REJECT` | The command does not commit |
| `QUARANTINE` | The object is stored but excluded from authoritative use |
| `REVIEW` | Routed to a human authority; no automatic outcome |
| `COMPENSATE` | Committed, then corrected by a follow-on action |
| `MARK_UNKNOWN` | Recorded as not-evaluated |

**Binding constraint on `MARK_UNKNOWN`:** it is never a pass. `INV-A3` — *absence is never permission* — and v3 principle 6 — *silence is an architectural decision*. An unevaluated invariant must be visible as unevaluated and must not satisfy any gate.

### 2.3 `status`

`Proposed · Accepted · Disputed · Retired`, as supplied — with one rule the instrument needs to stay honest:

> **A row may not reach `Accepted` while `test_reference` is empty.**

`how_to_work_with_sessions` §15 requires verification to show **Claim → Attack → Observed result → Evidence → Conclusion**, and distrusts *"all tests passed."* An invariant with no executable scenario has never been attacked. **Every row below is therefore `Proposed`** — no tests exist in this corpus, and none can until `OQ-1` resolves.

---

## 3. The aggregate assignment test

> **Superseded 10:29** by the single ownership test in `..._1029_invariant_allocation.md` §2 — *"If this condition is false, which single object has the **authority and the information** to reject the command?"* The six questions below are an unordered checklist; the one question forces an answer, and its authority/information pairing is why `INV-RULE-002` has two owners. Retained here as the derivation.

Adopted verbatim, applied in order. §6 records what it produced.

1. Can the invariant be evaluated using only one aggregate's state? → candidate for that aggregate.
2. Must two or more objects change atomically? → they may belong in the same aggregate.
3. Can temporary inconsistency be tolerated? → event, process manager, or reconciliation — **not a larger aggregate**.
4. Does evaluation require external facts? → keep the fact outside; record the result as an assessment or snapshot.
5. Is the result computed rather than authoritative? → analysis or projection, not aggregate state.
6. Does resolution require human authority? → governance workflow, not an automatic aggregate method.

---

## 4. The matrix

Scan projection of the records. Full fields per row live in the `InvariantRecord`; §5 works four of them in full.

### 4.1 `Rule`

| ID | Domain statement | Owner | Atomicity | Enforcement | Failure | Pin |
|---|---|---|---|---|---|---|
| `INV-RULE-001` | An active rule has a valid applicability expression | `Rule` | `ATOMICALLY_REQUIRED` | `Rule.activate()` | `REJECT` | — |
| `INV-RULE-002` | An active rule has an authority reference | `Rule` | `ATOMICALLY_REQUIRED` (reference only) | `Rule.activate()` | `REJECT` | `AuthorityGrant@v` |
| `INV-RULE-003` | An obligation or prohibition has a target and a value/constraint | `Rule` | `ATOMICALLY_REQUIRED` | `NormativeEffect` construction | `REJECT` | — |
| `INV-RULE-004` | `validFrom < validUntil`, unless open-ended | `ValidityPeriod` (VO) | `ATOMICALLY_REQUIRED` | value-object construction | `REJECT` | — |
| `INV-RULE-005` | No activation after retirement | `Rule` | `ATOMICALLY_REQUIRED` | lifecycle guard | `REJECT` | — |
| `INV-RULE-006` | No supersession without a valid replacement reference | `Rule` | `SAME_CONTEXT_TRANSACTION` | `Rule.supersede()` | `REJECT` | `Rule@v` (replacement) |
| `INV-RULE-007` | **A rule may not decide whether another rule conflicts with it** | *(boundary prohibition)* | n/a | design-time; no method exists | n/a | — |
| `INV-RULE-008` | No direct transition from creation to an authoritative state | `Rule` | `GOVERNANCE_DECISION` | lifecycle guard + authority port | `REJECT` | `AuthorityGrant@v` |
| `INV-RULE-009` | Exactly one authoritative home per rule | ⚠️ **not `Rule`** — see §6.2 | `ANALYTICAL` · `hardness: ADVISORY` | corpus-wide analysis | finding, **never a block** | snapshot |
| `INV-RULE-010` | A rule may be activated only by an actor holding authority for its scope and type | authority policy | `GOVERNANCE_DECISION` + `PINNED_AT_DECISION` | application service → authority port | `REJECT` or `REVIEW` | `AuthorityGrant@v` |

### 4.2 `ExceptionGrant`

| ID | Domain statement | Owner | Atomicity | Enforcement | Failure | Pin |
|---|---|---|---|---|---|---|
| `INV-EXC-001` | An exception identifies the rule it overrides and defines bounded scope and validity | `ExceptionGrant` | `ATOMICALLY_REQUIRED` | `ExceptionGrant.issue()` | `REJECT` | — |
| `INV-EXC-002` | Scope ⊆ base rule scope, unless explicitly authorized to exceed | `ExceptionGrant` | `PINNED_AT_DECISION` | issuance check vs pinned rule | `REJECT` or `REVIEW` | `Rule@v` (base) |
| `INV-EXC-003` | Validity ⊆ the approver's authority validity | authority policy | `GOVERNANCE_DECISION` + `PINNED_AT_DECISION` | authority evaluator | `REJECT` or `REVIEW` | `AuthorityGrant@v` |
| `INV-EXC-004` | An expired exception cannot satisfy a current conflict | `ExceptionGrant` | `ATOMICALLY_REQUIRED` **on read** | evaluated at query time — §5.2 | `MARK_UNKNOWN` → not satisfied | — |
| `INV-EXC-005` | Revocation is distinguishable from expiration | `ExceptionGrant` | `ATOMICALLY_REQUIRED` | distinct terminal states | `REJECT` | — |
| `INV-EXC-006` | An exception may not mutate the base rule | *(boundary prohibition)* | n/a | design-time; no write path | n/a | — |
| `INV-EXC-007` | No exception without a recorded approver | `ExceptionGrant` | `GOVERNANCE_DECISION` | `ExceptionGrant.approve()` | `REJECT` | `AuthorityGrant@v` |

### 4.3 Analysis

| ID | Domain statement | Owner | Atomicity | Enforcement | Failure | Pin |
|---|---|---|---|---|---|---|
| `INV-ANL-001` | A conflict report references a fixed rule and context snapshot | `AnalysisRun` | `ATOMICALLY_REQUIRED` | result factory | do not publish | snapshot |
| `INV-ANL-002` | A conflict is proven only when applicable effects are jointly unsatisfiable | `RuleConflictDetector` | `ANALYTICAL` | constraint analyzer | return `UNKNOWN` | — |
| `INV-ANL-003` | Analyzer version is recorded on every run | `AnalysisRun` | `ATOMICALLY_REQUIRED` | result factory | do not publish | — |
| `INV-ANL-004` | Both rule versions are recorded on every report | `ConflictReport` | `ATOMICALLY_REQUIRED` | result factory | do not publish | `Rule@v` ×2 |
| `INV-ANL-005` | The temporal interval of the conflict is recorded | `ConflictReport` | `ATOMICALLY_REQUIRED` | result factory | do not publish | — |
| `INV-ANL-006` | Nothing may depend on a `ConflictReport` | *(boundary prohibition)* | n/a | design-time | n/a | — |
| `INV-ANL-007` | A report may never be cited as authority for a change | *(boundary prohibition)* | n/a | design-time | n/a | — |
| `INV-ANL-008` | `confidence` is populated only for candidate detection | `ConflictReport` | `ATOMICALLY_REQUIRED` | result factory | `REJECT` | — |
| `INV-ANL-009` | A report's relationship value comes from the **analyser** vocabulary only | `ConflictReport` | `ATOMICALLY_REQUIRED` | enum constraint | `REJECT` | — |
| `INV-ANL-010` | Identical inputs yield an identical result | `AnalysisRun` | `ANALYTICAL` | determinism property | `REVIEW` | snapshot |

### 4.4 Newly writable — supplied by the input

`0922` §5 blocked these on `OQ-1`/`OQ-4`/`OQ-13`. **That judgement was too broad and is corrected here:** the statements below name no technology, so they are writable now. Only the *aggregates'* full field lists remain blocked.

| ID | Domain statement | Owner | Atomicity | Enforcement | Failure | Pin |
|---|---|---|---|---|---|---|
| `INV-EVD-001` | Verified evidence retains source identity, version, location, and content hash | `EvidenceRecord` | `ATOMICALLY_REQUIRED` | evidence factory / verifier | `QUARANTINE` | — |
| `INV-ASM-001` | An assessment verdict references ≥1 evidence record, or states explicitly that it is evidence-free and provisional | `Assessment` | `ATOMICALLY_REQUIRED` | `Assessment.issueVerdict()` | `REJECT` or mark provisional | `EvidenceRecord@v` |
| `INV-RES-001` | A conflict cannot be resolved without an authorized disposition and rationale | `ConflictResolution` | `GOVERNANCE_DECISION` | resolution workflow | keep unresolved | `AuthorityGrant@v` |
| `INV-PRJ-001` | A published projection identifies the source snapshot it was built from | `ProjectionBuild` | `ATOMICALLY_REQUIRED` | projection publisher | do not publish | snapshot |
| `INV-PRJ-002` | A stale or failed projection is not represented as current | `ProjectionBuild` | `EVENTUALLY_CONSISTENT` | reconciliation process | mark stale / rebuild | — |

**`INV-ASM-001` is the strongest row supplied by the input.** Its *"or explicitly states that it is evidence-free and provisional"* clause is the corpus's own posture made mechanical — `pks_progress` §7.2: *"No concept is promoted without operational evidence"*, and RET-1's finding that *"all defects were interpretive, not evidentiary."* It permits an unevidenced verdict while making the absence impossible to hide. That is exactly `MARK_UNKNOWN` done right.

---

## 5. Worksheets

### 5.1 `INV-RULE-002`

```text
Invariant:     INV-RULE-002
Statement:     An active rule has an authority reference.
Knowledge type: Rule
Applies when:  Rule lifecycle transition DRAFT → ACTIVE
Trigger:       Rule.activate()
Required state: Rule fields only (the reference), plus a pinned AuthorityGrant version
Candidate owner: Rule
Atomicity:     ATOMICALLY_REQUIRED for the reference's presence;
               GOVERNANCE_DECISION for its validity (see INV-RULE-010)
Enforcement:   Rule.activate() checks presence; application service checks validity via authority port
Referenced:    AuthorityGrant (by id + version; state never embedded)
Failure:       REJECT with structured reason code
Reconstructable as-of: AuthorityGrant@version at activation
Rationale:     proposal §1; v3 §9 — the mechanism records authority, never grants it
Test:          (none — blocked on OQ-1)
Status:        Proposed
```

**The split in `atomicity` is the point.** *Presence* of a reference is local and atomic; *validity* of the referenced authority is not. Collapsing them into one row is how an aggregate quietly acquires responsibility for the organizational authority graph — which the input's §3 warns against explicitly.

### 5.2 `INV-EXC-004`

```text
Invariant:     INV-EXC-004
Statement:     An expired exception cannot satisfy a current conflict.
Knowledge type: Exception
Applies when:  Any query asking whether a conflict is dispositioned
Trigger:       NONE — expiry is caused by time passing, not by any command
Required state: ExceptionGrant.validity_period + the instant of the question
Candidate owner: ExceptionGrant
Atomicity:     ATOMICALLY_REQUIRED — but evaluated at READ time, not write time
Enforcement:   validity evaluated against the asking instant, never cached as a boolean
Referenced:    Rule (base), by id
Failure:       MARK_UNKNOWN → the conflict remains unsatisfied (never a pass)
Reconstructable as-of: n/a — the interval is self-contained
Rationale:     proposal §4; INV-A3 — absence is never permission
Test:          (none — blocked on OQ-1)
Status:        Proposed
```

**This row is why `trigger_command` needs to permit `NONE`.** An instrument that models only command-triggered invariants cannot express expiry, and a model that evaluates exception validity only when something is written will honour dead exceptions indefinitely. **A stored `is_valid` boolean is the bug this row exists to prevent.**

### 5.3 `INV-EXC-002`

```text
Invariant:     INV-EXC-002
Statement:     An exception's scope ⊆ the base rule's scope, unless explicitly authorized to exceed it.
Knowledge type: Exception
Applies when:  ExceptionGrant issuance or scope amendment
Trigger:       ExceptionGrant.issue() / amendScope()
Required state: ExceptionGrant.scope + the base Rule's scope AT A PINNED VERSION
Candidate owner: ExceptionGrant
Atomicity:     PINNED_AT_DECISION
Enforcement:   scope containment checked against the pinned base-rule version
Referenced:    Rule (base), by id + version
Failure:       REJECT; or REVIEW where an authorized widening is claimed
Reconstructable as-of: Rule@version at issuance
Rationale:     proposal §4; without pinning, a later widening of the base rule
               silently widens every exception granted under it
Test:          (none — blocked on OQ-1)
Status:        Proposed
```

### 5.4 `INV-ANL-002`

```text
Invariant:     INV-ANL-002
Statement:     A conflict is proven only when applicable effects are jointly unsatisfiable.
Knowledge type: Analysis
Applies when:  Any pairwise rule comparison
Trigger:       RuleConflictDetector.compare(A, B)
Required state: Both rules at fixed versions + applicability ∩ scope ∩ temporal intersection
Candidate owner: RuleConflictDetector (domain service — NOT Rule)
Atomicity:     ANALYTICAL
Enforcement:   constraint analyzer over the intersection
Referenced:    Rule ×2, by id + version
Failure:       return UNKNOWN — never CONSISTENT by default
Reconstructable as-of: AnalysisRun snapshot
Rationale:     0914 §5; INV-RULE-007 keeps this off the Rule aggregate
Test:          (none — blocked on OQ-1)
Status:        Proposed
```

---

## 6. What applying the instrument's own tests produced

Four results that the catalog form could not have surfaced.

### 6.1 `ConflictReport` fails the aggregate test

The input's rule: *"If an aggregate has no invariant that it uniquely protects, it is probably only a data grouping or projection."*

`ConflictReport` carries `INV-ANL-004`, `-005`, `-008`, `-009` — but every one is a **construction constraint** enforced by the result factory, not an invariant the object defends over its own lifetime. It is written once, never mutated, and `INV-ANL-006`/`-007` forbid anything depending on it.

**Reclassified: `ConflictReport` is a read model, not an aggregate.** `AnalysisRun` remains the aggregate — it owns the snapshot identity that makes results reproducible. This is `DR-1` and `L4-8` reached by an independent route.

### 6.2 `INV-RULE-009` requires a global scan and loses its owner

Deliverable step 9: *"Review rows that claim aggregate ownership but require global scans."*

`INV-RULE-009` — *exactly one authoritative home per rule* — cannot be evaluated from one `Rule`. It requires every rule. **Ownership moves off the aggregate to a corpus-wide analysis process**, atomicity `ANALYTICAL`, failure `REVIEW`.

This is the first time `G-2 Rule`'s *"single canonical home"* clause has been given a checkable form — and the finding is that it was never an aggregate invariant at all.

### 6.3 The activation flow needs one correction

Supplied:

```
RuleActivated → CandidateRuleAnalysisRequested → AnalysisRunCompleted
              → ConflictDetected | NoConflictDetected → ActivationAllowed | ReviewRequired
```

Adopted, with **`ActivationAllowed` removed.** A mechanism that emits *allowed* has granted authority — v3 §9: *the mechanism records authority; it never grants it*; and `INV-RULE-008` forbids a direct creation→authoritative edge. The pipeline may report an absence of detected conflict; it may not license activation.

```
RuleActivationRequested → CandidateRuleAnalysisRequested → AnalysisRunCompleted
                        → ConflictDetected | NoConflictDetected
                        → ReviewRequired  |  (awaits the activating act)
```

The input's own caveat is adopted unchanged and matters: if activation must block synchronously, the **application service** may run analysis before committing — but the detector still never becomes part of the `Rule` aggregate (`INV-RULE-007`).

### 6.4 Nine rows carry a pin

`INV-RULE-002`, `-006`, `-008`, `-010`, `INV-EXC-002`, `-003`, `-007`, `INV-ASM-001`, `INV-RES-001` all reference another aggregate whose later change would silently alter their meaning. **Each needs `reconstructable_as_of` populated, or the invariant is checkable once and never again.**

This is v1 §4's *pin / detect / forbid* finding, now assigned per row rather than stated in aggregate.

---

## 7. Classification

Adopted from the input, with §6's corrections applied.

```text
Aggregates
  Rule · ExceptionGrant · EvidenceRecord · Assessment
  Decision · AnalysisRun · ConflictResolution · ProjectionBuild · AuthorityGrant

Value objects
  Scope · ApplicabilityExpression · NormativeEffect
  ValidityPeriod · Confidence · ProvenanceReference

Domain services                    (reason — never coordinate, never decide)
  RuleConflictDetector · AuthorityEvaluator
  ApplicabilityEvaluator · RelationshipClassifier
  ⚠ AuthorityEvaluator REPORTS whether a covering grant is recorded;
    it never concludes "authorized" — ..._1029_invariant_allocation.md §4

Application services               (coordinate — added 10:29; the matrix
  ActivateRuleHandler · IssueExceptionHandler         relied on this layer
  ResolveConflictHandler                              without declaring it)

Governance workflows               (decide — human authority only)
  ConflictResolution · exception approval · rule activation

Read models / projections
  KnowledgeGraph · SearchIndex · AIContextPackage · ArchitectureView
  ConflictReport                                    ← moved here by §6.1
```

**Unchanged from `0922` §6:** nine aggregates remain more than the evidence supports, `Architecture Context` stays open under `OQ-16`, and the context partition stays at the certified four under `OQ-4`. This list is a **candidate classification**, not an adopted decomposition.

---

## 8. Corrections to prior documents

| Document | Correction |
|---|---|
| **This file, v1** | Replaced in place. Catalog → instrument; 25 rows → 32; `PINNED_AT_DECISION` and `reconstructable_as_of` added |
| `0922` §5 | **Too broad.** `EvidenceRecord`, `Assessment`, `ConflictResolution`, `ProjectionBuild` invariants are writable now; only their full field lists are blocked (§4.4) |
| `0922` §5 | **`OQ-18` answered affirmatively** — `INV-EXC-003` is unanswerable without as-of-time authority validity |
| `0914` §11 | `ConflictReport` is a **read model**, not an aggregate (§6.1) |
| v3 §5 | `G-2 Rule`'s *single canonical home* becomes checkable as `INV-RULE-009` — and is **not** an aggregate invariant (§6.2) |

---

## 9. Open questions added

| # | Question | Settled by |
|---|---|---|
| **OQ-21** | Is `PINNED_AT_DECISION` a seventh atomicity value, or does `GOVERNANCE_DECISION` + a pinned reference suffice? | §2.1 argues seventh; a modelling decision |
| **OQ-22** | May `trigger_command` be `NONE`? `INV-EXC-004` requires it | §5.2 — time-triggered invariants exist and must be expressible |
| **OQ-23** | Which aggregates survive the unique-invariant test once all rows are written? | The completed matrix. Two have already failed it |

---

## 10. Bottom line

The instrument earns its structure by producing findings the catalog form could not:

- **`ConflictReport` is not an aggregate** — no invariant it uniquely defends over a lifetime.
- **`INV-RULE-009` was never an aggregate invariant** — it requires a global scan, so `G-2`'s oldest clause belongs to an analysis process.
- **`ActivationAllowed` cannot be emitted by a mechanism** — that is authority, and mechanisms only record it.
- **Nine rows need a pinned referent version**, or they are checkable exactly once.

And the rule that keeps the instrument honest, from its own `status` column: **no row reaches `Accepted` while `test_reference` is empty.** Every row here is `Proposed`. Thirty-two invariants, zero attacked.

`OQ-8` remains next. Ten `Rule` rows now constrain what its fields must support.

---

*Restructured from v1 per the Matrix Structure input (2026-08-16); external citations recorded as unverified input. Derived with `..._0922_aggregate_boundaries.md` §10 and the three prior 2026-08-16 documents. §8 lists the corrections other documents require.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
