<template>
  <div class="w-full mx-0 text-lg md:text-sm  bg-gray-50 rounded border border-lime-100 shadow-inner ">
    <svg :viewBox="`0 0 ${width} ${height}`" xmlns="http://www.w3.org/2000/svg">
        <!-- Here is the bar  -->
      <g v-for="(entry, index) in entries" 
        :key="'bar-'+index" fill="#696969" class="rounded">
        <rect :x="x(0)" :y="y(index)" 
            :width="Number(x(entry.value)) - Number(x(0))" 
            :height="y.bandwidth()" />
      </g>
        <!-- Here  is the percentage value  -->
      <g v-for="(entry, index) in entries" 
           :key="'text-'+index" fill="white">
        <text :x="x(entry.value)" v-if="entry.value>0" 
              :y="Number(y(index)) + Number(y.bandwidth()) / 2" dx="-265" 
              dy="0.35em">
               Total votes:
        {{ entry.vote_count }},  Total %:  
        {{ formattedText(entry.value) }}</text>
      </g>
        
        <!-- Here is to put the exact value 
          <g v-for="(entry, index) in entries" 
           :key="'text-'+index" fill="white">
        <text :x="x(entry.value)" v-if="entry.value>0"
              class="text-sm"
              :y="Number(y(index)) + Number(y.bandwidth()) / 2" dx="-180" 
              dy="0.35em">
              Total votes:
        {{ entry.vote_count }} | </text>
      </g> -->
         <!-- Here is the  ticks  -->
      <g :transform="`translate(175, ${margin.top})`">
        <g v-for="(num, index) in maxNumber" :key="'x-'+num" opacity="1" 
            :transform="`translate(${Number(x(index)) / 101 + 20}, 0)`"
             class="">
          <line stroke="red" opacity="0.5"  y2="-6"></line>
            <text v-if="index%5==0" fill="darkgreen" x="0" y="-20" dy="0em" 
                style="font-weight:bold">
              {{ index + format }}
              </text>
        </g>
      </g>
        <!-- Here are the names -->
      <g :transform="`translate(${margin.left}, 0)`">
        <path class="domain" stroke="#1E90FF" d="M0.5,30.5V683.5"></path>
        <g v-for="(entry, index) in entries" :key="'y-'+index" opacity="1" 
        :transform="`translate(0, ${Number(y(index)) + 15  })`">
          <line stroke="red" opacity="0.5" x2="-6"></line>
          <text fill="blue" x="-175" dy="0.3em" 
          tyle="font-weight:bold"
          >{{ get_name(entry.name) }}</text>
        </g>
      </g>
    </svg>
  </div>
</template>

<script>
import { computed } from 'vue'
import { scaleLinear, scaleBand, max, range } from 'd3'
export default{
    props: { 
    entries: Array,
    columns: Array,
    format: String
  },
  methods:{ 
       get_name(name){
         let names= name.split("(");
          return names[0];
       }

  },
  setup(props) {
    const margin = {
      top: 50,
      right: 50,
      bottom: 20,
      left: 200
    }
    const barHeight = 50
    const width = 1000
    const height = Math.round((props.entries.length + 0.1) * barHeight) + margin.top + margin.bottom

    const x = computed(() => scaleLinear().domain([0, max(props.entries, (d) => d.value)]).range([margin.left, width - margin.right]))

    const y = computed(() => scaleBand().domain(range(props.entries.length)).rangeRound([margin.top, height - margin.bottom]).padding(0.2))

    const formattedText = computed(() => x.value.tickFormat(300, props.format))

    const maxNumber = computed(() => {
      const formatNumber = formattedText.value(max(props.entries, d => d.value)).slice(0, -1)
      return Math.round(Number(formatNumber).toFixed(3))
    })

    return {
      margin,
      barHeight,
      width,
      height,
      x,
      y,
      formattedText,
      maxNumber
    }
  },
 

}
</script>