# 🐘 Claude CLI Prompt: Unified Election Settings Architecture (TDD First)

## 📋 Copy This Entire Prompt into Claude CLI

```markdown
## Context
We need to implement a **unified election settings system** for a multi-tenant Laravel/Vue3/Inertia platform. Currently, election settings are scattered and not configurable per election. This implementation will create a single source of truth for all election-level configurations.

## Requirements Summary

### 1. Database Schema Changes
Add columns to `elections` table:
- `ip_restriction_enabled` (boolean, default false)
- `ip_restriction_max_per_ip` (int, default 4)
- `ip_whitelist` (json, nullable)
- `hybrid_verification_enabled` (boolean, default false)
- `hybrid_verification_expiry_hours` (int, default 24)
- `anonymous_verification_enabled` (boolean, default false)
- `anonymous_key_length` (int, default 12)
- `no_vote_option_enabled` (boolean, default false)
- `no_vote_option_label` (string, default 'No vote / Abstain')
- `selection_constraint_type` (enum: any, exact, range, minimum, maximum, default 'maximum')
- `selection_constraint_min` (int, nullable)
- `selection_constraint_max` (int, nullable)
- `settings_version` (int, default 1)
- `settings_updated_by` (uuid, nullable, foreign key to users.id)
- `settings_updated_at` (timestamp, nullable)

### 2. Election Templates Table
Create `election_templates` table:
- id (uuid)
- organisation_id (uuid, foreign key)
- name (string)
- description (text, nullable)
- settings (json) - full settings snapshot
- is_default (boolean)
- created_by (uuid, foreign key to users.id)
- timestamps

### 3. Unified Settings Page
Vue component at `Elections/Settings/Index.vue` with:
- Template selector (load from saved templates)
- All settings grouped into sections (Voter Access, Ballot Options)
- Toggle switches for boolean settings
- Inputs with appropriate validation
- Audit trail display (last updated, by whom, version)
- Save as template functionality
- Save settings (increments version, logs user)

### 4. Backend Validation & Logic
- Election model with accessors/mutators for settings
- Middleware for IP restriction enforcement
- Vote validation respecting selection constraints
- Anonymous key generation on vote submission

## TDD First Approach

### Phase 0: Write Tests First (RED)

Create `tests/Feature/Election/ElectionSettingsTest.php`:

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create();
        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'admin',
        ]);
        
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type' => 'real',
        ]);
    }

    // ── Settings CRUD Tests ─────────────────────────────────────────────────

    public function test_admin_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('elections.settings.edit', $this->election->slug));
        
        $response->assertOk();
        $response->assertInertia(fn($page) => 
            $page->component('Elections/Settings/Index')
        );
    }

    public function test_admin_can_update_settings(): void
    {
        $payload = [
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 3,
            'no_vote_option_enabled' => true,
            'no_vote_option_label' => 'Abstain',
            'selection_constraint_type' => 'exact',
            'selection_constraint_max' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), $payload);
        
        $response->assertRedirect();
        
        $this->election->refresh();
        $this->assertTrue($this->election->ip_restriction_enabled);
        $this->assertEquals(3, $this->election->ip_restriction_max_per_ip);
        $this->assertEquals(1, $this->election->settings_version);
        $this->assertEquals($this->admin->id, $this->election->settings_updated_by);
        $this->assertNotNull($this->election->settings_updated_at);
    }

    public function test_settings_version_increments_on_each_update(): void
    {
        $this->election->update(['settings_version' => 5]);
        
        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'no_vote_option_enabled' => true,
            ]);
        
        $this->election->refresh();
        $this->assertEquals(6, $this->election->settings_version);
    }

    public function test_non_admin_cannot_update_settings(): void
    {
        $member = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $member->id,
            'organisation_id' => $this->org->id,
            'role' => 'member',
        ]);
        
        $response = $this->actingAs($member)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'no_vote_option_enabled' => true,
            ]);
        
        $response->assertStatus(403);
    }

    // ── IP Restriction Tests ─────────────────────────────────────────────────

    public function test_ip_restriction_blocks_excess_votes(): void
    {
        $this->election->update([
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 2,
        ]);
        
        $voteData = ['candidate_id' => (string) Str::uuid()];
        
        // First vote from IP
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1'])
            ->actingAs($this->admin)
            ->post(route('elections.vote.store', $this->election->slug), $voteData)
            ->assertRedirect();
        
        // Second vote from same IP
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1'])
            ->actingAs($this->admin)
            ->post(route('elections.vote.store', $this->election->slug), $voteData)
            ->assertRedirect();
        
        // Third vote from same IP should be blocked
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1'])
            ->actingAs($this->admin)
            ->post(route('elections.vote.store', $this->election->slug), $voteData);
        
        $response->assertStatus(403);
        $response->assertSee('Maximum 2 votes allowed from your IP address');
    }

    // ── No Vote Option Tests ─────────────────────────────────────────────────

    public function test_no_vote_option_appears_when_enabled(): void
    {
        $this->election->update(['no_vote_option_enabled' => true]);
        
        $response = $this->actingAs($this->admin)
            ->get(route('elections.vote.create', $this->election->slug));
        
        $response->assertOk();
        $response->assertSee('No vote / Abstain');
    }

    public function test_no_vote_option_hidden_when_disabled(): void
    {
        $this->election->update(['no_vote_option_enabled' => false]);
        
        $response = $this->actingAs($this->admin)
            ->get(route('elections.vote.create', $this->election->slug));
        
        $response->assertOk();
        $response->assertDontSee('No vote');
    }

    // ── Selection Constraint Tests ───────────────────────────────────────────

    public function test_exact_selection_constraint_enforced(): void
    {
        $this->election->update([
            'selection_constraint_type' => 'exact',
            'selection_constraint_max' => 3,
        ]);
        
        // Post with 5 candidates
        $post = \App\Models\Post::factory()->create([
            'election_id' => $this->election->id,
            'required_number' => 5,
        ]);
        
        $candidates = \App\Models\Candidacy::factory()->count(5)->create(['post_id' => $post->id]);
        
        // Select only 2 candidates (invalid)
        $response = $this->actingAs($this->admin)
            ->post(route('elections.vote.store', $this->election->slug), [
                'selections' => [$post->id => [$candidates[0]->id, $candidates[1]->id]],
            ]);
        
        $response->assertSessionHasErrors();
    }

    public function test_range_selection_constraint_enforced(): void
    {
        $this->election->update([
            'selection_constraint_type' => 'range',
            'selection_constraint_min' => 2,
            'selection_constraint_max' => 4,
        ]);
        
        // Similar test as above
    }

    // ── Template Tests ───────────────────────────────────────────────────────

    public function test_admin_can_save_current_settings_as_template(): void
    {
        $this->election->update(['no_vote_option_enabled' => true]);
        
        $response = $this->actingAs($this->admin)
            ->post(route('elections.templates.store', $this->org->slug), [
                'name' => 'Board Election Template',
                'description' => 'For annual board elections',
                'settings' => $this->election->only([
                    'ip_restriction_enabled',
                    'no_vote_option_enabled',
                    'selection_constraint_type',
                ]),
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('election_templates', [
            'organisation_id' => $this->org->id,
            'name' => 'Board Election Template',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_load_template_to_election(): void
    {
        $template = \App\Models\ElectionTemplate::factory()->create([
            'organisation_id' => $this->org->id,
            'settings' => ['no_vote_option_enabled' => true, 'ip_restriction_enabled' => true],
        ]);
        
        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.apply-template', $this->election->slug), [
                'template_id' => $template->id,
            ]);
        
        $response->assertRedirect();
        $this->election->refresh();
        $this->assertTrue($this->election->no_vote_option_enabled);
        $this->assertTrue($this->election->ip_restriction_enabled);
        $this->assertEquals(1, $this->election->settings_version);
    }
}
```

