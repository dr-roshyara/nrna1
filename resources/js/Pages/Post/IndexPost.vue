<template>
    <app-layout> 
         <!-- here is the table 
          -->           
            <div class="mx-auto w-full   px-4 text-center">
                <table class="w-full text-left rounded p-4">
                    <tr class="border-b-2 border-gray-400 bg-blue-100"> 
                         <th class="w-1/3 p-4">S.N. (क्रम संख्या ) </th>
                        <th class="w-1/3 p-4">Post Name (पदहरु) </th>
                            <!-- <th class="w-1/3 p-4">पदहरु </th> -->
                            <th class="w-1/3 p-4 text-center"> Number of Persons(आबस्यक सन्ख्या) </th>                        
                    </tr>
                    <tr
                        v-for="(post, postIndx) in posts.data"
                        :key="postIndx" 
                        :class="{
                            'bg-gray-100 border-b border-gray-100':
                                postIndx % 2 === 0,
                            'bg-white border-b border-gray-100':
                                postIndx % 2 > 0,
                        }"
                    >
                       <td class="w-1/3 p-4">
                            {{ postIndx+1 }} 
                        </td>
                        
                         <td class="w-1/3 p-4">
                            {{ post.name }} 
                        </td>
                        <!-- <td class="w-1/3 p-4">{{ post.nepali_name}}</td> -->
                        <td class="w-1/3 p-4 text-center">{{ post.required_number }}</td>
                    </tr>
                </table>
            </div>

        <!-- ends of table  -->
    </app-layout> 
    <!-- <div> <footer> </footer></div>  -->
</template>
<script>
import AppLayout from "@/Layouts/NrnaLayout";  
// import AppLayout from "@/Jetstream/Header";
// import Footer from "@/Jetstream/Footer";
export default {
    props: {
        posts: Object,
    },
    methods:{
       
    },
  
    components: {
        // NrnaLayout,
        AppLayout
        
    },
};
</script>
