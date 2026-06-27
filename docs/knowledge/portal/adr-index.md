---
knowledge_id: PORTAL-ADR-INDEX
title: EKP — Architecture Decision Record Index
knowledge_type: portal
bounded_context: global
status: approved
authority: authoritative
audience: [architect, developer, reviewer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adr, index, decisions]
related_to: [PORTAL-INDEX]
---

# ADR Index — the single canonical registry

> The one place that lists every Architecture Decision Record. ADRs live in [`docs/adr/`](../../adr/) and use the body template `Status / Context / Decision / Rationale / Implications`.

## Active decisions

| ID | Decision | Context | Status |
|---|---|---|---|
| ADR-0001 | [Committee read/write separation](../../adr/ADR-0001-committee-read-write-separation.md) | governance / membership | accepted |
| ADR-001-trust | [Trust attestation domain](../../adr/ADR-001-trust-attestation-domain.md) | trust | accepted |
| ADR-002 | [Verified ≠ Eligible ≠ Authorized](../../adr/ADR-002-verified-eligible-authorized.md) | elections / governance | accepted |
| ADR-003 | [Governance-driven revocation](../../adr/ADR-003-governance-driven-revocation.md) | governance / trust | accepted |
| ADR-008 | [Geography domain consolidation](../../adr/ADR-008-geography-domain-consolidation.md) | geography | accepted |
| ADR-T-LOG | [Adjudication tactical implementation](../../adr/ADR-T-LOG-Tactical-Implementation.md) | adjudication | accepted |

## Notes (hygiene)

- The committee read/write decision previously existed as **three** files (`docs/0001-…`, `docs/adr/0001-…`, `docs/adr/ADR-0001-…write-read…`). These are consolidated to the single canonical `ADR-0001-committee-read-write-separation.md`; the duplicates are moved to [`archive/superseded-adrs/`](../archive/).
- `PHASE-3-COMPLETE.md` was **not** an ADR; it has been relocated out of `docs/adr/`.
- **Open follow-up:** the historical numbering is inconsistent (`ADR-001-trust` collides numerically with `ADR-0001-committee`). Renumbering is deferred to a dedicated ADR to avoid breaking external references; new ADRs follow `ADR-NNNN-kebab-title` per [naming conventions](../_meta/naming-conventions.md).
