<template>
    <div class="mx-auto mt-2 max-w-xl py-2">
        <div
            v-for="openion in openions"
            class="relative my-2 rounded-md border p-2 shadow-md"
        >
            <!-- {{ openions }} -->
            <p class="border-b border-slate-100 pt-1 font-bold">
                <a
                    class="text-blue-800"
                    :href="'/user/' + openion.user.user_id"
                    >{{ openion.user.name }}</a
                >
                says
            </p>
            <p class="px-1 pb-1 text-center font-bold text-blue-800">
                {{ openion.title }}
            </p>
            <p class="mb-4 pb-2">{{ openion.body }}</p>
            <p
                v-if="openion.hash_tag"
                class="absolute bottom-0 ml-0 text-sm font-bold tracking-tighter text-teal-800"
            >
                #{{ openion.hash_tag }}
            </p>

            <!-- {{ openion }} -->
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            openions: {},
        };
    },
    mounted() {
        // const token = localStorage.getItem("test_token");
        // const headers = {
        //     headers: {
        //         Authorization: `Bearer ${token}`,
        //         Accept: "application/json",
        //     },
        // };
        axios.get(this.route("openions.index")).then((response) => {
            this.openions = response.data;
            console.log(this.openions);
        });
    },
    methods: {},
};
</script>
