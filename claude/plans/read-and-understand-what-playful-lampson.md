# Round 7 — Candidate Context Mapping (Exploratory)

## Context

Phase 2 (Rounds 6A–6F) is complete. The Phase 2 Governance Gate (ARB_Decision_Record_Phase2.md)
conditionally authorized Round 7 under 7 explicit conditions. All conditions are exploratory:
no tactical design, no aggregate design, no implementation, boundaries remain revisable.

The discovered concepts are: Evidence, Verification, Authority, Legitimacy, Recognition.
The established domains are: Membership, Election, Governance, Appeals, Fraud Investigation.
Two operational modes must be tested: Election-Only and Full Membership.

**Goal:** Produce `Round7_CandidateContextMap_v1.md` — a candidate boundary map synthesized
from all Phase 1 and Phase 2 discoveries, stress-tested for coherence, with all
assumptions and unknowns explicitly documented.

**No code changes. Documentation only.**

---

## Input Documents (read before writing)

### Primary synthesis inputs

| Document | Key Content |
|----------|-------------|
| `docs/architecture/contexts/VerificationDiscoverySummary.md` | Phase 2 complete findings, open questions, entering assumptions |
| `docs/architecture/contexts/ARB_Decision_Record_Phase2.md` | Governance gate answers, 7 conditions, what remains unresolved |
| `docs/architecture/contexts/AuthorityDiscoveryFindings.md` | 5 cross-domain patterns, 4 conflicts, 3 candidate hypotheses (H-A/H-B/H-C) |
| `docs/architecture/contexts/Round6F2_AuthorityStressTest.md` | 8 stress test results — H-A weakens, H-B/H-C viable |

### Earlier discovery inputs (context reference)

| Document | Key Content |
|----------|-------------|
| `docs/architecture/contexts/EvidenceContextLegitimacySynthesis.md` | Legitimacy as temporal emergent concept |
| `docs/architecture/contexts/EvidenceContextVerificationStressTest.md` | Verification stress test results |
| `docs/architecture/contexts/EvidenceContextCandidateAssessment.md` | Original Evidence Context candidate assessment |
| `docs/architecture/contexts/Round5_ResearchFindings.md` | Phase 1 research findings |

---

## Governance Constraints (must not be violated)

1. Context mapping remains exploratory (not final architecture)
2. Boundaries remain revisable
3. All assumptions must be documented explicitly
4. No aggregate design
5. No tactical design
6. No implementation decisions
7. Round 7 findings require governance review before Round 8

---

## Execution Steps

### Step 0 — Context Identification Heuristics

Before evaluating any element, apply these heuristics. A bounded context candidate should demonstrate at least 3 of 6:

1. **Unique language** — terms mean something different here than in other domains
2. **Unique decisions** — makes business decisions that no other domain makes
3. **Unique consistency rules** — enforces invariants that belong only to this domain
4. **Independent evolution** — can change without forcing changes in other domains
5. **Boundary pressure** — merging it with another context would create ambiguity
6. **Organizational ownership** — would a real organization assign different people or committees to govern this area?

If fewer than 3 are present: context candidacy weakens. Reclassify as capability, supporting domain, or cross-cutting concern.

Document heuristic score for each candidate (all 6 criteria, with rationale for each).

---

### Step 1 — Candidate Architectural Element Identification

For each discovered element, evaluate and document architectural classification:

**Candidate architectural elements to evaluate:**
- Membership
- Election
- Governance
- Appeals (Appeal Processing)
- Fraud Investigation
- Evidence
- Verification

**Round 7 must determine whether each is:**
- A bounded context (owns unique language + decisions + consistency rules)
- A capability within another context
- A cross-cutting concern (present everywhere, orthogonal to domains)
- A supporting domain
- A shared concept appearing in multiple contexts

Apply the heuristics from Step 0 to each. Document rationale. Do NOT decide finally.

---

### Step 2 — Discovered Concept Ownership

For each discovered concept, document ownership and relationships:

**Concepts to map:**
- Evidence
- Verification
- Authority
- Legitimacy
- Recognition

**For each, document:**
- Which context appears to own it?
- Which contexts consume it?
- Which contexts influence it?
- Can multiple contexts interpret it differently (translation)?
- Does it cross context boundaries?

---

### Step 3 — Election Mode Analysis

Test every candidate boundary against both operational modes:

**Election-Only Mode:**
- Does the boundary still make sense?
- Does ownership remain consistent?
- Does verification remain coherent without public voter list?

