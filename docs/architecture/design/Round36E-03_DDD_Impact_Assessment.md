# Round 36E-03 — DDD Impact Assessment

**Program:** NRNA DDD Trustworthiness Research Program  
**Series:** 36E — Architecture Impact Assessment  
**Sub-Round:** 36E-03 of 05  
**Status:** APPROVED WITH REQUIRED REVISIONS — APPROVED  
**Governing Question:** Can the current bounded contexts, aggregates, domain services, events, and context map satisfy the discovered constitutional constraints? Where are the gaps?

**Predecessors:**
- 36A Verifiability Research — CLOSED
- 36B Auditability Research — CLOSED
- 36C Threat Modeling — CLOSED
- 36D Trustworthiness Research — CLOSED
- 36E-01 Constraint Mapping (30 ACs) — APPROVED
- 36E-02 Constraint Interaction Analysis — APPROVED

**Discipline Boundary (binding):** This round assesses existing DDD artifacts only. No new bounded contexts, aggregates, services, ADRs, or architectural designs are produced.

---

## Existing DDD Model Reference

The current discovered DDD model, as approved through Round 35D, consists of:

**Bounded Contexts (established):**
- Trust Attestation (owns Verification aggregate)
- Constitutional Governance (owns GovernanceState aggregate)
- Voting (owns Vote aggregate)
- Eligibility (stateless evaluator — pull-over-push)
- Verification Representation (D42B resolution from 36A-09 — boundary established, internal design deferred)
- Audit (internal observer — event consumption model)
- Governance Evidence Replay (candidate, owns ReplaySession candidate)
- Authorization (candidate, owns RoleAssignment candidate)
- Results/Tallying (boundary identified, design deferred — D39 unresolved)

**Aggregates:**
- Verification (APPROVED): invariants TA-1/TA-2/TA-3, lifecycle PENDING→ACTIVE→REVOKED
- GovernanceState (APPROVED — Governance Debt Active): invariants CG-1/CG-2/CG-3, lifecycle SETUP→VOTING_ACTIVE→COUNTING→RESULTS_PUBLISHED→CLOSED, SuspensionOverlay entity
- Vote (APPROVED): invariants VO-1/VO-2/VO-3/VO-4, BallotSelection entity, VoteHash/DataChecksum/ReceiptHash value objects
- RoleAssignment (CANDIDATE — blocked by ADC-1/2/ADH-1)
- ReplaySession (CANDIDATE — blocked by D36/ADGR-1)

**Domain Events (discovered):**
- VerificationGranted, VerificationRevoked (Verification)
- VoteRecorded, VoteRejected (Vote)
- GovernanceTransitionCompleted, GovernanceSuspended, GovernanceResumed (GovernanceState)

**Authority Catalog (Round 35D Part 1 — proto-authority-map):**
- A1: Trust Attestation — ESTABLISHED (Verification)
- A2: Voting Authorization — ESTABLISHED (GovernanceState — derived)
- A3: Election Lifecycle State — ESTABLISHED (GovernanceState)
- A4: Election Enrollment — GAP (AUTHORITY-GAP-1 / D43)
- A5: Vote Record — ESTABLISHED (Vote)
- A6: Role Assignment — CANDIDATE (RoleAssignment — blocked)
- A7: Evidence Integrity — CANDIDATE (ReplaySession — blocked)

**Choreography model:** Pull-over-push throughout. No sagas, no process managers. Contexts publish authority; consumers evaluate on demand.

**Active Design Debts:** ADH-1 (governance transition authorization hierarchy), D35/D37 (lifecycle expiry semantics), D39 (results/tallying boundary), ADGR-1 (replay governance), ADC-1/2 (authorization context structure)

---

## Section 1 — Constraint Coverage Assessment

For each constraint AC-01 through AC-30, the assessment determines:
1. Which existing DDD elements appear to satisfy it
2. Which existing DDD elements are affected by it
3. Coverage: Strong / Partial / Weak / None
4. Supporting evidence

Coverage thresholds:
- **Strong** — the constraint is directly and explicitly satisfied by current model elements
- **Partial** — the constraint is satisfied for some cases or at some layers but not constitutionally complete
- **Weak** — the current model contains elements that are compatible with the constraint but do not satisfy it
- **None** — no current element addresses this constraint; the constraint introduces a gap

---

### AC-01: Distinguish behavioral quality mechanisms from constitutional legitimacy structures

**Affected elements:** All approved aggregates (Verification, GovernanceState, Vote)

**Assessment:** PARTIAL

**Evidence:** The current model has robust behavioral quality mechanisms:
- TA-1/2/3 protect the integrity of verification decisions (behavioral invariants)
- CG-1/2/3 protect the integrity of governance transitions (behavioral invariants)
- VO-1/2/3/4 protect vote anonymity and integrity (behavioral invariants)

These are correctly modeled as aggregate invariants and value object properties. However, the current model does NOT explicitly distinguish these behavioral controls from constitutional legitimacy. The GovernanceState aggregate enforces preconditions (CG-2) but the preconditions themselves are defined in the ElectionConstitution — an external policy not yet modeled as a constitutional legitimacy artifact. The Verification aggregate records Officer Attribution (TA-2) but the officer's constitutional mandate is not modeled as an L-2 legitimacy element — it is recorded as an attribution, not as a constitutional authority claim.

**36E-03-CCA-01:** AC-01 is PARTIALLY satisfied. The behavioral quality mechanisms (TA-1/2/3, CG-1/2/3, VO-1/2/3/4) are correctly modeled. Constitutional legitimacy structures are absent from the current model. The gap is not in behavioral quality — it is in the absence of an explicit constitutional legitimacy layer alongside the behavioral layer.

---

### AC-02: No authority function may be architecturally self-verifying

**Affected elements:** Verification (officer grants own status?), GovernanceState (officer transitions state without constitutional authority grounding), Audit Context (internally observes own domain)

**Assessment:** WEAK

**Evidence:** The current model does not contain an explicit mechanism by which an authority self-verifies in the most obvious sense (e.g., a voter cannot grant their own verification). TA-2 enforces that revocation must be attributed to an officer — preventing the most naive self-verification.

However, the constitutional self-verification prohibition (AC-02) goes deeper than the behavioral prohibition. The constitutional concern is: can any authority function produce a trustworthiness assessment of itself that is accepted without external validation? In the current model:

- The Audit context observes VoteRecorded events and internally accumulates audit evidence. The evidence of correct election execution is held by the election system itself. No external entity can confirm that the evidence is complete and authentic — the system's own audit is the only audit. This is constitutional self-verification of evidence integrity.
- GovernanceState enforces transition preconditions (CG-2) — but the authority to declare that preconditions are satisfied is exercised by the same system that executes the transition. ADH-1 is precisely the question of whether this is constitutional self-authorization.

**36E-03-CCA-02:** AC-02 is WEAKLY satisfied. The behavioral prohibition on the most naive self-attestation exists. The constitutional self-verification gap is present: the Audit context constitutes self-verification of election integrity; ADH-1 (unresolved) may constitute self-authorization of governance transitions.

---

### AC-03: Constitutional legitimacy structures (L-1 through L-5) must be first-class architectural elements

**Affected elements:** All aggregates, all contexts

**Assessment:** WEAK (L-1 candidate signal exists; L-2 through L-5 absent)

**Evidence:** No aggregate or context in the current model models L-2 (authority holder identification), L-3 (challenge mechanism), L-4 (revocation mechanism), or L-5 (succession mechanism) as explicit domain model elements. These four dimensions are entirely absent.

For L-1 (legitimacy source): the ElectionConstitution, referenced as an external policy by GovernanceState, is the closest element to an L-1 source in the current model. GovernanceState explicitly defers to it for precondition validation during state transitions. This is a weak candidate L-1 signal — the ElectionConstitution is acknowledged as an external constitutional authority, but it is not modeled as a first-class domain artifact carrying an explicit L-1 specification. It exists as an external policy reference, not as a constitutional legitimacy element.

The closest approximation for L-2: Officer Attribution (VO in Verification) captures who made a decision and when. But it records the identity of the officer, not the constitutional basis of the officer's mandate. A constitutional L-2 element would model the officer's authority grant, its source, and its validity — not merely their name.

**36E-03-CCA-03:** AC-03 is WEAKLY satisfied at L-1 only. The ElectionConstitution reference in GovernanceState is a weak candidate L-1 signal — the strongest constitutional legitimacy signal in the current model (see also 36E-03-DFA-10). L-2 through L-5 do not exist as first-class elements in any current aggregate or context. This is the single most foundational gap against the constitutional constraint catalog.

---

### AC-04: Authority relationships as first-class architectural concerns, distinct from function execution

**Affected elements:** Authority Catalog (Round 35D Part 1), all established contexts

**Assessment:** PARTIAL

**Evidence:** The Round 35D Authority Catalog (Part 1 — Authoritative Source Catalog) represents the closest existing element to a first-class authority relationship model. It names:
- Seven authorities
- Their decision owners (D1 through D7)
- Their owning contexts and aggregates
- Their published values and consumers

This is proto-authority-map content. However, it exists embedded within the Context Choreography document — it was produced as the precursor to choreography analysis, not as a standalone authority relationship model. The authority relationships are named but not modeled as domain elements with their own invariants, lifecycle, or constitutional specifications.

**36E-03-CCA-04:** AC-04 is PARTIALLY satisfied. Authority relationships are named in the 35D Authoritative Source Catalog. They are not modeled as first-class domain elements. The catalog is a discovery artifact; a domain-level authority model does not yet exist.

---

### AC-05: Each D43 function must realize at least one constitutionally independent authority relationship; architectural realization unresolved

**Affected elements:** All five D43 instances (Enrollment, Criteria, Audit, GovernanceAuth, Certification)

**Assessment:** NONE

**Evidence:** Evaluated per D43 instance:

| D43 Function | Independent Authority Relationship | Status |
|---|---|---|
| Enrollment | No owner established (AUTHORITY-GAP-1) | NONE |
| Criteria | GovernanceState holds criteria config; no independent authority relationship | NONE |
| Audit | Audit Context self-observes; no independent audit authority | NONE |
| GovernanceAuth | ADH-1 unresolved; no independent governance authorization structure | NONE |
| Certification | No certification mechanism exists at all | NONE |

**36E-03-CCA-05:** AC-05 is NOT satisfied for any D43 function. Zero out of five D43 functions have a constitutionally independent authority relationship in the current model.

---

### AC-06: Independence cannot be achieved by naming alone within the same authority boundary

**Assessment:** NOT APPLICABLE at this stage

**Evidence:** AC-05 is not satisfied. AC-06 applies as a quality constraint on AC-05 satisfaction. When a constitutionally independent authority relationship is eventually modeled, AC-06 will constrain how it is modeled. It cannot be assessed against the current model because there is nothing to assess it against.

**36E-03-CCA-06:** NOT APPLICABLE — prerequisite (AC-05) not satisfied.

---

### AC-07: Authority relationships must carry explicit L-1 to L-5 specifications

**Assessment:** NONE

**Evidence:** No authority relationship in the current model carries explicit L-1/L-5 specifications. Officer Attribution records WHO made a decision; it does not specify the constitutional basis (L-1), the authority mandate (L-2), the challenge pathway (L-3), the revocation mechanism (L-4), or the succession mechanism (L-5) of the officer's authority.

**36E-03-CCA-07:** NOT satisfied. No current DDD element carries L-1/L-5 specifications.

---

### AC-08: Authority decisions must be challengeable and externally addressable; representation form unresolved

**Affected elements:** VerificationGranted/VerificationRevoked, GovernanceTransitionCompleted, any future certification decision

**Assessment:** NONE

**Evidence:** The model has no challenge mechanism of any kind:
- Verification decisions are APPEND-ONLY (TA-3). An officer can revoke a verification, but the revocation itself is unchallengeable. A voter whose verification is revoked has no constitutional challenge pathway.
- GovernanceTransitionCompleted is final. No challenge pathway exists for governance decisions.
- Audit context observations are unreviewable.

The append-only property (TA-3) is the dominant pattern in the current model — it was designed to prevent tampering, but it simultaneously raises the question of constitutional challenge addressability. This creates a design tension: append-only protects integrity; external challengeability requires addressability of past decisions. Both are constitutional requirements. Whether a non-mutating challenge pathway can satisfy both simultaneously is structurally unresolved — many E2E-V systems attempt immutable evidence alongside challenge mechanisms; whether this is achievable for NRNA belongs to 36E-04.

**36E-03-CCA-08:** NOT satisfied. The append-only design pattern (TA-3, CG-1) protects behavioral integrity. Whether this pattern is compatible with external challengeability (AC-08) is **structurally unresolved**, not structurally incompatible. The tension is real and must be resolved; the resolution belongs to 36E-04. This is the most significant interaction between the current model's behavioral design and the constitutional constraint catalog.

---

### AC-09: Challenge reception structurally independent of challenged authority; form unresolved

**Assessment:** NONE (challenge mechanism does not exist)

**36E-03-CCA-09:** NOT satisfied. No challenge reception mechanism exists.

---

### AC-10: Challenge determination outside challenged authority

**Assessment:** NONE (challenge mechanism does not exist)

**36E-03-CCA-10:** NOT satisfied.

---

### AC-11: Authority lifecycle explicitly modeled (creation, operation, suspension, revocation, succession)

**Affected elements:** Verification lifecycle, GovernanceState lifecycle

**Assessment:** PARTIAL

**Evidence:**
- Verification lifecycle (PENDING→ACTIVE→REVOKED): models CREATION (grant), OPERATION (active), REVOCATION (revoked) for the VERIFICATION DECISION. SUSPENSION is absent. SUCCESSION is absent.
- GovernanceState lifecycle (SETUP→VOTING_ACTIVE→COUNTING→RESULTS_PUBLISHED→CLOSED + SuspensionOverlay): models CREATION (setup), OPERATION (voting active), SUSPENSION (overlay), CLOSURE (closed). REVOCATION and SUCCESSION for the election's governing authority are absent.

The critical gap: AC-11 requires authority lifecycle modeling — i.e., the lifecycle of the AUTHORITY HOLDER, not only the lifecycle of the domain entity the authority governs. The officer role has no lifecycle. The election governance authority has no lifecycle (only the election has a lifecycle).

**36E-03-CCA-11:** PARTIALLY satisfied. Domain entity lifecycles are modeled (Verification lifecycle, GovernanceState lifecycle). Authority holder lifecycles are not modeled. The lifecycle of the constitutional authority behind each function is entirely absent.

---

### AC-12: Revocation events must originate outside the authority boundary being revoked

**Affected elements:** Verification (VerificationRevoked), GovernanceState

**Assessment:** WEAK

**Evidence:** VerificationRevoked is issued by an officer. An officer is a system actor operating within the Trust Attestation context boundary. The authority that is being exercised (verification granting/revoking authority) and the authority that is being used to exercise it (officer role) share the same context boundary. There is no external constitutional authority that revokes the Trust Attestation authority itself.

