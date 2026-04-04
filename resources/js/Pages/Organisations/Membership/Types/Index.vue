<template>
  <PublicDigitLayout>
    <div class="max-w-5xl mx-auto py-8 px-4">

      <!-- Flash messages -->
      <div v-if="page.props.flash?.success"
           class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ page.props.flash.success }}
      </div>

      <div v-if="page.props.flash?.error"
           class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ page.props.flash.error }}
      </div>

      <div v-if="page.props.errors?.error"
           class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
        {{ page.props.errors.error }}
      </div>

      <!-- Page header -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">{{ t.title }}</h1>
          <p class="text-sm text-gray-500 mt-1">{{ organisation.name }}</p>
        </div>
        <button
          @click="openCreate"
          class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          {{ t.create_btn }}
        </button>
      </div>

      <!-- Types table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_name }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_fee }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_duration }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_status }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_sort }}</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t.col_actions }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="type in types.data" :key="type.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ type.name }}</div>
                <div class="text-xs text-gray-400">{{ type.slug }}</div>
                <div v-if="type.description" class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ type.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ type.fee_amount }} {{ type.fee_currency }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ type.duration_months ? type.duration_months + ' ' + t.months : t.lifetime }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="type.is_active
                    ? 'bg-green-100 text-green-800'
                    : 'bg-gray-100 text-gray-500'"
                  class="px-2 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ type.is_active ? t.active : t.inactive }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ type.sort_order }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                <button
                  @click="openEdit(type)"
                  class="text-blue-600 hover:text-blue-900 mr-4 font-medium"
                >{{ t.edit }}</button>
                <button
                  @click="destroy(type)"
                  :disabled="deleting[type.id]"
                  class="text-red-600 hover:text-red-900 font-medium disabled:opacity-40"
                >
                  <span v-if="deleting[type.id]">…</span>
                  <span v-else>{{ t.delete }}</span>
                </button>
              </td>
            </tr>
            <tr v-if="types.data.length === 0">
              <td colspan="6" class="px-6 py-16 text-center text-gray-400 text-sm">
                {{ t.no_types }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="types.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-600">
          <span>{{ t.page }} {{ types.current_page }} / {{ types.last_page }}</span>
          <div class="flex gap-2">
            <a
              v-if="types.prev_page_url"
              :href="types.prev_page_url"
              class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50"
            >{{ t.prev }}</a>
            <a
              v-if="types.next_page_url"
              :href="types.next_page_url"
              class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50"
            >{{ t.next }}</a>
          </div>
        </div>
      </div>

    </div>

    <!-- ── Modal (Create / Edit) ──────────────────────────────────────────── -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h2 class="text-lg font-semibold text-gray-900">
            {{ isEditing ? t.modal_edit_title : t.modal_create_title }}
          </h2>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">

          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.field_name }} <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              @input="autoSlug"
              type="text"
              maxlength="100"
              required
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="formErrors.name ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="formErrors.name" class="mt-1 text-xs text-red-600">{{ formErrors.name }}</p>
          </div>

          <!-- Slug -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.field_slug }} <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.slug"
              type="text"
              maxlength="100"
              required
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
              :class="formErrors.slug ? 'border-red-400' : 'border-gray-300'"
            />
            <p class="mt-1 text-xs text-gray-400">{{ t.slug_hint }}</p>
            <p v-if="formErrors.slug" class="mt-1 text-xs text-red-600">{{ formErrors.slug }}</p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t.field_description }}</label>
            <textarea
              v-model="form.description"
              rows="2"
              class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Fee amount + currency -->
          <div class="grid grid-cols-3 gap-3">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ t.field_fee_amount }} <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.fee_amount"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="formErrors.fee_amount ? 'border-red-400' : 'border-gray-300'"
              />
              <p v-if="formErrors.fee_amount" class="mt-1 text-xs text-red-600">{{ formErrors.fee_amount }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ t.field_currency }} <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.fee_currency"
                type="text"
                maxlength="3"
                required
                placeholder="EUR"
                class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase font-mono"
                :class="formErrors.fee_currency ? 'border-red-400' : 'border-gray-300'"
              />
            </div>
          </div>

          <!-- Duration -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t.field_duration }}</label>
            <input
              v-model="form.duration_months"
              type="number"
              min="1"
              class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <p class="mt-1 text-xs text-gray-400">{{ t.duration_hint }}</p>
          </div>

          <!-- Sort order -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t.field_sort_order }}</label>
            <input
              v-model="form.sort_order"
              type="number"
              min="0"
              class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Toggles -->
          <div class="space-y-3 pt-1">
            <label class="flex items-center gap-3 cursor-pointer">
              <input v-model="form.requires_approval" type="checkbox" class="rounded border-gray-300 text-blue-600" />
              <span class="text-sm text-gray-700">{{ t.field_requires_approval }}</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" />
              <span class="text-sm text-gray-700">{{ t.field_is_active }}</span>
            </label>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50"
            >
              {{ t.cancel }}
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ saving ? t.saving : (isEditing ? t.save_changes : t.create_btn) }}
            </button>
          </div>

        </form>
      </div>
    </div>

  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  types:        { type: Object, required: true },
})

