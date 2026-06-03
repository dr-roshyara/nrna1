# Round 6A — Candidate Responsibilities

**Date:** 2026-06-03  
**Objective:** Determine whether a coherent responsibility boundary emerges from Rounds 1–6.0 evidence  
**Constraint:** Testing for coherence, not designing or concluding ownership

---

## Methodology

For each candidate responsibility:
1. Description
2. Supporting Evidence (from Rounds 1–6.0 only)
3. Contradicting Evidence
4. Confidence Level
5. Alternative Owner(s) — which other bounded context could own this?
6. Open Questions

**Success ≠ proving Evidence Context exists**  
**Success = identifying which responsibilities form the strongest candidate cluster and whether they cohere**

---

## Candidate Responsibility A: Evidence Preservation and Immutability

**Description:** Ensuring observations are frozen immediately after capture, and preventing modification or deletion after creation. Immutability is the mechanism; preservation is the domain concern.

### Supporting Evidence

- SecurityEventRecorder implements append-only storage (ElectionSecurityEvent prevents updates/deletes)
- Invariant I-1 states: "Evidence frozen after evaluation completes"
- Immutability constraints enforced at persistence layer
- Privacy design (removing voter identity before storage) suggests preservation is a domain concern
- Round 5 finding: "Append-only: ElectionSecurityEvent prevents updates (save() method throws)"
- Retention policy (730 days) enforced with immutability guarantee

### Contradicting Evidence

- SecurityEventRecorder is in Application/Infrastructure layer, not Domain layer
- Immutability could be a database constraint (Infrastructure concern)
- No domain-level aggregate currently enforces this invariant
- ElectionSecurityEvent is an Eloquent model, not a domain entity

### Confidence Level

**HIGH** (responsibility exists operationally)  
**MEDIUM** (ownership certainty — could belong to Infrastructure or Evidence Context)

### Alternative Owner(s)

| Owner | Reasoning |
|-------|-----------|
| Infrastructure | Database constraints naturally belong here; append-only is a storage pattern |
| Evidence Context (Domain) | Preservation is a domain rule: "evidence cannot change after publication" |
| Election Context | Elections contain evidence; preservation could be election responsibility |

### Open Questions

1. Is preservation a domain business rule or a storage mechanism?
2. Does Evidence Context own the rule, or only the enforcement?
3. Could Election Context claim this responsibility?

---

## Candidate Responsibility B: Evidence Privacy Preservation

**Description:** Ensuring evidence records cannot be used to reconstruct voter identity, either directly or indirectly, without explicit constitutional authority.

### Supporting Evidence

- Invariant EVI-5 explicitly states: "Evidence must not permit reconstruction of voter identity without explicit constitutional authority"
- SecurityEventRecorder explicitly nulls `voter_slug_id` (deliberate, not accidental)
- Raw IP addresses hashed before SecurityEventRecorder receives them
- Architectural decision to null voter identity in evidence but include it in domain events suggests deliberate boundary
- Round 5 finding: "Privacy design explicitly removes voter identifying information"

### Contradicting Evidence

- Privacy preservation could be a system-wide policy (cross-cutting concern)
- TrustEvidencePrivacyPolicy exists in Infrastructure layer
- Not unique to Evidence Context; would apply to any voter-linked context

### Confidence Level

**HIGH** (responsibility exists operationally)  
**MEDIUM-HIGH** (ownership uncertain — could be Evidence, infrastructure, or system-wide policy)

### Alternative Owner(s)

| Owner | Reasoning |
|-------|-----------|
| Cross-Cutting Concern | Privacy rules apply to all voter data globally |
| Election Context | Election already controls voter data; could own privacy rules |
| Evidence Context | Evidence specifically requires voter-identity-free records |

### Open Questions

1. Is voter privacy a system-wide governance policy or Evidence-specific domain rule?
2. Would privacy preservation rules be identical for non-election contexts?
3. Does Evidence own the rule, or inherit it from a system-wide policy?

---

## Candidate Responsibility C: Observation Recording and Capture

**Description:** Recording domain events as objective facts about elections (voter assignments, voting window open/close, legitimacy evaluations).

