<template>
  <ElectionLayout>
    <!-- Skip link -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >Skip to main content</a>

    <main id="main-content" class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-50 py-8 relative overflow-hidden">
      <!-- Decorative left frame gradient -->
      <div
        class="absolute left-0 top-0 w-1/2 h-full pointer-events-none opacity-40"
        style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, transparent 50%);"
        aria-hidden="true"
      ></div>

      <!-- Decorative right frame gradient -->
      <div
        class="absolute right-0 top-0 w-1/2 h-full pointer-events-none opacity-40"
        style="background: linear-gradient(-135deg, rgba(245, 158, 11, 0.1) 0%, transparent 50%);"
        aria-hidden="true"
      ></div>

      <!-- Geometric accent borders -->
      <div
        class="absolute left-0 top-0 w-full h-1 pointer-events-none"
        style="background: linear-gradient(90deg, rgba(6, 182, 212, 0.5) 0%, rgba(245, 158, 11, 0.5) 50%, rgba(6, 182, 212, 0.5) 100%);"
        aria-hidden="true"
      ></div>

      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">

        <!-- Page Header -->
        <Card mode="admin" padding="lg" class="rounded-2xl">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">{{ t.page_eyebrow }}</p>
              <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ election.name }}</h1>
            </div>
            <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">
              <!-- Voter Import Link -->
              <a
                v-if="organisation"
                :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 font-semibold rounded-lg border-2 border-emerald-200 hover:border-emerald-400 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-emerald-200 transition-all duration-200 whitespace-nowrap"
                aria-label="Import voters from CSV file"
                title="Bulk import voters from a spreadsheet"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Import Voters</span>
                <span class="sm:hidden">Import</span>
              </a>
              <!-- Tutorial/Help Link -->
              <a
                href="/help/election-setup"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 font-semibold rounded-lg border-2 border-blue-200 hover:border-blue-400 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-200 transition-all duration-200 whitespace-nowrap"
                aria-label="Open Election Setup Guide (opens in new window)"
                title="Learn how to configure election settings and voter verification"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="hidden sm:inline">Setup Guide</span>
                <span class="sm:hidden">Guide</span>
              </a>
              <StatusBadge :status="election.status" size="md" />
            </div>
          </div>
        </Card>

        <!-- Flash Messages -->
        <div
          v-if="page.props.flash?.success"
          role="alert"
          class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-emerald-800">{{ page.props.flash.success }}</p>
        </div>
        <div
          v-if="page.props.flash?.error"
          role="alert"
          class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-red-800">{{ page.props.flash.error }}</p>
        </div>

        <!-- ── ACTIVATION BANNER ───────────────────────────────── -->
        <SectionCard v-if="election.status === 'planned'" variant="warning" padding="lg">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-base font-semibold text-amber-900">{{ t.sections.activate.title }}</h2>
                <p class="text-sm text-amber-700 mt-0.5" v-html="t.sections.activate.description"></p>
              </div>
            </div>
            <ActionButton
              variant="warning"
              size="md"
              :loading="isActivating"
              class="sm:flex-shrink-0 w-full sm:w-auto"
              @click="activateElection"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              {{ isActivating ? t.sections.activate.btn_activating : t.sections.activate.btn_activate }}
            </ActionButton>
          </div>
        </SectionCard>

        <!-- ── CURRENT STATUS ──────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">{{ t.sections.status.title }}</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Election System -->
            <div
              class="rounded-xl border p-5 flex items-center gap-4"
              :class="election.is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'"
            >
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="election.is_active ? 'bg-emerald-100' : 'bg-slate-100'"
              >
                <svg
                  class="w-5 h-5"
                  :class="election.is_active ? 'text-emerald-600' : 'text-slate-400'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                >
                  <path v-if="election.is_active" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase tracking-wide" :class="election.is_active ? 'text-emerald-600' : 'text-slate-400'">
                  {{ t.sections.status.election_system }}
                </p>
                <p class="text-sm font-semibold mt-0.5" :class="election.is_active ? 'text-emerald-800' : 'text-slate-600'">
                  {{ election.is_active ? t.sections.status.active : t.sections.status.inactive }}
                </p>
              </div>
            </div>

            <!-- Results Status -->
            <div
              class="rounded-xl border p-5 flex items-center gap-4"
              :class="election.results_published ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-200'"
            >
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="election.results_published ? 'bg-blue-100' : 'bg-slate-100'"
              >
                <svg
                  class="w-5 h-5"
                  :class="election.results_published ? 'text-blue-600' : 'text-slate-400'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase tracking-wide" :class="election.results_published ? 'text-blue-600' : 'text-slate-400'">
                  {{ t.sections.status.results }}
                </p>
                <p class="text-sm font-semibold mt-0.5" :class="election.results_published ? 'text-blue-800' : 'text-slate-600'">
                  {{ election.results_published ? t.sections.status.published : t.sections.status.unpublished }}
                </p>
              </div>
            </div>
          </div>
        </SectionCard>

        <!-- ── ELECTION DATES ─────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">{{ t.sections.dates.title }}</h2>
          </div>

          <form @submit.prevent="updateDates" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                {{ t.sections.dates.start_label }}
              </label>
              <input type="datetime-local" v-model="dateForm.start_date"
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                {{ t.sections.dates.end_label }}
              </label>
              <input type="datetime-local" v-model="dateForm.end_date"
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400" />
            </div>
            <div class="sm:col-span-2">
              <ActionButton variant="outline" size="md" type="submit" :loading="isSavingDates">
                {{ isSavingDates ? t.sections.dates.btn_saving : t.sections.dates.btn_save }}
              </ActionButton>
            </div>
          </form>
        </SectionCard>

        <!-- ── ORGANISATION LOGO ─────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-base font-semibold text-slate-800">{{ t.sections.logo.title }}</h2>
              <p class="text-xs text-slate-400 mt-0.5">{{ t.sections.logo.subtitle }}</p>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-6 items-start">
            <!-- Current logo preview -->
            <div class="flex-shrink-0">
              <div class="w-24 h-24 rounded-xl border-2 border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
                <img v-if="organisation?.logo"
                     :src="organisation.logo"
                     alt="Organisation logo"
                     class="w-full h-full object-contain p-1" />
                <svg v-else class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <p class="text-xs text-slate-400 text-center mt-1.5">
                {{ organisation?.logo ? t.sections.logo.current : t.sections.logo.no_logo }}
              </p>
            </div>

            <!-- Upload form -->
            <form @submit.prevent="uploadLogo" class="flex-1 space-y-3">
              <label class="block">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block mb-1.5">
                  {{ t.sections.logo.upload_label }}
                </span>
                <input type="file"
                       ref="logoFileInput"
                       accept="image/*"
                       @change="onLogoFileChange"
                       class="block w-full text-sm text-slate-600
                              file:mr-3 file:py-2 file:px-4 file:rounded-lg
                              file:border-0 file:text-sm file:font-semibold
                              file:bg-slate-100 file:text-slate-700
                              hover:file:bg-slate-200 cursor-pointer" />
              </label>
              <p class="text-xs text-slate-400">{{ t.sections.logo.file_hint }}</p>
              <ActionButton variant="outline" size="md" type="submit" :loading="isUploadingLogo" :disabled="!logoFile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                {{ isUploadingLogo ? t.sections.logo.btn_uploading : t.sections.logo.btn_upload }}
              </ActionButton>
            </form>
          </div>
        </SectionCard>

        <!-- ── VOTING STATISTICS ───────────────────────────────── -->
        <SectionCard v-if="stats && Object.keys(stats).length" padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">{{ t.sections.statistics.title }}</h2>
          </div>

          <!-- Summary Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
            <div class="rounded-xl bg-violet-50 border border-violet-200 p-5">
              <p class="text-xs font-semibold text-violet-500 uppercase tracking-wide">{{ t.sections.statistics.total_members }}</p>
              <p class="text-3xl font-bold text-violet-800 mt-1">{{ stats.total_memberships ?? 0 }}</p>
              <p class="text-xs text-violet-500 mt-0.5">{{ t.sections.statistics.registered }}</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-5">
              <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wide">{{ t.sections.statistics.active_voters }}</p>
              <p class="text-3xl font-bold text-emerald-800 mt-1">{{ stats.active_voters ?? 0 }}</p>
              <p class="text-xs text-emerald-500 mt-0.5">{{ t.sections.statistics.approved }}</p>
            </div>
            <div class="rounded-xl bg-blue-50 border border-blue-200 p-5">
              <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide">{{ t.sections.statistics.eligible_voters }}</p>
              <p class="text-3xl font-bold text-blue-800 mt-1">{{ stats.eligible_voters ?? 0 }}</p>
              <p class="text-xs text-blue-500 mt-0.5">{{ t.sections.statistics.not_expired }}</p>
            </div>
          </div>

          <!-- Status Breakdown -->
          <div v-if="stats.by_status" class="rounded-xl bg-slate-50 border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">{{ t.sections.statistics.breakdown_title }}</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <div class="text-center">
                <p class="text-2xl font-bold text-emerald-700">{{ stats.by_status.active ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.statistics.status_active }}</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-amber-600">{{ stats.by_status.invited ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.statistics.status_invited }}</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-slate-400">{{ stats.by_status.inactive ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.statistics.status_inactive }}</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-red-500">{{ stats.by_status.removed ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.statistics.status_removed }}</p>
              </div>
            </div>
          </div>

          <!-- Empty state: no voters -->
          <EmptyState
            v-if="!stats.total_memberships"
            :title="t.sections.statistics.empty_title"
            :description="t.sections.statistics.empty_desc"
          >
            <template #icon>
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </template>
          </EmptyState>
        </SectionCard>

        <!-- ── VOTING PERIOD CONTROL ───────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div
              class="w-10 h-10 rounded-xl flex items-center justify-center"
              :class="isVotingActive ? 'bg-emerald-100' : 'bg-slate-100'"
            >
              <svg
                class="w-5 h-5"
                :class="isVotingActive ? 'text-emerald-600' : 'text-slate-400'"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-base font-semibold text-slate-800">{{ t.sections.voting_control.title }}</h2>
              <p class="text-xs mt-0.5" :class="isVotingActive ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                {{ isVotingActive ? t.sections.voting_control.currently_active : t.sections.voting_control.currently_inactive }}
              </p>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <ActionButton
              v-if="!isVotingActive"
              variant="success"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="openVoting"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              {{ t.sections.voting_control.btn_open }}
            </ActionButton>

            <ActionButton
              v-if="isVotingActive"
              variant="danger"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="closeVoting"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
              </svg>
              {{ t.sections.voting_control.btn_close }}
            </ActionButton>
          </div>
        </SectionCard>

        <!-- ── POSTS & CANDIDATES MANAGEMENT ─────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
              <h2 class="text-base font-semibold text-slate-800">{{ t.sections.posts.title }}</h2>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
              <p class="text-2xl font-bold text-slate-700">{{ postsCount }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.posts.positions_label }}</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center">
              <p class="text-2xl font-bold text-emerald-700">{{ candidatesCount }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.posts.candidates_label }}</p>
            </div>
          </div>

          <div class="flex flex-wrap gap-3">
            <ActionButton as="a" variant="outline" size="md" :href="postsUrl" class="w-full sm:w-auto">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
              </svg>
              {{ t.sections.posts.btn_positions }}
            </ActionButton>
            <ActionButton as="a" variant="outline" size="md" :href="candidaciesUrl" class="w-full sm:w-auto">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
              {{ t.sections.posts.btn_candidates }}
            </ActionButton>
          </div>
        </SectionCard>

        <!-- ── CANDIDACY APPLICATION REVIEW ─────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-base font-semibold text-slate-800">{{ t.sections.applications.title }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.applications.subtitle }}</p>
              </div>
            </div>
          </div>
          <ActionButton as="a" variant="outline" size="md" :href="candidacyApplicationsUrl" class="w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            {{ t.sections.applications.btn_review }}
          </ActionButton>
        </SectionCard>

        <!-- ── VOTER MANAGEMENT ────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <h2 class="text-base font-semibold text-slate-800">{{ t.sections.voter_management.title }}</h2>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
              <p class="text-2xl font-bold text-slate-700">{{ stats.total_memberships ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.voter_management.total_label }}</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center">
              <p class="text-2xl font-bold text-emerald-700">{{ stats.active_voters ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.voter_management.approved_label }}</p>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-center">
              <p class="text-2xl font-bold text-amber-600">{{ stats.by_status?.inactive ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ t.sections.voter_management.suspended_label }}</p>
            </div>
          </div>

          <ActionButton variant="outline" size="md" :href="voterListUrl" class="w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            {{ t.sections.voter_management.btn_manage }}
          </ActionButton>
        </SectionCard>

        <!-- ── ELECTION SETTINGS ───────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-base font-semibold text-slate-800">Election Settings</h2>
              <p class="text-xs text-slate-500 mt-0.5">Configure voting rules and security restrictions</p>
            </div>
          </div>

          <ActionButton as="a" variant="outline" size="md" :href="settingsUrl" class="w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Configure Settings
          </ActionButton>
        </SectionCard>

        <!-- ── RESULT MANAGEMENT ───────────────────────────────── -->
        <SectionCard v-if="canPublish" padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">{{ t.sections.results.title }}</h2>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <ActionButton
              v-if="!election.results_published"
              variant="success"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="publishResults"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8"/>
              </svg>
              {{ t.sections.results.btn_publish }}
            </ActionButton>

            <ActionButton
              v-if="election.results_published"
              variant="outline"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="unpublishResults"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
              {{ t.sections.results.btn_unpublish }}
            </ActionButton>
          </div>
        </SectionCard>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import Card from '@/Components/Card.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import ActionButton from '@/Components/ActionButton.vue'
