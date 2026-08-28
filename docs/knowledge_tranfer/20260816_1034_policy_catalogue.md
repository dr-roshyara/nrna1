# Domain Policies — Catalogue, Result Contract, and a Naming Defect

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — the policy layer of `..._1029_invariant_allocation.md` §1 |
| **Input adjudicated** | The Business-Policy-as-Domain-Service input (conversation, 2026-08-16), incl. external citations (`medium.com/inbank`, `enterprisecraftsmanship.com`, `javaomnibus.org`, `learnixo.io`) — recorded as **input, evidence-graded by their authors, not verified here** |
| **Companions** | `..._0841` v3 · `..._0905` conformance · `..._0914` rule model · `..._0922` aggregates · `..._1023` matrix · `..._1029` allocation |
| **Date** | 2026-08-16 |

---

## 0. A defect found while adjudicating this input

Measured across today's documents:

```
RuleAnalysisService     5 occurrences   (0914 §11)
RuleConflictDetector    7 occurrences   (1023 §7, INV-ANL-002; 1029 §1)
RuleConflictPolicy      — this input
```

**Three names, one concept, three documents, one day.**

v3 §3 recorded exactly this failure at the system level — PKS / KnowledgeOS / EKS / Engineering Knowledge System / Engineering Knowledge Operating Platform — and cited the corpus's own terminology freeze against it. **The same drift has now reproduced one level down, in documents written to prevent it.** `G-7 Term`: *"a ubiquitous-language entry; changes are first-class governed events."*

**Resolution proposed — `RuleConflictPolicy`.** It is the most accurate of the three: `…Service` says nothing about what it does; `…Detector` implies it finds conflicts, when in fact its most common correct answer is that there is none. `…Policy` names a decision made under stated rules, which is what it is. Recorded as `OQ-27`; the other two names are frozen pending it, and this document uses the proposed name throughout.

That the defect appeared *inside* the corrective documents is the finding worth keeping. Naming drift is not a documentation problem — it is what happens by default.

---

## 1. Specification — a layer the corpus lacked

The input's clearest structural contribution:

> **Specification** — a reusable boolean predicate: `ProductionScopeSpecification` · `ActiveRuleSpecification` · `ApplicableToPaymentsSpecification`
> **Policy** — combines specifications, makes a decision, calculates a result, and **explains it**

Matrix §7 had aggregates, value objects, domain services and read models. **No specification layer** — and it needed one, because `0914` §3 had already ruled that `applicability` is *"a **predicate**, not a label."* A predicate with no home is why applicability kept drifting between a field on `Rule` and a service concern.

**Adopted:**

```
Specification      composable predicate, returns boolean, no explanation
Policy             composes specifications, returns a decision + reason codes
```

`ApplicabilityExpression` is now placed: it is a **specification** built inside `Rule` (the aggregate owns its well-formedness — `INV-RULE-001`), and **evaluated** by `RuleApplicabilityPolicy` against a context the aggregate never sees. That is the `0922` §2 definition/evaluation split, finally with a pattern name for each half.

---

## 2. The result envelope

Adopted, and generalized to every policy:

```text
PolicyResult
├── domain inputs           (by identity + version)
├── decision / calculation
├── reason codes
├── explanation
├── uncertainty status
└── policy version
```

**`policy_version` is the load-bearing field**, and the input supplies it where the matrix had it in only one place. Matrix §1 requires `reconstructable_as_of` so an invariant can be re-checked later against the state as it was. A policy decision is reconstructable only if **both** the inputs *and the deciding logic* are versioned — a policy whose rules changed silently makes every prior decision unexplainable, exactly as an unpinned base rule silently rewrites every exception granted under it (`INV-EXC-002`).

> **`POL-1`** — Every policy result records its `policy_version`. A decision without one is a report of an outcome, not a reconstructable act (`INV-A1`).

**`explanation` and `reason_codes` are not ergonomics here.** `0914` §5 ruled that the value of the satisfiability test is that it yields *the region over which a contradiction holds*, not a boolean. `how_to_work_with_sessions` §15 distrusts *"all tests passed."* A policy that returns a verdict without reason codes has failed the corpus's own evidence standard.

---

## 3. Result vocabulary — two additions

```
PROVEN_CONFLICT · PROVEN_CONSISTENT · UNKNOWN · NOT_APPLICABLE · TIMEOUT
```

`NOT_APPLICABLE` and **`TIMEOUT`** are new. `TIMEOUT` is a genuine distinction: a solver that gave up is not the same as a comparison that is undecidable, and collapsing them loses the only signal that the analysis is under-resourced rather than the domain ambiguous.

**Binding, per `INV-A3` (*absence is never permission*):**

> **`UNKNOWN`, `NOT_APPLICABLE` and `TIMEOUT` never satisfy a `HARD` gate.** Only `PROVEN_CONSISTENT` does. Matrix §2.2's `MARK_UNKNOWN` rule extends to all three.

