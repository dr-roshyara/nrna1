# EP-01 Plan — EKS + PKS (+ AIP) Current Architecture Baselines, Landscape & KnowledgeOS Evolution Study

> **⛔ STATUS 2026-08-21 — APPROVED FOR EXECUTION (Human Principal Architect, in-session; plan otherwise final, AMENDMENTS 3–6 binding, no further architectural amendment).** This is the **governed home** of the plan (ES-004.2). Draft origin: `.claude/plans/starry-jumping-tower.md` (plan-mode working file). **Execution scope per the approval:** a **fresh session** executes **P1 → EKS Current Architecture Baseline** first, then **stops for human review** before PKS/landscape work (the human-review gate). The study stays **read-only current-state archaeology**: no implementation changes, no target architecture, no kernel/bounded-context/aggregate decisions, no technology decisions. All outputs **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED** — nothing frozen. Full traceability: `.claude/sessions/2026-08-21.md` (FRESH-SESSION entry 2026-08-21 · AMENDMENTS 2–6).

## ⛔ Purpose of THIS plan (what it is / is not)

**What it is:** a governed execution plan for a **read-only current-state architecture study** of the existing knowledge-system ecosystem — **before** any KnowledgeOS kernel, bounded-context, or aggregate decision is made.

**What it is NOT:** it does **not** design the KnowledgeOS kernel. It does **not** validate proposed KnowledgeOS bounded contexts. It does **not** adopt any architecture. It is a **plan**, not the study — a later architecture session executes it.

**First objective (verbatim, binding):** *"Understand the existing knowledge-system landscape before deciding what KnowledgeOS should be."*

**All study outputs are declared in the plan as:** PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED. Nothing frozen. No implementation changes permitted during the reconstruction stages.

---

## 1. Context

The KnowledgeOS review set (00–07, `docs/knowledgeos/architecture/`) consolidates *proposed* KnowledgeOS architecture — hypotheses, not current architecture. The EKS baselines and the two kernel-extraction analyses reveal that EKS is a **governance-first, Git-backed YAML/Markdown knowledge-and-assurance ecosystem** with an implemented observation/recommendation runtime — a far richer current state than a "Git + Markdown" reading. PKS exists as a related but separately-governed system; the AI Engineering Platform (AIP) has an accepted/frozen baseline. The correct architectural dependency order is:

```
EKS baseline → PKS baseline → EKS↔PKS landscape → human review
→ KnowledgeOS proposed model (Review Set) → comparison
→ integration/evolution options → DDD validation → architecture decision
```

The prior session (2026-08-21) established this as **LANDSCAPE-FIRST** (AMENDMENT 3) and bound the current-architecture evidence files (AMENDMENT 4), the convergence/kernel refinements (AMENDMENT 5), and the final kernel-candidate promotion rule (AMENDMENT 6). **This plan's job: make those amendments executable — precisely enough that a later architecture session can execute the study without reinterpreting architectural intent.**

---

## 2. Binding architectural context (canonical source of truth)

The following are **binding inputs**. Do NOT reinterpret or silently weaken them. Read before executing:
- `.claude/CONTEXT.md` — rows: MANDATORY FRAMEWORK (AMENDMENT 2) · LANDSCAPE-FIRST (AMENDMENT 3) · CURRENT-ARCHITECTURE FILES (AMENDMENT 4) · CONVERGENCE & KERNEL REFINEMENTS (AMENDMENT 5) · FINAL REFINEMENT (AMENDMENT 6) · NEXT (re-scoped).
- `.claude/sessions/2026-08-21.md` — AMENDMENTS 2–6 (full text).
- `docs/plans/20260821-1749-knowledgeos-brainstorming-sort-and-final-architecture-plan.md` — the executed predecessor plan (brainstorming sort + Review Set).

### 2.1 LANDSCAPE-FIRST governing sequence (AMENDMENT 3 — binding)

⛔ **STOP TARGET ARCHITECTURE.** Do not begin with the proposed KnowledgeOS kernel, bounded-context validation, or aggregate design.

