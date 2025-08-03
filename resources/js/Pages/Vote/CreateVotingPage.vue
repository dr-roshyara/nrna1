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
                        
                        <button 
                            type="submit" 
                            class="mx-2 my-4 px-2 py-6 rounded-lg bg-blue-300 w-full mx-auto shadow-sm text-xl font-bold text-gray-900"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Submitting...</span>
                            <span v-else>Submit</span>
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
        // Calculate total number of posts for form initialization
        const totalPosts = (props.national_posts?.length || 0) + (props.regional_posts?.length || 0);
        
        const form = useForm({
            user_id: props.user_id,
            national_selected_candidates: [],
            regional_selected_candidates: [],
            no_vote_option: false, // Global no vote option (if needed)
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
            // Validate that user has made some selections or explicitly chosen no vote for all positions
            const hasNationalSelections = form.national_selected_candidates.some(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            );
            
            const hasRegionalSelections = form.regional_selected_candidates.some(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            );

            // Check if user has made at least one choice (either candidate selection or no vote)
            const totalNationalPosts = props.national_posts?.length || 0;
            const totalRegionalPosts = props.regional_posts?.length || 0;
            const totalPosts = totalNationalPosts + totalRegionalPosts;
            
            const nationalChoices = form.national_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const regionalChoices = form.regional_selected_candidates.filter(selection => 
                selection && (selection.candidates.length > 0 || selection.no_vote)
            ).length;
            
            const totalChoices = nationalChoices + regionalChoices;
            
            if (totalChoices === 0) {
                alert('Please make at least one selection or choose "No Vote" for the positions.');
                return;
            }
            
            form.post('/vote/submit_seleccted');
        }

        function handleCandidateSelection(type, postIndex, selectionData) {
            if (type === 'national') {
                form.national_selected_candidates[postIndex] = selectionData;
            } else if (type === 'regional') {
                form.regional_selected_candidates[postIndex] = selectionData;
            }
        }

        return { 
            form, 
            submit,
            handleCandidateSelection
        };
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
</style>