<template>
    <footer class="bg-publicdigit-footer text-white">
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Organization Info -->
                <div class="lg:col-span-1">
                    <div class="mb-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">PD</span>
                            </div>
                            <h3 class="text-xl font-bold text-purple-100">
                                PublicDigit
                            </h3>
                        </div>
                        <h4 class="text-lg font-semibold mb-3 text-purple-200">
                            Digital Innovation Platform
                        </h4>
                        <p class="text-purple-300 text-sm leading-relaxed mb-4">
                            {{ language === 'de' ? 
                                'Plattform für digitale Innovation und Technologielösungen.' :
                                'Platform for digital innovation and technology solutions.'
                            }}
                        </p>
                        
                        <!-- Social Media Links -->
                        <div class="flex space-x-4">
                            <a href="#" class="social-icon bg-purple-700/50 hover:bg-purple-600 p-2 rounded-full transition-all duration-200 transform hover:scale-110" aria-label="GitHub">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-icon bg-purple-700/50 hover:bg-purple-600 p-2 rounded-full transition-all duration-200 transform hover:scale-110" aria-label="Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-icon bg-purple-700/50 hover:bg-purple-600 p-2 rounded-full transition-all duration-200 transform hover:scale-110" aria-label="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4 text-purple-100">
                        {{ language === 'de' ? 'Schnellzugriff' : 'Quick Links' }}
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/dashboard" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'Dashboard' : 'Dashboard' }}
                            </a>
                        </li>
                        <li>
                            <a href="/products" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'Produkte' : 'Products' }}
                            </a>
                        </li>
                        <li>
                            <a href="/solutions" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'Lösungen' : 'Solutions' }}
                            </a>
                        </li>
                        <li>
                            <a href="/developers" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'Entwickler' : 'Developers' }}
                            </a>
                        </li>
                        <li>
                            <a href="/api" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'API' : 'API' }}
                            </a>
                        </li>
                        <li>
                            <a href="/blog" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                {{ language === 'de' ? 'Blog' : 'Blog' }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4 text-purple-100">
                        {{ language === 'de' ? 'Kontaktinformation' : 'Contact Information' }}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-purple-300 text-sm">
                                    {{ language === 'de' ? 'Adresse:' : 'Address:' }}
                                </p>
                                <p class="text-purple-200 text-sm">
                                    Public Digit Technology<br>
                                    Rathaus str 4 <br> 
                                    65203 Wiesbaden , Germany  </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-purple-300 text-sm">
                                    {{ language === 'de' ? 'E-Mail:' : 'Email:' }}
                                </p>
                                <a href="mailto:support@publicdigit.com" class="text-purple-200 text-sm hover:text-white transition-colors duration-200">
                                    support@publicdigit.com
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-purple-300 text-sm">
                                    {{ language === 'de' ? 'Telefon:' : 'Phone:' }}
                                </p>
                                <a href="tel:+49-15164322589" class="text-purple-200 text-sm hover:text-white transition-colors duration-200">
                                    +49 151 64322589
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Newsletter & Legal Links -->
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4 text-purple-100">
                        {{ language === 'de' ? 'Newsletter' : 'Newsletter' }}
                    </h3>
                    <p class="text-purple-300 text-sm mb-4">
                        {{ language === 'de' ? 
                            'Bleiben Sie mit den neuesten Tech-Updates und Innovationen auf dem Laufenden.' :
                            'Stay updated with the latest tech news and innovations.'
                        }}
                    </p>
                    
                    <!-- Newsletter Signup -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input 
                                type="email" 
                                v-model="newsletterEmail"
                                :placeholder="language === 'de' ? 'Ihre E-Mail' : 'Your email'"
                                class="flex-1 px-3 py-2 bg-purple-800/50 border border-purple-600 rounded-lg text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent text-sm"
                            />
                            <button 
                                @click="subscribeNewsletter"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium"
                            >
                                {{ language === 'de' ? 'Anmelden' : 'Sign Up' }}
                            </button>
                        </div>
                    </div>

                    <!-- Legal Links -->
                    <div class="space-y-2">
                        <h4 class="text-sm font-semibold text-purple-200 mb-2">
                            {{ language === 'de' ? 'Rechtliche Informationen' : 'Legal Information' }}
                        </h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="/privacy" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                    {{ language === 'de' ? 'Datenschutz' : 'Privacy Policy' }}
                                </a>
                            </li>
                            <li>
                                <a href="/terms" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                    {{ language === 'de' ? 'Nutzungsbedingungen' : 'Terms of Service' }}
                                </a>
                            </li>
                            <li>
                                <a href="/cookies" class="footer-link text-purple-300 hover:text-white transition-colors duration-200 text-sm">
                                    {{ language === 'de' ? 'Cookie-Richtlinie' : 'Cookie Policy' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-purple-700/50 bg-purple-950/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    
                    <!-- Copyright -->
                    <div class="text-purple-300 text-sm text-center md:text-left">
                        <p>
                            &copy; {{ currentYear }} PublicDigit Inc. 
                            {{ language === 'de' ? 'Alle Rechte vorbehalten.' : 'All rights reserved.' }}
                        </p>
                    </div>

                    <!-- Language Toggle -->
                    <div class="flex items-center space-x-4">
                        <span class="text-purple-300 text-sm">{{ language === 'de' ? 'Sprache:' : 'Language:' }}</span>
                        <button 
                            @click="toggleLanguage"
                            class="flex items-center space-x-2 px-3 py-1 bg-purple-700/50 border border-purple-600 rounded-lg text-purple-200 hover:text-white hover:bg-purple-600/50 transition-all duration-200 text-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                            <span>{{ language === 'de' ? 'English' : 'Deutsch' }}</span>
                        </button>
                    </div>

                    <!-- Back to Top -->
                    <button 
                        @click="scrollToTop"
                        class="flex items-center space-x-2 px-3 py-1 bg-purple-700/50 border border-purple-600 rounded-lg text-purple-200 hover:text-white hover:bg-purple-600/50 transition-all duration-200 text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span>{{ language === 'de' ? 'Nach oben' : 'Back to Top' }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-4 right-4 opacity-10 pointer-events-none">
            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
            </svg>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'PublicDigitFooter',
    
    props: {
        language: {
            type: String,
            default: 'en'
        }
    },
    
    data() {
        return {
            newsletterEmail: '',
            currentYear: new Date().getFullYear()
        };
    },
    
    methods: {
        toggleLanguage() {
            const newLanguage = this.language === 'en' ? 'de' : 'en';
            this.$emit('language-changed', newLanguage);
        },
        
        subscribeNewsletter() {
            if (this.newsletterEmail && this.isValidEmail(this.newsletterEmail)) {
                console.log('Subscribing email:', this.newsletterEmail);
                
                alert(this.language === 'de' ? 
                    'Vielen Dank! Ihre E-Mail wurde erfolgreich registriert.' :
                    'Thank you! Your email has been successfully registered.'
                );
                
                this.newsletterEmail = '';
            } else {
                alert(this.language === 'de' ? 
                    'Bitte geben Sie eine gültige E-Mail-Adresse ein.' :
                    'Please enter a valid email address.'
                );
            }
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    },
    
    mounted() {
        setInterval(() => {
            this.currentYear = new Date().getFullYear();
        }, 60000);
    }
};
</script>

<style scoped>
/* PublicDigit Footer Background */
.bg-publicdigit-footer {
    background: oklch(37.9% 0.146 265.522);
}

@supports not (color: oklch(0% 0 0)) {
  .bg-publicdigit-footer {
    background: #3d3d7a; /* Fallback color */
  }
}

/* Footer link hover effects */
.footer-link {
    position: relative;
    display: inline-block;
}

.footer-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: -2px;
    left: 0;
    background-color: currentColor;
    transition: width 0.3s ease;
}

.footer-link:hover::after {
    width: 100%;
}

/* Social icon hover effects */
.social-icon {
    transition: all 0.3s ease;
}

.social-icon:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 4px 12px rgba(168, 85, 247, 0.4);
}

/* Newsletter input focus effects */
input[type="email"]:focus {
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
}

/* Button hover effects */
button {
    transition: all 0.2s ease;
}

button:hover {
    transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid {
        gap: 2rem;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High contrast support */
@media (prefers-contrast: high) {
    .text-purple-300 {
        color: #ffffff;
    }
    
    .border-purple-600 {
        border-color: #ffffff;
    }
}
</style>