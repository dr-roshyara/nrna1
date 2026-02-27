<template>
  <election-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
          <!-- Title -->
          <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">
            {{ $t('pages.role-selection.roleSelection.welcome_short') }}
          </h2>
          <p class="text-center text-gray-600 mb-8">
            {{ $t('pages.role-selection.roleSelection.select_role_subtitle') }}
          </p>

          <!-- Role Buttons -->
          <div class="space-y-3">
            <!-- Admin Role Button -->
            <button
              v-if="availableRoles.includes('admin')"
              @click="selectRole('admin')"
              class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center justify-center"
              :aria-label="$t('pages.role-selection.roleSelection.roles.admin.ariaLabel')"
            >
              <span class="mr-2">👑</span>
              {{ $t('pages.role-selection.roleSelection.roles.admin.title') }}
            </button>

            <!-- Commission Role Button -->
            <button
              v-if="availableRoles.includes('commission')"
              @click="selectRole('commission')"
              class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-semibold flex items-center justify-center"
              :aria-label="$t('pages.role-selection.roleSelection.roles.commission.ariaLabel')"
            >
              <span class="mr-2">⚖️</span>
              {{ $t('pages.role-selection.roleSelection.roles.commission.title') }}
            </button>

            <!-- Voter Role Button -->
            <button
              v-if="availableRoles.includes('voter')"
              @click="selectRole('voter')"
              class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold flex items-center justify-center"
              :aria-label="$t('pages.role-selection.roleSelection.roles.voter.ariaLabel')"
            >
              <span class="mr-2">👤</span>
              {{ $t('pages.role-selection.roleSelection.roles.voter.title') }}
            </button>
          </div>

          <!-- No Roles Message -->
          <div v-if="availableRoles.length === 0" class="mt-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-sm">
              <p class="text-yellow-800 text-sm">
                {{ $t('pages.role-selection.noRolesMessage') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </election-layout>
</template>

<script setup>
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

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
button {
  transition: all 0.2s ease;
}

button:focus {
  outline: 3px solid #3b82f6;
  outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
  button {
    transition: none;
  }
}
</style>
