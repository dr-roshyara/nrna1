# PKS Phase I — ARB Review Dossier

| | |
|---|---|
| **Kind** | ARB review dossier — **administrative and governance support**, not a design artifact. Equips the Architecture Review Board to perform its review; contains **no recommendations, no assessments, no architecture**. |
| **Authority** | Generated — never authoritative without human review. The dossier informs the board; it decides nothing and advises nothing. |
| **Status** | **PREPARED — for the convening of the PKS Phase I ARB review.** |
| **Relation to the package** | NOT a member of PKS Phase I Review Package v1.0. The package remains fixed at four artifacts. |
| **Commission** | Principal Architect, 2026-07-28: *"Prepare the ARB Review Dossier... This is an administrative and governance commission. It is not a design commission."* |
| **Placement** | Project-side per ES-005.3, beside the package it supports. |

---

## 1. Executive Summary

**Purpose.** On 2026-07-27 the Principal Architect commissioned Strategic Discovery of the **Product Knowledge System (PKS) domain** — the governed body of engineering knowledge that describes, justifies, verifies, and evolves a specific product throughout its lifecycle. This is the product-side twin of the platform's knowledge-domain work, covering the domain that ES-006 explicitly scopes out of the platform.

**Scope of what is submitted.** Four artifacts (the **PKS Phase I Review Package v1.0**): an evidence discovery grounded in four full-corpus sweeps of this repository; a candidate conceptual model produced independently by a second AI model; a converged review synthesis registering where the two agree and where they collide; and a methodology capture recording the discovery method itself as a candidate engineering asset. All four carry `authority: generated`; none adopts anything.

**Evidence character.** The evidence discovery quotes and counts from this repository (claims re-verifiable by re-running the cited greps; "Evidence not found" used 9 times as a complete answer). The candidate model's evidence is *inferred from general practice, not demonstrated from this repository* — a distinction the package preserves by deliberately not merging the two documents.

**Review objective.** The board is asked to dispose of the package and to rule on the transition mechanism toward any next phase. Strategic Modeling is unauthorized until this review concludes; the Capabilities Pass (a bridge deliverable identified during review) is uncommissioned. The specific decisions are enumerated in §3.

**Procedural state.** A closure verification (executed 2026-07-28, instrumented per R-26) returned **PASS — no procedural defects**; remaining findings are editorial only. The package is procedurally ready for this review.

---

## 2. Package Map

Recommended review order follows the numbering — evidence before abstraction, reconciliation after both, method last.

| # | Artifact | Why it exists | Relation to the others |
|---|---|---|---|
| 1 | `Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md` — **Evidence Discovery** | Answers the commission's ten questions (Q0–Q9) from repository evidence alone: purposes, fundamental units (17 concepts), artifacts-as-projections, relationships with authoritative/informational polarity, evolution operations, lifecycles, ownership, authority, completeness, and 12 open questions (OQ-PKS-1..12) | The **empirical baseline**. Items 2 and 3 are read against it |
| 2 | `Strategic_DDD_Discovery_Product_Knowledge_System_Domain_Candidate_Model.md` — **Candidate Conceptual Model** | Records a second model's independently produced conceptual hypotheses: boundary statement, candidate primitives and composites, relationship candidates, evolution/lifecycle split, delegation matrix, completeness dimensions | The **hypothesis set**. Its evidence is inferred, not demonstrated; agreement with item 1 supports a candidate, divergence raises a modeling question. Derived from a raw working file whose appended review commentary (including a simulated ARB-style verdict) is excluded and preserved separately as marked supporting material |
| 3 | `Strategic_DDD_Discovery_Product_Knowledge_System_Converged_Review_Synthesis.md` — **Converged Review Synthesis** | Reconciles items 1 and 2 without merging them: records each artifact's standing, the analysis dimensions used in review (per-claim confidence; portability), and the four registered collisions C-1..C-4 | The **meeting point**. Resolves nothing; exposes the disagreements Strategic Modeling would have to resolve |
| 4 | `Strategic_Discovery_Methodology_Candidate.md` — **Methodology Candidate** | Captures the discovery method (promotion pipeline, three orthogonal tests, five claim classes, claim-level confidence) as a learning input | **Separable.** Different scope, promotion path, and evidence bar (one operational instance; routed to the retrospective inbox, not to adoption). Can be reviewed independently of items 1–3 |

**Companion records available to the board (not package members):**
- `PKS_Phase_I_Closure_Verification_Report.md` — procedural verification (PASS) + editorial findings E-1..E-7.
- `PKS_Strategic_Modeling_Readiness_Assessment.md` — **HELD**; not circulated before disposition; released upon explicit post-ARB commission (an ARB request or a Principal Architect commission).
- Raw working file `architecture/ai_architecture/documentation/what_gemini_suggested.md` — supporting material for item 2's provenance; not an ARB artifact.

