<template>
 <main class="profile-page ">
  <section class="relative block flex flex-col w-full mb-1">
   <div class="">
    <img src="images/background_images/nab_raj_roshyara.JPG" class="object-cover w-full rounded max-h-40 md:max-h-80">
    <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px" style="transform: translateZ(0px)">
      <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
        <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
      </svg>


    </div>
   </div>
    <div class="absolute  bottom-0 mr-2" style="left:50%;">
        <img alt="..." src="images/avatar/nab_raj_roshyara_1.JPG"
         class=" w-20 h-20 object-cover rounded-full -translate-x-1/2 translate-y-1/2">

    </div>



  </section>
  <div class="flex  justify-between items-center mb-2 mx-2">
      <div class="flex flex-col">
          <h2 class="text-bold text-sm md:text-2xl mb-0">Dr. {{user.name}}</h2>
          <p v-if="user.country" class="">{{user.country}} </p>
          <p class="text-sm md:text-lg tracking-tight "> Member since 2002 </p>
      </div>
      <div>
        <a href="" class="bg-blue-500 rounded-full shadow py-2 px-4 text-white text-xs"> Add me</a>
       </div>

  </div>
  <section class="status border border-lime-300 my-2">
    status
    Currently: I am working on nrna profile
  </section>
  <section class="pinned border border-lime-300 mb-2">
      pinned text
  </section>

  <section class="aboutme border border-lime-300 mb-2">
  about me
  </section>

  <section class="aboutme border border-lime-300 mb-2">
    my review
  </section>
 <section class="review border border-lime-300 mb-2">
    my review
  </section>

</main>
</template>
<script>
export default{
    props:{
        user:Array
    }

}
</script>