**Five non-assumptions (ALL binding — hypotheses to be tested, never assumed):**
1. KnowledgeOS replaces EKS.
2. KnowledgeOS replaces PKS.
3. KnowledgeOS merges EKS and PKS.
4. KnowledgeOS is a super-system containing EKS and PKS.
5. KnowledgeOS is a kernel above EKS and PKS.

**Governing sequence (execute in this order):**
`EKS baseline → PKS baseline → EKS↔PKS landscape → human review → KnowledgeOS proposed model (Review Set) → comparison → integration/evolution options → DDD validation → architecture decision`.

### 2.2 Mandatory framework (AMENDMENT 2 — binding, 12 principles)

1. **CURRENT before PROPOSED.** 2. **Evidence hierarchy:** code > tests > persistence > ADRs > docs > C4 > brainstorming; conflicts classified, never silently reconciled. 3. **C4 containers ≠ bounded contexts.** 4. **Reconstruct behavior, not components.** 5. **Reconstruct aggregates from invariants.** 6. **Preserve existing architecture by default.** 7. **Explicit CURRENT / PROPOSED / DELTA / UNKNOWN.** 8. **A–G reality classification** (A implemented · B implicit · C partial · D wrong-boundary · E missing · F contradicted · G hypothesis). 9. **Reverse mapping** (proposed → existing → evidence → A–G → evolution). 10. ⛔ **evolution vs rewrite — rewrite is NOT an implicit consequence; requires an explicit human architectural decision.** 11. **Kernel = empirical test; Linux/Unix analogy NEVER evidence.** 12. **No implementation changes during reconstruction — read/write documentation only.**

### 2.3 Current-architecture files (AMENDMENT 4 — binding evidence)

Four files supplied by the Human Principal Architect; their analysis is binding content:

| File | Status in this study |
|---|---|
| `docs/knowledgeos/brainstorming/20260801_1231_EKS Current Architecture Baseline.md` | **Candidate draft for `KOS-ARCH-BASELINE-001`** (older, richer — reveals the implemented observation/recommendation runtime). Apply two archaeology-hardening corrections: `Delegation modelling` → "OBSERVED BEHAVIOUR — SEMANTIC INTERPRETATION UNKNOWN" · `Rule governance` → "PARTIAL / semantic Rule model not established". Keep the UNKNOWN bounded-context position + reconstruction-candidate status. |
| `docs/knowledgeos/brainstorming/20260821_2033_what_eks_today.md` | Supporting EKS baseline (carries the archaeology-hardening recommendation). |
| `docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md` | Supporting current-state baseline. PKS/EKS identity ambiguity left UNRESOLVED → feeds Stage 2. CURRENT C4 = UNKNOWN/CONTESTED. EKS-07 coordination weakness preserved (observed issue, not silently redesigned). |
| `docs/knowledgeos/brainstorming/20260821_2032_how_to_change_eks_into_kowledge_os_kernel.md` | **Kernel-extraction analysis = Stage 4/5 material ONLY, NOT current EKS architecture.** EKS = first domain implementation on kernel primitives — **never "EKS becomes the kernel."** |

**Established evidence conclusions (from AMENDMENT 4, binding):**
- EKS = governance-first, Git-backed YAML/Markdown knowledge-and-assurance ecosystem; process-oriented; **conceptual model more mature than executable boundaries**.
- *"The mechanism records authority; it does not grant authority."*
- Governance most executable (Grant · Work Item · Session · Mutation Owner · Handoff · START · Review · Acceptance).
- Bounded contexts **NOT established** (candidates only).
- Work-Item record = clearest consistency boundary (IMPLICIT).
- Authority recorded but **NOT temporally reconstructable**.
- **Numeric (measured):** full `.claude/runtime/workflow/` estate = 18 work items · 126 grants · 218 transitions. The baseline's "9 work items · 20 grants **in the examined production record**" = an exact scoped subset (no contradiction once scope is explicit). Verified: 20/20 humanActRef · 20/20 registeredBy=governance · 0/20 validity · 0/20 delegation · 0/20 ownership. ⚠️ **"13/20 immutable · 7/20 descriptive" split NOT reproduced** (measures 3/17 or 16/4 under two defined rules) → **flagged for independent verification** in Stage 1.

### 2.4 Convergence & kernel refinements (AMENDMENT 5 — binding)

