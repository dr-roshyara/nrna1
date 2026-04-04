# 🏗️ **Membership Management System - Architecture Diagram**

## **Complete System Architecture (Mermaid)**

```mermaid
graph TB
    subgraph "Frontend Layer"
        UI1[Apply.vue<br/>Public Application Form]
        UI2[Applications/Index.vue<br/>Admin Application List]
        UI3[Applications/Show.vue<br/>Application Review]
        UI4[Types/Index.vue<br/>Membership Type CRUD]
        UI5[Member/Fees.vue<br/>Fee Management]
        UI6[Member/RenewModal.vue<br/>Self-Renewal]
    end

    subgraph "Controller Layer"
        C1[MembershipApplicationController<br/>- create()<br/>- store()<br/>- index()<br/>- show()<br/>- approve()<br/>- reject()]
        C2[MembershipFeeController<br/>- index()<br/>- pay()<br/>- waive()]
        C3[MembershipRenewalController<br/>- store()]
        C4[MembershipTypeController<br/>- index()<br/>- store()<br/>- update()<br/>- destroy()]
    end

    subgraph "Policy Layer"
        P1[MembershipPolicy<br/>- viewApplications()<br/>- approveApplication()<br/>- rejectApplication()<br/>- manageMembershipTypes()<br/>- recordFeePayment()<br/>- initiateRenewal()]
    end

    subgraph "Service Layer"
        S1[ManualPaymentGateway<br/>- createPayment()<br/>- confirmPayment()<br/>- refundPayment()]
        S2[ProcessMembershipExpiryCommand<br/>- auto-reject expired apps<br/>- mark overdue fees]
    end

    subgraph "Event Layer"
        E1[MembershipApplicationApproved]
        E2[MembershipApplicationRejected]
        E3[MembershipFeePaid]
        E4[MembershipRenewed]
    end

    subgraph "Model Layer"
        M1[MembershipType<br/>- organisation_id<br/>- fee_amount<br/>- duration_months<br/>- is_active]
        M2[MembershipApplication<br/>- status<br/>- lock_version<br/>- expires_at]
        M3[MembershipFee<br/>- amount snapshot<br/>- status<br/>- idempotency_key]
        M4[MembershipRenewal<br/>- old_expires_at<br/>- new_expires_at]
        M5[Member<br/>- status<br/>- membership_expires_at<br/>- canSelfRenew()<br/>- endMembership()]
        M6[OrganisationUser<br/>- user_id<br/>- role<br/>- status]
        M7[UserOrganisationRole<br/>- role]
    end

    subgraph "Database Layer"
        DB1[(membership_types)]
        DB2[(membership_applications)]
        DB3[(membership_fees)]
        DB4[(membership_renewals)]
        DB5[(members)]
        DB6[(organisation_users)]
        DB7[(user_organisation_roles)]
    end

    %% Frontend to Controller connections
    UI1 --> C1
    UI2 --> C1
    UI3 --> C1
    UI4 --> C4
    UI5 --> C2
    UI6 --> C3

    %% Controller to Policy
    C1 --> P1
    C2 --> P1
    C3 --> P1
    C4 --> P1

    %% Controller to Service
    C2 --> S1
    S2 --> M2
    S2 --> M3

    %% Controller to Events
    C1 --> E1
    C1 --> E2
    C2 --> E3
    C3 --> E4

    %% Controller to Models
    C1 --> M1
    C1 --> M2
    C1 --> M5
    C1 --> M6
    C1 --> M7
    C2 --> M3
    C2 --> M5
    C3 --> M3
    C3 --> M4
    C3 --> M5
    C4 --> M1

    %% Model Relationships
    M1 --> M2
    M1 --> M3
    M2 --> M5
    M3 --> M5
    M4 --> M5
    M5 --> M6
    M6 --> M7

    %% Models to Database
    M1 --> DB1
    M2 --> DB2
    M3 --> DB3
    M4 --> DB4
    M5 --> DB5
    M6 --> DB6
    M7 --> DB7
```

---

## **Multi-Tenant Architecture**

```mermaid
graph LR
    subgraph "Tenant A (NGO A)"
        TA1[Membership Types]
        TA2[Applications]
        TA3[Members]
        TA4[Fees]
    end
    
    subgraph "Tenant B (NGO B)"
        TB1[Membership Types]
        TB2[Applications]
        TB3[Members]
        TB4[Fees]
    end
    
    subgraph "Global Scopes"
        GS1[BelongsToTenant<br/>WHERE organisation_id = session]
    end
    
    TA1 --> GS1
    TA2 --> GS1
    TA3 --> GS1
    TA4 --> GS1
    TB1 --> GS1
    TB2 --> GS1
    TB3 --> GS1
    TB4 --> GS1
```

