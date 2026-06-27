# Round 36A-08 — Ownership Candidate Matrix

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-08 (Ownership Candidate Matrix — synthesis step)

**Authority:** Architecture Review Board

**Authorization:** Option C (ARB, 2026-06-13) — Literature Saturation Rule satisfied; ownership analysis is the higher-value next step.

**Governance Foundation:**
- Round 36A-01 Domain Verifiability Baseline (APPROVED)
- Round 36A-02 Verifiability Concept Catalog (APPROVED)
- Round 36A-03 ElectionGuard Literature Review (APPROVED)
- Round 36A-04 Helios/Benaloh Literature Review (APPROVED)
- Round 36A-04B Helios Chapter 11 Extended Analysis (APPROVED)
- Round 36A-05 Scantegrity/Prêt à Voter Literature Review (APPROVED)
- Round 36A-06 Risk Limiting Audits Literature Review (APPROVED)
- Round 36A Literature Saturation Rule — SATISFIED (three independent families)
- Round 36 Evaluation Filter (binding): "Does this strengthen the discovered model, or attempt to replace it?"

---

## Purpose

Round 36A-01 through 36A-06 answered:

> *What assurance patterns exist in mature verifiable election systems?*

This document answers:

> *Who owns those assurances inside NRNA?*

The output is a structured ownership candidate matrix. It assigns each verifiability concept to the most credible candidate owner(s) within the discovered domain model. Where ownership is contested or blocked, the conflict is named explicitly.

This document does NOT resolve conflicts. It names them so Round 36A-09 and the ARB can resolve them from a position of structured evidence.

---

## Option C Confidence Labels (Binding)

Per ARB Option C authorization, confidence labels from Round 36A must be carried honestly into this analysis:

| Item | Status | Meaning for 36A-08 |
|------|--------|-------------------|
| 36A-DI-01 | CONFIRMED | Carry as established fact |
| 36A-DI-02 | CONFIRMED | Carry as established fact |
| 36A-DI-03 | CONFIRMED | Carry as established fact |
| 36A-DI-05 | CONFIRMED | Carry as established fact |
| 36A-DI-04 | CANDIDATE | Ownership analysis may refine further |
| CAH-02 | CANDIDATE | Literature proves pattern; ownership not yet assigned |
| CDI-04 | CDI (three-family) | Pattern confirmed; architectural form remains open |
| CDI-06 | CDI (single-source) | Single Helios source; consistent with GovernanceState evidence |

---

## Governing Principle

```
OBS-36A-05-2 (binding constraint for all rows in this matrix)

Verification Representation
        ≠
Verification Ownership

All four reviewed systems demonstrate
that something is published for verification.

None of them answer: Who owns verification?

For each concept below, the matrix must answer
TWO distinct questions, not one:

  Question A: Who owns the CONCEPT (the assurance itself)?
  Question B: Who owns the REPRESENTATION (what is published)?

These may be the same aggregate. They may not be.
Collapsing them without evidence is a governance error.
```

Every matrix row carries an OBS-36A-05-2 check: does the candidate own the concept, the representation, or both? The distinction must be explicit.

---

## Naming Constraint — Critical

**ARB Status: PERMANENT ARCHITECTURE CONSTRAINT CANDIDATE** — ARB has directed this be elevated from a note to a formal candidate constraint. Mixing Trust Attestation and Election Verifiability into a single context would create a future disaster. This constraint must survive into all subsequent rounds.

```
NRNA has a "Verification" aggregate.

That aggregate owns Trust Attestation:
  VerificationGranted
  VerificationRevoked
  RequestVerification
  proveParticipation (trust-level proof)

It is NOT an election verifiability context.

Do NOT assign election verifiability concepts to it
because the name matches.

Assigning Cast-as-Intended, Tallied-as-Recorded,
or Independent Verification Observer to the
"Verification" aggregate because it is named
"Verification" is a naming error, not a
governance decision.

Trust Attestation and Election Verifiability
are distinct domain concepts.
```

All ownership candidates below are evaluated against this constraint.

---

## Vote Aggregate Baseline Scope

Before assigning ownership candidates, the matrix establishes what the Vote aggregate already owns. This is the baseline from which the analysis begins.

**Vote aggregate confirmed scope (Rounds 17–35, Round 24A, Round 36A-01):**

| Capability | Source | Scope |
|------------|--------|-------|
| VO-3: Receipt hash generation | Round 33 | Issued to voter at recording time |
| `verifyByReceipt` | Round 24A BaseVote | Voter self-verification via receipt hash |
| `verifyByCode` | Round 24A BaseVote | Code-based verification of vote ownership |
| `proveParticipation` | Round 24A BaseVote | Participation proof without revealing choice |
| VoteRecorded (anonymous) | Round 34A | Event: ballot permanently recorded, no voter identity |
| VO-1: anonymity constraint | Round 33 | Vote-voter link must not exist |
| VO-4: atomicity | Round 33 | CastVote is atomic |
| `calculateChecksum`, `verifyChecksum` | Round 24A | Vote integrity hashing |

