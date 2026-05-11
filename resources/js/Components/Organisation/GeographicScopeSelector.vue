<template>
  <div class="border-t border-slate-200 pt-6 space-y-5">

    <!-- Immutability warning -->
    <div class="flex items-start gap-2.5 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3">
      <LockClosedIcon class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" aria-hidden="true" />
      <p class="text-sm text-amber-800">
        <strong>Permanent decision:</strong> Committee structure and geographic scope cannot be changed after creation.
      </p>
    </div>

    <!-- Committee Structure -->
    <fieldset>
      <legend class="text-sm font-semibold text-slate-900 mb-3">
        Committee Structure <span class="text-danger-500" aria-hidden="true">*</span>
      </legend>
      <div class="space-y-2">
        <label
          v-for="opt in structureOptions"
          :key="opt.value"
          class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer transition-colors"
          :class="form.committee_structure === opt.value
            ? 'border-purple-400 bg-purple-50'
            : 'border-slate-200 hover:border-slate-300 bg-white'"
        >
          <input
            type="radio"
            :value="opt.value"
            v-model="form.committee_structure"
            class="mt-0.5 border-slate-300 text-purple-600 focus:ring-purple-500"
          />
          <div>
            <span class="block text-sm font-medium text-slate-900">{{ opt.label }}</span>
            <span class="block text-xs text-slate-500 mt-0.5">{{ opt.description }}</span>
          </div>
        </label>
      </div>
      <p v-if="errors?.committee_structure" role="alert" class="mt-1 text-xs text-danger-600">
        {{ errors.committee_structure }}
      </p>
    </fieldset>

    <!-- Geographic Scope — only when geographical -->
    <Transition
      enter-active-class="transition-all duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"
    >
      <div v-if="form.committee_structure === 'geographical'" class="pl-5 border-l-2 border-purple-200 space-y-4">

        <!-- Scope dropdown -->
        <div>
          <label for="geo-scope" class="block text-sm font-medium text-slate-700 mb-1">
            Geographic Scope <span class="text-danger-500" aria-hidden="true">*</span>
          </label>
          <select id="geo-scope" v-model="form.geographic_scope" :class="inputClass('geographic_scope')">
            <option value="">Select scope…</option>
            <option v-for="s in scopeOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
          </select>
          <p v-if="errors?.geographic_scope" role="alert" class="mt-1 text-xs text-danger-600">
            {{ errors.geographic_scope }}
          </p>
        </div>

        <!-- Worldwide: no extra fields needed -->
        <div v-if="form.geographic_scope === 'worldwide'" class="text-sm text-slate-500 italic">
          All countries included. Members from any country can join.
        </div>

        <!-- Multi-Country: country checkboxes -->
        <div v-if="form.geographic_scope === 'multi_country'">
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Allowed Countries <span class="text-danger-500" aria-hidden="true">*</span>
            <span class="text-slate-400 font-normal">(select 2 or more)</span>
          </label>

          <div v-if="loadingCountries" class="space-y-1.5">
            <div v-for="i in 4" :key="i" class="h-9 bg-slate-100 animate-pulse rounded-lg" />
          </div>
          <div v-else class="border border-slate-200 rounded-lg max-h-52 overflow-y-auto bg-white">
            <label
              v-for="c in countries"
              :key="c.code"
              class="flex items-center gap-2.5 px-3 py-2 hover:bg-slate-50 cursor-pointer text-sm border-b border-slate-100 last:border-0"
              :class="form.allowed_countries.includes(c.code) ? 'bg-purple-50' : ''"
            >
              <input
                type="checkbox"
                :value="c.code"
                v-model="form.allowed_countries"
                class="rounded border-slate-300 text-purple-600 focus:ring-purple-500"
              />
              <span>{{ c.flag }} {{ c.name }}</span>
            </label>
          </div>
          <p class="mt-1 text-xs text-slate-500">{{ form.allowed_countries.length }} selected</p>
          <p v-if="errors?.allowed_countries" role="alert" class="mt-1 text-xs text-danger-600">
            {{ errors.allowed_countries }}
          </p>
        </div>

        <!-- Single Country / Sub-Country: base country dropdown -->
        <div v-if="['single_country', 'sub_country'].includes(form.geographic_scope)">
          <label for="base-country" class="block text-sm font-medium text-slate-700 mb-1">
            Base Country <span class="text-danger-500" aria-hidden="true">*</span>
          </label>
          <div v-if="loadingCountries" class="h-10 bg-slate-100 animate-pulse rounded-lg" />
          <select
            v-else
            id="base-country"
            v-model="form.base_country_code"
            :class="inputClass('base_country_code')"
            @change="onCountryChange"
          >
            <option value="">Select country…</option>
            <option v-for="c in countries" :key="c.code" :value="c.code">
              {{ c.flag }} {{ c.name }}
            </option>
          </select>
          <p v-if="errors?.base_country_code" role="alert" class="mt-1 text-xs text-danger-600">
            {{ errors.base_country_code }}
          </p>
        </div>

        <!-- Sub-Country: region dropdown -->
        <div v-if="form.geographic_scope === 'sub_country' && form.base_country_code">
          <label for="base-region" class="block text-sm font-medium text-slate-700 mb-1">
            Base Region / State <span class="text-danger-500" aria-hidden="true">*</span>
          </label>
          <div v-if="loadingRegions" class="h-10 bg-slate-100 animate-pulse rounded-lg" />
          <div v-else-if="regions.length === 0" class="text-xs text-slate-400 py-2">
            No regions found for this country.
          </div>
          <select
            v-else
            id="base-region"
            v-model="form.base_region_id"
            :class="inputClass('base_region_id')"
          >
            <option value="">Select region…</option>
            <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
          </select>
          <p v-if="errors?.base_region_id" role="alert" class="mt-1 text-xs text-danger-600">
            {{ errors.base_region_id }}
          </p>
        </div>

      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { LockClosedIcon } from '@heroicons/vue/24/outline'
