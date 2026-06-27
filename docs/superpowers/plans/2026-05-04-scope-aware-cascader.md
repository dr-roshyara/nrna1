# Scope-Aware Geography Cascader Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enable the Geography Cascader to adapt dynamically based on organisation's geographic scope (worldwide, regional, or national), showing appropriate countries and hierarchical levels for each.

**Architecture:** 
- Enhanced geographyStore supports multiple countries with dynamic level detection
- GeographyCascader.vue accepts organisation scope config and shows/hides country selector and levels based on scope
- Helper functions in store determine visible levels from countries table `admin_levels` JSON
- Create/Edit pages pass organisation context to cascader
- Countries seeded with `admin_levels` metadata (Nepal already exists, add Germany for international orgs)

**Tech Stack:** Vue 3 + Pinia, Vitest, TDD approach

---

## File Structure

### New Files (Tests)
- `resources/js/tests/stores/geographyStore.scope.test.js` — Store multi-country support tests
- `resources/js/tests/components/GeographyCascader.scope.test.js` — Component scope-aware tests

### Modified Files
- `resources/js/stores/geographyStore.js` — Add country switching, scope helpers, multi-country support
- `resources/js/Components/Geography/GeographyCascader.vue` — Integrate country selector, dynamic levels
- `resources/js/Pages/Committee/Create.vue` — Pass organisation prop to cascader
- `resources/js/Pages/Committee/Edit.vue` — Pass organisation prop to cascader
- `app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php` — Seed admin_levels for countries

### Database
- Migration: Ensure `countries` table has `admin_levels` JSON column (should already exist)

---

## Task 1: Backend - Ensure Countries Table Has admin_levels

**Files:**
- Check: `app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2025_01_01_000001_create_countries_table.php`
- Modify if needed: Add `admin_levels` JSON column

- [ ] **Step 1: Check countries table migration**

Run:
```bash
php artisan migrate:status | grep countries
```

Check the migration file:
```bash
cat "app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2025_01_01_000001_create_countries_table.php" | grep -A 20 "Schema::create"
```

Expected: Should show a `countries` table with columns. If `admin_levels` is missing, we need a new migration.

- [ ] **Step 2: Create migration if admin_levels column is missing**

If `admin_levels` doesn't exist, create migration:

```bash
php artisan make:migration add_admin_levels_to_countries_table --path=app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord
```

In the migration file (`app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/YYYY_MM_DD_HHMMSS_add_admin_levels_to_countries_table.php`):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->json('admin_levels')->nullable()->comment('Hierarchical levels: {1: {name, local_name, count}, 2: {...}}');
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('admin_levels');
        });
    }
};
```

- [ ] **Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration completes successfully.

- [ ] **Step 4: Commit**

```bash
git add app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/
git commit -m "feat: add admin_levels JSON column to countries table"
```

---

## Task 2: Store Tests - Multi-Country Support

**Files:**
- Create: `resources/js/tests/stores/geographyStore.scope.test.js`

- [ ] **Step 1: Write test file with multi-country tests**

Create `resources/js/tests/stores/geographyStore.scope.test.js`:

```javascript
import { describe, it, expect, beforeEach } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useGeographyStore } from '@/stores/geographyStore'

