<template>
  <div class="py-14 text-center px-6">
    <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
      <component :is="iconComponent" class="w-7 h-7 text-slate-400" />
    </div>
    <h3 class="text-base font-semibold text-slate-700 mb-1">{{ title }}</h3>
    <p v-if="description" class="text-sm text-slate-400 max-w-xs mx-auto mb-4">{{ description }}</p>
    <a v-if="actionLabel && actionHref"
       :href="actionHref"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-purple-600 hover:text-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1 rounded">
      {{ actionLabel }}
      <ChevronRightIcon class="w-4 h-4" aria-hidden="true" />
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  DocumentTextIcon,
  UsersIcon,
  InboxIcon,
  ChevronRightIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  icon:        { type: String, default: 'inbox' },   // 'document' | 'users' | 'inbox'
  title:       { type: String, required: true },
  description: { type: String, default: '' },
  actionLabel: { type: String, default: '' },
  actionHref:  { type: String, default: '' },
})

const iconComponent = computed(() => {
  const map = { document: DocumentTextIcon, users: UsersIcon, inbox: InboxIcon }
  return map[props.icon] ?? InboxIcon
})
</script>
