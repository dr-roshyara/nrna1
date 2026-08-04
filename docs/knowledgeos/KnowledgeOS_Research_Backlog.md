# KnowledgeOS — Research Backlog

| | |
|---|---|
| **Kind** | ⭐ **RESEARCH GOVERNANCE.** ⛔ ***Not architecture · not discovery · no new concept. Research serves decisions; it does not drive architecture.*** |
| **Status** | ⚠️ **CANDIDATE — living artifact; entries change as questions close** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Research-strategy directive, 2026-08-03 |
| ⭐ **The standing rule** | **no research without a named architectural question.** *Broad literature reviews are retired* |
| **Admission rule** *(carried)* | a source enters the matrices only if it **materially changes a confidence** or **identifies a genuinely new gap** |

---

## 1. The five-question review protocol *(adopted — every source, every time)*

| # | Question |
|---|---|
| 1 | **What does this source claim?** |
| 2 | **Which KnowledgeOS concept or RELATIONSHIP does it support, challenge, or leave untouched?** |
| 3 | **Is it discovery, validation, refinement, or implementation guidance?** |
| 4 | **Does it reveal a gap?** |
| 5 | ⭐ **What architectural decision, if any, should change because of it?** — *if the answer is "none," the source does not enter the record* |

*Outputs flow into the two matrices (concept · relationship) under the admission rule.*

> ⭐ **Protocol run recorded (2026-08-03, governed-retrieval external input):** an external reviewer's five proposed research streams **mapped 5-for-5 onto the existing Tier-1 rows** — independent re-derivation of this backlog's shape, itself validation. **One enrichment admitted** *(provenance — see row 1)*; ⛔ everything implementation-shaped refused *(Tier-3 consistent)*. Record: `KnowledgeOS_Governed_Retrieval_Positioning_Validation.md`.

## 2. The backlog — every entry bound to a live open item

⭐ **This is the binding the directive's examples generalized; here each research area serves NAMED open items from this track's own record.**

### Tier 1 — Essential *(active now)*

| Architectural question *(live item)* | Research area | Serves | Stage |
|---|---|---|---|
| ⭐⭐ **How does knowledge become trusted — who may change STANDING, how do corrections propagate, and ⭐ WHO RETIRES KNOWLEDGE?** | ⭐ **KNOWLEDGE GOVERNANCE** *(the directive's #1 — adopted as the month's priority)* | **D-1** *(E-1 disposition)* · **PM-1** *(provenance × standing split)* · **PM-2** *(standing-change acts)* · **SC-2/X-3** *(individual vs community ownership)* · **C-5** *(correction propagation / backward links)* · ⭐ **PM-6 (new): who retires KNOWLEDGE, as distinct from artifacts?** — *checked before recording: **artifact** retirement machinery EXISTS (`superseded`+`superseded_by` guard · `archived` by Knowledge Manager · `historical` standing · ADR-M `Retired`), ⛔ **but retirement has NEVER been observed in several registers** (the R series: "no such state has yet been observed") and no rule says when a RULE stops being true, only when a document does. ⭐ **PM-6's scope enriched (review 2026-08-03): the needed states — Candidate · Approved · Current · Deprecated · Historical · Retired · Superseded · Archived — are lifecycle states of KNOWLEDGE, not documents; this is MM-4's question (does state attach to the artifact or the rule it carries?) arriving from the retirement side.** ⛔ *No machinery designed — the question is sharpened, not answered.* ⭐ **PM-1 ENRICHED (2026-08-03, external input): PROVENANCE as a named first-class concern — industry treats provenance as a governance control plane (identity → version → retrieval event → evidence → answer → audit), which parallels P4 and rests on identity discipline (CAP-001). Checked before recording: provenance already lives dispersed in the corpus (the authority axis · the non-progression kind · evidence grading · traceability blocks). "Provenance as its own domain" = HYPOTHESIS at the docket, ⛔ NOT a backlog stream — this row owns the questions** | **A** |
| **How should the three ontologies be reconciled, and how are `SPECIALIZES`-class relationships modeled?** | Ontology engineering *(minimal commitment · versioning · governance)* | **D-8** · **LG-5** · **CV-4** *(the transformational relationship group)* · MM-1..4 | **A** |
| **Should EVIDENCE-STATUS / OPERATIONAL-STATE become enforceable fields, and what does a governed edge vocabulary look like at scale?** | Enterprise knowledge graphs | **MM-2** · the declared-scope decision *(SC on graph coverage)* · I-4-as-scoped | **A** |
| **What does consumption look like when codification alone fails?** | Knowledge management systems *(codification vs personalization · organizational memory)* | **X-1 → D-1** · the E-1 remedy space *(evidence only — ⛔ no redesign yet)* | **A** |
| **How do contexts EVOLVE (map amendment, drift, dormancy)?** | Strategic DDD, advanced | **D-3** *(frozen-map amendment)* · C-7 · R-4-coverage *(BC↔PD reconciliation)* | **A** |
| **How should AI consume governed knowledge — and where does the capability↔asset link belong?** | AI knowledge platforms / context engineering *(governance side only)* | **SC-6** *(capability↔AST)* · **R-7** *(externally standard, internally unlinked)* · A-9 enforcement asymmetry | **A/C** |

### ⭐ Positioning passes *(admitted 2026-08-03 — serve the SPONSOR's decision, never architecture)*

