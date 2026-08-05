# Operational Validation Prompt Pack

## Engineering Knowledge Architecture — Phase Transition: Conceptual Architecture → Operational Validation

---

**Version:** 1.0
**Date:** July 2026
**Prepared for:** Dr. Nab Raj Roshyara
**Context:** Post-Conceptual Architecture Closure

---

## 1. Review of Architecture Phase Assessment

### 1.1 Agreement with the Assessment

The assessment is architecturally sound. The signal it identifies — that successive iterations are producing stylistic and organizational refinements rather than new architectural insights — is the correct criterion for closing a conceptual design phase. This is not a judgment that the architecture is perfect. It is a judgment that the marginal return on additional conceptual work has fallen below the marginal return on operational learning.

The phase map is accurate:

| Stage | Status | Evidence |
|-------|--------|----------|
| Vision | Complete | Coherent purpose: governed, queryable, auditable engineering knowledge |
| Strategic DDD | Complete | Bounded contexts defined for engineering knowledge domains |
| Engineering Platform Architecture | Complete | Five-layer governance framework, retrieval pipeline, CI gates |
| Governance Architecture | Complete | RACI, ownership registry, lifecycle state machine, review cadences |
| Knowledge Architecture | Complete | Metadata schema, taxonomy, confidence scoring, trust indicators |
| Reference Model | Complete | Audit event model, event taxonomy, artifact templates |
| Review & Verification | Complete | Advisor-verified, three targeted corrections applied |
| Readiness Review | Complete | Readiness criteria defined, gaps identified |
| **Conceptual Architecture** | **CLOSED** | Phase boundary crossed |
| Operational Validation | Not yet begun | **Next phase** |
| KnowledgeOS Implementation | Beginning | Dependent on validation evidence |
| Continuous Evolution | Years | Ongoing operational maturity |

### 1.2 One Nuance

The assessment recommends 40% real use, 30% build gaps, 20% measure, 10% refine. This is correct in spirit, but the transition should begin as a **thin operational validation slice**, not full-scale implementation. The first operational activity should not be "deploy the governance framework across all engineering domains." It should be: "deploy the thinnest possible version of each layer in 2-3 pilot domains, measure what happens, and let the evidence determine what to build next."

This preserves the phase boundary. It prevents implementation from silently reopening conceptual design under the guise of "building."

### 1.3 The Challenge Mechanism Gap

The prior ARB work identified the **absence of challenge mechanisms** as the single largest governance gap. The conceptual architecture addresses this indirectly through the lifecycle state machine (review-reopen state, event-triggered reviews, stale flagging). But it has not been validated operationally. A critical operational validation question is:

> Can a contributor challenge stale, wrong, duplicated, or conflicting knowledge, and can the system resolve that challenge with traceable authority?

This question must be answered by use, not by additional design. It is called out explicitly in the prompt pack below.

---

## 2. Global Operating Directive

**Include this directive at the top of every prompt in this pack. It establishes the mode of work and prevents conceptual drift.**

```text
OPERATIONAL VALIDATION MODE — ACTIVE

You are operating in Operational Validation Mode for the Engineering Knowledge
Architecture (KnowledgeOS). The conceptual architecture phase is closed.

OPERATING PRINCIPLES:
1. The current architecture is the BASELINE TO VALIDATE, not a draft to redesign.
2. Do not introduce new conceptual layers, frameworks, classifications, or
   maturity models unless real operational evidence demonstrates a recurring
   deficiency that the current architecture cannot address.
3. Existing concepts may be clarified (documentation, examples, edge cases) but
   not expanded (new sub-layers, new governance bodies, new asset types) without
   demonstrated operational need.
4. Every recommendation, finding, or architectural change must be tied to
   observed operational evidence — not theoretical possibility.
5. Classify friction carefully. Friction is not proof of architectural failure.
   Classify every observed issue as one of:
   - TOOLING (the tool doesn't support the process)
   - ADOPTION (people haven't adopted the process yet)
   - TRAINING (people don't know how to use the process)
   - WORKFLOW (the process doesn't fit existing engineering workflows)
   - METADATA (metadata schema needs adjustment, not redesign)
   - GOVERNANCE (governance rules need tuning, not restructuring)
   - CONCEPTUAL (the architecture itself is insufficient — requires evidence)
   Only the CONCEPTUAL classification may trigger architectural change.
6. Categorize all findings as: FACT, EVIDENCE, INTERPRETATION, RECOMMENDATION,
   or OPEN QUESTION.
7. Prefer use, measurement, and implementation evidence over further
   conceptual refinement.
8. When in doubt about whether something requires architectural change, default
   to "it doesn't" and collect more evidence first.

EFFORT ALLOCATION (per the Architecture Review Board recommendation):
- 40% — Apply the governance in real engineering work
- 30% — Build only the gaps identified in the readiness review
- 20% — Measure outcomes (where did the architecture help? create friction?
        which assumptions proved wrong?)
- 10% — Refine the architecture, ONLY in response to operational evidence
```

---

## 3. Phase-Specific Prompts

Each prompt is self-contained and can be pasted into an AI assistant session (Claude Code CLI or equivalent). Copy the Global Operating Directive first, then the specific prompt.

---

### Prompt 1: Conceptual Architecture Closure Record

**Purpose:** Formally document the phase closure with conditions, creating an auditable record that future work can reference.

