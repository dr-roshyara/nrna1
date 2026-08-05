# PKS — Knowledge Integrity Model

| | |
|---|---|
| **Kind** | **Integrity model** — it defines **what architecturally correct knowledge means** for a governed artifact in this program. *(Renamed from "Knowledge Quality Model" on ARB instruction 2026-07-30: **"quality" reads as readability, consistency, and completeness; what this document defines is semantic, authority, temporal, lineage, and context CORRECTNESS — integrity, not quality.**)* It defines nothing about *how* to review (that is the Review Method) and nothing about *how a reviewer must behave* (that is the Review Discipline). |
| **Responsibility (singular, per Knowledge Cohesion)** | **Criteria only.** This document names the architectural qualities a knowledge artifact is evaluated against. It contains no procedure, no ordering, no reviewer obligations, and no findings. |
| **Substance issued by** | The Authority (Senior Knowledge Architect) — qualities **A–E** 2026-07-30, quality **F** 2026-07-30; the four knowledge acts and the five temporal classes are the Authority's own formulations. |
| **This document authored by** | The reviewing assistant, 2026-07-30 — elaboration, evidence mapping, the derived rules, and the restatement determination for A/B. |
| **Lifecycle status** | **PROPOSED** |
| **Adoption** | **PENDING — no adoption record exists.** *(Correction applied on ARB instruction: the earlier header read "Issued by the Authority … In force immediately", which **conflated the Authority's recommendation to create this model with an Authority issuance of it, and conflated authorship with adoption.** That is the same defect class as CCP-1 §12.6's — review outcome ≠ adoption — recurring here as **recommendation ≠ issuance ≠ adoption.**)* |
| **Binding status** | **In force for the reviewing assistant's own conduct** under the standing scope note (reviewer-facing instructions take effect on issuance). **Binding on future review *commissions* requires adoption and follows the PMR → MCA → CDR path.** |
| **Siblings** | `PKS_Knowledge_Contract_Review_Method.md` (the method — what to examine, in what order) · `PKS_ARB_Review_Discipline.md` (reviewer conduct — Rules 1–18) |
| **Dependency position (ARB, 2026-07-30)** | **This model depends on NOTHING.** `Integrity Model ← Method ← Discipline` is acyclic and the direction is load-bearing: **this document must be revisable without reading either sibling.** Mentions of objectives or Rules below are **back-references — coverage evidence, not dependencies** (each quality is fully defined without them), per the dependency/back-reference distinction in the Discipline's dependency rule. |
| **Admission filter (inherited, STRENGTHENED 2026-07-30)** | ***A quality becomes part of the governed model only after REPEATED operational evidence demonstrates that its absence allowed defects to escape existing controls.*** Every quality below names its evidence and its count. **One instance admits a CANDIDATE (or, for constitutional severity, a PROVISIONAL control that operates immediately); repetition is what promotes it to GOVERNED.** Canonical statement and the graded ladder: the Discipline's §Admission ladder — where **quality E is disclosed as the model's thinnest rung at n=2.** |

---

## The five qualities

### A. Semantic Integrity

**Does the artifact preserve exactly the same meaning as the governing strategic model?**

Rejects: semantic drift · reinterpretation · vocabulary drift · accidental redesign.

**Status: RESTATEMENT, not a new quality.** It is what Review Method objectives 1–2 already examine, and what Discipline Rules 6 and 10 already protect *(back-reference: coverage evidence for the restatement determination — quality A stands without it)*. **Recorded here for completeness of the model, not as an addition.**

**Evidence of the defect class:** **VR-2** — *Verdict* → *judgment* by prose-smoothing · **AFV-F1** — a role separation read as a context boundary · **F-2.3** — a relationship described as a boundary.

---

### B. Authority Integrity

**Can every normative statement be traced to a governing authority?**

Rejects: inferred authority · implied authority · authority amplification · unsupported constraints.

**Status: RESTATEMENT, not a new quality.** Review Method objectives 3–4 and Discipline Rules 8 and 18 already carry it *(back-reference, as above)*. **Recorded for completeness.**

**Evidence:** **KC-3** — §8 authorized implementation by implication · **F-3.2** — a clause that may effect promotion · **IBC-M11** — a governed identifier cited in support of a requirement it does not establish (*cited ≠ mandated*).

**Refinement — TWO KINDS OF AUTHORITY, admitted 2026-07-30 on ARB instruction:**

| Kind | What the artifact does | Example |
|---|---|---|
| **NORMATIVE authority** | **Defines or constrains.** Downstream work must conform | AD-1 (promoted) · the M6 dispositions · DAR-1 |
| **EVIDENTIAL authority** | **Attests.** It is authoritative *about what was verified*, and constrains nothing | **AFV-1 (promoted)** · C4-2 · KBI-1 · ERV-1 |

**The rule this produces: *promotion does not imply normative authority.*** **AFV-1 is promoted and governs nothing** — it is the authoritative assurance record of the Model → Architecture transformation, and it is **not architectural law, implementation guidance, governance policy, or design authority.**

**Why it belongs under Authority Integrity rather than beside it:** the defect it prevents is a **promoted assurance record being cited as a constraint** — the same shape as *cited ≠ mandated* (IBC-M11), one level up. *ADR §3.1.1 already separates constraint-defining artifacts from records; this names the property that separation turns on.*

---

### C. Knowledge Integrity — **REFINEMENT with evidence**

**Does the artifact introduce new knowledge?** The refinement is that *"knowledge amplification"* is one label over **four distinct acts**, which must be distinguished because they carry different legitimacy:

| Act | Definition | Legitimate in a governed artifact? |
|---|---|---|
| **Representation** | Restates governed content faithfully | ✅ Always — this is what assembly artifacts do |
| **Synthesis** | Combines governed content into a new *statement* that adds no new fact | ✅ Yes, when marked as derived |
| **Interpretation** | Reads governed content as supporting a claim it does not state | ⚠️ **Only if disclosed as interpretation.** Undisclosed, it is the most common defect in this record |
| **Invention** | Asserts content the governed model does not contain | ❌ **Never** — regardless of disclaimers |

**Why the four-way distinction earns its place — all four are instantiated in real findings, and all four were previously filed under one label:**

| Act | Instance |
|---|---|
| Representation | **M8's `Assembly:` class** — integration without assertion, correctly done |
| Synthesis | **ADR §3.1.2** — three assertions composed from governed content, adding no fact |
| Interpretation | **AFV-F1** — *"AC-1 cannot self-issue"* derived from a role-separation rule, presented as derivation rather than interpretation |
| **Invention** | **KC-2** — an invented domain event · **KC-1** — component realizations over an architecturally undefined region |

**The rule the distinction produces:** **a disclaimer changes an artifact's authority, never its correctness.** *A non-normative statement is still a statement* — which is why KC-1 and KC-2 were removed rather than relabelled.

**General form, admitted GOVERNED 2026-07-30 on ARB instruction** *(n = 4 across THREE artifact kinds: KC-1/KC-2 in a knowledge artifact · PUB-1/2/3 in a publication artifact · RET-1 and RET-2 in a retrospective)*:

> ***An artifact's authority is determined not only by its stated purpose but also by the strength of the claims it makes. Disclaimers define INTENDED responsibility; wording determines EXERCISED responsibility.***

**It explains a defect class that recurs in every artifact kind reviewed so far:** M8's `Assembly:` marker meant *asserts nothing beyond the sources* while three statements asserted appraisals · RET-1's §9 header said *none is proposed as a rule* while four pattern names were written as rules · RET-1's §11 said *nothing here is a decision* while item 1 ranked the candidates. **In each case the declaration was correct and the wording exceeded it.**

**Two remedy classes, distinguished because they are not interchangeable** *(ARB refinement, 2026-07-30)*: **empirical over-generalization** — a claim extended beyond its evidence — is repaired by **adding scope**, and the claim survives at its true size · **normative over-generalization** — a claim that stops describing and starts prescribing — **cannot be repaired by scoping** (*"in Phase II, a control is only proven by…"* is still a prescription) and must be **reclassified** as a recommendation or routed to the governing process. ***Scoping fixes an overreach of extent; only reclassification fixes an overreach of kind.***

---

### D. Temporal Integrity — **REFINEMENT with evidence**

**Every knowledge statement belongs to exactly one temporal class.** This extends the binary *permanent vs transient* into five, because maintenance obligations differ per class:

| Temporal class | Definition | Maintenance obligation |
|---|---|---|
| **Constitutional truth** | Holds by governed decision until superseded forward | None — supersession only, never edited in place |
| **Timeless fact** | True independently of program state | None |
| **Point-in-time** | True as of a stated date | **Must carry its date and a pointer to the authoritative current state** |
| **Execution state** | Reflects where the program currently stands | **Belongs in state records, not in governed artifacts** |
| **Historical evidence** | Records what was once claimed or decided | **Belongs in a record artifact; retained, never presented as current** |

**Evidence — all five instantiated:**

| Class | Instance |
|---|---|
| Constitutional truth | The four M6 dispositions · *"nothing may cite a view as authority"* |
| Timeless fact | *frozen ≠ promoted* · *"certified" applies to the methodology* |
| **Point-in-time** | **KC-9b** — ADR §1.1's state table, which falsifies itself on promotion |
| **Execution state** | The ARB's CONTEXT caution — review progress removed from durable context |
| **Historical evidence** | **KC-8** — §14 and Appendix C embedded as artifact body |

**The rule:** **an artifact may contain any temporal class, provided each statement's class is identifiable.** Defects arise from *unmarked* class, not from mixture.

**Derived rule, admitted GOVERNED at n=2 on ARB instruction 2026-07-30** *(the Authority: "this deserves to become permanent guidance … it is no longer specific to M8 — it is a publication principle")*:

> ***An artifact may state where things STOOD; it must not state where things STAND unless it is maintained.***

**Evidence — two instances in two different artifact kinds, which is what took it past one:** **AF-1** (a **decision record**: *"the next PKS act is the opening of Strategic Modeling"*, false since the day after issuance) · **PUB-4/5/6** (a **publication artifact**: the DAR-1 folds described as pending after execution, and a completed checkpoint listed as a next step). **Applies to decision records, publication reports, architecture overviews, executive summaries — anything describing a current state.**

**The remedy is a choice, not a correction:** either **maintain** the statement, or **date-mark it and point at the authoritative current state.** *The defect is the unmarked present tense, not the passage of time.*

**Corollary for REVIEW RECORDS specifically — admitted GOVERNED at n=3 on ARB instruction 2026-07-30:**

> ***Review records are historical records. They are amended for CURRENCY, never rewritten for OUTCOME.***

**The ARCHIVAL PRINCIPLE that completes it** *(Authority, 2026-07-30 — KBI-R1; **archival, NOT methodology**, so it does not route through PMR → MCA → CDR)*:

> ### **A historical artifact should be ANNOTATED when its present-state classification ceases to describe the current state — provided that doing so does not alter the historical explanation of later governance acts.**

**Bounded on both sides, which is what gives it force:** it is **not** *"rewrite history"* (the artifact, its findings and its reasoning are untouched) and **not** *"leave stale operational status unqualified"* (no reader discovers by accident that a **STOP** no longer stops anything). ***Annotation preserves both.***

**The discriminator is NOT the tense of the sentence — it is whether the sentence is LOAD-BEARING in a later act's explanation:**

| The text is… | Currency finding? | Evidence |
|---|---|---|
| **A plan** | ❌ No — *a plan reads as a plan after execution* | CCP-1 |
| **A causal explanation of a later act** | ❌ No — *changing it would weaken the causal record* | ERV-1 (explains CCP-1 Amendment 1) · KBI-1's KBI-F1 (explains AFV-1) |
| **A present-state / readiness classification** | ✅ **Yes** — *the assessment stays correct; the current-state claim lapses* | **AFV-R1 · KBI-R1** |

*Three artifact kinds, one rule, two opposite outcomes.*

**Corollary — REACHABILITY, not placement** *(candidate, archival, n=2 — AFV-1 satisfied it incidentally, KBI-1 violated it and was repaired; Authority reformulation 2026-07-30)*:

> ### **A historical annotation fulfills its purpose only if a reader encountering the lapsed claim will reasonably encounter the qualifying annotation BEFORE BEING MISLED.**

**Mechanisms deliberately left open:** adjacent banner · header note · inline qualifier · cross-reference · document metadata. ***The governance concern is not WHERE the annotation sits; it is whether the reader is protected from an outdated operational interpretation.*** **Supersedes the narrower first formulation (*"placement IS the remedy"*), which prescribed a presentation technique — a principle governing the OUTCOME outlives the presentation conventions of any one format.**

---

### AVAILABILITY of authority is a precondition for its exercise *(Authority recognition, 2026-07-31)*

> ### **There is no live constitutional act to perform where the requested Authority act has already been performed.**

**The AFV-F4 commission offered ACCEPT · REJECT · DEFER, and the actual state was ORTHOGONAL to all three.** ***That is not merely a missing vocabulary term: availability of authority is itself a precondition for exercising authority.***

**⚠️ RECORDED AS A PRINCIPLE, EXPRESSLY NOT AS A CONTROL — and the distinction is the programme's own:**

| Recorded | NOT recorded |
|---|---|
| **The principle** — availability precedes exercise | **A mandatory AVAILABILITY CHECK on every governance action** |
| *A principle describes; recording it costs nothing* | ***A control mandates activity, and mandating one requires evidence its absence let defects escape*** |

**Evidence standing: TWO cases — one reviewability precondition (IBC-1: the object was absent) and one disposition-availability precondition (AFV-F4: the act was already performed).** ***Enough to record the observation. NOT enough to conclude that every governance action requires a formally modelled availability check. The evidence base is small and the direction it points is not yet the direction it establishes.***

### The progression of QUESTIONS the programme asks *(Authority observation, 2026-07-31)*

| Phase | Question |
|---|---|
| **Early** | ***Is the artifact correct?*** |
| **Intermediate** | ***Is the REVIEW of the artifact correct?*** |
| **Current** | ***Is the constitutional ACT itself still AVAILABLE to be performed?*** |

***The third is qualitatively different: the process is no longer evaluating only artifacts but the VALIDITY OF THE GOVERNANCE ACTIONS REQUESTED OF IT.***

**⚠️ This is a separate axis from the four governance maturities recorded above — those classify what is GOVERNED; this classifies what is ASKED. It is not a fifth maturity, and it is not evidence that the methodology is finished: open candidates and operational evidence remain.** *What it does show is a process able to decline work for CONSTITUTIONAL reasons supported by evidence, rather than on procedural rules or preference.*

### FOUR STRENGTHS of evidence that a rule governs *(Authority, 2026-07-31 — repository-scope determination)*

| Strength | Meaning |
|---|---|
| **RECORDED** | *somebody wrote it* |
| **REFERENCED** | *later artifacts rely on it* |
| **IMPLEMENTED** | *execution follows it* |
| **BINDING** | *Authority adopted it* |

> ### ***These are increasing strengths and must not be compressed into a single phrase. "Operationally enforced" collapsed all four and asserted two that the evidence did not support.***

**A rule can be recorded and referenced without being implemented; implemented without being binding; and binding without any of its instruments having run recently.** *Demonstrated: ES-005.1 is recorded ✅, referenced ✅, partially implemented on one dated execution ⚠️, and not demonstrated as binding ⛔ — four different answers to what one phrase had treated as one question.*

**⚠️ Terminology caution attached to the ladder** *(Authority, 2026-07-31)*: **avoid *"enforced"* / *"enforcement"* when reporting IMPLEMENTATION evidence — the word implies BINDING authority and re-collapses the distinction the ladder exists to make.** **Preferred form: *execution evidence shows AT LEAST ONE implemented use of the rule; the current evidence does NOT establish CONTINUOUS OR GENERAL implementation.*** *Bounds the extent, which "implementation evidence exists" left open.* ***Observed in practice: the reviewer's own correction of "operationally enforced" reintroduced "enforcement" one sentence later — the collapse is easy to repeat while explicitly guarding against it.***

### VERIFICATION USUALLY NARROWS *(observed across five cases, 2026-07-31)*

> ### **Several independent reviews have shown the same TENDENCY: replacing broad summary statements with finer evidence classifications.**

**In repository scope · AFV-F4 · M7-F1 · IBC-1 · ES-005.1 the result of additional verification was not a stronger conclusion but a more precisely bounded one.** *Stated as a tendency across cases, not as a law — which is what the evidence supports.*

| Discipline the pattern rests on | |
|---|---|
| **1** | Avoid collapsing different kinds of evidence into a single stronger claim |
| **2** | Separate factual observations from governance conclusions |
| **3** | ***Increase precision even when it WEAKENS the original statement*** |

***Accuracy taking precedence over rhetorical certainty is the property; five cases are encouraging evidence and not a demonstration that verification always narrows. Held provisionally, like every other explanatory model in this framework.***

