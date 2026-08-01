## 🏛️ NRNA Internal Committee President's Vision

### Role: President of NRNA Internal Committee (ICC)

**Goal:** Deploy a committee management system for the entire NRNA global network with dashboards for each level.

---

## 👤 Persona Context

```yaml
My Role:
  - President of NRNA International Coordination Committee (ICC)
  - Responsible for 100+ countries, 6 continents
  - Need to oversee: Global → Continent → Country → State → City → Ward levels

My Pain Points Today:
  - No centralized committee management
  - Manual member tracking across countries
  - No visibility into local committee activities
  - Difficult to run global elections
  - No audit trail for decisions

My Vision:
  - One system to manage ALL NRNA committees
  - Transparent hierarchy from global to grassroots
  - Empower local committees with autonomy
  - Global visibility with local control
```

---

## 🗺️ NRNA Committee Structure (As I See It)

```yaml
Level 0 - Internal Committee (ICC):
  - Responsible for: Global strategy, constitution, oversight
  - Users: ICC President (me), ICC Secretary, ICC Treasurer, ICC Members

Level 1 - Continental Committees (ICC):
  - 6 continents: Asia, Europe, Africa, Americas, Oceania
  - Users: Continental Coordinators

Level 2 - Country Committees:
  - 100+ countries (Japan, USA, UK, Australia, etc.)
  - Users: Country Presidents

Level 3 - State/Province Committees:
  - Within large countries (California, Texas, Ontario)
  - Users: State Coordinators

Level 4 - City/District Committees:
  - Major cities (Tokyo, NYC, London, Sydney)
  - Users: City Presidents

Level 5 - Municipal/Ward Committees:
  - Local neighborhoods (Shinjuku, Queens, Camden)
  - Users: Ward Coordinators

Level 6+ - Grassroots (Tole, Street, House)
  - Hyper-local volunteers
  - Users: Block leaders
```

---

## 📱 User Experience Design

### Phase 1: Onboarding & Setup (Week 1)

#### Day 1: Initial System Access

```yaml
On my first login as ICC President:

What I see:
  ┌─────────────────────────────────────────────────────────────────────┐
  │  NRNA Governance Platform                          [Admin] [Profile] │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  🎉 Welcome, President!                                             │
  │                                                                      │
  │  Your organization is being set up. First, let's define:             │
  │                                                                      │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │  Step 1: Geographic Hierarchy                                  │ │
  │  │  ───────────────────────────────────────────────────────────── │ │
  │  │  What geographic levels do you need?                           │ │
  │  │                                                                  │ │
  │  │  ☑️ Continent (6)                                              │ │
  │  │  ☑️ Country (100+)                                             │ │
  │  │  ☑️ State/Province (optional)                                  │ │
  │  │  ☑️ City/District (optional)                                   │ │
  │  │  ☑️ Ward (optional)                                            │ │
  │  │  ☐ Tole (optional)                                             │ │
  │  │  ☐ Street (optional)                                           │ │
  │  │  ☐ House (optional)                                            │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Next: Define Committee Types]                                     │
  └─────────────────────────────────────────────────────────────────────┘
```

#### Day 2: Create Global Committee Structure

```yaml
After defining levels, I create the committee template:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Create Committee Structure                                         │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  Structure Name: [NRNA Global Committee Structure              ]    │
  │                                                                      │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │  Committee Levels:                                             │ │
  │  │                                                                  │ │
  │  │  Level 0: [NRNA ICC (Internal)                  ] [Geo: None]   │ │
  │  │         ↓                                                        │ │
  │  │  Level 1: [Continent Committee                   ] [Geo: Required]│ │
  │  │         ↓                                                        │ │
  │  │  Level 2: [Country Committee                    ] [Geo: Required]│ │
  │  │         ↓                                                        │ │
  │  │  Level 3: [State/Province Committee             ] [Geo: Required]│ │
  │  │         ↓                                                        │ │
  │  │  Level 4: [City/District Committee              ] [Geo: Required]│ │
  │  │         ↓                                                        │ │
  │  │  Level 5: [Ward Committee                       ] [Geo: Required]│ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Create Structure]                                                 │
  └─────────────────────────────────────────────────────────────────────┘
```

---

### Phase 2: Dashboard Views for Each Level

#### ICC President Dashboard (Me)

