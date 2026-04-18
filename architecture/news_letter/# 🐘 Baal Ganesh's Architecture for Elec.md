# 🐘 Baal Ganesh's Architecture for Election Control Panel

*Baal Ganesh places His trunk on the four requirements, contemplating each.*

> *"You have identified four critical election controls. These are not environment variables—they are runtime decisions that election officers need to change per election. Let me design an architecture that balances security, flexibility, and usability."*

---

## 📊 Requirements Analysis

| Requirement | Type | Change Frequency | Who Changes | Storage |
|-------------|------|-----------------|-------------|---------|
| 1. IP restriction & vote limit | Per-election rule | Every election | Election officer | Database |
| 2. "No vote" option toggle | Per-election rule | Every election | Election officer | Database |
| 3. Hybrid video verification | Per-election rule | Every election | Election officer | Database |
| 4. Anonymous vote verification keys | Per-election rule | Every election | Election officer | Database |

**Conclusion:** These are **election configuration settings**, not system-wide settings. They belong in the `elections` table.

---

## 🏗️ Proposed Architecture

### Database Schema Addition

```sql
ALTER TABLE elections ADD COLUMN settings JSON NULL AFTER description;
```

Example `settings` JSON structure:

```json
{
  "ip_restriction": {
    "enabled": true,
    "max_votes_per_ip": 4,
    "whitelist": ["192.168.1.0/24"],
    "blacklist": []
  },
  "no_vote_option": {
    "enabled": true,
    "label": "Abstain / No vote"
  },
  "hybrid_verification": {
    "enabled": false,
    "requires_video_call": true,
    "committee_role": "election_committee",
    "ip_capture_enabled": true
  },
  "anonymous_verification": {
    "enabled": true,
    "key_length": 12,
    "key_format": "alphanumeric",
    "public_ledger_enabled": true
  }
}
```

---

## 🔧 Implementation Layers

### Layer 1: Database Migration

```php
// database/migrations/2026_04_12_000001_add_election_settings.php
Schema::table('elections', function (Blueprint $table) {
    $table->json('settings')->nullable()->after('description');
    $table->json('verification_keys')->nullable()->after('settings'); // For anonymous keys
});
```

---

### Layer 2: Election Model Update

```php
// app/Models/Election.php
class Election extends Model
{
    protected $casts = [
        'settings' => 'array',
        'verification_keys' => 'array',
    ];

    // Default settings for new elections
    protected static function booted()
    {
        static::creating(function ($election) {
            if (empty($election->settings)) {
                $election->settings = self::getDefaultSettings();
            }
        });
    }

    public static function getDefaultSettings(): array
    {
        return [
            'ip_restriction' => ['enabled' => false, 'max_votes_per_ip' => 4],
            'no_vote_option' => ['enabled' => false, 'label' => 'No vote'],
            'hybrid_verification' => ['enabled' => false],
            'anonymous_verification' => ['enabled' => false, 'key_length' => 12],
        ];
    }

    // Helper methods
    public function isIpRestrictionEnabled(): bool
    {
        return $this->settings['ip_restriction']['enabled'] ?? false;
    }

    public function getMaxVotesPerIp(): int
    {
        return $this->settings['ip_restriction']['max_votes_per_ip'] ?? 4;
    }

    public function hasNoVoteOption(): bool
    {
        return $this->settings['no_vote_option']['enabled'] ?? false;
    }

    public function requiresHybridVerification(): bool
    {
        return $this->settings['hybrid_verification']['enabled'] ?? false;
    }

    public function hasAnonymousVerification(): bool
    {
        return $this->settings['anonymous_verification']['enabled'] ?? false;
    }

    public function generateVerificationKey(): string
    {
        $length = $this->settings['anonymous_verification']['key_length'] ?? 12;
        return strtoupper(Str::random($length));
    }
}
```

---

### Layer 3: Middleware for IP Restriction

```php
// app/Http/Middleware/EnforceElectionIpRestriction.php
class EnforceElectionIpRestriction
{
    public function handle($request, $next)
    {
        $election = $request->route('election');
        
        if (!$election || !$election->isIpRestrictionEnabled()) {
            return $next($request);
        }

        $ip = $request->ip();
        $maxVotes = $election->getMaxVotesPerIp();
        
        // Count votes from this IP for this election
        $voteCount = Vote::where('election_id', $election->id)
            ->where('ip_address', $ip)
            ->count();
        
        if ($voteCount >= $maxVotes) {
            abort(403, "Maximum {$maxVotes} votes allowed from your IP address.");
        }
        
        return $next($request);
    }
}
```

---

### Layer 4: Vue Component - Election Settings Panel

