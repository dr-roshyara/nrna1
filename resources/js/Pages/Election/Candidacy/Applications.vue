<template>
  <ElectionLayout>
    <main class="review-page">

      <div class="page-texture" aria-hidden="true"></div>

      <div class="review-container">

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <a :href="route('organisations.show', organisation.slug)" class="bc-link">{{ organisation.name }}</a>
          <span class="bc-sep" aria-hidden="true">›</span>
          <a :href="route('elections.management', election.slug)" class="bc-link">{{ election.name }}</a>
          <span class="bc-sep" aria-hidden="true">›</span>
          <span class="bc-current">Candidacy Review</span>
        </nav>

        <!-- Flash -->
        <transition name="fade-down">
          <div v-if="page.props.flash?.success" role="alert" class="flash flash--success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ page.props.flash.success }}
          </div>
        </transition>
        <transition name="fade-down">
          <div v-if="page.props.flash?.error" role="alert" class="flash flash--error">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ page.props.flash.error }}
          </div>
        </transition>

        <!-- Docket Header -->
        <div class="docket-header">
          <div class="docket-seal" aria-hidden="true">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor">
              <circle cx="24" cy="24" r="20" stroke-width="1.5" opacity="0.4"/>
              <circle cx="24" cy="24" r="14" stroke-width="1"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M24 16v8l4 4"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 12l2 2M32 12l-2 2M12 24h2M34 24h2"/>
            </svg>
          </div>
          <div class="docket-meta">
            <p class="docket-case">ELECTION DOCKET — {{ election.name }}</p>
            <h1 class="docket-title">Candidacy Applications</h1>
            <div class="docket-stats">
              <span class="stat-pill stat-pill--pending">{{ pendingCount }} Pending</span>
              <span class="stat-pill stat-pill--approved">{{ approvedCount }} Approved</span>
              <span class="stat-pill stat-pill--rejected">{{ rejectedCount }} Rejected</span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="applications.length === 0" class="empty-docket">
          <div class="empty-docket__icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor">
              <rect x="12" y="8" width="40" height="48" rx="3" stroke-width="1.5"/>
              <path d="M20 20h24M20 28h24M20 36h16" stroke-linecap="round" stroke-width="1.5"/>
            </svg>
          </div>
          <p class="empty-docket__title">No Applications Filed</p>
          <p class="empty-docket__sub">No candidacy applications have been submitted for this election yet.</p>
        </div>

        <template v-else>

          <!-- Pending -->
          <section v-if="pendingApplications.length" class="docket-section" aria-label="Pending applications">
            <div class="section-divider">
              <span class="section-divider__label">
                <span class="divider-dot divider-dot--pending" aria-hidden="true"></span>
                Awaiting Decision
              </span>
            </div>
            <div class="case-stack">
              <article
                v-for="(app, i) in pendingApplications"
                :key="app.id"
                class="case-file"
                :style="{ animationDelay: `${i * 60}ms` }"
              >
                <div class="case-inner">
                  <!-- Number strip -->
                  <div class="case-number-strip">
                    <span class="case-number">APP-{{ app.id.slice(0,8).toUpperCase() }}</span>
                    <span class="case-date">Filed {{ app.submitted_at }}</span>
                  </div>

                  <!-- Body -->
                  <div class="case-body">
                    <div class="case-avatar">
                      <img v-if="app.photo" :src="`/storage/${app.photo}`" :alt="app.user.name" class="avatar-img"/>
                      <span v-else class="avatar-initials">{{ initials(app.user.name) }}</span>
                    </div>

                    <div class="case-details">
                      <h3 class="applicant-name">{{ app.user.name }}</h3>
                      <p class="applicant-email">{{ app.user.email }}</p>
                      <div class="post-badge">
                        <svg class="post-badge__icon" fill="none" stroke="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h10M3 8h10M3 12h6"/>
                        </svg>
                        Applying for: <strong>{{ app.post.name }}</strong>
                      </div>
                      <div class="meta-row" v-if="app.proposer_name || app.supporter_name">
                        <span v-if="app.proposer_name" class="meta-item">Proposer: <strong>{{ app.proposer_name }}</strong></span>
                        <span v-if="app.supporter_name" class="meta-item">Supporter: <strong>{{ app.supporter_name }}</strong></span>
                      </div>

                      <div v-if="app.manifesto" class="manifesto-wrap">
                        <button type="button" class="manifesto-toggle" @click="toggleManifesto(app.id)">
                          <svg class="manifesto-chevron" :class="{ 'manifesto-chevron--open': openManifestos.has(app.id) }" fill="none" stroke="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6l4 4 4-4"/>
                          </svg>
                          {{ openManifestos.has(app.id) ? 'Hide' : 'Read' }} Statement
                        </button>
                        <transition name="manifesto-slide">
                          <div v-if="openManifestos.has(app.id)" class="manifesto-text">{{ app.manifesto }}</div>
                        </transition>
                      </div>
                      <p v-else class="no-manifesto">No statement provided.</p>
                    </div>

                    <!-- Verdict buttons -->
                    <div class="verdict-panel">
                      <template v-if="rejectingFor !== app.id">
                        <button
                          type="button"
                          class="verdict-btn verdict-btn--approve"
                          :disabled="processingId === app.id"
                          @click="approve(app)"
                        >
                          <svg v-if="processingId === app.id && processingAction === 'approve'" class="btn-spinner" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="40" stroke-dashoffset="10"/>
                          </svg>
                          <svg v-else class="verdict-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l4 4 6-8"/>
                          </svg>
                          Approve
                        </button>
                        <button
                          type="button"
                          class="verdict-btn verdict-btn--reject"
                          :disabled="processingId === app.id"
                          @click="openReject(app)"
                        >
                          <svg class="verdict-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l8 8M14 6l-8 8"/>
                          </svg>
                          Reject
                        </button>
                      </template>
                    </div>
                  </div>

                  <!-- Rejection form -->
                  <transition name="rejection-slide">
                    <div v-if="rejectingFor === app.id" class="rejection-form">
                      <div class="rejection-form__header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 20 20" class="rejection-form__icon" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 2h6l3 3v13H2V2h7zm0 0v3H2"/>
                        </svg>
                        <span>Rejection Notice — <em>{{ app.user.name }}</em></span>
                      </div>
                      <textarea
                        class="rejection-textarea"
                        v-model="rejectionReason"
                        rows="3"
                        maxlength="500"
                        placeholder="State the grounds for rejection… (required)"
                      ></textarea>
                      <div class="rejection-form__footer">
                        <span class="char-count">{{ rejectionReason.length }}/500</span>
                        <div class="rejection-form__actions">
                          <button type="button" class="reject-cancel-btn" @click="closeReject()">Cancel</button>
                          <button
                            type="button"
                            class="reject-confirm-btn"
                            :disabled="!rejectionReason.trim() || processingId === app.id"
                            @click="confirmReject(app)"
                          >
                            <svg v-if="processingId === app.id && processingAction === 'reject'" class="btn-spinner btn-spinner--sm" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="40" stroke-dashoffset="10"/>
                            </svg>
                            Issue Rejection
                          </button>
                        </div>
                      </div>
                    </div>
                  </transition>
                </div>
              </article>
            </div>
          </section>

          <!-- Decided -->
          <section v-if="decidedApplications.length" class="docket-section" aria-label="Decided applications">
            <div class="section-divider">
              <span class="section-divider__label">
                <span class="divider-dot divider-dot--decided" aria-hidden="true"></span>
                Decided
              </span>
            </div>
            <div class="case-stack">
              <article v-for="app in decidedApplications" :key="app.id" class="case-file case-file--decided">
                <div class="case-inner">
                  <div class="case-decided">
                    <div class="case-avatar case-avatar--sm">
                      <img v-if="app.photo" :src="`/storage/${app.photo}`" :alt="app.user.name" class="avatar-img"/>
                      <span v-else class="avatar-initials avatar-initials--sm">{{ initials(app.user.name) }}</span>
                    </div>
                    <div class="case-decided__body">
                      <p class="case-decided__name">{{ app.user.name }}</p>
                      <p class="case-decided__post">{{ app.post.name }}</p>
                    </div>
                    <div class="case-decided__verdict">
                      <span class="verdict-badge" :class="`verdict-badge--${app.status}`">
                        <svg v-if="app.status === 'approved'" class="verdict-icon" fill="none" stroke="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l3 3 7-7"/>
                        </svg>
                        <svg v-else class="verdict-icon" fill="none" stroke="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l8 8M12 4l-8 8"/>
                        </svg>
                        {{ app.status === 'approved' ? 'Approved' : 'Rejected' }}
                      </span>
                      <p v-if="app.status === 'approved' && app.candidacy_id" class="case-decided__note">Draft candidate created</p>
                      <p v-if="app.status === 'rejected' && app.rejection_reason" class="case-decided__reason">{{ app.rejection_reason }}</p>
                    </div>
                  </div>
                </div>
              </article>
            </div>
          </section>

        </template>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

