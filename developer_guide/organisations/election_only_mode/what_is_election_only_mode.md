## Frontend Orientation: Election-Only vs Full Membership Mode

### Visual Guide to Mode Selection

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    ORGANISATION CREATION — Step 1                                 │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  Membership System                                                                │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                              │ │
│  │  ● Full Membership                                                           │ │
│  │    Voters must be formal members with paid fees.                              │ │
│  │    Best for organisations with membership tracking.                           │ │
│  │                                                                              │ │
│  │  ○ Election-Only                                                             │ │
│  │    Any registered user can vote.                                              │ │
│  │    Best for simple elections without membership tracking.                     │ │
│  │                                                                              │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│  Decision Tree:                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                              │ │
│  │  Do you track formal memberships?                                            │ │
│  │       │                                                                      │ │
│  │       ├── YES → Choose "Full Membership"                                     │ │
│  │       │         • Voters must be members                                     │ │
│  │       │         • Track fees and membership types                            │ │
│  │       │         • Membership applications required                           │ │
│  │       │                                                                      │ │
│  │       └── NO → Choose "Election-Only"                                        │ │
│  │                 • Any registered user can vote                               │ │
│  │                 • Import voters via CSV                                      │ │
│  │                 • No membership validation                                   │ │
│  │                                                                              │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### When to Use Each Mode

| Scenario | Recommended Mode | Why |
|----------|-----------------|-----|
| **Verein/Club with annual fees** | Full Membership | Track paid members, only they can vote |
| **NGO with formal membership** | Full Membership | Membership types, voting rights by tier |
| **Diaspora organization** | Full Membership | Verify members, fee collection |
| **One-time event voting** | Election-Only | No membership tracking needed |
| **Internal staff election** | Election-Only | All staff automatically eligible |
| **Quick poll among registered users** | Election-Only | Simple, fast setup |

### User Journey: Full Membership Mode

```
1. CREATE ORGANISATION
   └── Select "Full Membership"
   
2. ADD MEMBERS
   └── Members → Add Member → Enter user details
   └── Or: Membership Applications → Approve
   
3. MARK FEES AS PAID
   └── Members → Find member → "Mark Paid"
   
4. CREATE ELECTION
   └── Election created
   
5. ASSIGN VOTERS
   └── Voters page → Only paid members appear in dropdown
   └── Select members → Assign
   
6. VOTING
   └── Only assigned, paid members can vote
```

### User Journey: Election-Only Mode

```
1. CREATE ORGANISATION
   └── Select "Election-Only"
   
2. CREATE ELECTION
   └── Election created
   
3. ADD VOTERS (Three Options)
   └── A) Dropdown: Select any registered user
   └── B) CSV Import: Upload email list
   └── C) UUID: Paste user ID directly
   
4. VOTING
   └── All assigned users can vote immediately
   └── No membership or fee validation
```

### Visual Indicators in UI

| Location | Full Membership | Election-Only |
|----------|-----------------|---------------|
| **Settings Page** | 🔵 Blue badge "Full Membership" | 🟢 Green badge "Election-Only" |
| **Voters Page Header** | "ASSIGN MEMBERS AS VOTERS" | "ASSIGN USERS AS VOTERS" |
| **Voters Page Help Text** | "Only active members with paid fees appear below" | "All organisation users can be assigned as voters" |
| **Import Options** | Excel with membership validation | Simple CSV drop |

### Settings Page — Mode Indicator

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  Organisation Settings                                                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  Current Mode: [ 🔵 Full Membership ]                                             │
│                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                              │ │
│  │  Require Full Membership                                          [🟢 ON]   │ │
│  │  When enabled, voters must be active members with paid fees.                 │ │
│  │                                                                              │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│  Active members: 42                                                               │
│                                                                                   │
│  [Save Changes]                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Switching Modes — Warning

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  ⚠️ Confirm Mode Change                                                           │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  This organisation has 42 active members. Switching to election-only mode         │
│  will allow ANY registered user to vote, bypassing membership requirements.       │
│                                                                                   │
│  ☐ I understand and want to proceed with this change                              │
│                                                                                   │
│  [Cancel]  [Save Changes]                                                         │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### CSV Import (Election-Only Mode)

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  IMPORT VOTERS                                                                    │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                              │ │
│  │   [📂 Import CSV]                                                            │ │
│  │                                                                              │ │
│  │   CSV format: email (one per line or first column)                           │ │
│  │                                                                              │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│  Example CSV:                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │ voter1@example.com                                                           │ │
│  │ voter2@example.com                                                           │ │
│  │ voter3@example.com                                                           │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│  Results: ✅ 3 imported  ⚠️ 1 skipped  ❌ 0 errors                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Summary Table

| Feature | Full Membership | Election-Only |
|---------|-----------------|---------------|
| Member records required | ✅ Yes | ❌ No |
| Fee tracking | ✅ Yes | ❌ No |
| Voter dropdown shows | Only paid members | All org users |
| CSV import | Excel with preview | Simple CSV |
| Template download | ✅ Yes | ❌ No |
| Best for | Formal organizations | Quick elections |

**Om Gam Ganapataye Namah** 🪔🐘
