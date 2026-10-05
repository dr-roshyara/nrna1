<template>
  <!--
    PBDIGIT-213 C4 — individual identity attestation by the Chief or Deputy Chief.
    The attestation is the admission evidence (OFFICER_IDENTITY_VERIFICATION — the same
    requirement that Verify All satisfies). IP/device data is audit-only metadata and is
    kept in a collapsed technical section so it never reads as the identity criterion.
    Authority, voter eligibility and the verification window are enforced server-side.
  -->
  <Teleport to="body">
    <!-- One stable root (backdrop + dialog) so re-renders patch nodes in place. -->
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-neutral-900/50" aria-hidden="true" @click="emit('close')" />
      <div
        class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="identity-attestation-title"
      >
        <h2 id="identity-attestation-title" class="text-lg font-semibold text-neutral-900">
          {{ reverify ? 'Re-verify Identity' : 'Verify Identity' }}
        </h2>
        <p class="mt-2 text-sm text-neutral-800">{{ voter?.name }}</p>
        <p class="text-sm text-neutral-600">{{ voter?.email }}</p>

        <label class="mt-4 flex items-start gap-3 rounded-lg border border-neutral-200 p-3 text-sm text-neutral-800">
          <input
            v-model="attested"
            type="checkbox"
            data-testid="attest-identity"
            class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
          />
          <span>{{ STATEMENT }}</span>
        </label>

        <label for="identity-attestation-notes" class="mt-4 block text-sm font-medium text-neutral-800">
          Notes <span class="font-normal text-neutral-500">(optional)</span>
        </label>
        <textarea
          id="identity-attestation-notes"
          v-model="notes"
          rows="3"
          maxlength="1000"
          class="mt-1 w-full rounded-lg border border-neutral-300 p-2 text-sm focus:border-primary-500 focus:ring-primary-500"
        />

        <details data-testid="technical-details" class="mt-4 rounded-lg border border-neutral-200 p-3 text-sm text-neutral-700">
          <summary class="cursor-pointer font-medium text-neutral-800">Technical details — audit only, not identity proof</summary>
          <p class="mt-2 text-neutral-600">These details are stored for audit only. They are not used to admit the voter.</p>
          <p class="mt-2">Last-login IP address: <span class="font-mono">{{ voter?.lastLoginIp || '—' }}</span></p>
          <label for="identity-attestation-fingerprint" class="mt-2 block">
            Device fingerprint hash <span class="text-neutral-500">(optional)</span>
          </label>
          <input
            id="identity-attestation-fingerprint"
            v-model="fingerprint"
            type="text"
            maxlength="64"
            class="mt-1 w-full rounded-lg border border-neutral-300 p-2 font-mono text-xs focus:border-primary-500 focus:ring-primary-500"
          />
        </details>

        <p v-if="submitError" class="mt-4 text-sm text-danger-700" role="alert">{{ submitError }}</p>

        <div class="mt-6 flex justify-end gap-3">
          <Button variant="secondary" data-testid="cancel-identity" @click="emit('close')">Cancel</Button>
          <Button
            variant="primary"
            data-testid="confirm-identity"
            :disabled="!attested || submitting"
            :loading="submitting"
            @click="confirm"
          >
            Confirm
          </Button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Button from '@/Components/Button.vue'

const props = defineProps({
  open:             { type: Boolean, default: false },
  voter:            { type: Object,  default: null },   // { id, name, email, lastLoginIp }
  organisationSlug: { type: String,  required: true },
  electionSlug:     { type: String,  required: true },
  reverify:         { type: Boolean, default: false },
})

const emit = defineEmits(['close'])

const STATEMENT = "I confirm that this voter's identity has been verified according to the organisation's voter-registration process."

const attested    = ref(false)
const notes       = ref('')
const fingerprint = ref('')
const submitting  = ref(false)
const submitError = ref(null)

// A fresh attestation every time the dialog opens — never carried over between voters.
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    attested.value = false
    notes.value = ''
    fingerprint.value = ''
    submitError.value = null
  }
})

const confirm = () => {
  submitting.value = true
  submitError.value = null

  const ip = props.voter?.lastLoginIp ?? ''

  router.post(
    route('elections.voters.verify', { organisation: props.organisationSlug, election: props.electionSlug }),
    {
      user_id: props.voter?.id,
      attested: true,
      notes: notes.value || null,
      // Audit metadata only; an unknown IP (empty or the '—' placeholder) is sent as null.
      verified_ip: /^(\d{1,3}\.){3}\d{1,3}$/.test(ip) ? ip : null,
      verified_device_fingerprint_hash: fingerprint.value || null,
    },
    {
      preserveScroll: true,
      onSuccess: () => emit('close'),
      onError: (errors) => { submitError.value = Object.values(errors)[0] || 'The identity verification could not be saved.' },
      onFinish: () => { submitting.value = false },
    },
  )
}
</script>
