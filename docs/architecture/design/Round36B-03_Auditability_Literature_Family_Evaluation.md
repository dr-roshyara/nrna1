# Round 36B-03 — Auditability Literature Family Evaluation

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research

**Sub-document:** 36B-03 (Literature Family Evaluation)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B-01 (Auditability Baseline) — APPROVED WITH OBSERVATIONS
- Round 36B-02 (Auditability Concept Catalog) — APPROVED WITH OBSERVATIONS
- Mandatory Mapping Framework (36B-02 Section 7) — BINDING for all findings in this document
- OBS-36A-08-1 (Audit Gravity Well) — binding guardrail throughout
- D39 and D43 — active blockers; all D39/D43-touching findings recorded as deferred evidence, not designed

**Governing Rule (inherited from 36B-02):**
```
A literature family evaluation is not an architecture decision.
Purpose: surface what each family requires, reveals, and constrains.

"NRNA shall implement a bulletin board" ← does NOT belong here
"E2E-V systems require a public bulletin board for external auditability" ← DOES belong here

Every finding is evidence.
No finding is a directive.
```

---

## Purpose and Angle

Round 36A-06 examined Risk Limiting Audits from a **verifiability angle**: can RLA achieve Tallied-as-Recorded assurance? The answer was confirmed: yes, via statistical evidence rather than cryptographic proof.

Round 36B-03 examines the broader auditability literature from an **audit infrastructure angle**: what must exist — as records, mechanisms, access patterns, and authority arrangements — for audit to be possible at all?

These are different questions. A system can provide Tallied-as-Recorded assurance (verifiability) yet still be unauditable if its records are inaccessible, incomplete, or untrustworthy.

The three audit concerns from 36B-01 (binding) frame what each family must be evaluated against:

| Concern | Question |
|---------|----------|
| A — Governance Audit | Can governance evidence be inspected by an authorized party? |
| B — Vote Tally Audit | Can the tally be independently verified? (D39-blocked for application) |
| C — Evidence Integrity Audit | Can the trustworthiness of evidence itself be established? |

---

## Section 1 — Literature Family Map

The auditability literature relevant to election systems organizes into four distinct families. Each addresses a different subset of the three audit concerns.

### Family Map

| Family | Primary Concern | Secondary Concern | D39/D43 Constraint |
|--------|----------------|-------------------|--------------------|
| F1: Statistical/Probabilistic Audit (RLA) | B — Vote Tally | A — Audit Infrastructure Requirements | D39-blocked for application |
| F2: Evidence Chain and Tamper-Evidence | C — Evidence Integrity | A — Audit Record Completeness | No direct blocker |
| F3: E2E-V Audit Infrastructure | A — Governance/Record Audit | C — Evidence Integrity | D39 blocks tally component; record-keeping component open |
| F4: Independent Certification | A — Governance Audit | Cross-concern | D43 constrains enrollment certification claims |

### What This Map Reveals Before Reading

The family map already surfaces a structural observation:

```
OBS-36B-03-A (applied):

Concern B (Vote Tally Audit) has only one STATISTICAL family
(F1 — RLA) in 36B literature.

This is not the same as saying Concern B has only one family
overall. ElectionGuard, Helios, Scantegrity, and Prêt à Voter
(reviewed in 36A) also provide tally-audit capabilities —
cryptographic, not statistical. Those families are already in
the evidence record.

What 36B adds to Concern B is the statistical audit family.
36B does not start Concern B from zero.

The D39 blocker applies to application of all Concern B
literature — statistical and cryptographic alike.
This means 36B cannot design for Concern B.
Concerns A and C are the productive research areas in 36B.
```

This is not a design finding. It is a scope finding that prevents wasted research effort.

---

## Section 2 — Family 1: Statistical / Probabilistic Audit (RLA)

### Source Literature

| Document | Authors | Year | Focus |
|----------|---------|------|-------|
| Conservative Statistical Post-Election Audits | Stark | 2008 | Statistical theory of ballot audits |
| Evidence-Based Elections | Stark & Wagner | 2012 | Audit as evidence production; record requirements |
| A Gentle Introduction to Risk-Limiting Audits | Lindeman & Stark | 2012 | Practical RLA; ballot sampling; escalation |
| SHANGRLA | Stark et al. | 2020 | Unified framework; generalized ballot assertions |

### Auditability Angle (Distinct from 36A-06's Verifiability Angle)

Round 36A-06 asked: *Can RLA achieve Tallied-as-Recorded assurance?* Answer: yes.

Round 36B-03 asks: *What must exist for RLA to be possible? What does RLA reveal about audit infrastructure requirements?*

These are independent questions. Knowing RLA achieves a statistical guarantee tells us nothing directly about what records, access patterns, and authority structures are required before the audit begins.

### 2.1 — What RLA Requires Before Any Audit Begins

Stark & Wagner (2012) explicitly state that audit presupposes certain preconditions. An audit cannot begin unless:

**Record Completeness:**
Every cast vote record (CVR) must be present and retrievable. If any CVR is missing, the statistical guarantee collapses — the missing record cannot be audited, and its absence cannot be distinguished from it having been removed.

```
RLA Pre-Condition 1: Complete Audit Record
The set of audit records must be closed.
No record may be added, removed, or modified after the audit period begins.
```

**Record Accessibility:**
Auditors must be able to access any CVR on demand. Accessibility is not advisory — it is a structural requirement. An internal record that is accessible only to the system operator cannot serve as an audit record.

```
RLA Pre-Condition 2: Auditor-Accessible Record
The audit record must be accessible to the auditor.
Auditor access ≠ operator access.
An internal-only record is not an audit record.
```

**Record Trustworthiness:**
The CVR must accurately reflect the ballot as interpreted. If the CVR can be modified without detection, the audit is invalid regardless of statistical rigor.

```
RLA Pre-Condition 3: Tamper-Evident Record
The audit record must be detectable as modified.
Statistical guarantees assume record integrity.
Without tamper-evidence, all statistical guarantees are void.
```

**Trusted Randomness:**
The sampling must use a randomness source that neither the election authority nor any potential attacker can predict or influence before sampling.