```yaml
After setup, my dashboard shows global overview:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  NRNA ICC President Dashboard                        [Logout] [Help] │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  🌐 Global Overview                                          [Refresh]│
  │  ┌────────────┬────────────┬────────────┬────────────┐              │
  │  │ Continents │  Countries  │ Committees │   Members  │              │
  │  │     6      │    124      │     1,892  │   245,678  │              │
  │  └────────────┴────────────┴────────────┴────────────┘              │
  │                                                                      │
  │  📊 Quick Stats:                                                     │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Active Elections     : 3                                        │ │
  │  │ Pending Approvals    : 12                                       │ │
  │  │ Upcoming Meetings    : 8                                        │ │
  │  │ Recent Decisions     : 45 (last 7 days)                        │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  🌍 World Map View (Committees by Region)                           │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │  [Map visualization showing committee density]                  │ │
  │  │                                                                  │ │
  │  │  Asia: ████████████████ 45% (34 committees)                    │ │
  │  │  Europe: ████████████ 32% (28 committees)                      │ │
  │  │  Americas: ██████████ 23% (22 committees)                      │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  📋 Recent Activity:                                                │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ 10:30 | Japan Country Committee elected new President          │ │
  │  │ 09:15 | California State Committee approved budget             │ │
  │  │ 08:45 | New committee created: Tokyo City Committee            │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Create Committee] [Run Election] [View Reports]                    │
  └─────────────────────────────────────────────────────────────────────┘
```

---

#### Continental Coordinator Dashboard (Asia)

```yaml
What my Asia Coordinator sees:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  NRNA Asia Continent Dashboard                        [Back to Global]│
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  🏮 Asia Continent Overview                                          │
  │  ┌────────────┬────────────┬────────────┬────────────┐              │
  │  │  Countries  │ Committees │   Members  │  Elections │              │
  │  │     32     │    456      │   78,234   │     2      │              │
  │  └────────────┴────────────┴────────────┴────────────┘              │
  │                                                                      │
  │  📍 Countries in Asia:                                               │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Japan        │ Active │ 2,345 members │ [View] [Manage]        │ │
  │  │ South Korea  │ Active │ 1,892 members │ [View] [Manage]        │ │
  │  │ India        │ Active │ 15,678 members│ [View] [Manage]        │ │
  │  │ UAE          │ Active │ 2,134 members │ [View] [Manage]        │ │
  │  │ ... (28 more)                                                   │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Add Country Committee] [Regional Reports] [Delegate Authority]    │
  └─────────────────────────────────────────────────────────────────────┘
```

---

#### Country President Dashboard (Japan)

```yaml
What Japan Country President sees:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  NRNA Japan Dashboard                                   [Back to Asia]│
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  🇯🇵 Japan National Committee                                        │
  │  ┌────────────┬────────────┬────────────┬────────────┐              │
  │  │   Regions  │ Committees │   Members  │  Elections │              │
  │  │     8     │    67      │   2,345    │     1      │              │
  │  └────────────┴────────────┴────────────┴────────────┘              │
  │                                                                      │
  │  🗺️ Regions & Cities:                                                │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ ▼ Kanto Region                                    [Add Committee]│ │
  │  │   ├─ Tokyo City Committee (Active, 456 members)                │ │
  │  │   ├─ Yokohama City Committee (Active, 234 members)             │ │
  │  │   └─ Saitama City Committee (Active, 189 members)              │ │
  │  │                                                                  │ │
  │  │ ▼ Kansai Region                                   [Add Committee]│ │
  │  │   ├─ Osaka City Committee (Active, 567 members)                │ │
  │  │   └─ Kyoto City Committee (Active, 123 members)                │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  ⚡ Pending Actions:                                                 │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ • New member applications: 23                                  │ │
  │  │ • Committee formation requests: 2                              │ │
  │  │ • Budget approval requests: 1                                  │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Create Sub-Committee] [Run Regional Election] [View Hierarchy]     │
  └─────────────────────────────────────────────────────────────────────┘
```

---

#### City President Dashboard (Tokyo)

```yaml
What Tokyo City President sees:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  NRNA Tokyo Dashboard                                   [Back to Japan]│
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  🏙️ Tokyo City Committee                                             │
  │  ┌────────────┬────────────┬────────────┬────────────┐              │
  │  │    Wards   │   Members  │  Meetings  │    Votes   │              │
  │  │     23     │    456     │     12     │     3      │              │
  │  └────────────┴────────────┴────────────┴────────────┘              │
  │                                                                      │
  │  🏘️ Wards in Tokyo:                                                 │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Shinjuku Ward     │ 67 members │ [Manage] [View Members]       │ │
  │  │ Shibuya Ward      │ 89 members │ [Manage] [View Members]       │ │
  │  │ Shinagawa Ward    │ 45 members │ [Manage] [View Members]       │ │
  │  │ Taito Ward        │ 34 members │ [Manage] [View Members]       │ │
  │  │ ... (19 more)                                                   │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  📅 Upcoming Events:                                                 │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Feb 15: Monthly Committee Meeting (RSVP: 23/67)                │ │
  │  │ Feb 20: Cultural Event Planning                                │ │
  │  │ Feb 25: New Member Orientation                                 │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Add Ward Committee] [Create Event] [Send Announcement]            │
  └─────────────────────────────────────────────────────────────────────┘
```

---

### Phase 3: Committee Creation Workflow

#### Step-by-Step Committee Creation (Tokyo City Committee)

