# KOS-ATTR-ARCH-001 — Governance Assurance
# Domain and Responsibility Model (Stage 1)

**Session 4 — `S4-architecture-attr-target` · ACTIVE, mutation owner · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` · 2026-08-16**

> ## PROPOSED — NOT APPROVED
> **First output as ordered: a domain and responsibility model. No C4 diagrams, no technology choices, no schemas, no implementation.** Returns for Human/ARB design approval; the record's role set excludes implementation, so building anything requires a new work item after approval.
>
> **Startup gate:** work item `KOS-ATTR-ARCH-001` ✅ · assignment ACTIVE + mutation owner ✅ · both grants AUTHORIZED ✅ · scope = architecture design only ✅.
>
> **Disclosures.** (1) **Self-authored input:** the `KOS-GOV-ATTRIBUTION-001` ADP is an input to this commission and was produced by this same process — classified `Declared`, never independently confirmed. (2) **Baseline not presumed:** `KOS-ARCH-BASELINE-001` Phase A is still ACTIVE and unaccepted; every statement below about the running domain is re-derived **directly from `workflow-state.php` and the live records**, not from the baseline document. Bounded-context *confirmation* against the accepted baseline is deferred to Stage 2 (F-6).
> (3) **INV-ATTR-4/5/6 are accommodated, not adopted** — every reference marks them `candidate`.

---

## 1 · F-1 · Ubiquitous language — resolving the name collision

**The collision, stated:** the approved model names the generic concept **"Attribution Claim"**, while **"Attribution"** is simultaneously one of its three kinds. So "Attribution Claim" means both *any claim* and *the who-acted claim*. An ubiquitous language cannot carry that.

**Decision (Architecture's, per F-1) — rename the generic, keep the dimension:**

| Concept | Name adopted | Reason |
|---|---|---|
| the generic claim | **Assurance Claim** | the chain's own downstream vocabulary is already assurance-headed (*Assurance Assessment · Assurance Outcome*), and the model's §7 names the emerging domain **Governance Assurance**. The generic takes the domain's name. |
| the three kinds | **Attribution · Authorization Authenticity · Independence** | unchanged from the approved model; **"Attribution" now denotes exactly one thing everywhere** |

**Defence, and its cost.** The alternative — renaming the dimension (e.g. "Actor Attribution") — preserves the approved document's wording but degrades the dimension name that the *business* uses, and leaves "Attribution Claim" reading as a kind. Renaming the generic touches one term in approved business vocabulary and repairs the ambiguity at its source. **This is a vocabulary refinement, not a change to any requirement** — every outcome, category, invariant and trigger is untouched. It nonetheless needs **Governance registration as a vocabulary amendment**, because the approved model uses the old term (open question **Q-1**).

**Second collision, resolved the same way:** *Evidence* is used in the programme for governance artifacts generally. In this domain, **Assurance Evidence** is reserved for evidence *supporting an Assurance Claim*.

---

## 2 · The domain — concepts and their reason to exist

```
GOVERNED ACT  (a Transition or a Grant in the running domain — the SUBJECT, never owned here)
      │
      │  is the subject of ▼
ASSURANCE CLAIM        kind ∈ {Attribution · Authorization Authenticity · Independence}
      │                 asserts something about the act; has identity, scope, lifecycle
      │  supported by ▼
ASSURANCE EVIDENCE     referenced artifacts; associated to a claim; evaluated, never owned
      │  input to ▼
ASSURANCE ASSESSMENT   what the evidence legitimately permits; performed by an ASSESSOR
      │  yields ▼
ASSURANCE OUTCOME      classified per dimension, weakest-by-default
      │  published as ▼
