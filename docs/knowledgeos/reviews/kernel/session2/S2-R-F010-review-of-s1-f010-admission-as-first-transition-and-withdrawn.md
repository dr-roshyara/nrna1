# Session-2 Review — S1-F010

## 1 · Source artifact
`session1/S1-F010-admission-is-the-first-transition-not-the-core-act-and-a-rival-state-set.md`

## 2 · What Session 1 claims
(1) A **position change**: admission is the *first transition of a lifecycle*, not the core domain act — read as **reversing** `S1-F009` seven minutes earlier. (2) Three state-set derivations from three theory bases, called *"the strongest derivation method used anywhere in the corpus."* (3) `WITHDRAWN` given a complete transition spec, plus a second de-admission path via evidence invalidation. Session 1 notes this *"answers the question `W:C-15` leaves open — state, event, or disappearance? — with **all three at once**."*

## 3 · Evidence classification
Quotations **FACT**. The three state sets are **MODEL**. *"Reversal"* is **INFERENCE** and fails (§8). *"Strongest derivation method"* is **INTERPRETATION**. `Claimant` and *"no dependency violation"* are **MODEL** elements with no elaboration.

## 4 · Question type
**TEMPORAL / lifecycle** — and this is the artifact's own framing: *"What is the complete KnowledgeOS epistemic lifecycle, **independently of the Kernel**?"* Not a boundary question, not an ontology question. That framing sentence is what dissolves the reversal.

## 5 · DDD / architectural altitude
Candidate **states**, **domain events**, an **actor** (`Claimant`), and a **candidate invariant** (inter-claim dependency). Altitude: **Domain/Core lifecycle** — explicitly *not* Kernel, by the document's own construction.

## 6 · Provenance assessment
`P4` MODEL_INTERPRETATION, ⟦partly UNCERTAIN⟧, confidence **medium** — Session 1's own rating, and appropriate.

⚠ **Second candidate for the `S2-F020` pattern.** Source is 2026-08-23 11:16; v1.1 is 2026-08-22 14:02 — law preceded by ~21 hours, enumerating **seven** states and stating *"No eighth state is introduced."* This document derives a **rival** set with four states having no counterpart and never references that enumeration.

⚠ **But this is a *consistent* case, not a *demonstrated* one.** Unlike `S1-F012`, which asserted the law does not enumerate states (verifiably false), this document simply does not cite it — and **absence of citation is not absence of access.** I hold myself to the rule I applied at `S2-R-F001`. It strengthens the pattern's plausibility; it does not add a proven instance.

## 7 · Zero-lens assessment
Session 1 handles the central case correctly and deserves the credit: *"**This is a rival state set, not an extension** … it is **not** an eighth-state proposal against v1.1 — it is an independent derivation that never references v1.1. **No gap is claimed and nothing is adjudicated.**"* That is the right classification — **UNRELATED DERIVATION**, not *contradiction* and not *deficiency in law*.

## 8 · Contradiction test
The claimed reversal of `S1-F009`: **dissolved at `S2-F018`.** *Admission is the Kernel's core act* and *admission is the lifecycle's first transition* are jointly satisfiable — and the document's own framing sentence (*"independently of the Kernel"*) is quoted two paragraphs above the reversal claim. **DIFFERENT QUESTION.**

What survives from Session 1's reading is the **coverage gap**, which I endorse: `S1-F009`'s equilibrium is scoped *"during admission"*, so it governs less than the lifecycle. Incompleteness, not reversal.

## 9 · Convergence test — the three theory bases
Session 1's praise is **half right, and the half that fails is the tally.**

| Level | Verdict |
|---|---|
| The **theory bases** (epistemic theory · constitutional governance · evidence/justification theory) | genuinely independent traditions ✅ |
| The **derivations** from them | **DEPENDENT** — one reasoner, one document, one sitting, selecting the bases and performing all three derivations |
| The **comparison** | performed by the same reasoner whose priors shaped each derivation |

So *"`Admitted`, `Superseded`, `Reconciled`, `Rejected` appear in **two of three** bases"* is not three-fold corroboration; it is **one arrival expressed through three chosen framings**. Classification: **POSSIBLE INDEPENDENT ARRIVAL at the theory level · DEPENDENT at the derivation level.** The *method* deserves the praise Session 1 gives it — deriving from independent traditions and comparing beats asserting. The **count** inherits `M-6`.

## 10 · What survives
The three state sets as data. The **coverage gap** in `S1-F009`'s criterion. The `WITHDRAWN` transition spec. And the artifact's best contribution, which Session 1 identifies: **two distinct causes of de-admission** — voluntary retraction by an authorised claimant versus evidence invalidation — *different actor, different guard, different event.*