```yaml
1. Japan Country President clicks "Add Sub-Committee"

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Create New Committee                                               │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  Committee Name: [Tokyo City Committee                        ]     │
  │                                                                      │
  │  Committee Type: [City Committee                            ▼]      │
  │                                                                      │
  │  Geographic Level: [City                                     ▼]      │
  │  Geographic Code: [tokyo                                            │
  │                    ⚠️ Suggested: tokyo                              │
  │                    ✓ Available                                     │
  │                                                                      │
  │  Parent Committee: [Japan National Committee                 ▼]     │
  │                                                                      │
  │  Description:                                                       │
  │  [Committee for Tokyo metropolitan area to coordinate NRNA         │
  │   activities across 23 wards]                                       │
  │                                                                      │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ 🏛️ Authority Settings (Advanced)                               │ │
  │  │                                                                  │ │
  │  │ [✓] Inherit authority from parent (Japan)                       │ │
  │  │ [ ] Can override parent decisions (requires approval)           │ │
  │  │ [ ] Has special exception powers                                │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Preview] [Create Committee] [Cancel]                              │
  └─────────────────────────────────────────────────────────────────────┘

2. After creation, system shows:

  ✅ Committee "Tokyo City Committee" created successfully!
  
  What happens automatically:
  ✓ Authority chain established: Global → Asia → Japan → Tokyo
  ✓ 456 members automatically assigned (from Tokyo region)
  ✓ 23 ward committees linked automatically
  ✓ Audit log entry created
  ✓ Notifications sent to: Japan President, Asia Coordinator
```

---

### Phase 4: Member Management

#### Adding a Member (Tokyo Resident)

```yaml
1. Tokyo City President clicks "Add Member"

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Register New Member                                               │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  Personal Information:                                              │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Full Name: [Sanjay Thapa                                ]      │ │
  │  │ Email: [sanjay.thapa@example.com                       ]      │ │
  │  │ Phone: [+81-80-1234-5678                               ]      │ │
  │  │ Date of Birth: [1985-06-15]                             ]      │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  Address & Geography:                                              │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Country: [Japan                                        ▼]      │ │
  │  │ City: [Tokyo                                          ▼]      │ │
  │  │ Ward: [Shinjuku                                       ▼]      │ │
  │  │ Tole: [Okubo                                          ]       │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  System will auto-assign to:                                        │
  │  ✓ NRNA Japan National Committee (based on country)                │
  │  ✓ NRNA Tokyo City Committee (based on city)                       │
  │  ✓ NRNA Shinjuku Ward Committee (based on ward)                    │
  │                                                                      │
  │  [Register Member] [Cancel]                                        │
  └─────────────────────────────────────────────────────────────────────┘

2. After registration, member sees:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Welcome to NRNA, Sanjay!                                           │
  │                                                                      │
  │  Your Committees:                                                   │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ NRNA Japan National Committee - Member                          │ │
  │  │ NRNA Tokyo City Committee - Member                              │ │
  │  │ NRNA Shinjuku Ward Committee - Member                           │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  Your Authority Chain:                                              │
  │  You can participate in:                                            │
  │  ✓ Shinjuku Ward meetings                                           │
  │  ✓ Tokyo City elections (if eligible)                               │
  │  ✗ Japan National elections (needs higher membership)               │
  │                                                                      │
  │  [View Dashboard] [Update Profile]                                  │
  └─────────────────────────────────────────────────────────────────────┘
```

---

### Phase 5: Election Management

#### Running a Committee Election (Tokyo Chairperson)