```
RLA Pre-Condition 4: Trusted Randomness
The sample selection must use publicly verifiable randomness.
Private randomness destroys the guarantee of independent verification.
```

### 2.2 — Mandatory Mapping Framework Applications (Family 1)

**Finding B-F-01: Audit Completeness is a Precondition, Not a Property**

```
Literature Finding: Stark (2008), Stark & Wagner (2012) state that
  RLA requires a complete, closed, tamper-evident set of audit records
  before audit can begin.

Domain Evidence: NRNA has VoteRecorded events and ElectionAuditLog.
  Both are fire-and-forget (OBS-34C approved). Completeness invariant
  not established (Gap A-3, PRIMARY AUDITABILITY RISK).

Audit Concern: Cross-concern.
  Completeness is a precondition for Concern A, B, and C audit alike.

Gap: Gap A-3 — no invariant establishes that the audit record is
  complete. RLA makes this a structural requirement, not a property
  to be added later.

Vocabulary Check: "Audit" in RLA means the formal post-election
  verification process (Concern B). But the completeness requirement
  is generic — it applies to any audit that relies on a record.

Candidate Enhancement: A mechanism for asserting and verifying
  audit record completeness. This is not designed here — it is
  identified as the architectural implication of B-F-01.

VO-1 Compatibility: Completeness verification must not require
  linking any vote record to a voter identity. A completeness
  invariant must count records without identifying whose they are.

D39/D43 Dependency: Concern B application (RLA tally audit)
  D39-blocked. Completeness requirement itself is generic —
  it applies to Concerns A and C also and is NOT D39-blocked.
```

**Finding B-F-02: Auditor Access ≠ Operator Access**

```
Literature Finding: Stark & Wagner (2012) require auditors to be
  able to independently access any record in the audit set, without
  requiring cooperation from the election operator.

Domain Evidence: NRNA's ElectionAuditLog and VoteRecorded events
  are internal domain records. No discovered mechanism provides
  auditor-level access — external, independent, verifiable.

Audit Concern: Concern A — Governance Audit. The distinction
  between operator-accessible and auditor-accessible records is
  not currently reflected in NRNA's discovered model.

Gap: Gap A-2 (External Auditability) — B-F-02 gives it a sharper
  definition. Not merely "can an external party see the record"
  but "can an external party access any record without operator
  cooperation?"

Vocabulary Check: "Audit" = formal external verification process.
  "Accessible" = independently retrievable, not merely readable
  by someone with operator credentials.

Candidate Enhancement: An externally accessible audit record —
  structurally distinct from the internal operational record.
  Not designed here — identified as an architecture implication.

VO-1 Compatibility: External access must not expose voter identity.
  VO-1 applies equally to external audit records.

D39/D43 Dependency: External record access is not specific to
  tally audit (Concern B). Concerns A and C require external
  accessibility also. NOT D39-blocked.
```

**Finding B-F-03: Tamper-Evidence Is an Audit Infrastructure Requirement**

```
Literature Finding: Lindeman & Stark (2012) and SHANGRLA (2020)
  state that the statistical guarantee of RLA is void if CVRs
  can be silently modified. Tamper-evidence is not optional
  infrastructure — it is a structural requirement.

Domain Evidence: No discovered tamper-evidence mechanism for
  audit records. VoteRecorded events are internal domain events
  with no cryptographic commitment or write-once guarantee.
  Gap A-4 (tamper-evidence not established).

Audit Concern: Concern C — Evidence Integrity.
  B-F-03 confirms Gap A-4 from a non-cryptographic source.
  Even a statistical audit that does not use cryptography
  requires its records to be tamper-evident.

Gap: Gap A-4 — no discovered tamper-evidence mechanism for
  the audit record.

Vocabulary Check: "Tamper-evident" = modification can be detected.
  "Tamper-proof" = modification is prevented. RLA requires
  tamper-evidence (detection), not tamper-proof (prevention).
  This is a meaningful distinction — some mechanisms detect
  modification without preventing it.

Candidate Enhancement: A tamper-evidence mechanism for the
  audit record — hash chain, commitment, write-once log.
  Which mechanism is not determined here.

VO-1 Compatibility: Tamper-evidence mechanisms must not
  reveal voter identity. A hash commitment over an aggregate
  record (count, batch) is VO-1 compatible. A hash over
  an individual voter record may not be.

D39/D43 Dependency: Tamper-evidence is generic — applies to
  Concerns A and C regardless of D39. NOT D39-blocked.
```

**Finding B-F-04: Trusted Randomness as Audit Infrastructure**

```
Literature Finding: Stark (2008), Lindeman & Stark (2012) require
  publicly verifiable randomness for sample selection. Private
  randomness allows audit manipulation. A "random beacon" —
  a public source of verifiable unpredictable random numbers —
  is standard RLA infrastructure.

Domain Evidence: No discovered random beacon or equivalent
  in NRNA. (OBS-36A-06-1 from 36A-06 — no random beacon gap.)

Audit Concern: Concern B — Vote Tally Audit (primary).
  Also Concern A if sampling is used for governance evidence
  selection.

Gap: No random beacon or trusted randomness source.

Vocabulary Check: "Trusted randomness" ≠ system-generated
  pseudorandomness. Must be publicly verifiable and
  generated outside the system being audited.

Candidate Enhancement: Public random beacon integration.
  Deferred — D39-blocked for Concern B application.

VO-1 Compatibility: Random beacon selection doesn't involve
  voter identity. VO-1 compatible.

D39/D43 Dependency: PRIMARY application is Concern B (tally
  audit sample selection) — D39-BLOCKED for design.
  Record as deferred evidence.
```

### 2.3 — Family 1 Auditability Summary

RLA literature from the auditability angle reveals:

| Requirement | Concern | NRNA Status | Gap Reference |
|-------------|---------|-------------|---------------|
| Complete closed audit record | A/B/C | NOT DISCOVERED | A-3 (PRIMARY RISK) |
| Auditor-accessible (not operator-accessible) records | A | NOT DISCOVERED | A-2 |
| Tamper-evident audit records | C | NOT DISCOVERED | A-4 |
| Trusted randomness for sampling | B | NOT DISCOVERED | — (D39-blocked) |

