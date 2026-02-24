# User Guide: Viewing Demo Election Results

**For:** Organisation Members, Election Administrators, Election Observers
**Version:** 1.0
**Last Updated:** 2026-02-23

---

## Welcome!

This guide helps you understand and use the **Demo Election Results** pages in Public Digit. Whether you're testing an election setup, training members, or demonstrating the platform to stakeholders, this guide will walk you through every step.

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [What Are Demo Results?](#what-are-demo-results)
3. [Accessing Demo Results](#accessing-demo-results)
4. [Understanding the Results Page](#understanding-the-results-page)
5. [Reading the Data](#reading-the-data)
6. [Downloading & Printing](#downloading--printing)
7. [Frequently Asked Questions](#frequently-asked-questions)
8. [Troubleshooting](#troubleshooting)

---

## Quick Start

**In 3 easy steps:**

### Step 1: Log In
```
1. Go to Public Digit platform
2. Enter your email and password
3. Click "Sign In"
```

### Step 2: Navigate to Results
```
Option A - From Organisation Dashboard:
  1. Click your organisation name
  2. Look for "Demo Results" section
  3. Choose MODE 1 or MODE 2

Option B - Direct URL:
  MODE 1 (Global): https://your-domain.com/demo/global/result
  MODE 2 (Org):    https://your-domain.com/demo/result
```

### Step 3: View Results
```
✓ Scroll down to see all positions
✓ Download PDF for records
✓ Print for physical distribution
```

---

## What Are Demo Results?

### Demo Elections vs Real Elections

| Feature | Demo | Real |
|---------|------|------|
| **Purpose** | Testing & Training | Binding Elections |
| **Data** | Test/sample data | Official votes |
| **Can reset?** | Yes, unlimited | No |
| **Affects actual org?** | No | Yes |
| **Votes count?** | No | Yes |

### Two Types of Demo Results

#### MODE 1: Global Public Demo
**What it is:** A public demo that anyone can view

```
🌍 Visible to: All logged-in users
📊 Contains: Sample election data
🎯 Purpose: Platform showcase, onboarding
🔄 Reset: Can be reset for demos
```

**Use when:**
- Showcasing platform features to new users
- Training staff on how to interpret results
- Demonstrating to potential customers
- Getting feedback on platform interface

#### MODE 2: Organisation Demo
**What it is:** Your organisation's private test election

```
🏢 Visible to: Your organisation members only
📊 Contains: Your test data
🎯 Purpose: Testing before real election
🔄 Reset: Can be reset for new tests
```

**Use when:**
- Testing your election setup before going live
- Training your election officers
- Testing with your members
- Checking how results will look

---

## Accessing Demo Results

### From Your Organisation Dashboard

**Location:** Your organisation's main page

```
1. Log in to Public Digit
2. Click your organisation name (top of page)
3. Scroll down to "Demo Results" section
4. Choose your option:

   ┌─────────────────────────┐
   │   DEMO RESULTS          │
   │                         │
   │  [Organisation Demo]   <- MODE 2 (your org only)
   │  [Global Demo]        <- MODE 1 (public demo)
   └─────────────────────────┘
```

### Direct Links

**If you have the links:**

**Global Demo (anyone can view):**
```
https://your-domain.com/demo/global/result
```

**Organisation Demo (members only):**
```
https://your-domain.com/demo/result
```

### Via Menu

**Navigation menu path:**
```
Home → Your Organisation → Demo Results → Choose Mode
```

---

## Understanding the Results Page

### Page Layout Overview

```
┌─────────────────────────────────────────────────────────┐
│  MODE INDICATOR (Blue = Global / Purple = Organisation) │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📊 STATISTICS DASHBOARD                               │
│  ├─ Total Votes Cast: 1,234                            │
│  ├─ Positions: 5                                        │
│  ├─ Generated: Feb 23, 2026                            │
│  └─ Mode: MODE 1 or MODE 2                             │
│                                                          │
├─────────────────────────────────────────────────────────┤
│  [⬇ Download PDF]  [🖨 Print]                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📋 POSITION 1: PRESIDENT                              │
│  ├─ Rank 1: John Smith - 450 votes (35%)              │
│  ├─ Rank 2: Jane Doe - 380 votes (30%)                │
│  ├─ Rank 3: Bob Johnson - 320 votes (25%)             │
│  └─ Abstentions: 84 voters                             │
│                                                          │
│  📋 POSITION 2: VICE PRESIDENT                         │
│  [... similar structure ...]                            │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Components Explained

#### 1. MODE Indicator (Top)

**What it shows:**

```
Global Demo Mode          Organisation Demo Mode
├─ 🌍 Globe icon         ├─ 🏢 Building icon
├─ Blue background       ├─ Purple background
├─ MODE 1 badge          ├─ MODE 2 badge
└─ "Public testing"      └─ "Organisation ID: 1"
```

**Why it matters:**
- Tells you which demo you're viewing
- Shows audience (public vs private)
- Confirms right data for your needs

#### 2. Statistics Dashboard

**Four cards showing:**

| Card | Shows | Example |
|------|-------|---------|
| Votes | Total votes cast | 1,234 |
| Positions | Number of roles | 5 |
| Date | When generated | Feb 23, 2026 |
| Mode | Which demo type | MODE 1 |

**How to use:**
- Quick overview of election size
- Verify you're viewing right election
- Share numbers in meetings

#### 3. Action Buttons

| Button | Action | Use When |
|--------|--------|----------|
| Download PDF | Save as PDF file | Need permanent record |
| Print | Print to printer | Share physical copies |

#### 4. Position Cards (Main Content)

**For each position (President, Vice President, etc.):**

```
HEADER (Purple Gradient):
├─ Position Name: "PRESIDENT"
├─ Region/State: "Bayern" (if applicable)
├─ Total Votes: 1,234
└─ Abstentions: 84

CANDIDATE RESULTS:
├─ Rank 1: John Smith
│  ├─ Votes: 450
│  ├─ Percentage: 35%
│  └─ [Progress bar: ████████░░░░░]
│
├─ Rank 2: Jane Doe
│  ├─ Votes: 380
│  ├─ Percentage: 30%
│  └─ [Progress bar: ███████░░░░░░]
│
└─ Rank 3: Bob Johnson
   ├─ Votes: 320
   ├─ Percentage: 25%
   └─ [Progress bar: ██████░░░░░░░]

FOOTER:
├─ Candidates: 3
├─ To Select: 1
└─ Abstentions: 84
```

---

## Reading the Data

### How to Interpret Results

#### Vote Count & Percentage

```
Candidate: "John Smith"
Votes: 450
Percentage: 35%

Meaning:
- Out of 1,234 total votes
- John Smith received 450 votes
- That's 35% of all votes cast
```

#### Ranking

```
Rank 1: John Smith - 450 votes
Rank 2: Jane Doe - 380 votes
Rank 3: Bob Johnson - 320 votes

Meaning:
- Ranked by vote count
- Higher rank = more votes
- Clear winner at top
```

#### Progress Bar

```
Visual representation of percentage:

████████░░░░░ = 35% (proportional)
███████░░░░░░ = 30% (proportional)
██████░░░░░░░ = 25% (proportional)
```

**How to read:**
- Full bar = 100% of votes
- Length shows candidate's share
- Easy visual comparison

#### Abstentions

```
"Abstentions: 84 voters"

Meaning:
- 84 people voted "No" or abstained
- They didn't vote for any candidate
- Counted separately from vote totals
```

### Calculating Winner

**Who won the position?**

```
Look at: Rank 1 (top of the list)
  → Highest vote count
  → Highest percentage
  → Winner's name shown first

Example:
  ✓ John Smith (Rank 1, 450 votes) = WINNER
  ✗ Jane Doe (Rank 2, 380 votes)
  ✗ Bob Johnson (Rank 3, 320 votes)
```

### Understanding Vote Distribution

**Good distribution (votes spread):**
```
Candidate A: 400 votes (40%)
Candidate B: 300 votes (30%)
Candidate C: 300 votes (30%)

→ Close race, votes distributed evenly
```

**Skewed distribution (one clear winner):**
```
Candidate A: 700 votes (70%)
Candidate B: 200 votes (20%)
Candidate C: 100 votes (10%)

→ Clear preference for Candidate A
```

**Tied results (need tiebreaker):**
```
Candidate A: 500 votes (50%)
Candidate B: 500 votes (50%)

→ Exact tie, may need tiebreaker rules
```

---

## Downloading & Printing

### Download Results as PDF

**Step-by-step:**

```
1. Scroll to top of results page
2. Click [⬇ Download PDF] button
3. Your browser will download a PDF file
4. File name: demo_election_results_[date].pdf
5. Open with: Adobe Reader, web browser, etc.
```

**What's in the PDF:**

```
✓ Mode (Global or Organisation)
✓ All positions and candidates
✓ Vote counts and percentages
✓ Abstention numbers
✓ Generated date & time
✓ Public Digit branding
✓ Professional formatting
```

**PDF looks like:**

```
═══════════════════════════════════════════
  DEMO ELECTION RESULTS
═══════════════════════════════════════════

Generated: February 23, 2026 at 3:45 PM
Total Votes: 1,234

─────────────────────────────────────────
PRESIDENT
─────────────────────────────────────────

Rank | Name         | Votes | Percentage
─────┼──────────────┼───────┼───────────
  1  | John Smith   |  450  |   35.0%
  2  | Jane Doe     |  380  |   30.0%
  3  | Bob Johnson  |  320  |   25.0%
    Abstentions      84      6.8%

[Same format for other positions...]

═══════════════════════════════════════════
Generated by Public Digit Technology
═══════════════════════════════════════════
```

**File size:** ~500KB (images included)
**Compatibility:** All PDF readers
**Printable:** Yes, optimized for printing

### Print Results

**Option 1: Print from browser**

```
1. Click [🖨 Print] button on page
   OR
2. Use keyboard: Ctrl+P (Windows) / Cmd+P (Mac)
3. Choose printer
4. Click "Print"
```

**Option 2: Print from PDF**

```
1. Download PDF first
2. Open with Adobe Reader
3. Click Print icon
4. Choose printer
5. Click "Print"
```

**Print settings recommendation:**

```
Color Mode: Color (for readability)
Paper Size: A4 or Letter
Orientation: Portrait
Margins: Normal
Copies: As needed
```

**Will print:**

```
✓ MODE indicator banner
✓ Statistics cards
✓ All position results
✓ Candidate lists with votes
✓ Charts and visualizations
✓ Professional formatting
```

---

## Frequently Asked Questions

### General Questions

**Q: What's the difference between MODE 1 and MODE 2?**

A:
```
MODE 1 (Global Demo):
  • Visible to EVERYONE (all users)
  • Public testing
  • Not tied to your organisation
  • Good for showcasing platform

MODE 2 (Organisation Demo):
  • Visible to YOUR MEMBERS ONLY
  • Organisation-specific testing
  • Uses your organisation data
  • Good for internal testing
```

**Q: Can I reset the demo results?**

A:
```
Yes! Demo elections can be completely reset.
This allows unlimited testing without affecting real elections.

Contact: Your election administrator or support team
```

**Q: Are demo results official?**

A:
```
No. Demo results are:
  ✗ Not binding
  ✗ Not official votes
  ✗ Test/sample data only
  ✓ Can be reset anytime
  ✓ For testing purposes only
```

**Q: How often are results updated?**

A:
```
Demo results are static once generated.

To see new results:
  1. Run a new demo election
  2. Cast new test votes
  3. Refresh the page
  4. View updated results
```

### Accessing Results

**Q: I can't see my organisation's demo results**

A:
```
Check:
  ✓ Are you logged in?
  ✓ Are you a member of the organisation?
  ✓ Did you create demo posts/votes?
  ✓ Try direct link: /demo/result

If still not working:
  → Contact your administrator
  → Check browser console for errors (F12)
```

**Q: Which link should I use?**

A:
```
Use MODE 1 (/demo/global/result) if:
  • Showing to people outside your org
  • Training on platform features
  • Demonstrating to stakeholders
  • You want public access

Use MODE 2 (/demo/result) if:
  • Testing YOUR organisation's setup
  • Training your members
  • Internal testing only
  • Want privacy/security
```

### Understanding Data

**Q: Why is my total 1,234 but candidates have 1,150?**

A:
```
The difference is abstentions:

Total Votes: 1,234
├─ Candidate votes: 1,150
└─ Abstentions (no vote): 84
─────────────────────────
  Total: 1,234 ✓

Abstentions counted separately to show people
who voted but didn't select a candidate.
```

**Q: What does "abstention" mean?**

A:
```
An abstention means:
  • Person voted (participated)
  • But didn't select a candidate
  • Chose "No Vote" option
  • Their participation is recorded
  • But their vote isn't for any candidate

Example:
  - 1,234 people participated
  - 84 abstained (no vote)
  - 1,150 voted for candidates
```

**Q: How are candidates ranked?**

A:
```
By vote count (highest to lowest):

Rank 1: Most votes (clear leader)
Rank 2: Second most votes
Rank 3: Third most votes
...

If tied: Listed alphabetically
```

### Downloading & Printing

**Q: Why is the PDF so large?**

A:
```
Includes:
  • High-resolution charts
  • Professional formatting
  • Organisational branding
  • Full election data
  • Metadata

Size: ~500KB (typical)
Reduces to ~200KB when compressed
```

**Q: Can I edit the PDF?**

A:
```
The PDF is read-only for security.

To modify:
  1. Take a screenshot
  2. Use graphics editor (Photoshop, GIMP)
  3. Or edit HTML before printing

Original PDF cannot be edited.
```

**Q: When I print, some content is cut off**

A:
```
Check printer settings:

✓ Margins: Normal or minimal
✓ Scale: 100% (not "fit to page")
✓ Orientation: Portrait
✓ Color mode: Color

Try:
  1. Browser print: Ctrl+P
  2. Uncheck "Print headers/footers"
  3. Set margins to 0.5 inch
  4. Click "More settings"
```

---

## Troubleshooting

### Common Issues & Solutions

#### Issue: Page shows "No demo posts found"

**What it means:**
```
No demo election data exists yet.
Results page is empty.
```

**Solutions:**

```
1. Create demo posts:
   → Go to Election Setup
   → Create demo positions
   → Assign demo candidates

2. Add demo votes:
   → Start a demo election
   → Test voting process
   → Submit test votes

3. Then refresh results page
```

#### Issue: Can't access /demo/result (MODE 2)

**Error:**
```
404 Not Found
Access Denied
Empty results
```

**Causes & Fixes:**

```
1. Not logged in?
   → Log in first
   → Then try link

2. Not an organisation member?
   → Join organisation
   → Ask admin to add you

3. Organisation hasn't set up demo?
   → Create demo election first
   → Contact organisation admin

4. Session expired?
   → Log out and log back in
   → Try again
```

#### Issue: PDF download fails

**Error:**
```
"Failed to download"
"Connection error"
"Page shows loading"
```

**Solutions:**

```
1. Check internet connection
   → Try reloading page
   → Try different network

2. Clear browser cache
   → Chrome: Ctrl+Shift+Delete
   → Firefox: Ctrl+Shift+Delete
   → Safari: Cmd+Option+E

3. Try different browser
   → Firefox, Chrome, Safari, Edge

4. Try printing as PDF instead
   → Ctrl+P → "Save as PDF"

5. Contact support if still fails
```

#### Issue: Results look wrong (incorrect votes/percentages)

**Check:**

```
1. Did you reset demo recently?
   → Old votes deleted
   → Need new test votes

2. Are percentages adding up?
   → Should total ~100% (excluding abstentions)
   → Check abstention count

3. Is the right mode selected?
   → MODE 1: global results
   → MODE 2: organisation results
   → Verify MODE indicator at top

4. Are you seeing MODE 1 instead of MODE 2?
   → Clear browser cache
   → Log out and in again
   → Try incognito/private window
```

#### Issue: Can't see MODE 1 link from organisation page

**Solution:**

```
Direct link works:
  https://your-domain.com/demo/global/result

Or scroll organisation page to find
"Demo Results" section with both links.

If section missing:
  → Admin may have hidden it
  → Contact organisation admin
```

---

## Tips & Best Practices

### Best Time to Use Demo Results

```
✓ When: Planning an election
  → Test your setup before going live
  → See how results will look
  → Train staff on data interpretation

✓ When: Member training
  → Show how voting works
  → Demonstrate result visualization
  → Answer common questions

✓ When: Stakeholder presentations
  → Showcase platform features
  → Show professional output (PDF)
  → Demonstrate transparency

✓ When: Testing edge cases
  → Very close results
  → High abstention rates
  → Large number of candidates
```

### Sharing Results

**Share PDF with:**
```
✓ Committee members (email)
✓ Board of directors (printed)
✓ Member communication (link)
✓ Stakeholders (documentation)

Best practice:
  • Email: PDF attachment
  • Web: Link to /demo/result
  • Print: Use print function
  • Archive: Save PDF with date
```

**Share web link with:**
```
✓ Colleagues (show real-time)
✓ Team members (shared dashboard)
✓ Stakeholders (live demo)
✓ Training groups (interactive)

Include note:
  "This is a demo using test data
   for training/evaluation purposes"
```

### Data Privacy

```
Remember:
  • MODE 1 is PUBLIC
  • MODE 2 is ORGANISATION ONLY
  • Don't share MODE 2 externally
  • Use appropriate access controls

Do:
  ✓ Share MODE 1 widely
  ✓ Keep MODE 2 internal
  ✓ Use "share" feature for MODE 2
  ✓ Password protect PDFs if needed
```

---

## Getting Help

**If you need assistance:**

```
1. Check this guide
   → Search for your question
   → Look in FAQ section

2. Contact your organisation admin
   → They manage your election
   → Can reset/troubleshoot

3. Contact Public Digit support
   → support@publicdigit.tech
   → Help with technical issues
   → Account problems

4. Check platform help
   → In-app support chat
   → Video tutorials
   → Documentation links
```

**When contacting support, provide:**

```
✓ What you're trying to do
✓ Which link/page you're on
✓ Error message (if any)
✓ Screenshots (if helpful)
✓ Browser & device type
✓ When the problem started
```

---

## Summary

**You now know how to:**

```
✓ Access both MODE 1 and MODE 2 results
✓ Understand results page layout
✓ Read and interpret the data
✓ Download and print results
✓ Troubleshoot common issues
✓ Share results appropriately
✓ Use results for training/planning
```

**Next steps:**

```
1. View your organisation's demo results
2. Explore MODE 1 global demo
3. Download a PDF to see format
4. Print a sample for staff
5. Use for training/presentations
```

---

## Questions or Feedback?

We'd love to hear from you!

```
Email: support@publicdigit.tech
Subject: Demo Results Feedback

Include:
- What worked well
- What was confusing
- Suggestions for improvement
- Your use case/scenario
```

---

**Happy demoing!** 🎉

Public Digit makes democratic elections simple, transparent, and accessible.

