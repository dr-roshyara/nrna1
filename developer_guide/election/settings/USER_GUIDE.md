# Election Settings — Admin User Guide

## Overview

Election Settings let you configure voting rules and security features for each election without contacting technical support. You can enable IP-based voting limits, offer abstain options, and set candidate selection rules.

**Who Can Access:** Organization Admins and Election Chiefs/Deputies  
**Where:** Election Management → Settings  
**Impact:** Changes take effect immediately (with confirmation for active elections)

---

## Quick Start

### 1. Open Election Settings
1. Go to **Election Management**
2. Click on the election name
3. Click **Settings** in the left menu

### 2. Configure Voting Rules
- **Enable IP Restrictions** — Limit votes from the same location
- **Allow Abstain** — Let voters skip voting
- **Set Selection Rules** — Specify how many candidates must be selected

### 3. Save Changes
- Click **Save Settings** button
- If election is active with votes, confirm the change
- You'll see a success message

---

## Features

## 1️⃣ Voter Access Control — IP Address Restrictions

### What It Does
Prevents voters from the same location (IP address) from voting multiple times. Useful for preventing vote manipulation from shared networks.

### How to Enable

**Step 1: Toggle "IP Address Restriction"**
- Click the switch to turn it ON (blue = enabled)

**Step 2: Set Maximum Votes Per IP**
- Enter a number between **1 and 50**
- Example: `2` means each IP can submit maximum 2 votes
- Default: `4`

**Step 3: (Optional) Whitelist Safe IPs**
- If you have a shared office or school network, you can exempt it
- Click in the "Whitelisted IP addresses" box
- Enter one address per line:
  ```
  192.168.1.1
  10.0.0.0/24
  203.45.67.89
  ```

### IP Format Options

**Individual IP Address**
```
192.168.1.1    ← Single computer
203.45.67.89   ← Single location
```

**CIDR Range** (for networks)
```
10.0.0.0/8        ← Entire Class A network
192.168.0.0/16    ← Entire Class B network
203.45.67.0/24    ← Specific subnet
```

### Example: Whitelisting Your Office
If your office network is `10.50.0.0/16`, add:
```
10.50.0.0/16
```
All employees in that network can now vote as many times as the limit allows, without being blocked.

### How It Works Behind the Scenes
- When a voter starts voting, their IP address is recorded
- On the next voting attempt, the system counts how many completed votes came from that IP
- If limit is reached, that IP is blocked with message: **"Maximum X votes allowed from your IP address"**
- Whitelisted IPs skip the check entirely

---

## 2️⃣ Ballot Options — Abstain / No Vote

### What It Does
Allows voters to explicitly register that they don't want to vote or abstain from a particular election.

### How to Enable

**Step 1: Toggle "Allow No Vote / Abstain"**
- Click the switch to turn it ON (blue = enabled)

**Step 2: (Optional) Customize the Label**
- Default label: **"No vote / Abstain"**
- Examples:
  - `I prefer not to vote`
  - `Abstain`
  - `Pass`
  - `No preference`

### What Voters See
When this is enabled, voters will see an additional option on the ballot:

```
[ ] Candidate A
[ ] Candidate B
[✓] No vote / Abstain    ← This appears
```

Selecting this option counts as a complete ballot with no candidates selected.

---

## 3️⃣ Candidate Selection — Voting Rules

### What It Does
Controls how many candidates voters must or can select on each ballot.

### Options Explained

| Mode | Meaning | Example |
|------|---------|---------|
| **Any number** | Voters choose freely | 0, 1, 2, or all candidates OK |
| **Exactly N candidates** | Must select exactly this many | Choose exactly 3 or vote invalid |
| **At least N candidates** | Minimum selection | Must choose 3+, can choose more |
| **At most N candidates** | Maximum selection | Can choose up to 3 (0, 1, 2, 3 OK) |
| **Between min and max** | Range selection | Choose between 2 and 5 candidates |

### Common Scenarios

#### Scenario 1: Board Election (Choose 3)
```
Selection constraint type: Exactly N candidates
Exact number of candidates: 3
```
Voters must select exactly 3 candidates or their ballot is invalid.

#### Scenario 2: Survey (Vote for Favorites)
```
Selection constraint type: At most N candidates
Maximum candidates: 5
```
Voters can select 0, 1, 2, 3, 4, or 5 candidates.

