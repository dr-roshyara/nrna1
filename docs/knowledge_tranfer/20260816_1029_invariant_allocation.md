# Invariant Allocation — Aggregate · Domain Service · Application Service · Governance

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — the allocation procedure behind the matrix's `candidate_owner` and `enforcement_mechanism` columns |
| **Input adjudicated** | The Aggregate-vs-Domain-Service input (conversation, 2026-08-16), incl. its external citation (`github.com/SAP/curated-resources-for-domain-driven-design`) — recorded as **input, evidence-graded by its author, not verified here** |
| **Companions** | `..._0841_target_architecture_v3.md` · `..._0905_architecture_conformance.md` · `..._0914_rule_model_and_conflict_analysis.md` · `..._0922_aggregate_boundaries.md` · `..._1023_invariant_aggregate_matrix.md` |
| **Date** | 2026-08-16 |

> **Why this is a separate artifact and not a sixth matrix revision.** The matrix is a *table of invariants*. This is the *decision procedure* that fills two of its columns. Folding a procedure into a data table is how instruments become unreadable — and §7 lists exactly what the matrix must change as a result, so neither goes stale.

---

## 0. The gap this closes

The matrix classifies enforcement into aggregates, value objects, domain services and read models. **It has no application-service layer at all** — yet its own `INV-RULE-002` worksheet already reaches for one: *"application service checks validity via authority port."* An enforcement category was being used that the classification did not contain.

The input supplies the missing distinction, and it is the sharpest line in the whole input:

> **The application service coordinates. The domain service reasons. The aggregate protects its own state.**

Adopted as the allocation rule's spine.

---

## 1. The four layers

Adopted from the input's V1 recommendation, with the corpus's authority invariant added as the fourth line's binding constraint.

```
Rule aggregate           protects its own validity          — intrinsic, atomic
RuleConflictDetector     determines logical compatibility   — cross-aggregate reasoning
AuthorityEvaluator       reports recorded permission        — see §4, corrected
ConflictResolution       records an authorized disposition  — governance
Application service      coordinates the use case           — loads, sequences, commits
Projection process       propagates to graph/search/AI      — derived, rebuildable
```

> **`ALLOC-1`** — *Aggregates enforce intrinsic, atomic validity. Domain services analyse cross-aggregate relationships. Application services coordinate commands and transaction boundaries. Governance workflows resolve what analysis cannot authoritatively decide.*

---

## 2. The ownership test

The input's single-sentence test replaces the six-question form in matrix §3. It is better because it names the two things that must co-occur — **authority and information** — and the corpus's failures have always been one without the other.

> **"If this condition is false, which single object has the authority *and* the information to reject the command?"**

| Answer | Allocation |
|---|---|
| One aggregate root | Invariant lives on that aggregate |
| Several aggregates | Domain service or application process |
| Requires external facts | Port; record the evaluation as an assessment |
| Requires human authority | Governance workflow |
| Computed from a snapshot | Analysis result, not an aggregate invariant |

**Why the pairing matters here.** `INV-RULE-002` splits precisely along it: the `Rule` aggregate has the *information* to see that an authority reference is absent, so presence is atomic and local. It does not have the *authority* to judge whether the referenced grant is valid. One condition, two owners — which is why matrix §5.1 had to split its `atomicity` field, and now has a principled reason to.

---

## 3. Hardness — the classification the matrix lacks

The input's strong / cross-aggregate / advisory / external-precondition taxonomy is **not** a restatement of the matrix's `atomicity` vocabulary. `atomicity` says *when* a condition can be evaluated. **Hardness says whether the business wants it to block.** Both are needed, and conflating them is how an advisory check silently becomes a gate.

> **New field: `hardness` — `HARD` · `SOFT` · `ADVISORY`**

| Value | Meaning | Effect on the command |
|---|---|---|
| `HARD` | Must be true before commit | Blocks |
| `SOFT` | May proceed, but a finding must be raised | Commits; produces a `Finding` |
| `ADVISORY` | Informs; never controls lifecycle | Commits; informational only |

Worked on the input's own examples:

