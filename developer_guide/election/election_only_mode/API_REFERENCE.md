# API Reference: Election-Only Mode

## Endpoints

### Single Voter Assignment

#### POST `/api/organisations/{organisation}/elections/{election}/voters`

Assign a single user as a voter.

**Headers:**
```
Content-Type: application/json
Accept: application/json
X-CSRF-Token: {token}  (from meta tag)
```

**Request Body:**
```json
{
  "user_id": "uuid-of-user"
}
```

**Success Response (200/302):**
```json
{
  "success": true,
  "membership": {
    "id": "uuid",
    "user_id": "uuid",
    "election_id": "uuid",
    "organisation_id": "uuid",
    "role": "voter",
    "status": "active",
    "assigned_at": "2026-05-18T10:30:00Z"
  }
}
```

**Inertia Response (Redirect):**
```
Redirect to: /organisations/{org}/elections/{election}/voters
Flash message: "Voter assigned successfully"
```

**Error Responses:**

**422 Unprocessable Entity** - User not eligible:
```json
{
  "message": "User is not eligible to vote in this election",
  "errors": {
    "user_id": [
      "User must be a member of this organisation"
    ]
  }
}
```

**422 Unprocessable Entity** - Duplicate voter:
```json
{
  "message": "User is already an active voter in this election",
  "errors": {
    "user_id": [
      "User is already assigned as voter"
    ]
  }
}
```

**404 Not Found** - Election doesn't exist:
```json
{
  "message": "The requested election was not found"
}
```

---

### Bulk Voter Assignment

#### POST `/api/organisations/{organisation}/elections/{election}/voters/bulk`

Assign multiple users as voters in a single batch.

**Headers:**
```
Content-Type: application/json
Accept: application/json
X-CSRF-Token: {token}
Idempotency-Key: {optional-key}  (for deduplication)
```

**Request Body:**
```json
{
  "user_ids": [
    "uuid-1",
    "uuid-2",
    "uuid-3"
  ]
}
```

**Success Response (200):**
```json
{
  "success": true,
  "results": {
    "success": 2950,
    "already_existing": 20,
    "invalid": 30,
    "failed": 0
  }
}
```

**With Failures (200):**
```json
{
  "success": false,
  "results": {
    "success": 500,
    "already_existing": 20,
    "invalid": 30,
    "failed": 450
  },
  "message": "Some voters failed to assign. Check dead-letter queue for details.",
  "dlq_url": "/organisations/{org}/dead-letter-queue?queue=voter_bulk_assign"
}
```

**Error Responses:**

**422 Unprocessable Entity** - Invalid input:
```json
{
  "message": "The user_ids field is required and must be an array",
  "errors": {
    "user_ids": [
      "The user_ids field is required"
    ]
  }
}
```

**429 Too Many Requests** - Rate limited:
```json
{
  "message": "Too many bulk import requests. Please try again in 60 seconds.",
  "retry_after": 60
}
```

---

### List Assigned Voters

#### GET `/api/organisations/{organisation}/elections/{election}/voters`

Get paginated list of assigned voters.

**Query Parameters:**
```
?page=1
&per_page=50
&status=active|inactive|all
&search=email  (optional)
```

**Success Response (200):**
```json
{
  "data": [
    {
      "id": "uuid",
      "user_id": "uuid",
      "user": {
        "id": "uuid",
        "name": "John Doe",
        "email": "john@example.com"
      },
      "role": "voter",
      "status": "active",
      "has_voted": false,
      "assigned_at": "2026-05-18T10:30:00Z",
      "expires_at": null
    }
  ],
  "meta": {
    "total": 1250,
    "per_page": 50,
    "current_page": 1,
    "last_page": 25
  }
}
```

---

### Check Voter Eligibility

#### GET `/api/organisations/{organisation}/voters/{user_id}/eligible-elections`

Check which elections a user is eligible for.

**Success Response (200):**
```json
{
  "user_id": "uuid",
  "eligible_elections": [
    {
      "id": "uuid",
      "name": "Board Election 2026",
      "status": "active",
      "can_vote": true,
      "assigned_as_voter": true,
      "reason": "Active member with paid fees"
    },
    {
      "id": "uuid",
      "name": "Regional Representative",
      "status": "voting",
      "can_vote": false,
      "assigned_as_voter": false,
      "reason": "Not yet assigned—contact administrator"
    }
  ]
}
```

---

### Dead-Letter Queue: View Failed Rows

#### GET `/api/organisations/{organisation}/dead-letter-queue`

View rows that failed during bulk import.

**Query Parameters:**
```
?queue=voter_bulk_assign
&election_id=uuid  (optional)
&status=pending|retried|all
&page=1
&per_page=50
```

**Success Response (200):**
```json
{
  "data": [
    {
      "id": "uuid",
      "queue_name": "voter_bulk_assign",
      "payload": {
        "user_id": "uuid-of-failed-user",
        "election_id": "uuid",
        "organisation_id": "uuid"
      },
      "error_message": "Foreign key constraint failed: user not found in organisation_users",
      "error_class": "Illuminate\\Database\\QueryException",
      "failed_at": "2026-05-18T10:30:00Z",
      "retried_at": null
    }
  ],
  "meta": {
    "total": 450,
    "per_page": 50,
    "current_page": 1
  }
}
```

---

### Dead-Letter Queue: Retry Failed Rows

#### POST `/api/organisations/{organisation}/dead-letter-queue/retry`

