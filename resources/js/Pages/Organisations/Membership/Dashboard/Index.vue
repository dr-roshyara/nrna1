<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-slate-50">

      <!-- Hero header -->
      <div class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
          <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5">
            <a :href="route('organisations.show', organisation.slug)" class="hover:text-purple-600 transition-colors">
              {{ organisation.name }}
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-medium">{{ t.title }}</span>
          </nav>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-13 h-13 rounded-2xl bg-purple-600 flex items-center justify-center shadow-sm p-3">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                     M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                     m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ t.title }}</h1>
              <p class="text-slate-500 text-sm mt-1">
                {{ organisation.name }}
                <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium" :class="roleBadgeClass">
                  {{ roleLabel }}
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Flash / Error banners -->
      <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6">
        <div v-if="page.props.flash?.success"
             class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ page.props.flash.success }}
        </div>
        <div v-if="page.props.errors?.error"
             class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
          {{ page.props.errors.error }}
        </div>
      </div>

      <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-12">

        <!-- ════════════════════════════════════════════════
             STAT CARDS  (owner/admin: 4 · commission: 2)
             Clickable — each links to the relevant section
        ════════════════════════════════════════════════ -->
        <div v-if="stats && Object.keys(stats).length" class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">

          <a :href="route('organisations.members.index', organisation.slug)"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all block group">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_total_members }}</p>
            <p class="text-3xl font-bold text-slate-800">{{ stats.total_members ?? 0 }}</p>
            <p class="text-xs text-slate-400 mt-1 group-hover:text-purple-500 transition-colors">{{ t.view_all }} →</p>
          </a>

          <a :href="route('organisations.membership.applications.index', organisation.slug) + '?status=pending'"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all block group">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_pending_apps }}</p>
            <p class="text-3xl font-bold" :class="(stats.pending_apps ?? 0) > 0 ? 'text-blue-600' : 'text-slate-800'">
              {{ stats.pending_apps ?? 0 }}
            </p>
            <p class="text-xs text-slate-400 mt-1 group-hover:text-purple-500 transition-colors">{{ t.review }} →</p>
          </a>

          <a v-if="stats.pending_fees_total !== undefined"
             :href="route('organisations.members.index', organisation.slug) + '?filter=unpaid'"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all block group">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_pending_fees_total }}</p>
            <p class="text-3xl font-bold" :class="(stats.pending_fees_total ?? 0) > 0 ? 'text-amber-600' : 'text-slate-800'">
              {{ (stats.pending_fees_total ?? 0).toFixed(2) }}
            </p>
            <p class="text-xs text-slate-400 mt-1 group-hover:text-purple-500 transition-colors">{{ t.view_unpaid }} →</p>
          </a>

          <a v-if="stats.expiring_in_30 !== undefined"
             :href="route('organisations.members.index', organisation.slug) + '?filter=expiring'"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all block group">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ t.stat_expiring_30 }}</p>
            <p class="text-3xl font-bold" :class="(stats.expiring_in_30 ?? 0) > 0 ? 'text-orange-600' : 'text-slate-800'">
              {{ stats.expiring_in_30 ?? 0 }}
            </p>
            <p class="text-xs text-slate-400 mt-1 group-hover:text-purple-500 transition-colors">{{ t.view_expiring }} →</p>
          </a>

        </div>

        <!-- ════════════════════════════════════════════════
             QUICK NAVIGATION CARDS  (role-aware strip)
        ════════════════════════════════════════════════ -->
        <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

          <!-- Applications (owner/admin/commission) -->
          <a v-if="['owner', 'admin', 'commission'].includes(role)"
             :href="route('organisations.membership.applications.index', organisation.slug)"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_applications }}</p>
              <p class="text-xs text-slate-400 truncate">{{ role === 'commission' ? t.nav_view_only : t.nav_manage }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-purple-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

          <!-- Members (owner/admin) -->
          <a v-if="['owner', 'admin'].includes(role)"
             :href="route('organisations.members.index', organisation.slug)"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_members }}</p>
              <p class="text-xs text-slate-400 truncate">{{ t.nav_directory }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

          <!-- Membership Types (owner only) -->
          <a v-if="role === 'owner'"
             :href="route('organisations.membership-types.index', organisation.slug)"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_types }}</p>
              <p class="text-xs text-slate-400 truncate">{{ t.nav_manage_tiers }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-purple-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

          <!-- My Fees (member with active membership) -->
          <a v-if="role === 'member' && memberSelf?.has_membership && memberSelf?.member_id"
             :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-green-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_my_fees }}</p>
              <p class="text-xs text-slate-400 truncate">{{ t.nav_payment_history }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-green-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

          <!-- Renew (member with renewal eligibility) -->
          <a v-if="role === 'member' && memberSelf?.can_self_renew && memberSelf?.member_id"
             :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-orange-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_renew }}</p>
              <p class="text-xs text-slate-400 truncate">{{ t.nav_extend }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-orange-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

          <!-- Apply (non-member or no active membership) -->
          <a v-if="role === 'member' && !memberSelf?.has_membership"
             :href="route('organisations.membership.apply', organisation.slug)"
             class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-700 truncate">{{ t.nav_apply }}</p>
              <p class="text-xs text-slate-400 truncate">{{ t.nav_become_member }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-amber-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>

        </div>

        <!-- ════════════════════════════════════════════════
             MEMBER SELF-VIEW  (role = member)
        ════════════════════════════════════════════════ -->
        <template v-if="role === 'member' && memberSelf">

          <!-- No membership yet — contextual by platform_role -->
          <div v-if="!memberSelf.has_membership"
               class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
            <svg class="w-12 h-12 mx-auto mb-4"
                 :class="memberSelf.platform_role === 'member' ? 'text-amber-300' : 'text-slate-300'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>

            <!-- Has platform role 'member' but no formal membership record yet -->
            <template v-if="memberSelf.platform_role === 'member'">
              <h2 class="text-lg font-semibold text-amber-700 mb-2">{{ t.no_membership_title_member_role }}</h2>
              <p class="text-sm text-slate-500 mb-6">{{ t.no_membership_desc_member_role }}</p>
            </template>
            <!-- Has voter role but no formal membership -->
            <template v-else-if="memberSelf.platform_role === 'voter'">
              <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ t.no_membership_title_voter }}</h2>
              <p class="text-sm text-slate-500 mb-6">{{ t.no_membership_desc_voter }}</p>
            </template>
            <!-- No platform role or other role -->
            <template v-else>
              <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ t.no_membership_title }}</h2>
              <p class="text-sm text-slate-500 mb-6">{{ t.no_membership_desc }}</p>
            </template>

            <a :href="memberSelf.apply_url"
               class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-purple-700 transition-colors">
              {{ t.apply_now }}
            </a>
          </div>

          <!-- Has membership — status cards -->
          <div v-else class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ t.stat_status }}</p>
              <span :class="memberStatusClass(memberSelf.status)"
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-semibold">
                {{ memberSelf.status }}
              </span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ t.stat_expires }}</p>
              <p v-if="memberSelf.expires_at" class="text-lg font-bold"
                 :class="memberSelf.expires_in_days !== null && memberSelf.expires_in_days <= 30 ? 'text-orange-600' : 'text-slate-800'">
                {{ formatDate(memberSelf.expires_at) }}
                <span v-if="memberSelf.expires_in_days !== null" class="text-xs font-normal text-slate-400 ml-1">
                  ({{ memberSelf.expires_in_days }}d)
                </span>
              </p>
              <p v-else class="text-sm font-semibold text-purple-700">{{ t.lifetime }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ t.stat_pending_fees }}</p>
              <p class="text-lg font-bold" :class="memberSelf.pending_fees > 0 ? 'text-red-600' : 'text-slate-800'">
                {{ memberSelf.pending_fees > 0 ? memberSelf.pending_fees.toFixed(2) + ' EUR' : t.no_pending_fees }}
              </p>
            </div>

          </div>

          <!-- Renewal CTA banner -->
          <div v-if="memberSelf.has_membership && memberSelf.can_self_renew && memberSelf.member_id"
               class="mt-5 bg-purple-50 border border-purple-200 rounded-xl p-5 flex items-center justify-between flex-wrap gap-3">
            <div>
              <p class="text-sm font-semibold text-purple-800">{{ t.renewal_available }}</p>
              <p class="text-xs text-purple-600 mt-0.5">{{ t.renewal_cta_desc }}</p>
            </div>
            <a :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
               class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 transition-colors">
              {{ t.renew_now }}
            </a>
          </div>

          <!-- My Applications section -->
          <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <h2 class="text-base font-semibold text-slate-800">{{ t.my_applications }}</h2>
            </div>

            <div v-if="memberSelf.my_applications && memberSelf.my_applications.length > 0"
                 class="divide-y divide-slate-100">
              <div v-for="app in memberSelf.my_applications" :key="app.id" class="px-6 py-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                  <div>
                    <p class="text-sm font-medium text-slate-800">{{ app.membership_type ?? t.membership }}</p>
                    <p class="text-xs text-slate-400">{{ t.submitted }}: {{ formatDate(app.submitted_at) }}</p>
                  </div>
                  <span :class="statusClass(app.status)"
                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
                    {{ statusLabel(app.status) }}
                  </span>
                </div>
              </div>
            </div>

            <div v-else class="px-6 py-10 text-center text-slate-400 text-sm">
              <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <p>{{ t.no_applications }}</p>
              <a :href="route('organisations.membership.apply', organisation.slug)"
                 class="inline-block mt-2 text-purple-600 hover:underline">
                {{ t.apply_now }} →
              </a>
            </div>
          </div>

        </template>

        <!-- ════════════════════════════════════════════════
             ADMIN / OWNER / COMMISSION MAIN CONTENT
        ════════════════════════════════════════════════ -->
        <template v-else-if="role !== 'member'">

          <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Applications table (2/3 width) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-800">{{ t.applications_title }}</h2>
                <a :href="route('organisations.membership.applications.index', organisation.slug)"
                   class="text-xs font-medium text-purple-600 hover:text-purple-800">
                  {{ t.view_all }} →
                </a>
              </div>

              <div v-if="applications && applications.data && applications.data.length > 0"
                   class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                  <thead class="bg-slate-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_applicant }}</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_type }}</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_status }}</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_actions }}</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr v-for="app in applications.data" :key="app.id" class="hover:bg-slate-50 transition-colors">
                      <td class="px-6 py-3">
                        <div class="text-sm font-medium text-slate-900">{{ app.user?.name }}</div>
                        <div class="text-xs text-slate-400">{{ app.user?.email }}</div>
                      </td>
                      <td class="px-6 py-3 text-sm text-slate-600 whitespace-nowrap">
                        {{ app.membership_type?.name ?? '—' }}
                      </td>
                      <td class="px-6 py-3 whitespace-nowrap">
                        <span :class="statusClass(app.status)"
                              class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
                          {{ statusLabel(app.status) }}
                        </span>
                      </td>
                      <td class="px-6 py-3 whitespace-nowrap text-right text-sm">
                        <a :href="route('organisations.membership.applications.show', [organisation.slug, app.id])"
                           class="text-purple-600 hover:text-purple-900 font-medium">
                          {{ t.review }}
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div v-else class="py-14 text-center text-slate-400 text-sm">
                <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ t.no_applications }}
              </div>
            </div>

            <!-- Side panels (1/3 width) -->
            <div class="space-y-5">

              <!-- Expiring members (owner/admin) -->
              <div v-if="expiringMembers && expiringMembers.length > 0"
                   class="bg-white rounded-xl shadow-sm border border-orange-200 p-5">
                <h3 class="text-sm font-semibold text-orange-700 mb-3 flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ t.expiring_soon }} ({{ expiringMembers.length }})
                </h3>
                <ul class="space-y-2">
                  <li v-for="m in expiringMembers.slice(0, 5)" :key="m.id"
                      class="flex items-center justify-between text-sm">
                    <span class="text-slate-700">{{ m.organisationUser?.user?.name ?? '—' }}</span>
                    <span class="text-orange-600 text-xs font-medium">{{ formatDate(m.membership_expires_at) }}</span>
                  </li>
                </ul>
                <p v-if="expiringMembers.length > 5" class="mt-2 text-xs text-slate-400">
                  +{{ expiringMembers.length - 5 }} {{ t.more }}
                </p>
              </div>

              <!-- Recent activity (owner/admin) -->
              <div v-if="recentActivity && recentActivity.length > 0"
                   class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">{{ t.recent_activity }}</h3>
                <ul class="space-y-3">
                  <li v-for="(item, i) in recentActivity.slice(0, 6)" :key="i"
                      class="flex items-start gap-2 text-xs text-slate-600">
                    <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center"
                          :class="item.type === 'payment' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600'">
                      <svg v-if="item.type === 'payment'" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                    </span>
                    <span class="leading-snug">{{ item.message }}</span>
                  </li>
                </ul>
              </div>

              <!-- Empty side panel placeholder for commission (no expiring/activity) -->
              <div v-if="role === 'commission' && (!expiringMembers || !expiringMembers.length) && (!recentActivity || !recentActivity.length)"
                   class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center text-slate-400 text-sm">
                <svg class="w-8 h-8 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ t.all_up_to_date }}
              </div>

            </div>
          </div>

        </template>

      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation:    { type: Object, required: true },
  role:            { type: String, required: true },
  stats:           { type: Object,  default: () => ({}) },
  applications:    { type: Object,  default: null },
  expiringMembers: { type: Array,   default: () => [] },
  recentActivity:  { type: Array,   default: () => [] },
  memberSelf:      { type: Object,  default: null },
})

