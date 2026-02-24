# Tips & Troubleshooting

**Best practices and solutions to common issues**

This guide provides helpful tips for efficient work and solutions to common problems.

---

## 📋 Table of Contents

1. [Pro Tips for Efficiency](#pro-tips-for-efficiency)
2. [Common Issues & Solutions](#common-issues--solutions)
3. [Session Management](#session-management)
4. [Performance Optimization](#performance-optimization)
5. [Data & Security](#data--security)
6. [Reporting Problems](#reporting-problems)

---

## Pro Tips for Efficiency

### ⏱️ Working Faster

**Tip 1: Use keyboard shortcuts**
```
Tab           → Navigate between fields
Enter         → Submit forms or activate buttons
Escape        → Close dialogs
Ctrl+F        → Find on page (browser search)
F5            → Refresh page
```

See [Accessibility Guide](./08-accessibility.md) for more keyboard navigation.

**Tip 2: Master search**
- Don't scroll through pages
- Use search to jump directly to voters
- Takes 5 seconds vs 5 minutes of scrolling

**Tip 3: Use filters strategically**
```
Before bulk approving:
1. Filter: Status = Pending (500 voters down to 300)
2. Filter: Region = Bayern (300 voters down to 100)
3. Now bulk approve just 100 voters
vs Searching through all 500 at once
```

**Tip 4: Bookmark the voter list**
```
While on voter list page:
Ctrl+D (or Cmd+D)  → Bookmark page
Next time:
Just click bookmark → Jump straight to voter list
No need to navigate through menus
```

**Tip 5: Open results in new tab**
```
Ctrl+Click (or Cmd+Click) voter names
Opens voter details in new tab
Keeps main list visible
Switch between tabs to compare
```

### 📊 Commission Member Workflow

**Daily routine for efficient approvals:**

```
Morning (20 min):
1. Login to platform
2. Check statistics dashboard
3. Filter: Status = Pending
4. Scan pending voters (5 min)
5. Approve obvious candidates (15 min)
6. Come back to questionable cases later

Afternoon (10 min):
1. Filter: Status = Pending
2. Check any flagged voters
3. Approve/Suspend questionable ones
4. Monitor approval progress

End of day (5 min):
1. Quick review of statistics
2. Note progress for next day
3. Share updates with team if needed
```

**Recommended approval speed:**
- Fresh/focused: 30-50 voters per hour
- Steady pace: 20-30 voters per hour
- Careful review: 10-20 voters per hour

### 📈 Statistics Check-in

**Regular monitoring schedule:**

| Time | Action | Purpose |
|------|--------|---------|
| **Morning** | Check pending count | See overnight registrations |
| **Mid-day** | Check approved % | Track progress |
| **Afternoon** | Check voted count | Monitor participation |
| **End of day** | Review turnout % | Assess engagement |

---

## Common Issues & Solutions

### ❌ "Page is slow or sluggish"

**Problem:** Voter list loads slowly or is laggy

**Causes & Solutions:**

**1. Too many voters on screen**
- Solution: Filter to reduce loaded voters
- Filter by status, region, or date
- Reduces list from 2000 to 200 voters

**2. Large file in browser**
- Solution: Clear browser cache
- Settings → Privacy → Clear Browsing Data
- Select "Cached images and files"
- Reload page

**3. Slow internet connection**
- Solution: Try on different network
- Mobile hotspot vs WiFi
- Wired connection faster than WiFi
- Check internet speed online

**4. Browser has too many tabs**
- Solution: Close unnecessary tabs
- Each tab uses memory
- Close other applications
- Restart browser completely

**5. Outdated browser**
- Solution: Update your browser
- Chrome: Menu → About Chrome
- Firefox: Menu → Help → About Firefox
- Update immediately

---

### ❌ "Can't approve/suspend voters"

**Problem:** [Approve] or [Suspend] buttons don't work

**Causes & Solutions:**

**1. Wrong role**
- Check: Do you have Commission Member access?
- Members and Staff can only view
- Contact administrator for role upgrade
- See [Voter List Overview](./02-voter-list-overview.md#for-different-roles)

**2. Voter already approved/suspended**
- Button changes: [Approve] → [Suspend] (or vice versa)
- Click different button for opposite action
- Can't approve already-approved voter

**3. Session expired**
- Message: "Session expired, please login again"
- Solution: Refresh page, login again
- Don't need to leave the page
- See [Session Management](#session-management)

**4. Browser issue**
- Solution: Try different browser
- Try different device (phone vs computer)
- Clear browser cache and cookies
- Disable browser extensions

**5. Server issue**
- Error: "Server error" or "500 error"
- Solution: Wait 1 minute, try again
- Check if system status page shows issues
- Contact administrator if persists

---

### ❌ "Accidentally approved/suspended wrong voter"

**Problem:** Clicked approve for wrong person!

**Don't panic! It's reversible:**

**To undo approval:**
1. Find the voter in the list
2. Click [Suspend] to revoke approval
3. Status changes back to suspended
4. Done!

**To undo suspension:**
1. Find the voter in the list
2. Click [Approve] to reinstate them
3. Status changes back to approved
4. Done!

**Why this works:**
- Approval and suspension are toggles
- You can switch back and forth
- No permanent changes per voting period
- Audit logs show what happened

---

### ❌ "Can't find a voter I know exists"

**Problem:** Searched for "John Smith" but didn't find them

**Causes & Solutions:**

**1. Check filters first**
- You may have filters applied
- Click "Clear All" to reset
- Try searching again

**2. Filters hiding results**
- Status filter to "Pending" only
- But voter is "Approved"
- Clear filters, try again

**3. Search term issues**
- You typed: "John Smith"
- System recorded: "Jon Smith" (one 'h')
- Try: "jon" or "smith"
- Be less specific

**4. Different organization**
- You're in Organization A
- Voter is in Organization B
- Switch organizations via dashboard
- Different organization = different voter lists

**5. Voter not registered yet**
- Voter hasn't registered
- Contact them to register first
- Come back after they register

**6. Name variation**
- Registered as: "Johannes Schmidt"
- You searched: "John Smith"
- Try searching last name: "schmidt"
- Try first initial: "joh"

---

### ❌ "Page refreshing/losing my work"

**Problem:** My selections cleared when page refreshed

**Why this happens:**
- Selections are temporary (stored in browser memory)
- Not saved to database until you confirm
- If page reloads, temporary data is lost

**Solutions:**

**Option 1: Complete action before leaving**
```
1. Select voters
2. Click Approve immediately
3. Confirm in dialog
4. Don't navigate away until complete
```

**Option 2: Open in separate tab**
```
1. Right-click bulk action button
2. "Open in new tab"
3. Complete action there
4. Keep main tab for browsing
```

**Option 3: Save progress**
- If selecting many, do smaller batches
- 10 voters: Select & Approve
- Next 10 voters: Select & Approve
- Smaller batches = less to lose if page refreshes

---

### ❌ "Getting errors with CSV export"

**Problem:** Export to CSV shows error or empty file

**Causes & Solutions:**

**1. No data selected**
- Are you trying to export with Status filter = "No matches"?
- Clear filters to export all data
- Or adjust filters to include voters

**2. File too large**
- Exporting 50,000+ voters
- Break into smaller exports
- Export by date range (monthly)
- Export by status separately

**3. Browser issue**
- Try different browser
- Try incognito/private mode
- Clear cache
- Check browser download settings

**4. Permission issue**
- You may not have export rights
- Contact administrator
- Check role (Staff or Admin can export)

---

## Session Management

### Understanding Sessions

**Session = Your login period**

```
Login
  ↓
Active session (1 hour)
  ↓
Inactivity for 1 hour
  ↓
Session expires (forced logout)
  ↓
Must login again
```

### Session Duration

- **Active:** 1 hour of using the system
- **Idle:** 30 minutes with no activity
- **Maximum:** 8 hours (even if active)

### Staying Logged In

**To keep session active:**
- Keep using the page (any click counts)
- Navigating between pages extends session
- Scrolling extends session
- Typing in search extends session

**Any activity resets the inactivity counter:**
```
Login at 9:00 AM
Use page until 9:30 AM
Not active for 29 minutes
Click something at 9:29 AM (within 30 min window)
Session resets, now good until 10:29 AM
```

### Session Expiration Warning

**Before expiring, you'll see:**

```
⚠️ WARNING

Your session will expire in 5 minutes.
Do you want to continue?

[ No, logout ]  [ Yes, continue ]
```

**What to do:**
- Click **[Yes, continue]** to stay logged in
- Session extends another hour
- No need to login again

### Re-logging In After Expiration

If your session expires:

```
ERROR: Your session has expired.
Please login again.
```

**Steps:**
1. Click [Login]
2. Enter email and password again
3. You're back in the system
4. Any unsaved work is lost
   (This is why small batches are safer)

### "Remember Me" Feature

**What it does:**
- Keeps you logged in for 2 weeks
- Even if you close your browser
- Only on that specific computer

**When to use:**
- Personal computer (home, personal laptop)
- NOT on shared/public computers

**How to enable:**
1. On login page
2. Check "Remember Me" checkbox
3. Click login
4. Stay logged in for 2 weeks

---

## Performance Optimization

### Speeding Up Searches

**Strategy 1: Use specific search terms**
```
❌ Slow: Search "a" (finds 10,000+ voters)
✅ Fast: Search "anderson" (finds 50 voters)
```

**Strategy 2: Combine search + filter**
```
❌ Slow: Search "anderson" in 2000 voters
✅ Fast: Filter Status=Approved, then search "anderson" in 200
```

**Strategy 3: Use User ID**
```
❌ Slow: Search "john" (too many matches)
✅ Fast: Search "USR5234" (exact match)
```

### Speeding Up Bulk Operations

**Strategy 1: Work in batches**
```
❌ Slow: Select 500 voters, bulk approve
✅ Fast: Select 50, approve, repeat 10 times
```

**Strategy 2: Filter first**
```
❌ Slow: Bulk approve without filter (risks wrong voters)
✅ Fast: Filter to specific group, bulk approve
```

**Strategy 3: Plan your approach**
```
❌ Disorganized: Approve random voters whenever
✅ Organized: By region, then by registration date
```

### Network & Browser Optimization

**Checklist:**

- ✅ Close unused browser tabs
- ✅ Disable unnecessary extensions
- ✅ Use wired connection if possible
- ✅ Limit other users on your WiFi
- ✅ Update browser to latest version
- ✅ Clear browser cache regularly
- ✅ Restart browser every few hours
- ✅ Use latest compatible browser (Chrome, Firefox)

---

## Data & Security

### Protecting Voter Privacy

**Remember:**
- Voter information is sensitive
- Don't share screen/screenshots
- Don't email voter lists
- Don't print and leave lying around
- Only approved users should see this data

### Data Entry Accuracy

**When approving voters:**

```
Before clicking [Approve]:
1. Check voter name is correct
2. Verify User ID (if needed)
3. Confirm status change
4. Check you're in right organization
5. Read confirmation dialog carefully
6. Then click [Confirm]
```

### Audit Trail

**Everything is logged:**
- Who approved which voter
- When the approval happened
- Your IP address
- Your browser information

**This is good:**
- Provides accountability
- Helps resolve disputes
- Proves election integrity
- Can't "hide" approvals

**Be aware:**
- Your approvals are traceable
- Be consistent with approval standards
- Document any questionable approvals

---

## Reporting Problems

### When to Contact Support

**Contact support for:**
- ❌ Button not working (all approaches fail)
- ❌ Data appears wrong/inconsistent
- ❌ Repeated errors despite trying solutions
- ❌ Security concerns
- ❌ Account access issues
- ❌ Missing features or functionality

**Don't contact for:**
- ✅ How to use a feature → See documentation
- ✅ General questions → See FAQ
- ✅ Can't find voter → Use search tips
- ✅ Page slow → Try performance tips

### How to Report

**When reporting, include:**
1. **What happened:** Describe the issue
2. **When it happened:** Date and time
3. **How to reproduce:** Steps to make it happen again
4. **What you tried:** Solutions you attempted
5. **Screenshots:** Visual of the problem (if helpful)
6. **Browser/device:** What you're using
7. **Contact info:** How to reach you

**Example good report:**
```
Subject: [Approve] Button Not Working

What happened:
I clicked the [Approve] button for voter USR5234 (John Smith)
but nothing happened.

When:
February 23, 2026, 2:30 PM

Steps to reproduce:
1. Go to /organizations/election2024/voters
2. Search "USR5234"
3. Click [Approve]
4. Nothing happens

What I tried:
- Waited 5 seconds
- Refreshed page
- Tried different browser
- Cleared cache
- Nothing worked

Browser: Chrome 119 on Windows 10
Contact: john@example.com
```

### How to Contact

Email: support@yourplatform.com
Phone: +1-555-SUPPORT (if available)
Chat: Built-in chat (if available on platform)

---

## Quick Reference

| Problem | Quick Fix |
|---------|-----------|
| Page slow | Clear cache, close tabs |
| Can't approve | Check role, session, filters |
| Wrong voter approved | Click [Suspend] to undo |
| Can't find voter | Clear filters, try search |
| Session expired | Login again |
| Numbers wrong | Refresh page, clear filters |
| Export not working | Try smaller batch |
| Buttons not responding | Try different browser |

---

## Next Steps

👉 **Need accessibility help?** Go to [Accessibility Guide](./08-accessibility.md)

👉 **Having specific issues?** See troubleshooting in other guides

👉 **Want to change language?** Go to [Language Settings](./09-language-settings.md)

---

## 🆘 Still Need Help?

- **General questions?** Review [Getting Started](./01-getting-started.md)
- **Using accessibility tools?** See [Accessibility Guide](./08-accessibility.md)
- **Language support?** See [Language Settings](./09-language-settings.md)
- **Still stuck?** Contact support at support@yourplatform.com

---

**Happy voting! 🗳️**