// ── Props ─────────────────────────────────────────────────────────────────────
const props = defineProps({
  organisation: { type: Object, required: true },
  election:     { type: Object, required: true },
  applications: { type: Array,  default: () => [] },
})

const page = usePage()

// ── State ─────────────────────────────────────────────────────────────────────
const processingId     = ref(null)
const processingAction = ref(null)
const rejectingFor     = ref(null)
const rejectionReason  = ref('')
const openManifestos   = ref(new Set())

// ── Computed ──────────────────────────────────────────────────────────────────
const pendingApplications = computed(() => props.applications.filter(a => a.status === 'pending'))
const decidedApplications = computed(() => props.applications.filter(a => a.status !== 'pending'))
const pendingCount  = computed(() => pendingApplications.value.length)
const approvedCount = computed(() => props.applications.filter(a => a.status === 'approved').length)
const rejectedCount = computed(() => props.applications.filter(a => a.status === 'rejected').length)

// ── Helpers ───────────────────────────────────────────────────────────────────
function initials(name) {
  return (name ?? '?').split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
}

function toggleManifesto(id) {
  const s = new Set(openManifestos.value)
  s.has(id) ? s.delete(id) : s.add(id)
  openManifestos.value = s
}

function reviewUrl(appId) {
  return route('organisations.elections.candidacy.review', {
    organisation: props.organisation.slug,
    election:     props.election.slug,
    application:  appId,
  })
}

