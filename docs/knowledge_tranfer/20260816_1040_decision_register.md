# Decision Register — 29 Open Questions, Consolidated

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — consolidation, not new analysis. Every question below already exists in a prior document; this document adds only their **dependency structure** and **who must decide** |
| **Consolidates** | `..._0841` v3 · `..._0905` conformance · `..._0914` rule model · `..._0922` aggregates · `..._1023` matrix · `..._1029` allocation (+ addendum) · `..._1034` policy catalogue |
| **Date** | 2026-08-16 |

> **Why this artifact and not another analysis.** Seven documents were written today. They contain **29 open questions and 0 decisions**, and every one of them ends by deferring to the same two. That is the corpus's own recorded failure mode — v3 §0 found the 2026-08-15 generation moving contexts 4 → 6 → 7 → 4 while deciding nothing. **A register is subtractive:** it replaces "scattered across seven documents" with one view, and it makes visible that most questions are not actually blocked.

---

## 0. The headline

| | Count |
|---|---|
| Open questions raised today | 29 |
| **Already answered, awaiting closure** | **1** (`OQ-18`) |
| **Decidable now** — no unresolved dependency; needs only a human act | **13** |
| Blocked by `OQ-1` alone | 7 |
| Blocked by `OQ-8` alone | 4 |
| Blocked by evidence that does not yet exist | 4 |

**Two questions gate the corpus:** `OQ-1` is referenced 30 times, `OQ-8` 23 times. Together they block 11 of the remaining 28.

**But 13 are decidable today**, and four of those are cheap vocabulary acts that are actively costing money — the naming defect in §4 is compounding measurably, within single documents.

---

## 1. The two roots

### `OQ-1` — Is the PKS software?

The corpus asserts both answers and has never adjudicated:

- `what_is_pks_v1` §5.1 · `pks_progress` §5.1 — *"The PKS Is Not Software… YAML + Markdown… does not require PHP, Python, or any other programming language."* Inside a corpus marked **PROVISIONALLY CERTIFIED**.
- `C4 Level 4 — Code.md` — *"PHP 8.3+ / Laravel 12 · PostgreSQL (primary), Neo4j (optional graph) · Vue 3 / Inertia.js."*

**Blocks:** `OQ-17`, `OQ-19`, the hexagonal stack, code-level DDD gates, `EvidenceRecord`/`ProjectionBuild` field lists, and — because no tests can exist — **every one of the 32 matrix invariants is stuck at `status: Proposed`.**

**Decider:** human authority. **Decidable today.** Nothing is waiting on evidence; the corpus has simply never been asked.

**One finding that lowers the stakes:** `..._0922` §3 showed that if the answer is *specifications*, git already supplies content hashes, source versions and snapshot identity for free. The strongest practical argument for "database" — reproducibility machinery — evaporates.

### `OQ-8` — Do `applicability`, `normative effect`, `scope` and `temporalValidity` join `G-2 Rule`?

**Four independent derivations**, from four directions:

| Route | Document |
|---|---|
| Knowledge model — `eks_2.0` §9 Scope model | v3 |
| Enforcement — the conformance gate's Rule Contract | `..._0905` |
| Analysis — conflict detection needs scope intersection | `..._0914` |
| Tactical — `Rule`'s invariants cannot be stated without them | `..._1023` |

**Nothing else in this corpus carries that weight.** **Decidable today** — it is a modelling decision, not an evidence question, and its candidate form is specified in `..._0914` §3.

---

## 2. Decidable now — 13 questions

None depends on an unresolved question. Each needs only an act.

