<template>
  <!-- Backdrop -->
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="modal-backdrop" @click.self="$emit('close')">
        <div class="modal-box" role="dialog" aria-modal="true" :aria-label="t.title">

          <!-- Header -->
          <div class="modal-header">
            <div class="modal-header__icon">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </div>
            <h2 class="modal-header__title">{{ t.title }}</h2>
            <button class="modal-header__close" @click="$emit('close')" :aria-label="t.cancel">×</button>
          </div>

          <!-- Eligibility guard -->
          <div v-if="!canRenew" class="ineligible-banner">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <span>{{ t.ineligible }}</span>
          </div>

          <!-- Member info card -->
          <div class="member-card">
            <div class="member-card__row">
              <span class="member-card__label">{{ t.currentStatus }}</span>
              <span :class="['status-badge', `status-badge--${member.status}`]">{{ member.status }}</span>
            </div>
            <div v-if="member.expires_at" class="member-card__row">
              <span class="member-card__label">{{ t.expiresAt }}</span>
              <span class="member-card__value" :class="{ 'text-red-600': isExpired }">
                {{ formatDate(member.expires_at) }}
                <span v-if="isExpired" class="ml-1 text-xs font-medium text-red-600">({{ t.expired }})</span>
              </span>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="modal-form">

            <!-- Type selector — only shown when multiple types available -->
            <div v-if="types && types.length > 1" class="form-field">
              <label class="form-label" for="renewal-type">{{ t.membershipType }}</label>
              <select
                id="renewal-type"
                v-model="form.membership_type_id"
                class="form-select"
                :disabled="submitting || !canRenew"
                required
              >
                <option value="" disabled>{{ t.selectType }}</option>
                <option v-for="type in types" :key="type.id" :value="type.id">
                  {{ type.name }}
                  <template v-if="type.fee_amount">
                    — {{ type.fee_amount }} {{ type.fee_currency ?? 'EUR' }}
                  </template>
                </option>
              </select>
              <p v-if="formErrors.membership_type_id" class="form-error">{{ formErrors.membership_type_id }}</p>
            </div>

            <!-- Auto-select when only one type -->
            <div v-else-if="types && types.length === 1" class="type-auto-info">
              <span class="form-label">{{ t.membershipType }}</span>
              <span class="type-auto-info__name">{{ types[0].name }}</span>
              <span v-if="types[0].fee_amount" class="type-auto-info__fee">
                {{ types[0].fee_amount }} {{ types[0].fee_currency ?? 'EUR' }}
              </span>
            </div>

            <!-- Notes -->
            <div class="form-field">
              <label class="form-label" for="renewal-notes">{{ t.notes }}</label>
              <textarea
                id="renewal-notes"
                v-model="form.notes"
                class="form-textarea"
                rows="3"
                :placeholder="t.notesPlaceholder"
                :disabled="submitting || !canRenew"
              />
            </div>

            <!-- Error banner -->
            <div v-if="serverError" class="error-banner">{{ serverError }}</div>

            <!-- Actions -->
            <div class="modal-actions">
              <button type="button" class="btn btn--ghost" @click="$emit('close')" :disabled="submitting">
                {{ t.cancel }}
              </button>
              <button
                type="submit"
                class="btn btn--primary"
                :disabled="submitting || !canRenew || !form.membership_type_id"
              >
                <span v-if="submitting" class="btn__spinner" aria-hidden="true"></span>
                {{ submitting ? t.processing : t.confirm }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

// ── Props & Emits ─────────────────────────────────────────
const props = defineProps({
  show:         { type: Boolean, default: false },
  organisation: { type: Object, required: true },
  member:       { type: Object, required: true },
  types:        { type: Array,  default: () => [] },
})

const emit = defineEmits(['close', 'renewed'])

// ── Locale ────────────────────────────────────────────────
const page    = usePage()
const locale  = computed(() => page.props.locale ?? 'en')

const translations = {
  en: {
    title:          'Renew Membership',
    currentStatus:  'Current status',
    expiresAt:      'Expires',
    expired:        'Expired',
    membershipType: 'Membership type',
    selectType:     'Select a type…',
    notes:          'Notes (optional)',
    notesPlaceholder: 'Any additional notes for this renewal…',
    cancel:         'Cancel',
    confirm:        'Confirm Renewal',
    processing:     'Processing…',
    ineligible:     'This member is not currently eligible for self-renewal. An administrator can still process the renewal.',
  },
  de: {
    title:          'Mitgliedschaft verlängern',
    currentStatus:  'Aktueller Status',
    expiresAt:      'Läuft ab',
    expired:        'Abgelaufen',
    membershipType: 'Mitgliedschaftstyp',
    selectType:     'Typ auswählen…',
    notes:          'Notizen (optional)',
    notesPlaceholder: 'Weitere Hinweise zu dieser Verlängerung…',
    cancel:         'Abbrechen',
    confirm:        'Verlängerung bestätigen',
    processing:     'Wird verarbeitet…',
    ineligible:     'Dieses Mitglied ist derzeit nicht für eine Selbstverlängerung berechtigt.',
  },
  np: {
    title:          'सदस्यता नवीकरण गर्नुहोस्',
    currentStatus:  'हालको स्थिति',
    expiresAt:      'समाप्त हुने',
    expired:        'समाप्त',
    membershipType: 'सदस्यता प्रकार',
    selectType:     'प्रकार छान्नुहोस्…',
    notes:          'टिप्पणी (ऐच्छिक)',
    notesPlaceholder: 'यस नवीकरणका लागि थप टिप्पणी…',
    cancel:         'रद्द गर्नुहोस्',
    confirm:        'नवीकरण पुष्टि गर्नुहोस्',
    processing:     'प्रशोधन भइरहेको छ…',
    ineligible:     'यो सदस्य हाल स्व-नवीकरणका लागि योग्य छैन।',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── State ─────────────────────────────────────────────────
const submitting  = ref(false)
const serverError = ref(null)
const formErrors  = ref({})

const form = ref({
  membership_type_id: props.types?.length === 1 ? props.types[0].id : '',
  notes: '',
})

// ── Computed ──────────────────────────────────────────────
const isExpired = computed(() => {
  if (!props.member.expires_at) return false
  return new Date(props.member.expires_at) < new Date()
})

const canRenew = computed(() => {
  // Admin can always renew; for members, respect can_self_renew flag
  return props.member.can_self_renew !== false
})

// ── Watchers ──────────────────────────────────────────────
// Reset form when modal opens
watch(() => props.show, (val) => {
  if (val) {
    form.value = {
      membership_type_id: props.types?.length === 1 ? props.types[0].id : '',
      notes: '',
    }
    serverError.value = null
    formErrors.value  = {}
  }
})

// Auto-select when types change
watch(() => props.types, (val) => {
  if (val?.length === 1) {
    form.value.membership_type_id = val[0].id
  }
})

// ── Helpers ───────────────────────────────────────────────
function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString(locale.value === 'np' ? 'ne-NP' : locale.value === 'de' ? 'de-DE' : 'en-GB', {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}

// ── Submit ────────────────────────────────────────────────
function submit() {
  if (!canRenew.value || submitting.value) return

  submitting.value  = true
  serverError.value = null
  formErrors.value  = {}

  router.post(
    route('organisations.membership.renew', [props.organisation.slug, props.member.id]),
    {
      membership_type_id: form.value.membership_type_id,
      notes:              form.value.notes || null,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('renewed')
        emit('close')
      },
      onError: (errors) => {
        formErrors.value  = errors
        serverError.value = errors.error ?? null
      },
      onFinish: () => {
        submitting.value = false
      },
    }
  )
}
</script>

<style scoped>
/* ── Backdrop & box ──────────────────────────────────── */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
  padding: 1rem;
}

.modal-box {
  background: #fff;
  border-radius: .75rem;
  box-shadow: 0 20px 60px rgba(0,0,0,.2);
  width: 100%;
  max-width: 28rem;
  overflow: hidden;
}

/* ── Header ──────────────────────────────────────────── */
.modal-header {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  background: #f0fdf4;
}

.modal-header__icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.25rem;
  height: 2.25rem;
  background: #16a34a;
  color: #fff;
  border-radius: 50%;
  flex-shrink: 0;
}

.modal-header__title {
  flex: 1;
  font-size: 1.05rem;
  font-weight: 600;
  color: #166534;
  margin: 0;
}

.modal-header__close {
  font-size: 1.4rem;
  line-height: 1;
  color: #6b7280;
  background: none;
  border: none;
  cursor: pointer;
  padding: .25rem .5rem;
  border-radius: .25rem;
}
.modal-header__close:hover { background: #fee2e2; color: #dc2626; }

/* ── Ineligible banner ───────────────────────────────── */
.ineligible-banner {
  display: flex;
  align-items: flex-start;
  gap: .625rem;
  margin: 1rem 1.5rem 0;
  padding: .75rem 1rem;
  background: #fffbeb;
  border: 1px solid #fcd34d;
  border-radius: .5rem;
  color: #92400e;
  font-size: .875rem;
}

/* ── Member info card ────────────────────────────────── */
.member-card {
  margin: 1rem 1.5rem 0;
  padding: .875rem 1rem;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: .5rem;
}

.member-card__row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .5rem;
}
.member-card__row + .member-card__row { margin-top: .5rem; }

.member-card__label {
  font-size: .8125rem;
  color: #6b7280;
}

.member-card__value {
  font-size: .875rem;
  font-weight: 500;
  color: #111827;
}

/* Status badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: .125rem .625rem;
  border-radius: 9999px;
  font-size: .75rem;
  font-weight: 600;
  text-transform: capitalize;
}
.status-badge--active   { background: #dcfce7; color: #166534; }
.status-badge--expired  { background: #fee2e2; color: #dc2626; }
.status-badge--pending  { background: #fef9c3; color: #92400e; }
.status-badge--ended    { background: #f3f4f6; color: #6b7280; }

/* ── Form ────────────────────────────────────────────── */
.modal-form {
  padding: 1.25rem 1.5rem 1.5rem;
}

.form-field { margin-bottom: 1rem; }

.form-label {
  display: block;
  font-size: .875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: .375rem;
}

.form-select,
.form-textarea {
  width: 100%;
  padding: .5rem .75rem;
  border: 1px solid #d1d5db;
  border-radius: .375rem;
  font-size: .875rem;
  color: #111827;
  background: #fff;
  transition: border-color .15s;
}
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22,163,74,.15);
}
.form-select:disabled,
.form-textarea:disabled {
  background: #f3f4f6;
  color: #9ca3af;
}

