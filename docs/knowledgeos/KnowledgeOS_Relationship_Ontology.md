# KnowledgeOS — Relationship Ontology

| | |
|---|---|
| **Kind** | ⭐ **RELATIONSHIP MODEL.** ⛔ ***Not another taxonomy · no repository redesign · no implementation · no tooling · no ADR.*** |
| **Status** | ⭐ **STRATEGIC ONTOLOGY CANDIDATE** — *status adopted per review, 2026-08-02* |
| ⛔ **Cross-product validated** | **`KnowledgeOS_Ontology_Cross_Product_Validation.md`** — ⚠️ **SURVIVES, BUT NOT UNCHANGED**. *8/12 concepts and 10/11 invariants held; **`I-4` FALSIFIED**; "one PKS per product" **FAILED*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02 |
| **Rule obeyed** | ⛔ **No new concept unless the current model cannot explain existing evidence.** ⭐ *One is admitted on that ground, one is REJECTED* |
| **Success criterion** | ⭐ **every existing artifact placeable with NO additional top-level concept** — tested in §5 |

> ## ⛔ **FOUR CORRECTIONS ACCEPTED — AND THE FIRST IS THE SAME ERROR CLASS AGAIN**
>
> | # | Correction | Position |
> |---|---|---|
> | ⭐⭐ **1** | *The six concepts are **not siblings** — Purpose / Platform / Representation / Application are different ontological layers* | ✅ **ACCEPTED.** ⛔ ***Level mixing — the third time: flat-vs-dimensional, artifact-vs-concept, now layer-vs-sibling*** |
> | **2** | *Mission does not directly generate Capability* | ✅ **ACCEPTED and evidenced** — §1 |
> | ⭐⭐ **3** | *A **PKS contains** artifacts; it is not below them* | ✅ **ACCEPTED.** *A real error in my model* |
> | ⭐ **4** | *The document is an **ontology**, not a meta-model* | ⚠️ **PARTLY.** *`KnowledgeOS_Meta_Model.md` §1 (dimensions, orthogonality, derived functions) **is** meta-model; §2 (concepts) **is** ontology. **It conflated two levels — level mixing again.** Both are named rather than the whole renamed* |

---

## 1. The chain — tested, not assumed

**The reviewer proposes `Mission → Strategy → Principles → Capability`. Both insertions are evidenced; one further layer is discovered; one proposal is rejected.**

| Layer | Evidence | Verdict |
|---|---|---|
| ⭐ **ENGINEERING STRATEGY** | **12+ charter artifacts** — `Round32 Design Governance` · `Round38C-01 Strategic Discovery` · `Round39-00 Methodology Stabilization` · `Round40-00 Hostile Replication` · `Round44/45/46-00` · `Round16/17 Discovery` · `Context Assembly Research` · `Product Discovery` | ⭐ **ADMIT — a real artifact class** |
| ⭐ **ENGINEERING PRINCIPLE** | **six registers with their own identifier series** — **AIP-01..14 · AP-1..10 · PGP-01..05 · MC-01..08 · SD-1..7 · DR-1..8** | ⭐ **ADMIT — abundantly evidenced** |
| ⭐⭐⭐ **DESIGN POLICY (`DP-n`)** | ⭐⭐ **The Capability Catalog binds EVERY capability to a DP:** `CAP-001→DP-1` · `CAP-002→DP-2` · `CAP-003→DP-3` · `CAP-004→DP-4` … | ⭐⭐ **ADMIT — DISCOVERED. Neither of us named it** |
| ⛔ **ENGINEERING DOMAIN owns Capability** | ⛔ **NOT FOUND.** *No artifact links any `CAP-n` to a `PD-n`. PD-1..PD-6 exist; **nothing binds a capability to one*** | ⛔ **REJECT as an owner** |

