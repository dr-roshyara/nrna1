# PKS — Knowledge Contract Review Method

| | |
|---|---|
| **Kind** | **Review method** — it defines **what to examine and in what order** when reviewing a governed artifact as a *knowledge contract*. It defines nothing about what **architecturally correct knowledge** means (that is the Integrity Model) and nothing about how a reviewer must behave (that is the Review Discipline). |
| **Responsibility (singular, per objective 11)** | **Workflow only** — objectives, their order, and the required output shape. No criteria, no reviewer conduct rules. |
| **Substance issued by** | The Authority — objectives 1–10 on 2026-07-29 (as v2.0 of the review framework), objective 11 (Knowledge Cohesion) on 2026-07-30. **Objective text is the Authority's.** |
| **This document authored by** | The reviewing assistant, 2026-07-30 — extraction, the quality cross-references, and the admission-evidence notes. |
| **Lifecycle status** | **PROPOSED** · **Adoption: PENDING — no adoption record exists.** *(Lifecycle fields corrected on ARB instruction 2026-07-30; the earlier "in force immediately" conflated issuance with adoption.)* |
| **Binding status** | In force for the reviewing assistant's own **method** under the standing scope note. **Binding on future review commissions requires adoption** and follows the PMR → MCA → CDR path. |
| **Canonical specification** | §Canonical review specification — invariant sections plus **one** interchangeable module (the emphasis). Three emphases governed: publication · retrospective · architecture definition. |
| **Siblings (layer map at §Framework layers)** | `PKS_Knowledge_Integrity_Model.md` (criteria — qualities **A–F**) · `PKS_ARB_Review_Discipline.md` (conduct — Rules 1–18) |
| **Relationship to the Discipline** | **Rules 1–18 remain in force and operate *inside* these objectives.** The Discipline governs **how a reviewer reasons, and how findings are routed**; this document governs **what is examined, in what order, and what a conformant finding looks like** — including the **finding vocabulary**, relocated here by Authority disposition of FW-1 (2026-07-30). **Dependency direction (acyclic, per the Discipline's dependency rule): this document depends on the Integrity Model only, and now genuinely does — FW-1 RESOLVED.** |

---

## Review philosophy (binding)

The artifact is a **knowledge contract**, not a document. Prefer **precision over completeness · stable semantics over richer prose · durable knowledge over transient project state · architectural integrity over documentation convenience.**

**If additional explanation does not improve architectural correctness, recommend removing it.**

*Success is not measured by the number of findings. It is measured by preserving the integrity, stability, and traceability of the strategic knowledge.*

---

## The eleven objectives, in mandated order

| # | Objective | Examines | Quality referenced |
|---|---|---|---|
| **1** | **Knowledge Integrity** | Semantic drift · hidden assumptions · unstated reinterpretation · **the four acts: representation · synthesis · interpretation · invention** · accidental redesign | **C** (and A) |
| **2** | **Strategic DDD Integrity** | Ubiquitous language · bounded contexts · relationships · context-map semantics · domain invariants · strategic constraints · architectural principles | **A, F** |
| **3** | **Authority Integrity** | *Every normative statement must name a governing artifact.* **Never infer authority. Never elevate a supporting artifact into a governing one.** | **B** |
| **4** | **Governance Integrity** | Wording that accidentally performs **promotion · certification · acceptance · authority disposition · implementation authorization · governance interpretation.** ***An ADR must never perform an Authority act by implication.*** | **B** |
| **5** | **Knowledge Classification** | Strategic Architecture · Governance · Authority Record · Decision · Constraint · Assumption · Interpretation · Historical Record · Implementation Guidance · Observation · Recommendation — **report whenever two classes mix** | **C, D** |
| **6** | **Strategic/Tactical Boundary** | Any transition across it. **Also: what crosses a context boundary, and whether it crosses as *inheritance* or *translation* (quality F).** Strategic DDD defines *what exists, why, boundaries, relationships, invariants, constraints* — never aggregates, entities, repositories, APIs, services, persistence, frameworks, technologies, AI agents, deployment, or implementation patterns | **A** |
| **7** | **Temporal Integrity** | Each statement's temporal class: **constitutional truth · timeless fact · point-in-time · execution state · historical evidence.** *Temporary state must not become permanent architectural knowledge* | **D** |
| **8** | **Consistency & Cascade Review** | Contradictions · duplicated semantics · incompatible terminology · taxonomy drift · conflicting classifications. ***Actively search*** *for cascades: a correction in one section frequently requires corrections elsewhere* | **E** |
| **9** | **Decision Quality** | Explicit · bounded · testable · implementation-independent · authority-supported · internally consistent. **Reject decisions that depend on implied assumptions** | **B** |
| **10** | **Document Balance** | Whether material *explains rather than decides*, records transient state, or belongs in an appendix or companion. **Normative definitions stay in the body; transient evidence generally does not** | **D** |
| **11** | **Knowledge Cohesion** *(added 2026-07-30)* | ***Does this artifact have a single architectural responsibility?*** Review for **responsibility creep** — an ADR must not simultaneously be architecture, governance manual, review log, implementation guide, and project status report | **C, D** |

### On objective 11 and its relation to objective 10

**Objective 10 detects the symptom; objective 11 names the cause.** Balance asks *is this material in the right place?* Cohesion asks *does this artifact have one job?*

**Admission evidence (the filter requires a named escaped defect):** the whole-artifact review of PKS-ADR-001 found it had become *"architecture · explanation · governance summary · implementation guide · review record"* — detected under objective 10 as a placement problem, when the underlying defect was **responsibility**: §8 authorizing (implementation guide) · §14 recording (review log) · §1.1 reporting (status) · Appendix B illustrating (companion material). **Objective 10 alone would have relocated material without diagnosing why it accumulated.**

**Self-application, recorded because it is the warrant for this document's existence:** applying objective 11 to the review framework itself showed a single document carrying **reviewer conduct + review method + evaluation criteria** — three responsibilities. **Applying objective 11 revealed a better decomposition; separating the responsibilities improves cohesion and aligns the framework with its own design principles.** *(Wording refined on ARB instruction 2026-07-30: the earlier phrasing — *"the framework failed its own newest objective"* — presupposed that the framework was invalid. It was not; it produced every finding in this record while holding all three concerns. **DDD prefers discovering a better model over declaring the previous model incorrect**, and the two acts carry different bars: quality/rule admission requires escaped-defect evidence, cohesion refactoring requires only demonstrated multiple responsibilities.)*

---

## Framework layers — **by reference, not restated**

**The canonical layer map lives in `PKS_Knowledge_Integrity_Model.md` §Relationship to the sibling documents.** It records five concerns of which three are documents: **1 Integrity Model · 2 this document · 3 Review Discipline · 4 Framework Growth Governance (recognized concern, candidate model, no document) · 5 Program Governance Process (pre-existing pointer)**.

**This section deliberately holds no copy of the table.** A duplicate map here had already begun to diverge from the canonical one in layer naming before it was caught on 2026-07-30 — **the KC-4 pattern, arrested early.** *A referenced statement cannot silently diverge from itself.*

**What this document owns of the framework's structure:** its own responsibility (workflow + output shape) and its own dependency position — **depends on the Integrity Model, must not depend on the Discipline.**

### Dependency conformance — FW-1 **RESOLVED by Authority disposition (2026-07-30)**

**FW-1 (Major, framework-local) — the defect as found.** This document's **Required output** section took its finding vocabulary from *"the Discipline's, Rules 8/9/16."* That was a **normative dependency, not a back-reference** — a reviewer could not produce a conformant finding from this document alone. With `Discipline → Method` it closed a **cycle**, so the declared acyclic property did not hold.

**Authority disposition: resolution (a) ACCEPTED — *"move finding vocabulary to the Method."*** The finding vocabulary is **output shape, not reviewer conduct**, and therefore belongs here. This is **objective 11 applied once more, this time to the Discipline** — the same cohesion test that produced the three-way separation, now producing a boundary correction inside it.

**What moved, and what deliberately did not** — the split follows the layer boundary, not the rule numbers:

| Element | Layer | Where it now lives |
|---|---|---|
| The three **enumerations** (authority basis · constitutional category · evidence origin) | **Output shape** | **This document, §Finding vocabulary — authoritative here** |
| *"A finding's constitutional weight derives from this classification, not from its severity label"* (Rule 8) | **Reviewer reasoning** | Discipline, unchanged |
| *"Severity and constitutional category are orthogonal; never merge them into a single severity list"* (Rule 9) | **Reviewer reasoning** | Discipline, unchanged |
| Rule 16's **provisional status**, promotion and demotion conditions | **Reviewer conduct + growth governance** | Discipline, unchanged |

**Why the obligations stayed:** *"every finding shall declare its authority basis"* defines a conformant output; *"never merge severity with category"* constrains how a reviewer reasons. **Moving the second would have re-created the original defect with the layers reversed.** Each rule was split at its own seam rather than moved whole.

**Scope held to the disposition.** Rules 5, 11, 12 and 14 also carry enumerations, and they were **not** moved: this document's required-output section never cited them, so they form no back-edge. **Relocating them would be reviewer-initiated layer redesign — exactly what FW-1's own recording refused to do.** They become candidates only if a future output requirement cites them.

**Graph status: ACYCLIC.** `Integrity Model ← Method ← Discipline` now holds. Remaining upward mentions in this document are back-references — each states its substance inline and is applicable without the Discipline.

**Q-FW-1 (open question, not a finding — raised under the *when uncertain, raise a question* rule).** The required output mandates a **severity** per finding, and **no canonical severity enumeration exists in any of the four layers** — reviews have used *Critical · Major · Minor · Recommendation* by convention. **Defining one here would be reviewer-invented vocabulary, which the admission filter forbids absent evidence that its absence let a defect escape; no such evidence exists** (Rule 9 already prevents severity from carrying constitutional weight, which is what would make an undefined scale dangerous). **Recorded as a question for the Authority, deliberately not resolved.**

---

## Canonical review specification — INVARIANT sections + ONE interchangeable module *(Authority, 2026-07-30)*

**Adopted on the Authority's observation that the publication, retrospective and architecture-definition reviews all ran the same structure with one varying part.** *Recorded as a **consolidation, not an addition**: three commissions had repeated the same invariant sections, which is the KC-4 condition — **state once, reference**. This section removes duplication; it introduces no mechanism.*

### The invariant sections — identical for every emphasis

| Section | Content |
|---|---|
| **Role** | Knowledge Contract Review of a governed artifact. **Not** discovery · **not** design · **not** an Authority act |
| **Constitutional principle** | Review the artifact **as its own kind**. **Do not redesign, improve, or optimize it** — determine only whether it remains faithful to its responsibility |
| **Artifact responsibility** | What this artifact kind **may** and **may not** do, stated before any finding is raised |
| **The eleven objectives** | Unchanged, in the order above |
| **Review principles** | **Verify before criticizing · narrow findings whenever evidence requires · do not infer beyond the governed corpus · preserve historical correctness · separate observations from prescriptions · separate descriptions of the CURRENT state from constraints on FUTURE states · prefer recording uncertainty to resolving it without evidence** |
| **Explicit non-scope** | Named, so absence is not read as oversight |
| **Required output** | Per-finding fields and final assessment (below) |
| **Success criterion** | Whether the artifact is fit for its responsibility, or accompanied by a **bounded, evidence-based** remediation plan |

**The sixth review principle earned its place today:** *separate descriptions of the current state from constraints on future states* is **AD-R1** generalized, and it also names **AP-3/C-04** and **DR-7**. *The temporal axis is where derivation most often overreaches.*

### The honest-failure-direction question *(ARB, 2026-07-30 — a recurring review question, admitted GOVERNED at n=5)*

> ***What is the artifact's honest failure direction?*** *Where is it most likely to be wrong, and does it say so?*

**Ask it of every artifact, under every emphasis.** **It is admitted governed because five artifacts of five different kinds already answer it, and each answer was the most informative thing in the artifact:**

| Artifact | Its stated failure direction |
|---|---|
| **M6** (discovery) | **T-16 — complement-boundary asymmetry:** *"three well-evidenced edges imply a residual region whose interior is unevidenced; a residue can look artificially cohesive merely by being left over"* |
| **AD-1** (derived) | AR-1/AR-2 as **architecturally undefined regions**, plus six open questions |
| **M8** (knowledge) | ***"A complete assembly of incomplete knowledge is exactly what this report is"*** |
| **RET-1** (reflective) | *"what it has **not** learned and **cannot learn alone** — whether any of this is repeatable by anyone else"* |
| **AFV-1** (verification) | *"same-lineage (T-2). It attests **fidelity, not correctness**"* |

**Why it earns a place as a question rather than a criterion: it cannot be failed by omission without the omission being visible.** *An artifact that cannot say where it is most likely to be wrong has either not looked or is not saying — and both are findings. Claiming completeness is easy; naming the direction in which future evidence would most likely overturn you is not.*

### Negative-claim discipline *(ARB, 2026-07-30 — scoped as directed, with the wider evidence disclosed)*

> ***Negative claims require explicit evidence of SEARCH SUFFICIENCY.*** *A positive claim is supported by exhibiting an instance; a negative claim ("nowhere", "no governed rule", "the model never…") cannot be, so its strength rests entirely on the adequacy of the search space.*

**Adopted for the TRANSFORMATION-FIDELITY emphasis, as the Authority scoped it:** a transformation-fidelity review verifies not only the cited evidence but the **declared search scope** behind any corpus-wide absence claim. *Admission evidence: **AFV-R2** — AFV-1's absence claims were stated over "the model" while its disclosed instrument was M6 §3.1–§3.4's (b) columns.*

**Disclosed, not acted on: the evidence supports a WIDER form than the Authority scoped, and the second instance is the reviewer's own.** **AD-R1 asserted *"no governed rule forbids cycles"* — a corpus-wide negative — and did not state its search scope either.** *So the defect is not specific to verification artifacts; it is a property of negative claims wherever they appear, including in reviews.* **Recorded for the Authority rather than self-promoted: widening a directive on the reviewer's own authority is precisely the overreach this framework declines elsewhere, and the scoped form is in force until the Authority says otherwise.**

### ⛔ THIRD instance — 2026-07-31, and the rule was ALREADY IN FORCE in this document when it was broken

**The claim published:** *"no artifact of that name exists in the repository"* — a **corpus-wide negative**, asserted in the MCA-R1 input package to justify an identifier label. **`PKS_Phase_II_Method_Certification_Assessment.md` existed, and "MCA" already denoted it across four artifacts.**

**The Authority's analytical boundary, adopted as the statement of the defect:**

> ### **The lesson is not *"search better."* It is: DO NOT MAKE AN EXISTENTIAL CLAIM UNLESS YOU HAVE ESTABLISHED IT.**

**The distinction that carries it: *the search performed* and *the claim published* are different objects.** *The search was two greps ("Methodology Change Assessment"; filenames matching `mca|methodology`). The claim was corpus-wide. **No statement of search scope accompanied it — the exact omission this rule names.***

| | |
|---|---|
| **Instances now** | **n = 3 — AFV-R2 · AD-R1 · the MCA-R1 label.** **All three are the reviewer's own** |
| **Aggravating fact, recorded rather than softened** | ***The rule was already stated in THIS document, which the same party maintains, when the violation occurred. A governed rule was not absent, not unclear, and not new — it was simply not applied.*** |
| **What this is NOT** | **Not a new candidate.** *The scoped rule is adopted; the wider form is already disclosed-not-acted-on. This is a third DATUM for a proposition already on the record, and creating a new candidate for it would double-count the same rule* |
| **Distinct from PMR-10** | **Orthogonal.** *PMR-10 governs **creating identifiers safely**; this governs **publishing existential claims**. The MCA incident violated both, which is why it supplies a datum to each* |

***The wider form's evidence is now three-for-three from one party, which is a fact about the party as much as the rule — and it is the strongest argument that the rule needs to be a STEP rather than a standard, since knowing it demonstrably did not produce compliance.***

### The ONLY interchangeable module: the emphasis

| Artifact category | Emphasis | Governing question | Weighted objectives |
|---|---|---|---|
| — | *(none — full pass)* | Is the governed knowledge sound? | 1–11 |
| **Knowledge artifact** | **Publication** | Does it faithfully publish a model it must not change? | **7** currency · **1** marker discipline · **11** responsibility |
| **Reflective artifact** | **Retrospective** | Does it preserve what was learned without performing governance? | **11** responsibility · **1** epistemic class · **5** classification · **4** governance boundary |
| **Derived artifact** | **Architecture definition** | Does it derive architecture without exceeding its authority? | **1** derivation vs interpretation · **2** DDD integrity · **6** strategic/tactical boundary · **4** governance boundary |
| **Verification artifact** | **Transformation fidelity** | Does it verify that a transformation preserved governed knowledge — **its object is the transformation, not the destination artifact**? | **1** applied to the **verifier's own** claims · **quality E** traceability · **5** classification · **4** governance boundary · **7** temporal |
| **Change propagation design** | **Controlled-change integrity** | Does it design deterministic propagation of authorized decisions **without executing**, and can an independent team execute it without unstated judgment? | **1** design vs execution · **4** governance boundary · **quality E** per-item provenance · **8** dependency consistency · **5** classification |
| **Governance impact analysis** | **Authority-impact integrity** | Does it analyze the consequences of a PENDING decision without becoming that decision? *(It sits **between Review and Authority** as a governance safety mechanism.)* | **1** analysis vs decision · **4** governance boundary · **5** classification · **7** temporal · **quality E** consequence traceability |
| **Governance decision record** | **Authority-disposition integrity** | Does it record Authority decisions without inventing authority, modifying evidence, redesigning, or weakening governance traceability? | **1** decision vs rediscovery · **4** governance boundary · **quality E** decision traceability · **5** classification · **8** consistency |
| **Strategic relationship model** | **Relationship integrity** | Does it interpret the structure BETWEEN already-disposed elements without architectural invention, unsupported pattern assignment, or premature certainty? | **1** interpretation vs invention · **2** context-map semantics · **quality E** evidence per relationship · **5** classification · **7** temporal |
| **Strategic discovery artifact** | **Discovery integrity** | Does it discover strategic knowledge **without inventing it** — evidence before candidates, declared thresholds, honoured falsification, preserved uncertainty? | **1** discovery vs invention · **quality E** evidence lineage · **7** temporal · **8** cascade · **5** classification |
| **Representation artifact** | **Representation fidelity** | Does it communicate the governed architecture without adding, removing, strengthening, weakening or silently interpreting architectural meaning? | **1** representation vs interpretation · **8** consistency · **7** temporal · **quality E** provenance · **6** strategic/tactical boundary |

### The Knowledge Governance Lifecycle *(ARB, 2026-07-30 — replaces the flat statement of responsibilities)*

**Knowledge progresses through separated strategic responsibilities. Each stage CONSUMES the previous and PRODUCES inputs for the next. No stage may repeat or replace another.**

> **Discovery** identifies candidate knowledge → **Review** evaluates discovery → **Impact Assessment** analyzes the governance consequences of a pending decision → **Authority** transforms evaluated knowledge into governance decisions → **Controlled Change Planning** designs deterministic propagation → **Execution Readiness Verification** verifies the design → **Editorial Execution** implements the approved plan → **Consolidation** stabilizes accepted knowledge → **Strategic Modeling** derives relationships → **Certification** evaluates the governance process itself.

**Two stages were added at the ninth emphasis** *(ARB, 2026-07-30)*: **Impact Assessment** sits **between Review and Authority** as a **governance safety mechanism** — it analyzes consequences so the Authority inherits no hidden assumptions; **Editorial work** sits **after Authority** because *"Authority resolves uncertainty; editors implement resolved uncertainty."* ***The governing separation both stages exist to hold: Authority changes GOVERNANCE state, editorial work changes REPRESENTATION state, and never both in one commission.***

> ***A new LIFECYCLE STAGE does not imply a new review EMPHASIS*** *(ARB threshold decision, 2026-07-30)*. **Execution Readiness Verification joined the lifecycle as a stage while the emphasis count stayed at ten** — ERV-1 introduces no new governance responsibility, it exercises an existing one: **CCP-1 designs execution, ERV-1 verifies the design — two artifact kinds, one responsibility family.** *The first time the stage count and the emphasis count moved independently, and evidence the taxonomy is stabilizing.*

**The SECOND stabilizer, established on independent evidence** *(ARB threshold decision, 2026-07-30 — KBI-1)*:

> ***A new ANALYTICAL SCOPE does not imply a new governance RESPONSIBILITY.***

**KBI-1 changed the unit of analysis from artifact to BASELINE. Its principal finding — *"verification coverage is asymmetric across the transformation chain"* — is findable ONLY at that scope, because it compares a SET of instruments to a SET of links and no single artifact contains that comparison. It nonetheless required no new governance power: KBI-1 recorded, classified and recommended, as every preservation-family review does.** ***A review that sees more does not thereby decide more.***

**The THIRD stabilizer** *(ER-1, 2026-07-30)*:

> ***A new governed ARTIFACT TYPE does not imply a new governance CONTRACT.***

**Confirmed by reviewing an execution record under the existing preservation family with no new contract, family or emphasis slot — while two things lawfully varied: the FACETS examined (stopping integrity) and the MECHANISM of remedy (maintain, not annotate).** ***Expanding the facets of a responsibility is not expanding the responsibility; and the responsibility is invariant while the mechanism varies by object kind.***

**The three stabilizers close three distinct growth routes — a new STAGE, a new SCOPE, and a new ARTIFACT TYPE.**

### The FORM every stabilization takes *(Authority observation, 2026-07-30)*

> ### **Variation in X does not imply variation in Y.**

**This identifies which dimensions are permitted to evolve and which must hold.**

| Stabilizer | X *(permitted to vary)* | Y *(must hold)* |
|---|---|---|
| 1 | Lifecycle **stage** | Review **emphasis** |
| 2 | Analytical **scope** | Governance **responsibility** |
| 3 | Artifact **type** | Governance **contract** |

**⚠️ Two precisions, recorded because miscounting here would misstate what has been closed:**

1. ***The three stabilizers do NOT share one invariant.*** **Y differs across them — emphasis, responsibility, contract. They are therefore three INDEPENDENT results, not three instances of one claim.** *A single-Y reading would make two of them redundant.*
2. **Mechanism variation** (*maintain vs annotate*) **is a COROLLARY inside stabilizer 3, not a fourth stabilizer.** ***It closes no growth route — it describes a variation the framework permits. Counting it as a fourth would imply a fourth route had been closed when none was.***

### ONTOLOGY changes require the same discipline as STATUS changes *(Authority, 2026-07-31)*

> ### **Changing a finding's STATUS (strengthened → confirmed) is different in kind from changing its ONTOLOGY (what kind of finding it is). An ontology change is the BROADER claim and must be justified explicitly, never assumed.**

**The test, and it is decisive:**

| Ask | If yes | If no |
|---|---|---|
| ***Does the ORIGINAL PROPOSITION survive, merely categorized differently?*** | **A genuine class change** | ***A SUBSTITUTION — a new proposition wearing the old finding's identifier*** |

**Evidence — the M7-CF2 case, and it is the reviewer's own error:** *CON-F2's proposition had two limbs — the staging was incompatible, and M7 followed one side. The dissolution test falsified the first, and the second presupposed it. What survived was an observation about M7's internal practice, framed differently from the original. Calling that a "class change" implied continuity that did not exist.*

> ### ***A finding that inherits an identifier inherits a proposition. Where the proposition does not survive, the identifier must not travel — the observation is carried by a NEW finding, and the old one is dissolved.***

**Consequence recorded: a substitution disguised as a reclassification is the same failure as *silently changing the proposition under review* — committed at the moment of classification rather than during analysis, and therefore harder to see.**

### The CLASSIFICATION LADDER for a historical finding *(Authority, 2026-07-31 — makes the distinction operational)*

| Status | Requires |
|---|---|
| **DISSOLVED** | *the original proposition is NO LONGER TRUE* — evidence contradicts it |
| **CONFIRMED** | *the original proposition REMAINS TRUE* — current evidence still supports it |
| **STRENGTHENED** | *remains true **AND** **NEW INDEPENDENT EVIDENCE** now supports it more strongly than when raised* |

> ### ***The third step is the discriminator. Without a requirement for NEW INDEPENDENT EVIDENCE, "confirmed" and "strengthened" become indistinguishable — and every surviving finding drifts upward on re-examination.***

**⚠️ Two failure modes the ladder prevents, both observed in this programme:**

| Failure | Instance |
|---|---|
| **Treating the WITHDRAWAL OF A MITIGATION as strengthening** | **CON-F2 (corrected).** *Removing M7's excuse changed the finding's culpability reading, not its evidence — the greps were identical* |
| **Treating a CROSS-CASE PATTERN as support for one case** | **IBC-M4 (corrected below).** *CON-F2 evidences that the memberships are absent from M7; it is not evidence about IBC-1's content* |

**Audit of every "strengthened" recorded in the IBC-1 re-certification, run against this ladder:**

| Finding | New independent evidence? | Verdict |
|---|---|---|
| **IBC-M1** *(baseline list omits artifacts)* | ✅ **Yes — the baseline GREW** (AFV-1 · AIA-1 · KBI-1 · ERV-1 · CCP-1 · SDM v1.1 · CDR-R1 are now also omitted) | **STRENGTHENED stands** |
| **IBC-M3** *("certified" conflates statuses)* | ✅ **Yes — a THIRD status now exists (PROMOTED)**, so the conflation is broader | **STRENGTHENED stands** |
| **IBC-M4** *(contested memberships never named in IBC-1)* | ⛔ **NO.** *CON-F2 is evidence about **M7's** content, not IBC-1's. It shows a PATTERN across artifacts, not added support for this claim* | ⛔ **CORRECTED to CONFIRMED** |

***The ladder earned its place immediately: applied to prior work it upheld two classifications and overturned one — and the one it overturned was overturned for the same reason as CON-F2, which is the reason a ladder exists rather than a judgment.***

### RESTATE THE ORIGINAL CLAIM FIRST — and what it prevents *(Authority, 2026-07-31)*

| 1 | **Restate the original claim** |
|---|---|
| **2** | **Verify the current object** |
| **3** | **Compare claim and evidence** |
| **4** | **Only then classify** |

> ### ***What this avoids: SILENTLY CHANGING THE PROPOSITION UNDER REVIEW. Many review processes drift into testing a different question than the original one — and a drifted review can be entirely rigorous about the wrong claim.***

**Observed, not prescribed — recorded as a pattern the programme converged on, not a rule admitted to the methodology.**

### What a surviving review is and is not evidence of *(Authority, 2026-07-31 — operational form)*

| Question | Does a surviving review answer it? |
|---|---|
| **What reasoning was used?** | ✅ **Yes** |
| **What conclusions were reached?** | ✅ **Yes** |
| **Does the reviewed object still exist?** | ⛔ **Not by itself** |
| **Are the conclusions still independently reproducible?** | ⛔ **Not by itself** |

***Four distinct evidentiary questions where the earlier absolute formulation collapsed them into one. The refinement increases precision without weakening the caution — and the first two rows are why 19 of 21 governing bases could be re-certified at all.***

### Why the evidence/reasoning split matters *(Authority, 2026-07-31)*

> **Evidence should be independently VERIFIABLE. Reasoning should be independently REVIEWABLE.**

***Keeping them separate lets a later auditor disagree with the reasoning without disputing the evidence, or dispute the evidence without discarding the reasoning. Collapsed, a challenge to either becomes a challenge to both.*** *Stated in the AFV-F4 premise verification as: the governance reasoning is the reviewer's; the facts are the record's.*

### OBSERVATION — the verification sequence that emerged *(Authority observation, 2026-07-31 — IBC-1 re-certification)*

**Recorded as the Authority framed it: an observation about the sequence that EMERGED across these reviews, NOT a prescription.**

| 1 | **Verify that the OBJECT EXISTS** |
|---|---|
| **2** | **Verify the governing SOURCES** |
| **3** | **Verify the APPLICABILITY of historical findings** |
| **4** | **Only then CLASSIFY their current status** |

***Methodologically stronger than immediately revisiting the findings themselves — and step 1 was decisive exactly once: the IBC-1 re-certification could not have reached its result by starting at step 2, because the object's absence is invisible from the governing sources.***

**⚠️ Recorded as an OBSERVATION and NOT raised as a methodology candidate, deliberately:** *the Authority stated it descriptively — "that ordering reflects the same discipline that has emerged" — and did not propose it as a rule.* **Under Discipline Amendment 4 that is RECOGNITION, which is not adoption and is not yet even a proposal.**

***Were it stated as a prescription it would become a methodology candidate requiring PMR → MCA → CDR, and it would be DISTINCT from PMR-9: PMR-9 requires verification before CLASSIFICATION and PRESUPPOSES an object; step 1 requires establishing that the object exists at all. Noting the relationship so a future assessment can decide whether to merge them — and not raising a candidate the Authority did not propose.***

### VERIFICATION is itself a result *(Authority determination, 2026-07-31 — CDR-1)*

> ### **VERIFICATION THAT A CONSTITUTIONAL BOUNDARY IS CORRECTLY MAINTAINED IS A POSITIVE REVIEW RESULT.**

*(Authority phrasing, 2026-07-31, adopted in place of the reviewer's broader ~~"a review whose value is primarily verification is a legitimate outcome"~~ — **the claim is tied to DEMONSTRATED verification, and does not assert that every verification exercise necessarily produces a positive result.**)*

**The distinction that carries it:** *no findings because nothing significant was examined* ⟂ ***no findings because the highest-risk boundaries were deliberately tested and found correctly bounded.*** **Only the second produces governance evidence.**

**CDR-1 produced no finding against its artifact. That is not a null result:** *each of the four surfaces most susceptible to overreach turned out, on source verification, to be **explicitly bounded by earlier governance** — recognition supported by the MCA as an input, configuration control staged as a CDR act, MCR-5's split faithful to the advisory, freeze wording referring to the governed record plus adopted refinements.*

***That is POSITIVE EVIDENCE that an authority decision remained within its constitutional role — a stronger statement than "no defects were found," which is compatible with not having looked.***

**Pairs with the exemplar case below:** *MC-1 showed a review's value is not the COUNT of its findings; CDR-1 shows that value can be the ESTABLISHMENT OF A BOUND rather than the discovery of a breach.*

### Reviews do not absorb adjacent governance work *(established practice, recorded 2026-07-31)*

> ### **A REVIEW'S SCOPE IS DETERMINED BY ITS COMMISSION, NOT BY EVERYTHING IT CAN OBSERVE.**

*(Authority generalization, 2026-07-31 — stronger and more general than any single instance; the reviewer's **visibility is not jurisdiction** is retained as its compressed form.)*

| Evidence | What became visible | Where it went |
|---|---|---|
| **CON-1** | CON-F2 — a possible consequence in promoted M7 | **Routed to its own commission**; the review reported and stopped |
| **CDR-1 §6** | A defect in the reviewer's own prior act (SDM v1.1's authority claim) | **Reported, expressly NOT adjudicated** |
| **CDR-1** | Consolidation is authorized but uncommissioned | **Noted; no commission issued by the review** |

***Visibility is not jurisdiction. The alternative — a review that fixes what it happens to see — produces artifacts whose scope is determined by the accidents of what a reviewer noticed.***

### The review programme's discriminating standard *(Authority observation, 2026-07-31)*

**Four consecutive reviews, four distinct governance responsibilities, four contracts tailored to the responsibility rather than one generic checklist:**

| Review | Object | Responsibility verified |
|---|---|---|
| **ER-1** | Execution Record | **Execution discipline** |
| **CON-1** | M6 Consolidation | **Stabilization discipline** |
| **MC-1** | Method Certification Assessment | **Inference discipline** |
| **CDR-1** | CDR Decision | **Authority discipline** |

> ### **The standard is no longer *"is this document correct?"* but ***"did this document faithfully perform ITS SPECIFIC CONSTITUTIONAL ROLE?"***

***A more discriminating standard, and the reason the taxonomy did not need to grow to accommodate four different object kinds: the CONTRACT varied while the FAMILY, the EMPHASIS COUNT and the RESPONSIBILITY held — which is stabilizer 3 observed from the inside.***

**⚠️ Epistemic bound, stated at the Authority's instruction:** ***this is an OBSERVED PROPERTY OF THESE FOUR REVIEWS, not a universal proof.*** **It is encouraging evidence that the taxonomy has appropriate expressive power — not a demonstration that it always will.** *Consistent with the standing status of all three stabilizers: derived from this programme's evidence, falsifiable, WITHDRAWN as their lawful exit.*

### EXEMPLAR CASE — a review's value is not the count of its findings *(Authority determination, 2026-07-31)*

**The MC-1 sequence, recorded as the programme's exemplar of disciplined review:**

| 1 | A plausible **Major** finding appeared *(the composite inherits its strongest component's confidence)* |
|---|---|
| **2** | **Verification challenged the CLASSIFICATION, not the observation** |
| **3** | **The classification dissolved** *(the two values are not of the same kind)* |
| **4** | **The finding disappeared** |
| **5** | **A methodological candidate remained** |

> ### ***Disciplined review is not measured by the number of findings produced. It is measured by the CORRECTNESS OF THE FINDINGS THAT SURVIVE.***

**What made the dissolution probative is that it was COSTLY: confirming the finding would have discharged a deferred candidate (PMR-2), closed an open CDR-R1 item, and produced the review's only Major.** ***A verification that only ever subtracts cheap findings demonstrates nothing.***

**Observed across three consecutive reviews and recorded as an OBSERVATION about the reviews, not as methodology** *(per the Authority: "I would stop short of elevating that into methodology — that is exactly what PMR candidates are for")*: **ER-1's candidates dissolved on checking governing records · CON-1's ontology shifted · MC-1's Major dissolved and a false historical claim was replaced.** ***Not three anecdotes: three demonstrations that conclusions changed when the evidence required it.***

### The currency discriminator *(CON-1, 2026-07-31 — complements the load-bearing test)*

> ### **Statements about the WORLD age. Statements about THIS ACT do not.**

**Test: does the claim assert a state EXTERNAL to the artifact, or the SCOPE OF THE ARTIFACT'S OWN ACT?** *"This act performed no MCA"* is permanently true; *"no MCA exists"* lapses the moment one does.

**It complements the load-bearing criterion rather than replacing it:** *load-bearing asks whether a claim EXPLAINS a later act; this asks whether the claim was ever about anything but the act itself.* ***A claim can fail the load-bearing test and still never age.***

**Evidence: CON-1 found NO currency defect in a closure artifact every one of whose staged acts had since executed — because §7's nine exclusions and its "STOP" are statements about that act's scope.** *The discriminator therefore discriminates: it flags artifacts asserting a state of the world and spares those asserting a state of their own act.*

**⚠️ Epistemic status, stated deliberately** *(Authority correction, 2026-07-30)*: **these are GOVERNANCE PRINCIPLES DERIVED FROM THIS PROGRAMME'S EVIDENCE — not laws.** *"They have been demonstrated repeatedly here, but they remain open to revision if future evidence uncovers a genuinely new responsibility or governance power."* **Both sit at GOVERNED on repeated evidence, which under the admission ladder is precisely the status that remains falsifiable, with WITHDRAWN as its lawful exit.** ***A stabilizer that could not be withdrawn would be the unfalsifiable control the ladder exists to prevent.***

**Every review must verify the lifecycle remains intact for the artifact in front of it.** *The lifecycle is what makes "no stage may repeat another" checkable: a stage that consumes something other than its predecessor's output, or produces something other than its successor's input, has stepped out of position.*

**Its DDD reading, adopted: governance is a bounded context with its own ubiquitous language, responsibilities, lifecycle and invariants** — *Authority · Disposition · Acceptance · Return · Reopening condition · Ordered fold · Commission*. **The Authority stage is where strategic knowledge becomes organizational commitment: *it does not create knowledge, it creates commitment*.** So a decision-layer artifact must keep five concepts unblurred — **Evidence · Interpretation · Decision · Governance · Execution.**

### Decision Model Integrity — a review area for decision-layer artifacts *(ARB, 2026-07-30)*

**Treat a disposition as a strategic decision record. Every decision must exhibit eight properties:** derives from recorded evidence rather than preference · explicit **scope** · explicit **authority** · explicit **rationale** · explicit **confidence** · explicit **downstream consequences** · explicit **reopening conditions** · **immutable once recorded, except through additive governance.**

**And four aggregate properties: reproducible · auditable · bounded · historically stable.** ***A strategic decision must never depend on undocumented reasoning.***

### Authority Restraint, stated POSITIVELY *(ARB, 2026-07-30 — the negative form alone was insufficient)*

**Restraint is not only what an Authority refuses. Verify that it:** decides only what has reached **governance maturity** · preserves uncertainty where evidence is incomplete · **binds reopening conditions** rather than resolving uncertainty prematurely · **commissions** future work instead of performing it · records decisions without rewriting history · governs through **explicit authority rather than implicit interpretation**.

> ***A mature Authority record is characterized as much by the decisions it refuses to make as by those it records.***

### Observations vs Findings *(ARB, 2026-07-30 — formalized; practiced repeatedly before being written down)*

| Class | Definition | Consequence |
|---|---|---|
| **FINDING** | A defect materially affecting **authority · decision integrity · traceability · governance boundaries · historical correctness · review conclusions** | ***Requires disposition*** |
| **OBSERVATION** | A noteworthy characteristic, refinement opportunity, or presentation inconsistency **not materially affecting integrity** | ***Explicitly carried WITHOUT remediation*** |

> ***Do not elevate an observation into a finding without governance impact.***

**Evidence (n=4, one day): PUB-10** reclassified from defect to Observation on the rule that *a placement observation becomes a defect only when the placement demonstrably misleads* · **OBS-§6** · **OBS-§7** · **OBS-AD-1**.

***The rule matters because the incentive runs one way: a review reporting findings looks more valuable than one reporting observations, and this distinction is the only defence against that pressure.***

**Discriminating tests, one produced by each reclassification exercised so far** *(a class boundary that yields a new test each time it is used is drawn in the right place)*:

| Reclassification | The test it produced |
|---|---|
| **PUB-10** | ***A placement observation becomes a defect only when the placement demonstrably misleads.*** |
| **AIA-R2 → OBS-AIA-1** | ***Modal form vs operational semantics:*** a directive modal is a **finding** only where the artifact's behaviour actually constrains. Where the behaviour is advisory throughout — disposition refused, authorization conditional, Authority ownership preserved, alternatives fully developed — **a directive word in a summary field is an observation** |

**And the discipline that makes the class real: an OBSERVATION IS NOT REMEDIATED, even when the fix is one word.** *Repairing an observation while calling it an observation blurs the boundary the rule exists to hold. OBS-AIA-1's modal was deliberately left as written.*

### The Trustworthiness Test *(ARB, 2026-07-30 — supersedes the informal three-part test)*

| Limb | Question |
|---|---|
| **REPLAY** | Can every decision be reproduced **solely** from recorded evidence, rationale and commission history? |
| **AUDIT** | Can an independent reviewer reconstruct every act **without external explanation**? |
| **INSTITUTIONAL TRUST** | Can the record be trusted **without knowing the individuals who drafted it**, because authority derives from **commissions, evidence and governance roles rather than personal credibility**? |

> ***Failure of any ONE limb constitutes a governance weakness.*** *The three are not a score to average.*

**The third limb is operative and the easiest to fail invisibly: a record that replays and audits perfectly but rests on personal credibility has failed, and the failure stays hidden while the individuals remain available.** **It requires attribution to ROLES rather than persons, and the creating act located in the COMMISSION rather than the drafter.**

### The closing principle *(ARB, 2026-07-30)*

**The purpose of a review is NOT to determine whether the best decisions were reached. It is to determine whether knowledge was transformed into governance without violating the lifecycle's separation of concerns.** A successful record exhibits **epistemic · governance · historical · Strategic-DDD discipline, and architectural restraint.**

> ***The strongest record is one that can be REPLAYED from its evidence, AUDITED from its history, and TRUSTED without requiring knowledge of the individuals who produced it.***

**The third limb is the operative one and the easiest to fail: it requires attribution to ROLES rather than persons, and the creating act located in the commission rather than the drafter.**

### The two families *(ARB, 2026-07-30 — a classification of the seven emphases, not a new mechanism)*

| Family | Emphases | Central question |
|---|---|---|
| **PRESERVATION-oriented** | Knowledge · Retrospective · Architecture definition · Transformation · Representation | ***Was something faithfully preserved or transformed?*** |
| **INTERPRETATION-oriented** | **Discovery · Relationship** | ***Was something faithfully derived without invention?*** |
| **DECISION-oriented** | **Authority disposition** | ***Was authority exercised without exceeding it?*** |
| **CONSEQUENCE-oriented** | **Authority impact** | ***Were the consequences of a pending decision analyzed without becoming it?*** |
| **EXECUTION-oriented** | **Controlled change** | ***Was execution designed deterministically without being performed?*** |

**A third family appeared at the eighth emphasis:** a **decision** review compares an act against **the authority that permitted it** — not against a source (preservation) and not against evidence (interpretation). *Its characteristic finding is an act that exceeded, or a power that was used where restraint was required.* **The boundary between the first two families is real and is why the middle two are not variants of the first five: a preservation review compares an artifact against a source that already exists. An interpretation review has no such source — it can only check whether *interpretation stayed subordinate to evidence*.** *There is nothing to diff against; the test is whether the artifact refused to complete what its evidence left open.* **M7's ACL refusal and M6's demotion of its own strongest candidate are the canonical instances — in both, the correct output was a **refusal**, which no preservation check would recognize as a result.**

**Ten emphases now exist. Five review a KIND OF PRESERVATION** *(ARB, 2026-07-30)*: **knowledge** · **historical** · **architectural** · **transformation** · **representational**. **The sixth does not.** **Discovery integrity is the first emphasis centred on DISCIPLINED EMERGENCE rather than faithful transformation** — it asks not *"was something preserved?"* but ***"was something discovered without being invented?"*** *Seven emphases, one review capability, no new contract at any point.* **The sixth and seventh both sit outside preservation: discovery integrity asks *was something discovered without being invented?*, and relationship integrity asks *was the structure BETWEEN discovered elements interpreted without being invented?*** *Two artifacts can each be faithful while the relation asserted between them is not — which is why the seventh is not a special case of the sixth.*

**The artifact-CATEGORY column is an explanation, not a licence** *(ARB, 2026-07-30)*: **four categories exist because four artifacts were reviewed, not because a table has four rows.** **The admission rule still governs — an emphasis is admitted when an artifact of that category is actually reviewed and the existing emphases misdirect attention.** *This is the discipline that declined two review contracts at n=0.*

**Nothing else varies between commissions. An emphasis changes *where attention is concentrated* — never what capability performs the review, and never which objectives exist.**

### Dependency care, stated because this section could have re-created FW-1

**This specification is applicable from this document alone.** The six review principles and the invariant section list are **stated here in full**, not cited from elsewhere. **The rule governing *whether a new emphasis may be added* is growth governance and lives in the Review Discipline — but this document does not need it in order to apply an existing emphasis**, so that mention is a **back-reference, not a normative dependency.**

***The acyclic direction `Integrity Model ← Method ← Discipline` still holds.*** *FW-1 was a normative back-edge created exactly this way — by a workflow section reaching upward for something it needed — and it is not repeated here.*

## Required output

**Per finding:** identifier · severity · **category** · **authority basis** · **evidence origin** · description · **why it matters** · recommended resolution.

### Finding vocabulary — **owned by this document** *(relocated from Discipline Rules 8/9/16 by Authority disposition of FW-1, 2026-07-30)*

**These three enumerations are authoritative here and stated nowhere else.** The Discipline references them; it no longer defines them.

| Field | Permitted values — **exactly one** | Origin |
|---|---|---|
| **Authority basis** | *baseline violation · accepted-architecture violation · methodology violation · derived architectural constraint · reviewer recommendation* | Issued as **Rule 8** (ARB 2026-07-28); relocated here 2026-07-30 |
| **Constitutional category** | *architecture defect · governance defect · methodology observation · improvement recommendation* | Issued as **Rule 9** (ARB 2026-07-28); relocated here 2026-07-30 |
| **Evidence origin** — ***PROVISIONAL*** | *artifact-local* (independently reproducible from the reviewed artifact alone) · *cross-artifact* (must identify the governing artifact) · *reviewer knowledge* (must be clearly identified; normally converted into traceable evidence before becoming mandatory) | Issued as **Rule 16** (ARB 2026-07-28), **provisionally adopted — usable and encouraged, not yet permanent discipline** |
| **Severity** | **No canonical enumeration exists** — see **Q-FW-1**. Convention in use: *Critical · Major · Minor · Recommendation* | — |

**Relocation preserved status, it did not upgrade it: evidence origin remains PROVISIONAL** at n=1, with its promotion and demotion conditions still held in the Discipline where they belong (they are growth governance, not output shape). ***Moving a vocabulary between layers changes where it is defined, never how much evidence supports it*** — the same distinction as *cited ≠ mandated*, applied to relocation.

**The reasoning constraints that govern these fields remain reviewer conduct** and are **not** restated here: a finding's constitutional weight derives from its authority basis rather than its severity label (Discipline Rule 8), and severity and category are orthogonal and must never be merged into a single severity list (Discipline Rule 9). **Those are back-references, not dependencies — this section is applicable without them.**

**Final assessment:** overall verdict · architectural strengths · governance strengths · knowledge-integrity assessment · **cross-section cascade risks** · recommended Authority dispositions · **whether the document remains a faithful representation of the certified strategic knowledge.**

## Standing prohibitions

Never **invent authority · strengthen evidence · weaken uncertainty · assume governance · perform Authority acts · redesign the architecture.**

**When uncertain, raise a question instead of making a correction** *(substance of Discipline Rule 11, stated inline — a back-reference, not a dependency)*.

---

*Traceability: objectives 1–10 issued by the Authority 2026-07-29 (recorded as v2.0 Part VI of the Review Discipline, admitted by that document's change discipline and independently satisfying its admission filter on two named escaped defects — knowledge lifetime and cascade search) · objective 11 issued 2026-07-30 with its own named admission evidence · extracted to this document 2026-07-30 under the three-way separation, whose warrant is objective 11 applied to the framework itself · Discipline Rules 1–18 remain in force inside these objectives · qualities referenced, not restated · **warrant reworded 2026-07-30** (objective 11 revealed a better decomposition; the framework was not invalid) · **dependency direction declared acyclic** — this document depends on the Integrity Model only — with **FW-1 recorded as the one normative back-edge (the finding vocabulary at §Required output), disclosed and routed to the Authority rather than self-fixed** · **FW-1 DISPOSED 2026-07-30 — resolution (a) ACCEPTED by the Authority: the three enumerations of Rules 8/9/16 relocated into §Finding vocabulary and now authoritative here; the reasoning constraints and Rule 16's provisional status retained in the Discipline as conduct and growth governance; Rules 5/11/12/14 deliberately NOT moved (no back-edge, and moving them would be reviewer-initiated layer redesign); graph now acyclic; severity's missing enumeration raised as Q-FW-1 rather than invented** · **duplicate framework-layer map removed 2026-07-30 in favour of a reference to the Integrity Model's canonical map** — the two copies had already begun to diverge in layer naming.*
