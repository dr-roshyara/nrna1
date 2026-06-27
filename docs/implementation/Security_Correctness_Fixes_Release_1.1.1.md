---
knowledge_id: IMP-SEC-001
title: Security & Correctness Fixes — Release 1.1.1
knowledge_type: implementation_audit
bounded_context: global
status: certified
authority: authoritative
audience: [developer, architect]
owner: Chief Architect
reviewers: []
version: 1.0
schema_version: 1
tags: [security, correctness, release-1.1.1, tdd, capability, adjudication, election, constitutional-governance]
topic: release-audit

implements: []
requires: []
depends_on: []
derived_from: []
related_to:
  - Architecture_Baseline_1.1
  - Architecture_Debt_Backlog
  - Implementation_Architecture_Constitution_v1.0
verified_by:
  - ADR-001
  - ADR-002
  - ADR-003
  - ADR-T3
  - ADR-T7
  - ADR-T15
tested_by:
  - tests/Unit/Application/Election/ElectionCapabilityResolverTest.php
  - tests/Unit/Application/Election/CapabilityDecisionTest.php
  - tests/Unit/Application/Election/Capabilities/Policies/AttestationGuardTest.php
  - tests/Feature/Routes/VotingEligibilityMiddlewareRegressionTest.php
  - tests/Unit/Application/Election/ConstitutionalTransitionGuardSystemRoleBypassTest.php
  - resources/js/__tests__/composables/useElectionActions.test.ts
  - tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapterTest.php
  - tests/Unit/Application/Election/Security/TrustPolicyEvaluatorNetworkRestrictionTest.php

code_refs:
  - app/Application/Election/Services/ElectionCapabilityResolver.php
  - app/Application/Election/Capabilities/CapabilityDecision.php
  - app/Providers/AppServiceProvider.php
  - routes/election/electionRoutes.php
  - app/Application/Election/Services/ConstitutionalTransitionGuard.php
  - resources/js/composables/useElectionActions.ts
  - app/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapter.php
  - app/Application/Election/Security/TrustPolicyEvaluator.php

last_review: 2026-06-27
next_review: 2026-12-27
---

# Security & Correctness Fixes — Release 1.1.1

**Branch:** `enhance-election-only`  
**Date:** 2026-06-27  
**Commits:** `9cb0a664` (S1–S3) · `ec8ee295` (S4–S8)  
**Test coverage:** 73/73 DB-free unit tests green  
**Severity classification:** S1–S8 (correctness defects identified in structured code review)

---

## Summary

Eight correctness defects were identified during the pre-merge review of the `enhance-election-only` branch. All were fixed using TDD discipline: each regression test was confirmed RED before the fix was applied, then confirmed GREEN after. No fix altered the public API of any domain entity.

| ID | Layer | Severity | Title |
|----|-------|----------|-------|
| S1 | Application | **High** | ElectionCapabilityResolver evaluation short-circuit on grants |
| S2 | Application | **Medium** | CapabilityDecision abstain/authorized structural ambiguity |
| S3 | Infrastructure | **High** | EvidenceCapabilityPolicy wired with wrong signal string |
| S4 | HTTP / Routes | **High** | vote.eligibility middleware missing from Step 1 route group |
| S5 | Application | **Critical** | ConstitutionalTransitionGuard system-role bypass |
| S6 | Frontend | **Medium** | useElectionActions CSRF failure (raw fetch → Inertia router) |
| S7 | Infrastructure | **High** | OutboxEventAdapter tenant context null cast → silent event loss |
| S8 | Application | **Medium** | TrustPolicyEvaluator null-coalescing operator precedence bug |

---

## S1 — ElectionCapabilityResolver: grants short-circuit remaining policies

**File:** `app/Application/Election/Services/ElectionCapabilityResolver.php`  
**Commit:** `9cb0a664`  
**Test:** `tests/Unit/Application/Election/ElectionCapabilityResolverTest.php` (211 lines, 43/43 green)

### Root Cause

The evaluation loop treated any `granted()` decision as final, returning immediately and skipping all remaining capability policies. The intended semantics: only explicit denials are final and immediate; grants require all policies to complete without denial.

### Impact

A voter who satisfied any single capability policy was immediately granted full access, regardless of what subsequent policies might deny. A late-running denial policy was silently bypassed.

### Fix Applied

Remove the early `return` on `granted()` outcomes. Continue iterating. Return the grant only after the full policy sequence completes without encountering a denial. Explicit denials still return immediately.