| Condition | `atomicity` | `hardness` |
|---|---|---|
| A rule cannot be `ACTIVE` without an authority reference | `ATOMICALLY_REQUIRED` | `HARD` |
| A rule cannot be activated if it conflicts with an active rule | `ANALYTICAL` | **business decision — `HARD` or `SOFT`, and it must be chosen explicitly** |
| This rule may duplicate an existing rule | `ANALYTICAL` | `ADVISORY` |
| The rule applies only if the target service is deployed in production | `EXTERNAL_PRECONDITION` | `ADVISORY` at definition time |

**This resolves an open row.** Matrix `INV-RULE-009` (*exactly one authoritative home per rule*) carried `failure_behavior: REVIEW`, which was imprecise. It is **`ADVISORY`** — a duplicate-detection finding, never a block. The input names the case exactly: *"produce a finding; do not block."*

**And it makes one choice unavoidable rather than accidental.** Whether conflict blocks activation is a *business* decision that nobody has made. Recorded as `OQ-24`.

---

## 4. `AuthorityEvaluator` — corrected

The input describes it as determining *"whether an actor is authorized to activate a rule or grant an exception."* **That phrasing crosses this corpus's hardest line.**

v3 §9: *the mechanism records authority; it never grants it.* `INV-A3`: *absence is never permission.* `how_to_work_with_sessions` §22: the goal is a system **capable of refusing work outside its authority** — the resolver returned `UNASSIGNED / operable: false`, and that is a *report of an absent record*, not a machine denial.

**Correction adopted:**

> `AuthorityEvaluator` answers exactly one question: **"Does a recorded grant exist that covers this principal, capability, scope and instant?"** It reports `FOUND` or `NOT_FOUND`. It never concludes *authorized* or *unauthorized*.

The difference is not pedantic. `NOT_FOUND` and *unauthorized* diverge whenever a grant exists but was not recorded — and the corpus's whole authority model exists because that case is real. An evaluator that returns *unauthorized* has converted a gap in the record into a judgement about a person.

This also settles its layer: `AuthorityEvaluator` is a **domain service over recorded grants**, never a policy engine.

---

## 5. Anti-patterns, recorded as prohibitions

Both are adopted as design-time prohibitions, in the same class as `INV-RULE-007`:

> **`ALLOC-2`** — No `RuleService.updateRule(...)`. *"If the logic describes how a rule changes itself, it belongs on `Rule`."* A service that mutates an aggregate's own state relocates the invariant away from the object that has the information to enforce it.

> **`ALLOC-3`** — No `KnowledgeDomainService`. A domain service represents **one coherent domain operation** — `RuleConflictDetector`, `AuthorityEvaluator`, `ExceptionValidator`, `ApplicabilityEvaluator`. A service named after the domain is a procedural god object waiting to happen.

`ALLOC-3` matters more here than in a typical codebase: `eks_2.0` §7 proposed a `Knowledge Governance API`, an `Evidence & Assessment API` and a `Decision & Rule API` — three services named after contexts rather than operations. v3 §11.2 declined them on precision grounds; `ALLOC-3` declines them again on allocation grounds, independently.

---

## 6. The activation flow — third statement, same correction

The input restates the flow and again ends at *"activation allowed, blocked, or sent to review."* The correction from matrix §6.3 stands unchanged:

> A mechanism that emits **allowed** has granted authority. It may report `PROVEN_CONSISTENT`, `PROVEN_CONFLICT` or `UNKNOWN`. The activating act remains human (`INV-RULE-008` — no direct creation→authoritative edge).

Everything else in the input's treatment is adopted, including both timings:

```
HARD:   RuleActivationRequested → candidates → analysis
        → PROVEN_CONSISTENT | PROVEN_CONFLICT | UNKNOWN
        → application service commits or refuses, pre-commit

SOFT:   RuleActivated → ConflictAnalysisRequested
        → ConflictDetected → GovernanceReviewRequired
```

And the constraint that survives both: **the detector never becomes part of the `Rule` aggregate** — synchronous checking is an *application-service* choice about transaction boundaries, not a relocation of domain logic.

