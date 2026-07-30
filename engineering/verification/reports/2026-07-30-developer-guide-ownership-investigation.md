# Architectural Investigation — Developer-Guide Coverage and Artifact Ownership

**Date:** 2026-07-30 · **Role:** Chief Software Architect · **Commission:** DA instruction 2026-07-30 (investigation only; no code)
**Trigger:** ENG-006 (a slice shipped without a guide) and ENG-007 (the reminder could not detect it)
**Constraints honored:** frozen protocol untouched · no new governance rule · no redesign of the Developer-Guide process · **no implementation**

---

## Phase 1 — Architectural ownership map (evidence-based)

**The decisive discovery: there are TWO disjoint gate chains, with different owners.**

| Evidence | Finding |
|---|---|
| `grep` of `composer merge-gate` for any design script → **0 matches** | `composer merge-gate` (PHPUnit Architecture → Deptrac → PHPStan → GreenfieldCore) and the husky design chain (`verify.sh` → design/token/component/role gates) **share no members** |
| `package.json`: `"design-check": "bash scripts/design-check.sh"` · `.claude/UI_GUIDELINES.md` · `design-system.exceptions.json` | The design chain's authority is the **Design System**, not PB-007's merge gate |
| `.claude/platform/registry.yaml`: `AST-00x` entries carry `path:`, `component: CMP-00x`, and a five-question `trace:` (capability → context → principle → decision → adr) | **An architectural ownership registry already exists** — and `AST-006` *is* `dev-guide-reminder.sh`, the artifact under investigation |
| Registry coverage: 8 paths under `.claude/scripts/`; **0** for `.husky/`, `.github/workflows/` | The registry models **AI-platform assets only** |
| `developer_guide/color_theme/{01-tokens,02-button,03-card}.md` · `developer_guide/merge_gate/{00_index,01_stable_interface,02_ci_workflows}.md` | Two distinct guide areas already exist, matching the two chains |

**Ownership map:**

| Path | Architectural owner | Evidence |
|---|---|---|
| `scripts/design-check.sh` · `check-design-tokens.sh` · `component-audit.sh` · `structure-check.sh` · `check-domain-purity.sh` · `verify.sh` · `design-rules.json` · `ui-components.json` · `frontend-architecture.json` | **Design System / UI governance** | `npm run design-check`; UI_GUIDELINES + exceptions file are its authority; **disjoint from merge-gate**; guide area `color_theme/` |
| `scripts/check_roles.php` | **Security / RBAC governance** | Its own required-roles/permissions policy; relocated to CI by EG-003 |
| `scripts/lib/config-guard.sh` | **Design System tooling** *(shared library of the design chain)* | Sourced by the four design-chain scripts only; guards **project** gate configs, not `.claude` assets |
| `.husky/pre-commit` · `.husky/pre-push` | **Design System governance** | Both invoke the design chain (`lint-staged`, `verify.sh`) — neither invokes `composer merge-gate` |
| `.github/workflows/greenfield-merge-gate.yml` · `greenfield-quality-tier.yml` | **merge_gate** (PB-007) | Run `composer merge-gate` / `quality-gate` |
| `.github/workflows/role-permission-verification.yml` | **Security / RBAC** | Created by EG-003 for `check_roles.php` |
| `.github/workflows/knowledge-lint.yml` | **Knowledge platform (EKP)** | Runs `knowledge-lint` |
| `.claude/scripts/*` | **AI Engineering Platform** | Registered `AST-001..014` with `CMP` components and traces |
| `app/Contexts/<X>/**` | **Bounded context X** | Deptrac per-context layers; existing guide areas |
| `database/migrations/**` | **Ambiguous** — belongs to the context owning the table | No single owner derivable from the path |

## Phase 2 — Ownership vs. reminder behaviour

