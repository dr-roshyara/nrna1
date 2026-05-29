# Tactical Semantic Alignment

**Phase:** DD.3b
**Status:** Active
**Supersedes:** "Tactical Vocabulary Normalization" (narrower scope)
**Prerequisite:** DD.2.5 Event Taxonomy (complete), DD.3a Structural Enforcement (complete)

---

## Purpose

For every major type in the constitutional governance contexts, answer:

> **What authority does this type own?**
> **What authority must this type never own?**

This is more valuable than renaming alone. A class with a perfect name but wrong authority ownership is still structurally unsound. A class with a mediocre name but correct authority boundaries is structurally sound.

---

## Authority Vocabulary Glossary

| Term | Meaning | Authority Scope | Example |
|------|---------|----------------|---------|
| **Decision** | Final, sovereign authority. Immutable after publication. | legitimacy derivation | `ConstitutionalLegitimacyDecision` |
| **Resolver** | Converts evaluation state into authoritative outcome. Single point of derivation. | evaluation → outcome | `ConstitutionalLegitimacyDecision` (the sole resolver) |
| **Evaluator** | Computes evidence state without deriving authority. Returns findings, not outcomes. | evidence → finding | `TrustPolicyEvaluator`, `NetworkBindingPolicy` |
| **Policy** | Constitutional rule that evaluates specific evidence dimensions. NEVER produces outcomes. | evidence → finding | `DeviceBindingPolicy`, `VerificationAttestationPolicy` |
| **Finding** | Output of a policy evaluation. Descriptive, not authoritative. | policy output | `PolicyFinding` (formerly `ConstitutionalFinding`) |
| **Overlay** | Observational signal source. Observes, never interprets or decides. | observation only | `Overlay` interface, `DeviceAnomalyOverlay` |
| **Signal** | Individual observation unit. Categorical classification, not scalar weight. | observation unit | `OverlaySignal`, `OverlaySignalCategory` (formerly `OverlayInfluence`) |
| **Observation** | Non-authoritative contextual interpretation. A flat set — no ordering precedence. | evidence context | `ConstitutionalObservationContext`, `ObservationRecorded` |
| **Aggregator** | Collects observations without filtering, ranking, or prioritizing. | collection only | `OverlayAggregator` (formerly `OverlayCoordinator`) |
| **Registry** | Static definition catalog. No runtime logic, no authority. | definition storage | `OverlayRegistry` (formerly `ConstitutionalOverlayRegistry`) |
| **Projection** | Read-only presentation of constitutional state. No behavioral methods, no recalculation. | presentation only | `ConstitutionalTrustSnapshot`, `EvidenceSnapshot` |
| **Stratification** | Structural grouping/classification of overlays by layer. NOT execution order or priority. | classification | `OverlayStratification` |
| **Snapshot** | Frozen evidence at a point in time. Deterministic hash, replay-safe. | evidence preservation | `ConstitutionalTrustSnapshot`, `ElectionConstitutionSnapshot` |
| **Envelope** | Sealed evidence container with integrity verification. | evidence transport | `TrustEvaluationEnvelope`, `EvidenceEnvelope` |
| **Certification** | Replay outcome confirmation. Not authority. | replay result | `ReplayCertification` |
| **Session** | Bounded lifecycle for a replay evaluation. | lifecycle management | `ReplaySession` |
| **Divergence** | Record of authority mismatch between sovereignty modes. Immutable after creation. | telemetry | `SovereigntyDivergenceRecord`, `DivergenceObserved` |

---

## Authority Boundaries

### Bounded Context: Legitimacy Evaluation

| Type | Owns Authority | Must Never Own |
|------|---------------|----------------|
| `ConstitutionalLegitimacyDecision` | Legitimacy derivation (TrustEvaluationState → LegitimacyOutcome) | Evidence collection, observation ranking |
| `LegitimacyOutcome` | Enum of valid constitutional outcomes | Behavioral methods, derivation logic |
| `TrustPolicyEvaluator` | Policy orchestration sequence | Final legitimacy outcome |
| `PolicySequence` | Policy evaluation ordering | Policy outcomes themselves |
| `ConstitutionalPolicy` (interface) | Policy evaluation contract | Any authority beyond returning findings |
| `PolicyFinding` | Policy evaluation result shape | Structural enforcement |
| `OverlayAggregator` | Aggregation of overlay signals | Signal filtering, ranking, or weighting |
| `Overlay` (interface) | Observation contract | Interpreting or deriving authority from observations |
| `OverlayRegistry` | Definition catalog | Evaluation logic, lifecycle management |
| `TrustCapabilityContext` | Evidence bag for evaluation | Outcome derivation |
| `ConstitutionalTrustSnapshot` | Frozen presentation data | Behavioral methods, recalculation |

