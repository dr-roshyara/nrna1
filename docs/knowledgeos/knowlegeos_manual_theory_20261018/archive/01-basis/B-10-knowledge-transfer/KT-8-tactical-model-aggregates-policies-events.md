# KT-8 — The tactical model: aggregates, invariant matrix, allocation, policies, events (2026-08-16, 09:22–10:55)

| Field | Value |
|---|---|
| Read (full) | `20260816_0922_aggregate_boundaries.md` (240 l.) · `20260816_1023_invariant_aggregate_matrix.md` (393 l.) · `20260816_1029_invariant_allocation.md` (266 l.) · `20260816_1034_policy_catalogue.md` (233 l.) · `20260816_1044_domain_events.md` (429 l.) |
| Also noted | `Constitutional Governance Platform.md`: **0 bytes** (sha256 e3b0c442…b855, the empty-file hash). Nothing to extract |
| Standing of the sources | all **Generated · PROPOSED**; each adjudicates an outside conversation input (external web citations recorded as unverified) |
| Standing here | engineering / PKS background. **Not** KnowledgeOS corpus |
| Labels | [SRC] stated in the source · [I] my interpretation · [NEW] my deduction · [?] open |

## 1. The organizing question

- **[SRC]** *"Which object owns this invariant, which change may alter it, and which facts may lag?"* (0922 §13)
- **[SRC]** It replaces "another component diagram". Contexts had moved 4 → 6 → 7 → 4 and containers 12 → 10 with no new evidence (0922 §0).
- **[SRC] Ordering:** OQ-8 (what a Rule is) comes first, then the invariant matrix, then aggregate boundaries, then OQ-1/OQ-4 (0922 §10).
- **[SRC]** *"Four independent derivations of one gap"* lead to OQ-8 (0922 §13).

## 2. Findings → act: the analyser/disposition split

- **[SRC]** Two vocabularies, never one list (0922 §7):
  - **Relationship** = what the analyser *found*: CONSISTENT · SPECIALIZES · REFINES · DUPLICATES · OVERLAPS · CONFLICTS · UNKNOWN.
  - **Disposition** = what the authority *decided*: CONFIRMED_CONFLICT · NOT_A_CONFLICT · RULE_A/B_SUPERSEDES · EXCEPTION_GRANTED · RULE_REFINED · INSUFFICIENT_INFORMATION · ACCEPTED_RISK.
- **[SRC]** SUPERSEDES and EXCEPTION_TO move to the disposition side: *"neither is detectable; both are decisions."*
- **[SRC]** A conflict report is a **projection / read model**; it may never be cited as authority (0922 §4; 1023 §6.1).

## 3. Authority is temporal, and a report is not the act

- **[SRC] INV-A1:** *"A report of an act is not the act."* An audit event records that something happened; it does not own the transition (0922 §9).
- **[SRC]** Five separate things: domain state · domain events · audit events · provenance records · projection build records (0922 §9).
- **[SRC]** Authority becomes temporal: *"was the authority valid at the time of approval?"* (0922 §5).
- **[SRC] Pinning.** `reconstructable_as_of` records the referent versions pinned at the deciding command (1023 §1). A proposed seventh atomicity value is **PINNED_AT_DECISION** (OQ-21).
  - *"Pinning has now arrived from four directions"*: invariant checking, policy versioning, event payloads, idempotency keys (1044 §13.2).
- **[SRC] INV-A3:** *"absence is never permission."* Revocation must be distinguishable from expiration (0922 §5).
- **[SRC]** The same rule appears in the delivery domain: *"Never assume that a timeout means the operation did not happen"* (1044 §13.5).

## 4. Results that are not "yes"

- **[SRC]** Policy result vocabulary: PROVEN_CONFLICT · PROVEN_CONSISTENT · UNKNOWN · NOT_APPLICABLE · **TIMEOUT** (1034 §3).
  - *"UNKNOWN, NOT_APPLICABLE and TIMEOUT never satisfy a HARD gate. Only PROVEN_CONSISTENT does."*
- **[SRC]** MARK_UNKNOWN *"is never a pass"* (1023 §2.2).
- **[SRC]** INV-ASM-001: a verdict cites ≥1 evidence record, **or states explicitly that it is evidence-free and provisional** (1023 §4.4).
- **[SRC]** INV-EXC-004: validity is evaluated at **read time**, against the asking instant. *"A stored `is_valid` boolean is the bug this row exists to prevent"* (1023 §5.2).