const page = usePage()

// ── i18n ──────────────────────────────────────────────────────────────────────

const { locale } = useI18n()

const translations = {
  en: {
    title: 'Membership Types',
    create_btn: 'New Type',
    col_name: 'Name', col_fee: 'Fee', col_duration: 'Duration',
    col_status: 'Status', col_sort: 'Order', col_actions: 'Actions',
    months: 'months', lifetime: 'Lifetime',
    active: 'Active', inactive: 'Inactive',
    edit: 'Edit', delete: 'Delete',
    no_types: 'No membership types yet. Click "New Type" to create the first one.',
    page: 'Page', prev: '← Prev', next: 'Next →',
    modal_create_title: 'Create Membership Type',
    modal_edit_title:   'Edit Membership Type',
    field_name: 'Name', field_slug: 'Slug',
    field_description: 'Description (optional)',
    field_fee_amount: 'Fee Amount', field_currency: 'Currency',
    field_duration: 'Duration (months)',
    field_sort_order: 'Sort Order',
    field_requires_approval: 'Requires admin approval before activating membership',
    field_is_active: 'Active (visible to new applicants)',
    slug_hint: 'Auto-generated from name. Must be unique within this organisation.',
    duration_hint: 'Leave blank for a lifetime membership with no expiry.',
    cancel: 'Cancel', save_changes: 'Save Changes', saving: 'Saving…',
    confirm_delete: 'Delete this membership type? This cannot be undone.',
  },
  de: {
    title: 'Mitgliedschaftstypen',
    create_btn: 'Neuer Typ',
    col_name: 'Name', col_fee: 'Gebühr', col_duration: 'Dauer',
    col_status: 'Status', col_sort: 'Reihenfolge', col_actions: 'Aktionen',
    months: 'Monate', lifetime: 'Lebenslang',
    active: 'Aktiv', inactive: 'Inaktiv',
    edit: 'Bearbeiten', delete: 'Löschen',
    no_types: 'Noch keine Mitgliedschaftstypen. Klicken Sie auf "Neuer Typ".',
    page: 'Seite', prev: '← Zurück', next: 'Weiter →',
    modal_create_title: 'Mitgliedschaftstyp erstellen',
    modal_edit_title:   'Mitgliedschaftstyp bearbeiten',
    field_name: 'Name', field_slug: 'Slug',
    field_description: 'Beschreibung (optional)',
    field_fee_amount: 'Gebührenbetrag', field_currency: 'Währung',
    field_duration: 'Dauer (Monate)',
    field_sort_order: 'Sortierung',
    field_requires_approval: 'Erfordert Admin-Genehmigung',
    field_is_active: 'Aktiv (für neue Bewerber sichtbar)',
    slug_hint: 'Automatisch aus dem Namen generiert. Muss innerhalb der Organisation eindeutig sein.',
    duration_hint: 'Leer lassen für eine lebenslange Mitgliedschaft.',
    cancel: 'Abbrechen', save_changes: 'Speichern', saving: 'Wird gespeichert…',
    confirm_delete: 'Diesen Mitgliedschaftstyp löschen? Dies kann nicht rückgängig gemacht werden.',
  },
  np: {
    title: 'सदस्यता प्रकारहरू',
    create_btn: 'नयाँ प्रकार',
    col_name: 'नाम', col_fee: 'शुल्क', col_duration: 'अवधि',
    col_status: 'स्थिति', col_sort: 'क्रम', col_actions: 'कार्यहरू',
    months: 'महिना', lifetime: 'आजीवन',
    active: 'सक्रिय', inactive: 'निष्क्रिय',
    edit: 'सम्पादन', delete: 'हटाउनुहोस्',
    no_types: 'अहिलेसम्म कुनै सदस्यता प्रकार छैन। "नयाँ प्रकार" थिच्नुहोस्।',
    page: 'पृष्ठ', prev: '← अघिल्लो', next: 'अर्को →',
    modal_create_title: 'सदस्यता प्रकार बनाउनुहोस्',
    modal_edit_title:   'सदस्यता प्रकार सम्पादन गर्नुहोस्',
    field_name: 'नाम', field_slug: 'स्लग',
    field_description: 'विवरण (ऐच्छिक)',
    field_fee_amount: 'शुल्क रकम', field_currency: 'मुद्रा',
    field_duration: 'अवधि (महिनामा)',
    field_sort_order: 'क्रम',
    field_requires_approval: 'प्रशासक अनुमोदन आवश्यक छ',
    field_is_active: 'सक्रिय (नयाँ आवेदकहरूलाई देखिने)',
    slug_hint: 'नामबाट स्वतः बनाइन्छ। संस्था भित्र अद्वितीय हुनुपर्छ।',
    duration_hint: 'आजीवन सदस्यताको लागि खाली छोड्नुहोस्।',
    cancel: 'रद्द गर्नुहोस्', save_changes: 'परिवर्तन सुरक्षित गर्नुहोस्', saving: 'सुरक्षित गर्दै…',
    confirm_delete: 'यो सदस्यता प्रकार मेटाउने? यो पूर्ववत् गर्न सकिँदैन।',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── Modal state ───────────────────────────────────────────────────────────────

const showModal  = ref(false)
const isEditing  = ref(false)
const saving     = ref(false)
const deleting   = reactive({})
const editingId  = ref(null)
const formErrors = computed(() => page.props.errors ?? {})

const blankForm = () => ({
  name:              '',
  slug:              '',
  description:       '',
  fee_amount:        '',
  fee_currency:      'EUR',
  duration_months:   '',
  requires_approval: true,
  is_active:         true,
  sort_order:        0,
})

const form = reactive(blankForm())

// ── Auto-slug from name ───────────────────────────────────────────────────────

const autoSlug = () => {
  if (isEditing.value) return // don't overwrite a saved slug on edit
  form.slug = form.name
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

// ── Open / close ──────────────────────────────────────────────────────────────

const openCreate = () => {
  Object.assign(form, blankForm())
  isEditing.value = false
  editingId.value = null
  showModal.value = true
}

const openEdit = (type) => {
  Object.assign(form, {
    name:              type.name,
    slug:              type.slug,
    description:       type.description ?? '',
    fee_amount:        type.fee_amount,
    fee_currency:      type.fee_currency,
    duration_months:   type.duration_months ?? '',
    requires_approval: type.requires_approval,
    is_active:         type.is_active,
    sort_order:        type.sort_order,
  })
  isEditing.value = true
  editingId.value = type.id
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

// ── Submit (create or update) ─────────────────────────────────────────────────

const submit = () => {
  saving.value = true

  const payload = {
    name:              form.name,
    slug:              form.slug,
    description:       form.description || null,
    fee_amount:        form.fee_amount,
    fee_currency:      form.fee_currency.toUpperCase(),
    duration_months:   form.duration_months !== '' ? form.duration_months : null,
    requires_approval: form.requires_approval,
    is_active:         form.is_active,
    sort_order:        form.sort_order,
  }

  if (isEditing.value) {
    router.put(
      route('organisations.membership-types.update', [props.organisation.slug, editingId.value]),
      payload,
      {
        preserveScroll: true,
        onSuccess: () => { closeModal() },
        onFinish:  () => { saving.value = false },
      }
    )
  } else {
    router.post(
      route('organisations.membership-types.store', props.organisation.slug),
      payload,
      {
        preserveScroll: true,
        onSuccess: () => { closeModal() },
        onFinish:  () => { saving.value = false },
      }
    )
  }
}

// ── Delete ────────────────────────────────────────────────────────────────────

const destroy = (type) => {
  if (!confirm(t.value.confirm_delete)) return

  deleting[type.id] = true

  router.delete(
    route('organisations.membership-types.destroy', [props.organisation.slug, type.id]),
    {
      preserveScroll: true,
      onFinish: () => { deleting[type.id] = false },
    }
  )
}
</script>
