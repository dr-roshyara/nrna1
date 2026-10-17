# Membership Architecture Diagrams (C4 and Mermaid)

**Author:** Session 1. **Status:** PROPOSED documentation. **Baseline:** HEAD `f54037f96` (2026-10-16).
**Companion:** `20261016_2257_membership_architecture_verification_rev1.md` (requirement IDs BR-*, SEC-R*, open questions OQ-*, stories MEM-*).
**Placement note:** stored in the established Membership review location. The governed placement (`php scripts/doc-placement.php`) was **not run** (no execution authorised); a move into a governed diagrams location is a PO decision.
**Validation:** the Mermaid sources below were **not machine-validated** (no authorised renderer was run). They use only `flowchart`, `classDiagram`, `sequenceDiagram` and `stateDiagram-v2` constructs. C4 views are drawn as flowcharts in C4 notation, to avoid the experimental Mermaid C4 syntax.

**Legend used in every diagram:**
- solid box / solid line = **EXISTS** (VERIFIED in code at `f54037f96`);
- dashed box (`proposed` class) = **PROPOSED** (not built; needs the decisions listed);
- box marked `?` (`open` class) = **UNRESOLVED** (decision or evidence missing);
- `legacy` class = live legacy code that the ledger is meant to replace at cutover.

---

## 1. C4 Level 1: System context

```mermaid
flowchart TB
    classDef person fill:#dbeafe,stroke:#1e40af
    classDef system fill:#e5e7eb,stroke:#111827
    classDef ext fill:#f3f4f6,stroke:#6b7280
    classDef open fill:#fee2e2,stroke:#b91c1c

    Officer["Organisation officer<br/>(owner / admin)"]:::person
    MemberP["Member / applicant"]:::person
    PlatformAdmin["Platform admin"]:::person

    PD["PublicDigit platform<br/>(Laravel 12 monolith, multi-tenant)"]:::system

    Mail["Mail service<br/>(config only; not verified here)"]:::ext
    Ops["Production operations<br/>(PHP-FPM server; details UNKNOWN)"]:::open

    Officer -->|"manages members, fees, committees"| PD
    MemberP -->|"applies, views own membership"| PD
    PlatformAdmin -->|"platform administration"| PD
    PD -->|"sends notifications (UNVERIFIED for Membership)"| Mail
    Ops -.->|"deploys, runs migrations (OQ-01)"| PD
```

Requirements: BR-1, BR-15, BR-16. Unknowns: hosting topology, mail provider, cache driver (§4).

---

## 2. C4 Level 2: Containers (as verified)

```mermaid
flowchart TB
    classDef c fill:#e0f2fe,stroke:#0369a1
    classDef db fill:#fef3c7,stroke:#92400e
    classDef legacy fill:#f5f5f4,stroke:#57534e
    classDef proposed fill:#ffffff,stroke:#0369a1,stroke-dasharray: 5 5
    classDef open fill:#fee2e2,stroke:#b91c1c

    Browser["Browser SPA<br/>Vue 3 + Inertia 2"]:::c
    Web["Web application<br/>Laravel 12 HTTP: web + api/v1 route groups"]:::c
    Ledger["Membership ledger modules<br/>app/Contexts/Membership/*/Organisational<br/>(no HTTP caller, no container binding)"]:::c
    LegacyM["Legacy Membership<br/>app/Models/Member, MembershipPolicy,<br/>controllers, jobs"]:::legacy
    Sched["Scheduler / jobs<br/>(e.g. legacy expiry jobs; REPORTED)"]:::legacy
    Relay["Shared outbox relay<br/>(Shared messaging; tenant defects tracked elsewhere)"]:::open

    PG[("PostgreSQL default connection<br/>legacy tables + org_membership_* (OQ-01: in production?)")]:::db
    Outbox[("outbox_events table (shared)")]:::db
    Cache[("Cache store<br/>driver in production UNKNOWN")]:::open

    Browser -->|"HTTPS, Inertia"| Web
    Web --> LegacyM
    LegacyM --> PG
    Sched --> PG
    Web --> Cache
    Ledger -->|"PostgresMemberRepository (tests only)"| PG
    Ledger -.->|"PROPOSED: MEM-PUB via OutboxService (exists, no caller)"| Outbox
    Relay --> Outbox
```

