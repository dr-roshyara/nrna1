# Membership Import Guide

## Overview

The Membership Import system allows organisations to bulk-import members from CSV/Excel files. The import process respects both **Full Membership Mode** and **Election-Only Mode** configurations, handling validation and error reporting appropriately for each mode.

---

## Table of Contents

1. [Import Architecture](#import-architecture)
2. [CSV Format](#csv-format)
3. [Import Workflow](#import-workflow)
4. [Mode-Specific Behavior](#mode-specific-behavior)
5. [Validation Rules](#validation-rules)
6. [Error Handling](#error-handling)
7. [Common Tasks](#common-tasks)
8. [API Reference](#api-reference)

---

## Import Architecture

### Core Components

```php
// Service layer - orchestrates import logic
App\Services\VoterImportService

// Policies - controls access
App\Policies\MembershipPolicy (update)

// Controller - HTTP layer
App\Http\Controllers\Election\VoterImportController
```

### Import Flow Diagram

```
┌─────────────────────────────────────────────┐
│ 1. User uploads CSV/Excel file              │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 2. Route: POST /organisations/{slug}/        │
│    elections/{id}/voters/import              │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 3. VoterImportController validates:          │
│    - User is admin/owner                     │
│    - File is CSV/Excel                       │
│    - File size < 5MB                         │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 4. VoterImportService::preview():            │
│    - Parse rows                              │
│    - Check organisation mode                 │
│    - Run per-row validation                  │
│    - Return preview (success/error counts)   │
└──────────────────┬──────────────────────────┘
                   ↓
         ┌─────────────────┐
         │ User reviews    │
         │ preview         │
         └────────┬────────┘
                  ↓
    ┌─────────────────────────┐
    │ User confirms import     │
    └────────────┬────────────┘
                 ↓
┌─────────────────────────────────────────────┐
│ 5. VoterImportService::import():             │
│    - Create ElectionMembership records       │
│    - Update voter counts                     │
│    - Log audit trail                         │
│    - Return import results                   │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 6. Redirect with success/failure message     │
└─────────────────────────────────────────────┘
```

---

## CSV Format

### Required Columns

The import file must be CSV or Excel format with the following columns:

#### Full Membership Mode
```
email,name,membership_type,fees_status,membership_expires_at
voter1@example.com,John Smith,Standard,paid,2026-12-31
voter2@example.com,Jane Doe,Premium,exempt,
```

**Column Definitions:**
- `email` (required) - User's email address (must exist in users table)
- `name` (optional) - Full name (updates user.name if provided)
- `membership_type` (optional) - Must match an existing MembershipType name
- `fees_status` (required if member) - Either 'paid' or 'exempt'
- `membership_expires_at` (optional) - Date string in YYYY-MM-DD format

#### Election-Only Mode
```
email
voter1@example.com
voter2@example.com
```

**Column Definitions:**
- `email` (required) - User's email address (must exist in users table)

---

## Import Workflow

### Two-Phase Approach

#### Phase 1: Preview
```php
// GET /organisations/{slug}/elections/{id}/voters/import
// Returns form with file upload + existing voter count

// POST /organisations/{slug}/elections/{id}/voters/import?preview=true
// Returns preview without creating records
// Shows:
// - Rows to be imported
// - Expected success count
// - Validation errors (with row numbers)
// - Whether to proceed
```

#### Phase 2: Confirm & Import
```php
// POST /organisations/{slug}/elections/{id}/voters/import
// Requires 'confirmed' => true in request
// Creates ElectionMembership records
// Returns final results
```

### Example Implementation

```php
// In controller
if ($request->boolean('preview')) {
    return $this->showPreview($file, $election);
}

if (!$request->boolean('confirmed')) {
    return back()->withErrors(['confirm' => 'Please confirm to proceed']);
}

return $this->performImport($file, $election);
```

---

## Mode-Specific Behavior

### Full Membership Mode (`uses_full_membership = true`)

**Eligibility Check:**
```php
// User must have:
// 1. Active OrganisationUser record
// 2. Active Member record
// 3. fees_status IN ['paid', 'exempt']
// 4. Membership not expired (or no expiry date)
// 5. Optional: Membership type grants voting rights
```

**Validation:**
```php
$validationErrors = [];

// Check email exists
$user = User::where('email', $email)->first();
if (!$user) {
    $validationErrors[] = "Email not registered: {$email}";
}

// Check fees_status
if (!in_array($fees_status, ['paid', 'exempt'])) {
    $validationErrors[] = "Invalid fees_status: {$fees_status}";
}

// Check membership_type if provided
if ($membership_type) {
    $type = MembershipType::where('name', $membership_type)->first();
    if (!$type) {
        $validationErrors[] = "Unknown membership type: {$membership_type}";
    }
}
```

**Import Creates:**
- `Member` record with:
  - `fees_status`
  - `membership_type_id` (if provided)
  - `membership_expires_at` (if provided)
- `ElectionMembership` record with:
  - `role: 'voter'`
  - `status: 'active'`

### Election-Only Mode (`uses_full_membership = false`)

**Eligibility Check:**
```php
// User must have:
// 1. Active OrganisationUser record
// 2. NO Member record required
```

**Validation:**
```php
$validationErrors = [];

// Check email exists
$user = User::where('email', $email)->first();
if (!$user) {
    $validationErrors[] = "Email not registered: {$email}";
}

// Check user is active in organisation
$orgUser = OrganisationUser::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->first();

if (!$orgUser) {
    $validationErrors[] = "User is not an active member of organisation";
}
```

**Import Creates:**
- `ElectionMembership` record only:
  - `role: 'voter'`
  - `status: 'active'`
- NO Member record created

---

## Validation Rules

### Email Validation

```php
// Must be valid email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format: {$email}";
}

// Must exist in users table
if (!User::where('email', $email)->exists()) {
    return "Email not registered: {$email}";
}

// Must not already be assigned to this election
if (ElectionMembership::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->exists()) {
    return "User already assigned to this election";
}
```

### Full Membership Mode Validation

```php
// Fees status must be valid
if (!in_array($fees_status, ['paid', 'exempt'])) {
    return "Invalid fees_status: must be 'paid' or 'exempt'";
}

// Membership type (if provided) must exist
if ($membership_type && !MembershipType::where('name', $membership_type)->exists()) {
    return "Unknown membership type: {$membership_type}";
}

// Expiry date must be valid format
if ($expires_at && !strtotime($expires_at)) {
    return "Invalid date format for membership_expires_at";
}
```

### Election-Only Mode Validation

```php
// User must be active in organisation
$orgUser = OrganisationUser::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->first();

if (!$orgUser) {
    return "User is not an active organisation member";
}
```

---

## Error Handling

### Validation Error Response

```php
// If ANY row fails validation, entire import is rejected
// User sees preview with errors:

[
    'status' => 'error',
    'row' => 3,
    'email' => 'invalid@test.com',
    'error' => 'Email not registered: invalid@test.com'
]
```

### Import Error Types

| Error | Cause | Solution |
|-------|-------|----------|
| `Email not registered` | User doesn't exist in users table | User must be created first |
| `Invalid email format` | Malformed email | Fix email in CSV |
| `User already assigned` | Voter already in election | Remove from CSV or skip |
| `Invalid fees_status` | Not 'paid' or 'exempt' (Full Mode only) | Use valid fees_status |
| `Unknown membership type` | MembershipType doesn't exist | Check available types |
| `Invalid date format` | Not YYYY-MM-DD format | Reformat date |
| `User not org member` | OrganisationUser missing (Election-Only Mode) | User must join org first |

### Error Response Format

```json
{
    "status": "error",
    "message": "Import validation failed",
    "rows_processed": 3,
    "rows_failed": 1,
    "errors": [
        {
            "row": 2,
            "email": "voter2@example.com",
            "error": "Email not registered: voter2@example.com"
        }
    ]
}
```

---

## Common Tasks

### Task 1: Import Members for Full Membership Elections

**Scenario:** Organisation runs a formal election where voters must be paid members.

**Steps:**

1. Prepare CSV with member details:
```csv
email,name,membership_type,fees_status,membership_expires_at
john@example.com,John Smith,Standard,paid,2026-12-31
jane@example.com,Jane Doe,Premium,paid,
```

2. Navigate to election voters page
3. Click "Import Members" button
4. Upload CSV file
5. Review preview (should show all rows valid)
6. Click "Confirm Import"
7. System creates Member + ElectionMembership records

**Code Example:**

```php
// Manual import (for testing)
$file = storage_path('files/members.csv');
$election = Election::find('...');

$service = app(VoterImportService::class);
$result = $service->import($file, $election, auth()->user(), 'full_membership');

// Result:
[
    'imported' => 50,
    'skipped' => 5,
    'failed' => 0,
    'summary' => [...]
]
```

### Task 2: Import Voters for Election-Only Elections

**Scenario:** Organisation runs a quick survey election where any org member can vote (no membership tracking).

**Steps:**

1. Prepare CSV with just emails:
```csv
email
voter1@example.com
voter2@example.com
```

2. Navigate to election voters page
3. Click "Import Voters" button
4. Upload CSV file
5. Review preview (only validates emails and org membership)
6. Click "Confirm Import"
7. System creates ElectionMembership records only (NO Member records)

**Code Example:**

```php
// Manual import
$file = storage_path('files/voters.csv');
$election = Election::where('uses_full_membership', false)->first();

$service = app(VoterImportService::class);
$result = $service->import($file, $election, auth()->user(), 'election_only');

// Result:
[
    'imported' => 100,
    'skipped' => 0,
    'failed' => 2,
    'summary' => [...]
]
```

### Task 3: Handle Import Errors

**Scenario:** Import fails validation. You need to see which rows failed and why.

**Steps:**

1. Upload CSV file
2. System shows preview with validation errors
3. Each error row shows:
   - Row number
   - Email
   - Validation error message
4. Fix issues in CSV
5. Re-upload and confirm

**Error Response Example:**

```php
// VoterImportService::preview() response
[
    'status' => 'error',
    'rows_total' => 10,
    'rows_valid' => 8,
    'rows_invalid' => 2,
    'errors' => [
        [
            'row' => 3,
            'email' => 'notregistered@example.com',
            'error' => 'Email not registered: notregistered@example.com'
        ],
        [
            'row' => 7,
            'email' => 'already@example.com',
            'error' => 'User already assigned to this election'
        ]
    ]
]
```

### Task 4: Audit Import Activity

**Scenario:** You need to track who imported what and when.

**Solution:** Import activity is automatically logged via:

```php
// 1. User audit trail (via MemberController/policies)
UserActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'voters.imported',
    'organisation_id' => $election->organisation_id,
    'election_id' => $election->id,
    'metadata' => [
        'count' => 50,
        'file_name' => 'members.csv',
        'timestamp' => now()
    ]
]);

// 2. Query audit trail
$logs = UserActivityLog::where('action', 'voters.imported')
    ->where('organisation_id', $org_id)
    ->with('user')
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($logs as $log) {
    echo "{$log->user->name} imported {$log->metadata['count']} voters";
}
```

---

## API Reference

### Upload & Preview

```http
POST /organisations/{slug}/elections/{id}/voters/import?preview=true
Content-Type: multipart/form-data

file: <CSV or Excel file>

Response:
{
    "status": "success|error",
    "rows_total": 10,
    "rows_valid": 9,
    "rows_invalid": 1,
    "mode": "full_membership|election_only",
    "errors": [
        {
            "row": 5,
            "email": "invalid@example.com",
            "error": "Email not registered"
        }
    ]
}
```

### Confirm & Import

```http
POST /organisations/{slug}/elections/{id}/voters/import
Content-Type: multipart/form-data

file: <CSV or Excel file>
confirmed: true

Response:
{
    "status": "success",
    "imported": 50,
    "skipped": 0,
    "failed": 0,
    "message": "Successfully imported 50 voters"
}
```

### Get Import Status

```http
GET /organisations/{slug}/elections/{id}/voters

Response includes:
{
    "total_voters": 150,
    "assigned_voters": 50,
    "pending_assignment": 100,
    "import_summary": {
        "last_import_at": "2026-04-15T10:30:00Z",
        "last_import_count": 50,
        "last_import_by": "admin@example.com"
    }
}
```

---

## File Format Examples

### Full Membership Mode - CSV

```csv
email,name,membership_type,fees_status,membership_expires_at
john.smith@example.com,John Smith,Standard,paid,2026-12-31
jane.doe@example.com,Jane Doe,Premium,paid,
bob.wilson@example.com,Bob Wilson,,exempt,2027-06-30
```

### Full Membership Mode - Excel

| email | name | membership_type | fees_status | membership_expires_at |
|-------|------|-----------------|-------------|----------------------|
| john@example.com | John Smith | Standard | paid | 2026-12-31 |
| jane@example.com | Jane Doe | Premium | paid | |

### Election-Only Mode - CSV

```csv
email
voter1@example.com
voter2@example.com
voter3@example.com
```

### Election-Only Mode - Excel

| email |
|-------|
| voter1@example.com |
| voter2@example.com |
| voter3@example.com |

---

## Best Practices

### 1. Data Validation Before Upload

```bash
# Check CSV for common errors
- No duplicate emails
- All emails have valid format
- All fees_status values are 'paid' or 'exempt' (Full Mode)
- All dates are YYYY-MM-DD format
- No extra whitespace in email fields
```

### 2. Staged Imports

For large organisations, import in batches:
- Import 100-500 voters at a time
- Review success rate after each batch
- Fix any pattern errors before next batch

### 3. Backup Before Major Imports

```php
// Backup election voters before import
$backup = ElectionMembership::where('election_id', $election->id)->get();
Storage::put("backups/election_{$election->id}_backup.json", json_encode($backup));
```

### 4. Test with Small File First

- Create test CSV with 5-10 rows
- Verify import works end-to-end
- Then import full member list

### 5. Email Notifications

After import, notify imported voters:
```php
// Send email to newly assigned voters
Notification::sendNow($newVoters, new VoterAssignedNotification($election));
```

---

## Troubleshooting

### Q: "Email not registered" error

**Cause:** User hasn't created account yet
**Solution:** 
1. User must sign up at /register first
2. Then re-run import

### Q: "User already assigned to this election" error

**Cause:** Voter already in this election's voter list
**Solution:**
1. Remove duplicate rows from CSV
2. Or remove user from election first, then re-import

### Q: "Invalid membership type" error (Full Mode only)

**Cause:** MembershipType doesn't exist
**Solution:**
1. Check available types: `MembershipType::pluck('name')`
2. Use exact name from database
3. Or leave membership_type blank to use default

### Q: Import hangs/times out

**Cause:** File too large (>5MB) or too many rows
**Solution:**
1. Split file into smaller batches
2. Import 500-1000 rows at a time
3. Check server timeout settings

### Q: Wrong organisation imported

**Cause:** User is in multiple organisations, imported into wrong one
**Solution:**
1. Verify in URL: /organisations/{slug}/elections/...
2. Check organisation name before uploading
3. Bulk-delete wrong imports if needed

---

## Related Documentation

- [Membership Mode Architecture](./README.md) - Overall system design
- [Full Membership vs Election-Only](./MEMBERSHIP_MODES.md) - Detailed mode comparison
- [API Integration](./API_INTEGRATION.md) - Programmatic usage
- [Membership Types Guide](./MEMBERSHIP_TYPES.md) - Managing membership types

