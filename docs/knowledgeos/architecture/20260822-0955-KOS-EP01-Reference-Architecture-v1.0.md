# KnowledgeOS Reference Architecture v1.0

> **Position:** first artifact of the **architectural engineering phase**. Constitution v1.0 (the constraint system) → **this Reference Architecture** → Logical Architecture → Implementation Architecture → Systems. *"The research phase is finished. The architectural engineering phase begins."*
> **Commissioning instrument:** `docs/knowledgeos/reviews/20260822-0955-KOS-EP01-Reference-Architecture-v1.0-commissioning-prompt.md`.
> **Strict instruction (HPA):** *"Design an architecture that satisfies the Constitution. Do not extend the Constitution."*
> **Status:** ⭐ **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE** — architectural, pending HPA review. It adds no law, no concept, no row; it maps the Constitution's content to architectural structure.

---

## 0 · The architecture's nature

The Constitution is not architecture — it is the **constraint system architecture must obey**. This Reference Architecture is the first artifact that IS architecture. Its governing design principle:

> **The architecture is a set of boundaries that make the constitutional forbiddances structurally impossible.**

Each of the eleven laws forbids a specific epistemic collapse. The architecture's job is to place a **boundary** such that the forbidden flow **cannot occur regardless of how any engine, mechanism, or representation is implemented**. A law is then enforced not by exhortation but by structure.

---

## 1 · The layer architecture (from Constitution Ch III)

The architecture respects the Constitution's kernel boundary exactly — four layers below the kernel, three of them in constitutional scope here:

```
┌──────────────────────────────────────────────────────────────────┐
│  CONSTITUTIONAL KERNEL — PRESERVE                                │
│  the eleven articles as eleven architectural boundaries          │
│  + eleven kernel services (the PRESERVE surfaces)                │
├──────────────────────────────────────────────────────────────────┤
│  ENGINE LAYER — GOVERN                                           │
│  reasoning · validation · contradiction · fallacy detection       │
│  · history · intent classification · LLM (governed mechanism)     │
├──────────────────────────────────────────────────────────────────┤
│  REPRESENTATION — ENABLE                                         │
│  evidence records · context tuples · epistemic state model        │
│  · absence taxonomy · typed epistemic graph · verdict vocabulary  │
├──────────────────────────────────────────────────────────────────┤
│  IMPLEMENTATION — ENABLE                                          │
│  storage · interfaces · systems — OUT OF SCOPE HERE (Implementation Architecture) │
└──────────────────────────────────────────────────────────────────┘
```

**Three constitutional invariants carried into the architecture:**
1. **The kernel does not do the reasoning.** Kernel services gate, preserve, and record; engines propose, transform, and evaluate. A kernel service never generates a conclusion.
2. **No engine, mechanism, or representation may violate an article.** Each engine's responsibility is bounded by the article(s) it serves; an engine that would produce a forbidden flow is architecturally unreachable — the boundary sits between it and knowledge.
3. **Implementation is out of scope.** No storage technology, database, API, or class is chosen here. Those belong to the Implementation Architecture, which SHALL conform to these boundaries and SHALL NOT cross them.

---

## 2 · Constitutional laws → architectural boundaries (the mapping)

The centerpiece. Each article maps to one architectural boundary, one kernel service, and the forbidden flow the boundary makes structurally impossible.

