# Round 38A-04 — Timing, Phase, and Operational Attack Threats

**Program:** NRNA DDD Trustworthiness Research Program — Round 38A  
**Document:** 38A-04 of 06  
**Status:** SUBMITTED FOR ARB REVIEW  
**Predecessors:** 38A-01 APPROVED | 38A-02 APPROVED | 38A-03 APPROVED WITH STRATEGIC CORRECTIONS  
**Mission:** Adversarial posture. Attempt to produce FAIL findings. Do not defend the architecture.  
**Authorized by ARB:** TM-10 (deep), TM-11 (deep), TM-20–TM-27 (coverage); New threats — Operational Deadlock, Temporal Concentration, Successor Exhaustion, GovernanceState Corroboration Absence, Phase Boundary Ambiguity

---

## Part A — Pre-Evaluation Architecture State

### A.1 Inherited Findings Directly Relevant to 38A-04

| Finding | Source | Status | Relevance to 38A-04 |
|---|---|---|---|
| AA-07: GovernanceState sole authoritative phase record | 38A-01 G.1 | Active | TM-10 (Phase Lock) deep evaluation; GovernanceState corroboration absence |
| AA-05: Cascade-free authority suspension (unvalidated for simultaneous failures) | 38A-01 G.1 | Active | TM-11 (Succession Vacancy) deep evaluation; Operational Deadlock |
| AW-03-03: No successor minimum count or update obligation | 38A-03 F | Active | TM-11 deep; Successor Exhaustion |
| TM-03 (GovernanceState Corruption): C-F all conditions currently met | 38A-02 D.2 | Open | Phase Lock and GovernanceState attacks build on TM-03 foundation |
| TM-10 (Phase Lock): C-F (baseline, shallow evaluation in 38A-02) | 38A-02 | Open — deep evaluation authorized | Part C of this document |
| TM-11 (Succession Vacancy): C-F (baseline evaluation in 38A-03) | 38A-03 | Open — deep evaluation authorized | Part D of this document |
| TM-39 (Independence Illusion): F at Adversary D | 38A-03 C.9 | Active | Operational dependence creates hidden concentration; 38A-04 builds on it |
| Five structural gaps (1–5) | 38A-01 G.5 | Active | Gaps 1–5 constrain remediation options for every threat in 38A-04 |
| OA-01 (challenger evidence access): unresolved | 38A-01 | Active | Class 5 threats (TM-20–27) all depend on OA-01 for detection |

### A.2 Organizing Frame — Constitutional Assumption Failures

38A-04's central finding is not a set of independent threats. It is a set of **operational assumptions** embedded in the constitutional architecture that the architecture treats as guaranteed without specifying how they are maintained. Each assumption failure is both a vulnerability and a family of attacks.

| Assumption | Embedded Claim | Where It Fails | 38A-04 Threat |
|---|---|---|---|
| **GovernanceState reliability** | Sole authoritative phase record is always accurate | No corroboration mechanism; single-point-of-truth | TM-10, TM-40 |
| **Phase transition detectability** | Phase transitions are constitutionally detectable when they occur or fail to occur | No phase detection mechanism specified | TM-41, TM-10 |
| **Operational capacity sufficiency** | All required constitutional actors will be available throughout the election cycle | No minimum capacity specified; no reconstitution mechanism | TM-42 (Operational Deadlock) |
| **Succession achievability** | Pre-designation of successors guarantees succession is achievable when needed | Designation ≠ availability; no minimum count; no update obligation | TM-11, TM-43 |
| **Timing fairness** | Constitutional windows are long enough to allow genuine participation by all constitutional actors | No constitutional minimum window duration; no disruption tolerance | TM-44, TM-12 |
| **Operational independence** | Constitutional independence designations reflect genuine operational independence | ADR-2 specifies structure, not operations (TM-39 — established in 38A-03) | TM-39 (reference) |

Each of these assumptions was not written into the constitutional architecture explicitly — they are implied by the architecture's omission of any contrary specification. Their absence from the architecture is the vulnerability; their failure is the attack surface.

---

## Part B — GovernanceState Integrity Attacks

### B.1 TM-10 — Phase Lock Attack (Deep Evaluation)

**Class:** 2 (Governance) | **Adversary:** C, D  
**Baseline classification (38A-02):** C-F

**Deep Evaluation Context:**

TM-10 was identified in 38A-01 as an attack that prevents GovernanceState from recording valid phase transitions. 38A-02 provided a baseline C-F classification. This section provides deep evaluation authorized by ARB.

**Phase Lock Attack Paths:**

GovernanceState is the sole authoritative record of constitutional phase status (AA-07). A Phase Lock attack prevents the GovernanceState from transitioning, locking the election in an incorrect constitutional phase. There are five distinct attack paths:

| Attack Path | Mechanism | Effect | Constitutional Interface |
|---|---|---|---|
| **Lock A — Transition Prevention** | Prevent GovernanceAuthority from recording a valid transition event | Election locked in current phase indefinitely | TM-03 mechanism; GovernanceState cannot be updated |
| **Lock B — Premature Closure** | Force GovernanceState to record phase as complete before all constitutional requirements are met | Subsequent phases activate on incomplete foundation; CO-4 evaluates against incomplete record | CO-4 assessment gap; challenge requires evidence of what was incomplete |
| **Lock C — Window Destruction** | Prevent challenge window from closing after certification; or force it closed before material challenges are filed | Either permanent post-certification challenge window (CO-5 status perpetually contested) or premature closure (TM-12 amplifier) | Challenge window constitutional timing requirement — not specified |
| **Lock D — Phase Retrograde** | Manipulate GovernanceState to show a completed phase as incomplete | Certification phase cannot begin; election suspended in apparent constitutional non-compliance | AA-07 sole record; retrograde shows as legitimate phase status |
| **Lock E — Phase Ambiguity** | Create ambiguous GovernanceState where phase status is internally inconsistent | Constitutional actors cannot determine what phase the election is in; all phase-dependent actions are uncertain | AA-07 provides no conflict resolution for internal GovernanceState inconsistency |

