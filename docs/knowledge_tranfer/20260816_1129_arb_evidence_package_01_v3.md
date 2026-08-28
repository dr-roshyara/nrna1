# ARB Evidence Package 01 — `target_architecture_v3` examined

### With the L3 decomposition the ARB required as its instrument

| Governance | |
|---|---|
| **Kind** | ⭐ **ARB EVIDENCE PACKAGE.** ⛔ *Not an architecture · not a ruling · not a fourth ontology. **Recommendations only — every decision is the ARB's.*** |
| **Status** | **PROPOSED — an ARB INPUT.** *This document has no authority and acquires none by being detailed* |
| **Authority** | **Generated — never authoritative without human review** |
| **Commission** | ARB review, 2026-08-16 — *"decompose the proposed L3 before approving the layer"* + the ten examination questions |
| **Subject** | `20260816_0841_target_architecture_v3.md` |
| **Grades** | `OBSERVED` · `INFERRED` · `HYPOTHESIZED` · `PROPOSED` · `ADOPTED`. ⛔ **Only `OBSERVED` and `ADOPTED` bind** |
| **Constraint accepted** | ⛔ **No new concept is introduced.** *The decomposition below is decided by an existing canonical invariant, not by preference — §1.2* |
| **Date** | 2026-08-16 |

> ### ⛔ The ARB's stated danger, accepted as this document's binding constraint
>
> *"The biggest danger now is that this excellent gap analysis itself becomes a new unofficial architecture authority. It must remain an ARB input, not become the fourth ontology it correctly warns us against."*
>
> **Two guards applied throughout:** every claim carries a grade, and **§1's decomposition invents nothing** — it applies `I-11`, already canonical in the Relationship Ontology.

---

# 1 · The L3 decomposition

## 1.1 The ARB's question

> *Is L3: **A.** a domain model · **B.** a schema/metamodel · **C.** a governed knowledge runtime · **D.** a capability-independent knowledge ontology · **E.** a combination?*

**Answer: none of the five as posed.** L3 as drawn in the gap analysis §8 is **E — and E is the error.** It compounds two declarative sub-layers with one capability and one implementation concern.

## 1.2 The criterion is already canonical — `I-11`

⛔ **Nothing is invented here.** `KnowledgeOS_Relationship_Ontology.md` §4, invariant `I-11`, restated 2026-08-02 and *"validated in all three domains"* under cross-product projection — `OBSERVED`:

> ### **"KNOWLEDGE DOES NOT EXECUTE. Capabilities execute. Knowledge CONSTRAINS AND INFORMS generation."**

**That is the separating criterion, and it decides the layer boundary mechanically:**

| Test | Layer |
|---|---|
| Does it **execute**? | ⇒ **L2 capability** |
| Does it **constrain or inform**, declaratively? | ⇒ **L3 knowledge** |
| Is it the **internal structure** of something that executes? | ⇒ **below L2** — implementation, gated by `P3` |

## 1.3 The decomposition

Applying `I-11` to the four things the ARB identified inside L3:

| ARB's item | Executes? | Verdict | Placement |
|---|---|---|---|
| **1 · Knowledge ontology** — Claim · Evidence · Rule · Decision · Exception · Assessment · Verdict | ⛔ no | declarative — *what kinds of thing exist* | ⭐ **L3a** |
| **2 · Normative semantics** — applicability · scope · authority · temporal validity · normative effect | ⛔ no | declarative — *what a rule means* | ⭐ **L3b** |
| **3 · Analysis machinery** — satisfiability · conflict detection · relationship classification · disposition | ⭐ **YES** | it computes; it is a **capability** | ⛔ **LEAVES L3 → L2** |
| **4 · Tactical DDD** — aggregates · invariants · policies · events | ⭐ **YES** *(the internal structure of something that executes)* | implementation | ⛔ **LEAVES L3 → below L2, `P3`-gated** |

### ⭐ The corrected shape

```
   L2 · PLATFORM CAPABILITY                          ← executes
        C-13..C-17 existing services
        ⭐ + Rule Analysis  (conflict · applicability · classification)
             anchored to a DP-n, per the Capability Catalog's 1:1 binding
             ⛔ its aggregates/policies/events are BELOW this line, P3-gated
                          ▲
                          │ reads — never owns  (candidate I-12)
                          │
   L3b · NORMATIVE SEMANTICS                         ← declarative, schema-bearing
        the Rule / Authority / Exception field model AND its meaning
        subject · applicability · scope · normativeEffect
        temporalValidity · authorityRef · exceptions · evidence
        owner: PD-3 (schema) + ARB (semantics)
                          ▲
                          │ instantiates
                          │
   L3a · KNOWLEDGE ONTOLOGY                          ← declarative, representation-free
        which concepts exist · their relationships · their identity
        G-1..G-17 (+ G-18 Claim, candidate)
        owner: PD-3 + ARB · changes are first-class governed events (G-7)
```

