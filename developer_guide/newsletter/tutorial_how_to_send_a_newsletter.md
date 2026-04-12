# Tutorial: How to Send a Newsletter to Your Members

**Audience:** Organisation owners and admins
**Time required:** 5–10 minutes
**Prerequisites:** You must be logged in as an owner or admin of the organisation

---

## Overview

This tutorial walks you through the complete process of sending a newsletter campaign — from writing the first word to watching it land in your members' inboxes.

By the end you will have:
- ✅ Written a newsletter draft
- ✅ Verified the recipient count
- ✅ Sent the campaign
- ✅ Monitored delivery to completion

---

## Step 1 — Navigate to Newsletters

1. Log in to the platform.
2. In the top navigation, click on your **organisation name** — this takes you to the organisation page (e.g. `http://localhost:8000/organisations/namaste-nepal`).
3. Click **Membership** in the organisation menu — this takes you to the Membership Dashboard (e.g. `/organisations/namaste-nepal/membership`).
4. On the Membership Dashboard, click the **Newsletters** quick action card.

   Alternatively, go directly to: `/organisations/namaste-nepal/membership/newsletters`

You will see the newsletter list. If you have never sent a newsletter before, the list will be empty with a prompt to compose your first one.

---

## Step 2 — Create a New Draft

1. Click the **Compose** button in the top-right corner.
2. The **Compose Newsletter** form opens.

---

## Step 3 — Write Your Newsletter

### Fill in the Subject

The subject line is what members see in their inbox before opening the email. Make it clear and relevant.

**Good examples:**
- `NRNA EU — April 2026 Update`
- `Annual General Meeting — Important Voting Information`
- `Your Membership Renewal Is Due`

**Avoid:**
- All-caps (`URGENT!!!`) — often flagged as spam
- Vague subjects (`Newsletter #4`) — low open rates

### Write the Content

Click into the **Content** area and write your message. You can use:

- `**Bold**` for emphasis
- Headings to structure long messages
- Bullet lists for announcements or agenda items
- Links for external resources or meeting invitations

**Example structure:**

```
Dear Member,

We hope this message finds you well.

Here are the key updates for this month:

• The Annual General Meeting is scheduled for 15 May 2026.
• Voting opens on 10 May at 09:00 CET.
• Please ensure your membership fees are up to date.

For questions, contact us at info@nrna-eu.org.

Warm regards,
The NRNA EU Executive Committee
```

> **Note on images:** You can include images by pasting an image URL into an `<img>` tag. Direct image uploads are not yet supported.

### Plain Text (Optional)

Scroll down to the **Plain Text** field. This is an optional fallback for members using email clients that do not display HTML (rare, but exists in some corporate environments). If left blank, members still receive the HTML version.

---

## Step 4 — Check the Recipient Preview

Before saving, look at the **"This newsletter will be sent to N members"** count displayed on the form.

This number represents exactly who will receive the email right now:
- ✅ Active members only
- ✅ Not unsubscribed
- ✅ Not bounced

**If the count is 0:**
- Check that your organisation has members with `active` status in Membership Management.
- Confirm that members have not all unsubscribed.

**If the count is lower than expected:**
- Some members may be `expired` or `inactive` — they are correctly excluded.
- Some members may have previously unsubscribed.

---

## Step 5 — Save the Draft

Click **Save Draft**.

The newsletter is saved. You will be redirected to the **campaign detail page** showing:
- Subject, status (`Draft`), and creation date
- The recipient count
- An empty delivery progress bar
- The audit log showing `created` by you

You can leave and come back to this page at any time before sending. The draft is not sent until you explicitly click Send.

---

## Step 6 — Review Before Sending

On the campaign detail page, do a final review:

**Checklist:**
- [ ] Subject is clear and correct
- [ ] Content has no spelling errors
- [ ] Recipient count looks right
- [ ] You are ready — this cannot be unsent

If you spot a problem, click **Edit** (available on drafts only) to go back and fix it.

