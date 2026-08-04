# KnowledgeOS — Strategic Architecture Discovery

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC DDD DISCOVERY.** ⛔ ***No folder redesign · no ontology change · no YAML · no script · no ADR · no implementation · no tooling · no code.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Software Architect / Strategic DDD Architect / Enterprise Knowledge Engineer, 2026-08-02 |
| **Suspended** | ⛔ **D-1..D-7 refinement and all ontology debate.** *The falsification findings enter as **inputs**, never as conclusions* |
| ⭐ **Bar applied** | **≥ 3 of `Round47-OP`'s nine criteria**, as commissioned — ⚠️ *stricter than `Round47-OP`'s own "one or more"; the stricter bar is used* |
| **Evidence grades** | ⭐ **OBSERVED** *(read from an artifact)* · **INFERRED** · **ASSUMED** · **HYPOTHESIZED**. ⛔ *Only OBSERVED is authoritative* |

---

> ## ⚠️ **SUPERSEDED IN SEQUENCE 2026-08-02 — mission comes BEFORE domains**
>
> ⛔ **This document discovered domains before the mission was established. `KnowledgeOS_Mission_Discovery.md` is its prerequisite, written afterwards.**
>
> ⭐ **Two refinements it applies to this document:**
> | # | Refinement |
> |---|---|
> | **1** | ⭐ **PD-3 SPLITS.** *The **machinery** (schema validation, graph generation, portal indexing) is a **GENERIC subdomain** — replaceable, buyable. The **Knowledge Constitution** is **SUPPORTING**.* ⛔ **PD-3 remains a CANDIDATE until it survives a second product**; its individual ownership is a **succession risk**, not a boundary argument |
> | **2** | ⭐ **§7's flat ownership list is better read as THREE AUTHORITY SYSTEMS** — **policy** (DA/ARB) · **method & implementation** (sponsor+ARB; an individual) · **domain knowledge** (product team). *Not competing owners of one thing — different kinds of authority over different subjects* |
>
> ⛔⛔ **And the finding that reframes this whole document: there are TWO missions in canon.** *AIP-14 (ADOPTED: serve PublicDigit) and the Charter's hypothesis (PROPOSED, gate shut). **Under AIP-14, extraction is premature and `knowledgeos init` is out of mission.***

## 1. Mission

> ### **What is KnowledgeOS?** — not how it is documented.

⭐ **Answered by following the authority chain, because ownership is the strongest boundary criterion.** *The answer is not the one the previous documents implied.*

## 2. Evidence examined

| Source | What was read |
|---|---|
| `engineering/governance/` | ES-001..006 · **EEP header (`Owner: Decision Authority`)** · STANDARDS_INDEX |
| `engineering/architecture/` | ADR-AIP-01 · **ADR-AIP-02 (AIP-14)** · ADR-AIP-LOG · Reference Architecture · Knowledge Metamodel · Reference Model §8 |
| `docs/architecture/design/` | **`Round39-MC` (MC-01..08)** · `Round39-D6` (ADR-M) · `Round47-00` (SD-1..7) · `Round47-OP` |
| ⭐ **`docs/knowledge/`** | ⭐⭐ **`Knowledge-Constitution.md` frontmatter** · all 7 schema files (parsed) · **`portal/INDEX.md` frontmatter** |
| `scripts/` | `identifier-check.php` · `knowledge-lint.php` · `knowledge-graph.php` · `doc-placement.php` |
| capability work | CAP-001 (+ its evidence record) · **`Platform_Capability_Pattern` (FROZEN)** · Capability Catalog (H-CAT-1) |
| product-side | EAD-1 · BRM-1 · PKS Phase II artifacts · 106 verification reports |
| ⚠️ candidates *(inputs only)* | D-1..D-7 · the falsification report |

## 3. Strategic domains — discovered from ownership

### ⭐⭐ PD-1 · Engineering Governance — **6 criteria**

| Criterion | Evidence | Grade |
|---|---|---|
| Semantic ownership | **`Owner: Decision Authority`** *(EEP header, verbatim)* | ⭐ **OBSERVED** |
| Transactional consistency | ES-001..006 move as a set under `STANDARDS_INDEX`; ES-001.1 parsimony | OBSERVED |
| Lifecycle independence | ratification · freeze R-27/R-37/R-38, product-independent | OBSERVED |
| Invariants | append-only rulings · **R-46** | OBSERVED |
| UL divergence | *ruling · commission · ratification · disposition · authority tier* | OBSERVED |
| Team autonomy | DA/ARB act without product participation | INFERRED |

