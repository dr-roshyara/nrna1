<template>
  <ElectionLayout>
    <main role="main" class="py-12 bg-gray-50 min-h-screen">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back link -->
        <Link
          :href="route('organisations.show', organisation.slug)"
          class="inline-flex items-center text-amber-600 hover:text-amber-700 mb-6 text-sm font-medium"
        >
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          {{ $t('pages.organisation-show.election_officers.back_link') }}
        </Link>

        <!-- Page header -->
        <div class="mb-8">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">
                {{ $t('pages.organisation-show.election_officers.page_title') }}
              </h1>
              <p class="text-sm text-amber-700 font-medium">
                {{ $t('pages.organisation-show.election_officers.page_subtitle') }}
              </p>
            </div>
          </div>
        </div>

        <!-- Flash message -->
        <div
          v-if="$page.props.flash?.success"
          class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3"
        >
          <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span class="text-green-800 text-sm font-medium">{{ $page.props.flash.success }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <!-- Officers list -->
          <div class="lg:col-span-2 space-y-4">
            <h2 class="text-base font-semibold text-gray-700">
              {{ $t('pages.organisation-show.election_officers.current_officers') }}
            </h2>

            <div v-if="!officers.length" class="bg-white rounded-xl border border-gray-200 p-8 text-center">
              <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                  />
                </svg>
              </div>
              <p class="text-gray-500 text-sm">{{ $t('pages.organisation-show.election_officers.no_officers') }}</p>
              <p class="text-gray-400 text-xs mt-1">{{ $t('pages.organisation-show.election_officers.no_officers_hint') }}</p>
            </div>

            <div
              v-for="officer in officers"
              :key="officer.id"
              class="bg-white rounded-xl border border-gray-200 p-5 flex items-start justify-between gap-4"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <span class="text-gray-600 font-semibold text-sm">{{ officer.user_name.charAt(0).toUpperCase() }}</span>
                </div>
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-gray-900 truncate">{{ officer.user_name }}</p>
                  <p class="text-xs text-gray-500 truncate">{{ officer.user_email }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="roleBadgeClass(officer.role)">
                      {{ $t(`pages.organisation-show.election_officers.role_${officer.role}`) }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="statusBadgeClass(officer.status)">
                      {{ officer.status }}
                    </span>
                  </div>
                  <p v-if="officer.election_name" class="text-xs text-blue-600 font-medium mt-1">
                    For: {{ officer.election_name }}
                  </p>
                  <p v-else class="text-xs text-gray-400 mt-1 italic">Org-wide</p>
                  <p v-if="officer.appointed_at" class="text-xs text-gray-400 mt-1">
                    {{ $t('pages.organisation-show.election_officers.appointed_on', { date: officer.appointed_at }) }}
                    <span v-if="officer.appointed_by">
                      {{ $t('pages.organisation-show.election_officers.appointed_by', { name: officer.appointed_by }) }}
                    </span>
                  </p>
                </div>
              </div>

              <!-- Accept button (own pending) -->
              <button
                v-if="officer.status === 'pending' && officer.user_id === currentUserId"
                @click="acceptAppointment(officer.id)"
                :disabled="accepting === officer.id"
                class="flex-shrink-0 bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
              >
                {{ accepting === officer.id
                  ? $t('pages.organisation-show.election_officers.accepting')
                  : $t('pages.organisation-show.election_officers.accept_button') }}
              </button>

              <!-- Remove button (admin only) -->
              <button
                v-else-if="canManage"
                @click="removeOfficer(officer.id)"
                :disabled="removing === officer.id"
                class="flex-shrink-0 text-red-400 hover:text-red-600 disabled:opacity-50 text-xs font-medium transition-colors"
              >
                {{ removing === officer.id
                  ? $t('pages.organisation-show.election_officers.removing')
                  : $t('pages.organisation-show.election_officers.remove_button') }}
              </button>
            </div>
          </div>

          <!-- Appoint form -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-amber-200 p-5 sticky top-6">
              <h2 class="text-sm font-semibold text-gray-700 mb-4">
                {{ $t('pages.organisation-show.election_officers.appoint_new') }}
              </h2>

              <form @submit.prevent="appoint" v-if="canManage">

                <!-- Member search -->
                <div class="mb-4 relative">
                  <label class="block text-xs font-medium text-gray-600 mb-1" for="officer-search">
                    {{ $t('pages.organisation-show.election_officers.member_label') }}
                  </label>
                  <input
                    id="officer-search"
                    v-model="search"
                    type="text"
                    :placeholder="$t('pages.organisation-show.election_officers.search_placeholder')"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                    autocomplete="off"
                    @focus="dropdownOpen = true"
                    @blur="dropdownOpen = false"
                  />
                  <ul
                    v-if="dropdownOpen && filteredMembers.length"
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-40 overflow-y-auto"
                  >
                    <li
                      v-for="m in filteredMembers"
                      :key="m.id"
                      @mousedown.prevent="selectMember(m)"
                      class="px-3 py-2 text-sm hover:bg-amber-50 cursor-pointer"
                    >
                      <span class="font-medium">{{ m.name }}</span>
                      <span class="text-gray-400 ml-1 text-xs">{{ m.email }}</span>
                    </li>
                  </ul>
                  <p v-if="selectedMember" class="mt-1 text-xs text-amber-700 font-medium">✓ {{ selectedMember.name }}</p>
                  <p v-if="errors.user_id" class="mt-1 text-xs text-red-600">{{ errors.user_id }}</p>
                </div>

                <!-- Election (optional) -->
                <div class="mb-4">
                  <label class="block text-xs font-medium text-gray-600 mb-1" for="officer-election">
                    For election <span class="text-gray-400">(leave blank for org-wide)</span>
                  </label>
                  <select
                    id="officer-election"
                    v-model="form.election_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                  >
                    <option value="">— Organisation-wide —</option>
                    <option v-for="e in elections" :key="e.id" :value="e.id">{{ e.name }}</option>
                  </select>
                  <p v-if="errors.election_id" class="mt-1 text-xs text-red-600">{{ errors.election_id }}</p>
                </div>

                <!-- Role -->
                <div class="mb-4">
                  <label class="block text-xs font-medium text-gray-600 mb-2">
                    {{ $t('pages.organisation-show.election_officers.role_label') }}
                  </label>
                  <div class="space-y-2">
                    <label
                      v-for="r in roles"
                      :key="r.value"
                      class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer transition-all"
                      :class="form.role === r.value ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-300'"
                    >
                      <input type="radio" :value="r.value" v-model="form.role" class="accent-amber-500" />
                      <div>
                        <p class="text-xs font-semibold text-gray-800">{{ r.label }}</p>
                        <p class="text-xs text-gray-400">{{ r.desc }}</p>
                      </div>
                    </label>
                  </div>
                  <p v-if="errors.role" class="mt-1 text-xs text-red-600">{{ errors.role }}</p>
                </div>

                <button
                  type="submit"
                  :disabled="submitting || !selectedMember"
                  class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors"
                >
                  {{ submitting
                    ? $t('pages.organisation-show.election_officers.appointing')
                    : $t('pages.organisation-show.election_officers.appoint_button') }}
                </button>
              </form>

              <p v-else class="text-xs text-gray-400">
                {{ $t('pages.organisation-show.election_officers.no_permission') }}
              </p>
            </div>
          </div>

        </div>
      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const { t } = useI18n()

const props = defineProps({
  organisation: { type: Object, required: true },
  officers:     { type: Array,  default: () => [] },
  orgMembers:   { type: Array,  default: () => [] },
  elections:    { type: Array,  default: () => [] },
  canManage:    { type: Boolean, default: false },
})

const page          = usePage()
const currentUserId = computed(() => page.props.auth?.user?.id)

const search         = ref('')
const dropdownOpen   = ref(false)
const selectedMember = ref(null)
const form           = ref({ role: 'commissioner', election_id: '' })
const submitting     = ref(false)
const removing       = ref(null)
const accepting      = ref(null)
const errors         = ref({})

const roles = computed(() => [
  { value: 'chief',        label: t('pages.organisation-show.election_officers.role_chief'),        desc: t('pages.organisation-show.election_officers.role_chief_desc') },
  { value: 'deputy',       label: t('pages.organisation-show.election_officers.role_deputy'),       desc: t('pages.organisation-show.election_officers.role_deputy_desc') },
  { value: 'commissioner', label: t('pages.organisation-show.election_officers.role_commissioner'), desc: t('pages.organisation-show.election_officers.role_commissioner_desc') },
])

const filteredMembers = computed(() => {
  if (! search.value) return []
  const q = search.value.toLowerCase()
  return props.orgMembers.filter(
    m => m.name.toLowerCase().includes(q) || m.email.toLowerCase().includes(q)
  )
})

function selectMember(member) {
  selectedMember.value = member
  search.value         = member.name
  dropdownOpen.value   = false
}

function roleBadgeClass(role) {
  return { chief: 'bg-purple-100 text-purple-700', deputy: 'bg-blue-100 text-blue-700', commissioner: 'bg-gray-100 text-gray-700' }[role] ?? 'bg-gray-100 text-gray-600'
}

function statusBadgeClass(status) {
  return { active: 'bg-green-100 text-green-700', pending: 'bg-yellow-100 text-yellow-700', inactive: 'bg-gray-100 text-gray-500', resigned: 'bg-red-100 text-red-600' }[status] ?? 'bg-gray-100 text-gray-500'
}

function appoint() {
  if (! selectedMember.value) return
  errors.value     = {}
  submitting.value = true

  router.post(
    route('organisations.election-officers.store', props.organisation.slug),
    { user_id: selectedMember.value.id, role: form.value.role, election_id: form.value.election_id || null },
    {
      preserveScroll: true,
      onSuccess: () => {
        selectedMember.value   = null
        search.value           = ''
        form.value.role        = 'commissioner'
        form.value.election_id = ''
      },
      onError:  (e) => { errors.value = e },
      onFinish: () => { submitting.value = false },
    }
  )
}

function acceptAppointment(officerId) {
  accepting.value = officerId
  router.post(
    route('organisations.election-officers.accept', {
      organisation: props.organisation.slug,
      officer:      officerId,
    }),
    {},
    {
      preserveScroll: true,
      onFinish: () => { accepting.value = null },
    }
  )
}

function removeOfficer(officerId) {
  removing.value = officerId
  router.delete(
    route('organisations.election-officers.destroy', {
      organisation: props.organisation.slug,
      officer:      officerId,
    }),
    {
      preserveScroll: true,
      onFinish: () => { removing.value = null },
    }
  )
}
</script>
