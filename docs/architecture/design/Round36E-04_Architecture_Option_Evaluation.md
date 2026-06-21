# Round 36E-04 — Architecture Option Evaluation

**Program:** NRNA DDD Trustworthiness Research Program  
**Series:** 36E — Architecture Impact Assessment  
**Sub-Round:** 36E-04 of 05  
**Status:** APPROVED WITH REQUIRED REVISIONS — APPROVED  
**Governing Question:** Given the constitutional discoveries and DDD impact assessment, what architectural options remain viable for each major pressure area?

**Predecessors:**
- 36E-01: 30 Architectural Constraints — APPROVED
- 36E-02: Constraint Interaction Analysis — APPROVED
- 36E-03: DDD Impact Assessment — APPROVED

**Binding Guardrail:** This round evaluates options. This round does NOT select options. No recommendation is made. No winner is declared. Option selection belongs to Round 37 ADRs.

---

## Section 1 — Evaluation Framework

### 1.1 Evaluation Template

Every option in this round is evaluated using the following structure:

```
Pressure Area or Open Question
        ↓
Candidate Options
        ↓
Constraint Satisfaction Analysis
  — Which ACs does this option satisfy?
  — Which ACs does this option leave unsatisfied or stressed?
        ↓
DDD Impact Analysis
  — What does this option require of the existing DDD model?
  — What existing model elements are compatible, under pressure, or in significant gap?
        ↓
Governance Impact Analysis
  — What constitutional authority structure does this option require?
  — What does it assume about the organizational governance model?
        ↓
Trustworthiness Impact Analysis
  — How does this option address the threat classes from 36C?
  — What trustworthiness properties does it strengthen or weaken?
        ↓
Tradeoff Catalog
  — Strengths under this option
  — Weaknesses under this option
  — Constraints stressed (not violated — options are not ruled out)
```

### 1.2 Binding Prohibitions

The following statements do not appear in this document:

- "Therefore we should create Context X"
- "Therefore we need Service Y"
- "Therefore we choose Architecture Z"
- "Therefore Option B is best"
- Any statement implying a winner

Evaluation results in a tradeoff catalog. Selection results in an ADR. Those are different documents.

### 1.3 Open Questions Driving This Round

From 36E-03:

| OQ | Concern |
|---|---|
| OQ-03-01 | EH-01: does verifier independence require independent reference standards? |
| OQ-03-02 | EC-01: can challengeability and receipt-freeness coexist? |
| OQ-03-03 | ET-03: is audit scope authority a specialization of or separate relationship within D43-AUDIT? |
| OQ-03-04 | CPR-01: what is the form of constitutionally independent authority relationships? |
| OQ-03-05 | Authority relationships in the DDD model: what modeling approach is viable? |

### 1.4 Governing Constitutional Discipline

OBS-36D-02-1 (binding throughout): Authority ≠ Context. Authority boundaries and context boundaries are evaluated independently. No option is accepted or rejected on the grounds that it "requires a new context" — that is a design decision, not a constraint evaluation.

CF-05-19 (binding throughout): Constitutionally independent authority relationships appear necessary. The architectural realization of that independence remains unresolved. No option in this round is selected on the basis that it most naturally maps to a known architectural pattern.

---

## Section 2 — OQ-03-01: EH-01 (Verifier Independence)

**Question:** Does constitutional evidence authenticity verification require: (A) an independent verifier only, (B) an independent verifier plus an independent reference standard, or (C) an alternative trust mechanism that satisfies both concerns differently?

**Context:** EH-01 was promoted to Supported Hypothesis in 36E-03. The DataChecksum circularity case demonstrated that the current concept is insufficient — the reference standard for authenticity verification is held by the same system that produces the evidence. However, alternative architectures may address this concern without requiring a fully independent reference standard.

---

### Option A — Independent Verifier Only

**Description:** An external party is constitutionally authorized to verify election evidence. The external party uses the election system's own DataChecksum (VO-2) and VoteRecorded events as the reference standard for authenticity verification. The independence is achieved by the VERIFIER being external, not by the reference standard being external.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-02 (no self-verification) | Stressed | The reference standard is system-produced; the verifier is external; whether self-verification is eliminated depends on whether the reference can be tampered independently |
| AC-15 (external access) | Requires realization | External verifier needs access pathway — not yet modeled |
| AC-29 (presence ≠ authenticity) | Partially addressed | External verifier can distinguish the two concerns; the reference standard circularity remains |
| IR-H (independent of audited subject) | Partial | Verifier is independent; verifier's reference is not |

#### DDD Impact Analysis

The Vote aggregate already generates DataChecksum (VO-2) and ReceiptHash (VO-3). VRC (D42B) provides the verifier boundary. The additional requirement: AC-15 access pathway from VRC to external parties. Existing aggregates are compatible with Option A. DataChecksum as a value object requires no change. The gap: the reference standard circularity means that if an adversary tampers with evidence AND the associated DataChecksum in the same operation, the external verifier cannot detect the tampering.

#### Governance Impact Analysis

Option A requires only that an external party is constitutionally designated as the verifier. This is compatible with most organizational governance models — an independent observer or oversight body with access rights. It does not require the external party to have contributed to the reference standard generation.

#### Trustworthiness Impact Analysis

Threat class TC-2 (Fabrication): Option A reduces the ability to fabricate evidence undetected ONLY if the DataChecksum is cryptographically anchored such that simultaneous tampering of evidence AND checksum is computationally infeasible. If the checksum and evidence are stored in the same system without external anchoring, TC-2 fabrication may remain undetected by an independent verifier using the system's own reference.

Threat class TC-1 (Suppression): Option A does not address evidence suppression — the external verifier can only check what the system exposes, not whether the full set of evidence has been exposed.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional completeness | Incomplete — reference standard circularity not resolved |
| DDD compatibility | High — no new model elements required beyond AC-15 access pathway |
| Governance complexity | Low — requires external verifier designation only |
| Trustworthiness against TC-2 | Medium — depends on cryptographic properties of DataChecksum |
| Trustworthiness against TC-1 | Low — completeness gap (Gap A-3) unaddressed |
| EH-01 satisfaction | Satisfies the independent verifier claim; does NOT address the independent reference standard claim |

---

### Option B — Independent Verifier + Independent Reference Standard

**Description:** Both the verifying entity and the reference standard against which verification is performed are constitutionally independent of the election system. The reference standard is produced through a process that the election system cannot unilaterally control — either externally generated, externally anchored, or produced through a multi-party process.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-02 (no self-verification) | More fully satisfied | Both the verifier and the reference are external to the system; self-verification cycle is broken |
| AC-15 (external access) | Requires realization | External access pathway required for both evidence AND reference standard |
| AC-29 (presence ≠ authenticity) | Addressed at principle | Separate verification of presence (evidence exists) and authenticity (evidence is correct against independent reference) is architecturally separable |
| IR-H | Satisfied in principle | Both verifier and reference are independent of the audited subject |

#### DDD Impact Analysis

Option B requires that the Vote aggregate's evidence generation process be connected to an external reference anchoring mechanism. DataChecksum (VO-2) is compatible as an evidence integrity value — but the reference anchor (what the DataChecksum is checked against) must be generated or committed externally. ReceiptHash (VO-3) is a candidate mechanism if receipts are delivered to voters independently of the system's own record — voter-held receipts could serve as an independent reference for individual vote authenticity. This connection (VO-3 as independent reference anchor) is unexplored in the current model.

