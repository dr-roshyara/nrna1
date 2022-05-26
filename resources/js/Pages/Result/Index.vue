<template>
    <nrna-layout>
        <div
            v-for="(post, pIndx) in posts"
            :key="pIndx"
            class="my-4 flex flex-col justify-center border border-lime-300 py-2 shadow-xl"
        >
            <candidate
                :post="post"
                :final_result="final_result[0]"
                :class="{ 'pIndx%5==0': 'bg-blue-100' }"
            >
            </candidate>
        </div>
    </nrna-layout>
</template>

<script>
//   import { Bar } from 'vue-chartjs';
import NrnaLayout from "@/Layouts/ElectionLayout";
import Candidate from "@/Pages/Result/Candidate";

export default {
    components: {
        NrnaLayout,
        Candidate,
    },
    props: {
        final_result: Object,
        candidates: Object,
        posts: Object,
    },
};
</script>
