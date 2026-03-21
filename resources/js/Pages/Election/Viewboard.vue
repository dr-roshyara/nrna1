<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        चुनाव दर्शक बोर्ड
                    </h1>
                    <p class="text-xl text-gray-600 mb-4">Election Viewboard — {{ election.name }}</p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
                    <p class="text-sm text-gray-500 mt-3 italic">Read-only view | पठन-मात्र दृश्य</p>
                </header>

                <!-- Current Election Status -->
                <section class="mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            वर्तमान स्थिति | Current Status
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Election Active Status -->
                            <div class="text-center p-6 rounded-xl" :class="election.is_active ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="election.is_active ? 'bg-green-100' : 'bg-red-100'">
                                        <svg class="w-8 h-8" :class="election.is_active ? 'text-green-600' : 'text-red-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path v-if="election.is_active" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path v-else d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="election.is_active ? 'text-green-800' : 'text-red-800'">
                                    चुनाव प्रणाली | Election System
                                </h3>
                                <p class="text-sm" :class="election.is_active ? 'text-green-700' : 'text-red-700'">
                                    {{ election.is_active ? 'सक्रिय | Active' : 'निष्क्रिय | Inactive' }}
                                </p>
                            </div>

                            <!-- Voting Period Status -->
                            <div class="text-center p-6 rounded-xl" :class="isVotingActive ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="isVotingActive ? 'bg-yellow-100' : 'bg-gray-100'">
                                        <svg class="w-8 h-8" :class="isVotingActive ? 'text-yellow-600' : 'text-gray-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="isVotingActive ? 'text-yellow-800' : 'text-gray-800'">
                                    मतदान अवधि | Voting Period
                                </h3>
                                <p class="text-sm" :class="isVotingActive ? 'text-yellow-700' : 'text-gray-700'">
                                    {{ isVotingActive ? 'सक्रिय | Active' : 'निष्क्रिय | Inactive' }}
                                </p>
                            </div>

                            <!-- Results Publication Status -->
                            <div class="text-center p-6 rounded-xl" :class="election.results_published ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50 border border-gray-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="election.results_published ? 'bg-blue-100' : 'bg-gray-100'">
                                        <svg class="w-8 h-8" :class="election.results_published ? 'text-blue-600' : 'text-gray-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="election.results_published ? 'text-blue-800' : 'text-gray-800'">
                                    चुनाव परिणाम | Election Results
                                </h3>
                                <p class="text-sm" :class="election.results_published ? 'text-blue-700' : 'text-gray-700'">
                                    {{ election.results_published ? 'प्रकाशित | Published' : 'अप्रकाशित | Unpublished' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Voting Statistics -->
                <section class="mb-12" v-if="stats && Object.keys(stats).length">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">
                            मतदान तथ्यांक | Voting Statistics
                        </h2>

                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Total Memberships -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-600">Total Members</p>
                                        <p class="text-2xl font-bold text-purple-800">{{ stats.total_memberships ?? 0 }}</p>
                                        <p class="text-xs text-purple-600">Registered</p>
                                    </div>
                                    <div class="bg-purple-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2M4 18v-6h3v7H5.5c-.83 0-1.5-.67-1.5-1.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Voters -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-600">Active Voters</p>
                                        <p class="text-2xl font-bold text-green-800">{{ stats.active_voters ?? 0 }}</p>
                                        <p class="text-xs text-green-600">Approved</p>
                                    </div>
                                    <div class="bg-green-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Eligible Voters -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">Eligible Voters</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ stats.eligible_voters ?? 0 }}</p>
                                        <p class="text-xs text-blue-600">Not expired</p>
                                    </div>
                                    <div class="bg-blue-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status breakdown -->
                        <div class="bg-gray-50 p-6 rounded-xl" v-if="stats.by_status">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                सदस्यता स्थिति | Membership Status Breakdown
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-700">{{ stats.by_status.active ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Active</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-yellow-700">{{ stats.by_status.invited ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Invited</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-500">{{ stats.by_status.inactive ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Inactive</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-red-500">{{ stats.by_status.removed ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Removed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Result Viewing -->
                <section>
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            परिणाम दर्शन | Result Viewing
                        </h2>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center" v-if="election.results_published">
                            <a
                                href="/election/result"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                            >
                                👁️ परिणाम हेर्नुहोस् | View Results
                            </a>
                        </div>

                        <div v-else class="text-center p-6 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-gray-600">
                                📊 परिणाम अझै प्रकाशित भएको छैन | Results not yet published
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                मतदान समाप्त भएपछि परिणाम उपलब्ध हुनेछ। | Results will be available after voting ends.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </election-layout>
</template>

<script setup>
import { computed } from 'vue'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const props = defineProps({
    election: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    readonly: {
        type: Boolean,
        default: true
    }
})

const isVotingActive = computed(() => props.election.status === 'active')
</script>