#### Governance Impact Analysis

Option B requires that the reference standard generation process is constitutionally governed. Who controls the external reference? What is the L-1 legitimacy source for the reference standard authority? This creates a potential secondary D43-like gap — the reference standard authority may need its own L-1/L-5 specification. This is not yet a new D43 instance (no new instance is created in this round) — it is a structural observation about what Option B implies.

**ARB Discipline Note:** Mentioning a governance authority (reference standard authority) in this section does not create a new D43 instance. A D43 threshold assessment is required separately before any new instance is recognized.

#### Trustworthiness Impact Analysis

Threat class TC-2 (Fabrication): Option B significantly reduces TC-2 risk. If a reference standard exists independently of the evidence, simultaneous tampering with evidence AND reference requires compromising two independent systems — a higher constitutional bar.

Threat class TC-4 (Certification Abuse): If certification relies on independent reference validation, the reference independence strengthens certification's constitutional basis.

TC-3 (Governance Manipulation): The reference standard generation process itself becomes a governance target — it must be protected by its own constitutional legitimacy structures.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional completeness | Higher — reference standard circularity resolved in principle |
| DDD compatibility | Medium — requires reference anchoring mechanism connected to existing VOs |
| Governance complexity | Higher — reference standard authority requires constitutional grounding |
| Trustworthiness against TC-2 | High — simultaneous tampering requires compromising two independent systems |
| Trustworthiness against TC-1 | Medium — completeness gap (Gap A-3) still requires external scope definition |
| EH-01 satisfaction | Satisfies both derived and hypothesized claims of EH-01 |

---

### Option C — Cryptographic Commitment Mechanism

**Description:** The election system commits to evidence through a cryptographic commitment scheme (e.g., hash chain, Merkle tree, or ballot commitment protocol) at the time of vote recording. *(These are example mechanisms only — not candidate selections. Specific technology choices belong to Round 38 or later.)* The commitment is published externally before vote content is revealed. Post-election, any external party can verify that the published commitment matches the revealed evidence without the system being able to modify its commitment retroactively.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-02 (no self-verification) | Depends on commitment publication | If commitment is published to an external, independently verifiable location, the reference standard is constitutionally anchored outside the system |
| AC-29 (presence ≠ authenticity) | Potentially well-addressed | Commitment schemes can separate inclusion proof (presence) from content proof (authenticity) |
| VO-1 (anonymity) | Under scrutiny | Some commitment schemes reveal vote content as part of the commitment protocol — compatibility with VO-1 must be evaluated |
| Receipt-freeness (EC-01) | Stressed | Some E2E-V commitment schemes enable voters to construct receipts from their commitments — EC-01 tension applies directly |

#### DDD Impact Analysis

Option C creates a significant interaction with VO-3 (ReceiptHash). The receipt hash is currently generated by the Vote aggregate at recording time. A cryptographic commitment scheme would require that: (a) the commitment is generated by the Vote aggregate, (b) the commitment is published externally before the election closes, and (c) post-election verification can check individual votes against the published commitment. ReceiptHash (VO-3) is potentially the individual commitment mechanism; the aggregate commitment structure (Merkle root or equivalent) is not yet modeled. The D42B boundary (VRC) may be the natural location for commitment publication and verification, but its design is deferred.

#### Governance Impact Analysis

Option C requires that the commitment publication process is governed — who controls the public commitment bulletin board? If the election system publishes its own commitments to its own infrastructure, Option C degenerates into Option A (system controls both evidence and reference). True independence requires that the published commitment is received and held by an independent party. The governance question: who constitutionally holds the commitment publication record?

**ARB Discipline Note:** Mentioning a commitment holder or bulletin board authority in this section does not create a new D43 instance. A D43 threshold assessment is required separately before any new instance is recognized.

#### Trustworthiness Impact Analysis

Threat class TC-2 (Fabrication): Cryptographic commitment schemes are specifically designed to prevent post-hoc evidence fabrication. If implemented correctly, Option C provides strong TC-2 protection.

Threat class TC-1 (Suppression): Commitment schemes that include ALL evidence (universal commitment) can address completeness — the published commitment would not match a reduced evidence set. This is a potential path toward Gap A-3 resolution.

VO-1 interaction: Some commitment schemes require revealing partial information about ballot content during verification. This must be evaluated against VO-1 (the most critical invariant in the model). Any option that weakens VO-1 is constitutionally impermissible regardless of other benefits.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional completeness | Potentially high — if commitment is externally anchored |
| DDD compatibility | Medium — ReceiptHash (VO-3) is a building block; aggregate commitment structure is new |
| Governance complexity | High — commitment publication authority requires constitutional grounding |
| Trustworthiness against TC-2 | High — cryptographic binding |
| Trustworthiness against TC-1 | Medium-High — depends on whether commitment covers full evidence set |
| VO-1 compatibility | Requires explicit evaluation — some commitment schemes create VO-1 risk |
| EH-01 satisfaction | Potentially full satisfaction if commitment is externally anchored |

---

### OQ-03-01 Tradeoff Summary (No Selection)

| Dimension | Option A | Option B | Option C |
|---|---|---|---|
| EH-01 derived claim | Satisfies | Satisfies | Satisfies |
| EH-01 hypothesized claim | Does not satisfy | Satisfies | Conditionally satisfies |
| DDD compatibility | High | Medium | Medium |
| Governance complexity | Low | High | High |
| TC-2 strength | Medium | High | High |
| VO-1 risk | Low | Low | Requires assessment |
| Governance authority required | External verifier | External verifier + reference authority | External commitment holder |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** Which option's tradeoffs are acceptable given organizational governance constraints and constitutional requirements?

---

## Section 3 — OQ-03-02: EC-01 (Challengeability vs Receipt-Freeness)

**Question:** Can individual challenge addressability (AC-08 applied to voting decisions) and receipt-freeness coexist in an architecture that preserves VO-1 (anonymity)?

**Context:** EC-01 was classified as an unresolved tension in 36E-02. The tension: if vote recording decisions are individually addressable for challenge, a voter may use that addressability as a receipt proving how they voted. This could enable vote coercion. The history of E2E-V systems demonstrates serious attempts to satisfy both simultaneously; conflict status is unproven.

**Important scope note:** AC-08 applies to all authority decisions. The receipt-freeness tension arises specifically when AC-08 is applied to vote CONTENT decisions. The same tension does not arise when AC-08 is applied to enrollment, verification, or governance decisions — those challengeable decisions do not reveal vote content. This scope distinction is critical for option evaluation.

---

### Option A — Separated Challenge Domains (Vote Existence vs Vote Content)

**Description:** Challenge addressability is applied only to non-content authority decisions: verification decisions (VerificationGranted/VerificationRevoked), enrollment decisions, and eligibility determinations. Vote content itself (which candidates were selected) is not individually challengeable for receipt-freeness reasons. A voter can challenge "was I allowed to vote?" but not "how was my vote recorded?"

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-08 applied to non-vote decisions | Satisfiable | Verification decisions, enrollment, governance transitions: all challengeable without receipt risk |
| AC-08 applied to vote recording | Partially satisfied | Vote existence (was a vote recorded?) can be challenged without revealing content; vote content cannot be challenged individually |
| VO-1 (anonymity) | Compatible | Vote content remains unlinkable; challenge pathway does not expose content |
| Receipt-freeness | Compatible | Vote content is not individually addressable |

