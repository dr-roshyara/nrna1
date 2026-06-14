# Round 36A-09 — C-02 Verification Representation Surface

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-09 (Conflict Resolution — Primary Conflict C-02)

**Authority:** Architecture Review Board

**Authorization:** Round 36A-08 APPROVED — "Round 36A-09 AUTHORIZED. Primary mandate: resolve C-02."

**Governance Foundation:**
- All Round 36A documents (36A-01 through 36A-08) — APPROVED, binding
- OBS-36A-05-1: Vote Recording ≠ Verification Representation (binding)
- OBS-36A-05-2: Verification Representation ≠ Verification Ownership (governing constraint)
- OBS-36A-08-1: Audit Context Attraction Risk (caution on Audit extension)
- Round 36 Evaluation Filter (binding): strengthen the discovered model; do not replace it

---

## Purpose

Round 36A-08 identified C-02 as the primary architectural hotspot of the entire Round 36A program:

```
Five rows in the ownership matrix depend on C-02:
  Individual Verifiability (Row 1)
  Recorded-as-Cast external proof (Row 2)
  Verification Representation Surface (Row 6)
  Independent Verification Observer / CAH-02 (Row 7)
  Receipt Validation (Row 10)

D42B formal resolution is blocked until C-02 resolves.
```

This document resolves C-02 by applying three DDD identity tests to the two credible candidates and committing to a recommended resolution.

---

## Section 1 — C-02 Restated

**Conflict C-02:**

```
Verification Representation Surface

Who publishes the privacy-preserving representation
of vote evidence that voters and observers use
to verify election integrity?

Candidate A: Audit context extended to own external publication
Candidate B: New dedicated Verification Representation context
Candidate C: Vote aggregate extended — PRE-RULED (OBS-36A-05-1)
```

**Why this is the right question:**

36A-08 established that Vote already owns the receipt mechanism (VO-3, `verifyByReceipt`). The receipt provides a token. What the token is validated against — the accessible, privacy-preserving representation — has no owner in the discovered model. Without that representation, individual verifiability is incomplete (36A-DI-02: receipt enables access; receipt alone does not complete verification).

The representation is not an optional feature. It is a structural requirement for the verifiability guarantee.

---

## Section 2 — What Is a Verification Representation Surface?

Derived from domain evidence and literature synthesis (Rounds 36A-01 through 36A-08):

```
Verification Representation Surface

A publicly accessible, privacy-preserving
transformation of vote evidence that:

  1. Allows a voter to validate their receipt hash
     against a record (Individual Verifiability)

  2. Allows an external observer to verify that
     the published record is internally consistent
     (supports CAH-02 / Independent Observer)

  3. Does NOT expose raw vote content
     (VO-1 compliance requirement)

  4. Is architecturally separate from the
     Vote aggregate's internal records
     (OBS-36A-05-1)
```

**What it is not:**

- It is not the Audit event log (different consumer, different form)
- It is not the Vote aggregate's internal state
- It is not a real-time tally (D39 scope)
- It is not the Governance Evidence Replay record (different purpose)

---

## Section 3 — Domain Evidence

| Evidence | Source | Relevance to C-02 |
|----------|--------|-------------------|
| `verifyByReceipt` mechanism | BaseVote, Round 24A | Vote issues receipt; something must hold the accessible record |
| VO-3 receipt hash | Round 33 | Receipt hash exists; what it verifies against is undiscovered |
| Audit receives all domain events | Round 34C | Audit has the raw material; does it have the transformation responsibility? |
| Audit is fire-and-forget observer | Round 34C, OBS-34C pattern | Aggregates don't know Audit exists; Audit does not influence domain |
| GovernanceReplayService | Round 24A | Internal-only replay; not an external representation |
| VoteRecorded (anonymous) | Round 34A | The foundational event; raw form is already VO-1 compliant |
| Universal Verifiability gap | Round 24A, Round 36A-01 | "Infrastructure exists but internal — no external party can verify" |

**Key domain gap:** The domain has receipt issuance and internal event recording. It has no mechanism that makes evidence externally accessible in a form suitable for voter and observer verification. That is the C-02 gap.

