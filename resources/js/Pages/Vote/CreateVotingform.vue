<template>
     <div id="first_vote_window" 
          class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
          <div class="flex flex-col text-xl font-bold text-gray-900 mx-auto text-center justify-center">
                      <label> Please choose <span>   {{get_required_number(this.candidates)}} </span>   Candidate as the <span class="text-gray-900 font-bold"> {{get_post_name(this.candidates)}} </span>. </label> 
                      <label class="p-2"> कृपया <span>   {{get_required_number(this.candidates)}} </span> जना लाई  <span class="text-gray-900 font-bold"> {{get_post_name(this.candidates)}} </span> चुन्नुहोस ।  </label>   
          </div>
                   
    <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
            <!-- {{candidates.length}} -->
            <!-- {{candidates}} -->
        <div  v-for="(candidate, candiIndx) in candidates" :key="candiIndx"  
             class="flex flex-col justify-center p-4 mb-2 text-center  
              border border-gray-100 rounded"> 
                <!-- {{candidate.image_path_1}}
                {{candidate.post.name}}
                {{candidate.user.name}} -->

                <show-candidate 
                    :candidacy_image_path ="candidate.image_path_1"
                    :post_name            ="candidate.post.name"   
                     :post_nepali_name     ="get_nepali_name(candidate.post_id)"  
                    :candidacy_name       ="candidate.user.name">
                </show-candidate>              
                <!-- here starts  voting form -->
                <div class="px-2 py-2">
                    <input 
                        type      ="checkbox"
                        :id       ="candidate.candidacy_id"
                        :name     ="candidate.post.name"
                        :value    ="candidate.candidacy_id"  
                        class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 
                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model= "selected"
                        @change    ="updateBoxes(candidates, 
                                    this.selected, 
                                    this.icc_memberTicks)" 
                        :disabled  ="candidate.disabled"
                        v-on:change   ="inform_selected_candidates()"     
                    />
                  
                </div> 

                 <!-- here the voting form ends  -->
                
        </div>
                        

    </div>
     <div  class="mb-4 p-2 text-center mx-auto" v-if="selected.length"> 
         You have selected 
                      <span class="font-bold text-indigo-600"> 
                          {{find_selected_name(this.candidates,selected)}} </span> 
                      as <span class="font-bold text-lg text-gray-900"> {{this.get_post_name(this.candidates)}} </span>  of NRNA !
             
    </div> 
     </div>                 

</template>

<script>
import ShowCandidate from '@/Shared/ShowCandidate'
export default {
 props:{
     candidates: Array
 },

 components:{
     ShowCandidate
 },
 data(){
    return {
     selected: [],
     icc_member_post_id:           "2021_01",
     icc_memberTicks:{
            limit: 1,
            ticks:[]
    },

    }
 },
 computed:{
    getSelectionLength(){        
            return this.selected.length;
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
      },
      

    
 },
 methods:{
       inform_selected_candidates(){
        this.$emit('add_selected_candidates', this.selected);
    },    
     get_nepali_name(post_id){
       //president 
        if(post_id=="2021_01"){
            return "आइसीसी अद्यक्ष" 
        }
        if(post_id=="2021_02"){
            return "उपाध्यक्ष" 
        }
        if(post_id=="2021_03"){
            return "महिला  उपाध्यक्ष" 
        }
        
        if(post_id=="2021_04"){
            return "युबा  उपाध्यक्ष" 
        }
         if(post_id=="2021_05"){
            return "महासचिव" 
        }
         if(post_id=="2021_06"){
            return "सचिव" 
        }
        
        
     },
     get_post_name(candiVec){
        //   console.log(candiVec[0]);
          return candiVec[0].post.name;
      },
      get_required_number(candiVec){
        //   console.log(candiVec[0]);
          return candiVec[0].post.required_number;
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
       /***
        * 
        * first determine how many ticks  are allwed .
        * The number of ticks are given in the post.required_number  
        * this is given in the required number of for each post . 
        * 
        */
        tickObj.limit =candiVec[0].post.required_number

        /**
         * 
         *Get the selected candidates in ticks
         */
        tickObj.ticks = candiVec.filter(box => selectedVec.includes(box.candidacy_id));
       
        // console.log("tick length: "+tickObj.ticks.length);
        // console.log(tickObj.ticks);
       
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
    
    find_selected_name(candiVec, selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_names =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id) 
          
          if (selectedVec.includes(box.candidacy_id))  selected_names.push(box.user.name);
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

    

    // here ends 
 }, //end of methods 

}
</script>