### Security Implication

Authorization bypass. Capability policies added after the granting policy were never evaluated. Any denial-only policy added to harden a specific capability was structurally unreachable.

---

## S2 — CapabilityDecision: abstain/authorized structural ambiguity

**File:** `app/Application/Election/Capabilities/CapabilityDecision.php`  
**Commit:** `9cb0a664`  
**Test:** `tests/Unit/Application/Election/CapabilityDecisionTest.php` (44 lines)

### Root Cause

`abstain()` and `authorized()` returned the same structural shape. The resolver could not distinguish "this policy has no opinion" from "this policy explicitly granted". An abstaining policy was therefore treated as a grant, triggering the S1 short-circuit on abstentions.

### Impact

Combined with S1: any policy that abstained (returned no opinion) caused the resolver to exit early with a grant. This is the mechanism by which S1 was practically exploitable — policies that should have been skipped were instead promoting access.

### Fix Applied

Add `CapabilityOutcome` as a discriminator type. `abstain()` and `authorized()` now produce structurally distinct objects that the resolver can branch on without boolean flags.

### Security Implication

Architectural enabler of S1. Without the discriminator, any structural fix to S1 required runtime string comparisons on decision metadata — fragile and untestable.

---

## S3 — EvidenceCapabilityPolicy: wrong signal string wired

**File:** `app/Providers/AppServiceProvider.php:271`  
**Commit:** `9cb0a664`  
**Test:** `tests/Unit/Application/Election/Capabilities/Policies/AttestationGuardTest.php` (113 lines)

### Root Cause

`AppServiceProvider` wired `TrustCapabilityPolicy` as the attestation capability handler. That policy's guard checked for overlay signal `'ATTESTATION_AVAILABLE'`. However, overlays emit `'ADDITIONAL_ATTESTATION_PRESENT'`. The signal never matched — the policy's guard was permanently dead code.

### Impact

Attestation-based capability grants were never issued, regardless of actual attestation evidence. Voters who completed verification were denied access silently. No error was raised; the policy simply never fired.

### Fix Applied

Replace `TrustCapabilityPolicy` with `EvidenceCapabilityPolicy` in `AppServiceProvider`. `EvidenceCapabilityPolicy` checks the correct signal string `'ADDITIONAL_ATTESTATION_PRESENT'` that overlays actually emit.

### Security Implication

Feature regression (attestation grants non-functional). Lower security risk than S1/S5, but high correctness risk: a verification flow that logs success but silently denies access creates audit inconsistency and user confusion.

---

## S4 — vote.eligibility middleware missing from Step 1 route group

**File:** `routes/election/electionRoutes.php`  
**Commit:** `ec8ee295`  
**Test:** `tests/Feature/Routes/VotingEligibilityMiddlewareRegressionTest.php` (16 tests, 149 lines)

### Root Cause

During a route refactor, the `vote.eligibility` middleware was removed from the Step 1 route group (code-entry). It remained on Step 2 and later. The route guard was incomplete.

### Impact

An ineligible voter (suspended, wrong election, expired membership) could reach the `/code/create` endpoint and obtain a ballot code before being blocked at Step 2. The Step 2 guard would correctly deny further progress, but the code had already been issued — creating orphaned codes in the audit trail and potentially confusing dispute resolution.

### Fix Applied

Restore `vote.eligibility` to the Step 1 route group. Ineligibility is now caught before any code is issued.

### Security Implication

Medium. The voter could not cast a vote (Step 2 still blocked), but orphaned codes distort the audit trail and complicate post-election verification.

---

## S5 — ConstitutionalTransitionGuard: system-role bypass

**File:** `app/Application/Election/Services/ConstitutionalTransitionGuard.php`  
**Commit:** `ec8ee295`  
**Test:** `tests/Unit/Application/Election/ConstitutionalTransitionGuardSystemRoleBypassTest.php` (3 tests, 187 lines)

### Root Cause

`userHasAnyRole()` had a special branch for the `'system'` role. If `'system'` was the only required role, it returned `true` unconditionally — never checking `Auth::user()`. The intent was to allow background jobs and schedulers (which run without an authenticated user) to trigger system-reserved transitions. The implementation inverted the check: it allowed everyone instead of allowing only the unauthenticated system context.