**Full Membership Mode:**
- Does the boundary still make sense?
- Does eligibility verification alter ownership?
- Does distributed authority change context responsibilities?

Record differences. Identify mode-specific boundary requirements.

---

### Step 4 — Context Relationship Mapping

For every candidate context, identify:
- Upstream relationships (who supplies information)
- Downstream relationships (who consumes information)
- Shared concepts (which appear in multiple contexts)
- Translation requirements (do terms change meaning across contexts?)

---

### Step 5 — Boundary Stress Testing

For every proposed boundary, attempt to break it by asking:
- Can this context exist independently?
- Does this context make unique business decisions?
- Does this context own unique language?
- Would merging it create ambiguity?
- Would splitting it create duplication?

Document breaks and why they occur.

---

### Step 6 — Authority and Legitimacy Assessment

Because H-B (Authority Family) and H-C (Authority Is Cross-Cutting) both remain viable:

**For Authority:**
- Test H-B: does placing Authority in each domain create coherent boundaries?
- Test H-C: does treating Authority as cross-cutting simplify the map?
- Record which hypothesis the map favors and why

**For Legitimacy:**
- Test whether Legitimacy fits as a bounded context
- Test whether Legitimacy fits as an emergent property
- Record what the map suggests

Do NOT conclude. Document observations only.

---

### Step 7 — Recognition Assessment

Recognition appeared repeatedly but was never deeply investigated.

**Evaluate:**
- Domain concept (belongs to a specific context)?
- Cross-cutting concern (present in all contexts)?
- Emergent property (product of Authority + Verification + Transparency)?
- Architectural illusion (actually just "trust" by another name)?

Do not force classification. Document what the map reveals.

---

### Step 8 — Alternative Map Challenge

After producing the first candidate boundary arrangement, construct at least two alternative maps and compare them. A single map often looks correct; multiple maps reveal hidden assumptions.

**Map A (Maximum Separation):**
All 7 elements are separate bounded contexts:
- Membership | Election | Governance | Appeals | Fraud | Evidence | Verification

**Map B (Evidence + Verification Merged):**
Evidence and Verification exist as a single capability layer inside another context:
- Membership | Election | Governance | Appeals | Fraud | Evidence+Verification (capability)

**Map C (Evidence as Infrastructure, Verification as Capability):**
Evidence is infrastructure (like logging); Verification is a capability within Election or Governance:
- Membership | Election (owns Verification) | Governance | Appeals | Fraud | Evidence (infrastructure)

**For each map, document:**
- What does this map make easy?
- What does this map make hard?
- What assumptions does this map require?
- Does this map hold under Election-Only mode?
- Does this map hold under Full Membership mode?

**Then select** the map that survives the most stress with the fewest assumptions.

The selected map becomes **Candidate Context Map v1**. The alternatives remain documented as rejected candidates with rationale.

---

## Output File

**Location:** `docs/architecture/contexts/Round7_CandidateContextMap_v1.md`

**Required sections (15):**

1. **Heuristic Scoring** — each element scored against 5 heuristics with rationale
2. **Candidate Architectural Elements** — list with classification (context/capability/cross-cutting/supporting/shared) and rationale
3. **Context Responsibilities** — what each candidate context owns
4. **Context Relationships** — upstream/downstream dependencies mapped
5. **Ownership Analysis** — which context owns Evidence, Verification, Authority, Legitimacy, Recognition
6. **Election Mode Analysis** — boundary coherence per mode
7. **Boundary Stress Test Results** — what held, what broke, and why
8. **Authority Assessment** — H-B vs H-C findings from the map
9. **Legitimacy Assessment** — bounded context vs emergent from the map
10. **Recognition Assessment** — domain/cross-cutting/emergent/illusion from the map
11. **Alternative Maps** — Map A, B, C with trade-offs and rejection rationale
12. **Assumptions** — all provisional decisions explicitly listed
13. **Unknowns** — all unresolved questions explicitly listed
14. **Risks** — architectural risks if map is wrong
15. **Candidate Context Map v1** — the selected map with visual ASCII or table representation

---

## Success Criteria

- Candidate boundaries are visible and documented
- Ownership conflicts are identified (not resolved)
- Assumptions are documented explicitly
- Unknowns are listed explicitly
- Boundary stress test results documented
- Authority/Legitimacy/Recognition assessments do NOT conclude — they observe
- The map makes no tactical design decisions

---

## What This Round Does NOT Produce

- Final architecture
- Aggregate design
- Repository patterns
- API contracts
- Implementation plan
- `VerificationDecisionMatrix.md` (deferred until after Round 7 review)