### SCOPE vs CLASSIFICATION are different governance objects *(Authority insight, 2026-07-31)*

| Object | Governs | Instance |
|---|---|---|
| **Repository SCOPE / structure** | *what may exist* | **R-37 — no new top-level folders** |
| **Repository CLASSIFICATION / meaning** | *what existing things ARE* | **ES-005.1 — the three-concern separation** |

> ### ***One governs STRUCTURE, the other governs MEANING. They were dated the same day, tied to the same migration, and hosted in adjacent artifacts — and conflating them is how "the repository is the PKS domain" came to be asserted as though a structural freeze had settled a classification question.***

### THREE kinds of "already governed" *(observed 2026-07-31)*

| Situation | Instance |
|---|---|
| **Already DISPOSED** | **AFV-F4** — the requested Authority act had already been performed |
| **Already PARTIALLY governed** | **Repository scope** — two sources, different authority strengths, neither absent |
| **Already REVIEWED but no longer reproducible** | **IBC-1** — the review survived; its object did not |

***Three different constitutional situations, all requiring the same first question: **does governance already exist here?*** **NOT elevated into methodology — the evidence base is modest, and it is recorded as a characteristic of this programme rather than a required stage.**

### DIRECT vs DERIVED traceability of implementation wording *(Authority recognition, 2026-07-31 — M7-CC1)*

**Two kinds of implementation wording are BOTH lawful; they differ in their traceability PATH, not in their legitimacy:**

| Kind | Source | Example |
|---|---|---|
| **DIRECT** | the Authority disposition | *"recorded, unassigned"* · *"placed nowhere"* |
| **DERIVED** | the commissioned implementation design, constrained by existing governance | *"and not participants"* — from the MCR-1 constraint · *"unclassified"* — from the artifact's own convention |

> ### ***A stronger model than pretending everything is copied directly from the Authority act. An execution report claiming "all traceable" without distinguishing the two asserts more than it verified — and conceals the one place where implementation judgment was exercised.***

**Consequence for execution records: state the path for each element.** *Where derived wording cannot be traced through the commissioned design to the disposition, it is scope creep — and only the distinction makes that testable.*

### VERIFICATION as a first-class closing activity *(Authority observation, 2026-07-31)*

| Early form | Current form |
|---|---|
| Decision → Edit | **Evidence → Decision → Implementation design → Execution → VERIFICATION** |

**Demonstrated at M7-CC1: derive constraints · execute · verify byte-level invariants (SHA-256 on the protected member set) · record direct-vs-derived traceability.**

**⚠️ NOT elevated into formal methodology** — *a recurring operational pattern, held on the same terms as constraint derivation: an effective practice is not yet evidence that the methodology is incomplete without it.*

### The four-instrument separation, exercised across artifact types *(Authority summary, 2026-07-31)*

| Instrument | Its act |
|---|---|
| **Review** | **establishes EVIDENCE** |
| **Authority** | **determines WHAT should change** |
| **Change-control commission** | **derives a COMPLIANT IMPLEMENTATION MECHANISM within existing governance constraints** |
| **Execution** | **performs ONLY that commissioned mechanism** |

***Note what the third instrument is for: the Authority never decided "insert a scope clause in §2". It decided "make the contested memberships visible". Everything after that required architectural reasoning constrained by existing governance — which is why WHAT and HOW are different acts.***

**Distinguished from the programme's more tentative observations: this separation has been exercised REPEATEDLY across DIFFERENT ARTIFACT TYPES** *(AD-1 · C4-1 · the CDR · SDM v1.1 · M7)*, **which is a stronger evidence base than a pattern observed in one kind of act.**

**⚠️ And the associated causal claim is held proportionally:** *these cases show how collapsing decision and implementation design **can allow** a correct decision to produce a non-compliant implementation — three examples support the observation without establishing a universal causal law.*

### HOW this framework improved — the refinement progression *(Authority observation, 2026-07-31)*

```text
Specific case  →  Tentative abstraction  →  Counterexample  →  Narrower abstraction  →  Explicit boundary
```

| Instance | Where it narrowed |
|---|---|
| **The three stabilizers** | recorded as observations, then bounded — *governance principles, not laws; WITHDRAWN as their lawful exit* |
| **PMR-8 · 9 · 10** | routed rather than adopted — *an endorsement is not an adoption* |
| **Confirmed vs strengthened** | *strengthened requires NEW INDEPENDENT EVIDENCE* |
| **Status / rationale / ontology** | separated, then the ontology→disposition entailment softened to a dependency |
| **The adjacent-level collapse model** | *promising explanatory model, not a universal rule* — with an explicit refutation condition |

> ### ***The progression resists turning every useful observation into permanent methodology. Each step ADDED a boundary rather than a construct — which is why sixteen reviews produced ten emphases and one contract.***

**⚠️ Applied recursively, as the discipline requires: THIS pattern is itself held provisionally.** *Five instances explain how the framework has improved so far; they do not establish that it always will.* ***Recording it as a rule would violate the discipline it describes.***

### Standing caution *(Authority, 2026-07-31)*

> **Whenever a useful explanatory model emerges, keep treating it as PROVISIONAL until it demonstrates predictive value across additional cases.**

***The failure mode it guards against is not error but premature permanence: a model adopted on its explanatory fit becomes unfalsifiable the moment it is treated as settled, because every later case is then read through it.***

### ADOPTION changes BINDING STATUS, not content *(Authority, 2026-07-31 — governance vocabulary)*

> ### **Adoption changes a rule's BINDING STATUS. It does not change its CONTENT.**

**Three distinct constitutional acts, routinely conflated:**

| Act | Changes | Instance |
|---|---|---|
| **EDITING** a rule | its **content** | *requires its own authority; forbidden during adoption* |
| **ADOPTING** a rule | its **binding status** — and therefore its **enforceability**, not current practice | **PMR-9 · PMR-10 at CDR-R2; ES-005's rules pending** |
| **IMPLEMENTING** a rule | whether **execution follows** it | *ES-005.1 has implementation evidence its host document's status does not confer* |

***A rule can be implemented before it is adopted, and adopted without any change in behaviour. Adoption is about ENFORCEABILITY.***

### PRESENTATION is not CONSTITUTIONAL REPRESENTATION *(Authority, 2026-07-31 — BRM-1)*

> ### **Derived, clearly non-authoritative views that aid navigation are permitted. What is forbidden is a materialized document carrying governed statements as a SECOND AUTHORITATIVE HOME.**

| Permitted | Forbidden |
|---|---|
| tooling · indexes · navigation aids · generated views · onboarding guides | **a second authoritative home for any governed statement** |

***The distinction is what keeps the decision narrow: BRM-1 rejects a change to WHERE AUTHORITY LIVES, not a change to how the baseline is READ. Usability objections to a reference-defined baseline are therefore answerable without reopening the architecture.***

### RETIRED is not DEFERRED *(BRM-1, 2026-07-31)*

> ### ***A deferral holds an act open pending EVIDENCE. Where no evidence short of reversing a decision would revive the act, it is RETIRED — and reversing the decision is a NEW ACT, not the discharge of a deferral.***

**Demonstrated: SDM-EXT-1's premise was that the baseline should be materialized. BRM-1 decided against it, so no discharge condition exists.** *Recorded because "deferred" was the available and wrong label — a deferred act implies a route back that this one does not have.*

### THE BURDEN OF A PROPOSED ARCHITECTURAL CHANGE *(Authority, 2026-07-31 — BRM-1)*

> ### **The burden is on the proposed change to demonstrate benefits that JUSTIFY INTRODUCING A NEW GOVERNANCE RISK NOT PRESENT IN THE CURRENT ARCHITECTURE.**

| The question is NOT | The question IS |
|---|---|
| *Is the change useful?* | ***Are the benefits sufficient to justify the risk the change introduces?*** |

**⚠️ Not a presumption that the incumbent architecture is superior.** ***It is the ordinary principle that the existing architecture does not have to justify its own existence every time a change is proposed. The proposed change must justify itself.***

### HISTORICAL CONSISTENCY is evidence of DIRECTION, not an argument for CONTINUING it *(Authority, 2026-07-31)*

> ### ***Otherwise governance becomes PATH-DEPENDENT simply because previous commissions happened to move one way.***

**Demonstrated at BRM-1: five acts all reducing duplicated text were recorded as evidence that a direction had emerged — and expressly not as an argument that the direction should continue.**

