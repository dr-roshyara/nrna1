# Architecture Knowledge Transfer (AKT) — Part 6

**Repository Architecture**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 6 of 10 + Appendix** |
| **Part** | **Part 6 — Repository Architecture** |
| **Baseline** | **ES-005 PROPOSED** (the four rules in force in practice) · **R-37 structural freeze IN FORCE** · documentation roots **ESTABLISHED, Phase 2 BLOCKED** |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Part 2 §7** (repository philosophy) · **Part 5 §7** (the placement decisions). This Part is the *physical* map |

---

## 0. The one diagram that explains the whole repository

```
                    PublicDigit Repository

              ┌───────────────────────────────┐
              │           PRODUCT             │   what PublicDigit IS
              │───────────────────────────────│
              │  app/   tests/   docs/        │
              │  architecture_legacy/         │
              │  resources/  routes/  config/ │
              └───────────────────────────────┘
                             ▲
                      engineered using
                             │
              ┌───────────────────────────────┐
              │   ENGINEERING PLATFORM        │   HOW it is engineered
              │───────────────────────────────│
              │  engineering/                 │
              │    governance/  architecture/ │
              │    knowledge/   verification/ │
              └───────────────────────────────┘
                             ▲
                        executed by
                             │
              ┌───────────────────────────────┐
              │      RUNTIME ADAPTER          │   HOW the current engine executes
              │───────────────────────────────│
              │  .claude/                     │
              └───────────────────────────────┘
                             ▲
                             │
                     Execution Engine
              (AI assistant or human engineer —
                 REPLACEABLE, never the center)
```

> ### **ES-005.1 — The Three-Concern Separation.** *(EM-001, ARB 2026-07-10)*
> **`docs/` + `architecture_legacy/` + `app/` + `tests/` = Product · `engineering/` = Engineering Platform · `.claude/` = Runtime mount point.**
>
> ### **The mount never moves and is never "the architecture."**

**The bottom box is the point of the whole arrangement:** the execution engine — *today Claude; tomorrow possibly not* — is **replaceable and never the center.** The platform is provider-independent by construction, which is why the EEP says *"in any project, in any language, by any engineer — human or automated."*

**Audit trail for the separation:** `engineering/MIGRATION_REPORT.md` (migration EM-001, ARB-approved 2026-07-10). **Authority:** ADR-AIP-01 (Baseline v1.0) + ADR-AIP-02 (Product Primacy).

---

## 1. Top-level inventory

| Path | Concern | Notes |
|---|---|---|
| `app/` | **Product** | Laravel application. **`app/Contexts/` holds the bounded contexts** (Part 2 §2.2) |
| `tests/` | **Product** | five test suites (§4.2) |
| `docs/` | **Product** | documentation, ADRs, implementation records, the three new domain roots |
| `architecture_legacy/` | **Product** | ⚠️ **LEGACY** — the former `architecture/` folder |
| `developer_guide/` | **Product** | ⚠️ **LEGACY** — *"`developer_guide` folder is also a legacy folder; new developer guides must come inside `./docs`"* |
| `engineering/` | **Engineering Platform** | ⛔ **UNDER R-37 STRUCTURAL FREEZE** — bug fixes, broken-link fixes, typo corrections only |
| `.claude/` | **Runtime mount** | session state, plans, memory, protocol, platform registry, hooks |
| `claude/` | ⚠️ **anomaly** | a second, lowercase directory *(historical plan files; see §6.3)* |
| `scripts/` | **Product tooling** | 25 files — resolvers, linters, gates (§4.3) |
| `config/` `routes/` `resources/` `database/` `public/` `bootstrap/` | **Product** | standard Laravel |
| `build/` `bin/` `node_modules/` `vendor/` | generated / vendored | not governed |

---

## 2. `engineering/` — the Engineering Platform

### 2.1 Layout