DISCLOSURE ─────────▶ CONSUMERS (human reviewers · instruments · future EKS)
```

**Concepts, each with the question it answers and why it is not collapsible:**

| Concept | Answers | Why it must exist separately |
|---|---|---|
| **Assurance Claim** | *what is asserted about this act?* | must be scoped and superseded independently of the act (§3.2); one act bears up to three claims |
| **Assurance Evidence** | *what supports it?* | accrues **after** the act; may support several claims; may be retired without retiring the claim |
| **Assessor** | *who judged, and with what standing?* | P-2 Class-B independence is a property of the judge, not of the claim — Independence outcomes are unprovable without it |
| **Assurance Assessment** | *what does the evidence permit?* | revised by supersession, never rewriting (approved model §4) |
| **Assurance Outcome** | *the classified result, per dimension* | the business-facing value; carries its dimension always (never a bare level) |
| **Assurance Requirement** | *what does the business need here?* | per use-category C1–C5; owned by Governance; changed only by human decision |
| **Escalation Trigger** | *has something occurred that questions the requirement?* | P-6; raises a **question**, never an outcome |
| **Disclosure** | *what do consumers see?* | INV-ATTR-3 lives here; the read side is a first-class part of the model (F-4) |

---

## 3 · F-2 · Context map against the running domain

### 3.1 The finding that shapes everything: the claims already exist, unnamed

Re-derived directly from `workflow-state.php` and the live records:

| Running concept | Is already an implicit claim of kind | At outcome |
|---|---|---|
| `Transition.recordedBy` (unvalidated free string on REGISTER/HANDOFF/START) | **Attribution** | *Declared* |
| `SessionAssignment.executionContext` (self-declared label, never compared) | **Attribution** | *Declared* |
| the transition being appended at act time, sequenced | **Attribution** | *Recorded* |
| **`Grant.humanActRef`** | **Authorization Authenticity** | *Asserted → Recorded* |
| disclosure prose (*"this process declines… R-34"*, `A-4.3` disclosures) | **Independence** | *Declared* |

> **The Governance Assurance domain introduces no new claims. It names, scopes, evidences and classifies claims the platform is already making implicitly — today, all at their weakest outcomes.** This is why the domain is a refinement of an existing reality rather than a new capability, and it is the strongest argument against building a parallel model.

**Consequence for `ES-005.4` (consume or extend, never create a second):** `Grant.humanActRef` **is** the Authorization Authenticity claim. The model must **extend** it — give it identity, evidence and an assessed outcome — never introduce a second authorization-authenticity concept beside it.

### 3.2 Is an Assurance Claim an attribute of a Transition, or its own concept?

**Its own concept, referencing the act by identity.** Four reasons, the first decisive:

1. **Append-only forbids the alternative.** A transition is immutable once appended; assurance evidence arrives *later*. Storing assurance on the transition would require rewriting recorded history to record an assessment — violating the discipline the whole programme rests on.
2. **One act, up to three claims** (attribution · authorization authenticity · independence), each with its own evidence and outcome. An attribute cannot carry three independent lifecycles.
3. **Supersession.** Assessments are revised by superseding (approved §4). Claims and assessments need identity to be superseded; attributes do not have it.
4. **Candidate `INV-ATTR-6` (non-transitive)** requires assurance to be **scoped to a claim**. Scope presupposes an identity to scope to.

### 3.3 New bounded context, extension, or supporting subdomain?

**Answer: a supporting subdomain — *Governance Assurance* — realized as a THIRD orthogonal record inside the existing Work Item aggregate boundary. Not a new bounded context; not an attribute of existing records.**

**Honouring the existing boundary decision, by extending its logic rather than superseding it.** The engine enforces *two never-merged records* — session registry (`transitions[]`) and authority state (`grants[]`) — because they answer different questions. Assurance answers a **third, orthogonal** question — *how well-established is any claim about the contents of the other two?* It is meta to both, and belongs to neither:

```
Session Registry   — who exists · what state · who owns mutation
Authority State    — what was authorized · by whom · for what scope
Assurance Record   — how well-established is each claim about the above   ← the extension
        └── pairwise never merged; the two-record rule becomes a three-record rule
