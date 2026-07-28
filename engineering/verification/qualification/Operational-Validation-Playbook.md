# Operational Validation Playbook

**Kind:** validation protocol + operating playbook for the Operational Validation phase (sibling class: OQ-ENG-003, the observation protocols) — **this artifact IS the "Operational Validation Program"** independently specified three times (DA candidate commission · DA closing question set · the source prompt pack).
**Status:** **PREPARED — awaiting Decision Authority authorization to begin the phase** (the phase-start is a DA act; natural vehicle: the same signature batch as ratification + the closure declaration).
**Renamed** from "Prompt Pack" per DA review — it defines governance, execution, templates, evidence, and cadence: a playbook.
**Adaptation provenance:** derived from `developer_guide/ai_platform/Operational Validation Prompt Pack.md` (ungoverned source, RR-F4-class disposition pending) under the DA's "approve after adaptation" review, 2026-07-27. **Adaptations applied:** generic-roadmap references → the **Reference Model + adapted queue + Disposition Register** · trust/confidence scores as governance → **removed** (fact surface retained; ranking-only per the RR-F3 addendum) · Governance Council/DKO/steward → **the existing decision-authority model** · asserted targets → **baseline → measure → ratchet** (ES-003.3) · "design the challenge mechanism" → **operationalize the existing Finding lifecycle** · Architectural Validation Questions **AQ-1..AQ-6 added** · structured as Parts I/II/III (physical split evaluated and deferred — shared lifecycle/owner; trigger: diverging cadence or independent reuse).
**Constraint inherited from the closure:** this playbook validates the architecture; it never redesigns it.
**Final pre-ratification revision (DDD sign-off, 2026-07-27 — expressive clarifications only, folded once):** domain policy separated from workflow and measurement (P6) · implicit domain concepts acknowledged **by pointer** to their catalogued homes (Knowledge Domain Model — "aggregate" remains candidate vocabulary) · roles restated as **responsibilities performed by actors** (§I.6) · event vocabulary appendix added (names only, anchored to Phase-02 §5) · AQ retitled **Validation Domain Questions** (they validate domain invariants, not KPIs) · closure wording DDD-glossed (§I.2). **From this revision forward, the playbook changes only through its own §I.5 evidence chain — stop-refining is now a property of the artifact, not an instruction.**

---

# Part I — Handbook (explains)

## I.1 What is under validation

**The architecture this repository actually has** — per the Reference Model and the Disposition Register — never the generic five-layer framework (readiness review RR-F1/F2):

| Under validation | Canonical definition |
|---|---|
| Canonical ownership + placement discipline | ES-005 + Placement Rule (post D-1..D-5) + ownership model candidate |
| Four-axis lifecycle + status honesty | UL + Metamodel §6 (post OQ-ENG-004) |
| Append-only audit + provenance | AIP-11 corpus |
| Qualification + evidence discipline | ES-003 + OQ instruments |
| The one new instrument | documentation-currency instrument (proposed OQ-ENG-005) |
| Challenge workflow | **the existing Finding lifecycle** (ES-003.1: F-… → DA disposition → CR-… → re-run) + attempt-to-reject |
| Knowledge-assembly for AI (if its gates open) | Context Assembly Research Charter · Knowledge Packages · EPC-001/004 |

## I.2 Operating mode (the Global Directive, adapted)

**Strategic architecture is closed for redesign; domain discovery continues through operational evidence** (the DDD gloss on closure: the model, its language, and its policies keep evolving through learning — what is closed is conceptual *redesign*, not knowledge). The architecture is the baseline to validate. No new conceptual layers, classifications, or governance bodies without recurring operational deficiency evidenced and routed through the existing change chain (§I.5). Clarify, never expand. Every finding is tied to observed evidence and labeled **FACT · EVIDENCE · INTERPRETATION · RECOMMENDATION · OPEN QUESTION** (the epistemic-label discipline, R-36 §4). Default answer to "does this need architectural change?": **no — collect more evidence.**

**Effort allocation (DA-endorsed):** ~40% real engineering use · ~30% build only readiness-review gaps · ~20% measure outcomes · ~10% architecture, evidence-responsive only.

## I.3 Friction taxonomy (retained intact — DA-endorsed)

