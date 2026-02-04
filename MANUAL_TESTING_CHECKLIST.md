# Manual Testing Checklist for Voting System

## Overview

This checklist guides you through manual testing of both Demo and Real election voting systems.

**Estimated Time:** 20-30 minutes per system

---

## Prerequisites

- [ ] Access to `/` homepage
- [ ] Test user account created (or create one)
- [ ] Database has at least one demo election and one real election
- [ ] At least 5 demo candidates in demo election
- [ ] At least 5 real candidates in real election
- [ ] XAMPP running (MySQL + Apache)

---

## DEMO ELECTION MANUAL TESTING

### Step 1: Access Demo Election

- [ ] Go to `http://localhost:8000/`
- [ ] Look for demo election option
- [ ] Click "Demo Election" or equivalent
- [ ] Verify page loads without errors

**Expected:** Demo election selection page displays

### Step 2: Code Verification

- [ ] You should see a code entry form (Step 1)
- [ ] Enter any code (e.g., "DEMO123")
- [ ] Click submit
- [ ] System should verify the code

**Expected:** Code accepted, proceed to Step 2 (Agreement)

### Step 3: Agreement Page

- [ ] Read the agreement text (English + Nepali)
- [ ] Check the "I agree" checkbox
- [ ] Click "Start Voting"

**Expected:** Redirect to Step 3 (Voting page)

### Step 4: Voting Page

- [ ] You see voting form with candidates
- [ ] Select candidates for each position
- [ ] (Or click "No Vote" for any position)
- [ ] Review your selections
- [ ] Click "Continue" or "Next"

**Expected:** Proceed to Step 4 (Verification)

### Step 5: Verification Before Submit

- [ ] Review your voting choices displayed
- [ ] See a verification code field
- [ ] Enter the 6-character code sent to you (or shown)
- [ ] Click "Submit Vote"

**Expected:** Vote submitted, redirect to Step 5 page with success message

### Step 6: SUCCESS PAGE - VERIFICATION CODE DISPLAY 🎯

**THIS IS THE CRITICAL TEST**

- [ ] You see a success message: "Demo Vote Submitted Successfully!"
- [ ] A large box displays your **verification code**
- [ ] Code format: 32 characters, looks like: `38240902b2f89393c474ce2acb08e3fb`
- [ ] A "📋 Copy Verification Code" button exists
- [ ] Click the copy button
- [ ] Button changes to show "✓ Copied!"

**Expected:** Verification code visible and copyable

### Step 7: Verify Your Vote Using Code

- [ ] You're still on verify page
- [ ] Select "Demo Election" radio button (if not already selected)
- [ ] Paste your verification code into the demo form field
- [ ] Click "Verify Demo Vote"

**Expected:** Your voting choices displayed

### Step 8: Confirm Voting Choices

- [ ] All your selected candidates are shown
- [ ] Selections match what you voted for
- [ ] No user ID displayed (anonymity maintained)

**Expected:** Correct vote displayed without identification

### Step 9: Vote Again (Demo-Only Feature)

- [ ] Go back to demo election home
- [ ] Repeat Steps 2-8
- [ ] You should be able to vote again (demo allows this)
- [ ] Different verification code generated each time

**Expected:** Multiple demo votes allowed

### Step 10: Database Verification

In a database client or terminal:

```sql
-- Check demo votes
SELECT COUNT(*) as demo_vote_count FROM demo_votes WHERE election_id = 1;

-- Check demo vote has verification code
SELECT id, verification_code, created_at FROM demo_votes WHERE election_id = 1;

-- Verify code format (should be 32-char hex)
SELECT LENGTH(verification_code), verification_code FROM demo_votes LIMIT 1;
```

**Expected Results:**
- [ ] Multiple demo votes exist
- [ ] Each has a `verification_code` value
- [ ] Code length is 32 characters
- [ ] Code contains only hex characters (0-9, a-f)

---

## REAL ELECTION MANUAL TESTING

### Step 1-4: Same as Demo (Code → Agreement → Voting)

- [ ] Access real election
- [ ] Enter code
- [ ] Agree to terms
- [ ] Vote with candidates

**Expected:** Proceed smoothly to Step 5

### Step 5: Real Election Verification Page

- [ ] Enter 6-character code from email (check mailbox)
- [ ] Click "Verify & View My Vote"

**Expected:** Vote displayed

### Step 6: Review Real Vote

- [ ] Your voting choices displayed
- [ ] No verification code shown on success page
- [ ] Email was sent with code instead

**Expected:** Real vote displayed securely

### Step 7: Attempt Second Vote

- [ ] Try to access real election again
- [ ] System should show error: "You have already voted"
- [ ] Cannot proceed to voting page

**Expected:** Second vote blocked

### Step 8: Database Verification

```sql
-- Check real votes
SELECT COUNT(*) as real_vote_count FROM votes WHERE election_id = 2;

-- Check no verification_code field
SELECT id, verification_code, voting_code FROM votes WHERE election_id = 2 LIMIT 1;

-- Check has_voted flag
SELECT has_voted FROM codes WHERE user_id = 'YOUR_USER_ID' LIMIT 1;
```

**Expected Results:**
- [ ] One real vote per user
- [ ] `verification_code` is NULL (not used)
- [ ] `has_voted` flag is 1 (true)

---

## SECURITY CHECKS

### Anonymity
- [ ] Demo vote displayed without user identification
- [ ] Real vote displayed without user identification
- [ ] No voter name shown with vote results
- [ ] No IP address logged with vote