**What Vote does NOT own:**
- The external representation surface that makes evidence accessible to voters or auditors (OBS-36A-05-1)
- Any tally computation or tally verification (D39 unresolved)
- Cast-as-Intended verification (no mechanism discovered — gap from Round 36A-02)
- Governance configuration state (GovernanceState aggregate owns this)

**D42B question (the primary question this matrix must inform):**
Is the Vote aggregate's current scope sufficient for verifiability? Or does D42B require new capabilities, a new context, or a boundary extension?

---

## Ownership Candidate Matrix

The row source is the Round 36A-02 Concept-Evidence-Gap table, supplemented by concepts that emerged from literature review. Each row includes the OBS-36A-05-2 check.

---

### Row 1 — Individual Verifiability

**Concept:** A voter can verify that their vote was recorded as they cast it.

| Field | Content |
|-------|---------|
| **Domain Evidence** | `verifyByReceipt` (BaseVote), `verifyByCode` (BaseVote), VO-3 receipt hash, Vote receipt issued to voter (Round 24A). Evidence strength: HIGH |
| **Round 36A Status** | 36A-DI-02 CONFIRMED: receipt enables access but does not complete verification. RH-1: VO-1 compatible with receipt-based individual verifiability |
| **OBS-36A-05-2 Check** | Vote owns the CONCEPT (receipt generation, hash storage). Who owns the REPRESENTATION (the accessible record against which the receipt is validated)? These are separate questions |
| **Candidate Owner — Concept** | **Vote aggregate** — receipt generation, hash mechanism, and participation proof are already Vote's responsibility |
| **Candidate Owner — Representation** | (A) Audit context: already receives all domain events including VoteRecorded; could serve as accessible record. (B) Dedicated Verification Representation context: CDI-04 pattern suggests a purpose-built representation surface. (C) Vote aggregate extended: extend Vote's scope to publish a privacy-preserving representation |
| **Conflict?** | Concept ownership is clear (Vote). Representation ownership is contested between Audit and a potential new context. Extending Vote's boundary for representation risks violating OBS-36A-05-1 (Vote Recording ≠ Verification Representation) |
| **D42B Impact** | HIGH — this is D42B's primary question. Vote owns the mechanism; the representation surface is where D42B may require a boundary change |
| **Status** | **Vote OWNS mechanism. Representation surface: CANDIDATE (Audit / new context)** |

---

### Row 2 — Recorded-as-Cast

**Concept:** The vote as recorded matches the vote as cast. The recording event provides evidence of this.

| Field | Content |
|-------|---------|
| **Domain Evidence** | VoteRecorded (anonymous, Round 34A), VO-3 receipt hash, `verifyByReceipt`. Evidence strength: MEDIUM — recording evidenced; external proof model undiscovered |
| **Round 36A Status** | 36A-DI-03 CONFIRMED: Recorded-as-Cast and Tallied-as-Recorded are independent assurance levels. OBS-36A-05-1: Vote Recording ≠ Verification Representation |
| **OBS-36A-05-2 Check** | Vote owns the RECORDING (the internal event). Who makes that recording accessible to external parties for verification? These are separate |
| **Candidate Owner — Concept** | **Vote aggregate** — VoteRecorded is the authoritative business fact. Vote owns the recording act |
| **Candidate Owner — Representation** | (A) Audit context: VoteRecorded event is already observed by Audit; Audit could expose this as a verifiable record. (B) New Verification Representation context: purpose-built for external consumption, transforming Vote evidence without exposing internals |
| **Conflict?** | Same structural conflict as Row 1. VO-1 means Vote's internal records cannot be published directly. A transformation layer is required. Whether Audit provides that layer or a new context is needed: unresolved |
| **D42B Impact** | HIGH — Recorded-as-Cast is the assurance layer that D42B must address. Vote owns the recording; the external proof of recording is the unresolved scope question |
| **Status** | **Vote OWNS recording. External proof surface: CANDIDATE (Audit / new context)** |

---

### Row 3 — Tallied-as-Recorded

**Concept:** The tallied results match all recorded votes. An auditor can verify the count is correct.

