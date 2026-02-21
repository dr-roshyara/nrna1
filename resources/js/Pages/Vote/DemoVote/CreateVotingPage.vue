<template>
    <election-layout>
        <!-- Accessibility Announcement -->
        <div class="sr-only" aria-live="polite" aria-label="Page announcement">
            {{ $t('pages.voting.aria_labels.page_loaded') }}
        </div>

        <!-- Skip to Main Content Link -->
        <a href="#main-content" class="skip-link">
            {{ $t('pages.voting.aria_labels.skip_to_content') }}
        </a>

        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Page Header with Badge -->
                <header role="banner" class="text-center mb-12">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <h1 class="text-4xl font-bold text-gray-900">
                            {{ $t('pages.voting.header.title') }}
                        </h1>
                        <div class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full font-semibold text-sm flex items-center gap-2">
                            <span class="text-xl">🎮</span>
                            Demo Mode
                        </div>
                    </div>
                    <p class="text-xl text-gray-600 mb-4">
                        {{ $t('pages.voting.header.subtitle', { name: name }) }}
                    </p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full" aria-hidden="true"></div>
                </header>

                <!-- Voter Information Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg mr-4 flex-shrink-0">
                                <span class="text-green-600 text-2xl">👤</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">{{ $t('pages.voting.voter_info.label') }}</p>
                                <p class="font-bold text-gray-900 text-lg">{{ name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-blue-200">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4 flex-shrink-0">
                                <span class="text-blue-600 text-2xl">📋</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">Election</p>
                                <p class="font-bold text-gray-900 text-lg">{{ election_name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-purple-200">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4 flex-shrink-0">
                                <span class="text-purple-600 text-2xl">📊</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">{{ $t('pages.voting.progress_info.label') }}</p>
                                <p class="font-bold text-gray-900 text-lg">{{ votingProgress.completed }}/{{ votingProgress.total }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workflow Step Indicator - Step 3/5 -->
                <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-6 md:py-8 mb-8">
                    <WorkflowStepIndicator workflow="VOTING" :currentStep="3" />
                </div>

                <!-- Demo Mode Notice -->
                <div class="max-w-4xl mx-auto bg-purple-50 border-2 border-purple-300 rounded-lg p-6 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">🎮</div>
                        <div class="text-left">
                            <h3 class="font-bold text-purple-900 text-lg mb-2">Demo Election Mode</h3>
                            <p class="text-purple-800">This is a test voting system. Your selections here are for testing purposes only and will not affect real election results. You can vote multiple times to test the complete workflow.</p>
                        </div>
                    </div>
                </div>

                <!-- Main Voting Form -->
                <form @submit.prevent="submit" :aria-label="$t('pages.voting.aria_labels.voting_form')">
                    <main id="main-content" role="main" :aria-label="$t('pages.voting.aria_labels.main_content')">

                        <!-- National Posts Section -->
                        <section v-if="national_posts && national_posts.length > 0" class="mb-12" aria-labelledby="national-posts-title">
                            <h2 id="national-posts-title" class="text-3xl font-bold text-gray-900 text-center mb-8">
                                {{ $t('pages.voting.national_posts.section_title') }}
                            </h2>
                            <div class="space-y-8">
                                <div v-for="(post, postIndex) in national_posts" :key="`national-${post.post_id}`"
                                     class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
                                     :data-post-key="`national-${post.post_id}`">
                                    <div class="p-6">
                                        <create-votingform
                                            :candidates="post.candidates"
                                            :post="post"
                                            :errors="errors"
                                            :postType="'national'"
                                            :postIndex="postIndex"
                                            @add_selected_candidates="handleCandidateSelection('national', postIndex, $event)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Regional Posts Section -->
                        <section v-if="regional_posts && regional_posts.length > 0" class="mb-12" aria-labelledby="regional-posts-title">
                            <h2 id="regional-posts-title" class="text-3xl font-bold text-gray-900 text-center mb-8">
                                {{ regionalPostsMessages.sectionTitle?.replace('{region}', user_region) || `Candidates for ${user_region} Region` }}
                            </h2>
                            <div class="space-y-8">
                                <div v-for="(post, postIndex) in regional_posts" :key="`regional-${post.post_id}`"
                                     class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
                                     :data-post-key="`regional-${post.post_id}`">
                                    <div class="p-6">
                                        <create-votingform
                                            :candidates="post.candidates"
                                            :post="post"
                                            :errors="errors"
                                            :postType="'regional'"
                                            :postIndex="postIndex"
                                            @add_selected_candidates="handleCandidateSelection('regional', postIndex, $event)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- No Regional Posts Message -->
                        <section v-if="!regional_posts || regional_posts.length === 0 && user_region" class="mb-12" aria-labelledby="no-regional-posts-title">
                            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-8 max-w-2xl mx-auto text-center">
                                <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h2 id="no-regional-posts-title" class="text-2xl font-bold text-yellow-800 mb-4">
                                    {{ regionalPostsMessages.noCandidatesTitle || 'No Regional Candidates' }}
                                </h2>
                                <p class="text-yellow-700 text-lg">
                                    {{ regionalPostsMessages.noCandidatesMessage?.replace('{region}', user_region) || `There are currently no candidates available for your region ${user_region}.` }}
                                </p>
                            </div>
                        </section>

                        <!-- Validation Issues Alert (only show after user attempts submission) -->
                        <div v-if="attemptedSubmit && validationSummary.hasIssues" class="max-w-4xl mx-auto bg-amber-50 border-l-4 border-amber-500 p-6 mb-8 rounded-lg shadow-md" role="alert" aria-live="polite">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-amber-900">{{ $t('pages.voting.validation.title') }}</h3>
                                    <ul class="text-sm text-amber-800 mt-2 space-y-1 list-disc list-inside">
                                        <li v-for="issue in validationSummary.issues" :key="issue">
                                            {{ issue }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement Section -->
                        <section class="max-w-4xl mx-auto mb-12" aria-labelledby="agreement-title">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-6 px-8">
                                    <h2 id="agreement-title" class="text-2xl font-bold">{{ $t('pages.voting.agreement.section_title') }}</h2>
                                    <p class="text-sm opacity-90 mt-2">{{ $t('pages.voting.agreement.section_subtitle') }}</p>
                                </div>

                                <div class="p-8 space-y-6">
                                    <!-- Agreement Terms -->
                                    <div>
                                        <p class="text-gray-700 font-medium mb-4">
                                            {{ $t('pages.voting.agreement.intro_text') }}
                                        </p>
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-r-lg">
                                            <h3 class="font-bold text-blue-900 mb-4 text-lg">{{ $t('pages.voting.agreement.key_conditions') }}</h3>
                                            <ul class="space-y-3">
                                                <li class="flex items-start text-gray-800">
                                                    <span class="text-green-600 font-bold mr-3 flex-shrink-0 mt-1">✓</span>
                                                    <span class="text-base">{{ $t('pages.voting.agreement.condition_1') }}</span>
                                                </li>
                                                <li class="flex items-start text-gray-800">
                                                    <span class="text-green-600 font-bold mr-3 flex-shrink-0 mt-1">✓</span>
                                                    <span class="text-base">{{ $t('pages.voting.agreement.condition_2') }}</span>
                                                </li>
                                                <li class="flex items-start text-gray-800">
                                                    <span class="text-green-600 font-bold mr-3 flex-shrink-0 mt-1">✓</span>
                                                    <span class="text-base">{{ $t('pages.voting.agreement.condition_3') }}</span>
                                                </li>
                                                <li class="flex items-start text-gray-800">
                                                    <span class="text-green-600 font-bold mr-3 flex-shrink-0 mt-1">✓</span>
                                                    <span class="text-base">{{ $t('pages.voting.agreement.condition_4') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Large Accessible Checkbox -->
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-8">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 pt-2">
                                                <input
                                                    type="checkbox"
                                                    id="agree_button"
                                                    name="agree_button"
                                                    v-model="form.agree_button"
                                                    value="on"
                                                    class="w-16 h-16 text-blue-600 border-3 border-gray-400 rounded-lg focus:ring-4 focus:ring-blue-400 focus:ring-offset-2 cursor-pointer transition-all"
                                                    :aria-label="$t('pages.voting.agreement.checkbox_aria_label')"
                                                    @change="announceCheckboxStatus"
                                                />
                                            </div>
                                            <div class="flex-grow pt-2">
                                                <label for="agree_button" class="cursor-pointer block">
                                                    <div class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                                        {{ $t('pages.voting.agreement.checkbox_label') }}
                                                    </div>
                                                    <div class="text-lg text-gray-700 leading-relaxed">
                                                        {{ $t('pages.voting.agreement.checkbox_description') }}
                                                    </div>
                                                </label>

                                                <!-- Confirmation When Checked -->
                                                <div v-if="form.agree_button" class="mt-4 p-4 bg-green-50 border-2 border-green-300 rounded-lg flex items-center transition-all">
                                                    <span class="text-green-600 text-2xl mr-3">✓</span>
                                                    <span class="text-green-800 font-semibold text-lg">
                                                        {{ $t('pages.voting.agreement.ready_message') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Error Message -->
                                        <div v-if="errors.agree_button" class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded text-red-700 font-medium" role="alert">
                                            {{ errors.agree_button }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Sticky Submit Button -->
                        <div class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 shadow-2xl z-40">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <div class="md:col-span-1 flex justify-center md:justify-start">
                                        <div class="text-center md:text-left">
                                            <p class="text-sm text-gray-600 font-medium">{{ $t('pages.voting.submit.progress_label') }}</p>
                                            <p class="text-2xl font-bold text-blue-600">{{ votingProgress.completed }}/{{ votingProgress.total }}</p>
                                        </div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div
                                                class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2 rounded-full transition-all duration-300"
                                                :style="{ width: votingProgress.percentage + '%' }"
                                                role="progressbar"
                                                :aria-valuenow="votingProgress.percentage"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <button
                                            type="submit"
                                            @click="submit"
                                            class="w-full py-4 px-6 rounded-lg font-bold text-lg transition-all duration-200 shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2"
                                            :class="submitButtonClasses"
                                            :disabled="!canSubmit"
                                            :aria-label="canSubmit ? $t('pages.voting.submit.button_aria_enabled') : $t('pages.voting.submit.button_aria_disabled')"
                                        >
                                            <div class="flex items-center justify-center gap-2">
                                                <span v-if="loading" class="flex items-center gap-2">
                                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    {{ $t('pages.voting.submit.submitting') }}
                                                </span>
                                                <span v-else-if="!canSubmit" class="flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    {{ submitButtonText }}
                                                </span>
                                                <span v-else class="flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $t('pages.voting.submit.button_text') }}
                                                </span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spacer for sticky button -->
                        <div class="h-32 md:h-28"></div>

                    </main>
                </form>

                <!-- Information Footer Cards -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto" aria-labelledby="info-section">
                    <h2 id="info-section" class="sr-only">{{ $t('pages.voting.footer.section_title') }}</h2>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">🔒</div>
                            <div>
                                <h3 class="font-bold text-blue-900 text-lg mb-2">{{ $t('pages.voting.footer.security.title') }}</h3>
                                <p class="text-blue-800 text-sm">{{ $t('pages.voting.footer.security.description') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-200">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">⏱️</div>
                            <div>
                                <h3 class="font-bold text-green-900 text-lg mb-2">{{ $t('pages.voting.footer.time.title') }}</h3>
                                <p class="text-green-800 text-sm">{{ $t('pages.voting.footer.time.description') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">❓</div>
                            <div>
                                <h3 class="font-bold text-purple-900 text-lg mb-2">{{ $t('pages.voting.footer.help.title') }}</h3>
                                <p class="text-purple-800 text-sm">{{ $t('pages.voting.footer.help.description') }}</p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from '@/Layouts/ElectionLayout'
import CreateVotingform from '@/Pages/Vote/DemoVote/CreateVotingform.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator'
import { useForm } from '@inertiajs/inertia-vue3'
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
// Import translation files for regional posts
import regionDe from '@/locales/pages/Vote/DemoVote/CreateVotingPage/de.json'
import regionEn from '@/locales/pages/Vote/DemoVote/CreateVotingPage/en.json'
import regionNp from '@/locales/pages/Vote/DemoVote/CreateVotingPage/np.json'

export default {
    name: 'CreateVotingPage',

    components: {
        ElectionLayout,
        CreateVotingform,
        WorkflowStepIndicator,
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
        name: {
            type: String,
            required: true
        },
        user_id: {
            type: Number,
            required: true
        },
        user_region: {
            type: String,
            default: null
        },
        election_name: {
            type: String,
            default: 'Demo Election'
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
        const { locale } = useI18n()
        const selectedVotes = ref({})
        const errors = ref({})
        const loading = ref(false)
        const attemptedSubmit = ref(false)  // Track if user tried to submit

        // Translation data for regional posts
        const pageTranslations = {
            de: regionDe,
            en: regionEn,
            np: regionNp,
        }

        const currentPageData = computed(() => {
            return pageTranslations[locale.value] || pageTranslations.en
        })

        const regionalPostsMessages = computed(() => {
            return currentPageData.value?.createVotingPage?.regionalPosts || {}
        })

        const form = useForm({
            user_id: props.user_id,
            votes: {},
            agree_button: false,
            national_selected_candidates: [],
            regional_selected_candidates: [],
        });

        function submit() {
            attemptedSubmit.value = true  // Mark that user tried to submit
            errors.value = {}

            // Validate at least one vote (from national or regional)
            const hasVotes = form.national_selected_candidates.some(s => s?.candidates?.length > 0 || s?.no_vote) ||
                           form.regional_selected_candidates.some(s => s?.candidates?.length > 0 || s?.no_vote);

            if (!hasVotes) {
                errors.value.votes = 'Please select at least one candidate'
                return
            }

            // Validate agreement
            if (!form.agree_button) {
                errors.value.agree_button = 'You must agree to the terms before submitting your vote.'
                return
            }

            loading.value = true

            // Collect all votes from both national and regional selections
            const allVotes = [];
            form.national_selected_candidates.forEach(selection => {
                if (selection?.candidates?.length > 0) {
                    allVotes.push(...selection.candidates);
                }
            });
            form.regional_selected_candidates.forEach(selection => {
                if (selection?.candidates?.length > 0) {
                    allVotes.push(...selection.candidates);
                }
            });

            const voteForm = useForm({
                votes: allVotes,
            })

            const routeName = props.useSlugPath ? 'slug.demo-vote.submit' : 'demo-vote.submit'
            const params = props.useSlugPath ? { vslug: props.slug } : {}

            voteForm.post(route(routeName, params), {
                onError: (formErrors) => {
                    // Capture all form errors from backend
                    if (formErrors) {
                        errors.value = { ...formErrors }
                    }
                    loading.value = false

                    // Scroll to first error post for user convenience
                    scrollToFirstError()
                },
            })
        }

        function handleCandidateSelection(type, postIndex, selectionData) {
            if (type === 'national') {
                form.national_selected_candidates[postIndex] = selectionData
            } else if (type === 'regional') {
                form.regional_selected_candidates[postIndex] = selectionData
            }
        }

        function validateVoteData() {
            const issues = []

            // Check that at least one vote is selected
            const hasVotes = form.national_selected_candidates.some(s => s?.candidates?.length > 0 || s?.no_vote) ||
                           form.regional_selected_candidates.some(s => s?.candidates?.length > 0 || s?.no_vote);

            if (!hasVotes) {
                issues.push('Please select at least one candidate')
            }

            // Check agreement checkbox
            if (!form.agree_button) {
                issues.push('You must agree to the terms before submitting your vote.')
            }

            return {
                isValid: issues.length === 0,
                issues: issues
            };
        }

        return {
            form,
            submit,
            selectedVotes,
            errors,
            loading,
            handleCandidateSelection,
            validateVoteData,
            attemptedSubmit,
            regionalPostsMessages,
            locale
        };
    },

    computed: {
        votingProgress() {
            const nationalCompleted = this.form.national_selected_candidates.filter(selection =>
                selection && (selection.candidates?.length > 0 || selection.no_vote)
            ).length;

            const regionalCompleted = this.form.regional_selected_candidates.filter(selection =>
                selection && (selection.candidates?.length > 0 || selection.no_vote)
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
            return this.validationSummary.hasIssues === false && !this.loading;
        },

        submitButtonClasses() {
            if (this.loading) {
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

    methods: {
        announceCheckboxStatus() {
            if (this.form.agree_button) {
                this.$nextTick(() => {
                    const announcement = document.createElement('div');
                    announcement.setAttribute('role', 'status');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = this.$t('pages.voting.aria_labels.checkbox_checked');
                    document.body.appendChild(announcement);
                    setTimeout(() => {
                        if (document.body.contains(announcement)) {
                            document.body.removeChild(announcement);
                        }
                    }, 2000);
                });
            }
        },

        handleBeforeUnload(event) {
            if (this.votingProgress.completed > 0 && !this.loading) {
                event.preventDefault();
                event.returnValue = 'You have unsaved voting selections. Are you sure you want to leave?';
                return event.returnValue;
            }
        }
    },

    mounted() {
        window.addEventListener('beforeunload', this.handleBeforeUnload);
    },

    beforeUnmount() {
        window.removeEventListener('beforeunload', this.handleBeforeUnload);
    }
}
</script>

<style scoped>
/* Skip Link - Accessibility */
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #2563eb;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    z-index: 100;
    border-radius: 0 0 4px 0;
    font-weight: 600;
}

.skip-link:focus {
    top: 0;
}

/* Screen Reader Only Text */
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

/* Focus Styles for Accessibility */
a:focus-visible,
button:focus-visible,
[role="button"]:focus-visible,
input:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease-in-out;
}

.transition-shadow {
    transition: box-shadow 0.3s ease-in-out;
}

/* Enhanced button hover effects */
button:hover:not(:disabled) {
    transform: translateY(-1px);
}

button:active:not(:disabled) {
    transform: translateY(0);
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
