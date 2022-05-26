<template>
    <div class="flex flex-col rounded-sm bg-indigo-800">
        <div
            class="md:flex-warp item-center md: flex w-full flex-col justify-center rounded border border-blue-800 p-2"
        >
            <div class="flex-center flex items-center justify-between">
                <logo> </logo>
                <div class="ml-4 w-3/4 px-2">
                    <div
                        class="flex flex-row items-center justify-between space-x-3 sm:mt-0"
                    >
                        <div class="flex flex-col">
                            <h4
                                class="mx-auto py-2 text-xl font-bold text-gray-50 md:text-2xl"
                            >
                                Non Resident Nepali Association
                            </h4>
                        </div>
                        <div class="block md:hidden">
                            <menu-icon></menu-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Logo from "@/Components/Header/Logo.vue";
import MenuIcon from "@/Components/Header/Menu.vue";
export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
    },
    components: {
        Logo,
        MenuIcon,
    },
};
</script>
