# 🎨 UI/UX Unification Foundation Plan

**Date:** 2026-05-30
**Phase:** Foundation
**Status:** In Progress

## Context

The codebase has a solid starting point — `tokens.css`, `Button.vue`, `Card.vue`, `StatusBadge.vue`, and four shell scripts in `./scripts/`. However there is no **unified design governance**: Vue files still use raw Tailwind colors, components are inconsistent across pages, and there is no automated enforcement beyond token counting.

The gap between `scripts/` linting and actual rendered UX was demonstrated by the "candidates page shows zero candidates but passes all token checks" bug.

## Goal

Build a **layered design governance system** starting from the foundation (`scripts/` config files + component registry) and progressively adding visual, accessibility, and responsive gates.

## Architecture

```
scripts/                          # Enforcement gates (foundation layer)
├── design-rules.json             # Configurable token patterns (created)
├── design-check.sh               # Token compliance scanner (refactored to consume JSON)
├── check-design-tokens.sh        # Quick token count gate (reads threshold from JSON)
├── migration-progress.sh         # Phase target tracker
├── ui-components.json            # Component registry (NEW)
├── component-audit.sh            # Raw HTML component bypass scanner (NEW)
└── verify.sh                     # Master orchestration (NEW)

resources/css/
├── tokens.css                    # CSS custom properties (refactored)
├── app.css                       # Tailwind v4 theme registration
└── components.css                # Shared component styles (NEW)

resources/js/Components/
├── Button.vue                    # Existing — upgrade to trust mark variant
├── Card.vue                      # Existing — upgrade
├── StatusBadge.vue               # Existing — upgrade
├── Modal.vue                     # NEW
├── Input.vue                     # NEW
├── Dropdown.vue                  # NEW
├── ProgressBar.vue               # NEW
├── VerificationSeal.vue           # NEW — trust mark component
└── index.js                      # NEW — component registry export
```

---

## Phase 0: Script Foundation (THIS SESSION)

### 0.1 `design-rules.json` ✅
Configurable token pattern definitions consumed by `design-check.sh`. Includes:
- Background / text / border / ring / divide / placeholder color patterns
- Shadow size and font weight patterns
- Baseline (613), phase targets, enforcement thresholds
- Per-rule severity (error / warning / info) and allow-lists

### 0.2 `design-check.sh` ✅
Refactored from hardcoded grep to JSON-driven rule engine.
- Iterates `design-rules.json` rules array
- Outputs unified violation report per rule
- Calculates migration progress bar
- Compares against phase targets
- `--strict` flag forces exit code 1 on threshold exceed

### 0.3 `check-design-tokens.sh` ✅
Reads `current_threshold` from `design-rules.json` instead of hardcoded 150.

### 0.4 `tokens.css` ✅
Upgraded to v2 "Civic Clarity":
- Added DM Serif Display (headings) + DM Sans (body) typography
- Added `--shadow-seal` (trust glow)
- Added 5 keyframe animations (seal-verify, seal-pulse, fade-in-up, count-up, slide-in-right)
- Added `.trust-seal` utility classes (verified / warning variants)
- Added focus ring system

---

## Phase 1: Component Registry & Anti-Inline Linting

### 1.1 `ui-components.json` — Component Registry
```json
{
  "schema_version": "1.0",
  "baseline": {
    "raw_buttons": 127,
    "raw_inputs": 89,
    "total_pages": 64
  },
  "components": [
    {
      "name": "Button",
      "path": "resources/js/Components/Button.vue",
      "aliases": ["ActionButton"],
      "variants": ["primary","secondary","outline","ghost","danger","danger-outline","accent","success","warning"],
      "replaces_tags": ["<button>"],
      "usage_count": 0,
      "target_count": 0
    },
    {
      "name": "Card",
      "path": "resources/js/Components/Card.vue",
      "replaces_tags": ["<div class=\"...rounded...shadow...border"],
      "usage_count": 0,
      "target_count": 0
    },
    {
      "name": "Modal",
      "path": "resources/js/Components/Modal.vue",
      "replaces_tags": ["<div.*fixed", "<div.*modal", "<teleport"],
      "usage_count": 0,
      "target_count": 0
    },
    {
      "name": "Input",
      "path": "resources/js/Components/Input.vue",
      "replaces_tags": ["<input", "<select", "<textarea"],
      "usage_count": 0,
      "target_count": 0
    },
    {
      "name": "StatusBadge",
      "path": "resources/js/Components/StatusBadge.vue",
      "replaces_tags": [],
      "usage_count": 0,
      "target_count": 0
    }
  ]
}
```

### 1.2 `component-audit.sh` — Usage Scanner
Scans all `.vue` files and counts:
- Raw `<button` tags (should use `<Button>` component)
- Raw `<input` / `<select` / `<textarea` tags (should use `<Input>` component)
- Raw `<div class="...rounded...shadow..."` (should use `<Card>` component)
- Reports component usage counts per page
- Fails if raw count exceeds baseline (prevents regressions)

### 1.3 Wire into `verify.sh`
Master orchestration script:
```bash
#!/bin/bash
./scripts/design-check.sh --strict || exit 1
./scripts/component-audit.sh || exit 1
echo "✅ All design gates passed"
```

---

## Phase 2: Core Component Library

### 2.1 `Modal.vue`
Purpose: Election confirmation dialogs, suspension warnings, action confirmations.
- Props: `open`, `title`, `size` (sm/md/lg/fullscreen), `closable`, `onClose`
- Slots: default (body), `footer`
- Features: backdrop blur, ESC to close, focus trap, body scroll lock, enter/leave animation
- Accessibility: `role="dialog"`, `aria-modal="true"`, `aria-labelledby`

