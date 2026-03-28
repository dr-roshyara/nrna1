# Candidacy Approval Procedure

> **Audience:** Election officers (chief, deputy) and organisation owners/admins.
>
> This document explains every step from a voter submitting a candidacy application to the candidate appearing on the voting ballot.

---

## The Full Pipeline

```
Voter applies
    │
    ▼
CandidacyApplication (status = pending)
    │
    ▼  Officer reviews at /candidacy/applications
    ├── Approve ──▶ Candidacy created (status = draft)
    │                   │
    │                   ▼  Officer publishes at /candidacies
    │               Candidacy (status = approved)
    │                   │
    │                   ▼
    │               Appears on the voting ballot ✅
    │
    └── Reject ──▶ CandidacyApplication (status = rejected)
                      Voter may re-apply for a different post
```

---

## Step 1 — Voter Submits an Application

**Who can do this:** Any organisation member (role: `voter`, `officer`, etc.).

**URL:**
```
/organisations/{org-slug}/candidacy/apply
```

**What the voter does:**
1. Selects the election from the dropdown. Elections where they already have a pending or approved application are shown as disabled (`— Already applied`).
2. Selects the post (position) — the post list cascades from the selected election.
3. Fills in:
   - **Proposer Name** (required) — name of the member proposing their candidacy
   - **Supporter Name** (required) — name of the member supporting their candidacy
   - **Manifesto / Statement** (optional, max 5 000 characters)
   - **Candidate Photo** (optional, JPG/PNG, max 5 MB)
4. Ticks the declaration checkbox.
5. Clicks **Submit Application**.

**What is created:**
- One `CandidacyApplication` record with `status = pending`.

**One-per-election rule:** A voter can only have one active application (pending or approved) per election, regardless of which post they apply for. The server blocks duplicates even if the UI is bypassed.

**Voter reviews their applications at:**
```
/organisations/{org-slug}/candidacy/list
```

---

## Step 2 — Officer Reviews Applications

**Who can do this:** Election chief, deputy, or organisation owner/admin (`managePosts` permission in `ElectionPolicy`).

**URL:**
```
/organisations/{org-slug}/elections/{election-slug}/candidacy/applications
```

**Navigation shortcut:** Election Management page → **"Review Applications"** card.

**What the officer sees:**
- Applications are split into two groups:
  - **Awaiting Decision** — pending applications with Approve / Reject buttons
  - **Decided** — already-processed applications with verdict badges
- Each pending card shows:
  - Applicant name and email
  - Post applied for
  - Collapsible **Read Statement** toggle for the manifesto
  - Application ID (monospaced) and submission date

### Approving an Application

1. Click **Approve** on the application card.
2. A confirmation dialog appears: *"Approve [name] for [post]? A draft candidate entry will be created."*
3. Click OK.

**What happens on the server:**
- `CandidacyApplication.status` → `approved`
- `CandidacyApplication.reviewed_by` → current officer's user ID
- `CandidacyApplication.reviewed_at` → current timestamp
- A new `Candidacy` record is created:
  - `status = draft` (not yet visible on the ballot)
  - `post_id` → from the application
  - `user_id` → applicant's user ID
  - `name` → applicant's name
  - `description` → application manifesto
  - `image_path_1` → application photo (transferred automatically)
- `CandidacyApplication.candidacy_id` → linked to the new `Candidacy`

> The candidate is **not yet on the ballot**. `draft` status means the officer can review and edit before publishing.

### Rejecting an Application

1. Click **Reject** on the application card.
2. An inline rejection form slides in.
3. Enter a **rejection reason** (required — cannot submit without it, max 500 characters).
4. Click **Issue Rejection**.

**What happens on the server:**
- `CandidacyApplication.status` → `rejected`
- `CandidacyApplication.rejection_reason` → stored
- `CandidacyApplication.reviewed_by` / `reviewed_at` → set
- **No `Candidacy` record is created.**

**Can a rejected voter re-apply?** Yes — a `rejected` application does not block future applications. The duplicate check only blocks `pending` or `approved` status. The officer can tell the voter to re-apply for a different post.

---

## Step 3 — Officer Publishes the Draft Candidate

**Who can do this:** Same officer roles as Step 2.

**URL:**
```
/organisations/{org-slug}/elections/{election-slug}/candidacies
```

**Navigation shortcut:** Election Management page → **"Manage Candidates"** button.

**What the officer sees:**
- All posts for the election, each listing their candidates.
- Draft candidates have an orange background tint and a green **Publish** button.
- The header of each post card shows a count pill: `N draft`, `N approved`.

### Publishing a Draft Candidate

1. Find the candidate row (orange tint, labelled `draft`).
2. Click **Publish**.
3. The status changes to `approved` immediately (no page reload needed — Inertia re-renders).

**What happens:** `PATCH /posts/{post}/candidacies/{candidacy}` with `{ status: 'approved' }`. The `Candidacy` record's status changes from `draft` to `approved`.

The candidate now appears on the voting ballot.

### Unpublishing a Candidate

1. Find an approved candidate (green badge).
2. Click **Unpublish**.
3. Status reverts to `draft` — candidate is hidden from the ballot.

Use this if you need to make corrections before the election opens.

### Removing a Candidate

1. Click **Remove** on any candidate row.
2. A confirmation dialog appears.
3. Confirm to soft-delete the `Candidacy` record.

> Removing a candidate does **not** change the linked `CandidacyApplication` status — it remains `approved`. The application audit trail is preserved.

---

## Step 4 — Candidate Appears on the Ballot

Once `Candidacy.status = approved`, the candidate is included in the voting form under their post. Voters see only `approved` candidacies when casting votes.

---

## URL Quick Reference

| Action | Role | URL |
|--------|------|-----|
| Submit application | Voter | `/organisations/{org}/candidacy/apply` |
| View my applications | Voter | `/organisations/{org}/candidacy/list` |
| Review applications | Officer | `/organisations/{org}/elections/{election}/candidacy/applications` |
| Manage candidates (publish/unpublish) | Officer | `/organisations/{org}/elections/{election}/candidacies` |
| Manage positions | Officer | `/organisations/{org}/elections/{election}/posts` |
| Election management hub | Officer | `/elections/{election}/management` |

> ⚠️ **All URLs require `/organisations/{org-slug}/` as a prefix.** Routes like `/elections/{slug}/candidacies` return 404 — the organisation slug is always required for tenant scoping.

---

## Common Mistakes

| Mistake | Fix |
|---------|-----|
| Going to `/elections/{slug}/candidacy/applications` (404) | Use `/organisations/{org}/elections/{slug}/candidacy/applications` |
| Going to `/elections/{slug}/candidacies` (404) | Use `/organisations/{org}/elections/{slug}/candidacies` |
| Application approved but candidate not on ballot | Go to `/candidacies` and click **Publish** — approval creates a draft, not an approved candidate |
| Voter sees "Already applied" but wants a different post | Reject their current application first; they can then re-apply |
| Draft candidate visible to voters | It is not — only `approved` candidacies appear on the ballot |

---

## Data Trail

After the full process, the following records exist:

```
CandidacyApplication
  status        = approved
  candidacy_id  = {uuid}       ← links to the Candidacy below
  reviewed_by   = {officer_id}
  reviewed_at   = {timestamp}

Candidacy
  status        = approved      ← after Step 3 publish
  user_id       = {voter_id}
  post_id       = {post_id}
  image_path_1  = candidacy/...  ← transferred from application photo
```

The `candidacy_id` on `CandidacyApplication` provides a permanent audit link between the voter's original application and the ballot entry, even if the candidacy is later edited.
