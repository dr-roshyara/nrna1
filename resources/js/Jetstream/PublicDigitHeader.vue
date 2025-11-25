<template>
    <div class="bg-publicdigit-header shadow-2xl">
        <!-- Top Bar with Contact Info and Quick Links -->
        <div class="bg-purple-950/50 border-b border-purple-700/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-2 text-sm">
                    <!-- Contact Information -->
                    <div class="hidden md:flex items-center space-x-6 text-purple-200">
                        <a href="mailto:support@publicdigit.com" class="flex items-center hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            support@publicdigit.com
                        </a>
                        <a href="tel:+49 15164322589" class="flex items-center hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +49 151 64322589
                        </a>
                    </div>

                    <!-- Quick Links and Social Media -->
                    <div class="flex items-center space-x-4">
                        <!-- Social Media Links -->
                        <div class="hidden sm:flex items-center space-x-3">
                            <a href="#" class="text-purple-200 hover:text-white transition-colors duration-200" aria-label="GitHub">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-purple-200 hover:text-white transition-colors duration-200" aria-label="Twitter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-purple-200 hover:text-white transition-colors duration-200" aria-label="LinkedIn">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Language Selector -->
                        <div class="relative">
                            <select 
                                v-model="currentLanguage"
                                @change="$emit('language-changed', currentLanguage)"
                                class="bg-purple-800 text-purple-100 border border-purple-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all duration-200"
                            >
                                <option value="en">English</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>

                        <!-- Quick Links -->
                        <div class="hidden lg:flex items-center space-x-4 text-purple-200">
                            <a href="/help" class="hover:text-white transition-colors duration-200">Help</a>
                            <span class="text-purple-600">|</span>
                            <a href="/contact" class="hover:text-white transition-colors duration-200">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="relative bg-tech-pattern">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-6">
                    <!-- Logo and Organization Info -->
                    <div class="flex  items-center space-x-6">
                        <!-- Logo -->
                        <!-- <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">PD</span>
                            </div>
                        </div> -->
                        <img src="/images/logo_publicdigit.png" alt="PublicDigit Logo" class="sm:h-20 sm:w-20 lg:h-24 w-24 rounded-lg "/>

                        <!-- Organization Details -->
                        <div class="hidden md:block">
                            <div class="flex flex-col">
                                <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">
                                Public Digit 
                                </h1>
                                <h2 class="text-lg lg:text-xl font-semibold text-purple-200 leading-tight">
                                    Digital Innovation Platform
                                </h2>
                                <p class="text-sm text-purple-300 mt-1">
                                    Building the future of digital solutions
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Controls -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Button (Desktop) -->
                        <button 
                            v-if="showSearch"
                            @click="toggleSearch"
                            class="hidden md:flex items-center px-4 py-2 bg-purple-700/50 border border-purple-600 rounded-lg text-purple-100 hover:bg-purple-600/50 hover:text-white transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>

                        <!-- User Account Controls (if logged in) -->
                        <div v-if="isLoggedIn" class="hidden md:flex items-center space-x-3">
                            <!-- Notifications -->
                            <button class="relative p-2 text-purple-200 hover:text-white hover:bg-purple-700/50 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a11 11 0 01-1.5-5.5V5a2 2 0 10-4 0v3a11 11 0 01-1.5 5.5L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span v-if="hasNotifications" class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button 
                                    @click="toggleUserMenu"
                                    class="flex items-center space-x-2 p-2 bg-purple-700/50 border border-purple-600 rounded-lg text-purple-100 hover:bg-purple-600/50 hover:text-white transition-all duration-200"
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
                                        <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                        <a href="/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700">
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
                               class="px-4 py-2 bg-transparent border border-purple-400 text-purple-100 rounded-lg hover:bg-purple-700/50 hover:border-purple-300 transition-all duration-200">
                                Login
                            </a>
                            <a v-if="canRegister" 
                               href="/register" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 hover:shadow-lg transition-all duration-200">
                                Register
                            </a>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden">
                            <button 
                                @click="toggleMobileMenu"
                                class="inline-flex items-center justify-center p-2 rounded-lg text-purple-200 hover:text-white hover:bg-purple-700/50 transition-all duration-200"
                                aria-label="Mobile menu"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Organization Title -->
                <div class="md:hidden pb-4">
                    <h1 class="text-3xl font-bold text-white text-center leading-tight">
                        Public Digit
                    </h1>
                    <p class="text-sm text-purple-100 text-center mt-1">
                        Digital Innovation Platform
                    </p>
                </div>
            </div>
        </div>

        <!-- Search Bar (when expanded) -->
        <transition name="search-expand">
            <div v-if="searchExpanded" class="bg-purple-800/50 border-t border-purple-600/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="relative max-w-lg mx-auto">
                        <input 
                            ref="searchInput"
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Search PublicDigit platform..."
                            class="w-full pl-10 pr-4 py-2 bg-white/10 border border-purple-400 rounded-lg text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent"
                            @keyup.enter="performSearch"
                        />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Mobile Menu -->
        <transition name="mobile-menu">
            <div v-if="showMobileMenu" class="md:hidden bg-purple-800/95 border-t border-purple-600/30">
                <div class="px-4 py-4 space-y-4">
                    <!-- Mobile Search -->
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search..."
                            class="w-full pl-10 pr-4 py-2 bg-white/10 border border-purple-400 rounded-lg text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400"
                        />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Mobile User Actions -->
                    <div v-if="!isLoggedIn" class="space-y-2">
                        <a v-if="canLogin" href="/login" class="block w-full px-4 py-2 bg-transparent border border-purple-400 text-purple-100 rounded-lg text-center hover:bg-purple-700/50 transition-all duration-200">
                            Login
                        </a>
                        <a v-if="canRegister" href="/register" class="block w-full px-4 py-2 bg-purple-600 text-white rounded-lg text-center hover:bg-purple-700 transition-all duration-200">
                            Register
                        </a>
                    </div>

                    <!-- Mobile Contact Info -->
                    <div class="pt-4 border-t border-purple-600/30 space-y-2 text-sm text-purple-200">
                        <a href="mailto:support@publicdigit.com" class="flex items-center hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            support@publicdigit.com
                        </a>
                        <a href="tel:+49-15164322589" class="flex items-center hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +49 151 64322589
                        </a>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: 'PublicDigitHeader',
    
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
            isLoggedIn: false,
            userName: 'Developer',
            userAvatar: null,
            hasNotifications: true
        };
    },
    
    mounted() {
        this.checkAuthStatus();
        document.addEventListener('click', this.handleClickOutside);
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
                console.log('Searching for:', this.searchQuery);
                window.location.href = `/search?q=${encodeURIComponent(this.searchQuery)}`;
            }
        },
        
        checkAuthStatus() {
            const token = localStorage.getItem('auth_token');
            this.isLoggedIn = !!token;
            
            if (this.isLoggedIn) {
                this.userName = localStorage.getItem('user_name') || 'Developer';
                this.userAvatar = localStorage.getItem('user_avatar');
            }
        },
        
        logout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_name');
            localStorage.removeItem('user_avatar');
            this.isLoggedIn = false;
            this.showUserMenu = false;
            window.location.href = '/login';
        },
        
        handleClickOutside(event) {
            if (!event.target.closest('.relative')) {
                this.showUserMenu = false;
            }
            
            if (!event.target.closest('.md\\:hidden') && !event.target.closest('[aria-label="Mobile menu"]')) {
                this.showMobileMenu = false;
            }
        },
        
        handleKeydown(event) {
            if (event.key === 'Escape') {
                this.showMobileMenu = false;
                this.showUserMenu = false;
                this.searchExpanded = false;
            }
            
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
/* PublicDigit Header Background */
.bg-publicdigit-header {
    background: oklch(37.9% 0.146 265.522);
}

@supports not (color: oklch(0% 0 0)) {
  .bg-publicdigit-header {
    background: #3d3d7a; /* Fallback color */
  }
}

/* Tech-inspired background pattern */
.bg-tech-pattern {
    background-image: 
        linear-gradient(45deg, transparent 49%, rgba(255, 255, 255, 0.03) 50%, transparent 51%),
        linear-gradient(-45deg, transparent 49%, rgba(255, 255, 255, 0.03) 50%, transparent 51%);
    background-size: 20px 20px;
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
    outline: 2px solid #a855f7;
    outline-offset: 2px;
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
    .text-purple-200 {
        color: #ffffff;
    }
    
    .border-purple-600 {
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