| Article | Architectural boundary | Kernel service (PRESERVE) | Forbidden flow — structurally impossible | Serving engines / representations |
|---|---|---|---|---|
| **1 Identity** | **Identity boundary** — identity is only ever assigned, never derived | Identity Service | extension → identity (similarity/coextension match ⇒ identity) · representation ⇒ meaning | typed epistemic graph · context tuple |
| **2 Dimension** | **Dimension boundary** — each dimension evolves independently | Dimension Registry | single score ⇒ knowledge (Knowledge Quality = 0.87) · implicit cross-dimension transition | per-dimension state model |
| **3 Authority** | **Authority boundary** — authority is assigned, never emergent | Authority Service | evidence/assessment/source ⇒ authority (self-authorization) · source-role collapse | evidence record (source-role) |
| **4 Decision** | **Decision boundary** — knowledge informs, never executes | Decision Interlock | knowledge ⇒ action (execution without an authorized decision step) | (none — the interlock is the boundary) |
| **5 Projection** | **Projection boundary** — a projection is never a source | Projection Service | projection ⇒ source (a derived view writes back as the primary record) | derived-view representations |
| **6 Verification** | **Verification Gate** — the admission boundary into knowledge | Verification Gate | generation ⇒ justification · claim-without-path ⇒ knowledge · pseudo-evidence ⇒ evidence | validation engine · reasoning engines · intent classification · LLM (governed) · evidence record |
| **7 Failure-State** | **Failure-preservation boundary** — failed reasoning is a state, never a deletion | State Preservation | failed reasoning ⇒ knowledge · failed reasoning ⇒ silent deletion | fallacy detection · verdict vocabulary (REJECTED) |
| **8 Contradiction** | **Contradiction boundary** — CONFLICTED coexists until governed resolution | Contradiction Registry | CONFLICTED ⇒ premature TRUE/FALSE · challenge ⇒ identity destruction | debate pipeline · verdict vocabulary |
| **9 Unknown** | **Negative-state boundary** — negative states are first-class | Negative-State Model | no-evidence ⇒ PASS/FALSE · absent ⇒ false · uncertainty ⇒ lacuna | absence taxonomy · state model |
| **10 Agency** | **Agency boundary** — no knowledge without an actor context | Agency Record | knowledge ⇒ anonymous (no recorded who/why) | agency fields on every state |
| **11 History** | **History boundary** — revision is forward-only, deletion-free | History Service | revision ⇒ overwrite · supersession ⇒ deletion · freshness ⇒ truth | history/revision engine |

**Reading the table:** the boundary is the architectural line; the kernel service is the PRESERVE surface that holds it; the forbidden flow is the collapse the article names — made impossible because the boundary sits between the engine layer and the knowledge store.

---

## 3 · The kernel services (PRESERVE)

Eleven services, one per article. Each is a **gate or a record**, never a reasoner:

| Service | Article | What it does | What it never does |
|---|---|---|---|
| **Identity Service** | 1 | assigns and preserves knowledge identity across representation, expression, projection, and context changes | never derives identity from similarity or observable match |
| **Dimension Registry** | 2 | tracks each epistemic dimension as an independent, governed axis | never folds dimensions into a scalar; never transitions a value implicitly |
| **Authority Service** | 3 | records authority as an assignment — a reference to a human act; preserves source roles | never lets evidence/assessment/source self-authorize |
| **Decision Interlock** | 4 | terminates knowledge flows at recommendation | never executes an action from knowledge |
| **Projection Service** | 5 | tags derived views as projections; keeps them regenerable and non-authoritative | never lets a projection feed back as source |
| **Verification Gate** | 6 | the **only admission path** into knowledge: claim + authentic evidence + acquisition method + inference rule + reasoning path | never admits a generation, a bare claim, or pseudo-evidence |
| **State Preservation** | 7 | retains REJECTED and failed states as explicit epistemic records | never discards, never promotes failed reasoning to knowledge |
| **Contradiction Registry** | 8 | holds CONFLICTED states until a governed resolution; keeps the conflict record after resolution | never forces TRUE/FALSE prematurely; never deletes the weaker side |
| **Negative-State Model** | 9 | maintains UNKNOWN/ABSENT/FALSE as distinct first-class states | never passes absence as evidence; never maps no-evidence to false |
| **Agency Record** | 10 | attaches the actor context (who observed · reasoned · validated · decided · under which authority) to every state | never admits anonymous knowledge |
| **History Service** | 11 | records every revision; supersession is forward-only, the prior state remains | never overwrites; never equates freshness with truth |

**The Verification Gate is the architectural keystone.** It is the single admission boundary between "candidate" and "knowledge." Every engine, every LLM, every source must pass through it. This is the structural realization of Article 6 — and of Articles 7 (REJECTED outputs are preserved, not dropped), 9 (a claim that cannot be verified is UNKNOWN, not FALSE), 1 (the admitted object carries its identity), 10 (its agency), and 11 (its history).

---

## 4 · The engine responsibilities (GOVERN)

Engines **propose, transform, and evaluate**; they never decide admission (the Verification Gate does) and never hold the kernel's records. Each is drawn from Constitution Ch III and bounded by its article:

| Engine | Article served | Responsibility | Boundary it may not cross |
|---|---|---|---|
| **Validation Engine** | 6 | evaluates a candidate against its justification path; proposes a verdict (VALIDATED / QUESTIONABLE / REJECTED) | may propose — the Verification Gate decides admission |
| **Reasoning Engine(s)** | 6, 7 | governed transformations with preserved provenance: evidence → conclusion only via a preserved inference rule (Vyapti-warranted) | never produces knowledge directly; its output is a candidate with a preserved path |
| **Contradiction / Debate Engine** | 8 | structured opposition (A claims · B counters · C validates · mediator proposes resolution) | never destroys identity; never resolves a CONFLICTED state unilaterally — resolution is governed |
| **Fallacy Detection Engine** | 7 | classifies invalid reasoning (circular dependency · infinite regress · self-reference) | flags REJECTED; the State Preservation surface retains the state |
| **History / Revision Engine** | 11 | executes supersession mechanics — derives the new state, retains the old | never overwrites; never deletes |
| **Intent Classification Engine** | 6 | classifies a reasoning output by its goal (truth-seeking vs persuasion — Vāda/Jalpa/Vitaṇḍā) and feeds the epistemic weight into validation | never by itself confers or denies knowledge status |
| **LLM (governed mechanism)** | 6 | **generates candidates** — likely responses, with captured provenance (prompt · context · model · timestamp) | never writes to the knowledge store; its output enters ONLY through the Verification Gate as a candidate |

**LLM placement (answering the question explicitly):** the LLM sits in the ENGINE layer as a **governed generation mechanism**, at the mouth of the Verification Gate. It is architecturally incapable of the two things the Constitution reserves for the kernel — **persistent epistemic identity** (identity is assigned by the Identity Service, external to the LLM) and **historical belief evolution** (history is held by the History Service, external to the LLM). It may propose; it may never possess. This is the structural form of the §33 two-❌: the LLM's two absences are now *placement*, not weakness.

---

## 5 · The representation models (ENABLE)

Representations express the kernel's content; none of them is the kernel. Drawn from Constitution Ch III:

| Model | Serves | Content |
|---|---|---|
| **Evidence Record** | Articles 3, 6 | evidence + acquisition method + reliability conditions — the source-role separation made explicit (a sensor measurement ≠ an expert statement ≠ an AI inference ≠ a historical document) |
| **Context Tuple** | Article 1 | Entity · Property · Context · Relation · Time · Authority — one expression of the delimiting conditions (the tuple is a representation of Avacchedaka; the *principle* is Article 1) |
| **Epistemic State Model** | Articles 7, 9 | the state vocabulary: UNKNOWN · ABSENT · FALSE · VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · (…) — first-class negative and failure states |
| **Absence Taxonomy** | Article 9 | the four-fold Abhāva distinction — a mechanism for recording *how* something is absent, never collapsing absence into falsity |
| **Verdict Vocabulary** | Articles 6, 7, 8 | VALIDATED / QUESTIONABLE / REJECTED / CONFLICTED — the validation layer's vocabulary (an engine product, not a kernel law) |
| **Typed Epistemic Graph** | Articles 1, 6, 10, 11 | `type Knowledge = {identity, context, evidence, reasoning, authority, agency, history}` — knowledge as typed nodes with preserved relationships (the graph is a representation; the *relational principle* is Article 1) |

---

## 6 · The canonical verification path — how a claim becomes knowledge

The single end-to-end flow that ties the architecture together. Every claim, whatever its origin, follows exactly this path:

```
  PROPOSAL         a claim arises from any source (sensor · expert · LLM · document)
      │            → evidence record created (type · acquisition method · reliability)
      ▼
  PROVENANCE       acquisition method · reasoning path · inference rule · intent · agency captured
      │
      ▼
  VERIFICATION     VALIDATION ENGINE proposes a verdict (VALIDATED / QUESTIONABLE / REJECTED)
  GATE             VERIFICATION GATE decides admission — the only path into knowledge
      │
      ├── VALIDATED ──► ENTRY: identity assigned (Identity Service) · authority recorded
      │                     (Authority Service) · agency recorded (Agency Record) ·
      │                     state set (State Model) · timestamped (History Service)
      │
      ├── QUESTIONABLE ──► HELD: preserved, open to further evidence (never FALSE, never deleted)
      │
      └── REJECTED ──► PRESERVED as REJECTED state (State Preservation — Article 7)
                           ↓
  LIFE               knowledge is challenged (Contradiction Registry — CONFLICTED coexists),
                     revised forward-only (History Service — supersession, never overwrite),
                     projected (Projection Service — derived views, never sources),
                     used advisory-only (Decision Interlock — informs, never executes)
```

