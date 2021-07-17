<template>
    <div class="flex flex-col"> 
     test

    </div>   

</template>
<script>


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