# User Journey Examples

## Journey 1: New Customer Onboarding

**Persona:** Anna - Starting a new diaspora association

### Timeline

```
Day 1 - Registration:
├─ Anna visits publicdigit.com
├─ Clicks "Get Started"
├─ Registers email: anna@diaspora.de
├─ Password: (creates secure password)
└─ Email confirmed

Day 1 - First Login:
├─ Anna logs in
├─ LoginResponse checks: isFirstTimeUser() → TRUE
│   ├─ No organizations yet ✓
│   ├─ No roles assigned ✓
│   ├─ Created today ✓
│   └─ No legacy roles ✓
├─ Redirects to: /dashboard/welcome
├─ Sees Welcome Dashboard:
│   ├─ "Welcome, Anna! 👋"
│   ├─ GDPR Compliance notice
│   ├─ Primary CTA: "Create Organization"
│   ├─ Secondary: "Join Organization"
│   ├─ Use case examples
│   ├─ Social proof (50+ organizations)
│   └─ Key features (Transparency, Security, Multilingual)
└─ Clicks: "Create Organization"

Clicks "Create Organization":
├─ Redirects to: /organizations/create (TODO)
├─ Form appears:
│   ├─ Organization Name: "European Nepal Association"
│   ├─ Slug: "european-nepal-assoc"
│   ├─ Languages: DE, EN, NP selected
│   └─ Submit
├─ Backend creates:
│   ├─ Organization record
│   ├─ user_organization_roles entry (anna → admin)
│   └─ Clears Anna's role cache
└─ Anna becomes ADMIN

Day 1 - After Creation:
├─ Redirected to: /dashboard/admin
├─ Anna sees Admin Dashboard:
│   ├─ "European Nepal Association"
│   ├─ Members: 1 (herself)
│   ├─ Elections: 0
│   ├─ Options:
│   │   ├─ Create Election
│   │   ├─ Invite Members
│   │   └─ Manage Settings
│   └─ Audit trail: (empty)
└─ Anna has complete system access

Day 3 - Adding Members:
├─ Anna invites board members
│   ├─ Sends invites via email
│   └─ Members register
├─ Anna manually assigns roles:
│   ├─ Rahul → COMMISSION (election monitor)
│   ├─ Priya → VOTER (participant)
│   └─ Database updated:
│       ├─ user_organization_roles:
│       │   ├─ rahul → commission
│       │   └─ priya → voter
│       └─ Role caches cleared for Rahul & Priya
└─ Organization is ready

Day 5 - First Election:
├─ Anna creates election
│   ├─ Title: "Board Elections 2026"
│   ├─ Members eligible: Priya (voter)
│   ├─ Monitors: Rahul (commission)
│   └─ Starts voting
└─ Election created in database
```

### Database State After Day 5

```sql
-- Organizations
INSERT INTO organizations VALUES
(1, 'European Nepal Association', 'european-nepal-assoc', null, '["de","en","np"]', ...);

-- Users
INSERT INTO users VALUES
(1, 'Anna', 'anna@diaspora.de', ...),
(2, 'Rahul', 'rahul@diaspora.de', ...),
(3, 'Priya', 'priya@diaspora.de', ...);

-- user_organization_roles
INSERT INTO user_organization_roles VALUES
(1, 1, 1, 'admin', ...),      -- Anna is admin
(2, 2, 1, 'commission', ...),  -- Rahul monitors
(3, 3, 1, 'voter', ...);       -- Priya votes

-- elections
INSERT INTO elections VALUES
(1, 'Board Elections 2026', 1, ...);  -- organization_id = 1
```

### System Behavior

**Anna's Login (Day 5):**
```
Login → LoginResponse
    → isFirstTimeUser() = FALSE (has organization)
    → getDashboardRoles() = ['admin']
    → Single role
    → Direct to /dashboard/admin
    → Shows: Admin Dashboard
```

**Rahul's Login (Day 5):**
```
Login → LoginResponse
    → isFirstTimeUser() = FALSE (new but has role)
    → getDashboardRoles() = ['commission']
    → Single role
    → Direct to /dashboard/commission
    → Shows: Commission Dashboard
```

