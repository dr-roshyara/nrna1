# Round 30A — ARB Governance Resolution Authorization

**Date:** 2026-06-08

**Phase:** Governance Path Determination (Pre-Resolution Authorization)

**Status:** AWAITING ARB DECISION

**Purpose:** For each unresolved item (D35, D36, D37, ADH-1, D42B), determine whether resolution can proceed from existing evidence or requires additional discovery authorization.

**Critical Distinction:** This document authorizes how items will be resolved, not what the resolution will be. It establishes governance paths only.

---

## Evidence Authority Hierarchy

Before analyzing each item, establish evidence priority:

| Priority | Source | Authority | Use Case |
|----------|--------|-----------|----------|
| **1** | Constitutional Artifacts | Highest | Authority, governance, legitimacy |
| **2** | ADR Decisions | High | Architectural intent, design principles |
| **3** | Governance Documentation | Medium | Governance rules, procedures |
| **4** | Repository Implementation | Lowest | Behavior, mechanism (may reveal but cannot override) |

**Critical Rule:** Repository implementation reveals how things currently work. Constitutional artifacts establish how things SHOULD work. Stakeholder interviews may clarify ambiguity. Stakeholder opinion cannot override constitutional evidence.

---

## Governance Question

Before authorizing any resolution activities, ARB must answer:

```
For each unresolved item:
Can it be resolved from existing governance evidence
OR
does it require additional discovery authorization?
```

This determines the path forward and prevents accidental reopening of discovery.

---

## Unresolved Items Analysis

### D35: What Happens When Legitimacy = EXPIRED?

**Current Uncertainty:**

When ConstitutionalDecision determines legitimacy = EXPIRED, what are the consequences?
- Corrective (reversal of original decision)?
- Preventative (blocking future actions)?
- Advisory (informational only)?

**Existing Evidence Available:**

- ConstitutionalDecision model records EXPIRED status ✓
- No enforcement mechanism observed in code ✗
- ADRs reference legitimacy but not consequences ◐
- Governance artifacts may document consequences ◐

**Evidence Sufficiency Assessment:**

```
PARTIALLY SUFFICIENT
```

Why: We know legitimacy status is recorded. We do not know constitutional intent for consequences.

**Resolution Path Options:**

| Option | Path | Evidence Source | Effort |
|--------|------|-----------------|--------|
| A | Constitutional Evidence Reconciliation | Review ADRs, governance documents for consequence intent | Light |
| B | ARB Governance Decision | ARB decides from constitutional principles | Medium |
| C | Stakeholder Clarification | Interview governance authority on intent | Medium |
| D | Additional Discovery | Authorize new discovery stream | Heavy |

**Recommended Path:** Options A+B
- Evidence reconciliation (constitutional artifacts first, implementation second)
- ARB governance decision (based on constitutional evidence)

**Decision Owner:** ARB

**Closure Criteria (Governance Only):**

- ARB explicitly decides: D35 consequences are CORRECTIVE, PREVENTATIVE, or ADVISORY
- Consequence model documented with constitutional justification
- Decision ownership clarified (who determines/enforces consequences?)
- Governance interpretation recorded for future phases

**Additional Discovery Required?** NO — existing governance evidence sufficient

---

### D36: Who Is Permitted to Invoke ConstitutionalArbitrationKernel?

**Current Uncertainty:**

ConstitutionalArbitrationKernel.decide() is public. Who/what is authorized to invoke it?
- Anyone (distributed challenge capability)?
- Restricted authorities only (organizational)?
- Automatic invocation (no submission)?

**Existing Evidence Available:**

- ConstitutionalArbitrationKernel exists with public method ✓
- No invocation authorization policy documented ✗
- Authority roles documented (chief, deputy) ◐
- ADR-004 references authority but not challenge invocation ◐
- Constitutional artifacts may address authority ◐

**Evidence Sufficiency Assessment:**

```
PARTIALLY SUFFICIENT
```

Why: We know the method exists. We do not know constitutional authority scope.

**Evidence Priority Sequence:**

1. Constitutional artifacts (does constitution define challenge authority?)
2. ADRs (do ADRs document invocation authority?)
3. Governance documents (do governance rules establish who can challenge?)
4. Repository implementation (code may show current behavior but cannot establish authority)