**Constitutional Defense Analysis:**

The primary defense is GovernanceState challenge. But AA-07 identifies the single-point-of-truth problem: GovernanceState is the sole authoritative record. A challenger demonstrating GovernanceState inconsistency must use what evidence?

- **Lock A:** Challenger can demonstrate that constitutional phase-completion requirements were met (e.g., voting concluded, all mandated evidence submitted) — but must do so with external evidence, because GovernanceState itself is the record the challenger is disputing.
- **Lock B–E:** Challenger must reconstruct the correct constitutional phase status from evidence independent of GovernanceState. This requires OA-01 (challenger evidence access) to be resolved.

**AA-07 Critical Failure:** If GovernanceState is the sole authoritative phase record, challenges to GovernanceState require external evidence. The architecture has not specified what external evidence can override GovernanceState. Named Attestation challenges (CO-2/CO-3/CO-4) evaluate compliance with what GovernanceState says happened — not what actually happened.

**Revised Survivability Classification: C-F approaching F**

TM-10 was classified C-F in 38A-02 (shallow evaluation). Deep evaluation reveals:
- **Lock B (Premature Closure)** produces C-F: CA evaluates CO-4 against a GovernanceState that says all requirements were met. Challenge requires external evidence, but OA-01 is unresolved.
- **Lock D (Phase Retrograde)** produces C-F approaching F: election is suspended. No constitutional mechanism forces GovernanceState update. GovernanceAuthority capture makes this permanent.
- **Lock E (Phase Ambiguity)** produces C-F: no constitutional conflict resolution mechanism (Gap 4 analogue for GovernanceState interpretation).
- **Lock B + TM-03 (GovernanceState Corruption combined):** This is the TM-03 × TM-10 interaction — phase appears complete (TM-10 Lock B) while GovernanceState records are corrupt (TM-03). The combination makes the corruption invisible: a premature phase closure with corrupt supporting records has no challenge surface.

**Deep Evaluation Classification: C-F approaching F.** The AA-07 single-point-of-truth assumption is the controlling architectural weakness. Without corroboration, no challenge can override GovernanceState on the basis of external evidence alone.

---

### B.2 TM-40 — GovernanceState Corroboration Absence

**Class:** 2 (Governance / Structural) | **Adversary:** None required (design failure)  
**This is a newly-named structural threat. It is the explicit naming of the AA-07 assumption failure.**

**The Assumption Being Named:**

AA-07 identifies GovernanceState as the sole authoritative phase record. This document formally names the resulting structural threat: the architecture has provided no corroboration mechanism for GovernanceState. There is no constitutional requirement for any other authority or actor to independently record phase transitions, no requirement for independent confirmation before phase transitions are recorded as authoritative, and no mechanism for reconciling GovernanceState with independent phase records even if they existed.

**Consequence:** GovernanceState is constitutionally self-certifying. A GovernanceState record that has been corrupted (TM-03), locked (TM-10), or fabricated is constitutionally identical to a GovernanceState record that accurately reflects what happened. Named Attestation evaluates compliance with what GovernanceState says — not with what actually occurred.

**Attack Surface:**

TM-40 is not itself an adversary attack. It is the architectural condition that enables TM-03, TM-10, and all GovernanceState-dependent attacks. Every GovernanceState threat in the catalog is amplified by TM-40: without corroboration, each GovernanceState threat is harder to detect and has no internal constitutional remedy.

**Classification: C-F for all GovernanceState-dependent threats (structural amplifier)**

TM-40 does not independently produce FAIL. It is an architectural weakness that raises all GovernanceState-dependent threats' classification by making challenge more difficult. If TM-40 were addressed (corroboration mechanism added), TM-03 and TM-10 would remain C-F but with stronger detection paths.

---

### B.3 TM-41 — Phase Boundary Ambiguity

**Class:** 2 (Governance / Structural) | **Adversary:** C, D, or None  
**Newly named threat. Connects to AA-07 and Gap 4 (Constitutional Interpretation Authority).**

**Attack:** Constitutional phases have completion requirements. But the ElectionConstitution does not specify, for each phase, what constitutes complete and sufficient transition. Who determines that the voting phase is complete? What evidence determines that all challenges have been filed? When is the certification phase constitutionally open?

**Structural Vulnerability:**

If phase boundaries are not precisely specified, then:
- GovernanceAuthority has discretion in determining phase completion → TM-14 analogue at the phase level (scope self-amendment)
- Disputants can claim different constitutional phase status interpretations → TM-13 (Constitutional Ambiguity) at the operational phase level
- CA may certify in a phase that challengers claim is constitutionally incomplete → CO-4 compliance cannot be definitively evaluated

**Gap 4 Interface:** Phase boundary interpretation requires EC interpretation authority. Gap 4 (no Constitutional Interpretation Authority) means no body can authoritatively resolve phase boundary disputes. CAB adjudicates challenges but must itself interpret what phase completion requires — without a constitutional precedent to cite.

**Classification: C-F** — conditionally fails when (a) phase boundaries are ambiguous in EC AND (b) a dispute arises about phase completion status AND (c) Gap 4 prevents authoritative resolution. Conditions (b) and (c) are structurally created; condition (a) depends on EC specification quality.

---

## Part C — Succession Failure and Operational Collapse

