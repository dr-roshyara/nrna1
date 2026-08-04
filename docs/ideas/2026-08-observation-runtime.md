# 💡 Observation Runtime

**Status: VISION** *(see the folder README — nothing here is architecture or a decision)*

## The idea

The missing fourth concern. Today: collectors exist, observations flow, **nobody owns execution** — a developer runs collectors manually. The runtime would own *when/why/for-whom collectors execute*:

```
Engineering Event (commit · PR · nightly · release · manual)
        ▼
Observation Runtime — executes · schedules · parallelizes · recovers
        ▼
Collectors (plugins: TestPresence · LCOM4 · CBO · PHPStan · Deptrac …)
        ▼
Observation Store → Developer Feedback (console · PR comment · dashboard)
                  → KnowledgeOS (consumes later)
```

**Responsibility split (single reason to change each):** the runtime EXECUTES, never analyzes, never governs · collectors CALCULATE only · KnowledgeOS consumes and never says "run collector" *(the standing consumer-never-producer boundary, extended to execution)*. **Trigger profiles sketched:** fast collectors on commit · expensive on PR · whole-repo nightly · baseline on release · manual on demand.

**The strategic reading worth keeping** *(rephrased per review 2026-08-04 — vision, not architecture)*: ⚠️ **a POTENTIAL future stable execution center IF operational evidence justifies extraction** — collectors as plugins, different customers loading different observation sets on one runtime. ⛔ *Today's stable center is still PublicDigit (AIP-14); the runtime earns centrality only through multiple products, multiple plugged collectors, independent installs, and the second-adopter gate — like everything else.*

## Enrichment (2026-08-04): the EVENT-DRIVEN model + the sponsor's directive

⭐ **The user's directive on record: observations "should automatically happen."** The target model, kept as vision: **the EVENT is the engineering activity — never the metric** (`ClassCreated/Modified/Renamed/Deleted` → Observation Engine → independent observation plugins → observation records → Recommendation capability → developer decision → learning). Observations carry evidence payloads *(which method clusters share which fields)*; recommendations say **"consider reviewing"**, never "split". This slots into the existing **Observed** state (post-production, pre-decision).

**First automatic step ALREADY EXISTS (minimal, gate-respecting):** `scripts/observations/git-hooks/post-commit` runs the fast observation (test-presence) on every commit — advisory, one file to install/remove, no engine. *Slow collectors and the full event bus remain behind this idea's friction gate; the directive is recorded as sponsor pressure ON that gate, and the existing Claude hooks (Stop/PreToolUse) are prior art: event-triggered instrumentation is already this repo's idiom.*

## Second enrichment (2026-08-04): the TRIGGER concept — staged as the gate-opening design's FIRST question

⭐ **When this idea's gate opens, the first concept introduced is `ObservationTrigger` — a DOMAIN concept, not a tool** (Commit · PR · Merge · Nightly · Release · Manual are engineering events; the git hook / GitHub Action / Jenkins job are instrumentations of them). **The three-way responsibility split staged with it:**

| Concern | Owns | Example |
|---|---|---|
| **Observation Trigger** | WHEN | "commit happened" |
| **Observation Runtime** | HOW | run plugins · collect · publish |
| **Collector** | WHAT | LCOM4 · test-presence · CBO · fitness |

*Three single-reasons-to-change; the current post-commit hook = one CommitTrigger instrumentation, prior art in place. The reusable-platform reading (PublicDigit as first customer; ArchUnit/Roslyn/Ruff collectors emitting one contract) stands under the existing AIP-14 guard — vision until the second-adopter gate.*

⚠️ **Watch-for at gate-opening (review 2026-08-04): keep SCHEDULER distinct from TRIGGER.** *Commit is an external event ("what happened?"); Nightly is a scheduling policy ("when should we run?") — two reasons to change. Not needed now; the Trigger must never quietly absorb scheduling when the runtime evolves.* Also recorded: the trigger side now mirrors the canonical governance layering (Rule→Mechanism→Instrumentation ≙ Event→Trigger→Instrumentation) — **the architectural style is becoming internally consistent, which is itself evidence the layering is real.**

