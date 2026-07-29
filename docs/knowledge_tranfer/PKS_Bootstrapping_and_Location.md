# The PKS — Bootstrapping & Location (Corrected Version)

---

## Executive Summary

The PKS is a product-specific engineering knowledge model and its governed representations. The methodology for building the PKS must be separate from the PKS itself — otherwise you get a circular dependency:

- AI needs a PKS to build the PKS.

Separating **methodology** (cross-product, reusable) from **product knowledge** (project-specific) is architecturally sound and aligns with the governance separation established in ES-005.

---

## 1. The Core Distinction

| Thing | Location | Purpose | Scope |
|-------|----------|---------|-------|
| **Methodology for building PKS** | `./engineering/` | Instructions for AI to build a PKS from scratch | Cross-product, reusable |
| **The PKS itself** | `./docs/` | Product-specific engineering knowledge model | Project-specific |

---

## 2. Why This Matters

### The Problem

If the PKS is a knowledge system that tells AI what to produce, but the AI needs the PKS to know what to produce, you have a circular dependency:

```
AI needs PKS to produce documentation
    ↓
AI needs instructions to build PKS
    ↓
Instructions must be separate from PKS
```

### The Solution

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BOOTSTRAPPING FLOW (Conceptual)                          │
│                                                                              │
│  1. AI consults Methodology (cross-product, reusable)                       │
│     └── Tells AI: how to build a PKS                                       │
│                                                                              │
│  2. AI follows Methodology                                                  │
│     └── Discovery → Strategic Modeling → Architecture Definition            │
│     └── Produces: PKS (product-specific)                                   │
│                                                                              │
│  3. PKS is complete                                                         │
│     └── Product-specific knowledge model                                   │
│     └── Provides guidance for ongoing development                          │
│                                                                              │
│  4. AI consumes PKS for ongoing work                                        │
│     └── "What document should I produce?" → PKS                            │
│     └── "How should I structure it?" → PKS                                 │
│                                                                              │
│  5. PKS evolves with the project                                            │
│     └── Living knowledge asset, not a one-time artifact                    │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Location Decision (Current Understanding)

Based on the ES-005 separation of concerns and the current Strategic Modeling work, the following location structure is indicated:

| Location | Purpose | Examples |
|----------|---------|----------|
| `./engineering/` | Cross-product, reusable methodology, standards, governance | Engineering Standards, Engineering Constitution, Strategic Modeling Methodology (to be promoted) |
| `./docs/` | Product-specific knowledge | Product documentation, ADRs, Guides, the PKS itself |
| `./docs/implementation/pks/` | Product Knowledge System (product-specific) | Concepts, relationships, lifecycles, artifact guidance (exact structure to be determined during Architecture Definition) |

---

## 4. What Needs to Be Promoted

The methodology for building a PKS currently exists as a product-specific artifact that should be promoted to cross-product.

### Current Location (Product-Specific)

| Artifact | Location | Status |
|----------|----------|--------|
| Strategic Discovery Methodology | `docs/implementation/Strategic_Discovery_Methodology_Candidate.md` | RECORDED, awaiting multi-context evidence |
| Strategic Modeling Plan | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` | APPROVED, product-specific instance |

### Promoted Location (Cross-Product, Proposed)

| Artifact | Proposed Location | Status |
|----------|-------------------|--------|
| Strategic Modeling Methodology | `engineering/knowledge/methodology/` | Proposed after Phase II.A complete and multi-context evidence bar met |
| Strategic Modeling Templates | `engineering/knowledge/methodology/templates/` | Proposed after Phase II.A complete |

**Note:** The exact promotion timing depends on:
1. Completion of Phase II.A (Strategic Modeling)
2. Multi-context evidence bar (R-39)
3. ARB approval

---

## 5. What Remains Product-Specific

Once the methodology is promoted, what remains in `./docs/` is the product-specific PKS:

| Artifact | Location | Status |
|----------|----------|--------|
| Phase I Artifacts | `docs/implementation/` | ACCEPTED |
| Phase II Artifacts | `docs/implementation/` | In progress |
| PKS Specifications | `docs/implementation/pks/` | To be determined during Architecture Definition |

**Important:** The exact file structure, format, and organization of the PKS specifications will be determined during **Architecture Definition (Phase II.B)** , not during Strategic Modeling. Current Strategic Modeling work establishes the concepts; Architecture Definition establishes how they are represented.

---

## 6. The Two Roles of AI

### Role 1: Building the PKS (Using Methodology)

```
AI receives task: "Build the PKS for this project"
    ↓