### C.1 TM-11 — Succession Vacancy Attack (Deep Evaluation)

**Class:** 3 (Succession) | **Adversary:** B, C, D  
**Baseline classification (38A-03):** C-F

**Deep Evaluation Context:**

TM-11 attacks the pre-designated succession chain required by ADR7-INV-01. 38A-03 provided a C-F classification with the finding that ADR7-INV-01 requires designation but not minimum count or update obligation (AW-03-03). This section provides deep evaluation.

**ADR7-INV-01 Analysis — What It Provides and What It Omits:**

| Provision | ADR7-INV-01 Specifies? | Constitutional Consequence of Omission |
|---|---|---|
| Successor must be pre-designated | ✅ Yes | Baseline requirement |
| Minimum number of successors per authority | ❌ No | One designee = one-point-of-failure succession chain |
| Successor must be qualified for the role | ❌ Not specified | A designee who is unable to perform the constitutional function is constitutionally valid |
| Successor pre-designation must be updated periodically | ❌ No update obligation | A designee designated 10 years ago may be unavailable; no update mechanism |
| Succession chain must survive simultaneous suspensions | ❌ Not specified | AA-05 (unvalidated cascade-free assumption) |
| Succession takes effect when? | ❌ Trigger mechanism not specified | A suspension trigger is not defined; when does the designated successor become active? |

**TM-11 Attack Vectors (from the above gaps):**

