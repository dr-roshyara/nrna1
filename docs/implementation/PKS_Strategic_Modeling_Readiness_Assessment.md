# PKS — Strategic Modeling Readiness Assessment

| | |
|---|---|
| **Kind** | Readiness assessment — evaluates whether Strategic Modeling is *justified*, *not* whether it would succeed, and performs none of it. No bounded contexts, no context maps, no aggregates, no C4, no implementation, no new discovery, no KnowledgeOS expansion. |
| **Authority** | Generated — never authoritative without human review. **This assessment recommends; the ARB decides** the phase transition. |
| **Status** | **HELD.** Not circulated before ARB disposition. May be released upon **explicit post-ARB commission** — an ARB request or a Principal Architect commission. Rationale: whether the project is ready for Strategic Modeling lies within the ARB's own mandate; a pre-ARB readiness assessment borders on advising the board on a question that is its to answer. The sequencing is what matters; the specific requester is not — but the commission must be explicit and post-disposition. *(Supersedes the initial "SUBMITTED — decision input" status, a sequencing error, and the first HELD wording, which made the ARB the exclusive releaser — both corrections PA-directed 2026-07-28; the assessment body is unchanged throughout.)* **DR-6 (ARB, 2026-07-28): not requested at disposition; the PA-commission release path remains open.** Adopts nothing. |
| **Relation to the package** | **NOT a member of PKS Phase I Review Package v1.0** — the package remains fixed at four artifacts per the recorded ruling that post-review work must not reshape what the ARB judges. This document is a companion decision-input, same standing as the Closure Verification Report. |
| **Commission** | Principal Architect, 2026-07-28: *"Assess the readiness for Strategic Modeling. Do not perform Strategic Modeling. Assess only whether it is justified."* |
| **Evidence rule** | Every claim below cites the Phase I package (items 1–4) or verification records produced this session. No new corpus evidence was gathered — gathering it would be discovery, which this commission forbids. |
| **Placement** | Project-side per ES-005.3, beside the package it assesses. |

**Scope:** Readiness evaluation only. Where this document names concept groupings, it reports groupings *already present in the package's own text*, cited; it proposes none.

---

## 1. Ubiquitous Language readiness

**Stable and operating** *(evidence: item 1, §1.2)*: 17 knowledge concepts (K-1..K-17) each carry an identity scheme, live instances, and independent citation — Decision, Rule, Invariant, Ruling, Finding, Question, Term, Event contract, Model element, Observation, Risk, Candidate, Work item, Guide step, Verdict, Exception record, Charter grant. These names are not proposals; the corpus already speaks them. The evolution-operation vocabulary (supersede, version, split, consolidate, refine, promote, demote, reclassify, retire, archive, migrate, generalize, derive — item 1, Q4) is likewise reconstructed from precedent, not invented.

**Vocabulary risks, all already registered in the package** *(nothing new introduced here)*:

| Risk | Source | Character |
|---|---|---|
| V-1 **Lifecycle language is fragmented**: 8 declared lifecycle vocabularies + ~60 ad-hoc status tokens, with no mapping of object kind → vocabulary | item 1, Q5 / OQ-PKS-9 | The largest UL risk. A strategic model built before this mapping decision inherits the fragmentation |
| V-2 **"Qualification" carries two meanings** (verification instrument vs promotion-ladder stage) — flagged by the corpus's own UL audit | item 1, OQ-PKS-11 | Needs a UL ruling, not more evidence |
| V-3 **Constraint ≠ Invariant unseparated** in the corpus; presented as distinct primitives in the candidate model | synthesis, C-3 | Undecidable from current evidence — a modeling decision |
| V-4 **"Requirement" has no name because it may have no existence**: no `REQ-nn` scheme anywhere; candidate model presents it as a node | synthesis, C-1 | An explicit trichotomy awaits Strategic Modeling |
| V-5 **"Knowledge-System Conformance" is newly coined and Synthesized-class** — highest abstraction distance in the package; its *kind* (concept / assessment / emergent property) deliberately undecided | item 4 §3; synthesis §3.3, §4 | The name is stable; what it *names* is not yet |
| V-6 Artifact short-name case/form drift across package documents | Closure Verification §7, E-5 | Cosmetic |

