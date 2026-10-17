# Principal Reviewer analysis of the latest study

The attached transcript shows that Claude has completed a significant research round: the Cross-Context Consistency and Simulation Study, dated 11 October 2026. It contains a legacy representation inventory, contradiction catalogue C1–C12, a provisional invariant register, four simulation studies in `g14_cross_context_consistency.py`, and a proposed set of cross-context contracts. The study is explicitly marked as research, not ratified architecture.&#x20;

Eingefügter Text.txt



My recommendation for the next step is to validate the study independently, determine which contradictions actually occur in the available legacy data, and complete the missing Discussion Forum relationship model. We should not start integration or change the separate Membership implementation yet.

## 1. What the study has established

| Area                       | Research result                                                                                       | Principal Reviewer interpretation                                                           |
| -------------------------- | ----------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| Legacy representations     | Multiple overlapping stores for Committee, Membership, assignments, Geography, roles, and permissions | There is a genuine consistency and ownership problem to resolve.                            |
| Committee and Geography    | Committee geography is currently represented by several fields; a committee may be non-geographic     | Geography should not be forced to represent every committee.                                |
| Membership and assignments | The stores use inconsistent identity semantics and do not consistently enforce eligibility            | A canonical person reference and an agreed Membership contract are required.                |
| Position and permission    | Role limits, database constraints, and permission mechanisms disagree                                 | Holding a position must not automatically be treated as having every associated permission. |
| Historical consistency     | Geography moves and mutable assignment records can change historical interpretations                  | Current state and historical decision evidence need separate semantics.                     |
| Simulation                 | g14 compares alternative rules and reconciliation policies                                            | Useful evidence for selecting further experiments, but not proof of production correctness. |

Eingefügter Text.txt

### The most important simulation findings

- Assignment and Membership: deriving effective assignment status from the recorded assignment and Membership state avoids the modeled invariant violations and the loss of tenure caused by automatic cascading writes. Whether current Membership is actually required for every position remains an open business rule.
- Legacy reconciliation: an agree-or-queue policy preserves source evidence when representations disagree. However, the simulation uses synthetic noise assumptions, so its queue sizes are not estimates of the real legacy data.
- Committee type and Geography: the model identifies cross-organisation references as detectable from data, while type-to-geographic-level compatibility requires an approved mapping.
- Geography history: pinning the relevant geographical revision preserves historical decision replay, while successor relationships help determine current coverage after retirement. These are complementary behaviors.

Eingefügter Text.txt

## 2. What is still missing

The research is promising, but three gaps prevent us from treating the candidate architecture as ready for implementation.

1. Prevalence is unknown. We know that the legacy schema can represent several contradictions, but we do not yet know how many actual records exhibit them. The synthetic simulation cannot answer that.
2. Some findings still need independent verification. The study reports many representations and several critical mismatches. Before using those counts to justify migrations, verify the evidence and the classification of each representation.
3. Discussion Forum is not covered by the new study. Your overall target includes the relationships between Membership, Committee, roles, permissions, Geography, and the Discussion Forum. We need to establish how Forum access, posting, moderation, and authorization depend on those contexts without allowing Forum to become another authority for Membership or permissions.

The next step should therefore be a read-only verification and evidence round, not another broad redesign.

# Prompt instructions for Claude Code CLI — next research round

Use this prompt from the repository root. It deliberately separates evidence validation from architectural decisions and production implementation.

# Principal Reviewer Round 2 — Cross-Context Evidence Validation and Discussion Forum Architecture

## 1. Role and mission

Act as Principal DDD Architect, independent architecture reviewer, requirements engineer, formal-methods researcher, statistician, and software verification specialist.

We are developing a digitalisation platform for organisations and political parties. Our goal is a robust, implementable DDD architecture whose cross-context relationships are explicit, consistent, historically defensible, tenant-safe, and independently verifiable.

The previous research round produced the Cross-Context Consistency and Simulation Study, contradiction catalogue C1–C12, invariant register, proposed contracts, and g14 simulation model.

Your task is to conduct the next research round:

**Independently verify the previous study, measure the prevalence of contradictions where authorised read-only data access is available, and complete the missing Discussion Forum relationship model.**

Do not merely restate the previous report. Challenge its assumptions, reproduce its conclusions where feasible, and identify evidence that could change the architectural recommendation.

Election implementation remains out of scope. An election-adjacent security finding may be recorded when necessary to explain a shared authorization risk, but do not implement or redesign Election functionality.

A separate developer is working on the Membership context. Do not modify Membership implementation, introduce a competing Membership model, or create a second write authority.

## 2. Mandatory safety and execution constraints

This is a read-only research assignment.

You must not:

- Modify, create, delete, rename, or format repository files.
- Change any production code, Membership code, migrations, fixtures, tests, generated files, or documentation.
- Run migrations, seeders, application write operations, or destructive commands.
- Install dependencies or modify Git state.
- Read `.env` files, print credentials, or expose secrets.
- Execute tests or scripts that can mutate the repository or database.
- Connect to a production database unless an explicitly authorized, read-only connection is already available and the target can be verified safely.
- Infer permission to access a database from the existence of configuration files or connection definitions.

Inspect the existing working-tree status and HEAD before beginning. Preserve all pre-existing changes.

For data profiling, use only a verified, authorized, read-only data source. Confirm the database identity and access mode without exposing credentials. Use bounded SELECT-only queries, avoid locking or long-running queries, and inspect query plans where necessary.

If safe access cannot be established, do not attempt a connection. Produce the exact proposed profiling queries and execution prerequisites for later authorization. Report actual counts as UNKNOWN rather than inventing them.

You may use temporary in-memory reasoning or calculations, but do not create or update repository artifacts. Print the final report in your response.

## 3. Re-establish the research baseline

Locate and inspect the actual current versions of:

- `20261011_0900_cross_context_consistency_and_simulation_study.md`
- `g14_cross_context_consistency.py`
- The model README and relevant g7/g9–g13 research outputs.
- The cross-domain decision sheet.
- The current architecture and implementation plans.
- The ratified Membership architecture and applicable ADRs.
- The current Committee, Geography, Authorization, and Discussion Forum source code.
- Relevant migrations, tests, routes, policies, handlers, repositories, and projections.

Find actual paths using repository inspection. Do not assume that filenames, line numbers, or statuses in the earlier transcript are still current.

Record HEAD and initial working-tree status. Do not overwrite pre-existing changes.

Separate each important claim into:

- Verified directly from current source.
- Verified by safely executed test or read-only query.
- Reported by the previous study but not independently verified.
- Inferred from available evidence.
- Proposed recommendation.
- Open business or architecture decision.

## 4. Independent verification of C1–C12

For every contradiction C1–C12, independently inspect the source evidence and answer:

1. Does the contradiction still exist at the current HEAD?
2. Which models, tables, fields, writers, readers, and constraints are involved?
3. Is the finding a real data contradiction, a schema/design contradiction, or merely a naming inconsistency?
4. Can the invalid state actually be represented by the schema or reachable through the identified write paths?
5. Can the state be detected without making an unapproved business assumption?
6. Which context owns the relevant fact, if that ownership has been approved?
7. What evidence would confirm or refute the finding?
8. What is the smallest safe corrective direction?

Do not count multiple columns or classes as separate semantic concepts without explaining why they represent different sources, contracts, or meanings.

Pay particular attention to:

- C1: CommitteeType versus geographic level.
- C2/C7: cross-organisation geographic references.
- C3: Membership lifecycle versus committee assignment.
- C4/C8: conflicting assignment representations and historical dates.
- C5: position versus permission.
- C6: Geography moves, retirement, and historical reconstruction.
- C9: inconsistent person/member identity and `member_id` semantics.
- C10: incompatible geographic and organisational level numbering.
- C11: role-holder limits versus database uniqueness constraints.
- C12: committee membership flags granting unrelated authority.

Verify the reported identity-width mismatch, role-limit conflict, level-numbering conflict, and permission leakage from source code and migrations.

Do not treat an application-level rule as a database invariant unless the database actually enforces it. Do not infer the deployed schema from migration source alone.

## 5. Validate g14 and the earlier simulation conclusions

Independently review the mathematical and logical validity of g14.

For each submodel S1–S4, inspect:

- The state representation.
- Event ordering.
- Transition semantics.
- Invariants.
- Enumeration coverage.
- Assumptions and excluded states.
- Metric definitions.
- Whether the output actually supports the written conclusion.
- Whether the model contains implementation or measurement defects.

Specifically verify:

### S1 — Membership and assignment

Confirm whether the enumerated event sequences cover the intended state space. Check how admission, ending, appointment, vacating, and annulment interact.

Determine whether the claimed derived rule preserves historical facts and whether it merely changes effective eligibility rather than deleting or rewriting the assignment.

State explicitly that the model cannot decide whether every position requires current Membership; that is a business decision.

### S2 — Reconciliation of duplicate stores

Verify the definitions of the latest-wins, fixed-priority, and agree-or-queue policies.

Check how missing records, stale records, corrupted dates, and correlated errors affect the results. Confirm that the reported synthetic noise assumptions are not presented as real-world probabilities.

Evaluate the residual risk when every source agrees but all sources are wrong.

Do not select a migration policy from synthetic accuracy figures alone.

### S3 — Contradiction detectability

Verify the enumerated combinations and counts. Explain why organisation boundaries can be checked from authoritative identifiers while type-to-level compatibility requires a mapping decision.

