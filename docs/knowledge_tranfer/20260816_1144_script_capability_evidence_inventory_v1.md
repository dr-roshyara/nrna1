# KnowledgeOS/PKS — Script-to-Capability Evidence Inventory v1.0

| Governance | |
|---|---|
| **Kind** | ⭐ **EVIDENCE INVENTORY.** ⛔ *Not a redesign · not a consolidation · not a renaming · not a target-component proposal* |
| **Commission** | ARB, 2026-08-16 — **Step 2 AUTHORIZED**: *"Discover what capabilities already exist and what architectural responsibilities they evidence."* Architecture discussion **FROZEN** |
| **Status** | **PROPOSED** — an ARB input |
| **Authority** | Generated — never authoritative without human review |
| **Scope** | `.claude/scripts/` (12) · `scripts/` (13 shell + 5 PHP + `lib/` + `observations/` 24 + `metrics/`) · `.claude/platform/registry.yaml` · `.claude/settings.json` wiring |
| **Grades** | `OBSERVED` (read from source or the registry) · `INFERRED` · `UNKNOWN`. ⛔ **Only `OBSERVED` binds** |
| **ARB constraint, binding** | ⭐ **Script ≠ capability ≠ architecture boundary** |
| **Date** | 2026-08-16 |

> ⛔ **This inventory decides nothing.** No bounded context · no aggregate · no repository · no domain event · no database · no API · no Rule model · no conflict vocabulary · no C4 L3/L4 · no target structure.

---

# 1 · Scope and method

## 1.1 What was read

| Source | Depth | Grade |
|---|---|---|
| `.claude/platform/registry.yaml` | **full** — 16 assets, 8 components | `OBSERVED` |
| `.claude/settings.json` hook wiring | **full** — 10 wired scripts at 4 moments | `OBSERVED` |
| `.claude/scripts/*.sh` (10) | headers + decision logic; `db-safety-check` full | `OBSERVED` |
| `.claude/scripts/*.php` (2) | via registry entries + Phase-A baseline | `INFERRED` |
| `scripts/*.sh` (13), `scripts/*.php` (5) | purpose headers; `README.md` full | `OBSERVED` (purpose) |
| `scripts/observations/*` (24) | purpose headers + `recommendation-rules.yaml` full + `Verdict.php` full | `OBSERVED` |
| `scripts/lib/EngineeringKnowledge/` | file tree + `Verdict.php` | `OBSERVED` |

## 1.2 Method

For each mechanism: **Script → Observed mechanism → Observed behaviour → Capability → Contract/invariant enforced → Architectural responsibility → Candidate ownership boundary → Current owner → Evidence → Classification.**

⛔ **Limits, stated so they are not mistaken for completeness:** no script was executed; behaviour is read, not run. Eight `scripts/*.sh` design-system gates were classified from purpose headers only. `workflow-state.php` and `session-resolve.php` were **not re-read this session** — their entries derive from the registry and the Phase-A baseline and are graded `INFERRED`.

## 1.3 Classification vocabulary

`Domain invariant` · `Governance mechanism` · `Execution mechanism` · `Verification mechanism` · `Evidence collector` · `Projection` · `Developer tooling` · `AI/Harness mechanism` · `Infrastructure/adapter` · `Unknown`

---

# 2 · Script inventory

## 2.1 `.claude/scripts/` — the runtime mount, 12 mechanisms

