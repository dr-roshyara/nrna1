<template>
  <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-primary-200 bg-primary-50 px-4 py-3 text-sm">
    <p class="text-primary-800">
      {{ t('pages.reference-geography.knownAt', { position: knownAt.position }) }}
      <span class="text-primary-700">· {{ t('pages.reference-geography.validOn', { date: validDate }) }}</span>
    </p>
    <Link :href="knownAt.latestUrl" class="text-primary-700 underline underline-offset-2">
      {{ t('pages.reference-geography.latest') }}
    </Link>
  </div>
</template>

<script setup lang="ts">
// PBDIGIT-355: "as officially known at" — every answer on the page was read at this one publication position
// (pinned; browsing never writes). The latest link starts a new visit at the newest position.
import { Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import type { PropType } from 'vue'
import type { KnownAt } from '@/types/referenceGeography'

defineProps({
  knownAt: { type: Object as PropType<KnownAt>, required: true },
  validDate: { type: String, required: true },
})
const { t } = useI18n()
</script>
