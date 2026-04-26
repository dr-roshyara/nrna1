<template>
  <DialogModal :show="show" @close="$emit('cancel')">
    <template #title>{{ t.approve_election }}: {{ election?.name }}</template>

    <div class="space-y-4">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
          {{ t.approval_notes_optional }}
        </label>
        <textarea
          v-model="notes"
          rows="4"
          :placeholder="t.approval_notes_placeholder"
          class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
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
        variant="success"
        :loading="loading"
        @click="$emit('approve', notes)"
      >
        {{ t.approve }}
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

defineEmits(['approve', 'cancel'])

const { t } = useI18n()

const notes = ref('')
</script>
