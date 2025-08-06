<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <header class="text-center mb-12" role="banner">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-lg mb-8">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    NRNA निर्वाचन प्रणाली
                </h1>
                
                <div class="space-y-4 max-w-4xl mx-auto">
                    <p class="text-xl md:text-2xl font-semibold text-blue-800">
                        Election System for Nepali by Nepali
                    </p>
                    
                    <p class="text-lg md:text-xl text-bold text-red-800 font-medium">
                        Für Nepalesen, von Nepalesen – digital, sicher, und kostenlos!<br>
                        <span class="text-sm text-red-600">Made in Germany</span>
                    </p>
                    
                    <p class="text-lg text-green-700 font-medium">
                        Benutzerfreundlich und transparent
                    </p>
                </div>
            </header>

            <!-- Main Content Card -->
            <main class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" role="main">
                

                <!-- Login Options Section -->
                <section class="px-6 py-12" aria-labelledby="login-section">
                    <h2 id="login-section" class="text-3xl font-bold text-center text-gray-900 mb-10">
                        मतदान सुरु गर्नुहोस् | Start Voting
                    </h2>
                    
                    <div class="grid grid-cols-1  gap-8 w-full m-auto">
                        <!-- Direct Login -->
                        <a 
                            href="/login" 
                            class="group focus:outline-none focus:ring-4 focus:ring-blue-300 rounded-3xl"
                            aria-label="प्रत्यक्ष लगइन - Direct login to election system"
                        >
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-3xl p-8 shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-6">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold mb-3">प्रत्यक्ष लगइन</h3>
                                    <p class="text-xl font-medium mb-2">Direct Login</p>
                                    <p class="text-blue-100 text-sm">
                                        निर्वाचन प्रणालीमा प्रवेश गर्नुहोस्
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>
                <!-- Description Section -->
                <section class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-10">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                            हाम्रो डिजिटल निर्वाचन प्लेटफर्म
                        </h2>
                        
                        <div class="bg-white/70 rounded-2xl p-6 text-left">
                            <p class="text-lg leading-relaxed text-gray-800">
                                हाम्रो अनलाइन निर्वाचन प्रणाली नेपाली समुदायका लागि नेपाली स्वयंले निर्माण गरेको सुरक्षित, भरपर्दो र निःशुल्क सेवा हो। यो प्रणाली नेपाली र अंग्रेजी दुबै भाषामा सहज रूपमा उपलब्ध छ, जसले सबै सदस्यहरूलाई सजिलो र पारदर्शी रूपमा मतदान गर्न सहयोग गर्छ।
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-12" aria-labelledby="features-section">
                    <h2 id="features-section" class="text-2xl font-semibold text-center text-gray-800 mb-10">
                        प्रणालीका विशेषताहरू | System Features
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <!-- Security Feature -->
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4" aria-hidden="true">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">सुरक्षित</h3>
                            <p class="text-sm text-gray-600">Secure & Safe</p>
                        </div>

                        <!-- Free Feature -->
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4" aria-hidden="true">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">निःशुल्क</h3>
                            <p class="text-sm text-gray-600">Free of Cost</p>
                        </div>

                        <!-- Transparent Feature -->
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4" aria-hidden="true">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">पारदर्शी</h3>
                            <p class="text-sm text-gray-600">Transparent</p>
                        </div>
                    </div>
                </section>

                <!-- Support Section -->
                <section class="bg-white px-6 py-10">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-6" aria-hidden="true">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">
                            सहायता चाहिएको छ? | Need Help?
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            मतदान प्रक्रियामा कुनै समस्या भएमा सहायताका लागि सम्पर्क गर्नुहोस्।<br>
                            Contact support if you need assistance with the voting process.
                        </p>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    name: 'NrnaElectionJoinNetwork',
    
    mounted() {
        // Accessibility enhancements
        this.setupAccessibility();
    },
    
    methods: {
        setupAccessibility() {
            // Announce page load for screen readers
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = 'NRNA निर्वाचन प्रणाली लोड भयो। NRNA Election System loaded.';
            document.body.appendChild(announcement);
            
            // Remove announcement after screen reader has processed it
            setTimeout(() => {
                if (document.body.contains(announcement)) {
                    document.body.removeChild(announcement);
                }
            }, 1000);
            
            // Add keyboard navigation support
            document.addEventListener('keydown', (e) => {
                if (e.altKey && e.key === 'l') {
                    // Alt+L for direct login
                    window.location.href = '/login';
                }
                if (e.altKey && e.key === 'g') {
                    // Alt+G for Google login
                    window.location.href = '/login/google';
                }
            });
        }
    }
};
</script>

<style scoped>
/* Screen reader only utility class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gradient-to-r,
    .bg-gradient-to-br {
        background: #ffffff !important;
    }
    
    .text-blue-600,
    .text-indigo-600 {
        color: #000000 !important;
    }
    
    .border-gray-200 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .transform,
    .transition-all,
    .transition-colors {
        transform: none !important;
        transition: none !important;
    }
    
    .hover\:-translate-y-2:hover {
        transform: none !important;
    }
}

/* Large text support */
@media (min-resolution: 192dpi) {
    .text-sm {
        font-size: 1rem !important;
    }
    
    .text-base {
        font-size: 1.125rem !important;
    }
}

/* Focus improvements for better accessibility */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3);
}

/* Print styles */
@media print {
    .bg-gradient-to-r,
    .bg-gradient-to-br,
    .shadow-xl,
    .shadow-lg {
        background: white !important;
        box-shadow: none !important;
        border: 2px solid #000 !important;
    }
    
    .text-white {
        color: #000 !important;
    }
}
</style>