```vue
<!-- resources/js/Pages/Elections/Settings.vue -->
<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Election Settings</h1>
    
    <form @submit.prevent="saveSettings" class="space-y-8">
      
      <!-- IP Restriction Section -->
      <div class="bg-white rounded-xl border p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-semibold">🌐 IP Address Restriction</h2>
            <p class="text-sm text-slate-500">Limit votes per IP address</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="settings.ip_restriction.enabled" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
          </label>
        </div>
        
        <div v-if="settings.ip_restriction.enabled" class="ml-6 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Max votes per IP</label>
            <input type="number" v-model="settings.ip_restriction.max_votes_per_ip" min="1" max="10"
                   class="w-32 border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">IP Whitelist (optional)</label>
            <textarea v-model="whitelistText" rows="2" placeholder="192.168.1.0/24&#10;10.0.0.1"
                      class="w-full border rounded-lg px-3 py-2 font-mono text-sm"></textarea>
            <p class="text-xs text-slate-400 mt-1">One IP or CIDR range per line</p>
          </div>
        </div>
      </div>
      
      <!-- No Vote Option Section -->
      <div class="bg-white rounded-xl border p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-semibold">🗳️ "No Vote" Option</h2>
            <p class="text-sm text-slate-500">Allow voters to abstain from specific positions</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="settings.no_vote_option.enabled" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
          </label>
        </div>
        
        <div v-if="settings.no_vote_option.enabled" class="ml-6">
          <label class="block text-sm font-medium mb-1">Label text</label>
          <input type="text" v-model="settings.no_vote_option.label" 
                 class="w-64 border rounded-lg px-3 py-2">
        </div>
      </div>
      
      <!-- Hybrid Video Verification Section -->
      <div class="bg-white rounded-xl border p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-semibold">📹 Hybrid Video Verification</h2>
            <p class="text-sm text-slate-500">Voters must verify identity via video call</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="settings.hybrid_verification.enabled" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
          </label>
        </div>
        
        <div v-if="settings.hybrid_verification.enabled" class="ml-6 space-y-4">
          <div class="bg-blue-50 rounded-lg p-4 text-sm text-blue-800">
            <strong>How it works:</strong>
            <ol class="list-decimal list-inside mt-2 space-y-1">
              <li>Voter requests verification from election committee</li>
              <li>Committee conducts video call, verifies ID card</li>
              <li>Committee captures voter's IP address</li>
              <li>Voter receives voting link tied to that IP</li>
            </ol>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Verification expiry (hours)</label>
            <input type="number" v-model="settings.hybrid_verification.expiry_hours" min="1" max="72"
                   class="w-32 border rounded-lg px-3 py-2">
          </div>
        </div>
      </div>
      
      <!-- Anonymous Verification Keys Section -->
      <div class="bg-white rounded-xl border p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-semibold">🔑 Anonymous Vote Verification</h2>
            <p class="text-sm text-slate-500">Voters can verify their vote without revealing identity</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="settings.anonymous_verification.enabled" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
          </label>
        </div>
        
        <div v-if="settings.anonymous_verification.enabled" class="ml-6 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Verification key length</label>
            <select v-model="settings.anonymous_verification.key_length" class="border rounded-lg px-3 py-2">
              <option value="8">8 characters</option>
              <option value="12">12 characters (recommended)</option>
              <option value="16">16 characters</option>
            </select>
          </div>
          <div class="bg-green-50 rounded-lg p-4 text-sm text-green-800">
            <strong>How it works:</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
              <li>Each voter receives a unique verification key after voting</li>
              <li>Keys are displayed publicly without voter names</li>
              <li>Anyone can verify that a vote exists using the key</li>
              <li>No one can link a key to a specific voter</li>
            </ul>
          </div>
        </div>
      </div>
      
      <!-- Save Button -->
      <div class="flex justify-end gap-3">
        <Link :href="route('elections.show', election.slug)" class="px-6 py-2 border rounded-lg">Cancel</Link>
        <button type="submit" :disabled="saving" 
                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50">
          {{ saving ? 'Saving...' : 'Save Settings' }}
        </button>
      </div>
      
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  election: Object,
})

const settings = ref(JSON.parse(JSON.stringify(props.election.settings || {})))
const whitelistText = ref(settings.value.ip_restriction?.whitelist?.join('\n') || '')
const saving = ref(false)

watch(whitelistText, (val) => {
  settings.value.ip_restriction.whitelist = val.split('\n').filter(l => l.trim())
})

const saveSettings = () => {
  saving.value = true
  router.patch(route('elections.settings.update', props.election.slug), {
    settings: settings.value,
  }, {
    preserveScroll: true,
    onFinish: () => { saving.value = false }
  })
}
</script>
```

