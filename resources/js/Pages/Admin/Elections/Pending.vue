<template>
  <ElectionLayout>
    <div class="min-h-screen bg-slate-100 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900">
            {{ t.pending_elections }} ({{ elections.length }})
          </h1>
          <p class="text-slate-600 mt-2">
            {{ t.review_and_process }}
          </p>
        </div>

        <!-- Elections List -->
        <div class="grid grid-cols-1 gap-4">
          <div
            v-for="election in elections"
            :key="election.id"
            class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg transition-shadow"
          >
            <!-- Election Info Section -->
            <div class="flex items-start justify-between mb-4 gap-4 flex-wrap">
              <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-slate-900">{{ election.name }}</h3>
                <p class="text-sm text-slate-600 mt-1">
                  {{ t.submitted_by }}
                  {{ election.submitted_by_user?.name }}
                  {{ t.on }}
                  {{ formatDate(election.submitted_for_approval_at) }}
                </p>

                <!-- Rejection reason (if applicable) -->
                <div v-if="election.rejection_reason" class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                  <p class="text-sm font-semibold text-red-700">{{ t.previously_rejected }}:</p>
                  <p class="text-sm text-red-600 mt-1">{{ election.rejection_reason }}</p>
                </div>
              </div>

              <!-- State Badge -->
              <div class="flex-shrink-0">
                <StateBadge :state="election.current_state" size="md" />
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 flex-wrap">
              <ActionButton
                variant="success"
                size="sm"
                @click="openApproveModal(election)"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                {{ t.approve }}
              </ActionButton>

              <ActionButton
                variant="danger"
                size="sm"
                @click="openRejectModal(election)"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ t.reject }}
              </ActionButton>

              <a
                :href="route('elections.show', election.slug)"
                target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ t.preview }}
              </a>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!elections.length" class="text-center py-12">
          <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <p class="text-slate-600 font-medium">{{ t.no_pending }}</p>
          <p class="text-slate-500 text-sm">{{ t.all_processed }}</p>
        </div>
      </div>
    </div>

    <!-- Approval Modal -->
    <ApprovalModal
      v-if="selectedElection"
      :show="showApprovalModal"
      :election="selectedElection"
      @approve="confirmApprove"
      @cancel="closeApprovalModal"
      :loading="isLoading"
    />

    <!-- Rejection Modal -->
    <RejectionModal
      v-if="selectedElection"
      :show="showRejectionModal"
      :election="selectedElection"
      @reject="confirmReject"
      @cancel="closeRejectionModal"
      :loading="isLoading"
    />
  </ElectionLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import StateBadge from '@/Components/Election/StateBadge.vue'
import ActionButton from '@/Components/ActionButton.vue'
import ApprovalModal from '@/Components/Election/Modals/ApprovalModal.vue'
import RejectionModal from '@/Components/Election/Modals/RejectionModal.vue'

const props = defineProps({
  elections: {
    type: Array,
    required: true,
  },
})

const { t } = useI18n()

const selectedElection = ref(null)
const showApprovalModal = ref(false)
const showRejectionModal = ref(false)
const isLoading = ref(false)

const openApproveModal = (election) => {
  selectedElection.value = election
  showApprovalModal.value = true
}

const confirmApprove = (notes) => {
  isLoading.value = true
  router.post(
    route('admin.elections.approve', selectedElection.value.slug),
    { approval_notes: notes },
    {
      onFinish: () => {
        isLoading.value = false
        closeApprovalModal()
      },
    }
  )
}

const openRejectModal = (election) => {
  selectedElection.value = election
  showRejectionModal.value = true
}

const confirmReject = (reason) => {
  isLoading.value = true
  router.post(
    route('admin.elections.reject', selectedElection.value.slug),
    { rejection_reason: reason },
    {
      onFinish: () => {
        isLoading.value = false
        closeRejectionModal()
      },
    }
  )
}

const closeApprovalModal = () => {
  showApprovalModal.value = false
  selectedElection.value = null
}

const closeRejectionModal = () => {
  showRejectionModal.value = false
  selectedElection.value = null
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
</script>
