## 📋 **PROJECT OVERVIEW: Public Digit Voting Platform**

### **What We're Building**

We're developing a **comprehensive, multi-tenant election management and voting platform** that enables organizations to conduct secure, anonymous, and verifiable elections entirely online.

---

## 🎯 **Core Mission**

> *"Democratize democratic processes"* - Enable any organisation to run secure, transparent elections with complete voter anonymity and ironclad audit trails.

---

## 🏗️ **System Architecture**

### **1. Multi-Tenancy Foundation**
```
┌─────────────────────────────────────────────────────┐
│                    PLATFORM                          │
│  (Manages multiple organizations)                    │
├─────────────────────────────────────────────────────┤
│                                                      │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────┐  │
│  │ organisation│    │ organisation│    │  Demo   │  │
│  │      A      │    │      B      │    │  Mode   │  │
│  │  (Live)     │    │  (Live)     │    │ (Test)  │  │
│  └─────────────┘    └─────────────┘    └─────────┘  │
│                                                      │
│  Each tenant's data is COMPLETELY ISOLATED           │
│  - Separate database records                         │
│  - organisation_id scoping                           │
│  - No cross-tenant visibility                        │
└─────────────────────────────────────────────────────┘
```

### **2. Two-Tier Deployment Model**

| Mode | Description | Use Case |
|------|-------------|----------|
| **MODE 1 (Demo)** | `organisation_id = NULL` | Customer testing, platform demos, onboarding |
| **MODE 2 (Live)** | `organisation_id = X` | Production elections for paying customers |

---

## 🔑 **Key Features**

### **✅ Complete Voting Lifecycle**

```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│  Code   │───▶│Agreement│───▶│  Vote   │───▶│Verify   │───▶│Complete │
│  Entry  │    │Accept   │    │ Select  │    │ Vote    │    │         │
└─────────┘    └─────────┘    └─────────┘    └─────────┘    └─────────┘
   Step 1         Step 2         Step 3         Step 4         Step 5
```

### **🔒 Vote Anonymity (CRITICAL)**
```php
// CRITICAL DESIGN DECISION:
votes table:    NO user_id column (voters cannot be linked to votes)
results table:  NO user_id column (selections are anonymous)
voting_code:    Hashed audit trail only (cannot be reversed)

// This ensures:
- ✓ No vote coercion possible
- ✓ Complete voter privacy
- ✓ Election integrity
```

### **🔐 Multi-Layer Security**

| Layer | Protection | Implementation |
|-------|------------|----------------|
| **Database** | Hard isolation | Foreign keys with `organisation_id` |
| **Model** | Query scoping | `BelongsToTenant` global scope |
| **Middleware** | Request filtering | `TenantContext` middleware |
| **Session** | User context | `session('current_organisation_id')` |

### **📊 Regional & National Voting**

```
NATIONAL POSTS (Visible to ALL voters)
├── President
├── Vice President
└── Secretary

REGIONAL POSTS (Filtered by voter's region)
├── State Representative - Bayern
├── State Representative - Baden
└── State Representative - NRW
```

### **📝 Flexible Candidate Selection Rules**
- Configurable via `SELECT_ALL_REQUIRED` env variable
- Option A: Must select exactly N candidates
- Option B: Can select up to N candidates (flexible)
- Per-post configuration via `required_number` field

### **🧪 Complete Demo Environment**
- Separate `demo_*` tables (demo_votes, demo_candidacies, demo_codes)
- Reset functionality for unlimited testing
- Same UI/UX as production
- Zero impact on real elections

### **📈 Comprehensive Audit Trail**

```php
storage/logs/organisation_{id}/{election_name}/{user_id}_{user_name}.log
```

Each voter has their own log file containing:
- Timestamp of every action
- IP address for each step
- Step completion times
- Candidate selections (anonymous)
- Any errors encountered

---

