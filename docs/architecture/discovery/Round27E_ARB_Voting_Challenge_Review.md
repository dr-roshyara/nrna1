# Round 27E — ARB Voting Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Ready for ARB Decision

---

## Purpose

Challenge all aggregate candidates discovered in Voting context. Apply the same decision ownership → consistency boundary → aggregate discipline used in the Governance challenge reviews.

---

## Q1: What Invariants Require Vote to Be an Aggregate?

### Invariant Evidence Matrix

| Invariant | Evidenced? | Enforced? | Transactional? | Requires Aggregate? |
|-----------|-----------|-----------|----------------|---------------------|
| No user_id (anonymity) | ✅ ADR_20260203: "The vote itself must NOT contain user_id" | ✅ Database schema has no user_id column; save_vote() does not accept user_id | ✅ Enforced at schema level — not transactionally for each write, but structurally impossible to violate | **Required** — anonymity is a structural invariant of the vote record itself, not a cross-record constraint |
| vote_hash uniqueness | ✅ vote_hash is unique constraint on votes table | ✅ Database unique constraint | ✅ Transactionally enforced on every insert | **Required** — uniqueness requires transactional consistency at write time |
| data_checksum integrity | ✅ calculateChecksum() covers candidate data + app.key | ⚠️ Checksum is set at creation time (creating hook) but not verified on every read | ✅ Checksum is computed and stored atomically with vote creation | **Required** — checksum must be set atomically with vote data |
| receipt_hash verification | ✅ verifyByReceipt() compares hashed receipt against stored hash | YES — compare function exists | ✅ Receipt hash is set at vote creation time | **Required** — receipt hash is part of the vote record, set atomically |
| participation_proof integrity | ✅ proveParticipation() confirms voter participation | YES — compare function exists | ✅ Proof is set at vote creation | **Required** — proof attribute is part of the vote record |
| Vote belongs to an election | ✅ election_id FK constraint | ✅ Database FK with cascade delete | ✅ Enforced on every write | **Required** — part of vote creation in same transaction |

### Assessment

All 6 invariants require the same consistency boundary: vote creation must be atomic with its attributes, checksum, receipt, proof, election attribution, and uniqueness constraint. There is no scenario where these invariants can be safely split across separate aggregates.

**Result: Vote is a genuine aggregate.** HIGH confidence. Transactional consistency is required across all vote creation attributes.

---

## Q2: What Invariants Require ElectionEnrollment to Be an Aggregate?

### Invariant Evidence Matrix

| Invariant | Evidenced? | Enforced? | Transactional? | Requires Aggregate? |
|-----------|-----------|-----------|----------------|---------------------|
| One enrollment per election per participant | ElectionMembership model with role=voter, election_id, user_id | ⚠️ No unique composite constraint observed (similar to RoleAssignment issue in 27C) | ❌ Not transactionally enforced | **Not proven** — may be application-level convention |
| Step progression is sequential (1→2→3→4→5) | VoterSlugStep has step fields; code entry → agreement → selection → verification → completion | ⚠️ Steps are tracked as separate fields/records but no guard against skipping or reordering in observed code | ❌ No observed transactional enforcement | **Not proven** — may be UI constraint, not aggregate invariant |
| Enrollment status matches step completion | ElectionMembership status + VoterSlugStep fields | ⚠️ Fields exist but consistency not observed | ❌ Not observed | **Not proven** |
| HasVoted is set atomically with vote completion | VoteController marks hasVoted = true after vote submission | ⚠️ Observed in flow but transactional boundary unclear | ⚠️ May be part of vote transaction or separate | **Partially evidenced** |

### Assessment

Of 4 claimed invariants:
- **0 confirmed** with transactional enforcement
- **1 partially evidenced** (hasVoted may be set transactionally with vote)
- **3 unproven** (uniqueness, step ordering, status consistency)

This is similar to the RoleAssignment situation in 27C-ARB — the concept exists, it has state and lifecycle, but the invariants that would require an aggregate boundary are not evidenced with transactional enforcement. ElectionEnrollment may be an entity within a larger aggregate (Voting, or a participant-level aggregate).

**Boundary Stress Test:** If ElectionEnrollment is not an aggregate, what breaks?
- Step progression could jump from step 1 to step 5 (no observed guard)
- Multiple enrollments for same election could exist (no observed unique constraint)
- Enrollment status could become inconsistent with step completion (no observed enforcement)

These are real risks, but they are risks of convention, not risks of invariant violation. The system does not currently enforce these transactionally. An aggregate would add enforcement, but the aggregate necessity test is "does the invariant exist and require transactional protection?" — not "would an aggregate improve the design?"

**Result: NOT YET PROVEN as an aggregate.** ElectionEnrollment has state and lifecycle but the invariants requiring an aggregate boundary are not evidenced with transactional enforcement. MEDIUM-LOW confidence.

---

## Q3: Can Ballot Exist Independently of Vote?

### Analysis

| Test | Assessment |
|------|------------|
| Ballot exists before vote submission | ✅ Yes — ballot is built in session before submission (VoteController: session stores selections before vote is created) |
| Ballot has validation rules | ✅ Ballot content is validated before submission (required_number per post, valid candidates) |
| Ballot content is the source of truth for results | ✅ createResultsFromCandidates() reads ballot JSON from vote columns to create Result records |
| Ballot lifecycle independent of Vote | ❌ Ballot content is stored as candidate_XX columns on the Vote record. The ballot does not have its own table or record. It is only persisted as part of the Vote. |
| Ballot can change independently of Vote | ❌ Once the Vote is created, ballot content cannot be modified (checksum would detect changes) |

