# PKS Phase I — ARB Decision Analysis Memorandum

| | |
|---|---|
| **Kind** | Decision-analysis memorandum — ARB decision support. **Analytical, not advisory:** it identifies decisions, summarizes evidence, classifies ownership, exposes consequences — and stops. It is not an ARB ruling, approves or rejects no option, and exercises no governance authority. |
| **Authority** | Generated — never authoritative without human review. |
| **Status** | **PREPARED — for the convening of the PKS Phase I ARB review.** Adopts nothing; disposes of nothing. |
| **Relation to the package** | NOT a member of PKS Phase I Review Package v1.0 (fixed at four artifacts). ARB-support class, companion to `PKS_Phase_I_ARB_Review_Dossier.md`. **The dossier remains the record for the decision register (DR-1..DR-6) and the full open-questions register; this memorandum analyzes, it does not restate.** |
| **Provenance** | Derived from PA-supplied advisory material (a Principal-Architect review memorandum produced outside this session) refined through two PA-endorsed review rounds, packaged 2026-07-28 per the artifact-ingestion rule: raw material is supporting input; this governed form applies the reviews' language corrections and is verified against the repository record. Factual claims herein were grep-verified earlier this session (see Traceability). |
| **Placement** | Project-side per ES-005.3, beside the dossier it supports. |

**Reading rule — Classification ≠ Disposition.** This memorandum *classifies*: what kind of question is this, whose responsibilities does it appear aligned with, what evidence bears on it. Only the board *disposes*: what shall be done with it. Every routing statement below is classification. Where this memorandum echoes the dossier's routing, note that the dossier's routing is itself administrative classification, not adopted governance — **consistency with earlier analysis confers no authority.**

---

## 1. Executive assessment

Reviewed: the four package members plus the closure verification report. The package appears **procedurally complete, internally coherent, referentially sound, governance-compliant, explicit about uncertainty, and faithful to discovery-phase constraints** — consistent with the instrumented verification already on record (Closure Verification: PASS). No evidence was found that Phase I crossed into Strategic Modeling, reference modeling, technical architecture, implementation design, or unauthorized governance creation.

**The principal architectural value of the package is not the proposed concepts themselves but the separation it establishes and maintains between:** repository evidence · conceptual hypotheses · open modeling questions · governance decisions.

---

## 2. Per-decision analysis (decisions per dossier §3; options and consequences live there)

### DR-1 — Package disposition

Evidence bearing on acceptance: the five-class claim discipline with 9 recorded instances of "Evidence not found" rather than gap-filling; commission questions Q0–Q9 answered with measured inventories; uncertainty preserved as registered outputs (OQ-PKS-1..12, C-1..C-4) rather than resolved by assumption. Should the board accept, findings it *may choose* to record include: identity scheme unresolved · incumbent unresolved · no whole-system completeness definition exists · cross-artifact consistency ownership undefined · lifecycle-vocabulary governance undefined. **These are package outputs, not package defects.**

### DR-2 — Capabilities Pass standing

What the package revealed that makes this decision live: a recurring **specified vs operational** split — mechanisms exist on paper (AKB lifecycle governance, EKP structures, ownership models, `reviewed_by` fields) whose instances demonstrably do not operate. A Capabilities Pass would classify each capability as operational / specified-but-unperformed / absent / hypothetical, reusing the existing sweeps, without new discovery. The question before the board is therefore not *"is discovery complete?"* but *"is understanding operational maturity a prerequisite for Strategic Modeling?"* — a different question.

### DR-3 — Strategic Modeling authorization

Inputs that would support authorization: 17 discovered concept classes, relationship categories with polarity, evolution and lifecycle inventories, authority structures — plus a *defined modeling work queue* (OQ-1/5/6, C-1..C-4, the Conformance kind question). Constraints the board may weigh: the unresolved governance matters (incumbent, polarity standing, shadow-review authority, lifecycle governance) can be resolved before, during, or independently of modeling — that sequencing choice is itself part of this decision.

