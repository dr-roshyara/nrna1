# Plan — AI Engineering Platform · Platform Construction · Iteration 1

**Created:** 2026-07-08
**Status:** APPROVED WITH AMENDMENTS (ARB, 2026-07-08) — executing

**ARB amendments (binding, all applied):**
1. Registry is **machine-readable** (`registry.yaml`, runtime configuration) — human docs generated from it later, never the reverse.
2. `run-gates.sh` is the **first implementation of the Verification Engine**, never the engine itself — abstraction stays technology-independent (encoded in registry `components.verification_engine.implementations`).
3. Loading order: **configuration → registry → knowledge → rules → hooks → commands → agents** (knowledge before rules).
4. REUSE replaced by **ADOPT / VERIFY / DEPRECATE** — every existing asset earns adoption via a recorded VERIFY evaluation; nothing joins the Baseline automatically.
5. **OI-1 does not block construction** — C1 proceeds on current vocabulary; OI-1 renames apply by supersession.

**Standing iteration question (ARB):** every iteration ends by answering — *did the platform make implementing PB-004 easier, safer, and more architecturally consistent than before?* If no: improve the existing, add nothing.

**Iteration-close protocol (AIP-14 / ADR-AIP-02, 2026-07-08) — answered with evidence at every iteration boundary; unconvincing answers reject the iteration:**
1. What became simpler? 2. What became safer? 3. What became more deterministic? 4. What complexity did we remove? 5. What complexity did we add? 6. Was the addition justified by a PublicDigit feature (currently PB-004)?

