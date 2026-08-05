# Design System Refactoring TODO — Phase 4–5 Roadmap

**Last Updated:** 2026-05-01  
**Current Status:** Phase 4 Iteration 2 Complete, Iteration 3 In Progress  
**Baseline:** 589 violations (was 613 at start)

---

## ✅ Completed

### Phase 4 Iteration 1: Dashboard Pages ✅
- ✅ `Commission/Dashboard.vue` (3 violations → 0)
- ✅ `Organisations/Elections/Index.vue` (5 violations → 0)
- ✅ `Pages/Vote/Create.vue` (13 violations → 0, preserved gradient)
- **Result:** 613 → 592 violations (21 removed)

### Phase 4 Iteration 2: Admin Management ✅
- ✅ `Election/Management.vue` (8 actual violations targeted)
  - Skip link: `focus:bg-blue-*` → `focus:bg-primary-*`
  - Flash error: `bg-red-*` → `bg-danger-*`
  - Results status card: `bg-blue-*` → `bg-primary-*`
  - Modal confirm button: raw `<button>` → `<Button variant="primary">`
  - Modal textarea/error: `focus:ring-blue-*`, `text-red-*` → semantic tokens
- **Result:** 592 → 580 violations (12 removed)

---

## 🚧 In Progress

### Phase 4 Iteration 3: Public & Voting Pages

**Target Files (35+ violations):**

1. **`VotingStart.vue`** — `/voting` ⏳ In Progress
   - Status: All color replacements applied (9 violations targeted)
   - Changes: `bg-blue-*` → `bg-primary-*`, `text-gray-*` → `text-neutral-*`, `bg-green-*` → `bg-success-*`
   - Gradients: `from-blue-50 to-white` → `from-primary-50 to-white`, `from-blue-900 to-blue-800` → `from-primary-900 to-primary-800`
   - CSS: Hex `#2563eb` → CSS var `var(--primary-600, #2563eb)`
   - High-contrast mode: Updated class selectors
   - Next: Build verification & violation count

2. **`Pricing.vue`** — `/pricing` (9 violations)
   - Action: Migrate all `bg-blue-*`, `text-blue-*`, `border-blue-*` to primary
   - Keep: Intentional color-coded pricing tiers if present
   - Status: Pending

3. **`Tutorials/MembershipModes.vue`** — `/help/membership-modes` (9 violations)
   - Action: Same as above
   - Status: Pending

4. **`VotingElection.vue`** — `/election` (8 violations)
   - Action: Same as above
   - Status: Pending

**Iteration 3 Expected Outcome:** 580 → ~545 violations (35 removed)

---

## 📋 Remaining Iterations (Phase 4 Continued)

### Phase 4 Iteration 4: Voting/Demo Pages
- `Vote/DemoVote/Guide.vue` (6)
- `Vote/DemoVote/Create.vue` (5)
- `Vote/DemoVote/PublicResult.vue` (5)
- **Subtotal:** 16 violations

### Phase 4 Iteration 5: User & Admin Settings
- `User/Index.vue` (6)
- `Organisations/Settings/Index.vue` (6)
- `Members/Index.vue` (6)
- **Subtotal:** 18 violations

### Phase 4 Iteration 6: Public Content Pages
- `Public/Security.vue` (6)
- `Public/ElectionArchitecture.vue` (5)
- `Public/ElectionSecurity.vue` (5)
- `Organisations/Members/ImportTutorial.vue` (5)
- `Timeline/TimelineIndex.vue` (5)
- **Subtotal:** 26 violations

**Phase 4 Total Expected Violations Removed:** ~96 (target: 589 → 493)

---

## 🎯 Phase 5: Layout Consolidation (Deferred)

**Critical:** Only after Phase 4 complete. Slot structure verification required.

### Files to Consolidate
1. **`NrnaLayout.vue`** → Wrapper to `PublicLayout.vue`
2. **`LoginLayout.vue`** → Wrapper to `PublicLayout.vue`
3. **Keep:** `PublicDigitLayout.vue` as canonical `PublicLayout.vue`

### Cleanup (After Verification)
- Delete: 42 hash-named `.vue.txt` files in `Components/`
- Delete: Backup files (`*_backup.vue`, `*.vue.backup`, `*_backup.backup.vue`)
- Delete: `Applayout_backup.vue`, `NrnaHeader copy.vue`, `NrnaHeader_backup.vue`, `NrnaFooter.vue_backup`

---

## 📊 Enforcement & Governance