#### DDD Impact Analysis

The current Verification aggregate's append-only model (TA-3) can coexist with a challenge pathway for verification decisions if challenge is modeled as a non-mutating event alongside the append-only record. The Vote aggregate's structure is compatible with Option A — VoteRecorded events confirm vote existence; vote content is not individually challengeable. Eligibility Context's pull-over-push model is compatible — eligibility decisions (was I found eligible?) can be challenged; the resulting vote content is not.

#### Governance Impact Analysis

Option A requires that the election's constitutional framework explicitly designates which decisions are challengeable and which are not. The ElectionConstitution (existing candidate L-1 signal) must specify these domains. The organizational governance model must support challenge mechanisms for non-vote decisions (verification panels, eligibility review boards).

#### Trustworthiness Impact Analysis

Threat class TC-4 (Certification Abuse): Option A allows certification to be challenged on grounds of improper eligibility or enrollment decisions — the most common governance-level disputes. It does not allow certification to be challenged on grounds of individual vote manipulation — which requires TC-2 protections (evidence integrity) rather than individual challenge mechanisms.

Coercion risk: Option A does not introduce receipt-freeness risk. A voter cannot prove they voted for a specific candidate (VO-1 preserved). However, a voter CAN potentially prove they voted at all (existence acknowledgment) — whether this creates coercion risk depends on whether voting participation itself is sensitive.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Receipt-freeness preservation | Full — vote content not individually addressable |
| Challengeability coverage | Partial — non-content decisions challengeable; vote content not |
| VO-1 compatibility | Compatible |
| AC-08 full satisfaction | Partial — vote recording authority decisions are not fully addressable |
| Governance complexity | Moderate — requires clear domain distinction in constitutional framework |

---

### Option B — Individual Verifiability Without Receipt Construction

**Description:** Voters can individually verify their vote was correctly counted, but the verification mechanism is designed to prevent the voter from constructing a proof that can be shown to a third party. This is the approach attempted in several E2E-V systems. The voter knows their vote was counted correctly; they cannot prove to a coercer what they voted for.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-08 (challengeability) | Potentially satisfied | A voter who believes their vote was miscounted can initiate a challenge — the challenge mechanism is individual |
| VO-1 (anonymity) | Under pressure | Voter knows their own vote content through the verification process; the constitutional concern is whether that knowledge becomes provable to third parties |
| Receipt-freeness | Design-dependent | Whether receipt-freeness is achieved depends on specific cryptographic/procedural construction; not achievable in all designs |

#### DDD Impact Analysis

Option B requires a verification mechanism that provides individual feedback to voters without producing an externally verifiable proof. ReceiptHash (VO-3) is currently a candidate building block. However, ReceiptHash as currently modeled provides a hash that the voter can use to confirm their vote was recorded — this is existential confirmation, not content confirmation. Whether it can be extended to content confirmation without enabling receipt construction depends on the specific protocol, which belongs to design (Round 38+).

VRC (D42B) is the natural boundary for the voter-facing verification mechanism. Option B's requirements on VRC are more complex than a simple access pathway — VRC must provide individual feedback without enabling receipt construction.

#### Governance Impact Analysis

Option B requires constitutional protection of the verification mechanism's privacy properties. The organizational governance model must ensure that the verification process cannot be monitored in ways that effectively convert individual verifiability into receipt construction. This requires not only technical design but also governance procedures around how challenges are processed and logged.

#### Trustworthiness Impact Analysis

Threat class TC-2 (Fabrication): If a voter can verify their vote was correctly counted, individual vote fabrication becomes detectable by the voter. This strengthens TC-2 protections significantly.

Threat class TC-1 (Suppression): If a voter can confirm vote existence, vote suppression becomes detectable by the voter.

Coercion risk: If Option B is implemented correctly and receipt-freeness is preserved, coercion risk is low — a coercer cannot verify the voter's claimed vote. If Option B is implemented incorrectly and receipts can be constructed, coercion risk increases.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Receipt-freeness preservation | Conditional — depends on specific implementation correctness |
| Challengeability coverage | Broader than Option A — vote content challenges possible |
| VO-1 compatibility | Under pressure — requires careful design |
| TC-2 protection | High — individual vote manipulation detectable |
| DDD compatibility | Medium — VRC design becomes significantly more complex |
| Design risk | High — implementation correctness is critical and difficult to verify |

---

### Option C — Aggregate Verifiability Only

**Description:** Individual vote content decisions are not challengeable. Instead, the electoral result as a whole is verifiable — auditors can confirm that the tally correctly reflects the submitted ballots, and that the set of submitted ballots satisfies eligibility constraints. No individual voter can verify their specific vote.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-08 (challengeability of individual vote decisions) | Not satisfied | Individual vote content is not addressable |
| Receipt-freeness | Fully satisfied | No individual vote linkage exists in any accessible form |
| VO-1 (anonymity) | Fully satisfied | No voter-vote linkage created or exposed |
| Audit verifiability (36B) | Potentially strong | Aggregate verification is the approach of RLA (Risk Limiting Audits) |

#### DDD Impact Analysis

Option C is most compatible with the existing DDD model. Vote aggregate's current design — anonymous, content-only, no voter linkage — is precisely what Option C requires. Audit Context's aggregate observation model is compatible. No changes to existing aggregates are required to achieve Option C's property; Gap A-3 and external scope definition remain open.

#### Governance Impact Analysis

Option C shifts the verification burden from individual voters to auditors. This is the organizational model of most real elections today — auditors verify the tally process, not individual voters. The ElectionConstitution must define what auditors have constitutional authority to verify. This connects directly to D43-AUDIT and AC-14 (external audit scope).

#### Trustworthiness Impact Analysis

Threat class TC-2 (Fabrication): Individual vote fabrication is detectable only at the aggregate level — if enough votes are fabricated to affect the outcome, an auditor could detect it statistically. Individual vote manipulation below the detection threshold may be undetectable.

Coercion risk: Eliminated — no mechanism exists for a voter to prove their vote content.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Receipt-freeness preservation | Full |
| Challengeability coverage | Lowest — individual votes not addressable |
| VO-1 compatibility | Full |
| TC-2 protection | Statistical only |
| DDD compatibility | Highest — fully compatible with current model |
| Governance model | Matches existing electoral practice |

---

### OQ-03-02 Tradeoff Summary (No Selection)

| Dimension | Option A | Option B | Option C |
|---|---|---|---|
| AC-08 vote content satisfaction | Partial | Potentially full | Not satisfied |
| VO-1 compatibility | Full | Under pressure | Full |
| Receipt-freeness | Full | Conditional | Full |
| TC-2 protection | Non-content only | Full (if correct) | Statistical |
| Coercion risk | Low | Low (if correct) | Minimal |
| DDD compatibility | High | Medium | Highest |
| Implementation risk | Low | High | Low |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** Which combination of challenge scope and verification depth is constitutionally required, and which is constitutionally optional?

**Note for Round 37:** EC-01's conflict status remains unproven. The coexistence models in Options A and B provide candidate resolutions. Option A avoids the tension by scope separation. Option B addresses it directly but with design risk. Neither proves the conflict is Type E (irresolvable) or Type D (resolvable).

---

## Section 4 — OQ-03-03 / CPR-02: Audit Scope Authority

