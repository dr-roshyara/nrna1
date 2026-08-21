# Review — EP-01 P2 (PKS Current Architecture Baseline) launch prompt: archaeology-first review and final prompt

**Prepared by:** Claude Code session, at the direction of the Human Principal Architect · 2026-08-21
**Status:** **PROPOSED · REVIEW — not an adoption, not authoritative**
**Subject:** the archaeology-first launch prompt for the fresh EP-01 P2 session (PKS Current Architecture Baseline)
**Related artifacts:** the EP-01 plan (`docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md`) · the durable P2 mandate and P1-quarantine disposition (`.claude/sessions/2026-08-21.md`, sections "P2 MANDATE" and "P1 DELIVERABLE MARKED BROKEN") · the PKS evidence estate in the repository
**⚠ This review records findings about a session launch prompt. It decides no architecture, no bounded context, no capability, no ownership, and no open question.**

---

## 1 · Scope and mandate of this review

The Human Principal Architect drafted an archaeology-first opening prompt for the fresh P2 session and requested review before launch. This file records the review finding and the resolution adopted.

**Decision already taken (recorded here, not made by this review):** the prompt is used **without amendment to the approved EP-01 plan**. The durable session record establishes P2 as the next actor and states that the broken P1 quarantine **does not** block the study (*"The broken P1 does NOT block the study — P2 proceeds now"*, session record 2026-08-21). The review confirms that reading of the durable record and does not re-derive it.

## 2 · Verdict

> **The draft prompt is consistent with the governing plan (§7 Stage 2, §8 P2, AMENDMENT 2 mandatory framework) and the durable P2 mandate (verbatim, session record 2026-08-21), and it corrects the P1 failure mode. Endorsed for launch after four refinements, folded in below.**

**What the prompt already got right (retained unchanged):**

- **Evidence hierarchy** (code > tests > persistence > ADRs > docs > C4 > brainstorming) matches plan §2.2 principle 2 and mandate text — *"Do not treat documentation as proof of implementation."*
- **A–G reality classification** with an explicit no-promotion rule (B→A, C→A, G→A forbidden) matches AMENDMENT 2 principle 8.
- **`BOUNDED CONTEXT CANDIDATE` / `AGGREGATE CANDIDATE` discipline** — a folder, module, service, database, repository, deployment unit, team, or C4 container is not itself a bounded context. Matches plan §7 "DDD discipline".
- **DDD-as-analysis, never DDD-as-redesign** — record debt as evidence, do not improve boundaries.
- **It fixes the P1 defect.** P1 was marked BROKEN because it stopped at §20 — no UNKNOWN register, no contradiction register. The prompt *mandates* §24 (Unknowns) and §25 (Contradictions). P2 will not repeat P1's failure mode.

## 3 · Findings

### F-1 · The PKS evidence corpus is wider than plan §5.2 — and §5.2 is not marked as exhaustive

**Repository evidence (verified 2026-08-21):** plan §5.2 names the governed Phase II/IIB/IIC/III artifacts, the Phase I package + ARB rulings, `PKS_ADR_001_*`, and two plans. The actual PKS evidence estate is larger and mostly outside §5.2:

| Source | Contents |
|---|---|
| `docs/implementation/PKS_Phase_II_*` | **66 files** (M-series strategic-modeling artifacts etc.) |
| `architecture_legacy/ai_architecture/pks/` | dozens of legacy brainstorming records **plus `pks_architecture.png`, a C4-architecture diagram** (evidence type 8 in the hierarchy) |
| `docs/knowledge_tranfer/` | `what_is_pks_v0.md` · `20260728_1710_what_is_pks_v1.md` · `PKS_Bootstrapping_and_Location.md` · `20260729_1812_pks_progress.md` — the historical / origin record of what PKS was designed to be |
| `docs/pks/` | PKS-behaviour observations and architecture reports |

**Implementation search (verified):** no PKS implementation is present under `app/`, `resources/`, `tests/`, `routes/`, or `database/`. PKS current state appears **documentation-only** — the A–G classification will be dominated by **G / UNKNOWN** unless the session independently locates executable evidence.

**Risk if ungoverned:** a fresh session reading only §5.2 misses the legacy diagram and the origin records, inflating UNKNOWNs artificially; or it discovers the wider estate and makes an ungoverned corpus decision.