```text
TASK: Draft a Conceptual Architecture Closure Record for the Engineering Knowledge
Architecture (KnowledgeOS).

This is a formal governance artifact, not a design document. It records the
decision to close the conceptual architecture phase and the conditions under
which it may be reopened.

INCLUDE:
1. CLOSURE STATEMENT
   - The conceptual architecture phase is closed as of [DATE].
   - The architecture is sufficient for the next phase (Operational Validation).
   - This is not a declaration that the architecture is complete or final.

2. ARTIFACTS PRODUCED (list with one-line descriptions)
   - Reference all architectural artifacts created during the conceptual phase.
   - Include the Five-Layer AI Knowledge Governance Framework.
   - Include the metadata schema, lifecycle state machine, review cadence
     matrix, audit event model, trust indicators, operating model, and
     governance artifacts.
   - Include any DDD context maps and bounded context definitions.

3. CONDITIONS OF CLOSURE
   - No new conceptual layers unless operational evidence reveals recurring
     deficiencies that the current architecture cannot address.
   - Existing concepts may be clarified (examples, edge cases, documentation)
     but not expanded (new layers, new governance bodies, new asset types)
     without demonstrated operational need.
   - Future architectural changes require a documented operational evidence
     case, classified using the friction taxonomy (TOOLING, ADOPTION, TRAINING,
     WORKFLOW, METADATA, GOVERNANCE, CONCEPTUAL).
   - Only friction classified as CONCEPTUAL may trigger architectural change.
   - Engineering effort should now prioritize implementation and validation.

4. KNOWN GAPS (carried forward, not resolved by design)
   - Challenge/dispute mechanisms: identified as the largest gap by the ARB.
     To be validated operationally, not redesigned conceptually.
   - Evidence bounded context: may need to be split into Provenance, Custody,
     Integrity, Audit sub-contexts. This decision is deferred to operational
     validation. NOTE: If this validation includes the constitutional trust /
     evidence domain (EPIC-002 lineage), carry forward the Evidence context
     split as an open question. Otherwise treat it as out of scope for this
     validation cycle.
   - Any other gaps identified in the readiness review.

5. EFFORT ALLOCATION for the next 6 months
   - 40% real use, 30% build gaps, 20% measure, 10% refine (evidence-driven).

6. AUTHORIZATION
   - Issued by: Architecture Review Board
   - Date: [DATE]
   - Next review: 90 days after operational validation begins

OUTPUT FORMAT: Formal governance record in Markdown. Maximum 2 pages.
Do not redesign any architecture in this document. It is a record, not a design.
```

---

### Prompt 2: Operational Validation Charter

**Purpose:** Define the scope, objectives, success criteria, and constraints for the operational validation phase. This is the equivalent of the discovery charter from the conceptual phase, but oriented toward validation rather than design.

```text
TASK: Draft an Operational Validation Charter for the Engineering Knowledge
Architecture (KnowledgeOS).

CONTEXT:
The conceptual architecture phase is closed. The Five-Layer AI Knowledge
Governance Framework, metadata schema, lifecycle state machine, review cadences,
audit trail model, and operating model are defined. The next phase is to
validate that this architecture works in real engineering work.

OBJECTIVE:
Validate that the Engineering Knowledge Architecture, as designed, can:
1. Be deployed in 2-3 pilot engineering domains with minimal adaptation.
2. Reduce engineering effort in knowledge discovery and onboarding.
3. Improve AI retrieval precision and answer trust.
4. Reduce knowledge duplication and staleness.
5. Survive real engineering workloads for 90 days without architectural
   collapse.
6. Produce operational evidence that either confirms or challenges the
   architectural assumptions.

SCOPE:
- 2-3 pilot domains (to be selected in Prompt 3).
- All five layers deployed in thin-slice form (minimal viable version of each
  layer, not full enterprise deployment).
- 90-day validation window.
- Both human-facing workflows (PR-based review, ownership) and AI-facing
  workflows (metadata, retrieval, confidence scoring) exercised.

OUT OF SCOPE:
- Enterprise-wide rollout.
- New architectural layers or frameworks.
- Conceptual redesign of any existing layer.
- Tooling selection beyond what is needed for the thin-slice pilot.

SUCCESS CRITERIA (measurable):
- > 80% of pilot assets have complete Tier 0 metadata.
- > 70% of pilot assets have complete Tier 1 metadata.
- Review SLA compliance > 75% for pilot domains.
- Stale content rate < 20% for pilot domains.
- AI retrieval precision > 70% on pilot asset corpus (human-evaluated).
- All operational observations are classified using the friction taxonomy.
- Any CONCEPTUAL finding has a complete evidence package and ACR submitted.
- Zero CONCEPTUAL findings is a valid and positive validation outcome.
- Challenge mechanism tested at least 5 times (contributors challenging
  stale/wrong/conflicting knowledge).

CONSTRAINTS:
- Do not redesign the architecture. If something doesn't work, document it as
  evidence and classify it using the friction taxonomy.
- Do not expand the metadata schema outside the ACR/change-control process.
  If fields are missing, note them as OPEN QUESTIONS for the architecture
  change-control process.
- Do not create new governance bodies. Use the existing operating model.
- Thin-slice implementation only. Build the minimum needed to exercise each
  layer. Do not gold-plate.

OUTPUT FORMAT: Charter document in Markdown. Include objectives, scope, success
criteria, constraints, and a validation timeline aligned to the 90-day plan.
```

---

### Prompt 3: Pilot Domain Selection

**Purpose:** Select 2-3 engineering domains for operational validation based on criteria that maximize learning value.

```text
TASK: Recommend 2-3 pilot engineering domains for operational validation of the
Engineering Knowledge Architecture.

CONTEXT:
The architecture uses DDD bounded contexts as the unit of domain ownership. Each
pilot domain must have:
- A defined bounded context with identifiable systems and services.
- An existing body of knowledge assets (ADRs, runbooks, standards, etc.) that
  can be migrated to docs-as-code.
- A willing Domain Knowledge Owner (team) and at least one Technical Steward.
- Sufficient complexity to exercise all five governance layers.
- Enough existing knowledge decay/staleness to make the validation meaningful.

SELECTION CRITERIA (weight each domain against these):
1. KNOWLEDGE VOLUME: Domain preferably has 15+ existing knowledge assets to
   migrate (preference, not a hard gate).
2. CRITICALITY MIX: Domain has assets spanning at least 2 criticality tiers
   (critical, important, reference).
3. TEAM READINESS: Domain owner is willing and has capacity for 3-5 hours/week.
4. DECAY SIGNAL: Domain has visible knowledge decay (stale ADRs, orphaned docs,
   conflicting guidance).
5. AI RETRIEVAL VALUE: Domain's knowledge is frequently queried by engineers
   or AI assistants (high retrieval demand).
6. INDEPENDENCE: Domains should be sufficiently independent that cross-domain
   dependencies are minimal (reduces validation complexity).
7. DIVERSITY: Selected domains should represent different architectural patterns
   (e.g., one event-driven, one request-response, one batch/data pipeline).

CONSTRAINTS:
- Do not select more than 3 domains for the initial validation.
- Do not select domains that are currently undergoing major reorganization.
- Do not select domains where the DKO cannot commit 3-5 hours/week.

OUTPUT FORMAT:
- Table of candidate domains scored against selection criteria.
- Recommendation of 2-3 domains with rationale.
- For each selected domain: existing asset inventory summary, DKO confirmation
  status, and known knowledge decay signals.
- Do not begin designing the pilot implementation. Selection only.
```

