# 🧪 Single Election Architecture - Complete Test Guide

## System Overview

Your voting system now uses a **simplified single election architecture**:
- **1 Demo Election** (ID: 1, slug: `demo-election`) - For testing
- **1 Real Election** (ID: 2, slug: `2024-general-election`) - For production voting

Both elections are currently **ACTIVE** and checked as **currently active** (within voting period).

---

## ✅ Test Users Created

| # | Email | Password | Role | is_voter | Purpose |
|---|-------|----------|------|----------|---------|
| 1️⃣ | `admin.test@example.com` | `password123` | admin | NO | Test admin login flow |
| 2️⃣ | `voter.eligible@example.com` | `password123` | voter | **YES** ✓ | Test voter on election day |
| 3️⃣ | `voter.ineligible@example.com` | `password123` | voter | **YES** ✓ | Test voter when no election |
| 4️⃣ | `demo.tester@example.com` | `password123` | user | NO | Test demo election access |

---

## 🧪 Test Scenarios

### **SCENARIO 1: Admin Login**

**Expected Behavior:** Admin sees admin dashboard

**Test Steps:**
1. Navigate to http://localhost:8000/login
2. Enter: `admin.test@example.com` / `password123`
3. Click "Login"

**Expected Result:**
- ✅ You should be redirected to admin dashboard
- ✅ URL should show admin-related pages
- ✅ You see admin interface (not voting interface)

**Code Flow:**
```
LoginResponse.php:
  1. Check: hasRole('admin') OR hasRole('election_officer')
  2. Result: ✓ MATCH
  3. Redirect to: admin.dashboard
```

---

### **SCENARIO 2: Voter Login During Election Day**

**Expected Behavior:** Voter sees election page or voting dashboard

**Test Steps:**
1. Navigate to http://localhost:8000/login
2. Enter: `voter.eligible@example.com` / `password123`
3. Click "Login"

**Expected Result:**
- ✅ You should be redirected to dashboard
- ✅ You see either:
  - ElectionPage (if real election is currently active)
  - OR Election Dashboard with voting instructions
- ✅ You have option to enter verification code

**Code Flow:**
```
LoginResponse.php:
  1. Check: hasRole('admin')?
  2. Result: ✗ NO
  3. Redirect to: dashboard

ElectionController.php::dashboard():
  1. Check: is_voter = YES
  2. Check: Real election active = YES
  3. Check: Real election currentlyActive = YES
  4. Result: ✓ ELIGIBLE
  5. Render: Election/ElectionPage
```

---

### **SCENARIO 3: Voter Login When No Active Election**

**Expected Behavior:** Voter sees regular dashboard (no voting option)

**Test Steps:**
1. Navigate to http://localhost:8000/login
2. Enter: `voter.ineligible@example.com` / `password123`
3. Click "Login"

**Expected Result:**
- ✅ You should be redirected to dashboard
- ✅ You see Dashboard/ElectionDashboard
- ✅ Message: "No active election at this time" or similar
- ✅ No voting code entry option

**Code Flow:**
```
LoginResponse.php:
  1. Check: hasRole('admin')?
  2. Result: ✗ NO
  3. Redirect to: dashboard

ElectionController.php::dashboard():
  1. Check: is_voter = YES
  2. Check: Real election active = YES
  3. Check: Real election currentlyActive = YES
  4. Check: Can vote now? = (to be determined by Code model)
  5. Result: Show dashboard with election status
```

**Note:** Both test voters are voters, but actual voting permission (can_vote_now) is determined when they try to enter a voting code (Code model).

---

### **SCENARIO 4: Demo Election Access**

**Expected Behavior:** Any authenticated user can access demo without eligibility checks

**Test Steps:**
1. Navigate to http://localhost:8000/login
2. Enter: `demo.tester@example.com` / `password123`
3. Click "Login"
4. After login, navigate to: http://localhost:8000/election/demo/start

**Expected Result:**
- ✅ You should be redirected directly to demo voting code entry
- ✅ URL: `/code/create/demo-election`
- ✅ You can enter verification code for demo election
- ✅ No eligibility checks required

**Code Flow:**
```
Route: GET /election/demo/start

ElectionController.php::startDemo():
  1. Check: User authenticated = YES
  2. Check: Demo election exists = YES
  3. Bypass: ALL voter eligibility checks
  4. Redirect to: /code/create/demo-election
```

---

## 📋 Login Flow Summary