**Priya's Login (Day 5):**
```
Login → LoginResponse
    → isFirstTimeUser() = FALSE (new but has role)
    → getDashboardRoles() = ['voter']
    → Single role
    → Direct to /vote
    → Shows: Voter Dashboard with election
```

---

## Journey 2: Multi-Role User

**Persona:** Marcus - Admin of one org + voter in another

### Timeline

```
Scenario:
├─ Marcus is admin of "German Works Council"
│   └─ user_organization_roles:
│       └─ marcus → admin for org_id=1
├─ Marcus is voter in "Tech Workers Collective"
│   └─ user_organization_roles:
│       └─ marcus → voter for org_id=2
└─ Has 2 distinct roles

Marcus Login:
├─ LoginResponse checks:
│   ├─ isFirstTimeUser() = FALSE
│   ├─ getDashboardRoles() = ['admin', 'voter']
│   └─ Count = 2 (multiple)
├─ Redirects to: /dashboard/roles
└─ Shows Role Selection Dashboard

Role Selection UI:
├─ "Welcome, Marcus! You have 2 roles"
├─ Card 1: ADMIN
│   ├─ Icon: 👑
│   ├─ Title: "Administrator"
│   ├─ Description: "Manage German Works Council"
│   ├─ Stats: 15 members, 3 elections, 1 active
│   └─ Button: "Go to Admin Dashboard"
├─ Card 2: VOTER
│   ├─ Icon: 🗳️
│   ├─ Title: "Voter"
│   ├─ Description: "Participate in Tech Workers Collective"
│   ├─ Stats: 2 pending votes, 5 completed elections
│   └─ Button: "Vote Now"
└─ Marcus chooses role

Marcus Clicks "Go to Admin Dashboard":
├─ POST /switch-role/admin
│   └─ session['current_role'] = 'admin'
├─ Redirects to: /dashboard/admin
├─ Shows admin data for organization_id=1
├─ Marcus manages "German Works Council"
└─ (other org data hidden)

Marcus Clicks Back, Selects "Vote Now":
├─ POST /switch-role/voter
│   └─ session['current_role'] = 'voter'
├─ Redirects to: /vote
├─ Shows voter data for organization_id=2
├─ Marcus sees 2 pending ballots in "Tech Workers Collective"
└─ Can cast votes

Multiple Times Per Session:
├─ Marcus switches back to /dashboard/roles
├─ Selects different role
├─ Session role updated
└─ Appropriate dashboard shown
```

### Session Management

```php
// After Marcus logs in with 'admin' role
session('current_role');  // 'admin'

// Marcus switches to 'voter'
session(['current_role' => 'voter']);

// On next request, middleware checks:
if (!$user->hasDashboardRole('voter')) {
    redirect()->route('role.selection');
}
```

### Switching Roles During Session

```
Timeline:
├─ 2:00 PM - Login
│   └─ /dashboard/roles (role selection)
├─ 2:05 PM - Select admin
│   └─ /dashboard/admin (managing German Works Council)
├─ 2:30 PM - Click "Switch role"
│   └─ /dashboard/roles (back to role selection)
├─ 2:35 PM - Select voter
│   └─ /vote (voting in Tech Workers Collective)
├─ 3:15 PM - Navigate back
│   └─ /dashboard/roles (switch again)
└─ Session persists current_role throughout
```

---

## Journey 3: Legacy User Migration

**Persona:** Otto - Existing user with legacy roles

### Pre-Migration State

```sql
-- Otto's data BEFORE three-role system
INSERT INTO users VALUES
(5, 'Otto', 'otto@gewerkschaft.de', ...,
 is_voter=TRUE, is_committee_member=FALSE, ...);

INSERT INTO role_user VALUES  -- Spatie roles
(5, 1);  -- role_id=1 is 'admin'

-- No entry in user_organization_roles yet!
```

### Otto's Login After System Deployment

```
Login → LoginResponse
    → isFirstTimeUser() = FALSE (is_voter=TRUE)
    → getDashboardRoles():
        ├─ Query user_organization_roles
        │   └─ EMPTY (not migrated)
        ├─ Query Spatie roles
        │   └─ 'admin' found
        └─ Return: ['admin']
    → Single role
    → Direct to /dashboard/admin
    → Shows: Admin Dashboard (legacy behavior preserved!)
```