const page    = usePage()
const { locale } = useI18n()

// ── i18n ──────────────────────────────────────────────────────────────────────

const translations = {
  en: {
    title: 'Membership Dashboard',
    // Stat card labels
    stat_total_members: 'Total Members',
    stat_pending_apps: 'Pending Applications',
    stat_pending_fees_total: 'Pending Fees (EUR)',
    stat_expiring_30: 'Expiring in 30 Days',
    stat_status: 'Membership Status',
    stat_expires: 'Expires',
    stat_pending_fees: 'Outstanding Fees',
    lifetime: 'Lifetime',
    no_pending_fees: 'None',
    // Stat card link labels
    view_all: 'View all', view_unpaid: 'View unpaid', view_expiring: 'View expiring', review: 'Review',
    // Nav cards
    nav_applications: 'Applications', nav_view_only: 'View only', nav_manage: 'Manage requests',
    nav_members: 'Members', nav_directory: 'Directory & management',
    nav_types: 'Types', nav_manage_tiers: 'Manage tiers',
    nav_my_fees: 'My Fees', nav_payment_history: 'Payment history',
    nav_renew: 'Renew', nav_extend: 'Extend membership',
    nav_apply: 'Apply', nav_become_member: 'Become a member',
    // Applications table
    applications_title: 'Recent Applications',
    col_applicant: 'Applicant', col_type: 'Type', col_status: 'Status', col_actions: 'Actions',
    no_applications: 'No applications yet.',
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
    // Role labels
    role_owner: 'Owner', role_admin: 'Admin', role_commission: 'Commission', role_member: 'Member',
  },
  de: {
    title: 'Mitgliedschafts-Dashboard',
    stat_total_members: 'Mitglieder gesamt',
    stat_pending_apps: 'Ausstehende Anträge',
    stat_pending_fees_total: 'Ausstehende Gebühren (EUR)',
    stat_expiring_30: 'Läuft in 30 Tagen ab',
    stat_status: 'Mitgliedschaftsstatus',
    stat_expires: 'Läuft ab',
    stat_pending_fees: 'Offene Gebühren',
    lifetime: 'Lebenslang',
    no_pending_fees: 'Keine',
    view_all: 'Alle anzeigen', view_unpaid: 'Unbezahlt', view_expiring: 'Ablaufend', review: 'Prüfen',
    nav_applications: 'Anträge', nav_view_only: 'Nur lesen', nav_manage: 'Verwalten',
    nav_members: 'Mitglieder', nav_directory: 'Verzeichnis',
    nav_types: 'Typen', nav_manage_tiers: 'Stufen verwalten',
    nav_my_fees: 'Meine Gebühren', nav_payment_history: 'Zahlungshistorie',
    nav_renew: 'Verlängern', nav_extend: 'Mitgliedschaft verlängern',
    nav_apply: 'Beantragen', nav_become_member: 'Mitglied werden',
    applications_title: 'Aktuelle Anträge',
    col_applicant: 'Antragsteller', col_type: 'Typ', col_status: 'Status', col_actions: 'Aktionen',
    no_applications: 'Keine Anträge vorhanden.',
    no_membership_title: 'Sie sind noch kein Mitglied',
    no_membership_desc: 'Beantragen Sie die Mitgliedschaft.',
    no_membership_title_member_role: 'Mitgliedschaft nicht abgeschlossen',
    no_membership_desc_member_role: 'Sie haben Plattformzugang, aber Ihr offizieller Mitgliedschaftsdatensatz wurde noch nicht erstellt. Bitte stellen Sie einen Antrag oder kontaktieren Sie Ihren Administrator.',
    no_membership_title_voter: 'Sie sind als Wähler registriert',
    no_membership_desc_voter: 'Sie können an Wahlen teilnehmen, haben aber noch keine bezahlte Mitgliedschaft. Beantragen Sie die Vollmitgliedschaft.',
    apply_now: 'Jetzt beantragen',
    renewal_available: 'Verlängerung möglich',
    renewal_cta_desc: 'Ihre Mitgliedschaft kann verlängert werden.',
    renew_now: 'Jetzt verlängern',
    my_applications: 'Meine Anträge',
    membership: 'Mitgliedschaft', submitted: 'Eingereicht',
    expiring_soon: 'Läuft bald ab', more: 'weitere', recent_activity: 'Letzte Aktivitäten',
    all_up_to_date: 'Alles auf dem neuesten Stand.',
    role_owner: 'Eigentümer', role_admin: 'Administrator', role_commission: 'Kommission', role_member: 'Mitglied',
  },
  np: {
    title: 'सदस्यता ड्यासबोर्ड',
    stat_total_members: 'कुल सदस्यहरू',
    stat_pending_apps: 'विचाराधीन आवेदनहरू',
    stat_pending_fees_total: 'बाँकी शुल्क (EUR)',
    stat_expiring_30: '३० दिनमा समाप्त',
    stat_status: 'सदस्यता स्थिति',
    stat_expires: 'म्याद सकिन्छ',
    stat_pending_fees: 'बाँकी शुल्क',
    lifetime: 'आजीवन',
    no_pending_fees: 'कुनै छैन',
    view_all: 'सबै हेर्नुहोस्', view_unpaid: 'अपठित', view_expiring: 'समाप्त हुने', review: 'समीक्षा',
    nav_applications: 'आवेदनहरू', nav_view_only: 'हेर्न मात्र', nav_manage: 'व्यवस्थापन',
    nav_members: 'सदस्यहरू', nav_directory: 'निर्देशिका',
    nav_types: 'प्रकारहरू', nav_manage_tiers: 'स्तर व्यवस्थापन',
    nav_my_fees: 'मेरो शुल्क', nav_payment_history: 'भुक्तानी इतिहास',
    nav_renew: 'नवीकरण', nav_extend: 'सदस्यता बढाउनुहोस्',
    nav_apply: 'आवेदन', nav_become_member: 'सदस्य बन्नुहोस्',
    applications_title: 'हालका आवेदनहरू',
    col_applicant: 'आवेदक', col_type: 'प्रकार', col_status: 'स्थिति', col_actions: 'कार्यहरू',
    no_applications: 'अहिलेसम्म कुनै आवेदन छैन।',
    no_membership_title: 'तपाईं अझै सदस्य हुनुभएको छैन',
    no_membership_desc: 'सदस्यताको लागि आवेदन दिनुहोस्।',
    no_membership_title_member_role: 'सदस्यता नामांकन अपूर्ण',
    no_membership_desc_member_role: 'तपाईंसँग प्लेटफर्म पहुँच छ तर औपचारिक सदस्यता अभिलेख बनाइएको छैन। कृपया आवेदन दिनुहोस् वा आफ्नो प्रशासकलाई सम्पर्क गर्नुहोस्।',
    no_membership_title_voter: 'तपाईं मतदाताको रूपमा दर्ता हुनुभएको छ',
    no_membership_desc_voter: 'तपाईं निर्वाचनमा भाग लिन सक्नुहुन्छ, तर अहिलेसम्म तपाईंको भुक्तान सदस्यता छैन। पूर्ण सदस्यताको लागि आवेदन दिनुहोस्।',
    apply_now: 'अहिले आवेदन दिनुहोस्',
    renewal_available: 'नवीकरण उपलब्ध',
    renewal_cta_desc: 'तपाईंको सदस्यता नवीकरण योग्य छ।',
    renew_now: 'अहिले नवीकरण गर्नुहोस्',
    my_applications: 'मेरा आवेदनहरू',
    membership: 'सदस्यता', submitted: 'पेश गरिएको',
    expiring_soon: 'चाँडै समाप्त हुने', more: 'थप', recent_activity: 'हालका गतिविधिहरू',
    all_up_to_date: 'सबै अद्यावधिक छ।',
    role_owner: 'मालिक', role_admin: 'प्रशासक', role_commission: 'आयोग', role_member: 'सदस्य',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── Computed ──────────────────────────────────────────────────────────────────

const roleLabel = computed(() => {
  const map = { owner: t.value.role_owner, admin: t.value.role_admin, commission: t.value.role_commission, member: t.value.role_member }
  return map[props.role] ?? props.role
})

const roleBadgeClass = computed(() => {
  const map = { owner: 'bg-purple-100 text-purple-700', admin: 'bg-blue-100 text-blue-700', commission: 'bg-yellow-100 text-yellow-700', member: 'bg-green-100 text-green-700' }
  return map[props.role] ?? 'bg-slate-100 text-slate-600'
})

// ── Helpers ───────────────────────────────────────────────────────────────────

const formatDate = (val) => {
  if (!val) return '—'
  return new Date(val).toLocaleDateString(locale.value === 'np' ? 'en-NP' : locale.value, {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}

const statusLabel = (status) => {
  const map = { draft: 'Draft', submitted: 'Submitted', under_review: 'Under Review', approved: 'Approved', rejected: 'Rejected' }
  return map[status] ?? status
}

const statusClass = (status) => {
  const map = {
    draft:        'bg-slate-100 text-slate-600',
    submitted:    'bg-blue-100 text-blue-700',
    under_review: 'bg-yellow-100 text-yellow-700',
    approved:     'bg-green-100 text-green-800',
    rejected:     'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-slate-100 text-slate-600'
}

const memberStatusClass = (status) => {
  const map = { active: 'bg-green-100 text-green-800', expired: 'bg-red-100 text-red-700', ended: 'bg-slate-100 text-slate-600' }
  return map[status] ?? 'bg-slate-100 text-slate-600'
}
</script>
