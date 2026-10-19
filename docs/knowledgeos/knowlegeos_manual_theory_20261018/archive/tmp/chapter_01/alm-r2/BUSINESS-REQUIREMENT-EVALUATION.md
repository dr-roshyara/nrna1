# Evaluation against the KnowledgeOS business requirement (2026-10-18)

## The requirement (Principal, verbatim in substance)

> KnowledgeOS can decide within its authority, recognize when evidence is insufficient, obtain useful AI recommendations and preserve human ARB authority — with the behavior supported by rigorous evidence.

The workflow has four steps:
1. Examine the evidence.
2. Decide safely within the authorized scope, or else **refer** the question.
3. AI prepares a decision proposal: the problem, verified evidence versus uncertainty, the options, a recommendation, and **what requires human approval**.
4. The ARB decides, and KnowledgeOS records the decision and its authority.

**Status of this document:** research evaluation; a recommendation, not a decision.
- The ALM-r2 implementation is **paused**. `alm_r2.py` is an untested draft.
- The ALM-r2 specification and the independent-oracle work are kept, because they serve either path.

## 1. What the workflow actually needs from the theory

| Workflow need | Formal capability needed | Present in ALM? |
|---|---|---|
| **Decide within authority** | a **positive** verdict that is sound: "the records establish that this act or decision is authorized" | V layer: **verified** (Th-1, Th-2; reviewer-verified). Positive recognition: **defective** (false positives: R2-01, R2-02, R2-03, R2-04, R2-14) |
| **Recognize insufficient evidence** | every non-positive case → **abstain** | ALM's U verdict. But "not authorized" (F) is **not needed for the action**: both F and U lead to "do not decide" |
| **Useful AI recommendation** | the system must say **why** it cannot decide, separating (i) missing evidence, which AI can research, from (ii) a reserved governance choice, which only the ARB can make | **not modelled.** ALM computes verdicts, not reasons |
| **Preserve ARB authority** | (a) KnowledgeOS never adopts a reserved policy by default; (b) an AI recommendation is never recorded as a decision; (c) ARB decisions are recorded with their authority | (a) is the **design rule** of ALM-r1/r2 (no defaults), but ALM *requires* a parameter value to compute anything. (b) and (c) exist in the corpus as rules (ARCH-INV-1, ES-001.2, R-34, G-2/R5b), not in ALM |

## 2. Do ALM-r1's unresolved defects block the workflow?

| Defect class (review 2) | Blocks the workflow? | Why |
|---|---|---|
| **False positive authorization** under unknown fields, unknown order, or unverifiable endings (R2-01, R2-02, R2-03, R2-04; R2-14 for eligibility) | **YES: critical** | KnowledgeOS would **decide when it should escalate**. That is exactly the unsafe behaviour the requirement forbids |
| Th-5 characterization; λ⁻; sound negatives (R2-05) | **No** | negatives are not needed for action |
| Dated historical questions, K(d) (R2-06) | **No** for current decisions (an audit feature, separable) | the workflow decides about now |
| Auth/Form separation (R2-07) | **Partly**: useful for the *explanation* ("authorized but malformed" vs "not authorized") | affects the quality of the escalation package |
| C1 = a asymmetry (R2-08) | **Yes, if C1 = a is ever chosen** | otherwise moot (see §3) |
| Non-independent oracle (R2-09) | **Yes, for the evidence standard** | — |
| Silent policy choices: C7, RD 1 per ending, C6 proxy, BAR root, C11 (R2-10, R2-11, R2-12, R2-13, R2-16) | **YES for ARB authority** | any silent choice is the system deciding a governance question |

**Conclusion:** two defect classes block the requirement:
- **unsafe positives**;
- **silently embedded policy**.

The rest of ALM's open work (negatives, dates, the fine structure of Th-5) does **not** serve the workflow and can be **deferred**.

## 3. A smaller model that could meet the requirement: "Decide-or-Escalate Core" (DEC)

This is a sketch for evaluation; it is not authorized and not implemented. It **reuses ALM's verified V layer** and replaces the rest with three ideas.

### 3.1 Decide only when the verdict is robust across every open governance choice

Let Π be the set of reserved-policy values the ARB has **not yet decided**, e.g. C1, RD 1, υ (RD 6), C7, C10, C14, BAR, BASIS.

```
Decide(φ, E_k)  ⟺  ∀ θ ∈ Π :  Rec⁺_k(φ; θ) = T
```