Facts: `PostgresMemberRepository.php`; `OutboxService.php` writes `outbox_events` and has no caller; legacy writers per CUT-3 (REPORTED counts).

---

## 3. C4 Level 3: Membership ledger components (verified scope)

```mermaid
flowchart LR
    classDef k fill:#dcfce7,stroke:#166534
    classDef a fill:#e0f2fe,stroke:#0369a1
    classDef i fill:#fef3c7,stroke:#92400e
    classDef proposed fill:#ffffff,stroke:#0369a1,stroke-dasharray: 5 5
    classDef open fill:#fee2e2,stroke:#b91c1c

    subgraph Application["Application (Organisational)"]
        AdmitH["AdmitMemberHandler"]:::a
        EndH["EndMembershipHandler"]:::a
        DA["DecideAndAppend<br/>(authority, load, decide, append, idempotency)"]:::a
        RepoPort["MemberRepositoryPort<br/>load, findByHolder, create, appliedCommand, append"]:::a
        AuthPort["AuthorityCheckPort<br/>check(tenant, actor, act)"]:::a
        SVT["StorableValidTime"]:::a
        Cor3A["Annul / Void / Correct handlers<br/>MEM-COR3A"]:::proposed
    end

    subgraph Domain["Domain kernel (pure PHP)"]
        Member["Member aggregate"]:::k
        Facts["Facts: Admit, End, Annul, VoidFact"]:::k
        Res["Resolution"]:::k
        Inv["InvariantSet (G*)"]:::k
        Dec["Decision / RefusalReason"]:::k
        Correct["Correct, consequences<br/>MEM-COR2"]:::proposed
    end

    subgraph Infrastructure["Infrastructure (Organisational)"]
        PGRepo["PostgresMemberRepository<br/>(Annul/Void not encodable: V-4)"]:::i
        Interim["InterimRoleAuthority<br/>(Admit owner/admin; End denied: GOV-END)"]:::i
        RoleReader["OrganisationRoleReader (interface)<br/>Eloquent reader: MEM-2B"]:::i
        PJson["ProvenanceJson"]:::i
        Binding["Container bindings<br/>MEM-2B"]:::proposed
        Pub["Event publication<br/>MEM-PUB (OQ-08)"]:::open
    end

    AdmitH --> DA
    EndH --> DA
    DA --> AuthPort
    DA --> RepoPort
    DA --> Member
    Member --> Facts
    Member --> Res
    Member --> Inv
    Member --> Dec
    RepoPort -. implemented by .-> PGRepo
    AuthPort -. implemented by .-> Interim
    Interim --> RoleReader
    PGRepo --> PJson
    PGRepo --> SVT
    Cor3A -.-> DA
    Correct -.-> Member
    Binding -.-> PGRepo
    Binding -.-> Interim
    Pub -.-> PGRepo
```

Requirements: BR-1…BR-9a, BR-12, BR-13. Findings shown: V-4 (adapter), GOV-END (interim adapter).

---

## 4. C4 deployment view (only what is recorded)

```mermaid
flowchart TB
    classDef known fill:#e5e7eb,stroke:#111827
    classDef open fill:#fee2e2,stroke:#b91c1c

    subgraph Prod["Production environment"]
        App["Application server<br/>PHP-FPM, Laravel 12.70<br/>(source: ADR Laravel 12 outage note)"]:::known
        PGP[("PostgreSQL<br/>engine/version per PBDIGIT-261: UNVERIFIED")]:::open
        CacheP[("Cache store: UNKNOWN")]:::open
        CronP["Scheduler / outbox relay invocation: UNKNOWN"]:::open
    end
    subgraph Test["Test environments"]
        Iso[("pbdigit274_isolated_test<br/>(approved for 274 / 286 only)")]:::known
        MemDb[("Membership isolated DB (P-DB)<br/>NOT PROVISIONED: OQ-09")]:::open
        Shared[("nrna_test (shared; forbidden for Membership evidence)")]:::known
    end
    App --> PGP
    App --> CacheP
    CronP --> App
```

