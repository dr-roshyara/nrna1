<template>
   <nrna-layout>
    <!-- <app-layout>  -->
         <!-- {{candidacies}}  -->
                 <div class="py-2 flex flex-row justify-between "> 
            <Link  v-if="candidacies.prev_page_url" class="m-2 px-2 py-2 bg-gray-300 rounded" :href="candidacies.prev_page_url" >Previous Page </Link> 
            <Link  v-if="candidacies.next_page_url" class="m-2 px-2 py-2 bg-gray-300 rounded" :href="candidacies.next_page_url">Next Page </Link> 
        </div>
     <pagination class="mt-10" :links="candidacies.links" />

        <!-- {{candidacies.next_page_url}} -->
    <!-- <pagination class="mt-10" :links="candidacies.links" /> -->
    <div class=" flex flex-col  justify-center items-center overscroll-x-contain ">
     
    <Table
    :filters="queryBuilderProps.filters"
    :search="queryBuilderProps.search"
    :columns="queryBuilderProps.columns"
    :on-update="setQueryBuilder"
    :meta="candidacies"
     class="relative w-full border mx-auto"
  >
     <!-- head of the table  -->
      <template #head>
      <tr>
        <!-- <th @click.prevent="sortBy('name')">Stock Id</th> -->
        <!-- <td v-show="showColumn('manufacturerId')">S.N.</td> -->
        <th class="border-r border-green-200"  >S.N.</th>
        <th class="border-r border-green-200" v-show="showColumn('candidacy_id')" @click.prevent="sortBy('candidacy_id')">Candidacy ID</th>
         <th class="border-r border-green-200" v-show="showColumn('post_id')" @click.prevent="sortBy('post.post_id')">Post ID</th>
         <th class="border-r border-green-200" v-show="showColumn('post_name')" @click.prevent="sortBy('post.name')">Post Name</th>
         <th class="border-r border-green-200" v-show="showColumn('nrna_id')" @click.prevent="sortBy('user.nrna_id')">Candidate's NRNA ID</th>
         <th class="border-r border-green-200" v-show="showColumn('user_name')" @click.prevent="sortBy('user.name')">Candidate's Name</th>
         
        
      </tr>
    </template> 
     <!-- here is the body of the table  -->
       <template #body>
      <tr v-for="(candidacy, candiIndx) in candidacies.data" :key="candiIndx"
          :class="[{'bg-gray-100':candiIndx%2==0}, p-1]"    
      >
        <td class="border-r border-green-200" >{{ candiIndx+1 }}</td> 
        <td class="border-r border-green-200" v-show="showColumn('candidacy_id')">{{ candidacy.candidacy_id}}</td> 
         <td class="border-r border-green-200" v-show="showColumn('post_id')">{{ candidacy.post.post_id}}</td> 
        <td class="border-r border-green-200" v-show="showColumn('post_name')">{{ candidacy.post.name}}</td> 
         <td class="border-r border-green-200" v-show="showColumn('nrna_id')" >{{ candidacy.user.nrna_id}}</td>
        <td class="border-r border-green-200" v-show="showColumn('user_name')">{{ candidacy.user.name}}</td> 
        
        
       
      </tr>
    </template>

    <!-- //end of the table  -->
    </Table>
    </div>
    <!-- </app-layout>  -->
   </nrna-layout>
</template> 

<script>
import NrnaLayout from  '@/Layouts/NrnaLayout'
import AppLayout from  "@/Layouts/AppLayout";
import {Link} from '@inertiajs/inertia-vue3';
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';
//import { ref } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogOverlay,
  DialogTitle,
} from '@headlessui/vue'
export default {
   mixins: [InteractsWithQueryBuilder],
  components: {
    NrnaLayout,
    AppLayout, 
    Link, 
   Table: Tailwind2.Table,
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
   },
  props:{
     candidacies: Array
  },
    
}
</script>
