<template>
  <Teleport to="body">
    <!-- Backdrop -->
    <Transition name="fade">
      <div v-if="show" class="modal-backdrop" @click="emit('close')" />
    </Transition>

    <!-- Modal -->
    <Transition name="modal-slide">
      <div v-if="show" class="modal-container">
        <div class="modal-panel">
          <!-- Header -->
          <div class="modal-header">
            <h2 class="modal-title">Verify Voter Identity</h2>
            <button
              @click="emit('close')"
              class="modal-close"
              aria-label="Close modal"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="modal-body">
            <!-- Voter Info -->
            <div class="voter-info-card">
              <div class="voter-avatar">{{ voterName.charAt(0).toUpperCase() }}</div>
              <div>
                <p class="voter-name">{{ voterName }}</p>
                <p class="voter-email">{{ voterEmail }}</p>
              </div>
            </div>

            <!-- Verification Status Alert -->
            <div v-if="existingVerification" class="alert alert-info">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="font-semibold">Previously Verified</p>
                <p class="text-sm opacity-90">Verified {{ formatDate(existingVerification.verified_at) }}</p>
                <p v-if="existingVerification.verified_ip" class="text-sm opacity-90">IP: {{ existingVerification.verified_ip }}</p>
              </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="verification-form">
              <!-- IP Address (Auto-captured from last login) -->
              <div class="form-group">
                <label for="verified_ip" class="form-label">
                  Voter's Verified IP Address
                  <span class="label-hint">(Captured at last login)</span>
                </label>
                <input
                  id="verified_ip"
                  :value="form.verified_ip"
                  type="text"
                  readonly
                  class="form-input form-input--readonly"
                />
                <p class="form-help">
                  <span v-if="voterLastLogin" class="text-sm">
                    Last login: {{ formatDate(voterLastLogin) }}
                  </span>
                </p>
              </div>

              <!-- Fingerprint Hash (Optional) -->
              <div class="form-group">
                <label for="verified_device_fingerprint_hash" class="form-label">
                  Device Fingerprint Hash
                  <span class="label-optional">(optional)</span>
                </label>
                <input
                  id="verified_device_fingerprint_hash"
                  v-model="form.verified_device_fingerprint_hash"
                  type="text"
                  placeholder="Hash captured from voter's device"
                  maxlength="64"
                  class="form-input form-input--monospace"
                />
                <p class="form-help">64-character hash of voter's device fingerprint, if capturing device verification.</p>
              </div>

              <!-- Notes -->
              <div class="form-group">
                <label for="notes" class="form-label">
                  Notes
                  <span class="label-optional">(optional)</span>
                </label>
                <textarea
                  id="notes"
                  v-model="form.notes"
                  placeholder="e.g., Called on mobile, using home WiFi, family computer, etc."
                  rows="3"
                  maxlength="1000"
                  class="form-textarea"
                />
                <p class="form-help">{{ form.notes.length }}/1000 characters</p>
              </div>

              <!-- Error Messages -->
              <div v-if="submitError" class="alert alert-error">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>{{ submitError }}</p>
              </div>

              <!-- Buttons -->
              <div class="modal-actions">
                <button
                  type="button"
                  @click="emit('close')"
                  class="btn btn-secondary"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="submitting || !form.verified_ip"
                  class="btn btn-primary"
                  :aria-busy="submitting"
                >
                  <span v-if="submitting" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Saving…
                  </span>
                  <span v-else>{{ existingVerification ? 'Update Verification' : 'Verify Voter' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  voterId: {
    type: String,
    default: null
  },
  voterName: {
    type: String,
    default: 'Voter'
  },
  voterEmail: {
    type: String,
    default: ''
  },
  voterCurrentIp: {
    type: String,
    default: '—'
  },
  voterLastLogin: {
    type: String,
    default: null
  },
  organisation: {
    type: Object,
    required: true
  },
  election: {
    type: Object,
    required: true
  },
  existingVerification: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'success'])

const form = ref({
  verified_ip: props.voterCurrentIp ?? '',
  verified_device_fingerprint_hash: props.existingVerification?.verified_device_fingerprint_hash ?? '',
  notes: props.existingVerification?.notes ?? ''
})

const submitting = ref(false)
const submitError = ref(null)
const ipError = ref(null)

// Watch for changes to voter IP or existing verification
watch(() => props.voterCurrentIp, (newVal) => {
  form.value.verified_ip = newVal ?? ''
})

watch(() => props.existingVerification, (newVal) => {
  if (newVal) {
    form.value = {
      verified_ip: props.voterCurrentIp ?? '',
      verified_device_fingerprint_hash: newVal.verified_device_fingerprint_hash ?? '',
      notes: newVal.notes ?? ''
    }
  }
}, { deep: true })