The distinction is between:
- Revoking a verification (VerificationRevoked — present in model)
- Revoking the AUTHORITY to grant/revoke verifications (AC-12's concern — absent from model)

**36E-03-CCA-12:** WEAKLY satisfied. Revocation of domain decisions (VerificationRevoked) exists. Revocation of the constitutional AUTHORITY to make those decisions from outside the authority boundary does not exist.

---

### AC-13: Revocation pathway must include succession pathway

**Assessment:** NONE — succession mechanism does not exist in any aggregate or context.

**36E-03-CCA-13:** NOT satisfied.

---

### AC-14: Audit scope definition must originate outside the election system's architectural boundary

**Affected elements:** Audit Context, GovernanceState (implicitly defines what events are observable)

**Assessment:** NONE

**Evidence:** The Audit Context's scope is defined by which events it consumes. Currently it observes VoteRecorded (from Vote) and presumably GovernanceTransitionCompleted (from GovernanceState). Who defines what events Audit must consume? The current model provides no answer — the scope emerges from implementation choices, not from a constitutionally defined external mandate. Gap A-3 is precisely this gap.

**36E-03-CCA-14:** NOT satisfied. Audit scope is effectively defined by the election system itself (which events it publishes and which the Audit context subscribes to). No external scope-defining mechanism exists.

---

### AC-15: Election system must expose auditable data through externally accessible pathway

**Affected elements:** Audit Context, Vote (VoteRecorded event), GovernanceState events

**Assessment:** PARTIAL

**Evidence:** The Audit Context observes VoteRecorded and GovernanceTransitionCompleted events. This creates internal audit observation. The gap: this pathway is internal — the Audit Context is a bounded context INSIDE the election system boundary, not outside it. For external parties to audit the election, they would need access to audit evidence through a pathway that exits the system boundary. No such pathway is modeled.

The Audit Context's evidence store (whatever it accumulates) is not exposed externally in the current model.

**36E-03-CCA-15:** PARTIALLY satisfied. Internal audit observation exists (Audit Context). External audit access pathway does not exist.

---

### AC-16: Audit scope authority cannot share an architectural authority boundary with election execution authority

**Assessment:** NONE (prerequisite AC-14 not satisfied)

**Evidence:** No audit scope authority exists. The gap is upstream of this constraint.

**36E-03-CCA-16:** NOT satisfied — prerequisite missing.

---

### AC-17: Architecture must include mechanism for externally defining expected evidence set

**Assessment:** NONE

**Evidence:** Gap A-3 (expected evidence set undefined) is an open gap from 36B. No mechanism exists. The closest element — the ElectionConstitution (external policy referenced by GovernanceState) — governs governance transitions, not audit scope.

**36E-03-CCA-17:** NOT satisfied.

---

### AC-18: Bounded context design and authority boundary design are separate architectural activities requiring separate artifacts

**Affected elements:** Round 35D (Context Choreography + embedded Authority Catalog), discovery documentation

**Assessment:** PARTIAL

**Evidence:** Round 35D contains both a context choreography map AND an Authoritative Source Catalog (Part 1). The Authoritative Source Catalog is authority-map content embedded within a context-focused document. This is the correct CONTENT — seven authorities, their owners, their consumers — but it was produced as part of context choreography analysis rather than as a separate activity with separate governing principles.

The embedded location means that authority partitioning decisions (which is the domain-level equivalent of the authority map artifact) were made simultaneously with context partitioning decisions — not as a separate activity. This is a process gap against AC-18, though the content gap is smaller than it might appear (authority content exists; it needs to be extracted into a standalone artifact).

**36E-03-CCA-18:** PARTIALLY satisfied. Authority relationship content (Round 35D Authoritative Source Catalog) exists. A separate authority map artifact with independent governing principles does not exist. The existing content provides a strong foundation for creating the separate artifact.

---

### AC-19: Authority partitioning must be designed separately from context partitioning

**Affected elements:** Context-to-authority mapping (current model equates authority boundaries with context boundaries)

**Assessment:** WEAK

**Evidence:** In the current model, each authority is owned by a context, and authority boundaries follow context boundaries:
- Trust Attestation authority → Trust Attestation context boundary
- GovernanceState authority → Constitutional Governance context boundary
- Vote Record authority → Voting context boundary

This is the expected mapping in many DDD models, and it may be architecturally sound. However, OBS-36D-02-1 (Authority ≠ Context) established that authority boundaries and context boundaries are different concerns that may diverge. The current model has not explicitly evaluated whether the current alignment is constitutionally justified or merely a default.

The critical case: D43 instances. For each D43 function (Enrollment, Criteria, Audit, GovernanceAuth, Certification), the independent authority relationship required by AC-05 may cross context boundaries. If the challenge authority for verification decisions must be independent of the Trust Attestation context, and there is no such independent context yet, then authority partitioning and context partitioning WILL diverge when constitutional constraints are satisfied — they have not yet been forced to diverge because the constitutional constraints are not yet satisfied.

**36E-03-CCA-19:** WEAKLY satisfied. Current model aligns authority boundaries with context boundaries. Explicit evaluation of whether this alignment is constitutionally justified has not been performed. The alignment is likely to diverge as AC-05 constraints are satisfied.

---

### AC-20: Trust concentration risk must be assessed at authority map level, not context map level

**Assessment:** NONE

**Evidence:** Trust concentration assessment has been performed at discovery level (36C-06, TF-36C-06-02 through -09) — but this was a threat modeling assessment of the discovered patterns, not an assessment at the authority map level as a domain model artifact. No authority map artifact exists yet (AC-18 gap), so assessment at that artifact's level cannot occur.

**36E-03-CCA-20:** NOT satisfied as a model-level activity. Discovery-level concentration risk has been identified (36C). Authority-map-level assessment awaits the authority map artifact.

---

### AC-21: Enrollment must include representations for L-1, L-2, L-3, L-4, L-5

**Assessment:** NONE

**Evidence:** Enrollment authority owner is UNRESOLVED (AUTHORITY-GAP-1). No enrollment authority exists to apply L-1/L-5 specifications to.

**36E-03-CCA-21:** NOT satisfied. Gap is at the AC-05 level (no owner) — all five legitimacy dimensions are unaddressed because the authority itself has no owner.

---

### AC-22: Criteria authority must include external revocation pathway not routing through criteria authority

**Affected elements:** GovernanceState (presumptive criteria holder), ElectionConstitution (external policy)

**Assessment:** NONE

**Evidence:** Criteria are presumptively held by GovernanceState as configuration parameters. The only revocation pathway for criteria would be a GovernanceState transition that changes criteria values. This transition routes through GovernanceState itself — self-revocation of the criteria authority. AC-22 specifically requires that the revocation pathway NOT route through the criteria authority being revoked. No external criteria revocation pathway exists.

**36E-03-CCA-22:** NOT satisfied.

---

### AC-23: Criteria ratification process must be a first-class architectural concern

**Affected elements:** GovernanceState (criteria configuration)

**Assessment:** NONE

**Evidence:** Criteria in the current model are held as configuration within GovernanceState. The ratification of criteria changes — the constitutional process by which membership decides that eligibility rules are valid — is not modeled. GovernanceState's precondition model (CG-2) enforces that transitions require valid preconditions, but the criteria ratification process is a constitutional act that precedes and authorizes criteria values, not a precondition check on a state transition.

**36E-03-CCA-23:** NOT satisfied.

---

### AC-24: Audit must include external scope definition, external access pathway, IR-H independence structure

**Affected elements:** Audit Context (entire)

**Assessment:** NONE

**Evidence:** AC-24 is the composite audit constraint from C5. None of its three components are satisfied:
- External scope definition: absent (Gap A-3)
- External access pathway: absent (AC-15 partial)
- IR-H independence structure: absent (Audit Context is internal)

**36E-03-CCA-24:** NOT satisfied. The Audit Context exists and performs internal observation, but it does not satisfy any of the three components of AC-24.

---

### AC-25: Governance authorization authority and execution authority must be architecturally distinguishable

**Affected elements:** GovernanceState, Officer actors, ADH-1

**Assessment:** WEAK

**Evidence:** GovernanceState enforces CG-2 (preconditions must be satisfied before transitions). The precondition check and the state transition are atomic (CG-1). But WHO is authorized to issue a transition command is governed by ADH-1 — an unresolved design debt. The model knows THAT a transition requires preconditions; it does not know WHO has constitutional authority to declare preconditions satisfied and initiate transition.

In the current model, the actor who initiates a transition (officer) and the actor who "authorizes" the transition (the same officer, implicitly) are not distinguished. GovernanceState enforces the transition rules — it is both the authorization check (CG-2) and the execution mechanism (CG-1). This conflation is the AC-25 gap.

**36E-03-CCA-25:** WEAKLY satisfied. The structure exists (GovernanceState enforces preconditions) but authorization authority is not separated from execution authority. ADH-1 is the formal expression of this gap.

---

### AC-26: Governance authorization authority holder must be identified as first-class architectural concern (L-2)

**Assessment:** NONE

**Evidence:** ADH-1 (who may authorize governance transitions) is unresolved. No authority holder for governance authorization has been identified.

**36E-03-CCA-26:** NOT satisfied — blocked by ADH-1.

---

### AC-27: Certification must include external challenge pathway (L-3)

**Assessment:** NOT APPLICABLE — no certification mechanism exists in the current model

**Evidence:** The current DDD model does not have a certification aggregate, context, or domain service. Certification is one of the D43 instances with no modeled coverage at all.

**36E-03-CCA-27:** NOT APPLICABLE. Gap is prior to this constraint.

---

### AC-28: Certification element must be designed with awareness of complete legitimacy gap and terminal risk

**Assessment:** NOT APPLICABLE — no certification mechanism exists

**36E-03-CCA-28:** NOT APPLICABLE. Gap is prior to this constraint.

---

### AC-29: Evidence presence verification and evidence authenticity verification must be architecturally distinguished as distinct concerns

**Affected elements:** Vote (DataChecksum — VO-2), Audit Context, Verification Representation Context (D42B)

**Assessment:** PARTIAL

**Evidence:**

**Presence side:** The Audit Context observes VoteRecorded events. Presence of a VoteRecorded event demonstrates that the vote was recorded. There is no current mechanism to verify that ALL votes that should have been recorded ARE present — this is Gap A-3 again.

**Authenticity side:** The Vote aggregate generates DataChecksum (VO-2) at the time of vote recording. This SHA-256 integrity check enables authenticity verification of individual vote records — if a VoteRecorded event's data changes after recording, the DataChecksum will not match. This is an authenticity mechanism.

However: the DataChecksum is generated BY the Vote aggregate and stored INSIDE the election system. An external party auditing authenticity would need to:
1. Obtain the vote record (AC-15 gap)
2. Compute the checksum of the obtained record
3. Compare against the stored DataChecksum... which is also held by the same election system

Step 3 is the problem: the reference standard for authenticity verification is held by the system being verified. This is the emerging concern from EH-01.

**Architectural distinction:** The current model does NOT architecturally distinguish presence from authenticity as separate concerns. Both are handled by the same Audit Context through the same event observation pathway.

**36E-03-CCA-29:** PARTIALLY satisfied. DataChecksum (VO-2) enables authenticity checking of individual votes. No architectural separation between presence and authenticity verification exists at the audit layer. The reference standard for authenticity is internally held — see Section 5 (EH-01 assessment).

---

### AC-30: Criteria must be represented with sufficient precision to preclude multiple valid interpretations

**Affected elements:** GovernanceState (presumptive criteria holder), ElectionConstitution (external policy)

**Assessment:** WEAK

**Evidence:** Criteria are presumptively held by GovernanceState as configuration. The TF-36C-04-03 finding (Criteria Ambiguity Exploitability) directly applies: if criteria are stored as human-readable configuration values, multiple valid interpretations are possible. The current GovernanceState model does not specify what form criteria take — they are referenced as an external constitutional policy (ElectionConstitution) consulted during precondition checks, but the precision of their representation is not a modeled concern.

**36E-03-CCA-30:** WEAKLY satisfied. Criteria exist in the model (GovernanceState / ElectionConstitution). Their precision of representation is not a modeled concern. The constitutional precision requirement (AC-30) is not addressed.

---

### Section 1 Summary: Coverage Assessment

| Constraint | Coverage | Primary Gap |
|-----------|---------|------------|
| AC-01 | Partial | L-1/L-5 constitutional legitimacy layer absent |
| AC-02 | Weak | Audit self-verification; ADH-1 self-authorization |
| AC-03 | Weak | L-1 candidate signal (ElectionConstitution); L-2 through L-5 entirely absent |
| AC-04 | Partial | Authority content exists; not modeled as domain elements |
| AC-05 | None | Zero D43 functions have independent authority relationships |
| AC-06 | N/A | Prerequisite (AC-05) not satisfied |
| AC-07 | None | No authority relationship has L-1/L-5 specifications |
| AC-08 | None | Append-only design pattern prevents external challengeability |
| AC-09 | None | No challenge mechanism exists |
| AC-10 | None | No challenge mechanism exists |
| AC-11 | Partial | Domain entity lifecycles modeled; authority holder lifecycle absent |
| AC-12 | Weak | Revocation of decisions exists; revocation of authority from outside absent |
| AC-13 | None | No succession mechanism |
| AC-14 | None | Gap A-3 — no external audit scope definition |
| AC-15 | Partial | Internal audit observation; external access pathway absent |
| AC-16 | None | Prerequisite (AC-14) missing |
| AC-17 | None | No external expected-evidence-set mechanism |
| AC-18 | Partial | Authority content exists embedded in choreography document |
| AC-19 | Weak | Authority boundaries follow context boundaries; unconstitutionally evaluated |
| AC-20 | None | No authority map artifact exists for assessment |
| AC-21 | None | Enrollment has no owner |
| AC-22 | None | Criteria revocation routes through criteria authority itself |
| AC-23 | None | Criteria ratification not modeled |
| AC-24 | None | All three components absent |
| AC-25 | Weak | ADH-1 gap — authorization not separated from execution |
| AC-26 | None | ADH-1 — no authority holder identified |
| AC-27 | N/A | No certification mechanism exists |
| AC-28 | N/A | No certification mechanism exists |
| AC-29 | Partial | DataChecksum enables individual vote authenticity; audit layer lacks architectural distinction |
| AC-30 | Weak | Criteria exist; precision as constitutional requirement not modeled |

**Coverage Pattern:** Strong = 0 | Partial = 6 (AC-01/04/11/15/18/29) | Weak = 7 (AC-02/03/12/19/25/30) + AC-03 promoted from None to Weak (ElectionConstitution as candidate L-1) | None = 13 | N/A = 3

The model satisfies zero constraints strongly. Seven are partially addressed. The majority (14) have no current coverage. This is the expected result at this stage of discovery — the constitutional constraints were discovered AFTER the model was built; the model was built to satisfy domain behavioral invariants, not constitutional legitimacy requirements.

---

## Section 2 — Pressure Area Impact Assessment

### CPR-01: External Independence Cluster

**Governing question:** Which current contexts assume internal authority? Which aggregates implicitly self-authorize? Which workflows assume authority and execution are co-located?

---

**36E-03-PIA-01-1:** Trust Attestation context assumes internal authority.

The Trust Attestation context owns verification decisions (A1). The officer who grants or revokes verification is an actor recognized by the context itself. There is no external constitutional authority that certifies officer status — the system recognizes officers internally. The independence question (AC-05) asks: is there a constitutionally independent authority relationship for the verification function? The answer is no: the officer's authority to verify others is self-granted by the system that the officer operates within.

**36E-03-PIA-01-2:** GovernanceState implicitly self-authorizes governance transitions.

GovernanceState enforces preconditions (CG-2) but the authority to declare preconditions satisfied and issue transition commands resides with officers who are internal actors. ADH-1 is the formal gap: "who is authorized to trigger governance transitions?" If the answer is "officers as recognized by this system," then constitutional authority for governance transitions is self-granted by the election system. This is the AC-25 gap made concrete: the Constitutional Governance context authorizes AND executes transitions without a constitutionally independent authorization structure.

**36E-03-PIA-01-3:** Voting workflow assumes Eligibility and Vote are the complete authority chain.

The voting choreography (35D Choreography 1) shows: Eligibility evaluates three inputs, produces ELIGIBLE/NOT ELIGIBLE, and if ELIGIBLE, Vote records the ballot. The constitutional authority chain for "may this person vote?" runs entirely through internal contexts. There is no external constitutional authority that validates the eligibility determination. The double-check (Vote reads VotingAuthorized independently from Eligibility) is a behavioral redundancy, not a constitutional independence.

**36E-03-PIA-01-4:** The pull-over-push model has a constitutional authority gap.

The model's core architectural principle — "Publish Authority. Consume Authority. Evaluate On Demand." — is sound for distributing behavioral authority across contexts. However, it does not address CONSTITUTIONAL authority: the model correctly distributes which context makes which decision, but does not address whether any context's authority is constitutionally grounded. Distribution of behavioral responsibility is not the same as constitutional independence (OBS-36D-02-1).

**CPR-01 impact on current model:** HIGH. All three approved aggregates and all established contexts contain implicit assumptions of internal authority. The entire model was designed to enforce behavioral invariants correctly — it was not designed with constitutional legitimacy as a first-class concern. CPR-01 pressure is system-wide, not localized.

**Governing discipline note (binding):** CPR-01 establishes that constitutionally independent authority relationships are required. The architectural realization of that independence remains unresolved (CF-05-19). No conclusion should be drawn from this section regarding: separate contexts, separate services, separate organizations, or external entities. OBS-36D-02-1 (Authority ≠ Context) and CF-05-19 (realization unresolved) remain binding through 36E-04.

---

### CPR-02: Audit Scope Authority

**Governing question:** Where is audit scope represented today? Is Gap A-3 already modeled? Is audit completeness represented explicitly?

---

**36E-03-PIA-02-1:** Audit scope is not represented in any current model element.

The Audit Context observes events. The events it observes are determined by which events other aggregates publish and which events Audit subscribes to. There is no model element that defines "what events MUST be observed for the audit to be constitutionally complete." The scope is operationally defined by subscription choices, not by constitutional mandate.

**36E-03-PIA-02-2:** Gap A-3 is explicitly unmodeled.

From 36B: Gap A-3 (expected evidence set undefined) is the PRIMARY AUDITABILITY RISK. The current DDD model has no element that corresponds to an expected evidence set definition. There is no aggregate, value object, domain service, or policy that represents "the complete set of evidence that must be present for this election to be auditable."

**36E-03-PIA-02-3:** Audit completeness is not represented.

The Audit Context currently accumulates what it observes. There is no mechanism by which the context can determine whether what it has accumulated is COMPLETE relative to what SHOULD have been accumulated. This is not a database completeness problem — it is a constitutional problem: who defines completeness, and how does the audit function verify that completeness has been achieved?

**CPR-02 impact on current model:** CRITICAL. Gap A-3 remains fully open. No current model element addresses audit scope authority, expected evidence set, or audit completeness. The Audit Context exists but operates without a constitutional mandate for what it must observe.

---

### CPR-03: Dual Authority Map

**Governing question:** Does the current DDD model already contain implicit authority structures? Are authority relationships visible? Are they hidden inside contexts?

---

**36E-03-PIA-03-1:** The Round 35D Authoritative Source Catalog is an implicit authority map.

Round 35D Part 1 produced an Authoritative Source Catalog documenting seven authorities, their owners, published values, and consumers. This is authority map content — it describes authority relationships between contexts. It is embedded in a context choreography document and produced as a precursor to choreography analysis, but its content is appropriate for a standalone authority map.

**36E-03-PIA-03-2:** Authority relationships inside the current model are hidden inside context boundaries.

The key insight from OBS-36D-02-1 (Authority ≠ Context) is that authority boundaries and context boundaries may diverge. In the current model, authority relationships are defined by context ownership: "the Trust Attestation context owns the Trust Attestation authority." Authority relationships are not visible as independent elements — they are implied by context membership.

For example: the officer's authority to grant verifications is visible only by inference (an officer interacts with the Trust Attestation context, therefore the context's rules govern what the officer can do). The constitutional authority relationship (what gives the officer the mandate to grant verifications?) is hidden inside the context boundary, not modeled as a visible element.