---

### Prompt 4: Baseline Metrics & Hypotheses

**Purpose:** Establish the before-state measurements and the specific hypotheses the validation will test. Without baselines, you cannot prove improvement.

```text
TASK: Define the baseline metrics and validation hypotheses for the operational
validation of the Engineering Knowledge Architecture.

CONTEXT:
The architecture makes several operational claims:
- It reduces engineering effort in knowledge discovery.
- It improves onboarding time.
- It speeds decision-making.
- It improves AI assistance quality.
- It reduces knowledge duplication.
- It survives real engineering work.

These are operational claims that can only be proven by measurement against a
baseline. The baseline is the BEFORE state, measured before the architecture is
deployed in the pilot domains.

DEFINE BASELINE METRICS FOR:
1. KNOWLEDGE DISCOVERY TIME
   - How long does it take an engineer to find the correct, current guidance
     for a typical task in each pilot domain? (Measure via timed tasks with
     3-5 engineers per domain.)
2. ONBOARDING EFFICIENCY
   - How long does it take a new team member to make their first meaningful
     contribution? (Historical data from last 2-3 hires.)
3. KNOWLEDGE FRESHNESS
   - What percentage of existing knowledge assets are stale (no review in
     > 12 months)? (Manual audit of pilot domain assets.)
4. DUPLICATION RATE
   - How many duplicate or conflicting knowledge assets exist in each pilot
     domain? (Manual scan.)
5. AI RETRIEVAL BASELINE
   - Using current (non-governed) knowledge, what is the precision of AI
     answers for 20 domain-specific queries? (Human-evaluated.)
6. OWNERSHIP COVERAGE
   - What percentage of pilot domain assets have a clear, accountable owner?
     (Manual audit.)
7. AUDIT TRAIL COVERAGE
   - What percentage of recent knowledge changes have a reviewable audit
     trail? (Likely near 0% — this is the baseline.)

FORMULATE HYPOTHESES (for each metric, state the hypothesis the architecture
makes):
- H1: Knowledge discovery time will decrease by > 30% after governance
  deployment.
- H2: Onboarding time to first PR will decrease by > 20%.
- H3: Stale content rate will decrease from [baseline]% to < 10%.
- H4: Duplicate/conflicting assets will decrease by > 50%.
- H5: AI retrieval precision will increase from [baseline]% to > 85%.
- H6: Ownership coverage will reach 100% within 30 days of deployment.
- H7: Audit trail coverage will reach 100% for all new changes from deployment
  date forward.
- H8: The challenge mechanism will be exercised at least 5 times in 90 days,
  with at least 60% resolution rate.

CONSTRAINTS:
- Baselines must be measured BEFORE architecture deployment where possible.
  When real pre-deployment measurement is impossible, use historical data or
  proxy measurements, and mark these with a CONFIDENCE level (HIGH/MEDIUM/LOW).
- Hypotheses must be falsifiable (state what evidence would disprove them).
- Do not set targets so high they cannot be achieved with thin-slice
  implementation. Adjust targets if the baseline reveals the starting point is
  worse or better than expected.

OUTPUT FORMAT:
- Baseline measurement plan (what, how, who, when).
- Hypothesis table (ID, hypothesis, metric, target, falsification condition).
- Note any metrics that cannot be baselined before deployment and explain why.
```

---

### Prompt 5: Thin-Slice Implementation Backlog

**Purpose:** Define the minimum viable implementation needed to exercise all five layers in pilot domains. This is the build backlog for the 30% effort allocation.