// ── Actions ───────────────────────────────────────────────────────────────────
function approve(app) {
  if (!confirm(`Approve ${app.user.name} for "${app.post.name}"?\n\nA draft candidate entry will be created. You can publish it from Posts & Candidates.`)) return
  processingId.value     = app.id
  processingAction.value = 'approve'
  router.patch(reviewUrl(app.id), { action: 'approve' }, {
    preserveScroll: true,
    onFinish: () => { processingId.value = null; processingAction.value = null },
  })
}

function openReject(app) {
  rejectingFor.value    = app.id
  rejectionReason.value = ''
}

function closeReject() {
  rejectingFor.value    = null
  rejectionReason.value = ''
}

function confirmReject(app) {
  if (!rejectionReason.value.trim()) return
  processingId.value     = app.id
  processingAction.value = 'reject'
  router.patch(reviewUrl(app.id), { action: 'reject', rejection_reason: rejectionReason.value }, {
    preserveScroll: true,
    onSuccess: () => closeReject(),
    onFinish:  () => { processingId.value = null; processingAction.value = null },
  })
}

</script>

<style scoped>
/* ── Page shell ── */
.review-page {
  min-height: 100vh;
  background: #F0EDE6;
  position: relative;
  padding: 2.5rem 0 4rem;
}
.page-texture {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  background-image:
    repeating-linear-gradient(0deg, transparent, transparent 28px, rgba(26,58,92,.03) 28px, rgba(26,58,92,.03) 29px),
    repeating-linear-gradient(90deg, transparent, transparent 28px, rgba(26,58,92,.03) 28px, rgba(26,58,92,.03) 29px);
}
.review-container {
  position: relative;
  z-index: 1;
  max-width: 52rem;
  margin: 0 auto;
  padding: 0 1.25rem;
}

