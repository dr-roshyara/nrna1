<template>
  <tr
    class="hover:bg-neutral-50 transition-colors"
    :class="{ 'opacity-60': !unit.is_active }"
  >
    <!-- Name with depth indentation -->
    <td class="px-4 py-3">
      <div class="flex items-center gap-2" :style="{ paddingLeft: depth * 24 + 'px' }">
        <button
          v-if="hasChildren"
          class="flex items-center justify-center w-5 h-5 text-neutral-400 hover:text-neutral-600 transition-colors focus:outline-none"
          @click="$emit('toggle')"
          :aria-label="expanded ? 'Collapse' : 'Expand'"
        >
          <svg
            class="w-4 h-4 transition-transform duration-150"
            :class="{ 'rotate-90': expanded }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
        <span v-else class="w-5 h-5 flex-shrink-0" />
        <span
          class="text-sm font-medium cursor-pointer hover:text-primary-600 transition-colors"
          :class="unit.is_active ? 'text-neutral-900' : 'text-neutral-400'"
          @click="$emit('view')"
        >
          {{ unit.name || unit.code }}
        </span>
      </div>
    </td>

    <!-- Code -->
    <td class="px-4 py-3">
      <span class="text-sm font-mono" :class="unit.is_active ? 'text-neutral-600' : 'text-neutral-300'">
        {{ unit.code }}
      </span>
    </td>

    <!-- Level -->
    <td class="px-4 py-3">
      <span class="text-sm font-mono font-semibold text-neutral-700">
        {{ unit.admin_level }}
      </span>
    </td>

    <!-- Type -->
    <td class="px-4 py-3">
      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-600">
        {{ unit.admin_type }}
      </span>
    </td>

    <!-- Governance Level -->
    <td class="px-4 py-3">
      <span v-if="unit.governance_level !== null" class="text-sm text-primary-600 font-medium">
        {{ $t('pages.geo-units.fields.level') }} {{ unit.governance_level }}
      </span>
      <span v-else class="text-sm text-neutral-400">&mdash;</span>
    </td>

    <!-- Committee -->
    <td class="px-4 py-3">
      <span v-if="unit.governance_committee_code" class="text-sm font-mono text-neutral-600">
        {{ unit.governance_committee_code }}
      </span>
      <span v-else class="text-sm text-neutral-400">&mdash;</span>
    </td>

    <!-- Active -->
    <td class="px-4 py-3 text-center">
      <span
        class="inline-block w-2.5 h-2.5 rounded-full"
        :class="unit.is_active ? 'bg-success-500' : 'bg-neutral-200'"
      />
    </td>

    <!-- Actions -->
    <td class="px-4 py-3 text-right">
      <Button variant="ghost" size="sm" @click="$emit('view')">
        {{ $t('pages.geo-units.actions.refresh') }}
      </Button>
    </td>
  </tr>
</template>

<script setup lang="ts">
import Button from '@/Components/Button.vue'
import type { GovernanceGeoUnit } from '@/types/geo.types'

defineProps<{
  unit: GovernanceGeoUnit;
  expanded: boolean;
  depth: number;
  hasChildren: boolean;
}>()

defineEmits<{
  toggle: [];
  view: [];
}>()
</script>
