# Layer Verification Rule (Engineering Platform — methodology module)

**Class:** Engineering Platform methodology module · **Owner:** Decision Authority
**Status:** 🟡 **PROPOSED — NOT ADOPTED.** Authority requires an explicit Decision Authority ruling (**R-34**: authority is created only by explicit issuance; nothing becomes a ruling by inference from praise or suggestion). **Until issued, this module is non-binding and must be cited as a *recommended heuristic*, never as authority for what is permitted.**
**Nature:** a **methodology module about the governance of change** — it classifies *which abstraction a proposed change actually modifies*, and therefore *whose authority is engaged*. It is **not** architecture, **not** a tactical-DDD rule, and it decides no design question.
**Relationship to existing modules:** complements `DDD_Tactical_Governance_Principles.md` (tactical design) without overlapping it. That module governs *what to build*; this one governs *who may change it, and at which level*.
**Provenance:** derived during EPIC-004 WP-7 (finding **G-1**), refined across successive ARB iterations. Promotion to a reusable artifact requested by the ARB, 2026-08-01.
**Refinement:** 🧊 **FROZEN 2026-08-01** — no further refinement without new evidence. *(Amended three times after freezing, all at ARB direction: (1) the `Produces` column and the one-category invariant in §3; (2) **opening** moved from Delivery to Planning; (3) the categories re-derived from **lifecycle transitions**, with the artifact demoted to *evidence*; (4) **authority** made an explicit element, since categories do not act. **Four post-freeze amendments is itself worth noting: the freeze bound the AUTHOR against unprompted refinement, and every amendment came at ARB direction with a stated reason — but a module amended four times was, on the evidence, frozen before it was finished.** Recorded rather than glossed: **the freeze binds the author, not the Authority** — and because the module is still unadopted, the ruling will cover this final text.)* **Frozen ≠ adopted, and the distinction is load-bearing:** *freezing* ends **my** refinement of the text; *adoption* is **the Decision Authority's act** and has not occurred. **A frozen module that is not adopted is still non-binding.** The stopping rationale is the ARB's own: successive iterations had begun improving **expression** rather than adding **explanatory power**.

---

## 1. The four-level model

| Level | Role | Authority | May engineering change it? |
|---|---|---|---|
| **1 · Business Policy** | **decides WHAT** | Business / Decision Authority | ❌ never |
| **2 · Architectural Invariant** | **protects WHAT** | ARB | ❌ never |
| **3 · Mechanism** | **decides HOW** | **engineering, within the invariant** | ✅ **the only substitutable level** |
| **4 · Implementation** | **realizes HOW** | engineering | ✅ yes |

**What the model is for:** it converts *"is this substitutable?"* from a judgement into a **lookup**. A level-3 collision dissolves without touching the model; **a level-2 collision cannot be engineered around — it returns to the ARB.** Knowing which situation you are in **before** searching for a fix is the entire value.

**The failure mode it addresses:** plans routinely record an **invariant and its mechanism in the same sentence**, so the two are read as one binding thing. Split them before treating either as binding.

## 2. The rule

> ### **Can this layer change WITHOUT changing the layer above it?**
> ### **YES → it belongs at this layer. NO → you are modifying the wrong abstraction.**

### 2.1 The dual — what a failure *means*

> **If changing this layer forces a change above it, you have discovered an ARCHITECTURAL dependency, not an implementation dependency.**

**Why the dual matters:** the rule alone tells a reviewer *that* something is wrong; the dual tells them **why**, and therefore **where to go next**. A failure is not a defect to be worked around — it is **evidence that the change is larger than it was presented as**, and it names the authority that must be engaged. **A failing rule escalates; it does not block.**

### 2.2 Proposal checklist — applied *before* implementation

The rule evaluates changes; this applies it to **proposals**, which is where it is cheapest to act on:

1. **Which layer is intended to change?**
2. **Which higher layer would also change?**
3. **If any higher layer changes → ESCALATE before implementation.**

**Apply it to any change that feels like "just a technical choice"** — that is precisely how both historical defects (§4) presented themselves.

## 3. Companion principle — modelling causality vs modelling authority

> ### **Model causality with dependencies. Model authority with state transitions.**
>
> **Dependencies explain *why*. States record *what exists now*.**

**Added before adoption** *(ARB, 2026-08-01 — the module was already PROPOSED, so this amendment is part of what is put for ruling, not something added afterwards)*.

**Why it belongs in this module rather than standing alone:** §1–2 classify *which abstraction a change touches*; this classifies *how to model the governance around it*. Both answer **"whose authority is engaged?"** — one for a change, one for a process.

