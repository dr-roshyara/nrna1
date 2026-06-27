<template>
    <div class="post-section-container" :id="`post-${post.id}`" :style="{ animationDelay: postIndex * 100 + 'ms' }">
        <!-- Post Header -->
        <div class="post-header" :class="headerGradient">
            <div class="post-header-content">
                <div class="post-title-group">
                    <h2 class="post-title">{{ post.name }}</h2>
                    <p v-if="post.nepali_name" class="post-title-alt">{{ post.nepali_name }}</p>
                </div>
                <div class="post-meta">
                    <span class="post-count">
                        Select {{ post.required_number || 1 }}
                    </span>
                    <span v-if="(post.required_number || 1) > 1" class="post-count-label">candidates</span>
                    <span v-else class="post-count-label">candidate</span>
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        <transition name="slide-down">
            <div v-if="hasError && errorMessage" class="error-alert" role="alert">
                <span class="error-icon">⚠️</span>
                <p class="error-text">{{ errorMessage }}</p>
            </div>
        </transition>

        <!-- Candidates Gallery -->
        <div class="candidates-container">
            <div class="candidates-grid">
                <div v-for="(candidate, idx) in sortedCandidates"
                     :key="candidate.id"
                     :ref="el => { if (el) cardRefs.push(el) }"
                     :data-candidate-id="candidate.id"
                     :data-post-id="post.id"
                     class="candidate-card"
                     :class="{ 'is-selected': isSelected(candidate) }"
                     tabindex="0"
                     role="checkbox"
                     :aria-checked="isSelected(candidate)"
                     :aria-label="`${candidate.candidacy_name || candidate.user_name} for ${post.name}`"
                     @click="$emit('toggle-candidate', candidate)"
                     @keydown.enter.prevent="$emit('toggle-candidate', candidate)"
                     @keydown.space.prevent="$emit('toggle-candidate', candidate)"
                     :style="{ animationDelay: idx * 50 + 'ms' }">

                    <!-- Selection Badge -->
                    <transition name="scale-pop">
                        <span v-if="isSelected(candidate)" class="selection-badge">
                            <span class="badge-number">#{{ selectionOrder(candidate) }}</span>
                            <span class="badge-checkmark">✓</span>
                        </span>
                    </transition>

                    <!-- Photo Container -->
                    <div class="candidate-photo-wrapper">
                        <div class="candidate-photo">
                            <img v-if="getImageUrl(candidate.image_path_1)"
                                 :src="getImageUrl(candidate.image_path_1)"
                                 :alt="candidate.candidacy_name || candidate.user_name"
                                 class="candidate-image"
                                 @error="e => e.target.style.display = 'none'" />
                            <div v-else class="candidate-image-placeholder">👤</div>
                        </div>
                        <div class="photo-overlay"></div>
                    </div>

                    <!-- Candidate Info -->
                    <div class="candidate-info">
                        <h3 class="candidate-name">{{ candidate.candidacy_name || candidate.user_name }}</h3>
                        <p v-if="candidate.position_order" class="candidate-position">#{{ candidate.position_order }}</p>

                        <!-- Checkbox -->
                        <div class="candidate-checkbox-wrapper">
                            <div class="checkbox-box" :class="{ 'checkbox-checked': isSelected(candidate) }">
                                <svg v-if="isSelected(candidate)" class="checkbox-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="checkbox-label">
                                {{ isSelected(candidate) ? $t('pages.voting.candidate_selection.checkbox_selected') : $t('pages.voting.candidate_selection.checkbox_select') }}
                            </span>
                        </div>
                    </div>

                    <!-- Hidden checkbox for form semantics -->
                    <input type="checkbox"
                           :checked="isSelected(candidate)"
                           class="sr-only"
                           tabindex="-1"
                           aria-hidden="true" />
                </div>
            </div>
        </div>

        <!-- Selection Status -->
        <SelectionStatus
            :post="post"
            :selected-candidates="selectedCandidatesObjects"
            :no-vote-selected="noVoteSelected"
        />

        <!-- Skip Position (No Vote) -->
        <div v-if="noVoteEnabled" class="no-vote-section">
            <label class="no-vote-label">
                <input type="checkbox"
                       :checked="noVoteSelected"
                       @change="$emit('toggle-no-vote')"
                       class="no-vote-checkbox" />
                <span class="no-vote-text">{{ $t('pages.voting.no_vote.button_text', '⏭️ Abstain') }}</span>
            </label>
        </div>
    </div>
