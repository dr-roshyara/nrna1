# Professional Prompt Engineering Instructions for Claude Code CLI

## System Context Setup
```
You are a Senior Full-Stack Developer specializing in Laravel, Vue.js, and Inertia.js. You're implementing a voting system enhancement that adds compulsory candidate selection based on environment configuration.
```

## Task Overview
```
Implement SELECT_ALL_REQUIRED feature that enforces voters to select exactly the required number of candidates when enabled, while maintaining current flexible behavior when disabled.
```

## File Structure & Dependencies
```
Project Structure:
- Laravel 9+ with Inertia.js
- Vue 3 with Composition API
- Tailwind CSS for styling
- Current files to modify:
  * resources/js/Pages/Vote/CreateVotingform.vue
  * resources/js/Pages/Vote/CreateVotingPage.vue
  * app/Http/Controllers/VoteController.php
  * config/app.php
  * .env
```

## Implementation Steps

### Step 1: Environment Configuration
```bash
# Add to .env file
echo "SELECT_ALL_REQUIRED=yes" >> .env
```

### Step 2: Laravel Backend Configuration
```bash
# Create helper class
mkdir -p app/Helpers
cat > app/Helpers/VotingValidator.php << 'EOF'
<?php

namespace App\Helpers;

class VotingValidator
{
    public static function isSelectAllRequired()
    {
        return config('app.select_all_required', 'no') === 'yes';
    }
    
    public static function validatePostSelection($post, $selectedCandidates)
    {
        $isRequired = self::isSelectAllRequired();
        
        if ($isRequired) {
            // Must select exactly required_number candidates
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount === $requiredCount;
        } else {
            // Current behavior: can select 0 to required_number
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount <= $requiredCount;
        }
    }
}
EOF
```

### Step 3: Update Laravel Config
```bash
# Add to config/app.php
sed -i "/'timezone' => 'UTC',/a\
    'select_all_required' => env('SELECT_ALL_REQUIRED', 'no')," config/app.php
```

### Step 4: Update CreateVotingPage.vue
```bash
cat > resources/js/Pages/Vote/CreateVotingPage.vue << 'EOF'
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
        },
        slug: {
            type: String,
            default: null
        },
        useSlugPath: {
            type: Boolean,
            default: false
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
            
            // Use slug-based route if available, otherwise use regular route
            const submitUrl = props.useSlugPath && props.slug
                ? `/v/${props.slug}/vote/submit`
                : '/vote/submit';

            form.post(submitUrl, {
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
            const isSelectAllRequired = process.env.SELECT_ALL_REQUIRED === 'yes' || 
                                      import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
            
            // Validate national posts
            this.form.national_selected_candidates.forEach((selection, index) => {
                if (!selection) {
                    const post = this.national_posts[index];
                    issues.push(`Please make a selection for ${post?.name || `National Post ${index + 1}`}`);
                } else if (selection.no_vote) {
                    // No vote selected - this is always valid
                    return;
                } else if (isSelectAllRequired) {
                    // Must select exactly required_number candidates
                    const required = this.national_posts[index]?.required_number || 1;
                    const selected = selection.candidates.length;
                    
                    if (selected !== required) {
                        const postName = this.national_posts[index]?.name || `National Post ${index + 1}`;
                        issues.push(`Please select exactly ${required} candidate(s) for ${postName}`);
                    }
                } else {
                    // Current behavior: validate max selections
                    const required = this.national_posts[index]?.required_number || 1;
                    const selected = selection.candidates.length;
                    
                    if (selected > required) {
                        const postName = this.national_posts[index]?.name || `National Post ${index + 1}`;
                        issues.push(`You can select maximum ${required} candidate(s) for ${postName}`);
                    }
                }
            });
            
            // Validate regional posts (same logic)
            this.form.regional_selected_candidates.forEach((selection, index) => {
                if (!selection) {
                    const post = this.regional_posts[index];
                    issues.push(`Please make a selection for ${post?.name || `Regional Post ${index + 1}`}`);
                } else if (selection.no_vote) {
                    return;
                } else if (isSelectAllRequired) {
                    const required = this.regional_posts[index]?.required_number || 1;
                    const selected = selection.candidates.length;
                    
                    if (selected !== required) {
                        const postName = this.regional_posts[index]?.name || `Regional Post ${index + 1}`;
                        issues.push(`Please select exactly ${required} candidate(s) for ${postName}`);
                    }
                } else {
                    const required = this.regional_posts[index]?.required_number || 1;
                    const selected = selection.candidates.length;
                    
                    if (selected > required) {
                        const postName = this.regional_posts[index]?.name || `Regional Post ${index + 1}`;
                        issues.push(`You can select maximum ${required} candidate(s) for ${postName}`);
                    }
                }
            });
            
            // Check agreement checkbox
            if (!this.form.agree_button) {
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
EOF
```

