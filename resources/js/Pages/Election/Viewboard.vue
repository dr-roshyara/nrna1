<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        चुनाव दर्शक बोर्ड
                    </h1>
                    <p class="text-xl text-gray-600 mb-4">Election Viewboard</p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
                </header>

                <!-- Current Election Status -->
                <section class="mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            वर्तमान स्थिति | Current Status
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Election Active Status -->
                            <div class="text-center p-6 rounded-xl" :class="electionStatus.is_active ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="electionStatus.is_active ? 'bg-green-100' : 'bg-red-100'">
                                        <svg class="w-8 h-8" :class="electionStatus.is_active ? 'text-green-600' : 'text-red-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path v-if="electionStatus.is_active" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path v-else d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="electionStatus.is_active ? 'text-green-800' : 'text-red-800'">
                                    चुनाव प्रणाली | Election System
                                </h3>
                                <p class="text-sm" :class="electionStatus.is_active ? 'text-green-700' : 'text-red-700'">
                                    {{ electionStatus.is_active ? 'सक्रिय | Active' : 'निष्क्रिय | Inactive' }}
                                </p>
                            </div>

                            <!-- Voting Period Status -->
                            <div class="text-center p-6 rounded-xl" :class="electionStatus.voting_period_active ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="electionStatus.voting_period_active ? 'bg-yellow-100' : 'bg-gray-100'">
                                        <svg class="w-8 h-8" :class="electionStatus.voting_period_active ? 'text-yellow-600' : 'text-gray-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="electionStatus.voting_period_active ? 'text-yellow-800' : 'text-gray-800'">
                                    मतदान अवधि | Voting Period
                                </h3>
                                <p class="text-sm" :class="electionStatus.voting_period_active ? 'text-yellow-700' : 'text-gray-700'">
                                    {{ electionStatus.voting_period_active ? 'सक्रिय | Active' : 'निष्क्रिय | Inactive' }}
                                </p>
                            </div>

                            <!-- Results Publication Status -->
                            <div class="text-center p-6 rounded-xl" :class="electionStatus.results_published ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50 border border-gray-200'">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="rounded-full p-3" :class="electionStatus.results_published ? 'bg-blue-100' : 'bg-gray-100'">
                                        <svg class="w-8 h-8" :class="electionStatus.results_published ? 'text-blue-600' : 'text-gray-600'" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mb-2" :class="electionStatus.results_published ? 'text-blue-800' : 'text-gray-800'">
                                    चुनाव परिणाम | Election Results
                                </h3>
                                <p class="text-sm" :class="electionStatus.results_published ? 'text-blue-700' : 'text-gray-700'">
                                    {{ electionStatus.results_published ? 'प्रकाशित | Published' : 'अप्रकाशित | Unpublished' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Voting Statistics -->
                <section class="mb-12" v-if="statistics && !statistics.error">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">
                            मतदान तथ्यांक | Voting Statistics
                        </h2>

                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Participation Rate -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-600">Participation Rate</p>
                                        <p class="text-2xl font-bold text-purple-800">{{ statistics.summary.participation_percentage }}%</p>
                                        <p class="text-xs text-purple-600">{{ statistics.summary.voter_turnout }}</p>
                                    </div>
                                    <div class="bg-purple-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2M4 18v-6h3v7H5.5c-.83 0-1.5-.67-1.5-1.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Sessions -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-600">Active Sessions</p>
                                        <p class="text-2xl font-bold text-green-800">{{ statistics.sessions.active_voting_sessions }}</p>
                                        <p class="text-xs text-green-600">Currently voting</p>
                                    </div>
                                    <div class="bg-green-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Votes -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">Total Votes</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ statistics.votes.total_cast }}</p>
                                        <p class="text-xs text-blue-600">Completed votes</p>
                                    </div>
                                    <div class="bg-blue-200 rounded-full p-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Statistics -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Voter Statistics -->
                            <div class="bg-gray-50 p-6 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2M4 18v-6h3v7H5.5c-.83 0-1.5-.67-1.5-1.5"/>
                                    </svg>
                                    मतदाता विवरण | Voter Details
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Registered Voters</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.voters.total_registered }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Approved to Vote</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.voters.approved_to_vote }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Unique IP Addresses</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.system.unique_ip_addresses }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Voting Sessions -->
                            <div class="bg-gray-50 p-6 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    सत्र विवरण | Session Details
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Codes Generated</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.sessions.total_codes_generated }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Expired Sessions</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.sessions.expired_sessions }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="text-sm text-gray-600">Completion Rate</span>
                                        <span class="font-semibold text-gray-800">{{ statistics.votes.completion_rate }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="mt-8 bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-xl border border-indigo-200">
                            <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                </svg>
                                हालका गतिविधि | Recent Activity
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-800">{{ statistics.votes.completed_today }}</p>
                                    <p class="text-xs text-indigo-600">Votes Today</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-800">{{ statistics.votes.recent_24h }}</p>
                                    <p class="text-xs text-indigo-600">Votes (24h)</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-800">{{ statistics.sessions.codes_today }}</p>
                                    <p class="text-xs text-indigo-600">Codes Today</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-800">{{ statistics.sessions.recent_codes_24h }}</p>
                                    <p class="text-xs text-indigo-600">Codes (24h)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Statistics Error -->
                <section v-if="statistics && statistics.error" class="mb-12">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                            तथ्यांक लोड गर्न सकिएन | Statistics Unavailable
                        </h3>
                        <p class="text-yellow-700">{{ statistics.error }}</p>
                    </div>
                </section>

                <!-- Bulk Voter Management - Only for users with manage settings permission -->
                <section v-if="permissions.canManageSettings" class="mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            मतदाता व्यवस्थापन | Voter Management
                        </h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Bulk Approve Section -->
                            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 text-center">
                                    बल्क स्वीकृति | Bulk Approval
                                </h3>

                                <div class="space-y-4">
                                    <!-- IP Check Option -->
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <label for="enable-ip-check" class="text-sm font-medium text-gray-700">
                                            IP Address Checking
                                        </label>
                                        <input
                                            id="enable-ip-check"
                                            type="checkbox"
                                            v-model="bulkApproveSettings.enableIpCheck"
                                            class="rounded-sm border-gray-300 text-green-600 focus:ring-green-500"
                                        />
                                    </div>

                                    <p class="text-xs text-gray-600">
                                        <span v-if="bulkApproveSettings.enableIpCheck">🔒 Voters must vote from registered IP</span>
                                        <span v-else>🔓 Voters can vote from any IP address</span>
                                    </p>

                                    <!-- Approve Button -->
                                    <button
                                        @click="bulkApproveVoters"
                                        :disabled="isLoading"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                                    >
                                        <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-if="!isLoading">✅ सबै स्वीकृत गर्नुहोस् | Approve All</span>
                                        <span v-else>Approving...</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Bulk Disapprove Section -->
                            <div class="bg-red-50 p-6 rounded-xl border border-red-200">
                                <h3 class="text-lg font-semibold text-red-800 mb-4 text-center">
                                    बल्क अस्वीकार | Bulk Disapproval
                                </h3>

                                <div class="space-y-4">
                                    <!-- Include Voted Option -->
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <label for="include-voted" class="text-sm font-medium text-gray-700">
                                            Include Voted Users
                                        </label>
                                        <input
                                            id="include-voted"
                                            type="checkbox"
                                            v-model="bulkDisapproveSettings.includeVoted"
                                            class="rounded-sm border-gray-300 text-red-600 focus:ring-red-500"
                                        />
                                    </div>

                                    <p class="text-xs text-gray-600">
                                        <span v-if="bulkDisapproveSettings.includeVoted">⚠️ Will disapprove ALL voters (including voted)</span>
                                        <span v-else>🛡️ Will preserve voters who have already voted</span>
                                    </p>

                                    <!-- Disapprove Button -->
                                    <button
                                        @click="bulkDisapproveVoters"
                                        :disabled="isLoading"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                                    >
                                        <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-if="!isLoading">❌ सबै अस्वीकार गर्नुहोस् | Disapprove All</span>
                                        <span v-else>Disapproving...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Voting Period Control - Only for users with manage settings permission -->
                <section v-if="permissions.canManageSettings" class="mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            मतदान नियन्त्रण | Voting Control
                        </h2>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <!-- Start Voting Button -->
                            <button
                                v-if="!electionStatus.voting_period_active"
                                @click="startVoting"
                                :disabled="isLoading"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span v-if="!isLoading">🚀 मतदान सुरु गर्नुहोस् | Start Voting</span>
                                <span v-else>Starting...</span>
                            </button>

                            <!-- End Voting Button -->
                            <button
                                v-if="electionStatus.voting_period_active"
                                @click="endVoting"
                                :disabled="isLoading"
                                class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span v-if="!isLoading">🛑 मतदान समाप्त गर्नुहोस् | End Voting</span>
                                <span v-else>Ending...</span>
                            </button>
                        </div>

                        <!-- Current Voting Status Info -->
                        <div class="mt-6 p-4 rounded-lg" :class="electionStatus.voting_period_active ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200'">
                            <p class="text-center font-medium" :class="electionStatus.voting_period_active ? 'text-green-800' : 'text-gray-800'">
                                <span v-if="electionStatus.voting_period_active">
                                    ✅ मतदान सक्रिय छ | Voting is currently active
                                </span>
                                <span v-else>
                                    ⏸️ मतदान निष्क्रिय छ | Voting is currently inactive
                                </span>
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Result Management Actions - Only visible when can_view_result is true -->
                <section v-if="can_view_result && permissions.canViewResults">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            परिणाम दर्शन | Result Viewing
                        </h2>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <!-- View Results Button -->
                            <a
                                v-if="electionStatus.results_published"
                                href="/election/result"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                            >
                                👁️ परिणाम हेर्नुहोस् | View Results
                            </a>

                            <!-- Download PDF Button -->
                            <a
                                v-if="electionStatus.results_published"
                                href="/election/result/download-pdf"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200"
                                target="_blank"
                            >
                                📄 PDF डाउनलोड गर्नुहोस् | Download PDF
                            </a>

                            <!-- Results Not Published Message -->
                            <div v-else class="text-center p-4 bg-gray-50 rounded-lg border">
                                <p class="text-gray-600">
                                    📊 परिणाम अझै प्रकाशित भएको छैन | Results not yet published
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Result Hidden During Voting Period -->
                <section v-if="!can_view_result && electionStatus.voting_started && electionStatus.voting_period_active">
                    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-8 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <div class="bg-orange-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-orange-800 mb-2">
                            मतदान अवधि सक्रिय | Voting Period Active
                        </h3>
                        <p class="text-orange-700">
                            मतदान चलिरहेको बेला परिणाम लुकाइएको छ। मतदान समाप्त भएपछि परिणाम उपलब्ध हुनेछ।<br>
                            Results are hidden during the voting period. Results will be available after voting ends.
                        </p>
                    </div>
                </section>

                <!-- Access Denied Message -->
                <section v-if="!permissions.canViewResults">
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-8 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <div class="bg-red-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-red-800 mb-2">
                            पहुँच अस्वीकृत | Access Denied
                        </h3>
                        <p class="text-red-700">
                            तपाईंसँग चुनाव परिणाम हेर्ने अधिकार छैन। | You don't have permission to view election results.
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from "@/Layouts/ElectionLayout.vue";

export default {
    components: {
        ElectionLayout,
    },

    props: {
        electionStatus: {
            type: Object,
            default: () => ({
                is_active: false,
                results_published: false,
                voting_started: false,
                voting_period_active: false
            })
        },
        permissions: {
            type: Object,
            default: () => ({
                canPublishResults: false,
                canViewResults: false,
                canManageSettings: false
            })
        },
        statistics: {
            type: Object,
            default: () => null
        },
        can_view_result: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            isLoading: false,
            bulkApproveSettings: {
                enableIpCheck: false
            },
            bulkDisapproveSettings: {
                includeVoted: false
            }
        };
    },

    methods: {
        async startVoting() {
            if (!confirm('Are you sure you want to start the voting period? This will allow voters to begin casting their votes.')) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/election/start-voting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.$page.props.csrf_token || ''
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.$inertia.reload();
                    // Show success message
                    alert('✅ Voting period has been started successfully!');
                } else {
                    alert('❌ Error: ' + (data.error || 'Failed to start voting period'));
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            } finally {
                this.isLoading = false;
            }
        },

        async endVoting() {
            if (!confirm('Are you sure you want to end the voting period? This will prevent any new votes from being cast and terminate active voting sessions.')) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/election/end-voting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.$page.props.csrf_token || ''
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.$inertia.reload();
                    // Show success message
                    alert('✅ Voting period has been ended successfully!');
                } else {
                    alert('❌ Error: ' + (data.error || 'Failed to end voting period'));
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            } finally {
                this.isLoading = false;
            }
        },

        async bulkApproveVoters() {
            const ipCheckMsg = this.bulkApproveSettings.enableIpCheck
                ? 'with IP address checking enabled'
                : 'with IP address checking disabled';

            if (!confirm(`Are you sure you want to bulk approve all pending voters ${ipCheckMsg}? This will allow them to vote.`)) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/election/bulk-approve-voters', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.$page.props.csrf_token || ''
                    },
                    body: JSON.stringify({
                        enable_ip_check: this.bulkApproveSettings.enableIpCheck,
                        exclude_voted: false // Always include all for web interface
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.$inertia.reload();
                    // Show success message with details
                    const ipMsg = data.ip_check_enabled ? 'IP checking enabled' : 'IP checking disabled';
                    alert(`✅ Successfully approved ${data.approved_count} voters (${ipMsg})`);
                } else {
                    alert('❌ Error: ' + (data.error || 'Failed to approve voters'));
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            } finally {
                this.isLoading = false;
            }
        },

        async bulkDisapproveVoters() {
            const voteMsg = this.bulkDisapproveSettings.includeVoted
                ? 'including those who have already voted'
                : 'excluding those who have already voted';

            if (!confirm(`Are you sure you want to bulk disapprove all approved voters (${voteMsg})? This will prevent them from voting.`)) {
                return;
            }

            // Extra confirmation for dangerous operation
            if (this.bulkDisapproveSettings.includeVoted) {
                if (!confirm('⚠️ WARNING: This will disapprove voters who have already voted! Are you absolutely sure?')) {
                    return;
                }
            }

            this.isLoading = true;

            try {
                const response = await fetch('/election/bulk-disapprove-voters', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.$page.props.csrf_token || ''
                    },
                    body: JSON.stringify({
                        include_voted: this.bulkDisapproveSettings.includeVoted
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.$inertia.reload();
                    // Show success message with details
                    alert(`✅ Successfully disapproved ${data.disapproved_count} voters`);
                } else {
                    alert('❌ Error: ' + (data.error || 'Failed to disapprove voters'));
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>

<style scoped>
/* No custom styles needed - using Tailwind */
</style>