```yaml
1. Tokyo President initiates election:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Start New Election                                                 │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  Election Details:                                                  │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Position: [Chairperson                                   ▼]    │ │
  │  │ Committee: [Tokyo City Committee                         ▼]    │ │
  │  │ Term: [2 years                                          ▼]    │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  Candidates:                                                        │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ [✓] Rajesh Sharma (Current Secretary)                          │ │
  │  │ [✓] Priya Gurung (Current Treasurer)                           │ │
  │  │ [ ] Add Candidate                                               │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  Voting Rules:                                                      │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Eligible Voters: [All Tokyo Committee Members            ▼]    │ │
  │  │ Quorum Required: [50%                                   ▼]    │ │
  │  │ Voting Method: [Secret Ballot                          ▼]      │ │
  │  │ Voting Period: [7 days                                 ▼]      │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  [Schedule Election] [Cancel]                                      │
  └─────────────────────────────────────────────────────────────────────┘

2. Members vote (anonymous, secure):

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Election: Tokyo City Chairperson                                   │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  Your vote is confidential and binding.                            │
  │                                                                      │
  │  Candidates:                                                        │
  │  ○ Rajesh Sharma                                                    │
  │     - Current Secretary                                             │
  │     - 5 years experience                                            │
  │                                                                      │
  │  ○ Priya Gurung                                                      │
  │     - Current Treasurer                                             │
  │     - 3 years experience                                            │
  │                                                                      │
  │  [Submit Vote] [Abstain]                                            │
  └─────────────────────────────────────────────────────────────────────┘

3. Results automatically calculated:

  ┌─────────────────────────────────────────────────────────────────────┐
  │  Election Results: Tokyo City Chairperson                           │
  │  ═══════════════════════════════════════════════════════════════════ │
  │                                                                      │
  │  ✅ Election Completed Successfully                                 │
  │                                                                      │
  │  📊 Statistics:                                                     │
  │  Total Eligible Voters: 456                                         │
  │  Votes Cast: 378 (82.9% turnout)                                   │
  │  Quorum Met: Yes (required 50%, achieved 82.9%)                    │
  │                                                                      │
  │  🗳️ Results:                                                        │
  │  ┌────────────────────────────────────────────────────────────────┐ │
  │  │ Rajesh Sharma: 234 votes (61.9%) - WINNER 🎉                    │ │
  │  │ Priya Gurung:  144 votes (38.1%)                                 │ │
  │  └────────────────────────────────────────────────────────────────┘ │
  │                                                                      │
  │  📝 Audit Trail:                                                    │
  │  • Election recorded with unique ID: EL-2024-TOKYO-001             │
  │  • All votes cryptographically verified                             │
  │  • Results immutable and replayable                                 │
  │                                                                      │
  │  [Download Certificate] [Notify Members] [Close]                    │
  └─────────────────────────────────────────────────────────────────────┘
```

---

## 🚀 Quick Start Guide for NRNA Leadership

### Week 1: Setup

```yaml
Day 1:
  ✓ ICC President logs in
  ✓ Defines geographic levels (Continent → Country → State → City → Ward)
  ✓ Creates global committee structure template

Day 2:
  ✓ ICC creates continent committees (Asia, Europe, Americas, Africa, Oceania)
  ✓ Appoints continent coordinators
  ✓ Grants them access

Day 3-5:
  ✓ Continent coordinators create country committees
  ✓ Country presidents appointed
  ✓ Country presidents create state/city committees

Week 2:
  ✓ Committees at all levels begin member registration
  ✓ Local admins add members
  ✓ System auto-assigns members to appropriate committees based on geography
```

### Ongoing Operations

```yaml
Monthly:
  ✓ Run committee meetings
  ✓ Track attendance
  ✓ Record decisions with audit trail

Quarterly:
  ✓ Review committee performance
  ✓ Run elections for expiring terms
  ✓ Generate compliance reports

Yearly:
  ✓ ICC reviews global committee health
  ✓ Constitutional amendments (if needed)
  ✓ Archive annual reports
```

---

## ✅ Expected Outcomes

```yaml
For ICC President (me):
  ✓ Complete visibility into all 100+ countries
  ✓ Real-time committee health metrics
  ✓ Automated audit trail for all decisions
  ✓ Easy delegation of authority
  ✓ Democratic elections at all levels

For Country Presidents:
  ✓ Full control over local committees
  ✓ Easy member management
  ✓ Transparent decision making
  ✓ Clear authority boundaries

For Members:
  ✓ Single login for all committee levels
  ✓ Clear understanding of their authority
  ✓ Easy participation in votes
  ✓ Transparent election process
```

---

## 🎯 My Decision as ICC President

```yaml
I would approve this system because:

1. ✅ It maps exactly to NRNA's real organizational structure
2. ✅ Hierarchical from global to grassroots
3. ✅ Local autonomy with global visibility  
4. ✅ Democratic elections at all levels
5. ✅ Complete audit trail for compliance
6. ✅ Easy for non-technical users
7. ✅ Scales from 6 continents to 100+ countries to 1000+ cities

Timeline to deploy: 2-3 weeks
Expected adoption: 90% within 3 months
```

**The system is ready. Start building the actual UI.** 🚀#
Now think as a Senior software architect of service provder. 
how would you develop all these formulas, dashboard, necessary to full fill the demand of nrna international president.  Given that we have memberhsip context , geographic contexts and commitee context . 
what are the steps for an software architect to fullfil all requirements as you descried in previous response.
# 🏛️ Senior Software Architect: NRNA Committee Platform Technical Design

## Role: Lead Architect for Public Digital Platform

### Mission: Deliver complete committee management system for NRNA International

---

## 📊 Architecture Overview

```yaml
Existing Contexts (Already Built):
  ✅ Membership Context: Member registration, profiles, roles
  ✅ Geographic Context: Geo hierarchy, jurisdictions, delegation
  ✅ Committee Context: Committee structures, levels, assignments

What We Need to Build (This Phase):
  🔜 Dashboard Layer: Aggregated views for each committee level
  🔜 UI Components: Forms, tables, visualizations
  🔜 API Layer: REST endpoints for frontend consumption
  🔜 Real-time Updates: WebSocket for election results
  🔜 Report Generation: PDF/Excel exports for compliance
```