> ### ✅ **A DOMAIN.**

### ⭐ PD-2 · Engineering Method — **4 criteria**

| Criterion | Evidence | Grade |
|---|---|---|
| Semantic ownership | ⭐ **"Round 39 Methodology Governance (sponsor + ARB)"** — *a separately named body* | ⭐ **OBSERVED** |
| Invariants | **MC-01..08**; *"void to the extent of the conflict"* | OBSERVED |
| Transactional consistency | Constitution ▸ Spec ▸ ADR-M ▸ Validator ▸ Baseline move as one stack | OBSERVED |
| UL divergence | *family execution · F-AUTH/F-PROC/F-THR/F-REV · prediction register · hostile replication* | OBSERVED |
| ⛔ Lifecycle independence | ⛔ **FALSIFIED** — *"Retrospectively documented… authored together on 2026-06-25"* | OBSERVED |

> ### ✅ **A DOMAIN** — on four criteria, ⛔ **not including the one I originally leaned on.**

### ⭐⭐⭐ PD-3 · Engineering Knowledge Platform (EKP) — **6 criteria** — ⭐ **THE DOMAIN D-1..D-7 MISSED**

| Criterion | Evidence | Grade |
|---|---|---|
| ⭐⭐ **Semantic ownership** | ⭐⭐ **`owner: nab.raj.sharma`** — *a **named individual**, not the DA, not the ARB, not the sponsor* | ⭐ **OBSERVED** |
| Invariants | ⭐ `Knowledge-Constitution.md`: `status: frozen`, `authority: authoritative`, *"may only change via an approved ADR"* · **`single_authoritative` is lint-ENFORCED** | ⭐ **OBSERVED** |
| Transactional consistency | schema + lint + graph + portal move together; `schema_version` binds them | OBSERVED |
| Lifecycle independence | its own `version: 1.0` / `schema_version: 1`, independent of ES ratification | OBSERVED |
| UL divergence | *knowledge card · knowledge_id · authority · audience · knowledge_type · portal · hub · recipe* — ⛔ **none in PD-1's language** | ⭐ **OBSERVED** |
| Deployment autonomy | 18 lint rules execute over a **declared scope**, independent of any product build | OBSERVED |

> ## ⭐⭐⭐ **A DOMAIN — and the most strongly evidenced one in the repository.**
> ⛔ **My Engineering Knowledge Domain Model had no place for it.** *It treated the EKP as an **ontology layer (L-A)**, never as a **governed domain with a constitution and an owner**. **That is a category error, and this is its correction.***

### ⚠️ PD-4 · Capability — **3 criteria, at the bar, blocked by a standing refusal**

| Criterion | Evidence |
|---|---|
| Invariants | DP-1..DP-6, one per capability — OBSERVED |
| UL divergence | *verdict · port · fail-closed · prevents-never-repairs* — OBSERVED |
| Semantic ownership | `Platform_Capability_Pattern` is **FROZEN**, governed by **PGP-01..05** — OBSERVED |
| ⛔ Transactional consistency | ⛔ capabilities change independently of one another |

> ### ⚠️ **MEETS the numeric bar (3) and is BLOCKED by `H-CAT-1`** — *"a parent Validation Capability abstraction… not yet evidenced"*, never overturned.
> ⛔ **Reporting both is the honest answer. Only the ARB may resolve it.**

### ⚠️ PD-5 · Evidence — **splits; the halves score differently**

| Half | Criteria met | Verdict |
|---|---|---|
| **Protocol** *(ES-006.1/.4 · Observation Protocol)* | ownership · invariants · UL = **3** | ⚠️ **at the bar, ⛔ 0 traversals — unexercised** |
| **Records** *(106 reports · session logs · CAP-001 §9)* | ownership *("the producing track")* · invariants *(append-only, never edited)* · lifecycle = **3** | ✅ **a domain — product-side** |

### ⛔ PD-6 · Runtime — **2 criteria. BELOW the bar.**