</template>

<script>
import SelectionStatus from './SelectionStatus.vue'

export default {
    name: 'PostSection',
    components: { SelectionStatus },

    props: {
        post:           { type: Object,  required: true },
        selectedCandidates: { type: Array,   default: () => [] },
        noVoteSelected: { type: Boolean, default: false },
        noVoteEnabled:  { type: Boolean, default: true },
        noVoteLabel:    { type: String,  default: 'Abstain' },
        hasError:       { type: Boolean, default: false },
        errorMessage:   { type: String,  default: '' },
        postIndex:      { type: Number,  default: 0 },
    },

    emits: ['toggle-candidate', 'toggle-no-vote'],

    data() {
        return {
            cardRefs: [],
        }
    },

    computed: {
        sortedCandidates() {
            return [...(this.post.candidates || [])]
                .sort((a, b) => (a.position_order || 0) - (b.position_order || 0))
        },
        headerGradient() {
            if (this.noVoteSelected) return 'header-neutral'
            const n   = this.selectedCandidates.length
            const req = this.post.required_number || 1
            if (n === req) return 'header-success'
            if (n > 0)     return 'header-active'
            return 'header-default'
        },
        selectedCandidatesObjects() {
            return this.selectedCandidates
                .map(id => (this.post.candidates || []).find(c => c.id === id))
                .filter(Boolean)
        },
    },

    methods: {
        isSelected(candidate) {
            return this.selectedCandidates.includes(candidate.id)
        },
        selectionOrder(candidate) {
            return this.selectedCandidates.indexOf(candidate.id) + 1
        },
        getImageUrl(path) {
            if (!path) return null
            if (path.startsWith('http') || path.startsWith('/storage')) return path
            return `/storage/${path}`
        },
    },
}
</script>

<style scoped>
/* ─────────────────────────────────────────────────────────────────────── */
/* ANIMATIONS */
/* ─────────────────────────────────────────────────────────────────────── */

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scalePopIn {
    from {
        opacity: 0;
        transform: scale(0.7) rotate(-180deg);
    }
    to {
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }
}

@keyframes photoZoom {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08); }
}

.slide-down-enter-active, .slide-down-leave-active {
    transition: all 0.3s ease;
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-12px);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-12px);
}

.scale-pop-enter-active {
    animation: scalePopIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ─────────────────────────────────────────────────────────────────────── */
/* CONTAINER & LAYOUT */
/* ─────────────────────────────────────────────────────────────────────── */

.post-section-container {
    animation: fadeInUp 0.5s ease-out both;
    margin-bottom: 2rem;
}

.post-header {
    padding: 2rem 2.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.post-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
    background-size: 40px 40px;
    pointer-events: none;
}

.post-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
}

.post-title-group {
    flex: 1;
}

.post-title {
    font-size: 1.875rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    margin: 0;
    font-family: 'Georgia', 'Garamond', serif;
    line-height: 1.2;
}

.post-title-alt {
    font-size: 0.875rem;
    opacity: 0.85;
    margin-top: 0.375rem;
    font-weight: 400;
}