---

## Section 4 — Literature Evidence

All four reviewed systems separate internal vote recording from an external verification representation:

| System | Internal Recording | External Representation | Transformation Layer |
|--------|-------------------|------------------------|---------------------|
| ElectionGuard | Encrypted ballot in election record | Published cast/challenge ballot record | Encrypted ballots (not decrypted choices) |
| Helios | Encrypted ballot in database | Bulletin board (encrypted ballots + proofs) | Homomorphic ciphertext aggregation |
| Scantegrity | Paper ballot + optical scan | Public web bulletin board (confirmation codes only) | Invisible-ink confirmation codes |
| Prêt à Voter | Original submitted receipt | Bulletin board (shuffled, re-encrypted receipts) | Mixnet transformation |

**CDI-04 (three-family confirmed pattern):** The separation between internal recording and external accessible representation is consistent across all families. The transformation layer is always present. The transformation is always privacy-preserving.

**The architectural insight:** In every reviewed system, the transformation is not performed by the voting/recording mechanism itself. It is performed by a separate layer — one whose primary purpose is the transformation and publication of verifiable evidence. Whether that layer is:

- The bulletin board server (Helios, Scantegrity, Prêt à Voter)
- The election record publication system (ElectionGuard)

it is architecturally distinct from the recording mechanism.

---

## Section 5 — Candidate A: Audit Context Extension

**Proposal:** Extend the existing Audit context to own external verification publication. Audit already receives all domain events. Its scope would expand to include:

- Transforming VoteRecorded events into a privacy-preserving accessible representation
- Publishing the representation for external voter and observer access
- Owning the receipts-to-representation linkage

**Genuine advantages:**
- Audit already has access to VoteRecorded and all other events
- No new bounded context boundary to cross
- Lower integration cost
- Audit's event accumulation is the natural raw material

**Evaluation under OBS-36A-08-1 (Audit Gravity Well warning):**

If Audit is extended to own publication, its responsibilities become:

```
Audit (current):
  Observation of all domain events (fire-and-forget)
  Immutable event log for governance replay
  Evidence preservation

Audit (extended):
  Observation                               (current)
  Immutable event log                       (current)
  Privacy-preserving transformation logic   (NEW)
  External publication surface              (NEW)
  Voter-facing receipt validation anchor    (NEW)
  Observer-facing verification surface      (NEW)
```

The current Audit is a passive accumulator. The extended Audit is a passive accumulator AND an active publisher. These are different architectural identities.

**The DDD identity tests for Candidate A are evaluated in Section 8.**

---

## Section 6 — Candidate B: New Verification Representation Context

**Proposal:** Create a dedicated Verification Representation bounded context whose primary and sole purpose is receiving transformed vote evidence and publishing a privacy-preserving accessible representation.

**The context would own:**
- Subscribing to VoteRecorded events (event-driven consumption from Vote → Audit → Representation)
- Producing a privacy-preserving representation (the transformation layer)
- Serving voter-facing receipt validation queries
- Serving external observer access (CDI-04 surface)

**Genuine advantages:**
- Clean bounded context separation
- Audit's identity as passive observer is preserved
- Vote's identity as anonymous recorder is preserved
- The new context's language ("accessible verification record," "receipt validation anchor," "observer surface") is distinct from both Audit and Vote language

**Genuine costs:**
- Additional bounded context boundary
- Additional integration path
- Additional design work required

**The DDD identity tests for Candidate B are evaluated in Section 8.**

---

## Section 7 — Candidate C: Vote Aggregate Extension (Pre-Ruled)

**Status: INADMISSIBLE**

OBS-36A-05-1 (binding, established Round 36A-05) states:

```
Vote Recording
    ≠
Verification Representation
```

All four reviewed systems separate these. Extending Vote to own the representation surface would require Vote to:
- Own transformation logic (not recording logic)
- Publish externally (breaking its current internal-only character)
- Serve voter and observer queries (external API responsibility)

This would make Vote both the anonymous recorder AND the external publisher of what it recorded. That conflation is what every reviewed system explicitly avoids.

**Candidate C is rejected. This section exists only to make the rejection explicit and formal.**

