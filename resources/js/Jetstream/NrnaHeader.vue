<template>
      <div class="flex flex-col sm:flex-col md:flex-row  justify-center p-4 item-center
         bg-blue-600 md: w-full rounded border border-blue-100"> 
            <div class="flex flex-start spac-x-4 items-center ">
                <!-- <div id="logo" style="background-image: url('/logo-2.png')"> </div> -->
                <div id="logo" class="w-24 h-20"> 
                  <a href="/">  <img class="object-cover" src="/images/logo-2.png" width="200" height="200" />
                  </a>
                </div> 
                <!-- <div> <link href="#"> <img  :src ="image1"  style="width:100%;" alt="slogan" /> </link> </div>   -->
                <div id="slogan" class="ml-4 px-2 w-96">  
                  <a href="/">  <img class="object-cover" src="/images/slogan.png" /> </a>
                </div>  
            </div> 
             <div class="hidden sm:block m-auto text-white font-bold text-xl"> 
                <a href="/"> NRNA GERMANY </a>
             </div>
             <div class="w-44 m-auto h-32">
               <img class="object-cover" src="/images/germanynepal.png" width="200" height="200"/>
            </div> 
        </div> 
</template>
<script>
export default {
    
}
</script>