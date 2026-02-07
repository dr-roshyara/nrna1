<template>
  <div class="role-selection-dashboard min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
    <!-- Welcome Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ $t('pages.role-selection.roleSelection.welcome', { name: currentUser?.name || 'User' }) }}
        </h1>
        <p class="mt-2 text-gray-600">
          {{ $t('pages.role-selection.roleSelection.subtitle') }}
        </p>
      </div>
    </div>

    <!-- Role Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Admin Role Card -->
        <div
          v-if="availableRoles.includes('admin')"
          @click="selectRole('admin')"
          class="role-card admin-card bg-white rounded-lg shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-blue-500"
          role="button"
          tabindex="0"
          @keydown.enter="selectRole('admin')"
          :aria-label="$t('pages.role-selection.roleSelection.roles.admin.ariaLabel')"
        >
          <div class="p-6">
            <div class="text-4xl mb-4">👑</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
              {{ $t('pages.role-selection.roleSelection.roles.admin.title') }}
            </h3>
            <p class="text-gray-600 mb-4">
              {{ $t('pages.role-selection.roleSelection.roles.admin.description') }}
            </p>

            <div v-if="adminStats" class="mb-4 pb-4 border-b">
              <div class="text-sm text-gray-600">
                <p>{{ $t('pages.role-selection.roleSelection.stats.organizations') }}:
                  <span class="font-semibold">{{ adminStats.organizations }}</span>
                </p>
              </div>
            </div>

            <button
              @click.stop="selectRole('admin')"
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              {{ $t('pages.role-selection.roleSelection.buttons.goToAdmin') }}
            </button>
          </div>
        </div>

        <!-- Commission Role Card -->
        <div
          v-if="availableRoles.includes('commission')"
          @click="selectRole('commission')"
          class="role-card commission-card bg-white rounded-lg shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-purple-500"
          role="button"
          tabindex="0"
          @keydown.enter="selectRole('commission')"
          :aria-label="$t('pages.role-selection.roleSelection.roles.commission.ariaLabel')"
        >
          <div class="p-6">
            <div class="text-4xl mb-4">⚖️</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
              {{ $t('pages.role-selection.roleSelection.roles.commission.title') }}
            </h3>
            <p class="text-gray-600 mb-4">
              {{ $t('pages.role-selection.roleSelection.roles.commission.description') }}
            </p>

            <div v-if="commissionStats" class="mb-4 pb-4 border-b">
              <div class="text-sm text-gray-600">
                <p>{{ $t('pages.role-selection.roleSelection.stats.elections') }}:
                  <span class="font-semibold">{{ commissionStats.elections }}</span>
                </p>
              </div>
            </div>

            <button
              @click.stop="selectRole('commission')"
              class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors"
            >
              {{ $t('pages.role-selection.roleSelection.buttons.goToCommission') }}
            </button>
          </div>
        </div>

        <!-- Voter Role Card -->
        <div
          v-if="availableRoles.includes('voter')"
          @click="selectRole('voter')"
          class="role-card voter-card bg-white rounded-lg shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-green-500"
          role="button"
          tabindex="0"
          @keydown.enter="selectRole('voter')"
          :aria-label="$t('pages.role-selection.roleSelection.roles.voter.ariaLabel')"
        >
          <div class="p-6">
            <div class="text-4xl mb-4">👤</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
              {{ $t('pages.role-selection.roleSelection.roles.voter.title') }}
            </h3>
            <p class="text-gray-600 mb-4">
              {{ $t('pages.role-selection.roleSelection.roles.voter.description') }}
            </p>

            <div v-if="voterStats" class="mb-4 pb-4 border-b">
              <div class="text-sm text-gray-600">
                <p>{{ $t('pages.role-selection.roleSelection.stats.pendingVotes') }}:
                  <span class="font-semibold">{{ voterStats.pending }}</span>
                </p>
                <p>{{ $t('pages.role-selection.roleSelection.stats.castVotes') }}:
                  <span class="font-semibold">{{ voterStats.cast }}</span>
                </p>
              </div>
            </div>

            <button
              @click.stop="selectRole('voter')"
              :class="voterStats?.pending > 0
                ? 'w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors'
                : 'w-full px-4 py-2 bg-gray-400 text-white rounded-md cursor-default'"
            >
              {{ voterStats?.pending > 0
                ? $t('pages.role-selection.roleSelection.buttons.voteNow', { count: voterStats.pending })
                : $t('pages.role-selection.roleSelection.buttons.viewHistory')
              }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- No Roles Message -->
    <div
      v-if="availableRoles.length === 0"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
    >
      <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <p class="text-yellow-800">
          {{ $t('pages.role-selection.noRolesMessage') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useI18n } from 'vue-i18n'

const { t: $t } = useI18n()

defineProps({
  userName: String,
  userEmail: String,
  availableRoles: Array,
  adminStats: Object,
  commissionStats: Object,
  voterStats: Object,
  userOrganizations: Array,
})

// Create form instance for role switching
const roleForm = useForm({
  role: null
})

const selectRole = (role) => {
  roleForm.post(route('role.switch', { role }))
}
</script>

<style scoped>
.role-card {
  transition: all 0.3s ease;
}

.role-card:hover {
  transform: translateY(-2px);
}

.role-card:focus {
  outline: 3px solid #3b82f6;
  outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
  .role-card {
    transition: none;
  }

  .role-card:hover {
    transform: none;
  }
}
</style>
