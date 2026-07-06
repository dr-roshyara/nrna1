# Architecture Review Checklist

**Status:** governance instrument · applied to EVERY Push B (and later) Pull Request before merge.
**Rule:** No PR may be merged unless every changed class maps to a Blueprint section, applicable ADR(s), and a Traceability Matrix row. Any unchecked box blocks the merge; any "yes" on the last two questions routes to the Architecture Review Gate instead of merging.
**Date:** 2026-07-06 · Source: PushB_Architecture_Blueprint v1.0 §20 (ARB condition)

## Per-PR checklist

**Vocabulary & events**
- ☐ Uses only approved events (Canonical Event Catalog v1.0 / Blueprint §3) — no new names, no synonyms
- ☐ Every produced event has exactly one producer (AT-EVT-001; Blueprint §10)
- ☐ Event payloads carry no voter↔vote linkage, no identifying data (CI-5 / Q7 / ADR-T11)
- ☐ Envelope complete: EventId · CorrelationId · CausationId · SchemaVersion · TenantId propagation (Blueprint §14)

**Aggregates & transactions**
- ☐ Uses approved aggregates only (Challenge / Determination / Election lifecycle — Blueprint §4)
- ☐ One aggregate root per transaction; outbox row(s) appended in the SAME transaction (ADR-T1; AT-TXN-001)
- ☐ Guard → mutate → event → outbox → COMMIT ordering respected (Round50-07)
- ☐ No aggregate creates another aggregate (request-not-create, TP-2)
- ☐ Repositories persist exactly one root; no cross-root queries in repositories (ADR-T6)

**Messaging**
- ☐ Outbox written atomically via the context's own EventOutbox adapter (no reflection writer for loop events)
- ☐ Consumers idempotent via inbox `(event_id, consumer_context)` — no ad-hoc dedupe (ADR-T4; Blueprint §6)
- ☐ No synchronous cross-context call introduced anywhere (Blueprint §11)
- ☐ Handler exceptions classified: already-done / transient / permanent (Blueprint §8)

**Constitution & governance**
- ☐ No constitutional invariant violated or weakened (CI-1 / CI-3 / CI-4 / CI-5)
- ☐ Business invariants respected (BI-1: ≤1 binding determination per challenge; BI-2: each determination → exactly one challenge)
- ☐ Decision authority respected — no context decides outside its §10.1 row
- ☐ Traceability references present in code/PR (Blueprint § · ADR-T · CI/BI)
- ☐ Implementation Traceability Matrix row updated
- ☐ Decision Log updated (if any implementation decision was made)

**Escalation questions**
- ☐ Does this PR require a new ADR? (new architectural decision → write it BEFORE merge)
- ☐ Does this PR deviate from Blueprint v1.0? (→ STOP: Architecture Review Gate; deviation only via Blueprint v1.1 — never merged silently)

---
*Architecture Review Checklist — per-PR governance instrument; created as ARB condition 2026-07-06; enforced alongside Architecture + Security Review Gates.*
