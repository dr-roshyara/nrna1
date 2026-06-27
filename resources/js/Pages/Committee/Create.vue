<template>
  <PublicDigitLayout>
    <!-- Content -->
    <div class="flex-1 container mx-auto max-w-2xl py-12 px-4">
      <!-- Main Form Card -->
      <article class="bg-white rounded-xl border border-neutral-200 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">

        <!-- Header Section -->
        <header class="px-8 py-7 border-b border-primary-100 bg-gradient-to-br from-primary-50 via-primary-50 to-white">
          <div class="flex items-start gap-3 mb-2">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100">
              <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-neutral-900">{{ $t('pages.committee.create.title') }}</h1>
              <p class="text-sm text-neutral-600 mt-1">Define the structure and governance of your new committee</p>
            </div>
          </div>
        </header>

        <!-- Form Content -->
        <form @submit.prevent="handleSubmit" class="px-8 py-8 space-y-8">

          <!-- Section 1: Basic Information -->
          <div>
            <div class="flex items-center gap-2 mb-6">
              <div class="h-1 w-12 bg-gradient-to-r from-primary-600 to-primary-400 rounded-full"></div>
              <h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Basic Information</h2>
            </div>

            <div class="space-y-5">
              <!-- Committee Name -->
              <div class="group">
                <label for="name" class="block text-sm font-semibold text-neutral-900 mb-2">
                  {{ $t('pages.committee.create.form.name') }}
                  <span class="text-danger-600">*</span>
                </label>
                <div class="relative">
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    :placeholder="$t('pages.committee.create.form.name_placeholder')"
                    :class="[
                      'w-full px-4 py-3 rounded-lg border transition-all duration-300',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500',
                      errors.name
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 bg-white hover:border-neutral-300'
                    ]"
                    @focus="clearError('name')"
                  />
                  <transition name="slideDown">
                    <span v-if="errors.name" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ errors.name }}
                    </span>
                  </transition>
                </div>
                <p class="text-xs text-neutral-500 mt-2">The official name of the committee</p>
              </div>

              <!-- Committee Code -->
              <div class="group">
                <label for="code" class="block text-sm font-semibold text-neutral-900 mb-2">
                  {{ $t('pages.committee.create.form.code') }}
                  <span class="text-danger-600">*</span>
                </label>
                <div class="relative">
                  <input
                    id="code"
                    v-model="form.code"
                    type="text"
                    :placeholder="$t('pages.committee.create.form.code_placeholder')"
                    :class="[
                      'w-full px-4 py-3 rounded-lg border transition-all duration-300 font-mono text-sm',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500',
                      errors.code
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 bg-neutral-50 hover:border-neutral-300 hover:bg-white'
                    ]"
                    @focus="clearError('code')"
                    @input="handleCodeInput"
                  />
                  <!-- Validation indicator -->
                  <div v-if="form.code && codeValidating" class="absolute right-3 top-3 animate-spin">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                  </div>
                  <div v-else-if="form.code && !codeExists && !errors.code" class="absolute right-3 top-3.5">
                    <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <transition name="slideDown">
                    <span v-if="errors.code" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ errors.code }}
                    </span>
                  </transition>
                </div>
                <p class="text-xs text-neutral-500 mt-2">Unique identifier (e.g., EXEC-001, YOUTH-NRW)</p>
              </div>

              <!-- Committee Type -->
              <div class="group">
                <label for="type" class="block text-sm font-semibold text-neutral-900 mb-2">
                  Committee Type
                  <span class="text-danger-600">*</span>
                </label>
                <div class="relative">
                  <select
                    id="type"
                    v-model="form.type"
                    :class="[
                      'w-full px-4 py-3 rounded-lg border transition-all duration-300',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500 appearance-none',
                      'bg-white cursor-pointer',
                      errors.type
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 hover:border-neutral-300'
                    ]"
                    @change="clearError('type')"
                  >
                    <option value="" disabled>Select committee type...</option>
                    <option v-for="t in committeeTypes" :key="t.value" :value="t.value">
                      {{ t.label }}
                    </option>
                  </select>
                  <svg class="absolute right-3 top-3.5 w-5 h-5 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                  <transition name="slideDown">
                    <span v-if="errors.type" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ errors.type }}
                    </span>
                  </transition>
                </div>
                <p class="text-xs text-neutral-500 mt-2">The type/classification of this committee</p>
              </div>

              <!-- Committee Slug -->
              <div class="group">
                <label for="slug" class="block text-sm font-semibold text-neutral-900 mb-2">
                  URL Slug
                  <span class="text-neutral-400 font-normal text-xs">(auto-generated from name)</span>
                </label>
                <div class="relative">
                  <div class="absolute left-4 top-3 text-neutral-400 font-semibold">/</div>
                  <input
                    id="slug"
                    v-model="form.slug"
                    type="text"
                    placeholder="committee-name"
                    :class="[
                      'w-full pl-7 pr-4 py-3 rounded-lg border transition-all duration-300 font-mono text-sm',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500',
                      errors.slug
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 bg-white hover:border-neutral-300'
                    ]"
                    @focus="clearError('slug')"
                    @input="handleSlugInput"
                  />
                  <!-- Validation indicator -->
                  <div v-if="form.slug && slugValidating" class="absolute right-3 top-3 animate-spin">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                  </div>
                  <div v-else-if="form.slug && !slugExists && !slugReserved && !errors.slug" class="absolute right-3 top-3.5">
                    <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <transition name="slideDown">
                    <span v-if="errors.slug" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ $t(errors.slug) }}
                    </span>
                  </transition>
                </div>
                <!-- Suggestions -->
                <transition name="slideDown">
                  <div v-if="slugSuggestions.length > 0 && (slugExists || slugReserved)" class="mt-3 p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                    <p class="text-xs font-semibold text-neutral-600 mb-2">{{ $t('committee.slug.suggestions') || 'Try one of these:' }}</p>
                    <div class="flex flex-wrap gap-2">
                      <button
                        v-for="suggestion in slugSuggestions"
                        :key="suggestion"
                        type="button"
                        @click.prevent="selectSuggestion(suggestion)"
                        class="px-3 py-1.5 bg-white border border-primary-200 rounded-full text-xs font-medium text-primary-700 hover:bg-primary-50 hover:border-primary-300 transition-all duration-200 cursor-pointer"
                      >
                        / {{ suggestion }}
                      </button>
                    </div>
                  </div>
                </transition>
                <p class="text-xs text-neutral-500 mt-2">URL-friendly identifier (auto-generated, lowercase, hyphens only)</p>
              </div>
            </div>
          </div>

          <!-- Section 2: Governance Assignment -->
          <div>
            <div class="flex items-center gap-2 mb-6">
              <div class="h-1 w-12 bg-gradient-to-r from-accent-600 to-accent-400 rounded-full"></div>
              <h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Governance Assignment</h2>
            </div>

            <div class="space-y-5">
              <!-- Governance Level -->
              <div class="group">
                <label for="governanceLevel" class="block text-sm font-semibold text-neutral-900 mb-2">
                  Governance Level
                  <span class="text-danger-600">*</span>
                </label>
                <div class="relative">
                  <select
                    id="governanceLevel"
                    v-model.number="form.governanceLevel"
                    :class="[
                      'w-full px-4 py-3 rounded-lg border transition-all duration-300 appearance-none',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500',
                      errors.governanceLevel
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 bg-white hover:border-neutral-300'
                    ]"
                    @change="clearError('governanceLevel'); form.geoUnitId = null; clearError('geoUnitId')"
                  >
                    <option :value="null" disabled>Select governance level…</option>
                    <option v-for="level in governanceLevels" :key="level.id" :value="level.level">
                      Level {{ level.level }} — {{ level.committee_name }}
                    </option>
                  </select>
                  <div class="pointer-events-none absolute right-3 top-4">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                  <transition name="slideDown">
                    <span v-if="errors.governanceLevel" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ errors.governanceLevel }}
                    </span>
                  </transition>
                </div>
                <p class="text-xs text-neutral-500 mt-2">The hierarchical tier this committee operates at</p>
              </div>

              <!-- Geographic Unit -->
              <div class="group">
                <label for="geoUnitId" class="block text-sm font-semibold text-neutral-900 mb-2">
                  Geographic Unit
                  <span class="text-danger-600">*</span>
                </label>
                <div class="relative">
                  <select
                    id="geoUnitId"
                    v-model="form.geoUnitId"
                    :disabled="form.governanceLevel === null"
                    :class="[
                      'w-full px-4 py-3 rounded-lg border transition-all duration-300 appearance-none',
                      'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 focus:border-primary-500',
                      form.governanceLevel === null ? 'opacity-50 cursor-not-allowed bg-neutral-50' : '',
                      errors.geoUnitId
                        ? 'border-danger-300 bg-danger-50 focus:ring-danger-500 focus:border-danger-500'
                        : 'border-neutral-200 bg-white hover:border-neutral-300'
                    ]"
                    @change="clearError('geoUnitId')"
                  >
                    <option :value="null" disabled>
                      {{ form.governanceLevel === null ? 'Select governance level first…' : 'Select geographic unit…' }}
                    </option>
                    <option v-for="unit in filteredGeoUnits" :key="unit.id" :value="unit.id">
                      {{ unit.name }} ({{ unit.code }})
                    </option>
                  </select>
                  <div class="pointer-events-none absolute right-3 top-4">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                  <transition name="slideDown">
                    <span v-if="errors.geoUnitId" class="absolute top-full mt-2 flex items-center gap-1 text-sm text-danger-600">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l8.687-8.687z" clip-rule="evenodd" /></svg>
                      {{ errors.geoUnitId }}
                    </span>
                  </transition>
                </div>
                <p class="text-xs text-neutral-500 mt-2">The geographic unit this committee is responsible for</p>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex gap-3 pt-6 border-t border-neutral-100">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-lg transition-all duration-300 hover:from-primary-700 hover:to-primary-800 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 group"
            >
              <svg v-if="!loading" class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span v-if="loading" class="inline-block">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
              </span>
              {{ loading ? 'Creating Committee...' : $t('pages.committee.create.button_create') }}
            </button>
            <button
              type="button"
              @click="handleCancel"
              class="px-6 py-3 border border-neutral-200 text-neutral-700 font-semibold rounded-lg transition-all duration-300 hover:border-neutral-300 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-300"
            >
              {{ $t('pages.committee.create.button_cancel') }}
            </button>
          </div>
        </form>
      </article>

      <!-- Help Text Card -->
      <div class="mt-8 rounded-lg border border-primary-100 bg-primary-50 p-5">
        <div class="flex gap-3">
          <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="text-sm font-semibold text-primary-900 mb-1">Governance × Geographic assignment</p>
            <p class="text-sm text-primary-700">Select a governance level first — the geographic units shown will match that level's administrative scope. Committee codes should be unique (e.g., EXEC-001, YOUTH-NRW).</p>
          </div>
        </div>
      </div>
    </div>

  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue';

