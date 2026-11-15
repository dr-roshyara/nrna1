<template>
  <PublicDigitLayout>
    <Head :title="place.label.value || t('pages.reference-geography.title')" />
    <div class="mx-auto max-w-4xl px-4 py-8 space-y-6">
      <Link :href="knownAt.explorerUrl" data-back class="text-sm text-primary-700 underline underline-offset-2">
        ← {{ t('pages.reference-geography.place.back') }}
      </Link>

      <header>
        <h1 class="text-2xl font-bold"><PlaceLabelText :label="place.label" /></h1>
      </header>

      <KnownAtBanner :known-at="knownAt" :valid-date="validDate" />

      <Card v-if="!place.exists" variant="warning" padding="sm" data-not-existing>
        <p class="text-sm text-warning-800">{{ t('pages.reference-geography.place.notExisting') }}</p>
      </Card>
      <Card v-if="place.disputedKinds.length" variant="warning" padding="sm" data-disputed>
        <p class="text-sm text-warning-800">{{ t('pages.reference-geography.place.disputed', { kinds: place.disputedKinds.join(', ') }) }}</p>
      </Card>
      <Card v-if="place.mergedInto" variant="primary" padding="sm" data-merged>
        <p class="text-sm text-primary-800">
          {{ t('pages.reference-geography.place.mergedInto') }}
          <Link :href="place.mergedInto.url" class="underline underline-offset-2">{{ place.mergedInto.unitId }}</Link>
        </p>
      </Card>

      <div class="grid gap-4 md:grid-cols-2">
        <Card padding="md">
          <h2 class="mb-2 font-bold text-neutral-900">{{ t('pages.reference-geography.place.names') }}</h2>
          <p v-if="place.displayName" data-display-name class="mb-2 text-sm text-neutral-700">
            {{ t('pages.reference-geography.place.displayName') }}: <span :lang="place.displayName.resolvedLocale || undefined">{{ place.displayName.value }}</span>
            <span v-if="place.displayName.cldrRelease" class="text-xs text-neutral-500">({{ t('pages.reference-geography.place.cldr', { release: place.displayName.cldrRelease }) }})</span>
          </p>
          <ul v-if="place.sourceNames.length" class="space-y-1 text-sm">
            <li v-for="n in place.sourceNames" :key="`${n.source}|${n.language}|${n.type}|${n.value}`">
              <span :lang="n.language" class="text-neutral-900">{{ n.value }}</span>
              <span class="text-xs text-neutral-500"> · {{ n.source }} · {{ n.language }} · {{ n.type }}</span>
            </li>
          </ul>
          <p v-else-if="!place.displayName" class="text-sm text-neutral-500">{{ t('pages.reference-geography.place.none') }}</p>
        </Card>

        <Card padding="md">
          <h2 class="mb-2 font-bold text-neutral-900">{{ t('pages.reference-geography.place.classifications') }}</h2>
          <dl class="text-sm">
            <div v-for="(value, scheme) in place.classifications" :key="`c-${scheme}`" class="flex gap-2">
              <dt class="text-neutral-500">{{ scheme }}</dt><dd class="text-neutral-900">{{ value }}</dd>
            </div>
          </dl>
          <p v-if="!Object.keys(place.classifications).length" class="text-sm text-neutral-500">{{ t('pages.reference-geography.place.none') }}</p>
          <h2 class="mb-2 mt-4 font-bold text-neutral-900">{{ t('pages.reference-geography.place.identifiers') }}</h2>
          <dl class="text-sm">
            <div v-for="(value, namespace) in place.identifiers" :key="`i-${namespace}`" class="flex gap-2">
              <dt class="text-neutral-500">{{ namespace }}</dt><dd class="font-mono text-neutral-900">{{ value }}</dd>
            </div>
          </dl>
          <p v-if="!Object.keys(place.identifiers).length" class="text-sm text-neutral-500">{{ t('pages.reference-geography.place.none') }}</p>
        </Card>
      </div>

      <Card padding="md" data-parents>
        <h2 class="mb-2 font-bold text-neutral-900">{{ t('pages.reference-geography.place.parents') }}</h2>
        <ul v-if="place.parents.length" class="space-y-1">
          <li v-for="parent in place.parents" :key="`${parent.scheme}|${parent.unitId}`" class="text-sm">
            <span class="text-xs text-neutral-500">{{ t('pages.reference-geography.place.scheme', { scheme: parent.scheme }) }}</span>
            · <Link :href="parent.url" class="underline underline-offset-2"><PlaceLabelText :label="parent.label" /></Link>
          </li>
        </ul>
        <p v-else class="text-sm text-neutral-500">{{ t('pages.reference-geography.place.noParents') }}</p>
      </Card>

      <Card padding="md">
        <h2 class="mb-2 font-bold text-neutral-900">{{ t('pages.reference-geography.place.children') }}</h2>
        <section v-for="(children, scheme) in childrenByScheme" :key="scheme" :data-children-scheme="scheme" class="mb-3">
          <h3 class="text-xs text-neutral-500">{{ t('pages.reference-geography.place.scheme', { scheme }) }}</h3>
          <ul class="mt-1 grid gap-1 sm:grid-cols-2">
            <li v-for="child in children" :key="child.unitId" class="text-sm">
              <Link :href="child.url" class="underline underline-offset-2"><PlaceLabelText :label="child.label" /></Link>
            </li>
          </ul>
        </section>
        <p v-if="!place.children.length" data-no-children class="text-sm text-neutral-500">{{ t('pages.reference-geography.place.noChildren') }}</p>
      </Card>
    </div>
  </PublicDigitLayout>
</template>

<script setup lang="ts">
// PBDIGIT-355 (frontend M1): one officially published place at one publication position, read-only. Only fields of
// the published contract are shown; the backend decides every fact (developer guides 34, 35, 36).
import { computed } from 'vue'
import type { PropType } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import Card from '@/Components/Card.vue'
import KnownAtBanner from '@/Components/ReferenceGeography/KnownAtBanner.vue'
import PlaceLabelText from '@/Components/ReferenceGeography/PlaceLabelText.vue'
import type { KnownAt, PlaceProfile, RelatedPlace } from '@/types/referenceGeography'

// Runtime props typed by the page-data contract (PlacePageProps); imported types cannot be resolved by defineProps<…>().
const props = defineProps({
  knownAt: { type: Object as PropType<KnownAt>, required: true },
  validDate: { type: String, required: true },
  language: { type: String, required: true },
  place: { type: Object as PropType<PlaceProfile>, required: true },
})
const { t } = useI18n()

// The backend orders children by scheme, then label (ExplorerPageData::byScheme); grouping keeps that order.
const childrenByScheme = computed(() => {
  const groups: Record<string, RelatedPlace[]> = {}
  for (const child of props.place.children) (groups[child.scheme] ??= []).push(child)
  return groups
})
</script>