### Step 5: Update CreateVotingform.vue
```bash
cat > resources/js/Pages/Vote/CreateVotingform.vue << 'EOF'
<template>
    <div id="vote_window" 
         class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
        
        <div class="flex flex-col text-xl font-bold text-gray-900 mx-auto text-center justify-center">
            <label>
                Please choose 
                <span class="text-indigo-600">{{ post.required_number }}</span> 
                candidate(s) as the 
                <span class="text-gray-900 font-bold">{{ post.name }}</span>.
                <span v-if="selectAllRequired" class="text-red-600 text-sm block mt-1">
                    (Selection of all {{ post.required_number }} candidates is required)
                </span>
            </label> 
            <label class="p-2">
                कृपया 
                <span class="text-indigo-600">{{ post.required_number }}</span> 
                जना लाई  
                <span class="text-gray-900 font-bold">{{ post.nepali_name || post.name }}</span> 
                चुन्नुहोस्।
                <span v-if="selectAllRequired" class="text-red-600 text-sm block mt-1">
                    (सबै {{ post.required_number }} जना उम्मेदवार छान्नु अनिवार्य छ)
                </span>
            </label>   
        </div>
                   
        <!-- Candidates Section -->
        <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
            <div v-for="(candidate, candiIndex) in candidatesWithState" 
                 :key="candidate.candidacy_id"  
                 class="flex flex-col justify-center p-4 mb-2 text-center border border-gray-100 rounded transition-opacity duration-200"
                 :class="{ 'opacity-40 pointer-events-none': noVoteSelected }"> 
                
                <show-candidate 
                    :candidacy_image_path="candidate.image_path_1"
                    :post_name="post.name"   
                    :post_nepali_name="post.nepali_name"  
                    :candidacy_name="candidate.user?.name || 'Unknown Candidate'"
                />
                
                <!-- Voting checkbox -->
                <div class="px-2 py-2">
                    <input 
                        type="checkbox"
                        :id="candidate.candidacy_id"
                        :name="post.name"
                        :value="candidate.candidacy_id"  
                        class="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                        v-model="selected"
                        @change="updateBoxes()"
                        :disabled="candidate.disabled || noVoteSelected"
                    />
                </div> 
            </div>
        </div>
        
        <!-- Selection Summary -->
        <div class="mb-4 p-3 text-center mx-auto border-t border-gray-200 pt-4">
            <div v-if="noVoteSelected" class="text-red-600 font-semibold">
                You have chosen not to vote for {{ post.name }}
                <br>
                <span class="text-sm">तपाईंले {{ post.nepali_name || post.name }} का लागि मतदान नगर्ने रोज्नुभएको छ</span>
            </div>
            <div v-else-if="selected.length" 
                 :class="{
                     'text-green-600': selectionStatus.type === 'valid' || selectionStatus.type === 'full',
                     'text-yellow-600': selectionStatus.type === 'partial',
                     'text-red-600': selectionStatus.type === 'invalid'
                 }"> 
                <div class="font-semibold">
                    {{ selectionStatus.message }}
                </div>
                You have selected 
                <span class="font-bold text-indigo-600"> 
                    {{ getSelectedNames() }}
                </span> 
                as <span class="font-bold text-lg text-gray-900">{{ post.name }}</span> of NRNA!
            </div>
            <div v-else class="text-gray-500 text-sm">
                Please select your preferred candidate(s) for {{ post.name }}
                <br>
                <span class="text-xs">कृपया {{ post.nepali_name || post.name }} का लागि आफ्नो मनपर्ने उम्मेदवार छान्नुहोस्</span>
            </div>
        </div>

        <!-- No Vote Option - Placed at the bottom in smaller form -->
        <div class="flex justify-center mx-auto mt-2 mb-2">
            <div class="bg-gray-200 border border-gray-300 rounded-md px-4 py-2 text-sm">
                <div class="flex items-center space-x-2">
                    <input 
                        type="checkbox"
                        :id="`no_vote_${post.post_id}`"
                        name="no_vote_option"
                        v-model="noVoteSelected"
                        @change="handleNoVoteChange"
                        class="h-4 w-4 text-gray-500 border border-gray-400 rounded focus:ring-gray-400 focus:ring-1"
                    />
                    <label :for="`no_vote_${post.post_id}`" class="text-xs text-gray-600 cursor-pointer">
                      I want to skip this position / म यो पदमा मतदान गर्न इच्छुक छैन। 
                    </label>
                </div>
                <div class="text-xs text-gray-500 mt-1 text-center">
                    (Choose this only if you don't wish to vote for any candidate)
                    <br>
                    <span class="text-xs">(कुनै पनि उम्मेदवारलाई मत दिन नचाहेमा मात्र यो छान्नुहोस्)</span>
                </div>
            </div>
        </div>
    </div>                 
</template>

<script>
import ShowCandidate from '@/Shared/ShowCandidate'

export default {
    name: 'CreateVotingform',
    
    components: {
        ShowCandidate
    },
    
    props: {
        candidates: {
            type: Array,
            required: true,
            default: () => []
        },
        post: {
            type: Object,
            required: true
        }
    },
    
    data() {
        return {
            selected: [],
            candidatesWithState: [],
            noVoteSelected: false
        }
    },
    
    computed: {
        maxSelections() {
            return this.post?.required_number || 1;
        },
        
        selectAllRequired() {
            // Check if SELECT_ALL_REQUIRED is enabled
            return process.env.SELECT_ALL_REQUIRED === 'yes' || 
                   import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
        },
        
        hasValidSelection() {
            if (this.noVoteSelected) return true;
            
            if (this.selectAllRequired) {
                return this.selected.length === this.maxSelections;
            } else {
                return this.selected.length <= this.maxSelections;
            }
        },
        
        selectionStatus() {
            if (this.noVoteSelected) {
                return { type: 'no-vote', message: 'No vote selected' };
            }
            
            if (this.selectAllRequired) {
                if (this.selected.length === this.maxSelections) {
                    return { type: 'valid', message: `Perfect! You selected ${this.maxSelections} candidate(s)` };
                } else {
                    return { 
                        type: 'invalid', 
                        message: `Please select exactly ${this.maxSelections} candidate(s)` 
                    };
                }
            } else {
                if (this.selected.length === 0) {
                    return { type: 'empty', message: 'No candidates selected' };
                } else if (this.selected.length === this.maxSelections) {
                    return { type: 'full', message: `Maximum ${this.maxSelections} selected` };
                } else {
                    return { type: 'partial', message: `${this.selected.length} of ${this.maxSelections} selected` };
                }
            }
        }
    },
    
    watch: {
        candidates: {
            immediate: true,
            handler(newCandidates) {
                // Initialize candidates with disabled state
                this.candidatesWithState = newCandidates.map(candidate => ({
                    ...candidate,
                    disabled: false
                }));
            }
        },
        
        selected: {
            handler() {
                this.informSelectedCandidates();
            }
        },
        
        noVoteSelected: {
            handler() {
                this.informSelectedCandidates();
            }
        }
    },
    
    methods: {
        informSelectedCandidates() {
            // Emit the selected candidate objects, not just IDs
            let selectionData;
            
            if (this.noVoteSelected) {
                // When no vote is selected, send a special structure
                selectionData = {
                    post_id: this.post.post_id,
                    post_name: this.post.name,
                    required_number: this.post.required_number,
                    no_vote: true,
                    candidates: []
                };
            } else {
                // Normal candidate selection
                const selectedCandidates = this.candidatesWithState.filter(candidate => 
                    this.selected.includes(candidate.candidacy_id)
                );
                
                selectionData = {
                    post_id: this.post.post_id,
                    post_name: this.post.name,
                    required_number: this.post.required_number,
                    no_vote: false,
                    candidates: selectedCandidates.map(candidate => ({
                        candidacy_id: candidate.candidacy_id,
                        user_id: candidate.user?.user_id || candidate.user?.id,
                        name: candidate.user?.name,
                        post_id: candidate.post_id || this.post.post_id
                    }))
                };
            }
            
            this.$emit('add_selected_candidates', selectionData);
        },
        
        handleNoVoteChange() {
            if (this.noVoteSelected) {
                // Clear all candidate selections when no vote is selected
                this.selected = [];
                // Disable all candidate checkboxes (handled by :disabled in template)
                this.candidatesWithState.forEach(candidate => {
                    candidate.disabled = true;
                });
            } else {
                // Re-enable candidate checkboxes when no vote is deselected
                this.candidatesWithState.forEach(candidate => {
                    candidate.disabled = false;
                });
            }
            
            // Inform parent component about the change
            this.informSelectedCandidates();
        },
        
        updateBoxes() {
            // If no vote is selected, don't allow candidate selection
            if (this.noVoteSelected) {
                return;
            }
            
            // Re-enable all checkboxes first
            this.candidatesWithState.forEach(candidate => {
                candidate.disabled = false;
            });
            
            // If we've reached the limit, disable unselected checkboxes
            if (this.selected.length >= this.maxSelections) {
                this.candidatesWithState.forEach(candidate => {
                    if (!this.selected.includes(candidate.candidacy_id)) {
                        candidate.disabled = true;
                    }
                });
            }
        },
        
        getSelectedNames() {
            if (this.noVoteSelected) {
                return 'No Vote';
            }
            
            const selectedCandidates = this.candidatesWithState.filter(candidate => 
                this.selected.includes(candidate.candidacy_id)
            );
            return selectedCandidates.map(candidate => candidate.user?.name || 'Unknown').join(', ');
        }
    }
}
</script>

<style scoped>
#vote_window {
    transition: all 0.3s ease;
}

#vote_window:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

input[type="checkbox"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Smooth transitions for when no vote is selected */
.transition-opacity {
    transition: opacity 0.3s ease-in-out;
}

/* Make candidates visually de-emphasized when no vote is selected */
.opacity-40 {
    opacity: 0.4;
}

.pointer-events-none {
    pointer-events: none;
}

/* Style the no vote section to be less prominent */
.no-vote-section {
    background: linear-gradient(to right, #f9fafb, #f3f4f6);
}
</style>
EOF
```