Recorded facts only: PHP-FPM reload and Laravel 12 on the production server (ADR note); the isolated test database name (CONTEXT-publicdigit). Everything else is labelled UNKNOWN.

---

## 5. Bounded contexts and relationships

```mermaid
flowchart LR
    classDef ctx fill:#ede9fe,stroke:#5b21b6
    classDef legacy fill:#f5f5f4,stroke:#57534e
    classDef proposed fill:#ffffff,stroke:#5b21b6,stroke-dasharray: 5 5
    classDef open fill:#fee2e2,stroke:#b91c1c

    Identity["Identity<br/>(users, persons)"]:::ctx
    Org["Organisation<br/>(affiliation, roles: user_organisation_roles)"]:::ctx
    Mem["Membership<br/>(ledger: Member aggregate)"]:::ctx
    MemL["Legacy Membership<br/>(members table, fees, applications)"]:::legacy
    Gov["Governance<br/>(authority; committees split across<br/>Contexts/Committee, Governance, Membership/Domain/Committee)"]:::open
    Elec["Election<br/>(entitlement, voting)"]:::ctx
    Fin["Finance<br/>(fees, settlement)"]:::ctx
    Audit["Audit"]:::ctx
    Geo["Geography"]:::ctx

    Identity -->|"PersonRef (conformist)"| Mem
    Org -->|"roles via OrganisationRoleReader (ACL)"| Mem
    Gov -->|"AuthorityCheckPort (customer-supplier; interim adapter)"| Mem
    Mem -.->|"PROPOSED: IsMember, Suspended, events via shared outbox"| Elec
    Mem -.->|"PROPOSED: period events"| Fin
    Fin -.->|"PROPOSED later: FeePaid / standing evidence (DS-5)"| Mem
    Mem -.->|"PROPOSED: fact stream (Slice 12)"| Audit
    MemL -->|"current source of truth until cutover (D-6 / R10)"| Elec
    MemL --> Geo
```

Relationships marked PROPOSED have no implementation (VERIFIED: no event classes, no query port). Requirements: BR-15, BR-16, BR-17; strategic gaps SG-1…SG-4.

---

## 6. Domain model (Membership ledger, as implemented)

```mermaid
classDiagram
    class Member {
        <<aggregate root>>
        MemberId id
        PersonRef holder
        TenantId organisation
        RecordedFact[] log
        decideAdmit(id, effectiveAt, decisionDate, initialTermUntil, provenance, decidedAt) Decision
        decideEnd(id, period, effectiveAt, decisionDate, kind, provenance, decidedAt) Decision
        decideAnnul(id, target, provenance, decidedAt) Decision
        decideVoid(id, target, provenance, decidedAt) Decision
        fromLog(id, holder, organisation, log) Member
        knownAt(tau) ResolvedMember
        resolve() ResolvedMember
    }
    class RecordedFact {
        int seq
        Instant recordedAt
        Fact fact
    }
    class Fact {
        <<interface>>
        FactId id()
        Instant decidedAt()
        Provenance provenance()
    }
    class EffectiveDatedFact {
        <<interface>>
        TimePoint effectiveAt()
    }
    class Admit
    class End {
        PeriodId period
        EndKind kind
    }
    class Annul {
        FactId target
    }
    class VoidFact {
        FactId target
    }
    class Provenance {
        ProvenanceKind kind
        declarationRef
        AuthorityEvidence evidence
    }
    class AuthorityEvidence {
        actor
        capacity
        ruleRef
        policyVersion
        result
        checkedAt
        competenceVerified
    }
    class EndKind {
        <<enumeration>>
        resignation
        exclusion
        death
        legacy
    }
    class Decision {
        accepted or refused
        RefusalReason reason
        int basedOnVersion
    }
    class Resolution {
        <<pure>>
        isLive(facts, id)
    }
    class InvariantSet {
        <<G star>>
        INV-EP
        INV-9
        INV-COV
    }
    Member "1" *-- "many" RecordedFact
    RecordedFact --> Fact
    Fact <|.. Admit
    Fact <|.. End
    Fact <|.. Annul
    Fact <|.. VoidFact
    EffectiveDatedFact <|.. Admit
    EffectiveDatedFact <|.. End
    Fact --> Provenance
    Provenance --> AuthorityEvidence
    End --> EndKind
    Member ..> Resolution
    Member ..> InvariantSet
    Member ..> Decision
```

