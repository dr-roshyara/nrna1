<template>
    <div class="flex flex-col bg-indigo-800 rounded-sm">
        <div class="flex flex-col  md:flex-warp
            justify-center p-2
            item-center
              md: w-full rounded border border-blue-800">

            <div class="flex flex-center  items-center justify-between">
                <logo> </logo>
                <div  class="ml-4 px-2 w-3/4">
                    <div class="flex flex-row  justify-between  items-center   sm:mt-0 space-x-3">
                        <div class="flex flex-col">
                            <h4 class=" text-gray-50 py-2 mx-auto font-bold text-xl md:text-2xl ">
                                Non Resident Nepali Association
                            </h4>
                        </div>
                          <div >
                            <menu-icon></menu-icon>
                          </div>

                    </div>
                </div>
            </div>

         </div>
    </div>
</template>
<script>
import Logo from "@/Components/Header/Logo.vue"
import MenuIcon from "@/Components/Header/Menu.vue"
export default {
    props:{
        canLogin: Boolean,
        canRegister: Boolean,
    },
    components:{
        Logo,
        MenuIcon
    }
}
</script>
