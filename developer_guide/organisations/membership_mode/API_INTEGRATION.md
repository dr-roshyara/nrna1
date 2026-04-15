# API Integration Guide — Membership Modes

## Overview

This guide covers programmatic integration with the Membership Mode system. Use these examples to integrate membership management and voter eligibility checking into your applications.

---

## Table of Contents

1. [Authentication](#authentication)
2. [Check Voter Eligibility](#check-voter-eligibility)
3. [Assign Voters](#assign-voters)
4. [Import Voters](#import-voters)
5. [Manage Members](#manage-members)
6. [Query Examples](#query-examples)
7. [Error Handling](#error-handling)

---

## Authentication

All API calls require authentication. Use Laravel's Sanctum or Session authentication.

### Session Authentication (Web)

```php
// In controller
$user = auth()->user(); // From session

// Check authorization
$this->authorize('manageVoters', $election);
```

### Token Authentication (API)

```php
// Mobile app or external client
$headers = [
    'Authorization' => 'Bearer ' . $sanctumToken,
    'Accept' => 'application/json',
];

$response = Http::withHeaders($headers)
    ->post('/api/elections/voters', [
        'user_id' => $userId,
    ]);
```

### Authorization

All endpoints check user permissions:

```php
// User must have these roles in UserOrganisationRole
$requiredRoles = ['owner', 'admin'];

// OR have these permissions
$requiredPermissions = ['manage_voters', 'manage_members'];
```

---

## Check Voter Eligibility

### Check Single User Eligibility

```php
// Service injection
$eligibilityService = app(\App\Services\VoterEligibilityService::class);

$organisation = Organisation::find($orgId);
$user = User::find($userId);

// Check eligibility (works for both modes)
$isEligible = $eligibilityService->isEligibleVoter($organisation, $user);

if ($isEligible) {
    echo "User is eligible to vote";
} else {
    echo "User is not eligible";
}
```

### Check Why User Is Ineligible

```php
// Detailed eligibility check (Full Membership Mode)
$organisation = Organisation::find($orgId);
$user = User::find($userId);

if ($organisation->uses_full_membership) {
    // Full Membership Mode: Check Member record
    $reasons = [];
    
    $member = Member::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->first();
    
    if (!$member) {
        $reasons[] = "No Member record found";
    } else {
        if ($member->status !== 'active') {
            $reasons[] = "Member status is '{$member->status}' (not active)";
        }
        if (!in_array($member->fees_status, ['paid', 'exempt'])) {
            $reasons[] = "Fees status is '{$member->fees_status}' (not paid/exempt)";
        }
        if ($member->membership_expires_at && $member->membership_expires_at < now()) {
            $reasons[] = "Membership expired on {$member->membership_expires_at}";
        }
    }
    
    if (!empty($reasons)) {
        return [
            'eligible' => false,
            'reasons' => $reasons,
        ];
    }
} else {
    // Election-Only Mode: Check OrganisationUser
    $reasons = [];
    
    $orgUser = OrganisationUser::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->first();
    
    if (!$orgUser) {
        $reasons[] = "User is not in this organisation";
    } else if ($orgUser->status !== 'active') {
        $reasons[] = "Org membership status is '{$orgUser->status}' (not active)";
    }
    
    if (!empty($reasons)) {
        return [
            'eligible' => false,
            'reasons' => $reasons,
        ];
    }
}

return ['eligible' => true];
```

### Get Unassigned Eligible Voters

```php
// Service injection
$eligibilityService = app(\App\Services\VoterEligibilityService::class);

$organisation = Organisation::find($orgId);
$election = Election::find($electionId);

// Get already assigned voter IDs
$assignedUserIds = $election->memberships()
    ->pluck('user_id')
    ->toArray();

// Get query builder for unassigned eligible voters
$query = $eligibilityService->unassignedEligibleQuery($organisation, $assignedUserIds);

// Paginate
$unassignedVoters = $query->paginate(50);

foreach ($unassignedVoters as $voter) {
    echo "{$voter->name} ({$voter->email})";
}
```

---

## Assign Voters

### Assign Single Voter

```php
// HTTP Request
POST /organisations/{slug}/elections/{id}/voters

{
    "user_id": "uuid-of-user"
}

// Response
{
    "status": "success",
    "message": "Voter assigned successfully",
    "election_membership": {
        "election_id": "...",
        "user_id": "...",
        "role": "voter",
        "status": "active"
    }
}
```

**PHP Implementation:**

```php
// In Controller
$election = Election::find($electionId);
$user = User::find($request->user_id);

// Check authorization
$this->authorize('manageVoters', $election);

// Check eligibility
$eligibility = app(\App\Services\VoterEligibilityService::class);
if (!$eligibility->isEligibleVoter($election->organisation, $user)) {
    return back()->withErrors(['user_id' => 'User is not eligible to vote']);
}

// Check not already assigned
if ($election->memberships()->where('user_id', $user->id)->exists()) {
    return back()->withErrors(['user_id' => 'User already assigned']);
}

// Assign
$election->memberships()->create([
    'user_id' => $user->id,
    'organisation_id' => $election->organisation_id,
    'role' => 'voter',
    'status' => 'active',
]);
```

### Assign Multiple Voters

```php
// HTTP Request
POST /organisations/{slug}/elections/{id}/voters/bulk

{
    "user_ids": [
        "uuid-1",
        "uuid-2",
        "uuid-3"
    ]
}

// Response
{
    "status": "success",
    "assigned": 2,
    "skipped": 1,
    "results": [
        {
            "user_id": "uuid-1",
            "assigned": true
        },
        {
            "user_id": "uuid-2",
            "assigned": true
        },
        {
            "user_id": "uuid-3",
            "assigned": false,
            "reason": "User not eligible"
        }
    ]
}
```

**PHP Implementation:**

```php
// In Controller
$election = Election::find($electionId);
$userIds = $request->user_ids;

// Check authorization
$this->authorize('manageVoters', $election);

$assigned = 0;
$skipped = 0;
$results = [];
$eligibility = app(\App\Services\VoterEligibilityService::class);

foreach ($userIds as $userId) {
    $user = User::find($userId);
    
    if (!$user) {
        $skipped++;
        $results[] = ['user_id' => $userId, 'assigned' => false, 'reason' => 'User not found'];
        continue;
    }
    
    if (!$eligibility->isEligibleVoter($election->organisation, $user)) {
        $skipped++;
        $results[] = ['user_id' => $userId, 'assigned' => false, 'reason' => 'User not eligible'];
        continue;
    }
    
    if ($election->memberships()->where('user_id', $userId)->exists()) {
        $skipped++;
        $results[] = ['user_id' => $userId, 'assigned' => false, 'reason' => 'Already assigned'];
        continue;
    }
    
    $election->memberships()->create([
        'user_id' => $userId,
        'organisation_id' => $election->organisation_id,
        'role' => 'voter',
        'status' => 'active',
    ]);
    
    $assigned++;
    $results[] = ['user_id' => $userId, 'assigned' => true];
}

return response()->json([
    'status' => 'success',
    'assigned' => $assigned,
    'skipped' => $skipped,
    'results' => $results,
]);
```

---

## Import Voters

### Preview Import

```php
// HTTP Request (Preview - no data saved)
POST /organisations/{slug}/elections/{id}/voters/import?preview=true

multipart/form-data:
- file: <CSV or Excel file>

// Response
{
    "status": "success",
    "rows_total": 10,
    "rows_valid": 9,
    "rows_invalid": 1,
    "mode": "full_membership|election_only",
    "errors": [
        {
            "row": 5,
            "email": "invalid@example.com",
            "error": "Email not registered: invalid@example.com"
        }
    ]
}
```

**PHP Implementation:**

```php
// In Controller
$file = $request->file('file');
$election = Election::find($electionId);

// Check authorization
$this->authorize('manageVoters', $election);

// Preview (doesn't save anything)
$service = app(\App\Services\VoterImportService::class);
$preview = $service->preview($file, $election);

return response()->json($preview);
```

### Confirm and Import

```php
// HTTP Request (Confirm & Import)
POST /organisations/{slug}/elections/{id}/voters/import

multipart/form-data:
- file: <CSV or Excel file>
- confirmed: true

// Response
{
    "status": "success",
    "imported": 9,
    "failed": 1,
    "message": "Successfully imported 9 voters (1 error)",
    "summary": {
        "total_processed": 10,
        "total_assigned": 9,
        "total_skipped": 0,
        "total_failed": 1
    }
}
```

**PHP Implementation:**

```php
// In Controller
$file = $request->file('file');
$election = Election::find($electionId);

// Check authorization
$this->authorize('manageVoters', $election);

// Check confirmation
if (!$request->boolean('confirmed')) {
    return back()->withErrors(['confirm' => 'Must confirm to import']);
}

// Import
$service = app(\App\Services\VoterImportService::class);
$result = $service->import($file, $election, auth()->user());

return response()->json([
    'status' => 'success',
    'imported' => $result['imported'],
    'failed' => $result['failed'],
    'summary' => $result['summary'],
]);
```

---

## Manage Members

### Full Membership Mode Only

#### Create Member

```php
// HTTP Request
POST /organisations/{slug}/members

{
    "user_id": "uuid-of-user",
    "fees_status": "paid",
    "membership_type": "Standard",
    "membership_expires_at": "2026-12-31"
}

// Response
{
    "status": "success",
    "member": {
        "id": "...",
        "user_id": "...",
        "fees_status": "paid",
        "membership_type": "Standard",
        "membership_expires_at": "2026-12-31"
    }
}
```

**PHP Implementation:**

```php
// In Controller
$organisation = Organisation::find($orgId);

// Check authorization
$this->authorize('manageMemberships', $organisation);

$user = User::find($request->user_id);

// Create or update Member
$member = Member::updateOrCreate(
    [
        'user_id' => $user->id,
        'organisation_id' => $organisation->id,
    ],
    [
        'status' => 'active',
        'fees_status' => $request->fees_status,
        'membership_type_id' => MembershipType::where('name', $request->membership_type)->first()?->id,
        'membership_expires_at' => $request->membership_expires_at,
    ]
);

return response()->json([
    'status' => 'success',
    'member' => $member,
]);
```

#### Update Member

```php
// HTTP Request
PATCH /organisations/{slug}/members/{memberId}

{
    "fees_status": "exempt",
    "membership_expires_at": "2027-12-31"
}

// Response
{
    "status": "success",
    "member": { ... updated member ... }
}
```

**PHP Implementation:**

```php
// In Controller
$member = Member::find($memberId);

// Check authorization
$this->authorize('manageMemberships', $member->organisation);

$member->update($request->only([
    'fees_status',
    'membership_type_id',
    'membership_expires_at',
]));

return response()->json([
    'status' => 'success',
    'member' => $member,
]);
```

#### Get Member

```php
// HTTP Request
GET /organisations/{slug}/members/{memberId}

// Response
{
    "id": "...",
    "user_id": "...",
    "fees_status": "paid",
    "membership_type": "Standard",
    "membership_expires_at": "2026-12-31",
    "user": {
        "id": "...",
        "name": "...",
        "email": "..."
    }
}
```

**PHP Implementation:**

```php
// In Controller
$member = Member::with('user', 'membershipType')
    ->find($memberId);

return response()->json($member);
```

---

## Query Examples

### Get All Eligible Voters in Organisation

```php
// Full Membership Mode
$org = Organisation::find($orgId);
$eligibility = app(\App\Services\VoterEligibilityService::class);

// Using service method
$query = $eligibility->unassignedEligibleQuery($org);
$voters = $query->get();

// Or manually
$voters = Member::where('organisation_id', $org->id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->whereHas('user')
    ->with('user', 'membershipType')
    ->get();
```

### Get Voters by Membership Type

```php
// Full Membership Mode only
$membershipType = MembershipType::where('name', 'Premium')->first();

$voters = Member::where('organisation_id', $org->id)
    ->where('membership_type_id', $membershipType->id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->with('user')
    ->get();
```

### Get Voters with Expired Memberships

```php
// Full Membership Mode only
$expiredVoters = Member::where('organisation_id', $org->id)
    ->where('status', 'active')
    ->whereNotNull('membership_expires_at')
    ->where('membership_expires_at', '<', now())
    ->with('user')
    ->get();
```

### Get Election Participation Rate

```php
// Both modes
$election = Election::find($electionId);

$totalEligible = $election->organisation->uses_full_membership
    ? Member::where('organisation_id', $election->organisation_id)
        ->where('status', 'active')
        ->whereIn('fees_status', ['paid', 'exempt'])
        ->count()
    : OrganisationUser::where('organisation_id', $election->organisation_id)
        ->where('status', 'active')
        ->count();

$actualVoters = $election->memberships()->count();

$participationRate = $totalEligible > 0 
    ? round(($actualVoters / $totalEligible) * 100, 2)
    : 0;
```

---

## Error Handling

### Common Error Responses

#### Unauthorized

```json
{
    "message": "Unauthorized",
    "errors": {
        "authorization": "You do not have permission to manage voters"
    }
}
```

**Handle in client:**

```php
if ($response->status() === 403) {
    echo "You don't have permission for this action";
}
```

#### Validation Error

```json
{
    "message": "The given data was invalid",
    "errors": {
        "user_id": ["User not found"],
        "fees_status": ["Invalid fees status"]
    }
}
```

**Handle in client:**

```php
if ($response->status() === 422) {
    foreach ($response->json('errors') as $field => $messages) {
        echo "{$field}: " . implode(", ", $messages);
    }
}
```

#### Not Found

```json
{
    "message": "Not found",
    "errors": {
        "election": "Election not found"
    }
}
```

**Handle in client:**

```php
if ($response->status() === 404) {
    echo "Resource not found";
}
```

### Implementing Error Handling

```php
// In service/command
try {
    $result = $service->import($file, $election, $user);
    return ['status' => 'success', 'result' => $result];
} catch (ValidationException $e) {
    return [
        'status' => 'validation_error',
        'errors' => $e->errors(),
    ];
} catch (UnauthorizedException $e) {
    return [
        'status' => 'unauthorized',
        'message' => $e->getMessage(),
    ];
} catch (Exception $e) {
    return [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];
}
```

---

## Rate Limiting

### API Rate Limits

```
- 100 requests per minute per user
- 10 file imports per hour per organisation
- 1000 voter assignments per day per organisation
```

**Check rate limit in response headers:**

```php
$remaining = $response->header('X-RateLimit-Remaining');
$reset = $response->header('X-RateLimit-Reset');

if ($remaining < 10) {
    echo "Approaching rate limit, will reset at " . date('Y-m-d H:i:s', $reset);
}
```

---

## Complete Examples

### Example 1: Import Members from External System

```php
// Assume external API returns user data
$externalUsers = Http::get('https://external-api.example.com/users')->json();

$organisation = Organisation::find($orgId);
$eligibility = app(\App\Services\VoterEligibilityService::class);

foreach ($externalUsers as $userData) {
    // Create user if doesn't exist
    $user = User::firstOrCreate(
        ['email' => $userData['email']],
        ['name' => $userData['name']]
    );
    
    // Add to organisation
    OrganisationUser::firstOrCreate(
        ['user_id' => $user->id, 'organisation_id' => $organisation->id],
        ['status' => 'active']
    );
    
    // Create Member (Full Membership Mode)
    if ($organisation->uses_full_membership) {
        Member::updateOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $organisation->id],
            [
                'status' => 'active',
                'fees_status' => $userData['fees_paid'] ? 'paid' : 'pending',
                'membership_expires_at' => $userData['membership_expires'],
            ]
        );
    }
}
```

### Example 2: Programmatic Election Setup

```php
// Create election with voters
$org = Organisation::find($orgId);
$eligibility = app(\App\Services\VoterEligibilityService::class);

$election = $org->elections()->create([
    'name' => 'Board Elections 2026',
    'type' => 'real',
    'status' => 'active',
    'start_date' => now(),
    'end_date' => now()->addDays(30),
]);

// Get all eligible users and assign
if ($org->uses_full_membership) {
    $query = $eligibility->unassignedEligibleQuery($org);
} else {
    $query = OrganisationUser::where('organisation_id', $org->id)
        ->where('status', 'active');
}

$voters = $query->pluck('user_id');

foreach ($voters as $userId) {
    $election->memberships()->firstOrCreate(
        ['user_id' => $userId],
        [
            'organisation_id' => $org->id,
            'role' => 'voter',
            'status' => 'active',
        ]
    );
}

echo "Election created with " . count($voters) . " voters";
```

---

## Related Documentation

- [Membership Modes Comparison](./MEMBERSHIP_MODES.md)
- [Membership Import Guide](./MEMBERSHIP_IMPORT.md)
- [Operations Guide](./OPERATIONS_GUIDE.md)
- [Main Architecture Guide](./README.md)