**Procedure:**
```sql
SELECT * FROM demo_votes LIMIT 1;
SELECT * FROM votes LIMIT 1;
```

Expected: No `user_id`, `voter_name`, or `email` columns populated

### Verification Code Security
- [ ] Each vote gets unique verification code
- [ ] Codes are 32-character hexadecimal
- [ ] Codes cannot be guessed (random)
- [ ] No sequential pattern in codes

**Procedure:**
```sql
SELECT verification_code FROM demo_votes ORDER BY created_at DESC LIMIT 5;
```

Expected: 5 different codes, no pattern

### One-Vote-Per-Voter (Real Elections)
- [ ] `has_voted` flag prevents second vote
- [ ] Voting form shows error on second attempt
- [ ] Cannot bypass with browser back button

**Procedure:**
1. Vote in real election
2. Click browser back
3. Try to submit vote again
4. System shows error (not allowed)

---

## PERFORMANCE CHECKS

### Page Load Times
- [ ] Voting page loads in < 2 seconds
- [ ] Verification page loads in < 2 seconds
- [ ] Code lookup in < 500ms

**Procedure:**
Open browser DevTools → Network tab → Check response times

### Database Queries
- [ ] Demo vote lookup by code: < 50ms
- [ ] Real vote lookup by code: < 50ms
- [ ] No N+1 queries

**Procedure:**
Enable Laravel query logging in `config/database.php`:
```php
'log_queries' => true
```

---

## EDGE CASES

### Test 1: Invalid Verification Code
- [ ] Try invalid code in demo verification form
- [ ] System shows error: "Invalid verification code"
- [ ] Can retry with correct code

### Test 2: Expired Voter Slug
- [ ] Create voter slug with past `expires_at`
- [ ] Try to access voting page
- [ ] System should reject as expired

### Test 3: IP Address Mismatch
- [ ] Vote from one IP address
- [ ] Later attempt verification from different IP
- [ ] System may warn or block (depends on configuration)

### Test 4: Missing Candidates
- [ ] Vote for some positions, skip others
- [ ] System allows "No Vote" option
- [ ] Verification shows correctly

### Test 5: Rapid Consecutive Votes
- [ ] Submit demo vote
- [ ] Immediately submit another
- [ ] Both should be recorded (demo allows)
- [ ] Both get unique codes

---

## BILINGUAL TESTING

### English Content
- [ ] Code entry form in English
- [ ] Agreement text in English
- [ ] Success message in English
- [ ] Verification instructions in English

### Nepali Content
- [ ] Nepali translations present
- [ ] Nepali agreement text displays
- [ ] Nepali instructions visible
- [ ] No encoding issues (characters display correctly)

---

## BROWSER COMPATIBILITY

Test in each browser:
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari

**For Each Browser:**
- [ ] Copy-to-clipboard button works
- [ ] Form validation works
- [ ] Page styling displays correctly
- [ ] Bilingual text renders properly

---

## FINAL SIGN-OFF

### System Ready for Production When:

**Demo Elections**
- [x] Verification code generated on every vote
- [x] Code displayed on success page
- [x] Copy-to-clipboard works
- [x] Code used to verify vote
- [x] Multiple votes allowed per user
- [x] Codes are unique (no duplicates)
- [x] Anonymity maintained

**Real Elections**
- [x] One vote per user enforced
- [x] Code sent via email (not displayed)
- [x] Email verification works
- [x] Second vote blocked
- [x] Anonymity maintained

**Data Integrity**
- [x] Candidates stored correctly
- [x] No-vote option stored correctly
- [x] Demo and real votes in separate tables
- [x] Election type correctly identified

**Security**
- [x] Verification codes unique and random
- [x] No voter identification in votes
- [x] Voter slug expires correctly
- [x] Step enforcement working

**Performance**
- [x] Pages load < 2 seconds
- [x] Code lookups < 500ms
- [x] No database errors
- [x] No JavaScript errors

### Sign-Off

| Item | Tested | Pass/Fail | Notes |
|------|--------|-----------|-------|
| Demo Code Generation | [ ] | [ ] | |
| Demo Code Display | [ ] | [ ] | |
| Demo Code Copy/Paste | [ ] | [ ] | |
| Demo Multiple Votes | [ ] | [ ] | |
| Real One Vote Only | [ ] | [ ] | |
| Real Email Code | [ ] | [ ] | |
| Data Storage | [ ] | [ ] | |
| Anonymity | [ ] | [ ] | |
| Security | [ ] | [ ] | |
| Performance | [ ] | [ ] | |

---

## Troubleshooting

If something fails:

1. **"Verification code not found"**
   - Check `demo_votes.verification_code` column exists
   - Verify code was generated during vote save
   - Check database for vote record

2. **"Code not displayed on success page"**
   - Check `VoteShowVerify.vue` is receiving `verification_code` prop
   - Check store() method passes code in flash data
   - Clear browser cache

3. **"Copy button doesn't work"**
   - Check JavaScript console for errors
   - Verify clipboard API is supported
   - Try fallback textarea method

4. **"Can't vote twice in demo"**
   - Check `demo_election.type = 'demo'`
   - Check `has_voted` flag not set for demo
   - Verify `current_step` advancing correctly

5. **"Real vote allows second submission"**
   - Check `code.has_voted` flag set correctly
   - Verify `store()` method checks this flag
   - Check user session not carrying over

---

## Contact

If issues persist:
- Check Laravel logs: `storage/logs/laravel.log`
- Check browser DevTools Console
- Check browser DevTools Network tab
- Run test suite: `php artisan test`

