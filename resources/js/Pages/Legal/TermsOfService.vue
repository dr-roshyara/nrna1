<template>
    <div class="min-h-screen bg-neutral-50">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-8">
                <h1 class="text-4xl font-bold text-neutral-900 mb-8">Terms of Service</h1>

                <div class="prose prose-lg max-w-none text-neutral-700">
                    <p class="mb-6">
                        These Terms of Service ("Terms") govern your use of the Public Digit platform and services.
                        By accessing or using our platform, you agree to be bound by these Terms.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">1. Use License</h2>
                    <p class="mb-6">
                        Permission is granted to temporarily download one copy of the materials (information or software)
                        on Public Digit for personal, non-commercial transitory viewing only. This is the grant of a license,
                        not a transfer of title, and under this license you may not:
                    </p>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Modifying or copying the materials</li>
                        <li>Using the materials for any commercial purpose or for any public display</li>
                        <li>Attempting to decompile or reverse engineer any software contained on the platform</li>
                        <li>Removing any copyright or other proprietary notations from the materials</li>
                        <li>Transferring the materials to another person or "mirroring" the materials on any other server</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">2. Disclaimer</h2>
                    <p class="mb-6">
                        The materials on Public Digit's platform are provided on an 'as is' basis. Public Digit makes no
                        warranties, expressed or implied, and hereby disclaims and negates all other warranties including,
                        without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose,
                        or non-infringement of intellectual property or other violation of rights.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">3. Limitations</h2>
                    <p class="mb-6">
                        In no event shall Public Digit or its suppliers be liable for any damages (including, without limitation,
                        damages for loss of data or profit, or due to business interruption) arising out of the use or inability
                        to use the materials on Public Digit's platform.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">4. Accuracy of Materials</h2>
                    <p class="mb-6">
                        The materials appearing on Public Digit's platform could include technical, typographical, or
                        photographic errors. Public Digit does not warrant that any of the materials on its platform are accurate,
                        complete, or current. Public Digit may make changes to the materials contained on its platform at any time
                        without notice.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">5. Links</h2>
                    <p class="mb-6">
                        Public Digit has not reviewed all of the sites linked to its platform and is not responsible for the
                        contents of any such linked site. The inclusion of any link does not imply endorsement by Public Digit of
                        the site. Use of any such linked website is at the user's own risk.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">6. Modifications</h2>
                    <p class="mb-6">
                        Public Digit may revise these Terms of Service for its platform at any time without notice.
                        By using this platform, you are agreeing to be bound by the then current version of these Terms of Service.
                    </p>

                    <h2 class="text-2xl font-semibold text-neutral-900 mt-8 mb-4">7. Governing Law</h2>
                    <p class="mb-6">
                        These Terms and Conditions are governed by and construed in accordance with the laws of the
                        jurisdiction in which Public Digit operates, and you irrevocably submit to the exclusive jurisdiction
                        of the courts located in that location.
                    </p>

                    <div class="mt-12 pt-8 border-t border-neutral-200">
                        <p class="text-sm text-neutral-500">
                            Last updated: {{ new Date().toLocaleDateString() }}
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <a
                        :href="route('register')"
                        class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                    >
                        Back to Registration
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TermsOfService',
};
</script>