---

## Step 7 — Send the Newsletter

1. Click the **"Send to Members"** button.
2. A confirmation dialog appears:
   > *"You are about to send this newsletter to 247 members. This action cannot be undone. Are you sure?"*
3. Click **Confirm**.

The campaign status changes to **Queued** immediately.

> **What "Queued" means:** The system has accepted your send request and is building the personalised recipient list. This takes a few seconds.

---

## Step 8 — Watch the Campaign Progress

The page automatically shows the delivery progress as the campaign moves to **Processing**:

```
Sent:    148 / 247   ████████████░░░░░  60%
Failed:    2 / 247
Pending:  97 / 247
```

Refresh the page every 30–60 seconds to see updated numbers.

**What each number means:**

| Number | Meaning |
|--------|---------|
| **Sent** | The member's email server has accepted the message. |
| **Failed** | Delivery was rejected (invalid address, full inbox, server error). |
| **Pending** | Waiting in queue — will be processed shortly. |

---

## Step 9 — Campaign Completes

When all recipients have been processed, the status changes to **Completed** and the `completed_at` timestamp is recorded.

```
Status: ✅ Completed
Sent:   244 / 247
Failed:   3 / 247
```

The 3 failed deliveries mean 3 members' email addresses could not accept the message. Common causes:
- Email address no longer exists (account deleted)
- Inbox is full
- Temporary server error

Failed recipients are visible in the recipient table at the bottom of the detail page, along with the error message returned by their mail server.

---

## Step 10 — Review the Audit Log

Scroll to the bottom of the campaign detail page to see the **Audit Log** — a timestamped record of every action:

| Time | Action | Details |
|------|--------|---------|
| 14:02:11 | Created | Subject: "NRNA EU — April 2026 Update" |
| 14:05:33 | Dispatched | 247 recipients |
| 14:07:44 | Completed | 244 sent, 3 failed |

This log is permanent and cannot be edited.

---

## Congratulations

Your newsletter has been delivered. Here is what happened behind the scenes:

1. You saved a **Draft** with your subject and content.
2. You clicked **Send** — the system locked in the recipient list (247 members).
3. The system split those 247 recipients into batches of 50 and processed them in parallel.
4. Each email contained a unique **unsubscribe link** for that member.
5. All delivery results were recorded per-recipient.
6. The campaign was marked **Completed** when the last batch finished.

---

## What to Do If Something Goes Wrong

### The campaign stays in "Queued" for more than 5 minutes

The queue worker may not be running. Contact your platform administrator with the campaign ID.

### The campaign shows "Failed" (red status)

The system automatically stopped because the delivery failure rate exceeded 20%. This is a safety mechanism to protect your sender reputation. Check the audit log for the failure rate at the time of cancellation. Contact your platform administrator to investigate the mail server issue before sending again.

### A specific member says they didn't receive it

1. Go to the campaign detail page → recipient table.
2. Search for the member's email address.
3. Check their delivery status:
   - **Sent** — accepted by their server. Ask them to check spam.
   - **Failed** — their address rejected delivery. See the error message.
   - **Not in the list** — they were excluded at dispatch time. Check their membership status.

### You need to cancel mid-send

If you discover an error in the content after clicking Send:
1. Go to the campaign detail page.
2. Click **Cancel Campaign**.
3. Members who have already received it cannot be recalled — but no further emails will be sent.
4. Write a corrective newsletter as a new draft.

---

## Quick Reference

| Task | Where |
|------|-------|
| Start a new newsletter | Newsletters → Compose |
| Check recipient count | Compose form or campaign detail page |
| Send a draft | Campaign detail page → "Send to Members" |
| Monitor delivery | Campaign detail page → progress bar |
| Cancel in-flight | Campaign detail page → "Cancel Campaign" |
| See per-recipient results | Campaign detail page → recipient table |
| See history of actions | Campaign detail page → Audit Log |
