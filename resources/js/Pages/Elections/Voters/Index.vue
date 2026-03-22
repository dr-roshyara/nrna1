<template>
    <nrna-layout>
        <app-layout>
            <!-- Header -->
            <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-200 text-sm font-medium uppercase tracking-wide">
                                {{ organisation.name }}
                            </p>
                            <h1 class="mt-1 text-3xl font-bold">{{ election.name }}</h1>
                            <p class="mt-1 text-blue-200">Voter Management</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium"
                                :class="election.type === 'real'
                                    ? 'bg-green-600 text-white'
                                    : 'bg-yellow-500 text-black'"
                            >
                                {{ election.type === 'real' ? 'Real Election' : 'Demo' }}
                            </span>
                            <a
                                :href="exportUrl"
                                class="bg-white text-blue-900 hover:bg-blue-50 px-4 py-2 rounded-lg text-sm font-medium transition"
                            >
                                Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <!-- Flash messages -->
                <div v-if="$page.props.flash?.success" class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-green-800 text-sm">
                    ✅ {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.error" class="mb-4 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-800 text-sm">
                    ⚠️ {{ $page.props.flash.error }}
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow p-4 text-center">
                        <p class="text-2xl font-bold text-blue-700">{{ stats.active_voters }}</p>
                        <p class="text-sm text-gray-500 mt-1">Active Voters</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ stats.eligible_voters }}</p>
                        <p class="text-sm text-gray-500 mt-1">Eligible</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ stats.by_status?.inactive ?? 0 }}</p>
                        <p class="text-sm text-gray-500 mt-1">Inactive</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 text-center">
                        <p class="text-2xl font-bold text-red-500">{{ stats.by_status?.removed ?? 0 }}</p>
                        <p class="text-sm text-gray-500 mt-1">Removed</p>
                    </div>
                </div>

                <!-- Assign Voter Form -->
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Assign Voter</h2>
                    <form @submit.prevent="assignVoter" class="flex gap-3">
                        <input
                            v-model="assignUserId"
                            type="text"
                            placeholder="User ID (UUID)"
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <button
                            type="submit"
                            :disabled="assigning"
                            class="bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition disabled:opacity-50"
                        >
                            {{ assigning ? 'Assigning…' : 'Assign' }}
                        </button>
                    </form>
                    <p v-if="$page.props.errors?.user_id" class="mt-2 text-sm text-red-600">
                        {{ $page.props.errors.user_id }}
                    </p>
                </div>

                <!-- Voters Table -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membership in voters.data" :key="membership.id">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ membership.user?.name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ membership.user?.email ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800':   membership.status === 'active',
                                            'bg-yellow-100 text-yellow-800': membership.status === 'inactive',
                                            'bg-blue-100 text-blue-800':     membership.status === 'invited',
                                            'bg-red-100 text-red-700':       membership.status === 'removed',
                                        }"
                                    >
                                        {{ membership.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ membership.assigned_at ? new Date(membership.assigned_at).toLocaleDateString() : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Approve: shown when not already active -->
                                        <button
                                            v-if="membership.status !== 'active' && membership.status !== 'removed'"
                                            @click="approveVoter(membership)"
                                            :disabled="loadingId === membership.id"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium disabled:opacity-50"
                                        >
                                            Approve
                                        </button>
                                        <!-- Suspend: shown when active -->
                                        <button
                                            v-if="membership.status === 'active'"
                                            @click="suspendVoter(membership)"
                                            :disabled="loadingId === membership.id"
                                            class="text-yellow-600 hover:text-yellow-800 text-sm font-medium disabled:opacity-50"
                                        >
                                            Suspend
                                        </button>
                                        <!-- Remove -->
                                        <button
                                            v-if="membership.status !== 'removed'"
                                            @click="removeVoter(membership)"
                                            :disabled="loadingId === membership.id"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium disabled:opacity-50"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="voters.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                    No voters assigned to this election yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="voters.links?.length > 3" class="px-6 py-4 border-t border-gray-200 flex gap-1">
                        <template v-for="link in voters.links" :key="link.label">
                            <a
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-1 rounded text-sm"
                                :class="link.active ? 'bg-blue-700 text-white' : 'text-gray-600 hover:bg-gray-100'"
                                v-html="link.label"
                            />
                            <span v-else class="px-3 py-1 rounded text-sm text-gray-400" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    election:     { type: Object, required: true },
    organisation: { type: Object, required: true },
    voters:       { type: Object, required: true },
    stats:        { type: Object, required: true },
});

const assignUserId = ref('');
const assigning    = ref(false);
const loadingId    = ref(null);

const exportUrl = computed(() =>
    route('elections.voters.export', {
        organisation: props.organisation.slug,
        election:     props.election.id,
    })
);

const assignVoter = () => {
    assigning.value = true;
    router.post(
        route('elections.voters.store', {
            organisation: props.organisation.slug,
            election:     props.election.id,
        }),
        { user_id: assignUserId.value },
        {
            preserveScroll: true,
            onSuccess: () => { assignUserId.value = ''; },
            onFinish:  () => { assigning.value = false; },
        }
    );
};

const approveVoter = (membership) => {
    loadingId.value = membership.id;
    router.post(
        route('elections.voters.approve', {
            organisation: props.organisation.slug,
            election:     props.election.id,
            membership:   membership.id,
        }),
        {},
        {
            preserveScroll: true,
            onFinish: () => { loadingId.value = null; },
        }
    );
};

const suspendVoter = (membership) => {
    if (!confirm(`Suspend ${membership.user?.name ?? 'this voter'}? They will no longer be eligible to vote.`)) return;
    loadingId.value = membership.id;
    router.post(
        route('elections.voters.suspend', {
            organisation: props.organisation.slug,
            election:     props.election.id,
            membership:   membership.id,
        }),
        {},
        {
            preserveScroll: true,
            onFinish: () => { loadingId.value = null; },
        }
    );
};

const removeVoter = (membership) => {
    if (!confirm(`Remove ${membership.user?.name ?? 'this voter'} from the election?`)) return;
    loadingId.value = membership.id;
    router.delete(
        route('elections.voters.destroy', {
            organisation: props.organisation.slug,
            election:     props.election.id,
            membership:   membership.id,
        }),
        {
            preserveScroll: true,
            onFinish: () => { loadingId.value = null; },
        }
    );
};
</script>
