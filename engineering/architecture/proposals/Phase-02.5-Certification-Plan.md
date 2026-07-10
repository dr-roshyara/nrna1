# AI Engineering Platform — Phase 2.5: Capability Model & Certification Plan (Deliverable 10)

> Renamed from `Phase-02.5-Capability-Certification.md` per ARB ruling R-7 (2026-07-08): a certification document may only exist after certification has occurred (AIP-10). The name `Capability-Certification.md` is reserved for the future post-gate report.

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced; never authoritative without human review) |
| **Status** | **FROZEN — Baseline v1.0 (ADR-AIP-01, 2026-07-08). SEALED PERMANENTLY (R-30, 2026-07-08): this document is historical evidence of what was proposed; no further edits, ever.** The §6 rulings register is CLOSED at R-29 — the living register continues at `docs/adr/ADR-AIP-LOG-Platform-Rulings.md` |
| **Owner** | Architecture Review Board |
| **Promotion** | Generated → ARB Review → Capability Certification → ADR Approval → Authoritative → Frozen (chain endorsed at ARB Phase 2 review) |
| **Date** | 2026-07-07 |
| **Depends on** | `Phase-02-Domain-Model.md` (deliverables 1–9) · followed by `Phase-02.6-Ubiquitous-Language.md` (vocabulary freeze, ARB-mandated before Phase 3) |

> **Honesty note on this document's own name.** Certification of the AI platform has **not occurred**. This document is the capability **model** plus the certification **plan** — the protocol the platform must pass at its future D-11-style gate. Per AIP-10 (Assertion Integrity: *no artifact may assert an event that has not occurred*), the certification **report** will be a separate artifact, constructible only after an ARB `CapabilityCertified` (or `CertificationRefused`) event exists.

> **Anti-over-engineering rule (ARB observation 1, standing):** *The AI platform exists to support PublicDigit, not to become another enterprise system.* Every additional aggregate, context, or lifecycle must answer "does this reduce complexity?" (ER-05). Consequences applied in this revision: CertificationPlan/CertificationReport are **artifact rules, not new aggregates** (the plan is §5 of this document; the report is event-gated); **KnowledgePackage is documentation, not a domain aggregate** (a curated, lint-validated manifest — ARB observation 2); no further modeling depth is added anywhere without demonstrated need.

> Scope discipline: capabilities are **not mapped to files** — file/folder/prompt design is Phase 3.

---

## 1. Domain classification (ARB-decided)

| Class | Elements | Rationale |
|---|---|---|
| **Core Domain** | **Architecture Governance** — Design & Decision Support plus the governance model itself (promotion chain, ownership matrices, AIP principles) | **ARB ruling (2026-07-07):** this is the Core Domain. Verification is indispensable but *serves* governance; it does not define the platform's unique value. The prior open question (Verification & Evidence as Core candidate) is **closed: Supporting**. |
| **Supporting** | Knowledge Governance · Adversarial Review Support · Implementation Guidance · Session Continuity · Verification & Evidence | Necessary and project-shaped; each implements discipline the project has already defined. |
| **Generic** (adopt native primitives; never model richly) | Version control (git) · AI provider/runtime (behind the gateway principle, §3 AIP-07) · hooks/automation runtime · presentation · CI runners | Buy/adopt; thin adapters only. |

---

## 2. Capability catalog

Each capability: one responsibility, one owning bounded context (PGP-02), declared dependencies, extension points, governance tier. CAP numbering provisional until ARB certification.

