# Points Ledger: The Immutable Audit Trail

The **points ledger** is a permanent, immutable record of every point transaction. This guide explains how it works and how to use it for audits.

---

## What is the Points Ledger?

A **financial-journal-style** ledger that records every point change, forever.

**Key principle:** Immutable = Can't be edited or deleted. Only appended.

Like a bank statement: you don't edit old transactions; you make corrections as new entries.

---

## Why Is It Immutable?

**To prevent fraud:**
- ✓ Admins can't secretly remove points
- ✓ Members can see their full history
- ✓ Auditors can verify every transaction
- ✓ Disputes can be resolved with clear evidence

**To maintain integrity:**
- ✓ One source of truth
- ✓ No hidden changes
- ✓ Full transparency
- ✓ Legal/regulatory compliance

---

## What Gets Recorded?

Every ledger entry shows:

```
Entry ID: LDG-2026-04-15-001
User: Dr. Patel
Contribution ID: CONTRIB-567
Points: +1,440
Action: earned
Reason: "Track: major, Proof: institutional, Outcome bonus: +150"
Created By: Admin (Sarah)
Date: 2026-04-15 14:32:15
Timestamp: Permanent
```

### Fields Explained

**Points**: Can be positive (+earned) or negative (-adjustment)

**Action**: Type of transaction
- `earned` — Points awarded from approved contribution
- `spent` — Points used (rare, for reward systems)
- `adjusted` — Correction or penalty
- `appealed` — Appeal reversal

**Reason**: Human-readable explanation of calculation or change

**Created By**: Admin who made the entry (for audit trail)

---

## Types of Ledger Entries

### 1. Earned (Most Common)

When a contribution is **approved**:

```
Ledger Entry
User: Meena
Action: earned
Points: +107
Reason: "Contribution: Translation services. Track: standard, Hours: 6, 
         Skills: translation + legal (1.2×), Proof: document (0.8×), 
         Sustainability: one-time, Outcome: none"
Created: 2026-04-10 10:15
```

Member gets their points, ledger records why.

### 2. Adjusted (Corrections)

If you made a mistake or discovered fraud:

```
Ledger Entry
User: Raj
Action: adjusted
Points: -50
Reason: "Correcting overcount. Original contribution claimed 10 hours 
        but participant list shows 5 hours. Reducing points from 100 to 50."
Created: 2026-04-12 14:22
Adjusted By: Admin (Ahmed)
```

**The original entry stays.** You add a negative correction. Total is correct, full history is visible.

### 3. Spent (Reward System)

If members can **spend points** on rewards (optional feature):

```
Ledger Entry
User: Dr. Patel
Action: spent
Points: -300
Reason: "Redeemed for conference registration discount"
Created: 2026-04-08 09:50
```

Not enabled by default, but possible future extension.

### 4. Appealed (Reversals)

If a member **appealed** and won:

```
Ledger Entry
User: Ahmed
Action: appealed
Points: +50
Reason: "Appeal approved. Original contribution rejected due to missing 
        proof. Member provided additional evidence. Contribution now approved."
Created: 2026-04-15 11:33
```

Original rejection stands (for record), approval is recorded as new entry.

---

## Viewing the Ledger

### As an Admin

**View all points for one user:**
- Go to User Profile → Points Ledger
- See every transaction in order
- Click any entry for details

**Audit all contributions:**
- Admin Dashboard → Ledger → Filter by:
  - Date range
  - User
  - Action type
  - Points range

**Sample view:**
```
User: Dr. Patel

2026-04-15  earned    +150  Scholarship program (outcome bonus)
2026-04-15  earned    +1,440  Scholarship program (base)
2026-04-12  earned    +78    English workshop leadership
2026-04-10  earned    +107   Translation work
2026-04-08  adjusted  -25    Corrected workshop hours
2026-04-05  earned    +52    Mentoring session
─────────────────────────────
Total: 1,802 points
```

### As a Member

Members can **view their own ledger** for transparency:

- Go to Contributions → View Ledger
- See every point they earned
- Understand how points were calculated
- Spot any errors

---

## Using the Ledger for Audits

### Weekly Audit

Check for **obvious errors**:

```
SELECT COUNT(*) as num_entries, SUM(points) as total_points
FROM points_ledger
WHERE action = 'earned'
AND created_at >= NOW() - INTERVAL 7 DAY;

Result: 47 entries, +6,820 points
```

"This week: 47 contributions approved, 6,820 points earned. Looks reasonable."

### Monthly Review

Look for **patterns or anomalies**:

```
SELECT user_id, COUNT(*) as contributions, SUM(points) as total_points
FROM points_ledger
WHERE action = 'earned'
AND MONTH(created_at) = 4
GROUP BY user_id
ORDER BY total_points DESC;
```

**Red flags to spot:**
- Same person with 100+ contributions in one week (micro spam?)
- Sudden jump in points (might be justified, or might be inflated)
- Corrections outweighing original entries (quality control issue?)

### Quarterly Deep Dive

**Verify integrity:**
1. Pick 10 random ledger entries
2. Find the original contribution in the system
3. Check: Do calculated points match ledger?
4. Check: Is proof correct?
5. If discrepancy found, note it for correction

---

## Making Corrections

**If you find a mistake:**

**Don't edit the original.** Instead:

1. Create a new ledger entry
2. Action: `adjusted`
3. Points: Negative (to reverse the overpayment)
4. Reason: Explain what was wrong

Example:

```
Original Entry (2026-04-10)
User: Raj
Points: +100
Reason: "Workshop, 10 hours"

Correction Entry (2026-04-15)
User: Raj
Points: -50
Reason: "Audit found workshop was 5 hours, not 10. 
         Reducing points by 50 to correct from 100 to 50."

Result: Raj now has 50 points (correct), 
        but full history is visible (shows correction was made)
```

---

## Common Scenarios

### Scenario 1: Duplicate Entry

**Found**: Dr. Patel has two entries for the same contribution (1,440 + 1,440 = 2,880 points)

**Fix:**
1. Create correction entry: `-1,440`
2. Reason: "Duplicate entry. Removed one copy of scholarship fund contribution."
3. Result: 1,440 points (correct)

---

### Scenario 2: Inflated Hours

**Found**: Raj's workshop entry claims 10 hours, but attendance sheet shows 5 hours

**Fix:**
1. Create correction entry: `-50` (to reduce from 100 to 50)
2. Reason: "Corrected based on attendance records. Hours reduced from 10 to 5."
3. Result: 50 points (correct)

---

### Scenario 3: Outcome Bonus Adjustment

**Found**: You gave +200 outcome bonus, but member only impacted 150 people (should be +100)

**Fix:**
1. Create correction entry: `-100`
2. Reason: "Adjusted outcome bonus. Impact was 150 people, not 300+. Reducing from +200 to +100."
3. Result: Correct final points

---

## Reading the Ledger Formula

Each "earned" entry includes calculation details:

```
Points: +1,440
Reason: "Track: major | Hours: 60 | Base: 600 | Tier: +200 | 
         Skills: 3 unique (1.5×) | Proof: institutional (1.2×) | 
         Sustainability: one-time (1.0×) | Formula: (600+200)×1.5×1.2×1.0 = 1,440 | 
         Outcome: none"
```

**Reading it:**
- `Track: major` — This is a major-track contribution
- `Hours: 60` — 60 hours of work
- `Base: 600` — 60 hours × 10 = 600 points
- `Tier: +200` — Major track tier bonus = +200
- `Skills: 3 unique (1.5×)` — Cross-pollination synergy = 1.5× multiplier
- `Proof: institutional (1.2×)` — Official letter = 1.2× multiplier
- `Sustainability: one-time (1.0×)` — Not recurring = 1.0× (no bonus)
- `Formula: (600+200)×1.5×1.2×1.0 = 1,440` — Final calculation
- `Outcome: none` — No outcome bonus added

---

## Preventing Fraud

### Red Flags to Watch

❌ **Too many micro contributions in one week**
- Normal: 3–5 per person per week
- Suspicious: 10+ per person per week

❌ **Vague descriptions with high points**
- "Helped with community" (100 points claimed, no details)
- Requires proof/clarification

❌ **Multiple Admins approving same person**
- One person shouldn't be reviewing their own contributions
- Conflict of interest check

❌ **Correction patterns**
- If one person has 5+ corrections, investigate

### Preventive Measures

✓ **Two-admin rule** — For major track (40+ hours), require two admins to approve  
✓ **Monthly audits** — Check top 10 contributors  
✓ **Member spot-checks** — Ask for evidence on 10% of contributions  
✓ **Transparency** — Members can see the ledger (deters fraud)  

---

## Questions?

- **"Can a member request a correction?"** — Yes. They can appeal or contact an admin.
- **"What if an admin made a fraudulent entry?"** — It's visible in the ledger. Senior admin can add a reversal entry and investigate the admin.
- **"How long is the ledger kept?"** — Forever. It's permanent.
- **"Can I see who approved each entry?"** — Yes, every entry shows the admin who created it.

---

**Back**: [Outcome Bonus](02-outcome-bonus.md)

**Admin Dashboard**: Return to main admin guide
