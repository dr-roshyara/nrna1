<template>
  <div class="candidacy-form-wrap">

    <!-- Error flash (duplicate application) -->
    <div v-if="page.props.flash?.error"
      class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>{{ page.props.flash.error }}</span>
    </div>

    <!-- Form card -->
    <div class="nomination-card">

      <!-- Header band -->
      <div class="nomination-header">
        <div class="nomination-emblem" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
          </svg>
        </div>
        <div>
          <h2 class="nomination-title">Candidacy Application</h2>
          <p class="nomination-subtitle">Submit your formal nomination for an election post</p>
        </div>
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" novalidate>

        <!-- ── Section 1: Election & Post ── -->
        <div class="form-section">
          <div class="section-label">
            <span class="section-number">01</span>
            <span class="section-title">Position Selection</span>
          </div>

          <div class="field-row">
            <!-- Election selector -->
            <div class="field-group">
              <label for="election_id" class="field-label">Election <span class="required-mark">*</span></label>
              <div class="select-wrap">
                <select id="election_id" v-model="form.election_id" @change="form.post_id = ''" class="field-select" :class="{ 'field-error': errors.election_id }">
                  <option value="" disabled>Select an election…</option>
                  <option v-for="e in activeElections" :key="e.id" :value="e.id">{{ e.name }}</option>
                </select>
                <svg class="select-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
              </div>
              <p v-if="errors.election_id" class="field-error-msg">{{ errors.election_id }}</p>
            </div>

            <!-- Post selector — cascade from election -->
            <div class="field-group">
              <label for="post_id" class="field-label">Post / Position <span class="required-mark">*</span></label>
              <div class="select-wrap">
                <select id="post_id" v-model="form.post_id" class="field-select" :class="{ 'field-error': errors.post_id }" :disabled="!form.election_id">
                  <option value="" disabled>{{ form.election_id ? 'Select a post…' : 'Select an election first' }}</option>
                  <option v-for="p in postsForSelectedElection" :key="p.id" :value="p.id">
                    {{ p.name }}
                    <template v-if="!p.is_national_wide && p.state_name"> ({{ p.state_name }})</template>
                    · {{ p.required_number }} seat{{ p.required_number !== 1 ? 's' : '' }}
                  </option>
                </select>
                <svg class="select-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
              </div>
              <p v-if="errors.post_id" class="field-error-msg">{{ errors.post_id }}</p>
            </div>
          </div>
        </div>

        <!-- ── Section 2: Proposer & Supporter ── -->
        <div class="form-section">
          <div class="section-label">
            <span class="section-number">02</span>
            <span class="section-title">Nomination Details</span>
          </div>
          <p class="section-hint">Your candidacy must be supported and proposed by named members.</p>

          <div class="field-row">
            <div class="field-group">
              <label for="proposer_name" class="field-label">Proposer Name <span class="required-mark">*</span></label>
              <input id="proposer_name" v-model="form.proposer_name" type="text" maxlength="255"
                class="field-input" :class="{ 'field-error': errors.proposer_name }"
                placeholder="Full name of the member proposing your candidacy"
              />
              <p v-if="errors.proposer_name" class="field-error-msg">{{ errors.proposer_name }}</p>
            </div>

            <div class="field-group">
              <label for="supporter_name" class="field-label">Supporter Name <span class="required-mark">*</span></label>
              <input id="supporter_name" v-model="form.supporter_name" type="text" maxlength="255"
                class="field-input" :class="{ 'field-error': errors.supporter_name }"
                placeholder="Full name of the member supporting your candidacy"
              />
              <p v-if="errors.supporter_name" class="field-error-msg">{{ errors.supporter_name }}</p>
            </div>
          </div>
        </div>

        <!-- ── Section 3: Manifesto ── -->
        <div class="form-section">
          <div class="section-label">
            <span class="section-number">03</span>
            <span class="section-title">Election Statement</span>
          </div>
          <p class="section-hint">Optional — briefly describe why you are standing and what you aim to achieve.</p>

          <div class="field-group">
            <label for="manifesto" class="field-label">Manifesto / Statement</label>
            <textarea id="manifesto" v-model="form.manifesto" rows="5" maxlength="5000"
              class="field-textarea" :class="{ 'field-error': errors.manifesto }"
              placeholder="Share your vision and intentions with the electorate…"
            />
            <div class="flex items-center justify-between mt-1">
              <p v-if="errors.manifesto" class="field-error-msg">{{ errors.manifesto }}</p>
              <span class="ml-auto text-xs text-slate-400">{{ form.manifesto.length }}&thinsp;/&thinsp;5000</span>
            </div>
          </div>
        </div>

        <!-- ── Section 4: Documents ── -->
        <div class="form-section">
          <div class="section-label">
            <span class="section-number">04</span>
            <span class="section-title">Supporting Documents</span>
          </div>
          <p class="section-hint">Attach up to 5 files (PDF, JPG, PNG). Maximum 5 MB each.</p>

          <!-- Drop zone -->
          <div class="drop-zone"
            :class="{ 'drop-zone--active': isDragging, 'drop-zone--has-files': selectedFiles.length > 0 }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            @click="$refs.fileInput.click()"
            role="button"
            tabindex="0"
            :aria-label="`Document upload zone. ${selectedFiles.length} file${selectedFiles.length !== 1 ? 's' : ''} selected.`"
            @keydown.enter.prevent="$refs.fileInput.click()"
            @keydown.space.prevent="$refs.fileInput.click()"
          >
            <input ref="fileInput" type="file" multiple accept=".pdf,.jpg,.jpeg,.png" class="sr-only"
              @change="handleFileInput" :disabled="selectedFiles.length >= 5"
            />

            <div v-if="selectedFiles.length === 0" class="drop-zone-empty">
              <svg class="drop-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
              </svg>
              <p class="drop-text">Drop files here or <span class="drop-link">browse</span></p>
              <p class="drop-hint">PDF, JPG, PNG · max 5 MB · up to 5 files</p>
            </div>

            <div v-else class="drop-zone-files">
              <div v-for="(f, i) in selectedFiles" :key="i" class="file-chip">
                <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span class="file-name">{{ f.name }}</span>
                <span class="file-size">({{ formatSize(f.size) }})</span>
                <button type="button" @click.stop="removeFile(i)" class="file-remove" :aria-label="`Remove ${f.name}`">
                  <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                </button>
              </div>
              <button v-if="selectedFiles.length < 5" type="button" @click.stop="$refs.fileInput.click()" class="add-more-btn">
                + Add more
              </button>
            </div>
          </div>

          <!-- Size errors -->
          <p v-for="(err, i) in fileSizeErrors" :key="i" class="field-error-msg mt-1">{{ err }}</p>
          <p v-if="errors.documents" class="field-error-msg mt-1">{{ errors.documents }}</p>
        </div>

        <!-- ── Section 5: Terms & Conditions ── -->
        <div class="form-section terms-section">
          <div class="section-label">
            <span class="section-number">05</span>
            <span class="section-title">Declaration</span>
          </div>

          <label class="terms-label" :class="{ 'terms-error': termsError }">
            <input
              type="checkbox"
              v-model="agreedToTerms"
              class="terms-checkbox"
              @change="termsError = false"
            />
            <span class="terms-text">
              I confirm that the information provided is accurate and complete. I agree to the
              <strong>terms and conditions of this election</strong>, including the eligibility
              requirements, code of conduct, and the authority of the election commission to
              review and approve or reject candidacy applications.
            </span>
          </label>
          <p v-if="termsError" class="field-error-msg mt-2">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            You must agree to the terms and conditions before submitting.
          </p>
        </div>

        <!-- ── Submit ── -->
        <div class="form-footer">
          <p class="form-note">
            <svg class="inline w-3.5 h-3.5 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
            Applications are reviewed by the election commission. You will be notified of the outcome.
          </p>
          <button type="submit" class="submit-btn" :disabled="isSubmitting" :aria-busy="isSubmitting">
            <span v-if="isSubmitting" class="btn-spinner" aria-hidden="true"></span>
            <svg v-else class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ isSubmitting ? 'Submitting…' : 'Submit Application' }}</span>
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  organisation:    { type: Object, required: true },
  activeElections: { type: Array,  default: () => [] },
})