| Pass | Serves | State |
|---|---|---|
| **External positioning comparison** *(six areas, holdings-first)* | **U-GR-1 / D-5** *(sponsor positioning)* + novelty-claim honesty | ⭐ **first pass DONE** — `KnowledgeOS_External_Positioning_Comparison_Matrix.md`; verdict CANDIDATE: *governance platform that happens to use ontologies* |
| provenance/assertion-ontology follow-ups *(SEPIO · ECO · nanopublications)* | **PM-1** — ⭐ **[FETCHED] survey admitted: assertion/evidence side lacks a PROV-equivalent standard; human AUTHORITY under-modeled (the gap we occupy); when provenance formalization opens, MAP to PROV — never reinvent** | queued |
| ontology-evolution methodologies *(living/reused ontologies)* | **D-8** | queued |
| ODKE-class governed extraction | ⏳ Stream 4 / **R-6** — *only when its gate opens* | gated |

### Tier 2 — Valuable *(opened only when its question goes live)*

| Trigger question | Research area | Would serve |
|---|---|---|
| *When Package 11 opens:* how are reusable engineering platforms structured? | Enterprise architecture · capability engineering · **software product lines** | Stream 3 · the kernel repair *(A-6)* |
| *When Stream 4 opens:* can a governed PKS be GENERATED? | ⭐ **ontology learning · KG population · human-in-the-loop acquisition** | ⭐⭐ **R-6 — the bet itself** | 
| *When runtime work opens:* runtime-independent execution | AI runtime architecture · tool orchestration | Package 11's "service" framing · the second-adapter trigger |
| *When the harvest loop first runs:* how do organizations actually learn from evidence? | Organizational learning · feedback systems | **D-10 / C-1** · the ES-006.4 loop · Stage E |
| *If decision records need strengthening:* | engineering decision records · traceability engineering | **D-2 / C-2** *(minting practice)* · C-5 |
| **Engineering Assessment Commission** | **Status: decision-ready · Model: ⛔ UNKNOWN — deliberately** | **Trigger:** operational evidence that assessment-class decisions recur without evidence. **Questions (defined):** what observations collected? · how synthesized into assessments? · which earn recommendations? · which require the DA? · which stay informational? **Known constraints:** existing instrumentation is STATIC only — dynamic observation does not exist; the commission must not assume it. **Known collisions:** ⛔ PGP-01 — assessment as ONE capability violates one-capability-one-invariant; "CAP-007 CBO Validation" REFUSED *(H-CAT-1 precedent; a metric is not an invariant)*. ⚠️ **REV 2026-08-03: staging TRIMMED per review — decompositions, level-models and terminology previously staged here were PRE-SOLVING the commission; removed. The model emerges IN the commission, from evidence, or not at all** |

### ⛔ Tier 3 — Avoid *(recorded so the refusals stay deliberate)*

**Generic RAG architectures · generic vector databases · prompt-engineering tricks · autonomous-agent frameworks · multi-agent orchestration · AI coding benchmarks.**
⭐ *Rationale carried from the directive — implementation technologies that churn while platform concepts must hold. And consistent with the track's own refusals: **N-10** (no orchestration machinery without evidence of insufficiency) and the rejected five-layer operating model.*

### ⏳ Deferred third validation stream *(recorded, not opened — "not today, later")*

| Stream | Validates | Trigger |
|---|---|---|
| ⭐ **Lifecycle Validation** | **transitions over time** — the P-1..P-10 progression mechanisms as *processes* (do transitions occur as governed? at what rate? with what skips?) — *structure is validated (Stream 2); TIME is not* | when Stream 1's decisions land, or when a transition-conformance question goes live *(B-1's n≈3 · D-9's iteration definition are the first candidates)* |

## 3. Stage alignment *(A–E, recorded)*

**A · Finish Strategic Architecture** — *validate what was discovered* → the Tier-1 rows above · **B · Platform Architecture** — *after governance; Package 11* · **C · Runtime** — SC-6/R-7 first · **D · PKS Generation** — ⭐ *everything in D exists to test **R-6*** · **E · Operational Learning** — *the loop, once it runs once.*

⭐ **The backlog's shape mirrors the docket's gates deliberately: no stage's research opens before its decisions do — except Stage A, which serves the decisions themselves.**

---

> # ⛔⛔ **FREEZE ORDER RECORDED (ARB-chair direction, 2026-08-03): THREE LIVE STREAMS ONLY**
> ### **Knowledge Governance research · Relationship Validation · Operational Evidence.**
>
> ⭐ **TRIO RESTATED (final recommendation, 2026-08-03): Knowledge Governance** *(finish the trust · ownership · attestation · correction · **retirement** model)* **· Operational Evidence** *(validate through ACTUAL PublicDigit engineering work)* **· ⭐ KNOWLEDGE ARCHITECTURE** *(mature the ontology and relationships **without new platform abstractions** — subsumes Relationship Validation).* ⛔ **Full Platform Architecture begins only after these streams produce sufficient evidence.**
> ⛔ **No new platform domains · no new capabilities · no new layers · no new folders · no new terminology — until one of the three streams produces evidence requiring architectural change.**

> ### ⭐ **Standing summary: research continues, governed. Every entry above terminates in a decision that already has a number. The month's priority is KNOWLEDGE GOVERNANCE — because the platform's trustworthiness questions (who owns, who attests, how corrections propagate) are exactly the doors the docket is waiting at.**

*Traceability: research-strategy directive 2026-08-03 · five-question protocol adopted · three tiers recorded with Tier-3 refusals made deliberate (N-10-consistent) · **every Tier-1 entry bound to named live items (D-1 · D-3 · D-8 · PM-1/2 · SC-2/6 · MM-2 · LG-5 · CV-4 · X-1 · R-6/R-7 · C-1/5/7)** · stage alignment A–E mirrors the docket's gating · ⛔ **no architecture · no new concept · no implementation.***

> **⛔ Living artifact. Entries close when their decisions do.**
