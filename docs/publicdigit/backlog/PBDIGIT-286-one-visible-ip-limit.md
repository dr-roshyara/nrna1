# PBDIGIT-286: The constitutional IP limit is hidden, effectively 3, and not controlled by the admin switch

**Type:** Product / security design · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (finding C). **Index row:** added 2026-10-13.

| | |# Claude Code Instructions — PBDIGIT-286: Constitutional IP Limit

## 1. Role and objective

Act as a senior software architect, security engineer, DDD architect, requirements engineer, and independent code reviewer.

Work exclusively on **PBDIGIT-286: The constitutional IP limit is hidden, effectively 3, and not controlled by the admin switch**.

Your task is to establish the current implementation state, verify the previous investigation against the actual repository, resolve the technical ambiguities, and prepare a governed implementation plan for my approval.

**This is an investigation and planning task, not implementation authorization.**

## 2. Binding decisions

The following decisions have already been made and must not be silently reversed:

1. `IpVoteLimit` and the constitutional trust-policy IP limit remain separate controls.
2. The stricter applicable limit wins at code entry.
3. Do not merge the two limits or remove the constitutional control merely to simplify the implementation.
4. Do not silently remove trust-level scaling or invent verification semantics.
5. No implementation begins until I have reviewed and approved the proposed plan and outstanding business decisions.

If you believe a binding decision should be reconsidered, explain the reason and consequences, then ask for explicit approval. Do not implement the alternative.

## 3. First: establish the actual current state

Inspect the current repository, Git history, working tree, PBDIGIT-286 backlog item, relevant architecture decisions, existing tests, and developer guides.

Report separately:

- What was already implemented before this investigation.
- What was committed in previous work.
- What has actually changed for PBDIGIT-286, if anything.
- What is only investigated, proposed, or documented.
- What remains unimplemented.
- Which conclusions are verified from source code, which are verified by executed tests, and which remain assumptions.

Do not claim that a feature is implemented merely because a backlog item, test, or design document describes it.

Do not repeat PBDIGIT-278 item 8 or PBDIGIT-287 implementation. Those are completed and committed according to the existing project history. Do not reopen them unless a concrete dependency or regression is demonstrated.

## 4. Verify the current constitutional-limit behaviour

Inspect the actual implementation, including at least:

- `TrustPolicyEvaluator`
- `TrustLegitimacySecurityVerdict`
- `NetworkTrustEvidence`
- `NetworkThresholdInterpreter`
- `voter_verifications` and the verification methods
- `elections.max_votes_per_ip`
- `network_binding_strategy`
- `IpVoteLimit`
- Election creation and election settings forms, requests, controllers, and persistence
- Code-entry and final-cast authorization paths
- Refusal messages and audit logging
- Tests characterizing the current security policy

Verify the following findings rather than assuming they remain true:

1. The constitutional limit has a default of 6.
2. `attested` is hard-coded to false and verification records are not consulted by the trust policy.
3. The effective limit is scaled according to trust level.
4. The constitutional check uses `>` while `IpVoteLimit` uses `>=`.
5. The constitutional control is not visible or configurable through the election settings interface.
6. The constitutional control is activated independently of the ordinary election IP-restriction switch.
7. The voter receives a vague refusal message and the relevant refusal is not adequately audited.
8. The relevant election settings are not locked when an election becomes ready for voting.

For every finding, provide the file and line references, the actual code behaviour, and the evidence supporting it.

## 5. Resolve the verification and trust-level design

The previous analysis needs a precise distinction between individual and bulk officer attestation.

The verification records distinguish at least these methods:

- `officer_attestation`
- `officer_bulk_attestation`

The unresolved question is whether these methods should provide the same trust level. The fact that both are performed by an election officer does not itself decide that business rule.

Investigate the existing domain model and verification lifecycle. Propose explicit alternatives for:

- Individual officer attestation.
- Bulk officer attestation.
- Registrar-attested status, if supported by the actual domain model.
- Withdrawn, revoked, expired, or otherwise invalid verification.
- Missing, conflicting, or unavailable verification evidence.