| Field | Content |
|-------|---------|
| **Domain Evidence** | NONE. D39 unresolved: Results/Tallying context not designed. CDI-03 (Verifiable Tallying Mechanism) BLOCKED by D39 |
| **Round 36A Status** | 36A-DI-03 CONFIRMED: Tallied-as-Recorded is an independent assurance level from Recorded-as-Cast. D39 Resolution Framing: Option A (statistical RLA), Option B (cryptographic E2E-V), Option C (both). OBS-36A-06-2: statistical and cryptographic assurance are different types, not a quality hierarchy |
| **OBS-36A-05-2 Check** | Not applicable until D39 resolves. No owner; no representation |
| **Candidate Owner — Concept** | **Results/Tallying context (D39)** — the only credible owner; but D39 is unresolved so this context does not yet exist in the discovered model |
| **Candidate Owner — Representation** | Depends on D39 resolution: Option A would require an accessible CVR/sample-audit record; Option B would require an encrypted tally with public decryption proofs. Both differ in architectural form |
| **Conflict?** | **BLOCKED — D39 must be resolved before any ownership assignment is possible.** Assigning Tallied-as-Recorded to any current aggregate before D39 resolves would be a governance error |
| **D42B Impact** | MODERATE — D42B is primarily about Recorded-as-Cast (individual verifiability). Tallied-as-Recorded is D39's scope, not D42B's |
| **Status** | **BLOCKED — D39 unresolved. Do not assign. Carry as open conflict into 36A-09** |

---

### Row 4 — Cast-as-Intended

**Concept:** The ballot that was recorded matches what the voter intended to cast. The voter had an opportunity to verify their selection before submission.

| Field | Content |
|-------|---------|
| **Domain Evidence** | NONE discovered. This is the primary Round 36A-02 gap. 36A-DI-01 CONFIRMED: Cast-as-Intended ≠ Recorded-as-Cast — these are separated by an irreversible commit point |
| **Round 36A Status** | CDI-02: Cast-as-Intended Challenge Mechanism (two-source: Benaloh + ElectionGuard). OBS-36A-04B-2: Preparation Surface ≠ Submission Surface. NRNA 5-step voting workflow may map onto Preparation/Submission separation structurally |
| **OBS-36A-05-2 Check** | No NRNA aggregate owns this concept. The literature shows mechanisms exist; none map to a discovered NRNA aggregate |
| **Candidate Owner — Concept** | (A) No current aggregate — would require new capability at the ballot preparation layer. (B) Extended voting workflow context: Steps 1-4 (Preparation) vs. Step 5 (Submission) mirrors Preparation/Submission separation. (C) Defer: NRNA governance model may not require Cast-as-Intended; receipt-freeness trade-off was assessed as intentional in Round 24A |
| **Candidate Owner — Representation** | Not applicable until concept ownership is resolved |
| **Conflict?** | Constitutional question: does NRNA require Cast-as-Intended verification? Round 24A assessed the absence as an intentional design trade-off, not a gap requiring remediation. Round 36A literature shows the mechanism exists; it does not establish that NRNA must implement it |
| **D42B Impact** | LOW — D42B concerns Recorded-as-Cast (individual verifiability after recording). Cast-as-Intended is pre-recording. These are independent by 36A-DI-01 |
| **Status** | **UNASSIGNED. Constitutional question for ARB: is Cast-as-Intended required? Do not advance CDI-02 until constitutional determination is made** |

---

### Row 5 — Governance Configuration Freeze

**Concept:** Governance configuration is immutable before vote collection or outcome verification begins. No parameter change is possible once the process opens.

| Field | Content |
|-------|---------|
| **Domain Evidence** | GovernanceState aggregate owns state transitions. `OpenVoting` transition marks the voter-interaction phase. GovernanceDecisionSnapshot has integrity hashes (Round 24A). Evidence strength: HIGH for transition ownership; MEDIUM for immutability guarantee post-transition |
| **Round 36A Status** | 36A-DI-05 CONFIRMED (four independent sources, three families). CDI-06: Governance Fingerprint Pattern. OBS-36A-04B-3: Configuration Freeze ≠ Configuration Certification (two distinct governance acts, may belong to different commands) |
| **OBS-36A-05-2 Check** | GovernanceState owns the CONCEPT (the freeze event). The REPRESENTATION (evidence that configuration was frozen and is unchanged) may be Audit's responsibility |
| **Candidate Owner — Concept** | **GovernanceState aggregate** — `OpenVoting` is already the transition point; CDI-06 (Governance Fingerprint) maps directly to GovernanceDecisionSnapshot. GovernanceState is the clear concept owner |
| **Candidate Owner — Representation** | (A) Audit context: governance events (GovernanceTransitionCompleted) are already observed by Audit; Audit could surface the fingerprint as a verifiable record. (B) GovernanceState extended: publish fingerprint as part of OpenVoting transition (consistent with CDI-06 pattern) |
| **Conflict?** | OBS-36A-04B-3: if Freeze and Certification are distinct acts, GovernanceState may require two commands or two transitions. D35/D37/ADH-1 (legitimacy, authority hierarchy) block the Certification side. Freeze ownership is clear; Certification ownership is debt-blocked |
| **D42B Impact** | MODERATE — 36A-DI-05 confirms governance configuration must be frozen before verification begins. This is a precondition for verifiability claims, not a verifiability mechanism itself |
| **Status** | **PRESUMPTIVE OWNER: GovernanceState owns Configuration Freeze. Five-source, two-family (cryptographic + statistical) confirmation. Freeze ownership effectively settled. Certification side BLOCKED by D35/D37/ADH-1. Fingerprint representation: CANDIDATE (Audit / GovernanceState extension)** |

