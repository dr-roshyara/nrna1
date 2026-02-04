<template>
    <VotingLayout
        :election="election"
        page-title="Step 2: Confirm Agreement"
        :current-step="2"
        :total-steps="5"
    >
<div class="mt-6 text-center"> 
    <!-- <jet-validation-errors class="mb-4 mx-auto text-center" />  
     -->
    <!-- Just better styling for success message -->
    <div class="m-auto text-center bg-gradient-to-r from-green-500 to-blue-600 text-white py-6 px-8 rounded-xl shadow-lg max-w-4xl">  
        <div class="text-3xl mb-2">🎉</div>
        <p class="text-xl font-bold mb-2">Congratulation {{user_name}}!</p> 
        <p class="text-lg mb-2">You have given the correct voting code. You can Vote now!</p>
        <p class="mb-3">Please select the correct candidates of your choice</p>
        <p class="text-sm opacity-90">यहाँले दिएको भोटिङ कोड सही भएको प्रमाणित भाईसकेको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ।</p>
    </div>  
</div>

<!-- Better error styling -->
<div v-if="$page.props.errors.agree_button" class="max-w-4xl mx-auto mb-4">
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
        {{ $page.props.errors.agree_button }}
    </div>
</div>

<form @submit.prevent="submit" class="text-center mx-auto mt-10 max-w-6xl px-4">
    
    <!-- Agreement section - better styling -->
    <div class="flex flex-col items-center mx-auto my-8 w-full max-w-4xl bg-white rounded-xl shadow-lg"> 
        <div class="flex flex-col w-full border-2 border-blue-300 mx-2 my-4 py-6 px-6 rounded-lg bg-blue-50"> 
            
            <!-- Header -->
            <div class="flex flex-col items-center justify-center py-2 mb-4">
                <div class="text-3xl mb-2">✅</div>
                <p class="text-xl font-bold text-red-700">Button for Agreement</p> 
                <p class="text-lg font-semibold text-red-700">मतदान गरेको स्विकार</p>  
            </div>
            <!-- Checkbox -->
             <div class="flex justify-center mb-4">
        <label class="flex items-center cursor-pointer bg-white p-3 rounded-lg border" 
               :class="{'border-gray-300': !$page.props.errors.agree_button, 'border-red-500': $page.props.errors.agree_button}">
            <input 
                type="checkbox"
                id="agree_button"
                name="agree_button"
                :value="true"
                v-model="form.agree_button"
                @change="clearAgreeError" 
                class="w-5 h-5 text-blue-600 border-2 border-gray-400 rounded focus:ring-blue-500 focus:ring-2"
            />
            <span class="ml-3 text-lg font-medium text-gray-900">I agree / म स्विकार  गर्छु।
            
            </span>
        </label>
    </div> 
    
            <!-- Agreement text -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 mb-4">
                <p class="text-gray-700 mb-3 leading-relaxed">By clicking this button, I conform that I have chosen the candidates correctly and I followed the online rules to vote the candidates.</p>
                <p class="text-gray-700 text-sm leading-relaxed">यो बटनमा थिचेर मैले माथि छाने आनुसार मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु।</p> 
            </div>
            
            <!-- Error -->
            <div v-if="$page.props.errors.agree_button" class="mb-4">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
                    {{ $page.props.errors.agree_button }}
                </div>
            </div>

            <!-- Submit button -->
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-xl py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
            >
                <span class="mr-2">🗳️</span>
                Submit
            </button>
        </div>
    </div>
    
    <!-- Final errors -->
    <div class="mx-auto text-center">
        <jet-validation-errors class="mb-4 mx-auto text-center" />
    </div>
</form>
    </VotingLayout>
</template>
<script>
 import VotingLayout from '@/Components/Election/VotingLayout.vue'
 import CreatePost from '@/Pages/Vote/CreatePost.vue'
 import { useForm } from '@inertiajs/inertia-vue3'
import JetInput from '@/Jetstream/Input'
import ShowCheckbox from "@/Shared/ShowCheckbox";
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default {
components:{
    VotingLayout,
    CreatePost,
    JetValidationErrors

},
props:{
     national_posts: Array,
     regional_posts: Array,
     user_name : String,
     user_id : Number,
     election: {
        type: Object,
        default: null
     },
     errors: Object

},
setup (props) {
    const form = useForm({
      user_id:              props.user_id,
    //   president:            [],
      natioanal_selected_candidates:  new Array(props.national_posts.data.length),
      regional_selected_candidates:   new Array(props.regional_posts.data.length),
      no_vote_option:       false,
      agree_button:         false,     
      
    })
     function clearAgreeError() {
            if (form.agree_button && props.errors?.agree_button) {
                // Remove the agree_button error when checkbox is checked
                props.errors.agree_button = null;
            }
        }

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

     function add_selected_to_form_submission(curPost,selectedArray){
        //    console.log(typeof(selectedArray));
            console.log(selectedArray);
            // console.log("form selected");
            // console.log(form.president);
             let candiVec ={};
              candiVec.post_name =curPost.name;
              candiVec.post_id   =curPost.post_id; 
              candiVec.region    =curPost.state_name;   
           let ids = Object.values(selectedArray);
        //    console.log(ids.length);
         
           let candiArray =curPost.candidates; 
           let selected_candis =[];
           if(ids.length==0){
               candiVec.candidates =[];
            //    console.log(candiVec);
           }else{
                 
                selected_candis = candiArray.filter(candidate => {
                //   console.log(ids.includes(candidate.candidacy_id));
                  return ids.includes(candidate.candidacy_id)
                });
              if(selected_candis.length>0){
                     //  console.log(selected_candis);
                    // console.log(selected_candis[0]);
                    candiVec.candidates =selected_candis;                   
                }   
                // console.log(form.selected_candidates);

           } 
           return candiVec;    
          
        }
        

    return { form, submit, add_selected_to_form_submission, candidate_post_ids , clearAgreeError}
  },
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