### Step 6: Update VoteController for Server-Side Validation
```bash
# Add this method to VoteController.php
cat >> app/Http/Controllers/VoteController.php << 'EOF'

    /**
     * Validate vote selections server-side
     */
    private function validateVoteSelections($nationalSelections, $regionalSelections)
    {
        $isSelectAllRequired = config('app.select_all_required', 'no') === 'yes';
        
        // Validate national selections
        foreach ($nationalSelections as $selection) {
            if ($selection && !$selection['no_vote']) {
                $post = Post::find($selection['post_id']);
                $selectedCount = count($selection['candidates']);
                
                if ($isSelectAllRequired && $selectedCount !== $post->required_number) {
                    return "You must select exactly {$post->required_number} candidates for {$post->name}";
                }
                
                if (!$isSelectAllRequired && $selectedCount > $post->required_number) {
                    return "You can select maximum {$post->required_number} candidates for {$post->name}";
                }
            }
        }
        
        // Validate regional selections
        foreach ($regionalSelections as $selection) {
            if ($selection && !$selection['no_vote']) {
                $post = Post::find($selection['post_id']);
                $selectedCount = count($selection['candidates']);
                
                if ($isSelectAllRequired && $selectedCount !== $post->required_number) {
                    return "You must select exactly {$post->required_number} candidates for {$post->name}";
                }
                
                if (!$isSelectAllRequired && $selectedCount > $post->required_number) {
                    return "You can select maximum {$post->required_number} candidates for {$post->name}";
                }
            }
        }
        
        return null;
    }
EOF
```