Identify which proposed mappings are organisation-configurable and whether the existing `committee_structures` / `committee_structure_levels` model can safely represent the needed policy.

Do not declare that the existing structure model is the correct owner without checking the approved ownership decisions.

### S4 — Geography changes

Verify the modeled effects of unit moves and retirements. Distinguish:

- Historical decision reconstruction.
- Current jurisdiction coverage.
- Successor resolution.
- Missing or cyclic successor chains.
- Revisions that are unavailable or pruned.

Recommend additional successor-chain simulations only if the current model does not already cover them.

Report any defect in g14 or its interpretation. Do not modify the model.

## 6. Authorised read-only legacy-data profiling

If, and only if, a safe and authorized read-only database is available, profile the prevalence of C1–C12.

Use bounded, read-only queries. Avoid printing personally identifiable member data. Report aggregate counts and non-identifying examples only.

Before counting contradictions, determine the required joins and canonical identity mappings. If the mapping is unknown, report the affected count as unresolved rather than using an unsafe join.

Produce a table with:

- Contradiction ID.
- Required tables and fields.
- Detection predicate.
- Records examined.
- Number of affected records.
- Number of affected organisations, if safely measurable.
- Missing or ambiguous evidence.
- Confidence and limitations.
- Whether human adjudication is required.

Important rules:

- Distinguish record counts from distinct people, committees, assignments, and organisations.
- Detect join multiplication and duplicate counting.
- Treat nullable organisation ownership explicitly.
- Do not infer that an official shared Geography unit is safe merely because `organisation_id` is NULL.
- Do not classify all disagreements as corruption: some may be valid historical transitions.
- Do not update or repair data.
- Do not infer that a zero count proves the invariant is enforced.
- If no safe database is available, return a profiling plan with SELECT-only query templates and the exact missing prerequisites.

For C4/C8, estimate the potential review queue only from actual observed records, and explain the classification rules. Do not use g14's synthetic queue size as a production estimate.

## 7. Complete the Discussion Forum relationship model

This section is mandatory and must not be omitted.

Inspect the existing Forum implementation and architecture documents. Determine what currently exists and what remains proposed.

Establish the boundaries between:

- Membership.
- Person identity.
- Committee and Governance.
- Position and tenure.
- Authorization and permission.
- Geography and jurisdiction, where relevant.
- Discussion Forum.
- Audit and decision evidence.

Answer these questions:

1. What is the authoritative source for identifying a person?
2. What evidence establishes current Membership?
3. Can non-members, guests, external advisers, or honorary participants participate? If unknown, keep the question open.
4. Does committee membership grant access to a Forum, or does it only provide a potential input to an explicit access policy?
5. How are Forum participation, reading, contribution, moderation, and administration permissions distinguished?
6. Can a user hold a committee position without having every permission associated with the committee?
7. How should Membership ending, suspension, assignment ending, or permission revocation affect access?
8. Are these effects immediate, eventually consistent, or evaluated on every command?
9. What happens to existing posts and historical contributions after a person loses access?
10. Can a user change a client-supplied organisation, committee, or geographic identifier to access another Forum?
11. How are duplicate messages, retries, and concurrent permission changes handled?
12. What evidence must be recorded for moderation actions or decisions?
13. Does the Forum need geographic jurisdiction at all, or is geographic relevance a separate, optional feature?

Do not invent the product policy. For each unresolved rule, identify the required decision and its consequences.

Construct a proposed relationship and command-flow diagram. Clearly mark proposed and open elements.

Use a testable command such as `ContributePost` as an example only if it fits the existing Forum architecture. Trace its required authorization, Membership and committee evidence, transaction boundary, and resulting domain events. Do not implement the command.

Identify whether the Forum can be designed as a consumer of Membership and Governance contracts without becoming a writer of their authoritative facts.

## 8. Produce a canonical cross-context contract proposal

Based on verified evidence, evaluate the minimum candidate contracts:

- `PersonRef` or equivalent canonical identity reference.
- `MembershipQueryPort`.
- `GeographyQueryPort` with explicitly defined revision semantics.
- `CommitteeStructurePort` for approved type/level and position-capacity policies.
- `AuthorityAssertionPort`, where required by Adjudication.
- Authorization/permission decision contract.
- Forum participation or contribution authorization contract.
- Write-guard/version-token contract, where concurrency requirements justify it.

For every proposed contract, state:

- Provider and consumer.
- Authoritative facts it may return.
- Input and output semantics.
- Organisation scope.
- Temporal semantics (`asOf`, revision, or equivalent) where required.
- Behavior for missing or conflicting evidence.
- Whether it returns TRUE/FALSE/UNKNOWN or another explicit result type.
- Error and failure behavior.
- Consistency and concurrency requirements.
- Approval needed.
- Whether the current implementation supports it.