### ⭐ Why L3a and L3b are two layers, not one

They fail the same orthogonality test the meta-model applies to its own dimensions — *orthogonal · necessary · sufficient*:

| | L3a | L3b |
|---|---|---|
| **Answers** | *does `Rule` exist as a concept?* | *what does a `Rule` mean?* |
| **Changes when** | a concept is admitted or retired | a field's semantics change |
| **Lifecycle** | `G-7 Term` — supersession only | schema versioning (`schema_version`) |
| **Breaks what** | the ubiquitous language | every instance and every validator |
| **`OBSERVED` precedent** | `knowledge-types.yaml` (31 types) | `knowledge-schema.yaml` (fields + 16 rules) |

⭐ **The repository already separates them, in two files, with two version fields.** L3a/L3b is not a proposal — it is the existing `docs/knowledge/schema/` split, named.

## 1.4 What this decomposition costs the gap analysis

| §8 claim | Status after decomposition |
|---|---|
| *"L3 · Governed Knowledge Model"* as one layer | ⛔ **WITHDRAWN — it was `E`, the compound error** |
| *"L3 is where `knowledge_tranfer` belongs"* | ⚠️ **PARTLY.** Its ontology → L3a · its rule semantics → L3b · **its analysis machinery → L2** · **its tactical DDD → below L2, `P3`-gated** |
| The L0–L6 diagram | ⚠️ **remains an `INFERRED` reconciliation hypothesis.** ⛔ *Not adopted; §8's own header said so and the ARB has confirmed it* |

> ### ⭐ **The ARB's instinct was correct and the cost is real: one of the gap analysis's seven layers does not survive its own first examination.**

---

# 2 · What subject does v3 architect? *(ARB question 1)*

## 2.1 Measured, not summarised

| v3 section | Subject | Grade |
|---|---|---|
| §1 Vision — *"repeatable, evidence-based, governable architectural discovery"* | ⭐ **T1 / PD-2 Engineering Method** | `OBSERVED` |
| §2 Product boundary — *"the knowledge model of one product's engineering"* | ⭐ **T3 PKS** | `OBSERVED` |
| §4 Principles 1–6 | T3 — PKS lineage (`pks_progress` §7) | `OBSERVED` |
| §4 Principle 7 — one owner per invariant | ⭐ **tier-neutral** — it is `I-11`'s family | `OBSERVED` |
| §5 Vocabulary G-1..G-18 | ⭐ **L3a — tier-neutral** | `OBSERVED` |
| §6–§7 Contexts CBC-1..4, context map | **T3** — `AD-1`'s model | `OBSERVED` |
| §8 Three layers (knowledge/representation/presentation) | **representation plane**, not a tier | `INFERRED` |
| §9 Authority model | ⛔ **PD-1 Engineering Governance territory** | `OBSERVED` |
| §10 Lifecycle | **L3a + L3b** | `INFERRED` |
| §11 C4 views | **T3** | `OBSERVED` |
| §12 Persistence | **representation plane** | `OBSERVED` |
| §13 Scripts → capability | ⛔ **T2 KnowledgeOS Services** | `OBSERVED` |
| §14 Open questions | mixed across all of the above | `OBSERVED` |

## 2.2 The finding

> ### ⭐ **v3 architects T3 — a Product Knowledge Space — while stating a T1 vision, reaching into T2 for its capability mapping, and legislating in PD-1's territory on authority.**

**Four tiers, one document, no tier declared.** `OBSERVED`.

⛔ **And this is the mechanism of the whole confusion, stated precisely:** `AD-1`, from which v3 inherits `CBC-1`/`CBC-2`/`AR-1`/`AR-2`/`XD-1`, is **the PKS's strategic model — a T3 artifact**. v3 treats it as the architecture of the *system*. Every downstream document inherited that framing, and none re-examined it.

---

# 3 · Contradictions with adopted decisions *(ARB question 3)*

⭐ **The result is the opposite of what the gap analysis implied, and it is favourable to v3.**