**Question (ET-03):** Is audit scope authority a specialization of D43-AUDIT (one entity covers both scope definition and audit execution), or a separate authority relationship inside D43-AUDIT (requiring two constitutionally independent relationships)?

**Question (CPR-02):** What options exist for constitutionally grounding the audit scope authority that closes Gap A-3?

---

### Option A — Unified D43-AUDIT Authority (Scope Definition + Execution)

**Description:** A single constitutionally grounded audit authority holds both the mandate to define what must be audited (scope) and the mandate to execute the audit. Scope definition is an internal function of the audit authority, not a separate constitutional mandate.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-14 (scope from outside election system) | Satisfied | Audit authority is outside election system; scope definition is an internal function of that authority |
| AC-16 (scope authority ≠ execution authority boundary) | Potentially stressed | AC-16 says these cannot SHARE an architectural authority boundary — if scope and execution are held by the same entity, they share a boundary |
| AC-24 (IR-H for audit) | Partially addressed | External, independent audit authority satisfies IR-H; scope definition circularity (can the authority define its own scope?) is a secondary concern |

#### Governance Impact Analysis

Option A is compatible with traditional audit governance — an independent audit body (external auditors, oversight committee) defines what they will audit and then executes the audit. This is standard organizational practice. The constitutional concern: does the unified authority have sufficient independence from the election system (IR-H)? Yes. Does the unified authority have self-scoping risk (it defines its own scope, potentially narrowly)? Potentially.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| AC-16 satisfaction | Stressed — scope and execution share authority boundary |
| Organizational familiarity | High — matches traditional audit model |
| Gap A-3 closure | Depends on constitutional mandate strength |
| Self-scoping risk | Present — audit authority could strategically narrow its own scope |
| Independence requirement | Must hold IR-H; typically requires organizational separation from election system |

---

### Option B — Separated Scope Definition and Audit Execution

**Description:** Two constitutionally independent authority relationships within D43-AUDIT: one authority defines what must be audited (scope authority), a different authority executes the audit (audit authority). The scope authority defines Gap A-3's expected evidence set; the audit authority verifies against it.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-14 (scope from outside) | Satisfied | Scope authority is constitutionally independent from election system |
| AC-16 (scope ≠ execution boundary) | Satisfied | Two distinct authority relationships; different constitutional mandates |
| AC-17 (external expected evidence set mechanism) | Satisfied | Scope authority produces the expected evidence set that AC-17 requires |
| AC-24 (IR-H) | Stronger — both authorities are independent | |

#### Governance Impact Analysis

Option B requires that the organization can constitutionally designate TWO independent bodies: one that defines what must be evidenced (potentially a constitutional drafting body, a regulatory authority, or a membership-ratified standards committee) and one that verifies that the evidence is present and authentic. This is more complex than Option A but closes the AC-16 gap.

The scope-defining body may be constituted infrequently (per election cycle, per constitutional revision) while the audit execution body operates per election. This temporal separation may be the natural governance model.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| AC-16 satisfaction | Full |
| Gap A-3 closure | Strong — scope authority explicitly produces expected evidence set |
| Self-scoping risk | Eliminated |
| Organizational complexity | Higher — requires two distinct constitutional authorities |
| Constitutional grounding | Requires L-1/L-5 for both authorities |

---

### Option C — Delegated Scope Definition (ElectionConstitution as Scope Mandate)

**Description:** The ElectionConstitution (existing candidate L-1 signal from 36E-03) defines audit scope as part of the constitutional document. The audit execution authority is constitutionally independent. Scope definition is constitutionally ratified once (per constitutional cycle) rather than operationally defined per election.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-14 (scope from outside) | Satisfied | Constitutional document is the scope mandate; outside the operational election system |
| AC-16 (scope ≠ execution) | Satisfied | Constitutional document is the scope source; audit body is the execution authority |
| AC-23 (criteria ratification) | Compatible | Ratification process for ElectionConstitution includes scope definition |
| AC-30 (precision) | Requires discipline | Constitutional document must be sufficiently precise to constitute an expected evidence set |

#### Governance Impact Analysis

Option C is aligned with the existing ElectionConstitution reference in GovernanceState. It leverages the strongest existing constitutional legitimacy signal in the model. It requires that ElectionConstitution authorship and ratification are constitutionally grounded — which connects to the criteria ratification question (AC-23) and the membership ratification pattern identified in 36D.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Alignment with existing model | Strong — ElectionConstitution reference already exists |
| Gap A-3 closure | Strong in principle — constitutional document defines expected evidence |
| Update flexibility | Lower — scope changes require constitutional amendment |
| Governance complexity | Compatible with existing membership ratification pattern |
| Precision requirement | High — constitutional document must be precise enough to constitute audit scope (AC-30 applies) |

---

### OQ-03-03 Threshold Determination (carried from 36E-03)

Based on the option analysis:

**ET-03 specialization vs separate relationship:**

Option A treats scope as a specialization of D43-AUDIT (one unified authority). Option B treats scope as a separate authority relationship within D43-AUDIT. Option C treats scope as an ElectionConstitution function, with a separate execution authority.

All three options distinguish scope from execution to some degree. The constitutional question — whether this distinction requires two separate authority RELATIONSHIPS or is merely a functional distinction within one relationship — is not resolved by the option analysis alone. It is a governing principle question: does AC-16 require separate constitutional mandates, or only separate functional roles within one mandate?

**Determination:** The option analysis confirms that both structurings (one unified authority with scope function, and two separate authority relationships) are architecturally feasible. The constitutional sufficiency of Option A (unified) depends on the interpretation of AC-16. This is an ADR-level decision. Carry to Round 37.

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

---

## Section 5 — OQ-03-04 / CPR-01: Form of Constitutionally Independent Authority Relationships

**Question:** When AC-05 requires "at least one constitutionally independent authority relationship per D43 function," what architectural form can that independence take?

**Governing constraints:** CF-05-19 (realization unresolved), OBS-36D-02-1 (authority ≠ context). The following options are evaluated without concluding which form is required.

---

### Option A — Separate Constitutional Mandate (Role-Level Independence)

**Description:** Independence is achieved through constitutional designation of a specific role within the same organizational structure. An "enrollment oversight officer" or "verification review board" holds a constitutionally designated mandate, separate from the operational officer role, within the same organization. The organization itself grants the constitutional independence.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-05 (one independent authority relationship) | Potentially satisfied — depends on strength of constitutional designation | |
| AC-06 (independence not nominal) | Stressed | If the same organizational governance body can appoint AND remove the independent authority holder, the independence may be nominal |
| AC-12 (revocation from outside) | Partially stressed | If the organization revokes both the primary authority and its independent oversight, independence is self-revocable |
| CF-05-19 (independent relationship, not entity) | Compatible | The relationship (constitutional mandate) is independent even if the organizational entity is internal |

#### Governance Impact Analysis

Option A is the most organizationally accessible form — it requires constitutional bylaw changes to designate independent roles, not structural separation of the organization. Many civil associations use this model (audit committees, oversight boards within the same organization).

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Organizational accessibility | High |
| Constitutional strength | Moderate — depends on bylaw enforcement mechanisms |
| AC-06 risk | Present — independence may be nominal if appointment/removal is not itself independent |
| AC-12 risk | Present — revocation of oversight role and primary role by same authority |

---

### Option B — Separate Authority Holder (Individual/Committee Separation)

