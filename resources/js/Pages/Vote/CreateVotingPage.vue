<template>
    <nrna-layout>
        <app-layout>
            <div class="mt-6 text-center"> 
                <div class="m-auto text-center bg-blue-200 py-4">  
                    <p class="m-auto text-blue-700 font-bold text-sm">Congratulation {{ user_name }}!</p> 
                    <p>You have given the correct voting code. You can Vote now!</p>
                    <p class="m-auto">Please select the correct candidates of your choice</p>
                    <p>यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ।</p>
                </div>  
            </div>

            <!-- Display validation errors -->
            <jet-validation-errors class="mb-4 mx-auto text-center" />
            
            <!-- Progress indicator -->
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Voting Progress</span>
                        <span>{{ votingProgress.completed }}/{{ votingProgress.total }} completed</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                            :style="{ width: votingProgress.percentage + '%' }"
                        ></div>
                    </div>
                </div>
            </div>
            
            <form @submit.prevent="submit" class="text-center mx-auto mt-10">
                
                <!-- National Posts -->
                <div v-if="national_posts && national_posts.length > 0">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">National Posts</h2>
                    <div v-for="(post, postIndex) in national_posts" :key="`national-${post.post_id}`"
                         :class="[postIndex % 2 === 0 ? 'first_vote_window' : 'second_vote_window', 'flex flex-col']">
                        
                        <create-votingform 
                            :candidates="post.candidates"
                            :post="post"
                            @add_selected_candidates="handleCandidateSelection('national', postIndex, $event)"
                        />
                    </div>
                </div>

                <!-- Regional Posts -->
                <div v-if="regional_posts && regional_posts.length > 0">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Regional Posts ({{ user_region }})</h2>
                    <div v-for="(post, postIndex) in regional_posts" :key="`regional-${post.post_id}`"
                         :class="[postIndex % 2 === 0 ? 'first_vote_window' : 'second_vote_window', 'flex flex-col']">
                        
                        <create-votingform 
                            :candidates="post.candidates"
                            :post="post"
                            @add_selected_candidates="handleCandidateSelection('regional', postIndex, $event)"
                        />
                    </div>
                </div>

                <!-- Vote Summary -->
                <vote-summary 
                    :national-selections="form.national_selected_candidates"
                    :regional-selections="form.regional_selected_candidates"
                />

                <!-- Validation Summary -->
                <div v-if="validationSummary.hasIssues" class="max-w-4xl mx-auto mb-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-yellow-800 font-medium mb-2">Please Review Your Selections</h3>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li v-for="issue in validationSummary.issues" :key="issue">
                                {{ issue }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Agreement Section -->
                <div class="flex flex-col items-center mx-auto my-4 w-full py-4" 
                     style="background-color: #F1F1F1;"> 
                    <div class="flex flex-col w-full border border-3 border-blue-300 mx-2 my-4 py-4 px-6"> 
                        <div class="flex flex-col items-center justify-center py-2 mb-2 text-bold text-red-700 text-xl">
                            <p>Button for Agreement</p> 
                            <p>मतदान गरेको स्विकार</p>  
                        </div>
                        
                        <div class="px-2 py-2">
                            <input 
                                type="checkbox"
                                id="agree_button"
                                name="agree_button"
                                :value="true"
                                v-model="form.agree_button"
                                class="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />
                        </div> 
                        
                        <p>By clicking this button, I confirm that I have chosen the candidates correctly and I followed the online rules to vote the candidates.</p>
                        <p>यो बटनमा थिचेर मैले माथि छाने आनुसार मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु।</p> 
                        
                        <div v-if="form.errors.agree_button" class="text-red-500 text-sm mt-1">
                            {{ form.errors.agree_button }}
                        </div>
                        
                        <!-- Submit Button with enhanced validation -->
                        <button 
                            type="submit" 
                            class="mx-2 my-4 px-2 py-6 rounded-lg w-full mx-auto shadow-sm text-xl font-bold transition-all duration-200"
                            :class="submitButtonClasses"
                            :disabled="!canSubmit"
                        >
                            <span v-if="form.processing" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting Vote...
                            </span>
                            <span v-else-if="!canSubmit" class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                {{ submitButtonText }}
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Vote
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Error display -->
                <div class="mx-auto text-center">
                    <jet-validation-errors class="mb-4 mx-auto text-center" />
                </div>
            </form>
        </app-layout>
    </nrna-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'    
import CreateVotingform from '@/Pages/Vote/CreateVotingform.vue'
import VoteSummary from '@/Pages/Vote/VoteSummary.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default {
    name: 'CreateVotingPage',
    
    components: {
        AppLayout,
        NrnaLayout,
        CreateVotingform,
        VoteSummary,
        JetValidationErrors
    },
    
    props: {
        national_posts: {
            type: Array,
            default: () => []
        },
        regional_posts: {
            type: Array,
            default: () => []
        },
        user_name: {
            type: String,
            required: true
        },
        user_id: {
            type: Number,
            required: true
        },
        user_region: {
            type: String,
            default: ''
        }
    },
    
    setup(props) {
        const form = useForm({
            user_id: props.user_id,
            national_selected_candidates: [],
            regional_selected_candidates: [],
            no_vote_option: false,
            agree_button: false,
        });

        // Initialize arrays based on actual post counts
        if (props.national_posts) {
            form.national_selected_candidates = new Array(props.national_posts.length).fill(null);
        }
        if (props.regional_posts) {
            form.regional_selected_candidates = new Array(props.regional_posts.length).fill(null);
        }

        function submit() {
            // Validate before submission
            const validation = validateVoteData();
            if (!validation.isValid) {
                alert('Please complete your selections before submitting:\n\n' + validation.issues.join('\n'));
                return;
            }
            
            // Show confirmation dialog
            const confirmation = confirm(
                'Are you sure you want to submit your vote? This action cannot be undone.\n\n' +
                'के तपाईं आफ्नो मत पेश गर्न निश्चित हुनुहुन्छ? यो कार्य फिर्ता गर्न सकिदैन।'
            );
            
            if (!confirmation) {
                return;
            }
            
            form.post('/vote/submit_seleccted', {
                onSuccess: () => {
                    console.log('Vote submitted successfully');
                },
                onError: (errors) => {
                    console.error('Vote submission failed:', errors);
                    // Handle specific error cases
                    if (errors.session) {
                        alert('Your session has expired. Please refresh the page and try again.');
                    } else if (errors.integrity) {
                        alert('Vote validation failed. Please check your selections and try again.');
                    }
                }
            });
        }

        function handleCandidateSelection(type, postIndex, selectionData) {
            if (type === 'national') {
                form.national_selected_candidates[postIndex] = selectionData;
            } else if (type === 'regional') {
                form.regional_selected_candidates[postIndex] = selectionData;
            }
        }

        function validateVoteData() {
            const issues = [];
            
            // Check if user has made at least one choice
            const nationalChoices = form.national_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const regionalChoices = form.regional_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const totalChoices = nationalChoices + regionalChoices;
            const totalPosts = (props.national_posts?.length || 0) + (props.regional_posts?.length || 0);
            
            if (totalChoices === 0) {
                issues.push('Please make at least one selection or choose "No Vote" for the positions.');
            }
            
            // Check for incomplete selections
            form.national_selected_candidates.forEach((selection, index) => {
                if (!selection) {
                    const post = props.national_posts[index];
                    issues.push(`Please make a selection for ${post?.name || `National Post ${index + 1}`}`);
                }
            });
            
            form.regional_selected_candidates.forEach((selection, index) => {
                if (!selection) {
                    const post = props.regional_posts[index];
                    issues.push(`Please make a selection for ${post?.name || `Regional Post ${index + 1}`}`);
                }
            });
            
            // Check agreement checkbox
            if (!form.agree_button) {
                issues.push('You must agree to the terms before submitting your vote.');
            }
            
            return {
                isValid: issues.length === 0,
                issues: issues
            };
        }

        return { 
            form, 
            submit,
            handleCandidateSelection,
            validateVoteData
        };
    },
    
    computed: {
        votingProgress() {
            const nationalCompleted = this.form.national_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const regionalCompleted = this.form.regional_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const completed = nationalCompleted + regionalCompleted;
            const total = (this.national_posts?.length || 0) + (this.regional_posts?.length || 0);
            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            
            return {
                completed,
                total,
                percentage
            };
        },
        
        validationSummary() {
            const validation = this.validateVoteData();
            return {
                hasIssues: !validation.isValid,
                issues: validation.issues
            };
        },
        
        canSubmit() {
            return this.validationSummary.hasIssues === false && !this.form.processing;
        },
        
        submitButtonClasses() {
            if (this.form.processing) {
                return 'bg-blue-500 text-white cursor-not-allowed';
            }
            if (!this.canSubmit) {
                return 'bg-gray-400 text-gray-600 cursor-not-allowed';
            }
            return 'bg-blue-600 hover:bg-blue-700 text-white hover:shadow-lg';
        },
        
        submitButtonText() {
            if (!this.form.agree_button) {
                return 'Please agree to terms';
            }
            if (this.votingProgress.completed === 0) {
                return 'Please make selections';
            }
            return 'Complete remaining selections';
        }
    },
    
    mounted() {
        // Auto-save functionality (optional)
        this.startAutoSave();
        
        // Add beforeunload listener to warn about unsaved changes
        window.addEventListener('beforeunload', this.handleBeforeUnload);
    },
    
    beforeUnmount() {
        // Clean up auto-save and event listeners
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
        }
        window.removeEventListener('beforeunload', this.handleBeforeUnload);
    },
    
    methods: {
        startAutoSave() {
            // Save progress to localStorage every 30 seconds
            this.autoSaveInterval = setInterval(() => {
                this.saveProgress();
            }, 30000);
        },
        
        saveProgress() {
            try {
                const progressData = {
                    national_selected_candidates: this.form.national_selected_candidates,
                    regional_selected_candidates: this.form.regional_selected_candidates,
                    agree_button: this.form.agree_button,
                    timestamp: new Date().toISOString(),
                    user_id: this.user_id
                };
                
                localStorage.setItem('voting_progress', JSON.stringify(progressData));
            } catch (error) {
                console.warn('Failed to save voting progress:', error);
            }
        },
        
        loadProgress() {
            try {
                const saved = localStorage.getItem('voting_progress');
                if (saved) {
                    const progressData = JSON.parse(saved);
                    
                    // Verify it's for the same user
                    if (progressData.user_id === this.user_id) {
                        // Check if saved data is not too old (e.g., within last hour)
                        const saveTime = new Date(progressData.timestamp);
                        const now = new Date();
                        const hoursDiff = (now - saveTime) / (1000 * 60 * 60);
                        
                        if (hoursDiff < 1) {
                            this.form.national_selected_candidates = progressData.national_selected_candidates || [];
                            this.form.regional_selected_candidates = progressData.regional_selected_candidates || [];
                            this.form.agree_button = progressData.agree_button || false;
                        }
                    }
                }
            } catch (error) {
                console.warn('Failed to load voting progress:', error);
            }
        },
        
        handleBeforeUnload(event) {
            if (this.votingProgress.completed > 0 && !this.form.processing) {
                event.preventDefault();
                event.returnValue = 'You have unsaved voting selections. Are you sure you want to leave?';
                return event.returnValue;
            }
        }
    }
}
</script>

<style scoped>
.first_vote_window { 
    background-color: #C6FFC1;
}  

.second_vote_window {
    background-color: #BEDCFA;
}  

/* Smooth transitions for progress bar */
.transition-all {
    transition: all 0.3s ease-in-out;
}

/* Enhanced button hover effects */
button:hover:not(:disabled) {
    transform: translateY(-1px);
}

button:active:not(:disabled) {
    transform: translateY(0);
}
</style>