<template>
  <article
    class="group bg-white rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden"
    :aria-label="election.name"
  >
    <!-- Card Header -->
    <div class="px-5 pt-5 pb-4 flex items-start justify-between gap-3">
      <div class="min-w-0 flex-1">
        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ election.name }}</h3>
        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
          <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          {{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}
        </p>
      </div>
      <StatusBadge :status="election.status" size="sm" />
    </div>

    <!-- Divider -->
    <div class="mx-5 border-t border-slate-100" />

    <!-- Actions -->
    <div class="px-5 py-4 flex items-center gap-2 mt-auto flex-wrap">
      <!-- Activate — chief/deputy only, planned elections -->
      <ActionButton
        v-if="canActivate"
        variant="warning"
        size="sm"
        :loading="activatingId === election.id"
        :disabled="activatingId === election.id"
        class="flex-1 sm:flex-none"
        @click="$emit('activate', election.id)"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        {{ activatingId === election.id ? 'Activating…' : 'Activate' }}
      </ActionButton>

      <!-- Manage — chief/deputy/admin/owner -->
      <a
        v-if="canManage && !isReadonly"
        :href="`/elections/${election.id}/management`"
        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
        :aria-label="`Manage ${election.name}`"
      >
        Manage
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>

      <!-- View only — commissioner / regular member -->
      <span
        v-if="isReadonly"
        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 border border-slate-200 text-slate-400 rounded-lg bg-slate-50 cursor-default select-none"
        aria-label="View only"
      >
        View only
      </span>
    </div>
  </article>
</template>

<script setup>
import StatusBadge from '@/Components/StatusBadge.vue'
import ActionButton from '@/Components/ActionButton.vue'

defineProps({
  election:    { type: Object, required: true },
  activatingId: { type: [Number, String, null], default: null },
  canActivate: { type: Boolean, default: false },
  canManage:   { type: Boolean, default: false },
  isReadonly:  { type: Boolean, default: false },
})

defineEmits(['activate'])

const formatDate = (dateStr) => dateStr ? dateStr.slice(0, 10) : '—'
</script>
