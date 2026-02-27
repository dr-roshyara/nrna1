<template>
    <nav class="flex flex-col bg-publicdigit-header shadow-2xl border-b border-purple-700/30">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo Section -->
                <div class="shrink-0">
                    <Link :href="route('dashboard')" class="flex items-center">
                        <img
                            class="h-10 w-10 md:h-12 md:w-12 object-contain"
                            src="/images/logo_publicdigit.png"
                            alt="PublicDigit Logo"
                            width="60"
                            height="60"
                        />
                        <span class="ml-3 text-xl font-semibold text-white hidden sm:block">
                            PublicDigit
                        </span>
                    </Link>
                </div>

                <!-- Primary Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <Link
                        :href="route('dashboard')"
                        class="flex flex-col items-center px-3 py-2 rounded-md text-sm font-medium transition-colors"
                        :class="isDashboardActive ? 'text-purple-200 bg-purple-700/50' : 'text-purple-200 hover:text-white hover:bg-purple-700/30'"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-xs mt-1">Dashboard</span>
                    </Link>

                    <Link
                        :href="userProfileUrl"
                        class="flex flex-col items-center px-3 py-2 rounded-md text-sm font-medium transition-colors"
                        :class="isProfileActive ? 'text-purple-200 bg-purple-700/50' : 'text-purple-200 hover:text-white hover:bg-purple-700/30'"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-xs mt-1">Network</span>
                    </Link>

                    <Link
                        v-if="isLoggedIn && userLoggedIn"
                        :href="userProfileUrl"
                        class="flex flex-col items-center px-3 py-2 rounded-md text-sm font-medium transition-colors"
                        :class="isProfileActive ? 'text-purple-200 bg-purple-700/50' : 'text-purple-200 hover:text-white hover:bg-purple-700/30'"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        <span class="text-xs mt-1">Profile</span>
                    </Link>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Authentication Section -->
                    <div class="flex items-center space-x-3">
                        <!-- Logout -->
                        <form v-if="isLoggedIn" @submit.prevent="logout" class="flex items-center">
                            <button
                                type="submit"
                                class="flex flex-col items-center text-purple-200 hover:text-white transition-colors"
                                title="Logout"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="text-xs mt-1">Logout</span>
                            </button>
                        </form>

                        <!-- Mobile menu button -->
                        <button
                            class="md:hidden flex flex-col items-center text-purple-200 hover:text-white transition-colors"
                            @click="toggleMobileMenu"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span class="text-xs mt-1">Menu</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    name: 'ProfileHeader',

    components: {
        Link
    },

    props: {
        canLogin: {
            type: Boolean,
            default: false
        },
        canRegister: {
            type: Boolean,
            default: false
        },
        isLoggedIn: {
            type: Boolean,
            default: false
        },
        user: {
            type: Object,
            default: () => ({
                profileEditUrl: '/profile'
            })
        },
        userLoggedIn: {
            type: Boolean,
            default: false
        }
    },

    emits: ['toggleMobileMenu'],

    computed: {
        userProfileUrl() {
            if (this.user && this.user.profileEditUrl) {
                return this.user.profileEditUrl;
            }
            return '/profile';
        },

        isDashboardActive() {
            if (!this.$page || !this.$page.url) {
                return false;
            }
            return this.$page.url === '/dashboard';
        },

        isProfileActive() {
            if (!this.$page || !this.$page.url) {
                return false;
            }
            return this.$page.url.startsWith('/profile');
        }
    },

    methods: {
        logout() {
            if (this.$inertia) {
                this.$inertia.post(route("logout"));
            }
        },

        toggleMobileMenu() {
            this.$emit('toggleMobileMenu');
        }
    }
};
</script>

<style scoped>
/* PublicDigit Header Background */
.bg-publicdigit-header {
    background: oklch(37.9% 0.146 265.522);
}

@supports not (color: oklch(0% 0 0)) {
  .bg-publicdigit-header {
    background: #3d3d7a; /* Fallback color */
  }
}

nav {
    transition: all 0.3s ease;
}

.flex.flex-col.items-center {
    transition: all 0.2s ease-in-out;
}

.flex.flex-col.items-center:hover {
    transform: translateY(-2px);
}

.text-purple-200.bg-purple-700\/50 {
    box-shadow: 0 2px 4px rgba(168, 85, 247, 0.3);
}

@media (max-width: 767px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

a:focus,
button:focus {
    outline: 2px solid #a855f7;
    outline-offset: 2px;
    border-radius: 0.375rem;
}

svg {
    transition: transform 0.2s ease;
}

button:hover svg,
a:hover svg {
    transform: scale(1.1);
}
</style>
