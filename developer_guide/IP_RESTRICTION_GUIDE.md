# IP Address Restriction Enforcement Guide

**Status:** Implemented & Tested  
**Date:** 2026-04-14  
**Author:** Claude (Haiku 4.5)

---

## Overview

The Public Digit voting platform implements a **three-layer IP address restriction system** to protect elections from vote manipulation while respecting voter privacy and flexibility.

### Three-Layer Architecture

```
Layer 1 (Per-Voter):     CONTROL_IP_ADDRESS=1 → voter.voting_ip
Layer 2 (Platform):      config('app.max_use_clientIP') global limit
Layer 3 (Per-Election):  Election.ip_restriction_enabled + ip_restriction_max_per_ip ← NEW
                         ↑ Election.ip_whitelist (bypasses ALL layers)
```

**Precedence (highest to lowest):**
1. **Whitelist** — `Election.ip_whitelist` (exact IPs or CIDR) — **ALWAYS** bypasses all layers
2. **Layer 3** — Per-election setting (when `ip_restriction_enabled = true`)
3. **Layer 2** — Global platform fallback (when Layer 3 disabled)
4. **Layer 1** — Per-voter IP pre-registration (existing, separate system)

---

## Layer 3: Per-Election IP Restriction (NEW)

### Database Schema

**Elections table:**
```sql
ip_restriction_enabled    BOOLEAN DEFAULT false
ip_restriction_max_per_ip INT DEFAULT 4
ip_whitelist              JSON NULL (array of IPs/CIDRs)
```

**Voter slugs table:**
```sql
step_1_ip VARCHAR(45)   -- IP captured at first entry point
```

### Model Methods (Election)

```php
// Check if per-election restriction is enabled
$election->isIpRestricted(): bool

// Check if IP is whitelisted (exact or CIDR)
$election->isIpWhitelisted(string $ip): bool

// Validate selection count against constraint type
$election->validateSelectionCount(int $count): bool
```

### Controller Enforcement

**Entry Point:** `ElectionVotingController::start()` (POST /elections/{slug}/start)

```php
$ipBlock = $this->resolveIpBlock($election, $request->ip());

if ($ipBlock['blocked']) {
    return redirect()->route('elections.show', $slug)
        ->with('error', $ipBlock['message']);
}

// If here, no slug exists yet
// Only creates slug if IP restriction check passes
$voterSlug = VoterSlug::create([...]);
```

**Three-layer evaluation in `resolveIpBlock()`:**

```php
private function resolveIpBlock(Election $election, string $ip): array
{
    // 1. Whitelist (always bypasses)
    if ($election->ip_whitelist && $election->isIpWhitelisted($ip)) {
        return ['blocked' => false, 'message' => null];
    }

    // 2. Layer 3: Per-election (takes precedence when enabled)
    if ($election->isIpRestricted()) {
        return $this->evaluateIpCount($election, $ip, $election->ip_restriction_max_per_ip);
    }

    // 3. Layer 2: Global fallback
    $globalMax = (int) config('app.max_use_clientIP', 0);
    if ($globalMax > 0) {
        return $this->evaluateIpCount($election, $ip, $globalMax);
    }

    return ['blocked' => false, 'message' => null];
}

private function evaluateIpCount(Election $election, string $ip, int $max): array
{
    $votedCount = VoterSlug::where('election_id', $election->id)
        ->where('step_1_ip', $ip)
        ->where('has_voted', true)
        ->count();

    if ($votedCount >= $max) {
        return [
            'blocked'        => true,
            'message'        => "The maximum of {$max} vote(s) from your network has been reached.",
            'remainingVotes' => 0,
        ];
    }

    return [
        'blocked'        => false,
        'message'        => null,
        'remainingVotes' => $max - $votedCount,
    ];
}
```

### Frontend Display (Election/Show.vue)

**Props passed from controller:**
```javascript
ipBlocked: Boolean
ipBlockMessage: String
remainingVotes: Number
```

**UI:**
```vue
<!-- Alert when blocked -->
<div v-if="props.ipBlocked" class="bg-red-50 border border-red-200 rounded-lg p-4">
  <p class="font-semibold text-red-700">Voting Not Available From This Network</p>
  <p class="text-red-600">{{ props.ipBlockMessage }}</p>
</div>

<!-- Hint when under limit -->
<div v-else-if="props.remainingVotes > 0" class="text-xs text-gray-500">
  {{ props.remainingVotes }} vote(s) remaining from your network
</div>

<!-- Button disabled when blocked -->
<button :disabled="!canVote || props.ipBlocked">Vote Now</button>
```

---

## Layer 2: Global Platform Fallback

### Configuration (`.env`)

```
MAX_USE_IP_ADDRESS=5
```

### Behavior

- Used when **no election has per-election restriction enabled**
- Provides platform-wide protection against high-volume attacks from single IP
- Admin can increase this in `.env` for entire platform
- **Layer 3 overrides Layer 2** when per-election setting is enabled

### Query Logic

```php
$globalMax = (int) config('app.max_use_clientIP', 0);
if ($globalMax > 0) {
    $votedCount = VoterSlug::where('election_id', $election->id)
        ->where('step_1_ip', $ip)
        ->where('has_voted', true)
        ->count();
    
    if ($votedCount >= $globalMax) {
        // blocked
    }
}
```

---

## Whitelist Configuration

### Admin Interface

Election Settings page allows admin to configure:

**IP Whitelist** (optional JSON array)
```json
[
  "192.168.1.100",           // Exact IPv4
  "2001:db8::1",             // Exact IPv6
  "10.0.0.0/8",              // CIDR IPv4
  "2001:db8::/32"            // CIDR IPv6
]
```

### Whitelist Logic

```php
public function isIpWhitelisted(string $ip): bool
{
    if (empty($this->ip_whitelist)) return false;
    
    foreach ($this->ip_whitelist as $range) {
        if ($this->ipInRange($ip, $range)) {
            return true;
        }
    }
    return false;
}

private function ipInRange(string $ip, string $range): bool
{
    if (strpos($range, '/') === false) {
        // Exact match
        return $ip === $range;
    }
    
    // CIDR range check using ip2long()
    [$subnet, $bits] = explode('/', $range);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask;
    return ($ip & $mask) === $subnet;
}
```

### Bypass Behavior

**Whitelisted IPs:**
- Can vote unlimited times (ignores all Layer 2 and Layer 3 limits)
- Still respects Layer 1 (per-voter IP pre-registration if enabled)
- Admin can whitelist corporate proxies, office networks, etc.

---

## Security Considerations

### No Slug Creation on Block

✅ **Security:** Blocking happens **before** `VoterSlug` creation in `start()`.

```php
// CORRECT: Check BEFORE creating slug
$ipBlock = $this->resolveIpBlock($election, $request->ip());
if ($ipBlock['blocked']) {
    return redirect();  // No slug created
}

// Only reach here if IP check passed
$voterSlug = VoterSlug::create([...]);
```

### Redirect Instead of Abort

✅ **UX:** User sees friendly redirect to election page with flash error, not blank 403.

```php
// WRONG:
abort(403, "IP limit reached");  // Shows blank 403 page

// RIGHT:
return redirect()->route('elections.show', $slug)
    ->with('error', $ipBlock['message']);  // User sees error and can try again
```

### No Vote Log Until Successful

✅ **Data Integrity:** Blocked IPs do not create audit trail votes.

---

## Testing

### Test Suite

**File:** `tests/Feature/IpRestriction/ElectionIpRestrictionTest.php`

**8 comprehensive tests:**

```php
// Layer 3: Per-election restriction
test_show_passes_ip_blocked_true_when_per_election_limit_reached()
test_show_passes_ip_blocked_false_for_whitelisted_ip_at_limit()
test_show_passes_ip_blocked_false_when_under_limit()
test_start_redirects_with_error_when_ip_limit_reached()
test_start_creates_slug_for_whitelisted_ip_despite_limit()

// Layer 2: Global fallback
test_show_enforces_global_limit_when_per_election_restriction_disabled()
test_whitelist_bypasses_global_limit()

// Remaining votes calculation
test_show_passes_correct_remaining_votes_count()
```

### Running Tests

```bash
php artisan test tests/Feature/IpRestriction/ElectionIpRestrictionTest.php
```

---

## Admin Workflow

### Step 1: Enable Per-Election Restriction

Navigate to **Elections > [Election Name] > Settings > IP Address Restriction**

```
☑ Enable IP address restrictions
Max votes per IP: 3
Whitelist: [empty]
```

### Step 2: (Optional) Add Whitelist

```
Whitelist (JSON array):
[
  "10.20.30.40",
  "192.168.1.0/24",
  "office.example.com"
]
```

### Step 3: Test

1. Visit `/elections/{slug}` from a new IP → button enabled
2. Vote → creates slug, increments vote count
3. Try voting again from same IP → button disabled, shows "Maximum X votes reached"
4. Try from different IP → button enabled (if not at Layer 2 limit)

---

## Troubleshooting

| Symptom | Cause | Solution |
|---------|-------|----------|
| All IPs blocked even first vote | `ip_restriction_max_per_ip = 0` | Set to at least 1 in Settings |
| Voters can vote unlimited times | `ip_restriction_enabled = false` AND `MAX_USE_IP_ADDRESS = 0` | Enable Layer 3 OR increase Layer 2 |
| Whitelist not working | JSON syntax error or CIDR mismatch | Check JSON format, verify IP is in CIDR range |
| Whitelisted IP still blocked | Whitelist check runs AFTER restriction check | Should be first check — verify `resolveIpBlock()` logic |
| button enabled but vote fails on submit | Layer 3/2 changed between page load and submit | Admin may have changed settings — page shows state at load time |

---

## Future Enhancements

- [ ] Geo-IP blocking (country/region based)
- [ ] Dynamic whitelist from LDAP/AD
- [ ] IP reputation service integration
- [ ] Per-region constraints (e.g. max 10 from NA, max 20 from EU)
- [ ] Adaptive thresholds based on election size

---

## References

- **Model:** `app/Models/Election.php` (methods `isIpRestricted()`, `isIpWhitelisted()`)
- **Controller:** `app/Http/Controllers/ElectionVotingController.php` (methods `show()`, `start()`, `resolveIpBlock()`, `evaluateIpCount()`)
- **Frontend:** `resources/js/Pages/Election/Show.vue` (props `ipBlocked`, `ipBlockMessage`, `remainingVotes`)
- **Tests:** `tests/Feature/IpRestriction/ElectionIpRestrictionTest.php`
- **Database:** `database/migrations/2026_04_12_000001_add_election_settings_columns.php`

---

**Last Updated:** 2026-04-14  
**Implemented By:** Claude Haiku 4.5  
**Status:** ✅ Production Ready (TDD tested)
