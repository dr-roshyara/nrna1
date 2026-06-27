# Round 18 — Governance Clarification Preparation (Charter)

**Date:** 2026-06-07

**Phase:** Round 18 — Governance Clarification Preparation (Non-Repository Discovery Charter)

**Status:** Awaiting ARB Review — Execution Not Yet Authorized

**Scope:** Prepare governance clarification investigation for D42, D22, D30. Identify questions, sources, and success criteria. No governance sources have been consulted yet — this document prepares the investigation, it does not report findings.

---

## 1. Integrity Guarantees Investigation (D42)

D42 asks: "What election integrity guarantees are explicitly intended by the system?"

### Eligibility

**Evidence from Round 17:**
- ElectionConstitution preconditions reference eligibility (has_voters, has_chief, has_approved_candidates)
- ParticipationEligibilityEvidence captures membership status, assignment validity, approval, suspension status
- ElectionMembership model tracks active/suspended status
- VoterSlugStep tracks voter progress through 5-step workflow

**Remaining Unknown:**
- Source of eligibility policy: constitutional requirement, organizational governance, or implementation choice?
- What constitutes sufficient eligibility evidence? (D13)
- Are there regulatory or legal constraints on eligibility?

**Questions for Governance Clarification:**
- Who defines eligibility criteria? Is this a constitutional rule, an organizational policy, or a system configuration?
- What happens when eligibility determination is contested?

---

### Participation Integrity

**Evidence from Round 17:**
- participation_proof on BaseVote enables IP-based verification
- vote_hash unique constraint prevents duplicates
- device_fingerprint_hash enables duplicate device detection
- VoterSlugStep tracks step completion for workflow integrity

**Remaining Unknown:**
- Is participation proof intended to prevent double-voting across all scenarios?
- Are device fingerprint checks sufficient for fraud detection?

**Questions for Governance Clarification:**
- What level of participation assurance is required?
- Is duplicate detection a legal requirement or a technical safeguard?

---

### Vote Integrity

**Evidence from Round 17:**
- data_checksum (SHA256) computed over candidate selections + app key
- verifyChecksum() enables tamper detection
- No user_id column — anonymity by design

**Remaining Unknown:**
- What happens when checksum verification fails?
- Is checksum verification run periodically or only on request?
- What data does the checksum cover — all relevant fields?

**Questions for Governance Clarification:**
- What integrity guarantees are required for vote storage?
- Is cryptographic verification a policy requirement or an implementation choice?
- What is the expected response to integrity violations?

---

### Result Integrity

**Evidence from Round 17:**
- syncResults() can regenerate results from vote JSON source of truth
- verifyResultsIntegrity() compares stored count vs expected count
- Results are a derived projection — not independent records
- results_published flag gates visibility, not computation

**Remaining Unknown:**
- Is result drift detection run in production? (D41)
- What guarantees confidence in result accuracy?
- Is manual verification required before publication?

**Questions for Governance Clarification:**
- What level of result integrity assurance is required?
- Is the counting state a legal requirement (waiting period), a manual verification step, or a technical gate?
- Who is authorized to verify results before publication?

---

### Auditability

**Evidence from Round 17:**
- ElectionAuditLog tracks state changes with old/new values, user, IP, session
- ElectionAuditService per-voter JSONL files with step-level tracking
- SecurityEventRecorder logs trust evaluation events
- GovernanceReplayService enables snapshot replay for governance verification
- GovernanceDecisionSnapshot provides integrity-verified decision records

**Remaining Unknown:**
- Is full chain-of-custody reconstruction supported?
- Are audit logs sufficient for post-election dispute resolution?
- Is there a retention or destruction policy for audit data?

**Questions for Governance Clarification:**
- What auditability requirements exist? Legal, regulatory, or organizational?
- How long must audit records be retained?
- Who has access to audit data?

---

### Verifiability

**Evidence from Round 17:**
- receipt_hash enables voter self-verification without revealing vote choice
- verifyByReceipt() compares hashed receipt against stored hash
- verifyByCode() validates vote against code-based cryptographic expectation
- proveParticipation() enables admin verification of voter participation without revealing vote

