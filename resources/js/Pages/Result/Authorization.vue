<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        <span v-if="phase === 'sealed'">
                            🔒 कन्टेनर सील गर्नुहोस् | Seal Container
                        </span>
                        <span v-else>
                            🔓 कन्टेनर खोल्नुहोस् | Unseal Container
                        </span>
                    </h1>
                    <p class="text-lg text-gray-600">
                        <span v-if="phase === 'sealed'">
                            निर्वाचन सुरु गर्नु अघि रिजल्ट कन्टेनर सील गर्नुहोस्
                        </span>
                        <span v-else>
                            निर्वाचन सकिएपछि रिजल्ट कन्टेनर खोल्नुहोस्
                        </span>
                    </p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mt-4"></div>
                </header>

                <!-- Already Completed Notice -->
                <div v-if="publisher.agreed" class="mb-8">
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-800">
                                    <span v-if="phase === 'sealed'">
                                        ✅ कन्टेनर सील गरिएको छ | Container Sealed
                                    </span>
                                    <span v-else>
                                        ✅ कन्टेनर खोलिएको छ | Container Unsealed
                                    </span>
                                </h3>
                                <p class="text-green-700 mt-1">
                                    <span v-if="phase === 'sealed'">
                                        तपाईंले {{ formatDate(publisher.agreed_at) }} मा कन्टेनर सील गर्नुभयो
                                    </span>
                                    <span v-else>
                                        तपाईंले {{ formatDate(publisher.agreed_at) }} मा कन्टेनर खोल्नुभयो
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Authorization Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <!-- Progress Section -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        <span v-if="phase === 'sealed'">
                                            सीलिंग प्रगति | Sealing Progress
                                        </span>
                                        <span v-else>
                                            अनसीलिंग प्रगति | Unsealing Progress
                                        </span>
                                    </h3>
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ progress.agreed }}/{{ progress.required }} ({{ progress.percentage }}%)
                                    </span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                    <div 
                                        class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500 ease-out"
                                        :style="{ width: progress.percentage + '%' }"
                                    ></div>
                                </div>
                                
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ progress.remaining }} बाँकी | remaining</span>
                                    <span v-if="election.authorization_deadline">
                                        समय सीमा | Deadline: {{ formatDate(election.authorization_deadline) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Authorization Form -->
                            <div v-if="!publisher.agreed && !progress.complete">
                                <form @submit.prevent="submitAuthorization">
                                    <div class="space-y-6">
                                        <!-- Election Info -->
                                        <div class="bg-blue-50 rounded-xl p-6">
                                            <h4 class="text-lg font-medium text-blue-900 mb-3">
                                                निर्वाचन जानकारी | Election Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-blue-800">निर्वाचन:</span>
                                                    <span class="text-blue-700">{{ election.name }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-blue-800">स्थिति:</span>
                                                    <span class="text-blue-700">
                                                        <span v-if="phase === 'sealed'">सील गर्न तयार</span>
                                                        <span v-else>खोल्न तयार</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Password Input -->
                                        <div>
                                            <label for="authorization_password" class="block text-sm font-medium text-gray-700 mb-2">
                                                प्राधिकरण पासवर्ड | Authorization Password
                                            </label>
                                            <input
                                                type="password"
                                                id="authorization_password"
                                                v-model="form.authorization_password"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                :class="{ 'border-red-300 ring-1 ring-red-500': form.errors.authorization_password }"
                                                placeholder="तपाईंको प्राधिकरण पासवर्ड प्रविष्ट गर्नुहोस्"
                                                required
                                            />
                                            <div v-if="form.errors.authorization_password" class="mt-2 text-sm text-red-600">
                                                {{ form.errors.authorization_password }}
                                            </div>
                                        </div>

                                        <!-- Agreement Checkbox -->
                                        <div>
                                            <label class="flex items-start">
                                                <input
                                                    type="checkbox"
                                                    v-model="form.agree"
                                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                    :class="{ 'border-red-300 ring-1 ring-red-500': form.errors.agree }"
                                                    required
                                                />
                                                <span class="ml-3 text-sm text-gray-700">
                                                    <span v-if="phase === 'sealed'">
                                                        म यो खाली परिणाम कन्टेनर सील गर्न सहमत छु। 
                                                        <br><em>I agree to seal this empty result container.</em>
                                                    </span>
                                                    <span v-else>
                                                        म निर्वाचन परिणाम प्रकाशन गर्न सहमत छु।
                                                        <br><em>I agree to publish the election results.</em>
                                                    </span>
                                                </span>
                                            </label>
                                            <div v-if="form.errors.agree" class="mt-2 text-sm text-red-600">
                                                {{ form.errors.agree }}
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="w-full flex justify-center items-center py-3 px-6 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                        >
                                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span v-if="!form.processing">
                                                <span v-if="phase === 'sealed'">
                                                    🔒 कन्टेनर सील गर्नुहोस् | Seal Container
                                                </span>
                                                <span v-else>
                                                    🔓 कन्टेनर खोल्नुहोस् | Unseal Container
                                                </span>
                                            </span>
                                            <span v-else>
                                                प्रक्रिया गर्दै... | Processing...
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Completion Message -->
                            <div v-else-if="progress.complete" class="text-center py-8">
                                <div class="text-6xl mb-4">🎉</div>
                                <h3 class="text-2xl font-bold text-green-600 mb-2">
                                    <span v-if="phase === 'sealed'">
                                        सबै प्रकाशकहरूले सील गरे!
                                    </span>
                                    <span v-else>
                                        परिणाम प्रकाशित भयो!
                                    </span>
                                </h3>
                                <p class="text-gray-600">
                                    <span v-if="phase === 'sealed'">
                                        निर्वाचन सुरु गर्न तयार छ। | Ready to start election.
                                    </span>
                                    <span v-else>
                                        परिणाम अब सबैलाई उपलब्ध छ। | Results are now available to everyone.
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                प्रकाशक स्थिति | Publisher Status
                            </h3>

                            <!-- Agreed Publishers -->
                            <div v-if="progress.agreed > 0" class="mb-6">
                                <h4 class="text-sm font-medium text-green-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span v-if="phase === 'sealed'">सील गरिएको | Sealed</span>
                                    <span v-else>खोलिएको | Unsealed</span>
                                </h4>
                                <div class="space-y-2">
                                    <div v-for="(pub, index) in agreedPublishers" :key="index" class="flex items-center p-2 bg-green-50 rounded-lg">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-green-900 truncate">{{ pub.name }}</p>
                                            <p class="text-xs text-green-700">{{ pub.title }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Publishers -->
                            <div v-if="progress.remaining > 0">
                                <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    बाँकी | Pending
                                </h4>
                                <div class="space-y-2">
                                    <div v-for="(pub, index) in pendingPublishers" :key="index" class="flex items-center p-2 bg-gray-50 rounded-lg">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-3"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ pub.name }}</p>
                                            <p class="text-xs text-gray-600">{{ pub.title }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Remaining -->
                            <div v-if="election.authorization_deadline && !progress.complete" class="mt-6 p-4 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">समय सीमा</p>
                                        <p class="text-xs text-yellow-700">{{ formatDate(election.authorization_deadline) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from "@/Layouts/ElectionLayout";
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    components: {
        ElectionLayout,
    },
    
    props: {
        phase: {
            type: String,
            required: true // 'sealed' or 'unsealing'
        },
        publisher: {
            type: Object,
            required: true
        },
        election: {
            type: Object,
            required: true
        },
        progress: {
            type: Object,
            required: true
        },
        agreedPublishers: {
            type: Array,
            default: () => []
        },
        pendingPublishers: {
            type: Array,
            default: () => []
        }
    },
    
    setup() {
        const form = useForm({
            authorization_password: '',
            agree: false,
        });
        
        return { form };
    },
    
    methods: {
        submitAuthorization() {
            this.form.post(route('publisher.authorize.submit'), {
                preserveScroll: true,
                onSuccess: () => {
                    // Reset form on success
                    this.form.reset();
                },
                onError: (errors) => {
                    // Handle validation errors
                    console.log('Validation errors:', errors);
                }
            });
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (error) {
                return 'Invalid Date';
            }
        }
    },
    
    mounted() {
        // Auto-refresh progress every 30 seconds
        this.progressInterval = setInterval(() => {
            if (!this.progress.complete) {
                // You can add an API call here to refresh progress
                // axios.get('/api/authorization-progress').then(response => {
                //     // Update progress data
                // });
            }
        }, 30000);
    },
    
    unmounted() {
        if (this.progressInterval) {
            clearInterval(this.progressInterval);
        }
    }
};
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Custom progress bar animation */
@keyframes progressFill {
    0% { width: 0%; }
    100% { width: var(--progress-width); }
}

/* Loading spinner animation */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .text-4xl {
        font-size: 2rem;
    }
    
    .text-2xl {
        font-size: 1.25rem;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .border-gray-300 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br {
        background: white !important;
    }
}
</style>