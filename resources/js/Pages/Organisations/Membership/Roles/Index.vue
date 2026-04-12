<template>
  <ElectionLayout>
    <main role="main" class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Flash -->
        <div v-if="$page.props.flash?.success"
             class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-5 py-4 text-sm">
          <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error"
             class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-5 py-4 text-sm">
          {{ $page.props.flash.error }}
        </div>

        <!-- Header -->
        <div class="mb-8">
          <Link :href="`/organisations/${organisation.slug}/membership`"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ t.back }}
          </Link>
          <h1 class="text-3xl font-bold text-gray-900">{{ t.title }}</h1>
          <p class="text-gray-500 mt-1 text-sm">{{ t.description.replace('{organisation}', organisation.name) }}</p>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_name }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_role }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_member_status }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_officer_assignments }}</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_actions }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="users.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">{{ t.empty }}</td>
              </tr>
              <tr v-for="u in users" :key="u.user_id" class="hover:bg-slate-50 transition-colors">

                <!-- Name / email -->
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ u.name }}</div>
                  <div class="text-xs text-gray-400">{{ u.email }}</div>
                </td>

                <!-- Org role badge -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="roleBadgeClass(u.role)" class="px-2.5 py-1 rounded-full text-xs font-medium">
                    {{ roleLabel(u.role) }}
                  </span>
                </td>

                <!-- Member status -->
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span v-if="u.is_member" class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ t.is_member }}
                  </span>
                  <span v-else class="text-amber-600">○ {{ t.not_member }}</span>
                </td>

                <!-- Officer assignments -->
                <td class="px-6 py-4 text-sm">
                  <div v-if="u.officer_assignments.length === 0" class="text-gray-400 text-xs">{{ t.no_assignments }}</div>
                  <div v-else class="space-y-1">
                    <div v-for="a in u.officer_assignments" :key="a.election_id"
                         class="flex items-center gap-2 flex-wrap">
                      <span :class="officerRoleBadge(a.role)" class="px-2 py-0.5 rounded text-xs font-medium">
                        {{ a.role }}
                      </span>
                      <span class="text-xs text-gray-600 truncate max-w-[160px]" :title="a.election_name">
                        {{ a.election_name }}
                      </span>
                      <button @click="removeOfficer(u.user_id, a.election_id)"
                              class="text-red-400 hover:text-red-600 text-xs transition-colors ml-1">
                        ✕
                      </button>
                    </div>
                  </div>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right space-y-1">
                  <div>
                    <button
                      v-if="!u.is_member"
                      @click="addAsMember(u.user_id)"
                      class="text-purple-600 hover:text-purple-800 text-sm font-medium transition-colors block w-full text-right"
                    >
                      {{ t.add_as_member }}
                    </button>
                    <span v-else class="text-slate-400 text-xs block text-right">{{ t.already_member }}</span>
                  </div>
                  <div>
                    <button
                      @click="openAssignModal(u)"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors block w-full text-right"
                    >
                      {{ t.assign_officer }}
                    </button>
                  </div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </main>

    <!-- Assign Officer Modal -->
    <Teleport to="body">
      <div v-if="modal.open"
           class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
           @click.self="modal.open = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

          <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ t.modal_title }}</h2>
          <p class="text-sm text-gray-500 mb-5">{{ modal.user?.name }}</p>

          <div v-if="elections.length === 0" class="text-sm text-amber-700 bg-amber-50 rounded p-3 mb-4">
            {{ t.no_elections }}
          </div>

          <template v-else>
            <!-- Election picker -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ t.modal_select_election }}</label>
              <select v-model="modal.election_id"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="" disabled>— select —</option>
                <option v-for="e in elections" :key="e.id" :value="e.id">
                  {{ e.name }} <span class="text-gray-400">({{ e.status }})</span>
                </option>
              </select>
            </div>

            <!-- Role picker -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ t.modal_select_role }}</label>
              <div class="space-y-2">
                <label v-for="opt in roleOptions" :key="opt.value"
                       class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                       :class="modal.role === opt.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'">
                  <input type="radio" :value="opt.value" v-model="modal.role" class="mt-0.5 accent-blue-600"/>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ opt.label }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ opt.desc }}</div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
              <button @click="modal.open = false"
                      class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 font-medium transition-colors">
                {{ t.modal_cancel }}
              </button>
              <button @click="submitAssign"
                      :disabled="!modal.election_id || !modal.role"
                      class="px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors">
                {{ t.modal_assign }}
              </button>
            </div>
          </template>

        </div>
      </div>
    </Teleport>

  </ElectionLayout>
</template>

<script setup>
import { computed, reactive } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

import pageDe from '@/locales/pages/Organisations/Membership/Roles/Index/de.json'
import pageEn from '@/locales/pages/Organisations/Membership/Roles/Index/en.json'
import pageNp from '@/locales/pages/Organisations/Membership/Roles/Index/np.json'

const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

const props = defineProps({
  organisation: { type: Object, required: true },
  users:        { type: Array,  default: () => [] },
  elections:    { type: Array,  default: () => [] },
})

const modal = reactive({ open: false, user: null, election_id: '', role: 'deputy' })

const roleOptions = computed(() => [
  { value: 'chief',        label: 'Chief',        desc: t.value.modal_role_chief },
  { value: 'deputy',       label: 'Deputy',       desc: t.value.modal_role_deputy },
  { value: 'commissioner', label: 'Commissioner', desc: t.value.modal_role_commissioner },
])

function openAssignModal(user) {
  modal.user        = user
  modal.election_id = ''
  modal.role        = 'deputy'
  modal.open        = true
}

function submitAssign() {
  router.post(`/organisations/${props.organisation.slug}/membership/roles/assign-officer`, {
    user_id:     modal.user.user_id,
    election_id: modal.election_id,
    role:        modal.role,
  }, {
    preserveScroll: true,
    onSuccess: () => { modal.open = false },
  })
}

function addAsMember(userId) {
  if (!confirm(t.value.confirm_add)) return
  router.post(`/organisations/${props.organisation.slug}/membership/roles/add-member`, {
    user_id: userId,
  }, { preserveScroll: true })
}

function removeOfficer(userId, electionId) {
  if (!confirm(t.value.confirm_remove_officer)) return
  router.post(`/organisations/${props.organisation.slug}/membership/roles/remove-officer`, {
    user_id:     userId,
    election_id: electionId,
  }, { preserveScroll: true })
}

function roleLabel(role) {
  const map = {
    owner: t.value.role_owner, admin: t.value.role_admin,
    commission: t.value.role_commission, voter: t.value.role_voter, member: t.value.role_member,
  }
  return map[role] ?? role
}

function roleBadgeClass(role) {
  return {
    owner:      'bg-purple-100 text-purple-700',
    admin:      'bg-blue-100 text-blue-700',
    commission: 'bg-yellow-100 text-yellow-700',
    voter:      'bg-green-100 text-green-700',
    member:     'bg-slate-100 text-slate-600',
  }[role] ?? 'bg-gray-100 text-gray-700'
}

function officerRoleBadge(role) {
  return {
    chief:        'bg-red-100 text-red-700',
    deputy:       'bg-orange-100 text-orange-700',
    commissioner: 'bg-sky-100 text-sky-700',
  }[role] ?? 'bg-gray-100 text-gray-700'
}
</script>