| Criterion | Result |
|---|---|
| Integration characteristics | ✅ an adapter boundary exists — OBSERVED |
| Deployment autonomy | ⚠️ *claimed* — n=1 runtime |
| ⛔ **UL divergence** | ⛔⛔ **FALSIFIED** — no Capability Mapping artifact; `deny/ask/allow` ≈ the abstract verbs |
| ⛔ Semantic ownership | ⛔ **no declared owner found for `.claude/`** |

> ### ⛔ **NOT A DOMAIN at the commissioned bar.** *A supporting subsystem with an adapter boundary. Consistent with the falsification (WEAK).*

### ⛔ PD-7 · PKS — **not a platform domain**

*n=1 · projection falsified · owned product-side.* ⛔ **It belongs to the product, not to KnowledgeOS.**

## 4. Core / Supporting / Generic

⚠️ **The classification is INFERRED. One constraint is OBSERVED and overrides the frame:**

> ⛔ **AIP-14 / DA 2026-07-27: *"The Election System is the Core Domain; this platform is a **Supporting Subdomain**."*** **Within the programme as it stands, NONE of PD-1..PD-6 is a Core Domain.**
> ⭐ *The table below therefore answers the conditional the Reference Model §8 already records: **if the gate opened**, what would be core?*

| Domain | Classification *(conditional)* | Why |
|---|---|---|
| ⭐⭐ **PD-2 Engineering Method** | ⭐ **CORE** | **the differentiator.** *Every serious organisation has governance; a **methodology constitution with a validator, a prediction register and hostile-replication charters** is what nothing else in the comparison set has* — INFERRED |
| **PD-1 Engineering Governance** | **SUPPORTING** | ⚠️ *necessary, and not distinctive — authority models are table stakes* — INFERRED |
| ⭐ **PD-3 EKP** | **SUPPORTING** | *it makes knowledge retrievable and enforceable; it is infrastructure for the method, not the method* — INFERRED |
| **PD-4 Capability** | **SUPPORTING** | serves the method; blocked by H-CAT-1 |
| **PD-5a Evidence Protocol** | **SUPPORTING** | the loop that would let the core improve |
| **PD-6 Runtime** | ⭐ **GENERIC** | ⭐ **replaceable by declaration** — *"only the adapter would be rewritten"* — OBSERVED |

## 5. Context map

⭐ **Every edge graded. Verbs as commissioned. ⛔ No Evans/Vernon pattern assigned** — the ARB ruling stands.

```
  ┌───────────────────────────────┐        ┌────────────────────────────────┐
  │ PD-1 ENGINEERING GOVERNANCE   │        │ PD-2 ENGINEERING METHOD        │
  │ owner: Decision Authority     │◀──────▶│ owner: sponsor + ARB           │
  │ ES-001..006 · EEP · rulings   │  ARB   │ MC-01..08 · ADR-M · MB-39.1    │
  └───────────────┬───────────────┘ shared └────────────────┬───────────────┘
                  │ governs (OBSERVED)      mutual scope     │ produces (OBSERVED)
                  │                         EXCLUSION        │
                  ▼                         (OBSERVED)       ▼
  ┌───────────────────────────────┐                ┌──────────────────────────┐
  │ PD-4 CAPABILITY               │                │ discovery instruments    │
  │ pattern FROZEN · H-CAT-1 ⛔   │                │ workbooks · gates · cats │
  └───────────────┬───────────────┘                └──────────────────────────┘
                  │ realized as (OBSERVED)
                  ▼
  ┌───────────────────────────────────────────────────────────────────────┐
  │ ⭐⭐ PD-3 ENGINEERING KNOWLEDGE PLATFORM   owner: an INDIVIDUAL        │
  │ frozen constitution · 7 schemas · 18 lint rules · graph · portal      │
  │ ⛔ DECLARED SCOPE: docs/knowledge/** only                             │
  └───────────────┬───────────────────────────────────┬───────────────────┘
                  │ produces (OBSERVED)               │ classifies (OBSERVED)
                  ▼                                   ▼
        ┌──────────────────────┐             ┌────────────────────┐
        │ PROJECTIONS          │             │ governed documents │
        │ authority: derived ⭐ │             │ (132 of ~1,000+)   │
        └──────────────────────┘             └────────────────────┘

  ┌───────────────────────────────┐        ┌────────────────────────────────┐
  │ PD-6 RUNTIME (generic)        │        │ PD-5b PRODUCT EVIDENCE         │
  │ ⛔ no declared owner          │        │ owner: the producing track     │
  └───────────────────────────────┘        └────────────────┬───────────────┘
                                                            │ observes ⛔ 0 TRAVERSALS
                                                            ▼  (HYPOTHESIZED)
                                              PD-5a Evidence Protocol → PD-1/PD-2
```

