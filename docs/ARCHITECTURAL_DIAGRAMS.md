# Constitutional Governance Architecture — System Diagrams

Generated during GEO-3.4 Consolidation Sprint.

---

## 1. Governance Decision Flow

How a governance decision flows from capability request through constitutional arbitration to persisted snapshot.

```mermaid
graph TD
    A["CapabilityContext<br/>(who, what, when)"] -->|evaluate| B["GovernanceDecisionKernel<br/>(operational layer)"]
    
    B -->|resolve authority| C["GeoAuthorityGraph<br/>(current state)"]
    C -->|classify| D["AuthorityClassification<br/>(exceptions, overrides, direct, delegated)"]
    
    D -->|apply precedence| E["DefaultConflictResolutionPolicy<br/>(precedence rules)"]
    E -->|returns| F["FinalAuthorityDecision<br/>(winner + reason)"]
    
    F -->|wrap with constitutional| G["ConstitutionalArbitrationPolicy<br/>(GEO-3.1 layer)"]
    G -->|derive legitimacy| H["GovernanceLegitimacy<br/>(LEGITIMATE|EXPIRED|...)"]
    
    G -->|build arbitration trace| I["ConstitutionalArbitrationTrace<br/>(decision path + evidence)"]
    
    G -->|returns| J["ConstitutionalDecision<br/>(winner, legitimacy, reason, trace, timestamp)"]
    
    K["ConstitutionalGovernanceDecision<br/>(operational + constitutional)"] -->|delegates to| B
    K -->|delegates to| G
    K -->|wraps| J
    
    K -->|persist to snapshot| L["GovernanceReplayService<br/>(GEO-3.2 layer)"]
    L -->|serialize reason & trace| M["CanonicalConstitutionalSerializer<br/>(deterministic JSON)"]
    
    M -->|canonical order| N["sorted arrays + fixed fields<br/>(prevents semantic drift)"]
    
    N -->|hash snapshot| O["SnapshotIntegrityHash<br/>& ConstitutionalReplayFingerprint"]
    
    O -->|store| P["GovernanceDecisionSnapshot<br/>(immutable archive)"]
    P -->|via| Q["GovernanceDecisionStore<br/>(interface - Eloquent in GEO-3.3)"]
    
    Q -->|persists to| R["governance_decisions table<br/>(complete historical context)"]
    
    style A fill:#e1f5e1
    style K fill:#e3f2fd
    style L fill:#f3e5f5
    style P fill:#fff3e0
    style R fill:#fce4ec
```

---

## 2. Replay Isolation & Immutability Model

How replay is completely isolated from live governance, using only stored snapshots.

```mermaid
graph LR
    subgraph LIVE["🟢 LIVE GOVERNANCE (Current State)"]
        A1["GeoAuthorityGraph<br/>(current nodes, edges)"]
        A2["DoctrineRegistry<br/>(current doctrine v2.0)"]
        A3["SystemClock<br/>(current time)"]
        A4["GovernanceDecisionKernel<br/>(operational engine)"]
    end
    
    subgraph ARCHIVED["📦 ARCHIVED SNAPSHOT (Historical Context)"]
        B1["GovernanceDecisionSnapshot"]
        B2["├─ decisionId<br/>├─ decided At<br/>├─ legitimacy: LEGITIMATE<br/>├─ winningAuthorityId: node-1<br/>├─ doctrineVersion: 1.0<br/>├─ arbitrationPolicyVersion: 1.0<br/>└─ integrityHash"]
    end
    
    subgraph REPLAY["🔄 REPLAY ENGINE (Isolation Boundary)"]
        C1["GovernanceReplayService"]
        C2["GovernanceArchaeologyRecord<br/>(read-only historical DTO)"]
        C3["FixedClock<br/>(historical time from snapshot)"]
        C4["InMemoryDoctrineRegistry<br/>(snapshot-provided doctrine)"]
    end
    
    LIVE -->|never directly used| REPLAY
    LIVE -->|only for new decisions| A4
    
    B1 -->|load snapshot| REPLAY
    C1 -->|.replay(decisionId)| B1
    
    C2 -->|.originallyDecidedAt()| B1
    C2 -->|.legitimacy()| B1
    C2 -->|.wasConstitutionallyValid()| B1
    
    C3 -->|provides historical time<br/>from snapshot.decidedAt| REPLAY
    A3 -->|NOT used| REPLAY
    
    C4 -->|provides doctrine v1.0<br/>from snapshot metadata| REPLAY
    A2 -->|NOT used| REPLAY
    
    A1 -->|NOT used| REPLAY
    
    style LIVE fill:#c8e6c9
    style ARCHIVED fill:#fff9c4
    style REPLAY fill:#f1f8e9
    style B1 fill:#ffccbc
    style B2 fill:#fff3e0
    style C1 fill:#c5e1a5
    style C3 fill:#a5d6a7
    style C4 fill:#a5d6a7
```