---

### Row 6 — Verification Representation Surface

**Concept:** A publicly accessible, privacy-preserving transformation of vote evidence that allows external verification without exposing raw vote data.

| Field | Content |
|-------|---------|
| **Domain Evidence** | No external representation surface discovered. Audit context exists (internal). `verifyByReceipt` exists (voter-facing only). CDI-04 pattern: three-family confirmation of a distinct verification representation layer separate from vote recording |
| **Round 36A Status** | CDI-04: three-family confirmed pattern (CDI status maintained; architectural form remains open). OBS-36A-05-1: Vote Recording ≠ Verification Representation (four systems separate these). SP-02 (Prêt à Voter): published record is a privacy-preserving transformation — not raw vote data |
| **OBS-36A-05-2 Check** | This row IS the representation question. Who owns the publication of a privacy-preserving transformation of vote evidence? |
| **Candidate Owner — Concept** | (A) **Audit context (D6)**: already receives all domain events; already the records layer; natural absorber for a verification representation surface. Risk: Audit is currently internal-only; extending it to external consumption changes its scope. (B) **New Verification Representation context**: purpose-built for external-facing transformation and publication; cleaner boundary; higher architectural cost. (C) **Vote aggregate extended**: Vote generates evidence and publishes transformation. Risk: violates OBS-36A-05-1 (Vote Recording ≠ Representation) |
| **Candidate Owner — Representation** | Same as concept owner for this row — the representation IS the concept |
| **Conflict?** | Audit (A) and new context (B) are both credible. Extending Audit may create a God Context (OBS-34C-2 warning). A new context is architecturally clean but adds a bounded context boundary. Option C (Vote extension) is architecturally inadvisable per OBS-36A-05-1 |
| **D42B Impact** | HIGH — this is where D42B resolves for the representation side. Individual Verifiability (Row 1) depends on this row being resolved first |
| **Status** | **CANDIDATE: Audit extension vs. new context. Do not extend Vote for this purpose. Resolution for 36A-09** |

---

### Row 7 — Independent Verification Observer (CAH-02)

**Concept:** An external party with read-only access to the verification representation can independently verify election integrity without requiring system cooperation.

| Field | Content |
|-------|---------|
| **Domain Evidence** | No independent observer discovered. GovernanceReplayService (internal). Audit context (internal). No external verification API or accessible record discovered |
| **Round 36A Status** | CAH-02: CANDIDATE (four-source, three-family — Literature Saturation Rule met; CANDIDATE per Option C until ownership analysis). CDI-07 promoted to CAH-02 |
| **OBS-36A-05-2 Check** | The observer is external. The question is: who inside NRNA owns the obligation to provide the accessible record that an external observer can verify? Representation ownership is an NRNA responsibility; the observer itself is external |
| **Candidate Owner — NRNA Responsibility** | (A) **Audit context**: if Audit is extended to external-facing verification representation (Row 6 Option A), the observer would access Audit's published records. Audit owns the obligation. (B) **New Verification Representation context**: if a dedicated representation context is created (Row 6 Option B), it owns the external-facing obligation. (C) **External actor entirely**: NRNA publishes records; an external party independently builds an observer. NRNA's responsibility ends at publication |
| **Conflict?** | CAH-02 is CANDIDATE — ownership analysis may narrow the options but cannot resolve them in 36A-08. Conflict with Row 6: CAH-02 ownership depends on Row 6 resolution (who owns the representation surface). These two rows are coupled |
| **D42B Impact** | MODERATE — independent verification is relevant to D42B's scope question (what does the Vote boundary owe?) but the observer pattern is not Vote's responsibility |
| **Status** | **CANDIDATE: Audit / new context / external actor. Depends on Row 6 resolution. Carry coupled conflict into 36A-09** |

---

### Row 8 — Two Verification Audiences (36A-DI-04)

**Concept:** Two distinct audiences have distinct verification interests: (A) Individual Voter — "Was my vote recorded?"; (B) Election Auditor / Observer — "Is the announced outcome correct?"

