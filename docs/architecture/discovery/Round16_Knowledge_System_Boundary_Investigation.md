# Round 16 — Knowledge System Boundary Investigation

**Purpose:** Investigate whether Governance and Election Operations represent different knowledge systems, different bounded context signals, different actor communities, different decision ownership models, or merely different documentation styles.

**Date:** 2026-06-06

**Status:** Investigation Complete

**Critical Constraint:** This investigation does NOT create bounded contexts, merge candidates, eliminate candidates, rank candidates, reassess candidates, create aggregates, create context maps, or recommend architecture.

---

## Background

The Election Domain Evidence Assessment revealed a previously unnoticed pattern:

```
Governance evidence sources:
  - CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md (architecture document)
  - ADR-001, ADR-003 (architecture decisions)
  - GEO-3.5 Voting Context Architecture (engineering design)
  - NRNA Governance Meaning Layer (implementation plan)
  Written by: Systems thinkers / architects

Election operations evidence sources:
  - docs/election_management/ (officer user guides)
  - candidacy/approval-procedure.md (operational procedure)
  - select-all-required-business-case.md (organizational business case)
  - docs/election/ (voter management guides)
  Written by: Election officers, administrators, organizational stakeholders
```

These two evidence families are not just different topics. They are different **types of knowledge** produced by different **communities of knowing**.

The investigation question is: What does this difference reveal about the domain structure?

---

## Inputs Used

- Round16_Step1_Candidate_Context_Discovery_Workbook.md
- Round16_Step1B_Context_Evidence_Validation_Workbook.md
- Round16_Evidence_Source_Assessment.md
- Round16_Governance_Evidence_Extraction.md
- Round16_Election_Domain_Evidence_Assessment.md
- docs/election_management/ (all six guides)
- developer_guide/election/real_election/candidacy/approval-procedure.md
- docs/select-all-required-business-case.md
- docs/CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md
- docs/adr/ADR-003-governance-driven-revocation.md

---

## Question 1 — Vocabulary Ownership

**Question:** Who uses each major term, in which documents, and for what purpose?

### Vocabulary Analysis Table

| Term | Governance Sources | Election Sources | Meaning Difference Observed |
|------|-------------------|-----------------|---------------------------|
| **Governance** | Core subject — constitutional rules, doctrine, authority relationships | Referenced as rules that apply to elections; not actively discussed | Yes — Governance sources define governance. Election sources receive governance as external constraint. |
| **Authority** | Core technical concept — JurisdictionNode, DelegationEdge, TemporalAuthorityWindow | Appears as "election authority" in role descriptions (Chief has authority to publish results) | Yes — Governance uses authority as computational graph traversal problem. Election uses authority as organizational role assignment. |
| **Legitimacy** | Technical domain concept — GovernanceLegitimacy enum, temporal validity | Not found in election operational sources | Absent from election operations vocabulary |
| **Doctrine** | Core architectural concept — DoctrineArtifact, immutable constitutional law | Not found in election operational sources | Absent from election operations vocabulary |
| **Election** | Referenced as scope for governance decisions (ConstitutionalScope includes ELECTION) | Core subject — election types, election phases, election configuration | Yes — Governance uses election as scope parameter. Election operations treat election as the primary entity being managed. |
| **Verification** | Legitimacy verification — "was this decision constitutionally valid?" | Voter eligibility verification — "is this voter approved to vote?" | Yes — Governance: constitutional verification. Election operations: eligibility/identity verification. |
| **Voter** | Not found in governance sources as primary concept | Primary concept — voter registration, voter approval, voter suspension, voter eligibility | Yes — Voter is absent from governance vocabulary. Voter is central to election operations vocabulary. |
| **Ballot** | Not found in governance sources | Core concept — ballot preparation, ballot completion, ballot submission | Absent from governance vocabulary |
| **Candidate** | Not found in governance sources as primary concept | Core concept — candidacy application, candidacy approval, candidate management | Absent from governance vocabulary |
| **Vote** | Found in GEO-3.5 as engineering term (BallotCollection, EligibilitySnapshot) | Found in election operations as organizational act (cast vote, vote count) | Yes — Governance/engineering: computational artifact. Election operations: human act with organizational meaning. |
| **Chief Election Officer** | Not found in governance sources | Defined role with explicit powers — open/close voting, approve voters | Absent from governance vocabulary |
| **Manifesto** | Not found | Candidacy application element | Absent from governance vocabulary |
| **Result** | GovernanceArchaeologyRecord, replay trace | Vote counts per candidate, publication decision | Yes — Governance: audit/replay artifact. Election operations: outcome for announcement. |
| **Revocation** | Governance policy concept — VerificationRevokedEvent, consequence decisions | Voter suspension (different term used) | Partial — Governance: formal constitutional revocation. Election operations: suspend/unsuspend (operational term). |