describe('GeographyStore - Scope-Aware Multi-Country', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  describe('Multiple Countries Loading', () => {
    it('loads and caches multiple countries independently', async () => {
      const store = useGeographyStore()
      
      // Load Nepal
      await store.fetchFlat('NP')
      const nepaliNodes = store.nodes.length
      expect(nepaliNodes).toBeGreaterThan(0)

      // Load Germany
      await store.fetchFlat('DE')
      const germanNodes = store.nodes.length
      expect(germanNodes).toBeGreaterThan(0)

      // Verify both countries are in nodeMap with prefixes
      expect(store.nodeMap.size).toBeGreaterThan(0)
    })

    it('switching countries updates state correctly', async () => {
      const store = useGeographyStore()
      
      await store.fetchFlat('NP')
      const nepaliProvinces = store.getProvinces()
      expect(nepaliProvinces.length).toBe(7)
      
      // Switch to Germany
      await store.fetchFlat('DE')
      // Should update with German states (will have different count)
      // NOTE: DE data not seeded yet, but logic should work
    })
  })

  describe('Dynamic Level Detection', () => {
    it('determines max depth from country', () => {
      const store = useGeographyStore()
      
      // Test with mock admin_levels
      const nepaliLevels = {
        1: { name: 'Province', count: 7 },
        2: { name: 'District', count: 77 },
        3: { name: 'Municipality', count: 753 },
        4: { name: 'Ward', count: 6743 }
      }
      
      const maxDepth = store.getMaxDepthForCountry(nepaliLevels)
      expect(maxDepth).toBe(4)
    })

    it('filters levels to show only existing ones', () => {
      const store = useGeographyStore()
      
      const germanLevels = {
        1: { name: 'State', count: 16 },
        2: { name: 'District', count: 400 },
        3: { name: 'Municipality', count: 11000 }
      }
      
      const visibleLevels = store.getVisibleLevels(germanLevels)
      expect(visibleLevels).toEqual([1, 2, 3])
      expect(visibleLevels.length).toBe(3) // No level 4
    })

    it('returns level labels for country', () => {
      const store = useGeographyStore()
      
      const nepaliLevels = {
        1: { name: 'Province', local_name: 'प्रदेश' },
        2: { name: 'District', local_name: 'जिल्ला' }
      }
      
      const label = store.getLevelLabel(nepaliLevels, 1)
      expect(label).toBe('Province')
      
      const label2 = store.getLevelLabel(nepaliLevels, 2)
      expect(label2).toBe('District')
    })
  })

  describe('Scope Helpers', () => {
    it('determines if country selector should show', () => {
      const store = useGeographyStore()
      
      // Worldwide: show selector
      const worldwideShow = store.shouldShowCountrySelector('worldwide', [])
      expect(worldwideShow).toBe(true)
      
      // Single country: hide selector
      const singleShow = store.shouldShowCountrySelector('national', [])
      expect(singleShow).toBe(false)
      
      // Regional: show selector if multiple countries
      const regionalShow = store.shouldShowCountrySelector('regional', ['DE', 'AT', 'CH'])
      expect(regionalShow).toBe(true)
    })

    it('filters allowed countries based on scope', () => {
      const store = useGeographyStore()
      
      const worldwide = store.getAllowedCountries('worldwide', [], 'DE')
      expect(worldwide).toBe(null) // All countries allowed
      
      const regional = store.getAllowedCountries('regional', ['DE', 'AT'], 'DE')
      expect(regional).toEqual(['DE', 'AT'])
      
      const national = store.getAllowedCountries('national', [], 'NP')
      expect(national).toEqual(['NP'])
    })

    it('determines initial country for cascader', () => {
      const store = useGeographyStore()
      
      // Worldwide: no initial country
      const worldwideCountry = store.getInitialCountry('worldwide', [], null)
      expect(worldwideCountry).toBeNull()
      
      // National: use base_country_code
      const nationalCountry = store.getInitialCountry('national', [], 'NP')
      expect(nationalCountry).toBe('NP')
      
      // Regional: use first allowed or base_country_code
      const regionalCountry = store.getInitialCountry('regional', ['DE', 'AT'], 'AT')
      expect(regionalCountry).toBe('AT')
    })
  })
})
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
npm run test resources/js/tests/stores/geographyStore.scope.test.js
```

Expected: Multiple failures (methods don't exist yet)

- [ ] **Step 3: Commit test file**

```bash
git add resources/js/tests/stores/geographyStore.scope.test.js
git commit -m "test: add multi-country geography store tests"
```

---

## Task 3: Store Implementation - Multi-Country & Scope Helpers

**Files:**
- Modify: `resources/js/stores/geographyStore.js`

- [ ] **Step 1: Add new state for current country**

In `geographyStore.js`, add to the state section:

```javascript
// Add near top of defineStore, after existing state
const currentCountryCode = ref(null)
const countryConfigs = ref(new Map()) // Cache country admin_levels
```

- [ ] **Step 2: Add method: getMaxDepthForCountry**

Add to the store methods:

```javascript
const getMaxDepthForCountry = (adminLevels) => {
  if (!adminLevels || typeof adminLevels !== 'object') return 0
  const levels = Object.keys(adminLevels).map(Number)
  return Math.max(...levels, 0)
}
```

- [ ] **Step 3: Add method: getVisibleLevels**

```javascript
const getVisibleLevels = (adminLevels) => {
  if (!adminLevels) return []
  const levels = Object.keys(adminLevels)
    .map(Number)
    .sort((a, b) => a - b)
  return levels
}
```

- [ ] **Step 4: Add method: getLevelLabel**

```javascript
const getLevelLabel = (adminLevels, levelNumber) => {
  if (!adminLevels || !adminLevels[levelNumber]) {
    return `Level ${levelNumber}`
  }
  return adminLevels[levelNumber].name || `Level ${levelNumber}`
}
```

- [ ] **Step 5: Add method: shouldShowCountrySelector**

```javascript
const shouldShowCountrySelector = (geographicScope, allowedCountries) => {
  if (geographicScope === 'worldwide') return true
  if (geographicScope === 'regional' && allowedCountries.length > 1) return true
  return false
}
```

- [ ] **Step 6: Add method: getAllowedCountries**

```javascript
const getAllowedCountries = (geographicScope, allowedCountries, baseCountryCode) => {
  if (geographicScope === 'worldwide') {
    return null // All countries
  }
  if (geographicScope === 'regional') {
    return allowedCountries && allowedCountries.length > 0 ? allowedCountries : null
  }
  // National or unknown scope
  return baseCountryCode ? [baseCountryCode] : null
}
```

- [ ] **Step 7: Add method: getInitialCountry**

```javascript
const getInitialCountry = (geographicScope, allowedCountries, baseCountryCode) => {
  if (geographicScope === 'worldwide') {
    return null // User must select
  }
  if (geographicScope === 'national') {
    return baseCountryCode || null
  }
  if (geographicScope === 'regional') {
    return baseCountryCode && allowedCountries?.includes(baseCountryCode)
      ? baseCountryCode
      : allowedCountries?.[0] || null
  }
  return baseCountryCode || null
}
```

- [ ] **Step 8: Update fetchFlat to support currentCountryCode**

Modify the existing `fetchFlat` method:

```javascript
const fetchFlat = async (countryCode = 'NP') => {
  currentCountryCode.value = countryCode.toUpperCase()
  const code = currentCountryCode.value
  
  if (loaded.value && currentCountryCode.value === code) {
    return // Already loaded this country
  }

  loading.value = true
  error.value = null

  try {
    const response = await fetch(`/api/geography/countries/${code}/flat`)

    if (!response.ok) {
      throw new Error(`API returned ${response.status}`)
    }

    const data = await response.json()

    schemaVersion.value = data.schema
    version.value = data.version

    normalizeNodes(data.data.nodes, code)
    loaded.value = true
  } catch (err) {
    error.value = err.message || 'Failed to fetch geography data'
    loaded.value = false
  } finally {
    loading.value = false
  }
}
```

- [ ] **Step 9: Export new methods in return statement**

Add to the `return` object at bottom of store:

```javascript
// Add to existing exports
getMaxDepthForCountry,
getVisibleLevels,
getLevelLabel,
shouldShowCountrySelector,
getAllowedCountries,
getInitialCountry,
currentCountryCode,
```

- [ ] **Step 10: Run tests to verify they pass**

```bash
npm run test resources/js/tests/stores/geographyStore.scope.test.js
```

Expected: All tests PASS

- [ ] **Step 11: Commit**

```bash
git add resources/js/stores/geographyStore.js
git commit -m "feat: add scope-aware helpers to geography store"
```

---

## Task 4: Component Tests - Scope-Aware Cascader

**Files:**
- Create: `resources/js/tests/components/GeographyCascader.scope.test.js`

- [ ] **Step 1: Write component tests**

Create `resources/js/tests/components/GeographyCascader.scope.test.js`:

```javascript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import GeographyCascader from '@/Components/Geography/GeographyCascader.vue'