**Critical rules enforced:**
- ✓ Replay reads snapshot fields ONLY
- ✓ Replay never calls `GovernanceDecisionKernel`
- ✓ Replay never accesses live authority graph
- ✓ Replay never evaluates live doctrine
- ✓ Snapshot metadata is immutable (locked at creation)

---

## 3. Temporal Legitimacy Windows

How authority legitimacy is evaluated at specific moments in time.

```mermaid
graph TD
    A["Authority Lifecycle<br/>(with temporal windows)"] -->|has| B["TemporalAuthorityWindow"]
    
    B -->|constructor| C["validFrom: 2024-01-15<br/>validUntil: 2025-12-31"]
    
    C -->|evaluate at| D["DateTimeImmutable $at"]
    
    D -->|stateAt\($at\)| E{Time Comparison}
    
    E -->|$at < validFrom| F["TemporalWindowState::PENDING<br/>GovernanceLegitimacy::PENDING"]
    E -->|validFrom ≤ $at ≤ validUntil| G["TemporalWindowState::ACTIVE<br/>GovernanceLegitimacy::LEGITIMATE"]
    E -->|$at > validUntil| H["TemporalWindowState::EXPIRED<br/>GovernanceLegitimacy::EXPIRED"]
    
    F -->|isPendingAt\($at\) = true| I["Authority not yet valid"]
    G -->|isActiveAt\($at\) = true| J["Authority is constitutional"]
    H -->|isExpiredAt\($at\) = true| K["Authority no longer valid"]
    
    style B fill:#e0f2f1
    style C fill:#b2dfdb
    style D fill:#80cbc4
    style F fill:#ffcccc
    style G fill:#ccffcc
    style H fill:#ffcccc
    style I fill:#ffebee
    style J fill:#e8f5e9
    style K fill:#ffebee
```

**Key principle:** All legitimacy evaluation is point-in-time, never "current legitimacy."

---

## 4. Five-Dimensional Semantic Equivalence (Replay Certification)

How snapshots are certified to be constitutionally equivalent.

```mermaid
graph TD
    A["GovernanceArchaeologyRecord<br/>(replayed snapshot)"] -->|compare| B["GovernanceDecisionSnapshot<br/>(original snapshot)"]
    
    B -->|validate 5 dimensions| C["ReplayCertification"]
    
    C -->|dimension 1| D1["Legitimacy Equivalence<br/>original.legitimacy ==<br/>replayed.legitimacy"]
    C -->|dimension 2| D2["Winning Authority Equivalence<br/>original.winningAuthorityId ==<br/>replayed.winningAuthorityId"]
    C -->|dimension 3| D3["Capability Type Equivalence<br/>original.capabilityType ==<br/>replayed.capabilityType"]
    C -->|dimension 4| D4["Constitutional Scope Equivalence<br/>original.scope ==<br/>replayed.scope"]
    C -->|dimension 5| D5["Doctrine Version Equivalence<br/>original.doctrineVersion ==<br/>replayed.doctrineVersion"]
    
    D1 -->|all pass| E["✅ isEquivalent = true<br/>equivalenceBreaches = []"]
    D2 -->|all pass| E
    D3 -->|all pass| E
    D4 -->|all pass| E
    D5 -->|all pass| E
    
    D1 -->|any fails| F["❌ isEquivalent = false<br/>equivalenceBreaches = [...]"]
    D2 -->|any fails| F
    D3 -->|any fails| F
    D4 -->|any fails| F
    D5 -->|any fails| F
    
    E -->|returns| G["ReplayCertification<br/>'All 5 dimensions verified'"]
    F -->|returns| H["ReplayCertification<br/>'Semantic drift: legitimacy, scope'"]
    
    style D1 fill:#e1f5e1
    style D2 fill:#e1f5e1
    style D3 fill:#e1f5e1
    style D4 fill:#e1f5e1
    style D5 fill:#e1f5e1
    style E fill:#c8e6c9
    style F fill:#ffcccc
    style G fill:#a5d6a7
    style H fill:#ef9a9a
```

