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
  </div>
</template>

<script>
import BarChart from "@/Pages/Result/BarChart";
import { computed } from 'vue';

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

    return { chartData };
  }
};
</script>