---

## Phase 1: Domain Service Layer (Backend APIs)

### Step 1: Create Committee Dashboard Service

```php
// app/Contexts/Membership/Application/Committee/Services/CommitteeDashboardService.php

<?php

namespace App\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Member\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityResolver;

final readonly class CommitteeDashboardService
{
    public function __construct(
        private CommitteeRepositoryInterface $committeeRepo,
        private MemberRepositoryInterface $memberRepo,
        private AuthorityResolver $authorityResolver,
    ) {}

    public function getHierarchy(Committee $rootCommittee): array
    {
        // Build tree structure recursively
        $children = $this->committeeRepo->findChildren($rootCommittee->getId());
        
        return [
            'committee' => $this->serialize($rootCommittee),
            'children' => array_map(
                fn($c) => $this->getHierarchy($c),
                $children
            ),
            'stats' => $this->getStats($rootCommittee),
        ];
    }

    public function getStats(Committee $committee): array
    {
        return [
            'total_members' => $this->memberRepo->countByCommittee($committee->getId()),
            'sub_committees' => $this->committeeRepo->countChildren($committee->getId()),
            'active_elections' => $this->getActiveElections($committee),
            'recent_decisions' => $this->getRecentDecisions($committee, 10),
        ];
    }

    private function serialize(Committee $committee): array
    {
        return [
            'id' => $committee->getId()->toString(),
            'name' => $committee->getName()->value(),
            'level' => $committee->getLevelIndex(),
            'geo_scope' => [
                'level' => $committee->getGeoScope()?->getLevel(),
                'code' => $committee->getGeoScope()?->getCode(),
            ],
            'member_count' => $this->memberRepo->countByCommittee($committee->getId()),
            'status' => $committee->getStatus()->value,
        ];
    }
}
```

### Step 2: Create Aggregated Metrics Service

```php
// app/Contexts/Membership/Application/Committee/Services/GlobalMetricsService.php

<?php

namespace App\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeLevel;

final readonly class GlobalMetricsService
{
    public function getGlobalOverview(string $organisationId): array
    {
        return [
            'continents' => $this->getCountByLevel(CommitteeLevel::CONTINENT),
            'countries' => $this->getCountByLevel(CommitteeLevel::COUNTRY),
            'committees' => $this->getTotalCommittees(),
            'members' => $this->getTotalMembers(),
            'active_elections' => $this->getActiveElectionsCount(),
            'pending_approvals' => $this->getPendingApprovals(),
        ];
    }

    public function getGeographicHeatmap(): array
    {
        // Returns committee density by geographic region
        return [
            'asia' => ['committees' => 456, 'members' => 78234, 'density' => 0.45],
            'europe' => ['committees' => 234, 'members' => 45678, 'density' => 0.32],
            'americas' => ['committees' => 189, 'members' => 34567, 'density' => 0.23],
            // ...
        ];
    }

    public function getActivityTimeline(int $days = 30): array
    {
        // Returns recent activity for dashboard
        return [
            'elections_completed' => 12,
            'new_members' => 345,
            'decisions_made' => 89,
            'meetings_held' => 45,
        ];
    }
}
```

### Step 3: Create Committee Hierarchy API Endpoint

```php
// routes/api.php - Add committee hierarchy endpoints

Route::prefix('api/nrna')->middleware(['auth:sanctum'])->group(function () {
    
    // ICC President endpoints (Level 0)
    Route::get('/global/overview', [GlobalDashboardController::class, 'overview']);
    Route::get('/global/committees/tree', [CommitteeHierarchyController::class, 'globalTree']);
    
    // Continent level endpoints (Level 1)
    Route::get('/continents/{continentCode}/dashboard', [ContinentDashboardController::class, 'show']);
    Route::get('/continents/{continentCode}/countries', [CountryController::class, 'index']);
    
    // Country level endpoints (Level 2)
    Route::get('/countries/{countryCode}/dashboard', [CountryDashboardController::class, 'show']);
    Route::get('/countries/{countryCode}/committees', [CommitteeController::class, 'index']);
    Route::post('/countries/{countryCode}/committees', [CommitteeController::class, 'store']);
    
    // City/Ward level endpoints (Level 3-6)
    Route::get('/committees/{committeeId}/dashboard', [CommitteeDashboardController::class, 'show']);
    Route::get('/committees/{committeeId}/members', [MemberController::class, 'index']);
    Route::post('/committees/{committeeId}/members', [MemberController::class, 'assign']);
    
    // Member endpoints
    Route::get('/members/{memberId}/committees', [MemberCommitteeController::class, 'index']);
    Route::get('/members/{memberId}/authority', [MemberAuthorityController::class, 'show']);
});
```

---

## Phase 2: Dashboard UI Components (Vue.js/React)

