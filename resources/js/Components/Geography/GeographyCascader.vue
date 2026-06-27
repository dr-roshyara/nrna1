<template>
  <div class="space-y-4">
    <!-- Render levels by type -->
    <div v-if="config?.levels" class="space-y-3">
      <template v-for="level in config.levels" :key="level.index">
        <!-- Static Level (info only) -->
        <div v-if="level.type === 'static'" class="space-y-2">
          <div class="block text-sm font-medium text-gray-700 p-2 bg-gray-100 rounded">
            📍 {{ level.label }}
          </div>
        </div>

        <!-- Region Level -->
        <div v-else-if="level.type === 'region'" class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">{{ level.label }}</label>
          <select
            v-model="selections.region"
            @change="handleRegionChange"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
            style="background-color: white; color: #111; font-color: #111;"
          >
            <option value="" style="color: #111; background: white;">-- Select {{ level.label }} --</option>
            <option v-for="region in store.getRegions" :key="region.code" :value="region.code" style="color: #111; background: white;">
              {{ region.name }}
            </option>
          </select>
        </div>

        <!-- Country Level -->
        <div v-else-if="level.type === 'country'" class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">{{ level.label }}</label>
          <select
            v-model="selections.country"
            @change="handleCountryChange"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
            :disabled="!selections.region && hasRegionLevel"
            style="background-color: #f3f4f6; color: #111; font-color: #111;"
          >
            <option value="" style="color: #111; background: #f3f4f6;">-- Select {{ level.label }} --</option>
            <option
              v-for="country in getCountriesForSelectedRegion()"
              :key="country.code"
              :value="country.code"
              style="color: #111; background: #f3f4f6;"
            >
              {{ country.name_en || country.name }}
            </option>
          </select>
          <div v-if="!selections.region && hasRegionLevel" class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
            Please select a {{ getRegionLevelLabel() }} first
          </div>
        </div>

        <!-- Geo Unit Level (existing flat geography logic) -->
        <div v-else-if="level.type === 'geo_unit'" class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">{{ level.label }}</label>
          <select
            v-model="geoSelections[level.index]"
            @change="handleGeoLevelChange(level.index, level.db_level)"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
            :disabled="!isGeoLevelEnabled(level.index)"
            style="background-color: white; color: #111; font-color: #111;"
          >
            <option value="" style="color: #111; background: white;">-- Select {{ level.label }} --</option>
            <option v-for="node in getGeoOptions(level.index, level.db_level)" :key="node.id" :value="node.id" style="color: #111; background: white;">
              {{ node.name_local }}
            </option>
          </select>
          <div v-if="!isGeoLevelEnabled(level.index)" class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
            Please select {{ getPreviousGeoLevelLabel(level.index) }} first
          </div>
        </div>
      </template>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center text-gray-500 text-sm py-4 animate-pulse">
      ⏳ Loading geography data...
    </div>

    <!-- Region/Country Loading -->
    <div v-if="store.regionsLoading" class="text-sm text-gray-400 py-2 animate-pulse">
      📍 Loading regions...
    </div>
    <div v-if="selections.region && store.countriesLoading[selections.region]" class="text-sm text-gray-400 py-2 animate-pulse">
      🌍 Loading countries...
    </div>

    <!-- Error State -->
    <div v-if="error" class="p-3 bg-red-50 border border-red-200 rounded-md text-red-700 text-sm">
      ⚠️ {{ error }}
      <button
        @click="retryLoad"
        class="block mt-2 text-red-600 underline hover:text-red-700"
      >
        Retry
      </button>
    </div>

    <!-- Region/Country Error -->
    <div v-if="selections.region && store.countriesError[selections.region]" class="p-3 bg-amber-50 border border-amber-200 rounded-md text-amber-700 text-sm">
      ⚠️ Failed to load countries: {{ store.countriesError[selections.region] }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { fetchCascaderConfig, type CascaderConfig } from '@/services/geography'
import { useGeographyStore } from '@/stores/geographyStore'

const props = defineProps({
  organisationSlug: {
    type: String,
    required: true,
  },
  modelValue: {
    type: [String, Object],
    default: null,
  },
})

const emit = defineEmits(['update:modelValue'])

const config = ref<CascaderConfig | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const store = useGeographyStore()

// Track selections per geo_unit level index
const geoSelections = ref<Record<number, any>>({})

// New selections object format: { region?, country?, geo: [] }
const selections = ref({
  region: null as string | null,
  country: null as string | null,
  geo: [] as number[],
})

// Check if config has region or country levels
const hasRegionLevel = computed(() => {
  return config.value?.levels?.some(l => l.type === 'region') ?? false
})

const hasCountryLevel = computed(() => {
  return config.value?.levels?.some(l => l.type === 'country') ?? false
})

const hasGeoLevel = computed(() => {
  return config.value?.levels?.some(l => l.type === 'geo_unit') ?? false
})

const getRegionLevelLabel = (): string => {
  return config.value?.levels?.find(l => l.type === 'region')?.label || 'Region'
}

const geoUnitLevels = computed(() => {
  return config.value?.levels?.filter(l => l.type === 'geo_unit') ?? []
})

const getCountriesForSelectedRegion = () => {
  if (!selections.value.region) return []
  return store.getCountriesForRegion(selections.value.region) || []
}

const isGeoLevelEnabled = (levelIndex: number): boolean => {
  // Guard: config not loaded yet
  if (!geoUnitLevels.value.length) return false

  const sortedGeoLevels = [...geoUnitLevels.value].sort((a, b) => a.index - b.index)
  const firstGeoLevel = sortedGeoLevels[0]?.index

  // First geo_unit level needs country selected (if country level exists) or can always be enabled
  if (levelIndex === firstGeoLevel) {
    return !hasCountryLevel.value || !!selections.value.country
  }

  // Subsequent geo_unit levels need previous geo_unit selection
  const geoLevelPosition = sortedGeoLevels.findIndex(l => l.index === levelIndex)
  if (geoLevelPosition > 0) {
    const previousGeoLevel = sortedGeoLevels[geoLevelPosition - 1]
    return !!geoSelections.value[previousGeoLevel.index]
  }

  return false
}

const getPreviousGeoLevelLabel = (levelIndex: number): string => {
  const sortedDescending = [...geoUnitLevels.value].sort((a, b) => b.index - a.index)
  const previousGeoLevel = sortedDescending.find(l => l.index < levelIndex)
  return previousGeoLevel?.label || 'the previous level'
}

const getGeoOptions = (levelIndex: number, dbLevel: number) => {
  if (!selections.value.country) return []

  // First geo_unit level gets options from store
  const firstGeoLevel = geoUnitLevels.value[0]?.index
  if (levelIndex === firstGeoLevel) {
    return store.getLevelNodes(selections.value.country, dbLevel) || []
  }

  // Subsequent levels get children of immediate predecessor geo_unit selection
  const sortedLevels = [...geoUnitLevels.value].sort((a, b) => b.index - a.index)
  const previousGeoLevel = sortedLevels.find(l => l.index < levelIndex)

  if (previousGeoLevel && geoSelections.value[previousGeoLevel.index]) {
    return store.getChildrenOf(selections.value.country, geoSelections.value[previousGeoLevel.index]) || []
  }

  return []
}

const handleRegionChange = async () => {
  try {
    selections.value.country = null
    selections.value.geo = []
    geoSelections.value = {}

    emitValue()

    if (selections.value.region) {
      await store.loadCountriesForRegion(selections.value.region)
    }
  } catch (err) {
    error.value = 'Failed to load region data: ' + err.message
  }
}

const handleCountryChange = async () => {
  // Reset geo when country changes
  selections.value.geo = []
  geoSelections.value = {}

  emitValue()

  // Load flat geography data for the selected country
  if (selections.value.country) {
    try {
      loading.value = true
      await store.fetchFlat(selections.value.country)
    } catch (err: any) {
      error.value = err.message || 'Failed to load geography data'
    } finally {
      loading.value = false
    }
  }
}

const handleGeoLevelChange = (levelIndex: number, dbLevel: number) => {
  // Clear deeper levels when a level changes
  const sortedLevels = [...geoUnitLevels.value].sort((a, b) => a.index - b.index)
  const position = sortedLevels.findIndex(l => l.index === levelIndex)

  // Remove selections from deeper levels (immutable update)
  const newGeoSelections = { ...geoSelections.value }
  for (let i = position + 1; i < sortedLevels.length; i++) {
    delete newGeoSelections[sortedLevels[i].index]
  }
  geoSelections.value = newGeoSelections

  // Update geo array from geoSelections
  selections.value.geo = sortedLevels.map(level => geoSelections.value[level.index] || 0).filter(v => v)

  emitValue()
}

const emitValue = () => {
  emit('update:modelValue', {
    region: selections.value.region,
    country: selections.value.country,
    geo: selections.value.geo.filter(v => v), // Remove falsy values
  })
}

const retryLoad = async () => {
  await loadConfig()
}

const loadConfig = async () => {
  loading.value = true
  error.value = null

  try {
    // Reset store on each load to prevent stale cache
    store.reset()

    // Step 1: Load configuration
    config.value = await fetchCascaderConfig(props.organisationSlug)

    // Step 2: Auto-select country for single-country scope (before dependent operations)
    if (!config.value.showCountrySelector && config.value.initialCountryCode && !selections.value.country) {
      selections.value.country = config.value.initialCountryCode
    }

    // Step 3: Load regions if config has region levels
    if (config.value.levels?.some(l => l.type === 'region')) {
      await store.loadRegions()

      // Step 3a: Load countries for selected region (if region is selected)
      if (selections.value.region) {
        await store.loadCountriesForRegion(selections.value.region)
      }
    }

    // Step 4: Load flat geography if country is selected
    if (config.value.levels?.some(l => l.type === 'geo_unit')) {
      const countryToLoad = selections.value.country || config.value.initialCountryCode
      if (countryToLoad) {
        await store.fetchFlat(countryToLoad)
      }
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to load geography configuration'
  } finally {
    loading.value = false
  }
}

// Reset store when organisation changes (prevents stale data from previous org)
watch(() => props.organisationSlug, () => {
  store.reset()
}, { immediate: false })

onMounted(async () => {
  await loadConfig()
})

// Watch modelValue to populate selections from parent (new format only)
watch(() => props.modelValue, (newVal) => {
  if (newVal && typeof newVal === 'object') {
    // New format: { region, country, geo: [] }
    // Legacy string format handled server-side via GeoReferenceParser
    selections.value = {
      region: newVal.region || null,
      country: newVal.country || null,
      geo: newVal.geo || [],
    }

    // Initialize geoSelections from geo array
    if (newVal.geo && Array.isArray(newVal.geo) && geoUnitLevels.value.length) {
      const sortedLevels = [...geoUnitLevels.value].sort((a, b) => a.index - b.index)
      const newGeoSelections: Record<number, any> = {}
      for (let i = 0; i < sortedLevels.length && i < newVal.geo.length; i++) {
        if (newVal.geo[i]) {
          newGeoSelections[sortedLevels[i].index] = newVal.geo[i]
        }
      }
      geoSelections.value = newGeoSelections
    }
  }
})

// Watch country changes to auto-load geography (handles programmatic selection)
watch(() => selections.value.country, async (newCountry, oldCountry) => {
  if (newCountry && newCountry !== oldCountry && newCountry !== (oldCountry || null)) {
    await store.fetchFlat(newCountry)
  }
})
</script>
