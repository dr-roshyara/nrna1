<template>
    <nrna-layout>
        <app-layout>
           <div class="mt-6 text-center"> 
          
            <div class="m-auto text-center bg-blue-200 py-4 ">  
            <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
            <p> You have given the correct voting code. you can Vote now!</p>
            <p class="m-auto"> Please select the correct candidates of your choice</p>
            <p> यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ। </p>
            </div>  
          </div>
                    <!-- {{posts.data}}   -->
                    <!-- {{candidate_post_ids(candidacies.data)}} -->
          <!-- {{candidacies.data}}   -->
            <jet-validation-errors class="mb-4  mx-auto text-center " />
              {{this.page.errors}}
          <form @submit.prevent="submit" class=" text-center mx-auto mt-10">
      
          <div v-if ="!this.no_vote_option" 
           v-for ="(post_id, pId) in candidate_post_ids(candidacies.data)" :key="pId"
            :class="[pId%2==0? 'second_vote_window flex flex-col': 'first_vote_window flex flex-col']"
            > 
            <create-votingform 
            :candidates=" select_candidates_for_a_post(candidacies.data, post_id)"
            @add_selected_candidates = "this.form.selected_candidates[pId] = add_selected_to_form_submission(selectedArray=$event)"
            > 

          </create-votingform>
        
        
        </div>
             <!-- Here we write the div for accepting the voting action .  -->
            <div class="flex flex-col items-center mx-auto my-4 w-full by-4 " 
            style="background-color: #F1F1F1;"> 
              <!-- Here comes the no vote Button  -->
                <div  class="flex flex-col w-full border border-3 border-blue-300 mx-2 my-4 py-4 px-6"> 
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
                      <div v-if="errors.agree_button">{{ errors.agree_button }} </div>
                   <button type="submit" class="mx-2 my-4 px-2 py-6 rounded-lg bg-blue-300 w-full mx-auto shadow-sm text-xl font-bold text-gray-900">
                   Submit
                   </button>
                </div>
            </div>
            <!-- here comes the error  -->
            <div class="mx-auto text-center">
              <jet-validation-errors class="mb-4  mx-auto text-center " />
            </div>
   
          </form>
        </app-layout>
    </nrna-layout>
</template>
<script>
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'    
 import  CreateVotingform from '@/Pages/Vote/CreateVotingform.vue'
 import { useForm } from '@inertiajs/inertia-vue3'
import JetInput from '@/Jetstream/Input'
import ShowCheckbox from "@/Shared/ShowCheckbox";
import JetValidationErrors from '@/Jetstream/ValidationErrors' 

export default {
components:{
    AppLayout,
    NrnaLayout,
    CreateVotingform,
    JetValidationErrors

},
props:{
    candidacies: Array,
    posts: Array,
     user_name : String,
     user_id : Number, 
},
setup (props) {
    const form = useForm({
      user_id:              props.user_id,
    //   president:            [],
      selected_candidates:  new Array(candidate_post_ids(props.candidacies.data).length), 
      no_vote_option:       false,
      agree_button:         false,     
      
    })
    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
   
      form.post('/vote/submit')
    }
    function onlyUnique(value, index, self) {
             return self.indexOf(value) === index;
        }
         function candidate_post_ids(candidacies){
            let $post_ids =[] 
            candidacies.forEach(candidate =>{
                if(candidate.post_id){
                        $post_ids.push(candidate.post_id)
                } 
          });
          return $post_ids.filter(onlyUnique);    
        }

     function add_selected_to_form_submission(selectedArray){
        //    console.log(typeof(selectedArray));
        let candiVec =[];
           let ids = Object.values(selectedArray);
        //    console.log(ids.length);
           let tot_posts = posts.data;
           let candiArray =props.candidacies.data; 
           let selected_candis =[];
           if(ids.length==0){
               candiVec =[];
            //    console.log(candiVec);
           }else{
                 
                selected_candis= candiArray.filter(candidate => {
                //   console.log(ids.includes(candidate.candidacy_id));
                  return ids.includes(candidate.candidacy_id)
                });
              if(selected_candis.length>0){
                     //  console.log(selected_candis);
                    // console.log(selected_candis[0]);
                    candiVec =selected_candis;                   
                }   
                // console.log(form.selected_candidates);

           }
            // console.log("form selected");
            // console.log(form.president);
           return candiVec;    
          
        }
        

    return { form, submit,add_selected_to_form_submission, candidate_post_ids }
  },
data(){
    return {
        // candidate_post_ids =[]
        presient_post_id : 1
    }
},
computed:{
    /***
     * pluck the  post_ids 
     * const countries = [
        { name: 'France', capital: 'Paris'  },
        { name: 'Spain',  capital: 'Madrid' },
        { name: 'Italy',  capital: 'Rome'   }
        ]

        // we can extract the attributes with individual arrow functions
        countries.map(country => country.name)     // ⇒ ['France', 'Spain', 'Italy']
        countries.map(country => country.capital)  // ⇒ ['Paris', 'Madrid', 'Rome']

        // this function allows us to write that arrow function shorter
        const pluck = property => element => element[property]

        countries.map(pluck('name'))     // ⇒ ['France', 'Spain', 'Italy']
        countries.map(pluck('capital'))  // ⇒ ['Paris', 'Madrid', 'Rome']
    */
    sscandidate_post_ids(candidacies){
       let $post_ids =[] 
       candidacies.forEach(candidate =>{
          if(candidate.post_id){
                $post_ids.push(candidate.post_id)
          } 
          });
       return $post_ids.filter(this.onlyUnique);    
       }

},
methods:{

        select_candidates_for_a_post(candidacies,pid){
            let candiArray =[];
            candidacies.forEach(item=>{
                 if(item.post_id===pid){
                     let newItem =item;
                     newItem.disabled =false;
                     candiArray.push(newItem);  
                 }
            }); 
            return candiArray;
        },
}

//end     
}
</script>
<style scopeed>
.first_vote_window{ 
  background-color :#C6FFC1;/* #EFF8FF; /** #BEDCFA; **/
}  
.second_vote_window{
  background-color : #BEDCFA; /*#B5EAEA; /* #B2EBF2; /** #BEDCFA; **/ 
}  

</style>