<template>
      <div class="flex flex-col">
        <div class="flex flex-col  md:flex-row
            justify-center px-4 py-2 lg:py-4
            item-center
            bg-blue-600 md: w-full rounded border border-blue-100">
            <div class="flex flex-start space-x-4 items-center ">
                <!-- <div id="logo" style="background-image: url('/logo-2.png')"> </div> -->
                <div id="logo" class="w-24 h-20">
                  <a href="/">
                      <img class="object-cover" src="/images/logo-2.png" width="200" height="200" />
                  </a>
                </div>
                <!-- <div> <link href="#"> <img  :src ="image1"  style="width:100%;" alt="slogan" /> </link> </div>   -->
                <div id="slogan" class="ml-4 px-2 w-96">
                  <a href="/">  <img class="object-cover" src="/images/slogan.png" /> </a>
                </div>
            </div>
             <div class="hidden sm:block m-auto text-white font-bold text-xl">
                <!-- <a href="/"> Non Resident Nepali Association </a> -->

             </div>
             <div class="w-44 m-auto h-auto md:h-32 text-white
                font-bold align-bottom sm:hidden md:block">
                <inertia-link v-if="canLogin"
                  :href="route('login')"
                class="text-sm underline">
                    Log in
                </inertia-link>

                <inertia-link v-if="canRegister"
                  :href="route('register')"
                  class="ml-4 text-sm underline">
                    Register
                </inertia-link>
               <!-- <img class="object-cover" src="/images/germanynepal.png" width="200" height="200"/> -->
            </div>
        </div>
         <div class="mt-2">
          <div class="ml-2 p-2">
            <inertia-link href="/student/create" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> IT Classes</inertia-link>
            </div>

         </div>
         <!-- start  -->
            <!--        <inertia-link href="#" class="m-auto" >
                      <div class=" flex flex-col h-64 py-2 m-auto text-center px-10 w-full">
                       <p class="w-full text-center text-gray-900 font-bold">
                         VOTE  HERE </p>
                       <img src="/storage/images/ballot.png" width="200" height="200" />
                    </div>

            </inertia-link>-->
                    <!-- <inertia-link href="/dashboard" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Dashboard</inertia-link>
                    <inertia-link href="/voters/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Voter's List </inertia-link>
                    <inertia-link href="/users/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Member's List </inertia-link>
                    <inertia-link href="/candidacies/index" class=" p-2 mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> List of Candidates </inertia-link>
                    <inertia-link href="/candidacy/create" class=" p-2 bg-red-50 font-bold text-gray-900 rounded border border-right "> Candidacy Form </inertia-link>
                    <inertia-link href="/posts/index" class=" p-2  mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> Name of Posts</inertia-link>
                     <inertia-link href="/notices/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> List of Notices</inertia-link> -->


         <!-- end  -->
        </div>
</template>
<script>
export default {
    props:{
        canLogin: Boolean,
        canRegister: Boolean,
    }
}
</script>
