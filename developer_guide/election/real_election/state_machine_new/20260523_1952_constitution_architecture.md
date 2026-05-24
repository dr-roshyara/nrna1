## Current Architecture: Constitutional Governance Engine

Here's the complete architecture of your election state machine system after Phases A through C.2.8.

### High-Level Architecture

```mermaid
graph TB
    subgraph "Frontend Layer (Vue 3)"
        UI[Vue Components]
        COMPOSABLE[useElectionCapabilities]
        PHASE_PANEL[StateMachinePanel.vue]
        CONSTANTS[ElectionLifecycleStates.ts]
        ACTIONS[ElectionActions.ts]
    end

    subgraph "Application Layer"
        CONTROLLER[ElectionManagementController]
        RESOLVER[ElectionCapabilityResolver]
        CONTEXT[CapabilityContext]
        DECISION[CapabilityDecision]
    end

    subgraph "Domain Layer"
        ENGINE[ElectionLifecycleEngine]
        CONSTITUTION[ElectionConstitution]
        STATE_ENUM[ElectionLifecycleState]
        PROJECTION[ElectionLifecycleProjection]
        SNAPSHOT[ElectionLifecycleSnapshot]
    end

    subgraph "Infrastructure Layer"
        DB[(PostgreSQL)]
        CACHE[Cache]
        QUEUE[Queue]
    end

    UI --> COMPOSABLE
    UI --> PHASE_PANEL
    COMPOSABLE --> CONTROLLER
    PHASE_PANEL --> CONTROLLER
    
    CONTROLLER --> RESOLVER
    CONTROLLER --> ENGINE
    
    RESOLVER --> CONTEXT
    RESOLVER --> DECISION
    RESOLVER --> CONSTITUTION
    
    ENGINE --> STATE_ENUM
    ENGINE --> SNAPSHOT
    ENGINE --> PROJECTION
    
    ENGINE --> DB
    RESOLVER --> CACHE
```

### Lifecycle State Progression (12 States)

```mermaid
stateDiagram-v2
    [*] --> Draft: Create Election
    
    Draft --> SubmittedForApproval: Submit (if >40 voters)
    Draft --> Approved: Auto-approve (if ≤40 voters)
    
    SubmittedForApproval --> Approved: Platform Admin Approves
    SubmittedForApproval --> Rejected: Platform Admin Rejects
    
    Rejected --> Draft: Revise & Resubmit
    
    Approved --> SetupAdministration: Begin Setup
    
    SetupAdministration --> SetupNomination: Complete Administration
    
    SetupNomination --> ReadyForVoting: Complete Nomination
    
    ReadyForVoting --> VotingActive: Open Voting (when window opens)
    
    VotingActive --> Counting: Close Voting (when window ends)
    
    Counting --> ResultsPublished: Publish Results
    
    ResultsPublished --> Archived: Archive
    
    note right of VotingActive: Overlay: Suspended\n(freezes capabilities)
    note right of Counting: Suspension possible\nat any non-terminal state
```

### Constitutional Capability Resolution

```mermaid
flowchart TD
    USER[User Action Request] --> CONTROLLER
    
    subgraph CONTROLLER ["ElectionManagementController"]
        CONTEXT_BUILDER[Build CapabilityContext]
        CONTEXT_BUILDER --> RESOLVER_CALL[Call Resolver]
    end
    
    subgraph RESOLVER ["ElectionCapabilityResolver (Boring)"]
        ORDER[Order Policies by Priority]
        ORDER --> OVERLAY{OverlayPolicy}
        
        OVERLAY -->|Suspended & action != resume| SHORT_CIRCUIT[Short Circuit - DENY]
        OVERLAY -->|Not Suspended| LIFECYCLE{LifecycleBaselinePolicy}
        
        LIFECYCLE -->|Action allowed in state| GRANT[GRANT]
        LIFECYCLE -->|Action not allowed| DENY[DENY]
        
        SHORT_CIRCUIT --> TRACE[Add to Trace]
        GRANT --> TRACE
        DENY --> TRACE
    end
    
    subgraph FRONTEND ["Vue Frontend"]
        COMPOSABLE[useElectionCapabilities]
        COMPOSABLE --> CAN_DO[canDo('action')]
        COMPOSABLE --> DENIAL_REASON[denialReason('action')]
    end
    
    RESOLVER --> RESPONSE[stateMachine.capabilities]
    RESPONSE --> FRONTEND
```

### Policy Chain (Ordered by Priority)

```mermaid
graph LR
    subgraph POLICIES ["Policy Chain (Priority Order)"]
        P1[1. OverlayCapabilityPolicy]
        P2[2. LifecycleCapabilityBaselinePolicy]
        P3[3. RoleCapabilityPolicy*]
        P4[4. PreconditionsCapabilityPolicy*]
    end
    
    subgraph OUTCOMES ["Decision Outcomes"]
        SC[Short Circuit - Stop Evaluation]
        G[Grant - Allow Action]
        D[Deny - Block Action]
        A[Abstain - Continue Chain]
    end
    
    P1 -->|Suspended| SC
    P1 -->|Not Suspended| A
    P2 -->|Allowed in State| G
    P2 -->|Not Allowed| D
    P3 -->|Future| A
    P4 -->|Future| A
    
    SC --> FINAL[Final Decision]
    G --> FINAL
    D --> FINAL
    
    style P1 fill:#ffcccc
    style P2 fill:#ccffcc
    style P3 fill:#ccccff
    style P4 fill:#ccccff
    style SC fill:#ff9999
    style G fill:#99ff99
    style D fill:#ffcc99
```

### Projection Sovereignty (C.2.8)