For each alternative, explain the security consequences and the resulting vote allowance.

Do not equate a verification method with a trust level unless the domain rules explicitly support that mapping. Do not introduce a new trust level or claim that RegistrarAttested is distinguishable unless the implementation and evidence support it.

Present the individual-versus-bulk distinction as a business decision requiring my approval.

## 6. Prepare explicit decision points

Create a decision table for all ten decisions (D1–D10) from the previous PBDIGIT-286 proposal.

First locate and reproduce the existing D1–D10 wording faithfully. Do not silently invent new decision IDs or replace the original questions.

For each decision, provide:

- Existing proposal and recommendation.
- Current behaviour.
- Available options.
- Recommended option and rationale.
- Security, usability, operational, and migration consequences.
- Whether my explicit approval is required.
- Dependencies on other decisions.

In particular, address these issues:

### D1 — Scope and lifecycle of the configured limit

Clarify whether `max_votes_per_ip` is configured at election creation only or remains editable afterwards.

If creation-only is proposed, explain that existing elections retain their current value and administrators cannot change it later. Verify whether the election-creation form can carry and persist this field. Do not assume that it can.

### D3 — Individual versus bulk attestation

Make this an explicit policy decision. Explain the consequences of treating individual and bulk attestation equally or differently.

### D4 — Withdrawn or invalid verification

Preserve the previously agreed direction that withdrawn verification is treated as unverified, unless the original decision record establishes a more precise rule.

### D7 — Threshold comparison

Explain the consequences of changing `>` to `>=` for every applicable trust level, not only for one example.

Separate the effects of verification wiring from the effects of changing the comparison. Show the current and proposed vote allowances clearly.

### D9 — Concurrency and locking

Correct the earlier description: the constitutional check currently runs outside the lock. The recommendation to retain this arrangement relies on the existing `IpVoteLimit` check under its lock preventing concurrent over-limit votes.

Explain the assumptions and limitations of this approach. If moving the constitutional check under a lock requires an ADR or broader design decision, identify that dependency. Do not silently change locking architecture.

### Election readiness safeguard

Regardless of the D1 choice, evaluate locking `max_votes_per_ip` and `network_binding_strategy` once the election is ready for voting. Specify the required invariant and tests.

Preserve the previously accepted decisions D2, D5, D6, D8, and D10, after verifying their original wording and dependencies.

## 7. Required implementation-plan design

Prepare a test-first plan covering the approved behaviour. The plan must address:

1. Election-specific configuration and global defaults.
2. Validation and consistent threshold semantics.
3. Verification-record integration and the approved trust-level mapping.
4. Individual and bulk attestation policy.
5. Withdrawn and invalid verification.
6. Election creation, editing, persistence, and readiness locking.
7. Enforcement at code entry and final cast.
8. Clear voter-facing refusal messages.
9. Audit records for constitutional-limit refusals.
10. Regression coverage for the separate `IpVoteLimit` mechanism.
11. Concurrency and locking assumptions.
12. Existing elections and backward compatibility.
13. Documentation and developer-guide updates.

Preserve the invariant:

**Both limits remain separate, and the stricter applicable limit wins at code entry.**

Identify any conflict between this invariant and the existing final-cast behaviour. Propose a resolution without silently changing the agreed design.

## 8. Testing and safety requirements

For the planning stage:

- Inspect existing tests and identify the exact tests that must change or be added.
- Specify RED → GREEN test cases for each approved business rule.
- Distinguish unit tests, integration tests, isolated-database tests, and regression tests.
- Include boundary cases at the exact limit, one below, and one above.
- Include missing, withdrawn, and invalid verification evidence.
- Include individual and bulk verification.
- Include different elections with different configurations.
- Include the election-readiness safeguard and refusal audit.
- Include concurrent attempts where relevant.

Do not run destructive database commands.

If later implementation is approved, use only the approved isolated database `pbdigit274_isolated_test`, with a database-identity guard before any database setup. Never run migrations or destructive setup against `nrna`, `nrna_test`, or shared/development databases without separate explicit authorization.