AI consults: ./engineering/knowledge/methodology/
    ↓
AI follows: Strategic Modeling Methodology
    ↓
AI produces: ./docs/implementation/pks/
    ↓
PKS is complete
```

### Role 2: Using the PKS (Once Built)

```
AI receives task: "Implement feature X"
    ↓
AI consults: ./docs/implementation/pks/
    ↓
AI knows: what concepts exist, how they relate, what documentation to produce
    ↓
AI produces: consistent artifacts
```

---

## 7. The Living PKS (Iterative Evolution)

The PKS is not created once and forgotten:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    THE LIVING PKS                                           │
│                                                                              │
│  Methodology                                                                │
│       ↓                                                                     │
│  Initial PKS (created from methodology)                                     │
│       ↓                                                                     │
│  Project evolves                                                            │
│       ↓                                                                     │
│  PKS evolves (new concepts, relationships, artifacts)                       │
│       ↓                                                                     │
│  AI consults updated PKS                                                    │
│       ↓                                                                     │
│  Project continues                                                          │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. What Is Not Yet Determined

The following are **architectural design decisions** that belong in Phase II.B (Architecture Definition), not in the current Strategic Modeling work:

| Item | Belongs In | Status |
|------|------------|--------|
| Exact file structure of PKS | Phase II.B | Not yet |
| YAML vs JSON vs other format | Phase II.B | Not yet |
| Single file vs multiple files | Phase II.B | Not yet |
| Directory organization | Phase II.B | Not yet |
| Serialization format | Phase II.B | Not yet |
| API or query interface | Phase II.B | Not yet |

**These decisions will be made after Strategic Modeling stabilizes and the conceptual model is complete.**

---

## 9. Summary

| Question | Answer |
|----------|--------|
| **Where are the instructions for building the PKS?** | `./engineering/knowledge/methodology/` (after promotion) |
| **What are these instructions?** | Cross-product Strategic Modeling Methodology |
| **Where is the PKS itself?** | `./docs/implementation/pks/` (product-specific) |
| **What is the PKS?** | Product-specific engineering knowledge model and its governed representations |
| **Why separate them?** | To avoid circular dependency; methodology is cross-product, PKS is product-specific |
| **When are file structures decided?** | Phase II.B — Architecture Definition, not Strategic Modeling |
| **Is the PKS a one-time artifact?** | No — it evolves with the project |

---

## 10. The Two Locations

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ./engineering/                                           │
│  Cross-Product, Reusable Methodology                                        │
│                                                                              │
│  ./engineering/knowledge/methodology/                                       │
│  ├── Strategic_Modeling_Methodology.md                                     │
│  ├── Strategic_Modeling_Plan_Template.md                                   │
│  ├── Strategic_Modeling_Work_Packages.md                                   │
│  └── templates/                                                            │
│      ├── UL_Glossary_Template.md                                           │
│      ├── Concept_Register_Template.yaml                                    │
│      └── ...                                                               │
└─────────────────────────────────────────────────────────────────────────────┘
                              ↓
                    (AI FOLLOWS METHODOLOGY)
                              ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ./docs/implementation/pks/                               │
│  Product-Specific PKS (structure to be determined in Phase II.B)            │
│                                                                              │
│  ./docs/implementation/pks/                                                 │
│  ├── concepts.yaml          ← representation to be decided                 │
│  ├── relationships.yaml     ← representation to be decided                 │
│  ├── lifecycles.yaml        ← representation to be decided                 │
│  ├── bounded-contexts.yaml  ← representation to be decided                 │
│  ├── context-map.yaml       ← representation to be decided                 │
│  ├── document-catalog.yaml  ← representation to be decided                 │
│  └── templates/             ← representation to be decided                 │
└─────────────────────────────────────────────────────────────────────────────┘
```