**Resolution Path Options:**

| Option | Path | Evidence Source | Effort |
|--------|------|-----------------|--------|
| A | Constitutional Evidence Reconciliation | Priority 1-3 sources for authority rules | Light |
| B | ARB Governance Decision | ARB decides from constitutional evidence | Medium |
| C | Stakeholder Clarification | Interview governance authority on challenge authority | Medium |
| D | Additional Discovery | Authorize new discovery stream | Heavy |

**Recommended Path:** Options A+B
- Evidence reconciliation (constitutional priority first)
- ARB governance decision (based on constitutional evidence)

**Decision Owner:** ARB

**Closure Criteria (Governance Only):**

- ARB explicitly decides: invocation is DISTRIBUTED, ORGANIZATIONAL, or AUTOMATIC
- Authority scope documented with constitutional justification
- Challenge handling capability classified
- Governance interpretation recorded for future phases

**Additional Discovery Required?** NO — existing governance evidence sufficient

---

### D37: How Is Legitimacy Determination Enforced?

**Current Uncertainty:**

When legitimacy is determined, what is the enforcement mechanism?
- Automatic blocking (system-enforced)?
- Officer-triggered (governance-enforced)?
- Informational only (advisory)?

**Existing Evidence Available:**

- ConstitutionalGovernanceDecision.isConstitutionallyValid() exists ✓
- No enforcement policy documented ✗
- Authority roles documented ◐
- Governance documents may address enforcement ◐
- Constitutional artifacts may define enforcement ◐

**Evidence Sufficiency Assessment:**

```
PARTIALLY SUFFICIENT
```

Why: We know legitimacy is evaluated. We do not know constitutional enforcement intent.

**Evidence Priority Sequence:**

1. Constitutional artifacts (does constitution define enforcement authority?)
2. ADRs (do ADRs document enforcement model?)
3. Governance documents (do governance rules establish enforcement?)
4. Repository implementation (code reveals behavior but not authority intent)

**Resolution Path Options:**

| Option | Path | Evidence Source | Effort |
|--------|------|-----------------|--------|
| A | Constitutional Evidence Reconciliation | Priority 1-3 sources for enforcement rules | Light |
| B | ARB Governance Decision | ARB decides from constitutional evidence | Medium |
| C | Stakeholder Clarification | Interview governance authority on enforcement | Medium |
| D | Additional Discovery | Authorize new discovery stream | Heavy |

**Recommended Path:** Options A+B
- Evidence reconciliation (constitutional priority)
- ARB governance decision (based on constitutional evidence)

**Decision Owner:** ARB

**Closure Criteria (Governance Only):**

- ARB explicitly decides: enforcement is AUTOMATIC, OFFICER-TRIGGERED, or ADVISORY
- Enforcement authority documented with constitutional justification
- Decision ownership clarified (who is responsible for enforcement?)
- Governance interpretation recorded for future phases

**Additional Discovery Required?** NO — existing governance evidence sufficient

---

### ADH-1: What Is the Complete Authority Hierarchy?

**Current Uncertainty:**

What is the complete authority hierarchy?
- Chief vs. deputy authority split?
- What decisions are chief-only?
- What can be delegated?
- Override and escalation paths?
- Emergency protocols?

**Existing Evidence Available:**

- Chief and deputy roles observed in code ✓
- ADR-001, ADR-004 reference authority ◐
- Authorization resolver exists ◐
- No complete hierarchy documented ✗
- Constitutional artifacts may define hierarchy ◐

**Evidence Sufficiency Assessment:**

```
INSUFFICIENT
```

Why: Roles exist, but complete authority hierarchy is unknown. This is constitutional governance, not implementation detail.

**Evidence Priority Sequence:**

1. Constitutional artifacts (constitution defines authority structure)
2. ADRs (architectural decisions may clarify intent)
3. Governance documents (governance procedures reference hierarchy)
4. Repository implementation (reveals current structure but cannot define authority)

**Critical Rule:** If authority hierarchy is a constitutional matter, stakeholder opinion about "how it works" cannot override what the constitution says "how it should work."

**Resolution Path Options:**