### Step 7: Clear Cache and Test
```bash
# Clear configuration cache
php artisan config:clear
php artisan cache:clear

# Run tests
php artisan test
```

## Testing Commands

### Test with SELECT_ALL_REQUIRED=yes
```bash
# Set to required mode
echo "SELECT_ALL_REQUIRED=yes" >> .env

# Clear cache
php artisan config:clear

# Test the application
php artisan serve
```

### Test with SELECT_ALL_REQUIRED
Looking at your voting system, I can see how candidate selection is currently implemented and how to make it compulsory based on the `.env` setting. Let me analyze the current setup and provide the solution.

## Current Candidate Selection Setup

### In `CreateVotingform.vue`:
- **Selection Logic**: Uses `v-model="selected"` array to track selected candidates
- **Maximum Selections**: Controlled by `maxSelections` computed property from `post.required_number`
- **Current Behavior**: 
  - Voter can select 0, 1, or up to `required_number` candidates
  - When limit is reached, other candidates are disabled
  - "No Vote" option allows skipping the position entirely

### In `CreateVotingPage.vue`:
- **Validation**: Currently only checks if at least one selection is made across all posts
- **No Post-Level Validation**: Doesn't enforce that each post must have exactly `required_number` selections

## Solution: Implement SELECT_ALL_REQUIRED Feature

