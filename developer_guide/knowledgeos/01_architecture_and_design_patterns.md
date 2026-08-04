# 01 — KnowledgeOS Architecture & Design Patterns

## Purpose

Explain how KnowledgeOS is built and *why it is built that way*, so that any
developer can extend it without violating the rules that made it work. This is
a developer guide, not a governance document — the canonical rules live where
the Traceability section points; this explains them with code.

## What KnowledgeOS is

An evidence-driven engineering platform inside the PublicDigit repository. It
observes engineering (metrics, test presence, cohesion), issues advisory
recommendations, captures developer decisions, records outcomes, and assesses
deterministically whether accepted recommendations actually helped.

**The architectural style: Hexagonal Architecture with DDD governance** —
capability-oriented, evidence-first, deliberately NOT microservices (one repo,
one process; no evidence justifies distributed boundaries).

## The structure: Capability → Port → Adapter

Everything follows one shape. The domain names a **capability**; the capability
is reachable through a conceptual **port**; technology sits outside as an
**adapter** and is always replaceable.

| Capability (UL) | Port (conceptual) | Adapter(s) today | Key files |
|---|---|---|---|
| **ObservationTrigger** *(UL correction 2026-08-04: the capability sits one level above any single trigger)* | Trigger — every adapter produces a `ChangeSet`; the runtime never knows how it was triggered | commit (husky `.husky/post-commit` → canonical script) · file-save (`watch.php` poller + VS Code task) · PR/CI (staged) | `ChangeSet.php` · `ObservationRuntime.php` · `FileSaveTrigger.php` · `scripts/observations/git-hooks/post-commit` |
| **Observation** | Collector | LCOM4 · test-presence · metrics | `Lcom4Collector.php` · `test-presence-observer.php` · `metrics-report.php` |
| **Recommendation** | Rules | rules-as-data (YAML) + pure engine | `RecommendationEngine.php` · `recommendation-rules.yaml` |
| **Decision Capture** | Decision | two CLIs, one record format | `recommendation-decide.php` · `recommendation-inbox.php` |
| **Outcome / Assessment** | Evidence | JSONL streams + deterministic policy | `OutcomeRecorder.php` · `AssessmentService.php` |
| **Evidence projections** | Dashboard | regenerable markdown | `EvidenceDashboardRenderer.php` · `dashboard-renderer.php` |
| **Platform Bootstrap** | — | `init` + `doctor` (Track A) | `KnowledgeOsInitPlanner.php` · `KnowledgeOsDoctor.php` |
| **Evidence Analytics** *(gated)* | Analytics | none yet — Python/DuckDB are candidates | Deferred Architecture Register |
| **Adaptive Recommendation** *(gated)* | Learning | none yet | Deferred Architecture Register |

**Two rules keep this honest:**
1. **UL is technology-free.** It is *Evidence Analytics*, never "Python
   Analytics"; `enable commit-trigger`, never "enable husky".
2. **One implementation needs no port.** Port *interfaces* are written only
   when a second adapter or consumer actually exists (BootstrapPort and
   DecisionCaptureService are staged on exactly this trigger). Until then the
   invariant lives elsewhere — e.g. both decision CLIs share one record format
   byte-for-byte; the format IS the contract.

*(Candidate vocabulary, n=1, unadopted: **Provider** — a middle term between
capability and adapter, e.g. CommitTrigger → Git → Husky. Use it in
explanation if it adds precision; discard it if it never does.)*

## The ObservationTrigger Port — the canonical contract

Not a class, not an interface — a documented contract every trigger adapter
implements. *(Legitimately documented as of 2026-08-04: TWO adapters exist —
commit and file-save — so the port is proven duplication, not anticipation.)*

```
ObservationTrigger

Input:      ChangeSet   (technology-neutral: changed files · source · timestamp · optional commit id)
Output:     ObservationRuntime.run(ChangeSet)

Guarantees: deterministic            — identical ChangeSet + identical sources → identical recommendations
            non-blocking             — never blocks a commit, a save, or typing
            trigger-independent      — the runtime never knows how it was triggered
            no collector logic       — adapters detect and delegate, nothing else
            no recommendation logic  — thresholds live in rules-as-data only

Publication: the ADAPTER's decision, not the runtime's —
            commit publishes to the evidence streams · file-save displays ephemerally
```

Adapters today: commit (husky) · file-save (`watch.php` poller + VS Code task).
Staged: PR/CI · real-time (FS events / IDE extension) — each drops in against
this contract without touching anything downstream.

## The deterministic pipeline