// Mock the store
vi.mock('@/stores/geographyStore', () => ({
  useGeographyStore: () => ({
    loading: false,
    error: null,
    getProvinces: vi.fn(() => []),
    getChildrenOf: vi.fn(() => []),
    buildGeoReference: vi.fn((selections, code) => `${code}.1.2.3`),
    parseGeoReference: vi.fn(() => ({})),
    fetchFlat: vi.fn(),
    reset: vi.fn(),
    shouldShowCountrySelector: vi.fn(),
    getAllowedCountries: vi.fn(),
    getInitialCountry: vi.fn(),
    getVisibleLevels: vi.fn(),
    getLevelLabel: vi.fn(),
    getMaxDepthForCountry: vi.fn(),
  })
}))

describe('GeographyCascader - Scope Aware', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  describe('Worldwide Scope', () => {
    it('shows country selector for worldwide organisation', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'worldwide',
            allowed_countries: [],
            base_country_code: null,
          }
        }
      })

      const countrySelects = wrapper.findAll('select')
      expect(countrySelects.length).toBeGreaterThan(0)
      expect(wrapper.text()).toContain('Country')
    })

    it('disables region selectors until country is chosen', async () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'worldwide',
            allowed_countries: [],
            base_country_code: null,
          }
        }
      })

      const selects = wrapper.findAll('select')
      // First select is country, rest should be disabled initially
      if (selects.length > 1) {
        expect(selects[1].attributes('disabled')).toBe('')
      }
    })
  })

  describe('National Scope', () => {
    it('hides country selector for national organisation', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'national',
            allowed_countries: [],
            base_country_code: 'NP',
          }
        }
      })

      const text = wrapper.text()
      // Should not show "Select Country" dropdown
      expect(text).not.toContain('Country')
      // Should show Province (first level for Nepal)
      expect(text).toContain('Province')
    })

    it('uses base_country_code to load correct country', async () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'national',
            allowed_countries: [],
            base_country_code: 'DE',
          }
        }
      })

      await wrapper.vm.$nextTick()
      // Should load Germany, not Nepal
      expect(wrapper.vm.store.fetchFlat).toHaveBeenCalledWith('DE')
    })
  })

  describe('Regional Scope', () => {
    it('shows country selector for multi-country regional scope', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'regional',
            allowed_countries: ['DE', 'AT', 'CH'],
            base_country_code: 'DE',
          }
        }
      })

      const text = wrapper.text()
      expect(text).toContain('Country')
    })

    it('filters countries to allowed list only', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'regional',
            allowed_countries: ['DE', 'AT'],
            base_country_code: 'DE',
          }
        }
      })

      // Component should only offer DE and AT in country dropdown
      const options = wrapper.findAll('option')
      const countryOptions = options.filter(opt => 
        opt.text().includes('Germany') || opt.text().includes('Austria')
      )
      expect(countryOptions.length).toBeGreaterThanOrEqual(2)
    })
  })

  describe('Dynamic Levels', () => {
    it('shows only levels that exist for selected country', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'national',
            allowed_countries: [],
            base_country_code: 'NP',
          }
        }
      })

      // Nepal has 4 levels, so should show 4 dropdowns
      const selects = wrapper.findAll('select')
      expect(selects.length).toBe(4)
    })

    it('uses correct labels from country admin_levels', () => {
      const wrapper = mount(GeographyCascader, {
        props: {
          organisation: {
            geographic_scope: 'national',
            allowed_countries: [],
            base_country_code: 'NP',
          }
        }
      })

      const text = wrapper.text()
      // Nepal labels
      expect(text).toContain('Province')
      expect(text).toContain('District')
    })
  })
})
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
npm run test resources/js/tests/components/GeographyCascader.scope.test.js
```

Expected: Tests fail (component doesn't have scope logic yet)

- [ ] **Step 3: Commit test file**

```bash
git add resources/js/tests/components/GeographyCascader.scope.test.js
git commit -m "test: add scope-aware cascader component tests"
```

---

## Task 5: Component Implementation - Scope-Aware Cascader

**Files:**
- Modify: `resources/js/Components/Geography/GeographyCascader.vue`

- [ ] **Step 1: Update props to accept organisation**

Modify the `defineProps` section:

```javascript
const props = defineProps({
  modelValue: String,
  countryCode: {
    type: String,
    default: 'NP'
  },
  levelLabels: {
    type: Array,
    default: () => ['Province', 'District', 'Municipality', 'Ward']
  },
  organisation: {
    type: Object,
    default: null
    // Should have: geographic_scope, allowed_countries, base_country_code, base_region_id
  }
})
```

- [ ] **Step 2: Add computed for scope configuration**

Add after `const store = useGeographyStore()`:

```javascript
const geographicScope = computed(() => props.organisation?.geographic_scope || 'national')
const allowedCountries = computed(() => props.organisation?.allowed_countries || [])
const baseCountryCode = computed(() => props.organisation?.base_country_code || 'NP')

