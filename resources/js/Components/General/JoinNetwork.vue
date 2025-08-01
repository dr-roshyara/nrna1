<template>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-rose-500 to-pink-600 rounded-full shadow-lg mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            
            <a href="/register" class="group inline-block">
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-4 group-hover:from-rose-700 group-hover:to-pink-700 transition duration-300">
                    JOIN NRNA NETWORK
                </h1>
            </a>
            
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Connect with the global Nepalese community and be part of a network that's making a difference worldwide
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-10">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                        Ready to Connect with the Global Nepali Diaspora?
                    </h2>
                    <p class="text-gray-600 text-lg leading-relaxed max-w-2xl mx-auto">
                        Join thousands of Nepalese around the world. Share experiences, build connections, and contribute to our global community's growth and development.
                    </p>
                </div>
            </div>

            <!-- Action Buttons Section -->
            <div class="px-8 py-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <!-- Registration Button -->
                    <a href="/register" class="group">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span class="text-xl font-semibold">New Registration</span>
                            </div>
                            <p class="text-blue-100 text-center text-sm">
                                Create your account and join our community
                            </p>
                        </div>
                    </a>

                    <!-- Direct Login Button -->
                    <a href="/login" class="group">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-xl font-semibold">Direct Login</span>
                            </div>
                            <p class="text-green-100 text-center text-sm">
                                Already a member? Sign in here
                            </p>
                        </div>
                    </a>

                    <!-- Google Login Button -->
                    <a href="/login/google" class="group">
                        <div class="bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-gray-300 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="text-xl font-semibold text-gray-700">Google Login</span>
                            </div>
                            <p class="text-gray-500 text-center text-sm">
                                Quick access with your Google account
                            </p>
                        </div>
                    </a>

                    <!-- Facebook Login Button -->
                    <a href="/login/facebook" class="group">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300" style="background: linear-gradient(135deg, #4267B2 0%, #365899 100%);">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-xl font-semibold">Facebook Login</span>
                            </div>
                            <p class="text-blue-100 text-center text-sm">
                                Connect with your Facebook account
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-10">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">
                        Why Join Our Network?
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <!-- Benefit 1 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Global Communication</h4>
                        <p class="text-gray-600 text-sm">Connect and communicate with Nepalese communities worldwide</p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Community Building</h4>
                        <p class="text-gray-600 text-sm">Be part of initiatives that strengthen our global Nepali diaspora</p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-rose-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Cultural Preservation</h4>
                        <p class="text-gray-600 text-sm">Help preserve and promote Nepali culture and traditions globally</p>
                    </div>
                </div>

                <!-- Final CTA Text -->
                <div class="text-center mt-10 max-w-3xl mx-auto">
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Join our vibrant community of Nepalese living around the globe. Share experiences, build meaningful connections, and contribute to initiatives that benefit Nepal and Nepalese communities worldwide.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'JoinNrnaNetwork'
};
</script>