---
knowledge_id: PORTAL-BY-ROLE
title: EKP — Navigation by Role
knowledge_type: portal
bounded_context: global
status: approved
authority: derived
audience: [newcomer, developer, architect, reviewer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [portal, navigation, onboarding]
related_to: [PORTAL-INDEX]
---

# Navigation by Role

> Different consumers need different entry points. Each path is an ordered reading list. (Powered by the `audience` field; the knowledge graph can regenerate these automatically.)

## Newcomer

1. [Project overview](../../../CLAUDE.md) — what the platform is (Public Digit voting platform).
2. [Knowledge Constitution](../Knowledge-Constitution.md) — how knowledge is governed here.
3. [Developer guide: overview](../../../developer_guide/01-overview.md).
4. [Topic hub: Election](hubs/election.md) — the core domain.
5. [Recipe: Implement an Aggregate](recipes/implement-aggregate.md) — your first contribution pattern.

## Developer

1. **[EKP Developer Guide](../global/ekp-developer-guide.md)** — how the knowledge platform works and how to add to it.
1. Backend discipline & layer rules: [`.claude/CLAUDE.md`](../../../.claude/CLAUDE.md).
2. The domain you're touching: [domains/](../domains/) (e.g. [Adjudication](../domains/adjudication/README.md)).
3. The relevant [recipe](recipes/) and [package](packages/).
4. Architecture boundaries enforced by code: [`deptrac.yaml`](../../../deptrac.yaml).
5. Testing: [`developer_guide/06-testing-guide.md`](../../../developer_guide/06-testing-guide.md).

## Architect

1. [Knowledge Constitution](../Knowledge-Constitution.md) + [Lifecycle & Governance](../_meta/lifecycle.md).
2. [ADR index](adr-index.md) — all decisions of record.
3. Architecture baselines: [`architecture/ARCHITECTURE_BASELINE_V1.md`](../../../architecture_legacy/ARCHITECTURE_BASELINE_V1.md).
4. Context map / domains: [domains/](../domains/).
5. The [knowledge graph](graph/) to see how it all connects.

## Reviewer

1. [Lifecycle quality gates](../_meta/lifecycle.md#4-knowledge-quality-gates-definition-of-approved) — definition of "approved".
2. [Ownership & approval matrix](../_meta/OWNERS).
3. Run `npm run knowledge-lint` before approving knowledge changes.

## AI assistant

1. [Knowledge Constitution → AI collaboration principles](../Knowledge-Constitution.md#ai-collaboration-principles) — **AI output is never authoritative without review**.
2. Load the relevant [context package](packages/) for the task.
3. AI working material lives under [`ai/`](../ai/); promote only reviewed output.
4. Respect `bounded_context` boundaries (mirrors Deptrac).