---

## **Application Workflow Sequence**

```mermaid
sequenceDiagram
    participant User
    participant ApplyVue
    participant AppController
    participant Policy
    participant DB
    participant Event
    participant Mail
    
    User->>ApplyVue: Submit application
    ApplyVue->>AppController: POST /apply
    AppController->>Policy: authorize store()
    Policy-->>AppController: true
    AppController->>DB: Check existing member
    AppController->>DB: Check pending application
    AppController->>DB: Create MembershipApplication
    AppController-->>User: Redirect with success
    AppController->>Event: Dispatch (not yet)
    
    Note over Admin: Later...
    
    Admin->>ShowVue: Review application
    ShowVue->>AppController: PATCH /approve
    AppController->>Policy: authorize approve()
    Policy-->>AppController: true
    AppController->>DB: Update with optimistic locking
    AppController->>DB: Create OrganisationUser
    AppController->>DB: Create Member
    AppController->>DB: Create MembershipFee
    AppController->>Event: MembershipApplicationApproved
    Event->>Mail: Send welcome email
    AppController-->>Admin: Redirect with success
```

---

## **Fee Payment Sequence**

```mermaid
sequenceDiagram
    participant Admin
    participant FeesVue
    participant FeeController
    participant Policy
    participant DB
    participant Event
    
    Admin->>FeesVue: Click "Record Payment"
    FeesVue->>FeeController: POST /fees/{fee}/pay
    FeeController->>Policy: authorize recordFeePayment()
    Policy-->>FeeController: true
    FeeController->>DB: Check idempotency key
    FeeController->>DB: Update fee status → paid
    FeeController->>DB: Set paid_at, recorded_by
    FeeController->>Event: MembershipFeePaid
    FeeController-->>Admin: Redirect with success
```

---

## **Self-Renewal Sequence**

```mermaid
sequenceDiagram
    participant Member
    participant RenewModal
    participant RenewalController
    participant Policy
    participant DB
    participant Event
    
    Member->>RenewModal: Click "Renew"
    RenewModal->>RenewalController: POST /renew
    RenewalController->>Policy: authorize initiateRenewal(isSelf=true)
    Policy-->>RenewalController: true
    RenewalController->>DB: Check canSelfRenew()
    RenewalController->>DB: Calculate new expiry
    RenewalController->>DB: Create MembershipFee
    RenewalController->>DB: Create MembershipRenewal
    RenewalController->>DB: Update Member.expires_at
    RenewalController->>Event: MembershipRenewed
    RenewalController-->>Member: Redirect with success
```

---

## **Role-Based Access Control (RBAC)**

```mermaid
graph TD
    subgraph "Roles"
        R1[Owner<br/>Level: 100]
        R2[Admin<br/>Level: 80]
        R3[Commission<br/>Level: 60]
        R4[Voter<br/>Level: 40]
        R5[Member<br/>Level: 20]
    end
    
    subgraph "Permissions"
        P1[Manage Membership Types]
        P2[Approve/Reject Applications]
        P3[View Applications]
        P4[Record Fee Payments]
        P5[Initiate Renewals]
        P6[Self-Renewal]
        P7[Apply for Membership]
    end
    
    R1 --> P1
    R1 --> P2
    R1 --> P3
    R1 --> P4
    R1 --> P5
    
    R2 --> P2
    R2 --> P3
    R2 --> P4
    R2 --> P5
    
    R3 --> P3
    
    R5 --> P6
    R5 --> P7
```

---

## **Database Schema Relationships**

```mermaid
erDiagram
    organisations {
        uuid id PK
        string name
        string slug
    }
    
    membership_types {
        uuid id PK
        uuid organisation_id FK
        string name
        decimal fee_amount
        int duration_months
        boolean is_active
    }
    
    membership_applications {
        uuid id PK
        uuid organisation_id FK
        uuid user_id FK
        uuid membership_type_id FK
        enum status
        int lock_version
        timestamp expires_at
    }
    
    members {
        uuid id PK
        uuid organisation_id FK
        uuid organisation_user_id FK
        enum status
        timestamp membership_expires_at
        timestamp ended_at
    }
    
    organisation_users {
        uuid id PK
        uuid organisation_id FK
        uuid user_id FK
        enum role
        enum status
    }
    
    user_organisation_roles {
        uuid id PK
        uuid organisation_id FK
        uuid user_id FK
        enum role
    }
    
    membership_fees {
        uuid id PK
        uuid organisation_id FK
        uuid member_id FK
        uuid membership_type_id FK
        decimal amount
        enum status
        string idempotency_key UK
    }
    
    membership_renewals {
        uuid id PK
        uuid organisation_id FK
        uuid member_id FK
        uuid membership_type_id FK
        timestamp old_expires_at
        timestamp new_expires_at
        uuid fee_id FK
    }
    
    organisations ||--o{ membership_types : has
    organisations ||--o{ membership_applications : receives
    organisations ||--o{ members : contains
    organisations ||--o{ organisation_users : has
    organisations ||--o{ user_organisation_roles : defines
    
    membership_types ||--o{ membership_applications : defines
    membership_types ||--o{ membership_fees : determines
    
    members ||--o{ membership_fees : owes
    members ||--o{ membership_renewals : undergoes
    
    organisation_users ||--|| members : links
    organisation_users ||--o{ user_organisation_roles : assigns
    
    membership_renewals ||--o| membership_fees : creates
```