**What it prevents, concretely:** a dependency graph can say *"execution authorization requires plan approval."* **A state machine says *which states may legally coexist*** — making `execution AUTHORIZED ∧ plan AWAITING APPROVAL` **unreachable** rather than merely discouraged. **The first relies on someone remembering the rule; the second makes the illegal state unrepresentable.**

**Companion — classify on TWO independent axes.** **State category:** *Architecture* (design decisions) · *Planning* (plan approval) · **Delivery** (work-package transitions) · *Execution* (engineering authorization). **Transition type:** *Approval* (admits a delivered thing) · *Ratification* (confirms an existing thing's reading) · *Planning* (binds a forward commitment) · *Execution* (unlocks work).

**Neither axis is derivable from the other IN PRINCIPLE** — an *Architecture Governance* transition can be an **Approval** (approving a **new** ADR) or a **Ratification** (confirming the reading of an **existing** one), and a type can recur across categories. **But check the direction you are claiming:** in the WP-6→WP-7 sample, *Type* happened to be derivable **from** Category while Category was **not** derivable from Type. **One-directional non-derivability is not independence** — say which direction the evidence supports. **A state machine that labels neither hides which authority owns each transition and what kind of act it is** — which is how a single "yes" comes to mean four different things in one minute-book. *(Applied in the WP-6→WP-7 session: labelling exposed that the Planning Governance state is the only one not owned by the ARB — EP-01's separation made visible, and the reason its vote is separate.)*

**Canonical governance categories** *(this is their single home — cite, never restate)*.

> ### The organizing idea is the LIFECYCLE TRANSITION, not the artifact.
>
> **A governance category owns exactly one business transition. The durable artifact is the EVIDENCE that the transition occurred — it exists *because* the transition happened, never the other way round.**
>
> ```
> Lifecycle transition → Governance category → Governance authority → Durable artifact
>     (what happened)      (who governs it)      (who exercised it)     (what remains)
> ```
>
> **Categories do not act. Authorities act.** A category says *whose kind of decision this is*; only an authority can exercise it. Omitting that step makes the model read as though a category could decide something, which no category can.
>
> **Categories are derived from lifecycle events, not from terminology.** Asking *"which label sounds right?"* is how the model drifts; asking *"which business transition is being exercised?"* is how it holds.

| Category | **Lifecycle transition it owns** | Typical authority | Durable artifact *(evidence)* |
|---|---|---|---|
| **Architecture Governance** | a design question is **decided or interpreted** | ARB | an **approved architectural decision** |
| **Planning Governance** | a bounded work package is **opened**, or the plan bounding it **approved** | Decision Authority *(plans)* · ARB *(packages)* | an **approved work package** |
| **Execution Governance** | engineering is **authorized to begin** approved work | ARB | an **authorized engineering activity** |
| **Delivery Governance** | completed work is **accepted and closed** | ARB | an **accepted implementation baseline** |

*"Typical" is deliberate: the category does not determine the authority. **Planning Governance alone spans two** — EP-01 plan approval is the Decision Authority's, opening a work package is the ARB's — which is exactly why authority must be named per transition rather than inferred from the category.*

### The invariant

> **One lifecycle transition → one governance category → one exercising authority → one durable governance artifact.**
>
> **If any element cannot be identified, the governance model is incomplete.** Two distinct transitions cannot share a category; a category leaving no durable artifact records nothing; and a transition with no named authority **did not actually occur** — it was only described.

**Every governance decision must answer four questions:**

| Question | Answers with |
|---|---|
| **What happened?** | the lifecycle transition |
| **Who governs it?** | the governance category |
| **Who exercised it?** | the **authority** |
| **What remains?** | the durable artifact |

### ⚠️ A naming hazard this creates — flagged before it costs anything

**"Lifecycle transition" and "transition type" are different axes with confusingly similar names.**

| Axis | Answers | Values |
|---|---|---|
| **Lifecycle transition** | *what happened to the work* | opened · authorized · accepted · decided |
| **Transition type** | *what kind of authority act was performed* | Approval · Ratification · Authorization · Acceptance |

**They are independent** — Architecture Governance takes **Approval** for a new ADR and **Ratification** for confirming an existing one's reading, from the same lifecycle transition. **Keep both; do not collapse them.** *(Recorded now because a pair of near-identical names in a shared definition is precisely the shape that mis-filed R-52 and R-60.)*

### Root-cause record — model evolution, not human error

**This model was not refined for style. It was refined because operational evidence showed the previous definition could not classify repeated behaviour consistently:**

```
Two rulings (R-52, R-60) opened work packages and were filed under Delivery
        ↓
Both were internally consistent; the governance process was followed
        ↓
The canonical definition read "acceptance AND PROGRESSION"
        ↓
"Progression" admitted "opening" — two lifecycle events under one category
        ↓
Definition refined: opening is a PLANNING transition; Delivery is acceptance and CLOSURE
        ↓
Future rulings inherit the corrected language from this single source
```

**The repeated misclassification followed from the shared definition, not from the rulings.** That is the case for one canonical home: **a loose word in a shared definition reproduces itself in every artifact that cites it** — here, twice, before anyone noticed.

**Opening creates INTENT. Acceptance creates COMPLETION.** Different events; different categories.

### When this vocabulary may change again

**Only when operational evidence shows the model cannot classify repeated behaviour consistently — the same bar that produced this refinement. Never for style.** Any proposed category must demonstrate its transition and its artifact; if it cannot, it is not a category.

**Naming note:** prefer **Delivery Governance** over "Programme Governance" — *programme* still reads as portfolio, funding or schedule governance, whereas the definition above fixes where delivery begins and ends: **it starts at a bounded work package and ends at its acceptance or progression.**

**Limit:** like §2, this is a **modelling heuristic, not a gate.** It improves how governance is expressed; it enforces nothing.

## 4. Evidence base — and its limits, stated

The platform's own **Methodological Fitness Rule** sets the standard a criterion must meet: *"a criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed ceremonial until evidence shows otherwise."* **The rule is offered for adoption because it meets that standard — it both accepts and rejects.**

| Case | Layer intended | Layer above changes? | Rule's verdict | Correct? |
|---|---|---|---|---|
| **G-1** — import the provider's port → **consumer-side port** | Mechanism | ❌ *"MAD has exactly one canonical home" untouched* | ✅ **ACCEPT** — a legitimate mechanism substitution | ✅ *(prospective)* |
| **AP-2** — a MAD key added to a retention config | presented as Mechanism | ✅ **destroys** the invariant | ❌ **REJECT** — an **invariant breach dressed as a mechanism choice** | ✅ *(retrospective)* |
| **AP-1** — `max(1,$days)` clamping in an adapter | presented as Implementation | ✅ overrides *"Q-2 decides durations"* (**two levels up**) | ❌ **REJECT** — an implementation edit reaching level 1 | ✅ *(retrospective)* |
| Renaming an adapter's config keys | Implementation | ❌ | ✅ ACCEPT | ✅ |

**The significant fact:** **AP-1 and AP-2 passed every automated gate** — Architecture suite, Deptrac, PHPStan, the widened regression suite — and were caught only by a human preservation review. **The rule flags both.** It reaches a defect class the executable gates demonstrably do not.

### Limits — recorded, not minimised

- **One work package.** Thinner than the DDD module's evidence base at *its* promotion, which was itself recorded as a governance exception (**R-39**).
- **Two of three validating cases are retrospective.** Retrospective fit is weaker evidence than prospective prediction: the defects were known when the rule was written. **Only G-1 was prospective.**
- **Level assignment is a judgement, not a computation.** The rule presupposes the four levels are correctly assigned; it detects *crossing* them, not *mislabelling* them. **A change misfiled at the wrong level will pass.**
- **Unenforceable by tooling.** This is a review heuristic. It belongs in review, not in CI.

### Expected validation before the exception closes

**Two prospective applications outside EPIC-004**, at least one of which **rejects or escalates** a proposal that would otherwise have proceeded. **If, after that, the rule has never escalated anything, it is ceremonial by the platform's own fitness rule and should be retired** rather than retained out of attachment.

## 5. Non-scope

**This module does not:** decide where code lives · select tactical patterns · assign bounded-context ownership · substitute for Deptrac, architecture tests or PHPStan (it reaches a *different* defect class) · grant any authority to the person applying it. **Its only output is a classification and, where warranted, an escalation.**

## 6. Adoption

**Requested of the Decision Authority.** If adopted:

- record the ruling in the platform rulings register (as **R-39** was for the DDD module), including the thin-evidence exception and the validation expectation in §4;
- index it in `engineering/governance/STANDARDS_INDEX.md`;
- consider a non-blocking review reminder, matching the precedent of `.claude/scripts/ddd-principles-reminder.sh` (AST-014).

**None of the above has been done.** Indexing and rulings are governance acts and are **not** performed by the artifact that requests them.

---

**Traceability:** EPIC-004 WP-7 — implementation guard commission (**G-1**) · architecture–enforcement alignment commission (four-level model; rule; retrospective validation) · WP-6 findings **AP-1** (fail closed / no substituted business value) and **AP-2** (one home per parameter) · `DDD_Tactical_Governance_Principles.md` §1 Methodological Fitness Rule (the standard applied to this rule in §4) and §4 Architectural Silence Principle (rejections are recorded decisions) · **R-34** (authority only by explicit issuance) · **R-39** (precedent for promotion on a thin evidence base as a recorded exception). **Non-binding until issued.**
