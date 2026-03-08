<template>
    <div class="min-h-screen bg-gray-50 py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Select a Demo Election</h1>
                <p class="text-gray-600">Choose a demo election to test the voting system</p>
            </div>

            <!-- Grouped Elections -->
            <div class="space-y-8">
                <div v-for="group in groupedByOrganisation" :key="group.name" class="space-y-4">
                    <!-- Organisation Header -->
                    <div class="border-b-2 border-blue-500 pb-2">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ group.name }}</h2>
                    </div>

                    <!-- Elections Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="election in elections.filter(e => 
                                (e.organisation_name === group.name || 
                                 (group.name === 'PublicDigit' && e.organisation_name === 'PublicDigit'))
                            )"
                            :key="election.id"
                            class="border border-gray-200 rounded-lg p-6 hover:shadow-lg hover:border-blue-500 transition cursor-pointer"
                            @click="selectElection(election.id)">
                            
                            <!-- Election Name -->
                            <h3 class="font-bold text-lg text-gray-900 mb-2">{{ election.name }}</h3>
                            
                            <!-- Description -->
                            <p v-if="election.description" class="text-gray-600 text-sm mb-3">
                                {{ election.description }}
                            </p>

                            <!-- Election Details -->
                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 mb-4">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-1">Posts:</span>
                                    <span class="text-blue-600">{{ election.posts_count }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold mr-1">Candidates:</span>
                                    <span class="text-blue-600">{{ election.candidates_count }}</span>
                                </div>
                                <div class="col-span-2 text-xs text-gray-500">
                                    Valid until: {{ election.end_date }}
                                </div>
                            </div>

                            <!-- Start Button -->
                            <button
                                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-semibold">
                                Start Demo Election →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Elections Message -->
            <div v-if="elections.length === 0" class="text-center py-12">
                <p class="text-gray-600 text-lg">No demo elections available</p>
                <Link href="/dashboard" class="text-blue-600 hover:underline mt-2">
                    Return to Dashboard
                </Link>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <Link href="/dashboard" class="text-blue-600 hover:underline">
                    ← Back to Dashboard
                </Link>
            </div>
        </div>
    </div>

    <!-- Hidden Form for POST submission -->
    <form :id="formId" method="POST" :action="'/election/demo/select'" style="display: none">
        <input type="hidden" name="_token" :value="csrf_token" />
        <input type="hidden" name="election_id" :value="selectedElectionId" />
    </form>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    elections: Array,
    groupedByOrganisation: Array,
    user_orgs: Array,
    csrf_token: String,
})

const selectedElectionId = ref(null)
const formId = ref('demo-election-form')

const selectElection = (electionId) => {
    selectedElectionId.value = electionId
    
    // Submit form via Inertia
    router.post('/election/demo/select', {
        election_id: electionId,
    }, {
        preserveState: false,
        onSuccess: () => {
            // Redirect handled by controller
        },
        onError: (errors) => {
            console.error('Failed to select election:', errors)
        }
    })
}
</script>

<style scoped>
/* Smooth transitions for hover effects */
.transition {
    transition: all 0.3s ease;
}
</style>
