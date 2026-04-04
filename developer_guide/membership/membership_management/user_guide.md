# Membership Management — User Guide

This guide is written for **organisation staff and members** using the membership management system. It covers every task from the perspective of the people doing it — no technical knowledge required.

---

## Table of Contents

1. [Who Can Do What — Roles at a Glance](#1-who-can-do-what--roles-at-a-glance)
2. [Applying for Membership (New Members)](#2-applying-for-membership-new-members)
3. [Reviewing Applications (Admins & Commission)](#3-reviewing-applications-admins--commission)
4. [Approving an Application](#4-approving-an-application)
5. [Rejecting an Application](#5-rejecting-an-application)
6. [Recording a Fee Payment](#6-recording-a-fee-payment)
7. [Waiving a Fee](#7-waiving-a-fee)
8. [Renewing a Membership](#8-renewing-a-membership)
9. [Self-Renewal (Members)](#9-self-renewal-members)
10. [Managing Membership Types (Owners)](#10-managing-membership-types-owners)
11. [Ending a Membership](#11-ending-a-membership)
12. [Automatic Daily Processing](#12-automatic-daily-processing)
13. [Frequently Asked Questions](#13-frequently-asked-questions)

---

## 1. Who Can Do What — Roles at a Glance

Every person in an organisation has one of five roles. Your role determines what you can see and do in the membership system.

| Action | Owner | Admin | Commission | Voter | Member |
|--------|:-----:|:-----:|:----------:|:-----:|:------:|
| View application list | ✓ | ✓ | ✓ | — | — |
| View application detail | ✓ | ✓ | ✓ | — | — |
| Approve an application | ✓ | ✓ | — | — | — |
| Reject an application | ✓ | ✓ | — | — | — |
| Record a fee payment | ✓ | ✓ | — | — | — |
| Waive a fee | ✓ | ✓ | — | — | — |
| Renew any member | ✓ | ✓ | — | — | — |
| Self-renew own membership | ✓ | ✓ | — | — | ✓ |
| Create membership types | ✓ | — | — | — | — |
| Edit membership types | ✓ | — | — | — | — |
| Delete membership types | ✓ | — | — | — | — |

> **Commission** members have read-only access to applications. They can view but not act.

> **Voters** and non-role users have no access to the membership management area.

---

## 2. Applying for Membership (New Members)

Anyone with a platform account can apply to join an organisation as a member. You do not need to already be part of the organisation.

### Steps

1. Go to the organisation's public page.
2. Click **"Apply for Membership"** (or use the direct link shared by the organisation).
3. Select a **membership type** from the list. Each type shows:
   - Name and description
   - Fee amount and currency
   - Duration (e.g., "12 months" or "Lifetime")
4. Fill in any additional fields required by the organisation.
5. Click **Submit Application**.

You will be redirected to the organisation's Voter Hub with a confirmation message.

### What Happens Next

- Your application is recorded with status **Submitted**.
- The organisation's admins will receive a notification.
- Your application will be reviewed — you will be notified by email when a decision is made.

### Things to Know

- **You cannot apply twice.** If you already have a pending application for the same organisation, the system will block a second submission and show an error.
- **You cannot apply if you are already an active member.** If your membership has expired, your old record is no longer active and you may reapply.
- **Applications expire after 30 days** if not reviewed. You will be notified if this happens, and you may submit a new application.

---

## 3. Reviewing Applications (Admins & Commission)

### Viewing the Applications List

1. Log in and navigate to your organisation's management area.
2. Go to **Membership → Applications**.
3. You will see a paginated list of all applications, ordered by most recent.

Each row shows:
- Applicant name
- Membership type applied for
- Status (Submitted, Under Review, Approved, Rejected)
- Submitted date

### Viewing an Application Detail

Click any application row to open the detail page. You will see:
- Full applicant information
- The membership type they applied for
- Any application data they submitted
- The application's current status
- If reviewed: who reviewed it and when, and any rejection reason

Commission members see all of this but do not see Approve or Reject buttons.

---

## 4. Approving an Application

**Required role:** Owner or Admin

1. Open the application from the list.
2. Confirm the applicant's details are correct.
3. Click **Approve**.

### What Happens Automatically on Approval

The system performs all of these steps in a single transaction — if anything fails, nothing is saved:

1. The application status is set to **Approved**.
2. The applicant is added to the organisation as a **Member**.
3. A **UserOrganisationRole** entry is created so they can access member-only features.
4. A **pending fee** is created based on the membership type's current fee amount. The fee amount is frozen at the time of approval — future price changes to the type will not affect this fee.
5. The applicant receives an email notification.

### If Two Admins Click Approve at the Same Time

The system uses **optimistic locking** to prevent double-approval. Only one approval will succeed. The second admin will see the message:

> *"This application was already processed by another administrator."*

No duplicate records are created.

---

## 5. Rejecting an Application

**Required role:** Owner or Admin

1. Open the application from the list.
2. Click **Reject**.
3. Enter a **rejection reason** (required — this will be sent to the applicant).
4. Confirm the rejection.

The application status is set to **Rejected** and the applicant receives an email with your reason.

A rejected application cannot be rejected again. If the applicant wishes to reapply, they may submit a new application.

---

## 6. Recording a Fee Payment

When an application is approved, the system creates a **pending fee**. Admins record the actual payment manually when they receive it (bank transfer, cash, online payment confirmation, etc.).

**Required role:** Owner or Admin

### Steps

1. Navigate to **Membership → Members**.
2. Click on the member's name to open their profile.
3. Go to the **Fees** tab.
4. Find the pending fee and click **Record Payment**.
5. Fill in:
   - **Payment Method** (e.g., "Bank Transfer", "Cash", "Card")
   - **Payment Reference** (e.g., bank reference number) — optional
   - **Idempotency Key** — optional, see below
6. Click **Confirm Payment**.

The fee status changes to **Paid** and the payment date is recorded.

### What Is an Idempotency Key?

An idempotency key is a unique reference you assign to a payment submission to prevent accidental duplicate recording. For example, if your internet disconnects mid-submission and you are unsure whether the payment was saved, you can re-submit with the same idempotency key. The system will recognise the key and reject the duplicate.

- If you submit the same key for the **same fee** again → the system accepts it (safe retry).
- If you submit the same key for a **different fee** → the system blocks it with an error: *"Duplicate payment detected."*

You do not have to use idempotency keys — they are optional. If you leave the field blank, the system still records the payment normally.

### A Fee That Has Already Been Paid Cannot Be Paid Again

If you try to record a payment for a fee that is already marked as Paid or Waived, the system will show:

> *"This fee has already been processed."*

---

## 7. Waiving a Fee

Waiving a fee means the organisation decides not to collect it — for example, for a founding member, a hardship case, or an administrative correction.

**Required role:** Owner or Admin

1. Navigate to the member's **Fees** tab.
2. Find the pending fee and click **Waive**.
3. Confirm the action.

The fee status changes to **Waived**. No payment is required and no further action is needed.

Only **pending** fees can be waived. Fees already marked as Paid cannot be waived.

---

## 8. Renewing a Membership (Admin-Initiated)

Admins can renew any member's membership at any time, regardless of the member's current expiry date.

**Required role:** Owner or Admin

### Steps

1. Navigate to **Membership → Members**.
2. Click on the member you want to renew.
3. Click **Renew Membership**.
4. Select the **membership type** to renew under (you may change the type on renewal).
5. Add optional notes.
6. Click **Confirm Renewal**.

### What Happens on Renewal

1. A new **pending fee** is created with the type's current fee amount (frozen as a snapshot).
2. A **renewal record** is created, linking the old expiry, new expiry, and the fee.
3. The member's **expiry date is updated**.

### How the New Expiry Is Calculated

| Situation | New Expiry |
|-----------|-----------|
| Member is still active (not yet expired) | Old expiry + type duration |
| Member has already expired | Today + type duration |

**Example 1:** Member expires 1 June 2025. Admin renews on 15 May 2025 with a 12-month type.
→ New expiry: **1 June 2026** (stacks on top of remaining time)

**Example 2:** Member expired 1 March 2025. Admin renews on 15 April 2025 with a 12-month type.
→ New expiry: **15 April 2026** (starts fresh from today)

### Lifetime Members Cannot Be Renewed

If a member holds a lifetime membership (no expiry date), the system blocks renewal:

> *"Lifetime members cannot be renewed."*

---

## 9. Self-Renewal (Members)

Members can renew their own membership without contacting an admin, as long as they are within the **self-renewal window**.

**Required role:** Member (own membership only)

### Self-Renewal Window

You may self-renew if your membership expired **no more than 90 days ago**.

| Your expiry date | Today | Can self-renew? |
|------------------|-------|----------------|
| 1 April 2025 | 30 June 2025 | ✓ (90 days window) |
| 1 April 2025 | 2 July 2025 | ✗ (91 days — window closed) |
| No expiry (lifetime) | Any | ✗ (lifetime cannot be renewed) |

### Steps

1. Log in and go to your membership profile (via the Voter Hub or your profile page).
2. Click **Renew My Membership**.
3. Select the membership type you want to renew under.
4. Confirm.

A pending fee is created. You will need to pay it through the organisation's payment process (e.g., bank transfer to the treasurer).

If you are outside the 90-day window, you will see:

> *"You are not eligible to self-renew at this time."*

In that case, contact the organisation's admin directly.

---

## 10. Managing Membership Types (Owners)

Membership types define the different tiers of membership your organisation offers — for example, "Annual Member", "Student Member", "Lifetime Member", "Associate Member".

**Required role:** Owner only

### Viewing Types

Go to **Settings → Membership Types** to see all types for your organisation.

### Creating a New Type

1. Click **New Membership Type**.
2. Fill in:
   - **Name** — shown to applicants (e.g., "Annual Member")
   - **Slug** — URL-friendly identifier, unique within your organisation (e.g., "annual-member")
   - **Description** — optional, shown in the application form
   - **Fee Amount** — the membership fee (e.g., 50.00)
   - **Fee Currency** — 3-letter currency code (e.g., EUR, GBP, USD)
   - **Duration (months)** — leave blank for a **lifetime** membership with no expiry
   - **Requires Approval** — if on, all applications go through admin review; if off, applications are auto-approved (future feature)
   - **Active** — only active types appear in the application form
   - **Sort Order** — controls display order in the application form (lower number = shown first)
3. Click **Save**.

### Editing a Type

1. Click **Edit** next to the type.
2. Update any fields.
3. Click **Save**.

> **Note:** Changing the fee amount on a type does NOT affect existing fees. All existing fees have their amount frozen at the time they were created. Only new applications and renewals will use the updated fee.

### Deactivating a Type

Set **Active** to OFF. The type will no longer appear in the application form for new applicants.

Existing members who are on this type are **not affected** — their membership continues normally.

### Deleting a Type

1. Click **Delete** next to the type.
2. Confirm the deletion.

> **You cannot delete a type that has existing applications or fee records.** Deactivate it instead. This preserves the historical audit trail.

### Slug Rules

- Slugs must be unique within your organisation (but two different organisations may use the same slug).
- Once a type has applications against it, changing the slug is not recommended as it may affect existing links.

---

## 11. Ending a Membership

Ending a membership permanently terminates it with a recorded reason. This is different from a membership simply expiring — an ended membership is an active administrative decision.

**This action is not currently available via the user interface** — it is performed programmatically by administrators via the system. If you need to end a member's membership, contact your platform administrator.

### What Happens When a Membership Is Ended

1. The member's status changes to **Ended**.
2. The date and reason are recorded permanently.
3. All pending fees are automatically **waived**.
4. The member is **removed from all active elections** they are registered in.

This action cannot be reversed.

---

## 12. Automatic Daily Processing

The system runs a background task every day at midnight that performs two housekeeping operations. No manual action is required.

### Operation 1 — Auto-Reject Expired Applications

Any application with status **Submitted**, **Under Review**, or **Draft** that has passed its **expiry date** (30 days after submission by default) is automatically rejected.

The rejection reason is recorded as: *"Application expired automatically."*

The applicant receives a notification. They may submit a new application.

**Why 30 days?** Unreviewed applications should not sit indefinitely. The 30-day window gives admins sufficient time to review. If your organisation needs more time, contact your platform administrator to change the default.

### Operation 2 — Mark Overdue Fees

Any fee with status **Pending** that has a **due date** in the past is automatically marked as **Overdue**.

This does not cancel the fee or remove the member — it is a status update to help admins identify which fees need follow-up.

---

## 13. Frequently Asked Questions

---

**Q: An applicant submitted their application but we cannot find it in the list.**

The application list shows all applications. Check the status filter — the application may be in a "Rejected" or "Approved" state and filtered out of the default view. Also check whether the application expired (older than 30 days without review) — expired applications are auto-rejected by the daily job.

---

**Q: We approved an application but the member does not appear in the members list.**

After approval, the system creates the member record automatically inside a database transaction. If the approval appeared to succeed (you saw the success message) but the member is missing, this may indicate a database error during the transaction. Check the system logs and contact your platform administrator.

---

**Q: Can we change a member's membership type?**

Not directly through the renewal screen — renewal creates a new record under a new type. You cannot retroactively change the type on an existing active membership without a database-level change. Contact your platform administrator if you need to correct a type assignment.

---

**Q: A member wants to renew but they are past the 90-day window.**

Only admins (Owner or Admin role) can renew a membership outside the self-renewal window. Go to Membership → Members, find the member, and click Renew Membership. Admins have no time restriction on renewal.

---

**Q: We entered the wrong payment method when recording a fee. Can we edit it?**

Once a fee is marked as Paid, it cannot be edited through the interface. This is intentional — paid fees are immutable for audit purposes. Contact your platform administrator to make a correction at the database level if required.

---

**Q: Can a member hold two different memberships at the same time?**

No. Each user can have one active membership per organisation. The application form blocks submission if the user already has an active membership or a pending application.

---

**Q: What happens to a member's election access if their membership expires?**

Their `ElectionMembership` record remains active in the database, but the **eligibility scope** used by the voting system checks the underlying `Member` status. An expired member will not be considered eligible for election participation, even if their election registration was never removed.

---

**Q: Can we reactivate a membership that was ended?**

No. An ended membership is a permanent administrative record. The member may submit a fresh application to rejoin. The organisation can approve that application normally.

---

**Q: Who receives notifications when an application is submitted or reviewed?**

Notification delivery depends on how your organisation has configured the notification system. By default:
- **Application submitted** → notifies organisation admins (email + in-app)
- **Application approved** → notifies the applicant (email)
- **Application rejected** → notifies the applicant (email) with your rejection reason

---

**Q: The application expired but we still want to approve it. What do we do?**

Once an application is auto-rejected by the daily job, it cannot be approved. Ask the applicant to submit a new application. You can then approve the new one promptly.

---

**Q: Is the fee amount on an old fee updated when we change the membership type's price?**

No. Fee amounts are **frozen at the time the fee is created**. Changing a type's price affects only future fees. All historical fees retain the price that was in effect when they were issued. This is intentional for accounting accuracy.

---

**Q: Can two admins approve the same application simultaneously?**

The system prevents this. Even if two admins click Approve at exactly the same moment, only one approval will succeed. The second admin will see the message *"This application was already processed by another administrator."* No duplicate member or fee records will be created.