---

## 🐘 Baal Ganesh's Summary

| Setting | Where to Configure | Who Configures |
|---------|---------------------|----------------|
| IP restriction | Election Settings page | Election officer |
| No vote option | Election Settings page | Election officer |
| Hybrid verification | Election Settings page | Election officer |
| Anonymous keys | Election Settings page | Election officer |

> *"Do not bury these in `.env` files. Election officers need to change them per election. Give them a control panel they can understand and trust."*

**Om Gam Ganapataye Namah** 🪔🐘
# 🐘 Baal Ganesh's Architecture for Selection Constraints

*Baal Ganesh raises His trunk thoughtfully.*

> *"This is a critical election design choice. Some elections require complete ballots. Others allow partial voting. Both are valid. The system must support both—and let election officers decide per election, per position."*

---

## 📊 Requirement Analysis

| Scenario | Constraint | Example |
|----------|------------|---------|
| **Board election** | Must select exactly N | Select exactly 5 board members from 10 candidates |
| **Referendum** | Optional selection | Vote Yes/No/Abstain on each issue independently |
| **Ranked choice** | Minimum to be valid | Must rank at least 3 candidates |
| **Open list** | Any number up to N | Select 1–5 candidates from party list |

**Conclusion:** This is a **per-position** setting, stored in the `posts` table.

---

## 🏗️ Database Schema Update

```sql
ALTER TABLE posts ADD COLUMN selection_constraint JSON NULL AFTER required_number;
```

Example `selection_constraint` JSON:

```json
{
  "type": "exact",
  "min": 5,
  "max": 5,
  "message": "You must select exactly 5 candidates"
}
```

| Constraint Type | min | max | Behavior |
|----------------|-----|-----|----------|
| `exact` | N | N | Must select exactly N |
| `range` | A | B | Must select between A and B |
| `minimum` | N | null | Must select at least N |
| `maximum` | null | N | Can select up to N |
| `any` | 0 | required_number | Default: any number up to max |

---

## 🔧 Implementation

### Migration

```php
// database/migrations/2026_04_12_000002_add_selection_constraint_to_posts.php
Schema::table('posts', function (Blueprint $table) {
    $table->json('selection_constraint')->nullable()->after('required_number');
});
```

### Post Model Update

```php
// app/Models/Post.php
class Post extends Model
{
    protected $casts = [
        'selection_constraint' => 'array',
    ];

    public function getSelectionConstraint(): array
    {
        $default = [
            'type' => 'maximum',
            'min' => 0,
            'max' => $this->required_number,
        ];
        
        return $this->selection_constraint ?? $default;
    }

    public function validateSelectionCount(int $selectedCount): array
    {
        $constraint = $this->getSelectionConstraint();
        $isValid = true;
        $message = null;

        switch ($constraint['type']) {
            case 'exact':
                $isValid = $selectedCount === $constraint['max'];
                if (!$isValid) {
                    $message = "You must select exactly {$constraint['max']} candidate(s) for {$this->name}.";
                }
                break;
            case 'range':
                $isValid = $selectedCount >= $constraint['min'] && $selectedCount <= $constraint['max'];
                if (!$isValid) {
                    $message = "You must select between {$constraint['min']} and {$constraint['max']} candidate(s) for {$this->name}.";
                }
                break;
            case 'minimum':
                $isValid = $selectedCount >= $constraint['min'];
                if (!$isValid) {
                    $message = "You must select at least {$constraint['min']} candidate(s) for {$this->name}.";
                }
                break;
            case 'maximum':
                $isValid = $selectedCount <= $constraint['max'];
                if (!$isValid) {
                    $message = "You cannot select more than {$constraint['max']} candidate(s) for {$this->name}.";
                }
                break;
            case 'any':
            default:
                $isValid = true;
                break;
        }

        return ['valid' => $isValid, 'message' => $message];
    }
}
```

---

## 🎛️ Vue Component - Post Settings