---

## 5. Governance Lineage Graph (Branching History)

How governance decisions form a directed acyclic graph, supporting emergency forks and amendments.

```mermaid
graph TD
    A["2024-01-15<br/>decision-1<br/>NATIONAL scope"] -->|successor| B["2024-02-20<br/>decision-2<br/>NATIONAL scope"]
    
    B -->|successor| C["2024-03-10<br/>decision-3<br/>NATIONAL scope"]
    
    C -->|amendment| D["2024-04-01<br/>decision-4<br/>post-reform NATIONAL scope"]
    
    C -->|emergency_fork| E["2024-03-15<br/>decision-5<br/>EMERGENCY scope<br/>(temporary authority)"]
    
    D -->|successor| F["2024-05-10<br/>decision-6<br/>NATIONAL scope"]
    
    E -->|successor| F
    
    B -->|amendment_supersedes| G["decision-2 becomes<br/>superseded_byDoctrineId"]
    
    subgraph EPOCH1["Era: Constitutional Pre-Reform<br/>(2024-01-15 to 2024-04-01)"]
        A
        B
        C
    end
    
    subgraph EPOCH2["Era: Post-Reform Constitution<br/>(2024-04-01 onwards)"]
        D
        F
    end
    
    subgraph EMERGENCY["Emergency State<br/>(2024-03-15 to 2024-04-15)"]
        E
    end
    
    H["GovernanceLineageGraph<br/>branches() = [decision-3]<br/>(has 2 successors)"]
    
    style A fill:#c5cae9
    style B fill:#c5cae9
    style C fill:#c5cae9
    style D fill:#b2dfdb
    style E fill:#ffccbc
    style F fill:#b2dfdb
    style EPOCH1 fill:#f5f5f5
    style EPOCH2 fill:#e8f5e9
    style EMERGENCY fill:#fff3e0
    style H fill:#f1f8e9
```

**Features:**
- Linear history (A → B → C → D → F)
- Amendment branches (C → D)
- Emergency forks (C → E → F)
- Institutional epochs track scope changes
- Architecture supports arbitrary branching

---

## 6. Doctrine Artifact with Provenance Chain

How constitutional doctrine is versioned, approved, and superseded.

```mermaid
graph TD
    A["Constitution v1.0"] -->|approved by| B["DoctrineProvenance"]
    B -->|approvedBy: President<br/>approvedAt: 2024-01-15<br/>reason: 'Initial constitutional framework'<br/>supersedesDoctrineId: null| C["DoctrineArtifact"]
    
    C -->|contains| D["DoctrineArtifact<br/>doctrineId: 'constitution_v1_2024'<br/>version: '1.0'<br/>effectiveFrom: 2024-01-15<br/>effectiveUntil: null (open)<br/>constitutionalScope: NATIONAL<br/>doctrineRules: [...]"]
    
    D -->|canonical hash| E["DoctrineArtifactHash<br/>sha256(sorted_rules_json)"]
    
    E -->|verify integrity| F["verifyIntegrity(): bool"]
    
    D -->|superseded by| G["Constitution v2.0<br/>(2024-04-01 reform)"]
    
    G -->|approved by| H["DoctrineProvenance"]
    H -->|approvedBy: Parliament<br/>approvedAt: 2024-04-01<br/>reason: 'Post-emergency reform'<br/>supersedesDoctrineId: constitution_v1_2024<br/>supersededByDoctrineId: constitution_v2_2024| I["DoctrineArtifact v2"]
    
    I -->|contains| J["DoctrineArtifact<br/>doctrineId: 'constitution_v2_2024'<br/>version: '2.0'<br/>effectiveFrom: 2024-04-01<br/>constitutionalScope: NATIONAL<br/>doctrineRules: [...]"]
    
    J -->|canonical hash| K["DoctrineArtifactHash<br/>(different from v1)"]
    
    style B fill:#fff3e0
    style H fill:#fff3e0
    style C fill:#e0f2f1
    style I fill:#e0f2f1
    style E fill:#f1f8e9
    style K fill:#f1f8e9
```

