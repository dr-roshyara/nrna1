# Canonical Vocabulary Rules

**Status:** Executable Enforcement Law  
**Phase:** D.R.2 Semantic Governance  
**Authority:** Senior Architectural Review  
**Purpose:** Define vocabulary restrictions and AST enforcement targets for CI gates

---

## Enforcement Philosophy

These rules are **NOT style guidelines**. They are **constitutional semantic law** enforced through:

1. **Type system** (method signatures, return types)
2. **AST gates** (CI linters scanning source code)
3. **Import restrictions** (forbidden `use` statements)
4. **Semantic regression tests** (ConstitutionalSemanticRegressionTests)
5. **Code review doctrine** (mandatory architectural review)

Violations are architectural failures, not code style issues.

---

## CATEGORY 1: Forbidden Authority Vocabulary

**Context:** These words encode sovereign interpretation and MUST NOT appear in canonical zone.

### Forbidden in Canonical Zone

```text
trust, trusted, trustLevel, trustCapability, trustContext
authorize, authorization, authority
grant, granted, grantCapability
eligible, eligibility, canVote, canParticipate
hasCapability, hasPermission, canAccess, canExecute
participate, participation, participationEligibility
```

### Enforcement Rules

**Rule C1.1:** No variable named `$trust` in canonical files
```bash
grep -r '\$trust[A-Z_]' app/Domain/Election/Security/Simplified/
grep -r '\$trust[A-Z_]' app/Application/Election/Security/Simplified/
```

**Rule C1.2:** No class named `Trust*` in canonical zone
```bash
find app/Domain/Election/Security/Simplified/ -name 'Trust*.php'
find app/Application/Election/Security/Simplified/ -name 'Trust*.php'
```

**Rule C1.3:** No method returning `bool` for authority-like questions
```php
// FORBIDDEN in canonical:
public function canVote(): bool { }     // ← VIOLATION
public function isEligible(): bool { }  // ← VIOLATION
public function hasCapability(): bool { } // ← VIOLATION

// CORRECT:
public function count(): int { }        // ✓ Safe
public function all(): array { }        // ✓ Safe
```

**Rule C1.4:** Authority decisions only in resolver
```php
// FORBIDDEN outside EvidenceCapabilityPolicy:
return CapabilityDecision::allow();    // ← VIOLATION
return CapabilityDecision::deny();     // ← VIOLATION

// ONLY in EvidenceCapabilityPolicy:
// ✓ Allowed (resolver-exclusive)
```

---

## CATEGORY 2: Forbidden Influence Vocabulary

**Context:** These words encode procedural guidance and hidden authority.

### Forbidden in Canonical Zone

```text
influence, influenced, influenceContext
elevate, elevation, elevationRequest, elevateTrust
escalate, escalation, escalationPath
recommend, recommendation, recommendedPath, recommendedAction
suggest, suggestion, suggestedAction, suggestedElevation
path (in decision context), route, routing, nextAction
```

### Enforcement Rules

**Rule C2.1:** No `OverlayInfluenceContext` in canonical imports
```bash
grep -r "OverlayInfluenceContext" \
  app/Domain/Election/Security/Simplified/ \
  app/Application/Election/Security/Simplified/
```

**Rule C2.2:** No `RecommendedProceduralPath` in canonical
```bash
grep -r "RecommendedProceduralPath" \
  app/Domain/Election/Security/Simplified/ \
  app/Application/Election/Security/Simplified/
```

**Rule C2.3:** No methods implying influence direction
```php
// FORBIDDEN:
public function getStrongestSignal() { }     // ← VIOLATION
public function getRecommendedPath() { }     // ← VIOLATION
public function getElevationRequest() { }    // ← VIOLATION
public function escalateTo() { }             // ← VIOLATION

// CORRECT:
public function count() { }                  // ✓ Safe
public function all() { }                    // ✓ Safe
```

**Rule C2.4:** No elevation or escalation semantics
```php
// FORBIDDEN:
$context->elevateTrust();               // ← VIOLATION
$context->requestElevation();           // ← VIOLATION
$context->escalateToGovernance();       // ← VIOLATION

// CORRECT:
$context->attestationObserved();        // ✓ Safe
$context->concernPresent();             // ✓ Safe
```

---

## CATEGORY 3: Forbidden Procedural Semantics

**Context:** These patterns encode workflow routing and decision sequencing.

### Forbidden Patterns

```text
continue, stop, shortCircuit, block (in flow context)
proceed, nextStep, currentPhase, workflow
flow, routing, path (procedural), direction
priority, precedence, strongest, primary, highest
merge, collapse, reconcile, resolve (observation context)
```