Here `Rec⁺_k` is the **positive-only**, worst-case recognition: T only if φ holds in **every** world (unknown fields, unknown order, unrecorded parties and late-unverifiable endings all enumerated). Otherwise it is "not established".

**Consequence:** KnowledgeOS never needs a policy default. When the ARB decides a parameter, Π shrinks and more cases become decidable.

**This is how "preserve ARB authority" becomes a mathematical property, not a promise.**

### 3.2 Explain why not, automatically, and separate research from governance

If `¬Decide(φ)`, classify the reason (all derivable from the same enumeration):

| Class | Condition | Who resolves it |
|---|---|---|
| **EVIDENCE-GAP** | under *some* fixed θ the verdict is not T because worlds disagree (missing, unknown or contradictory records) | **AI research**: find or obtain the records |
| **POLICY-DEPENDENT** | with the evidence complete enough that worlds agree, the verdict is T for some θ ∈ Π and not for others | **only the ARB** (named parameter, with its business consequences) |
| **NOT-AUTHORIZED-ON-RECORDS** | not T for any θ | AI drafts the case; ARB or the authority decides whether to proceed |

The escalation package that AI prepares is then **grounded**:
- the evidence gap lists exactly which records are missing;
- the policy dependence lists exactly which reserved decision changes the outcome, and how.

### 3.3 Invariants for human authority (already in the corpus; to be tested in DEC)

| Id | Statement | Source |
|---|---|---|
| H-1 | a recommendation is never recorded as a decision | ES-001.2; R-34 |
| H-2 | a decision in a reserved domain is recorded only with a referenced ARB act | G-2/R5b; ARCH-INV-1 |
| H-3 | `Decide` never depends on an undecided reserved parameter | §3.1, by construction |
| H-4 | **reversibility**: a decision taken on records as of k is re-examined when later records change `Rec⁺` (ALM Th-4, non-monotone recognition) | ALM-r1, verified construction |

### 3.4 Why DEC is smaller

| ALM-r2 component | In DEC? |
|---|---|
| V layer (validity, authority, currency, eligibility) | **yes**: reused, verified. R2-07 (Auth separation) and R2-08 (C1 = a) are fixed there |
| worst-case worlds for positives (⊥ fields, order, υ, π) | **yes**: needed for safe positives |
| negatives, λ⁻, Th-5 / Th-5a, sound-negative characterization | **no**: deferred |
| dated questions, witnesses, K(d) | **no**: separate audit feature, deferred |
| C1 / RD 1 / … as configuration | **replaced** by quantification over Π |
| reason classification + escalation package | **new**, small |

**What DEC must prove:**
- **(D1) Safety:** `Decide ⇒ V(φ) = T` under AX-Truth + completeness, *for every θ*.
- **(D2) Non-usurpation:** H-3.
- **(D3) Explanation soundness:** EVIDENCE-GAP and POLICY-DEPENDENT are correct.
- **(D4) Reversibility:** H-4.

**Honest limit:** without completeness, (D1) cannot hold for any record-based system (ALM Th-4 and Th-6). DEC therefore needs an operational answer, not just a theorem:
- either a **recording-deadline duty** with the positive horizon λ⁺ (T1-20);
- or **re-examination on new records** (H-4).

That choice remains the ARB's.

## 4. Recommendation (for the Principal)

**Re-scope round 2 from "repair ALM-r2 in full" to "build and verify DEC on ALM's verified V layer".**

- **Exit criterion** (the Principal's): KnowledgeOS can decide within its authority, recognize insufficient evidence, obtain useful AI recommendations and preserve ARB authority, with rigorous evidence. Measured by (D1)–(D4) + H-1…H-4, an independent oracle, and one fresh review.
- **Same bounds as the round-2 charter:**
  - one round;
  - no further repair round without a Principal decision;
  - if DEC fails review, keep the verified results and narrow further.
- **What is kept from the work so far:**
  - ALM's V layer and its proofs;
  - the review-2 counterexamples (they become DEC's safety regressions);
  - the ALM-r2 specification's world semantics (for positives);
  - the independent oracle approach.
- **What is deferred, not lost:** sound negatives, dated audit questions, Th-5. These are recorded as backlog for an audit capability.

**Alternative:** continue ALM-r2 as specified. Its full recognition layer would also give negatives and dated answers, which the workflow does not require. Larger, slower, more review risk.

**This is a scope and goal decision, so it belongs to the Principal:**
- **(1) re-scope to DEC** (recommended); or
- **(2) continue the full ALM-r2.**