### A DEFERRED rule is SILENT, not weakly supportive *(Authority determination, 2026-07-31 — BRM-1)*

> ### **A deferred rule does not bind, and it also cannot be CITED AS AUTHORITY for the position it would have supported.**

***A deferred rule is not a weak rule pointing one way. It is silent. Citing it in either direction would grant governance force to something the Authority expressly declined to make binding.***

**Demonstrated: ES-005.4 *"Never a Copy"* would align with retaining Model B — and is unavailable to that argument, having been deferred hours earlier.**

### EVIDENCE INFORMS a decision; it does not REPLACE one *(Authority, 2026-07-31)*

> ### **Where a question is a governance CHOICE rather than a matter of fact, NO EVIDENCE ALONE CAN SETTLE IT — but evidence still improves the decision's quality.**

| Evidence CAN supply | Evidence CANNOT supply |
|---|---|
| the available interpretations · the consequences of each · compatibility with existing rules | **which interpretation the Authority intends** |

***Demonstrated at ES-005.4: its own text says "ARB to confirm scope", so investigation can enumerate the candidate scopes and their consequences and still not determine which is meant. The weaker claim — "no evidence I can gather would settle it" — understated evidence's role by implying it is useless where it is merely insufficient.***

### ⭐ THE CLOSING META-PRINCIPLE — carried forward to any future cycle *(Authority, 2026-07-31)*

> ## **EVERY GOVERNANCE ACT SHOULD ESTABLISH ONLY THE STRONGEST CLAIM THAT ITS EVIDENCE PRESENTLY SUPPORTS.**

**It is not a methodology step. It is a QUALITY CRITERION — and it explains almost every refinement made in Phase II:**

| Refinement | The over-claim it prevented |
|---|---|
| **confirmed vs strengthened** | more support than the evidence grew |
| **dissolved vs superseded** | error where the world had merely moved |
| **document vs rule vs binding** | adoption where only recording had occurred |
| **rationale vs ontology** | a changed proposition disguised as a changed reason |
| **readiness vs authorization** | a derived state performing a decision |
| **implementation vs adoption** | enforceability inferred from practice |
| **observation vs methodology** | a pattern promoted to a rule |
| **disposition vs terminal state** | one case promoted to an ontology |

***Every one narrowed a claim. None changed an outcome. That is the criterion's signature: it costs rhetoric and buys accuracy.***

### The scope of what was completed *(Authority, 2026-07-31)*

| This programme has… | It has NOT… |
|---|---|
| **exhausted the questions it set out to answer** | **finished governance** |

***Different statements. Any further substantial governance work should begin as a NEW CYCLE with a NEW MANDATE — triggered by new evidence, new architectural needs, or operational experience — not by reopening questions already brought to lawful terminal states.***

### WHAT COMPLETION MEANS *(Authority definition, 2026-07-31)*

> ### **Completion does NOT mean "every possible question has been answered." It means EVERY QUESTION NECESSARY FOR THIS BASELINE HAS BEEN CONSTITUTIONALLY RESOLVED, ADOPTED, DEFERRED, OR RETIRED.**

**FOUR lawful terminal states, and a question in any of them is CLOSED for the baseline's purposes:**

| State | Meaning | Instance |
|---|---|---|
| **RESOLVED** | decided on its merits | *repository scope → Outcome B* |
| **ADOPTED** | made binding | *PMR-9 · PMR-10 · ES-005.1/.2/.3* |
| **DEFERRED** | held open on a stated condition | *PMR-8 · ES-005.4 · PMR-2 · PMR-3* |
| **RETIRED** | premise no longer holds; no route back short of a new decision | *SDM-EXT-1 · CON-F2* |


***A baseline whose open questions are all in one of these four states is complete. A baseline pursuing exhaustiveness is not more complete — it is merely unfinished in a different way.***

**⚠️ A candidate FIFTH state, recorded as an OBSERVATION and expressly NOT admitted** *(Authority correction, 2026-07-31)*:

> **SIA-1 reached the outcome *no present constitutional act is required* — which is none of the four above.**

**Standing: ONE clear case. Enough to USE the disposition; NOT enough to elevate it into the general governance ontology.** ***Whether it should become a general terminal state must be evaluated separately if similar cases recur.***

***Recorded because the reviewer promoted it on a single case at the moment of declaring completion — the same premature-promotion error the programme had resisted for the constraint-derivation stage, the "already governed" heuristic, the adjacent-level collapse model and the escape-filter blind spot. Each of those began as an observation before becoming a candidate; this one was granted ontology status directly, and the pull toward a tidy ontology is strongest exactly when a baseline is being closed.***

### The SEVEN governance responsibilities, now fully separated *(observed 2026-07-31)*

| Layer | Act |
|---|---|
| **Review** | establishes **evidence** |
| **Assessment** | **classifies** evidence |
| **Readiness** | a **derived state** — never an authority act |
| **Authority** | decides **what changes** |
| **Change control** | derives a **compliant implementation** |
| **Execution** | performs **only the commissioned mechanism** |
| **Architectural decision** | determines the **enduring representation model** *(BRM-1 established this layer)* |

***The seventh is the newest and the highest-order: SDM-EXT-1 could not proceed because an architectural decision governed it, and no other layer could supply one.***

### A GOVERNED "DEFERRED" STATE IS A VALID COMPLETION STATE *(Authority, 2026-07-31)*

> ### **Trying to eliminate every deferred item would WEAKEN the constitutional discipline, not complete it.**

**Applied in this programme to: PMR-8 · ES-005.4 · AFV-F4 · repository scope · M7.** ***Closure is more valuable than exhaustiveness: a baseline with recorded deferrals is complete; a baseline with forced resolutions is merely tidy.***

### AUTHORITY GRANULARITY has increased, never decreased *(Authority insight, 2026-07-31)*

| Governance object | Authority unit exercised |
|---|---|
| **Finding** | the individual **finding** *(CON-F2 → M7-F1)* |
| **PMR** | the individual **candidate** *(PMR-7 → 7a/7b/7c)* |
| **ES-005** | the individual **RULE** *(three adopted, one deferred)* |

> ### ***Authority has repeatedly become MORE granular, never less — and that is not stylistic: it REDUCES ACCIDENTAL AUTHORITY PROPAGATION. Each increase in granularity removed a path by which an undecided element could acquire governance status through association with a decided one.***

**Recorded as an observed direction, not admitted as a rule.** *Three instances; and the direction's value is demonstrated rather than asserted — ES-005.4 would have become binding under document-level granularity.*

### READINESS is a DERIVED STATE, not an authority act *(Authority, 2026-07-31)*

| Instrument | Responsibility |
|---|---|
| **Review** | establish **evidence** |
| **Assessment** | **classify** evidence |
| **READINESS** | determine whether **prerequisites are satisfied** — ***a DERIVED STATE, not a decision*** |
| **Authority** | decide **what changes** |
| **Change control** | determine a **compliant implementation** |
| **Execution** | perform **only the commissioned implementation** |

> ### ***No stage performs the next one automatically. "Ready for execution" is an assessment of prerequisites; it is never an authorization.***

**The programme already carried the analogous pairs — Reviewed ≠ Approved · Assessed ≠ Adopted · Promotion Ready ≠ Promoted · Commissioned ≠ Executed.** *Ready ≠ Execute is the same distinction in a new place.* **NOT elevated into a formal methodology stage — the evidence base is limited, and it is recorded as an observation consistent with the existing pattern.**

### THE GOVERNANCE UNIT SHOULD MATCH THE AUTHORITY UNIT *(Authority principle, 2026-07-31)*

| Governance object | Authority unit |
|---|---|
| **Findings** | the individual **proposition** *(CON-F2 → M7-F1)* |
| **PMRs** | the individual **candidate** *(PMR-7 split into 7a/7b/7c)* |
| **ES-005** | the individual **RULE, not the whole document** |
| **M7** | the individual **change item** *(C-M7-01)* |
| **SDM / EOP** | the **governed baseline issuance** |
| **CDR** | the individual **Authority disposition** |

> ### ***Avoid bundling decisions whose EVIDENCE, AUTHORITY or MATURITY differ.***

**The MECHANISM, stated so the danger is explained rather than merely described** *(Authority refinement, 2026-07-31)*:

> ### **When governance objects with different evidence, authority, or maturity are bundled into a single decision, THE BUNDLE SHARES ONE AUTHORITY BOUNDARY WHILE ITS EVIDENCE REMAINS UNEVEN. That can allow weaker elements to acquire governance status they have not independently justified.**

