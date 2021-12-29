<!-- This example requires Tailwind CSS v2.0+ -->
<template> 
<div class="flex flex-col"> 
        {{vote}}
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Designation 
              </th> 
                 <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Region
              </th> -->
              <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th> -->
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <template v-for="(candidate, vtIndex) in vote.natioanal_selected_candidates" :key="vtIndex"> 
            <tr v-if = "candidate"  
                v-for= '(candiVote, cIndx) in candidate["candidates"]  ' :key="cIndx">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-16">
                     <img class="h-16 w-16  rounded-full" 
                     :src="full_image_name(candiVote.image_path_1)" alt="">
                  
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{candiVote.user.name}}
                    </div>
                    <div class="text-sm text-gray-500">
                      Candidacy Id: {{candiVote.candidacy_id}}
                    </div>
                    <div class="text-sm text-gray-500">
                      User Id: {{candiVote.user_id}}
                    </div>
                  
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{candidate["post_name"] }}</div> 
                <div class="text-sm text-gray-500">post ID: {{candidate["post_id"] }}</div>
              </td>
              <!-- <td class="px-6 py-4 whitespace-nowrap">
                    {{candiVote.user.region }} 
               </td> -->
              
            </tr>
            </template>
            <!-- More people... -->
            <!-- regional candidates  here  -->
              <template v-for="(candidate, vtIndex) in vote.regional_selected_candidates" :key="vtIndex"> 
            <tr v-if = "candidate"  
                v-for= '(candiVote, cIndx) in candidate["candidates"]  ' :key="cIndx">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-16 w-16">
                      <!-- src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt=""> -->
                    <img class="h-16 w-16  rounded-full" 
                     :src="full_image_name(candiVote.image_path_1)" alt="">
                  
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{candiVote.user.name}}
                    </div>
                    <div class="text-sm text-gray-500">
                      Candidacy Id: {{candiVote.candidacy_id}}
                    </div>
                    <div class="text-sm text-gray-500">
                      User Id: {{candiVote.user_id}}
                    </div>
                  
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{candidate["post_name"] }}</div> 
                <div class="text-sm text-gray-500">post ID: {{candidate["post_id"] }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                   {{candiVote.user.region }} 
               </td>
              
            </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</template>
<script>
export default{
 props:{
     vote: Object 
 },
 computed:{
    
 },
 data(){
       return {
          image_path: "/storage/images/",
        //   imagename: this.image_path+ this.candidacy_image_path 
       } 
     
    },
methods:{
    full_image_name (imagename){
         return this.image_path+ imagename    
    }
}  
}
</script>