```
engineering/
├── README.md                    ← entry point; reserved-namespaces table
├── MIGRATION_REPORT.md          ← EM-001 audit trail
├── governance/                  ← THE STANDARDS
│   ├── STANDARDS_INDEX.md       ← ES-001..006 index + Decision Authority Matrix + stopping rule
│   ├── ES-001-Engineering-Constitution.md
│   ├── ES-002-Engineering-Execution.md
│   ├── ES-003-Qualification.md
│   ├── ES-004-Documentation.md
│   ├── ES-005-Repository.md
│   ├── ES-006-Engineering-Knowledge-Governance.md
│   └── Engineering_Execution_Protocol.md   ← the EEP (STABLE)
├── architecture/
│   ├── baseline/                ← ⛔ SEALED CORPUS (AIP-01..14 · PD-01..20 · FF-01..17)
│   ├── adr/                     ← ADR-AIP-01/02 + ADR-AIP-LOG (the rulings register)
│   ├── reference/               ← what the platform IS (DRAFT→ADOPTED→STABLE)
│   └── c4/                      ← platform views + the Engineering Knowledge System reference model
├── knowledge/
│   ├── methodology/             ← ⭐ THE CANON DIRECTORY
│   │   ├── DDD_Tactical_Governance_Principles.md   (ADOPTED)
│   │   └── Layer_Verification_Rule.md              (PROPOSED)
│   └── patterns/
├── verification/
│   ├── reports/                 ← every commission's evidence (~100+ files)
│   └── qualification/           ← OQ instruments
└── developer_guide/
```

### 2.2 The rules that govern this directory specifically

