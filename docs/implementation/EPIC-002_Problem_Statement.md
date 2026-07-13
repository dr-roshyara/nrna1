# EPIC-002 — Problem Statement (Research Charter for Strategic Discovery — **Constitutional Trust Discovery**)

**Kind:** research charter — NOT an IDD, NOT an ADR. This document is the constitution of the Strategic Discovery phase: it defines the problem, the questions, and the boundaries of the research. It decides nothing about the solution.
**Authorized:** ARB 2026-07-11. **Activated:** after the EPIC-001 retrospective + formal closure.
**Role instruction (ARB, binding for this phase):** *Do not think like a software architect during EPIC-002 — think like a researcher.* The researcher asks **"what exists?"**; the architect asks "what should we build?". EPIC-002 stays in the first role until the ARB authorizes an IDD.

---

## Objective (ARB redirect, 2026-07-11 — AUTHORITATIVE)

> **The objective is NOT to design the Evidence bounded context. The objective is to determine whether "Evidence" should exist as a bounded context at all.** It may become Evidence · Trust · Certification · Constitutional Audit · Provenance · Accountability · Audit Services — or two or three contexts. Approach with genuine uncertainty: confirmed, split, merged, renamed, or rejected are all acceptable outcomes. This phase is **Constitutional Trust Discovery**, not Evidence Discovery.

## Primary research question (problem space first — ARB, 2026-07-11)

> **What properties must a constitutional governance platform satisfy to produce evidence that stakeholders can trust?**

Discovery starts HERE — in the problem space — not at "how should Evidence be designed?". The research tries to **falsify** the going-in assumption (Evidence as one bounded context), not to elaborate it. Every discovery activity must answer one of: *what exists? · what is already known? · which assumptions are wrong? · which concepts recur across disciplines? · what ownership emerges naturally? · which responsibilities clearly do NOT belong together?*

**Finding labels (never collapsed):** every statement in discovery output is labeled **FACT** (observed from literature or implementation) · **INTERPRETATION** (analysis) · **RECOMMENDATION** (proposal) · **OPEN QUESTION** (unknown). At the end of each discovery session: STOP — the ARB decides whether discovery is sufficient to authorize Strategic DDD/tactical design.

## Initial assumptions to test (ARB, 2026-07-11 — the falsification targets)

| # | Assumption | Source |
|---|------------|--------|
| A-1 | "Evidence" is the right bounded context name | Existing roadmap |
| A-2 | Evidence must be immutable | ADR-T8 (forward-only) |
| A-3 | Evidence must be auditable | ADR-T1/T4/T16 |
| A-4 | Evidence must be replay-safe | Inbox deduplication |
| A-5 | Evidence must be tenant-isolated | ADR-T16 |
| A-6 | Evidence must be causally ordered | Parking + redrive |
| A-7 | Evidence must be anonymous | ADR-T11 |
| A-8 | Evidence must be queryable | IT-8 |
| A-9 | Evidence belongs to a single bounded context | Existing context map |
| A-10 | Evidence is produced by the correction loop | PB-004/005/006 |

## Problem

PublicDigit's constitutional correction loop (EPIC-001) produces a complete, provenance-carrying event trail — but **current operational evidence is implicit**. Events exist as messaging artifacts (outbox/inbox rows, correlation chains), not as *constitutional evidence*: nothing yet defines what makes a record admissible, immutable, publishable, replayable, or forensically inspectable. For a platform whose value proposition is trust, evidence is the next capability — and today nobody can state precisely what "Evidence" owns, where its boundaries lie, or whether it is one bounded context at all.

## Candidate bounded context

**Evidence** — explicitly a *candidate*. Discovery may conclude the candidate is too large, contains two or more contexts, or should merge with an existing concern. **Discovery must not assume its own answer.**

## Objectives (understand — not design)

