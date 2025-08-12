<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Election Management</h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Configure and manage election timelines, phases, and settings
                            </p>
                        </div>
                        <div>
                            <Link
                                v-if="canCreateNew"
                                :href="route('admin.elections.create')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create New Election
                            </Link>
                            <div v-else class="text-sm text-gray-500 text-center">
                                <div class="inline-flex items-center px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <span class="text-yellow-800">Cannot create new election while another is active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Elections Grid -->
                <div v-if="elections.length > 0" class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="election in elections"
                        :key="election.id"
                        class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                    >
                        <!-- Election Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate mb-1">
                                        {{ election.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ election.description }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <span 
                                        :class="getStatusBadgeClass(election.status)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ getStatusLabel(election.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Status -->
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">Current Phase</span>
                                <span 
                                    :class="getPhaseBadgeClass(election.timeline_status)"
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium"
                                >
                                    {{ getPhaseIcon(election.timeline_status) }} {{ getPhaseLabel(election.timeline_status) }}
                                </span>
                            </div>

                            <!-- Timeline Progress -->
                            <div class="space-y-3">
                                <!-- Key Dates -->
                                <div class="text-xs text-gray-600 space-y-1">
                                    <div class="flex justify-between">
                                        <span>📝 Registration:</span>
                                        <span>{{ formatDateRange(election.registration_start, election.registration_end) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🗳️ Voting:</span>
                                        <span>{{ formatDateRange(election.voting_start_time, election.voting_end_time) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>📊 Results:</span>
                                        <span>{{ formatDate(election.result_publication_date) }}</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span>Progress</span>
                                        <span>{{ getTimelineProgress(election.timeline_status) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div 
                                            :class="getTimelineProgressClass(election.timeline_status)"
                                            class="h-2 rounded-full transition-all duration-500 ease-out"
                                            :style="{ width: getTimelineProgress(election.timeline_status) + '%' }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Election Stats -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-xl font-bold text-blue-600">{{ election.vote_count || 0 }}</div>
                                    <div class="text-xs text-gray-600">Votes</div>
                                </div>
                                <div>
                                    <div class="text-xl font-bold text-green-600">
                                        {{ getDaysRemaining(election) }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ getDaysRemainingLabel(election) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xl font-bold text-purple-600">
                                        {{ getElectionAge(election) }}
                                    </div>
                                    <div class="text-xs text-gray-600">Days Old</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <Link
                                    :href="route('admin.elections.show', election.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </Link>
                                <!-- Edit/Configure Button - Simple fix -->
                                <Link
                                    v-if="election.can_edit"
                                    :href="route('admin.elections.show', election.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Configure
                                </Link>
                                <button
                                    v-else
                                    disabled
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-200 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Locked
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16">
                    <div class="mx-auto w-24 h-24 text-gray-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No elections found</h3>
                    <p class="text-gray-600 mb-8 max-w-sm mx-auto">
                        Get started by creating your first election to manage voter registration, candidate nominations, and voting processes.
                    </p>
                    <div v-if="canCreateNew">
                        <Link
                            :href="route('admin.elections.create')"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Your First Election
                        </Link>
                    </div>
                </div>

                <!-- Quick Stats (if elections exist) -->
                <div v-if="elections.length > 0" class="mt-8 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ elections.length }}</div>
                            <div class="text-sm text-gray-600">Total Elections</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ getActiveElectionsCount() }}</div>
                            <div class="text-sm text-gray-600">Active Elections</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ getTotalVotes() }}</div>
                            <div class="text-sm text-gray-600">Total Votes Cast</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ getCompletedElectionsCount() }}</div>
                            <div class="text-sm text-gray-600">Completed Elections</div>
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

export default {
    name: 'ElectionsIndex',
    
    components: {
        AppLayout,
        Link,
    },
    
    props: {
        elections: {
            type: Array,
            required: true,
        },
        canCreateNew: {
            type: Boolean,
            default: false,
        },
    },
    
    methods: {
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
        
        getTimelineProgressClass(phase) {
            const classes = {
                'upcoming': 'bg-blue-500',
                'registration': 'bg-green-500',
                'preparation': 'bg-yellow-500',
                'voting': 'bg-purple-500',
                'authorization': 'bg-orange-500', 
                'completed': 'bg-gray-500',
            };
            return classes[phase] || 'bg-gray-300';
        },
        
        getTimelineProgress(phase) {
            const progress = {
                'upcoming': 0,
                'registration': 20,
                'preparation': 40,
                'voting': 70,
                'authorization': 85,
                'completed': 100,
            };
            return progress[phase] || 0;
        },
        
        getDaysRemaining(election) {
            const now = new Date();
            const target = new Date(election.voting_start_time);
            const diffTime = target - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays < 0) return 0;
            return diffDays;
        },
        
        getDaysRemainingLabel(election) {
            const days = this.getDaysRemaining(election);
            if (days === 0) return 'Today';
            if (days === 1) return 'Day Left';
            if (days < 0) return 'Ended';
            return 'Days Left';
        },

        getElectionAge(election) {
            const now = new Date();
            const created = new Date(election.created_at);
            const diffTime = now - created;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        },
        
        formatDate(dateString) {
            if (!dateString) return 'Not Set';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            });
        },

        formatDateRange(startDate, endDate) {
            if (!startDate || !endDate) return 'Not Set';
            
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            const startStr = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const endStr = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            
            return `${startStr} - ${endStr}`;
        },

        // Quick stats methods
        getActiveElectionsCount() {
            return this.elections.filter(e => ['active', 'voting'].includes(e.status)).length;
        },

        getTotalVotes() {
            return this.elections.reduce((total, election) => total + (election.vote_count || 0), 0);
        },

        getCompletedElectionsCount() {
            return this.elections.filter(e => e.status === 'completed').length;
        },
    },
};
</script>

<style scoped>
/* Line clamp for description text */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for hover effects */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Card hover effects */
.transform {
    --tw-translate-x: 0;
    --tw-translate-y: 0;
    --tw-rotate: 0;
    --tw-skew-x: 0;
    --tw-skew-y: 0;
    --tw-scale-x: 1;
    --tw-scale-y: 1;
    transform: translateX(var(--tw-translate-x)) translateY(var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
}

.hover\:-translate-y-1:hover {
    --tw-translate-y: -0.25rem;
}

/* Progress bar animations */
@keyframes progressFill {
    from { width: 0%; }
    to { width: var(--progress-width); }
}

/* Custom scrollbar for responsive design */
@media (max-width: 640px) {
    .grid-cols-1 {
        gap: 1rem;
    }
}
</style>