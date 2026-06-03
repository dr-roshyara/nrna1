# Round 6D — Verification Stress Test

**Date:** 2026-06-03  
**Objective:** Test hypotheses from Round 6C.6 against election scenarios; identify which hypotheses hold and which break  
**Method:** Run concrete election scenarios through Verification decision model; look for contradictions

---

## Methodology

For each scenario:

1. **Scenario description** — What happens in this election?
2. **Hypothesis predictions** — What should happen if hypotheses are correct?
3. **Stress test** — Does the model handle this? Do contradictions emerge?
4. **Results** — Which hypotheses hold? Which crack?
5. **Architectural implication** — What does this reveal about the domain?

**Success = Hypotheses survive stress testing**  
**Failure = Find contradictions that require rethinking domain boundaries**

---

## Test Scenario 1: Simple Election (Election-Only Mode)

**Setup:**
- Organization conducts vote on single question
- 100 voters, all eligible
- Paper ballots, manual count
- Two independent observers verify count

**Test Case 1A: Normal path**

**Scenario:** Election occurs, votes counted, observers agree.

**Hypothesis predictions:**

| Hypothesis | Prediction |
|-----------|-----------|
| **Governance owns cert authority** | Governance says "both observers must agree" → model decides both agree → certified ✓ |
| **Verification owns proof sufficiency** | Verification computes: votes + observer agreement = sufficient proof → yes ✓ |
| **Evidence provides audit trail** | Evidence stores: ballot counts, observer names, signatures → yes ✓ |
| **Two contexts can coexist** | Governance decides standard; Verification proves it; Evidence records it → yes ✓ |

**Result:** ✅ Hypotheses hold for happy path.

---

**Test Case 1B: Observer disagreement**

**Scenario:** Observer A counts 63 votes for Option A. Observer B counts 61 votes for Option A. Recount needed.

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Governance owns cert authority** | Governance rule: "If observers disagree, recount required" → model follows rule → recount ✓ | ✅ YES |
| **Verification owns proof sufficiency** | Verification identifies: counts don't match; 2-vote discrepancy ≤ 0.5% margin → sufficient for recount → yes ✓ | ✅ YES |
| **Evidence provides audit trail** | Evidence stores: both counts, recount result, final agreement → yes ✓ | ✅ YES |
| **Governance handles failure response** | Governance rule: "Recount required; both must re-verify" → model executes rule ✓ | ✅ YES |

**Result:** ✅ Hypotheses hold under disagreement scenario.

---

**Test Case 1C: Observer A refuses to certify**

**Scenario:** Observer A says "I will not certify; something is wrong." Observer B says "Everything looks correct." Governance rule requires BOTH to certify.

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Governance owns cert decision** | Governance rule: "Both must certify or election is disputed" → model blocks certification ✓ | ✅ YES |
| **Verification owns proof decision** | Verification computed: counts are correct. But authority won't certify. → Authority decision ≠ verification proof. Clear separation? | ⚠️ MAYBE |
| **Who breaks tie?** | Governance said "both must agree." Observer disagreed. Who overrides Governance rule? | ❓ UNCLEAR |

**Hypothesis crack:** This reveals a hidden decision:

**Decision: Authority Override**

```
Who decides:
"Observer A's refusal can be overridden"?
```

This is not in the hypotheses. It's a new decision that Governance or something higher must own.

**Result:** ⚠️ Hypothesis holds but reveals gap: Authority refusal handling is undefined.

---

## Test Scenario 2: Membership Election (Full Membership Mode)

**Setup:**
- Organization with 200 members votes on leadership
- Some members are: current members, suspended members, ex-members, applicants
- Membership authority says: only current members can vote
- Authority A (HR) validates membership

**Test Case 2A: Normal path - all eligible**

**Scenario:** 150 current members vote. No disputes.

**Hypothesis predictions:**

| Hypothesis | Prediction |
|-----------|-----------|
| **Governance defines eligibility** | "Current members only" → rule set ✓ |
| **Membership supplies facts** | List of 200 members with status → supplied ✓ |
| **Verification proves eligibility** | Verify: each voter is in "current" list → yes for all ✓ |
| **Evaluation interprets authority** | Authority A approved; evaluation trusts approval ✓ |
| **Evidence records authority** | Evidence stores: Authority A approved on date X ✓ |

