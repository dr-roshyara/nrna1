<template>
  <section
    aria-labelledby="stats-heading"
    class="mb-8"
  >
    <h2
      id="stats-heading"
      class="text-xl font-semibold text-gray-900 mb-6"
    >
      {{ $t('pages.organization-show.stats.title') }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Total Members Card -->
      <div class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.total_members') }}
            </p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              {{ stats.members_count ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-blue-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
          </svg>
        </div>
      </div>

      <!-- Active Members Card -->
      <div class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.active_members') }}
            </p>
            <p class="text-3xl font-bold text-green-600 mt-2">
              {{ stats.active_members_count ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-green-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <!-- Total Elections Card -->
      <div class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.total_elections') }}
            </p>
            <p class="text-3xl font-bold text-purple-600 mt-2">
              {{ stats.elections_count ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-purple-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2h2zm13 2a1 1 0 100 2h1v-2h-1z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <!-- Active Elections Card -->
      <div class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.active_elections') }}
            </p>
            <p class="text-3xl font-bold text-orange-600 mt-2">
              {{ stats.active_elections_count ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-orange-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M6 5a3 3 0 000 6 3 3 0 000-6zM1.477 10.816a4 4 0 016.046.281.75.75 0 00.527.205h.001a.75.75 0 00.528-.205 4 4 0 016.046-.281.75.75 0 10-1.06 1.06 2.5 2.5 0 01-3.537 0 .75.75 0 00-1.06 0 2.5 2.5 0 01-3.537 0.75.75.75 0 10-1.06-1.06zM1.477 15.816a4 4 0 016.046.281.75.75 0 00.527.205h.001a.75.75 0 00.528-.205 4 4 0 016.046-.281.75.75 0 10-1.06 1.06 2.5 2.5 0 01-3.537 0 .75.75 0 00-1.06 0 2.5 2.5 0 01-3.537 0 .75.75 0 10-1.06-1.06z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <!-- Completed Elections (optional) -->
      <div v-if="stats.completed_elections !== undefined" class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.completed_elections') }}
            </p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              {{ stats.completed_elections ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-gray-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M5 2a1 1 0 011-1h8a1 1 0 011 1v14a1 1 0 11-2 0V4H7v12a1 1 0 11-2 0V2z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <!-- New Members (30 days) -->
      <div v-if="stats.new_members_30d !== undefined" class="bg-white rounded-lg shadow-xs p-6 hover:shadow-md transition-shadow">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">
              {{ $t('pages.organization-show.stats.new_members_30d') }}
            </p>
            <p class="text-3xl font-bold text-blue-600 mt-2">
              {{ stats.new_members_30d ?? 0 }}
            </p>
          </div>
          <svg class="w-12 h-12 text-blue-100" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0015.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
          </svg>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      members_count: 0,
      active_members_count: 0,
      elections_count: 0,
      active_elections_count: 0,
      completed_elections: 0,
      new_members_30d: 0,
      exited_members_30d: 0
    })
  }
})
</script>
