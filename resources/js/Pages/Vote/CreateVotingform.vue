<template>
    <div id="vote_window" 
         class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
        
        <div class="flex flex-col text-xl font-bold text-gray-900 mx-auto text-center justify-center">
            <label>
                Please choose 
                <span class="text-indigo-600">{{ post.required_number }}</span> 
                candidate(s) as the 
                <span class="text-gray-900 font-bold">{{ post.name }}</span>.
            </label> 
            <label class="p-2">
                कृपया 
                <span class="text-indigo-600">{{ post.required_number }}</span> 
                जना लाई  
                <span class="text-gray-900 font-bold">{{ post.nepali_name || post.name }}</span> 
                चुन्नुहोस्।
            </label>   
        </div>

        <!-- No Vote Option -->
        <div class="flex flex-col items-center mx-auto my-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
            <div class="flex flex-col text-center mb-3">
                <label class="text-lg font-semibold text-gray-800">No Vote Option</label>
                <label class="text-sm text-gray-600">मतदान नगर्ने विकल्प</label>
            </div>
            <div class="flex items-center">
                <input 
                    type="checkbox"
                    :id="`no_vote_${post.post_id}`"
                    name="no_vote_option"
                    v-model="noVoteSelected"
                    @change="handleNoVoteChange"
                    class="h-5 w-5 text-red-600 border-2 border-red-400 rounded focus:ring-red-500"
                />
                <label :for="`no_vote_${post.post_id}`" class="ml-2 text-sm font-medium text-gray-700">
                    I choose not to vote for this position / यस पदका लागि मतदान नगर्ने
                </label>
            </div>
        </div>
                   
        <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
            <div v-for="(candidate, candiIndex) in candidatesWithState" 
                 :key="candidate.candidacy_id"  
                 class="flex flex-col justify-center p-4 mb-2 text-center border border-gray-100 rounded"
                 :class="{ 'opacity-50': noVoteSelected }"> 
                
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
                        class="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model="selected"
                        @change="updateBoxes()"
                        :disabled="candidate.disabled || noVoteSelected"
                    />
                </div> 
            </div>
        </div>
        
        <!-- Selection summary -->
        <div class="mb-4 p-2 text-center mx-auto">
            <div v-if="noVoteSelected" class="text-red-600 font-semibold">
                You have chosen not to vote for {{ post.name }}
                <br>
                <span class="text-sm">तपाईंले {{ post.nepali_name || post.name }} का लागि मतदान नगर्ने रोज्नुभएको छ</span>
            </div>
            <div v-else-if="selected.length" class="text-green-600"> 
                You have selected 
                <span class="font-bold text-indigo-600"> 
                    {{ getSelectedNames() }}
                </span> 
                as <span class="font-bold text-lg text-gray-900">{{ post.name }}</span> of NRNA!
            </div>
            <div v-else class="text-gray-500">
                No selection made for {{ post.name }}
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
                        post_id: candidate.post_id
                    }))
                };
            }
            
            this.$emit('add_selected_candidates', selectionData);
        },
        
        handleNoVoteChange() {
            if (this.noVoteSelected) {
                // Clear all candidate selections when no vote is selected
                this.selected = [];
                // Disable all candidate checkboxes
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
</style>