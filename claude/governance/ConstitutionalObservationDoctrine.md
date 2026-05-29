# Constitutional Observation Doctrine

**Status:** Constitutional Law (Frozen)  
**Phase:** D.R.2 Semantic Governance  
**Authority:** Senior Architectural Review  
**Date Established:** 2026-05-27

---

## Preamble

This doctrine defines what observations ARE and what they fundamentally are NOT within the constitutional runtime.

Observations are the **foundation of evidence preservation** before interpretation. They are non-sovereign, non-authoritative, and must remain semantically flat.

The seven articles that follow establish inviolable semantic law governing the observation topology.

---

## Article 1 — Observations Are Non-Sovereign

**Principle:**

Observations describe constitutional evidence **only**. They derive no authority, grant no capability, and imply no sovereign consequence.

**Enforcement:**

- Observations MAY contain evidence descriptions
- Observations MAY describe findings, concerns, evidence facts
- Observations MUST NOT contain:
  - Authority recommendations
  - Procedural guidance
  - Capability grants or denials
  - Participation instructions
  - Routing semantics

**Rationale:**

Sovereignty is reserved exclusively to the resolver. Any observation that encodes authority semantics creates distributed sovereignty and hidden governance leakage.

**Example (Correct):**

```php
// CORRECT: Observational
$observation = new OverlayObservation(
    overlayId: 'device_anomaly',
    finding: 'Device fingerprint changed from session start',
    evidenceContext: ['previous_fp' => 'abc123', 'current_fp' => 'xyz789'],
    severity: EvidenceSeverity::MODERATE,
);
```

**Example (Violation):**

```php
// WRONG: Encodes authority
$observation = new OverlayObservation(
    overlayId: 'device_anomaly',
    recommendedAction: 'REQUIRE_REVERIFICATION',  // ← VIOLATION
    escalationLevel: 'HIGH',                       // ← VIOLATION
    suggestedDecision: 'DENY_PARTICIPATION',       // ← VIOLATION
);
```

---

## Article 2 — Aggregation Preserves Evidence

**Principle:**

When observations are collected, aggregation MUST preserve all evidence without reconciliation, prioritization, or authority interpretation.

**Enforcement:**

- Observations collected in `ConstitutionalObservationContext` MUST remain distinct
- No observation may be eliminated, collapsed, or reconciled
- No precedence ordering may be applied
- Aggregation MAY organize observations but MUST NOT rank them

**Rationale:**

Evidence preservation is constitutional principle. Collapsing contradictory evidence prematurely transfers interpretation authority from resolver to aggregation layer.

**Forbidden Patterns:**

```php
// WRONG: Ranking
$context->highestConcern();           // ← VIOLATION
$context->strongestSignal();          // ← VIOLATION
$context->maxSeverity();              // ← VIOLATION
$context->primaryObservation();       // ← VIOLATION
```

```php
// WRONG: Reconciliation
$context->resolveConflict();          // ← VIOLATION
$context->mergeObservations();        // ← VIOLATION
$context->collapseSignals();          // ← VIOLATION
```

**Correct Pattern:**

```php
// CORRECT: Flat collection
readonly class ConstitutionalObservationContext {
    public readonly array $observations;  // No ranking, no precedence
    
    public function count(): int {
        return count($this->observations);
    }
    
    public function all(): array {
        return $this->observations;
    }
    
    // NO ranking methods
    // NO precedence methods
    // NO interpretation methods
}
```

---

## Article 3 — Observations May Conflict

**Principle:**

Contradictory observations are **preserved as evidence**, not eliminated as errors. Constitutional governance preserves uncertainty until the resolver interprets sovereign consequence.

**Enforcement:**

- Conflicting observations MUST coexist in context
- No observation may override another
- No mandatory resolution logic
- Resolver receives full evidence including contradictions

**Rationale:**

Real governance systems contain conflicting evidence, ambiguity, and unresolved disputes. The runtime must preserve this richness of constitutional evidence rather than prematurely collapsing it into binary decisions.

**Example (Correct Constitutional Modeling):**

```php
// CORRECT: Preserving conflict
$context = new ConstitutionalObservationContext(
    observations: [
        new OverlayObservation(
            overlayId: 'ip_velocity',
            finding: 'IP voting frequency within normal parameters',
            severity: EvidenceSeverity::LOW,
        ),
        new OverlayObservation(
            overlayId: 'device_anomaly',
            finding: 'Device fingerprint changed unexpectedly',
            severity: EvidenceSeverity::MODERATE,
        ),
        // Both observations preserved despite apparent conflict
        // Resolver receives full evidence picture
    ]
);
```

**Invalid Pattern (Violation):**

```php
// WRONG: Forced resolution
if ($ipVelocityOk && $deviceChanged) {
    return ALLOW;  // ← VIOLATION: premature reconciliation
}
```

---

## Article 4 — Interpretation Is Resolver-Exclusive

**Principle:**

Only the resolver derives sovereign interpretation from observations. No other component may:
- Convert observations to authority decisions
- Interpret evidence as capability grants
- Route based on observation content
- Short-circuit on observation states

**Enforcement:**

Forbidden outside resolver:

```text
allow()
deny()
authorize()
grant()
canVote()
eligible()
participate()
hasCapability()
```

Forbidden APIs that imply interpretation:

```text
interpretObservations()
deriveAuthority()
resolveEvidence()
decideParticipation()
evaluateAuthority()
```

**Rationale:**

Distributed interpretation creates distributed sovereignty. The resolver is the sole constitutional authority derivation engine.

---

## Article 5 — Recommendations Are Annotations

**Principle:**

Governance considerations (formerly "recommendations") are **annotations** on observations, not directives for behavior.

**Enforcement:**

Governance annotations MAY include:
- `constitutionalBasis` — why this observation matters
- `governanceNote` — suggested constitutional review areas
- `escalationContext` — facts for escalation review (NOT directive)
- `registrarContext` — evidence for registrar consideration

Governance annotations MUST NOT include:
- `recommendedAction` — procedural guidance
- `suggestedPath` — routing directives
- `requiredEscalation` — authority commands
- `elevationRequest` — capability implications

---

## Article 6 — Observation Ordering Has No Sovereign Meaning

**Principle:**

The ordering, sequence, or position of observations within the context carries **no authority weight** and implies no precedence.

**Enforcement:**

- Insertion order MUST NOT create implicit priority
- Collection order MUST NOT affect resolver interpretation
- No observation is inherently "primary" or "strongest" by position
- Resolver MUST examine all observations regardless of order
- Aggregation order is arbitrary and may change without semantic consequence

**Rationale:**

Implicit ordering is a hidden sovereignty vector. By making ordering explicit as non-authoritative, we prevent accidental precedence topology from emerging through data structure choices.

**Correct Pattern:**

```php
// CORRECT: Order independence
$observations = [
    $deviceAnomaly,
    $ipVelocity,
    $registrarAttestation,
];

// Resolver processes ALL observations
// Order is meaningless
// Results must be identical regardless of order
```

**Violation Pattern:**

```php
// WRONG: Implicit ordering
$firstObservation = $context->observations[0];  // ← VIOLATION: implies priority
$sortedByContext = collect($context->observations)
    ->sortBy('severity')                        // ← VIOLATION: creates precedence
    ->first();                                   // ← VIOLATION: takes strongest
```

---

## Article 7 — Observational Neutrality Is Inviolable

**Principle:**

The observation layer must remain **semantically neutral** — it must not encode, imply, or hint at authority interpretation even through secondary mechanisms like metadata, severity weighting, urgency flags, or handling hints.

**Enforcement:**

Forbidden secondary sovereignty vectors:

```php
// WRONG: Metadata weighting
$observation->weight           // ← VIOLATION
$observation->importance       // ← VIOLATION
$observation->urgency          // ← VIOLATION
$observation->priority         // ← VIOLATION

// WRONG: Handling hints
$observation->recommendedHandling()      // ← VIOLATION
$observation->escalationTrigger()        // ← VIOLATION
$observation->handlingPath()             // ← VIOLATION

// WRONG: Authority annotations
$observation->authorityImplication()     // ← VIOLATION
$observation->capabilityContext()        // ← VIOLATION
$observation->participationHint()        // ← VIOLATION
```

**Allowed Properties:**

```php
// CORRECT: Pure observation
$observation->overlayIdentifier       // What observed
$observation->finding                 // What was found
$observation->evidenceContext         // Supporting facts (hashed)
$observation->constitutionalBasis     // Why observation matters (no action implied)
$observation->recordedAt              // When observed
```

**Rationale:**

Even "neutral" metadata can accidentally recreate hidden authority through:
- Implicit weighting in aggregation
- "Recommended" handling hints
- "Urgent" escalation flags
- "Important" metadata that silently affects interpretation

By explicitly forbidding these mechanisms, we ensure observations remain fundamentally neutral even under evolution and future extensions.

---

## Enforcement Through Constitutional Contracts

These seven articles are enforced through:

1. **Type System** — Forbidden methods absent from observation classes
2. **AST Gates** — CI prevents forbidden vocabulary in canonical zone
3. **Semantic Regression Tests** — verify no hidden authority emerges
4. **Code Review Doctrine** — explicit architectural review of observation topology

---

## Amendment Process

This doctrine is **constitutionally frozen** for Phase D.R.2 through D.6 (resolution cutover).

Amendments require:
1. Senior architectural review
2. Proof that current articles prevent legitimate observation modeling
3. Demonstration that amendment does not enable distributed sovereignty
4. Formal documentation in amendment appendix

**No amendments during D.R.2 → D.6 stabilization phase.**

---

## Constitutional Purity Audit Checklist

Before deployment, verify:

- [ ] No observation has authority vocabulary
- [ ] No aggregation performs ranking or precedence
- [ ] No observation ordering implies priority
- [ ] All observations preserved in context (none collapsed)
- [ ] Resolver receives complete evidence picture
- [ ] No secondary sovereignty vectors (weight, urgency, handling hints)
- [ ] Observational neutrality maintained across all extensions
- [ ] No interpretation logic outside resolver

---

**This doctrine is semantic constitutional law. It governs observation topology. It is executable through type systems and AST enforcement.**

**Violations weaken the constitutional runtime and enable distributed sovereignty.**

