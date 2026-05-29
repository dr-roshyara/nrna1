# Semantic Boundary Map

**Status:** Governance Jurisdiction Definition  
**Phase:** D.R.2 Semantic Governance  
**Authority:** Senior Architectural Review  
**Purpose:** Define canonical vs compatibility zones and enforce migration boundaries

---

## Executive Summary

The runtime is divided into two jurisdictions:

| Zone | Purpose | Vocabulary | Expansion | Deletion Target |
|------|---------|-----------|-----------|-----------------|
| **Canonical** | Frozen observational core | Evidence, Observation, Evaluation | ❌ FORBIDDEN | ⏳ Stable (D.6+) |
| **Compatibility** | Strangler fig layer | Trust*, Influence*, Legacy* | ❌ FORBIDDEN | ✅ Phase E.3 |

This boundary prevents semantic recolonization and enforces disciplined migration.

---

## ZONE 1: CANONICAL (Frozen Constitutional Runtime)

### Scope

All files in:
```
app/Domain/Election/Security/Simplified/
app/Application/Election/Security/Simplified/
app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php
```

Plus new canonical files created during D.R.2:
```
app/Domain/Election/Constitution/ConstitutionalObservationContext.php
app/Application/Election/Security/OverlayObservationAggregator.php
```

### Constitutional Vocabulary (ALLOWED)

**Observation-Level:**
- observation, finding, concern, evidence, context
- signal (descriptive only, not procedural)
- annotation, basis, constitution

**Evaluation-Level:**
- evaluation, assessment, result, outcome
- state, condition, classification
- sufficiency, completeness

**Capability-Level:**
- capability, decision, permission
- allow, deny, abstain (resolver-only decision verbs)
- CapabilityDenialReason (enum of WHY decisions)

**Governance-Level:**
- doctrine, rule, invariant, principle
- boundary, jurisdiction, zone
- enforcement, contract, verification

### Forbidden Vocabulary (ABSOLUTELY PROHIBITED)

**Authority Semantics:**
```
trust, trusted, trustLevel, trustCapability
authorize, authorization, authority, grant
eligible, eligibility, participant, participation
canVote, canParticipate, hasCapability
```

**Influence Semantics:**
```
influence, influenced, influenceContext
elevate, elevation, elevationRequest
escalate, escalation, escalationPath
recommend, recommendation, recommendedPath
suggest, suggestion, suggestedAction
```

**Procedural Semantics:**
```
proceed, proceed
continue, stop, shortCircuit, block
path (except "basis"), route, routing
flow, step, sequence (in decision context)
```

**Legacy Trust-Era Vocabulary:**
```
TrustLevel, TrustEvaluationState, TrustCapabilityPolicy
VotingTrustResult, TrustEvaluationEnvelope
OverlayInfluenceContext, RecommendedProceduralPath
ConstitutionalTrustSnapshot
```

### Canonical Zone Rules

**MUST enforce:**
1. All observations are non-sovereign (no authority derivation)
2. No precedence ordering (Article 6)
3. No aggregation reconciliation (Article 2)
4. All evidence preserved (Article 3)
5. Resolver-exclusive interpretation (Article 4)
6. Neutral secondary properties (Article 7)

**MAY expand:**
- New observational capabilities (if preserve neutrality)
- New evidence types (if preserve non-sovereignty)
- New doctrinal articles (if preserve observational purity)

**MUST NOT expand:**
- Authority vocabulary
- Influence semantics
- Procedural routing
- Ranking/precedence logic
- Implicit ordering interpretation

---

## ZONE 2: COMPATIBILITY QUARANTINE (Strangler Fig Layer)

### Scope

Transitional files used by OLD pipeline only:
```
app/Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php
app/Application/Election/Security/TrustSnapshotAssembler.php
app/Application/Election/Security/SnapshotAssembler.php
tests/Unit/Domain/Election/Security/OverlaySignalTest.php (legacy model)
tests/Unit/Domain/Election/Security/TrustEvaluationEnvelopeTest.php
tests/Feature/Election/ConstitutionalParityIntegrationTest.php
```

### Permitted Vocabulary (TEMPORARY ONLY)

These exist **ONLY** in compatibility zone during strangler fig phase:
```
Trust*, TrustLevel, TrustEvaluationState
OverlayInfluenceContext, RecommendedProceduralPath
VotingTrustResult, TrustEvaluationEnvelope
ConstitutionalTrustSnapshot
elevationRequest, escalationPath
```

### Compatibility Zone Rules

**MUST enforce:**
1. No expansion (new files forbidden)
2. No new functionality additions
3. No creep into canonical zone
4. Clear adapter isolation
5. Strangler fig parity maintained

**MAY contain:**
- Old Trust* vocabulary (isolated only)
- Old Influence* semantics (no expansion)
- Legacy Recommendation* logic (documented, not spread)

**MUST NOT:**
- Import canonical classes and reuse them
- Blur boundary with canonical zone
- Create new compatibility-zone files
- Expand functionality scope

---