**Remaining Unknown:**
- Are verification mechanisms adequate for intended assurance level?
- Is individual verifiability (voter verifies own vote) or universal verifiability (anyone verifies) required?
- Are receipts durable and accessible to voters?

**Questions for Governance Clarification:**
- What verifiability guarantee is intended — individual, universal, or both?
- What assurance level is required?
- How are verification disputes handled?

---

### Transparency

**Evidence from Round 17:**
- ResultController gating via results_published flag
- State machine enforces publication workflow (chief-only)
- Audit logs available for review

**Remaining Unknown:**
- What transparency means beyond result publication?
- Are internal governance decisions transparent?

**Questions for Governance Clarification:**
- What transparency requirements exist?
- Should governance decisions (e.g., suspension, arbitration) be transparent to voters?

---

### Privacy

**Evidence from Round 17:**
- No user_id in votes table — anonymity is a core design principle
- Email masking in audit logs (ElectionAuditService.maskEmail)
- receipt_hash does not reveal vote choice
- participation_proof does not reveal vote choice
- device metadata is anonymized

**Remaining Unknown:**
- What privacy guarantees beyond vote anonymity?
- Is voter privacy against insiders (election officials) required?

**Questions for Governance Clarification:**
- What privacy guarantees are required?
- Is vote anonymity absolute or qualified (e.g., for legal challenges)?
- Who can access voter-identifying audit data?

---

## 2. Constitutional Rule Origin (D22)

**Question:** Where do constitutional state transition rules originate?

**Evidence from Round 17:**
- ElectionConstitution.RULES array is the single implementation registry
- Rules control all state transitions, roles, and preconditions
- No source documents or references found in examined repository
- No organizational bylaws, governance policies, or regulatory references found

**Possible Sources (unresolved):**
- **Organizational bylaws:** Rules may reflect organizational governance structure
- **Election regulations:** May be derived from legal requirements for voting systems
- **Domain requirements:** May reflect best practices from election domain
- **Technical constraints:** May be shaped by platform architecture decisions
- **Platform policy:** Free/paid tier distinction (40-voter threshold) suggests business model influence

**Questions for Governance Clarification:**
- Who defined the initial set of constitutional rules?
- Are these rules derived from an external source (law, regulation, constitution)?
- Are rules specific to this platform or generalized from election domain knowledge?
- Is there a governance document that defines the election lifecycle?

---

## 3. Rules-in-Code Intent (D30)

**Question:** Are rules in code intentional (immutable constitution) or temporary (awaiting rule engine)?

**Evidence from Round 17:**
- All rules are embedded in ElectionConstitution.RULES PHP array
- Changing rules requires code deployment — no runtime configuration mechanism observed
- Rules are NOT tenant-differentiated in examined implementation
- No rule engine, configuration file, or external policy source found
- Stub implementation for capacity_eligibility payment check suggests some features incomplete

**Possible Interpretations (unresolved):**
- **Intentional immutability:** Rules are designed to be immutable for constitutional protection — changes require deliberate governance process
- **Temporary embedding:** Rules are embedded in code because rule engine or configuration mechanism is not yet implemented
- **Transitional state:** Current code is an intermediate state between simpler earlier implementation and planned flexible architecture
- **Hybrid:** Some rules are intentionally immutable (state transitions), others are temporarily embedded (preconditions, thresholds)

**Questions for Governance Clarification:**
- Is the in-code rule approach intentional or temporary?
- If intentional, what governance process governs rule changes?
- If temporary, is there a planned rule engine or configuration mechanism?
- Are there any documented ADRs about rule management strategy?

---

## 4. Impact on Discovery Debt

### D42A — What Integrity Guarantees Exist Today? (Repository-Answerable)

**Status:** Partially answerable from Round 17 evidence. Stream 3 cataloged 9 integrity-related artifacts across Eligibility, Participation, Vote, Result, Audit, and Verifiability domains.

**Next Step:** Map existing artifacts to guarantee categories using repository evidence — no governance access required.

---

### D42B — What Integrity Guarantees Were Intended? (Governance-Answerable)

**Status:** Governance clarification initiated. No governance sources have been consulted yet.

**Next Step:** Requires ADR archaeology, architect interviews, governance document review, or stakeholder consultation.