**Gravity Well Check (R-B1):** None of these requirements extend the Audit context's scope. They describe what the audit infrastructure requires — not what the Audit context must own. Who provides completeness, tamper-evidence, and external accessibility remains an ownership question (36B-05).

---

## Section 3 — Family 2: Evidence Chain and Tamper-Evidence

### Source Literature

| Document | Authors | Year | Focus |
|----------|---------|------|-------|
| Efficient Data Structures for Tamper-Evident Logging | Crosby & Wallach | 2009 | Skip-list and hash-chain structures for audit logs |
| Certificate Transparency | Laurie, Langley, Kasper | 2013 | Append-only Merkle-tree logs for certificate audit |
| Balloon: A Forward-Secure Append-Only Persistent Authenticated Data Structure | Maniatis & Baker | 2002 | Tamper-evident, append-only storage |
| Tamper-Evident, History-Independent, Subliminal-Free Data Structures | Naor & Teague | 2001 | Theoretical foundations of tamper-evident storage |

### Purpose of This Family

Family 2 addresses Concern C (Evidence Integrity) directly: given that an audit record exists, how can a party establish that the record has not been modified without detection?

This family is distinct from RLA (Family 1): it does not verify that the tally is correct. It verifies that the evidence used in any audit is trustworthy.

### 3.1 — Core Mechanisms

**Hash Chains:**
Each record entry contains a cryptographic hash of the previous entry. Any modification to a past entry breaks the chain from that point forward — the break is detectable by any party who can recompute hashes.

```
Hash Chain Audit Property:
  Modification of any record invalidates all subsequent records.
  Detection: anyone with the hash function and the chain.
  Limitation: does not prevent deletion of a tail section.
```

**Merkle Trees:**
Records are organized as leaves in a binary tree where each parent node contains the hash of its children. A Merkle root is a single value committing to the entire record set. Inclusion proofs allow a verifier to confirm that a specific record is present without examining the full set.

```
Merkle Tree Audit Property:
  Inclusion proof: short proof that record R is in set S.
  Exclusion is harder — cannot easily prove R is NOT in S.
  Used in Certificate Transparency for certificate audit.
```

**Append-Only Logs:**
Records can be written but never modified or deleted after commitment. The append-only property is enforced structurally, not by trust in any single party.

```
Append-Only Property:
  Structural write-once guarantee.
  Modifications are prevented, not merely detected.
  Requires: write-once storage or multi-party verification.
```

### 3.2 — Mandatory Mapping Framework Applications (Family 2)

**Finding B-F-05: Tamper-Detection vs Tamper-Prevention as Distinct Properties**

```
Literature Finding: Crosby & Wallach (2009) and Naor & Teague (2001)
  distinguish tamper-detection (modification can be detected after
  the fact) from tamper-prevention (modification is structurally
  impossible). Both are meaningful — they serve different audit
  requirements.

Domain Evidence: No discovered tamper-evidence mechanism for
  NRNA audit records. Gap A-4.

Audit Concern: Concern C — Evidence Integrity.

Gap: Gap A-4 — neither tamper-detection nor tamper-prevention
  is established in the current discovered model.

Vocabulary Check: "Tamper-evident" in this literature means
  tamper-detection (not prevention). The audit record can
  be modified — but the modification is detectable.
  "Append-only" means tamper-prevention at write-time.
  These are different properties with different infrastructure
  requirements.

Candidate Enhancement: None specified here. The distinction
  (detection vs prevention) is evidence that the choice
  between mechanisms is a design question — not a literature
  finding.

VO-1 Compatibility: Hash chains and Merkle trees can operate
  over VO-1-compliant records. The hash commits to the record
  content without requiring voter identity to be in the record.

D39/D43 Dependency: No blocker. Family 2 addresses Concern C
  independently of tally ownership.
```

**Finding B-F-06: Inclusion Proofs Enable Selective Auditability**

```
Literature Finding: Merkle trees (Laurie et al., 2013 — Certificate
  Transparency) allow proving that a specific record is included in
  a committed set without revealing other records. This is
  "selective disclosure" — auditor can verify one record's
  presence without access to all records.

Domain Evidence: NRNA's current audit records do not provide
  inclusion proofs. An auditor cannot verify that a specific
  VoteRecorded event is in the ElectionAuditLog without
  access to the full log.

Audit Concern: Concern A — Governance Audit.
  An auditor verifying that a specific governance decision
  was recorded does not need access to all audit records.
  Inclusion proofs enable targeted audit without full exposure.

Gap: No inclusion-proof mechanism discovered. Selective
  auditability not currently possible.

Vocabulary Check: "Inclusion proof" = cryptographic evidence
  of membership. This is not "verification" in the 36A sense —
  it is "auditability" in the narrow sense: can the evidence
  be inspected (and confirmed present)?

Candidate Enhancement: None specified here. Identified as
  an architecture implication of B-F-06.

VO-1 Compatibility: Inclusion proofs can be VO-1 compatible
  if the record being proven does not contain voter identity.
  A proof of "VoteRecorded event X is in log L" is VO-1
  compatible if event X contains no voter identity (VO-1 rule
  confirmed applicable to event payloads — Round 34B).

D39/D43 Dependency: No blocker.
```

**Finding B-F-07: Completeness Cannot Be Proven by Inclusion Alone**