```php
// BEFORE (BUG) — any caller passes, no auth check:
if (count($requiredRoles) === 1) {
    return true;  // ← unconditional
}

// AFTER (FIX) — null user = system job context (safe):
if (count($requiredRoles) === 1) {
    return Auth::user() === null;
}
```

### Impact

Any authenticated HTTP user — including ordinary voters — could POST to endpoints that trigger system-reserved transitions such as `auto_submit` and `auto_close`. Election state could be forcibly advanced by any authenticated principal with network access.

### Fix Applied

`Auth::user() === null` is the correct system-principal signal. Queue jobs and schedulers run without an authenticated user; HTTP requests always have one. The guard now returns `true` only when no human principal is present.

### Security Implication

**Critical privilege escalation.** System-reserved election state transitions (auto_submit, auto_close, tally) were accessible to any authenticated user via HTTP. An attacker who could log in could close an election early or force vote tallying before the voting window ended.

---

## S6 — useElectionActions: raw fetch bypasses Inertia CSRF handling

**File:** `resources/js/composables/useElectionActions.ts`  
**Commit:** `ec8ee295`  
**Test:** `resources/js/__tests__/composables/useElectionActions.test.ts` (5 Vitest tests — requires `npm install`)

### Root Cause

`completePhase()` used raw `fetch()` with no `X-CSRF-TOKEN` header and no `X-Inertia` header. In Inertia 2.0, the server returned a 302 HTML redirect response (not JSON). `response.json()` threw `SyntaxError`. The error was silently swallowed in the catch block; the UI showed no feedback.

### Impact

Election phase transitions (Nomination → Voting, Voting → Closed, Closed → Results) silently failed. The backend state was never updated. The UI appeared to succeed (no error shown) while the election remained in its prior phase. Only a page refresh would reveal the failure.

### Fix Applied

Replace `fetch()` with `router.post()` from `@inertiajs/vue3`. Inertia's router handles CSRF tokens automatically (via meta tags), follows redirects correctly, and exposes `onSuccess`/`onError` callbacks for explicit state management.

### Security Implication

Medium. No unauthorized access; CSRF was the failure mode, not exploitation. The practical impact was operational: phase transitions were broken, requiring workarounds or direct database intervention.

---

## S7 — OutboxEventAdapter: null tenant context cast to empty string

**File:** `app/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapter.php`  
**Commit:** `ec8ee295`  
**Test:** `tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapterTest.php` (4 tests, 173 lines)

### Root Cause

```php
// BEFORE (BUG):
'organisation_id' => (string) TenantContext::get(),
// TenantContext::get() returns null when no tenant set.
// (string) null === '' — empty string is not a valid UUID.
// PostgreSQL raises PDOException on the NOT NULL UUID FK column.
// The exception bubbles out of writeDeterminationIssued() uncaught.
// The domain event is never persisted. No retry. No log at business layer.

// AFTER (FIX):
'organisation_id' => TenantContext::require(),
// require() throws RuntimeException immediately if tenant is null.
// The failure is explicit, logged, and visible before any DB interaction.
```

### Impact

`DeterminationIssued` domain events were silently lost whenever the outbox adapter ran without tenant context set — a condition that could arise from queue jobs that missed the tenant bootstrapping middleware. The Adjudication bounded context's event trail was incomplete with no visible error at the application layer.

### Fix Applied

`TenantContext::require()` was already implemented and tested. This fix applies it: the failure is made explicit and proximal (before the DB call) rather than implicit and distal (PostgreSQL exception buried in a queue job log).

### Security Implication

Data integrity. Silent event loss in an adjudication system breaks the audit trail for challenge determinations — the exact record that election integrity disputes depend on.

---

## S8 — TrustPolicyEvaluator: null-coalescing operator precedence

**File:** `app/Application/Election/Security/TrustPolicyEvaluator.php:139`  
**Commit:** `ec8ee295`  
**Test:** `tests/Unit/Application/Election/Security/TrustPolicyEvaluatorNetworkRestrictionTest.php` (5 tests, 173 lines)

### Root Cause