const props = defineProps({
  organisation: Object,
  organisationSlug: String,
  governanceLevels: { type: Array, default: () => [] },
  geoUnits: { type: Array, default: () => [] },
});

const form = ref({
  name: '',
  code: '',
  slug: '',
  type: '', // Required: central, province, district, ward, youth_wing, women_wing, student_wing, diaspora, geographic
  governanceLevel: null,
  geoUnitId: null,
});

const committeeTypes = [
  { value: 'central', label: 'Central Committee' },
  { value: 'province', label: 'Provincial Committee' },
  { value: 'district', label: 'District Committee' },
  { value: 'ward', label: 'Ward Committee' },
  { value: 'youth_wing', label: 'Youth Wing' },
  { value: 'women_wing', label: 'Women Wing' },
  { value: 'student_wing', label: 'Student Wing' },
  { value: 'diaspora', label: 'Diaspora Committee' },
  { value: 'geographic', label: 'Geographic Committee' },
];

const filteredGeoUnits = computed(() =>
  props.geoUnits.filter(unit => unit.admin_level === form.value.governanceLevel)
);

const errors = ref({});
const loading = ref(false);
const codeValidating = ref(false);
const codeExists = ref(false);
let codeValidationTimeout = null;

const slugTouched = ref(false);
const slugValidating = ref(false);
const slugExists = ref(false);
const slugReserved = ref(false);
const slugSuggestions = ref([]);
let slugValidationTimeout = null;

