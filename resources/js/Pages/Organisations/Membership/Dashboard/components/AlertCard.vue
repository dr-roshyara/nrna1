<template>
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
    <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
      <h3 class="text-sm font-semibold text-slate-700">{{ title }}</h3>
    </div>
    <div class="divide-y divide-slate-100">
      <div
        v-for="(alert, idx) in alerts"
        :key="idx"
        role="status"
        class="p-4 flex items-start gap-3"
        :class="alertBgClass(alert.type)"
      >
        <component :is="alertIcon(alert.type)" class="w-5 h-5 flex-shrink-0 mt-0.5" :class="alertIconClass(alert.type)" aria-hidden="true" />
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold" :class="alertTitleClass(alert.type)">{{ alert.title }}</p>
          <p class="text-xs mt-0.5" :class="alertMsgClass(alert.type)">{{ alert.message }}</p>
          <a v-if="alert.action"
             :href="alert.action.href"
             class="inline-block mt-1.5 text-xs font-semibold underline focus:outline-none focus:ring-2 focus:ring-offset-1 rounded"
             :class="alertLinkClass(alert.type)">
            {{ alert.action.label }} →
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'

defineProps({
  title:  { type: String, default: 'Alerts' },
  alerts: { type: Array, required: true },
})

const alertIcon      = (type) => type === 'warning' ? ExclamationTriangleIcon : InformationCircleIcon
const alertBgClass   = (type) => type === 'warning' ? 'bg-amber-50'   : 'bg-blue-50'
const alertIconClass = (type) => type === 'warning' ? 'text-amber-500' : 'text-blue-500'
const alertTitleClass= (type) => type === 'warning' ? 'text-amber-800' : 'text-blue-800'
const alertMsgClass  = (type) => type === 'warning' ? 'text-amber-700' : 'text-blue-700'
const alertLinkClass = (type) => type === 'warning' ? 'text-amber-800 focus:ring-amber-500' : 'text-blue-800 focus:ring-blue-500'
</script>