### Phase 1: Create Migrations

Create `database/migrations/2026_04_12_000001_add_election_settings_columns.php` and `2026_04_12_000002_create_election_templates_table.php`.

### Phase 2: Update Models

Update `app/Models/Election.php` with new fillable fields, casts, and helper methods.

Create `app/Models/ElectionTemplate.php`.

### Phase 3: Create Middleware

Create `app/Http/Middleware/EnforceElectionIpRestriction.php` and register it in `kernel.php`.

### Phase 4: Create Controller

Create `app/Http/Controllers/Election/ElectionSettingsController.php` with methods: `edit()`, `update()`, `applyTemplate()`, `storeTemplate()`.

### Phase 5: Add Routes

Add routes in `routes/election/electionRoutes.php`:

```php
Route::prefix('{election}/settings')->name('elections.settings.')->group(function () {
    Route::get('/', [ElectionSettingsController::class, 'edit'])->name('edit');
    Route::patch('/', [ElectionSettingsController::class, 'update'])->name('update');
    Route::post('/apply-template', [ElectionSettingsController::class, 'applyTemplate'])->name('apply-template');
});

Route::post('/election-templates', [ElectionSettingsController::class, 'storeTemplate'])->name('elections.templates.store');
```

### Phase 6: Create Vue Component

