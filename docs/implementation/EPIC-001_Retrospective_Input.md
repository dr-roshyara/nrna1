# EPIC-001 Retrospective — Input Pack (evidence, not rulings)

**Purpose:** input FOR the ARB-led retrospective. This document **collects and never rules** — every promotion, deletion, and scheduling decision belongs to the retrospective itself. Prepared 2026-07-10 at the ARB-amended order step 6 boundary; precedent: `PB-004_Retrospective.md`.
**Structure (ARB refinement):** Observed Evidence (facts only) → Candidate Promotions → Candidate Deletions → Scheduling inputs → Standing questions.

---

## 1. Observed Evidence (facts only — each with source)

| # | Observation | Evidence source |
|---|-------------|-----------------|
| O-1 | All 7 tickets closed; the constitutional correction loop runs end-to-end over the REAL delivery path (IT-1..IT-8) | Board sync `e51178f73` · PB-006 IDD · `CorrectionLoopIntegrationTest` 4✔/39 |
| O-2 | The merge gate exists as ONE stable command and passed fresh at qualification (Architecture 146✔/1 skip · Deptrac 0 violations fail-mode · PHPStan clean · regression 179✔/0F) | PB-007 IDD §6.1 |
| O-3 | A celebrated measurement (MSI 75% / Test Strength 96%) was **rejected** by evidence validation: 202 mutants flipped killed→escaped without concurrency (65 = migration-file mutants; rest = messaging/persistence infra); validated baseline = MSI 50% / MCC 77% / TS 65%, reproducible across two runs | PB-007 IDD §2e (F-7D-2) |
| O-4 | Escaped mutants concentrate exactly in the constitutional correction loop's messaging/persistence boundary (`ChallengeOutboxAdapter` 22 · mappers · hydrators · `InboxExecutionEngine`) | F-7D-2 identity comparison, IDD §2e |
| O-5 | Two engineering-platform defects were found BY the gates themselves (PHPUnit-9 coverage schema; Composer 300s process timeout) and repaired as classified platform repairs — neither introduced by the tools | PB-007 IDD §2c-i (7C) · §2e F-7D-1 |
| O-6 | Cross-session concurrency corrupted a gate run once (unique-slug collision on shared `nrna_test`) — an operational race, not a test defect | Session log 2026-07-10 (operational note) |
| O-7 | `--threads=max` silently degrades to 1 in Infection 0.29.10; thread count proved to be part of measurement semantics, not tuning | IDD §2e · quality-gate run records |
| O-8 | Implementation outran governance records: boards were 4 days / 5 ticket-closures stale before the one-time sync | Board sync diff `e51178f73` |
| O-9 | PB-004..007 replaced per-ticket WBS trackers with IDD slices + session-log gate records; estimates (103 WBS items) diverged from actual slice structure | EPIC-001 board derivation note |
| O-10 | Rulings are duplicated across CONTEXT / session logs / IDDs / observation inboxes (consolidation need) | ARB observation at PB-007 closure |
| O-11 | 6,109-test full-suite pass (Infection initial run) was the FIRST ever — it exposed legacy debt invisible to per-slice gates (F-7C-4/5) plus 57 risky greenfield Feature tests (framework-level, F-7C-6) | PB-007 IDD §2c-ii |
| O-12 | Every finding this epic followed `Finding → Classification → ARB ruling → (experiment) → Decision` with zero silent fixes | PB-007 IDD §2a rollout order + §2c/§2e records |

## 2. Candidate Promotions (for ARB ruling — stage labels per the six-state vocabulary)

| # | Candidate | Current stage | Evidence source |
|---|-----------|---------------|-----------------|
| P-1 | Principle: *"Quality gates are permitted to reveal latent defects. They are not responsible for introducing them."* | Candidate principle (recorded, not adopted) | PB-007 IDD §2b · demonstrated at 7A/7C/F-7D-1 |
| P-2 | **Evidence-Preconditions checklist** for long-running experiments (measured source unchanged · tests unchanged · artifacts reused · no concurrent activity) | **Single observation** — one successful use is not candidacy; ladder: observation → *repeated* observation → practice → candidate standard (ARB mentoring 2026-07-11) | F-7D-2 execution contract, IDD §2e |
| P-3 | *"The platform owns orchestration, not the third-party tool"* (two-step Infection invocation is the instance) | Candidate principle | IDD §2c-iii · ARB 7C acceptance |
| P-4 | **EP-01-Light form** (Objective · Classification · Risk · Files · Expected evidence · Not changing) for below-IDD-weight changes | Observed working practice (used twice, both approved) | `.claude/MEMORY.md` · F-7D-2 resume + threads repair |
| P-5 | **PASS AFTER CORRECTION** qualification-lifecycle (Finding F-… / Correction CR-… / Qualification OQ-… identifier separation) | Approved on the platform track; candidate for product-track adoption | Engineering-track OQ records (parallel session, ARB-approved 2026-07-10) |
| P-6 | Documentation-consolidation rule: IDD = implementation decisions · ADR = architectural decisions · retrospective = lessons · CONTEXT = current state only | ARB-directed retrospective item | ARB @ PB-007 closure · session log 2026-07-10 |
| P-7 | Measurement-reporting convention: a mutation/quality number is always reported **with its execution model** | Observed implementation evidence | F-7D-2 (IDD §2e) |

## 3. Candidate Deletions (deletion goal — removing ≥1 counts as success)

| # | Candidate | Rationale (evidence) |
|---|-----------|----------------------|
| D-1 | Per-ticket estimated-WBS mechanics (103-item style estimates + `(est.)` recomputation rule) | O-9: superseded by IDD-slice tracking for 4 consecutive tickets; estimates never matched reality |
| D-2 | Any Stop/PreToolUse hook that reality proved redundant during EPIC-001 (review `.claude/scripts/` usage against session evidence) | AIP-14 deletion goal; usage evidence in session logs — inventory at the retrospective |
| D-3 | The stale "F-1/F-2 open debt" tracking pattern (gate work tracked as debt items in parallel with tickets) | F-1/F-2 duplicated PB-007's scope on two boards (O-8) |

## 4. Scheduling inputs (follow-ups already recorded — sequencing is the retrospective's)

F-7C-1..6 cleanup (dead test class · 2 non-parsing scaffold files · legacy suite debt · 57 risky tests) · ENG-002 (Shared PHPStan alignment) · ENG-003 (`*Ref` naming sweep) · ENG-004 Mutation Ratchet 1 **with TEST_TOKEN per-thread DBs as enabling sub-item** · first real CI run (push-dependent, PB-007 §5 open item) · deployment doc (production readiness 🟡) · EPIC-006 sequencing (legacy migration).

## 5. Standing questions (process + AIP-14)

What was used? What wasn't? What hurt? What simplified development? What should disappear? **What surprised us?** (Candidates: F-7D-2 — the attractive number was the artifact; the cross-session race; `--threads=max` silently = 1.)

## 6. Next epic (status only — no design here)

```text
Next Epic:  EPIC-002 — Evidence
Status:     Ready for Discovery (after retrospective + formal EPIC-001 closure)
First step: Discovery + literature review (no code, no architecture in this pack)
```

---
*This pack collects; the retrospective rules. Every item traces to repository evidence.*
