# DeviceFingerprint Service - Quick Start Guide

**TL;DR** - Device-based fraud detection with privacy-preserving hashing

---

## What It Does

Detects and prevents vote fraud by limiting votes per device, without exposing voter identity.

```
Request (IP + User-Agent) → SHA256 Hash → Device ID
                                            ↓
                                    Vote limit check
                                            ↓
                                    Allow or block vote
```

## Installation

### 1. Add Service Provider (Already Done)

**File:** `app/Providers/AppServiceProvider.php`

```php
public function register(): void
{
    $this->app->singleton(DeviceFingerprint::class, function () {
        return new DeviceFingerprint();
    });
}
```

### 2. Add Migration (Already Done)

**File:** `database/migrations/2026_03_06_155200_add_device_fingerprinting_to_codes.php`

```bash
php artisan migrate
```

### 3. Add Config (Already Done)

**File:** `config/voting.php`

```php
return [
    'max_votes_per_device' => env('VOTING_MAX_VOTES_PER_DEVICE', 3),
    'device_anomaly_threshold' => env('VOTING_DEVICE_ANOMALY_THRESHOLD', 5),
    'device_time_window_minutes' => env('VOTING_DEVICE_TIME_WINDOW_MINUTES', 15),
];
```

## 5-Minute Usage

### Generate Device Hash

```php
$deviceHash = app(DeviceFingerprint::class)->generate($request);
// Result: "a7f3e9c2d1b4f5a8e9c2d1b4f5a8e9c2d1b4f5a"
```

### Check Vote Limit

```php
$canVote = app(DeviceFingerprint::class)->canVote($deviceHash, $electionId);

if (!$canVote['allowed']) {
    return back()->withErrors([
        'device_limit' => $canVote['limit_message']
    ]);
}

// Check remaining votes
echo $canVote['remaining'];  // 2 votes left
```

### Get Device Statistics

```php
$stats = app(DeviceFingerprint::class)->getDeviceStats($deviceHash, $electionId);

echo $stats['total_codes'];      // 3 codes from this device
echo $stats['used_codes'];       // 1 already used
echo $stats['unused_codes'];     // 2 still available
echo $stats['first_used'];       // When first code created
echo $stats['last_used'];        // When last code created
```

### Detect Anomalies

```php
$anomaly = app(DeviceFingerprint::class)->detectAnomaly($deviceHash, $electionId);

if ($anomaly['detected']) {
    Log::warning('Suspicious voting pattern', [
        'device' => $deviceHash,
        'codes_in_15min' => $anomaly['count'],
    ]);
}
```

## Configuration

### Environment Variables

```bash
# .env
VOTING_MAX_VOTES_PER_DEVICE=3              # Votes per device
VOTING_DEVICE_ANOMALY_THRESHOLD=5          # Codes in time window = anomaly
VOTING_DEVICE_TIME_WINDOW_MINUTES=15       # Time window for anomaly check
```

### Per-Organisation Override

```php
$organisation->update([
    'voting_settings' => [
        'max_votes_per_device' => 5,  // This org allows 5 votes per device
    ]
]);
```

## Integration Points

### Step 1: Generate Hash (On Code Request)

```php
// VoterController::getCode()
$deviceHash = app(DeviceFingerprint::class)->generate($request);
$code->update(['device_fingerprint_hash' => $deviceHash]);
```

### Step 2: Check Limit (Before Vote Submission)

```php
// VoterController::submitVote()
$deviceHash = app(DeviceFingerprint::class)->generate($request);

$canVote = app(DeviceFingerprint::class)->canVote($deviceHash, $electionId);
if (!$canVote['allowed']) {
    return back()->withErrors(['device_limit' => $canVote['limit_message']]);
}
```

### Step 3: Log Anomalies (After Vote)

```php
// VoterController::complete()
$anomaly = app(DeviceFingerprint::class)->detectAnomaly($deviceHash, $electionId);
if ($anomaly['detected']) {
    Log::security('Anomaly', $anomaly);
}
```

## Response Format

### canVote() Response

```php
[
    'allowed' => true|false,          // Can this device vote?
    'used' => 2,                      // Codes already used
    'max' => 3,                       // Configured maximum
    'remaining' => 1,                 // Can vote N more times
    'limit_message' => 'Your family can cast 3 votes per device',
]
```

### detectAnomaly() Response

```php
[
    'detected' => true|false,         // Anomaly found?
    'count' => 4,                     // Codes in time window
    'threshold' => 5,                 // Anomaly threshold
    'time_window_minutes' => 15,      // Time window
]
```

### getDeviceStats() Response

```php
[
    'total_codes' => 3,               // Total codes from device
    'used_codes' => 1,                // Both codes exhausted
    'unused_codes' => 2,              // Still have codes
    'first_used' => Carbon(...),      // First code timestamp
    'last_used' => Carbon(...),       // Last code timestamp
]
```

## Testing

### Run Tests

```bash
php artisan test tests/Unit/Services/DeviceFingerprintTest.php
```

### Test Cases Covered

