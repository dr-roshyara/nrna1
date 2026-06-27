<template>
    <nrna-layout>
        <app-layout>
            <div class="mt-6 text-center max-w-4xl mx-auto">
                <!-- Success Message -->
                <div class="m-auto text-center bg-gradient-to-r from-green-500 to-blue-600 text-white py-6 px-8 rounded-xl shadow-lg mb-8">
                    <div class="text-4xl mb-3">🎉</div>
                    <p class="text-xl font-bold mb-2">Welcome {{ user_name }}!</p>
                    <p class="text-lg mb-2">Your code has been verified. You can now vote!</p>
                    <p class="mb-3">Please select the candidates of your choice</p>
                    <p class="text-sm opacity-90">आपको कोड सत्यापित भएको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ।</p>
                </div>

                <!-- Validation Errors -->
                <jet-validation-errors class="mb-6 mx-auto text-center" />

                <!-- Voting Form -->
                <form @submit.prevent="submit" class="text-center mx-auto mt-8">
                    <!-- National Posts Section -->
                    <div v-if="posts.national && posts.national.length" class="mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-neutral-800">National Posts</h2>
                        <create-votingform
                            v-for="(post, postIndex) in posts.national"
                            :key="post.id"
                            :post="post"
                            :postIndex="postIndex"
                            :selectedVotes="selectedVotes"
                            @update-votes="handleVoteUpdate"
                        />
                    </div>

                    <!-- Regional Posts Section -->
                    <div v-if="posts.regional && posts.regional.length" class="mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-neutral-800">Regional Posts</h2>
                        <create-votingform
                            v-for="(post, postIndex) in posts.regional"
                            :key="post.id"
                            :post="post"
                            :postIndex="postIndex"
                            :selectedVotes="selectedVotes"
                            @update-votes="handleVoteUpdate"
                        />
                    </div>


                    <!-- Errors -->
                    <div v-if="errors.votes" class="bg-danger-50 border border-danger-200 rounded-lg p-4 mb-6">
                        <p class="text-danger-800 font-semibold">{{ errors.votes }}</p>
                    </div>

                    <!-- Agreement and Submit Section -->
                    <div class="bg-white rounded-xl shadow-lg border border-neutral-200 p-8 mt-8">
                        <!-- Agreement Section -->
                        <div class="border-2 border-primary-300 rounded-lg p-6 mb-6 bg-primary-50">
                            <!-- Header -->
                            <div class="flex flex-col items-center justify-center mb-6">
                                <div class="text-3xl mb-2">✅</div>
                                <h3 class="text-xl font-bold text-danger-700 mb-1">Voting Agreement | मतदान समझौता</h3>
                                <p class="text-lg font-semibold text-danger-700">मतदान गरेको स्विकार</p>
                            </div>

                            <!-- Checkbox -->
                            <div class="flex justify-center mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        v-model="form.agree_button"
                                        class="w-5 h-5 text-primary-600 border-2 border-neutral-400 rounded-sm focus:ring-blue-500 focus:ring-2"
                                    />
                                    <span class="ml-3 text-lg font-medium text-neutral-900">I agree to the terms</span>
                                </label>
                            </div>

                            <!-- Agreement Text -->
                            <div class="bg-white rounded-lg p-4 border border-neutral-200 mb-4">
                                <p class="text-neutral-700 mb-3 leading-relaxed">
                                    By clicking this button, I confirm that I have chosen the candidates correctly and I followed the online rules to vote the candidates.
                                </p>
                                <p class="text-neutral-700 text-sm leading-relaxed">
                                    यो बटनमा थिचेर मैले माथि छाने आनुसार मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु।
                                </p>
                            </div>

                            <!-- Demo Note -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 mb-4">
                                <p class="text-purple-800 text-sm font-medium">
                                    💡 <strong>Demo Mode:</strong> This is a test vote. You can vote again after this for testing.
                                </p>
                            </div>

                            <!-- Checkbox Error -->
                            <div v-if="errors.agree_button" class="text-danger-600 text-sm mb-4 bg-danger-50 p-2 rounded-sm">
                                {{ errors.agree_button }}
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-xl py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                                :disabled="!form.agree_button"
                                :class="{ 'opacity-50 cursor-not-allowed': !form.agree_button }"
                            >
                                <span class="mr-2">🗳️</span>
                                Submit Your Vote
                            </button>
                        </div>
                    </div>

                    <!-- Form Validation Errors -->
                    <div class="mx-auto text-center mt-6">
                        <jet-validation-errors class="mb-4 mx-auto text-center" />
                    </div>
                </form>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import JetValidationErrors from '@/Components/Jetstream/ValidationErrors.vue'
