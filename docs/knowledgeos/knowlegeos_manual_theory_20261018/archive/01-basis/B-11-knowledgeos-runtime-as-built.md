# B-11 — KnowledgeOS as built: the observation runtime (developer guide 01)

| Field | Value |
|---|---|
| Read (full) | `developer_guide/knowledgeos/01_architecture_and_design_patterns.md` (195 l.; first commit 2026-08-04) |
| Also checked (metadata only, contents not read) | that the 12 code files the guide names exist in `scripts/observations/`, and the line counts plus last-commit dates of the 7 JSONL evidence streams in `engineering/verification/observations/` |
| Standing | engineering background, first-hand about KnowledgeOS. **Not** corpus |
| Labels | [SRC] stated in the source · [CHK] checked in the repository · [I] interpretation · [?] open |

## 1. What the guide says KnowledgeOS is

- **[SRC]** *"An evidence-driven engineering platform inside the PublicDigit repository. It observes engineering (metrics, test presence, cohesion), issues advisory recommendations, captures developer decisions, records outcomes, and assesses deterministically whether accepted recommendations actually helped."*
- **[SRC] Style:** Hexagonal architecture with DDD governance. It is deliberately *not* microservices: *"no evidence justifies distributed boundaries."*
- **[SRC] v1.0 closure invariant (2026-08-04):** *"The Runtime is closed for feature-driven modification."* New experiences enter as adapters.

## 2. The pipeline that was built

```
Engineering Event → Observation (verdict-free) → Recommendation (advisory)
→ Decision (ACCEPTED · IGNORED · DEFERRED) → Outcome (metric delta after ≥3 commits)
→ Assessment (SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE)
→ (Learning — future, gated)
```

- **[SRC]** Every transition is an append-only JSONL record. *"Projections read; nothing writes back."*
- **[SRC]** The ObservationTrigger port takes a technology-neutral **ChangeSet**. Its guarantees: deterministic · non-blocking · trigger-independent · no collector logic in adapters · no recommendation logic in adapters.
- **[SRC]** Trigger adapters: commit (husky), file-save, and a Claude Code hook. A PresentationPort renders to terminal, Claude session and VS Code.
- **[SRC]** Gated, not built: Evidence Analytics and Adaptive Recommendation (Learning).

## 3. Design rules stated

- **[SRC]** UL is technology-free.
- **[SRC]** One implementation needs no port.
- **[SRC]** Pure core + thin runner.
- **[SRC]** Projections are derived and never hand-edited.
- **[SRC]** Rules-as-data.
- **[SRC]** Append-only evidence.
- **[SRC]** Idempotent planner.
- **[SRC]** Honest empty cells: *"NO DATA YET"*, n is always shown, no rate is rendered at n≈1.
- **[SRC]** Advisory, never blocking.
- **[SRC] Governance:**
  - Freeze v1/v2.
  - The ARB question: *"Which engineering event produced the evidence requiring this change?"*
  - The Deferred Architecture Register.
  - *"READY means the pipeline CAN run; the dashboard shows whether it HAS run."*

## 4. What the repository shows

- **[CHK]** All 12 named code files exist under `scripts/observations/`.
- **[CHK] Evidence streams, all last committed 2026-08-04:**

  | Stream | Lines |
  |---|---|
  | test-presence | 58 |
  | recommendations | 10 |
  | decisions | 4 |
  | watch-usage | 4 |
  | lcom4 | 2 |
  | outcomes | 1 |
  | assessments | 1 |

  This agrees with KT-7 (1203): *one completed cycle; idle since 08-05.*

## 5. Contribution to the basis

| # | Contribution | Kind |
|---|---|---|
| R-1 | **KOS-M5 was not only a vision. It was implemented** as the observation runtime, and this is the only *running* KnowledgeOS software found | [SRC]+[CHK] |
| R-2 | The runtime realizes the governance core in code: observation ≠ verdict; recommendation is advisory; the decision is human; assessment is deterministic; history is append-only; projections are non-authoritative; INCONCLUSIVE is a legitimate result | [I] |
| R-3 | Its evidence is **n = 1 completed cycle**. The value of the loop is not demonstrated | [CHK] |
| R-4 | **[?]** Its relation to the PKS tactical model (KT-8) is not stated. Assessment vocabularies differ: the runtime has 4 values including PARTIALLY_SUPPORTED, while `G-15 Verdict` has no partial value (OQ-28) | open |
