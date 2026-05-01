<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">Pending Elections</h1>
                <p class="mt-2 text-neutral-600">
                    Review and approve elections awaiting platform admin approval.
                </p>
            </div>

            <!-- Flash Messages -->
            <div v-if="$page.props.success" class="rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-800">{{ $page.props.success }}</div>
            </div>
            <div v-if="$page.props.error" class="rounded-md bg-danger-50 p-4">
                <div class="text-sm text-danger-800">{{ $page.props.error }}</div>
            </div>

            <!-- Elections Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table v-if="elections.data.length > 0" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">
                                Election Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">
                                Organization
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">
                                Expected Voters
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">
                                Submitted
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="election in elections.data" :key="election.id" class="hover:bg-neutral-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ election.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                {{ election.organisation?.name || '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                {{ election.expected_voter_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                {{ formatDate(election.submitted_for_approval_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                <button
                                    @click="openApproveDialog(election)"
                                    class="inline-block px-3 py-1 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                    Approve
                                </button>
                                <button
                                    @click="openRejectDialog(election)"
                                    class="inline-block px-3 py-1 text-sm font-medium text-white bg-danger-600 rounded hover:bg-danger-700">
                                    Reject
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="px-6 py-8 text-center text-neutral-600">
                    No pending elections.
                </div>
            </div>

            <!-- Approve Dialog -->
            <div v-if="showApproveDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
                    <h2 class="text-lg font-bold text-neutral-900 mb-4">Approve Election</h2>
                    <p class="text-neutral-600 mb-4">
                        Are you sure you want to approve <strong>{{ selectedElection?.name }}</strong>?
                    </p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Notes (optional)</label>
                        <textarea
                            v-model="approveNotes"
                            class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm"
                            placeholder="Add approval notes..."
                            rows="3"></textarea>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button
                            @click="showApproveDialog = false"
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50">
                            Cancel
                        </button>
                        <button
                            @click="submitApprove"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                            Approve
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reject Dialog -->
            <div v-if="showRejectDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
                    <h2 class="text-lg font-bold text-neutral-900 mb-4">Reject Election</h2>
                    <p class="text-neutral-600 mb-4">
                        Are you sure you want to reject <strong>{{ selectedElection?.name }}</strong>?
                    </p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Reason (required)</label>
                        <textarea
                            v-model="rejectReason"
                            class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm"
                            placeholder="Explain the reason for rejection..."
                            rows="3"></textarea>
                        <p v-if="rejectReasonError" class="mt-2 text-sm text-danger-600">{{ rejectReasonError }}</p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button
                            @click="showRejectDialog = false"
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50">
                            Cancel
                        </button>
                        <button
                            @click="submitReject"
                            class="px-4 py-2 text-sm font-medium text-white bg-danger-600 rounded-md hover:bg-danger-700">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

export default {
    components: { AdminLayout, Link },
    props: {
        elections: Object,
    },
    data() {
        return {
            showApproveDialog: false,
            showRejectDialog: false,
            selectedElection: null,
            approveNotes: '',
            rejectReason: '',
            rejectReasonError: '',
        }
    },
    methods: {
        openApproveDialog(election) {
            this.selectedElection = election
            this.approveNotes = ''
            this.showApproveDialog = true
        },
        openRejectDialog(election) {
            this.selectedElection = election
            this.rejectReason = ''
            this.rejectReasonError = ''
            this.showRejectDialog = true
        },
        submitApprove() {
            router.post(
                route('platform.elections.approve', this.selectedElection.id),
                { notes: this.approveNotes },
                {
                    onSuccess: () => {
                        this.showApproveDialog = false
                    },
                    onError: (errors) => {
                        console.error('Approve error:', errors)
                    },
                }
            )
        },
        submitReject() {
            if (!this.rejectReason.trim()) {
                this.rejectReasonError = 'Reason is required'
                return
            }
            if (this.rejectReason.length < 10) {
                this.rejectReasonError = 'Reason must be at least 10 characters'
                return
            }

            router.post(
                route('platform.elections.reject', this.selectedElection.id),
                { reason: this.rejectReason },
                {
                    onSuccess: () => {
                        this.showRejectDialog = false
                    },
                    onError: (errors) => {
                        console.error('Reject error:', errors)
                    },
                }
            )
        },
        formatDate(date) {
            if (!date) return '—'
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            })
        },
    },
}
</script>

