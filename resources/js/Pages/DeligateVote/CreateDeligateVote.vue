<template>
<nrna-layout>
<app-layout>
      <div class="mt-6 text-center"> 
        <jet-validation-errors class="mb-4  mx-auto text-center " />  
         <div class="m-auto text-center bg-blue-200 py-4 ">  
        <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
          <p> You have given the correct voting code. you can Vote now!</p>
        <p class="m-auto"> Please select the correct candidates of your choice</p>
        <p> यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ। </p>
      </div>  
      </div>
      <form @submit.prevent="submit" class=" text-center mx-auto mt-10">
        <div class="flex flex-col justify-center px-2 m-2"> 
           <!-- Two divs 
            **First : Voting option 
            **Second no vote option            
            -->
            
              <div v-if ="! this.no_vote_option" class="flex flex-col">  <!-- Here starts the voting option  -->

                  <!-- ****************ICC Member ******************************************************************************************************** -->       
                    <!-- <div id="first_vote_window"  -->
                  <div id="first_vote_window" 
                      v-if ="this.icc_members.length>0" 
                     class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
                    <div class="flex flex-col text-xl font-bold text-gray-900">
                      <label> Please choose one  Candidate as the ICC Member. </label> 
                      <label class="p-2"> कृपया एक जना लाई आइसीसी सदस्य  चुन्नुहोस ।  </label>   
                    </div>
                      <!-- here starts: icc -->
                      <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                          <div  v-for="(icc_member, pIndx) in icc_members" :key="pIndx"  
                              class="flex flex-col justify-center p-4 mb-2 text-center  
                              border border-gray-100 rounded"> 
                              <show-candidate 
                              :candidacy_image_path ="icc_member.image_path_1"
                              :post_name          ="icc_member.post_name"   
                              post_nepali_name   ="आइसीसी सदस्य"  
                              :candidacy_name     ="icc_member.candidacy_name">
                              </show-candidate>              
                                        <!-- here starts -->
                            <div class="px-2 py-2">
                              <input 
                              type      ="checkbox"
                              :id       ="icc_member.user_id"
                              :name     ="icc_member.post_name"
                              :value    ="icc_member.candidacy_id"  
                              v-model   ="form.icc_member"
                              class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 
                              focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                              @change    ="updateBoxes(this.icc_members,this.form.icc_member,this.icc_memberTicks)" 
                              :disabled ="icc_member.disabled"
                            />
                            </div> 

                          </div>
                          

                      </div> 
                        <div  class="mb-4 p-2" v-if="form.icc_member.length"> You have selected 
                        <span class="font-bold text-indigo-600"> {{find_selected_name(this.icc_members,this.form.icc_member)}} </span> as President of NRNA Germany!
                      </div>
                   </div>
                    <!-- next -->
      
                 
          </div>  <!-- End of Voting option   --> 
             
             <!-- ****************NO vode option ******************************************************************************************* -->       
      <!-- ****************NO vode option ******************************************************************************************* -->       
            <!-- ****************NO vode option ******************************************************************************************* -->       
         
             
              <!-- Here comes the no vote Button  -->
              <div  id="second_vote_window"
               class="flex flex-col border border-2 border-blue-300 m-2 py-4 px-6"> 
                <div class=" flex flex-col items-center justify-center py-2 mb-2 text-bold text-red-500 text-xl">
                 <p> !!कुनै पनि उमेद्बारहरुलाई स्विकार गर्न नचाहने हरुका लागि मात्र !!</p> 
                <p>  !! Attention Please!, This is option only for the Rejection!!</p>
                <p> !! उमेदवारहरु लाई अस्विकार को लागि मतदान । !!!</p> 
                </div>
               <div class="px-2 py-2">
                    <input 
                    type      ="checkbox"
                    :id       ="no_vote_option"
                    :name     ="no_vote_option"
                    :value    =true
                    v-model   ="form.no_vote_option"
                    class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    @change  ="update_no_vote_option()" 
                  />
                  </div> 
                  <p> By clicking this button, You agree that you are rejecting  all the candidates.  </p>                      
                  <p> यो भोटमा क्लिक गरेर म यो लिस्टमा उल्लेखित कुनै पनि उमेद्बारलाई  मतदानलाई नगरि आफ्नो अभिमत ""राइट टु रिजेक्ट अल क्यान्डिडेट्स" 
                    लाई सुरक्षित गर्न चाहन्छु। 
                   </p>

              </div>     
           <!--end of no vote button -->             
            <div class="flex flex-col items-center mx-auto my-4 w-full by-4 " style="background-color: #F1F1F1;"> 
              <!-- Here comes the no vote Button  -->
              <div  class="flex flex-col border border-3 border-blue-300 mx-2 my-4 py-4 px-6"> 
                <div class=" flex flex-col items-center justify-center py-2 mb-2 text-bold text-red-700 text-xl">
                      <p> Button for Agreement </p> 
                     <p> मतदान गरेको स्विकार </p>  
                </div>
               <div class="px-2 py-2">
                    <input 
                    type      ="checkbox"
                    :id       ="agree_button"
                    :name     ="agree_button"
                    :value    =true
                    v-model   ="form.agree_button"
                    class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                 
                  />
                  </div> 
                  <p>  By clicking this button, I conform that I have chosen the candidates correctly and I followed the online rules to vote the candidates. </p>
                <p>यो बटनमा थिचेर मैले माथि छाने आनुसार  मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु। </p> 
             
                   <button type="submit" class="mx-2 my-4 px-2 py-6 rounded-lg bg-blue-300 w-full mx-auto shadow-sm text-xl font-bold text-gray-900">
                   Submit
                   </button>
              

              </div>
              <!-- here ends -->
            </div>
             <!-- here comes the error  -->
            <div class="mx-auto text-center">
              <jet-validation-errors class="mb-4  mx-auto text-center " />
            </div>
        </div>
      </form>
     
  </app-layout>