### Script Threshold Tightening (After Each Iteration)
- Current: 150 violations max (permissive)
- After Iteration 3: Tighten to 100
- After Iteration 4: Tighten to 75
- After Iteration 5: Tighten to 50
- After Iteration 6: Tighten to 25
- Final Target: 0 violations (hard fail)

**Update:** `scripts/check-design-tokens.sh` line 15

### Governance Rules (Enforced)
```
✅ Allowed in Pages:
  - <Button variant="primary|secondary|danger|ghost">
  - <Card>, <ActionButton>, <SectionCard>
  - text-primary-*, text-success-*, text-danger-*, text-warning-*
  - mapElectionStateToUI(state) for badge colors
  - Intentional gradients with semantic colors (from-primary-50, etc.)

❌ Forbidden in Pages:
  - bg-blue-* (raw)        → <Button variant="primary">
  - bg-red-* (raw)         → <Button variant="danger">
  - bg-gray-* (raw)        → bg-neutral-*
  - border-gray-* (raw)    → border-neutral-*
  - text-gray-* (raw)      → text-neutral-*
  - rounded-xl (cards)     → <Card> (rounded-lg default)
```

---

## 📝 Design Debt Register

### Intentional Designs (Preserve — Not Violations)
| File | Element | Reason | Status |
|------|---------|--------|--------|
| Vote/Create.vue | Gradient success banner | Celebratory voter UX | ✅ Preserved |
| VotingStart.vue | Blue gradient callout | Marketing/branding | ✅ Preserved |
| Management.vue | Results link gradient | Intentional decoration | ✅ Preserved |
| Commission/Dashboard.vue | Stat card color coding | Visual hierarchy | ✅ Preserved |
| Election/Show.vue | Editorial serif design | Civic/formal feel | 🏖️ Deferred (Phase 5) |

### Sub-Component Sections (Out of Scope)
| Component | Reason | Status |
|-----------|--------|--------|
| StateMachinePanel.vue | Progress timeline colors | Separate component |
| StateProgress.vue | Phase indicator badges | Separate component |
| ElectionCard.vue | Intentional warning colors | Separate component |

---

## 🔧 Implementation Checklist

### Before Each Iteration
- [ ] Run baseline: `bash scripts/check-design-tokens.sh`
- [ ] Record violation count
- [ ] Identify target files
- [ ] Plan color migrations

### During Each Iteration
- [ ] Read file to identify violations
- [ ] Apply `replace_all` for each color token
- [ ] Verify no partial replacements missed
- [ ] Build: `npm run build` (must pass)
- [ ] Spot-check 2–3 pages in browser (no visual regressions)

### After Each Iteration
- [ ] Re-run baseline script
- [ ] Update threshold in `check-design-tokens.sh`
- [ ] Commit changes: `git commit -m "..."`
- [ ] Update this TODO file

---

## 📅 Timeline Estimate

| Iteration | Files | Violations | Est. Hours | Est. Complete |
|-----------|-------|-----------|-----------|---------------|
| 3 | 4 | 35 | 2 | 2026-05-02 |
| 4 | 3 | 16 | 1 | 2026-05-03 |
| 5 | 3 | 18 | 1.5 | 2026-05-03 |
| 6 | 5 | 26 | 2 | 2026-05-05 |
| **5** | Layout | – | 3 | 2026-05-08 |
| **Phase Complete** | – | – | **~9.5h** | **2026-05-08** |

---

## 🚀 Success Criteria

### Iteration Success
- ✅ All target files migrated
- ✅ Build passes with zero errors
- ✅ No visual regressions in browser (spot-check)
- ✅ Violation count decreased as expected
- ✅ Threshold script updated & passing

### Phase 4 Success
- ✅ All 5–6 iterations complete
- ✅ Violations: 589 → <50
- ✅ Enforcement script consistently fails >50 violations
- ✅ No governance rule breaches in new code

### Phase 5 Success
- ✅ Layout slots verified before merging
- ✅ Backup files deleted
- ✅ Zero double-nesting bugs
- ✅ All imports updated
- ✅ Final violation count: 0 (hard fail active)

---

## 🔗 Related Files

- Plan: `C:\Users\nabra\.claude\plans\use-plan-mode-and-groovy-goblet.md`
- Enforcement: `scripts/check-design-tokens.sh`
- Design Tokens: `resources/js/design-tokens/tokens.json`
- Token Config: `tailwind.config.js`, `resources/css/tokens.css`
- Domain Mapper: `resources/js/Utils/electionUiMapper.js`

---

**Next Action:** Complete VotingStart.vue verification, proceed to Pricing.vue (Iteration 3).