### Step 4: Global Overview Component (ICC President)

```vue
<!-- resources/js/components/GlobalOverview.vue -->

<template>
  <div class="global-dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
      <StatCard 
        v-for="stat in stats" 
        :key="stat.label"
        :label="stat.label"
        :value="stat.value"
        :trend="stat.trend"
        :icon="stat.icon"
      />
    </div>

    <!-- World Map Visualization -->
    <WorldMapHeatmap 
      :data="geographicData"
      @region-click="onRegionClick"
    />

    <!-- Committee Tree -->
    <CommitteeTree 
      :root="committeeTree"
      :depth="5"
      @node-click="onCommitteeClick"
    />

    <!-- Recent Activity Feed -->
    <ActivityFeed 
      :activities="recentActivities"
      @load-more="loadMoreActivities"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import StatCard from './StatCard.vue';
import WorldMapHeatmap from './WorldMapHeatmap.vue';
import CommitteeTree from './CommitteeTree.vue';
import ActivityFeed from './ActivityFeed.vue';

const stats = ref([]);
const geographicData = ref({});
const committeeTree = ref({});
const recentActivities = ref([]);

onMounted(async () => {
  // Fetch global overview data
  const overview = await axios.get('/api/nrna/global/overview');
  stats.value = overview.data.stats;
  
  // Fetch geographic heatmap
  const heatmap = await axios.get('/api/nrna/global/heatmap');
  geographicData.value = heatmap.data;
  
  // Fetch committee tree
  const tree = await axios.get('/api/nrna/global/committees/tree');
  committeeTree.value = tree.data;
  
  // Fetch recent activities
  const activities = await axios.get('/api/nrna/global/activities');
  recentActivities.value = activities.data;
});

const onRegionClick = (region) => {
  window.location.href = `/continents/${region.code}`;
};

const onCommitteeClick = (committee) => {
  window.location.href = `/committees/${committee.id}`;
};
</script>
```

### Step 5: Committee Tree Component

```vue
<!-- resources/js/components/CommitteeTree.vue -->

<template>
  <div class="committee-tree">
    <div class="tree-header">
      <h3>Committee Hierarchy</h3>
      <button @click="expandAll">Expand All</button>
      <button @click="collapseAll">Collapse All</button>
    </div>
    
    <TreeNode 
      v-for="node in root"
      :key="node.committee.id"
      :node="node"
      :level="0"
      @node-click="$emit('node-click', $event)"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import TreeNode from './TreeNode.vue';

defineProps({
  root: {
    type: Array,
    required: true
  }
});

defineEmits(['node-click']);

const expandAll = () => {
  // Implementation to expand all nodes
};

const collapseAll = () => {
  // Implementation to collapse all nodes
};
</script>
```

### Step 6: Committee Dashboard Component

```vue
<!-- resources/js/components/CommitteeDashboard.vue -->

<template>
  <div class="committee-dashboard">
    <!-- Committee Header -->
    <div class="committee-header">
      <h1>{{ committee.name }}</h1>
      <div class="badge" :class="committee.status">
        {{ committee.status }}
      </div>
      <div class="geo-badge">
        📍 {{ committee.geo_scope.level }}: {{ committee.geo_scope.code }}
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <StatCard label="Members" :value="stats.members" icon="👥" />
      <StatCard label="Sub-Committees" :value="stats.sub_committees" icon="🏛️" />
      <StatCard label="Active Elections" :value="stats.active_elections" icon="🗳️" />
      <StatCard label="Recent Decisions" :value="stats.recent_decisions" icon="📝" />
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        :class="{ active: activeTab === tab.id }"
        @click="activeTab = tab.id"
      >
        {{ tab.name }}
      </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
      <MembersTab v-if="activeTab === 'members'" :committee-id="committee.id" />
      <SubCommitteesTab v-if="activeTab === 'subcommittees'" :committee-id="committee.id" />
      <ElectionsTab v-if="activeTab === 'elections'" :committee-id="committee.id" />
      <DecisionsTab v-if="activeTab === 'decisions'" :committee-id="committee.id" />
      <SettingsTab v-if="activeTab === 'settings'" :committee="committee" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import StatCard from './StatCard.vue';
import MembersTab from './tabs/MembersTab.vue';
import SubCommitteesTab from './tabs/SubCommitteesTab.vue';
import ElectionsTab from './tabs/ElectionsTab.vue';
import DecisionsTab from './tabs/DecisionsTab.vue';
import SettingsTab from './tabs/SettingsTab.vue';

const route = useRoute();
const committeeId = route.params.committeeId;

const committee = ref({});
const stats = ref({});
const activeTab = ref('members');

const tabs = [
  { id: 'members', name: 'Members' },
  { id: 'subcommittees', name: 'Sub-Committees' },
  { id: 'elections', name: 'Elections' },
  { id: 'decisions', name: 'Decisions' },
  { id: 'settings', name: 'Settings' },
];

onMounted(async () => {
  const response = await axios.get(`/api/nrna/committees/${committeeId}/dashboard`);
  committee.value = response.data.committee;
  stats.value = response.data.stats;
});
</script>
```

