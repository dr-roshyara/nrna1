# UI Governance Backlog

**Status:** Active governance layer established May 29, 2026  
**Owner:** Frontend governance (tracked via git + PR reviews)  
**Philosophy:** Never refactor for consistency alone. Apply standards during normal feature delivery.

---

## Tier 1 — Voting Workflow (Highest User Impact)

These are the pages voters interact with directly. Consistency here affects user trust and clarity.

### UI-001
**What:** Replace 50+ hardcoded voted/active/pending badges with `<StatusBadge>`

**Pages:**
- `resources/js/Pages/Vote/Create.vue`
- `resources/js/Pages/Vote/Verify.vue`
- `resources/js/Pages/Vote/Result.vue`
- `resources/js/Pages/DemoVote/Create.vue`
- `resources/js/Pages/DemoVote/Verify.vue`

**Priority:** High  
**Business Value:** High — voters see status in 1 consistent color instead of 4 across pages  
**Effort:** Small (4h)

**Acceptance:** All status displays use `<StatusBadge status="voted" />`, no inline badge HTML

---

### UI-002
**What:** Adopt `<Button>` component in Voting workflow

**Pages:**
- `resources/js/Pages/Vote/Create.vue`
- `resources/js/Pages/Vote/Verify.vue`
- `resources/js/Pages/Vote/CreateVotingPage.vue`

**Priority:** High  
**Business Value:** High — primary user journey, CTA prominence clear  
**Effort:** Small (2h) — replace raw `<button>` with `<Button variant="primary" />`

**Acceptance:** All buttons use canonical component, consistent sizing and hover states

---

### UI-003
**What:** Apply `<WorkflowLayout>` to Voting workflow

**Pages:**
- `resources/js/Pages/Vote/CreateVotingPage.vue`
- `resources/js/Pages/Vote/Create.vue`
- `resources/js/Pages/Vote/Verify.vue`

**Priority:** High  
**Business Value:** High — users recognize consistent step-by-step pattern (Code → Agreement → Vote → Verify → Complete)  
**Effort:** Medium (6-10h, depends on page complexity) — restructure page sections into slots, handle conditional sections and error states

**Acceptance:** Pages use WorkflowLayout with correct step tracking, progress indicator renders, all slots properly implemented

---

### UI-004
**What:** Add focus states to Voting form fields

**Pages:**
- `resources/js/Pages/Vote/CreateVotingPage.vue`
- `resources/js/Pages/Vote/DemoVote/CreateVotingform.vue`

**Priority:** High  
**Business Value:** Medium — accessibility + keyboard navigation critical for voters  
**Effort:** Small (2h) — add `focus:ring-2 focus:ring-primary-500 focus:ring-offset-2` to all inputs

**Acceptance:** All form inputs have visible focus state when tabbing

---

## Tier 2 — Election Workflow (Election Manager Facing)

Election managers perform setup, approval, and results verification. Consistency here affects platform credibility with administrators.

### UI-005
**What:** Apply `<WorkflowLayout>` to Election creation workflow

**Pages:**
- `resources/js/Pages/Election/ElectionPage.vue`
- `resources/js/Pages/Election/Show.vue`
- `resources/js/Pages/Election/Management.vue`

**Priority:** High  
**Business Value:** High — election managers recognize structure across domains  
**Effort:** Medium (6h)

**Acceptance:** Election management pages follow consistent workflow topology

---

### UI-006
**What:** Adopt `<Button>` component in Election workflow

**Pages:**
- `resources/js/Pages/Election/Show.vue`
- `resources/js/Pages/Election/Management.vue`
- `resources/js/Pages/Election/ElectionIndex.vue`
- `resources/js/Pages/Election/Posts/Index.vue`

**Priority:** High  
**Business Value:** Medium — staff-facing, high frequency  
**Effort:** Small (2h)

**Acceptance:** All buttons use canonical component

---

### UI-007
**What:** Adopt `<StatusBadge>` in Election pages

**Pages:**
- `resources/js/Pages/Election/ElectionIndex.vue`
- `resources/js/Pages/Election/ElectionResult.vue`
- `resources/js/Pages/Admin/Elections/All.vue`

**Priority:** High  
**Business Value:** High — election state consistency is critical for transparency  
**Effort:** Small (2h) — existing election statuses map directly to StatusBadge API

**Acceptance:** All election state displays use `<StatusBadge status="voting_active" />`

---

