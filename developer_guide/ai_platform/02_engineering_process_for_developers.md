# 02 — Working Under the Engineering Process (EP-01/EP-02, Operating Instructions)

## Purpose

Since 2026-07-08, every non-trivial engineering task on this project — by a human developer or any AI assistant — runs under the Engineering Process execution rules. This guide explains what that means in practice: how a working session starts, when you must plan, who approves what, and how work ends. It documents committed rules; it invents nothing.

## Where it fits

- **Authoritative rule text (read that, not this, on conflict):** `docs/implementation/Implementation_Process_v1.1_Draft.md` § "Execution Rules — Engineering Process (EP)". Rules live once, there. `.claude/CLAUDE.md` only points to them.
- **Operating instructions for AI sessions:** `.claude/platform/OPERATING_INSTRUCTIONS.md` (AST-013) — role, slice discipline, observation classes, economies.
- **Rulings history:** `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (living) · R-1..R-29 historical in the sealed `engineering/architecture/proposals/Phase-02.5-Certification-Plan.md`.

## How a session starts

You do nothing special. The SessionStart hook (`.claude/scripts/inject-context.sh`, AST-002) injects `MEMORY.md`, `CONTEXT.md`, the active plan declared by CONTEXT's `Plan:` line, and today's session log. The repository always wins over anything remembered ("evidence beats memory").

## EP-01 — Planning Stage (the rule you will use every day)

For every **non-trivial** task:

1. Understand the request.
2. Analyze the relevant architecture (frozen artifacts win).
3. Produce a plan — for PB tickets this *is* the IDD → Architecture Review path; for smaller governed work, a written plan in `.claude/plans/`.
4. Wait for explicit human approval. **Approval applies to the plan, not merely to the task request** — "implement X" authorizes *planning* X only.
5. Implement only the approved plan.
6. If implementation invalidates the plan: **STOP**, explain, present a revised plan, wait for approval. Never silently change direction.

**When is a task non-trivial?** Architecture-touching · new feature · refactoring · DB schema · ADR/process change · API/contract · domain model · behavior-affecting bug fix → **plan required**. Typos/formatting/comments → usually not. Questions/explanations → never. (Full decision table in the v1.1 draft.)

## EP-03 — Engineering Readiness Review (the "think first" step)

Before planning any non-trivial task, answer the nine question domains (business · DDD · architecture · process · TDD · design · impact · verification · completion — full table in the v1.1 draft). **Derive from the repository first; ask the human only what you cannot determine confidently**, and say which is which in the plan ("Derived: … · Cannot determine: … — please decide"). Depth scales: a typo answers "trivial" in seconds; a PB ticket answers in full — the 17-section IDD *is* the complete form. The AI behaves like a senior engineer opening an engineering conversation, never like a code generator opening a file.

## EP-02 — Completion Review

Verification (gates, PASS/FAIL) answers *does it work?* Completion Review answers *did we implement the approved plan?* — a human comparison against the plan/IDD before anything is called done. For PB tickets this is the existing Implementation Review + step-14 checklist.

## Gates you will hit

- **PHPStan (greenfield gate):** `vendor/bin/phpstan analyse -c phpstan-greenfield.neon` — the official gate; don't invent ad-hoc paths.
- **Architecture suite:** `vendor/bin/phpunit --testsuite Architecture` — property-based fitness tests; zero regressions.
- **DB safety:** destructive artisan commands against the dev DB are blocked by a hook (AST-007); use `--env=testing`.
- (Planned: `run-gates.sh` — AST-010, slice C3 — will run the first two and capture evidence; until then run them directly and paste output as evidence.)

## Recording your work (observation classes — from AST-013)

- **A Observation** (ideas, surprises, reviewer comments) → session log / backlog. Never governance.
- **B Commitment** (next-slice scope, constraints) → the implementation plan.
- **C Change** (code, scripts, guides) → repository, with verification.
- **D Governance** (ADR, ruling, principle) → **only by explicit human decision.** Suggestions and praise are never governance.

At session end ask: *what is the smallest permanent record required?* Everything else stays in the session log. Also: a **developer guide step under `developer_guide/<area>/` is part of the Definition of Done** whenever your work changed how a developer works — including work on the engineering platform itself (this guide exists because that rule was audited and found unenforced for `.claude/` work — see Pitfalls).

## Platform work is gated harder than product work

Before any engineering-platform discussion or change, answer in 30 seconds: **"Which current PublicDigit feature is blocked, made unsafe, or made significantly harder by the current engineering process?"** No answer → no platform work; return to the product. (Governance freeze R-27/R-29; defect fixes and unblocking changes are the only exceptions.)

## Pitfalls

- Don't restate rules in CLAUDE.md or prompts — reference the process doc (rules live once).
- Don't treat plan-mode approval of a *task* as approval of your *plan* (EP-01 step 4).
- Don't let the Verification Engine interpret — it measures (PASS/FAIL + evidence); analysis belongs to review.
- Known enforcement gap (found 2026-07-08): `dev-guide-reminder.sh` watches only `app/` and `database/migrations/` — platform/docs work doesn't trigger the guide reminder. Until the hook is extended (defect recorded, fix requires an approved micro-slice), the guide obligation for platform work is enforced by discipline, not tooling.

## Traceability

EP-01/EP-02 (`Implementation_Process_v1.1_Draft.md`) · AST-013 operating instructions · AST-002/006/007 (hooks, registry `.claude/platform/registry.yaml`) · R-27/R-29/R-34 + ADR-AIP-LOG · ADR-AIP-01/02 · guide step for the 2026-07-08 process/instructions work (C1's step is guide 01).