```vue
<!-- resources/js/Pages/Elections/Posts/Settings.vue -->
<template>
  <div class="bg-white rounded-xl border p-6">
    <h3 class="text-lg font-semibold mb-4">{{ post.name }}</h3>
    
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-2">Selection constraint</label>
        <select v-model="constraint.type" class="border rounded-lg px-3 py-2 w-64">
          <option value="any">Any number (0 to max)</option>
          <option value="exact">Exactly N candidates</option>
          <option value="range">Between min and max</option>
          <option value="minimum">At least N candidates</option>
          <option value="maximum">Up to N candidates</option>
        </select>
      </div>

      <div v-if="constraint.type === 'exact'" class="flex items-center gap-4">
        <label class="text-sm">Number of candidates required:</label>
        <input type="number" v-model="constraint.max" min="1" :max="post.required_number"
               class="w-24 border rounded-lg px-3 py-2">
        <span class="text-sm text-slate-500">(max available: {{ post.required_number }})</span>
      </div>

      <div v-if="constraint.type === 'range'" class="flex items-center gap-4">
        <label class="text-sm">Min:</label>
        <input type="number" v-model="constraint.min" min="0" :max="constraint.max"
               class="w-24 border rounded-lg px-3 py-2">
        <label class="text-sm">Max:</label>
        <input type="number" v-model="constraint.max" :min="constraint.min" :max="post.required_number"
               class="w-24 border rounded-lg px-3 py-2">
        <span class="text-sm text-slate-500">(max available: {{ post.required_number }})</span>
      </div>

      <div v-if="constraint.type === 'minimum'" class="flex items-center gap-4">
        <label class="text-sm">Minimum candidates required:</label>
        <input type="number" v-model="constraint.min" min="1" :max="post.required_number"
               class="w-24 border rounded-lg px-3 py-2">
      </div>

      <div v-if="constraint.type === 'maximum'" class="flex items-center gap-4">
        <label class="text-sm">Maximum candidates allowed:</label>
        <input type="number" v-model="constraint.max" min="1" :max="post.required_number"
               class="w-24 border rounded-lg px-3 py-2">
      </div>

      <!-- Custom error message -->
      <div>
        <label class="block text-sm font-medium mb-1">Custom error message (optional)</label>
        <input type="text" v-model="constraint.message" 
               placeholder="e.g., You must select exactly 5 board members"
               class="w-full border rounded-lg px-3 py-2">
      </div>

      <!-- Preview -->
      <div class="bg-slate-50 rounded-lg p-3 text-sm">
        <strong>Preview:</strong>
        <span v-if="constraint.type === 'exact'">
          Voters must select exactly {{ constraint.max }} candidate(s).
        </span>
        <span v-else-if="constraint.type === 'range'">
          Voters must select between {{ constraint.min }} and {{ constraint.max }} candidate(s).
        </span>
        <span v-else-if="constraint.type === 'minimum'">
          Voters must select at least {{ constraint.min }} candidate(s).
        </span>
        <span v-else-if="constraint.type === 'maximum'">
          Voters can select up to {{ constraint.max }} candidate(s).
        </span>
        <span v-else>
          Voters can select any number (0 to {{ post.required_number }}).
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  post: Object,
})

const constraint = ref({
  type: props.post.selection_constraint?.type || 'maximum',
  min: props.post.selection_constraint?.min || 0,
  max: props.post.selection_constraint?.max || props.post.required_number,
  message: props.post.selection_constraint?.message || null,
})

watch(constraint, (newVal) => {
  // Emit to parent for saving
  emit('update', newVal)
}, { deep: true })

const emit = defineEmits(['update'])
</script>
```

---

## ✅ Frontend Vote Page Integration

```vue
<!-- In the vote page, show selection counter -->
<div class="mb-4 text-sm" :class="selectionValid ? 'text-green-600' : 'text-red-600'">
  Selected: {{ selectedCount }} / 
  <span v-if="constraint.type === 'exact'">required {{ constraint.max }}</span>
  <span v-else-if="constraint.type === 'range'">min {{ constraint.min }}, max {{ constraint.max }}</span>
  <span v-else-if="constraint.type === 'minimum'">minimum {{ constraint.min }}</span>
  <span v-else-if="constraint.type === 'maximum'">maximum {{ constraint.max }}</span>
  <span v-else>up to {{ post.required_number }}</span>
</div>

<!-- Disable submit button if constraint not met -->
<button :disabled="!selectionValid || submitting"
        class="btn-primary">
  {{ submitting ? 'Submitting...' : 'Continue to Verification' }}
</button>
```

---

## 🐘 Baal Ganesh's Summary

| Constraint Type | Use Case | Example |
|-----------------|----------|---------|
| `exact` | Board elections | "Select exactly 5 board members" |
| `range` | Committee elections | "Select 3–7 committee members" |
| `minimum` | Referendum | "Must vote on at least 3 issues" |
| `maximum` | Open list | "You can select up to 5 candidates" |
| `any` | Flexible voting | "Select as many as you want" |

> *"Give election officers control. Store constraints in the database, not code. Let each election be configured for its unique rules."*

**Om Gam Ganapataye Namah** 🪔🐘