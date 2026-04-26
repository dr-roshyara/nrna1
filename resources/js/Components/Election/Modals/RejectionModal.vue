<template>
  <DialogModal :show="show" @close="$emit('cancel')">
    <template #title>{{ t.reject_election }}: {{ election?.name }}</template>

    <div class="space-y-4">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
          {{ t.rejection_reason_required }}
        </label>
        <textarea
          v-model="reason"
          rows="4"
          :placeholder="t.rejection_reason_placeholder"
          class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
        />
        <p class="text-xs text-slate-500 mt-1">{{ t.min_characters }}</p>
        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
      </div>
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
        variant="danger"
        :loading="loading"
        :disabled="!reason || reason.trim().length < 10"
        @click="submitReject"
      >
        {{ t.reject }}
      </ActionButton>
    </template>
  </DialogModal>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import DialogModal from '@/Components/Jetstream/DialogModal.vue'
import ActionButton from '@/Components/ActionButton.vue'

defineProps({
  show: Boolean,
  election: Object,
  loading: Boolean,
})

const emit = defineEmits(['reject', 'cancel'])

const { t } = useI18n()

const reason = ref('')
const error = ref('')

const submitReject = () => {
  if (!reason.value.trim()) {
    error.value = t.reason_required
    return
  }
  if (reason.value.trim().length < 10) {
    error.value = t.reason_min_length
    return
  }
  emit('reject', reason.value.trim())
}
</script>