```mermaid
flowchart LR
    subgraph BACKEND ["Backend (PHP)"]
        ENUM[ElectionLifecycleState enum<br/>12 constitutional states]
        PROJECTION_SERVICE[ElectionLifecycleProjection]
        CONTROLLER_PROJECT[Controller adds projection]
    end
    
    subgraph TRANSFER ["API Response (Inertia)"]
        RESPONSE[stateMachine = {<br/>  currentState,<br/>  completedStates,<br/>  projectionAvailable,<br/>  capabilities<br/>}]
    end
    
    subgraph FRONTEND_UI ["Frontend (Vue)"]
        PANEL[StateMachinePanel.vue]
        COMPLETED[isPhaseCompleted =<br/>completedStates.includes(state)]
        PHASE_FOR[phaseFor() projection]
    end
    
    ENUM --> PROJECTION_SERVICE
    PROJECTION_SERVICE --> CONTROLLER_PROJECT
    CONTROLLER_PROJECT --> RESPONSE
    RESPONSE --> PANEL
    PANEL --> COMPLETED
    PANEL --> PHASE_FOR
    
    style BACKEND fill:#e1f5fe
    style TRANSFER fill:#fff3e0
    style FRONTEND_UI fill:#e8f5e9
```

### Data Flow: From Fact to UI

```mermaid
sequenceDiagram
    participant User
    participant Vue as Vue Component
    participant Composable as useElectionCapabilities
    participant Controller as ElectionManagementController
    participant Resolver as ElectionCapabilityResolver
    participant Engine as ElectionLifecycleEngine
    participant Projection as ElectionLifecycleProjection
    participant DB as Database

    User->>Vue: Click "Open Voting"
    Vue->>Composable: canOpenVoting?
    Composable->>Controller: GET /elections/{id}/management
    
    Controller->>Engine: getState(election)
    Engine->>DB: Read facts (timestamps, flags)
    DB-->>Engine: Facts
    Engine-->>Controller: Derived state
    
    Controller->>Projection: completedStatesFor(state)
    Projection-->>Controller: ['draft', 'approved', ...]
    
    Controller->>Resolver: evaluate(action, state, user)
    Resolver->>Resolver: OverlayPolicy (suspension check)
    Resolver->>Resolver: LifecyclePolicy (state check)
    Resolver-->>Controller: CapabilityDecision
    
    Controller-->>Composable: stateMachine { capabilities, completedStates }
    Composable-->>Vue: canOpenVoting = true
    
    Vue->>User: Show "Open Voting" button
```

### Key Architecture Principles

```mermaid
mindmap
  root((Constitutional<br/>Governance))
    Sovereignty
      Facts derive state
      Never state column
      Engine is sole authority
    Separation
      Lifecycle != Phase
      Overlay != State
      Capability != Role
    Bounded Contexts
      Domain: Engine, Constitution
      Application: Resolver, Policies
      UI: Projection only
    Invariants
      No Carbon in resolver
      No auth() in resolver
      No DB queries in policies
    Frontend Rules
      Never infer permissions
      Use composable only
      Project from backend
```

### File Structure

```mermaid
graph TB
    subgraph PHP ["Backend (PHP)"]
        DOMAIN[Domain/Election/]
        DOMAIN_ENUM[Enum/ElectionLifecycleState.php]
        DOMAIN_CONST[Constitution/ElectionConstitution.php]
        DOMAIN_ENGINE[Services/ElectionLifecycleEngineImpl.php]
        DOMAIN_PROJECT[Projection/ElectionLifecycleProjection.php]
        
        APP[Application/Election/]
        APP_RESOLVER[Services/ElectionCapabilityResolver.php]
        APP_POLICY[Capabilities/Policy/]
        APP_CONTEXT[Capabilities/CapabilityContext.php]
        
        HTTP[Http/Controllers/Election/]
        HTTP_CTRL[ElectionManagementController.php]
    end
    
    subgraph TS ["Frontend (TypeScript/Vue)"]
        CONSTANTS[Constants/]
        TS_ACTIONS[ElectionActions.ts]
        TS_STATES[ElectionLifecycleStates.ts]
        
        COMPOSABLES[Composables/]
        TS_COMPOSABLE[useElectionCapabilities.ts]
        
        DOMAIN_TS[Domain/Election/]
        TS_PHASE[ElectionPhaseService.ts]
        
        PAGES[Pages/Election/]
        VUE_MGMT[Management.vue]
        VUE_PANEL[Partials/StateMachinePanel.vue]
    end
    
    HTTP_CTRL --> APP_RESOLVER
    HTTP_CTRL --> DOMAIN_ENGINE
    HTTP_CTRL --> DOMAIN_PROJECT
    
    APP_RESOLVER --> DOMAIN_CONST
    
    VUE_MGMT --> TS_COMPOSABLE
    VUE_PANEL --> TS_COMPOSABLE
    VUE_PANEL --> TS_PHASE
    
    TS_COMPOSABLE --> HTTP_CTRL
```

### Summary of Achievements

| Phase | Focus | Status |
|-------|-------|--------|
| **A** | Setup Split (Administration vs Nomination) | ✅ Complete |
| **B** | Suspended State (Operational Overlay) | ✅ Complete |
| **C.2.1** | Capability Foundation (Enums, DTOs, Trace) | ✅ Complete |
| **C.2.2** | Policies + Resolver | ✅ Complete |
| **C.2.3** | Controller Integration | ✅ Complete |
| **C.2.4** | Frontend Sovereignty Migration | ✅ Complete |
| **C.2.5** | Transitional Debt Elimination | ✅ Complete |
| **C.2.6** | Vocabulary Stabilization | ✅ Complete |
| **C.2.7** | Projection Sovereignty | ✅ Complete |
| **C.2.8** | ESLint + Governance Hardening | ✅ Complete |

**The architecture is now constitutionally sovereign, fully documented, and production-ready.**