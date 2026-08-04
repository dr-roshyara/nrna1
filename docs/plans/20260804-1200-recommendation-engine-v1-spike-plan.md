# Recommendation Engine v1 — Engineering Spike Plan

| | |
|---|---|
| **Type** | ⭐ **ENGINEERING SPIKE (deterministic rule engine — the first recommendation layer).** ⛔ ***No AI · no learning logic · no blocking · no assessment model.*** |
| **Status** | ✅ **APPROVED (2026-08-04):** spike-level ownership default ENDORSED (*"exactly the right level"* — engineering observation tooling; capability-level stays the inform question) · conceptual discussion FROZEN (*"every remaining question is empirical"*) · maturity table: *ready to implement* · mission: *"Do not invent. Observe. Implement. Measure. Learn."* **Execution begun same day** |
| **Origin** | phase-boundary review 2026-08-04: *"the next major implementation… a deterministic rule engine that consumes observation contracts and produces recommendations"* |
| **The chicken-and-egg it resolves** | the dashboard's outcome cells (*recommendations accepted? ignored? prevented defects?*) can NEVER fill while no recommendations exist — **the loop needs a first recommendation source before outcome tracking can observe anything** |

## ⚠️ The gate interplay — declared, not dodged

**Check-before:** the **Engineering Assessment Commission** is staged (decision-ready · model deliberately UNKNOWN · charter: which observations are synthesized, which earn recommendations, which require the DA). A recommendation engine is its subject matter. **This spike's relationship to it, scoped explicitly:**

1. the spike **generates the commission's missing evidence** (real recommendations meeting real developer decisions) — it does not answer the charter questions;
2. **rules are DATA, not model** — five starter rules in YAML; no assessment taxonomy, no synthesis model, no threshold philosophy is designed;
3. the **three-level separation is enforced by construction**: collectors OBSERVE (untouched) · the engine RECOMMENDS (text + rationale, advisory) · **the developer DECIDES** — nothing is ever applied automatically;
4. if the spike's evidence fires the commission's trigger, the commission opens and inherits everything — *the spike is its instrumentation, not its replacement.*

## AMENDMENT (EP-01A, 2026-08-04) — the capability elevated above its implementation

> **The stable thing is the RECOMMENDATION capability; the engine is only today's implementation.** *Capabilities remain; implementations change (Rule Engine → ML → Hybrid → LLM, without the capability moving).*

### Domain Capability Ownership *(the ARB's required section)*

| | |
|---|---|
| **Capability** | Recommendation *(business-capability sense — see the term-collision note below)* |
| **Current implementation** | Deterministic Rule Recommendation Engine v1 *(this spike)* |
| **Inputs** | Observation Contracts |
| **Outputs** | Recommendation Records |
| **Consumers** | Developer · Dashboard · Learning *(future)* |
| **Persistence** | Recommendation Log *(append-only)* |
| **Decision authority** | ⛔ **Human developer — always** |
| **Future implementations** | ML · Hybrid · LLM engines *(same contract, same capability)* |
| **Context ownership** | ⚠️ **OPEN — answered by the human at approval.** Candidates named by the ARB: Engineering Intelligence · Engineering Analytics · Recommendation · Knowledge Intelligence |

### ⚠️ Two canon notes the amendment must carry *(check-before findings)*

1. ⛔ **Term collision:** the FROZEN Platform Capability Pattern (PGP-01) defines "capability" as *invariant-protecting, fail-closed, prevents-never-repairs* — **Recommendation protects no invariant; it produces advice.** This is "capability" in the BUSINESS-capability sense (the chain: Observation → Measurement → Projection → Recommendation → Decision → Outcome → Learning). **The two senses must not merge; disambiguation is a UL act for governance.** This plan says "domain capability (business sense)" throughout.
2. ⭐ **Canon on ownership:** the validated Relationship Ontology **refuted "Domains own Capabilities"** — capabilities are anchored by **design policies (`DP-n`)**, 1:1. So the ownership question may resolve as *"which DP anchors Recommendation?"* rather than *"which context owns it?"* — **both readings are put to the human; the plan presumes neither.**

### The contract chain *(defined before code — record shapes only, no engine in sight)*

| Contract | Minimal shape |
|---|---|
| **Observation** | *(exists — the nine-field staged contract)* |
| **Recommendation Record** | `{id, rule, subject, evidence_refs[], text, ts, status}` |
| **Developer Decision** | `{recommendation_id, decision: ACCEPTED\|IGNORED\|DEFERRED, ts, actor}` |
| **Decision Rationale** | `{recommendation_id, reason_code, comment?}` — ⭐ **a SEPARATE concept from the decision** *(the decision is the response; the rationale is why — different business concepts, never one field)* |
| **Outcome** *(future)* | `{recommendation_id, decision_ref, observed_change, metric_delta, ts}` — ⭐ **refers back to BOTH the recommendation AND the decision** *(REV per review: enables "did accepted recommendations improve engineering?" · "were ignored ones actually unnecessary?" · "which rationale codes correlate with success?")* |

### Reason-code taxonomy *(codes + optional comment — free text alone cannot aggregate)*