/* ── Breadcrumb ── */
.breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .8rem; margin-bottom: 2rem; font-family: 'Courier New', monospace; }
.bc-link    { color: #5a6a7a; text-decoration: none; transition: color .15s; }
.bc-link:hover { color: #1a3a5c; }
.bc-sep     { color: #a0aab4; }
.bc-current { color: #1a3a5c; font-weight: 600; }

/* ── Flash ── */
.flash { display: flex; align-items: center; gap: .75rem; padding: .875rem 1.25rem; border-radius: .75rem; font-size: .875rem; font-weight: 500; margin-bottom: 1.25rem; }
.flash--success { background: #e6f4ec; border: 1px solid #a7d7b8; color: #1a5c33; }
.flash--error   { background: #fdecea; border: 1px solid #f5aaaa; color: #8b1d1d; }
.flash-icon { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }

/* ── Docket header ── */
.docket-header {
  display: flex;
  align-items: flex-start;
  gap: 1.5rem;
  margin-bottom: 2.5rem;
  padding-bottom: 2rem;
  border-bottom: 2px solid #d4cfc6;
}
.docket-seal { width: 5rem; height: 5rem; flex-shrink: 0; color: #1a3a5c; opacity: .6; }
.docket-seal svg { width: 100%; height: 100%; }
.docket-case {
  font-family: 'Courier New', monospace;
  font-size: .7rem; font-weight: 700; letter-spacing: .12em;
  color: #8b7a5e; text-transform: uppercase; margin-bottom: .5rem;
}
.docket-title {
  font-size: 2rem; font-weight: 800; color: #1a3a5c;
  line-height: 1.1; letter-spacing: -.02em; margin-bottom: .875rem;
  font-variant: small-caps;
}
.docket-stats { display: flex; flex-wrap: wrap; gap: .5rem; }
.stat-pill {
  font-family: 'Courier New', monospace;
  font-size: .7rem; font-weight: 700; letter-spacing: .06em;
  padding: .2rem .625rem; border-radius: 9999px; border: 1.5px solid;
}
.stat-pill--pending  { background: #fef9ec; border-color: #d4a847; color: #7c5c0a; }
.stat-pill--approved { background: #e8f5ee; border-color: #5aab7a; color: #1a5c33; }
.stat-pill--rejected { background: #fdecea; border-color: #d47a7a; color: #8b1d1d; }

/* ── Empty ── */
.empty-docket { text-align: center; padding: 5rem 2rem; }
.empty-docket__icon { width: 5rem; height: 5rem; margin: 0 auto 1.5rem; color: #b5a88c; }
.empty-docket__icon svg { width: 100%; height: 100%; }
.empty-docket__title { font-size: 1.125rem; font-weight: 700; color: #4a3c28; margin-bottom: .5rem; }
.empty-docket__sub   { font-size: .875rem; color: #8b7a5e; }

/* ── Section divider ── */
.docket-section { margin-bottom: 2.5rem; }
.section-divider { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; }
.section-divider::before, .section-divider::after { content: ''; flex: 1; height: 1px; background: #d4cfc6; }
.section-divider__label {
  display: flex; align-items: center; gap: .5rem;
  font-family: 'Courier New', monospace;
  font-size: .7rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: #8b7a5e; white-space: nowrap;
}
.divider-dot { width: .5rem; height: .5rem; border-radius: 50%; flex-shrink: 0; }
.divider-dot--pending { background: #d4a847; box-shadow: 0 0 0 3px #fef3cd; }
.divider-dot--decided { background: #9ca3af; }

/* ── Case stack ── */
.case-stack { display: flex; flex-direction: column; gap: 1rem; }
.case-file { animation: slide-up .35s ease both; }
@keyframes slide-up {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Case inner card ── */
.case-inner {
  background: #ffffff;
  border: 1.5px solid #d4cfc6;
  border-radius: .875rem;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(26,58,92,.06), 0 1px 2px rgba(26,58,92,.04);
  transition: box-shadow .2s;
}
.case-inner:hover { box-shadow: 0 4px 16px rgba(26,58,92,.1), 0 1px 4px rgba(26,58,92,.06); }
.case-file--decided .case-inner { border-color: #e0ddd8; box-shadow: 0 1px 3px rgba(26,58,92,.04); }

/* ── Number strip ── */
.case-number-strip {
  display: flex; align-items: center; justify-content: space-between;
  background: #1a3a5c; padding: .5rem 1.25rem;
}
.case-number { font-family: 'Courier New', monospace; font-size: .7rem; font-weight: 700; letter-spacing: .12em; color: #a8c4d8; }
.case-date   { font-family: 'Courier New', monospace; font-size: .65rem; color: #6a8a9a; letter-spacing: .06em; }

/* ── Case body ── */
.case-body {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 1.25rem;
  padding: 1.25rem 1.5rem;
  align-items: start;
}

/* ── Avatars ── */
.case-avatar {
  width: 3.5rem; height: 3.5rem;
  border-radius: .625rem; overflow: hidden; flex-shrink: 0;
  border: 2px solid #e8e4dc;
}
.case-avatar--sm { width: 2.75rem; height: 2.75rem; border-radius: .5rem; border: 1.5px solid #e8e4dc; }
.avatar-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.avatar-initials {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  background: #e8e4dc; color: #5a4a30;
  font-family: 'Courier New', monospace; font-size: 1rem; font-weight: 700;
}
.avatar-initials--sm { font-size: .8rem; }

/* ── Case details ── */
.applicant-name  { font-size: 1rem; font-weight: 700; color: #1a2c3c; letter-spacing: -.01em; margin-bottom: .125rem; }
.applicant-email { font-size: .75rem; color: #7a8a9a; font-family: 'Courier New', monospace; margin-bottom: .75rem; }
.post-badge {
  display: inline-flex; align-items: center; gap: .375rem;
  background: #f0ede6; border: 1px solid #d4cfc6; border-radius: .375rem;
  padding: .25rem .625rem; font-size: .78rem; color: #4a3c28; margin-bottom: .75rem;
}
.post-badge__icon { width: .875rem; height: .875rem; color: #8b7a5e; flex-shrink: 0; }

/* ── Manifesto ── */
.manifesto-toggle {
  display: inline-flex; align-items: center; gap: .375rem;
  font-size: .78rem; font-weight: 600; color: #1a3a5c;
  background: none; border: none; cursor: pointer; padding: 0; transition: color .15s;
}
.manifesto-toggle:hover { color: #2a5a8c; }
.manifesto-chevron { width: .875rem; height: .875rem; transition: transform .2s; }
.manifesto-chevron--open { transform: rotate(180deg); }
.manifesto-text {
  margin-top: .625rem; padding: .875rem 1rem;
  background: #f9f7f3; border-left: 3px solid #d4a847; border-radius: 0 .375rem .375rem 0;
  font-size: .8125rem; color: #3a2c18; line-height: 1.65; white-space: pre-wrap;
}
.no-manifesto { font-size: .75rem; color: #a0968c; font-style: italic; }

/* ── Verdict panel ── */
.verdict-panel { display: flex; flex-direction: column; gap: .5rem; flex-shrink: 0; }
.verdict-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .5rem 1.125rem; border-radius: .5rem;
  font-size: .8125rem; font-weight: 700; letter-spacing: .02em;
  border: 2px solid; cursor: pointer;
  transition: background .15s, transform .1s, opacity .15s;
  white-space: nowrap; min-width: 5.5rem;
}
.verdict-btn:disabled { opacity: .55; cursor: not-allowed; }
.verdict-btn:not(:disabled):active { transform: scale(.97); }
.verdict-btn__icon { width: 1rem; height: 1rem; flex-shrink: 0; }
.verdict-btn--approve { background: #1a5c33; border-color: #1a5c33; color: #fff; }
.verdict-btn--approve:not(:disabled):hover { background: #146b3a; }
.verdict-btn--reject  { background: #fff; border-color: #d47a7a; color: #8b1d1d; }
.verdict-btn--reject:not(:disabled):hover  { background: #fdecea; }

/* ── Rejection form ── */
.rejection-form { padding: 1.25rem 1.5rem; border-top: 1.5px dashed #d4a847; background: #fffdf5; }
.rejection-form__header {
  display: flex; align-items: center; gap: .625rem;
  font-family: 'Courier New', monospace; font-size: .75rem; font-weight: 700;
  letter-spacing: .06em; text-transform: uppercase; color: #7c5c0a; margin-bottom: .875rem;
}
.rejection-form__header em { font-style: italic; text-transform: none; font-family: inherit; }
.rejection-form__icon { width: 1rem; height: 1rem; flex-shrink: 0; }
.rejection-textarea {
  width: 100%; border: 1.5px solid #d4a847; border-radius: .5rem;
  padding: .75rem 1rem; font-size: .875rem; color: #2a1c08;
  background: #fff; resize: vertical; outline: none;
  font-family: Georgia, 'Times New Roman', serif; line-height: 1.6;
  transition: border-color .15s, box-shadow .15s;
}
.rejection-textarea:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(212,168,71,.15); }
.rejection-form__footer { display: flex; align-items: center; justify-content: space-between; margin-top: .75rem; }
.char-count { font-family: 'Courier New', monospace; font-size: .7rem; color: #a08060; }
.rejection-form__actions { display: flex; gap: .625rem; }
.reject-cancel-btn {
  padding: .4375rem .875rem; border-radius: .375rem;
  font-size: .8125rem; font-weight: 600; color: #5a6a7a;
  background: #fff; border: 1.5px solid #d4cfc6; cursor: pointer; transition: border-color .15s;
}
.reject-cancel-btn:hover { border-color: #9ca3af; }
.reject-confirm-btn {
  display: inline-flex; align-items: center; gap: .375rem;
  padding: .4375rem 1rem; border-radius: .375rem;
  font-size: .8125rem; font-weight: 700; color: #fff;
  background: #8b1d1d; border: 1.5px solid #8b1d1d; cursor: pointer; transition: background .15s;
}
.reject-confirm-btn:hover:not(:disabled) { background: #a12222; }
.reject-confirm-btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── Decided row ── */
.case-decided {
  display: flex; align-items: center; gap: 1rem; padding: .875rem 1.25rem;
}
.case-decided__body   { flex: 1; min-width: 0; }
.case-decided__name   { font-size: .875rem; font-weight: 700; color: #2a3a4a; }
.case-decided__post   { font-size: .75rem; color: #7a8a9a; }
.case-decided__verdict { text-align: right; flex-shrink: 0; }
.verdict-badge {
  display: inline-flex; align-items: center; gap: .3rem;
  font-family: 'Courier New', monospace; font-size: .7rem; font-weight: 700;
  letter-spacing: .06em; padding: .2rem .625rem; border-radius: .25rem; border: 1.5px solid;
}
.verdict-badge--approved { background: #e8f5ee; border-color: #5aab7a; color: #1a5c33; }
.verdict-badge--rejected { background: #fdecea; border-color: #d47a7a; color: #8b1d1d; }
.verdict-icon     { width: .75rem; height: .75rem; flex-shrink: 0; }
.case-decided__note   { font-size: .7rem; color: #5aab7a; margin-top: .25rem; font-style: italic; }
.case-decided__reason { font-size: .7rem; color: #d47a7a; margin-top: .25rem; max-width: 16rem; text-align: right; line-height: 1.4; }

/* ── Spinners ── */
.btn-spinner { width: 1rem; height: 1rem; animation: spin .8s linear infinite; flex-shrink: 0; }
.btn-spinner--sm { width: .875rem; height: .875rem; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Transitions ── */
.fade-down-enter-active, .fade-down-leave-active { transition: opacity .25s, transform .25s; }
.fade-down-enter-from { opacity: 0; transform: translateY(-8px); }
.fade-down-leave-to   { opacity: 0; transform: translateY(-4px); }
.manifesto-slide-enter-active, .manifesto-slide-leave-active { transition: opacity .2s, max-height .25s; overflow: hidden; max-height: 20rem; }
.manifesto-slide-enter-from, .manifesto-slide-leave-to { opacity: 0; max-height: 0; }
.rejection-slide-enter-active, .rejection-slide-leave-active { transition: opacity .2s, max-height .3s ease; overflow: hidden; max-height: 20rem; }
.rejection-slide-enter-from, .rejection-slide-leave-to { opacity: 0; max-height: 0; }

/* ── Responsive ── */
@media (max-width: 640px) {
  .case-body { grid-template-columns: auto 1fr; }
  .verdict-panel { grid-column: 1 / -1; flex-direction: row; justify-content: flex-end; }
  .docket-header { flex-direction: column; gap: 1rem; }
  .docket-seal   { width: 3.5rem; height: 3.5rem; }
}
</style>
