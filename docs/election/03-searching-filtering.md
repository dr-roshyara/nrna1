# Searching & Filtering Voters

**Find voters quickly and efficiently**

This guide shows you how to search for specific voters and use advanced filtering options.

---

## 📋 Table of Contents

1. [Quick Search](#quick-search)
2. [Advanced Filtering](#advanced-filtering)
3. [Filter Combinations](#filter-combinations)
4. [Sorting Results](#sorting-results)
5. [Clearing Filters](#clearing-filters)
6. [Search Tips & Tricks](#search-tips--tricks)

---

## Quick Search

### The Simplest Way to Find a Voter

**Step 1:** Click the search box at the top of the page

```
┌──────────────────────────────────────────────────┐
│  🔍 Search by name, ID...     [Show Filters ▼]  │
│     ↑ Click here to start typing                │
└──────────────────────────────────────────────────┘
```

**Step 2:** Type what you're looking for

**Step 3:** Press Enter or wait for auto-search (results update as you type)

### What You Can Search For

✅ **By Full Name**
- Search: "John Smith"
- Finds: All voters with "John" and/or "Smith" in their name
- Example results: John Smith, John Anderson, Mary Smith

✅ **By First Name**
- Search: "John"
- Finds: All voters with first name starting with "John"
- Example results: John Smith, Johnny Doe, John Anderson

✅ **By Last Name**
- Search: "Smith"
- Finds: All voters with last name containing "Smith"
- Example results: John Smith, Mary Smith, Robert Smith

✅ **By User ID**
- Search: "USR001"
- Finds: Voter with ID starting with "USR001"
- Example results: USR001, USR0010, USR0011

✅ **By Email** (if enabled)
- Search: "john@example.com"
- Finds: Voters with that email address
- Example results: john@example.com, john.smith@example.com

### Search Examples

| Search Term | Finds |
|------------|-------|
| "john" | All voters with "john" in any part of name |
| "smith" | All voters with "smith" in any part of name |
| "john smith" | All voters with both "john" and "smith" |
| "USR001" | Voters with ID starting with "USR001" |
| "john@" | All voters with "john@" in email |

### ⚡ Quick Tip

Search is **case-insensitive**, so:
- "JOHN" = "john" = "John" (all work the same)

---

## Advanced Filtering

### Accessing Advanced Filters

**Location:** Click **"Show Filters ▼"** button next to the search box

```
┌──────────────────────────────────────────────────┐
│  🔍 Search by name, ID...    [Show Filters ▼]  │
│                               ↑ Click here      │
└──────────────────────────────────────────────────┘

Click to reveal:

┌─────────────────────────────────────────────────┐
│ 📊 FILTER OPTIONS                              │
├─────────────────────────────────────────────────┤
│                                                 │
│ Status:                                         │
│ ☑ All  ☐ Approved  ☐ Pending  ☐ Suspended   │
│                                                 │
│ Date Range:                                     │
│ From: [Select Date]  To: [Select Date]         │
│                                                 │
│ Region: [Select Region ▼]                       │
│                                                 │
│ [ Apply Filters ]                               │
│                                                 │
└─────────────────────────────────────────────────┘
```

### Filter Options

#### **1. Filter by Status**

**Purpose:** Show only voters with a specific approval status

**Options:**
- **All** - Show all voters (default)
- **Approved** - Only approved voters
- **Pending** - Only pending approval voters
- **Suspended** - Only suspended voters

**How to use:**
1. Click the checkbox next to the status(es) you want
2. Check multiple boxes to see multiple statuses together
3. Click "Apply Filters"

**Example scenarios:**
- *Commission wants to review pending voters:* Check "Pending" only
- *Manager wants to see who has been approved:* Check "Approved" only
- *Auditor wants to review all non-approved voters:* Check "Pending" + "Suspended"

#### **2. Filter by Date Range**

**Purpose:** Show voters registered or approved within a specific date range

**Options:**
- **From Date:** Start date (when you want to begin)
- **To Date:** End date (when you want to end)

**How to use:**
1. Click "From Date" field and select start date from calendar
2. Click "To Date" field and select end date from calendar
3. Click "Apply Filters"

**Date format:** MM/DD/YYYY (or your localized format)

**Example scenarios:**
- *Find all voters registered this week:* From: [Monday], To: [Today]
- *Find voters approved yesterday:* From: [Yesterday], To: [Yesterday]
- *Find voters from last month:* From: [1st of last month], To: [Last day of last month]

#### **3. Filter by Region** (if applicable)

**Purpose:** Show voters from a specific geographic region

**Options:**
- [Region dropdown will show available regions for your organization]

**How to use:**
1. Click the "Region" dropdown
2. Select the region you want
3. Click "Apply Filters"

**Available regions depend on:**
- Your organization's geographic setup
- State/province/region structure
- How administrators configured the system

**Example:**
- Germany organization: Bayern, Baden-Württemberg, Hamburg, etc.
- India organization: Delhi, Karnataka, Maharashtra, etc.

#### **4. Multiple Filters Together**

You can combine all filters at once! See next section.

---

## Filter Combinations

### Example: Approved Voters in Bayern This Month

```
Filters Applied:
┌─────────────────────────────────────────────────┐
│                                                 │
│ Status: ☑ Approved                            │
│                                                 │
│ Date Range:                                     │
│ From: 02/01/2026  To: 02/23/2026              │
│                                                 │
│ Region: Bayern                                  │
│                                                 │
│ [ Clear All ]                                   │
│                                                 │
└─────────────────────────────────────────────────┘

Results: 127 voters match your filters
```

### Common Filter Combinations

| Scenario | Filters to Use |
|----------|----------------|
| Review pending voters | Status: Pending |
| See recent approvals | Status: Approved + Date Range: Last 7 days |
| Check region participation | Region: Specific region |
| Find problem voters | Status: Suspended + Optional Date Range |
| Commission workload | Status: Pending + Optional Region |
| Regional statistics | Region: Specific region + Date Range |

---

## Sorting Results

### Sort by Any Column

**How to sort:**
1. Click the column header (Name, Status, Approved By, etc.)
2. Click again to reverse the sort order

**Sort directions:**
- **↑** Ascending (A to Z, or oldest to newest)
- **↓** Descending (Z to A, or newest to oldest)

**Sortable columns:**

| Column | Ascending | Descending |
|--------|-----------|------------|
| **S.N.** | 1, 2, 3... | ...3, 2, 1 |
| **User ID** | A to Z | Z to A |
| **Name** | A to Z | Z to A |
| **Status** | Approved, Pending, Suspended | Suspended, Pending, Approved |
| **Approved By** | A to Z | Z to A |
| **Date** | Oldest to Newest | Newest to Oldest |

### Sorting Examples

#### **Sort by Name (A to Z)**
1. Click "Name" column header
2. List shows: Anderson, Bob, Chen, David, Evans...

#### **Sort by Status (most approved)**
1. Click "Status" column header
2. List shows: Approved voters first, then Pending, then Suspended

#### **Sort by Date (newest first)**
1. Click "Date" column header (if visible)
2. List shows: Most recent registrations first

---

## Clearing Filters

### Remove All Filters at Once

**Location:** Look for "Clear All" or "Reset Filters" button

```
Filters Applied: 3
┌─────────────────────────┐
│ [ Clear All ]           │  ← Click to remove everything
└─────────────────────────┘
```

**Effect:** All filters removed, page returns to showing all voters

### Remove Individual Filters

Each filter shows an **X** button to remove just that filter:

```
Status: ☑ Approved [X]     ← Click X to remove just this filter
Date Range: 02/01 - 02/23 [X]  ← Click X to remove date range
```

### Reset Search Box

**To clear the search:** Click the X button in the search box or select all and delete

---

## Search Tips & Tricks

### ⚡ Speed Tips

**Tip 1: Use partial names**
- Instead of "John Smith", try "smith"
- Saves typing and finds more results

**Tip 2: Search is instant**
- No need to press Enter
- Results update as you type

**Tip 3: Combine search + filters**
- Search "john" + Filter by Status: Pending
- Finds: Pending voters with "john" in their name

**Tip 4: Use User ID for exact matches**
- Search "USR001" finds only that specific voter
- More reliable than searching by name

### 🎯 Accuracy Tips

**Tip 5: Check spelling**
- Search "Jon" vs "John" = different results
- Copy/paste long names to avoid typos

**Tip 6: Be less specific**
- If "John Michael Smith" doesn't work, try just "Smith"
- The system may have truncated first names

**Tip 7: Try different fields**
- Name doesn't work? Try User ID
- Email doesn't work? Try phone number (if available)

**Tip 8: Use wildcards (if enabled)**
- Some systems support: "jo*" (finds john, joseph, joan)
- Check your organization's help for wildcard support

### 🚫 Common Mistakes

**❌ Mistake 1: Searching with extra spaces**
- Search: "  john  smith  " (multiple spaces)
- Problem: May not find anything
- Solution: Use single spaces: "john smith"

**❌ Mistake 2: Case sensitivity**
- Most systems are NOT case sensitive
- "JOHN" = "john" = "John"
- But try lowercase first if uncertain

**❌ Mistake 3: Special characters**
- Search: "john@smith" looking for email
- Problem: The system might only search names
- Solution: Try searching just the name part: "john"

**❌ Mistake 4: Forgetting to clear old filters**
- Applied Status: Pending filter
- Can't find "john" anymore
- Reason: Only searching pending voters
- Solution: Click "Clear All" filters first

---

## Performance Tips

### For Large Voter Lists (5000+ voters)

**Use search instead of scroll:**
- ❌ Don't: Scroll through 100 pages
- ✅ Do: Use search to find exactly who you need

**Use filters to narrow down:**
- ❌ Don't: View all voters then look for Pending ones
- ✅ Do: Filter by Status: Pending first

**Example workflow:**
```
1. Filter by Status: Pending (maybe 300 voters)
2. Filter by Region (maybe 50 voters)
3. Search by name (1-2 voters)
4. Found in 3 steps instead of scrolling 200 pages!
```

---

## Troubleshooting

### ❌ "No Results Found"

**Problem:** Search returns zero voters

**Solutions:**
1. ✅ Check spelling of the search term
2. ✅ Try a shorter search (just "john" instead of "john smith")
3. ✅ Check that you haven't applied restrictive filters
4. ✅ Click "Clear All" to reset everything
5. ✅ Verify you have permission to see this organization's voters
6. ✅ Try searching by User ID instead of name

---

### ❌ "Too Many Results"

**Problem:** Search returns 1000+ voters (not helpful)

**Solutions:**
1. ✅ Be more specific (add more letters or words)
2. ✅ Use filters to narrow results (Status, Region, Date)
3. ✅ Search by User ID instead of name (more precise)
4. ✅ Check filters aren't set too broad

---

### ❌ "Search isn't working"

**Problem:** Search box is not responsive

**Solutions:**
1. ✅ Make sure JavaScript is enabled in your browser
2. ✅ Refresh the page (F5 or Ctrl+R)
3. ✅ Clear browser cache
4. ✅ Try a different browser
5. ✅ Contact your administrator

---

## Next Steps

👉 **Found a voter to approve?** Go to [Managing Voters](./04-managing-voters.md)

👉 **Need to work with many voters?** Go to [Bulk Operations](./05-bulk-operations.md)

👉 **Want to see statistics?** Go to [Statistics & Reports](./06-statistics-reports.md)

---

## 🆘 Need Help?

- **Can't find a voter?** See [Tips & Troubleshooting](./07-tips-troubleshooting.md)
- **Keyboard only access?** See [Accessibility Guide](./08-accessibility.md)
- **Performance issues?** See [Tips & Troubleshooting](./07-tips-troubleshooting.md#performance)

---

**Happy voting! 🗳️**