1. ✅ Consistent hash for same device
2. ✅ Different hash for different IPs
3. ✅ Respects max votes config
4. ✅ Blocks when max reached
5. ✅ Detects anomalies
6. ✅ Returns friendly messages
7. ✅ Calculates device stats

### Write Your Own Tests

```php
public function test_device_limit_blocks_family_voting()
{
    config(['voting.max_votes_per_device' => 2]);

    $deviceHash = 'test-device-123';
    $electionId = Str::uuid();

    // Create 2 codes (max reached)
    $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
    $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

    // Try to vote again
    $result = app(DeviceFingerprint::class)->canVote($deviceHash, $electionId);

    $this->assertFalse($result['allowed']);
    $this->assertEquals(0, $result['remaining']);
}
```

## Privacy Guarantees

### What's Hashed

```php
// Input: IP + User-Agent + Salt
'192.168.1.1' + 'Mozilla/5.0...' + 'salt123'

// Output: One-way hash
'a7f3e9c2d1b4f5a8e9c2d1b4f5a8e9c2d1b4f5a'

// Cannot reverse:
hash_input = "192.168.1.1Mozilla/5.0salt123"
// Cannot determine IP from hash
```

### What's NOT Stored

- ❌ IP addresses (only hash)
- ❌ User agents (only hash)
- ❌ Device names
- ❌ Personal identifiers
- ❌ Voting preferences

### What IS Stored

- ✅ Device hash (one-way)
- ✅ Election ID
- ✅ Vote count
- ✅ Timestamps

## Common Scenarios

### Scenario 1: Family Voting (Allowed)

```
Device 1 (Family Router) → 3 family members vote
Device 2 (Family Router) → Same router, different voter

Config: max_votes_per_device = 3

✅ Device 1: 3 votes allowed
❌ Device 4: Blocked (max reached)
```

### Scenario 2: Single User Multiple Devices

```
Device A (Home WiFi) → User votes
Device B (Mobile) → Same user, different device

✅ Allowed: Each device has separate limit
✅ No collision: Different IP = different hash
```

### Scenario 3: Anomalous Pattern

```
Device X: 4 codes in 10 minutes

Config:
  - anomaly_threshold = 5
  - time_window = 15 minutes

⚠️ Detected but allowed (below threshold)
```

### Scenario 4: Max Anomaly

```
Device Y: 5 codes in 10 minutes

Config:
  - anomaly_threshold = 5
  - time_window = 15 minutes

🚨 ANOMALY DETECTED: 5 >= 5
Log warning, but don't block (soft limit)
```

## Troubleshooting

### Q: Why is my vote being blocked?

**A:** Your device has reached the vote limit. This is configured per organisation:

```php
// Check limit
$canVote = app(DeviceFingerprint::class)->canVote($hash, $electionId);
echo $canVote['limit_message'];  // "Your family can cast 3 votes per device"

// Check stats
$stats = app(DeviceFingerprint::class)->getDeviceStats($hash, $electionId);
echo "Used: {$stats['used_codes']}, Total: {$stats['total_codes']}";
```

### Q: Can I increase the vote limit?

**A:** Yes, per organisation:

```php
Organisation::find($orgId)->update([
    'voting_settings' => [
        'max_votes_per_device' => 5,  // Increase from 3 to 5
    ]
]);
```

### Q: How do I test with different devices?

**A:** In tests, use different IPs:

```php
$request1 = new Request([], [], [], [], [], [
    'REMOTE_ADDR' => '192.168.1.1',
    'HTTP_USER_AGENT' => 'Mozilla/5.0...',
]);

$request2 = new Request([], [], [], [], [], [
    'REMOTE_ADDR' => '192.168.1.2',  // Different IP
    'HTTP_USER_AGENT' => 'Mozilla/5.0...',
]);

$hash1 = app(DeviceFingerprint::class)->generate($request1);
$hash2 = app(DeviceFingerprint::class)->generate($request2);

$this->assertNotEquals($hash1, $hash2);  // Different devices
```

### Q: How do I see device statistics in production?

**A:** Query the service:

```php
$stats = app(DeviceFingerprint::class)->getDeviceStats($deviceHash, $electionId);

// Dashboard widget
return view('admin.device-stats', compact('stats'));
```

## Architecture Decision Record

### Why SHA256 Hash?

✅ **One-way:** Cannot reverse to IP
✅ **Deterministic:** Same input = same output
✅ **Fast:** Milliseconds per hash
✅ **Standard:** SHA256 industry standard

### Why Device, Not User?

✅ **Privacy:** No user tracking
✅ **Practical:** Detects family voting (same router)
✅ **Anonymous:** Votes remain unlinked
✅ **Flexible:** Per-organisation limits

### Why Anomaly Detection?

✅ **Soft Limit:** Warns without blocking
✅ **Pattern Detection:** Multiple codes in short time
✅ **Logging:** Valuable for audit trail
✅ **Graceful:** Allows legitimate use

---

## Next Steps

1. **Phase D:** Integrate into VoterController
2. **Phase E:** Add anomaly logging
3. **Phase F:** Dashboard widgets
4. **Phase G:** Admin configuration UI

---

**Reference:** See `INDEX.md` for complete documentation