## 11 · What is challenged
The reversal (`S2-F018`) · the three-base tally (§9) · and one reframing of Session 1's own reading, below.

### *"All three at once"* is not evidence of confusion — **CHALLENGE**
Session 1 (and `S1-F013` retrospectively) treat `WITHDRAWN` being **a state, an event, and a history-remainder simultaneously** as symptomatic of the unsettled state/relationship/event classification.

**But that is the normal shape of a lifecycle transition.** Every transition has a resulting state, an emitting event, and a history entry — three *roles*, not three rival answers. Nothing anomalous is happening.

The genuine open question is narrower and better: **is `WITHDRAWN` a member of the epistemic-state vocabulary, or a lifecycle status outside it?** Law's seven states are epistemic (*how well is this known?*); `WITHDRAWN` is about **assertion status** (*is this still claimed?*). Those may be orthogonal axes rather than competitors for one slot.

**Consequence:** `S1-F013`'s classification blocker may be real for `SUPERSEDED`/`CONTESTED`/`RECONCILED`/`INSUFFICIENT_EVIDENCE`, but **`WITHDRAWN` is weak evidence for it** — and `S1-F013` leans on `WITHDRAWN` as its retrospective illustration.

## 12 · Implementation relevance

### Candidate A · Withdrawal preserves history
*"The claim is no longer part of KnowledgeOS (**though its history remains**)."*

**Kernel test:** remove it and withdrawal becomes deletion; *"what did we know at T"* for any T before withdrawal is unanswerable. Destroys **reconstructibility**. Passes strongly.

**Existing protection:** `INV-KOS-HISTORY-001` (forward-only history) plus Article 11's rejection of *revision = deletion* and *Revision ≠ Erasure* already carry this. **Decision → DO NOT IMPLEMENT (already protected).** Loss from inaction: none.

### Candidate B · Two causes of de-admission must be distinguishable
Voluntary retraction (`KnowledgeWithdrawn`, guard *"Claimant authorized"*) versus evidence invalidation (`KnowledgeEvidenceInvalidated`).

**Kernel test:** collapse them, and a claim the **claimant** retracted is indistinguishable from one whose **evidence** failed. You cannot then tell whether the *claimant* changed position or the *world* did — an **authority and attribution** question, and distinct from supersession.

**Decision → NEEDS FURTHER EVIDENCE.**
⚠ **Duplicate-control check named, deliberately not run.** It would not change the verdict: the evidence here is a **model proposal** from a `P4` document that never references the state enumeration in law — materially weaker than `S1-F001`'s *measurement*. Even if law carries no retraction event, a model proposal at this provenance does not establish a requirement. Running the grep would manufacture an architectural conclusion this artifact does not license.
**Trigger that would change it:** the first consumer that must be told *why* a claim is no longer asserted.
**Loss if genuinely absent:** the reason for de-assertion, unrecoverable after the fact.

### Candidate C · Inter-claim dependency (*"no dependency violation"*)
**Kernel test:** without it, withdrawing a claim others depend on silently invalidates derived knowledge with no signal. Real consequence.
**But:** one unelaborated clause in one model-generated document, introducing a **relation between claims** that appears in no other finding. **Decision → NEEDS FURTHER EVIDENCE.** This is the artifact's largest unexplored idea and its thinnest evidence — recorded so it is not lost.

### Candidate D · The rival state set itself
**Decision → DO NOT IMPLEMENT.** Law enumerates seven and refuses an eighth; a rival derivation that never engages that enumeration cannot displace it, and adopting states from it would be adjudication. **NOT ADJUDICATED.**

## 13 · Standing hypothesis test (§17)
**Strengthens.** The artifact models membership as a **lifecycle position** with history surviving de-assertion — *"though its history remains"* is literally a reconstruction guarantee. It weakens the *"stores a primitive called Knowledge"* reading, since being in KnowledgeOS becomes a function of assertion status over time rather than of being stored. **Still not adopted.**

## 14 · Confidence
**High** on the dissolution and on §9. **High** on Candidates A and D. **Medium** on B. **Low** on C — deliberately, given single-clause evidence. Level 1 **UNVERIFIED** throughout.

## 15 · Final Session-2 assessment
Session 1's Zero-lens handling of the rival state set is exemplary and its *"reversal"* is not one. My one substantive reframing runs **against** a later Session-1 finding: *"all three at once"* describes an ordinary transition, so `WITHDRAWN` is weak support for `S1-F013`'s blocker. The artifact's real contribution is the **two-cause de-admission distinction**, which Session 1 identified and which no other finding carries. **Zero IMPLEMENT; one protected, two evidence-gated, one refused.**