> ### ⭐⭐ **THE REVIEWER'S CONCERN IS RIGHT — CAPABILITIES MUST NOT FLOAT — BUT THE REPOSITORY ALREADY ANCHORS THEM, AND NOT TO A DOMAIN.**
>
> ```
>   MISSION  →  STRATEGY (charter)  →  PRINCIPLE (AIP/AP/PGP/MC/SD/DR)
>                                        →  DESIGN POLICY (DP-n)
>                                            →  CAPABILITY (CAP-n)
>                                                →  KNOWLEDGE  →  ARTIFACT
> ```
>
> ⭐ **`DP-n` is the missing anchor. Capabilities are owned by design policies, not by domains — and that is observable in one table.**

## 2. Concepts × kind × owner

⭐ **Kinds as the commission defines them: purpose · domain · capability · knowledge · representation · container · runtime element · projection.**

| # | Concept | ⭐ Kind | Owned by | Grade |
|---|---|---|---|---|
| **C-1** | **VISION** | **purpose** | the **sponsor** | OBSERVED |
| **C-2** | **MISSION** | **purpose** | the **sponsor** *(enacted, unratified)* | OBSERVED |
| **C-3** | **ENGINEERING STRATEGY** | **purpose→governance bridge** | **ARB / sponsor** *(charters carry approval asks)* | OBSERVED |
| **C-4** | **ENGINEERING PRINCIPLE** | ⭐ **governance** | **DA** *(AIP, AP, DR)* · **sponsor+ARB** *(MC)* · **platform** *(PGP)* | OBSERVED |
| ⭐ **C-5** | **DESIGN POLICY (`DP-n`)** | ⭐ **governance** | **DA**, via the Catalog | ⭐ **OBSERVED** |
| **C-6** | **ENGINEERING CAPABILITY** | **capability** | ⭐ **its `DP-n`** *(not a domain)* | OBSERVED |
| **C-7** | **ENGINEERING KNOWLEDGE** | **knowledge** | DA *(policy)* · sponsor+ARB *(method)* | OBSERVED |
| **C-8** | **ENGINEERING ARTIFACT** | ⭐ **representation** | its `owner` field | OBSERVED |
| ⭐ **C-9** | **KNOWLEDGE SPACE** | ⭐⭐ **container** | the space's own owner *(e.g. **`owner: nab.raj.sharma`** for the EKP)* | ⭐ **OBSERVED** |
| **C-10** | **PKS** | ⭐ **container** *(a product-scoped knowledge space)* | the product team | OBSERVED |
| **C-11** | **PRODUCT** | **application** | the product team | OBSERVED |
| **C-12** | **RUNTIME ADAPTER** | **runtime element** | ⛔ **no owner found** | OBSERVED |
| **C-13** | **PROJECTION** | ⭐ **projection** | the space that generates it | OBSERVED |

### ⭐⭐ C-9 KNOWLEDGE SPACE — admitted, and the evidence is a *boundary*, not a folder

| Evidence | Grade |
|---|---|
| ⭐⭐ **`knowledge-schema.yaml` declares `scope.include: docs/knowledge/**/*.md`** — ⭐ **a knowledge space is a set of artifacts with a DECLARED BOUNDARY** | ⭐ **OBSERVED** |
| **Three registered documentation roots** — `knowledgeos` · `pks` · `publicdigit` | OBSERVED |
| **ES-005.1's three concerns** — Product · Platform · Runtime mount | OBSERVED |
| ⭐ **The EKP has its own constitution and its own owner** — a space governs itself | OBSERVED |

> ### ⭐ **A Knowledge Space is not a folder. It is a DECLARED SCOPE with an owner and a governing constitution.** ⛔ *Which is why the "13% coverage" question was a scope decision, not a bug — the space's boundary is asserted, not discovered.*

## 3. The relationship model — the commission's seven questions

⭐ **`contains` = a container relation · `references` = a pointer · `generates` = produces a new instance · `depends on` = cannot be understood without.**