1. **Ownership** — what does Evidence own vs merely observe? What do Contestation/Adjudication/Election/Messaging own that Evidence must never duplicate?
2. **Provenance** — how do the existing CorrelationId/CausationId chains (Constitutional Audit Invariant, ADR-MP-06) relate to evidential provenance and chain of custody?
3. **Admissibility** — what distinguishes *constitutional evidence* from telemetry? Who decides admissibility, and when?
4. **Immutability** — what must be tamper-evident vs merely append-only vs merely logged? What does the anonymity invariant (CI-5/Q7 — no voter↔vote linkage, ever) forbid evidence from containing?
5. **Replay** — does replay belong inside Evidence, beside it, or as a platform capability? What does "deterministic replay over constitutional history" require of evidence records?
6. **Evidence lifecycle** — capture → preservation → verification → publication → (retention/expiry?). Which stages exist in this domain at all?
7. **Interactions** — how does Evidence relate to Contestation (challenges cite evidence), Adjudication (`EvidenceEnvelopeRef` already exists in Determination — upstream dependency per the dependency map), and Legitimacy (hypothesis: read model — falsification question in scope).

## Out of scope (hard boundaries)

- ❌ No implementation. ❌ No aggregates. ❌ No repositories. ❌ No APIs. ❌ No Laravel. ❌ No IDD.
- ❌ No engineering-platform changes (production subsystem — demonstrated insufficiency only).
- ❌ No reopening of closed decisions without evidence (R49-07 order, frozen Blueprint, ADR corpus).

## Literature review (multi-disciplinary — the core research activity; meaningful time, not a day or two)

| Discipline | Key concepts |
|---|---|
| **Constitutional law** | due process · judicial review · evidentiary standards · legitimacy · challengeability · admissible evidence |
| **Administrative law** | government justification · appeals · procedural fairness · demonstrable reasoning |
| **Election science** | Risk-Limiting Audits (RLAs) · End-to-End Verifiable Voting · election certification · ballot accounting · observer models |
| **Digital forensics** | evidence admissibility · chain of custody · integrity · provenance · timestamping |
| **Trust engineering** | NIST trustworthy systems · assurance cases · safety cases · ISO trust frameworks |
| **Distributed systems** | Byzantine agreement · consensus · immutability · replay · deterministic reconstruction |
| **Provenance / data lineage** | W3C PROV · causal history · lineage · attribution |
| **Audit theory** | audit trails · forensic accounting · auditability |
| **DDD (strategic only)** | bounded contexts · ubiquitous language · context mapping · ownership — which concepts recur across ALL the disciplines above? |

Purpose: **validate or falsify our own model** — identify durable concepts that belong in the ubiquitous language; never copy systems.

**Permanent researcher rule (ARB, 2026-07-11):** every source must produce one of FOUR results against the assumption register — no collecting for its own sake:

| Result | Meaning |
|---|---|
| **Supports** | strengthens an existing PublicDigit assumption |
| **Weakens** | calls an assumption into question |
| **Contradicts** | directly opposes an assumption |
| **Introduces** | exposes an important concept never considered — enters the register as a NEW recorded assumption/concept |

The review maintains the assumption-evidence table (A-1..A-10 above + introduced entries).

## Research method (ARB enhancement, 2026-07-11 — governs the literature review)

**Standing instruction to any external research tool:** *"Do NOT design our architecture. Perform a systematic literature review. Produce a Normalized Knowledge Model."* Raw prose is not a deliverable; structured knowledge is.

**1. Research quality gates** — the review is incomplete until each gate is answered:
| Gate | Question |
|---|---|
| Evidence completeness | Are multiple independent sources converging? |
| Source diversity | Academic AND industry AND tooling ecosystems represented? |
| Recency | Are we relying on pre-AI-era assumptions where that matters? |
| Contradiction surface | Are disagreements between sources explicitly mapped, not averaged away? |

**2. Evidence classification** — every finding is labeled: **Observed practice** ("teams use X") vs **Claimed benefit** ("X improves Y") vs **Evidence strength** (anecdotal · case study · wide adoption without proof · empirical validation). Descriptive consensus is never treated as causal evidence.

**3. Normalized Knowledge Model (NKM)** — the unit of research output, one per concept:
```text
Concept · Purpose · Variants · Preconditions · Failure modes · Evidence level
```
The synthesis step consumes NKMs, never raw articles.

**4. Negative findings are mandatory** — what does NOT work, where approaches failed, what was abandoned and why. A review reporting only successes has failed the survivorship-bias check.

**5. Promotion criteria** — a researched concept may enter the candidate ubiquitous language only if: cross-domain evidence (≥2 independent disciplines from the table above) · survives the contradiction surface · aligns with existing principles (anonymity, provenance, append-only) · respects rule parsimony (no rule explosion).

