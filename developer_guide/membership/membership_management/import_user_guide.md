# Import User Guide — Participants, Members & Voters

This guide explains how to bulk-import people into your organisation using Excel or CSV files.
It is written for **organisation owners and administrators**.

---

## Table of Contents

1. [Before You Start — Key Concepts](#1-before-you-start--key-concepts)
2. [Importing Participants (Staff / Guests / Committee)](#2-importing-participants)
3. [Importing Members](#3-importing-members)
4. [Importing Voters for an Election](#4-importing-voters-for-an-election)
5. [Common Errors & How to Fix Them](#5-common-errors--how-to-fix-them)

---

## 1. Before You Start — Key Concepts

### Three different lists, three different purposes

| What | Who is on it | Why it matters |
|------|-------------|----------------|
| **Participants** | Staff, guests, election committee | Operational roles within the organisation |
| **Members** | Paid/approved formal members | Formal membership; determines voting eligibility |
| **Voters** | Members registered for a specific election | Per-election voter roll; scoped to one election |

> **Important:** These three lists are independent. Adding someone as a participant does **not** automatically make them a member or a voter. Each list must be managed separately.

### Who can import?

| Import type | Required role |
|-------------|--------------|
| Participants | Owner **or** Admin |
| Members | Owner **or** Admin |
| Voters | Owner, Admin, or Election Officer (for that election) |

### What format do files need to be?

All imports accept **Excel (`.xlsx`, `.xls`)** and **CSV (`.csv`)** files.
Maximum file size: **10 MB** (participants, members) · **50 MB** (voters/members bulk).

---

## 2. Importing Participants

Use this import to add **staff**, **guests**, or **election committee members** in bulk.

### Route

```
GET   /organisations/{slug}/membership/participants/import/template  ← download template
POST  /organisations/{slug}/membership/participants/import/preview   ← validate without saving
POST  /organisations/{slug}/membership/participants/import           ← save to database
```

### Step 1 — Download the template

Navigate to:
```
Membership → Participants → Import → Download Template
```

The template has these columns:

| Column | Required | Accepted values | Example |
|--------|----------|-----------------|---------|
| `email` | Yes | Must be an existing platform account | `john@example.com` |
| `participant_type` | Yes | `staff` · `guest` · `election_committee` | `staff` |
| `role` | No | Any text, max 100 characters | `coordinator` |
| `expires_at` | No | Future date `YYYY-MM-DD` (for guests) | `2026-12-31` |
| `permissions` | No | Valid JSON object | `{"manage":true}` |

> **Note:** The person's email address **must already exist** in the platform as a registered user.
> Unlike the member import, this import does **not** create new user accounts.

### Step 2 — Fill in the spreadsheet

```
email,participant_type,role,expires_at,permissions
anna.baker@org.com,staff,Treasurer,,
guest.speaker@conf.com,guest,Observer,2026-06-30,
mike.jones@org.com,election_committee,Scrutineer,,
```

Rules to follow:
- `participant_type` must be exactly `staff`, `guest`, or `election_committee` (lowercase)
- `expires_at` must be a **future** date — past dates are rejected
- `permissions` must be valid JSON if provided — leave blank if not needed
- If a person already exists as a participant in your organisation, their record will be **updated** (not duplicated)

### Step 3 — Preview before importing

Upload your file to the **Preview** endpoint. This validates every row without saving anything.

The preview response shows:
```json
{
  "preview": [
    {
      "row": 2,
      "email": "anna.baker@org.com",
      "participant_type": "staff",
      "status": "✅ Valid",
      "errors": []
    },
    {
      "row": 3,
      "email": "nobody@unknown.com",
      "participant_type": "staff",
      "status": "❌ Invalid",
      "errors": ["User 'nobody@unknown.com' does not exist in the platform"]
    }
  ],
  "stats": { "total": 2, "valid": 1, "invalid": 1 }
}
```

Fix any invalid rows in your spreadsheet before proceeding.

### Step 4 — Run the import

POST to the import endpoint with `confirmed=1` to save the records.

The response (flash message) tells you:
```
Import completed: 12 created, 3 updated, 1 skipped.
```

- **Created** — new participant record added
- **Updated** — existing participant record for this person was updated
- **Skipped** — row failed validation (invalid type, non-existent email, past date)

### Scope

Participants are scoped to **your organisation only**. You cannot import into another organisation's participant list.

---

## 3. Importing Members

Use this import to register **formal paid members** in bulk. This import also creates the underlying platform user account if the person does not already have one, and can optionally register them as a voter for an election in the same file.

### Route

```
GET   /organisations/{slug}/users/import              ← import page (owner only)
GET   /organisations/{slug}/users/import/template     ← download template
POST  /organisations/{slug}/users/import/preview      ← validate without saving
POST  /organisations/{slug}/users/import/process      ← save to database
GET   /organisations/{slug}/users/export              ← export current members
```

> **Access:** Owner only (stricter than participant import which allows admins).

### Step 1 — Download the template

Navigate to:
```
Organisation → Users → Import → Download Template
```

The template has these columns:

| Column | Required | Accepted values | Example |
|--------|----------|-----------------|---------|
| `email` | Yes | Valid email address | `member@org.com` |
| `name` | Yes | Full name | `Anna Baker` |
| `is_org_user` | Yes | `YES` or `NO` | `YES` |
| `is_member` | Yes | `YES` or `NO` | `YES` |
| `is_voter` | No | `YES` or `NO` | `YES` |
| `election_id` | Conditional | UUID of an election | `abc123...` |

> **Cascade rule:** `is_member = YES` requires `is_org_user = YES`.
> `is_voter = YES` requires both `is_org_user = YES` and `is_member = YES`, plus a valid `election_id`.

### Step 2 — Fill in the spreadsheet

```
email,name,is_org_user,is_member,is_voter,election_id
anna.baker@org.com,Anna Baker,YES,YES,YES,<election-uuid>
bob.wilson@org.com,Bob Wilson,YES,YES,NO,
carol.smith@org.com,Carol Smith,YES,NO,NO,
```

Rules to follow:
- If the email does not exist in the platform, a new user account is created automatically with a temporary random password (the person will need to use "Forgot Password" to set their own)
- If the email already exists, the record is updated (no duplicate user created)
- To register a voter, find the election UUID from the election management page and paste it into `election_id`
- Setting `is_member = NO` for a person who is currently a member **removes** their membership and any voter registrations

### Step 3 — Preview

Use the Preview endpoint to validate all rows. Fix any errors before proceeding.

Common validation errors:

| Error | Cause | Fix |
|-------|-------|-----|
| `Email is required` | Empty email column | Fill in the email |
| `Name is required` | Empty name column | Fill in the name |
| `Cannot be member without being organisation user first` | `is_member = YES` but `is_org_user = NO` | Set `is_org_user = YES` |
| `Cannot be voter without being member first` | `is_voter = YES` but `is_member = NO` | Set `is_member = YES` |
| `Election ID required for voters` | `is_voter = YES` but no election_id | Add the election UUID |
| `Election 'xxx' not found in this organisation` | Wrong election UUID | Copy the UUID from the election management page |

### Step 4 — Process the import

POST to `/process` with `confirmed=true`. The operation runs synchronously (all-or-nothing transaction).

The result shows:
```
Import completed: 8 created, 2 updated, 0 skipped
```

### What gets created

For each valid row, the import creates or updates the hierarchy in order:

```
1. User account (created if new email, updated if existing)
2. OrganisationUser (links the user to your organisation)
3. Member (if is_member = YES)
4. Voter (if is_voter = YES, linked to the specified election)
```

### Scope

This import is scoped to **your organisation**. The election UUID in `election_id` must belong to your organisation — cross-organisation election IDs are rejected.

### Export

To export your current member list (for backup or editing):
```
GET /organisations/{slug}/users/export
```
This downloads an Excel file in the same format as the import template, pre-filled with your current data.

---

## 4. Importing Voters for an Election

Voters are **election-scoped** — every voter registration belongs to one specific election. To register voters, you must specify which election they are being added to.

There are two ways to register voters in bulk:

### Option A — During member import (recommended for initial setup)

When filling in the member import template (Section 3), set:
- `is_voter = YES`
- `election_id = <uuid of your election>`

This registers the person as a member **and** a voter for that election in a single step.

### Option B — Bulk voter assignment (for elections already set up)

Use this option when your members already exist and you want to register them as voters for a specific election.

**Route:**
```
POST /organisations/{organisation:slug}/elections/{election:slug}/voters/bulk
```

**Request body:**
```json
{
  "user_ids": [
    "uuid-of-user-1",
    "uuid-of-user-2",
    "uuid-of-user-3"
  ]
}
```

**Rules:**
- `user_ids` must be an array of platform user UUIDs (maximum 1000 per request)
- Each user must be a member of your organisation — non-members are automatically filtered out and counted as invalid
- The election must belong to your organisation
- Demo elections do not support voter registration via this endpoint (404)

**Response (flash `bulk_result`):**
```json
{
  "success": 47,
  "already_existing": 3,
  "invalid": 0
}
```

| Key | Meaning |
|-----|---------|
| `success` | Voters newly registered |
| `already_existing` | Already registered — skipped, not duplicated |
| `invalid` | Not organisation members — filtered out |

### Voter scoping — important rule

Voters are **always scoped to a specific election**. The same person can be a voter in multiple elections — each registration is independent. Registering someone as a voter for Election A does not affect their voter status for Election B.

```
Organisation
└── Election A  ← voter in this election
└── Election B  ← NOT a voter (must register separately)
└── Election C  ← voter in this election too
```

### Who can register voters?

- **Owners** and **Admins** always can
- **Election Officers** assigned to that specific election can manage voters for their election

---

## 5. Common Errors & How to Fix Them

### "User does not exist in the platform"

The participant import requires the person to have an existing platform account. The member import creates accounts automatically. If you are using the participant import and the person is not yet registered, ask them to sign up first, or use the member import instead.

### "participant_type must be one of: staff, guest, election_committee"

The `participant_type` value must be in lowercase with an underscore. Common mistakes:

| Wrong | Correct |
|-------|---------|
| `Staff` | `staff` |
| `Election Committee` | `election_committee` |
| `GUEST` | `guest` |

### "expires_at must be a future date"

The `expires_at` column must be a date in the future. Dates today or in the past are rejected. Dates must be in `YYYY-MM-DD` format (e.g. `2026-12-31`).

### "Cannot be voter without being member first"

In the member import, `is_voter = YES` requires `is_member = YES` in the same row. A person cannot be registered as a voter if they are not also a member.

### "Election 'xxx' not found in this organisation"

The election UUID in the `election_id` column does not belong to your organisation, or the UUID is incorrect. Copy it directly from the election's URL or settings page.

### Import redirects back with no message (validation failure)

This typically means the file type was rejected. Ensure your file is saved as `.xlsx`, `.xls`, or `.csv`. Do not rename a `.txt` file to `.csv` — save it properly as CSV from Excel or your spreadsheet application.

### Partial imports (some rows skipped)

Invalid rows are skipped; valid rows are saved. Check the import result message for the skipped count. Re-run with only the failed rows after fixing the errors — the import is idempotent (already-existing records are updated, not duplicated).