import SectionCard from '@/Components/SectionCard.vue'
import EmptyState from '@/Components/EmptyState.vue'

import pageDe from '@/locales/pages/Election/Management/de.json'
import pageEn from '@/locales/pages/Election/Management/en.json'
import pageNp from '@/locales/pages/Election/Management/np.json'

const props = defineProps({
  election:        { type: Object,  required: true },
  organisation:    { type: Object,  default: null },
  stats:           { type: Object,  default: () => ({}) },
  canPublish:      { type: Boolean, default: false },
  postsCount:      { type: Number,  default: 0 },
  candidatesCount: { type: Number,  default: 0 },
})

const page = usePage()

// Translation
const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.de)

const isLoading       = ref(false)
const isActivating    = ref(false)
const isSavingDates   = ref(false)
const isUploadingLogo = ref(false)
const logoFile        = ref(null)
const logoFileInput   = ref(null)

const onLogoFileChange = (e) => {
  logoFile.value = e.target.files[0] ?? null
}

const uploadLogo = () => {
  if (!logoFile.value) return
  isUploadingLogo.value = true
  const formData = new FormData()
  formData.append('logo', logoFile.value)
  router.post(route('elections.upload-logo', { election: props.election.slug }), formData, {
    forceFormData: true,
    preserveScroll: true,
    onFinish: () => {
      isUploadingLogo.value = false
      logoFile.value = null
      if (logoFileInput.value) logoFileInput.value.value = ''
    },
  })
}