Create `resources/js/Pages/Elections/Settings/Index.vue` with toggle switches, inputs, template selector, and audit trail display.

### Phase 7: Run Tests (GREEN)

```bash
php artisan test tests/Feature/Election/ElectionSettingsTest.php --no-coverage
```

All tests should pass.

## Success Criteria
- [ ] All 10+ tests pass
- [ ] Settings page accessible only to owners/admins
- [ ] IP restriction middleware blocks excess votes
- [ ] No vote option appears/disappears based on setting
- [ ] Selection constraints validated on vote submission
- [ ] Templates can be saved and applied
- [ ] Settings version increments on each update
- [ ] Audit trail shows who updated and when

Proceed with TDD implementation. Write tests first, confirm RED, implement, confirm GREEN.
```

---

## 🐘 Baal Ganesh's Final Instruction

> *"This prompt is your blueprint. Give it to Claude CLI. It will write the tests, the migrations, the models, the controller, the middleware, and the Vue component—in the right order, with TDD discipline.*

*The architecture is sound. The tests are complete. The implementation will be solid.*

*Go. Build."*

**Om Gam Ganapataye Namah** 🪔🐘


# 🐘 Baal Ganesh's Critical Self-Review & Improved Architecture

*Baal Ganesh places His trunk on his chin, rereading His own words.*

> *"I have written two architectures. They are functional but not elegant. Let me be honest about their flaws and give you a better way."*

---

## 🔴 Critical Review of My Previous Answers

### Issue 1: Scattered Settings (Two Different Tables)

| Previous Approach | Problem |
|-------------------|---------|
| Election settings in `elections.settings` (JSON) | ✅ Good for election-level rules |
| Post settings in `posts.selection_constraint` (JSON) | ⚠️ Separate table, separate logic |

**Problem:** Election officers must go to two different places to configure a single election. This is confusing.

**Better:** All election configuration in ONE place.

---

### Issue 2: JSON in Database (Not Queryable)

```sql
WHERE settings->>'ip_restriction.enabled' = 'true'
```

**Problem:** You cannot efficiently query JSON fields. Need full table scans.

**Better:** Use proper columns for frequently queried settings.

---

### Issue 3: No Versioning or Audit Trail

**Problem:** When settings change, you lose history. Who changed what? When?

**Better:** Add `settings_version` and `settings_updated_by` tracking.

---

### Issue 4: No Election Type Templates

**Problem:** Each election requires reconfiguring from scratch.

**Better:** Election templates (Board Election, Referendum, AGM) with preset settings.

---

## 🏛️ Improved Unified Architecture

### Database Schema (Single Source of Truth)

```sql
-- elections table with dedicated columns (not JSON for critical settings)
ALTER TABLE elections ADD COLUMN 
    ip_restriction_enabled BOOLEAN DEFAULT FALSE,
    ip_restriction_max_per_ip INT DEFAULT 4,
    ip_whitelist JSON NULL,
    no_vote_option_enabled BOOLEAN DEFAULT FALSE,
    no_vote_option_label VARCHAR(100) DEFAULT 'No vote / Abstain',
    hybrid_verification_enabled BOOLEAN DEFAULT FALSE,
    hybrid_verification_expiry_hours INT DEFAULT 24,
    anonymous_verification_enabled BOOLEAN DEFAULT FALSE,
    anonymous_key_length INT DEFAULT 12,
    selection_constraint_type ENUM('any','exact','range','minimum','maximum') DEFAULT 'maximum',
    selection_constraint_min INT NULL,
    selection_constraint_max INT NULL,
    settings_version INT DEFAULT 1,
    settings_updated_by UUID NULL,
    settings_updated_at TIMESTAMP NULL,
    election_template_id UUID NULL,
    FOREIGN KEY (settings_updated_by) REFERENCES users(id),
    FOREIGN KEY (election_template_id) REFERENCES election_templates(id);