```text
TASK: Define a thin-slice implementation backlog for operational validation of
the Engineering Knowledge Architecture.

CONTEXT:
The five layers are:
1. Domain Ownership & Accountability
2. Knowledge Asset Lifecycle Management
3. Enterprise Metadata & Semantic Retrieval
4. Automated Review Cadence & Quality Gates
5. Auditability, Trust & Institutional Memory

A "thin slice" means: the minimum implementation of each layer that is
sufficient to exercise it in real engineering work and collect operational
evidence. It is NOT the full enterprise implementation described in the roadmap.

DEFINE THE BACKLOG AS USER STORIES WITH ACCEPTANCE CRITERIA:

LAYER 1 THIN SLICE:
- Create ownership registry YAML for pilot domains.
- Assign DKO and stewards.
- Map existing assets to domains.
ACCEPTANCE: Every pilot asset has an assigned DKO and domain.

LAYER 2 THIN SLICE:
- Define lifecycle states in frontmatter.
- Apply states to existing pilot assets (draft/review/approved).
- Set next_review_date based on criticality.
ACCEPTANCE: Every pilot asset has a lifecycle state and review date.

LAYER 3 THIN SLICE:
- Define metadata schema YAML (Tier 0 + Tier 1 fields).
- Apply Tier 0 metadata to all pilot assets.
- Apply Tier 1 metadata to at least 50% of pilot assets.
- Set up embedding service + vector store for pilot assets.
ACCEPTANCE: > 80% of pilot assets have Tier 0 metadata; > 50% have Tier 1;
retrieval returns pilot assets with metadata context.

LAYER 4 THIN SLICE:
- Implement CI gate for Tier 0 metadata validation.
- Implement link checker.
- Implement stale detection job (scheduled review reminders).
- Implement PR-based review workflow with steward approval.
ACCEPTANCE: CI blocks PRs with missing Tier 0 metadata; stale detection
generates review tickets; at least one review cycle completes end-to-end.

LAYER 5 THIN SLICE:
- Implement audit trail logging for: asset_created, content_updated,
  review_approved, state_changed, stale_flagged.
- Implement basic trust score computation (freshness + metadata completeness
  only; defer other components).
- Build a simple governance dashboard (read-only view of pilot domain metrics).
ACCEPTANCE: All listed audit events are logged; trust scores are computed;
dashboard displays asset count, stale count, ownership coverage, and review
compliance for pilot domains.

ADDITIONAL BACKLOG ITEMS (cross-cutting):
- Docs-as-code repository setup for pilot domains.
- Migration of existing pilot assets from wiki/other to Git.
- Taxonomy v1 for pilot domains (domain-specific tags).
- Challenge mechanism thin slice: a simple process for contributors to flag
  an asset as "disputed" or "potentially stale," triggering a review ticket.
  (This addresses the ARB-identified gap operationally, not conceptually.)

CONSTRAINTS:
- Do not implement features not listed here. If a gap is discovered during
  implementation, log it as an OPEN QUESTION, do not build it.
- Do not build enterprise-grade infrastructure. Use the simplest tool that
  works for pilot scale (e.g., GitHub Actions for CI, a simple vector DB,
  a basic dashboard script).
- Each backlog item must have a clear acceptance criterion.
- Prioritize items that unblock other layers (Layer 1 before Layer 2, etc.).

OUTPUT FORMAT:
- Backlog table: ID, Layer, Story, Acceptance Criteria, Priority, Dependencies,
  Estimated Effort (story points or hours).
- Note any items that require tooling decisions and list 2-3 options for each.
```

---

### Prompt 6: Challenge Mechanism Validation

**Purpose:** The ARB identified the absence of challenge mechanisms as the single largest governance gap. This prompt validates it operationally rather than redesigning it conceptually.

```text
TASK: Design and validate a thin-slice challenge mechanism for the Engineering
Knowledge Architecture.

CONTEXT:
The Architecture Review Board identified the "complete absence of challenge
mechanisms" as the single largest governance gap in the conceptual architecture.
The architecture addresses this indirectly through:
- The "review-reopen" lifecycle state.
- Event-triggered reviews.
- Stale content flagging.
- Duplicate detection in CI gates.

But these are system-initiated challenges. The gap is: can a HUMAN contributor
challenge knowledge they believe is wrong, stale, duplicated, or conflicting,
and can the system resolve that challenge with traceable authority?

THIN-SLICE CHALLENGE MECHANISM DESIGN (operational, not conceptual):

1. CHALLENGE TYPES:
   - STALE: "This asset is outdated and no longer reflects current reality."
   - INCORRECT: "This asset contains factual or technical errors."
   - DUPLICATE: "This asset duplicates another asset."
   - CONFLICTING: "This asset conflicts with another canonical asset."
   - ORPHANED: "This asset has no clear owner and may be abandoned."

2. CHALLENGE WORKFLOW (thin-slice):
   - Contributor opens a challenge issue in the knowledge repository.
   - Issue template captures: asset_id, challenge_type, evidence/description,
     suggested resolution.
   - Challenge automatically assigns to the asset's Technical Steward(s).
   - If the asset has no steward (orphaned), escalates to the DKO.
   - Steward must respond within [SLA: 5 business days for critical assets,
     10 for important, 20 for reference].
   - Resolution options: accept challenge (update/deprecate asset), reject
     challenge (with rationale), escalate to DKO.
   - All challenge events are logged to the audit trail.

3. VALIDATION QUESTIONS (to answer through use, not design):
   - Q1: Do contributors actually use the challenge mechanism? (Adoption)
   - Q2: What is the challenge-to-resolution time? (Efficiency)
   - Q3: What percentage of challenges result in asset changes? (Accuracy)
   - Q4: Do challenges surface knowledge gaps that the system-initiated
     reviews missed? (Value-add)
   - Q5: Does the challenge mechanism create meaningful workload for stewards?
     (Sustainability)
   - Q6: Are there challenge types the current taxonomy doesn't cover?
     (Completeness — but do NOT add new types without evidence)

4. TRACEABILITY:
   - Every challenge must generate an audit event: challenge_opened,
     challenge_resolved, challenge_rejected, challenge_escalated.
   - The resolution must be linked to the asset's audit trail.
   - If an asset is challenged multiple times, this should be visible in the
     governance dashboard as a signal (possibly indicating the asset needs
     fundamental revision or the domain needs ownership review).

5. CONTESTED REJECTION PATH (thin-slice):
   - If a contributor disputes a rejected challenge, they may escalate ONCE to
     the DKO (or Governance Council if the DKO was the original rejector).
   - The escalation must include: original challenge, rejection rationale,
     and contributor's counter-evidence.
   - The DKO/Council decision is final. The decision and full rationale are
     logged to the audit trail.
   - This is a minimal contested-rejection path, not a formal appeals process.
     If it proves insufficient, log as evidence for the ACR process.

CONSTRAINTS:
- Do not design a dispute resolution committee or formal appeals process.
  That is conceptual architecture. If the thin-slice mechanism proves
  insufficient, log it as evidence for the architecture change-control process.
- Do not implement automated challenge resolution. Human steward judgment is
  required.
- Do not expand the challenge types beyond the five listed without
  operational evidence that additional types are needed.

OUTPUT FORMAT:
- Challenge issue template (Markdown, ready to use in GitHub/GitLab).
- Challenge workflow diagram (text-based).
- Audit event definitions for challenge events.
- Validation metric definitions (Q1-Q6 with measurement method).
```

