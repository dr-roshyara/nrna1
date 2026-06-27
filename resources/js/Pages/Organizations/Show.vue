<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Header -->
    <div class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold text-neutral-900 dark:text-white">
              {{ organization.name }}
            </h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-2">
              {{ $t('organization.dashboard.subtitle', {
                fallback: 'Verwalten Sie Ihre Organisation, Mitglieder und Wahlen'
              }) }}
            </p>
          </div>
          <button
            @click="goBack"
            class="text-primary-600 hover:text-primary-700 font-medium transition-colors"
          >
            ← {{ $t('common.back', { fallback: 'Zurück' }) }}
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Welcome Card -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-8 mb-8 border-l-4 border-primary-600">
        <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">
          🎉 {{ $t('organization.dashboard.welcome', { fallback: 'Willkommen!' }) }}
        </h2>
        <p class="text-neutral-700 dark:text-neutral-300 mb-6">
          {{ $t('organization.dashboard.welcome_message', {
            fallback: 'Ihre Organisation wurde erfolgreich erstellt. Folgen Sie den Schritten unten, um loszulegen.'
          }) }}
        </p>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Members Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-neutral-600 dark:text-neutral-400 text-sm">
                {{ $t('organization.dashboard.members', { fallback: 'Mitglieder' }) }}
              </p>
              <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">
                {{ stats.members_count }}
              </p>
            </div>
            <div class="text-4xl">👥</div>
          </div>
        </div>

        <!-- Elections Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-neutral-600 dark:text-neutral-400 text-sm">
                {{ $t('organization.dashboard.elections', { fallback: 'Wahlen' }) }}
              </p>
              <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">
                {{ stats.elections_count }}
              </p>
            </div>
            <div class="text-4xl">🗳️</div>
          </div>
        </div>

        <!-- Created Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-neutral-600 dark:text-neutral-400 text-sm">
                {{ $t('organization.dashboard.created', { fallback: 'Erstellt am' }) }}
              </p>
              <p class="text-lg font-bold text-neutral-900 dark:text-white mt-2">
                {{ organization.created_at }}
              </p>
            </div>
            <div class="text-4xl">📅</div>
          </div>
        </div>
      </div>

      <!-- Next Steps -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-8">
        <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">
          {{ $t('organization.dashboard.next_steps', { fallback: 'Nächste Schritte' }) }}
        </h3>

        <div class="space-y-4">
          <!-- Step 1: Invite Members -->
          <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 rounded-lg">
            <div class="flex-shrink-0">
              <div class="flex items-center justify-center h-10 w-10 rounded-full bg-primary-600 text-white font-bold">
                1
              </div>
            </div>
            <div class="flex-1">
              <h4 class="font-semibold text-neutral-900 dark:text-white">
                {{ $t('organization.dashboard.step_invite', {
                  fallback: 'Mitglieder einladen'
                }) }}
              </h4>
              <p class="text-neutral-700 dark:text-neutral-300 mt-1 text-sm">
                {{ $t('organization.dashboard.step_invite_desc', {
                  fallback: 'Laden Sie Ihre Mitglieder und Wahlberechtigten ins System ein'
                }) }}
              </p>
              <button class="mt-3 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                {{ $t('common.start', { fallback: 'Starten' }) }}
              </button>
            </div>
          </div>

          <!-- Step 2: Create Election -->
          <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-green-50 to-transparent dark:from-green-900/20 rounded-lg">
            <div class="flex-shrink-0">
              <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-600 text-white font-bold">
                2
              </div>
            </div>
            <div class="flex-1">
              <h4 class="font-semibold text-neutral-900 dark:text-white">
                {{ $t('organization.dashboard.step_election', {
                  fallback: 'Erste Wahl erstellen'
                }) }}
              </h4>
              <p class="text-neutral-700 dark:text-neutral-300 mt-1 text-sm">
                {{ $t('organization.dashboard.step_election_desc', {
                  fallback: 'Richten Sie Ihre erste Abstimmung ein – mit Kandidaten und Wahlterminen'
                }) }}
              </p>
              <button class="mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                {{ $t('common.start', { fallback: 'Starten' }) }}
              </button>
            </div>
          </div>

          <!-- Step 3: Setup Commission -->
          <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 rounded-lg">
            <div class="flex-shrink-0">
              <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-600 text-white font-bold">
                3
              </div>
            </div>
            <div class="flex-1">
              <h4 class="font-semibold text-neutral-900 dark:text-white">
                {{ $t('organization.dashboard.step_commission', {
                  fallback: 'Wahlkommission einrichten'
                }) }}
              </h4>
              <p class="text-neutral-700 dark:text-neutral-300 mt-1 text-sm">
                {{ $t('organization.dashboard.step_commission_desc', {
                  fallback: 'Weisen Sie Moderatoren und Wahlvorsteher zu'
                }) }}
              </p>
              <button class="mt-3 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                {{ $t('common.start', { fallback: 'Starten' }) }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Organization Info -->
      <div class="mt-8 bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">
          {{ $t('organization.dashboard.org_info', { fallback: 'Organisationsinformation' }) }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $t('common.name', { fallback: 'Name' }) }}</p>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">{{ organization.name }}</p>
          </div>
          <div>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $t('common.email', { fallback: 'E-Mail' }) }}</p>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">{{ organization.email }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  organization: Object,
  stats: Object,
});

const goBack = () => {
  window.history.back();
};
</script>