No branch of the path crosses a constitutional forbiddance: nothing is admitted without a justification path; nothing failed is deleted; nothing unverifiable is false; nothing is anonymous; nothing is overwritten.

---

## 7 · The six questions, answered

1. **How do constitutional laws map to architectural boundaries?** — One-to-one: each of the eleven articles maps to one architectural boundary and one kernel service (§2). The Constitution's forbiddances become structural lines that the engine layer cannot cross.
2. **What are the kernel services?** — Eleven PRESERVE services (§3): Identity · Dimension Registry · Authority · Decision Interlock · Projection · Verification Gate · State Preservation · Contradiction Registry · Negative-State Model · Agency Record · History. All gates and records; none reasons.
3. **What are engine responsibilities?** — Propose, transform, evaluate — never admit, never hold the records (§4): validation · reasoning · contradiction/debate · fallacy detection · history/revision · intent classification.
4. **What are representation models?** — Evidence record · context tuple · epistemic state model · absence taxonomy · verdict vocabulary · typed epistemic graph (§5) — all ENABLE-layer expressions, none the kernel.
5. **Where do LLMs fit as governed mechanisms?** — In the ENGINE layer, at the mouth of the Verification Gate, as candidate generators (§4). Architecturally incapable of identity and history; its output enters knowledge only through the Gate. *The LLM optimizes generation; the architecture preserves epistemic continuity.*
6. **Where are validation, reasoning, contradiction management, and history engines placed?** — All in the ENGINE layer (GOVERN), between the kernel and the representations (§4): each bounded by its article, each proposing into a kernel gate, none able to reach the knowledge store directly.

---

## 8 · Constraints check — satisfies, does not extend

| Constraint | Status |
|---|---|
| No new law, concept, or row | ✅ the eleven articles map 1:1 to boundaries/services; every engine/mechanism/representation is drawn from Constitution Ch III or the established LLM position |
| The kernel does not do the reasoning | ✅ kernel services are gates and records; engines reason |
| No engine may violate an article | ✅ each engine's output must pass a kernel gate; the forbidden flows are structurally unreachable |
| Implementation out of scope | ✅ no storage/database/API/class choices — those are Implementation Architecture |
| LLM two absences honored | ✅ placement makes them structural, not rhetorical |
| The final rule | ✅ every architectural claim is derived from a cited article or established position |

---

## 9 · What this architecture is NOT

It is **not** the Logical Architecture (no service contracts, no detailed component design), **not** the Implementation Architecture (no technologies), **not** a database or API design. It is the reference level: the mapping of the constitutional constraint system onto architectural boundaries, services, responsibilities, and placement. The next artifacts refine this level downward — **Logical Architecture → Implementation Architecture → Systems** — each conforming to these boundaries and none crossing them.

---

## 10 · Completion gate

**Does the architecture satisfy the Constitution without extending it?** — YES on both. Every constitutional forbiddance is a structural line; every architectural element is a Constitution-sourced element placed. The research is not reopened; the Constitution is not touched. **The architectural engineering phase is correctly begun.**

---

## Traceability

- **Position:** first artifact of the architectural engineering phase; after Constitution v1.0 (ratified), before Logical Architecture.
- **Inputs:** Constitution v1.0 (`20260822-0951-KOS-EP01-Constitution-v1.0.md` — Ch III kernel boundary · the eleven articles · the negative boundary) · step-⑤ kernel decision · the §33 two-❌ LLM comparison · commissioning instrument `20260822-0955-KOS-EP01-Reference-Architecture-v1.0-commissioning-prompt.md`.
- **Constraints honored:** satisfies, never extends the Constitution · no new concepts/rows/philosophy · no implementation choices · the final rule.
- **Status:** ✅ **COMPLETED — Reference Architecture v1.0 produced · PROPOSED · pending HPA review. Next: Logical Architecture → Implementation Architecture → Systems.**