#### Scenario 3: Committee Selection (Minimum Turnout)
```
Selection constraint type: At least N candidates
Minimum candidates: 1
```
Voters must select at least 1 candidate (voting is required).

#### Scenario 4: Open-Ended
```
Selection constraint type: Any number
```
Voters can select any number of candidates, including zero (if "No vote" is enabled).

---

## ⚠️ Active Election Warning

### What It Means
If your election is currently active and has received votes, a warning appears:

```
⚠️ WARNING: Election is active with votes
This election is currently active and has received votes. 
Any changes made here will take effect immediately and affect 
the voting experience.

☑️ I understand and want to proceed with changes
```

### Why It Matters
- **Before** you enable settings: They apply immediately to new voters
- **Changes** take effect instantly — no grace period
- **Examples:**
  - Enable IP restriction mid-election → New voters see the limit
  - Add abstain option → Voters can use it immediately
  - Change selection rule → New voters see the new rule

### When to Use This
Only check this box if you're confident in your changes. Common scenarios:
- Realized you forgot to enable a setting → Safe to enable mid-election
- Need to fix an IP whitelist → Safe to update
- Change the abstain label → Safe to update

---

## 📊 Settings History

At the bottom of the settings page, you'll see:

```
Settings History
─────────────────────────
Version: 5
Last updated: April 12, 2026 at 2:45 PM
Updated by: Jane Admin
```

This helps you:
- **Track who** made the change (Jane Admin)
- **Know when** the change happened (timestamp)
- **See the version** (for diagnosing conflicts)

---

## ✅ Save & Validate

### What Happens When You Save

1. **Form validation** — All values are checked:
   - Max votes per IP: between 1–50 ✓
   - IP whitelist: proper format (IPs/CIDR) ✓
   - Candidate numbers: logical ranges ✓

2. **Conflict check** — If another admin edited settings:
   - You'll see: **"Settings were modified by another user"**
   - Action: Reload the page and try again

3. **Confirmation** (if election is active):
   - You'll see: **"This election is active with votes..."**
   - Action: Check the confirmation box and re-submit

4. **Success** — Green message appears:
   - **"Settings saved."**
   - Changes are now in effect

---

## Common Tasks

### Task 1: Prevent Multiple Votes from Same Office
```
1. Open Election Settings
2. Toggle "IP Address Restriction" ON
3. Set "Maximum votes per IP" = 2
4. In "Whitelisted IP addresses", enter your office subnet:
   192.168.1.0/24
5. Click "Save Settings"
✓ Done! Your office can vote 2x, others can vote 2x from any IP
```

### Task 2: Allow Voters to Abstain
```
1. Open Election Settings
2. Toggle "Allow No Vote / Abstain" ON
3. (Optional) Change label to "I prefer not to vote"
4. Click "Save Settings"
✓ Done! Voters now see the abstain option
```

### Task 3: Require Exactly 5 Candidates
```
1. Open Election Settings
2. Scroll to "Selection constraint type"
3. Select "Exactly N candidates"
4. Enter 5 in the text field
5. Click "Save Settings"
✓ Done! Voters must select exactly 5 or vote is invalid
```

### Task 4: Fix IP Whitelist During Election
```
1. Open Election Settings
2. Update the whitelist with correct IP
3. Confirm "I understand and want to proceed with changes"
4. Click "Save Settings"
✓ Done! The change takes effect immediately
```

---

## Troubleshooting

### Problem: "Settings were modified by another user"

**What Happened:**
Another admin edited settings while you were editing. Your changes conflicted.