Do not require every context to implement every contract. Minimize dependencies.

Keep ownership explicit. A context must not update another context's authoritative tables or internal domain objects.

Coordinate with the Membership owner through contract proposals and review questions only. Do not change Membership implementation.

## 9. Formal verification and statistical discipline

Where useful, express candidate invariants in formal notation, state-transition rules, or executable model pseudocode.

Distinguish:

- Safety properties: something bad never happens.
- Liveness properties: something good eventually happens.
- Data-quality predicates: a record is structurally inconsistent.
- Business policies: the organisation decides what is permitted.
- Historical reconstruction guarantees.
- Operational assumptions about delivery, transactions, and concurrency.

Do not use machine learning as a substitute for explicit authorization or domain rules.

If clustering or anomaly detection is proposed for finding legacy inconsistencies, require explainable features, human review, and a clear distinction between an anomaly and a confirmed violation. Do not train on real personal data for this research round.

## 10. Final report format

Print a self-contained report with these sections:

### A. Executive decision

State whether the architecture is ready for integration, and identify the immediate blocker.

### B. Research baseline and evidence quality

Record HEAD, initial working-tree status, inspected artefacts, safe queries/tests actually executed, and limitations.

### C. Independent review of the previous study

List confirmed, corrected, unverified, and rejected findings.

### D. C1–C12 evidence matrix

Include source evidence, detectability, data-profile results if available, and required decisions.

### E. Simulation verification

Describe the validated model semantics, reproduced outputs, defects, missing cases, and limitations.

### F. Legacy-data profiling results

Provide aggregate counts or explicitly state why counts remain UNKNOWN.

### G. Discussion Forum architecture

Show its relationships with Membership, Committee/Governance, Permission, Geography, and Audit. Distinguish current implementation from proposed design.

### H. Canonical cross-context contracts

List the minimum proposed contracts, their owners and consumers, and the agreements required.

### I. Contradiction and reconciliation strategy

Define safe classification and human-review behavior without silently changing legacy facts.

### J. Architecture decisions

Separate approved decisions, open business questions, open architecture decisions, and recommendations.

### K. Next implementation gates

Specify the evidence and approvals needed before implementation, integration, and migration.

### L. Progress and TODO checklist

Use bullet points. Distinguish research completed, decisions pending, profiling pending, and implementation not yet started.

### M. Principal Reviewer conclusion

Answer explicitly:

1. What is established?
2. What remains uncertain?
3. What should be done next?
4. What must not be implemented yet?
5. What would demonstrate readiness for the next phase?

## 11. Reporting integrity

- Cite actual current repository evidence for material findings.
- Do not claim a query, test, or simulation was run unless actual output was inspected.
- Do not convert synthetic model rates into real-world prevalence.
- Do not treat proposed rules as approved.
- Do not infer database integrity from migration files alone.
- Do not modify the repository or Membership implementation.
- Do not start integration just because contract interfaces can be drafted.
- Do not invent missing business decisions.
- State UNKNOWN where evidence is insufficient.

Begin the independent verification now and print the complete report. Remain read-only throughout.

## 3. What success looks like after this round

We should expect three concrete outcomes from Claude:

- Evidence: we know which reported contradictions are confirmed and, if authorized data access is available, how prevalent they are.
- Architecture: we have a proposed Forum integration model and a minimal, agreed-contract candidate for the cross-context relationships.
- A decision-ready backlog: we know which rules require a business decision, which defects can be corrected independently, and which gates must pass before implementation.

The most important safeguard is to avoid turning a simulation result into a business rule automatically. The simulation can show that a derived assignment rule preserves the modeled history better than cascading writes; only the authorized business owners can decide whether Membership is a prerequisite for every type of committee position.

## 4. Progress and remaining TODOs

- Complete the initial cross-context legacy inventory and contradiction catalogue C1–C12.
- Produce the first bounded simulation study for Membership/assignment consistency, duplicate reconciliation, contradiction detectability, and Geography history.
- Independently validate the model implementation, assumptions, and conclusions.
- Confirm C1–C12 against the current repository state.
- Perform authorised, read-only data profiling or prepare verified query templates if safe access is unavailable.
- Complete the Discussion Forum relationship model and authorization flow.
- Agree the canonical identity and Membership contracts with the Membership owner.
- Resolve Committee ownership, Geography revision semantics, role capacity, and Membership-to-tenure rules through authorized decisions.
- Approve the minimum cross-context contracts.
- Only then begin controlled integration, with a single authoritative writer and reconciliation before migration.

Overall assessment: cross-context research has made substantial progress, but integration readiness has not been demonstrated. The next milestone is to turn the current research model into an independently verified, data-informed, decision-ready architecture—including Discussion Forum—without competing with the ongoing Membership implementation.&#x20;