const page = usePage()

// ── Form state ─────────────────────────────────────────────────────────────
const form = ref({
  election_id:    '',
  post_id:        '',
  proposer_name:  '',
  supporter_name: '',
  manifesto:      '',
})

const selectedFiles  = ref([])
const fileSizeErrors = ref([])
const isDragging     = ref(false)
const isSubmitting   = ref(false)
const fileInput      = ref(null)
const agreedToTerms  = ref(false)
const termsError     = ref(false)

// ── Computed ───────────────────────────────────────────────────────────────
const errors = computed(() => page.props.errors ?? {})

const postsForSelectedElection = computed(() => {
  if (!form.value.election_id) return []
  const election = props.activeElections.find(e => e.id === form.value.election_id)
  return election?.posts ?? []
})

// ── File handling ──────────────────────────────────────────────────────────
const MAX_FILE_SIZE = 5 * 1024 * 1024 // 5 MB

function addFiles(fileList) {
  fileSizeErrors.value = []
  const remaining = 5 - selectedFiles.value.length
  Array.from(fileList).slice(0, remaining).forEach(file => {
    if (file.size > MAX_FILE_SIZE) {
      fileSizeErrors.value.push(`"${file.name}" exceeds 5 MB (${formatSize(file.size)})`)
      return
    }
    selectedFiles.value.push(file)
  })
}

