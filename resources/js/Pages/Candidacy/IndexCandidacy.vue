<template>
    <app-layout> 
         <!-- here is the table 
          -->           
          <div class="flex flex-col mb-10 py-10" > 
              <!-- <div class="flex flex-rwow justify-center m-auto space-x-4 py-2 mb-2">
                   <inertia-link href="/candidacy/create" class=" p-2 bg-red-50 font-bold text-gray-900 rounded border border-right "> Candidacy Form </inertia-link>
                    <inertia-link href="/posts/index" class=" p-2  mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> Name of Posts</inertia-link>
                     <inertia-link href="/notices/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> List of Notices</inertia-link>

                </div> -->
             <div class="p-4 m-auto bg-gray-50">  
             <h2 class="font-bold text-xl text-gray-800 leading-tight text-center">
                Candidates List | उमेद्बवारहरुको सुची  
            </h2>
             </div>
           <!-- start  -->
               <div class="text-center mx-auto mt-4 px-4 mb-12">
            <div class="mx-auto text-center">
                <table class="w-full m-4 text-left">
                    <tr class="border-b-2 border-gray-400">
                        <th class="w-1/3 p-4">Candidacy Name</th>
                        <th class="w-1/3 p-4">Candidacy ID</th>
                        <th class="w-1/3 p-4">Post</th>
                    </tr>
                    <tr
                        v-for="(candidate, candiIndx) in candidacies.data"
                        :key="candiIndx"
                        :class="{
                            'bg-gray-100 border-b border-gray-100':
                                candiIndx % 2 === 0,
                            'bg-white border-b border-gray-100':
                                candiIndx % 2 > 0,
                        }"
                    >
                        <td class="w-1/3 p-4">
                            {{ candidate.candidacy_name }}
                        </td>
                        <td class="w-1/3 p-4">{{ candidate.id }}</td>
                        <td class="w-1/3 p-4">{{ candidate.post_name }}</td>
                    </tr>
                </table>
            </div>
        </div>
             <!-- end  -->
          </div>
    </app-layout>    
</template>
<script>
import AppLayout from "@/Layouts/NrnaLayout";


export default {
    props:{
        candidacies: Object
    },
    computed: {
        candi(){
        console.log(this.candidacies.data)
        return  this.candidacies;
        }   
    },
    components:{
        AppLayout
    }

}
</script>