| # | Question | Decider | Cost |
|---|---|---|---|
| `OQ-1` | Is the PKS software? | Human authority | The root decision |
| `OQ-8` | Rule gains applicability · effect · scope · temporal validity | Human authority | One schema act; 4 derivations behind it |
| `OQ-3` | Canonical system name — PKS · KnowledgeOS · EKS | `G-7` governed act | Minutes |
| `OQ-27` | Canonical conflict-policy name | `G-7` governed act | Minutes — **live defect, §4** |
| `OQ-28` | Does `G-15 Verdict` admit `PARTIALLY_SUPPORTED`? | `G-7` governed act | Minutes |
| `OQ-7` / `OQ-14` | Disposition of `AR-1` — is rule analysis the Core Domain? | Human authority (`AFV-F4`) | **Two framings of one question** |
| `OQ-16` | Does the PKS own `System`/`Component`/`Capability`/`Dependency`? | Human authority | Strategic posture |
| `OQ-25` | Does the PKS govern read access to knowledge? | Human authority | Strategic posture; mechanism named |
| `OQ-24` | Is conflict-on-activation `HARD`, `SOFT` or `ADVISORY`? | Business decision | **Three documents assumed it blocks; nobody decided** |
| `OQ-21` | Is `PINNED_AT_DECISION` a seventh atomicity value? | Modelling | Cheap |
| `OQ-22` | May `trigger_command` be `NONE`? | Modelling | Cheap — `..._1023` §5.2 argues yes |
| `OQ-26` | Does `hardness` sit on the invariant or the command? | Modelling | Cheap |

**`OQ-7` and `OQ-14` are the same question.** v3 asked *"do `AR-1`/`AR-2` get filled?"*; `..._0914` §2 asked *"is Rule Analysis the Core Domain?"* — and `AR-1` is exactly where the Rule model lands. **They should be merged and decided once.** Recorded as the register's first correction.

---

## 3. Blocked

### By `OQ-1`

| # | Question |
|---|---|
| `OQ-17` | Consistency boundary — git commit or database transaction? *(this is `OQ-1` restated tactically)* |
| `OQ-19` | How many aggregates does V1 need? Fifteen is a hypothesis |
| `OQ-11` | Should the intake practice be mechanized, and when does mechanizing cost more than it protects? |
| — | The hexagonal stack (`..._0922` §13 Part B; `..._1029` §11.6) |
| — | Code-level DDD gates (`..._0905` §4) |
| — | `EvidenceRecord` and `ProjectionBuild` field lists |
| — | **All 32 matrix rows' `status`** — no test can exist, so none may reach `Accepted` |

### By `OQ-8`

| # | Question |
|---|---|
| `OQ-12` | `NormativeEffect` vocabulary — `MUST/MUST_NOT/SHOULD/SHOULD_NOT/MAY` or `REQUIRED/PROHIBITED/…` |
| `OQ-13` | Does `SHOULD(X)` vs `MUST_NOT(X)` resolve by strength or route to review? *(also needs `OQ-12`)* |
| `OQ-15` | Does `temporalValidity` extend the 3-class lifecycle or sit beside it? |
| `OQ-29` | Which policies are `override: RECORDED_ACT`? *(also needs `OQ-24`)* |

### By work not yet done

| # | Question | What it needs |
|---|---|---|
| `OQ-4` | Re-partition the certified four contexts? | A ubiquitous-language merge/split analysis of the kind `pks_progress` records for the existing four. **Five partitions now circulate; none carries one** |
| `OQ-5` | Is the Execution plane a fifth bounded context? | The same analysis |
| `OQ-20` | Per cross-aggregate invariant: pin, detect, or forbid? | Partially answerable now — 9 matrix rows await it |
| `OQ-23` | Which aggregates survive the unique-invariant test? | The completed matrix. **Two have already failed** |

### By evidence that does not exist

| # | Question | What it needs |
|---|---|---|
| `OQ-2` | One product or many organizations? | A second execution lineage — *"one instance is a record; multiple instances are evidence"* |
| `OQ-6` | Promote `G-18 Claim` from Candidate? | Evidence the 17 concepts cannot express it |
| `OQ-9` | Is the Model→Architecture link verified? | `KBI-1` found no dedicated verification and recorded it honestly |
| `OQ-10` | Certification `PROVISIONAL` → full? | *"The one thing this program cannot self-supply"* — a second lineage or external review |

