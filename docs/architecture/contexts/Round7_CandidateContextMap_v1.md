# Round 7 — Candidate Context Map v1

**Date:** 2026-06-03  
**Phase:** 2 (Post-Governance Gate)  
**Status:** Exploratory. Boundaries are candidates, not conclusions.  
**Governance:** Conditional authorization under 7 constraints (ARB_Decision_Record_Phase2.md)  
**Purpose:** Test whether concepts discovered in Phases 1–2 form coherent bounded contexts.

**CRITICAL:** This document tests boundaries against discoveries. It does NOT conclude architecture. All findings require governance review before Round 8.

---

## Context Identification Heuristics

A bounded context candidate must demonstrate at least 3 of 6 criteria:

| Heuristic | Definition |
|-----------|-----------|
| **Unique Language** | Terms mean something different here than in other domains |
| **Unique Decisions** | Makes business decisions that no other domain makes |
| **Unique Consistency Rules** | Enforces invariants that belong only to this domain |
| **Independent Evolution** | Can change without forcing changes in other domains |
| **Boundary Pressure** | Merging with another context would create ambiguity |
| **Organizational Ownership** | Real organization would assign different people/committees to govern |

**Minimum threshold:** 3 of 6 required for bounded context status.  
**Below threshold:** Reclassify as capability, supporting domain, or cross-cutting concern.

---

## Heuristic Scoring — Candidate Architectural Elements

### Membership

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Eligible," "Applicant," "Member status," "Suspension" — vocabulary specific to membership decisions |
| Unique Decisions | ✓ STRONG | Decides who can/cannot participate in elections; manages lifecycle (apply → approve → suspend → reinstate) |
| Unique Consistency Rules | ✓ STRONG | Maintains invariants: "Active members are current," "Payment status determines eligibility," "One person one membership" |
| Independent Evolution | ✓ MODERATE | Can change membership rules without changing election rules; but Full Membership mode couples membership to voter eligibility |
| Boundary Pressure | ✓ STRONG | Merging with Governance blurs "who sets policy" from "who decides eligibility"; merging with Election breaks the separation of concerns |
| Organizational Ownership | ✓ STRONG | Real organizations have Membership Committee (distinct from Election Committee, Governance, Appeals) |

**Score: 6/6 — BOUNDED CONTEXT CANDIDATE (STRONG)**

---

### Election

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Voter," "Ballot," "Vote count," "Certification," "Candidate," "Post" — specific to election execution |
| Unique Decisions | ✓ STRONG | Decides when/how voting happens, certifies counts, assigns observers, publishes results |
| Unique Consistency Rules | ✓ STRONG | Maintains: "One vote per voter," "Count matches ballots," "Results certified before publication," "Votes are anonymous" |
| Independent Evolution | ✓ STRONG | Election rules can change without changing membership or governance policy |
| Boundary Pressure | ✓ VERY STRONG | Merging with Membership conflates "who can vote" (Membership) with "how voting works" (Election); merging with Governance conflates policy with execution |
| Organizational Ownership | ✓ VERY STRONG | Real organizations have Election Committee (distinct from Membership, Governance, Appeals) |

**Score: 6/6 — BOUNDED CONTEXT CANDIDATE (VERY STRONG)**

---

### Governance

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Policy," "Rule," "Constitutional," "Precedence," "Override" — vocabulary of governance |
| Unique Decisions | ✓ STRONG | Decides eligibility rules, voting rules, appeals process, authority precedence, constitutional questions |
| Unique Consistency Rules | ✓ STRONG | Maintains: "Policies are constitutional," "Precedence rules are consistent," "No self-contradicting rules" |
| Independent Evolution | ✗ WEAK | Governance cannot change without affecting everything; changes ripple to Membership, Election, Appeals |
| Boundary Pressure | ✓ STRONG | Merging with Membership/Election would conflate policy-setting with policy execution |
| Organizational Ownership | ✓ STRONG | Real organizations have Governance body distinct from operational committees |

**Score: 5/6 — BOUNDED CONTEXT CANDIDATE (STRONG)**

---

### Appeals

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Appeal," "Reversal," "Due process," "Challenge," "Grounds," "Appeal authority" — specific vocabulary |
| Unique Decisions | ✓ STRONG | Decides whether to reverse prior decisions; overturns membership/election/governance decisions |
| Unique Consistency Rules | ✓ STRONG | Maintains: "Appeals follow due process," "Grounds are documented," "Reversals are consistent," "Appeals reach finality" |
| Independent Evolution | ✓ MODERATE | Can change appeals process without changing what's appealable; but dependent on Membership/Election/Governance existing |
| Boundary Pressure | ✓ STRONG | Merging with Membership/Election/Governance would remove separation of decision-maker from reviewer |
| Organizational Ownership | ✓ STRONG | Real organizations have Appeals Committee (distinct from Membership, Election, Governance) |

**Score: 6/6 — BOUNDED CONTEXT CANDIDATE (STRONG)**

---