`ALREADY_PLANNED · IMMEDIATE_VALUE · DEADLINE_PRESSURE · FALSE_POSITIVE · DUPLICATE · WAITING_DEPENDENCY · OTHER(comment required)` — *so the future learning layer can distinguish bad recommendation from bad timing from organizational constraint: fundamentally different engineering situations that identical "ignored" counts would conflate.* `DEFERRED` added as a decision value for the dependency case.

## Objective

Turn observations into advisory recommendations, log every recommendation with an ID, and capture developer decisions (accepted / ignored) — so the outcome questions become answerable.

## Scope

**IN:**
- `RecommendationEngine` (pure: observations + rules → recommendations) + runner, TDD-first per the standing practice
- **five starter rules as YAML data** *(rules-as-data — converging with the mechanism layer and the Governance-as-a-Service sketch)*:
  `R1` LCOM4 > 20 → recommend cohesion review · `R2` production-without-tests → recommend tests · `R3` CBO trend rising across snapshots → recommend dependency review · `R4` hotspot unchanged ≥ N runs → recommend refactoring priority review · `R5` WARN band + hotspot → recommend architecture attention
- append-only `recommendations.jsonl` — `{id, rule, subject, evidence_refs, text, ts, status: issued}`
- **decision capture v1 = manual**: developer marks `ACCEPTED` / `IGNORED` **+ REASON** *(prompted, free text — upgraded from "optional note" per review 2026-08-04: reasons are the difference between knowing WHAT happened and WHY — "ignored: deadline this sprint" vs "accepted: already planned" become the future learning layer's most valuable data)* via a tiny CLI or direct log line — *this feeds the workflow/usage logs and the dashboard's empty cells*
- dashboard upgrade: recommendations section + acceptance counters (the first outcome cells begin to fill)
- developer guide 05, shipped **with** the step

**OUT:** ⛔ AI/ML · learning/confidence adjustment (KnowledgeOS's future, needs outcome data first) · automatic code changes · blocking anything · new collectors · assessment-model design · **"PKS" in the component name** *(the association is a candidate; naming is governance's — the spike says "Recommendation Engine v1")*.

## Design decisions (proposed — approval decides)

1. **Rules reference the observation contract only** (rule/mechanism separation honored — a rule never knows Deptrac or pdepend exist).
2. **Recommendation ≠ observation ≠ decision**: three record types, three files, no fusion (the I-1 lesson at the tooling level).
3. **Advisory forever within this spike** — acceptance is measured, never assumed; the protected sentence governs: *issuance counts are vanity; acceptance and outcomes are the product.*
4. Dedup: one open recommendation per (rule, subject) — re-issuance only after the subject changes.

## Task checklist

- [ ] RED: engine tests (rule matching · dedup · verdict-free recommendation records · YAML loading)
- [ ] GREEN: `RecommendationEngine` + `recommendation-rules.yaml` + runner
- [ ] Decision-capture CLI (`recommendation-decide.php <id> accepted|ignored [note]`)
- [ ] Dashboard: recommendations + acceptance counters section
- [ ] First real run over current observations (expect: R1 fires on Election/Committee · R5 on the hotspot set)
- [ ] Developer guide 05 + index
- [ ] Record the spike outcome honestly (incl. whether the Assessment-Commission trigger now has evidence)

## Questions the spike INFORMS but must not answer *(REV per review 2026-08-04)*

1. ⭐ **Is Recommendation a DOMAIN CAPABILITY or an APPLICATION SERVICE?** *(if recommendations grow lifecycle · confidence · versioning · effectiveness · learning → probably a domain; if they merely transform observations into advice → probably a service. Deliberately unanswered; the spike's operational shape is the evidence.)*
2. Which context or `DP-n` owns it *(the standing ownership question)*.
3. Whether "recommendation" generalizes to **INTERVENTION** *(one kind among future: review comments · doc suggestions · governance reminders · test hints — measuring INTERVENTIONS keeps the framework stable as assistance expands. Staged with the Run-2 measurement design, not renamed now)*.

## Measurement staging *(with the Run-2 outward design — every recommendation is a small experiment)*

Each intervention record eventually reads: problem → recommendation → decision+rationale → commit → **outcome after N commits (metric delta)** → status *(successful / unsuccessful / partial)*. Per-rule success tables follow (issued/accepted/successful), and the candidate headline KPI: ⭐ **Engineering Improvement Score — % of interventions with measurable engineering improvement.** ⛔ *All of it fills from evidence; none of it ships with v1 beyond the record fields that make it computable later.*

## Risks

Recommendation spam (mitigate: dedup + top-N) · pre-solving the commission (mitigate: §interplay, rules-as-data, no taxonomy) · vanity metrics (mitigate: dashboard counts acceptance, never issuance alone) · dead recommendations nobody marks (that ITSELF is the evidence the outcome cells exist to show).

## Next actions

**None until EP-01 approval.** On approval: checklist top-to-bottom; completion is an OE-entry candidate and — if developers actually decide on recommendations — the first data the learning loop has ever had.

---
*Traceability: phase-boundary review 2026-08-04 · gate interplay with the staged Engineering Assessment Commission declared in §interplay · rules-as-data converges with the mechanism layer + GaaS sketch (R-2/XACML shape) · three-level separation (observe/recommend/decide) enforced by construction · Discovery Freeze v1.0 untouched (this is engineering, not architecture discovery).*