**36E-03-PIA-03-3:** Implicit authority structures exist throughout the discovered model.

Every context that owns an authority (Trust Attestation, Constitutional Governance, Voting) has an implicit authority structure — the officer-or-actor model that determines who can exercise the context's authority. These implicit structures are:
- Partially captured by ADH-1 (governance transition authority — identified as a gap)
- Partially captured by TA-2 (officer attribution — recorded for verification decisions)
- Not captured at all for Audit, Criteria, Enrollment, or Certification

**CPR-03 impact on current model:** MEDIUM. The proto-authority-map content from Round 35D provides a strong foundation. The primary gap is: (a) the content is embedded, not a standalone artifact; (b) implicit authority structures inside each context are not modeled as explicit authority relationships; (c) independence properties of those relationships are not assessed.

---

### CPR-04: Certification Terminal Node

**Governing question:** Which current aggregates participate in certification? What legitimacy assumptions exist? What challenge assumptions exist?

---

**36E-03-PIA-04-1:** No aggregate or context participates in certification.

The current DDD model — Verification, GovernanceState, Vote, Eligibility, Audit, Verification Representation, Governance Evidence Replay (candidate), Authorization (candidate) — contains no certification mechanism of any kind. There is no aggregate that produces a certification decision. There is no context responsible for certification authority. There is no domain event that represents "election certified."

**36E-03-PIA-04-2:** Results/Tallying (D39 deferred) is the closest proximity to certification.

The Results/Tallying context (boundary identified, design deferred) would be responsible for producing election results. Whether result publication constitutes certification, or whether certification is a separate constitutional act following result publication, is unresolved. D39 must be resolved before certification positioning can be determined.

**36E-03-PIA-04-3:** The absence of certification is the constitutional highest-risk gap.

TF-36C-05-11: Certification is the terminal constitutional act — TC-4 failure amplifies all prior threat classes. AC-28 requires that any certification element be designed with awareness of the complete legitimacy gap and terminal risk. The current model has neither a certification element NOR awareness of this gap modeled anywhere. The constitutional stakes of this absence are documented in 36C-05 but not yet reflected in any model element.

**CPR-04 impact on current model:** CRITICAL. Certification has zero current model coverage. The terminal constitutional act is entirely unaddressed by existing DDD elements.

---

### CPR-05: Evidence Integrity Decomposition

**Governing question:** Where is evidence represented? Where is authenticity represented? Where is completeness represented?

---

**36E-03-PIA-05-1:** Evidence is represented by Vote events and Audit Context accumulation.

VoteRecorded events (from Vote aggregate) constitute the primary evidence of vote recording. GovernanceTransitionCompleted events constitute evidence of governance lifecycle. The Audit Context observes and accumulates these events. This covers evidence EXISTENCE.

**36E-03-PIA-05-2:** Authenticity is partially represented by DataChecksum (VO-2).

The Vote aggregate generates DataChecksum (SHA-256 of ballot content) at recording time (VO-2). This enables integrity checking of individual vote records. However: (a) the reference for comparison is held by the same system, (b) the scope is individual votes only — not composite audit evidence, (c) this is not yet exposed to external auditors (AC-15 gap).

**36E-03-PIA-05-3:** Completeness is not represented.

No element of the current model represents "the expected complete set of evidence." Gap A-3 states this explicitly. The Audit Context can confirm what evidence it HAS; it cannot confirm that what it has is ALL of what should exist.

**36E-03-PIA-05-4:** Presence verification and authenticity verification are not architecturally distinguished.

In the current model, the Audit Context handles both: it observes events (presence) and implicitly accepts them as authentic (because they came through the internal event pathway). There is no architectural distinction between "VoteRecorded is present" and "VoteRecorded is authentic." The DataChecksum (VO-2) enables authenticity checking in principle, but there is no architectural component that performs authenticity checking as a distinct function.

**CPR-05 impact on current model:** HIGH. Evidence existence is modeled (Vote events, Audit Context). Evidence authenticity is partially addressable (DataChecksum). Evidence completeness is unmodeled (Gap A-3). Presence and authenticity are not architecturally distinguished. The stratified evidence architecture required by CPR-05 does not yet exist.

---

## Section 3 — D42B Reassessment

**D42B background:** Round 36A-09 resolved D42B by establishing the Verification Representation Context (VRC) as the boundary responsible for the verifiability guarantee. Specifically: the voter's right to confirm their vote was counted as cast requires a context that is constitutionally independent of the Vote aggregate — the Vote aggregate cannot self-certify verifiability.

**Re-evaluating D42B under AC-18, AC-19, AC-20, and CPR-03:**

---

