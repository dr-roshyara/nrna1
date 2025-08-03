<template>
    <nrna-layout>
        <div class="m-2 min-h-screen bg-gray-100 p-2">
            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" class="mb-4 rounded bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="mb-4 rounded bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.errors?.error" class="mb-4 rounded bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                {{ $page.props.errors.error }}
            </div>

            <div class="flex flex-row justify-between py-2">
                <Link
                    v-if="voters.prev_page_url"
                    class="m-2 rounded bg-gray-300 px-2 py-2"
                    :href="voters.prev_page_url"
                    >Previous Page
                </Link>
                <Link
                    v-if="voters.next_page_url"
                    class="m-2 rounded bg-gray-300 px-2 py-2"
                    :href="voters.next_page_url"
                    >Next Page
                </Link>
            </div>
            
            <!-- Committee Member Info -->
            <div v-if="isCommitteeMember" class="mb-4 rounded bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3">
                <strong>Committee Member Access:</strong> You can approve/reject voters.
            </div>

            <!-- {{voters}}  -->
            <!-- header ends  -->
            <!-- Table head starts  -->
            <Table
                :filters="queryBuilderProps.filters"
                :search="queryBuilderProps.search"
                :columns="queryBuilderProps.columns"
                :on-update="setQueryBuilder"
                :meta="voters"
                class="relative w-full border"
            >
                <!-- Table head  -->
                <template #head>
                    <tr
                        class="sticky border-b border-gray-400 bg-gray-300 py-2 font-bold text-gray-900"
                    >
                        <!-- <th @click.prevent="sortBy('name')">Stock Id</th> -->
                        <!-- <td v-show="showColumn('manufacturerId')">S.N.</td> -->
                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('sn')"
                        >
                            S.N.
                        </th>
                        <!-- Name -->
                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('name')"
                            @click.prevent="sortBy('name')"
                        >
                            Name
                        </th>

                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('user_id')"
                            @click.prevent="sortBy('user_id')"
                        >
                            User ID
                        </th>
                        <!-- region    -->
                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('region')"
                            @click.prevent="sortBy('region')"
                        >
                            Region
                        </th>
                        <!-- Voting Status -->
                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('status')"
                        >
                            Voting Status
                        </th>
                        <!-- Approved By -->
                        <th
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('approved_by')"
                        >
                            Status Details
                        </th>
                        <!-- Actions column - only show if committee member -->
                        <th 
                            v-if="isCommitteeMember" 
                            class="sticky top-0 border-r border-green-200"
                            v-show="showColumn('actions')"
                        >
                            Actions
                        </th>
                    </tr>
                    <!-- <tr><th class="fixed top-0"> test</th> </tr>      -->
                </template>
                <!--
       ******
       *
       * here starts the table body
       *
       -->
                <template #body>
                    <tr
                        v-for="(voter, voterIndx) in voters.data"
                        :key="voterIndx"
                        :class="[{ 'bg-gray-100': voterIndx % 2 == 0 }, 'p-1']"
                    >
                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('sn')"
                        >
                            {{ voterIndx + 1 }}
                        </td>
                        <!-- externalId -->
                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('user_id')"
                        >
                            {{ voter.user_id }}
                        </td>

                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('name')"
                        >
                            {{ voter.name }}
                        </td>
                        <!-- region  -->
                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('region')"
                        >
                            {{ voter.region }}
                        </td>
                        <!-- Voting Status -->
                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('status')"
                        >
                            <span 
                                :class="{
                                    'bg-green-100 text-green-800': voter.can_vote == 1,
                                    'bg-red-100 text-red-800': voter.can_vote == 0 || voter.can_vote == null
                                }"
                                class="px-2 py-1 rounded-full text-xs font-medium"
                            >
                                {{ voter.can_vote == 1 ? 'Approved' : 'Pending Approval' }}
                            </span>
                        </td>
                        <!-- Status Details (Approved By / Suspended By) -->
                        <td
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('approved_by')"
                        >
                            <!-- If voter is approved (can_vote = 1) -->
                            <div v-if="voter.can_vote == 1">
                                <span v-if="voter.approvedBy" class="text-sm text-green-600">
                                    ✅ Approved by: {{ voter.approvedBy }}
                                </span>
                                <span v-else class="text-sm text-gray-400 italic">
                                    Approved (no record)
                                </span>
                            </div>
                            
                            <!-- If voter is suspended (can_vote = 0 and has suspension info) -->
                            <div v-else-if="voter.can_vote == 0 && voter.suspendedBy">
                                <div class="text-sm">
                                    <div class="text-red-600">
                                        ❌ Suspended by: {{ voter.suspendedBy }}
                                    </div>
                                    <div v-if="voter.approvedBy" class="text-gray-500 text-xs mt-1">
                                        Originally approved by: {{ voter.approvedBy }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- If voter is pending (never approved) -->
                            <div v-else>
                                <span class="text-sm text-gray-400 italic">
                                    Pending approval
                                </span>
                            </div>
                        </td>
                        <!-- Actions - only show if committee member -->
                        <td 
                            v-if="isCommitteeMember" 
                            class="border-r border-green-200 p-2"
                            v-show="showColumn('actions')"
                        >
                            <div class="flex space-x-2">
                                <!-- Approve Button -->
                                <button
                                    v-if="voter.can_vote == 0 || voter.can_vote == null"
                                    @click="approveVoter(voter.id)"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs"
                                    :disabled="loading"
                                >
                                    {{ loading ? 'Loading...' : 'Approve' }}
                                </button>
                                
                                <!-- Reject/Suspend Button -->
                                <button
                                    v-if="voter.can_vote == 1"
                                    @click="rejectVoter(voter.id)"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs"
                                    :disabled="loading"
                                >
                                    {{ loading ? 'Loading...' : 'Suspend' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <!-- Table head ends here  -->
            </Table>

            <!-- here ends  -->
        </div>
    </nrna-layout>
</template>
<script>
// import User from "../User.vue";
import NrnaLayout from "@/Layouts/ElectionLayout";
import { Inertia } from "@inertiajs/inertia";
import Sendmessage from "@/Pages/Message/Sendmessage";
import { Link } from "@inertiajs/inertia-vue3";
import {
    InteractsWithQueryBuilder,
    Tailwind2,
} from "@protonemedia/inertiajs-tables-laravel-query-builder";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
} from "@headlessui/vue";
import { defineComponent, ref } from "vue";

export default {
    mixins: [InteractsWithQueryBuilder],
    props: {
        voters: Object,
        filters: Object,
        can_send_code: Boolean,
        isCommitteeMember: Boolean,
        // message_receiver_id:Integer
        // from: String,
        // name: String,
    },

    data() {
        return {
            loading: false,
        };
    },

    methods: {
        approveVoter(voterId) {
            if (confirm('Are you sure you want to approve this voter?')) {
                this.loading = true;
                console.log('Approving voter with ID:', voterId); // Debug log
                
                this.$inertia.post(route('voters.approve', voterId), {}, {
                    onSuccess: (page) => {
                        console.log('Approval successful'); // Debug log
                        this.loading = false;
                    },
                    onError: (errors) => {
                        console.error('Approval error:', errors); // Debug log
                        this.loading = false;
                    },
                    onFinish: () => {
                        this.loading = false;
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        },

        rejectVoter(voterId) {
            if (confirm('Are you sure you want to suspend this voter\'s voting access?')) {
                this.loading = true;
                console.log('Rejecting voter with ID:', voterId); // Debug log
                
                this.$inertia.post(route('voters.reject', voterId), {}, {
                    onSuccess: (page) => {
                        console.log('Rejection successful'); // Debug log
                        this.loading = false;
                    },
                    onError: (errors) => {
                        console.error('Rejection error:', errors); // Debug log
                        this.loading = false;
                    },
                    onFinish: () => {
                        this.loading = false;
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        },
    },

    components: {
        Sendmessage,
        NrnaLayout,
        Link,
        Table: Tailwind2.Table,
        // Modal components
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogOverlay,
        DialogTitle,
    },
};
</script>