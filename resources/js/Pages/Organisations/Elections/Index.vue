<template>
  <election-layout>
    <div class="min-h-screen bg-neutral-100 p-6">

      <!-- Header -->
      <Card padding="lg" class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-neutral-900">
            {{ statusFilter ? statusFilter.charAt(0).toUpperCase() + statusFilter.slice(1) + ' Elections' : 'All Elections' }}
          </h1>
          <p class="text-neutral-500 mt-1">{{ organisation.name }}</p>
        </div>
        <div class="flex items-center gap-3">
          <Button
            v-if="canManage"
            as="a"
            :href="`/organisations/${organisation.slug}/elections/create`"
            variant="primary"
            size="md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Election
          </Button>
          <Button
            as="a"
            :href="`/organisations/${organisation.slug}`"
            variant="outline"
            size="md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
          </Button>
        </div>
      </Card>

      <!-- Elections Table -->
      <Card padding="none" class="overflow-hidden">
        <div v-if="elections.length === 0" class="p-12 text-center text-neutral-500">
          <svg class="w-12 h-12 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <p class="text-lg font-medium">No elections yet</p>
          <p class="text-sm mt-1">Create your first election to get started.</p>
        </div>

        <table v-else class="w-full text-sm">
          <thead class="bg-neutral-50 border-b border-neutral-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wide">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wide">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wide">Start Date</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wide">End Date</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wide">Created</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-100">
            <tr v-for="election in elections" :key="election.id" class="hover:bg-neutral-50 transition-colors">
              <td class="px-6 py-4 font-medium text-neutral-900">{{ election.name }}</td>
              <td class="px-6 py-4">
                <span :class="statusClass(election.status)" class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold">
                  {{ election.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-neutral-600">{{ formatDate(election.start_date) }}</td>
              <td class="px-6 py-4 text-neutral-600">{{ formatDate(election.end_date) }}</td>
              <td class="px-6 py-4 text-neutral-600">{{ formatDate(election.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <a
                  v-if="canManage && election.slug"
                  :href="`/organisations/${organisation.slug}/elections/${election.slug}/posts`"
                  class="text-primary-600 hover:text-primary-800 font-medium text-sm"
                >
                  Manage →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>

    </div>
  </election-layout>
</template>

<script>
import Button from '@/Components/Button.vue'
import Card from '@/Components/Card.vue'

export default {
  components: {
    Button,
    Card,
  },

  props: {
    organisation: Object,
    elections: Array,
    canManage: Boolean,
    statusFilter: String,
  },

  methods: {
    formatDate(date) {
      if (!date) return '—';
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      });
    },

    statusClass(status) {
      const map = {
        active:    'bg-success-100 text-success-800',
        completed: 'bg-neutral-100 text-neutral-700',
        draft:     'bg-warning-100 text-warning-800',
        expired:   'bg-danger-100 text-danger-800',
      };
      return map[status] ?? 'bg-neutral-100 text-neutral-700';
    },
  },
};
</script>