This matters most for `TIMEOUT`, which is the one an impatient implementation is most tempted to treat as a pass.

---

## 4. `RuleApprovalPolicy` — the correction holds, and gains something

The input has it return **`ALLOW` / `DENY` / `REVIEW_REQUIRED`**. `..._1029` §4 ruled thirty minutes earlier that an authority evaluator reports whether a covering grant is **recorded**, and never concludes *authorized*. That ruling stands, and the reason is unchanged:

> `NOT_FOUND` and *unauthorized* diverge whenever a grant exists but was not recorded — and this corpus's entire authority model exists because that case is real. A policy returning `DENY` has converted a gap in the record into a judgement about a person.

**Corrected return vocabulary:**

```
COVERING_GRANT_FOUND      a recorded grant covers principal · capability · scope · instant
NO_COVERING_GRANT         no such record — NOT a denial
SOD_VIOLATION             a recorded separation-of-duties constraint is breached
REVIEW_REQUIRED           routed to human authority
```

**What the input adds, and it is genuinely new:** *separation-of-duties constraints* as a policy input. The corpus already holds SoD as an invariant — `how_to_work_with_sessions` §5: verification *"must not verify work it implemented itself"*; §17 lists *"verifier verifies its own work"* as a STOP condition — but nothing had ever placed it in the tactical model. `SOD_VIOLATION` is the first mechanical form of a rule the corpus has stated since its earliest documents.

Note the asymmetry that makes the vocabulary coherent: `SOD_VIOLATION` **is** a machine-decidable finding, because it is a statement about *records* (this principal produced that artifact), not about entitlement. Reporting it is not deciding authority.

---

## 5. `ContextAssemblyPolicy` — this answers `OQ-25`

`..._1029` §8 raised, thirty minutes ago, that the corpus governs **writing** knowledge and is entirely silent on **reading** it — no invariant anywhere says who may see what, while the delivery plane includes `AIContextPackage`.

The input independently supplies the shape:

```text
ContextAssemblyPolicy
  evaluate(actor, task, knowledgeSet) → ContextPackage
```

**Second independent arrival at the same gap, from opposite directions** — mine by noticing an absence, the input's by enumerating a catalogue. `OQ-25` moves from *unrecognized* to *named with a proposed mechanism*.

Two constraints follow immediately from the corpus and must be recorded with it:

- **The policy filters; it never elevates.** An assembled context may contain less than the actor could reach, never more. Assembly is a projection (`DR-1`, `L4-8`) — nothing may depend on it, and it carries no independent semantic identity.
- **Provenance survives assembly.** `authored_by` (v3 §5.3) must travel into the package. A context bundle that strips provenance hands an AI generated material indistinguishable from authoritative material — which the Knowledge-Constitution's rule that AI output never becomes authoritative without review exists to prevent.

**`OQ-25` remains open** — this names a mechanism, it does not decide policy. Whether the PKS governs read access at all is still a human decision, and it may imply a boundary the certified context model does not have.

---

## 6. `ProjectionFreshnessPolicy` — names a mechanism that was vague

Matrix `INV-PRJ-002` (*a stale or failed projection must not be represented as current*) carried enforcement *"reconciliation process"* — a placeholder. The input supplies inputs and output:

```text
ProjectionFreshnessPolicy
  evaluate(projection, sourceSnapshot) → FreshnessDecision
```

Adopted. And it composes with `0922` §3's finding: if the PKS remains specifications, `sourceSnapshot` is a commit SHA and freshness is a SHA comparison — the cheapest possible implementation of an invariant that otherwise needs bespoke machinery.

---

## 7. Duplication exposes a gap in `hardness`

The input's rule:

> *"A new rule should not be activated if an equivalent active rule already exists in the same scope, **unless the user explicitly confirms the duplication**."*

`..._1029` §3 classified `INV-RULE-009` as `ADVISORY` — finding, never blocks. The input describes something different: **blocks unless overridden.** Neither `HARD` (blocks) nor `SOFT` (commits + finding) expresses it.

**Resolution: override is orthogonal to hardness, not a fourth value.**

> **New field: `override` — `NONE` | `RECORDED_ACT`**

A `HARD` gate may be overridable by an authorized, recorded act; that does not make it soft, because the default remains refusal.

**And the corpus forces a sharpening of the input's phrasing.** *"The user explicitly confirms"* is not sufficient here. Under `INV-A1` — *a report of an act is not the act* — a UI confirmation is a report. An override must be a **recorded act**, which is why the value is `RECORDED_ACT` and not `USER_CONFIRMATION`. A dialog box that dismisses a duplicate finding leaves no reconstructable trace of who decided what.

`INV-RULE-009` is therefore `ADVISORY` **or** `HARD + override: RECORDED_ACT` — a business decision, folded into `OQ-24`.

---

## 8. The catalogue, adjudicated