**Critical properties:**
- ✓ Doctrine is immutable after approval
- ✓ Supersession chain is explicit
- ✓ Provenance tracks approval authority and reason
- ✓ Hash prevents tampering
- ✓ Snapshots embed doctrineVersion → can look up original doctrine for replay

---

## 7. Snapshot Schema Integrity & Decomposition

How snapshots embed all historical context and decompose into sub-structures.

```mermaid
graph TD
    A["GovernanceDecisionSnapshot<br/>(monolithic archive)"] -->|decompose| B["4 sub-structures"]
    
    A -->|.identity\(\)| C["SnapshotIdentity<br/>├─ decisionId<br/>├─ capabilityType<br/>└─ constitutionalScope"]
    
    A -->|.decisionData\(\)| D["SnapshotDecisionData<br/>├─ winningAuthorityId<br/>├─ legitimacy<br/>└─ constitutionalReasonJson"]
    
    A -->|.traceData\(\)| E["SnapshotTraceData<br/>├─ arbitrationTraceJson<br/>├─ doctrineVersion<br/>└─ arbitrationPolicyVersion"]
    
    A -->|.integrityData\(\)| F["SnapshotIntegrityData<br/>├─ integrityHash<br/>├─ schemaVersion<br/>├─ replayEngineVersion<br/>└─ replayCompatibilityVersion"]
    
    A -->|contains all fields| G["Complete snapshot<br/>├─ Identity + DecisionData + TraceData + IntegrityData<br/>├─ Metadata: doctrineVersion, arbitrationPolicyVersion,<br/>│           legitimacyPolicyVersion, replayEngineVersion<br/>├─ Timestamps: decidedAt, generatedAt<br/>└─ Hashes: integrityHash, (implicit fingerprint)"]
    
    A -->|verify integrity| H["integrityHash verified<br/>via verifyIntegrity()"]
    
    A -->|compute fingerprint| I["ConstitutionalReplayFingerprint<br/>covers 5 dimensions (not engine versions)"]
    
    H -->|fields used| J["ALL snapshot fields<br/>(detect corruption)"]
    I -->|fields used| K["Semantic fields only<br/>(prove equivalence)"]
    
    style C fill:#e3f2fd
    style D fill:#f3e5f5
    style E fill:#fce4ec
    style F fill:#e8f5e9
    style G fill:#fff3e0
    style H fill:#ffcccc
    style I fill:#c8e6c9
    style J fill:#ffcccc
    style K fill:#c8e6c9
```

**Design principle:** Sub-structures are navigation aids. Snapshot is source of truth.

---

## 8. Canonical Serialization for Deterministic Hashing

How JSON serialization is made deterministic to prevent semantic drift.

```mermaid
graph TD
    A["ConstitutionalReason<br/>├─ code<br/>├─ summary<br/>├─ articleCodes: ['art.3', 'art.1', 'art.2'] (unsorted)<br/>├─ severity: ConstitutionalSeverity::BINDING<br/>└─ legitimacyImpact: LegitimacyImpact::VALID"] -->|serialize| B["CanonicalConstitutionalSerializer<br/>.serializeReason()"]
    
    B -->|step 1: sort arrays| C["articleCodes: ['art.1', 'art.2', 'art.3']<br/>(canonical order)"]
    
    B -->|step 2: fixed field order| D["JSON field order fixed<br/>code → summary → explanation →<br/>articleCodes → severity → legitimacyImpact"]
    
    B -->|step 3: enum to value| E["severity: 'binding'<br/>(enum.value)"]
    
    B -->|step 4: serialize flags| F["JSON_THROW_ON_ERROR<br/>| JSON_UNESCAPED_UNICODE<br/>| JSON_UNESCAPED_SLASHES"]
    
    F -->|produces| G["Deterministic JSON string<br/>{'code':'...','summary':'...',<br/>'articleCodes':['art.1',...],<br/>'severity':'binding',...}"]
    
    G -->|same input| H["ALWAYS same output"]
    
    A -->|different order<br/>articleCodes: ['art.2', 'art.1', 'art.3']| I["Still produces identical JSON<br/>via canonical sort"]
    
    H -->|feed to hash| J["sha256(canonical_json)<br/>→ identical hash"]
    
    style B fill:#e0f2f1
    style C fill:#b2dfdb
    style D fill:#80cbc4
    style E fill:#4db6ac
    style F fill:#26a69a
    style G fill:#009688
    style H fill:#00897b
    style J fill:#00695c
```