Invariants (where enforced): D-1 basis, closed EndKind, legacy ⇔ attested, `effectiveAt ≤ decisionDate`, finite dates, reversal-target rules, INV-9 / INV-EP / INV-COV → **at decide time** (`Member.php:90-192`). On load (`Member.php:25-40`): seq order and non-decreasing `recordedAt` only (V-1). Not shown because absent: Renew, ChangeType, Suspend, Restore, Correct.

---

## 7. Command → decision → append flow (Admit / End, as implemented)

```mermaid
flowchart TB
    classDef proposed fill:#ffffff,stroke:#0369a1,stroke-dasharray: 5 5
    classDef open fill:#fee2e2,stroke:#b91c1c

    Cmd["AdmitMember / EndMembership command<br/>(tenant, actor, dates, provenance, idempotency key)"]
    Env["Trusted command envelope from authenticated context<br/>(V-9; OQ-04 boundary date check)"]:::proposed
    Auth{"AuthorityCheckPort"}
    Idem{"appliedCommand(key)?"}
    Load["load / findByHolder / create (1a port)"]
    Decide{"Member.decide*"}
    Append{"append(expected version)"}
    Ok["Appended (fact + command row in one transaction)"]
    RefA["ApplicationRefusal: NotAuthorised / MemberNotFound /<br/>IdempotencyKeyConflict / ConcurrentModification / ValidTimeNotStorable"]
    RefD["Domain refusal (RefusalReason), nothing appended"]
    Events["Event publication (OQ-08)"]:::open

    Env -.-> Cmd
    Cmd --> Auth
    Auth -->|denied| RefA
    Auth -->|granted| Idem
    Idem -->|same key, same fingerprint| Ok
    Idem -->|same key, other fingerprint| RefA
    Idem -->|new| Load
    Load --> Decide
    Decide -->|refused| RefD
    Decide -->|accepted| Append
    Append -->|version conflict| RefA
    Append -->|success| Ok
    Ok -.-> Events
```

Note: the order (authority before load) is `DecideAndAppend`'s; the exact idempotency and refusal order are pinned by the 1a contract and its tests (A1). End is always denied by the interim adapter until GOV-END.

---

## 8. Correction flow: command handling separated from event publication (P-01)

```mermaid
flowchart LR
    classDef exists fill:#dcfce7,stroke:#166534
    classDef proposed fill:#ffffff,stroke:#0369a1,stroke-dasharray: 5 5
    classDef open fill:#fee2e2,stroke:#b91c1c

    K1["COR-1 kernel: decideAnnul / decideVoid<br/>(EXISTS; G3 pending)"]:::exists
    K2["COR-2 kernel: Correct + consequences<br/>(OQ-02, OQ-03)"]:::proposed
    P4["COR-4 persistence: schema + encode/decode +<br/>load validation + tie rule<br/>(ACC-260, OQ-01, OQ-05, OQ-06, DV-1)"]:::proposed
    C3A["COR-3a commands: Annul / Void / Correct handlers<br/>authority evidence incl. 'reason'<br/>(Package G: CQ-3, CQ-9; G-AUTH-1)"]:::proposed
    PUB["PUB: shared outbox reuse<br/>(OQ-08, first consumer slice)"]:::open
    C3B["COR-3b emission: FactAnnulled, FactVoided,<br/>FactCorrected, TrimRevived"]:::proposed
    Gate["ARB-MEM-01: verified correction capability<br/>before live writes and cutover"]:::open

    K1 --> K2
    K1 --> P4
    K2 -.->|"Correct shapes, if not split"| P4
    P4 -->|"accepted before wiring (V-4)"| C3A
    PUB --> C3B
    C3A --> C3B
    C3A --> Gate
    P4 --> Gate
```

