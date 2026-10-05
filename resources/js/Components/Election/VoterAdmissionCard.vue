<template>
  <!--
    PBDIGIT-213 C4-A — "Voter Admission" (election settings).
    Shows and changes the election's Voter Admission Policy in business language.
    Display + intent only: the server (ChangeVoterAdmissionPolicyHandler + Election
    model guard) decides whether a change is allowed. Hidden for elections where
    voter admission is not available (non Election-Only).
  -->
  <section
    v-if="admission.available"
    data-testid="voter-admission-card"
    class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm"
    aria-labelledby="voter-admission-title"
  >
    <h2 id="voter-admission-title" class="text-lg font-semibold text-neutral-900">Voter Admission</h2>
    <p class="mt-1 text-sm text-neutral-600">Choose which verification requirements apply to voters.</p>

    <div class="mt-5 flex items-start justify-between gap-6">
      <div>
        <p id="identity-verification-label" class="font-medium text-neutral-900">Identity verification required</p>
        <p class="mt-1 text-sm text-neutral-600">{{ HELP_TEXT }}</p>
      </div>

      <!--
        Escape (UI_GUIDELINES "MAY", justified): an accessible ON/OFF switch with a
        disabled (locked) state. The shared ToggleSwitch has no disabled state and puts
        attributes on a wrapper, so it cannot express a locked policy.
      -->
      <button
        type="button"
        role="switch"
        data-testid="identity-verification-toggle"
        :aria-checked="String(required)"
        aria-labelledby="identity-verification-label"
        :disabled="admission.isLocked"
        class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
        :class="required ? 'bg-primary-600' : 'bg-neutral-300'"
        @click="required = !required"
      >
        <span class="sr-only">{{ required ? 'ON' : 'OFF' }}</span>
        <span
          aria-hidden="true"
          class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform"
          :class="required ? 'translate-x-6' : 'translate-x-1'"
        />
      </button>
    </div>

    <p v-if="admission.isLocked" class="mt-4 text-sm text-neutral-700" role="status">
      <strong>{{ required ? 'ON — LOCKED' : 'OFF — LOCKED' }}</strong>
      <span class="block">{{ LOCKED_TEXT }}</span>
    </p>

    <p v-else-if="changed" class="mt-4 rounded-lg bg-warning-50 px-4 py-3 text-sm text-warning-800" role="status">
      {{ impactText }}
    </p>

    <div v-if="!admission.isLocked" class="mt-5 flex justify-end">
      <Button
        variant="primary"
        data-testid="save-admission-policy"
        :disabled="saving || !changed"
        :loading="saving"
        @click="save"
      >
        Save Admission Policy
      </Button>
    </div>
    <div v-else class="mt-5 flex justify-end">
      <Button variant="primary" data-testid="save-admission-policy" disabled>Save Admission Policy</Button>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Button from '@/Components/Button.vue'

const props = defineProps({
  // { identityVerificationRequired, isLocked, available, affectedVoters } — from ElectionSettingsController::edit()
  admission:    { type: Object, required: true },
  electionSlug: { type: String, required: true },
})

const HELP_TEXT   = 'When enabled, every voter must have identity verification before they can vote.'
const LOCKED_TEXT = 'The admission policy cannot be changed once the election is ready for voting.'

const required = ref(Boolean(props.admission.identityVerificationRequired))
const saving   = ref(false)

watch(() => props.admission.identityVerificationRequired, (value) => { required.value = Boolean(value) })

const changed = computed(() => required.value !== Boolean(props.admission.identityVerificationRequired))

// Transition-specific impact (ADR R3-2): the affected count is the server's own.
const impactText = computed(() => required.value
  ? `${props.admission.affectedVoters} registered voters currently require identity verification.`
  : `${props.admission.affectedVoters} registered voters would no longer need verification to vote.`)

const save = () => {
  saving.value = true
  router.patch(
    route('elections.admission-policy.update', props.electionSlug),
    { identity_verification_required: required.value },
    { preserveScroll: true, onFinish: () => { saving.value = false } },
  )
}
</script>