### Key Points

✅ Otto's experience unchanged
✅ Legacy roles still recognized
✅ No new setup needed
✅ Can migrate to new system gradually
✅ Both systems work in parallel

---

## Journey 4: Admin Batch Operations (Future)

**Persona:** Sarah - Admin creating multiple elections

### Workflow

```
Sarah's Workflow:
├─ Login
│   └─ Single admin role → /dashboard/admin
├─ Selects "Create Election"
│   ├─ Fills form (title, voters, dates)
│   ├─ Selects eligible voters
│   │   └─ Filters by organization members
│   ├─ Selects monitors
│   │   └─ commission members
│   └─ Submit
├─ API creates election
│   ├─ elections table
│   └─ election_commission_members table
├─ Voters notified
│   ├─ Email with voting link
│   └─ /vote shows pending ballot
└─ Monitors can watch in real-time
    └─ Commission dashboard updates
```

### Data Flow

```
Create Election Request
    ↓
ElectionController::store()
    ├─ Verify user is admin
    ├─ Create election record
    ├─ Add commission members
    ├─ Notify voters (queue job)
    └─ Return election with stats

Commission Member Monitoring
    ├─ Login
    ├─ /dashboard/commission
    ├─ See live election stats
    │   ├─ Total voters: 50
    │   ├─ Votes cast: 23
    │   ├─ Votes pending: 27
    │   └─ Audit trail (timestamped)
    └─ Can see individual votes (anonymized)

Voter Participation
    ├─ Login
    ├─ /vote shows ballots
    ├─ Selects ballot
    ├─ Casts vote (encrypted)
    ├─ Receives verification code
    └─ Vote submitted (election server)
```

---

## Journey 5: Error Handling Scenarios

### Scenario A: User Tries Unauthorized Access

```
Marcus (voter in org 1) tries to access /dashboard/admin
    ├─ CheckUserRole middleware runs
    ├─ hasDashboardRole('admin')? → FALSE
    ├─ Redirect to /dashboard/roles
    └─ Message: "You don't have admin access"

Database check:
    SELECT * FROM user_organization_roles
    WHERE user_id = marcus_id AND role = 'admin'
    → EMPTY RESULT
    → Access denied
```

### Scenario B: Session Expires

```
Marcus browses /dashboard/admin (admin role)
    ├─ 15 minutes of inactivity
    ├─ Session expires
    ├─ Next request to /dashboard/admin
    ├─ Auth middleware checks
    ├─ Not authenticated → redirect to login
    ├─ Marcus logs in again
    └─ Redirected to /dashboard/roles (role selection)

Marcus must re-select admin role to return to dashboard
```

### Scenario C: Role Revoked During Session

```
Anna (admin) revokes Marcus's voter role
    ├─ API DELETE from user_organization_roles
    ├─ Cache::forget("user_marcus_dashboard_roles")
    ├─ Marcus still browsing /vote (in other tab)
    ├─ Next request
    ├─ hasDashboardRole('voter')? → FALSE (cache cleared)
    ├─ Redirect to /dashboard/roles
    └─ Error: "You no longer have voter access"
```

---

## Summary: All Paths to Dashboards

```
Entry Point: /login → POST /login → LoginResponse

Route Decision Tree:

isFirstTimeUser()?
├─ YES → /dashboard/welcome (Welcome Dashboard)
└─ NO → getDashboardRoles()

Role Count:
├─ 0 roles
│   └─ Check legacy roles
│       ├─ Spatie admin? → /dashboard/admin
│       ├─ is_voter? → /dashboard
│       ├─ is_committee_member? → /dashboard/commission
│       └─ none → /dashboard
├─ 1 role
│   ├─ admin → /dashboard/admin
│   ├─ commission → /dashboard/commission
│   ├─ voter → /vote
│   └─ unknown → /dashboard/roles
└─ 2+ roles → /dashboard/roles

Role Selection:
├─ Select admin → /dashboard/admin
├─ Select commission → /dashboard/commission
├─ Select voter → /vote
└─ Select other → /dashboard/roles
```
