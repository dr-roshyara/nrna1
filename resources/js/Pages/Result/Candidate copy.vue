<template>
      <div class="mx-auto">
         <p>  Total Voters: {{final_result.total_votes}}  </p>          
         <p>  Total Counted Votes: {{final_result.total}}  </p>    
      </div>
        <!-- Here comes the result   -->
       <div class="mx-auto"> 
          <div> 
              <p> post : {{post.name}}</p> 
              <p> total votes  :{{ get_total_post_vote(post.post_id) }}</p> 
          </div>
           <BarChart  :entries="entries" :columns="columns" :format="format" />
      </div> 
       <!-- total :{{final_result[post.post_id]}} -->
      <div> 
          <p> post id: {{post.post_id}}</p> 
          <p> post Name : {{post.name}}</p> 
         <p> total votes  :{{ get_total_post_vote(post.post_id) }}</p> 
          <p> candidate votes: </p> 
         <!-- <p> first candidate: </p> -->
      </div> 
        {{   get_candidates(post.candidates)}}
        <br> <hr><br>
    <!-- {{(final_result) }} -->
    {{posts}}

</template>
<script>
 
//  import { defineComponent } from 'vue' 
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
    // const entries = [
    //   {name: "E", value: 0.12702},
    //   {name: "T", value: 0.09056},
    //   {name: "A", value: 0.08167},
    //   {name: "O", value: 0.07507},
    //   {name: "I", value: 0.06966},
    //   {name: "N", value: 0.06749},
    //   {name: "S", value: 0.06327},
    //   {name: "H", value: 0.06094},
    //   {name: "R", value: 0.05987},
    //   {name: "D", value: 0.04253},
    //   {name: "L", value: 0.04025},
    //   {name: "C", value: 0.02782},
    //   {name: "U", value: 0.02758},
    //   {name: "M", value: 0.02406},
    //   {name: "W", value: 0.0236},
    //   {name: "F", value: 0.02288},
    //   {name: "G", value: 0.02015},
    //   {name: "Y", value: 0.01974},
    //   {name: "P", value: 0.01929},
    //   {name: "B", value: 0.01492},
    //   {name: "V", value: 0.00978},
    //   {name: "K", value: 0.00772},
    //   {name: "J", value: 0.00153},
    //   {name: "X", value: 0.0015},
    //   {name: "Q", value: 0.00095},
    //   {name: "Z", value: 0.00074}
    // ]
    const entries = get_candidates(post.candidates);
    const columns = ["letter", "frequency"]
    const format  = "%"
    
    
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
                "value":        get_vote_count(candidate.post_id, candidate.candidacy_id),
                "vote_percent": get_vote_percent(candidate.post_id, candidate.candidacy_id)
              }
              // console.log("Result")
              // console.log(result);              
          return result;
      }
      function get_candidates(candidates){
            // console.log(final_result);
            // console.log(candidates);
           let result =[];
            for (let i=0;i<candidates.length;i++){
              result.push(get_vote_result(candidates[i]));
            }
           return result;
        
      }

    return {
      entries,
      columns,
      format
    }
  },
  methods:{
      get_total_post_vote (post_id) {
        return this.final_result[0][post_id];
      },
       get_candidacy_from_key(key){
           let elems =key.split('_and_');
           if(elems.length==2){
             return elems[1];
           }
           return null;                   
       },

  }
   



}
</script>