Every observed issue is classified exactly once: **TOOLING · ADOPTION · TRAINING · WORKFLOW · METADATA · GOVERNANCE · CONCEPTUAL.** Only CONCEPTUAL may trigger architectural change, and only with an evidence package through §I.5. *(Convergence note: this taxonomy composes with the platform's A/B/C/D observation classification — friction class describes the problem; A/B/C/D routes the record.)*

## I.4 Validation Domain Questions (AQ — the DA's addition; answered at the 90-day review and at phase end)

*(Retitled per DDD sign-off: these validate **domain invariants**, not KPIs — AQ-3 tests the no-duplication invariant; AQ-6 tests whether governance rules held invariant under real use.)*

- **AQ-1** Did canonical ownership reduce ambiguity?
- **AQ-2** Did contributors know where knowledge belongs (zero-judgment placement)?
- **AQ-3** Did the Reference Model prevent duplicate documentation?
- **AQ-4** Which architectural assumption failed?
- **AQ-5** Which assumption was never exercised? *(unexercised ≠ validated)*
- **AQ-6** Which governance rule was bypassed, and was the bypass caught?

## I.5 Change control (no new process — the existing chain IS the ACR)

```text
Observation (friction log, classified) → Evidence (repeated, counted — never scored)
→ CONCEPTUAL classification with evidence package → ES-006.1 ladder → Decision Authority
```

Review and decision are **distinct roles, never merged** (DA sign-off refinement, 2026-07-27): the **Architecture Review Board** reviews and recommends (architectural recommendations, readiness assessments, technical review); the **Decision Authority** accepts, rejects, or defers (governance decisions, phase gates, ratification). No council is created. Verdict vocabulary: ES-003.1. "No change" is a success outcome.

## I.6 Roles

**Responsibilities, performed by actors** (DDD sign-off: the domain concepts are the responsibilities; actors fill them): **Ratification · Disposition · Authorization** — performed by the Decision Authority · **Review · Recommendation · Readiness Assessment** — performed by the Architecture Review Board (recommendations never self-enact) · **Execution · Evidence Production** — performed by the engineering design assistant (all output `generated`, never self-adopted) · **Finding-raising (the challenge surface)** — performed by any contributor. Enterprise roles (DKO/stewards/councils) appear only inside KnowledgeOS product material (routed, Disposition Register #3/#18).

**Implicit domain concepts, acknowledged by pointer (never re-listed here):** Finding · Correction · Disposition · Hypothesis · Evidence Record · Observation behave as identified, lifecycle-bearing domain concepts — **their catalog is the Knowledge Domain Model** (2026-07-27) and the candidate Metamodel; "aggregate" remains candidate vocabulary until the model earns adoption.

---

# Part II — Prompt Sequence (executes; each prompt inherits Part I as its directive)

| # | Prompt | Adapted objective · key constraints |
|---|---|---|
| P1 | **Closure Record** | **Largely exists**: the closure declaration is drafted (session record 2026-07-27) awaiting the ratification signature. P1 = transcribe the signed declaration into the rulings register; conditions + known-gaps carried forward (challenge validation; the Evidence-context split stays an open question ONLY if the validation touches the EPIC-002 lineage — else out of scope). No design. |
| P2 | **Validation Charter** | Scope: pilots (P3) · 90 days · thin slices of §I.1 items. Success criteria are **hypotheses with baselines pending** (P4) — no asserted percentages; the charter states falsification conditions and that **zero CONCEPTUAL findings is a positive outcome**. |
| P3 | **Pilot Selection** | Repository-fit criteria (DA adaptation): engineering diversity · knowledge complexity · active maintenance · AI-retrieval demand. Candidates named from reality (e.g. an active product context such as Adjudication/Determination work; the engineering platform's own corpus; the Project Knowledge pilot if its gate opens — interleaving per charter ask 3). Selection only; no design. |
| P4 | **Baselines & Hypotheses** | **Baseline before target, always** (ES-003.3). Measure the before-state (discovery time · staleness rate — ENG-005 gives a head start · duplication · ownership coverage · audit coverage · retrieval precision *if* the retrieval gate opens). Hypotheses falsifiable, targets set only *after* baselines, confidence-labeled (HIGH/MED/LOW) where proxied. |
| P5 | **Thin-Slice Backlog** | **The adapted queue IS the backlog** — no parallel list: ratification → C3+OQ-ENG-003 → D-1..D-5 → OQ-ENG-004 → commission OQ-ENG-005 (documentation currency) → challenge-surface routing (P6) → [gated] context-assembly slice. Trust-score computation from the source pack: **removed** (Disposition #11) — the fact surface (status · authority · review-date · canonical source · provenance) is already the implementation. Dashboards stay deferred-with-trigger (#14). Build nothing not listed; gaps become OPEN QUESTIONS. |
| P6 | **Challenge Validation** | **Operationalize, never design** (DA wording) — stated at three separated levels (DDD sign-off): **Domain policy** (canonical, ES-003.1 — pointed at, never restated): *a Finding may be raised against any knowledge; a Finding requires evidence; every Finding receives a disposition; history is preserved.* **Application workflow:** "disputed/stale?" flag → F-id created → reviewer assigned → evidence collected → disposition → CR if authorized → re-verify. **Operational validation (this playbook's own layer):** measure adoption (was it used?), resolution traceability, time-to-disposition, recurrence — counts, baselines first, thresholds ratcheted. |
| P7 | **AI Retrieval Evaluation** | **Gated:** runs only if the Context Assembly gate opens (internal) or as KnowledgeOS Stage-5 material (product). Comparison arms retained (raw vs metadata-filtered vs ranked); **ranking heuristics permitted per the RR-F3 addendum** — ephemeral, never persisted into records, never authority signals; no weight-tuning mid-evaluation; results are counts + human-judged correctness, no composite quality score persisted. |
| P8 | **Weekly Evidence Review** | 30 minutes · append-only evidence log (session-log convention) · classify (friction + epistemic) · **no redesign in-review** (the report-never-fix discipline applied to observation). Blockers escalate to the **Decision Authority** (not a council). |
| P9 | **Change Control** | = §I.5 verbatim. The prompt exists to *route*, not to create process. |
| P10 | **90-Day Validation Review** | Answers: hypotheses (P4) confirmed/falsified/unmeasured · **AQ-1..AQ-6** · friction distribution (counts per class) · disposition recommendations per ES-004.1 form (recommend + evidence + "DA decision: PENDING"). |
| P11 | **Six-Month Maturity Assessment** | Evidence-only maturity re-assessment against the Phase-1-baseline vocabulary (Emerging/Defined/Managed/Institutionalized) — qualitative, no scores persisted; sustainability of effort allocation reviewed. |
| P12 | **KnowledgeOS Initialization** | **Gated behind the charter's three asks + Stages 1–4.** Validation evidence feeds Stage 3 as source material. The platform's architecture is inherited as *founding evidence*, never assumed to transfer (standing caution on record). |

---

# Part III — Templates (standardizes evidence)

**T1 — Evidence log entry:** `date · observer · observation (FACT) · friction class · epistemic label · evidence ref · A/B/C/D routing · action (default: none)`
**T2 — Friction summary row:** `class · count this period · cumulative · exemplar refs · CONCEPTUAL? (evidence package ref or n/a)`
**T3 — Change request (routes through §I.5):** `observation refs (≥ recurring) · friction class = CONCEPTUAL justification · what existing rule/concept is insufficient (cite) · smallest change proposal · what evidence would falsify the proposal · ladder stage · DA decision: PENDING`
**T4 — Hypothesis row:** `id · claim · metric (count/fact-based) · baseline (measured, date) · target (set post-baseline) · falsification condition · status (UNMEASURED/CONFIRMED/FALSIFIED/INCONCLUSIVE)`
**T5 — AQ answers (P10/P11):** one row per AQ-1..6: `answer · evidence · epistemic label`
**T6 — Challenge record:** `= a Finding record (F-id series)` — deliberately not a new template; the existing lifecycle's forms apply.

**Appendix — Operational Validation event vocabulary (names only — vocabulary, never implementation; anchored to the platform's existing event language, Phase-02 §5, which already names `FindingRaised` · `ReviewCompleted` · `GatePassed` · `SessionArchived`):** `ValidationPhaseStarted` · `BaselineMeasured` · `FrictionObserved` · `FindingRaised` (existing) · `EvidenceRecorded` · `DispositionIssued` · `HypothesisConfirmed` / `HypothesisFalsified` · `RatificationSigned` · `ValidationReviewCompleted` (90-day) · `ValidationPhaseCompleted`. All past-tense facts per the UL naming convention; candidate vocabulary until used in records; any formal catalog extension follows the Canonical-Event-Catalog precedent (additive, governed).

---

## Constraint check & traceability

Nothing implemented by this document · no governance created (PREPARED; phase-start is a DA act) · no council, no score, no asserted target survives from the source · the source pack remains in place as ungoverned reference material pending its RR-F4-class disposition.

*Traceability: DA adaptation review 2026-07-27 (approve-after-adaptation, six adaptations + AQ addition + rename + split-consideration) · source: `developer_guide/ai_platform/Operational Validation Prompt Pack.md` (read: structure + prompts 1–5 in full, remaining prompts by adaptation-target scan) · constitutional anchors: RR-F3 addendum (ranking ≠ authority) · Disposition Register rows #3/5/9/10/11/14/16/18 · ES-003.1/.2/.3 · ES-006.1 · R-36 §4 · the closure declaration (drafted, awaiting signature). **STOP — submitted to the Decision Authority; the validation phase begins on its authorization.***
