## 7. Your new organisational principle changes the next research priority

Your clarification is important: each organisational function must have a responsible committee, and that committee must operate within its assigned responsibilities.

This gives us a more practical foundation for the PublicDigit domain model. We should not model an organisation as merely a collection of Members and Elections. We need to model how the organisation assigns responsibility, authority, and accountability to the bodies that operate it.

## Organisation

Organisational structure, rules and governance

Governance and organisational authority

Committee appointments · Mandates · Tenures · Delegations · Duties · Decision procedures

Election Committee

Election operations

Financial Committee

Financial operations

Organisational Committee

Organisation and governance operations

Conceptual target model — exact mandates, powers and decision rules still require discovery and ratification.

### The critical DDD distinction

A committee is not automatically a bounded context, and membership in a committee is not the same as organisational Membership.

| Concept                        | Architectural responsibility                                                                       |
| ------------------------------ | -------------------------------------------------------------------------------------------------- |
| Member                         | Represents the recognised relationship between a person and the organisation.                      |
| Committee                      | Represents an organisational body assigned a defined responsibility.                               |
| Committee appointment / tenure | Establishes who serves on a committee, in what capacity, and during which period.                  |
| Mandate                        | Defines the committee's authorised scope, limits and purpose.                                      |
| Authority                      | Determines which decisions or actions the committee or its authorised representatives may perform. |
| Duty / accountability          | Defines what must be done and who remains responsible.                                             |
| Election context               | Owns election processes, registration, voting rules and election outcomes.                         |
| Finance context                | Owns financial facts, charges, payments and settlements.                                           |
| Membership context             | Owns the organisation's Member relationship and its lifecycle facts.                               |

For example, the Financial Committee may be authorised to approve a financial action, but the Finance context should still enforce the applicable authority and accounting rules. The committee should not directly modify Membership facts merely because a financial decision affects a Member.

Likewise, an Election Committee should operate elections through the Election context. It must not become the authority that changes general organisational Membership simply because a person's membership status affects a Full-membership election.

One key rule from our existing research remains relevant: delegating authority does not automatically transfer the underlying duty or accountability. The exact legal and organisational interpretation must be discovered and ratified.

We also need to investigate whether the Organisational Committee is authorised to admit and end Members, or whether those decisions belong to another committee or a different organisational authority. Your clarification establishes the need for responsible committees; it does not yet settle every committee's precise power.

## 8. Recommended next step for Claude

I recommend that the next research task be Committee Responsibility, Authority and Membership Boundary Discovery. This incorporates your new organisational principle into the domain theory before we implement Participation or connect Membership to live application workflows.

There is a separate technical track: `PBDIGIT-264` remains the next small Membership persistence-remediation slice, but its implementation still requires explicit Product Owner authorisation. Researching committee responsibilities does not authorise that implementation.

# Claude Commission — PublicDigit: Committee Responsibility, Governance Authority and Membership Theory

## Role

Act as Senior DDD Architect, Principal Researcher, Requirements Engineer, mathematician, statistician, formal-logic researcher and software verification engineer.

The goal is to derive a robust, practical organisational governance and Membership theory for PublicDigit. Treat the current corpus as brainstorming and evidence, not as a set of instructions to accept uncritically.

The Principal Reviewer controls architectural review. The Product Owner, Legal adviser, Governance owner, Election owner, Finance owner and Operations retain their established decision rights.

## 1. New business principle

The Product Owner has clarified the following organisational principle:

- An Election Committee is responsible for operating elections.
- A Financial Committee is responsible for operating financial matters.
- An Organisational Committee is responsible for organisational and governance matters.
- Each committee is responsible for its assigned roles and responsibilities.

Treat this as an adopted business discovery input. Do not invent the detailed powers, membership requirements, decision procedures or legal status of these committees. Derive candidate rules and identify which require ratification.

## 2. Establish the actual repository state

Inspect the current branch, HEAD, working tree and session/context records. Do not assume that the previously reported commit `f1a483b47` is still HEAD.

Read the latest versions of:

- `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md`
- `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md`
- `docs/publicdigit/membership/architecture/models/README.md`
- The ratified Membership architecture and Slice 0 / 1a / 1b contracts.
- Existing Governance, committee, role-assignment, election, Finance and organisational domain models.
- The latest backlog, context and session records.

Preserve all unrelated work from other sessions. Do not reset, clean, stage or commit unrelated changes.

## 3. Discover the committee responsibility model

Produce a traceable responsibility model for:

1. The Organisational Committee.
2. The Election Committee.
3. The Financial Committee.

For each committee, investigate:

- Purpose and mandate.
- Decisions it is authorised to make.
- Operational actions it may perform.
- Explicitly prohibited decisions and actions.
- Composition and appointment authority.
- Individual roles versus collective committee authority.
- Quorum, voting, approvals and tie-breaking, where applicable.
- Delegation, substitution and conflicts of interest.
- Tenure, expiry, suspension, removal and replacement.
- Duties and accountability before and after delegation.
- Evidence required to prove that a decision was valid.
- Auditability, correction and historical reconstruction.
- How authority is checked when an action is attempted.
- How organisation-specific statutes or rules alter the default model.

Do not assume that all committees share identical rules. Identify common concepts and genuine variations.

## 4. Separate the domain concepts

Evaluate and document the boundaries among:

`Person ≠ OrganisationAffiliation ≠ Member ≠ MembershipPeriod ≠ CommitteeAppointment ≠ Tenure ≠ Mandate ≠ Authority ≠ Duty ≠ ElectionRegistration ≠ VotingEntitlement ≠ Exercisability`.

In particular:

- A Member is not automatically a committee member.
- A committee appointment does not create or replace organisational Membership.
- A committee mandate does not grant unlimited authority.
- Membership does not itself grant voting entitlement.
- An Election Committee operates through the Election context.
- A Financial Committee operates through the Finance context.
- The Organisational Committee operates only within its ratified organisational mandate.
- A committee must not directly write another bounded context's authoritative facts.
- Access to services and forums is derived through the relevant policy; it is not primary Membership truth.
- Delegation of authority must be distinguished from transfer of duty and accountability.

Investigate whether a committee appointment requires current organisational Membership, whether exceptions exist, and who may authorise them. Do not assume the answer without evidence.

## 5. Define bounded-context responsibilities

Produce a context map that distinguishes organisational bodies from software bounded contexts.

At minimum, analyse:

- Membership: Member relationship and lifecycle.
- Governance: committees, appointments, positions, tenures, mandates, authority and duties.
- Election: election setup, registration, eligibility evaluation, voting and results.
- Finance: charges, payments, credits, settlement and accounting.
- Audit: consumption of authoritative evidence without becoming an alternative authority source.
- Service/access policies: derived access to forums, documents, meetings and other services.

For every cross-context interaction, identify:

- The initiating actor or committee.
- The command or request.
- The authoritative context.
- The required authority check.
- The evidence produced.
- The resulting state or projection.
- Failure and retry behaviour.
- The audit evidence.
- The rule preventing unauthorised cross-context writes.

Use a Mermaid context map if it clarifies the boundaries. Label proposed relationships and unresolved dependencies explicitly.

## 6. Investigate Membership decision authority

Determine which committee or authority may:

- Review an application.
- Admit a Member.
- End a Membership.
- Suspend or restore a Member, if that concept is ratified.
- Correct or annul a prior Membership fact.
- Authorise exceptional or migrated records.

The existing Membership model separates the Member aggregate from the application and from the authority evidence. Preserve those distinctions.

Do not assume the Organisational Committee owns every Membership decision merely because it is responsible for organisational governance. Produce candidate responsibility allocations, supporting evidence and explicit ratification questions.

## 7. Formalise responsibility, authority and duty

Develop a precise model that distinguishes:

- Responsibility for a function.
- Authority to make a decision.
- Duty to perform an action.
- Accountability for the result.
- Delegation and substitution.
- Appointment and tenure.
- Collective decisions and individual execution.

Define invariants and decision predicates where possible. Examples to investigate:

- An action requiring committee authority cannot succeed without valid authority evidence.
- An expired appointment cannot authorise a new action unless a ratified rule permits it.
- A delegate cannot exceed the mandate of the delegating authority.
- Delegating authority does not automatically discharge the original duty.
- Committee members cannot exercise powers outside the committee's mandate.
- A committee decision must satisfy the applicable decision procedure.

These are candidate invariants for validation, not automatic new business rules. Identify counterexamples and ask for ratification wherever legal or organisational semantics remain unresolved.

## 8. Use formal methods and computation carefully

Use finite-state enumeration, property-based tests, truth tables, temporal reasoning and independent oracles when they improve the analysis.

For each model:

- State its finite domain and assumptions.
- Separate counterexamples from bounded agreement.
- Distinguish admissible from inadmissible histories.
- Reuse the real Membership kernel only for operations it actually implements.
- Label histories involving unimplemented Annul or Correct behaviour as unchecked by the kernel.
- Never claim that bounded agreement proves universal correctness.
- Do not describe enumerated counts as production probabilities.

Machine learning may be proposed for duplicate detection, migration triage and classification of exceptions for human review. It must not decide legal authority, committee legitimacy, Membership legitimacy, voter eligibility or financial approval.

Do not modify production code to accommodate a research model.

## 9. Connect the committee model to the existing Membership implementation roadmap

Review the latest PBDIGIT-264 findings and decisions:

- F-4b: normalise command/idempotency storage, subject to migration preflight and rollback verification.
- F-5 N1: reject nested adapter calls and enforce top-level `READ COMMITTED`.
- F-3: approved adapter contract clarification.
- Slice 1b: acceptance with conditions recommended, not production-ready.
- DV-1: earlier minimal-fix approval was found in a transcript; preserve the repository countersignature recommendation.
- PBDIGIT-264 implementation still requires explicit Product Owner authorisation.
- Slice 2 decisions D-1 through D-6 remain subject to their respective ratifiers.
- PBDIGIT-265 remains an Election-owned Full-membership eligibility issue and a cutover blocker where voting eligibility could change.

Do not interpret this research commission as authorisation for PBDIGIT-264 or any production implementation.

Show how the committee model affects the future Governance participation contract, Membership authority port, Election eligibility and Finance approval without introducing cross-context writes.

## 10. Deliverables

Create or update a single coherent research document in the existing Governance/Participation research area, using repository conventions. Do not create duplicate decision records.

Include:

1. Executive assessment.
2. Adopted business input versus proposed interpretations.
3. Committee responsibility matrix.
4. Context map and authoritative ownership.
5. Conceptual domain model.
6. Command/decision/evidence flows.
7. Candidate invariants and counterexamples.
8. Open requirements and questions.
9. Decision owners and ratification gates.
10. Impact on the Membership implementation roadmap.
11. Test and model results with reproducible commands.
12. Recommended next smallest implementation slice and its prerequisites.

Update the session/context record and relevant research index as appropriate, preserving unrelated changes.

## 11. Stop conditions

This commission authorises read-only investigation and research documentation only.

Do not implement production Governance, Membership, Election or Finance behaviour. Do not modify migrations, database schemas, handlers or live entry points. Do not implement PBDIGIT-264, PBDIGIT-261, PBDIGIT-262 or PBDIGIT-263. Do not push changes.

At completion, report the files changed, repository state, evidence, decisions still needed by role, contradictions found, and the dependency-ordered next steps. Then STOP and await explicit authorisation.

## 9. Current status and remaining TODOs

The latest attachment reports commit `f1a483b47`, with research and documentation changes only. It says the development database is PostgreSQL and the Slice 1b migration remains pending there; production is inaccessible and its database engine is unverified. It also reports that the admissibility bridge can evaluate Admit/End histories, but cannot validate histories involving Annul or Correct because those operations are not implemented in the kernel.

### Where we stand

- Membership theory: core architecture and initial lifecycle semantics are established; some lifecycle and authority decisions remain open.
- Membership kernel: Slice 0 implemented.
- Application/repository contract: Slice 1a implemented, with the recorded minimal fix and adapter-contract clarification.
- PostgreSQL adapter: Slice 1b implemented, but conditional acceptance and remediation remain.
- Research: strengthened Finance falsification and partial kernel-admissibility checks are available; histories involving unimplemented correction operations remain unverified by the kernel.
- Committee governance: your responsibility principle is now a clear discovery input, but the detailed mandate and authority model still needs to be documented and ratified.
- Production integration: not complete. Legacy cutover, election eligibility parity, deployment verification and end-to-end acceptance remain outstanding.

### Remaining TODOs in dependency order

- Governance discovery: complete the committee responsibility model and context map described in the Claude prompt.
- Authority rules: establish who may admit, end, suspend, restore, correct or annul Membership, and whether committee membership requires organisational Membership.
- PBDIGIT-264: obtain explicit Product Owner authorisation, then implement F-1, F-5/N1, F-3, F-4b and F-2 in the approved order.
- Slice 1b acceptance: run the required tests and obtain a fresh Principal Reviewer decision; do not label the adapter production-ready prematurely.
- Slice 2 ratification: resolve D-1 through D-6 with the responsible Product Owner, Legal, Governance and engineering stakeholders.
- Election consistency: resolve PBDIGIT-265 and reconcile single-user and bulk voter-eligibility predicates.
- Finance ownership: establish the signed ownership map for legacy membership and Finance columns; continue the Finance-ledger falsification research.
- Cutover safety: implement and test the source-of-truth flag, writer fitness test, database-level guard, reverse projection, reader mapping, migration parity, rollback and write-freeze gates.
- Election-Only isolation: preserve and test that Election-Only organisations do not depend on the Membership ledger.
- Remaining technical debt: separately authorise and resolve PBDIGIT-261, PBDIGIT-262 and PBDIGIT-263.
- Lifecycle completion: implement further Membership lifecycle operations only after their contracts and business rules are ratified.
- Governance, Finance and Audit: implement their respective behaviours only after the committee mandate, authority, evidence and cross-context contracts are approved.
- Production readiness: verify the production database engine, stage migrations, establish a clean-checkout regression baseline, and pass operational, security and end-to-end acceptance gates.

My recommendation: do not jump directly from the current Member aggregate into committee implementation. First derive the relationship among committee responsibility, mandate, authority, duty, and the domain context that executes each decision. That gives us a coherent organisational governance model while keeping the Membership implementation small, testable, and safe.&#x20;