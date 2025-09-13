<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Create New Election</h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Set up election timeline, phases, and configuration
                            </p>
                        </div>
                        <Link
                            :href="route('admin.elections.index')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Elections
                        </Link>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="mb-8">
                    <nav aria-label="Progress">
                        <ol class="flex items-center">
                            <li 
                                v-for="(step, index) in steps" 
                                :key="step.id"
                                :class="[
                                    index !== steps.length - 1 ? 'pr-8 sm:pr-20' : '',
                                    'relative'
                                ]"
                            >
                                <div v-if="index !== steps.length - 1" class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="h-0.5 w-full bg-gray-200"></div>
                                </div>
                                <div 
                                    :class="[
                                        currentStep >= index + 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-300 text-gray-500',
                                        'relative flex h-10 w-10 items-center justify-center rounded-full border-2'
                                    ]"
                                >
                                    <span class="text-sm font-medium">{{ index + 1 }}</span>
                                </div>
                                <p class="mt-2 text-sm font-medium text-gray-900">{{ step.name }}</p>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Step 1: Basic Information -->
                    <div v-show="currentStep === 1" class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Election Name</label>
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., NRNA Europe Election 2024"
                                    required
                                />
                                <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Brief description of the election..."
                                    required
                                ></textarea>
                                <div v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</div>
                            </div>

                            <div>
                                <label for="constituency" class="block text-sm font-medium text-gray-700">Constituency</label>
                                <select
                                    id="constituency"
                                    name="constituency"
                                    v-model="form.constituency"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                    <option value="">Select Constituency</option>
                                    <option v-for="(label, value) in availableConstituencies" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.constituency" class="mt-2 text-sm text-red-600">{{ form.errors.constituency }}</div>
                                <!-- Debug info -->
                                <div v-if="Object.keys(availableConstituencies).length === 0" class="mt-2 text-sm text-orange-600">
                                    Debug: No constituencies loaded. Check backend data.
                                </div>
                            </div>

                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                <select
                                    id="timezone"
                                    name="timezone"
                                    v-model="form.timezone"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                    <option v-for="(label, value) in timezones" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.timezone" class="mt-2 text-sm text-red-600">{{ form.errors.timezone }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Registration Phase -->
                    <div v-show="currentStep === 2" class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            👥 Registration Phase
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="registration_start" class="block text-sm font-medium text-gray-700">Registration Start</label>
                                <input
                                    id="registration_start"
                                    name="registration_start"
                                    v-model="form.registration_start"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.registration_start" class="mt-2 text-sm text-red-600">{{ form.errors.registration_start }}</div>
                            </div>

                            <div>
                                <label for="registration_end" class="block text-sm font-medium text-gray-700">Registration End</label>
                                <input
                                    id="registration_end"
                                    name="registration_end"
                                    v-model="form.registration_end"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.registration_end" class="mt-2 text-sm text-red-600">{{ form.errors.registration_end }}</div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-900">Registration Phase Details</h3>
                            <ul class="mt-2 text-sm text-blue-800 space-y-1">
                                <li>• Voters can register for this specific constituency</li>
                                <li>• Election committee reviews and approves voter applications</li>
                                <li>• Approved voters receive unique voting links for this election</li>
                                <li>• Multiple elections can run simultaneously for different constituencies</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 3: Nomination Phase -->
                    <div v-show="currentStep === 3" class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            🏆 Candidate Nomination Phase
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="candidate_nomination_start" class="block text-sm font-medium text-gray-700">Nomination Start</label>
                                <input
                                    id="candidate_nomination_start"
                                    name="candidate_nomination_start"
                                    v-model="form.candidate_nomination_start"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.candidate_nomination_start" class="mt-2 text-sm text-red-600">{{ form.errors.candidate_nomination_start }}</div>
                            </div>

                            <div>
                                <label for="candidate_nomination_end" class="block text-sm font-medium text-gray-700">Nomination End</label>
                                <input
                                    id="candidate_nomination_end"
                                    name="candidate_nomination_end"
                                    v-model="form.candidate_nomination_end"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.candidate_nomination_end" class="mt-2 text-sm text-red-600">{{ form.errors.candidate_nomination_end }}</div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                            <h3 class="text-sm font-medium text-purple-900">Nomination Phase Details</h3>
                            <ul class="mt-2 text-sm text-purple-800 space-y-1">
                                <li>• Eligible candidates can submit nominations for positions</li>
                                <li>• Committee reviews candidate eligibility and qualifications</li>
                                <li>• Final candidate list is prepared for voting</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 4: Voting Phase -->
                    <div v-show="currentStep === 4" class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            🗳️ Voting Phase
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="voting_start_time" class="block text-sm font-medium text-gray-700">Voting Start</label>
                                <input
                                    id="voting_start_time"
                                    name="voting_start_time"
                                    v-model="form.voting_start_time"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.voting_start_time" class="mt-2 text-sm text-red-600">{{ form.errors.voting_start_time }}</div>
                            </div>

                            <div>
                                <label for="voting_end_time" class="block text-sm font-medium text-gray-700">Voting End</label>
                                <input
                                    id="voting_end_time"
                                    name="voting_end_time"
                                    v-model="form.voting_end_time"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.voting_end_time" class="mt-2 text-sm text-red-600">{{ form.errors.voting_end_time }}</div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="text-sm font-medium text-green-900">Voting Phase Details</h3>
                            <ul class="mt-2 text-sm text-green-800 space-y-1">
                                <li>• 6-step secure voting process: Code1 → Agreement → Vote → Code2</li>
                                <li>• 20-minute voting window per session</li>
                                <li>• Unique voting links for each voter and election</li>
                                <li>• Real-time monitoring and security tracking</li>
                                <li>• Isolated voting sessions for each constituency</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 5: Post-Election -->
                    <div v-show="currentStep === 5" class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            📊 Post-Election Phase
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="authorization_deadline" class="block text-sm font-medium text-gray-700">Publisher Authorization Deadline</label>
                                <input
                                    id="authorization_deadline"
                                    name="authorization_deadline"
                                    v-model="form.authorization_deadline"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.authorization_deadline" class="mt-2 text-sm text-red-600">{{ form.errors.authorization_deadline }}</div>
                            </div>

                            <div>
                                <label for="result_publication_date" class="block text-sm font-medium text-gray-700">Result Publication Date</label>
                                <input
                                    id="result_publication_date"
                                    name="result_publication_date"
                                    v-model="form.result_publication_date"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.result_publication_date" class="mt-2 text-sm text-red-600">{{ form.errors.result_publication_date }}</div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-orange-50 rounded-lg">
                            <h3 class="text-sm font-medium text-orange-900">Post-Election Phase Details</h3>
                            <ul class="mt-2 text-sm text-orange-800 space-y-1">
                                <li>• 3 publishers must authorize result publication per election</li>
                                <li>• Election-specific authorization sessions</li>
                                <li>• Results compiled and verified automatically</li>
                                <li>• Independent result publication for each constituency</li>
                            </ul>
                        </div>

                        <!-- Settings -->
                        <div class="mt-8 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Election Settings</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input
                                        id="auto_phase_transition"
                                        name="auto_phase_transition"
                                        v-model="form.auto_phase_transition"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="auto_phase_transition" class="ml-2 block text-sm text-gray-900">
                                        Enable automatic phase transitions
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        id="notification_enabled"
                                        name="notification_enabled"
                                        v-model="form.notification_enabled"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="notification_enabled" class="ml-2 block text-sm text-gray-900">
                                        Send email notifications to voters
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        id="public_registration"
                                        name="public_registration"
                                        v-model="form.public_registration"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="public_registration" class="ml-2 block text-sm text-gray-900">
                                        Allow public voter registration
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between">
                        <button
                            v-if="currentStep > 1"
                            type="button"
                            @click="previousStep"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Previous
                        </button>
                        <div v-else></div>

                        <button
                            v-if="currentStep < 5"
                            type="button"
                            @click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>

                        <button
                            v-else
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                        >
                            <span v-if="form.processing">Creating...</span>
                            <span v-else>Create Election</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: 'ElectionsCreate',
    
    components: {
        AppLayout,
        Link,
    },
    
    props: {
        timezones: {
            type: Object,
            required: true,
        },
        constituencies: {
            type: Object,
            default: () => ({
                'general': 'General Election',
                'europe': 'NRNA Europe',
                'americas': 'NRNA Americas',
                'asia_pacific': 'NRNA Asia Pacific',
            }),
        },
        defaultTimeline: {
            type: Object,
            required: true,
        },
    },
    
    setup(props) {
        const form = useForm({
            name: '',
            description: '',
            constituency: 'general',
            timezone: 'UTC',
            registration_start: props.defaultTimeline.registration_start,
            registration_end: props.defaultTimeline.registration_end,
            candidate_nomination_start: props.defaultTimeline.candidate_nomination_start,
            candidate_nomination_end: props.defaultTimeline.candidate_nomination_end,
            voting_start_time: props.defaultTimeline.voting_start_time,
            voting_end_time: props.defaultTimeline.voting_end_time,
            authorization_deadline: props.defaultTimeline.authorization_deadline,
            result_publication_date: props.defaultTimeline.result_publication_date,
            auto_phase_transition: true,
            notification_enabled: true,
            public_registration: true,
        });
        
        return { form };
    },
    
    data() {
        return {
            currentStep: 1,
            steps: [
                { id: 1, name: 'Basic Info' },
                { id: 2, name: 'Registration' },
                { id: 3, name: 'Nomination' },
                { id: 4, name: 'Voting' },
                { id: 5, name: 'Post-Election' },
            ],
        };
    },
    
    computed: {
        availableConstituencies() {
            // Fallback constituencies if backend doesn't provide them
            if (!this.constituencies || Object.keys(this.constituencies).length === 0) {
                return {
                    'general': 'General Election',
                    'europe': 'NRNA Europe',
                    'americas': 'NRNA Americas',
                    'asia_pacific': 'NRNA Asia Pacific',
                    'middle_east': 'NRNA Middle East',
                    'africa': 'NRNA Africa',
                    'oceania': 'NRNA Oceania',
                    'youth': 'NRNA Youth Committee',
                    'women': 'NRNA Women Committee',
                };
            }
            return this.constituencies;
        },
    },
    
    mounted() {
        // Debug: Check if props are received correctly
        console.log('Create Election - Props received:', {
            constituencies: this.constituencies,
            timezones: this.timezones,
            defaultTimeline: this.defaultTimeline
        });
    },
    
    methods: {
        nextStep() {
            if (this.currentStep < 5) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        submit() {
            this.form.post(route('admin.elections.store'));
        },
    },
};
</script>