import { geographyApi } from '@/services/geographyApi'

const props = defineProps({
  form: { type: Object, required: true },
  errors: { type: Object, default: () => ({}) },
})

const countries = ref([])
const regions = ref([])
const loadingCountries = ref(false)
const loadingRegions = ref(false)

const structureOptions = [
  {
    value: 'flat',
    label: 'Single Committee',
    description: 'One committee manages everything. No parent-child hierarchy.',
  },
  {
    value: 'geographical',
    label: 'Geographical Committees',
    description: 'Multi-level hierarchy — Continent → Country → Region → District.',
  },
]

const scopeOptions = [
  { value: 'worldwide',     label: '🌍 Worldwide — all countries' },
  { value: 'multi_country', label: '🌐 Multi-Country — specific countries (diaspora)' },
  { value: 'single_country',label: '🏳️ Single Country — one nation' },
  { value: 'sub_country',   label: '🗺️ Sub-Country — one region or state' },
]

const inputClass = (field) => [
  'w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 transition-colors',
  'focus:outline-none focus:ring-2 focus:border-transparent',
  props.errors?.[field]
    ? 'border-danger-400 bg-danger-50 focus:ring-red-500'
    : 'border-slate-300 bg-white hover:border-slate-400 focus:ring-purple-500',
]

const loadCountries = async () => {
  loadingCountries.value = true
  try {
    countries.value = await geographyApi.getCountries()
  } finally {
    loadingCountries.value = false
  }
}

const onCountryChange = async () => {
  if (!props.form.base_country_code || props.form.geographic_scope !== 'sub_country') return
  loadingRegions.value = true
  props.form.base_region_id = ''
  regions.value = []
  try {
    regions.value = await geographyApi.getRegions(props.form.base_country_code)
  } finally {
    loadingRegions.value = false
  }
}

// Reset sub-fields when scope changes
watch(() => props.form.geographic_scope, () => {
  props.form.allowed_countries = []
  props.form.base_country_code = ''
  props.form.base_region_id = ''
  regions.value = []
})

// Reset geographic fields when structure changes to flat
watch(() => props.form.committee_structure, (val) => {
  if (val === 'flat') {
    props.form.geographic_scope = ''
    props.form.allowed_countries = []
    props.form.base_country_code = ''
    props.form.base_region_id = ''
    regions.value = []
  }
})

onMounted(async () => {
  await loadCountries()

  // Pre-populate regions if editing an existing org with sub_country scope
  if (
    props.form.geographic_scope === 'sub_country' &&
    props.form.base_country_code &&
    props.form.base_region_id
  ) {
    await onCountryChange()
  }
})
</script>