## 🛠️ **Technology Stack**

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Backend** | Laravel 9.x | PHP framework, business logic |
| **Frontend** | Vue 3 + Inertia.js | Reactive SPA experience |
| **Database** | MySQL/PostgreSQL | Data persistence |
| **Authentication** | Laravel Fortify | Login, registration, 2FA |
| **Permissions** | Spatie Permission | Role-based access control |
| **Multi-tenancy** | Custom implementation | Organisation isolation |
| **Testing** | PHPUnit | TDD-first approach |

---

## 🏛️ **Domain Model**

### **Core Entities**

```
┌─────────────────────────────────────────────────────────────────┐
│                         ELECTION                                  │
│  - id, name, type (real/demo), organisation_id                  │
│  - start_date, end_date, is_active                              │
└─────────────────────────────────────────────────────────────────┘
                              │
            ┌─────────────────┼─────────────────┐
            ▼                 ▼                 ▼
┌───────────────────┐ ┌───────────────────┐ ┌───────────────────┐
│       POST        │ │       CODE        │ │     VOTER SLUG    │
│ - post_id         │ │ - code1, code2    │ │ - slug            │
│ - name            │ │ - is_usable       │ │ - user_id         │
│ - is_national_wide│ │ - can_vote_now    │ │ - current_step    │
│ - required_number │ │ - has_voted       │ │ - step_meta       │
│ - region/state    │ │ - voting_time_min │ └───────────────────┘
└───────────────────┘ └───────────────────┘          │
         │                                            │
         ▼                                            ▼
┌───────────────────┐                      ┌───────────────────┐
│    CANDIDACY      │                      │  VOTER SLUG STEP  │
│ - candidacy_id    │                      │ - step (1-5)      │
│ - user_id         │                      │ - ip_address      │
│ - post_id         │                      │ - started_at      │
│ - position_order  │                      │ - completed_at    │
└───────────────────┘                      └───────────────────┘
         │                                            │
         └──────────────────┬─────────────────────────┘
                           ▼
              ┌───────────────────────┐
              │         VOTE          │
              │ - voting_code (hashed) │
              │ - election_id         │
              │ - NO user_id          │ ← CRITICAL!
              │ - candidate_01..60    │
              │ - no_vote_option      │
              └───────────────────────┘
                           │
                           ▼
              ┌───────────────────────┐
              │        RESULT         │
              │ - vote_id             │
              │ - candidate_id         │
              │ - post_id              │
              └───────────────────────┘
```

---

## 🎮 **Two-Use Code System**

```
┌─────────────────────────────────────────────────────────────────┐
│                    TWO-USE VOTING CODE                           │
│                         (Simple Mode)                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  CODE GENERATED:  is_code1_usable = 1                            │
│                  code1_used_at = NULL                            │
│                  code2_used_at = NULL                            │
│                          │                                        │
│                          ▼                                        │
│  STEP 1: /code/create   │  FIRST USE                             │
│  Enter Code1            │  is_code1_usable = 0                   │
│                         │  code1_used_at = NOW()                 │
│                          │                                        │
│                          ▼                                        │
│  STEP 3: /vote/submit   │  SECOND USE                            │
│  Submit Vote            │  code2_used_at = NOW()                 │
│                         │  Code exhausted - cannot reuse         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🌍 **Regional Distribution**

### **Voter Assignment**
```php
// Users have region field
$user->region = 'Bayern' // or 'Baden', 'Hamburg', etc.

// Regional posts are filtered by user's region
$regional_posts = DemoPost::where('election_id', $election->id)
    ->where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();