Manually retry failed voter assignments.

**Headers:**
```
Content-Type: application/json
Accept: application/json
X-CSRF-Token: {token}
```

**Request Body:**
```json
{
  "queue_name": "voter_bulk_assign",
  "dlq_entry_ids": [
    "uuid-1",
    "uuid-2"
  ]
}
```

**Success Response (200):**
```json
{
  "success": true,
  "retried": 2,
  "results": {
    "success": 2,
    "failed": 0
  },
  "message": "2 rows successfully retried"
}
```

**Partial Success Response (200):**
```json
{
  "success": true,
  "retried": 2,
  "results": {
    "success": 1,
    "failed": 1
  },
  "message": "1 of 2 rows retried successfully",
  "still_failed": [
    {
      "dlq_entry_id": "uuid",
      "user_id": "uuid",
      "error_message": "User no longer in organisation"
    }
  ]
}
```

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Resource created |
| 204 | No content (success, no body) |
| 302 | Redirect (form submission success) |
| 400 | Bad request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not found |
| 422 | Unprocessable entity (validation error) |
| 429 | Too many requests (rate limited) |
| 500 | Server error |

---

## Error Response Format

All errors follow this format:

```json
{
  "message": "Human-readable error message",
  "errors": {
    "field_name": [
      "Field-specific error message"
    ]
  }
}
```

---

## Idempotency

The bulk assignment endpoint supports idempotency via the `Idempotency-Key` header.

**Example:**
```
POST /api/organisations/{org}/elections/{election}/voters/bulk
Idempotency-Key: import-batch-2026-05-18-001

Request body: { "user_ids": [...] }
```

**First request:** Returns 200 with results, stores key with result in cache.

**Duplicate request (same key within 10 minutes):** Returns 200 with cached result (no duplicate processing).

**After 10 minutes:** Key expires, treated as new request.

---

## Rate Limiting

Bulk import endpoint is rate-limited per organization:

```
10 requests per minute per organisation
```

**Rate limit headers:**
```
X-RateLimit-Limit: 10
X-RateLimit-Remaining: 7
X-RateLimit-Reset: 1716025860
```

**When exceeded (429):**
```json
{
  "message": "Too many bulk import requests",
  "retry_after": 45
}
```

---

## Authentication

All endpoints require:

1. **CSRF Token** (unless in API token mode)
   ```html
   <meta name="csrf-token" content="{token}">
   ```
   Pass as header: `X-CSRF-Token: {token}`

2. **Authentication** (user must be logged in)
   ```
   Cookie: XSRF-TOKEN=...;
   ```

3. **Authorization** (user must have admin role for election)
   ```
   App\Models\User with role 'admin' or 'election_admin'
   ```

---

## Examples

### cURL: Single Voter Assignment

```bash
curl -X POST https://api.example.com/api/organisations/org-slug/elections/election-slug/voters \
  -H "Content-Type: application/json" \
  -H "X-CSRF-Token: token-from-meta" \
  -H "Accept: application/json" \
  -d '{
    "user_id": "user-uuid"
  }' \
  -b "XSRF-TOKEN=token; session=session-id"
```

### cURL: Bulk Voter Assignment

```bash
curl -X POST https://api.example.com/api/organisations/org-slug/elections/election-slug/voters/bulk \
  -H "Content-Type: application/json" \
  -H "X-CSRF-Token: token-from-meta" \
  -H "Idempotency-Key: batch-001" \
  -d '{
    "user_ids": [
      "user-1-uuid",
      "user-2-uuid",
      "user-3-uuid"
    ]
  }' \
  -b "XSRF-TOKEN=token; session=session-id"
```

### JavaScript: Inertia.js (Vue)

```javascript
import { router } from '@inertiajs/vue3';

// Single voter
router.post('/api/organisations/org-slug/elections/election-slug/voters', {
  user_id: 'user-uuid',
}, {
  onSuccess: () => {
    console.log('Voter assigned');
  },
});

// Bulk voters
router.post('/api/organisations/org-slug/elections/election-slug/voters/bulk', {
  user_ids: ['uuid-1', 'uuid-2', 'uuid-3'],
}, {
  headers: {
    'Idempotency-Key': 'batch-001',
  },
  onSuccess: (page) => {
    console.log('Bulk import result:', page.props.result);
  },
});
```

### Python: Requests Library

```python
import requests
from uuid import uuid4

url = "https://api.example.com/api/organisations/org-slug/elections/election-slug/voters/bulk"

headers = {
    "Content-Type": "application/json",
    "X-CSRF-Token": csrf_token,
    "Idempotency-Key": str(uuid4()),
}

payload = {
    "user_ids": [
        "user-uuid-1",
        "user-uuid-2",
        "user-uuid-3",
    ]
}

response = requests.post(url, json=payload, headers=headers, cookies=session_cookies)

if response.status_code == 200:
    result = response.json()['results']
    print(f"Success: {result['success']}, Failed: {result['failed']}")
else:
    print(f"Error: {response.json()['message']}")
```

---

## Webhooks (Future Phase D)

The following events will trigger webhooks:

- `voter.assigned` - Single voter assigned
- `voters.bulk_assigned` - Bulk import completed
- `voters.bulk_failed` - Bulk import had failures
- `voter_dlq.retry_attempt` - Dead-letter queue retry attempted

Subscribe to webhooks via: `POST /api/organisations/{org}/webhooks`

---

**Last Updated**: 2026-05-18