## BOUNDARY: CANONICAL ↔ COMPATIBILITY ISOLATION

### Isolation Mechanism

**One-way dependency allowed:**

```
Canonical MAY read Compatibility (during strangler fig)
    ↓
Canonical → OLD pipeline data
    ↓
Parity verification

FORBIDDEN:
Compatibility ← Canonical
(legacy code must not import new canonical classes)
```

**Rationale:** OLD pipeline remains authoritative during E.2. It may accept outputs from NEW canonical pipeline for parity verification. But OLD pipeline MUST NOT import or depend on new canonical classes (avoids semantic contamination).

### Migration Checkpoint Files

During D.R.2, these files track boundary health:

```
claude/governance/MigrationParityLog.md
    - Tracks divergence between OLD and NEW
    - Documents parity verification results
    - Flags semantic leakage attempts

claude/governance/CompatibilityZoneAudit.md
    - Lists all compatibility-zone files
    - Verifies no unauthorized expansion
    - Documents deletion targets for E.3
```

---

## NEW CANONICAL FILES (D.R.2 Creation)

These files are created during semantic refactoring and immediately frozen:

### 1. ConstitutionalObservationContext

**Location:** `app/Domain/Election/Security/Simplified/ConstitutionalObservationContext.php`

**Purpose:** Replaces `OverlayInfluenceContext` with pure observational topology

**Constraints:**
- Immutable collection of observations
- No ranking, precedence, or interpretation
- Preserves all evidence (Article 2)
- Allows conflicts (Article 3)
- Order-independent (Article 6)
- Neutrality verified (Article 7)

### 2. OverlayObservationAggregator

**Location:** `app/Application/Election/Security/OverlayObservationAggregator.php`

**Purpose:** Gathers overlay observations without interpretation

**Constraints:**
- Purely collection operation (no authority)
- Flat aggregation only
- No ranking, no reconciliation
- Returns `ConstitutionalObservationContext`

---

## ENFORCEMENT THROUGH CI GATES

### Automated Semantic Boundary Checks

These gates run on every PR:

```yaml
# .github/workflows/election-security-architecture.yml

- name: "Enforce Canonical Zone Vocabulary"
  run: |
    forbidden_in_canonical=(
      "trust" "authorize" "grant" "eligible"
      "influence" "escalate" "elevate" "recommend"
      "TrustLevel" "OverlayInfluenceContext"
    )
    
    for word in "${forbidden_in_canonical[@]}"; do
      grep -r "$word" app/Domain/Election/Security/Simplified/ \
        && exit 1 || true
    done

- name: "Verify Compatibility Zone Non-Expansion"
  run: |
    # Fail if new files added to compatibility zone
    git diff --name-only origin/main...HEAD | \
      grep -E "TrustCapabilityPolicy|SnapshotAssembler" && exit 1 || true

- name: "Detect Canonical→Compatibility Imports"
  run: |
    # Fail if compatibility zone imports new canonical classes
    grep -r "use App\Domain\Election\Security\Simplified" \
      app/Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php \
      && exit 1 || true
```

---

## MIGRATION SCHEDULE

### Phase D.R.2 (Current)

- [x] Define canonical zone
- [x] Define compatibility quarantine
- [ ] Create ConstitutionalObservationContext
- [ ] Implement OverlayObservationAggregator
- [ ] Verify boundary integrity
- [ ] Run parity tests

### Phase D.6 (Resolution Cutover)

- [ ] NEW pipeline fully tested and operational
- [ ] Parity verified across all scenarios
- [ ] Resolver integration complete
- [ ] OLD pipeline in maintenance mode

### Phase E.3 (OLD Pipeline Removal)

- [ ] Delete `TrustCapabilityPolicy.php`
- [ ] Delete `SnapshotAssembler.php`
- [ ] Delete `TrustSnapshotAssembler.php`
- [ ] Remove legacy test files
- [ ] Canonical zone becomes entire runtime

---

## Boundary Health Metrics

### Forbidden Expansion Signals

**RED FLAGS:**

- New files created in compatibility zone
- Canonical zone imports legacy classes
- Legacy code imports canonical classes
- New Trust* vocabulary in canonical files
- No parity log updates (suggests hidden divergence)

### Healthy Boundary Indicators

**GREEN LIGHTS:**

- Canonical zone remains frozen (no files changed)
- Compatibility zone unchanged (no file additions)
- Parity log regularly updated
- Semantic boundary tests all passing
- No import leakage detected

---

## Exception Process

If new canonical files are needed beyond those specified:

1. **Document rationale** in architectural review
2. **Verify observational purity** against all 7 articles
3. **Add to canonical-zone list** in this document
4. **Immediately freeze** (no further evolution)
5. **Get senior approval** before PR merge

**Philosophy:** Canonical zone grows only through intentional architecture decisions, never through accidental creep or convenience refactoring.

---

**This map is semantic jurisdiction boundary law. It prevents distributed sovereignty through explicit quarantine of legacy semantics.**

**Violations of boundary isolation recreate the procedural-authority-leakage that we are eliminating.**

