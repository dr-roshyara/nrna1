<template>
    <election-layout>
        <div class="min-h-screen bg-linear-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        मतदान गरेका सदस्यहरू
                    </h1>
                    <p class="text-xl text-gray-600 mb-4">Members Who Have Voted</p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
                </header>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Voted</p>
                                <p class="text-3xl font-bold text-green-600">{{ stats.total_voted }}</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Voters</p>
                                <p class="text-3xl font-bold text-blue-600">{{ stats.total_voters }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Participation</p>
                                <p class="text-3xl font-bold text-purple-600">{{ participationRate }}%</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voters List -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 bg-linear-to-r from-blue-600 to-indigo-600">
                        <h2 class="text-xl font-semibold text-white">Voted Members List</h2>
                    </div>

                    <!-- Pagination Top -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <Link
                            v-if="votedUsers.prev_page_url"
                            :href="votedUsers.prev_page_url"
                            class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                        >
                            <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Previous</span>
                        </Link>
                        <div v-else class="invisible flex items-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Previous</span>
                        </div>

                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ votedUsers.from || 0 }}</span> to
                            <span class="font-semibold text-gray-900">{{ votedUsers.to || 0 }}</span> of
                            <span class="font-semibold text-gray-900">{{ votedUsers.total }}</span> results
                        </div>

                        <Link
                            v-if="votedUsers.next_page_url"
                            :href="votedUsers.next_page_url"
                            class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                        >
                            <span>Next</span>
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                        <div v-else class="invisible flex items-center gap-2 text-sm">
                            <span>Next</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Region
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        NRNA ID
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Voted At
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(code, index) in votedUsers.data" :key="code.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ votedUsers.from + index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold text-sm">
                                                    {{ code.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ code.user?.name || 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ code.user?.email || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ code.user?.region || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ code.user?.nrna_id || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                            {{ formatDate(code.vote_submitted_at) }}
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="votedUsers.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="mt-2 text-lg font-medium">No votes recorded yet</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Bottom -->
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <Link
                            v-if="votedUsers.prev_page_url"
                            :href="votedUsers.prev_page_url"
                            class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                        >
                            <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Previous</span>
                        </Link>
                        <div v-else class="invisible flex items-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Previous</span>
                        </div>

                        <div class="text-sm text-gray-600">
                            Page <span class="font-semibold text-gray-900">{{ votedUsers.current_page }}</span> of
                            <span class="font-semibold text-gray-900">{{ votedUsers.last_page }}</span>
                        </div>

                        <Link
                            v-if="votedUsers.next_page_url"
                            :href="votedUsers.next_page_url"
                            class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                        >
                            <span>Next</span>
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                        <div v-else class="invisible flex items-center gap-2 text-sm">
                            <span>Next</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import ElectionLayout from '@/Layouts/ElectionLayout.vue';

export default {
    components: {
        Link,
        ElectionLayout
    },
    props: {
        votedUsers: {
            type: Object,
            required: true
        },
        stats: {
            type: Object,
            required: true
        },
        filters: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {
        participationRate() {
            if (this.stats.total_voters === 0) return 0;
            return ((this.stats.total_voted / this.stats.total_voters) * 100).toFixed(2);
        }
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }
    }
};
</script>
