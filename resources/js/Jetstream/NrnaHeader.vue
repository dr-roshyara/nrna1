<template>
    <div class="bg-gradient-to-r from-blue-900 via-indigo-800 to-blue-900 shadow-2xl">
        <!-- Top Bar with Contact Info and Quick Links -->
        <div class="bg-blue-950/50 border-b border-blue-700/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-2 text-sm">
                    <!-- Contact Information -->
                    <div class="hidden md:flex items-center space-x-6 text-blue-200">
                        <a href="mailto:info@nrna.org" class="flex items-center hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            de.election@nrna.org
                        </a>
                        <a href="tel:+49-1622383846" class="flex items-center hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +49-1622383846
                        </a>
                    </div>

                    <!-- Quick Links and Social Media -->
                    <div class="flex items-center space-x-4">
                        <!-- Social Media Links -->
                        <div class="hidden sm:flex items-center space-x-3">
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-200" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-200" aria-label="Twitter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-200" aria-label="YouTube">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Language Selector -->
                        <div class="relative">
                            <select 
                                v-model="currentLanguage"
                                @change="$emit('language-changed', currentLanguage)"
                                class="bg-blue-800 text-blue-100 border border-blue-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200"
                            >
                                <option value="en">English</option>
                                <option value="ne">नेपाली</option>
                            </select>
                        </div>

                        <!-- Quick Links -->
                        <div class="hidden lg:flex items-center space-x-4 text-blue-200">
                            <a href="/help" class="hover:text-white transition-colors duration-200">Help</a>
                            <span class="text-blue-600">|</span>
                            <a href="/contact" class="hover:text-white transition-colors duration-200">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="relative bg-pattern">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-6">
                    <!-- Logo and Organization Info -->
                    <div class="flex items-center space-x-6">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <logo class="transform hover:scale-105 transition-transform duration-200" />
                        </div>

                        <!-- Organization Details -->
                        <div class="hidden md:block">
                            <div class="flex flex-col">
                                <h1 class="text-2xl lg:text-3xl font-bold text-white leading-tight">
                                    Non Resident Nepali Association
                                </h1>
                                <h2 class="text-lg lg:text-xl font-semibold text-blue-200 leading-tight">
                                    गैर आवासीय नेपाली संघ (NRNA)
                                </h2>
                                <!-- <p class="text-sm text-blue-300 mt-1">
                                    Germany Chapter - जर्मनी शाखा
                                </p> -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Controls -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Button (Desktop) -->
                        <button 
                            v-if="showSearch"
                            @click="toggleSearch"
                            class="hidden md:flex items-center px-4 py-2 bg-blue-700/50 border border-blue-600 rounded-lg text-blue-100 hover:bg-blue-600/50 hover:text-white transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>

                        <!-- User Account Controls (if logged in) -->
                        <div v-if="isLoggedIn" class="hidden md:flex items-center space-x-3">
                            <!-- Notifications -->
                            <button class="relative p-2 text-blue-200 hover:text-white hover:bg-blue-700/50 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a11 11 0 01-1.5-5.5V5a2 2 0 10-4 0v3a11 11 0 01-1.5 5.5L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span v-if="hasNotifications" class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button 
                                    @click="toggleUserMenu"
                                    class="flex items-center space-x-2 p-2 bg-blue-700/50 border border-blue-600 rounded-lg text-blue-100 hover:bg-blue-600/50 hover:text-white transition-all duration-200"
                                >
                                    <img 
                                        :src="userAvatar || '/images/default-avatar.jpg'" 
                                        alt="User Avatar" 
                                        class="w-6 h-6 rounded-full"
                                    />
                                    <span class="text-sm font-medium">{{ userName || 'User' }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                    <div class="py-1">
                                        <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                        <a href="/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Settings
                                        </a>
                                        <hr class="my-1">
                                        <button @click="logout" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Login/Register Buttons (if not logged in) -->
                        <div v-else class="hidden md:flex items-center space-x-3">
                            <a v-if="canLogin" 
                               href="/login" 
                               class="px-4 py-2 bg-transparent border border-blue-400 text-blue-100 rounded-lg hover:bg-blue-700/50 hover:border-blue-300 transition-all duration-200">
                                Login
                            </a>
                            <a v-if="canRegister" 
                               href="/register" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                                Register
                            </a>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden">
                            <button 
                                @click="toggleMobileMenu"
                                class="inline-flex items-center justify-center p-2 rounded-lg text-blue-200 hover:text-white hover:bg-blue-700/50 transition-all duration-200"
                                aria-label="Mobile menu"
                            >
                                <menu-icon :isOpen="showMobileMenu" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Organization Title -->
                <div class="md:hidden pb-4">
                    <h1 class="text-xl font-bold text-white text-center leading-tight">
                        Non Resident Nepali Association (NRNA)
                    </h1>
                    <p class="text-sm text-blue-200 text-center mt-1">
                        गैर आवासीय नेपाली संघ - Germany Chapter
                    </p>
                </div>
            </div>
        </div>

        <!-- Search Bar (when expanded) -->
        <transition name="search-expand">
            <div v-if="searchExpanded" class="bg-blue-800/50 border-t border-blue-600/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="relative max-w-lg mx-auto">
                        <input 
                            ref="searchInput"
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Search NRNA website..."
                            class="w-full pl-10 pr-4 py-2 bg-white/10 border border-blue-400 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                            @keyup.enter="performSearch"
                        />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Mobile Menu -->
        <transition name="mobile-menu">
            <div v-if="showMobileMenu" class="md:hidden bg-blue-800/95 border-t border-blue-600/30">
                <div class="px-4 py-4 space-y-4">
                    <!-- Mobile Search -->
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search..."
                            class="w-full pl-10 pr-4 py-2 bg-white/10 border border-blue-400 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Mobile User Actions -->
                    <div v-if="!isLoggedIn" class="space-y-2">
                        <a v-if="canLogin" href="/login" class="block w-full px-4 py-2 bg-transparent border border-blue-400 text-blue-100 rounded-lg text-center hover:bg-blue-700/50 transition-all duration-200">
                            Login
                        </a>
                        <a v-if="canRegister" href="/register" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700 transition-all duration-200">
                            Register
                        </a>
                    </div>

                    <!-- Mobile Contact Info -->
                    <div class="pt-4 border-t border-blue-600/30 space-y-2 text-sm text-blue-200">
                        <a href="mailto:de.election@nrna.org" class="flex items-center hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            de.election@nrna.org
                        </a>
                        <a href="tel:+49-1622383846" class="flex items-center hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +49-1622383846
                        </a>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import Logo from "@/Components/Header/Logo.vue";
import MenuIcon from "@/Components/Header/Menu.vue";

export default {
    name: 'NrnaHeader',
    
    components: {
        Logo,
        MenuIcon,
    },
    
    props: {
        canLogin: {
            type: Boolean,
            default: true
        },
        canRegister: {
            type: Boolean,
            default: true
        },
        isScrolled: {
            type: Boolean,
            default: false
        },
        showSearch: {
            type: Boolean,
            default: true
        }
    },
    
    data() {
        return {
            showMobileMenu: false,
            showUserMenu: false,
            searchExpanded: false,
            searchQuery: '',
            currentLanguage: 'en',
            isLoggedIn: false, // This should come from your auth system
            userName: 'John Doe', // This should come from your auth system
            userAvatar: null, // This should come from your auth system
            hasNotifications: true
        };
    },
    
    mounted() {
        // Check authentication status
        this.checkAuthStatus();
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', this.handleClickOutside);
        
        // Listen for escape key to close menus
        document.addEventListener('keydown', this.handleKeydown);
    },
    
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
        document.removeEventListener('keydown', this.handleKeydown);
    },
    
    methods: {
        toggleMobileMenu() {
            this.showMobileMenu = !this.showMobileMenu;
            this.showUserMenu = false;
        },
        
        toggleUserMenu() {
            this.showUserMenu = !this.showUserMenu;
            this.showMobileMenu = false;
        },
        
        toggleSearch() {
            this.searchExpanded = !this.searchExpanded;
            if (this.searchExpanded) {
                this.$nextTick(() => {
                    if (this.$refs.searchInput) {
                        this.$refs.searchInput.focus();
                    }
                });
            }
        },
        
        performSearch() {
            if (this.searchQuery.trim()) {
                // Implement search functionality
                console.log('Searching for:', this.searchQuery);
                // Redirect to search results page or perform search
                window.location.href = `/search?q=${encodeURIComponent(this.searchQuery)}`;
            }
        },
        
        checkAuthStatus() {
            // This should integrate with your authentication system
            // For now, it's a mock implementation
            const token = localStorage.getItem('auth_token');
            this.isLoggedIn = !!token;
            
            if (this.isLoggedIn) {
                // Load user data
                this.userName = localStorage.getItem('user_name') || 'User';
                this.userAvatar = localStorage.getItem('user_avatar');
            }
        },
        
        logout() {
            // Implement logout functionality
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_name');
            localStorage.removeItem('user_avatar');
            this.isLoggedIn = false;
            this.showUserMenu = false;
            
            // Redirect to login page or home
            window.location.href = '/login';
        },
        
        handleClickOutside(event) {
            // Close user menu if clicking outside
            if (!event.target.closest('.relative')) {
                this.showUserMenu = false;
            }
            
            // Close mobile menu if clicking outside
            if (!event.target.closest('.md\\:hidden') && !event.target.closest('[aria-label="Mobile menu"]')) {
                this.showMobileMenu = false;
            }
        },
        
        handleKeydown(event) {
            // Close menus on Escape key
            if (event.key === 'Escape') {
                this.showMobileMenu = false;
                this.showUserMenu = false;
                this.searchExpanded = false;
            }
            
            // Toggle search with Ctrl+K or Cmd+K
            if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                event.preventDefault();
                this.toggleSearch();
            }
        }
    },
    
    watch: {
        currentLanguage(newLang) {
            this.$emit('language-changed', newLang);
            localStorage.setItem('preferred_language', newLang);
        }
    }
};
</script>

