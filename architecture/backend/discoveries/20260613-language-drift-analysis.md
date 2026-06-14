# Discovery: Language Drift Analysis

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 4C)  
**Status:** Complete

## Action Language Comparison

| # | Action | Backend PHP Enum (`ElectionAction`) | Backend Constitution (`RULES`) | Frontend TS (`ElectionActions`) | Frontend TS (`StateMachineContract`) |
|---|--------|---|---|---|---|
| 1 | `submit_for_approval` | ✅ | ✅ | ✅ | ✅ |
| 2 | `approve` | ✅ | ✅ | ✅ | ✅ |
| 3 | `reject` | ✅ | ✅ | ✅ | ✅ |
| 4 | `auto_submit` | ✅ | ✅ | ✅ | ✅ |
| 5 | `begin_setup` | ❌ **Missing** | ✅ | ✅ | ✅ |
| 6 | `revise_and_resubmit` | ❌ **Missing** | ✅ | ✅ | ✅ |
| 7 | `complete_administration` | ✅ | ✅ | ✅ | ✅ |
| 8 | `complete_nomination` | ❌ **Missing** | ✅ | ✅ | ✅ |
| 9 | `apply_candidacy` | ❌ **Missing** | ✅ | ✅ | ✅ |
| 10 | `open_voting` | ✅ | ✅ | ✅ | ✅ |
| 11 | `close_voting` | ✅ | ✅ | ✅ | ✅ |
| 12 | `publish_results` | ✅ | ✅ | ✅ | ✅ |
| 13 | `archive` | ❌ **Missing** | ✅ | ✅ | ✅ |
| 14 | `suspend` | ✅ | ✅ | ✅ | ✅ |
| 15 | `resume` | ✅ | ✅ | ✅ | ✅ |
| **Total** | — | **10** | **15** | **15** | **15** |

## Analysis

### 5 actions missing from the PHP `ElectionAction` enum:

| Missing Action | Group | Active in Constitution? | Used in Frontend? | Impact |
|---------------|-------|------------------------|-------------------|--------|
| `begin_setup` | Setup | ✅ Yes, transitions `approved → setup_administration` | ✅ Yes | Cannot type-check this action at the PHP level |
| `revise_and_resubmit` | Approval | ✅ Yes, transitions `rejected → submitted_for_approval` | ✅ Yes | Cannot type-check. Resubmission path untyped. |
| `complete_nomination` | Setup | ✅ Yes, precondition `has_approved_candidates` | ✅ Yes | Cannot type-check nomination completion. |
| `apply_candidacy` | Nomination | ✅ Yes, allowed for voter/member roles | ✅ Yes | Capability check only — no state change, but still a constitutional action. |
| `archive` | Results | ✅ Yes, terminal transition `results_published → archived` | ✅ Yes | Cannot type-check archiving. |

### Direction of drift

```
USAGE → CONSTITUTION → FRONTEND (complete, 15 actions)
                    ↘ BACKEND ENUM (incomplete, 10 actions)
```

The Constitution and Frontend are fully aligned. The backend `ElectionAction` enum has not kept up as new actions were defined in the Constitution.

## Root Cause

`ElectionAction` enum was defined as a static list but not refreshed when `ElectionConstitution::RULES` gained new entries. The Constitution is the real source of truth — the enum should derive from it rather than duplicate it.

## Recommendation

Regenerate or expand the backend `ElectionAction` PHP enum to include the 5 missing actions. The Constitution's `RULES` keys are the ground truth:

```php
enum ElectionAction: string
{
    case SubmitForApproval = 'submit_for_approval';
    case AutoSubmit = 'auto_submit';
    case Approve = 'approve';
    case Reject = 'reject';
    case BeginSetup = 'begin_setup';
    case ReviseAndResubmit = 'revise_and_resubmit';
    case CompleteAdministration = 'complete_administration';
    case CompleteNomination = 'complete_nomination';
    case ApplyCandidacy = 'apply_candidacy';
    case OpenVoting = 'open_voting';
    case CloseVoting = 'close_voting';
    case PublishResults = 'publish_results';
    case Archive = 'archive';
    case Suspend = 'suspend';
    case Resume = 'resume';
}
```

## Conclusion

The language drift is small (5 missing enum cases) and easily corrected. No concept mismatches exist between backend and frontend — only a stale enum. The ubiquitous language is stable across all three layers once corrected.
