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
                   
        <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
            <div v-for="(candidate, candiIndex) in candidatesWithState" 
                 :key="candidate.candidacy_id"  
                 class="flex flex-col justify-center p-4 mb-2 text-center border border-gray-100 rounded"> 
                
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
                        :disabled="candidate.disabled"
                    />
                </div> 
            </div>
        </div>
        
        <!-- Selection summary -->
        <div class="mb-4 p-2 text-center mx-auto" v-if="selected.length"> 
            You have selected 
            <span class="font-bold text-indigo-600"> 
                {{ getSelectedNames() }}
            </span> 
            as <span class="font-bold text-lg text-gray-900">{{ post.name }}</span> of NRNA!
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
            candidatesWithState: []
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
            const selectedCandidates = this.candidatesWithState.filter(candidate => 
                this.selected.includes(candidate.candidacy_id)
            );
            
            const selectionData = {
                post_id: this.post.post_id,
                post_name: this.post.name,
                required_number: this.post.required_number,
                candidates: selectedCandidates.map(candidate => ({
                    candidacy_id: candidate.candidacy_id,
                    user_id: candidate.user?.user_id || candidate.user?.id,
                    name: candidate.user?.name,
                    post_id: candidate.post_id
                }))
            };
            
            this.$emit('add_selected_candidates', selectionData);
        },
        
        updateBoxes() {
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