## Third enrichment (2026-08-04): ARB verdict on the full EDA proposal — *"approve the conceptual direction, reject the infrastructure timing"*

An external proposal (event bus · Kafka/RabbitMQ/Redis Streams · domain events `ClassChanged`/`MetricCalculated`/…) was reviewed. The verdict, recorded here because this idea is where the EDA vision lives:

- ✅ **The canonical behavioral event chain RATIFIED** *(it is the published Engineering Improvement Cycle, event-named)*: `EngineeringEvent → ObservationProduced → RecommendationIssued → DeveloperDecisionRecorded → OutcomeRecorded → AssessmentRecorded`. **Two corrections to the proposal:** these are **Engineering Observation Events belonging to KnowledgeOS** — NOT domain events (PublicDigit's domain is elections, not `ClassChanged`); and **Assessment was missing** — it belongs in the chain (Python learns from Assessment, never from Outcome — the frozen rule).
- ✅ **`ObservationProduced` named the central stable concept** — producer-agnostic (LCOM4 · CBO · ArchUnit · Roslyn · Ruff · human review · ADR check all emit *Observation*, never "metric"). ⭐ *Converges with the existing OBS-1 observation contract — the common contract is already implemented practice.*
- ✅ **Collector latency classes adopted into the trigger profiles:** fast (LCOM4 · test presence · formatting) → pre-commit/on-save · medium (architecture rules · fitness functions) → PR · slow (repo/trend/cross-project analysis) → nightly.
- ⛔ **REJECTED FOR NOW — no engineering event requires them:** event bus · async messaging · event store. One repository, one developer, one pipeline: no scalability problem exists. **EDA entry criterion recorded:** multiple producers/consumers (repos · CI systems · IDE plugin · CLI · dashboard) needing the same observations, making a bus *simpler* than point-to-point — the standing rule (*engineering event → observation → evidence → architectural evolution*) applied to infrastructure.
- ⭐ **The proposal accidentally CONFIRMED the earlier decomposition** — each box in Event→Trigger→Instrumentation→Observation→Recommendation→Decision→Outcome→Assessment has exactly one reason to change; the layering absorbed the proposal without conflict.
- ✅ **The "implement NOW" slice BUILT (TDD):** `scripts/observations/recommendation-inbox.php` — the developer's decision inbox (list open recommendations with age; `--decide` prompts a/i/d + reason code and appends the standard decision+rationale records). *Attacks the measured bottleneck directly: needs-decision = 9.*

## Why interesting

Answers the four questions the collectors deliberately don't: who runs it · when · how developers are notified · how observations are published. Once it exists, every future collector plugs into one pipeline instead of inventing its own runner — *the runner-boilerplate-×3 duplication already observed is this idea's first evidence.*

## Evidence that would promote it

> ⛔ **The gate, verbatim (review 2026-08-04): create an execution runtime only when MANUAL EXECUTION becomes a MEASURABLE source of friction or inconsistency.**

The measuring instruments already exist: the usage-phase observation log and the AI workflow log record manual-run friction (forgotten runs · inconsistent ranges · time cost). **Friction entries accumulate there or the runtime stays parked.**

## Not allowed today

⛔ No runtime · no scheduler · no git hooks · no daemon *(N-10's refused machinery class again)* · no plugin interface *(that's the collector-interface item, gated with OBS-1)*. "Observation Runtime" = candidate name only.

## Canon guards

KnowledgeOS stays consumer — it never triggers collection · advisory-first applies to any future trigger (a commit hook that *blocks* would be advisory→enforcing promotion, evidence-gated) · the four-concern shape *(Specification → Collectors → Runtime → Store)* is the emergent architecture, recorded here as vision, adopted nowhere.
