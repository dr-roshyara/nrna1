<template>
  <div class="w-full">
    <svg :viewBox="`0 0 ${width} ${height}`">
      <!-- Bars -->
      <g v-for="(entry, index) in entries" :key="index">
        <rect
          class="bar"
          :x="margin.left"
          :y="y(index)"
          :width="x(entry.value)"
          :height="y.bandwidth()"
          :fill="colors[index % colors.length]"
        />
        
        <!-- Candidate Name -->
        <text
          class="candidate-label"
          :x="margin.left - 10"
          :y="y(index) + y.bandwidth() / 2"
          text-anchor="end"
        >
          {{ entry.name }}
        </text>
        
        <!-- Vote Count -->
        <text
          class="vote-count"
          :x="margin.left + x(entry.value) + 10"
          :y="y(index) + y.bandwidth() / 2"
        >
          {{ entry.vote_count }} ({{ (entry.value * 100).toFixed(1) }}%)
        </text>
      </g>
    </svg>
  </div>
</template>

<script>
import { scaleLinear, scaleBand } from 'd3';

export default {
  props: {
    entries: Array,
    postName: String
  },
  setup(props) {
    const margin = { top: 20, right: 20, bottom: 30, left: 150 };
    const height = Math.max(300, props.entries.length * 40 + margin.top + margin.bottom);
    const width = 800;
    
    const colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
    
    const x = scaleLinear()
      .domain([0, 1])
      .range([0, width - margin.left - margin.right]);
      
    const y = scaleBand()
      .domain(props.entries.map((_, i) => i))
      .range([margin.top, height - margin.bottom])
      .padding(0.2);
      
    return { margin, height, width, colors, x, y };
  }
};
</script>

<style>
.bar {
  transition: width 0.3s ease;
}
.candidate-label {
  font-size: 14px;
  fill: #374151;
  dominant-baseline: middle;
}
.vote-count {
  font-size: 13px;
  fill: #6b7280;
  dominant-baseline: middle;
}
</style>