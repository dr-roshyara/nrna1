# ✅ EXECUTING SEQUENTIAL PLAN - STEP 2: Governance Levels (Admin UI)

## Starting Point

```yaml
Status:
  ✅ Step 1: Organisation Creation (complete - handled by existing system)
  ⏳ Step 2: Define Governance Levels (START HERE)

Goal:
  - Create backend tables for governance level definitions
  - Build admin API endpoints (CRUD)
  - Create Vue admin forms for managing levels
```

---

## 📋 Step 2.1: Create Governance Level Definition Table

### Migration

```php
// database/migrations/2026_05_10_000010_create_governance_level_definitions_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governance_level_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id', 36);
            $table->integer('level')->unsigned(); // 0, 1, 2, 3, 4, 5...
            $table->string('committee_name', 100);
            $table->string('committee_code', 20);
            $table->string('geo_name', 100);
            $table->string('geo_code', 20);
            $table->string('geo_parent_code', 20)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'level']);
            $table->index(['tenant_id', 'is_active', 'level']);
            $table->foreign('tenant_id')->references('id')->on('organisations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_level_definitions');
    }
};
```

### Model

```php
// app/Models/GovernanceLevelDefinition.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernanceLevelDefinition extends Model
{
    protected $table = 'governance_level_definitions';

    protected $fillable = [
        'tenant_id', 'level', 'committee_name', 'committee_code',
        'geo_name', 'geo_code', 'geo_parent_code', 'description',
        'is_active', 'sort_order', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'sort_order' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'tenant_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

## 📋 Step 2.2: Create Admin API Endpoints

### Controller

```php
// app/Http/Controllers/Admin/GovernanceLevelController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Models\GovernanceLevelDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class GovernanceLevelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenantId = $request->user()->current_organisation_id;
        
        $levels = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->orderBy('level')
            ->get();
        
        return response()->json($levels);
    }

    public function store(Request $request): JsonResponse
    {
        $tenantId = $request->user()->current_organisation_id;
        
        $validated = $request->validate([
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:10',
                Rule::unique('governance_level_definitions')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],
            'committee_name' => 'required|string|max:100',
            'committee_code' => 'required|string|max:20',
            'geo_name' => 'required|string|max:100',
            'geo_code' => 'required|string|max:20',
            'geo_parent_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['tenant_id'] = $tenantId;
        $validated['created_by'] = $request->user()->id;

        $level = GovernanceLevelDefinition::create($validated);

        return response()->json($level, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $tenantId = $request->user()->current_organisation_id;
        
        $level = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'committee_name' => 'required|string|max:100',
            'committee_code' => 'required|string|max:20',
            'geo_name' => 'required|string|max:100',
            'geo_code' => 'required|string|max:20',
            'geo_parent_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['updated_by'] = $request->user()->id;

        $level->update($validated);

        return response()->json($level);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $tenantId = $request->user()->current_organisation_id;
        
        $level = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $level->delete();

        return response()->json(null, 204);
    }
}
```

### Routes

```php
// routes/api.php (add to admin section)

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::apiResource('governance-levels', GovernanceLevelController::class);
});
```

---

## 📋 Step 2.3: Admin Vue Component

### TypeScript Interfaces

```typescript
// resources/js/types/governance-level.types.ts

