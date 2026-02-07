# **THREE-USER PATH SYSTEM DESIGN**

## **CRITICAL UNDERSTANDING:**

You actually need **THREE distinct roles**, not two:

1. **👑 Organization Admin** - Owns/organizes elections
2. **⚖️ Election Commission** - Runs/operates specific election  
3. **👤 Voter** - Casts votes in elections

---

## **POST-LOGIN ROLE SELECTION DASHBOARD**

```vue
<template>
<!-- ROLE SELECTION DASHBOARD -->
<div class="role-selection-dashboard">
  
  <!-- Welcome Header -->
  <section class="welcome-section">
    <h1>Welcome back, {{ userName }}!</h1>
    <p>You have access to multiple roles. Choose where to go:</p>
  </section>

  <!-- Role Selection Cards -->
  <section class="role-cards-section">
    <div class="role-cards-grid">
      
      <!-- CARD 1: ORGANIZATION ADMIN -->
      <RoleCard 
        :active="userHasAdminRole"
        @click="selectRole('admin')"
        class="admin-role"
      >
        <div class="role-icon">👑</div>
        <div class="role-title">Organization Administrator</div>
        <div class="role-description">
          Manage your organization, create elections, oversee everything
        </div>
        
        <div class="role-details" v-if="userHasAdminRole">
          <div class="org-info">
            <strong>{{ userOrgs.length }} organization(s)</strong>
            <div v-for="org in userOrgs" :key="org.id" class="org-item">
              • {{ org.name }} ({{ org.role }})
            </div>
          </div>
          <div class="quick-stats">
            <Stat>Active elections: {{ adminStats.elections }}</Stat>
            <Stat>Total members: {{ adminStats.members }}</Stat>
          </div>
        </div>
        
        <div class="cta-section">
          <Button 
            v-if="userHasAdminRole"
            @click.stop="goToAdminDashboard"
            variant="primary"
          >
            Go to Admin Dashboard
          </Button>
          <Button 
            v-else
            @click.stop="requestAdminAccess"
            variant="outline"
          >
            Request Admin Access
          </Button>
        </div>
      </RoleCard>

      <!-- CARD 2: ELECTION COMMISSION -->
      <RoleCard 
        :active="userHasCommissionRole"
        @click="selectRole('commission')"
        class="commission-role"
      >
        <div class="role-icon">⚖️</div>
        <div class="role-title">Election Commission Member</div>
        <div class="role-description">
          Run specific elections, monitor voting, ensure fairness
        </div>
        
        <div class="role-details" v-if="userHasCommissionRole">
          <div class="elections-list">
            <strong>{{ commissionElections.length }} election(s)</strong>
            <div v-for="election in commissionElections" :key="election.id" class="election-item">
              • {{ election.title }} ({{ election.status }})
            </div>
          </div>
          <div class="quick-stats">
            <Stat>Votes cast: {{ commissionStats.votes }}</Stat>
            <Stat>Issues to review: {{ commissionStats.issues }}</Stat>
          </div>
        </div>
        
        <div class="cta-section">
          <Button 
            v-if="userHasCommissionRole"
            @click.stop="goToCommissionDashboard"
            variant="primary"
          >
            Go to Commission Dashboard
          </Button>
          <Button 
            v-else
            @click.stop="joinCommission"
            variant="outline"
          >
            Join Election Commission
          </Button>
        </div>
      </RoleCard>

      <!-- CARD 3: VOTER -->
      <RoleCard 
        :active="userHasVoterRole"
        @click="selectRole('voter')"
        class="voter-role"
      >
        <div class="role-icon">👤</div>
        <div class="role-title">Voter / Member</div>
        <div class="role-description">
          Vote in elections, verify your vote, view results
        </div>
        
        <div class="role-details" v-if="userHasVoterRole">
          <div class="active-elections">
            <strong>{{ voterElections.active }} active election(s)</strong>
            <div class="election-item" v-if="voterElections.nextElection">
              • {{ voterElections.nextElection.title }} 
                (deadline: {{ voterElections.nextElection.deadline }})
            </div>
          </div>
          <div class="quick-stats">
            <Stat>Pending votes: {{ voterStats.pending }}</Stat>
            <Stat>Votes cast: {{ voterStats.cast }}</Stat>
          </div>
        </div>
        
        <div class="cta-section">
          <Button 
            v-if="userHasVoterRole && voterStats.pending > 0"
            @click.stop="goToVoting"
            variant="primary"
          >
            Vote Now ({{ voterStats.pending }} pending)
          </Button>
          <Button 
            v-else-if="userHasVoterRole"
            @click.stop="goToVoting"
            variant="outline"
          >
            View Voting History
          </Button>
          <Button 
            v-else
            @click.stop="registerAsVoter"
            variant="outline"
          >
            Register as Voter
          </Button>
        </div>
      </RoleCard>

    </div>
  </section>

  <!-- Quick Actions -->
  <section class="quick-actions" v-if="selectedRole">
    <h3>Quick Actions for {{ selectedRoleName }}</h3>
    <div class="action-buttons">
      <Button @click="goToRoleDashboard" variant="primary">
        Go to {{ selectedRoleName }} Dashboard
      </Button>
      <Button @click="viewRoleTutorial" variant="outline">
        Watch Tutorial
      </Button>
      <Button @click="contactSupport" variant="text">
        Get Help
      </Button>
    </div>
  </section>

  <!-- Recent Activity -->
  <section class="recent-activity">
    <h3>Your Recent Activity Across Roles</h3>
    <ActivityTimeline :activities="recentActivities" />
  </section>

</div>
</template>
```