| Field | Content |
|-------|---------|
| **Domain Evidence** | `verifyByReceipt`, `proveParticipation` serve Individual Voter. No mechanism serves Election Auditor/Observer externally |
| **Round 36A Status** | 36A-DI-04: CANDIDATE DOMAIN INSIGHT — visible across multiple families; not yet fully confirmed |
| **OBS-36A-05-2 Check** | The two audiences require different representations, not just different mechanisms. Individual Voter verification and Auditor/Observer verification may require different architectural surfaces |
| **Candidate Owner — Individual Voter audience** | **Vote aggregate** — `verifyByReceipt` and `proveParticipation` already serve this audience. This is Vote's confirmed scope |
| **Candidate Owner — Auditor/Observer audience** | **Results/Tallying context (D39)** for outcome verification; **Audit context** for evidence availability; **Verification Representation context** (if created per Row 6) for accessible evidence. The Auditor/Observer audience is inseparable from D39 and Row 6 |
| **Conflict?** | The two-audience model is architecturally sound (36A-DI-04 CANDIDATE). The problem is that one audience's owner (Vote) is clear, and the other's (Auditor/Observer) is blocked by D39. This row cannot be fully resolved until D39 resolves |
| **D42B Impact** | DIRECT — D42B's scope question is precisely this: does Vote own only Individual Voter verification, or is it responsible for enabling Auditor/Observer verification too? The audience separation suggests Vote owns Individual Voter; Auditor/Observer is a separate scope |
| **Status** | **Individual Voter: Vote OWNS. Auditor/Observer: CANDIDATE (Audit / Results-D39 / new context). D39 BLOCKED for outcome-verification side** |

---

### Row 9 — Universal Verifiability

**Concept:** Any external party can verify that all votes were counted correctly and that the announced result accurately reflects all recorded votes.

| Field | Content |
|-------|---------|
| **Domain Evidence** | NONE. Round 24A: "Universal verifiability is the weakest guarantee in the current system." No external verification mechanism; governance infrastructure is internal-only |
| **Round 36A Status** | Not a separate DI; encompasses Tallied-as-Recorded (Row 3, D39 BLOCKED) and Verification Representation Surface (Row 6, CANDIDATE). Universal Verifiability is the composite of Rows 3, 6, and 7 |
| **OBS-36A-05-2 Check** | Universal Verifiability requires both: (A) a published representation, and (B) the tally being independently verifiable. Both components are unresolved |
| **Candidate Owner — Concept** | No current aggregate owns this. It is a composite of D39 (tally) + Row 6 (representation) + Row 7 (observer). Cannot be assigned until its components are resolved |
| **Conflict?** | Universal Verifiability cannot be addressed as a single concept until D39, Row 6, and Row 7 are resolved. Attempting to assign a single owner prematurely conflates three separate unresolved questions |
| **D42B Impact** | LOW — Universal Verifiability is beyond D42B's scope. D42B is specifically about the Vote aggregate's individual verifiability boundary |
| **Status** | **UNASSIGNED. Composite concept. Resolves only after D39 + Row 6 + Row 7 are resolved. Do not assign in isolation** |

---

### Row 10 — Receipt-based Verification

**Concept:** A voter holds a receipt hash that can be validated against a published record.

| Field | Content |
|-------|---------|
| **Domain Evidence** | `verifyByReceipt` (BaseVote, Round 24A) — HIGH |
| **Round 36A Status** | 36A-DI-02 CONFIRMED: receipt enables access; receipt alone does not complete verification. The external record against which the receipt is validated is Row 6 |
| **OBS-36A-05-2 Check** | Vote owns the receipt MECHANISM. The REPRESENTATION (the published record against which the hash is validated) is Row 6. These are split |
| **Candidate Owner — Concept** | **Vote aggregate** — already owns this. Receipt hash generation, storage (VO-3), and `verifyByReceipt` are confirmed Vote scope |
| **Candidate Owner — Representation** | Row 6 (Verification Representation Surface) — same owner question applies here |
| **Conflict?** | Mechanism ownership is clear (Vote). This row is a dependency on Row 6 resolution — it does not introduce a new conflict |
| **D42B Impact** | HIGH — receipt-based verification is the primary D42B mechanism. Vote owns the receipt; the accessible record is D42B's unresolved scope |
| **Status** | **Vote OWNS mechanism. Representation dependency: Row 6. No new conflict beyond Row 6** |

---

### Row 11 — Participation Proof

**Concept:** A voter can prove they participated in the election without revealing their vote choice.

| Field | Content |
|-------|---------|
| **Domain Evidence** | `proveParticipation` (BaseVote, Round 24A) — HIGH |
| **Round 36A Status** | Explicitly confirmed as VO-1-compatible |
| **OBS-36A-05-2 Check** | Vote owns both concept and mechanism. External representation is not required for participation proof — the proof is provided directly to the voter |
| **Candidate Owner** | **Vote aggregate** — already owns this. No representation surface required |
| **Conflict?** | None. This is the most settled row in the matrix |
| **D42B Impact** | LOW — participation proof is within Vote's confirmed scope |
| **Status** | **Vote OWNS. Settled. No open conflict** |

