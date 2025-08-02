<template>
    <nrna-layout>
        <app-layout>
        <div class="mt-6 text-center max-w-4xl mx-auto"> 
    <!-- Success Message -->
    <div class="m-auto text-center bg-gradient-to-r from-green-500 to-blue-600 text-white py-6 px-8 rounded-xl shadow-lg mb-8">  
        <div class="text-4xl mb-3">🎉</div>
        <p class="text-xl font-bold mb-2">Congratulation {{user_name}}!</p> 
        <p class="text-lg mb-2">You have given the correct voting code. You can Vote now!</p>
        <p class="mb-3">Please select the correct candidates of your choice</p>
        <p class="text-sm opacity-90">यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ।</p>
    </div>  

    <!-- Validation Errors -->
    <jet-validation-errors class="mb-6 mx-auto text-center" />
    {{this.page.errors}}

    <!-- Voting Form -->
    <form @submit.prevent="submit" class="text-center mx-auto mt-8">
        <!-- Voting Options -->
        <div v-if="!this.no_vote_option" 
             v-for="(post_id, pId) in candidate_post_ids(candidacies.data)" 
             :key="pId"
             :class="[pId%2==0? 'bg-blue-50 border-blue-200': 'bg-gray-50 border-gray-200']"
             class="mb-6 p-6 rounded-xl border-2 shadow-md"> 
            <create-votingform 
                :candidates="select_candidates_for_a_post(candidacies.data, post_id)"
                @add_selected_candidates="this.form.selected_candidates[pId] = add_selected_to_form_submission(selectedArray=$event)">
            </create-votingform>
        </div>

        <!-- Agreement and Submit Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mt-8"> 
            <!-- Agreement Section -->
            <div class="border-2 border-blue-300 rounded-lg p-6 mb-6 bg-blue-50"> 
                <!-- Header -->
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="text-3xl mb-2">✅</div>
                    <h3 class="text-xl font-bold text-red-700 mb-1">Button for Agreement</h3> 
                    <p class="text-lg font-semibold text-red-700">मतदान गरेको स्विकार</p>  
                </div>

                <!-- Checkbox -->
                <div class="flex justify-center mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="checkbox"
                            :id="agree_button"
                            :name="agree_button"
                            :value="true"
                            v-model="form.agree_button"
                            class="w-5 h-5 text-blue-600 border-2 border-gray-400 rounded focus:ring-blue-500 focus:ring-2"
                        />
                        <span class="ml-3 text-lg font-medium text-gray-900">I agree to the terms</span>
                    </label>
                </div> 

                <!-- Agreement Text -->
                <div class="bg-white rounded-lg p-4 border border-gray-200 mb-4">
                    <p class="text-gray-700 mb-3 leading-relaxed">
                        By clicking this button, I conform that I have chosen the candidates correctly and I followed the online rules to vote the candidates.
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        यो बटनमा थिचेर मैले माथि छाने आनुसार मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु।
                    </p> 
                </div>

                <!-- Checkbox Error -->
                <div v-if="errors.agree_button" class="text-red-600 text-sm mb-4 bg-red-50 p-2 rounded">
                    {{ errors.agree_button }}
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-xl py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                    :disabled="!form.agree_button"
                    :class="{ 'opacity-50 cursor-not-allowed': !form.agree_button }"
                >
                    <span class="mr-2">🗳️</span>
                    Submit Your Vote
                </button>
            </div>
        </div>

        <!-- Form Validation Errors -->
        <div class="mx-auto text-center mt-6">
            <jet-validation-errors class="mb-4 mx-auto text-center" />
        </div>
    </form>
</div></app-layout>
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