| Script | AST | Hook moment | Blocking | Classification |
|---|---|---|---|---|
| `inject-context.sh` | AST-002 | `SessionStart` | no | AI/Harness |
| `session-changes-logger.sh` | AST-003 | `PostToolUse` (Write\|Edit) | no | Evidence collector |
| `session-log-reminder.sh` | AST-004 | `Stop` | no | Governance mechanism |
| `discipline-gate-reminder.sh` | AST-005 | `PreToolUse` (Write\|Edit) | no | Governance mechanism |
| `dev-guide-reminder.sh` | AST-006 | `Stop` | no | Governance mechanism |
| `db-safety-check.sh` | AST-007 | `PreToolUse` (Bash\|PowerShell) | ⭐ **YES — exit 2** | Verification mechanism |
| `ddd-principles-reminder.sh` | AST-014 | `PreToolUse` (Write\|Edit) | no | Governance mechanism |
| `workflow-state.php` | AST-015 | `ON_DEMAND` (CLI) | no | ⭐ **Execution mechanism** |
| `session-resolve.php` | AST-016 | `ON_DEMAND` (CLI) | no | ⭐ **Execution mechanism** |
| `engineering-placement-guard.sh` | ⛔ **none** | `PreToolUse` (Write\|Edit) | no | Governance mechanism |
| `project-knowledge-guard.sh` | ⛔ **none** | `PreToolUse` (Write\|Edit) | ⭐⭐ **YES — exit 2** | Governance mechanism |
| `claude-code-trigger.sh` | ⛔ **none** | `PostToolUse` (Write\|Edit) | no | Infrastructure/adapter |

**Registered but ABSENT:** `run-gates.sh` (**AST-010**, `adoption: planned` since 2026-07-08). ⛔ **File does not exist.** `OBSERVED`.

## 2.2 `scripts/` — two distinct systems under one directory

### 2.2.1 Design & Security Governance Gates — orchestrated by `verify.sh`

`README.md`, `OBSERVED`: *"Shell/PHP gates run by `.husky/pre-commit` and `.husky/pre-push` (orchestrator: `verify.sh`)."*

| Script | Purpose (header) | Classification |
|---|---|---|
| `verify.sh` | *"Master Design Governance Orchestrator v3"* | Governance mechanism |
| `design-check.sh` · `check-design-tokens.sh` | design-token compliance; threshold-based | Verification mechanism |
| `component-audit.sh` | raw HTML vs design-system components; **baseline regression counts** | Verification mechanism |
| `migration-progress.sh` | progress toward enforcement thresholds | Projection |
| `structure-check.sh` | *"Frontend DDD Structure Visibility"* | Verification mechanism |
| `check-domain-purity.sh` | *"Domain Layer Purity Gate v2"* | ⭐ **Domain invariant** |
| `enforce-tenantid.sh` | *"prevents regression of canonical identity model"* | ⭐ **Domain invariant** |
| `check_roles.php` | *"Role & Permission Governance Check"* | Verification mechanism |
| `audit-*.sh` (3) | design tokens · page structure · SEO translations | Verification mechanism |
| `unify-page-structure.sh` | standardizes page structure | ⛔ **Developer tooling — mutates source** |
| `fix-build.js` | build repair | Developer tooling |
| `lib/config-guard.sh` | ⭐ *"a governance gate must REFUSE to run when it cannot read its policy"* | ⭐ **Governance mechanism** |

### 2.2.2 Engineering Knowledge Platform (EKP) services

| Script | Capability | Classification |
|---|---|---|
| `knowledge-lint.php` / `.sh` | *"PHPStan for knowledge"* — 16 rules over `docs/knowledge/**` | Verification mechanism |
| `knowledge-graph.php` / `.sh` | typed-relationship graph → `portal/graph/` | ⭐ **Projection** |
| `doc-placement.php` | placement derived from classification; **exit 2 = unruled** | Governance mechanism |
| `identifier-check.php` | CAP-001 Identifier Integrity CLI adapter | Verification mechanism |
| `link-check.php` | broken links classified **by evidence and confidence** | Verification mechanism |
| `lib/EngineeringKnowledge/` | CAP-001 hexagonal implementation (Domain/Application/Infrastructure/Tests) | Infrastructure/adapter |

### 2.2.3 `scripts/observations/` — the KnowledgeOS runtime (24 files)

