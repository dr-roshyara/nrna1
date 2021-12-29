<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
          <div class="m-auto text-center bg-blue-200 py-4 ">  
               <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
               <p class="m-auto"> Thank You for your vote. Please keep your passowrd very secret. Also please do not  ask others to show their vote. 
                    For verification of the result, this vote will be deleted only after one month. </p>
               <p>यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो पासवर्ड अरुलाई नदिनु होला ।  
                    प्राप्त मतहरुको पुनर्पुष्ठी गर्नको लाई यहाँको यो मत  अबको एक महिना पछी मात्रा डिलेट हुने छ। 
               </p>
               <!-- {{vote}} -->
          </div>
           <div v-if="vote.no_vote_option" class="mt-10 p-2 mx-auto "> 
                
                <p class =" text-xl font-bold text-gray-900"> You have used 
                <span class="text-red-600" > your right to reject </span> all candidates.</p> 
 
          </div> 
          <div v-else class="flex flex-col justify-center  py-4 px-6  ">         
            <!--  here we put the candidate -->
                     <p class="px-2 mx-auto py-2 text-gray-900 font-bold text-xl"> 
                          Your Vote  </p>
                          {{vote.candidate_01}}
                    <table class=" m-4 text-left" v-if="vote">
                        <tr class="border-b-2 border-gray-400">
                             <th class="w-1/3 p-4">Post</th>
                              <th class="w-1/3 p-4">User ID</th>
                              <th class="w-1/3 p-4">Candidacy Name</th>

                        </tr>
                        <tr  v-if="vote.icc_member1_id">
                            
                             <td class="w-1/3 p-4">ICC Member </td>
                             <td class="w-1/3 p-4">{{ vote.icc_member1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.icc_member1_name }} </td>
                        </tr> 
                         <!-- next -->
                           <tr v-if="vote.president_id" >
                            
                             <td class="w-1/3 p-4">President</td>
                             <td class="w-1/3 p-4">{{ vote.president_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.president_name }} </td>
                        </tr>
                           <!-- next - -->
                         <tr v-if="vote.vice_president1_id"> 
                               <td class="w-1/3 p-4">Vice President</td>
                             <td class="w-1/3 p-4">{{ vote.vice_president1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.vice_president1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next - -->
                         <tr v-if="vote.vice_president2_id"> 
                               <td class="w-1/3 p-4">Vice President</td>
                             <td class="w-1/3 p-4">{{ vote.vice_president1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.vice_president2_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <tr v-if="vote.general_secretary_id"> 
                               <td class="w-1/3 p-4">General Secretary</td>
                             <td class="w-1/3 p-4">{{ vote.general_secretary_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.general_secretary_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                    <!-- next -->
                           <tr v-if="vote.secretary1_id"> 
                               <td class="w-1/3 p-4">Secretary</td>
                             <td class="w-1/3 p-4">{{ vote.secretary1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.secretary1_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                  
                           <tr v-if="vote.secretary2_id"> 
                               <td class="w-1/3 p-4">Secretary</td>
                             <td class="w-1/3 p-4">{{ vote.secretary2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.secretary2_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                  
                           <tr v-if="vote.treasure_id"> 
                               <td class="w-1/3 p-4">Treasure</td>
                             <td class="w-1/3 p-4">{{ vote.treasure_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.treasure_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="vote.woman_coordinator_id"> 
                               <td class="w-1/3 p-4">Woman Coordinator</td>
                             <td class="w-1/3 p-4">{{ vote.woman_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.woman_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="vote.youth_coordinator_id"> 
                               <td class="w-1/3 p-4">Youth Coordinator</td>
                             <td class="w-1/3 p-4">{{ vote.youth_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.youth_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="vote.culture_coordinator_id"> 
                               <td class="w-1/3 p-4">Culture Coordinator</td>
                             <td class="w-1/3 p-4">{{ vote.culture_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.culture_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="vote.children_coordinator_id"> 
                               <td class="w-1/3 p-4">Language, Lieterature and Children coordinator</td>
                             <td class="w-1/3 p-4">{{ vote.children_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.children_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                                                   
                           <tr v-if="vote.student_coordinator_id"> 
                               <td class="w-1/3 p-4">Student Coordinator</td>
                             <td class="w-1/3 p-4">{{ vote.student_coordinator_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.student_coordinator_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <tr v-if="vote.member_berlin1_id"> 
                               <td class="w-1/3 p-4">Member Berlin</td>
                             <td class="w-1/3 p-4">{{ vote.member_berlin1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_berlin1_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_berlin2_id"> 
                               <td class="w-1/3 p-4">Member Berlin</td>
                             <td class="w-1/3 p-4">{{ vote.member_berlin2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_berlin2_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_hamburg1_id"> 
                               <td class="w-1/3 p-4">Member Hamburg</td>
                             <td class="w-1/3 p-4">{{ vote.member_hamburg1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_hamburg1_name }} </td>
 
                         </tr>
                            <!-- next -->
                             <tr v-if="vote.member_hamburg2_id"> 
                               <td class="w-1/3 p-4">Member Hamburg</td>
                             <td class="w-1/3 p-4">{{ vote.member_hamburg2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_hamburg2_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_niedersachsen1_id"> 
                               <td class="w-1/3 p-4">Member Niedersachsen</td>
                             <td class="w-1/3 p-4">{{ vote.member_niedersachsen1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_niedersachsen1_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <!-- next -->
                             <tr v-if="vote.member_niedersachsen2_id"> 
                               <td class="w-1/3 p-4">Member Niedersachsen</td>
                             <td class="w-1/3 p-4">{{ vote.member_niedersachsen2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_niedersachsen2_name }} </td>
 
                         </tr>
                         
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_nrw1_id"> 
                               <td class="w-1/3 p-4">Member NRW</td>
                             <td class="w-1/3 p-4">{{ vote.member_nrw1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_nrw1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_nrw2_id"> 
                               <td class="w-1/3 p-4">Member NRW</td>
                             <td class="w-1/3 p-4">{{ vote.member_nrw2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_nrw2_name }} </td>
 
                         </tr>
                          <!-- next -->
                           <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_hessen1_id"> 
                               <td class="w-1/3 p-4">Member Hessen</td>
                             <td class="w-1/3 p-4">{{ vote.member_hessen1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_hessen1_name }} </td>
 
                         </tr>
                          <!-- next -->
                     
                               <!-- next -->
                             <tr v-if="vote.member_hessen2_id"> 
                               <td class="w-1/3 p-4">Member Hessen</td>
                             <td class="w-1/3 p-4">{{ vote.member_hessen2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_hessen2_name }} </td>
 
                         </tr>
                          <!-- next -->
                          
                             <!-- next -->
                             <tr v-if="vote.member_rheinland_pfalz1_id"> 
                               <td class="w-1/3 p-4">Member Rheinland Pfalz</td>
                             <td class="w-1/3 p-4">{{ vote.member_rheinland_pfalz1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_rheinland_pfalz1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <tr v-if="vote.member_rheinland_pfalz2_id"> 
                               <td class="w-1/3 p-4">Member Rheinland Pfalz</td>
                             <td class="w-1/3 p-4">{{ vote.member_rheinland_pfalz2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_rheinland_pfalz2_name }} </td>
 
                         </tr>
                          <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_bayern1_id"> 
                               <td class="w-1/3 p-4">Member Bayern</td>
                             <td class="w-1/3 p-4">{{ vote.member_bayern1_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_bayern1_name }} </td>
 
                         </tr>
                          <!-- next -->
                            <!-- next -->
                             <!-- next -->
                             <tr v-if="vote.member_bayern2_id"> 
                               <td class="w-1/3 p-4">Member Bayern</td>
                             <td class="w-1/3 p-4">{{ vote.member_bayern2_id }}</td>
                            <td class="w-1/3 p-4"> {{ vote.member_bayern2_name }} </td>
 
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
import VoteFinal from '@/Pages/Vote/VoteFinal';
export default {
  components: { 
    NrnaLayout,
    AppLayout,
    VoteFinal 
    }, 
    props:{
         vote: Object 
    },
    data(){
         return {
          //     has_voted: (this.vote)
         }
    },
    computed: { 
         user_has_voted(){
          //     console.log(this.vote);
          // return isEmpty(this.vote)
          return true
         }
    },
    
}
</script>