```

**Why not a separate bounded context:** it would need its own model of Work Item, Transition and Grant identity — duplication `ES-005.4` forbids, and a second place where the workflow's own truths could drift.
**Why not a mere attribute:** §3.2.
**Why "supporting subdomain":** it has its own ubiquitous language, its own lifecycles and its own consumers, but it exists **to serve** governance of the core work — it is not the core domain, and `AIP-14` keeps the Election system in that position.

**Stage-2 obligation (F-6):** this placement is a Stage-1 determination made against the **running mechanism**. Confirming it against the *accepted* current-state baseline is Stage 2, gated on `KOS-ARCH-BASELINE-001` acceptance. It is stated as a decision, not smuggled as an assumption.

---

### 3.4 The four candidate domain areas — tested, not inherited

The approved model §7 offers *Governance · Attribution · Authorization · Verification* as **hypotheses**. Tested against the running domain and the concept set:

| Candidate area | Verdict | Reason |
|---|---|---|
| **Governance** | **not a separate area — it is the existing owner** | Governance already exists as a role and the sole Authority-State writer (`G-2`). Making it an assurance sub-area would duplicate an actor that already has a home |
| **Attribution** | **not a context — a *dimension*** | it shares its entire lifecycle vocabulary (claim/evidence/assessment/outcome) with the other two. Splitting it would triplicate one model; the ubiquitous language is identical, only the *question* differs — the classic false boundary |
| **Authorization** | **not a context here — it is the SUBJECT** | authorization lives in Authority State (grants). Assurance makes *claims about* it. A separate "Authorization" assurance context would compete with the record that already owns authorization |
| **Verification** | **not a context — an *activity* performed by an Assessor** | verification is how an Independence outcome reaches *Independently verified*. It is modelled as the Assessor's act (§11, P-2 Class-B), not as a boundary |

**Result: one subdomain, three dimensions, not four contexts.** The four groupings collapse because they share one language and one lifecycle; what genuinely differs between them is the *question asked*, which is exactly what a dimension is for. **The hypotheses are disposed of by evidence, not inherited.**

## 4 · Ownership and responsibility

| Thing | Owner | Explicitly not owned by |
|---|---|---|
| Lifecycle state (assignments, transitions) | the running workflow domain | Governance Assurance |
| Authority state (grants) | Governance (sole writer, `G-2`) | Governance Assurance |
| **Assurance claims, assessments, outcomes** | **Governance Assurance** | the workflow engine |
| **Assurance evidence** | **nobody — evidence is referenced, never owned.** Artifacts live where they live (commits, records, external attestations). The domain owns the *association* and the *evaluation*, not the artifact | the assurance record |
| Assurance requirements (C1–C5 targets) | Governance, changed only by human decision | any mechanism |
| Escalation triggers | Governance evaluates and surfaces; instruments may detect | any automated requirement change |
| Authority itself | **Human PO/ARB — unchanged** | every concept in this model |
| Knowledge (future EKS) | EKS | Governance Assurance (it is a *consumer*, F-4) |

**The responsibility sentence:** *Governance Assurance owns how well a claim is established. It never owns what happened, what was authorized, or what is permitted.*

---

## 5 · Dependencies and authority direction

```
Human authority ──▶ Governance registration ──▶ Authority State ──▶ gates (START, ownership, closure)
                                                      ▲
                                                      │  subject-of  (identity reference only)