---

### Prompt 7: AI Retrieval Evaluation Protocol

**Purpose:** Establish a rigorous, repeatable evaluation protocol for AI retrieval quality. This is the 20% measurement effort directed at the AI-facing claims of the architecture.

```text
TASK: Define an AI Retrieval Evaluation Protocol for the Engineering Knowledge
Architecture.

CONTEXT:
The architecture claims that enterprise metadata, confidence scoring, and
freshness signals will improve AI retrieval precision from a baseline to > 85%.
This claim must be tested rigorously and repeatably.

PROTOCOL DESIGN:

1. QUERY SET CONSTRUCTION:
   - Construct 20-50 stratified queries per pilot domain (start with 20-30;
     expand to 50 if evaluator capacity allows).
   - Query types:
     a) FACTUAL LOOKUP: "What is the timeout value for the payment gateway?"
     b) PROCEDURAL: "How do I failover the payment gateway?"
     c) ARCHITECTURAL: "Why did we choose Kafka over RabbitMQ?"
     d) TROUBLESHOOTING: "The reconciliation job is failing, what should I
        check?"
     e) AMBIGUOUS: "How does our logging work?" (tests disambiguation)
   - Queries should reflect REAL questions engineers ask, not synthetic ones.
   - Collect real queries from: Slack/search logs, new hire questions, incident
     channels, code review discussions.

2. GROUND TRUTH ESTABLISHMENT:
   - For each query, a domain expert identifies the CORRECT answer and the
     CANONICAL source asset(s).
   - Ground truth includes: expected answer, expected source asset_id, expected
     confidence level.

3. EVALUATION RUNS:
   - Run A (BASELINE): Retrieval against pilot assets WITHOUT governance
     metadata (raw content only, no metadata filtering, no confidence scoring).
   - Run B (PARTIAL): Retrieval with metadata filtering (lifecycle_state,
     domain) but without confidence scoring.
   - Run C (FULL): Retrieval with full governance (metadata filtering +
     confidence scoring + freshness signals + canonical source ranking).

4. METRICS:
   - PRECISION@1: Is the top-ranked result the canonical source? (Yes/No)
   - PRECISION@5: Is the canonical source in the top 5 results? (Yes/No)
   - ANSWER CORRECTNESS: Does the AI-generated answer match ground truth?
     (Correct / Partially Correct / Incorrect / Hallucinated)
   - SOURCE CITATION: Does the AI cite the canonical source? (Yes/No)
   - FRESHNESS ACCURACY: Is the retrieved asset within its review SLA? (Yes/No)
   - STALE RETRIEVAL RATE: How often is stale content retrieved? (Percentage)
   - CONFIDENCE CALIBRATION: Do confidence scores correlate with correctness?
     (High-confidence answers should be correct more often than low-confidence.)

5. EVALUATION CADENCE:
   - Baseline (Run A): Before governance deployment.
   - Run B: 30 days after deployment.
   - Run C: 60 days after deployment.
   - Final evaluation: 90 days after deployment.
   - Ad hoc: After any metadata schema change, embedding model update, or
     confidence weight adjustment.

6. HUMAN EVALUATION:
   - Each AI answer is evaluated by a domain expert (not the query author).
   - Evaluator classifies: Correct / Partially Correct / Incorrect / Hallucinated.
   - Evaluator notes: Was the canonical source cited? Was the answer stale?
     Was it misleading?

CONSTRAINTS:
- Do not optimize the evaluation protocol for high scores. Design it to find
  failures. A protocol that shows 95% precision on its first run is not
  rigorous enough.
- Do not conflate retrieval precision with answer quality. An AI can retrieve
  the correct source and still generate an incorrect answer. Measure both.
- Do not adjust confidence weights to improve scores. Record the weights used
  in each run and note any weight changes as architectural changes requiring
  evidence justification.

OUTPUT FORMAT:
- Evaluation protocol document.
- Query set template.
- Ground truth template.
- Evaluation run report template (for each run: metrics, observations,
  classified findings).
```

---

### Prompt 8: Weekly Operational Evidence Review

**Purpose:** Establish a lightweight weekly cadence for collecting, classifying, and acting on operational evidence. This is the operational equivalent of the ARB review cycle.

```text
TASK: Define a Weekly Operational Evidence Review process for the Engineering
Knowledge Architecture during the operational validation phase.

CONTEXT:
During the 90-day validation window, operational evidence must be collected
continuously and reviewed weekly. This is not a design activity — it is a
measurement and classification activity.

WEEKLY REVIEW PROCESS:

1. EVIDENCE COLLECTION (continuous, throughout the week):
   - Any participant (DKO, steward, contributor, AI retrieval owner) can log
     an observation.
   - Observation template:
     ```
     Date: [YYYY-MM-DD]
     Observer: [name/role]
     Domain: [pilot domain]
     Observation: [what happened]
     Layer affected: [L1/L2/L3/L4/L5/cross-cutting]
     Friction classification: [TOOLING/ADOPTION/TRAINING/WORKFLOW/METADATA/
                              GOVERNANCE/CONCEPTUAL]
     Finding type: [FACT/EVIDENCE/INTERPRETATION/RECOMMENDATION/OPEN QUESTION]
     Severity: [blocker/major/minor/observation]
     Proposed action: [if any]
     ```

2. WEEKLY REVIEW MEETING (30 minutes, same participants each week):
   - Review all observations from the past week.
   - Confirm or revise friction classifications.
   - Identify patterns (same friction recurring = signal).
   - Decide actions:
     a) NO ACTION (observation logged, monitor for recurrence)
     b) TOOLING FIX (adjust tooling, not architecture)
     c) PROCESS TUNING (adjust cadence, SLA, workflow)
     d) OPEN QUESTION (needs more evidence before action)
     e) ARCHITECTURE CHANGE REQUEST (only for CONCEPTUAL friction — requires
        evidence package, see Prompt 10)
   - Update the Operational Evidence Log.

3. EVIDENCE LOG (cumulative, append-only):
   - All observations, classified, with actions taken.
   - Searchable by domain, layer, friction type, finding type.
   - Forms the evidence base for the 90-day and 6-month reviews.

4. ESCALATION CRITERIA:
   - Any CONCEPTUAL friction → immediate architecture change request.
   - Any blocker → resolve within 48 hours or escalate to Governance Council.
   - Any pattern (3+ occurrences of same friction) → flag for review
     regardless of classification.
   - Any challenge mechanism failure (challenge unresolved past SLA) →
     flag for review.

CONSTRAINTS:
- Weekly review is 30 minutes maximum. If it takes longer, the process is
  too heavy. Simplify.
- Do not use the weekly review to redesign architecture. If an item is
  classified CONCEPTUAL, it goes to the change-control process (Prompt 10),
  not the weekly meeting.
- Do not skip weeks. Consistency of evidence collection is more important
  than completeness of any single week's observations.

OUTPUT FORMAT:
- Observation template (copy-paste ready).
- Weekly review agenda (30-minute structure).
- Evidence log structure (Markdown table or YAML).
- Escalation criteria summary.
```