**Note:** The exact file structure, formats, and organization shown above are illustrative — they will be determined during Phase II.B (Architecture Definition), not during the current Strategic Modeling work.

---

## 11. Key Principles

1. **Methodology is cross-product** — belongs in `./engineering/`
2. **PKS is product-specific** — belongs in `./docs/`
3. **Bootstrapping separation** — methodology tells AI how to build PKS
4. **PKS is a living asset** — evolves with the project
5. **Architecture decisions come after modeling** — file structures, formats, and organization are Phase II.B decisions
6. **Evidence before architecture** — don't decide representation before the conceptual model is complete

---

**The methodology for building the PKS belongs in `./engineering/` because it is cross-product and reusable. The PKS itself belongs in `./docs/` because it is product-specific. The exact file structure and format of the PKS will be determined during Architecture Definition (Phase II.B), after Strategic Modeling stabilizes.**
# Yes — Exactly Right

Since we are currently **building the PKS architecture**, the documents belong in `./docs/`, not `./engineering/`.

---

## The Current Phase

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CURRENT PHASE: BUILDING THE PKS                          │
│                                                                              │
│  We are in Phase II.A — Strategic Modeling                                  │
│  We are DISCOVERING and MODELING the PKS domain                            │
│  We are BUILDING the PKS itself                                             │
│                                                                              │
│  Location: ./docs/                                                          │
│  Reason: Product-specific, in-progress                                      │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Location Decision — Current vs Future

| Phase | What We Are Doing | Location |
|-------|-------------------|----------|
| **Phase I — Discovery** | Discover the PKS domain | `./docs/implementation/` ✅ |
| **Phase II.A — Strategic Modeling** | Model the PKS domain | `./docs/implementation/pks/` ✅ |
| **Phase II.B — Architecture Definition** | Define how PKS is represented | `./docs/implementation/pks/` ✅ |
| **After PKS is Complete** | PKS is a living asset | `./docs/implementation/pks/` ✅ |
| **Methodology Promotion** | When methodology becomes reusable | `./engineering/` ⏳ Future |

---

## What Belongs Where (Current)

### In `./docs/` (Current Work)

