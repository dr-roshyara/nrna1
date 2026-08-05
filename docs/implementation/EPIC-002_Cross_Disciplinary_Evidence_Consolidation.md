# EPIC-002 Cross-Disciplinary Evidence Consolidation Report

**Kind:** research synthesis artifact — bridges the completed literature program to Strategic DDD. **Authority:** generated; never authoritative without ARB review.
**ARB ruling authorizing this report (2026-07-14):** literature collection per the EPIC-002 charter is COMPLETE; no additional general literature review is authorized; P5's uncertainty is acknowledged and carried forward as a documented architectural uncertainty, not a research blocker.
**Scope discipline (binding on this document):** uses ONLY verified findings already committed in `EPIC-002_Literature_Review.md` and `EPIC-002_Concept_Register.md`. No new sources. No reinterpretation of refuted claims. **Does NOT** confirm bounded contexts, propose aggregates/repositories/APIs/events/tactical models, write an IDD, or produce a Context Map. Every "architectural implication" below is phrased as an open question for Strategic DDD to resolve, never as an answer.
**Inputs consolidated:** Iteration 1 (Election Science, unverified/superseded) · 2a (Constitutional + Administrative Law, verified) · 2b (Digital Forensics + PROV + Trust Engineering, verified) · 2c (Digital Forensics isolated, partially verified) · 3a v2 (ElectionGuard + E2E-VV, valid, falsification mode) · 4 (Governance Theory, valid, falsification mode) · 5 (DDD literature, valid, falsification mode, explanatory-power prioritized).

---

## 1. Cross-disciplinary synthesis, per phenomenon

### P1 — Evidence is structurally plural / multi-kind

- **Strongest supporting evidence:** Evans (DDD, primary) — domain-model "raw material" comes from qualitatively distinct source kinds (experts, users, prior team experience, documents, talk). PCAOB AS 2201 (Governance Theory, primary) — inquiry alone is explicitly insufficient; four distinct evidence-gathering procedures are required. W3C PROV-DM (Provenance) — typed Entity/Activity/Agent decomposition. Benaloh et al. (E2E-VV) — cast-as-intended/recorded-as-cast/tallied-as-recorded triad.
- **Strongest counterexample:** none survived verification in any discipline. Every attempt to frame a source as supporting single-channel evidence was either not attempted or refuted for being too indirect.
- **Holds under:** every discipline examined treats evidence/knowledge production as drawing from heterogeneous, non-interchangeable source kinds.
- **Fails/doubtful under:** the DDD-literature iteration surfaced a live definitional split — *stakeholder plurality* (many people collaborating) is not the same claim as *evidence-kind plurality* (many kinds of evidentiary material). Only a subset of DDD sources speak to the latter.
- **Genuinely contested:** the DDD-literature SLR found 17 of 36 reviewed studies report no operationalized evaluation metric at all — plurality-in-principle coexists with widespread absence of operationalized evidence in that discipline's own practice.
- **Appears across:** 8 independent evidence families, the broadest of any phenomenon in this program.

### P2 — An adjudication/correction mechanism is neither automatic nor guaranteed reliable

