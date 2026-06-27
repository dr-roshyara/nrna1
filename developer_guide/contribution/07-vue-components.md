# 07 — Vue Components

All four pages live in `resources/js/Pages/Contributions/` and use the `PublicDigitLayout` wrapper.

---

## Create.vue — Contribution Form

**Renders:** `Contributions/Create`
**Props:** `organisation`, `weeklyPoints`, `weeklyCap`

### Features

| Feature | Implementation |
|---------|---------------|
| Weekly cap indicator | Progress bar showing used/remaining micro-track points |
| Track selection cards | 3-card grid (micro/standard/major) with visual distinction |
| Effort slider + number input | Dual input for hours, range 1–40 |
| Skill toggle pills | 10 available skills, toggle on/off, drives synergy multiplier |
| Synergy tooltip | Hover tooltip explaining 1.0x/1.2x/1.5x bonus thresholds |
| Proof type selector | 5-button grid showing multiplier values |
| Recurring toggle | Checkbox with "+20% sustainability bonus" label |
| Live points preview | Card showing full formula breakdown, updates reactively |
| Weekly cap warning | Shows when < 30 micro-track points remain |

### Formula Mirror

The component contains a JavaScript copy of `GaneshStandardFormula::TRACK_CONFIG`:

```javascript
const TRACK_CONFIG = {
  micro:    { base_rate: 10, tier_bonus: 0,   min_base: 0,   weekly_cap: 100 },
  standard: { base_rate: 10, tier_bonus: 50,  min_base: 31,  weekly_cap: null },
  major:    { base_rate: 10, tier_bonus: 200, min_base: 201, weekly_cap: null },
}
```

If the backend formula changes, this must be updated too. The frontend is an estimate; the backend is authoritative.

### Inertia 2.0 Submission

```javascript
router.post(route('organisations.contributions.store', props.organisation.slug), form.value, {
  preserveScroll: true,
  onError: (err) => { errors.value = err },
  onFinish: () => { submitting.value = false },
})
```

No raw `fetch()`. No manual CSRF. Inertia handles both.

---

## Index.vue — My Contributions

**Renders:** `Contributions/Index`
**Props:** `organisation`, `contributions` (paginated), `weeklyPoints`, `weeklyCap`

### Features

| Feature | Implementation |
|---------|---------------|
| Weekly summary card | Shows points used/remaining with progress bar |
| Empty state | Icon + "Log your first contribution" CTA when no data |
| Contribution cards | Track badge, status badge, title, effort/proof info, points |
| Pagination | Laravel pagination links rendered as styled buttons |
| Leaderboard link | Quick link to organisation leaderboard |

### Badge Classes

Track and status badges use colour-coded classes:

```javascript
// Track: micro=blue, standard=amber, major=purple
// Status: pending=yellow, approved/completed=green, rejected=red, appealed=orange
```

### Date Formatting

```javascript
const formatDate = (dateStr) => {
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}
// Output: "11 Apr 2026"
```

---

## Show.vue — Contribution Detail

**Renders:** `Contributions/Show`
**Props:** `organisation`, `contribution` (with `ledgerEntries` eager-loaded)

### Sections

1. **Points Card** — Large purple gradient card showing `calculated_points`
2. **Details Card** — Description, effort hours, proof type, recurring flag, outcome bonus, team skills
3. **Review History** — Verification and approval timeline with dates and notes
4. **Points Ledger** — List of all ledger entries for this contribution (earned, adjusted, etc.)
5. **Status Messages** — Yellow banner for pending, red banner for rejected (with verifier notes)

### Data Display

- `team_skills` rendered as purple pill badges
- `proof_type` formatted with `replace(/_/g, ' ')` and title-cased
- Ledger entries show green for positive points, red for negative

---

## Leaderboard.vue — Organisation Leaderboard

**Renders:** `Contributions/Leaderboard`
**Props:** `organisation`, `board` (array from `LeaderboardService::get()`)

### Features

| Feature | Implementation |
|---------|---------------|
| Top-3 podium | 3-column grid: 2nd, 1st (elevated), 3rd with distinct styling |
| Full ranking table | Every ranked user with medal badges for top 3 |
| Relative bar chart | Horizontal bars scaled to max points in the list |
| Empty state | "Be the first to log a contribution" message |
| Privacy note | Footer explaining public/anonymous/private visibility |

### Bar Width Calculation

```javascript
const maxPoints = computed(() => props.board[0]?.total_points || 1)
const barWidth = (points) => Math.max(2, (points / maxPoints.value) * 100)
```

Minimum 2% width so every entry has a visible bar.

---

## Design System

All components follow the project's established patterns:

- **Layout:** `PublicDigitLayout` wrapper
- **Navigation:** `Link` from `@inertiajs/vue3` (not `<a>` tags)
- **Routing:** `route()` helper via Ziggy
- **Colours:** Purple primary (`purple-600`), slate neutrals, gradient accents
- **Borders:** `rounded-2xl` cards with `border-slate-200`
- **Hover:** `hover:border-purple-300 hover:shadow-md transition-all`