| Group | Files | Classification |
|---|---|---|
| Trigger port + adapters | `ChangeSet` · `ObservationRuntime` · `FileSaveTrigger` · `watch.php` · `git-hooks/post-commit` | Execution mechanism |
| Collectors | `Lcom4Collector` · `TestPresenceCollector` · `lcom4-observer` · `test-presence-observer` | ⭐ **Evidence collector** |
| Rules | `RecommendationEngine` + `recommendation-rules.yaml` (**5 rules, data**) | Governance mechanism |
| Decision capture | `recommendation-inbox` · `recommendation-decide` · `RecommendationInbox` | Execution mechanism |
| Outcome / assessment | `OutcomeRecorder` · `outcome-record` · `AssessmentService` · `assess` | Verification mechanism |
| Projections | `EvidenceDashboardRenderer` · `dashboard-renderer` | ⭐ **Projection** |
| Bootstrap | `KnowledgeOsInitPlanner` · `init` · `KnowledgeOsDoctor` · `doctor` · `dev` | Developer tooling |
| API | `observe.php --json` | Infrastructure/adapter |
| IDE | `vscode-knowledgeos/` | Infrastructure/adapter |

---

# 3 · Mechanism inventory — the harness, at the depth the ARB required

⭐ **For each: *what failure does it prevent → what contract → who owns it → which class → how enforced*.**

### `db-safety-check.sh` (AST-007) — Tier 1, **blocking**

| | |
|---|---|
| **Failure prevented** | destruction of the **development database** by `migrate:fresh` · `migrate:refresh` · `db:seed` · `migrate --seed` |
| **Contract** | *"The user's development database is sacred"* — `.claude/CLAUDE.md` |
| **Owner** | Verification & Evidence (CMP-005) |
| **Class** | ⭐ **Developer-process contract with data-loss consequence** — not a domain invariant |
| **Enforcement** | `exit 2` on `PreToolUse`; escape hatches `APP_ENV=testing` · `--env=testing` · `DB_DATABASE~test` |
| ⛔ **Failure history** | **Two silent fail-opens.** (1) Used `exit 1` until 2026-07-12 — *"the BLOCKED message was cosmetic only; the destructive command executed regardless."* (2) Bare relative hook paths until 2026-07-30 — *"while the path was unresolvable, `db-safety-check.sh` did not run at all."* `OBSERVED` |

### `project-knowledge-guard.sh` (**unregistered**) — **blocking**

| | |
|---|---|
| **Failure prevented** | project memory/plans/state written to **global** `~/.claude/{projects,plans,memory}/` instead of the repository |
| **Contract** | *"The project repository is the single source of truth"* |
| **Owner** | ⛔ **none declared** — no AST, no CAP, no component, no trace |
| **Class** | ⭐ **Governance mechanism enforcing a repository-boundary invariant** |
| **Enforcement** | `exit 2`. Allow-list preserves `~/.claude/CLAUDE.md`, `settings*.json`, and everything in-repo |
| **Origin** | *"On 2026-07-28 an assistant wrote a project convention into the GLOBAL auto-memory directory despite the rule — caught by the human, not by the repo. Instructions are a request; a hook is a gate."* |

### `engineering-placement-guard.sh` (**unregistered — by ruling**)

| | |
|---|---|
| **Failure prevented** | *"a governed action performed without consulting its governing standards"* — a methodology module written into canon while still at stage 1 of the promotion ladder (2026-08-01) |
| **Contract** | ⭐ **RUNTIME TOOLING ENFORCES GOVERNANCE. IT NEVER DUPLICATES GOVERNANCE.** *Reads the canonical index at runtime; quotes no rule text; hard-codes no standard number* |
| **Owner** | project-side workflow |
| **Class** | Governance mechanism |
| **Enforcement** | `exit 1` — non-blocking by design: *"the hook cannot evaluate the answers to its own questions — only the author can"* |
| ⭐ **Non-registration is RULED, not omitted** | *"R-42 ruled the registry governs platform assets only, and a project-side workflow hook has no AIP lineage. **Registering it would require fabricating traceability.**"* `OBSERVED` |

