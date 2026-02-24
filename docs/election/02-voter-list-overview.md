# Voter List Overview

**Understanding the Main Voter Management Page**

This guide explains what you see on the voter list page and what each section does.

---

## 📋 Table of Contents

1. [The Voter List Page Layout](#the-voter-list-page-layout)
2. [Understanding Each Column](#understanding-each-column)
3. [Status Indicators](#status-indicators)
4. [Key Information Cards](#key-information-cards)
5. [Navigation & Controls](#navigation--controls)
6. [For Different Roles](#for-different-roles)

---

## The Voter List Page Layout

When you access the voter list, you'll see this structure:

```
┌─────────────────────────────────────────────────────────────────┐
│ Election Management System                    [Language] [Menu] │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Organization Name > Voters                     ← Breadcrumb    │
│                                                                 │
│  📊 Voter Statistics                          ← Dashboard Cards │
│  ┌──────────────┬──────────────┬──────────────┬──────────────┐  │
│  │ Total Voters │ Approved     │ Pending      │ Voted        │  │
│  │    2,450     │    1,950     │    500       │    1,850     │  │
│  └──────────────┴──────────────┴──────────────┴──────────────┘  │
│                                                                 │
│  🔍 Search & Filter                                             │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Search by name, ID...         [Show Filters ▼]         │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  📋 Voter Table                                                 │
│  ┌────┬────────┬──────────┬─────────┬────────┬────────────┐    │
│  │S.N.│ User ID│   Name   │ Status  │Approved│  Actions   │    │
│  │    │        │          │         │   By   │            │    │
│  ├────┼────────┼──────────┼─────────┼────────┼────────────┤    │
│  │ 1. │ USR001 │John Smith│Approved │ Admin  │[Suspend] ⋯│    │
│  │ 2. │ USR002 │Jane Doe  │Pending  │  —     │[Approve] ⋯│    │
│  │ 3. │ USR003 │Bob Jones │Suspended│ Admin  │[Approve] ⋯│    │
│  │    │   ...  │   ...    │  ...    │  ...   │   ...     │    │
│  └────┴────────┴──────────┴─────────┴────────┴────────────┘    │
│                                                                 │
│  Showing 1-50 of 2,450 voters  [< 1 2 3 ... >]  Show: 50 ▼     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## Understanding Each Column

### 🔢 S.N. (Serial Number)

- **What it is:** Sequential number for this page
- **Resets:** Each page of results has its own numbering
- **Example:** 1, 2, 3... on page 1; 51, 52, 53... on page 2

### 🆔 User ID

- **What it is:** Unique identifier for the voter in the system
- **Format:** Usually alphanumeric (e.g., USR001, VOTER_1234)
- **Use case:** Reference when discussing specific voters
- **Click behavior:** May be clickable to view voter details (if available)

### 👤 Name

- **What it is:** The voter's full name as registered in the system
- **Format:** FirstName LastName
- **Example:** "John Smith", "Maria García", "李明"
- **Click behavior:** May open voter details or profile

### ✅ Status

- **What it is:** Current approval status for voting
- **Possible values:**
  - **Approved** (Green) - Ready to vote
  - **Pending** (Yellow/Orange) - Waiting for approval
  - **Suspended** (Red) - Not allowed to vote
- **Meaning:**
  - **Approved:** Commission member has approved this voter
  - **Pending:** Commission has not yet reviewed this voter
  - **Suspended:** Voter was temporarily suspended from voting

### 👨‍⚖️ Approved By

- **What it is:** Name of the commission member who approved the voter
- **Shows:** The person's name if status is "Approved"
- **Shows:** "—" (dash) if status is "Pending" or "Suspended"
- **Use case:** Track who approved each voter (audit trail)

### 🕐 Voting IP (if visible)

- **What it is:** The voter's internet address when they voted
- **Privacy note:** Only visible to administrators
- **Use case:** Fraud detection and audit purposes
- **Example:** 192.168.1.100

### ⚙️ Actions

- **What it is:** Buttons to manage this voter
- **Available actions depend on your role:**

| Role | Available Actions |
|------|-------------------|
| **Member** | View details |
| **Staff** | View details, Generate reports |
| **Commission Member** | Approve, Suspend, View details |
| **Admin** | All actions |

---

## Status Indicators

### Color Codes

```
┌─────────────────────────────────────────┐
│          STATUS COLOR GUIDE              │
├─────────────────────────────────────────┤
│                                         │
│  🟢 Green = Approved (Ready to vote)    │
│                                         │
│  🟡 Yellow = Pending (Not approved yet)│
│                                         │
│  🔴 Red = Suspended (Cannot vote)       │
│                                         │
│  ⚫ Gray = Inactive/Archived             │
│                                         │
└─────────────────────────────────────────┘
```

### Understanding Each Status

#### 🟢 **Approved**
- Voter has been reviewed and approved by a commission member
- Status shows "Approved" with green background
- "Approved By" shows who approved them
- Commission can still "Suspend" them if needed
- This voter can now vote

#### 🟡 **Pending**
- Voter has been registered but not yet reviewed
- Status shows "Pending" with yellow/orange background
- "Approved By" shows "—" (empty)
- Commission member can click "Approve" to change status
- This voter cannot vote until approved

#### 🔴 **Suspended**
- Voter was previously approved but is now suspended
- Status shows "Suspended" with red background
- "Approved By" shows the original approver
- Commission can click "Approve" to reinstate the voter
- This voter cannot vote while suspended

---

## Key Information Cards

### 📊 Voter Statistics (Top Dashboard)

Located at the top of the page, showing four important metrics:

#### **Total Voters**
- Count of all registered voters in this organization
- Includes approved, pending, and suspended voters
- Updates in real-time as voters are added

#### **Approved**
- Count of voters who have been approved
- These voters are ready to vote
- This number increases when you approve a voter

#### **Pending**
- Count of voters waiting for approval
- Action item: Commission members should review these
- This number decreases when you approve voters

#### **Voted**
- Count of voters who have already voted
- Read-only (for information only)
- Tracking who has participated

### 📈 Percentages

Below each card, you may see:
- **% of Total:** What percentage of all voters this represents
- **Change:** How this number has changed recently (↑ increase, ↓ decrease)
- **Trend:** "5 more approved today" type messages

---

## Navigation & Controls

### 🔍 Search Bar

**Location:** Above the voter table

**Function:** Find voters by name or ID

**How to use:**
1. Click in the search box
2. Type the voter's name or ID
3. Press Enter or wait for auto-search
4. Results update in real-time

**Examples:**
- Search "John" → Shows all voters with "John" in their name
- Search "USR001" → Shows voter with ID starting with "USR001"

See [Searching & Filtering](./03-searching-filtering.md) for advanced search options.

### 📊 Show Filters Button

**Location:** Next to search bar

**Function:** Additional filtering options

**Options include:**
- Filter by status (Approved, Pending, Suspended)
- Filter by date range
- Filter by region (if applicable)

### 🔄 Sorting

**Location:** Click on column headers

**Function:** Sort voters by that column

**How to use:**
1. Click the column header (Name, Status, etc.)
2. Click again to reverse sort order (A→Z, Z→A)

**Sortable columns:**
- ✅ Name
- ✅ Status
- ✅ Approved By (if visible)
- ✅ Date columns

**Non-sortable columns:**
- ❌ Actions (control buttons)

### 📄 Pagination

**Location:** Bottom of the table

**Shows:** "Showing 1-50 of 2,450 voters"

**Navigation buttons:**
- **< Prev** - Go to previous page
- **1, 2, 3...** - Page numbers (click to jump)
- **Next >** - Go to next page

**Rows per page:**
- Default: 50 rows per page
- Click dropdown to change to: 10, 25, 50, 100, or 250

---

## For Different Roles

### 👤 **Member Role**

**What you see:**
- ✅ All voter information
- ✅ Search and filter
- ✅ Statistics cards
- ❌ Approve/Suspend buttons
- ❌ Edit voter information

**What you can do:**
- View all voters in your organization
- Search for specific voters
- View statistics and trends
- Export data (if available)

**You cannot:**
- Approve or suspend voters
- Modify voter information
- Delete voters

---

### 👔 **Staff Role**

**What you see:**
- ✅ All voter information
- ✅ Search and filter
- ✅ Statistics cards
- ✅ Advanced reports
- ❌ Approve/Suspend buttons

**What you can do:**
- View all voters in your organization
- Search and filter voters
- Generate reports and analytics
- Export data to CSV or PDF
- View historical data

**You cannot:**
- Approve or suspend voters
- Modify voter information

---

### ⚖️ **Commission Member Role**

**What you see:**
- ✅ All voter information
- ✅ Search and filter
- ✅ Statistics cards
- ✅ Approve/Suspend buttons
- ✅ Bulk action options

**What you can do:**
- View all voters in your organization
- Search, filter, and sort voters
- **Approve individual voters** (one at a time)
- **Suspend individual voters**
- **Bulk approve** multiple voters at once
- **Bulk suspend** multiple voters at once
- View and edit voter information

**Full responsibilities:**
- Review pending voters
- Make approval decisions
- Monitor voting activity
- Generate reports

---

### 🔐 **Admin Role**

**What you see:**
- ✅ All voter information
- ✅ All controls
- ✅ Administrative functions
- ✅ System settings

**What you can do:**
- Everything commission members can do
- Plus: Manage users, roles, permissions
- System configuration
- View audit logs
- Access all organizations (if multi-tenant platform)

---

## Quick Actions Reference

| Task | Where to Find It |
|------|-----------------|
| **Find a voter** | Search bar at top |
| **Filter by status** | Show Filters button |
| **View statistics** | Top dashboard cards |
| **Change page** | Pagination at bottom |
| **Change items per page** | "Show: 50" dropdown |
| **Approve a voter** | Click [Approve] in Actions column |
| **Suspend a voter** | Click [Suspend] in Actions column |
| **View voter details** | Click voter name or [Details] |
| **Bulk operations** | Select checkboxes + Bulk menu |

---

## Page Behavior

### 🔄 Auto-Refresh

- **Default:** Page does NOT auto-refresh
- **Manual refresh:** Press F5 or click refresh button
- **Changes visible:** After someone approves a voter, you may need to refresh to see updates

### 💾 Session Timeout

- **Duration:** 1 hour of inactivity
- **Warning:** You'll see a notification before timeout
- **After timeout:** You'll be logged out and need to login again

### 📱 Mobile View

- **Small screens:** Table compacts to show essential columns
- **Sorting:** Still available but may be labeled differently
- **Actions:** May appear in a menu instead of buttons
- **Full table:** Swipe right to see hidden columns

See [Accessibility Guide](./08-accessibility.md) for more details on mobile and accessible viewing.

---

## Next Steps

👉 **Need to find a specific voter?** Go to [Searching & Filtering](./03-searching-filtering.md)

👉 **Want to approve or suspend voters?** Go to [Managing Voters](./04-managing-voters.md)

👉 **Need to work with many voters at once?** Go to [Bulk Operations](./05-bulk-operations.md)

👉 **Want to see statistics?** Go to [Statistics & Reports](./06-statistics-reports.md)

---

## 🆘 Need Help?

- **Can't see a voter?** See [Searching & Filtering](./03-searching-filtering.md)
- **Questions about actions?** See [Managing Voters](./04-managing-voters.md)
- **Performance issues?** See [Tips & Troubleshooting](./07-tips-troubleshooting.md)
- **Using a screen reader?** See [Accessibility Guide](./08-accessibility.md)

---

**Happy voting! 🗳️**
