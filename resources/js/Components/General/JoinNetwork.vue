<template>
    <div
        class="mx-auto flex flex-col px-2 shadow-inner sm:w-full md:max-w-screen-md mb-2"
    >
        <a title="NRNA Netowrk" href="/register">
            <h1
                class="px-auto mx-auto my-4 py-4 text-center text-2xl font-bold text-rose-800"
            >
                JOIN NRNA NETWORK
            </h1>
        </a>

        <p class="mx-auto pb-2">
            Are you interested to join the Nepalese network and interact with
            the Nepali Diaspora? Please choose the corresponding button.
             <div class="grid grid-cols-2 gap-x-3 py-4 mb-10 my-auto  space-y-8 justify-items-center">
                 <!-- Registration  -->
                 <a href="/register" class="my-auto mb-0">
                 <jet-button>
                     Registration
                     </jet-button>
                </a>
                <!-- Direct Button  -->
                 <a href="/login" class="my-auto mb-0 mx-auto">
                 <jet-button>
                     Direct Login
                    </jet-button>
                </a>

                <!-- login with google  -->
                <a href="/login/google" class="my-auto mb-0 mx-auto">
                        <img
                            src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
                        />
                </a>
                 <!-- login in with facebook  -->

                <a  href="/login/facebook" class="my-auto mb-0 mx-auto">
                    <div class="rounded-lg  uppercase text-bold text-sm"  style="background: #4267B2; color: #ffffff; padding: 10px; width: 100%; text-align: center; display: block; border-radius:3px;">
                        Login with Facebook
                    </div>
                </a>

            </div>
            Inside this network, you can share, communicate, and interact with
            the Nepalese living around the globe. Become a member to get engaged
            with the Global Diaspora for the betterment of Nepal and Nepalese
            communities around the world.
        </p>
    </div>
</template>
<script>
import JetButton from "@/Jetstream/Button";

export default {
   components:{
       JetButton
   }
};
</script>
