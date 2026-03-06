## ✅ **YES - CONFIGURABLE VOTES PER DEVICE VIA .ENV**

This is **easy to implement** with Laravel's configuration system. Here's how:

---

## 📋 **IMPLEMENTATION**

### **Step 1: Create Voting Configuration File**

```php
// config/voting.php

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vote Limits Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control how many votes are allowed per device, per election.
    | Different organisations may have different requirements:
    | - 1 vote per device (most secure - one person one vote)
    | - 2-3 votes per device (family voting from same computer)
    | - Unlimited (for testing/demo purposes)
    |
    */

    'max_votes_per_device' => env('MAX_VOTES_PER_DEVICE', 1),

    'max_codes_per_device' => env('MAX_CODES_PER_DEVICE', 1),

    /*
    |--------------------------------------------------------------------------
    | Time Window Settings
    |--------------------------------------------------------------------------
    |
    | Define time windows for detecting suspicious activity
    | Example: If a device requests 5 codes in 10 minutes, flag as suspicious
    |
    */

    'device_time_window_minutes' => env('DEVICE_TIME_WINDOW', 10),

    'device_anomaly_threshold' => env('DEVICE_ANOMALY_THRESHOLD', 5),

    /*
    |--------------------------------------------------------------------------
    | Per-Organisation Overrides
    |--------------------------------------------------------------------------
    |
    | Different organisations can have different voting limits
    | Format: 'organisation_uuid' => votes_per_device
    |
    */

    'organisation_overrides' => [
        // 'org-uuid-here' => 3, // Family voting allowed
        // 'another-org-uuid' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | Fallback values if organisation-specific settings aren't found
    |
    */

    'defaults' => [
        'max_votes_per_device' => 1,
        'allow_family_voting' => false,
        'family_voting_message' => 'This election allows multiple votes per device for family members.',
    ],
];
```

---

### **Step 2: Add to .env File**

```env
# .env - Global Default Settings

# Default: 1 vote per device (most secure)
MAX_VOTES_PER_DEVICE=1

# For family voting: 3 votes per device
# MAX_VOTES_PER_DEVICE=3

# For testing/unlimited: 999
# MAX_VOTES_PER_DEVICE=999

# Time window for anomaly detection (minutes)
DEVICE_TIME_WINDOW=10

# Threshold for flagging suspicious activity
DEVICE_ANOMALY_THRESHOLD=5
```

---

### **Step 3: Per-Organisation Configuration**

```php
// app/Models/Organisation.php

class Organisation extends Model
{
    // ... existing code ...

    /**
     * Get max votes per device for this organisation
     */
    public function getMaxVotesPerDevice(): int
    {
        // Check if organisation has custom setting in config
        $overrides = config('voting.organisation_overrides', []);
        
        if (isset($overrides[$this->id])) {
            return $overrides[$this->id];
        }
        
        // Check if organisation has setting in database
        if ($this->settings && isset($this->settings['max_votes_per_device'])) {
            return $this->settings['max_votes_per_device'];
        }
        
        // Fall back to global default
        return config('voting.max_votes_per_device', 1);
    }
    
    /**
     * Check if organisation allows family voting
     */
    public function allowsFamilyVoting(): bool
    {
        return $this->getMaxVotesPerDevice() > 1;
    }
    
    /**
     * Get voting limit message for this organisation
     */
    public function getVotingLimitMessage(): string
    {
        $limit = $this->getMaxVotesPerDevice();
        
        if ($limit === 1) {
            return "⚠️ One vote per device - each person needs their own device.";
        }
        
        if ($limit > 5) {
            return "ℹ️ This is a test/demo election with unlimited votes per device.";
        }
        
        return "👨‍👩‍👧 This election allows {$limit} votes per device for family members.";
    }
}
```

---

### **Step 4: Update Code Model with Configurable Limits**

```php
// app/Models/Code.php

class Code extends Model
{
    // ... existing code ...

    /**
     * Check if device can get more codes for this election
     */
    public static function canDeviceGetMoreCodes(
        string $deviceHash, 
        string $electionId,
        ?Organisation $organisation = null
    ): array {
        // Get max votes from organisation or global config
        $maxVotes = $organisation 
            ? $organisation->getMaxVotesPerDevice()
            : config('voting.max_votes_per_device', 1);
        
        $existingCount = static::where('device_fingerprint_hash', $deviceHash)
            ->where('election_id', $electionId)
            ->count();
        
        $remaining = max(0, $maxVotes - $existingCount);
        
        return [
            'allowed' => $existingCount < $maxVotes,
            'used' => $existingCount,
            'max' => $maxVotes,
            'remaining' => $remaining,
            'message' => $organisation 
                ? $organisation->getVotingLimitMessage()
                : "You have {$remaining} vote(s) remaining on this device.",
        ];
    }
    
    /**
     * Get usage stats for device
     */
    public static function getDeviceUsageStats(
        string $deviceHash, 
        string $electionId
    ): array {
        $codes = static::where('device_fingerprint_hash', $deviceHash)
            ->where('election_id', $electionId)
            ->get();
        
        return [
            'total_codes' => $codes->count(),
            'used_codes' => $codes->where('is_used', true)->count(),
            'unused_codes' => $codes->where('is_used', false)->count(),
            'first_code_at' => $codes->min('created_at'),
            'last_code_at' => $codes->max('created_at'),
        ];
    }
}
```

