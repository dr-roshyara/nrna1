# Trust Domain Migration Backlog

**Status:** Active backlog  
**Date:** May 30, 2026  
**Scope:** Actionable items to strengthen trust architecture

---

## Overview

This backlog contains items to implement findings from Phase 1-3 domain analysis. All items are:
- **Actionable** (not architectural discussion)
- **Independent** (can be done in any order)
- **Concrete** (specific file/code changes)
- **Prioritized** (by business value and implementation dependency)

---

## Item T-001: Domain Events for Trust Attestation

**Priority:** High  
**Business Value:** High (enables governance to react to verification changes)  
**Effort:** Medium (6-8h)  
**Risk:** Low  
**Dependencies:** None  

**What:** Create domain events for verification lifecycle

**Acceptance Criteria:**
- [ ] Define event classes (IdentityAttested, VerificationRevoked, etc.)
- [ ] Emit events from VoterVerificationController
- [ ] Publish events to event bus
- [ ] Document event payloads
- [ ] Add event handlers (basic logging initially)

**Affected Files:**
- `app/Domain/Trust/Events/IdentityAttestedEvent.php` (create)
- `app/Domain/Trust/Events/VerificationRevokedEvent.php` (create)
- `app/Http/Controllers/Verification/VoterVerificationController.php` (update)
- `app/EventServiceProvider.php` (update)

**Rationale:** Governance cannot react to verification changes without events.

---

## Item T-002: Explicit Trust Levels

**Priority:** High  
**Business Value:** High (supports multi-org requirements)  
**Effort:** Large (12-16h)  
**Risk:** Medium (schema migration on live table)  
**Dependencies:** None  

**What:** Add trust_level column and transition logic to VoterVerification

**Acceptance Criteria:**
- [ ] Create migration: add trust_level enum column
- [ ] Create migration: backfill existing data (active=true → officer_verified)
- [ ] Update VoterVerification model with trust_level accessor
- [ ] Define allowed transitions (Officer Verified → Organization Verified, etc.)
- [ ] Update VoterVerificationPolicy to check trust levels
- [ ] Add trust level to verification controller UI/API

**Affected Files:**
- `database/migrations/2026_05_XX_add_trust_level_to_voter_verifications.php` (create)
- `app/Models/VoterVerification.php` (update)
- `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php` (update)
- `app/Http/Controllers/Verification/VoterVerificationController.php` (update)

**Rationale:** Different organizations require different assurance levels.

---

## Item T-003: Trust Policy Configuration

**Priority:** High  
**Business Value:** High (organizations control requirements)  
**Effort:** Large (14-18h)  
**Risk:** Medium (governance logic becomes configurable)  
**Dependencies:** T-002 (explicit trust levels)  

**What:** Create TrustPolicy model and enforcement

**Acceptance Criteria:**
- [ ] Create TrustPolicy model (org, process, min_trust_level, evidence_types, expiry)
- [ ] Create migration for TrustPolicy table
- [ ] Update VoterVerificationPolicy to check TrustPolicy
- [ ] Add admin UI to configure trust policies
- [ ] Document how organizations set requirements
- [ ] Add tests for policy enforcement

**Affected Files:**
- `app/Domain/Trust/ValueObjects/TrustPolicy.php` (create)
- `app/Models/TrustPolicy.php` (create)
- `database/migrations/2026_05_XX_create_trust_policies_table.php` (create)
- `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php` (update)
- `app/Http/Controllers/Admin/TrustPolicyController.php` (create)

**Rationale:** Governance should be configurable by organization policy, not hardcoded.

---

## Item T-004: Evidence Tracking

**Priority:** Medium  
**Business Value:** Medium (audit trail, quality assessment)  
**Effort:** Medium (8-10h)  
**Risk:** Low  
**Dependencies:** None  

**What:** Create VerificationEvidence model and tracking

**Acceptance Criteria:**
- [ ] Create VerificationEvidence model (type, source, quality, captured_at, expires_at)
- [ ] Create migration
- [ ] Update VoterVerificationController to create evidence records
- [ ] Add evidence_quality field to attestation form
- [ ] Document evidence types (officer_attestation, document, gov_id, address_proof)
- [ ] Add evidence list to officer UI

**Affected Files:**
- `app/Models/VerificationEvidence.php` (create)
- `database/migrations/2026_05_XX_create_verification_evidence_table.php` (create)
- `app/Http/Controllers/Verification/VoterVerificationController.php` (update)
- Views for verification form (update)

**Rationale:** Structured evidence enables trust level assignment and policy decisions.

---

## Item T-005: Verification Expiry and Re-verification

**Priority:** Medium  
**Business Value:** Medium (maintains ongoing trust)  
**Effort:** Large (16-20h)  
**Risk:** Medium (affects voting eligibility)  
**Dependencies:** T-002 (explicit trust levels)  

**What:** Add expiry mechanism and re-verification requirements

**Acceptance Criteria:**
- [ ] Add expires_at column to VoterVerification
- [ ] Create migration for existing records (null = never, or set to 24mo future?)
- [ ] Create VerificationExpiredEvent
- [ ] Create scheduled job to emit expiry events
- [ ] Update VoterVerificationPolicy to check expiry
- [ ] Add re-verification UI workflow
- [ ] Document re-verification requirements

**Affected Files:**
- `database/migrations/2026_05_XX_add_expires_at_to_voter_verifications.php` (create)
- `app/Domain/Trust/Events/VerificationExpiredEvent.php` (create)
- `app/Console/Commands/CheckVerificationExpiry.php` (create)
- `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php` (update)
- `app/Http/Controllers/Verification/VoterVerificationController.php` (update)

