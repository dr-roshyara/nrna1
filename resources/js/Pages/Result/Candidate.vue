<template>
     <div class="w-full flex flex-col justify-center px-2 md:px-6 py-4 shadow-inner ">
          <div class="text-center my-2 mx-auto text-xl font-bold text-gray-900">  {{post.name}}</div>
        <bar-chart class="w-full m-auto" :entries="entries" :columns="columns" :format="format"> </bar-chart> 
        
     </div>
</template>

<script>
import BarChart from "@/Pages/Result/BarChart";
import {reactive } from 'vue'
export default {
    components:{
        BarChart 
    },
    props:{
      post: Object,
      final_result:Object
  },
 setup(props) {
  
    
    const columns = ["letter", "frequency"]
    const format = "%";

     function get_vote_count(post_id, candidacy_id){
          let _key = post_id+"_and_"+candidacy_id;
            
           return props.final_result[_key];         

     }
    function get_vote_percent(post_id, candidacy_id){
          let _key      = post_id+"_and_"+candidacy_id;
          let total     =props.final_result[post_id];
          let voteCout  =props.final_result[_key];
           return  voteCout/total;
          

      }
       function get_vote_result(candidate){
              
              let result= {
                "candidacy_id": candidate["candidacy_id"],
                "user_id":      candidate.user_id,
                "post_id":      candidate.post_id,                
                "name":         candidate.user.name,
                "vote_count":   get_vote_count(candidate.post_id, candidate.candidacy_id),
                "value":         get_vote_percent(candidate.post_id, candidate.candidacy_id)
              }
                         
          return result;
      }
       function get_candidates(candidates){
           
           let result =[];
            for (let i=0;i<candidates.length;i++){
              result.push(get_vote_result(candidates[i]));
            }
           return result;
        
      }
     const entries = reactive(get_candidates(props.post.candidates));       
    return {
      entries,
      columns,
      format
    }
  }
}
</script>