| Rule | Effect |
|---|---|
| **R-37 structural freeze** | **only bug fixes, broken-link fixes, typo corrections.** *What it constrains is **SELF-DIRECTED editing**; it has never constrained the Authority's own acts* |
| **R-38 conceptual freeze** | **no new concepts, standards, decisions or architectural subsystems** — changes enter only through operational evidence via the ES-006.1 promotion ladder |
| **R-30 sealed corpus** | `architecture/baseline/` and the sealed proposals: **moves allowed, edits NEVER** |
| **ES-005.2 folder rule** | **a directory exists only when its first artifact arrives.** Reserved namespaces are **documented in the README table, never created speculatively** |
| **Append-only** | `architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — rulings are appended, never rewritten |
| **The placement guard** | `.claude/scripts/engineering-placement-guard.sh` fires **non-blocking** on any **NEW** file under `engineering/`, with **sharper text for `knowledge/methodology/`** — the canon directory |

**⭐ How the guard works, and why it matters as a pattern:** it **derives the governing standards at runtime** by reading `STANDARDS_INDEX.md` and matching the concept names *promotion ladder* and *placement litmus* in its Hosts column. **It quotes no rule text and hardcodes no standard number** — so renumbering, supersession or rehoming is followed automatically. *(The first version embedded the rule text and the ES numbers, **making runtime tooling a second home for governance**. Caught in review.)*

**It asks four generic questions** — what governs **maturity** · what governs **placement** · what **evidence** supports this location · what **exception** authorizes it *(and is that exception ruled, named, and given a validation expectation)* — then requires one outcome: **COMPLIANT / APPROVED EXCEPTION / REQUIRES ARB DECISION.**

**Silent for:** existing files *(an edit is not a placement act)* · `verification/reports/**` *(verification output)* · anything outside `engineering/`.

---

## 3. `docs/` — Product documentation, mid-transition

### 3.1 The three canonical domain roots

```
docs/
├── publicdigit/     ← product-specific · domain: publicdigit
├── knowledgeos/     ← product-specific · domain: knowledgeos
└── pks/             ← product-specific · domain: pks
```

**Each was created WITH a README as its first artifact** — *satisfying ES-005.2 rather than working around it.* **Each README is ~15–20 lines**: purpose · holds · owner · internal-layout ownership · a pointer table (**policy** → the ADR · **configuration** → `documentation-placement.yaml` · **resolver** → `doc-placement.php`). ⛔ **Everything else belongs in the ADR.**

**Actual occupancy (verified 2026-08-01):**

| Root | Contents | Reading |
|---|---|---|
| `docs/publicdigit/` | **11 architecture documents placed today**, incl. `WP-7C_Engineering_Readiness.md` *(placement **derived**, not chosen)* | ⭐ **the roots are IN REAL USE FOR NEW WORK, not merely established.** *Stated at true strength: placed **consistently with** the derivation rule; **whether the resolver was consulted is not observable** — adoption evidence, not proof of process* |
| `docs/pks/` | the documentation-debt observation *(first artifact in a derived root)* | first real derived placement |
| `docs/knowledgeos/` | **only its README** | ⚠️ **root established, PURPOSE UNDECIDED** — OQ-5 |

**⚠️ And the counter-signal, recorded alongside:** `docs/implementation/` grew **183 → 185** and `PKS_*` **89 → 91** on the same day. ***New product-specific documents still land in the mixed folder. The mechanism exists; the habit does not yet.***

### 3.2 The pre-transition layout, which is still where most documents live

```
docs/
├── implementation/   ← 185 files · 91 PKS_* + 3 KnowledgeOS_* ← ⚠️ THE MIXED FOLDER
│   ├── Implementation_Process_v1.0.md / _v1.1_Draft.md   (the EP rules)
│   ├── PushB_Architecture_Blueprint.md   (FROZEN v1.0, IMMUTABLE)
│   ├── Canonical_Event_Catalog_v1.0.md
│   ├── PKS_* (91)  ← Phase I/II/III records, review framework, MCA, CDR
│   ├── KnowledgeOS_* (3)
│   ├── EPIC-004_* (roadmap, Q-2 resolution package)
│   ├── PROGRAM_STATUS.md · backlog/BACKLOG.md · EPIC-001_Greenfield_Core.md
├── adr/              ← ADR-S · ADR-UL · ADR-PL · ADR-PC · ADR-T log · README (the class index)
├── architecture/     ← design/ · c4/ · principles/ · patterns/ · governance/ · contexts/ · papers/
├── knowledge/        ← the EKP: portal/ · _meta/ · schema/ · domains/ (40 cards)
├── plans/            ← governed plans (ES-004.2 naming)
└── (root)            ← ~35 mixed legacy files (ARCHITECTURE.md, TESTING_GUIDE.md, …)
```

### 3.3 ⛔ The transition is Phase 1 of 2, and Phase 1 has not started

| Phase | Content | State |
|---|---|---|
| **1** | approve the ADR *(**no files move**)* → **classify every document** (Scope · Steward · Maturity · Domain) → deliverable: **a classification map** | ⚠️ **NOT STARTED** |
| **2** | create roots → move files → update cross-references | ⛔ **BLOCKED** — prerequisite: ***does R-37's freeze bind `docs/`?*** unanswered |

> **Verified statement of record: Documentation Migration = NOT STARTED. ZERO documents migrated.** ⚠️ *A status table once read "✅ Complete" — **which would have retired a gate nobody ruled on.*** **Both this and link recovery were left open BY ARB DECISION, not by omission.**

### 3.4 The Engineering Knowledge Platform (`docs/knowledge/`)

```
docs/knowledge/
├── portal/INDEX.md            ← role-based navigation, topic hubs, recipes
├── Knowledge-Constitution.md  ← the rules
├── _meta/
│   ├── lifecycle.md
│   ├── knowledge-card.template.md
│   ├── classification-map.template.md
│   └── documentation-migration-checklist.md
├── schema/                    ← ⭐ WHERE ENUMERATIONS LIVE
│   ├── bounded-contexts.yaml          (the controlled vocabulary — see Part 2 §2.2.1)
│   ├── statuses.yaml · authorities.yaml · knowledge-types.yaml
│   ├── knowledge-audiences.yaml · knowledge-relationships.yaml
│   ├── documentation-placement.yaml   ← the placement single source of truth
│   └── repository-migrations.yaml     ← the declarative migration registry
├── domains/adjudication/      ← the reference-model pilot
└── ai/                        ← ⚠️ AI-generated knowledge enters here as `authority: generated`
                                  and is NEVER authoritative without human review
```

**Validation:** `npm run knowledge-lint` (*"PHPStan-for-knowledge"*) · `npm run knowledge-graph`.

**⚠️ Scope trap, and it is the single most misleading gate in the repository:** **`knowledge-lint` validates `docs/knowledge/` ONLY.** A repo-wide scan found **121 broken links where the linter reported 9.** ***GREEN LINT IS NOT A GREEN REPOSITORY.* Always record the baseline before and after.**

---

## 4. `.claude/` — the runtime mount

### 4.1 Layout

```
.claude/
├── CLAUDE.md                  ← project instructions (POINTERS ONLY — never restates EP rules)
├── MEMORY.md                  ← stable, durable knowledge · NO tasks, NO daily progress
├── CONTEXT.md                 ← the current working state ⚠️ see Part 1 §7.5
├── IMPLEMENTATION_PROTOCOL.md ← the 17 phases (FROZEN for routine work)
├── UI_GUIDELINES.md · TEST_DATABASE_SAFETY.md
├── sessions/YYYY-MM-DD.md     ← ⛔ APPEND-ONLY daily logs
├── plans/                     ← WP-1..WP-7 plans + AIP/EM/PB plans
├── memory/                    ← archived governance track (INDEX.md · ddd_program_state.md)
├── platform/
│   ├── registry.yaml          ← RUNTIME CONFIGURATION, machine-readable
│   └── OPERATING_INSTRUCTIONS.md  ← AST-nnn behaviours (incl. AST-013 promoted behaviours)
├── scripts/                   ← hooks: discipline-gate-reminder · dev-guide-reminder ·
│                                 engineering-placement-guard · ddd-principles-reminder
├── runtime/                   ← per-day file logs, state JSON, reminder flags
├── worktrees/
└── settings.json / settings.local.json
```

### 4.2 The registry's boundary — a ruled question, not a convention

**`platform/registry.yaml` self-declares as *"RUNTIME CONFIGURATION, machine-readable"***, sits in the platform `loading_order` boot sequence, types every asset by `runtime_moments` from a **closed AI-session enum**, and admits assets only on a **five-question lineage ending in an AIP principle · a platform decision (PD-nn) · or ADR-AIP-01** — *"an asset that cannot answer them shall not be registered — and shall not exist."*

> ### **R-42: the registry's scope does NOT extend to project-side engineering artifacts. It governs Engineering Platform assets ONLY.**
> **Project scripts, git hooks and CI workflows execute at NO AI runtime moment and carry NO AIP lineage — *registering them would require FABRICATING TRACEABILITY*.**

**Consequence:** `.claude/scripts/engineering-placement-guard.sh` is **deliberately unregistered**. **And it obeys its own litmus:** *it is runtime session tooling, so ES-005.3 puts it in `.claude/scripts/`, not in `engineering/`.*

### 4.3 Hooks — checkpoints, not walls

| Hook | Fires | Behaviour |
|---|---|---|
| `discipline-gate-reminder.sh` | **PreToolUse** — a **new** test or production file is about to be created | reminds to confirm the upstream artifacts exist |
| `dev-guide-reminder.sh` | **Stop** | **area-aware** — nudges per code-*area* changed today that has no matching guide update, *so a guide written for an unrelated track can't silence a real gap* |
| `engineering-placement-guard.sh` | **PreToolUse** (Write\|Edit) — a **new** file under `engineering/`, or a domain-mixed location under `docs/` | four generic questions; derives standards at runtime |
| `ddd-principles-reminder.sh` | AST-014 | tactical-DDD principles |

> **All non-blocking, on purpose.** ***A wall that is always dismissed teaches less than a checkpoint that is read*** — and **a hook cannot evaluate the answers to its own questions; only the author can.**

**⚠️ A bash lesson recorded from building these:** ***`case` globs SPAN `/` — `docs/pks/x.md` matches `docs/*.md`.*** **Path tests that need precision belong in PHP, not in a case glob.** *Caught by testing the NEGATIVE cases.*

### 4.4 Runtime vs persistent — the authority difference

| | **Runtime (`.claude/`)** | **Persistent (repo proper)** |
|---|---|---|
| **Lifetime** | the current adapter | the programme |
| **Authority** | ⚠️ **hints and current state — NEVER constitutional truth** | **canonical** |
| **Mutability** | MEMORY/CONTEXT mutable · **sessions APPEND-ONLY** | decision text and history **never rewritten** |
| **End state** | ***MEMORY = runtime hints only*** | ***Standards = constitutional truth*** |

---

## 5. Reserved namespaces and the folder rule in practice

> ### **ES-005.2 — a directory exists only when its first artifact arrives.**
> **Reserved namespaces are DOCUMENTED (the `engineering/README.md` table), never created speculatively. Empty directories are removed on discovery** *(verified-empty only)*.

**Three worked examples of the rule being obeyed rather than worked around:**

| Case | Resolution |
|---|---|
| the three documentation roots | **a README is each root's first artifact** |
| `RuntimeAdapters/` | ⛔ **not created — one implementation is a hypothesis, not a demonstrated abstraction.** Trigger: **a SECOND runtime** |
| the `ExternalPlatform` Deptrac layer | **a recorded future refinement**, so everything becomes *covered-or-intentionally-ignored* |

---

## 6. Legacy, anomalies and known repository debt

### 6.1 The ruled legacy folders

`docs/adr/20260801_1712_legacy_folder_and_files.md`: **legacy architecture → `architecture_legacy/`** · **developer guides consolidated into `developer_guide/`, itself a legacy folder** · ⭐ **new developer guides go under `./docs`.**

### 6.2 `developer_guide/` — large, legacy, and unvalidated

**~100+ files at the root of a legacy folder** (`01-overview.md` … `08-login-flow.md`, plus ~90 SCREAMING_SNAKE files: `ARCHITECTURE.md`, `API_GUIDE.md`, `CI_CD_GUARDRAILS.md`, `BUG_FIXES_20260314.md`, …). **The `<area>/00_index.md` structure the Definition of Done requires applies to *new* area folders** (e.g. `developer_guide/audit_system/`), **not to this legacy root.**

> **⚠️ `knowledge-lint` does not validate this folder.** *The areas carrying the documentation debt — `developer_guide/`, `architecture_legacy/` — have **NO LINK VALIDATION AT ALL**.* **ENG-011 (repo-wide validation) exists for exactly this.**

### 6.3 The two `claude` directories — a RULED LEGACY MISTAKE, not a second mount

**Both `.claude/` (the ruled runtime mount) and `claude/` (lowercase, no dot) exist at the repository root.**

> ### **Human authority, stated 2026-08-01: *"it was a legacy mistake that I requested to write inside `claude` folder. Actually it should have been written `.claude` folder."***
>
> **This CLASSIFIES the directory: `claude/` is not a deliberate second concern, an alternative mount, or a product folder. It is a misplacement of runtime-mount content.** *ES-005.1 names `.claude/` as the mount; `claude/` was never a ruled location.*

**⚠️ Classification is not authorization.** *Per Part 4 §2.1, **classification precedes placement** — the classification is now settled, and the **correction is a separate, governed act** (§6.3.2). Recording what an artifact **is** does not move it.*

#### 6.3.1 What is actually in there — 82 files, 1.4 MB, and it is not junk

| Subdirectory | Contents |
|---|---|
| `claude/audits/` (**~47 files**) | the **constitutional/sovereignty audit series** — `C5a`..`C5i` (failure classification · hidden sovereignty · replay stability · sovereignty topology archaeology · stabilization certificate · convergence · resolver exclusivity · scalar sovereignty · early-return sovereignty · projection leakage), the `D_5_5_*` convergence/mutation audits, plus `AuthorityOwnershipMatrix`, `BusinessInvariantCatalog`, `DomainEventOwnershipMatrix`, `PublishedLanguageMatrix`, `CoreDomainIdentification`, `ContextRelationshipMatrix` |
| `claude/governance/` (**6 files**) | `CanonicalVocabularyRules` · `ConstitutionalLegitimacyDerivationDoctrine` · `ConstitutionalObservationDoctrine` · `ConstitutionalUbiquitousLanguage` · `SemanticBoundaryMap` · `TacticalSemanticAlignment` |
| `claude/plans/` (**28 files**) | dated plans + the codename plans, incl. ⭐ **`swirling-jingling-blossom.md`** |
| `claude/prompts/` (1) · root (3) | `IMPLEMENT_SSOT_PLAN` · `ConstitutionalVocabularyDoctrine` · `PolicyPurityRules` · `SSOT_QUICK_REFERENCE` |

> **⚠️ This is a substantial body of constitutional and ownership-audit work, not scratch files.** *Several titles cover ground the current governance vocabulary also covers — **which makes it a candidate for the classification map, not for deletion.***

#### 6.3.2 ⛔ Why a move is a GOVERNED act — the register cites into it

**`claude/plans/swirling-jingling-blossom.md` is cited by R-36** as the AI-Architecture Promotion Review matrix — **inside `ADR-AIP-LOG-Platform-Rulings.md`, which is APPEND-ONLY and whose decision text must NEVER be edited.**

**Full citation inventory (verified):**

| Citing artifact | Mutable? |
|---|---|
| **`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`** (R-36) | ⛔ **NO — append-only; decision text is never edited** |
| ⭐ **`app/Domain/Election/Enum/VoterSourceStrategy.php:35`** — a docblock `@see Plan: claude/plans/snappy-foraging-codd.md` | ✅ yes — **but it is PRODUCTION CODE** |
| `.claude/platform/registry.yaml` | ✅ yes |
| `.claude/memory/INDEX.md` (`C5a`, `C5b`, `fancy-tinkering-newell`, `phase1_parity_verification_specification`) | ✅ yes |
| `.claude/memory/ddd_program_state.md` (`cheerful-wishing-lighthouse`) | ✅ yes |
| `docs/architecture/discovery/Round17_Codebase_Map.md` (documents `claude/plans/` as a structural location) | ✅ yes |
| `architecture_legacy/election/election_state_machine/20260518_ARCHITECTURE_APPROVED.md` | ✅ yes |
| cross-references **inside** `claude/` itself (numerous — `fancy-tinkering-newell.md` alone carries ~12) | ✅ yes |

> **⚠️ The code reference is the one that widens the scope.** *A relocation would leave a stale `@see` path in `app/Domain/Election` — and **no link checker covers PHP docblocks**: `knowledge-lint` validates `docs/knowledge/` only, and `link-check.php` scans markdown references. **This citation would break silently.***
>
> **Method note, recorded because it is the reusable lesson:** *this reference was missed by a markdown-globbed search and found only by a full-repository scan across `*.md`, `*.php`, `*.yaml`, `*.json`, `*.sh`.* ***A path-reference inventory scoped to documentation is not an inventory.***

> ### **The register's citation is exactly what the DECLARATIVE MIGRATION REGISTRY exists to solve.**
> **A documented migration keeps an old path RESOLVABLE without editing the artifact that cites it** — the precedent is `architecture/` → `architecture_legacy/`, recorded in `repository-migrations.yaml` with its source ADR. ***A documented migration is evidence; a filename heuristic is not.***

#### 6.3.3 ⚠️ A COLLISION that would silently destroy content

**`lovely-singing-pelican.md` exists in BOTH directories with DIFFERENT content:**

```
claude/plans/lovely-singing-pelican.md    2,626 bytes
.claude/plans/lovely-singing-pelican.md   8,647 bytes
```

**Verify:** `comm -12 <(ls claude/plans/ | sort) <(ls .claude/plans/ | sort)`

> ### ⛔ **A naive `mv claude/plans/* .claude/plans/` would OVERWRITE the 8,647-byte governed file with the 2,626-byte legacy one — a silent, plausible-looking data loss of exactly the class recorded for the discarded first link-repair attempt.**
>
> **Any consolidation MUST resolve this one file explicitly, by a human reading both.**

#### 6.3.4 The in-discipline correction sequence

**Recorded as the prepared path, NOT executed. Each step names its authority:**

```
1. RULING / ADR records the migration  claude/ → .claude/           [authority act]
     — same shape as docs/adr/20260801_1712_legacy_folder_and_files.md
2. REGISTRY ENTRY in docs/knowledge/schema/repository-migrations.yaml
     (id · from · to · kind · source ADR)                          [a registry edit, not code]
3. RESOLVE the lovely-singing-pelican collision explicitly          [human reading both]
4. git mv  (preserves rename records → link-check confidence 100)   [engineering]
5. RECORD the link-check + knowledge-lint baseline BEFORE and AFTER [engineering]
6. UPDATE the MUTABLE citing artifacts only                         [engineering]
     ⛔ never the rulings register — R-36 stays resolvable via step 2
7. CLASSIFY the 82 files for the classification map                 [separate act; Phase 1]
```

**⚠️ And the open prerequisite that gates step 4:** *does R-37's structural freeze bind repository reorganizations outside `engineering/`?* **The same unanswered question that blocks documentation Phase 2.** *R-37's terms name `engineering/`; whether *"no more document reorganizations"* reaches a root-level runtime-adjacent folder **has not been ruled.***

> **Recorded honestly: the classification is settled by human statement; the correction is not authorized, and no file has been moved.**

### 6.4 The recorded repository debt inventory

| Item | State |
|---|---|
| **53 broken references** | **classified, counted, attributed and owned — NOT repaired.** 47 **never written** (documentation debt predating the refactor) · **6 AMBIGUOUS**, needing a human choice |
| **The mixed folder** | 185 files in `docs/implementation/`; **the PKS corpus is SPLIT across `docs/implementation/` and `docs/pks/`. Search both** |
| **92 artifacts non-conformant on ES-005 issuance** | prefer a **TRANSITIONAL NON-CONFORMANCE** record over a self-nullifying fallback |
| **`app/Contexts/Election` unregistered** | Part 2 §2.2.1 |
| **`PROGRAM_STATUS.md` dated 2026-07-11** | ⚠️ **stale** — it reports *"between epics, next milestone EPIC-002"* while EPIC-004 WP-7 is mid-flight |
| **ENG-010 / ENG-011** | documentation integrity · repo-wide validation — **OPEN** |
| ⭐ **`claude/` — 82 files misplaced outside the runtime mount** | **CLASSIFIED as a legacy mistake by human statement (§6.3); correction NOT authorized.** Carries a **register citation (R-36)** and a **content-destroying filename collision.** *The 82 files are also unclassified for the Phase 1 map* |

---

## 7. Rules for placing a new artifact — the operational checklist

```
1. Is it ACTIVE SESSION STATE?              → .claude/  (the runtime mount)
2. Could a DIFFERENT PROJECT adopt it UNCHANGED?   (ES-005.3 litmus)
        YES → engineering/   ⚠️ but R-37: is this a new file under a freeze?
        NO  → the project
3. For a project document, RESOLVE the location — never choose it:
        php scripts/doc-placement.php --scope=<...> [--maturity=<...>] [--domain=<...>]
4. Exit code 2?  → record PENDING and ESCALATE. NEVER invent a destination.
5. Creating a NEW DIRECTORY? → ES-005.2: does its FIRST ARTIFACT exist right now?
6. Is this a COPY of something?  → ES-005.4: reference it instead. One rule, one home.
```

**And the four questions the guard will ask you anyway:** what governs **maturity** · what governs **placement** · what **evidence** supports this location · what **exception** authorizes it — *ruled, named, and given a validation expectation?*

---

## Traceability

**Primary sources:** `engineering/README.md` (the three-concern diagram, reserved namespaces) · `engineering/governance/ES-005-Repository.md` (ES-005.1–.4) · `engineering/MIGRATION_REPORT.md` (EM-001) · `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` · `docs/adr/20260801_1712_legacy_folder_and_files.md` · `docs/knowledge/schema/*.yaml` · `docs/knowledge/_meta/knowledge-card.template.md` · `.claude/` and `docs/` directory inventories (read at authoring) · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (**R-30 · R-37 · R-38 · R-42**) · `engineering/verification/reports/2026-08-01-programme-state-verification.md` (the three corrected states, the `docs/publicdigit/` occupancy, the counter-signal) · `.claude/MEMORY.md` (the guard's design, the bash lesson, the debt inventory) · `.claude/sessions/2026-08-01.md`.

**New observations recorded by this Part (routed, not enacted):** §6.3 — **two `claude` directories** (`.claude/` ruled, `claude/` historical) coexist at the repository root on a case-insensitive filesystem.

**Supersedes:** nothing. **Depends on:** Parts 1–5.
