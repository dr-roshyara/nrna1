<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">All Elections</h1>
                <p class="mt-2 text-gray-600">
                    Overview of all elections with subscription status: Free (≤40 voters) vs Paid (>40 voters).
                </p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow">
                    <p class="text-sm text-gray-600">Total Elections</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ elections.total }}</p>
                </div>
                <div class="rounded-lg bg-green-50 p-6 shadow border border-green-200">
                    <p class="text-sm text-green-600 font-medium">Free Elections</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">{{ freeCount }}</p>
                    <p class="text-xs text-green-600 mt-2">≤ 40 voters (auto-approved)</p>
                </div>
                <div class="rounded-lg bg-amber-50 p-6 shadow border border-amber-200">
                    <p class="text-sm text-amber-600 font-medium">Paid Elections</p>
                    <p class="text-3xl font-bold text-amber-900 mt-2">{{ paidCount }}</p>
                    <p class="text-xs text-amber-600 mt-2">> 40 voters (requires approval)</p>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 border-b border-gray-200 pb-2">
                <Link
                    :href="route('platform.elections.all', { filter: 'all', sort: filters.sort, direction: filters.direction })"
                    :class="filterClass('all')"
                >
                    All
                </Link>
                <Link
                    :href="route('platform.elections.all', { filter: 'free', sort: filters.sort, direction: filters.direction })"
                    :class="filterClass('free')"
                >
                    Free
                </Link>
                <Link
                    :href="route('platform.elections.all', { filter: 'paid', sort: filters.sort, direction: filters.direction })"
                    :class="filterClass('paid')"
                >
                    Paid
                </Link>
            </div>

            <!-- Elections Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table v-if="elections.data.length > 0" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <button @click="toggleSort('name')" class="text-xs font-medium text-gray-500 uppercase hover:text-gray-900 inline-flex items-center gap-1">
                                    Election Name
                                    <span v-if="filters.sort === 'name'" class="text-gray-400">{{ filters.direction === 'asc' ? '↑' : '↓' }}</span>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Organisation
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button @click="toggleSort('expected_voter_count')" class="text-xs font-medium text-gray-500 uppercase hover:text-gray-900 inline-flex items-center gap-1">
                                    Expected Voters
                                    <span v-if="filters.sort === 'expected_voter_count'" class="text-gray-400">{{ filters.direction === 'asc' ? '↑' : '↓' }}</span>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Subscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                State
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button @click="toggleSort('created_at')" class="text-xs font-medium text-gray-500 uppercase hover:text-gray-900 inline-flex items-center gap-1">
                                    Created
                                    <span v-if="filters.sort === 'created_at'" class="text-gray-400">{{ filters.direction === 'asc' ? '↑' : '↓' }}</span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="election in elections.data" :key="election.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ election.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ election.organisation?.name || '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ election.expected_voter_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="election.is_free"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Free
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    ⭐ Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="stateClass(election.state)">
                                    {{ formatState(election.state) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ formatDate(election.created_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="px-6 py-8 text-center text-gray-600">
                    No elections found.
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="elections.links && elections.links.length > 3" class="mt-6 flex justify-center gap-2">
                <Link
                    v-for="link in elections.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    :class="link.active
                        ? 'px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium'
                        : 'px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50'"
                    v-html="link.label"
                />
            </div>

            <!-- Info Section -->
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-6">
                <h3 class="font-bold text-blue-900">Subscription Model</h3>
                <ul class="mt-3 space-y-2 text-sm text-blue-800">
                    <li>• <strong>Free (≤40 voters):</strong> Automatically approved and ready to use immediately</li>
                    <li>• <strong>Paid (>40 voters):</strong> Requires platform admin approval before proceeding</li>
                    <li>• <strong>Pending Approvals:</strong> Check "Pending Approvals" page to review large elections</li>
                </ul>
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
        filters: Object,
    },
    computed: {
        freeCount() {
            return this.elections.data?.filter(e => e.is_free).length ?? 0
        },
        paidCount() {
            return this.elections.data?.filter(e => !e.is_free).length ?? 0
        },
    },
    methods: {
        filterClass(filter) {
            const active = this.filters.filter === filter
            return active
                ? 'px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600'
                : 'px-4 py-2 text-sm text-gray-500 hover:text-gray-700'
        },
        toggleSort(field) {
            const direction = this.filters.sort === field && this.filters.direction === 'asc' ? 'desc' : 'asc'
            router.get(route('platform.elections.all'), {
                filter: this.filters.filter,
                sort: field,
                direction: direction,
            }, { preserveState: true })
        },
        formatDate(date) {
            if (!date) return '—'
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric',
            })
        },
        formatState(state) {
            if (!state) return '—'
            return state.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
        },
        stateClass(state) {
            const classes = {
                draft: 'bg-gray-100 text-gray-800',
                pending_approval: 'bg-yellow-100 text-yellow-800',
                administration: 'bg-blue-100 text-blue-800',
                nomination: 'bg-purple-100 text-purple-800',
                voting: 'bg-green-100 text-green-800',
                results_pending: 'bg-orange-100 text-orange-800',
                results: 'bg-teal-100 text-teal-800',
            }
            return classes[state] || 'bg-gray-100 text-gray-800'
        },
    },
}
</script>
