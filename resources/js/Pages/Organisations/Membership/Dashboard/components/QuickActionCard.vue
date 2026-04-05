<template>
  <a :href="href"
     class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:shadow-md transition-all group focus:outline-none focus:ring-2 focus:ring-offset-2"
     :class="focusRingClass">
    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors" :class="iconBgClass" aria-hidden="true">
      <component :is="icon" class="w-5 h-5" :class="iconTextClass" />
    </div>
    <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold text-slate-700 truncate">{{ title }}</p>
      <p class="text-xs text-slate-400 truncate">{{ description }}</p>
    </div>
    <ChevronRightIcon class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0" aria-hidden="true" />
  </a>
</template>

<script setup>
import { computed } from 'vue'
import { ChevronRightIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  title:       { type: String, required: true },
  description: { type: String, default: '' },
  href:        { type: String, required: true },
  icon:        { type: Object, required: true },
  color:       { type: String, default: 'purple' },
})

const colorMap = {
  purple: { bg: 'bg-purple-100 group-hover:bg-purple-200', text: 'text-purple-600', ring: 'focus:ring-purple-500 hover:border-purple-300' },
  blue:   { bg: 'bg-blue-100   group-hover:bg-blue-200',   text: 'text-blue-600',   ring: 'focus:ring-blue-500   hover:border-blue-300' },
  indigo: { bg: 'bg-indigo-100 group-hover:bg-indigo-200', text: 'text-indigo-600', ring: 'focus:ring-indigo-500 hover:border-indigo-300' },
  green:  { bg: 'bg-green-100  group-hover:bg-green-200',  text: 'text-green-600',  ring: 'focus:ring-green-500  hover:border-green-300' },
  amber:  { bg: 'bg-amber-100  group-hover:bg-amber-200',  text: 'text-amber-600',  ring: 'focus:ring-amber-500  hover:border-amber-300' },
  orange: { bg: 'bg-orange-100 group-hover:bg-orange-200', text: 'text-orange-600', ring: 'focus:ring-orange-500 hover:border-orange-300' },
  slate:  { bg: 'bg-slate-100  group-hover:bg-slate-200',  text: 'text-slate-600',  ring: 'focus:ring-slate-500  hover:border-slate-300' },
}

const c = computed(() => colorMap[props.color] ?? colorMap.purple)
const iconBgClass   = computed(() => c.value.bg)
const iconTextClass = computed(() => c.value.text)
const focusRingClass= computed(() => c.value.ring)
</script>