**6. Time-box + confidence** — each objective above is time-boxed; findings carry an explicit confidence level (High/Medium/Low by source quality and consistency); a provisional conclusion with a marked uncertainty beats an unbounded search.

**7. Feedback loop** — implementation later becomes a research *producer*: failed implementations return here as first-class evidence for the next research cycle.

*(This method section serves EPIC-002 first. If a second research effort adopts it unchanged, it earns promotion to a platform research protocol — evidence first, per the placement litmus.)*

## PROCESS FREEZE (ARB, 2026-07-11 — AUTHORITATIVE, supersedes further meta-process elaboration)

**The research methodology is now sufficiently mature. No further research-framework mechanics unless implementation demonstrates a deficiency.** Effective immediately: optimize *quality of insight per paper*, not process. Preserve what exists (confirmations/refutations/coverage-gaps/negative-findings recording, the Concept Register, the mapping freeze) — do not add further meta-artifacts.

**Exactly two amendments are authorized this round** (below); no others.

### Amendment 1 — classification convention (REFINED, ARB 2026-07-11): "Observed in X" before "Candidate Y"

Every concept entry (Literature Review + Concept Register) records **where it was observed** (which discipline/jurisdiction the source comes from) and, separately, a **generality candidate** — never a premature final class. Convention: *"Observed in [discipline/jurisdiction] — Candidate [Universal / Domain-specific / Jurisdiction-specific] (evidence pending)"*. A concept sourced from one jurisdiction is NOT thereby proven jurisdiction-bound — the source is a fact; the generality is a hypothesis awaiting corroboration from an independent discipline. Only concepts corroborated across ≥2 independent disciplines/jurisdictions may drop the "candidate"/"evidence pending" qualifier.

**Also (same amendment, ARB refinement):** every concept additionally tagged **Descriptive** (describes existing practice/reality) or **Normative** (prescribes how a system ought to behave) — these carry different synthesis weight (a description of what exists is not the same kind of evidence as a prescription of what should exist).

**Wording discipline (ARB refinement):** external doctrine does not "prove" an internal assumption. Replace emphatic verdicts ("STRONGLY SUPPORTS", "DIRECTLY CONTRADICTS") with **"independent evidence consistent with / in tension with"** — the assumption register still uses Supports/Weakens/Contradicts/Introduces as its required classification vocabulary, but the prose around it must not overclaim proof.

### Amendment 2 — Stopping criterion refined (frequency alone is insufficient)

**Stop when BOTH hold:** (1) no concept promotion (LOW→MEDIUM/MEDIUM→HIGH) for three consecutive major sources, **AND** (2) no fundamentally new conceptual category has appeared (a single paper — e.g. the CAP theorem, ElectionGuard — can change a field regardless of promotion-frequency bookkeeping). Both conditions checked at every iteration boundary; frequency-only saturation is not sufficient to stop.

### Roadmap reorder (ARB, 2026-07-11 — supersedes the prior iteration-2 order)

Digital Evidence · Digital Forensics · Provenance (W3C PROV) · Trust Engineering · **ElectionGuard** · End-to-End Verifiable Voting are inserted **before** Governance Theory — they sit closer to the candidate Evidence context than governance theory's abstractions do. Order: ~~Constitutional Law ✓ → Administrative Law ✓~~ → **Digital Evidence/Forensics → Provenance (W3C PROV) → Trust Engineering → ElectionGuard → E2E-Verifiable Voting** → Governance Theory → DDD literature.

## Iteration-2 method amendments (ARB, 2026-07-11 — AUTHORITATIVE)