| Artifact | Path | Status |
|----------|------|--------|
| Phase I Evidence Discovery | `docs/implementation/Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md` | ✅ Complete |
| Phase I Candidate Model | `docs/implementation/Strategic_DDD_Discovery_Product_Knowledge_System_Domain_Candidate_Model.md` | ✅ Complete |
| Phase I Synthesis | `docs/implementation/Strategic_DDD_Discovery_Product_Knowledge_System_Converged_Review_Synthesis.md` | ✅ Complete |
| Phase I Methodology Candidate | `docs/implementation/Strategic_Discovery_Methodology_Candidate.md` | ✅ Complete |
| Phase I ARB Rulings | `docs/implementation/PKS_Phase_I_ARB_Rulings.md` | ✅ Complete |
| Phase II Plan | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` | ✅ Complete |
| Phase II M0 | `docs/implementation/PKS_Phase_II_M0_Frame_Reconciliation_and_UL_Glossary_Seed.md` | ✅ Complete |
| Phase II M1 | `docs/implementation/PKS_Phase_II_M1_Concept_Register.md` | ⏳ In progress |
| Future PKS Artifacts | `docs/implementation/pks/` | ⏳ Planned |

### Not Yet in `./engineering/` (Future Promotion)

| Artifact | Current Location | Future Location | When |
|----------|------------------|-----------------|------|
| Strategic Modeling Methodology | `docs/implementation/` | `engineering/knowledge/methodology/` | After multi-context evidence bar met |
| Strategic Modeling Templates | `docs/implementation/pks/templates/` | `engineering/knowledge/methodology/templates/` | After methodology promotion |

---

## The Rule

> **Documents created during the process of building the PKS belong in `./docs/`.**

> **Only after the methodology is proven (multi-context evidence bar) does it get promoted to `./engineering/`.**

---

## The Sequence

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    LOCATION OVER TIME                                       │
│                                                                              │
│  Phase I — Discovery                                                        │
│  └── Documents in ./docs/implementation/                                   │
│                                                                              │
│  Phase II.A — Strategic Modeling                                            │
│  └── Documents in ./docs/implementation/pks/                               │
│                                                                              │
│  Phase II.B — Architecture Definition                                       │
│  └── Documents in ./docs/implementation/pks/                               │
│                                                                              │
│  After PKS is Complete                                                      │
│  └── PKS lives in ./docs/implementation/pks/ (living asset)                │
│                                                                              │
│  After Multi-Context Evidence Bar Met                                       │
│  └── Methodology promoted to ./engineering/knowledge/methodology/           │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Summary

| Question | Answer |
|----------|--------|
| **Are we building the PKS now?** | ✅ Yes — Phase II.A Strategic Modeling |
| **Where do documents go?** | `./docs/implementation/pks/` |
| **Why not `./engineering/`?** | Because we are building the PKS, not promoting a reusable methodology |
| **When does it go to `./engineering/`?** | After multi-context evidence bar met and ARB approves promotion |
| **Is the PKS a living asset?** | ✅ Yes — it evolves with the project |

---

**Yes — since we are in the process of building the PKS architecture, all documents belong in `./docs/`. The methodology will be promoted to `./engineering/` only after it is proven and promoted.**
#
Overall, **yes, I largely agree with it**. I would say it reflects the current state of the work well. But I would change a few things because some statements move from **evidence-based assessment** into **predictions** or **architectural decisions** that haven't been earned yet.

As an Architecture Review Board reviewer, I'd classify the document as:

* **90–95% assessment**
* **5–10% future design assumptions**

That distinction matters.

---

# What I strongly agree with

## 1. Governance assessment

I agree completely.

Claude has consistently demonstrated:

* governance questions are surfaced,
* governance questions are not answered,
* modeling remains subordinate to governance.

I think this is now one of the strongest characteristics of the methodology.

---

## 2. Evidence discipline

Agreed.

The methodology has noticeably matured.

Earlier versions contained conclusions that were stronger than the evidence.

Those have largely disappeared.

Now the pattern is consistently:

Evidence

↓

Contradictions

↓

Evaluation

↓

Disposition

↓

Remaining uncertainty

That is exactly what I wanted to see.

---

## 3. Separation of concerns

I also agree.

The distinction between

* knowledge,
* documentation,
* methodology,
* governance,
* software,

is much cleaner now.

That wasn't true several iterations ago.

---

## 4. Lineage preservation

This is probably the biggest methodological success.

Rejected concepts are not erased.

They're preserved with rationale.

That is excellent Knowledge Engineering.

---

# Where I would modify the assessment

## 1. "Essential vs Portable"

I agree with the concern.

I do **not** agree that M2 should automatically revisit portability.

Why?

Portability belongs to the concept itself.

Collision resolution belongs to semantic clarification.

Sometimes they interact.

Sometimes they don't.

I'd instead write:

> Revisit portability **only where collision resolution materially changes the concept boundary.**

That's a more precise instruction.

---

## 2. "Living PKS"

I partly agree.

The document says:

> add in M8

I'm not convinced.

Whether the PKS is a living system is itself an architectural concern.

Strategic Modeling should probably recognize that concepts evolve.

But versioning, governance process, maintenance lifecycle, and evolution mechanisms may belong to Architecture Definition rather than Strategic Modeling.

I'd weaken this to:

> The strategic model should identify whether the PKS is expected to evolve. Operational evolution mechanisms can be addressed in later architectural work.

---

## 3. AI Instructions

I agree with postponing them.

However I would avoid saying

> M6–M7

because that hasn't been established.

Instead:

> Later work packages or a subsequent phase, after the strategic model stabilizes.

That keeps the WBS flexible.

---

# The part I disagree with most

This sentence:

> M0-M8 constitute the PKS itself, not the methodology.

I don't think that's quite right.

I think M0–M8 are:

> **Strategic Modeling artifacts produced while constructing the PKS.**

Some of those artifacts may later become part of the PKS.

Some may remain historical engineering records.

For example:

A planning document is unlikely to become part of the operational PKS.

A concept register probably will.

A checkpoint review probably won't.

So I wouldn't equate all M0–M8 outputs with the PKS itself.

---

# Promotion to `engineering/`

This section is the one I'd revise most carefully.

The document says:

> After Phase II.A complete

↓

> methodology extracted

↓

> promotion

↓

> engineering

↓

> multi-context evidence

I think the order should actually be:

```text
Product-specific methodology