**Rationale:** Trust shouldn't be permanent; organizations may require periodic re-verification.

---

## Item T-006: Governance Notification on Revocation

**Priority:** Medium  
**Business Value:** Medium (governance can react)  
**Effort:** Medium (6-8h)  
**Risk:** Low  
**Dependencies:** T-001 (domain events)  

**What:** Create handler for VerificationRevokedEvent

**Acceptance Criteria:**
- [ ] Create VerificationRevokedHandler
- [ ] Handler logs revocation to election audit
- [ ] Handler queries affected votes
- [ ] Handler notifies governance dashboard
- [ ] Add tests for handler

**Affected Files:**
- `app/Listeners/VerificationRevokedHandler.php` (create)
- `app/EventServiceProvider.php` (update)
- Tests for handler (create)

**Rationale:** Governance needs awareness of revocations to decide consequences.

---

## Item T-007: Bootstrap Trust Documentation

**Priority:** Low  
**Business Value:** Low (clarifies architecture)  
**Effort:** Small (2-3h)  
**Risk:** None  
**Dependencies:** None  

**What:** Document how officer authority is bootstrapped

**Acceptance Criteria:**
- [ ] Create docs/architecture/trust-domain/BOOTSTRAP_TRUST.md
- [ ] Document officer role assignment flow
- [ ] Explain why officers don't require self-verification
- [ ] Add diagram showing trust flow
- [ ] Update UBIQUITOUS_LANGUAGE.md with bootstrap definition

**Affected Files:**
- `docs/architecture/trust-domain/BOOTSTRAP_TRUST.md` (create)
- `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md` (update)

**Rationale:** Architecture documentation prevents misunderstandings.

---

## Item T-008: Formal Authorization Formula

**Priority:** Low  
**Business Value:** Low (clarifies behavior)  
**Effort:** Small (3-4h)  
**Risk:** None  
**Dependencies:** None  

**What:** Implement and test the three-part authorization formula

**Acceptance Criteria:**
- [ ] Create AuthorizationService with explicit formula
- [ ] Service checks: Verified && Eligible && Permission
- [ ] Update controllers to use service
- [ ] Add tests covering all combinations
- [ ] Document in API docs

**Affected Files:**
- `app/Domain/Authorization/AuthorizationService.php` (create)
- `app/Http/Controllers/Vote/VoteController.php` (update)
- Tests (create)

**Rationale:** Makes implicit formula explicit and testable.

---

## Item T-009: Trust Transition Policy Formalization

**Priority:** Low  
**Business Value:** Low (prevents invalid state transitions)  
**Effort:** Small (4-5h)  
**Risk:** Low  
**Dependencies:** T-002 (explicit trust levels)  

**What:** Define and enforce valid trust level transitions

**Acceptance Criteria:**
- [ ] Create TrustTransitionPolicy class
- [ ] Define matrix: which transitions are allowed
- [ ] Reject invalid transitions
- [ ] Log all transitions
- [ ] Document policy matrix

**Affected Files:**
- `app/Domain/Trust/TrustTransitionPolicy.php` (create)
- `app/Models/VoterVerification.php` (update)
- Tests (create)

**Rationale:** Prevents accidental/invalid state transitions.

---

## Item T-010: Revocation Appeal Process

**Priority:** Low  
**Business Value:** Low (fairness/governance)  
**Effort:** Large (18-24h)  
**Risk:** Medium (governance/legal implications)  
**Dependencies:** T-001 (domain events)  

**What:** Implement formal appeal workflow for revoked verification

**Acceptance Criteria:**
- [ ] Create Appeal model
- [ ] Voter can submit appeal after revocation
- [ ] Officer can review appeal
- [ ] Officer can reverse revocation
- [ ] Appeal audit trail maintained
- [ ] Email notifications sent
- [ ] Add UI for appeal submission and review

**Affected Files:**
- `app/Models/VerificationAppeal.php` (create)
- `database/migrations/2026_05_XX_create_verification_appeals_table.php` (create)
- `app/Http/Controllers/Appeal/VerificationAppealController.php` (create)
- Views for appeal submission (create)
- `app/Listeners/VerificationRevokedHandler.php` (update)

**Rationale:** Fair governance requires appeal mechanism.

---

## Summary

| Item | Priority | Effort | Business Value | Dependencies |
|------|----------|--------|-----------------|---|
| T-001 | High | 6-8h | High | None |
| T-002 | High | 12-16h | High | None |
| T-003 | High | 14-18h | High | T-002 |
| T-004 | Medium | 8-10h | Medium | None |
| T-005 | Medium | 16-20h | Medium | T-002 |
| T-006 | Medium | 6-8h | Medium | T-001 |
| T-007 | Low | 2-3h | Low | None |
| T-008 | Low | 3-4h | Low | None |
| T-009 | Low | 4-5h | Low | T-002 |
| T-010 | Low | 18-24h | Low | T-001 |

**Total Effort:** 89-126 hours over 3-4 months

---

## Recommended Implementation Order

### Phase 1 (Foundation - Weeks 1-2)
1. T-001: Domain Events
2. T-002: Explicit Trust Levels
3. T-007: Bootstrap Documentation

### Phase 2 (Policy - Weeks 3-4)
4. T-003: Trust Policy Configuration
5. T-004: Evidence Tracking
6. T-006: Governance Notification

### Phase 3 (Lifecycle - Weeks 5-6)
7. T-005: Verification Expiry
8. T-008: Authorization Formula
9. T-009: Trust Transitions

### Phase 4 (Governance - Weeks 7-8)
10. T-010: Revocation Appeal Process

---

**Last Updated:** May 30, 2026  
**Related:** docs/architecture/trust-domain/ (all files)
