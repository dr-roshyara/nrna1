<template>
    <nrna-layout>
        <app-layout>
          <!-- {{candidacies.data}}   -->
          <div v-for ="(post_id, pId) in candidate_post_ids(candidacies.data)" :key="pId"> 
            <create-votingform :candidates=" select_candidates_for_a_post(candidacies.data,post_id)"> 

          </create-votingform>

          </div>
        </app-layout>
    </nrna-layout>
</template>
<script>
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'    
 import  CreateVotingform from '@/Pages/Vote/CreateVotingform.vue'
export default {
components:{
    AppLayout,
    NrnaLayout,
    CreateVotingform

},
props:{
    candidacies: Array
},
data(){
    return {
        // candidate_post_ids =[]
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
        candidate_post_ids(candidacies){
       let $post_ids =[] 
       candidacies.forEach(candidate =>{
          if(candidate.post_id){
                $post_ids.push(candidate.post_id)
          } 
          });
       return $post_ids.filter(this.onlyUnique);    
        },
        onlyUnique(value, index, self) {
             return self.indexOf(value) === index;
        },

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