| Attack Vector | Mechanism | Constitutional Consequence |
|---|---|---|
| **Designee Compromise** | Adversary compromises the sole designated successor | When suspension is triggered, compromise is in place; succession activates a compromised authority |
| **Designee Incapacitation** | Adversary incapacitates the designated successor (not capture — physical or organizational incapacitation) | When suspension is triggered, succession activates an unavailable successor → authority is functionally absent |
| **Designee Disqualification** | Adversary manipulates circumstances so that the designated successor fails eligibility requirements (if any exist — and they don't currently) | No minimum qualification requirement means this attack path is closed |
| **Succession Trigger Ambiguity** | Adversary disputes whether a suspension trigger has occurred (Gap 4 interface: what constitutes a valid suspension?) | Succession never activates because the trigger is disputed; original authority continues under compromise |
| **Simultaneous Succession Exhaustion** | Adversary (or operational failure) simultaneously suspends multiple authorities with exhausted succession chains | → TM-42 (Operational Deadlock) — covered in C.2 |

**Revised Survivability Classification: C-F (maintained, but with heightened severity)**

TM-11 remains C-F: it requires adversary action (or operational failure) to incapacitate a designated successor. However:
- The C-F condition is far easier to achieve than initially evaluated: a single designee per authority means one-point-of-failure succession
- The Succession Trigger Ambiguity vector (Gap 4 interface) is currently met: no constitutional specification of what triggers succession means that disputes about whether succession has occurred are constitutionally unresolvable

**TM-11 severity characterization upgrade:** C-F → C-F approaching F when combined with Succession Trigger Ambiguity (Gap 4) + single-designee succession chains. These conditions are currently met for all seven D43 authorities.

---

### C.2 TM-42 — Operational Deadlock

**Class:** 5 (Operational / Structural) | **Adversary:** None required for structural failure; D/E for targeted attack  
**Newly named threat. PRIMARY FAIL CANDIDATE for 38A-04.**

**The Structural Failure:**

The constitutional architecture specifies no minimum operational capacity for any of the seven D43 authority aggregates. It specifies no mechanism for reconstituting a suspended authority when its succession chain is exhausted. It specifies no constitutional response when multiple authorities are simultaneously unavailable. This means:

The constitutional architecture contains a structural failure mode requiring no adversary action: Operational Deadlock occurs when multiple D43 authorities are simultaneously suspended (or non-functional) with no available successors, leaving the election unable to proceed constitutionally.

**Deadlock Scenarios:**

| Scenario | Trigger | Adversary Required | Constitutional Remedy |
|---|---|---|---|
| **Complete Deadlock** | All seven authorities suspended + succession exhausted | D/E for deliberate; No for accident | None specified |
| **Certification Deadlock** | CA suspended + no successor + CAB suspended + no successor | C/D for targeted; No if both fail simultaneously | None specified |
| **Challenge Deadlock** | CAB suspended + no successor | B/C | ADR-5 MA appeal path — but MA can only act if reconstitution mechanism exists |
| **Phase Deadlock** | GA suspended + no successor | C | Election frozen in current phase — neither advancing nor voiding |
| **Audit Deadlock** | ASA + AEA both suspended + successors exhausted | C/D | No audit scope defined; no audit execution possible; CO-2 cannot be produced |

**Key Finding — Certification Deadlock without Adversary:**

The most severe Deadlock scenario requires only two conditions: CA is unavailable (suspended, incapacitated, or operationally non-functional) AND CA's succession chain is exhausted. These conditions are:
- Currently possible: ADR7-INV-01 requires pre-designation but not minimum count or update obligation (AW-03-03). A single designee who becomes unavailable produces CA succession exhaustion.
- Not adversary-dependent: a natural disaster, organizational dissolution, or technical failure could produce both conditions without any adversary action.
- Without constitutional remedy: no ADR specifies what happens to the election when CA is unavailable with no successor. The election cannot be certified. No alternative certification mechanism exists. No void-and-rerun mechanism is specified (same gap as TM-08-B Condition B — no constitutional recovery procedure).

**Survivability Classification: C-F → F**

**C-F condition:** Multiple authorities simultaneously suspended with exhausted succession chains. This requires either: (a) operational failures to simultaneously affect multiple authorities (unlikely but possible without adversary action) or (b) deliberate adversary action (TM-43 Successor Exhaustion Attack).

**F consequence once triggered:** 
- The election cannot proceed constitutionally
- No constitutional mechanism exists to reconstitute suspended authorities with exhausted succession chains
- No constitutional mechanism exists to void the election and require a rerun
- The constitutional architecture has no floor — no minimum that must be preserved for the election process to be constitutional
- The election exists in a constitutional void: not certified, not voided, not challengeable (if CAB is also deadlocked), indefinitely suspended

**Distinction from TM-08-B:** TM-08-B was reclassified to C-F approaching F because a constitutional recovery procedure (void → rerun) could address it. Operational Deadlock cannot be addressed by a void → rerun procedure if CAB itself is deadlocked — there is no body to adjudicate the deadlock, no body to declare the void, and no body to authorize a rerun.

**Zero-adversary FAIL path:** If we accept that multiple authorities can simultaneously fail without adversary action (organizational dissolution, natural disaster, concurrent illness), Operational Deadlock has a non-adversarial FAIL path. This is the closest the catalog has come to an unconditional FAIL since TM-08-B was reclassified. The ARB must determine whether "structural impossibility without adversary action" constitutes an unconditional F or merely a C-F where the condition is non-adversarial.

**Interaction with Independence Illusion (TM-39):** If TM-39's design-level F is accepted, Operational Deadlock is dramatically amplified: a single operational infrastructure failure simultaneously disables multiple constitutionally independent authorities, producing Complete Deadlock without targeting individual succession chains.

---

### C.3 TM-43 — Successor Exhaustion Attack

**Class:** 5 (Operational) | **Adversary:** B, C, D  
**Newly named threat. The adversarial path to TM-42 (Operational Deadlock).**

**Attack:** Adversary systematically identifies and incapacitates designated successors before a primary authority suspension occurs. When the suspension is triggered (by the adversary or otherwise), the succession chain is already exhausted — Operational Deadlock immediately results.

**Attack Path:**
1. Adversary identifies pre-designated successors for one or more D43 authorities (ADR7-INV-01 requires pre-designation, and designations may be publicly recorded or discoverable).
2. Adversary incapacitates, compromises, or disqualifies successors one-by-one, before any suspension event that would activate succession.
3. When the primary authority is suspended (adversary action or otherwise), succession is triggered — but the chain is empty.
4. The authority is effectively absent with no constitutional reconstitution mechanism.

**Architectural Vulnerability:**

The architecture protects primary authorities against capture (TM-04, TM-02, etc.) but has no analogous protection for designated successors. Successors are private individuals or organizations whose availability has no constitutional guarantee. The architecture's investment in protecting primary authority independence does not extend to succession chains.

**Constitutional Consequence:** Successor Exhaustion is the setup attack for Operational Deadlock. A sophisticated adversary would execute TM-43 before triggering the primary authority suspension — ensuring the succession chain is exhausted precisely when it is needed.

**Classification: C-F → F** (via TM-42 once execution completes)

The C-F condition: adversary successfully exhausts one or more succession chains. Once condition is met, the consequence (TM-42 Operational Deadlock) is F-equivalent.

---

## Part D — Temporal Concentration

### D.1 TM-44 — Temporal Concentration

**Class:** 5 (Operational / Structural) | **Adversary:** None required (design gap); any adversary who can disrupt a constitutional window  
**Newly named threat. Hidden concentration point: time is not distributed in the constitutional architecture.**

**The Hidden Concentration Point:**

The constitutional architecture specifies sequential phases and phase transitions. Evidence is gathered, audits are conducted, certifications are issued, and challenges are filed — each in defined temporal windows. The architecture has not specified:
- Minimum window durations (how long must the challenge window be open?)
- Disruption tolerance (what happens if a window is interrupted?)
- Window extensibility (can a window be extended if constitutional actors are temporarily unavailable?)
- Sequential dependencies (can phase N+1 begin if phase N is disputed?)

**Temporal Concentration:** Every constitutional action must occur within its window. Unlike geographic or authority concentration (where the same effect could be achieved through multiple paths), temporal concentration is irreversible: a missed window cannot be retrospectively filled within the constitutional architecture.

**Attack Path:**

Any disruption during a constitutionally critical window — whether adversarial or not — can produce permanent constitutional failure:

| Window | Disruption Effect | Constitutional Consequence |
|---|---|---|
| **Voting window** | Disruption prevents eligible voters from participating | CO-2 evidence will show incomplete enrollment-to-vote ratio; CO-2 challenge required but incomplete participation is not the same as evidence fraud |
| **Evidence submission window** | Disruption prevents required evidence from being submitted before audit scope activates | CO-2 cannot be satisfied; TM-08-B (Review Impossible) triggered if no recovery procedure |
| **Challenge window** | Disruption prevents challengers from filing before window closes | Material challenges are permanently barred; CO-5 issued without adjudication of known constitutional issues |
| **Certification window** | Disruption prevents CA from issuing CO-5 before constitutional deadline | Election is uncertified; TM-42 (Phase Deadlock) if no deadline extension mechanism |
| **Succession activation window** | Disruption prevents successor from taking role before succession activates | TM-11 / TM-42 triggered |

**Why This Is a Hidden Concentration Point:**

Temporal Concentration is hidden because time appears distributed — different phases occur at different times, different actors act in different windows. But the constitutional architecture has zero disruption tolerance within each window. A one-minute disruption at the wrong moment can produce the same constitutional consequence as a month-long deliberate attack.

**Constitutional Defenses:** None specified. The architecture does not specify:
- Emergency window extension mechanisms (who can authorize extensions? Under what circumstances?)
- Disruption tolerance (how much disruption is constitutionally permissible before a window is void?)
- Force majeure provisions (what happens to constitutional obligations when natural disasters prevent their performance?)
- Sequential phase flexibility (can phase N+1 be conditionally started before phase N is fully complete?)

**Classification: C-F approaching F**

The constitutional architecture has no disruption tolerance. Any disruption during a constitutionally critical window produces constitutional consequence. The C-F condition is disruption occurring during the wrong window — an adversary targeting a critical window can achieve C-F approaching F with very limited adversary capability (disruption is far easier than authority capture). Non-adversarial disruptions (technical failures, organizational crises, natural events) can also trigger this.

**Distinction from TM-12 (Challenge Window Attrition):** TM-12 uses procedural mechanisms to prevent challenge exercise within a window. TM-44 attacks the window itself — making it so short, so disrupted, or so narrowly specified that the constitutional architecture's sequential process has zero tolerance for any disruption.

---

### D.2 TM-12 — Challenge Window Attrition (Deepened Context)

**Baseline classification (38A-02):** C-F

**38A-04 Context (TM-44 interface):**

TM-12 (Challenge Window Attrition) was evaluated as C-F in 38A-02 using procedural delay mechanisms. TM-44 (Temporal Concentration) provides a deeper structural finding: the challenge window has no minimum duration in the constitutional architecture. This means:

TM-12 procedural attrition + TM-44 narrow window = Challenge window attrition can succeed with far less adversary sophistication than originally evaluated. If the constitutional architecture permits a challenge window of zero minimum duration, a trivial TM-12 procedural delay can exhaust the entire window.

**Revised finding (not reclassification):** TM-12 remains C-F, but the C-F condition is more easily met than the 38A-02 evaluation recognized. The absence of a constitutional minimum window duration (TM-44) means TM-12's adversary capability threshold is lower than the baseline assumed.

---

## Part E — Election Security Threats (Class 5)

*TM-20 through TM-27 are Class 5 election security threats operating at the voting level rather than the constitutional governance level. This section evaluates them against the constitutional architecture's certification and challenge framework.*

### E.1 Constitutional Interface for Class 5 Threats

All Class 5 threats (if undetected) ultimately manifest as anomalies in two constitutional evidence layers:
- **CO-2 (Evidence Completeness):** Vote counts, enrollment records, eligibility records should be internally consistent. Fraud that produces anomalies in these records is CO-2 territory.
- **CO-3 (Evidence Authenticity):** Fraud that involves forging or falsifying voting records is CO-3 territory.

The constitutional architecture's defense against Class 5 threats is: anomalies in the evidence will produce CO-2 or CO-3 challenges that expose the fraud. This defense is entirely dependent on **OA-01 (challenger evidence access) being resolved** — without access to enrollment records, voting records, and audit results, challengers cannot demonstrate CO-2 or CO-3 non-compliance.

**Class 5 Constitutional Assumption:** The architecture assumes that fraud manifesting in voting records will be detected through CO-2/CO-3 Named Attestation challenges. This assumption fails if: (a) OA-01 is unresolved (challengers lack access), (b) fraud is below detectable anomaly thresholds, or (c) fraud is coordinated across the evidence chain so that no single CO-2 or CO-3 comparison reveals it.

### E.2 Individual Threat Evaluations

#### TM-20 — Vote Buying

**Attack:** Adversary purchases votes by compensating voters for voting for specific candidates, withdrawing from voting, or voting in ways that can be verified by the adversary.

**Constitutional Interface:** Vote buying requires vote verification — the adversary must know how the voter voted. The architecture's vote anonymity (no user_id in votes table; no voter-to-vote linkage) structurally eliminates verifiable vote buying. Without the ability to verify how a voter voted, vote buying cannot be enforced.

**Classification: A-D (Architecture Dependent)** — the constitutional architecture's anonymity design is the primary mitigation. If vote anonymity is implemented correctly, vote buying is not enforceable. This is the ONLY Class 5 threat where the constitutional architecture provides a structural (not just evidentiary) defense.

**Architectural vulnerability:** If anonymity implementation has a flaw (a side-channel that reveals voter-vote linkage), vote buying becomes feasible. This is a technical implementation threat (38A-05 scope) rather than a constitutional architecture threat.

---

#### TM-21 — Voter Coercion

**Attack:** Adversary threatens voters with consequences for voting incorrectly or not voting at all.

**Constitutional Interface:** Same as TM-20 — vote anonymity eliminates the enforceability of vote-based coercion. However, coercion to ABSTAIN (not vote at all) can be enforced without vote verification: the adversary can verify that a voter's unique code was not used.

**Classification: C-F for abstention coercion.** Voter anonymity protects against how-you-voted coercion but not against whether-you-voted coercion. CO-2 evidence (enrollment records vs voting participation) would show reduced participation, but this is consistent with voluntary non-participation.

---

#### TM-22 — Chain Voting / Carousel Attack

**Attack:** A voter retrieves an unused voting code, casts a vote, then passes the code to the next voter in the chain to repeat. This requires the code system to allow this.

**Constitutional Interface:** The Two-Code System (documented in CLAUDE.md) prevents chain voting: Code 1 must be presented to enter the voting session; Code 2 is used to submit. A used code cannot be reused. The voting code is hashed in the database; there is no way to extract a used code from the system.

**Classification: S-D (Survives with Detection)** — the Two-Code System provides structural protection. Chain voting would require compromising the code system itself, which is a TM-28 (Insider Manipulation) or TM-29 (Infrastructure Compromise) threat.

---

#### TM-23 — Selective Disenfranchisement

**Attack:** Adversary prevents specific voter classes from participating — through system unavailability, targeted communication failures, eligibility manipulation, or enrollment exclusion.

**Constitutional Interface:** CO-2 (Evidence Completeness) — enrollment evidence must be complete. Selective disenfranchisement that affects enrollment records would produce CO-2 anomalies. However:
- System unavailability during the voting window is TM-44 (Temporal Concentration) — constitutional response is not specified
- Targeted communication failure (voters never receive codes) does not appear in enrollment records as fraud — it appears as non-participation
- EA+ASA coalition (38A-03 SC1) is the governance-level version of this threat

**Classification: C-F** — conditionally fails when disenfranchisement is below CO-2 detection threshold (absence of voters who never appeared in the system is constitutionally invisible without external enrollment comparison). OA-01 dependency for evidence access.

---

#### TM-24 — Ballot Stuffing

**Attack:** Adversary creates additional votes for candidates beyond the enrolled voter population.

**Constitutional Interface:** CO-2 requires enrollment evidence to match voting participation. More votes than enrolled voters is a CO-2 anomaly that should produce challenge. But: if EA is captured (enrollment records stuffed alongside vote records), the anomaly is masked. EA+ASA coalition (38A-03 SC1) covers this scenario.

**Classification: S-D (Survives with Detection) when EA is independent; C-F when EA is captured.** The constitutional architecture's CO-2 requirement creates a detection mechanism for isolated ballot stuffing. Coalition capture removes it.

---

#### TM-25 — Result Manipulation

**Attack:** Adversary directly modifies vote counting or result reporting to alter election outcomes.

**Constitutional Interface:** CO-3 (Evidence Authenticity) — result evidence must be authenticated via AC-31. If results are manipulated, CO-3 authentication should fail (AC-31 cannot authenticate forged result records). However:
- If TM-19 (AC-31 capture) has occurred, forged records are authenticated
- If AC-31 authenticated results before manipulation, post-authentication manipulation may not be detected

**Classification: S-D when AC-31 is independent; C-F → F when TM-19 has occurred.** The most dangerous result manipulation scenario is TM-25 + TM-19: AC-31 authenticates manipulated results; CO-3 attestation covers the manipulation; Named Attestation challenge cannot overcome AC-31 authentication.

---

#### TM-26 — Verification Abuse

**Attack:** Adversary exploits the vote verification mechanism to break vote anonymity or harvest verifiable vote data.

**Constitutional Interface:** The verification step (Step 4 in the voting workflow — "Verify Vote") is designed for voter self-verification, not third-party verification. If verification data is accessible to third parties, it creates a de-anonymization surface.

**Classification: A-D (Architecture Dependent) → C-F if verification implementation leaks.** Constitutional protection depends on implementation maintaining the separation between verification (voter sees their own vote) and observation (third parties see voter votes). Implementation vulnerability is TM-28/TM-29 scope.

---

#### TM-27 — Voter Credential Theft

**Attack:** Adversary obtains voting codes for enrolled voters and casts votes on their behalf before the enrolled voters can do so.

**Constitutional Interface:** Each voter's unique code combination is single-use. If a voter's code is used (whether by the voter or an adversary), the voter cannot vote again. CO-2 evidence shows the voter participated — even though the participation was fraudulent.

**Classification: C-F** — CO-2 would show participation; the enrolled voter would be unable to vote (discovering the theft); challenger standing (the affected voter) exists to file a challenge. Challenge requires demonstrating that code usage was unauthorized — which requires access to system logs (OA-01 dependency for credential-level evidence).

**Survivability: C-F** — detection is possible through affected voters self-reporting, but constitutional remedy (replacing the fraudulent vote with the genuine voter's choice) is not specified in the challenge architecture.

### E.3 Class 5 Consolidated Finding

**OA-01 is the constitutional load-bearing mechanism for all Class 5 threats.** The constitutional architecture's defense against all Class 5 threats (except TM-20/TM-21 where anonymity is the structural defense) depends on challengers having access to enrollment records, voting participation data, result records, and system logs. Without OA-01 resolution, all Class 5 CO-2/CO-3 detection paths are closed.

**Class 5 constitutional vulnerability table:**

| Threat | Structural Defense | CO-2/CO-3 Detection | OA-01 Dependency | Classification |
|---|---|---|---|---|
| TM-20 Vote Buying | Vote anonymity — structural | N/A | N/A | A-D |
| TM-21 Voter Coercion | Vote anonymity (partial) | CO-2 (abstention visible) | High | C-F (abstention) |
| TM-22 Chain Voting | Two-Code System — structural | N/A | N/A | S-D |
| TM-23 Selective Disenfranchisement | None | CO-2 (if enrollment complete) | High | C-F |
| TM-24 Ballot Stuffing | CO-2 enrollment check | CO-2 | High | S-D / C-F (if EA captured) |
| TM-25 Result Manipulation | CO-3 / AC-31 | CO-3 | High | S-D / C-F→F (if TM-19) |
| TM-26 Verification Abuse | Implementation | N/A (implementation) | High | A-D |
| TM-27 Credential Theft | None | CO-2 (voter self-reporting) | High | C-F |

---

## Part F — FAIL Findings Summary (38A-04)

### F.1 New FAIL Findings

| # | Threat | Classification | Key Reasoning | Adversary Required |
|---|---|---|---|---|
| 4 | **TM-42 (Operational Deadlock)** | **C-F → F** | No constitutional minimum operational capacity; no reconstitution mechanism; no void + rerun trigger; deadlock may occur without adversary action if multiple operational failures coincide | None required for structural failure; D/E for targeted attack |
| 5 | **TM-42 + TM-43 (Successor Exhaustion → Deadlock)** | **C-F → F** | Systematic successor exhaustion before triggering suspension produces Operational Deadlock; constitutional succession architecture provides no protection for successors themselves | D (systematic targeting) |

**New structural finding (non-FAIL):** TM-44 (Temporal Concentration) is C-F approaching F — zero constitutional disruption tolerance at any window creates a hidden concentration point for both adversarial and non-adversarial failure paths.

### F.2 FAIL Findings Catalog to Date (38A-01 through 38A-04)

| # | Source | Threat / Coalition | Classification |
|---|---|---|---|
| 1 | 38A-03 | CA + CAB coalition | **F** |
| 2 | 38A-03 | EC + CA / TM-06 full | **F** |
| 3 | 38A-03 SC1 | GA + ASA + CAB | **F** |
| 4 | 38A-03 | TM-19 (AC-31 capture) | **C-F → F** |
| 5 | 38A-03 SC2 | TM-39 (Independence Illusion) at Adversary D | **F (design-level)** |
| 6 | 38A-04 | TM-42 (Operational Deadlock) | **C-F → F** |

**Running FAIL count: 5 FAILs + 1 C-F → F (from prior rounds) + 1 C-F → F (this round) = 7 total FAIL-class findings across 38A-03 and 38A-04.**

---

## Part G — Architectural Weakness Register (38A-04 Findings)

| Weakness ID | Description | Threat(s) | Severity |
|---|---|---|---|
| AW-04-01 | GovernanceState has no corroboration mechanism — it is constitutionally self-certifying; no independent record can override it | TM-10, TM-40, TM-03 | Critical |
| AW-04-02 | Phase completion requirements are not precisely specified in the constitutional architecture — phase boundary ambiguity is inherent | TM-41, TM-13 | High |
| AW-04-03 | No constitutional minimum operational capacity for any D43 authority — the architecture does not specify what happens when an authority is unavailable and succession is exhausted | TM-42 | Critical (C-F → F) |
| AW-04-04 | No constitutional reconstitution mechanism — when an authority is suspended and successor list is exhausted, no ADR specifies how to reconstitute it | TM-42, TM-43 | Critical |
| AW-04-05 | Succession architecture protects primary authorities, not successors — designated successors have no constitutional protection from incapacitation or compromise | TM-43, TM-11 | High |
| AW-04-06 | No constitutional disruption tolerance — temporal windows have no minimum duration, no extension mechanism, no force majeure provision | TM-44 | High |
| AW-04-07 | No constitutional floor — the architecture specifies no minimum that must be preserved for the election process to be constitutional; an election can be deadlocked without any constitutional response | TM-42 | Critical |
| AW-04-08 | OA-01 is load-bearing for all Class 5 threat detection — without challenger evidence access, all CO-2/CO-3 detection paths for voting-level fraud are closed | TM-20 through TM-27 | Critical (cross-threat dependency) |
| AW-04-09 | Succession trigger is not constitutionally specified — no ADR defines what constitutes a valid suspension event that activates succession; trigger disputes are constitutionally unresolvable (Gap 4) | TM-11, TM-42 | High |
| AW-04-10 | No constitutional minimum window duration — challenge windows, certification windows, and voting windows have no constitutional floor; zero-duration windows are architecturally valid | TM-44, TM-12 | High |

---

## Part H — Observations and Open Questions

### H.1 Key Structural Observations

**OBS-38A04-01: Operational Deadlock Is the First Non-Capture FAIL Candidate**
All prior FAIL findings (CA+CAB, EC+CA, GA+ASA+CAB, TM-19, TM-39) involve adversary capture of specific authority functions. TM-42 (Operational Deadlock) is the first FAIL candidate that arises from a structural architectural gap rather than adversary capture: the absence of minimum operational capacity requirements and reconstitution mechanisms. Whether this constitutes an unconditional F (if non-adversarial operational failures can trigger it) is the primary ARB question for 38A-04.

**OBS-38A04-02: Temporal Concentration Is Structurally Analogous to Geographic Concentration**
The 38A program identified AC-31 as a cross-election concentration point (single reference standard). TM-44 identifies temporal concentration as a parallel: the constitutional architecture's sequential phase structure creates mandatory time-windows where any disruption produces permanent constitutional consequence. Unlike geographic concentration (which can be distributed), temporal concentration cannot be distributed — time is irreversible.

**OBS-38A04-03: TM-43 + TM-42 Is the Constitutional Equivalent of CA + CAB**
CA + CAB (38A-03) is the minimum sufficient FAIL coalition for certification+enforcement. TM-43 + TM-42 is its operational equivalent: exhaust succession chains (TM-43) then trigger suspensions (TM-43) → Operational Deadlock (TM-42). The mechanisms differ (capture vs. operational exhaustion) but the constitutional consequence is similar: the election cannot proceed, and no internal constitutional remedy exists.

**OBS-38A04-04: Class 5 Threats Are Architecturally Handled — With One Load-Bearing Dependency**
The constitutional architecture handles Class 5 threats (voting fraud) through CO-2/CO-3 evidence requirements + Named Attestation challenges. This is architecturally sound. But the entire detection system depends on OA-01 resolution. OA-01 is the constitutional load-bearing mechanism for all Class 5 defenses. Until OA-01 is resolved, the architecture's Class 5 protection is theoretical rather than operational.

**OBS-38A04-05: GovernanceState Is the Single Most Critical Corroboration Gap**
AA-07 (GovernanceState sole record) + TM-40 (Corroboration Absence) + TM-10 (Phase Lock) + TM-03 (GovernanceState Corruption) + TM-41 (Phase Boundary Ambiguity) form a cluster of threats all feeding through the same vulnerability: GovernanceState is constitutionally self-certifying. Addressing TM-40 (adding a corroboration mechanism) would reduce the severity of all other GovernanceState-dependent threats. GovernanceState corroboration is the highest-leverage single architectural remedy in 38A-04.

### H.2 Open Questions

**OQ-38A04-01:** Should TM-42 (Operational Deadlock) be classified as unconditional F when non-adversarial operational failures can produce it? The ARB reclassified TM-08-B from F to C-F because a recovery procedure would address it. TM-42 differs: no recovery procedure is specified AND the deadlock prevents the constitutional actors who would authorize recovery from operating. Does the absence of both a constitutional floor and a recovery mechanism constitute an unconditional FAIL?

**OQ-38A04-02:** Should a constitutional minimum window duration requirement be added to the architecture? TM-44 reveals that zero-duration windows are architecturally valid. Should each constitutional window (challenge, certification, voting, evidence submission) have a constitutionally mandated minimum duration? This is a design question for Round 38B.

**OQ-38A04-03:** Does GovernanceState corroboration require a new constitutional mechanism, or can it be addressed within the existing ADR framework? Possible approaches: (a) require GovernanceAuthority to provide contemporaneous corroboration from independent actors; (b) require all seven D43 authorities to independently attest phase transitions; (c) require a separate GovernanceStateWitness aggregate. Each would affect the DDD model significantly.

**OQ-38A04-04:** TM-43 (Successor Exhaustion) targets designated successors. Can the succession architecture be strengthened within the existing ADR-7 framework by specifying minimum successor counts and update obligations? Or does genuine succession security require a succession architecture that is itself constitutionally independent?

**OQ-38A04-05:** TM-25 + TM-19 (Result Manipulation + AC-31 Capture) produces a C-F → F path from result manipulation to total evidence authentication failure. Does this combination require a new entry in the FAIL catalog, or is TM-19 sufficient to capture this threat path?

---

## Part I — ARB Decision Block

### Document Status

**[SUBMITTED FOR ARB REVIEW]**

### Findings Summary

1. **New FAIL findings:** TM-42 (Operational Deadlock): C-F → F — constitutional minimum capacity absent; reconstitution mechanism absent; non-adversarial trigger possible. Running FAIL catalog: 7 findings across 38A-03 + 38A-04.

2. **TM-10 (Phase Lock) deep evaluation:** Revised to C-F approaching F. Five attack paths identified (Lock A–E). AA-07 (sole authoritative record) is the controlling architectural weakness — GovernanceState is constitutionally self-certifying.

3. **TM-11 (Succession Vacancy) deep evaluation:** Maintained C-F but upgraded severity characterization: C-F approaching F when Succession Trigger Ambiguity (Gap 4 interface) + single-designee chains are considered. Both conditions currently met.

4. **TM-40 (GovernanceState Corroboration Absence):** Newly named structural threat — the explicit naming of AA-07's failure mode. Not a standalone FAIL but amplifies all GovernanceState-dependent threats.

5. **TM-41 (Phase Boundary Ambiguity):** C-F — phase completion requirements not precisely specified; Gap 4 prevents authoritative resolution.

6. **TM-42 (Operational Deadlock):** C-F → F — primary FAIL candidate; unique in requiring no adversary action for structural failure path. ARB ruling on unconditional F question requested (OQ-38A04-01).

7. **TM-43 (Successor Exhaustion):** C-F → F (via TM-42) — the adversarial path to Operational Deadlock. Successor pre-designation has no constitutional protection for the successors themselves.

8. **TM-44 (Temporal Concentration):** C-F approaching F — hidden concentration point; zero constitutional disruption tolerance at any window; non-adversarial triggers possible.

9. **Class 5 threats (TM-20 through TM-27) evaluated:** Most survive the constitutional architecture (vote anonymity and two-code system are structural defenses). All detection paths depend on OA-01. TM-25 + TM-19 produces C-F → F interaction. TM-20 and TM-22 are defended structurally. TM-21, TM-23, TM-24, TM-27 are C-F.

10. **Ten new architectural weaknesses documented** (AW-04-01 through AW-04-10). Highest priority: AW-04-01 (GovernanceState self-certification), AW-04-03 (no minimum operational capacity), AW-04-07 (no constitutional floor), AW-04-08 (OA-01 load-bearing dependency).

### Authorization Request

ARB is requested to:

1. Rule on TM-42 (Operational Deadlock): C-F → F (as written) or unconditional F given the non-adversarial trigger path (OQ-38A04-01)?
2. Rule on TM-40 (GovernanceState Corroboration Absence) as structural threat: accept as newly-named threat in the catalog?
3. Rule on GovernanceState corroboration as the highest-leverage single remedy in 38A-04 — prioritize for Round 38B architectural response?
4. Rule on OA-01: is OA-01 a constitutional load-bearing mechanism whose unresolved status produces effective C-F for all Class 5 threat detection? Should OA-01 resolution be mandated before 38A synthesis?
5. Authorize Round 38A-05 — Technical and Infrastructure Attack Threats (TM-28 through TM-33)

---

**Authors:**
- Senior Election Security Architect
- Constitutional Governance Architect
- Threat Modeling Specialist (adversarial)
- DDD Architect

**Round 38A Program Status:**
*38A-01 APPROVED WITH STRATEGIC CORRECTIONS*
*38A-02 APPROVED WITH TARGETED CORRECTIONS APPLIED*
*38A-03 APPROVED WITH STRATEGIC CORRECTIONS APPLIED*
*38A-04 SUBMITTED FOR ARB REVIEW*
*38A-05 through 38A-06 — Awaiting Authorization*
*Next Phase (after 38A-06): Round 38B — Technical Architecture — AUTHORIZATION NOT YET GRANTED*