### Supporting Evidence

- SecurityEventRecorder actively records observations during voting
- Five domain events designed for observation (ObservationRecorded, LegitimacyEvaluated, etc.)
- Round 4 finding: "SecurityEventRecorder actively called in TrustPolicyEvaluator during every vote evaluation"
- Events capture facts (timing, context) not interpretations
- EvidenceEventTaxonomy distinguishes observed vs. target events

### Contradicting Evidence

- Recording could belong to Observation Context (separate bounded context)
- Domain events carry `voterIdentifier`; SecurityEventRecorder does not (opposite choices)
- Recording happens in election workflow, suggesting Election Context ownership
- No evidence that Evidence Context initiates observation capture

### Confidence Level

**HIGH** (observations are recorded)  
**LOW** (ownership unresolved)

### Alternative Owner(s)

| Owner | Reasoning |
|-------|-----------|
| Observation Context | Recording facts is observation work; Evidence interprets facts |
| Election Context | Observations are election-specific facts; could live here |
| Evidence Context | Evidence receives and freezes observations |

### Open Questions

1. Does Evidence Context record observations, or receive them from Observation Context?
2. Is "observation" a separate bounded context, or responsibility within Evidence/Election?
3. Does the decision on C affect the decision on A/B?
4. Would C be part of Evidence or separate?

**Note:** Do NOT conclude C belongs elsewhere yet. Ownership is genuinely unresolved.

---

## Candidate Responsibility D: Constitutional Authority Evidence

**Description:** Recording which authorities (voter eligibility, device trust, network continuity, governance rules) evaluated and approved participation. This connects evidence to the constitutional decision chain.

### Supporting Evidence

- Invariant VR-5: "Verification must support replayability" — implies evidence includes authority chain
- ConstitutionalEvidenceSnapshot hypothesis includes "evaluatedAt" and "electionConstitutionSnapshot"
- Round 5 finding: "evidence must enable reconstruction of evaluation"
- Domain events reference "gate conditions" and "migration phases" (external authorities)
- Replay verification (VR-5) requires knowing which authority validated what
- **Critical question:** If Evidence only owns A (preservation) + B (privacy), why isn't it just infrastructure? Answer: D makes it a domain concept.

### Contradicting Evidence