**36E-03-D42B-1:** D42B resolution remains valid.

The constitutional argument underlying the D42B resolution is strengthened, not weakened, by the authority map constraints:
- AC-02 (no self-verification) directly supports the D42B conclusion: the Vote aggregate cannot be the verifier of its own vote-count-as-intended guarantee
- The Verification Representation Context is structurally aligned with AC-02: it provides an independent verification pathway separate from the Vote aggregate's boundary

The D42B conclusion — that a separate context is needed for verifiability representation — becomes more constitutionally necessary, not less, under the 36E-01 constraints.

---

**36E-03-D42B-2:** AC-18/19 require that D42B's authority boundary be explicitly distinguished from its context boundary.

D42B was resolved as a context boundary decision: "Verification Representation Context" is a bounded context. Under AC-18/19, the AUTHORITY BOUNDARY of this context must be separately established: what is the constitutional mandate of VRC? Specifically:
- What is VRC's L-1 (legitimacy source)? — The voter's constitutional right to verify their ballot was counted as cast.
- What is VRC's L-2 (authority holder)? — Not yet identified; the context boundary is established but the authority holder within it is not.
- What is VRC's L-3 (challenge mechanism)? — Not yet modeled.

The authority map must contain a separate entry for VRC's constitutional mandate, distinct from its context boundary definition.

---

**36E-03-D42B-3:** AC-20 (trust concentration at authority map level) applies to VRC positioning.