### `discipline-gate-reminder.sh` (AST-005) · `ddd-principles-reminder.sh` (AST-014) · `dev-guide-reminder.sh` (AST-006)

| | discipline-gate | ddd-principles | dev-guide |
|---|---|---|---|
| **Failure prevented** | a test or class encoding assumptions **before** the model justifies them | tactical-DDD files written without the 7 principles | code shipped without its Developer Guide |
| **Contract** | `Business → DDD → Architecture → Tests → Implementation` | DDD Tactical Governance Principles (**ADOPTED**, R-39) | Developer Guide = Definition of Done |
| **Class** | developer-process | ⭐ **methodology contract** | developer-process |
| **Enforcement** | non-blocking; **once per session-day** (marker file) | non-blocking; once per session-day | non-blocking; **per code-area** |
| ⭐ **Design property** | *"points, never restates — rule text lives ONLY in the canonical module"* | same | area-alias map prevents an unrelated track's guide from silencing a real gap |

### `lib/config-guard.sh` — the fail-closed guard

| | |
|---|---|
| **Failure prevented** | ⭐⭐ **a governance gate passing without measuring anything.** *"an empty/garbage parse made every numeric test error out as 'false' and the gates passed"* |
| **Contract** | ⭐ **A governance gate must REFUSE to run when it cannot read its policy** (EG-002a) |
| **Class** | ⭐ **Meta-governance — a contract about contracts** |
| **Origin** | the npm `jq` impostor: *"answers every query with empty output and exit 0, and the gates then run on empty configuration and **fail open**"* (F-GATE-1, 2026-07-26) |

### `workflow-state.php` (AST-015) · `session-resolve.php` (AST-016) — `INFERRED` from registry

| | AST-015 | AST-016 |
|---|---|---|
| **Failure prevented** | authority inferred rather than recorded | a session operating without a permitting record |
| **Contract** | `sessionRegistry ≠ authorityState`; append-only; **state = fold** | *"resolution is not activation"*; **structurally write-free** |
| **Class** | ⭐ **Execution mechanism — the authority record** | ⭐ **Execution mechanism — read-only query** |
| **Enforcement** | ⚠️ *"Advisory record only — violations are visible/adjudicable, **not physically prevented**"* | none — reports a verdict |
| ⭐ **Invariant realised** | sole interpretation authority | ⭐ **delegates ALL interpretation to AST-015 via subprocess — no second fold** |

---

# 4 · Capability mapping

| Capability (observed) | Mechanisms | Declared CAP | Component |
|---|---|---|---|
| Context bootstrap | `inject-context.sh` | CAP-01 | CMP-002 |
| Session recording | `session-changes-logger` · `session-log-reminder` | CAP-02 | CMP-002 |
| Discipline gating | `discipline-gate` · `dev-guide` · `ddd-principles` | CAP-06 | CMP-004 |
| Destructive-command guard | `db-safety-check` | CAP-09 | CMP-005 |
| Gate execution + evidence | ⛔ **`run-gates.sh` — ABSENT** | CAP-07 | CMP-005 |
| ⭐ **Governed session orchestration** | `workflow-state` · `session-resolve` | ⛔ **NONE** | CMP-004 |
| ⭐ **Repository-boundary enforcement** | `project-knowledge-guard` | ⛔ **NONE** | ⛔ **none** |
| ⭐ **Placement-consultation checkpoint** | `engineering-placement-guard` | ⛔ **NONE** (ruled, R-42) | project-side |
| Identifier integrity | `identifier-check.php` + `lib/EngineeringKnowledge/` | CAP-001 (DP-1) | — |
| Knowledge-card integrity | `knowledge-lint.php` | — | EKP |
| Knowledge graph | `knowledge-graph.php` | — | EKP |
| Placement derivation | `doc-placement.php` | — | EKP |
| Reference integrity | `link-check.php` | — | EKP |
| Design-system conformance | `verify.sh` + 8 gates | ⛔ **NONE** | ⛔ **none** |
| Domain purity | `check-domain-purity.sh` · `enforce-tenantid.sh` | ⛔ **NONE** | ⛔ **none** |
| Observation → assessment | `observations/` (24) | ⛔ **NONE** | ⛔ **none** |