---

### **Step 5: Update VoteController to Check Limits**

```php
// app/Http/Controllers/VoteController.php

public function checkDeviceLimit(Request $request)
{
    $deviceFingerprint = app(DeviceFingerprint::class)->generate($request);
    $election = Election::findOrFail($request->election_id);
    $organisation = $election->organisation;
    
    $limitCheck = Code::canDeviceGetMoreCodes(
        $deviceFingerprint, 
        $election->id,
        $organisation
    );
    
    if (!$limitCheck['allowed']) {
        return response()->json([
            'error' => 'Device vote limit reached',
            'message' => $limitCheck['message'],
            'limit_info' => [
                'used' => $limitCheck['used'],
                'max' => $limitCheck['max'],
                'remaining' => 0
            ]
        ], 403);
    }
    
    return response()->json([
        'allowed' => true,
        'message' => $limitCheck['message'],
        'limit_info' => [
            'used' => $limitCheck['used'],
            'max' => $limitCheck['max'],
            'remaining' => $limitCheck['remaining']
        ]
    ]);
}

public function store(Request $request)
{
    // ... existing validation ...
    
    $deviceFingerprint = app(DeviceFingerprint::class)->generate($request);
    $election = Election::findOrFail($validated['election_id']);
    $organisation = $election->organisation;
    
    // Check device limit BEFORE creating vote
    $limitCheck = Code::canDeviceGetMoreCodes(
        $deviceFingerprint, 
        $election->id,
        $organisation
    );
    
    if (!$limitCheck['allowed']) {
        Log::warning('Device vote limit reached', [
            'device_hash' => $deviceFingerprint,
            'election_id' => $election->id,
            'used' => $limitCheck['used'],
            'max' => $limitCheck['max']
        ]);
        
        return response()->json([
            'error' => 'Vote limit reached for this device',
            'message' => $organisation->getVotingLimitMessage(),
            'limit_info' => [
                'used' => $limitCheck['used'],
                'max' => $limitCheck['max'],
                'remaining' => 0
            ]
        ], 403);
    }
    
    // ... proceed with vote creation ...
}
```

---

### **Step 6: Add Database Field for Organisation Settings**

```php
// migration to add settings JSON to organisations if not exists
Schema::table('organisations', function (Blueprint $table) {
    $table->json('voting_settings')->nullable()->after('settings');
});

// In Organisation model
protected $casts = [
    'voting_settings' => 'array',
];

public function getVotingSetting(string $key, $default = null)
{
    return $this->voting_settings[$key] ?? $default;
}

public function setVotingSetting(string $key, $value): void
{
    $settings = $this->voting_settings ?? [];
    $settings[$key] = $value;
    $this->voting_settings = $settings;
}
```

---

### **Step 7: Admin Interface for Organisation Settings**

```blade
{{-- resources/views/admin/organisations/settings.blade.php --}}

<div class="card">
    <h3>Voting Configuration</h3>
    
    <div class="form-group">
        <label>Max Votes Per Device</label>
        <select name="voting_settings[max_votes_per_device]" class="form-control">
            <option value="1" {{ $org->getVotingSetting('max_votes_per_device', 1) == 1 ? 'selected' : '' }}>
                1 vote per device (One person, one vote)
            </option>
            <option value="2" {{ $org->getVotingSetting('max_votes_per_device', 1) == 2 ? 'selected' : '' }}>
                2 votes per device (Couple/family voting)
            </option>
            <option value="3" {{ $org->getVotingSetting('max_votes_per_device', 1) == 3 ? 'selected' : '' }}>
                3 votes per device (Small family)
            </option>
            <option value="4" {{ $org->getVotingSetting('max_votes_per_device', 1) == 4 ? 'selected' : '' }}>
                4 votes per device
            </option>
            <option value="5" {{ $org->getVotingSetting('max_votes_per_device', 1) == 5 ? 'selected' : '' }}>
                5 votes per device
            </option>
            <option value="999" {{ $org->getVotingSetting('max_votes_per_device', 1) == 999 ? 'selected' : '' }}>
                Unlimited (Testing/Demo)
            </option>
        </select>
        <small class="text-muted">
            How many votes can be cast from the same device?
        </small>
    </div>
    
    <div class="form-group">
        <label>Family Voting Message</label>
        <input type="text" 
               name="voting_settings[family_message]" 
               class="form-control"
               value="{{ $org->getVotingSetting('family_message', 'This election allows multiple votes per device for family members.') }}">
        <small class="text-muted">
            Message shown to voters about device limits
        </small>
    </div>
</div>
```