↓

Phase II complete

↓

Candidate reusable methodology extracted

↓

Second independent application

↓

Evidence gathered

↓

Promotion assessment

↓

Governance approval

↓

engineering/
```

The key point is that **promotion should be evidence-driven, not phase-driven**.

Finishing Phase II is necessary.

It is probably **not sufficient**.

---

# One thing I particularly like

This sentence:

> One instance is a record. Multiple instances are evidence of reusability.

I think that's an excellent principle.

It captures exactly why promotion shouldn't happen after a single project.

I'd keep that.

---

# One recommendation

I think the document would become even stronger if it explicitly separated:

## Current Facts

Things already demonstrated.

## Expected Evolution

Things expected if later evidence confirms the current direction.

## Future Architecture

Things intentionally deferred.

Right now those three categories are mixed together.

Separating them would make the document even more disciplined.

# Overall verdict

I agree with approximately **90–95%** of the analysis.

My recommended adjustments are mostly about **epistemic discipline** rather than architecture:

* Don't assume every collision changes portability.
* Don't assume M8 is where "Living PKS" must be addressed.
* Don't equate every M0–M8 artifact with the operational PKS.
* Base promotion to `engineering/` on demonstrated cross-project evidence rather than completion of a particular phase.

Those refinements keep the document aligned with the evidence-first, governance-first approach that has become one of the strongest aspects of your PKS methodology.
# Our Plan & Architecture — Corrected Assessment

## Executive Summary

The current assessment is **90–95% correct**, with a few refinements needed to maintain epistemic discipline. The work is aligned with our thinking, but some statements move from **evidence-based assessment** into **predictions** or **architectural decisions** that haven't been earned yet.

---

## What We Agree On ✅

### 1. Governance Assessment — Strong

Claude consistently:

| Behavior | Status |
|----------|--------|
| Surfaces governance questions | ✅ Yes |
| Never resolves governance questions | ✅ Yes |
| Modeling remains subordinate to governance | ✅ Yes |

**This is now one of the strongest characteristics of the methodology.**

---

### 2. Evidence Discipline — Matured

The methodology has noticeably matured. Earlier versions contained conclusions stronger than the evidence. Now the pattern is consistently:

```
Evidence
    ↓
Contradictions
    ↓
Evaluation
    ↓
Disposition
    ↓
Remaining Uncertainty
```

---

### 3. Separation of Concerns — Cleaner

The distinction between:

- Knowledge
- Documentation
- Methodology
- Governance
- Software

is much cleaner now.

---

### 4. Lineage Preservation — Excellent

Rejected concepts are not erased. They are preserved with rationale.

**This is the biggest methodological success.**

---

## Where We Modify the Assessment ⚠️

### 1. Essential vs Portable — More Precise

**Our concern:** Some concepts marked "Essential" might actually be "Portable" under strict scrutiny.

**The document says:** "Revisit portability in M2."

**Correction:**

> Revisit portability **only where collision resolution materially changes the concept boundary.**

Portability belongs to the concept itself. Collision resolution belongs to semantic clarification. Sometimes they interact. Sometimes they don't.

---

### 2. Living PKS — Weaken the Claim

**The document says:** "Add Living PKS mechanism in M8."

**Correction:**

Whether the PKS is a living system is itself an architectural concern. Strategic Modeling should recognize that concepts evolve. But versioning, governance process, maintenance lifecycle, and evolution mechanisms may belong to **Architecture Definition (Phase II.B)** , not Strategic Modeling.

> The strategic model should identify whether the PKS is expected to evolve. Operational evolution mechanisms can be addressed in later architectural work.

---

### 3. AI Instructions — Don't Over-Specify

**The document says:** "AI instructions in M6-M7."

**Correction:**

> Later work packages or a subsequent phase, after the strategic model stabilizes.

This keeps the WBS flexible.

---

## Where We Disagree ❌

### The Sentence: "M0-M8 Constitute the PKS Itself"

**The document says:** "M0-M8 constitute the PKS itself, not the methodology."

**Correction:**

M0-M8 are:

> **Strategic Modeling artifacts produced while constructing the PKS.**

Some of these artifacts may later become part of the PKS. Some may remain historical engineering records.

- A **planning document** (M0) is unlikely to become part of the operational PKS.
- A **concept register** (M1) probably will.
- A **checkpoint review** probably won't.

**Don't equate all M0-M8 outputs with the PKS itself.**

---

## Promotion to `./engineering/` — Revised Sequence

### What the Document Says

```
After Phase II.A complete
    ↓