function handleFileInput(e) {
  addFiles(e.target.files)
  e.target.value = ''
}

function handleDrop(e) {
  isDragging.value = false
  addFiles(e.dataTransfer.files)
}

function removeFile(index) {
  selectedFiles.value.splice(index, 1)
}

function formatSize(bytes) {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

// ── Submit ─────────────────────────────────────────────────────────────────
function submit() {
  if (!agreedToTerms.value) {
    termsError.value = true
    return
  }
  isSubmitting.value = true

  const data = new FormData()
  data.append('election_id',    form.value.election_id)
  data.append('post_id',        form.value.post_id)
  data.append('proposer_name',  form.value.proposer_name)
  data.append('supporter_name', form.value.supporter_name)
  data.append('manifesto',      form.value.manifesto)
  selectedFiles.value.forEach(f => data.append('documents[]', f))

  router.post(
    route('organisations.candidacy.apply', props.organisation.slug),
    data,
    {
      preserveScroll: true,
      onSuccess: () => {
        form.value = { election_id: '', post_id: '', proposer_name: '', supporter_name: '', manifesto: '' }
        selectedFiles.value = []
        fileSizeErrors.value = []
        agreedToTerms.value = false
        termsError.value = false
      },
      onFinish: () => { isSubmitting.value = false },
    }
  )
}
</script>

<style scoped>
/* ── Wrapper ── */
.candidacy-form-wrap { container-type: inline-size; }

/* ── Card ── */
.nomination-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / .06), 0 1px 2px -1px rgb(0 0 0 / .06);
}

/* ── Header band ── */
.nomination-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  padding: 1.5rem 2rem;
  position: relative;
  overflow: hidden;
}
.nomination-header::after {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    -45deg,
    transparent,
    transparent 12px,
    rgb(255 255 255 / .02) 12px,
    rgb(255 255 255 / .02) 13px
  );
}
.nomination-emblem {
  width: 3rem;
  height: 3rem;
  border-radius: 0.75rem;
  background: rgb(245 158 11 / .15);
  border: 1px solid rgb(245 158 11 / .3);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  position: relative;
  z-index: 1;
}
.nomination-emblem svg { width: 1.5rem; height: 1.5rem; stroke: #f59e0b; }
.nomination-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #f8fafc;
  letter-spacing: -0.01em;
  position: relative;
  z-index: 1;
}
.nomination-subtitle {
  font-size: 0.8125rem;
  color: #94a3b8;
  margin-top: 0.125rem;
  position: relative;
  z-index: 1;
}

/* ── Sections ── */
.form-section {
  padding: 1.75rem 2rem;
  border-bottom: 1px solid #f1f5f9;
}
.form-section:last-of-type { border-bottom: none; }

.section-label {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  margin-bottom: 1.25rem;
}
.section-number {
  font-size: 0.6875rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  color: #f59e0b;
  background: rgb(245 158 11 / .1);
  border: 1px solid rgb(245 158 11 / .25);
  border-radius: 0.375rem;
  padding: 0.1875rem 0.5rem;
  font-variant-numeric: tabular-nums;
}
.section-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #0f172a;
  letter-spacing: -0.01em;
}
.section-hint {
  font-size: 0.8125rem;
  color: #64748b;
  margin-top: -0.625rem;
  margin-bottom: 1.25rem;
  line-height: 1.5;
}

/* ── Field row (2-col grid) ── */
.field-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}
@container (max-width: 560px) {
  .field-row { grid-template-columns: 1fr; }
}