</nrna-layout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'
import ShowCandidate from '@/Shared/ShowCandidate'
import JetInput from '@/Jetstream/Input'
import ShowCheckbox from "@/Shared/ShowCheckbox";
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'    
export default {
  props:{
     candidacies:Object,
     user_name : String,
     user_id : Number,  
     user_lcc : String   
  },
 
  data(){ 
      return{
        /**
        ** Write here the candidate ids:
        *  They are saved in posts table . 
        */
        icc_member_post_id:           "2021_01",
        
      //+---------------------------------+---------+  
   
        no_vote_option: 0,
        
        /** 
        * Define Ticks for each Post seperately 
        *
        */

         icc_memberTicks:{
            limit: 1,
            ticks:[]
          },
        

          //end of ticks 
          ticks: [],
       
      }
  },
  setup (props) {
    const form = useForm({
      user_id:          props.user_id,
      icc_member:             [],
      president:              [],
      vice_president:         [], 
      wvp:                    [], 
      general_secretary:      [],
      secretary:              [],
      treasure:               [],
      w_coordinator:          [],
      y_coordinator:          [],
      cult_coordinator:       [],
      child_coordinator:      [],
      studt_coordinator:      [],
      member_berlin:          [],
      member_hamburg:         [],
      member_nsachsen:        [],
      member_nrw:             [],
      member_hessen:          [],
      member_rhein_pfalz:     [],
      member_bayern:          [],     
      no_vote_option:       false,
      agree_button:          false,     
      
    })
    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
   
      form.post('/deligatevote/submit') 
    }

    return { form, submit }
  },
  computed: {
      getLength(){        
        return this.form.icc_member.length;
      },
      
      /***************************************************************************
      * We compute the candidacies here 
      *
      ******************************************************************************/
      icc_members (){
        // return pres1;
        
        return this.create_candidates(this.icc_member_post_id)
      //  return this.create_candidates(this.icc_member_post_id).sorty(this.sortByName)
     
      },

      /***************************************************************************/
      get_selected_name(candiVec,selected_id){
          /**
           *@input1 : candidacy vector , 
           *@input2: selected id
           */
          return this.find_selected_name(candiVec, selected_id);
        // return selected_name
      },
       get_selected_ids(candiVec,selected_id){
          /**
           *@input1 : candidacy vector , 
           *@input2: selected id
           */
          return this.find_selected_ids(candiVec, selected_id);
        // return selected_name
      }
  },
  methods:{
     /**
      * This function changes the value of no vote option . 
      * That means if you click on no vote option. The form will disappear 
      * 
      */
       update_no_vote_option(){
          this.no_vote_option =this.form.no_vote_option;
          
          if(this.no_vote_option){
            //make the default value  for form 
            this.form.icc_member          = []
          }
       },

     updateBoxes(candiVec, selectedVec,tickObj) {
       /**
        * @param1  : candidateVector 
        * @param2  : selected vector 
        * @tickobj : ticks defined in the data. 
        * 
        * 
        */
        //
      
      // update the number of ticks...
      // this.ticks = this.icc_members.filter(box => this.form.president.includes(box.candidacy_id));
       tickObj.ticks = candiVec.filter(box => selectedVec.includes(box.candidacy_id));
      // console.log("tick length: "+tickObj.ticks.length);
      
      // re-enable checkboxes if back under the limit...
      if (tickObj.ticks.length < tickObj.limit) {
         candiVec.forEach(box => {
          if (!selectedVec.includes(box.post_id)) box.disabled = false;
        });
      }

      // disable empty checkboxes if at the limit...
      if (tickObj.ticks.length == tickObj.limit) {
        candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id)
          // console.log(selectedVec); 
          if (!selectedVec.includes(box.candidacy_id)) box.disabled = true;
        });
      }
     
    },
    create_candidates(pid){
      let candiArray =[]; 
        //let pres =this.candidacies.data.find(item=>item.candidacy_id===1)
        // console.log(candi) 
        let candi =this.candidacies.data.filter(item=>item.post_id===pid) 
      if(candi.length>1){
          candi[0].disabled =false
          candiArray  =candi
        }else{
          candi.disabled=false
          // candiArray.push(candi)
          candiArray=candi

        }
        console.log("candidate array: "+pid)
        console.log(candiArray.length);
        return candiArray;
    },
     find_selected_name(candiVec, selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_names =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id) 
          
          // console.log(selectedVec); 
          if (selectedVec.includes(box.candidacy_id))  selected_names.push(box.candidacy_name);
        });
        let  elected_names =selected_names.join(',');
        return elected_names

     },
      find_selected_user_id(candiVec,selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_ids =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id)
          // console.log(selectedVec); 
          if (selectedVec.includes(box.candidacy_id))  selected_ids.push(box.user_id);
        });
        let  elected_user_ids =selected_ids.join(',');
        return elected_user_ids

     },
      getSortOrder(prop) {    
    return function(a, b) {    
        if (a[prop] > b[prop]) {    
            return 1;    
        } else if (a[prop] < b[prop]) {    
            return -1;    
        }    
        return 0;    
    }
        
},
    SortByName(x,y) {
      return ((x.candidacy_name == y.candidacy_name) ? 0 : ((x.candidacy_name > y.candidacy_name) ? 1 : -1 ));
    },

    // Call Sort By Name
    
   
  },
  components:{
      ShowCandidate,
      JetInput,
      ShowCheckbox,
      JetValidationErrors,
      AppLayout,
      NrnaLayout  
  
  }
}
</script>
<style scopeed>
#first_vote_window{ 
  background-color :#C6FFC1;/* #EFF8FF; /** #BEDCFA; **/
}  
#second_vote_window{
  background-color : #BEDCFA; /*#B5EAEA; /* #B2EBF2; /** #BEDCFA; **/ 
}  

</style>