---

### Prompt 9: Architecture Change-Control Process

**Purpose:** Define the process by which operational evidence can trigger architectural changes. This enforces the closure conditions: no architectural changes without demonstrated operational need.

```text
TASK: Define an Architecture Change-Control Process for the Engineering Knowledge
Architecture during and after the operational validation phase.

CONTEXT:
The conceptual architecture phase is closed with the condition that future
architectural changes require demonstrated operational need. This process
defines how that need is demonstrated, evaluated, and acted upon.

ARCHITECTURE CHANGE REQUEST (ACR) PROCESS:

1. TRIGGER:
   - An operational observation is classified as CONCEPTUAL friction (the
     architecture itself is insufficient, not just the tooling or process).
   - OR: A pattern of 3+ related observations across different domains/weeks
     suggests a systemic architectural gap.
   - OR: The 90-day or 6-month review identifies a recurring deficiency.

2. ACR PACKAGE (required for any architectural change):
   ```
   ACR-ID: [sequential]
   Date: [YYYY-MM-DD]
   Requester: [name/role]
   Trigger: [observation IDs or review reference]
   
   EVIDENCE SUMMARY:
   - What operational evidence supports this change?
   - How many times has this friction been observed?
   - Across how many domains?
   - What is the impact of NOT making this change?
   
   FRICTION CLASSIFICATION:
   - Confirm this is CONCEPTUAL (not TOOLING, ADOPTION, etc.)
   - If there is any doubt, list what additional evidence would resolve it.
   
   PROPOSED CHANGE:
   - What specific architectural element would change?
   - Which layer is affected?
   - Is this a clarification (documentation, examples) or an expansion
     (new layer, new governance body, new asset type, new metadata fields)?
   
   ALTERNATIVES CONSIDERED:
   - Could this be resolved with tooling changes?
   - Could this be resolved with process changes?
   - Could this be resolved with training?
   - Why are these insufficient?
   
   IMPACT ASSESSMENT:
   - What other layers/components are affected?
   - Does this change invalidate any existing artifacts?
   - Does this require metadata schema migration?
   ```

3. REVIEW AND DECISION:
   - ACRs are reviewed by the Knowledge Governance Council.
   - Decision options:
     a) APPROVED — change may proceed.
     b) DEFERRED — more evidence needed. Specify what evidence.
     c) REJECTED — the friction is real but does not require architectural
        change. Specify alternative resolution.
     d) RECLASSIFIED — the friction is not CONCEPTUAL. Reclassify and
        redirect to appropriate resolution path.
   - Decision must be made within 10 business days of ACR submission.

4. CHANGE IMPLEMENTATION:
   - If approved, the change is implemented as an architectural update.
   - The update must be versioned (e.g., Metadata Schema v1.1, Framework v2.0).
   - All affected artifacts must be updated.
   - The change and its evidence are recorded in the Architecture Change Log.

5. ARCHITECTURE CHANGE LOG (append-only):
   - ACR-ID, date, trigger evidence, approved change, version, affected
     artifacts, implementation date.
   - This log becomes part of the audit trail for the architecture itself.

CONSTRAINTS:
- No architectural change may be made without an approved ACR.
- Clarifications (examples, edge cases, documentation) do not require ACRs but
  should be logged.
- Expansions (new layers, new governance bodies, new asset types, new metadata
  fields) always require ACRs.
- The burden of proof is on the requester. "This would be better" is not
  sufficient. "This is failing in this specific way, observed N times, across
  M domains, and here is the evidence" is sufficient.

OUTPUT FORMAT:
- ACR template (copy-paste ready).
- Decision workflow diagram (text-based).
- Architecture Change Log structure.
- List of change types and whether they require ACRs.
```

---

### Prompt 10: 90-Day Validation Review

**Purpose:** Conduct the formal 90-day review of operational validation, producing the evidence package that determines whether the architecture is confirmed, needs targeted changes, or requires reopening the conceptual phase.

