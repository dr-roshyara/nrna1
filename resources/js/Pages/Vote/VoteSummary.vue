<template>
    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 my-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
            Vote Summary / मतदानको सारांश
        </h3>
        
        <!-- National Posts Summary -->
        <div v-if="nationalSelections && nationalSelections.length > 0" class="mb-6">
            <h4 class="text-lg font-semibold text-blue-700 mb-3">National Posts</h4>
            <div v-for="(selection, index) in nationalSelections" :key="`national-${index}`" class="mb-3">
                <div v-if="selection" class="bg-white rounded p-3 border">
                    <div class="font-medium text-gray-800">{{ selection.post_name }}</div>
                    <div v-if="selection.no_vote" class="text-red-600 font-medium">
                        ✗ No Vote Selected / मतदान नगरिएको
                    </div>
                    <div v-else-if="selection.candidates && selection.candidates.length > 0" class="text-green-600">
                        <div class="font-medium">✓ Selected Candidates:</div>
                        <ul class="list-disc list-inside ml-4">
                            <li v-for="candidate in selection.candidates" :key="candidate.candidacy_id">
                                {{ candidate.name }}
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-yellow-600">
                        ⚠ No selection made / कुनै छनौट गरिएको छैन
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Regional Posts Summary -->
        <div v-if="regionalSelections && regionalSelections.length > 0" class="mb-6">
            <h4 class="text-lg font-semibold text-purple-700 mb-3">Regional Posts</h4>
            <div v-for="(selection, index) in regionalSelections" :key="`regional-${index}`" class="mb-3">
                <div v-if="selection" class="bg-white rounded p-3 border">
                    <div class="font-medium text-gray-800">{{ selection.post_name }}</div>
                    <div v-if="selection.no_vote" class="text-red-600 font-medium">
                        ✗ No Vote Selected / मतदान नगरिएको
                    </div>
                    <div v-else-if="selection.candidates && selection.candidates.length > 0" class="text-green-600">
                        <div class="font-medium">✓ Selected Candidates:</div>
                        <ul class="list-disc list-inside ml-4">
                            <li v-for="candidate in selection.candidates" :key="candidate.candidacy_id">
                                {{ candidate.name }}
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-yellow-600">
                        ⚠ No selection made / कुनै छनौट गरिएको छैन
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="bg-blue-50 rounded p-4 border border-blue-200">
            <div class="text-sm text-gray-600">
                <strong>Summary:</strong>
                {{ totalSelections }} positions selected, 
                {{ noVoteCount }} no-vote selections, 
                {{ incompleteCount }} incomplete selections
            </div>
            <div class="text-xs text-gray-500 mt-1">
                सारांश: {{ totalSelections }} पद चयन गरिएको, 
                {{ noVoteCount }} मतदान नगरिएको, 
                {{ incompleteCount }} अपूर्ण छनौट
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