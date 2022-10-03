<template>
    <social-layout>
        <div class="flex min-h-screen flex-col items-center">
            <div>
                If you are an authorized person of NRNA Committee and can submit
                the financial report, you can do so by clicking on the
                corresponding links?
            </div>
            <div class="mx-auto py-2 text-gray-800">
                <div class="border-b-slate-200 py-2">
                    <p class="mx-auto mb-1 text-left text-xl">
                        For income statement
                    </p>
                    <JetButton class="w-64 py-4">
                        <a href="/finance/income/create" class="mx-auto">
                            Income Statement</a
                        >
                    </JetButton>
                </div>
                <div class="flex-start mt-2 flex flex-col py-2">
                    <p class="mb-1 text-xl">For Expenditure</p>
                    <JetButton class="w-64 py-4">
                        <a href="/finance/outcome/create" class="mx-auto">
                            Expenditure Statement</a
                        >
                    </JetButton>
                </div>
            </div>
        </div>
    </social-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/ProfileLayout";
import SocialLayout from "@/Layouts/SocialLayout";

import JetButton from "@/Jetstream/Button";

export default {
    components: {
        NrnaLayout,
        SocialLayout,
        JetButton,
    },
};
</script>