### Enforcement Rules

**Rule C3.1:** No short-circuit logic in observation aggregation
```php
// FORBIDDEN:
foreach ($observations as $obs) {
    if ($obs->severity === HIGH) {
        return $obs;  // ← VIOLATION: short-circuits
    }
}

// CORRECT:
return $observations;  // All preserved, no short-circuit
```

**Rule C3.2:** No ordering-based interpretation
```php
// FORBIDDEN:
$first = $context->observations[0];      // ← VIOLATION: implies priority
$sorted = $context->sortBySeverity();    // ← VIOLATION: creates precedence
$strongest = $context->max('severity');  // ← VIOLATION: ranking

// CORRECT:
$all = $context->all();                  // Order-independent
```

**Rule C3.3:** No reconciliation of conflicting observations
```php
// FORBIDDEN:
if ($obs1->finding && !$obs2->finding) {
    return $obs1;  // ← VIOLATION: reconciliation
}

// CORRECT:
return [$obs1, $obs2];  // Both preserved
```

**Rule C3.4:** No routing or flow control based on observation content
```php
// FORBIDDEN:
if ($observation->severity > 5) {
    return $this->escalateRoute();  // ← VIOLATION: routing
}

// CORRECT:
// No routing — resolver handles all interpretation
```

---

## CATEGORY 4: Forbidden Secondary Authority Vectors

**Context:** Hidden sovereignty can leak through metadata, hints, or annotations.

### Forbidden Secondary Properties

```php
// FORBIDDEN in observations:
$observation->weight            // ← VIOLATION: implicit ranking
$observation->importance        // ← VIOLATION: priority hint
$observation->urgency           // ← VIOLATION: escalation hint
$observation->priority          // ← VIOLATION: precedence marker
$observation->handlingHint      // ← VIOLATION: authority suggestion
$observation->recommendedAction // ← VIOLATION: procedural directive
$observation->escalationTrigger // ← VIOLATION: routing trigger
```

### Enforcement Rules

**Rule C4.1:** Observation classes have only neutral properties
```php
// CORRECT observational properties:
public readonly string $overlayIdentifier;
public readonly string $finding;
public readonly array $evidenceContext;
public readonly string $constitutionalBasis;
public readonly EvidenceSeverity $severity;
public readonly \DateTimeImmutable $recordedAt;

// FORBIDDEN properties: (none of the above)
```

**Rule C4.2:** No "recommended" or "suggested" metadata
```bash
grep -r "recommended\|suggested\|handling\|escalation" \
  app/Domain/Election/Security/Simplified/OverlayObservation.php
```

**Rule C4.3:** Severity is DESCRIPTIVE only, never interpreted as authority
```php
// CORRECT: Pure descriptive property
$observation->severity = EvidenceSeverity::HIGH;
// Means: "This observation is about a significant change"
// Does NOT mean: "This should escalate or influence decisions"

// FORBIDDEN interpretation:
if ($observation->severity === HIGH) {
    // escalate or prioritize  ← VIOLATION
}
```

---

## CATEGORY 5: Legacy Trust-Era Vocabulary

**Context:** These classes encode the OLD procedural model and must not leak into canonical.

### Absolutely Forbidden in Canonical

```text
TrustLevel (use EvidenceClassification instead)
TrustEvaluationState (use EvidenceEvaluationState instead)
VotingTrustResult (use EvidenceEvaluationResult instead)
TrustEvaluationEnvelope (use EvaluationEnvelope instead)
ConstitutionalTrustSnapshot (use EvidenceSnapshot instead)
OverlayInfluenceContext (use ConstitutionalObservationContext instead)
RecommendedProceduralPath (use GovernanceConsideration instead)
TrustCapabilityPolicy (NEW: EvidenceCapabilityPolicy exists)
TrustCapabilityContext (use EvidenceContext instead)
```

### Enforcement Rules

**Rule C5.1:** No legacy class imports in canonical zone
```bash
grep -r "use App\Domain\Election\Security\TrustLevel" \
  app/Domain/Election/Security/Simplified/
grep -r "use App\Domain\Election\Security\TrustEvaluationState" \
  app/Domain/Election/Security/Simplified/
```

**Rule C5.2:** No legacy enum references
```bash
grep -r "TrustLevel::" \
  app/Domain/Election/Security/Simplified/
grep -r "RecommendedProceduralPath::" \
  app/Domain/Election/Security/Simplified/
```

**Rule C5.3:** Renamed classes must be used in canonical
```php
// FORBIDDEN:
use App\Domain\Election\Security\TrustLevel;  // ← Import violation

// REQUIRED:
use App\Domain\Election\Security\Simplified\EvidenceClassification;
```

