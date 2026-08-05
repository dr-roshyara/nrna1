---
knowledge_id: HUB-ELECTION
title: Topic Hub — Election & Voting
knowledge_type: portal
bounded_context: election
status: approved
authority: derived
audience: [developer, architect, ai, newcomer]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [election, voting, ballot, voter, hub]
related_to: [PORTAL-INDEX, HUB-ADJUDICATION]
code_refs:
  - app/Domain/Election
  - app/Contexts/Elections
---

# Topic Hub — Election & Voting

> **The retrieval test:** "Six months from now — where is everything about how elections and voting work?" This hub answers it by linking across `architecture/`, `docs/`, `developer_guide/`, code, and tests. **The files stay where they are; the hub connects them.**

## Concept

The core domain: an Election runs a 5-step anonymous voting workflow (Code → Agreement → Vote → Verify → Complete) with national + regional posts, two-use codes, and **no voter↔vote linkage**. See the platform overview in [`CLAUDE.md`](../../../../CLAUDE.md).

## Architecture & discovery (Think)

- [Voting workflow architecture](../../../../architecture_legacy/election/20260302_0946_voting_workflow_architecture.md)
- [Election domain inventory](../../../../architecture_legacy/backend/discoveries/20260613-election-domain-inventory.md)
- [Election state-machine analysis](../../../../architecture_legacy/backend/discoveries/20260613-election-state-machine-analysis.md)
- [Voting domain discovery](../../../../architecture_legacy/backend/discoveries/20260613-voting-domain-discovery.md)
- [Analysis of current system](../../../../architecture_legacy/election/analysis_of_current_system.md)

## Decisions (Truth)

- [ADR-002 — Verified ≠ Eligible ≠ Authorized](../../../adr/ADR-002-verified-eligible-authorized.md)
- [ADR index](../adr-index.md)

## Code (Build)

- Root election domain: [`app/Domain/Election`](../../../../app/Domain/Election) — `Constitution/`, `StateMachine/`, `Security/`, `Policies/`, `ValueObjects/`.
- Voter assignment context: [`app/Contexts/Elections`](../../../../app/Contexts/Elections) — e.g. [`AssignVoterHandler`](../../../../app/Contexts/Elections/Application/Handlers/AssignVoterHandler.php), [`ElectionOnlyPolicy`](../../../../app/Contexts/Elections/Domain/Policies/ElectionOnlyPolicy.php), [`FullMembershipPolicy`](../../../../app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php).

## Tests (Verify)

- [`tests/Architecture/Election/ElectionLifecycleStateConsistencyTest.php`](../../../../tests/Architecture/Election/ElectionLifecycleStateConsistencyTest.php)
- [`tests/Architecture/ElectionStateMachineConsistencyTest.php`](../../../../tests/Architecture/ElectionStateMachineConsistencyTest.php)
- [`tests/Feature/Admin/ElectionApprovalTest.php`](../../../../tests/Feature/Admin/ElectionApprovalTest.php)

## Guides (Build)

- [Developer guide: overview](../../../../developer_guide/01-overview.md)
- [Developer guide: verifiable anonymity](../../../../developer_guide/02-verifiable-anonymity.md)

## Related hubs

- [Adjudication](adjudication.md) — challenges to election outcomes flow here.

> _This hub is `authority: derived` — it points at sources, it is not itself the source of truth. When a doc here is migrated into `domains/election/`, update its link, not its content._