**Superseded, retained:** ~~*"the strongest element carries the weakest"*~~ — ***it described the OUTCOME without explaining the mechanism, and asserted that every bundle fails. The fuller form ties directly to the composite-confidence reasoning (PMR-2) without assuming inevitability: bundling CAN allow unjustified elevation, and whether it does depends on the bundle.***

**Six instances across the programme. Held as an observed architectural direction, not admitted as a rule.**

### THREE kinds of change to a finding or an act — each with its own discipline *(consolidated 2026-07-31)*

| Change | What changes | Discipline required | Instances |
|---|---|---|---|
| **STATUS** | *how strongly the same proposition is held* | **The classification ladder** — *strengthened requires NEW INDEPENDENT EVIDENCE, or confirmed and strengthened become indistinguishable* | CON-F2 (corrected) · IBC-M4 (corrected) · IBC-M1 · IBC-M3 |
| **RATIONALE** | *the GROUNDS for the same outcome* | ***A rationale change is itself a governance change*** — it may not be folded into a re-decision | **AFV-F4** *(a repository-scope rationale vs the model's own contingency — same placement, different grounds)* · **CDR-1 §6** *(SDM v1.1's form was right, its stated reason false)* |
| **ONTOLOGY** | ***THE GOVERNING PROPOSITION CHANGES*** | **The conceptual event is the change of proposition; a new identifier NORMALLY FOLLOWS as its governance consequence.** *Test: does the original proposition survive, merely categorized differently? If no, it is a SUBSTITUTION* | **CON-F2 → M7-F1** |

***The three are ordered by breadth, and each is easy to mistake for the one above it. A substitution presented as a status change is the most dangerous, because it looks like housekeeping.***

**⚠️ Causality stated in the correct direction** *(Authority refinement, 2026-07-31)*: ~~*"ontology → a new identifier"*~~ **inverted the order. The ONTOLOGY CHANGE is the conceptual event; the new identifier is a GOVERNANCE CONSEQUENCE that normally follows.** ***Keeping the model focused on PROPOSITIONS rather than NAMING matters because the hedge is real: a proposition can change without any successor being warranted — where the observation does not survive at all, the finding simply dissolves and nothing follows it.***

### The FOUR concepts these changes operate on *(Authority, 2026-07-31 — the axis that makes the three-tier model coherent)*

| # | Concept | Question it answers |
|---|---|---|
| **1** | **EVIDENCE** | ***what is OBSERVED*** |
| **2** | **PROPOSITION** | ***what is CLAIMED*** |
| **3** | **RATIONALE** | ***why the claim is JUSTIFIED*** |
| **4** | **DISPOSITION** | ***what governance DECIDES to do about it*** |

**The three kinds of change map onto the middle two; evidence is the base and disposition is the output:**

| Change | Operates on |
|---|---|
| **Status** | *the strength with which **2** is held* |
| **Rationale** | **3**, leaving **2** and **4** intact |
| **Ontology** | **2** itself. **⚠️ Corrected 2026-07-31 — the earlier form (*"which is why **4** must be retaken"*) was TOO STRONG.** **Operative: *ontology changes ALTER THE GOVERNING PROPOSITION. Where governance action DEPENDS on that proposition, a new governance decision or finding will normally be required.*** *Counter-cases: a review finding may dissolve and be replaced without an immediate Authority disposition · an observation may change ontology and never become a governance decision · a methodology candidate may split without any Authority act* |

> ### ***These four are routinely conflated, and every conflation this programme has caught was a collapse between two adjacent levels: strengthened-vs-confirmed collapsed 1 into 2 · SDM v1.1's authority claim collapsed 3 into 4 · CON-F2's "reclassification" collapsed 2 into a status change.***

**⚠️ Epistemic status, stated at the Authority's instruction: this is a PROMISING EXPLANATORY MODEL, not a universal rule.** *It explains three cases from this programme. **It becomes a stronger methodological claim only if it continues to explain future cases** — and it is falsifiable in the obvious way: a conflation between NON-adjacent levels would refute it.*

***Held on the same terms as the three stabilizers: derived from this programme's evidence, revisable, with withdrawal as its lawful exit.***

**The CON-F2 resolution respects all four: the EVIDENCE was re-examined · the original PROPOSITION was shown false · a new PROPOSITION was identified · and the DISPOSITION preserved both through dissolution plus a successor pointer.**

### The PROPOSITION is primary; the IDENTIFIER references it *(Authority decision, 2026-07-31 — CON-F2 → M7-F1)*

> ### **The proposition is primary. The identifier is a REFERENCE to that proposition — not the other way around.**
>
> **Where the two diverge, the identifier retires WITH its proposition rather than carrying a falsified premise forward under a familiar name.**

**The auditability test that settled it, and it is the operative test for any future identifier-continuity question:**

| Preserved | Lost |
|---|---|
| **HISTORY** — an annotation can always preserve it | **SEMANTIC CLARITY** — *"X means… except not the first limb… but the second… except the second depended on the first… except we reclassified it"* |

> ### ***An identifier whose meaning requires a chain of nested exceptions has stopped functioning as a reference. History and clarity are different properties, and an annotation buys the first at the cost of the second.***

**⚠️ This does NOT contradict the identifier-stability rule** *(ledger identifiers are evidence; clarify → alias → annotate, never renumber)*. **The two govern different acts: identifier stability forbids REASSIGNING an identifier to different content; this governs whether an identifier TRAVELS with a changed proposition.** ***Never renumber; but do not stretch either — retire and raise anew.***

**Established preference on FOUR independent instances:** *superseded vs dissolved (why a finding retired) · AFV-F4 (the constitutional act had already occurred) · IBC-REC-F1 (reasoning vs application reproducibility) · CON-F2 → M7-F1 (a new identifier for a new proposition).*

> ***Where the underlying PROPOSITION changes, the programme creates a CLEARER SEMANTIC BOUNDARY rather than stretching an existing identifier.***

**Recorded as an OBSERVED PREFERENCE, not admitted as a rule.** *Stated prescriptively it would govern future classification conduct and require PMR → MCA → CDR. Its n = 4 across independent cases exceeds most register entries — which is a fact about the evidence, not a reason to skip the path.*

### AUTHORITY ACTS are governed artifacts in their own right *(Authority recognition, 2026-07-31)*

> ### **An Authority act is a governed artifact, with UNIQUENESS, TRACEABILITY and CHANGE CONTROL preserved across its lifecycle.**

**Consequences, each demonstrated by the AFV-F4 premise verification:**

| Property | What it forbids |
|---|---|
| **Uniqueness** | **A second disposition of one finding.** *Nothing would establish which governs; a finding with two dispositions is worse than one with none* |
| **Traceability** | **Re-deciding behind a promotion.** *The act that gated a correction explains why the correction executed* |
| **Change control** | **Altering an act's RATIONALE while preserving its outcome.** ***Two Authority acts may reach the same operational outcome on different constitutional grounds — and changing the grounds is itself a governance change*** |

**The repeatable discipline this produced, recorded as an OBSERVATION of what occurred, not as a prescription:**

| 1 | Verify that the commission premise still exists |
|---|---|
| **2** | **Determine whether the requested Authority act has ALREADY occurred** |
| **3** | **Refuse to duplicate a discharged constitutional act** |
| **4** | **Separate genuinely new governance questions into their own commissions** |

***Higher than issuing a correct decision: it treats the decision RECORD as something that can be corrupted by a well-intentioned duplicate.*** **Related to the review-side sequence recorded in the Method (object → sources → applicability → classify); both begin by verifying that the thing being acted on exists. Neither is minted as a rule — both were stated descriptively.**

### The five GOVERNANCE DOMAINS *(Authority, 2026-07-31)*

| Domain | Governs |
|---|---|
| **Knowledge governance** | how architectural knowledge is created, reviewed and promoted |
| **Execution governance** | how governed execution is recorded without altering authority boundaries |
| **Archival governance** | how historical records remain truthful over time — annotation or maintenance, as the class requires |
| **Methodology governance** | how changes to FUTURE PRACTICE require their own admission pipeline |
| **Meta-governance** | how the governance system applies its own rules to itself |

> ***These are governance DOMAINS — not review families, not contracts, not emphases. They are different APPLICATIONS of one governance discipline, not expansions of the governance taxonomy.***

**⚠️ A precision that must be stated, because the collision is real and would mislead:** **there are five review FAMILIES and five governance DOMAINS, and the coincidence of count is ACCIDENTAL. The two axes are ORTHOGONAL.**