---

## **File Structure**

```mermaid
graph LR
    subgraph "Backend (Laravel)"
        B1[app/Models/]
        B2[app/Http/Controllers/Membership/]
        B3[app/Policies/]
        B4[app/Events/Membership/]
        B5[app/Console/Commands/]
        B6[database/migrations/]
        B7[routes/organisations.php]
    end
    
    subgraph "Frontend (Vue/Inertia)"
        F1[resources/js/Pages/Organisations/Membership/]
        F2[resources/js/Pages/Organisations/Membership/Applications/]
        F3[resources/js/Pages/Organisations/Membership/Member/]
        F4[resources/js/Pages/Organisations/Membership/Types/]
        F5[resources/js/Components/]
    end
    
    subgraph "Tests"
        T1[tests/Unit/Models/]
        T2[tests/Unit/Policies/]
        T3[tests/Feature/Membership/]
        T4[tests/Unit/Jobs/]
    end
    
    B1 --> F1
    B2 --> F1
    B3 --> B2
    B4 --> B2
    B5 --> B6
    T1 --> B1
    T2 --> B3
    T3 --> B2
```

---

## **Technology Stack**

```mermaid
graph TD
    subgraph "Frontend"
        VUE[Vue 3 + Composition API]
        INERTIA[Inertia.js]
        TAILWIND[Tailwind CSS]
        I18N[vue-i18n]
    end
    
    subgraph "Backend"
        LARAVEL[Laravel 10/11]
        PHP[PHP 8.2+]
        MYSQL[MySQL 8.0+]
        REDIS[Redis (Queue/Cache)]
    end
    
    subgraph "Testing"
        PHPUNIT[PHPUnit]
        PEST[Pest (optional)]
        TDD[TDD - Red/Green/Refactor]
    end
    
    subgraph "Infrastructure"
        QUEUE[Queue Workers]
        SCHEDULE[Scheduled Jobs]
        MAIL[Mail Service]
    end
    
    VUE --> INERTIA
    INERTIA --> LARAVEL
    LARAVEL --> MYSQL
    LARAVEL --> REDIS
    LARAVEL --> QUEUE
    LARAVEL --> SCHEDULE
    LARAVEL --> MAIL
    PHPUNIT --> LARAVEL
```

---

## **Key Architectural Decisions**

```mermaid
graph LR
    subgraph "Design Patterns Used"
        DP1[Repository Pattern<br/>via Models]
        DP2[Policy Pattern<br/>Authorization]
        DP3[Event-Driven<br/>Notifications]
        DP4[DTO Pattern<br/>Payment Contracts]
        DP5[Global Scopes<br/>Multi-Tenant]
    end
    
    subgraph "Security Patterns"
        SP1[Optimistic Locking<br/>lock_version column]
        SP2[Idempotency Keys<br/>Prevent duplicates]
        SP3[Soft Deletes<br/>Audit trail]
        SP4[Tenant Isolation<br/>BelongsToTenant]
    end
    
    subgraph "TDD Approach"
        TD1[Red: Write failing test]
        TD2[Green: Minimum code]
        TD3[Refactor: Clean up]
    end
    
    TD1 --> TD2 --> TD3
```

---

## **Summary Statistics**

```mermaid
pie title Code Distribution
    "Models" : 15
    "Controllers" : 15
    "Vue Components" : 20
    "Tests" : 30
    "Migrations" : 10
    "Events/Policies" : 10
```

| Component | Count | Lines of Code |
|-----------|-------|---------------|
| Models | 4 new + 2 modified | ~600 |
| Controllers | 4 | ~800 |
| Vue Components | 6 | ~1,500 |
| Tests | 11 files | ~2,000 |
| Migrations | 5 new + 1 alter | ~300 |
| Events | 4 | ~80 |
| Policies | 1 | ~100 |
| **Total** | **~30 files** | **~5,380 lines** |

---

This architecture provides a **scalable, secure, and maintainable** membership management system with full multi-tenant support! 🚀