**Path to Resolution:**
- Interview platform architects about design intent for integrity mechanisms
- Review organizational governance documents for guarantee requirements
- Identify any regulatory or legal constraints on election integrity
- Determine which guarantees are constitutional, which are policy, and which are implementation choices

---

### D22 — Updated

**Status:** Governance clarification initiated. Source of rules remains unknown.

**Next Step:** Requires governance document review or stakeholder interviews.

**Path to Resolution:**
- Review organizational bylaws or governance policies
- Interview decision-makers about rule origin
- Check for external regulatory requirements

---

### D30 — Updated

**Status:** Governance clarification initiated. Intent remains unknown.

**Next Step:** Requires ADR archaeology or architect interviews.

**Path to Resolution:**
- Search for ADRs or design documents about rule management
- Interview architects about rule configuration plans
- Determine if stub implementations (capacity_eligibility) indicate incomplete migration or intentional design

---

## 5. Governance Hypotheses

**Hypothesis 1 (Unconfirmed):** The system contains integrity mechanisms that appear to address multiple election guarantees, but the intended guarantees are not explicitly documented. Stream 3 cataloged at least 9 integrity-related artifacts, but their purpose relative to specific guarantees is inferred from code comments, not from governance requirements.

**Hypothesis 2 (Unconfirmed):** Constitutional rules exist in a single centralized registry but their origin is undocumented. The rules may derive from organizational governance, election domain knowledge, legal requirements, or technical constraints — but no source documents exist in the examined repository.

**Hypothesis 3 (Unconfirmed):** The in-code rule representation may be intentional, temporary, or transitional. Without understanding the design intent, it is impossible to determine whether the current rule structure is stable or subject to change.

---

## 6. Governance Sources Matrix

The following sources may be able to answer governance-origin questions. This matrix identifies who or what to consult — sources have not yet been engaged.

| Question | Potential Source | Type |
|----------|----------------|------|
| D42B — Intended integrity guarantees | Chief Architect / Platform Architect | Interview |
| D42B — Election guarantee requirements | Product Owner / Domain Expert | Interview |
| D42B — Regulatory or legal constraints | Election Commission / Legal Counsel | Document review |
| D22 — Constitutional rule origins | Original Platform Architects / ADRs | Interview + ADR archaeology |
| D22 — Organizational governance source | Governance Committee / Bylaws | Document review |
| D30 — Rules-in-code design intent | Original Platform Architects / ADRs | Interview + ADR archaeology |
| D30 — Rule engine plans | Technical Lead / Product Roadmap | Interview |

---

## 7. Exit Criteria

Round 18 Governance Clarification may be considered complete when:

- **D42B** — Intended election integrity guarantees have an explicit answer (even if that answer is "not formally documented")
- **D22** — Constitutional rule origin has an explicit answer (source identified or confirmed as unknown)
- **D30** — Rules-in-code design intent has an explicit answer (intentional, temporary, or transitional)
- **Governance sources consulted** — Each source in the Governance Sources Matrix has been engaged or explicitly documented as unavailable
- **Remaining uncertainty classified** — All governance unknowns are classified as resolved, partially resolved, or unresolvable

---

## 8. Remaining Unknowns

| Debt | Question | Status | Required Action |
|------|----------|--------|----------------|
| D42A | What integrity guarantees exist today? | Partially answerable from repository evidence | Map existing artifacts to guarantee categories |
| D42B | What integrity guarantees were intended? | Unresolved — governance sources not yet consulted | Interview architects, review governance docs |
| D22 | Where do constitutional rules originate? | Unresolved — governance sources not yet consulted | Governance document review, stakeholder interviews |
| D30 | Are rules-in-code intentional or temporary? | Unresolved — design intent sources not yet consulted | ADR archaeology, architect interviews |

---

**Round 18 Governance Clarification Preparation — READY FOR ARB REVIEW**

**No governance sources have been consulted yet. No candidate contexts, bounded context discovery, or architecture conclusions were produced.**

**Next step after ARB review: Either authorize Round 18 Governance Discovery Execution (contact sources), or determine whether existing preparation is sufficient to proceed to Candidate Context Discovery with provisional governance understanding.**
