# KOS-ATTR-ARCH-001 — Governance Assurance
# Stage 1, rev 3 — Aggregate justification · Outcome semantics · Assurance Requirement

**Session 4 — `S4-architecture-attr-target` · ACTIVE, mutation owner · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` · 2026-08-16**
**Refines:** rev 2 (`7feec4ff`), following the ARB's four remaining questions and its required addition. Rev 2 stands except where superseded below.

> ## ARCHITECTURE STATUS — the three levels, never collapsed
> | Level | Status |
> |---|---|
> | **Aggregate recommendation** (§1) | **PROPOSED** |
> | **Bounded-context placement** (Stage 2) | **NOT YET CONFIRMED** — gated on `KOS-ARCH-BASELINE-001` acceptance |
> | **Target architecture** | **NOT APPROVED** |
>
> No C4. No technology. No schemas. No implementation. `INV-ATTR-4/5/6` remain **CANDIDATE / NOT ADOPTED**.
> **Self-review:** P-2 **Class-A ownership review only** — produced and reviewed by the same session. Both substantive corrections in this document's history (rev 2 §1, rev 3 §1) were found by the ARB, not by this session. **Q-D stands: independent architecture review may be required before approval.**

---

## 1 · The aggregate root, justified by APPROVED invariants only

**The weakness the ARB found:** rev 2 justified the Assurance Claim root by candidate **INV-ATTR-5** — *"an accepted assessment's outcome is consistent with the evidence associated to this claim."* A consistency boundary cannot rest on an unadopted invariant. **Restated: "Assurance Claim is the *leading aggregate-root candidate*," not "the root."**

### 1.1 What the approved invariants actually require to hold together

| Approved invariant | Consistency demand | Boundary consequence |
|---|---|---|
| **INV-ATTR-2** — classified; absent independent evidence, treated as *declared* | at **every read**, a claim must yield a defined outcome, defaulting to its dimension's weakest | **Claim + its Assessments + its current outcome must be transactionally consistent** |
| **INV-ATTR-3** — outcome visible in the **correct dimension** | an outcome must never be readable apart from its dimension and claim scope | the claim carries the dimension; the outcome cannot escape it |
| **INV-ATTR-1** — attribution never grants authority | nothing — it is a *dependency-direction* rule (§6 rev 2), not a consistency rule | no boundary demand; correctly so |

> **The minimal boundary justified by approved invariants is `{ Assurance Claim · its Assessments · its current outcome }` — with Evidence References *outside*, referenced.** INV-ATTR-2 alone is sufficient: it is a rule about what a read must always yield, which is precisely what a consistency boundary guarantees.

**Candidate invariants would *enlarge* this boundary, not create it.** If ARB adopts **INV-ATTR-5**, evidence associations must move **inside** the root, because "outcome cannot exceed evidence" then becomes a transactional rule. **This is stated as the accommodation, and it is why the model does not place evidence inside now** — adopting it later widens the boundary; adopting it prematurely would presume a decision the ARB has not made.

### 1.2 Scenario tests, as required

| Scenario | Behaviour under the proposed boundary |
|---|---|
| multiple evidence items | Evidence References sit outside; the root references them. No contention |
| evidence arriving at different times | no change to the root; may motivate a **new** assessment superseding the prior one — history preserved |
| **two assessments concurrently proposed** | proposals are internal (§3, non-events). Only *establishment* changes the current outcome, and the root serializes establishment. **Two proposals cannot both become current** |
| assessment supersession | inside the root — atomic; readers move from one current outcome to the next with no intermediate undefined state (this is INV-ATTR-2 doing work) |
| evidence retirement | outside the root; **cannot silently change an outcome.** It may motivate a new assessment — a deliberate act, not a side effect |
| **multiple dimensions on one act** | **separate claims, separate roots — one claim per (Governed Act, dimension).** This makes INV-ATTR-3 structurally true: an outcome cannot be read in the wrong dimension because it has no existence outside a single-dimension claim |
| consumer reads during supersession | the root yields exactly one current outcome, always defined (weakest by default). **No read can observe "no outcome"** |

**One claim per (act, dimension)** is the load-bearing consequence of these tests, and it was not stated in rev 2.

---

## 2 · Assurance Outcome — identity and lifecycle, made explicit

**Decision: the Assurance Outcome is a VALUE OBJECT — the immutable result of an established Assessment. It has no independent identity and no lifecycle of its own.**

The "current outcome" of a claim is a **derived read**, not stored state:

```
current outcome (claim) :=  outcome of the claim's currently-established Assessment
                            ELSE the weakest outcome of the claim's dimension     ← INV-ATTR-2