/* ── Field group ── */
.field-group { display: flex; flex-direction: column; gap: 0.375rem; }
.field-label {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #334155;
  letter-spacing: -0.005em;
}
.required-mark { color: #ef4444; margin-left: 0.125rem; }

/* ── Inputs ── */
.field-input,
.field-textarea,
.field-select {
  width: 100%;
  border: 1.5px solid #e2e8f0;
  border-radius: 0.625rem;
  background: #f8fafc;
  color: #0f172a;
  font-size: 0.875rem;
  transition: border-color 0.15s, background-color 0.15s, box-shadow 0.15s;
  outline: none;
  appearance: none;
}
.field-input,
.field-select { padding: 0.5625rem 0.875rem; height: 2.625rem; }
.field-textarea { padding: 0.625rem 0.875rem; resize: vertical; }
.field-input:focus,
.field-textarea:focus,
.field-select:focus {
  border-color: #f59e0b;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgb(245 158 11 / .12);
}
.field-input.field-error,
.field-textarea.field-error,
.field-select.field-error {
  border-color: #ef4444;
  background: #fff5f5;
}
.field-error-msg {
  font-size: 0.75rem;
  color: #dc2626;
  margin-top: 0.125rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}
.field-input:disabled,
.field-select:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* ── Select wrap ── */
.select-wrap { position: relative; }
.select-wrap .field-select { padding-right: 2.25rem; }
.select-chevron {
  position: absolute;
  right: 0.625rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1.125rem;
  height: 1.125rem;
  color: #94a3b8;
  pointer-events: none;
}

/* ── Drop zone ── */
.drop-zone {
  border: 2px dashed #cbd5e1;
  border-radius: 0.875rem;
  background: #f8fafc;
  padding: 1.5rem;
  cursor: pointer;
  transition: border-color 0.2s, background-color 0.2s;
  min-height: 6rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.drop-zone:hover,
.drop-zone:focus-visible { border-color: #f59e0b; background: #fffbeb; outline: none; }
.drop-zone--active { border-color: #f59e0b; background: #fffbeb; }
.drop-zone--has-files { border-style: solid; border-color: #e2e8f0; }

.drop-zone-empty { text-align: center; }
.drop-icon { width: 2rem; height: 2rem; stroke: #94a3b8; margin: 0 auto 0.5rem; display: block; }
.drop-text { font-size: 0.875rem; color: #475569; font-weight: 500; }
.drop-link { color: #f59e0b; text-decoration: underline; text-underline-offset: 2px; }
.drop-hint { font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; }

.drop-zone-files { width: 100%; }
.file-chip {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 0.4375rem 0.625rem;
  margin-bottom: 0.5rem;
  font-size: 0.8125rem;
}
.file-icon { width: 1rem; height: 1rem; stroke: #64748b; flex-shrink: 0; }
.file-name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #334155; font-weight: 500; }
.file-size { color: #94a3b8; flex-shrink: 0; }
.file-remove {
  width: 1.25rem;
  height: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.25rem;
  color: #94a3b8;
  flex-shrink: 0;
  transition: color 0.15s, background-color 0.15s;
  border: none;
  background: transparent;
  cursor: pointer;
  padding: 0;
}
.file-remove:hover { color: #ef4444; background: #fee2e2; }
.file-remove svg { width: 0.875rem; height: 0.875rem; }
.add-more-btn {
  font-size: 0.8125rem;
  color: #f59e0b;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem 0;
  font-weight: 600;
  transition: color 0.15s;
}
.add-more-btn:hover { color: #d97706; }

/* ── Terms ── */
.terms-section { background: #fffbeb; border-bottom: 1px solid #fde68a; }
.terms-label {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  cursor: pointer;
  padding: 0.25rem 0;
}
.terms-label.terms-error .terms-text { color: #b45309; }
.terms-checkbox {
  width: 1.125rem;
  height: 1.125rem;
  flex-shrink: 0;
  margin-top: 0.125rem;
  accent-color: #1e293b;
  cursor: pointer;
  border-radius: 0.25rem;
}
.terms-text {
  font-size: 0.8125rem;
  color: #475569;
  line-height: 1.6;
}
.terms-text strong { color: #0f172a; font-weight: 600; }

/* ── Footer ── */
.form-footer {
  padding: 1.25rem 2rem 1.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  background: #f8fafc;
  border-top: 1px solid #f1f5f9;
}
.form-note { font-size: 0.75rem; color: #64748b; max-width: 28rem; line-height: 1.5; }
.submit-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  color: #ffffff;
  font-size: 0.875rem;
  font-weight: 600;
  padding: 0.625rem 1.5rem;
  border-radius: 0.625rem;
  border: none;
  cursor: pointer;
  transition: opacity 0.2s, transform 0.1s;
  white-space: nowrap;
  letter-spacing: -0.01em;
  box-shadow: 0 1px 2px rgb(0 0 0 / .15);
}
.submit-btn:hover:not(:disabled) { opacity: 0.88; transform: translateY(-1px); }
.submit-btn:active:not(:disabled) { transform: translateY(0); }
.submit-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }
.btn-icon { width: 1rem; height: 1rem; }
.btn-spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid rgb(255 255 255 / .3);
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
