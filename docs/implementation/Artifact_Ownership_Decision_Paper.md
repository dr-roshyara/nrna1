# Artifact Ownership — Platform Architecture Decision Paper

**Status:** DRAFT — **awaiting Decision Authority ratification** · **Authority:** Generated (Chief Software Architect commission, 2026-07-30)
**Resolves:** the open question deferred by `2026-07-30-developer-guide-ownership-investigation.md` — *does the Platform Registry become the authoritative ownership registry for project-side engineering artifacts?*
**Precedent for form/placement:** `Placement_Rule_Decision_Paper.md` (same shape: a paper awaiting DA decisions). **No code. No governance document modified. No protocol amendment.**

---

## Phase 1 — Platform boundary verification (the registry's constitutional responsibility)

Evidence from the registry's own preamble (`.claude/platform/registry.yaml`):

| Evidence (verbatim or structural) | What it establishes |
|---|---|
| *"**RUNTIME CONFIGURATION**, machine-readable"* · *"Human documentation is GENERATED from this file — never the reverse"* | The registry exists to **configure the AI platform's runtime**, not to catalogue project assets |
| `loading_order: [configuration, registry, knowledge, rules, hooks, commands, agents]` | It is a member of the platform's **boot sequence** |
| `runtime_moment_enum: [SESSION_START, PRE_ACTION, POST_ARTIFACT, SESSION_END, ON_DEMAND]`, and every asset carries `runtime_moments` | An **asset** is something that **executes at an AI runtime moment** |
| Admission rule: every asset answers five questions — capability → bounded context → **architecture principle (AIP-nn)** → **platform decision (PD-nn)** → **ADR (ADR-AIP-01)**. *"An asset that cannot answer them shall not be registered — **and shall not exist**."* | Registration requires **AIP-lineage traceability** |
| *"Keep this file small, authoritative, machine-readable."* | Bounded by design |

**Applying the admission rule to the artifacts in question:**

| Artifact | Executes at an AI runtime moment? | Can answer AIP principle / PD / ADR-AIP-01? |
|---|---|---|
| `scripts/design-check.sh`, `verify.sh`, `component-audit.sh`, … | **No** — they run in git hooks and npm scripts | **No** — governed by the Design System (`UI_GUIDELINES`, exceptions file) |
| `.husky/pre-commit`, `pre-push` | **No** — git lifecycle, not AI session lifecycle | **No** |
| `.github/workflows/*` | **No** — CI | **No** — governed by PB-007 / RBAC policy |
| `.claude/scripts/*` | **Yes** (`PRE_ACTION`, `SESSION_END`, …) | **Yes** — AST-001..014 already do |

### Phase 1 conclusion — the deferred question is now ANSWERED, not merely pending

**No. The Platform Registry must not become the ownership registry for project-side artifacts.** Registering them would require **fabricating** AIP-principle and platform-decision traces they do not have — which the registry's own admission rule prohibits in the strongest available terms (*"shall not be registered — and shall not exist"*). Three independent platform constraints agree: **AIP-14 Product Primacy** (the platform serves delivery; it does not absorb product concerns) · **R-37 structural freeze** (*"every architectural idea is guilty until proven necessary"*; expansion rejected by default) · the standing question *"smallest platform change that unlocks the next capability?"* — and the smallest change here is **none**.

**Consequence for the investigation's Option C: it is REFUTED by the registry's constitution, not "preferred pending approval."** The investigation was right to defer the question and right not to presume the answer; the answer, once the registry's own text is read, is negative.

## Phase 2 — Responsibility analysis (the clarifying result)

**Two distinct concepts had been conflated, and that conflation caused every wrong mapping in this thread:**

| # | Concept | Example | Metadata class | Needed by the reminder? |
|---|---|---|---|---|
| 1 | **Architectural ownership** — which context/capability owns the artifact | `design-check.sh` → Design System | **Project / architecture metadata** | **No** |
| 2 | **Documentation-routing coverage** — which `developer_guide/<area>/` discharges the DoD for it | `design-check.sh` → `color_theme/` | **Documentation metadata** | **Yes** |

The reminder answers exactly one question: *"which guide area owes a guide for what changed today?"* That is (2). Every failed proposal in this thread — `scripts/* → merge_gate`, `config-guard.sh → ai_platform` — tried to answer (2) by asserting (1), which is why each felt wrong and each was wrong.

**Therefore project-artifact ownership, for ENG-007's purpose, is documentation metadata.** Machine-readable *architectural* ownership is a larger question that ENG-007 does not need and this paper does not open.

## Phase 3 — Options re-evaluated

| Criterion | **A** prefix routing | **B** table inside the reminder | **C** platform registry | **D** declared at the owner |
|---|---|---|---|---|
| DDD alignment | ✗ routes by implementation detail | ~ correct routing, model hidden in a tool | ✗ **wrong bounded context** (platform runtime ≠ project docs) | ✓ declared by the concept's owner |
| Single source of truth | ✗ none | ~ central, but away from the owner | ✗ would need fabricated traces | ✓ one declaration per area |
| Architectural cohesion | ✗ | ~ | ✗ mixes platform runtime config with documentation routing | ✓ obligation and declaration co-located |
| Separation of concerns | ✗ | ~ one file everyone edits | ✗ | ✓ |
| Platform responsibility | n/a | n/a | ✗ **violates the admission rule** | ✓ platform keeps its boundary |
| Maintainability | ✗ silent breakage per new root | ~ drifts from reality | ✗ | ✓ new area self-registers |
| Traceability | ✗ | ~ path→area only | — | ✓ coverage traces to the guide that discharges it |
| Operational complexity | low | low | high (governance + fabrication) | low (read N small declarations) |