### DR-4 — Methodology Candidate standing

Applied once, successfully, within Phase I; produced the claim classification, confidence, portability, and operationality analyses used across the package. Its limitation is self-recorded: one operational instance; a second independent run named as the qualifying evidence. **Observation:** the artifact is unusually disciplined in refusing its own promotion — routing itself to the retrospective inbox rather than an adoption path, consistent with repository governance norms.

### DR-5 — Capability-layer proposal

Provenance fact the board should have: the proposal (`Engineering Activities → Knowledge Capabilities → Knowledge Concepts → Knowledge Artifacts`) originates in **excluded commentary preserved in the raw working file** — not in the discovery evidence, the synthesis, or the methodology capture. The package prevented silent inheritance. Architectural observation, offered as classification only: the proposal addresses a tension discovery genuinely surfaced — *what exists conceptually* vs *what actually operates*. Whether a capability layer is the correct mechanism for that distinction is undecided and belongs to whoever the board assigns.

### DR-6 — Held readiness assessment

Facts: it exists, is held, is not part of Phase I, has not been reviewed, was intentionally withheld; the package was designed to be reviewable without it. It answers *"are we ready for Strategic Modeling?"* while Phase I answers *"what was discovered?"* — not the same question. Whether an explicit readiness judgment is wanted before authorizing subsequent work is the board's choice (release requires explicit post-ARB commission — ARB request or PA commission).

---

## 3. Cross-cutting themes *(observed across all reviewed artifacts; cited to package text)*

1. **Knowledge is not the document.** Decisions, findings, invariants, rules, and questions carry identity and lifecycle independent of their carriers; the repository behaves as if concepts are primary and documents are projections. The strongest single finding in the package.
2. **Governance exists through human acts.** "AI discovers, drafts, analyzes; humans authorize" holds without counterexample across every reviewed artifact.
3. **The practiced system differs from the designed systems.** Three realities — AKB (designed), EKP (designed), operating practice (running) — recur throughout, and the distinction is among the most consequential Phase I discoveries.
4. **Completeness is the largest unresolved domain question.** Per-unit completeness is richly defined (document, ticket, session, capability); whole-PKS completeness is defined nowhere.

---

## 4. Governance-handling framework *(classification of ownership classes — refines the dossier's routing at finer grain; inherits its non-authority)*

### Category A — Decisions that appear to require ARB disposition before modeling

*These define the authority frame within which modeling would operate.*

- **OQ-PKS-2 (incumbent).** Modeling cannot reasonably answer "what should evolve?" without knowing whether AKB, EKP, neither, or both-as-historical-inputs is the incumbent. *Possible governance handling:* an explicit disposition among those four shapes.
- **OQ-PKS-10 (shadow-review authority).** Evidence of use exists; authority definition does not — and a modeling phase should not decide governance authority. *Possible governance handling:* an explicit standing (advisory-only · evidence source · independent review input · no authority · other).
- **OQ-PKS-11 (Qualification, one concept or two).** A governance vocabulary question of the kind boards typically own. *Possible governance handling:* issue the UL ruling, or formally delegate UL resolution — either way, ownership made explicit.

### Category B — Candidates for owned delegation (ownership decided now, answers returned later)

- **OQ-PKS-4 (relationship polarity).** *Possible handling:* a delegation statement — modeling determines whether polarity is a first-class construct and returns a recommendation; adoption stays governance work.
- **OQ-PKS-7 (three roots).** Touches territory, boundaries, and ownership; the board may prefer not to redesign territory itself. *Possible handling:* authorize the modeling work; final territorial disposition returns to the board.
- **OQ-PKS-9 (lifecycle-vocabulary mapping).** Eight vocabularies, ~60 ad-hoc statuses. *Possible handling:* modeling returns kind→vocabulary recommendations; adoption remains governance work.