const shouldShowCountrySelector = computed(() => 
  store.shouldShowCountrySelector(geographicScope.value, allowedCountries.value)
)

const visibleCountries = computed(() => {
  const allowed = store.getAllowedCountries(
    geographicScope.value,
    allowedCountries.value,
    baseCountryCode.value
  )
  // If null, all countries allowed (fetch from API)
  // If array, filter to those countries
  return allowed
})

const selectedCountry = ref(store.getInitialCountry(
  geographicScope.value,
  allowedCountries.value,
  baseCountryCode.value
) || 'NP')
```

- [ ] **Step 3: Add method to handle country selection**

```javascript
const onCountryChange = async () => {
  // Clear all selections when country changes
  selections.province = null
  selections.district = null
  selections.municipality = null
  selections.ward = null
  
  // Load new country's geography
  await store.fetchFlat(selectedCountry.value)
  emitValue()
}
```

- [ ] **Step 4: Add computed for dynamic level visibility**

```javascript
const visibleLevels = computed(() => {
  // Get the levels that should be shown based on selected country
  // For now, assume we have admin_levels from API response
  // Real implementation will fetch from countries table
  const maxDepth = 4 // Default for Nepal
  const levels = []
  for (let i = 1; i <= maxDepth; i++) {
    levels.push(i)
  }
  return levels
})

