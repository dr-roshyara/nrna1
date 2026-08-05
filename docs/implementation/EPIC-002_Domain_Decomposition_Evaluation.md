# EPIC-002 Domain Decomposition Evaluation Report

**Kind:** evaluation artifact — Strategic DDD Phase 2. **Authority:** generated; never authoritative without ARB review. **This report is not a canonical-model selection.**
**ARB ruling authorizing this report:** the Candidate Bounded Context Discovery Report is approved with a terminology refinement — its candidates (BC-1..BC-4 and their alternatives) are re-labeled **Candidate Domain Boundaries (CB-n)** here, since "bounded context" denotes an architectural commitment and these remain hypotheses. Model C is accepted only as a **preferred working hypothesis**, not yet ratified as canonical. Context Mapping is **not yet authorized**.
**Inputs (only):** `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md`, `EPIC-002_Bounded_Context_Discovery.md`. No new literature, no external assumptions, no implementation knowledge.
**Explicitly out of scope:** Context Map, relationship patterns (Partnership/ACL/Shared Kernel/Published Language), aggregates, entities, value objects, repositories, APIs, events, services, databases, implementation architecture, IDDs.

---

## 1. Candidate Boundary Evaluation

*(CB-n numbering carries forward from the Discovery Report's BC-n; renamed per the terminology refinement above.)*

### CB-1 — Collection & Aggregation
- **Strengths:** broadest evidence base (8 independent families); no discipline examined suggests single-channel evidence production.
- **Weaknesses:** owns no discovered decision; K4 documents that a large share of at least one discipline's own practice (17/36 DDD-effectiveness studies) proceeds with no operationalized metric — the capability is aspirational in real practice, not just cohesive in principle.
- **Supporting evidence:** Evans, SLR (arXiv 2310.01905), EventStorming, PCAOB AS 2201, OECD risk-assessment.
- **Contradicting evidence:** none directly refutes its existence; the only challenge is definitional (stakeholder-plurality vs. evidence-kind-plurality), a scope question, not a refutation.
- **Why it exists:** every discipline needs some activity that gathers heterogeneous inputs before anything else can happen.
- **Why it might not exist as its own boundary:** owning no decision is a real argument that this is an upstream shared capability other boundaries draw on, not a boundary in its own right.

### CB-2 vs. CB-2-Alt — Custodial vs. Self-Verifying Integrity
- **Strengths (custodial):** named, concrete, deployed practice in 2 disciplines (ES/DF); real pilot precedent (Franklin County, Idaho).
- **Strengths (self-verifying):** genuine academic design position (Ali & Murray) motivated by removing trust-in-personnel.
- **Weaknesses (custodial):** does not generalize — Governance Theory and DDD literature never engage it.
- **Weaknesses (self-verifying):** two stronger versions of the claim were refuted (VMV/Selene; Helios-bulletin-board); real hybrid deployed systems (STAR-Vote, Wombat) retain custody as backup, undermining self-verification-alone-suffices in practice.
- **Contradicting evidence:** each side is the other's contradiction — the clearest head-to-head contest in the program (T1).
- **Why the boundary might not resolve to one:** this may not be a single boundary with two implementations but two alternative designs for the same concern — evaluation must ask whether both coexist for different evidentiary streams, one displaces the other, or the tension requires an architectural (not evidentiary) decision.

### CB-3 vs. CB-3-Alt — Adjudication vs. Authority-Validity
- **Strengths (Adjudication):** the strongest *decision-owning* candidate in the program — 8 independent families, tied with CB-1 (**corrected from an earlier 11**; see `EPIC-002_Evidence_Family_Independence_Audit.md`), clear decision-ownership (D1, which CB-1 lacks), consistent non-automaticity everywhere examined.
- **Weaknesses (Adjudication):** the language analysis suggests possible semantic overload (content-dispute resolution vs. authority-validity/expiry) — an internal inconsistency not yet resolved.
- **Strengths (Authority-Validity):** a genuine, real-world documented mechanism (Australia's Legislative Instruments Act), not hypothetical.
- **Weaknesses (Authority-Validity):** rests on exactly one narrow, jurisdiction-specific, secondary-sourced (2-1 vote) candidate — the thinnest evidentiary base of any candidate in the program.
- **Why the split might not hold:** automatic lapse of legal authority may be a fundamentally different *kind* of event than an adjudication decision — it might not belong in the Adjudication family even as a split, or might not be a distinct capability at all, given how thin the evidence is.

### CB-4 — Contemporaneous Record-Fixing
- **Strengths:** owns a decision (D2); real supporting evidence from 3 disciplines; a principled volatility argument (act-time vs. review-time) for staying separate from CB-3.
- **Weaknesses:** narrowest positive evidence base (3 families) and the most internally ambiguous phenomenon in the program (P3) — attacked from both directions, resolved in neither.
- **Why it might not exist as its own boundary:** owns only one decision and otherwise feeds CB-3 — the same "thin, decision-light, upstream" shape as CB-1's weakness.

### Re-evaluated: Evidence/Argument Separability
No new argument overturns the Discovery Report's finding: still a cross-cutting constraint (K2), not a boundary — it has no independent responsibility of its own to perform.

## 2. Whole-domain Model Comparison

*(Models A/B/C as presented in the Discovery Report §8, evaluated here against nine identical criteria.)*

| Criterion | Model A (Minimal, 2) | Model B (Maximal, 6–7) | Model C (Catalogue, 4 + 2 contested pairs) |
|---|---|---|---|
| Cohesion | Low-Medium — forces custody, contemporaneity, and collection together despite differing volatility and an active internal design contest (T1) | High per-boundary, but Separability has no independent activity to perform — a cohesion failure of the opposite kind | Medium-High — CB-1/CB-3 well-cohered; contested pairs left open rather than force-cohered |
| Coupling | Appears low (2 boundaries) but hides internal coupling: one boundary would have to encapsulate two mutually exclusive custody designs | High — 6–7 boundaries multiplies cross-boundary dependencies; Authority-Validity introduces a boundary of unconfirmed necessity | Medium — fewer boundaries, and open coupling questions (e.g., CB-4 fold into CB-3?) stay visible rather than falsely resolved |
| Language consistency | Poor — three distinct senses of "evidence" (record/custody-object/raw-input) sit inside one boundary despite being shown semantically distinct | High per-boundary, but over-fragments language not shown to be that discontinuous (Authority-Validity split rests on one candidate) | Reflects the discovered discontinuities without inventing a boundary for every one found |
| Responsibility ownership | Unclear — R1/R3/R4 share one boundary despite only sequential, not same-actor, dependency | Very clean per-boundary, except Separability has no responsibility to own | Matches the Responsibility Catalogue directly — no orphaned or invented responsibilities |
| Decision ownership | D2 and D3 both fall inside the same boundary as D1's absence — doesn't track the Decision Boundary Analysis, which found three separately-triggered questions | Clean split, but D1's and D3's splits both rest on the thinnest, most contested candidates in the program | D1/D2/D3 map cleanly to CB-3(/-Alt)/CB-4/CB-2(/-Alt) respectively |
| Evidence traceability | Technically traceable, but merges evidence the Discovery Report explicitly found does not force merging | Traceable, but Authority-Validity and Separability-as-boundary both stretch a single or explicitly-rejected data point into full boundary status | Strongest — every candidate and alternative traces directly to an already-catalogued evidence family, nothing stretched |
| Uncertainty handling | Poor — folds T1 (the most informative unresolved tension in the program) silently inside one boundary | Mixed — surfaces T1/T3 explicitly, but converts the Separability question into false confidence by giving it boundary status anyway | Best — keeps T1 and T3 open as explicit alternative pairs, neither hidden nor falsely resolved |
| Architectural stability | Uncertain — absorbs a larger blast radius if T1/T4 eventually resolve toward splitting | Risk of premature commitment — thin boundaries may need collapsing later, causing rework | Medium — two contested pairs are a known, explicit, honestly-flagged source of future change |
| Future evolvability | Low — bundles three differently-paced responsibilities into one boundary | High in principle, undermined by artificial boundaries that may not survive further scrutiny | Medium-High — small boundary count with two explicitly-flagged decision points |

## 3. Boundary Stress Test

- **Could CB-1 and CB-4 merge?** Both are decision-light (CB-1 owns none, CB-4 owns only D2) and both feed CB-3. Merging is plausible and not foreclosed by evidence — a genuine open stress-test result, **not resolved here**.
- **Could CB-3 split (Adjudication vs. Authority-Validity)?** Possible per the tension analysis, but the Authority-Validity evidence is the thinnest in the program — splitting now would commit structure ahead of evidence.
- **Which tensions disappear under Model A?** None — T1 and T3 don't disappear, they're hidden inside a larger boundary, which is worse than visible, since the tension still exists but the model's own structure no longer surfaces it.
- **Which tensions worsen, or appear, under Model B?** None of T1–T5 disappear, but Model B manufactures a *new* one: whether Separability deserves boundary status at all, since it has no independent responsibility — a tension Model C's own evaluation already resolved (constraint, not boundary).
- **Which responsibilities become duplicated?** Under Model B, if Separability is a boundary, its "responsibility" (ensuring evidence/argument separability) is not actually distinct — it duplicates a concern already latent in CB-1/CB-2/CB-4, each of which must already respect K2 as a constraint. This is the strongest concrete argument against Model B's Separability-as-boundary choice.
- **Which decisions become ambiguous?** Under Model A, D2 and D3 both sit inside "Evidentiary Record" alongside D1's absence, leaving unclear whether one mechanism handles all three — not supported, since each decision has different supporting/contradicting evidence and confidence.
- **Which language becomes inconsistent?** Under Model A, the semantic overload of "evidence" (Discovery Report §2) is not resolved by placement under one boundary — it is absorbed silently, worse for language consistency, not better.

## 4. Trade-off Analysis

| | Benefit | Risk | Assumption | Unresolved |
|---|---|---|---|---|
| **Model A** | Simplicity; fewer boundaries to coordinate | Hides T1 and T3; forces language/decision heterogeneity into one boundary | Collection/custody/contemporaneity share enough fate to be one boundary — not demonstrated | Whether "Evidentiary Record" can coherently own three differently-evidenced decisions |
| **Model B** | Maximal clarity per boundary; nothing implicit | Premature commitment on thin evidence (Authority-Validity, Separability-as-context); higher coordination overhead; a self-manufactured tension | Every discovered discontinuity deserves boundary status regardless of evidence strength — not supported by the evidence-traceability discipline applied elsewhere | Whether Authority-Validity is a real capability or an artifact of one thin candidate |
| **Model C** | Matches the Responsibility/Decision/Language analyses most closely; keeps genuinely unresolved tensions visible; strongest evidence traceability | Still carries two explicit open decisions (CB-2/Alt, CB-3/Alt) that evidence alone cannot fully close | The four-plus-two-pairs shape is the right granularity — itself only one of several defensible readings | Same two contested pairs, explicitly carried forward rather than resolved |

## 5. Remaining Architectural Risks

- The CB-1/CB-4 merger question (stress test, §3) is not resolved by this evaluation and remains open.
- T1 (CB-2 vs. CB-2-Alt) and T3 (CB-3 vs. CB-3-Alt) may require an **architectural** decision, not merely further evidentiary review — no additional literature is authorized, so these cannot be closed by more research.
- Authority-Validity's already-thin evidence cannot be strengthened without new literature, which is not authorized — this candidate may remain permanently thin.
- "Separability as constraint" (not a boundary) still needs some future mechanism for how it gets honored operationally wherever CB-1/CB-2/CB-4 are eventually realized — noted as a forward risk, not designed here.
- Committing to Model C's four-boundary skeleton before resolving the two contested pairs risks premature structural lock-in if the ARB later decides differently on either pair.

## 6. Recommended Domain Decomposition (advisory, not a canonical selection)

**Model C's four-candidate-boundary skeleton (CB-1, CB-2/CB-2-Alt, CB-3/CB-3-Alt, CB-4) is the best-supported working hypothesis**, on evidence-traceability, uncertainty-handling, and alignment with the Responsibility/Decision/Language analyses. Models A and B are not merely less preferred by intuition — each fails a specific, articulable test: Model A hides two live tensions inside one boundary; Model B manufactures a boundary (Separability) with no independent responsibility and over-commits on the thinnest evidence in the program.

**This is not a canonical-model ratification.** Two internal questions remain genuinely unresolved by evidence alone:
1. Does custody resolve to CB-2, CB-2-Alt, both (for different evidentiary streams), or neither?
2. Does CB-3-Alt (Authority-Validity) deserve separate status, or collapse into CB-3, or not exist as a capability at all?

## 7. Confidence Assessment

| Candidate / Model | Confidence | Basis |
|---|---|---|
| CB-1 Collection | Medium | Cohesive, broad evidence, but decision-light |
| CB-2 Custodial | Low/contested | Competes directly with CB-2-Alt |
| CB-2-Alt Self-Verifying | Low/contested | Weakened by two refuted stronger versions |
| CB-3 Adjudication | High | Strongest, most decision-clear candidate in the program |
| CB-3-Alt Authority-Validity | Low | Single narrow, secondary-sourced candidate |
| CB-4 Record-Fixing | Low/Medium | Real counterargument for absorption into CB-3 |
| Model A (Minimal) | Low | Fails uncertainty-handling and language-consistency tests |
| Model B (Maximal) | Low-Medium | Fails evidence-proportionality; manufactures one tension |
| Model C (Catalogue) | Medium-High as a working hypothesis | Best alignment with all prior analyses; two internal questions explicitly still open |

## 8. Outstanding Questions for the ARB

1. Should CB-2/CB-2-Alt be resolved now as an architectural (not evidentiary) decision, or held open into Context Mapping as a documented alternative?
2. Same question for CB-3/CB-3-Alt — is the Authority-Validity evidence too thin to ever justify separate status, regardless of future review?
3. Should CB-1 and CB-4 be tested for merger before Context Mapping, given both are decision-light and both feed CB-3?
4. How should "Separability as a cross-cutting constraint" (not a boundary) be carried forward operationally into whatever comes next?
5. Is Model C's four-boundary skeleton ready to be evaluated for Context Mapping, or does the ARB want the two contested pairs resolved first?

---

**Stop condition:** this report evaluates and compares; it does not select a canonical model or map relationships. **STOP.** Context Map, relationship-pattern assignment (Partnership/ACL/Shared Kernel/Published Language), Tactical DDD, and implementation specifications remain **not authorized** until the ARB rules on the outstanding questions above.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md`, `EPIC-002_Bounded_Context_Discovery.md` · No new sources consulted.*
