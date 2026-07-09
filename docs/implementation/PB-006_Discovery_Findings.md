# PB-006 (Integration Tests IT-1..IT-8) — Phase 1 Discovery Findings

**Status:** Discovery (no IDD, no code). **2026-07-10.** Contains one **plan-invalidating architectural finding** requiring ARB ruling before the IDD.

## 1. Scope (grounded)
PB-006 = author **IT-1..IT-8** (Blueprint §9, `PushB_Architecture_Blueprint.md:227-240`): E2E Upheld (IT-1) · E2E Dismissed (IT-2) · per-consumer idempotency (IT-3) · out-of-order park/redrive (IT-4) · relay registry (IT-5) · failure injection F5/F9 (IT-6) · fitness (IT-7) · observability/correlation (IT-8). Every failure-model case F1–F9 gets at least one test. Board: L, 0/12 WBS, depends on PB-004+PB-005 (both closed).

## 2. THE FINDING — the outbox→inbox delivery bridge is ABSENT (F-PB006-1)
**Observed:**
- The relay (`OutboxEventProcessor`, scheduled `outbox:process`) hydrates each outbox row and dispatches the domain event + an `IntegrationEvent` onto the **Laravel event bus** — and stops there.
- **No production code calls `Inbox::consume()`** (repo-wide: only tests + a dev-guide sample). No listener is bound to the relay's `IntegrationEvent`. Context providers only *register* handlers in `InboxHandlerRegistry` — registration ≠ delivery. `RedriveParkedInboxEvents` can only re-drive rows that already exist; production never creates the first row for loop events.
- Existing "integration" tests (PB-004 4B, PB-005 5C) **hand-construct `InboxMessage` and call `consume()` directly**, side-stepping the gap.

**Derived:** IT-1/IT-2 as specified (`…issue→relay→correction→relay→adjudicated→resolved…`) are **not testable end-to-end today** — the chain breaks between relay dispatch and inbox consumption. In production terms: the correction loop's slices are individually correct, but **no mechanism carries an event from a producer's outbox to a consumer's inbox.**

**Classification (Interpreted):** a **missing platform capability**, not an implementation defect and not a documentation error. The Messaging Platform is FROZEN — but R-29's own escape clause is precisely this situation: *"no platform change unless a feature demonstrates the current platform is insufficient."* PB-006 is that demonstration. Per the **ARR gate**, a change to a frozen capability is an architecture decision → STOP and request ARB review (this document).

## 3. Options for ARB ruling
| | Option | Consequence |
|---|---|---|
| **A (recommended)** | **Build the delivery bridge as a platform capability, ARB-authorized, before/within PB-006.** A thin in-process dispatcher: listener (or relay step) on the relay's output → for each consumer registered for the event type in `InboxHandlerRegistry` → build `InboxMessage` (event_id, type, payload, organisation, correlation/causation) → `Inbox::consume($msg, $handler)`. Reuses every frozen piece (registry, inbox, engine, redrive); adds only the missing link. Requires ARB authorization + an ADR (new platform capability) + its own RED-first slice. IT-1/IT-2 then test the REAL production path. | True operational validation; the loop actually runs in production. Platform change (justified under R-29 escape). |
| B | Hand-stitch delivery in IT-1/IT-2 tests (as PB-004/5 tests did). | No platform change, but PB-006 would validate *loop semantics*, NOT operational delivery — "implementation ≠ operational validation" would remain true even after PB-006. The production gap stays. |
| C | Defer the bridge to a new PB ticket; PB-006 stitches manually now. | Same weakness as B for PB-006; adds sequencing overhead. |

**Recommendation (Recommended):** **Option A.** PB-006's entire purpose is operational validation; validating a hand-stitched path would certify something production cannot do. The finding satisfies the freeze's evidence bar. Proposed shape (design only, for the IDD after your ruling): `InboxDeliveryBridge` (Shared Infrastructure) — resolves all registered consumers for an event type and calls `Inbox::consume` per consumer; invoked from the relay after successful dispatch (or as an `IntegrationEvent` listener); per-consumer isolation (one consumer's park/failure never blocks another); org/correlation propagated from the outbox row (D-06).

## 4. Everything else in scope is ready (no other blockers)
Inbox semantics (dedupe/park/redrive/dead-letter) proven at unit/feature level (PB-003); both reacting contexts qualified (PB-004/5); `outbox:process` + `inbox:redrive` scheduled; fitness + anonymity checks in place for IT-7; correlation/causation columns exist for IT-8.

## STOP
Awaiting ARB ruling on F-PB006-1 (Option A/B/C) before producing the PB-006 IDD. No IDD, no code.
