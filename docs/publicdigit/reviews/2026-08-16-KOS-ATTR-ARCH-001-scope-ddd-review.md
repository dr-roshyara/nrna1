# KOS-ATTR-ARCH-001 — DDD Review of the Commission Scope (pre-START)

**Requested by:** PO/ARB, 2026-08-16, before the START of `S4-architecture-attr-target`.
**Performed by:** Governance.

## P-2 disclosure (mandatory shape)

```
Reviewer involvement:            this Governance session DRAFTED the scope under review
Review scope:                    internal consistency with the approved baseline and decisions;
                                 DDD completeness of the questions the scope poses
Not independently established:   whether the scope is CORRECT — a producer reviewing its own
                                 drafting cannot certify it; this is a Class-A review with
                                 advisory analysis, not independent verification
```

---

## Verdict in one sentence

**The scope is sound in its boundaries and prohibitions, but it under-specifies four DDD questions the first output must answer — and it carries one name collision and one sequencing dependency that should be visible before the START.**

None of the findings invalidates the commission. All are refinements to the *must-determine* list.

---

## F-1 · The ubiquitous language has a name collision — "Attribution" means two things

In the approved model, **"Attribution Claim" is the generic concept** — *"what is asserted about who acted / who authorized / how independently"* — covering all three claim kinds. But **"Attribution" is also the name of the first of the three dimensions** (who performed it), sibling to Authorization and Independence.

So the language currently says: *an Attribution Claim may be about Authorization.* That is exactly the kind of overload DDD exists to remove, and it will surface the moment Architecture names its first aggregate.

**Recommendation:** require the domain model to **resolve the overload as part of the ubiquitous language work** — e.g. the generic concept becomes *Assurance Claim* (or another name Architecture defends), while *attribution* remains the dimension. The choice is Architecture's; the obligation to choose should be in the scope.

## F-2 · The biggest gap: no context-map question against the RUNNING domain

The scope treats the design space as new. It is not empty. **A working domain model already runs in production:** *Work Item · SessionAssignment · Role · Grant · Transition · humanAct · Handoff/token · mutation owner · Session Registry ≠ Authority State (two never-merged records).* That vocabulary is the programme's de facto ubiquitous language, enforced by the engine daily.

The scope never asks how the new concepts relate to it. Yet this is the decisive strategic question:

- Is **Governance Assurance a new bounded context**, an **extension of the workflow-engine context**, or a **supporting subdomain**?
- Is an *Assurance Claim* an **attribute of a Transition**, or **its own concept with its own lifecycle** referencing transitions as evidence?
- Does the existing **Grant** already embody an *authorization-authenticity claim* (its `humanActRef` is exactly an Asserted/Recorded authorization claim under the model)?
- The engine's **two-record separation** (session registry ≠ authority state) is a boundary decision already made and enforced — the target design must either honor it or explicitly argue its supersession.

**Recommendation:** add to must-determine: **a context map relating the Governance Assurance concepts to the existing workflow-engine domain**, with an explicit answer to "new context / extension / supporting subdomain," and a mapping of Claim/Evidence/Assessment onto or beside Transition/Grant. *(Per ES-005.4: consume or extend existing concepts; never create a second.)*

## F-3 · Invariant→owner allocation is implied but not required

The standing discipline is explicit: *resolve ownership of every invariant before protecting it.* The scope asks for "ownership of state, knowledge, evidence, authority" generally, but never requires the specific artifact that settles arguments later: **an allocation of each invariant to the concept/context that owns its protection** — INV-ATTR-1/2/3 (established), and where INV-ATTR-4/5/6 *would* live if adopted (accommodate-not-adopt).

**Recommendation:** require an **invariant→owner allocation** as part of the first output.

## F-4 · Consumers are absent from the model — and INV-ATTR-3 makes them domain actors

INV-ATTR-3 obliges the assurance outcome to be *visible to consumers of the record*. Candidate INV-ATTR-6 (non-transitive assurance) bites precisely at consumption, and its stated weight is EKS downstream reuse. But the scope never asks **who the consumers are as domain actors** — human reviewers, the resolver/reporting instruments, a future EKS knowledge graph — or what the read-side of an assurance outcome looks like.

**Recommendation:** add to must-determine: **the consumer contexts of assurance outcomes** (who reads them, in what context, with what obligations), since two approved rules (INV-ATTR-3, P-5's advisory boundary) and one candidate (INV-ATTR-6) all live on the consumption side.

## F-5 · No domain-event question, in an event-shaped domain

The whole domain is append-only: claims are *superseded, never rewritten*; the running engine is a transition log with a fold. The natural DDD expression of such a domain is its **event vocabulary** (claim recorded · evidence associated · assessment superseded · …). The scope asks for lifecycles but not for events.

**Recommendation:** add: **the domain-event vocabulary** as part of the domain model (names and meanings — no schemas, which stay behind the implementation gate).

## F-6 · Sequencing dependency, visible rather than discovered later

Boundaries are "hypotheses to test" — but `KOS-ARCH-BASELINE-001` (Phase A, current-state reconstruction) is **still ACTIVE**, and the scope correctly forbids presuming its conclusions. Consequence: **the domain and responsibility model can proceed now; bounded-context *confirmation* may need to wait for the baseline's acceptance.**

**Recommendation:** state the two stages explicitly in the commission: *Stage 1 — domain & responsibility model (proceeds now) · Stage 2 — context-map confirmation against the accepted baseline (gated on Phase A acceptance).* Otherwise the lane will either stall waiting or quietly presume.

## F-7 · Minor: mechanisms question is correctly placed but should be explicitly last

"What mechanisms can provide the required assurance levels" is Architecture's half of the P-3 split and belongs in scope — but it is solution space. The first-output ordering already subordinates it; adding "after the domain model is stable" makes the intent unmistakable.

---

## What the review found sound (stated so silence is not ambiguity)

The prohibitions (no implementation · accommodate-not-adopt · no baseline overwrite · not-the-KnowledgeOS) · the mechanical role-set exclusion of implementation · the first-output ordering (domain model before C4/technology) · the hypotheses-not-conclusions framing of §7 · the return-for-design-approval loop · the Election-pause boundary.

---

## Recommendation to the PO/ARB

**Amend the grant once, before START** — a single amendment adding F-1…F-5 as required questions of the first output and F-6's two-stage sequencing (F-7 folds into wording). Grants are append-only, so this is a new registered act (`AMD1`), not an edit.

**Alternative:** START as-is and pass this review to the Architecture session as advisory input. Workable, but then the sharpened questions are advice, not scope — and today's evidence says write the requirement into the record the startup check reads.

**Human decision required:** amend then START · or START as-is with this review attached.

---

**Traceability:** grant `G-KOS-ATTRARCH-DESIGN` (scope as registered) · commission `fac1971a` · assurance model rev 3 §1/§4/§7 (claim chain · lifecycles · candidate areas) · `workflow-state.php` (the running domain vocabulary: two never-merged records, SessionAssignment, grant, fold) · P-2 `8de09453` (the disclosure shape used above) · P-3 addendum (requirement/mechanism split → F-7) · ES-005.4 (→ F-2) · the standing invariant-ownership discipline (→ F-3) · `KOS-ARCH-BASELINE-001` ACTIVE (→ F-6)