VRC was designed to prevent Vote from self-certifying. If VRC is positioned correctly — as a constitutionally independent verifiability authority — it reduces trust concentration in the Voting context. If VRC remains closely coupled to Voting (e.g., consuming Vote's internal data structures directly), the independence may be nominal (AC-06 risk). The authority map must explicitly assess whether VRC's trust distribution is genuine or nominal.

---

**36E-03-D42B-4:** VRC partially addresses EH-01 (Verifier Independence hypothesis).

VRC was designed to separate the verifier (VRC) from the evidenced system (Vote). This directly addresses the "independent verifier" part of EH-01 (the derived claim). Whether VRC also provides an independent reference standard (the hypothesized claim) depends on VRC's design — specifically, whether the reference point for verification (what the voter is checking against) is generated independently of the Vote aggregate's internal state.

If VRC reads Vote's internal state as its reference, the reference standard is the system's own representation — which is the EH-01 concern. If VRC uses an independently generated or externally anchored reference (e.g., a receipt delivered to the voter independently of the Vote aggregate), the reference standard is independent. This design question belongs to 36E-04 — but it is the right question to ask of D42B.

---

**D42B Reassessment Conclusion:** D42B remains VALID. It is STRENGTHENED by the authority map constraints (AC-02 principle directly supports it). It requires EXTENSION: (a) VRC's authority boundary must be separated from its context boundary in the authority map; (b) VRC's L-1/L-2 must be identified; (c) VRC's independence must be assessed for nominality (AC-06 risk); (d) VRC's reference standard design must address EH-01.

---

## Section 4 — D43 Reassessment

All five D43 instances are reassessed against the architectural constraints from 36E-01.

---

### D43-ENROLL (Enrollment Authority)

**Current status:** Owner unresolved (AUTHORITY-GAP-1). No context, aggregate, or authority holder established.

**Constraint impacts:**

| Constraint | Impact |
|-----------|--------|
| AC-05 | Cannot be satisfied — no authority to make independent |
| AC-07 | Cannot apply — no authority relationship to specify |
| AC-11 | Cannot apply — no authority lifecycle to model |
| AC-21 | All five dimensions (L-1/L-5) undefined and unaddressable |

**36E-03-D43-1:** D43-ENROLL is the most foundational gap in the model.

Without enrollment authority, Eligibility's three-precondition model (Trust Attestation + VotingAuthorized + EnrollmentStatus) remains constitutionally incomplete. The pull-over-push choreography cannot be completed for the voting flow — one of the three authorities that Eligibility must evaluate has no owner, no authority relationship, no independence structure, and no L-1/L-5 specification. CPR-01 (External Independence) applies directly but cannot be evaluated until an owner is established.

**Architecture pressure:** CRITICAL — Enrollment must be resolved before constitutional constraint satisfaction can begin for this D43 instance.

---

### D43-CRITERIA (Criteria Authority)

**Current status:** GovernanceState holds criteria as configuration (PRESUMPTIVE). Constitutional criteria ownership unresolved.

**Constraint impacts:**

| Constraint | Impact |
|-----------|--------|
| AC-22 | Not satisfied — criteria revocation routes through GovernanceState |
| AC-23 | Not satisfied — criteria ratification not modeled |
| AC-30 | Weakly addressed — criteria exist as configuration; precision not modeled |
| TF-36C-04-03 | DIRECTLY APPLIES — ambiguous criteria in GovernanceState are constitutionally exploitable |

**36E-03-D43-2:** D43-CRITERIA has the highest exploitability risk of all five D43 instances.

TF-36C-04-03 established that criteria ambiguity exploitability is one of the program's most important findings: perfect evidence + ambiguous criteria = dishonest result appears valid. The current model holds criteria as GovernanceState configuration — the same context that governs election lifecycle transitions. This means:

1. Criteria authority is co-located with election execution authority (AC-25 equivalent for criteria)
2. Criteria precision is not a modeled concern (AC-30 not addressed)
3. Criteria ratification (AC-23) does not exist
4. Criteria revocation (AC-22) routes through the criteria authority itself

The criteria gap combines AC-22, AC-23, AC-25, and AC-30 into a compound constitutional risk.

**Architecture pressure:** HIGH — multiple constitutional constraints converge on this gap.

---

### D43-AUDIT (Audit Authority)

**Current status:** Audit Context exists as internal observer. Gap A-3 (expected evidence set) open. No external scope-defining authority.

**Constraint impacts:**

| Constraint | Impact |
|-----------|--------|
| AC-14 | Not satisfied — scope defined internally (subscription choices) |
| AC-15 | Partially satisfied — internal observation exists; external pathway absent |
| AC-16 | Cannot be assessed — no audit scope authority to separate |
| AC-17 | Not satisfied — no external evidence-set mechanism |
| AC-24 | Not satisfied (composite) |
| Gap A-3 | Remains fully open |

**36E-03-D43-3:** D43-AUDIT is the most structurally incomplete D43 instance relative to the current model.

The Audit Context is the most developed of the D43 instances in terms of existing model elements — it has a recognized context boundary and observes real events. But every constitutional constraint targeting audit (AC-14/15/16/17/24) is either unsatisfied or only partially addressed. The Audit Context as currently modeled is a behavioral audit mechanism (it observes events) without a constitutional audit mandate (who defines what must be observed, from where, and with what independence guarantee).

**ET-03 connection:** The question of who defines audit scope (ET-03) applies directly to D43-AUDIT — see Section 6 for full evaluation.

**Architecture pressure:** CRITICAL — the Audit Context exists but requires constitutional mandate assignment for every C5 constraint.

---

### D43-GOV-AUTH (Governance Authorization Authority)

**Current status:** ADH-1 open — who may authorize governance transitions is unresolved.

**Constraint impacts:**

| Constraint | Impact |
|-----------|--------|
| AC-25 | Weakly addressed — authorization authority and execution authority conflated in GovernanceState |
| AC-26 | Not satisfied — authority holder for governance authorization not identified |
| AC-08 | Not satisfied — governance authorization decisions not externally challengeable |

**36E-03-D43-4:** D43-GOV-AUTH is the closest to resolution of all D43 instances, but has a specific structural gap.

GovernanceState is the most sophisticated aggregate in the model. It correctly enforces precondition-gated atomic transitions (CG-1/2). The constitutional gap is surgical: WHO has the constitutional authority to say "preconditions are satisfied, proceed"? This is ADH-1. The model knows the mechanism (precondition enforcement); it does not know the constitutional mandate of the actor who activates that mechanism.

Once ADH-1 is resolved, AC-25 (separation of authorization and execution) becomes the next pressure: the current design conflates authorization (actor declares preconditions satisfied) with execution (GovernanceState transitions). Whether these must be architecturally separated is the AC-25 question for this instance.

**Architecture pressure:** HIGH — ADH-1 resolution is a prerequisite; once resolved, AC-25 separation becomes the next design question.

---

### D43-CERT (Certification Authority)

**Current status:** No certification mechanism exists anywhere in the current DDD model.

**Constraint impacts:** ALL constraints from AC-27, AC-28, plus all cross-cutting constitutional constraints (AC-03/05/07/08/11/12/13).

**36E-03-D43-5:** D43-CERT has zero coverage in the current model — it is the constitutional blank space.

D43-CERT presents a paradox: it is both the highest-risk D43 instance (TF-36C-05-11: terminal act, amplifies all prior threat classes) AND the one with the least modeled content. The current DDD model ends at evidence collection (Audit Context) and tentatively at results publication (Results/Tallying, D39 deferred). Certification as the constitutional act of validating and declaring the election outcome exists as neither a boundary, nor an aggregate, nor an authority catalog entry.

Results/Tallying (D39 deferred) may or may not include certification — this depends on whether certification is understood as a sub-function of result publication or as a separate constitutional act. That determination cannot be made until D39 is resolved.

**Architecture pressure:** CRITICAL — the highest-risk constitutional act is entirely unrepresented. CPR-04 (Certification Terminal Node) applies with maximum force.

---

### D43 Reassessment Summary

| D43 Instance | Current Model Coverage | Most Binding Constraint | Pressure |
|---|---|---|---|
| D43-ENROLL | None (no owner) | AC-05 / AC-21 | Critical |
| D43-CRITERIA | Weak (config in GovernanceState) | AC-30 / TF-36C-04-03 | High |
| D43-AUDIT | Partial (internal observer only) | AC-14 / AC-24 / Gap A-3 | Critical |
| D43-GOV-AUTH | Weak (ADH-1 gap) | AC-25 / AC-26 | High |
| D43-CERT | None | AC-27 / AC-28 / terminal risk | Critical |

Three of five D43 instances have CRITICAL gaps. None are constitutionally complete.

---

## Section 5 — EH-01 Validation Assessment

**EH-01 (Emerging Hypothesis — Verifier Independence from 36E-02):**
- **Derived claim:** An entity independent of the election system must be able to perform evidence authenticity verification (from AC-02 + AC-15 + IR-H)
- **Hypothesized claim:** That independent verifier must have access to a reference standard that the election system did not unilaterally control (from AC-29 + derivation step 4 of 36E-02 Section 7)

The 36E-02 ARB instructed 36E-03 to: (A) assess whether evidence requires an independent verifier, (B) assess whether evidence requires an independent reference standard, and (C) determine whether EH-01 meets the promotion threshold.

---

**36E-03-EH-01-1: Question A — Does the evidence require an independent verifier?**

Evidence from the DDD model:

The current model's audit function is performed by the Audit Context — an internal component of the election system. The Audit Context observes events published by other internal components (Vote, GovernanceState). The "verifier" in the current model is the election system itself.

AC-02 applied: The election system cannot constitutionally verify its own evidence integrity for constitutional purposes. The Audit Context is architecturally self-referential — it is part of the system whose outputs it audits.

AC-15 applied: The constraint requires that the election system expose auditable data through an externally accessible pathway — implying that an external entity needs to access and verify that data. This presupposes an external verifier.

IR-H applied: The audit function must be independent of the audited subject. The Audit Context is NOT independent of the audited subject — it is a component of the same system.

**Conclusion for Question A:** YES — the derived claim is confirmed. The DDD model evidence (Audit Context as internal self-referential observer, no external access pathway, Audit Context as part of the audited system) demonstrates that the current model fails the independence requirement and therefore an independent verifier IS required.

**Assessment: SUPPORTED by current DDD evidence.**

---

**36E-03-EH-01-2: Question B — Does the evidence require an independent reference standard?**

Evidence from the DDD model:

The DataChecksum (VO-2 in Vote aggregate) is the primary authenticity mechanism in the current model. Assessment:
- DataChecksum is generated by the Vote aggregate at recording time
- DataChecksum is stored as a value object within the Vote aggregate's domain boundary
- DataChecksum is the primary reference standard for vote record authenticity

An external auditor wishing to verify that a vote record is authentic would need to:
1. Obtain the vote record
2. Compute its checksum
3. Compare against a reference value

Step 3 — the reference value — is the DataChecksum stored in the Vote aggregate. The reference standard (DataChecksum) is generated and stored by the same system whose records are being audited. An external auditor checking DataChecksum against the stored DataChecksum is checking the system's evidence against the system's own reference.

This is circular: if an adversary could tamper with a vote record AND its associated DataChecksum simultaneously, the reference standard would confirm the tampered record as authentic. The reference standard is only meaningful if it is generated or anchored independently of the record it authenticates.

**Conclusion for Question B:** The DDD model evidence demonstrates the reference standard circularity problem. An independent reference standard (or at minimum, a reference anchor that cannot be tampered simultaneously with the evidence) appears necessary. This is consistent with the hypothesized claim in EH-01.

**Assessment: SUPPORTED by current DDD evidence. Confidence: MEDIUM** — the DataChecksum circularity case is compelling but represents one specific case. Alternative architectures could address this without requiring a fully independent reference standard (e.g., cryptographic commitment schemes where the commitment cannot be post-hoc modified). Whether such alternatives satisfy the constitutional requirement belongs to 36E-04.

---

**36E-03-EH-01-3: Question C — Promotion threshold assessment**

Promotion criteria from 36E-02: Can EH-01 be promoted to a constraint candidate based on DDD model evidence?

Evidence:
- Question A (independent verifier required): SUPPORTED by DDD model evidence
- Question B (independent reference standard): SUPPORTED by DDD model evidence (DataChecksum circularity case)
- Alternative interpretations: Not ruled out — cryptographic commitment schemes may address the reference standard concern without requiring full independence; this possibility remains

**Assessment:**

EH-01 should be promoted from EMERGING HYPOTHESIS to **SUPPORTED HYPOTHESIS**. The DDD model evidence confirms the independent verifier claim (Question A — strong) and the DataChecksum circularity case provides supporting evidence for the independent reference standard claim (Question B — medium confidence).

Important distinction for Question B: the DataChecksum circularity case proves that the *current implementation concept* is insufficient. It does NOT prove that all valid architectures require an independent reference standard. Alternative architectures (cryptographic commitment schemes, external anchoring mechanisms) have not been evaluated — one of them may satisfy the reference standard concern without requiring full independence of the reference standard itself.

**EH-01 does NOT meet the promotion threshold to CONSTRAINT CANDIDATE (AC-31).** The DataChecksum circularity is a single concrete case. Until 36E-04 evaluates whether any available architectural form can satisfy the constitutional requirement without a fully independent reference standard, AC-31 promotion would be premature.

**36E-03-EH-01-4: ARB recommendation**

Promote EH-01 from Emerging Hypothesis to **Supported Hypothesis**. Do NOT promote to AC-31 at this stage. Carry to 36E-04 for architectural response evaluation. AC-31 promotion should be decided after 36E-04 determines whether the independent reference standard is a constitutional necessity or only one of several possible satisfying architectures.

---

## Section 6 — ET-03 Threshold Assessment

**ET-03 (from 36E-02):** External audit scope definition (AC-14) and operational security are in tension. The external scope-defining entity must itself be constitutionally grounded — a possible D43 recursion pattern.

The 36E-02 ARB instructed 36E-03 to evaluate: Is ET-03 (A) a sub-function of D43-AUDIT, (B) a specialization of an existing authority, (C) a new D43-class authority gap, or (D) insufficient evidence?

---

**36E-03-ET03-1: Establish what "audit scope authority" means in the current model**

In the current model, there is no entity that defines audit scope. Audit scope is operationally determined by which events the Audit Context subscribes to — a technical decision. Gap A-3 names this as the "expected evidence set undefined" gap.

AC-14 requires that audit scope definition come from outside the election system. This creates a logical entity: the "audit scope authority" — whoever constitutionally has the right to define what must be evidenced in an election.

Does this logical entity already correspond to something in the discovered model? Candidates:

| Candidate | Evaluation |
|-----------|-----------|
| D43-AUDIT (audit authority) | D43-AUDIT covers the audit function's authority. Scope definition IS a dimension of audit authority — specifically, who has L-1 (legitimacy source) for defining what must be audited? |
| Existing authority (A1-A7) | None of the seven authorities in the catalog addresses audit scope definition |
| Separate D43 instance | Would require that the scope-defining authority is constitutionally distinct from the audit execution authority — an additional independence layer beyond D43-AUDIT itself |

---

**36E-03-ET03-2: Does the audit scope authority match D43-AUDIT's structure?**

D43-AUDIT already has:
- A missing L-1 (who has the constitutional mandate to audit, and on whose authority?)
- A missing L-4 (who can revoke the audit authority?)
- A gap in scope definition (Gap A-3)

Gap A-3 can be understood as the SCOPE dimension of D43-AUDIT's L-1 gap: the legitimacy of the audit function depends partly on who defines what the audit covers. If no external authority defines audit scope, the audit function is self-scoping — which is a self-verification problem (AC-02).

The question is: is scope definition separable from audit execution in the constitutional sense?
- If YES: the scope-defining authority is a separate constitutional mandate — potentially a new D43 instance
- If NO: scope definition is a dimension of D43-AUDIT's L-1 legitimacy — it belongs to D43-AUDIT

---

**36E-03-ET03-3: Threshold analysis**

The evidence for a separate D43 instance:
- AC-16 requires that audit scope authority and election execution authority not share an architectural boundary — but this does not require that scope authority and audit execution authority be separate; it only separates scope from EXECUTION
- The independence requirement (IR-H) applies to the auditor; whether it also applies to the scope-defining authority is an additional claim that has not been explicitly established
- Gap A-3 is described in 36B as an auditability gap — it is the audit's own completeness gap, not a separate authority gap

The evidence against a separate D43 instance:
- Gap A-3 is already named as part of D43-AUDIT's open gaps
- Scope definition and audit execution are functionally inseparable in most constitutional frameworks — the entity that defines what must be audited typically has authority over the audit domain, not a distinct constitutional mandate
- Creating a separate D43 instance would multiply the constitutional authority gaps without clear constitutional necessity

**36E-03-ET03-4: Threshold determination**

**ET-03 = Option A: Sub-function of D43-AUDIT**

The audit scope authority is the L-1 dimension of D43-AUDIT's legitimacy question: what is the constitutional source of the audit mandate, and does that mandate include defining what must be evidenced? This does NOT meet the threshold for a separate D43 instance. Gap A-3 (audit completeness / expected evidence set) and AC-14/17 (external scope definition) are constitutionally housed within D43-AUDIT's own legitimacy gaps (L-1/L-2), not as a separate authority pattern.

The ET-03 tension (external scope vs. operational security) remains valid but is a design constraint on D43-AUDIT's solution, not a discovery of a new constitutional authority type.

**No new D43 instance is created.**

**Open question carried to 36E-04:** The threshold determination (ET-03 = sub-function of D43-AUDIT) resolves the D43 instance question. An open question remains within D43-AUDIT: is audit scope authority a *specialization* of D43-AUDIT (i.e., one of D43-AUDIT's L-1/L-2 dimensions), or a *separate authority relationship* that exists inside D43-AUDIT alongside the operational audit relationship? The distinction matters for the authority map: if they are the same relationship, one independent authority covers both; if they are separate, D43-AUDIT may require two constitutionally independent authority relationships simultaneously. 36E-04 must evaluate this.

---

## Section 7 — DDD Fitness Assessment

For each major DDD artifact, the question is: can this artifact satisfy the discovered constitutional constraints?

Classification:
- **Compatible** — the artifact satisfies or is consistent with constitutional constraints without modification
- **Compatible with Pressure** — the artifact is structurally sound but constitutional constraints create design pressure that must be managed
- **Significant Gap** — the artifact does not satisfy constitutional constraints in areas where it would be expected to; gap is specific and bounded
- **Requires Future Architectural Decision** — the artifact is incomplete or the constraint cannot be assessed until an upstream decision is made; the artifact cannot be classified until that decision is resolved

---

### Verification Aggregate

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-01 (behavioral ≠ constitutional) | Compatible with Pressure | TA-1/2/3 are behavioral; L-1/L-5 are absent but do not contradict the existing structure |
| AC-02 (no self-verification) | Significant Gap | The officer's constitutional mandate is not verified by any external authority |
| AC-03 (L-1/L-5 first-class) | Significant Gap | Officer Attribution records identity but not constitutional mandate |
| AC-08 (external challengeability) | Significant Gap | Append-only (TA-3) prevents mutation; challenge must be modeled as non-mutating pathway |
| AC-11 (authority lifecycle) | Compatible with Pressure | Verification lifecycle exists (PENDING→ACTIVE→REVOKED); officer authority lifecycle absent |
| AC-12 (revocation from outside) | Significant Gap | Revocation of decisions exists; revocation of officer's authority from outside absent |
| AC-13 (succession) | Significant Gap | No succession mechanism |

**Overall: Significant Gap.** The Verification aggregate's behavioral design (TA-1/2/3) is constitutionally sound as a behavioral control. The aggregate requires constitutional extension: officer authority must be constitutionally grounded (L-1/L-2), verification decisions must be externally challengeable (AC-08 — as a non-mutating pathway alongside TA-3), and the officer authority's revocation and succession must be modeled.

**36E-03-DFA-01:** Verification aggregate = Significant Gap. Behavioral invariants are correct. Constitutional legitimacy extensions are required. The relationship between TA-3 (append-only) and external challengeability (AC-08) is structurally unresolved — the tension is real; the resolution belongs to 36E-04.

---

### GovernanceState Aggregate

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-01 (behavioral ≠ constitutional) | Compatible with Pressure | CG-1/2/3 are behavioral; ElectionConstitution as external policy gestures toward constitutional legitimacy |
| AC-25 (authorization ≠ execution) | Significant Gap | ADH-1 — authorization authority and execution are conflated |
| AC-26 (authority holder L-2) | Requires Future Architectural Decision | Blocked by ADH-1 |
| AC-08 (external challengeability) | Significant Gap | GovernanceTransitionCompleted is final; no challenge pathway |
| AC-11 (authority lifecycle) | Compatible with Pressure | Election lifecycle modeled (CG-1/2); authority lifecycle absent |
| CG-3 (SuspensionOverlay) | Compatible with Pressure | Suspension overlay protected by CG-3; suspension authorization authority unknown (ADH-1) |

**Overall: Significant Gap (with one Requires Future Architectural Decision).**

The GovernanceState aggregate is the most constitutionally sophisticated in the model — the ElectionConstitution as an external policy represents the closest thing to a constitutional legitimacy reference in any current aggregate. CG-1/2 precondition enforcement is structurally correct. The primary constitutional gaps are: (a) ADH-1 unresolved (who may authorize transitions — AC-25/26), (b) no external challenge pathway for governance decisions (AC-08), (c) the GovernanceState aggregate itself may need to distinguish its state-machine function (valid transitions) from its constitutional function (authorized transitions), per ACQ-13.

**36E-03-DFA-02:** GovernanceState aggregate = Significant Gap + Requires Future Architectural Decision (ADH-1). ADH-1 resolution is the prerequisite for constitutional constraint satisfaction. Once ADH-1 is resolved, AC-25 (authorization vs. execution separation) becomes the primary design pressure.

---

### Vote Aggregate

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-01 (behavioral ≠ constitutional) | Compatible | VO-1 is both behavioral AND constitutional (anonymity is a constitutional requirement not behavioral policy) |
| VO-1 (anonymity) | Compatible | No voter identity in Vote aggregate or its events |
| AC-29 (presence ≠ authenticity) | Compatible with Pressure | DataChecksum (VO-2) addresses authenticity of individual votes; reference standard circularity concern (EH-01) |
| AC-08 (external challengeability) | Significant Gap | VoteRejected is returned to the system; vote recording decisions (VoteRecorded) are not externally challengeable |
| Receipt Hash (VO-3) | Compatible with Pressure | ReceiptHash enables voter-facing verifiability; external reference standard question remains (EH-01/D42B) |

**Overall: Compatible with Pressure** (most constitutionally sound aggregate).

The Vote aggregate is the most constitutionally aligned of the three approved aggregates. VO-1 (anonymity) directly implements a constitutional requirement. DataChecksum (VO-2) and ReceiptHash (VO-3) demonstrate that integrity mechanisms were designed with constitutional intent. The primary constitutional pressure is: (a) DataChecksum reference standard circularity (EH-01), (b) the Vote aggregate's verifiability guarantee requires VRC (D42B) to be constitutionally independent, (c) vote recording decisions are not externally challengeable (AC-08) — but VO-1 (anonymity) constrains what form any challenge can take.

**36E-03-DFA-03:** Vote aggregate = Compatible with Pressure. The Vote aggregate's design is constitutionally sound at the behavioral layer. Constitutional pressure points are: EH-01 (DataChecksum reference standard), D42B/VRC (verifiability independence), and the apparent tension between VO-1 (anonymity prevents vote-linkage) and AC-08 (challenge addressability). This tension directly instantiates EC-01 (challenge addressability vs. receipt-freeness) from 36E-02.

---

### Eligibility Context (stateless evaluator)

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-04 (authority relationships) | Compatible with Pressure | Eligibility reads three authorities; Authority 4 (Enrollment) is a gap |
| Pull-over-push choreography | Compatible | Evaluation-on-demand model is constitutionally sound — no stored eligibility state |
| AC-29 independence | Compatible | Eligibility is stateless — it does not produce evidence, only evaluations |

**Overall: Compatible with Pressure.**

Eligibility's architectural model (stateless pull-over-push evaluator) is constitutionally sound. The three-authority evaluation structure correctly separates Trust Attestation, Voting Authorization, and Enrollment. The constitutional pressure: one of the three authorities (Enrollment, Authority 4) has no established owner, making Eligibility's constitutionally complete evaluation impossible until D43-ENROLL is resolved.

**36E-03-DFA-04:** Eligibility Context = Compatible with Pressure. The stateless evaluator architecture is constitutionally compatible. Pressure from D43-ENROLL (no enrollment authority owner) prevents constitutional completion.

---

### Audit Context

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-14 (external scope) | Significant Gap | Scope defined by subscription choices; no external mandate |
| AC-15 (external access) | Significant Gap | Internal observer; no external access pathway |
| AC-24 (composite) | Significant Gap | All three components absent |
| AC-02 (no self-verification) | Significant Gap | Audit Context is internal to the system it audits |
| Gap A-3 | Significant Gap | Expected evidence set undefined |

**Overall: Significant Gap.**

The Audit Context's purpose (audit) and its implementation (internal event observation) are constitutionally incompatible with the constitutional requirements for audit. The fundamental issue: a constitutionally valid audit function must be independent of the audited subject (IR-H) — the Audit Context is currently a component OF the audited subject. This is not a minor gap; it is a structural incompatibility between the current Audit Context model and the constitutional audit requirements.

**36E-03-DFA-05:** Audit Context = Significant Gap. The Audit Context correctly accumulates events for internal audit observation. It cannot, in its current form, satisfy constitutional audit requirements (IR-H, AC-14/15/24). The transition from "internal audit observer" to "constitutionally independent audit function" is one of the most significant architectural decisions facing the program.

---

### Verification Representation Context (D42B Resolution)

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-02 (no self-verification) | Compatible | VRC is separate from Vote — enables independent verification |
| EH-01 (independent reference standard) | Requires Future Architectural Decision | Reference standard design not yet specified |
| AC-03 (L-1/L-5) | Significant Gap | VRC's authority basis (L-1/L-2) not yet established |
| AC-15 (external access) | Compatible with Pressure | VRC could provide external access for voter verification; design deferred |

**Overall: Compatible with Pressure (context boundary established; design deferred).**

The VRC boundary establishment (D42B resolution) is constitutionally sound in principle. The authority basis and internal design are not yet established. The constitutional constraints create clear pressure on what VRC's internal design must achieve — particularly EH-01 (reference standard independence) and L-1/L-2 specification.

**36E-03-DFA-06:** Verification Representation Context = Compatible with Pressure. Context boundary is constitutionally justified (D42B + AC-02). Internal design and authority basis require future architectural decision.

---

### Authority Catalog / Proto-Authority Map (Round 35D Part 1)

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-18 (separate artifacts) | Significant Gap | Authority content embedded in context choreography document |
| AC-19 (separate partitioning) | Significant Gap | Authority boundaries follow context boundaries; not independently evaluated |
| AC-20 (trust concentration at authority map level) | Significant Gap | No standalone authority map exists for concentration assessment |
| AC-04 (authority relationships) | Partial | Seven authorities named; relationships not first-class model elements |

**Overall: Significant Gap.**

The content of the 35D Authoritative Source Catalog is the strongest current evidence that an authority map is achievable. Three authority relationships are established (Trust Attestation, GovernanceState authorities, Vote Record). Four have gaps (Enrollment, Role Assignment, Evidence Integrity, and any constitutional independence relationships). The artifact as produced does not satisfy AC-18/19/20 — it is the right content in the wrong artifact with the wrong governing principles.

**36E-03-DFA-07:** Authority Catalog (Round 35D Part 1) = Significant Gap as a standalone artifact. Content is high quality and provides a strong foundation for a dedicated authority map. The standalone artifact and separate design activity must be created.

---

### Domain Events Catalog

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-08 (challengeability) | Significant Gap | Events are final facts; no challenge event types exist |
| AC-11 (authority lifecycle) | Significant Gap | No authority lifecycle events (only domain lifecycle events) |
| AC-03 (L-1/L-5 elements) | Significant Gap | No constitutional legitimacy events (VerificationGranted records grant, not constitutional mandate) |
| VO-1 compliance | Compatible | No voter-linkage in any Vote event |

**Overall: Significant Gap.**

The discovered domain events correctly represent business facts (VerificationGranted, VerificationRevoked, VoteRecorded, GovernanceTransitionCompleted). They do not and cannot represent constitutional legitimacy facts — there are no events representing authority grants, challenge submissions, revocation of authority (as opposed to revocation of decisions), or succession. The constitutional event vocabulary does not exist.

**36E-03-DFA-08:** Domain Events = Significant Gap. The discovered events are correct for their existing purposes. The constitutional event vocabulary (authority grants, challenges, revocations, succession) is entirely absent.

---

### Choreography Model (Pull-over-Push)

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| CPR-01 (external independence) | Compatible with Pressure | Pull-over-push distributes evaluation correctly; constitutional grounding of authorities is the gap |
| AC-02 (no self-verification) | Compatible with Pressure | Pull model reads external authorities; constitutional independence of those authorities is the gap |
| Saga/Process Manager absence | Compatible | Constitutional authority model does not require sagas |

**Overall: Compatible with Pressure.**

The pull-over-push choreography model ("Publish Authority. Consume Authority. Evaluate On Demand.") is constitutionally sound as an architectural principle. It correctly distributes behavioral authority. The constitutional pressure: the model assumes that published authority values are constitutionally grounded — but constitutional grounding of the publishing contexts is precisely what is missing. Pull-over-push distributes function correctly; it does not ground function constitutionally.

**36E-03-DFA-09:** Choreography Model = Compatible with Pressure. The architectural principle is constitutionally compatible. Constitutional grounding of published authority values is the outstanding requirement.

---

### GovernanceState's ElectionConstitution Reference

| Constitutional Requirement | Assessment | Evidence |
|---|---|---|
| AC-03 (L-1/L-5) | Compatible with Pressure | ElectionConstitution IS a candidate L-1 legitimacy source for governance; not yet modeled as one |
| AC-30 (criteria precision) | Compatible with Pressure | Constitutional document provides criteria source; precision of representation not specified |

**Overall: Compatible with Pressure.**

The ElectionConstitution reference in GovernanceState is the most significant constitutional legitimacy artifact in the current model. It is currently an EXTERNAL POLICY reference — acknowledged as an input to precondition evaluation but not modeled as a domain artifact. Under AC-03, ElectionConstitution could serve as the L-1 source for multiple authority relationships. Elevating it from an external policy reference to a first-class authority source model is a potential path forward for multiple D43 instances.

**36E-03-DFA-10:** ElectionConstitution reference = Compatible with Pressure. This is the strongest constitutional legitimacy signal in the current model. It warrants explicit authority-map representation as a potential L-1 source.

---

## Section 8 — Program Answer

**Can the current DDD model satisfy the constitutional discoveries, and where are the gaps?**

**Short answer:** No. The current model satisfies zero constitutional constraints strongly. Seven are partially addressed. The model was built to enforce behavioral invariants correctly — which it does well — but not to satisfy constitutional legitimacy requirements.

**Where the gaps are (priority order):**

1. **Constitutional legitimacy layer entirely absent** — L-1 through L-5 do not exist as first-class elements anywhere in the model (AC-03). This is the root of most other gaps.

2. **D43-CERT: Certification has zero coverage** — The terminal constitutional act is unrepresented (CPR-04). This is the highest-risk gap.

3. **D43-AUDIT: Audit is internally bounded** — The Audit Context is a component of the system it audits; IR-H requires independence. Gap A-3 remains fully open (CPR-02).

4. **D43-ENROLL: Enrollment has no owner** — Eligibility's three-precondition model cannot be constitutionally completed (CPR-01).

5. **No challenge mechanism exists** — Append-only design (TA-3) and final-fact events are incompatible with external challengeability (AC-08) unless challenge is modeled as a non-mutating pathway (CPR-01, D43 across all instances).

6. **Authority map does not exist as a standalone artifact** — Proto-authority-map content is embedded in choreography analysis; AC-18/19/20 require a separate artifact and activity (CPR-03).

7. **EH-01 is supported** — The DataChecksum circularity case demonstrates that the reference standard for evidence authenticity verification is held by the audited system itself; independent reference standard appears necessary (CPR-05).

8. **D43-CRITERIA: Criteria ambiguity remains exploitable** — Criteria in GovernanceState are configuration, not constitutionally precise representations (AC-30/TF-36C-04-03).

9. **D43-GOV-AUTH: ADH-1 unresolved** — Governance authorization authority and execution authority are not separated (AC-25/26).

**What the current model does well:**

- Vote aggregate's anonymity guarantee (VO-1) is constitutionally sound and must be preserved
- Pull-over-push choreography is constitutionally compatible
- GovernanceState's precondition-enforcement model (CG-1/2) is architecturally correct for the behavioral layer
- Verification's append-only model (TA-3) is correct for behavioral integrity — must be extended, not replaced
- D42B resolution (VRC) is constitutionally justified and should proceed
- DataChecksum (VO-2) is a sound authenticity foundation
- ElectionConstitution reference in GovernanceState is the strongest constitutional legitimacy signal in the current model

---

## Section 9 — ARB Decision Block

**[APPROVED]**

ARB Assessment: Research Quality Excellent | DDD Discipline Excellent | Constraint Traceability Excellent | Architecture Neutrality Very Good | Governance Discipline Excellent

### Required Revisions Applied

1. **AC-03 weakened** — ElectionConstitution is a weak candidate L-1 signal; assessment changed from NONE to WEAK; L-1 = weak candidate, L-2 through L-5 = absent; Section 1 summary table and coverage count updated
2. **AC-08 / TA-3 tension: "structurally incompatible" → "structurally unresolved"** — 36E-03 identifies tension; 36E-04 evaluates resolution; same correction applied in DFA-01
3. **EH-01 stays at Supported Hypothesis — no AC-31 promotion** — DataChecksum circularity proves current concept insufficient, not that all architectures require independent reference standards; AC-31 promotion decision deferred to post-36E-04
4. **CPR-01 governing discipline note added** — explicit statement: no conclusions regarding separate contexts/services/organizations/external entities; OBS-36D-02-1 and CF-05-19 remain binding through 36E-04
5. **ET-03/D43-AUDIT open question added** — 36E-04 must evaluate whether audit scope authority is a specialization of D43-AUDIT or a separate authority relationship inside D43-AUDIT requiring its own independence structure

### Key Findings Summary

| Finding ID | Finding |
|---|---|
| 36E-03-CCA-03 | L-1 weak candidate (ElectionConstitution); L-2 through L-5 entirely absent — most foundational gap |
| 36E-03-CCA-08 | Append-only design (TA-3) and external challengeability (AC-08) tension is structurally unresolved |
| 36E-03-PIA-04-3 | Certification has zero DDD coverage — highest-risk constitutional blank space |
| 36E-03-PIA-02-2 | Gap A-3 fully open — audit scope and completeness unmodeled |
| 36E-03-D42B-1 | D42B remains valid — strengthened by AC-02; VRC needs authority boundary separated from context boundary |
| 36E-03-D43-1 | D43-ENROLL: enrollment authority gap blocks Eligibility constitutional completion |
| 36E-03-D43-2 | D43-CRITERIA: criteria ambiguity + self-revocation compound constitutional risk |
| 36E-03-D43-3 | D43-AUDIT: Audit Context exists but has zero constitutional mandate coverage |
| 36E-03-D43-4 | D43-GOV-AUTH: ADH-1 resolution prerequisite for AC-25/26 satisfaction |
| 36E-03-D43-5 | D43-CERT: no model coverage; terminal constitutional risk entirely unaddressed |
| 36E-03-EH-01-3 | EH-01 = Supported Hypothesis; do NOT promote to AC-31; carry to 36E-04 |
| 36E-03-ET03-4 | ET-03 = sub-function of D43-AUDIT; specialization-vs-separate-relationship question carried to 36E-04 |
| 36E-03-DFA-05 | Audit Context = Significant Gap; IR-H tension structurally unresolved (not incompatible) |
| 36E-03-DFA-10 | ElectionConstitution reference = strongest constitutional legitimacy signal in current model (candidate L-1) |

### ARB Governing Decision

**Round 36E-03: APPROVED**  
**Round 36E-04: AUTHORIZED**

**36E-04 strict rule (binding):** 36E-04 MAY evaluate architectural options. 36E-04 MAY NOT select options. Option selection belongs to Round 37 ADRs. This guardrail prevents the program from skipping from impact assessment directly into architecture decisions.

### Open Questions Carried to 36E-04

**OQ-36E-03-01 (resolved by ARB):** EH-01 promotion from Emerging Hypothesis to Supported Hypothesis: ACCEPTED. Promotion to AC-31: NOT YET — deferred until 36E-04 determines whether all satisfying architectures require an independent reference standard or only some do.

**OQ-36E-03-02 (carried to 36E-04):** The Audit Context faces a structural tension with IR-H (independence of the audited subject). Can this be resolved by repositioning Audit as a boundary component bridging internal and external? Or does IR-H require the audit function to be entirely outside the election system? Most consequential architectural question for 36E-04.

**OQ-36E-03-03 (carried to 36E-04):** The TA-3 (append-only) and AC-08 (external challengeability) tension is structurally unresolved. Can a non-mutating challenge pathway satisfy both simultaneously? This gates the constitutional extension model for Verification, GovernanceState, and Vote.

**OQ-36E-03-04 (carried to 36E-04):** The ElectionConstitution reference in GovernanceState is the strongest L-1 signal in the model. Should 36E-04 evaluate whether ElectionConstitution can serve as a shared L-1 source for multiple D43 instances, or must each D43 instance find its L-1 source independently?

**OQ-36E-03-05 (carried to 36E-04, added from Revision 5):** Is audit scope authority a specialization of D43-AUDIT (one relationship covers both scope and execution) or a separate authority relationship inside D43-AUDIT (requiring two constitutionally independent relationships)?

---

*Round 36E-03 — DDD Impact Assessment — APPROVED*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round36E-03_DDD_Impact_Assessment.md*
