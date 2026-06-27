<template>
  <ElectionLayout>
    <div role="status" aria-live="polite" class="sr-only">
      {{ t.title }} — {{ organisation.name }}
    </div>

    <main role="main" class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
          <Link :href="`/organisations/${organisation.slug}`"
                class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ t.back }}
          </Link>
          <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ t.title }}</h1>
          <p class="text-neutral-600">{{ t.description.replace('{organisation}', organisation.name) }}</p>
        </div>

        <!-- Flash success -->
        <div v-if="$page.props.flash?.success"
             class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-5 py-4 text-sm">
          <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ $page.props.flash.success }}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

          <!-- Main area -->
          <div class="lg:col-span-3">

            <!-- Invite Form Card -->
            <section class="bg-white rounded-lg shadow-xs p-6 mb-8">
              <h2 class="text-xl font-semibold text-neutral-900 mb-6">{{ t.form.title }}</h2>

              <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                  <!-- Email -->
                  <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">{{ t.form.email_label }}</label>
                    <input
                      v-model="form.email"
                      type="email"
                      :placeholder="t.form.email_placeholder"
                      class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      :class="{ 'border-danger-400': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="mt-1.5 text-xs text-danger-600">{{ form.errors.email }}</p>
                  </div>

                  <!-- Role -->
                  <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">{{ t.form.role_label }}</label>
                    <select
                      v-model="form.role"
                      class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                      <option v-for="role in allowedRoles" :key="role" :value="role">
                        {{ t.roles[role] ?? role }}
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Personal Message -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                    {{ t.form.message_label }}
                    <span class="text-neutral-400 font-normal">{{ t.form.message_optional }}</span>
                  </label>
                  <textarea
                    v-model="form.message"
                    rows="3"
                    :placeholder="t.form.message_placeholder"
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                  />
                </div>

                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
            </section>

            <!-- Pending Invitations -->
            <section class="bg-white rounded-lg shadow-xs overflow-hidden">
              <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-900">
                  {{ t.pending.title }}
                  <span class="ml-2 text-sm font-normal text-neutral-400">({{ pendingInvitations.length }})</span>
                </h2>
              </div>

              <div v-if="pendingInvitations.length === 0" class="px-6 py-12 text-center text-sm text-neutral-400">
                {{ t.pending.empty }}
              </div>

              <div v-else class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">
                      <th class="px-6 py-3 text-left">{{ t.pending.col_email }}</th>
                      <th class="px-6 py-3 text-left">{{ t.pending.col_role }}</th>
                      <th class="px-6 py-3 text-left">{{ t.pending.col_invited_by }}</th>
                      <th class="px-6 py-3 text-left">{{ t.pending.col_expires }}</th>
                      <th class="px-6 py-3 text-right">{{ t.pending.col_action }}</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                    <tr v-for="inv in pendingInvitations" :key="inv.id" class="hover:bg-neutral-50 transition-colors">
                      <td class="px-6 py-4 font-medium text-neutral-900">{{ inv.email }}</td>
                      <td class="px-6 py-4">
                        <span :class="roleBadgeClass(inv.role)" class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold">
                          {{ t.roles[inv.role] ?? inv.role }}
                        </span>
                      </td>
                      <td class="px-6 py-4 text-neutral-500">{{ inv.invited_by }}</td>
                      <td class="px-6 py-4 text-neutral-500">{{ inv.expires_at }}</td>
                      <td class="px-6 py-4 text-right">
                        <button
                          @click="cancelInvitation(inv.id)"
                          class="text-xs text-danger-600 hover:text-danger-800 font-medium transition-colors"
                        >
                          {{ t.pending.cancel }}
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>

          </div>

          <!-- Info panel -->
          <aside v-if="t.info_panel" class="lg:col-span-1" aria-label="Invitation information">
            <div class="bg-primary-50 rounded-lg p-6 border border-primary-200 sticky top-4">
              <h3 class="font-semibold text-neutral-900 mb-4">📋 {{ t.info_panel.title }}</h3>
              <div class="space-y-4 text-sm">

                <div>
                  <p class="font-medium text-neutral-900 mb-2">{{ t.info_panel.roles_heading }}</p>
                  <ul class="text-neutral-600 space-y-2">
                    <li v-for="item in t.info_panel.roles" :key="item">• {{ item }}</li>
                  </ul>
                </div>

                <div class="border-t border-primary-200 pt-4">
                  <p class="font-medium text-neutral-900 mb-2">{{ t.info_panel.process_heading }}</p>
                  <ol class="text-neutral-600 space-y-1 list-decimal list-inside">
                    <li v-for="step in t.info_panel.process" :key="step">{{ step }}</li>
                  </ol>
                </div>

                <div class="border-t border-primary-200 pt-4">
                  <p class="text-xs text-amber-700 bg-amber-50 rounded p-2">{{ t.info_panel.note }}</p>
                </div>

              </div>
            </div>
          </aside>

        </div>
      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router, useForm, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

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
    member:     'bg-neutral-100 text-neutral-700',
    admin:      'bg-primary-100 text-primary-700',
    commission: 'bg-amber-100 text-amber-700',
  }
  return classes[role] ?? 'bg-neutral-100 text-neutral-700'
}
</script>

