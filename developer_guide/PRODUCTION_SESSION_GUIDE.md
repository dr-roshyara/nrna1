# How to Check Session Data in Production

## ⚠️ IMPORTANT: Sessions are Temporary!

**Session data is ONLY available for:**
- Active voting sessions (user still logged in)
- Within SESSION_LIFETIME (120 minutes = 2 hours)
- Once expired, session data is **PERMANENTLY DELETED**

For Vote ID 16, session data is **likely already gone** if the vote was cast more than 2 hours ago.

---

## Method 1: SSH to Production Server

### Step 1: Connect to Production
```bash
ssh user@publicdigit.com
cd /path/to/your/laravel/app
```

### Step 2: Run the Session Check Script

Upload `check-session-vote.php` to production, then:

```bash
php check-session-vote.php
```

---

## Method 2: Direct Database Query (Production)

### Step 1: Connect to Production Database

```bash
ssh user@publicdigit.com
mysql -u your_user -p your_production_database
```

### Step 2: Check Sessions Table

```sql
-- Check if sessions table exists and has data
SELECT COUNT(*) as total_sessions FROM sessions;

-- Find sessions for a specific user (if you know user_id)
SELECT
    id,
    user_id,
    FROM_UNIXTIME(last_activity) as last_active,
    ip_address
FROM sessions
WHERE user_id = YOUR_USER_ID
ORDER BY last_activity DESC;

-- Check codes table for session names
SELECT
    c.id,
    c.user_id,
    c.session_name,
    c.has_voted,
    c.vote_completed_at,
    u.name as user_name
FROM codes c
LEFT JOIN users u ON u.id = c.user_id
WHERE c.session_name IS NOT NULL
AND c.has_voted = 1
ORDER BY c.vote_completed_at DESC
LIMIT 20;
```

### Step 3: Decode Session Payload (if exists)

```sql
-- Get the session payload
SELECT payload FROM sessions WHERE user_id = YOUR_USER_ID LIMIT 1;
```

Then in PHP/tinker:
```php
$payload = 'YOUR_BASE64_PAYLOAD_HERE';
$data = unserialize(base64_decode($payload));
print_r($data);
```

---

## Method 3: Use Artisan Tinker on Production

```bash
ssh user@publicdigit.com
cd /path/to/laravel
php artisan tinker
```

Then:
```php
// Find vote
$vote = \App\Models\Vote::find(16);

// Try to find user (if user_id is not hashed)
$user = \App\Models\User::where('id', $vote->user_id)->first();

// Check active sessions
$sessions = DB::table('sessions')
    ->where('user_id', $user->id)
    ->get();

foreach ($sessions as $session) {
    $payload = base64_decode($session->payload);
    $data = unserialize($payload);
    print_r($data);
}
```

---

## Method 4: Check Logs (Best for Historical Data)

If sessions are expired, check Laravel logs:

```bash
ssh user@publicdigit.com
cd /path/to/laravel/storage/logs

# Search for vote submission logs
grep "vote_data_19" laravel.log
grep "first_submission" laravel.log | grep "user_id.*16"
grep "FIRST_SUBMISSION START" laravel.log
```

Look for log entries like:
```
[2025-11-29 18:02:55] local.INFO: Stored vote data in session
{"session_name":"vote_data_19","user_id":19,"data_keys":["user_id","national_selected_candidates","regional_selected_candidates","no_vote_option","agree_button"]}
```

---

## Understanding Session Data Structure

### Session Storage in Database

Table: `sessions`

| Column | Description |
|--------|-------------|
| `id` | Session ID (random string) |
| `user_id` | User ID (nullable) |
| `ip_address` | User's IP |
| `user_agent` | Browser info |
| `payload` | **Base64-encoded serialized PHP array** |
| `last_activity` | Unix timestamp |

### Vote Session Data Structure

When user submits vote, session contains:

```php
// Session key: vote_data_{user_id}
[
    'user_id' => 19,
    'national_selected_candidates' => [
        0 => [
            'post_id' => '2021_01',
            'post_name' => 'President',
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => 123, 'name' => 'John Doe']
            ]
        ],
        1 => [
            'post_id' => '2021_02',
            'post_name' => 'National Deligate',
            'no_vote' => false,  // ← THE BUG
            'candidates' => []   // ← EMPTY!
        ]
    ],
    'regional_selected_candidates' => [...],
    'no_vote_option' => false,
    'agree_button' => true
]
```

This session data is saved at:
- **VoteController.php:395** - `$request->session()->put($session_name, $vote_data);`

---

## What to Look For

### 1. Check Session Payload for Vote Data

```php
// Session key format
$session_name = 'vote_data_' . $user_id;
```

### 2. Look for Inconsistencies

In the session data, check each position:
- If `no_vote: false` AND `candidates: []` → **BUG DETECTED**
- If `no_vote: true` AND `candidates: []` → Valid skip
- If `no_vote: false` AND `candidates: [...]` → Valid vote

---

## Why Sessions Won't Help for Vote 16

### 1. **Sessions Expire**
- SESSION_LIFETIME = 120 minutes
- After 2 hours, sessions are deleted
- Vote 16 is likely from days/weeks ago

### 2. **Sessions are Cleared on Vote Completion**
Some session data might be cleared after vote is stored:
```php
// VoteController.php - after vote is saved
session()->forget('vote_data_' . $user->id);
```

### 3. **User Logged Out**
When user logs out, their session is destroyed.

---

## Better Approach: Analyze the Vote Pattern

Since sessions are expired, use the **vote pattern analysis**:

### Run This Query on Production:

```sql
-- Find all votes with the bug pattern
SELECT
    id as vote_id,
    created_at,
    user_id
FROM votes
WHERE
    JSON_EXTRACT(candidate_01, '$.no_vote') = false
    AND JSON_LENGTH(JSON_EXTRACT(candidate_01, '$.candidates')) = 0
ORDER BY created_at DESC;
```

### Or Check Individual Vote:

```sql
SELECT
    id,
    candidate_01,
    candidate_02,
    candidate_03
FROM votes
WHERE id = 16;
```

Then manually inspect the JSON to find positions with:
```json
{"no_vote": false, "candidates": []}
```

---

## Recommendation

### For Production Investigation:

1. ✅ **Analyze vote data pattern** (permanent, reliable)
2. ⚠️ **Check Laravel logs** (if log retention allows)
3. ❌ **Check session data** (likely expired)

### For Future Debugging:

1. Enable detailed logging before vote submission
2. Consider storing session snapshot with votes
3. Add session_data column to votes table (optional)

---

## Summary

**Can you access session data for Vote 16?**

**NO** - Because:
- ❌ Sessions expire after 120 minutes
- ❌ Vote 16 is from the past
- ❌ Session data is auto-deleted
- ✅ Use vote table data instead

**What you CAN do:**
- ✅ Analyze the vote JSON pattern
- ✅ Check production Laravel logs
- ✅ Use the bug pattern to infer voter intent
- ✅ Fix future votes with proper validation
