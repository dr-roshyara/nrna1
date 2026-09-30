# FAQ & Troubleshooting

Answers to the most common questions and problems for election officers.

---

## Invitation & Access

### Q: I never received my invitation email. What do I do?

1. Check your **spam/junk folder** — search for "Election Officer"
2. Check the email address your account is registered with
3. Ask your **organisation administrator** to re-send the invitation (they can remove and re-appoint you)
4. Add the platform's email address to your contacts to prevent future filtering

---

### Q: My invitation link says "This link has expired."

Invitation links are valid for **7 days** from when they were sent.

**Solution:** Ask your organisation administrator to appoint you again. A new 7-day link will be emailed immediately.

---

### Q: I get a "403 Forbidden" error when I try to open the Management Dashboard.

The most common causes:

| Cause | Solution |
|-------|---------|
| Your status is still **Pending** | Accept your invitation email first |
| You are a **Commissioner** | Commissioners cannot access Management. Use the Viewboard instead |
| You are logged in as the wrong account | Log out and log in with the account that received the invitation |
| Your appointment was removed | Contact your organisation administrator |

---

### Q: I accepted the invitation but the link just shows the login page again.

This happens when you were not logged in during acceptance. The system saves your invitation and redirects you after login.

**Steps:**
1. Enter your email (it should be pre-filled) and password
2. Click Sign In
3. You will be automatically redirected to the acceptance confirmation page

---

### Q: The wrong person accepted the invitation. What happens?

Only the correct account holder can accept — the system checks that the logged-in user matches the invited user. If someone else tries, they see a "Wrong User" error and the invitation is not consumed. The link remains valid for the correct user.

---

## Voter Management

### Q: A voter says they cannot vote. Their status shows Active. Why?

Check these in order:

1. **Is voting actually open?** Go to the Management Dashboard and check the Voting Period status. If it shows "Closed", the Chief or Deputy must click "Open Voting".
2. **Is the election Active?** If status is "Completed", voting has ended.
3. **Has the voter's access expired?** Check the "Eligible" count on the voter list — if it's lower than "Active", some voters have expired access. Contact your system administrator.

---

### Q: I accidentally approved a voter who should not vote.

Click **Suspend** on that voter immediately. Their status changes to Inactive and they can no longer submit a ballot, even if voting is open.

---

### Q: I accidentally suspended a voter who should be able to vote.

Click **Approve** on that voter. Status returns to Active immediately. If voting is currently open, they can vote right away.

---

### Q: I accidentally removed a voter (not just suspended).

Removed voters cannot be restored through the voter list UI. Contact your **system administrator** to manually restore the membership record.

> This is why we recommend **Suspend** (reversible) over **Remove** (permanent) when in doubt.

---

### Q: Can I approve voters while voting is already open?

Yes. Approving a voter while voting is open immediately grants them eligibility. They can vote as soon as they log in.

---

### Q: The voter list shows 0 voters even though members were imported.

Members must be explicitly **assigned to this election** before they appear on the voter list. Assignment is done by the Chief or Deputy via the "Assign Voter" form, or by the organisation administrator in bulk.

Assigning a member creates an `invited` entry. You then approve them to grant voting eligibility.

---

### Q: What is the difference between "Active" and "Eligible"?

- **Active** = Status is set to Active (approved)
- **Eligible** = Active AND their access has not expired (based on `expires_at`)

In most elections, these numbers are the same. A difference means some voters were assigned with a time-limited access window that has now passed.

---

## Voting Period

### Q: Can we reopen voting after closing it?

Yes. Click **Open Voting** on the Management Dashboard. There is no restriction on how many times voting can be opened and closed.

---

### Q: We closed voting too early. Can votes already cast still be counted?

Yes. Closing voting prevents **new** votes from being submitted. All votes already cast are preserved and will appear in the results.

---

### Q: What is the difference between "Close Voting" and "Remove Voter"?

- **Close Voting** = ends the voting period for the entire election
- **Remove Voter** = removes a specific individual from the voter list

These are completely independent actions.

---

## Results

### Q: The Deputy wants to publish results. Can they?

No. Publishing results is a **Chief-only** action. The Deputy can do everything else including closing voting, but the **Publish Results** button only appears for the Chief.

---

### Q: Can we publish results before closing voting?

Yes, technically — there is no system lock preventing it. However, it is strongly recommended to **close voting first**, then publish. Publishing results while voting is open could influence voters who haven't voted yet.

---

### Q: We published results but found an error. Can we unpublish?

Yes. Click **Unpublish Results** on the Management Dashboard. Results are immediately hidden from voters. You can republish at any time.

---

### Q: Voters say they cannot see the results page.

Check that:
1. Results are published (Management Dashboard shows "Results: Published")
2. The voter is viewing the correct election results URL
3. The results page is not cached in their browser — ask them to refresh (Ctrl+F5)

---

## Roles & Permissions

### Q: Can an officer be appointed to two elections at the same time?

Yes. An officer can serve on multiple elections simultaneously. Each election is independent.

---

### Q: Can someone be both Chief for one election and Commissioner for another?

Yes — roles are per-election, not global.

---

### Q: The administrator appointed me but used the wrong role (e.g. Commissioner instead of Deputy). What do I do?

Ask the administrator to:
1. Remove your current officer appointment
2. Re-appoint you with the correct role

A new invitation email will be sent. You must accept it to activate the new role.

---

## Technical Issues

### Q: I clicked "Open Voting" but nothing changed.

Try these steps:
1. Wait 5 seconds and refresh the page (F5)
2. Check your internet connection
3. Try again — the action is safe to repeat
4. If the status still does not change, contact your system administrator

---

### Q: The page shows an error after I click a button.

Note the error message if any, then:
1. Refresh the page
2. Check if the action actually completed (e.g. check the voter's status)
3. Try the action again if nothing changed
4. If errors persist, contact your system administrator with the error details

---

### Q: Buttons appear "greyed out" and cannot be clicked.

A greyed-out button means an action is already in progress for that row. Wait a moment — the button will re-enable automatically once the request completes.

If a button stays grey permanently, refresh the page.

---

## Election Lifecycle Summary

```
SETUP
  │
  ├─ Administrator creates election
  ├─ Administrator appoints officers (Chief, Deputy, Commissioner)
  ├─ Officers accept invitations (Pending → Active)
  ├─ Members assigned to voter list (status: invited)
  └─ Officers approve voters (status: active)
          ↓
VOTING DAY
  │
  ├─ Chief/Deputy clicks "Open Voting"
  ├─ Voters log in and cast ballots
  ├─ Officers monitor via Viewboard
  ├─ Officers can approve/suspend voters as needed
  └─ Chief/Deputy clicks "Close Voting"
          ↓
RESULTS
  │
  ├─ Chief reviews results internally
  └─ Chief clicks "Publish Results"
          ↓
  Voters can view results at the results page
```

---

## Still Need Help?

If this guide did not answer your question:

1. **Re-read the relevant guide** — [Roles](./01-your-role.md) | [Invitation](./02-accepting-invitation.md) | [Dashboard](./03-management-dashboard.md) | [Voter List](./04-voter-list.md) | [Viewboard](./05-viewboard.md)
2. **Contact your organisation administrator** — they manage officer appointments and member assignments
3. **Contact platform support** — for technical errors that persist after retrying
