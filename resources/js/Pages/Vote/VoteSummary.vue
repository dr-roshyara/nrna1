<template>
    <div class="bg-neutral-50 border-2 border-neutral-200 rounded-lg p-6 my-6">
        <h3 class="text-xl font-bold text-neutral-800 mb-4 text-center">
            {{ $t('pages.voting.summary.title') }}
        </h3>
        
        <!-- National Posts Summary -->
        <div v-if="nationalSelections && nationalSelections.length > 0" class="mb-6">
            <h4 class="text-lg font-semibold text-primary-700 mb-3">{{ $t('pages.voting.national_posts.section_title') }}</h4>
            <div v-for="(selection, index) in nationalSelections" :key="`national-${index}`" class="mb-3">
                <div v-if="selection" class="bg-white rounded-sm p-3 border">
                    <div class="font-medium text-neutral-800">{{ selection.post_name }}</div>
                    <div v-if="selection.no_vote" class="text-danger-600 font-medium">
                        ✗ {{ $t('pages.voting.summary.no_vote_selected') }}
                    </div>
                    <div v-else-if="selection.candidates && selection.candidates.length > 0" class="text-green-600">
                        <div class="font-medium">✓ {{ $t('pages.voting.summary.selected_candidates') }}:</div>
                        <ul class="list-disc list-inside ml-4">
                            <li v-for="candidate in selection.candidates" :key="candidate.candidacy_id">
                                {{ candidate.candidacy_name || candidate.user_name || candidate.name }}
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-yellow-600">
                        ⚠ {{ $t('pages.voting.summary.no_selection_made') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Regional Posts Summary -->
        <div v-if="regionalSelections && regionalSelections.length > 0" class="mb-6">
            <h4 class="text-lg font-semibold text-purple-700 mb-3">{{ $t('pages.voting.regional_posts.section_title', { region: 'Region' }) }}</h4>
            <div v-for="(selection, index) in regionalSelections" :key="`regional-${index}`" class="mb-3">
                <div v-if="selection" class="bg-white rounded-sm p-3 border">
                    <div class="font-medium text-neutral-800">{{ selection.post_name }}</div>
                    <div v-if="selection.no_vote" class="text-danger-600 font-medium">
                        ✗ {{ $t('pages.voting.summary.no_vote_selected') }}
                    </div>
                    <div v-else-if="selection.candidates && selection.candidates.length > 0" class="text-green-600">
                        <div class="font-medium">✓ {{ $t('pages.voting.summary.selected_candidates') }}:</div>
                        <ul class="list-disc list-inside ml-4">
                            <li v-for="candidate in selection.candidates" :key="candidate.candidacy_id">
                                {{ candidate.candidacy_name || candidate.user_name || candidate.name }}
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-yellow-600">
                        ⚠ {{ $t('pages.voting.summary.no_selection_made') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="bg-primary-50 rounded-sm p-4 border border-primary-200">
            <div class="text-sm text-neutral-600">
                <strong>{{ $t('pages.voting.summary.summary_stats') }}:</strong>
                {{ totalSelections }} {{ $t('pages.voting.summary.positions_selected') }},
                {{ noVoteCount }} {{ $t('pages.voting.summary.no_vote_selections') }},
                {{ incompleteCount }} {{ $t('pages.voting.summary.incomplete_selections') }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'VoteSummary',
    
    props: {
        nationalSelections: {
            type: Array,
            default: () => []
        },
        regionalSelections: {
            type: Array,
            default: () => []
        }
    },
    
    computed: {
        totalSelections() {
            const national = this.nationalSelections.filter(selection => 
                selection && (selection.no_vote || (selection.candidates && selection.candidates.length > 0))
            ).length;
            
            const regional = this.regionalSelections.filter(selection => 
                selection && (selection.no_vote || (selection.candidates && selection.candidates.length > 0))
            ).length;
            
            return national + regional;
        },
        
        noVoteCount() {
            const national = this.nationalSelections.filter(selection => 
                selection && selection.no_vote
            ).length;
            
            const regional = this.regionalSelections.filter(selection => 
                selection && selection.no_vote
            ).length;
            
            return national + regional;
        },
        
        incompleteCount() {
            const totalPossible = this.nationalSelections.length + this.regionalSelections.length;
            return totalPossible - this.totalSelections;
        }
    }
}
</script>

<style scoped>
.list-disc {
    list-style-type: disc;
}
</style>
