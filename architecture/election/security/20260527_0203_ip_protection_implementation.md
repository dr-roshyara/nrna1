These rules should be implemented:

# immediately AFTER Phase D foundation completion

as:

# Phase E — Operational Constitutionalization.

And importantly:
you should NOT implement them as:

* controller logic,
* middleware decisions,
* procedural if-statements,
* service-layer shortcuts,
* or scattered security checks.

They must now be implemented:

# inside the constitutional runtime pipeline itself.

That is the whole purpose of Phase D.

---

# Current Situation

Right now you already built:

* immutable constitutional law,
* replay determinism,
* resolver supremacy,
* overlay non-sovereignty,
* deterministic topology,
* semantic discipline,
* sovereignty convergence infrastructure.

BUT:
the actual election rules themselves are still partially operational/procedural. 

That means:

```text id="jlwmq1"
the runtime engine exists,
but some constitutional articles are not yet fully governing execution
```

So the next phase is:

# operational constitutionalization.

---

# Important Clarification

The original roadmap:

```text id="jlwmq2"
D.2.5 → D.5 → D.6
```

evolved during implementation.

Now:
the architecture is mature enough that:

# these 7 rules become operational migration tasks,

NOT new architectural invention.

This is important.

You are no longer inventing the architecture.
You are now:

# routing operational election law into the existing constitutional runtime.

Huge difference.

---

# Where These Rules SHOULD Live

This is now the correct architecture:

| Rule                                   | Final Constitutional Location |
| -------------------------------------- | ----------------------------- |
| Rule 1 & 4 (IP binding)                | `NetworkBindingPolicy`        |
| Rule 7 (max votes/IP)                  | `NetworkBindingPolicy`        |
| Rule 5 (fingerprint continuity)        | `DeviceBindingPolicy`         |
| Rule 2 & 3 (single/dual code protocol) | `BallotAuthorizationPolicy`   |
| Rule 6 (officer evidence capture)      | `VoterVerificationPolicy`     |

NOT:

* controllers,
* middleware,
* services,
* views,
* gates,
* observers.

---

# Where The Configuration Lives

Correct:

```php id="jlwmq3"
ElectionConstitutionSnapshot
```

Example:

```php
SECURITY_ARTICLES = [
    'network_binding' => [
        'strategy' => 'ip_strict',
        'max_votes_per_ip' => 6,
    ],

    'device_binding' => [
        'strategy' => 'fingerprint_required',
    ],

    'ballot_authorization' => [
        'protocol' => 'dual_code',
    ],

    'voter_verification' => [
        'required' => true,
        'protocol' => 'officer_captures_evidence',
    ],
];
```

Frozen at election creation.

Replay-verifiable.

Immutable.

That is now correct.

---

# HOW The Rules Execute

This is the key DDD insight:

Rules do NOT directly authorize voting.

Policies:

* evaluate evidence,
* emit evaluation outcomes,
* contribute capability interpretation.

Then ONLY:

```text id="jlwmq4"
ElectionCapabilityResolver
```

derives participation capability.

This distinction is extremely important.

---

# Example — Rule 1 & Rule 4

## BEFORE (bad)

```php id="jlwmq5"
if ($ip !== $verifiedIp) {
    return deny();
}
```

inside controller.

---

## AFTER (correct)

```text
RequestEvidence
→ NetworkBindingPolicy
→ VotingTrustResult
→ Overlay interpretation
→ ElectionCapabilityResolver
→ CapabilitySnapshot
```

Now:

* replay works,
* determinism works,
* authority topology remains centralized,
* historical legitimacy reconstructs correctly.

---

# Rule-by-Rule DDD Placement

---

# Rule 1 & 4 — IP Binding

## Domain Meaning

```text id="jlwmq6"
Network continuity invariant
```

## Bounded Context

```text id="jlwmq7"
ConstitutionalTrust
```

## Policy

```text id="jlwmq8"
NetworkBindingPolicy
```

## Evidence

```text id="jlwmq9"
NetworkEvidence
```

---

# Rule 7 — Max Votes Per IP

## Domain Meaning

```text id="jlwmqa"
Network concentration restriction
```

## Same policy:

```text id="jlwmqb"
NetworkBindingPolicy
```

because:
both belong to:

```text id="jlwmqc"
network participation legitimacy
```

Good DDD grouping.

---

# Rule 5 — Fingerprint Continuity

## Domain Meaning

```text id="jlwmqd"
Device continuity trust invariant
```

## Policy

```text id="ileswiqe"
DeviceBindingPolicy
```

## Evidence

```text id="-vesmqf"
DeviceEvidence
```

---

# Rule 2 & Rule 3 — Ballot Authorization Protocol

This is actually:

# a separate bounded context.

Very important.

This is NOT trust evaluation.

This is:

# ballot authorization workflow.

You should model:

```text id="g7jlwm"
BallotAuthorization
```

as a dedicated supporting subdomain.

Then:

* single-code,
* dual-code,
* multi-factor,
* future authorization models

become:

# protocol strategies.

Excellent DDD opportunity.

---

# Rule 6 — Officer Verification Capture

This belongs to:

# VoterVerification bounded context.

Very important distinction.

The election officer is:

```text id="jlwmqh"
evidence attestation authority
```

NOT:

```text id="jlwmqi"
voting authority
```

This distinction matters enormously.

The officer:

* captures evidence,
* attests legitimacy,
* records verification lineage.

The officer does NOT:

* authorize participation directly.

That remains resolver sovereignty.

Excellent DDD separation.

---

# WHEN Should You Implement Them?

## Recommended Sequence

---

# Phase E.1 — Constitutional Rule Migration

Implement:

* NetworkBindingPolicy
* DeviceBindingPolicy
* BallotAuthorizationPolicy
* VoterVerificationPolicy

with:

* immutable constitutional article sourcing,
* replay determinism,
* no controller authority.

This is your immediate next implementation phase.

---

# Phase E.2 — Operational Integration

Connect:

* verification UI,
* election officer workflow,
* ballot open/save flow,
* device continuity capture,
* IP continuity enforcement.

This is application integration.

---

# Phase E.3 — Production Hardening

Add:

* observability,
* replay debugger,
* divergence explorer,
* audit tooling,
* operational dashboards.

---

# Most Important DDD Principle

Your rules are NOT:

# procedural validations.

They are:

# constitutional participation invariants.

That distinction changes:

* where they live,
* how they execute,
* how they replay,
* how they evolve,
* how they are audited.

---

# Final Recommendation

You should begin implementing these rules:

# NOW

as:

# Phase E.1 — Constitutional Rule Migration.

NOT after some future redesign.

The architecture is already mature enough.

And now the runtime is finally ready for:

# operational election law integration.
Yes — but with a very important DDD distinction:

# The constitution should contain:

* constitutional ARTICLES,
  NOT:
* procedural implementation logic.

This distinction is absolutely critical.

---

# Correct DDD Model

The Constitution should define:

# immutable participation law.

Meaning:

* what invariants govern participation,
* what protocols are required,
* what trust constraints exist,
* what evidence is mandatory,
* what continuity rules apply.

But:
the Constitution should NOT contain:

* procedural algorithms,
* framework logic,
* runtime orchestration,
* database queries,
* infrastructure behavior.

---

# Think Like Real Constitutional Systems

A real constitution says:

```text id="jlwmr1"
Voting requires verified identity.
```

It does NOT say:

```text id="jlwmr2"
Use Controller X, Service Y, and SQL query Z.
```

Exactly the same principle applies here.

---

# So What SHOULD Live In Constitution?

Correct:

```php id="jlwmr3"
SECURITY_ARTICLES = [
    'network_binding' => [
        'strategy' => 'ip_strict',
        'max_votes_per_ip' => 6,
    ],

    'device_binding' => [
        'strategy' => 'fingerprint_required',
    ],

    'ballot_authorization' => [
        'protocol' => 'dual_code',
    ],

    'voter_verification' => [
        'required' => true,
        'protocol' => 'officer_captures_evidence',
    ],
];
```

This is:

# constitutional law.

It defines:

* allowed participation semantics,
* immutable governance constraints,
* replay-verifiable election law.

That belongs in the Constitution.

---

# What Should NOT Live In Constitution

