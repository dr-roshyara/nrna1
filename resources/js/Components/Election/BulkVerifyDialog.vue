<template>
  <!--
    PBDIGIT-213 C4-C — "Verify All" bulk attestation (PO decisions C4-D1…D4).
    The officer confirms the exact count they see and the attestation statement.
    The server recomputes the eligible voters and refuses a changed count; the Chief/
    Deputy authority and the verification window are enforced server-side.
  -->
  <Teleport to="body">
    <!-- One stable root (backdrop + dialog) so re-renders patch nodes in place. -->
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-neutral-900/50" aria-hidden="true" @click="emit('close')" />
      <div
        class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="bulk-verify-title"
      >
        <h2 id="bulk-verify-title" class="text-lg font-semibold text-neutral-900">Verify {{ count }} voters?</h2>
        <p class="mt-2 text-sm text-neutral-600">
          This records identity verification for every active voter in this election who has never been verified.
          Voters whose verification was revoked must be verified individually.
        </p>

        <label class="mt-4 flex items-start gap-3 rounded-lg border border-neutral-200 p-3 text-sm text-neutral-800">
          <input
            v-model="attested"
            type="checkbox"
            data-testid="attest-checkbox"
            class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
          />
          <span>{{ STATEMENT }}</span>
        </label>

        <div class="mt-6 flex justify-end gap-3">
          <Button variant="secondary" data-testid="cancel-verify-all" @click="emit('close')">Cancel</Button>
          <Button
            variant="primary"
            data-testid="confirm-verify-all"
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
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Button from '@/Components/Button.vue'

const props = defineProps({
  open:             { type: Boolean, default: false },
  count:            { type: Number,  required: true },
  organisationSlug: { type: String,  required: true },
  electionSlug:     { type: String,  required: true },
})

const emit = defineEmits(['close'])

const STATEMENT = "I confirm that the identities of these voters have already been verified according to the organisation's voter-registration process."

const attested   = ref(false)
const submitting = ref(false)

const confirm = () => {
  submitting.value = true
  router.post(
    route('elections.voters.verify-all', { organisation: props.organisationSlug, election: props.electionSlug }),
    { attested: true, expected_count: props.count },
    {
      preserveScroll: true,
      onSuccess: () => emit('close'),
      onFinish: () => { submitting.value = false; attested.value = false },
    },
  )
}
</script>