Read also (revised kernel-extraction analysis): `docs/knowledgeos/brainstorming/20260821_2108_how_to_intigrate_current_eks_into_knowledge_os_kernel.md` — **quarantine, Stage 4/5 material only, NEVER current EKS architecture.** Its content: EKS = three subsystems (Engineering Knowledge · Observation Runtime · Engineering Governance); `ObservationTrigger → ChangeSet → ObservationRuntime → Collectors → Recommendations → Presentation` runtime; ChangeSet = strong kernel abstraction; recommendation engine NOT wholesale kernel; AIP = consumer/runtime of kernel, not a component; `.claude` must NOT become the kernel; PKS study indispensable ("what do EKS and PKS share, independent of their domain semantics?"); four maps (A EKS · B PKS · C KOS proposed target-only · D Convergence matrix); three loops (Observation/Knowledge/Governance); **"do not extract the kernel from EKS alone."**

**Two refinements adopted (binding):**
1. **Architecture Reconciliation Ledger** — a REQUIRED **Stage-5** artifact (§7). ⛔ Stage-5 output only, UNKNOWN preserved, NEVER a silent-reconciliation device.
2. **"Do not generalize from EKS alone"** as a hard rule — a kernel candidate requires **cross-system evidence**: domain-neutral · invariant-bearing · reusable · independently owned · stable across knowledge domains. Blocks `EKS → rename → KnowledgeOS kernel`.

**Flagged extension (adopted):** **AIP added as a third current-ecosystem leg** (EKS · PKS · AIP). AIP leg = **read-only current-state reconstruction** (consistent with its frozen baseline; NO AIP redesign).

### 2.5 Final refinement (AMENDMENT 6 — binding)

