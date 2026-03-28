<template>
    <div class="rounded-lg p-4 mx-6 mb-2 transition-all duration-200" :class="containerClass">
        <div class="flex items-center justify-between gap-2 mb-2">
            <div class="flex items-center gap-2">
                <span aria-hidden="true" class="text-base">{{ status.icon }}</span>
                <span class="font-sans font-semibold text-sm">{{ status.message }}</span>
            </div>
            <span class="font-mono text-sm font-bold">{{ selectedCandidates.length }}/{{ post.required_number || 1 }}</span>
        </div>
        <!-- Progress bar -->
        <div class="w-full bg-white/50 rounded-full h-1.5 overflow-hidden"
             role="progressbar"
             :aria-valuenow="progressPercent"
             aria-valuemin="0"
             aria-valuemax="100"
             :aria-label="`${selectedCandidates.length} of ${post.required_number || 1} candidates selected`">
            <div class="h-full rounded-full transition-all duration-500"
                 :class="progressBarClass"
                 :style="{ width: progressPercent + '%' }"></div>
        </div>
        <!-- Selected names -->
        <p v-if="selectedNames"
           class="text-xs mt-2 opacity-80 font-sans truncate"
           :title="selectedNames">
            {{ selectedNames }}
        </p>
    </div>
</template>

<script>
export default {
    name: 'SelectionStatus',
    props: {
        post:               { type: Object,  required: true },
        selectedCandidates: { type: Array,   default: () => [] },
        noVoteSelected:     { type: Boolean, default: false },
    },
    computed: {
        status() {
            if (this.noVoteSelected)
                return { type: 'no-vote', icon: '⏭️', message: 'Position skipped' }
            const n   = this.selectedCandidates.length
            const req = this.post.required_number || 1
            if (n === 0)   return { type: 'empty',   icon: '⚠️', message: 'No candidate selected yet' }
            if (n === req) return { type: 'valid',   icon: '✓',  message: `${n} of ${req} selected — complete` }
            return           { type: 'partial', icon: 'ℹ️', message: `${n} of ${req} selected` }
        },
        containerClass() {
            const map = {
                'valid':   'bg-success-50 border border-success-200 text-success-800',
                'partial': 'bg-warning-50 border border-warning-200 text-warning-800',
                'empty':   'bg-danger-50 border border-danger-200 text-danger-800',
                'no-vote': 'bg-neutral-50 border border-neutral-200 text-neutral-600',
            }
            return map[this.status.type] || map['empty']
        },
        progressBarClass() {
            const map = {
                'valid':   'bg-success-600',
                'partial': 'bg-yellow-500',
                'empty':   'bg-danger-400',
                'no-vote': 'bg-neutral-400',
            }
            return map[this.status.type] || 'bg-primary-600'
        },
        progressPercent() {
            if (this.noVoteSelected) return 100
            const req = this.post.required_number || 1
            if (req === 0) return 0
            return Math.min(Math.round((this.selectedCandidates.length / req) * 100), 100)
        },
        selectedNames() {
            if (!this.selectedCandidates.length) return ''
            return this.selectedCandidates
                .map(c => c.candidacy_name || c.user_name || c.name || 'Unknown')
                .join(', ')
        },
    },
}
</script>