**`UNKNOWN` is a first-class outcome in both timings.** Under `INV-A3` it never satisfies a `HARD` gate.

---

## 7. What the matrix must change

| Matrix element | Change |
|---|---|
| §1 record | **+ `hardness`** (§3) · **+ `enforcement_layer`** — `AGGREGATE` · `VALUE_OBJECT` · `DOMAIN_SERVICE` · `APPLICATION_SERVICE` · `GOVERNANCE_WORKFLOW` · `PROJECTION_PROCESS` |
| §3 assignment test | Superseded by the single ownership test (§2). The six questions were an unordered checklist; the one question forces an answer |
| §7 classification | **+ an application-service layer** — the matrix had none, while already relying on one |
| `INV-RULE-009` | `failure_behavior: REVIEW` → **`hardness: ADVISORY`** (§3) |
| `INV-RULE-010`, `INV-EXC-003`, `INV-EXC-007` | Owner refined: `AuthorityEvaluator` **reports**; the *deciding* layer is `GOVERNANCE_WORKFLOW` (§4) |
| `INV-ANL-002` | Confirmed on `RuleConflictDetector` — third independent placement |

**Unchanged, and worth stating because this input does not address it:** the `PINNED_AT_DECISION` finding and `reconstructable_as_of`. The input allocates *where* an invariant is checked; it is silent on *whether it remains checkable afterwards*. Nine matrix rows still need a pinned referent version.

---

## 8. A gap this input opens

One row in its classification table has no counterpart anywhere in the corpus:

> *"Query returns only permitted knowledge → Policy service at retrieval boundary."*

**Nothing in the folder addresses read-side access control.** Every invariant discussed across five documents governs *writing* knowledge — creation, activation, approval, exception, resolution. None governs *who may read what*.

This is not a small omission for a system whose delivery plane includes `AIContextPackage`. An AI context assembled without a retrieval policy will faithfully deliver whatever it can reach — and the corpus's own authority model has no opinion on reach.

Recorded as **`OQ-25`**. It is a strategic question, not a tactical one: it may imply a boundary the certified context model does not have.

---

## 9. Open questions added

| # | Question | Settled by |
|---|---|---|
| **OQ-24** | Is conflict-on-activation `HARD`, `SOFT`, or `ADVISORY`? | A business decision. Three documents have now assumed it blocks without anyone deciding (§3) |
| **OQ-25** | Does the PKS govern read access to knowledge, and if so at which boundary? | Human authority — the corpus is entirely silent (§8) |
| **OQ-26** | Does `hardness` belong on the invariant, or on the *command* that could violate it? The same invariant may block one command and merely warn on another | Modelling decision (§3) |

---

## 10. Bottom line

The input is largely confirmatory — its domain-service list, value-object list, and cross-aggregate ruling match the matrix row for row, which is a fourth independent arrival at the same placement. Four things are new:

- **An application-service layer the matrix lacked** while already depending on one.
- **`hardness`**, which is orthogonal to `atomicity` and forces `OQ-24` into the open: *nobody has decided whether conflict blocks activation.*
- **The ownership test's pairing of authority and information** — which is why `INV-RULE-002` has two owners and had to be split.
- **`OQ-25`** — the corpus governs writing knowledge and says nothing about reading it.

And one correction: **`AuthorityEvaluator` reports whether a covering grant is recorded. It does not determine authorization.** `NOT_FOUND` is not *unauthorized*, and this corpus exists because that difference is real.

---

---

## 11. Addendum — Domain Service vs Application Service (input, 2026-08-16 10:40)

A further input on the same allocation question. Filed here rather than as a separate document: it is the **same topic refined**, and a second document on one topic is the `DUPLICATES` violation the matrix exists to detect. External citations (`stackoverflow.com`, `docs.synapsestudios.com`) recorded as unverified input.

**Largely confirmatory.** Its comparison table, its layer question (*"What does this domain rule mean?"* vs *"How does this use case run?"*), and its closing rule (*"Domain services decide domain meaning; application services coordinate domain activity"*) restate §0–§1 above. That is now the **sixth** independent statement of `ALLOC-1`.

### 11.1 Adopted — Option A, and why it matters more here than generally

