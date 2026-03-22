<template>
  <div
    :class="bannerClass"
    role="banner"
    :aria-label="ariaLabel"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-1">
          <!-- Globe Icon (MODE 1) -->
          <svg v-if="isGlobal" class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20H7m6 0v-2c0-.656.126-1.283.356-1.857m0 0a5 5 0 015.898-8.86c.474-.468.666-1.157.289-1.774A6 6 0 0021 9c0 .896-.153 1.755-.476 2.569m0 0c.321.895.476 1.674.476 2.569 0 2.191-.868 4.169-2.276 5.614" />
          </svg>

          <!-- Building Icon (MODE 2) -->
          <svg v-else class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
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
    validator: (v) => ['global', 'organisation'].includes(v)
  },
  organisationId: { type: Number, default: null }
});

const isGlobal = computed(() => props.mode === 'global');

const bannerClass = computed(() =>
  isGlobal.value
    ? 'bg-blue-50 text-blue-900 border-b-2 border-blue-200 dark:bg-blue-900 dark:text-blue-50 dark:border-blue-700'
    : 'bg-purple-50 text-purple-900 border-b-2 border-purple-200 dark:bg-purple-900 dark:text-purple-50 dark:border-purple-700'
);

const badgeClass = computed(() =>
  isGlobal.value
    ? 'px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 whitespace-nowrap'
    : 'px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 whitespace-nowrap'
);

const title = computed(() =>
  isGlobal.value
    ? t('pages.demo-result.mode_indicator.global_title')
    : t('pages.demo-result.mode_indicator.org_title')
);

const description = computed(() =>
  isGlobal.value
    ? t('pages.demo-result.mode_indicator.global_description')
    : t('pages.demo-result.mode_indicator.org_description', { id: props.organisationId })
);

const badgeText = computed(() =>
  isGlobal.value
    ? t('pages.demo-result.mode_indicator.mode1')
    : t('pages.demo-result.mode_indicator.mode2')
);

const ariaLabel = computed(() => `${title.value}: ${description.value}`);
</script>
