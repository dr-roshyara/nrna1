# Creating Committees — User Manual

**For Organization Administrators & Committee Organizers**

---

## Table of Contents

1. [What is a Committee?](#what-is-a-committee)
2. [Before You Start](#before-you-start)
3. [Step 1: Check Your Governance Levels](#step-1-check-your-governance-levels)
4. [Step 2: Define Geographic Units](#step-2-define-geographic-units)
5. [Step 3: Create the Committee](#step-3-create-the-committee)
6. [Step 4: Add Members](#step-4-add-members)
7. [Common Scenarios](#common-scenarios)
8. [Troubleshooting](#troubleshooting)

---

## What is a Committee?

A **committee** is a formal governing body within your organization.

In NRNA (Non-Resident Nepalese Association), you have committees at different levels:

```
┌─────────────────────────────────────────────────┐
│  ICC (International Coordination Committee)      │  Level 0
│  └─ Covers the ENTIRE world                      │
├─────────────────────────────────────────────────┤
│  Regional Committees (Europe, Asia, Americas)   │  Level 1
│  └─ Each covers a major region                  │
├─────────────────────────────────────────────────┤
│  NCC (National Coordination Committee)          │  Level 2
│  └─ One per country (Nepal, India, etc.)        │
├─────────────────────────────────────────────────┤
│  State/Local Coordination Committees (LCC)      │  Level 3
│  └─ One per state within a country              │
└─────────────────────────────────────────────────┘
```

Each committee:
- ✅ Has a name (e.g., "Nepal National Coordination Committee")
- ✅ Belongs to a governance level (ICC, Regional, NCC, LCC)
- ✅ Covers a geographic area (World, Europe, Nepal, Kathmandu)
- ✅ Has members (elected or appointed)
- ✅ Has a chairperson and officers

---

## Before You Start

### Prerequisites

You need to have **organization admin** access. If you don't, ask your organization's main administrator.

### Information You'll Need

1. **Committee Name** — What do you want to call it?
   - Example: "Nepal National Coordination Committee"

2. **Governance Level** — Which level of authority?
   - ICC (Level 0) — International
   - Regional (Level 1) — Multi-country region
   - NCC (Level 2) — National
   - LCC (Level 3) — State/Local

3. **Geographic Coverage** — Which area does it cover?
   - Example: "Nepal" (country), "Kathmandu" (state)

---

## Step 1: Check Your Governance Levels

Before creating any committee, verify that your **governance levels are configured**.

### What Are Governance Levels?

Governance levels are the **types of committees** your organization uses:
- **Level 0:** ICC (International Committee)
- **Level 1:** Regional Committee (e.g., Europe)
- **Level 2:** National Committee (e.g., Nepal)
- **Level 3:** State Committee (e.g., Kathmandu)

### How to Check

**Go to:** Organization Settings → Governance Levels

You should see a table:

```
┌──────────────────────────────────────────┐
│ Governance Level Configuration           │
├──────────┬────────────────┬──────────────┤
│ Level    │ Name           │ Active?      │
├──────────┼────────────────┼──────────────┤
│ 0        │ ICC Global     │ ✅ Yes       │
│ 1        │ Regional Coor. │ ✅ Yes       │
│ 2        │ National Coor. │ ✅ Yes       │
│ 3        │ State Coor.    │ ✅ Yes       │
└──────────┴────────────────┴──────────────┘
```

**If you see this:** ✅ You're ready to proceed.

**If you don't see it:** ⚠️ Ask your administrator to set up governance levels. Or see [Troubleshooting](#troubleshooting).

---

## Step 2: Define Geographic Units

Geographic units are the **areas your committees cover**.

Examples:
```
Level 0: World (the entire globe)
Level 1: Europe, Asia, Americas
Level 2: Nepal, India, Australia
Level 3: Kathmandu, Pokhara, Biratnagar (states in Nepal)
```

### How to Define Geographic Units

**Go to:** Organization Settings → Geographic Units

### Option A: Manual Entry (Small Organizations)

Click **"+ Add Geographic Unit"**

```
┌────────────────────────────────────┐
│ New Geographic Unit                │
├────────────────────────────────────┤
│ Code: [Europe           ]           │
│ Name: [European Region  ]           │
│ Level: [1 - Regional ▼] │
│ Active: [✓ Yes]         │
│                                     │
│ [ Save ]  [ Cancel ]                │
└────────────────────────────────────┘
```

Fill in:
- **Code:** Short identifier (e.g., "REG-EU")
- **Name:** Full name (e.g., "European Region")
- **Level:** Which governance level (e.g., 1 for Regional)
- **Active:** Leave checked (✓)

Click **Save**.

**Repeat** for each geographic area you need.

### Option B: Bulk Import (Large Organizations)

If you have many units, use **Excel Import**.

Click **"Import from Excel"** and upload a file with this format:

```
Code        Name                    Level   Active
REG-EU      European Region         1       Yes
REG-AS      Asian Region           1       Yes
NCC-NP      Nepal National          2       Yes
NCC-IN      India National          2       Yes
SLC-KTM     Kathmandu State         3       Yes
SLC-PKH     Pokhara State           3       Yes
```

---

## Step 3: Create the Committee

Now you're ready to create a committee.

**Go to:** Committees → Create New Committee

### The Committee Creation Form

You'll see a form like this:

```
┌──────────────────────────────────────────┐
│ Create a New Committee                   │
├──────────────────────────────────────────┤
│                                          │
│ Committee Name:                          │
│ [Nepal National Coordination Committee ]  │
│                                          │
│ Governance Level:                        │
│ [2 - National Committee        ▼]        │
│                                          │
│ Geographic Area:                         │
│ [NCC-NP - Nepal              ▼]        │
│                                          │
│ Description: (optional)                  │
│ [________________________________________]│
│                                          │
│   [ Create Committee ]  [ Cancel ]       │
└──────────────────────────────────────────┘
```

### Fill in Each Field

**1. Committee Name**
- What you want to call this committee
- Example: "Nepal National Coordination Committee"
- Be specific and descriptive

**2. Governance Level**
- The type/level of committee
- Dropdown shows: Level 0 (ICC), Level 1 (Regional), etc.
- **Choose the level that matches your committee**

**3. Geographic Area**
- Where the committee operates
- Dropdown shows: World, Europe, Nepal, Kathmandu, etc.
- **Must match the governance level**
  - If Level 0 (ICC) → choose World
  - If Level 2 (National) → choose Nepal or India
  - If Level 3 (State) → choose Kathmandu or Pokhara

**4. Description** (optional)
- Add any extra details
- Example: "Coordinates national elections and member services"

### Validation Rules

The system **automatically checks** that your selections are valid:

✅ **Valid combinations:**
- Level 0 (ICC) + World
- Level 1 (Regional) + Europe
- Level 2 (National) + Nepal
- Level 3 (State) + Kathmandu

❌ **Invalid combinations** (system will reject):
- Level 3 (State) + Europe (state can't cover continent)
- Level 0 (ICC) + Nepal (ICC covers world, not one country)
- Level 2 (National) + World (national can't cover world)

**If you get an error:** Check that your governance level and geographic area match. Ask your administrator if you're unsure.

### Create the Committee

Click **"Create Committee"**

**Success message:**
```
✅ Committee "Nepal National Coordination Committee" created successfully!
```

You can now:
- View the committee details
- Add members
- Set officers/chairperson
- Make electoral decisions

---

## Step 4: Add Members

Once your committee is created, you can add members.

**Go to:** Committee → Members → Add Member

### Member Requirements

Not everyone can join every committee. The system **automatically checks eligibility**:

```
Rule: Members must be from the committee's geographic area (or above)

Example:
┌─────────────────────────────────────────────┐
│ Nepal National Committee (Level 2, Nepal)   │
├─────────────────────────────────────────────┤
│ ✅ Can add: NRNA members registered in Nepal│
│ ✅ Can add: Members from Kathmandu (below) │
│ ❌ Cannot add: Members registered in Europe │
│ ❌ Cannot add: Members from India          │
└─────────────────────────────────────────────┘
```

### Add a Member

**Go to:** Committee → Members → "Add Member"

```
┌───────────────────────────────────────┐
│ Add Member to Committee               │
├───────────────────────────────────────┤
│ Select Member:                        │
│ [Search for member name    ▼]        │
│                                       │
│ Role:                                 │
│ [Chairperson  ▼]                    │
│   Options:                            │
│   - Chairperson                       │
│   - Co-Chairperson                    │
│   - Treasurer                         │
│   - Secretary                         │
│   - Member                            │
│                                       │
│ [ Add Member ]  [ Cancel ]            │
└───────────────────────────────────────┘
```

**Fill in:**
1. **Select Member** — Search for the person's name
2. **Role** — What position will they have?
3. Click **Add Member**

**If you get "Member not eligible":**
- The member's home location doesn't match the committee's area
- They need to be from the committee's region (or a sub-region)
- Ask the member to update their address, or contact your administrator

---

## Common Scenarios

### Scenario 1: Create Regional Committee (Europe)

**Your goal:** Start a European regional committee

**Steps:**
1. **Governance Level:** Choose "1 - Regional Committee"
2. **Geographic Area:** Choose "REG-EU" or similar
3. **Name:** "European Regional Coordination Committee"
4. Click **Create**

**What it covers:** All NRNA members in Europe

**Who can join:**
- Members registered in Europe
- Members from individual European countries (if those exist at Level 2)

---

### Scenario 2: Create National Committee (Nepal)

**Your goal:** Establish NCC for Nepal

**Steps:**
1. **Governance Level:** Choose "2 - National Committee"
2. **Geographic Area:** Choose "NCC-NP" (Nepal)
3. **Name:** "Nepal National Coordination Committee"
4. Click **Create**

**What it covers:** All NRNA members in Nepal

**Who can join:**
- Members registered in Nepal
- Members from Kathmandu, Pokhara, etc. (states within Nepal)

---

### Scenario 3: Create State Committee (Kathmandu)

**Your goal:** Form LCC for Kathmandu state

**Steps:**
1. **Governance Level:** Choose "3 - State Coordination Committee"
2. **Geographic Area:** Choose "SLC-KTM" (Kathmandu)
3. **Name:** "Kathmandu State Coordination Committee"
4. Click **Create**

**What it covers:** NRNA members in Kathmandu

**Who can join:**
- Members registered in Kathmandu only

---

### Scenario 4: Create ICC (International Committee)

**Your goal:** Establish the global ICC

**Steps:**
1. **Governance Level:** Choose "0 - ICC Global"
2. **Geographic Area:** Choose "World"
3. **Name:** "International Coordination Committee"
4. Click **Create**

**What it covers:** All NRNA members globally

**Who can join:**
- Any NRNA member, anywhere in the world
- This is the highest level

---

## Troubleshooting

### Problem 1: "Geographic Area dropdown is empty"

**Cause:** You haven't created any geographic units yet.

**Solution:**
1. Go to Settings → Geographic Units
2. Add geographic units (see [Step 2](#step-2-define-geographic-units))
3. Try creating the committee again

---

### Problem 2: "Invalid governance assignment error"

**Cause:** You chose a governance level and geographic area that don't match.

**Example:**
```
❌ Wrong: Level 3 (State) + Europe (continent)
   → States can't cover continents
   
✅ Right: Level 3 (State) + Kathmandu
   → State committee covers its state
```

**Solution:**
- Check the table below for valid combinations
- Ask your administrator if you're unsure

---

### Problem 3: "Member not eligible to join"

**Cause:** The member's home location doesn't fit the committee.

**Solution:**
- Check the member's profile for their registered location
- If wrong, update their profile first
- Then try adding them again

**Rule:** Members must be from the committee's area or a sub-area:
```
Kathmandu Committee (Level 3, Kathmandu)
  ├── ✅ Can add: Members from Kathmandu
  ├── ❌ Cannot add: Members from Pokhara
  ├── ❌ Cannot add: Members from India
  └── ❌ Cannot add: Members from Europe
```

---

### Problem 4: "Committee already exists"

**Cause:** A committee with this name/location already exists.

**Solution:**
- Use a different name, or
- Update the existing committee instead of creating a new one

---

### Problem 5: "Permission denied"

**Cause:** Your account doesn't have admin rights.

**Solution:**
- Contact your organization's main administrator
- Ask them to grant you "Committee Manager" role

---

## Valid Committee Combinations

This table shows which **governance level** goes with which **geographic area**:

```
┌─────────────┬─────────────────────────────────┬──────────────────┐
│ Gov Level   │ Description                     │ Geographic Area  │
├─────────────┼─────────────────────────────────┼──────────────────┤
│ 0           │ International Coordination      │ World            │
│             │ Committee (ICC)                 │                  │
├─────────────┼─────────────────────────────────┼──────────────────┤
│ 1           │ Regional Coordination Committee │ Europe, Asia,    │
│             │ (RCC)                          │ Americas, Africa │
├─────────────┼─────────────────────────────────┼──────────────────┤
│ 2           │ National Coordination Committee │ Nepal, India,    │
│             │ (NCC)                          │ USA, Australia   │
├─────────────┼─────────────────────────────────┼──────────────────┤
│ 3           │ State Coordination Committee    │ Kathmandu,       │
│             │ (LCC)                          │ Pokhara,         │
│             │                                │ Biratnagar, etc. │
└─────────────┴─────────────────────────────────┴──────────────────┘
```

**Rule:** Each level's geographic area must fit that level's scope.

---

## After Creating the Committee

### What Happens Next?

Once created, your committee:
- ✅ Has a unique ID (UUID)
- ✅ Is recorded in the system permanently
- ✅ Can accept members
- ✅ Can hold elections
- ✅ Can make decisions
- ✅ Appears on your organization's roster

### Next Steps

1. **Add members** (see [Step 4](#step-4-add-members))
2. **Appoint officers** (Chairperson, Treasurer, Secretary)
3. **Set up elections** (if elections are enabled)
4. **Configure committees rules** (voting, quorum, etc.)

---

## Quick Reference

### Committee Hierarchy

```
Level 0 (ICC Global)
    │
    ├─→ Level 1 (Regional) — Europe, Asia, Americas
    │       │
    │       ├─→ Level 2 (National) — Nepal, India, etc.
    │       │       │
    │       │       ├─→ Level 3 (State) — Kathmandu, etc.
    │       │       │
    │       │       └─→ Reporting line to National
    │       │
    │       └─→ Reporting line to International
    │
    └─→ Reporting line to ICC
```

### Membership Rules

| Member Location | Can Join | Committee Level |
|-----------------|----------|-----------------|
| Nepal           | ICC      | ✅ Yes (global) |
| Nepal           | Regional | ✅ Yes (if Asia) |
| Nepal           | National | ✅ Yes (Nepal) |
| Nepal           | State    | ✅ Yes (if Kathmandu) |
| India           | Nepal NCC | ❌ No (wrong country) |

---

## Support

**Need help?**

- 📧 Email: [support@example.com](mailto:support@example.com)
- 💬 Contact your organization administrator
- 📖 Check the FAQ below

---

## FAQ

**Q: Can I create multiple committees at the same level?**  
A: Yes! You can have multiple regional committees (Europe, Asia) or multiple state committees (Kathmandu, Pokhara).

**Q: What if I made a mistake creating a committee?**  
A: Contact your administrator. They can edit or delete it.

**Q: Can I move members between committees?**  
A: Yes, use the member management interface. Click "Transfer Member" and choose the new committee.

**Q: How many members can a committee have?**  
A: Unlimited! But many organizations set a quorum size.

**Q: Do committees need to have officers?**  
A: Recommended, but not required. You can add them anytime.

**Q: What if a member moves to a different country?**  
A: Update their profile address. The system will automatically check if they're still eligible.

---

**Last Updated:** 2026-05-13  
**Version:** 1.0  
**Status:** Ready for deployment