| v3 position | Adopted KnowledgeOS position | Verdict |
|---|---|---|
| §12 — **repository is the system of record**; graph/search are rebuildable projections | *"one repo, one process; no evidence justifies distributed boundaries"*; JSONL + YAML + git shipped | ⭐ **AGREES** |
| §1 — **rejects** the multi-organisation platform framing on one-lineage evidence | AIP-14 **ADOPTED**: Supporting Subdomain; multi-product is the gated Vision | ⭐ **AGREES** |
| §11.2 — **declines** the ten-container Level 2 | *"no evidence justifies distributed boundaries"*; the Deferred Register's activation-criterion discipline | ⭐ **AGREES** |
| §7 — **leaves `AR-1`/`AR-2` undefined**; naming the elegant-partition error | `AFV-F4` pending; *"a directory exists only when its first artifact arrives"* (`ES-005.2`) | ⭐ **AGREES** |
| §6.3 — Execution admitted as a **plane, not a bounded context** | `PD-6 Runtime` scored **2 of 9**, below the bar — *"a supporting subsystem with an adapter boundary"* | ⭐⭐ **AGREES — independently, on different evidence** |
| §3 — naming freeze, *"five names for one system"* | Five-tier model: KnowledgeOS ≠ PKS ≠ EKP | ⛔ **CONTRADICTS** |
| §6.2 — declines re-partitioning, preserving `CBC-1..4` | `PD-1..PD-7` at a 9-criteria bar | ⚠️ **NOT a contradiction — a different subject** (§2.2) |

> ### ⭐⭐ **Five agreements, one contradiction, one category difference.**
>
> **v3 is the most conservative document in the `knowledge_tranfer` corpus, and its conservatism was right every time it was exercised.** Every position it *declined* to take — PostgreSQL, ten containers, multi-org scope, filling `AR-1` — matches an adopted KnowledgeOS position it had never read. `OBSERVED`.
>
> ⛔ **The one contradiction, §3's naming freeze, is the one place v3 made a positive claim about cross-tier identity. That is where it broke.**

---

# 4 · Genuinely new · restated · premature *(ARB questions 4, 5, 6)*

## 4.1 Genuinely new — `INFERRED`, four items

| # | Claim | Why it survives |
|---|---|---|
| **N-1** | ⭐ **`authored_by` as a provenance dimension** — AI authorship recorded on every artifact | The Knowledge-Constitution rules AI output enters as `authority: generated`; **nothing records *which* AI act produced *which* claim.** No counterpart in `knowledgeos` |
| **N-2** | ⭐ **No lifecycle may contain a creation→authority edge** | The Lifecycle Gap Analysis found authority has **no state machine at all**; v3 states the invariant that machine would have to satisfy. **Complementary, not duplicate** |
| **N-3** | **`G-18 Claim` as a Candidate** | Uses `G-12 Candidate` correctly — probationary, not admitted. ⚠️ `P3` applies: n≥2 |
| **N-4** | **The open-question register discipline** | 38 questions with deciders. ⚠️ *The Deferred Architecture Register does this better and already exists — see §6* |

## 4.2 Restatements of existing architecture — `OBSERVED`, six items

⛔ **Each was independently derived, and each already existed.**

| v3 | Already canonical as |
|---|---|
| §9 *"the mechanism records authority; it never grants it"* | ⭐⭐ **`AP-1`, compiled into `Verdict.php::emittable()`** |
| Principle 2 *"evidence before architecture"* | `ES-006.1` · Freeze v2 · the ARB standing question |
| Principle 3 *"governance before implementation"* | `I-8` / `R-37` |
| Principle 6 *"silence is an architectural decision"* | the OBSERVED/INFERRED/HYPOTHESIZED grading discipline |
| §12 *projections are rebuildable; nothing depends on them* | `DR-1` · `L4-8` · the projection pattern (dev guide §2) |
| Principle 7 *one authoritative owner per invariant* | `I-11` · `P-7` — **the same invariant this package uses in §1.2** |

## 4.3 Premature tactical DDD — `OBSERVED`

⭐ **v3 itself is nearly clean.** Its only tactical content is §5.2's association shape and §10's per-type lifecycles — both declarative, both L3a/L3b.

⛔ **The `P3` violation is in v3's *successors*, not in v3:** `..._0922` (9 aggregates) · `..._1023` (32 invariants) · `..._1029` (allocation) · `..._1034` (8 policies) · `..._1044` (events, outbox, inbox).