NOT this:

```php id="jlwmr4"
if ($requestIp !== $verifiedIp) {
    return deny();
}
```

or:

```php id="jlwmr5"
DB::table('votes')->count(...)
```

or:

```php id="jlwmr6"
FingerprintService::matches(...)
```

These are:

# operational policy implementations.

They belong in:

* policies,
* infrastructure adapters,
* application services,
* evidence providers.

NOT constitutional law.

---

# Correct DDD Layering

This is the proper architecture:

| Layer               | Responsibility                               |
| ------------------- | -------------------------------------------- |
| Constitution        | immutable participation law                  |
| Policies            | interpret constitutional articles            |
| Evidence Aggregates | provide replayable evidence                  |
| Resolver            | derive participation capability              |
| Controllers         | orchestration only                           |
| Infrastructure      | persistence, fingerprinting, network capture |

This separation is extremely important.

---

# Rule-by-Rule Example

---

# Rule 1 & 4 — IP Binding

## Constitution

Defines:

```text id="jlwmr7"
network binding strategy = ip_strict
```

---

## Policy

Implements:

```text id="jlwmr8"
NetworkBindingPolicy
```

---

## Infrastructure

Captures:

```text id="jlwmr9"
actual request IP
```

---

## Resolver

Determines:

```text id="jlwmra"
capability result
```

That is correct DDD layering.

---

# Rule 2 & 3 — Single/Dual Code System

## Constitution

Defines:

```text id="-vesmrb"
authorization protocol = dual_code
```

---

## BallotAuthorizationPolicy

Implements:

* open-code verification,
* submit-code verification,
* continuity rules.

---

## Infrastructure

Handles:

* OTP delivery,
* token persistence,
* session transport.

Again:
perfect separation.

---

# Rule 6 — Officer Verification

## Constitution

Defines:

```text id="jlwmrc"
verification required = true
protocol = officer_captures_evidence
```

---

## VoterVerificationPolicy

Interprets:

* attestation validity,
* verification continuity,
* evidence completeness.

---

## Infrastructure

Handles:

* video call integration,
* device capture,
* IP extraction,
* fingerprint acquisition.

The officer process itself is:

# evidence generation,

NOT sovereignty.

Very important distinction.

---

# The Most Important DDD Insight

Your Constitution is NOT:

# executable code.

It is:

# immutable domain law.

Policies become:

# interpreters of constitutional law.

Resolver becomes:

# sovereign authority derivation.

This is a very strong DDD architecture.

---

# Important Architectural Warning

Do NOT put:

* business logic,
* infrastructure details,
* procedural workflows,
* or UI behavior

inside constitutional articles.

Otherwise:
the Constitution becomes:

# a giant god-config object.

That becomes unmaintainable.

---

# Correct Mental Model

Think of the Constitution as:

# immutable declarative governance constraints.

Then:

* policies interpret,
* overlays influence,
* resolver derives capability,
* snapshots preserve replay lineage.

That separation is exactly what your architecture was evolving toward.

---

# Final DDD Recommendation

YES:
the rules SHOULD live in the Constitution —
BUT ONLY as:

# immutable declarative constitutional articles.

NOT:

* procedural implementations,
* controller logic,
* infrastructure mechanics,
* or runtime algorithms.

That separation is the key architectural principle.
Yes — based on the implementation summaries and the completed audit suites, Phase D is effectively complete. 

The strongest evidence is this:

```text id="yl7w8k"
86 tests passed (215 assertions)
All phases D.1 through D.6 test suites passing
```

And architecturally, the critical invariants are already established:

| Phase D Capability           | Status |
| ---------------------------- | ------ |
| Immutable constitutional law | ✅      |
| Replay determinism           | ✅      |
| Resolver sovereignty         | ✅      |
| Overlay non-sovereignty      | ✅      |
| Temporal determinism         | ✅      |
| Integer-only reproducibility | ✅      |
| Sovereignty convergence      | ✅      |
| Semantic enforcement         | ✅      |
| Explicit execution topology  | ✅      |

This means:

# the constitutional runtime foundation is now mature enough for operational election law integration.

---

# So What Happens Now?

