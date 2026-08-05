# Static Engineering Metrics — Engineering Spike Plan

| | |
|---|---|
| **Type** | ⭐ **ENGINEERING SPIKE (PublicDigit delivery tooling).** ⛔ ***No KnowledgeOS architecture changes. Not a capability. Not a commission. Not governance.*** |
| **Status** | ✅ **APPROVED — user directive 2026-08-03: *"the Engineering Metrics Prototype should begin now as part of PublicDigit engineering."* Execution started same day** |
| **REV 2** *(user message, same day)* | scope + **Instability** *(afferent/efferent)* · ⭐ **added the crucial evidence element: RECORD WHETHER WARNINGS ACTUALLY LEAD TO BETTER ENGINEERING DECISIONS** — *until developers are observed using warnings, they are measurements, not validated governance. KnowledgeOS learns from this spike only AFTER that operational evidence exists* |
| **Origin** | the CBO idea, returned to where it belongs: an engineering problem *(ARB review 2026-08-03: "Approved. Freeze. The next chapter is engineering instrumentation")* |
| **Boundary** | KnowledgeOS OBSERVES this spike *(its execution may become an OE entry — a genuinely non-WP-4B event)*; it never owns it |

## Objective

Collect architecture metrics during PublicDigit development and give developers immediate feedback: **warnings + trend history + evidence.**

## Background — check-before findings (done before this plan)

| Already exists | Consequence |
|---|---|
| ⭐ **PHPStan 2.1 + Deptrac, wired into a composer MERGE GATE** *("Architecture fitness / Deptrac / greenfield PHPStan / widened regression")* | ⛔ **the spike EXTENDS the existing gate; it must not build a parallel pipeline.** Deptrac already covers layer violations and dependency direction |
| Architecture fitness tests | boundary rules have a home; metrics complement, never duplicate |
| ⛔ **No numeric OO metrics** *(CBO · RFC · LCOM · WMC · fan-in/out · package cycles as numbers)* and **no trend history anywhere** | the genuinely missing pieces — and trend history is the one with no near-neighbor in the repo |

## Scope

