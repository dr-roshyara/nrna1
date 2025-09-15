<template>
    <nrna-layout>
        <app-layout>
            <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
                <div class="max-w-4xl mx-auto px-4">
                    
                    <!-- Success Header -->
                    <div class="text-center mb-8">
                        <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white py-6 px-8 rounded-xl shadow-lg">
                            <div class="text-4xl mb-3">✅</div>
                            <h1 class="text-2xl font-bold mb-2">Verification Successful!</h1>
                            <h2 class="text-xl mb-2">मतदान  गर्नको लागि सवै प्रक्रिया पुग्यो। </h2>
                            <p class="text-lg opacity-90">Welcome {{ user_name }}, you can now proceed to vote.</p>
                            <p class="text-sm opacity-80">{{ user_name }}, यहाँले अब  मतदान गर्न सक्नुहुन्छ।</p>
                        </div>
                    </div>

                    <!-- Agreement Form -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6">
                            <h2 class="text-xl font-bold text-center">Voting Agreement | मतदान सम्झौता</h2>
                        </div>
                        
                        <form @submit.prevent="submitAgreement" class="p-8">
                            
                            <!-- Voting Time Info -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Important:</strong> You have {{ voting_time_minutes }} minutes to complete your voting once you start.
                                        </p>
                                        <p class="text-sm text-yellow-700">
                                            <strong>महत्वपूर्ण:</strong> मतदान सुरु गरेपछि तपाईंसँग {{ voting_time_minutes }} मिनेट समय छ।
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Agreement Text -->
                            <div class="space-y-4 mb-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Terms and Conditions | नियम र सर्तहरू</h3>
                                    
                                    <div class="space-y-3 text-gray-700">
                                        <p class="leading-relaxed">
                                            {{ agreement_text_english }}
                                        </p>
                                        <p class="leading-relaxed text-sm">
                                            {{ agreement_text_nepali }}
                                        </p>
                                        
                                        <div class="mt-4 p-4 border-l-4 border-blue-400 bg-blue-50">
                                            <ul class="text-sm space-y-1">
                                                <li>• I understand that my vote is secret and will be recorded securely</li>
                                                <li>• I will complete the voting process within the time limit</li>
                                                <li>• I will not share my voting codes with anyone</li>
                                                <li>• I agree to the electronic voting process and its rules</li>
                                            </ul>
                                            <ul class="text-sm space-y-1 mt-2 text-blue-700">
                                                 <li>• मेरो मत गोप्य छ र सुरक्षित रूपमा रेकर्ड हुनेछ भन्ने कुरा म ढुक्क छु। </li>
                                                <li>• म यो कुरामा  सहमत छु कि यो मतदान प्रणाली स्वयंसेवी रूपमा तयार पारिएको हो र सेवा प्रदायकलाई कुनै शुल्क तिरेको छैन।</li>
                                                                                              <li>• म समय सीमा भित्र मतदान प्रक्रिया पूरा गर्नेछु।</li>
                                                <li>• मैले  मेरो मतदान कोड अरूलाइ दिने छैन।</li>
                                                <li>• म इलेक्ट्रोनिक मतदान प्रक्रिया र यसका नियमहरूमा सहमत छु।</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Agreement Checkbox -->
                            <div class="mb-6">
                                <div class="border-2 border-blue-300 rounded-lg p-6 bg-blue-50">
                                    <div class="flex flex-col items-center justify-center mb-4">
                                        <div class="text-3xl mb-2">✅</div>
                                        <h3 class="text-xl font-bold text-red-700 mb-1">Agreement Required</h3>
                                        <p class="text-lg font-semibold text-red-700">सम्झौता आवश्यक</p>
                                    </div>
                                    
                                    <div class="flex justify-center">
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox"
                                                v-model="form.agreement"
                                                class="w-5 h-5 text-blue-600 border-2 border-gray-400 rounded focus:ring-blue-500 focus:ring-2"
                                            />
                                            <span class="ml-3 text-lg font-medium text-gray-900">
                                                <span class="font-semibold">I agree to all terms and conditions stated above</span>
                                                <br>
                                                <span class="text-sm text-gray-600">माथि उल्लेखित सबै नियम र सर्तहरूमा म सहमत छु</span>
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <!-- Error Display -->
                                    <div v-if="$page.props.errors.agreement" class="mt-4 text-red-600 text-sm bg-red-50 p-2 rounded">
                                        {{ $page.props.errors.agreement }}
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button Only -->
                            <div class="text-center">
                                <button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-xl py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                                    :disabled="!form.agreement"
                                    :class="{ 'opacity-50 cursor-not-allowed': !form.agreement }"
                                >
                                    <span class="mr-2">🗳️</span>
                                    Start Voting | मतदान सुरु गर्नुहोस्
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    components: {
        AppLayout,
        NrnaLayout,
    },
    
    props: {
        user_name: String,
        voting_time_minutes: Number,
        agreement_text_nepali: String,
        agreement_text_english: String,
        slug: String, // Add slug prop for slug-based routing
        useSlugPath: Boolean, // Configuration to enable/disable slug paths
    },
    
    setup(props) {
        const form = useForm({
            agreement: false,
        })

        function submitAgreement() {
            // Always use slug-based route since we're using slug-based voting exclusively
            if (!props.slug) {
                console.error('No slug provided for agreement submission');
                return;
            }

            const submitUrl = route('slug.code.agreement.submit', { vslug: props.slug });
            console.log('Submitting agreement to URL:', submitUrl, 'slug:', props.slug);
            form.post(submitUrl);
        }

        return {
            form,
            submitAgreement
        }
    }
}
</script>