> ### ⭐ **v3 held the strategic/tactical line that the documents after it crossed. That is a point in v3's favour and a finding about the sequence, not about v3.**

---

# 5 · The ARB's classification, applied *(ARB question 7, 8)*

The ARB's own classification scheme, applied item by item:

| v3 content | ARB class | Destination |
|---|---|---|
| G-1..G-17 vocabulary | ⭐ **RETAIN** — knowledge/semantic discovery | **L3a** |
| Rule field model (`OQ-8`) | ⭐ **RETAIN + PROMOTE** — observed gap in shipped code | **L3b** |
| `authored_by` (N-1) | ⭐ **RETAIN** | **L3b** provenance |
| No creation→authority edge (N-2) | ⭐ **RETAIN** | **L3b** — input to `OQ-18` |
| L0–L6 layering | ⭐ **RETAIN** — architectural hypothesis | ARB input; ⛔ **not adopted** |
| §6.3 Execution-plane finding | ⭐ **RETAIN** — corroborates `PD-6` at 2/9 | evidence |
| `G-18 Claim` | ⚠️ **STAGE** — candidate invariant | Deferred Register row |
| Conflict analysis machinery | ⚠️ **STAGE** → **L2**, not L3 | Deferred Register row |
| Aggregate boundaries | ⛔ **NOT ADOPTED** | design record only |
| Policies | ⛔ **NOT ADOPTED** | design record only |
| Domain events | ⛔ **NOT ADOPTED** | design record only |
| Outbox / inbox | ⛔ **NOT ADOPTED** — register already **REJECTED FOR NOW** | design record only |
| §3 naming freeze | ⛔ **RETIRE** | superseded by the tier model |
| `OQ-1` | ⛔ **RETIRE** — dissolved | → `OQ-1a`/`OQ-1b` |
| "EKS" | ⛔ **RETIRE** | no referent in canon |

---

# 6 · The Deferred Register already does what v3 §14 attempted *(ARB question 9)*

`OBSERVED` comparison — the same job, two instruments:

| | v3 §14 open-question register | Deferred Architecture Register |
|---|---|---|
| Rows | 38 | 25 |
| Names a **decider** | ✅ | ✅ |
| Names an **activation criterion** | ⛔ **no** | ⭐ **yes — every row** |
| Names the **current invariant** protecting the need | ⛔ **no** | ⭐ **yes** |
| Records **transitions** | ⛔ no | ⭐ **6 logged, one criterion-fired** |
| Update rule | ⛔ none — re-reasoning permitted | ⭐ *"rows change only when a criterion FIRES… never by re-reasoning"* |
| Resolved to date | **1 of 38** | **6 transitions** |

> ### ⭐ **An open question with no activation criterion cannot close except by argument. That is why 37 of 38 remain open, and why the register moved six rows in two days.**
>
> ⛔ **Recommendation: v3 §14's surviving questions become Deferred Register rows with criteria — they do not become a second register.** *A second register is the same failure as a fourth ontology.*

---

# 7 · On the Authority model — the ARB's warning, extended

The ARB wrote:

> *"Do not design an 'Authority aggregate' yet. First establish the semantic model… Otherwise we repeat the exact P3 violation the report itself identifies."*

⭐ **Accepted, and the corpus supplies a sharper reason than `P3`.**

The Lifecycle Gap Analysis withdrew its own question `LG-1` on discovering that:

> ⭐⭐ **`authority` is TWO dimensions in one field — immutable PROVENANCE (`generated`, `derived`) × act-changed STANDING (`authoritative`, `provisional`, `historical`). Provenance CANNOT have a state machine; standing changes by governance ACTS.**

**Consequence for `OQ-18`, `OBSERVED`:**

> An "Authority aggregate" designed today would model **one field that carries two orthogonal dimensions** — and would therefore encode the conflation into code, permanently.

**The correct order is therefore three steps, and only the first is available now:**

```
1  SPLIT the field            provenance ⟂ standing        ← L3b, declarative, available now
2  GOVERN standing            who may move derived→authoritative, and on what act
3  ONLY THEN ask              does this need a runtime aggregate?     ← P3, n≥2
```

⭐ **This strengthens the ARB's position: it is not merely premature to design the aggregate — the thing it would model is not yet one thing.**

---

# 8 · What should become an ARB decision *(ARB question 10)*

⛔ **Recommendations. Every decision is the named authority's.**

