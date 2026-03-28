<template>
  <ElectionLayout>

    <!-- Toast notifications -->
    <Teleport to="body">
      <Transition name="toast">
        <div v-if="page.props.flash?.success"
          class="toast toast--success"
          role="alert"
        >
          <svg class="toast-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
          </svg>
          <span>{{ page.props.flash.success }}</span>
        </div>
      </Transition>
      <Transition name="toast">
        <div v-if="page.props.flash?.error"
          class="toast toast--error"
          role="alert"
        >
          <svg class="toast-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          <span>{{ page.props.flash.error }}</span>
        </div>
      </Transition>
    </Teleport>

    <main class="apply-root">

      <!-- Page watermark -->
      <div class="page-watermark" aria-hidden="true">NOMINATION</div>

      <div class="apply-container">

        <!-- ── Breadcrumb ── -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <a :href="route('organisations.show', organisation.slug)" class="breadcrumb-link">{{ organisation.name }}</a>
          <span class="breadcrumb-sep" aria-hidden="true">/</span>
          <a :href="route('elections.show', election.slug)" class="breadcrumb-link">{{ election.name }}</a>
          <span class="breadcrumb-sep" aria-hidden="true">/</span>
          <span class="breadcrumb-current">Apply for Candidacy</span>
        </nav>

        <!-- ── Document Header ── -->
        <header class="doc-header apply-fade" style="--delay: 0ms">
          <div class="doc-header-seal" aria-hidden="true">
            <svg viewBox="0 0 64 64" fill="none">
              <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 3"/>
              <circle cx="32" cy="32" r="20" stroke="currentColor" stroke-width="1"/>
              <path d="M32 16l2.9 8.9H44l-7.5 5.5 2.9 8.9L32 34l-7.5 5.3 2.9-8.9L20 24.9h9.1z" fill="currentColor" opacity=".9"/>
            </svg>
          </div>
          <div class="doc-header-text">
            <p class="doc-header-org">{{ organisation.name }}</p>
            <h1 class="doc-header-title">Candidacy Application</h1>
            <p class="doc-header-election">{{ election.name }}</p>
          </div>
          <div class="doc-header-meta" aria-hidden="true">
            <span class="meta-pill" :class="election.status === 'active' ? 'meta-pill--active' : 'meta-pill--closed'">
              {{ election.status === 'active' ? 'Accepting Applications' : election.status }}
            </span>
          </div>
        </header>

        <!-- ── Available Positions ── -->
        <section class="doc-section apply-fade" style="--delay: 80ms">
          <div class="section-rule">
            <span class="section-rule-label">Available Positions</span>
          </div>

          <div v-if="posts.length === 0" class="empty-posts">
            <p>No positions have been added to this election yet.</p>
          </div>

          <div v-else class="posts-grid">
            <div
              v-for="post in posts"
              :key="post.id"
              class="post-card"
              :class="{ 'post-card--selected': form.post_id === post.id }"
              @click="! existingApplication && selectPost(post.id)"
              :role="! existingApplication ? 'button' : 'article'"
              :tabindex="! existingApplication ? 0 : undefined"
              @keydown.enter="! existingApplication && selectPost(post.id)"
              @keydown.space.prevent="! existingApplication && selectPost(post.id)"
            >
              <div class="post-card-inner">
                <div class="post-card-check" aria-hidden="true">
                  <svg v-if="form.post_id === post.id" viewBox="0 0 16 16" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.354 4.354a.5.5 0 010 .707L7 10.414 4.646 8.061a.5.5 0 01.708-.707L7 9.001l4.646-4.647a.5.5 0 01.708 0z"/>
                  </svg>
                </div>
                <div class="post-card-body">
                  <h3 class="post-name">{{ post.name }}</h3>
                  <p v-if="post.nepali_name" class="post-name-alt">{{ post.nepali_name }}</p>
                  <div class="post-badges">
                    <span class="badge badge--scope">
                      {{ post.is_national_wide ? 'National' : (post.state_name || 'Regional') }}
                    </span>
                    <span class="badge badge--seats">
                      {{ post.required_number }} seat{{ post.required_number !== 1 ? 's' : '' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <p v-if="errors.post_id" class="field-error-msg" role="alert">{{ errors.post_id }}</p>
        </section>

        <!-- ── Existing Application Status ── -->
        <section v-if="existingApplication" class="doc-section apply-fade" style="--delay: 120ms">
          <div class="section-rule">
            <span class="section-rule-label">Your Application</span>
          </div>

          <div class="status-card" :class="`status-card--${existingApplication.status}`">
            <div class="status-card-icon" aria-hidden="true">
              <!-- Pending -->
              <svg v-if="existingApplication.status === 'pending'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <!-- Approved -->
              <svg v-else-if="existingApplication.status === 'approved'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <!-- Rejected -->
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="status-card-body">
              <p class="status-card-label">
                <template v-if="existingApplication.status === 'pending'">Application Under Review</template>
                <template v-else-if="existingApplication.status === 'approved'">Application Approved</template>
                <template v-else>Application Not Approved</template>
              </p>
              <p class="status-card-post">Applied for: <strong>{{ existingApplication.post_name }}</strong></p>
              <p class="status-card-date">Submitted {{ existingApplication.submitted_at }}</p>
              <p v-if="existingApplication.status === 'pending'" class="status-card-note">
                The election commission will review your application. You will be notified of the outcome.
              </p>
              <p v-else-if="existingApplication.status === 'approved'" class="status-card-note">
                Congratulations — you are now an official candidate. Your name will appear on the ballot.
              </p>
              <p v-else class="status-card-note">
                Your application was not approved. Please contact the election commission for further information.
              </p>
            </div>
            <!-- Candidate photo thumbnail -->
            <div v-if="existingApplication.photo" class="status-card-photo">
              <img
                :src="`/storage/${existingApplication.photo}`"
                :alt="`Candidate photo`"
                class="status-photo-img"
              />
            </div>
          </div>
        </section>

        <!-- ── Application Form ── -->
        <template v-else-if="election.status === 'active'">

          <!-- Section 02: Nominee Details -->
          <section class="doc-section apply-fade" style="--delay: 160ms">
            <div class="section-rule">
              <span class="section-rule-label">Nominee Details</span>
            </div>

            <div class="fields-grid">
              <div class="field-group">
                <label for="supporter_name" class="field-label">
                  Supporter Name <span class="required-mark" aria-hidden="true">*</span>
                </label>
                <input
                  id="supporter_name"
                  v-model="form.supporter_name"
                  type="text"
                  autocomplete="off"
                  maxlength="255"
                  placeholder="Full name of your supporter"
                  class="field-input"
                  :class="{ 'field-input--error': errors.supporter_name }"
                  @focus="errors.supporter_name = ''"
                />
                <p v-if="errors.supporter_name" class="field-error-msg" role="alert">{{ errors.supporter_name }}</p>
              </div>

              <div class="field-group">
                <label for="proposer_name" class="field-label">
                  Proposer Name <span class="required-mark" aria-hidden="true">*</span>
                </label>
                <input
                  id="proposer_name"
                  v-model="form.proposer_name"
                  type="text"
                  autocomplete="off"
                  maxlength="255"
                  placeholder="Full name of your proposer"
                  class="field-input"
                  :class="{ 'field-input--error': errors.proposer_name }"
                  @focus="errors.proposer_name = ''"
                />
                <p v-if="errors.proposer_name" class="field-error-msg" role="alert">{{ errors.proposer_name }}</p>
              </div>
            </div>
          </section>

          <!-- Section 03: Statement -->
          <section class="doc-section apply-fade" style="--delay: 200ms">
            <div class="section-rule">
              <span class="section-rule-label">Personal Statement</span>
              <span class="section-rule-hint">Optional — max 5,000 characters</span>
            </div>

            <div class="field-group">
              <textarea
                id="manifesto"
                v-model="form.manifesto"
                rows="6"
                maxlength="5000"
                placeholder="Describe your vision, experience, and commitment to this position…"
                class="field-textarea"
                :class="{ 'field-input--error': errors.manifesto }"
              />
              <div class="char-count" :class="{ 'char-count--warn': form.manifesto.length > 4500 }">
                {{ form.manifesto.length.toLocaleString() }} / 5,000
              </div>
              <p v-if="errors.manifesto" class="field-error-msg" role="alert">{{ errors.manifesto }}</p>
            </div>
          </section>

          <!-- Section 04: Candidate Photo -->
          <section class="doc-section apply-fade" style="--delay: 240ms">
            <div class="section-rule">
              <span class="section-rule-label">Candidate Photo</span>
              <span class="section-rule-hint">Optional · JPG or PNG · max 5 MB</span>
            </div>

            <div class="photo-upload-area">
              <!-- Preview -->
              <div class="photo-preview-frame" :class="{ 'has-photo': photoPreview }">
                <img v-if="photoPreview" :src="photoPreview" alt="Photo preview" class="photo-preview-img" />
                <div v-else class="photo-preview-placeholder" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                  </svg>
                </div>
              </div>

              <!-- Controls -->
              <div class="photo-controls">
                <label class="photo-btn photo-btn--choose" :for="'photo-input'">
                  <svg viewBox="0 0 20 20" fill="currentColor" class="photo-btn-icon" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1 8a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 018.07 3h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0016.07 6H17a2 2 0 012 2v7a2 2 0 01-2 2H3a2 2 0 01-2-2V8zm13.5 3a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM10 14a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                  </svg>
                  {{ photoPreview ? 'Change Photo' : 'Choose Photo' }}
                </label>
                <input
                  id="photo-input"
                  ref="photoInputRef"
                  type="file"
                  accept="image/jpeg,image/png"
                  class="sr-only"
                  @change="handlePhoto"
                />
                <button
                  v-if="photoPreview"
                  type="button"
                  class="photo-btn photo-btn--remove"
                  @click="removePhoto"
                >
                  Remove
                </button>
                <p v-if="photoSizeError" class="field-error-msg" role="alert">{{ photoSizeError }}</p>
              </div>
            </div>
          </section>

          <!-- Section 05: Declaration -->
          <section class="doc-section apply-fade" style="--delay: 280ms">
            <div class="section-rule">
              <span class="section-rule-label">Declaration</span>
            </div>

            <div class="declaration-band" :class="{ 'declaration-band--error': termsError }">
              <label class="declaration-label">
                <input
                  type="checkbox"
                  v-model="agreedToTerms"
                  class="declaration-checkbox"
                  @change="termsError = false"
                />
                <span class="declaration-text">
                  I declare that the information provided is accurate and complete. I agree to abide by the
                  rules and regulations of the election, and I accept the
                  <strong>terms and conditions</strong> governing candidacy applications.
                </span>
              </label>
              <p v-if="termsError" class="field-error-msg mt-2" role="alert">
                You must agree to the terms and conditions before submitting.
              </p>
            </div>
          </section>

          <!-- Submit -->
          <div class="submit-row apply-fade" style="--delay: 320ms">
            <button
              type="button"
              class="submit-btn"
              :class="{ 'submit-btn--loading': isSubmitting }"
              :disabled="isSubmitting"
              @click="submitForm"
            >
              <span v-if="! isSubmitting" class="submit-btn-label">
                <svg viewBox="0 0 20 20" fill="currentColor" class="submit-btn-icon" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Submit Application
              </span>
              <span v-else class="submit-btn-label">
                <svg class="submit-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Submitting…
              </span>
            </button>
            <a :href="route('organisations.voter-hub', organisation.slug)" class="cancel-link">
              Cancel
            </a>
          </div>

        </template>

        <!-- Election not active -->
        <section v-else class="doc-section apply-fade" style="--delay: 120ms">
          <div class="closed-notice">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="closed-notice-icon" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
            <div>
              <p class="closed-notice-title">Applications Closed</p>
              <p class="closed-notice-desc">This election is not currently accepting candidacy applications.</p>
            </div>
          </div>
        </section>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const props = defineProps({
  organisation:        { type: Object, required: true },
  election:            { type: Object, required: true },
  posts:               { type: Array,  default: () => [] },
  existingApplication: { type: Object, default: null },
})

const page = usePage()

// ── Form state ──────────────────────────────────────────────────────────────
const form = ref({
  election_id:    props.election.id,
  post_id:        '',
  supporter_name: '',
  proposer_name:  '',
  manifesto:      '',
})

const errors         = ref({})
const isSubmitting   = ref(false)
const agreedToTerms  = ref(false)
const termsError     = ref(false)

// ── Photo upload ─────────────────────────────────────────────────────────────
const photoFile      = ref(null)
const photoPreview   = ref(null)
const photoSizeError = ref('')
const photoInputRef  = ref(null)

function handlePhoto(event) {
  const file = event.target.files?.[0]
  if (! file) return

  if (file.size > 5 * 1024 * 1024) {
    photoSizeError.value = 'Photo must be under 5 MB.'
    return
  }

  photoSizeError.value = ''
  photoFile.value      = file

  if (photoPreview.value) URL.revokeObjectURL(photoPreview.value)
  photoPreview.value = URL.createObjectURL(file)
}

function removePhoto() {
  if (photoPreview.value) URL.revokeObjectURL(photoPreview.value)
  photoPreview.value   = null
  photoFile.value      = null
  photoSizeError.value = ''
  if (photoInputRef.value) photoInputRef.value.value = ''
}

// ── Post selection ────────────────────────────────────────────────────────────
function selectPost(postId) {
  form.value.post_id = postId
  if (errors.value.post_id) errors.value.post_id = ''
}

// ── Submit ────────────────────────────────────────────────────────────────────
function submitForm() {
  errors.value = {}

  // Client-side T&C guard
  if (! agreedToTerms.value) {
    termsError.value = true
    return
  }

  if (! form.value.post_id) {
    errors.value.post_id = 'Please select a position before submitting.'
    return
  }

  isSubmitting.value = true

  const data = new FormData()
  data.append('election_id',    form.value.election_id)
  data.append('post_id',        form.value.post_id)
  data.append('supporter_name', form.value.supporter_name)
  data.append('proposer_name',  form.value.proposer_name)
  data.append('manifesto',      form.value.manifesto)
  if (photoFile.value) data.append('photo', photoFile.value)

  router.post(
    route('organisations.candidacy.apply', props.organisation.slug),
    data,
    {
      forceFormData: true,
      preserveScroll: true,
      onError(errs) {
        errors.value = errs
      },
      onFinish() {
        isSubmitting.value = false
      },
    }
  )
}
</script>

<style scoped>
/* ── Design tokens ─────────────────────────────────────────────────────────── */
:root {
  --parchment:    #F9F8F3;
  --ink-deep:     #1C2B40;
  --ink-mid:      #374151;
  --ink-light:    #6B7280;
  --rule-color:   #D4C9B0;
  --gold:         #7C6A42;
  --gold-light:   #F0E8D6;
  --active-green: #166534;
  --active-bg:    #F0FDF4;
  --select-ring:  #1C2B40;
}

/* ── Page shell ──────────────────────────────────────────────────────────── */
.apply-root {
  min-height: 100vh;
  background: #F7F5EE;
  background-image:
    radial-gradient(ellipse 80% 60% at 50% -10%, rgba(124, 106, 66, 0.08) 0%, transparent 60%);
  padding: 2.5rem 1rem 5rem;
  position: relative;
  overflow: hidden;
  font-family: 'Georgia', 'Times New Roman', serif;
}

.page-watermark {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(-35deg);
  font-size: 10rem;
  font-weight: 900;
  letter-spacing: 0.4em;
  color: rgba(124, 106, 66, 0.04);
  pointer-events: none;
  user-select: none;
  white-space: nowrap;
  font-family: Georgia, serif;
  z-index: 0;
}

.apply-container {
  position: relative;
  z-index: 1;
  max-width: 720px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

/* ── Breadcrumb ──────────────────────────────────────────────────────────── */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8125rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
}
.breadcrumb-link {
  color: #6B7280;
  text-decoration: none;
  transition: color 0.15s;
}
.breadcrumb-link:hover { color: #1C2B40; }
.breadcrumb-sep { color: #D1D5DB; }
.breadcrumb-current { color: #374151; font-weight: 500; }

/* ── Document header ─────────────────────────────────────────────────────── */
.doc-header {
  background: #FFFEF9;
  border: 1px solid #E2D9C4;
  border-radius: 4px;
  padding: 2rem;
  display: grid;
  grid-template-columns: 72px 1fr auto;
  gap: 1.25rem;
  align-items: center;
  box-shadow: 0 2px 12px rgba(28, 43, 64, 0.06);
}

.doc-header-seal {
  width: 64px;
  height: 64px;
  color: #7C6A42;
  opacity: 0.8;
  flex-shrink: 0;
}

.doc-header-org {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #9CA3AF;
  margin: 0 0 0.2rem;
}
.doc-header-title {
  font-size: 1.625rem;
  font-weight: 700;
  color: #1C2B40;
  margin: 0 0 0.25rem;
  line-height: 1.2;
  letter-spacing: -0.01em;
}
.doc-header-election {
  font-size: 0.875rem;
  color: #7C6A42;
  margin: 0;
  font-style: italic;
}
.doc-header-meta {
  text-align: right;
}
.meta-pill {
  display: inline-block;
  font-size: 0.6875rem;
  font-family: system-ui, sans-serif;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0.3rem 0.75rem;
  border-radius: 999px;
}
.meta-pill--active {
  background: #DCFCE7;
  color: #166534;
  border: 1px solid #BBF7D0;
}
.meta-pill--closed {
  background: #F3F4F6;
  color: #6B7280;
  border: 1px solid #E5E7EB;
}

/* ── Section wrapper ─────────────────────────────────────────────────────── */
.doc-section {
  background: #FFFEF9;
  border: 1px solid #E2D9C4;
  border-radius: 4px;
  padding: 1.5rem 2rem;
  box-shadow: 0 1px 6px rgba(28, 43, 64, 0.04);
}

.section-rule {
  display: flex;
  align-items: baseline;
  gap: 1rem;
  margin-bottom: 1.25rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #E2D9C4;
}
.section-rule-label {
  font-size: 0.6875rem;
  font-family: system-ui, sans-serif;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #7C6A42;
}
.section-rule-hint {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
}

/* ── Posts grid ──────────────────────────────────────────────────────────── */
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 0.75rem;
}

.post-card {
  border: 1.5px solid #E2D9C4;
  border-radius: 4px;
  padding: 1rem;
  cursor: pointer;
  transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
  background: #FDFCF8;
  outline: none;
}
.post-card:hover,
.post-card:focus {
  border-color: #7C6A42;
  box-shadow: 0 0 0 3px rgba(124, 106, 66, 0.12);
  background: #FBF8F0;
}
.post-card--selected {
  border-color: #1C2B40;
  background: #F0F4FA;
  box-shadow: 0 0 0 3px rgba(28, 43, 64, 0.1);
}
.post-card-inner {
  display: flex;
  gap: 0.75rem;
  align-items: flex-start;
}
.post-card-check {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 1.5px solid #D4C9B0;
  flex-shrink: 0;
  margin-top: 2px;
  background: white;
  transition: background 0.15s, border-color 0.15s;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}
.post-card--selected .post-card-check {
  background: #1C2B40;
  border-color: #1C2B40;
}
.post-card-body { flex: 1; min-width: 0; }
.post-name {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #1C2B40;
  margin: 0 0 0.15rem;
  line-height: 1.3;
}
.post-name-alt {
  font-size: 0.8125rem;
  color: #9CA3AF;
  margin: 0 0 0.5rem;
  font-style: italic;
}
.post-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  margin-top: 0.4rem;
}
.badge {
  font-size: 0.625rem;
  font-family: system-ui, sans-serif;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  padding: 0.15rem 0.5rem;
  border-radius: 2px;
}
.badge--scope { background: #EEF2FF; color: #3730A3; }
.badge--seats { background: #F3F4F6; color: #374151; }

.empty-posts {
  font-size: 0.875rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
  text-align: center;
  padding: 1.5rem 0;
}

/* ── Status card ─────────────────────────────────────────────────────────── */
.status-card {
  display: flex;
  gap: 1.25rem;
  align-items: flex-start;
  padding: 1.25rem;
  border-radius: 4px;
  border: 1px solid transparent;
}
.status-card--pending  { background: #FFFBEB; border-color: #FDE68A; }
.status-card--approved { background: #F0FDF4; border-color: #86EFAC; }
.status-card--rejected { background: #FFF1F2; border-color: #FECDD3; }

.status-card-icon {
  width: 36px;
  height: 36px;
  flex-shrink: 0;
  margin-top: 2px;
}
.status-card--pending  .status-card-icon { color: #D97706; }
.status-card--approved .status-card-icon { color: #16A34A; }
.status-card--rejected .status-card-icon { color: #DC2626; }

.status-card-body { flex: 1; min-width: 0; }
.status-card-label {
  font-size: 1rem;
  font-weight: 700;
  color: #1C2B40;
  margin: 0 0 0.25rem;
}
.status-card-post {
  font-size: 0.8125rem;
  font-family: system-ui, sans-serif;
  color: #374151;
  margin: 0 0 0.15rem;
}
.status-card-date {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
  margin: 0 0 0.5rem;
}
.status-card-note {
  font-size: 0.8125rem;
  font-family: system-ui, sans-serif;
  color: #6B7280;
  margin: 0;
  line-height: 1.5;
}
.status-card-photo { flex-shrink: 0; }
.status-photo-img {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(28, 43, 64, 0.15);
}

/* ── Form fields ─────────────────────────────────────────────────────────── */
.fields-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 500px) {
  .fields-grid { grid-template-columns: 1fr; }
}

.field-group { display: flex; flex-direction: column; gap: 0.4rem; }

.field-label {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  color: #4B5563;
}
.required-mark { color: #DC2626; margin-left: 2px; }

.field-input,
.field-textarea {
  width: 100%;
  padding: 0.6rem 0.75rem;
  background: #FDFCF8;
  border: 1px solid #D4C9B0;
  border-radius: 3px;
  font-size: 0.9375rem;
  font-family: system-ui, sans-serif;
  color: #1C2B40;
  line-height: 1.5;
  transition: border-color 0.15s, box-shadow 0.15s;
  box-sizing: border-box;
}
.field-input:focus,
.field-textarea:focus {
  outline: none;
  border-color: #7C6A42;
  box-shadow: 0 0 0 3px rgba(124, 106, 66, 0.12);
  background: #FFFEF9;
}
.field-input--error { border-color: #DC2626 !important; }

.field-textarea {
  resize: vertical;
  min-height: 140px;
  font-family: Georgia, serif;
  font-size: 0.9375rem;
  line-height: 1.7;
}

.char-count {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
  text-align: right;
  margin-top: 0.25rem;
}
.char-count--warn { color: #D97706; }

.field-error-msg {
  font-size: 0.75rem;
  font-family: system-ui, sans-serif;
  color: #DC2626;
  margin: 0.15rem 0 0;
}

/* ── Photo upload ────────────────────────────────────────────────────────── */
.photo-upload-area {
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}
.photo-preview-frame {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 2px solid #D4C9B0;
  overflow: hidden;
  background: #F3F0E8;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: border-color 0.15s;
}
.photo-preview-frame.has-photo { border-color: #7C6A42; }
.photo-preview-img { width: 100%; height: 100%; object-fit: cover; }
.photo-preview-placeholder {
  width: 48px;
  height: 48px;
  color: #D4C9B0;
}
.photo-controls {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding-top: 0.5rem;
}
.photo-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8125rem;
  font-family: system-ui, sans-serif;
  font-weight: 500;
  padding: 0.45rem 0.875rem;
  border-radius: 3px;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
  text-decoration: none;
  border: none;
}
.photo-btn-icon { width: 14px; height: 14px; }
.photo-btn--choose {
  background: #1C2B40;
  color: white;
}
.photo-btn--choose:hover { background: #0F1C2C; }
.photo-btn--remove {
  background: transparent;
  color: #DC2626;
  padding-left: 0;
  font-size: 0.75rem;
}
.photo-btn--remove:hover { color: #991B1B; }

/* ── Declaration ─────────────────────────────────────────────────────────── */
.declaration-band {
  background: #FFFBEB;
  border: 1.5px solid #FDE68A;
  border-radius: 3px;
  padding: 1rem 1.25rem;
  transition: border-color 0.15s;
}
.declaration-band--error { border-color: #DC2626; background: #FFF1F2; }
.declaration-label {
  display: flex;
  gap: 0.75rem;
  cursor: pointer;
}
.declaration-checkbox {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  margin-top: 2px;
  accent-color: #1C2B40;
  cursor: pointer;
}
.declaration-text {
  font-size: 0.875rem;
  font-family: system-ui, sans-serif;
  color: #374151;
  line-height: 1.6;
}

/* ── Submit row ──────────────────────────────────────────────────────────── */
.submit-row {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}
.submit-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  background: #1C2B40;
  color: white;
  font-size: 0.9375rem;
  font-family: system-ui, sans-serif;
  font-weight: 600;
  letter-spacing: 0.01em;
  padding: 0.75rem 2rem;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  transition: background 0.15s, transform 0.1s;
}
.submit-btn:hover:not(:disabled) { background: #0F1C2C; transform: translateY(-1px); }
.submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.submit-btn--loading { opacity: 0.7; }
.submit-btn-label { display: flex; align-items: center; gap: 0.4rem; }
.submit-btn-icon { width: 16px; height: 16px; }
.submit-spinner {
  width: 16px;
  height: 16px;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.cancel-link {
  font-size: 0.875rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
  text-decoration: none;
  transition: color 0.15s;
}
.cancel-link:hover { color: #374151; }

/* ── Closed notice ───────────────────────────────────────────────────────── */
.closed-notice {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  padding: 1.25rem;
  background: #F9FAFB;
  border-radius: 4px;
  border: 1px solid #E5E7EB;
}
.closed-notice-icon {
  width: 28px;
  height: 28px;
  color: #9CA3AF;
  flex-shrink: 0;
  margin-top: 2px;
}
.closed-notice-title {
  font-size: 0.9375rem;
  font-weight: 600;
  font-family: system-ui, sans-serif;
  color: #374151;
  margin: 0 0 0.25rem;
}
.closed-notice-desc {
  font-size: 0.8125rem;
  font-family: system-ui, sans-serif;
  color: #9CA3AF;
  margin: 0;
}

/* ── Toast ───────────────────────────────────────────────────────────────── */
.toast {
  position: fixed;
  top: 1.25rem;
  right: 1.25rem;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.875rem;
  font-family: system-ui, sans-serif;
  font-weight: 500;
  padding: 0.75rem 1.25rem;
  border-radius: 6px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
  max-width: 360px;
}
.toast-icon { width: 16px; height: 16px; flex-shrink: 0; }
.toast--success { background: #166534; color: white; }
.toast--error   { background: #DC2626; color: white; }
.toast-enter-active, .toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(1rem); }

/* ── Staggered fade-in ───────────────────────────────────────────────────── */
.apply-fade {
  animation: applyFadeUp 0.4s ease both;
  animation-delay: var(--delay, 0ms);
}
@keyframes applyFadeUp {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width: 640px) {
  .doc-header {
    grid-template-columns: 48px 1fr;
    grid-template-rows: auto auto;
  }
  .doc-header-meta {
    grid-column: 2;
    text-align: left;
  }
  .doc-section { padding: 1.25rem; }
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}
</style>
