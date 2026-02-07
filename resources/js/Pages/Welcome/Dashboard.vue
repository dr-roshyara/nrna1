<template>
  <div class="welcome-dashboard min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />

    <!-- Main Content -->
    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
      <!-- Welcome Section -->
      <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">
          {{ $t('pages.welcome-dashboard.header.title', { name: userName || 'User' }) }}
        </h1>
        <p class="text-xl text-gray-600">
          {{ $t('pages.welcome-dashboard.header.subtitle') }}
        </p>
      </div>

      <!-- German Compliance Notice -->
      <div class="mb-12 p-6 border border-blue-200 bg-blue-50 rounded-lg">
        <div class="flex items-start gap-4">
          <span class="text-3xl">🇩🇪</span>
          <div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.compliance.title') }}
            </h3>
            <p class="text-gray-600 mb-3">
              {{ $t('pages.welcome-dashboard.compliance.description') }}
            </p>
            <ul class="text-sm text-gray-600 space-y-2">
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.gdpr') }}</li>
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.dataprotection') }}</li>
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.encryption') }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Action Buttons Section -->
      <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
          {{ $t('pages.welcome-dashboard.actions.title') }}
        </h2>
        <div class="space-y-4">
          <!-- PRIMARY ACTION: Create Organization (with visual emphasis) -->
          <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <button
              @click="createOrganization"
              class="relative w-full p-6 bg-white border-2 border-blue-500 rounded-lg hover:shadow-xl transition-all text-left group-hover:border-blue-600"
            >
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                  <span class="text-3xl">🏢</span>
                  <div>
                    <div class="font-bold text-gray-900 text-lg">
                      {{ $t('pages.welcome-dashboard.actions.createOrg') }}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                      {{ $t('pages.welcome-dashboard.actions.createOrgDesc') }}
                    </div>
                  </div>
                </div>
                <span class="text-2xl text-blue-600 font-bold">→</span>
              </div>
            </button>
          </div>

          <!-- Secondary Actions -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Join Organization -->
            <button
              @click="joinOrganization"
              class="p-6 bg-white border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-lg transition-all text-left"
            >
              <div class="flex items-start gap-4">
                <span class="text-3xl">👥</span>
                <div>
                  <div class="font-bold text-gray-900">
                    {{ $t('pages.welcome-dashboard.actions.joinOrg') }}
                  </div>
                  <div class="text-sm text-gray-600 mt-1">
                    {{ $t('pages.welcome-dashboard.actions.joinOrgDesc') }}
                  </div>
                </div>
              </div>
            </button>

            <!-- Skip Setup -->
            <button
              @click="skipSetup"
              class="p-6 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-lg transition-all text-left"
            >
              <div class="flex items-start gap-4">
                <span class="text-3xl">📊</span>
                <div>
                  <div class="font-bold text-gray-900">
                    {{ $t('pages.welcome-dashboard.actions.skip') }}
                  </div>
                  <div class="text-sm text-gray-600 mt-1">
                    {{ $t('pages.welcome-dashboard.actions.skipDesc') }}
                  </div>
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Use Cases Section -->
      <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
          {{ $t('pages.welcome-dashboard.usecases.title') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- German Organizations -->
          <div class="p-6 border border-gray-200 rounded-lg bg-white hover:shadow-md transition-shadow">
            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.usecases.german.title') }}
            </h3>
            <p class="text-gray-600 mb-4">
              {{ $t('pages.welcome-dashboard.usecases.german.description') }}
            </p>
            <ul class="text-sm text-gray-600 space-y-2">
              <li>• {{ $t('pages.welcome-dashboard.usecases.german.example1') }}</li>
              <li>• {{ $t('pages.welcome-dashboard.usecases.german.example2') }}</li>
            </ul>
          </div>

          <!-- Diaspora Communities -->
          <div class="p-6 border border-gray-200 rounded-lg bg-white hover:shadow-md transition-shadow">
            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.usecases.diaspora.title') }}
            </h3>
            <p class="text-gray-600 mb-4">
              {{ $t('pages.welcome-dashboard.usecases.diaspora.description') }}
            </p>
            <ul class="text-sm text-gray-600 space-y-2">
              <li>• {{ $t('pages.welcome-dashboard.usecases.diaspora.example1') }}</li>
              <li>• {{ $t('pages.welcome-dashboard.usecases.diaspora.example2') }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Social Proof Section -->
      <div class="mb-12 pt-8 border-t border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 mb-8 text-center">
          {{ $t('pages.welcome-dashboard.socialProof.title') }}
        </h2>
        <div class="grid grid-cols-3 gap-6 text-center">
          <div class="p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-3xl font-bold text-blue-600">50+</div>
            <div class="text-sm text-gray-600 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.orgs') }}
            </div>
          </div>
          <div class="p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-3xl font-bold text-blue-600">8</div>
            <div class="text-sm text-gray-600 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.languages') }}
            </div>
          </div>
          <div class="p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-3xl font-bold text-blue-600">100%</div>
            <div class="text-sm text-gray-600 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.gdpr') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Key Features Section -->
      <div class="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          {{ $t('pages.welcome-dashboard.keyFeatures.title') }}
        </h2>
        <p class="text-gray-600 mb-6">
          {{ $t('pages.welcome-dashboard.keyFeatures.subtitle') }}
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature 1: Transparency -->
          <div class="p-4 bg-white rounded-lg border border-blue-100">
            <div class="text-3xl mb-3">🔍</div>
            <h4 class="font-semibold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.keyFeatures.transparency.title') }}
            </h4>
            <p class="text-sm text-gray-600">
              {{ $t('pages.welcome-dashboard.keyFeatures.transparency.description') }}
            </p>
          </div>

          <!-- Feature 2: Security -->
          <div class="p-4 bg-white rounded-lg border border-blue-100">
            <div class="text-3xl mb-3">🔒</div>
            <h4 class="font-semibold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.keyFeatures.security.title') }}
            </h4>
            <p class="text-sm text-gray-600">
              {{ $t('pages.welcome-dashboard.keyFeatures.security.description') }}
            </p>
          </div>

          <!-- Feature 3: Multilingual -->
          <div class="p-4 bg-white rounded-lg border border-blue-100">
            <div class="text-3xl mb-3">🌐</div>
            <h4 class="font-semibold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.keyFeatures.multilingual.title') }}
            </h4>
            <p class="text-sm text-gray-600">
              {{ $t('pages.welcome-dashboard.keyFeatures.multilingual.description') }}
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Tips Section -->
      <div class="mb-12 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          💡 {{ $t('pages.welcome-dashboard.tips.title') }}
        </h3>
        <ul class="space-y-2">
          <li class="text-sm text-gray-700">
            • {{ $t('pages.welcome-dashboard.tips.tip1') }}
          </li>
          <li class="text-sm text-gray-700">
            • {{ $t('pages.welcome-dashboard.tips.tip2') }}
          </li>
          <li class="text-sm text-gray-700">
            • {{ $t('pages.welcome-dashboard.tips.tip3') }}
          </li>
        </ul>
      </div>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";

export default {
  name: 'WelcomeDashboard',

  components: {
    ElectionHeader,
    PublicDigitFooter,
  },

  props: {
    userName: String,
    userEmail: String,
    userCreatedAt: String,
  },

  data() {
    return {
      // Empty data - all i18n via $t()
    };
  },

  methods: {
    createOrganization() {
      console.log('Navigating to create organization');
      this.$inertia.visit(this.route('organizations.create'));
    },

    joinOrganization() {
      console.log('Navigating to join organization');
      this.$inertia.visit(this.route('organizations.join'));
    },

    skipSetup() {
      console.log('Skipping setup, going to role selection');
      this.$inertia.visit(this.route('role.selection'));
    },
  },

  mounted() {
    console.log('Welcome Dashboard mounted');
    console.log('Current locale:', this.$i18n.locale);
    console.log('User:', this.userName);
  },
};
</script>

<style scoped>
.welcome-dashboard {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex-grow: 1;
}

/* Smooth gradient animation on hover */
@media (prefers-reduced-motion: no-preference) {
  .group:hover .blur {
    opacity: 100;
  }
}
</style>