### Vocabulary Observations

**Terms exclusive to governance sources:**
- Doctrine, DoctrineArtifact, GovernanceLegitimacy
- JurisdictionNode, DelegationEdge, TemporalAuthorityWindow
- ConstitutionalArbitration, GovernanceDecisionSnapshot
- Replay, Fingerprint, Epoch

**Terms exclusive to election operations sources:**
- Chief Election Officer, Deputy Election Officer, Commissioner
- Ballot, Manifesto, Proposer, Supporter
- Approved voter, Suspended voter, Invited voter
- Open voting, Close voting, Publish results
- CandidacyApplication, Draft candidate

**Terms appearing in both but with different meaning:**
- Verification (constitutional vs. eligibility)
- Election (scope parameter vs. primary entity)
- Result (replay artifact vs. outcome announcement)
- Vote (computational artifact vs. organizational act)

---

## Question 2 — Decision Ownership

**Question:** Who produces decisions, who consumes them, and who can challenge them?

### Decision Ownership Table

| Decision | Producer | Consumer | Challenger | Source |
|----------|----------|----------|-----------|--------|
| **Who is eligible to vote?** | Election Officer (approves/suspends voter) | Voting process | Unknown — no challenge process found in sources | Election management guide |
| **Who may be a candidate?** | Election Officer (approves candidacy application) | Voting process (ballot) | Unknown — rejection procedure exists, challenge process absent | Candidacy procedure |
| **When does voting open/close?** | Chief or Deputy Election Officer | Voters | None found — voting can be re-opened unilaterally | Election management guide |
| **When are results published?** | Chief Election Officer only | Voters, public | None found | Election management guide |
| **Is authority constitutionally valid?** | ConstitutionalArbitrationKernel | Any operation requiring authority | Through governance challenge process | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md |
| **What are the constitutional rules?** | DoctrineArtifact (immutable after creation) | Governance kernel | Unknown — doctrine is immutable, not challengeable | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md |
| **What are the consequences of revocation?** | Governance Context | Elections, audit processes | ADR-003: not yet defined | ADR-003 |
| **How many candidates must be selected per position?** | Organization configuration (SELECT_ALL_REQUIRED) | Voter (during ballot completion) | Election Committee can change before election | Business case document |
| **Who is appointed as Election Officer?** | Organisation administrator | Election management system | Not found | Election management guide |
| **Can a candidacy application be re-submitted?** | Election Officer (through reject, not block) | Applicant | Not found | Candidacy procedure |

### Decision Ownership Observations

**Election operations decisions are produced by humans with organizational roles:**
- Chief Election Officer
- Deputy Election Officer
- Election Officer (generic)
- Organisation administrator
- Organisation owner/admin

**Governance decisions are produced by computational processes:**
- ConstitutionalArbitrationKernel
- GovernanceDecisionKernel
- LegitimacyEvaluator
- ScopeAwareReplayValidator

**Challenge mechanisms differ:**
- Election operations: No challenge procedures found in sources. Officers can re-open, re-suspend, re-publish — but no formal challenge to officer decisions is documented.
- Governance: Challenge mechanisms are referenced (ADR-003, GovernanceLineageGraph) but not fully defined.

**One boundary explicitly stated in sources (ADR-003):**

```
Trust Attestation Context decides: Verification is revoked
Governance Context decides: Consequences for elections and results
```

This is an explicit decision boundary documented in an architectural source. It separates "detecting a problem" from "deciding the impact."

---

## Question 3 — Knowledge Ownership

**Question:** Who understands each capability deeply?

### Knowledge Ownership Table

| Capability | Primary Knowledge Holder | Evidence Source Type |
|-----------|------------------------|---------------------|
| Voter eligibility rules | Election officers, organisation administrators | Problem-space (user guides) |
| Candidacy approval process | Election officers | Problem-space (operational procedure) |
| Voting period management | Election officers | Problem-space (management dashboard guide) |
| Result publication rules | Election officers (Chief specifically) | Problem-space (role guide) |
| Ballot completion requirements | Election committee, organization leadership | Problem-space (business case) |
| Constitutional legitimacy evaluation | Systems architects, governance engineers | Solution-space (architecture docs) |
| Doctrine immutability and replay | Systems architects | Solution-space (architecture docs, ADRs) |
| Authority graph traversal | Systems architects | Solution-space (architecture docs) |
| Temporal authority windows | Systems architects | Solution-space (architecture docs) |
| Revocation consequence policy | Governance policy makers | Solution-space + gap (ADR-003 notes this is undefined) |
| Vote anonymity guarantees | Systems architects, election integrity specialists | Solution-space (CLAUDE.md, architecture notes) |
| Double vote prevention | Systems architects | Solution-space (bug analysis document) |