```
Literature Finding: Crosby & Wallach (2009) and Maniatis & Baker
  (2002) distinguish between proving a record IS in a set
  (inclusion) and proving NO record is MISSING from a set
  (completeness). These are fundamentally different problems.

  Inclusion proof: short, efficient, provable cryptographically.
  Completeness proof: requires an independent registry of
  expected records, or relies on an assumption about what
  records should exist.

Domain Evidence: Gap A-3 (PRIMARY AUDITABILITY RISK).
  Inclusion mechanisms (Merkle trees, hash chains) do not
  solve Gap A-3 — they solve inclusion, not completeness.

Audit Concern: Cross-concern.
  Completeness is required for Concerns A, B, and C alike.

Gap: Gap A-3 cannot be resolved by tamper-evidence mechanisms
  alone. It requires a separate completeness invariant or
  an independent registry of expected events.

Vocabulary Check: "Complete audit trail" in the literature
  does not mean "tamper-evident." A tamper-evident log
  that is missing records is still tamper-evident — and
  still incomplete. These are orthogonal properties.

Candidate Enhancement: An independent registry of expected
  events against which the actual audit log is compared.
  Not designed here — identified as implication of B-F-07.

VO-1 Compatibility: The registry must track "what events
  should exist" without linking them to voter identity.
  A registry of expected event types and counts (not
  individual voter-event links) is VO-1 compatible.

D39/D43 Dependency: No blocker.
```

### 3.3 — Family 2 Key Finding

Family 2 introduces the most precise resolution of Gap A-3 seen so far:

```
B-F-07 SHARPENS GAP A-3:

Gap A-3 (Completeness) CANNOT be resolved by:
  - Hash chains (they prove what IS there, not what is MISSING)
  - Merkle trees (inclusion proof, not completeness proof)
  - Tamper-evidence (orthogonal to completeness)

Gap A-3 requires an independent mechanism: an expected-event
registry or completeness invariant that is external to the
audit record itself.

This is the structural answer to the second failure mode in
36B-02 OBS-36B-02-2:
  "Event never produced — NOT detectable from audit record alone"

The tamper-evidence family confirms: this is correct.
No tamper-evidence mechanism can detect an event that was
never produced.
```

---

## Section 4 — Family 3: E2E-V Audit Infrastructure

### Source Literature

References already reviewed in 36A. Re-read here from the auditability angle only — not the verifiability angle.

| System | Auditability Mechanism | Family 3 Focus |
|--------|----------------------|----------------|
| ElectionGuard | Encrypted ballot bulletin board; public key ceremony | What records does the bulletin board hold? Who can access it? |
| Helios | Public encrypted ballot record; published tally | What is published vs what is internal? |
| Scantegrity | Paper ballots with codes; public commitment table | What is the externally accessible audit record? |

### 4.1 — What E2E-V Systems Reveal About Audit Infrastructure

**The Bulletin Board Pattern:**

All three E2E-V systems create a public record that is:
- Externally accessible (any party with internet access can read it)
- Immutable after publication (records are published, not modified)
- Cryptographically committed (no silent modification possible)
- Separated from the operational system (the bulletin board is not the ballot box)

The bulletin board is not the system's internal state. It is a dedicated, externally accessible audit artifact created specifically to enable independent verification.

```
E2E-V Audit Architecture:
  Operational system: runs the election, records votes
  Bulletin board: externally accessible, immutable public record
  These are DISTINCT systems serving DISTINCT purposes.
```

### 4.2 — Mandatory Mapping Framework Applications (Family 3)

**Finding B-F-08: Reviewed E2E-V Systems Consistently Use a Dedicated External Artifact**

```
OBS-36B-03-B (applied): The original finding claimed external
  auditability "requires" a dedicated external artifact. That
  overstates the evidence. RLA (Family 1) achieves auditability
  using internal records + auditor access, without a public
  bulletin board. The evidence from this family is:
  "E2E-V systems reviewed consistently use a dedicated external
  artifact" — not "all auditable systems require one."

Literature Finding: ElectionGuard, Helios, and Scantegrity all
  create a dedicated external artifact (bulletin board / public
  record) that is structurally separate from the operational
  system. In these systems, the external artifact is the primary
  mechanism for enabling independent audit. This is a consistent
  pattern within the E2E-V family — not yet a universal requirement.

Domain Evidence: NRNA has internal audit records (ElectionAuditLog,
  VoteRecorded events). These are operational records, not
  externally accessible audit artifacts. Gap A-2.

Audit Concern: Concern A — Governance Audit.
  The reviewed E2E-V family resolves Gap A-2 by creating a
  dedicated external artifact. Family 1 (RLA) resolves it
  differently: internal records + mandated auditor access.
  Whether NRNA's constitutional requirements point toward the
  E2E-V pattern or the RLA pattern is not determined here.

Gap: Gap A-2 is refined by B-F-08.
  The question shifts from "can external parties access internal
  records?" to "what access pattern does NRNA's constitution
  require?" — internal-with-access vs dedicated-external-artifact.
  These are different architectural postures, not a hierarchy.

Vocabulary Check: "Audit record" in E2E-V literature means the
  external public artifact — not the internal system record.
  The bulletin board IS the audit record in this family.
  Internal records serve operational purposes.

Candidate Enhancement: A dedicated external audit artifact
  structurally separate from the operational system.
  Not designed here — identified as implication of B-F-08.
  OBS-36A-08-1 guardrail: this artifact is NOT an extension
  of the Audit context. It is infrastructure. Ownership TBD.

VO-1 Compatibility: E2E-V bulletin boards publish ENCRYPTED
  ballots — individual votes are present but not readable
  without the decryption key. VO-1 requires that voter identity
  cannot be linked to vote content. Encrypted publication is
  one VO-1-compatible pattern. Unencrypted publication of
  vote content linked to voter identity would violate VO-1.
  The external artifact design must respect VO-1.

D39/D43 Dependency: The external tally record is D39-blocked.
  The external governance audit record is NOT D39-blocked.
  These are separate concerns even if implemented as one artifact.
```

**Finding B-F-09: Publication Commitment ≠ Operational Commitment**