1. **Concept Register is the spine** (`EPIC-002_Concept_Register.md`, living): concept × discipline matrix; confidence by discipline count (HIGH ≥5 · MEDIUM 3–4 · LOW 1–2 · TENTATIVE single-source). The ONLY permitted mapping is concept→discipline.
2. **PublicDigit-mapping freeze:** forbidden until all major disciplines are reviewed — no "PublicDigit equals…", no "this means we should build…", no "this maps to…". Iteration-1 mapping interpretations are quarantined. Confirmation bias enters at the mapping step; the freeze blocks it.
3. **Pipeline refined:** literature → Concept Register → concept *frequency* analysis → concept *relationship* analysis → cross-disciplinary synthesis → clustering → candidate capabilities → candidate subdomains → candidate bounded contexts → ARB review → Strategic DDD → IDD → implementation.
4. **Stopping criterion (replaces the 3-source UL rule):** stop when no concept moves LOW→MEDIUM or MEDIUM→HIGH for three consecutive major sources (knowledge saturation, not fatigue). Movement log lives in the register.
5. **Per-source output format:** Source · Discipline · Key claims · Assumption impact (Supports/Weakens/Contradicts/Introduces) · Concept Register update · Category (FACT/INTERPRETATION/RECOMMENDATION/OPEN QUESTION) · Open question.
6. **Iteration-2 discipline order (law dominates):** Constitutional Law → Administrative Law → Governance Theory → Trust Engineering (assurance/safety cases, NIST, dependability) → Digital Forensics → Distributed Systems → DDD literature (Evans/Vernon/Brandolini — discovering domains rather than naming them).
7. **Standing open question carried into every discipline:** which trust properties are achievable in PublicDigit's private-organization constitutional setting, and which must be explicitly declared out of scope with recorded consequences?

## Synthesis-weighting guidance (recorded for the synthesis phase — NOT acted on now, ARB 2026-07-11)

The eventual cross-disciplinary synthesis should weight **election science, trust engineering, and evidence/forensics literature more heavily than administrative-procedure doctrine** — the platform is trustworthy voting, not administrative-law software. Legal doctrine informs the design; it does not dominate it. This is guidance for the synthesis step, not an instruction to re-weight completed iterations.

## RESEARCH WORKFLOW QUALIFICATION (ARB directive, 2026-07-13, refined same day — binding, precedes any further literature iteration)

*(Renamed from "Research Infrastructure Qualification" — ARB: "infrastructure" suggests servers/pipelines; this is workflow correctness.)*

### The pipeline (ARB-defined, refined)

```
Search → Extraction → Verification → Evidence Set (persisted) → Extraction Audit → Synthesis → Interpretation
```

**Evidence Set is the canonical research artifact** (new stage, ARB refinement): the persisted output of Verification — every confirmed, refuted, and unverified claim, each with its source quote and vote. In this program's existing practice, the per-iteration "Confirmed findings" + "Refuted claims" tables already written into `EPIC-002_Literature_Review.md` ARE the Evidence Set — no new file is created; the existing tables are recognized under this name retroactively. **Synthesis and Interpretation are consumers of the Evidence Set, never replacements for it** — this is why Extraction and Verification never need to re-run merely because Synthesis failed (exactly what happened in iteration 3a).

### Stage invariants

| Stage | Input | Output | Invariant |
|---|---|---|---|
| **Extraction** | Sources | Raw claims | Every extracted claim cites a specific source and carries a verbatim quote (no paraphrase). |
| **Verification** | Raw claims | Verified claims (confirmed / refuted / unverified) + vote record | Every claim receives an adversarial vote; the vote is recorded alongside the claim. |
| **Evidence Set** | Verified claims | Persisted, canonical claim record | **No verified claim is silently lost.** **No claim in the Evidence Set exists without a verification record.** |