| | Classifies |
|---|---|
| **Review family** *(preservation · interpretation · decision · consequence · execution)* | **what a review EXAMINES** |
| **Governance domain** *(knowledge · execution · archival · methodology · meta)* | **what governance ACTIVITY is being performed** |

***"Execution" appears in both and means different things: the execution FAMILY examines whether a plan was faithfully applied; the execution DOMAIN governs how execution evidence is recorded.*** **ER-1 is the proof of orthogonality: a PRESERVATION-family review operating in the ARCHIVAL and EXECUTION domains simultaneously.**

### The four governance maturities *(recognized 2026-07-30, KBI-R1)*

| # | Maturity | Established by |
|---|---|---|
| 1 | **Artifact governance** — documents reviewed and bounded individually | the per-artifact reviews |
| 2 | **Cross-artifact governance** — coherence and transformation relationships verified | C4-2 · AFV-1 · KBI-1 (baseline scope) |
| 3 | **Execution governance** — plans and readiness independently validated | CCP-1 · ERV-1 |
| 4 | **HISTORICAL GOVERNANCE** — completed governance acts preserved without rewriting history | AFV-R1 · ERV-R1 · KBI-R1 |

> **Maturities 1–3 establish how knowledge is CREATED and VERIFIED. Maturity 4 establishes how governance evidence REMAINS TRUSTWORTHY AFTER IT HAS FULFILLED ITS OPERATIONAL PURPOSE.**

***An artifact's most dangerous phase is not while it is in force — it is after it has stopped being in force and before anyone has said so.***

---

### TWO lawful archival lifecycles *(ER-1, 2026-07-30 — richer than "frozen vs not frozen")*

**The programme assumed one lifecycle for ten reviews. ER-1 established that a second is legitimate:**

| **Frozen-artifact lifecycle** | **Segmented-ledger lifecycle** |
|---|---|
| draft → review → authority → **freeze** | segment executed → **segment sealed** → append next segment → **maintain operational header** → append next segment |
| Remedy for a lapsed present-state claim: **ANNOTATE** | Remedy: **MAINTAIN** |
| *Editing would destroy a completed statement* | ***Nothing in the sealed segments changes; only the operational metadata evolves*** |

> ***Maintenance is not amendment. Updating an operational header is analogous to updating an INDEX, not to rewriting a CHAPTER — and it is lawful only because the history is already sealed inside completed segments.***

**⭐ RETENTION is the OBSERVABILITY CONDITION, not a courtesy** *(Authority, 2026-07-31)*:

> ### **Without retention of the superseded text, maintenance is OBSERVATIONALLY INDISTINGUISHABLE from rewriting history.**

**With retention, four properties hold simultaneously — and this is the full test for lawful maintenance:**

| 1 | the current operational state is immediately visible |
|---|---|
| **2** | **the historical operational state remains immediately RECONSTRUCTIBLE** |
| **3** | **no sealed execution segment changes** |
| **4** | **chronology remains intact** |

***A maintainer who cannot be distinguished from a rewriter has not earned the distinction, however honest the intent. Retention is what makes the claim checkable by a reader who does not trust the maintainer.***

### Why a polished history is a defect *(Authority, 2026-07-31)*

> ### **A polished history CONCEALS THE DECISION POINT.**

**An auditable governance history shows future readers *both* the inconsistency *and* the reasoning used to resolve it.** *Demonstrated by the ER-F1/ER-F2 disposition, where a contradictory instruction was recorded rather than silently resolved.* ***The value of the record is not that it is clean — it is that a later reader can find the place where judgment was exercised and check it.***

### Claim-level governance *(the strongest theoretical result of ER-1)*

> ### **Artifacts are not preserved or maintained wholesale. INDIVIDUAL CLAIMS are governed according to their function.**

**Demonstrated where it could not be evaded: the Execution Record's header holds both kinds in ADJACENT ROWS** — the stopping rule (explanatory → **preserve**) beside the Status row (operational classification → **maintain**). *An artifact-level verdict would have been wrong in one direction whichever way it went.*

### Two baseline architectures — **a DISCOVERED property, not a proposal** *(Authority decision 1, 2026-07-31)*

| **Model A — MATERIALIZED** | **Model B — REFERENCE-DEFINED** |
|---|---|
| Authority decision → **consolidated baseline document** → verification of the document | Authority decision → **definition by reference** → governed baseline |
| **Decision → COPY into baseline → risk of transcription, synchronization and divergence** | **Decision → reference BECOMES baseline.** *No transcription step* |

***The evidence is operational, not theoretical: the original CDR already defined SDM v1 / EOP v1 by reference, and v1.1 lawfully extended that definition without a consolidated document. This is a discovered architectural property of the programme, not a new proposal.***

**The architecture is not merely different — it optimizes for a different RISK PROFILE.** *Model B removes an entire defect class (copying · synchronization · source/baseline divergence), and in exchange fidelity verification of copied text becomes unnecessary because there is no copied text.*

> ### ⚠️ **QUALIFICATION forced by ISV-F1: Model B eliminates transcription into the BASELINE. It does NOT eliminate transcription into the RECORDS that decide and issue it.**

**ISV-F1 occurred entirely in that residual path: the Assembly Fidelity Rule's text was transcribed from the register into MCA-R1, then CDR-R1, then the issuance — three transcriptions, one corrupted — even though the baseline itself holds no copy.**

***The transcription risk class was not removed. It was RELOCATED from the baseline to the decision chain, where nothing was checking it.*** **Consequence for practice: under Model B, every QUOTATION drawn from an authoritative source into a decision or issuance record must be compared against that source, precisely because the baseline no longer provides a second place to catch the error.**

### Epistemic preconditions vs disclaimers *(Authority determination, 2026-07-31 — MC-1)*

**The MCA's header carries a MANDATORY T-2 declaration: *"Independence is procedural, not personal. **Every 'validated' below means same-lineage-validated on n=1 execution.**"***

> ### **That is an EPISTEMIC PRECONDITION, not a disclaimer. It does not warn the reader — it DEFINES THE SCOPE WITHIN WHICH EVERY LATER CLAIM IS INTENDED TO BE INTERPRETED.**

| Disclaimer | **Epistemic precondition** |
|---|---|
| Sits beside the claims and asks to be remembered | **Sits BEFORE the claims and fixes what they mean** |
| A reader may finish the document having forgotten it | ***A reader cannot reach a claim without having passed the frame that scopes it*** |
| Failure mode: the claims are read unqualified | Failure mode: none available to a reader who starts at the top |

**Stronger than an ordinary limitation section, and it anticipates the reachability principle** *(candidate, n=3)* **by a different route: reachability asks that a qualifier be encounterable from a lapsed claim; an epistemic precondition makes the qualifier UNAVOIDABLE for the whole document.**

***Recorded as an artifact-design pattern available to any artifact whose vocabulary is scoped by a limitation — which is every assessment this programme produces.***

### Verification follows the artifact architecture *(Authority decision 2, 2026-07-31)*

> ### **Verification must be appropriate to the artifact architecture — the strategy is selected BECAUSE OF the artifact's architecture, not because a technique already exists.**

| If the artifact is… | The appropriate verification may be… |
|---|---|
| **Derived** | **fidelity** verification |
| **Reference-defined** | **scope / completeness** verification |
| **Immutable ledger** | **execution-evidence** verification |

***This avoids forcing every governance object into one verification pattern — and it is the general form of the ruling that let the AFV question be dissolved rather than answered.***

### Definition-by-reference vs consolidated baselines *(Authority ruling, 2026-07-31)*

**Two architectural patterns, neither inherently superior:**

| Consolidated | **Definition by reference** |
|---|---|
| Decision → **baseline document** → verification | Decision → **definition by reference** → governed baseline |
| A derived text exists and can be verified for fidelity | ***There is only ONE authoritative wording for each governed statement — no copied baseline that can drift*** |
| Risk: the copy drifts from its source | **Trade-off: some forms of fidelity verification cease to be MEANINGFUL, because there is no derived text to compare** |

> ### **The verification strategy adapts to the architecture, not the architecture to the verification strategy.**

**The four settled conclusions** *(Authority, 2026-07-31 — the architectural question CLOSED)*:

| 1 | **Do NOT consolidate solely to enable verification** |
|---|---|
| **2** | **Do NOT perform a tautological fidelity verification where no derived artifact exists** |
| **3** | **Verify what actually CHANGED — the issuance scope — using a verification appropriate to a reference-defined baseline** |
| **4** | **Treat any future consolidation as an INDEPENDENT controlled-change decision, justified on its own merits rather than as a workaround for verification** |