### Knowledge Ownership Observations

**Election operations capabilities** are primarily understood by organizational actors — people with election roles. The knowledge is captured in guides written *for* those actors.

**Governance and constitutional capabilities** are primarily understood by systems architects and engineers. The knowledge is captured in technical architecture documents written *by* those architects.

**Gap observed:** No evidence found of knowledge transfer between these two groups within the repository. Documents describing governance architecture do not reference the election officer role structure. Documents describing election officer procedures do not reference the constitutional governance engine.

---

## Question 4 — Change Drivers

**Question:** What causes each area to change?

### Change Driver Table

| Area | What Would Drive Change | Evidence of Change Driver |
|------|------------------------|--------------------------|
| **Officer appointment rules** | Organizational bylaws revision, new election policy | Business case references bylaws; bylaws absent from repository |
| **Candidacy approval process** | Change in election regulations, new appeal requirements | Operational procedure document (static, no change history visible) |
| **Voting period rules** | Election committee policy change, constitutional amendment | Election management guide describes re-open capability (policy choice) |
| **Ballot completion requirements** | Bylaw change, organizational policy decision | Business case explicitly references bylaw compliance |
| **Constitutional legitimacy evaluation** | Doctrine version update, governance reform | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md: Doctrine is immutable; new doctrine version creates new DoctrineArtifact |
| **Authority delegation rules** | Constitutional change, governance reform | GEO phases (3.0 through 3.4) — driven by architectural understanding, not by external policy |
| **Revocation consequence policy** | Governance policy decision | ADR-003: consequence policy is "missing" and undefined — no change driver found |
| **Vote anonymity guarantee** | Legal/privacy requirement, constitutional mandate | CLAUDE.md describes as design principle, not policy change |
| **Result publication permission** | Organizational role policy | Management dashboard guide (stable, no change signals) |

### Change Driver Observations

**Election operations change drivers are organizational and bylaw-driven:**
- Changes in election regulations
- Organizational bylaw revisions
- Election committee policy decisions
- New election requirements from organizational leadership

**Governance change drivers are architectural and doctrine-driven:**
- New constitutional doctrine versions (DoctrineArtifact)
- GEO phase requirements (engineering-driven)
- Replay compatibility requirements (technical)
- Architecture fitness test failures (technical)

**These are different change frequencies and change authorities:**
- Election operations: changes when the organisation decides to change its procedures
- Governance: changes when the architectural design evolves or when constitutional doctrine changes

**Notable overlap found:** Bylaw changes could affect both. A bylaw change affecting voter eligibility rules would affect election operations. A bylaw change affecting governance authority structure could affect the governance engine. However, the same bylaw change touches these two areas for different reasons and through different mechanisms.

---

## Question 5 — Language Translation Boundaries

**Question:** Do any documents translate between governance language and election operations language?

### Translation Boundary Search Results

**Documents examined for translation activity:**

| Document | Translates Between? | Evidence |
|----------|-------------------|---------|
| ADR-001: Constitutional Capability Sovereignty | Governance → Election operations | Partially — defines how backend governance decisions are surfaced as election capabilities |
| ADR-003: Governance-Driven Revocation | Trust/Verification → Governance | Yes — explicitly defines the handoff: "Trust detects the problem. Governance decides the impact." |
| ElectionPolicy.php (developer guide) | Governance roles → Election permissions | Yes — translates organisational role (owner/admin) into election creation authority |
| GEO-3.5 Voting Context Architecture | Engineering → Voting operations | Partially — engineering document uses election vocabulary (ballot, eligibility) but within solution-space framing |
| SELECT_ALL_REQUIRED business case | Business/bylaw → System behavior | Yes — translates organizational requirement (bylaws mandate full participation) into system configuration |

### Translation Boundary Observations

**Three points of translation were found:**

**Translation Point 1 (ADR-003):**
```
Trust Attestation vocabulary                      Governance vocabulary
  "Verification is revoked"          →           "Evaluate consequences"
  "Officer withdrew attestation"     →           "Governance policy decides impact"
```

Explicit boundary documented in ADR. The translation is acknowledged as a design decision, not an implementation detail.

**Translation Point 2 (ElectionPolicy):**
```
Organisational role vocabulary                    Election operations vocabulary
  "Owner" / "Admin"                  →           "Can create election"
  "Chief" / "Deputy"                 →           "Can manage settings"
  "Chief" only                       →           "Can publish results"
```