The input offers two treatments for a domain service that needs external domain facts, and recommends the first **for KnowledgeOS specifically**:

```
Option A   application service loads the inputs; the domain service stays pure
             candidates = rule_query.find_possible_conflicts(rule)
             analysis   = conflict_policy.analyze(rule, candidates)

Option B   domain service depends on a domain port (RuleCandidateProvider)
```

> *"For KnowledgeOS, Option A is preferable initially **because it makes the analysis snapshot explicit and easier to reproduce**."*

**Adopted — and the stated reason is an independent arrival at `INV-ANL-001`.** The matrix requires every result to be bound to a fixed input snapshot; `..._1034` §2 requires `policy_version` so a decision stays explainable. Option A makes the snapshot a visible parameter instead of a hidden fetch. Under Option B, *what was analysed* is decided inside the service and cannot be reconstructed from the call.

> **`ALLOC-4`** — A policy receives its inputs; it does not fetch them. Candidate selection is an application-service responsibility, so that every analysis states the world it was true of.

### 11.2 Adopted — the four diagnostic tests

Complementary to §2's ownership test, which asks *who* enforces; these ask *which layer*:

1. **Would the operation still make sense without this use case?** Yes → domain service. No → application service.
2. **Does it make a business decision?** Yes → aggregate or domain service. No, only sequencing → application service.
3. **Does it know about HTTP, persistence, transactions, messaging?** Yes → application service or adapter.
4. **Can one aggregate enforce it from its own state?** Yes → aggregate.

Test 1 is the most useful and is new: `RuleConflictPolicy` serves activation, review, reporting and scheduled analysis, so it is domain-level; `ActivateRuleHandler` exists only to run one use case.

### 11.3 Adopted — four anti-patterns, extending `ALLOC-2`/`ALLOC-3`

`Fat application service` (domain semantics duplicated in the handler) · `Anemic domain service` (a repository wrapper) · `Domain service as workflow engine` · and:

```python
class Rule:
    def activate(self, all_active_rules, all_authorities, all_systems): ...
```

**`Aggregate as global registry` is `INV-RULE-007` made concrete.** The matrix states the prohibition abstractly; this is what violating it looks like in code, and it is worth keeping precisely because the signature looks reasonable at a glance.

### 11.4 Correction held — third occurrence of `ALLOW`/`DENY`

The worked handler contains `if not decision.allowed: return ActivationResult.rejected(...)`. §4 above stands: a policy reports `COVERING_GRANT_FOUND` / `NO_COVERING_GRANT` / `SOD_VIOLATION` / `REVIEW_REQUIRED`. `NO_COVERING_GRANT` is a statement about the record, not a denial of a person.

Two further points on that handler, both `INV-RULE-008`: `rule.activate(...)` invoked directly after a policy returns is a **mechanism completing an authority transition**. The activating act stays human; the handler may prepare and refuse, never grant.

### 11.5 New evidence for `OQ-27` — the drift is now visible inside one input

This input names the same two concepts twice each, in different sections:

```
authority policy   AuthorityEvaluator      ·  RuleApprovalPolicy
exception policy   ExceptionValidator      ·  ExceptionValidationPolicy
conflict policy    RuleConflictDetector    (+ RuleConflictPolicy, RuleAnalysisService elsewhere)
```

`..._1034` §0 recorded three names for the conflict policy across three documents. **The same drift now appears within a single document, for three concepts.** That raises `OQ-27` from a tidy-up to a live defect: naming is diverging faster than it is being adjudicated, and every unresolved name multiplies through the documents that cite it.

### 11.6 Blocked — the layering stack

`Interface / Application / Domain / Infrastructure`, with PostgreSQL, search, graph, solver, identity provider and event bus, is the hexagonal stack of `..._0922` §13 Part B. **Blocked on `OQ-1`**, unchanged.

---

*Adjudicated against `docs/knowledge_tranfer/` and the five prior 2026-08-16 documents; §11 added for a further input the same day. Inputs and their external citations are recorded as **input**; this document is the assessed artifact they produce. §7 lists the corrections the matrix requires.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
