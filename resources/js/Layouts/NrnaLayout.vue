<template>
    <div class="flex flex-col"> 
        <div class="flex"> 
            <div class=" flex flex-left  p-4  bg-blue-600 md: w-full rounded border border-blue-100">
                <!-- <div id="logo" style="background-image: url('/logo-2.png')"> </div> -->
                <div id="logo" class="w-20">          
                </div> 
                <!-- <div> <link href="#"> <img  :src ="image1"  style="width:100%;" alt="slogan" /> </link> </div>   -->
                <div id="slogan" class="w-96">  </div>  
            </div> 
        </div> 
     <!-- middle  -->
       <slot> </slot> 
       <!-- footer  -->
     <nrna-footer class="px-4"> </nrna-footer>
    </div> 

</template>
<script>
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
</style>