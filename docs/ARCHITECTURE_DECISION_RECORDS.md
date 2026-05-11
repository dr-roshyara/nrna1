# Architecture Decision Records — Constitutional Governance Engine

**Project:** Public Digit Voting Platform - Constitutional Governance Layer  
**Timeline:** GEO-3.0 (2024) through GEO-3.4 (2026)  
**Format:** [ADR Template](https://adr.github.io/) adapted for domain governance

---

## ADR-001: Temporal Legitimacy Window Model (GEO-3.0)

**Date:** 2024-Q1 | **Status:** ACCEPTED | **Supersedes:** None

### Context

Governance engines traditionally answer "who wins right now?" — evaluating authority precedence at runtime using the current authority graph. This fails for historical governance archaeology:

- A decision made in 2024 under Director A cannot be replayed in 2026 if Director A's term has expired
- "Current legitimacy" evaluation breaks temporal meaning of decisions
- No distinction between "this authority was never valid" vs. "this authority expired after the decision"

### Decision

Introduce **TemporalAuthorityWindow** as the canonical model for all authority legitimacy:

```php
class TemporalAuthorityWindow {
    public DateTimeImmutable $validFrom;      // When authority becomes valid
    public ?DateTimeImmutable $validUntil;    // When authority expires (null = open-ended)
    
    public function stateAt(DateTimeImmutable $at): TemporalWindowState
    // Returns PENDING, ACTIVE, or EXPIRED
}
```

**Key principle:** Legitimacy is always point-in-time. Never "current legitimacy."

### Rationale

1. **Historical preservation:** Historical decisions maintain their legitimacy at decision time, not live time
2. **Constitutional semantics:** Temporal windows mirror real-world authority lifecycles (terms of office, appointment periods, emergency declarations)
3. **Deterministic replay:** Same decision time + same window = same legitimacy state, always
4. **Audit explainability:** Can answer "was this authority valid when the decision was made?" with certainty

### Consequences

✅ **Positive:**
- Governance archaeology becomes temporally precise
- Replay decisions are deterministic (same time → same legitimacy)
- Historical meaning is preserved across authority changes

❌ **Negative:**
- Must track `validFrom` and `validUntil` for every authority (GeoAuthorityGraph enrichment)
- Live governance cannot ask "is X currently valid?" directly (must be point-in-time)
- Administrative changes (suspension, emergency) require separate signals (deferred to GEO-3.1)

### Alternatives Considered

| Alternative | Why Rejected |
|-------------|-------------|
| "Current legitimacy" evaluation | Breaks historical archaeology; replay becomes non-deterministic |
| Duration-based windows (e.g., "6-month term") | Cannot handle mid-term resignation or emergency extension |
| Implicit windows (infer from graph changes) | Requires historical graph reconstruction; too fragile |

---

## ADR-002: Wrapper Pattern for Constitutional Arbitration (GEO-3.1)

**Date:** 2024-Q2 | **Status:** ACCEPTED | **Supersedes:** ADR-001 (partial)

### Context

GEO-3.0 adds temporal legitimacy. Now we need constitutional judgment: "was this decision constitutionally valid?" This must layer atop the existing operational `GovernanceDecisionKernel` without modifying it.

Options:
1. **Modify kernel directly** — Add constitutional logic into GovernanceDecisionKernel
2. **Wrapper (new decision class)** — Create new layer that wraps kernel output
3. **Separate service** — Constitutional arbitration runs in parallel, no coupling

### Decision

Use **wrapper pattern** with new `ConstitutionalGovernanceDecision` class:

```
GovernanceDecisionKernel (unchanged)
    ↓ produces
GovernanceDecision (operational layer)
    ↓ wrapped by
ConstitutionalGovernanceDecision (new)
    ├─ governanceDecision: GovernanceDecision
    └─ constitutionalDecision: ConstitutionalDecision
```

**Key principle:** Kernel never modified. Constitutional layer is purely additive.

### Rationale

1. **Zero breaking changes:** Existing clients of GovernanceDecisionKernel work unchanged
2. **Clear separation of concerns:** Operational logic (who wins?) vs. constitutional validity (is it legal?)
3. **Additive evolution:** Future layers (compliance, institutional) can wrap further without touching kernel
4. **Test isolation:** Kernel tests remain unchanged; constitutional tests added independently

### Consequences

✅ **Positive:**
- Kernel remains stable and testable in isolation
- Constitutional layer can evolve independently
- Easy to add future layers (compliance, transparency, etc.)

❌ **Negative:**
- One extra object allocation per decision (negligible for governance use case)
- Clients must be aware of both layers (documented in handbook)

### Alternatives Considered

| Alternative | Why Rejected |
|-------------|-------------|
| Modify kernel directly | Violates SRP; couples operational + constitutional concerns; breaks existing tests |
| Separate parallel service | No ownership relationship; hard to ensure both run; fragile |

---

## ADR-003: Snapshot-Based Replay Instead of Event Sourcing (GEO-3.2)

**Date:** 2024-Q3 | **Status:** ACCEPTED | **Supersedes:** None

### Context

Governance decisions must be replayable years later. Two storage models:

1. **Event sourcing:** Store all decisions as immutable events, rebuild state by replaying
2. **Snapshots:** Store complete decision context at one point in time, replay from snapshot

Governance requires temporal accuracy: replaying a 2024 decision in 2026 must use 2024 doctrine, 2024 authorities, 2024 legitimacy policies.

### Decision

Use **snapshot-based persistence** for GEO-3.2, with event sourcing deferred to GEO-4.x:

```php
GovernanceDecisionSnapshot {
    // Complete historical context embedded
    decisionId, decidedAt, capabilityType,
    winningAuthorityId, legitimacy,
    constitutionalReasonJson, arbitrationTraceJson,
    metadata: {doctrineVersion, arbitrationPolicyVersion, ...},
    integrityHash, replayFingerprint
}
```

### Rationale

1. **Temporal isolation:** Snapshot embeds ALL context needed to replay. No live graph lookup required.
2. **Deterministic replay:** No question "which events to include?" or "in what order?" — snapshot is complete.
3. **Corruption detection:** Integrity hash proves snapshot wasn't tampered with.
4. **Schema stability:** Fixed snapshot structure easier to version than event stream.

### Consequences

✅ **Positive:**
- Replay is completely isolated from live governance state
- No circular dependencies (events wouldn't know their replay consequences)
- Simpler for compliance audit (snapshot ≈ legal record)

❌ **Negative:**
- Snapshots are larger than individual events (acceptable given governance infrequency)
- Schema evolution more complex (one large versioned structure)
- Defers event sourcing benefits (explicit audit log) to GEO-4.x

### Alternatives Considered

| Alternative | Why Rejected |
|-------------|-------------|
| Pure event sourcing (GEO-3.2) | Would require embedding all historical rules in events; complex versioning |
| Hybrid (events + snapshots) | GEO-3.2 not ready for hybrid; deferred to GEO-4.x |

---

## ADR-004: Canonical Serialization for Semantic Invariance (GEO-3.3)

**Date:** 2024-Q4 | **Status:** ACCEPTED | **Supersedes:** None

### Context

Snapshots are hashed for integrity verification. Two serialization approaches:

1. **Loose serialization:** Use PHP's default json_encode — field order varies, could produce different JSON for same data
2. **Canonical serialization:** Fixed field order, sorted arrays, deterministic output

Without canonical serialization, identical constitutional meaning could produce different hashes due to serialization order.

### Decision

Implement **CanonicalConstitutionalSerializer** with mandatory rules:

```php
class CanonicalConstitutionalSerializer {
    public function serializeReason(ConstitutionalReason $reason): string {
        // 1. Sort arrays alphabetically (articleCodes, etc.)
        // 2. Fix field order (code → summary → explanation → articleCodes → ...)
        // 3. Convert enums to string values
        // 4. Use flags: JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        // Result: Identical constitutional meaning → identical JSON → identical hash
    }
}
```

### Rationale

1. **Semantic stability:** Same constitutional decision produces same fingerprint regardless of implementation order
2. **Cross-version compatibility:** JSON produced in 2024 is byte-identical to JSON produced in 2026 (if semantic content is same)
3. **Prevents drift:** Team members cannot accidentally break replay invariants via serialization changes
4. **Hash determinism:** Proves "two snapshots are constitutionally equivalent"

### Consequences

✅ **Positive:**
- Semantic invariance is mathematically provable (canonical hash)
- Easier to integrate with future audit systems (deterministic signatures)
- Protects against subtle serialization bugs

❌ **Negative:**
- Serialization logic must be maintained carefully (changes break compatibility)
- Slightly more code (custom serializer vs. json_encode)

### Alternatives Considered

| Alternative | Why Rejected |
|-------------|-------------|
| Loose serialization | Different orderings → different hashes → false equivalence failures in replay |
| Explicit field ordering in properties | Fragile; doesn't prevent new developers from adding unsorted fields |

---

## ADR-005: Five-Dimensional Semantic Equivalence (GEO-3.4)

**Date:** 2025-Q1 | **Status:** ACCEPTED | **Supersedes:** ADR-004 (extends)

### Context

Snapshots must be certified as "constitutionally equivalent" to handle:
- Doctrine evolution (rules change, but historical decision meaning preserved)
- Engine improvements (replay engine version changes, same outcome)
- Schema migrations (snapshot fields evolve, decision semantics unchanged)

Which snapshot fields constitute "semantic equivalence" vs. "structural difference"?

### Decision

Define **5 mandatory equivalence dimensions**; certification fails if ANY drift:

| Dimension | Snapshot Field | Rationale |
|-----------|---|---|
| **Legitimacy** | `legitimacy` field | Authority validity at decision time must match |
| **Winning Authority** | `winningAuthorityId` | Same authority must win (not a different one) |
| **Capability Type** | `capabilityType` | Same decision type (not confused with another) |
| **Constitutional Scope** | `constitutionalScope` | Same jurisdiction (NATIONAL vs REGIONAL) |
| **Doctrine Version** | `metadata.doctrineVersion` | Same constitutional rules in effect |

**Non-semantic fields (ignored in equivalence):**
- `replayEngineVersion` (engine improvements don't change meaning)
- `replayCompatibilityVersion` (backward-compatibility flags)
- `generatedAt` (when snapshot was written, not semantic)

### Rationale

1. **Completeness:** These 5 dimensions capture ALL constitutional meaning
2. **Explicitness:** Prevents silent equivalence mismatches (e.g., scope drift)
3. **Migration safety:** Can evolve snapshot schema without breaking equivalence
4. **Audit clarity:** Certification report names exact dimension of any drift

### Consequences

✅ **Positive:**
- Equivalence testing is exhaustive and explicit
- Snapshot schema can evolve (new fields ignored if non-semantic)
- Drift is immediately visible in certification reports

❌ **Negative:**
- Certification logic must stay in sync with domain definitions (coupling risk)
- Adding new constitutional dimensions requires reviewing all prior snapshots

### Alternatives Considered

| Alternative | Why Rejected |
|-------------|-------------|
| Single hash equivalence | Doesn't explain WHERE drift occurred (all-or-nothing) |
| Field-by-field equivalence | Too granular; false positives on irrelevant changes |

---

## ADR-006: Domain Purity — No Laravel in Constitutional Layer (GEO-3.4)

**Date:** 2025-Q2 | **Status:** ACCEPTED | **Supersedes:** ADR-001, ADR-002 (enforcement)

### Context

Constitutional decisions are constitutional law, not application infrastructure. They may outlive:
- The current Laravel version
- The current database system
- The current framework entirely

Where should constitutional domain code live?

Options:
1. **With application logic** — `app/` directory, use Laravel
2. **Pure domain** — Standalone directory, pure PHP (no framework)
3. **Hybrid** — Mostly domain, but use Laravel where convenient

### Decision

Enforce **absolute domain purity**: Zero Laravel dependencies in `Constitutional/` namespace.

```
app/Contexts/Membership/Domain/Committee/Constitutional/
  └─ Pure PHP ONLY
    ✓ DateTimeImmutable, arrays, interfaces
    ✗ Eloquent, Facades, Service Container
    ✗ Any Illuminate\ or Laravel\ namespace
```

### Rationale

1. **Framework independence:** Constitutional decisions transcend Laravel lifecycle
2. **Testability:** No service container coupling; unit tests are trivial
3. **Longevity:** Code survives framework rewrites (unlikely but possible)
4. **Clarity:** Constitutional concepts are clearly separated from infrastructure
5. **Portability:** Governance logic could be extracted to library or separate service

### Consequences

✅ **Positive:**
- Domain is absolutely testable without framework
- Clear conceptual separation (constitutional vs. operational)
- Easier for non-Laravel developers to understand constitutional logic
- Future-proof against framework changes

❌ **Negative:**
- Cannot use Laravel conveniences (e.g., Cache facade for caching)
- Requires explicit service provider bindings (extra wiring)
- Slightly more code for things Laravel does for free

### Enforcement

- ✅ Automated fitness test: `test_constitutional_domain_files_have_no_laravel_imports`
- ✅ CI blocks merge if violated
- ✅ Code review checklist includes this rule

---

## ADR-007: Replay Immutability Boundary (GEO-3.2, GEO-3.3)

**Date:** 2024-Q4 | **Status:** ACCEPTED | **Supersedes:** ADR-003

### Context

Replaying a historical governance decision must use **only stored snapshot data**, never:
- Current authority graph (authorities may have changed)
- Live doctrine (constitutional rules may have evolved)
- Current time (must use decision time from snapshot)
- Current infrastructure (systems may be reimplemented)

Temptation: "Can we enhance replay by looking up current authority X?" Answer: NO. This breaks temporal meaning.

### Decision

Enforce **hermetic replay boundary**: `GovernanceReplayService` reads snapshot ONLY.

```
Forbidden in replay:
  ✗ $graph->getNode(id)
  ✗ $doctrineRegistry->findEffectiveAt(time)
  ✗ new SystemClock()
  ✗ call GovernanceDecisionKernel

Required in replay:
  ✓ $snapshot->winningAuthorityId (from snapshot)
  ✓ $snapshot->metadata->doctrineVersion (from snapshot)
  ✓ $snapshot->decidedAt (from snapshot)
  ✓ FixedClock($snapshot->decidedAt) (injected)
```

### Rationale

1. **Determinism:** Same snapshot + same replay engine = identical archaeology record
2. **Historical integrity:** Cannot accidentally "rewrite history" via live data
3. **Auditability:** Replay is reproducible and traceable
4. **Legal defensibility:** Replayed decision matches original

### Enforcement

- ✅ Code review: Reviewers verify replay service doesn't call infrastructure
- ✅ Fitness test: `test_replay_service_has_no_eloquent_constructor_params`
- ✅ Design: GovernanceReplayService constructor has NO infrastructure dependencies

---

## ADR-008: Scope-Aware Governance (GEO-3.4)

**Date:** 2025-Q1 | **Status:** ACCEPTED | **Supersedes:** None

### Context

Governance decisions exist at different constitutional scopes:
- **NATIONAL** — Applies to entire federation (all voters)
- **REGIONAL** — Applies to one state/province
- **EMERGENCY** — Temporary extraordinary authority
- **CARETAKER** — Interim appointment
- **ELECTION** — Specific election period

A decision made under NATIONAL scope cannot be replayed under REGIONAL scope. But early implementation missed scope tracking entirely.

### Decision

Introduce **ConstitutionalScope** enum and validate scope compatibility during replay:

```php
enum ConstitutionalScope: string {
    case NATIONAL = 'national';
    case REGIONAL = 'regional';
    case EMERGENCY = 'emergency';
    case CARETAKER = 'caretaker';
    case ELECTION = 'election';
}

class ScopeAwareReplayValidator {
    public function validate(snapshot, replayScope): void {
        // Null scope = unconstrained (can replay under any scope)
        // Matching scopes = allowed
        // Mismatched scopes = throw ScopeMismatchException
    }
}
```

### Rationale

1. **Constitutional correctness:** Scope defines jurisdiction; cannot be ignored
2. **Prevents confusion:** National decision cannot be mistaken for regional
3. **Audit clarity:** Explicitly documents which constitutional context
4. **Future epochs:** Institutional epochs (ADR-009) require scope awareness

### Consequences

✅ **Positive:**
- Scope violations are detected early
- Snapshots embed scope (persisted and replayed correctly)

❌ **Negative:**
- Adds another dimension to equivalence checking
- Early snapshots may have `scope=null`; must handle gracefully

---

## ADR-009: Institutional Epochs as Constitutional Boundaries (GEO-3.4)

**Date:** 2025-Q2 | **Status:** ACCEPTED | **Supersedes:** ADR-008 (extends)

### Context

Governance history segmentsby constitutional reform. Examples:
- **2018-2024:** Original Constitution
- **2024-03-15 to 2024-04-01:** Emergency Government (temporary)
- **2024-04-01 onward:** Reformed Constitution (post-amendment)

Decisions made in one epoch may need re-evaluation in another (e.g., emergency decree may expire when emergency ends).

### Decision

Introduce **InstitutionalEpoch** as temporal boundary marker:

```php
class InstitutionalEpoch {
    public string $epochId;
    public string $name;
    public DateTimeImmutable $startedAt;
    public ?DateTimeImmutable $endedAt;
    public ?string $constitutionReference;
    
    public function isActiveAt(DateTimeImmutable $at): bool { ... }
    public function isOpen(): bool { return $endedAt === null; }
}
```

### Rationale

1. **Constitutional periods:** Separates decisions into constitutional eras
2. **Lineage clarity:** Governance lineage graph can track epoch transitions
3. **Compliance:** Some decisions valid only in specific epochs
4. **Flexibility:** Supports constitutional amendments, regime changes, emergency periods

### Consequences

✅ **Positive:**
- Governance history is segmented by constitutional reality
- Clear boundaries for compliance and audit

❌ **Negative:**
- Adds complexity to governance lineage graphs
- Epoch definitions must be managed and versioned

---

## ADR-010: Governance Lineage Graph (GEO-3.4)

**Date:** 2025-Q2 | **Status:** ACCEPTED | **Supersedes:** None

### Context

Traditional governance history is linear: Decision 1 → Decision 2 → Decision 3 → ...

But real governance branches:
- Emergency decree (Decision 2A) runs parallel to normal process (Decision 2B)
- Constitutional amendments supersede prior decisions
- Multiple authority lines (national, regional, emergency) coexist

Linear chain model is insufficient.

### Decision

Implement **GovernanceLineageGraph** — directed acyclic graph of decisions:

```php
class GovernanceLineageGraph {
    private array $nodes;      // Map[decisionId → GovernanceLineageNode]
    private array $edges;      // List[GovernanceLineageEdge]
    
    public function addNode(GovernanceLineageNode): void { ... }
    public function addEdge(GovernanceLineageEdge): void { ... }
    public function branches(): string[] { ... }  // nodeIds with >1 successor
}

class GovernanceLineageEdge {
    public string $fromNodeId;
    public string $toNodeId;
    public string $edgeReason;  // "successor", "amendment", "emergency_fork"
}
```

### Rationale

1. **Branching realities:** Models actual governance (emergency ≠ normal authority)
2. **Amendment tracking:** Supersession chain explicit
3. **Archaeology:** Can trace how decisions relate
4. **Future complexity:** Supports institutional epochs (ADR-009)

### Consequences

✅ **Positive:**
- Arbitrary governance topologies supported
- Clear distinction between succession, amendment, emergency fork

❌ **Negative:**
- More complex than linear chain
- Storage and traversal more involved

---

## Summary: Architectural Evolution

| GEO Phase | Decision | Key Innovation |
|-----------|----------|-----------------|
| **GEO-3.0** | ADR-001 | Temporal legitimacy windows |
| **GEO-3.1** | ADR-002 | Constitutional wrapper pattern |
| **GEO-3.2** | ADR-003 | Snapshot-based replay (not events) |
| **GEO-3.3** | ADR-004, ADR-005 | Canonical serialization + 5-D equivalence |
| **GEO-3.4** | ADR-006 through ADR-010 | Domain purity, scope awareness, lineage graphs |

---

## Governance of ADRs

**Process for proposing new ADRs:**

1. Open issue with "ADR:" prefix
2. Describe context, decision, rationale
3. List alternatives considered
4. Identify any superseded ADRs
5. Architecture review vote (unanimous preferred)
6. Merged ADR becomes immutable record

**Process for reconsidering ADRs:**

- Status changes to "SUPERSEDED" (not deleted)
- New ADR explicitly supersedes prior (with rationale)
- History is maintained for audit

---

**Last Updated:** 2026-05-08  
**Status:** 10 ADRs Accepted, 0 Superseded, 0 Pending  
**Next Review:** After GEO-3.5 completion