---

## 3. Decision Register

Decisions before the board. For each: statement, options, dependencies, consequences. **No option is recommended.**

### DR-1 — Package disposition

> Does the board accept PKS Phase I Review Package v1.0 as a completed discovery?

| Option | Consequences |
|---|---|
| Accept | Phase I closes; the discovered baseline becomes the accepted input to whatever the board authorizes next; the 12 OQs and 4 collisions become the accepted open-question inventory |
| Accept with findings | As above, plus board findings recorded against specific artifacts; editorial items E-1..E-7 may be folded here |
| Request changes / further evidence | Package returns to the discovery track; Phase I remains open; all downstream decisions (DR-2..DR-5) are moot until resubmission |

*Dependencies: none — this is the root decision.*

### DR-2 — Capabilities Pass (transition mechanism)

> Should a Capabilities Pass be commissioned as a bridge deliverable between Phase I Discovery and Strategic Modeling — and with what standing?

Identified during review (synthesis §2): would classify each candidate capability as operational / specified-but-unperformed / absent / hypothetical, reusing the four existing sweeps.

| Option | Consequences |
|---|---|
| Mandatory | Strategic Modeling entry is gated on the Pass's completion; a commissioning act with scope is required |
| Recommended | The Pass is commissioned but Strategic Modeling entry is not gated on it |
| Unnecessary | Strategic Modeling (if authorized) proceeds directly from the package as-is |

*Dependencies: meaningful only if DR-1 ≠ request-changes. Interacts with DR-3.*

### DR-3 — Strategic Modeling authorization

> Is Strategic Modeling authorized, and on what condition?

| Option | Consequences |
|---|---|
| Authorize now | Modeling proceeds from the accepted package; open questions routed to it (§4) become its work items |
| Authorize conditionally | Entry gated on named conditions (e.g., DR-2's deliverable, or specific OQ rulings from §4) |
| Defer | Phase transition postponed; the package stands as an accepted record; no further PKS work occurs without a new commission |

*Dependencies: DR-1 (acceptance), DR-2 (if the Pass is made mandatory).*

### DR-4 — Methodology Candidate standing (item 4)

> Does the board confirm the methodology capture's recorded standing?

| Option | Consequences |
|---|---|
| Confirm as recorded | Stays in the retrospective inbox; a second independent discovery run remains the qualifying evidence for candidacy |
| Early promotion | Would require an explicit exception to the multi-context evidence bar (the register carries an R-39 single-context-exception precedent); a recorded exception act is needed |
| Reject the capture | The record is re-labeled per the corpus's demote-with-record discipline; the discovery artifacts are unaffected |

*Dependencies: none — separable from DR-1..DR-3.*

### DR-5 — Capability-layer proposal from unmarked commentary

> Should the capability-layer proposal (`Engineering Activities → Knowledge Capabilities → Knowledge Concepts → Knowledge Artifacts`), which appears only in the raw working file's excluded commentary, be taken up?

| Option | Consequences |
|---|---|
| Take up as its own agenda item | The proposal enters governance explicitly, with attribution to its actual provenance |
| Decline | The proposal remains supporting material; it may resurface only through a future explicit commission |

*Dependencies: none, though its subject matter overlaps DR-2.*

### DR-6 — Post-disposition readiness assessment

> After disposition, does the board wish to receive the held readiness assessment?

| Option | Consequences |
|---|---|
| Request it | `PKS_Strategic_Modeling_Readiness_Assessment.md` (HELD) is released to the board as a commissioned input |
| Do not request it | It remains a held record; the board forms its readiness judgment from the package alone. The Principal Architect may still commission it post-disposition as a next-phase input — the hold binds sequencing, not the requester |

*Dependencies: DR-1. Recorded here because the artifact exists; whether to consult it is itself the board's choice.*

---

## 4. Open Questions Register

All open questions in the package, with origin, evidence state, decision owner, and routing. Routing follows the package's own text where it speaks; where it does not, the classification is administrative (by the question's shape), not a recommendation.

