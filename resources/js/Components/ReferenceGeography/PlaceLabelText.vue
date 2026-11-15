<template>
  <span class="inline-flex flex-wrap items-baseline gap-x-2">
    <span v-if="label.value" :lang="label.language || undefined" class="text-neutral-900">{{ label.value }}</span>
    <span v-else class="italic text-neutral-500">{{ t('pages.reference-geography.label.none') }}</span>
    <span v-if="note" data-label-note class="text-xs text-neutral-500">{{ note }}</span>
  </span>
</template>

<script setup lang="ts">
// PBDIGIT-355: a place's label as the backend chose it (ExplorerPageData::label). Presentation only: a
// platform-fallback name is labelled as such, never shown as a translation (D-87, G-7).
import { computed } from 'vue'
import type { PropType } from 'vue'
import { useI18n } from 'vue-i18n'
import type { PlaceLabel } from '@/types/referenceGeography'

const props = defineProps({
  label: { type: Object as PropType<PlaceLabel>, required: true },
})
const { t } = useI18n()

const note = computed(() => {
  if (props.label.kind === 'display_fallback') return t('pages.reference-geography.label.fallback', { language: props.label.language })
  if (props.label.kind === 'source') return t('pages.reference-geography.label.source', { language: props.label.language })
  return null
})
</script>
