<template>
  <PublicDigitLayout>
    <MetaTags
      :title="`${$t('pages.organisation-create-tutorial.page_title')} | Public Digit`"
      :description="$t('pages.organisation-create-tutorial.page_description')"
      :keywords="$t('pages.organisation-create-tutorial.page_keywords')"
    />

    <main id="tutorial-content" role="main" class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-slate-50 py-12 px-4">
      <div class="max-w-4xl mx-auto">
        <!-- Hero Section -->
        <section class="text-center mb-16">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-100 mb-6" aria-hidden="true">
            <BuildingOffice2Icon class="w-8 h-8 text-purple-600" />
          </div>
          <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">
            {{ $t('pages.organisation-create-tutorial.hero_title') }}
          </h1>
          <p class="text-xl text-slate-600 max-w-2xl mx-auto">
            {{ $t('pages.organisation-create-tutorial.hero_subtitle') }}
          </p>
        </section>

        <!-- Quick Navigation / Table of Contents -->
        <nav class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 mb-12">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">
            {{ $t('pages.organisation-create-tutorial.toc_title') }}
          </h2>
          <ul class="grid md:grid-cols-2 gap-3">
            <li v-for="section in 8" :key="section">
              <a :href="`#section-${section}`" class="text-purple-600 hover:text-purple-700 hover:underline font-medium">
                {{ $t(`pages.organisation-create-tutorial.section_${section}_title`) }}
              </a>
            </li>
          </ul>
        </nav>

        <!-- Sections -->
        <TutorialSection
          id="section-1"
          :title="$t('pages.organisation-create-tutorial.section_1_title')"
          :intro="$t('pages.organisation-create-tutorial.section_1_intro')"
        >
          <ul class="space-y-3 mb-6">
            <li class="flex gap-3">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_1_requirement_1') }}</span>
            </li>
            <li class="flex gap-3">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_1_requirement_2') }}</span>
            </li>
            <li class="flex gap-3">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_1_requirement_3') }}</span>
            </li>
          </ul>
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="font-medium text-blue-900 mb-1">{{ $t('pages.organisation-create-tutorial.section_1_step_1') }}</p>
            <p class="text-sm text-blue-800">{{ $t('pages.organisation-create-tutorial.section_1_step_1_desc') }}</p>
          </div>
        </TutorialSection>

        <TutorialSection
          id="section-2"
          :title="$t('pages.organisation-create-tutorial.section_2_title')"
          :intro="$t('pages.organisation-create-tutorial.section_2_intro')"
        >
          <FormFieldDescription
            :label="$t('pages.organisation-create-tutorial.section_2_field_name')"
            :description="$t('pages.organisation-create-tutorial.section_2_field_name_desc')"
            :example="$t('pages.organisation-create-tutorial.section_2_field_name_example')"
          />
          <FormFieldDescription
            :label="$t('pages.organisation-create-tutorial.section_2_field_email')"
            :description="$t('pages.organisation-create-tutorial.section_2_field_email_desc')"
          />
          <FormFieldDescription
            :label="$t('pages.organisation-create-tutorial.section_2_field_representative')"
            :description="$t('pages.organisation-create-tutorial.section_2_field_representative_desc')"
          />
          <FormFieldDescription
            :label="$t('pages.organisation-create-tutorial.section_2_field_languages')"
            :description="$t('pages.organisation-create-tutorial.section_2_field_languages_desc')"
          />
          <FormFieldDescription
            :label="$t('pages.organisation-create-tutorial.section_2_field_logo')"
            :description="$t('pages.organisation-create-tutorial.section_2_field_logo_desc')"
          />
        </TutorialSection>

        <TutorialSection
          id="section-3"
          :title="$t('pages.organisation-create-tutorial.section_3_title')"
          :intro="$t('pages.organisation-create-tutorial.section_3_intro')"
        >
          <OptionCard
            :title="$t('pages.organisation-create-tutorial.section_3_option_full')"
            :description="$t('pages.organisation-create-tutorial.section_3_option_full_desc')"
          />
          <OptionCard
            :title="$t('pages.organisation-create-tutorial.section_3_option_election')"
            :description="$t('pages.organisation-create-tutorial.section_3_option_election_desc')"
          />
          <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-6">
            <p class="text-sm text-amber-800">
              {{ $t('pages.organisation-create-tutorial.section_3_tip') }}
            </p>
          </div>
        </TutorialSection>

        <TutorialSection
          id="section-4"
          :title="$t('pages.organisation-create-tutorial.section_4_title')"
          :intro="$t('pages.organisation-create-tutorial.section_4_intro')"
        >
          <h4 class="text-lg font-semibold text-slate-900 mt-6 mb-3">
            {{ $t('pages.organisation-create-tutorial.section_4_subtitle_flat') }}
          </h4>
          <p class="text-slate-700 mb-3">{{ $t('pages.organisation-create-tutorial.section_4_flat_desc') }}</p>
          <UseCaseList :items="[
            $t('pages.organisation-create-tutorial.section_4_flat_use_case_1'),
            $t('pages.organisation-create-tutorial.section_4_flat_use_case_2'),
            $t('pages.organisation-create-tutorial.section_4_flat_use_case_3'),
          ]" />
          <p class="text-slate-700 mt-4 mb-3">
            <strong>{{ $t('pages.organisation-create-tutorial.section_4_flat_example') }}</strong>
          </p>
          <p class="text-slate-700 text-sm bg-slate-50 p-3 rounded">
            {{ $t('pages.organisation-create-tutorial.section_4_flat_result') }}
          </p>

          <h4 class="text-lg font-semibold text-slate-900 mt-8 mb-3">
            {{ $t('pages.organisation-create-tutorial.section_4_subtitle_geographical') }}
          </h4>
          <p class="text-slate-700 mb-3">{{ $t('pages.organisation-create-tutorial.section_4_geo_desc') }}</p>
          <p class="font-medium text-slate-900 mb-3">{{ $t('pages.organisation-create-tutorial.section_4_geo_hierarchy') }}</p>
          <HierarchyLevel
            :level="$t('pages.organisation-create-tutorial.section_4_geo_level_1')"
            :description="$t('pages.organisation-create-tutorial.section_4_geo_level_1_desc')"
          />
          <HierarchyLevel
            :level="$t('pages.organisation-create-tutorial.section_4_geo_level_2')"
            :description="$t('pages.organisation-create-tutorial.section_4_geo_level_2_desc')"
          />
          <HierarchyLevel
            :level="$t('pages.organisation-create-tutorial.section_4_geo_level_3')"
            :description="$t('pages.organisation-create-tutorial.section_4_geo_level_3_desc')"
          />
          <UseCaseList
            :items="[
              $t('pages.organisation-create-tutorial.section_4_geo_use_case_1'),
              $t('pages.organisation-create-tutorial.section_4_geo_use_case_2'),
              $t('pages.organisation-create-tutorial.section_4_geo_use_case_3'),
            ]"
          />
          <p class="text-slate-700 mt-4 mb-3">
            <strong>{{ $t('pages.organisation-create-tutorial.section_4_geo_example') }}</strong>
          </p>
        </TutorialSection>

        <TutorialSection
          id="section-5"
          :title="$t('pages.organisation-create-tutorial.section_5_title')"
          :intro="$t('pages.organisation-create-tutorial.section_5_intro')"
        >
          <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-red-800">
              {{ $t('pages.organisation-create-tutorial.section_5_note') }}
            </p>
          </div>

          <ScopeTypeCard
            :title="$t('pages.organisation-create-tutorial.section_5_subtitle_worldwide')"
            :description="$t('pages.organisation-create-tutorial.section_5_worldwide_desc')"
            :useCase="$t('pages.organisation-create-tutorial.section_5_worldwide_use_case')"
            :example="$t('pages.organisation-create-tutorial.section_5_worldwide_example')"
            :result="$t('pages.organisation-create-tutorial.section_5_worldwide_result')"
          />

          <ScopeTypeCard
            :title="$t('pages.organisation-create-tutorial.section_5_subtitle_multi')"
            :description="$t('pages.organisation-create-tutorial.section_5_multi_desc')"
            :example="$t('pages.organisation-create-tutorial.section_5_multi_example')"
            :result="$t('pages.organisation-create-tutorial.section_5_multi_result')"
          >
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 mt-3">
              <li>{{ $t('pages.organisation-create-tutorial.section_5_multi_step_1') }}</li>
              <li>{{ $t('pages.organisation-create-tutorial.section_5_multi_step_2') }}</li>
              <li>{{ $t('pages.organisation-create-tutorial.section_5_multi_step_3') }}</li>
            </ol>
          </ScopeTypeCard>

          <ScopeTypeCard
            :title="$t('pages.organisation-create-tutorial.section_5_subtitle_single')"
            :description="$t('pages.organisation-create-tutorial.section_5_single_desc')"
            :example="$t('pages.organisation-create-tutorial.section_5_single_example')"
            :result="$t('pages.organisation-create-tutorial.section_5_single_result')"
          >
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 mt-3">
              <li>{{ $t('pages.organisation-create-tutorial.section_5_single_step_1') }}</li>
              <li>{{ $t('pages.organisation-create-tutorial.section_5_single_step_2') }}</li>
            </ol>
          </ScopeTypeCard>

          <ScopeTypeCard
            :title="$t('pages.organisation-create-tutorial.section_5_subtitle_sub')"
            :description="$t('pages.organisation-create-tutorial.section_5_sub_desc')"
            :example="$t('pages.organisation-create-tutorial.section_5_sub_example')"
            :result="$t('pages.organisation-create-tutorial.section_5_sub_result')"
          >
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 mt-3">
              <li>{{ $t('pages.organisation-create-tutorial.section_5_sub_step_1') }}</li>
              <li>{{ $t('pages.organisation-create-tutorial.section_5_sub_step_2') }}</li>
              <li>{{ $t('pages.organisation-create-tutorial.section_5_sub_step_3') }}</li>
            </ol>
          </ScopeTypeCard>
        </TutorialSection>

        <TutorialSection
          id="section-6"
          :title="$t('pages.organisation-create-tutorial.section_6_title')"
          :intro="$t('pages.organisation-create-tutorial.section_6_intro')"
        >
          <ul class="space-y-2 mb-6">
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_6_checklist_1') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_6_checklist_2') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_6_checklist_3') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_6_checklist_4') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_6_checklist_5') }}</span>
            </li>
          </ul>
          <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <p class="text-purple-900">{{ $t('pages.organisation-create-tutorial.section_6_final_note') }}</p>
          </div>
        </TutorialSection>

        <TutorialSection
          id="section-7"
          :title="$t('pages.organisation-create-tutorial.section_7_title')"
          :intro="$t('pages.organisation-create-tutorial.section_7_intro')"
        >
          <ul class="space-y-2">
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_7_action_1') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_7_action_2') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_7_action_3') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_7_action_4') }}</span>
            </li>
            <li class="flex gap-3 text-slate-700">
              <CheckCircleIcon class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <span>{{ $t('pages.organisation-create-tutorial.section_7_action_5') }}</span>
            </li>
          </ul>
        </TutorialSection>

        <TutorialSection
          id="section-8"
          :title="$t('pages.organisation-create-tutorial.section_8_title')"
        >
          <div class="space-y-6">
            <FAQItem
              :question="$t('pages.organisation-create-tutorial.section_8_q1')"
              :answer="$t('pages.organisation-create-tutorial.section_8_a1')"
            />
            <FAQItem
              :question="$t('pages.organisation-create-tutorial.section_8_q2')"
              :answer="$t('pages.organisation-create-tutorial.section_8_a2')"
            />
            <FAQItem
              :question="$t('pages.organisation-create-tutorial.section_8_q3')"
              :answer="$t('pages.organisation-create-tutorial.section_8_a3')"
            />
            <FAQItem
              :question="$t('pages.organisation-create-tutorial.section_8_q4')"
              :answer="$t('pages.organisation-create-tutorial.section_8_a4')"
            />
            <FAQItem
              :question="$t('pages.organisation-create-tutorial.section_8_q5')"
              :answer="$t('pages.organisation-create-tutorial.section_8_a5')"
            />
          </div>
        </TutorialSection>

        <!-- CTA Section -->
        <section class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg p-10 text-center mb-12">
          <h2 class="text-3xl font-bold text-white mb-4">
            {{ $t('pages.organisation-create-tutorial.cta_button') }}
          </h2>
          <p class="text-purple-100 mb-6">
            {{ $t('pages.organisation-create-tutorial.cta_description') }}
          </p>
          <Link
            :href="route('organisations.create')"
            class="inline-block bg-white text-purple-600 font-semibold px-8 py-3 rounded-lg hover:bg-purple-50 transition-colors"
          >
            {{ $t('pages.organisation-create-tutorial.cta_button') }}
          </Link>
        </section>

        <!-- Help Section -->
        <section class="bg-white rounded-xl border border-slate-200 p-8 mb-12">
          <h2 class="text-2xl font-bold text-slate-900 mb-4">
            {{ $t('pages.organisation-create-tutorial.need_help') }}
          </h2>
          <p class="text-slate-600 mb-6">
            {{ $t('pages.organisation-create-tutorial.need_help_desc') }}
          </p>
          <Link
            :href="`mailto:support@publicdigit.com`"
            class="inline-block bg-purple-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-purple-700 transition-colors"
          >
            {{ $t('pages.organisation-create-tutorial.contact_button') }}
          </Link>
        </section>

        <!-- Related Topics -->
        <section class="bg-slate-50 rounded-xl border border-slate-200 p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-4">
            {{ $t('pages.organisation-create-tutorial.related_pages') }}
          </h2>
          <ul class="space-y-2">
            <li>
              <Link href="#" class="text-purple-600 hover:underline font-medium">
                {{ $t('pages.organisation-create-tutorial.related_1') }}
              </Link>
            </li>
            <li>
              <Link href="#" class="text-purple-600 hover:underline font-medium">
                {{ $t('pages.organisation-create-tutorial.related_2') }}
              </Link>
            </li>
            <li>
              <Link href="#" class="text-purple-600 hover:underline font-medium">
                {{ $t('pages.organisation-create-tutorial.related_3') }}
              </Link>
            </li>
          </ul>
        </section>
      </div>
    </main>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import MetaTags from '@/Components/SEO/MetaTags.vue'
import { BuildingOffice2Icon, CheckCircleIcon } from '@heroicons/vue/24/outline'

// Reusable components
import TutorialSection from '@/Components/Tutorial/TutorialSection.vue'
import FormFieldDescription from '@/Components/Tutorial/FormFieldDescription.vue'
import OptionCard from '@/Components/Tutorial/OptionCard.vue'
import UseCaseList from '@/Components/Tutorial/UseCaseList.vue'
import HierarchyLevel from '@/Components/Tutorial/HierarchyLevel.vue'
import ScopeTypeCard from '@/Components/Tutorial/ScopeTypeCard.vue'
import FAQItem from '@/Components/Tutorial/FAQItem.vue'

const page = usePage()
</script>