| Edge | Grade |
|---|---|
| PD-1 **governs** PD-4 | ⭐ OBSERVED — the Capability Pattern is FROZEN under an ADR regime |
| PD-1 ↔ PD-2 **share the ARB** | ⭐ **OBSERVED** — *"sponsor + ARB"* |
| PD-1 ↔ PD-2 **mutual scope exclusion** | ⭐ **OBSERVED** — *"Governance discoveries MUST NOT appear here. No DDD concepts here."* |
| PD-2 **produces** discovery instruments | OBSERVED — workbooks, saturation gate, catalogs |
| PD-3 **produces** projections | ⭐ **OBSERVED** — `portal/INDEX.md` carries `authority: derived` |
| PD-3 **classifies** documents | OBSERVED — `knowledge_type` required, lint-enforced |
| PD-4 **realized as** CAP-001 | OBSERVED |
| PD-5b → PD-5a → PD-1/PD-2 | ⛔ **HYPOTHESIZED — 0 traversals** |
| ⚠️ **PD-1 or PD-2 governs PD-3?** | ⛔⛔ **NEITHER IS OBSERVED — see §7** |

## 6. Platform boundaries

| Inside KnowledgeOS | Outside |
|---|---|
| PD-1 governance instruments · PD-2 method + instruments · PD-3 schema/lint/graph/portal machinery · PD-4 pattern + CAP-001's Domain/Application/Shared · PD-5a protocol · PD-6 adapter contract | ⛔ every register's **contents** · PD-5b **records** · each PKS **instance** · `app/` · all product domain knowledge · `governed-registers.yaml` · `.claude/settings.json` |

⛔ **Two OBSERVED boundary breaches** — §8, V-1 and V-2.

## 7. Ownership — ⭐ **the discovery**

| Domain | Owner | Grade |
|---|---|---|
| PD-1 Governance | **Decision Authority** | ⭐ OBSERVED (`Owner:` header) |
| PD-2 Method | **sponsor + ARB** *("Round 39 Methodology Governance")* | ⭐ OBSERVED |
| ⭐⭐ **PD-3 EKP** | ⭐⭐ **an INDIVIDUAL — `owner: nab.raj.sharma`** | ⭐ **OBSERVED** |
| PD-5b Records | **the producing track** | OBSERVED |
| ⛔ **PD-6 Runtime** | ⛔ **none found** | — |
| ⛔ **The Platform Ontology (L-B)** | ⛔ **none — it does not exist** | — |
| ⛔ **PKS generation** | ⛔ **none found** | — |
| ⛔⛔ **KnowledgeOS itself** | ⛔⛔ **NO SINGLE OWNER EXISTS** | ⭐ **OBSERVED by absence** |

> # ⭐⭐⭐ **THE ANSWER TO "WHAT IS KNOWLEDGEOS?"**
>
> ### **It is not yet ONE thing. It is THREE separately-governed domains that overlap, plus a generic runtime — with NO single owner and NO reconciling authority.**
>
> | Governed by | Domain |
> |---|---|
> | **Decision Authority** | Engineering Governance |
> | **sponsor + ARB** | Engineering Method |
> | ⭐ **an individual** | Engineering Knowledge Platform |
>
> ⭐⭐ **AND THIS EXPLAINS THE PROGRAMME'S OWN SYMPTOMS:** *ten rediscoveries · two parallel ontologies · three bounded-context instruments · two decision templates · two colliding verdict vocabularies · an unfired feedback loop.* **These are not discipline failures. They are what three unreconciled authorities over one subject produce.**

## 8. Invariants — ⛔ **OBSERVED only, and two are already breached**

