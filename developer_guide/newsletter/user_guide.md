# Newsletter — User Guide

This guide is written for **organisation owners and admins** who need to send newsletters to their members. No technical knowledge is required.

---

## Table of Contents

1. [Who Can Use Newsletters](#1-who-can-use-newsletters)
2. [What Is a Newsletter Campaign](#2-what-is-a-newsletter-campaign)
3. [Composing a Newsletter](#3-composing-a-newsletter)
4. [Previewing the Recipient Count](#4-previewing-the-recipient-count)
5. [Sending a Newsletter](#5-sending-a-newsletter)
6. [Monitoring Delivery Progress](#6-monitoring-delivery-progress)
7. [Cancelling a Campaign](#7-cancelling-a-campaign)
8. [Deleting a Draft](#8-deleting-a-draft)
9. [Understanding Delivery Statuses](#9-understanding-delivery-statuses)
10. [How Members Unsubscribe](#10-how-members-unsubscribe)
11. [Why Some Members Do Not Receive the Newsletter](#11-why-some-members-do-not-receive-the-newsletter)
12. [Frequently Asked Questions](#12-frequently-asked-questions)

---

## 1. Who Can Use Newsletters

Only **owners** and **admins** of an organisation can compose, send, or cancel newsletters.

| Action | Owner | Admin | Other roles |
|--------|:-----:|:-----:|:-----------:|
| View newsletter list | ✓ | ✓ | — |
| Compose a draft | ✓ | ✓ | — |
| Preview recipient count | ✓ | ✓ | — |
| Send a newsletter | ✓ | ✓ | — |
| Cancel a campaign | ✓ | ✓ | — |
| Delete a draft | ✓ | ✓ | — |

Members, voters, and commission members have no access to the newsletter management area.

---

## 2. What Is a Newsletter Campaign

A **campaign** is one newsletter — from the moment you write it to the moment every member receives it (or it is cancelled). Each campaign moves through a series of stages:

```
Draft  →  Queued  →  Processing  →  Completed
                                 →  Failed (automatic if too many errors)
         ↘ Cancelled (you cancel it)
```

| Stage | Meaning |
|-------|---------|
| **Draft** | You have written it but not sent it yet. You can edit, delete, or send it. |
| **Queued** | You clicked Send. The system is preparing the recipient list. |
| **Processing** | Emails are actively being delivered to members. |
| **Completed** | All emails have been processed. |
| **Cancelled** | You cancelled it before (or during) sending. |
| **Failed** | The system automatically stopped due to too many delivery errors. |

---

## 3. Composing a Newsletter

### Where to find it

1. Log in and go to your organisation page.
2. Click **Membership** — you land on the Membership Dashboard (`/organisations/{slug}/membership`).
3. Click the **Newsletters** quick action card on the dashboard.
4. Click the **Compose** button (top right of the newsletter list).

### What to fill in

**Subject** *(required)*
The email subject line that members will see in their inbox. Keep it short and clear — e.g. "NRNA June Update" or "Annual General Meeting — Your Vote Matters".

**Content** *(required)*
The body of your email. You can use:
- Headings, bold, italic, bullet points
- Links
- Images

> **Security note:** Any dangerous HTML (scripts, iframes, embedded forms) is automatically removed before saving. This protects your members' inboxes.

**Plain text** *(optional)*
A plain-text version for members whose email clients do not display HTML. If left blank, members still receive the HTML version.

### Saving

Click **Save Draft**. The newsletter is saved but **not sent**. You can come back and send it later.

---

## 4. Previewing the Recipient Count

Before sending, you can see exactly how many members will receive the newsletter.

On the **Compose** page or the newsletter **Detail** page, the system shows a live count:

> **"This newsletter will be sent to 247 members."**

This count excludes:
- Members who have unsubscribed
- Members whose emails have bounced
- Inactive or expired members

If the count looks wrong, check the member list in **Membership Management** to ensure members have `active` status.

---

## 5. Sending a Newsletter

1. Open the newsletter (from the list or after saving a draft).
2. Review the subject, content, and recipient count.
3. Click **"Send to Members"**.
4. A confirmation dialog appears — confirm that you want to send.
5. The newsletter status changes to **Queued**.

From this point, delivery is handled automatically. You do not need to stay on the page.

### What happens after you click Send

1. The system builds a recipient list of all eligible active members.
2. Each member gets a personal unsubscribe link embedded in their email.
3. Emails are sent in batches of 50, at a rate of up to 10 per second.
4. The delivery progress is visible on the campaign detail page.

### Rate limit

You can send a maximum of **3 newsletters per hour** per admin account. This prevents accidental mass sends.

---

## 6. Monitoring Delivery Progress

After sending, go to **Newsletters → [Campaign Name]** to see the live progress:

| Counter | Meaning |
|---------|---------|
| **Total** | How many members are on the recipient list |
| **Sent** | Successfully delivered to the member's email server |
| **Failed** | The email server rejected delivery |
| **Pending** | Not yet processed |

A **progress bar** shows sent vs. total. Refresh the page to see updated numbers.

The **Audit Log** at the bottom of the page shows a timestamped record of every action taken on this campaign (created, dispatched, cancelled, completed, or failed).

### How long does delivery take?

A campaign to 500 members typically completes in under 2 minutes. A campaign to 5,000 members may take 10–15 minutes.

---

## 7. Cancelling a Campaign

You can cancel a campaign while it is still in **Draft** or **Processing** status.

1. Open the campaign.
2. Click **"Cancel Campaign"**.
3. Confirm in the dialog.

**What happens:**
- Emails already sent are **not** recalled — they have already been delivered.
- Emails not yet sent are **not** sent.
- The campaign status changes to **Cancelled**.
- An audit log entry is written.

You cannot cancel a campaign that is **Completed**, **Failed**, or already **Cancelled**.

---

## 8. Deleting a Draft

You can delete a newsletter that has **never been sent** (status = Draft).

1. From the newsletter list, click the **Delete** button on a draft row.
2. Confirm deletion.

The newsletter is soft-deleted — it is removed from your view but the record is retained internally for compliance purposes.

You cannot delete a newsletter that has been queued, sent, or cancelled.

---

## 9. Understanding Delivery Statuses

Each individual recipient has their own delivery status:

| Status | Meaning |
|--------|---------|
| **Pending** | Waiting to be sent |
| **Sending** | Currently being processed |
| **Sent** | Your email server accepted the message |
| **Failed** | Your email server rejected the message (wrong address, full inbox, etc.) |

> **"Sent" means accepted, not opened.** The email was accepted by the member's mail server. Whether the member opened it depends on their email client.

You can see per-recipient statuses on the campaign detail page (paginated table under the progress bar).

---

## 10. How Members Unsubscribe

Every newsletter email contains an **Unsubscribe** link in the footer. When a member clicks it:

1. They are taken to a simple confirmation page: *"You have been unsubscribed."*
2. Their unsubscribe is recorded immediately.
3. They will not receive any future newsletter campaigns from your organisation.

No admin action is needed. The system handles it automatically.

Members who unsubscribe can only be re-subscribed manually by an admin (by clearing the unsubscribe flag in their member record). This should only be done with the member's explicit consent.

---

## 11. Why Some Members Do Not Receive the Newsletter

A member will **not** appear on the recipient list if any of the following is true:

| Reason | What it means |
|--------|---------------|
| Status is not `active` | The member is expired, suspended, or ended |
| Unsubscribed | The member clicked the Unsubscribe link in a previous email |
| Bounced | A previous email to this member bounced (invalid/full email address) |

If a specific member should have received the newsletter but did not, check their membership record for any of the above conditions.

---

## 12. Frequently Asked Questions

**Can I edit a newsletter after saving it as a draft?**
Yes — drafts can be edited at any time before you click Send. Once sent (Queued or beyond), the content cannot be changed.

**Can I send the same newsletter twice?**
No. Once a newsletter is sent, it cannot be re-sent. Create a new draft with the same content if you need to send again.

**Can I schedule a newsletter for a future date?**
Not currently. Newsletters are sent immediately when you click Send. Draft them in advance and send manually at the right time.

**What happens if the system detects too many delivery failures?**
If more than 20% of emails fail after at least 50 delivery attempts, the system automatically stops the campaign and marks it as **Failed**. This protects your organisation's sender reputation. Contact your platform administrator to investigate the cause.

**Can I see who opened the email?**
Not currently. The system tracks whether emails were accepted by the recipient's mail server, but open tracking is not yet implemented.

**A member says they did not receive the newsletter. What do I check?**
1. Confirm their membership status is `active` in Membership Management.
2. Check whether they are unsubscribed (look for `Newsletter Unsubscribed` field in their member record).
3. Check whether they appear in the campaign's recipient table (on the campaign detail page).
4. If they appear with status `Failed`, their email address may be invalid or their inbox full.

**Can I send a newsletter to a specific group of members (e.g., only members from one region)?**
Not currently. Newsletters are sent to all eligible active members of the organisation. Segment-based sending is a planned future feature.

**Will members from other organisations receive my newsletter?**
Never. The recipient list is strictly scoped to your organisation. Members of other organisations are completely isolated.