**Resolution folded into the prompt:** §5.2 is the **governed starting corpus, not an exhaustive corpus**. The session discovers additional PKS evidence, **classifies every source by role** (CURRENT / IMPLEMENTED · ACCEPTED ARCHITECTURE · HISTORICAL / LEGACY · TRANSFER / RECONSTRUCTION · BRAINSTORMING · KNOWLEDGEOS PROPOSAL → **EXCLUDE**), admits only what reconstructs PKS current state, and **records every corpus-boundary admission/exclusion as a finding**.

### F-2 · The shared-vocabulary firewall was one notch stricter than mandate #7

**Mandate #7 (verbatim, binding):** *"Where EKS and PKS use the same word, determine independently whether the underlying concept, invariant, lifecycle, ownership, authority and consistency semantics are actually the same."* Plan §5 repeats it for `knowledge · product · evidence · authority · work item · workflow · observation · decision · artifact`.

**The draft's "NO EKS ↔ PKS COMPARISON" + "EKS may be mentioned only where unavoidable" could be read to forbid even *recognizing* a shared word** — suppressing exactly the per-concept data P3 needs and that mandate #7 requires the P2 session to produce.

**Resolution folded into the prompt** (a clean DDD discipline):

> **P2 detects shared LEXICAL terms. P2 does NOT establish semantic equivalence. That is P3.**
>
> *Lexical similarity is evidence for investigation, not evidence of bounded-context equivalence.*

Per shared concept the session records: **"shared lexical term" · "PKS meaning independently established" · "semantic equivalence NOT established"** — never a normalisation and never an equivalence conclusion.

### F-3 · Output path and stable registers unpinned

The draft did not state the deliverable path (the session could re-derive it under governance rules and risk a wrong call) and did not require stable IDs on the Unknown register (it required Contradiction IDs only).

**Resolution folded into the prompt:** fixed path `docs/knowledgeos/architecture/20260821-<HHMM>-PKS-Current-Architecture-Baseline-Stage-2.md` (following the P1 precedent `20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md`; the `<HHMM>` is the actual execution timestamp), the plan §8 P2 commit message verbatim, and **U-01… / X-01…** stable IDs on both terminal registers so P3/P4 (landscape) can reference them directly (`PKS U-07`, `PKS X-03`, `EKS U-12` …) — including in the eventual Reconciliation Ledger.

### F-4 · Whole-plan reading is a contamination vector

The draft said *"read the approved plan … before doing anything else"*. The plan is dense with Stage-4/5 content (kernel quarantine, Reconciliation Ledger spec, kernel gate questions, Review Set) — exactly the target-architecture framing the session must not absorb before reconstructing PKS.

**Resolution folded into the prompt:** initial read is restricted to **§1 Context · §2 Binding architectural context · §5.2 PKS evidence inputs · §7 Stage 2 · §8 P2**, plus the durable P2 mandate in full. Other sections are consulted only when necessary; Stages 3–5 and the Review Set are explicitly **NOT evidence** for this session.

## 4 · The four refinements folded in (summary)

| # | Refinement | Landed in prompt section |
|---|---|---|
| 1 | Corpus = §5.2 governed starting corpus + curated historical/legacy/transfer, role-classified; KnowledgeOS-proposal material excluded; corpus decisions = findings | new `PKS EVIDENCE CORPUS` section |
| 2 | Shared lexical terms detected in P2; semantic equivalence deferred to P3; "shared lexical term / equivalence NOT established" tagging | `SHARED VOCABULARY` block |
| 3 | Fixed output path + commit message; U-xx / X-xx stable register IDs | `EXPECTED DELIVERABLE` + sections 24/25 |
| 4 | Initial plan reading restricted to §1–§2 · §5.2 · §7 Stage 2 · §8 P2 | opening block |

**The approved EP-01 plan is untouched** — all refinements are session-prompt level.

## 5 · Explicitly NOT decided / NOT done

- ❌ **No amendment to the approved EP-01 plan.**
- ❌ **P2 not executed** in the reviewing session — the prompt is a launch instruction for a fresh session.
- ❌ No architecture, boundary, kernel-candidate, capability, ownership, or open-question determination.
- ❌ No EKS/PKS comparison, no migration/refactoring/technology recommendation.
- ❌ The PKS current-state classification is **not** performed here — that is the P2 session's output.

## 6 · Traceability

