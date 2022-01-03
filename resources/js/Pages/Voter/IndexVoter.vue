<template >
   <nrna-layout>
   <app-layout>
    <div class="p-2 m-2 bg-gray-100 min-h-screen">
              <div class="py-2 flex flex-row justify-between "> 
      <Link  v-if="voters.prev_page_url" class="m-2 px-2 py-2 bg-gray-300 rounded" :href="voters.prev_page_url" >Previous Page </Link> 
      <Link  v-if="voters.next_page_url" class="m-2 px-2 py-2 bg-gray-300 rounded" :href="voters.next_page_url">Next Page </Link> 
      </div>
       <!-- {{voters}}  -->
    <!-- header ends  -->
    <!-- Table head starts  -->
    <Table
          :filters="queryBuilderProps.filters"
          :search="queryBuilderProps.search"
          :columns="queryBuilderProps.columns"
          :on-update="setQueryBuilder"
          :meta="voters"  
          class="relative w-full border"
    >
         <!-- Table head  -->
          <template #head >        
      <tr class="py-2 border-b border-gray-400 bg-gray-300 text-gray-900 font-bold sticky ">
        <!-- <th @click.prevent="sortBy('name')">Stock Id</th> -->
        <!-- <td v-show="showColumn('manufacturerId')">S.N.</td> -->
        <th class="sticky top-0 border-r border-green-200" v-show="showColumn('sn')">S.N.</th>
        <!-- Name -->
        <th class="sticky top-0 border-r border-green-200" v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
        
        <th class="sticky top-0 border-r border-green-200" v-show="showColumn('user_id')" @click.prevent="sortBy('user_id')">User ID</th>
        <!-- region    -->
        <th class="sticky top-0 border-r border-green-200" v-show="showColumn('region')" @click.prevent="sortBy('region')">Status Name</th>
        
      </tr>
      <!-- <tr><th class="fixed top-0"> test</th> </tr>      -->
    </template> 
       <!--
       ******
       * 
       * here starts the table body  
       *
       -->
      <template #body>
      <tr v-for="(voter, voterIndx) in voters.data" :key="voterIndx"
        :class="[{'bg-gray-100':voterIndx%2==0}, p-1]" 
      >
        <td class="border-r border-green-200" v-show="showColumn('sn')">{{ voterIndx +1}}</td>
        <!-- externalId -->
        <td class="border-r border-green-200" v-show="showColumn('user_id')">{{ voter.user_id}}</td>
        
        <td class="border-r border-green-200" v-show="showColumn('name')">{{ voter.name}}</td>
        <!-- status name  -->
        <td class="border-r border-green-200" v-show="showColumn('region')">{{ voter.region}}</td>
       
      </tr>
       </template>
     <!-- Table head ends here  -->
    </Table>

        <!-- here ends  -->
    </div>
</app-layout>
</nrna-layout>
</template> 
<script>
// import User from "../User.vue";
import NrnaLayout from '@/Layouts/NrnaLayout'
import AppLayout from "@/Layouts/AppLayout";
import { Inertia } from '@inertiajs/inertia';
import  Sendmessage from '@/Pages/Message/Sendmessage';
import { Link } from '@inertiajs/inertia-vue3';
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogOverlay,
  DialogTitle,
} from '@headlessui/vue'
import { defineComponent, ref } from "vue";

export default {
    mixins: [InteractsWithQueryBuilder],
    props:{ 
        voters: Object,
        filters: Object,
        can_send_code: Boolean,
        // message_receiver_id:Integer 
        // from: String,
        // name: String,
        
    },
 
    components:{
        Sendmessage,
        NrnaLayout,
        AppLayout,
        Link, 
        Table: Tailwind2.Table,
    // Modal components 
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogOverlay,
        DialogTitle,
    }
   
}
</script>