| Option | Path | Evidence Source | Effort |
|--------|------|-----------------|--------|
| A | Constitutional Evidence Reconciliation | Priority 1-3 sources only; constitutional artifacts override interpretation | Light |
| B | ARB Governance Decision | ARB decides from constitutional evidence | Medium |
| C | Stakeholder Clarification | Interview governance authority on hierarchy intent (clarification, not authority) | Medium |
| D | Additional Discovery | Authorize new discovery stream on constitutional governance | Heavy |

**Recommended Path:** Options A+B (do NOT use stakeholder interpretation to override constitutional evidence)
- Evidence reconciliation (constitutional priority strictly enforced)
- ARB governance decision (based on constitutional artifacts)

**Decision Owner:** ARB (constitutional authority only)

**Closure Criteria (Governance Only):**

- Complete authority hierarchy documented (chief/deputy/roles)
- Delegation scopes defined (what is chief-only, what can be delegated)
- Escalation paths established
- Override mechanisms clarified
- Constitutional justification documented
- Governance interpretation recorded for future phases

**Additional Discovery Required?**

Current Position:

Constitutional evidence review is required first.

Only if constitutional evidence cannot establish complete hierarchy may ARB authorize additional constitutional-source discovery (not stakeholder interviews, only constitutional artifacts).

---

### D42B: Who Owns the Verifiability Guarantee?

**Current Uncertainty:**

Who owns the guarantee that votes are verifiable to voters?
- Verification context (identity trust extends to vote verification)?
- Voting context (vote recording enables verification)?
- Separate context needed?

**Note:** This is the ONLY remaining pure domain-model question. D35, D36, D37, ADH-1 are constitutional governance questions.

**Existing Evidence Available:**

- Receipt hash exists in Vote aggregate ✓
- Voter can verify receipt ◐
- Trust Attestation establishes identity trust ◐
- Verification context owns trust decisions ◐
- No explicit guarantee ownership observed ✗

**Evidence Sufficiency Assessment:**

```
PARTIALLY SUFFICIENT
```

Why: We know receipt exists. We do not know which context owns the verifiability guarantee.

**Evidence Priority Sequence:**

1. Discovered invariants (what must be true about verifiability?)
2. Bounded context evidence (which context logically owns this decision?)
3. Aggregate boundaries (which aggregate should enforce this?)
4. Repository implementation (reveals current mechanism)

**Resolution Path Options:**

| Option | Path | Evidence Source | Effort |
|--------|------|-----------------|--------|
| A | Boundary Analysis | Use discovered invariants to determine ownership | Light |
| B | Candidate Evaluation | Evaluate Voting, Verification, separate context options | Medium |
| C | Design Preparation | Document candidates for future design phase | Medium |
| D | Additional Discovery | Authorize new discovery stream | Heavy |

**Recommended Path:** Options A+B+C
- Boundary analysis (which context owns this decision?)
- Open-ended candidate evaluation (evaluate all options)
- Design preparation (record candidates for future design phase)

**Decision Owner:** ARB (investigation-driven)

**Closure Criteria (Governance Only):**

- Ownership determined: Voting, Verification, or existing context?
- Candidate options documented with tradeoffs
- Boundary implications clarified
- Governance decision recorded for future design

**Governance Constraint:**

Round30C may not create, split, merge, or introduce bounded contexts.

Ownership evaluation must occur within the Round 25 accepted context map (9 contexts). No new contexts may be proposed during this investigation.

**Important:** Do NOT decide mechanism (hash, signature, ZK proof, etc.) at this stage. That belongs in design phase.

**Additional Discovery Required?** NO — existing evidence sufficient for boundary analysis within accepted context map

---

## Unresolved Items Classification

### Class A — Constitutional Governance Resolution

Items requiring ARB governance decisions on constitutional matters:

```
D35 — Legitimacy Consequences
D36 — Invocation Authority
D37 — Enforcement Mechanism
ADH-1 — Authority Hierarchy
```

**Characteristic:** These questions determine governance rules, not domain boundaries. Resolution comes from constitutional evidence, not domain analysis. Unified in Round 30B.

### Class B — Domain Ownership Resolution

Items requiring boundary and ownership determination within domain model:

```
D42B — Verifiability Guarantee Ownership
```

**Characteristic:** This question determines which bounded context owns a domain decision. Resolution comes from domain-model evidence. Evaluated separately in Round 30C with strict governance constraints.

