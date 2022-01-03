<template>
   <nrna-layout>
    <app-layout> 
        <div class="py-2 flex flex-row justify-center "> 
            <Link  v-if="posts.prev_page_url" 
                    class="m-2 px-2 py-2 bg-gray-300 rounded" 
                    :href="posts.prev_page_url" 
            >
                Previous Page 
        </Link> 
        <Link  v-if="posts.next_page_url" 
            class="m-2 px-2 py-2 bg-gray-300 rounded" 
            :href="posts.next_page_url"
            >
            Next Page 
            </Link> 
        </div>
      <Table
      :filters="queryBuilderProps.filters"
      :search="queryBuilderProps.search"
      :columns="queryBuilderProps.columns"
      :on-update="setQueryBuilder"
      :meta="posts"  
      class="relative w-full border"
    >
    <template #head>
        <tr>
            <!-- <th @click.prevent="sortBy('name')">Stock Id</th> -->
            <!-- <td v-show="showColumn('manufacturerId')">S.N.</td> -->
            <th class="border-r border-green-200" v-show="showColumn('sn')" >S.N. (क्रम संख्या )</th>
            <th class="border-r border-green-200" v-show="showColumn('post_id')" @click.prevent="sortBy('post_id')">Post ID </th>
            <th class="border-r border-green-200" v-show="showColumn('name')" @click.prevent="sortBy('name')">Post Name  (पदहरु)</th>
            <th class="border-r border-green-200" v-show="showColumn('required_number')" @click.prevent="sortBy('required_number')">Required Number</th>
            <th class="border-r border-green-200" v-show="showColumn('state_name')" @click.prevent="sortBy('state_name')">Scope </th>
            
            
        </tr>
    </template> 
       <template #body>
      <tr v-for="(post, pIndx) in posts.data" :key="pIndx"
          :class="[{'bg-gray-100':pIndx%2==0}, p-1]"    
      >
        <td class="border-r border-green-200" >{{ pIndx+1 }}</td> 
        <td class="border-r border-green-200" v-show="showColumn('post_id')">{{ post.post_id}}</td>  
        <td class="border-r border-green-200" v-show="showColumn('name')">{{ post.name}}</td> 
        <td class="border-r border-green-200" v-show="showColumn('required_number')" >{{ post.required_number}}</td>
        <td class="border-r border-green-200" v-show="showColumn('state_name')">{{ post.state_name}}</td> 
        
       
      </tr>
    </template>
   
        <!-- end of table  -->
     </Table>  


        <!-- ends of table  -->
    </app-layout> 
    </nrna-layout>
    <!-- <div> <footer> </footer></div>  -->
</template>
<script>
// import AppLayout from "@/Layouts/NrnaLayout";
import { Head, Link } from '@inertiajs/inertia-vue3';  
import NrnaLayout from '@/Layouts/NrnaLayout'
import AppLayout from "@/Layouts/AppLayout";
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogOverlay,
  DialogTitle,
} from '@headlessui/vue'
// import AppLayout from "@/Jetstream/Header";
// import Footer from "@/Jetstream/Footer";
export default {
    mixins: [InteractsWithQueryBuilder],
    props: {
        posts: Object,
    },
    methods:{
       
    },
  
    components: {
        // NrnaLayout,
        AppLayout,
        NrnaLayout,
          Link, 
        Table: Tailwind2.Table,
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogOverlay,
        DialogTitle,
        
    },
};
</script>
