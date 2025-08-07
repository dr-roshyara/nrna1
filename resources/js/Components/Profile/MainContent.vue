<template>
    <main class="profile-page bg-gray-50 min-h-screen">
        <!-- Profile Header Section -->
        <section class="bg-white shadow-sm">
            <ProfileImage
                :user="user"
                :isLoggedIn="isLoggedIn"
                :userLoggedIn="userLoggedIn"
            />
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <!-- User Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold text-gray-900 truncate">
                                {{ user.name || 'Unknown User' }}
                            </h1>
                            
                            <!-- Location Information -->
                            <div v-if="userLocation" class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ userLocation }}</span>
                            </div>
                            
                            <!-- Designation -->
                            <div v-if="user.designation" class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 002 2M8 6a2 2 0 002 2v8a2 2 0 002 2h4a2 2 0 002-2v-8a2 2 0 002-2" />
                                </svg>
                                <span>{{ user.designation }}</span>
                            </div>
                            
                            <!-- Member Since -->
                            <div v-if="user.created_at" class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1M8 7a2 2 0 100 4h8a2 2 0 100-4M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7" />
                                </svg>
                                <span>Member since {{ formatDate(user.created_at) }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0 flex space-x-3">
                            <button
                                v-if="!userLoggedIn"
                                @click="handleConnect"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                :disabled="isConnecting"
                            >
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ isConnecting ? 'Connecting...' : 'Connect' }}
                            </button>
                            
                            <button
                                v-if="userLoggedIn"
                                @click="handleMessage"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Message
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profile Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Section -->
                    <ProfileSection 
                        title="Current Status"
                        icon="status"
                        :collapsible="false"
                    >
                        <div class="text-gray-700">
                            <p class="flex items-start">
                                <svg class="flex-shrink-0 mr-2 h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ user.status || 'Working on NRNA profile development and community engagement initiatives.' }}
                            </p>
                        </div>
                    </ProfileSection>

                    <!-- About Section -->
                    <ProfileSection 
                        title="About"
                        icon="user"
                        :collapsible="true"
                        :defaultExpanded="true"
                    >
                        <div class="prose prose-sm max-w-none text-gray-700">
                            <p>
                                {{ user.bio || 'This member has not provided additional information about themselves yet. Check back later for updates to their profile.' }}
                            </p>
                        </div>
                    </ProfileSection>

                    <!-- Reviews Section -->
                    <ProfileSection 
                        title="Reviews & Feedback"
                        icon="star"
                        :collapsible="true"
                        :defaultExpanded="false"
                    >
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <p class="text-sm">No reviews available yet</p>
                            <p class="text-xs mt-1">Reviews and feedback will appear here once available</p>
                        </div>
                    </ProfileSection>

                    <!-- Discussions Section -->
                    <ProfileSection 
                        title="Discussions & Opinions"
                        icon="chat"
                        :collapsible="true"
                        :defaultExpanded="true"
                    >
                        <Openions
                            :openionRoute="openionRoute"
                            :user="user"
                            :userLoggedIn="userLoggedIn"
                        />
                    </ProfileSection>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Information -->
                    <ProfileSection 
                        title="Contact Information"
                        icon="contact"
                        :collapsible="false"
                    >
                        <div class="space-y-3">
                            <div v-if="user.email" class="flex items-center text-sm text-gray-600">
                                <svg class="flex-shrink-0 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a :href="`mailto:${user.email}`" class="text-blue-600 hover:text-blue-800">
                                    {{ user.email }}
                                </a>
                            </div>
                            
                            <div v-if="user.phone" class="flex items-center text-sm text-gray-600">
                                <svg class="flex-shrink-0 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ user.phone }}</span>
                            </div>
                        </div>
                    </ProfileSection>

                    <!-- Quick Stats -->
                    <ProfileSection 
                        title="Profile Statistics"
                        icon="chart"
                        :collapsible="false"
                    >
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Profile Views</span>
                                <span class="font-medium text-gray-900">{{ user.profile_views || 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Connections</span>
                                <span class="font-medium text-gray-900">{{ user.connections_count || 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Posts</span>
                                <span class="font-medium text-gray-900">{{ user.posts_count || 0 }}</span>
                            </div>
                        </div>
                    </ProfileSection>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import ProfileImage from "@/Components/Profile/ProfileImage.vue";
import Openions from "@/Components/Discussion/Openions.vue";

// Reusable ProfileSection Component
const ProfileSection = {
    props: {
        title: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            default: 'default'
        },
        collapsible: {
            type: Boolean,
            default: false
        },
        defaultExpanded: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isExpanded: this.defaultExpanded
        }
    },
    computed: {
        iconSvg() {
            const icons = {
                status: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                user: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                star: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                chat: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                contact: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                default: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            };
            return icons[this.icon] || icons.default;
        }
    },
    template: `
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div 
                class="px-6 py-4 border-b border-gray-200 flex items-center justify-between cursor-pointer"
                :class="{ 'cursor-pointer': collapsible }"
                @click="collapsible && (isExpanded = !isExpanded)"
            >
                <div class="flex items-center">
                    <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconSvg" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
                </div>
                <svg 
                    v-if="collapsible"
                    class="h-5 w-5 text-gray-400 transition-transform duration-200"
                    :class="{ 'transform rotate-180': isExpanded }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div 
                v-show="isExpanded"
                class="px-6 py-4 transition-all duration-200"
            >
                <slot></slot>
            </div>
        </div>
    `
};

export default {
    name: 'MainContent',
    
    components: {
        ProfileImage,
        Openions,
        ProfileSection
    },
    
    props: {
        user: {
            type: Object,
            required: true,
            default: () => ({})
        },
        isLoggedIn: {
            type: Boolean,
            default: false
        },
        userLoggedIn: {
            type: Boolean,
            default: false
        }
    },
    
    data() {
        return {
            isConnecting: false
        };
    },
    
    computed: {
        userId() {
            return this.user?.id || null;
        },
        
        openionRoute() {
            return this.userId ? `/openions/${this.userId}` : '#';
        },
        
        userLocation() {
            const parts = [];
            if (this.user?.city) parts.push(this.user.city);
            if (this.user?.country) parts.push(this.user.country);
            return parts.length > 0 ? parts.join(', ') : null;
        }
    },
    
    methods: {
        async handleConnect() {
            if (this.isConnecting) return;
            
            this.isConnecting = true;
            try {
                // Simulate connection request - replace with actual API call
                await this.sendConnectionRequest();
                this.$toast.success('Connection request sent successfully!');
            } catch (error) {
                console.error('Failed to send connection request:', error);
                this.$toast.error('Failed to send connection request. Please try again.');
            } finally {
                this.isConnecting = false;
            }
        },
        
        handleMessage() {
            // Navigate to messaging interface
            this.$inertia.visit(`/messages/new/${this.userId}`);
        },
        
        async sendConnectionRequest() {
            // Simulate API delay
            return new Promise((resolve) => {
                setTimeout(resolve, 1000);
            });
        },
        
        formatDate(dateString) {
            if (!dateString) return 'Unknown';
            
            try {
                return new Date(dateString).getFullYear().toString();
            } catch (error) {
                return 'Unknown';
            }
        }
    },
    
    mounted() {
        // Component initialization logic
        if (this.userId) {
            console.log(`Profile loaded for user: ${this.userId}`);
        }
    }
};
</script>

<style scoped>
.profile-page {
    @apply transition-all duration-300 ease-in-out;
}

/* Smooth transitions for interactive elements */
button {
    @apply transition-all duration-200 ease-in-out;
}

button:disabled {
    @apply opacity-50 cursor-not-allowed;
}

/* Custom scrollbar for better UX */
.prose::-webkit-scrollbar {
    width: 6px;
}

.prose::-webkit-scrollbar-track {
    @apply bg-gray-100;
}

.prose::-webkit-scrollbar-thumb {
    @apply bg-gray-300 rounded-full;
}

.prose::-webkit-scrollbar-thumb:hover {
    @apply bg-gray-400;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .profile-page {
        @apply px-2;
    }
}

/* Loading state animations */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>