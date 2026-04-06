<template>
  <a :href="href"
     class="relative flex flex-col gap-3 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm
            hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm
            transition-all duration-200 group
            focus:outline-none focus:ring-2 focus:ring-offset-2"
     :class="focusRingClass"
     :aria-label="`${title} — ${description}`">

    <!-- Colour accent stripe -->
    <div class="absolute inset-x-0 top-0 h-1 rounded-t-2xl transition-opacity opacity-0 group-hover:opacity-100" :class="accentBarClass" aria-hidden="true" />

    <!-- Icon + arrow row -->
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors"
           :class="iconBgClass" aria-hidden="true">
        <component :is="icon" class="w-5 h-5 transition-transform group-hover:scale-110" :class="iconTextClass" />
      </div>
      <ChevronRightIcon class="w-4 h-4 mt-1 flex-shrink-0 transition-all opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5"
                        :class="iconTextClass" aria-hidden="true" />
    </div>

    <!-- Text -->
    <div class="flex-1 min-w-0">
      <p class="text-sm font-bold text-slate-800 leading-snug">{{ title }}</p>
      <p class="text-xs text-slate-400 mt-0.5 leading-snug">{{ description }}</p>
    </div>
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
  purple: { bg: 'bg-purple-100 group-hover:bg-purple-200', text: 'text-purple-600', ring: 'focus:ring-purple-500 hover:border-purple-300', bar: 'bg-gradient-to-r from-purple-500 to-purple-400' },
  violet: { bg: 'bg-violet-100 group-hover:bg-violet-200', text: 'text-violet-600', ring: 'focus:ring-violet-500 hover:border-violet-300', bar: 'bg-gradient-to-r from-violet-500 to-violet-400' },
  blue:   { bg: 'bg-blue-100   group-hover:bg-blue-200',   text: 'text-blue-600',   ring: 'focus:ring-blue-500   hover:border-blue-300',   bar: 'bg-gradient-to-r from-blue-500   to-blue-400' },
  indigo: { bg: 'bg-indigo-100 group-hover:bg-indigo-200', text: 'text-indigo-600', ring: 'focus:ring-indigo-500 hover:border-indigo-300', bar: 'bg-gradient-to-r from-indigo-500 to-indigo-400' },
  green:  { bg: 'bg-green-100  group-hover:bg-green-200',  text: 'text-green-600',  ring: 'focus:ring-green-500  hover:border-green-300',  bar: 'bg-gradient-to-r from-green-500  to-emerald-400' },
  emerald:{ bg: 'bg-emerald-100 group-hover:bg-emerald-200', text: 'text-emerald-600', ring: 'focus:ring-emerald-500 hover:border-emerald-300', bar: 'bg-gradient-to-r from-emerald-500 to-teal-400' },
  teal:   { bg: 'bg-teal-100   group-hover:bg-teal-200',   text: 'text-teal-600',   ring: 'focus:ring-teal-500   hover:border-teal-300',   bar: 'bg-gradient-to-r from-teal-500   to-cyan-400' },
  sky:    { bg: 'bg-sky-100    group-hover:bg-sky-200',    text: 'text-sky-600',    ring: 'focus:ring-sky-500    hover:border-sky-300',    bar: 'bg-gradient-to-r from-sky-500    to-blue-400' },
  amber:  { bg: 'bg-amber-100  group-hover:bg-amber-200',  text: 'text-amber-600',  ring: 'focus:ring-amber-500  hover:border-amber-300',  bar: 'bg-gradient-to-r from-amber-500  to-yellow-400' },
  orange: { bg: 'bg-orange-100 group-hover:bg-orange-200', text: 'text-orange-600', ring: 'focus:ring-orange-500 hover:border-orange-300', bar: 'bg-gradient-to-r from-orange-500 to-amber-400' },
  slate:  { bg: 'bg-slate-100  group-hover:bg-slate-200',  text: 'text-slate-600',  ring: 'focus:ring-slate-500  hover:border-slate-300',  bar: 'bg-gradient-to-r from-slate-400  to-slate-300' },
}

const c = computed(() => colorMap[props.color] ?? colorMap.purple)
const iconBgClass    = computed(() => c.value.bg)
const iconTextClass  = computed(() => c.value.text)
const focusRingClass = computed(() => c.value.ring)
const accentBarClass = computed(() => c.value.bar)
</script>
