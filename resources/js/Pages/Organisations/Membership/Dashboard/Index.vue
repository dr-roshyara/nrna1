<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-primary-50">

      <!-- ── Sticky Hero Header ─────────────────────────────────────────── -->
      <div class="bg-white/95 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">

          <!-- Breadcrumb -->
          <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm mb-4">
            <a :href="route('organisations.show', organisation.slug)"
               class="text-slate-500 hover:text-purple-600 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 rounded-md">
              {{ organisation.name }}
            </a>
            <ChevronRightIcon class="w-4 h-4 text-slate-300 flex-shrink-0" aria-hidden="true" />
            <span class="text-slate-800 font-semibold" aria-current="page">{{ t.title }}</span>
          </nav>

          <!-- Title row -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
              <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg flex-shrink-0" aria-hidden="true">
                <UsersIcon class="w-6 h-6 sm:w-7 sm:h-7 text-white" aria-hidden="true" />
              </div>
              <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">{{ t.title }}</h1>
                <div class="flex items-center flex-wrap gap-2 mt-1">
                  <span class="text-sm text-slate-500">{{ organisation.name }}</span>
                  <span :class="roleBadgeClass" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold">
                    {{ roleLabel }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Contextual primary action -->
            <a v-if="primaryAction"
               :href="primaryAction.url"
               class="self-start sm:self-auto inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-700 transition-all hover:shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
              <component :is="primaryAction.icon" class="w-4 h-4" aria-hidden="true" />
              {{ primaryAction.label }}
            </a>
          </div>
        </div>
      </div>

      <!-- ── Flash Banners ──────────────────────────────────────────────── -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <Transition name="slide-down">
          <div v-if="page.props.flash?.success"
               role="alert" aria-live="polite"
               class="mb-4 rounded-xl bg-green-50 border border-green-200 p-4 flex items-center gap-3">
            <CheckCircleIcon class="w-5 h-5 text-green-600 flex-shrink-0" aria-hidden="true" />
            <p class="text-sm text-green-800">{{ page.props.flash.success }}</p>
          </div>
        </Transition>
        <div v-if="page.props.errors?.error"
             role="alert" aria-live="assertive"
             class="mb-4 rounded-xl bg-danger-50 border border-danger-200 p-4 flex items-center gap-3">
          <XCircleIcon class="w-5 h-5 text-danger-600 flex-shrink-0" aria-hidden="true" />
          <p class="text-sm text-danger-800">{{ page.props.errors.error }}</p>
        </div>
      </div>

      <!-- ── Main Content ───────────────────────────────────────────────── -->
      <main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14">

        <!-- KPI Cards (admin/owner/commission) -->
        <div v-if="visibleKPIs.length" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <KPICard
            v-for="kpi in visibleKPIs"
            :key="kpi.key"
            :label="kpi.label"
            :value="kpi.value"
            :href="kpi.href"
            :icon="kpi.icon"
            :color="kpi.color"
            :trend="kpi.trend"
            :trend-label="kpi.trendLabel"
            :link-label="t.view_all"
          />
        </div>

        <!-- Quick Actions -->
        <div v-if="quickActions.length" class="mt-10">
          <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900">{{ t.quick_actions }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ t.quick_actions_desc }}</p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            <QuickActionCard
              v-for="action in quickActions"
              :key="action.key"
              :title="action.title"
              :description="action.description"
              :href="action.href"
              :icon="action.icon"
              :color="action.color"
            />
          </div>
        </div>

        <!-- ══════════════════════ MEMBER VIEW ══════════════════════ -->
        <template v-if="role === 'member' && memberSelf">

          <!-- Active membership card -->
          <div v-if="memberSelf.has_membership" class="mt-8">
            <h2 class="text-base font-semibold text-slate-700 mb-3">{{ t.your_membership }}</h2>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">

              <!-- Status banner -->
              <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" :class="memberStatusIconBg">
                      <component :is="memberStatusIcon" class="w-6 h-6" :class="memberStatusIconColor" aria-hidden="true" />
                    </div>
                    <div>
                      <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ t.stat_status }}</p>
                      <p class="text-lg font-bold capitalize" :class="memberStatusTextClass">{{ memberSelf.status }}</p>
                    </div>
                  </div>
                  <a v-if="memberSelf.can_self_renew && memberSelf.member_id"
                     :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
                     class="self-start sm:self-auto inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <ArrowPathIcon class="w-4 h-4" aria-hidden="true" />
                    {{ t.renew_now }}
                  </a>
                </div>
              </div>

              <!-- Details grid -->
              <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                <div class="p-5 sm:p-6">
                  <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_expires }}</p>
                  <p v-if="memberSelf.expires_at"
                     class="text-base font-semibold"
                     :class="memberSelf.expires_in_days !== null && memberSelf.expires_in_days <= 30 ? 'text-orange-600' : 'text-slate-800'">
                    {{ formatDate(memberSelf.expires_at) }}
                    <span v-if="memberSelf.expires_in_days !== null" class="text-xs font-normal text-slate-400 block">
                      ({{ memberSelf.expires_in_days }}d {{ t.days_left }})
                    </span>
                  </p>
                  <p v-else class="text-base font-semibold text-purple-700">{{ t.lifetime }}</p>
                </div>
                <div class="p-5 sm:p-6">
                  <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_pending_fees }}</p>
                  <p class="text-base font-semibold" :class="memberSelf.pending_fees > 0 ? 'text-danger-600' : 'text-green-700'">
                    {{ memberSelf.pending_fees > 0 ? memberSelf.pending_fees.toFixed(2) + ' €' : t.no_pending_fees }}
                    <span v-if="memberSelf.pending_fees <= 0" class="sr-only">— paid up</span>
                  </p>
                </div>
                <div v-if="memberSelf.member_id" class="p-5 sm:p-6 flex items-end gap-4">
                  <a :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
                     class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 rounded">
                    {{ t.nav_my_fees }} →
                  </a>
                  <a :href="route('organisations.members.finance', [organisation.slug, memberSelf.member_id])"
                     class="text-sm font-medium text-green-600 hover:text-green-800 hover:underline focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 rounded">
                    {{ t.nav_my_finance }} →
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- No membership — enhanced empty state -->
          <div v-else class="mt-8">
            <div class="bg-gradient-to-br from-amber-50 to-white rounded-xl border border-amber-200 p-8 text-center">
              <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                <UserPlusIcon class="w-8 h-8 text-amber-600" aria-hidden="true" />
              </div>
              <h2 class="text-xl font-semibold text-slate-800 mb-2">{{ noMembershipTitle }}</h2>
              <p class="text-slate-500 max-w-md mx-auto mb-6 text-sm">{{ noMembershipDesc }}</p>
              <a :href="memberSelf.apply_url"
                 class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-700 transition-all focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                {{ t.apply_now }}
                <ArrowRightIcon class="w-4 h-4" aria-hidden="true" />
              </a>
            </div>
          </div>

          <!-- My Applications -->
          <div v-if="memberSelf.my_applications && memberSelf.my_applications.length" class="mt-8">
            <h2 class="text-base font-semibold text-slate-700 mb-3">{{ t.my_applications }}</h2>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
              <div class="divide-y divide-slate-100">
                <div v-for="app in memberSelf.my_applications" :key="app.id"
                     class="p-4 sm:p-5 hover:bg-slate-50 transition-colors">
                  <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-slate-800">{{ app.membership_type ?? t.membership }}</p>
                      <p class="text-xs text-slate-400 mt-0.5">{{ t.submitted }}: {{ formatDate(app.submitted_at) }}</p>
                    </div>
                    <StatusBadge :status="app.status" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else-if="!memberSelf.has_membership" class="mt-4">
            <!-- apply link shown inside the empty-state above, no second block needed -->
          </div>

        </template>

        <!-- ════════════════ ADMIN / OWNER / COMMISSION VIEW ════════════════ -->
        <template v-else-if="role !== 'member'">
          <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Applications table (2/3) -->
            <div class="lg:col-span-2">
              <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                  <h2 class="text-base font-semibold text-slate-800">{{ t.applications_title }}</h2>
                  <a :href="route('organisations.membership.applications.index', organisation.slug)"
                     class="text-sm font-medium text-purple-600 hover:text-purple-800 flex items-center gap-1 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1 rounded">
                    {{ t.view_all }}
                    <ArrowRightIcon class="w-3.5 h-3.5" aria-hidden="true" />
                  </a>
                </div>

                <div v-if="applications && applications.data && applications.data.length" class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-slate-100" :aria-label="t.applications_title">
                    <thead class="bg-white">
                      <tr>
                        <th scope="col" class="px-5 sm:px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t.col_applicant }}</th>
                        <th scope="col" class="px-5 sm:px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">{{ t.col_type }}</th>
                        <th scope="col" class="px-5 sm:px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">{{ t.col_status }}</th>
                        <th scope="col" class="px-5 sm:px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t.col_actions }}</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                      <tr v-for="app in applications.data.slice(0, 6)" :key="app.id"
                          class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 sm:px-6 py-3">
                          <div class="text-sm font-medium text-slate-900">{{ app.user?.name }}</div>
                          <div class="text-xs text-slate-400">{{ app.user?.email }}</div>
                          <!-- Show type inline on small screens -->
                          <div class="text-xs text-slate-500 mt-0.5 sm:hidden">{{ app.membership_type?.name ?? '—' }}</div>
                        </td>
                        <td class="px-5 sm:px-6 py-3 text-sm text-slate-600 hidden sm:table-cell">
                          {{ app.membership_type?.name ?? '—' }}
                        </td>
                        <td class="px-5 sm:px-6 py-3 hidden md:table-cell">
                          <StatusBadge :status="app.status" />
                        </td>
                        <td class="px-5 sm:px-6 py-3 text-right whitespace-nowrap">
                          <a :href="route('organisations.membership.applications.show', [organisation.slug, app.id])"
                             :aria-label="`${t.review}: ${app.user?.name}`"
                             class="inline-flex items-center gap-1 text-sm font-medium text-purple-600 hover:text-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1 rounded">
                            {{ t.review }}
                            <ArrowRightIcon class="w-3.5 h-3.5" aria-hidden="true" />
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <EmptyState v-else
                  icon="document"
                  :title="t.no_applications"
                  :description="t.no_applications_desc"
                />
              </div>
            </div>

            <!-- Sidebar (1/3) -->
            <div class="space-y-5">

              <!-- Alert card -->
              <AlertCard v-if="alerts.length" :title="t.attention_needed" :alerts="alerts" />

              <!-- Expiring members -->
              <div v-if="expiringMembers && expiringMembers.length"
                   class="bg-white rounded-xl border border-orange-200 overflow-hidden shadow-sm">
                <div class="px-5 py-3 border-b border-orange-100 bg-orange-50 flex items-center gap-2">
                  <ClockIcon class="w-4 h-4 text-orange-600 flex-shrink-0" aria-hidden="true" />
                  <h3 class="text-sm font-semibold text-orange-800">
                    {{ t.expiring_soon }} ({{ expiringMembers.length }})
                  </h3>
                </div>
                <ul class="divide-y divide-slate-100">
                  <li v-for="m in expiringMembers.slice(0, 5)" :key="m.id"
                      class="px-5 py-3 flex items-center justify-between gap-2 text-sm">
                    <span class="text-slate-700 truncate">{{ m.organisationUser?.user?.name ?? '—' }}</span>
                    <span class="text-orange-600 text-xs font-medium flex-shrink-0">
                      {{ formatDate(m.membership_expires_at) }}
                    </span>
                  </li>
                </ul>
                <p v-if="expiringMembers.length > 5" class="px-5 py-2 text-xs text-slate-400 border-t border-slate-100">
                  +{{ expiringMembers.length - 5 }} {{ t.more }}
                </p>
              </div>

              <!-- Recent activity -->
              <div v-if="recentActivity && recentActivity.length"
                   class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                  <h3 class="text-sm font-semibold text-slate-700">{{ t.recent_activity }}</h3>
                </div>
                <ul class="divide-y divide-slate-100">
                  <li v-for="(item, i) in recentActivity.slice(0, 6)" :key="i"
                      class="px-5 py-3 flex items-start gap-3">
                    <div class="mt-0.5 w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
                         :class="item.type === 'payment' ? 'bg-green-100' : 'bg-primary-100'"
                         aria-hidden="true">
                      <component :is="item.type === 'payment' ? CheckCircleIcon : UserIcon"
                                 class="w-3.5 h-3.5"
                                 :class="item.type === 'payment' ? 'text-green-600' : 'text-primary-600'" />
                    </div>
                    <span class="text-xs text-slate-600 leading-snug">{{ item.message }}</span>
                  </li>
                </ul>
              </div>

              <!-- All up to date placeholder (commission only) -->
              <div v-if="role === 'commission' && !alerts.length && (!expiringMembers || !expiringMembers.length) && (!recentActivity || !recentActivity.length)"
                   class="bg-white rounded-xl border border-slate-200 p-6 text-center shadow-sm">
                <CheckCircleIcon class="w-10 h-10 text-green-200 mx-auto mb-2" aria-hidden="true" />
                <p class="text-sm text-slate-400">{{ t.all_up_to_date }}</p>
              </div>

            </div>
          </div>
        </template>

      </main>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import {
  ChevronRightIcon,
  UsersIcon,
  UserPlusIcon,
  ArrowRightIcon,
  ArrowPathIcon,
  CheckCircleIcon,
  XCircleIcon,
  ClockIcon,
  UserIcon,
  DocumentTextIcon,
  CreditCardIcon,
  CurrencyEuroIcon,
  TagIcon,
  ArrowUpTrayIcon,
  EnvelopeIcon,
  EnvelopeOpenIcon,
} from '@heroicons/vue/24/outline'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import KPICard         from './components/KPICard.vue'
import QuickActionCard from './components/QuickActionCard.vue'
import StatusBadge     from './components/StatusBadge.vue'
import EmptyState      from './components/EmptyState.vue'
import AlertCard       from './components/AlertCard.vue'