### UI-008
**What:** Add focus states to Election form fields

**Pages:**
- `resources/js/Pages/Election/Posts/Partials/PostForm.vue`
- `resources/js/Pages/Election/Posts/Partials/CandidateForm.vue`
- `resources/js/Pages/Election/Candidacy/Applications.vue`

**Priority:** Medium  
**Business Value:** Medium — accessibility  
**Effort:** Small (2h)

**Acceptance:** All inputs have visible focus state

---

## Tier 3 — Membership Workflow (Member Facing)

Membership pages handle registration, verification, approval, and role management.

### UI-009
**What:** Apply `<WorkflowLayout>` to Membership workflow

**Pages:**
- `resources/js/Pages/Organisations/Membership/` (all multi-step pages when touched)

**Priority:** Medium  
**Business Value:** Medium — member experience convergence  
**Effort:** Medium (6h) — defer until membership pages are modified for feature work

**Acceptance:** Membership workflows follow consistent topology

---

### UI-010
**What:** Adopt `<StatusBadge>` in Membership pages

**Pages:**
- `resources/js/Pages/Organisations/Membership/Member/` (status displays)

**Priority:** Medium  
**Business Value:** Medium — member status clarity  
**Effort:** Small (2h)

**Acceptance:** All member statuses (active, pending, inactive, approved) use `<StatusBadge>`

---

## Tier 4 — Admin & Internal (Low Priority)

These pages are staff-only and have low user visibility. Defer consistency work until normal maintenance.

### UI-011
**What:** Consolidate table border colors in Admin pages

**Pages:**
- `resources/js/Pages/Members/Index.vue`
- `resources/js/Pages/Admin/GeoUnits.vue`
- `resources/js/Pages/Admin/GovernanceLevels.vue`

**Priority:** Low  
**Business Value:** Low — admin-only, low user visibility  
**Effort:** Small (2h) — find-replace `slate-200` → `neutral-200` in table contexts

**Acceptance:** All table borders use consistent neutral color

---

### UI-012
**What:** Consolidate modal patterns in Election/Candidacy pages

**Pages:**
- `resources/js/Pages/Election/Candidacy/Applications.vue`
- `resources/js/Pages/Election/Candidacy/Index.vue`

**Priority:** Low  
**Business Value:** Low — staff-facing, low frequency  
**Effort:** Medium (4h) — defer to Tier 3+ phases

**Acceptance:** All modals follow canonical modal structure

---

### UI-013 (Deferred to Phase 2)
**What:** Create and adopt `<AppTable>` canonical component

**Scope:** 30+ table implementations across Admin pages and data-heavy views

**Priority:** Low  
**Business Value:** Medium — table consistency across admin panels and results pages  
**Effort:** Medium (8-12h) — create component, then migrate adoption

**Status:** Deferred — do NOT implement until WorkflowLayout stabilizes (Phase 1 complete)  
**Reason:** Phase 1 focuses on user-facing workflows (Voting, Election, Membership). Admin tables are lower priority. TableLayout created in Phase 2 after learning from WorkflowLayout.

**Note:** This prevents the component from being forgotten, but prioritizes delivery of voting/election features first.

---

## Tracking Rules

1. **Only add items to this backlog during feature work** — never as standalone consistency "projects"
2. **Link each item to a PR** — consistency work should be part of feature delivery, not separate
3. **Review effort estimates quarterly** — some may be easier/harder than estimated
4. **Monthly check-in:** Review Tier 1 progress (voting and election workflows are critical path)

---

## Success Metrics

| Milestone | Target | Dates |
|-----------|--------|-------|
| Governance layer active | May 29, 2026 | ✅ Done |
| Voting workflow consistency (UI-001 through UI-004) | June 30, 2026 | — |
| Election workflow consistency (UI-005 through UI-008) | July 31, 2026 | — |
| 70% of newly touched pages use canonical patterns | September 30, 2026 | — |

---

## Rules

- ✅ When a page is modified for business work: bring it to canonical patterns
- ✅ Use this backlog to communicate upcoming work to the team
- ✅ Link backlog items in PR descriptions: "Addresses UI-001"
- ❌ Never refactor an untouched page solely for consistency
- ❌ Never estimate effort on Tier 4 items until they're promoted

---

**Last Updated:** May 29, 2026  
**Governance:** Tied to `FRONTEND_DECISIONS.md` and `.claude/UI_GUIDELINES.md`