---

### **Step 8: Frontend Display for Users**

```vue
<!-- resources/js/components/VotingLimitInfo.vue -->

<template>
  <div class="voting-limit-card" :class="{ 'family-mode': limit > 1 }">
    <div class="limit-icon">
      <span v-if="limit === 1">🔒</span>
      <span v-else-if="limit > 5">🧪</span>
      <span v-else>👨‍👩‍👧</span>
    </div>
    
    <div class="limit-content">
      <h4>{{ title }}</h4>
      <p>{{ message }}</p>
      
      <div v-if="showProgress" class="progress-info">
        <div class="progress-bar">
          <div 
            class="progress-fill" 
            :style="{ width: `${(used / limit) * 100}%` }"
          ></div>
        </div>
        <span class="progress-text">
          {{ used }} of {{ limit }} votes used on this device
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    limit: { type: Number, default: 1 },
    used: { type: Number, default: 0 },
    showProgress: { type: Boolean, default: true },
    customMessage: { type: String, default: null }
  },
  
  computed: {
    title() {
      if (this.limit === 1) return 'One Vote Per Device';
      if (this.limit > 5) return 'Test/Demo Mode';
      return 'Family Voting Enabled';
    },
    
    message() {
      if (this.customMessage) return this.customMessage;
      
      if (this.limit === 1) {
        return 'Each person needs their own device to vote.';
      }
      
      if (this.limit > 5) {
        return 'This is a test election - unlimited votes per device.';
      }
      
      return `This election allows up to ${this.limit} votes per device for family members.`;
    }
  }
}
</script>

<style scoped>
.voting-limit-card {
  background: #f8fafc;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  gap: 1rem;
  border: 2px solid #e2e8f0;
}

.voting-limit-card.family-mode {
  border-color: #48bb78;
  background: #f0fff4;
}

.limit-icon {
  font-size: 2.5rem;
}

.limit-content {
  flex: 1;
}

.limit-content h4 {
  margin: 0 0 0.5rem 0;
  color: #2d3748;
}

.limit-content p {
  margin: 0 0 1rem 0;
  color: #4a5568;
}

.progress-bar {
  height: 8px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.progress-fill {
  height: 100%;
  background: #48bb78;
  transition: width 0.3s ease;
}

.progress-text {
  font-size: 0.9rem;
  color: #718096;
}
</style>
```

---

### **Step 9: API Endpoint for Device Status**

```php
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/device/status', [VoteController::class, 'checkDeviceLimit']);
});
```

```javascript
// Frontend usage
async function checkDeviceStatus() {
    const response = await axios.get('/api/device/status', {
        params: { election_id: currentElectionId }
    });
    
    if (response.data.limit_info.remaining === 0) {
        showLimitReachedMessage(response.data.message);
    }
    
    return response.data;
}
```

---

## 📊 **.ENV OPTIONS SUMMARY**

```env
# GLOBAL DEFAULTS
MAX_VOTES_PER_DEVICE=1           # Options: 1,2,3,4,5,999 (unlimited)
DEVICE_TIME_WINDOW=10             # Minutes for anomaly detection
DEVICE_ANOMALY_THRESHOLD=5        # Codes in time window to flag

# PER-ORGANISATION (in database or config)
# Organisation A (strict) - 1 vote per device
# Organisation B (family) - 3 votes per device
# Organisation C (test) - unlimited
```

---

## ✅ **FEATURES ACHIEVED**

| Feature | Implementation |
|---------|----------------|
| **Global default** | `.env` setting |
| **Per-organisation override** | Database settings or config array |
| **Family voting** | Configurable 2-5 votes per device |
| **Testing mode** | Unlimited (999) for demo elections |
| **User feedback** | Clear messages about limits |
| **Progress tracking** | Shows used/allowed votes |
| **Admin control** | Interface to change settings |

**This gives you complete flexibility - from strict one-person-one-vote to family-friendly multi-vote devices, all configurable without code changes.**