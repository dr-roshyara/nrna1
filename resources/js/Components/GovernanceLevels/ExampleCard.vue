<template>
  <Card padding="lg" class="h-full">
    <div class="flex items-start gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
        <slot name="icon">
          <span class="text-xl">{{ icon }}</span>
        </slot>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-neutral-900">{{ title }}</h3>
        <p class="text-sm text-neutral-500">{{ organisationType }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <!-- Detailed table (all columns) -->
      <table v-if="detailed" class="w-full text-sm">
        <thead>
          <tr class="border-b border-neutral-200">
            <th class="text-left py-2 pr-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-16">{{ $t('examples_table.level') }}</th>
            <th class="text-left py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('examples_table.committee_name') }}</th>
            <th class="text-left py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('examples_table.committee_code') }}</th>
            <th class="text-left py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('examples_table.geo_name') }}</th>
            <th class="text-left py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('examples_table.geo_code') }}</th>
            <th class="text-left py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('examples_table.parent_geo_code') }}</th>
            <th class="text-center py-2 px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-20">{{ $t('examples_table.active') }}</th>
            <th class="text-right py-2 pl-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-32">{{ $t('examples_table.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, i) in levelData" :key="i"
              class="border-b border-neutral-100 last:border-0"
              :class="{ 'bg-primary-50/30': i === 0 }"
          >
            <td class="py-2 pr-2 font-mono text-neutral-900">{{ row.level }}</td>
            <td class="py-2 px-2 text-neutral-700">{{ row.committee }}</td>
            <td class="py-2 px-2 font-mono text-neutral-600">{{ row.committee_code || '—' }}</td>
            <td class="py-2 px-2 text-neutral-700">{{ row.geo }}</td>
            <td class="py-2 px-2 font-mono text-neutral-600">{{ row.geo_code || '—' }}</td>
            <td class="py-2 px-2 font-mono text-neutral-500">{{ row.parent_geo_code || '—' }}</td>
            <td class="py-2 px-2 text-center">
              <span :class="row.is_active !== false ? 'text-success-600' : 'text-neutral-300'" class="text-lg">●</span>
            </td>
            <td class="py-2 pl-2 text-right whitespace-nowrap text-neutral-400 text-xs">
              <span class="text-primary-600 hover:text-primary-700 cursor-pointer mr-2">{{ $t('examples_table.edit') }}</span>
              <span class="text-danger-500 hover:text-danger-600 cursor-pointer">{{ $t('examples_table.delete') }}</span>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Simple table (3 columns) -->
      <table v-else class="w-full text-sm">
        <thead>
          <tr class="border-b border-neutral-200">
            <th class="text-left py-2 pr-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider w-16">{{ $t('examples_table.level') }}</th>
            <th class="text-left py-2 px-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('examples_table.committee') }}</th>
            <th class="text-left py-2 pl-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('examples_table.geo_scope') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, i) in levelData" :key="i"
              class="border-b border-neutral-100 last:border-0"
              :class="{ 'bg-primary-50/30': i === 0 }"
          >
            <td class="py-2 pr-3 font-mono text-neutral-900">{{ row.level }}</td>
            <td class="py-2 px-3 text-neutral-700">{{ row.committee }}</td>
            <td class="py-2 pl-3">
              <span class="text-neutral-600">{{ row.geo }}</span>
              <span v-if="row.parent" class="text-neutral-400 text-xs ml-1">(Parent: {{ row.parent }})</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Card>
</template>

<script setup lang="ts">
import Card from '@/Components/Card.vue'

defineProps<{
  title: string
  icon: string
  organisationType: string
  detailed?: boolean
  levelData: Array<{
    level: number
    committee: string
    geo: string
    parent?: string
    committee_code?: string
    geo_code?: string
    parent_geo_code?: string
    is_active?: boolean
  }>
}>()
</script>