function toDatetimeLocal(raw) {
  if (!raw) return ''
  return new Date(raw).toISOString().slice(0, 16)
}

const dateForm = ref({
  start_date: toDatetimeLocal(props.election.start_date),
  end_date:   toDatetimeLocal(props.election.end_date),
})

const updateDates = () => {
  isSavingDates.value = true
  router.patch(route('elections.update-dates', { election: props.election.slug }), dateForm.value, {
    preserveScroll: true,
    onFinish: () => { isSavingDates.value = false },
  })
}

const isVotingActive = computed(() => props.election.status === 'active')

const settingsUrl = computed(() =>
  route('elections.settings.edit', {
    election: props.election.slug,
  })
)

const voterListUrl = computed(() =>
  route('elections.voters.index', {
    organisation: props.election.organisation?.slug,
    election:     props.election.slug,
  })
)

const postsUrl = computed(() =>
  route('organisations.elections.posts.index', {
    organisation: props.election.organisation?.slug,
    election:     props.election.slug,
  })
)

const candidacyApplicationsUrl = computed(() =>
  route('organisations.elections.candidacy.applications', {
    organisation: props.election.organisation?.slug,
    election:     props.election.slug,
  })
)

const candidaciesUrl = computed(() =>
  route('organisations.elections.candidacies.index', {
    organisation: props.election.organisation?.slug,
    election:     props.election.slug,
  })
)

