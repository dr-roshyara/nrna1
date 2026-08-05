# Messaging Architecture Verification Matrix

**Status:** living · permanent template (ARB direction, PB-003-C6A, 2026-07-07)
**Purpose:** certify that the Inbox subsystem is a **reusable platform messaging capability**, not a ticket-specific implementation. Verification is *objective* (executable fitness tests, this document). Certification is *judgment* (see Platform Capability Certification, C6B).

**Rule (architect):** verify architectural **properties**, never concrete class names. Renaming a component MUST NOT break a test; violating an invariant MUST. All tests are pure-PHP file scans (no external tool, Windows-safe), mirroring `GreenfieldCoreArchitectureTest`.

**Enforced by:** `tests/Architecture/InboxMessagingArchitectureTest.php` (10 properties). Scope gap this closes: the pre-existing greenfield gates (`GreenfieldCoreArchitectureTest`, `deptrac.yaml`, `phpstan-greenfield.neon`) cover only Contestation + Adjudication — `app/Contexts/Shared` had **zero** architecture coverage before C6A.

| # | Property | Invariant (property-based) | Test method | Status |
|---|----------|----------------------------|-------------|--------|
| 1 | Port purity | `Shared\Application\Inbox` imports no framework | `test_port_layer_is_framework_free` | PASS |
| 2 | Dependency direction | Port does not import Infrastructure | `test_port_layer_has_no_infrastructure_dependency` | PASS |
| 3 | **Messaging ownership** | Shared messaging imports **no** bounded context (`App\Contexts\<X>\`, X≠Shared) — transports, never decides | `test_messaging_layer_imports_no_bounded_context` | PASS |
| 4 | Single writer | The `InboxEvent` persistence model is referenced only inside the messaging package | `test_inbox_persistence_model_has_a_single_owning_package` | PASS |
| 5 | Execution ownership | **Exactly one** component catches the classification markers (cardinality, not name) | `test_exactly_one_component_classifies_handler_outcomes` | PASS |
| 6 | Recovery ownership | **Exactly one** component references retry scheduling (`parkedDue`) | `test_exactly_one_component_owns_retry_scheduling` | PASS |
| 7 | Transaction ownership | The execution component opens no transaction (runs in caller's txn; ADR-T1) | `test_execution_component_opens_no_transaction` | PASS |
| 8 | Clock ownership (decisions) | Execution + recovery paths read no ambient time — clock injected | `test_decision_paths_use_injected_time_only` | PASS |
| 9 | Clock allow-list | Any surviving ambient time in messaging is only an audit stamp (`processed_at`) | `test_ambient_time_in_messaging_is_only_audit_metadata` | PASS |
| 10 | Message immutability | Concrete data carriers in the port are `readonly` | `test_port_data_carriers_are_immutable` | PASS |
| 11 | Anonymity (ADR-T11/Q7) | Messaging transport carries no voter↔vote linkage token | `test_messaging_layer_has_no_voter_vote_linkage` | PASS (added C6B/ARR, F-1) |

**C6B ARR hardening:** properties 5/6 broadened from directory-scoped to **app-wide** (F-2); property 3 broadened to catch inline `\App\Contexts\<X>\` FQNs, not just `use` (F-3).

**Falsifiability proven (C6A):** invariants 3, 5, 9 verified to *detect* synthetic violations and *ignore* conformant code (guardrails, not vacuous assertions).

**Documented, accepted exceptions (property 9):** `InboxEvent::markProcessed()/markDead()` write `processed_at => now()` — audit metadata, not a decision input (temporal-determinism rule concerns scheduling/status, not metadata). `scopeParkedDue()` no longer has a `now()` default (removed C6A) — the instant is now required and injected.

---

## Platform Capability Certification (C6B — Architecture Readiness Review)

**Outcome: CERTIFIED (2026-07-07).** Conducted as an adversarial ARB review. Full report: [`PB-003_Architecture_Readiness_Report.md`](./PB-003_Architecture_Readiness_Report.md). Three guardrail findings (F-1 anonymity, F-2 app-wide ownership, F-3 inline FQN) were remediated RED-first during the review; three residual risks (R-1 handler txn discipline, R-2 replay unproven, R-3 shared-kernel/alerting) carry forward as tracked, non-blocking items.

Before PB-004 may begin, answer (evidence-backed). Any "No" halts implementation and requests architectural review.

1. Can every future bounded context reuse Inbox unchanged?
2. Can Replay reuse the execution component?
3. Can Monitoring observe the subsystem without modifying business code?
4. Can another framework host the subsystem by replacing only adapters?
5. Does Shared Infrastructure remain business-agnostic?
6. Does the subsystem satisfy all constitutional messaging invariants?
7. Has any architectural regression been detected?

> **Governance note:** this matrix + the 7 questions are a permanent template. Folding them into the FROZEN `Implementation_Process_v1.0.md` requires a **v1.1** bump (or ADR); until then this companion doc is authoritative for messaging verification/certification. See D-11.