| Concept | may CONTAIN | may REFERENCE | may GENERATE | DEPENDS ON |
|---|---|---|---|---|
| **VISION** | ⛔ nothing | Mission | Strategy | ⛔ nothing *(root)* |
| **MISSION** | ⛔ nothing | Vision | **Strategy** | Vision |
| **STRATEGY** | ⛔ nothing | Mission · Principle | **Principle** · Charter-authorized work | Mission |
| **PRINCIPLE** | ⛔ nothing | Strategy · other Principles | ⭐ **Design Policy** | Strategy |
| ⭐ **DESIGN POLICY** | ⛔ nothing | Principle · the invariant it names | ⭐ **Capability** | Principle |
| **CAPABILITY** | ⛔ nothing | its DP · the Knowledge it reads | ⭐ **Evidence** · Assessments | Design Policy · Knowledge |
| **KNOWLEDGE** | ⛔ nothing | other Knowledge | ⛔ **nothing** — *it is represented, never generative* | Principle *(for rules)* |
| ⭐ **ARTIFACT** | ⛔ **nothing** — *it is a leaf* | any concept | ⛔ nothing | ⭐ **the Knowledge it represents** |
| ⭐⭐ **KNOWLEDGE SPACE** | ⭐⭐ **ARTIFACTS · other Knowledge Spaces** | other spaces | ⭐ **PROJECTIONS** | its constitution + declared scope |
| ⭐ **PKS** | ⭐⭐ **product Artifacts** ⛔ *never reusable Method* | Engineering Knowledge | Product Projections | Engineering Knowledge · Product |
| **PRODUCT** | code · tests · deployment | its PKS | ⭐ **Evidence** | PKS *(guides)* · Capability *(verifies)* |
| **RUNTIME ADAPTER** | its configuration Artifacts | ⛔ **capability vocabulary only** | ⛔ nothing | the Capability Mapping |
| ⭐ **PROJECTION** | ⛔ nothing | its sources | ⛔ nothing | ⭐ **the space that generates it** |

## 4. ⭐⭐ Invariant relationships vs implementation choices

> ### **This is the durable half of the model. An invariant may never be changed by a project; a choice may be changed freely.**

### ⭐ INVARIANT — each traced to a governed statement

| # | Invariant | Source |
|---|---|---|
| ⭐⭐ **I-1** | **Knowledge is REPRESENTED by an Artifact. An Artifact is never the Knowledge.** | ⭐ *RQ-002's first smoking gun; the fix for the artifact-absence error* |
| ⭐ **I-2** | **A Capability protects EXACTLY ONE invariant** *(one `DP-n`)* | CAP-001: *"CAP-001 stops growing"* · the Catalog's 1:1 CAP→DP binding |
| ⭐ **I-3** | **A Knowledge Space has a DECLARED boundary** | `scope.include`; ES-005.2 *(a directory exists only when its first artifact arrives)* |
| ⚠️ **I-4** *(UNDERSPECIFIED — falsification WITHDRAWN 2026-08-02)* | ⭐ **NOT false — INCOMPLETE.** *It holds **until attestation**.* ⛔ ~~FALSE as a general rule~~ *A **discharge summary** and a **financial statement** are both GENERATED and AUTHORITATIVE.* ⭐⭐ **CORRECTED DIAGNOSIS: a discharge summary is not authoritative BECAUSE it was projected — it became authoritative via `DERIVED → REVIEWED → ATTESTED → RELEASED`. **Governance created the authority, not the derivation.** ⭐ **So DP-2 does not state a falsehood — it CONFLATES DERIVATION WITH ATTESTATION.** ⛔ **And the mechanical cause: `status` has a governed state machine (roles + lint-checkable guards); `authority` has NONE — so “attested” is inexpressible.** Record: `KnowledgeOS_Lifecycle_Gap_Analysis.md` | **DP-2** · ⭐ **reclassified: a LIFECYCLE gap, not an ontology falsification** |
| ⭐ **I-5** | **ONE authoritative artifact per topic + context** | ⭐ **`single_authoritative` — lint-ENFORCED** |
| ⭐ **I-6** | **The Runtime never owns Knowledge** | ES-005.1: the mount *"is never 'the architecture'"* |
| ⭐ **I-7** | **A PKS never contains reusable Method** | ⛔ **stated and BREACHED** *(EAD-1)* |
| ⭐ **I-8** | **Governance precedes automation; automation never defines governance** | Reference Architecture §1 |
| ⭐ **I-9** | **Governance-status ⟂ Authority** | `statuses.yaml`: *"status … is INDEPENDENT of authority"* |
| ⭐ **I-10** | **A constitutional principle is void-overriding** | `Round39-MC` **and** `Round47-00`, independently |
| ⭐⭐ **I-11** *(RESTATED 2026-08-02)* | ⛔ ~~Knowledge is never generative~~ → ⭐ **KNOWLEDGE DOES NOT EXECUTE. Capabilities execute. Knowledge CONSTRAINS AND INFORMS generation.** *My original wording was too strong — knowledge does participate in generation (Knowledge → Design Policies; KnowledgeOS → PKS).* ⭐⭐ **The restatement SURVIVED cross-product projection where the original would have failed** | review 2026-08-02; validated in all three domains |