---

### Row 12 — Anonymity-Verifiability Compatibility (VO-1 + Individual Verifiability)

**Concept:** Anonymity (VO-1) and individual verifiability coexist without trade-off.

| Field | Content |
|-------|---------|
| **Domain Evidence** | VO-1 constraint (Round 33), `verifyByReceipt` + `proveParticipation` (Round 24A) — MEDIUM (coexistence observed; formal adversarial proof is not in scope for this program) |
| **Round 36A Status** | RH-1 CONFIRMED: VO-1 is compatible with receipt-based individual verifiability. Four systems demonstrate coexistence. Mechanism appropriateness for NRNA's digital context is a design question, not a compatibility question |
| **OBS-36A-05-2 Check** | VO-1 is a Vote invariant. The compatibility proof belongs to Vote's scope. Representation surfaces must be VO-1-preserving by design |
| **Candidate Owner** | **Vote aggregate** — VO-1 and verifyByReceipt are both Vote responsibilities. The compatibility obligation belongs to Vote |
| **Conflict?** | The Verification Representation Surface (Row 6) must be VO-1-compatible by design. Any candidate owner for Row 6 must take VO-1 compatibility as a required constraint, not an optional one. If Audit is extended to serve as the representation surface, Audit must publish only privacy-preserving transformations |
| **D42B Impact** | HIGH — D42B must confirm that any scope extension for Vote or any new representation context preserves VO-1. VO-1 is a constitutional constraint on the representation surface owner, whoever that is |
| **Status** | **Vote OWNS VO-1 constraint. VO-1 is a binding constraint on Row 6 owner selection** |

---

## Conflict Register

The following conflicts require ARB resolution before ownership assignments can be finalized.

---

### Conflict C-01 — D39 (Tallied-as-Recorded)

**Nature:** Results/Tallying context not designed. No tally verification mechanism exists in the discovered model.

**Blocked rows:** Row 3 (Tallied-as-Recorded), Row 8 (Auditor/Observer audience), Row 9 (Universal Verifiability)

**Resolution required:** D39 must be resolved — Option A (statistical RLA), Option B (cryptographic E2E-V), or Option C (both). The resolution determines which rows become assignable.

**Cannot be resolved in 36A-08.** Carry into 36A-09.

---

### Conflict C-02 — Verification Representation Surface Owner (Rows 1, 2, 6, 7, 10)

**Nature:** Five rows depend on a single unresolved question: who owns the accessible, privacy-preserving representation of vote evidence?

**Option A — Audit context extended:** Audit already receives all domain events. Extending Audit to external-facing publication is lower architectural cost. Risk: scope inflation approaching God Context (OBS-34C-2).

**Option B — New Verification Representation context:** Clean boundary. Higher architectural cost. Directly resolves CDI-04 as a dedicated context rather than an Audit extension.

**Option C — Vote aggregate extended:** Architecturally inadvisable. Violates OBS-36A-05-1 (Vote Recording ≠ Verification Representation). Should not be selected.

**Resolution required in 36A-09 or ARB ADR.** This is the highest-priority unresolved conflict in the matrix — it blocks D42B resolution.

---

### Conflict C-03 — Cast-as-Intended (Row 4)

**Nature:** No NRNA aggregate owns Cast-as-Intended. CDI-02 (Cast-as-Intended Challenge Mechanism) exists as a two-source pattern, but NRNA's constitutional model does not require it.

**Constitutional question:** Did Round 24A's intentional-trade-off determination resolve this permanently? Or does Round 36A literature evidence obligate ARB to reconsider?

**If ARB maintains trade-off:** Row 4 remains UNASSIGNED permanently. No action required.

**If ARB requires Cast-as-Intended:** A new capability at the ballot preparation layer is required. This is a significant scope addition.

**Resolution belongs to ARB constitutional determination.** Carry into 36A-09.

---

### Conflict C-04 — GovernanceState Freeze vs. Certification (Row 5)

**Nature:** OBS-36A-04B-3 established that Configuration Freeze and Configuration Certification may be distinct governance acts. GovernanceState clearly owns the Freeze (OpenVoting transition). Certification authority is blocked by D35/D37/ADH-1.

**Impact on 36A-DI-05:** The Governance Configuration Freeze (CONFIRMED) is the freeze side. The certification side is debt-blocked and not part of 36A-DI-05's scope. These should not be conflated.

**Resolution required:** D35/D37/ADH-1 must resolve before Certification ownership can be assigned. Freeze ownership is clear without this resolution.

