# PKS Phase I — Converged Review Synthesis (with Confidence & Portability Analysis)

| | |
|---|---|
| **Kind** | Review synthesis — reconciles the two Phase-1 discovery artifacts and registers the collisions between them. **Adopts nothing; creates no governance** (ES-001.2). Exposes disagreements; resolves none. |
| **Authority** | Generated — never authoritative without human review. **PA-endorsed in review conversation (2026-07-27), which is NOT an ARB act.** |
| **Status** | **ACCEPTED (ARB, 2026-07-28: DR-1 — `PKS_Phase_I_ARB_Rulings.md`).** Collisions C-1..C-4 remain unresolved **by design** — they are Strategic Modeling's work queue, not defects cured by acceptance. *(Supersedes SUBMITTED FOR ARB REVIEW.)* |
| **Part of** | **PKS Phase I Review Package v1.0**, item (3) of 4. Companions: (1) `Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md` — evidence discovery · (2) `Strategic_DDD_Discovery_Product_Knowledge_System_Domain_Candidate_Model.md` — candidate conceptual model · (4) `Strategic_Discovery_Methodology_Candidate.md` — engineering methodology candidate. |
| **Provenance** | Extracted 2026-07-28 from review-round prose recorded in `.claude/sessions/2026-07-27.md` (entries: "PKS Discovery — review round", its closing round, and "PHASE 1 CLOSED"). **The extraction was a packaging act only — no claim was added, strengthened, or softened in the move.** Reason: as session-log prose the synthesis was not independently reviewable, leaving the review package with an unreviewable member. |
| **Placement** | Project-side per ES-005.3 — inseparable from PublicDigit evidence; sits with its three package companions under `docs/implementation/`. |

**Scope:** This document reconciles two discovery artifacts and registers their contradictions. It contains no architecture, no bounded contexts, no strategic model, and no resolution of the collisions it lists.

---

## 1. The two artifacts and their distinct standing

| | Evidence Discovery (item 1) | Candidate Conceptual Model (item 2) |
|---|---|---|
| **Role in the package** | **Empirical baseline / falsification dataset** — what this repository demonstrably does | **Candidate abstractions + hypotheses** — concepts that might explain or extend it |
| **Evidence basis** | Quoted and counted from this repository; four full-corpus sweeps executed in-session; "Evidence not found" used 9× as a complete answer | Evidence-oriented *structure*, with evidence **inferred from general practice rather than demonstrated from this repository** |
| **Consequence** | Claims are re-verifiable by re-running the cited greps | Claims require validation against the baseline before promotion |

*Wording note on record:* item (2)'s evidence class was deliberately softened during review from an evidence claim to "presents evidence-oriented structure; evidence inferred, not demonstrated." That softening is part of the synthesis, not a later correction to it.

**The two are deliberately NOT merged.** Separation preserves independent evidence, independent reasoning, and independent strengths; this document is where they meet. A single blended report would have destroyed the ability to distinguish demonstrated findings from inferred ones.

---

## 2. Review Context — Observed Phase Relationship

*Descriptive only. This section reports what emerged during the review conversation. It does not derive, recommend, or prioritize a transition.*

During review, the possibility of a **Capabilities Pass** was identified as a potential bridge between Discovery and Strategic Modeling — described as classifying each candidate capability as **operational / specified-but-unperformed / absent / hypothetical**, reusing the four existing sweeps, and (as the discussion refined it) classifying **operational maturity rather than nouns**. It **has not been commissioned and is therefore not part of Phase I.** Whether such a bridge is required remains an ARB decision.

The review also positioned **Strategic Modeling** as the phase in which the §4 collisions would be resolved and surviving candidates promoted to ubiquitous language / bounded contexts. Strategic Modeling is **unauthorized** pending ARB acceptance of Phase I.

```text
Evidence Discovery  ─┐
                     ├─►  [Capabilities Pass — identified in review, UNCOMMISSIONED]  ─►  [Strategic Modeling — UNAUTHORIZED]
Candidate Model     ─┘
```

*The diagram depicts the relationship as discussed during review; it is not an adopted process.*

---

## 3. Analysis dimensions used in this review

### 3.1 Per-claim confidence

Confidence attaches to **claims, never to concepts** — `Decision` has no confidence; *"the document is not the fundamental unit"* does. Compound statements are split before rating so a weak inference cannot borrow a strong observation's authority. In-corpus precedent: EPIC-004C candidate confidence (High/Medium, as ARB decision input); Round 32B ("DISCOVERED — HIGH confidence / CANDIDATE — MEDIUM-LOW").

### 3.2 Portability filter

> Could another product instantiate this concept **without this repository's conventions**?