**Assessment:** the *concept* vocabulary is stable enough to model with; the *lifecycle/status* vocabulary is not yet, and the package knows it. Critically, every collision is **registered, named, and routed** — these are known unknowns, which is the healthiest state a vocabulary can be in short of resolution. No renaming performed here.

---

## 2. Domain knowledge sufficiency

| Question | Answered? | Evidence |
|---|---|---|
| **What exists** | ✅ Measured, not estimated: 17 concepts, ~40 artifact kinds in 9 families, 14 evolution operations, 8 lifecycle vocabularies, a relationship vocabulary with polarity | item 1, Q1–Q5 |
| **What is important** | ✅ Nine evidenced purposes (P-1..P-9), including two the classical taxonomies don't name (AI-collaborator memory; task-scoped context assembly); the executive finding identifies which of three coexisting knowledge systems actually operates | item 1, Q0, §0 |
| **What is merely implementation** | ✅ The artifact/concept separation is the package's central discovery: "the document is a serialization; the knowledge concepts are the domain" — with the decisive concept/file ratio evidence | item 1, §1.3, Q2 |
| **What is evidence vs hypothesis** | ✅ Enforced by construction: five claim classes (Observed/Measured/Derived/Synthesized/Interpreted), per-claim confidence, compound-claim splitting, and the two artifacts kept deliberately unmerged so demonstrated and inferred claims cannot blend | item 4; synthesis §1, §3 |

**Remaining strategic uncertainty is fully catalogued**: 12 open questions (OQ-PKS-1..12), 4 registered collisions (C-1..C-4), 1 open kind-question (Conformance A/B/C). **Verified property of the OQ register (checked this session): all 12 are decision-shaped — an identity-scheme choice, an incumbent choice, an ownership assignment, a rule adoption, a completeness definition — none is closable by further reading of the repository.** This is the saturation signal: discovery has converted every remaining unknown into a decision.

**Assessment: sufficient.** Another discovery round would re-find the same facts and re-ask the same questions.

---

## 3. Boundary readiness *(no boundaries proposed)*

**Seams already visible in the package's own text:**