## 5. A mechanism reports; it never authorizes

- **[SRC]** AuthorityEvaluator answers only *"does a recorded grant cover this principal, capability, scope and instant?"* It returns FOUND / NOT_FOUND, never authorized / unauthorized (1029 §4).
  - *"NOT_FOUND and unauthorized diverge whenever a grant exists but was not recorded."*
- **[SRC]** Corrected approval vocabulary: COVERING_GRANT_FOUND · NO_COVERING_GRANT · SOD_VIOLATION · REVIEW_REQUIRED (1034 §4).
- **[SRC]** "ActivationAllowed" is removed from the activation flow, because *"a mechanism that emits allowed has granted authority"* (1023 §6.3).
- **[SRC] Refinement:** an application service may **refuse**, and may **execute an approval already recorded**. It may not **approve** (1044 §5).
  - The pattern is `rule.activate(command.approval_reference)`.
  - A regression was found when the policy itself minted the reference. The proposed fix is INV-RULE-011 / OQ-33: the reference must resolve to a *recorded* act (1044 §12.5).

## 6. Allocation and layering

- **[SRC] ALLOC-1:** aggregates protect intrinsic, atomic validity · domain services reason across aggregates · application services coordinate · governance decides (1029 §1).
- **[SRC] Ownership test:** *"which single object has the authority **and** the information to reject the command?"* (1029 §2).
- **[SRC] Hardness** (HARD · SOFT · ADVISORY) is orthogonal to atomicity. *"Nobody has decided whether conflict blocks activation"* (OQ-24) (1029 §3).
- **[SRC]** Override is a separate field (NONE | RECORDED_ACT). A UI confirmation is a report, not an act (1034 §7).
- **[SRC] ALLOC-4:** *"A policy receives its inputs; it does not fetch them,"* so every analysis states the world it was true of (1029 §11.1).
- **[SRC] EVT-1:** no event handler may evaluate an ATOMICALLY_REQUIRED invariant. *"Handlers act on facts; they do not validate them"* (1044 §2).
- **[SRC]** Specification layer (a composable predicate) vs Policy (a decision plus reason codes plus `policy_version`).
  - POL-1: a decision without `policy_version` is *"a report of an outcome, not a reconstructable act"* (1034 §1–2).

## 7. Two findings about the corpus itself

- **[SRC] Naming drift inside the corrective documents:** RuleAnalysisService / RuleConflictDetector / RuleConflictPolicy, *"three names for one concept … in a single day"* (OQ-27).
  - *"Terminology does not stay stable by intention"* (1034 §0, §12).
- **[SRC] Design outruns the decision.** *"OQ-1 is now referenced 49 times"*; *"design is accumulating faster than the decision it rests on"* (1044 §13.0).
- **[SRC]** Under the "specifications" answer to OQ-1, git already gives most of the machinery for free (EVT-2): *"the commit log is the outbox."* This finding recurs four times (0922 §3; 1044 §12.1, §13.4).
- **[SRC]** The folder's own event chain `DecisionApproved → KnowledgeUpdated → …` is ruled the anti-pattern and superseded (1044 §0).

## 8. Contribution to the basis

| # | Contribution | Kind |
|---|---|---|
| KT8-1 | **Finding ≠ disposition.** What a mechanism detects and what an authority decides are separate vocabularies | [SRC] |
| KT8-2 | **Report ≠ act (INV-A1); absence ≠ permission (INV-A3).** Both are restated at every tactical layer | [SRC]; matches B-06 principles 1, 7, 10 |
| KT8-3 | **Pinning:** a claim stays checkable only if it records the versions of what it read | [SRC] |
| KT8-4 | **Non-pass results:** UNKNOWN · NOT_APPLICABLE · TIMEOUT · MARK_UNKNOWN · "evidence-free and provisional" are distinct values and never count as a pass | [SRC] |
| KT8-5 | **[NEW] Lead for the corpus Zero/Gap reading.** These documents distinguish at least four "empty" outcomes: no conflict found (UNKNOWN) · not applicable · not decided in time (TIMEOUT) · proven consistent. SC-2 (KT-6) separately forbids treating unspecified as all. If the corpus's two "Zeros" map onto this distinction, it would be an engineering antecedent of Zero/Gap. **This is a lead to test against the corpus, not a finding** | [NEW] |
| KT8-6 | **[?]** All five documents are PROPOSED. No ratification record was found for any of ALLOC-1…4, EVT-1…5, POL-1, INV-A1/A3 in this form | open |
