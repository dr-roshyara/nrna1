## 📝 **Claude Code CLI Prompt Instructions: Add IP Address Display to Election Show Page**

---

```markdown
## Task: Add IP Address Display to Election Show Page

### Background

The election show page (`resources/js/Pages/Election/Show.vue`) currently displays election details, voting status, and a countdown timer. It needs to display the voter's IP address as a security feature, matching the style of the existing `ElectionDashboard.vue` which already has a professional IP badge.

**Reference:** The `ElectionDashboard.vue` has a well-designed "System Information" card that displays IP address, user info, and email. We need to add a similar IP display to `Show.vue` but in a more subtle, integrated way that matches the civic editorial aesthetic.

### Files to Modify

| File | Change |
|------|--------|
| `app/Http/Controllers/ElectionVotingController.php` | Add `ipAddress` to Inertia response |
| `resources/js/Pages/Election/Show.vue` | Add `ipAddress` prop and IP badge component |

---

## Step 1: Update Controller to Pass IP Address

**File:** `app/Http/Controllers/ElectionVotingController.php`

Find the `show()` method (around lines 23-51). In the `return Inertia::render()` array, add the IP address:

```php
public function show(string $slug): Response
{
    $election = Election::withoutGlobalScopes()
        ->where('slug', $slug)
        ->where('type', 'real')
        ->firstOrFail();

    $user = auth()->user();

    $membership = $user->electionMemberships()
        ->where('election_id', $election->id)
        ->first();

    $hasVoted   = $membership?->has_voted ?? false;
    $isEligible = $membership !== null
        && $membership->role   === 'voter'
        && $membership->status !== 'removed';

    $canVote = $isEligible
        && ! $hasVoted
        && $election->status === 'active'
        && $election->start_date <= now()
        && $election->end_date   >= now();

    return Inertia::render('Election/Show', [
        'election'   => $election,
        'hasVoted'   => $hasVoted,
        'canVote'    => $canVote,
        'isEligible' => $isEligible,
        'ipAddress'  => request()->ip(),  // ← ADD THIS LINE
    ]);
}
```

**Run test:**
```bash
php artisan test tests/Feature/Election/ElectionShowControllerTest.php --filter=eligible_voter_sees_can_vote_true
```
Expected: PASS (existing tests unaffected)

---

## Step 2: Update Show.vue — Add IP Address Prop and Badge

**File:** `resources/js/Pages/Election/Show.vue`

### 2.1 Add `ipAddress` to `defineProps`

Find the `defineProps` section (around line 173-182) and add `ipAddress`:

```vue
<script setup>
// ... existing imports ...

const props = defineProps({
    election:   { type: Object,  required: true },
    hasVoted:   { type: Boolean, default: false },
    canVote:    { type: Boolean, default: false },
    isEligible: { type: Boolean, default: false },
    ipAddress:  { type: String,  default: null },  // ← ADD THIS
})
```

### 2.2 Add IP Badge Component in Hero Section

Find the hero section (lines 22-48). After the election metadata (`<div class="esp-hero__meta">`), add the IP badge:

```vue
<!-- ─── Hero ─────────────────────────────────────────────── -->
<section class="esp-hero">
    <div class="esp-hero__grid" aria-hidden="true"></div>
    <div class="esp-hero__inner">
        <div class="esp-eyebrow">
            <span class="esp-eyebrow__line"></span>
            <span class="esp-eyebrow__text">Official Election</span>
            <span class="esp-eyebrow__line"></span>
        </div>
        <h1 class="esp-hero__title">{{ election.name }}</h1>
        <div class="esp-hero__meta">
            <span class="esp-meta-date">{{ formatDate(election.start_date) }} — {{ formatDate(election.end_date) }}</span>
            <span class="esp-meta-dot" aria-hidden="true">·</span>
            <span class="esp-meta-status" :class="`esp-status--${election.status}`">{{ election.status }}</span>
        </div>

        <!-- ─── IP Address Badge ─────────────────────────────────────────── -->
        <div class="esp-ip-badge" v-if="ipAddress">
            <div class="esp-ip-badge__inner">
                <svg class="esp-ip-badge__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12.55C5 7.45 8.5 3 12 3s7 4.45 7 9.55c0 4.1-2.5 7.5-5 9.5-2.5-2-5-5.4-5-9.5z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <span class="esp-ip-badge__label">Voting from</span>
                <span class="esp-ip-badge__value">{{ ipAddress }}</span>
                <span class="esp-ip-badge__tooltip" role="tooltip">Your IP address is recorded for security verification</span>
            </div>
        </div>
    </div>
