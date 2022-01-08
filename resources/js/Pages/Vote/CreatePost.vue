<template>
   <div  v-if ="post.candidates.length"
        class="flex flex-col border border-3 border-blue-600 mx-2 py-4 px-6 bg-gray-50 shadow-md my-4">      
          <div class="flex flex-col text-xl font-bold text-gray-900 mx-auto text-center justify-center">
                      <label> Please choose <span>   {{post.required_number}} </span>   Candidate as the <span class="text-gray-900 font-bold"> {{post.name}} </span>. </label> 
                      <label class="p-2"> कृपया <span>   {{post.required_number}} </span> जना लाई  <span class="text-gray-900 font-bold"> {{get_nepali_name(post.post_id)}} </span> चुन्नुहोस ।  </label>   
          </div>  
   
       <!-- Display the candidates here  -->

        <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">  
                <!-- {{candidates.length}} -->
                <!-- {{post.candidates}} -->
            <div  v-for="(candidate, candiIndx) in post.candidates" :key="candiIndx"  
                class="flex flex-col justify-center p-4 mb-2 text-center  
                border border-gray-100 rounded"> 
               
                    <!-- {{disabledVec}} -->

                    <show-candidate 
                        :candidacy_image_path ="candidate.image_path_1"
                        :post_name            ="post.name"   
                        :post_nepali_name     ="get_nepali_name(post.post_id)"  
                        :candidacy_name       ="candidate.user.name">
                    </show-candidate>              
                        <!-- here starts  voting form -->
                    <div class="px-2 py-2">
                        <input 
                            type      ="checkbox"
                            :id       ="candidate.candidacy_id"
                            :name     ="post.name"
                            :value    ="candidate.candidacy_id"  
                            class     ="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 
                                focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            v-model= "selectedVec"                           
                            :disabled  ="disabledVec[candiIndx]"
                            v-on:change   ="inform_selected_candidates()"
                             @change    ="updateBoxes(post.candidates, 
                                    this.selectedVec, 
                                    this.totalTicks)"  
                        />
                    
                    </div> 

                 <!-- here the voting form ends  -->

                    <!-- here the voting form ends  -->
                    
            </div>
                            

        </div>

        <!-- Here ends the display  -->
        <div  class="mb-4 p-2 text-center mx-auto" 
             v-if="selectedVec.length"> 
            <p>     You have selected 
                <span class="font-bold text-indigo-600"> 
                          {{find_selected_name(this.post.candidates,this.selectedVec)}} </span> 
                    as <span class="font-bold text-lg text-gray-900"> 
                        {{post.name}} 
                    </span>  of NRNA !
                </p>
             
         </div> 
   
   </div>         

</template>

<script>
import ShowCandidate from '@/Shared/ShowCandidate'
export default {
 props:{
     post:Array 
 },
 components:{
     ShowCandidate
 },

 data(){
    return {
     selectedVec: [],
     disabledVec: this.fillArray(false, this.post.candidates.length),
     totalTicks:{
            limit: 1,
            ticks:[]
    },

    }
 },
methods:{
       inform_selected_candidates(){
        this.$emit('add_selected_candidates', this.selectedVec);
    }, 
  
    find_selected_name(candiVec, selectedVec){
        // console.log(candiVec)
        // console.log(selected_id)
         let selected_names =[]
         candiVec.forEach(box => {
          // console.log("candidacy id: "+box.candidacy_id) 
          
          if (selectedVec.includes(box.candidacy_id))  selected_names.push(box.user.name);
        });
        let  elected_names =selected_names.join(', ');
        return elected_names

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
        tickObj.limit =this.post.required_number

        /**
         * 
         *Get the selected candidates in ticks
         */
        tickObj.ticks = candiVec.filter(box => selectedVec.includes(box.candidacy_id));
       
        // console.log("tick length: "+tickObj.ticks.length);
        // console.log(tickObj.ticks);
       
      // re-enable checkboxes if back under the limit...
      if (tickObj.ticks.length < tickObj.limit) {
         candiVec.forEach((box,index) => {
          if (!selectedVec.includes(box.post_id)) this.disabledVec[index] = false;
        });
      }

      // disable empty checkboxes if at the limit...
      if (tickObj.ticks.length == tickObj.limit) {
        candiVec.forEach((box,index) => {
          // console.log("candidacy id: "+box.candidacy_id)
        //   console.log(selectedVec); 
          if (!selectedVec.includes(box.candidacy_id)) this.disabledVec[index]  = true;
        });
      }
        // console.log(this.disabledVec);
    },
    
     get_nepali_name(post_id){
       //president 
        if(post_id=="2021_01"){
            return "आइसीसी अध्यक्ष" 
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
          if(post_id=="2021_07"){
            return "महिला सचिव" 
        }
        if(post_id=="2021_49"){
            return "प्याट्रोन काउन्सिल सदश्य" 
        }
        if(post_id=="2021_08"){
            return "कोषाध्यक्ष" 
        }
         if(post_id=="2021_09"){
            return "सह-कोषाध्यक्ष" 
        }
          if(post_id=="2021_10"){
            return "महिला सह-कोषाध्यक्ष" 
        }
         if(post_id=="2021_11"){
            return "महिला संयोजक" 
        }
        if(post_id=="2021_12"){
            return "युवा संयोजक" 
        }
        if(post_id=="2021_13"){
            return "क्षेत्रिय  संयोजक युरोप" 
        }
         if(post_id=="2021_14"){
            return "क्षेत्रिय  संयोजक अमेरिकाज" 
        }
          if(post_id=="2021_15"){
            return "क्षेत्रिय  संयोजक ओसियाना"  
        }
          if(post_id=="2021_16"){
            return "क्षेत्रिय  संयोजक एसिया प्यासिफिक" 
        }
          if(post_id=="2021_17"){
            return "क्षेत्रिय  संयोजक मध्य एसिया" 
        }
          if(post_id=="2021_18"){
            return "क्षेत्रिय  संयोजक अफ्रिका" 
        }
          if(post_id=="2021_19"){
            return "क्षेत्रिय  महिला संयोजक युरोप" 
        }
          if(post_id=="2021_20"){
            return "क्षेत्रिय  महिला संयोजक अमेरिकाज" 
        }
         if(post_id=="2021_20"){
            return "क्षेत्रिय  महिला संयोजक ओसियाना" 
        }
     },
        fillArray(value, len) {
        var arr = [];
            for (var i = 0; i < len; i++) {
                arr.push(value);
            }
            return arr;
        }

}//end of mehtods
 

}
</script>