```
Literature Finding: In E2E-V systems, there are two distinct
  commitment events:
    (a) Internal commitment — when a vote is recorded in the
        operational system (Vote aggregate equivalent)
    (b) External publication — when the encrypted representation
        is published to the bulletin board

  These happen at different times, potentially by different
  processes, and create different trust guarantees.

Domain Evidence: VoteRecorded (domain event, Round 34A) captures
  internal commitment. No external publication event or mechanism
  discovered.

Audit Concern: Cross-concern.
  Internal vs external commitment distinction affects
  Concerns A and C.

Gap: A new gap not formally numbered in 36B-01:
  No external publication commitment discovered.
  The transition from internal record to external audit record
  is not modeled.

Vocabulary Check: "Committed" in the E2E-V context means
  cryptographically committed and published — both internal
  recording AND external publication. The two-step nature
  is an architectural insight, not a vocabulary distinction.

Candidate Enhancement: An external publication event or process.
  Not designed here.

VO-1 Compatibility: External publication must encrypt or
  anonymize content such that voter identity is not exposable.

D39/D43 Dependency: External tally publication — D39-blocked.
  External governance publication — not blocked.
```

**Finding B-F-10: Immutability of the External Record Is a Constitutional Property**

```
Literature Finding: Helios, ElectionGuard, and Scantegrity all
  treat the external bulletin board as immutable after
  publication. This is not a technical implementation choice —
  it is a constitutional requirement of the audit framework.
  An auditable election is one where "what was published remains
  what was published." Post-publication modification destroys
  the audit guarantee.

Domain Evidence: No external published record discovered.
  If one is created, immutability must be a constitutional
  requirement — not a technical option.

Audit Concern: Concern C — Evidence Integrity.
  The external record's immutability is the primary Concern C
  mechanism in E2E-V systems.

Gap: Gap A-4 (tamper-evidence) extends to: if an external
  record is created, it must be constitutionally immutable.

Vocabulary Check: "Immutable" = cannot be changed after
  publication. This is stronger than "tamper-evident" (which
  only detects modification). E2E-V systems use the stronger
  property.

Candidate Enhancement: Constitutional rule — if an external
  audit artifact is created, it becomes constitutionally
  immutable. Not designed here.

VO-1 Compatibility: Immutability of the external record
  does not affect VO-1 — but the content of what is published
  must be VO-1 compliant at the time of publication.

D39/D43 Dependency: No direct blocker for constitutional
  immutability rule. Applies to any external artifact.
```

### 4.3 — Family 3 Key Finding

Family 3 (E2E-V Audit Infrastructure) refines Gap A-2 without resolving it:

```
B-F-08 REFINES GAP A-2 (OBS-36B-03-B applied):

Before B-F-08: "External parties cannot access NRNA's audit records."
After B-F-08:  "The literature shows two valid patterns for external
               auditability, not one requirement:

  Pattern A (RLA family):     Internal records + mandated auditor access
  Pattern B (E2E-V family):   Dedicated external artifact, structurally
                              separate from operational records

The architectural question for NRNA is not 'must we create a
bulletin board?' — it is 'which access pattern does the NRNA
constitution require?' The literature shows both patterns achieve
external auditability. They do so through different mechanisms.
Cross-family synthesis (36B-04) will compare the two patterns
against the discovered domain model before any pattern preference
is stated."

This is research evidence only.
The choice between patterns is a constitutional question.
The literature establishes that both patterns exist and work.
```

---

## Section 5 — Family 4: Independent Certification

### Source Literature

| Context | Mechanism | Notes |
|---------|-----------|-------|
| ISO/IEC Election Security Standards | Conformance criteria + certification authority | Evidence + Criteria + Authority pattern |
| OSCE/ODIHR Election Observation Methodology | Observation mission reports | Observer as independent authority |
| CoE Recommendation on E-Voting Standards | Certification requirements for e-voting | Formal criteria for electronic elections |
| Academic: Trusted Third Party Certification | Canard et al. (2008) | Cryptographic certification patterns |

### 5.1 — Certification in Election Contexts

Independent certification in election systems follows the pattern identified in 36B-02:

```
Certification requires three elements (36B-02, OBS-36B-02-1):
  Evidence:   what was examined
  Criteria:   what standard was applied
  Authority:  who made the declaration

All three must be present.
Evidence alone = audit result (not certification)
Criteria alone = standard (not certification)
Authority alone = assertion (not certification)
```

The certification literature shows that each element has specific requirements in election contexts:

**Evidence requirements:**
- Must be verifiably complete (cannot certify a partial record)
- Must be tamper-evident (certification over mutable records is invalid)
- Must be independently accessible (certifier cannot rely on certified party for access)

**Criteria requirements:**
- Must be defined before the election (ex-post criteria invalidate the certification)
- Must be public (secret criteria cannot be independently verified)
- Must be specific (general "ran correctly" assertions are not certifiable)

**Authority requirements:**
- Must be independent of the certified party
- Must have defined scope (what they are authorized to certify)
- Must have accountability (their certification can be challenged)

### 5.2 — Mandatory Mapping Framework Applications (Family 4)

**Finding B-F-11: Certification Cannot Occur Without Governance Audit Completion**

```
Literature Finding: ISO/IEC certification standards and CoE
  e-voting recommendations state that an election cannot
  receive a certification declaration unless the governance
  audit (Concern A) has been completed. Certification is
  downstream of audit — it is an attestation that the audit
  found the criteria satisfied.

Domain Evidence: NRNA has GovernanceDecisionSnapshot and
  GovernanceReplayService — evidence of Concern A
  infrastructure. No certification mechanism discovered.

Audit Concern: Concern A — Governance Audit is a precondition
  for certification. Concern B — tally certification blocked by D39.

Gap: No certification mechanism. No criteria defined.
  No authority designated.

Vocabulary Check: "Certification" as used in these standards
  matches 36B-02's definition: requires Evidence + Criteria
  + Authority. The standards explicitly distinguish certification
  from "passing an audit" — an audit produces evidence,
  certification is the authorized declaration that the evidence
  satisfies defined criteria.

Candidate Enhancement: None designed here. Three-element
  certification framework (Evidence/Criteria/Authority) is
  identified as the structure any future certification
  mechanism must satisfy.

VO-1 Compatibility: Certification of "election was conducted
  correctly" does not require voter identity exposure.
  VO-1 compatible if certification is over aggregate/governance
  evidence rather than individual voter records.

D39/D43 Dependency: Tally certification — D39-blocked.
  Governance certification — not blocked.
  D43 — enrollment eligibility certification constrained.
```

**Finding B-F-12: Observer Role ≠ Certification Role**

