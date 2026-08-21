# EKS Current Architecture Baseline — Stage 1 (Architecture Archaeology)

> **STATUS: PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED.** Nothing here is frozen, adopted, or ratified. This document **describes what EKS is today**; it proposes no target architecture, no migration, no refactoring, no technology, no kernel, and no bounded context.
>
> ✅ **CORPUS COMPLETED — 2026-08-22 · R-1 of the approved EP-01 study** (launch instrument: `docs/knowledgeos/reviews/2026-08-22-KOS-EP01-R1-eks-corpus-completion-launch-prompt.md`, commit `a9cf7936`). **§1–§20 are preserved verbatim** — the git diff shows additions/errata only, no silent rewrite. The previously-missing terminal sections are supplied **in place**: **§21** (special questions, cited at §7.6c · §11.5 · §16.2 · §16.3 · §16.6 · §17.2) · **§22** (Known Gaps and Open Areas) · **§23** (P-rows, cited at §5.1) · **§24** (UNKNOWN register **U-***, cited at §11.4) · **§25** (contradiction register **X-***, cited at §25.1 · §25.3). One smallest-scope erratum was applied at the affected site — **E-1** (§8, §25.2): the fully-read corpus (`KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` §1.2) contradicts the claim that a lost transition is *mechanically detectable as a gap* — **density is NOT a completeness proof** (P3-F1 lesson). The corpus was re-read exhaustively (P3-F1 lesson) and the §1–§20 measurements were re-verified by direct re-run in this session. This banner records the corpus completion (this commit); it is **not a repair of content**, and it is **not a substitute for Human Principal Architect review** — the baseline remains **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED** until the HPA re-reviews.
>
> **Deliverable:** Stage 1 / phase P1 of the approved EP-01 plan `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md`.
> **Identifier:** ⛔ **This document does NOT claim the identifier `KOS-ARCH-BASELINE-001`.** It is a *candidate input* to that baseline. Per PMR-10 the identifier is not minted here; `identifier-check.php --audit` returns INCONCLUSIVE for that series (no governed register exists — gap **G-1**, §17.4).
> **Placement:** derived, not chosen — `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0). Filed alongside the sibling supporting baseline in `architecture/`.
> **Scope:** EKS only. ⛔ **No PKS study · no EKS↔PKS comparison · no landscape · no KnowledgeOS design · no kernel extraction.** The session STOPS after this document, awaiting Human Principal Architect review.

**Method:** evidence-first archaeology. Every substantive claim carries an evidence tier (1–8) and a reality class (A–G). Measurements in this document were **executed in this session** against the working tree, not copied from prior documents. Where a prior document and a direct measurement disagree, **both are recorded** and neither is silently reconciled.

---

## 0. How to read this document

### 0.1 Evidence hierarchy (tier 1 = strongest)

| Tier | Evidence |
|---|---|
| **1** | implementation / source code |
| **2** | executable tests (re-run in this session) |
| **3** | persisted / runtime records (measured in this session) |
| **4** | accepted ADRs / governance decisions |
| **5** | architecture documentation |
| **6** | C4 / PUML |
| **7** | brainstorming |
| **8** | historical conversation / context |

### 0.2 Reality classification (mandatory, AMENDMENT 2 principle 8)

| Class | Meaning |
|---|---|
| **A** | IMPLEMENTED — running code and/or persisted records demonstrate it |
| **B** | IMPLEMENTED BUT IMPLICIT — the behaviour exists; the concept is not named or modelled anywhere |
| **C** | PARTIAL — some of it exists; the rest does not |
| **D** | WRONG-BOUNDARY / ARCHITECTURAL DEBT — it exists, and its placement or shape is a defect |
| **E** | MISSING — the concept is referenced but nothing implements it |
| **F** | CONTRADICTED — sources conflict and the conflict is unresolved |
| **G** | HYPOTHESIS / DOCUMENTED ONLY — documentation describes it; no implementation evidence |

⛔ **No B, C, or G finding in this document has been promoted to A.** Documentation describing something is never treated as evidence that it is implemented.

### 0.3 KnowledgeOS firewall

The proposed KnowledgeOS architecture (Review Set `00…07`, the kernel-extraction analyses, the Digitalization Robot, the proposed Knowledge Governance / Evidence / Execution / Product contexts, the proposed kernel) is **quarantined**. It appears in this document **only** in the form: *"Not established by current EKS evidence."* No EKS finding is mapped into any proposed context.

---

## 1. Executive Summary

**EKS, as it exists on 2026-08-21, is two loosely-joined subsystems and a large governed document corpus, all living inside the PublicDigit product repository, executed by human-and-AI *processes* rather than by a server.**

The single most important structural finding:

> **EKS has no runtime. It has mechanisms.**
>
> There is no EKS service, daemon, scheduler, queue, database, or deployment unit. Every EKS behaviour is a command-line program that a human or an AI process chooses to invoke, plus a set of files in the repository. Nothing in EKS runs unless something outside EKS runs it.

The second most important finding:

> **The governance mechanism *records* authority; it does not *grant* it, and it cannot *enforce* it.**
>
> This is not a criticism — it is the implemented design, stated verbatim in the source: *"An illegal write is a REFUSED transition (exit 65, nothing appended) — the fold refuses; nothing is physically prevented"* (`.claude/scripts/workflow-state.php:11–13`, tier 1). Authority is a recorded reference to a human act. The record refuses malformed *writes to itself*; it prevents no engineering action whatsoever.

The third finding is an asymmetry that no prior document states:

> **The two EKS subsystems have inverse operational vitality, and the more valuable one is the less durable one.**
>
> | Subsystem | Last activity (measured) | Persistence | Executable assurance | CI gate |
> |---|---|---|---|---|
> | **Governance / Workflow** | **2026-08-21** (today) | 🔴 **gitignored** | 29 tests ✅ | none |
> | **Observation / Assurance** | **2026-08-04** (17 days dormant) | ✅ git-tracked | 43 + 252 tests ✅ | 1, non-blocking |
>
> The subsystem that carries authority, ownership, and lineage for every governed engineering act writes to an untracked local directory. The subsystem that carries advisory code metrics writes to git.

**What is genuinely strong (class A, tier 1–3):** an append-only, fold-derived work-item state record with eleven machine-enforced invariants pinned by 29 passing contract tests; a read-only session resolver that structurally refuses to interpret records itself and delegates to the qualified mechanism; a hexagonal, four-capability deterministic document-assurance library with 252 passing tests; a trigger→ChangeSet→runtime→collector→recommendation→decision→outcome→assessment chain that is fully implemented and has closed end-to-end exactly once.

**What is genuinely weak:** authority is prose (grant `scope` has a median length of 2,337 characters and is compared **by string equality**, making the `authorized` query effectively unanswerable in practice); the transition log has no time dimension (2 of 218 transitions carry any date field), so authority is not temporally reconstructable; the work-item state machine has **no terminal state** despite 34 recorded `COMPLETE` transitions; `workflow` is a free-text label with 14 distinct values across 18 records and no definition registry; and the governance standards that supposedly govern all of this (ES-001…ES-006) are all still **PROPOSED**.

**What is not established:** EKS's bounded contexts (candidates only), its aggregates (one implicit candidate), its current C4 container model (UNKNOWN/CONTESTED), its identity boundary against PKS (UNRESOLVED — deliberately deferred to Stage 2), and its persistence architecture (F — CONTRADICTED, §11.4).

**Answer to the joining-architect test** *(could a Principal Architect who has never seen EKS understand how it actually works from this document alone?)* — **Yes for the mechanisms, No for the domain.** Sections 8–11 and 15–16 describe operable machinery precisely enough to run and extend it. Sections 6–7 and 9 show that the *domain* those mechanisms serve has not been modelled: the language is rich and inconsistent, ownership of most invariants is unassigned, and the boundaries are candidates. **EKS's conceptual model is more mature than its executable boundaries, and its executable mechanisms are more mature than its domain model.** Both statements are true simultaneously, and that is the honest shape of the system.

---

## 2. System Purpose

### 2.1 Demonstrated purpose — tier 1–3 · class **A**

What EKS demonstrably does, measured from records and running code:

1. **Records who authorized what, by reference to a human act.** 126 grants across 18 work items; **126/126 carry a `humanActRef`**; **126/126 carry `registeredBy: governance`** (measured this session).
2. **Records who may mutate a work item, and refuses to let anyone claim it.** 218 transitions; ownership moves only by `START` after a token-carrying `HANDOFF`; `CLAIM_OWNERSHIP` is not an edge in the machine (`workflow-state.php:279–281`).
3. **Answers "which governed assignment does this execution host hold?" without granting anything.** `session-resolve.php` — verdicts `RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE`, all exit 0.
4. **Deterministically assesses documents and code.** 4 capabilities (Identifier · Reference · Vocabulary · Cohesion Integrity) + 2 collectors (LCOM4 · test-presence), 324 passing tests total.
5. **Converts observations into advisory recommendations, and records the human's decision, the outcome, and an assessment of whether the recommendation helped.** The full chain exists in code and has produced 10 recommendations → 2 decision rows → 1 outcome → 1 assessment.
6. **Injects governed knowledge into an AI process at session start.** `inject-context.sh` (SessionStart hook).
7. **Persists a governed engineering corpus.** 199 markdown files / 59,596 lines under `docs/knowledgeos/`; 132 / 31,136 under `docs/knowledge/`; 172 / 21,468 under `engineering/`; 37 / 27,085 session logs.

### 2.2 Documented purpose — tier 5 · class **A** (the documents exist and say this)

`engineering/governance/ES-006-Engineering-Knowledge-Governance.md`: *"how ENGINEERING knowledge is harvested, promoted, and retired — the lifecycle of the platform's own learning."*

`engineering/architecture/c4/Engineering_Knowledge_System_Reference_Model.md` §1: *"the single explanatory map: what exists · what governs what · what state everything is in · where information lives · which pattern to follow when adding to it."*

### 2.3 Inferred purpose — class **B**

The purpose that the mechanisms collectively serve, which **no document states as EKS's purpose**:

> **To make engineering work on a multi-actor (human + AI) codebase *adjudicable after the fact* — so that any past engineering act can be traced to the authority that permitted it and the evidence that justified it.**

This is class **B (implemented but implicit)**: every mechanism serves it, and no artifact names it as the purpose. It is stated here as an inference, explicitly labelled, not as a finding.

### 2.4 Purpose confidence

| Statement | Class | Confidence |
|---|---|---|
| EKS records authority and lineage for governed engineering work | **A** | high — measured |
| EKS produces deterministic engineering assessments | **A** | high — 324 tests re-run green |
| EKS governs a durable engineering knowledge corpus | **A** | high — measured |
| EKS *enforces* anything | **E — MISSING** | high — no lock, hook, lease, or gate exists (§16.2) |
| EKS is a knowledge *platform* | **G** | documented only; no platform runtime exists (§19) |

---

## 3. Current System Shape

### 3.1 The physical shape — tier 1 · class **A**

```text
ONE git repository  ·  ONE composer autoload  ·  ONE PHP runtime  ·  NO server
└── nrna1/                                    (the PublicDigit product repository)
    ├── app/                    PRODUCT (Laravel) ......................... not EKS
    ├── .claude/                                        AI-process operating surface
    │   ├── CLAUDE.md  MEMORY.md  CONTEXT.md            governed knowledge (injected)
    │   ├── platform/registry.yaml                      component/asset registry (16 assets)
    │   ├── scripts/            13 files ─ workflow-state.php · session-resolve.php
    │   │                                   + 11 bash hooks
    │   ├── sessions/           37 append-only session logs        (git-tracked)
    │   └── runtime/workflow/   18 work-item JSON records     🔴 GITIGNORED
    ├── scripts/
    │   ├── observations/       ObservationTrigger→ChangeSet→Runtime→Collectors
    │   │                       →RecommendationEngine→Inbox→Decision→Outcome→Assessment
    │   ├── metrics/            pdepend snapshot + trend
    │   ├── lib/EngineeringKnowledge/   4 capabilities, hexagonal, 252 tests
    │   ├── knowledge-lint.php  identifier-check.php  link-check.php  doc-placement.php
    │   └── knowledge-graph.php
    ├── engineering/
    │   ├── governance/         ES-001..006 + EEP + STANDARDS_INDEX   (all PROPOSED)
    │   ├── architecture/       adr/ baseline/ c4/ reference/
    │   ├── knowledge/          methodology/ patterns/
    │   └── verification/       observations/*.jsonl  metrics/trend.jsonl  reports/
    │                                                            (git-tracked)
    ├── docs/knowledge/         132 files + 10 schema YAML  (EKP — disposition PENDING)
    ├── docs/knowledgeos/       199 files: architecture · reviews · brainstorming · backlog
    ├── docs/adr/  docs/plans/  docs/implementation/
    └── tests/Unit/Platform/WorkflowEngine/   29 contract tests
```

### 3.2 The behavioural shape — tier 1 · class **A**

EKS is **process-oriented, not service-oriented**. Nothing observes, schedules, or enforces continuously.

```text
                A HUMAN OR AN AI PROCESS DECIDES TO ACT
                                 │
        ┌────────────────────────┼────────────────────────┐
        ▼                        ▼                        ▼
  invokes a CLI            edits a file             starts a session
  (php <script>)      (→ PostToolUse hook →       (→ SessionStart hook →
        │              ClaudeCodeTrigger)          inject-context.sh)
        ▼                        ▼                        ▼
   reads/writes            observes ONE file        injects ~1.14 MB
   files in the            ephemerally              of governed knowledge
   repository              (nothing persisted)      into the process
        │
        ▼
  FILES ARE THE ONLY INTEGRATION MECHANISM
```

**Consequence (class D):** EKS's liveness is entirely a property of its *consumers*. The 17-day dormancy of the observation subsystem (§18.5) is not a fault in EKS — it is EKS working exactly as built, with nobody invoking it.

### 3.3 What EKS is *not* — tier 1 · class **A** (verified by absence)

| Claim | Verification |
|---|---|
| not a database-backed system | `grep -rilE 'PDO\|mysqli\|pg_connect\|postgres\|neo4j\|elasticsearch\|qdrant\|weaviate'` over `scripts/` + `.claude/scripts/` → **zero** functional hits |
| not event-driven | no bus, broker, queue, publisher, subscriber, or event store anywhere in EKS code |
| not a service mesh / microservices | no HTTP server, no API surface, no service boundary, no network call |
| not a deployment unit | no Dockerfile, no manifest, no CI deploy job for EKS; it ships as files in the product repo |
| not continuously running | `.git/hooks` empty · `core.hooksPath` unset · `.husky/_` empty → **no git hook is installed** (§17.3) |
| not enforcing | 0 locks, 0 leases, 0 blocking gates (`workflow-state.php:12–13`; Increment 2 explicitly not authorized) |

---

## 4. Business / Engineering Capabilities

Capabilities as evidenced, with the reality class of the *capability*, not of its documentation.

| # | Capability | Implementation evidence | Tier | Class |
|---|---|---|---|---|
| C-01 | **Authority registration** — record that a human act authorized a scope | `workflow-state.php` `grant` cmd; 126 grants, 126/126 `humanActRef` | 1,3 | **A** |
| C-02 | **Work-item state recording** — append-only transition log; state = fold | `foldSessions()`; 218 transitions, seq dense+monotonic 18/18 | 1,3 | **A** |
| C-03 | **Mutation-ownership arbitration** — exactly one owner, moved only by governed transitions | R1 contract test; `assertTransitionAllowed` | 1,2 | **A** |
| C-04 | **Session-assignment discovery** — read-only "which assignment applies here?" | `session-resolve.php`; 17 contract tests | 1,2 | **A** |
| C-05 | **Deterministic document assurance** — identifier / reference / vocabulary / table integrity | `EngineeringKnowledge` 4 capabilities; **252 tests green** | 1,2 | **A** |
| C-06 | **Deterministic code observation** — LCOM4, test-presence | `Lcom4Collector`, `TestPresenceCollector`; 43 tests green | 1,2 | **A** |
| C-07 | **Rules-as-data recommendation** — facts × rules → advisory recommendations | `RecommendationEngine`; `recommendation-rules.yaml` (5 rules) | 1,2 | **A** |
| C-08 | **Decision + rationale capture** — two records per response | `recommendation-decide.php`; `decisions.jsonl` (4 rows) | 1,3 | **A** |
| C-09 | **Outcome + effectiveness assessment** — did the recommendation help? | `OutcomeRecorder` + `AssessmentService`; 1 outcome, 1 assessment | 1,3 | **A** |
| C-10 | **Knowledge-corpus linting** — schema-driven validation of governed docs | `knowledge-lint.php`; ran clean over **37 governed documents** | 1,3 | **A** |
| C-11 | **Derived placement resolution** — classification → documentation root | `doc-placement.php`; **9/9 self-test cases pass** | 1,2 | **A** |
| C-12 | **Reference / link integrity reporting** | `link-check.php`; reports **58 broken links** | 1,3 | **A** |
| C-13 | **Identifier-collision prevention before minting** | `identifier-check.php`; **only 2 series governed** | 1,3 | **C** |
| C-14 | **Evidence presentation** — dashboards over the streams | `dashboard-renderer.php`, `EvidenceDashboardRenderer.php`, `DASHBOARD.md` | 1,3 | **A** |
| C-15 | **Governed-knowledge distribution to a process** | `inject-context.sh`; injects the whole of MEMORY+CONTEXT | 1 | **D** (§15.2) |
| C-16 | **Component/asset registration** — registry-first governance | `.claude/platform/registry.yaml`; 8 components, 16 assets | 1 | **C** (§16.6) |
| C-17 | **Authority *enforcement*** | — | — | **E — MISSING** |
| C-18 | **Temporal reconstruction of authority** ("who held what, when?") | 2/218 transitions carry any date field | 3 | **E — MISSING** |
| C-19 | **Cross-process coordination / provenance attestation** | EKS-07, first-hand measured evidence | 3,4 | **E — MISSING** |
| C-20 | **Delegation** (narrowing/passing of authority as a modelled concept) | 0/126 grants carry any delegation field | 3 | **OBSERVED BEHAVIOUR — SEMANTIC INTERPRETATION UNKNOWN** (§7.9) |
| C-21 | **Semantic Rule model** (Rule as a governed domain concept) | rule-*like* mechanisms exist in ≥7 unrelated senses | 3,5 | **C / not established** (§6.4) |
| C-22 | **Knowledge graph / search / vector projection** | `knowledge-graph.php` emits one markdown file; no graph store | 1 | **C** (graph doc) / **E** (store) |

**Archaeology-hardening corrections applied as required by AMENDMENT 4 §2.3:**
- C-20 **Delegation** is recorded as *OBSERVED BEHAVIOUR — SEMANTIC INTERPRETATION UNKNOWN*, **not** as IMPLEMENTED-BUT-IMPLICIT. Independently re-verified this session: **0/126 grants carry any delegation, validity, expiry, ownership, or session-linkage field.** The only grant fields that exist are the six the contract requires.
- C-21 **Rule governance** is recorded as *PARTIAL — the semantic Rule model is not established as the current EKS domain model.*

---

## 5. Actors and Users

### 5.1 Governance — tier 1,3 · class **A**

The **only** role with write authority over the Authority State. Enforced in code:

```php
if ($writer !== 'governance') {
    refuse('the Authority State has exactly one writer: the Governance role (G-2/R5a)');
}
```
`workflow-state.php:333–335` · pinned by contract test R5a.

Measured: **126/126 grants** carry `registeredBy: governance` (the field is *assigned by the mechanism*, not supplied by the caller — `$g['registeredBy'] = 'governance';` line 349). Governance recorded **160 of 218 transitions**.

⚠️ **`--writer-role` is a command-line argument.** Anything that can run the CLI can pass `--writer-role=governance`. The single-writer rule is a **recorded** constraint, not an **authenticated** one (class **D**, §23 P-7).

### 5.2 Human engineering participants — tier 3 · class **A**

| Actor identity found in records | Occurrences |
|---|---|
| `recordedBy: human` | **56 / 218** transitions |
| `authority: PO` / `PO/ARB` / `PO/ARB (…)` | 126 grants, **~90 distinct prose values** |
| `ARB Chief / Principal DDD Architect` | 1 grant |
| `ARB Chief / Principal Architect` | 1 grant |
| `Governance / PO-ARB` | 1 grant |
| git `user.name` (decision capture) | `decisions.jsonl` actor field |

⚠️ **Actor identity is prose, never an identifier.** There is no actor register, no actor value object, and no way to resolve `PO/ARB (delivered act 2026-08-21, C-12 AMD6 routing / eligibility clarification)` to a person or a role instance. Class **D**.
⚠️ **Data defect (tier 3):** `decisions.jsonl` records `"actor":"=Dr. Nab Raj Roshyara"` — a leading `=` from `git config user.name` capture, present in both decision rows.

### 5.3 Roles declared by workflows — tier 3 · class **A**

Four role names, declared per work item at `init` and **immutable per session assignment** (R8):

| Role | Declared in N of 18 records |
|---|---|
| `governance` | 18 |
| `verification` | 18 |
| `architecture` | 17 |
| `implementation` | 11 |

The **six-role operating model** (Governance · Architecture · Implementation · Verification · Knowledge · Communication) is adopted in documentation (`docs/knowledgeos/reviews/2026-08-19-six-role-operating-model-adoption.md`, tier 4). The **runtime knows four**. `knowledge` and `communication` appear in **no** workflow record. Class **F — CONTRADICTED** (§25.1 X-2). *(The adoption record itself states roles ≠ bounded contexts ≠ capabilities ≠ agents; that statement is honoured here.)*

### 5.4 AI processes — tier 1,3,4 · class **A** (they act) / **E** (they cannot be attested)

AI processes are first-class actors: they invoke the CLIs, author artifacts, and **record transitions**. Measured: `recordedBy: architecture` on 2 HANDOFF transitions — i.e. a non-Governance role wrote to the transition log, which the contract permits (only `COMPLETE` and `CONTINUATION` constrain `recordedBy`).

**Their identity cannot be established by any mechanism.** Verbatim in tier-1 source:

> *"It is CODE provenance, never AI-process attestation: nothing here asserts who produced the artifact (EKS-07 stays unsolved; self-declared process identity is not independently attested authorship)."*
> — `scripts/lib/EngineeringKnowledge/Shared/Domain/HandoffContext.php:11–15`

Self-declaration is the practice: `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-ADR.md` declares its producing process as `claude-code-session:5e1dd9ee` and simultaneously marks it **"self-declared and NOT attestable"**.

### 5.5 Automation as an actor — tier 1 · class **C**

| Automated actor | Wiring | Active? |
|---|---|---|
| **ClaudeCodeTrigger** (`PostToolUse` Write\|Edit) | `.claude/settings.json` | ✅ **ACTIVE** |
| SessionStart context injector | `.claude/settings.json` | ✅ **ACTIVE** |
| 4 × `PreToolUse` reminder hooks | `.claude/settings.json` | ✅ ACTIVE (non-blocking) |
| 2 × `Stop` reminder hooks | `.claude/settings.json` | ✅ ACTIVE (non-blocking) |
| **CommitTrigger** (`post-commit`) | `.husky/post-commit` → `scripts/observations/git-hooks/post-commit` | 🔴 **NOT INSTALLED** — `.git/hooks` empty, `core.hooksPath` unset, `.husky/_` empty |
| `knowledge-lint` CI | `.github/workflows/knowledge-lint.yml` | ⚠️ runs, `continue-on-error: true` — **cannot fail the build** |
| every other EKS check | — | 🔴 **manual invocation only** |

**Finding (class D):** EKS's most-cited automatic trigger — the commit trigger — is **not wired in this working copy**. The only *automatic* EKS observation path that actually runs is the one owned by the AI harness.

### 5.6 External systems — tier 1 · class **A**

`git` (CLI, subprocess) · GitHub Actions · `composer`/`vendor` (`symfony/yaml`, `nikic/php-parser`, `phpunit`) · `php` · `python3` (one evidence-experiment collector) · `pdepend`. **No network integration of any kind.**

---

## 6. Ubiquitous Language — EKS's own

Reconstructed **only** from EKS's own artifacts. Terms are given the meaning the *implementation* assigns, and where implementation and documentation disagree the disagreement is recorded.

### 6.1 Terms with a single, precise, implemented meaning — class **A**

| Term | Implemented meaning (tier 1) |
|---|---|
| **Work Item** | The identity of one governed unit of work; **one JSON file, exactly one authoritative record** (Inv A). Id charset `[A-Za-z0-9._-]`. |
| **Transition** | One append-only entry in the log. Carries `type`, `recordedBy`, and a mechanism-assigned `seq`. Eight types exist: `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`. |
| **Fold** | The derivation of current state from the transition log. **The fold is the only state authority**; no derived state is ever persisted. |
| **Session Registry** | `transitions[]` — historical evidence. **Record 1.** |
| **Authority State** | `grants[]` — the authority record. **Record 2. Never merged with Record 1.** |
| **Grant** | A registered reference to a human authorizing act. Six mandatory fields, no others (§7.2). |
| **Mutation Owner** | The single session that may currently mutate the work item. Derived, never stored. Set only by `START`/`CONTINUATION`; cleared by `HANDOFF` and by `COMPLETE` of the owner. |
| **Handoff** | `from → to` plus a **token AND tokenRef**. *"A handoff without its token-reference is not a small handoff; it is not a transition"* (Inv D). |
| **START** | The **conjunction** of a recorded predecessor handoff **and** a recorded human act. Either alone yields nothing (G-3, R3b, R3c). |
| **Refusal** | The mechanism's rejection of an illegal write: exit 65, nothing appended. **A refusal prevents no engineering action.** |
| **Verdict** (assurance) | A closed enum: `PASS · FAIL · WARN · INCONCLUSIVE` emittable by machine; `PASS AFTER CORRECTION · EMERGENT · CERTIFIED` reserved for human review/certification acts and **structurally unemittable** by code. |
| **Assessment** (assurance) | Verdict + mandatory evidence string + opaque subject. Confers no authority (AP-1). |
| **ChangeSet** | `(changedFiles, source, timestamp, ?commitId)` — the technology-neutral object every trigger produces and the **only** thing the Observation Runtime consumes. |
| **Observation** | A stated fact about code: metric · subject · value · evidence refs. **No verdict, no threshold, no policy.** |
| **Recommendation** | Advisory output of `rule × fact`. Stable id `REC-<sha1(rule|subject)[0:10]>`. Status `issued`. |
| **Decision** (observation loop) | A developer's response: `ACCEPTED · IGNORED · DEFERRED`, plus a separate **Rationale** record with a closed reason code. |
| **Outcome** | Raw metric reality N commits after a decision. **Never judges.** |
| **Assessment** (observation loop) | The judgement over an outcome: `SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE`. |

### 6.2 Overloaded terms — class **F/D**

| Term | Distinct meanings found in EKS |
|---|---|
| **Session** | (1) a *SessionAssignment* in a workflow record (`S3-impl-2026-08-14`) — has role, predecessor, execution context, lifecycle; (2) an AI-process execution (`claude-code-session:5e1dd9ee`); (3) a **day** of work (`.claude/sessions/2026-08-21.md`); (4) an ARB meeting (`arb-session-agenda.md`). **These are four different things sharing one word.** |
| **Assessment** | (1) `Shared\Domain\Assessment` = verdict + evidence over a document; (2) `AssessmentService` = did a recommendation help? Unrelated concepts, identical name, both class A. |
| **Decision** | (1) developer response to a recommendation; (2) ADR / architectural decision; (3) `decision: D-2` in the registry = a boundary approval; (4) PO/ARB decision act. |
| **Evidence** | (1) mandatory string on an `Assessment`; (2) `evidence_refs` file list; (3) the `engineering/verification/` corpus; (4) the transition log ("historical evidence"); (5) a commit sha. |
| **Verdict** | (1) assurance enum; (2) resolver enum (`RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE`); (3) `EdgeVerdict` in the cohesion graph. Three closed enums, one word. |
| **Rule** | ≥7 senses: engineering standard (ES-nnn) · ruling (R-nn) · architecture principle (AIP-nn) · recommendation heuristic (`recommendation-rules.yaml`) · quality-gate rule · document-validation rule · permission rule. |
| **Workflow** | a free-text label on a work item (14 distinct values / 18 records) with **no definition anywhere**. |
| **Capability** | (1) `CAP-nn` in the platform registry; (2) `Capabilities/` in the EngineeringKnowledge library; (3) "business/engineering capability" in the protocol. |

**Vocabulary finding (preserved from tier 7, independently corroborated at tier 1):** the **Rule** overload is a *DDD vocabulary problem, not a naming problem*. This baseline **preserves the distinctions rather than normalizing them**, as AMENDMENT 4 requires.

### 6.3 Terms present in documentation with no EKS implementation — class **G**

`bounded context` (as an EKS structure) · `aggregate` (no aggregate type exists in EKS code) · `domain event` (no event type, bus, or store) · `projection` (except one generated markdown file) · `knowledge graph store` · `semantic search` · `vector store` · `kernel` · `platform runtime` · `enforcement`.

### 6.4 The `Rule` position (AMENDMENT 4 correction, applied verbatim)

> **Rule-like governance mechanisms exist in the ecosystem, but the Track-2 semantic Rule model is NOT established as the current EKS domain model.**

Corroborating tier-1 measurement: the only executable "rules" in EKS are (a) the 5 rows of `recommendation-rules.yaml`, (b) the schema severities in `docs/knowledge/schema/knowledge-schema.yaml`, and (c) the hard-coded preconditions in `assertTransitionAllowed()`. These three have **no shared type, no shared vocabulary, and no relationship to one another.**

---

## 7. Domain Model

### 7.1 What can actually be established

There are **no domain types in EKS for the governance subsystem**. The governance model exists as (a) a JSON schema-by-convention with `"schema": 1`, and (b) a set of functions over associative arrays. There is no `WorkItem` class, no `Grant` class, no `Session` class, no `Transition` class, no value object, and no repository interface anywhere in `.claude/scripts/`.

By contrast, the **assurance subsystem has a real tactical model**: `scripts/lib/EngineeringKnowledge/` is hexagonal, with `Domain/` (pure), `Application/` (ports + use cases), `Infrastructure/` (adapters), and `Tests/` per capability — 4 capabilities, ~60 domain classes, 36 test files, **252 passing tests**.

> **⭐ The single most consequential DDD finding in this baseline:**
> **EKS's *governance* — the part that carries authority, ownership, and lineage — is modelled with the least rigour of anything in EKS. Its *document-assurance* — the part that produces advisory verdicts — is modelled with the most.** The modelling investment is inversely proportional to the invariant weight.

### 7.2 Entities and structures as they exist — tier 1,3

**Work-item record (JSON, one file):**
```text
{ schema:1, workItem, workflow, roles[], transitions[], grants[] }
```
Six top-level keys. **Nothing else is ever persisted** — no derived state, no timestamps at record level, no version, no checksum, no author.

**Transition (measured field frequency over 218):**

| field | present | note |
|---|---|---|
| `type` | 218 (100%) | closed set of 8 |
| `recordedBy` | 218 (100%) | free string; constrained only for `COMPLETE`/`CONTINUATION` |
| `seq` | 218 (100%) | **assigned by the mechanism**; dense + monotonic in 18/18 records |
| `session` | 157 (72%) | |
| `role` `predecessor` `executionContext` | 61 each (28%) | = the 61 `REGISTER`s |
| `from` `to` `token` `tokenRef` | 61 each (28%) | = the 61 `HANDOFF`s |
| `humanAct` | 61 (28%) | 58 on `START`, **3 on `COMPLETE`** |
| `note` | 34 (16%) | |
| `reason` | 4 (2%) | mandatory on `STOP` (1 STOP exists) |
| **`date`** | **2 (0.9%)** | day granularity only |
| `transcription` `executionContextRef` `grant` `boundary` | 1 each | **ad-hoc fields — the schema is open** |

**Grant (measured over 126):** exactly six fields, on all 126 — `grantId · status · authority · humanActRef · scope · registeredBy`. **Zero grants carry any seventh field.**

| Property | Measurement | Consequence |
|---|---|---|
| `status` | `AUTHORIZED` 125 · `REVOKED` 1 | `PROPOSED`, `CONSUMED`, `CLOSED` are declared in code and **never used** — the grant lifecycle is not exercised |
| `authority` | **~90 distinct prose values / 126 grants**; only **12/126** are a bare token (`PO`, `PO/ARB`) | authority *type* is not a modelled concept |
| `scope` | **min 65 · median 2,337 · max 6,037 characters**; only **2/126** are ≤200 chars | see §7.4 |
| `humanActRef` | **26/126** contain a commit-like sha; **63/126** contain a sha **or** a doc path; **63/126** are prose only | provenance is resolvable for at most half |
| `grantId` | **1 duplicate across the estate** (`G-KOS-GOVGAPS-VERIFY` ×2) | grant identity is **not** enforced unique |

### 7.3 Aggregates — class **B** (one implicit candidate) / **E** (all others)

| Candidate | Assessment |
|---|---|
| **Work-item record** | **The clearest consistency boundary in EKS, and it is IMPLICIT.** Evidence *for*: one file per work item; atomic `tmp+rename` write; every precondition evaluated **only** against that record's own fold; contract test **R7** pins "a transition on one item leaves every other record unchanged"; `refuse('record already exists — exactly ONE authoritative record per work item (Inv A)')`. Evidence *against* calling it an aggregate: no type, no identity object, no encapsulation, no invariant *owner* — the invariants live in a free function (`assertTransitionAllowed`), not in the thing they protect; and the record has **two independent internal records** (`transitions`, `grants`) with different writers and different lifecycles, which is unusual for a single aggregate. **Verdict: CONSISTENCY BOUNDARY — YES (class B). AGGREGATE — NOT ESTABLISHED.** |
| Grant | Written into the work-item file; never independently loaded, never independently identified (duplicate ids exist), no lifecycle transitions. **NOT an aggregate.** |
| Session / SessionAssignment | Exists only as a key in the fold's output map. Never persisted as an entity. **NOT an aggregate.** |
| Recommendation / Decision / Outcome / Assessment | Append-only JSONL rows joined by string id. No transactional boundary; the chain can be partially written. **NOT aggregates.** |
| Knowledge document | A file with front-matter, validated by an external linter. No write-side model at all. **NOT an aggregate.** |

### 7.4 ⭐ The authority-scope finding — tier 1,3 · class **D**

The `authorized` query is implemented as:

```php
if (($g['status'] ?? '') === 'AUTHORIZED' && ($g['scope'] ?? null) === $scope) { $answer = true; }
```
`workflow-state.php:429–431` — **string equality on `scope`**.

Measured: grant `scope` has a **median length of 2,337 characters** and a maximum of **6,037**. Only 2 of 126 scopes are under 200 characters.

> **Therefore: the authorization query is structurally implemented (class A) and practically unanswerable (class D).** To ask "is this session authorized for scope X?" a caller must reproduce a multi-thousand-character prose paragraph byte-for-byte. `session-resolve.php` already records this in tier-1 source: *"the intended act is covered by grant scope → UNKNOWN — not evaluable from the record: scope exists only as a string, compared by equality."*
>
> **`scope` is not a scope. It is a commission narrative stored in a field named `scope`.**

This is a first-hand finding of this baseline; no prior document states it in these terms.

### 7.5 Domain services — class **A** (as functions, not services)

`foldSessions()` (state derivation) · `assertTransitionAllowed()` (invariant guard) · `RecommendationEngine::evaluate()` (pure) · `AssessmentService::evaluate()` (pure, thresholded) · `RecommendationInbox::classify()` (pure) · `AnalyseCohesion::observe()` (pure) · the four `Validate*` application services in the assurance library. **All are static/pure functions. There is no service registry, container, or injection anywhere in EKS** except constructor injection inside the assurance library.

### 7.6 State machines — class **A** (two) / **E** (one incomplete)

**(a) SessionAssignment lifecycle — class A, complete:**
```text
                    ┌──────────────────────────── STOP ──► STOPPED
                    │                                        │ (only CONTINUATION,
REGISTER            │                                        │  by governance|human)
   │                │                                        ▼
   ▼                │                                    (ACTIVE)
CREATED ── HANDOFF(from=this) ──► HANDED_OFF
   │                                   │
   └── START (needs handoff-to-me AND recorded human act) ──► ACTIVE
                                                               ├─ COMPLETE ──► COMPLETED  (terminal)
                                                               ├─ FAIL ─────► FAILED      (terminal)
                                                               └─ CANCEL ───► CANCELLED   (terminal)
```

**(b) Grant lifecycle — class C:** 5 states declared (`PROPOSED · AUTHORIZED · CONSUMED · CLOSED · REVOKED`); **no transition mechanism exists.** A grant's status is whatever the single `grant` write set it to. Measured: 125 `AUTHORIZED`, 1 `REVOKED`, and **zero** grants in the other three states. Tier-1 source confirms the gap: *"Grant status is reported as recorded; lifecycle exercise/closure is E-15."*

**(c) ⭐ Work-item lifecycle — class E (MISSING TERMINAL STATE):**
```php
$itemState = 'OPEN';                  // initial
case 'START': case 'CONTINUATION':  $itemState = 'OPEN';     break;
case 'STOP':                        $itemState = 'STOPPED';  break;
// COMPLETE affects the SESSION only; it never changes $itemState
```
> **`workItemState` has exactly two reachable values: `OPEN` and `STOPPED`. There is no `CLOSED`, `COMPLETED`, `ACCEPTED`, or `CANCELLED` work-item state.**
>
> Measured consequence: **34 `COMPLETE` transitions have been recorded, and 17 of 18 work items still fold to `OPEN`.** Two carry a live mutation owner; fifteen fold to `OPEN` with `mutationOwner: null` — a state that is indistinguishable from *"never started"*, *"finished"*, and *"abandoned"*. One folds to `STOPPED`.

This is a first-hand finding. **The lifecycle of governed work has no recorded end** (special question 6, §21).

### 7.7 Domain events — class **E**

**No domain events exist in EKS.** No event type, no event class, no publisher, no subscriber, no bus, no store, no outbox, no handler. See §14.

### 7.8 Invariant ownership — the DDD question that matters most

| Invariant | Owner in code | Class |
|---|---|---|
| Inv A — exactly one authoritative record per work item | filesystem + `is_file()` check in `init` | **B** |
| Inv B — session identity answerable from the record alone | `REGISTER` preconditions | **A** |
| Inv C — exactly one mutation owner; ownership moves only by governed transition | `foldSessions` + `HANDOFF` precondition | **A** |
| Inv D — no token, no handoff | `HANDOFF` precondition | **A** |
| Inv E — STOPPED is sticky; only `CONTINUATION` exits | global guard in `assertTransitionAllowed` | **A** |
| Inv F/G-3 — ACTIVE requires handoff **and** human act | `START` precondition | **A** |
| Inv G — ACTIVE ≠ AUTHORIZED (lifecycle and authority never merge) | separate records; `authorized` returns a plain boolean | **A** |
| Inv H/G-2 — Governance is the sole Authority writer, registering a *referenced* human act | `grant` precondition | **A** (recorded) / **D** (unauthenticated) |
| Inv I — the engine holds no decision logic; preconditions check that facts exist, never supply them | design property, stated in the header | **A** |
| Inv J — zero cross-work-item coupling | one file per item; pinned by R7 | **A** |
| R8 — role is immutable per SessionAssignment | `REGISTER` duplicate check + absent `ROLE_CHANGE` edge | **A** |
| **grant identity uniqueness** | **nothing** | **E** — 1 duplicate measured |
| **temporal ordering of authority** | **nothing** | **E** — 2/218 dated |
| **process/actor authenticity** | **nothing** | **E** — EKS-07 |
| **corpus reference integrity** | `link-check.php`, report-only | **C** — 58 broken |
| **identifier uniqueness across the estate** | `identifier-check.php`, 2 series governed | **C** |
| **document schema conformance** | `knowledge-lint.php`, non-blocking CI | **C** |
| **placement correctness** | `doc-placement.php` + advisory hook | **C** |

**11 of 11 workflow-record invariants have a single, identifiable owner and an executable test. Every invariant outside the workflow record either has no owner or has an advisory-only owner.**

### 7.9 Delegation — OBSERVED BEHAVIOUR, SEMANTIC INTERPRETATION UNKNOWN

Required correction, applied. **Observed:** grants on a work item frequently narrow over time (e.g. `KOS-AIP-GOV-STATE-DURABILITY-ADR` carries 23 grants, many of them successive amendment-scoped acts). **Not established:** that the business concept *Delegation* exists in the current EKS model. Independently re-verified: **0/126 grants carry a delegation, delegator, delegatee, parent-grant, validity, or expiry field.** The narrowing is visible in prose `scope` text only, and prose is not a model.

---

## 8. Aggregate / Consistency Boundaries

| Boundary | What changes atomically | Evidence | Class |
|---|---|---|---|
| **One work-item file** | the whole record — `transitions` + `grants` together, via `tmp + rename` | `saveRecord()`; contract test R7 | **B** — clearest boundary in EKS, never named as one |
| Each JSONL stream row | one append (`FILE_APPEND`) | `recommendation-observer.php`, `recommendation-decide.php` | **A** for the row; **E** across rows |
| `decisions.jsonl` decision+rationale **pair** | ⚠️ written as **two rows in one `file_put_contents`** — atomic by accident of buffering, not by design | `recommendation-decide.php:44–48` | **D** |
| The recommendation→decision→outcome→assessment chain | **nothing** — four separate files, joined by string id, each appended independently | measured: 10 → 2 → 1 → 1 rows | **E** — no transactional boundary |
| The knowledge corpus | **nothing** — a git commit is the only grouping, and it is not an EKS mechanism | — | **E** |

**Concurrency:** none. `workflow-state.php` performs read → fold → check → append → write with **no lock, no lease, no compare-and-swap, and no re-read**. Two concurrent appends silently lose one. Tier-1 source states this is deliberate: *"Increment 2 is not authorized: no lock, no lease, no hook, no enforcement."* Mitigation is detection, not prevention: `seq` is dense and monotonic, so a lost transition is *mechanically detectable as a gap* (measured: no gaps today, 18/18 records clean). Class **D — accepted, recorded debt.**

> ⛔ **E-1 erratum (applied 2026-08-22 — smallest scope, see §25.2):** the mitigation claim immediately above — *"a lost transition is mechanically detectable as a gap"* — is **contradicted by the fully-read corpus** (`KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` §1.2, tier 1/3; P3-F1 lesson). In the concurrent-append experiment, **23 / 30 trials silently lost a transition; the losing process exited 0 and reported `{"ok":true,"seq":2}`; every survivor was dense and monotonic.** *"DENSITY IS NOT A COMPLETENESS PROOF. It is preserved BY the loss, because the clobbering writer reuses the sequence number the lost writer took."* A lost transition is therefore **NOT** mechanically detectable as a gap. The parenthetical "(measured: no gaps today, 18/18 records clean)" stands only as a point-in-time fact and proves nothing about loss. Class stays **D — accepted, recorded debt.** The original sentence is preserved verbatim above; this erratum is the correction, never a silent rewrite.

---

## 9. Bounded Context Candidates

> ⛔ **EKS's bounded contexts are NOT ESTABLISHED.** What follows are **BOUNDARY CANDIDATES** derived from language, ownership, invariants, lifecycle, and dependency direction — never from folders, files, scripts, or diagrams. **No candidate is promoted.** No KnowledgeOS context is used as a target.

| # | Boundary candidate | Own language? | Own invariants? | Own lifecycle? | Identifiable owner? | Verdict |
|---|---|---|---|---|---|---|
| **BC-1** | **Governed Work & Authority** — work item, grant, transition, session assignment, mutation owner, handoff, START, refusal | ✅ precise and internally consistent | ✅ 11, all owned and tested | ✅ two state machines (one incomplete) | ✅ the `governance` role, enforced in code | **STRONG BOUNDARY CANDIDATE** — the strongest in EKS |
| **BC-2** | **Deterministic Document Assurance** — capability, assessment, verdict, evidence, policy, contents-reader, handoff report | ✅ closed enums, explicit AP-1/AP-8 rules | ✅ within each capability | ⚠️ per-run only; no stateful lifecycle | ⚠️ no named steward in code or registry | **BOUNDARY CANDIDATE** |
| **BC-3** | **Engineering Observation** — change set, trigger, collector, observation, fact | ✅ clean and enforced ("collectors never judge") | ⚠️ purity/trigger-independence properties, tested | ⚠️ stateless per ChangeSet | ⚠️ unregistered | **BOUNDARY CANDIDATE** |
| **BC-4** | **Engineering Improvement Loop** — recommendation, decision, rationale, outcome, assessment | ✅ distinct and consistent | ⚠️ one: "recommend, never decide" — enforced by construction | ✅ a real 4-stage lifecycle, exercised once end-to-end | ⚠️ unregistered | **BOUNDARY CANDIDATE** (separate from BC-3: different language, different lifecycle, and BC-3 works without it) |
| **BC-5** | **Engineering Knowledge Corpus** — knowledge document, knowledge card, knowledge id, relationship, authority, maturity, placement | ✅ schema-declared (10 YAML files) | ⚠️ all advisory | ✅ documented lifecycle (`_meta/lifecycle.md`) | 🔴 **disposition PENDING ARB** — ES-006 lists `docs/knowledge/` as *"EKP (incumbent **project**-knowledge governance) — disposition PENDING"* | **BOUNDARY CANDIDATE · OWNERSHIP UNRESOLVED** |
| **BC-6** | **Architecture Governance Records** — ADR, ruling (R-nn), review, acceptance, register | ✅ | 🔴 none executable | ✅ documented | ✅ ARB / Decision Authority | **BOUNDARY CANDIDATE (document-only)** |
| **BC-7** | **AI Engineering Platform surface** (`.claude/`) — hook, asset, component, registry, session log, CONTEXT, MEMORY | ✅ CMP/AST identity model | ⚠️ registry-first rule, unenforced | ✅ adoption states | ✅ ARB, via the registry | **PLATFORM BOUNDARY — not a business bounded context** (Stage 3 owns its reconstruction) |

### 9.1 Why none is promoted

| Required for promotion | Status across candidates |
|---|---|
| Ownership | Established for BC-1, BC-6, BC-7 only. BC-2/3/4 have **no registered owner at all**. BC-5's ownership is formally PENDING. |
| Transaction boundary | Only BC-1 has one (§8). |
| Dependency direction | Verified acyclic for BC-1↔BC-7 (§18.2). **Unverified between BC-2, BC-3, BC-4, BC-5.** |
| Ubiquitous language integrity | Violated *across* candidates by 8 overloaded terms (§6.2) — `Session`, `Assessment`, `Verdict`, `Decision`, `Evidence`, `Rule`, `Capability`, `Workflow`. **A shared word with different meanings on both sides of a candidate boundary is evidence the boundary is not yet drawn.** |
| Lifecycle | BC-2 and BC-3 have none. |

### 9.2 Current C4 — **UNKNOWN / CONTESTED**

Per AMENDMENT 4, preserved. Independently corroborated: `engineering/architecture/c4/` holds three files, one of which is explicitly `-superseded`; `Engineering_Knowledge_System_Reference_Model.md` is dated **2026-07-27** (25 days stale), self-classifies as *"documentation only … does not govern"*, and **maps the "Engineering Platform" and the "KnowledgeOS product governance stack" rather than EKS's own containers**. The `.puml` files in `docs/knowledgeos/architecture/` belong to the **proposed** KnowledgeOS target and are quarantined. **No current EKS container model exists.** Class **F**.

---

## 10. Application / Workflow Architecture

### 10.1 The one governed flow that is fully implemented — tier 1,2,3 · class **A**

```text
 (1) HUMAN ACT                       outside the system entirely
        │                            (a prompt, a commission, a ruling — never captured verbatim)
        ▼
 (2) GOVERNANCE registers a GRANT    php workflow-state.php grant <WI> --writer-role=governance
        │                            requires: grantId · status · authority · scope · humanActRef
        │                            refuses:  any writer != governance · missing humanActRef
        ▼
 (3) GOVERNANCE registers a lane     append {type:REGISTER, session, role, predecessor,
        │                                    executionContext}
        │                            refuses:  duplicate session · role not in declared roles ·
        │                                      missing executionContext · missing predecessor key
        ▼
 (4) the CURRENT OWNER hands off     append {type:HANDOFF, from, to, token, tokenRef}
        │                            refuses:  missing token OR tokenRef · unregistered successor ·
        │                                      from != current mutationOwner
        ▼
 (5) HUMAN starts the lane           append {type:START, session, humanAct}
        │                            refuses:  no humanAct  -- OR --  no recorded handoff to it
        │                            => ACTIVE, and this session becomes mutationOwner
        ▼
 (6) WORK HAPPENS                    ⛔ ENTIRELY OUTSIDE THE MECHANISM.
        │                            Nothing observes it. Nothing gates it. Nothing links
        │                            an artifact to the grant that permitted it.
        ▼
 (7) HANDOFF to review / verification  (4) again, with a token referencing the evidence commit
        ▼
 (8) COMPLETE                        append {type:COMPLETE, session, recordedBy:governance|human}
                                     => the SESSION becomes COMPLETED.
                                     ⛔ the WORK ITEM remains OPEN — forever (§7.6c)
```

### 10.2 Measured shape of the estate — tier 3

| Metric | Value |
|---|---|
| Work-item records | **18** |
| Transitions | **218** (`REGISTER` 61 · `HANDOFF` 61 · `START` 58 · `COMPLETE` 34 · `CANCEL` 3 · `STOP` 1 · `CONTINUATION` 0 · `FAIL` 0) |
| Grants | **126** (`AUTHORIZED` 125 · `REVOKED` 1) |
| Distinct session assignments | **61** |
| `recordedBy` | `governance` 160 · `human` 56 · **`architecture` 2** |
| Records with a live mutation owner | **2 / 18** |
| Records folding to `OPEN` | **17 / 18** · to `STOPPED` **1 / 18** |
| Distinct `workflow` labels | **14 / 18 records** |
| Records with zero transitions | **2** (`KOS-ACTIVATION-REPORTING-001`, `KOS-ARCH-V3-BOUNDARY-VALIDATION` — the latter holds **3 grants with no lane at all**) |

**Reconciliation with prior measurements (no contradiction; growth):**

| Source | Date | Work items | Transitions | Grants |
|---|---|---|---|---|
| `KOS-AIP-GOV-STATE-DURABILITY-ADR` O-2 (tier 4) | ~2026-08-19 | 16 | 210 | 99 |
| AMENDMENT 4 full-estate measurement (tier 8) | 2026-08-21 | 18 | 218 | 126 |
| **This baseline, re-measured** | **2026-08-21** | **18** | **218** | **126** |

### 10.3 Registration without a lane, and lanes without work — class **D**

- `KOS-ARCH-V3-BOUNDARY-VALIDATION`: **3 grants, 0 transitions.** Authority registered for work that was never assigned to anyone.
- `KOS-ACTIVATION-REPORTING-001`: **0 grants, 0 transitions.** An initialised record with nothing in it, created 2026-08-15 — the discharge vehicle for AST-016's V-3 condition (§16.7).
- **58 `START`s against 61 `REGISTER`s:** three registered assignments were never started.
- **34 `COMPLETE`s against 61 registered assignments:** 27 assignments have no recorded end.

### 10.4 `workflow` is a label, not a definition — tier 1,3 · class **E**

`init` accepts `--workflow=<any string>`. **No workflow definition registry, schema, or catalogue exists anywhere in EKS.** Measured: 14 distinct values across 18 records — `architecture-decision` (5), and 13 values used exactly once each (`platform-implementation`, `architecture-adr`, `architecture-discovery`, `architecture-model-update`, `architecture-domain-model`, `architecture-exploration`, `architecture-design`, `evidence-experiment`, `governance-verification`, `verification`, `specification-correction`, `operational-qualification`, `platform-capability`).

> **Consequence: the `workflow` field carries no machine meaning. Every work item is procedurally identical; only its declared `roles[]` differ. "Workflow" in EKS is a naming convention, not a process model.**

The only thing the field constrains is nothing; the only thing `roles[]` constrains is which `role` values `REGISTER` will accept.

---

## 11. Persistence Architecture — and §11.4 the central contradiction

### 11.1 Measured persistence inventory — tier 1,3 · class **A**

| # | Store | Format | Written by | Git-tracked? | History? | Time dimension? |
|---|---|---|---|---|---|---|
| P1 | `.claude/runtime/workflow/*.json` | JSON, append-only arrays | `workflow-state.php` (`tmp+rename`) | 🔴 **NO** | 🔴 **none** | 🔴 **2/218 dated** |
| P2 | `engineering/verification/observations/*.jsonl` | JSONL | 5 observation scripts | ✅ yes | ✅ git | ✅ ISO-8601 per row |
| P3 | `engineering/verification/metrics/trend.jsonl` | JSONL | `metrics-report.php` | ✅ yes | ✅ git | ⚠️ commit id, not timestamp |
| P4 | `docs/**`, `engineering/**` markdown corpus | Markdown + YAML front-matter | humans / AI processes | ✅ yes | ✅ git | ✅ filename + front-matter |
| P5 | `docs/knowledge/schema/*.yaml` | YAML | humans | ✅ yes | ✅ git | n/a |
| P6 | `.claude/platform/registry.yaml` | YAML | humans | ✅ yes | ✅ git | ✅ `verified.date` |
| P7 | `.claude/sessions/*.md` | Markdown, append-only | humans / AI processes | ✅ yes | ✅ git | ✅ filename = date |
| P8 | `.claude/CONTEXT.md` · `MEMORY.md` | Markdown | humans / AI processes | ✅ yes | ✅ git | ⚠️ prose only |
| P9 | `engineering/verification/observations/DASHBOARD.md` | Markdown | `dashboard-renderer.php` | ✅ yes | ✅ git | derived |
| P10 | `docs/knowledge/portal/graph/knowledge-graph.md` | Markdown | `knowledge-graph.php` | ✅ yes | ✅ git | derived |

**No relational database. No graph database. No search index. No vector store. No object store. No cache. No queue.** Verified by grep over all EKS code (§3.3).

### 11.2 ⭐ The persistence inversion — tier 1,3 · class **D**

```text
                            durable?   has history?   reconstructable in time?
ADRs, reviews, registrations   ✅ git        ✅               ✅
observation streams (advisory) ✅ git        ✅               ✅
──────────────────────────────────────────────────────────────────────────────
GRANTS + TRANSITIONS           🔴 NO        🔴 NO            🔴 NO
(authority · ownership · lineage — the evidence of who was permitted to do what)
```

`.gitignore` excludes `.claude/runtime/` — and does so **twice**, at lines 25 and 32 (a duplicated rule; harmless, but a tell that it was added twice, independently).

**`.claude/runtime/` is the ONLY excluded subdirectory of `.claude/`.** Total `.claude` tracked files: **125** (`sessions` 37 · `memory` ~46 · `scripts` 13 · `platform` 2 · plus root files).

**This exclusion is deliberate and recorded**, not accidental — `registry.yaml` AST-015 notes: *"Runtime files live gitignored at `.claude/runtime/workflow/<work-item>.json`; the registry never carries runtime state (boundary §9-3)."* The `KOS-AIP-GOV-STATE-DURABILITY-ADR` (tier 4, **PROPOSED, undecided**) then measures the consequence and names the inversion, observing that *"there is no runtime state in the runtime directory"* — the file persists only an append-only evidence log and an authority record; **all execution state is derived by the fold and stored nowhere.**

**Consequences measured in this session:**
1. **No history.** `git log -- .claude/runtime/` is empty. A grant's or transition's insertion cannot be dated, attributed, or diffed.
2. **Single-host.** The authoritative governance state of the entire estate exists in exactly one working copy. Any other clone folds to `UNRESOLVABLE`.
3. **No backup, no replication, no integrity check.** No checksum, no signature, no external anchor.
4. **The scoped-subset problem is now unresolvable** — see §25.3.

### 11.3 Temporal reconstructability — **E · MISSING** (independently verified)

**Measured this session: 2 of 218 transitions (0.9%) carry any time-like field, both at day granularity.** Ordering is by `seq` alone, and `seq` is a per-record counter with no relation to any other record. Combined with §11.2 (no git history), the following questions are **unanswerable by any mechanism**:

- When was grant `G-X` registered?
- Which grants were in force when act `Y` occurred?
- Did the handoff precede the human act, or follow it?
- In what order did events across two work items occur?
- Is a given grant still current?

> **Authority in EKS is durably *stated* and completely *un-timed*.** `session-resolve.php` itself records the consequence in tier-1 source: *"Reports are ephemeral: re-run before acting, never cache (staleness is uncomputable — the record carries no transition timestamps, D-1)."*

### 11.4 ⭐ CONTRADICTION — class **F** (recorded, NOT reconciled)

| Source | Tier | Claim about EKS's system of record |
|---|---|---|
| `20260821_2032_EKS Current Architecture Baseline.md` §11.1 | 5 | *"Git-backed repository containing YAML and Markdown knowledge specifications"* — **and it attributes this to the PKS corpus** |
| Later KnowledgeOS architecture proposals | 7 | PostgreSQL / graph / object store / search as system of record |
| AMENDMENT 4 evidence conclusion | 8 | *"governance-first, Git-backed YAML/Markdown knowledge-and-assurance ecosystem"* |
| **This baseline, measured (tier 1,3 — strongest)** | **1,3** | **Two different systems of record with opposite durability properties: a git-tracked Markdown/YAML/JSONL corpus, AND an untracked local JSON governance store.** |

**Resolution status:** The *database* question is settled — **PostgreSQL/graph/vector as EKS system of record is PROPOSED, not current** (class G; zero implementation evidence). The *"Git-backed"* characterization is **half true and materially misleading**: it is accurate for P2–P10 and **false for P1**, which holds the authority record. The characterization's source is also contested — the 2032 baseline attributes it to *the PKS corpus*, which is precisely the EKS/PKS identity ambiguity (§24 U-4). **⛔ Left UNRESOLVED for Stage 2. Not reconciled here.**

### 11.5 What is persisted vs derived — tier 1 · class **A** (special question 8)

| Persisted | Derived, never stored |
|---|---|
| `schema`, `workItem`, `workflow`, `roles[]` | `sessions{}` (the whole map) |
| `transitions[]` — verbatim, append-only, `seq`-stamped | each session's `state` |
| `grants[]` — verbatim, append-only | `mutationOwner` |
| every JSONL row (recommendation, decision, rationale, outcome, assessment) | `workItemState` |
| the markdown/YAML corpus | `handoffsTo{}` |
| | `authorizationLinkage` (identity query) |
| | `authorized` (boolean) |
| | every resolver verdict, reason, and `authorizationFact` |
| | `DASHBOARD.md`, `knowledge-graph.md`, all lint/check output |

**A clean and unusually disciplined separation.** The record holds facts; every interpretation is recomputed on demand. It is the strongest single design property in EKS, and it is what makes the fold trustworthy.

---

## 12. Data Architecture *(complements §11)*

### 12.1 Data model characteristics — tier 1,3

| Property | Reality |
|---|---|
| Schema enforcement | **None at rest.** `"schema": 1` is written but never validated on read. `loadRecord()` checks only that JSON decodes to an array. |
| Schema openness | **Open.** Four ad-hoc transition fields exist in production records (`transcription`, `executionContextRef`, `grant`, `boundary`) that no code reads. |
| Referential integrity | **None.** `humanActRef` is prose (63/126 with no resolvable target). `tokenRef` is prose. `evidence_refs` are unchecked path strings. `predecessor` is not validated to be a registered session. |
| Identity uniqueness | **Work item: yes** (filesystem). **Grant: no** (1 duplicate measured). **Session: within a record only.** **Recommendation: yes** (content-derived sha1). |
| Referential direction | Records reference commits and documents; commits and documents do **not** reference records. **One-way, and the wrong way for reconstruction** — from the untracked side to the tracked side. |
| Encoding | JSON with `JSON_UNESCAPED_SLASHES` but **not** `JSON_UNESCAPED_UNICODE`: prose is stored escaped, so records are not cleanly human-greppable. |
| Data volume | 18 files, ~651 KB total; largest record 188 KB (`KOS-AIP04-DISCOVERY-001`, 33 transitions + 28 grants). Prose scope text is the dominant cost. |
| Deletion | **No delete path exists** in any EKS write mechanism. Append-only by construction. |

### 12.2 Data quality defects measured — class **D**

| Defect | Measurement |
|---|---|
| Duplicate grant identity | `G-KOS-GOVGAPS-VERIFY` appears twice |
| Malformed actor value | `"actor":"=Dr. Nab Raj Roshyara"` in 2/2 decision rows |
| Duplicate metrics snapshot | `trend.jsonl` has 3 rows for **2** distinct commits (`c3409d69f` twice) |
| Recommendation text drift | `recommendations.jsonl` REC-eaf245474a text (*"LCOM4 of 29 exceeds 20 — review aggregate cohesion"*) does not match the current R1 rule text — the rule was edited after issuance and issued recommendations carry no rule version |
| Broken corpus references | **58** (50 missing, 6 ambiguous, 2 git-rename) |
| Unresolvable authority references | **63/126** grants have a prose-only `humanActRef` |

---

## 13. State and Lifecycle

*(State machines are in §7.6. This section covers lifecycles that span mechanisms.)*

### 13.1 Governed-work lifecycle — class **C**

```text
grant registered  ->  lane registered  ->  handed off  ->  human START  ->  ACTIVE
   ->  work (unobserved)  ->  handoff to verification  ->  COMPLETE(session)
   ->  ⛔ NOTHING. The work item stays OPEN.
```
Acceptance, closure, certification, and supersession of a **work item** exist only as prose in review documents — never as a recorded state. Class **E** for the terminal segment.

### 13.2 Governance-artifact lifecycle — class **G/C**

Documented (`docs/knowledge/_meta/lifecycle.md`, `knowledge-schema.yaml` statuses, the Reference Model's *"PROPOSED → drafting-closed → ACTIVE → historical"*). **Executable enforcement: none.** `knowledge-lint.php` validates that a `status` value is in the enum; it cannot validate that a *transition* between statuses was legitimate. Class **C**.

### 13.3 Standards lifecycle — class **F**

**ES-001 … ES-006 are all `PROPOSED`, awaiting ARB review** (`STANDARDS_INDEX.md`, tier 5). Consolidation was ordered **2026-07-11**; the set is still unratified **41 days later**. Yet `.claude/CLAUDE.md` binds them as *"canonical rules"* at runtime, and the project `CLAUDE.md` cites ES-004.2 / ES-005.4 / ES-006.1 as governing rules.

> **The governance standards that EKS instructs every process to obey have never been adopted.** Class **F — CONTRADICTED** (§25.1 X-3).

### 13.4 Asset lifecycle — class **A** (the best-run lifecycle in EKS)

`registry.yaml` adoption states: `planned → under-construction → adopted → deprecated`, plus `adopted-by-reference` and `deferred`. Each asset carries a `verified{date, method}` block. AST-015's and AST-016's `verified.method` fields are the most rigorous provenance records in the estate — including a **self-correction** (AST-015: *"CORRECTION (Governance, 2026-08-14, F-2): previously recorded here as '12/12, 142 assertions' … that figure is the two files SUMMED"*) and an honest **unproven** admission (*"a separate RED commit does not exist, so authoring order rests on Session 3's testimony — recorded as unproven"*).

### 13.5 Recommendation lifecycle — class **A**, exercised **once**

```text
issued (10)  ->  decided (2 rows / 1 unique recommendation)  ->  outcome (1)  ->  assessment (1)
                                                                          verdict: INCONCLUSIVE
```
`DASHBOARD.md` reports this drop-off as a first-class finding: *"drop-offs are findings, not failures — an organizational mirror."* **8 recommendations remain open since 2026-08-04.** The single closed loop returned `INCONCLUSIVE` (*"only 1 commits since decision (min 3)"*), and the same recommendation was decided **twice** (2026-08-04 06:30 and 15:11) with near-identical rationales — the mechanism permits re-decision and the inbox classifies by latest, which is correct behaviour, but the duplicate is visible in the record.

---

## 14. Events and Messaging

> ### **There is no event architecture in EKS. Class E — MISSING.**

Verified by exhaustive absence (tier 1):

| Concept | Present? |
|---|---|
| domain event type / class / interface | 🔴 none in any EKS namespace |
| event bus, broker, dispatcher, mediator | 🔴 none |
| publisher / subscriber / listener / handler | 🔴 none |
| event store, outbox, saga, process manager | 🔴 none |
| Kafka / AMQP / Redis / any transport | 🔴 none |
| message contract or schema | 🔴 none |

### 14.1 What exists instead — class **B**

| Mechanism | What it actually is |
|---|---|
| **Trigger** (`FileSaveTrigger`, ClaudeCodeTrigger, CommitTrigger) | a *synchronous adapter* that builds a `ChangeSet` and calls a function. No message, no queue, no delivery guarantee, no retry, no ordering. |
| **`ChangeSet`** | the input DTO of one synchronous call. Explicitly *not* an event: *"NOT GitCommit, NOT VSCodeFile, NOT PullRequest: the runtime must never know how an observation was triggered."* |
| **Transition** | a persisted *fact*, not a published event. Nothing subscribes. Governance discovers other processes' transitions **by re-folding, not by notification** — measured, first-hand, in EKS-07 item 1: *"Six workflow transitions (seq 28–33) … were recorded by other windows during a single Governance session … Governance discovered them by re-folding, not by notification."* |
| **JSONL append** | a persisted record. No tailer, no watcher, no consumer group. |
| **Hook** | the AI harness calling a shell script. Fire-and-forget, always exit 0. |

**Therefore:** *"events exist"* in the loose sense that things happen and are recorded. **They do not constitute an event-driven architecture**, and no prior document's event claims survive at tier 1. Any statement that EKS is event-driven is **G — documented only**.

**Integration style, stated plainly: EKS is a shared-file integration architecture with synchronous in-process calls.** That is the whole of it.

---

## 15. AI-Agent Interaction

### 15.1 The three interaction surfaces — tier 1 · class **A**

| Surface | Mechanism | Direction | Active |
|---|---|---|---|
| **Knowledge injection** | `SessionStart` → `inject-context.sh` → cats `MEMORY.md` + `CONTEXT.md` + active plan + today's session log into the process | EKS → agent | ✅ |
| **Behavioural reminders** | 4 × `PreToolUse` + 2 × `Stop` hooks (`project-knowledge-guard`, `discipline-gate-reminder`, `ddd-principles-reminder`, `engineering-placement-guard`, `session-log-reminder`, `dev-guide-reminder`) — **all non-blocking** | EKS → agent | ✅ |
| **Observation feedback** | `PostToolUse` Write\|Edit → `claude-code-trigger.sh` → `observe.php --source=claude-code` → advisories printed into the session, **nothing persisted** | agent → EKS → agent | ✅ |
| **Governed action** | the agent invokes `workflow-state.php` / `session-resolve.php` / the lint CLIs itself | agent → EKS | ✅ (voluntary) |

Plus one **safety** hook: `db-safety-check.sh` on `Bash|PowerShell` — the *only* blocking-class guard in the estate, and it protects the **product** database, not EKS.

### 15.2 ⭐ The knowledge-distribution defect — tier 1, first-hand · class **D**

`inject-context.sh` injects **the entire** `MEMORY.md` **and the entire** `CONTEXT.md`, unconditionally, with no selection, scoping, relevance filter, or size bound.

**Measured now:**

| File | Size |
|---|---|
| `.claude/MEMORY.md` | **188,730 bytes** |
| `.claude/CONTEXT.md` | **954,708 bytes** |
| **injected per session start** | **≈ 1.14 MB**, plus the active plan and today's session log |

**First-hand evidence from this very session:** the SessionStart hook output was **1.2 MB and was truncated by the consumer**, which persisted it to a file and delivered a 2 KB preview instead. **The knowledge-distribution mechanism exceeded its consumer's ingestion capacity and silently degraded to a pointer.**

> This is the strongest possible evidence for **EKS-01 (*"Governed-Knowledge Distribution — rules must reach the session at startup"*)**: the mechanism exists, is active, and **does not reliably deliver**. `CONTEXT.md` — nominally *"the current working state"* — has grown to 954 KB, which is not a working-state document by any reasonable definition. Class **D**.

### 15.3 What EKS does **not** do with AI processes — class **E**

- **No process identity.** No mechanism issues, records, or verifies an AI-process identifier. Self-declaration is the practice (§5.4).
- **No cross-process notification.** Concurrent processes discover each other only by re-reading files (EKS-07).
- **No agent registry.** No AI process appears in `registry.yaml`.
- **No capability restriction.** Every hook is advisory; nothing an AI process can do is prevented by EKS.

---

## 16. Governance and Authority

### 16.1 The authority chain — tier 1,3 · class **A**

```text
HUMAN ACT (outside the system, never captured verbatim)
    │
    │  referenced by  ─────────────────────────────────────┐
    ▼                                                      │
GRANT  { grantId · status · authority · scope · humanActRef · registeredBy }
    │       written by governance ONLY · refused without humanActRef
    │
    │  ⛔ NO LINKAGE EXISTS FROM HERE DOWN  ─────────────────┘
    ▼
SESSION ASSIGNMENT  { role · predecessor · executionContext · state }
    │       activated by (handoff-token AND human-act), both recorded
    ▼
MUTATION OWNERSHIP  (derived, single-valued)
    │
    ▼
ENGINEERING WORK    ⛔ unobserved, ungated, unlinked to any grant
```

### 16.2 ⭐ Where authority is actually enforced (special question 4)

**Nowhere against engineering work. In exactly four places against the record itself:**

| # | Enforced constraint | Site | Test |
|---|---|---|---|
| 1 | Only `governance` may write a grant | `workflow-state.php:333` | R5a |
| 2 | A grant must carry a `humanActRef` | `:336` | R5b |
| 3 | Only the current mutation owner may hand off | `:225` | R1 |
| 4 | Only `governance`/`human` may record `COMPLETE` or `CONTINUATION` | `:262`, `:249` | R4 |

Every one is a **precondition on a write to the JSON file**, evaluated by a CLI the actor chose to run, with the writer role supplied as a **command-line flag**.

> **Enforcement scope, stated precisely: EKS enforces the well-formedness of its own record. It enforces nothing about engineering.** No file edit, commit, push, merge, deploy, document creation, or architectural change is gated by any grant, any ownership, or any transition. The tier-1 source says so in its opening comment: *"the fold refuses; nothing is physically prevented."*

### 16.3 Grant vs record authority (special question 5)

> **The system RECORDS authority. It does not GRANT authority.** Verbatim in tier-1 source: *"a grant registers a recorded human act by reference — the record never manufactures authority (G-2/R5b)"*.

Corroborated by measurement: **126/126** grants carry a `humanActRef`, and **0/126** carry anything the mechanism could have generated as authority itself.

### 16.4 The six AuthorizationFacts — the system's own honest self-assessment · tier 1

`session-resolve.php` surfaces six facts and **evaluates none**. Three of the six are permanently UNKNOWN by construction:

| Fact | Answerable from the record? |
|---|---|
| correct session | ✅ |
| correct role | ✅ |
| state is ACTIVE | ✅ |
| holds mutation ownership | ✅ |
| **is the grant holder** | 🔴 **UNKNOWN — grants carry no session/role linkage (D-2)** |
| **the intended act is covered by grant scope** | 🔴 **UNKNOWN — scope exists only as a string, compared by equality** |

> **⭐ The load-bearing consequence: EKS cannot answer "is this actor authorized to do this thing?" — and it says so, in code, deliberately, rather than guessing.** Grants and sessions are two disjoint record sets with no join key. This is the single largest gap in the governance model, and it is **honestly disclosed by the implementation itself** rather than hidden.

### 16.5 Governance discipline that is genuinely enforced by construction — class **A**

| Discipline | How it is made structural, not promised |
|---|---|
| Lifecycle ≠ authority | two arrays, never merged; `authorized` returns false without changing state (R6) |
| Absence ≠ permission | `loadRecord` refuses on a missing record: *"absence never implies ownership (C-1)"*; the resolver refuses the reasoning *"no record = ungoverned = free"* |
| Recommendation ≠ decision | `RecommendationEngine` has no decision path; decisions are a separate CLI writing a separate file |
| Observation ≠ verdict | collectors emit values with no thresholds; `Verdict::PASS_AFTER_CORRECTION/EMERGENT/CERTIFIED` throw if a machine tries to emit them |
| Discovery ≠ activation | `session-resolve.php` is **structurally** write-free (no `fopen('w')`, no `file_put_contents`, no `mkdir`/`rename`/`unlink`) — *"read purity is a property of the code, not a promise in a comment"*, pinned by test T-11 |
| One interpretation authority | the resolver **subprocesses** `workflow-state.php fold` and refuses `UNRESOLVABLE` rather than parsing records itself (T-13) |
| Ambiguity is an answer | `AMBIGUOUS` lists all candidates and chooses **none**; exit 0 |
| Producer ≠ acceptor | the durability ADR **binds its own producing process out of approving it** (R-34/P-2) |

**This is the most architecturally impressive property of EKS: refusal, ambiguity, and "I cannot know" are first-class, tested, non-error outcomes.** Very few systems get this right.

### 16.6 Executable vs documented-only governance (special question 12) — class **A/G**

| Governance rule | Executable? |
|---|---|
| Single Authority writer · humanActRef required · single mutation owner · token-bearing handoff · conjunctive START · sticky STOPPED · role immutability · work-item isolation · ACTIVE≠AUTHORIZED | ✅ **executable + tested (29 tests)** |
| Read-purity of discovery · delegation to one interpreter · interpreter identity reporting | ✅ **executable + tested (17 tests)** |
| Closed verdict vocabulary · machine-unemittable verdicts · evidence-mandatory assessments · Shared-must-not-depend-on-capability | ✅ **executable + tested (252 tests)** |
| Knowledge-document schema conformance | ⚠️ executable, **non-blocking CI**, 37 documents |
| Derived documentation placement | ⚠️ executable (9/9 self-test), **advisory hook only** |
| Identifier-collision prevention | ⚠️ executable, **2 series governed**, rest INCONCLUSIVE |
| Reference/link integrity | ⚠️ executable, **report-only**, 58 broken |
| **Registry-first workflow** (register → review → implement → verify) | 🔴 **documented only** |
| **ES-001…ES-006** (all clauses) | 🔴 **documented only, and PROPOSED** |
| **Promotion ladder** (ES-006.1) | 🔴 documented only |
| **The 14-phase Operating Loop** (`.claude/CLAUDE.md`) | 🔴 documented only |
| **EP-01/02/03 · EEP · 17-phase IMPLEMENTATION_PROTOCOL** | 🔴 documented only |
| **Six-role operating model** | 🔴 documented only (and contradicted by the 4-role runtime) |
| **Work-item acceptance / closure** | 🔴 **not even documented as a state** |

**Registry coverage (class C/D):** the registry declares **8 components and 16 assets**, all on the `.claude/` surface. The observation runtime, the metrics pipeline, the entire `EngineeringKnowledge` library, and all five knowledge/assurance CLIs are **unregistered** — so the registry-first rule is unmet for the majority of EKS code, and those components have **no recorded owner**.

**The `Decision Authority & Verification Matrix` in `STANDARDS_INDEX.md` states EKS's own verdict on this, and it is honest:** *"The smallest automation set justified by evidence: **ZERO new hooks**."* EKS has deliberately chosen documentation over enforcement, on a recorded burden-of-proof principle. **This baseline records that as a decision, not a defect** — while noting that §16.2's consequence follows from it inescapably.

### 16.7 Open governance findings carried in the registry — class **A** (recorded)

- **V-3 (AST-016, ARCHITECTURE/SPECIFICATION GAP, unresolved):** `workflow-state.php` computes the predecessor-handoff fact and gates `START` on it, but **exposes it through no read command** — so `missingForActivation` enumerates the handoff **unconditionally** and a delegating consumer cannot distinguish a recorded handoff from an absent one. Fails safe (over-reports "not ready"). **Binding condition: V-3 must be resolved before AST-016 is ever wired into `SESSION_START`.** Discharge vehicle: `KOS-ACTIVATION-REPORTING-001` — which is the **empty record** (0 transitions, 0 grants) noted in §10.3.
- **`KOS_MECHANISM_PATH`:** an environment variable that substitutes the workflow interpreter at runtime. Tier-1 source is explicit and admirably candid: *"It is NOT a hardened boundary and must not be described as one … anything that can set this environment variable selects which interpreter answers."* Mitigation is disclosure, not prevention: the resolver **reports** which interpreter answered and performs *"no legitimacy assessment, registry comparison, warning, or refusal on that basis."*

---

## 17. Assurance / Verification Architecture

### 17.1 Executable assurance — measured by re-running it in this session · tier 2 · class **A**

| Suite | Tests | Assertions | Result |
|---|---|---|---|
| `tests/Unit/Platform/WorkflowEngine/` (R1–R8 record contract, T-1–T-15 resolver contract, 65/69 replay) | **29** | **312** | ✅ **OK** |
| Observation runtime (`ChangeSet`, `ObservationRuntime`, `RecommendationEngine`, `RecommendationInbox`, `AssessmentService`, `OutcomeRecorder`, `Lcom4Collector`, `KnowledgeOsDoctor`, `KnowledgeOsInitPlanner`) | **43** | **125** | ✅ **OK** |
| `--testsuite=EngineeringKnowledge` (4 capabilities + Shared) | **252** | **603** | ✅ **OK** |
| **Total EKS executable assurance** | **324** | **1,040** | ✅ **all green** |

**This is real, substantial, deterministic assurance, and it is the single strongest piece of evidence in this baseline.**

### 17.2 What "deterministic assurance" actually means in EKS (special question 11) — class **A**

Determinism is a **designed and tested property**, not an aspiration:

| Property | Where it is guaranteed |
|---|---|
| Identical `ChangeSet` + identical sources → identical recommendations, **regardless of trigger** | `ObservationRuntime` header; enforced by injecting the file reader; tested |
| The runtime **never scans the repository** — changed files only | `ObservationRuntime::run` |
| Rules are **data**, not code | `recommendation-rules.yaml`; *"the engine knows no tool, no metric source, no threshold philosophy"* |
| Recommendation ids are **content-derived** (`sha1(rule|subject)`) → stable, dedupable | `RecommendationEngine::evaluate` |
| Assessment thresholds are **explicit constants** with stated rationale (`MIN_COMMITS=3`, 20%, 2%) | `AssessmentService` |
| Verdicts are a **closed enum**; three values are structurally unemittable by machine | `Verdict::emittable()` + `Assessment::of()` throw |
| An assessment **cannot exist without evidence** | `Assessment::of()` throws on blank evidence (DP-5) |
| **Independent-implementation agreement** as an evidence technique | `lcom4_collector.py` — a deliberately non-ported Python implementation written from the pinned `expected.json` spec, *"to test whether the written contract is sufficient, not transliteration"* (`KOS-CONTRACT-NEUTRALITY-001`) |
| Checker provenance on every report | `CheckerVersion::CURRENT` + `git rev-parse --short HEAD` or `UNKNOWN` + timestamp + command line |

**That last row is a genuinely sophisticated assurance practice**: EKS tests its own *specification* by re-implementing a collector in a different language with a different parsing strategy and checking that the two agree.

### 17.3 ⭐ The gating gap — tier 1 · class **D**

| CI workflow | Owner | EKS? | Blocking? |
|---|---|---|---|
| `greenfield-merge-gate.yml` → `composer merge-gate` | product | ❌ | ✅ blocking |
| `greenfield-quality-tier.yml` | product | ❌ | ⚪ measures |
| `membership-architecture.yml` | product | ❌ | ✅ blocking |
| `regression-detector.yml` | product | ❌ | ⚪ alerts |
| `role-permission-verification.yml` | product | ❌ | ✅ blocking |
| **`knowledge-lint.yml`** | **EKS** | ✅ | 🔴 **`continue-on-error: true` on both steps — cannot fail the build** |

- `composer merge-gate` runs `--testsuite=Architecture` + Deptrac + PHPStan + `--testsuite=GreenfieldCore`. **It does not run `--testsuite=EngineeringKnowledge`.**
- The 324 EKS tests run only when a human or agent types the command.
- Local git hooks: **`.git/hooks` empty · `core.hooksPath` unset · `.husky/_` empty ⇒ no git hook is installed at all.** The `.husky/pre-commit`, `pre-push`, `post-commit` scripts exist as version-controlled *source* and are **not wired**. (Note: `pre-commit` and `pre-push` are product design-system gates, not EKS.)

> **EKS's assurance is excellent and ungated. The product's assurance is gated. EKS holds itself to a lower operational standard than it holds the product to.** Class **D**.

### 17.4 Assurance coverage of the corpus — class **C**

| Check | Coverage measured |
|---|---|
| `knowledge-lint.php` | **37 governed documents**, all PASS — out of **132** files under `docs/knowledge/` and well over a thousand governed markdown files across the estate |
| `identifier-check.php --series` | **2 governed series** (`R`, `PMR`). *"Every other series returns INCONCLUSIVE. That is correct behaviour, and it measures G-1 (no canonical register list) rather than hiding it."* |
| `link-check.php` | **58 broken references**: 50 missing · 6 ambiguous · 2 git-rename. Report-only; `--apply` repairs only confidence ≥ 99 (the 2 renames). |
| `doc-placement.php` | **9/9 self-test cases pass**; `--verify` available. Advisory. |
| Handoff assurance report (`--report=handoff`) | Phase-1, **warn-only, always exit 0** by design (D-4) |

### 17.5 Assurance philosophy — the authority boundary (class **A**)

Enforced by construction, and stated identically in three independent places: **AP-1 — *"knowledge feeds authority, it never holds it."*** An assurance run produces an `Assessment`; an `Assessment` *"confers no authority"*; `PASS AFTER CORRECTION`, `EMERGENT`, and `CERTIFIED` belong to **human review and certification acts** and throw if code attempts them. This is the cleanest separation of concerns anywhere in EKS.

---

## 18. Integration and Dependency Architecture

### 18.1 Integration mechanisms — tier 1 · class **A**

| # | Mechanism | Kind |
|---|---|---|
| I1 | The filesystem | shared state |
| I2 | Git (as a CLI subprocess: `rev-parse`, `diff-tree`, `config`) | tool invocation |
| I3 | `proc_open('php', <script>, 'fold')` — the resolver → engine delegation | **the only inter-component protocol in EKS** |
| I4 | `shell_exec`/`require_once` between observation scripts | in-process |
| I5 | Composer autoload (`EngineeringKnowledge\` PSR-4) | in-process |
| I6 | AI-harness hooks (`.claude/settings.json`) | inbound trigger |
| I7 | GitHub Actions | outbound gate (one job, non-blocking) |
| I8 | `KOS_MECHANISM_PATH` env var | runtime interpreter selection |
| I9 | Prose cross-references between records and documents | **human-resolved only** |

**No network integration. No API. No serialization contract between components other than the JSON that `fold` prints on stdout.**

### 18.2 Dependency direction — tier 1 · class **A** (verified, acyclic)

```text
                    tests (324)
                        │ verifies
                        ▼
  session-resolve.php ──subprocess──►  workflow-state.php  ──►  .claude/runtime/*.json
  (AST-016, read-only)   (the ONLY      (AST-015, sole
                          protocol)      interpreter)
                                              ▲
                                              │ writes
                                     governance / human / AI processes
                                              │ invoke
  ─────────────────────────────────────────────────────────────────────────────
  triggers ──► ChangeSet ──► ObservationRuntime ──► Collectors ──► facts
                                    │                                │
                                    └──► RecommendationEngine ◄───────┘
                                              ▲ rules-as-data
                                              │
                                    recommendation-rules.yaml
  ─────────────────────────────────────────────────────────────────────────────
  CLI adapters (knowledge-lint · identifier-check · link-check)
        └──► EngineeringKnowledge\Capabilities\*  ──►  Shared\Domain
                                                       ⛔ Shared MUST NOT depend
                                                          on any capability
                                                          (enforced by carrying the
                                                           subject as an opaque string —
                                                           "discovered by the type system")
```

**Verified properties:**
- **No cycle** in any EKS dependency path examined.
- **`Shared` → capability dependency is structurally prevented**, and the source records *why* the signature looks the way it does: the class was extracted while still typed against `IdentifierIntegrity\Identifier`, the type system caught it, and the fix was to make the subject an opaque string. **That is a dependency rule discovered by compilation, not by review** — the strongest kind.
- **Single interpretation authority**: nothing but `workflow-state.php` interprets a workflow record. AST-016 refuses `UNRESOLVABLE` rather than falling back.

### 18.3 External dependencies — tier 1

`php` ≥ 8.2 (running 8.5.8) · `symfony/yaml` · `nikic/php-parser` · `phpunit` 11.5.6 · `git` · `bash` · `python3` (one collector) · `pdepend` (metrics) · GitHub Actions. **EKS adds no dependency of its own** — every library it uses was already a product dependency, and the Python collector explicitly notes that installing a parser *"is forbidden by the grant"*.

### 18.4 ⛔ The coupling that matters most — class **D**

**EKS is not a separable system.** It shares:

| Shared with the product | Consequence |
|---|---|
| the **same git repository** | EKS cannot be versioned, released, or branched independently |
| the **same `composer.json` autoload** (`EngineeringKnowledge\` sits beside `App\`) | EKS cannot be depended on as a package |
| the **same `phpunit.xml`** | EKS's suite is one testsuite among the product's |
| the **same `vendor/`** | EKS's dependency choices are the product's |
| the **same CI runner and workflows directory** | EKS's one job sits among five product jobs |
| the **same `.gitignore`** | EKS's durability is decided by a product config file |

**Reuse in a second repository is therefore not demonstrated and, in the current shape, not possible without extraction.** Class **D — recorded, not remedied.**

### 18.5 ⭐ Operational state — first-hand, measured this session · class **D**

| Stream | Last recorded activity | Dormant for |
|---|---|---|
| `lcom4.jsonl` | **2026-08-03** 22:09 | 18 days |
| `recommendations.jsonl` | **2026-08-04** 00:01 | 17 days |
| `outcomes.jsonl` | **2026-08-04** 15:20 | 17 days |
| `assessments.jsonl` | **2026-08-04** 15:26 | 17 days |
| `watch-usage.jsonl` | **2026-08-04** 20:00 | 17 days |
| `test-presence.jsonl` | **2026-08-04** 20:49 | 17 days |
| `trend.jsonl` | 3 snapshots, 2 distinct commits | — |
| — | — | — |
| `.claude/runtime/workflow/` | **2026-08-21** 20:29 | **active today** |
| `.claude/sessions/` | **2026-08-21** | **active today** |

> **The observation/assurance loop has produced nothing for 17 days. The governance/workflow loop has run daily.** Both are class-A implemented. Only one is in use. **The subsystem that is durably persisted is the one that is dormant; the subsystem that is untracked is the one that is alive.**

---

## 19. Runtime / Deployment Architecture

### 19.1 Deployment — class **E**

**EKS has no deployment.** No Dockerfile, no image, no manifest, no service definition, no deploy job, no environment, no configuration management, no versioning, no release artifact. EKS "deploys" by being present in a git checkout.

### 19.2 Runtime topology — class **A**

```text
ONE developer workstation
└── ONE git working copy
    ├── php CLI invocations         ephemeral, single-process, no daemon
    ├── an AI-harness process       triggers hooks; holds the injected knowledge in context
    └── .claude/runtime/            the estate's authoritative governance state
                                    — local, untracked, unreplicated, unbackuped
```

**There is exactly one instance of EKS's authoritative state in existence**, and it is on one machine, excluded from version control.

### 19.3 Execution moments — tier 1 · class **A**

`registry.yaml` declares a normalized enum: `SESSION_START · PRE_ACTION · POST_ARTIFACT · SESSION_END · ON_DEMAND`. Both workflow assets are `ON_DEMAND` — deliberately: AST-016's `runtime_moments` note records the **binding condition** that it must not be wired to `SESSION_START` until V-3 is resolved (*"wired in, V-3's false line would be read by every session at every start"*). **That is exactly the right call, correctly recorded.**

### 19.4 Configuration — class **C**

| Config | Location | Governed? |
|---|---|---|
| recommendation rules | `scripts/observations/recommendation-rules.yaml` | ✅ data-driven |
| knowledge schema (10 files) | `docs/knowledge/schema/*.yaml` | ✅ data-driven |
| documentation placement | `docs/knowledge/schema/documentation-placement.yaml` | ✅ data-driven |
| metrics thresholds | `scripts/metrics/metrics-config.yaml` | ✅ |
| hooks | `.claude/settings.json` + `settings.local.json` | ⚠️ AST-001; `settings.local.json` is local-only |
| components/assets | `.claude/platform/registry.yaml` | ✅ AST-009 |
| governed identifier registers | hard-coded in `GovernedRegisterMap` | ⚠️ 2 series only |
| **assessment thresholds** | **hard-coded constants** in `AssessmentService` | 🔴 code, not config |
| **interpreter path** | `KOS_MECHANISM_PATH` env var | 🔴 unhardened by design |
| **durability of governance state** | `.gitignore` line 25/32 | 🔴 a product config file decides EKS durability |

---

## 20. Architectural Patterns Actually Present

Patterns are listed **only** where tier-1/2 evidence demonstrates them.

| Pattern | Where | Class |
|---|---|---|
| **Event sourcing (degenerate / single-stream)** | append-only `transitions[]`; state = fold; nothing derived is persisted | **A** — the purest architectural pattern in EKS. *Degenerate* because there is no event type, no publication, and no projection store. |
| **CQRS-ish read/write split** | `append`/`grant` (write) vs `fold`/`identity`/`authorized` (read); reads recompute, never cache | **A** |
| **Two never-merged records in one consistency boundary** | `transitions` (evidence) ≠ `grants` (authority), different writers, different rules | **A** — unusual and deliberate |
| **Hexagonal / Ports & Adapters** | `EngineeringKnowledge`: `Domain` ← `Application` (ports: `*ContentsReader`) ← `Infrastructure` (`Markdown*Reader`, `Yaml*Source`, `Cli*`) | **A** — textbook, and the only place in EKS with a real tactical model |
| **Rules as data** | `recommendation-rules.yaml`; `knowledge-schema.yaml`; `documentation-placement.yaml` | **A** |
| **Pipeline** | `Trigger → ChangeSet → Runtime → Collectors → facts → Engine → Recommendations` | **A** |
| **Adapter-per-trigger with a neutral input DTO** | `ChangeSet` is *"NOT GitCommit, NOT VSCodeFile, NOT PullRequest"* | **A** |
| **Delegation to a single interpretation authority** | resolver subprocesses the engine; refuses rather than re-implementing | **A** — pinned by T-13 |
| **Structural read-only** | write-purity as a *property of the code*, byte-verified, pinned by T-11 | **A** |
| **Closed vocabulary at the boundary** | `Verdict` enum with a machine-emittable subset | **A** |
| **Contract tests over implementation shape** | R1–R8 exercise the CLI, asserting no namespace, class, or storage layout | **A** |
| **Independent re-implementation as specification assurance** | `lcom4_collector.py`, deliberately not a port | **A** |
| **Registry-first identity** (`CMP-nnn`/`AST-nnn`; *"paths change; ids never do"*) | `registry.yaml` | **C** — the convention is followed for 16 assets and ignored for the rest of EKS |
| **Advisory-only instrumentation** | every hook exits 0; every check is warn-only | **A** (and see §16.2) |
| **Anti-corruption by opaque type** | `Assessment::subject` is a string so `Shared` cannot depend on a capability | **A** |
| Aggregate / Repository / Domain Event / Saga / Projection store / Outbox / Bus | — | **E — absent** |
| Microservices / Layered service architecture / Event-driven architecture | — | **G — proposed only** |

**Duplication finding (class D):** **three independent LCOM4 implementations coexist** — `scripts/observations/Lcom4Collector.php` (in the live pipeline, uses `nikic/php-parser`), `scripts/observations/lcom4_collector.py` (the evidence experiment, own scanner), and `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/**` (the newest, hexagonal, best-modelled — `UnitKind`, `EdgeRules`, `CohesionGraph`, `Lcom4`, `Interpretation`). **The newest is called only by its own tests** (`tests/Unit/Cohesion/`); the pipeline still uses the oldest. Which is canonical is recorded nowhere.

---

## 21. Special Questions

> The baseline's body cites **six "special questions"** posed by the producing session and answered in place (§7.6c · §11.5 · §16.2 · §16.3 · §16.6 · §17.2). The numbered list these questions were drawn from is **not preserved anywhere in the corpus** — the numbers **4 · 5 · 6 · 8 · 11 · 12** survive only as those citations. The other numbers (1–3 · 7 · 9 · 10 · 13+) are **UNKNOWN** (§24 U-18). Each question is consolidated here with its answer, its evidence class, and its home section. No new question is invented.

### 21.1 SQ4 — Where is authority actually enforced? (§16.2) — class **A** (enforcement) / **D** (scope)

**Answer:** Nowhere against engineering work; in exactly four places against the record itself — (1) only `governance` may write a grant (`workflow-state.php:333`, R5a); (2) a grant must carry a `humanActRef` (`:336`, R5b); (3) only the current mutation owner may hand off (`:225`, R1); (4) only `governance`/`human` may record `COMPLETE` or `CONTINUATION` (`:262`, `:249`, R4). Every one is a **precondition on a write to the JSON file**, evaluated by a CLI the actor chose to run, with the writer role supplied as a **command-line flag**.

> **Enforcement scope, stated precisely: EKS enforces the well-formedness of its own record. It enforces nothing about engineering.** The tier-1 source says so in its opening comment: *"the fold refuses; nothing is physically prevented."*

### 21.2 SQ5 — Grant vs record authority (§16.3) — class **A**

**Answer:** **The system RECORDS authority. It does not GRANT authority.** Verbatim in tier-1 source: *"a grant registers a recorded human act by reference — the record never manufactures authority (G-2/R5b)"*. Corroborated by measurement: **126/126** grants carry a `humanActRef`, and **0/126** carry anything the mechanism could have generated as authority itself.

### 21.3 SQ6 — Does governed work have an end? (§7.6c) — class **E**

**Answer:** **No.** `workItemState` has exactly two reachable values: `OPEN` and `STOPPED`. There is no `CLOSED`, `COMPLETED`, `ACCEPTED`, or `CANCELLED` work-item state. Measured consequence: **34 `COMPLETE` transitions** have been recorded, and **17 of 18 work items still fold to `OPEN`**; one folds to `STOPPED`. **The lifecycle of governed work has no recorded end.** (See §22 G-8.)

### 21.4 SQ8 — What is persisted vs derived? (§11.5) — class **A**

**Answer:** A clean and unusually disciplined separation. **Persisted:** `schema`, `workItem`, `workflow`, `roles[]`; `transitions[]` (verbatim, append-only, `seq`-stamped); `grants[]` (verbatim, append-only); every JSONL row; the markdown/YAML corpus. **Derived, never stored:** `sessions{}` and each session's `state`; `mutationOwner`; `workItemState`; `handoffsTo{}`; `authorizationLinkage`; `authorized`; every resolver verdict, reason and `authorizationFact`; all lint/check output. *"The record holds facts; every interpretation is recomputed on demand."* It is the strongest single design property in EKS, and it is what makes the fold trustworthy.

### 21.5 SQ11 — What does "deterministic assurance" actually mean in EKS? (§17.2) — class **A**

**Answer:** Determinism is a **designed and tested property**, not an aspiration: identical `ChangeSet` + identical sources → identical recommendations regardless of trigger (tested); the runtime never scans the repository (changed files only); rules are **data**, not code; recommendation ids are content-derived (`sha1(rule|subject)`); assessment thresholds are explicit constants with stated rationale; verdicts are a **closed enum** with three values structurally unemittable by machine; an assessment **cannot exist without evidence**; and EKS tests its own *specification* by independent re-implementation in another language (`lcom4_collector.py`, deliberately not a port).

### 21.6 SQ12 — Executable vs documented-only governance (§16.6) — class **A/G**

**Answer:** Nine governance rules (single authority writer · humanActRef required · single mutation owner · token-bearing handoff · conjunctive START · sticky STOPPED · role immutability · work-item isolation · ACTIVE≠AUTHORIZED) are **executable + tested (29 tests)**; read-purity/discovery and one-interpreter rules are executable + tested (17 tests); the closed-verdict vocabulary is executable + tested (252 tests). Everything else — the registry-first workflow, **ES-001…ES-006 (all PROPOSED)**, the promotion ladder, the 14-phase Operating Loop, EP-01/02/03, the six-role operating model, work-item acceptance/closure — is **documented only**. The `Decision Authority & Verification Matrix` in `STANDARDS_INDEX.md` states EKS's own verdict: *"The smallest automation set justified by evidence: ZERO new hooks."* This baseline records that as a **decision, not a defect** — while noting that §16.2's enforcement-scope consequence follows from it inescapably.

---

## 22. Known Gaps and Open Areas

> This section delivers the plan's Stage-1 required terminal section **"Known Gaps"** (the "Contradictions" half lives in §25). Each gap carries a stable id, class, evidence, and its detailed home.

| ID | Gap | Class | Evidence | Detailed |
|---|---|---|---|---|
| **G-1** | **No canonical identifier-register list** — `identifier-check.php --audit` returns INCONCLUSIVE for every series except `R` and `PMR` | **E** | tier 1 | §17.4 |
| **G-2** | **EKS enforces nothing about engineering** — no edit/commit/push/merge/deploy gated by any grant, ownership, or transition | **D** | tier 1 | §16.2 |
| **G-3** | **No temporal ordering of authority** — 2/218 transitions (0.9%) carry any time-like field; ordering by per-record `seq` alone | **E** | tier 1,3 | §11.3 |
| **G-4** | **No git history for the runtime** — `git log -- .claude/runtime/` is empty; insertion cannot be dated, attributed, or diffed | **E** | tier 1 | §11.2 |
| **G-5** | **Single-host, no backup, no replication, no integrity check** for the estate's authoritative governance state | **E** | tier 1 | §11.2 |
| **G-6** | **The scoped-subset problem** — two "current state" measurements of the same corpus cannot be reconciled (undefined inclusion rule) | **D** | tier 1,3 | §25.3 |
| **G-7** | **Grant identity not enforced unique** — 1 duplicate measured (`G-KOS-GOVGAPS-VERIFY`) | **E** | tier 1,3 | §12.2 |
| **G-8** | **Work-item lifecycle has no terminal state** — 17/18 fold to `OPEN`; 34 `COMPLETE` transitions recorded | **E** | tier 1,3 | §21.3 |
| **G-9** | **Registry-first rule unmet for the majority** — 8 components + 16 assets registered; the observation runtime, metrics pipeline, `EngineeringKnowledge` library, and five knowledge/assurance CLIs are unregistered, with no recorded owner | **C/D** | tier 5 vs tier 1 | §16.6 |
| **G-10** | **Observation/assurance loop dormant 17 days** (2026-08-04 → 2026-08-21); governance/workflow loop active daily | **D** | tier 3 | §18.5 |
| **G-11** | **EKS is not a separable system** — shares repo, autoload, phpunit, vendor, CI, `.gitignore` with the product; reuse in a second repository not demonstrated | **D** | tier 1 | §18.4 |
| **G-12** | **No deployment** — no Dockerfile, no service, no CI deploy, no release artifact; EKS "deploys" by being present in a git checkout | **E** | tier 1 | §19.1 |
| **G-13** | **Reference integrity** — **58 broken references** (50 missing · 6 ambiguous · 2 git-rename); report-only | **C** | tier 1 | §17.4 |
| **G-14** | **`KOS_MECHANISM_PATH` is an unhardened write-capable interpreter-substitution path** — reporting only, no legitimacy assessment, no refusal | **D** | tier 1 | §25.1 X-6 |
| **G-15** | **Five shell scripts hold ephemeral reminder/session state in `.claude/runtime/`** — the runtime directory legitimately holds both ephemera and authority evidence | **C** | tier 1 | §25.1 X-5 |
| **G-16** | **The durability of governance state is decided by a product config file** (`.gitignore` lines 25/32) | **D** | tier 1 | §19.4 |

**Previously-unread-artifact finding (P3-F1 lesson):** **G-14 and G-15 appear nowhere in §1–§20**; both are current-state facts folded in from the exhaustively-read `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` (§1.5 CL-10 · §1.6). G-6 is the scoped-subset problem (§25.3). These are corpus-boundary decisions recorded as findings, never silent choices.

---

## 23. Propositions (P-rows)

> The register of the baseline's own **propositions** — stated findings that are not accepted/ratified. **P-7** is the row the body forward-references (§5.1). **P-2** is forward-referenced at §16.5 (`R-34/P-2`). The remaining rows consolidate §1–§20's key findings under stable ids. A proposition records *what is*, with its class; it claims no remediation.

| ID | Proposition | Class | Source |
|---|---|---|---|
| **P-1** | EKS enforces the well-formedness of its own record and **nothing about engineering**; the fold refuses, nothing is physically prevented | **A** | §16.2 |
| **P-2** | **Producer ≠ acceptor** — the durability ADR binds its own producing process out of approving it (`R-34/P-2`) | **A** | §16.5 |
| **P-3** | The record holds facts; every interpretation is recomputed on demand (persisted vs derived) | **A** | §11.5 · §21.4 |
| **P-4** | Two never-merged records in one consistency boundary: `transitions` (evidence) ≠ `grants` (authority) | **A** | §7.6 · §20 |
| **P-5** | EKS's assurance is excellent and **ungated**; the product's is gated — EKS holds itself to a lower operational standard than it holds the product to | **D** | §17.3 |
| **P-6** | The subsystem that is durably persisted is the one that is dormant; the subsystem that is untracked is the one that is alive | **D** | §18.5 |
| **P-7** | The **single-writer rule is a recorded constraint, not an authenticated one** — `--writer-role` is a command-line flag; anything that can run the CLI can pass `--writer-role=governance` | **D** | §5.1 |
| **P-8** | Density of `seq` is **not** a completeness proof — a lost transition leaves a perfectly dense sequence (§25.2 E-1) | **D** | MIGRATION-PLAN §1.2 |

---

## 24. UNKNOWN Register (U-*)

> Every UNKNOWN the corpus leaves open. **An honest UNKNOWN is valid; an artificial UNKNOWN is a defect** (P3-F1 lesson). **U-4** is the row the body forward-references (§11.4). No entry is filled to make the reconstruction coherent.

| ID | UNKNOWN | Source | Status |
|---|---|---|---|
| **U-1** | **Bounded contexts NOT ESTABLISHED** — BC-1..BC-7 are boundary candidates only; none is promoted; no formal DDD validation exists | §9 | OPEN |
| **U-2** | **Aggregates NOT established** — the one-work-item file is the clearest boundary but "never named as one" (class B); no aggregate is named in code or docs | §8 | OPEN |
| **U-3** | **Current C4 model UNKNOWN / CONTESTED** — no current EKS container model exists; only a stale (2026-07-27) self-disclaiming reference model and quarantined proposed diagrams | §9.2 | OPEN |
| **U-4** | **EKS/PKS identity ambiguity** — the 2032 baseline attributes the "Git-backed" characterization to the *PKS corpus*; whether EKS and PKS are the same, different, or overlapping systems is UNRESOLVED by EKS evidence | §11.4; P2 baseline §24 U-02 | OPEN |
| **U-5** | **Delegation semantic interpretation UNKNOWN** — grants narrow over time, but the business concept *Delegation* is NOT established; 0/126 grants carry a delegation/delegator/delegatee/parent-grant/validity/expiry field | §7.9 | OPEN |
| **U-6** | **Knowledge-corpus (BC-5) ownership PENDING ARB** — ES-006 lists `docs/knowledge/` disposition as PENDING | §9 | OPEN |
| **U-7** | **Grant lifecycle closure unexercised** — the recommendation lifecycle was exercised once (10 → 2 → 1 → 1); 8 recommendations open since 2026-08-04; no grant closure observed | §13.5 | OPEN |
| **U-8** | **Work-item terminal state absent** — `workItemState` has exactly two reachable values; what "finished" means for a work item is not recorded | §7.6c | OPEN (see §21.3) |
| **U-9** | **Authorization query unanswerable** — EKS cannot answer "is this actor authorized to do this thing?"; grants and sessions are disjoint record sets with no join key; 2 of 6 AuthorizationFacts permanently UNKNOWN by construction | §16.4 | OPEN |
| **U-10** | **Process/actor authenticity UNKNOWN** — AI processes are first-class actors but their identity cannot be established by any mechanism; *"self-declared process identity is not independently attested authorship"* (EKS-07 unsolved) | §5.4 · §7.8 | OPEN |
| **U-11** | **Temporal authority unreconstructable** — "was this authority valid at a historical point?" is unanswerable (0/20 observed grants carry validity; 2/218 baseline) | §11.3; what_eks_today §20.1 | OPEN |
| **U-12** | **Rule and authority mechanisms not connected** — no observed grant references a Rule and no Rule references a grant | what_eks_today §20.4 | OPEN |
| **U-13** | **Evidence addressing partially mutable** — 7/20 observed grants reference artifacts descriptively rather than immutably; evidence provenance can drift | what_eks_today §20.3 | OPEN |
| **U-14** | **What the observation loop's dormancy means** — the operational state is recorded (§18.5), not interpreted | what_eks_today §20.5 · §18.5 | OPEN |
| **U-15** | **Persistence topology** — PostgreSQL/graph/vector as EKS system of record is PROPOSED, not current (zero implementation evidence) | §11.4; 60-section draft §58 | OPEN |
| **U-16** | **Cross-repo topology** — reuse in a second repository not demonstrated; extraction not possible in current shape | §18.4 | OPEN |
| **U-17** | **Terminology migration** — 8 overloaded terms across candidate boundaries (`Session`, `Assessment`, `Verdict`, `Decision`, `Evidence`, `Rule`, `Capability`, `Workflow`) | §6.2 · §9.1 | OPEN |
| **U-18** | **The special-question list** — the numbered list SQ1–SQ3, SQ7, SQ9, SQ10, SQ13+ is not preserved anywhere in the corpus; only SQ4 · SQ5 · SQ6 · SQ8 · SQ11 · SQ12 survive as citations | §21 | OPEN |

---

## 25. Contradiction Register (X-*)

> Every EKS-internal contradiction surfaced — implementation vs documentation, proposal vs implementation, current vs historical, measurement vs measurement. **Never silently reconciled — both sides are recorded.**

### 25.1 Contradictions

| ID | Contradiction (concepts · evidence · nature) | Sources | Status |
|---|---|---|---|
| **X-1** | **System of record** — "Git-backed repository" (2032 baseline, tier 5, attributed to the PKS corpus) vs KnowledgeOS proposals (PostgreSQL/graph/vector, tier 7) vs measured (tier 1,3): **two systems of record with opposite durability** (a git-tracked corpus AND an untracked local JSON governance store). "Git-backed" is half true and materially misleading — accurate for P2–P10, false for P1, which holds the authority record | §11.4 | **OPEN — UNRESOLVED for Stage 2** |
| **X-2** | **Six-role vs four-role operating model** — six-role (Governance · Architecture · Implementation · Verification · Knowledge · Communication) adopted in docs (tier 4); the runtime knows four; `knowledge` and `communication` appear in **no** workflow record | §5.3 | **OPEN — CONTRADICTED** |
| **X-3** | **ES-001…ES-006 PROPOSED vs bound-as-canonical** — all six are PROPOSED, awaiting ARB review 41 days after consolidation was ordered (2026-07-11); yet `.claude/CLAUDE.md` binds them as "canonical rules" at runtime and the project CLAUDE.md cites ES-004.2/005.4/006.1 as governing rules | §13.3 | **OPEN — CONTRADICTED** |
| **X-4** | **Density not a completeness proof** — §8 claimed "a lost transition is mechanically detectable as a gap"; the fully-read corpus's concurrent-append experiment lost **23/30** trials while every survivor stayed dense and monotonic; *"the clobbering writer reuses the sequence number the lost writer took"* → **E-1 erratum applied** | §8; MIGRATION-PLAN §1.2 | **OPEN — erratum E-1 (§25.2)** |
| **X-5** | **"No runtime state in the runtime directory"** — the durability ADR infers the `.claude/runtime/` artifacts are "not runtime state" (scoped to the workflow JSON files); MIGRATION-PLAN §1.6 shows **five shell scripts** legitimately hold ephemeral reminder/session state there. Both are true at different scopes; no source states the boundary | ADR §3–4; MIGRATION-PLAN §1.6 | **OPEN — scoping note** |
| **X-6** | **`KOS_MECHANISM_PATH` framing** — §16.7 presents it as "not a hardened boundary" (interpreter substitution, reporting); the fully-read corpus upgrades it: **it is a WRITE-CAPABLE path** (CL-10) — *"worse than 'the right bytes read by the wrong mechanism'"*. Both agree it is unhardened; the write-capability is the newly-read fact | §16.7; MIGRATION-PLAN §1.5 CL-10 | **OPEN** |
| **X-7** | **Commit-trigger not wired** — a version-controlled post-commit hook exists (`.husky/post-commit` → `scripts/observations/git-hooks/post-commit`); `.git/hooks` empty · `core.hooksPath` unset · `.husky/_` empty → **NOT INSTALLED** | §5.5 | **OPEN — CONTRADICTED** |
| **X-8** | **Two "current state" measurements of the same corpus disagree** — this baseline (2026-08-21): 18 records / **218** transitions / **126** grants / 125 unique; MIGRATION-PLAN AMD3 (2026-08-20): 18 / **216** / **110** / 109 unique; MIGRATION-PLAN AMD6 (2026-08-21): 18 / 216 / **114** / 113 unique. The delta is plausible corpus growth but **unattestable** (git log empty) | §10.2; MIGRATION-PLAN §1.1, AMD6 | **OPEN — REQUIRES FUTURE VALIDATION** |
| **X-9** | **"13/20 immutable commit addressing" split not reproduced** — what_eks_today §1.3 claims 13/20 immutable / 7/20 descriptive (20-grant scoped subset); direct re-measurement this session produces **3/17 or 16/4** under two defined rules; the claim's defining rule is undefined | what_eks_today §1.3; this session | **OPEN — REQUIRES FUTURE VALIDATION** |

### 25.2 Errata (E-*)

| ID | Erratum | Applied at | Evidence |
|---|---|---|---|
| **E-1** | §8's mitigation claim ("seq is dense and monotonic, so a lost transition is mechanically detectable as a gap") is **contradicted by the fully-read corpus**: 23/30 concurrent-append trials silently lost a transition while every survivor remained dense and monotonic — *"DENSITY IS NOT A COMPLETENESS PROOF. It is preserved BY the loss, because the clobbering writer reuses the sequence number the lost writer took."* A lost transition is **NOT** mechanically detectable as a gap. Smallest-scope erratum — the original sentence is preserved verbatim; this record is the correction. | §8 (inline) · §25.2 | MIGRATION-PLAN §1.2 (tier 1,3); re-verified this session |

### 25.3 The scoped-subset problem (cited §11.2)

**Not resolvable by any mechanism.** A "current state" measurement of the runtime corpus is meaningful only if the measured set and its defining rule are stated and reproducible. Neither is attestable:

- **Scope drift:** what_eks_today §1.3's "13/20" (X-9) is a **20-grant scoped subset**; this baseline's §10.2 measures the **full estate** (126 grants). The two are not comparable without a defined inclusion rule.
- **No history:** `git log -- .claude/runtime/` is empty (§11.2) — a criterion pinned to "18 / 216 / 110" or "126 grants" cannot be re-derived at any later date, and cannot distinguish *"a record is missing"* from *"the corpus legitimately grew"* (MIGRATION-PLAN §1.1). The corpus grew during the ADR, the design, the planning, and the reviews.
- **Consequence:** §11.2's point 4 — *"The scoped-subset problem is now unresolvable"* — stands. Any Stage-2 comparison that relies on an absolute record count must first pin the set and the rule, or record the comparison as **UNKNOWN** (§24).

---

## Traceability and corpus record

- **Preserved verbatim:** §1–§20 of this baseline. The git diff for this completion shows **additions/errata only** — the banner (completion record, not a repair), the §8 E-1 erratum block, and the appended §21–§25 + this traceability record.
- **Completed in place by R-1 (this session, 2026-08-22):** §21 Special Questions (SQ4 · SQ5 · SQ6 · SQ8 · SQ11 · SQ12) · §22 Known Gaps and Open Areas (G-1..G-16) · §23 Propositions (P-1..P-8) · §24 UNKNOWN register (U-1..U-18) · §25 Contradiction register (X-1..X-9) · Errata (E-1) · §25.3 Scoped-subset problem.
- **Sources:** the AMENDMENT-4 binding corpus (60-section draft `docs/knowledgeos/brainstorming/20260801_1231_EKS Current Architecture Baseline.md` · `docs/knowledgeos/brainstorming/20260821_2033_what_eks_today.md` · `docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md`), plus the exhaustively-read `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` (P3-F1 lesson) and direct tier-1/3 measurements executed in this session against `.claude/runtime/workflow/*.json`, `.claude/scripts/workflow-state.php`, `.claude/scripts/session-resolve.php`, `engineering/governance/STANDARDS_INDEX.md`, `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-ADR.md`.
- **Quarantined (never EKS current-architecture evidence):** the proposed KnowledgeOS architecture — Review Set `docs/knowledgeos/architecture/00…07-*`, kernel-extraction conclusions, `docs/knowledgeos/brainstorming/20260821_2032_how_to_change_eks_into_kowledge_os_kernel.md` (Stage-4/5 material only), the Digitalization Robot. The Review Set is never used to interpret EKS current architecture.
- **Quality test — every substantive claim in this document carries (a) an evidence tier (1–8), (b) a reality class A–G, or (c) a stable U-/X-/P-/G- register reference.** No B/C/G finding is promoted to A. UNKNOWN remains UNKNOWN. Contradictions are surfaced, never silently reconciled. **EKS is frozen — read-only; no redesign, no frozen-baseline reopening, no technology change.**
