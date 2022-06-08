<template>
    <div
        class="mx-auto mb-2 flex flex-col items-center justify-center px-2 shadow-inner sm:w-full md:max-w-screen-md"
    >
        <a title="NRNA Netowrk" href="/register">
            <h1
                class="px-auto mx-auto my-4 py-4 text-center text-2xl font-bold text-rose-800"
            >
                JOIN NRNA NETWORK
            </h1>
        </a>

        <div
            class="mx-auto grid grid-cols-1 place-content-center justify-center pb-2"
        >
            <p>
                Are you interested to join the Nepalese network and interact
                with the Nepali Diaspora? Please choose the corresponding
                button.
            </p>
            <div
                class="my-auto mb-10 grid grid-cols-2 place-items-center gap-x-3 space-y-8 py-4"
            >
                <!-- Registration  -->
                <a href="/register" class="my-auto mb-0">
                    <jet-button> Registration </jet-button>
                </a>
                <!-- Direct Button  -->
                <a href="/login" class="my-auto mb-0">
                    <jet-button> Direct Login </jet-button>
                </a>

                <!-- login with google  -->
                <a href="/login/google" class="my-auto mb-0">
                    <img
                        src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
                    />
                </a>
                <!-- login in with facebook  -->

                <a href="/login/facebook" class="my-auto mb-0 rounded">
                    <div
                        class="text-bold rounded-lg text-sm uppercase shadow"
                        style="
                            background: #4267b2;
                            color: #ffffff;
                            padding: 10px;
                            width: 100%;
                            text-align: center;
                            display: block;
                            border-radius: 3px;
                        "
                    >
                        Facebook Login
                    </div>
                </a>
            </div>
            <p>
                Inside this network, you can share, communicate, and interact
                with the Nepalese living around the globe. Become a member to
                get engaged with the Global Diaspora for the betterment of Nepal
                and Nepalese communities around the world.
            </p>
        </div>
    </div>
</template>
<script>
import JetButton from "@/Jetstream/Button";

export default {
    components: {
        JetButton,
    },
};
</script>
