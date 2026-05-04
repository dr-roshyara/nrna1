<template>
  <div>
    <div v-if="committees && committees.length > 0" class="space-y-4">
      <div
        v-for="committee in committees"
        :key="committee.id"
        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">{{ committee.name }}</h3>
            <p class="text-sm text-gray-600 mt-1">Code: <strong>{{ committee.code }}</strong></p>
            <p v-if="committee.geo_reference" class="text-sm text-gray-600">
              Geography: <strong>{{ committee.geo_reference }}</strong>
            </p>
            <div class="mt-3 flex items-center gap-3">
              <span
                :class="[
                  'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                  committee.status === 'active'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-gray-100 text-gray-800'
                ]"
              >
                {{ committee.status }}
              </span>
              <span class="text-xs text-gray-500">
                {{ committee.members_count || 0 }} member{{ (committee.members_count || 0) !== 1 ? 's' : '' }}
              </span>
            </div>
          </div>
          <div class="flex gap-2 ml-4">
            <a
              :href="`/organisations/${organisationSlug}/committees/${committee.id}/dashboard`"
              class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              View
            </a>
            <a
              :href="`/organisations/${organisationSlug}/committees/${committee.id}/edit`"
              class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
            </a>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-8">
      <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
      <p class="text-gray-500 font-medium">No committees at this level yet</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  committees: {
    type: Array,
    default: () => [],
  },
  level: {
    type: String,
    required: true,
  },
  organisationSlug: {
    type: String,
    required: true,
  },
});
</script>

<style scoped>
/* Component styles here */
</style>
