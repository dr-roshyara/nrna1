# Constitutional Policy Purity Rules (D.R.3)

**Source:** Phase D.R.3 — Policy Dependency Isolation & Purity Enforcement

**Purpose:** Prevent policies from becoming distributed mini-authority engines that recreate sovereignty leakage.

---

## Core Doctrine

Policies are **constitutional fact reporters**, not authority engines.

```
EVALUATOR reports facts → RESOLVER interprets facts → CONTROLLER reads authority
```

Policies NEVER participate in authority derivation.

---

## What Policies May Do

| Action | Status | Why |
|--------|--------|-----|
| Evaluate immutable facts | ✅ Required | Facts are constitutional evidence |
| Emit constitutional findings | ✅ Required | Findings inform resolver decisions |
| Describe concern levels | ✅ Required | Descriptive, not prescriptive |
| Report evidence quality | ✅ Required | Resolver needs reliability assessment |
| Consume overlay context | ✅ Allowed | But ONLY as descriptive signals |
| Maintain determinism | ✅ Required | Replay legitimacy depends on it |

---

## What Policies Must NOT Do

| Action | Status | Why |
|--------|--------|-----|
| Derive participation authority | ❌ FORBIDDEN | Only resolver has this power |
| Deny participation | ❌ FORBIDDEN | Authority language is resolver domain |
| Call other policies | ❌ FORBIDDEN | Creates hidden coupling |
| Inspect policy outcomes | ❌ FORBIDDEN | Hidden dependencies on order |
| Access resolver decisions | ❌ FORBIDDEN | Would bypass resolver supremacy |
| Access capability snapshots | ❌ FORBIDDEN | Distributed sovereignty |
| Interpret resolver state | ❌ FORBIDDEN | Authority is resolver privilege |
| Mutate evaluation context | ❌ FORBIDDEN | Facts are immutable |
| Bypass PolicySequence | ❌ FORBIDDEN | Dependencies must go through orchestrator |
| Output authority semantics | ❌ FORBIDDEN | Findings are descriptive only |

---

## Dependency Declaration (D.R.3 Requirement)

Every policy MUST declare its constitutional dependencies:

```php
interface ConstitutionalPolicy
{
    public function constitutionalDomain(): string;
    // 'verification'|'network'|'device'|'continuity'

    public function constitutionalDependencies(): array;
    // Which domains (by name) must be evaluated before this policy
    // Example: NetworkBindingPolicy depends on 'verification'

    public function constitutionalPriority(): int;
    // Within same domain, ordering (not authority weight)

    public function evaluate(TrustCapabilityContext $ctx): ConstitutionalFinding;
    // Returns finding, NEVER VotingTrustResult, NEVER authority decision
}
```

---

## Proof of Purity Tests (D.R.3 Requirement)

Every policy must pass:

```php
public function test_policy_cannot_derive_participation_authority(): void
{
    // Assert: No output contains "deny", "allow", "grant", "authorize", "permit"
    // Assert: No boolean trust/untrust decision in result
}

public function test_policy_cannot_access_resolver_state(): void
{
    // Assert: Policy receives ONLY TrustCapabilityContext
    // Assert: No ElectionCapabilityResolver dependency injection
    // Assert: No capability snapshot parameter
}

public function test_policy_cannot_access_capability_snapshot(): void
{
    // Assert: Constructor has no CapabilityResolver, CapabilitySnapshot, or resolver-adjacent dependencies
}

public function test_policy_output_contains_no_authority_semantics(): void
{
    // Assert: Result is ConstitutionalFinding (descriptive)
    // Assert: No CapabilityDenialReason enum values
    // Assert: No "trusted"/"untrusted" fields
}

public function test_policy_evaluation_order_does_not_create_authority(): void
{
    // Assert: Running policies in different order produces same evaluation state
    // (Yes, semantics may differ, but authority derivation logic is identical)
}

public function test_policy_evaluation_is_replay_deterministic(): void
{
    // Assert: Same context always produces same finding
    // No randomness, no external I/O, no timestamp dependency
}
```

---

## Architectural Invariant: The Three Layers

### Layer 1: Evaluation (Policies)

- **Input:** Immutable facts
- **Output:** Constitutional findings (descriptive)
- **Authority role:** NONE
- **Coupling:** No inter-policy calls
- **Ordering:** Dependency-declared, not arbitrary

### Layer 2: Interpretation (Resolver)

- **Input:** Constitutional findings + overlay descriptive signals
- **Output:** Authority decision (can this user vote?)
- **Authority role:** EXCLUSIVE
- **Coupling:** Consumes findings, never consumes resolver decisions
- **Ordering:** Irrelevant (resolver derives from immutable inputs)

### Layer 3: Consumption (Controllers/UI)

- **Input:** Authority decision (CapabilitySnapshot)
- **Output:** User-facing response
- **Authority role:** NONE (reads authority, never derives it)
- **Coupling:** None
- **Ordering:** Irrelevant

---

## Explicit Forbidden Patterns

### ❌ Pattern 1: Policy Calling Policy

```php
// FORBIDDEN
final class NetworkPolicy {
    public function evaluate(TrustCapabilityContext $ctx): ConstitutionalFinding
    {
        $verificationFinding = $this->verificationPolicy->evaluate($ctx);
        // Hidden coupling, distributed sovereignty
    }
}
```