### 2.2 `Input.vue`
Purpose: Unified form input — text, number, email, textarea, select.
- Props: `modelValue`, `label`, `type`, `placeholder`, `error`, `hint`, `disabled`, `required`, `size`
- Variants: default, error, success
- Accessibility: `aria-describedby` for hint/error, `aria-invalid` for error state

### 2.3 `Dropdown.vue`
Purpose: Election selector, role picker, status filter.
- Props: `items`, `modelValue`, `label`, `placeholder`, `searchable`, `clearable`
- Features: click outside to close, keyboard navigation (arrows + enter), search filter

### 2.4 `ProgressBar.vue`
Purpose: Voting progress, setup progress, data import progress.
- Props: `value`, `max`, `size` (sm/md/lg), `variant` (primary/success/warning/danger), `showLabel`, `animated`
- Features: CSS-only transition, aria-valuenow/min/max, label formatter

### 2.5 `VerificationSeal.vue`
Purpose: Trust mark — visual confirmation of official/verified state.
- Props: `status` (verified/pending/warning/idle), `size` (sm/md/lg), `label`
- Features: SVG shield icon with animated verification check, glowing border on verified state
- This is the **differentiator** — every confirmed/official action gets the seal

### 2.6 `index.js` — Barrel Export
```js
export { default as Button } from './Button.vue'
export { default as Card } from './Card.vue'
export { default as Modal } from './Modal.vue'
export { default as Input } from './Input.vue'
export { default as Dropdown } from './Dropdown.vue'
export { default as ProgressBar } from './ProgressBar.vue'
export { default as StatusBadge } from './StatusBadge.vue'
export { default as VerificationSeal } from './VerificationSeal.vue'
```

---

## Phase 3: Visual Regression Testing

### 3.1 Playwright Setup
```bash
npm install -D @playwright/test
npx playwright install chromium
playwright.config.js
```

### 3.2 Voting Flow Visual Test
Core business path — public demo voting flow:
1. `/public-demo/start` — entry page screenshot
2. Code entry step — code verification screenshot
3. Vote page — ballot rendering (catch the "no candidates" bug)
4. Verification confirmation — seal animation screenshot

### 3.3 Responsive Breakpoints
All tests run at:
- Mobile: `375x812` (iPhone X)
- Tablet: `768x1024` (iPad)
- Desktop: `1440x900`

### 3.4 Script: `visual-diff.sh`
Runs Playwright with `--reporter=list`.
Exit code 1 on any snapshot mismatch.

---

## Phase 4: Accessibility Compliance

### 4.1 `a11y-check.sh`
Uses axe-cli or @axe-core/playwright to audit:
- Color contrast (WCAG AA — 4.5:1 normal, 3:1 large)
- Keyboard navigation (all interactive elements reachable)
- ARIA landmarks (proper roles on sections)
- Form labels (every input has associated label)
- Focus indicators (visible focus ring on interactive elements)

### 4.2 Fail conditions
- Any `critical` or `serious` axe violation → fail
- Missing heading hierarchy → fail
- Missing form labels → fail

---

## Phase 5: CI Integration

### 5.1 GitHub Actions Workflow
```yaml
name: UI Design Gate
on: [pull_request]
jobs:
  design-check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm run design-check
      - run: npm run component-audit
      - run: npm run a11y-check
      - run: npx playwright test tests/Visual
```

### 5.2 Pre-commit Hook
`npm run design-check --strict` runs before every commit via husky.

---

## Guardrails

1. **No regressions allowed.** Baseline counts only decrease, never increase.
2. **design-rules.json is the authority.** All scripts consume it — never hardcode thresholds.
3. **Sequential gating.** Phase 1 gates before Phase 2, Phase 2 before Phase 3, etc.
4. **Progressive enforcement.** Thresholds tighten as phases complete: 150 → 100 → 50 → 0.

---

## Success Criteria

| Metric | Current | Phase 1 | Phase 2 | Phase 3 | Phase 4 | Final |
|--------|---------|---------|---------|---------|---------|-------|
| Token violations | ~600 | 150 | 100 | 50 | 10 | 0 |
| Raw `<button>` tags | ~127 | 127 | 100 | 75 | 50 | 0 |
| Axe violations | unknown | — | — | — | 0 | 0 |
| Visual snapshots | 0 | — | — | 5 | 10 | 20 |
| Scripts in CI | 0 | 3 | 3 | 4 | 5 | 5 |

---

## Files to Create

| File | Internal ID |
|------|-------------|
| `scripts/design-rules.json` | ✅ Done |
| `scripts/design-check.sh` | ✅ Done (v2) |
| `scripts/check-design-tokens.sh` | ✅ Done (v2) |
| `resources/css/tokens.css` | ✅ Done (v2) |
| `scripts/ui-components.json` | 1.1 |
| `scripts/component-audit.sh` | 1.2 |
| `scripts/verify.sh` | 1.3 |
| `resources/js/Components/Modal.vue` | 2.1 |
| `resources/js/Components/Input.vue` | 2.2 |
| `resources/js/Components/Dropdown.vue` | 2.3 |
| `resources/js/Components/ProgressBar.vue` | 2.4 |
| `resources/js/Components/VerificationSeal.vue` | 2.5 |
| `resources/js/Components/index.js` | 2.6 |
| `tests/Visual/` | 3.x |
| `scripts/a11y-check.sh` | 4.1 |
| `.github/workflows/design-gate.yml` | 5.1 |