### Step 1: Update .env File
```env
SELECT_ALL_REQUIRED=yes
# or
SELECT_ALL_REQUIRED=no
```

### Step 2: Update Laravel Backend

**In VoteController.php or a helper file:**
```php
<?php

namespace App\Helpers;

class VotingValidator
{
    public static function isSelectAllRequired()
    {
        return config('app.select_all_required', 'no') === 'yes';
    }
    
    public static function validatePostSelection($post, $selectedCandidates)
    {
        $isRequired = self::isSelectAllRequired();
        
        if ($isRequired) {
            // Must select exactly required_number candidates
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount === $requiredCount;
        } else {
            // Current behavior: can select 0 to required_number
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount <= $requiredCount;
        }
    }
}
```

**Add to config/app.php:**
```php
'select_all_required' => env('SELECT_ALL_REQUIRED', 'no'),
```

### Step 3: Update CreateVotingPage.vue

**Add computed property for SELECT_ALL_REQUIRED:**
```javascript
computed: {
    selectAllRequired() {
        // Check if SELECT_ALL_REQUIRED is enabled
        return process.env.SELECT_ALL_REQUIRED === 'yes' || 
               import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
    },
    
    // ... existing computed properties
}
```

**Update validation method:**
```javascript
validateVoteData() {
    const issues = [];
    const isSelectAllRequired = this.selectAllRequired;
    
    // Validate national posts
    this.form.national_selected_candidates.forEach((selection, index) => {
        if (!selection) {
            const post = this.national_posts[index];
            issues.push(`Please make a selection for ${post?.name || `National Post ${index + 1}`}`);
        } else if (selection.no_vote) {
            // No vote selected - this is always valid
            return;
        } else if (isSelectAllRequired) {
            // Must select exactly required_number candidates
            const required = this.national_posts[index]?.required_number || 1;
            const selected = selection.candidates.length;
            
            if (selected !== required) {
                const postName = this.national_posts[index]?.name || `National Post ${index + 1}`;
                issues.push(`Please select exactly ${required} candidate(s) for ${postName}`);
            }
        } else {
            // Current behavior: validate max selections
            const required = this.national_posts[index]?.required_number || 1;
            const selected = selection.candidates.length;
            
            if (selected > required) {
                const postName = this.national_posts[index]?.name || `National Post ${index + 1}`;
                issues.push(`You can select maximum ${required} candidate(s) for ${postName}`);
            }
        }
    });
    
    // Validate regional posts (same logic)
    this.form.regional_selected_candidates.forEach((selection, index) => {
        if (!selection) {
            const post = this.regional_posts[index];
            issues.push(`Please make a selection for ${post?.name || `Regional Post ${index + 1}`}`);
        } else if (selection.no_vote) {
            return;
        } else if (isSelectAllRequired) {
            const required = this.regional_posts[index]?.required_number || 1;
            const selected = selection.candidates.length;
            
            if (selected !== required) {
                const postName = this.regional_posts[index]?.name || `Regional Post ${index + 1}`;
                issues.push(`Please select exactly ${required} candidate(s) for ${postName}`);
            }
        } else {
            const required = this.regional_posts[index]?.required_number || 1;
            const selected = selection.candidates.length;
            
            if (selected > required) {
                const postName = this.regional_posts[index]?.name || `Regional Post ${index + 1}`;
                issues.push(`You can select maximum ${required} candidate(s) for ${postName}`);
            }
        }
    });
    
    // Check agreement checkbox
    if (!this.form.agree_button) {
        issues.push('You must agree to the terms before submitting your vote.');
    }
    
    return {
        isValid: issues.length === 0,
        issues: issues
    };
}
```

