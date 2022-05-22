<template>
 <main class="profile-page ">
  <profile-image
    :user="user"
    :isLoggedIn="isLoggedIn">
  </profile-image>
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
// import ImageUpload  from "@/Components/Upload/ImageUpload.vue"
import ProfileImage  from "@/Components/Profile/ProfileImage.vue"

export default{
    props:{
        user:Array,
        isLoggedIn:Boolean
    },
    components:{
        // ImageUpload,
        ProfileImage
    },
    data(){
        return {
          editBackground:false,
          uploadBackground:false,
          uploadIcon :false,
          bgUrlValid:true ,
          iconUrlValid:true
        }
    },
    methods:{
        onBgImageError(){
            this.bgUrlValid= false
        },
        onIconImageError(){
            this.iconUrlValid=false;
        }

    }

}
</script>