---

### Conflict C-05 — CAH-02 Coupling (Rows 6 and 7)

**Nature:** CAH-02 (Independent Verification Observer) is a CANDIDATE. Its ownership inside NRNA depends on Row 6 resolution (who owns the representation surface the observer reads). C-02 and C-05 are structurally coupled — resolving C-02 resolves the primary dependency of C-05.

**Resolution sequence:** Resolve C-02 first. C-05 narrows significantly once the representation surface owner is established.

---

## D42B Resolution Framing

D42B asks: Is the Vote aggregate's boundary correct for verifiability? Does it need to expand, or can verifiability responsibilities be satisfied by adjacent contexts?

**Matrix output for D42B:**

| Component | Owner | Requires Boundary Change? |
|-----------|-------|--------------------------|
| Receipt generation (VO-3) | Vote | No — already owned |
| `verifyByReceipt` mechanism | Vote | No — already owned |
| `proveParticipation` | Vote | No — already owned |
| VoteRecorded (anonymous) | Vote | No — already owned |
| Verification Representation Surface | CANDIDATE (C-02) | Yes, if assigned to Vote — inadvisable (OBS-36A-05-1) |
| Tallied-as-Recorded | BLOCKED (D39) | Not Vote's scope |
| Cast-as-Intended | UNASSIGNED (C-03) | Only if ARB requires — would be a new scope addition |

**D42B most likely resolution path:**

```
D42B — CANDIDATE RESOLUTION

Vote aggregate owns:
  Individual Verifiability (mechanism side)
  Recorded-as-Cast (recording side)
  Anonymity compatibility (VO-1)

Vote aggregate does NOT own:
  External verification representation surface (C-02 owner)
  Tallied-as-Recorded (D39 owner)
  Cast-as-Intended (constitutional determination pending)

D42B resolves toward:
  "Vote boundary is correct for its current responsibilities.
   The representation surface is a gap that belongs to
   an adjacent context — either Audit (extended) or new."

This is a CANDIDATE resolution.
Formal resolution requires C-02 to be decided first.
```

---

## Ownership Summary Table

| Concept | Concept Owner | Representation Owner | Status |
|---------|--------------|---------------------|--------|
| Individual Verifiability | Vote | CANDIDATE (C-02) | Partially assigned |
| Recorded-as-Cast | Vote | CANDIDATE (C-02) | Partially assigned |
| Tallied-as-Recorded | Results/Tallying (D39) | BLOCKED | Blocked by D39 |
| Cast-as-Intended | UNASSIGNED | n/a | Constitutional question (C-03) |
| Governance Config Freeze | GovernanceState | Audit / GovernanceState extension | PRESUMPTIVE OWNER |
| Verification Representation Surface | CANDIDATE (C-02) | CANDIDATE (C-02) | Primary open conflict |
| Independent Verification Observer | NRNA: CANDIDATE (C-05) | Depends on C-02 | Coupled to C-02 |
| Two Verification Audiences | Vote (Individual Voter side) + D39 (Auditor/Observer side) | Split | Partially blocked by D39 |
| Universal Verifiability | Composite — D39 + C-02 + C-05 | n/a | Unassigned; composite |
| Receipt-based Verification | Vote | CANDIDATE (C-02) | Mechanism owned; depends on C-02 |
| Participation Proof | Vote | n/a (direct, no surface required) | Settled |
| Anonymity-Verifiability Compatibility | Vote (VO-1) | Binding constraint on C-02 owner | Settled as constraint |

---

## ARB Observations Applied

### OBS-36A-08-1 — Audit Context Attraction Risk

```
OBS-36A-08-1

Audit Context Attraction Risk

Audit appears repeatedly in this matrix as
a candidate owner for:
  - Verification Representation Surface (Row 6)
  - Independent Verification Observer (Row 7)
  - Recorded-as-Cast external proof (Row 2)
  - Governance Fingerprint representation (Row 5)

This pattern has two possible explanations:

  A) Audit genuinely owns these responsibilities.
     They are the natural extension of an
     evidence-observation context.

  B) Audit is becoming an architectural gravity well.
     "Audit receives events" is being conflated with
     "Audit owns verification capabilities."

     These are different responsibilities.

Round 36A-09 must distinguish explicitly:

  "Audit receives evidence as an observer"
      from
  "Audit owns and publishes verification surfaces"

If Audit is assigned too many publication and
verification obligations, it risks becoming:

  Logs
  + Evidence
  + Verification
  + Observer Support
  + External Publication

which is the God Context pattern (OBS-34C-2).

Audit's candidate ownership must be justified
against this risk in Round 36A-09.
```

---

### OBS-36A-08-2 — GovernanceState Freeze Effectively Settled

