<template>
  <election-layout>
    <div class="min-h-screen bg-gray-100 p-6">

      <!-- Header -->
      <div class="mb-6 bg-white rounded-xl shadow-sm p-6 flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            {{ statusFilter ? statusFilter.charAt(0).toUpperCase() + statusFilter.slice(1) + ' Elections' : 'All Elections' }}
          </h1>
          <p class="text-gray-500 mt-1">{{ organisation.name }}</p>
        </div>
        <div class="flex items-center gap-3">
          <a
            v-if="canManage"
            :href="`/organisations/${organisation.slug}/elections/create`"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Election
          </a>
          <a
            :href="`/organisations/${organisation.slug}`"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
          </a>
        </div>
      </div>

      <!-- Elections Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div v-if="elections.length === 0" class="p-12 text-center text-gray-500">
          <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <p class="text-lg font-medium">No elections yet</p>
          <p class="text-sm mt-1">Create your first election to get started.</p>
        </div>

        <table v-else class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Start Date</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">End Date</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="election in elections" :key="election.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-medium text-gray-900">{{ election.name }}</td>
              <td class="px-6 py-4">
                <span :class="statusClass(election.status)" class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold">
                  {{ election.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-gray-600">{{ formatDate(election.start_date) }}</td>
              <td class="px-6 py-4 text-gray-600">{{ formatDate(election.end_date) }}</td>
              <td class="px-6 py-4 text-gray-600">{{ formatDate(election.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <a
                  v-if="canManage && election.slug"
                  :href="`/organisations/${organisation.slug}/elections/${election.slug}/posts`"
                  class="text-blue-600 hover:text-blue-800 font-medium text-sm"
                >
                  Manage →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </election-layout>
</template>

<script>
export default {
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
        active:    'bg-green-100 text-green-800',
        completed: 'bg-gray-100 text-gray-700',
        draft:     'bg-yellow-100 text-yellow-800',
        expired:   'bg-red-100 text-red-800',
      };
      return map[status] ?? 'bg-gray-100 text-gray-700';
    },
  },
};
</script>
