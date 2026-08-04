# KnowledgeOS — Deferred Architecture Register

| | |
|---|---|
| **Kind** | ⭐ **PROJECTION — a governance VIEW** *(consolidates activation criteria already recorded elsewhere; derived, non-authoritative, regenerable; on conflict the cited source wins)* |
| **Authority** | Generated — never authoritative without human review |
| **The pattern this view makes explicit** | **"A deferred concept has an explicit engineering activation criterion."** *(practice made explicit — every row below already operated this way; nothing is minted here)* |
| **Update rule** | rows change only when a criterion FIRES (an engineering event) or an authority re-rules a criterion — never by re-reasoning |

> ⛔ **Standing rule (review 2026-08-04):** no new services, bounded contexts, policies, buses, or layers **unless a documented activation criterion below is actually met.** A firing criterion is an engineering event — the Freeze v2 path. A proposal with no row here and no engineering event goes to `docs/ideas/`.

## The register

| Deferred concept | Current invariant (what protects the need TODAY) | Activation criterion (the engineering event) | Evidence required | Status |
|---|---|---|---|---|
| **DecisionCaptureService** | the shared decision+rationale record format, byte-identical across both CLIs | a SECOND decision-recording surface arrives (dashboard · IDE · GitHub Action) | real duplication across surfaces | **STAGED** |
| **Observation Runtime** | git post-commit hook + manual runners; OBS-1 contract | manual execution becomes a MEASURABLE source of friction/inconsistency *(gate verbatim)* | friction entries in usage/workflow logs | **PARKED** (friction gate) |
| **Event bus / async messaging / event store** | point-to-point JSONL streams | multiple producers AND consumers needing the same observations, making a bus SIMPLER than point-to-point | multi-repo/CI/IDE consumption | **REJECTED FOR NOW** (EDA verdict 2026-08-04) |
| **AssessmentPolicy** (as object) | thresholds as constants in `AssessmentService` (MIN_COMMITS=3 · ≥20% · ≥2%) | a SECOND metric family needing different thresholds | second family actually assessed | **STAGED** |
| **AssessmentEvidence** (as object) | the `basis` field carried on every verdict | MULTIPLE assessment types needing structured evidence | second assessment type exists | **STAGED** |
| **Pattern Register machinery** (candidate lifecycle: evidence → counterexamples → promotion → retirement) | staging table in the OE register (append-only) | staged-candidate volume reaches **~10–15** *(today: 4 — MO-1 · MO-2 · IF-1 · DG-1)* | the count itself | **STAGED** (volume watch) |
| **Evidence Analytics** *(UL name — Python/DuckDB/Postgres = implementation detail. Sub-capabilities: trend analysis · anomaly detection · forecasting · clustering. READS history, never changes what developers are recommended — answers "what happened?")* | deterministic PHP projections (dashboard · funnel · lead times · effectiveness) | **dataset-explicit gate:** recommendation history ✓ + decision history ✓ + timestamps ✓ *(assessments OPTIONAL — trends/forecasts/clusters don't need them)*, at a volume worth analyzing *(dozens of cycles — analytics on n≈1 analyzes noise)* | the required dataset, populated | **GATED** (earlier gate — split + UL-named per reviews 2026-08-04) |
| **Adaptive Recommendation** *(UL name — the learning layer. Sub-capabilities: confidence learning · threshold adaptation · context adaptation · developer-profile adaptation. CHANGES system behaviour — answers "what should happen next?")* | deterministic rules-as-data; *learning consumes assessments, never raw deltas* (frozen) | evidence thresholds *(CANDIDATE values)*: ≥100 completed cycles · ≥50 assessed outcomes · ≥20 accepted-with-improvement · multi-rule span | large ASSESSMENT history *(today: 1 cycle)* | **GATED** (later gate) |
| **Recommendation/Decision/Outcome/Assessment bounded context** | application-level workflow objects + the published Engineering Improvement Cycle process doc | ~6 months of operation giving the concepts lifecycle · invariants · versioning · policies · effectiveness · history · analytics | the watch criteria observed | **WATCHED** |
| **Bucket aging projection** | timestamps already recorded on every stream | operations make AGE meaningful (items old enough that age discriminates) | aging spread in real data | **STAGED** |
| **Flow-efficiency refinement** (distribution over means) | lead-times section (means with n shown) | populations outgrow n≈1 | volume | **STAGED** (refinement, not new) |
| **Platform extraction** (repo separation: collectors/tooling as product) | ADR Repository Separation Strategy (readiness levels; L1 substantially achieved); code-as-if-separated | accidental complexity in-repo · independent ownership+operation · second adopter | the ADR's exit criteria | **GATED** (ADR PROPOSED) |
| **EIS rendering** (the wall KPI) | record fields already make it computable later | computable population stops being n≈1 | accumulated interventions | **GATED** (fills from evidence) |
| **MO-2 promotion** (ownership-before-state heuristic) | staged candidate with the five-question shape quoted | recurrence across MULTIPLE bounded contexts | a second incident, different BC | **STAGED** (n=1) |
| **Engineering Assessment Commission** | backlog entry trimmed to Question/Trigger/Constraints/Collisions (Model: UNKNOWN) | its recorded trigger *(not fired)* | per backlog entry | **PARKED** |
| **FileSaved / IDE trigger** *(live feedback while coding — another `ObservationTrigger` instrumentation; collectors unchanged)* | **CommitTrigger, ACTIVE since 2026-08-04** (`.husky/post-commit` → canonical script; fires on every commit) | empirical evidence that commit-time feedback is TOO LATE: the same recommendation repeatedly triggered-then-fixed-in-the-next-commit · developers saying "I wish I had seen this while coding" | usage/workflow-log entries showing the pattern | **STAGED** (review 2026-08-04: "an engineering question, not an architectural one") |

## The register's own deferred fields *(review 2026-08-04 — "not now, later"; the register obeys its own pattern)*

| Future field | Purpose | Activation criterion |
|---|---|---|
| **Activated on** | which engineering event fired the criterion | the FIRST row transition actually occurring |
| **Disposition** | Implemented / Withdrawn / Superseded | same — history begins when there is history |

*Rationale: in two years, "why did we build this?" should be answerable from one row, not from months of logs. The status vocabulary (REJECTED · PARKED · STAGED · WATCHED · GATED) is an emerging spectrum, deliberately NOT formalized — it stays descriptive until transitions give it meaning.*

## How to use this view

A review asking *"should we build X now?"* checks one row: **has the activation criterion fired?** Yes → the firing event is the evidence; proceed through readiness (EP-01). No → the current invariant continues to protect the need; the proposal waits. **Consistency replaces judgment-per-proposal.**

---

*Derived 2026-08-04 from: CONTEXT.md staging records · `docs/ideas/2026-08-observation-runtime.md` · the OE register annotations · the recommendation-engine and metrics spike plans · ADR Repository Separation Strategy · the EDA-proposal verdict. Regenerate when a criterion fires. This projection decides nothing.*