**Result:** ✅ Hypotheses hold.

---

**Test Case 2B: Authority disagreement**

**Scenario:** 
- Voter claims: "I was promoted to current member last week"
- Membership list says: "Still in applicant status"
- Authority A (HR) says: "We haven't processed promotion yet"
- Authority B (Membership Committee) says: "Promotion was approved; should be current"

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Governance handles authority conflict** | Governance rule: "HR is authority of record" → model accepts HR decision: applicant ✓ | ✅ YES |
| **Verification proves eligibility** | Verification: check status (applicant) against rule (current only) → ineligible ✓ | ✅ YES |
| **Can Evaluation override Verification?** | Evaluation says: "Authority B approved; should be current." But Verification says: "Status is applicant; ineligible." → Conflict? | ⚠️ CONFLICT |

**Hypothesis crack:** Clear separation between Verification and Evaluation breaks:

**Who decides:** "Is Authority B's approval sufficient, or does Authority A's record take precedence?"

This is a Governance decision, but it's not listed in the hypotheses.

**New decision needed:** Authority Precedence (beyond simple conflict resolution).

**Result:** ⚠️ Hypothesis holds but reveals: Authority precedence is more complex than hypothesized.

---

**Test Case 2C: Membership revocation post-vote**

**Scenario:**
- Voter X votes successfully (eligible at vote time)
- Next day: Membership authority revokes X's membership
- Governance asks: "Does revocation invalidate X's vote?"

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Legitimacy is point-in-time?** | Hypothesis assumes: eligibility determined at vote time, frozen after ✓ | ✅ YES |
| **Can membership change invalidate votes?** | Governance rule not stated: can revocation invalidate? | ❓ UNKNOWN |
| **Verification: what is scope?** | Does Verification verify "was eligible at vote time" or "is currently eligible"? → Different verification. | ⚠️ DIFFERENT SCOPE |

**Hypothesis crack:** The hypotheses assume point-in-time legitimacy, but Governance hasn't decided: can post-vote events invalidate participation?

**New decision needed:** Legitimacy Lifecycle (temporal scope of eligibility).

**Result:** ⚠️ Hypothesis holds for vote time; breaks for post-vote changes.

---

## Test Scenario 3: Fraud Detection (Edge Case)

**Setup:**
- 200 member election
- Auditor discovers: Voter A also cast vote as Voter B (duplicate vote, different identity)
- Evidence shows: same device, 2 minutes apart, different authentication

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Verification proves legitimacy** | Verification computed: X is legitimate. Evidence shows: X also voted as Y. → Was X legitimate if X is fraud? | ⚠️ RETROACTIVE |
| **Evidence supplies proof material** | Evidence provides: device match, timing, IP match → fraud detected ✓ | ✅ YES |
| **Can Verification invalidate past verification?** | If fraud discovered post-count, can Verification revoke earlier legitimacy? → Not addressed in hypotheses | ❓ UNKNOWN |
| **Governance decides fraud response** | Governance rule: "Fraud invalidates both votes" → model voids both votes ✓ | ✅ YES |
| **Can result be rescertified?** | After removing fraudulent votes, observer must re-certify → who decides if recertification is required? | ❓ UNCLEAR |

**Hypothesis crack:** Fraud scenario reveals: Verification assumes evidence is clean. If Evidence reveals fraud post-verification, who decides invalidation?

**New decision needed:** Fraud Investigation Authority (who investigates? who decides consequences?).

**Result:** ⚠️ Hypothesis breaks under fraud detection.

---

## Test Scenario 4: Multi-Authority Election (Complex Membership)