## 9. Working-tree and Git safeguards

- Preserve all existing uncommitted changes, including the previously agreed deletion of `app/Http/Middleware/VoterSlugStep.php`.
- Do not reset, stash, discard, rewrite, or stage unrelated changes.
- Do not commit or push.
- Do not modify production code, schemas, migrations, configuration, or tests during this investigation.
- Do not edit `.env` or `.env.*` files.
- Do not perform unrelated Membership, Adjudication, or other domain work.
- Keep investigation output limited to what is necessary for this ticket.

## 10. Deliverables

Provide the following:

**A. Current-state report**
- What exists and works today.
- What is broken or incomplete.
- Exact evidence and test status.
- What has already been committed versus what is uncommitted.

**B. Corrected D1–D10 decision sheet**
- Original decision wording.
- Corrected technical explanations.
- Options, recommendation, consequences, and approval status.

**C. Proposed governed implementation plan**
- Phases and dependencies.
- Test-first acceptance criteria.
- Migration and compatibility strategy.
- Security, concurrency, audit, and documentation considerations.

**D. Decisions required from me**
- List only genuine unresolved business or architecture decisions.
- State the recommended option and the consequence of each alternative.

**E. Stop point**
- Do not implement anything.
- Do not create implementation commits.
- Do not mark PBDIGIT-286 as implemented.
- Wait for my explicit approval of the decisions and plan.

The objective is a verified, decision-ready design that preserves the existing architectural decision to keep both IP limits separate. It is not permission to implement the proposed changes.
|---|---|
| **Status** | `OPEN` · not authorised · **needs a PO decision first** |
| **Background decision** | Human PO 2026-10-13 (PBDIGIT-278 item 8): keep `IpVoteLimit` and the constitutional limit **separate**; at code entry the stricter applies. This item is about making the constitutional limit **visible and controllable**, not about merging them silently. |

## Finding (verified in code)
- **The trust policy** (`TrustPolicyEvaluator`, `TrustLegitimacySecurityVerdict`, `NetworkTrustEvidence`, `NetworkThresholdInterpreter`) applies `elections.max_votes_per_ip` (default 6) **scaled by trust level** at code-entry authorization and at the final cast.
- **The trust level is always the lowest:** `attested = false` is hard-coded (`TrustPolicyEvaluator.php:178`, "populated by controller from VoterVerification record", but it never is). So the effective limit is ⌊6/2⌋ = **3**. With its `>` comparison (`NetworkTrustEvidence.php:32`), it refuses from about the 5th vote per IP.
- **The admin switch doesn't control it:** it is turned on by `network_binding_strategy !== 'none'` (default `ip_count`), **not** by the admin's "IP restriction" toggle (`ip_restriction_enabled`).
- **Admins can't see it:** `max_votes_per_ip` is not exposed in any request, controller or settings page. An admin setting of 50 does not relax it. This is characterised in `SecurityPolicySurfaceCharacterizationTest` ("a loose settings limit does not relax the authority").
- **Inconsistent numbers:**
  - defaults 4 (`ip_restriction_max_per_ip`), 6 (`max_votes_per_ip`), 10 (`IpVoteLimit`) and 7 (dead demo field);
  - validation ranges 1–50 vs 1–100;
  - comparisons `>=` vs `>`.
- **What voters see:** "constitutional verification" denials don't mention the network.

## Decision needed
1. **Is the constitutional per-IP limit still wanted?** If so, show and set it on the election settings page, next to the IP restriction, with clear wording.
2. **`attested`:** populate it from the voter-verification record as designed, or remove the trust-level scaling.
3. **Which switch turns which limit on**, and aligned defaults, validation ranges and comparisons.

## Acceptance (after the decision)
- [ ] Admins can see and control every per-IP limit that can refuse a voter.
- [ ] Characterisation tests are updated to the decided behaviour (RED → GREEN).
- [ ] Refusal messages say that the network limit was reached.

## Related
`PBDIGIT-278` item 8 · `PBDIGIT-285` · `PBDIGIT-288`.