```php
// BEFORE (BUG):
$restrictionEnabled = $election?->network_binding_strategy !== 'none' ?? true;

// PHP operator precedence:
//   ($election?->network_binding_strategy !== 'none') ?? true
//   The ?? fires only when its left operand is null.
//   !== always returns bool — never null. The ?? true is permanently dead.
//
// When $election is null:
//   $election?->network_binding_strategy → null
//   null !== 'none'                      → true   ← PHP: null IS NOT EQUAL to 'none'
//   $restrictionEnabled = true           ← wrongly restricts all voters

// AFTER (FIX):
$restrictionEnabled = $election !== null && $election->network_binding_strategy !== 'none';
//   null election          → false  (no policy configured — not restricted)
//   strategy 'none'        → false  (explicitly disabled)
//   strategy 'ip_count'    → true   (restricted)
//   strategy 'strict'      → true   (restricted)
```

### Impact

In demo/detached context (no election record), all voters were blocked by network restriction regardless of their IP. The developer's fallback intent ("restrict if unknown") was inverted by precedence: `null !== 'none'` evaluates to `true` in PHP, not `null`.

### Fix Applied

Explicit null guard before the strategy comparison. The precedence ambiguity is eliminated; the semantics are now readable at a glance.

### Security Implication

Low security risk, high correctness risk. Demo mode was unusable for IP-restricted elections. The dead `?? true` also represents a class of PHP precedence trap worth noting: `??` applied to the result of a comparison operator is always dead code.

---

## Traceability

| Finding | Commit | Test File | ADR |
|---------|--------|-----------|-----|
| S1 | `9cb0a664` | `ElectionCapabilityResolverTest.php` | ADR-002 (Verified/Eligible/Authorized) |
| S2 | `9cb0a664` | `CapabilityDecisionTest.php` | ADR-002 (Verified/Eligible/Authorized) |
| S3 | `9cb0a664` | `AttestationGuardTest.php` | ADR-001 (Trust Attestation Domain) · ADR-T7 (executable conformance) |
| S4 | `ec8ee295` | `VotingEligibilityMiddlewareRegressionTest.php` | ADR-002 (Verified/Eligible/Authorized) |
| S5 | `ec8ee295` | `ConstitutionalTransitionGuardSystemRoleBypassTest.php` | ADR-003 (Governance-driven Revocation) |
| S6 | `ec8ee295` | `useElectionActions.test.ts` | N/A (Inertia 2.0 migration rule — infra boundary) |
| S7 | `ec8ee295` | `OutboxEventAdapterTest.php` | ADR-T3 (outbox at-least-once) · ADR-T15 (EventOutbox port) |
| S8 | `ec8ee295` | `TrustPolicyEvaluatorNetworkRestrictionTest.php` | ADR-001 (Trust Attestation Domain) |

> ADR references are to the architectural decisions whose intent this fix restores. Where no ADR exists, the fix is a correctness defect with no architectural decision to trace to.

---

## Test Coverage Summary

| ID | Test File | Tests | Approach |
|----|-----------|-------|----------|
| S1 | `ElectionCapabilityResolverTest.php` | included in 43 | DB-free, PHPUnit\TestCase |
| S2 | `CapabilityDecisionTest.php` | included in 43 | DB-free, PHPUnit\TestCase |
| S3 | `AttestationGuardTest.php` | included in 43 | DB-free, PHPUnit\TestCase |
| S4 | `VotingEligibilityMiddlewareRegressionTest.php` | 16 | DB-free, PHPUnit\TestCase |
| S5 | `ConstitutionalTransitionGuardSystemRoleBypassTest.php` | 3 | DB-free, anonymous Authenticatable stub |
| S6 | `useElectionActions.test.ts` | 5 | Vitest, router.post mock |
| S7 | `OutboxEventAdapterTest.php` | 4 | DB-free, real value objects |
| S8 | `TrustPolicyEvaluatorNetworkRestrictionTest.php` | 5 | DB-free, ReflectionClass |

**Total DB-free PHP tests at commit ec8ee295:** 73/73 green.

All PHP tests use `PHPUnit\Framework\TestCase` (not `Tests\TestCase`) and bootstrap the Laravel application manually. No database connection is required; no `RefreshDatabase` trait is used. Tests are isolated from the development database.

---

## Verification Protocol

Each fix followed this sequence:

1. Write regression test targeting the specific defect
2. Confirm test RED (failure message explicitly demonstrates the bug)
3. Apply fix — minimal, scoped to the defect
4. Confirm test GREEN
5. Run full DB-free suite to confirm no regressions

No fix was applied before step 2 was confirmed in terminal output. No test was written after a fix was applied.

---

*AKB Entry for branch `enhance-election-only` → `main` merge review.*  
*Authored: 2026-06-27 · Review: Chief DDD Architect*