---

## CATEGORY 6: Import Boundary Rules

**Context:** One-way dependencies prevent semantic contamination.

### Forbidden Imports

**Rule C6.1:** Canonical code MUST NOT import from compatibility zone
```bash
# In canonical files, these imports are FORBIDDEN:
# grep -r "use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy" \
#   app/Domain/Election/Security/Simplified/
```

**Rule C6.2:** Canonical code MUST NOT import legacy security classes
```bash
grep -r "use App\Domain\Election\Security\[^S]" \
  app/Domain/Election/Security/Simplified/
```

**Rule C6.3:** Compatibility zone MAY import canonical (for parity testing)
```php
// ALLOWED in compatibility zone:
use App\Domain\Election\Security\Simplified\EvaluationEnvelope;

// Purpose: Compare OLD vs NEW for parity verification
```

---

## CATEGORY 7: Semantic Regression Test Anchors

**Context:** ConstitutionalSemanticRegressionTests verify that hidden authority doesn't reappear over time.

### Test Coverage Requirements

Each test class must verify:

```php
class ConstitutionalSemanticRegressionTests extends TestCase
{
    // FORBIDDEN VOCABULARY MUST NOT APPEAR
    public function test_no_trust_vocabulary_in_canonical() { }
    public function test_no_influence_vocabulary_in_canonical() { }
    public function test_no_procedural_vocabulary_in_canonical() { }
    public function test_no_secondary_authority_vectors() { }
    
    // OBSERVATIONAL TOPOLOGY MUST REMAIN FLAT
    public function test_observations_remain_unranked() { }
    public function test_no_precedence_ordering_emerges() { }
    public function test_conflicting_observations_preserved() { }
    public function test_order_independence_maintained() { }
    
    // RESOLVER EXCLUSIVITY MUST HOLD
    public function test_no_authority_outside_resolver() { }
    public function test_no_CapabilityDecision_in_canonical() { }
    public function test_no_allow_deny_in_observations() { }
    
    // NEUTRAL METADATA MUST REMAIN NEUTRAL
    public function test_severity_is_descriptive_not_authoritative() { }
    public function test_basis_is_annotation_not_directive() { }
    public function test_no_handling_hints_in_observations() { }
}
```

---

## CI Gate Implementation

### Automated Check Script

```bash
#!/bin/bash
# canonical-vocabulary-enforcement.sh

set -e

CANONICAL_PATHS=(
    "app/Domain/Election/Security/Simplified/"
    "app/Application/Election/Security/Simplified/"
    "app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php"
)

FORBIDDEN_WORDS=(
    "trust" "authorize" "grant" "eligible"
    "influence" "elevate" "escalate" "recommend"
    "path" "routing" "precedence" "priority"
)

FORBIDDEN_IMPORTS=(
    "TrustLevel" "TrustEvaluationState" "VotingTrustResult"
    "OverlayInfluenceContext" "RecommendedProceduralPath"
)

echo "Checking canonical vocabulary enforcement..."

for path in "${CANONICAL_PATHS[@]}"; do
    echo "Scanning: $path"
    
    for word in "${FORBIDDEN_WORDS[@]}"; do
        if grep -r "\b${word}\b" "$path" 2>/dev/null | \
           grep -v "test" | \
           grep -v "// ALLOWED" >/dev/null; then
            echo "❌ VIOLATION: Forbidden word '$word' in canonical zone"
            exit 1
        fi
    done
    
    for import in "${FORBIDDEN_IMPORTS[@]}"; do
        if grep -r "use.*$import" "$path" 2>/dev/null >/dev/null; then
            echo "❌ VIOLATION: Legacy import '$import' in canonical zone"
            exit 1
        fi
    done
done

echo "✅ All vocabulary rules enforced"
```

---

## Violation Response Protocol

**IF a violation is detected:**

1. **Block PR merge** (CI gate fails)
2. **Identify violation category** (C1–C7)
3. **Assess intent:**
   - Accidental leakage? → Fix and re-run
   - Intentional architecture change? → Requires senior review + doctrine amendment
4. **Document in changelog**

**Philosophy:** Violations are not errors to suppress; they are architectural signals that require explicit decision-making.

---

## Amendment Process

To add new rules or exceptions:

1. **Document rationale** with architectural justification
2. **Verify exemption doesn't enable distributed sovereignty**
3. **Add to this document** with clear "Exception" header
4. **Update CI gate script** if automation needed
5. **Require senior architectural approval**

---

**These vocabulary rules are constitutional semantic law. They prevent distributed sovereignty through strict vocabulary discipline. Violations weaken the runtime's sovereign isolation.**