| # | Invariant | Grade |
|---|---|---|
| **I-1** | **Rulings are append-only; entries immutable once recorded** | ⭐ OBSERVED |
| **I-2** | **R-46 — plan approval ≠ execution authorization** | ⭐ OBSERVED |
| ⭐ **I-3** | **One authoritative document per topic + context** | ⭐⭐ **OBSERVED AND MACHINE-ENFORCED** (`single_authoritative`) |
| **I-4** | **The runtime mount never moves and is never "the architecture"** | ⭐ OBSERVED (ES-005.1) |
| ⭐ **I-5** | **Governance precedes automation; automation never defines governance** | ⭐ OBSERVED (Reference Architecture §1) |
| ⭐ **I-6** | **A constitutional principle is void-overriding** — *any artifact conflicting is void to the extent of the conflict* | ⭐⭐ **OBSERVED TWICE — `Round39-MC` and `Round47-00` independently** |
| **I-7** | **Status is independent of authority** | ⭐ OBSERVED (`statuses.yaml` / `authorities.yaml`) |

### ⛔⛔ Two proposed invariants are **STATED AND VIOLATED**

| # | Proposed | ⛔ Breach — OBSERVED |
|---|---|---|
| ⛔ **V-1** | *"KnowledgeOS never contains business knowledge"* | ⛔ **ES-005.1 names PublicDigit and hard-codes `.claude/`** — a product binding **inside the governance layer** |
| ⛔ **V-2** | *"PKS never contains reusable engineering capability"* | ⛔ **EAD-1: the methodology trio has ZERO election terms and sits in `docs/` — a PRODUCT path.** *Domain-free method inside a product boundary* |

> ### ⭐ **These are not candidate invariants. They are invariants the architecture asserts and the repository breaks.** ⛔ *Recording the breach is the finding; repairing it is not this commission's act.*

## 9. Lifecycle — what produces what

⛔ **The commission's proposed chain is mostly unobserved. Graded honestly:**

| Link | Grade |
|---|---|
| **Platform Domains → Platform Ontology** | ⛔ **HYPOTHESIZED** — L-B does not exist |
| **Platform Ontology → Documentation Ontology** | ⛔ **HYPOTHESIZED** — L-A exists and is **governed by its own constitution, not by L-B** |
| ⭐ **Documentation Ontology → Knowledge Graph** | ⭐ **OBSERVED** — `knowledge-graph.php` |
| ⭐ **Documentation Ontology → Indexes** | ⭐ **OBSERVED** — `authority: derived` |
| **Knowledge Graph → PKS** | ⛔ **FALSIFIED** — PKS artifacts are authored/accepted, not derived |
| **PKS → Runtime Context** | ⛔ HYPOTHESIZED — no prompt-context artifact, though `audience: ai` exists |
| **Runtime → Software** | ⭐ OBSERVED — 1,532 files |
| **Software → Evidence** | ⭐ OBSERVED — 106 reports |
| **Evidence → Platform** | ⛔⛔ **EMPTY — 0 traversals; blocker = the retrospective has not run** |

> ### ⭐ **Two of nine links are observed inside the platform. The chain is a destination, not a pipeline.**

## 10. Open questions

| # | Question | Authority |
|---|---|---|
| ⭐⭐ **SA-1** | **WHO OWNS KNOWLEDGEOS?** *Three authorities govern parts of it; none owns the whole* | **sponsor + DA + ARB jointly** |
| ⭐ **SA-2** | **Is PD-3's individual ownership intentional or historical?** *A domain owned by a person and not by a body is a succession risk* | **DA** |
| **SA-3** | Does PD-1 or PD-2 govern PD-3 — or neither? ⛔ **no relation is observed** | **ARB** |
| **U-1** *(carried)* | Was `Round39-MC` adopted by sponsor authority **because** PD-2 is independent, or **despite** it not being? | **sponsor + ARB** |
| **SA-4** | Should V-1 and V-2 be adopted as invariants **given they are currently breached**? | **ARB** |
| **SA-5** | Does PD-4 clear H-CAT-1 on the FROZEN pattern? | **ARB only** |
| **SA-6** | Should PD-3's declared scope extend beyond `docs/knowledge/**`? | **PD-3's owner + ARB** |

