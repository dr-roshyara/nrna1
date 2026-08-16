# KOS-ATTR-ARCH-001 — Governance Assurance
# DDD Architecture Decision Refinement (Stage 1, rev 2)

**Session 4 — `S4-architecture-attr-target` · ACTIVE, mutation owner · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` · 2026-08-16**
**Refines:** the Stage-1 model at `8dab1be1`, following the ARB's five challenges. **Supersedes its §3.3 aggregate conclusion; every other section stands as refined here.**

> ## PROPOSED — NOT APPROVED
> No C4. No technology. No schemas. No implementation classes. `INV-ATTR-4/5/6` remain **CANDIDATE / NOT ADOPTED**. Stage 2 stays gated on `KOS-ARCH-BASELINE-001` acceptance.
>
> **Self-review disclosure (P-2 Class-A — ownership review only).** *Produced by this session:* the Stage-1 model and this refinement. *Reviewed by this session:* its own Stage-1 model, against the ARB's challenges. *Could not independently establish:* that its own reasoning is free of the same class of error the ARB caught — the correction in §1 was found by the ARB, not by me. **This is Class-A ownership review; it is not independent verification and must not be represented as one.**

---

## 1 · The error I accept, and why it mattered

**Stage-1 §3.3 said:** *"a supporting subdomain realized as a THIRD orthogonal record inside the existing Work Item aggregate boundary."*

**Two different decisions were fused in one sentence.** "Supporting subdomain" is a **strategic** classification (what kind of domain area this is). "Inside the Work Item aggregate" is a **tactical consistency boundary** (what must change together, transactionally). Conceptual relatedness was allowed to imply a shared consistency boundary. It does not.

**Worse — my own evidence argued the opposite.** Stage-1 §3.2 established that evidence arrives *after* the act, that assessments supersede, and that one act bears multiple independent claims. Those are precisely the signals of an **independent lifecycle**, which points away from a shared aggregate. The ARB read my evidence more faithfully than my conclusion did.

**§3.3's aggregate clause is withdrawn.** §3 below decides it properly.

---

## 2 · Revised ubiquitous language

The ARB's second challenge — *is a claim about an act, or by an actor?* — is resolved by separating three things Stage 1 left fused:

| Term | Precise meaning |
|---|---|
| **Governed Act** | a recorded act in the workflow domain — a Transition or a Grant. The **subject**. Owned elsewhere; referenced here by identity only. |
| **Assurance Claim** | an assertion **about a Governed Act**, of one kind, with a defined scope. *"Transition T-123 was performed by lane X."* |
| **Claimant** | **who asserted the claim.** May be the actor, may be Governance, may be a third party. A property *of the claim*, never the claim itself. |
| **Actor Statement** | what an actor says about its own act — *"I performed T-123."* **This is EVIDENCE for a claim, not a claim.** |

> **The resolution: an Assurance Claim is always a claim *about a governed act*. Who asserted it is the `Claimant`. An actor's self-statement is evidence, and evidence of the weakest kind — which is exactly why `Declared` is the weakest outcome.**

This also disposes of a trap: without the distinction, an actor's self-statement and an independent assertion would be the same object, and the model could not explain why one is weaker than the other.

**Retained from Stage 1:** *Assurance Claim* as the generic; *Attribution · Authorization Authenticity · Independence* as the three kinds; *Assurance Evidence* reserved to this domain.
**Added:** *Evidence Reference* (§5), *Actor Statement*, *Claimant*.

---

## 3 · Aggregate-boundary analysis — A vs B vs C

| Criterion | **A · inside Work Item aggregate** | **B · separate Assurance aggregate, referencing acts** | **C · separate bounded context** |
|---|---|---|---|
| **Consistency** | assurance changes must be transactionally consistent with workflow state — but nothing about an assessment requires that | claim + its evidence associations + its assessments are consistent together; the act is a stable reference | as B, plus a translation boundary |
| **Lifecycle independence** | ❌ **fatal** — assurance continues after a work item closes (a later audit re-assesses a years-old act). Under A the work item could never reach a terminal state | ✅ claim lifecycle runs independently of the act's | ✅ |
| **Transaction boundary** | an assessment would open a transaction on the workflow record | assessment transacts only on its own claim | ✅ |
| **Concurrency** | ❌ **decisive** — the engine serializes work-item mutation through a **single mutation owner**. Under A, *a reviewer recording an assessment would have to seize mutation ownership of the lane* — blocking implementation to record a judgment about it. Absurd, and it inverts the read/write asymmetry the whole model rests on | ✅ assessments never contend with workflow transitions | ✅ |
| **Ownership** | workflow domain would own assurance state — contradicting §4's ownership matrix | Governance Assurance owns its own state | ✅ |
| **Failure handling** | assurance unavailable ⇒ workflow degraded | assurance unavailable ⇒ **workflow entirely unaffected** — which is the required direction (§6) | ✅ |
| **Duplication risk** | none | none — acts referenced by identity; a reference is not a copy | ⚠️ higher: needs its own act-identity concept and translation |
| **Cross-context dependency** | none | one-way, in-context | one-way, across a published language |

### 3.1 Decision

> **B — a separate Assurance aggregate referencing governed acts by identity. Context placement (B-within-the-existing-context vs C) is deferred to Stage 2.**

**Why B, and why not A:** the concurrency row alone disqualifies A. The engine's single-mutation-owner rule exists to serialize *changes to the work*; assurance is a judgment *about* work already recorded. Forcing judgments through the work's write-lock would make assessment a blocking operation on execution — the precise coupling `INV-ATTR-1` forbids, arriving through the back door of a shared consistency boundary rather than through a gate.

**Why not C *yet*:** C is a defensible end state — the language genuinely differs, and consumers differ. But choosing it now would require asserting where the boundary sits in the accepted context map, and **the accepted context map does not exist** (`KOS-ARCH-BASELINE-001` Phase A is unaccepted). Deciding C now would presume baseline conclusions the grant forbids presuming. **B is decidable on evidence available today; C is a Stage-2 question.** Stated plainly: *the aggregate boundary is decided; the context boundary is not.*

**Candidate aggregate roots under B** (proposed, not fixed): **Assurance Claim** as root, holding its Evidence References and its Assessments — because the invariant that must hold transactionally is *"an accepted assessment's outcome is consistent with the evidence associated to this claim"* (the shape candidate `INV-ATTR-5` would formalize). Requirement and Escalation Trigger are Governance-owned concepts outside this root.

---

## 4 · Context map against the running domain

| Fact | Owned by | Published by | Consumed by | Translation needed? |
|---|---|---|---|---|
| Work Item identity, `workItemState` | workflow domain | the record | Assurance (as claim scope) | no — identity only |
| `SessionAssignment`, `Role`, `mutationOwner` | workflow domain | the record | Assurance (as claim subject/context) | no |
| `Transition` (incl. `recordedBy`, `executionContext`) | workflow domain | the record | Assurance — **subject of Attribution claims** | no |
| `Grant`, incl. **`humanActRef`** | Governance (sole writer, `G-2`) | Authority State | Assurance — **subject of Authorization Authenticity claims** | no |
| `humanAct` text on a START | Governance registers; the **human** originates | the record | Assurance (evidence) | no |
| **Assurance Claim / Evidence Reference / Assessment / Outcome** | **Governance Assurance** | Disclosure | reviewers · instruments · future EKS | **yes — at the read side** (§8) |
| Assurance Requirement (C1–C5) | Governance | approved model | Assurance (as the target to compare against) | no |

**Extend, never duplicate (`ES-005.4`), applied concretely:**

- **`Grant.humanActRef` IS the Authorization Authenticity claim** at outcome *Recorded*. The model **wraps** it — giving it identity, evidence and an assessed outcome — and introduces **no second authorization-authenticity representation**. If a design ever needs a second place where "was this authority act genuine?" is answered, that is a defect, not a feature.
- **`Transition.recordedBy` + `executionContext` ARE the Attribution claim** at *Declared*. Same treatment.
- **The two never-merged records stand.** Assurance adds a third concern **outside** their aggregate (per §3), so the two-record rule is neither merged nor superseded — it is left intact and joined by a separate neighbour.

---

## 5 · Evidence model — corrected per ARB challenge 3

**Withdrawn:** *"evidence is owned by nobody."* **Adopted:**

> **Source artifacts remain owned by their source domain. Governance Assurance owns the *Evidence Reference* — the association of an artifact to a claim — and the *evaluation* of its bearing.**

```
Source Artifact  (owned elsewhere)  ──▶  Evidence Reference  (owned here)  ──▶  Assurance Claim
```

| Source artifact | Owned by | What Assurance owns |
|---|---|---|
| Git commit / history | version-control domain | that this commit is evidence *for this claim*, and what it establishes |
| Workflow transition | workflow domain | its association as evidence, and its evaluated bearing |
| Verification report | the verification activity / its lane | the association + evaluation; **not the verdict itself** |
| External attestation (e.g. a signature) | an external identity/attestation system | the association + evaluation; **never the key material or the trust root** |
| Actor Statement (§2) | the actor | the association + evaluation — *and the classification that makes it weak* |

**Why the distinction is load-bearing:** if Assurance owned the artifacts, it would become a second home for facts other domains already own — and an artifact retired in its source domain could silently persist here. Owning only the *reference* and the *evaluation* keeps one home per fact and makes evidence retirement expressible (`EvidenceSuperseded`) without touching the source.

---

## 6 · Dependency direction — retained, and restated as enforcement

```
Governance Assurance ──depends on──▶ workflow / authority facts        ✅
workflow / authority ──depends on──▶ assurance outcomes                ❌ FORBIDDEN
```

**This is the architectural enforcement of `INV-ATTR-1`.** The dangerous design the ARB names — *assurance says "verifier is trusted" → workflow says "therefore verification may proceed"* — is impossible here not by policy but by direction: no gate may read an outcome, and §3's aggregate separation removes even the transactional path by which such a coupling could grow.

**Over-generalization corrected (ARB challenge 4).** Stage 1's §10 claim is restated:

> **Distinguishable actor identity is the highest-leverage *currently observed* shared constraint** — it presently blocks the second rung of all three ladders in this system. **It is not established as the only prerequisite for every future assurance dimension.** Authorization Authenticity in particular may admit evidence independent of execution attribution — e.g. an authority act delivered through a channel the recorder does not control, which corroborates authority without attributing execution. Recorded as an observation about the present system, not as a target-architecture invariant.

---

## 7 · Claim / Evidence / Assessment lifecycle, and the event vocabulary

**Business lifecycle:** `Claim asserted → Evidence associated → Assessment performed → Outcome established → Outcome disclosed → Claim/Assessment superseded`.

**Event test applied (ARB challenge 5):** *would another bounded context need to know this happened?* If not, it is an internal lifecycle transition, not a domain event.

| Proposed in Stage 1 | Classification | Reason |
|---|---|---|
| `AssuranceClaimAsserted` | **DOMAIN FACT** | consumers and reviewers need to know a claim now exists and is scoped |
| `AssessmentAccepted` | **DOMAIN FACT** | a governance judgment; the current outcome changes — reviewers and downstream consumers depend on it |
| `AssessmentSuperseded` | **DOMAIN FACT** | anyone relying on the prior outcome must learn it no longer stands |
| `EscalationTriggerRaised` | **DOMAIN FACT** | Governance must act; it crosses the boundary by design |
| `EvidenceObserved` | **internal** — or belongs to the *source* domain | an artifact existing is not an assurance fact |
| `EvidenceAssociated` | **internal** | matters only within the claim's own consistency boundary |
| `EvidenceEvaluated` | **internal** | an input to assessment, not a published conclusion |
| `AssessmentProposed` | **internal** | a proposal is not yet a conclusion |
| `OutcomeClassified` | **removed — redundant** | it is what `AssessmentAccepted` *means*; two events for one fact is inflation |
| `OutcomeDisclosed` | **read-side projection, not a domain event** | publication is a consumer concern (§8) |

**Result: ten proposed events reduce to four domain facts.** The pruning is the point — an event per state change would have made the read side and the domain indistinguishable.

---

## 8 · Consumer / read-side model

| Consumer | Consumes | Constraint |
|---|---|---|
| Human reviewers (PO/ARB, Governance) | disclosed `(dimension, outcome)` + claim scope + basis | must be legible without the assessor present |
| Reporting/resolver instruments (`AST-016` class) | outcomes, to surface | **P-5: report only.** No evaluation, no gating, no upgrading — and, per §3, no write path exists to reach |
| Future EKS knowledge graph | outcomes attached to reused knowledge | **candidate `INV-ATTR-6`** matters most here: knowledge reuse is exactly where assurance would silently propagate |

**Translation happens here, and only here** (§4): internal claim/assessment structure is *not* published. What crosses the boundary is `(dimension, outcome, claim scope, basis)` — the form `INV-ATTR-3` requires and the only form that keeps `INV-ATTR-6` enforceable, because an outcome that travels without its scope is exactly a transitive one.

---

## 9 · Invariant-to-owner allocation (revised for §3)

| Invariant | Owner | Protection mechanism |
|---|---|---|
| **INV-ATTR-1** — attribution never grants authority | **the dependency direction (§6) + the aggregate separation (§3)** | no gate reads an outcome; and no transactional path exists by which one could |
| **INV-ATTR-2** — classified; default = weakest | **Assurance Assessment** | absent an accepted assessment, the outcome *is* the weakest — there is no null to misread |
| **INV-ATTR-3** — visible to consumers, correct dimension | **Disclosure (§8)** | publishable only as `(dimension, outcome, scope)` |
| *(candidate)* **INV-ATTR-4** — assurance requires a claim | *would live on* the Assurance Claim aggregate root | an assessment outside a claim root would be unrepresentable |
| *(candidate)* **INV-ATTR-5** — cannot exceed evidence | *would live on* Assessment, **and is the invariant that justifies the §3 root choice** | the root's consistency rule relates accepted outcome to associated evidence |
| *(candidate)* **INV-ATTR-6** — non-transitive | *would live on* Claim scope **+ Disclosure** | outcomes addressable only via their claim; publication always carries scope |

**Accommodated, not adopted** — the design admits 4/5/6 without redesign; it does not claim to protect them, and it must not be described as doing so until ARB adopts them.

---

## 10 · Open Architecture Questions for Human/ARB

| # | Question |
|---|---|
| **Q-1** | Approve the F-1 vocabulary (**Assurance Claim** generic; *Attribution* = dimension) — needs a Governance vocabulary amendment; no requirement altered |
| **Q-2** *(revised)* | Approve **B** — a separate Assurance aggregate referencing acts by identity — and accept that **context placement (B vs C) is deferred to Stage 2**? |
| **Q-3** | Approve §6's one-way dependency as the architectural enforcement of `INV-ATTR-1`? |
| **Q-4** *(revised)* | Approve §5 — source artifacts owned by their source domain; Assurance owns the **Evidence Reference and its evaluation**? |
| **Q-5** *(revised)* | Accept actor identity as the **currently observed** highest-leverage shared constraint — **not** as a necessary prerequisite for all future dimensions? |
| **Q-6** | Stage 2 as a new assignment on this work item, or its own work item? |
| **Q-7** *(new)* | Approve the §7 event pruning — four domain facts, the rest internal or read-side? |
| **Q-8** *(new)* | Given §1: does the ARB require an **independent** architecture review of this refinement before design approval, since the only review so far is Class-A ownership review by its author? |

---

**Traceability:** Stage-1 model `8dab1be1` (§3.3 aggregate clause **superseded** by §3 here) · ARB review 2026-08-16 (five challenges, all addressed: §1 aggregate · §2 claim-about-vs-by · §5 evidence ownership · §6 over-generalization · §7 event inflation) · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` (F-1…F-6) · Business Assurance Model rev 3 APPROVED (`5ab3b4e6`) · P-1…P-6 · accepted root gap `487fce74` · running domain re-derived from `workflow-state.php` and the live records · `ES-005.4` · `AIP-14` · `R-34` · `P-2` Class-A/B.

---

> # PROPOSED — NOT APPROVED · Class-A ownership review only · returns for Human/ARB architectural decision