### Step 4: Update CreateVotingform.vue

**Add real-time validation feedback:**
```javascript
computed: {
    maxSelections() {
        return this.post?.required_number || 1;
    },
    
    hasValidSelection() {
        if (this.noVoteSelected) return true;
        
        const isSelectAllRequired = process.env.SELECT_ALL_REQUIRED === 'yes' || 
                                   import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
        
        if (isSelectAllRequired) {
            return this.selected.length === this.maxSelections;
        } else {
            return this.selected.length <= this.maxSelections;
        }
    },
    
    selectionStatus() {
        if (this.noVoteSelected) {
            return { type: 'no-vote', message: 'No vote selected' };
        }
        
        const isSelectAllRequired = process.env.SELECT_ALL_REQUIRED === 'yes' || 
                                   import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
        
        if (isSelectAllRequired) {
            if (this.selected.length === this.maxSelections) {
                return { type: 'valid', message: `Perfect! You selected ${this.maxSelections} candidate(s)` };
            } else {
                return { 
                    type: 'invalid', 
                    message: `Please select exactly ${this.maxSelections} candidate(s)` 
                };
            }
        } else {
            if (this.selected.length === 0) {
                return { type: 'empty', message: 'No candidates selected' };
            } else if (this.selected.length === this.maxSelections) {
                return { type: 'full', message: `Maximum ${this.maxSelections} selected` };
            } else {
                return { type: 'partial', message: `${this.selected.length} of ${this.maxSelections} selected` };
            }
        }
    }
}
```

