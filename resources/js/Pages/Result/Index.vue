<template>
  <nrna-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Election Results</h1>
      
      <div class="space-y-8">
        <div 
          v-for="post in posts" 
          :key="post.post_id"
          class="bg-white rounded-lg shadow-md p-6"
        >
          <candidate
            :post="post"
            :final_result="getPostResults(post.post_id)"
          />
        </div>
      </div>
    </div>
  </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/ElectionLayout";
import Candidate from "@/Pages/Result/Candidate";

export default {
  components: { NrnaLayout, Candidate },
  props: {
    final_result: Object,
    posts: Array,
  },
  methods: {
    getPostResults(postId) {
      return this.final_result?.posts?.find(p => p.post_id === postId) || {};
    }
  }
};
</script>