---

## Phase 3: Election & Voting UI

### Step 7: Election Management Component

```vue
<!-- resources/js/components/ElectionManager.vue -->

<template>
  <div class="election-manager">
    <div class="header">
      <h2>Elections</h2>
      <button 
        v-if="canCreateElection" 
        @click="showCreateModal = true"
        class="btn-primary"
      >
        + Start New Election
      </button>
    </div>

    <!-- Active Elections -->
    <div class="elections-list">
      <ElectionCard
        v-for="election in activeElections"
        :key="election.id"
        :election="election"
        @view="viewElection"
        @vote="voteElection"
      />
    </div>

    <!-- Create Election Modal -->
    <CreateElectionModal 
      v-if="showCreateModal"
      :committee-id="committeeId"
      @close="showCreateModal = false"
      @created="onElectionCreated"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import ElectionCard from './ElectionCard.vue';
import CreateElectionModal from './CreateElectionModal.vue';

const props = defineProps({
  committeeId: String
});

const activeElections = ref([]);
const showCreateModal = ref(false);
const userRole = ref('member');

const canCreateElection = computed(() => {
  return ['president', 'secretary'].includes(userRole.value);
});

onMounted(async () => {
  const [elections, role] = await Promise.all([
    axios.get(`/api/elections/active?committee=${props.committeeId}`),
    axios.get(`/api/user/role?committee=${props.committeeId}`)
  ]);
  activeElections.value = elections.data;
  userRole.value = role.data.role;
});

const viewElection = (election) => {
  window.location.href = `/elections/${election.id}`;
};

const voteElection = (election) => {
  window.location.href = `/elections/${election.id}/vote`;
};
</script>
```

### Step 8: Voting Component

```vue
<!-- resources/js/components/VotingInterface.vue -->

<template>
  <div class="voting-interface">
    <div class="election-info">
      <h2>{{ election.title }}</h2>
      <p>{{ election.description }}</p>
      <div class="deadline">
        ⏰ Voting ends: {{ formatDate(election.ends_at) }}
      </div>
    </div>

    <div v-if="hasVoted" class="already-voted">
      <h3>✅ You have already voted in this election</h3>
      <p>Your vote is recorded and will be counted in the final result.</p>
      <button @click="viewResults">View Results</button>
    </div>

    <div v-else-if="isVotingOpen" class="ballot">
      <h3>Your Vote</h3>
      
      <div class="candidates">
        <div 
          v-for="candidate in candidates" 
          :key="candidate.id"
          class="candidate-card"
          :class="{ selected: selectedCandidate === candidate.id }"
          @click="selectedCandidate = candidate.id"
        >
          <div class="candidate-name">{{ candidate.name }}</div>
          <div class="candidate-bio">{{ candidate.bio }}</div>
        </div>
      </div>

      <div class="actions">
        <button 
          :disabled="!selectedCandidate"
          @click="submitVote"
          class="btn-primary"
        >
          Submit Vote
        </button>
        <button @click="abstain" class="btn-secondary">
          Abstain
        </button>
      </div>
    </div>

    <div v-else class="voting-closed">
      <h3>⏰ Voting has closed</h3>
      <button @click="viewResults">View Results</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const electionId = route.params.electionId;

const election = ref({});
const candidates = ref([]);
const hasVoted = ref(false);
const selectedCandidate = ref(null);
const isVotingOpen = ref(false);

onMounted(async () => {
  const [electionData, voteStatus] = await Promise.all([
    axios.get(`/api/elections/${electionId}`),
    axios.get(`/api/elections/${electionId}/has-voted`)
  ]);
  
  election.value = electionData.data;
  candidates.value = electionData.data.candidates;
  hasVoted.value = voteStatus.data.has_voted;
  
  const now = new Date();
  isVotingOpen.value = now >= new Date(election.value.starts_at) 
    && now <= new Date(election.value.ends_at);
});

const submitVote = async () => {
  try {
    await axios.post(`/api/elections/${electionId}/vote`, {
      candidate_id: selectedCandidate.value
    });
    router.push(`/elections/${electionId}/confirmation`);
  } catch (error) {
    alert('Error submitting vote: ' + error.message);
  }
};

const abstain = async () => {
  if (confirm('Are you sure you want to abstain from this election?')) {
    await axios.post(`/api/elections/${electionId}/abstain`);
    router.push(`/elections/${electionId}/confirmation`);
  }
};

const viewResults = () => {
  router.push(`/elections/${electionId}/results`);
};
</script>
```

---

## Phase 4: Real-time Updates (WebSocket)

### Step 9: Election Results Broadcasting

