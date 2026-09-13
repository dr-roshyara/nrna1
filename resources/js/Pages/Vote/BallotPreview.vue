<template>
    <CreateVotingPage
        :national_posts="national_posts"
        :regional_posts="regional_posts"
        :user_name="user_name"
        :user_id="user_id"
        :election="election"
        preview-mode
    />
    <!-- slug / useSlugPath are simply never passed — CreateVotingPage's own
         prop defaults (null / false) apply, and preview-mode is hardcoded
         true here, the ONLY place that ever sets it. -->
</template>

<script>
import CreateVotingPage from './CreateVotingPage.vue'

export default {
    name: 'BallotPreview',

    components: { CreateVotingPage },

    props: {
        national_posts: { type: Array, default: () => [] },
        regional_posts: { type: Array, default: () => [] },
        user_name: { type: String, required: true },
        user_id: { type: [String, Number], required: true },
        election: { type: Object, default: null },
    },
}
</script>