| ID | Capability | Responsibility (one line) | Owner (BC) | Depends on | Extension points | Governance |
|---|---|---|---|---|---|---|
| CAP-01 | **Context Bootstrap & Rehydration** | Assemble the correct governed state (stable facts, current state, active plan, today's log, task-relevant knowledge bundle) into every session start and post-compaction resume. | Session Continuity | CAP-03, CAP-05 | New bundle sources (governed artifacts only) | Tier-2 staleness reminder; FF-11 |
| CAP-02 | **Session Recording & Archival** | Append-only session logs; immutable archival; a session may not end without state sync. | Session Continuity | — | New log sections (append-only) | Tier-1 immutability (FF-12); Tier-2 sync reminder |
| CAP-03 | **Knowledge Capture & Quarantine** | Register every AI-produced governed artifact as `authority: generated`, correctly carded, entering the promotion chain. Includes curating documentation bundles (packages) as lint-validated manifests. | Knowledge Governance | EKP spec (upstream) | New knowledge types via schema (ADR-gated); new packages per recurring task | Tier-1 Authority Boundary (FF-1/FF-2); lint (FF-10) |
| CAP-04 | *(merged into CAP-03 — ARB observation 1/2: package provisioning is documentation curation, not a separate capability)* | | | | | |
| CAP-05 | **Implementation Planning & Progress Derivation** | Maintain the per-ticket plan (WBS, 15 steps, 14-box DoD); derive all progress figures from WBS — never hand-written. | Implementation Guidance | Frozen Process v1.0 (upstream), CAP-07 | New WBS item types; DoD changes only via process re-versioning | Tier-1 step order, no-progress-setter (FF-5) |
| CAP-06 | **Discipline Gating & Tripwires** | Surface the standing sequence (Business → DDD → Architecture → Tests → Implementation) and the gap sequence (Finding → Decision → RED → GREEN → Certification) before violations happen. | Implementation Guidance | CAP-05 | New tripwire rules (each citing the ER/rule it enforces) | Tier-2 by design; Tier-1 for step-order breaches |
| CAP-07 | **Gate Execution & Honest Verdict Recording** | Run executable checks (PHPStan gate, architecture suite, knowledge-lint, future Deptrac/Infection); record immutable, evidence-bearing verdicts. | Verification & Evidence | The project's check suites (observed) | New checks registered with falsifiability proof (RED run) | Tier-1 Honesty Invariant (FF-3/FF-4) |
| CAP-08 | **Traceability & Audit Trail** | Maintain the ticket→IDD→commit→test→DoD chain and the append-only evidence record. | Verification & Evidence | CAP-05, CAP-07 | New hop types only via process change | Tier-1 (FF-6, FF-12) |
| CAP-09 | **Constitutional Observation & Escalation** | Read-only observation of constitutional guards (CI-1..5/Q7); on a trip: halt, escalate, never retry, never modify. | Verification & Evidence | Constitutional suite (observed; zero write-path) | None — deliberately closed | Tier-1 action-space asymmetry |
| CAP-10 | **TDD & Craft Coaching** | Advisory: RED-before-GREEN enforcement support, refactoring and convergence (ER-05) advisories — every advice citing rule + evidence. | Implementation Guidance | CAP-05, CAP-07 | New coaching doctrines (grounded in governed rules) | Tier-3 advisory only |
| CAP-11 | **Adversarial Review Support** | Produce attempt-to-reject analyses, evidence-cited findings, and certification *recommendations* (`generated`) for ARB/Chief-Architect gates. | Adversarial Review Support | CAP-03 (published artifacts), CAP-07 (evidence) | New review protocols (dictated by the Customer — ARB) | Tier-1 producer ≠ reviewer (FF-7); evidence-bearing findings (FF-8) |
| CAP-12 | **Decision & Capability Drafting** | Draft ADRs (one decision, ≥2 options, convergence note) and capability models (12-section pattern, exactly-one-owner validation); APPROVED unreachable internally. | Design & Decision Support **(Core)** | Platform Capability Pattern + ADR discipline (upstream) | New draft templates mirroring frozen upstream templates | Tier-1 Authority Boundary; Tier-3 "consider an ADR" prompts |
| CAP-13 | **Platform Self-Governance** | Maintain the platform's own capability registry (this catalog), ownership matrix, and AIP principles; exactly one owner per capability; **every platform decision traces ADR → Capability → Rule → Implementation, never Decision → Implementation** (ARB observation 5). | Design & Decision Support **(Core)**; fitness functions hosted by Verification & Evidence | CAP-07, CAP-12 | New capabilities enter as `Proposed` (§4) | Tier-1 (FF-9, FF-13, FF-16) |

*(Deliberately not a capability: an "AI Provider Gateway" context. The **principle** of vendor independence is adopted now — AIP-07 + FF-15 — but building a gateway context before a second provider is a live option would violate ER-05 and ARB observation 1. Revisit under business pressure.)*

---

## 3. Platform principles (AIP) — each with guard host (PGP-03)

The platform's counterpart of PGP-01..05. Each principle is itself a governed knowledge item entering at `generated`.

| ID | Principle | Enforced by | Guard host |
|---|---|---|---|
| AIP-01 | Evidence before Authority | ER-02 conformance; Finding/Verdict require EvidenceRef by construction | Verification & Evidence |
| AIP-02 | Human Approval Boundary | Promotion chain; APPROVED/CERTIFIED unreachable in platform models | Knowledge Governance |
| AIP-03 | No Hidden State | Repo is single source of truth; every stable fact carries provenance | Session Continuity |
| AIP-04 | Deterministic Behaviour | Verdicts derived from executable checks; re-runs create new verdicts, never edits | Verification & Evidence |
| AIP-05 | Separation of Duties | discover ≠ decide ≠ check ≠ entrench; producer ≠ reviewer; Separate Ways BC-6/BC-5 | Adversarial Review Support |
| AIP-06 | Least Authority | Everything emitted is `generated`/`provisional`; read-only toward constitutional guards | Knowledge Governance |
| AIP-07 | Vendor Independence | No provider vocabulary inside bounded contexts; FF-15 scan | Design & Decision Support (until a gateway exists) |
| AIP-08 | Reproducibility | Bootstrap assembled from governed sources only; immutability digests | Knowledge Governance / Verification & Evidence |
| AIP-09 | Architecture before Implementation | ER-01 conformance; plan blocks GREEN without approved decision/IDD refs | Implementation Guidance |
| AIP-10 | **Assertion Integrity** — no artifact may assert an event that has not occurred | Event-gated report construction; FF-14 doc scan | Knowledge Governance |
| AIP-11 | **Append-Only History** — *"Nothing is ever rewritten. Everything is superseded."* (ARB observation 4 — the election-audit principle applied to the platform) | Draft → Approved → Frozen → Superseded; supersession creates new artifacts and demotes old to `historical`; FF-12 hash check | Knowledge Governance |
| AIP-12 | **Decision Traceability** — every platform decision flows ADR → Capability → Rule → Implementation; never Decision → Implementation (ARB observation 5) | CAP-13 registry links; FF-6-style chain resolution over platform decisions | Design & Decision Support |
| AIP-13 | **Implementation-Driven Evolution** (ARB ruling R-12) — the architecture is complete; further refinement arises from implementation experience, not speculative modeling. New architectural artifacts require explicit ARB approval; amendments preferred over new documents | ARB gate on any new architecture doc; ER-05 convergence check | Design & Decision Support |
| AIP-14 | **Product Primacy** (ADR-AIP-02, added to this frozen table under that ADR's authority) — the platform exists solely to improve delivery of PublicDigit; every iteration produces measurable progress on a PublicDigit feature; platform-only iterations are exceptional and require explicit ARB approval; two consecutive platform-only iterations trigger an ARB over-evolution review | Platform Cost metric (derived, per iteration) + iteration-close protocol | Design & Decision Support |

---

## 4. Capability lifecycle

Each platform capability carries, alongside knowledge `status` and `authority`:

```
Proposed → Experimental → Certified → Frozen → Deprecated → Archived
```

Invariants: `Certified` only via an observed ARB `CapabilityCertified` event · `Frozen` only via ADR · an `Experimental` capability may not be depended on by a `Certified` one (FF-16) · `Deprecated` requires a successor ref or an ADR accepting the gap · `Archived` is immutable and terminal (AIP-11).

**Additional fitness functions** (extending FF-1..13 of `Phase-02-Domain-Model.md` §8; all executable, falsifiability proven before active):

| # | Fitness function | Executable verification |
|---|---|---|
| FF-14 | Assertion integrity | Scan governed docs: zero documents claiming `certified`/`approved` without a resolvable reference to the corresponding human decision event. |
| FF-15 | Vendor independence | Static scan: zero provider-specific identifiers (model names, SDK imports, provider config vocabulary) outside the designated adapter seam. |
| FF-16 | Experimental-dependency check | Registry scan: zero `Certified` capabilities depending on `Experimental` ones. |

---

## 5. Certification plan — the platform's own D-11-style gate

When Phase 3 has produced the platform and its verification matrix is green, the ARB convenes an adversarial certification (attempt-to-reject, per the `PB-003_Architecture_Readiness_Report.md` method). **Evidence prerequisites:** FF-1..16 executable and green, each with a recorded falsifiability (RED) run.

**Certification question set** (analogue of the Messaging 7 reuse questions — every answer must be *Yes with evidence*; any *No* halts and routes back to design):

1. Does every capability have exactly one owner and one responsibility, with zero capability implemented twice? (FF-13 + registry review)
2. Is every verdict, score, or progress figure the platform reports derived from an executable check — zero asserted numbers anywhere? (FF-3, FF-5, FF-14)
3. Can any `generated`→`authoritative` promotion, gate passage, or certification be reached without a referenced human decision event? (FF-1 — must be **No**)
4. Is the platform's entire state inspectable and versioned in the repository — zero hidden state, zero external runtime dependencies? (FF-9, FF-11, AIP-03)
5. Does the platform have zero write-paths toward constitutional invariants and their guards, and does a guard trip halt-and-escalate with no retry path? (CAP-09 review + synthetic-trip test)
6. Is separation of duties structural — can a producer ever review its own artifact; do Design Support and Review Support share any model? (FF-7 — must be **No/No**)
7. Can a new architect (human or AI) reach operational understanding from ≤3 governed documents in one session? (witnessed onboarding test — Phase 1 success criterion 5)

**Certification output:** a separate report artifact recording the ARB's decision event — never this document (AIP-10).

---

## 6. ARB rulings recorded at Phase 2 review (2026-07-07)

| # | Ruling | Effect in this document |
|---|---|---|
| R-1 | Anti-over-engineering: the platform supports PublicDigit; every new model element must reduce complexity | Standing rule (header); CAP-04 merged; CertificationPlan/Report demoted to artifact rules; gateway context deferred |
| R-2 | Documentation is not a domain: KnowledgePackage is a curated manifest, not an aggregate | Package curation folded into CAP-03; `Phase-02-Domain-Model.md` §4 amended accordingly |
| R-3 | **Core Domain = Architecture Governance** (Verification serves governance; it does not define unique value) | §1 decided; prior open question closed |
| R-4 | Append-only history: "Nothing is ever rewritten. Everything is superseded." | AIP-11 |
| R-5 | Decision traceability: ADR → Capability → Rule → Implementation, never Decision → Implementation | AIP-12; CAP-13 responsibility |
| R-6 | **Phase 2.6 Ubiquitous Language Freeze** inserted before Phase 3 | `Phase-02.6-Ubiquitous-Language.md` is the next Phase 2 artifact |

**ARB rulings at the follow-up review (2026-07-08). Phases 01, 02, and the 02.6 direction: ACCEPTED.**

| # | Ruling | Effect |
|---|---|---|
| R-7 | Rename: a certification document may only exist post-certification (AIP-10 applied to filenames) | Renamed `Phase-02.5-Certification-Plan.md`; `Capability-Certification.md` reserved for the future post-gate report |
| R-8 | **Phase 3 is split.** Phase 3A Platform Architecture (CLAUDE.md architecture, rules, capabilities, dependency graph, loading order, inheritance, extension model — **no implementation**) → Phase 3B Implementation (write CLAUDE.md, hooks, agents, commands, settings, templates) | Prevents implementation decisions from driving architecture |
| R-9 | Create `Phase-02.7-Platform-Decisions.md` — the platform's constitution: binary authority decisions | Created |
| R-10 | **Provider-independence litmus** for every design decision: *"Would this still make sense if the current AI provider disappeared tomorrow and were replaced by another?"* Yes → stable architectural concept; No → implementation detail behind the provider seam | Recorded in Phase-02.7; governs Phase 3A |
| R-11 | Phase 2.6 is **not frozen yet**: ARB reviews it like an ADR (duplicates · synonyms · single meanings · Laravel/domain separation · provider-term exclusion · alignment with PublicDigit's ubiquitous language) before freezing | Freeze pending that review |

**ARB rulings at architecture-closure review (2026-07-08):**

| # | Ruling | Effect |
|---|---|---|
| R-12 | **The architecture phase is complete.** Further architectural refinement must arise from implementation experience, not speculative modeling. From this point, every new architectural artifact requires explicit ARB approval; if implementation reveals a missing concept, propose an **amendment** to an existing document, never a new standalone phase/document | Codified as AIP-13; no Phase-02.8+ documents |
| R-13 | **Phase 3A = Reference Architecture** (the last design artifact): how the domain model becomes software — Capability → Software Component → Runtime → Configuration → Persistence → Extension. No code | `Phase-03A-Reference-Architecture.md` |
| R-14 | **The platform is not finished after Phase 3B.** It is validated by implementing a real PublicDigit feature (e.g. an Election/Voting bounded-context change) through the new platform; implementation becomes the primary source of architectural feedback | Validation plan recorded in Phase 3A |

**Construction-era rulings (2026-07-08, appended — register is Living):**

| # | Ruling | Effect |
|---|---|---|
| R-23 | **AIP-14 Product Primacy adopted** (via ADR-AIP-02): every iteration produces measurable PublicDigit progress; platform-only iterations exceptional (ARB-approved); two consecutive platform-only iterations → over-evolution review. Platform Cost metric (derived) + six-question iteration-close protocol instituted. Iteration 1 is the sanctioned platform-only exception | ADR-AIP-02; AIP table row added; baseline cost recorded in the Iteration 1 plan |
| R-24 | **FF-17 Documentation Authority adopted** (definition): executable check that the documentation ecosystem stays coherent — every developer guide references an ADR · no guide contradicts the registry · no registry entry references a missing component · no ADR references a deleted artifact · examples match the current registry schema. **Implementation deferred** (AIP-14: not required by PB-004); formal table incorporation at the next FF-section supersession | Definition on record; build when evidence demands (Iteration 2 candidate) |
| R-25 | **C2 re-sequenced after Platform Qualification** — evidence-based answer to the ARB question "is C2 a prerequisite for qualifying with PB-004?": **No.** (a) plan `Last Updated` stamping used by PB-004 lives in-repo (`session-changes-logger.sh`); (b) the machine-local hook (AST-008) only fires on PlanCreate, is **demonstrably broken locally** (depends on `jq`, a documented broken shim; this session's plan file was never renamed), and its intended rename-to-`plan_<timestamp>.md` behavior contradicts the project's descriptive-filename convention; (c) PB-004's DoD needs gate evidence (C3), not plan renaming. C2 also **shrinks**: likely unwire + deprecate rather than relocate — decided at its own slice review. Iteration order: C1 → **C3 → PB-004 qualification** → C2 | Plan updated; qualification starts sooner |
| R-26 | **Measuring-instrument rule + lean-forward protocol** (ARB 2026-07-08): (1) the Verification Engine performs **no interpretation** — run → capture output → PASS/FAIL → stop; no explanations, recommendations, or AI summaries inside the instrument (analysis belongs to Review/Coaching contexts); (2) Platform Value ledger carries an **Evidence** column (never scores); (3) the standing construction question becomes *"what is the SMALLEST platform change that unlocks the next PublicDigit capability?"*; (4) after PB-004: a **usage retrospective** — which platform parts did PB-004 exercise vs. leave unused; unused parts are challenged (simplify/remove unless foundational) — the closing stage of AIP-13. No new governance until then | Plan + registry AST-010 note updated |
| R-27 | **GOVERNANCE FREEZE + standing instruction for C3** (ARB closing directive, 2026-07-08): (1) **no new platform principles until the PB-004 retrospective** — clarifications of existing governance remain legal, additions do not; (2) **no further architectural expansion** — the sole success criterion for the next session: *can AST-010, as the first implementation of the Verification Engine, help deliver PB-004 with greater determinism, traceability, and honesty than before?* If yes → proceed directly to PB-004; the retrospective, not speculation, decides any amendment; (3) **after the retrospective the process changes to Continuous Evolution**: Feature → platform observation → retrospective → one amendment (if justified) → next feature. "Iteration" vocabulary retires; platform work is thereafter named by the feature it serves (PB-005 → amendment? → PB-006 → …). No platform roadmaps, no speculative capabilities; (4) vocabulary candidates for the next 02.6 supersession (OI-1): "Platform **Operational Readiness**" (preferred over "Qualification" — readiness → used → observed → improved), "the source disappears early", the measuring-instrument philosophy | Freeze in force; plan + CONTEXT updated |
| R-28 | **Engineering Doctrine — named Iteration-2 candidate (CMP-009 reserved), DEFERRED per AIP-14/R-27** (ARB 2026-07-08, final architecture review). Concept: a capability distinct from platform governance — *platform governance governs the AI platform; engineering doctrine governs software design* — encoding PublicDigit's architectural laws (strategic+tactical DDD · TDD · Clean · Hexagonal · Event-Driven · CQRS-where-valuable · ADR-traceability · verification-before-certification) as **first-class, verifiable rules with stable ids** (DDD-nn / TDD-nn / CA-nn / HEX-nn …) that implementation artifacts declare and the platform can check — stronger than "I followed DDD". **Evidence note:** most doctrine *content* already exists as frozen project artifacts with executable guards (CLAUDE.md layer rules, Implementation Process TDD rules, ADR-T corpus, architecture test suite, PHPStan/Deptrac) — the candidate capability is *traceable doctrine-ids over existing rules*, not new rules. **Trigger:** build only if PB-004 shows repeated architectural mistakes the existing enforcement (tripwires + architecture tests + frozen process) failed to prevent; decided at the usage retrospective. **Final architecture review outcome recorded:** *Approved — the program has transitioned from an architectural program into an engineering program; future changes justified primarily by implementation evidence.* | Candidate registered; nothing built; retrospective decides |
| R-29 | **FINAL ARB INSTRUCTION — Platform Foundation declared COMPLETE** (architecture-complete = knew *what* to build; foundation-complete = enough runtime structure — registry, process, governance, provider binding, session continuity — to implement real work without more infrastructure): ***"No platform change shall be made unless PB-004 or a subsequent feature demonstrates that the current platform is insufficient."*** The strongest expression of AIP-13; for construction purposes it supersedes all softer phrasings of the freeze. EP-01 Planning Stage + EP-02 Completion Review recorded in `Implementation_Process_v1.1_Draft.md` (project process, not platform governance). Philosophy candidate queued for future Engineering Standards: *"The platform exists to improve engineering decisions, not to automate engineering judgment."* | In force |

**ARB Resolution — Baseline (2026-07-08, recorded as ADR-AIP-01):**

| # | Ruling | Effect |
|---|---|---|
| R-15 | **AI Engineering Platform Baseline v1.0** — Phases 01–03A accepted and frozen ("baseline", not "closed": stable → validated → evolves by amendment). One formal ADR records the decision | `docs/adr/ADR-AIP-01-AI-Engineering-Platform-Baseline-v1.0.md` (Accepted); artifact headers stamped |
| R-16 | Phase 2.6 freeze conditional on one final terminology review | Open item **OI-1** (tracked in ADR-AIP-01) |
| R-17 | **Phase 3B mission:** realize the approved reference architecture. Every file must answer: which capability owns me → which bounded context → which principle justifies me → which platform decision governs me → which ADR authorizes me. A file that cannot answer these shall not exist | Phase 3B DoD |
| R-18 | **Phase 4 — Platform Validation:** pilot = **PB-004 (ContestedOutcome / Election Reaction)** — exercises DDD, governance, ADR workflow, implementation guidance, verification, review, traceability | Follows 3B immediately; friction → amendment proposals (AIP-13) |

**ARB sign-off rulings (2026-07-08, recorded in ADR-AIP-01 Addendum):**

| # | Ruling | Effect |
|---|---|---|
| R-19 | ADR-AIP-01 **APPROVED (signed)**; baseline designated **"Baseline v1.0 — Reference Implementation Pending"** (architecture approved ≠ implementation proven) | ADR-AIP-01 Addendum |
| R-20 | Renames: Phase 3B → **Platform Construction**; Phase 4 → **Platform Qualification**; phases end — construction proceeds in **Iterations** (It-1 minimal platform for PB-004 · It-2 refine from lessons · It-3+ advanced only if evidence-justified) | Vocabulary for OI-1 to absorb into the glossary on its review |
| R-21 | **Construction discipline:** incremental commits, each leaving the platform usable/green/testable (indicative order: root+registry → rules → knowledge → hooks → commands → agents); cadence = small slice → review → merge; **never one session** | Binding on Iteration 1 |
| R-22 | OI-1 scope note: the term **"Review"** (architecture/code/security/gate/ARR/human/AI) is the exemplary ambiguity the terminology review must disambiguate | OI-1 input |

---

*Traceability: derives from `Phase-02-Domain-Model.md` · pattern discipline from `docs/architecture/patterns/Platform_Capability_Pattern.md` · certification method from `docs/implementation/PB-003_Architecture_Readiness_Report.md` + D-11 · principle style from `docs/architecture/principles/Platform_Governance_Principles.md` · ARB rulings of 2026-07-07 recorded in §6.*