```text
TASK: Conduct the 90-Day Operational Validation Review for the Engineering
Knowledge Architecture.

CONTEXT:
The operational validation has been running for 90 days. Baseline metrics were
captured (Prompt 4), thin-slice implementation was deployed (Prompt 5), the
challenge mechanism was tested (Prompt 6), AI retrieval was evaluated (Prompt 7),
and weekly evidence was collected (Prompt 8). Any architectural changes were
processed through change-control (Prompt 9).

REVIEW STRUCTURE:

1. HYPOTHESIS EVALUATION:
   - For each hypothesis (H1-H8 from Prompt 4):
     - State the hypothesis.
     - Present the baseline measurement.
     - Present the 90-day measurement.
     - State: CONFIRMED, PARTIALLY CONFIRMED, DISCONFIRMED, or INCONCLUSIVE.
     - If disconfirmed or inconclusive: what evidence explains the gap?

2. FRICTION ANALYSIS:
   - Total observations logged: [N]
   - By friction classification:
     - TOOLING: [N] — summary of top issues
     - ADOPTION: [N] — summary
     - TRAINING: [N] — summary
     - WORKFLOW: [N] — summary
     - METADATA: [N] — summary
     - GOVERNANCE: [N] — summary
     - CONCEPTUAL: [N] — each one detailed with ACR status
   - Patterns identified (3+ recurring observations):
     - List each pattern with frequency, domains affected, and resolution status.

3. CHALLENGE MECHANISM ASSESSMENT:
   - Number of challenges opened: [N]
   - Number resolved: [N]
   - Number rejected: [N]
   - Number escalated: [N]
   - Average resolution time: [N] days
   - Challenge types used: which of the 5 types were exercised?
   - Any challenge types that should be added? (Evidence only, not speculation.)
   - Did the challenge mechanism surface gaps that system-initiated reviews
     missed? (Yes/No with examples.)

4. AI RETRIEVAL ASSESSMENT:
   - Baseline precision: [N]%
   - 30-day precision: [N]%
   - 60-day precision: [N]%
   - 90-day precision: [N]%
   - Stale retrieval rate at 90 days: [N]%
   - Confidence calibration: [description]
   - Notable failures: [examples of incorrect/hallucinated answers]

5. ARCHITECTURAL CHANGES:
   - ACRs submitted: [N]
   - ACRs approved: [N]
   - ACRs deferred: [N]
   - ACRs rejected: [N]
   - Summary of approved changes and their impact.

6. OVERALL ASSESSMENT:
   - Is the architecture CONFIRMED as sufficient for the next phase?
   - Are there targeted changes needed before scaling?
   - Does any evidence suggest reopening the conceptual architecture phase?
   - What are the top 3 lessons learned?

7. RECOMMENDATION FOR NEXT PHASE:
   - Proceed to enterprise rollout? (With what modifications?)
   - Extend pilot? (For how long? What needs to be proven?)
   - Targeted architecture revisions? (Which ACRs to prioritize?)

CONSTRAINTS:
- This review is EVIDENCE-BASED. Every claim must reference specific
  observations, metrics, or measurements.
- Do not use this review to propose new architecture. If architectural changes
  are needed, they go through the ACR process (Prompt 9).
- Be honest about failures. A validation that finds no problems is a validation
  that wasn't looking hard enough.
- Distinguish between "the architecture doesn't work" (CONCEPTUAL) and "the
  implementation needs improvement" (TOOLING/WORKFLOW). These require
  different responses.

OUTPUT FORMAT:
- Formal review document (10-15 pages).
- Executive summary (1 page).
- Evidence appendix (all metrics, observations, ACRs).
- Recommendation with conditions.
```

---

### Prompt 11: Six-Month Operational Maturity Assessment

**Purpose:** Conduct the broader six-month assessment that looks beyond the 90-day pilot to evaluate whether the architecture is maturing or decaying under real operational load.

```text
TASK: Conduct a Six-Month Operational Maturity Assessment for the Engineering
Knowledge Architecture.

CONTEXT:
Six months have passed since operational validation began. The 90-day review
was completed, targeted changes were made, and the system has been running in
real engineering work for an additional three months. This assessment
evaluates whether the architecture is MATURING (improving over time) or
DECAYING (degrading under operational load).

ASSESSMENT DIMENSIONS:

1. TRAJECTORY ANALYSIS (are things getting better or worse?):
   - Compare 90-day metrics to 6-month metrics for:
     - Stale content rate (should be decreasing)
     - Review SLA compliance (should be increasing)
     - Metadata completeness (should be increasing)
     - AI retrieval precision (should be stable or increasing)
     - Ownership stability (should be stable)
     - Challenge mechanism usage (should be stable or increasing)
     - Trust score distribution (median should be increasing)
   - For each metric: is the trajectory IMPROVING, STABLE, or DEGRADING?
   - Any metric that is degrading requires explanation.

2. SCALABILITY ASSESSMENT:
   - If pilot domains were expanded (additional domains onboarded after 90-day
     review), how did the architecture handle the expansion?
   - Were there domains where the governance model didn't fit naturally?
   - Did the metadata schema accommodate new asset types or domains without
     modification? (If modification was needed, was an ACR required?)
   - Did the CI quality gates scale without becoming bottlenecks?

3. ADOPTION ASSESSMENT:
   - What percentage of engineers in pilot domains have contributed to or
     reviewed a knowledge asset in the last 90 days?
   - Is the challenge mechanism being used organically (not prompted)?
   - Are engineers trusting AI-retrieved knowledge without manual verification?
     (Survey or behavioral evidence.)
   - Are new hires using the governance system during onboarding?

4. ARCHITECTURAL DEBT:
   - How many ACRs are pending? (Deferred but not resolved)
   - Are there workarounds in place that should be architectural changes?
   - Has the metadata schema accumulated technical debt (fields that are
     defined but not used, or used but not enforced)?
   - Are there assets that exist outside the governance system (shadow
     knowledge)?

5. SUSTAINABILITY:
   - Is the DKO and steward time commitment sustainable? (Are people still
     doing it after 6 months?)
   - Is the Platform Knowledge Engineer role adequately resourced?
   - Is the weekly evidence review still happening? (Or has it been
     deprioritized?)
   - Are governance dashboard metrics being acted upon, or just observed?

6. ARCHITECTURE FITNESS:
   - Has any CONCEPTUAL friction been observed that the architecture genuinely
     cannot address?
   - Are there domains or asset types that don't fit the current model?
   - Is the bounded context ownership model still aligned with the actual
     organizational structure?
   - Has the trust scoring model proven useful, or is it ignored?

7. MATURITY LEVEL:
   - Rate each dimension: NASCENT / DEVELOPING / ESTABLISHED / OPTIMIZING
   - Overall maturity: [level] with justification
   - What is the single biggest risk to continued maturation?

CONSTRAINTS:
- This is an assessment, not a redesign. Findings feed into ACRs if needed.
- Be particularly attentive to SILENT DECAY — metrics that look stable but
  where the underlying process is being bypassed (e.g., review compliance
  looks good because reviews are being rubber-stamped).
- Compare against the 40/30/20/10 effort allocation. Has the organization
  actually shifted to 40% use? Or is it still 80% design?

OUTPUT FORMAT:
- Maturity assessment report.
- Trajectory charts (text-based or data tables).
- Dimension ratings with evidence.
- Top 5 risks and mitigations.
- Recommendation: continue at current pace, accelerate rollout, or pause and
  address architectural debt.
```

