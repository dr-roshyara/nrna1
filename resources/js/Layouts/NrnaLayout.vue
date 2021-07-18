<template>
    <div class="flex flex-col min-h-screen">
         <nrna-header> </nrna-header> 
     <!-- middle  -->
       <slot class="flex-grow min-h-screen"> </slot> 
       <!-- footer  -->
     <nrna-footer class="px-4 "> </nrna-footer>
    </div> 

</template>
<script>
import NrnaHeader from "@/Jetstream/NrnaHeader"; 
import NrnaFooter from "@/Jetstream/NrnaFooter"; 

export default {
    props: {
        image2:String
    },
    data(){
        return {
            image1: "/storage/images/logo_nrna.jpg"
            //  image1: this.image2
        }
    },
    components:{
        NrnaHeader,
        NrnaFooter
    }
}

</script>
<style scoped>
 #slogan{
      background-image: url('/images/slogan.png');
      min-height:8rem;
      background-repeat: no-repeat;
 }
 #logo{
      background-image: url('/images/logo-2.png');
      min-height:8rem;
      background-repeat: no-repeat;
 }
 #germanynepal{
     background-image: url('/images/germanynepal.png');
      background-repeat: no-repeat;
      background-size: 300px 200px cover;

 }
</style>