---

## **DATABASE SCHEMA FOR THREE ROLES:**

```sql
-- Users table (base)
users:
  id
  email
  name
  created_at

-- Organizations
organizations:
  id
  name
  type
  settings

-- User-Organization Roles (Many-to-Many)
user_organization_roles:
  user_id
  organization_id
  role_type ENUM('admin', 'commission', 'voter')
  permissions JSON
  created_at

-- Elections
elections:
  id
  organization_id
  title
  status ENUM('draft', 'active', 'completed')
  commission_members JSON -- array of user_ids with commission access

-- User can have DIFFERENT roles in DIFFERENT organizations!
-- Example:
-- User ID 1:
--   - Admin of Organization A
--   - Commission member for Election X in Organization B  
--   - Voter in Organization C
```

---

## **ROLE PERMISSIONS MATRIX:**

| Feature | Organization Admin | Election Commission | Voter |
|---------|-------------------|---------------------|-------|
| Create organization | ✅ | ❌ | ❌ |
| Create elections | ✅ | ❌ | ❌ |
| Edit election settings | ✅ | ✅ (limited) | ❌ |
| Add/remove voters | ✅ | ❌ | ❌ |
| View voter list | ✅ | ✅ (anonymized) | ❌ |
| Monitor live votes | ✅ | ✅ | ❌ |
| Send reminders | ✅ | ✅ | ❌ |
| Cast vote | ❌ | ❌ | ✅ |
| View results before close | ✅ | ❌ | ❌ |
| Publish results | ✅ | ✅ | ❌ |
| View audit trail | ✅ | ✅ | ❌ |

---

## **AFTER ROLE SELECTION - THREE SEPARATE DASHBOARDS:**

### **1. Organization Admin Dashboard**
```
URL: /dashboard/admin
Purpose: Strategic oversight of ALL elections in organization
Features:
- Organization settings
- Create new elections
- Assign commission members
- View all election reports
- Manage organization members
- Billing & subscriptions
```

### **2. Election Commission Dashboard**  
```
URL: /dashboard/commission/:election_id
Purpose: Operational management of SPECIFIC election
Features:
- Election-specific monitoring
- Voter support (reset codes, etc.)
- Live vote counting (anonymized)
- Fraud detection alerts
- Send election communications
- Publish results when ready
```

### **3. Voter Portal**
```
URL: /vote
Purpose: Simple voting interface
Features:
- List elections I can vote in
- Voting booth
- Vote verification
- Voting history
- Profile settings
```

---

## **USER EXPERIENCE FLOW:**

### **Registration/Login:**
```
1. User logs in with email/password
2. System checks ALL their roles across ALL organizations
3. Show role selection dashboard with available roles
4. User chooses role → goes to that specific dashboard
```

### **Role Switching:**
```vue
<!-- Role Switcher in Header -->
<RoleSwitcherDropdown>
  <div class="current-role">
    Currently: {{ currentRole }}
    <Icon>⌄</Icon>
  </div>
  
  <DropdownMenu>
    <MenuItem v-if="hasAdminRole" @click="switchToRole('admin')">
      👑 Switch to Organization Admin
    </MenuItem>
    <MenuItem v-if="hasCommissionRole" @click="switchToRole('commission')">
      ⚖️ Switch to Election Commission
    </MenuItem>
    <MenuItem v-if="hasVoterRole" @click="switchToRole('voter')">
      👤 Switch to Voter Portal
    </MenuItem>
  </DropdownMenu>
</RoleSwitcherDropdown>
```

---

## **IMPLEMENTATION PRIORITY:**

### **Week 1: Basic Role System**
1. Add role columns to database
2. Create role selection page after login
3. Build basic admin dashboard (priority for your customers)

### **Week 2: Commission Features**
1. Commission dashboard for specific elections
2. Role-based permissions
3. Election assignment system

### **Week 3: Polish & Integration**
1. Role switching
2. Combined activity timeline
3. Notification system per role
4. Mobile optimization

---

## **BUSINESS BENEFITS:**

### **For Organizations:**
- **Clear separation of duties** - Admins set up, commissions run
- **Reduced risk** - No single person has all power
- **Professional structure** - Mimics real election committees

### **For Users:**
- **Simple interface** - Each role sees only what they need
- **No confusion** - Clear boundaries between roles
- **Flexibility** - Can have multiple roles across organizations

### **For Platform:**
- **Better security** - Principle of least privilege
- **Scalable** - Supports large, complex organizations
- **Professional** - Meets governance requirements

---

## **CRITICAL NEXT STEP:**

**Build the role selection dashboard FIRST**, then:
1. Admin dashboard (your #1 priority - where customers create elections)
2. Voter portal (you already have demo, needs polish)
3. Commission dashboard (can come later)

**Without this role separation, admins and voters get mixed up in the same interface, creating terrible UX for both.**