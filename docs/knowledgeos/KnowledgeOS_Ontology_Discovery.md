# KnowledgeOS — Ontology Discovery

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC DISCOVERY — the relationships among the archetypes.** ⛔ ***No redesign · no folder change · no ADR · no implementation · no new archetype · no new terminology.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Ontology Discovery, 2026-08-03 (review of the Meta-Model Discovery) — *"the previous commission classified THINGS; this one classifies RELATIONSHIPS"* |
| ⭐ **Freeze lawfulness** | Knowledge Architecture stream — maturing relationships among already-recognized elements. **Nothing new is minted below.** |
| ⭐ **The DDD rule this document obeys** | **The aim of DDD is not classification — it is a shared language that lets people make correct decisions about the domain.** *Every relation below must name the engineering decision it enables (§5). A relation that enables no decision is not admitted, however elegant.* |

> ## ⭐ **CHECK-BEFORE-DISCOVERING — this commission is an ELEVATION, not a greenfield**
>
> ⛔ **A relationship ontology ALREADY EXISTS**: `KnowledgeOS_Relationship_Ontology.md` (2026-08-02) answers CONTAIN / REFERENCE / GENERATE / DEPENDS-ON for 13 *concepts*, carries **11 invariants vs 8 choices**, and passed its placement test on 11 real artifacts. The Relationship Validation Matrix externally validated ten of its edges. The Meta-Model Discovery §4 validated four meta-edges and refuted one.
>
> **Therefore three of the commission's nine questions (own · reference · generate) are substantially answered at concept level. What is genuinely new here:**
> 1. the **lift from concept level to ARCHETYPE level** *(do the edges survive the abstraction?)*
> 2. the questions never asked before: **realize · constrain · temporal · persistent · executable · conceptual**
> 3. the review's **hierarchy hypothesis**, which must be *tested*, not adopted
> 4. the three review challenges *(archetype terminology · Container · Purpose)* — **resolved first, §1**

---

## 1. The three challenges — accepted, and one is settled by my own evidence rules