> ### ⭐ **Nine observed capabilities carry no `CAP-` identifier.** Two carry a *ruled* exemption; seven do not.

---

# 5 · Contract / invariant mapping

| # | Contract enforced | Mechanism | Class | Enforcement |
|---|---|---|---|---|
| **K-1** | The development database is sacred | `db-safety-check` | developer-process | ⭐ **BLOCKING** |
| **K-2** | The repository is the single source of truth | `project-knowledge-guard` | governance | ⭐ **BLOCKING** |
| **K-3** | A governed action consults its governing standards | `engineering-placement-guard` | governance | advisory |
| **K-4** | A gate refuses to run when it cannot read its policy | `lib/config-guard.sh` | ⭐ meta-governance | fail-closed |
| **K-5** | Business → DDD → Architecture → Tests → Implementation | `discipline-gate-reminder` | developer-process | advisory |
| **K-6** | Tactical DDD obeys the 7 principles | `ddd-principles-reminder` | methodology | advisory |
| **K-7** | Developer Guide = Definition of Done | `dev-guide-reminder` | developer-process | advisory |
| **K-8** | Session state stays synchronised | `session-log-reminder` | developer-process | advisory |
| **K-9** | Placement is derived, never chosen | `doc-placement.php` | governance | exit 2 = unruled |
| **K-10** | One authoritative document per topic+context | `knowledge-lint` (`single_authoritative`) | ⭐ **knowledge invariant** | error |
| **K-11** | ⭐ **Domain layer must not depend on infrastructure** | `check-domain-purity.sh` | ⭐ **DOMAIN INVARIANT** | gate |
| **K-12** | ⭐ **Canonical identity model must not regress** | `enforce-tenantid.sh` | ⭐ **DOMAIN INVARIANT** | gate |
| **K-13** | Design-system conformance must not regress | `component-audit` (baselines) | product-process | threshold |
| **K-14** | `sessionRegistry ≠ authorityState` | `workflow-state.php` | ⭐ **governance invariant** | ⚠️ advisory only |
| **K-15** | No second fold of workflow state | `session-resolve.php` | ⭐ **`P-7` realised** | structural |
| **K-16** | Verdicts cross a boundary only in a closed vocabulary | `Verdict.php` (AP-8) | ⭐ **governance invariant** | enum |
| **K-17** | ⭐ **Knowledge feeds authority; it never holds it** | `Verdict.php::emittable()` | ⭐⭐ **`ARCH-INV-1`** | ⭐ **compiled** |
| **K-18** | Rules are data, never code | `recommendation-rules.yaml` | architecture | convention |
| **K-19** | Evidence is append-only | JSONL streams | governance | convention |
| **K-20** | Advisory, never blocking | every collector | architecture | convention |

> ### ⭐ **`K-17` is the only contract in the repository that is *compiled* rather than checked, conventioned, or reminded.** It is `ARCH-INV-1` — and it lives in an enum method, not in a governance document.

---

# 6 · Ownership mapping

| Owner | Mechanisms | Evidence |
|---|---|---|
| **Architecture Review Board** | CMP-001 composition root | registry |
| **Session Continuity** (CMP-002) | AST-002/003/004 | registry |
| **Implementation Guidance** (CMP-004) | AST-005/006/012/013/014/015/016 | registry |
| **Verification & Evidence** (CMP-005) | AST-007 · ⛔ AST-010 absent | registry |
| **Design & Decision Support** (CMP-007/008) | AST-009/011 | registry |
| ⛔ **NONE — project-side by ruling** | `engineering-placement-guard` | ⭐ R-42, in-source |
| ⛔⛔ **NONE — undeclared** | `project-knowledge-guard` · `claude-code-trigger` | absence |
| ⛔⛔ **NONE — undeclared** | all of `scripts/` (design gates · EKP services · observations) | absence |