---

### Prompt 12: KnowledgeOS Implementation Initialization

**Purpose:** Begin the transition from operational validation to product implementation of KnowledgeOS, using the evidence gathered during validation to scope the build.

```text
TASK: Define the KnowledgeOS Implementation Scope based on operational validation
evidence.

CONTEXT:
The operational validation phase has produced evidence about what works, what
doesn't, and what the architecture needs. KnowledgeOS is the productized
implementation of the Engineering Knowledge Architecture — the tooling, platform,
and infrastructure that makes the governance framework operational at enterprise
scale.

This is NOT a product specification. It is an implementation scope definition
that translates validation evidence into build priorities.

DEFINE IMPLEMENTATION SCOPE IN THREE TIERS:

TIER 1 — VALIDATED (build immediately, evidence supports):
- List each component of the five-layer framework that was successfully
  validated during operational validation.
- For each, summarize the evidence that validates it.
- Define the implementation scope: what needs to be built beyond the thin-slice
  to make it enterprise-ready.
- Example: "CI quality gates validated in 2 domains. Enterprise build needs:
  support for 20+ domains, parallel execution, caching, custom rule plugins."

TIER 2 — ADJUSTED (build with modifications based on validation findings):
- List components that worked but required adjustments during validation.
- For each, describe the adjustment and the evidence that drove it.
- Define the adjusted implementation scope.
- Example: "Metadata schema required 3 additional fields during validation.
  Enterprise build incorporates these fields and adds validation rules."

TIER 3 — EVIDENCE-PENDING (do not build until evidence is sufficient):
- List components or features that were proposed but lack validation evidence.
- For each, state what evidence is needed before building.
- Example: "Automated conflict resolution between duplicate assets: no evidence
  that manual resolution is unsustainable. Do not build until evidence shows
  manual resolution is a bottleneck."

IMPLEMENTATION PRINCIPLES:
1. Build only what has been validated or adjusted based on validation.
2. Do not build features that were not tested during operational validation.
3. If a new feature is desired, it must go through the ACR process with
   operational evidence.
4. Prefer extending validated components over building new ones.
5. The implementation should make the governance INVISIBLE to engineers who
   just want to find or contribute knowledge — governance happens in the
   background through CI, automation, and metadata enforcement.

CONSTRAINTS:
- Do not design KnowledgeOS as a standalone product disconnected from existing
  engineering tools. It must integrate with: Git hosting, CI/CD, ticketing,
  IDE, and existing documentation systems.
- Do not build a custom UI unless the validation evidence shows that existing
  tools cannot display the governance information engineers need.
- Do not scope more than can be built in 6 months with the available team
  (1-2 Platform Knowledge Engineers + part-time contributions from domain
  stewards).

OUTPUT FORMAT:
- Tiered implementation scope document.
- Evidence summary for each tier.
- Build priority order with dependencies.
- 6-month implementation timeline (high-level).
- Resource requirements.
```

---

## 4. Usage Guide

### How to Use This Prompt Pack

1. **Start with Prompt 1** (Conceptual Architecture Closure Record) to formally close the design phase.

2. **Then run Prompts 2-4 in sequence** (Charter → Pilot Selection → Baseline Metrics) to set up the validation. These must be completed before any implementation begins.

3. **Run Prompt 5** (Thin-Slice Implementation Backlog) to define what to build. This is the build plan for the 30% effort allocation.

4. **Deploy Prompt 6** (Challenge Mechanism) as part of the thin-slice implementation. It addresses the ARB-identified gap operationally.

5. **Run Prompt 7** (AI Retrieval Evaluation) at baseline, 30, 60, and 90 days. This is the 20% measurement effort.

6. **Run Prompt 8** (Weekly Evidence Review) continuously throughout the 90-day validation window.

7. **Use Prompt 9** (Architecture Change-Control) whenever a CONCEPTUAL friction is identified. This enforces the closure conditions.

8. **Run Prompt 10** (90-Day Review) at the end of the validation window.

9. **Run Prompt 11** (Six-Month Assessment) three months after the 90-day review.

10. **Run Prompt 12** (KnowledgeOS Implementation) only after the 90-day review confirms the architecture is sufficient for scaling.

### Guardrails Summary

These guardrails apply to ALL prompts in this pack:

- Do not redesign terminology for elegance.
- Do not add maturity models, governance layers, or classifications unless evidence requires them.
- Do not treat friction as proof of architectural failure; classify it.
- Every recommendation must be tied to observed operational evidence.
- Categorize findings as FACT, EVIDENCE, INTERPRETATION, RECOMMENDATION, or OPEN QUESTION.
- Architecture becomes the smallest activity (10% of effort).
- When in doubt about whether something requires architectural change, default to "it doesn't" and collect more evidence.

### Effort Allocation Reminder

| Activity | Allocation | Prompts |
|----------|-----------|---------|
| Real use in engineering work | 40% | Prompts 2-6, 8 |
| Build missing capabilities | 30% | Prompts 5, 6, 12 |
| Measure outcomes | 20% | Prompts 4, 7, 8, 10, 11 |
| Refine architecture (evidence-driven only) | 10% | Prompt 9 |

---

*This prompt pack is designed to preserve the phase boundary between conceptual architecture and operational validation. It treats the current architecture as a baseline to validate, not a draft to redesign. The architecture will evolve — but only in response to evidence, not in response to the temptation to design.*