---

## ARB Authorization Matrix

| Item | Classification | Evidence Sufficiency | Resolution Path | Discovery Required? | Primary Owner |
|------|---|---------------------|-----------------|---------------------|---------------|
| D35 | Class A — Constitutional | Partially Sufficient | Evidence Reconciliation + ARB Decision | NO | ARB |
| D36 | Class A — Constitutional | Partially Sufficient | Evidence Reconciliation + ARB Decision | NO | ARB |
| D37 | Class A — Constitutional | Partially Sufficient | Evidence Reconciliation + ARB Decision | NO | ARB |
| ADH-1 | Class A — Constitutional | Insufficient | Constitutional Evidence Review + conditional authorization | Conditional | ARB |
| D42B | Class B — Domain Model | Partially Sufficient | Boundary Analysis + Candidate Evaluation | NO | ARB (investigation-driven) |

---

## Proposed Authorization: Two Parallel Governance Reviews

**Round 30B — Constitutional Governance Review (Class A)**

Scope: D35, D36, D37, ADH-1

Activities:
1. Constitutional evidence reconciliation (priority-ordered: Constitution > ADR > Governance Docs)
2. ARB governance decisions on constitutional matters
3. Governance interpretation documented

Output: Constitutional governance model approved

---

**Round 30C — Verifiability Ownership Investigation (Class B)**

Scope: D42B only

**Critical Constraints:**
- Answer "WHO OWNS IT" only. Do NOT answer "HOW IS IT IMPLEMENTED."
- Ownership evaluation must occur within Round 25 accepted context map.
- No new bounded contexts may be created or proposed.

Activities:
1. Boundary analysis (which accepted context owns verifiability?)
2. Candidate evaluation (Voting vs. Verification vs. existing context)
3. Tradeoffs documented for future design

Output: D42B ownership determined; design candidates recorded

---

## Next Steps for ARB

**Decision 1: Governance Evidence Reconciliation**
- Approve constitutional evidence path for D35, D36, D37, ADH-1?
- Or authorize additional discovery?

**Decision 2: D42B Scope Boundary**
- Authorize Round 30C with OWNERSHIP ONLY scope?
- Confirm mechanism and workflow design are deferred to Round 31+?

**Decision 3: Round 30B and 30C Authorization**
- If Decisions 1 and 2 approved: Authorize both?
- Parallel execution permitted?

---

## Closure Criteria for Round 30A

**Document Governance Authorization Charter:**

For each unresolved item (D35, D36, D37, ADH-1, D42B), ARB explicitly decides:

1. ✓ Is evidence path approved?
2. ✓ Is resolution path selected?
3. ✓ Is additional discovery authorized or rejected?
4. ✓ Who owns the decision?
5. ✓ What constitutes closure (governance outcomes only)?

Once all five items have explicit ARB decisions on these criteria, Round 30A is complete.

Then: Authorize Round 30B (Constitutional Governance Review) and Round 30C (Verifiability Ownership Investigation).

---

## Summary

**Current Status:**
- Round 29 discovery closed
- Round 30 readiness assessed  
- Design not authorized

**Key Discovery:**
The remaining uncertainty is split into two classes:
- **Class A (Constitutional):** D35, D36, D37, ADH-1 — governance questions, not domain questions
- **Class B (Domain Model):** D42B — ownership determination within existing domain

This separation reflects that discovery has matured from DDD domain discovery into constitutional governance discovery.

**This Document's Purpose:**
- Determine whether governance debts can be resolved from existing evidence
- Establish evidence authority hierarchy (Constitutional > ADR > Governance Docs > Implementation)
- Propose separate governance review paths for distinct problem classes
- Prevent accidental design, rediscovery, or context boundary drift

**Key Principle:**
Repository implementation shows current behavior. Constitutional artifacts establish authority. Stakeholder interpretation clarifies, not overrides. This document prioritizes constitutional evidence over implementation behavior.

**Outcome:**
Two parallel governance reviews (Round 30B for constitutional questions, Round 30C for domain ownership) determining what must be true before design can begin.

---

**Round 30A — Governance Resolution Authorization**

**Status:** READY FOR ARB REVIEW AND DECISION

**Next Step:** ARB approves governance paths and authorizes Round 30B and 30C