**IN:** CBO · RFC · LCOM · WMC · package cycles · fan-in · fan-out, computed per class/package over `app/` — via an **existing tool evaluated first** (candidates: `pdepend/pdepend`, `phpmetrics/phpmetrics`), never a hand-built engine · a thin runner producing (a) console warnings on threshold crossings, (b) an append-only trend file (per-commit or per-run snapshot), (c) a machine-readable report artifact.
**OUT:** ⛔ assessment/recommendation logic *(that is the un-fired Engineering Assessment Commission's ground)* · KnowledgeOS registers, capabilities, ontology entries · CI redesign · fixing the findings themselves.

## Design decision candidates *(decided at approval, not now)*

1. **Tool:** pdepend (battle-tested metrics, XML output) vs phpmetrics (richer report, includes trend-ish HTML). Evaluation criterion: which yields CBO/LCOM/WMC per class with least glue.
2. **Thresholds:** start ADVISORY (warnings only, no gate failure) — thresholds earn gate status through observed usefulness, mirroring the platform's advisory≠enforcing lesson at the tooling level.
3. **Trend storage:** one append-only JSON/CSV under `storage/metrics/` or `engineering/verification/metrics/` — placement derived at implementation time via the placement decision, not chosen here.

## Task checklist

- [x] Evaluate pdepend vs phpmetrics against `app/` — **pdepend 2.16 chosen; criterion met on first candidate (CBO/WMC/Ca/Ce per class, XML, zero glue beyond one parser script), so the phpmetrics timebox was not spent**
- [x] Wire chosen tool into composer scripts beside the merge gate (advisory) — **`composer metrics:collect`, ADVISORY tier, described; merge-gate untouched (ARB R3 stable interface)**
- [x] Emit warnings for top-N worst CBO offenders — `scripts/metrics/metrics-report.php` *(LCOM not in pdepend's class attrs — recorded as a limitation, not glued on)*
- [x] Append per-run snapshot to the trend file — first snapshot at commit `c3409d69f` in `engineering/verification/metrics/trend.jsonl`
- [x] **Characterization tests (added 2026-08-03, freeze-compatible):** `tests/Unit/MetricsReportTest.php` — 4 tests / 24 assertions pinning frozen behaviour *(stereotype banding · v3 snapshot schema · append-only deltas · exit codes)*. Includes one bug-fix-class seam: `METRICS_TREND_DIR` env override **so tests can never pollute the real evidence file** *(verified: real trend.jsonl untouched at 3 snapshots)*
- [ ] ⏳ **Backlogged per review ("not immediately"):** a `composer test:metrics` alias so the characterization tests run by one command — *do at the next natural composer.json touch, never as its own churn commit*
- [ ] Run twice across real commits; confirm the trend file shows deltas — **awaits the next real commit**
- [ ] Record whether warnings actually lead to better engineering decisions *(REV 2's crucial element — awaits real development use)*

## Progress — Run 1 results (2026-08-03)

**1,299 classes · mean CBO 3.98 · runtime ~7.5 min (hence advisory/on-demand, never a gate).**

| Signal | Reading (observation only — no assessment, per scope) |
|---|---|
| `MembershipServiceProvider` CBO=105, `AppServiceProvider` CBO=69, both I=1.00 | ⚠ **known metric caveat: service providers are pure wiring (all-efferent) — high CBO is their job.** A future exclusion list is the obvious first refinement |
| ⭐ `App\Models\Election` **CBO=57 · I=0.33 · WMC=257** | the afferent-heavy one — much depends ON it, and it is complex. *The kind of line the spike exists to surface* |
| `VoteController` WMC=474 · `DemoVoteController` **WMC=509** | the two largest complexity concentrations in the codebase |

**Honest limitations recorded:** LCOM/RFC not in pdepend's class summary attrs (available metrics used: cbo·ca·ce·wmc·dit; instability computed) · providers dominate top-N until excluded · single snapshot = no trend yet.

## REV 3 — review dispositions (2026-08-03, spike rated 9.7/10)

**Implemented same day (runner v2):**
- **A · risk score** — `0.4·CBO + 0.4·WMC (normalized within stereotype) + 0.2·instability`; top-N by risk, not raw CBO
- **B · stereotype baselines** — domain / model / controller / other / provider, compared only within their group; providers listed last with the caveat inline. ⭐ *Immediate payoff: `Committee` (domain, risk 0.96) surfaced — drowned in the raw-CBO list*
- **C · per-class trend** — snapshot v2 carries a 40-class watchlist (CBO≥15 or WMC≥100)
- **D · commit deltas** — next run prints `Δ class CBO 57 → 63` against the previous snapshot

**Honored:** ⛔ **no AI recommendations** — observations and trends only; human judgment decides (the runner's scope guard cites this).

**The Python question — disposition recorded, not executed:**
| | |
|---|---|
| Rewrite the spike in Python? | ⛔ **NO — this PHP implementation is the REFERENCE IMPLEMENTATION.** *Rewriting creates work, not evidence; the open question ("are these metrics useful to engineers?") is answered by usage, not by language* |
| The recorded migration path | Phase 1 today (done) → Phase 2 Python owns reporting → Phase 3 PHP emits observation JSON only → Phase 4 other languages plug in. ⛔ **Each phase gated on the same condition: the service boundary validated by REAL USAGE — none executes now** |
| Language-neutral Observation API + Python Engineering Services | ⏳ staged as a QUESTION with its gate *(trim rule)* — designed only when Phase 2's evidence gate opens |

**Staged (trim rule — question + constraint only):** **dynamic/git observations** *(change frequency · hotspots · co-change · review duration · rollback rate)* — the review's biggest missing piece and the recorded next evolution; constraint: no instrumentation exists (known); its need-recurrence feeds the Engineering Assessment Commission trigger.

## REV 4 — second engineering review (2026-08-03, 8.5–9/10); runner v3

**Implemented same day:**
- **1 · Threshold profiles** — per-stereotype CBO bands (OK/WATCH/WARN) in `metrics-config.yaml`; codebase shape now visible in one line: **1198 OK / 84 WATCH / 17 WARN**. *Bands label numbers; humans judge them — still observation, never assessment*
- **2 · Configuration** — weights + bands + watchlist floors + hotspot policy all in `scripts/metrics/metrics-config.yaml` (symfony/yaml, already a dependency)
- **4 · Hotspots** — ⭐⭐ **the first DYNAMIC observation, and the spike's best result: watchlist ∩ git change frequency.** Top findings: `ElectionManagementController` **37 changes × CBO 49** · `App\Models\Election` **30 changes × CBO 57 × WMC 257** · `VoteController` **24 × CBO 49 × WMC 474** — high coupling AND high churn, the classic architectural-hotspot signature. 19 hotspots total
- Delta engine verified against the previous snapshot (same commit → "watchlist unchanged", correct)

**Partially covered / staged:**
- **3 · Git-range analytics** *(last-30-commits %, branch/release comparisons)* — commit-to-commit deltas accumulate naturally as `metrics:collect` runs per commit; range analytics STAGED (question + gate: enough snapshots to make ranges meaningful)
- **5 · Multi-language observation JSON** — staged with the Phase-2+ migration path (REV 3); same real-usage gate

**Naming guard:** *"Engineering Observation Platform"* — the reviewer's reading of what the spike is becoming — recorded as a **candidate name only** (same rule as "Engineering Intelligence": names freeze meaning; meaning follows evidence; governance adopts names).

## REV 6 — LCOM4 collector built (2026-08-04, user-directed re-sequencing)

**Gate amendment recorded transparently:** LCOM4 was staged behind the usage-phase "yes" and noted Python-side-if-chosen. **The user-endorsed directive re-sequenced it with a better purpose: the second collector IS the extraction trigger's evaluation instrument** — built in PHP as an independent collector precisely to measure architecture fit. *(The metrics-tool freeze is untouched — this is a separate artifact, not tool growth.)*

**Built TDD-first (RED shown → GREEN):** `scripts/observations/Lcom4Collector.php` *(pure; Hitz & Montazeri connected components; constructors excluded; nikic/php-parser REUSED, no parser written)* + `lcom4-observer.php` runner · **8 tests / 24 assertions** incl. verdict-free pinned by test. Check-before note: pdepend's 8 "lcom" grep hits were `We**lcom**eDashboardController` — genuinely absent, so branch 3 (independent implementation) applied.

**⭐⭐ First real observations:** `App\Models\Election` **LCOM4=29** — *now flagged FOUR independent ways: CBO 57 · WMC 257 · 30 changes · 29 disjoint responsibility clusters.* `Committee` LCOM4=16 *(matching its risk-0.96 stereotype ranking)*. **Two collectors independently converging on the same suspects = cross-collector corroboration — the platform's own convergence epistemology, now operating on code.**

**⭐ The architectural experiment's findings (the directive's real question):**
| Reused cleanly | Duplicated (the signal) |
|---|---|
| observation shape *(verdict-free dict)* · OBS_DIR seam · TDD/fixture pattern · php-parser dependency | ⚠️ **runner boilerplate now exists in THREE copies** *(arg parsing · OBS_DIR resolution · JSONL append · console header · commit stamping, ~30 lines each)* **and the three snapshot schemas quietly diverge** — the OBS-1 abstraction pressure, visible in practice |

⛔ **Extraction evaluation verdict: complexity is accumulating but TOLERABLE at n=3 — evaluation fed, not fired.** The functional trigger's first real data point is recorded; a fourth collector or schema pain revisits it.

**EV-1 count, kept honest (facet-lesson applied):** lcom4 is a second collector *within the same rediscovery category* (collector fixture tests) — **EV-1 stays n=2 categories, strengthened, not n=3.**

## REV 7 — Collector Verification Suite (2026-08-04); ⛔ collectors PAUSE, usage begins

- ⭐ **Built per review ("validate the metric before validating the code"):** `scripts/observations/examples/lcom4/` — **seven language-neutral golden fixtures + `expected.json`** *(the conformance CONTRACT any future Python/Java/Rust implementation must satisfy identically)* + `Lcom4VerificationSuiteTest` — **GREEN, 23 assertions**, including a completeness check *(no fixture may exist without an expectation)*.
- ⭐ **Variant decisions PINNED in expected.json, not folklore:** constructors excluded · **statics = isolated nodes (inflates LCOM4 — pinned, revisitable)** · trait methods unresolved · inherited methods excluded. ⚠️ **Consequence for real values: Eloquent models carry statics/magic — `Election=29` is an observation WITH these caveats attached, never a verdict.** *Metric validated on canonical shapes; interpretation of real classes stays human.*
- ⛔ **Collector-building PAUSES here (review directive): three observation streams exist (metrics · test-presence · LCOM4); the next evidence comes from USING them on PublicDigit development, not from a fourth collector.**

## REV 5 — third engineering review (2026-08-03, 9.5/10): ⛔ FEATURE FREEZE — the USAGE PHASE begins

> # **The tool stops growing. The evidence phase starts: 20–50 real commits · PRs · refactorings · features · bug fixes — then the question: *did these observations actually help developers make better decisions?***

**A directive tension, resolved by principle and recorded:** the review asks for an *immediate* restructure (collectors/analyzers/reporters) AND says *stop adding, use it*. **The stop-directive wins by the platform's own rules** — structure follows need; a working single-file tool entering a feature freeze does not get nine speculative classes, especially with Phase 2 (Python extraction) already staged to absorb reporting later. ⏳ ~~The restructure is recorded with its TRIGGER: the moment a second metric collector is actually added, the split happens first.~~
> ⭐ **TRIGGER AMENDED (user-confirmed 2026-08-03; generalized, then made FUNCTIONAL same day): extraction is EVALUATED when the current implementation can no longer accommodate new observation sources without accumulating accidental complexity. Multiple heterogeneous sources (LCOM4 · Java · git analytics · ADR analysis · AI-workflow) are a COMMON INDICATOR — strong evidence, never the rule itself.** *The trigger is emergent complexity / demonstrated need, not `count == 2` — a 50-line second collector may justify nothing; one source that strains the single-file design may justify everything.* *If Python is the destination, making PHP beautifully extensible is unnecessary engineering.* **The PHP tool's final form is fixed: collector · runner · output JSON — DONE. It never becomes the platform; it becomes the platform's PHP ADAPTER (collector #1, reference implementation). Growth budget: zero, permanently — bug fixes and evidence only.**

**Staged for future iterations (question + gate each; nothing built):**
| Direction | Gate |
|---|---|
| Metric-provider separation *(PDepend · Git · PHPStan · Deptrac · custom DDD)* | the second collector *(= the restructure trigger)* |
| ⭐ **LCOM4** *(lack of cohesion via connected components — the strongest single "does this class have more than one responsibility?" observation; complements CBO: coupling looks OUTWARD, cohesion looks INWARD)* | ⛔ **pdepend's class summary does not carry it (limitation already recorded in Run 1)** — sourcing it means a second observation source, **so LCOM4 is ONE possible instance of the generalized trigger *(REV 5 as amended: any second independent source fires extraction EVALUATION; no PHP restructure)* — if chosen, it lands Python-side as a native collector** · usage-phase "yes" first, like everything else |
| ⭐ **DDD metrics** *(aggregate size · entities/aggregate · cross-context dependencies · infrastructure leakage · VO ratio)* | usage-phase evidence that stereotype/CBO signals are consumed at all |
| Architectural drift *(cross-context calls over time)* | snapshot accumulation |
| ⭐ **ADR-compliance observations** — the review's own observation-grade phrasing preserved verbatim: *"Observation: ADR-17 may deserve review"* — never *"architecture violated"* | needs ADR→checkable-rule mapping; genuinely powerful, genuinely later |
| KnowledgeOS integration | ⛔ **standing boundary restated: KnowledgeOS stays CONSUMER, never producer** — observation JSON → KnowledgeOS observes → OE → maybe assessment. Unchanged |

**Usage-phase success criterion (= the spike's remaining checklist, unchanged):** real-commit deltas accumulate · warnings observed influencing (or failing to influence) actual decisions. **The spike's completion record — honest either way — becomes the OE-KOS-3 candidate.**

## Usage-phase observation log *(REV 5 approved; append-only — observations about THE TOOL, not features)*

*Record one line per event, as it happens. These five kinds are the watch categories (review 2026-08-03):*
`IGNORED` warning seen, not acted on *(threshold wrong?)* · `ACTED` warning led to refactoring *(value!)* · `FALSE+` flagged wrongly *(rule needs work)* · `MUTED` developer disabled or bypassed a warning *(noise?)* · `PERSISTENT` same hotspot across many runs *(debt is durable)*

| Date | Kind | What happened |
|---|---|---|
| *(log starts with real usage)* | | |

**Final refinements (closing review, 2026-08-03 — approved; chapter closed):**
- ⭐ **Success criterion SHARPENED — the milestone is behavioral, not numeric:** ⛔ ~~"20–50 commits"~~ → **"the FIRST developer changes code BECAUSE of a warning"** *("I split this class because the hotspot kept growing")*. Commit counts are the window; the behavioral event is the evidence.
- ⛔ **Category freeze:** the five kinds are FINAL for the usage phase. *SECURITY / PERFORMANCE / DUPLICATION / COVERAGE etc. are all interesting and none are needed — the current scope suffices to answer the one question: can static engineering observations influence developer behavior?* Expansion earns itself only on a "yes."
- ⏳ **Staged for phase end: a one-table retrospective** — counts per observation kind (ACTED / IGNORED / FALSE+ / MUTED / PERSISTENT). *That table will say more than another metric.*
- **The closing three-layer picture, recorded:** PublicDigit produces engineering activity → the Observation Tool measures and reports → KnowledgeOS governs how observations become trusted knowledge. **Each layer observes the one below; none reaches down.**

## Risks

Metric noise on a 1,532-file codebase (mitigate: top-N reporting, not exhaustive) · threshold bikeshedding (mitigate: advisory-only start) · ⛔ scope creep toward assessment logic (mitigate: the OUT list is binding).

## Open questions

Which composer script name? *(convention: beside the existing gate)* · per-commit vs per-run snapshots? · does the runner run in CI or locally first? *(recommend: locally first — evidence before automation)*.

## Next actions

**None until EP-01 approval of this plan.** On approval: the checklist top-to-bottom; the spike's completion is a candidate OE entry *(a real, non-WP-4B engineering event — exactly what Run 2's comparables ask for)*.

---

*Traceability: ARB review 2026-08-03 ("open a completely separate engineering spike... No KnowledgeOS architecture changes") · check-before: PHPStan+Deptrac merge gate exists and is extended, not duplicated · assessment logic explicitly OUT (Engineering Assessment Commission's un-fired trigger) · plan per ES-004.2.*