**Evidence Set interface contract (ARB final refinement, 2026-07-13):** every entry contains exactly `{Claim, Evidence (quote), Source, Vote, Confidence, Status (confirmed/refuted/unverified)}` — this is the public contract between Verification and Synthesis; Synthesis may consume only these fields. **Versioned:** each Evidence Set is stamped with its producing run ID (e.g. `wf_3f9f7018-a37`) — if an iteration is rerun, the new Evidence Set is a new version (v2), never a silent overwrite; the invalid v1 stands as history. **Immutable:** once persisted, an Evidence Set entry is never edited — corrections happen by adding a new entry (e.g. a later-refuted claim) or a new version, never by mutating the old one. Immutability is what makes every later stage reproducible from a fixed input.
| **Extraction Audit** | Verified claims + Evidence Set | Audit result (PASS/FAIL) | Every verified claim is present in the Evidence Set. Every claim in the Evidence Set has a verified source. No unverified claim is silently promoted. |
| **Synthesis** | Evidence Set | Synthesized findings | Every synthesized claim originates from a verified claim in the Evidence Set. Every verified claim either appears in synthesis or is explicitly accounted for as excluded. No synthesized claim appears without a verified source (this is exactly the invariant iteration 3a's synthesis stage violated — it returned content with no traceable source at all). |
| **Interpretation** | Synthesized findings | Strategic Discovery input (ARB-gated) | No architectural interpretation without a synthesized finding behind it. |

### Validity rule

**A research iteration is VALID only if Extraction, Verification, Evidence Set, and Extraction Audit all pass, AND Synthesis passes.** If any stage fails, **the iteration is INVALID, not negative.** An invalid iteration must never be read as "0 confirmed / N refuted" (that reads as falsification succeeding). Instead, record explicitly:

> **Positive evidence status: UNKNOWN.**
> **Reason: the workflow failed before positive findings could be synthesized — this is a statement about the workflow, not about the world.**

An invalid iteration:
- does **not** update the recurrence table,
- does **not** change any phenomenon's status,
- does **not** touch any architectural hypothesis,
- but **does** retain whatever Evidence Set content it produced before the failing stage (e.g. refuted claims from a working Verification stage survive a broken Synthesis stage — they carry their own vote record and need no further stage to be legitimate).
- **A failed stage invalidates the iteration but must not invalidate previously completed stages** — the Evidence Set persists even when Synthesis fails, so Extraction and Verification never have to re-run for that reason alone.

### What happened in iteration 3a (2026-07-13), diagnosed, not guessed

Extraction PASS (104/105 agents completed, real claim text with quotes) · Verification PASS (per-claim votes recorded, including 7 well-formed refuted claims with real quotes and vote counts) · Evidence Set: the confirmed side is genuinely absent (the `confirmed` key never existed in the output — not merely empty) · Extraction Audit: would have caught this immediately had it existed as an explicit step · **Synthesis FAILED** — the final aggregation call returned a literal placeholder stub (`"claim":"test claim"`, `"evidence":"test evidence"`) with no traceable source, violating the Synthesis invariant outright. Manual reconstruction from the raw per-vote journal was attempted and abandoned: cache keys are opaque content hashes with no visible claim↔vote pairing exposed to the caller, so reconstructing confirmed claims by hand risked misattributing evidence — an unacceptable integrity risk.

**Verdict: iteration 3a is INVALID.** Positive evidence status: **UNKNOWN** — the workflow failed before positive findings could be synthesized; this says nothing about whether ElectionGuard/E2E-VV literature would confirm or refute the five phenomena. The 7 refuted claims are retained as legitimate Evidence Set content (their own stage — Verification — passed); they carry no conclusion about phenomenon survival, since the confirming half of the same test is exactly what Synthesis lost.

**Important operational note:** resuming the SAME run with identical arguments will NOT fix a synthesis-stage corruption — the tool's own caching semantics mean a call that "completed" (even with bad output) replays its cached result rather than regenerating. A genuine retry requires either a fresh (non-resumed) run or a deliberately altered prompt.

### Acceptance gate going forward

Before any iteration's findings are written into the Literature Review or Concept Register, verify Extraction, Verification, Evidence Set, Extraction Audit, and Synthesis all passed.

### PROCESS FREEZE (ARB, 2026-07-13 — the research workflow is now mature)

**"Synthesis and Interpretation are consumers of the Evidence Set, never replacements for it"** is adopted as a standing architectural principle of the research process. **The methodology is frozen under the same evidence-first governance already applied to the engineering platform: no further workflow refinement in anticipation of hypothetical problems.** Future changes to this pipeline require demonstrated deficiency, not foresight. This mirrors exactly the post-PB-007 transition on the engineering-platform track — the workflow has earned the right to be used, not further designed.

**Sequence from here:**
1. Run one small-scope qualification iteration — deliberately OFF-TOPIC from EPIC-002 (so a pass/fail proves nothing about ElectionGuard and costs nothing research-wise if it fails again) — to prove Extraction → Verification → Evidence Set → Extraction Audit → Synthesis can complete cleanly end-to-end.
2. If it passes: rerun the invalid ElectionGuard/E2E-VV iteration (3a) using the now-qualified workflow.
3. Continue the planned literature disciplines (Governance Theory, DDD literature) under the falsification-phase rules already in force.
4. Only after the full charter is covered: cross-disciplinary synthesis (ARB-gated).
5. Only after ARB approval of that synthesis: transition from Strategic Discovery into tactical design (IDD) — still out of scope for this ticket.

## FALSIFICATION PHASE (ARB directive, 2026-07-12 — supersedes discovery-mode emphasis for the remaining disciplines)

**The strategy changes: stop trying to discover more concepts; start trying to falsify the recurring phenomena.** The question for every remaining discipline is no longer "can I find more support?" but **"can I find a mature discipline where this phenomenon does NOT exist?"** A phenomenon that survives serious falsification attempts across ElectionGuard, E2E-verifiable voting, governance theory, and DDD literature is far stronger evidence of a genuine architectural regularity than any amount of additional confirmation.

Binding rules for the remaining iterations:
1. Remaining disciplines (ElectionGuard · E2E-VV · Governance Theory · DDD literature) run as **falsification attempts** against the recurring phenomena — counterexamples and contradictory evidence are the primary quarry; supporting examples are recorded but are not the goal.
2. **Independent Evidence Families replace discipline-count** as the recurrence metric. What matters is mechanism independence, not label count: ES contributes *statistical verification*, AL/CL *judicial review doctrine*, PR *ontology/formal modeling*, TE *safety-assurance argumentation*, DF *forensic procedure*. Two disciplines sharing one mechanism (e.g. both citing hashes) count as ONE family.
3. **Recurrence numbers carry limited meaning by themselves** — recurrence can reflect one school of thought echoing across fields. True independence requires different assumptions, methodologies, objectives, communities, and terminology, all converging.
4. **No new candidate concepts** unless they emerge from multiple independent sources; priority is strengthening or falsifying the existing recurring phenomena.
5. **Trajectory note (recorded, NOT adopted):** the ARB observes the recurring phenomena may be heading toward "constitutional invariants." They are NOT called invariants yet — the falsification phase is precisely the test of whether any deserves that name. A "Candidate invariant?" column exists in the register for bookkeeping only.
6. **ARB-introduced hypothesis to test (not mine, not adopted):** the level model may be missing a layer — *Reality → Observation → Evidence → Argument → Decision → Legitimacy*: evidence is not reality; evidence is already an interpretation of observations. The remaining disciplines should look for whether mature fields distinguish observation from evidence (e.g. raw sensor data vs. admitted exhibit; witnessed event vs. testimony). Recorded as a falsifiable hypothesis, tested like the phenomena.
7. Discovery/synthesis separation, mapping freeze, and all prior conventions remain in force unchanged.

## Deliverables (Strategic Discovery artifacts ONLY)

0. **Problem Space Model** — the primary deliverable: what concepts exist, which recur across disciplines, which differ, which conflict, which are domain-specific vs universal. Only AFTER it: whether "Evidence" is a bounded context.
1. **Literature review** (state of the art → PublicDigit gap analysis; assumption-evidence table with supports/weakens/contradicts/introduces)
2. **Candidate ubiquitous language** for the evidence domain
3. **Ownership map** (what the candidate context owns / observes / must never touch)
4. **Context map** (relationships to the implemented core; builds on `EPIC-002_Context_Dependency_Map_Draft.md`)
5. **Open questions register** (what discovery could not resolve)
6. **Recommendation to the ARB** — is "Evidence" confirmed, split, merged, renamed, or rejected; should tactical DDD begin, and for which context(s)
7. **STOP → ARB review**: the ARB decides whether the accumulated evidence authorizes an IDD.

## Stopping criterion (ARB-strengthened, 2026-07-11)

**Stop when no source changes the ubiquitous language for three consecutive major sources.** Not "finish the review"; not "time is up". State explicitly, in the report, where the criterion was met (or why the time-box ended first, with marked uncertainty).

## Success criterion

The Strategic Discovery Report can **confirm or change** the current understanding of the Evidence candidate. A report that merely restates the going-in assumption has failed; a report that falsifies it with evidence has succeeded.

---
*Charter for EPIC-002 Strategic Discovery. Inputs: EPIC-001 evidence chain · `EPIC-002_Context_Dependency_Map_Draft.md` · ADR-MP-06 provenance model · anonymity invariants (ADR-T11/CI-5/Q7). Traceability: ARB rulings 2026-07-10/11 (discovery-first, candidate-BC framing, researcher role).*