**How to Fix:**
1. Click **Cancel** (don't submit again)
2. Reload the page (press F5)
3. You'll see the latest settings from the other admin
4. Make your changes again and save

---

### Problem: IP Restriction Isn't Working

**Check This:**
1. Is the toggle **blue (ON)**?
2. Is the limit set correctly? (1–50)
3. Check if the IP is in the whitelist — whitelisted IPs bypass the limit

**Ask Support If:**
- Voter is blocked but should be allowed
- Voter isn't blocked but should be
- IP whitelist entries aren't working as expected

---

### Problem: Can't Save Settings / "Settings is missing expected key"

**What Happened:**
A required field is empty or invalid.

**How to Fix:**
1. Check that all fields have values:
   - Max votes per IP: number 1–50
   - IP whitelist: valid IPs/CIDR or leave empty
   - Selection constraint type: one selected
2. Re-submit

---

### Problem: Voters Complain Changes Happened Unexpectedly

**What Happened:**
Election was active and you changed settings without warning voters.

**How to Prevent Next Time:**
- If election is active **with votes**, always confirm the warning
- Consider announcing changes to voters via email before updating
- Test settings on a demo election first

---

## Settings Reference

### IP Restriction

| Setting | Range | Default | Notes |
|---------|-------|---------|-------|
| Enabled | true/false | false | Off by default |
| Max per IP | 1–50 | 4 | Can change anytime |
| Whitelist | IPs/CIDR | empty | Optional — leave blank if not needed |

**Examples:**
- `2` = Each IP can vote twice
- `1` = One vote per IP (strict)
- `10` = Ten votes per IP (lenient)

### Ballot Options

| Setting | Values | Default | Notes |
|---------|--------|---------|-------|
| Enabled | true/false | false | Off by default |
| Label | Text (0–100 chars) | "No vote / Abstain" | Customizable |

**Examples:**
- `"No vote"` = Simple
- `"I prefer not to vote"` = Formal
- `"Abstain"` = Traditional
- `"Pass"` = Casual

### Selection Constraint

| Type | Min | Max | Usage |
|------|-----|-----|-------|
| Any number | N/A | N/A | No restrictions |
| Exactly N | — | 1+ | Board elections (exactly 3 directors) |
| At least N | 1+ | — | Minimum participation |
| At most N | — | 1+ | Maximum per ballot (up to 5) |
| Between min & max | 1+ | 1+ | Range (2–5 candidates) |

---

## Tips & Best Practices

### ✓ Do This
- **Test in demo election first** — Try settings before the real election
- **Enable restrictions before voting starts** — Easier than mid-election
- **Keep labels clear** — "No vote", "Abstain", "I pass" are all OK
- **Whitelist only trusted IPs** — Office networks, not public WiFi
- **Document your settings** — Note why you enabled restrictions

### ✗ Don't Do This
- **Enable restrictions mid-election without warning** — Voters may be confused
- **Use unclear labels** — "XYZ" means nothing to voters
- **Whitelist too many IPs** — Defeats the purpose of restrictions
- **Change rules constantly** — Confuses voters and staff
- **Set impossible selection numbers** — E.g., "At most 10" when only 5 candidates exist

---

## Need Help?

### Quick Reference

| Question | Answer | Location |
|----------|--------|----------|
| How do I enable IP restrictions? | Settings > IP Address Restriction toggle | Voter Access Control |
| What's CIDR notation? | 10.0.0.0/24 means 10.0.0.0 to 10.0.0.255 | IP Whitelist help |
| Can I change settings while voting? | Yes, but warn voters first | Active Election Warning |
| What happens if I change selection rules mid-election? | New votes follow new rules, old votes unchanged | Settings take effect immediately |

### Contact Support If
- IP restrictions not working as expected
- Need to whitelist a complex network
- Settings won't save with validation error
- Need to audit who changed what and when

---

## Appendix: IP Whitelist Examples

### Example 1: Single Office Computer
```
203.45.67.89
```
Only this one IP can vote without limit.

### Example 2: Small Office Network
```
192.168.1.0/24
```
Any IP from 192.168.1.0 to 192.168.1.255 can vote without limit.

### Example 3: Multiple Office Subnets
```
192.168.1.0/24
10.50.0.0/16
203.45.67.89
```
Three separate networks + one specific IP are whitelisted.

### Example 4: Entire Organization (Class B)
```
10.0.0.0/16
```
Any IP from 10.0.0.0 to 10.255.255.255 can vote without limit.

---

## Glossary

**IP Address:** A unique number identifying a computer on the network (e.g., 192.168.1.1)

**CIDR Notation:** A way to write IP ranges (e.g., 10.0.0.0/24 means 10.0.0.0–10.0.0.255)

**Whitelist:** A list of IPs allowed to bypass restrictions

**Optimistic Locking:** A safety mechanism that prevents two admins' changes from conflicting

**Settings Version:** A number that increments each time settings are saved (tracks changes)

**Active Election:** An election currently accepting votes

---

---

# Phase 2: Voter Verification — Admin User Guide

## Overview

**Status:** Admin endpoints available (enforcement coming)  
**Who Can Access:** Organization Admins and Election Chiefs/Deputies  
**Where:** Election Management → Voters → Verify

Voter Verification lets you confirm each voter's identity during a video call by recording their IP address and/or device fingerprint. Once verified, voters can only vote from that pre-verified location/device, adding an extra layer of security.

**Key Difference from IP Restrictions:**
- **IP Restriction** (Phase 1) — Limits total votes per IP address (any voter)
- **Voter Verification** (Phase 2) — Ties specific voter to specific device

---

## Quick Start

### 1. Enable Voter Verification
1. Go to **Election Management** → Your Election → **Settings**
2. Scroll to **Voter Verification Mode**
3. Choose one:
   - `IP Address Only` — Voter must vote from same IP
   - `Device Only` — Voter must vote from same device
   - `Both IP and Device` — Voter must match both
   - `None` (default) — No verification required
4. Click **Save Settings**

### 2. Verify a Voter (During Video Call)
1. Go to **Voters** section
2. Click **Verify** next to voter's name
3. Record their IP address: (shown automatically OR ask them to say it)
4. Record their device fingerprint: (will be captured automatically when they vote)
5. Add optional notes about the call
6. Click **Save Verification**

### 3. Voter Attempts to Vote
- If verified: Voter can only vote from that IP/device
- If wrong location: Voter blocked with message "Voting from different location"
- If not verified yet: Blocked until you verify them

---

## Features

## 1️⃣ Voter Identity Verification

### What It Does
Confirms that each voter is who they claim to be before allowing votes. Prevents account takeover and vote manipulation from unauthorized locations.

### When to Use
- **High-Security Elections** — Board elections, sensitive votes
- **Remote Participants** — Video calls with overseas voters
- **Contested Elections** — When you need audit trail of who verified whom
- **Regulatory Requirement** — Some jurisdictions require identity verification

### How It Works

**Step 1: During Video Call with Voter**
- Call voter via Zoom/Teams/Phone
- Ask them to confirm their identity
- Check ID if needed (passport, driver's license, etc.)

**Step 2: Record Verification**
- Open **Voters** section
- Click **Verify** for that voter
- Enter their current IP address (or let system detect it)
- Add notes (e.g., "Verified ID passport on 4/12/26")
- Click **Save Verification**

**Step 3: Voter Votes**
- Voter logs in from verified IP address
- System recognizes them
- Voter can proceed to vote
- Vote cast

---

## 2️⃣ Verification Modes

### `None` (Default)
**No verification required.** Voters can vote from anywhere.
```
Result: Anyone from any IP/device can vote
```

### `IP Address Only`
**Voter must vote from their verified IP address.**
- Useful if: You verified them on their office network or home WiFi
- Example: Verify voter at 192.168.1.50 → They can only vote from 192.168.1.x
- Blocks: Same voter trying to vote from 4G phone

### `Device Only`
**Voter must vote from the same device/browser.**
- Useful if: Voter may move between locations but uses same laptop
- Example: Verify voter on their MacBook → They can only vote from that MacBook
- Blocks: Same voter on different computer

### `Both IP and Device`
**Maximum security — voter must match BOTH IP and device.**
- Useful if: Highest risk election (board votes, sensitive decisions)
- Example: Verify voter at home on MacBook → Must vote from home on MacBook
- Blocks: Different IP OR different device

---

## 3️⃣ Admin Verification Workflow

### Scenario: Election with 50 Voters

**Timeline:**
```
Monday:   Schedule video calls with voters
Tuesday:  Call voters 1–15, verify in system
Wednesday: Call voters 16–35, verify
Thursday: Call remaining voters, verify
Friday:   Election voting opens
```

**For Each Voter During Call:**

```
1. Open Voters section
2. Click [Verify] button next to voter's name

   → Opens verification form

3. Record Information:
   ┌─────────────────────────────┐
   │ Voter: John Smith           │
   │ IP Address: 192.168.1.50    │
   │ [✓] Device auto-detected    │
   │ Notes: "ID checked, valid"  │
   └─────────────────────────────┘

4. Click [Save Verification]
   → "Voter verified successfully"

5. Done! Voter can now vote.
```

### What If Voter's IP Changes?

**Scenario:**
- Verified on Monday at office (192.168.1.50)
- Calls in Tuesday from home (203.45.67.89)
- Blocked: "You are voting from a different IP address"

**What to Do:**
1. Option A: Re-verify them at new location (run a quick call)
2. Option B: Switch to `Device Only` mode instead of `IP Only`
3. Option C: Use `None` mode and accept the risk

---

## 4️⃣ Revoking Verification

### When to Revoke
- Voter compromised (account hacked)
- Voter lost their device (had to get new computer)
- Dispute over legitimacy
- Voter requests to be re-verified from different location

### How to Revoke

```
1. Go to Voters section
2. Find the verified voter
3. Click [Revoke] button
   → "Voter verification revoked"
4. Voter is now unverified
5. (Optional) Re-verify them at new IP/device
```

---

## 5️⃣ Verification + IP Restriction Interaction

### Important: Verified Voters Bypass IP Count Limit

If you have **both** settings enabled:
- **IP Restriction:** Max 2 votes per IP
- **Voter Verification:** IP mode enabled

**Result:**
```
Unverified voter from IP X:
  → Counts toward IP limit
  → Can vote max 2 times from that IP

Verified voter from their verified IP:
  → SKIPS IP limit check
  → Can vote (enforcement TBD)
```

**Example:**
```
Election Settings:
  ✓ IP Restriction Enabled = max 2 votes per IP
  ✓ Voter Verification = IP Only

Scenario 1 — Verified voter:
  Voter at 192.168.1.50 verified
  → Can vote (not blocked by IP limit)

Scenario 2 — Unverified voter:
  First vote from 10.0.0.1 = OK
  Second vote from 10.0.0.1 = OK
  Third vote from 10.0.0.1 = BLOCKED (IP limit reached)
  Unknown user tries 10.0.0.1 = BLOCKED (IP limit)
```

---

## Common Tasks

### Task 1: Verify 50 Voters Before Election

```
1. Schedule 5 video calls (10 voters each)
2. For each voter:
   - Ask: "Are you ready to vote?"
   - Ask: "Confirm your email?"
   - Record their current IP address
   - Click [Verify]
3. After all 50 verified:
   - Go to Settings
   - Set Voter Verification Mode = "IP Address Only"
   - Click Save Settings
4. Send email to voters:
   "Your identity has been verified.
    You can now vote from your registered IP address."

✓ Done!
```

### Task 2: Switch from IP Only to Device Only (Mid-Election)

**Reason:** Verified voters are trying to vote from different IPs

```
1. Go to Settings
2. Change "Voter Verification Mode" from "IP Only" to "Device Only"
3. Check: "I understand changes take effect immediately"
4. Click Save
5. Notify voters: "You can now vote from any location,
                   as long as you use the same device"

✓ Done! Future votes check device instead of IP
```

### Task 3: Revoke a Compromised Voter

**Scenario:** Voter reports their account was hacked

```
1. Go to Voters
2. Find the voter
3. Click [Revoke] next to their name
4. Confirmation: "Verification revoked"
5. Call the voter:
   - "Have you secured your account?"
   - "What device will you use to vote?"
6. Re-verify them with new info
7. Notify: "Your account is secure and re-verified"

✓ Done!
```

---

## ⚠️ Important Notes

### Voter Experience

If verification is enabled (`mode ≠ None`):

**When voter tries to vote:**
```
1. Voter logs in
2. System checks: "Are you verified?"
   - YES → Proceed to ballot
   - NO → Show message:
     "Your identity needs to be verified.
      Contact the election administrator."
3. Voter cannot proceed until verified
```

### Audit Trail

Every verification is recorded:
```
Voter: Jane Doe
Verified by: John Admin
Date: April 12, 2026 at 2:30 PM
IP: 192.168.1.100
Device: MacBook Air
Notes: "ID verified via video call"
```

This appears in the election audit report.

---

## Troubleshooting

### Problem: "Voter can't vote even though verified"

**Check:**
1. Is Voter Verification Mode enabled? (Settings → not "None")
2. Is the verification status "Active" (not "Revoked")?
3. Did you save after enabling verification mode?

### Problem: "Can't record device fingerprint"

**What Happens:**
- If voter's browser doesn't support device detection
- Or if they disabled JavaScript
- Or if they use old/unsupported browser

**How to Fix:**
- Verify in "IP Address Only" mode instead
- Ask voter to use Chrome, Firefox, Safari, or Edge (latest version)
- Have them enable JavaScript in browser settings

### Problem: "Verified voter blocked from wrong IP"

**This is working correctly!**
- If mode = "IP Only", voter must use verified IP
- If they call from different location:
  1. Option A: Re-verify them at new IP
  2. Option B: Change mode to "Device Only"
  3. Option C: Change mode to "None"

### Problem: "Want to verify voters but haven't done video calls"

**Solutions:**
1. **Schedule calls** — Use Zoom/Teams to verify (async OK)
2. **Email verification** — Wait for Phase 2.3 (future)
3. **Phone verification** — Wait for Phase 2.3 (future)
4. **Skip for now** — Use `Mode = None`, add verification later

---

## Best Practices

### ✓ Do This
- **Test in demo election first** — Verify a test user, try voting
- **Document the process** — Create a checklist for your team
- **Schedule calls in advance** — Don't scramble day-of
- **Use IP Only** — Simpler than device verification
- **Keep records** — Screenshot verification for audit trail
- **Notify voters in advance** — "You'll need to be available for a 5-min call"

### ✗ Don't Do This
- **Verify without ID check** — Ask for official ID for high-stakes elections
- **Skip the video call** — Always talk to voter directly
- **Change modes mid-election** — Causes voter confusion
- **Verify at wrong time** — Don't verify day-before if election next month
- **Forget to save** — Always click the Save button!
- **Enable without warning** — Tell voters about verification requirement

---

## Quick Reference

| Setting | Effect | Use When |
|---------|--------|----------|
| `None` | No verification required | Low-stakes elections, speed matters |
| `IP Only` | IP must match verified address | Medium security, most common |
| `Device Only` | Device must match verified | Voter changes locations frequently |
| `Both` | IP AND device must match | High security, sensitive votes |

---

## Need Help?

### Verification-Specific Questions

| Question | Answer |
|----------|--------|
| Can I verify voters after voting starts? | Yes, but change takes effect immediately |
| What if voter uses VPN? | VPN masks real IP — verify before they use VPN |
| Can voter change device after verified? | Only if mode allows (Device Only — no) |
| How many verifications per voter? | One per voter per election (updates replace) |
| Can I verify someone else's voter? | Only if you have admin/chief role |

### Contact Support If
- Voters can't be verified (technical error)
- Device fingerprinting not working
- Need to bulk-verify 100+ voters
- Need to audit who verified whom

---

## Summary

Voter Verification adds an identity layer:
1. ✅ **Admin verifies** voter during video call (5 minutes)
2. ✅ **System records** verified IP/device
3. ✅ **Voter votes** only from that location/device
4. ✅ **Audit trail** shows who verified when

**Start Simple:**
- Use `IP Only` mode first
- Verify just critical voters
- Expand later if needed
- Test in demo election first

---

## Appendix: Example Verification Call Script

**For Admin to Read During Video Call:**

```
"Hi [Voter Name], thanks for joining. 
This should take about 5 minutes.

First, I need to verify your identity.
Can you please show me your [ID type]?
...
Great, thank you.

Now I'll record your current location information.
You're joining from [their IP address] right now.
And you're using a [Device type - Mac/Windows/iPhone].

When you vote next, you'll need to use the same 
device and location. OK?

Do you have any questions?

Great — you're all set. You can now vote anytime
between [start date] and [end date].

Thanks for participating!"
```

---

## Summary

Election Settings give you fine-grained control over:
1. ✅ **Who votes** — IP restrictions prevent multi-voting
2. ✅ **How voters vote** — Abstain options, selection rules
3. ✅ **Voter verification** — Confirm identity before voting
4. ✅ **When changes happen** — Immediate with confirmation for active elections
5. ✅ **Who changed what** — Audit trail shows timestamp and admin name

**Start Simple:**
- Default settings are safe
- Enable only the features you need
- Test in demo mode first
- Update documentation if you change rules

