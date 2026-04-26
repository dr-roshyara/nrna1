<template>
  <DialogModal :show="show" @close="$emit('cancel')">
    <template #title>{{ t.submit_for_approval }}</template>

    <div class="space-y-4">
      <p class="text-slate-600">
        {{ t.submit_checklist }}
      </p>

      <div class="space-y-2">
        <div class="flex items-center gap-2">
          <svg v-if="election.postsCount > 0" class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span :class="election.postsCount > 0 ? 'text-slate-700' : 'text-slate-500'">
            {{ t.posts_created }} ({{ election.postsCount }})
          </span>
        </div>

        <div class="flex items-center gap-2">
          <svg v-if="election.candidatesCount > 0" class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span :class="election.candidatesCount > 0 ? 'text-slate-700' : 'text-slate-500'">
            {{ t.candidates_approved }} ({{ election.candidatesCount }})
          </span>
        </div>

        <div class="flex items-center gap-2">
          <svg v-if="election.votersCount > 0" class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span :class="election.votersCount > 0 ? 'text-slate-700' : 'text-slate-500'">
            {{ t.voters_registered }} ({{ election.votersCount }})
          </span>
        </div>
      </div>

      <p v-if="hasErrors" class="text-red-600 text-sm font-medium">
        ⚠️ {{ t.complete_setup }}
      </p>
    </div>

    <template #footer>
      <button
        @click="$emit('cancel')"
        :disabled="loading"
        class="px-4 py-2 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 disabled:opacity-50 transition-colors"
      >
        {{ t.cancel }}
      </button>
      <ActionButton
        variant="primary"
        :disabled="hasErrors || loading"
        :loading="loading"
        @click="$emit('submit')"
      >
        {{ t.submit_for_approval }}
      </ActionButton>
    </template>
  </DialogModal>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import DialogModal from '@/Components/DialogModal.vue'
import ActionButton from '@/Components/ActionButton.vue'

const props = defineProps({
  show: Boolean,
  election: Object,
  loading: Boolean,
})

defineEmits(['submit', 'cancel'])

const { t } = useI18n()

const hasErrors = computed(() =>
  !props.election.postsCount ||
  !props.election.candidatesCount ||
  !props.election.votersCount
)
</script>