> ***Let the architecture determine the verification, not the verification determine the architecture.***

**⚠️ Terminology discipline attached to conclusion 3** *(Authority ruling, 2026-07-31)*: **the BEHAVIOUR is admitted; a NAMED CLASS is not.** *The governed phrasing is "perform a scope verification of the issuance" — not a capitalized construct.* **Evidence standing: one demonstrated use · one artifact class · one governance act.** ***That supports the behaviour, not yet a named verification type; a name follows recurrence, never anticipates it.***

**And the cost of getting this wrong is asymmetric, which is why the conservative default is correct:** ***a name, once issued into the record, cannot be WITHDRAWN — only BOUNDED, because identifier stability forbids renaming. Premature naming is permanent in a way premature silence is not.***

**Two rulings that follow, both recorded as governing:**

1. **Do NOT consolidate merely to enable a verification.** ***Governance defines the artifact structure; verification SERVES the governance structure. Verification must not become the reason the governance architecture changes.*** *Consolidation, if ever undertaken, is an independent controlled-change proposal with its own justification — never a prerequisite for a check.*
2. **A classical fidelity verification does not exist for a reference-based baseline.** *Verifying register → register is identity, not fidelity.* **The meaningful instrument is an ISSUANCE SCOPE verification: does the issuance carry exactly the decided adoptions and nothing else?**

**⚠️ Bounded by ISV-1's own result: this ruling holds for the SHAPE of the verification, not for its emptiness.** ***ISV-1 found a MAJOR defect by comparing an issued clause against its register source — so text comparison against the authoritative wording remains essential even where no derived BASELINE exists. What is absent is a derived baseline; what is present, and must be checked, is every QUOTATION drawn from the authoritative source.***

### Identifier stability in a ledger *(archival rule, ER-1)*

> **Ledger identifiers are part of the EVIDENCE. Renumbering changes the external identity of historical references.**

**Escalation ladder where ambiguity exists — and renumbering is not on it:**

| 1 | 2 | 3 | ✗ |
|---|---|---|---|
| **Clarify** | **Alias** | **Annotate** | ~~Renumber~~ |

*This is the same forward-only archival philosophy the programme applies to supersession: identity is never reassigned, only extended.*

### The four instruments, and what operational separation means

| Instrument | Its ONE responsibility |
|---|---|
| **Review** | Establish evidence |
| **Authority** | Dispose the evidence |
| **Historical annotation** | Preserve the record |
| **Methodology governance** | Evolve future practice |

> ***Conceptual separation is claimed in a framework document. OPERATIONAL separation is only ever demonstrated by a case in which collapsing the instruments would have been EASIER — and the collapse did not occur.***

**Demonstrated on KBI-R1: the shortcut (silently correct the stale Status line) was one edit, and would have destroyed a historical assessment, taken an Authority act inside a review, and set a methodology precedent by accident.**

### Operational Evidence at ZERO — restraint, not a gap

> ### **A certification dimension derives credibility from being OUTSIDE THE UNILATERAL CONTROL of the programme it certifies.**

*(Generalized 2026-07-30 from the Operational Evidence case, which is one instance:)* **a governance programme cannot certify what only independent operational experience can establish.**

**The zero STRENGTHENS the credibility of the certification model rather than weakening it: a programme able to move its own Operational Evidence dimension would have shown only that the dimension measures nothing independent.** *This is the Self-Application Principle at its widest scope — the discipline KBI-1 applied to itself (*"what this review can attest is internal coherence"*), applied by the programme to the programme.*

**Evidence — three review records in three artifact categories, all on the same day:** the **RET-1 review's** verdict date-marked after its findings were remedied · the **AD-1 review's** *"NOT YET READY"* verdict date-marked after AD-1 was promoted · the **AFV-1 verification's** Status and §13 date-marked with a discharge note after all five findings were closed.

**Why date-marking rather than rewriting, stated because the cheaper option is always to rewrite: a rewritten verdict erases the evidence that the review process worked.** *AFV-1's §13 saying "AD-1 is not promotion-ready" is the record of why AD-1 was corrected. Deleting it would leave the corrections unexplained and the review's contribution invisible.*

---

### E. Traceability Integrity — **REFINEMENT with evidence**

**Lineage, not citation.** Every major statement should answer four questions, of which the existing rules cover only one:

| Question | Covered before? |
|---|---|
| **Where did it come from?** | ✅ Rule 18 / provenance |
| **Why does it exist?** | ✅ Rationale conventions |
| **What governs it?** | ✅ Rule 8 (authority basis) |
| **What supersedes it?** | ❌ **Not covered by any existing rule** |

**Evidence that the gap is real:** **KC-4.** When ADR §3.1.1 was corrected, it **superseded** the taxonomies in §4.1 and Appendix A — and *nothing recorded the supersession*, so three incompatible taxonomies coexisted inside one artifact. **In a forward-only regime, supersession is the only mechanism of change; an untracked supersession is therefore an untracked change.** Also: **§14's embedded status created a second account competing with the metadata block** — a supersession that was never declared.

**The rule:** **when a correction supersedes content elsewhere, the supersession is itself a recordable fact.** Preferred remedy, demonstrated at KC-4: **state the content once and reference it** — a referenced statement cannot silently diverge from itself.

---

### F. Context Integrity — **NEW quality, admitted with evidence** *(ARB, 2026-07-30)*

**Within which bounded context is this statement authoritative?** The questions it forces:

| Question | Why it is distinct from A–E |
|---|---|
| **Which model owns this knowledge?** | A asks whether meaning is preserved; F asks **who is entitled to state it** |
| **Where is it authoritative, and where merely informative?** | B asks whether a statement has *an* authority; F asks **in which context that authority holds** |
| **What crosses a context boundary?** | E tracks lineage *within* a chain; F tracks **crossings between chains** |
| **What is translation versus inheritance?** | Neither A–E distinguishes a statement *inherited* across a boundary from one *translated* across it |

**Why it earns admission — the defect class it names is *correct statements in the wrong place*, which no other quality catches:**

| Instance | The defect |
|---|---|
| **KC-9** — Appendix B's technology stacks | Every statement was **factually correct** (Spring Boot exists; Deptrac enforces rules) and **contextually wrong**: implementation-context knowledge asserted inside a strategic artifact. A–E would each pass it |
| **U-2** — the unassigned translation obligation at the PKS ∥ Work-Management edge | **"Translation versus inheritance" is literally this finding**: the DoD's knowledge boxes cross a domain boundary, and **no one owns the translation** — an open item the program has carried since M7 |
| **The guarded homonyms** — *"Approved"* and *"Verified"* | The same token is authoritative in **two contexts with different meanings** (work state vs authority act / verdict). Correct in each; catastrophic if read across the boundary untranslated |
| **OQ-PKS-11** *("Qualification": one concept or two)* and the **three-sense "register"** | Both are context-ownership questions the corpus recorded before this model existed — the domain **felt** this quality without having named it |

**The rule the quality produces:** **a statement's correctness and its placement are independent properties.** An artifact may contain only statements it is entitled to make; anything crossing a boundary must be marked as **inherited** (carried unchanged, with its owner named) or **translated** (restated for the receiving context, with the translation owned).

**Derived — a practice already in use before the rule existed:** the illustrative-realizations companion carries *"Authority: NONE. This document is illustrative only… PKS-ADR-001 prevails without exception."* **That is a Context Integrity statement**, written before the quality was named — which is the ordinary sign that a quality is real rather than invented.

---

## Relationship to the sibling documents

**CANONICAL LAYER MAP — this is the single authoritative statement of the framework's structure.** Siblings reference it; none restates it. *(Made canonical 2026-07-30 after a duplicate copy in the Method was found already diverging in layer naming — the KC-4 pattern caught early. See §Cascade, below.)*