| Class | Instances |
|---|---|
| **Missing** | `scripts/**`, `.husky/**`, `.github/workflows/**` produce no code-area at all — the ENG-006 blind spot |
| **Incorrect (structurally impossible to express)** | `scripts/design-check.sh` (Design System) and `scripts/check_roles.php` (Security/RBAC) share a directory but **differ in owner**. Any prefix rule must assign them the same owner, so at least one is always wrong |
| **Ambiguous** | `database/migrations/**` (owner = the table's context) · `scripts/lib/config-guard.sh` (a shared library) |
| **Duplicated** | None found |

**Two mappings proposed earlier are refuted by this evidence:**

| Proposed | Verdict | Why |
|---|---|---|
| `scripts/design-check.sh → merge_gate` | **WRONG** | The chains are disjoint; design-check belongs to the Design System (`color_theme/`) |
| `scripts/lib/config-guard.sh → ai_platform` | **WRONG** | It is sourced only by the design-chain scripts and guards project gate configs; the AI platform's assets are the registered `.claude/scripts/*` |

*Both my own earlier proposal (`scripts/* → merge_gate`) and the example table refined from it would have encoded these errors — the investigation was the thing that caught them.*

## Phase 3 — Root cause

| Candidate cause | Verdict |
|---|---|
| Incomplete ownership model | **Contributing** — three areas unmapped |
| **Incorrect ownership model** | **PRIMARY** — a **category error**: the hook models ownership as *filesystem prefix*, but ownership is *architectural*. Files in one directory can have different owners (proved by `design-check.sh` vs `check_roles.php`), so no prefix rule can be correct in general |
| Implementation bug | **No** — the script does exactly what it is written to do |
| Missing documentation convention | **No** — the DoD convention exists and is clear |
| Architectural ambiguity | **Contributing** — genuinely unresolved for migrations and shared libraries |

**Root cause:** the reminder routes by *directory*, while the Definition of Done is owed per *architectural owner*. Extending prefixes cannot fix a category error; it only relocates it — which is exactly why the same component has now failed in **both** directions (too broad in PB-007, too narrow here).

## Phase 4 — Alternatives

| | **A. Extend prefix rules** | **B. Explicit ownership table in the hook** | **C. Architectural ownership registry (extend the existing one)** | **D. Ownership declared at the artifact, map derived** |
|---|---|---|---|---|
| DDD alignment | ✗ Routes by implementation detail; cannot express intra-directory divergence | ~ Correct routing, but the model lives inside tooling | ✓ Routes by the model; reuses the governed `path → component → trace` vocabulary — **no new ownership category invented** | ~ Model is authoritative but **scattered** across artifacts |
| Maintainability | ✗ Silent breakage on every new directory | ~ One file to edit, drifts from the registry | ✓ One governed source; registry-first workflow already binding | ✗ N edit sites; no single view |
| Scalability | ✗ Degrades as roots multiply | ~ Linear growth in a hook | ✓ Registry already scales (`AST-001..014`) | ~ Scales, but discovery requires a full scan |
| False positives | ✗ Demonstrated (PB-007) | ✓ | ✓ | ✓ |
| False negatives | ✗ Demonstrated (ENG-006) | ✓ | ✓ | ~ Unregistered artifact = silent |
| Traceability | ✗ None | ~ Path→area only | ✓ Full five-question trace already in the schema | ~ Local only |

**Not chosen on effort.** A is rejected as architecturally incorrect even though it is cheapest. B is *sufficient* but hides the model in a tool. **C is correct**: the ownership model already exists, is governed, is registry-first-binding, and already registers the very hook in question (`AST-006`).

## Phase 5 — Recommendation

**Adopt Option C: the architectural ownership registry becomes the single source of artifact ownership, and the reminder becomes a *reader* of it rather than a holder of its own model.**

- **Architectural decision.** Ownership is a property of the model, not of the filesystem. The registry already expresses exactly that (`path` → `component` → `trace`). Making the hook read it converts the reminder from an independent, drifting model into a projection of the governed one — and removes the class of defect rather than one instance of it.
- **Implementation impact.** (1) Register the project-side gate assets with their owners as established in Phase 1 — **including the two corrections above**. (2) Add a *documentation-area* attribution per component (which `developer_guide/<area>/` discharges its DoD). (3) `dev-guide-reminder.sh` (AST-006) resolves changed paths through the registry instead of its three regexes. (4) Resolve the two ambiguities explicitly: migrations attributed to the owning table's context; shared libraries attributed to the chain they serve.
- **Migration impact.** Existing `app/Contexts/<X>/` behaviour is preserved (the registry would reproduce it); no guide moves; no existing guide invalidated.
- **Backward compatibility.** Reminder behaviour changes only by *gaining* coverage — plus it will now fire correctly on the two paths currently mis-attributable. No previously-silent correct case becomes noisy.
- **Risks.** (a) An unregistered artifact is silent — mitigated because registry-first registration is already binding. (b) **Scope risk, flagged not assumed:** the registry is today the *AI Engineering Platform's* register (`CMP`/`AST` for `.claude` assets). Extending its scope to project-side assets is a **platform-governance decision**, not an engineering one — it must be ruled, not presumed. If that ruling is withheld, **Option B is the correct fallback**, with the explicit note that it duplicates a model the registry should own.

## Explicit statement on ENG-007's classification

**ENG-007 remains correctly classified as ordinary engineering debt for the *hook repair itself*** — no architecture changes, no ADR is touched, no protocol amendment is implied, and it does not block WP-3.

**But it is no longer purely mechanical, and the backlog entry should say so:** the investigation converted a "map more prefixes" task into (i) a root cause that is a **category error**, (ii) **two corrected ownership facts** that any prefix fix would have encoded wrongly, and (iii) **one genuine architectural question** — whether the platform registry's scope extends to project-side assets — which requires a platform ruling before Option C can be implemented. Recommend annotating ENG-007 with that dependency, and recording the Phase-1 ownership map as its input so the work does not restart from directory guesses.

---

**Traceability:** DA investigation commission 2026-07-30 · triggers ENG-006/ENG-007 (`docs/implementation/backlog/BACKLOG.md`) · evidence: `composer.json` merge-gate composition · `package.json` design scripts · `.claude/UI_GUIDELINES.md` · `design-system.exceptions.json` · `.claude/platform/registry.yaml` (AST-006 = the hook under investigation) · `developer_guide/{color_theme,merge_gate}/` · `.claude/scripts/dev-guide-reminder.sh` mapping regexes. Gathered read-only; **no artifact modified by this investigation.**
