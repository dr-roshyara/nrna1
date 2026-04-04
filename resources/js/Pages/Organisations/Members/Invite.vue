<template>
  <div class="min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 py-10">

      <!-- Page Header -->
      <div class="mb-8">
        <Link :href="`/organisations/${organisation.slug}`"
              class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-4 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          {{ t.back }}
        </Link>
        <h1 class="text-2xl font-bold text-slate-900">{{ t.title }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ t.description.replace('{organisation}', organisation.name) }}</p>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash?.success"
           class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 text-sm">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ $page.props.flash.success }}
      </div>

      <!-- Invite Form Card -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 mb-8">
        <h2 class="text-base font-semibold text-slate-800 mb-6">{{ t.form.title }}</h2>

        <form @submit.prevent="submit">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ t.form.email_label }}</label>
              <input
                v-model="form.email"
                type="email"
                :placeholder="t.form.email_placeholder"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                :class="{ 'border-red-400': form.errors.email }"
              />
              <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Role -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ t.form.role_label }}</label>
              <select
                v-model="form.role"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              >
                <option v-for="role in allowedRoles" :key="role" :value="role">
                  {{ t.roles[role] ?? role }}
                </option>
              </select>
            </div>
          </div>

          <!-- Personal Message -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-1.5">
              {{ t.form.message_label }}
              <span class="text-slate-400 font-normal">{{ t.form.message_optional }}</span>
            </label>
            <textarea
              v-model="form.message"
              rows="3"
              :placeholder="t.form.message_placeholder"
              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
            />
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors"
          >
            <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            {{ form.processing ? t.form.submitting : t.form.submit }}
          </button>
        </form>
      </div>

      <!-- Pending Invitations Table -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
          <h2 class="text-base font-semibold text-slate-800">
            {{ t.pending.title }}
            <span class="ml-2 text-xs font-normal text-slate-400">({{ pendingInvitations.length }})</span>
          </h2>
        </div>

        <div v-if="pendingInvitations.length === 0" class="px-8 py-12 text-center text-sm text-slate-400">
          {{ t.pending.empty }}
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider">
              <th class="px-6 py-3 text-left">{{ t.pending.col_email }}</th>
              <th class="px-6 py-3 text-left">{{ t.pending.col_role }}</th>
              <th class="px-6 py-3 text-left">{{ t.pending.col_invited_by }}</th>
              <th class="px-6 py-3 text-left">{{ t.pending.col_expires }}</th>
              <th class="px-6 py-3 text-right">{{ t.pending.col_action }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="inv in pendingInvitations" :key="inv.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-4 font-medium text-slate-800">{{ inv.email }}</td>
              <td class="px-6 py-4">
                <span :class="roleBadgeClass(inv.role)" class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold">
                  {{ t.roles[inv.role] ?? inv.role }}
                </span>
              </td>
              <td class="px-6 py-4 text-slate-500">{{ inv.invited_by }}</td>
              <td class="px-6 py-4 text-slate-500">{{ inv.expires_at }}</td>
              <td class="px-6 py-4 text-right">
                <button
                  @click="cancelInvitation(inv.id)"
                  class="text-xs text-red-600 hover:text-red-800 font-medium transition-colors"
                >
                  {{ t.pending.cancel }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router, useForm, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

import pageDe from '@/locales/pages/Organisations/Members/Invite/de.json'
import pageEn from '@/locales/pages/Organisations/Members/Invite/en.json'
import pageNp from '@/locales/pages/Organisations/Members/Invite/np.json'

const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

const props = defineProps({
  organisation:       { type: Object, required: true },
  pendingInvitations: { type: Array,  default: () => [] },
  allowedRoles:       { type: Array,  default: () => ['member', 'admin', 'commission'] },
})

const form = useForm({
  email:   '',
  role:    'member',
  message: '',
})

function submit() {
  form.post(`/organisations/${props.organisation.slug}/members/invite`, {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}

function cancelInvitation(id) {
  router.delete(`/organisations/${props.organisation.slug}/members/invitations/${id}`, {
    preserveScroll: true,
  })
}

function roleBadgeClass(role) {
  const classes = {
    member:     'bg-slate-100 text-slate-700',
    admin:      'bg-blue-100 text-blue-700',
    commission: 'bg-amber-100 text-amber-700',
  }
  return classes[role] ?? 'bg-slate-100 text-slate-700'
}
</script>