Governance Assurance ─── claims · evidence · assessments · outcomes ──▶ Disclosure ──▶ consumers
```

**One-way, and the direction is load-bearing:** Governance Assurance **depends on** the workflow domain (it references transition and grant identities). The workflow domain **must not depend on** Governance Assurance — because the moment a gate consults an assurance outcome, assurance has become authority, violating **INV-ATTR-1**. The dependency direction *is* the enforcement of INV-ATTR-1 at the architecture level, and it is the single property most worth protecting in this design.

---

## 6 · The three dimensions, represented

An Assurance Outcome is **always a pair — (dimension, outcome)** — never a bare level. There is no cross-dimension ordering and no aggregate "assurance score"; a design that computes one would destroy the distinction the business model exists to preserve.

| Dimension | Outcome ladder (weakest → strongest) | Today, measured |
|---|---|---|
| **Attribution** | Declared → Recorded → Corroborated → Strongly bound | Declared / Recorded |
| **Authorization Authenticity** | Asserted → Recorded → Corroborated → Independently established | Asserted / Recorded |
| **Independence** | Declared → Corroborated → Independently verified | Declared |

**Weakest-by-default is a modelling rule, not a convention:** absent an accepted assessment, the outcome *is* the weakest of its dimension (INV-ATTR-2). There is no "unknown" state that could be mistaken for something better.

---

## 7 · F-3 · Invariant-to-owner allocation

| Invariant | Owned by | How that concept protects it |
|---|---|---|
| **INV-ATTR-1** — attribution never grants authority | **the dependency direction** (§5), enforced at the boundary of Authority State | no gate reads an assurance outcome; assurance has no write path into grants |
| **INV-ATTR-2** — must be classified; default = declared | **Assurance Assessment** | absence of an accepted assessment *yields* the weakest outcome, rather than a null |
| **INV-ATTR-3** — outcome visible to consumers, in the correct dimension | **Disclosure** (the read side, F-4) | an outcome is publishable only as a (dimension, outcome) pair with its claim scope |
| *(candidate)* **INV-ATTR-4** — assurance requires a claim | **would live on Assurance Assessment** | an assessment without a Claim reference would be unrepresentable |
| *(candidate)* **INV-ATTR-5** — assurance cannot exceed evidence | **would live on Assurance Assessment** | the assessment rule mapping Evidence → Outcome would refuse an unsupported outcome |
| *(candidate)* **INV-ATTR-6** — assurance is non-transitive | **would live on Assurance Claim's scope + Disclosure** | outcomes are addressable only via their claim; no consumer-side inheritance path exists |

**Accommodation, not adoption:** the model is shaped so that 4/5/6 *could* be adopted without redesign — claims have identity, assessments reference claims and evidence, outcomes are claim-scoped. **They are not treated as requirements.**

---

## 8 · F-4 · Consumers as domain actors, and the read side

| Consumer | Consumes | Constraint it imposes |
|---|---|---|
| **Human reviewers** (PO/ARB, governance) | disclosed outcomes, to decide | outcomes must be legible without the assessor present; disclosure carries the claim's scope |
| **Reporting / resolver instruments** (the `AST-016` class) | outcomes, to surface | **P-5: may report, never gate, never evaluate.** An instrument surfaces `(dimension, outcome)` and its basis; it must not compute or upgrade one |
| **Future EKS knowledge graph** | outcomes attached to reused knowledge | **candidate INV-ATTR-6 matters most here** — assurance must not propagate along knowledge edges. Knowledge reuse is exactly where silent transitivity would occur |

**The read side is modelled, not incidental:** Disclosure is a first-class concept because three separate requirements live on it — INV-ATTR-3, the P-5 advisory boundary, and candidate INV-ATTR-6. A design that treated publication as a rendering detail would have no home for any of them.

---

## 9 · F-5 · Domain-event vocabulary

**Names and meanings only. No schemas — schemas stay behind the implementation gate.**

**Claim:** `AssuranceClaimAsserted` (a claim about an act now exists, with kind and scope) · `AssuranceClaimSuperseded` (replaced by a later claim; the original stands in history).
**Evidence:** `EvidenceObserved` (an artifact exists that may bear on claims) · `EvidenceAssociated` (bound to a specific claim — the deliberate act of relevance) · `EvidenceEvaluated` (its bearing has been judged) · `EvidenceSuperseded` (no longer relied upon; never deleted).
**Assessment:** `AssessmentProposed` (an assessor offers a conclusion) · `AssessmentAccepted` (the conclusion stands; the outcome becomes current) · `AssessmentRejected` (the outcome does not follow from the evidence) · `AssessmentSuperseded` (revised by a later assessment — **never rewritten**).
**Outcome / disclosure:** `OutcomeClassified` (a (dimension, outcome) pair is current for a claim) · `OutcomeDisclosed` (published where consumers see the record).
**Escalation:** `EscalationTriggerRaised` — *raises a question*. **There is deliberately no `RequirementChanged` event:** requirements change only by human decision (P-5/P-6), so no event in this vocabulary can effect one. The absence is the design.

---

## 10 · Mechanisms that could provide each outcome *(the P-3 addendum Architecture half — subject to Governance approval; nothing chosen)*

| Dimension → outcome | What a mechanism must establish | Candidate means |
|---|---|---|
| Attribution → *Recorded* | the claim was appended at act time, sequenced | **already provided** by the append-only transition log |
| Attribution → *Corroborated* | an evidence source outside the declarer's control is consistent | distinct lane identities (**P-4**); an independent execution trace |
| Attribution → *Strongly bound* | evidence the declarer cannot forge | attestation with keys the declarer does not hold |
| Auth. authenticity → *Recorded* | the act registered in the authority's own words at the time | **already provided** by `Grant.humanActRef` — extend, do not duplicate |
| Auth. authenticity → *Corroborated* | evidence outside the recorder's control | delivery of the act through a channel the recorder does not control |
| Auth. authenticity → *Independently established* | the authority's own unforgeable act | the authority signing its own act |
| Independence → *Corroborated* | separation evidenced outside the claimant's control | distinct lane identity (**P-4 caveat: evidences role separation, not independence of persons or decision authority**) |
| Independence → *Independently verified* | an independent party established it | a **P-2 Class-B** assessor examining the evidence |

**Architecture's honest determination:** *Corroborated* in every dimension requires **one prerequisite the platform does not have — distinguishable actor identity.** Attribution, authorization-authenticity and independence all stall at their second rung for the same reason. **This is the accepted G-2 root gap, and it is the single highest-leverage constraint in the model: one prerequisite unlocks the second rung of all three ladders.** Which mechanism supplies it is a Governance decision (P-3), and `KOS-GOV-ATTRIBUTION-001`'s P-1…P-6 remain the route.

**Priority signal honoured:** the business model names **C3 (human authority acts)** primary. C3 lives in the Authorization Authenticity dimension, whose *Recorded* rung already exists — so C3's gap is precisely the *Corroborated* rung, i.e. the same prerequisite.

---

## 11 · P-2 classes, the P-5 boundary, P-6 escalation — in the model

- **P-2 review classes** are a property of the **Assessor**, not of the claim. Only an assessor meeting **Class-B independence requirements** can produce an *Independently verified* Independence outcome. Modelling this on the assessor is what makes the outcome checkable rather than assertable.
- **P-5 advisory boundary** is realized structurally, not by policy text: instruments consume Disclosure and have **no write path** to claims, assessments, outcomes, grants or gates. Nothing in the model can automatically block.
- **P-6 escalation** is a one-way loop: `EscalationTriggerRaised` → Governance surfaces → **human decides** → optionally a new Assurance Requirement or a mechanism commission. **The model has no self-amending part** — §9's deliberate absence of a `RequirementChanged` event is where that is enforced.

---

## 12 · F-6 · Stage boundary

**Stage 1 — this document, complete:** ubiquitous language (F-1) · the domain concepts and their reasons · the context map against the *running* domain (F-2) · ownership and responsibility · dependencies and authority direction · dimension representation · invariant allocation (F-3) · consumers and the read side (F-4) · domain events (F-5) · mechanism-to-outcome mapping · P-2/P-5/P-6 representation.

**Stage 2 — deferred, gated on `KOS-ARCH-BASELINE-001` Phase A acceptance:** confirmation of the §3.3 placement against the *accepted* current-state baseline · whether Governance Assurance is confirmed as a supporting subdomain in the accepted context map · relationship to any context the baseline establishes that this model has not seen. **The lane neither stalls waiting for the baseline nor presumes its conclusions.**

## 13 · What this model does not do

No implementation · no technology · no schemas · no C4 · no aggregate design beyond the placement determination in §3.3 · **no adoption of INV-ATTR-4/5/6** · no change to any approved business requirement, outcome ladder, use category or trigger · no redesign of KnowledgeOS · **no contamination of `KOS-ARCH-BASELINE-001`** (Phase A discipline stands; nothing here is written back into the current-state reconstruction) · no bounded-context declaration for anything outside this subdomain · the Election Architecture pause is unaffected.

## 14 · Open questions for Human/ARB

| # | Question |
|---|---|
| **Q-1** | Approve the F-1 vocabulary decision (**Assurance Claim** as the generic; *Attribution* reserved to the dimension)? It requires a Governance vocabulary amendment to the approved model — **a wording change, no requirement altered.** |
| **Q-2** | Approve §3.3 — Governance Assurance as a **supporting subdomain realized as a third never-merged record**, extending the two-record rule rather than superseding it? |
| **Q-3** | Approve §5 — the **one-way dependency direction** as the architectural enforcement of INV-ATTR-1 (no gate may ever read an assurance outcome)? |
| **Q-4** | Confirm that **evidence is referenced, never owned** (§4) — the domain owns association and evaluation only? |
| **Q-5** | Accept §10's determination that **all three dimensions stall at their second rung on one shared prerequisite** (distinguishable actor identity = the G-2 root gap), and that this is the highest-leverage constraint? |
| **Q-6** | Confirm the Stage-2 gate (F-6) as stated, and whether Stage 2 should be a new assignment on this work item or its own work item. |

---

**Traceability:** `G-KOS-ATTRARCH-DESIGN` + `G-KOS-ATTRARCH-DESIGN-AMD1` (F-1…F-6) · human START act *"START S4-architecture-attr-target — 2026-08-16"* (record seq 3) · commission `2026-08-16-KOS-ATTR-ARCH-001-commission.md` · **Business Assurance Model rev 3, APPROVED** (`5ab3b4e6`) §1–§8 · P-1 `02f813ae` · P-2 `8de09453` · P-3 `6ad39fa0`+addendum · P-4 `fdfd6470` · P-5 `defc8a22` · P-6 `30976423` · accepted root gap `487fce74` · scope DDD review `a230b08f` (source of F-1…F-6) · `KOS-GOV-ATTRIBUTION-001` ADP (**self-authored input, `Declared`**) · running domain re-derived directly from `workflow-state.php` (`assertTransitionAllowed`, grant/transition structure) and the live records · `ES-005.4` · `AIP-14` · `R-34`.

---

> # PROPOSED — NOT APPROVED · returns for Human/ARB design approval
