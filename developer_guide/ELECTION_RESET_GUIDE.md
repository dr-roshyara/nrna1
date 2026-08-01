# NRNA Election System - Reset Guide

**Date**: 2025-11-28
**Version**: 1.0.0
**Status**: Production Ready

---

## Overview

This guide explains how to safely reset your NRNA election system after running a demo or test election. The reset process allows you to clear all election data and start fresh for the actual election.

---

## Table of Contents

1. [What Gets Reset](#what-gets-reset)
2. [Safety Features](#safety-features)
3. [Reset Options](#reset-options)
4. [Step-by-Step Instructions](#step-by-step-instructions)
5. [Database Structure](#database-structure)
6. [Manual Reset (Advanced)](#manual-reset-advanced)
7. [Troubleshooting](#troubleshooting)
8. [Best Practices](#best-practices)

---

## What Gets Reset

### 🗳️ Votes & Codes Reset

When resetting votes and codes, the following data is **permanently deleted**:

| Data Type | Table | Description | Count Check |
|-----------|-------|-------------|-------------|
| **Votes** | `votes` | All cast votes with candidate selections | `SELECT COUNT(*) FROM votes` |
| **Verification Codes** | `codes` | All verification codes (code1, code2, vote_show_code) | `SELECT COUNT(*) FROM codes` |
| **Voter Slugs** | `voter_slugs` | Temporary voting session links | `SELECT COUNT(*) FROM voter_slugs` |
| **User Flags** | `users` | Voting status flags (has_voted, timestamps) | Reset to default |
| **Sessions** | `sessions` | Active user sessions | Cleared |

### 🎯 Candidates Reset

When resetting candidates, the following data is **permanently deleted**:

| Data Type | Table | Description | Count Check |
|-----------|-------|-------------|-------------|
| **Candidates** | `candidacies` | All candidate registrations | `SELECT COUNT(*) FROM candidacies` |
| **Candidate Images** | File system | Uploaded candidate photos | Check `/storage/app/public/candidacies/` |

### ⚠️ What Does NOT Get Reset

The following data is **preserved** during reset:

- ✅ User accounts (`users` table)
- ✅ User profiles and personal information
- ✅ User roles and permissions
- ✅ Electoral posts (`posts` table)
- ✅ System settings and configuration
- ✅ Email logs and notifications
- ✅ System audit logs

---

## Safety Features

Our reset command includes multiple safety features:

### 🔒 Multi-Level Confirmation

1. **Selection Confirmation**: Choose what to reset (votes/candidates/all)
2. **Data Preview**: Shows count of records that will be deleted
3. **Warning Message**: Clear warning that action cannot be undone
4. **User Confirmation**: Must confirm with Yes/No prompt
5. **Double Confirmation**: For complete reset, must type "DELETE ALL"

### 🛡️ Transaction Safety

- All deletions happen within a **database transaction**
- If any error occurs, **entire operation rolls back**
- Database remains in consistent state
- No partial deletions

### 📝 Progress Tracking

- Real-time progress bar showing current step
- Detailed success messages after completion
- Error messages with specific failure details

---

## Reset Options

The `election:reset` command supports three reset modes:

### Option 1: Reset Votes & Codes Only

**Use Case**: Keep candidates, remove all voting data

```bash
php artisan election:reset --votes
```

**What Happens**:
- ✅ Deletes all votes
- ✅ Deletes all verification codes
- ✅ Deletes all voter slugs
- ✅ Resets user voting flags
- ❌ Keeps all candidates

**When to Use**:
- After testing the voting process
- Want to reuse same candidates for actual election
- Need to clear test votes but keep candidate registrations

---

### Option 2: Reset Candidates Only

**Use Case**: Keep voting data, remove all candidates

```bash
php artisan election:reset --candidates
```

**What Happens**:
- ✅ Deletes all candidates
- ❌ Keeps all votes
- ❌ Keeps all codes

**When to Use**:
- After testing candidate registration
- Need to re-register candidates with different data
- Want to clear demo candidates

⚠️ **Warning**: This creates an inconsistent state if votes reference deleted candidates. Use with caution!

---

### Option 3: Complete Reset (Recommended)

**Use Case**: Fresh start for everything

```bash
php artisan election:reset --all
```

**What Happens**:
- ✅ Deletes all votes
- ✅ Deletes all verification codes
- ✅ Deletes all voter slugs
- ✅ Deletes all candidates
- ✅ Resets user voting flags
- ✅ Clears sessions

**When to Use**:
- After completing demo election
- Starting fresh for actual election
- Complete system reset needed

**Confirmation Required**: Must type `DELETE ALL` to proceed

---

### Interactive Mode (No Options)

**Use Case**: Guided reset with menu

```bash
php artisan election:reset
```

**What Happens**:
- Presents interactive menu
- Choose: Votes & Codes / Candidates / Everything / Cancel
- Same safety features as option modes

---

## Step-by-Step Instructions

### 📋 Prerequisites

Before resetting, ensure:

1. **Backup Your Database**
   ```bash
   # Windows (XAMPP)
   cd C:\xampp\mysql\bin
   mysqldump -u root nrna_eu > C:\backup\nrna_backup_20251128.sql

   # Linux/Mac
   mysqldump -u root -p nrna_eu > ~/backup/nrna_backup_$(date +%Y%m%d).sql
   ```

2. **Stop Any Running Elections**
   - Ensure no users are currently voting
   - Check active sessions: `SELECT COUNT(*) FROM sessions WHERE user_id IS NOT NULL`

3. **Notify Committee Members**
   - Inform election committee about reset
   - Schedule reset during low-activity period

---

### 🚀 Performing Complete Reset

#### Step 1: Navigate to Project Directory

```bash
cd C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu
```

#### Step 2: Run Reset Command

```bash
php artisan election:reset --all
```

#### Step 3: Review Data to be Deleted

The system will display:

```
═══ The following data will be PERMANENTLY DELETED ═══

  📊 Votes:        152 records
  🔐 Codes:        98 records
  🔗 Voter Slugs:  98 records
  👤 User flags:   Reset can_vote, has_voted, etc.
  🎯 Candidates:   25 records

⚠️  WARNING: This action CANNOT be undone!

 Are you absolutely sure you want to proceed? (yes/no) [no]:
```

#### Step 4: First Confirmation

Type `yes` and press Enter

#### Step 5: Final Confirmation

```
🚨 FINAL WARNING: You are about to delete ALL election data!

 Type 'DELETE ALL' to confirm complete reset:
```

Type `DELETE ALL` exactly and press Enter

#### Step 6: Wait for Completion

```
Starting reset process...

 5/5 [████████████████████████████] 100% Clearing sessions...

  ✓ Deleted 152 votes
  ✓ Deleted 98 verification codes
  ✓ Deleted 98 voter slugs
  ✓ Reset 420 user voting flags
  ✓ Cleared session data
  ✓ Deleted 25 candidates

╔═══════════════════════════════════════════════════════════╗
║              ✅ Reset Completed Successfully              ║
╚═══════════════════════════════════════════════════════════╝

Your election system is now ready for a fresh start!
```

---

### 🔧 Alternative: Force Reset (Skip Confirmations)

**⚠️ DANGER**: Use only in automated scripts or when you're absolutely certain

```bash
php artisan election:reset --all --force
```

This skips ALL confirmation prompts. **Use with extreme caution!**

---

## Database Structure

### Tables Modified During Reset

#### 1. `votes` Table

**Columns**:
```sql
id, user_id, voting_code, no_vote_option,
candidate_01 through candidate_60,
created_at, updated_at
```

**Reset Action**: `TRUNCATE TABLE votes`

---

#### 2. `codes` Table

**Columns**:
```sql
id, user_id, code1, code2, code3, code4, vote_show_code,
is_code1_usable, is_code2_usable, is_code3_usable, is_code4_usable,
can_vote_now, has_voted, vote_last_seen,
code1_sent_at, code2_sent_at, has_code1_sent,
vote_submitted, client_ip, voting_time_in_minutes,
has_agreed_to_vote, has_agreed_to_vote_at, voting_started_at,
session_name, vote_completed_at, is_codemodel_valid,
created_at, updated_at
```

**Reset Action**: `TRUNCATE TABLE codes`

**Foreign Keys**:
- `user_id` references `users(id)` with CASCADE DELETE

---

#### 3. `voter_slugs` Table

**Columns**:
```sql
id, user_id, slug, expires_at, is_active,
current_step, vote_submitted, vote_completed,
created_at, updated_at
```

**Reset Action**: `TRUNCATE TABLE voter_slugs`

**Foreign Keys**:
- `user_id` references `users(id)` with CASCADE DELETE

---

#### 4. `users` Table (Modified Columns Only)

**Reset Columns**:
```sql
has_voted = 0,
vote_last_seen = NULL,
voting_started_at = NULL,
vote_submitted_at = NULL,
vote_completed_at = NULL
```

**Preserved Columns**:
- Personal information (name, email, etc.)
- Authentication data
- Roles and permissions
- can_vote flag (eligibility)

---

#### 5. `candidacies` Table

**Columns**:
```sql
id, candidacy_id, user_id, post_id,
proposer_id, supporter_id,
image_path_1, image_path_2, image_path_3,
created_at, updated_at
```

**Reset Action**: `TRUNCATE TABLE candidacies`

**Foreign Keys**:
- `user_id` references `users(id)` (candidate's user account)
- `post_id` references `posts(id)` (electoral post)

---

## Manual Reset (Advanced)

If you need to reset manually via SQL, use these commands:

### ⚠️ BACKUP FIRST!

```bash
mysqldump -u root nrna_eu > backup_before_reset.sql
```

### Complete Reset SQL

```sql
-- Start transaction for safety
START TRANSACTION;

-- 1. Delete all votes
TRUNCATE TABLE votes;

-- 2. Delete all verification codes
TRUNCATE TABLE codes;

-- 3. Delete all voter slugs
TRUNCATE TABLE voter_slugs;

-- 4. Delete all candidates
TRUNCATE TABLE candidacies;

-- 5. Reset user voting flags
UPDATE users SET
    has_voted = 0,
    vote_last_seen = NULL,
    voting_started_at = NULL,
    vote_submitted_at = NULL,
    vote_completed_at = NULL;

-- 6. Clear sessions
DELETE FROM sessions;

-- If everything looks good, commit
COMMIT;

-- If something went wrong, rollback
-- ROLLBACK;
```

### Votes & Codes Only

```sql
START TRANSACTION;

TRUNCATE TABLE votes;
TRUNCATE TABLE codes;
TRUNCATE TABLE voter_slugs;

UPDATE users SET
    has_voted = 0,
    vote_last_seen = NULL,
    voting_started_at = NULL,
    vote_submitted_at = NULL,
    vote_completed_at = NULL;

DELETE FROM sessions;

COMMIT;
```

### Candidates Only

```sql
START TRANSACTION;

TRUNCATE TABLE candidacies;

COMMIT;
```

---

## Verification After Reset

After resetting, verify the system is clean:

### 1. Check Data Counts

```bash
php artisan tinker
```

```php
// Check votes
\App\Models\Vote::count(); // Should be 0

// Check codes
\App\Models\Code::count(); // Should be 0

// Check voter slugs
\App\Models\VoterSlug::count(); // Should be 0

// Check candidates (if reset)
\App\Models\Candidacy::count(); // Should be 0

// Check users with has_voted flag
\App\Models\User::where('has_voted', 1)->count(); // Should be 0

exit
```

### 2. Check Users Can Vote

```bash
php artisan tinker
```

```php
// Get a sample user
$user = \App\Models\User::where('can_vote', 1)->first();

// Verify voting flags are reset
echo "Has Voted: " . $user->has_voted . "\n"; // Should be 0
echo "Vote Last Seen: " . $user->vote_last_seen . "\n"; // Should be NULL

exit
```

### 3. Test Voting Flow

1. Access voting page: `/voter/start`
2. Should start from step 1 (code entry)
3. No previous voting data should exist

---

## Troubleshooting

### Issue 1: Foreign Key Constraint Error

**Error Message**:
```
SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row
```

**Solution**:
```sql
-- Temporarily disable foreign key checks
SET FOREIGN_KEY_CHECKS=0;

-- Run reset commands
TRUNCATE TABLE votes;
TRUNCATE TABLE codes;
TRUNCATE TABLE voter_slugs;
TRUNCATE TABLE candidacies;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;
```

---

### Issue 2: Command Not Found

**Error Message**:
```
Command "election:reset" is not defined.
```

**Solution**:
```bash
# Clear config cache
php artisan config:clear

# Clear application cache
php artisan cache:clear

# Rebuild autoload files
composer dump-autoload

# Try again
php artisan election:reset --all
```

---

### Issue 3: Transaction Rollback

**Error Message**:
```
❌ Reset Failed
Error: [specific error message]
No changes were made to the database (transaction rolled back).
```

**Solution**:
1. Check the error message for specific issue
2. Common causes:
   - Database connection lost
   - Table doesn't exist
   - Permission issues
3. Fix the underlying issue
4. Try again

**Manual verification**:
```sql
-- Check if tables still have data
SELECT COUNT(*) FROM votes;
SELECT COUNT(*) FROM codes;
```

---

### Issue 4: Partial Reset

**Symptom**: Some tables cleared but others still have data

**Solution**:
```bash
# Check what's left
php artisan tinker
```

```php
echo "Votes: " . \App\Models\Vote::count() . "\n";
echo "Codes: " . \App\Models\Code::count() . "\n";
echo "Slugs: " . \App\Models\VoterSlug::count() . "\n";
echo "Candidates: " . \App\Models\Candidacy::count() . "\n";
exit
```

```bash
# Re-run the reset
php artisan election:reset --all
```

---

### Issue 5: Sessions Not Cleared

**Symptom**: Users still logged in with old session data

**Solution**:
```bash
# Clear all sessions manually
php artisan session:clear

# Or via SQL
php artisan tinker
```

```php
DB::table('sessions')->delete();
exit
```

```bash
# Restart web server
# For XAMPP: Stop and start Apache
```

---

## Best Practices

### ✅ Do's

1. **Always Backup First**
   - Create database backup before reset
   - Keep backup for at least 30 days
   - Verify backup is restorable

2. **Schedule During Low Activity**
   - Reset during off-peak hours
   - Notify users of maintenance window
   - Ensure no active voting sessions

3. **Test After Reset**
   - Verify all counts are zero
   - Test complete voting flow
   - Check user can register as candidate

4. **Document the Reset**
   - Record date and time of reset
   - Note who performed the reset
   - Keep logs for audit trail

5. **Update Configuration**
   - Check election settings in `.env`
   - Verify `SELECT_ALL_REQUIRED` setting
   - Confirm election dates are correct

### ❌ Don'ts

1. **Don't Reset During Active Election**
   - Never reset while users are voting
   - Check for active sessions first
   - Wait for all voters to complete

2. **Don't Use `--force` in Production**
   - Avoid skipping confirmations
   - Always review data before deletion
   - Double-check you're on correct environment

3. **Don't Reset Selectively Without Understanding**
   - Resetting only votes but keeping codes creates inconsistency
   - Resetting only candidates invalidates existing votes
   - Use `--all` for clean state

4. **Don't Forget to Clear Application Cache**
   - Cached data may show old counts
   - Clear cache after reset: `php artisan cache:clear`

5. **Don't Delete Users**
   - User accounts should be preserved
   - Only reset voting-related flags
   - Voters should be able to vote again

---

## Post-Reset Checklist

After resetting the election system, complete this checklist:

- [ ] Database backup created and verified
- [ ] Reset command executed successfully
- [ ] All tables show zero records (votes, codes, slugs, candidates)
- [ ] User voting flags reset (has_voted = 0)
- [ ] Sessions cleared
- [ ] Application cache cleared: `php artisan cache:clear`
- [ ] Config cache cleared: `php artisan config:clear`
- [ ] Route cache cleared: `php artisan route:clear`
- [ ] View cache cleared: `php artisan view:clear`
- [ ] Test voting flow from start to finish
- [ ] Verify email notifications working
- [ ] Check candidate registration process
- [ ] Confirm election settings in `.env`
- [ ] Update election start/end dates if needed
- [ ] Notify election committee of successful reset
- [ ] Document reset in system logs
- [ ] Take new database backup of clean state

---

## Common Scenarios

### Scenario 1: After Demo Election

**Situation**: Completed full demo with 50 test votes, 10 candidates

**Steps**:
```bash
# 1. Backup current state
mysqldump -u root nrna_eu > demo_backup_20251128.sql

# 2. Complete reset
php artisan election:reset --all

# 3. Verify clean state
php artisan tinker
\App\Models\Vote::count(); // 0
\App\Models\Candidacy::count(); // 0
exit

# 4. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Test actual registration
# Navigate to /candidacy/create
# Register new candidate for actual election
```

---

### Scenario 2: Testing Voting Process Only

**Situation**: Want to test voting multiple times with same candidates

**Steps**:
```bash
# Reset only votes and codes
php artisan election:reset --votes

# Candidates remain intact
# Users can vote again
```

---

### Scenario 3: Wrong Candidates Registered

**Situation**: Demo candidates need to be replaced

**Steps**:
```bash
# Reset only candidates
php artisan election:reset --candidates

# Register correct candidates
# Previous test votes are invalidated
# Recommend full reset instead
```

---

### Scenario 4: Mid-Election Reset (Emergency)

**Situation**: Critical issue found during actual election

**Steps**:
```bash
# 1. STOP: Announce election pause
# 2. Backup immediately
mysqldump -u root nrna_eu > emergency_backup_$(date +%Y%m%d_%H%M%S).sql

# 3. Document the issue
# 4. Consult election committee
# 5. If approved, reset:
php artisan election:reset --all

# 6. Fix the issue
# 7. Re-open election
# 8. Notify all voters
```

⚠️ **Warning**: Mid-election reset is extremely serious and should only be done with committee approval!

---

## Automation Scripts

### PowerShell Script (Windows)

Create `reset-election.ps1`:

```powershell
# NRNA Election Reset Script
# Run as: .\reset-election.ps1

$projectPath = "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu"
$backupPath = "C:\backup"

Write-Host "=== NRNA Election Reset Script ===" -ForegroundColor Cyan
Write-Host ""

# Create backup
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupFile = "$backupPath\nrna_backup_$timestamp.sql"

Write-Host "Creating backup..." -ForegroundColor Yellow
cd C:\xampp\mysql\bin
.\mysqldump.exe -u root nrna_eu > $backupFile

Write-Host "Backup created: $backupFile" -ForegroundColor Green
Write-Host ""

# Navigate to project
cd $projectPath

# Run reset
Write-Host "Running reset command..." -ForegroundColor Yellow
php artisan election:reset --all

# Clear caches
Write-Host ""
Write-Host "Clearing caches..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

Write-Host ""
Write-Host "Reset completed!" -ForegroundColor Green
```

Usage:
```powershell
.\reset-election.ps1
```

---

### Bash Script (Linux/Mac)

Create `reset-election.sh`:

```bash
#!/bin/bash

# NRNA Election Reset Script
# Run as: ./reset-election.sh

PROJECT_PATH="/var/www/nrna-eu"
BACKUP_PATH="$HOME/backup"

echo "=== NRNA Election Reset Script ==="
echo ""

# Create backup directory if not exists
mkdir -p "$BACKUP_PATH"

# Create backup
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_PATH/nrna_backup_$TIMESTAMP.sql"

echo "Creating backup..."
mysqldump -u root -p nrna_eu > "$BACKUP_FILE"

echo "Backup created: $BACKUP_FILE"
echo ""

# Navigate to project
cd "$PROJECT_PATH" || exit

# Run reset
echo "Running reset command..."
php artisan election:reset --all

# Clear caches
echo ""
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "Reset completed!"
```

Make executable:
```bash
chmod +x reset-election.sh
./reset-election.sh
```

---

## Support & Contact

If you encounter issues during the reset process:

1. **Check this documentation** for troubleshooting steps
2. **Review Laravel logs**: `storage/logs/laravel.log`
3. **Check database logs** in XAMPP control panel
4. **Contact technical support** with:
   - Error message (exact text)
   - Steps you performed
   - Database backup status
   - Laravel version: `php artisan --version`

---

## Changelog

### Version 1.0.0 (2025-11-28)
- ✅ Initial release of reset command
- ✅ Multi-level confirmation system
- ✅ Transaction-based safety
- ✅ Progress bar implementation
- ✅ Comprehensive documentation
- ✅ Three reset modes (votes/candidates/all)
- ✅ Force option for automation

---

## Summary

The `election:reset` command provides a safe, reliable way to reset your NRNA election system:

✅ **Safe**: Multiple confirmations prevent accidental deletion
✅ **Transactional**: All-or-nothing approach ensures consistency
✅ **Flexible**: Choose what to reset (votes/candidates/all)
✅ **Tracked**: Progress bars and detailed feedback
✅ **Recoverable**: Always creates transaction, can rollback

**Remember**: Always backup before resetting!

---

**Document Version**: 1.0.0
**Last Updated**: 2025-11-28
**Status**: Production Ready