<style scoped>
/* Custom background pattern */
.bg-pattern {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 30px 30px, 20px 20px;
    background-position: 0 0, 15px 15px;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Custom backdrop blur */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Enhanced focus states */
button:focus,
a:focus,
select:focus,
input:focus {
    outline: 2px solid #60a5fa;
    outline-offset: 2px;
}

/* Dropdown animation */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Mobile menu animation */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
    transition: all 0.3s ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Search bar animation */
.search-expand-enter-active,
.search-expand-leave-active {
    transition: all 0.3s ease;
}

.search-expand-enter-from,
.search-expand-leave-to {
    opacity: 0;
    max-height: 0;
    transform: translateY(-10px);
}

.search-expand-enter-to,
.search-expand-leave-from {
    opacity: 1;
    max-height: 100px;
    transform: translateY(0);
}

/* Hover effects for social icons */
.social-icon {
    transition: all 0.2s ease;
}

.social-icon:hover {
    transform: translateY(-2px);
}

/* Custom scrollbar for dropdowns */
.dropdown-content {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.dropdown-content::-webkit-scrollbar {
    width: 6px;
}

.dropdown-content::-webkit-scrollbar-track {
    background: transparent;
}

.dropdown-content::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}

/* Responsive improvements */
@media (max-width: 640px) {
    .text-2xl {
        font-size: 1.25rem;
    }
    
    .text-3xl {
        font-size: 1.5rem;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .text-blue-200 {
        color: #ffffff;
    }
    
    .border-blue-600 {
        border-color: #ffffff;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>