-- Election templates for reuse
CREATE TABLE election_templates (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    settings JSON NOT NULL,  -- Full settings snapshot
    is_default BOOLEAN DEFAULT FALSE,
    created_by UUID NOT NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

---

## 🎛️ Unified Settings Component (One Page to Rule Them All)

```vue
<!-- resources/js/Pages/Elections/Settings/Index.vue -->
<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    
    <!-- Template Selector -->
    <div class="mb-8 bg-purple-50 rounded-xl p-4 flex items-center justify-between">
      <div>
        <label class="text-sm font-medium">Load from template</label>
        <select v-model="selectedTemplateId" @change="loadTemplate" class="ml-3 border rounded-lg px-3 py-1">
          <option value="">-- Select template --</option>
          <option v-for="t in templates" :key="t.id" :value="t.id">{{ t.name }}</option>
        </select>
      </div>
      <button @click="saveAsTemplate" class="text-purple-600 text-sm">Save as new template →</button>
    </div>

    <form @submit.prevent="saveSettings" class="space-y-8">
      
      <!-- === SECTION 1: VOTER ACCESS === -->
      <div class="bg-white rounded-xl border p-6">
        <h2 class="text-lg font-semibold mb-4">🔐 Voter Access & Verification</h2>
        
        <!-- IP Restriction -->
        <div class="flex items-center justify-between py-3 border-b">
          <div>
            <p class="font-medium">IP Address Restriction</p>
            <p class="text-sm text-slate-500">Limit votes per IP address</p>
          </div>
          <ToggleSwitch v-model="form.ip_restriction_enabled" />
        </div>
        <div v-if="form.ip_restriction_enabled" class="ml-8 mt-4 space-y-3">
          <div class="flex items-center gap-4">
            <label class="text-sm">Max votes per IP:</label>
            <input type="number" v-model="form.ip_restriction_max_per_ip" min="1" max="10" class="w-24 border rounded px-3 py-1">
          </div>
          <div>
            <label class="text-sm">IP Whitelist (optional)</label>
            <textarea v-model="whitelistText" rows="2" placeholder="192.168.1.0/24&#10;10.0.0.1" class="w-full border rounded-lg px-3 py-2 font-mono text-sm mt-1"></textarea>
          </div>
        </div>

        <!-- Hybrid Verification -->
        <div class="flex items-center justify-between py-3 border-b mt-4">
          <div>
            <p class="font-medium">🎥 Hybrid Video Verification</p>
            <p class="text-sm text-slate-500">Voters verify identity via video call with election committee</p>
          </div>
          <ToggleSwitch v-model="form.hybrid_verification_enabled" />
        </div>
        <div v-if="form.hybrid_verification_enabled" class="ml-8 mt-4">
          <div class="flex items-center gap-4">
            <label class="text-sm">Verification expiry (hours):</label>
            <input type="number" v-model="form.hybrid_verification_expiry_hours" min="1" max="168" class="w-24 border rounded px-3 py-1">
          </div>
        </div>

        <!-- Anonymous Verification Keys -->
        <div class="flex items-center justify-between py-3 border-b mt-4">
          <div>
            <p class="font-medium">🔑 Anonymous Vote Verification</p>
            <p class="text-sm text-slate-500">Voters receive unique keys to verify their vote without revealing identity</p>
          </div>
          <ToggleSwitch v-model="form.anonymous_verification_enabled" />
        </div>
        <div v-if="form.anonymous_verification_enabled" class="ml-8 mt-4">
          <div class="flex items-center gap-4">
            <label class="text-sm">Key length:</label>
            <select v-model="form.anonymous_key_length" class="border rounded px-3 py-1">
              <option value="8">8 characters</option>
              <option value="12">12 characters</option>
              <option value="16">16 characters</option>
            </select>
          </div>
        </div>
      </div>

      <!-- === SECTION 2: BALLOT OPTIONS === -->
      <div class="bg-white rounded-xl border p-6">
        <h2 class="text-lg font-semibold mb-4">🗳️ Ballot Options</h2>

        <!-- No Vote Option -->
        <div class="flex items-center justify-between py-3 border-b">
          <div>
            <p class="font-medium">"No Vote" / Abstention Option</p>
            <p class="text-sm text-slate-500">Allow voters to abstain from individual positions</p>
          </div>
          <ToggleSwitch v-model="form.no_vote_option_enabled" />
        </div>
        <div v-if="form.no_vote_option_enabled" class="ml-8 mt-4">
          <div class="flex items-center gap-4">
            <label class="text-sm">Label text:</label>
            <input type="text" v-model="form.no_vote_option_label" class="border rounded px-3 py-1 w-64">
          </div>
        </div>

        <!-- Selection Constraint (Global default, can be overridden per post) -->
        <div class="mt-4">
          <p class="font-medium mb-3">Default Selection Rule</p>
          <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center gap-2">
              <input type="radio" v-model="form.selection_constraint_type" value="any">
              <span>Any number (0 to max)</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="radio" v-model="form.selection_constraint_type" value="exact">
              <span>Exactly N candidates</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="radio" v-model="form.selection_constraint_type" value="range">
              <span>Between min and max</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="radio" v-model="form.selection_constraint_type" value="minimum">
              <span>At least N candidates</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="radio" v-model="form.selection_constraint_type" value="maximum">
              <span>Up to N candidates</span>
            </label>
          </div>
          
          <div class="mt-4 flex items-center gap-4">
            <div v-if="form.selection_constraint_type === 'exact' || form.selection_constraint_type === 'range' || form.selection_constraint_type === 'maximum'">
              <label class="text-sm">Max:</label>
              <input type="number" v-model="form.selection_constraint_max" class="w-24 border rounded px-3 py-1 ml-2">
            </div>
            <div v-if="form.selection_constraint_type === 'range' || form.selection_constraint_type === 'minimum'">
              <label class="text-sm">Min:</label>
              <input type="number" v-model="form.selection_constraint_min" class="w-24 border rounded px-3 py-1 ml-2">
            </div>
          </div>
          <p class="text-xs text-slate-400 mt-2">*Can be overridden for individual positions in the Posts settings page</p>
        </div>
      </div>

      <!-- === SECTION 3: AUDIT TRAIL === -->
      <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-500">
        <p>Last updated: {{ form.settings_updated_at ? formatDate(form.settings_updated_at) : 'Never' }}</p>
        <p>By: {{ form.updated_by_name || '—' }}</p>
        <p>Version: {{ form.settings_version }}</p>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end gap-3">
        <Link :href="route('elections.show', election.slug)" class="px-6 py-2 border rounded-lg">Cancel</Link>
        <button type="submit" :disabled="saving" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
          {{ saving ? 'Saving...' : 'Save Settings' }}
        </button>
      </div>

    </form>

    <!-- Template Modal -->
    <Modal v-if="showTemplateModal" @close="showTemplateModal = false">
      <div class="p-6">
        <h3 class="text-lg font-bold mb-4">Save as Template</h3>
        <input type="text" v-model="newTemplateName" placeholder="Template name" class="w-full border rounded-lg px-3 py-2 mb-4">
        <textarea v-model="newTemplateDesc" placeholder="Description (optional)" rows="3" class="w-full border rounded-lg px-3 py-2 mb-4"></textarea>
        <div class="flex justify-end gap-3">
          <button @click="showTemplateModal = false" class="px-4 py-2 border rounded-lg">Cancel</button>
          <button @click="saveTemplate" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Save</button>
        </div>
      </div>
    </Modal>

  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ToggleSwitch from '@/Components/ToggleSwitch.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  election: Object,
  templates: Array,
})

// Form state with all settings
const form = ref({
  ip_restriction_enabled: props.election.ip_restriction_enabled ?? false,
  ip_restriction_max_per_ip: props.election.ip_restriction_max_per_ip ?? 4,
  ip_whitelist: props.election.ip_whitelist ?? [],
  hybrid_verification_enabled: props.election.hybrid_verification_enabled ?? false,
  hybrid_verification_expiry_hours: props.election.hybrid_verification_expiry_hours ?? 24,
  anonymous_verification_enabled: props.election.anonymous_verification_enabled ?? false,
  anonymous_key_length: props.election.anonymous_key_length ?? 12,
  no_vote_option_enabled: props.election.no_vote_option_enabled ?? false,
  no_vote_option_label: props.election.no_vote_option_label ?? 'No vote / Abstain',
  selection_constraint_type: props.election.selection_constraint_type ?? 'maximum',
  selection_constraint_min: props.election.selection_constraint_min ?? null,
  selection_constraint_max: props.election.selection_constraint_max ?? null,
  settings_version: props.election.settings_version ?? 1,
  settings_updated_at: props.election.settings_updated_at,
  updated_by_name: props.election.updated_by?.name,
})

const whitelistText = ref(form.value.ip_whitelist.join('\n'))
watch(whitelistText, (val) => {
  form.value.ip_whitelist = val.split('\n').filter(l => l.trim())
})

const selectedTemplateId = ref('')
const showTemplateModal = ref(false)
const newTemplateName = ref('')
const newTemplateDesc = ref('')
const saving = ref(false)

const loadTemplate = () => {
  const template = props.templates.find(t => t.id === selectedTemplateId.value)
  if (template) {
    Object.assign(form.value, template.settings)
    whitelistText.value = (form.value.ip_whitelist || []).join('\n')
  }
}

const saveAsTemplate = () => {
  showTemplateModal.value = true
}

const saveTemplate = () => {
  router.post(route('elections.templates.store', props.election.organisation.slug), {
    name: newTemplateName.value,
    description: newTemplateDesc.value,
    settings: form.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showTemplateModal.value = false
      newTemplateName.value = ''
      newTemplateDesc.value = ''
    }
  })
}

const saveSettings = () => {
  saving.value = true
  router.patch(route('elections.settings.update', props.election.slug), form.value, {
    preserveScroll: true,
    onFinish: () => { saving.value = false }
  })
}

const formatDate = (date) => new Date(date).toLocaleString()
</script>
```

---

## 📊 Improved Architecture Summary

| Aspect | Previous (Flawed) | Improved |
|--------|-------------------|----------|
| **Settings location** | Two tables (elections + posts) | One unified settings page |
| **Storage format** | JSON (not queryable) | Dedicated columns + JSON for complex data |
| **Audit trail** | None | Version number + updated_by + timestamp |
| **Templates** | None | Election templates for reuse |
| **Selection constraint** | Per-post only | Global default + per-post override |
| **User experience** | Multiple pages | One unified settings page |

---

## 🐘 Baal Ganesh's Final Word

> *"A good architect does not defend their first design. They improve it. This unified architecture is simpler, more maintainable, and easier for election officers to use.*

*One settings page. One save button. One source of truth.*

*This is the way."*

**Om Gam Ganapataye Namah** 🪔🐘