# PB-003 — Architecture Readiness Report (ARR)

**Review type:** Architecture Readiness Review — the reviewer acted as the **Architecture Review Board (ARB)**, treating PB-003 as if implemented by another team, and attempting to **reject** it. Approval is granted only where objective evidence supports every architectural decision.
**Subsystem:** Inbox messaging infrastructure (`app/Contexts/Shared/{Application,Infrastructure}/Inbox`).
**Date:** 2026-07-07 · **Reviewer role:** ARB (C6B) · **Governs:** D-11.
**Result:** **CERTIFIED** — PB-003 is a reusable platform capability. PB-004 is authorized (pending its own IDD). Three review findings were remediated during this review (RED-first); three are recorded as accepted residual risk.

---

## 1. Method

Evidence re-validated independently, not trusted from the build sessions:
- app-wide `ripgrep` for marker-catch cardinality, `parkedDue` ownership, `InboxEvent` references, anonymity tokens, and `Shared/Domain` contents;
- full re-run of the Inbox suite, the Architecture Fitness suite, and the greenfield PHPStan gate;
- adversarial "how could this fail / how could a future developer violate it" pass per category.

One reviewer probe (`grep -E` for marker catches) produced a **false negative**; it was discarded and re-run with precise tooling — recorded here as a caution that scan tooling must itself be validated.

---

## 2. Readiness matrix

| Area | Result | Evidence |
|------|--------|----------|
| Strategic DDD (Shared is not a context) | **PASS** | `Shared/Domain` holds only shared-kernel base types (`DomainEvent`, `IntegrationEvent`, `TenantAggregateRoot`, `TenantId`, `ActorId`) — no domain-specific aggregates/business. Shared Kernel, not a bounded context. |
| Tactical DDD (single responsibility) | **PASS** | consume = dedupe-claim; engine = run+classify; redrive = select+delegate; registry = route; model = persist. No class holds two of these. |
| Messaging ownership (infra ≠ business) | **PASS** | `test_messaging_layer_imports_no_bounded_context` (now also catches inline FQN, F-3). |
| Hexagonal (ports inward) | **PASS** | `test_port_layer_is_framework_free` + `test_port_layer_has_no_infrastructure_dependency`. |
| Dependency direction | **PASS** | Infra→App→Domain; consumers import Shared, never the reverse. |
| Transaction ownership | **PASS** | `test_execution_component_opens_no_transaction`; consume + redrive each own one boundary (ADR-T1). |
| Execution ownership | **PASS** | `test_exactly_one_component_classifies_handler_outcomes` — now **app-wide** (F-2); ripgrep confirms exactly one marker-catch file. |
| Clock ownership / determinism | **PASS** | `test_decision_paths_use_injected_time_only` + `test_ambient_time_in_messaging_is_only_audit_metadata`; `scopeParkedDue()` requires an injected instant. |
| Retry ownership | **PASS** | `test_exactly_one_component_owns_retry_scheduling` — now **app-wide** (F-2); D-10. |
| Registry ownership / Open-Closed | **PASS** | Registry keyed by `(context, event_type)`; a new context registers without editing Shared. |
| Anonymity (ADR-T11 / Q7) | **PASS (remediated)** | `test_messaging_layer_has_no_voter_vote_linkage` added this review (F-1). Was previously unguarded for Shared. |
| Message immutability | **PASS** | `test_port_data_carriers_are_immutable`. |
| Extensibility / evolution | **PASS (design)** | PB-004 = a handler + registration + optional config; no Shared change required. |
| Replay readiness | **READY-BY-DESIGN** | Engine seam `execute(row, message, handler)` is reusable; **no replay driver exists yet** (see risk R-2). |
| Framework independence | **PASS** | Port is pure PHP; only adapters are Laravel-bound — porting rewrites adapters only. |
| Operational readiness | **PASS (read-side)** | `inbox:redrive` scheduled every minute; dead-letter reasons logged; parked/dead rows queryable per tenant. No active alerting emitter yet (R-3). |
| Maintainability | **PASS** | Property-based guardrails survive renames; each component small and single-purpose. |

