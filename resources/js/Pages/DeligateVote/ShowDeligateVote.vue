<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
          <div class="m-auto text-center bg-blue-200 py-4 ">  
               <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
               <p class="m-auto"> Thank You for your deligatevote. Please keep your passowrd very secret. Also please do not  ask others to show their deligatevote. 
                    For verification of the result, this deligatevote will be deleted only after one month. </p>
               <p>यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो पासवर्ड अरुलाई नदिनु होला ।  
                    प्राप्त मतहरुको पुनर्पुष्ठी गर्नको लाई यहाँको यो मत  अबको एक महिना पछी मात्रा डिलेट हुने छ। 
               </p>
               <!-- {{deligatevote}} -->
          </div>
           <div v-if="deligatevote.no_vote_option" class="mt-10 p-2 mx-auto "> 
                
                <p class =" text-xl font-bold text-gray-900"> You have used 
                <span class="text-red-600" > your right to reject </span> all candidates.</p> 
 
          </div> 
          <div v-else class="flex flex-col justify-center  py-4 px-6  ">         
            <!--  here we put the candidate -->
                     <p class="px-2 mx-auto py-2 text-gray-900 font-bold text-xl"> 
                          Your Vote  </p>
                    <table class=" m-4 text-left" v-if="deligatevote">
                        <tr class="border-b-2 border-gray-400">
                             <th class="w-1/3 p-4">Post</th>
                              <th class="w-1/3 p-4">User ID</th>
                              <th class="w-1/3 p-4">Candidacy Name</th>

                        </tr>
                        <tr  v-if="deligatevote.icc_member1_id">
                            
                             <td class="w-1/3 p-4">ICC Member </td>
                             <td class="w-1/3 p-4">{{ deligatevote.icc_member1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.icc_member1_name }} </td>
                        </tr> 
                         <!-- next -->
                           <tr v-if="deligatevote.president_id" >
                            
                             <td class="w-1/3 p-4">President</td>
                             <td class="w-1/3 p-4">{{ deligatevote.president_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.president_name }} </td>
                        </tr>
                           <!-- next - -->
                         <tr v-if="deligatevote.vice_president1_id"> 
                               <td class="w-1/3 p-4">Vice President</td>
                             <td class="w-1/3 p-4">{{ deligatevote.vice_president1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.vice_president1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next - -->
                         <tr v-if="deligatevote.vice_president2_id"> 
                               <td class="w-1/3 p-4">Vice President</td>
                             <td class="w-1/3 p-4">{{ deligatevote.vice_president1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.vice_president2_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <tr v-if="deligatevote.general_secretary_id"> 
                               <td class="w-1/3 p-4">General Secretary</td>
                             <td class="w-1/3 p-4">{{ deligatevote.general_secretary_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.general_secretary_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                    <!-- next -->
                           <tr v-if="deligatevote.secretary1_id"> 
                               <td class="w-1/3 p-4">Secretary</td>
                             <td class="w-1/3 p-4">{{ deligatevote.secretary1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.secretary1_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                  
                           <tr v-if="deligatevote.secretary2_id"> 
                               <td class="w-1/3 p-4">Secretary</td>
                             <td class="w-1/3 p-4">{{ deligatevote.secretary2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.secretary2_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                  
                           <tr v-if="deligatevote.treasure_id"> 
                               <td class="w-1/3 p-4">Treasure</td>
                             <td class="w-1/3 p-4">{{ deligatevote.treasure_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.treasure_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="deligatevote.woman_coordinator_id"> 
                               <td class="w-1/3 p-4">Woman Coordinator</td>
                             <td class="w-1/3 p-4">{{ deligatevote.woman_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.woman_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="deligatevote.youth_coordinator_id"> 
                               <td class="w-1/3 p-4">Youth Coordinator</td>
                             <td class="w-1/3 p-4">{{ deligatevote.youth_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.youth_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="deligatevote.culture_coordinator_id"> 
                               <td class="w-1/3 p-4">Culture Coordinator</td>
                             <td class="w-1/3 p-4">{{ deligatevote.culture_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.culture_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="deligatevote.children_coordinator_id"> 
                               <td class="w-1/3 p-4">Language, Lieterature and Children coordinator</td>
                             <td class="w-1/3 p-4">{{ deligatevote.children_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.children_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="deligatevote.student_coordinator_id"> 
                               <td class="w-1/3 p-4">Student Coordinator</td>
                             <td class="w-1/3 p-4">{{ deligatevote.student_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.student_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <tr v-if="deligatevote.member_berlin1_id"> 
                               <td class="w-1/3 p-4">Member Berlin</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_berlin1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_berlin1_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_berlin2_id"> 
                               <td class="w-1/3 p-4">Member Berlin</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_berlin2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_berlin2_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_hamburg1_id"> 
                               <td class="w-1/3 p-4">Member Hamburg</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_hamburg1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_hamburg1_name }} </td>
 
                         </tr>
                            <!-- next -->
                             <tr v-if="deligatevote.member_hamburg2_id"> 
                               <td class="w-1/3 p-4">Member Hamburg</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_hamburg2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_hamburg2_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_niedersachsen1_id"> 
                               <td class="w-1/3 p-4">Member Niedersachsen</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_niedersachsen1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_niedersachsen1_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <!-- next -->
                             <tr v-if="deligatevote.member_niedersachsen2_id"> 
                               <td class="w-1/3 p-4">Member Niedersachsen</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_niedersachsen2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_niedersachsen2_name }} </td>
 
                         </tr>
                         
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_nrw1_id"> 
                               <td class="w-1/3 p-4">Member NRW</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_nrw1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_nrw1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_nrw2_id"> 
                               <td class="w-1/3 p-4">Member NRW</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_nrw2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_nrw2_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_hessen1_id"> 
                               <td class="w-1/3 p-4">Member Hessen</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_hessen1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_hessen1_name }} </td>
 
                         </tr>
                          <!-- next -->
                     
                               <!-- next -->
                             <tr v-if="deligatevote.member_hessen2_id"> 
                               <td class="w-1/3 p-4">Member Hessen</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_hessen2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_hessen2_name }} </td>
 
                         </tr>
                          <!-- next -->
                          
                             <!-- next -->
                             <tr v-if="deligatevote.member_rheinland_pfalz1_id"> 
                               <td class="w-1/3 p-4">Member Rheinland Pfalz</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_rheinland_pfalz1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_rheinland_pfalz1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <tr v-if="deligatevote.member_rheinland_pfalz2_id"> 
                               <td class="w-1/3 p-4">Member Rheinland Pfalz</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_rheinland_pfalz2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_rheinland_pfalz2_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_bayern1_id"> 
                               <td class="w-1/3 p-4">Member Bayern</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_bayern1_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_bayern1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="deligatevote.member_bayern2_id"> 
                               <td class="w-1/3 p-4">Member Bayern</td>
                             <td class="w-1/3 p-4">{{ deligatevote.member_bayern2_id }}</td>
                            <td class="w-1/3 p-4"> {{ deligatevote.member_bayern2_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                         
                         
                           
                    </table>
          </div>
           <!-- end of list -->
     </div>  
     </app-layout>
</nrna-layout> 
</template>
<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import AppLayout  from '@/Layouts/AppLayout'
export default {
  components: { NrnaLayout }, 
    props:{
         deligatevote: Object 
    },
    data(){
         return {
          //     has_voted: (this.deligatevote)
         }
    },
    computed: { 
         user_has_voted(){
          //     console.log(this.deligatevote);
          // return isEmpty(this.deligatevote)
          return true
         }
    },
    components:{
         NrnaLayout,
         AppLayout 
    }
}
</script>