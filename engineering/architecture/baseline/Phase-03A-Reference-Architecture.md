# AI Engineering Platform — Phase 3A: Reference Architecture

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced; never authoritative without human review) |
| **Status** | **FROZEN — Baseline v1.0 (ADR-AIP-01, 2026-07-08).** The LAST design artifact (R-12/R-13). Phase 3B realizes it; further architectural change only from implementation experience, as amendments (AIP-13) |
| **Owner** | Architecture Review Board |
| **Promotion** | Generated → ARB Review → ADR Approval → Authoritative → Frozen |
| **Date** | 2026-07-08 |
| **Depends on** | `Phase-02-Domain-Model.md` (contexts, aggregates, events) · `Phase-02.5-Certification-Plan.md` (CAP/AIP/FF) · `Phase-02.6-Ubiquitous-Language.md` (vocabulary — used verbatim) · `Phase-02.7-Platform-Decisions.md` (PD-01..20; conflicts resolve in its favour) |
| **Question answered** | **How does the domain model become software?** — Capability → Software Component → Runtime → Configuration → Persistence → Extension. **No code, no file contents** (Phase 3B). |

> **Provider-independence (R-10).** This reference architecture is expressed in provider-neutral terms. The concrete AI environment (currently Claude Code) appears only in §7 as the **Provider Binding** — the one replaceable layer. Everything in §§1–6 must survive replacing the provider.

---

## 1. Component model — capability → software component

Seven components. Every CAP maps to exactly one component; every component has exactly one owning bounded context (PGP-02).

| Software Component | Realizes | Owning context | One-line responsibility |
|---|---|---|---|
| **Session Manager** | CAP-01, CAP-02 | Session Continuity | Assemble the bootstrap from governed sources at session start; record and archive sessions append-only; detect staleness. |
| **Knowledge Manager** | CAP-03 | Knowledge Governance | Register platform outputs as `generated` with correct cards; curate lint-validated packages; surface promotion proposals. |
| **Workflow Engine** | CAP-05, CAP-06 | Implementation Guidance | Track the per-ticket plan (WBS/steps/DoD); derive progress; fire discipline tripwires; enforce step order. |
| **Verification Engine** | CAP-07, CAP-08, CAP-09 | Verification & Evidence | Execute registered checks; record immutable evidence-bearing verdicts; maintain traceability; observe constitutional guards → halt + escalate. |
| **Review Engine** | CAP-11 | Adversarial Review Support | Run review protocols (attempt-to-reject, findings-with-evidence); enforce producer ≠ reviewer; emit recommended verdicts (`generated`). |
| **Drafting Studio** | CAP-10, CAP-12 | Design & Decision Support **(Core)** | Guide ADR/IDD/capability-model drafting against the frozen upstream templates; coaching doctrines; convergence notes. |
| **Platform Registry** | CAP-13 | Design & Decision Support (registry) + Verification & Evidence (its FFs) | The authoritative component/capability map (this table, §1, as data); platform fitness functions FF-1..16; PD enforcement mapping. |

**Component rules:** a component may depend on the Platform Registry, the repository's governed artifacts, and the published language of upstream components — never on another component's internals, and never on provider vocabulary (FF-15). The Registry is loaded first; engines are invoked on demand.

## 2. Runtime model — when components execute

Provider-neutral **runtime moments** (the Provider Binding §7 maps each to a concrete mechanism):

| Runtime moment | Components activated | Behaviour |
|---|---|---|
| **Session start / resume (incl. post-compaction)** | Session Manager → Knowledge Manager | Bootstrap assembled from: stable facts, current state, active plan, today's log, task-relevant package. Repo wins over any prior conversational state (AIP-03). |
| **Before a state-changing action** | Workflow Engine (tripwires), Verification Engine (guard checks where registered) | Tier-1 blocks (step order, protected paths); Tier-2 reminders (discipline sequence). |
| **After a work product is produced** | Knowledge Manager (register as `generated`), Workflow Engine (WBS/DoD update), Verification Engine (traceability) | Facts recorded; nothing auto-promoted. |
| **On demand (human or AI invokes a capability)** | Review Engine, Drafting Studio, Verification Engine (gate runs) | The engines are explicitly invoked; they never run speculatively. |
| **Session end / stop** | Session Manager | State sync enforced (session may not end unsynced); Tier-2 sync report. |
| **Never** | — | No background daemons, no scheduled autonomous mutation, no auto-commit (PD-12). |

**Escalation path (all moments):** ambiguity, rule conflict, or a constitutional-guard trip → halt the affected work, surface to Human Authority, record the event. No retry path exists (PD-06/PD-19).

## 3. Configuration model

- **One composition root** — a single, versioned configuration entry point wires runtime moments to components (Phase 1 lesson: the funnel shape is good; the payloads must be owned). No component self-registers outside it.
- **Three scopes, one rule:** machine/user scope · project scope · local overrides. **Project scope is authoritative for governance**; user/local scopes may tighten but never weaken a Tier-1 gate (a platform fitness check verifies this).
- **The Platform Registry is configuration-as-data:** the capability/component map, registered checks, review protocols, and tripwire rules are declarative, versioned entries — adding one is a governed change (registry entry + owner + guard), not an ad-hoc script.
- **No secrets, no environment-dependent behaviour** in any governed configuration; deny-by-default for anything not declared.

## 4. Persistence model