The policy explicitly notes this distinction: *"Election creation is an organisational governance decision. Election management is an election officer decision."*

**Translation Point 3 (Business Case):**
```
Organizational bylaw vocabulary                   System configuration vocabulary
  "Bylaws mandate full participation"  →         "SELECT_ALL_REQUIRED=yes"
  "Deliberate abstention"              →         "No Vote option"
  "Incomplete ballot"                  →         "Validation error prevented"
```

The business case is itself a translation document — it explains organizational requirements in terms that can drive system configuration decisions.

**No document translates from governance constitutional language to election operations language:**
- GovernanceLegitimacy states are not referenced in any election operations guide
- DoctrineArtifact is not referenced in any election officer procedure
- TemporalAuthorityWindow is not referenced in any operational workflow

This absence is an observation. The governance constitutional vocabulary does not appear in election operations documents, and election operations vocabulary does not appear in governance constitutional documents.

---

## Summary of Observations

### Observation 1: The vocabulary communities are distinct and non-overlapping

Governance vocabulary (Doctrine, Legitimacy, Arbitration, Epoch, Replay) is absent from election operations documents.

Election operations vocabulary (Chief, Commissioner, Ballot, Manifesto, Candidacy, Open voting) is absent from governance architecture documents.

This is not a documentation style difference. These are genuinely different vocabularies used by different communities.

---

### Observation 2: The examined documents appear written for different audiences

The governance documents examined appear primarily written for systems architects and engineers. They are expressed in technical architecture documents and ADRs using engineering vocabulary.

The election operations documents examined appear primarily written for election officers, organization administrators, and organizational leadership. They are expressed in operational guides, user manuals, and business cases using organizational vocabulary.

Whether knowledge ownership matches document authorship cannot be confirmed from this evidence alone. The observation is about the documents, not necessarily about who holds knowledge in the organization. A Chief Election Officer may understand constitutional doctrine without that knowledge appearing in their operational guides.

---

### Observation 3: The change drivers are different

Election operations change when organizational policy changes, bylaws change, or election committee decisions change.

Governance architecture changes when doctrine evolves, when architectural understanding deepens (GEO phases), or when engineering fitness requirements change.

These are different change cadences driven by different authorities.

---

### Observation 4: Three explicit translation points exist

Three documents were found that explicitly translate between the two knowledge systems:
- ADR-003 (Trust → Governance boundary)
- ElectionPolicy (Organisational roles → Election permissions)
- Business case (Bylaws → System configuration)

Translation points in DDD are significant. They often mark boundaries between contexts.

---

### Observation 5: Decision production is modeled differently in each knowledge system

Election operations sources model decisions as produced by human actors with organizational roles (Chief, Officer, Administrator). The role holder makes the decision.

Governance sources model decision production through computational mechanisms (ArbitrationKernel, LegitimacyEvaluator). The system evaluates authority according to constitutional rules.

Whether the underlying business decisions actually belong to different people or committees is not established by this observation. The ArbitrationKernel may execute policy defined by a governance committee. The observation is about how each knowledge system models and documents decision production, not necessarily about where business authority ultimately rests.

---

### Observation 6: The word "Election" means different things

In governance sources: "Election" is a scope parameter for constitutional authority (ConstitutionalScope includes ELECTION as one value among NATIONAL, REGIONAL, EMERGENCY, CARETAKER).

In election operations sources: "Election" is the primary organizational entity being managed — it has a creation date, officer appointments, voter lists, candidacy periods, voting periods, and result publication.

The same word carries different meaning in each knowledge system.

---

### Observation 7: The governance sources do not model actors

Governance sources define computation — kernels, validators, policies, snapshots. Actors (who performs actions) are largely absent.

Election operations sources define roles and permissions explicitly — Chief, Deputy, Commissioner each have distinct capabilities documented in detail.

This structural difference (governance = computation model; election operations = actor/role model) is consistent across all examined documents.

---

## Closing Statement

```
Observations collected.

No conclusions made.

No bounded context claims.

No architecture decisions.
```

The investigation has produced seven observations about the relationship between governance knowledge sources and election operations knowledge sources. 

These observations document:
- Vocabulary differences between document sets
- Different audience orientation in documentation
- Different change drivers reflected in sources
- Translation points between knowledge systems

The investigation has NOT determined whether these knowledge systems represent:
- Different bounded contexts
- Different views of the same context
- A context and its policy layer
- Separate domains

The observations are available as input for future discovery activities, when and if such activities are authorized.

The distinction between "knowledge systems" and "bounded contexts" is important. A knowledge system is a social and linguistic fact about how understanding is organized and transmitted. A bounded context is an architectural decision. The evidence presented here concerns the former, not the latter.