⛔ **The stricter kernel-candidate promotion rule (replaces AMENDMENT 5's wording):**

> **A kernel candidate must be demonstrated to be independent of the domain semantics of at least one concrete domain, AND must have evidence of reuse or justified reuse across more than one knowledge domain, before it can be promoted from candidate to kernel decision.**

**Required progression:**
```
EKS capability → candidate primitive
   → cross-system equivalent (does PKS exhibit the same?)
   → same invariant / lifecycle / authority semantics / ownership?
   → reusable abstraction?
   → KERNEL CANDIDATE
   → DDD validation
   → kernel decision
```
Blocks the unsupported `EKS has Authority → "Authority is domain-neutral" → Kernel` leap without a second domain demonstrating the same. Kernel stays **empirically discovered, never architecturally imagined**.

**Plan status:** plan otherwise **final** — no further change. AMENDMENTS 3 + 4 + 5 + 6 are binding content.

---

## 3. Readiness (EP-03, derived — no human gap)

| Domain | Derived answer |
|---|---|
| Business | Understand the existing knowledge-system landscape before deciding what KnowledgeOS should be. No constitutional invariant touched. |
| DDD | No domain model change — read-only current-state archaeology for EKS/PKS/AIP. No bounded context, capability, ownership, or kernel decision made. Deferred to the DDD validation phase that follows this study. |
| Architecture | All baselines/landscape/evolution options are PROPOSED evidence — never authority. This study decides nothing architecturally. |
| Process | Human commission (fresh-session EP-01). Methodology freeze respected — no protocol refinement, no KnowledgeOS governance proposal, no review-model evolution. |
| Impact | Documentation-only: baselines + landscape + ledger + registers. **No code, no tests, no runtime state, no `.claude` config, no technology, no migration design.** |
| Verification | Documentation-only checklist (§10): every claim evidence-tagged · CURRENT/PROPOSED/DELTA/UNKNOWN + A–G · UNKNOWN preserved · contradictions surfaced unresolved · no implementation changes · all outputs marked PROPOSED/NON-AUTHORITATIVE/NOT ADOPTED. |
| Completion | Session log · CONTEXT · plan status · one commit per phase (§8). |

---

## 4. Scope

**In:** EKS baseline (Stage 1) · PKS baseline (Stage 2) · AIP reconstruction (Stage 3, read-only) · Landscape (Stage 4) · KnowledgeOS comparison + Reconciliation Ledger + kernel-candidate register + evolution options (Stage 5) · the 10 study outputs · bookkeeping (session log + CONTEXT + plan status).

**Out (do-not-touch / do-not-decide):**
- ⛔ **No implementation changes** during reconstruction stages — read/write documentation only.
- ⛔ **No migration design, no refactoring proposal, no technology selection, no target decisions.**
- ⛔ **Not decided by this plan:** Rust · Spring Boot · PHP/Laravel rewrite · Gradle/Cargo · Kafka · microservices · event sourcing · PostgreSQL migration · commercial vs open source · IPO/IP strategy · Digitalization Robot · Business Translator · product strategy. Technology comes only after: domain → boundaries → invariants → runtime requirements → architecture → technology.
- ⛔ **AIP:** read-only reconstruction; no redesign, no frozen-baseline reopening, no technology change.
- ⛔ **`.claude` must not become the kernel.**
- ⛔ **No bounded-context/aggregate validation workshop** in this study — deferred (only a *recommendation for the next DDD validation phase* is an output).
- Existing governance artifacts (ADR-T log, PKS Phase I–III, KOS-AIP cluster, Review Set) are **read as evidence, not modified**.

---

## 5. Current systems to study (independently)

1. **EKS — Engineering Knowledge System**
2. **PKS — Product Knowledge System**
3. **AIP — AI Engineering Platform** (READ-ONLY current-state reconstruction)

⛔ **EKS and PKS must each be reconstructed using their OWN ubiquitous language.** Do not assume similarly named concepts mean the same thing. `knowledge` · `product` · `evidence` · `authority` · `work item` · `workflow` · `observation` · `decision` · `artifact` must each be interpreted independently in each system **before** comparison.

### 5.1 EKS evidence inputs (binding)

- `docs/knowledgeos/brainstorming/20260801_1231_EKS Current Architecture Baseline.md` (draft KOS-ARCH-BASELINE-001)
- `docs/knowledgeos/brainstorming/20260821_2033_what_eks_today.md`
- `docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md`
- Runtime evidence: `.claude/runtime/workflow/*.json` (measured: 18 work items · 126 grants · 218 transitions; scoped subset: 9 work items · 20 grants)
- Archaeology corrections (§2.3): Delegation → OBSERVED BEHAVIOUR—SEMANTIC INTERPRETATION UNKNOWN · Rule governance → PARTIAL/semantic Rule model not established · Bounded contexts → NOT ESTABLISHED · CURRENT C4 → UNKNOWN/CONTESTED · EKS-07 → preserved observed weakness.

**Distinguish implemented runtime from conceptual model.** Do NOT describe conceptual architecture as implemented architecture.

### 5.2 PKS evidence inputs (binding)

- `docs/implementation/PKS_Phase_II_Methodology_Baseline_v1_2.md` (SDM v1.2 / EOP v1.2 — the currently-governed PKS baseline)
- `docs/implementation/PKS_Phase_II_M0_M1_..._M8_*.md` (the M-series strategic-modeling artifacts)
- `docs/implementation/PKS_Phase_IIB_AD1_Architecture_Definition_Review.md` · `docs/implementation/PKS_Phase_IIC_C4_Representation_Verification.md` · `docs/implementation/PKS_Phase_III_EAD_1_Ecosystem_Architecture_Discovery.md`
- `docs/implementation/Strategic_DDD_Discovery_Product_Knowledge_System_Converged_Review_Synthesis.md` + Phase I package + `PKS_Phase_I_ARB_Rulings.md`
- `docs/adr/PKS_ADR_001_*` · `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` · `docs/plans/20260802-0015-pks-identifier-validation-capability-plan.md`

### 5.3 AIP evidence inputs (binding — read-only)

- `.claude/plans/AIP-iteration-1-construction.md` (AIP-14 iteration-close protocol · the frozen platform baseline)
- `docs/knowledgeos/architecture/KOS-AIP04-DISCOVERY-001-capability-architecture-analysis.md` + `2026-08-18-KOS-AIP04-DISCOVERY-001-adr-aip-04-capability-discovery-proposal.md`
- `docs/knowledgeos/reviews/2026-08-19-six-role-operating-model-adoption.md` (six-role model ADOPTED — Governance · Architecture · Implementation · Verification · Knowledge · Communication; roles ≠ bounded contexts, ≠ capabilities, ≠ agents, ≠ platform services, ≠ org positions)
- `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-{ADR,IMPLEMENTATION-DESIGN,MIGRATION-PLAN}.md` (+ AMD4/5/6)
- `docs/knowledgeos/brainstorming/20260817-145153-ai-engineering-platform-6-role-model.md`

### 5.4 Kernel quarantine (NEVER current EKS architecture)

- `docs/knowledgeos/brainstorming/20260821_2032_how_to_change_eks_into_kowledge_os_kernel.md`
- `docs/knowledgeos/brainstorming/20260821_2108_how_to_intigrate_current_eks_into_knowledge_os_kernel.md`

Used **only later in the evolution/convergence analysis (Stage 5).** They must NEVER be used as evidence that EKS already has a KnowledgeOS kernel. Core hypothesis carried forward (as hypothesis, not fact): **"Do not extract the kernel from EKS alone."** ChangeSet · ObservationRuntime · evidence mechanisms · governance mechanisms = **candidate abstractions only**.

### 5.5 KnowledgeOS Review Set (PROPOSED hypothesis material — Stage 5 only)

- `docs/knowledgeos/architecture/00…07-KnowledgeOS-*.md` — used ONLY as target/proposal material in Stage 5 (Map C). **Never treated as current architecture.**

---

## 6. Core architectural rule

⛔ **Do NOT perform: `EKS capability → rename → KnowledgeOS kernel`** — that is an EKS rewrite under a new name.

A capability can become a kernel candidate ONLY when evidence demonstrates (AMENDMENT 6 rule, §2.5): independence from domain semantics of ≥1 concrete domain · evidence of reuse OR justified reuse across >1 knowledge domain · invariant-bearing behaviour · independent ownership · stable lifecycle · reusable abstraction.

The kernel must be **empirically discovered, never architecturally imagined.**

---

## 7. Required study stages (the executable plan)

### STAGE 1 — EKS CURRENT ARCHITECTURE BASELINE

Reconstruct EKS as it exists today. Output = a **baseline document** (20 sections) describing: purpose · capabilities · runtime · persistence · artifacts · workflows · governance · authority · evidence · observations · assurance · integrations · dependencies · execution mechanisms · current architectural boundaries · known gaps · contradictions · UNKNOWN areas.

**Required 20 sections:**
1. Executive Summary · 2. System Purpose (demonstrated / documented / inferred) · 3. Current System Shape · 4. Business/Engineering Capabilities · 5. Actors and Users (Governance · human engineering participants · AI agents · external systems) · 6. Ubiquitous Language (EKS's own) · 7. Domain Model (entities, aggregates, services, state machines, events — with A–G reality classification) · 8. Aggregate/Consistency Boundaries (Work Item / Workflow Record = clearest, IMPLICIT) · 9. Bounded Context Candidates (NOT ESTABLISHED — candidates only) · 10. Application/Workflow Architecture (Grant · Work Item · Session · Mutation Owner · Handoff · START · Review · Acceptance) · 11. Persistence Architecture (Git-backed; authority recorded but NOT temporally reconstructable) · 12. Knowledge Storage and Projection · 13. Integration Architecture · 14. Runtime/Execution Architecture (ObservationTrigger → ChangeSet → ObservationRuntime → Collectors → Recommendation → Presentation — only where IMPLEMENTED evidence supports; else mark C-partial/B-implicit) · 15. AI-Agent Interaction · 16. Governance Architecture · 17. Authority and Evidence Mechanisms · 18. Observation/Assurance Mechanisms · 19. Known Gaps and Contradictions (incl. EKS-07; the archaeology corrections; the NOT-reproduced 13/7 split flagged for independent verification) · 20. UNKNOWN Areas.

**Stage-1 rules (binding):** No KnowledgeOS mapping. No migration proposal. No refactoring proposal. No target architecture. No kernel extraction. Implemented runtime distinguished from conceptual model.

**Evidence hierarchy:** code/tests (`.claude/runtime/workflow/*.json`, scripts) > ADRs > baseline docs > C4 > brainstorming. Each claim tagged CURRENT/PROPOSED/DELTA/UNKNOWN + A–G.

### STAGE 2 — PKS CURRENT ARCHITECTURE BASELINE

Same archaeology discipline for PKS. **Do not force EKS terminology onto PKS.** Output = a **baseline document** describing: PKS ubiquitous language · domain concepts · artifacts · workflows · ownership · authority · persistence · integrations · consistency mechanisms · governance · evidence · runtime · dependencies · current boundaries · gaps · UNKNOWN areas.

**Stage-2 rules (binding):** No KnowledgeOS mapping. No migration proposal. No kernel extraction. Same evidence hierarchy. The EKS/PKS identity ambiguity (AMENDMENT 4) is resolved here **from PKS evidence** — never from EKS's meaning.

### STAGE 3 — AIP CURRENT ARCHITECTURE RECONSTRUCTION

**Read-only.** Use the accepted/frozen AIP baseline (§5.3). Determine (a) what AIP actually owns · (b) what it consumes from EKS · (c) what it produces · (d) what it does NOT own · (e) which responsibilities are platform concerns vs knowledge concerns. Output = a **reconstruction note** (not a redesign). If the baseline is insufficient for a deep reconstruction, record the UNKNOWN — do not invent.

**Stage-3 rules (binding):** No AIP redesign. No frozen-baseline reopening. No technology change.

### STAGE 4 — EKS ↔ PKS ↔ AIP LANDSCAPE (only after the baselines are reviewed)

Compare concepts · terminology · ownership · authority · lifecycle · invariants · persistence · dependencies · integration mechanisms · evidence · workflows · consistency boundaries · duplicate knowledge · conflicting knowledge · shared mechanisms · genuinely different domain semantics.

**Do NOT assume equivalence merely because names are similar.** Classify every relationship explicitly:
`SAME · RELATED · OVERLAPPING · DUPLICATE · COMPLEMENTARY · CONFLICTING · DISTINCT · UNKNOWN`.

Output = a **landscape/relationship analysis** with the relationship classification register.

**⛔ Human review gate:** the landscape is reviewed by the Human Principal Architect **before** Stage 5 begins. Stage 5 proceeds only on explicit human authorization.

### STAGE 5 — INTRODUCE KNOWLEDGEOS PROPOSAL + RECONCILIATION LEDGER

Only after the current ecosystem is understood and the landscape is human-reviewed. Use the KnowledgeOS Review Set as PROPOSED/NON-AUTHORITATIVE hypothesis material (§5.5). Compare:
```
CURRENT EKS · CURRENT PKS · CURRENT AIP  →  PROPOSED KNOWLEDGEOS
```
Identify (per concept): what already exists · what is missing · what is duplicated · what should potentially remain separate · what might be generalized · what might be extracted · what might be integrated · what might be replaced · what might be newly introduced.

**Stage-5 outputs (the 10 study outputs, §8):**
1. EKS Current Architecture Baseline
2. PKS Current Architecture Baseline
3. AIP Current Architecture Reconstruction
4. EKS–PKS–AIP Landscape / Relationship Analysis
5. **Architecture Reconciliation Ledger** (required artifact, spec §7.1)
6. KnowledgeOS Current-vs-Proposed Delta Analysis
7. Kernel Candidate Register (§7.2)
8. Integration / Evolution Options (evolution classifications §7.3)
9. Open Questions and Contradictions
10. Recommendation for the next DDD validation phase

**Stage-5 rules (binding):** The Reconciliation Ledger is a **Stage-5-only** artifact. **UNKNOWN must remain UNKNOWN** — never fill an unknown merely to make the architecture coherent. No kernel decision in this study unless the evidence genuinely warrants one (and even then: KERNEL CANDIDATE / OPEN / NOT KERNEL classification only — the *decision* is the human's, after DDD validation).

#### 7.1 Architecture Reconciliation Ledger (spec)

For every significant KnowledgeOS concept/capability, one row with columns:

| Column |
|---|
| Concept |
| EKS evidence |
| PKS evidence |
| AIP evidence (where relevant) |
| KnowledgeOS proposal |
| Same language? |
| Same invariant? |
| Same ownership? |
| Same lifecycle? |
| Same authority? |
| Same consistency requirement? |
| Persistence |
| Dependency |
| Status |
| Evolution option |
| Kernel candidate? |
| Evidence |
| UNKNOWN |

Evolution classifications (mandatory vocabulary): `PRESERVE · EVOLVE · EXTRACT · SEPARATE · REPLACE · INTRODUCE · LEAVE IN EXISTING SYSTEM · RECONCILE · UNKNOWN`.

#### 7.2 Kernel Candidate Register (spec)

For every candidate (from the ledger rows marked kernel-candidate-relevant), apply the **eight gate questions**:
1. Is it domain-independent?
2. Is it invariant-bearing?
3. Does it have independent ownership?
4. Does it have a stable lifecycle?
5. Does it have evidence of reuse or justified reuse across >1 knowledge domain?
6. Does the same concept exist in PKS or another independent knowledge domain?
7. Can it be separated from domain-specific rules?
8. Is the abstraction stable enough to become infrastructure?

Then the **AMENDMENT 6 promotion rule** (§2.5) is applied **before** any promotion to a kernel decision. Classify: `NOT KERNEL · KERNEL CANDIDATE · OPEN`. **No kernel decision is made in this study** unless the evidence genuinely warrants one — and the decision itself remains the human's after DDD validation.

#### 7.3 Integration / Evolution Options

Derived from the ledger's evolution column — options expressed as architectural *options*, never decisions. Each option states: what would be preserved/evolved/extracted/separated/replaced/introduced · what evidence justifies it · what would need human architectural decision. **Rewrite is never an implicit option — it requires an explicit human architectural decision.**

### Three-loop hypothesis (investigated, NOT adopted)

Investigate whether the ecosystem exhibits three useful architectural dimensions — **Observation Loop** (engineering activity → observation → evidence → assessment → recommendation) · **Knowledge Loop** (evidence → knowledge → validation → governed knowledge) · **Governance Loop** (proposal → authority → decision → outcome → effectiveness evidence). Determine whether these are: genuinely shared domain mechanisms · EKS-specific · PKS-specific · AIP/platform mechanisms · or merely a useful analytical model. Record the finding as hypothesis/evidence; **adopt nothing.**

### DDD discipline

Do NOT derive bounded contexts from: folders · C4 containers · repositories · deployment units · existing services · database schemas · team structure alone. Use: ubiquitous language · ownership · invariants · lifecycle · authority · consistency · transaction boundaries · dependency direction. **But defer the formal bounded-context/aggregate validation workshop** until after the landscape and convergence study.

---

## 8. Plan execution phases (one commit per phase)

**P1 — Stage 1 (EKS baseline).** Read all EKS inputs + runtime evidence (§5.1); execute the archaeology corrections; produce the 20-section baseline. Commit: `docs(knowledgeos): Stage 1 — EKS Current Architecture Baseline (PROPOSED, evidence-based, no target architecture)`.

**P2 — Stage 2 (PKS baseline).** Read all PKS inputs (§5.2); produce the PKS baseline in PKS's own language. Commit: `docs(knowledgeos): Stage 2 — PKS Current Architecture Baseline (PROPOSED, evidence-based)`.

**P3 — Stage 3 (AIP reconstruction).** Read-only reconstruction from the frozen baseline (§5.3). Commit: `docs(knowledgeos): Stage 3 — AIP Current Architecture Reconstruction (READ-ONLY, PROPOSED)`.

**P4 — Stage 4 (Landscape).** Relationship analysis + classification register (§7 Stage 4). ⛔ **Human review gate — STOP for Human Principal Architect review.** Commit after review: `docs(knowledgeos): Stage 4 — EKS–PKS–AIP Landscape / Relationship Analysis (PROPOSED)`.

**P5 — Stage 5 (Reconciliation + evolution).** Reconciliation Ledger · Delta Analysis · Kernel Candidate Register · Evolution Options · Open Questions · DDD-validation recommendation (§7.1–7.3). Commit: `docs(knowledgeos): Stage 5 — Reconciliation Ledger, kernel-candidate register, evolution options (PROPOSED, nothing adopted)`.

**P6 — Bookkeeping.** Canonical plan → `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` · session log `.claude/sessions/YYYY-MM-DD.md` · `.claude/CONTEXT.md` NEXT → study outputs await Human Principal Architect review / architecture decision. Commit: `docs(knowledgeos): EP-01 study bookkeeping — baselines + landscape + reconciliation delivered (PROPOSED, NOT ADOPTED)`.

---

## 9. Verification (documentation-only Definition of Done)

1. **Every major claim is evidence-tagged** — CURRENT/PROPOSED/DELTA/UNKNOWN + A–G (§2.2).
2. **UNKNOWN preserved** — no cell in the ledger filled to make the architecture coherent.
3. **Contradictions surfaced, unresolved** — every contradiction is an explicit finding, never silently reconciled.
4. **No implementation changes** — `git status` shows only study docs + bookkeeping; no code/test/runtime/`.claude` config touched.
5. **Implemented vs conceptual distinguished** — no conceptual architecture described as implemented.
6. **No target-architecture assumptions** — the five non-assumptions (§2.1) respected; kernel quarantine honored (§5.4).
7. **AIP read-only** — no redesign, no frozen-baseline reopening.
8. **All outputs marked PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED.**
9. **Review Set never used as current architecture** — only as Stage-5 hypothesis material.
10. **Every original file accounted for; no source content lost; no renaming of existing artifacts.**

---

## 10. Risks / boundaries

| | Risk | Mitigation |
|---|---|---|
| 🔴 | Plan reads as authority / freezes architecture | All outputs PROPOSED · NOT ADOPTED; nothing frozen; explicit non-assumptions; kernel decision deferred to human after DDD validation. |
| 🔴 | Silent reconciliation of contradictions | A–G + explicit-finding discipline; UNKNOWN preserved; Reconciliation Ledger is Stage-5-only, never a silent-reconciliation device. |
| 🔴 | EKS-as-kernel leap | AMENDMENT 6 promotion rule + the eight gate questions; "do not generalize from EKS alone"; quarantine honored. |
| 🔴 | Method-freeze violation | Scope locked to read-only archaeology + a plan; no protocol refinement, no governance proposal. |
| 🔴 | AIP redesign | Read-only Stage 3; frozen baseline not reopened. |
| ⚠️ | Technology/product decisions creep in | Explicit exclusion list (§4); technology comes only after domain → boundaries → invariants → runtime → architecture. |
| ⚠️ | Concept-equivalence assumption | Each system reconstructed in its own ubiquitous language before comparison; SAME/RELATED/…/UNKNOWN classification. |
| ⚠️ | The NOT-reproduced 13/7 split | Flagged for independent verification in Stage 1; never forced to fit. |

---

## 11. Open questions / determinations

None blocking — AMENDMENTS 3–6 are the determinations. Flagged for the executing session (resolved in-plan): (a) EKS/PKS identity ambiguity → resolved from PKS evidence in Stage 2, never from EKS's meaning; (b) the 13/7 immutable/descriptive split → independent verification in Stage 1; (c) three-loop status → investigated, not adopted; (d) AIP depth → evidence-gated, UNKNOWN when insufficient; (e) kernel decision → deferred to human after DDD validation.

---

## 12. Traceability

Fresh-session commission 2026-08-21 (draft the EP-01 plan for the EKS + PKS (+ AIP) study) · **binding content: AMENDMENTS 3, 4, 5, 6** (`.claude/CONTEXT.md` rows 17–22 · `.claude/sessions/2026-08-21.md` full text) · AMENDMENT 2 mandatory framework · LANDSCAPE-FIRST governing sequence · the five non-assumptions · kernel-candidate promotion rule (AMENDMENT 6) · the four current-architecture files (AMENDMENT 4) · the revised kernel-extraction analysis `20260821_2108_...` (AMENDMENT 5) · predecessor plan `docs/plans/20260821-1749-knowledgeos-brainstorming-sort-and-final-architecture-plan.md` (executed) · naming convention ES-004.2 (`docs/plans/YYYYMMDD-HHMM-<topic>-plan.md`) · evidence classification A–G (AMENDMENT 2 principle 8).