| State | Where | Class | Rule |
|---|---|---|---|
| Governed artifacts (plans, drafts, models, guides, dictionary) | Repository (versioned markdown + cards) | Living/Frozen per card | Promotion chain only; supersede, never rewrite (AIP-11) |
| Session logs, context snapshots | Repository (versioned) | Living → Historical | Append-only; archived = immutable |
| Verdicts & evidence records | Repository (versioned, append-only) | Historical from birth | Constructible only with evidence ref (FF-3); re-runs append |
| Registry (capabilities, checks, protocols) | Repository (versioned, declarative) | Frozen-ish (governed change) | Every entry has owner + guard |
| Runtime scratch (transient run state) | Ignored workspace area | Non-authoritative | Never read as truth; reconstructible; the ONLY non-versioned state (AIP-03) |

**No databases. No hidden state. No external services.** The repository *is* the platform's persistence — which is what makes the platform auditable by the same means as the product (FF-9, FF-12).

## 5. Extension model

Closed by default; extension is a governed act, never a drop-in file:

1. Every extension = a **registry entry** (what, owner, guard) + the governed artifact itself, entering as `generated`.
2. Legal extension points are exactly those declared per capability in `Phase-02.5-Certification-Plan.md` §2 (new checks require a falsifiability RED run; new tripwires cite their ER; new review protocols come from the Customer — ARB; new knowledge types via schema ADR).
3. CAP-09 (constitutional observation) is **deliberately closed** — no extension point exists.
4. A new *capability* (vs. an extension of one) enters the capability lifecycle at `Proposed` and requires ARB approval (AIP-13).

## 6. Governance enforcement mapping (PD → component)

| Constitution rows | Enforcing component | Mechanism class |
|---|---|---|
| PD-01..04 (no authority over architecture/ADRs/certification/governance) | Drafting Studio + Knowledge Manager | Unreachable states in artifacts; promotion chain |
| PD-05, PD-08 (no authoritative docs; no asserted events) | Knowledge Manager | `generated`-by-default; FF-1/FF-14 |
| PD-06 (constitutional guards untouchable) | Verification Engine | Read-only observation; halt+escalate; FF on write-path absence |
| PD-07, PD-17 (honesty; derived progress) | Verification Engine + Workflow Engine | Evidence-required construction; FF-3/FF-5 |
| PD-09 (append-only history) | Session Manager + Verification Engine | Immutable archives; FF-12 |
| PD-10 (producer ≠ reviewer) | Review Engine | Construction-time rejection; FF-7 |
| PD-11, PD-12 (no merge/Done authority; no background autonomy) | Workflow Engine + composition root | Human gates in step model; no scheduled entry points exist |
| PD-13..20 (permitted actions & bounds) | All engines | The engines *are* the bounded permissions |

Phase 3B's Definition of Done includes: every PD row demonstrably guarded or a named compensating human control recorded (Phase-02.7 §4).

## 7. Provider Binding (the only replaceable layer)

The single place provider-specific knowledge may exist. For the current provider (Claude Code), the binding maps:

| Reference concept | Current binding |
|---|---|
| Composition root | The provider's project settings file |
| Runtime moments | The provider's lifecycle hook events |
| On-demand capability invocation | The provider's skill/command mechanism |
| AI actor sub-workers | The provider's subagent mechanism (non-autonomous pattern: restricted tools, human-approval posture) |
| Bootstrap injection | The provider's session-start hook |

Binding rules: the binding contains **zero** engineering judgment (it translates, never decides); provider vocabulary appears nowhere outside it (FF-15); replacing the provider = rewriting only this layer plus the §3 composition root. The existing `.claude/` assets (context injection, tripwires, reminders, plans/sessions convention) are the **embryonic form of this binding plus Session Manager and Workflow Engine** — Phase 3B evolves them; it does not start from zero.

## 8. Phase 3B acceptance criteria & validation plan (R-14)

**3B builds:** the composition root, the seven components as thin, testable, versioned assets, the Provider Binding, and the platform fitness functions — in the frozen vocabulary, each file owned by exactly one component (traceable via the Registry).

**Acceptance:** FF-1..16 executable and green with falsifiability runs · every PD row guarded or compensated · Phase 1 success criteria met (≤ ~100 governed files, zero external runtimes, onboarding from ≤3 documents) · the platform's own D-11-style certification gate (Phase-02.5 §5) passed by ARB.

**Validation (the real test, per R-14):** the platform is *not finished* at 3B. It is validated by running one real PublicDigit ticket through it end-to-end — the natural candidate is the next main-track ticket (PB-004 Election Reaction, or the ContestedOutcome ADR work preceding it): bootstrap → IDD drafting support → RED/GREEN slices with tripwires → gate verdicts → adversarial review pass → certification recommendation → session archival. Every friction, gap, or missing concept discovered becomes an **amendment proposal** (AIP-13), not a new architecture document. Implementation is now the primary source of architectural feedback.

---

*Traceability: realizes `Phase-02-Domain-Model.md` (six contexts → seven components; events → runtime moments) and `Phase-02.5-Certification-Plan.md` (CAP → components; FF → acceptance) under `Phase-02.7-Platform-Decisions.md` (PD → §6) in the vocabulary of `Phase-02.6-Ubiquitous-Language.md`, per ARB rulings R-8, R-10, R-12..R-14 (2026-07-08).*
