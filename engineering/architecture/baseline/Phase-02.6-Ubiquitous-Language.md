# AI Engineering Platform — Phase 2.6: Ubiquitous Language (Vocabulary Freeze Candidate)

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced; never authoritative without human review) |
| **Status** | **Freeze APPROVED by ADR-AIP-01 (2026-07-08), conditional on one final terminology review (open item OI-1**; protocol per R-11: duplicates · synonyms · single meanings · Laravel/domain separation · provider-term exclusion · PublicDigit-UL alignment). On completion of OI-1: FROZEN — Baseline v1.0; thereafter supersession only (AIP-11) |
| **Owner** | Architecture Review Board |
| **Promotion** | Generated → ARB Review → ADR Approval → Authoritative → **Frozen** |
| **Date** | 2026-07-07 |
| **Depends on** | `Phase-02-Domain-Model.md` · `Phase-02.5-Certification-Plan.md` |
| **Purpose** | ARB ruling R-6: stabilize the language before building the implementation. One definition per term. **Never synonyms.** Phase 3 artifacts (CLAUDE.md, agents, hooks, commands, rules, settings) must use these terms verbatim and no others. |

---

## 1. Canonical terms

One definition per term. Where the project already defines a term (EKP, Implementation Process, PGP), the platform **inherits that definition unchanged** — marked ⬆ (Conformist: the platform never redefines upstream language).

### 1.1 Authority & governance

| Term | Definition |
|---|---|
| **Authority** ⬆ | The trust level of an artifact, independent of its status: `authoritative`, `derived`, `generated`, `historical`, `provisional`. |
| **Authoritative** ⬆ | The single source of truth for a topic in a context. Exactly one per topic. Reachable only through the Promotion Chain. |
| **Generated** ⬆ | Produced by AI or tooling. Never authoritative without human review. The entry authority of every platform output. |
| **Provisional** ⬆ | Human-authored but not yet reviewed. |
| **Derived** ⬆ | Mechanically produced from authoritative sources (e.g. an index, a graph). |
| **Historical** ⬆ | Superseded or archival; immutable; preserved, never deleted. |
| **Status** ⬆ | Lifecycle position of an artifact: `idea → research → draft → discovery → reviewed → approved → baseline → frozen` (side branch: `superseded → archived`). Orthogonal to Authority. |
| **Frozen** ⬆ | Stable; changes only via a new ADR + review. (Contrast **Immutable**: changes only via a new version.) |
| **Superseded** ⬆ | Replaced by a newer artifact; the old one becomes Historical. The only way platform artifacts "change" after approval (AIP-11). |
| **Promotion / Promotion Chain** | The only path by which a platform artifact gains authority: `Generated → ARB Review → Capability Certification → ADR Approval → Authoritative → Frozen`. Each stage requires its own Human Decision Event. Stages are never skipped. |
| **Human Decision Event** | An authoritative fact emitted only by Human Authority (ARB, Chief Architect, Sponsor): `ADRApproved`, `IDDApproved`, `CapabilityCertified`, `CertificationRefused`, `ArtifactFrozen`, `MergeApproved`, `ConstitutionalIncidentResolved`, `ArbReviewCompleted`. No platform state transition may substitute for one. |
| **Authority Boundary** | The platform's constitutional policy: no promotion and no gate passage without a referenced Human Decision Event. |
| **Human Authority** | The external domain comprising ARB, Chief Architect, and Sponsor — the only source of Human Decision Events. |
| **ARB** ⬆ | Architecture Review Board — final authority; certifies adversarially. |
| **Escalation** ⬆ | Routing a breach or ambiguity up to Human Authority. The platform's only lawful response to a constitutional guard trip. |
| **Architecture Principle (AIP-nn)** | A named, enduring platform rule (AIP-01..12) with a designated guard host. Principles are enduring; decisions (ADRs) are situational and reference principles. |

### 1.2 Evidence & verification

| Term | Definition |
|---|---|
| **Evidence** | The raw output of an executable check, preserved immutably and referenced by everything that claims anything. |
| **Evidence Record** | The stored artifact holding evidence; append-only. |
| **Verdict** | The outcome of one gate execution (`passed`/`failed`), constructible only with an Evidence reference (Honesty Invariant). Immutable; re-runs create new Verdicts. |
| **Gate** ⬆ | A mandatory checkpoint in the workflow. Typed by **Gate Class** ⬆: Capability, Architecture, or Engineering-Improvement. |
| **Fitness Function (FF-nn)** ⬆ | An executable architectural check verifying a Property. Active only after Falsifiability is proven. |
| **Property** ⬆ | An architectural invariant stated so that renaming cannot break its test but violating it must ("properties, never class names"). |
| **Falsifiability** ⬆ | Proof that a fitness function detects a synthetic violation and ignores conformant code (a recorded RED run). |
| **Verification** ⬆ | Objective, executable, property-based checking. Produces Verdicts and the Verification Matrix. **Never** a judgment. |
| **Certification** ⬆ | The human judgment act (ARB), performed adversarially, after Verification passes. **Never** performed by the platform. |
| **Verification Matrix** ⬆ | The table of Properties × enforcing checks × Verdicts for a capability. |
| **Honesty Invariant** | AIP-01/04 combined operationally: every reported score, verdict, or progress figure derives from an executable check. Asserted numbers cannot exist. |
| **Assertion Integrity** | AIP-10: no artifact may assert an event that has not occurred. |
| **Traceability Chain** ⬆ | The resolvable path ticket → IDD → commit → test → DoD (and, for platform decisions, ADR → Capability → Rule → Implementation — AIP-12). |
| **Constitutional Guard** ⬆ | The executable protection of a constitutional invariant (CI-1..5/Q7), hosted by the constitutional suite. The platform observes; it never hosts, modifies, or retries one. |