</section>
```

### 2.3 Add IP Badge Styles

Add the following CSS to the `<style scoped>` section (before the responsive styles at the end):

```css
/* ── IP Address Badge ──────────────────────────────────────────────────────── */
.esp-ip-badge {
    margin-top: 1.75rem;
    display: flex;
    justify-content: center;
}

.esp-ip-badge__inner {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(247, 243, 236, 0.1);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(181, 134, 43, 0.3);
    border-radius: 999px;
    padding: 0.375rem 1rem;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    position: relative;
    cursor: help;
}

.esp-ip-badge__inner:hover {
    background: rgba(247, 243, 236, 0.2);
    border-color: rgba(181, 134, 43, 0.6);
}

.esp-ip-badge__icon {
    color: var(--gold);
    flex-shrink: 0;
}

.esp-ip-badge__label {
    font-family: var(--ff-mono);
    font-size: 0.65rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(247, 243, 236, 0.6);
}

.esp-ip-badge__value {
    font-family: var(--ff-mono);
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--cream);
    letter-spacing: 0.02em;
}

.esp-ip-badge__tooltip {
    position: absolute;
    bottom: calc(100% + 8px);
    left: 50%;
    transform: translateX(-50%);
    background: var(--ink);
    color: var(--cream);
    font-family: var(--ff-body);
    font-size: 0.7rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
    pointer-events: none;
    z-index: 10;
    border: 1px solid var(--gold);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.esp-ip-badge__inner:hover .esp-ip-badge__tooltip {
    opacity: 1;
    visibility: visible;
}

.esp-ip-badge__tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: var(--gold) transparent transparent transparent;
}

@media (max-width: 640px) {
    .esp-ip-badge__tooltip {
        white-space: normal;
        width: 200px;
        text-align: center;
        font-size: 0.65rem;
    }
}
```

---

## Step 3: Run Tests

```bash
# Run the election show tests
php artisan test tests/Feature/Election/ElectionShowControllerTest.php --no-coverage

# Run the voter suspension tests (unrelated, but ensure no regression)
php artisan test tests/Feature/Election/ElectionVoterSuspensionTest.php --no-coverage

# Run the unit tests
php artisan test tests/Unit/Models/ElectionMembershipTest.php --no-coverage
```

**Expected:** All tests pass (IP address addition does not affect existing functionality)

---

## Step 4: Manual Verification

1. **Login as a voter**
2. **Navigate to an active election page:** `/elections/{slug}`
3. **Verify IP address appears** below the election dates and status
4. **Hover over the IP badge** → tooltip appears showing "Your IP address is recorded for security verification"
5. **Verify on mobile** (or using browser dev tools) that tooltip adapts to screen size

---

## Success Criteria

| Check | Expected |
|-------|----------|
| IP address appears on election show page | ✅ Visible below election metadata |
| IP badge has gold accent color | ✅ Matches election status badge styling |
| Hover tooltip shows security message | ✅ Tooltip appears on hover |
| Responsive on mobile | ✅ Tooltip wraps text on small screens |
| No console errors | ✅ |
| All tests pass | ✅ |

---

## Summary of Changes

| File | Change |
|------|--------|
| `app/Http/Controllers/ElectionVotingController.php` | Added `'ipAddress' => request()->ip()` to Inertia response |
| `resources/js/Pages/Election/Show.vue` | Added `ipAddress` prop, IP badge HTML, and CSS styles |

**This implementation is minimal, matches the existing design language, and adds a professional security touch to the election page.** 🚀
```

---

## ✅ **Ready to Execute**

Copy these instructions into Claude Code CLI to implement the IP address display on the election show page. The implementation follows TDD principles and maintains the existing civic editorial design.

## 📝 **Updated Election Show Page with IP Address Display**

I'll add the IP address information in a professional, civic-style card that matches the existing design. The IP display will be placed in the hero section after the election metadata, maintaining the elegant editorial aesthetic.

---

**File:** `resources/js/Pages/Election/Show.vue`