const activateElection = () => {
  if (!confirm(t.value.confirm.activate)) return
  isActivating.value = true
  router.post(route('elections.activate', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isActivating.value = false },
  })
}

const publishResults = () => {
  if (!confirm(t.value.confirm.publish)) return
  isLoading.value = true
  router.post(route('elections.publish', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const unpublishResults = () => {
  if (!confirm(t.value.confirm.unpublish)) return
  isLoading.value = true
  router.post(route('elections.unpublish', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const openVoting = () => {
  if (!confirm(t.value.confirm.open_voting)) return
  isLoading.value = true
  router.post(route('elections.open-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const closeVoting = () => {
  if (!confirm(t.value.confirm.close_voting)) return
  isLoading.value = true
  router.post(route('elections.close-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
</script>

<style scoped>
/* Modern Democratic Design - Framed Main Content */

/* Enhanced card styling for visual hierarchy */
:deep(.rounded-2xl) {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.rounded-2xl:hover) {
  box-shadow: 0 12px 30px rgba(6, 182, 212, 0.12);
  transform: translateY(-2px);
}

/* Animated entrance for content sections */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

main > div > * {
  animation: slideInUp 0.6s ease-out;
  animation-fill-mode: both;
}

main > div > *:nth-child(1) { animation-delay: 0.1s; }
main > div > *:nth-child(2) { animation-delay: 0.2s; }
main > div > *:nth-child(3) { animation-delay: 0.3s; }
main > div > *:nth-child(4) { animation-delay: 0.4s; }
main > div > *:nth-child(5) { animation-delay: 0.5s; }
main > div > *:nth-child(n+6) { animation-delay: 0.6s; }

/* Decorative elements positioning and animation */
:deep(main > div) {
  position: relative;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  main > div > * {
    animation: none;
    opacity: 1;
    transform: none;
  }
}

/* Enhanced focus states for accessibility */
:deep(main a):focus-visible,
:deep(main button):focus-visible {
  outline: 3px solid #0369a1;
  outline-offset: 2px;
}

/* Gradient text effect for headers (subtle) */
:deep(main h1) {
  background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Card border animation on hover */
:deep(.rounded-xl) {
  position: relative;
  overflow: hidden;
}

:deep(.rounded-xl)::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s ease;
  pointer-events: none;
}

:deep(.rounded-xl:hover)::before {
  left: 100%;
}
</style>