### Category C — Operational governance gaps surfaced by discovery

- **OQ-PKS-3 (consistency ownership + contradiction-detection cadence).** Perhaps the most operationally important discovery: the *function* exists, the *owner* does not; both executed synchronizations were one-time. *Possible handling:* commission an ownership assignment and a defined cadence — assigned at the review even if not solved there. The specific role matters less than ending the vacuum.

### Category D — Candidates for Strategic Modeling responsibilities

OQ-PKS-1 (identity scheme) · OQ-PKS-5 (evolution-operation canon) · OQ-PKS-6 (whole-system completeness) · C-1 (Requirement) · C-2 (merge/invalidation) · C-3 (constraint vs invariant) · C-4 (completeness dimensions) · the Knowledge-System Conformance kind question. These appear aligned with modeling rather than governance; the classification leaves the board free to route otherwise. One approach the board may consider: ensure each is *owned* rather than answered prematurely.

### Coverage note *(record accuracy)*

The categories above cover **15 of the 17 entries** in the dossier's open-questions register. **OQ-PKS-8** (successor class for `developer_issues/`) and **OQ-PKS-12** (advisory transcripts disposition) are not addressed by the source material's framework; the dossier classifies both as Backlog. Their absence here is an omission in the analysis, not a re-routing. *(Addendum 2026-07-28: after this memorandum was prepared, the dossier register was extended by four item-2-origin, hypothesis-conditioned questions — dossier §4a, OQ-CM-1..4. The source material's categories do not address those either; the same omission-not-re-routing reading applies.)*

---

## 5. Illustrative review sequence *(one possible ordering; the agenda is the board's)*

1. **Disposition first (DR-1)** — without it, downstream work is moot.
2. **Governance clarifications** — OQ-2, OQ-10, OQ-11 (the highest-risk authority ambiguities).
3. **Ownership assignments** — OQ-3, OQ-7, OQ-9 (ownership is often more consequential than immediate resolution).
4. **Entry conditions** — DR-2 and DR-3 together (is modeling authorized; is a Capabilities Pass a precondition).
5. **Companion matters** — DR-4, DR-5, DR-6.

---

## 6. Closing observations

Phase I appears to have accomplished its commissioned purpose — *discover the domain from repository evidence, expose uncertainties, stop before modeling* — without crossing its authorization boundary. The remaining questions are no longer discovery questions; they are governance, modeling, and disposition questions. The package has moved the conversation from *"what exists?"* to *"what do we choose to do with what was discovered?"* — which is precisely where board involvement becomes appropriate.

One approach the board may consider, offered as observation rather than advice: resist resolving every open question at the review. The package's strongest quality is its separation of discovery, governance, and modeling; the corresponding governance outcome would not be "all questions answered" but —

> **Every unresolved question has a clearly assigned owner, a defined resolution venue, and a documented path to closure.**

---

## Constraint check

Classifies, never disposes · recommends no option (all handling stated as *possible*, all sequences as *illustrative*) · restates no dossier tables (the dossier is the record) · introduces no architecture, no modeling, no implementation · modifies no package member · coverage gap vs the dossier register stated, not silently absorbed.

---

*Traceability: PA-supplied advisory memorandum + two PA-endorsed review rounds (2026-07-28), packaged per the artifact-ingestion rule (raw material = supporting input; session log 2026-07-28) · analyzes PKS Phase I Review Package v1.0 · companion to `PKS_Phase_I_ARB_Review_Dossier.md` (the record for DR-1..DR-6 and the OQ register) · factual claims verified this session: 9× "Evidence not found", 17 concept classes, 8 lifecycle vocabularies (grep, closure-verification session), DR-5 provenance (raw-file inspection), methodology single-instance limitation (item 4 header) · reading rule lineage: analysis ≠ advice · classification ≠ disposition · consistency ≠ authority. **STOP — decision analysis only; every disposition belongs to the Architecture Review Board.***