const clearError = (field) => {
  delete errors.value[field];
};

// Debounced code validation
const checkCodeUniqueness = async () => {
  if (!form.value.code?.trim()) {
    codeExists.value = false;
    return;
  }

  codeValidating.value = true;

  try {
    const response = await fetch(
      route('committee.check-code', {
        organisation: route().params.organisation,
        code: form.value.code,
      })
    );
    const data = await response.json();
    codeExists.value = data.exists;

    if (data.exists) {
      errors.value.code = `Committee code "${form.value.code}" already exists`;
    } else {
      delete errors.value.code;
    }
  } catch (error) {
    console.error('Error checking code uniqueness:', error);
  } finally {
    codeValidating.value = false;
  }
};

const handleCodeInput = () => {
  clearError('code');
  codeExists.value = false;

  // Debounce the API call
  clearTimeout(codeValidationTimeout);
  codeValidationTimeout = setTimeout(() => {
    checkCodeUniqueness();
  }, 500);
};

// Auto-generate slug from name (only while untouched)
watch(() => form.value.name, (newName) => {
  if (!slugTouched.value) {
    form.value.slug = newName
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');

    triggerSlugCheck();
  }
});

// Debounced slug validation
const checkSlugUniqueness = async () => {
  if (!form.value.slug?.trim()) {
    slugExists.value = false;
    slugReserved.value = false;
    slugSuggestions.value = [];
    return;
  }

  slugValidating.value = true;

  try {
    const url = new URL(route('committee.check-slug', { organisation: route().params.organisation }));
    url.searchParams.append('value', form.value.slug);

    const response = await fetch(url.toString());
    const data = await response.json();

    slugExists.value = data.exists;
    slugReserved.value = data.reserved;
    slugSuggestions.value = data.suggestions || [];

    if (data.error_key) {
      errors.value.slug = data.error_key;
    } else {
      delete errors.value.slug;
    }
  } catch (error) {
    console.error('Error checking slug uniqueness:', error);
  } finally {
    slugValidating.value = false;
  }
};