```
Engineering Event (commit · manual run)
      ▼
Observation        — an instrument saw something; verdict-free
      ▼
Recommendation     — deterministic rule fired; ADVISORY, developer decides
      ▼
Decision           — ACCEPTED · IGNORED · DEFERRED (+ reason code, rationale)
                     IGNORED ends the lifecycle here — closure, not a gap
      ▼
Outcome            — metric delta after ≥3 commits; raw, assessment-free
      ▼
Assessment         — SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE
      ▼
(Learning — FUTURE, gated: consumes assessments, never raw metric deltas)
```

Every stage transition is a timestamped JSONL record under
`engineering/verification/observations/`. Projections read; nothing writes back.

## The design patterns in use (with their code anchors)

1. **Pure core + thin runner.** Every tool is a pure, fully-testable class
   (`classify()`, `diagnose()`, `plan()`, `evaluate()`, `render()`) plus a thin
   runner script that gathers real-world state and prints/writes. Test the
   core; the runner stays near-trivial. See `RecommendationInbox.php` vs
   `recommendation-inbox.php`.
2. **Projection / read model.** DASHBOARD.md, the health dashboard, the
   Deferred Architecture Register are all *derived, non-authoritative,
   regenerable*; on conflict the cited source wins. Never hand-edit a
   projection.
3. **Rules-as-data.** Thresholds live in `recommendation-rules.yaml` and
   `metrics-config.yaml`, not in code. Rules change through the assessment
   loop — never because an example somewhere used a different number.
4. **Append-only evidence.** JSONL streams and the OE register are never
   edited — annotate or append. History is not rewritten.
5. **Idempotent planner.** `init` plans the gap between current and desired
   state and executes only what's missing; an initialized repo plans zero
   actions. Pinned by test.
6. **Honest empty cells / withheld rates.** The dashboard renders NO DATA YET
   with the source that will fill it; Evidence Velocity refuses to extrapolate
   sub-week windows. Measurements, never conclusions; n is always shown.
7. **Advisory, never blocking.** No collector or hook may block a commit or
   contain policy verdicts (WARN/BLOCK belongs to configured governance).
   Enforcement would be an advisory→enforcing promotion — evidence-gated.

## The governance model (why you can't just add things)

- **Freeze v1:** discovery reopens only when the existing model cannot explain
  engineering evidence.
- **Freeze v2:** new architecture originates only from engineering events
  (event → observation → candidate → evidence → promotion).
- **The ARB standing question:** *"Which engineering event produced the
  evidence requiring this change?"* No event → the idea goes to `docs/ideas/`.
- **The Deferred Architecture Register**
  (`docs/knowledgeos/KnowledgeOS_Deferred_Architecture_Register.md`): every
  deferred concept with its current invariant, activation criterion, and
  status. *Its purpose is to delay architecture until engineering evidence
  justifies it — it is not itself a source of architecture.*
- **Three-question staging:** Can we build it? (deterministic) · Should we
  build it now? (its object exists — deterministic ≠ demanded) · Should it
  join the platform? (cross-repo — a separate, later decision).
- **UL exemption:** vocabulary may be refined on engineering evidence at any
  time; renaming is not discovery.

## How to extend KnowledgeOS (the checklist)

1. **Check the register first.** Is there a row? Has its criterion fired?
   No row + no engineering event → write it into `docs/ideas/`, stop.
2. **TDD, RED first.** Pure core class, failing test shown, then GREEN.
3. **Respect the boundaries:** collectors calculate and never judge ·
   projections read and never write back · decisions belong to developers ·
   evidence is append-only.
4. **Write the developer guide** in the matching area
   (`developer_guide/engineering_observations/` for tooling), update the index.
5. **Commit with explicit paths** (never `git add -A` — a parallel session's
   files were once swept; the rule exists because of a real incident).
   The post-commit hook will log its observation; it rides in the next commit.

## Pitfalls

- Don't "improve" the register, freezes, or governance documents — the
  governance-artifact set is declared sufficient; new governance must be
  demanded by operations.
- Don't add a port/interface for a single implementation.
- Don't render a rate, score, or KPI whose population is n≈1 — stage it and
  name what fills it.
- READY (doctor) means the pipeline CAN run; the dashboard shows whether it
  HAS run. Different questions.

## Traceability

Canonical sources: `docs/knowledgeos/KnowledgeOS_Deferred_Architecture_Register.md` ·
`docs/knowledgeos/KnowledgeOS_Engineering_Improvement_Cycle.md` ·
`docs/knowledgeos/KnowledgeOS_Operational_Evidence_Register.md` ·
`.claude/CONTEXT.md` (freezes v1/v2, era declarations, ARB records) ·
tooling guides 01–10 in `developer_guide/engineering_observations/`.
Commissioned 2026-08-04 with the review that scored the methodology coherent;
the architecture classification is recorded at description strength (an ADR
awaits an operational demand, per the sufficiency declaration).