> ### ⛔⛔ **The largest ownership gap is not a hook. It is `scripts/` — roughly 45 mechanisms, three distinct systems, and not one registry entry, component, or declared owner between them.** `OBSERVED`.

---

# 7 · Harness / fitness-function mapping

| Property | Value | Grade |
|---|---|---|
| Wired hooks | **10**, at 4 runtime moments | `OBSERVED` |
| ⭐ **Blocking (exit 2)** | ⭐ **2** — `db-safety-check` · `project-knowledge-guard` | `OBSERVED` |
| Advisory | 8 | `OBSERVED` |
| ⭐ **Fitness functions FF-1..FF-16** | ⛔ **none implemented** — `run-gates.sh` absent | `OBSERVED` |
| Gate orchestration (product side) | `verify.sh` via husky pre-commit/pre-push | `OBSERVED` |
| Gate orchestration (platform side) | ⛔ **none** — AST-010 planned since 2026-07-08 | `OBSERVED` |

## 7.1 ⛔⛔ Three independent fail-open incidents in the enforcement layer

| # | Incident | Effect | Fixed |
|---|---|---|---|
| **1** | `db-safety-check` used `exit 1` | *"the BLOCKED message was cosmetic only; the destructive command executed regardless"* | 2026-07-12 |
| **2** | npm `jq` impostor shadowing real `jq` | *"the gates run on empty configuration and **fail open** — they pass without measuring anything"* | 2026-07-26 (F-GATE-1) |
| **3** | bare relative hook paths | *"`db-safety-check.sh` did not run at all… An absent gate reports nothing and blocks nothing"* | 2026-07-30 |

> ### ⭐⭐ **The pattern is one thing, three times: the enforcement layer failed SILENTLY, and in every case the loudest symptom was cosmetic while the actual consequence was an unenforced gate.**
>
> ⭐ **`lib/config-guard.sh` is the repository's own answer to this class** — *"a governance gate must REFUSE to run when it cannot read its policy."* ⛔ **It is applied to the design gates only. The two blocking `.claude/` hooks have no equivalent self-check.**

---

# 8 · Duplicate / overlapping capability findings

## 8.1 ⭐⭐ Two governance systems over one repository, mutually unaware

| | `.claude/scripts/` | `scripts/` |
|---|---|---|
| Identity | AI Engineering Platform | Design & Security Governance Gates |
| Inventory | `CMP-nnn` / `AST-nnn` / `CAP-nn` | ⛔ none |
| Lineage | ADR-AIP-01, five-question trace | ⛔ none |
| Orchestrator | ⛔ **none** (AST-010 absent) | `verify.sh` |
| Trigger | Claude Code hooks | husky pre-commit / pre-push |
| Repair workstream | Iteration slices C1–C3 | EG-001..EG-004 |
| Fail-closed guard | ⛔ none | ⭐ `lib/config-guard.sh` |

> ⛔ **Neither README, registry, nor orchestrator references the other.** Two enforcement regimes, two vocabularies, two repair programmes, over one codebase. `OBSERVED`.

## 8.2 ⭐ Same invariant, two mechanisms — Domain purity

```
check-domain-purity.sh        "Domain Layer Purity Gate v2"        scripts/, husky, GATE
ddd-principles-reminder.sh    tactical-DDD surface, 7 principles   .claude/, AST-014, ADVISORY
```

**Both act on `app/*/Domain/**`.** One blocks-by-gate at commit; one reminds once per session-day at edit. ⛔ **Neither references the other. No contract states which is authoritative for domain-layer conformance.** `OBSERVED` — **potential duplicate ownership: YES.**