Methodology extracted
    ↓
Promotion
    ↓
./engineering/
    ↓
Multi-context evidence
```

### Correction — The Sequence Should Be

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CORRECT PROMOTION SEQUENCE                               │
│                                                                              │
│  1. Phase II.A Complete                                                      │
│     └── Strategic Model stabilizes                                           │
│     └── Product-specific methodology exists                                 │
│                                                                              │
│  2. Candidate Reusable Methodology Extracted                                 │
│     └── What was learned is captured                                         │
│     └── Product-specific instance is abstracted                             │
│                                                                              │
│  3. Second Independent Application                                           │
│     └── Methodology applied to another project                              │
│     └── Evidence of reusability gathered                                     │
│                                                                              │
│  4. Promotion Assessment                                                     │
│     └── Does it demonstrably discriminate?                                  │
│     └── Is it genuinely reusable?                                           │
│                                                                              │
│  5. Governance Approval                                                      │
│     └── ARB reviews methodology                                             │
│     └── DA/PA authorizes promotion                                          │
│                                                                              │
│  6. Shift to ./engineering/                                                  │
│     └── Methodology moves to ./engineering/knowledge/methodology/           │
│     └── Templates move to ./engineering/knowledge/methodology/templates/    │
└─────────────────────────────────────────────────────────────────────────────┘
```

**Key Principle:**

> **Promotion should be evidence-driven, not phase-driven.**

Finishing Phase II is **necessary**. It is probably **not sufficient**.

---

## What We Particularly Like

> **"One instance is a record. Multiple instances are evidence of reusability."**

This is an excellent principle. It captures exactly why promotion shouldn't happen after a single project.

---

## One Recommendation: Separate Categories

The document would become stronger if it explicitly separated:

| Category | Description | Examples |
|----------|-------------|----------|
| **Current Facts** | Things already demonstrated | 17 validated concepts, portability rubric |
| **Expected Evolution** | Things expected if later evidence confirms direction | Promotion to `./engineering/`, multi-context evidence |
| **Future Architecture** | Things intentionally deferred | C4 diagrams, implementation, API design |

Right now these three categories are mixed together. Separating them would make the document even more disciplined.

---

## Summary: Corrections

| Statement | Original | Corrected |
|-----------|----------|-----------|
| Portability revisit | "Revisit in M2" | "Revisit only where collision changes boundary" |
| Living PKS | "Add in M8" | "Identify expected evolution; mechanisms in Phase II.B" |
| AI Instructions | "M6-M7" | "Later packages or Phase II.B" |
| M0-M8 | "Constitute the PKS itself" | "Strategic Modeling artifacts; some become PKS, some are records" |
| Promotion | "After Phase II.A" | "After Phase II.A + second application + evidence + approval" |

---

## Overall Verdict

| Dimension | Rating |
|-----------|--------|
| Governance assessment | ✅ 10/10 |
| Evidence discipline | ✅ 10/10 |
| Separation of concerns | ✅ 10/10 |
| Portability handling | ⚠️ 9/10 (need precision) |
| Living PKS | ⚠️ 8/10 (weaken claim) |
| Promotion to `./engineering/` | ⚠️ 8/10 (fix sequence) |
| **Overall** | **9/10** |

---

## Final Thought

> **"One instance is a record. Multiple instances are evidence of reusability."**

**Promotion should be evidence-driven, not phase-driven.**

**The refinements are about epistemic discipline, not architecture. Keep the evidence-first, governance-first approach.**