```
Literature Finding: OSCE/ODIHR methodology distinguishes
  election observers from certification authorities:
    Observer: attests to what was seen (evidentiary role)
    Certifier: declares that criteria were satisfied (authority role)
  The same party can hold both roles, but they are distinct.
  A report by observers is NOT a certification unless it
  includes all three elements: evidence, criteria, authority.

Domain Evidence: No observer or certifier role discovered
  in NRNA's domain model.

Audit Concern: Concern A — Governance Audit.

Gap: No observer or certifier role. Audit Concern A currently
  has no "authorized declaration" mechanism — only evidence
  production (ElectionAuditLog, GovernanceDecisionSnapshot).

Vocabulary Check: "Observer" and "certifier" are used
  interchangeably in common language but are distinct in
  the certification literature. NRNA must establish which
  role is constitutionally required before designing any
  mechanism.

Candidate Enhancement: None. Whether NRNA requires observers,
  certifiers, or both is a constitutional question (D43-adjacent
  for enrollment; unresolved for governance certification).

VO-1 Compatibility: Observer and certifier roles should not
  require access to voter-vote linkage. Observer verifies
  process; certifier declares criterion satisfaction. Both
  can operate on VO-1-compliant evidence.

D39/D43 Dependency: D43 constrains any certification claim
  about eligible voter population (enrollment). Governance
  certification (Concern A) is not directly blocked.
```

**Finding B-F-13: Pre-Election Criteria Publication as Auditability Requirement**

```
Literature Finding: CoE Recommendation on E-Voting and ISO/IEC
  standards require that certification criteria be published
  before the election begins. Ex-post criteria definition
  invalidates the certification regardless of how the election
  ran. "We decided after the election that these are the
  standards we met" is not a certification.

Domain Evidence: 36A-DI-05 (Governance Configuration Freeze,
  CONFIRMED across four sources) establishes that governance
  configuration must be immutable before vote collection.
  Certification criteria publication is a stronger version
  of the same principle: criteria must be published and
  immutable before the election.

Audit Concern: Concern A — Governance Audit.
  Pre-election criteria publication is an extension of
  the Configuration Freeze principle (36A-DI-05) to
  the certification context.

Gap: No discovered criteria publication mechanism.
  The Configuration Freeze (36A-DI-05) is confirmed for
  governance configuration — it may or may not extend to
  audit criteria. This is an open question for 36B-05.

Vocabulary Check: "Criteria" in certification literature
  means the specific, enumerated standards against which
  the election is measured. Not general assertions.

Candidate Enhancement: None. If certification is constitutionally
  required, criteria must be defined and published before
  vote collection. This is a governance constitutional question.

VO-1 Compatibility: Criteria publication has no voter identity
  implication. VO-1 compatible.

D39/D43 Dependency: Tally criteria — D39-blocked.
  Governance criteria — not blocked.
```

### 5.3 — Family 4 Key Finding

Family 4 reveals that certification is not an audit outcome — it is a distinct governance act that follows from audit:

```
Audit → produces evidence
Certification → declares that criteria were satisfied (using that evidence)

NRNA currently has:
  Evidence production mechanisms (ElectionAuditLog, GovernanceDecisionSnapshot)
  No criteria definition
  No certification authority
  No declaration mechanism

The three-element gap (Evidence/Criteria/Authority) is architectural,
not merely procedural. Adding a "certification step" without defining
criteria and authority does not produce certification.
```

### 5.4 — Context Explosion Risk (OBS-36B-03-C)

Family 4 names governance roles: Observer, Certifier, Certification Authority.

```
OBS-36B-03-C — Context Explosion Risk

These are governance roles, not bounded contexts.

The Audit Gravity Well (OBS-36A-08-1) has a twin:
Context Explosion Risk.

Every discovered role is not a bounded context.
Every discovered capability is not an aggregate.
Every discovered concept is not a service.

Round 36B-04 must NOT create:
  Certification Context
  Observer Context
  Random Beacon Context
  Inclusion Proof Context

...from this family evaluation alone.

Creating a bounded context requires:
  - Discovered domain evidence of a consistency boundary
  - An invariant that requires ownership
  - A ubiquitous language that is domain-discovered, not literature-imported

Naming a role ≠ discovering a context.
Naming a mechanism ≠ discovering an aggregate.

If 36B-04 finds evidence that certification or observation
requires its own consistency boundary, that finding must be
presented explicitly, with domain evidence, for ARB review.
It must not emerge silently from the names used in this family.
```

---

## Section 6 — Cross-Family Synthesis

### 6.1 — What All Four Families Agree On

Across Family 1 (RLA), Family 2 (Evidence Chain), Family 3 (E2E-V Infrastructure), and Family 4 (Certification), five properties are required by all four families:

**Property P-1: Complete Record**
All four families require that the audit record be complete before audit can begin. None provides a mechanism to audit a partial record and reach a valid conclusion.

```
Cross-family confirmation: Audit Completeness (Gap A-3)
is not a Concern B (tally) property.
It is a precondition for all three concerns.
```

**Property P-2: Tamper-Evident Record**
All four families require that the audit record be detectable as modified. Statistical audit (F1), hash chains (F2), encrypted publication (F3), and certification evidence requirements (F4) all presuppose tamper-evidence.

```
Cross-family confirmation: Gap A-4 (tamper-evidence)
is a cross-concern structural requirement, not optional infrastructure.
```

**Property P-3: Independent Access**
All four families require that the auditor can access the audit record without requiring cooperation from the party being audited.

```
Cross-family confirmation: Gap A-2 (external auditability)
is a structural requirement, not a configuration choice.
Operator-accessible records are not audit records.
```

**Property P-4: Pre-Audit Criterion Definition**
RLA (risk limit as criterion), certification standards (explicit criteria), and E2E-V systems (election fingerprint / configured parameters) all require that what will be audited against is defined before the election.

```
Cross-family confirmation: This extends 36A-DI-05
(Configuration Freeze) into the certification context.
Criteria must be immutable before vote collection begins.
```

