# Discovery: Candidacy Apply.vue Assessment

**Date:** 2026-06-13  
**Status:** Complete  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)  
**Context:** Evaluation for next DDD extraction — does Apply.vue contain domain logic?

## 1. File Overview

| Metric | Value |
|--------|-------|
| Total lines | 1076 |
| Template | ~350 lines (33%) |
| Script | ~106 lines (10%) |
| CSS | ~615 lines (57%) |
| API calls | 1 (`router.post` — form submission) |
| State refs | 8 (form, errors, isSubmitting, agreedToTerms, termsError, photoFile, photoPreview, photoSizeError) |
| Props | 4 (organisation, election, posts, existingApplication) |
| Composable imports | 0 |
| Domain imports | `ElectionLifecycleStates` (from Constants/) |

**Key insight:** Unlike Management.vue (1598 lines, 12 API calls, 16 methods), Apply.vue is **mostly CSS** (~57%). The actual script section is only 106 lines with 1 API call. It is a form page, not an orchestration page.

## 2. Business Rules Inventory

| Rule | Location | Layer | Notes |
|------|----------|-------|-------|
| Must be in `SETUP_NOMINATION` state to see the form | Line 165 | **Presentation** (conditional render) | Backend-enforced; frontend just hides/shows |
| Cannot apply if `existingApplication` is not null | Line 165 | **Presentation** | Backend-enforced |
| Must select a post before submitting | Lines 431-434 | **Presentation** (client validation) | Duplicates backend validation |
| Must agree to terms before submitting | Lines 426-429 | **Presentation** | UX guard only |
| Photo max 5MB | Lines 395-398 | **Presentation** (client check) | Backend also enforces |
| Photo must be JPG/PNG | Line 266 | **Presentation** (accept attribute) | Backend also enforces |
| Supporter/proposer names max 255 chars | Lines 183, 201 | **Presentation** (HTML maxlength) | Backend enforces |
| Manifesto max 5000 chars | Line 224 | **Presentation** (maxlength + counter) | Backend enforces |

**Verdict:** All business rules in Apply.vue are **presentation-layer guards**. They duplicate backend validation for UX purposes (instant feedback). No domain logic exists in isolation — every rule is a convenient client check, not an authoritative business decision.

## 3. Method Inventory

| Method | Lines | Logic | Classification |
|--------|-------|-------|----------------|
| `handlePhoto` | 391-405 | File size validation (5MB) + create preview | **Presentation** |
| `removePhoto` | 407-413 | Revoke object URL + reset refs | **Presentation** |
| `selectPost` | 416-419 | Set form.post_id + clear error | **Presentation** |
| `submitForm` | 422-460 | Validate terms + post → FormData → router.post | **Application** (thin) |

`submitForm` is the only method that crosses into Application territory, but it is pure orchestration:
1. Validate terms (client)
2. Build FormData
3. POST to backend
4. Map errors to UI

There is no business-rule branching like Management.vue's `voterCount <= 40`.

## 4. Existing Abstractions Available for Reuse

| Artifact | Used? | Notes |
|----------|-------|-------|
| `ElectionLayout` | ✅ Yes | Correct layout |
| `ElectionLifecycleStates` | ✅ Yes | Used for `SETUP_NOMINATION` check |
| `useElectionCapabilities` | ❌ Not imported | Not needed — no capability checks |
| `useElectionActions` | ❌ Not imported | Not needed — single API call |
| `ElectionPhaseService` | ❌ Not imported | Not needed |
| `ElectionApprovalPolicy` | ❌ Not imported | Not applicable — not an approval flow |

No ADR-001 reuse opportunity exists because no existing, unused abstraction applies to this page.

## 5. Duplication Check

| Duplication | Location | Assessment |
|-------------|----------|------------|
| Photo validation (5MB, JPG/PNG) | Apply.vue + backend | Acceptable — standard UX pattern |
| Post selection required | Apply.vue + backend | Acceptable — instant feedback |
| Terms agreement | Apply.vue + backend | Acceptable — UX guard |
| Nomination state check | Apply.vue + `StateMachinePanel.vue` | Both check `SETUP_NOMINATION` but in different contexts |

No problematic duplication found. All client-side validations are standard UX patterns, not competing domain logic.

## 6. Extraction Feasibility

| Candidate | Reason | Verdict |
|-----------|--------|---------|
| Eligibility policy | All eligibility is backend-enforced | ❌ No frontend domain rule to extract |
| Deadline enforcement | Frontend checks state via prop, not dates | ❌ Backend owns deadlines |
| Photo validation policy | 5MB JPG/PNG is generic file validation | ❌ Would violate YAGNI |
| Terms acceptance policy | Boolean guard | ❌ Too trivial |

**No extraction candidate exists in this page.**

## 7. Architectural Conclusion

| Question | Answer |
|---|---|
| Does Apply.vue contain domain logic? | ❌ No. All rules are presentation-layer UX guards. |
| Is there an ADR-001 reuse opportunity? | ❌ No. All existing abstractions are either already in use or not applicable. |
| Should we extract anything? | ❌ Not justified. This page is a thin form. |
| What about the remaining 1076 lines? | ~615 lines are CSS for the document-style UI. The page is visually rich but architecturally simple. |

**Verdict:** No extraction justified. Move to next candidate.

## 8. Discovery Outcome

| Result | Detail |
|--------|--------|
| Extraction? | **NO EXTRACTION** |
| Reason | No domain invariant discovered. No policy discovered. No duplicated business rule discovered. No existing abstraction suitable for reuse. All form logic is presentation-layer validation. |
| Decision | Leave Apply.vue unchanged. |

This page was inspected intentionally and does not require architectural changes.

## 9. Updated Priority

| Priority | Candidate | Action | Status |
|----------|-----------|--------|--------|
| 1 | `Election/Candidacy/Apply.vue` | Discovery | ✅ Complete — no extraction justified |
| 2 | `Vote/CreateVote.vue` (1408 lines, voting domain) | Discovery | 🔍 Next |
| 3 | `Vote/DemoVote/Create.vue` (1251 lines) | Discovery | ⏳ |
| 4 | `Elections/Voters/Index.vue` (1121 lines) | Discovery | ⏳ |

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: Election Management Analysis](20260613-election-management-analysis.md)
- [Discovery: PhaseService Assessment](20260613-phase-service-assessment.md)
