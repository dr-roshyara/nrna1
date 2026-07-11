# General Knowledge Architecture — Constitutional Model

**Kind:** constitutional modeling — the invariant structure of knowledge **within the researched domain (software project knowledge)**; the terminal synthesis artifact of RQ-002. NOT design: no folders, templates, software, or implementation. The model is deliberately timeless — it stands to any future implementation as relational algebra stands to a database engine.
**Status:** ACCEPTED AS RESEARCH (ARB) · refined for epistemic honesty (this revision) — architectural adoption pending ARB review of the refined form.
**Epistemic scope (binding on every claim below):** "constitutional" means *constitutional within the researched domain — software project knowledge*. Evidence from medicine, law, aviation, finance, or education was not collected; cross-industry validity is an untested hypothesis, not a claim. This boundary strengthens the model: it states exactly what the evidence covers.
**Evidence base (closed):** the full RQ-002 chain (charter · raw findings · discovery report · synthesis · taxonomy · meta-model) + the Engineering Platform as first-party corroborating evidence.

---

## 1. The Knowledge Space (the foundational concept)

```text
KNOWLEDGE SPACE = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )
```

| Concept | Definition |
|---|---|
| **Knowledge Coordinate** | A position in the space (e.g., Decision × Recorded × Canonical × Project) — existing independently of any artifact. Retrieval matches coordinates, never artifact types: "all canonical project decisions", not "all ADRs". |
| **Knowledge Claim** | **The atomic unit of knowledge** (constitutional — promoted from the Project Knowledge specialization on the ARB's identity finding): content + Coordinate + Provenance + Status + Scent. The four-way smallest-unit convergence (nanopublication · knowledge claim · smallest-true-span · decision-or-definition) defined exactly this object. Every specialization inherits it unchanged. |
| **Knowledge Instance** | An artifact occupying a coordinate — either a single Claim or a **composite bundling claims** (an ADR document, a domain model). Composites are governed claim-by-claim (a composite is "fresh" only claim-by-claim). |
| **Knowledge Flow** | A path through the space — a sequence of nature derivations and representation transitions. |
| **Knowledge Query** | A retrieval of a *region* of the space by coordinate predicates — the consumer-side act where **Purpose** lives; elevated to first-class as **Context Assembly** in the Project Knowledge specialization. |
| **Knowledge Transition** | A deliberate move of one coordinate (tacit→recorded; prose→executable; canonical→superseded). |
| **Knowledge Specialization** | A dense region of the space populated by one domain — weights, never new dimensions. |

Everything else in this constitution is a statement about this space: the invariants constrain every point; the rules govern moves and queries; the lifecycle is the trajectory every represented point follows; specializations are density maps.

## 2. The Constitutional Dimensions (within the researched domain)

```text
Knowledge Instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )
    lifecycle regime  = f(Nature)
    validation method = f(Representation, Nature)
```

- **NATURE** — what the knowledge asserts: Decision · Definition · Constraint/Rule · Description-of-state · Finding/Experience · Measurement/Observation (· Skill, tacit-only).
- **REPRESENTATION** — how it is expressed: Executable · Recorded · Conversational/Social · Tacit/Embodied.
- **GOVERNANCE STATUS** — lifecycle position: Draft/Candidate · Canonical · Superseded/Historical · Deprecated (with reason) · Archived.
- **AUTHORITY SCOPE** *(provisional — thinnest evidence)* — where canonicity holds: Context-local · Project · Platform · Constitutional.

**The Purpose hypothesis (tested, disposed):** Purpose is real but is NOT an instance dimension. Creation-side purpose collapses into Nature (purpose-shift spawns a new instance of a new nature — first-party evidence: measurement baseline vs ratchet constraint); consumption-side purpose is a property of the retrieval relation (one ADR simultaneously serves Decide/Learn/Enforce for different consumers). **Purpose is the consumer-side selector of Knowledge Queries.**

**Confidence table (per the ARB's epistemic-honesty requirement):**

| Claim | Confidence | Basis |
|---|---|---|
| The dimensions are constitutional (researched domain) | High | Triangulated across 7 disciplines, 4 independent executors; smoking-gun decompositions (D10 §1) |
| The invariants are constitutional (researched domain) | High | Each traced to ≥2 unrelated fields in the corpus |
| The governance rules are constitutional (researched domain) | Medium-High | P4 carries a domain-varying population component; P1/P2 strongest |
| The lifecycle is constitutional (researched domain) | High | Convergent across KM, SE, DDD, IA/IQ, AI literatures |
| Authority-Scope as a fourth dimension | Medium (provisional) | Thinner corpus support; first-party evidence only for decision knowledge |
| Any claim beyond software project knowledge | **Untested** | No evidence collected — hypothesis only |

## 3. The Constitutional Invariants (ten)

1. Knowledge never silently disappears.
2. Status transitions are explicit, reasoned, and visible at discovery.
3. Provenance is mandatory on every claim.
4. Contradictions remain visible — coexist at rest, resolve at consumption.
5. Authority is scoped; nothing claims global truth.
6. Freshness is observable at the point of discovery, priced per domain half-life.
7. Supersession is a link, never an edit.
8. No knowledge capability may create a second-population artifact class.
9. Knowledge quality is evidenced, never asserted.
10. **Representation transitions are deliberate, named acts that preserve nature and provenance** — a silent transition (a summary dropping a caveat, an encoding changing the rule) is a defect.
11. **Knowledge exists only to satisfy Knowledge Needs. Documentation is merely one possible representation.** *(The telos invariant — ARB, 2026-07-11; wording strengthened at review to keep knowledge and its representations separate, per the space's own dimensions.)* Every other rule derives from it: minimal sufficient context (P6), never-a-copy, ephemeral assembly, gap evidence from unsatisfied needs, selective capture. A knowledge system's success metric is needs satisfied, not artifacts produced — the direct inversion of the supply-driven KM tradition whose failures the evidence documents.

## 4. The Constitutional Governance Rules

All ten principles (PK-P1..P10) hold constitutionally **within the researched domain**; none demoted to domain-specific. Two are theorems rather than axioms (P5 = f(Nature); P9 follows from Tacit's inability to carry status against invariant 2). P4 (Execution-Proximity) is constitutional *as a gradient* while its cell population varies by specialization — every domain trusts encoded over prose; how much can be encoded differs.

## 5. The Constitutional Lifecycle

Creation → Qualification → Publication → Discovery & Consumption → Evolution (regime fork = f(Nature)) → Supersession → Historical Retention → Staged Retirement — **a cycle, not a pipeline**: validation-is-use closes the loop from Consumption back into Qualification; an unconsumed instance cannot maintain quality. The tacit column follows the parallel practice-bound path (built→strengthened→lost-at-departure) and never reaches Publication — which is *why* it is ungovernable as artifact (theorem, per invariant 2).

## 6. The Flow Algebra

> A knowledge flow is a sequence of two move types — **nature derivations** (a new instance of a new nature referencing its source) and **representation transitions** (the same nature moved to a better representation) — closed into a loop by **consumption feedback** (evidence → finding).

Corroboration: the Engineering Platform's adopted loop (Implementation → Execution → Evidence → Qualification → Retrospective → Evolution) parses exactly as such a path — and was adopted before this model existed. Domain flows are paths through the one space; the paths differ, the algebra does not.

## 7. The Specialization Principle

**A specialization is defined by weights, never by new rules:** (a) which Nature×Representation cells it densely populates · (b) which validation machinery therefore dominates · (c) which authority scope its canon lives at · (d) which lifecycle stages carry its risk. Specializations inherit the space, the invariants, the rules, and the lifecycle unchanged.

**The constitution ends here.** What follows is derived material, not constitution.

---

## Appendix A — Specializations (EXAMPLES, derived from the evidence base — not part of the constitution)

*The constitution defines the space; these are observed dense regions. They live here as worked examples and will move to their own architecture documents when derived.*

| Example domain | Dense cells | Dominant validation | Canon scope | Risk-bearing stages |
|---|---|---|---|---|
| Engineering Knowledge | Constraint×Executable · Measurement×Recorded · Decision×Recorded | Execution + instrument runs + promotion chain | Platform/Constitutional | Qualification · Supersession |
| Project Knowledge | Definition×Conversational+Code · Decision×Recorded · Description×Recorded | Conversation + review-with-code + enforcement-binding | Context/Project | Creation (capture window) · Discovery (the observed assembly gap) |
| Runtime Knowledge *(INSUFFICIENT EVIDENCE flag inherited from D9)* | Observation×Recorded (instrument-produced) | Instrument runs; freshness by construction | Project | Publication · Retirement (volume) |
| Business Knowledge | Constraint×Recorded/Executable · Decision×Recorded | Human authority + encoding where possible (anonymity invariants as fitness tests = first-party proof of Business-Constraint×Executable) | Constitutional | Qualification · Historical Retention |

## Appendix B — Verification: the Engineering Platform decomposition (evidence, not constitution)

Ten artifact classes of the existing Engineering Platform decompose into the space with **no residue and no new dimensions** (ADR-AIP-01 = Decision×Recorded×Canonical×Platform · merge-gate = Constraint×Executable×Canonical×Project · OQ-ENG-001 = Measurement×Recorded×Canonical-with-verdict-history×Platform · pattern cards = Finding×Recorded×Candidate×Platform · EEP = Constraint×Recorded×Canonical-Stable×Constitutional · session logs = Observation×Recorded×Historical×Runtime · sealed Baseline = composite×Recorded×Historical×Platform · developer guides = Description×Recorded×Canonical×Project · …). The platform also independently converged on invariants 1/2/7/9 before this research existed.

**Conclusion (verified within the researched domain):** the Engineering Platform is the first implemented bounded context of this constitutional model. **Consequence discipline:** a model conclusion, not a migration order — nothing is restructured to "match" (R-37, rule parsimony, P1 all forbid it).

## Appendix C — Readiness

Complete enough to derive domain architectures **within the researched domain**. Order: Project Knowledge Architecture (after E-1, the EKP in-flow usage test) → Engineering Knowledge Architecture (descriptive re-derivation only, zero refactoring) → Runtime Knowledge Architecture (after EPIC-002 supplies first-party evidence). Open: E-2 machine-consumer conflict resolution · E-3 discovery token economics · Authority-Scope consolidation · any cross-industry claim.

**STOP — ARB review of this refined form precedes any domain-specific architecture.**