---

## 3. Findings

### Remediated during this review (RED-first)

- **F-1 — Anonymity unguarded on the transport (HIGH).** No test forbade voter↔vote linkage tokens in the messaging subsystem; the pre-existing guard scoped only to Contestation/Adjudication. For a voting platform this is a real gap even though the code was clean. **Fix:** added `test_messaging_layer_has_no_voter_vote_linkage` scanning the port + infrastructure for `user_id`/`voter_id`/`voterId`/`voting_code`/`votingCode`. Falsifiability proven.
- **F-2 — Ownership cardinality was directory-scoped (MEDIUM).** "Exactly one execution/recovery owner" was asserted only within the `Inbox` directory; a second owner in another context would have slipped through. **Fix:** broadened both scans to app-wide (`SCAN = 'app'`). Falsifiability proven (two-owner case detected).
- **F-3 — Messaging-ownership scan was `use`-only (MEDIUM).** Inline `\App\Contexts\<X>\` fully-qualified references evaded detection. **Fix:** the scan now matches any `App\Contexts\<X>\` reference. Falsifiability proven.

### Accepted residual risk (documented, not blocking)

- **R-1 (was F-4) — Handler transaction discipline is convention, not enforced.** A handler that opens and commits its own nested transaction could persist partial work while still parking. No handlers exist yet. **Disposition:** document the constraint for handler authors; candidate guardrail when PB-004 introduces the first handler.
- **R-2 (was F-5) — Replay readiness is asserted, not proven.** The engine is designed for reuse by a future replay driver, but none exists. **Disposition:** validate when replay is built (PB-004+); no redesign anticipated.
- **R-3 (was F-6 / ops) — Shared Kernel and alerting are unguarded.** No test asserts `Shared/Domain` stays free of context-specific business, and dead-lettering emits a `Log::error` but no active alert. **Disposition:** future guardrail + an operational alerting adapter; neither blocks reuse.

---

## 4. Attempt-to-reject summary

| Rejection question | Answer | Basis |
|--------------------|--------|-------|
| Can a developer accidentally move election logic into Shared? | **No** | Messaging-ownership test (use + inline FQN). |
| Can a second execution/recovery owner appear unnoticed? | **No** | App-wide cardinality (F-2). |
| Can voter identity enter the transport? | **No** | Anonymity guard (F-1). |
| Can recovery create impossible history? | **No** | Redrive re-invokes the same handler with the same message; only timing differs (C5, D-10). |
| Can duplicates be processed twice? | **No** | Dedupe key + `lockForUpdate` + UNIQUE constraint (C2/C3); the constraint is the load-bearing guard under concurrency. |
| Can redrive run non-deterministically? | **No** | Injected clock + idempotent re-run test. |
| Can the subsystem survive five future contexts? | **Yes** | Registration-only extension; Open/Closed. |
| Can Replay be added without redesign? | **Yes, by design** | Engine seam — unproven until built (R-2). |
| Can Monitoring observe without touching business code? | **Yes (read-side)** | Table + structured logs; no active alerting yet (R-3). |
| Can another framework host it? | **Yes** | Pure port; adapters isolated. |

No rejection could be sustained with evidence. Certification granted.

---

## 5. Certification decision

**PB-003 is CERTIFIED** as the platform Messaging Infrastructure Foundation:
- all implementation and architecture evidence agree;
- DDD boundaries intact; Shared Infrastructure is business-agnostic and remains a Shared Kernel, not a context;
- no architectural regressions — protection **increased** (anonymity + app-wide ownership + inline-FQN coupling now guarded);
- the subsystem is reusable by future bounded contexts.

**Recommendation:** proceed to PB-003 Implementation Review, mark **Verified**, and **authorize PB-004** (which still requires its own IDD before implementation). Residual risks R-1/R-2/R-3 carry forward as tracked items, not blockers.

**Future ADR / refactor candidates:** Process v1.1 to absorb the verification matrix + this ARR template; a handler-transaction-discipline guardrail; a Shared-Kernel purity test; an operational alerting adapter for dead-letters.
