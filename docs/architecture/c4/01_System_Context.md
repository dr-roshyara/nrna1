# C1 — System Context

**Diagram:** [`plantuml/SystemContext.puml`](plantuml/SystemContext.puml) · **Derived from:** 38C-15 ruling · ADR-5 standing classes · CLAUDE.md platform overview · routes/controllers (verified)

## Explanation
One system: the **NRNA Governance Platform** (multi-tenant election, membership, contestation and adjudication). Six human actor roles interact over HTTPS; the only external system is SMTP email.

## Assumptions
1. Actor list derives from the standing classes (S-1 Directly Affected / S-2 Constitutional Observer / S-3 Authority Peer, ADR-5) plus operational roles present in routes (officer, committee, platform admin).
2. Auth is internal (Laravel Fortify/Sanctum sessions) — hence no Identity Provider box.

## Rationale for exclusions (prompt rule: "only approved relationships")
- **External Identity Provider** — not documented in any frozen artifact; excluded.
- **External Trust Anchor** — deliberately absent by constitutional ruling: 38C-15 adopted **Option B (Functional Independence)** — a single sovereign source with permanent safeguards S-1..S-5. Modeling an external anchor would contradict the ruling.