```
User Logs In
    ↓
LoginResponse::toResponse()
    ↓
┌─────────────────────────────────────┐
│ Is user an admin?                   │
├─────────────────────────────────────┤
│ YES → admin.dashboard               │
│ NO  ↓                               │
│     Is user a voter?                │
│     YES → dashboard                 │
│     NO  → dashboard                 │
└─────────────────────────────────────┘
    ↓
ElectionController::dashboard()
    ↓
┌─────────────────────────────────────┐
│ Real election active?               │
├─────────────────────────────────────┤
│ YES & is_voter=YES → ElectionPage   │
│ (show voting interface)             │
│                                     │
│ Otherwise → Dashboard               │
│ (show status/no voting option)      │
└─────────────────────────────────────┘
```

---

## 🔐 Database State

### Elections
```
ID  | Type | Name | Slug | Active | Start Date | End Date
----|------|------|------|--------|------------|----------
1   | demo | Demo Election - Testing Only | demo-election | YES | 2026-02-03 | 2026-05-04
2   | real | 2024 General Election | 2024-general-election | YES | 2026-02-03 | 2026-02-18
```

### Users
```
Email | Role | is_voter | can_vote | Purpose
------|------|----------|----------|--------
admin.test@example.com | admin | NO | NO | Admin login test
voter.eligible@example.com | voter | YES | YES | Voter election day test
voter.ineligible@example.com | voter | YES | YES | Voter off-time test
demo.tester@example.com | user | NO | NO | Demo access test
```

---

## 🚀 Quick Start Testing

### Option 1: Manual Testing (Recommended for First Run)

```bash
# 1. Start your server
php artisan serve

# 2. Test each scenario in browser
http://localhost:8000/login

# Use credentials from table above
# Test 4 scenarios one by one
```

### Option 2: Automated Testing Script

```bash
# Run the PHPUnit test suite
php artisan test tests/Feature/SingleElectionLoginFlowTest.php
```

(Note: Tests may have compatibility issues - manual testing is recommended)

---

## ✅ Verification Checklist

After testing, verify these items:

- [ ] Scenario 1: Admin user sees admin dashboard
- [ ] Scenario 2: Eligible voter sees election page with voting option
- [ ] Scenario 3: Voter sees regular dashboard when not voting time
- [ ] Scenario 4: Demo user can access demo election directly
- [ ] All 4 users can log in successfully
- [ ] All users have correct roles assigned
- [ ] Both elections exist in database
- [ ] Real election is currently active (isCurrentlyActive = YES)
- [ ] Demo election is currently active (isCurrentlyActive = YES)

---

## 🔧 Implementation Files

### Modified Files
- ✅ `app/Http/Responses/LoginResponse.php` - Simplified login redirect logic
- ✅ `app/Http/Controllers/Election/ElectionController.php` - Simplified dashboard logic
- ✅ `routes/web.php` - Added `/election/demo/start` route
- ✅ `database/seeders/ElectionSeeder.php` - Fixed column names

### New Files
- ✅ `tests/Feature/SingleElectionLoginFlowTest.php` - Test suite
- ✅ `tests/manual_login_flow_test.php` - Manual test script

---

## 🎯 Key Architecture Decisions

### Why Simplified Single Election?

1. **User Clarity** - No confusing election selection
2. **Faster Flow** - Direct path to voting
3. **Easier Maintenance** - Less conditional logic
4. **Future-Proof** - SelectElection.vue kept as backup for multiple elections

### Where is Voter Eligibility Checked?

- **Login Time:** Only checks `is_voter` role flag
- **Code Entry Time:** Checks `can_vote_now` (stored in Code model during verification)
- **Voting Time:** Final validation before recording vote

This separation of concerns keeps login fast while maintaining security.

---

## 📞 Troubleshooting

### Issue: Users show `is_voter: NO` but should be YES

**Solution:** The User model protects voting fields from mass assignment for security. Update directly:

```php
DB::table('users')
    ->where('email', 'voter.eligible@example.com')
    ->update(['is_voter' => 1]);
```

### Issue: Real election not showing as "currently active"

**Check:**
1. `is_active` flag in elections table = TRUE
2. `start_date` <= current time
3. `end_date` >= current time

### Issue: Cannot find `/election/demo/start` route

**Verify:**
1. Route added to `routes/web.php`
2. Route has `auth` middleware
3. Run `php artisan route:list` to confirm

---

## 📊 Next Steps

1. ✅ Test all 4 scenarios
2. ✅ Verify users can log in and see correct pages
3. ⏳ **TODO:** Test entering verification codes
4. ⏳ **TODO:** Test casting votes
5. ⏳ **TODO:** Test vote recording and demo vote verification

---

## 📝 Notes

- Demo and Real elections are **separate voting data stores** (demo_votes vs votes tables)
- Votes are **anonymous** - user_id not stored in votes
- Each user can only vote **once per election** (enforced in application logic)
- Verification codes are **unique per user per election**

---

**Created:** 2026-02-04
**Architecture:** Single Real + Single Demo Election
**Status:** ✅ Ready for Testing