```vue
<template>
    <ElectionLayout>
        <Head>
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link
                href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Mono:wght@400;500&family=Outfit:wght@300;400;500;600&display=swap"
                rel="stylesheet"
            />
        </Head>

        <div class="esp-root">

            <!-- ─── Hero ─────────────────────────────────────────────── -->
            <section class="esp-hero">
                <div class="esp-hero__grid" aria-hidden="true"></div>
                <div class="esp-hero__inner">
                    <div class="esp-eyebrow">
                        <span class="esp-eyebrow__line"></span>
                        <span class="esp-eyebrow__text">Official Election</span>
                        <span class="esp-eyebrow__line"></span>
                    </div>
                    <h1 class="esp-hero__title">{{ election.name }}</h1>
                    <div class="esp-hero__meta">
                        <span class="esp-meta-date">{{ formatDate(election.start_date) }} — {{ formatDate(election.end_date) }}</span>
                        <span class="esp-meta-dot" aria-hidden="true">·</span>
                        <span class="esp-meta-status" :class="`esp-status--${election.status}`">{{ election.status }}</span>
                    </div>

                    <!-- ─── IP Address Badge ─────────────────────────────────────────── -->
                    <div class="esp-ip-badge" v-if="ipAddress">
                        <div class="esp-ip-badge__inner">
                            <svg class="esp-ip-badge__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12.55C5 7.45 8.5 3 12 3s7 4.45 7 9.55c0 4.1-2.5 7.5-5 9.5-2.5-2-5-5.4-5-9.5z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <span class="esp-ip-badge__label">Voting from</span>
                            <span class="esp-ip-badge__value">{{ ipAddress }}</span>
                            <span class="esp-ip-badge__tooltip" role="tooltip">Your IP address is recorded for security verification</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ─── Main ──────────────────────────────────────────────── -->
            <section class="esp-main">
                <div class="esp-main__inner">

                    <!-- Flash messages -->
                    <div v-if="flash.success || flash.error || flash.info" class="esp-flash-wrap" role="alert" aria-live="polite">
                        <div v-if="flash.success" class="esp-flash esp-flash--success">{{ flash.success }}</div>
                        <div v-if="flash.error"   class="esp-flash esp-flash--error">{{ flash.error }}</div>
                        <div v-if="flash.info"    class="esp-flash esp-flash--info">{{ flash.info }}</div>
                    </div>

                    <!-- Ballot Card -->
                    <div class="esp-ballot" :class="ballotCardClass" role="main">

                        <!-- ── STATE: Can Vote ── -->
                        <template v-if="canVote">
                            <div class="esp-ballot__header">
                                <div class="esp-ballot__icon esp-ballot__icon--active" aria-hidden="true">🗳</div>
                                <div>
                                    <p class="esp-ballot__eyebrow">YOUR BALLOT IS READY</p>
                                    <h2 class="esp-ballot__heading">You are eligible to vote</h2>
                                </div>
                            </div>

                            <!-- Countdown -->
                            <div class="esp-countdown" aria-label="Time remaining to vote">
                                <p class="esp-countdown__label">Voting window closes in</p>
                                <div class="esp-countdown__display" aria-live="polite">
                                    <div class="esp-countdown__unit">
                                        <span class="esp-countdown__value">{{ countdown.hours }}</span>
                                        <span class="esp-countdown__name">hrs</span>
                                    </div>
                                    <span class="esp-countdown__sep" aria-hidden="true">:</span>
                                    <div class="esp-countdown__unit">
                                        <span class="esp-countdown__value">{{ countdown.minutes }}</span>
                                        <span class="esp-countdown__name">min</span>
                                    </div>
                                    <span class="esp-countdown__sep" aria-hidden="true">:</span>
                                    <div class="esp-countdown__unit">
                                        <span class="esp-countdown__value">{{ countdown.seconds }}</span>
                                        <span class="esp-countdown__name">sec</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Steps -->
                            <ol class="esp-steps" aria-label="Voting process steps">
                                <li class="esp-step">
                                    <span class="esp-step__num" aria-hidden="true">1</span>
                                    <span class="esp-step__text">Click <em>Cast Your Vote</em> below to begin</span>
                                </li>
                                <li class="esp-step">
                                    <span class="esp-step__num" aria-hidden="true">2</span>
                                    <span class="esp-step__text">Enter your secure, single-use voting code</span>
                                </li>
                                <li class="esp-step">
                                    <span class="esp-step__num" aria-hidden="true">3</span>
                                    <span class="esp-step__text">Select your candidates for each position</span>
                                </li>
                                <li class="esp-step">
                                    <span class="esp-step__num" aria-hidden="true">4</span>
                                    <span class="esp-step__text">Confirm — your anonymous ballot is cast</span>
                                </li>
                            </ol>

                            <button
                                class="esp-cta"
                                :class="{ 'esp-cta--loading': voting }"
                                :disabled="voting"
                                :aria-busy="voting"
                                @click="startVoting"
                            >
                                <span class="esp-cta__text">{{ voting ? 'Preparing ballot…' : 'Cast Your Vote' }}</span>
                                <span class="esp-cta__arrow" aria-hidden="true">→</span>
                            </button>

                            <ul class="esp-trust" aria-label="Security guarantees">
                                <li class="esp-trust__item">🔒 End-to-end encrypted</li>
                                <li class="esp-trust__item">👤 Completely anonymous</li>
                                <li class="esp-trust__item">✓ Immutable audit trail</li>
                            </ul>
                        </template>

                        <!-- ── STATE: Has Voted ── -->
                        <template v-else-if="hasVoted">
                            <div class="esp-ballot__header">
                                <div class="esp-ballot__icon esp-ballot__icon--voted" aria-label="Vote confirmed">✓</div>
                                <div>
                                    <p class="esp-ballot__eyebrow">VOTE RECORDED</p>
                                    <h2 class="esp-ballot__heading">Your ballot has been cast</h2>
                                </div>
                            </div>

                            <p class="esp-voted-body">
                                Your vote has been securely recorded. Thank you for participating
                                in this election. Your ballot is completely anonymous and cannot
                                be linked back to your identity.
                            </p>

                            <dl class="esp-certificate">
                                <div class="esp-certificate__row">
                                    <dt class="esp-certificate__label">Election</dt>
                                    <dd class="esp-certificate__value">{{ election.name }}</dd>
                                </div>
                                <div class="esp-certificate__row">
                                    <dt class="esp-certificate__label">Status</dt>
                                    <dd class="esp-certificate__value esp-certificate__value--green">Recorded ✓</dd>
                                </div>
                                <div class="esp-certificate__row">
                                    <dt class="esp-certificate__label">Anonymity</dt>
                                    <dd class="esp-certificate__value">Guaranteed — no voter-vote linkage</dd>
                                </div>
                            </dl>
                        </template>

                        <!-- ── STATE: Not Eligible ── -->
                        <template v-else>
                            <div class="esp-ballot__header">
                                <div class="esp-ballot__icon esp-ballot__icon--ineligible" aria-label="Not eligible">⊘</div>
                                <div>
                                    <p class="esp-ballot__eyebrow">NOT REGISTERED</p>
                                    <h2 class="esp-ballot__heading">You are not eligible to vote in this election</h2>
                                </div>
                            </div>
                            <p class="esp-ineligible-body">
                                Your account is not registered as an eligible voter for
                                <strong>{{ election.name }}</strong>. If you believe this is an
                                error, please contact the election administrator.
                            </p>
                        </template>

                    </div>
                    <!-- /ballot card -->

                </div>
            </section>

        </div>
    </ElectionLayout>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const props = defineProps({
    election:   { type: Object,  required: true },
    hasVoted:   { type: Boolean, default: false },
    canVote:    { type: Boolean, default: false },
    isEligible: { type: Boolean, default: false },
    ipAddress:  { type: String,  default: null },  // ← NEW: IP address from controller
})

// ─── Flash ────────────────────────────────────────────────────────────────────
const page  = usePage()
const flash = computed(() => page.props.flash ?? {})

// ─── Start voting ─────────────────────────────────────────────────────────────
const voting = ref(false)

function startVoting () {
    voting.value = true
    router.post(route('elections.start', props.election.slug), {}, {
        onFinish: () => { voting.value = false },
    })
}

// ─── Countdown ────────────────────────────────────────────────────────────────
const countdown = ref({ hours: '00', minutes: '00', seconds: '00' })
let timer = null

function tick () {
    const diff = new Date(props.election.end_date) - Date.now()
    if (diff <= 0) {
        countdown.value = { hours: '00', minutes: '00', seconds: '00' }
        return
    }
    const h = Math.floor(diff / 3_600_000)
    const m = Math.floor((diff % 3_600_000) / 60_000)
    const s = Math.floor((diff % 60_000)    / 1_000)
    countdown.value = {
        hours:   String(h).padStart(2, '0'),
        minutes: String(m).padStart(2, '0'),
        seconds: String(s).padStart(2, '0'),
    }
}

onMounted  (() => { tick(); timer = setInterval(tick, 1000) })
onUnmounted(() => clearInterval(timer))

// ─── Helpers ──────────────────────────────────────────────────────────────────
function formatDate (raw) {
    return new Date(raw).toLocaleDateString('en-GB', {
        day: 'numeric', month: 'long', year: 'numeric',
    })
}

const ballotCardClass = computed(() => ({
    'esp-ballot--active':     props.canVote,
    'esp-ballot--voted':      props.hasVoted && !props.canVote,
    'esp-ballot--ineligible': !props.isEligible && !props.hasVoted,
}))
</script>

<style scoped>
/* ─── Google Fonts are loaded via <Head> ─────────────────────────────────────
   Cormorant Garamond  → election title, headings (luxury serif)
   DM Mono             → countdown, codes, data (precise mono)
   Outfit              → body, steps, labels (clean geometric)
   ─────────────────────────────────────────────────────────────────────────── */

/* ── Variables ──────────────────────────────────────────────────────────────── */
.esp-root {
    --ink:       #141418;
    --ink-mid:   #2c2c38;
    --cream:     #f7f3ec;
    --parchment: #ede8df;
    --gold:      #b5862b;
    --gold-lt:   #d4a84b;
    --vermeil:   #a0291e;
    --green:     #1e6b45;
    --slate:     #6b7280;
    --border:    #d4cfc6;

    --ff-serif: 'Cormorant Garamond', Georgia, serif;
    --ff-mono:  'DM Mono', 'Courier New', monospace;
    --ff-body:  'Outfit', system-ui, sans-serif;

    font-family: var(--ff-body);
    color: var(--ink);
}

/* ── Hero ────────────────────────────────────────────────────────────────────── */
.esp-hero {
    position: relative;
    background: var(--ink);
    padding: 5rem 1.5rem 4rem;
    overflow: hidden;
}

.esp-hero__grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(181,134,43,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(181,134,43,.08) 1px, transparent 1px);
    background-size: 48px 48px;
}

.esp-hero__inner {
    position: relative;
    max-width: 780px;
    margin: 0 auto;
    text-align: center;
}

/* eyebrow */
.esp-eyebrow {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1.75rem;
}

.esp-eyebrow__line {
    flex: 1;
    max-width: 80px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold));
}
.esp-eyebrow__line:last-child {
    background: linear-gradient(270deg, transparent, var(--gold));
}

.esp-eyebrow__text {
    font-family: var(--ff-mono);
    font-size: .7rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--gold);
}

/* title */
.esp-hero__title {
    font-family: var(--ff-serif);
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 600;
    line-height: 1.1;
    color: var(--cream);
    margin: 0 0 1.5rem;
    letter-spacing: -.01em;
}

/* meta */
.esp-hero__meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    flex-wrap: wrap;
}

.esp-meta-date {
    font-family: var(--ff-mono);
    font-size: .78rem;
    color: rgba(247, 243, 236, .6);
    letter-spacing: .03em;
}

.esp-meta-dot { color: var(--gold); }

.esp-meta-status {
    font-family: var(--ff-mono);
    font-size: .65rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: .25rem .75rem;
    border-radius: 999px;
    border: 1px solid;
}

.esp-status--active    { color: #4ade80; border-color: #4ade8055; background: #4ade800f; }
.esp-status--planned   { color: #94a3b8; border-color: #94a3b855; background: #94a3b80f; }
.esp-status--completed { color: var(--gold); border-color: #b5862b55; background: #b5862b0f; }

/* ── IP Address Badge ──────────────────────────────────────────────────────── */
.esp-ip-badge {
    margin-top: 1.75rem;
    display: flex;
    justify-content: center;
}

.esp-ip-badge__inner {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(247, 243, 236, 0.1);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(181, 134, 43, 0.3);
    border-radius: 999px;
    padding: 0.375rem 1rem;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    position: relative;
    cursor: help;
}

.esp-ip-badge__inner:hover {
    background: rgba(247, 243, 236, 0.2);
    border-color: rgba(181, 134, 43, 0.6);
}

.esp-ip-badge__icon {
    color: var(--gold);
    flex-shrink: 0;
}

.esp-ip-badge__label {
    font-family: var(--ff-mono);
    font-size: 0.65rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(247, 243, 236, 0.6);
}

.esp-ip-badge__value {
    font-family: var(--ff-mono);
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--cream);
    letter-spacing: 0.02em;
}

.esp-ip-badge__tooltip {
    position: absolute;
    bottom: calc(100% + 8px);
    left: 50%;
    transform: translateX(-50%);
    background: var(--ink);
    color: var(--cream);
    font-family: var(--ff-body);
    font-size: 0.7rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
    pointer-events: none;
    z-index: 10;
    border: 1px solid var(--gold);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.esp-ip-badge__inner:hover .esp-ip-badge__tooltip {
    opacity: 1;
    visibility: visible;
}

.esp-ip-badge__tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: var(--gold) transparent transparent transparent;
}

@media (max-width: 640px) {
    .esp-ip-badge__tooltip {
        white-space: normal;
        width: 200px;
        text-align: center;
        font-size: 0.65rem;
    }
}

/* ── Main ────────────────────────────────────────────────────────────────────── */
.esp-main {
    background: var(--cream);
    padding: 3rem 1.5rem 5rem;
}

.esp-main__inner {
    max-width: 640px;
    margin: 0 auto;
}

/* ── Flash ───────────────────────────────────────────────────────────────────── */
.esp-flash-wrap { margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: .5rem; }
.esp-flash {
    padding: .875rem 1.25rem;
    border-radius: 6px;
    font-size: .875rem;
    font-family: var(--ff-body);
}
.esp-flash--success { background: #d1fae5; color: #065f46; border-left: 3px solid #10b981; }
.esp-flash--error   { background: #fee2e2; color: #7f1d1d; border-left: 3px solid #ef4444; }
.esp-flash--info    { background: #e0f2fe; color: #0c4a6e; border-left: 3px solid #0ea5e9; }

/* ── Ballot Card ─────────────────────────────────────────────────────────────── */
.esp-ballot {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 32px rgba(20,20,24,.07), 0 1px 3px rgba(20,20,24,.04);
    padding: 2.5rem;
    border-top: 4px solid var(--border);
    animation: esp-rise .5s cubic-bezier(.22,.68,0,1.2) both;
}

@keyframes esp-rise {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.esp-ballot--active     { border-top-color: var(--gold); }
.esp-ballot--voted      { border-top-color: var(--green); }
.esp-ballot--ineligible { border-top-color: var(--slate); }

/* ── Ballot Header ───────────────────────────────────────────────────────────── */
.esp-ballot__header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.esp-ballot__icon {
    font-size: 2.25rem;
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.esp-ballot__icon--active     { background: #fef3c7; }
.esp-ballot__icon--voted      { background: #d1fae5; color: var(--green); font-size: 1.75rem; font-weight: 700; }
.esp-ballot__icon--ineligible { background: var(--parchment); color: var(--slate); font-size: 1.75rem; }

.esp-ballot__eyebrow {
    font-family: var(--ff-mono);
    font-size: .65rem;
    letter-spacing: .15em;
    color: var(--slate);
    margin: 0 0 .25rem;
}

.esp-ballot__heading {
    font-family: var(--ff-serif);
    font-size: 1.55rem;
    font-weight: 600;
    margin: 0;
    line-height: 1.2;
    color: var(--ink);
}

/* ── Countdown ───────────────────────────────────────────────────────────────── */
.esp-countdown {
    background: var(--ink);
    border-radius: 8px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.esp-countdown__label {
    font-family: var(--ff-mono);
    font-size: .65rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--gold);
    margin: 0 0 .875rem;
}

.esp-countdown__display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}

.esp-countdown__unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 3.5rem;
}

.esp-countdown__value {
    font-family: var(--ff-mono);
    font-size: 2.25rem;
    font-weight: 500;
    color: var(--cream);
    letter-spacing: -.02em;
    line-height: 1;
}

.esp-countdown__name {
    font-family: var(--ff-mono);
    font-size: .6rem;
    color: rgba(247,243,236,.45);
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-top: .3rem;
}

.esp-countdown__sep {
    font-family: var(--ff-mono);
    font-size: 1.75rem;
    color: var(--gold);
    line-height: 1;
    padding-bottom: .5rem;
}

/* ── Steps ───────────────────────────────────────────────────────────────────── */
.esp-steps {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem;
    display: flex;
    flex-direction: column;
    gap: .875rem;
}

.esp-step {
    display: flex;
    align-items: flex-start;
    gap: .875rem;
}

.esp-step__num {
    font-family: var(--ff-mono);
    font-size: .7rem;
    font-weight: 500;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    background: var(--parchment);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--gold);
    margin-top: .1rem;
}

.esp-step__text {
    font-size: .9rem;
    color: var(--ink-mid);
    line-height: 1.5;
}
.esp-step__text em { font-style: italic; color: var(--ink); }

/* ── CTA Button ──────────────────────────────────────────────────────────────── */
.esp-cta {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.125rem 1.75rem;
    background: var(--ink);
    color: var(--cream);
    border: none;
    border-radius: 8px;
    font-family: var(--ff-serif);
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: .01em;
    cursor: pointer;
    transition: background .18s, transform .15s;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.esp-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(181,134,43,.15) 100%);
    opacity: 0;
    transition: opacity .2s;
}
.esp-cta:hover:not(:disabled)::before { opacity: 1; }
.esp-cta:hover:not(:disabled) { background: var(--ink-mid); transform: translateY(-1px); }
.esp-cta:active:not(:disabled) { transform: translateY(0); }
.esp-cta:disabled { opacity: .65; cursor: not-allowed; }

.esp-cta__arrow {
    font-size: 1.1rem;
    transition: transform .2s;
}
.esp-cta:hover:not(:disabled) .esp-cta__arrow { transform: translateX(4px); }

.esp-cta--loading .esp-cta__text { opacity: .7; }

/* ── Trust Signals ───────────────────────────────────────────────────────────── */
.esp-trust {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: .5rem 1.25rem;
    justify-content: center;
}

.esp-trust__item {
    font-family: var(--ff-mono);
    font-size: .67rem;
    letter-spacing: .06em;
    color: var(--slate);
}

/* ── Voted State ─────────────────────────────────────────────────────────────── */
.esp-voted-body {
    font-size: .95rem;
    line-height: 1.7;
    color: var(--ink-mid);
    margin-bottom: 2rem;
}

.esp-certificate {
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: .75rem;
}

.esp-certificate__row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 1rem;
}

.esp-certificate__label {
    font-family: var(--ff-mono);
    font-size: .65rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--slate);
    flex-shrink: 0;
}

.esp-certificate__value {
    font-size: .875rem;
    color: var(--ink);
    text-align: right;
}

.esp-certificate__value--green {
    color: var(--green);
    font-weight: 500;
}

/* ── Ineligible State ────────────────────────────────────────────────────────── */
.esp-ineligible-body {
    font-size: .95rem;
    line-height: 1.7;
    color: var(--ink-mid);
}
.esp-ineligible-body strong { color: var(--ink); }

/* ── Responsive ──────────────────────────────────────────────────────────────── */
@media (max-width: 480px) {
    .esp-hero { padding: 3.5rem 1rem 3rem; }
    .esp-ballot { padding: 1.75rem 1.25rem; }
    .esp-ballot__header { flex-direction: column; align-items: flex-start; gap: .875rem; }
    .esp-countdown__value { font-size: 1.75rem; }
    .esp-certificate__row { flex-direction: column; gap: .2rem; }
    .esp-certificate__value { text-align: left; }
}
</style>
```

---

## 📋 **Summary of Changes**

| Addition | Description |
|----------|-------------|
| **`ipAddress` prop** | New prop to receive IP address from controller |
| **IP Badge Component** | Elegant pill-shaped badge with hover tooltip |
| **Security Tooltip** | Shows "Your IP address is recorded for security verification" on hover |
| **Responsive Design** | Tooltip adapts on mobile screens |
| **Visual Consistency** | Uses the same gold accent color and monospace font as other metadata |

---

## 🔧 **Update Controller to Pass IP Address**

In your `ElectionVotingController@show`, add the IP address:

```php
public function show($slug)
{
    // ... existing code ...

    return Inertia::render('Election/Show', [
        'election' => $election,
        'hasVoted' => $hasVoted,
        'canVote' => $canVote,
        'isEligible' => $isEligible,
        'ipAddress' => request()->ip(),  // ← Add this
    ]);
}
```

---

## ✅ **Result**

The election show page now displays:
- Elegant IP address badge in the hero section
- Hover tooltip explaining security context
- Consistent with the civic editorial design language

**Ready to implement!** 🚀