**Setup:**
- 300 member organization
- Membership across 3 regions (A, B, C)
- Regional rules differ:
  - Region A: Authority A1 approves (any current member)
  - Region B: Authority B1 + Authority B2 must both approve
  - Region C: Authority C1 OR Authority C2 OR Authority C3 (any one can approve)

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Single governance rule works?** | Governance rule: "current members vote" is simple. But approval differs by region. → Model becomes region-aware? | ⚠️ COMPLEXITY |
| **Verification can handle variable rules?** | Verification for Region A: check against A1. Region B: check against both B1 + B2. Region C: check against any of C1/C2/C3. → Three different verification algorithms? | ⚠️ MULTIPLE PATHS |
| **Authority precedence applies uniformly?** | In Region A, A1 is sovereign. In Region B, both are. In Region C, any one is. → Precedence rule varies by context. | ⚠️ BREAKS UNIFORMITY |
| **Single Evidence model?** | Evidence records which authority approved. But approval meaning differs by region. → Context-dependent semantics. | ⚠️ SEMANTIC VARIANCE |

**Hypothesis crack:** Hypotheses assume uniform rules. Multi-region authority variation breaks the model.

**New decision needed:** Regional Authority Policies (governance varies by context).

**Result:** ⚠️ Hypothesis assumes centralized Governance; breaks under distributed authority.

---

## Test Scenario 5: Appeal Process (Governance vs. Verification)

**Setup:**
- Voter denied eligibility by Authority A
- Voter appeals: "Authority A made an error"
- Appeal authority (Governance-appointed) reviews

**Hypothesis stress test:**

| Hypothesis | Stress | Holds? |
|-----------|--------|--------|
| **Governance owns appeal decision** | Appeal authority is appointed by Governance → Governance owns appeals ✓ | ✅ YES |
| **Verification's proof is challenged** | Original Verification: "Status = ineligible per Authority A" → Appeal asks: "Is Authority A's decision valid?" → Verification doesn't own authority validity! | ⚠️ VERIFICATION CHALLENGED |
| **Can Verification be overridden?** | Appeal authority says: "Authority A was wrong; voter is eligible." → Verification was technically correct (per A), but A was wrong. → Who decides authority is wrong? | ❓ UNCLEAR |
| **Is there a "higher Verification"?** | Appeal authority conducts independent verification → two Verification processes exist? Or is appeal authority a Governance decision? | ⚠️ ROLE AMBIGUITY |

**Hypothesis crack:** Appeal process creates a second-order verification (appeal authority re-verifies). This is not in the hypotheses.

**New decision needed:** Appeal Authority (is this Governance, or a separate Verification-like context?).

**Result:** ⚠️ Hypothesis holds but reveals: Appeals create new decision level not accounted for.

---

## Summary of Stress Test Results

| Scenario | Hypothesis Holds? | New Decisions Revealed |
|----------|---|---|
| **1A: Happy path** | ✅ YES | None |
| **1B: Observer disagreement** | ✅ YES | None |
| **1C: Observer refuses** | ⚠️ MOSTLY | Authority Override |
| **2A: Membership normal** | ✅ YES | None |
| **2B: Authority disagreement** | ⚠️ MOSTLY | Authority Precedence (complex) |
| **2C: Post-vote revocation** | ⚠️ PARTIAL | Legitimacy Lifecycle |
| **3: Fraud detection** | ⚠️ BREAKS | Fraud Investigation Authority |
| **4: Multi-region authority** | ⚠️ BREAKS | Regional Authority Policies |
| **5: Appeal process** | ⚠️ PARTIAL | Appeal Authority Role |

---

## Critical Findings

### Hypothesis 1: Governance owns constitutional decisions

**Status:** ✅ **HOLDS** (mostly)

Evidence:
- Governance rules work in simple cases (1A, 1B, 2A)
- Complex authority scenarios (2B, 4) strain but don't break the hypothesis
- Governance is clearly the authority-level decision maker

**Weakness:** Assumes Governance is singular/central. Fails when authority is distributed (regional).

---

### Hypothesis 2: Verification owns proof decisions

**Status:** ⚠️ **BREAKS UNDER STRESS**

Evidence:
- Verification successfully proves eligibility in normal cases (1A, 2A)
- Verification fails when authority itself is questioned (1C, 2B, 5)
- Fraud detection (3) reveals: Verification assumes evidence is honest; breaks if evidence reveals fraud