## 8.3 ⭐ Same event, two consumers, no declared contract

```
PostToolUse (Write|Edit)
   ├── session-changes-logger.sh   (AST-003)  → runtime/<date>-files.log + state.json
   └── claude-code-trigger.sh      (no AST)   → observe.php → advisories
```

Not duplicate ownership — different purposes — but **a shared trigger surface with no declared contract, one side of which is unregistered.** `OBSERVED`.

## 8.4 ⭐⭐ Same capability implemented twice — verdict vocabularies

```
Verdict.php          PASS · FAIL · WARN · INCONCLUSIVE
                     PASS AFTER CORRECTION · EMERGENT · CERTIFIED     (AP-8, 7 values)

AssessmentService    SUPPORTED · PARTIALLY_SUPPORTED
                     NOT_SUPPORTED · INCONCLUSIVE                     (4 values)
```

**Two closed verdict vocabularies, two mechanisms, one repository. `INCONCLUSIVE` is the only shared token.** Already recorded as **HIGH severity** in the KnowledgeOS UL table. ⛔ **Authoritative owner: UNKNOWN.** `OBSERVED`.

## 8.5 ⭐ Same authority decision represented twice

```
K-2  "repository is the single source of truth"
        ├── project-knowledge-guard.sh   BLOCKING, exit 2, unregistered
        └── "autoMemoryEnabled": false   settings.json, AST-001, registered
```

⭐ **Two mechanisms enforce one contract by different means** — one blocks writes, one disables the subsystem. The hook's own header names the other as *"companion control."* **Deliberate defence in depth, not accidental duplication** — but only one of the two is registered, and neither is named in the other's registry entry. `OBSERVED`.

## 8.6 ⚠️ Documentation/implementation drift — AST-003

```
header comment :  .claude/sessions/YYYY-MM-DD-files.log
actual code    :  .claude/runtime/$(date +%F)-files.log      ← line 48
```

⛔ **An `adoption: adopted` asset whose header describes a path it does not write.** Small, and exactly the multi-representation drift class the `round7` LANGUAGE_CONFORMANCE instrument was built to detect. `OBSERVED`.

## 8.7 ⛔ A projection treated as source of truth — none found

⭐ **Checked and negative.** `registry.yaml`: *"Human documentation is GENERATED from this file — never the reverse."* `portal/INDEX.md` carries `authority: derived`. Dashboards declare themselves regenerable. **No violation observed.**

---

# 9 · Architectural unknowns

| # | Unknown | Why it cannot be settled here |
|---|---|---|
| **U-1** | Who owns `scripts/`? | No registry entry, component, or owner field exists for ~45 mechanisms |
| **U-2** | Which mechanism is authoritative for domain-layer conformance — `check-domain-purity.sh` or `ddd-principles-reminder.sh`? | Neither references the other (§8.2) |
| **U-3** | Which verdict vocabulary is authoritative? | Both are closed sets in running code (§8.4) |
| **U-4** | Why is `run-gates.sh` (AST-010) still absent after 5+ weeks? | Registered `planned` 2026-07-08; CMP-005 remains `under-construction v0.1` |
| **U-5** | Do `project-knowledge-guard` and `claude-code-trigger` fall under R-42's exemption, as `engineering-placement-guard` explicitly does? | Only one of the three documents the ruling |
| **U-6** | Is `unify-page-structure.sh` a gate or a mutation tool? | It **modifies source files**; every sibling only measures |
| **U-7** | Is the design-gate system in scope for KnowledgeOS at all? | It predates the platform and answers to husky, not to the registry |
| **U-8** | AST-015/016 behaviour | Not re-read this session; entries are `INFERRED` from registry + baseline |

---

# 10 · Evidence index