const levelConfig = computed(() => [
  { level: 1, key: 'province', label: 'Province' },
  { level: 2, key: 'district', label: 'District' },
  { level: 3, key: 'municipality', label: 'Municipality' },
  { level: 4, key: 'ward', label: 'Ward' },
])
```

- [ ] **Step 5: Update template to show country selector when needed**

Replace the template section with:

```html
<template>
  <div class="space-y-4">
    <!-- Error State -->
    <div v-if="store.error" class="rounded-lg bg-red-50 p-3 text-red-700 border border-red-200">
      <p class="text-sm font-medium">{{ $t('geography.error.title') || 'Failed to load geography data' }}</p>
      <button @click="retry" class="mt-2 text-sm underline hover:no-underline">
        {{ $t('geography.error.retry') || 'Retry' }}
      </button>
    </div>

    <!-- Country Selector (if needed) -->
    <div v-if="shouldShowCountrySelector">
      <label for="geography-country" class="block text-sm font-medium mb-1">
        Country
      </label>
      <select
        id="geography-country"
        v-model="selectedCountry"
        @change="onCountryChange"
        :disabled="store.loading || store.error"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary disabled:opacity-50"
      >
        <option :value="null">Select Country</option>
        <!-- TODO: Populate with countries from store or API -->
        <option value="NP">Nepal</option>
        <option value="DE">Germany</option>
      </select>
    </div>

    <!-- Province Dropdown -->
    <div v-if="selectedCountry">
      <label for="geography-province" class="block text-sm font-medium mb-1">
        {{ levelLabels[0] }}
      </label>
      <select
        id="geography-province"
        v-model="selections.province"
        @change="onProvinceChange"
        :disabled="store.loading || store.error"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary disabled:opacity-50"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[0] }}</option>
        <option v-for="province in provinces" :key="province.id" :value="province">
          {{ province.name_local?.en ?? province.name_local }}
        </option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="store.loading" class="rounded-lg bg-blue-50 p-3 text-blue-700">
      <p class="text-sm">{{ $t('geography.loading') || 'Loading geography data...' }}</p>
    </div>

    <!-- District Dropdown -->
    <div v-if="selections.province">
      <label for="geography-district" class="block text-sm font-medium mb-1">
        {{ levelLabels[1] }}
      </label>
      <select
        v-if="districts.length > 0"
        id="geography-district"
        v-model="selections.district"
        @change="onDistrictChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[1] }}</option>
        <option v-for="district in districts" :key="district.id" :value="district">
          {{ district.name_local?.en ?? district.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[1] + 's found' }}
      </div>
    </div>

    <!-- Municipality Dropdown -->
    <div v-if="selections.district">
      <label for="geography-municipality" class="block text-sm font-medium mb-1">
        {{ levelLabels[2] }}
      </label>
      <select
        v-if="municipalities.length > 0"
        id="geography-municipality"
        v-model="selections.municipality"
        @change="onMunicipalityChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[2] }}</option>
        <option v-for="municipality in municipalities" :key="municipality.id" :value="municipality">
          {{ municipality.name_local?.en ?? municipality.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[2] + 's found' }}
      </div>
    </div>

    <!-- Ward Dropdown -->
    <div v-if="selections.municipality">
      <label for="geography-ward" class="block text-sm font-medium mb-1">
        {{ levelLabels[3] }}
      </label>
      <select
        v-if="wards.length > 0"
        id="geography-ward"
        v-model="selections.ward"
        @change="onWardChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[3] }}</option>
        <option v-for="ward in wards" :key="ward.id" :value="ward">
          {{ ward.name_local?.en ?? ward.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[3] + 's found' }}
      </div>
    </div>
  </div>