**Platform Cost ledger (derived, never asserted — ER-05's concrete measure):**

| Measured at | Components | Assets | Scripts | Hook wirings | Commands | Agents | Registry lines |
|---|---|---|---|---|---|---|---|
| Iteration 1 / C1 (2026-07-08, AIP-14 baseline) | 8 | 11 | 6 | 8 | 0 | 0 | 283 |

Derivation: registry parse (components/assets) + `settings.json` hook count + `glob` on scripts/commands/agents + `wc -l` registry. Re-derive at every iteration close; increases require a PB-justification in the close protocol.

**Ledger caveat (ARB, 2026-07-08):** a discussion tool, never a KPI. 8 components is not inherently better than 9 — the only question the numbers serve is *"was additional complexity justified by a PublicDigit capability?"* Do not optimize the numbers.

**PB-004 justification per remaining slice (ARB challenge: "make PB-004 inevitable"):**
- **C2 — RE-SEQUENCED AFTER QUALIFICATION (R-25, evidence-based).** ARB question: "can PB-004 start without C2?" **Yes — proven from the repository:** plan `Last Updated` stamping is done in-repo by `session-changes-logger.sh` (lines 40–46); AST-008 fires only on PlanCreate, is broken on this machine anyway (`jq` = documented broken shim; this session's plan file was demonstrably never renamed), and its intended behavior (rename to `plan_<timestamp>.md`) *contradicts* the descriptive-filename convention. C2 is technical-debt cleanup, and likely shrinks to **unwire + deprecate** (no relocation) — decided at its own slice review, after qualification.
- **C3 — NEXT SLICE, directly justified, strong:* PB-004's per-commit DoD demands "RED/GREEN evidence captured — output, not claim." AST-010 makes that capture deterministic and honest for every PB-004 micro-slice. Constraints (ARB): execute → collect evidence → return verdict — **nothing more**; any drift toward workflow/planner/reporter/dashboard = stop and refactor. **No interpretation (R-26): the Verification Engine is a measuring instrument, not a reviewer** — run PHPStan/suite → capture output → PASS/FAIL → stop. No "maybe because…", no recommendations, no AI summaries inside the instrument; analysis belongs to Review/Coaching contexts.

**Iteration order (amended per R-25):** C1 ✔ → **C3 → PB-004 Platform Qualification → C2 (post-qualification cleanup)** → **usage retrospective (R-26.4):** which platform parts did PB-004 exercise vs. leave unused — unused parts are challenged (simplify/remove unless foundational). No new governance until that retrospective.

**Standing construction question (R-26.3, supersedes "what should we build next?"):** *what is the SMALLEST platform change that unlocks the next PublicDigit capability?*

**GOVERNANCE FREEZE (R-27, in force):** no new platform principles until the PB-004 retrospective; clarifications legal, additions not. **C3's sole success criterion:** can AST-010 help deliver PB-004 with greater determinism, traceability, and honesty than before? If yes → straight to PB-004. **After the retrospective:** Continuous Evolution replaces the phase/iteration model — Feature → observation → retrospective → one amendment (if justified) → next feature; "Iteration" vocabulary retires; no platform roadmaps.

**Iteration-2 candidate on record (R-28, deferred per AIP-14/R-27):** **Engineering Doctrine** (CMP-009 reserved) — doctrine ids (DDD-nn/TDD-nn/CA-nn/HEX-nn…) over PublicDigit's existing architectural laws, declared by implementation artifacts and checkable by the platform. *Not built now.* Trigger: PB-004 shows repeated architectural mistakes that existing enforcement (tripwires + architecture tests + PHPStan + frozen process) failed to prevent. Decided at the retrospective. Doctrine ≠ platform governance — never mixed.

**Retrospective question added (peer-review close): "What surprised us?"** — friction found beats features planned; three surprises outrank twenty speculative improvements.

**Engineering habit (informal, not governance): every new piece of metadata must save more time than it costs to maintain.** Metadata inventory is itself subject to the retrospective's deletion goal.

**Retrospective clarifications (peer-review close, 2026-07-08):** the retrospective questions are: *what was used? what wasn't? what hurt? what simplified development? what should disappear?* — and it carries an explicit **deletion goal**: removing at least one script/hook/rule that reality proved unnecessary counts as success, not failure (architecture evolves by removal too). **Reviewer progress assessment on record:** Architecture 100% · Governance 100% · Construction ~15% · overall ~35–40%; v1.0 completion is defined by **capability, not component count** — the platform is v1.0 when it carries one full PublicDigit cycle (then a second, unrelated one) with no manual architectural intervention: context assembly · process guidance · enforced gates · traceable evidence · review support without hidden state · session continuity. **OI-1 vocabulary candidate added:** rename "AI Engineering Platform" → **"PublicDigit Engineering Platform"** (provider-independence carried into the name; registry `platform.name` follows at the supersession).

**Platform Value ledger (evidence, never scores — answers "what did this enable that wasn't possible before?"):**

| Iteration/slice | PublicDigit capability enabled | Evidence |
|---|---|---|
| C1 | Registry-first governance: every `.claude` artifact traceable and reviewable before it exists | `registry.yaml` accepted by ARB (2026-07-08); AST-010 registered before implementation |
| C3 (next) | Deterministic, honest gate-evidence capture for every PB-004 micro-slice | (to fill: PB-004 RED/GREEN evidence captured via AST-010) |
| PB-004 | First real end-to-end validation of the platform (qualification) | (to fill: ticket completed through the platform) |
| C2 (deferred) | Repository-local composition root (removal of last machine-local dependency) | (to fill at C2) |

**FF-17 Documentation Authority (R-24):** adopted as a definition (guides reference ADRs · guides don't contradict the registry · registry refs resolve · ADR refs resolve · examples match schema); **implementation deferred** per AIP-14 — Iteration 2 candidate, built when documentation growth demands it.

## Progress
- [x] **C1 — Platform Registry** ✔ 2026-07-08 · **ACCEPTED by ARB** ("Implementation Slice 1") · schema-v1.0 amendment applied at review: stable ids (CMP-001..008, AST-001..011), `name`+`version` per component, `owner` (governance) separate from `bounded_context` (architecture), runtime moments normalized to enum (SESSION_START/PRE_ACTION/POST_ARTIFACT/SESSION_END/ON_DEMAND), structured `schema:` block. Assets reference components by id; ADRs reference ids, not paths. Validated: parse ✔ · unique ids ✔ · refs resolve ✔ · five questions ✔ · VERIFY evidence on all non-planned assets ✔ · enum legality ✔ · **falsifiability proven** (dangling ref + illegal moment both detected). Dependencies graph deliberately NOT added (ARB: "not today").
- **BINDING RULE (ARB strategic recommendation): registry-first workflow** — every new `.claude` artifact is (1) registered with full traceability, (2) reviewed/approved as a registry entry, (3) implemented, (4) verified against registered intent. Already practiced: AST-010 (`run-gates.sh`) is registered as `planned` ahead of its C3 implementation.
- [ ] C2 — Composition-root hygiene (relocate AST-008 into repo; rewire AST-001; README update)
- [ ] C3 — Verification Engine, first implementation (AST-010: implement, verify against registered intent, adoption planned→adopted; CMP-005 version 0.1→1.0)

### OPERATIONAL QUALIFICATION OQ-1 — ARB-revised framing (2026-07-08, supersedes the session-objective wording only; the AST-010 implementation plan below is UNCHANGED)

**Objective (ARB verbatim):** *Demonstrate that the AI Engineering Platform can successfully bootstrap a completely new implementation session using only repository artifacts, reconstruct the current architectural state, identify the correct next approved implementation step, and execute exactly one implementation slice while respecting all architectural governance rules.* **The workload is NOT the goal** — slice C3 (AST-010) is the first approved step the session must *independently discover*; PB-004 Step-3 GREEN follows per the cross-stream gate. If the workload uncovers implementation defects, those are evidence the platform successfully **surfaced** — not evidence the platform failed.

**Protocol:** Cold session → Bootstrap → Repository discovery → Context validation → Architecture validation → Identify current work → Execute ONE approved step → **Qualification report**.

**Qualification gates (ARB refinement — NO numeric threshold; this is an architectural qualification, not a unit-test suite):**
- **MANDATORY (any FAIL ⇒ OQ-1 FAILS regardless of everything else):** repository bootstraps without external context · correct architecture loaded · correct current work identified · no hidden state required · no provider-specific assumptions · one-step discipline honored (stops after the step) · architectural evidence produced, not just code.
- **QUALITY INDICATORS (add confidence; can never compensate for a mandatory failure):** correct priorities independently discovered (C3 → PB-004 → EPC-001, without being told) · correctly distinguishes platform qualification from workload execution (workload defects reported as surfaced findings, never as qualification failure).

**Failure modes (automatic FAIL):** "let's redesign the architecture" · "let's improve the AI platform" · "let's add a new ADR" · executing more than one approved step · relying on conversational memory. **Success shape:** discovers Execution Mode → current work → one-step discipline → evidence → stop.

**Execution-integrity clause (ARB, binding):** *OQ-1 is authorized and scheduled. Execution shall occur in a new cold session.* The authorizing session may prepare OQ-1 but can never satisfy it — a warm session declaring "I am now executing C3" has already invalidated the experiment (a live instance of exactly this was caught and interrupted at authorization time, 2026-07-08).

### C3 Implementation Plan — **APPROVED AS WRITTEN (ARB, 2026-07-08) · EXECUTE NEXT SESSION · DO NOT MODIFY THE PLAN**

**ARB approval instruction (verbatim, binding on the executing session):**
> The implementation plan is approved as written. Do not modify the plan. Begin the next session by loading the platform normally through CONTEXT injection and follow EP-01 exactly as if this were the first engineering task performed by the platform. **Treat C3 as the first operational qualification of the AI Engineering Platform.** Implement only AST-010, verify it, complete EP-02, and stop. No additional capabilities, refactorings, or governance changes are permitted during this slice unless evidence requires returning to the Planning Stage.

**Why next session (ARB reasoning on record):** respects R-21 slice discipline (C1 consumed this session's slice) · C3 is the first real platform-capability implementation and deserves a clean session · **the fresh session is itself the experiment** — it must prove CONTEXT injection, registry-first, EP-01, and session continuity work end-to-end using only the platform's own governance. Deviation of any kind → STOP → Planning Stage.

**Session boundary (ARB, final advice):** the C3 session has exactly one objective — *execute the approved plan without changing it* — and **ends immediately after EP-02 completes**. No "while we're here", no "small improvement", no "let's also".

**Engineering commitment (ARB, 2026-07-08 — a commitment, not a ruling; stronger than AIP-14):** *No platform work may be undertaken unless it directly supports the next product feature.*

**Backlog (platform improvement candidates, post-PB-004; observations, not scheduled — each faces the 30-second question at the retrospective):**
- Refine the dev-guide reminder: (a) implementation behavior changed → guide expected, (b) governance/process changed → not necessarily, (c) documentation-only → silent.
- **Audit findings → retrospective candidates (ARB dispositions, 2026-07-08; audit doc: `engineering/verification/reports/knowledge_architecture_validation.md` — moved by EM-001):** F1 knowledge-home consolidation (dual quarantine) · F2 Engineering-Knowledge domain decision (three homes; decide with the third-domain question, after PB-004 + one more harvest) · **F3 HIGH-PRIORITY: define the "Standard" promotion event (align with AIP-02 chain)** · F4 housekeeping: sweep rule-like session-log entries into Engineering Standards at R-32 execution · PI-1 low: neutral alias for `.claude/` path references in process docs. Provider-independence audit: **PASS (executed by grep, not asserted)**.
- **ARB observation for the PB-004 retrospective (2026-07-09, Class A — recorded verbatim, nothing implemented):** *"EP-03 appears to be evolving into a composable Engineering Review Framework, where the core review is always executed and specialized review modules (Automation, Security, Performance, etc.) are loaded only when relevant to the feature."* Grounds: EP-03 exists · AR-01 (Automation Readiness questions — an **EP pattern, not an automation capability**: automation executes, process structures thinking) extends it naturally · progressive disclosure (EPC-001) supports conditional loading · derive-first-ask-gaps already favors it · extends one process instead of creating a parallel one. Companion queue items: the ARB's automation-development prompt template + AR-01's eight questions (the new economic layer: why not manual? what measurable cost removed? deterministic enough?) — adopted only when a PublicDigit feature names an automation need; **AST-010/C3 is the retroactive first evidence** (the platform's first automation, engineered under equivalent discipline before the terminology existed). Not governance; PB-004 evidence decides whether nine fixed domains suffice or modular reviews genuinely improve outcomes. **ARB refinement (2026-07-09):** if adopted, the shape to consider is an `EngineeringReview` aggregate — core modules always (Business·DDD·Architecture·Verification·Completion) + optional modules loaded by relevance (Automation·Security·Performance·Database·Compliance·…), **each module owning its own questions, invariants, and evidence** — progressive disclosure's natural home (EPC-001 finds its destination here); provider-independent (a human architect runs the same review). **Explicitly NOT modeled as an aggregate now** — current evidence (one planned implementation, one automation example, one theoretical extension) suffices for a candidate, not a first-class concept; several reviews naturally decomposing into reusable modules during PB-004+ would be the justifying evidence.
- **Evidence-backed Engineering Improvement Candidates** (renamed per ARB — "candidate" means *maybe*, needs evidence, may be rejected): **EPC-001 Progressive Disclosure (highest priority) · EPC-002 Engineering Budgets · EPC-004 Load-vs-Reference classes · EPC-005 Runtime Declarations · EPC-006 Pattern Library · EPC-010 Knowledge Harvest Process.** Full Engineering Pattern Cards (problem/intent/owner/required-evidence/maturity, harvest-source metadata): `engineering/knowledge/patterns/engineering_pattern_cards_agent_skills.md` (moved by EM-001). Confirmations requiring no action: EPC-003/007/008/009. Rejected provider mechanisms: Skill terminology · frontmatter duplication · auto-routing · model selection · permissions-in-files. Review point for all: PB-004 retrospective.
- **ARB observation — Platform ≙ Adoption separation (2026-07-10, Class A, recorded verbatim in spirit; NOT executed under R-27):** post-EM-001 assessment (phrasing refined per AIP-10 — no unmeasured ratios): **the Engineering Platform is largely reusable, but still contains PublicDigit-specific adoption evidence; separation will be driven by the first successful adoption in another project.** The platform concepts (standards, governance model, knowledge lifecycle, provider abstraction, verification methodology, registry model, runtime concepts) and the PublicDigit adoption (PB-004, C3, election evidence, reviews, retrospectives) are interwoven **inside the same documents** (e.g. ADR-AIP-01 mixes both). Eventual shape sketched: `engineering/platform/` (reusable product) vs `engineering/projects/publicdigit/` (one adoption) — the Kubernetes/Spring-Boot framework-vs-application split. ARB's own constraint: *"the next architectural evolution is NOT to reorganize folders again, but to separate platform from adoption"* — a **document-level** separation (Platform ADR vs Project Adoption ADR), not a move. **Litmus test on record:** could a Hospital/Membership system copy `engineering/` unedited? Today: largely, but not without removing adoption evidence. **Trigger for execution:** a second adopting project (real, not hypothetical) — until then the split is speculative structure for a single-adopter platform; queue with F2 (Engineering-Knowledge domain homes) and R-35 (rename) at the retrospective.
- **ARB observation — DDD-aggregate folder organization (2026-07-10, Class A; ARB classification sharpened at EM-001 closure: RESEARCH HYPOTHESIS — "not roadmap, not design, not future architecture"; PublicDigit hasn't yet demonstrated these deserve to become first-class aggregates; structural freeze R-37 applies):** the current `engineering/` top level (architecture/knowledge/verification) is still *technical categories*; DDD would organize by the domain objects the platform has repeatedly identified — Capability · Pattern · Evidence · Standard · Review · Registry ("one is where documents happen to live; the other is what exists in the engineering domain"). Sketched eventual tree: governance/{standards,decisions,policies} · knowledge/{patterns,evidence,research} · capabilities/ · reviews/{modules,reports,qualifications} · architecture/{baseline,c4,adr} · runtime/{registry,bindings}. **ARB verdict in the same review: "stop reorganizing now — additional folder restructuring is unlikely to produce proportional value."** Decide at the retrospective together with the capability-layer ruling and the Platform≙Adoption split (the three reshape the same tree — one decision, not three reorgs). Review scores on record: overall 9.4/10 (DDD Thinking 8.5 — this observation is the gap it names).
- **R-37 operational terms (ARB at EM-001 closure, Class B — the freeze's day-to-day meaning):** until the retrospective, `engineering/` permits ONLY bug fixes · broken-link fixes · typo corrections. Everything else — Capability, Principles, Adoption, DDD aggregate tree, executable review modules — goes into this inbox. **No exceptions.**
- **ARB observation — Architecture Qualification Dashboard (2026-07-10, Class A; AFTER PB-004; trimmed at ARB direction — no further design now, "otherwise you slowly start designing another subsystem"):** post-PB-004 **generated** operational dashboard over the evidence — never handwritten (AIP-10 holds automatically). Nothing more decided. Queue beside EPC-014 at the retrospective.
- **ARB observation — lifecycle separation (2026-07-10, Class A, research):** `engineering/` now separates *ownership* well but not yet *lifecycle*. Candidate lifecycle categories (NOT folders; folder-hood decided by implementation evidence): Engineering Knowledge (patterns, research) · Engineering Decisions (ADR, rulings) · Engineering Evidence (qualification, reports) · Engineering Runtime (registry, bindings) · Engineering Adoption (project-specific evidence). Decide with the coupled tree decision at the retrospective.

**Objective:** implement AST-010 exactly as registered. Nothing more.

**Authoritative inputs read (EP-01 step 1–2):** AST-010 registry entry + R-26 measuring-instrument rule · `phpunit.xml` (testsuite `Architecture` confirmed) · `phpstan-greenfield.neon` + `vendor/bin/phpstan` (existence confirmed) · scripts README conventions (bash + PHP parsing, printf-not-echo, repo-root cd).

**Assets modified (exactly three, all already governed):**
1. CREATE `.claude/scripts/run-gates.sh` (= AST-010, registered `planned` since C1 — registry-first step 1 already done).
2. UPDATE `.claude/platform/registry.yaml` — after verification only: AST-010 `planned→adopted` + VERIFY evidence; CMP-005 `version 0.1→1.0`.
3. UPDATE `.claude/scripts/README.md` — one table row (binding stays self-describing).

**Why no additional assets:** both gates already exist as project artifacts (config + testsuite); evidence storage reuses the existing `.claude/runtime/` convention (gitignored raw capture — durable citation happens when a human/commit quotes the verdict into governed docs, matching current per-commit DoD practice); **no hook wiring** — AST-010's runtime moment is `ON_DEMAND` (invoked explicitly; `settings.json` untouched); no commands, no agents.

**Registered behavior (measuring instrument, R-26 + construction-prompt constraints):**
- Run gate 1: `vendor/bin/phpstan analyse -c phpstan-greenfield.neon --no-progress`.
- Capture raw stdout+stderr → `.claude/runtime/gates/<YYYY-MM-DD-HHMMSS>-phpstan-greenfield.log`.
- Emit exactly one verdict line: `phpstan-greenfield PASS|FAIL evidence=<path>`.
- **On FAIL: stop** (no gate 2 — "must not continue execution after failure"). Exit non-zero.
- On PASS, run gate 2: `vendor/bin/phpunit --testsuite Architecture`; same capture + verdict line; exit reflects overall result.
- **No interpretation anywhere:** no summaries, no failure explanations, no recommendations, no code changes, no re-runs.

**Slice verification (before registry flip):** (a) run → both gates execute, evidence files exist, verdict lines correct, exit code correct; (b) **falsifiability:** temporarily add `tests/Architecture/ZzFalsifiabilityProofTest.php` (one assertion: false), run → gate 2 FAIL captured with evidence path, exit non-zero; delete the file, rerun → PASS. Transient repo mutation, fully reverted, evidence retained.

**EP-02 Completion Review:** human compares delivered script against this plan; only then AST-010 flips to `adopted`.

**Out of scope (explicitly):** gate selection arguments · additional gates (Deptrac/Infection arrive via PB-007, registered then) · verdict ledger/FF-3 tooling (deferred FFs) · any settings/hook change.
**Authority:** ADR-AIP-01 (+ Addendum) · Phase-03A Reference Architecture · Phase-02.7 Platform Decisions · rulings R-17/R-21
**Iteration 1 goal:** the smallest possible platform capable of supporting **PB-004** end-to-end. Nothing else.

## Objective

Realize — not redesign — the approved reference architecture, minimally. Success = PB-004 (whose prerequisite chain is already defined on the main track: `ContestedOutcomeRef` VO → v2 events → hydrators → RED C1) can be developed *through* the platform with every artifact traceable Capability → Bounded Context → Principle → Platform Decision → ADR.

## What PB-004 actually requires from the platform (evidence-based minimum)

1. Correct session bootstrap (context, active plan, standing rules) — **exists**.
2. Discipline tripwires (RED-before-GREEN, discipline sequence, dev-guide DoD, DB safety) — **exist**.
3. Per-commit gate execution with captured evidence (greenfield PHPStan + architecture suite; per-commit DoD demands "RED/GREEN evidence captured, output not claim") — **manual today; the one real gap**.
4. Traceable platform self-description (the five-question rule needs a place where answers live) — **missing; the Registry**.
5. IDD drafting support — **exists as frozen template** (`docs/implementation/IDD_Prompt_Template_Push_Implementation_Design.md`); needs nothing new.

Everything else in the reference architecture is NOT required by PB-004 → deferred.

## Disposition table (every artifact answers the five questions or is not created)

### CREATE (2 artifacts — the entire new-file footprint of Iteration 1)

| Artifact | Disposition & justification |
|---|---|
| `.claude/platform/registry.md` — **Platform Registry** (declarative, versioned: capability → component → owning context → assets → principle → PD → ADR; includes the adoption record of every existing asset below) | CREATE. Capability: CAP-13 Platform Self-Governance · BC: Design & Decision Support · Principle: AIP-12 (decision traceability), AIP-03 (no hidden state) · Decision: PD-13 (drafts), R-17 (five-question rule needs a home) · ADR: ADR-AIP-01. Reference architecture §1/§3: "registry loaded first; configuration-as-data". Without it, no other artifact can answer the five questions. |
| `.claude/scripts/run-gates.sh` — **minimal gate runner** (runs `vendor/bin/phpstan -c phpstan-greenfield.neon` + the architecture test suite; appends verdict + raw-output pointer to `.claude/runtime/` and prints it; records facts, decides nothing) | CREATE. Capability: CAP-07 Gate Execution & Honest Verdict Recording · BC: Verification & Evidence · Principle: AIP-01/AIP-04 (evidence-derived verdicts) · Decision: PD-16 (AI may run checks and record verdicts) · ADR: ADR-AIP-01. Required by PB-004's per-commit DoD ("evidence captured — output, not claim"). |

### UPDATE (2 — both minimal, both portability/hygiene)

| Artifact | Disposition & justification |
|---|---|
| `.claude/settings.json` — relocate the `PlanCreate → ~/.claude/hooks/timestamp-plan.sh` hook into the repo (copy script to `.claude/scripts/`, rewire) | UPDATE. Violates AIP-03 (machine-local state a fresh clone lacks) and 03A §3 ("project scope authoritative"). One-line rewire + one file copy. Capability: Composition Root · PD-12-adjacent hygiene · ADR-AIP-01. |
| `.claude/scripts/README.md` — add the two new scripts + registry pointer to the existing table | UPDATE. Keeps the binding self-describing (Phase 1 success criterion 5: onboarding from ≤3 documents). |

### REUSE unchanged (formally adopted into the platform via registry entries — zero code changes)

| Existing asset | Adopted as (component / capability) |
|---|---|
| `settings.json` hook wiring | Composition Root (03A §3) |
| `inject-context.sh` + CONTEXT/MEMORY/plans/sessions convention | Session Manager / CAP-01, CAP-02 |
| `session-changes-logger.sh`, `session-log-reminder.sh` | Session Manager / CAP-02 (staleness = Tier-2) |
| `discipline-gate-reminder.sh` | Workflow Engine / CAP-06 (Tier-2 tripwire) |
| `dev-guide-reminder.sh` | Workflow Engine / CAP-05 (DoD box reminder) |
| `db-safety-check.sh` | Verification Engine guard surface (Tier-1 blocking) |
| `.claude/CLAUDE.md` + root `CLAUDE.md` standing rules | Rules (Tier-3 doctrine; governed by PD/AIP on conflict) |
| `docs/implementation/IDD_Prompt_Template_*.md`, ADR templates, EKP packages | Drafting Studio / Knowledge sources — consumed by reference, not duplicated |

### REMOVE — none. (Nothing in the current `.claude` is dead weight; Phase 1's DROP list concerned the external framework, which was never installed here.)

### DEFER (explicitly out of Iteration 1 — each waits for implementation evidence per the three maintainer questions)

Review Engine automation (PB-004 reviews are human; PD-10 reviewer-agent waits for a real recurring need) · new commands (**zero** required by PB-004) · new agents (**zero** required) · new hooks beyond the relocation · Knowledge-Package/bootstrap integration (plan file already declares its reading list) · executable platform fitness functions FF-1..16 beyond what the registry enables (Iteration 2, from lessons learned) · root `CLAUDE.md` tech-table refresh (main-track docs debt, tracked separately).

## Slices (R-21: each leaves the platform usable, green, testable; slice → review → merge)

- **C1** — Platform Registry (adoption record of all REUSE assets + itself). Verify: registry entries answer the five questions for every listed asset; hooks still fire.
- **C2** — Composition-root hygiene (relocate timestamp hook; README update). Verify: fresh-clone simulation — no reference to machine-local paths in project hooks; all hooks still fire.
- **C3** — `run-gates.sh` + its registry entry. Verify: run it → PHPStan + architecture suite execute, verdict + evidence pointer recorded in `.claude/runtime/`; deliberately break a check locally → verdict records FAIL (falsifiability demonstrated).
- **STOP.** Iteration 1 ends. PB-004 proceeds on the main track *through* the platform (= Platform Qualification, R-18). Friction found → Amendment Proposals (AIP-13), never in-construction redesign.

## Ordering constraint

**OI-1 (Phase-02.6 terminology review) should close before C1 merges** — the registry must be written in frozen vocabulary. If ARB prefers, C1 can proceed using current 02.6 wording with a note that OI-1 renames apply by supersession.

## Risks

Scope creep ("while we're here…") — mitigated by this plan's DEFER list being binding · gate-runner platform variance (Windows/Git-Bash; PHP-based JSON parsing precedent exists in current scripts) · double-tracking risk between registry and docs (mitigated: registry holds *dispositions and traceability only*, never content).

## Success criteria (from the Iteration 1 prompt)

PB-004 developable through the platform · all artifacts satisfy ADR-AIP-01 · no unnecessary capability introduced (2 CREATEs, 2 UPDATEs, 0 new commands/agents) · no principle violated · full five-question traceability (lives in the registry).

## Next actions

1. ARB approves / amends this plan (and rules on the OI-1 ordering constraint).
2. On approval: execute C1 (one slice, one session), request review, merge; then C2; then C3.
