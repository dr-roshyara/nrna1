<template>
  <!--
    PBDIGIT-213 C4-B — one voter's Identity Verification state in the voter table.
    The state is a server-computed DISPLAY read-model (ElectionVoterController); this
    cell only renders it and asks for the existing Verify flow. The server decides.
  -->
  <div class="flex flex-col items-start gap-1.5">
    <span v-if="state === 'verified'" class="text-sm font-medium text-success-700">Verified ✓</span>

    <span v-else-if="state === 'not_required'" class="text-sm text-neutral-500">Not required</span>

    <template v-else>
      <span v-if="state === 'reverification_required'" class="text-sm font-medium text-warning-700">
        ⚠ Re-verification required
      </span>
      <span v-else class="text-sm text-warning-700">⚠ Identity verification required</span>

      <Button size="sm" variant="primary" data-testid="verify-identity" @click="emit('verify')">Verify Identity</Button>
    </template>
  </div>
</template>

<script setup>
import Button from '@/Components/Button.vue'

defineProps({
  // not_required | verified | verification_required | reverification_required
  state: { type: String, required: true },
})

const emit = defineEmits(['verify'])
</script>