| # | Decision | Authority | Evidence | Recommendation |
|---|---|---|---|---|
| **D-A** | **Rule v3's subject** — is it T3, and is `AD-1` a T3 artifact? | **ARB** | §2, `OBSERVED` | ⭐ **ADOPT: v3 is T3.** Everything downstream inherits this |
| **D-B** | **Accept L3a / L3b split; relocate analysis to L2** | **ARB** | §1, decided by `I-11` | ⭐ **ADOPT** — invents nothing; names an existing two-file split |
| **D-C** | **Promote `OQ-8`** — normative rule semantics into L3b | **ARB** | gap analysis §6.1, `OBSERVED` in `recommendation-rules.yaml` | ⭐ **ADOPT** |
| **D-D** | **Split `authority` into provenance ⟂ standing** before any lifecycle or aggregate work | **ARB + PD-3 owner** | §7, `OBSERVED` in the Lifecycle Gap Analysis | ⭐ **ADOPT** — precondition for `OQ-18` |
| **D-E** | **Retire `EKS`; withdraw v3 §3's naming freeze** | `G-7` act | §3, `OBSERVED` | ⭐ **ADOPT** |
| **D-F** | **Dissolve `OQ-1`** → `OQ-1a`/`OQ-1b` | **ARB** | gap analysis §4 | ⭐ **ADOPT** |
| **D-G** | **Migrate surviving OQs into the Deferred Register with criteria; do not create a second register** | **ARB** | §6, `OBSERVED` | ⭐ **ADOPT** |
| **D-H** | **Classify the tactical corpus** per the ARB's own scheme (§5) | **ARB** | §4.3, §5 | ⭐ **ADOPT as stated by the ARB** |

⚠️ **Not recommended for decision yet:** the L0–L6 layering as a whole. **It survives §1 only in amended form, and one of its seven layers did not survive at all.** It should continue as an `INFERRED` reconciliation hypothesis until the next package tests it against `..._0914` and `..._0922`.

---

# 9 · This package's own limits

⛔ **Stated so they cannot be mistaken for confidence.**

| Limit | |
|---|---|
| **Producer ≠ reviewer is violated** | I authored v3 and this examination of it. `R-34`: engineering never accepts its own work. **This package is evidence, not acceptance** |
| **`AD-1` read only through citation** | `AD-1` itself was not read this session — its content reaches me via the four C4 documents and `pks_progress`. §2.2's claim that `AD-1` is a T3 artifact is `INFERRED`, not `OBSERVED` |
| **`knowledgeos` read at 6 of 46 documents in full** | The remainder via titles, the docket, and cross-citation. Absence of a counter-finding in the unread 40 is **not** evidence of its absence |
| **No `knowledgeos` artifact was tested** | No script executed, no lint run. `Verdict.php` and `recommendation-rules.yaml` were read, not run |
| ⭐ **The fourth-ontology risk is not eliminated** | It is only declared. **The structural guard is that §1 decides by `I-11` rather than by preference — if that citation is wrong, the decomposition falls with it** |

---

# 10 · Recommendation to the ARB

**Three, in order.**

**One — rule `D-A` first.** Every other question in this package inherits the subject. Until v3 is ruled T3, its contexts, its principles and its open questions cannot be placed.

**Two — take `D-B` on the citation, not on the argument.** The L3a/L3b split is worth adopting because `I-11` already decides it and `docs/knowledge/schema/` already implements it. ⛔ **If the ARB reads `I-11` differently, the split fails — and that is the correct failure mode for a document that must not become an authority.**

**Three — the next package should be `..._0914` (the rule model), not `..._0922`.** It carries `OQ-8`, which `D-C` promotes, and it is the only `knowledge_tranfer` artifact whose central claim is now supported by `OBSERVED` evidence in running code. **The tactical documents can wait; they are `NOT ADOPTED` under the ARB's own classification and nothing depends on them.**

---

*Prepared as an ARB evidence package, 2026-08-16, on the commission to decompose L3 and examine v3 against ten questions. Every claim graded; only `OBSERVED` and `ADOPTED` treated as binding. §1's decomposition applies `I-11` from `KnowledgeOS_Relationship_Ontology.md` and introduces no concept. §9 records this package's own limits, including a producer≠reviewer violation. ⛔ **No file moved outside `docs/knowledge_tranfer/` · no ADR · no code · no ruling.***

***PROPOSED — an ARB INPUT. It has no authority and acquires none by being detailed.***