**The crack:** Verification proves "does this person meet the rule?" but cannot answer "is the rule authority valid?"

---

### Hypothesis 3: Evidence is supporting infrastructure

**Status:** ⚠️ **UNCERTAIN**

Evidence:
- Evidence successfully supplies audit trail in all scenarios
- But in fraud detection (3), Evidence becomes critical to decision (invalidate votes?)
- In multi-region (4), Evidence's context-dependent semantics matter

**The concern:** Evidence is not passive infrastructure; it influences legitimacy decisions.

---

### Hypothesis 4: A single core domain "Verification" exists

**Status:** ❌ **DOES NOT HOLD**

Evidence:
- Verification handles proofs, but not authority validity
- Governance handles decisions, but not verification techniques
- Appeals create a second verification level (appeal authority)
- Fraud creates a third level (fraud investigation)

**The reality:** No single context owns "verification." It's a distributed responsibility.

---

## What Broke

### The hypothesis assumes:

```
Governance ← authorizes
    ↓
Verification ← proves
    ↓
Evidence ← records
```

### Reality appears to be:

```
Governance (central) ← defines
    ↓
Governance (regional) ← applies
    ↓
Authority Layer (A, B1, B2, C1...) ← approves
    ↓
Verification Layer 1 ← proves (point-in-time)
    ↓
Evidence ← records
    ↓
Fraud Investigation ← detects
    ↓
Appeal Authority ← reconsiders
    ↓
Verification Layer 2 ← re-proves (if appeal granted)
```

This is far more complex than hypothesized.

---

## Architecture Implications

### What survives stress test

✅ **Decision matrix** — Correctly maps decisions to contexts
✅ **Two-mode analysis** — Both modes follow similar patterns
✅ **Governance as authority definer** — Consistently appears as decision-maker
✅ **Verification as proof mechanism** — Works for point-in-time legitimacy
✅ **Evidence as audit trail** — Holds across all scenarios

### What doesn't survive

❌ **Single Verification context** — Verification is fragmented across layers
❌ **Linear flow** (Gov → Verify → Evid) — Reality is cyclic (appeals, fraud, revocation)
❌ **Evidence as pure infrastructure** — Evidence influences legitimacy decisions
❌ **Centralized Governance** — Authority is distributed and context-dependent

---

## Architectural Questions That Emerged

1. **Authority Validity:** Who decides if an authority's decision is valid? (Breaks Verification hypothesis)
2. **Authority Precedence:** How are conflicting authorities resolved? (Breaks in multi-authority scenarios)
3. **Legitimacy Lifecycle:** Is legitimacy fixed at vote time, or can it change? (Breaks with revocation)
4. **Fraud Authority:** Who investigates fraud? Who invalidates votes? (New context?)
5. **Appeal Authority:** Is appeal authority a Governance role or separate context? (New context?)
6. **Regional Governance:** How do distributed authorities fit into central Governance? (Breaks uniformity)

---

## Conclusion: Hypotheses Status

| Hypothesis | Status | Recommendation |
|-----------|--------|---|
| **Governance owns decisions** | ✅ HOLDS | **Confirm; refine for distributed authority** |
| **Verification owns proofs** | ⚠️ PARTIAL | **Revise; Verification is one layer among many** |
| **Evidence is infrastructure** | ⚠️ UNCERTAIN | **Revise; Evidence influences legitimacy decisions** |
| **Verification is core domain** | ❌ BREAKS | **Reject; multiple domains own verification layers** |
| **Constitutional Trust domain exists** | ✅ EMERGES | **New hypothesis: Higher-level domain coordinates Governance/Verification/Evidence/Authority/Appeals** |

---

**Status: Round 6D Stress Test complete. Hypotheses partially hold but crack under distributed authority, fraud, and appeals. New architectural hypothesis emerges: Constitutional Trust domain that coordinates Governance, Authority, Verification, Evidence, and Appeals as collaborating layers.**

**Ready for Round 6E: Final Classification and architectural commitment based on stress-test findings.**