.form-textarea { resize: vertical; min-height: 5rem; }

.form-error {
  margin-top: .25rem;
  font-size: .8125rem;
  color: #dc2626;
}

/* Type auto-info */
.type-auto-info {
  display: flex;
  align-items: center;
  gap: .5rem;
  margin-bottom: 1rem;
}
.type-auto-info__name {
  font-weight: 600;
  color: #111827;
  font-size: .875rem;
}
.type-auto-info__fee {
  font-size: .8125rem;
  color: #6b7280;
}

/* Error banner */
.error-banner {
  padding: .75rem 1rem;
  background: #fee2e2;
  border: 1px solid #fca5a5;
  border-radius: .375rem;
  color: #dc2626;
  font-size: .875rem;
  margin-bottom: 1rem;
}

/* ── Actions ─────────────────────────────────────────── */
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: .75rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  padding: .5rem 1.25rem;
  border-radius: .375rem;
  font-size: .875rem;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all .15s;
}
.btn:disabled { opacity: .6; cursor: not-allowed; }

.btn--ghost {
  background: #f3f4f6;
  color: #374151;
}
.btn--ghost:hover:not(:disabled) { background: #e5e7eb; }

.btn--primary {
  background: #16a34a;
  color: #fff;
}
.btn--primary:hover:not(:disabled) { background: #15803d; }

/* Spinner */
.btn__spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(255,255,255,.4);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin .7s linear infinite;
  display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Transition ──────────────────────────────────────── */
.modal-enter-active, .modal-leave-active { transition: opacity .2s; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
.modal-enter-active .modal-box,
.modal-leave-active .modal-box           { transition: transform .2s; }
.modal-enter-from .modal-box             { transform: scale(.95) translateY(-1rem); }
.modal-leave-to .modal-box               { transform: scale(.95) translateY(-1rem); }
</style>
