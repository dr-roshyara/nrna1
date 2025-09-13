<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-3">
                                <Link
                                    :href="route('admin.elections.index')"
                                    class="text-gray-400 hover:text-gray-600"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                </Link>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ election.name }}</h1>
                                    <p class="mt-1 text-sm text-gray-600">{{ election.description }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Status Badge -->
                            <span 
                                :class="getStatusBadgeClass(election.status)"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            >
                                {{ getStatusLabel(election.status) }}
                            </span>
                            
                            <!-- Phase Badge -->
                            <span 
                                :class="getPhaseBadgeClass(election.current_phase)"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            >
                                {{ getPhaseIcon(election.current_phase) }} {{ getPhaseLabel(election.current_phase) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Votes</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ election.total_votes }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Positions</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ election.total_positions }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Candidates</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ election.total_candidates }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Days Old</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ getElectionAge(election) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Tabs -->
                <div class="bg-white shadow rounded-lg">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="currentTab = tab.id"
                                :class="[
                                    currentTab === tab.id
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm'
                                ]"
                            >
                                {{ tab.name }}
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- Overview Tab -->
                        <div v-show="currentTab === 'overview'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Basic Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Election ID</dt>
                                            <dd class="text-sm text-gray-900">{{ election.id }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                                            <dd class="text-sm text-gray-900">{{ election.name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                                            <dd class="text-sm text-gray-900">{{ election.description }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                            <dd class="text-sm text-gray-900">{{ election.timezone }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                                            <dd class="text-sm text-gray-900">{{ formatDate(election.created_at) }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                            <dd class="text-sm text-gray-900">{{ formatDate(election.updated_at) }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Settings -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Election Settings</h3>
                                    <dl class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Auto Phase Transition</dt>
                                            <dd>
                                                <span :class="election.auto_phase_transition ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                    {{ election.auto_phase_transition ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Email Notifications</dt>
                                            <dd>
                                                <span :class="election.notification_enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                    {{ election.notification_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Public Registration</dt>
                                            <dd>
                                                <span :class="election.public_registration ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                    {{ election.public_registration ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Tab -->
                        <div v-show="currentTab === 'timeline'">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Election Timeline</h3>
                            
                            <div class="space-y-6">
                                <!-- Timeline Visual -->
                                <div class="relative">
                                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200" aria-hidden="true"></div>
                                    
                                    <!-- Registration Phase -->
                                    <div class="relative flex items-start space-x-3 pb-6">
                                        <div class="relative">
                                            <div :class="getTimelineStepClass('registration')" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-medium text-white">1</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">Registration Phase</div>
                                            <div class="text-sm text-gray-500">
                                                {{ formatDate(election.registration_start) }} - {{ formatDate(election.registration_end) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nomination Phase -->
                                    <div class="relative flex items-start space-x-3 pb-6">
                                        <div class="relative">
                                            <div :class="getTimelineStepClass('nomination')" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-medium text-white">2</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">Candidate Nomination</div>
                                            <div class="text-sm text-gray-500">
                                                {{ formatDate(election.candidate_nomination_start) }} - {{ formatDate(election.candidate_nomination_end) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Voting Phase -->
                                    <div class="relative flex items-start space-x-3 pb-6">
                                        <div class="relative">
                                            <div :class="getTimelineStepClass('voting')" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-medium text-white">3</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">Voting Period</div>
                                            <div class="text-sm text-gray-500">
                                                {{ formatDate(election.voting_start_time) }} - {{ formatDate(election.voting_end_time) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Authorization Phase -->
                                    <div class="relative flex items-start space-x-3 pb-6">
                                        <div class="relative">
                                            <div :class="getTimelineStepClass('authorization')" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-medium text-white">4</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">Publisher Authorization</div>
                                            <div class="text-sm text-gray-500">
                                                Deadline: {{ formatDate(election.authorization_deadline) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Results Publication -->
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div :class="getTimelineStepClass('results')" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-medium text-white">5</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">Results Publication</div>
                                            <div class="text-sm text-gray-500">
                                                {{ formatDate(election.result_publication_date) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Edit Button -->
                                <div v-if="election.can_edit" class="pt-4 border-t border-gray-200">
                                    <button
                                        @click="showTimelineEditor = true"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Timeline
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Tab -->
                        <div v-show="currentTab === 'actions'">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Election Management</h3>
                            
                            <div class="space-y-6">
                                <!-- Status Management -->
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-base font-medium text-gray-900 mb-4">Status Management</h4>
                                    <div class="space-y-4">
                                        <!-- Current Status -->
                                        <div>
                                            <label class="text-sm font-medium text-gray-700">Current Status</label>
                                            <div class="mt-1">
                                                <span :class="getStatusBadgeClass(election.status)" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ getStatusLabel(election.status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Phase Transition -->
                                        <div v-if="election.can_edit">
                                            <label class="text-sm font-medium text-gray-700">Change Status</label>
                                            <div class="mt-1 flex space-x-3">
                                                <button
                                                    v-if="election.can_activate"
                                                    @click="transitionPhase('active')"
                                                    :disabled="isTransitioning"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                                                >
                                                    <span v-if="isTransitioning">Activating...</span>
                                                    <span v-else>Activate Election</span>
                                                </button>
                                                
                                                <button
                                                    v-if="election.status === 'active'"
                                                    @click="transitionPhase('voting')"
                                                    :disabled="isTransitioning"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
                                                >
                                                    <span v-if="isTransitioning">Starting...</span>
                                                    <span v-else>Start Voting</span>
                                                </button>
                                                
                                                <button
                                                    v-if="election.status === 'voting'"
                                                    @click="transitionPhase('completed')"
                                                    :disabled="isTransitioning"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                                                >
                                                    <span v-if="isTransitioning">Completing...</span>
                                                    <span v-else>Complete Election</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-base font-medium text-gray-900 mb-4">Quick Actions</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <Link
                                            :href="route('post.index')"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            Manage Positions
                                        </Link>
                                        
                                        <Link
                                            :href="route('candidacy.index')"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                            View Candidates
                                        </Link>
                                        
                                        <Link
                                            :href="route('voters.index')"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Voter List
                                        </Link>
                                        
                                        <Link
                                            v-if="election.total_votes > 0"
                                            :href="route('result.index')"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            View Results
                                        </Link>
                                    </div>
                                </div>

                                <!-- Danger Zone -->
                                <div v-if="election.can_delete" class="bg-red-50 border border-red-200 rounded-lg p-6">
                                    <h4 class="text-base font-medium text-red-900 mb-4">Danger Zone</h4>
                                    <p class="text-sm text-red-700 mb-4">
                                        Once you delete an election, there is no going back. Please be certain.
                                    </p>
                                    <button
                                        @click="showDeleteConfirmation = true"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete Election
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Editor Modal -->
                <div v-if="showTimelineEditor" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Edit Election Timeline
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Voting Start</label>
                                        <input
                                            v-model="timelineForm.voting_start_time"
                                            type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Voting End</label>
                                        <input
                                            v-model="timelineForm.voting_end_time"
                                            type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Authorization Deadline</label>
                                        <input
                                            v-model="timelineForm.authorization_deadline"
                                            type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Result Publication</label>
                                        <input
                                            v-model="timelineForm.result_publication_date"
                                            type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button
                                    @click="updateTimeline"
                                    :disabled="isUpdatingTimeline"
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                                >
                                    <span v-if="isUpdatingTimeline">Updating...</span>
                                    <span v-else>Update Timeline</span>
                                </button>
                                <button
                                    @click="showTimelineEditor = false"
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div v-if="showDeleteConfirmation" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Delete Election
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Are you sure you want to delete "{{ election.name }}"? This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button
                                    @click="deleteElection"
                                    :disabled="isDeleting"
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                                >
                                    <span v-if="isDeleting">Deleting...</span>
                                    <span v-else>Delete</span>
                                </button>
                                <button
                                    @click="showDeleteConfirmation = false"
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'ElectionShow',
    
    components: {
        AppLayout,
        Link,
    },
    
    props: {
        election: {
            type: Object,
            required: true,
        },
    },
    
    data() {
        return {
            currentTab: 'overview',
            tabs: [
                { id: 'overview', name: 'Overview' },
                { id: 'timeline', name: 'Timeline' },
                { id: 'actions', name: 'Actions' },
            ],
            showTimelineEditor: false,
            showDeleteConfirmation: false,
            isTransitioning: false,
            isUpdatingTimeline: false,
            isDeleting: false,
            timelineForm: {
                voting_start_time: '',
                voting_end_time: '',
                authorization_deadline: '',
                result_publication_date: '',
            },
        };
    },
    
    mounted() {
        this.initializeTimelineForm();
    },
    
    methods: {
        initializeTimelineForm() {
            this.timelineForm = {
                voting_start_time: this.formatDateTimeLocal(this.election.voting_start_time),
                voting_end_time: this.formatDateTimeLocal(this.election.voting_end_time),
                authorization_deadline: this.formatDateTimeLocal(this.election.authorization_deadline),
                result_publication_date: this.formatDateTimeLocal(this.election.result_publication_date),
            };
        },
        
        formatDateTimeLocal(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toISOString().slice(0, 16);
        },
        
        formatDate(dateString) {
            if (!dateString) return 'Not Set';
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },
        
        getElectionAge(election) {
            const now = new Date();
            const created = new Date(election.created_at);
            const diffTime = now - created;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        },
        
        getStatusBadgeClass(status) {
            const classes = {
                'draft': 'bg-gray-100 text-gray-800',
                'upcoming': 'bg-blue-100 text-blue-800', 
                'active': 'bg-green-100 text-green-800',
                'voting': 'bg-purple-100 text-purple-800',
                'completed': 'bg-gray-100 text-gray-800',
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },
        
        getStatusLabel(status) {
            const labels = {
                'draft': 'Draft',
                'upcoming': 'Upcoming', 
                'active': 'Active',
                'voting': 'Voting',
                'completed': 'Completed',
            };
            return labels[status] || status.charAt(0).toUpperCase() + status.slice(1);
        },
        
        getPhaseBadgeClass(phase) {
            const classes = {
                'upcoming': 'bg-blue-100 text-blue-800',
                'registration': 'bg-green-100 text-green-800',
                'preparation': 'bg-yellow-100 text-yellow-800',
                'voting': 'bg-purple-100 text-purple-800',
                'authorization': 'bg-orange-100 text-orange-800',
                'completed': 'bg-gray-100 text-gray-800',
            };
            return classes[phase] || 'bg-gray-100 text-gray-800';
        },
        
        getPhaseIcon(phase) {
            const icons = {
                'upcoming': '⏰',
                'registration': '👥',
                'preparation': '⚙️', 
                'voting': '🗳️',
                'authorization': '🔒',
                'completed': '✅',
            };
            return icons[phase] || '📋';
        },
        
        getPhaseLabel(phase) {
            const labels = {
                'upcoming': 'Upcoming',
                'registration': 'Registration',
                'preparation': 'Preparation',
                'voting': 'Voting', 
                'authorization': 'Authorization',
                'completed': 'Completed',
            };
            return labels[phase] || phase.charAt(0).toUpperCase() + phase.slice(1);
        },
        
        getTimelineStepClass(step) {
            const currentPhase = this.election.current_phase;
            const stepOrder = ['upcoming', 'registration', 'nomination', 'voting', 'authorization', 'results'];
            const currentIndex = stepOrder.indexOf(currentPhase);
            const stepIndex = stepOrder.indexOf(step);
            
            if (stepIndex <= currentIndex) {
                return 'bg-blue-600';
            } else {
                return 'bg-gray-300';
            }
        },
        
        transitionPhase(newPhase) {
            if (this.isTransitioning) return;
            
            this.isTransitioning = true;
            
            Inertia.post(route('admin.elections.transition', this.election.id), {
                phase: newPhase,
            }, {
                onSuccess: () => {
                    this.isTransitioning = false;
                },
                onError: () => {
                    this.isTransitioning = false;
                },
            });
        },
        
        updateTimeline() {
            if (this.isUpdatingTimeline) return;
            
            this.isUpdatingTimeline = true;
            
            Inertia.put(route('admin.elections.timeline.update', this.election.id), this.timelineForm, {
                onSuccess: () => {
                    this.isUpdatingTimeline = false;
                    this.showTimelineEditor = false;
                },
                onError: () => {
                    this.isUpdatingTimeline = false;
                },
            });
        },
        
        deleteElection() {
            if (this.isDeleting) return;
            
            // Check if route exists before calling it
            try {
                const deleteRoute = route('admin.elections.destroy', this.election.id);
                console.log('Delete route:', deleteRoute);
            } catch (error) {
                console.error('Route admin.elections.destroy not found:', error);
                alert('Delete functionality not available. Please contact administrator.');
                return;
            }
            
            this.isDeleting = true;
            
            Inertia.delete(route('admin.elections.destroy', this.election.id), {
                onSuccess: (page) => {
                    this.isDeleting = false;
                    this.showDeleteConfirmation = false;
                    // Redirect to elections index with success message
                },
                onError: (errors) => {
                    this.isDeleting = false;
                    console.error('Delete failed:', errors);
                    alert('Failed to delete election. Check console for details.');
                },
                onFinish: () => {
                    this.isDeleting = false;
                }
            });
        },
    },
};
</script>