No edge runs from publication back into COR-3a: commands are complete and testable without emission, which removes the 2219 plan's cycle.

---

## 9. Corrected implementation dependency graph with decision gates

```mermaid
flowchart TB
    classDef done fill:#dcfce7,stroke:#166534
    classDef decision fill:#fef9c3,stroke:#a16207
    classDef work fill:#e0f2fe,stroke:#0369a1
    classDef blocked fill:#fee2e2,stroke:#b91c1c

    A1["A1 database-free evidence<br/>(DONE f54037f96)"]:::done
    A2["A2 PR source preserved; DEC-G6"]:::decision
    PRR["PR review of Slice 2a"]:::decision
    ACC["ACC-258 / 259 / 268 / 271<br/>Human PO / ARB"]:::decision
    K1["MEM-K1 Annul own provenance (V-2)"]:::work
    R["MEM-R scanner hardening (V-3, V-10)"]:::work
    CUT0["MEM-CUT0 writer inventory fitness test"]:::work
    OQ0203["OQ-02 BD-REV-ER, OQ-03 D-COR-3 (PO)"]:::decision
    COR2["MEM-COR2 Correct kernel"]:::work
    OQ09["OQ-09 DEC-SEC-DB (ARB, PO, Infra)"]:::decision
    PDB["MEM-PDB provision + identity proof"]:::blocked
    PG2["MEM-PG2 Part 2 PostgreSQL suite"]:::blocked
    F264["264 F-5, F-4b, F-2"]:::blocked
    OQ10["OQ-10 security conditions for ACC-260; DV-1"]:::decision
    ACC260["ACC-260 Slice 1b"]:::decision
    G["Package G: GOV-END, CQ-3 + reason,<br/>CQ-9, DS-4, LQ-1, D-3-L, SEC-R3, SEC-R5"]:::decision
    OQ07["OQ-07 end_kind representation"]:::decision
    B2["MEM-2B bindings, role reader, End authority"]:::blocked
    OQ01["OQ-01 production schema status (Ops)"]:::decision
    OQ0506["OQ-05 ties, OQ-06 column"]:::decision
    COR4["MEM-COR4 correction persistence"]:::blocked
    COR3A["MEM-COR3A correction commands"]:::blocked
    OQ08["OQ-08 PUB"]:::decision
    PUB["MEM-PUB publication"]:::blocked
    COR3B["MEM-COR3B emission"]:::blocked
    CHAR["MEM-SEC-CHAR characterisation (ARB-SEC-01)"]:::blocked
    FIX["MEM-SEC-FIX-S1..S5 (separate authorisation)"]:::blocked
    OQ04["OQ-04 boundary date rule; D-6 / R10;<br/>BD-CONSENT, BD-REC0"]:::decision
    HTTP["MEM-HTTP Slice 14"]:::blocked
    CUT["Migration / cutover: CUT-0..CUT-10,<br/>ARB-MEM-01, DV-1, PBDIGIT-265"]:::blocked

    A1 --> ACC
    A2 --> ACC
    PRR --> ACC
    ACC --> K1
    ACC --> COR2
    OQ0203 --> COR2
    OQ09 --> PDB --> PG2 --> F264
    PG2 --> ACC260
    F264 --> ACC260
    OQ10 --> ACC260
    ACC260 --> B2
    G --> B2
    OQ07 --> B2
    ACC260 --> COR4
    OQ01 --> COR4
    OQ0506 --> COR4
    ACC --> COR4
    COR4 --> COR3A
    G --> COR3A
    OQ08 --> PUB --> COR3B
    COR3A --> COR3B
    OQ09 --> CHAR --> FIX
    G --> FIX
    FIX --> HTTP
    B2 --> HTTP
    OQ04 --> HTTP
    COR3A --> CUT
    COR4 --> CUT
    HTTP --> CUT
    CUT0 --> CUT
```