### Classification Alternatives

| Alternative | Evidence | Verdict |
|-------------|----------|---------|
| **Value Object** | Ballot is the content of the Vote. It is built before vote creation, validated, and then stored as part of the Vote. It has no independent persistence or lifecycle. | STRONG |
| **Entity** | Ballot could be an entity if it has its own identity and lifecycle separate from Vote. Evidence does not support this — ballot content is embedded in Vote columns. | WEAK |
| **Aggregate** | Ballot has no invariants requiring its own consistency boundary. All ballot-related invariants (valid selections, required_number) are enforced by VoteController before Vote creation. | VERY WEAK |

**Alternative considered: Ballot as Process Entity.** During the voting workflow, the ballot exists in session before vote creation. It is built, reviewed, verified, and then submitted. This is a transient existence — the ballot does not become a persistent domain object until it is cast as a Vote. The session-based existence does not warrant aggregate or entity status.

**Alternative considered: Ballot as Aggregate.** A Ballot aggregate would own the decision "what selections did the voter make?" But this decision is not independently meaningful — it exists only as input to the Vote. The Vote owns the decision "was this vote validly cast?" which encompasses ballot content.

**Result: Value Object.** Ballot is the content of the Vote. It exists independently before vote creation (in session) but has no persistent identity or lifecycle. Once cast, the ballot content is stored as attributes of the Vote aggregate.

---

## Q4: Consistency Boundaries (Stress Test)

### Scenario A: Vote is NOT an Aggregate

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Vote anonymity | ✅ Yes | Could accidentally be stored with user_id if no aggregate boundary enforces the no-user_id invariant |
| vote_hash uniqueness | ✅ Yes | Two votes could be created with same hash without transactional enforcement |
| Checksum matches vote data | ✅ Yes | Vote data could be updated without checksum recalculation |
| Receipt hash matches vote | ✅ Yes | Vote could be created without receipt |

**Result: 4 invariants break.** Vote clearly requires an aggregate boundary.

### Scenario B: ElectionEnrollment is NOT an Aggregate

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Step progression sequential | ⚠️ Possible | Steps are tracked but no transactional enforcement observed |
| One enrollment per election per participant | ⚠️ Possible | No unique constraint observed |
| Enrollment status consistent with steps | ⚠️ Possible | Fields exist but consistency not enforced |

**Result: 0 invariants definitively break.** All three are "possible" rather than "certain." This does not mean Enrollment is never an aggregate — it means the existing evidence does not prove transactional invariant requirements. This is consistent with the 27C-ARB finding for RoleAssignment.

### Scenario C: Ballot is NOT an Aggregate

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Ballot content validation | ❌ No | Validation happens before vote creation, not within a ballot aggregate |
| Ballot content integrity | ❌ No | Once stored in Vote, Vote aggregate's checksum protects integrity |
| Ballot-to-Vote mapping | ❌ No | Ballot is embedded in Vote — no separate mapping to break |

**Result: 0 invariants break.** Ballot as a standalone aggregate is not justified by consistency requirements.

---

## 5. Revised Aggregate Inventory

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **Vote** | **Aggregate** | **HIGH** | 6 invariants requiring transactional consistency; strong evidence; explicit design mandate |
| **ElectionEnrollment** | **Entity (within Voting or within a Participant aggregate, not a standalone aggregate)** | **MEDIUM-LOW** | Has state and lifecycle but invariants requiring aggregate boundary are not evidenced with transactional enforcement. Consistent with RoleAssignment pattern from 27C-ARB — may be an entity within a larger aggregate. |
| Ballot | Value Object | HIGH | Input to Vote creation; stored as Vote attributes; no independent lifecycle |
| Receipt | Value Object | HIGH | Attribute of Vote |
| ParticipationProof | Value Object | HIGH | Attribute of Vote |
| VoteIntegrityChecksum | Value Object | HIGH | Attribute of Vote |
| DeviceFingerprint | Value Object | HIGH | Attribute of Vote |

---

## 6. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADV-1 | Should Vote creation be transactionally consistent with Result creation? Currently synchronous. Depends on D39. | MEDIUM |
| ADV-2 | Is ElectionEnrollment truly an entity, or does future enforcement of step-ordering and uniqueness justify aggregate status? Current evidence suggests entity, but design intent unknown. | MEDIUM |
| ADV-3 | Does the Ballot's session-based existence before Vote creation warrant any domain modeling consideration (e.g., a "BallotInProgress" concept), or is this purely an application-layer concern? | LOW |

---

## 7. Summary

| Aggregate Candidate | Previous Confidence | Revised Confidence | Status |
|-------------------|-------------------|-------------------|--------|
| **Vote** | HIGH | **HIGH** (unchanged) | ✅ Confirmed |
| **ElectionEnrollment** | MEDIUM | **MEDIUM-LOW** | ⚠️ Downgraded — entity candidate |
| Ballot (VO) | HIGH | HIGH | ✅ Confirmed |
| Receipt (VO) | HIGH | HIGH | ✅ Confirmed |
| Checksum (VO) | HIGH | HIGH | ✅ Confirmed |

---

**Round 27E ARB Voting Challenge Review — READY FOR ARB DECISION**

**Vote aggregate confirmed (HIGH). ElectionEnrollment downgraded from MEDIUM to MEDIUM-LOW — entity candidate, not proven aggregate. Ballot confirmed as Value Object. 3 aggregate discovery debt items. Same challenge discipline applied as Governance reviews.**