### Fraud Investigation

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Fraud," "Invalid," "Duplicate," "Device fingerprint," "Investigation," "Evidence of misconduct" — specific vocabulary |
| Unique Decisions | ✓ STRONG | Investigates irregularities, determines if fraud occurred, invalidates results/votes, recommends prosecution |
| Unique Consistency Rules | ✓ MODERATE | Maintains investigation standards, but fraud definitions vary (what counts as "fraud" is Governance's decision) |
| Independent Evolution | ✗ WEAK | Cannot exist without Election; cannot define fraud without Governance rules |
| Boundary Pressure | ✗ WEAK | Could be subsumed into Governance (fraud investigation as Governance execution) or Appeals (fraud review as appeal ground) |
| Organizational Ownership | ✓ MODERATE | Some organizations have separate Fraud Investigation team; others handle through Governance or Appeals |

**Score: 4/6 — WEAK BOUNDED CONTEXT CANDIDATE (might be capability)**

---

### Evidence

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✗ VERY WEAK | "Evidence" is used by Verification, Election, Fraud, Appeals — no unique vocabulary here |
| Unique Decisions | ✗ NO DECISIONS | Evidence does not decide anything; it stores facts. All decisions made by other contexts |
| Unique Consistency Rules | ✓ MODERATE | Maintains: "Data is immutable," "Anonymity preserved," "Audit logs complete" — but these are technical, not domain |
| Independent Evolution | ✗ NO | Evidence structure is entirely dependent on what Election/Verification/Fraud need to store |
| Boundary Pressure | ✗ WEAK | Could be merged into Election (votes as election data), Verification (records for verification), or remain separate |
| Organizational Ownership | ✗ WEAK | No organization assigns "Evidence Committee" — evidence management is operational (like logging) |

**Score: 1/6 — NOT A BOUNDED CONTEXT (Evidence is infrastructure, not domain)**

---

### Verification

| Heuristic | Score | Rationale |
|-----------|-------|-----------|
| Unique Language | ✓ STRONG | "Verify," "Legitimate," "Certify," "Challenge," "Acceptance," "Trust" — specific to verification |
| Unique Decisions | ✓ STRONG | Decides what is legitimate (voter eligible? count correct? signature valid? candidate qualified?) |
| Unique Consistency Rules | ✓ STRONG | Maintains: "Verification rules are consistent," "All verifications use same authority structure," "Legitimacy is documented" |
| Independent Evolution | ✓ STRONG | Verification rules can evolve independently (change what requires verification, who verifies) |
| Boundary Pressure | ✓ VERY STRONG | Merging with Election confuses "how voting works" with "is voting legitimate"; with Membership confuses "who is member" with "is membership legitimate" |
| Organizational Ownership | ✗ WEAK | Real organizations don't have "Verification Committee" — verification happens inside Election, Membership, Governance, Appeals |

**Score: 5/6 — CONTEXT CANDIDATE BUT AMBIGUOUS (might be distributed or cross-cutting)**

---

## Summary: Heuristic Scoring Results

| Element | Score | Status | Classification |
|---------|-------|--------|-----------------|
| Membership | 6/6 | ✓✓✓ STRONG | Bounded Context |
| Election | 6/6 | ✓✓✓ VERY STRONG | Bounded Context |
| Governance | 5/6 | ✓✓ STRONG | Bounded Context |
| Appeals | 6/6 | ✓✓✓ STRONG | Bounded Context |
| Fraud Investigation | 4/6 | ⚠ WEAK | Capability (not context) |
| Evidence | 1/6 | ✗ NO | Infrastructure |
| Verification | 5/6 | ⚠ AMBIGUOUS | Distributed capability OR context |

---

## Candidate Architectural Elements Classification

### BOUNDED CONTEXTS (5 strong candidates)

1. **Membership** — Manages eligibility, lifecycle, status
2. **Election** — Manages voting execution, certification, publication
3. **Governance** — Sets rules, policy, authority precedence
4. **Appeals** — Reviews decisions, grants reversals, ensures due process
5. **Verification** (conditional) — Determines legitimacy of decisions/results

### CAPABILITIES (2 elements)

1. **Fraud Investigation** — Investigates irregularities; likely capability within Governance or Appeals
2. **Evidence** — Stores immutable facts; infrastructure (like logging) — not a domain context

---

## Context Responsibilities (Preliminary Assignment)

### Membership Context

**Owns:**
- User lifecycle (apply, approve, suspend, reinstate)
- Membership status determination
- Eligibility rules (payment status, suspension rules)
- Member records
- Membership communication

**Consumes:**
- Appeal reversals (from Appeals)
- Policy rules (from Governance)
- Evidence of member status (from Evidence/infrastructure)

**Does NOT own:**
- How members vote (Election)
- Whether membership rules are fair (Governance sets that)
- Verification of member legitimacy (Verification)

---

### Election Context

**Owns:**
- Voting execution (when/how voting happens)
- Vote recording (anonymously)
- Vote counting
- Result certification
- Ballot/candidate management
- Voter registration for THIS election
- Voter communication during voting

**Consumes:**
- Membership eligibility rules (from Membership)
- Voting policy (from Governance)
- Verification rules (from Verification)
- Appeal reversals (from Appeals)
- Evidence of counts/votes (from Evidence)

**Does NOT own:**
- Who is eligible to vote (Membership decides)
- Rules about voting (Governance decides)
- Whether election is legitimate (Verification decides)

---

### Governance Context

**Owns:**
- Eligibility policy ("who can vote")
- Voting policy ("how voting works")
- Authority structure (who has what authority)
- Appeal policy (appeals process)
- Fraud investigation policy (what constitutes fraud)
- Constitutional rules (overrides, precedence)
- Mode configuration (Election-Only vs Full Membership)

**Consumes:**
- Evidence of prior decisions (from Evidence)
- Appeal recommendations (from Appeals)

**Does NOT own:**
- Making membership decisions (Membership does)
- Executing elections (Election does)
- Verifying legitimacy (Verification does)
- Investigating specific fraud cases (Fraud Investigation/Appeals does)

---

### Appeals Context

**Owns:**
- Appeal process (intake, review, decision)
- Appeal authority structure
- Reversal decisions (can/cannot overturn)
- Due process enforcement
- Appeal communication

**Consumes:**
- Membership records (from Membership)
- Election records (from Election)
- Appeal policy (from Governance)
- Evidence of decisions being appealed (from Evidence)

**Does NOT own:**
- Setting appeal policy (Governance does)
- Executing elections (Election does)
- Investigating fraud (may use Fraud Investigation capability)

---

### Verification Context (AMBIGUOUS)

**If CONTEXT:**
- Determines what makes a decision legitimate
- Defines verification rules (what authority required, what evidence required)
- Certifies legitimacy of other contexts' decisions

**If DISTRIBUTED CAPABILITY:**
- Verification is owned by each context that makes decisions:
  - Membership verifies member eligibility
  - Election verifies vote counts
  - Governance verifies rule legitimacy
  - Appeals verifies appeal authority legitimacy

**Status:** UNRESOLVED — requires boundary stress testing

---

## Context Relationships

### Upstream Dependencies (Who supplies information)

```
Membership ←─────── Governance (supplies eligibility rules)
           ←─────── Appeals (can reverse membership decisions)
           ←─────── Evidence (supplies member records)

Election   ←─────── Governance (supplies voting rules)
           ←─────── Membership (supplies eligible voters)
           ←─────── Appeals (can reverse election decisions)
           ←─────── Verification (supplies legitimacy rules)
           ←─────── Evidence (supplies vote records)

Governance ←─────── Evidence (supplies decision history)

Appeals    ←─────── Membership (supplies decisions being appealed)
           ←─────── Election (supplies decisions being appealed)
           ←─────── Governance (supplies appeal rules)
           ←─────── Evidence (supplies decision records)

Evidence   (supplies to all contexts)
```

### Downstream Dependencies (Who consumes information)

```
Governance ──────→ Membership (sends eligibility rules)
           ──────→ Election (sends voting rules)
           ──────→ Appeals (sends appeal process)
           ──────→ Fraud Investigation (sends fraud definitions)

Membership ──────→ Election (sends eligible voters)
           ──────→ Verification (supplies status to verify)

Election   ──────→ Appeals (provides results to appeal)
           ──────→ Verification (provides counts to verify)

Appeals    ──────→ Membership (can reverse eligibility)
           ──────→ Election (can reverse results)

Verification ─────→ Membership (certifies legitimacy)
            ─────→ Election (certifies legitimacy)
            ─────→ Governance (certifies rule legitimacy)
            ─────→ Appeals (certifies appeal legitimacy)
```

---

## Ownership Analysis: Discovered Concepts

### Evidence

**Classification:** Infrastructure (not a domain context)  
**Owner:** Infrastructure layer (logging/storage, like code)  
**Owned By:** All contexts (each context contributes to evidence storage)  
**Observation:** Evidence serves all domains but doesn't make decisions itself.

**Implication for architecture:** Evidence should be:
- Shared infrastructure
- Mode-aware (different tables/structures per mode)
- Append-only (immutable)
- Anonymized where required

---

### Verification

**Classification:** AMBIGUOUS  
**Candidates:**
1. Distributed capability (each context verifies its own decisions)
2. Cross-cutting capability (verification logic applies everywhere)
3. Dedicated context (single source of legitimacy rules)

**Evidence for each:**

**Distributed capability (STRONG):**
- Membership verifies member eligibility (is payment current? suspended?)
- Election verifies vote counts (did count match ballots?)
- Governance verifies rule legitimacy (is rule constitutional?)
- Appeals verifies appeal authority legitimacy (can this body overturn decisions?)
- Each has unique verification rules

**Cross-cutting (MODERATE):**
- Verification logic appears in all contexts
- All contexts need to answer "is this decision legitimate?"
- Verification rules can be unified (same Authority + Recognition pattern everywhere)

**Dedicated context (WEAK):**
- Verification doesn't make independent decisions
- Verification is always in service of another context's decision
- No organization has "Verification Committee"

**Current Assessment:** DISTRIBUTED CAPABILITY (likely, but Round 7 must test)

---

### Authority

**Classification:** Unknown (Hypothesis H-B vs H-C unresolved)  
**Candidates:**
1. H-B: Authority Family (different types per context)
2. H-C: Authority Is Cross-Cutting (orthogonal to domains)

**Evidence from Membership:**
- Membership Committee has authority to approve/reject
- Authority is scoped (cannot decide elections)
- Authority can be appealed

**Evidence from Election:**
- Election Committee has authority to certify
- Authority is temporal (expires after results published)
- Authority can be challenged

**Evidence from Governance:**
- Governance has authority to set rules
- Authority can be overridden by Constitution
- Authority is permanent (until changed by higher authority)

**Evidence from Appeals:**
- Appeals has authority to reverse
- Authority is precedence-based (Appeals can override Membership/Election)
- Authority scope is "review prior decisions"

**Current Assessment:** H-B more likely than H-A, but H-C not excluded  
**Classification:** Authority appears to be a cross-cutting concern (like Time, Identity)

---

### Legitimacy

**Classification:** Emergent Property  
**Formula (from Phase 2):** Authority + Recognition + [additional factors] = Legitimacy

**Evidence from contexts:**

**Membership legitimacy requires:**
- Membership Committee authority (established)
- Member recognition (members accept decision)
- Governance rule compliance (decision follows policy)

**Election legitimacy requires:**
- Election Committee authority (established)
- Voter acceptance (voters recognize result)
- Verification completion (counts certified)
- Transparency (in Full Membership mode)

**Appeals legitimacy requires:**
- Appeals authority (established and recognized)
- Due process (followed)
- Governance rule compliance (follows appeal rules)

**Current Assessment:** Legitimacy is EMERGENT (created by combination of Authority, Recognition, Governance compliance, Verification)

---

### Recognition

**Classification:** Partially understood  
**Patterns observed:**

- Recognition is how legitimacy becomes binding (Authority without recognition = claimed, not legitimate)
- Recognition is mode-specific (public in Full Membership, private in Election-Only)
- Recognition can be contested (some voters/members may not recognize authority)
- Recognition feeds back to Authority (unrecognized authority tends to be revoked)

**Current Assessment:** Recognition is SIGNIFICANT but SUBORDINATE to Legitimacy and Authority

---

## Election Mode Analysis

### Election-Only Mode

**Candidate map coherence:**

| Element | Coherent? | Notes |
|---------|-----------|-------|
| Membership | ✓ YES | Decides who can vote; voters accept list as authoritative |
| Election | ✓ YES | Counts votes; publishes results; voters accept certification |
| Governance | ✓ YES | Sets voting rules; organization enforces |
| Appeals | ✓ PARTIAL | Can appeal decisions but appeals are internal (organization decides) |
| Fraud Investigation | ✓ YES | Investigates post-election irregularities |
| Evidence | ✓ YES | Stores voter list, votes, counts, appeals records |
| Verification | ⚠ STRAINED | Verification is limited (organization is both decision-maker and verifier) |

**Mode-specific observations:**
- No public transparency (voters trust organization)
- No challenge mechanism (appeal only if dispute raised)
- Organization is central (single source of authority)
- Verification limited by anonymity (can't verify individual votes)

**Boundary pressure:** MODERATE
- Membership could merge with Election (same authority structure)
- Fraud Investigation could merge with Governance or Appeals
- Verification is weakest (organization can't verify itself)

---

### Full Membership Mode

**Candidate map coherence:**

| Element | Coherent? | Notes |
|---------|-----------|-------|
| Membership | ✓ STRONG | Public list; voters can challenge; Membership Committee authority clear |
| Election | ✓ STRONG | Voting execution separated from eligibility; clear responsibilities |
| Governance | ✓ STRONG | Sets rules; policy explicitly enforced by operational contexts |
| Appeals | ✓ STRONG | Clear appeal process; Governance authority structure established |
| Fraud Investigation | ✓ YES | Can investigate; recommendations go to Governance/Appeals |
| Evidence | ✓ YES | Supports public verification and transparency |
| Verification | ✓ STRONGER | Verification distributed; voters can self-verify (aggregate) |

**Mode-specific observations:**
- Public transparency (lists, counts published)
- Challenge mechanism embedded (voters are verifiers too)
- Authority distributed (Membership, Election, Governance, Appeals all have distinct roles)
- Recognition is significant (voter recognition of authorities matters)

**Boundary pressure:** STRONG
- Clear separation between policy (Governance) and execution (Membership, Election, Appeals)
- Context boundaries create natural check-and-balance
- Authority structure supported by context structure

---

## Boundary Stress Testing

### Stress Test 1: Can Membership exist independently?

**Scenario:** Just Membership, no Election, Governance, or Appeals.

**Result:** ✗ BREAKS
- Without Governance, there are no eligibility rules to enforce
- Without Appeals, there's no way to challenge decisions
- Membership requires Governance rules + Appeals capability

**Implication:** Membership is NOT independent; it's dependent on Governance (rules) and Appeals (reversals)

---

### Stress Test 2: Can Election exist independently?

**Scenario:** Just Election, no Membership, Governance, or Appeals.

**Result:** ✗ BREAKS
- Without Membership, there's no voter list
- Without Governance, there are no voting rules
- Without Appeals, there's no way to challenge results

**Implication:** Election is NOT independent; it's dependent on Membership + Governance + Appeals

---

### Stress Test 3: Can Verification exist as a standalone context?

**Scenario:** Verification as a context that verifies other contexts' decisions.

**Result:** ⚠ WORKS, BUT AWKWARD
- Verification could review Membership decisions (is member eligible?)
- Verification could review Election decisions (is count correct?)
- But Verification wouldn't make any decisions itself
- Verification would just report "legitimate" or "not legitimate"
- Other contexts would need to act on Verification findings

**Alternative:** ⚠ BETTER AS DISTRIBUTED CAPABILITY
- Each context verifies its own decisions
- Verification rules (what needs verification, who verifies) live in each context
- Verification requirements defined by Governance

**Implication:** Verification as a context is POSSIBLE but WEAK. Better as distributed capability.

---

### Stress Test 4: Would merging Membership + Election work?

**Scenario:** Single "Voter Management" context for both eligibility and voting.

**Result:** ✗ BREAKS
- Eligibility changes would require re-voting
- Voting execution could be delayed by membership disputes
- Would conflate "who can participate" with "how participation works"
- Fraud in one would contaminate the other

**Implication:** Membership and Election MUST remain separate

---

### Stress Test 5: Would merging Governance + Appeals work?

**Scenario:** Single "Policy" context for both rules and reversals.

**Result:** ✗ BREAKS
- Appeals would lose independence (policy-maker reviews own decisions)
- No true separation of concerns
- Governance could be biased toward its own policies
- Would violate "appeals authority must be independent"

**Implication:** Governance and Appeals MUST remain separate

---

### Stress Test 6: Where does Fraud Investigation belong?

**Scenario 1:** As standalone context.
**Result:** ⚠ WEAK
- Fraud investigation has no decisions of its own
- Recommendations go to Governance or Appeals
- No unique language or consistency rules

**Scenario 2:** As capability within Governance.
**Result:** ⚠ ADEQUATE
- Governance defines what fraud is
- Governance directs investigation
- Investigation results inform Governance decisions

**Scenario 3:** As capability within Appeals.
**Result:** ⚠ ADEQUATE
- Fraud investigation can support appeals
- Appeal authority can request investigation
- Investigation supports appeal decisions

**Implication:** Fraud Investigation should be CAPABILITY, not context. Assign to Governance or Appeals depending on who acts on findings.

---

### Stress Test 7: Can Evidence be merged into Election?

**Scenario:** Evidence storage is internal to Election context.

**Result:** ⚠ BREAKS FOR FULL MEMBERSHIP
- Election-Only mode: could work (Organization controls all evidence)
- Full Membership mode: BREAKS
  - Membership needs its own records (member list, status)
  - Governance needs to store policies
  - Appeals needs decision history
  - Can't have all evidence controlled by Election

**Implication:** Evidence MUST be shared infrastructure (not owned by any context)

---

### Stress Test 8: Authority and Legitimacy across contexts

**Scenario:** Authority is H-C (cross-cutting).

**Result:** ✓ WORKS
- Each context has its own authority (Membership Committee, Election Committee, etc.)
- Authority behaves the same everywhere (scoped, temporal, challengeable)
- Governance defines precedence when authorities conflict
- Recognition happens independent of authority type

**Scenario:** Legitimacy is emergent from Authority + Recognition + Governance compliance.

**Result:** ✓ WORKS
- Membership decision is legitimate if: Committee has authority + Members recognize it + Follows rules
- Election result is legitimate if: Committee has authority + Voters recognize it + Verified + Follows rules
- Appeals reversal is legitimate if: Appeals authority recognized + Due process followed + Follows rules

**Implication:** Authority is likely CROSS-CUTTING; Legitimacy is EMERGENT

---

## Alternative Context Maps

### MAP A: Maximum Separation (7 contexts)

```
┌─────────────────────────────────────────────────┐
│                  INFRASTRUCTURE                  │
│  (Evidence storage, Event publishing, Logging)   │
└─────────────────────────────────────────────────┘
                         ▲
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ Membership  │  │  Election   │  │ Governance  │
│ (eligibility)  │ (voting)    │  │ (policy)    │
└─────────────┘  └─────────────┘  └─────────────┘
        │                │                │
        │                │                │
        └────────────────┼────────────────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│   Appeals   │  │Verification │  │    Fraud    │
│(reversals)  │  │(legitimacy) │  │(investigation)
└─────────────┘  └─────────────┘  └─────────────┘
```

**Pros:**
- Maximum separation of concerns
- Each context has clear ownership
- Boundaries are clear

**Cons:**
- Verification as a context is awkward (doesn't make decisions)
- Fraud Investigation as a context is weak (no independent decisions)
- Too many contexts creates coordination overhead
- "What is legitimate?" question gets fragmented

**Trade-offs:**
- Clarity vs. Complexity
- Separation vs. Coordination

---

### MAP B: Evidence + Verification as Merged Capability

```
┌─────────────────────────────────────────────────┐
│                  INFRASTRUCTURE                  │
│  (Evidence storage, Verification logic)          │
│  (Event publishing, Logging)                     │
└─────────────────────────────────────────────────┘
                         ▲
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ Membership  │  │  Election   │  │ Governance  │
└─────────────┘  └─────────────┘  └─────────────┘
        │                │                │
        └────────────────┼────────────────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐
│   Appeals   │  │    Fraud    │
│(reversals)  │  │(investigation)
└─────────────┘  └─────────────┘
```

**Pros:**
- Reduces context count to 5 (Membership, Election, Governance, Appeals, Fraud)
- Verification lives in infrastructure (where it actually belongs — it's a cross-cutting rule)
- Evidence is naturally infrastructure
- Simpler architecture

**Cons:**
- Infrastructure needs to know about all contexts' verification rules
- Verification logic becomes infrastructure concern (architectural risk)
- Fraud Investigation still has weak status

**Trade-offs:**
- Simpler topology
- But infrastructure becomes more complex

---

### MAP C: Distributed Verification, Fraud in Appeals

```
┌─────────────────────────────────────────────────┐
│                  INFRASTRUCTURE                  │
│  (Evidence storage, Event publishing, Logging)   │
└─────────────────────────────────────────────────┘
                         ▲
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ Membership  │  │  Election   │  │ Governance  │
│+Verification│  │+Verification│  │ (policy)    │
│capability   │  │capability   │  │             │
└─────────────┘  └─────────────┘  └─────────────┘
        │                │                │
        └────────────────┼────────────────┘
                         │
        ┌────────────────┘
        │
        ▼
┌─────────────────────┐
│   Appeals           │
│ (reversals)         │
│ +Fraud Investigation│
│ capability          │
└─────────────────────┘
```

**Pros:**
- Only 4 core contexts (Membership, Election, Governance, Appeals)
- Verification distributed to where decisions are made (each context verifies its own)
- Fraud investigation has natural home (Appeals investigates alleged fraud)
- Clean architecture
- Governance defines what needs verification (contracts with each context)

**Cons:**
- Verification logic duplicated across contexts (but Governance provides templates)
- Appeals becomes heavier (reversals + fraud investigation)
- Requires clear Verification contracts between Governance and contexts

**Trade-offs:**
- Clear separation
- Distributed responsibility
- Context autonomy
- Requires governance discipline (contracts)

---

## Comparative Analysis: Which map survives best?

### Election-Only Mode Testing

**Map A (7 contexts):** ✓ WORKS
- Verification has limited role (organization verifies itself)
- Fraud investigation ad-hoc
- All boundaries hold

**Map B (5 contexts + infrastructure):** ✓ WORKS
- Verification in infrastructure
- Less explicit about fraud handling
- Simpler topology

**Map C (4 contexts + distributed verification):** ✓ WORKS BEST
- Each context owns its verification
- Appeals clearly handles disputes
- Cleanest for this mode

---

### Full Membership Mode Testing

**Map A (7 contexts):** ✓ WORKS
- Verification has real role (determines legitimacy)
- Fraud investigation can investigate
- All boundaries hold

**Map B (5 contexts + infrastructure):** ⚠ WORKS but STRAINED
- Verification in infrastructure must know about distributed transparency requirements
- Fraud investigation less clear
- Infrastructure becomes complex

**Map C (4 contexts + distributed verification):** ✓✓ WORKS BEST
- Each context owns verification rules
- Governance provides verification requirements
- Appeals + Fraud investigates and recommends reversals
- Transparency naturally emerges
- Recognition naturally embedded

---

### Boundary Pressure Testing

**Map A:** ✗ PRESSURE TO MERGE
- Verification context doesn't make decisions (pressure to merge or remove)
- Fraud Investigation context is weak (pressure to move into Governance/Appeals)
- Would reduce to Map C naturally

**Map B:** ⚠ PRESSURE TO CLARIFY
- Infrastructure becomes complex (pressure to document verification contracts)
- Which context owns verification decisions? (ambiguous)
- Would drift toward Map C for clarity

**Map C:** ✓ MINIMAL PRESSURE
- Each context owns its verification (clear)
- Appeals naturally handles fraud (sensible)
- Governance defines what verification is needed (clean contract)
- No pressure to reorganize

---

## Comparative Assessment: Which Map Survives Best?

**No map has been selected.** This section documents relative strengths and weaknesses for governance review.

### Comparison Summary

| Criterion | Map A (7 contexts) | Map B (5 + infrastructure) | Map C (4 + distributed) |
|-----------|-------------------|---------------------------|------------------------|
| Election-Only mode | ✓ works | ✓ works | ✓✓ works best |
| Full Membership mode | ✓ works | ⚠ strained | ✓✓ works best |
| Boundary pressure | ✗ high | ⚠ moderate | ✓ minimal |
| Verification clarity | ✓ explicit | ⚠ infrastructure | ✓ distributed |
| Context count | ⚠ 7 (complex) | ⚠ 5 + infrastructure | ✓ 4 (simpler) |
| Governance dependency | ✓ low | ⚠ moderate | ✓ clear contracts |
| Fraud Investigation placement | ⚠ standalone | ⚠ ambiguous | ✓ in Appeals |
| Recognition support | ✓ similar | ✓ similar | ✓ similar |

**Observation:** Map C exhibits fewest contradictions and minimal boundary pressure across all tested scenarios.

**Status:** Remains a candidate. Not approved. Requires governance review.

---

### Alternative Map Visual: Map C Structure

```
┌──────────────────────────────────────────────────────────┐
│                    GOVERNANCE                            │
│  Policy Layer                                            │
│  - Sets eligibility rules                               │
│  - Sets voting rules                                    │
│  - Defines verification requirements                   │
│  - Defines appeal process                              │
│  - Defines fraud investigation policy                  │
│  - Establishes authority precedence                    │
└──────────────────────────────────────────────────────────┘
        │                    │                    │
        │ Policy Contracts   │ Policy Contracts   │ Policy Contracts
        │                    │                    │
        ▼                    ▼                    ▼
┌─────────────────┐  ┌──────────────┐  ┌──────────────┐
│  MEMBERSHIP     │  │   ELECTION   │  │    APPEALS   │
│                 │  │              │  │              │
│ Core Logic:     │  │ Core Logic:  │  │ Core Logic:  │
│ - Approve/reject│  │ - Conduct    │  │ - Review     │
│ - Manage status │  │   voting     │  │ - Reverse    │
│ - Track member  │  │ - Certify    │  │ - Investigate│
│   history       │  │   results    │  │   fraud      │
│                 │  │ - Publish    │  │ - Due process│
│ Verification:   │  │   results    │  │              │
│ - Is member     │  │              │  │ Verification:│
│   eligible?     │  │ Verification:│  │ - Is appeal  │
│ - Evidence:     │  │ - Is count   │  │   justified? │
│   member records│  │   correct?   │  │ - Evidence:  │
│                 │  │ - Evidence:  │  │   decision   │
│                 │  │   vote       │  │   records    │
│                 │  │   records    │  │              │
└─────────────────┘  └──────────────┘  └──────────────┘
        ▲                    ▲                    ▲
        │ Query eligible     │ Query rules       │ Query policy
        │ voters             │ & verify counts   │ & investigate
        │                    │                   │
        └────────────────────┴───────────────────┘
                    │
                    ▼
        ┌──────────────────────┐
        │   INFRASTRUCTURE     │
        │ (Evidence, Events)   │
        │ - Voter records      │
        │ - Election records   │
        │ - Decision history   │
        │ - Audit logs         │
        │ - Appeal records     │
        │ - Policy versions    │
        └──────────────────────┘
```

---

## Authority Assessment

### Observed Candidate Pattern: Authority Behavior Within Maps

**Within Map C, Authority exhibits this pattern:**

| Context | Authority Behavior |
|---------|------------------|
| Membership | Committee authority; scoped to eligibility; temporal (per election cycle); can be appealed |
| Election | Committee authority; scoped to voting execution; temporal (during voting); can be appealed |
| Appeals | Higher authority; scoped to reversals; procedural authority; can be appealed to Governance |
| Governance | Constitutional authority; scoped to policy; semi-permanent (until changed); limited appeal |

**Common Characteristics Observed:**
- Authority is SCOPED (each context's authority is limited)
- Authority is TEMPORAL (starts/expires; can be revoked)
- Authority is CHALLENGEABLE (can be appealed/reviewed)
- Authority REQUIRES RECOGNITION (others must accept it)
- Authority DEPENDS ON GOVERNANCE (policy defines authority limits)

**Observation:** If this pattern holds, Authority might behave similarly in all contexts (suggesting H-C: cross-cutting concern).

**Alternative observation:** Authority might be context-specific (suggesting H-B: Authority Family), but this pattern would need to be validated by deeper investigation.

**Status:** This is a candidate observation from Map C. Map A and Map B may reveal different authority patterns. Requires governance review to determine which hypothesis is correct.

---

## Legitimacy Assessment

### Observed Candidate Pattern: Legitimacy Within Maps

**Within Map C, legitimacy appears to require:**
Authority + Recognition + Governance Compliance + Verification

**Observation from each context:**

**Membership eligibility decision would be legitimate if:**
- Membership Committee has authority
- Members recognize the Committee
- Decision follows Governance rules
- Decision can be verified (transparent to Appeals)

**Election results would be legitimate if:**
- Election Committee has authority
- Voters recognize the results
- Results follow Governance rules
- Results are verified (counts certified)

**Appeal reversal would be legitimate if:**
- Appeals has authority
- Decision-makers recognize Appeals authority
- Reversal follows Governance rules
- Reversal is investigated (grounds clear)

**Candidate hypothesis:** Legitimacy is **emergent** (produced by intersection of Authority, Recognition, Governance rules, Verification).

**Alternative possibility:** Legitimacy might be foundational (not emergent), with these factors supporting it rather than creating it.

**Implication (if Map C hypothesis correct):** Systems would need to enable legitimacy emergence (transparency, appeals, verification) rather than guarantee it by design.

**Status:** This pattern holds within Map C. Maps A and B may reveal different legitimacy structures. Requires governance review to validate hypothesis.

---

## Recognition Assessment

### Observed Candidate Pattern: Recognition Within Maps

**Within Map C, Recognition exhibits these patterns:**

1. **Recognition appears to enable Authority** — Authority without recognition appears hollow
2. **Recognition is distributed** — Not everyone recognizes all authorities equally
3. **Recognition appears mode-dependent** — Public in Full Membership, implicit in Election-Only
4. **Recognition appears contestable** — Can be challenged, revoked, restored
5. **Recognition appears related to legitimacy** — Appears to be part of legitimacy formula

**Where Recognition appears within each context:**

| Context | Recognition Pattern |
|---------|-------------------|
| Membership | Members accept (or reject) Membership Committee authority |
| Election | Voters accept (or reject) Election Committee authority and results |
| Appeals | Decision-makers accept (or reject) Appeals authority |
| Governance | Contexts accept (or reject) Governance rules as legitimate |

**Candidate observation:** Recognition might be **SIGNIFICANT** but **SECONDARY TO AUTHORITY**.
- Hypothesis: Authority without recognition = claimed, not legitimate
- Hypothesis: Recognition without authority = acceptance, but not legitimacy

**Alternative observation:** Recognition might be equally important as Authority (not secondary).

**Implication (if Map C hypothesis correct):** Architecture would need to enable recognition (transparency, communication, process fairness) rather than force it.

**Status:** Recognition patterns observed within Map C. Requires governance review to validate observations across all maps.

---

## Contradictions With Earlier Discovery

Round 7 analysis reveals tensions with conclusions from Phase 2. These are NOT resolved; they are flagged for governance review.

### Contradiction 1: Evidence as Infrastructure vs Constitutional Pillar

**Phase 2 Finding:**
```
Evidence and Verification are co-equal constitutional pillars.
Neither is foundational to the other.
```

**Map C Treatment:**
```
Evidence is infrastructure (like logging).
Verification is capability/context.
This creates a hierarchy.
```

**Tension:** If Evidence is truly a constitutional pillar (equal to Verification), can it be infrastructure? Or does infrastructure status contradict the constitutional pillar finding?

**Status:** UNRESOLVED. Requires governance review before architectural decision.

---

### Contradiction 2: Verification as Capability vs Constitutional Role

**Phase 2 Finding:**
```
Verification is a constitutional pillar.
```

**Map C Treatment:**
```
Verification is distributed capability within contexts.
```

**Tension:** If Verification is constitutional, can it be a mere capability distributed to other contexts? Or is distributed capability consistent with constitutional significance?

**Status:** UNRESOLVED. Requires governance review before architectural decision.

---

### Contradiction 3: Legitimacy as Emergent vs Foundational

**Map C Observation:**
```
Legitimacy emerges from Authority + Recognition + Governance + Verification.
```

**Phase 2 Legitimacy Investigation:**
```
Legitimacy is emergent property / temporal concept.
Legitimacy requires Authority + Recognition + [factors].
```

**Tension:** No contradiction here; Map C is consistent. BUT: Alternative maps might treat Legitimacy as foundational. Round 7 did not test this alternative.

**Status:** NOTED. Maps A and B should be evaluated for alternative Legitimacy treatments.

---

### Contradiction 4: Authority Cross-Cutting vs Family vs Unified

**Phase 2 Finding:**
```
H-A (Single Authority): Significantly weakens
H-B (Authority Family): Remains viable
H-C (Authority Cross-Cutting): Remains viable
Hypothesis unresolved.
```

**Map C Treatment:**
```
Authority exhibits cross-cutting characteristics.
Suggests H-C as most likely.
```

**Tension:** Map C analysis suggests H-C, but Phase 2 governance explicitly left this unresolved. By designing Map C around H-C assumption, Round 7 has made a governance decision, not an exploratory observation.

**Status:** CRITICAL ISSUE. Round 7 should have evaluated all three maps as equally viable inputs to governance review, not selected one based on Authority hypothesis.

---

### Contradiction 5: Recognition as Significant vs Subordinate

**Phase 2 Finding:**
```
Recognition was observed as recurring pattern.
Recognition was NOT deeply investigated.
Recognition significance UNRESOLVED.
```

**Map C Treatment:**
```
Recognition is significant but secondary to Authority.
```

**Tension:** By assigning Recognition a role in Map C, Round 7 has made a judgment that Phase 2 explicitly deferred.

**Status:** UNRESOLVED. Requires governance review.

---

## Summary: What These Contradictions Mean

**The contradictions do NOT invalidate Round 7 analysis.**

**They reveal that Round 7 has transitioned from exploration to hypothesis-testing.**

Round 7 examined whether the discovered concepts could be organized into coherent contexts. In doing so, Round 7 implicitly tested hypotheses:

- Authority IS cross-cutting (tested via Map C)
- Legitimacy IS emergent (tested via Map C)
- Recognition IS significant but secondary (tested via Map C)
- Evidence CAN be infrastructure (tested via Map C)
- Verification CAN be distributed (tested via Map C)

**These are legitimate questions for Round 7 to test.**

**But they should have been explicit hypothesis tests, not implicit in "selected map" language.**

---



**All provisional; to be validated in governance review.**

1. **Authority is cross-cutting** — All contexts use same authority pattern; no context "owns" authority
2. **Verification is distributed** — Each context verifies its own decisions using Governance-defined rules
3. **Legitimacy is emergent** — No single system component produces legitimacy; emerges from context interactions
4. **Recognition is orthogonal to architecture** — Cannot be designed in; must enable conditions for emergence
5. **Fraud Investigation belongs in Appeals** — Natural place for dispute investigation; supports appeal decisions
6. **Evidence is infrastructure** — Shared, not owned by any context; mode-aware
7. **Governance is central policy layer** — All other contexts receive policy contracts from Governance
8. **Boundaries remain stable across modes** — Election-Only and Full Membership use same contexts; policy changes, not structure

---

## Unknowns

1. **Can Recognition be designed, or only enabled?** — Is recognition emergent or architectural?
2. **What is minimum Authority required?** — Can default/implicit authority exist, or must all authority be explicit?
3. **Is Governance truly independent, or does it depend on Appeals oversight?** — Can Governance override Appeals?
4. **How much Fraud Investigation belongs in Appeals vs. Governance?** — Should Appeals investigate, or only review?
5. **Does Evidence storage affect boundary coherence?** — Where should evidence physically live vs. logically belong?
6. **Can Membership+Election share voter registration data, or must they remain separate?** — Data vs. logic separation?
7. **How does authorization precedence work when authorities disagree?** — Governance decides, or built-in rules?
8. **Does Full Membership mode transparency requirement force any context boundary changes?** — Publication vs. storage?

---

## Risks

**If this map is wrong:**

1. **Risk: Verification remains ambiguous** — Architecture doesn't clarify what "legitimate" means in each context
   - Mitigation: Explicit governance contracts defining verification per context

2. **Risk: Authority is NOT cross-cutting** — If Authority is context-specific, contexts become incoherent
   - Mitigation: Authority investigation continues; alternatives prepared

3. **Risk: Recognition cannot be enabled by architecture** — If recognition is purely social/political, design is incomplete
   - Mitigation: Document what architecture ENABLES (transparency, process) vs. what it CANNOT CONTROL (actual recognition)

4. **Risk: Appeals becomes too heavy** — If Appeals handles reversals AND fraud investigation, it becomes a bottleneck
   - Mitigation: Split fraud investigation path; Appeals investigates grounds, Governance decides policy implications

5. **Risk: Fraud Investigation decisions conflict with Appeals decisions** — Two bodies investigating same issue
   - Mitigation: Clear precedence; Governance defines which body takes action

6. **Risk: Distributed verification creates inconsistency** — Each context verifies differently
   - Mitigation: Governance provides verification templates; each context applies same pattern

7. **Risk: Evidence as shared infrastructure creates performance bottleneck** — All contexts write to same storage
   - Mitigation: Evidence layer is designed for high throughput; mode-specific tables

8. **Risk: Governance is bottleneck** — All policy decisions flow through one body
   - Mitigation: Governance delegates execution; contexts have authority to implement policy

---

## Round 7 Observations

Round 7 has completed exploratory context mapping using three candidate maps (A, B, C).

### What Was Tested

**Heuristic Scoring:** All 7 elements evaluated against 6 context identification criteria.

**Context Responsibilities:** Preliminary assignment of concerns within each candidate map.

**Election Mode Analysis:** Boundary coherence tested against Election-Only mode (5 elements require mode-specific evaluation).

**Boundary Stress Testing:** 8 stress scenarios attempted against proposed boundaries.

**Alternative Maps:** Three distinct architectural approaches evaluated for:
- Mode coherence (Election-Only, Full Membership)
- Boundary pressure (natural vs forced)
- Context autonomy (independent evolution possible?)
- Governance dependency (clear contracts?)

**Concept Ownership:** Evidence, Verification, Authority, Legitimacy, Recognition mapped to candidate contexts/capabilities.

### What Was NOT Decided

**No context map has been selected.**

Maps A, B, and C remain candidates. Each exhibits different:
- Strengths (fewer boundary conflicts, clearer ownership)
- Weaknesses (more complex, infrastructure confusion)
- Implicit hypothesis tests (about Authority, Legitimacy, Recognition, Evidence role)

**All boundaries remain revisable.**

**All architectural classifications remain provisional.**

### Key Unresolved Questions

1. Is Authority H-B (Family) or H-C (Cross-Cutting)? Maps suggest H-C but do not prove it.
2. Is Evidence a constitutional pillar or infrastructure? Map C treats as infrastructure; contradicts Phase 2 finding.
3. Is Legitimacy truly emergent, or foundational? Map C assumes emergent; alternatives not fully tested.
4. Is Recognition architecturally designable or purely social? All maps assume designable; not tested otherwise.
5. Should Verification be distributed or centralized? Map C assumes distributed; alternatives exist.

### Contradictions Requiring Governance Review

See "Contradictions With Earlier Discovery" section (lines 754–829).

### Status

**Round 7 Candidate Context Map v1 is EXPLORATORY.**

It is NOT approved architecture.

It is input to governance review.

Governance must decide:
- Are these observations valid?
- Do these contradictions matter?
- Which map deserves further investigation?
- Should Alternative Maps A and B be explored more deeply?
- Are Phase 2 and Round 7 findings coherent, or do they reveal design problems?

**Next:** Governance review (ARB) determines whether Round 7 findings warrant further investigation or Round 8 design work.

---

**Status:** Round 7 Candidate Context Mapping complete — exploratory, not approved.  
**Authority:** Phase 2 Governance Gate (conditional authorization, 7 constraints, all preserved).  
**Disposition:** Ready for governance review. No selection has been made. No decisions finalized.