### ⛔ IMPLEMENTATION CHOICES — changeable without breaking the model

| Choice | Currently |
|---|---|
| Which **folder** a Knowledge Space maps to | three registered roots |
| **Markdown** as the artifact representation | universal here |
| **YAML** for schemas · **Mermaid** for the graph | current |
| ⭐ **The graph's declared scope** | ⭐ **`docs/knowledge/**` — a DECISION, not an invariant** |
| **Claude Code** as the runtime adapter | n=1 |
| **Frontmatter** as the metadata carrier | L-A only |
| `CAP-nnn` / `DP-n` **identifier formats** | current |
| That **Projections are Mermaid + markdown** | current |

> ### ⭐ **11 invariants · 8 choices. A future adopter may change every choice and none of the invariants — that is what makes this a platform rather than a repository layout.**

## 5. ⭐ Success-criterion test

⛔ **If any row needed a new top-level concept, the model would be incomplete.**

| Existing thing | Kind | Space | Concept placement |
|---|---|---|---|
| `identifier-check.php` | representation | platform | **Artifact** representing **CAP-001** |
| `governed-registers.yaml` | representation | ⛔ **product** | **Artifact** carrying a **Binding** *(a kind of Knowledge)* |
| `portal/graph/knowledge-graph.md` | ⭐ **projection** | EKP | **Projection** generated by the **EKP space** |
| `.claude/settings.json` | representation | ⭐ **runtime** | **Artifact** carrying **Boundary/Trigger** rules |
| `Round39-MC` | representation | platform | **Artifact** carrying **Principles MC-01..08** |
| `Round40-00 F-THR Charter` | representation | platform | **Artifact** carrying a **Strategy** |
| The 106 verification reports | representation | product | **Artifacts** carrying **Evidence** |
| `docs/knowledge/` | ⭐ **container** | — | ⭐ **a Knowledge Space** *(declared scope + constitution + owner)* |
| `PKS_..._M4` | representation | ⭐ **PKS** | **Artifact** inside the **PKS container** |
| CAP-001 §9 evidence record | representation | product | **Artifact** carrying **Evidence** generated by a **Capability** |
| **This document** | representation | platform | **Artifact** carrying **Knowledge** *(an ontology)* |

> ### ✅ **All eleven place. ⛔ No additional top-level concept required.**
> ⭐ **And the test earned its keep twice:** *`governed-registers.yaml` sits in the **product** space while the capability reading it is **platform** — **a Capability may read across a space boundary, but may not own what it reads**. That relation was not in §3 and is now recorded as **I-12 candidate**.*

## 6. Missing concepts — recorded as hypotheses, ⛔ not admitted