export interface GovernanceLevelDefinition {
    id: number;
    tenant_id: string;
    level: number;
    committee_name: string;
    committee_code: string;
    geo_name: string;
    geo_code: string;
    geo_parent_code: string | null;
    description: string | null;
    is_active: boolean;
    sort_order: number;
    created_by: string | null;
    updated_by: string | null;
    created_at: string;
    updated_at: string;
}
```

### Admin Vue Component

```vue
<!-- resources/js/pages/admin/GovernanceLevels.vue -->

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Governance Level Definitions</h1>
      <button 
        @click="addLevel" 
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        + Add Level
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-3 border text-left">Level</th>
            <th class="p-3 border text-left">Committee Name</th>
            <th class="p-3 border text-left">Committee Code</th>
            <th class="p-3 border text-left">Geo Name</th>
            <th class="p-3 border text-left">Geo Code</th>
            <th class="p-3 border text-left">Parent Geo</th>
            <th class="p-3 border text-left">Active</th>
            <th class="p-3 border text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="level in levels" :key="level.id" class="border-b hover:bg-gray-50">
            <td class="p-3">{{ level.level }}</td>
            <td class="p-3">
              <input 
                v-model="level.committee_name" 
                class="w-full border rounded px-2 py-1"
                :disabled="!editingId || editingId !== level.id"
              />
            </td>
            <td class="p-3">
              <input 
                v-model="level.committee_code" 
                class="w-full border rounded px-2 py-1"
                :disabled="!editingId || editingId !== level.id"
              />
            </td>
            <td class="p-3">
              <input 
                v-model="level.geo_name" 
                class="w-full border rounded px-2 py-1"
                :disabled="!editingId || editingId !== level.id"
              />
            </td>
            <td class="p-3">
              <input 
                v-model="level.geo_code" 
                class="w-full border rounded px-2 py-1"
                :disabled="!editingId || editingId !== level.id"
              />
            </td>
            <td class="p-3">
              <select 
                v-model="level.geo_parent_code" 
                class="w-full border rounded px-2 py-1"
                :disabled="!editingId || editingId !== level.id"
              >
                <option value="">None (Root)</option>
                <option v-for="g in geoOptions" :key="g.code" :value="g.code">
                  {{ g.name }} ({{ g.code }})
                </option>
              </select>
            </td>
            <td class="p-3 text-center">
              <input 
                type="checkbox" 
                v-model="level.is_active" 
                class="w-5 h-5"
                :disabled="!editingId || editingId !== level.id"
              />
            </td>
            <td class="p-3">
              <button 
                v-if="editingId === level.id"
                @click="saveLevel(level)" 
                class="text-green-600 hover:text-green-800 mr-2"
              >
                Save
              </button>
              <button 
                v-if="editingId === level.id"
                @click="cancelEdit" 
                class="text-gray-600 hover:text-gray-800 mr-2"
              >
                Cancel
              </button>
              <button 
                v-if="editingId !== level.id"
                @click="editLevel(level)" 
                class="text-blue-600 hover:text-blue-800 mr-2"
              >
                Edit
              </button>
              <button 
                @click="deleteLevel(level.id)" 
                class="text-red-600 hover:text-red-800"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- New Level Row (when adding) -->
    <div v-if="newLevel" class="mt-4 p-4 border rounded bg-gray-50">
      <h3 class="font-bold mb-3">New Governance Level</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Level</label>
          <input type="number" v-model.number="newLevel.level" class="w-full border rounded px-2 py-1" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Committee Name</label>
          <input v-model="newLevel.committee_name" class="w-full border rounded px-2 py-1" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Committee Code</label>
          <input v-model="newLevel.committee_code" class="w-full border rounded px-2 py-1" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Geo Name</label>
          <input v-model="newLevel.geo_name" class="w-full border rounded px-2 py-1" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Geo Code</label>
          <input v-model="newLevel.geo_code" class="w-full border rounded px-2 py-1" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Parent Geo</label>
          <select v-model="newLevel.geo_parent_code" class="w-full border rounded px-2 py-1">
            <option value="">None (Root)</option>
            <option v-for="g in geoOptions" :key="g.code" :value="g.code">
              {{ g.name }} ({{ g.code }})
            </option>
          </select>
        </div>
      </div>
      <div class="mt-4 flex gap-2">
        <button @click="createLevel" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <button @click="cancelNew" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import type { GovernanceLevelDefinition } from '@/types/governance-level.types'

const levels = ref<GovernanceLevelDefinition[]>([])
const loading = ref(false)
const editingId = ref<number | null>(null)
const newLevel = ref<Partial<GovernanceLevelDefinition> | null>(null)

const geoOptions = computed(() => {
  return levels.value
    .filter(l => l.geo_code && l.geo_name)
    .map(l => ({
      code: l.geo_code,
      name: l.geo_name
    }))
})

const fetchLevels = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/governance-levels')
    levels.value = response.data
  } catch (error) {
    console.error('Failed to fetch levels:', error)
  } finally {
    loading.value = false
  }
}

const editLevel = (level: GovernanceLevelDefinition) => {
  editingId.value = level.id
}

const cancelEdit = () => {
  editingId.value = null
  fetchLevels() // Refresh to discard changes
}

const saveLevel = async (level: GovernanceLevelDefinition) => {
  try {
    await axios.put(`/api/admin/governance-levels/${level.id}`, level)
    editingId.value = null
    await fetchLevels()
  } catch (error) {
    console.error('Failed to save level:', error)
    alert('Error saving level')
  }
}

const deleteLevel = async (id: number) => {
  if (!confirm('Are you sure you want to delete this level?')) return
  try {
    await axios.delete(`/api/admin/governance-levels/${id}`)
    await fetchLevels()
  } catch (error) {
    console.error('Failed to delete level:', error)
    alert('Error deleting level')
  }
}

const addLevel = () => {
  newLevel.value = {
    level: levels.value.length,
    committee_name: '',
    committee_code: '',
    geo_name: '',
    geo_code: '',
    geo_parent_code: null,
    is_active: true,
    sort_order: 0,
  }
}

const cancelNew = () => {
  newLevel.value = null
}

const createLevel = async () => {
  if (!newLevel.value) return
  
  try {
    await axios.post('/api/admin/governance-levels', newLevel.value)
    newLevel.value = null
    await fetchLevels()
  } catch (error) {
    console.error('Failed to create level:', error)
    alert('Error creating level')
  }
}

onMounted(() => {
  fetchLevels()
})
</script>
```

---

## 🚀 Next Steps After Step 2

```yaml
After Step 2.3 (Governance Levels Admin UI) is complete:

Step 3: Define Geographic Levels (uses same table - no new code)
Step 4: Import Geographic Units (UNSD M49 import)
Step 5: Committee Management (CRUD forms)
Step 6: Link Committees to Geography
Step 7: Authority Delegation (forms)

Then Phase 8B: User Frontend Hierarchy Viewer
```

**Proceed to create the migration and model?** 🚀 