.post-meta {
    display: flex;
    gap: 0.5rem;
    align-items: baseline;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1.25rem;
    border-radius: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.post-count {
    font-size: 1.125rem;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

.post-count-label {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Header Color Variants */
.header-default {
    background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
}

.header-active {
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
}

.header-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.header-neutral {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

/* ─────────────────────────────────────────────────────────────────────── */
/* ERROR ALERT */
/* ─────────────────────────────────────────────────────────────────────── */

.error-alert {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 4px solid #dc2626;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1rem 2rem 1.5rem;
    border-radius: 0.5rem;
    animation: slideInDown 0.3s ease;
}

.error-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.error-text {
    margin: 0;
    color: #7f1d1d;
    font-weight: 500;
    font-size: 0.95rem;
}

/* ─────────────────────────────────────────────────────────────────────── */
/* CANDIDATES GALLERY */
/* ─────────────────────────────────────────────────────────────────────── */

.candidates-container {
    padding: 3rem 2.5rem;
    background: #fafafa;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
}

.candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 2rem;
    max-width: 100%;
}

@media (min-width: 1024px) {
    .candidates-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1280px) {
    .candidates-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* ─────────────────────────────────────────────────────────────────────── */
/* CANDIDATE CARD */
/* ─────────────────────────────────────────────────────────────────────── */

.candidate-card {
    position: relative;
    cursor: pointer;
    animation: fadeInUp 0.5s ease-out both;
    outline: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.candidate-card:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.candidate-card:hover:not(.is-selected) .photo-overlay {
    opacity: 0.15;
}

.candidate-card:hover:not(.is-selected) .candidate-photo {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.candidate-card.is-selected {
    animation: fadeInUp 0.5s ease-out both;
}

/* ─────────────────────────────────────────────────────────────────────── */
/* SELECTION BADGE */
/* ─────────────────────────────────────────────────────────────────────── */

.selection-badge {
    position: absolute;
    top: -12px;
    right: -12px;
    z-index: 20;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.875rem;
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4), 0 0 0 4px rgba(255, 255, 255, 1);
    flex-direction: column;
    gap: 0.125rem;
}

.badge-number {
    font-size: 0.75rem;
    opacity: 0.9;
}

.badge-checkmark {
    font-size: 1.125rem;
    font-weight: 900;
    letter-spacing: 0.1em;
}

/* ─────────────────────────────────────────────────────────────────────── */
/* PHOTO SECTION */
/* ─────────────────────────────────────────────────────────────────────── */

.candidate-photo-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 140%; /* Portrait aspect ratio 5:7 */
    background: #f3f4f6;
    border-radius: 0.75rem;
    overflow: hidden;
    margin-bottom: 1.25rem;
}

.candidate-photo {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #f3f4f6;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border-radius: 0.75rem;
}

.candidate-card.is-selected .candidate-photo {
    box-shadow: inset 0 0 0 2px #3b82f6;
}

.candidate-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.4s ease;
}

.candidate-card:hover:not(.is-selected) .candidate-image {
    transform: scale(1.08);
}

.candidate-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #d1d5db;
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.candidate-card.is-selected .photo-overlay {
    opacity: 0.2;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.08) 100%);
}

/* ─────────────────────────────────────────────────────────────────────── */
/* CANDIDATE INFO */
/* ─────────────────────────────────────────────────────────────────────── */

.candidate-info {
    padding: 0 0.5rem;
}

.candidate-name {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
    color: #1f2937;
    line-height: 1.3;
    letter-spacing: -0.3px;
    font-family: 'Georgia', serif;
}

.candidate-card.is-selected .candidate-name {
    color: #3b82f6;
}

.candidate-position {
    font-size: 0.75rem;
    color: #9ca3af;
    margin: 0;
    font-weight: 500;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

/* ─────────────────────────────────────────────────────────────────────── */
/* CANDIDATE CHECKBOX */
/* ─────────────────────────────────────────────────────────────────────── */

.candidate-checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-top: 1.25rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e5e7eb;
}

.checkbox-box {
    width: 56px;
    height: 56px;
    border: 4px solid #374151;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    flex-shrink: 0;
    background: white;
}

.checkbox-box:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.checkbox-box.checkbox-checked {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-color: #1e40af;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
}

.checkbox-icon {
    width: 32px;
    height: 32px;
    color: white;
    stroke-linecap: round;
    stroke-linejoin: round;
    animation: scalePopIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.checkbox-label {
    font-size: 1.125rem;
    font-weight: 700;
    color: #374151;
    transition: color 0.3s ease;
    cursor: pointer;
    user-select: none;
    letter-spacing: 0.3px;
}

.candidate-card.is-selected .checkbox-label {
    color: #3b82f6;
    font-weight: 800;
}

/* ─────────────────────────────────────────────────────────────────────── */
/* NO-VOTE SECTION */
/* ─────────────────────────────────────────────────────────────────────── */

.no-vote-section {
    padding: 1.5rem 2.5rem;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.no-vote-label {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.875rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    user-select: none;
}

.no-vote-label:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.no-vote-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: #3b82f6;
}

.no-vote-text {
    font-size: 0.95rem;
    font-weight: 500;
    color: #4b5563;
    transition: color 0.2s ease;
}

.no-vote-label:hover .no-vote-text {
    color: #1f2937;
}
</style>
