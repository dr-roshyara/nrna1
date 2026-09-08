# Epistemic Contract — Formal Reconstruction

All quotes from seq 0583, read in full (`01`).

## The core formal object

> `EC = (R, Γ, A, V)` where `R` = requirements; `Γ` = satisfaction rules; `A` = authority/provenance;
> `V` = validity/version. (§25E.5)

Refined later in the same document (§25E.37) to a nine-tuple: `EC = (Requirements, Rules, Authority,
Scope, Time, Dependencies, Exceptions, Provenance, Version)` — the document's own text treats this as
a "provisional" refinement, not a replacement of the four-tuple.

## `DeriveContract(G,S) → EC_G`

A named function taking a Goal and a set of governing sources `S = {Constitution, Policy, ADR, Rule,
Scope, HumanInstruction, RiskModel, DomainModel, Law, Standard}` (§25E.2) and producing the contract.
An authority relation `Authority(s₁) > Authority(s₂)` is required, explicitly **not** hard-coded
universally — "the organization must define it" (§25E.3).

## The five requirement types (§25E.14) — the section most directly relevant to this search

| Type | Predicate | Example |
|---|---|---|
| Knowledge | `Known(r)` | `TargetVersionKnown` |
| Evidence | `EvidenceExists(r)` | `BackupEvidenceAvailable` |
| **Validation** | **`Validated(r)`** | `RestoreTestPassed` |
| **Governance** | **`Authorized(r)`** | `ArchitectureApprovalGranted` |
| Operational | `State(r)` | `TargetEnvironmentProvisioned` |

Explicitly stated as **five distinct types**, later (§25E.23) recorded in the document's own proposed
"Ubiquitous Language" as five separate DDD terms: `KnowledgeRequirement`, `ValidationRequirement`,
`GovernanceRequirement` (and, implicitly, Evidence/Operational counterparts) — kept grammatically and
conceptually parallel, never nested or subsumed.

## Contract closure and evaluation

`Closed(EC)` requires every requirement to carry a semantic definition, authority, satisfaction rule,
scope, validity, and dependency definition (§25E.20) — `UndefinedRequirement ⇒ InvalidContract`
(§25E.21). `EvalRequirement(K,r,C) → Status` and `EvalContract(K,EC,C) → ContractStatus ∈
{Ready, Blocked, Invalid, Indeterminate}` (§25E.27).

## `CandidateRequirement ≠ ContractRequirement`

An LLM-proposed requirement is not automatically binding — it must pass four named governed checks
(`SourceExists`, `SourceApplicable`, `AuthorityValid`, `RuleSatisfied`, §25E.13) before becoming a
`ContractRequirement`. Explicitly named "a very important AI governance invariant" (§25E, worked
example §25E.30).

## What the document itself says remains unresolved

*"We can represent and compute an Epistemic Contract, but we still need a rigorous conflict/
authority algebra for deriving the contract when governing sources disagree"* (§25E.38, the
document's own verdict: "PASS, with one major unresolved area"). The document closes by opening a
further, unexecuted "Step 25F — Governance Conflict Algebra" (§25E.41) as the next research step.