```
OBS-36A-08-2

GovernanceState Configuration Freeze Ownership

Row 5 labelled this "Strong Candidate."

The ARB directs this be upgraded to:

  Presumptive Owner

Rationale:

  Five independent sources:
    ElectionGuard
    Helios
    Prêt à Voter
    Scantegrity
    Risk Limiting Audits

  Two independent families:
    Cryptographic E2E-V
    Statistical Auditing

  Plus direct domain evidence:
    GovernanceState owns OpenVoting transition
    GovernanceDecisionSnapshot has integrity hashes
    OpenVoting is already the configuration-freeze point

This is no longer a candidate assignment.
It is effectively settled.

The only unresolved question is:
  Configuration Freeze ≠ Configuration Certification
  (OBS-36A-04B-3)

The Freeze side belongs to GovernanceState.
The Certification side is BLOCKED by D35/D37/ADH-1.

These are separate questions.
GovernanceState's Freeze ownership is not
contingent on the Certification side being resolved.
```

---

## Primary Architectural Hotspot

The matrix makes the true architectural question visible:

```
C-02 — Verification Representation Surface

is the architectural hotspot of Round 36A.

Not D42B.
Not CAH-02.
Not Cast-as-Intended.
Not D39.

C-02.

The following depend on C-02 resolution:

  Individual Verifiability (Row 1)
  Recorded-as-Cast external proof (Row 2)
  Receipt Validation (Row 10)
  Independent Observer (Row 7 — CAH-02)
  D42B formal resolution

Round 36A-09 should direct almost all
analytical effort at a single question:

  What bounded context owns the
  Verification Representation Surface?

    Option A: Audit context extended
    Option B: New dedicated context
    Option C: Vote extended — inadvisable (OBS-36A-05-1)

Once this falls, most of the remaining matrix
collapses into place.
```

---

## Priority Sequence for 36A-09

The conflicts are not independent. The resolution sequence matters:

```
1. FIRST: Resolve C-02 (Verification Representation Surface owner)
   → Unblocks: Rows 1, 2, 6, 7, 10
   → Enables: D42B candidate resolution to become formal
   → Constrains: CAH-02 ownership (C-05)

2. SECOND: Resolve C-03 (Cast-as-Intended constitutional question)
   → ARB determines: required or intentional gap?
   → If required: new scope addition; if gap confirmed: Row 4 closed

3. PARALLEL: C-04 (Freeze vs Certification) resolves with D35/D37/ADH-1
   → Does not block C-02 or C-03
   → GovernanceState freeze ownership is clear regardless

4. BLOCKED until D39: C-01 (Tallied-as-Recorded)
   → Do not attempt to resolve until D39 produces a Results/Tallying design
```

---

## What This Document Does NOT Resolve

Consistent with the Option C authorization (proceed with honest confidence labels; 36A-08 produces candidates and conflicts; 36A-09 resolves):

- C-02 (Representation Surface owner): NOT resolved here
- D39: NOT resolved here
- C-03 (Cast-as-Intended constitutional question): NOT resolved here
- CAH-02 owner: NOT resolved here
- D42B formal resolution: NOT resolved here — the candidate resolution is provided; formal resolution requires C-02 to be decided first

---

## ARB Formal Decision

```
Round 36A-08 — Ownership Candidate Matrix

APPROVED WITH OBSERVATIONS

Research Quality:       EXCELLENT
Governance Discipline:  VERY HIGH
Conflict Clarity:       EXCELLENT
DDD Discipline:         HIGH

Confidence: HIGH

Corrections applied:
- Naming Constraint elevated to permanent architecture constraint candidate
- Row 5 GovernanceState: Strong Candidate → PRESUMPTIVE OWNER
- Ownership Summary Table updated to reflect PRESUMPTIVE OWNER
- OBS-36A-08-1 added: Audit Context Attraction Risk
- OBS-36A-08-2 added: GovernanceState Freeze Effectively Settled
- Primary Architectural Hotspot section added: C-02 identified as
  the central unresolved question on which most other matrix items depend

Key findings accepted:
- Vote aggregate owns: Individual Verifiability mechanism,
  Recorded-as-Cast, Participation Proof, VO-1 constraint
- D39 correctly blocked for Tallied-as-Recorded throughout
- D42B candidate resolution accepted (~85% probable):
  Vote owns mechanism; representation surface belongs
  to an adjacent context
- Cast-as-Intended correctly framed as a constitutional question,
  not an architectural gap requiring remediation

Next authorised step: Round 36A-09 — Conflict Resolution and D42B Formal Resolution

Primary mandate for 36A-09:
  Resolve C-02 (Verification Representation Surface owner)
  Audit extension vs. new dedicated context — decide
  Once C-02 falls, Rows 1, 2, 6, 7, 10 and D42B follow
```