## 11. Candidate architecture

```
                    ⛔ NO SINGLE OWNER  (SA-1)
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
  Decision Authority    sponsor + ARB        an individual
        │                     │                     │
        ▼                     ▼                     ▼
   PD-1 GOVERNANCE  ◀shared ARB▶  PD-2 METHOD   PD-3 EKP
   supporting          ⭐ CORE (conditional)    supporting
   6 criteria           4 criteria              6 criteria
        │                     │                     │
        │ governs             │ produces            │ produces
        ▼                     ▼                     ▼
   PD-4 CAPABILITY     instruments          PROJECTIONS (derived)
   ⚠️ H-CAT-1 blocks                        graph · portal · indexes
        │
        │ realized as
        ▼
   CAP-001 ──────── executes over ────────▶ registers ⛔ OUTSIDE PD-3's scope
                                                     │
   PD-6 RUNTIME (generic, ⛔ no owner) ──────────────┤
                                                     ▼
                                            PRODUCT (PublicDigit)
                                                     │ produces
                                                     ▼
                                            PD-5b RECORDS
                                                     │ ⛔ 0 traversals
                                                     ▼
                                            PD-5a PROTOCOL
```

| | |
|---|---|
| ⭐ **Domains at the commissioned bar** | **PD-1 (6) · PD-2 (4) · PD-3 (6) · PD-5b (3)** · ⚠️ PD-4 (3, blocked) · ⚠️ PD-5a (3, unexercised) |
| ⛔ **Below the bar** | **PD-6 Runtime (2)** — a supporting subsystem, not a domain |
| ⛔ **Not platform domains** | **PD-7 PKS** |
| ⭐ **Change from D-1..D-7** | ⭐⭐ **PD-3 ADDED** *(the EKP — missed entirely)* · **D-3 Runtime DEMOTED below the bar** · **D-4 stays rejected** · **D-7 moved product-side** |

---

## ⭐ Closing — the success criterion

**The commission asked what KnowledgeOS *is*. The evidence gives an uncomfortable answer, and it is the most useful one produced in this track.**

| | |
|---|---|
| ⭐⭐ **What KnowledgeOS is** | **THREE separately-governed, overlapping domains plus a generic runtime — with no single owner** |
| ⭐⭐ **The domain everything missed** | **PD-3, the Engineering Knowledge Platform** — a frozen constitution, seven schemas, 18 enforced rules, a generated graph, a portal, and ⭐ **a named individual as owner.** *My own model classified it as a "layer" and never asked who owned it* |
| ⛔ **What is not a domain** | **Runtime** — 2 criteria; the UL divergence that justified it was falsified |
| ⛔ **Two invariants breached** | **ES-005.1 names a product; domain-free method sits in a product path** |
| ⭐ **Why the symptoms make sense now** | ⭐⭐ **ten rediscoveries, two ontologies, three BC instruments, two templates, two verdict vocabularies, an unfired loop — all downstream of three unreconciled authorities over one subject** |

> ### **KnowledgeOS is not under-architected. It is under-OWNED.**
> ### ⭐ **And SA-1 — *who owns KnowledgeOS?* — is now the question every other question waits on.**

---

*Traceability: Strategic Architecture Discovery commission 2026-08-02 · ⛔ **D-1..D-7 refinement and ontology debate suspended; falsification findings used as inputs only** · **≥3-of-nine bar applied as commissioned (stricter than `Round47-OP`'s own "one or more")** · every claim graded **OBSERVED / INFERRED / HYPOTHESIZED** · ⭐⭐ **PD-3 Engineering Knowledge Platform DISCOVERED — 6 criteria, `owner: nab.raj.sharma` OBSERVED in `Knowledge-Constitution.md` frontmatter — a domain absent from D-1..D-7** · **Runtime DEMOTED to 2 criteria, below the bar** · **7 invariants OBSERVED; 2 proposed invariants recorded as STATED-AND-BREACHED** · **2 of 9 lifecycle links observed; the Knowledge-Graph→PKS link falsified** · ⭐ **`portal/INDEX.md` carries `authority: derived` — projections are OBSERVED in production, not theoretical** · ⛔ **no folder · no ontology change · no YAML · no script · no ADR · no implementation · no tooling · no code.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
