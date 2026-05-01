<template>
    <app-layout>
        <!-- Breadcrumb Schema for SEO -->
        <!-- Event Schema for Elections -->
        <EventSchema />

        <template #header>
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ electionName || 'Election Result' }}
            </h2>
        </template>
        <div>Election result post name , position , no of votes lcc wise</div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import EventSchema from "@/Components/EventSchema.vue";
import { useMeta } from "@/composables/useMeta";

export default {
    components: {
        AppLayout,
        EventSchema,
    },
    props: {
        election: Object,
        results: Object,
    },
    created() {
        /**
         * SEO Meta Tags for Election Results
         *
         * Dynamically sets page-level meta tags based on election data.
         * Updates title and description with election name and result summary.
         */
        useMeta({
            pageKey: 'election.result',
            params: {
                electionName: this.election?.name || 'Election',
            }
        });
    },
    computed: {
        electionName() {
            return this.election?.name || 'Election Result';
        }
    }
};
</script>