**The framework holds FIVE distinct concerns, of which THREE are documents** *(ARB 2026-07-30, extended by the ARB's own Growth Governance recognition the same day)*:

| Layer | Responsibility | Canonical home |
|---|---|---|
| **1. Knowledge Integrity Model** | Defines the invariant properties of trustworthy knowledge — *what is good knowledge?* | **This document** — qualities A–F only · **depends on nothing** |
| **2. Knowledge Contract Review Method** | Defines the review process for evaluating those properties — *how do we evaluate it?* | `PKS_Knowledge_Contract_Review_Method.md` — eleven objectives, which **reference** these qualities · **depends on layer 1 only** |
| **3. ARB Review Discipline** | Defines reviewer governance, authority boundaries, and conduct — *how must reviewers behave while evaluating it?* | `PKS_ARB_Review_Discipline.md` — Rules 1–18 · **depends on layer 2, transitively on layer 1** |
| **4. Framework Growth Governance** — ***RECOGNIZED CONCERN, CANDIDATE bounded model, NO DOCUMENT CREATED*** | Defines how **the framework itself** evolves | **Distributed, deliberately** — hosted where each rule currently operates (inventory and promotion trigger below) |
| **5. Program Governance Process** | Defines how **reviews** become authoritative decisions and changes | **Pre-existing — no new artifact:** SDM v1 + EOP v1 (frozen) · the CDR decision · **Process Under Configuration Control** · CCP-1 §12.6's chain (*review → Authority disposition → change item → execution*) · and, for implementation, `Implementation_Process_v1.0.md` |

**Recorded deliberately: layer 5 is a pointer, not a document.** Creating a framework artifact for it would be the accretion the admission filter exists to prevent — the governance process has been governed since the CDR, and it needed naming in this map, not writing.

### Layer 4 — Framework Growth Governance: recognized, named, and deliberately NOT extracted *(ARB, 2026-07-30)*

**The ARB's observation, adopted:** the framework has accumulated rules that are *"not review behavior, review workflow, or knowledge quality — they are governance of the framework itself"*, and this **has emerged as its own architectural concern.** The ARB's instruction is equally explicit: ***recognize it; do not split it into a document yet; if it continues to grow it may eventually justify its own bounded model.***

**Why recognition without extraction is the correct act and not a compromise:** the admission ladder now requires **repeated operational evidence** before a control becomes governed. **Extraction on first recognition would violate the very rule that growth governance holds** — the concern would be promoted by insight rather than by evidence, which is the accretion pattern the filter exists to stop. **Naming a concern and hosting a document are different acts.**

**Inventory — where growth-governance content lives today.** Recorded now, while it is cheap, because *this inventory is what would make a future extraction bounded rather than exploratory*:

| Growth-governance rule | Current host | Why it sits there |
|---|---|---|
| The **admission filter** (strengthened) | Discipline header — canonical; inherited by this model | Written as that document's own growth rule before the concern was named |
| The **admission ladder** (Candidate → Provisional → Governed → Declined) | Discipline §Admission ladder | Same |
| **Provisional adoption** + promotion/demotion conditions | Discipline (Rule 16; Part V) | Retained there by the FW-1 disposition — *trial status is growth governance, not output shape* |
| **Frozen baseline** semantics + **amendment** mechanism | Discipline §Freeze status · §Amendment 1 · §Change discipline | Applies to that document |
| **Restatement vs addition** determination (qualities A/B) | This model | Applies to this model's own growth |

**Promotion trigger — evidence-based, per the ladder, so the fifth document cannot arrive by drift:** extraction becomes justified when **growth-governance rules can no longer be stated within the layer they govern** — concretely, when a growth rule is needed that **no single layer can host**, or when the same growth rule must be **restated in two layers to remain applicable** (the KC-4 condition). **Until one of those fires, distribution is not a defect: each rule sits in the document whose growth it governs, which is the strongest possible locality.**

**Q-GG-1 (open question, deliberately unresolved).** Is Framework Growth Governance a **genuinely distinct** concern, or is it **Configuration Control specialized to the framework**? The shapes are suspiciously alike: the CDR's declared process is *execution evidence → MCA-class assessment → CDR-class decision*, and the admission ladder is *escaped-defect evidence → candidate/provisional assessment → governed decision*. **If the second is an instance of the first, then layer 4 is not a fifth concern at all but layer 5 applied to a different object — and no document could ever be justified for it.** The question bears directly on the promotion trigger, so it is recorded rather than assumed either way.

**Naming disambiguation, applied under quality F.** *"Governance"* was on its way to becoming this framework's **third guarded homonym** after *Approved* and *Verified*: the ARB's table labelled layer 4 **Growth Governance** while the existing map already labelled layer 4 **Governance Process** — **two different concerns, one token, in the same table position.** Silently adopting the new label would have overwritten a pre-existing pointer without recording the supersession — the KC-4 defect. Resolved by naming both explicitly: **Framework Growth Governance** (layer 4, the framework's own evolution) vs **Program Governance Process** (layer 5, how reviews become decisions), and by renumbering rather than replacing.

### Cascade found while applying this update *(objective 8, self-applied)*

**This layer map existed in two documents and had already begun to diverge** — this model named layer 4 *"Governance Process"*; the Method's copy left it unnamed and listed only homes. Neither was wrong yet, which is exactly how KC-4 began. **Resolved the way KC-4 was: stated once here, referenced there.** *A referenced statement cannot silently diverge from itself.*

**Three rates of change, governed independently** *(the ARB named this the key architectural result, and it is the reason for the separation)*: **integrity model — slowest** · **review method — evolves as experience accumulates** · **review discipline — operational practice, fastest.** Each layer has **one reason to change**.

**Derived:** the qualities are **stable**; methods and disciplines will evolve faster. **That difference in expected rate of change is the practical reason for the separation** — coupling criteria to procedure would force a quality revision every time a procedural rule changed.

**The three are BOUNDED MODELS, not three documents** *(ARB framing, 2026-07-30, adopted)* — each answers a different architectural question, which is why they are genuinely different domains of concern rather than a filing convention. **And each is the one place its answer is stated:** a second statement of the same answer elsewhere would be the duplication that KC-4 showed becomes silent divergence.

**Dependency direction, acyclic** — `Integrity ← Method ← Discipline`; canonical statement and the dependency/back-reference distinction live in the Discipline's dependency rule. **FW-1 (the one recorded violation) was DISPOSED and closed on 2026-07-30 — the graph now holds.** *(Layers 4 and 5 sit outside this ordering: growth governance and the program governance process act **on** the three documents, they are not consumed **by** them.)* **This model is the sink: it depends on nothing, and that is what makes it revisable in isolation.**

---

*Traceability: substance issued by the Authority 2026-07-30 (qualities A–F, the four knowledge acts, the five temporal classes); **this document authored by the reviewing assistant, lifecycle PROPOSED, adoption PENDING** · part of the four-layer separation of the review framework (Integrity Model / Method / Discipline / pre-existing Governance Process), per DDD's one-responsibility-per-model principle · qualities A and B recorded as restatements of existing coverage, not additions · C, D, E admitted as refinements and **F admitted as a new quality**, each with named escaped-defect evidence per the inherited admission filter · renamed from "Quality Model" and its lifecycle status corrected (authored/proposed/adoption-pending) on ARB instruction 2026-07-30 · this document defines criteria only and contains no procedure, no ordering, and no reviewer obligations · **admission filter strengthened 2026-07-30 to require REPEATED operational evidence; all six qualities re-checked against the stronger bar, with C/D/F Governed and E disclosed as the thinnest rung at n=2** (the disclosure is deliberate — presenting E as equal would be the confidence inflation R-M6-6 exists to prevent) · **dependency position recorded: this model depends on nothing**, upward mentions of objectives and Rules are back-references (coverage evidence), not dependencies · the three siblings recorded as **bounded models** per the ARB's framing, each answering a distinct architectural question · **2026-07-30: this document's layer map made CANONICAL** (a diverging duplicate in the Method reduced to a reference — KC-4 pattern arrested early) and extended to **five concerns**, with **layer 4 Framework Growth Governance RECOGNIZED as a distinct architectural concern per the ARB and deliberately NOT extracted into a document** (inventory + evidence-based promotion trigger recorded; extraction on first recognition would violate the repeated-evidence filter that growth governance itself holds), **layer 5 renamed Program Governance Process** to prevent *"governance"* becoming a third guarded homonym, and **Q-GG-1** raised unresolved (*is growth governance distinct, or Configuration Control specialized to the framework?* — Options A/B stated, **Authority leaning toward B recorded as a leaning, not a decision**; both options require the same action today) · **2026-07-30 additions: the map recorded as TWO ORTHOGONAL DIMENSIONS** (governance oversight acting on the framework · operational dependency defining and executing reviews — the acyclic rule constrains only the lower one) and the **SELF-APPLICATION PRINCIPLE** recorded as an architectural property, admitted **GOVERNED on five counted instances**, hosted here rather than given a document because the promotion trigger, on its own first exercise, **does not fire**.*