| Policy | Status | Where it already lives, or what is new |
|---|---|---|
| `RuleApprovalPolicy` | **adopted, vocabulary corrected** | `AuthorityEvaluator` (matrix §7) renamed; `ALLOW/DENY` → §4. **New: SoD input** |
| `RuleApplicabilityPolicy` | **adopted** | `ApplicabilityEvaluator` (matrix §7); now with its specification/policy split named (§1) |
| `RuleConflictPolicy` | **adopted as the canonical name** | Three prior names — §0, `OQ-27` |
| `ExceptionValidationPolicy` | **adopted** | Enforces `INV-EXC-001…003`, `-007`. **Its checks do not supersede the pin** (`INV-EXC-002` is `PINNED_AT_DECISION`) |
| `EvidenceAssessmentPolicy` | **adopted, vocabulary open** | `SUPPORTED / PARTIALLY_SUPPORTED / UNKNOWN / REFUTED` — **`PARTIALLY_SUPPORTED` has no counterpart in `G-15 Verdict`**; a `G-7` question, `OQ-28` |
| `RuleDuplicationPolicy` | **adopted, hardness open** | `INV-RULE-009`; §7 |
| `ContextAssemblyPolicy` | **named, policy undecided** | §5 — `OQ-25` |
| `ProjectionFreshnessPolicy` | **adopted** | `INV-PRJ-002`; §6 |

Its worked example for `EvidenceAssessmentPolicy` — the claim *"the payment bounded context is isolated"*, assessed against dependency analysis, architecture tests and an approved context map — is precisely `AD-1`'s `AC-1` pattern: observations evaluated against criteria owned elsewhere, yielding a verdict. **Independent reconstruction of the corpus's oldest accepted context.**

---

## 9. Not adopted — illustrative material

`PremiumDiscountPolicy` · `PortfolioPricingPolicy` · `ShipmentPromisePolicy` · `PatientTransferPolicy` are pedagogical examples of the pattern, not EKS concepts. Recorded so they are not later mistaken for domain vocabulary — the corpus's own lesson is that *"rejected concepts remain part of project memory with rejection rationale"* (v3 principle 4), and the rationale here is simply: different domain.

Their *shape* is adopted where it generalizes — a policy consumes **snapshots**, returns a **result**, and *"should not directly persist orders, emit emails, or update billing systems."* That is `ALLOC-1`'s coordination boundary, stated a fifth time.

---

## 10. Corrections to prior documents

| Document | Correction |
|---|---|
| `0914` §11 | **`RuleAnalysisService` → `RuleConflictPolicy`** (§0) |
| `1023` matrix §7 | **`RuleConflictDetector` → `RuleConflictPolicy`**; **+ specification layer** (§1); `AuthorityEvaluator` → `RuleApprovalPolicy` |
| `1023` matrix §1 | **+ `override`** field (§7); `policy_version` becomes required wherever a policy decides (§2) |
| `1023` `INV-ANL-002` | Result vocabulary gains `NOT_APPLICABLE` and `TIMEOUT`; none of the three non-proven values satisfies a `HARD` gate (§3) |
| `1023` `INV-PRJ-002` | Enforcement named: `ProjectionFreshnessPolicy` (§6) |
| `1029` §4 | **Holds.** `ALLOW/DENY` declined; vocabulary refined and SoD added (§4) |
| `1029` §8 | `OQ-25` gains a proposed mechanism, remains open (§5) |

---

## 11. Open questions added

| # | Question | Settled by |
|---|---|---|
| **OQ-27** | Canonical name: `RuleConflictPolicy`, `RuleConflictDetector`, or `RuleAnalysisService`? | A `G-7` governed act. Three names are in circulation today (§0) |
| **OQ-28** | Does `G-15 Verdict` admit `PARTIALLY_SUPPORTED`? | A `G-7` governed act — the corpus has no partial verdict (§8) |
| **OQ-29** | Which policies are `override: RECORDED_ACT`, and what act qualifies? | Governance (§7) |

---

## 12. Bottom line

The input is the fifth independent statement of `ALLOC-1` and the third of the anti-patterns — which is itself evidence that the allocation layer is settled. Four things are new:

- **A specification layer**, which finally places `applicability` — a predicate that had no home since `0914` §3 named it one.
- **`policy_version` on every result**, extending `reconstructable_as_of` from inputs to deciding logic. A policy whose rules changed silently makes every prior decision unexplainable.
- **`TIMEOUT`** as distinct from `UNKNOWN`, and the ruling that neither passes a gate.
- **Separation of duties** entering the tactical model for the first time, as `SOD_VIOLATION` — a rule the corpus has held since its earliest documents and never mechanized.

One correction held: **a policy reports what the record contains; it does not decide who is authorized.**

And one finding worth more than any of them: **three names for one concept appeared across three documents written in a single day, in the very documents written to prevent that drift.** Terminology does not stay stable by intention. `OQ-27`.

---

*Adjudicated against `docs/knowledge_tranfer/` and the six prior 2026-08-16 documents. The input and its external citations are recorded as **input**; this document is the assessed artifact they produce. §10 lists the corrections other documents require.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
