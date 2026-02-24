# Statistics & Reports

**View election data and monitoring information**

This guide explains how to view and understand election statistics and reports.

---

## 📋 Table of Contents

1. [Overview Dashboard Cards](#overview-dashboard-cards)
2. [Understanding the Metrics](#understanding-the-metrics)
3. [Reading the Data](#reading-the-data)
4. [Filtering Statistics](#filtering-statistics)
5. [Exporting Reports](#exporting-reports)
6. [Interpreting Results](#interpreting-results)

---

## Overview Dashboard Cards

### The Statistics Dashboard

Located at the top of the voter list page, showing four key metrics:

```
┌─────────────────────────────────────────────────────────────────┐
│                    📊 VOTER STATISTICS                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐ │
│  │  TOTAL VOTERS    │  │     APPROVED     │  │   PENDING    │ │
│  │                  │  │                  │  │              │ │
│  │      2,450       │  │      1,950       │  │     500      │ │
│  │                  │  │                  │  │              │ │
│  │ ↑ 50 this week   │  │ ↑ 200 this week  │  │ ↓ 100 today  │ │
│  │ 100% registered  │  │ 79.6% of total   │  │ 20.4% of total
│  └──────────────────┘  └──────────────────┘  └──────────────┘ │
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐                    │
│  │     VOTED        │  │    TURNOUT       │                    │
│  │                  │  │                  │                    │
│  │      1,850       │  │      75.5%       │                    │
│  │                  │  │                  │                    │
│  │ ↑ 150 this hour  │  │ of approved      │                    │
│  │ 75.5% approved   │  │ voters have voted│                    │
│  └──────────────────┘  └──────────────────┘                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## Understanding the Metrics

### 📊 Total Voters

**What it shows:**
- Total number of registered voters in your organization
- Includes: Approved, Pending, and Suspended voters

**Why it matters:**
- Reference point for all other metrics
- Tracks growth over time
- Shows registration progress

**Calculation:**
```
Total Voters = Approved + Pending + Suspended
2,450 = 1,950 + 500 + 0
```

**Trend:**
- ↑ (up arrow) = More voters registered recently
- ↓ (down arrow) = Fewer new registrations
- Number shows "50 this week" = 50 new voters registered this week

---

### 🟢 Approved

**What it shows:**
- Number of voters approved and ready to vote
- Only includes "Approved" status voters

**Why it matters:**
- Shows readiness for election
- Tracks commission progress
- Percentage of voters approved

**Calculation:**
```
Approved = Count of voters with status "Approved"
1,950 voters
```

**Percentage:**
```
Approved % = (Approved ÷ Total) × 100
79.6% = (1,950 ÷ 2,450) × 100
```

**Interpretation:**
- **80%+ approved:** Ready for election
- **50-79% approved:** Still reviewing voters
- **<50% approved:** Early stage, much review needed

---

### 🟡 Pending

**What it shows:**
- Number of voters waiting for approval
- Action item for commission members

**Why it matters:**
- Shows work remaining
- Identifies stalled approvals
- Tracks registration backlog

**Calculation:**
```
Pending = Count of voters with status "Pending"
500 voters
```

**Percentage:**
```
Pending % = (Pending ÷ Total) × 100
20.4% = (500 ÷ 2,450) × 100
```

**Interpretation:**
- **0% pending:** All voters reviewed
- **<10% pending:** Nearly complete
- **20%+ pending:** Significant review needed

---

### ✅ Voted

**What it shows:**
- Number of voters who have already participated
- Only includes voters who successfully voted

**Why it matters:**
- Tracks participation
- Measures election engagement
- Real-time turnout data

**Calculation:**
```
Voted = Count of voters who completed voting
1,850 voters
```

**Percentage (of approved):**
```
Voted % = (Voted ÷ Approved) × 100
95% = (1,850 ÷ 1,950) × 100
```

**Interpretation:**
- **80%+ turnout:** Excellent participation
- **50-79% turnout:** Good participation
- **<50% turnout:** Low engagement

---

### 📈 Turnout Rate

**What it shows:**
- Percentage of approved voters who have voted
- Most important metric for election success

**Calculation:**
```
Turnout Rate = (Voted ÷ Approved) × 100
95% = (1,850 ÷ 1,950) × 100
```

**Why it matters:**
- Indicates voter engagement
- Helps assess election validity
- Compares to previous elections

**Interpretation:**
```
90%+ → Excellent (nearly unanimous participation)
70-89% → Good (strong participation)
50-69% → Fair (reasonable participation)
<50% → Low (limited engagement)
```

---

## Reading the Data

### Understanding Trends

Each card shows trend information:

```
Voted: 1,850
↑ 150 this hour     ← Trend direction
        ↑ Shows increase/decrease over time
        ↑ Number represents change in the period
```

**Trend indicators:**
- **↑** (up arrow) = Increase (positive trend)
- **↓** (down arrow) = Decrease (negative trend)
- **→** (right arrow) = No change
- **Number** = How much changed (e.g., "+150")

**Time periods:**
- "this hour" = Last 60 minutes
- "today" = Since midnight
- "this week" = Last 7 days
- "this month" = Last 30 days

### Comparing to Previous Elections

To compare current election to past elections:

```
Current Election:       Previous Election:
Approved: 79.6%        Approved: 75.2%
Turnout: 95%           Turnout: 88%

Difference: +4.4% approval, +7% turnout
(Current is stronger)
```

---

## Filtering Statistics

### By Status

Filter statistics to see specific groups:

```
Filters applied:
Status: ☑ Approved

Results show only:
- Approved voters: 1,950
- Voted (approved only): 1,850
- Turnout: 95%

Pending and Suspended voters excluded from view
```

### By Date Range

View statistics for specific time periods:

```
Filters applied:
Date Range: 02/15 - 02/23

Statistics show only voters:
- Registered between dates
- Approved during that period
- Voted during that period
```

### By Region

If regional elections, filter by region:

```
Filters applied:
Region: Bayern

Shows statistics for:
- Only Bayern voters
- Bayern-specific turnout
- Bayern participation rates
```

### Combining Filters

Use multiple filters together:

```
Status: ☑ Approved
Region: Bayern
Date: 02/01 - 02/23

Shows: Bayern approved voters registered/acting in February
```

---

## Exporting Reports

### Export to CSV

For spreadsheet analysis:

**Steps:**
1. Click **[Export as CSV]** button (if available)
2. File downloads to your computer
3. Open with Excel, Google Sheets, or similar
4. Analyze data further

**What's included:**
- All voter data visible in the table
- All statistics and metrics
- Your selected filters are applied

### Export to PDF

For printing and sharing:

**Steps:**
1. Click **[Export as PDF]** button (if available)
2. File downloads to your computer
3. Open with PDF reader
4. Print or share

**What's included:**
- Summary statistics
- Voter list
- Approval status for each
- Generated date/time

### Export via Email

Some systems support email export:

**Steps:**
1. Click **[Email Report]** button
2. Confirm your email address
3. Check inbox for report link
4. Download from email

---

## Interpreting Results

### Election Status Indicators

Use statistics to assess election readiness:

```
EARLY STAGE (Registration Period):
- Total Voters: Growing
- Approved: 0-50%
- Pending: 50-100%
→ Status: Registration in progress, not ready yet

REVIEW STAGE (Commission Reviewing):
- Total Voters: Stable
- Approved: 50-80%
- Pending: 20-50%
→ Status: Reviewing voters, ongoing process

READY STAGE (Ready to Vote):
- Total Voters: Stable
- Approved: 80-100%
- Pending: 0-20%
→ Status: Ready for voting

VOTING STAGE (Voting Happening):
- Approved: Stable
- Voted: Growing
- Turnout: 0-100%
→ Status: Voting in progress

COMPLETE STAGE (After Voting Closes):
- Voted: Stable
- Turnout: Final percentage
→ Status: Ready for results
```

### Red Flags

Watch for concerning patterns:

🚩 **Very low approval rate**
- Problem: Commission far behind
- Action: Speed up reviews or get more reviewers

🚩 **High pending after deadline**
- Problem: Voters not reviewed
- Action: Fast-track remaining reviews

🚩 **Very low turnout**
- Problem: Voters not participating
- Action: Send reminders, extend deadline if allowed

🚩 **Suspicious patterns**
- Problem: Data doesn't match expectations
- Action: Investigate with administrator

### Positive Indicators

✅ **Steady growth in approved**
- Shows: Commission is working
- Expected: Steady increase week by week

✅ **Rising turnout**
- Shows: Voters are participating
- Expected: Increase as voting period continues

✅ **High approval + high turnout**
- Shows: Healthy, engaged election
- Expected: Election success

---

## Example Scenarios

### Scenario 1: Commission Review in Progress

```
Total: 5,000 voters
Approved: 2,000 (40%)
Pending: 3,000 (60%)
Voted: 100 (5%)

Interpretation:
- Still in heavy review phase
- Commission needs to approve 3,000 more voters
- Voting hasn't opened yet (too few approved)
- Status: On track, reviewing steadily
```

### Scenario 2: Election Going Well

```
Total: 1,000 voters
Approved: 950 (95%)
Pending: 50 (5%)
Voted: 850 (89% turnout)

Interpretation:
- Nearly all voters approved
- Excellent participation rate
- Only 50 voters not yet reviewed
- Status: Excellent, strong engagement
```

### Scenario 3: Struggling Election

```
Total: 1,000 voters
Approved: 800 (80%)
Pending: 200 (20%)
Voted: 300 (38% turnout)

Interpretation:
- Approval nearly complete
- BUT very low participation (38%)
- 700 approved voters haven't voted yet
- Status: Weak engagement, send reminders
```

---

## Troubleshooting

### ❌ "Statistics not updating"

**Problem:** Numbers look stale, don't match voting activity

**Solutions:**
1. ✅ Refresh page (F5 or Ctrl+R)
2. ✅ Wait a few seconds (may update with delay)
3. ✅ Clear browser cache
4. ✅ Check if filters are applied (may hide data)

---

### ❌ "Numbers don't add up"

**Problem:** Approved + Pending + Suspended ≠ Total

**Causes:**
- Suspended voters (may not be shown by default)
- Data from different time points
- System still processing

**Solutions:**
1. ✅ Clear filters to see all data
2. ✅ Check if Suspended voters are hidden
3. ✅ Refresh page for fresh data

---

### ❌ "Can't export reports"

**Problem:** Export button missing or not working

**Solutions:**
1. ✅ Check your role - May need Staff or higher
2. ✅ Check permissions - Admins can always export
3. ✅ Try different browser
4. ✅ Contact administrator for access

---

## Next Steps

👉 **Need to approve voters?** Go to [Managing Voters](./04-managing-voters.md)

👉 **Want to find specific voters?** Go to [Searching & Filtering](./03-searching-filtering.md)

👉 **Having issues?** Go to [Tips & Troubleshooting](./07-tips-troubleshooting.md)

---

## 🆘 Need Help?

- **Understanding metrics?** Review [Understanding the Metrics](#understanding-the-metrics) section
- **Can't see statistics?** See [Troubleshooting](#troubleshooting)
- **Using keyboard only?** See [Accessibility Guide](./08-accessibility.md)

---

**Happy voting! 🗳️**