// ── Props ─────────────────────────────────────────────────────────────────────
const props = defineProps({
  organisation:    { type: Object, required: true },
  role:            { type: String, required: true },
  stats:           { type: Object, default: () => ({}) },
  applications:    { type: Object, default: null },
  expiringMembers: { type: Array,  default: () => [] },
  recentActivity:  { type: Array,  default: () => [] },
  memberSelf:      { type: Object, default: null },
})

const page       = usePage()
const { locale } = useI18n()

// ── Translations ──────────────────────────────────────────────────────────────
const translations = {
  en: {
    title: 'Membership Dashboard',
    quick_actions: 'Quick Actions',
    quick_actions_desc: 'Common tasks and shortcuts for managing membership',
    your_membership: 'Your Membership',
    days_left: 'left',
    // Stat card labels
    stat_total_members: 'Total Members',
    stat_pending_apps: 'Pending Applications',
    stat_pending_fees_total: 'Outstanding Fees (€)',
    stat_expiring_30: 'Expiring in 30 Days',
    stat_status: 'Membership Status',
    stat_expires: 'Expires',
    stat_pending_fees: 'Outstanding Fees',
    lifetime: 'Lifetime',
    no_pending_fees: 'Paid up ✓',
    // Link / action labels
    view_all: 'View all', view_unpaid: 'View unpaid', view_expiring: 'View expiring', review: 'Review',
    review_pending: 'Review Pending',
    attention_needed: 'Attention Needed',
    // Nav / quick actions
    nav_applications: 'Applications', nav_manage: 'Manage requests', nav_view_only: 'View only',
    nav_members: 'Members', nav_directory: 'Directory & management',
    nav_participants: 'Participants', nav_all_roles: 'All platform roles',
    nav_roles: 'Organisation Roles', nav_roles_desc: 'View roles & add as member',
    nav_types: 'Types', nav_manage_tiers: 'Manage tiers',
    nav_my_fees: 'My Fees', nav_payment_history: 'Payment history',
    nav_my_finance: 'Finance', nav_financial_overview: 'Financial overview',
    nav_manage_fees: 'Manage Fees', nav_manage_fees_desc: 'Assign and track member fees',
    nav_renew: 'Renew', nav_extend: 'Extend membership',
    nav_apply: 'Apply', nav_become_member: 'Become a member',
    nav_invite_members: 'Invite Members', nav_invite_members_desc: 'Send email invitations to join',
    nav_invite_participants: 'Invite Participants', nav_invite_participants_desc: 'Invite staff, guests & committee',
    nav_import_members: 'Import Members', nav_import_members_desc: 'Bulk upload via Excel / CSV',
    nav_import_participants: 'Import Participants', nav_import_participants_desc: 'Staff, guests & committee',
    nav_import_users: 'Import Users', nav_import_users_desc: 'Users + members + voters in one file',
    nav_newsletters: 'Newsletters', nav_newsletters_desc: 'Compose & send bulk emails to members',
    nav_public_apply: 'Public Application Form', nav_public_apply_desc: 'Share this link to accept new members',
    // Table columns
    applications_title: 'Recent Applications',
    col_applicant: 'Applicant', col_type: 'Type', col_status: 'Status', col_actions: 'Actions',
    applicant: 'Applicant', membership_type: 'Type', date: 'Date', action: 'Action',
    no_applications: 'No applications yet',
    no_applications_desc: 'When members submit applications they will appear here.',
    // Member self-view
    no_membership_title: 'You are not yet a member',
    no_membership_desc: 'Apply for membership to access member benefits.',
    no_membership_title_member_role: 'Membership enrollment incomplete',
    no_membership_desc_member_role: 'You have platform access but your formal membership record has not been created yet. Please submit a membership application or contact your administrator.',
    no_membership_title_voter: 'You are registered as a voter',
    no_membership_desc_voter: 'You can participate in elections, but you do not yet have a paid membership. Apply to become a full member.',
    apply_now: 'Apply Now',
    renewal_available: 'Renewal Available',
    renewal_cta_desc: 'Your membership is due for renewal. Renew now to stay active.',
    renew_now: 'Renew Now',
    my_applications: 'My Applications',
    membership: 'Membership', submitted: 'Submitted',
    // Side panels
    expiring_soon: 'Expiring Soon', more: 'more', recent_activity: 'Recent Activity',
    all_up_to_date: 'All up to date.',
    // Alerts
    applications_need_review: 'applications need review',
    members_expiring_soon: 'members are expiring in 30 days',
    review_now: 'Review now',
    expiring_memberships: 'Expiring Memberships',
    pending_applications: 'Pending Applications',
    // Role labels
    role_owner: 'Owner', role_admin: 'Admin', role_commission: 'Commission', role_member: 'Member',
    // Pro tip
    pro_tip: 'Tip',
    pro_tip_message: 'Approve pending applications quickly to keep members active.',
  },
  de: {
    title: 'Mitgliedschafts-Dashboard',
    quick_actions: 'Schnellaktionen',
    quick_actions_desc: 'Häufige Aufgaben und Verknüpfungen für die Mitgliedschaftsverwaltung',
    your_membership: 'Ihre Mitgliedschaft',
    days_left: 'verbleibend',
    stat_total_members: 'Mitglieder gesamt',
    stat_pending_apps: 'Ausstehende Anträge',
    stat_pending_fees_total: 'Offene Gebühren (€)',
    stat_expiring_30: 'Läuft in 30 Tagen ab',
    stat_status: 'Mitgliedschaftsstatus',
    stat_expires: 'Läuft ab',
    stat_pending_fees: 'Offene Gebühren',
    lifetime: 'Lebenslang',
    no_pending_fees: 'Bezahlt ✓',
    view_all: 'Alle anzeigen', view_unpaid: 'Unbezahlt', view_expiring: 'Ablaufend', review: 'Prüfen',
    review_pending: 'Ausstehend prüfen',
    attention_needed: 'Achtung erforderlich',
    nav_applications: 'Anträge', nav_manage: 'Verwalten', nav_view_only: 'Nur lesen',
    nav_members: 'Mitglieder', nav_directory: 'Verzeichnis',
    nav_participants: 'Teilnehmer', nav_all_roles: 'Alle Plattformrollen',
    nav_roles: 'Organisationsrollen', nav_roles_desc: 'Rollen anzeigen & als Mitglied hinzufügen',
    nav_types: 'Typen', nav_manage_tiers: 'Stufen verwalten',
    nav_my_fees: 'Meine Gebühren', nav_payment_history: 'Zahlungshistorie',
    nav_my_finance: 'Finanzen', nav_financial_overview: 'Finanzielle Übersicht',
    nav_manage_fees: 'Gebühren verwalten', nav_manage_fees_desc: 'Mitgliedsgebühren zuweisen und verfolgen',
    nav_renew: 'Verlängern', nav_extend: 'Mitgliedschaft verlängern',
    nav_apply: 'Beantragen', nav_become_member: 'Mitglied werden',
    nav_invite_members: 'Mitglieder einladen', nav_invite_members_desc: 'E-Mail-Einladungen versenden',
    nav_invite_participants: 'Teilnehmer einladen', nav_invite_participants_desc: 'Mitarbeiter, Gäste & Ausschuss einladen',
    nav_import_members: 'Mitglieder importieren', nav_import_members_desc: 'Massenupload via Excel / CSV',
    nav_import_participants: 'Teilnehmer importieren', nav_import_participants_desc: 'Mitarbeiter, Gäste & Ausschuss',
    nav_import_users: 'Benutzer importieren', nav_import_users_desc: 'Benutzer + Mitglieder + Wähler in einer Datei',
    nav_newsletters: 'Newsletter', nav_newsletters_desc: 'Massen-E-Mails an Mitglieder senden',
    nav_public_apply: 'Öffentliches Beitrittsformular', nav_public_apply_desc: 'Link teilen um neue Mitglieder aufzunehmen',
    applications_title: 'Aktuelle Anträge',
    col_applicant: 'Antragsteller', col_type: 'Typ', col_status: 'Status', col_actions: 'Aktionen',
    applicant: 'Antragsteller', membership_type: 'Typ', date: 'Datum', action: 'Aktion',
    no_applications: 'Keine Anträge vorhanden',
    no_applications_desc: 'Eingereichte Anträge erscheinen hier.',
    no_membership_title: 'Sie sind noch kein Mitglied',
    no_membership_desc: 'Beantragen Sie die Mitgliedschaft.',
    no_membership_title_member_role: 'Mitgliedschaft nicht abgeschlossen',
    no_membership_desc_member_role: 'Sie haben Plattformzugang, aber Ihr offizieller Mitgliedschaftsdatensatz wurde noch nicht erstellt.',
    no_membership_title_voter: 'Sie sind als Wähler registriert',
    no_membership_desc_voter: 'Sie können an Wahlen teilnehmen, haben aber noch keine bezahlte Mitgliedschaft.',
    apply_now: 'Jetzt beantragen',
    renewal_available: 'Verlängerung möglich',
    renewal_cta_desc: 'Ihre Mitgliedschaft kann verlängert werden.',
    renew_now: 'Jetzt verlängern',
    my_applications: 'Meine Anträge',
    membership: 'Mitgliedschaft', submitted: 'Eingereicht',
    expiring_soon: 'Läuft bald ab', more: 'weitere', recent_activity: 'Letzte Aktivitäten',
    all_up_to_date: 'Alles auf dem neuesten Stand.',
    applications_need_review: 'Anträge zur Prüfung',
    members_expiring_soon: 'Mitglieder laufen in 30 Tagen ab',
    review_now: 'Jetzt prüfen',
    expiring_memberships: 'Ablaufende Mitgliedschaften',
    pending_applications: 'Ausstehende Anträge',
    role_owner: 'Eigentümer', role_admin: 'Administrator', role_commission: 'Kommission', role_member: 'Mitglied',
    pro_tip: 'Tipp',
    pro_tip_message: 'Genehmigen Sie ausstehende Anträge schnell, um Mitglieder aktiv zu halten.',
  },
  np: {
    title: 'सदस्यता ड्यासबोर्ड',
    quick_actions: 'द्रुत कार्यहरू',
    quick_actions_desc: 'सदस्यता प्रबन्धनका लागि सामान्य कार्य र शर्टकट',
    your_membership: 'तपाईंको सदस्यता',
    days_left: 'बाँकी',
    stat_total_members: 'कुल सदस्यहरू',
    stat_pending_apps: 'विचाराधीन आवेदनहरू',
    stat_pending_fees_total: 'बाँकी शुल्क (€)',
    stat_expiring_30: '३० दिनमा समाप्त',
    stat_status: 'सदस्यता स्थिति',
    stat_expires: 'म्याद सकिन्छ',
    stat_pending_fees: 'बाँकी शुल्क',
    lifetime: 'आजीवन',
    no_pending_fees: 'भुक्तान ✓',
    view_all: 'सबै हेर्नुहोस्', view_unpaid: 'अपठित', view_expiring: 'समाप्त हुने', review: 'समीक्षा',
    review_pending: 'विचाराधीन समीक्षा',
    attention_needed: 'ध्यान चाहिन्छ',
    nav_applications: 'आवेदनहरू', nav_manage: 'व्यवस्थापन', nav_view_only: 'हेर्न मात्र',
    nav_members: 'सदस्यहरू', nav_directory: 'निर्देशिका',
    nav_participants: 'सहभागीहरू', nav_all_roles: 'सबै भूमिकाहरू',
    nav_roles: 'संगठन भूमिकाहरू', nav_roles_desc: 'भूमिका हेर्नुहोस् र सदस्य थप्नुहोस्',
    nav_types: 'प्रकारहरू', nav_manage_tiers: 'स्तर व्यवस्थापन',
    nav_my_fees: 'मेरो शुल्क', nav_payment_history: 'भुक्तानी इतिहास',
    nav_my_finance: 'वित्त', nav_financial_overview: 'वित्तीय सारांश',
    nav_manage_fees: 'शुल्क व्यवस्थापन', nav_manage_fees_desc: 'सदस्य शुल्क नियुक्त र ट्र्याक गर्नुहोस्',
    nav_renew: 'नवीकरण', nav_extend: 'सदस्यता बढाउनुहोस्',
    nav_apply: 'आवेदन', nav_become_member: 'सदस्य बन्नुहोस्',
    nav_invite_members: 'सदस्य आमन्त्रण', nav_invite_members_desc: 'इमेल आमन्त्रण पठाउनुहोस्',
    nav_invite_participants: 'सहभागी आमन्त्रण', nav_invite_participants_desc: 'कर्मचारी, अतिथि र समिति आमन्त्रण',
    nav_import_members: 'सदस्य आयात', nav_import_members_desc: 'Excel / CSV मार्फत',
    nav_import_participants: 'सहभागी आयात', nav_import_participants_desc: 'कर्मचारी, अतिथि र समिति',
    nav_import_users: 'प्रयोगकर्ता आयात', nav_import_users_desc: 'प्रयोगकर्ता + सदस्य + मतदाता एकसाथ',
    nav_newsletters: 'न्युजलेटर', nav_newsletters_desc: 'सदस्यहरूलाई सामूहिक इमेल पठाउनुहोस्',
    nav_public_apply: 'सार्वजनिक आवेदन फाराम', nav_public_apply_desc: 'नया सदस्यका लागि यो लिंक साझा गर्नुहोस्',
    applications_title: 'हालका आवेदनहरू',
    col_applicant: 'आवेदक', col_type: 'प्रकार', col_status: 'स्थिति', col_actions: 'कार्यहरू',
    applicant: 'आवेदक', membership_type: 'प्रकार', date: 'मिति', action: 'कार्य',
    no_applications: 'अहिलेसम्म कुनै आवेदन छैन',
    no_applications_desc: 'आवेदनहरू यहाँ देखिनेछन्।',
    no_membership_title: 'तपाईं अझै सदस्य हुनुभएको छैन',
    no_membership_desc: 'सदस्यताको लागि आवेदन दिनुहोस्।',
    no_membership_title_member_role: 'सदस्यता नामांकन अपूर्ण',
    no_membership_desc_member_role: 'तपाईंसँग प्लेटफर्म पहुँच छ तर औपचारिक सदस्यता अभिलेख बनाइएको छैन।',
    no_membership_title_voter: 'तपाईं मतदाताको रूपमा दर्ता हुनुभएको छ',
    no_membership_desc_voter: 'तपाईं निर्वाचनमा भाग लिन सक्नुहुन्छ, तर अहिलेसम्म तपाईंको भुक्तान सदस्यता छैन।',
    apply_now: 'अहिले आवेदन दिनुहोस्',
    renewal_available: 'नवीकरण उपलब्ध',
    renewal_cta_desc: 'तपाईंको सदस्यता नवीकरण योग्य छ।',
    renew_now: 'अहिले नवीकरण गर्नुहोस्',
    my_applications: 'मेरा आवेदनहरू',
    membership: 'सदस्यता', submitted: 'पेश गरिएको',
    expiring_soon: 'चाँडै समाप्त हुने', more: 'थप', recent_activity: 'हालका गतिविधिहरू',
    all_up_to_date: 'सबै अद्यावधिक छ।',
    applications_need_review: 'आवेदनहरूलाई समीक्षा चाहिन्छ',
    members_expiring_soon: 'सदस्यताहरू ३० दिनमा समाप्त हुँदैछन्',
    review_now: 'अहिले समीक्षा गर्नुहोस्',
    expiring_memberships: 'समाप्त हुने सदस्यताहरू',
    pending_applications: 'विचाराधीन आवेदनहरू',
    role_owner: 'मालिक', role_admin: 'प्रशासक', role_commission: 'आयोग', role_member: 'सदस्य',
    pro_tip: 'सुझाव',
    pro_tip_message: 'सदस्यहरूलाई सक्रिय राख्न विचाराधीन आवेदनहरू छिटो अनुमोदन गर्नुहोस्।',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── Role label & badge ────────────────────────────────────────────────────────
const roleLabel = computed(() => {
  const map = { owner: t.value.role_owner, admin: t.value.role_admin, commission: t.value.role_commission, member: t.value.role_member }
  return map[props.role] ?? props.role
})

const roleBadgeClass = computed(() => {
  const map = {
    owner:      'bg-purple-100 text-purple-700',
    admin:      'bg-primary-100 text-primary-700',
    commission: 'bg-yellow-100 text-yellow-700',
    member:     'bg-green-100 text-green-700',
  }
  return map[props.role] ?? 'bg-slate-100 text-slate-600'
})

// ── Contextual primary action ─────────────────────────────────────────────────
const primaryAction = computed(() => {
  if (props.role === 'member' && props.memberSelf?.can_self_renew && props.memberSelf?.member_id) {
    return {
      label: t.value.renew_now,
      url: route('organisations.members.fees.index', [props.organisation.slug, props.memberSelf.member_id]),
      icon: ArrowPathIcon,
    }
  }
  if (props.role === 'member' && !props.memberSelf?.has_membership) {
    return { label: t.value.apply_now, url: props.memberSelf?.apply_url, icon: UserPlusIcon }
  }
  if (['owner', 'admin'].includes(props.role) && (props.stats?.pending_apps ?? 0) > 0) {
    return {
      label: `${t.value.review_pending} (${props.stats.pending_apps})`,
      url: route('organisations.membership.applications.index', props.organisation.slug),
      icon: DocumentTextIcon,
    }
  }
  return null
})

// ── KPI Cards ─────────────────────────────────────────────────────────────────
const visibleKPIs = computed(() => {
  const kpis = []
  const { stats, role, organisation } = props

  if (!stats || !['owner', 'admin', 'commission'].includes(role)) return kpis

  if (stats.total_members !== undefined && ['owner', 'admin'].includes(role)) {
    kpis.push({
      key: 'members', label: t.value.stat_total_members, value: stats.total_members,
      href: route('organisations.members.index', organisation.slug),
      icon: UsersIcon, color: 'blue',
    })
  }
  if (stats.pending_apps !== undefined) {
    kpis.push({
      key: 'apps', label: t.value.stat_pending_apps, value: stats.pending_apps,
      href: route('organisations.membership.applications.index', organisation.slug),
      icon: DocumentTextIcon, color: 'purple',
      trend: stats.pending_apps > 0 ? 'warning' : null,
      trendLabel: stats.pending_apps > 0 ? t.value.applications_need_review : '',
    })
  }
  if (stats.pending_fees_total !== undefined && ['owner', 'admin'].includes(role)) {
    kpis.push({
      key: 'fees', label: t.value.stat_pending_fees_total, value: `€${(stats.pending_fees_total ?? 0).toFixed(2)}`,
      href: route('organisations.members.index', organisation.slug) + '?filter=unpaid',
      icon: CurrencyEuroIcon, color: 'amber',
      trend: stats.pending_fees_total > 0 ? 'danger' : null,
      trendLabel: '',
    })
  }
  if (stats.expiring_in_30 !== undefined && ['owner', 'admin'].includes(role)) {
    kpis.push({
      key: 'expiring', label: t.value.stat_expiring_30, value: stats.expiring_in_30,
      href: route('organisations.members.index', organisation.slug) + '?filter=expiring',
      icon: ClockIcon, color: 'orange',
      trend: stats.expiring_in_30 > 0 ? 'warning' : null,
      trendLabel: '',
    })
  }
  return kpis
})

// ── Quick Actions ─────────────────────────────────────────────────────────────
const safeRoute = (name, params) => {
  try {
    return route(name, params)
  } catch (e) {
    console.error(`[Dashboard] Route not found: ${name}`, e.message)
    return '#'
  }
}

const quickActions = computed(() => {
  const actions = []
  const { role, organisation, memberSelf } = props

  if (['owner', 'admin', 'commission'].includes(role)) {
    actions.push({
      key: 'applications', title: t.value.nav_applications,
      description: role === 'commission' ? t.value.nav_view_only : t.value.nav_manage,
      href: safeRoute('organisations.membership.applications.index', organisation.slug),
      icon: DocumentTextIcon, color: 'purple',
    })
  }
  if (['owner', 'admin'].includes(role)) {
    actions.push({
      key: 'public_apply', title: t.value.nav_public_apply, description: t.value.nav_public_apply_desc,
      href: safeRoute('organisations.join', organisation.slug),
      icon: UserPlusIcon, color: 'amber',
    })
  }
  if (['owner', 'admin'].includes(role)) {
    actions.push({
      key: 'members', title: t.value.nav_members, description: t.value.nav_directory,
      href: safeRoute('organisations.members.index', organisation.slug),
      icon: UsersIcon, color: 'blue',
    })
    actions.push({
      key: 'manage_fees', title: t.value.nav_manage_fees, description: t.value.nav_manage_fees_desc,
      href: safeRoute('organisations.members.index', organisation.slug),
      icon: CurrencyEuroIcon, color: 'green',
    })
    actions.push({
      key: 'participants', title: t.value.nav_participants, description: t.value.nav_all_roles,
      href: safeRoute('organisations.membership.participants.index', organisation.slug),
      icon: UsersIcon, color: 'slate',
    })
    actions.push({
      key: 'roles', title: t.value.nav_roles, description: t.value.nav_roles_desc,
      href: safeRoute('organisations.membership.roles.index', organisation.slug),
      icon: UsersIcon, color: 'purple',
    })
  }
  if (role === 'owner') {
    actions.push({
      key: 'types', title: t.value.nav_types, description: t.value.nav_manage_tiers,
      href: safeRoute('organisations.membership-types.index', organisation.slug),
      icon: TagIcon, color: 'indigo',
    })
  }
  if (['owner', 'admin'].includes(role)) {
    actions.push({
      key: 'newsletters', title: t.value.nav_newsletters, description: t.value.nav_newsletters_desc,
      href: safeRoute('organisations.membership.newsletters.index', organisation.slug),
      icon: EnvelopeOpenIcon, color: 'emerald',
    })
    actions.push({
      key: 'invite_members', title: t.value.nav_invite_members, description: t.value.nav_invite_members_desc,
      href: safeRoute('organisations.members.invite', organisation.slug),
      icon: EnvelopeIcon, color: 'violet',
    })
    actions.push({
      key: 'invite_participants', title: t.value.nav_invite_participants, description: t.value.nav_invite_participants_desc,
      href: safeRoute('organisations.membership.participant-invitations.index', organisation.slug),
      icon: EnvelopeIcon, color: 'indigo',
    })
    actions.push({
      key: 'import_users', title: t.value.nav_import_users, description: t.value.nav_import_users_desc,
      href: safeRoute('organisations.users.import.index', organisation.slug),
      icon: ArrowUpTrayIcon, color: 'teal',
    })
    actions.push({
      key: 'import_members', title: t.value.nav_import_members, description: t.value.nav_import_members_desc,
      href: safeRoute('organisations.members.import', organisation.slug),
      icon: ArrowUpTrayIcon, color: 'emerald',
    })
    actions.push({
      key: 'import_participants', title: t.value.nav_import_participants, description: t.value.nav_import_participants_desc,
      href: safeRoute('organisations.membership.participants.import.create', organisation.slug),
      icon: ArrowUpTrayIcon, color: 'sky',
    })
  }
  if (role === 'member') {
    if (memberSelf?.has_membership && memberSelf?.member_id) {
      actions.push({
        key: 'my_fees', title: t.value.nav_my_fees, description: t.value.nav_payment_history,
        href: route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id]),
        icon: CurrencyEuroIcon, color: 'green',
      })
    }
    if (memberSelf?.can_self_renew && memberSelf?.member_id) {
      actions.push({
        key: 'renew', title: t.value.nav_renew, description: t.value.nav_extend,
        href: route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id]),
        icon: ArrowPathIcon, color: 'orange',
      })
    }
    if (!memberSelf?.has_membership) {
      actions.push({
        key: 'apply', title: t.value.nav_apply, description: t.value.nav_become_member,
        href: memberSelf?.apply_url ?? '#',
        icon: UserPlusIcon, color: 'amber',
      })
    }
  }
  return actions
})