| # | Question | Origin | Evidence state | Owner | Routing |
|---|---|---|---|---|---|
| OQ-PKS-1 | Identity scheme for fundamental knowledge units — global or scoped? | Item 1, Q9 | Both practiced; ids collide across six F-spaces, four D-spaces, five ADR-spaces | Modeling (design choice) | **Strategic Modeling** |
| OQ-PKS-2 | Which designed system (AKB, EKP) — if either — is the incumbent to evolve? | Item 1, Q9 | ES-006 records EKP "disposition PENDING ARB"; AKB never formally superseded | ARB (a pending disposition already names it) | **ARB** |
| OQ-PKS-3 | Who owns cross-artifact consistency; on what cadence is contradiction detection run? | Item 1, Q9 | "Evidence not found" — no role named; both syncs were one-time | ARB/DA (staffing + ownership) | **ARB** |
| OQ-PKS-4 | Is the authoritative/informational relationship polarity a rule? | Item 1, Q9 | No counterexample, no statement — "adopting it would be a decision, not a discovery" | ARB (rule adoption) | **ARB** |
| OQ-PKS-5 | Canonical set of evolution operations; are merge/expiry deliberately absent? | Item 1, Q9 | 14 operations reconstructed from precedent; absences have no recorded rationale | Modeling (canonical-set definition) | **Strategic Modeling** |
| OQ-PKS-6 | What does whole-PKS completeness/readiness mean? | Item 1, Q8/Q9 | No criterion exists; candidate ingredients exist; item 2 §6 offers five candidate dimensions (collision C-4) | Modeling ("selecting among them is design") | **Strategic Modeling** |
| OQ-PKS-7 | The three roots (`docs/` · `architecture/` · `engineering/` bindings) — one corpus or three? | Item 1, Q9 | No unified index; ES-005.1 predates several inhabitants | ARB (territory/namespace touches EM-001/ES-005) | **ARB** — with explicit delegation into modeling as an available act |
| OQ-PKS-8 | Successor class for `developer_issues/` (deleted, uncommitted, undecided) | Item 1, Q9 | No decision recorded | DA (housekeeping disposition) | **Backlog** |
| OQ-PKS-9 | Which lifecycle vocabulary (of eight) governs which object kind? | Item 1, Q9 | Nothing maps kind → vocabulary; ~60 ad-hoc status tokens besides | ARB or delegated | **ARB** — with explicit delegation into modeling as an available act |
| OQ-PKS-10 | What authority do second-model (shadow) reviews carry? | Item 1, Q9 | `-deepseek` corpus + this package's item 2 exist with no recorded standing | ARB (authority grant) | **ARB** |
| OQ-PKS-11 | Is "Qualification" one concept or two? | Item 1, Q9 (corpus's own UL audit) | Dual use documented; "candidate for a future UL ruling" | ARB (UL ruling per ER-06) | **ARB** |
| OQ-PKS-12 | Ungoverned advisory transcripts in governed folders — inputs or debris? | Item 1, Q9 | One already has a DA disposition; others none | DA (disposition) | **Backlog** |
| C-1 | Requirement: missing concept / represented otherwise / outside boundary? | Synthesis §4 | "Evidence not found" vs candidate-model node; explicit trichotomy | Modeling | **Strategic Modeling** |
| C-2 | Merge & Invalidation: real evolution operations or imported abstractions? | Synthesis §4 | Zero repository precedent vs general-practice plausibility | Modeling | **Strategic Modeling** |
| C-3 | Constraint ≠ Invariant: real distinction or imported? | Synthesis §4 | Corpus does not separate them; candidate model does | Modeling | **Strategic Modeling** |
| C-4 | Do item 2's five completeness dimensions answer Q8? | Synthesis §4 | Candidate answer; discriminative power indicated once | Modeling (validation against evidence) | **Strategic Modeling** |
| — | Knowledge-System Conformance: first-class concept / derived assessment / emergent property? | Synthesis §4; item 4 §5 | All three shapes explain the evidence; kind deliberately left open | Modeling | **Strategic Modeling** |

*Register shape, stated without inference: no entry routes to Future Discovery — every entry awaits a decision, not evidence.*

### §4a — Addendum (2026-07-28, ARB review round 2): item 2's own open questions — omitted above, added here

Cross-reading during the Item-2 review round surfaced that this register omitted the four "Open Discovery Questions" item 2 itself carries (its §7) — an erratum in this dossier's original "all open questions in the package" claim, corrected here additively; the original rows above are unchanged. All four are **hypothesis-conditioned**: they presuppose item-2 candidate constructs (`Observation`/`Verdict` ingestion; an `Approved` lifecycle stage) and stand or fall with those hypotheses. The handles OQ-CM-1..4 are register-local labels only, not an identity-scheme adoption (that question is OQ-PKS-1).

| # | Question | Origin | Evidence state | Owner | Routing |
|---|---|---|---|---|---|
| OQ-CM-1 | Granularity of observation captures — raw outputs ingested as `Observation` concepts, or aggregated into `Verdict`s before ingestion? | Item 2, §7 | Hypothesis-conditioned (presupposes the Observation/Verdict candidates) | Modeling (conditional) | **Strategic Modeling**, if the underlying candidates survive validation |
| OQ-CM-2 | Cross-product knowledge inheritance mechanics — how do global standards propagate to a local PKS with local overrides? | Item 2, §7 | Hypothesis-conditioned; additionally touches the EKA↔product boundary already governed platform-side | Modeling (conditional), boundary aspects governance-adjacent | **Strategic Modeling** for mechanics; boundary aspects a candidate for ARB delegation framing |
| OQ-CM-3 | Automated invalidation boundaries — what failure thresholds trigger review of an `Approved` decision? | Item 2, §7 | Hypothesis-conditioned; coupled to collision C-2 (Invalidation has no repository precedent) | Modeling (conditional) | **Strategic Modeling**, coupled to the C-2 disposition |
| OQ-CM-4 | Governance overhead vs velocity — minimal metadata for lightweight `Draft` creation? | Item 2, §7 | Hypothesis-conditioned; evidence side notes the corpus's own designed-system stall as relevant background | Modeling (conditional) | **Strategic Modeling** |

The §4 shape statement holds for these additions: each awaits a decision (hypothesis validation in modeling), not further evidence-gathering.

**Also recorded from review round 2 (review findings, not register entries):** the Item-2 reviewer's cross-reference surfaced two **candidate collision-register additions** beyond C-1..C-4 — (a) *lifecycle consolidation*: 8 observed vocabularies + ~60 ad-hoc tokens vs item 2's single proposed lifecycle; (b) *concept abstraction*: 17 observed concepts vs item 2's 7 primitives + 3 composites. The relayed meta-review classifies both as expected conceptual-modeling abstraction requiring validation rather than defects. Whether they join the synthesis's collision register (which would require a versioned re-issue of a submitted artifact) or route directly into Strategic Modeling's work queue is a board disposition; they are recorded here as review findings only.

---

## 5. Review Checklist

Questions only. One pass per artifact, then the cross-cutting block.

**Item 1 — Evidence Discovery**
- [ ] Does every load-bearing claim carry an epistemic label (Observed/Measured/Derived/Interpreted), and does the label match the claim's actual grounding?
- [ ] Spot-check reproducibility: pick one Measured claim and re-run its cited count — does it reproduce?
- [ ] Are the 9 "Evidence not found" answers genuine absences rather than unsearched areas?
- [ ] Does the constraint check hold — no technology, no bounded contexts, no relocations, no governance created?
- [ ] Are the 12 open questions actually unanswerable from the evidence presented?

**Item 2 — Candidate Conceptual Model**
- [ ] Is the evidence-class labeling ("evidence inferred, not demonstrated") honored throughout the body, not just declared in the header?
- [ ] Is every concept presented as candidate rather than fact?
- [ ] Does the provenance note accurately describe what was excluded from the raw working file, and is the raw file's banner consistent with it?
- [ ] Does anything in the body silently assume a decision the corpus has not made?

**Item 3 — Converged Review Synthesis**
- [ ] Does it add any claim not present in items 1–2 or the recorded review conversation?
- [ ] Are the four collisions genuinely unresolved in the underlying texts?
- [ ] Are §2 and §6 descriptive of the review, or do they steer the board?
- [ ] Is the confidence/portability table consistent with item 4's claim classes?

**Item 4 — Methodology Candidate**
- [ ] Is the single-instance evidence caveat appropriate, and is the qualifying-evidence bar (a second independent run) correctly stated?
- [ ] Does the capture stay method-only, free of PKS domain content?

**Cross-cutting**
- [ ] Does any artifact assert an approval, adoption, or ratification that has not occurred?
- [ ] Can a reader always tell demonstrated findings from inferred ones?
- [ ] Is the separation between the two discovery artifacts (unmerged by design) intact?
- [ ] Are the companion records (verification report; held readiness assessment) correctly excluded from the package?
- [ ] For DR-2: is the claim that the four sweeps are reusable for a Capabilities Pass plausible on the sweeps' described scope?
- [ ] Is anything in this dossier itself more than administrative?

---

## 6. Governance Validation

Confirmed for this dossier commission:

- **No architecture introduced** — every concept named above is cited to package text; none originates here.
- **No modeling performed** — no bounded contexts, context maps, aggregates, or relationships defined.
- **No implementation proposed.**
- **No package member modified** — the four package artifacts were not touched by this commission; the only artifact amendment this commission made is the readiness assessment's status change to HELD (a companion record, per the same PA ruling that commissioned this dossier).
- **No recommendation issued** — §3 presents options and consequences symmetrically; §4's routing is administrative classification citing the package's own text; §5 contains questions only.
- **The dossier is administrative, not architectural.**

---

*Traceability: PA commission 2026-07-28 (ARB support; administrative only) · supports the review of PKS Phase I Review Package v1.0 (items 1–4, listed in §2) · draws on `PKS_Phase_I_Closure_Verification_Report.md` · readiness assessment HELD post-ARB per the same PA ruling · session record `.claude/sessions/2026-07-28.md`. **STOP — dossier only; the review, its agenda, and every decision in §3 belong to the Architecture Review Board.***