```

**Why this and not the alternatives:**

| Alternative | Rejected because |
|---|---|
| separate entity with its own lifecycle | it would need its own supersession rules, duplicating the Assessment's — two lifecycles for one fact |
| stored current state on the claim | INV-ATTR-2 could then be *wrong* (stored value drifting from the assessments). As a derivation it **cannot** be wrong |
| projection of an Assessment | close, and effectively what this is — but "projection" implies read-side, and the outcome is also the Assessment's own conclusion. "Value object produced by an Assessment, derived as current at the claim" is precise |

**This is consistent with rev 2 §7's removal of `OutcomeClassified`:** an outcome does not "become classified" as a separate happening — `AssessmentEstablished` (§3) *is* that happening. One fact, one event, no independent identity.

---

## 3 · `AssessmentAccepted` — renamed, and the ambiguity closed

**The ARB is right that "accepted" is a loaded word in this programme**, where *acceptance* denotes a Human/ARB act. Two meanings were riding on one name.

**Renamed: `AssessmentEstablished`.** Defined:

> **`AssessmentEstablished` means the assessment's lifecycle state is established *within the Governance Assurance domain*: its conclusion now determines the claim's current outcome. It confers no authority, constitutes no Human/ARB approval, and changes no permission.**

Two consequences worth stating:

1. **No Human/ARB act is required for an assessment to be established.** Independence at *Independently verified* is achieved by the **assessor's P-2 Class-B standing** — a property of the assessor, not an approval event. Requiring PO approval per assessment would make assurance a governance gate, contradicting `INV-ATTR-1` and P-5.
2. **The four domain facts become:** `AssuranceClaimAsserted` · **`AssessmentEstablished`** · `AssessmentSuperseded` · `EscalationTriggerRaised`.

---

## 4 · Assurance Requirement — developed as a first-class concept (ARB addition)

**Why it must be first-class, in the ARB's own terms:** the EKS must be able to answer *"why does this action require this level of assurance?"* If the requirement lives in configuration or prose, that reasoning disappears. Making it a domain concept keeps the *justification* traversable.

| Aspect | Definition |
|---|---|
| **Identity** | a **(Business Use Category, dimension)** pair — e.g. `(C3, Authorization Authenticity)`. Not per-act: requirements are about *classes* of act |
| **Content** | the **required outcome**, possibly risk-conditioned (C3: ordinary → *Corroborated*; high-consequence/externally consequential → *Independently established*), plus its **rationale** — the reason the business needs it |
| **Owner** | **Governance**, changed only by human decision (P-5/P-6). Not owned by the Assurance aggregate; **referenced** by it |
| **Lifecycle** | `proposed → approved → superseded` — every transition a human decision. **There is no mechanism path that alters a requirement**, which is where P-6's "no self-amending part" is enforced |
| **Relation to C1–C5** | the requirement **is** the mapping from business category to required outcome; the approved model §5 table is the current requirement set |
| **Relation to Escalation Trigger** | a trigger **raises a question about a requirement**; only a human decision changes one. Trigger → question → human → (possibly) new requirement version |
| **Relation to actual outcome** | comparison, never coupling — §4.1 |

### 4.1 The Gap — derived, reported, never gating

```
Governed Act ──classified as──▶ Business Use Category ──▶ Assurance Requirement ──▶ REQUIRED outcome
                                                                                          │ compare
Assurance Claim ──▶ current Assessment ──▶ CURRENT outcome ────────────────────────────────┘
                                                                                          ▼
                                                                            ASSURANCE GAP (derived)