- Human Principal Architect's draft P2 launch prompt (2026-08-21, in-session)
- EP-01 plan: `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` — §2.2 (AMENDMENT 2), §2.4/§2.5 (AMENDMENTS 5/6), §5 (own ubiquitous language), §5.2 (PKS evidence inputs), §7 Stage 2, §8 P2 (commit message), §9 (DoD)
- Durable P2 mandate + P1 quarantine disposition: `.claude/sessions/2026-08-21.md` — "P2 MANDATE (binding for the next session)" and "P1 DELIVERABLE MARKED BROKEN" sections
- P1 precedent path: `docs/knowledgeos/architecture/20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md`
- Evidence inventory: repository search 2026-08-21 (PKS Phase II count · `architecture_legacy/ai_architecture/pks/` · `docs/knowledge_tranfer/` · `docs/pks/` · no implementation under `app|resources|tests|routes|database`)
- Placement: `docs/knowledgeos/reviews/` per the KnowledgeOS review placement convention (`README.md`, 2026-08-16) — KnowledgeOS work products are placed by derived convention, not by the producing session's path.

**Next actor:** a fresh session executes P2 with the prompt in Appendix A, then **STOPS for Human Principal Architect review**.

---

## Appendix A · Final P2 launch prompt (verbatim)

> The approved EP-01 plan is the governing plan for this work. Read the approved plan and the durable P2 mandate before doing anything else. Initially read ONLY the plan's P2-relevant sections — §1 Context · §2 Binding architectural context · §5.2 PKS evidence inputs · §7 Stage 2 · §8 P2 (plan: `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md`). Read the durable P2 mandate in full (record: `.claude/sessions/2026-08-21.md`, section "P2 MANDATE"). Consult other plan sections only when necessary. The plan's Stages 3–5 (AIP reconstruction, Landscape, Reconciliation Ledger, kernel) and the KnowledgeOS Review Set are NOT evidence for this session — do not absorb target-architecture material before reconstructing PKS.
>
> **P2 MANDATE:** Your task is to discover what PKS is, not what we want PKS to become. If evidence does not establish something, record UNKNOWN. Do not complete the architecture from the KnowledgeOS proposal.
>
> **ABSOLUTE SCOPE:** This session studies ONLY PKS — Product Knowledge System. Architecture archaeology: reconstruct the CURRENT architecture of PKS from evidence. Do NOT: design KnowledgeOS · design the KnowledgeOS kernel · validate KnowledgeOS bounded contexts · design aggregates for KnowledgeOS · compare PKS with EKS yet · perform EKS ↔ PKS integration analysis · extract shared primitives · propose migration/replacement/refactoring · redesign PKS/EKS/AIP · select technologies (Rust, Spring, Kafka, microservices, event sourcing…) · design the Digitalization Robot · make product/business decisions. Produce the PKS Current Architecture Baseline and STOP.
>
> **ARCHITECTURAL FIREWALL:** The proposed KnowledgeOS architecture is NOT evidence of PKS. Do not use KnowledgeOS bounded contexts, kernel concepts, evidence/governance/execution/product models, the Linux/Unix analogy, the Digitalization Robot concept, or previous EKS architecture/terminology to fill gaps in the PKS architecture. EKS may be mentioned only where unavoidable to explain an ambiguity in historical documentation. If PKS evidence does not resolve the ambiguity: UNKNOWN. Do not resolve it by using EKS semantics.
>
> **PKS HAS ITS OWN UBIQUITOUS LANGUAGE:** Reconstruct PKS using PKS's OWN language. Do not normalize PKS terminology to EKS terminology. For every important PKS concept determine independently: meaning · ownership · behaviour · what it does NOT own · lifecycle · invariants · authority · consistency requirements · persistence · relationships · dependencies. Only after P2 is complete may we compare these concepts with EKS. **SHARED VOCABULARY (mandate #7):** P2 detects shared LEXICAL terms; P2 does NOT establish semantic equivalence (that is P3). *Lexical similarity is evidence for investigation, not evidence of bounded-context equivalence.* Per shared concept record: "shared lexical term" · "PKS meaning independently established" · "semantic equivalence NOT established".
>
> **CRITICAL IDENTITY AMBIGUITY:** Determine from PKS evidence whether PKS is independent from EKS, consumes EKS, is consumed by EKS, shares infrastructure / knowledge artifacts / domain concepts with EKS, is separate, is a subsystem, or whether the historical documentation uses inconsistent names. If the evidence is insufficient: **PKS/EKS relationship = UNKNOWN** — a finding, not a failure.
>
> **EVIDENCE HIERARCHY:** 1 implementation/source code · 2 executable tests · 3 persisted PKS data/artifacts · 4 configuration · 5 runtime/execution records · 6 accepted ADRs/governance decisions · 7 architecture documentation · 8 C4/PlantUML · 9 design documents · 10 brainstorming/history. Do not treat documentation as proof of implementation. Where documentation and implementation disagree: report both, identify the contradiction, prefer stronger evidence, do not silently reconcile.
>
> **PKS EVIDENCE CORPUS (binding supplement to plan §5.2):** §5.2 is the governed starting corpus, NOT necessarily an exhaustive corpus. Discover additional PKS evidence in the repository, classify it by evidence status, and include it only when it can contribute to reconstructing PKS current state. Exclude KnowledgeOS target/proposal material. Classify every source by role: CURRENT / IMPLEMENTED · ACCEPTED ARCHITECTURE · HISTORICAL / LEGACY · TRANSFER / RECONSTRUCTION · BRAINSTORMING · KNOWLEDGEOS PROPOSAL → EXCLUDE. Concrete corpus: **A.** Accepted architecture — plan §5.2 (binding minimum): `PKS_Phase_II_Methodology_Baseline_v1_2.md`, `PKS_Phase_II_M0…M8_*.md`, `PKS_Phase_IIB_*`, `PKS_Phase_IIC_*`, `PKS_Phase_III_EAD_1_*`, Phase I package + `PKS_Phase_I_ARB_Rulings.md`, `PKS_ADR_001_*`, the two PKS plans. **B.** Historical / origin records: `docs/knowledge_tranfer/20260728_1710_what_is_pks_v1.md`, `what_is_pks_v0.md`, `PKS_Bootstrapping_and_Location.md`, `20260729_1812_pks_progress.md`. **C.** Legacy / brainstorming: `architecture_legacy/ai_architecture/pks/` (incl. `pks_architecture.png`) EXCEPT KnowledgeOS/PKS mixed material (filename contains "knowledge_os"/"knowledgeos", or content frames PKS as part of KnowledgeOS). **D.** Current / implemented: no executable PKS implementation is known to exist in this repository — verify independently; if confirmed, record as a headline finding "PKS current state = documentation-only; A–G dominated by G / UNKNOWN." **Exclude (never evidence):** KnowledgeOS Review Set (`docs/knowledgeos/architecture/00…07-*.md`), `docs/knowledgeos/*`, kernel-extraction analyses (plan §5.4 quarantine), the EKS baseline/evidence. Corpus-boundary decisions are FINDINGS; if the boundary cannot be established: record UNKNOWN.
>
> **CURRENT-STATE CLASSIFICATION:** Classify every significant finding as **A** IMPLEMENTED · **B** IMPLEMENTED BUT IMPLICIT · **C** PARTIAL · **D** WRONG-BOUNDARY / ARCHITECTURAL DEBT · **E** MISSING · **F** CONTRADICTED · **G** DOCUMENTED / HYPOTHESIS ONLY, or **UNKNOWN** when evidence cannot establish the claim. Never promote B→A, C→A, or G→A merely because architecture documentation describes the desired behaviour.
>
> **DDD ARCHAEOLOGY:** Use DDD as an analytical discipline, NOT as a reason to redesign PKS. Do not declare a bounded context merely because you find a directory, module, service, database, repository, deployment unit, team, or C4 container — use **BOUNDED CONTEXT CANDIDATE**; likewise **AGGREGATE CANDIDATE** unless lifecycle, invariant ownership and consistency boundaries establish it.
>
> **RECONSTRUCT ACTUAL PKS BEHAVIOUR:** knowledge lifecycle (create/change/review/approve/version/retire; what is a valid knowledge state) · ownership (who owns/changes/approves/publishes/consumes) · authority (grants/records/enforces/delegates/derives authority, or merely stores approval information — do not assume) · consistency (atomic vs eventual; where transactions occur; database/application/workflow/governance level) · persistence (authoritative/derived/cached/generated/reconstructable) · integration (external systems, APIs, files, databases, brokers, repositories, batch, import/export — only mechanisms actually evidenced).
>
> **REQUIRED PKS ARCHITECTURE AREAS (25):** 1 Executive Summary · 2 System Purpose · 3 Business / Product-Knowledge Capabilities · 4 Actors and Users · 5 PKS Ubiquitous Language · 6 Domain Model · 7 Bounded-Context Candidates · 8 Application / Workflow Architecture · 9 Component Architecture · 10 Data Architecture · 11 Persistence Architecture · 12 Invariants · 13 State and Lifecycle · 14 Events and Messaging · 15 Governance and Authority · 16 Assurance / Validation Architecture · 17 Integration Architecture · 18 Dependency Architecture · 19 Runtime / Deployment Architecture · 20 Architectural Patterns Actually Present · 21 Architectural Strengths · 22 Architectural Problems / Debt · 23 Current Architecture Diagram · 24 Unknowns / Evidence Gaps — stable IDs **U-01, U-02, …** · 25 Contradictions / Conflicting Evidence — stable IDs **X-01, X-02, …**. If a section cannot be established: **UNKNOWN / INSUFFICIENT EVIDENCE** — never fabricate to complete the section.
>
> **IMPLEMENTATION VS CONCEPTUAL MODEL:** distinguish IMPLEMENTED → PARTIALLY IMPLEMENTED → DOCUMENTED ONLY → PROPOSED → UNKNOWN. Do not describe conceptual PKS architecture as implemented architecture; if a document says "PKS provides X", verify whether the implementation actually provides X.
>
> **ARCHITECTURAL BOUNDARY DISCIPLINE:** do not improve PKS boundaries — ask where boundaries are visible/implicit, where responsibilities are mixed, where invariants cross boundaries, where ownership is unclear, where the implementation violates its own conceptual model. Record debt as evidence; do not redesign it.
>
> **NO KNOWLEDGEOS KERNEL EXTRACTION:** do not ask "what can we extract from PKS into the KnowledgeOS kernel?" (that is the later convergence phase). Ask only "what actually exists in PKS?". P2 must NOT produce a kernel candidate register.
>
> **NO EKS ↔ PKS COMPARISON:** no "PKS Product is equivalent to EKS Work Item", no "PKS Evidence should become KnowledgeOS Evidence", no "PKS and EKS share the same bounded context" — those belong to P3. P2 records similar-looking concepts only in PKS terms: `PKS concept / PKS meaning / PKS invariant / PKS owner / PKS lifecycle / PKS authority / PKS consistency` — never "this is the PKS equivalent of EKS X".
>
> **SOURCE TRACEABILITY:** every major claim carries source · evidence type · confidence · current-state classification · location. Maintain explicit evidence gaps. When sources disagree: Contradiction ID · Source A · Source B · nature of conflict · stronger evidence if determinable · UNKNOWN if not. Do not silently reconcile.
>
> **EXPECTED DELIVERABLE:** the PKS Current Architecture Baseline must let a Principal Architect who has never worked on PKS answer "What is PKS today, how does it actually work, what does it own, what are its real boundaries, where are the unknowns?" — and must NOT answer "What should PKS become?" or "How should PKS become part of KnowledgeOS?". **OUTPUT LOCATION & COMMIT (binding):** write to `docs/knowledgeos/architecture/20260821-<HHMM>-PKS-Current-Architecture-Baseline-Stage-2.md` (directory and semantic filename FIXED; `<HHMM>` = actual execution timestamp; do not derive or relocate). Commit message verbatim: `docs(knowledgeos): Stage 2 — PKS Current Architecture Baseline (PROPOSED, evidence-based)`. Bookkeeping alongside the commit: day's session log + `.claude/CONTEXT.md` (NEXT → P2 baseline awaits Human Principal Architect review).
>
> **QUALITY TEST:** can every major PKS architectural statement be traced to evidence? Yes → retain. Partially → mark PARTIAL / IMPLICIT / HYPOTHESIS. Unsupported → UNKNOWN. Contradicted → record the contradiction. Never fill a gap because the proposed KnowledgeOS architecture provides a convenient answer.
>
> **STOP CONDITION:** when the PKS Current Architecture Baseline is complete: STOP. Do not analyze EKS, compare EKS and PKS, analyze AIP, create the Reconciliation Ledger, design KnowledgeOS / the kernel, validate bounded contexts, propose migration, select technology, or create implementation tasks. P3 (EKS + PKS + AIP Current Architecture Landscape) starts only after human Principal Architect review of P2.
>
> **FINAL PRINCIPLE:** the purpose of this session is not to make PKS fit KnowledgeOS — it is to discover whether PKS contains architectural truth that KnowledgeOS must preserve. **DISCOVER FIRST. COMPARE LATER. GENERALIZE LAST.** *Your task is to discover what PKS is, not what we want PKS to become. If evidence does not establish something, record UNKNOWN. Do not complete the architecture from the KnowledgeOS proposal.*
