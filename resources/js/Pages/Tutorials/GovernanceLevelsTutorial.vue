<template>
  <PublicDigitLayout>
    <MetaTags
      :title="$t('meta.title')"
      :description="$t('meta.description')"
    />

    <main class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-blue-50 py-12 px-4">
      <div class="max-w-5xl mx-auto">
        <!-- Hero Section -->
        <section class="text-center mb-16">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-100 mb-6">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold text-neutral-900 mb-4">
            {{ $t('hero.title') }}
          </h1>
          <p class="text-xl text-neutral-600 font-medium mb-4">
            {{ $t('hero.subtitle') }}
          </p>
          <p class="text-base text-neutral-500 max-w-3xl mx-auto leading-relaxed">
            {{ $t('hero.summary') }}
          </p>
        </section>

        <!-- Section Divider -->
        <div class="relative mb-16">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-neutral-200"></div>
          </div>
        </div>

        <!-- Why Section -->
        <section class="mb-16">
          <h2 class="text-2xl font-bold text-neutral-900 text-center mb-8">
            {{ $t('why.title') }}
          </h2>
          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="(point, i) in whyPoints" :key="i" padding="md" class="hover:shadow-md transition-shadow">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-neutral-900 mb-1">{{ point.title }}</h3>
                  <p class="text-sm text-neutral-500">{{ point.description }}</p>
                </div>
              </div>
            </Card>
          </div>
        </section>

        <!-- Section Divider -->
        <div class="relative mb-16">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-neutral-200"></div>
          </div>
        </div>

        <!-- How Section -->
        <section class="mb-16">
          <h2 class="text-2xl font-bold text-neutral-900 text-center mb-8">
            {{ $t('how.title') }}
          </h2>
          <Card padding="lg">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b border-neutral-200">
                    <th class="text-left py-3 pr-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-40">{{ $t('how.table_header.field') }}</th>
                    <th class="text-left py-3 pl-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('how.table_header.description') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(field, i) in howFields" :key="field.key" class="border-b border-neutral-100 last:border-0" :class="i % 2 === 0 ? 'bg-white' : 'bg-neutral-50'">
                    <td class="py-3 pr-4">
                      <span class="font-medium text-neutral-800">{{ field.field }}</span>
                      <span v-if="field.required" class="ml-1 text-danger-500">*</span>
                    </td>
                    <td class="py-3 pl-4 text-neutral-600">{{ field.description }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </Card>
        </section>

        <!-- Section Divider -->
        <div class="relative mb-16">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-neutral-200"></div>
          </div>
        </div>

        <!-- Examples Section -->
        <section class="mb-16">
          <h2 class="text-2xl font-bold text-neutral-900 text-center mb-2">
            {{ $t('examples.title') }}
          </h2>
          <p class="text-neutral-500 text-center mb-8">
            {{ $t('examples.subtitle') }}
          </p>
          <div class="grid md:grid-cols-2 gap-6">
            <ExampleCard
              title="NRNA International"
              organisation-type="Non-Resident Nepali Association"
              :level-data="nrnaLevelsDetailed"
              detailed
              class="md:col-span-2"
            >
              <template #icon>
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </template>
            </ExampleCard>
            <ExampleCard
              title="SPD"
              organisation-type="German Political Party (SPD)"
              :level-data="spdLevelsDetailed"
              detailed
              class="md:col-span-2"
            >
              <template #icon>
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </template>
            </ExampleCard>
            <ExampleCard
              title="Political Party"
              organisation-type="Democratic Organisation"
              :level-data="congressLevelsDetailed"
              detailed
              class="md:col-span-2"
            >
              <template #icon>
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </template>
            </ExampleCard>
            <ExampleCard
              title="Corporate Group"
              organisation-type="Multi-National Corporation"
              :level-data="bjpLevelsDetailed"
              detailed
              class="md:col-span-2"
            >
              <template #icon>
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </template>
            </ExampleCard>
          </div>
        </section>

        <!-- Section Divider -->
        <div class="relative mb-16">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-neutral-200"></div>
          </div>
        </div>

        <!-- CTA Section -->
        <section class="text-center bg-white rounded-xl shadow-sm border border-neutral-200 p-10 mb-8">
          <h2 class="text-2xl font-bold text-neutral-900 mb-3">
            {{ $t('cta.title') }}
          </h2>
          <p class="text-neutral-500 mb-6 max-w-2xl mx-auto">
            {{ $t('cta.description') }}
          </p>
          <p class="text-sm text-neutral-400 mb-4">
            {{ $t('cta.login_hint') }}
          </p>
          <Link href="/login" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            {{ $t('cta.button') }}
          </Link>
        </section>
      </div>
    </main>
  </PublicDigitLayout>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import MetaTags from '@/Components/SEO/MetaTags.vue'
import Card from '@/Components/Card.vue'
import { Link } from '@inertiajs/vue3'
import ExampleCard from '@/components/GovernanceLevels/ExampleCard.vue'

const { t } = useI18n()

const whyPoints = [
  { title: t('why.points.0.title'), description: t('why.points.0.description') },
  { title: t('why.points.1.title'), description: t('why.points.1.description') },
  { title: t('why.points.2.title'), description: t('why.points.2.description') },
  { title: t('why.points.3.title'), description: t('why.points.3.description') },
  { title: t('why.points.4.title'), description: t('why.points.4.description') },
]

const howFields = [
  { key: 'level', field: t('how.fields.0.field'), description: t('how.fields.0.description'), required: true },
  { key: 'committee_name', field: t('how.fields.1.field'), description: t('how.fields.1.description'), required: true },
  { key: 'committee_code', field: t('how.fields.2.field'), description: t('how.fields.2.description'), required: true },
  { key: 'geo_name', field: t('how.fields.3.field'), description: t('how.fields.3.description'), required: true },
  { key: 'geo_code', field: t('how.fields.4.field'), description: t('how.fields.4.description'), required: true },
  { key: 'geo_parent_code', field: t('how.fields.5.field'), description: t('how.fields.5.description') },
  { key: 'is_active', field: t('how.fields.6.field'), description: t('how.fields.6.description') },
]

const nrnaLevels = [
  { level: 0, committee: 'ICC Global Committee', geo: 'World' },
  { level: 1, committee: 'Continent Committee', geo: 'Continent', parent: 'World' },
  { level: 2, committee: 'National Committee', geo: 'Country', parent: 'Continent' },
  { level: 3, committee: 'Regional Committee', geo: 'Region/State', parent: 'Country' },
  { level: 4, committee: 'City Committee', geo: 'City', parent: 'Region' },
  { level: 5, committee: 'Ward Committee', geo: 'Ward', parent: 'City' },
]

const nrnaLevelsDetailed = [
  { level: 0, committee: 'ICC Global Committee', committee_code: 'ICC', geo: 'World', geo_code: 'WLD', parent_geo_code: null as string | null, is_active: true },
  { level: 1, committee: 'Continent Committee', committee_code: 'CONT', geo: 'Continent', geo_code: 'CONT', parent_geo_code: 'WLD', is_active: true },
  { level: 2, committee: 'National Coordination Committee', committee_code: 'NCC', geo: 'Country', geo_code: 'CTRY', parent_geo_code: 'CONT', is_active: true },
  { level: 3, committee: 'Local Coordination Committee', committee_code: 'LCC', geo: 'State/Region', geo_code: 'REG', parent_geo_code: 'CTRY', is_active: true },
  { level: 4, committee: 'City Committee', committee_code: 'CITY', geo: 'City', geo_code: 'CITY', parent_geo_code: 'REG', is_active: true },
  { level: 5, committee: 'Ward Committee', committee_code: 'WARD', geo: 'Ward/Neighborhood', geo_code: 'WARD', parent_geo_code: 'CITY', is_active: true },
]

const spdLevels = [
  { level: 0, committee: 'SPD Federal Party Committee', geo: 'Germany (National)' },
  { level: 1, committee: 'State Committee', geo: 'Federal State', parent: 'Germany' },
  { level: 2, committee: 'District Committee', geo: 'District/Bezirk', parent: 'State' },
  { level: 3, committee: 'Local Committee', geo: 'City/County', parent: 'District' },
  { level: 4, committee: 'Branch Committee', geo: 'Neighborhood/Town', parent: 'City' },
]

const spdLevelsDetailed = [
  { level: 0, committee: 'SPD Federal Party Committee', committee_code: 'SPD_FED', geo: 'Germany (National)', geo_code: 'DE', parent_geo_code: null as string | null, is_active: true },
  { level: 1, committee: 'SPD State Committee', committee_code: 'SPD_STATE', geo: 'Federal State', geo_code: 'DE-STATE', parent_geo_code: 'DE', is_active: true },
  { level: 2, committee: 'SPD District Committee', committee_code: 'SPD_DIST', geo: 'District/Bezirk', geo_code: 'DE-DIST', parent_geo_code: 'DE-STATE', is_active: true },
  { level: 3, committee: 'SPD Local Committee', committee_code: 'SPD_LOCAL', geo: 'City/County', geo_code: 'DE-LOCAL', parent_geo_code: 'DE-DIST', is_active: true },
  { level: 4, committee: 'SPD Branch Committee', committee_code: 'SPD_BRANCH', geo: 'Neighborhood/Town', geo_code: 'DE-BRANCH', parent_geo_code: 'DE-LOCAL', is_active: true },
]

const congressLevels = [
  { level: 0, committee: 'Central Working Committee', geo: 'Nepal (National)' },
  { level: 1, committee: 'Province Committee', geo: 'Province', parent: 'Nepal' },
  { level: 2, committee: 'District Committee', geo: 'District', parent: 'Province' },
  { level: 3, committee: 'Municipality Committee', geo: 'Municipality/City', parent: 'District' },
  { level: 4, committee: 'Ward Committee', geo: 'Ward', parent: 'Municipality' },
  { level: 5, committee: 'Booth Committee', geo: 'Polling Booth', parent: 'Ward' },
]

const congressLevelsDetailed = [
  { level: 0, committee: 'National Executive Committee', committee_code: 'NEC', geo: 'Nation', geo_code: 'NAT', parent_geo_code: null as string | null, is_active: true },
  { level: 1, committee: 'State Committee', committee_code: 'STATE', geo: 'State/Province', geo_code: 'ST', parent_geo_code: 'NAT', is_active: true },
  { level: 2, committee: 'District Committee', committee_code: 'DIST', geo: 'District', geo_code: 'DIST', parent_geo_code: 'ST', is_active: true },
  { level: 3, committee: 'Block Committee', committee_code: 'BLOCK', geo: 'Block/Mandal', geo_code: 'BLK', parent_geo_code: 'DIST', is_active: true },
  { level: 4, committee: 'Booth Committee', committee_code: 'BOOTH', geo: 'Polling Booth', geo_code: 'BTH', parent_geo_code: 'BLK', is_active: true },
]

const bjpLevels = [
  { level: 0, committee: 'National Executive', geo: 'India (National)' },
  { level: 1, committee: 'State Committee', geo: 'State', parent: 'India' },
  { level: 2, committee: 'District Committee', geo: 'District', parent: 'State' },
  { level: 3, committee: 'Mandal Committee', geo: 'Mandal/Block', parent: 'District' },
  { level: 4, committee: 'Booth Committee', geo: 'Polling Booth', parent: 'Mandal' },
]

const bjpLevelsDetailed = [
  { level: 0, committee: 'Global Board', committee_code: 'GLB', geo: 'Global', geo_code: 'GLB', parent_geo_code: null as string | null, is_active: true },
  { level: 1, committee: 'Regional Council', committee_code: 'REG', geo: 'Region', geo_code: 'RGN', parent_geo_code: 'GLB', is_active: true },
  { level: 2, committee: 'Country Office', committee_code: 'CTRY', geo: 'Country', geo_code: 'CTY', parent_geo_code: 'RGN', is_active: true },
  { level: 3, committee: 'Local Chapter', committee_code: 'LCL', geo: 'City/Locality', geo_code: 'LCL', parent_geo_code: 'CTY', is_active: true },
]
</script>