**Property P-5: Independence of Auditor from Operator**
All four families distinguish the auditor (who examines) from the operator (who runs the system). The audit is only meaningful if conducted by a party independent of the operation.

```
Cross-family confirmation: CAH-02 (Independent Verification
Observer, confirmed in 36A-06) holds also from the auditability
angle. Independence is not just a verifiability property —
it is an auditability property.
```

### 6.2 — Where Families Diverge

**On the location of the audit record:**
- F1 (RLA): Record can be internal — if accessible. CVRs are internal records that auditors are given access to.
- F3 (E2E-V): Record is external — a dedicated bulletin board separate from the operational system.
- F2 (Evidence Chain): Neutral — hash chains and Merkle trees can be applied to internal or external records.
- F4 (Certification): Record must be accessible to the certifier — but does not specify internal vs external.

```
Divergence finding: The literature does not uniformly require
an external audit record. F1 accepts internal-but-accessible;
F3 creates a dedicated external record. Which is appropriate
for NRNA is an architectural judgment informed by constitutional
requirements — not determined by literature preference.
```

**On who certifies:**
- F1 (RLA): The audit itself is the certification mechanism — statistical evidence satisfies the stopping criterion.
- F3 (E2E-V): Cryptographic proofs serve as the certification mechanism — mathematical rather than declared.
- F4 (Certification): An explicit, human authority declares certification.

```
Divergence finding: Certification by mathematical proof,
by statistical evidence, and by human authority declaration
are distinct patterns. They address the Authority element
of certification differently.
```

### 6.3 — Vocabulary Consistency Check (Cross-Family)

| Term | F1 Meaning | F2 Meaning | F3 Meaning | F4 Meaning | 36B-02 Meaning | Consistent? |
|------|------------|------------|------------|------------|----------------|-------------|
| Audit | Formal statistical post-election review | Examination of tamper-evident log | Examination of bulletin board | Formal examination against criteria | "Can evidence be inspected?" | MOSTLY — all include inspection |
| Complete | All CVRs present and retrievable | All log entries present (no gaps) | All encrypted ballots published | All criteria-relevant evidence present | Not yet formally defined | CONVERGING on same concept |
| Tamper-evident | CVRs cannot be silently modified | Hash chain detects modification | Encrypted bulletin board is immutable | Evidence must be trustworthy | Gap A-4 (undefined) | CONSISTENT |
| Certification | Stopping criterion met (implicit) | Not addressed | Proof of correct computation (cryptographic) | Authorized declaration: evidence satisfies criteria | Evidence + Criteria + Authority | DIVERGES — 36B-02 definition is the most structured |
| Auditor | Statistical sampling party | Log examination party | Any party with public record access | Designated independent authority | Not yet defined | CONVERGES on independence |

**Vocabulary finding:** The 36B-02 Certification definition (Evidence + Criteria + Authority) is the most structured across the literature. The families use "certification" loosely. The 36B-02 definition should be treated as the governing vocabulary for NRNA — tighter than the literature's usage.

---

## Section 7 — NRNA Applicability Assessment

### 7.1 — What Is Applicable Now (Within Active Constraints)

| Finding | Concern | Applicability | Constraint |
|---------|---------|---------------|------------|
| B-F-01: Completeness is a precondition | A/B/C | APPLICABLE (A, C) | B — D39-blocked |
| B-F-02: Auditor access ≠ operator access | A | APPLICABLE | None |
| B-F-03: Tamper-evidence is an audit infrastructure requirement | C | APPLICABLE | None |
| B-F-05: Tamper-detection vs tamper-prevention distinction | C | APPLICABLE | None |
| B-F-06: Inclusion proofs enable selective auditability | A | APPLICABLE | None |
| B-F-07: Completeness cannot be proven by inclusion alone | A/B/C | APPLICABLE (A, C) | B — D39-blocked |
| B-F-08: External auditability requires a dedicated external artifact | A | APPLICABLE | None (tally artifact — D39-blocked) |
| B-F-10: External record immutability is constitutional | A/C | APPLICABLE (if artifact created) | None |
| B-F-11: Certification requires Concern A completion | A | APPLICABLE | None |
| B-F-12: Observer ≠ Certifier | A | APPLICABLE | None |
| B-F-13: Pre-election criteria publication | A | APPLICABLE | None |

### 7.2 — What Is Deferred

| Finding | Blocked By | Deferred Evidence Statement |
|---------|------------|----------------------------|
| B-F-04: Trusted randomness for sampling | D39 | If statistical tally audit is constitutionally required, a public random beacon becomes infrastructure. Design deferred pending D39. |
| B-F-09: External publication commitment (tally component) | D39 | Tally publication transition from internal to external record deferred pending D39. Governance publication not blocked. |
| B-F-11: Tally certification | D39 | Tally certification requires tally ownership. Design deferred pending D39. |
| Concern B applicability generally | D39 | All Concern B application findings are deferred evidence — recorded for use at Round 36E and post-D39. |

### 7.3 — Updated Concept Status Table (Partial — for 36B-04 use)

Based on Family 1–4 findings, the following concept status changes are candidates for 36B-04 cross-family analysis:

| Concept | 36B-02 Status | Post-36B-03 Evidence | Status Candidate |
|---------|--------------|----------------------|------------------|
| Audit Trail | DISCOVERED (HIGH) | No change — confirmed foundation | CONFIRMED |
| Audit Completeness | NOT DISCOVERED | B-F-01, B-F-07: requires external completeness invariant | NOT DISCOVERED → PRECISELY DEFINED GAP |
| Tamper-Evidence | NOT DISCOVERED | B-F-03, B-F-05: cross-family requirement; detection vs prevention distinct | ELEVATED PRIORITY |
| External Auditability | NOT DISCOVERED | B-F-02, B-F-08: requires dedicated external artifact, not just access | REDEFINED |
| Certification | NOT DISCOVERED | B-F-11, B-F-12, B-F-13: three-element gap confirmed | PRECISELY DEFINED GAP |
| Inclusion Proof | NOT IN 36B-02 | B-F-06: new concept — selective auditability | CANDIDATE ADDITION |
| Pre-Audit Criteria | NOT IN 36B-02 | B-F-13: extension of 36A-DI-05 into certification context | CANDIDATE ADDITION |