Generalized from ES-005.3 (placement) to concepts. Guards against a local convention being promoted to a domain concept. **Recorded in review as mandatory for Strategic Modeling** — essential-to-any-PKS to be separated from incidental-to-this-repo. (Reported as the review's position; this document does not itself impose it.)

### 3.3 Combined assessment of the strongest candidates

The three tests are **orthogonal** — a claim can be high-confidence, portable, and describe something *absent*.

| Candidate claim | Class | Confidence | Portable? | Operational status |
|---|---|---|---|---|
| The fundamental unit of knowledge is **sub-document** (23 ADR-T in 1 file; 14 D-nn in 1 log; ~18 rules inside the process doc) | Measured → Derived | **High** — re-count returns the same | **Yes** — any product with registers/logs exhibits it | **Operational** (ids are cited independently today) |
| **Knowledge-System Conformance** — "the degree to which the operational knowledge system conforms to its declared governance, lifecycle, and structural rules" | **Synthesized** (integrates AKB + EKP + platform enforcement-asymmetry across two domains) | **Medium-High** — 3 independent instances meet the evidence standard; the *abstraction* carries the residual risk | **Yes** — the failure mode (rules written, instances don't follow) is not repo-specific | **Absent** — no mechanism observes conformance anywhere |
| **Relationship polarity** — a relationship is authoritative *iff* establishing, changing, or removing it requires an authorized (per-item, recorded) governance decision | Derived | **Medium-High** — no counterexample found, but also **no statement** in the corpus | **Yes** — states a governance property, not a convention | **Operational-but-unstated** (practiced without being written) |
| Whole-system **completeness/readiness** is undefined | Measured (negative) | **High** — checked in all four sweeps | **Yes** | **Absent** — "operates nowhere"; would be the domain's first genuinely new concept |
| "Merge has no repository evidence" | Measured | **High** | — | **Absent** |
| "…therefore merge is unnecessary to the domain" | **Interpreted** | **Low** | — | — |

The last two rows are the worked example of why compound claims are split: identical subject, opposite confidence.

---

## 4. Registered collisions — exposed, NOT resolved

Genuine disagreements between the two artifacts. Each is a modeling question for Strategic Modeling or an ARB decision; none is settled here.

| # | Collision | Evidence-discovery position | Candidate-model position | Why it is unresolved |
|---|---|---|---|---|
| C-1 | **Requirement** as a first-class concept | **"Evidence not found"** as an identified unit — no `REQ-nn` scheme exists anywhere product-side; operating forms are invariant, acceptance criterion, conformance test | Presents `Requirement/Goal` as a node with an authoritative `implements` relationship | An explicit modeling **trichotomy**: missing concept / represented via invariants+criteria+conformance-tests / outside the boundary. Choosing is design |
| C-2 | **Merge** and **Invalidation** as evolution operations | Checked for, **not found** — the corpus splits and consolidates text but never merged two *identified* objects under a rule; no automatic-invalidation mechanism exists | Lists both as discovered evolution patterns | Zero repository precedent vs. general-practice plausibility. The absence has no recorded rationale (OQ-PKS-5) |
| C-3 | **Constraint ≠ Invariant** | The corpus **does not separate them**; "Constraints that bite" is a hint list pointing at rules and ADRs | Presents Constraint and Invariant as distinct primitive concepts | The distinction may be real and merely unexpressed here, or an imported abstraction. Undecidable from current evidence |
| C-4 | **Whole-system completeness** (Q8) | No criterion exists; per-unit completeness is richly defined, whole-system completeness "operates nowhere" | Proposes five qualitative dimensions (Domain Coverage · Decision Traceability · Contract Completeness · Lifecycle Hygiene · Verifiability) | The five dimensions are a **candidate answer** to the open question, requiring validation against evidence. Discriminative power already indicated: EKP lifecycle-hygiene would score ≈0 |

**Additional open item, kind deliberately left undecided:** Knowledge-System Conformance may be modeled as **(A)** a first-class concept, **(B)** a derived assessment producing a Verdict, or **(C)** an emergent per-object property aggregating to system level. All three explain the evidence. Discovery records the options and stops; Strategic Modeling chooses.

---

## 5. Gates — identified, none passed here

| Gate | State |
|---|---|
| Candidate model needs class/status header + placement decision to be admissible | **DISCHARGED 2026-07-28** — packaged as item (2); review commentary appended to the raw source (incl. a simulated ARB-style verdict) excluded from the governed artifact and left as marked supporting material in the raw working file |
| Capabilities Pass | **UNCOMMISSIONED.** Recorded PA decision 2026-07-28: **ARB review comes first**, so the Pass cannot reshape the package the ARB is judging. Referred to the ARB as its own agenda item (mandatory / recommended / unnecessary) |
| This synthesis becoming binding | Requires an explicit ARB/DA decision. PA endorsement in review is not that act |
| Strategic Modeling | **UNAUTHORIZED** until the ARB reviews Phase I |

---

## 6. Potential ARB agenda items identified during review

*Descriptive only. These items surfaced during the review conversation. This document neither sets the agenda nor expresses a preference among dispositions — all of that is the ARB's.*

1. Disposition of the Phase I package (4 items).
2. Whether a **Capabilities Pass** should be commissioned as a bridge deliverable between Phase I Discovery and Strategic Modeling, and if so with what standing (mandatory / recommended / unnecessary). Raised during review as a transition-mechanism question distinct from the disposition of the discovery itself.
3. Whether the capability-layer proposal that appears in the raw working file's unmarked commentary (`Engineering Activities → Knowledge Capabilities → Knowledge Concepts → Knowledge Artifacts`) should be considered. Noted during review as something that would need to be raised explicitly rather than inherited silently from uncredited text.

---

*Traceability: extracted 2026-07-28 from review-round prose in `.claude/sessions/2026-07-27.md` (packaging act only, no claim altered) · reconciles `Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md` and `Strategic_DDD_Discovery_Product_Knowledge_System_Domain_Candidate_Model.md` · methodology lineage: `Strategic_Discovery_Methodology_Candidate.md` (R-36 epistemic labels · EPIC-004C confidence · ES-005.3 portability · ES-006.1 promotion ladder) · open questions it feeds: OQ-PKS-4 (polarity as rule), OQ-PKS-5 (canonical evolution operations), OQ-PKS-6 (whole-system completeness) · packaging decisions recorded in `.claude/sessions/2026-07-28.md`. **STOP — submitted for ARB review; adopts nothing, resolves no collision.***
