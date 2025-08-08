<template>
  <div class="space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">
      {{ post.name }} - Election Results
    </h2>
    
    <div class="text-lg font-medium text-gray-700">
      Total Votes: {{ final_result.total_votes_for_post || 0 }}
    </div>
    
    <div class="mt-6">
      <h3 class="text-xl font-medium mb-4 text-gray-800">
        Election Results - {{ post.name }}
      </h3>
      <bar-chart 
        :entries="chartData"
        :post-name="post.name"
      />
    </div>

    <button 
      @click="verifyResults"
      class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
    >
      Verify Results
    </button>

    <div v-if="verification" class="mt-6 p-4 border rounded">
      <h4 class="font-bold mb-2">Verification Report</h4>
      <div v-if="verification.match" class="text-green-600">
        Results verified successfully! All counts match.
      </div>
      <div v-else-if="verification.error" class="text-red-600">
        Error: {{ verification.error }}
      </div>
      <div v-else>
        <div class="text-red-600 mb-2">Discrepancies found:</div>
        <table class="w-full border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2">Candidate</th>
              <th class="p-2">Official Count</th>
              <th class="p-2">Raw Count</th>
              <th class="p-2">Difference</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(diff, candId) in verification.discrepancies" :key="candId">
              <td class="p-2 border">{{ getCandidateName(candId) }}</td>
              <td class="p-2 border">{{ diff.official }}</td>
              <td class="p-2 border">{{ diff.raw }}</td>
              <td class="p-2 border">{{ diff.official - diff.raw }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import BarChart from "@/Pages/Result/BarChart";
import { computed, ref } from 'vue';
import axios from 'axios';

export default {
  components: { BarChart },
  props: {
    post: Object,
    final_result: Object
  },
  setup(props) {
    const chartData = computed(() => {
      if (!props.final_result?.candidates) return [];
      
      return props.final_result.candidates.map(candidate => ({
        name: candidate.name,
        value: candidate.vote_percent / 100, // Convert to decimal
        vote_count: candidate.vote_count
      })).sort((a, b) => b.value - a.value);
    });

    const verification = ref(null);
    
    const getCandidateName = (candId) => {
      const candidate = props.post.candidates?.find(c => c.candidacy_id === candId);
      return candidate?.user?.name || candidate?.name || candId;
    };
    
    const verifyResults = async () => {
      try {
        const response = await axios.get(`/api/verify-results/${props.post.post_id}`);
        verification.value = response.data;
      } catch (error) {
        console.error("Verification failed:", error);
        verification.value = {
          match: false,
          error: error.response?.data?.message || "Failed to verify results"
        };
      }
    };
    
    return { 
      chartData,
      verification,
      verifyResults,
      getCandidateName
    };
  }
};
</script>

<style scoped>
.border {
  border: 1px solid #e2e8f0;
}
.rounded {
  border-radius: 0.25rem;
}
</style>