---

## Section 8 — Structural Observation for ARB Review

The four-family evaluation surfaces one structural observation requiring ARB attention:

**OBS-36B-03-1 — Gap A-3 (Completeness) Confirmed Irreducible**

Families 1, 2, 3, and 4 independently confirm that audit completeness cannot be resolved by:
- Tamper-evidence mechanisms (Family 2, B-F-07)
- Inclusion proofs (Family 2, B-F-07)
- Statistical sampling (Family 1 — assumes completeness, doesn't establish it)
- Certification (Family 4 — requires completeness as input, doesn't create it)

Gap A-3 requires an independent mechanism: a completeness invariant or an expected-event registry that exists outside the audit record itself.

The literature confirms the gap is real and explains why it cannot be resolved by any single mechanism within the other four families. Gap A-3 is structurally irreducible — it requires its own architectural response.

**OBS-36B-03-2 — Gap A-2 (External Auditability) Refined (not resolved) by B-F-08**

The four-family evaluation refines Gap A-2. The literature does not uniformly require a dedicated external artifact — Family 1 (RLA) achieves external auditability through internal-records-plus-mandated-auditor-access. Family 3 (E2E-V) uses a dedicated external artifact.

The architectural question shifts: not "how do we grant access to internal records?" but "which access pattern does NRNA's constitution require, and who owns it?" The two patterns (access vs artifact) are now documented evidence for 36B-04 cross-family synthesis.

OBS-36A-08-1 guardrail applies to both patterns: neither the audit artifact nor the auditor access mechanism is an extension of the Audit context's scope. Ownership is a 36B-05 question.

**OBS-36B-03-3 — Three Distinct Constitutional Capabilities Emerging**

Across all four families, a three-way distinction is becoming visible:

```
OBS-36B-03-3

Three distinct constitutional capabilities are emerging
from the Round 36B literature:

  Auditability:     Can we inspect the evidence?
                    (Is the record complete, accessible, tamper-evident?)

  Verifiability:    Can we verify the claims?
                    (Is the tally correct? Was the vote recorded as cast?)

  Certification:    Can we accept the claims?
                    (Has an authorized party declared criteria satisfied?)

The literature increasingly treats these as distinct constitutional questions.
Round 36B-04 must determine whether NRNA also treats them as distinct —
or whether its constitution collapses them into one requirement.

A system can be auditable without being verifiable.
A system can be verifiable without being certifiable.
A system can be certified without the certification being meaningful
  if the evidence is incomplete or the criteria are undefined.

This distinction is more architecturally significant than:
  hash chains, Merkle trees, bulletin boards, random beacons.

Round 36B-04 must test whether NRNA requires all three,
two, or only one of these capabilities — as a constitutional
question, not a design preference.

The answer constrains Round 36E (Architecture Impact Assessment)
and Round 37 (ADR Authoring) more than any specific mechanism.
```

---

## Section 9 — Findings Summary for 36B-04

Round 36B-04 (Cross-Family Pattern Extraction) should take the following as its input:

**Confirmed Cross-Family Properties (P-1 through P-5):**
1. P-1: Completeness is a universal audit precondition
2. P-2: Tamper-evidence is a universal structural requirement
3. P-3: Independent access is a universal requirement
4. P-4: Pre-audit criterion definition is a universal requirement
5. P-5: Auditor independence is a universal property (extends CAH-02 into auditability)

**Key Divergences for 36B-04 Analysis:**
- Internal vs external audit record location (not uniformly required to be external)
- Cryptographic vs statistical vs declarative certification (three distinct patterns)

**Gaps Precisely Defined:**
- Gap A-3: Irreducible completeness gap — requires its own mechanism (cannot be solved by tamper-evidence or inclusion proofs)
- Gap A-2: Access-pattern gap — two valid literature patterns (internal+access vs dedicated external artifact); constitutional requirement determines which

**Concept Additions for 36B-02 Update:**
- Inclusion Proof (new concept — selective auditability)
- Pre-Audit Criteria (extension of 36A-DI-05 into certification context)

**D39-Deferred Evidence Bundle (carry to 36E):**
- B-F-04: Random beacon requirement
- B-F-09: External publication commitment (tally component)
- Statistical tally audit applicability (entire Concern B)

---

## ARB Decision

```
Round 36B-03 — Auditability Literature Family Evaluation

APPROVED WITH OBSERVATIONS

Research Discipline:      EXCELLENT
Governance Discipline:    EXCELLENT
DDD Discipline:           VERY GOOD
Architecture Neutrality:  GOOD (improved to VERY GOOD after OBS-36B-03-B applied)
Confidence:               HIGH

Corrections applied:
  OBS-36B-03-A: "Concern B has only one STATISTICAL family" — E2E-V
                tally-audit capabilities from 36A acknowledged; D39
                blocks application of all Concern B literature alike.

  OBS-36B-03-B: B-F-08 weakened from "requires" to "consistently uses."
                Gap A-2 now documented as an access-pattern gap with
                two valid literature patterns (RLA internal+access vs
                E2E-V dedicated external artifact). Constitutional
                requirement determines which.

  OBS-36B-03-C: Context Explosion Risk warning added (Section 5.4).
                Observer and Certifier are governance roles, not
                bounded contexts. 36B-04 must not silently promote
                literature roles to DDD contexts.

  OBS-36B-03-3: Three distinct constitutional capabilities documented
                (Section 8): Auditability/Verifiability/Certification.
                The literature increasingly treats these as distinct
                constitutional questions. 36B-04 must determine whether
                NRNA does also.

Round 36B-03: APPROVED
Round 36B-04 (Cross-Family Pattern Extraction): AUTHORIZED

Round 36B-04 mandatory instructions (ARB binding):
  1. Do NOT promote architecture.
  2. Do NOT create new bounded contexts.
  3. Test whether Auditability, Verifiability, and Certification
     are truly separate constitutional capabilities in NRNA —
     this question is more important than any specific mechanism.
```