```

### **Candidate Assignment**
```php
// Candidates don't know their region - they only know their post
// The POST determines the region context
$candidate = DemoCandidacy::where('post_id', $post->id)->get();
// If post is regional, all its candidates are for that region
```

---

## 🔐 **Security Philosophy**

### **Defense in Depth**

| Level | Protection | Bypass Risk |
|-------|------------|-------------|
| **Database** | Foreign key constraints with org_id | Impossible |
| **Model** | Global query scopes | Requires `withoutGlobalScopes()` |
| **Controller** | Manual validation | Requires code change |
| **Middleware** | Route filtering | Requires route change |

### **Vote Anonymity Guarantees**

```sql
-- This query should ALWAYS return 0
SELECT COUNT(*) 
FROM votes v
JOIN users u ON v.voting_code = u.something  -- NO LINKAGE POSSIBLE!
-- Impossible because votes table has NO user_id and NO user reference
```

---

## 📊 **Current Project Status**

| Component | Status | Completion |
|-----------|--------|------------|
| Multi-tenancy Foundation | ✅ Complete | 100% |
| Demo Mode (NULL org) | ✅ Complete | 100% |
| Live Mode (with org) | ✅ Complete | 100% |
| Voting Workflow (5 steps) | ✅ Complete | 100% |
| Regional Filtering | ✅ Complete | 100% |
| National Posts | ✅ Complete | 100% |
| Two-Code System | ✅ Complete | 100% |
| Vote Anonymity | ✅ Complete | 100% |
| Audit Logging | ✅ Complete | 100% |
| CSRF Protection | ⚠️ Needs Standardization | 70% |
| Admin Dashboard | 🚧 In Progress | 40% |
| Results Publication | 🚧 In Progress | 30% |
| Mobile Responsiveness | 🚧 In Progress | 50% |

---

## 🚀 **What Makes This Special**

### **1. True Anonymity**
Unlike many voting systems that claim anonymity but store user IDs, our votes table has **NO user_id column**. Votes are completely anonymous while still being verifiable.

### **2. Production-Ready Multi-tenancy**
Each organisation's data is completely isolated at every layer - database, model, and application.

### **3. Testable Demo Environment**
Organizations can test the entire voting flow in demo mode before going live, with the exact same UI/UX.

### **4. Regional Intelligence**
Supports complex electoral geographies - national posts for all voters, regional posts filtered by voter location.

### **5. Configurable Rules**
Every aspect is configurable via environment variables:
- `SELECT_ALL_REQUIRED` - Exact match or up-to selection
- `TWO_CODES_SYSTEM` - Single or dual-code verification
- `VOTING_TIME_MINUTES` - Voting window duration

### **6. Ironclad Audit Trail**
Every voter action is logged per-person, per-election with IP, timestamp, and step completion times - invaluable for dispute resolution.

### **7. TDD-First Development**
All critical paths are tested first, ensuring reliability for actual elections.

---

## 🎓 **Use Cases**

### **Corporate Elections**
- Board of directors elections
- Shareholder voting
- Employee representation votes

### **Non-Profit Organizations**
- Executive committee elections
- Member voting on bylaws
- Chapter representative selection

### **Political Organizations**
- Primary elections
- Delegate selection
- Internal party votes

### **Educational Institutions**
- Student government elections
- Faculty senate votes
- Alumni association voting

### **Professional Associations**
- Board elections
- Committee selection
- Bylaw ratification

---

## 📈 **Future Roadmap**

### **Phase 3 (Current)**
- ✅ Complete voting workflow
- ✅ Multi-tenancy with org_id
- ✅ Regional/national posts
- ⬜ Admin dashboard

### **Phase 4 (Next)**
- ⬜ Real-time results dashboard
- ⬜ Advanced analytics
- ⬜ Export functionality (CSV/PDF)
- ⬜ Email notifications

### **Phase 5 (Future)**
- ⬜ Mobile app
- ⬜ Blockchain verification
- ⬜ API for third-party integration
- ⬜ Advanced fraud detection

---

## 🏆 **Why This Matters**

In an era of increasing remote participation, the ability to conduct **secure, verifiable, and truly anonymous elections online** is critical for democratic organizations. This platform provides:

- ✅ **Accessibility** - Vote from anywhere
- ✅ **Security** - Multi-layer protection
- ✅ **Verifiability** - Complete audit trails
- ✅ **Anonymity** - No voter-vote linkage
- ✅ **Scalability** - From 10 to 100,000 voters

---

## 📝 **Project Mantra**

> *"Vote with confidence, audit with certainty, remain completely anonymous."*

---

**Built with:** Laravel, Vue.js, Inertia.js, and a commitment to democratic integrity.