```

**`Assurance Gap` is a derived read-side concept, not stored state** — for the same reason as §2: a stored gap could drift from its inputs. Per **P-5** it is **high-priority reported evidence for a human decision, and never an automatic block**; per the approved model's C3 rule, an unmet requirement obliges **explicit disclosure of the actual outcome at the time of the act**, which is a disclosure duty, not a gate.

**The EKS traversal this enables:** `act → category → requirement → required outcome → rationale`, answerable without reading prose. That is the knowledge-engineering value the ARB asked for.

---

## 5 · Stage-2 separation, stated as status (ARB point 4)

Reproduced at the head of this document as the three-level status table. Restated as a rule:

> **No bounded-context decision is final architecture until Stage 2 is complete.** §1's boundary is a **Stage-1 tactical recommendation** about *consistency*; where that aggregate lives in the context map is **not decided** and must not be reported as decided. The two are separable precisely because rev 2 separated them — which was the correction that produced this rev.

---

## 6 · Open questions, restructured by decision type (ARB request)

### A · Human/ARB business decisions
| # | Question |
|---|---|
| **Q-A1** | Approve the vocabulary change — **Assurance Claim** as the generic, *Attribution* reserved to the dimension (rev 2 §2). Requires a Governance vocabulary amendment to the approved model; **no requirement altered** |
| **Q-A2** | Does the new domain vocabulary itself become **canonical** for the programme, or remain architecture-local until Stage 2? |

### B · Architecture decisions
| # | Question |
|---|---|
| **Q-B1** | Approve **B — a separate Assurance aggregate**, with the minimal boundary `{Claim · Assessments · current outcome}` justified by **INV-ATTR-2/3 alone** (§1), and Evidence References outside? |
| **Q-B2** | Approve **one claim per (Governed Act, dimension)** (§1.2)? |
| **Q-B3** | Approve **Assurance Outcome as a value object with no independent lifecycle**, current-outcome derived (§2)? |
| **Q-B4** | Approve the one-way dependency direction as the enforcement of INV-ATTR-1 (rev 2 §6)? |
| **Q-B5** | Approve the evidence model — source artifacts owned by their source domain; Assurance owns the Evidence Reference and its evaluation (rev 2 §5)? |
| **Q-B6** | Approve the four domain facts with **`AssessmentEstablished`** replacing `AssessmentAccepted` (§3)? |
| **Q-B7** | Approve **Assurance Requirement** as a first-class Governance-owned concept, and **Assurance Gap** as derived/reported-never-gating (§4)? |
| **Q-B8** | Accept actor identity as the **currently observed** highest-leverage shared constraint — not a necessary prerequisite for all future dimensions (rev 2 §6)? |

### C · Process / sequence decision
| # | Question |
|---|---|
| **Q-C1** | Stage 2 (context-map confirmation) as a new assignment on this work item, or its own work item? |

### D · Independent review
| # | Question |
|---|---|
| **Q-D1** | Does this design require **independent architecture review** before ARB approval? Both substantive corrections so far were found by the ARB, not by the producing session — which is evidence about the limits of Class-A ownership review |

---

## 7 · What remains explicitly undone

Context-map placement (Stage 2) · C4 · technology · schemas · aggregate implementation · adoption of INV-ATTR-4/5/6 · any change to approved business requirements, outcome ladders, categories or triggers · any redesign of KnowledgeOS · any write-back to `KOS-ARCH-BASELINE-001` (Phase A discipline stands) · the Election Architecture pause (unaffected).

---

**Traceability:** rev 1 `8dab1be1` · rev 2 `7feec4ff` (§3.3 aggregate clause superseded there; candidate-invariant justification superseded here) · ARB reviews 2026-08-16 (first: five challenges; second: four questions + the Assurance Requirement addition + the status-table and question-restructuring requirements — all addressed) · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` (F-1…F-6) · Business Assurance Model rev 3 APPROVED (`5ab3b4e6`) §2 ladders, §3 invariants, §4 lifecycles, §5 categories/C3 primacy, §6 escalation · P-2 Class-A/B · P-5 · P-6 · `R-34` · `ES-005.4`.

---

> # PROPOSED — NOT APPROVED · Class-A ownership review only