const validateIp = () => {
  const ipPattern = /^(\d{1,3}\.){3}\d{1,3}$/
  if (form.value.verified_ip && !ipPattern.test(form.value.verified_ip)) {
    ipError.value = 'Invalid IP address format (e.g., 192.168.1.100)'
  } else {
    ipError.value = null
  }
}

const formatDate = (date) => {
  if (!date) return 'never'
  return new Date(date).toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const submit = async () => {
  submitting.value = true
  submitError.value = null

  router.post(
    route('elections.voters.verify', {
      organisation: props.organisation.slug,
      election: props.election.slug
    }),
    {
      user_id: props.voterId,
      verified_ip: form.value.verified_ip,
      verified_device_fingerprint_hash: form.value.verified_device_fingerprint_hash || null,
      notes: form.value.notes || null
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('close')
        emit('success')
      },
      onError: (errors) => {
        submitError.value = Object.values(errors)[0] || 'Failed to save verification'
      },
      onFinish: () => {
        submitting.value = false
      }
    }
  )
}
</script>

<style scoped>
/* ── Transitions ────────────────────────────────────── */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-slide-enter-active,
.modal-slide-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-slide-enter-from,
.modal-slide-leave-to {
  transform: translateY(20px);
  opacity: 0;
}

/* ── Backdrop ────────────────────────────────────── */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 40;
}

/* ── Container ────────────────────────────────────── */
.modal-container {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
  padding: 1rem;
  @media (prefers-reduced-motion: reduce) {
    transition: none;
  }
}

.modal-panel {
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

/* ── Header ────────────────────────────────────── */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  cursor: pointer;
  color: #6b7280;
  padding: 0.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
}

.modal-close:hover {
  background: #f3f4f6;
  color: #1f2937;
}

.modal-close:focus-visible {
  outline: 2px solid #0369a1;
  outline-offset: 2px;
}

/* ── Body ────────────────────────────────────── */
.modal-body {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}

/* ── Voter Info Card ────────────────────────────────────── */
.voter-info-card {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
  border-left: 4px solid #0369a1;
  border-radius: 0.5rem;
  margin-bottom: 1.5rem;
}

.voter-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background: linear-gradient(135deg, #0369a1, #06b6d4);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1.125rem;
  flex-shrink: 0;
}

.voter-name {
  font-weight: 600;
  color: #1f2937;
  margin: 0 0 0.25rem 0;
}

.voter-email {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0;
}

/* ── Alerts ────────────────────────────────────── */
.alert {
  display: flex;
  gap: 0.75rem;
  padding: 1rem;
  border-radius: 0.5rem;
  margin-bottom: 1.5rem;
  font-size: 0.875rem;
}

.alert-info {
  background: #eff6ff;
  color: #0c4a6e;
  border-left: 4px solid #0369a1;
}

.alert-error {
  background: #fef2f2;
  color: #991b1b;
  border-left: 4px solid #dc2626;
}

.alert svg {
  flex-shrink: 0;
  margin-top: 0.125rem;
}

/* ── Form ────────────────────────────────────── */
.verification-form {
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
  color: #1f2937;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.label-hint,
.label-optional {
  font-weight: 400;
  color: #6b7280;
  font-size: 0.75rem;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.625rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-family: inherit;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #0369a1;
  box-shadow: 0 0 0 3px rgba(3, 105, 161, 0.1);
}

.form-input--monospace {
  font-family: 'Courier New', monospace;
  font-size: 0.75rem;
}

.form-input--readonly {
  background-color: #f9fafb;
  color: #4b5563;
  cursor: not-allowed;
  user-select: none;
  font-weight: 500;
}

.form-input--readonly:focus {
  border-color: #d1d5db;
  box-shadow: none;
}

.form-textarea {
  resize: vertical;
}

.form-error {
  color: #dc2626;
  font-size: 0.75rem;
  margin-top: 0.375rem;
}

.form-help {
  color: #6b7280;
  font-size: 0.75rem;
  margin-top: 0.375rem;
}

/* ── Actions ────────────────────────────────────── */
.modal-actions {
  display: flex;
  gap: 0.75rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
  justify-content: flex-end;
}

.btn {
  padding: 0.625rem 1.25rem;
  border-radius: 0.375rem;
  font-weight: 500;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn:focus-visible {
  outline: 2px solid #0369a1;
  outline-offset: 2px;
}

.btn-primary {
  background: linear-gradient(135deg, #0369a1, #06b6d4);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #0c4a6e, #0891b2);
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(3, 105, 161, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f3f4f6;
  color: #1f2937;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

@media (prefers-reduced-motion: reduce) {
  .fade-enter-active,
  .fade-leave-active,
  .modal-slide-enter-active,
  .modal-slide-leave-active {
    transition: none;
  }

  .btn-primary:hover:not(:disabled) {
    transform: none;
  }
}
</style>
