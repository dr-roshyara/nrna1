# Constitutional Governance Architecture Handbook

**Version:** 3.4  
**Status:** GEO-3.4 Complete — 233 Domain Tests Passing  
**Last Updated:** 2026-05-08  
**Audience:** Domain architects, governance engineers, compliance officers

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Architectural Principles](#architectural-principles)
3. [Domain Model](#domain-model)
4. [Temporal Legitimacy Foundation (GEO-3.0)](#temporal-legitimacy-foundation-geo-30)
5. [Constitutional Arbitration Engine (GEO-3.1)](#constitutional-arbitration-engine-geo-31)
6. [Governance Replay Engine (GEO-3.2)](#governance-replay-engine-geo-32)
7. [Snapshot Schema Governance (GEO-3.3)](#snapshot-schema-governance-geo-33)
8. [Doctrine Foundation & Infrastructure (GEO-3.4)](#doctrine-foundation--infrastructure-geo-34)
9. [Boundary Enforcement](#boundary-enforcement)
10. [Semantic Invariance Guarantees](#semantic-invariance-guarantees)
11. [Critical Architectural Constraints](#critical-architectural-constraints)

---

## Executive Summary

The Constitutional Governance Engine is a temporal, audit-proof governance decision system designed to answer:

> **"Who was constitutionally allowed to make this decision at a specific moment in time?"**

Unlike traditional governance systems that answer "who wins right now?", the Constitutional Engine preserves historical constitutional meaning across doctrine evolution, authority changes, and emergency states. Every decision is:

- **Temporally valid** — legitimacy verified at decision time, not live evaluation
- **Institutionally scoped** — national, regional, emergency, or caretaker constitutional scope
- **Semantically immutable** — replaying historical decisions produces identical outcomes
- **Fully traced** — every decision path recorded with complete arbitration evidence
- **Doctrine-aware** — constitutional meaning preserved even when rules change

**Current scope:** GEO-3.4 implements doctrine foundation, scope enforcement, 5-dimensional semantic equivalence, and replay certification. Historical governance replay is snapshot-isolated and deterministic.

**Test coverage:** 233 passing domain tests across 7 GEO phases (GEO-3.0 through GEO-3.4). Architecture fitness tests enforce import boundaries. Semantic invariance tests prevent meaning drift.

---

## Architectural Principles

### 1. **Replay Immutability (Golden Rule)**

```
HISTORICAL GOVERNANCE REPLAY MUST NEVER DEPEND ON:
  ✗ Current authority graphs
  ✗ Current constitutional doctrines
  ✗ Current infrastructure state
  ✗ Live policy evaluation
```

**Why this matters:** A constitutional decision made in 2024 must replay identically in 2026, even if the constitution changed, directors resigned, emergency doctrine was declared, or the system was reimplemented.

**Implementation:** All snapshots embed:
- Historical metadata (doctrineVersion, arbitrationPolicyVersion, legitimacyPolicyVersion)
- Stored legitimacy (not re-evaluated)
- Stored winning authority (not re-determined)
- Integrity hash (detects corruption)
- Replay fingerprint (proves semantic equivalence)

### 2. **Domain Purity**

The `Constitutional/` namespace is **pure PHP with zero Laravel dependencies**:

```
Domain layer:        Pure PHP, no framework
Application layer:   Limited Laravel (DTOs, interfaces only)
Infrastructure layer: Laravel fully allowed
```

**Why:** Governance decisions are constitutional artifacts, not infrastructure. They must survive database rewrites, ORM changes, and framework upgrades.

### 3. **Temporal Window Semantics**

All authority legitimacy is evaluated through temporal windows:

```php
TemporalAuthorityWindow($validFrom, $validUntil)
  .stateAt($evaluationTime)  // → PENDING | ACTIVE | EXPIRED
  .isActiveAt($t)            // → bool
```

No "current legitimacy" evaluation. Legitimacy is always point-in-time.

### 4. **Doctrine-as-Code with Immutable Artifacts**

Constitutional doctrine is versioned, immutable, and semantically hashed:

```php
DoctrineArtifact {
  doctrineId:     "constitution_v1_2024"
  version:        "1.0"
  effectiveFrom:  2024-01-15
  effectiveUntil: null (open-ended)
  doctrineRules:  ["exception_overrides_override", ...]  // sorted canonically
  doctrineHash:   sha256(canonical_sort(rules)) // deterministic
  provenance:     {approvedBy, approvedAt, reason, supersedes}
}
```

**Why immutability:** Doctrine is constitutional law. It cannot be retroactively changed without breaking governance archaeology.

### 5. **Five-Dimensional Semantic Equivalence**

Replay certification validates all 5 constitutional dimensions:

| Dimension | Meaning | Protected | Example |
|-----------|---------|-----------|---------|
| **Legitimacy** | Authority was valid at decision time | Stored in snapshot | LEGITIMATE vs EXPIRED |
| **Winning Authority** | Same authority won | Snapshot embeds ID | node-1 vs node-2 |
| **Capability Type** | Same decision type | Not re-evaluated | ELECT_PRESIDENT vs DELEGATE |
| **Constitutional Scope** | Same governance jurisdiction | Scope-aware validation | NATIONAL vs REGIONAL |
| **Doctrine Version** | Same constitutional rules | Metadata embedded | doctrine_v1.0 vs doctrine_v2.0 |

Snapshot fails certification if ANY dimension drifts.

### 6. **Institutional Epochs as Constitutional Boundaries**

Governance history is segmented by constitutional eras:

```
2018-01-01 to 2024-02-15 → Constitutional Era 1
2024-02-15 to 2025-06-30 → Emergency Government (constitutional scope change)
2025-07-01 to ∞          → Constitutional Era 2 (post-reform)
```

Decisions made in one epoch maintain their legitimacy only within that epoch's constitutional context.

---

## Domain Model

### Core Entities & Value Objects

```
┌─────────────────────────────────────────────────────────┐
│         CONSTITUTIONAL GOVERNANCE DECISION              │
│  (Wrapper: Operational + Constitutional layers)         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  govDecision: GovernanceDecision (operational result)   │
│  constDecision: ConstitutionalDecision (constitutional) │
│                                                          │
│  Accessors (convenience):                               │
│    id() → GovernanceDecisionId                          │
│    decidedAt() → DateTimeImmutable                      │
│    winner() → ?JurisdictionNode                         │
│    legitimacy() → GovernanceLegitimacy                  │
│    isConstitutionallyValid() → bool                     │
│                                                          │
└─────────────────────────────────────────────────────────┘
         ↓ Wrapped by
┌─────────────────────────────────────────────────────────┐
│      GOVERNANCE DECISION SNAPSHOT (Immutable Archive)   │
│  (Embeds ALL historical context for replay)             │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Identification:                                        │
│    decisionId, capabilityType, constitutionalScope      │
│                                                          │
│  Decision Data:                                         │
│    winningAuthorityId, legitimacy,                      │
│    constitutionalReasonJson, arbitrationTraceJson       │
│                                                          │
│  Metadata (Historical Context):                         │
│    schemaVersion, doctrineVersion,                      │
│    legitimacyPolicyVersion, arbitrationPolicyVersion,   │
│    replayEngineVersion, replayCompatibilityVersion,     │
│    generatedAt                                          │
│                                                          │
│  Integrity:                                             │
│    integrityHash (sha256, detects corruption),          │
│    verifyIntegrity() → bool                             │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Temporal Authority Windows

```
TemporalAuthorityWindow {
  validFrom:      DateTimeImmutable
  validUntil:     ?DateTimeImmutable (null = open-ended)
  
  stateAt(time):  TemporalWindowState (PENDING | ACTIVE | EXPIRED)
  isActiveAt():   bool
  isExpiredAt():  bool
  isPendingAt():  bool
}
```

### Doctrine Artifacts

```
DoctrineArtifact {
  doctrineId:      string ("constitution_v1_2024")
  version:         string ("1.0")
  constitutionalScope: ConstitutionalScope (NATIONAL|REGIONAL|EMERGENCY|CARETAKER|ELECTION)
  effectiveFrom:   DateTimeImmutable
  effectiveUntil:  ?DateTimeImmutable
  doctrineRules:   string[] (sorted canonically)
  doctrineHash:    string (sha256)
  provenance:      DoctrineProvenance
  
  Methods:
    isEffectiveAt(DateTimeImmutable): bool
    verifyIntegrity(): bool
}

DoctrineProvenance {
  approvedBy:           string
  approvedAt:           DateTimeImmutable
  supersedesDoctrineId: ?string
  supersededByDoctrineId: ?string
  reason:               string
  
  Methods:
    supersedes(): bool
    isSuperseded(): bool
}
```

### Governance Lineage

```
GovernanceLineageGraph {
  nodes:  Map[decisionId → GovernanceLineageNode]
  edges:  List[GovernanceLineageEdge]
  
  Methods:
    addNode(GovernanceLineageNode): void
    addEdge(GovernanceLineageEdge): void
    findNode(decisionId): ?GovernanceLineageNode
    successorsOf(nodeId): string[]
    branches(): string[] (nodeIds with >1 successor)
    nodeCount(): int
}

GovernanceLineageNode {
  decisionId:    string
  integrityHash: string
  decidedAt:     DateTimeImmutable
}

GovernanceLineageEdge {
  fromNodeId:   string
  toNodeId:     string
  edgeReason:   string ("successor"|"amendment"|"emergency_fork")
}
```

---

## Temporal Legitimacy Foundation (GEO-3.0)

### Problem Solved

The engine initially answered "who wins right now?" — static authority precedence. GEO-3.0 adds temporal dimension: "who was constitutionally allowed to win at a specific moment in time?"

### Key Innovations

1. **TemporalAuthorityWindow** — captures `[validFrom, validUntil]` for any authority
2. **LegitimacyEvaluator** — maps temporal states to legitimacy (LEGITIMATE | EXPIRED | PENDING | SUSPENDED | EMERGENCY | CARETAKER | REVOKED)
3. **Convenience state accessors** — `isActiveAt()`, `isExpiredAt()`, `isPendingAt()`

### Legitimacy States

| State | Derivation | Meaning |
|-------|-----------|---------|
| **LEGITIMATE** | `validFrom ≤ t AND (validUntil = null OR t ≤ validUntil)` | Authority is constitutional |
| **PENDING** | `t < validFrom` | Authority not yet active |
| **EXPIRED** | `validUntil != null AND t > validUntil` | Authority no longer valid |
| **SUSPENDED** | (Admin signal, GEO-3.2+) | Temporarily revoked by order |
| **EMERGENCY** | (Admin signal, GEO-3.2+) | Granted temporary extraordinary powers |
| **CARETAKER** | (Admin signal, GEO-3.2+) | Interim/transitional authority |
| **REVOKED** | (Admin signal, GEO-3.2+) | Permanently voided |

### Critical Design Constraint

`LegitimacyEvaluator` derives ONLY 3 states:
```
LEGITIMATE ← [PENDING → ACTIVE → EXPIRED] window evaluation
PENDING    ← before validFrom
EXPIRED    ← after validUntil
```

States like SUSPENDED, EMERGENCY, CARETAKER, REVOKED require **administrative signals** only available when full node+edge context exists (GEO-3.1+). They are defined in enum for completeness; not derivable in GEO-3.0.

**Test suite:** 5 tests in `TemporalAuthorityWindowTest`, 3 in `GovernanceLegitimacyTest`, 3 in `LegitimacyEvaluatorTest` → 11 tests total.

---

## Constitutional Arbitration Engine (GEO-3.1)

### Problem Solved

GEO-3.0 added temporal state. GEO-3.1 wraps the operational `GovernanceDecisionKernel` with constitutional layer: answering "was this decision constitutionally valid?" without modifying existing kernel.

### Architecture: Wrapper Pattern (Zero Breaking Changes)

```
ConstitutionalArbitrationKernel
  .decide(ctx, capability, ?at)
    ├─ GovernanceDecisionKernel.decide(ctx, capability, at)  ← UNCHANGED
    │    └─ returns GovernanceDecision (operational)
    ├─ ConstitutionalArbitrationPolicy.arbitrate(classification, at)
    │    └─ returns ConstitutionalDecision (constitutional)
    └─ returns ConstitutionalGovernanceDecision (wrapper)
```

**Key principle:** The operational kernel is never modified. Constitutional layer is purely additive wrapping.

### Legitimacy Derivation

`DefaultConstitutionalArbitrationPolicy` derives legitimacy from **resolution type** (NOT via `LegitimacyPolicy` — extension point deferred to GEO-3.2):

```php
$legitimacy = $finalDecision->winningNode !== null
    ? GovernanceLegitimacy::LEGITIMATE   // Exception, Override, Direct, Delegated
    : GovernanceLegitimacy::EXPIRED;     // No authority found
```

**Why:** Graph traversal already validated temporal correctness for each authority. Calling `LegitimacyPolicy` with synthetic windows would manufacture a predetermined answer — semantic abuse.

### Arbitration Trace

Every decision includes complete trace:

```php
ConstitutionalArbitrationTrace {
  evaluatedNodeIds:      string[]    // All authorities considered
  selectedNodeId:        ?string     // Winner (null = no authority)
  precedenceReason:      string      // Why this authority won
  doctrineRulesApplied:  string[]    // Constitutional rules invoked
  rejectionReasons:      array[]     // Why others were rejected
}
```

**Purpose:** Explainability + audit + debugging. Decision can always be replayed with full rationale.

### Constitutional Decision

```php
ConstitutionalDecision {
  winner:      ?JurisdictionNode
  legitimacy:  GovernanceLegitimacy
  reason:      ConstitutionalReason
  evaluatedAt: DateTimeImmutable
  trace:       ConstitutionalArbitrationTrace
}

ConstitutionalReason {
  code:                string
  summary:             string
  explanation:         string
  articleCodes:        string[]      // Constitutional references
  severity:            ConstitutionalSeverity
  legitimacyImpact:    LegitimacyImpact
}
```

**Test suite:** 22 tests across 6 test files → 120 total (GEO-3.1 adds 22 to GEO-3.0's 98).

---

## Governance Replay Engine (GEO-3.2)

### Problem Solved

Operational + constitutional layers exist in memory. GEO-3.2 makes them **persistent and replayable** — every governance decision can be reconstructed identically from a snapshot.

### Snapshot Architecture

```
GovernanceDecisionSnapshot (Immutable Archive)
  ├─ Identity: decisionId, capabilityType, constitutionalScope
  ├─ Decision: winningAuthorityId, legitimacy, constitutionalReasonJson, arbitrationTraceJson
  ├─ Metadata: schemaVersion, doctrineVersion, legitimacyPolicyVersion, 
  │            arbitrationPolicyVersion, replayEngineVersion, replayCompatibilityVersion
  └─ Integrity: integrityHash (sha256, immutable proof), verifyIntegrity() → bool
```

### Replay Service (Domain Layer)

```php
GovernanceReplayService {
  __construct(GovernanceDecisionStore, GovernanceClock)
  
  persist(ConstitutionalGovernanceDecision, CapabilityType, ?SnapshotMetadata)
    → GovernanceDecisionSnapshot
  
  replay(GovernanceDecisionId)
    → GovernanceArchaeologyRecord
    throws GovernanceDecisionNotFoundException | SnapshotIntegrityViolationException
}

GovernanceArchaeologyRecord (Read-only historical DTO)
  decisionId(): string
  legitimacy(): GovernanceLegitimacy
  wasConstitutionallyValid(): bool
  winningAuthorityId(): ?string
  originallyDecidedAt(): DateTimeImmutable
  persistedAt(): DateTimeImmutable
```

### Persistence Boundary

**Pure domain layer.** No DB migration, no Eloquent, no infrastructure code. `GovernanceDecisionStore` is interface only — Eloquent implementation deferred to GEO-3.3 infrastructure layer.

**Test suite:** 15 tests across 4 test files → 135 total (GEO-3.2 adds 15 to GEO-3.1's 120).

---

## Snapshot Schema Governance (GEO-3.3)

### Problem Solved

Snapshots are now persistent. GEO-3.3 governs schema evolution, adds canonical serialization, and introduces read models (projections) while maintaining replay isolation.

### Snapshot Decomposition

```
GovernanceDecisionSnapshot accessors:

.identity(): SnapshotIdentity
  {decisionId, capabilityType, constitutionalScope}

.decisionData(): SnapshotDecisionData
  {winningAuthorityId, legitimacy, constitutionalReasonJson}

.traceData(): SnapshotTraceData
  {arbitrationTraceJson, doctrineVersion, arbitrationPolicyVersion}

.integrityData(): SnapshotIntegrityData
  {integrityHash, schemaVersion, replayEngineVersion, replayCompatibilityVersion}
```

### Canonical Serialization

```php
CanonicalConstitutionalSerializer {
  serializeReason(ConstitutionalReason): string
    → Fixed field order, canonically sorted articleCodes
    → JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
  
  serializeTrace(ConstitutionalArbitrationTrace): string
    → Fixed field order, canonically sorted evaluatedNodeIds, doctrineRulesApplied
    → Same flags
}
```

**Why canonical:** Identical constitutional meaning must produce identical JSON, which produces identical hash. Serialization order is deterministic — prevents semantic drift from field ordering.

### Two-Hash Model

| Hash | Purpose | Coverage |
|------|---------|----------|
| **SnapshotIntegrityHash** | Corruption detection | All snapshot fields (decision, metadata, timestamps) |
| **ConstitutionalReplayFingerprint** | Semantic identity proof | Doctrine versions, legitimacy, authority, scope, reason, trace — NOT replayEngineVersion or replayCompatibilityVersion |

Fingerprint proves two snapshots are constitutionally equivalent even if produced by different engine versions.

### Read Models & Projection Boundary

```php
GovernanceTimelineProjection (Pure DTO, no logic)
  {decisionId, legitimacy, winningAuthorityId, capabilityType, 
   decidedAt, persistedAt, replayFingerprint, doctrineVersion, schemaVersion}

GovernanceTimelineProjector (Pure assembler)
  .project(GovernanceDecisionSnapshot): GovernanceTimelineProjection

ProjectionAntiCorruptionBoundary (Marker interface)
  ← Projections implement this
  ← Replay layer does NOT import it (architectural dependency barrier)
```

**Critical rule:** Replay uses snapshots only. Read models are navigational artifacts. Never call snapshot APIs from projection code.

### Infrastructure Persistence (GEO-3.3)

- DB table: `governance_decisions` with all snapshot fields
- Eloquent model: `GovernanceDecisionSnapshotModel` (non-incrementing UUID PK)
- Repository: `EloquentGovernanceDecisionStore` (implements domain interface)
- Service provider binding: `GovernanceDecisionStore::class` → `EloquentGovernanceDecisionStore::class`

**Test suite:** 39 tests across 11 test files → 174 total (GEO-3.3 adds 39 to GEO-3.2's 135).

---

## Doctrine Foundation & Infrastructure (GEO-3.4)

### GEO-3.4A: Doctrine Foundation (Tasks 1-9)

#### Task 1: Controller Governance Boundary Hardening

Enforced that all aggregate mutations flow through domain use cases, eliminating 3 Eloquent bypasses in `CommitteeManagementController`:

```php
// ❌ BEFORE: Direct Eloquent bypass
$committee->update(['region_code' => ..., 'country_code' => ...]);

// ✅ AFTER: Flow through domain use case
committee.updateOperationalGeo(geoReference, regionCode, countryCode);
```

All CRUD operations now enforce governance boundary. Direct Eloquent writes to aggregates are impossible.

#### Tasks 2-5: Doctrine as Constitutional Artifact

| Task | Class | Role |
|------|-------|------|
| 2 | `GovernanceJurisdictionId` VO | Constitutional jurisdiction boundary (not generic tenant) |
| 3 | `DoctrineProvenance` VO | Doctrine approval chain + supersession tracking |
| 4 | `DoctrineArtifact` + `DoctrineArtifactHash` | Immutable constitutional law with canonical hashing |
| 5 | `InMemoryDoctrineRegistry` | Test-time doctrine lookup without persistence |

**Key:** Doctrine artifacts are immutable constitutional law. Version them. Hash them. Track who approved. Prevent retroactive changes.

#### Task 6: Scope-Aware Replay Validation

```php
ScopeAwareReplayValidator.validate(snapshot, replayScope): void
  throws ScopeMismatchException

// Compatibility rules:
null scope snapshot     ← can replay under any scope
NATIONAL scope snapshot ← can only replay under NATIONAL scope
REGIONAL scope snapshot ← can only replay under REGIONAL scope
```

Prevents cross-scope governance confusion (NATIONAL decision cannot be replayed as REGIONAL decision).

#### Task 7: Typed Constitutional Semantics

Migrated string constants → enums:

```php
ConstitutionalSeverity(string-backed):
  DETERMINATIVE = 'determinative'
  BINDING = 'binding'
  PERSUASIVE = 'persuasive'

LegitimacyImpact(string-backed):
  VALID = 'valid'
  QUESTIONABLE = 'questionable'
  INVALID = 'invalid'
```

**Critical:** Enum values MUST match legacy string constants exactly (snapshot backward compatibility).

#### Task 8: Timeline Sequence Policies

Governs temporal ordering semantics:

```php
TimelineSequencePolicy (interface)
  compare(GovernanceDecisionSnapshot a, GovernanceDecisionSnapshot b): int

DecidedAtSequencePolicy       // Order by when decision was made
PersistedAtSequencePolicy     // Order by when snapshot was written
```

Enables archaeology navigation with explicit temporal semantics.

#### Task 9: Replay Persistence Verification

Integration test proving full persist → replay pipeline preserves constitutional identity using in-memory store stub.

**Test suite (GEO-3.4A):** 39 tests across 10 test files → 204 total domain tests.

### GEO-3.4B: Constitutional Infrastructure (Tasks 10-16)

#### Task 10: Institutional Epochs

```php
InstitutionalEpoch {
  epochId:               string
  name:                  string
  startedAt:             DateTimeImmutable
  endedAt:               ?DateTimeImmutable
  constitutionReference: ?string
  
  isActiveAt(time):      bool
  isOpen():              bool
}
```

Pre/post-reform constitutional boundaries. Governance history segments by constitutional era.

#### Task 11: Governance Lineage Graph

```php
GovernanceLineageGraph {
  nodes:   Map[id → GovernanceLineageNode]
  edges:   List[GovernanceLineageEdge]
  
  successorsOf(nodeId):  string[]
  branches():            string[]  // nodeIds with >1 successor (forks)
  nodeCount():           int
}
```

Supports branching governance histories (not just linear chains). Enables archaeology of emergency decrees, constitutional amendments, caretaker authorities.

#### Task 12: Constitutional Article Registry

```php
ConstitutionalArticleReference {
  articleId:      string
  title:          string
  scopeContext:   ?string
}

ConstitutionalArticleReferenceCollection {
  add(ref): self       // Immutable
  filterByScope(?s):   self
  toArticleCodes():    string[]
  count():             int
}
```

Typed article references for doctrine lookup. Parallel enrichment layer alongside string `articleCodes` in reasons.

#### Task 13: Governance Provenance

```php
GovernanceProvenance {
  producedByKernel:        string
  doctrineVersion:         string
  replayEngineVersion:     string
  migratedFromVersion:     ?string
  certificationId:         ?string
  provenanceCreatedAt:     DateTimeImmutable
  
  wasMigrated():           bool
  isCertified():           bool
}
```

Snapshot-level provenance. Tracks which kernel produced decision, which doctrine was active, migration status, certification ID.

#### Task 14: Replay Certification (5-Dimensional Equivalence)

```php
ReplayCertification.certify(
  GovernanceArchaeologyRecord record,
  GovernanceDecisionSnapshot original,
  DateTimeImmutable certifiedAt
): ReplayCertification

// Validates 5 dimensions:
1. legitimacy              (same GovernanceLegitimacy value)
2. winning_authority       (same authorityId)
3. capability_type         (same CapabilityType)
4. constitutional_scope    (same ConstitutionalScope)
5. doctrine_version        (same doctrineVersion from metadata)
```

Certification fails if ANY dimension drifts. Complete semantic equivalence proof.

#### Task 15: Constitutional Semantic Invariance Tests

Tests proving same constitutional meaning with different implementation produces same result:
- Enum vs string serialization produces identical JSON
- Rule ordering invariance (unordered arrays → identical hash)
- Stored legitimacy used, never re-evaluated
- Non-semantic fields don't affect fingerprint

#### Task 16: Architecture Fitness Tests

Automated import boundary enforcement via reflection assertions:
- Constitutional domain has zero Laravel imports
- All constitutional VOs are `final readonly`
- Replay service doesn't depend on projection boundary
- Governance lineage doesn't import decision store
- Fitness tests run in CI, fail if boundaries violated

**Test suite (GEO-3.4B):** 29 additional tests across 6 test files → 233 total domain tests.

---

## Boundary Enforcement

### Layer Architecture (Mandatory)

```
Domain Layer (Constitutional/*)
  ✓ Pure PHP, zero Laravel
  ✓ All classes final readonly
  ✓ All exceptions extend DomainException
  ✓ All identifiers are Value Objects
  ✗ No Eloquent, Facades, or Laravel features

Application Layer
  ✓ DTOs (readonly classes)
  ✓ Command handlers
  ✓ Use cases
  ✓ Repository interfaces (no implementation)
  ✗ No direct Eloquent
  ✗ No Facades

Infrastructure Layer
  ✓ Eloquent models allowed
  ✓ Repository implementations
  ✓ Service provider bindings
  ✓ Database migrations
  
HTTP/Controller Layer
  ✓ Format DTOs from requests
  ✓ Call use cases
  ✓ Return responses
  ✗ Never modify aggregates directly
  ✗ Never access repositories directly
```

### Import Boundary Enforcement

**Architecture fitness tests verify:**

```php
// ✓ This is allowed:
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;

// ✗ This is forbidden in domain:
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Framework\Whatever;
```

**Tooling:** PHPStan, Psalm, and custom tests enforce boundary via CI.

---

## Semantic Invariance Guarantees

### Replay Immutability Proofs

| Guarantee | How Enforced | Test Suite |
|-----------|-------------|-----------|
| Snapshot hash detects corruption | `SnapshotIntegrityHash` verification | `SnapshotIntegrityHashTest` |
| Fingerprint detects semantic drift | `ConstitutionalReplayFingerprint` on all 5 dimensions | `ConstitutionalReplayFingerprintTest` |
| Replay uses stored state, never re-evaluates | `GovernanceArchaeologyRecord` exposes snapshot fields directly | `ReplayCompatibilityTest` |
| Deterministic serialization | `CanonicalConstitutionalSerializer` with canonical field order + sorted arrays | `SemanticInvarianceTest` |
| Non-semantic fields ignored | `replayEngineVersion`, `replayCompatibilityVersion` excluded from fingerprint | `SemanticInvarianceTest` |
| Scope mismatch impossible | `ScopeAwareReplayValidator` enforces compatibility rules | `ScopeAwareReplayValidatorTest` |
| Doctrine version tracked | Metadata embeds `doctrineVersion` | All replay tests |

### Cross-Version Stability

Governance decisions made under doctrine v1.0 must produce **identical replay results** under doctrine v2.0 (if they're replayed under v1.0 context).

**How:** Snapshot embeds all historical context. Replay never uses live doctrine. Historical meaning is preserved.

---

## Critical Architectural Constraints

### LOCKED IN (Change breaks governance archaeology)

| Constraint | Why | Violation Cost |
|-----------|-----|-----------------|
| Snapshot → immutable | Constitutional decisions are historical law | Retroactive governance changes, audit failure |
| Enum values match legacy strings | JSON backward compatibility | Old snapshots fail integrity verification |
| DoctrineArtifact before DoctrineRegistry | Prevents orphaned registry | Historical replay silently reinterprets doctrine |
| Replay immutability | Historical decisions must replay identically | Governance archaeology becomes meaningless |
| GovernanceJurisdictionId not organisation_id | Constitutional ≠ generic tenancy | Mixing concerns collapses boundaries |
| Semantic preservation over completeness | Historical meaning > feature completeness | Decisions become legally meaningless |
| Domain purity (no Laravel in Constitutional/) | Governance transcends infrastructure | Framework changes break governance |
| Arbitration trace mandatory | Decision explainability | Opaque governance decisions, no audit trail |
| No controller Eloquent bypasses | All mutations flow through domain | Governance kernel becomes optional, not enforced |
| LegitimacyPolicy abstraction | Enables constitutional doctrine variation | Lost flexibility for future policy changes |

---

## File Organization Reference

```
app/Contexts/Membership/Domain/Committee/Constitutional/

├── TemporalWindowState.php
├── TemporalAuthorityWindow.php
├── GovernanceLegitimacy.php
├── ConstitutionalSeverity.php
├── LegitimacyImpact.php
├── LegitimacyEvaluator.php
├── ConstitutionalReason.php
│
├── ConstitutionalScope.php
├── GovernanceClock.php (interface)
├── SystemClock.php
├── FixedClock.php
│
├── Jurisdiction/
│   └── GovernanceJurisdictionId.php
│
├── Doctrine/
│   ├── DoctrineProvenance.php
│   ├── DoctrineArtifact.php
│   ├── DoctrineArtifactHash.php
│   ├── DoctrineRegistryInterface.php (interface)
│   ├── InMemoryDoctrineRegistry.php
│   ├── ConstitutionalArticleReference.php
│   └── ConstitutionalArticleReferenceCollection.php
│
├── Arbitration/
│   ├── ConstitutionalArbitrationPolicy.php (interface)
│   ├── DefaultConstitutionalArbitrationPolicy.php
│   ├── ConstitutionalArbitrationTrace.php
│   ├── ConstitutionalDecision.php
│   └── ConstitutionalGovernanceDecision.php
│
├── Kernel/
│   ├── ConstitutionalArbitrationKernel.php
│   ├── LegitimacyPolicy.php (interface)
│   └── DefaultTemporalLegitimacyPolicy.php
│
├── Snapshot/
│   ├── GovernanceDecisionSnapshot.php
│   ├── SnapshotMetadata.php
│   ├── SnapshotIntegrityHash.php
│   ├── ConstitutionalReplayFingerprint.php
│   ├── SnapshotIdentity.php
│   ├── SnapshotDecisionData.php
│   ├── SnapshotTraceData.php
│   ├── SnapshotIntegrityData.php
│   ├── SnapshotSchemaVersion.php
│   ├── SnapshotMigrationPolicy.php (interface)
│   ├── NoOpSnapshotMigrationPolicy.php
│   └── CanonicalConstitutionalSerializer.php
│
├── Replay/
│   ├── GovernanceDecisionStore.php (interface)
│   ├── GovernanceReplayService.php
│   ├── GovernanceArchaeologyRecord.php
│   ├── GovernanceDecisionNotFoundException.php
│   ├── SnapshotIntegrityViolationException.php
│   ├── ScopeMismatchException.php
│   ├── ScopeAwareReplayValidator.php
│   └── ReplayCertification.php
│
├── Timeline/
│   ├── GovernanceTimelineProjection.php
│   ├── GovernanceTimelineProjector.php
│   ├── ProjectionAntiCorruptionBoundary.php (interface)
│   ├── TimelineSequencePolicy.php (interface)
│   ├── DecidedAtSequencePolicy.php
│   └── PersistedAtSequencePolicy.php
│
├── Epoch/
│   └── InstitutionalEpoch.php
│
├── Lineage/
│   ├── GovernanceLineageNode.php
│   ├── GovernanceLineageEdge.php
│   └── GovernanceLineageGraph.php
│
├── Provenance/
│   └── GovernanceProvenance.php
│
└── Doctrine/
    └── DoctrineRegistryInterface.php

tests/Unit/Domain/Committee/Constitutional/
├── TemporalAuthorityWindowTest.php
├── GovernanceLegitimacyTest.php
├── LegitimacyEvaluatorTest.php
├── ... (39 test files, 233 tests total)
```

---

## Next Steps: GEO-3.4C Consolidation

### Immediate (This Phase)

1. ✅ **Architecture Documentation** (this handbook) — Governance concepts frozen in prose
2. ⬜ **Mermaid System Diagrams** — Visual architecture maps
3. ⬜ **Package Boundary Audit** — Verify no cross-boundary imports
4. ⬜ **Architecture Decision Records (ADRs)** — Rationale for critical choices
5. ⬜ **Replay Performance Benchmarking** — Establish baseline for future optimization

### Post-Consolidation (GEO-3.5+)

- **Snapshot Schema Freeze Policy** — Formal versioning SLA
- **Doctrine Authoring Rules** — Guidelines for constitutional law creation
- **Constitutional Membership Governance** — Voting rights, term limits, quorum (GEO-3.5)
- **Constitutional Provenance Infrastructure** — Event sourcing layer (GEO-4.x)

---

**This architecture is locked in. Changes require explicit RFC with governance impact assessment.**

Last reviewed: GEO-3.4 completion (233 tests passing, zero regressions)