### 1.3 Review

| Term | Definition |
|---|---|
| **Review** | An adversarial examination of an artifact producing Findings and a Recommended Verdict. The platform produces the analysis; humans produce the decision. |
| **Finding (F-n)** ⬆ | A defect identified during review; invalid without an Evidence reference. Remediated RED-first or accepted as Residual Risk. |
| **Residual Risk (R-n)** ⬆ | A reviewed, accepted, tracked non-blocking risk. |
| **Attempt-to-Reject** ⬆ | The review method: actively try to reject the artifact; approval only where objective evidence defeats every rejection attempt. A review without recorded rejection attempts is invalid. |
| **Recommended Verdict** | The review's output judgment, always `authority: generated`. Never the word "certified". |
| **Producer ≠ Reviewer** | AIP-05 structural rule: the session that produced an artifact may not review it. |

### 1.4 Knowledge & session

| Term | Definition |
|---|---|
| **Knowledge Item** ⬆ | A governed document identified by `knowledge_id`, carrying a Knowledge Card. |
| **Knowledge Card** ⬆ | The mandatory YAML frontmatter: identity, type, bounded context, status, authority, owner, typed relationships, code refs. |
| **Knowledge Package** ⬆ | A curated, lint-validated **documentation manifest** bundling `knowledge_id`s for a task. Documentation, not a domain object (ARB ruling R-2). |
| **Bootstrap** | The assembly of governed state (stable facts, current state, active plan, today's log, task-relevant package) delivered at session start. Assembled from governed sources only; never stored as truth. |
| **Session** | One continuous working engagement of the AI actor, recorded append-only, archived immutably. |
| **Context Snapshot** | The platform's record of "where we are": active ticket reference, exactly one Single Next Action, and Stable Facts. |
| **Stable Fact** | A durable statement admissible in a snapshot only with repo provenance. On conflict, the repo wins ("evidence beats memory" ⬆). |
| **Single Next Action** | The exactly-one declared next step. Two next actions is an invariant violation, not a convenience. |
| **Staleness** | The observable condition of a snapshot older than the repo state it references. Surfaced, never silently repaired. |

### 1.5 Implementation

| Term | Definition |
|---|---|
| **Ticket (PB-xxx)** ⬆ | A capability unit of work running the full 15-step workflow. |
| **IDD** ⬆ | Implementation Design Document — the 17-section design produced before code, approved by the Chief Architect at the Architecture Review gate. |
| **Implementation Plan** | The platform's per-ticket execution model: WBS, step checklist, DoD checklist, micro-slices. |
| **WBS** ⬆ | Work Breakdown Structure — the enumerated deliverables; the only source of progress. |
| **Derived Progress** ⬆ | completed WBS ÷ total WBS, always scoped. The only legal progress figure; structurally unsettable by hand. |
| **Lifecycle State** ⬆ | Governance position of a ticket: `Designed → Approved → In Development → Implemented → Verified → Released` (+ Blocked flag). Orthogonal to progress. |
| **Micro-slice (PB-xxx-Cn)** ⬆ | A commit-sized increment that compiles, passes, is RED-first, and has a rollback. |
| **RED / GREEN / REFACTOR** ⬆ | The TDD cycle. RED evidence must exist before GREEN. Literal, not aspirational. |
| **DoD** ⬆ | The 14-box Definition of Done. All boxes or not Done — no partial credit. |
| **Gap Sequence** ⬆ | For any discovered gap: `Finding → Architecture Decision → RED → GREEN → Certification`. Never Finding → Implementation. |
| **Developer Guide** ⬆ | The per-step guide under `developer_guide/` — part of DoD, grounded in committed code. |

### 1.6 Capability & decision

| Term | Definition |
|---|---|
| **Capability (CAP-nn)** | A platform service with exactly one responsibility and exactly one owning bounded context, registered in the capability registry with declared dependencies, extension points, and governance tier. |
| **Platform Capability** ⬆ | (Project sense) A reusable, certified architectural capability consumed by multiple bounded contexts, owned centrally, governed by the 12-section pattern. The AI platform aspires to be one. |
| **Capability Lifecycle** | `Proposed → Experimental → Certified → Frozen → Deprecated → Archived`. Certified only via a Human Decision Event; Certified may not depend on Experimental. |
| **Decision Draft** | A platform-authored ADR candidate: exactly one decision, ≥2 options with consequences, an ER-05 convergence note. APPROVED is unreachable inside the platform. |
| **ADR** ⬆ | Architecture Decision Record — one decision, `PROPOSED → REVIEW → APPROVED`, with conformance test. |
| **Ownership Disposition** ⬆ | PGP-02 vocabulary, the only legal ownership words: **Owns · Coordinates · Preserves · Observes · Does-NOT-own.** Exactly one owner per responsibility. |
| **Convergence** ⬆ | ER-05: every iteration reduces or maintains long-term complexity; increases require an ADR. |
| **Extension Point** | The declared, governed way a capability may be extended (Open/Closed). Anything not declared is closed. |
| **Governance Tier** | The enforcement class of a platform rule: **Tier 1 blocking gate** (executable, cannot be argued past) · **Tier 2 non-blocking reminder** (fact surfaced, work continues) · **Tier 3 advisory guidance** (judgment offered, cites rule + evidence). |

---

## 2. Naming conventions

| Convention | Rule |
|---|---|
| Principles | `AIP-nn` (platform) — parallel to `PGP-nn` (project), never mixed. |
| Capabilities | `CAP-nn`, singular noun phrase, one responsibility in one line. |
| Fitness functions | `FF-nn`, named for the property they verify. |
| Open questions | `OQ-AIP-nn` until resolved by a Human Decision Event, then closed — never deleted. |
| Events | Past-tense facts (`GatePassed`, `SessionArchived`). Human Decision Events are named for the decision, not the process. |
| Aggregates | Singular nouns; references to foreign artifacts end in `Ref` and are value objects (identity crosses as strings — ADR-T16 discipline). |
| Documents | Descriptive names; lifecycle lives in `status`, never in filenames (no `_FINAL`, `_v2` — EKP naming rule ⬆). |
| Phases | `Phase-NN[.n]-<Deliverable>.md` in the proposals staging area until promoted. |

---

## 3. Reserved vocabulary (meaning fixed; use only as defined)

`approve/approved` · `certify/certified` · `frozen` · `authoritative` · `verified` · `evidence` · `verdict` · `gate` · `owner/owns` · `preserves` · `observes` · `escalate` · `supersede` — these words carry governance weight. In any Phase 3 artifact they may be used **only** in their §1 senses. In particular: the platform never says "approved/certified/authoritative" about its own output.

---

## 4. Forbidden terms (synonyms and ambiguity killers)

| Forbidden | Use instead | Why |
|---|---|---|
| *truth score*, *quality score*, *confidence score* (asserted) | Verdict (with Evidence) | Phase 1 lesson: undefined scores are governance corruption. |
| *validated* | Verified (executable) or Certified (human) | Ambiguous middle word that blurs D-11. |
| *complete/done* (informal) | DoD-satisfied; Derived Progress | "Done" is 14/14, nothing less. |
| *learn/learning*, *neural*, *self-improving* | (no replacement — do not claim) | The platform makes no learning claims it cannot evidence. |
| *swarm*, *hive-mind*, *queen/worker* | (none) | Rejected source-framework vocabulary; imports orchestration cosplay. |
| *memory* (for governed knowledge) | Knowledge Item / Stable Fact / Context Snapshot | "Memory" is reserved for the repo-file convention (MEMORY.md) only. |
| *sync/update docs* (as a task description) | Supersede / append / record | AIP-11: history is never rewritten. |
| *check passed* (without artifact) | Verdict recorded (with Evidence Record) | No verdict without evidence. |
| *the AI decided* (for judgments) | The platform recommended; \<human role\> decided | Authority Boundary in speech, not just in code. |
| *agent* (in Phase 2 sense) | Capability / AI actor | "Agent" is a Phase 3 implementation word; using it earlier smuggles design into the model. |
| *plugin*, *framework* (for the platform) | Capability / Platform | Prevents drift back toward a prompt-collection mental model. |

---

## 5. Freeze procedure

On ARB approval of this document: status → `approved`, then `frozen` via the promotion chain. From that moment: every Phase 3 artifact is linted (conceptually, then executably — a future FF) against §3/§4; any new term or changed definition requires an ADR and produces a superseding version of this dictionary (AIP-11). Terms may be **added** by supersession; they may never be silently redefined.

---

*Traceability: consolidates the vocabulary of `Phase-02-Domain-Model.md` (BC ubiquitous languages §2), `Phase-02.5-Certification-Plan.md` (AIP/CAP/FF), inherited project terms from `docs/knowledge/Knowledge-Constitution.md`, `docs/implementation/Implementation_Process_v1.0.md`, `docs/architecture/patterns/Platform_Capability_Pattern.md`, `docs/architecture/principles/Platform_Governance_Principles.md`, and the ARB rulings of 2026-07-07 (R-1..R-6).*