- **Strongest supporting evidence:** Evans (primary) — "software can't do this"; reconciliation requires "knowledge crunching in close collaboration." Brandolini (primary, 2019 interview) — Hot Spots visualize disagreement "to visualize reality, not a photoshopped version of it... a few can be resolved, but not all of them." ICSID Convention (primary) — revision/annulment remedies are narrow, exceptional, explicitly not a route to relitigate merits. PCAOB/Deloitte DART — ICFR remediation requires professional judgment and written communication, not mechanical pass/fail.
- **Strongest counterexample:** the automatic-crisis-response-activation claim (Governance Theory, OECD) was tested and refuted — activation-of-a-pre-set-plan is not adjudication of evidence quality. No confirmed counterexample survived across any discipline.
- **Holds under:** every discipline's own description of how contested or incomplete knowledge actually gets resolved in practice.
- **Fails/doubtful under:** unresolved — two open categorization questions remain live, not resolved by this consolidation:
  1. Does self-executing legislative sunset/expiry (Australia's Legislative Instruments Act) count as an automatic correction mechanism against P2, or is "expiry of legal authority" a structurally different kind of mechanism than "adjudication of evidence quality"? (iteration 4)
  2. Do algorithmic microservice-boundary-clustering techniques (Louvain, GNN-based) constitute automated adjudication, or a structurally different technical-clustering activity? (iteration 5, cited only in passing, not applied in the source paper's own method)
- **Appears across:** 8 independent evidence families, tied with P1 — **corrected from an earlier 11 figure** (`EPIC-002_Evidence_Family_Independence_Audit.md`, 2026-07-25: four of iteration 4's claimed families shared one underlying mechanism, "institutional deliberative oversight is non-automatic," and collapse to one). Still the most aggressively attacked phenomenon in the program, even if no longer the single broadest by count.

### P3 — Contemporaneity — no retrospective reconstruction of record or justification

- **Strongest supporting evidence:** Evans — the waterfall's one-way, non-contemporaneous analyst-to-programmer relay is diagnosed as a failure mode ("knowledge trickles in one direction, but does not accumulate"). Chenery/hard-look doctrine (AL/CL) — no post-hoc rationalization of agency decisions.
- **Strongest counterexample (unresolved, not confirmed):** EventStorming's retrospective narrative-building of business processes — a genuine candidate tension, but explicitly hedged: it reconstructs a shared *model*, not an evidentiary *record* of a specific factual occurrence, so whether it actually bears on P3 as scoped is an open definitional question, not a settled contradiction.
- **Holds under:** P3 read narrowly as evidentiary-record contemporaneity (not model/narrative reconstruction).
- **Fails/doubtful under:** a broadened reading of P3 that folds in model/narrative reconstruction generically — but this reading is not what the baseline claims, so it complicates scope rather than falsifying the phenomenon.
- **Genuinely contested:** this is the only phenomenon where a direct attempt to *strengthen* it (an OECD-sourced claim that crisis governance "explicitly rejects" retrospective reconstruction) was also refuted, alongside two direct falsification attempts (post-legislative sunset scrutiny; EventStorming's Reverse Narrative). Three genuine tests, three rejections, in both directions — P3 is more genuinely ambiguous in the literature than confirmed or falsified.
- **Appears across:** 3 independent evidence families — the narrowest positive-evidence base of the five phenomena, but arguably the most rigorously tested relative to its size.

### P4 — Evidence-substrate is separable from argument/trust-judgement over it

- **Strongest supporting evidence:** thin throughout. PR's scope-boundary observation (medium confidence) and TE's case-vs-evidence framing (most direct claims refuted) are the closest; ElectionGuard's "no canonical verifier" design fact (E2E-VV) is the single strongest instance — independently-written third-party software, not the proof object alone, determines whether tamper-evidence holds.
- **Strongest counterexample:** two, both consequential. (1) A documented Helios client-side rootkit attack (E2E-VV) shows evidence and argument CAN collapse together under compromise — an attack scenario, not a designed-in property. (2) DDD's "the model itself becomes the substrate of domain knowledge" (Fowler/Evans) is a genuine co-location tension — but the sharper, explicitly P4-framed version of this same fact ("Ubiquitous Language fuses domain terminology... evidence not kept separable from argument") was tested and refuted. The phenomenon holds only in its softer, unframed form.
- **Holds under:** a reading where evidence and argument remain conceptually distinct even when co-located in the same artifact/system.
- **Fails/doubtful under:** client-side compromise (E2E-VV); a reading where model-embedding is taken to mean evidence and argument literally merge by design (attempted in DDD literature, did not survive verification).
- **Genuinely contested:** the weakest phenomenon in the program by explanatory power, not by family count — every discipline tested produces only narrow or attack-scenario-contingent support, and DDD literature does not appear to frame the underlying question in evidentiary-epistemic terms at all.
- **Appears across:** 5 independent evidence families (+1 hedged) — a moderate count concealing genuinely thin, contested content.

### P5 — Chain of custody / evidence integrity as a named practice

- **Strongest supporting evidence for custody-retained:** ElectionGuard's own specification and the Franklin County, Idaho pilot explicitly retain physical custody of guardian/administrator keys even as ballots/tally are made public ("supplements, does not replace"). NIST SP 800-86 (Digital Forensics) — a person-attributed, time-stamped custody log is the field's own named practice.
- **Strongest counterexample:** the Ali & Murray academic framing (E2E-VV) that public self-verification *replaces* trust-in-personnel with cryptographic guarantees — a genuine, surviving partial counterexample candidate, though two stronger versions of the same claim (VMV/Selene ledger-displacement; Helios-bulletin-board-replaces-custody) were tested and refuted, and real hybrid deployed systems (STAR-Vote, Wombat) retain paper custody as backup.
- **Holds under:** systems and disciplines that explicitly name and operationalize a custody concept (ES, DF, and the custody-retained reading of E2E-VV).
- **Fails/doubtful under:** the self-verification framing, where custody's *function* (preventing undetected tampering by a trusted party) is claimed to be achievable without a custody *chain* at all.
- **Genuinely contested — the single most informative result in the entire program:** this is the only phenomenon where the literature itself is internally split, not merely thin. Compounding this, **no discipline searched since iteration 3a v2 has actually engaged P5's own vocabulary** — Governance Theory produced zero new engagement, and DDD literature contains, on direct re-verification, no discussion of evidence, chain of custody, or forensic integrity concepts whatsoever. The research's own recommendation (search records-management/archival-science literature) was considered and explicitly not authorized by the ARB, which chose instead to carry this forward as documented uncertainty.
- **Appears across:** 3 independent evidence families, genuinely mixed — the smallest and most contested evidence base of the five phenomena.

---

## 2. Recurring concepts (beyond P1–P5, drawn from the Concept Register's individual rows)

These are concept-level echoes the anti-clustering-bias rule deliberately keeps as separate rows rather than merging — recorded here as *observed recurrence*, not as a synthesis merge:

- **Custody as a precondition of certification**, not an independent add-on (ES: "certification over an unestablished trail = theater"; DF: custody log as the field's foundational practice) — this precondition relationship is itself only asserted within ES/DF; it is not established across the other six disciplines.
- **Detection vs. prevention** (tamper-*evidence*, not tamper-*prevention*) recurs independently in ES (software independence) and DF (hash-based tamper-evidence) — two disciplines, not merged, but pointing at the same underlying distinction.
- **Contemporaneity/no-post-hoc-rationalization** recurs as a concept-level echo in AL/CL (Chenery/hard-look), ES (commit-before-sample, declare-failure), and DDD (waterfall's lack-of-feedback diagnosis) — related to, but recorded separately from, the P3 phenomenon row.
- **Finality ≠ immutability** (AL: Bestandskraft/German administrative law) — a single-discipline observation with a flagged-but-unmerged relationship to ES's declare-failure/evidence-co-production concepts.
- **Differentiated standard-of-review / scrutiny-varies-by-evidence-type** (AL/CL) — a jurisdiction-specific instrument whose underlying idea is flagged as a candidate-universal echo of P1, not merged into it.
- **No-canonical-verifier as a design principle** (E2E-VV) — the closest concrete instantiation of P4 found in any discipline, and notably a *designed* structural choice rather than a general claim.
- **Knowledge crunching / EventStorming Hot Spots** (DDD) — concept-level instances of P2's mechanism, kept separate per the anti-clustering rule despite the obvious family resemblance.
- **Context Mapping / Anticorruption Layer / Bounded Context** (DDD) — DDD's own named boundary-integrity practices, explicitly confirmed disanalogous to P5's custody concept, and observed only within this single discipline.

## 3. Recurring responsibility patterns (descriptive only — no ownership or bounded-context assignment)

These describe *what the literature says happens*, not what any future architecture should own:

- **Adjudication of contested/incomplete evidence is consistently performed by a distinct human or institutional actor, never a fully automatic mechanism, in every discipline examined** — even where automated tooling exists (cryptographic proofs, algorithmic clustering, cryptographic attestation), the literature consistently frames it as an *input to* that separate adjudicative step, never a *replacement* for it. This is the single most consistent responsibility-shaped pattern across all eight evidence families supporting P2.
- **Evidence production is consistently treated as an aggregation responsibility, not a single-channel one** — no discipline examined assigns evidence-gathering to one uniform source or actor; all describe combining heterogeneous inputs (P1).
- **Custody-maintenance is answered differently, or not at all, depending on discipline** — ES/DF name it as an explicit responsibility; E2E-VV literature is internally split on whether that responsibility can be discharged by public self-verification instead of a named custodian; DDD literature does not address the responsibility at all. This is the least settled responsibility pattern in the program (P5).
- **Contemporaneity is recurrently observed as a property of records, but is rarely tested as a responsibility-question** (who must ensure it, and how) — most disciplines treat it descriptively ("the record was/wasn't contemporaneous") rather than assigning it to a named actor or mechanism, which may itself explain why P3 has proven hardest to pin down (P3).

## 4. Stable invariants (candidates only — not confirmed, per explicit ARB instruction)

- **P1 (evidence plurality)** and **P2 (adjudication non-automaticity)** are tied as the strongest standing candidates in the program: **8 independent families each** (P2 corrected from an earlier 11 — see `EPIC-002_Evidence_Family_Independence_Audit.md`), zero counterexamples despite repeated, genuine falsification attempts across maximally diverse disciplines (cryptographic protocol analysis, judicial-review doctrine, safety-assurance argumentation, DDD literature). P2 additionally carries two open categorization questions, unresolved rather than settled in its favor.
- Neither is asserted here as confirmed or as an architectural decision. Both are candidates for Strategic DDD to test further and for the ARB to ultimately rule on.

## 5. Explicit uncertainties (carried forward, not resolved)

- **P3** is genuinely ambiguous — attacked from both directions across two disciplines, three times, with every attempt (for and against) failing verification. This is a documented "known unknown," not a research gap requiring more searching.
- **P4** is thin and fragile across the entire program; its one sharp articulation failed verification.
- **P5** is CONTESTED since iteration 3a v2 and, by ARB decision, will not receive further targeted literature search. It is carried forward explicitly as an open architectural uncertainty.
- **Two open P2 categorization questions** (legislative sunset/expiry; algorithmic boundary-clustering) remain deliberately unresolved.

## 6. Architectural implications — open questions for Strategic DDD, not answers

*(None of the following proposes a bounded context, aggregate, repository, event, API, or tactical structure. Each is a question the evidence base raises but does not answer.)*

- P1's robustness raises the question of how multiple evidence kinds would need to be represented or aggregated — without this report specifying whether that implies one structure or several.
- P2's robustness raises the question of where non-automatable, discretionary judgment would need to be located, and how it would be distinguished from automatable processing steps that merely feed it.
- P3's genuine ambiguity means Strategic DDD cannot assume a single, universal contemporaneity boundary applies uniformly; it may need to be evaluated per sub-domain rather than assumed globally.
- P4's fragility means evidence/argument separability should not be treated as a settled design principle going in; the literature does not support assuming it holds by default.
- P5's contestation is the most architecturally consequential open item in the whole program: whether custody is preserved, displaced, or split is unresolved in the literature itself, and Strategic DDD will need to treat this as a genuine decision requiring its own explicit justification — not one the evidence base pre-answers.

## 7. Evidence Sufficiency Review (whole-program level, advisory only — decision is ARB's)

- **Strongest confirmations:** P1 and P2 — broadest family counts, zero confirmed counterexamples, tested against genuinely adversarial disciplines.
- **Strongest counterexamples/tensions:** P5's custody-vs-self-verification split (iteration 3a v2) remains the single most informative result in the program. P2's two open categorization questions are the next most significant open items.
- **Unresolved questions carried into synthesis:** P3's bidirectional ambiguity; P4/P5's thinness relative to their apparent architectural stakes; the two P2 categorization questions.
- **Confidence:** P1/P2 high. P3 medium but genuinely ambiguous (not simply "unconfirmed" — actively contested). P4 low. P5 low/CONTESTED.
- **Remaining gaps:** P4 and P5 are undertested relative to how architecturally consequential they may be — this gap is now being carried forward by explicit ARB decision, not by oversight.
- **Advisory recommendation (not a decision):** the evidence base is sufficient to proceed toward Strategic DDD, **provided P3, P4, and P5's uncertainty is carried forward explicitly as open questions rather than resolved by assumption.** This is consistent with, and offered in support of, the ARB's own ruling that literature collection is complete and P5 remains a documented architectural uncertainty rather than a research blocker.

---

**Stop condition (per ARB instruction):** this report is the deliverable. No Strategic DDD, Context Map, candidate-BC evaluation, tactical design, or IDD work is authorized by this document. STOP for ARB review of this consolidation before any further activity.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Literature_Review.md`, `EPIC-002_Concept_Register.md` (iterations 1, 2a, 2b, 2c, 3a v2, 4, 5) · No new sources consulted in producing this report.*