Independent of every open decision (authorisation only): A2, PR review, MEM-R, MEM-CUT0, A1b. Sources per edge: verification rev 1, §7.

---

## 10. Sequence diagrams

### 10.1 Admit: success (as implemented, application level)

```mermaid
sequenceDiagram
    participant H as AdmitMemberHandler
    participant D as DecideAndAppend
    participant A as AuthorityCheckPort
    participant R as MemberRepositoryPort
    participant M as Member
    H->>D: run(command, key, fingerprint)
    D->>A: check(tenant, actor, Admit)
    A-->>D: granted with AuthorityEvidence
    D->>R: appliedCommand(tenant, key)
    R-->>D: none
    H->>R: findByHolder or create (identity row, version 0)
    D->>M: decideAdmit(... decisionDate ...)
    M-->>D: Decision accepted (basedOnVersion)
    D->>R: append(tenant, id, decision, key, fingerprint)
    R-->>D: Appended
```

### 10.2 Admit: domain refusal

```mermaid
sequenceDiagram
    participant D as DecideAndAppend
    participant M as Member
    participant R as MemberRepositoryPort
    D->>M: decideAdmit(effectiveAt after decisionDate)
    M-->>D: refused EffectiveDateAfterDecisionDate
    Note over D,R: nothing appended, refusal not remembered (IDEM-4)
    Note over R: an identity row created by create() remains (approved 1a port, see OQ CR-01)
```

### 10.3 Append: concurrency conflict

```mermaid
sequenceDiagram
    participant D as DecideAndAppend
    participant R as PostgresMemberRepository
    D->>R: append(expected version v)
    R->>R: UPDATE version where version = v (no row)
    R-->>D: ConcurrentModification
    Note over D: caller reloads, re-decides, re-runs G star (ratified section 6)
```

### 10.4 Failure path today: an Annul reaching the adapter (latent, V-4)

```mermaid
sequenceDiagram
    participant X as Future COR-3a handler (not built)
    participant R as PostgresMemberRepository
    X->>R: append(accepted Annul)
    R->>R: version UPDATE
    R->>R: encode(fact) calls effectiveAt on Fact
    R-->>X: raw Error (undefined method), transaction rolled back (INFERRED)
    Note over X,R: MEM-COR4 must replace this with a defined outcome before COR-3a is wired
```

---

## 11. TDD implementation flow with verification gates

```mermaid
stateDiagram-v2
    [*] --> Ready
    Ready: Definition of Ready - story ID, approved plan, decisions recorded, - authorisation, RED boundary named
    Ready --> Red: authorised
    Red: RED - failing test recorded (assertion failure, not error where possible)
    Red --> Green: failure output stored
    Green: GREEN - minimal change, test passes
    Green --> Refactor
    Refactor: REFACTOR - behaviour-preserving, suite green
    Refactor --> Red: next criterion
    Refactor --> Verify: all criteria done
    Verify: VERIFY - scoped regression on a committed tree, - C-1 differential 15,530 decisions, G6 hash, - architecture fitness, PG suite on isolated DB where in scope
    Verify --> Evidence
    Evidence: EVIDENCE - command, commit, versions, exit codes, SHA-256, - developer guide, deviations disclosed
    Evidence --> Review
    Review: REVIEW - Session 1 conformance, Principal Reviewer
    Review --> Accept: recommendation
    Accept: ACCEPT - Human PO / ARB decision record
    Accept --> [*]
    Verify --> Stop: failure or out-of-scope change
    Stop: STOP and report - no silent fix, no scope change
    Stop --> [*]
```