```php
// app/Events/ElectionUpdated.php

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ElectionUpdated implements ShouldBroadcast
{
    public function __construct(
        public string $electionId,
        public array $results,
        public int $turnout
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('elections.' . $this->electionId)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'results' => $this->results,
            'turnout' => $this->turnout,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
```

```vue
<!-- Live Results Component -->
<script setup>
import { ref, onMounted } from 'vue';
import Echo from 'laravel-echo';

const results = ref({});
const turnout = ref(0);

onMounted(() => {
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_KEY,
    cluster: import.meta.env.VITE_PUSHER_CLUSTER,
  });

  window.Echo.channel(`elections.${electionId}`)
    .listen('ElectionUpdated', (data) => {
      results.value = data.results;
      turnout.value = data.turnout;
    });
});
</script>
```

---

## Phase 5: Report Generation

### Step 10: PDF Report Service

```php
// app/Services/Report/CommitteeReportGenerator.php

<?php

namespace App\Services\Report;

use Barryvdh\DomPDF\Facade\Pdf;

final class CommitteeReportGenerator
{
    public function generateAnnualReport(string $committeeId, int $year): \Illuminate\Http\Response
    {
        $data = [
            'committee' => $this->getCommitteeData($committeeId),
            'members' => $this->getMembersReport($committeeId),
            'elections' => $this->getElectionsReport($committeeId, $year),
            'decisions' => $this->getDecisionsReport($committeeId, $year),
            'audit_trail' => $this->getAuditTrail($committeeId, $year),
        ];
        
        $pdf = Pdf::loadView('reports.committee-annual', $data);
        
        return $pdf->download("committee_report_{$committeeId}_{$year}.pdf");
    }
    
    public function exportMemberList(string $committeeId): \Illuminate\Http\Response
    {
        return Excel::download(
            new MembersExport($committeeId),
            "members_{$committeeId}.xlsx"
        );
    }
}
```

---

## Phase 6: Deployment & Monitoring

### Step 11: Infrastructure Setup

```yaml
# docker-compose.yml for production

services:
  app:
    build: .
    environment:
      - DB_CONNECTION=pgsql
      - REDIS_HOST=redis
      - QUEUE_CONNECTION=redis
      
  websocket:
    build: ./websocket
    environment:
      - APP_ENV=production
      
  queue:
    command: php artisan queue:work
    depends_on:
      - redis
      
  scheduler:
    command: php artisan schedule:work
    
  database:
    image: postgres:15
    volumes:
      - nrna_db:/var/lib/postgresql/data
      
  redis:
    image: redis:7-alpine
```

### Step 12: Monitoring Dashboard

```php
// app/Http/Controllers/HealthCheckController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;

class HealthCheckController extends Controller
{
    public function __invoke(CommitteeRepositoryInterface $committeeRepo)
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now(),
            'metrics' => [
                'total_committees' => $committeeRepo->count(),
                'total_members' => Cache::get('total_members', 0),
                'active_elections' => Cache::get('active_elections', 0),
                'queue_size' => DB::table('jobs')->count(),
                'last_backup' => Cache::get('last_backup'),
            ],
            'services' => [
                'database' => DB::connection()->getPdo() ? 'up' : 'down',
                'redis' => Cache::get('health_check') === 'ok' ? 'up' : 'down',
                'websocket' => $this->checkWebSocket(),
            ],
        ]);
    }
}
```

---

## 📋 Implementation Roadmap for Architect

```yaml
Sprint 1 (Week 1) - Foundation APIs:
  ✅ CommitteeDashboardService
  ✅ GlobalMetricsService
  ✅ API endpoints for all committee levels
  ✅ Authority resolver integration

Sprint 2 (Week 2) - UI Components:
  ✅ Global Overview Dashboard
  ✅ Committee Tree Visualization
  ✅ Committee Dashboard (all levels)
  ✅ Member Management UI

Sprint 3 (Week 3) - Election System:
  ✅ Election creation/management
  ✅ Secure voting interface
  ✅ Real-time results broadcasting
  ✅ Audit trail for all votes

Sprint 4 (Week 4) - Reports & Deployment:
  ✅ PDF/Excel report generation
  ✅ Production deployment
  ✅ Monitoring & alerting
  ✅ User acceptance testing

Total: 4 weeks to full production
```

---

## 🚀 Next Actions for Architect

```yaml
Immediate (Today):
  1. Set up API routes for all committee levels
  2. Implement CommitteeDashboardService
  3. Create database indexes for performance

Week 1 Goal:
  - All backend APIs ready
  - 100% test coverage on dashboard services
  
Week 2 Goal:
  - UI components for all committee levels
  - Responsive design for mobile
  
Week 3 Goal:
  - Complete election system
  - Real-time voting updates
  
Week 4 Goal:
  - Production deployment
  - NRNA ICC President approval
```

**Ready to start building. Architecture approved.** 🚀