import CreateVotingform from '@/Pages/Vote/DemoVote/CreateVotingform.vue'
import { useForm } from '@inertiajs/vue3'

export default {
    components: {
        AppLayout,
        NrnaLayout,
        JetValidationErrors,
        CreateVotingform
    },

    props: {
        posts: {
            type: Object,
            required: true,
            default: () => ({ national: [], regional: [] })
        },
        user_name: String,
        user_id: Number,
        user_region: String,
        slug: String,
        useSlugPath: Boolean,
        election: Object,
    },

    setup(props) {
        const selectedVotes = ref({})
        const errors = ref({})
        const loading = ref(false)

        const form = useForm({
            user_id: props.user_id,
            agree_button: false,
        })

        function submit() {
            errors.value = {}

            // Validate at least one vote
            if (Object.keys(selectedVotes.value).length === 0) {
                errors.value.votes = 'Please select at least one candidate'
                return
            }

            loading.value = true

            const voteForm = useForm({
                votes: selectedVotes.value,
            })

            const routeName = props.useSlugPath ? 'slug.demo-vote.submit' : 'demo-vote.submit'
            const params = props.useSlugPath ? { vslug: props.slug } : {}

            voteForm.post(route(routeName, params), {
                onError: (formErrors) => {
                    if (formErrors.votes) {
                        errors.value.votes = formErrors.votes
                    }
                    loading.value = false
                },
            })
        }

        const handleVoteUpdate = ({ postId, candidateId, candidateData }) => {
                if (candidateId === null) {
                    // User deselected - mark as no_vote
                    selectedVotes.value[postId] = {
                        post_id: postId,
                        post_name: candidateData?.post_name || '',
                        required_number: candidateData?.required_number || 1,
                        no_vote: true,
                        candidates: []
                    }
                } else {
                    // User selected a candidate
                    selectedVotes.value[postId] = {
                        post_id: postId,
                        post_name: candidateData?.post_name || '',
                        required_number: candidateData?.required_number || 1,
                        no_vote: false,
                        candidates: [{
                            candidacy_id: candidateId,  // ✅ Use candidacy_id, not candidate_id
                            // Include other candidate data if needed
                        }]
                    }
                }
            }

        return {
            form,
            submit,
            selectedVotes,
            errors,
            loading,
            handleVoteUpdate,
        }
    },
}
</script>

<style scoped>
.candidate-selection {
    scroll-margin-top: 2rem;
}

.candidate-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.candidate-card:hover:not(.cursor-not-allowed) {
    transform: translateY(-4px);
}

/* Screen Reader Only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* Focus styles for accessibility */
input:focus-visible + label,
button:focus-visible,
[role="button"]:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .candidate-card,
    .candidate-card:hover,
    .transition-all {
        transition: none !important;
        transform: none !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .candidate-card {
        border-width: 2px !important;
    }

    .ring-4 {
        outline: 3px solid #000 !important;
    }
}

/* Large touch targets for mobile */
@media (max-width: 640px) {
    .candidate-card {
        padding: 1rem;
    }

    input[type="checkbox"] + label {
        min-width: 48px;
        min-height: 48px;
    }
}
</style>