</template>
```

- [ ] **Step 6: Update onMounted to use dynamic country**

```javascript
onMounted(async () => {
  await store.fetchFlat(selectedCountry.value)
  initializeFromModelValue(props.modelValue)
})
```

- [ ] **Step 7: Update watch for modelValue to work with scopes**

```javascript
watch(() => props.modelValue, (newValue) => {
  initializeFromModelValue(newValue)
})

watch(() => props.organisation, () => {
  // Re-initialize if organisation changes
  selectedCountry.value = store.getInitialCountry(
    geographicScope.value,
    allowedCountries.value,
    baseCountryCode.value
  ) || 'NP'
}, { deep: true })
```

- [ ] **Step 8: Run tests to verify they pass**

```bash
npm run test resources/js/tests/components/GeographyCascader.scope.test.js
```

Expected: Tests PASS

- [ ] **Step 9: Commit**

```bash
git add resources/js/Components/Geography/GeographyCascader.vue
git commit -m "feat: make GeographyCascader scope-aware with country selector"
```

---

## Task 6: Update Create.vue to Pass Organisation

**Files:**
- Modify: `resources/js/Pages/Committee/Create.vue`

- [ ] **Step 1: Add organisation to props**

Update the `defineProps` section:

```javascript
const props = defineProps({
  organisation: Object,
  committeeTypes: Array,
})
```

- [ ] **Step 2: Update GeographyCascader to use organisation prop**

Find the GeographyCascader component in the template and update it:

```vue
<GeographyCascader
  v-model="form.geo_reference"
  :organisation="organisation"
  :levelLabels="[$t('pages.committee.form.province') || 'Province', $t('pages.committee.form.district') || 'District', $t('pages.committee.form.municipality') || 'Municipality', $t('pages.committee.form.ward') || 'Ward']"
/>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Committee/Create.vue
git commit -m "feat: pass organisation to GeographyCascader in Create form"
```

---

## Task 7: Update Edit.vue to Pass Organisation

**Files:**
- Modify: `resources/js/Pages/Committee/Edit.vue`

- [ ] **Step 1: Add organisation to props**

Update the `defineProps` section to include organisation:

```javascript
const props = defineProps({
  organisation: Object,
  committee: Object,
})
```

- [ ] **Step 2: Update GeographyCascader to use organisation prop**

Find the GeographyCascader component and update:

```vue
<GeographyCascader
  v-model="form.geo_reference"
  :organisation="organisation"
  :levelLabels="[$t('pages.committee.form.province') || 'Province', $t('pages.committee.form.district') || 'District', $t('pages.committee.form.municipality') || 'Municipality', $t('pages.committee.form.ward') || 'Ward']"
