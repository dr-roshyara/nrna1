<template>
  <section
    aria-labelledby="stats-heading"
    class="mb-8"
  >
    <h2
      id="stats-heading"
      class="text-xl font-semibold text-gray-900 mb-6"
    >
      {{ $t('pages.organisation-show.stats.title') }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Total Members Card -->
      <a :href="`/organisations/${organisationSlug}/members`" class="block bg-gradient-to-br from-blue-50 to-blue-100/30 rounded-xl shadow-sm p-6 hover:shadow-md hover:scale-[1.02] transition-all border border-blue-200 hover:border-blue-400 cursor-pointer no-underline">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.stats.total_members') }}
            </p>
            <p class="text-4xl font-bold text-gray-900 mt-2">
              {{ stats.members_count ?? 0 }}
            </p>
          </div>
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-blue-100 p-3">
            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
            </svg>
          </div>
        </div>
      </a>

      <!-- Active Members Card -->
      <a :href="`/organisations/${organisationSlug}/members`" class="block bg-gradient-to-br from-green-50 to-green-100/30 rounded-xl shadow-sm p-6 hover:shadow-md hover:scale-[1.02] transition-all border border-green-200 hover:border-green-400 cursor-pointer no-underline">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.stats.active_members') }}
            </p>
            <p class="text-4xl font-bold text-green-600 mt-2">
              {{ stats.active_members_count ?? 0 }}
            </p>
          </div>
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-green-100 p-3">
            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </a>

      <!-- Total Elections Card -->
      <a :href="`/organisations/${organisationSlug}/elections`" class="block bg-gradient-to-br from-purple-50 to-purple-100/30 rounded-xl shadow-sm p-6 hover:shadow-md hover:scale-[1.02] transition-all border border-purple-200 hover:border-purple-400 cursor-pointer no-underline">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.stats.total_elections') }}
            </p>
            <p class="text-4xl font-bold text-purple-600 mt-2">
              {{ stats.elections_count ?? 0 }}
            </p>
          </div>
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-purple-100 p-3">
            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2h2zm13 2a1 1 0 100 2h1v-2h-1z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </a>

      <!-- Active Elections Card -->
      <a :href="`/organisations/${organisationSlug}/elections?status=active`" class="block bg-gradient-to-br from-orange-50 to-orange-100/30 rounded-xl shadow-sm p-6 hover:shadow-md hover:scale-[1.02] transition-all border border-orange-200 hover:border-orange-400 cursor-pointer no-underline">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.stats.active_elections') }}
            </p>
            <p class="text-4xl font-bold text-orange-600 mt-2">
              {{ stats.active_elections_count ?? 0 }}
            </p>
          </div>
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-orange-100 p-3">
            <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M6 5a3 3 0 000 6 3 3 0 000-6zM1.477 10.816a4 4 0 016.046.281.75.75 0 00.527.205h.001a.75.75 0 00.528-.205 4 4 0 016.046-.281.75.75 0 10-1.06 1.06 2.5 2.5 0 01-3.537 0 .75.75 0 00-1.06 0 2.5 2.5 0 01-3.537 0.75.75.75 0 10-1.06-1.06zM1.477 15.816a4 4 0 016.046.281.75.75 0 00.527.205h.001a.75.75 0 00.528-.205 4 4 0 016.046-.281.75.75 0 10-1.06 1.06 2.5 2.5 0 01-3.537 0 .75.75 0 00-1.06 0 2.5 2.5 0 01-3.537 0 .75.75 0 10-1.06-1.06z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </a>

      <!-- Completed Elections (optional) -->
      <div v-if="stats.completed_elections !== undefined" class="bg-gradient-to-br from-indigo-50 to-indigo-100/30 rounded-xl shadow-sm p-6 hover:shadow-md transition-all border border-indigo-200 hover:border-indigo-300">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.stats.completed_elections') }}
            </p>
            <p class="text-4xl font-bold text-indigo-600 mt-2">
              {{ stats.completed_elections ?? 0 }}
            </p>
          </div>
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 p-3">
            <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M5 2a1 1 0 011-1h8a1 1 0 011 1v14a1 1 0 11-2 0V4H7v12a1 1 0 11-2 0V2z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
defineProps({
  organisationSlug: {
    type: String,
    required: true,
  },
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