| Challenge | Disposition |
|---|---|
| ⭐ **1 · "Archetype", not "nine kinds", until MM-1** | ✅ **ACCEPTED.** *"Archetype" already exists in the corpus (RQ-002's pass: "the seven types survive as archetypes"), so the rename mints nothing.* ⛔ **This is a UBIQUITOUS-LANGUAGE decision, and in DDD the language is decided with the domain experts — here, governance (MM-1) — never unilaterally by the modeler.** The Meta-Model Discovery is annotated REV 2 accordingly; **this document says "archetype" throughout.** |
| ⭐⭐ **2 · CONTAINER demoted CONFIRMED → CANDIDATE** | ✅ **ACCEPTED — and my own admission rule decides it, not deference.** Auditing the evidence: the strongest thing a mere `scope + boundary` (dimension values) cannot do is **generate** — but ⛔ **"Containers generate Projections" is observed in exactly ONE space (the EKP's `knowledge-graph.php`). n=1.** The PKS generates no projection; the runtime mount generates none. Nesting is unevidenced (H-3). ⭐ **By the same rule that admits a candidate at n=1 and a kind only on repetition, CONTAINER is a CANDIDATE archetype.** *The settling test is already known: a **second** space generating its own projection confirms; a reduction of the EKP's generator to a capability-plus-scope reading refutes.* |
| ⭐ **3 · PURPOSE underexplored, possibly more fundamental than Container** | ✅ **FLAGGED, not resolved.** *Mission/Vision/Intent/Engineering Goal have indeed been rediscovered repeatedly — that repetition is evidence of a real archetype.* But ⛔ **the mission is enacted and UNRATIFIED (D-5 pending)** — deepening PURPOSE before the sponsor ratifies what the purpose *is* would model ahead of the domain expert. **U-MM-4 stands; "more fundamental than Container" is recorded as a hypothesis (U-ONT-2), n=0.** |

## 2. The archetype relationship matrix — evidence only

**Vocabulary discipline:** the commission's verbs map onto edges the corpus already owns — ⛔ nothing minted. `realizes` = **I-1's "represented by"** *(and R-3's FRBR validation literally uses "realized through")* · `constrains` = **I-11's corrected form** *("knowledge constrains and informs generation")* and the deny/ask boundary rules · `owns` = the `owner:` field and the Catalog's DP-binding · `generates` = produces a new instance *(Relationship Ontology §3's definition)*.

| Archetype | may OWN | may REFERENCE | REALIZES / is REALIZED by | may CONSTRAIN | may GENERATE | Grade |
|---|---|---|---|---|---|---|
| **PURPOSE** | ⛔ nothing | Vision ↔ Mission | — | ⭐ everything downstream, via the chain | Strategy | OBSERVED *(chain §1 of the Relationship Ontology)* |
| ⭐ **DOMAIN** | ⛔ **NOTHING — the thinnest row in the model, and that is a finding, not a gap** *(see below)* | its member concepts | — | ⛔ nothing observed | ⛔ nothing observed | OBSERVED *(by absence)* |
| **CAPABILITY** | ⛔ nothing — *anchored BY its `DP-n`, owns nothing itself* | ⭐ Knowledge — **H-1: may READ across a space boundary, never own** | ⭐ **is realized toward the runtime by a RUNTIME ASSET** — ⚠️ *R-7: externally standard, **internally the CAP↔AST link is undeclared (SC-6)*** | the work it verifies *(fail closed)* | ⭐ **Evidence** *(CAP-001 §9)* | OBSERVED |
| **PROCESS** | ⛔ nothing — *owned via its governing artifact (U-MM-3)* | its protocol | — | the work it sequences *(gates)* | ⭐⭐ **Knowledge · Artifacts · Evidence** — *EEP §8, P-7/P-8, 106 records. **The most generative archetype in the model*** | OBSERVED |
| **KNOWLEDGE** | ⛔ nothing | other Knowledge | ⭐⭐ **is realized by ARTIFACT (I-1)** — *never is one* | ⭐⭐ **I-11: constrains and informs generation — but NEVER executes** | ⛔ nothing directly | OBSERVED |
| **ARTIFACT** | ⛔ nothing — a leaf | any concept | ⭐ **realizes exactly one Knowledge per topic+context** *(I-5, lint-enforced)* | ⛔ nothing | ⛔ nothing | OBSERVED |
| **RUNTIME ASSET** | ⛔ nothing — ⛔ *and I-6: the runtime never owns Knowledge* | ⛔ **capability vocabulary ONLY — governance vocabulary may not leak upward** | ⭐ realizes a Capability at the boundary *(PEP position, R-7)* | ⭐⭐ **the ONLY archetype that constrains AT RUNTIME** — deny×19 · ask×22 | ⛔ nothing | OBSERVED |
| ⚠️ **CONTAINER** *(candidate)* | its declared scope *(constitution + `owner:`)* | other spaces | — | admission to itself *(schema, lint)* | ⚠️ **Projections — n=1 (EKP only)** | ⚠️ CANDIDATE |
| **PROJECTION** | ⛔ nothing | its sources | — | ⛔ nothing | ⛔ nothing | OBSERVED |

> ### ⭐⭐ **THE DOMAIN ROW IS THE DISCOVERY OF THIS SECTION.**
> **DOMAIN is a confirmed archetype with almost no outgoing edges.** It does not own capabilities (refuted), does not produce, does not constrain, does not generate. **What it does is SCOPE MEANING — it is the archetype of classification, not of ownership.**
>
> ⭐ **And this is orthodox strategic DDD, not an anomaly:** *in DDD a (sub)domain never owns artifacts either — it delimits where a language holds; teams and contexts own things.* The repository rediscovered Evans' distinction empirically: **ownership work is done by `DP-n` (governance-natured Knowledge), meaning work is done by Domains.** The review's hierarchy places Domain as an owner in a stack — the evidence says it is a *boundary-drawer standing beside the stack*.

## 3. The nature questions — temporal · persistent · executable · conceptual

⭐ **The dimensional model answers most of this before the archetypes do — which is itself validation of the dimensional shape:**

| Question | Answer from evidence |
|---|---|
| ⭐⭐ **Which are executable?** | **Exactly one archetype has execution as its ESSENCE: CAPABILITY** — I-11 verbatim: *"Knowledge does not execute. Capabilities execute."* **RUNTIME ASSETS execute at the boundary** *(AST-nnn, the only candidate already executable)*. ⚠️ **An ARTIFACT may be executable (`identifier-check.php`) — but that is a REPRESENTATION-dimension property, not an archetype property.** *An executable artifact is still an artifact; executability does not change its kind — RQ-002's smoking gun, passing its third test.* |
| **Which are conceptual?** | **PURPOSE · DOMAIN · KNOWLEDGE** — never executable, never runtime-resident. *I-6 guards the border: the runtime never owns Knowledge.* |
| ⭐ **Which are temporal?** | **PROCESS is the temporal archetype** — *"has a clock"* (Observation Protocol), two lifecycles never conflated (GEP-F1), the WORK-EXECUTION progression kind. **PROJECTION is temporal by rebuildability** — regenerate-at-will, authoritative only via attestation (the corrected I-4). ⚠️ *EVIDENCE-STATUS and OPERATIONAL-STATE — the two candidate dimensions (MM-2) — are where time attaches to everything else.* |
| **Which are persistent?** | **KNOWLEDGE outlives its artifacts** *(the Capability-Mapping demo: knowledge present, artifact absent)* · **ARTIFACTS persist as representations** · **PURPOSE persists by enactment** *(mission enacted through 8 artifacts while unratified)*. |

## 4. The hierarchy hypothesis — ⛔ TESTED AND REFUTED AS A SINGLE STACK

**The review proposes:** `Purpose → Knowledge → Domain → Capability → Process → Runtime → Artifact` — *"that hierarchy hasn't been investigated."* **Investigated now, edge by edge:**

| Proposed edge | Evidence verdict |
|---|---|
| Purpose above all | ✅ **SUPPORTED** — the evidenced chain roots at Mission |
| Knowledge → Domain | ⛔ **NO EVIDENCE — no pass ever produced an edge between them in either direction** |
| Domain → Capability | ⛔⛔ **ALREADY REFUTED** — no artifact binds a `CAP-n` to a `PD-n`; **`DP-n` owns capabilities** |
| Capability → Process | ⛔ **no evidence**; if anything the observed arrow points the other way *(processes exercise capabilities in work)* |
| Process → Runtime | ⚠️ inverted-ish: **P-8 — the runtime HOSTS the work** |
| Runtime → Artifact | ⚠️ only as containment of its own configuration artifacts |

> ### ⭐⭐ **VERDICT: one supported edge, one refuted edge, four unevidenced. The single stack does not exist.**
>
> **What exists instead — and every line of it is already evidenced — is FOUR DISTINCT PARTIAL ORDERS, one per relationship type:**
>
> | Order | The evidenced chain |
> |---|---|
> | ⭐ **AUTHORITY** *(who justifies whom)* | `PURPOSE → STRATEGY → PRINCIPLE → DP-n → CAPABILITY` |
> | ⭐ **PRODUCTION** *(who produces whom)* | `PROCESS → Knowledge / Artifacts / Evidence` · `CAPABILITY → Evidence` · `CONTAINER → Projection (n=1)` |
> | ⭐ **REALIZATION** *(concept → executable/persistent form)* | `KNOWLEDGE → ARTIFACT (I-1)` · `CAPABILITY → RUNTIME ASSET (R-7, SC-6 gap)` |
> | ⭐ **CONTAINMENT** *(what moves together)* | `CONTAINER ⊃ Artifacts (⊃ spaces? — H-3 OPEN)` |
>
> ⛔ **Collapsing four orders into one stack is LEVEL MIXING — the error class this corpus has corrected three times already (flat-vs-dimensional · artifact-vs-concept · layer-vs-sibling). This would be the fourth instance, and this time the draft came from the review, not from me.** *The review's own earlier correction — "the six concepts are not siblings" — is the rule that refutes its own sketch.*
>
> ⭐ **The intuition underneath the stack survives:** relations are directional and DOMAIN/KNOWLEDGE sit on the conceptual side while RUNTIME/ARTIFACT sit on the realized side. **But "which is above" is only answerable PER RELATION — asked of the stack as a whole, the question is ill-posed.**

## 5. ⭐⭐ What the ontology is FOR — the DDD aim, stated as decisions

> ### **DDD's purpose test: a model earns its existence by the decisions it lets the team make correctly. Here is each relation's decision — and the operational scar that proves the decision is real:**

| Relation | The engineering decision it enables | ⭐ The scar that proves it |
|---|---|---|
| **owns** | *who must approve a change* — the question behind U-MM-2, RO-4, and every ownership gap found | the EEP could not be exercised solo **because ownership was real** *(MVK F-1)* |
| **contains** | ⭐⭐ *what moves together when a boundary moves* — **the extraction question** | ⛔ **the MVK FAIL was a containment failure in disguise: the 15-file set was drawn before "what does the platform CONTAIN?" was ever modeled.** *AR-1 realized = the cost of an unmodeled `contains` edge* |
| **realizes** | *what survives deletion of the file* — knowledge vs artifact triage | Capability Mapping: knowledge present, artifact absent *(W-2)* — expressible only because I-1 split them |
| **constrains** | *what blocks work at runtime vs what merely advises* | the deny×19/ask×22/advisory×16 triage — mislabeled until Boundaries≠Triggers was corrected |
| **generates** | *what may be rebuilt vs what must be preserved* | projections regenerate freely **because** they are non-authoritative until attested *(corrected I-4)* |
| **the partial orders** | *in which direction change is allowed to propagate* | the back-edge (evidence→platform) is the loop's thin edge *(n≈3)* — visible only when production and authority orders are kept apart |
|

**And the mission connection, because a model without a mission is technique:** the enacted mission is *engineering knowledge that does not vaporize and demonstrably improves PublicDigit delivery*. **Every relation above serves one of its two halves** — `realizes`/`contains`/`generates` are the anti-vaporization relations *(what persists, what moves, what rebuilds)*; `owns`/`constrains` and the authority order are the delivery-governance relations *(who decides, what blocks)*. ⭐ **A relation serving neither half would be ornament; none of the admitted ones is.**

## 6. The proposed permanent rule — ⛔ it already exists

**The review proposes:** *"No new archetype may be introduced unless it cannot be expressed using the existing ontology."*

⭐ **Check-before-adopting: this rule is already in force, twice:**

| Existing statement | Where |
|---|---|
| *"No new concept unless the current model cannot explain existing evidence"* — **operated as the header rule; admitted one concept, rejected one under it** | Relationship Ontology, 2026-08-02 |
| *new dimension only if **orthogonal · necessary · sufficient — all three*** | model-integrity observation |

**What the review's wording adds is only the LEVEL — archetype instead of concept.** ⛔ **Adopting the generalization is a governance act, not a discovery** — routed to the docket as a candidate rider on D-8 *(the ontology-reconciliation decision, where the rule's scope naturally lives)*. **Nothing is adopted here.**

## 7. Remaining unknowns — not resolved by reasoning

| # | Unknown | Settling evidence |
|---|---|---|
| **U-ONT-1** | **CONTAINER: archetype or `scope+boundary` reduction?** | a **second** space generating its own projection *(confirms)* · a capability-reading of the EKP generator *(refutes)* |
| **U-ONT-2** | **Is PURPOSE more fundamental than CONTAINER?** *(review hypothesis, n=0)* | waits on mission ratification *(D-5)* before PURPOSE can be exercised at all |
| **U-ONT-3** | **The CAP↔AST link (SC-6)** — realization's inner edge is externally standard and internally undeclared | declaring the link is governance work already on the record |
| **U-ONT-4** | **Does any archetype pair have NO possible relation?** *(the matrix's empty cells are absence-of-evidence, not evidence-of-absence — the corpus's own ×3 lesson)* | more placement tests in real work |
| **U-ONT-5** | **Who owns the ontology?** — same answer as U-MM-2: ⛔ nobody | SA-1 / sponsor layer |

---

## ⭐ Closing — the success criterion

> **"Which archetypes may own, reference, realize, constrain, generate — and which are temporal, persistent, executable, conceptual?"**
>
> # ⭐ **ANSWERED FROM EVIDENCE — and the shape of the answer is FOUR PARTIAL ORDERS, not one hierarchy.**
>
> | | |
> |---|---|
> | ⭐ **Elevated, not rediscovered** | the existing Relationship Ontology's 11 invariants survive the lift to archetype level unchanged |
> | ⭐⭐ **Discovered** | **DOMAIN is the classification archetype — nearly edge-free, it scopes meaning while `DP-n` does the owning** *(orthodox Evans, found empirically)* |
> | ⭐ **Sharpest single answer** | **exactly one archetype executes: CAPABILITY** *(I-11)* — executability elsewhere is a representation property, not a kind |
> | ⛔ **Refuted** | the review's single stack — one supported edge, one refuted, four unevidenced; **level mixing, fourth instance, this time not mine** |
> | ✅ **Challenges accepted** | archetype terminology *(UL decision → MM-1)* · CONTAINER → CANDIDATE *(generates at n=1)* · PURPOSE flagged *(waits on D-5)* |
> | ⛔ **Not adopted here** | the parsimony rule *(already exists at concept level; generalization routed to D-8)* |
| | |

---

*Traceability: Ontology Discovery commission 2026-08-03 (review of Meta-Model Discovery) · **check-before-discovering: the Relationship Ontology (2026-08-02) already answered own/reference/generate at concept level — this pass elevates to archetype level and answers realize/constrain/temporal/persistent/executable/conceptual** · **three review challenges accepted: archetype (UL → MM-1) · CONTAINER demoted to CANDIDATE on my own n=1 rule (EKP is the only generating space) · PURPOSE flagged, waits on D-5** · ⛔ **the single-stack hierarchy REFUTED (1 supported · 1 refuted · 4 unevidenced edges) — replaced by four evidenced partial orders: authority · production · realization · containment** · ⭐⭐ **DOMAIN found to be the classification archetype (scopes meaning, owns nothing — DP-n owns)** · **§5 binds every relation to the engineering decision it enables and an operational scar (DDD's aim, not its technique)** · **parsimony rule found already existing; generalization routed to the docket (D-8 rider)** · **five unknowns recorded** · ⛔ **no new archetype · no redesign · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes. It enters D-8's reconciliation alongside the Meta-Model Discovery — as input, never as a competitor.**
