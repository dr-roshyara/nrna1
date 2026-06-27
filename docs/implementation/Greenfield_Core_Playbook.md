# Greenfield Core — Implementation Playbook

**Status:** implementation-facing process spec for the greenfield Core (Contestation + Adjudication). TDD-first. Governs every slice from fitness-tests-red to merge.
**Date:** 2026-06-26 · References: ADR-T log · Canonical Event Catalog v1.0 · Failure Strategy · Round 50-06/07/08/09.

## Toolchain (standardized — complementary layers, ADR-T7)
| Tool | Role |
|------|------|
| **Deptrac** (primary) | bounded-context boundaries, layer/hexagonal enforcement, module isolation (TP-1/T2/T6). **Install via standalone PHAR** (not composer — keeps it out of project deps): download `deptrac.phar`, run `php deptrac.phar analyse --config-file=deptrac.yaml`. Config already staged. |
| **PHPStan** | static analysis (max level on `app/Contexts/*`) |
| **Pest / PHPUnit** | behavioral + aggregate/policy/event tests (RefreshDatabase per CLAUDE.md) |
| **`tests/Architecture/`** | bespoke constitutional rules (Q7 anonymity, single-producer, no-foreign-consumer) |

## Definition of Done — per aggregate
✓ State machine complete (allowed/forbidden/terminal, 50-07) ✓ invariants tested ✓ policies tested (50-06) ✓ events emitted via outbox (Catalog v1.0) ✓ repository implements interface; one root only (ADR-T6) ✓ consumers idempotent via inbox (ADR-T4) ✓ Deptrac + PHPStan + arch-tests green ✓ no `TODO`/no `user_id` on vote path ✓ ADR-T references in code/PR ✓ docs/runbook updated.

## Definition of Done — per slice
✓ all aggregate DoDs met ✓ end-to-end correction path passes (ChallengeRaised→…→ChallengeResolved) ✓ Failure-Strategy cases covered by tests ✓ Empirical Validation Plan rows populated (observed metrics) ✓ Architecture + Security review gates passed.

## Metrics dashboard (3 categories — formalized)
**Architecture:** dependency violations (Deptrac) · circular dependencies · aggregate size · event coupling · arch-fitness pass %.
**Domain (operational):** challenge resolution time · determinations issued · determination latency · replay-verification success % · evidence-integrity failures · election corrections applied · duplicate-event rate.
**Security:** voter↔vote linkage violations (must be 0) · replay failures · authorization failures.

## Architectural KPIs (Release gate — objective thresholds)
- **0** architecture/dependency violations (Deptrac) · **0** cyclic dependencies · **100%** arch-fitness green.
- **100%** aggregate invariants tested · **0** voter↔vote linkage (Q7) · **100%** replay-determinism contract pass.
- **100%** event-version compatibility (upcast tests) · **0** events emitted outside a txn/outbox.

## Architecture Compliance Checklist (every PR answers)
☐ new bounded context? ☐ new aggregate? ☐ new repository? ☐ new event (Catalog v1.0 updated + version)? ☐ new policy? ☐ violates an ADR-T? ☐ violates BDR 1.1? ☐ violates architecture tests? — *any "yes" requires an ADR + Architecture Review Gate sign-off before merge.*

## Slice Exit Criteria (Definition of Slice Complete — beyond per-aggregate DoD)
✓ tests (arch + behavioral) ✓ documentation/runbook ✓ ADR-T referenced/added ✓ architecture tests green ✓ **security review gate** ✓ **domain review** (metrics observed) ✓ code review ✓ benchmark/baseline recorded ✓ Implementation Traceability Matrix updated.

## Architecture Review Gate (before merge)
PR cannot merge until: matches **BDR 1.1** (no new/blurred boundary) · conforms to ADR-T1..T12 · Canonical Event Catalog unchanged or version-bumped (ADR-T5) · KPIs green · DoD checked. Reviewer signs "still matches certified architecture."

## Security Review Gate (before merge — high-assurance)
☐ threat model updated ☐ **anonymity preserved (no voter↔vote linkage introduced)** ☐ authorization checks complete (policies, 50-06) ☐ event payloads free of identifying data (ADR-T11) ☐ cryptographic assumptions unchanged (ADR-T13 limitation still recorded) ☐ no new attack surface on the voting/anonymity path.

## Slice 1 (first) — order
1. Fitness tests red (single-producer · one-aggregate-per-txn · no-foreign-consumer · Q7) + Deptrac config.
2. `Challenge` aggregate + state machine + `ChallengeStandingPolicy`/`ChallengeStateInvariant` → `ChallengeRaised…Routed`.
3. `AdjudicationService` (request-not-create) reads `Challenge` + `EvidenceEnvelope`.
4. `Determination` aggregate + finality invariant → `DeterminationIssued`.
5. Election reacts → `ElectionCorrectionApplied` (`ContainedOnly`) → `ChallengeResolved`.
6. Outbox wiring + **inbox** (ADR-T4); all gates green.

---
*Greenfield Core Playbook — toolchain (Deptrac+PHPStan+Pest+tests/Architecture); DoD (aggregate+slice); architectural KPIs; domain metrics; Architecture + Security review gates before merge; Slice 1 order. Implementation-facing — no new methodology rounds.*