Now you should begin:

# Phase E — Operational Constitutionalization.

Exactly as the document describes.

This is where the actual election rules move from:

* procedural controller logic,
* middleware checks,
* service-layer shortcuts,

into:

# constitutional runtime policies.

---

# Important Clarification

You are NOT “inventing the architecture” anymore.

That part is done.

Now you are:

# operationalizing the constitutional runtime.

Huge difference.

---

# What Should Be Implemented NOW

These are the immediate next bounded-context implementations:

| Rule                            | Target Policy               |
| ------------------------------- | --------------------------- |
| Rule 1 & 4 — IP binding         | `NetworkBindingPolicy`      |
| Rule 7 — max votes/IP           | `NetworkBindingPolicy`      |
| Rule 5 — fingerprint continuity | `DeviceBindingPolicy`       |
| Rule 2 & 3 — single/dual code   | `BallotAuthorizationPolicy` |
| Rule 6 — officer verification   | `VoterVerificationPolicy`   |

Exactly as your Phase E blueprint states.

---

# DDD Expert Guidance — The Most Important Rule

The Constitution must contain:

# immutable declarative law

NOT:

* controller logic,
* procedural workflows,
* database queries,
* infrastructure mechanics.

Correct:

```php id="wjlwm1"
'network_binding' => [
    'strategy' => 'ip_strict',
    'max_votes_per_ip' => 6,
]
```

Incorrect:

```php id="4jlwm2"
if ($requestIp !== $verifiedIp) {
    return deny();
}
```

Policies interpret constitutional articles.
Resolver derives authority.
Infrastructure captures evidence.

That separation is now the key architectural principle.

---

# Recommended Phase E.1 Sequence (TDD First)

As a DDD expert, I would implement in this exact order:

---

## E.1.1 — NetworkBindingPolicy

Implements:

* Rule 1,
* Rule 4,
* Rule 7.

Why first?
Because:

* highest sovereignty leakage risk,
* already partially modeled,
* easiest replay verification,
* strongest parity tests.

TDD tests first:

```text id="jlwm3"
test_ip_binding_requires_registered_ip()
test_max_votes_per_ip_enforced()
test_trust_elevated_thresholds_are_deterministic()
test_whitelist_strategy_allows_matching_cidr()
```

---

## E.1.2 — DeviceBindingPolicy

Implements:

* Rule 5.

TDD:

```text id="jlwm4"
test_exact_fingerprint_match_satisfies_attestation()
test_device_change_requires_reattestation()
test_not_required_strategy_preserves_continuity()
```

---

## E.1.3 — BallotAuthorizationPolicy

Implements:

* Rule 2,
* Rule 3.

IMPORTANT:
This is a separate bounded context from ConstitutionalTrust. 

Very important DDD separation.

TDD:

```text id="jlwm5"
test_single_code_protocol_uses_same_token()
test_dual_code_protocol_requires_view_and_commit()
test_commit_token_replay_rejected()
```

---

## E.1.4 — VoterVerificationPolicy

Implements:

* Rule 6.

Officer becomes:

# evidence attestation authority

NOT:

# voting authority.

Very important DDD distinction.

TDD:

```text id="jlwm6"
test_officer_attestation_captures_network_evidence()
test_revoked_attestation_denies_continuity()
test_attestation_expiration_requires_reverification()
```

---

# MOST IMPORTANT IMPLEMENTATION RULE

Do NOT implement any new controller authority.

Controllers must remain:

# orchestration-only.

Meaning:

* collect evidence,
* invoke resolver,
* persist snapshots,
* render projections.

Nothing more.

---

# Also Important

Some old inflated architecture still exists:

* `OverlayDefinition`
* `OverlayStratification`
* old `ConstitutionalFinding`
* old `OverlayInfluenceContext`

These are now:

# architectural fossils.

Do NOT build new features on top of them.

Phase E should use:

# simplified Phase D objects only.

---

# Final DDD Assessment

You are now transitioning from:

```text id="jlwm7"
Strategic Constitutional Architecture
```

into:

```text id="jlwm8"
Operational Constitutional Runtime
```

That is the correct next phase.

And yes:

# you should begin implementing it now.