**Description:** A different individual or committee holds the constitutionally independent authority, operating within the same organizational framework but with a distinct mandate that is not subordinate to the primary authority holder. Example: the primary authority grants verifications; an independent committee reviews and can challenge those grants.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-05 (independent authority relationship) | Satisfied — different holder holds the relationship | |
| AC-06 (independence not nominal) | Stronger than Option A — different person/committee | |
| AC-09 (independent challenge reception) | Satisfied in principle — different holder receives challenges | |
| AC-12 (revocation from outside) | Partially — if committee is appointed by the primary authority holder, independence of revocation pathway is limited |

#### Governance Impact Analysis

Option B requires the organization to establish and maintain a constitutionally designated committee structure. This is structurally common in democratic organizations (board + management, board + audit committee). The constitutional question: how is the independent committee itself constituted? Who holds L-2 (authority mandate) for the committee? This creates one layer of constitutional authority that must itself be grounded — potentially in the ElectionConstitution (Option C territory for the committee's mandate).

**ARB Discipline Note:** Mentioning a committee authority or oversight body in this section does not create a new D43 instance. A D43 threshold assessment is required separately before any new instance is recognized.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional strength | Moderate-High |
| AC-06 risk | Lower than Option A |
| Organizational complexity | Moderate — committee structure required |
| L-1/L-5 for committee | Must be specified |

---

### Option C — External Organization

**Description:** Constitutional independence is achieved by having a separate external organization hold the authority. The independent authority relationship runs between the election system and an external constitutional body that is organizationally distinct.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-05 (independent authority relationship) | Satisfied — organizationally independent entity | |
| AC-06 (independence not nominal) | Strongest — organizational boundary provides genuine independence | |
| AC-12 (revocation from outside) | Satisfied — external organization revokes internal authority; revocation pathway is genuinely external |
| IR-H (independent of audited subject) | Satisfied — organizational independence from the audited system |

#### Governance Impact Analysis

Option C requires inter-organizational agreements, constitutional recognition of external authority, and operational coordination between the election system's organization and the external body. This is the highest governance complexity of the three options but the strongest constitutional independence. Examples: regulatory oversight bodies, external certification authorities, national electoral commissions overseeing organizational elections.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional strength | Highest |
| AC-06 risk | Minimal |
| Operational complexity | Highest |
| Organizational overhead | High — requires inter-organizational agreements |
| Applicability | Varies — some organizations can establish this; others cannot |

---

### Option D — Hybrid per D43 Function

**Description:** Different D43 functions receive different forms of constitutional independence based on their risk profile and operational requirements. Enrollment may use Option A; Audit may use Option C; Certification may use Option B or C.

#### Constraint Satisfaction Analysis

Option D inherits the constraint satisfaction profile of whichever option is applied to each D43 function. The key additional constraint: the selection of independence form for each D43 function must itself be constitutionally grounded (who decides which form applies to which function?).

#### Governance Impact Analysis

Option D is realistic — different authority functions have different independence requirements based on their constitutional risk (TC-4 amplification, D43 terminal risk). It avoids the one-size-fits-all problem. It requires a constitutional framework that explicitly specifies the independence form for each D43 function.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional specificity | High — each function's independence is tailored |
| Governance complexity | Highest of all options |
| Constitutional coverage | Potentially most complete — each D43 function optimally protected |
| Framework requirement | ElectionConstitution must specify independence form per D43 function |

---

### OQ-03-04 Tradeoff Summary (No Selection)

| Dimension | Option A | Option B | Option C | Option D |
|---|---|---|---|---|
| Constitutional strength | Moderate | Moderate-High | Highest | Variable |
| AC-06 risk | Highest | Lower | Minimal | Variable |
| Organizational accessibility | Highest | Moderate | Lowest | Variable |
| AC-12 satisfaction | Partial | Partial | Full | Variable |
| L-1/L-5 grounding required | Yes | Yes | Yes | Yes (per function) |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** Which form of independence is constitutionally sufficient for each D43 function, given the organization's governance structure and constitutional capabilities?

---

## Section 6 — OQ-03-05 / CPR-03: Authority Relationships in the DDD Model

**Question:** How should authority relationships be represented within or alongside the DDD model? What modeling approach is viable given the constitutional constraints and existing DDD architecture?

**Constraint basis:** AC-03 (L-1/L-5 as first-class elements), AC-04 (authority relationships first-class), AC-18 (separate activities), AC-19 (separate partitioning), AC-20 (trust concentration at authority map level).

---

### Option A — Authority Relationships as Domain Policies

**Description:** Authority relationships are modeled as domain policies — external constitutional rules that aggregates consult when making decisions. ElectionConstitution is already this kind of element in GovernanceState. Extending this pattern: each authority relationship (who holds D43-ENROLL, D43-CRITERIA, etc.) becomes a named policy that aggregates and contexts can reference.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-03 (L-1/L-5 first-class) | Partial — policies can carry L-1/L-5 specifications if explicitly designed to | |
| AC-04 (authority relationships first-class) | Partial — policies are first-class DDD elements | |
| AC-18 (separate activities) | Not fully satisfied — policies are typically designed alongside aggregates, not in a separate activity | |
| AC-19 (separate partitioning) | Not satisfied — policy partitioning follows context partitioning in the DDD pattern | |

#### DDD Impact Analysis

The ElectionConstitution reference is the strongest evidence for this option's viability. Extending it to a full authority policy catalog (one policy per D43 function, each carrying L-1/L-5 specifications) is a natural extension of existing practice. Policy elements are standard DDD vocabulary; they do not require new modeling concepts.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| DDD vocabulary compatibility | High — policies are standard |
| AC-18/19 satisfaction | Low — separate activity and partitioning not achieved through policy pattern |
| L-1/L-5 representation | Possible but requires explicit policy structure design |
| Existing model leverage | High — ElectionConstitution reference is the foundation |

---

### Option B — Authority Relationships as Dedicated Aggregates

**Description:** Each authority relationship is modeled as a first-class aggregate (e.g., EnrollmentAuthority, AuditScopeAuthority, CertificationAuthority). These aggregates hold L-1/L-5 specifications as value objects and have their own lifecycle (creation, operation, suspension, revocation, succession — AC-11).

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-03 (L-1/L-5 first-class) | Well-satisfied — each authority aggregate contains all five dimensions | |
| AC-04 (authority relationships first-class) | Well-satisfied — authority relationships are aggregates | |
| AC-11 (authority lifecycle) | Well-satisfied — aggregates have explicit lifecycle | |
| AC-18 (separate activities) | Depends on process — authority aggregates can be designed in a separate activity | |
| AC-08 (external challengeability) | More natural — authority aggregate can expose a challenge pathway as an aggregate behavior | |
| AC-06 risk | Lower — different aggregate = different boundary; nominal independence harder | |

#### DDD Impact Analysis

Option B requires designing new aggregates — potentially 5 authority aggregates for 5 D43 functions plus cross-cutting authority aggregates for challenge and revocation. This is significant new modeling work. However, the existing aggregate design patterns (Verification, GovernanceState) provide templates: authority aggregates would follow the same invariant-protected, lifecycle-gated pattern.

**Important note for discipline:** Option B identifies that authority aggregates would be needed. This is NOT a design decision — it is an impact assessment. Whether authority relationships SHOULD become aggregates is the Round 37 ADR question.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional completeness | High — aggregates can represent full L-1/L-5 model |
| DDD vocabulary compatibility | High — aggregates are the core DDD building block |
| Model size impact | High — potentially 5+ new aggregates |
| AC-08 / challengeability | More natural — authority aggregate can model challenge events |
| Authority map separation | Can be designed separately from context map |

---

### Option C — Separate Authority Modeling Layer

**Description:** Authority relationships are modeled in a separate architectural layer that exists alongside the DDD domain model. The domain model handles behavioral correctness; the authority layer handles constitutional legitimacy. The two layers interact through explicit cross-layer references rather than being merged into one model. This directly responds to ACQ-13 (governance = state machine layer + constitutional layer).

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-18 (separate activity) | Well-satisfied — separate layer = separate design activity | |
| AC-19 (separate partitioning) | Well-satisfied — authority partitioning is designed independently | |
| AC-20 (trust concentration at authority map level) | Well-satisfied — the authority layer IS the authority map | |
| AC-03 (L-1/L-5 first-class) | Can be fully satisfied — L-1/L-5 are native to the authority layer | |
| OAQ-13 (DDD tools for constitutional authority) | Directly addressed — separate layer implies a different modeling vocabulary for authority |

#### DDD Impact Analysis

Option C requires that two models be produced and maintained: the domain model (aggregates, contexts, events, commands — as currently discovered) and the authority model (authority relationships, L-1/L-5 specifications, independence assessments, challenge pathways, revocation mechanisms, succession plans). The domain model remains unchanged. The authority model is produced in a separate design activity.

Cross-layer references: GovernanceState references ElectionConstitution (domain model → authority layer). This pattern already exists — it would be formalized as the canonical cross-layer interaction pattern.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| AC-18/19/20 satisfaction | Strongest of all options |
| DDD vocabulary compatibility | Requires augmentation — authority layer needs its own modeling vocabulary |
| Model maintenance overhead | High — two models to maintain |
| OAQ-13 | Directly addresses the question of whether standard DDD tools suffice |
| Implementation complexity | High — two layers with cross-references |

---

### Option D — Authority Relationships as Annotations on Existing DDD Elements

**Description:** Existing aggregates and contexts are annotated with their constitutional authority specifications. The authority map is a structured annotation of the DDD model, not a separate layer. Each aggregate's L-1/L-5 specifications are attached to the aggregate definition.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-03 (L-1/L-5 first-class) | Partially — annotations make them explicit but not independent | |
| AC-18 (separate activity) | Not fully satisfied — annotations are designed with aggregates | |
| AC-19 (separate partitioning) | Not satisfied — authority partitioning remains coupled to context partitioning | |
| AC-20 (trust concentration) | Partially — trust concentration could be assessed from annotations but requires additional analysis step |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| DDD vocabulary compatibility | Highest — no new modeling elements needed |
| AC-18/19 satisfaction | Lowest — separate activity not achieved |
| Model maintenance overhead | Lowest |
| AC-20 assessment | Requires additional tooling to extract authority map from annotations |

---

### OQ-03-05 Tradeoff Summary (No Selection)

| Dimension | Option A (Policies) | Option B (Aggregates) | Option C (Separate Layer) | Option D (Annotations) |
|---|---|---|---|---|
| AC-18/19 satisfaction | Low | Moderate | Highest | Lowest |
| AC-03 satisfaction | Partial | High | High | Partial |
| AC-11 satisfaction | Partial | Strongest | High | Low |
| DDD vocabulary | Standard | Standard | Augmented | Standard |
| Model complexity | Low | Medium | High | Lowest |
| Independence robustness | Lower | Moderate | Highest | Lowest |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** What modeling vocabulary is constitutionally sufficient for representing L-1/L-5 authority relationships? Does the program require augmented modeling (Option C) or can existing DDD vocabulary (Options A/B) satisfy the constitutional requirements?

---

## Section 7 — CPR-04: Certification Options

**Question:** What architectural options exist for a certification function that satisfies AC-27 (external challenge pathway), AC-28 (legitimacy gap awareness), and the terminal risk profile of D43-CERT?

**Context from 36E-03:** D43-CERT has zero coverage in the current DDD model. Results/Tallying (D39 deferred) is the closest proximity. Certification is the terminal constitutional act (TF-36C-05-11); TC-4 failure amplifies all prior threat classes.

**D39 Dependency Note:** D39 (Results/Tallying) remains unresolved. All certification options below are necessarily provisional — they identify viable architectural patterns but cannot be finalized until D39 is resolved. This does not prevent Round 37 from evaluating which options are compatible with a range of D39 realizations.

---

### Option A — Certification as Terminal State of Results/Tallying

**Description:** Certification is designed as a final lifecycle state within the Results/Tallying function. After tallying is complete, the election system transitions to a "certified" state. The certification act is internal to the results publication process.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-27 (external challenge pathway) | Stressed — if certification is a lifecycle state, challenging it requires challenging a state transition (same pattern as GovernanceState's unresolved ADH-1 problem) | |
| AC-28 (legitimacy gap awareness) | Depends on design — a state within Results/Tallying inherits that context's legitimacy gaps | |
| AC-25 (authorization ≠ execution) | Stressed — the results-computing function and the certifying function would share a boundary | |
| D43-CERT gaps | Partially reduced — legitimacy gaps may be inherited from Results/Tallying's design |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Design simplicity | Higher — fewer new elements |
| Constitutional independence | Lower — certification shares authority boundary with election execution |
| AC-27 satisfaction | Stressed |
| Terminal risk reduction | Partial — terminal risk not fully addressable within a shared boundary |

---

### Option B — Certification as a Constitutionally Separate Act

**Description:** Certification is a constitutionally distinct act performed by an independent authority after results are published. The results publication function and the certification function are held by constitutionally separate authority relationships. Certification involves an independent examination of the published results against constitutional standards before declaring the election valid.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-27 (external challenge pathway) | More naturally satisfied — the certifying authority is independent; challenges go to the certifying authority | |
| AC-28 (terminal legitimacy gap awareness) | More naturally satisfied — a separate certification authority can be designed with all five legitimacy dimensions | |
| AC-25 (authorization ≠ execution) | Satisfied for certification — the certification authority is separate from the election execution authority | |
| AC-05 (independent authority relationship) | Can be satisfied — D43-CERT has its own constitutionally independent authority relationship | |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional independence | Stronger |
| AC-27 / AC-28 satisfaction | More natural |
| Governance complexity | Higher — requires a constitutionally designated certification authority |
| D43-CERT gap closure | More complete — independent authority can be specified with L-1/L-5 |
| Terminal risk reduction | Higher |

---

### Option C — Multi-Party Certification

**Description:** Certification requires the concurrent agreement of multiple constitutionally independent parties — for example, the election administration body, an independent auditor, and a membership representative. No single party can certify; all must concur.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-27 (external challenge pathway) | Well-satisfied — challenges go to any of the certifying parties | |
| AC-05 (independent relationship per function) | Can be satisfied — multiple independent relationships | |
| Trust concentration | Minimized — no single point of certification failure | |
| Operational complexity | High — all parties must agree; coordination required |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional strength | Highest |
| TC-4 protection | Highest — certification abuse requires compromise of multiple independent parties |
| Operational complexity | Highest |
| Governance structure required | Most complex — requires multiple constitutionally designated parties |

---

### Option D — Deferred Certification Design

**Description:** Formal certification design is deferred until D39 (Results/Tallying) is resolved. The relationship between result publication and certification cannot be determined without knowing how results are produced.

#### Constitutional Impact

Option D is not a certification architecture — it is a sequencing decision. Whether it constitutes an acceptable deferral depends on whether the program can proceed without a certification design at this stage. Given that D43-CERT has zero coverage and D39 is a known prerequisite, Option D may be the most honest near-term assessment.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| Constitutional commitment | Lowest — no commitment made |
| Design honesty | High — D39 prerequisite acknowledged |
| Risk profile | Option D does not reduce D43-CERT's terminal risk; it defers the decision |
| Program sequencing | D39 resolution must precede certification design |

---

### CPR-04 Tradeoff Summary (No Selection)

| Dimension | Option A | Option B | Option C | Option D |
|---|---|---|---|---|
| AC-27 satisfaction | Stressed | Natural | Strong | Deferred |
| AC-28 satisfaction | Partial | More complete | Strong | Deferred |
| TC-4 protection | Partial | Moderate | Highest | Not addressed |
| Governance complexity | Lowest | Moderate | Highest | None |
| D43-CERT gap closure | Partial | More complete | Most complete | Not addressed |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** Should certification architecture proceed before D39 is resolved? If yes, which option provides constitutional coverage without over-committing on D39-dependent design decisions?

---

## Section 8 — CPR-05: Evidence Integrity Architecture Options

**Question:** What architectural options exist for satisfying the evidence integrity decomposition constraint cluster (AC-14/15/17/29)?

**Context from 36E-03:** Evidence existence is modeled (Vote events, Audit Context). Evidence authenticity is partially addressable (DataChecksum). Evidence completeness is unmodeled (Gap A-3). Presence and authenticity are not architecturally distinguished.

---

### Option A — Layered Evidence Pathways

**Description:** Evidence is exposed through two distinct architectural pathways: (1) an internal presence pathway (existing Audit Context observes VoteRecorded events — existence confirmation) and (2) an external authenticity pathway (DataChecksum exposed via VRC or equivalent external access point — authenticity verification). The two concerns (AC-29) are architecturally separated by their pathways.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-29 (presence ≠ authenticity) | Satisfied in principle — different pathways for different concerns | |
| AC-15 (external access) | Requires external authenticity pathway design | |
| AC-14 (external scope) | Not addressed — scope definition is not a pathway question | |
| Gap A-3 | Not addressed — completeness remains unmodeled |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| AC-29 architectural separation | Satisfied |
| Gap A-3 / completeness | Not addressed |
| DDD compatibility | High — pathways are routing decisions, not model changes |
| EH-01 satisfaction | Partial — external authenticity pathway exists; reference standard circularity assessed separately (Section 2) |

---

### Option B — External Evidence Repository

**Description:** Evidence is written simultaneously to two locations: the internal election system (operational use) and an externally managed, constitutionally independent evidence repository (audit use). The external repository is the authoritative reference for authenticity verification. Gap A-3 is closed by the repository's constitutional mandate to receive ALL required evidence.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-14 (scope from outside) | Partial — repository receives evidence; scope of what it must receive still requires external definition | |
| AC-15 (external access) | Well-satisfied — repository is externally accessible by design | |
| AC-17 (expected evidence set mechanism) | Partial — repository constitution defines expected evidence; but expected evidence set must still be authoritatively defined | |
| AC-29 (presence ≠ authenticity) | Satisfied — external repository holds reference; authenticity verified against repository, not originating system |

#### Governance Impact Analysis

The external repository requires its own constitutional grounding — who governs it? Who can write to it? Who can read from it? This is the D43-AUDIT specialization question (OQ-03-03) instantiated as a concrete governance problem.

**ARB Discipline Note:** Mentioning a repository authority or repository governance body in this section does not create a new D43 instance. A D43 threshold assessment is required separately before any new instance is recognized.

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| EH-01 satisfaction | Strong — repository is the independent reference standard |
| Gap A-3 | Partially addressed — repository scope requires constitutional definition |
| Constitutional complexity | High — repository requires its own governance structure |
| TC-2 protection | High — simultaneously tampering with election system AND repository requires compromising two systems |

---

### Option C — Cryptographic Evidence Accumulation

**Description:** Evidence is accumulated in a cryptographic structure (Merkle tree, hash chain, or ballot cryptogram set) whose root commitment is published externally. *(These are example mechanisms only — not candidate selections. Specific technology choices belong to Round 38 or later.)* Gap A-3 is addressed by the commitment covering a universally defined set of evidence. Authenticity verification checks evidence against the published commitment.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-29 | Well-addressed — presence (commitment includes X) and authenticity (X matches commitment) are cryptographically separable | |
| AC-14 / AC-17 | Requires universal definition — the commitment must cover a constitutionally defined evidence set | |
| VO-1 compatibility | Requires assessment — some commitment schemes reveal vote content |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| EH-01 satisfaction | Strong if commitment is externally published |
| Gap A-3 | Addressable if commitment covers full constitutional evidence set |
| VO-1 risk | Requires explicit evaluation |
| Implementation complexity | High |

---

### Option D — Stratified Evidence Architecture

**Description:** Evidence is organized into three distinct strata: (1) Completeness Stratum — what records must exist (externally defined by audit scope authority, closing Gap A-3); (2) Presence Stratum — what records do exist (internally observed by Audit Context, then externally accessible); (3) Authenticity Stratum — are present records unmodified (DataChecksum or cryptographic reference, independently anchored).

Each stratum has different access requirements and constitutional grounding requirements. This directly maps to TP-02 (completeness access vs. authenticity access depth) from 36E-02.

#### Constraint Satisfaction Analysis

| Constraint | Assessment | Notes |
|---|---|---|
| AC-29 (presence ≠ authenticity) | Well-satisfied — separate strata with separate access pathways | |
| AC-14 / AC-17 | Completeness Stratum requires external scope definition — carries the Gap A-3 question forward | |
| AC-15 (external access) | Satisfied at Presence and Authenticity strata | |

#### Tradeoff Catalog

| Attribute | Assessment |
|---|---|
| AC-29 architectural satisfaction | Strongest — three distinct concerns with three distinct strata |
| Gap A-3 | Completeness Stratum makes the gap explicit; does not close it |
| Design complexity | Highest — three strata with different governance, access, and constitutional requirements |
| Mapping to 36E-02 findings | Direct — TP-02 tension is the architectural basis for stratification |

---

### CPR-05 Tradeoff Summary (No Selection)

| Dimension | Option A | Option B | Option C | Option D |
|---|---|---|---|---|
| AC-29 satisfaction | Satisfied | Satisfied | Well-satisfied | Strongest |
| Gap A-3 closure | Not addressed | Partial | Partial | Makes gap explicit |
| EH-01 satisfaction | Partial | Strong | Strong | Conditional |
| VO-1 risk | Low | Low | Requires assessment | Low |
| DDD compatibility | Highest | Moderate | Moderate | Moderate |
| Implementation complexity | Lowest | Moderate | Highest | High |

**Options are not ranked.** Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.

**Carried to Round 37:** Which evidence stratum model is constitutionally sufficient? Is Gap A-3 closed by the architecture (Options B/C/D partially) or does it require a separate constitutional authority decision first?

---

## Section 9 — Cross-Option Tension Analysis

Several architectural option choices in different sections interact with each other. The following tensions are identified between options (not within options).

### Cross-Tension 1: EC-01 Option B (Individual Verifiability) × CPR-05 Option C (Cryptographic Commitment)

EC-01 Option B (individual verifiability without receipt construction) and CPR-05 Option C (cryptographic commitment scheme) are potentially complementary but also potentially in tension. A commitment scheme may enable individual verifiability (voter checks commitment against published root) — satisfying EC-01 Option B. However, the same mechanism may enable receipt construction if the voter's ballot contribution to the commitment is linkable to them. The two options must be evaluated together, not independently, if both are chosen.

### Cross-Tension 2: OQ-03-05 Option C (Separate Authority Layer) × CPR-03 (Dual Authority Map)

OQ-03-05 Option C (separate authority modeling layer) and CPR-03 (dual authority map requirement from AC-18/19) are naturally complementary — the separate authority layer IS the authority map. However, Option C requires a new modeling vocabulary; CPR-03's requirement is for a separate ARTIFACT. Whether a new modeling vocabulary is constitutionally required (over an extended DDD vocabulary) is an open question.

### Cross-Tension 3: CPR-04 Option D (Deferred Certification) × Program Timeline

If D39 (Results/Tallying) is deferred indefinitely, CPR-04 Option D effectively defers the highest-risk constitutional gap (D43-CERT) indefinitely. The program's constitutional completeness depends on D39 resolution. This is a sequencing risk, not an architectural conflict.

### Cross-Tension 4: CPR-01 Option A (Role-Level Independence) × AC-06 (Nominal Independence)

CPR-01 Option A (separate constitutional mandate within the same organization) faces AC-06 risk: if the same organizational authority can appoint and remove the independent role, the independence is potentially nominal. An organization that adopts Option A for D43 functions must also adopt a bylaw structure that protects the independent role's mandate from self-revocation — otherwise the independence is structurally nominal, violating AC-06.

---

## Section 10 — Open Questions for Round 37

The following questions cannot be resolved through option evaluation. They require architectural decisions (ADRs):

**36E-04-OQ-01:** Which form of constitutional independence (CPR-01 Options A/B/C/D) is constitutionally sufficient for each D43 function? This is the organizing decision for all subsequent D43 gap closure work.

**36E-04-OQ-02:** Does the organizational governance model support constitutionally grounded external verification (CPR-05 Options B/C)? If not, what organizational prerequisites must be met before the architecture can be completed?

**36E-04-OQ-03:** Is EC-01 a Type D tension (resolvable through Option A or Option B design) or a Type E conflict (unresolvable within constitutional constraints)? This must be determined before vote challenge architecture can proceed.

**36E-04-OQ-04:** Does the ElectionConstitution serve as a shared L-1 source for multiple D43 instances (Criteria, GovernanceAuth, potentially Enrollment), or does each function require a separate L-1 determination? This is the most consequential single architectural question for the authority model.

**36E-04-OQ-05:** Should certification design proceed before or after D39 resolution? If before: which certification option is compatible with an as-yet-undefined tallying architecture?

**36E-04-OQ-06:** What modeling vocabulary is constitutionally sufficient for authority relationships (OQ-03-05 Options A/B/C/D)? Does constitutional legitimacy require augmented modeling beyond standard DDD vocabulary?

---

## Section 11 — ARB Decision Block

**[APPROVED WITH REQUIRED REVISIONS — APPROVED]**

**ARB Verdict Date:** 2026-06-14

### Summary of Deliverables

**Produced:**
- Option evaluation for OQ-03-01 (EH-01): 3 options with full tradeoff catalog
- Option evaluation for OQ-03-02 (EC-01): 3 options with full tradeoff catalog
- Option evaluation for OQ-03-03/CPR-02 (Audit Scope): 3 options with D43-AUDIT threshold determination
- Option evaluation for OQ-03-04/CPR-01 (Form of Independence): 4 options with full tradeoff catalog
- Option evaluation for OQ-03-05/CPR-03 (Authority in DDD): 4 options with full tradeoff catalog
- Option evaluation for CPR-04 (Certification): 4 options with full tradeoff catalog
- Option evaluation for CPR-05 (Evidence Integrity): 4 options with full tradeoff catalog
- 4 cross-option tensions identified
- 6 open questions for Round 37

**Not produced (by discipline):** architectural decisions, option selections, winners, ADRs, new contexts, new aggregates, new services.

### ARB Required Revisions (Applied)

**Revision 1 — Option C Technical Mechanism Language:**
Added "Example mechanisms only — not candidate selections" notes to Section 2 Option C (hash chain, Merkle tree, ballot commitment) and Section 8 Option C (Merkle tree, hash chain, ballot cryptogram set). Technology choices belong to Round 38 or later.

**Revision 2 — D43 Discipline Notes in Governance Sections:**
Added standard ARB discipline notes to all governance impact sections that introduce new authority mentions: Section 2 Option B (reference standard authority), Section 2 Option C (commitment holder), Section 5 Option B (committee authority), Section 8 Option B (repository authority). Standard note: mentioning a governance authority does not create a new D43 instance; D43 threshold assessment is required separately.

**Revision 3 — CPR-04 D39 Dependency Warning:**
Added explicit dependency note in Section 7 (CPR-04) opening: "D39 remains unresolved. All certification options below are necessarily provisional — they identify viable architectural patterns but cannot be finalized until D39 is resolved."

**Revision 4 — Non-Ranking Rule at Each Section Summary:**
Added explicit non-ranking rule ("Options are not ranked. Option ordering does not imply preference. No option is recommended. Option selection belongs to Round 37 ADRs.") before every "Carried to Round 37" line in sections 2, 3, 4, 5, 6, 7, and 8.

### ARB Responses to OQs

**OQ-36E-04-01 (Cross-option tensions):** Cross-option tensions as identified in Section 9 are accepted. Four tensions correctly identified. No additional tensions added in this round.

**OQ-36E-04-02 (Eliminable options):** No options are definitively eliminable before Round 37. All options remain in the catalog. Ruling an option out requires explicit constitutional evidence that no design under that option can satisfy the constraints — that evidence is not available at the 36E evaluation stage.

**OQ-36E-04-03 (36E-05 or directly to Round 37):** **Round 36E-05 is authorized.** Purpose: Architecture Synthesis — synthesize the option catalog into an architecture recommendation that Round 37 ADR authoring can act upon. Without 36E-05, Round 37 ADR authors would face 20+ options across 7 pressure areas without synthesis. 36E-05 must NOT select options — it must identify which combinations are constitutionally coherent, which are in tension, and what the ADR authoring sequence should be.

### Authorization

**36E-04: APPROVED — CLOSED**
**36E-05: AUTHORIZED**

36E-05 Charter: Synthesize the Architecture Option Catalog from 36E-04. Identify coherent option combinations. Map constitutional dependencies between options. Propose ADR authoring sequence for Round 37. Binding guardrail: 36E-05 may RECOMMEND OPTION COMBINATIONS AND SEQUENCING. It may NOT select individual options as the final architecture. Selection remains in Round 37 ADRs.

---

*Round 36E-04 — Architecture Option Evaluation — APPROVED WITH REQUIRED REVISIONS — APPROVED*  
*ARB Approval Date: 2026-06-14*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round36E-04_Architecture_Option_Evaluation.md*  
*Successor: Round36E-05_Architecture_Synthesis.md — AUTHORIZED*