**Update template to show validation status:**
```vue
<!-- Selection Summary -->
<div class="mb-4 p-3 text-center mx-auto border-t border-gray-200 pt-4">
    <div v-if="noVoteSelected" class="text-red-600 font-semibold">
        You have chosen not to vote for {{ post.name }}
        <br>
        <span class="text-sm">तपाईंले {{ post.nepali_name || post.name }} का लागि मतदान नगर्ने रोज्नुभएको छ</span>
    </div>
    <div v-else-if="selected.length" 
         :class="{
             'text-green-600': selectionStatus.type === 'valid' || selectionStatus.type === 'full',
             'text-yellow-600': selectionStatus.type === 'partial',
             'text-red-600': selectionStatus.type === 'invalid'
         }"> 
        <div class="font-semibold">
            {{ selectionStatus.message }}
        </div>
        You have selected 
        <span class="font-bold text-indigo-600"> 
            {{ getSelectedNames() }}
        </span> 
        as <span class="font-bold text-lg text-gray-900">{{ post.name }}</span> of NRNA!
    </div>
    <div v-else class="text-gray-500 text-sm">
        Please select your preferred candidate(s) for {{ post.name }}
        <br>
        <span class="text-xs">कृपया {{ post.nepali_name || post.nepali_name || post.name }} का लागि आफ्नो मनपर्ने उम्मेदवार छान्नुहोस्</span>
    </div>
</div>
```

### Step 5: Update Instructions Text

**In CreateVotingform.vue template:**
```vue
<div class="flex flex-col text-xl font-bold text-gray-900 mx-auto text-center justify-center">
    <label>
        Please choose 
        <span class="text-indigo-600">{{ post.required_number }}</span> 
        candidate(s) as the 
        <span class="text-gray-900 font-bold">{{ post.name }}</span>.
        <span v-if="selectAllRequired" class="text-red-600 text-sm block">
            (Selection of all {{ post.required_number }} candidates is required)
        </span>
    </label> 
    <label class="p-2">
        कृपया 
        <span class="text-indigo-600">{{ post.required_number }}</span> 
        जना लाई  
        <span class="text-gray-900 font-bold">{{ post.nepali_name || post.name }}</span> 
        चुन्नुहोस्।
        <span v-if="selectAllRequired" class="text-red-600 text-sm block">
            (सबै {{ post.required_number }} जना उम्मेदवार छान्नु अनिवार्य छ)
        </span>
    </label>   
</div>
```

### Step 6: Server-Side Validation

**In your VoteController submit method:**
```php
public function submit(Request $request)
{
    $isSelectAllRequired = config('app.select_all_required', 'no') === 'yes';
    
    // Validate national selections
    foreach ($request->national_selected_candidates as $index => $selection) {
        if ($selection && !$selection['no_vote']) {
            $post = Post::find($selection['post_id']);
            $selectedCount = count($selection['candidates']);
            
            if ($isSelectAllRequired && $selectedCount !== $post->required_number) {
                return back()->withErrors([
                    'selections' => "You must select exactly {$post->required_number} candidates for {$post->name}"
                ]);
            }
            
            if (!$isSelectAllRequired && $selectedCount > $post->required_number) {
                return back()->withErrors([
                    'selections' => "You can select maximum {$post->required_number} candidates for {$post->name}"
                ]);
            }
        }
    }
    
    // Similar validation for regional posts
    
    // Continue with existing submission logic...
}
```

## Summary

With this implementation:

1. **When `SELECT_ALL_REQUIRED=no`** (default): Current behavior - voters can select 0 to `required_number` candidates
2. **When `SELECT_ALL_REQUIRED=yes`**: Voters must select exactly `required_number` candidates for each position

The system provides:
- Real-time validation feedback in the UI
- Clear error messages
- Server-side validation for security
- Proper visual indicators for selection status
- Bilingual support (English/Nepali)

This maintains your current flexible system while adding the compulsory selection feature when needed.