**Why forbidden:** Creates hidden ordering dependency, makes policies co-author authority.

---

### ❌ Pattern 2: Policy Inspecting Previous Result

```php
// FORBIDDEN
$verificationResult = $previousPolicy->evaluate($ctx);
$myResult = match($verificationResult->trustLevel) {
    TrustLevel::Attested => deny(),
    ...
};
```

**Why forbidden:** Authority derivation is distributed, not localized to resolver.

---

### ❌ Pattern 3: Policy Returning Authority Semantics

```php
// FORBIDDEN
return VotingTrustResult::deny('some_reason');
```

**Why forbidden:** Policy is making authority decision, resolver is bypassed.

---

### ❌ Pattern 4: Policy Accessing Resolver

```php
// FORBIDDEN
public function __construct(private ElectionCapabilityResolver $resolver) { ... }
```

**Why forbidden:** Resolver is authority engine, policies are fact reporters — never mix.

---

### ✅ Pattern 1 (Correct): Policies Return Findings

```php
// ALLOWED
final class VerificationPolicy implements ConstitutionalPolicy
{
    public function evaluate(TrustCapabilityContext $ctx): ConstitutionalFinding
    {
        if ($ctx->attestation->revoked) {
            return new ConstitutionalFinding(
                ConstitutionalConcernLevel::HIGH,
                EvidenceWeightCategory::DEFINITIVE,
                'attestation_revoked',
                ['revoked' => true],
                $this->identifier(),
            );
        }
        // ...
    }
}

// Resolver interprets the finding to decide authority
```

---

### ✅ Pattern 2 (Correct): PolicySequence Orchestrates Dependencies

```php
// ALLOWED — PolicySequence is the ONLY place that understands ordering
final class PolicySequence
{
    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        // Step 1: Verification
        $verificationFinding = $this->verificationPolicy->evaluate($ctx);

        // Step 2: Network (operates within facts, not previous results)
        $networkFinding = $this->networkPolicy->evaluate($ctx);

        // Step 3: Resolver interprets ALL findings together
        return $this->resolver->interpretFindings(
            $verificationFinding,
            $networkFinding,
            // ...
        );
    }
}
```

---

## Constitutional Vocabulary for Policies

| Permitted | Forbidden |
|-----------|-----------|
| "concern detected" | "deny voting" |
| "evidence quality: weak" | "trust insufficient" |
| "attestation missing" | "verification failed" |
| "finding: network limit exceeded" | "voting not permitted" |
| "continuity broken" | "access denied" |
| "uncertainty remains" | "user is untrusted" |
| "requires review" | "authorization required" |

---

## Policy Dependency Graph (Fail-Fast Validation)

PolicySequence must validate dependency graph at construction:

```php
final class PolicySequence
{
    public function __construct(
        private VerificationAttestationPolicy $verificationPolicy,
        private NetworkBindingPolicy $networkPolicy,
        private DeviceBindingPolicy $devicePolicy,
    ) {
        $this->validateDependencyGraph([
            'verification' => [],
            'network' => ['verification'],
            'device' => ['verification'],
        ]);
    }

    private function validateDependencyGraph(array $dependencies): void
    {
        // Assert: no cycles
        // Assert: no missing domains
        // Assert: all declared dependencies are satisfied
        // Throw ConstitutionalSequenceViolation if validation fails
    }
}
```

---

## D.R.3 Completion Criteria

- [ ] All policies implement `ConstitutionalPolicy` interface
- [ ] All policies declare `constitutionalDependencies()` explicitly
- [ ] All policies return `ConstitutionalFinding`, NOT `VotingTrustResult`
- [ ] PolicySequence validates dependency graph (fail-fast)
- [ ] All 6 purity tests pass per policy
- [ ] No policy receives other policy outputs as parameters
- [ ] No policy calls another policy
- [ ] No policy accesses resolver
- [ ] No policy uses authority vocabulary
- [ ] Replay determinism proven by test

---

## Migration Path (From D.R.1–D.R.2 to D.R.3)

**Phase 1: Interface Declaration**
- Add `ConstitutionalPolicy` interface
- Declare dependencies

**Phase 2: Result Type Change**
- Policies still accept `VotingTrustResult` parameter (from D.2)
- Policies still return `VotingTrustResult` (for compatibility)
- But internally, policies emit `ConstitutionalFinding` semantics

**Phase 3: PolicySequence Refactoring**
- Stop passing policy results as parameters
- Stop reading previous policy results
- Let policies work with immutable `TrustCapabilityContext` only
- Orchestrator (PolicySequence) builds up findings
- Resolver interprets findings

**Phase 4: Resolver Integration**
- Resolver learns to interpret `ConstitutionalFinding` array
- Resolver derives authority from findings + overlay signals
- **This completes D.R.3 and seals distributed sovereignty**

---

## References

- **D.R.2:** Overlay signal redesign (sovereignty sealed at overlay layer)
- **D.R.4:** Snapshot purity enforcement (sovereignty sealed at snapshot layer)
- **D.R.5:** Constitutional article decomposition (structural improvement)
- **D.5.5:** Constitutional convergence audit (runtime verification)