---

## 4. Already answered — close it

**`OQ-18`** — *Does `AuthorityGrant` need a delegation chain and as-of-time validity?*

**Answered affirmatively** by `..._1023` §4: `INV-EXC-003` (*an exception's validity may not exceed its approver's authority*) is **unanswerable without it**. Raised as a question at 09:22, answered by building the matrix at 10:23.

**Awaiting only closure.** It is the corpus's single completed decision cycle today, and it happened because someone built the artifact rather than discussed it further.

---

## 5. The defect that is compounding

`OQ-27` is not housekeeping. Measured:

| Concept | Names in circulation |
|---|---|
| Conflict policy | `RuleAnalysisService` · `RuleConflictDetector` · `RuleConflictPolicy` |
| Authority policy | `AuthorityEvaluator` · `RuleApprovalPolicy` |
| Exception policy | `ExceptionValidator` · `ExceptionValidationPolicy` |

**Three concepts, seven names, one day.** v3 §3 recorded the identical failure at the system level (five names for the system) and cited the corpus's own terminology freeze against it. `..._1034` §0 found it reproduced one level down. `..._1029` §11.5 then found **two of the three drifting inside a single input document.**

`G-7 Term`: *"a ubiquitous-language entry; changes are first-class governed events."*

**Every unresolved name multiplies through the documents that cite it.** This is the cheapest decision available and the only one whose cost grows while it waits.

---

## 6. Corrections this register makes

| Correction | Effect |
|---|---|
| **`OQ-7` ≡ `OQ-14`** | Two framings of the `AR-1` disposition. Merge and decide once (§2) |
| **`OQ-17` ≡ `OQ-1` tactically** | Not an independent question; it is `OQ-1`'s consequence (§3) |
| **`OQ-18` answered** | Close it (§4) |
| **`OQ-22` near-decided** | `..._1023` §5.2 already argues yes; it needs ratification, not analysis |

Four of 29 resolve by consolidation alone — which is the register's own argument for existing.

---

## 7. Recommended sequence

```
NOW, no dependencies, minutes each
  OQ-27  canonical policy names        ← cost grows while it waits
  OQ-3   canonical system name
  OQ-28  PARTIALLY_SUPPORTED
  close  OQ-18

NOW, the two roots
  OQ-1   is the PKS software?          → unblocks 7 + all 32 row statuses
  OQ-8   what a Rule is                → unblocks 4, and 4 derivations back it

THEN, cheap once the roots are settled
  OQ-12 → OQ-13 · OQ-15 · OQ-17 · OQ-21 · OQ-22 · OQ-26

THEN, strategic posture — independent of the roots
  OQ-7/OQ-14  ·  OQ-16  ·  OQ-24 → OQ-29  ·  OQ-25

THEN, requires work
  OQ-4 · OQ-5   ubiquitous-language merge/split analysis
  OQ-20 · OQ-23 complete the matrix

DEFERRED, requires evidence that does not exist
  OQ-2 · OQ-6 · OQ-9 · OQ-10
```

---

## 8. Bottom line

Seven documents, twenty-nine questions, one answered.

The register's finding is not that the analysis was wrong — it converged, repeatedly and from independent directions, which is the strongest signal in the corpus. **The finding is that 13 of 29 questions were never blocked at all.** They have been carried forward as open because each document raised them and none was authorized to close them.

`OQ-18` shows the cycle can close: raised at 09:22, answered at 10:23, by **building the artifact rather than discussing it further**.

**The cheapest act available is `OQ-27`** — three concepts, seven names, and the only defect whose cost is measurably growing. **The most consequential is `OQ-1`**, which has never been asked, and which `..._0922` §3 makes cheaper to answer than it first appears.

---

*Consolidated from the seven 2026-08-16 documents. No new analysis; §6 lists the four questions that resolve by consolidation alone.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