- **The authority seam** — the corpus rigidly separates governance-bearing material from navigation/teaching material: "nothing may cite a view as authority"; guides and views are explicitly non-authoritative projections (item 1, Q2/Q3). This seam has held stable across ~50 recorded rounds of practice.
- **The polarity seam** — authoritative vs informational relationships behave differently under change (human decision event vs author's discretion), with no counterexample found (item 1, Q3). A structural distinction this consistent is the kind of thing context boundaries later crystallize around.
- **The product/platform seam** — already *decided*, not merely visible: EM-001 namespace ruling, ES-005 three-concern separation, and the candidate model's outer boundary (Engineering Knowledge / PKS / External Systems) agrees with it (item 2, Boundary Statement).
- **Evidence-class clusters** — the discovery's own groupings (decision carriers; verification/evidence artifacts; teaching artifacts; work/process artifacts; runtime knowledge) recur across both the concept inventory and the artifact families (item 1, §1.2, §2.1).

**What is not yet stable enough for context mapping:**

| Gap | Source | Why it matters for boundaries |
|---|---|---|
| B-1 **The three-roots question**: is the product knowledge corpus one corpus or three (`docs/` + `architecture/` + `engineering/` bindings)? | OQ-PKS-7 | A context map cannot be drawn over territory whose count is undecided |
| B-2 **The incumbent question**: AKB, EKP, neither, or the unnamed practice-based system — which is being evolved? | OQ-PKS-2 | Determines what Strategic Modeling models *against*. Modeling the designed systems while the practice-based one operates would repeat the exact failure mode the package names (Knowledge-System Conformance) |
| B-3 **Noun-clusters vs capability-clusters**: the review recorded that capability groupings are the more likely boundary candidates than concept nouns — and the deliverable that would classify capabilities (the Capabilities Pass) is uncommissioned | synthesis §2, §6 | Boundary readiness partially depends on a deliverable whose commissioning is itself an open ARB agenda item |

**Assessment: seams visible and stable; three named ambiguities genuinely affect where lines would fall.** None requires discovery — B-1 and B-2 are ARB decisions; B-3 is the pending Capabilities-Pass disposition.

---

## 4. Relationship readiness *(no relationships redesigned)*

**Sufficient:** the package supplies an evidenced relationship vocabulary with semantics and precedent per entry (8 authoritative, 6 informational — item 1, Q3), a polarity rule candidate with zero counterexamples, and 14 evolution operations governing how related knowledge changes. The candidate model's relationship set (justifies / implements / verifies / violates / supersedes / constrains) overlaps the evidence set enough to reconcile rather than collide — except where noted.

**Missing knowledge, all named:**

- **R-gap-1:** whether the polarity is a *rule* is undecided (OQ-PKS-4) — adopting it is a decision that would become a load-bearing constraint on any future context map's relationship types.
- **R-gap-2:** the candidate `implements` relationship terminates on `Requirement`, whose existence is collision C-1 — this relationship's fate is coupled to that modeling decision.
- **R-gap-3:** cross-root relationships (how the three roots relate — OQ-PKS-7 again) are the one genuinely under-evidenced relationship area; everything intra-root is well covered.
- **Known asymmetry, not a gap:** the machine-readable inversion (rich relationships exist only as prose; typed fields are barely used — item 1, §3.2) is *information for* modeling, not an obstacle to it.

**Assessment: sufficient for future context mapping once B-1/B-2 are decided.**

---

## 5. Strategic risks

Ordered by potential to invalidate Strategic Modeling:

| # | Risk | Grounding | Mitigation path |
|---|---|---|---|
| SR-1 | **Modeling the wrong incumbent.** The package's central finding is that the *practice-based* system operates while both *designed* systems stalled at rules-written/instances-don't-follow. A strategic model that formalizes a third designed system without a conformance mechanism walks the identical path — the package literally names this failure mode | item 1 §0, Q7; OQ-PKS-2 | ARB decides the incumbent before modeling begins |
| SR-2 | **Lifecycle-vocabulary fragmentation imported into the model** (V-1) | item 1, Q5; OQ-PKS-9 | ARB maps kind→vocabulary, or explicitly delegates that mapping *into* Strategic Modeling as one of its tasks |
| SR-3 | **Untested portability filter.** Recorded in review as mandatory for Strategic Modeling but never yet applied; by the corpus's own Methodological Fitness Rule, "a criterion that never rejects anything is presumed ceremonial." If it fails to discriminate, this-repo conventions leak into the domain model as concepts | synthesis §3.2; item 4 §7 | First applications during modeling must be reviewed for whether the filter actually rejected anything |
| SR-4 | **Synthesized-class abstraction risk.** Knowledge-System Conformance integrates evidence across two domains through the largest inference distance in the package, under a methodology with exactly one operational instance | item 4 §3, §7; synthesis §3.3 | The A/B/C kind-decision should be treated as falsifiable, with the evidence trail kept live |
| SR-5 | **Ownership void.** Nobody owns cross-artifact consistency (OQ-PKS-3, "evidence not found"); a strategic model without a custodian degrades exactly as the AKB and EKP did | item 1, Q6 | Staffing/ownership decision at or before model acceptance |
| SR-6 | **Single-corpus evidence base.** All portability claims are untested against a second product by construction | item 4 §7 | Acknowledged limitation; not resolvable now, should stay visible in the model's headers |

None of these is evidence-shaped. All are decision- or discipline-shaped.

---

## 6. DDD readiness ratings

| Dimension | Rating | Why |
|---|---|---|
| **Ubiquitous Language** | **MODERATE** | 17 concept names stable and operating; evolution-operation vocabulary reconstructed from precedent. Held back by V-1 (8 lifecycle vocabularies, no kind→vocabulary mapping) and V-2..V-5 — all registered, none latent |
| **Domain Understanding** | **STRONG** | Measured inventory; purposes evidenced; the artifact/concept and designed/practiced distinctions are deep structural findings; every remaining unknown converted to a named decision |
| **Boundary Clarity** | **MODERATE** | Authority, polarity, and product/platform seams visible and historically stable; B-1 (three roots), B-2 (incumbent), B-3 (capability clustering) unresolved — the first two are single ARB decisions |
| **Relationship Clarity** | **STRONG** | Evidenced two-polarity vocabulary with per-entry precedent; gaps are two named decisions and one under-evidenced cross-root area, not missing understanding |
| **Evidence Sufficiency** | **STRONG (baseline) / MODERATE (abstractions)** | The empirical baseline is re-verifiable by construction; the candidate abstractions are honest hypotheses whose evidence is inferred, and the package never lets the two blend |
| **Strategic Risk** | **MODERATE** | Six named risks; the top two (wrong incumbent, vocabulary fragmentation) are each closable by a single ARB decision; none requires discovery |

---

## 7. Recommendation

**Ready after specific ARB actions.**

*Not* "Ready for Strategic Modeling" unqualified, and *not* "Additional Discovery required." The justification rests entirely on package evidence:

**Why not more discovery — saturation is demonstrable, not asserted.** Four full-corpus sweeps executed; "Evidence not found" returned 9 times as a complete answer; a second, independent model produced the candidate abstraction set; the collisions between the two are registered, not blended. Decisively: **all 12 open questions in the package's own register are decision-shaped** — verified this session — so no further reading of the repository can close any of them. A fifth sweep would re-find the same 17 concepts and re-ask the same 12 questions.

**Why not immediate modeling.** Strategic Modeling would begin with its frame undecided: *what* it models (B-2, the incumbent), *over what territory* (B-1, the three roots), and *with which grouping input* (B-3, the Capabilities-Pass disposition). Beginning anyway would force the modeler to decide these implicitly — precisely the "ticket silently becomes architecture" failure the standing discipline forbids.

**The specific ARB actions** (each already on, or routable to, the recorded agenda — nothing new introduced):

1. **Disposition of the Phase I package** (the existing review).
2. **Capabilities-Pass ruling** — mandatory / recommended / unnecessary (the existing transition-mechanism agenda item; resolves B-3).
3. **Incumbent ruling on OQ-PKS-2** — or an explicit delegation of that question *into* Strategic Modeling as its first task (resolves B-2 / SR-1).
4. **Three-roots ruling on OQ-PKS-7** — same delegation option (resolves B-1).
5. **Polarity disposition on OQ-PKS-4** — adopt, reject, or defer-with-record (resolves R-gap-1); and a kind→vocabulary decision or explicit delegation on OQ-PKS-9 (resolves SR-2).

If the ARB takes actions 1–5 — even where the action is an explicit *delegation into* modeling rather than a resolution — Strategic Modeling is justified on the existing evidence base with no further discovery. Sequencing among them is the ARB's to choose; nothing here prescribes it.

---

## Constraint check

No bounded contexts identified · no context maps created · no context relationships defined · no aggregates/entities/VOs/repositories/domain services mentioned as design · no C4 · no implementation recommended · no new discovery performed (one verification re-check of already-cited package text only) · no KnowledgeOS expansion · every concept grouping cited to the package's own text · package remains v1.0, four members.

---

*Traceability: PA commission 2026-07-28 (readiness assessment only) · assesses PKS Phase I Review Package v1.0 — items (1)–(4) · draws on `PKS_Phase_I_Closure_Verification_Report.md` (incl. §7 editorial findings) · OQ decision-shape property verified against item (1)'s Q9 register this session · session record `.claude/sessions/2026-07-28.md`. **STOP — readiness assessment only; the phase transition is the ARB's decision.***