| Claim | Source | Grade |
|---|---|---|
| 16 assets, 8 components | `.claude/platform/registry.yaml` | `OBSERVED` |
| 10 wired hooks, 2 blocking | `.claude/settings.json` + `.claude/scripts/README.md` | `OBSERVED` |
| `run-gates.sh` absent | filesystem check | `OBSERVED` |
| R-42 exempts the placement guard | in-source comment, lines 31–33 | `OBSERVED` |
| Three fail-open incidents | in-source comments + both READMEs | `OBSERVED` |
| `K-17` compiled into `emittable()` | `Verdict.php` | `OBSERVED` |
| 5 recommendation rules, no normative fields | `recommendation-rules.yaml` | `OBSERVED` |
| AST-003 path drift | header vs line 48 | `OBSERVED` |
| Two verdict vocabularies | `Verdict.php` + `AssessmentService.php` | `OBSERVED` |
| AST-015/016 contracts | registry `notes` + Phase-A baseline | `INFERRED` |
| 8 design gates' behaviour | purpose headers only | `INFERRED` |

---

# 11 · Recommendations — only where evidence warrants

⛔ **Five. Each cites its evidence; none proposes architecture.**

| # | Recommendation | Evidence | Authority |
|---|---|---|---|
| **R-1** | ⭐ **Rule the ownership of `scripts/`.** Roughly 45 mechanisms, three systems, zero declared owners — the largest gap found | §6, §8.1 | **ARB** |
| **R-2** | ⭐ **Apply the fail-closed principle to the two blocking hooks.** `lib/config-guard.sh` already states the contract; the `.claude/` gates that *block* have no self-check, and all three fail-open incidents landed there | §7.1 | **ARB** |
| **R-3** | ⭐ **Correct the Phase-A "3 unregistered hooks" finding.** One is ruled-exempt under R-42 with its reasoning in source. **Two remain genuinely unregistered — and one of those blocks** | §3, §6 | **ARB** |
| **R-4** | **Rule which mechanism is authoritative for domain-layer conformance** (`U-2`). Two mechanisms, one invariant, no precedence — `P-7`'s exact failure shape | §8.2 | **ARB** |
| **R-5** | **Dispose AST-010** — adopt, re-plan, or withdraw. A registry entry `planned` for five weeks with no artifact is the registry describing an intention, not an inventory | §2.1, `U-4` | **ARB** |

⛔ **Explicitly NOT recommended:** consolidating the two governance systems · renaming anything · registering the unregistered hooks before R-3 is ruled · building `run-gates.sh` · reconciling the verdict vocabularies (that is Step 5) · any target-architecture change.

---

## Closing

**Roughly 57 mechanisms were inventoried. Nine observed capabilities carry no `CAP-` identifier; ~45 mechanisms carry no owner at all.**

Three findings are worth the ARB's attention above the rest:

**One.** `ARCH-INV-1` — *knowledge feeds authority, it never holds it* — is the **only contract in the repository that is compiled** rather than checked, conventioned or reminded. It lives in an enum method.

**Two.** The enforcement layer has **failed open silently three times**, and in each case the visible symptom was cosmetic while the gate was absent. The repository has written its own cure — `lib/config-guard.sh` — and applied it to the design gates only.

**Three.** `scripts/` and `.claude/scripts/` are **two governance regimes over one repository, mutually unaware**: two orchestrators, two vocabularies, two repair programmes, one inventory between them, and no document that names both.

> ### ⛔ **No target architecture v4. This inventory is evidence for the next architectural decision — not a proposal for one.**

---

*Step 2 of the ARB sequence, authorized 2026-08-16. Scope: `.claude/scripts/` · `scripts/` · `.claude/platform/registry.yaml` · `.claude/settings.json`. Every claim graded; only `OBSERVED` binds. §1.2 records what was not read. ⛔ **Nothing redesigned, consolidated, renamed, or promoted to a target component. No file written outside `docs/knowledge_tranfer/`.***

***PROPOSED — an ARB input.***
