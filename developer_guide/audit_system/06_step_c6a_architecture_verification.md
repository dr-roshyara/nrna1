# Step C6A — The Guardrails: Messaging Architecture Verification

**Layer:** tests (fitness) + one Infrastructure tightening.
**Files:** `tests/Architecture/InboxMessagingArchitectureTest.php`, `docs/implementation/Messaging_Architecture_Verification.md`.
**Delivered by:** PB-003-C6A (`305cfb9ff`).

---

## Purpose

C1–C5 built a working Inbox. C6A proves it is a **reusable platform capability**, not a one-off, by turning its architectural intent into executable tests. Every future context (PB-004 onward) will depend on this subsystem; these guardrails guarantee it keeps the properties that make that dependency safe.

C6A also closes a real gap: the pre-existing architecture enforcement (`GreenfieldCoreArchitectureTest`, `deptrac.yaml`, `phpstan-greenfield.neon`) only ever scanned the Contestation and Adjudication contexts. `app/Contexts/Shared` — where the entire Inbox lives — had **zero** architecture coverage. C6A is the first executable protection for it.

---

## The governing rule: verify properties, not class names

Architecture tests must assert **architectural properties**, never concrete implementation names. The difference is the whole point:

- ✅ *"Exactly one component classifies handler outcomes."*
- ❌ *"`InboxExecutionEngine` classifies handler outcomes."*

The first survives a rename from `InboxExecutionEngine` to `InboxRuntime`; the second breaks on it while the architecture is still perfectly sound. A test that fails on a rename is testing the implementation, not the architecture. So C6A expresses ownership as **cardinality/uniqueness** ("exactly one file catches the markers"), which is a property, not a name.

All tests are pure-PHP file scans — no external tool, no shell (Windows-safe) — mirroring `GreenfieldCoreArchitectureTest`.

---

## The ten verified properties

| # | Property | Invariant | Test method |
|---|----------|-----------|-------------|
| 1 | Port purity | port imports no framework | `test_port_layer_is_framework_free` |
| 2 | Dependency direction | port does not import Infrastructure | `test_port_layer_has_no_infrastructure_dependency` |
| 3 | **Messaging ownership** | Shared messaging imports **no** bounded context | `test_messaging_layer_imports_no_bounded_context` |
| 4 | Single writer | `InboxEvent` referenced only in the messaging package | `test_inbox_persistence_model_has_a_single_owning_package` |
| 5 | Execution ownership | **exactly one** component catches the classification markers | `test_exactly_one_component_classifies_handler_outcomes` |
| 6 | Recovery ownership | **exactly one** component references `parkedDue` | `test_exactly_one_component_owns_retry_scheduling` |
| 7 | Transaction ownership | the execution component opens no transaction | `test_execution_component_opens_no_transaction` |
| 8 | Clock ownership | decision paths read no ambient time | `test_decision_paths_use_injected_time_only` |
| 9 | Clock allow-list | ambient time in messaging only stamps `processed_at` | `test_ambient_time_in_messaging_is_only_audit_metadata` |
| 10 | Message immutability | port data carriers are `readonly` | `test_port_data_carriers_are_immutable` |

Property #3 — **messaging ownership** — is the most important. It is the executable defence against business policy leaking into infrastructure: Shared may import `App\Contexts\Shared\...`, but any import of another context (`App\Contexts\Election\...`, etc.) fails the test. Infrastructure transports; it never decides.

---

## The strict-clock tightening

C6A adopted the stricter clock policy and made a small code change to back it:

```php
// before (C5): a re-drive decision could silently fall back to ambient time
public function scopeParkedDue($query, ?\DateTimeInterface $asOf = null)
{
    ... ->where('parked_until', '<=', $asOf ?? now());
}

// after (C6A): the instant is REQUIRED — a decision must receive its time
public function scopeParkedDue($query, \DateTimeInterface $asOf)
{
    ... ->where('parked_until', '<=', $asOf);
}
```

The default `now()` was the last place ambient time could sneak onto a decision path. Removing it means the *only* remaining ambient-time calls in the whole subsystem are `processed_at` audit stamps — which properties #8 and #9 now enforce exactly. (One C2-era test that called `parkedDue()` bare was updated to inject an explicit instant.)

---

## Guardrails must be falsifiable, not decorative

A fitness test that passes against correct code but *cannot fail* is worthless. C6A proves the three most important invariants (messaging ownership, execution ownership, clock allow-list) actually detect violations: each was run against a synthetic violating string and a clean string, confirming it flags the former and ignores the latter. When you add a property test, do the same — demonstrate it goes red before you trust it green.

---

## What this does *not* cover (yet) — C6B

C6A is **verification** (objective, executable). It is deliberately separate from **certification** (architectural judgment), which is step **C6B — Platform Capability Certification** (decision **D-11**). Before any business code (PB-004) may depend on the Inbox, C6B answers seven questions with evidence:

1. Can every future bounded context reuse the Inbox unchanged?
2. Can Replay reuse the execution component?
3. Can Monitoring observe the subsystem without modifying business code?
4. Can another framework host it by replacing only adapters?
5. Does Shared Infrastructure remain business-agnostic?
6. Does it satisfy all constitutional messaging invariants?
7. Has any architectural regression been detected?

A "No" to any of these halts implementation and triggers an architectural review. C6B also extends `deptrac.yaml` to include `app/Contexts/Shared`.

> **Governance note.** The verification matrix and the seven questions are a permanent template kept in `docs/implementation/Messaging_Architecture_Verification.md`. Folding them into the FROZEN `Implementation_Process_v1.0.md` requires a **v1.1** bump — until then, that companion doc is authoritative for messaging verification and certification.

---

## Running the guardrails

```bash
# the messaging guardrails only
php artisan test tests/Architecture/InboxMessagingArchitectureTest.php

# the full architecture fitness suite (should stay green)
php artisan test tests/Architecture

# the official static-analysis gate
vendor/bin/phpstan analyse --configuration=phpstan-greenfield.neon
```

At delivery: verification 10/10; full Inbox suite 44/44; Architecture Fitness 142 passed / 1 skipped; greenfield PHPStan clean.

---

## Traceability

ADR-T1/T4 · Blueprint §6/§7/§8 · temporal-determinism (ClockInterface) · D-11 (C6A/C6B split, property-based rule, strict clock, Platform Capability Certification gate) · `Messaging_Architecture_Verification.md`.