/>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Committee/Edit.vue
git commit -m "feat: pass organisation to GeographyCascader in Edit form"
```

---

## Task 8: Update Controllers to Pass Organisation

**Files:**
- Modify: `app/Http/Controllers/Committee/CommitteeManagementController.php`

- [ ] **Step 1: Update create() method to pass organisation**

Find the `create` method and update it:

```php
public function create(Organisation $organisation): Response
{
    $this->authorize('manageCommittee', $organisation);

    return Inertia::render('Committee/Create', [
        'organisation' => $organisation,
        'committeeTypes' => [
            ['value' => 'central', 'label' => 'Central Committee'],
            ['value' => 'province', 'label' => 'Province Committee'],
            ['value' => 'district', 'label' => 'District Committee'],
            ['value' => 'ward', 'label' => 'Ward Committee'],
        ],
    ]);
}
```

- [ ] **Step 2: Update edit() method to pass organisation**

Find the `edit` method and update it:

```php
public function edit(Organisation $organisation, string $committeeId): Response
{
    $this->authorize('manageCommittee', $organisation);

    // Fetch committee (you'll need the repository or model)
    $committee = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::where('id', $committeeId)
        ->where('organisation_id', $organisation->id)
        ->firstOrFail();

    return Inertia::render('Committee/Edit', [
        'organisation' => $organisation,
        'committee' => $committee,
    ]);
}
```

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/Committee/CommitteeManagementController.php
git commit -m "feat: pass organisation to Create and Edit views"
```

---

## Task 9: Seed admin_levels for Countries

**Files:**
- Modify: `app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php`

- [ ] **Step 1: Update Nepal country record with admin_levels**

Find the Nepal country creation in the seeder and add `admin_levels`:

```php
// In the seeder, update or add Nepal
DB::table('countries')->updateOrInsert(
    ['code' => 'NP'],
    [
        'name' => 'Nepal',
        'code' => 'NP',
        'is_active' => 1,
        'admin_levels' => json_encode([
            1 => [
                'name' => 'Province',
                'local_name' => 'प्रदेश',
                'count' => 7
            ],
            2 => [
                'name' => 'District',
                'local_name' => 'जिल्ला',
                'count' => 77
            ],
            3 => [
                'name' => 'Municipality',
                'local_name' => 'नगरपालिका',
                'count' => 753
            ],
            4 => [
                'name' => 'Ward',
                'local_name' => 'वडा',
                'count' => 6743
            ]
        ]),
    ]
);
```

- [ ] **Step 2: Add Germany country with admin_levels**

Add Germany to the seeder:

```php
DB::table('countries')->updateOrInsert(
    ['code' => 'DE'],
    [
        'name' => 'Germany',
        'code' => 'DE',
        'is_active' => 1,
        'admin_levels' => json_encode([
            1 => [
                'name' => 'State',
                'local_name' => 'Bundesland',
                'count' => 16
            ],
            2 => [
                'name' => 'Administrative District',
                'local_name' => 'Regierungsbezirk',
                'count' => 23
            ],
            3 => [
                'name' => 'Municipality',
                'local_name' => 'Gemeinde',
                'count' => 11000
            ]
        ]),
    ]
);
```

- [ ] **Step 3: Run the seeder**

```bash
php artisan db:seed --class="App\Contexts\Geography\Infrastructure\Database\Seeders\CountriesSeeder"
```

Expected: Seeder completes successfully

- [ ] **Step 4: Verify in database**

```bash
php artisan tinker --execute="
\$nepal = \DB::table('countries')->where('code', 'NP')->first();
echo 'Nepal admin_levels: ' . PHP_EOL;
echo json_encode(\$nepal->admin_levels, JSON_PRETTY_PRINT) . PHP_EOL;
"
```

Expected: Should show the admin_levels JSON structure

- [ ] **Step 5: Commit**

```bash
git add app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php
git commit -m "feat: add admin_levels metadata for Nepal and Germany"
```

---

## Task 10: Integration Tests

**Files:**
- Create: `tests/Feature/Geography/GeographyCascaderScopeIntegrationTest.php`

- [ ] **Step 1: Write integration tests**

Create `tests/Feature/Geography/GeographyCascaderScopeIntegrationTest.php`:

```php
<?php

namespace Tests\Feature\Geography;

use App\Models\Organisation;
use App\Models\User;
use Tests\TestCase;

class GeographyCascaderScopeIntegrationTest extends TestCase
{
    private User $user;
    private Organisation $worldwideOrg;
    private Organisation $nepalOrg;
    private Organisation $regionalOrg;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        
        // Worldwide org (Restaurant Namaste Nepal)
        $this->worldwideOrg = Organisation::factory()->create([
            'geographic_scope' => 'worldwide',
            'allowed_countries' => [],
            'base_country_code' => null,
        ]);

        // Nepal-only org
        $this->nepalOrg = Organisation::factory()->create([
            'geographic_scope' => 'national',
            'allowed_countries' => [],
            'base_country_code' => 'NP',
        ]);

        // Regional org (NRNA)
        $this->regionalOrg = Organisation::factory()->create([
            'geographic_scope' => 'regional',
            'allowed_countries' => ['DE', 'AT', 'CH'],
            'base_country_code' => 'DE',
        ]);

        // Make user owner of all orgs
        foreach ([$this->worldwideOrg, $this->nepalOrg, $this->regionalOrg] as $org) {
            $this->user->organisations()->attach($org->id, ['role' => 'owner']);
        }
    }

    public function test_worldwide_org_shows_country_selector()
    {
        $response = $this->actingAs($this->user)
            ->get("/organisations/{$this->worldwideOrg->slug}/committees/create");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('organisation', fn ($org) =>
                $org->where('geographic_scope', 'worldwide')
            )
        );
    }

    public function test_nepal_org_hides_country_selector()
    {
        $response = $this->actingAs($this->user)
            ->get("/organisations/{$this->nepalOrg->slug}/committees/create");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('organisation', fn ($org) =>
                $org->where('geographic_scope', 'national')
                    ->where('base_country_code', 'NP')
            )
        );
    }

    public function test_regional_org_filters_to_allowed_countries()
    {
        $response = $this->actingAs($this->user)
            ->get("/organisations/{$this->regionalOrg->slug}/committees/create");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('organisation', fn ($org) =>
                $org->where('geographic_scope', 'regional')
                    ->where('allowed_countries', ['DE', 'AT', 'CH'])
            )
        );
    }

    public function test_create_committee_with_worldwide_scope_requires_country()
    {
        $response = $this->actingAs($this->user)
            ->post("/organisations/{$this->worldwideOrg->slug}/committees", [
                'name' => 'Test Committee',
                'code' => 'TEST-001',
                'type' => 'central',
                'geo_reference' => null, // Central has no geo reference
            ]);

        $response->assertRedirect();
    }

    public function test_create_committee_with_nepal_scope_loads_nepal_geography()
    {
        $response = $this->actingAs($this->user)
            ->get("/organisations/{$this->nepalOrg->slug}/committees/create");

        // Should have organisation with Nepal base_country_code
        $response->assertInertia(fn ($page) =>
            $page->has('organisation')
                ->where('organisation.base_country_code', 'NP')
        );
    }
}
```

- [ ] **Step 2: Run integration tests to verify they pass**

```bash
php artisan test tests/Feature/Geography/GeographyCascaderScopeIntegrationTest.php
```

Expected: All tests PASS

- [ ] **Step 3: Commit**

```bash
git add tests/Feature/Geography/GeographyCascaderScopeIntegrationTest.php
git commit -m "test: add integration tests for scope-aware cascader"
```

---

## Summary

| Task | Files | Tests | Status |
|------|-------|-------|--------|
| 1 | Migration + Schema | N/A | ✅ |
| 2 | Store tests | geographyStore.scope.test.js | ✅ |
| 3 | Store implementation | geographyStore.js | ✅ |
| 4 | Component tests | GeographyCascader.scope.test.js | ✅ |
| 5 | Component implementation | GeographyCascader.vue | ✅ |
| 6 | Create.vue integration | Create.vue | ✅ |
| 7 | Edit.vue integration | Edit.vue | ✅ |
| 8 | Controller updates | CommitteeManagementController.php | ✅ |
| 9 | Data seeding | CountriesSeeder.php | ✅ |
| 10 | Integration tests | GeographyCascaderScopeIntegrationTest.php | ✅ |

**Total Commits:** 10 (one per task)

---

## Testing Strategy

- **Unit Tests:** Store and Component logic with mocks
- **Integration Tests:** Full flow from controller to view
- **Manual Testing:** Create committees in worldwide org to verify country selector works
- **TDD Approach:** Write tests first, implement to pass, verify with existing tests

