<template>
  <PublicDigitLayout>
    <Head :title="t('pages.reference-geography.title')" />
    <div class="mx-auto max-w-4xl px-4 py-8 space-y-6">
      <header>
        <h1 class="text-2xl font-bold text-neutral-900">{{ t('pages.reference-geography.title') }}</h1>
        <p class="text-neutral-600">{{ t('pages.reference-geography.subtitle') }}</p>
      </header>

      <KnownAtBanner :known-at="knownAt" :valid-date="validDate" />

      <Card v-if="empty" padding="none">
        <EmptyState
          :title="t('pages.reference-geography.empty.title')"
          :description="t('pages.reference-geography.empty.description')"
        />
      </Card>

      <ul v-else class="grid gap-3 sm:grid-cols-2">
        <li v-for="place in places" :key="place.unitId" data-place>
          <Link :href="place.url" class="block h-full">
            <Card padding="sm" hover class="h-full">
              <PlaceLabelText :label="place.label" />
              <p v-if="classes(place).length" class="mt-1 text-xs text-neutral-500">{{ classes(place).join(' · ') }}</p>
            </Card>
          </Link>
        </li>
      </ul>
    </div>
  </PublicDigitLayout>
</template>

<script setup lang="ts">
// PBDIGIT-355 (frontend M1): the officially published top-level places, read-only, pinned to one publication
// position (developer guides 34, 35, 36). The backend decides every fact; this page only shows it.
import { Head, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import Card from '@/Components/Card.vue'
import EmptyState from '@/Components/EmptyState.vue'
import KnownAtBanner from '@/Components/ReferenceGeography/KnownAtBanner.vue'
import PlaceLabelText from '@/Components/ReferenceGeography/PlaceLabelText.vue'
import type { PropType } from 'vue'
import type { KnownAt, PlaceSummary } from '@/types/referenceGeography'

// Runtime props typed by the page-data contract (ExplorerProps); imported types cannot be resolved by defineProps<…>().
defineProps({
  knownAt: { type: Object as PropType<KnownAt>, required: true },
  validDate: { type: String, required: true },
  language: { type: String, required: true },
  places: { type: Array as PropType<PlaceSummary[]>, required: true },
  empty: { type: Boolean, required: true },
})
const { t } = useI18n()

const classes = (place: PlaceSummary): string[] => Object.values(place.classifications)
</script>