// ── Alerts ────────────────────────────────────────────────────────────────────
const alerts = computed(() => {
  const list = []
  if ((props.stats?.pending_apps ?? 0) > 0 && ['owner', 'admin', 'commission'].includes(props.role)) {
    list.push({
      type: 'warning',
      title: t.value.pending_applications,
      message: `${props.stats.pending_apps} ${t.value.applications_need_review}`,
      action: {
        label: t.value.review_now,
        href: route('organisations.membership.applications.index', props.organisation.slug),
      },
    })
  }
  if ((props.stats?.expiring_in_30 ?? 0) > 0 && ['owner', 'admin'].includes(props.role)) {
    list.push({
      type: 'info',
      title: t.value.expiring_memberships,
      message: `${props.stats.expiring_in_30} ${t.value.members_expiring_soon}`,
      action: {
        label: t.value.view_expiring,
        href: route('organisations.members.index', props.organisation.slug) + '?filter=expiring',
      },
    })
  }
  return list
})

// ── Member empty state ────────────────────────────────────────────────────────
const noMembershipTitle = computed(() => {
  if (props.memberSelf?.platform_role === 'member') return t.value.no_membership_title_member_role
  if (props.memberSelf?.platform_role === 'voter')  return t.value.no_membership_title_voter
  return t.value.no_membership_title
})
const noMembershipDesc = computed(() => {
  if (props.memberSelf?.platform_role === 'member') return t.value.no_membership_desc_member_role
  if (props.memberSelf?.platform_role === 'voter')  return t.value.no_membership_desc_voter
  return t.value.no_membership_desc
})

// ── Member status appearance ──────────────────────────────────────────────────
const memberStatusIconBg = computed(() => {
  const s = props.memberSelf?.status
  if (s === 'active')  return 'bg-green-100'
  if (s === 'expired') return 'bg-danger-100'
  return 'bg-slate-100'
})
const memberStatusIconColor = computed(() => {
  const s = props.memberSelf?.status
  if (s === 'active')  return 'text-green-600'
  if (s === 'expired') return 'text-danger-600'
  return 'text-slate-500'
})
const memberStatusTextClass = computed(() => {
  const s = props.memberSelf?.status
  if (s === 'active')  return 'text-green-700'
  if (s === 'expired') return 'text-danger-700'
  return 'text-slate-700'
})
const memberStatusIcon = computed(() => {
  const s = props.memberSelf?.status
  if (s === 'active')  return CheckCircleIcon
  if (s === 'expired') return XCircleIcon
  return ClockIcon
})

// ── Helpers ───────────────────────────────────────────────────────────────────
const formatDate = (val) => {
  if (!val) return '—'
  return new Date(val).toLocaleDateString(locale.value === 'np' ? 'en-NP' : locale.value, {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>

