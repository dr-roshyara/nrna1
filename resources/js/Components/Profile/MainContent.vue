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
         class=" w-24 h-24 object-cover rounded-full -translate-x-1/2 translate-y-1/2">

    </div>
    <div @click="editBackground=!editBackground"
        class="absolute bottom-0 ml-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>

    </div>
    <div class="absolute z-10 bottom-0  translate-y-0 bg-blue-100 rounded w-full h-full"
        v-if="editBackground">
          <div class="px-2  ">
               <ul>
                   <li class="py-2 mb-2 flex flex-wrap items-center">
                       <svg class="h-8 w-8 text-blue-600"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                        </svg>   <span>  Upload new picture </span></li>
                    <li class="py-2 mb-2"> Select exiting picture</li>
               </ul>


            </div>
    </div>


  </section>
  <section class="mx-2">
  <div class="flex  justify-between items-start   pt-2 text-gray-900">
      <div class="flex flex-col">

          <!-- <p class="text-sm md:text-lg tracking-tight "> Member since 2002 </p> -->
      </div>
      <div>
        <a href="" class="bg-blue-500 rounded-full shadow py-2 px-4 text-white text-xs"> Add me</a>
       </div>

  </div>
  <div class="py-2 text-gray-900">
          <h2 class="text-bold   text-sm md:text-2xl mb-0"> {{user.name}}</h2>
        <p v-if="user.country">
           Living in: <span v-if="user.city"> {{user.city}} </span>
            <span v-if="user.country" class="">, {{user.country}} </span>
        </p>
        <p v-if="user.designation" > Designation: {{user.designation}}</p>
  </div>
  </section>
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
    },
    data(){
        return {
          editBackground:false,
        }
    }

}
</script>