**Guarantee:** Same constitutional meaning → identical JSON → identical hash. Prevents semantic drift from serialization order.

---

## 9. Package Boundary Enforcement via Architecture Fitness Tests

How architectural boundaries are enforced through executable tests.

```mermaid
graph TD
    subgraph DOMAIN["Domain Layer<br/>App\Contexts\Membership\Domain\Committee\Constitutional\"]
        D1["✓ Pure PHP only<br/>✓ final readonly classes<br/>✓ No Laravel imports<br/>✓ All VOs immutable"]
    end
    
    subgraph BOUNDARY["Fitness Tests<br/>(Executable Boundaries)"]
        B1["✓ test_constitutional_domain_files_have_no_laravel_imports<br/>→ Glob + regex check for 'use Illuminate'"]
        B2["✓ test_all_constitutional_vos_are_final_and_readonly<br/>→ Reflection::getModifiers()"]
        B3["✓ test_replay_service_does_not_depend_on_projection_boundary<br/>→ Reflection::getInterfaceNames()"]
        B4["✓ test_governance_replay_service_has_no_eloquent_constructor_params<br/>→ Reflection::getParameters()->getType()"]
        B5["✓ test_lineage_graph_does_not_import_snapshot_store<br/>→ File::get() + strpos check"]
    end
    
    subgraph CI["CI Pipeline<br/>(Continuous Enforcement)"]
        C1["php artisan test<br/>tests/Unit/Domain/Committee/Constitutional/ArchitectureFitnessTest.php"]
        C2["FAIL → Block merge<br/>PASS → Boundary maintained"]
    end
    
    DOMAIN -->|must pass| B1
    DOMAIN -->|must pass| B2
    DOMAIN -->|must pass| B3
    DOMAIN -->|must pass| B4
    DOMAIN -->|must pass| B5
    
    B1 -->|run via| C1
    B2 -->|run via| C1
    B3 -->|run via| C1
    B4 -->|run via| C1
    B5 -->|run via| C1
    
    C1 -->|auto-check| C2
    
    style DOMAIN fill:#e1f5e1
    style B1 fill:#fff9c4
    style B2 fill:#fff9c4
    style B3 fill:#fff9c4
    style B4 fill:#fff9c4
    style B5 fill:#fff9c4
    style C1 fill:#f0f4c3
    style C2 fill:#c8e6c9
```

**Benefit:** Boundaries are self-enforcing. Violations caught immediately, not in code review.

---

## Summary: Architectural Layers & Dependencies

```mermaid
graph BT
    A["HTTP/Controllers<br/>(CommitteeManagementController)"]
    B["Application Layer<br/>(Use Cases, Commands, DTOs)"]
    C["Domain Layer<br/>(Constitutional/)"]
    D["Infrastructure Layer<br/>(Eloquent, DB, Services)"]
    
    A -->|calls| B
    A -->|never directly calls| C
    B -->|calls via interfaces| C
    B -->|calls via interfaces| D
    D -->|implements| C
    
    C -->|zero dependencies<br/>on D| C
    
    style A fill:#bbdefb
    style B fill:#c8e6c9
    style C fill:#fff9c4
    style D fill:#ffccbc
```

**Critical:** Domain layer has zero knowledge of infrastructure. Domain defines contracts. Infrastructure implements them.

---

**All diagrams locked in. Changes require architecture review.**