| # | Candidate | Evidence | Why not admitted |
|---|---|---|---|
| **H-1** | ⭐ **`I-12`: a Capability may READ across a space boundary but never OWN what it reads** | ⭐ CAP-001 reads product registers from the platform | ⚠️ **n=1 capability.** *Needs a second cross-boundary reader* |
| **H-2** | **ENGINEERING DOMAIN as an owner of Capabilities** | PD-1..PD-6 exist | ⛔ **no artifact binds a CAP to a PD.** *DP-n already fills the ownership slot* |
| **H-3** | **KNOWLEDGE SPACE nesting** — *does the EKP contain the PKS, or sit beside it?* | three roots are siblings; ES-005.1's concerns are siblings | ⚠️ **containment vs adjacency unevidenced** |
| **H-4** | **Whether OPERATIONAL-STATE attaches to the Artifact or to the Knowledge it carries** *(MM-4)* | `Round39-MC` is enforced by **what it says**, not how it is stored | ⚠️ **would change I-1's granularity** |

## 7. Open questions

| # | Question | Authority |
|---|---|---|
| ⭐ **RO-1** | **Adopt `DP-n` as a named layer?** *It is observable in the Catalog but never declared as a concept* | **ARB** |
| **RO-2** | Are Knowledge Spaces **nested or adjacent**? *(H-3)* | ARB |
| **RO-3** | Is **I-7** *(PKS never contains reusable method)* to be repaired or withdrawn? ⛔ *it is breached* | ARB |
| **RO-4** | Does the **Runtime Adapter** need a declared owner? ⛔ *none found* | DA |
| **MM-1 / MQ-1** *(carried)* | Adopt RQ-002 · ratify the enacted mission | ARB · sponsor |

---

## ⭐ Closing

| | |
|---|---|
| ⛔ **Conceded** | **level mixing — the third instance.** *Purpose / platform / representation / application are not siblings, and I listed them as one set* |
| ⭐ **Accepted and evidenced** | **Strategy** *(12+ charters)* and **Principle** *(six registers)* are real layers between Mission and Capability |
| ⭐⭐⭐ **Discovered** | ⭐ **DESIGN POLICY (`DP-n`) — the actual anchor of capabilities.** *The reviewer was right that capabilities must not float; the repository already anchors them, and not to a domain* |
| ⛔ **Rejected on evidence** | **Engineering Domain as capability owner** — *no artifact binds a `CAP-n` to a `PD-n`* |
| ⭐⭐ **Corrected** | **A PKS CONTAINS artifacts.** *And `KNOWLEDGE SPACE` is admitted as a container whose evidence is a **declared boundary**, not a folder* |
| ⭐⭐ **The durable output** | ⭐ **11 invariants vs 8 implementation choices.** *A future adopter may change every choice and none of the invariants* |

> ### **A taxonomy tells you what exists. A relationship model tells you what may change.**
> ### ⭐ **That is the difference this document adds — and the eight choices are as important as the eleven invariants, because they are the permissions.**

---

*Traceability: relationship-ontology commission 2026-08-02 · ⛔ **four corrections accepted, the first being LEVEL MIXING — my third instance of that error class** · ⭐ **STRATEGY and PRINCIPLE admitted on evidence (12+ charters; AIP/AP/PGP/MC/SD/DR registers)** · ⭐⭐ **DESIGN POLICY (`DP-n`) DISCOVERED as the true owner of capabilities, observable in the Capability Catalog's 1:1 CAP→DP binding** · ⛔ **ENGINEERING DOMAIN REJECTED as capability owner — no artifact binds a `CAP-n` to a `PD-n`** · ⭐ **KNOWLEDGE SPACE admitted as a container defined by a DECLARED BOUNDARY (`scope.include`), not by a folder; PKS corrected to CONTAIN artifacts** · ⭐⭐ **11 INVARIANT relationships traced to governed statements vs 8 IMPLEMENTATION CHOICES** · **success criterion tested on 11 real things — all place, no new top-level concept needed, and the test surfaced candidate I-12 (read-across-boundary, never own)** · **4 missing concepts recorded as hypotheses, none admitted** · ⛔ **no taxonomy · no redesign · no implementation · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