---

## Section 8 — Ownership Evaluation Matrix (Three DDD Identity Tests)

### Test 1 — Ubiquitous Language Test

Does the language of verification publication belong in the same bounded context as the language of audit observation?

| Language Domain | Audit Context | Verification Representation |
|----------------|---------------|---------------------------|
| Actors | Governance replay, legitimacy certification | Voters, independent observers |
| Primary operations | "Observe," "Record," "Replay," "Detect divergence" | "Publish," "Validate receipt," "Expose accessible record" |
| Outputs | Internal evidence log, replay certification | Accessible representation, receipt validation anchor |
| Time horizon | Permanent historical record | Election-period accessible record |
| Consumers | GovernanceReplayService, legitimacy functions | Voters, external observers |

**Assessment:** These read as different language families. A team that talks about "audit observation" and "replay divergence detection" is not the same team that talks about "voter receipt validation anchors" and "observer-accessible verification surfaces." The concepts are related but belong to different languages.

**Test 1 result: DIFFERENT LANGUAGE. Points toward Candidate B (new context).**

---

### Test 2 — Consumer Divergence Test

Do the consumers of this evidence have structurally different needs from the same raw evidence?

| Consumer | What they need | Form required |
|----------|---------------|--------------|
| GovernanceReplayService (internal) | Full event history, causal ordering, evidence integrity proofs | Rich ordered event stream with provenance |
| Voter (external) | "Is my receipt hash in the published record?" | Simple lookup: hash → confirmation |
| Independent Observer (external) | "Is the overall published record consistent?" | Accessible, queryable representation of all recordings |

Internal governance needs the full, richly-ordered, causally-coherent event stream. External voters need a specific voter-centric lookup. External observers need a queryable aggregate representation.

When the same raw evidence must serve structurally different consumer needs, a transformation layer between the internal record and the external consumers is the standard DDD response — not extending the internal context outward to serve both.

**Test 2 result: CONSUMER DIVERGENCE CONFIRMED. Points toward Candidate B (transformation context between internal Audit and external consumers).**

---

### Test 3 — Publication vs. Observation Identity Test

Round 34C characterized Audit as a passive fire-and-forget observer:

```
Aggregates fire events → Audit observes
Audit does not influence domain decisions
Aggregates do not know Audit exists
```

If Audit is extended to own external publication:

```
Voters depend on Audit for receipt validation
Observers depend on Audit for verification access
Audit becomes load-bearing for external assurance
```

This changes Audit's constitutional role from:

```
Passive observer
(aggregates unaware of Audit's existence)
```

to:

```
Active publisher
(voters and observers depend on Audit's publication)
```

In DDD terms, an aggregate that fires events into a fire-and-forget observer is not coupled to that observer. If the observer becomes an external-facing publisher that voters depend on, the architecture gains a new load-bearing dependency. That is not a scope extension. That is an identity change.

**Round 34C's fire-and-forget characterization was a constitutional determination, not an incidental observation.** It means Audit cannot break from this pattern without explicit architectural justification that extends beyond scope expansion.