const triggerSlugCheck = () => {
  clearTimeout(slugValidationTimeout);
  slugValidationTimeout = setTimeout(() => {
    checkSlugUniqueness();
  }, 500);
};

const handleSlugInput = () => {
  slugTouched.value = true;
  clearError('slug');
  slugExists.value = false;
  slugReserved.value = false;
  triggerSlugCheck();
};

const selectSuggestion = (suggestion) => {
  form.value.slug = suggestion;
  slugTouched.value = false;
  slugSuggestions.value = [];
  delete errors.value.slug;
  triggerSlugCheck();
};

onUnmounted(() => {
  clearTimeout(codeValidationTimeout);
  clearTimeout(slugValidationTimeout);
});

const handleSubmit = () => {
  loading.value = true;
  errors.value = {};

  // Validate
  if (!form.value.name?.trim()) {
    errors.value.name = 'Committee name is required';
  }
  if (!form.value.code?.trim()) {
    errors.value.code = 'Committee code is required';
  }
  if (codeExists.value) {
    errors.value.code = `Committee code "${form.value.code}" already exists`;
  }
  if (!form.value.type?.trim()) {
    errors.value.type = 'Committee type is required';
  }
  if (!form.value.slug?.trim()) {
    errors.value.slug = 'committee.slug.empty';
  }
  if (slugExists.value) {
    errors.value.slug = 'committee.slug.taken';
  }
  if (slugReserved.value) {
    errors.value.slug = 'committee.slug.reserved';
  }
  if (form.value.governanceLevel === null) {
    errors.value.governanceLevel = 'Governance level is required';
  }
  if (form.value.geoUnitId === null) {
    errors.value.geoUnitId = 'Geographic unit is required';
  }

  if (Object.keys(errors.value).length > 0) {
    loading.value = false;
    return;
  }

  // Submit via Inertia 2.0
  router.post(route('committees.store', { organisation: route().params.organisation }), form.value, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      loading.value = false;
    },
    onError: (serverErrors) => {
      loading.value = false;
      errors.value = serverErrors;
    },
  });
};

const handleCancel = () => {
  router.visit(route('committees.index', { organisation: route().params.organisation }));
};
</script>

<style scoped>
/* Smooth entrance animation */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

article {
  animation: slideUpFade 0.5s ease-out;
}

div:has(> div:first-child > .h-1) {
  animation: slideUpFade 0.6s ease-out 0.1s both;
}

/* Error message transitions */
.slideDown-enter-active,
.slideDown-leave-active {
  transition: all 0.3s ease;
}

.slideDown-enter-from {
  opacity: 0;
  transform: translateY(-8px);
}

.slideDown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

/* Focus state transitions */
input:focus,
select:focus {
  transition: all 0.2s ease;
}

/* Smooth input transitions */
input,
select {
  transition: border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
}

/* Loading spinner animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Button hover elevation */
button[type="submit"]:not(:disabled) {
  transition: all 0.3s ease;
}

button[type="submit"]:not(:disabled):hover {
  transform: translateY(-1px);
}

button[type="button"] {
  transition: all 0.3s ease;
}

button[type="button"]:hover {
  transform: translateY(-1px);
}

/* Refined focus ring for accessibility */
button:focus-visible {
  outline: 2px solid rgba(245, 158, 11, 0.5);
  outline-offset: 2px;
}

input:focus-visible,
select:focus-visible {
  outline: none;
}
</style>