- Authority tracking could belong to Evaluation Context (evaluation's decision metadata)
- No deployed aggregate currently owns authority evidence
- Five domain events inactive; unclear if they represent this

### Confidence Level

**MEDIUM** (evidence suggests need; implementation unclear)  
**CRITICAL:** This responsibility is a **primary differentiator** between Evidence as domain vs. infrastructure.

### Alternative Owner(s)

| Owner | Reasoning |
|-------|-----------|
| Evaluation Context | Evaluation makes decisions; authority is metadata of decision-making |
| Governance Context | Authority is governance concern |
| Evidence Context | Evidence must record which authority was consulted for replay |

### Open Questions

1. **CRITICAL:** Does Evidence own authority metadata, or receive it from Evaluation?
2. Is "which authority approved this" evidence, or evaluation metadata?
3. What is the minimum authority information needed for replay verification (VR-5)?
4. **KEY QUESTION:** If D belongs elsewhere, does Evidence Context have sufficient domain substance to be a bounded context, or is it infrastructure?

**Note:** D is the strongest candidate for distinguishing Evidence Context as domain-level.

---

## Candidate Responsibility G: Cryptographic Integrity (Future)

**Description:** Computing and verifying hashes/signatures that prove evidence has not been tampered with.

### Supporting Evidence

- VR-1: "Evidence must be independently verifiable"
- Deferred in Round 6.0; insufficient evidence to evaluate now
- Would enable external verification without access to evidence context

### Contradicting Evidence

- No operational implementation found
- Five domain events inactive; unclear if represent this
- Implementation deferred to future phase

### Confidence Level

**LOW** (future/deferred; insufficient evidence to assign ownership)

### Alternative Owner(s)

| Owner | Reasoning |
|-------|-----------|
| Verification Context | Verification computes hashes; stores data belongs here |
| Evidence Context | Evidence must be cryptographically sealed |

### Open Questions

1. Is cryptographic integrity part of Evidence preservation, or separate concern?
2. When should this be implemented relative to A/B/D?
3. Should this influence A/B/D decisions, or can it be deferred?

---

## Coherence Analysis

### Strongest Candidate Cluster

**Responsibilities that appear to cohere:**

- **A: Evidence Preservation** (immutability + retention)
- **B: Evidence Privacy Preservation** (no voter re-identification)
- **D: Constitutional Authority Evidence** (replaying authority decisions)

**Why cluster together:** A and B define the form of evidence (frozen, private). D defines the content (authority chain). All three are domain-level concerns that, together, answer "What must evidence contain and how must it behave?"

---

### Responsibilities with Unresolved Ownership

- **C: Observation Recording** — Could be Evidence, Observation, or Election. Ownership unclear.
- **G: Cryptographic Integrity** — Deferred to future. Insufficient evidence.

---

### Critical Coherence Test

**Question:** Do A, B, and D form a coherent responsibility cluster?

**Sub-question:** Is D the differentiator that makes Evidence a domain bounded context, or is it better owned by Evaluation?

**Assessment:**

| Aspect | Result |
|--------|--------|
| **A + B cohere?** | YES — preservation + privacy form unified "form of evidence" concern |
| **D strengthens A+B?** | YES — authority evidence explains what evidence must contain |
| **Without D, what is Evidence?** | Infrastructure (frozen private records) |
| **With D, what is Evidence?** | Domain (constitutional authority trail) |
| **Operational evidence for D** | WEAK (no deployed aggregate; 5 events inactive) |
| **Inference evidence for D** | STRONG (VR-5 requires it; hypothesis includes it) |

---

## Cross-Context Responsibility Mapping

```
Observation Context (or Election Context)
    ↓ (captures facts about elections)
Evidence Context        ← A: Preservation
                        ← B: Privacy Preservation
                        ← D: Constitutional Authority (key differentiator)
                        ← C: Maybe? (unresolved)
    ↓ (stores frozen, private, authority-traced evidence)
Evaluation Context      
    ↓ (interprets evidence)
Legitimacy/Governance Context
```

---

## Decision Gate

**Question:** Does a coherent responsibility cluster emerge?

**Answer:** PROVISIONALLY YES (with conditions)

**Evidence:**

✅ **A and B are strong candidates** — both operational and domain-aligned  
✅ **D is the key test** — if Evidence owns D, it's a domain bounded context; if not, it may be infrastructure  
⚠️ **C ownership is unresolved** — decision on C may affect final cluster  
⚠️ **G is deferred** — insufficient evidence to evaluate

---

## Confidence Levels

| Responsibility | Exists? | Domain-Level? | Ownership Clear? |
|---|---|---|---|
| A (Preservation) | HIGH | MEDIUM | MEDIUM |
| B (Privacy) | HIGH | MEDIUM-HIGH | MEDIUM |
| D (Authority Evidence) | MEDIUM | HIGH | LOW |
| C (Observation) | HIGH | MEDIUM | LOW |
| G (Cryptographic) | LOW | MEDIUM | LOW |

---

## Strongest Candidate Outcome

**Cluster:** Responsibilities A, B, and potentially D form the strongest candidate responsibility group.

**Why this cluster matters:**
- A + B define evidence structure (frozen, private)
- D defines evidence content (authority chain)
- Together, they distinguish Evidence from mere data storage

**Key remaining question:** Does Evidence Context own D (constitutional authority), or does D belong to Evaluation Context?

---

## Next Step

**Recommended:** Proceed to Round 6B — Candidate Invariants

**Evaluate:**
1. Which invariants do A, B, D enforce?
2. How do they interact?
3. Can invariants be stated without assuming Evidence Context exists?
4. Does D's assignment to invariants clarify ownership?

**Alternative:** If D ownership remains critical blocker, consider author clarification on whether "constitutional authority evidence" is Evidence-owned or Evaluation-owned before proceeding to 6B.

---

**Status: Round 6A complete. Responsibilities A, B, D emerge as strongest candidate cluster. C and G remain unresolved. D is the key differentiator for domain vs. infrastructure classification.**