### Bounded Context: Replay Certification

| Type | Owns Authority | Must Never Own |
|------|---------------|----------------|
| `ReplaySession` | Session lifecycle (sealed → replayed → certified/diverged) | Legitimacy outcome interpretation |
| `ReplayCertification` | Certification result (matched/diverged) | Policy evaluation, evidence dimension | n/a |
| `ReplayAssertion` | Contract binding envelope + expected outcome | Any post-creation mutation |
| `ReplayEvidenceEnvelope` | Evidence integrity and sealing | Outcome derivation |
| `ReplayCompatibilityVersion` | Schema version compatibility checks | Evaluation logic |

### Bounded Context: Sovereignty Migration

| Type | Owns Authority | Must Never Own |
|------|---------------|----------------|
| `SovereigntyDivergenceRecord` | Dual-authority mismatch recording | Resolution of divergences |
| `DivergenceType` | Divergence classification | Severity ranking, authority |
| `DivergenceSeverity` | Severity classification (telemetry) | Any influence on enforcement decisions |

### Event Types (Cross-Cutting)

| Category | Owns Authority | Must Never Own |
|----------|---------------|----------------|
| **Sovereign Events** | Record of authoritative decisions | None (immutable after emission) |
| **Replay Events** | Replay lifecycle telemetry | Legitimacy evaluation |
| **Migration Events** | Sovereignty transition audit trail | Authority over transition outcome |
| **Observational Events** | Pure telemetry, non-authoritative | Any influence on derivation |

---

## Authority Ownership Matrix

```
Class                        │ Owns                        │ Must Never Own
─────────────────────────────┼─────────────────────────────┼─────────────────────────────
ConstitutionalLegitimacyDecision │ legitimacy derivation    │ evidence collection
TrustPolicyEvaluator         │ policy evaluation           │ final legitimacy
ReplaySession                │ replay lifecycle            │ legitimacy
ReplayCertification          │ certification result        │ policy evaluation
OverlayAggregator            │ observation aggregation     │ signal ranking/weighting
OverlayRegistry              │ definition storage          │ evaluation logic
Overlay (interface)          │ observation contract        │ authority derivation
PolicyFinding                │ policy result shape         │ enforcement semantics
ConstitutionalTrustSnapshot  │ frozen presentation data    │ behavioral methods, recalculation
OverlayStratification        │ layer classification        │ execution priority
OverlaySignalCategory        │ signal classification       │ scalar influence/weight
SovereigntyDivergenceRecord  │ divergence telemetry        │ divergence resolution
```

---

## Rename Summary (DD.3b)

Confirmed renames:

| Old Name | New Name | Rationale |
|----------|----------|-----------|
| `ConstitutionalOverlay` | `Overlay` | "Constitutional" prefix implies authority; overlays are purely observational |
| `ConstitutionalOverlayRegistry` | `OverlayRegistry` | Same authority prefix; registry has no sovereignty role |
| `OverlayCoordinator` | `OverlayAggregator` | "Coordinator" implies orchestration; only method is `aggregate()` |
| `ConstitutionalFinding` | `PolicyFinding` | "Constitutional" implies sovereign authority; these are policy evaluation outputs |
| `OverlayInfluence` | `OverlaySignalCategory` | "Influence" is scalar sovereignty language; enum values are signal categories |

Rejected/Deferred renames:

| Candidate | Decision | Rationale |
|-----------|----------|-----------|
| `ConstitutionalTrustSnapshot` → `TrustSnapshot` | Rejected | Context qualifier needed in multi-BC system; too generic without prefix |
| `OverlayStratification` → `OverlayEvaluationOrder` | Rejected | Enum values describe structural layers (EMERGENCY_CONSTITUTIONAL, GOVERNANCE_LAYER, etc.), not evaluation order. `evaluationOrder()` is a derived behavior. |