**Status:** A **rejected** (category error) · B **valid fallback** · C **REFUTED** (Phase 1) · **D SELECTED**.

## Phase 4 — DDD consistency review of the selected option

| Property | Preserved? | How |
|---|---|---|
| Bounded contexts | ✓ | Each guide area declares only its own coverage; no area speaks for another |
| Ubiquitous language | ✓ | Reuses `developer_guide/<area>` — **no new ownership category invented** |
| Ownership boundaries | ✓ | **The declarer is the discharger** — the area that owes the guide declares what it covers, so declaration and obligation cannot drift apart |
| Architectural autonomy | ✓ | Adding an area requires no central edit |
| Explicit dependencies | ✓ | The reminder depends on declarations, never on directory layout |
| Single ownership declaration | ✓ | One area claims a path. **Two claims = a detectable conflict; zero claims = an "unclaimed path" signal** — both are findings, not silence |

**Violations found: none.** Residual risk: an unclaimed path is only surfaced, not prevented — which is strictly better than today, where it is invisible.

## Phase 5 — Decision

### Decision

**Option D — ownership is declared by the owner, and the reminder derives routing from those declarations.** Concretely: each `developer_guide/<area>/00_index.md` carries a machine-readable declaration of the code paths that area covers; `dev-guide-reminder.sh` (AST-006) resolves changed paths through those declarations instead of its three regexes; paths claimed by no area are reported as **unclaimed** rather than ignored.

### Architectural rationale

The reminder's question is a **documentation-routing** question (Phase 2), so its answer belongs to the **documentation** model, declared by the areas that discharge the obligation. This satisfies the investigation's invariant — *ownership is declared exactly once, and all engineering tooling derives its routing from that declaration* — while keeping the AI platform's registry inside the constitutional boundary its own admission rule defines (Phase 1). It invents no vocabulary and requires no governance expansion.

### Responsibilities

| Responsibility | Owner |
|---|---|
| Documentation-coverage declaration | **Each guide area** (in its `00_index.md`) |
| Documentation routing (derivation) | **`dev-guide-reminder.sh` / AST-006** — a *reader*, never a holder of the model |
| Reminder behaviour + hook maintenance | **AI Engineering Platform** (it owns the hook; it does **not** own the map) |
| Platform registry maintenance | **AI Engineering Platform** — scope **unchanged** by this decision |
| Machine-readable *architectural* ownership | **Not opened** — unneeded for ENG-007; a separate question if ever required |

### Consequences

**Positive.** No platform-scope violation and no fabricated traceability · declaration co-located with the obligation, so the two cannot drift · areas are autonomous · the previously invisible "unowned path" case becomes an explicit signal · both historical defect directions are removed (too-broad and too-narrow), because prefixes stop being the routing mechanism.

**Negative / trade-offs.** N declaration sites instead of one central file (mitigated: each is small and lives where it is edited) · the reminder must read ~40 small files per run (negligible at `SESSION_END`) · areas lacking an index must gain one — e.g. `developer_guide/color_theme/` currently has no `00_index.md` (an ENG-007 implementation task, not a decision) · an unregistered area is silent until it declares.

### Migration strategy (no duplicate model at any point)

1. Add coverage declarations to existing area indexes, **reproducing today's behaviour first** (`app/Contexts/<X>/` → area `<x>`) so the change is behaviour-preserving before it is behaviour-extending.
2. Add the previously unmapped declarations from the investigation's ownership map (design chain → `color_theme/`; RBAC → its area; CI merge-gate workflows → `merge_gate/`; `.claude/scripts/*` → `ai_platform/`).
3. **Replace** the hook's three regexes with declaration-derived routing in the same change — the regexes are never kept alongside the declarations, so no second model exists even transiently.
4. Resolve the two recorded ambiguities explicitly in declarations: migrations claimed by the context owning the table; shared libraries claimed by the chain they serve (`config-guard.sh` → the design chain's area).

**Implementation belongs to ENG-007**, RED→GREEN as normal, after ratification.

## Success criteria (commission self-check)

☑ One authoritative ownership model (per-area declarations) · ☑ ownership declared exactly once (conflicts and gaps are detectable, not silent) · ☑ tooling derives routing from the declaration · ☑ platform responsibilities explicitly defined and **unchanged** · ☑ no architectural ambiguity remains before ENG-007 · ☑ **WP-3 carries no unresolved platform-architecture decision** — the deferred question is answered, not deferred again.

## Requested of the Decision Authority

1. **Ratify Phase 1's answer:** the Platform Registry's scope does **not** extend to project-side artifacts *(if ratified, record as a ruling in the platform rulings register — the next free id is **R-42**; this paper does not self-issue it)*.
2. **Ratify the Phase 5 decision** (Option D) as ENG-007's architectural target.
3. Confirm that **machine-readable architectural ownership stays unopened** as a separate question.

---

**Traceability:** DA Platform Architecture Decision commission 2026-07-30 · investigation `engineering/verification/reports/2026-07-30-developer-guide-ownership-investigation.md` · evidence: `.claude/platform/registry.yaml` preamble + admission rule · AIP-14 · R-37 · ENG-006/ENG-007 (`BACKLOG.md`) · `.claude/scripts/dev-guide-reminder.sh` · `developer_guide/*/00_index.md`. Read-only; **no artifact modified by this commission.**