**Test 3 result: IDENTITY CHANGE CONFIRMED. Extending Audit changes its constitutional role. Points toward Candidate B (new context preserves Audit's observer identity).**

---

### Test Summary

| Test | Candidate A (Audit Extension) | Candidate B (New Context) |
|------|-------------------------------|--------------------------|
| Ubiquitous Language | FAIL — different language families | PASS — distinct language |
| Consumer Divergence | FAIL — same context must serve structurally divergent consumers | PASS — transformation context between divergent consumers |
| Publication vs. Observation Identity | FAIL — identity change (passive observer → active publisher) | PASS — preserves Audit's observer identity |

**All three tests point to Candidate B.**

---

## Section 9 — DDD Boundary Analysis

### What Would the New Context Own?

```
Verification Representation Context

Primary responsibility:
  Receive privacy-preserving evidence of vote recording
  Publish it in an accessible form for voters and observers

Specific responsibilities:
  - Consume VoteRecorded events (anonymous — VO-1 compliant)
  - Produce a privacy-preserving accessible representation
  - Serve voter receipt validation queries
  - Serve external observer access surface (CDI-04 pattern)
  - Own the receipt hash → accessible record linkage

What it does NOT own:
  - Vote recording (Vote aggregate owns this)
  - Audit event history (Audit context owns this)
  - Governance state transitions (GovernanceState owns this)
  - Tally computation (D39 scope — Results/Tallying)
  - Trust attestation (Verification aggregate owns this)
```

### Ubiquitous Language of the New Context

```
Accessible verification record
Privacy-preserving representation
Receipt validation anchor
Observer surface
Published evidence
Verification record accessibility
```

These concepts do not belong naturally in Audit's language or Vote's language. They constitute a distinct language — the language of evidence publication for external verification.

### Is the New Context Large Enough to Justify the Boundary Cost?

The context has a single, clearly bounded responsibility: transform anonymous vote recording evidence into an accessible verification record. This is not a large context. But the boundary cost is justified because:

1. The identity tests show Audit cannot absorb this without changing its constitutional character
2. VO-1 requires the transformation to be designed carefully — that design belongs to a dedicated owner
3. CAH-02 (Independent Verification Observer) has a clean integration point: observers read from the Verification Representation context, not from Audit
4. The context's responsibilities are stable and bounded — it is unlikely to grow into a God Context

### How Evidence Flows to This Context

```
Vote aggregate
    ↓ VoteRecorded (anonymous — no voter identity, VO-1 compliant)
    ↓ [fire-and-forget, via Audit or direct event]

Verification Representation Context
    ↓ Transform: VoteRecorded → accessible receipt record
    ↓ Publish: privacy-preserving representation

Voter
    → validates receipt hash against published record

Independent Observer (CAH-02)
    → reads published representation
    → verifies internal consistency
```

Audit may be the relay (Audit already receives VoteRecorded events; the Verification Representation Context could subscribe to Audit's output rather than directly to Vote events). Alternatively, the Verification Representation Context could directly observe VoteRecorded via the same fire-and-forget pattern. Either is compatible with the boundary decision.

### VO-1 as a Binding Constraint on the New Context

The Verification Representation Context must:
- Never store or publish voter identity
- Never allow a receipt hash to be reverse-linked to a voter
- Publish only transformations (not raw Vote content)

VO-1 applies as a constitutional constraint on the new context's design. This is the same constraint Prêt à Voter handles via mixnet transformation and Scantegrity handles via anonymous confirmation codes. The mechanism appropriate for NRNA's digital context is a design question for the new context's ADR — it is not answered here.

---

### Decision Ownership Test (OBS-36A-09-1)

OBS-36A-09-1 (Senior DDD Architect review) requires one additional test before the "bounded context" classification can stand. The three DDD identity tests prove a context boundary exists. They do not prove the implementation form is a full bounded context rather than a projection, read model, or open host service.

```
Decision Ownership Test

Does this capability own:
  — Decisions (choosing among outcomes based on business rules)?
  — Invariants (rules that must not be violated)?
  — Policies (how something must be done)?
  — Business rules (domain logic)?
```

**Assessment:**

| Criterion | Finding | Held by Capability? |
|-----------|---------|-------------------|
| Decisions | Transformation is deterministic — every VoteRecorded produces exactly one accessible record entry. No outcome is chosen; the result is determined by rule. | NO |
| Invariants | Completeness: every VoteRecorded must appear in the accessible record. Privacy: no accessible record may expose voter identity (VO-1 in publication). Immutability: published records are append-only. | YES |
| Policies | Transformation policy: how VoteRecorded is converted into a privacy-preserving representation. Publication lifecycle: when the representation becomes accessible. | YES |
| Business rules | "Every recorded vote must appear in the accessible representation" — constitutional enforcement rule. Violation means individual verifiability is broken for that election. | YES (constitutional enforcement) |

**Decision Ownership Test result:**

```
DECISION:  NO  — deterministic, no business outcome choices
INVARIANT: YES — completeness, privacy, immutability
POLICY:    YES — transformation and publication lifecycle
RULE:      YES — constitutional enforcement of VO-1 at publication
```

**DDD classification consequence:**

The capability does not make business decisions. It does own invariants, policies, and constitutional enforcement rules. In DDD terms this is neither a pure projection (projections own no invariants and can be rebuilt at any time from the event stream) nor a rich decision-making context.

The correct classification is:

```
Constitutional Enforcement Context

A bounded context whose primary responsibility
is enforcing constitutional constraints
(completeness, VO-1 privacy, immutability)
in the form of a publicly accessible
verification surface.

Scope: narrow and stable.
Decision-making: none.
Invariant ownership: yes (constitutional).
```

Why this is NOT a pure projection: projections have no invariant ownership. This capability must guarantee that published receipt records cannot be retracted mid-election and that every VoteRecorded event produces an entry. A projection that fails to update can be rebuilt; a missing receipt record mid-election is a constitutional violation with no recovery path. The immutability and completeness guarantees distinguish it from a projection.

Why this does NOT change the C-02 resolution: the implementation form is more precisely characterized. The boundary decision (Candidate B) remains correct. The ADR must specify the constitutional enforcement scope explicitly.

---

## Section 10 — Impact on D42B

With C-02 resolved to Candidate B:

```
D42B — Boundary Scope of Vote Aggregate

Question: Is Vote's boundary correct for verifiability?

Answer (post-C-02 resolution):

  Vote owns:
    Individual Verifiability mechanism (receipt, verifyByReceipt)
    Recorded-as-Cast (VoteRecorded, anonymous)
    Participation Proof (proveParticipation)
    VO-1 anonymity constraint

  Vote does NOT own:
    External verification representation (→ new context owns this)
    Tally verification (→ D39, Results/Tallying)
    Cast-as-Intended verification (→ constitutional determination pending)

  Vote's boundary is correct for its current responsibilities.

  The D42B gap is not a Vote boundary problem.
  It is an absence of the Verification Representation Context.

  D42B resolution:

    "Vote aggregate boundary is CONFIRMED CORRECT.
     The verifiability gap is filled by introducing
     a Verification Representation bounded context
     that owns the external publication surface."
```

This is the formal D42B candidate resolution that Round 36A-08 assessed at ~85% probable. C-02 resolution confirms it.

**D42B STATUS: CANDIDATE RESOLUTION CONFIRMED. Ready for formal ADR.**

---

## Section 11 — Recommended Resolution

```
C-02 — Verification Representation Surface

RECOMMENDED RESOLUTION:

  Candidate B

  Introduce a new bounded context:

  Verification Representation Context
  (Constitutional Enforcement scope — OBS-36A-09-1 applied)

Rationale — three DDD identity tests:

  Test 1 — Ubiquitous Language:
    The language of verification publication is distinct
    from the language of audit observation.
    They belong to different teams with different concerns.

  Test 2 — Consumer Divergence:
    Internal governance (replay, legitimacy) and external
    parties (voters, observers) require structurally
    different forms of the same evidence.
    A transformation context between them is the correct
    DDD response.

  Test 3 — Publication vs. Observation Identity:
    Extending Audit to own external publication would
    change its constitutional role from passive observer
    to active publisher — an identity change, not a
    scope extension. The Round 34C fire-and-forget
    characterization prohibits this without separate
    architectural justification.

  VO-1 compliance:
    The transformation responsibility belongs to a
    dedicated context precisely because VO-1 requires
    it to be designed carefully. A context whose sole
    purpose is privacy-preserving publication is the
    most defensible VO-1 custodian for this surface.

Rationale — Decision Ownership Test (OBS-36A-09-1):

  The capability owns: invariants (completeness, privacy,
  immutability), policies (transformation, publication
  lifecycle), and constitutional enforcement rules.

  The capability does NOT make domain decisions.

  Classification: Constitutional Enforcement Context
  (narrow scope, policy-driven, no decision-making).

  The context status is justified.
  The implementation form is more precisely bounded
  than the initial "new context" framing implied.

  ADR-Candidate-01 must specify: this is a
  constitutional enforcement scope, not a
  decision-making scope.

Candidate A (Audit Extension) is rejected:
  Identity change without justification.
  All three DDD tests point away from it.

Candidate C (Vote Extension) is rejected:
  Pre-ruled by OBS-36A-05-1.
```

---

## Section 12 — ADR Candidates

Two ADR candidates emerge from this resolution:

---

### ADR-Candidate-01: Verification Representation Context Boundary

**Trigger:** C-02 resolution (this document)

**Decision:** Introduce the Verification Representation bounded context as a distinct context responsible for the external verification representation surface.

**Content (for ADR author):**
- Context name and primary responsibility
- VO-1 compliance obligation
- Relationship to Vote (receives VoteRecorded events, fire-and-forget)
- Relationship to Audit (Audit remains passive observer; Verification Representation is a distinct context)
- Relationship to CAH-02 (Independent Verification Observer reads from this context's output)
- Ubiquitous language register

**Governance gate:** Must resolve what mechanism produces the privacy-preserving representation (receipt hash lookup? commitment scheme? something else?). This is the context's primary design question and must be addressed in the ADR.

**OBS-36A-09-1 constraint on ADR-Candidate-01:** The ADR must explicitly characterize this as a Constitutional Enforcement Context — policy-driven, invariant-owning, non-decision-making. "Bounded context" is correct; "rich domain context" would overstate it. The scope is narrow by design.

---

### ADR-Candidate-02: D42B — Vote Aggregate Verifiability Boundary Formal Resolution

**Trigger:** C-02 resolution confirms D42B candidate resolution

**Decision:** Vote aggregate boundary is confirmed correct. D42B is closed by the introduction of the Verification Representation Context (not by extending Vote).

**Content (for ADR author):**
- Vote's confirmed scope: receipt mechanism, verifyByReceipt, proveParticipation, VoteRecorded, VO-1
- What Vote does not own: external representation surface, tally verification
- How D42B is formally closed: by adjacent context introduction, not Vote boundary change
- Remaining open question: Cast-as-Intended (C-03 — constitutional determination pending; does NOT block D42B closure)

---

## What This Document Does NOT Resolve

- Cast-as-Intended (C-03): constitutional question; ARB must determine if required
- Tallied-as-Recorded (C-01): D39 BLOCKED
- CAH-02 owner inside NRNA (C-05): the new context clarifies the integration point, but CAH-02 classification as internal NRNA responsibility vs. external actor is still open
- Audit's exact scope post-C-02: Audit's observer role is preserved; its precise boundary relative to the new context requires the ADR

---

## ARB Review

```
Round 36A-09 — C-02 Verification Representation Surface

APPROVED WITH ONE MAJOR OBSERVATION

DDD Quality:                 A
Governance Discipline:       A+
Research Discipline:         A
Voting Architecture Quality: B+
Confidence:                  High

Corrections applied:

OBS-36A-09-1 — Decision Ownership Test Applied

  The three DDD identity tests correctly prove
  a new context boundary is warranted.

  OBS-36A-09-1 required an additional test:
  does the capability own decisions, invariants,
  policies, and business rules?

  Result of Decision Ownership Test:
    DECISION:  NO
    INVARIANT: YES (completeness, privacy, immutability)
    POLICY:    YES (transformation, publication lifecycle)
    RULE:      YES (constitutional enforcement of VO-1)

  Classification refined:
    Constitutional Enforcement Context
    (not a rich decision-making context;
     not a pure projection/read model)

  The context boundary is justified.
  The implementation form is now correctly specified.

Key findings accepted:

  C-02 resolves to Candidate B.
  Candidate A (Audit Extension) rejected —
    identity change without justification.
  Candidate C (Vote Extension) rejected —
    pre-ruled by OBS-36A-05-1.

  D42B CONFIRMED:
    Vote aggregate boundary is correct.
    D42B closes by introduction of the
    Verification Representation Context,
    not by Vote boundary change.

  C-03 (Cast-as-Intended) correctly deferred —
    constitutional question, not an
    architectural gap requiring remediation.

Next authorised steps:

  ADR-Candidate-01:
    Verification Representation Context
    (Constitutional Enforcement scope —
     OBS-36A-09-1 must be referenced in ADR)

  ADR-Candidate-02:
    D42B formal closure

  Then: Round 36B (Auditability Research)
```
