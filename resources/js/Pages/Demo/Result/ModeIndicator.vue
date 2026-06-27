<template>
  <div
    :class="bannerClass"
    role="banner"
    :aria-label="ariaLabel"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-1">
          <!-- Globe Icon (global mode) -->
          <svg v-if="mode === 'global'" class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20H7m6 0v-2c0-.656.126-1.283.356-1.857m0 0a5 5 0 015.898-8.86c.474-.468.666-1.157.289-1.774A6 6 0 0021 9c0 .896-.153 1.755-.476 2.569m0 0c.321.895.476 1.674.476 2.569 0 2.191-.868 4.169-2.276 5.614" />
          </svg>

          <!-- Building Icon (organisation mode) -->
          <svg v-else-if="mode === 'organisation'" class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
          </svg>

          <!-- Star/Shield Icon (public mode) -->
          <svg v-else class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>

          <div>
            <p class="font-semibold text-sm sm:text-base">{{ title }}</p>
            <p class="text-xs sm:text-sm opacity-90">{{ description }}</p>
          </div>
        </div>

        <span :class="badgeClass">{{ badgeText }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  mode: {
    type: String,
    required: true,
    validator: (v) => ['global', 'organisation', 'public'].includes(v)
  },
  organisationId: { type: [String, Number], default: null }
});

const bannerClass = computed(() => {
  if (props.mode === 'global') return 'bg-primary-50 text-primary-900 border-b-2 border-primary-200'
  if (props.mode === 'public') return 'bg-accent-50 text-accent-900 border-b-2 border-accent-200'
  return 'bg-purple-50 text-purple-900 border-b-2 border-purple-200'
});

const badgeClass = computed(() => {
  if (props.mode === 'global') return 'px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 whitespace-nowrap'
  if (props.mode === 'public') return 'px-3 py-1 rounded-full text-xs font-medium bg-accent-100 text-accent-800 whitespace-nowrap'
  return 'px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 whitespace-nowrap'
});

const title = computed(() => {
  if (props.mode === 'global') return t('pages.demo-result.mode_indicator.global_title')
  if (props.mode === 'public') return 'Public Digit Demo Election'
  return t('pages.demo-result.mode_indicator.org_title')
});

const description = computed(() => {
  if (props.mode === 'global') return t('pages.demo-result.mode_indicator.global_description')
  if (props.mode === 'public') return 'Aggregated results for the public demo election'
  return t('pages.demo-result.mode_indicator.org_description', { id: props.organisationId })
});

const badgeText = computed(() => {
  if (props.mode === 'global') return t('pages.demo-result.mode_indicator.mode1')
  if (props.mode === 'public') return 'Public'
  return t('pages.demo-result.mode_indicator.mode2')
});

const ariaLabel = computed(() => `${title.value}: ${description.value}`);
</script>
