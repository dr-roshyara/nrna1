<template>
    <section class="container justify-center">
        <!-- <iframe
            class="responsive-iframe"
            id="google_calendar"
            src="https://calendar.google.com/calendar/embed?height=690&wkst=1&bgcolor=%233F51B5&ctz=Europe%2FBerlin&hl=en_GB&title&src=MWw4dnEyMGFqYzM1MGwxdWE4ZDR1NGR2azhAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&src=aHQzamxmYWFjNWxmZDYyNjN1bGZoNHRxbDhAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%237CB342&color=%23D81B60"
            style="border: solid 1px #777"
            frameborder="0"
            scrolling="no"
        ></iframe> -->
        <iframe
            class="responsive-iframe"
            src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=Europe%2FBerlin&mode=AGENDA&hl=en_GB&src=MWw4dnEyMGFqYzM1MGwxdWE4ZDR1NGR2azhAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%237CB342"
            style="border: solid 1px #777"
            width="100%"
            height="600"
            frameborder="0"
            scrolling="no"
        ></iframe>
    </section>
</template>

<script>
// import NrnaLayout from "@/Layouts/NrnaLayout";

export default {
    components: {},
};
</script>
<style scoped>
.container {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}
.responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    border: none;
}
</style>
