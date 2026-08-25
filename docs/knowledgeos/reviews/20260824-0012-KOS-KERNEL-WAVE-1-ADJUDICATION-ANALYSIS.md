# KOS-KERNEL-WAVE-1-ADJUDICATION-ANALYSIS — the six constitutional questions

> **Role:** strict adjudication analysis of **Wave 1** — the six unblocked, highest-leverage questions from `KOS-KERNEL-HPA-ADJUDICATION-WORKBOOK-001` §3. Decision support for the HPA/ARB.
> **Commission:** HPA, 2026-08-24 — continue from the workbook, use its dependency graph, begin with Wave 1, and for each item run the full structure: question · object · altitude · existing law · existing evidence · brainstorming evidence (separately) · DDD analysis · options · twelve per-option sub-questions · ZERO test · **falsification of every option, without defending the current architecture** · decision consequence · recommended ruling *formulation* · unresolved. **STOP after Wave 1.**
> **⛔ DECISION SUPPORT ONLY.** No ruling field is filled. No decision is marked accepted. No decision is committed on the HPA's behalf. Recommended formulations are **candidate wordings**, offered so a ruling can be phrased precisely — never a selection of the substantive answer.
> **Status:** 📋 **PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** Nothing implemented · Constitution **not modified** · no research opened · no brainstorming reopened · **no philosophical lens used as authority**. Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · **the Kernel is not built.**
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 0 · Wave 1, and why these six

From the workbook's dependency graph: **all six are unblocked**, and four of them (Wisdom · C-15 · C-17 · F-CM-1a) close **constitutional** questions that sit beneath every later ruling. **C-18 unblocks C-2**, which in turn unblocks the entire fitness family. **C-14 is the one Wave 1 item with a prerequisite** (C-11, Wave 3) — it is included because the workbook treats it as a *reclassification* question, and whether the existing deferral stands can be settled independently of C-11's substance.

| # | ID | One-line question | Altitude | Blocks |
|---|---|---|---|---|
| 1 | **Wisdom** | does Wisdom enter the core? | Constitution (V.3) | nothing |
| 2 | **C-15** | must retraction have a lawful representation? | Constitution / event–state vocabulary | C-7 |
| 3 | **C-17** | what state does an internally impossible claim bear? | Constitution / state vocabulary | C-7 |
| 4 | **F-CM-1a** | record *Ambiguity ≠ Contradiction*? | Constitution (§15) | F-CM-1b → C-4 · C-16 |
| 5 | **C-18** | must an admitted state bind its governing law version? | trigger §16 → remedy on the aggregate | **C-2 → C-8 · C-12 · C-9** |
| 6 | **C-14** | does UQ-4's deferral stand? | reclassification: Constitution or Logical Architecture | nothing |

**Ordering note.** Items 2 and 3 both propose state/event vocabulary changes and both feed C-7. They are **independent of each other** but should be read together, because two separate additions to the same seven-state vocabulary have a combined effect neither has alone.

---

# 1 · WISDOM

### QUESTION
Does **Wisdom** enter KnowledgeCore or the Admission Boundary as a domain concept — and if the programme wishes it to, does that require a **V.3 constitutional amendment act** separate from the Kernel chain?

### OBJECT
**O-5 — the Constitution / the character.** No aggregate, member, event, state, context or capability is touched by the status quo. Only an amendment would touch any of them.

### ALTITUDE
**Constitution (V.3).**

### EXISTING LAW
- **§4.1**, candidate-core table: *"**Wisdom Formation** | no register row; the character ends at *trustworthy truth discovery*; adopting it would extend the frozen Constitution (V.3) | **REJECTED at the boundary** (§17; amendment-gated)"*.
- **§17**, Rejected / External Concepts: *"**Wisdom Formation as a core domain** | not a constitutional concept; adopting it would extend the frozen Constitution | **V.3**"*.
- **§8**: *"⚠️ **WisdomDerived — recorded for traceability, NOT admitted.** Wisdom is not a constitutional concept (no register row; the character ends at trustworthy truth discovery). A future Wisdom context would require a constitutional amendment, and **V.3 forbids adding concepts.**"*
- **§16**: *"Nothing moves between altitudes except by constitutional amendment."*

### EXISTING EVIDENCE
ADR-KOS-SCOPE-001 §16 states the position with these three sources, records that a question of the form *"the smallest boundary of knowledge **and wisdom**"* cannot be answered by a boundary act, and **makes no amendment and no recommendation**. No delivered artifact in the chain requires Wisdom.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
Extensive: the Gaṇeśa/Wisdom character work, `WISDOM-MECH-001..006`, the wisdom lifecycle. **Decisive point: the corpus's own 26-lens consolidation files the wisdom family in tier 3, *mechanism candidates* — not in the architectural-adjudication tier.** The corpus never claims Wisdom is core. Its own meta-rule applies: *"convergence of lenses does not make the lens itself architectural authority."*

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity / VO** | no candidate identified; nothing in the corpus proposes a Wisdom entity with identity and lifecycle inside KnowledgeCore |
| **Event** | `WisdomDerived` exists **only** as a named-and-refused candidate (§8) |
| **Relation** | wisdom-as-relation over admitted knowledge would be a **projection** (Article 5) — regenerable, non-authoritative, no write-back |
| **Policy** | plausible as a *mechanism-side* policy behind a port; nothing requires it inside |
| **Aggregate responsibility** | **none.** No invariant becomes unprotectable if Wisdom is absent |
| **Invariant** | none exists; adding one is V.3-forbidden |
| **Consistency boundary** | **no atomicity requirement whatsoever.** Nothing must change atomically with a wisdom determination |

**Minimality test result: fails decisively.** No invariant is invalidated if wisdom changes independently of knowledge state. Under the workbook's minimality rule this is the clearest exclusion in the entire set.

### OPTIONS
- **W-1** — status quo confirmed: Wisdom remains outside as research · conceptual lens · mechanism candidate · future possibility.
- **W-2** — commission a **separate V.3 amendment act**, independent of the Kernel chain.
- **W-3** — admit Wisdom as a **Projection-context concern** (a `DerivedView` over admitted knowledge) without touching the core.

### PER-OPTION ANALYSIS

| | **W-1** status quo | **W-2** V.3 amendment act | **W-3** projection-only |
|---|---|---|---|
| What changes | nothing | the Constitution; a concept is added | possibly nothing in law; a projection kind is named |
| Invariant affected | none | **V.3** (and the character's endpoint) | INV-KOS-PROJECTION-001 governs it |
| Aggregate owning it | none | undetermined — the amendment must say | `DerivedView` (Projection context) |
| Consistency boundary implied | none | undetermined | none — projections are regenerable |
| Semantic reasoning introduced | no | **likely** — wisdom determinations are semantic | **outside** the boundary, so lawful |
| Authority introduced | no | possibly — "wise" is an authority claim | no; a projection carries none |
| Evidence custody introduced | no | possibly | no |
| Hidden state introduced | no | risk: a wisdom attribute on knowledge | no, if strictly regenerable |
| Identity semantics changed | no | possibly, if wisdom individuates | no |
| Constitutional amendment required | **no** | **yes — V.3** | **no**, if it is only a projection |
| Determinism affected | no | unknown | no |
| Anti-reasoner constraint affected | no | **yes — directly at risk** | no, if outside |

### ZERO TEST
- **What is not represented?** Nothing the current chain needs. Wisdom's absence blocks no admission, no transition, no refusal.
- **What distinction has silently collapsed?** None here — but note the corpus itself distinguishes *wisdom as mechanism* from *wisdom as concept*, and only the second is refused.
- **What assumption is required but unstated?** That *"trustworthy truth discovery"* is the character's endpoint. §4.1 and §8 state it; nothing hidden.
- **What failure case would pass undetected?** **One: wisdom entering as a Confidence input.** If a wisdom mechanism produced a score that crossed the port and became `Confidence`, ⟨R-1⟩ would be breached — and this interacts with **C-11**, where Confidence has no lawful input. Worth flagging even though no option proposes it.
- **What would an implementation invent?** Nothing under W-1. Under W-2, everything — the amendment would have to specify object, invariant and boundary from scratch.

### FALSIFICATION *(attempting to destroy each option)*
**Against W-1:** *is there a scenario where the domain cannot function without Wisdom?* No scenario was found. Every admission, refusal, revision, contradiction and supersession in the model completes without any wisdom determination. **W-1 survives.**
**Against W-2:** *can it be done without breaching the anti-reasoner constraint?* Not demonstrated. A wisdom concept inside the core would need to determine something about knowledge that is not structural — which is production, not protection. **W-2 is falsifiable on the anti-reasoner constraint unless the amendment defines wisdom non-semantically, which nothing has attempted.**
**Against W-3:** *does a projection smuggle authority?* Article 5 forbids write-back and makes a projection *never its source*, so no. But **W-3 may be vacuous**: it grants nothing that Projection cannot already do, since a `DerivedView` over admitted knowledge needs no new permission. **W-3 survives but may be a null option.**
- *Two legitimate interpretations producing different outcomes?* Yes, for W-2 only: "wisdom as a quality of knowledge" vs "wisdom as a quality of the knower" lead to different objects. Unresolved by anything.
- *Unowned transition?* W-2 risks one — no aggregate is nominated.
- *Impossible aggregate state?* Not under W-1/W-3.
- *History preserved · identity assigned?* Unaffected by all three.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **W-1** | the ADR position stands; Wisdom stays outside; the Kernel chain proceeds unchanged | **NO LAW CHANGE** |
| **W-2** | a separate constitutional act opens, with its own commission, object determination and anti-reasoner analysis; **the Kernel chain is unaffected either way** | **CONSTITUTIONAL AMENDMENT** |
| **W-3** | a projection kind is named; possibly no change at all | **NO LAW CHANGE** (possibly **MAPPING CHANGE**) |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **`RECORD ONLY`** — *Wisdom is not a KnowledgeCore or Admission-Boundary concept. §4.1, §17 and §8 stand: adopting it as a core domain concept would extend the frozen Constitution and is gated by V.3. Wisdom remains available as research, as a conceptual lens, and as a mechanism candidate outside the boundary. No amendment is made or recommended by this ruling, and none is required for the Kernel chain to proceed.*

*(If the HPA instead wishes to open the question: `AMENDMENT ACT COMMISSIONED — separate from the Kernel chain; scope, object and anti-reasoner analysis to be determined by that act.`)*

### UNRESOLVED
1. Whether a wisdom **mechanism** may ever supply an input that reaches `Confidence` — interacts with **C-11**, and is not settled by any Wisdom ruling.
2. Whether **W-3** is a real option or a null one (does Projection already permit it?).
3. If W-2 were ever taken: which object, which invariant, and how the anti-reasoner constraint is preserved. **None of these is answered by anything in the current chain.**

---

# 2 · C-15 · RETRACTION

### QUESTION
Must the domain be able to express *"the claimant no longer asserts this"* as **distinct** from *false* · *superseded* · *rejected* — and if so, by a **state**, an **event**, or neither?

### OBJECT
**O-2 — the KnowledgeAggregate's state/event vocabulary** (§8 events · §9 states).

### ALTITUDE
**Constitution** if a state or event is added (states 7→8 or events 10→11); **aggregate/member** level otherwise.

### EXISTING LAW
- **§8** — the **ten** events. None is a withdrawal. `KnowledgeSuperseded` requires a **successor**: *"a new version supersedes an old one | old state → History; forward-only"*.
- **§8 ⟨A-3⟩** — *"An event records a domain state transition, never a technical operation… If nothing in the aggregate changes, there is no domain event."*
- **§9** — the seven states; and *"Note on vocabulary: the informal complement of UNKNOWN — 'known' — is rendered by **VALIDATED**. **No eighth state is introduced.**"*
- **INV-KOS-HISTORY-001** — *"Revision never deletes; supersession is forward-only; freshness never truth, expiry never absence."*
- **Appendix B OBS-2** — a proposed eighth state was **NOT incorporated**; *"Retained as seven."*
- **INV-KOS-AGENCY-001** — *"Every state preserves epistemic agency; knowledge is never anonymous."* Relevant: retraction is an **agency act**.

### EXISTING EVIDENCE
The critique classified this a **law gap, not a mapping gap** — the mapping's eleven domain acts mirror the ten events faithfully and inherit the silence. The workbook confirms deletion is forbidden and supersession requires a successor a retraction does not have.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
**Anticipated four times, independently:** `111647` §5 lists `ADMITTED → WITHDRAWN` as a lifecycle transition and §12 places WITHDRAWN in the lifecycle · `113410` investigates WITHDRAWN across state/relationship/event/derived-condition and returns **UNRESOLVED** · `123630` lists **`WITHDRAWN ≠ FALSE`** among six ZERO non-collapse pairs · `123619` scenario 12 is *"Claim withdrawn."* **The corpus never resolved it and proposed no representation.**

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity** | no new entity — retraction is about an existing `Knowledge` |
| **Value object** | a candidate: a *retraction record* as part of `History` |
| **Event** | **strongest fit.** Retraction is a discrete act by an identified agent at a time — precisely event-shaped. But ⟨A-3⟩ requires it to change the aggregate, so it must have a state effect |
| **Relation** | not relational — it is unary, about one claim |
| **Policy** | who may retract is a policy, and it is an **authority** question (→ C-3), not a state question |
| **Aggregate responsibility** | the KnowledgeAggregate owns it if it is a transition of its own state |
| **Invariant** | INV-KOS-HISTORY-001 forbids deletion; INV-KOS-AGENCY-001 requires the retractor be identified |
| **Consistency boundary** | **atomicity holds**: a retraction that completed without its History entry would breach INV-KOS-HISTORY-001. So it belongs inside if it exists at all |

**Key DDD observation:** retraction is **event-shaped but state-requiring**. ⟨A-3⟩ makes an event lawful only if the aggregate changes — so an event with no state to move into is not admissible. **That coupling is what makes this hard: you cannot add the event without deciding the state.**

### OPTIONS
- **R-1** — add an eighth state `WITHDRAWN` (+ a `KnowledgeWithdrawn` event).
- **R-2** — add only an event, whose aggregate effect is a `History` entry plus a transition to an **existing** state.
- **R-3** — express retraction as `BeliefRevised` into an existing state, with the retraction recorded in `JustificationPath`/`History`.
- **R-4** — reject: retraction is not a domain concept; the record stands as asserted and retraction is a mechanism- or governance-side fact.

### PER-OPTION ANALYSIS

| | **R-1** state+event | **R-2** event only | **R-3** via BeliefRevised | **R-4** reject |
|---|---|---|---|---|
| What changes | states **7→8**, events **10→11** | events **10→11** | nothing structural | nothing |
| Invariant affected | §9's seven · OBS-2 · ⟨A-3⟩ | ⟨A-3⟩ (needs a state effect) | INV-KOS-HISTORY-001 (satisfied) | INV-KOS-FAILURE-001? — *no*: this is not failed reasoning |
| Aggregate owning it | KnowledgeAggregate | KnowledgeAggregate | KnowledgeAggregate | none |
| Consistency boundary | inside; atomic with History | inside; atomic with History | inside; already atomic | n/a |
| Semantic reasoning | **no** — retraction is declared, not inferred | no | no | no |
| Authority introduced | **yes** — *who may retract?* → **C-3** | **yes** — same | **yes** — same | no |
| Evidence custody | no | no | no | no |
| Hidden state | no | **risk**: an event with no distinct state leaves the fact only in History, which read models may miss | **yes, arguably** — retraction becomes invisible in state | no |
| Identity semantics | unchanged | unchanged | unchanged | unchanged |
| Amendment required | **yes** (state + event) | **yes** (event) | **no** | **no** |
| Determinism | unaffected | unaffected | unaffected | unaffected |
| Anti-reasoner | unaffected | unaffected | unaffected | unaffected |

### ZERO TEST
- **What is not represented?** The claimant's own withdrawal of assertion. Currently expressible only by collapsing it into another state.
- **What distinction has silently collapsed?** **`WITHDRAWN ≠ FALSE`** — and also `WITHDRAWN ≠ REJECTED`: rejection is the *domain's* judgement on justification; retraction is the *agent's* act. Collapsing them attributes a domain judgement to an agent act.
- **What assumption is required but unstated?** That an admitted claim's assertion is **permanent** unless superseded or found unjustified. Nothing states this, and it may not be intended.
- **What failure case passes undetected?** A retracted claim continues to be read as asserted by every consumer of state. Under R-3 the retraction lives only in `History`/`JustificationPath`, which projections may not surface — **the claim reads as live**.
- **What would an implementation invent?** Almost certainly a `REJECTED` transition, or a boolean/flag outside the state vocabulary — i.e. **hidden state**, which the model forbids by omission rather than by rule.

### FALSIFICATION
**Against R-1:** *does an eighth state breach anything?* It contradicts §9's explicit *"No eighth state is introduced"* and OBS-2's refusal — both of which are statements of record rather than invariants, so an amendment could lift them. But **it also weakens the seven-state discipline that INV-KOS-UNKNOWN-001 depends on** (*unknown ≠ absent ≠ false* works because the set is closed and small). **R-1 survives only as an amendment, and at a stated cost.**
**Against R-2:** *can an event exist without a distinct state?* ⟨A-3⟩ demands the aggregate change. A `History` entry *is* an aggregate change, so R-2 is technically lawful — **but it creates a fact visible only in History, which is the definition of hidden state.** **R-2 is falsified on the hidden-state test unless a state effect is named.**
**Against R-3:** *can `BeliefRevised` carry it?* §8 gives `BeliefRevised`'s trigger as *"new evidence or reasoning changes the justification"* — **a retraction is neither.** Using it would misdescribe the trigger. **R-3 is falsified on trigger fidelity**, unless the trigger's wording is also amended, which makes it no cheaper than R-2.
**Against R-4:** *can the domain function?* Yes — but it must then accept that **an agent cannot withdraw an assertion**, which sits oddly beside INV-KOS-AGENCY-001's insistence that knowledge is never anonymous and always carries its lineage. **R-4 survives, at the cost of an asymmetry: agency is required to create but insufficient to retract.** That asymmetry should be stated in the ruling if R-4 is taken.
- *Impossible aggregate state?* R-1 risks one if `WITHDRAWN` can coexist with `CONFLICTED` (→ **C-7**).
- *History preserved?* Yes in all four.
- *Identity assigned not derived?* Unaffected in all four.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **R-1** | states 7→8, events 10→11; the closed-set discipline weakens; C-7 gains a case | **CONSTITUTIONAL AMENDMENT** |
| **R-2** | events 10→11; retraction exists but only in History — hidden state | **CONSTITUTIONAL AMENDMENT** |
| **R-3** | no structural change; `BeliefRevised`'s trigger is misdescribed | **MAPPING CHANGE** (with a fidelity cost) |
| **R-4** | the domain cannot record retraction; the agency asymmetry must be stated | **NO LAW CHANGE** |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **If rejecting:** `REJECT — retraction is not a KnowledgeCore concept. An admitted claim's assertion is not withdrawable within the domain; withdrawal is a governance- or mechanism-side fact. The asymmetry — agency is constitutive of creation but does not confer withdrawal — is accepted and recorded.`
> **If amending:** `AMENDMENT REQUIRED — [state / event], subject to (a) the effect on the closed seven-state discipline being recorded, (b) the retractor's authority being resolved under C-3, and (c) coexistence with CONFLICTED being resolved under C-7.`
> **If deferring:** `DEFER — pending C-3 (who may retract) and C-7 (state coexistence).`

### UNRESOLVED
1. **Who may retract** — an authority question, unresolved and dependent on **C-3**.
2. Whether retraction is **reversible** (may a claim be re-asserted?). No option addresses it; nothing in law does either.
3. Whether a retracted claim's **evidence and justification remain valid** for other claims that cite them — interacts with **C-6**.
4. Whether retraction is **unary or relational** if the retractor differs from the original agent.

---

# 3 · C-17 · INTERNALLY IMPOSSIBLE CLAIM

### QUESTION
What epistemic state does a claim bear whose content is **internally** self-contradictory (asserting P and ¬P within one claim) — given that the boundary may not read meaning and so cannot detect it?

### OBJECT
**O-2 — the state vocabulary** (§9), or **the Port Contract** if the determination is supplied from outside.

### ALTITUDE
**Constitution** if a state is added; **boundary/Port Contract** if the determination crosses as port vocabulary; **documentation** if recorded as an accepted limitation.

### EXISTING LAW
- **§9** — seven states, *"distinct and none is a degree of another"*; `FALSE` = *"grounds that it is not so"*; `ABSENT` = *"grounds that it does not exist"*; `UNKNOWN` = *"no grounds"*.
- **INV-KOS-CONTRADICTION-001** — *"Conflicting knowledge coexists as CONFLICTED until governed resolution"*, enforcement locus *"EpistemicState (CONFLICTED) + ConflictRecord"*; **§6.1** — ConflictRecord *"references the conflicting aggregates by identity"* — i.e. **two aggregates**.
- **§8 KnowledgeRejected (post-r5)** — *"a candidate that satisfied the constitutive prerequisites failed on its **justification path**"*.
- **§16** — *"a kernel member can refuse a transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**."*
- **Test F / F-3** — no surface form is core; the boundary does not interpret content.

### EXISTING EVIDENCE
The critique's independent 15-condition ZERO pass found **`impossible` the only condition with no lawful state**. `CONFLICTED` is structurally inter-claim (ConflictRecord references two aggregates). `FALSE` requires grounds. `REJECTED` requires an absent justification path — and a self-contradictory claim may arrive with a **complete, well-formed** path.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
**SILENT.** The corpus addresses contradiction *between* claims extensively, and `123619` scenario 20 is *"Contradictory determination"* — but that is **the boundary producing inconsistent verdicts**, not a single internally inconsistent claim. **No artifact addresses P ∧ ¬P within one claim.** This is the one Wave 1 item the brainstorming did not anticipate.

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity / VO** | none required |
| **Event** | none — no transition occurs; the claim is simply admitted |
| **Relation** | **the crux**: `CONFLICTED` is modelled as a *relation* realised through ConflictRecord over **two** identities. An intra-claim contradiction has **one** identity, so the existing relation cannot carry it |
| **Policy** | *"submissions must be internally consistent"* is a plausible **port obligation** — mechanism-side |
| **Aggregate responsibility** | detecting it is **not** an aggregate responsibility: detection requires reading meaning (§16, Test F) |
| **Invariant** | none currently addresses intra-claim consistency |
| **Consistency boundary** | no atomicity requirement — nothing must change atomically with such a determination |

**Decisive DDD point:** the model has a relation for *inconsistency between identities* and **no construct for inconsistency within one**. That is a genuine structural absence, not a naming gap.

### OPTIONS
- **I-1** — add a state (e.g. `INCOHERENT`).
- **I-2** — a **port obligation**: the submitting mechanism must declare internal consistency, or declare insufficiency; the determination crosses as port vocabulary and never becomes a member.
- **I-3** — reject: internal consistency is the mechanism's obligation *before* submission; the domain admits such claims and records the limitation.
- **I-4** — treat it as a **justification-path defect**, so it resolves to `REJECTED` via the existing gate.

### PER-OPTION ANALYSIS

| | **I-1** new state | **I-2** port obligation | **I-3** reject/record | **I-4** as a path defect |
|---|---|---|---|---|
| What changes | states **7→8** | Port Contract §2 gains an obligation | nothing | nothing structural |
| Invariant affected | §9's closed set · OBS-2 | INV-KOS-VERIFICATION-001 (the gate) | none | INV-KOS-VERIFICATION-001 |
| Aggregate owning it | KnowledgeAggregate | none — the ACL enforces | none | KnowledgeAggregate |
| Consistency boundary | inside | **outside** (conformance ≠ admission) | n/a | inside |
| Semantic reasoning | **yes if the core detects it** — fatal | **no** — the mechanism determines, the core records | no | **yes** — deciding a path is incoherent requires reading it |
| Authority introduced | no | the mechanism's declaration is trusted → a trust-boundary move | no | no |
| Evidence custody | no | no | no | no |
| Hidden state | no | no | **the defect is invisible** — no symptom at all | no |
| Identity semantics | unchanged | unchanged | unchanged | unchanged |
| Amendment required | **yes** | **no** (Port Contract is ⬜ unratified — rides on that act) | **no** | **no**, but see falsification |
| Determinism | unaffected | unaffected | unaffected | unaffected |
| Anti-reasoner | **breached if the core detects** | preserved | preserved | **breached** |

### ZERO TEST
- **What is not represented?** Intra-claim incoherence. The model represents inter-claim conflict only.
- **What distinction has silently collapsed?** *Inconsistent-with-another* vs *inconsistent-with-itself*. One word — "contradiction" — covers both in ordinary usage, and the model expresses only the first.
- **What assumption is required but unstated?** **That submitted claims are internally coherent.** Nothing states it; no obligation requires it; no test detects its violation.
- **What failure case passes undetected?** **This one, completely.** A self-contradictory claim with a complete justification path is admitted, receives an identity, gets a state, and enters History. Nothing is wrong at any step. **This is the only item in the whole set whose drift produces no symptom.**
- **What would an implementation invent?** Nothing — which is precisely the danger. There is no pressure to invent, so the gap persists silently.

### FALSIFICATION
**Against I-1:** *can a new state be assigned without reading meaning?* **No.** To set `INCOHERENT` the boundary must know the claim is incoherent, which requires reading content — breaching §16 and Test F. **I-1 is falsified unless the determination is supplied**, at which point it collapses into I-2.
**Against I-2:** *does a trusted mechanism declaration violate anything?* Obligation 3 already establishes the pattern (a mechanism declares insufficiency and the core acts on the declaration without verifying it). So the pattern is lawful. **But:** r4-4 requires the declaration to remain **port vocabulary, never a member** — so the core may *refuse* on it, and may not *record* it as state. **I-2 survives, narrowed: it can gate, not label.**
**Against I-3:** *can the domain live with it?* Yes — and INV-KOS-VERIFICATION-001 is not breached, since a well-formed path *is* preserved. **But the ruling then asserts that the domain's notion of justification does not include the claim's internal coherence**, which is a substantive epistemic position and should be stated as one. **I-3 survives, at the cost of an explicit position.**
**Against I-4:** *is incoherence a path defect?* Only if the path is required to *entail* the conclusion — which would make the core check entailment, i.e. reason. **I-4 is falsified on the anti-reasoner constraint.**
- *Two interpretations, different outcomes?* Yes: "the path is complete" (structural) vs "the path is sound" (semantic). The boundary checks the first. Whether incoherence is a *structural* or *semantic* defect is exactly the unresolved point.
- *Unowned transition?* No.
- *Impossible aggregate state?* I-1 risks one — could `INCOHERENT` coexist with `VALIDATED`?
- *History · identity?* Unaffected throughout.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **I-1** | states 7→8; only viable if the determination is supplied, which makes it I-2 | **CONSTITUTIONAL AMENDMENT** |
| **I-2** | a seventh port obligation; the ACL can refuse; **the core never labels incoherence** | **LOGICAL ARCHITECTURE CHANGE** (Port Contract, ⬜ unratified) |
| **I-3** | the limitation is recorded; the domain's justification notion is stated as structural | **NO LAW CHANGE** |
| **I-4** | the core would have to check entailment | **CONSTITUTIONAL AMENDMENT** (§16 would have to be weakened) |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **If recording:** `RECORD ONLY — internal coherence of a claim is a mechanism obligation prior to submission, not a domain determination. The boundary checks that a justification path is present and well-formed, not that it is sound; §16 forbids the boundary from generating the conclusion that a claim is incoherent. The limitation is accepted and recorded, and it is noted that this failure mode produces no operational symptom.`
> **If adding a port obligation:** `ACCEPT — a seventh port obligation requiring a declared internal-consistency determination; the declaration is port vocabulary (r4-4) and never becomes a member; refusal occurs at the ACL. Rides on Port Contract ratification.`
> **If amending:** `AMENDMENT REQUIRED — new state; note that assignment requires a supplied determination, since detection by the boundary would breach §16.`

### UNRESOLVED
1. Whether **internal coherence** is part of the domain's notion of justification at all. **This is the substantive question, and no delivered artifact addresses it.**
2. Whether *"complete and well-formed"* (the boundary's structural check) should include **non-contradiction of the conclusion with the premises** — which sits exactly on the structural/semantic line.
3. If I-2 is taken: whether a *refusal* on a supplied incoherence declaration is a **pre-domain refusal** (⟨Z-1⟩, no event) or a **`KnowledgeRejected`**. Not decided by anything.

---

# 4 · F-CM-1a · AMBIGUITY ≠ CONTRADICTION

### QUESTION
Should *Ambiguity ≠ Contradiction* be **recorded** as a non-collapse distinction in §15 — separately from where interpretation-**selection** sits, which is F-CM-1b (Wave 2)?

### OBJECT
**O-5 — the Constitution**, specifically §15's non-collapse list.

### ALTITUDE
**Constitution** (an interpretation-class row, in the family of ⟨C-1⟩ · ⟨R-1⟩ · ⟨C-5⟩ · the three ⟨r4⟩ rows).

### EXISTING LAW
- **§15** — **eleven** non-collapse distinctions. *Ambiguity ≠ Contradiction* is **not** among them. The three ⟨r4⟩ rows (*Probability ≠ Truth · Canonicalization ≠ Authority · Low entropy ≠ Certainty*) establish the precedent that such rows are **"interpretations of existing invariants, no new invariant."**
- **INV-KOS-CONTRADICTION-001** — governs *conflicting knowledge*: two claims that cannot both hold.
- **INV-KOS-UNKNOWN-001 + ⟨C-5⟩** — *"a mechanism's inability to determine meaning is a first-class output that maps to **UNKNOWN** — never to ABSENT, never to FALSE, and never to a low-confidence acceptance."*
- **Port obligation 3** — a mechanism **declares its own insufficiency**; abstention is first-class.
- **⟨C-1⟩** — *"canonical-form equality is a similarity claim, not an identity determination"*; similarity never becomes identity.
- Token check: `ambiguity` appears in v1.1 **twice** — the §13 LLM row and OQ-4's corpus requirements — **never as a modelled distinction**.

### EXISTING EVIDENCE
The capability mapping declared it unanswerable from law and refused to resolve it; the critique **confirmed the absence by direct search**, and established that the intuitive route (treat competing interpretations as `CONFLICTED`) requires the core to judge two candidates *about the same thing* — a similarity determination ⟨C-1⟩ forbids.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
**Anticipated most heavily of all — five independent artifacts reach for an explicit `AMBIGUOUS` state or verdict:** `103255` (*"Ambiguity → explicit state | **Very high**"*; `{MATCH|MISMATCH|AMBIGUOUS|UNRESOLVED}`) · `104148` · `104251` · `111647` · `123630`. **`105200` §10 supplies the layered counter-proposal:** *"Interpretation: UNRESOLVED, AMBIGUOUS / Governance: NOT_AUTHORIZED… / Domain: admissible/rejected — one generic status enum would probably be a mistake."* **P5 Established item 7** is the affirmative evidence: SNF-C's one measurable advantage was recognising an omitted kāraka as **underdetermined rather than resolvable**.

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity / VO** | none — this is a *distinction*, not an object |
| **Event** | none |
| **Relation** | the point of the distinction: *ambiguity* is a relation between an **expression and several meanings**; *contradiction* is a relation between **two claims about one meaning**. **Different relata** |
| **Policy** | the port already carries the policy for the ambiguity case (obligation 3 → `UNKNOWN`) |
| **Aggregate responsibility** | none — a §15 row constrains, it does not confer |
| **Invariant** | it is an **interpretation** of INV-KOS-IDENTITY-001 (Article 1.2/1.4) and INV-KOS-CONTRADICTION-001, not a new invariant |
| **Consistency boundary** | not applicable |

**Decisive DDD point:** the two concepts have **different relata** — expression↔meanings versus claim↔claim. A model that expresses only the second cannot express the first, and nothing in §15 currently forbids conflating them.

### OPTIONS
- **A-1** — record as a §15 row (interpretation-class, like ⟨r4⟩).
- **A-2** — rule that ⟨C-5⟩ + obligation 3 already cover the ambiguity case, and the absence of a row is **deliberate**.
- **A-3** — defer until F-CM-1b (selection placement) is settled.

### PER-OPTION ANALYSIS

| | **A-1** record the row | **A-2** rule the absence deliberate | **A-3** defer |
|---|---|---|---|
| What changes | §15 gains a twelfth row | nothing; a ruling of record | nothing |
| Invariant affected | **none created** — an interpretation of IDENTITY-001 + CONTRADICTION-001 | same two, as read | none |
| Aggregate owning it | none | none | none |
| Consistency boundary | none | none | none |
| Semantic reasoning | **no — it forbids some**, by making the collapse citable | no | no |
| Authority introduced | no | no | no |
| Evidence custody | no | no | no |
| Hidden state | no | **risk**: future actors re-derive the answer each time, inconsistently | same risk, deferred |
| Identity semantics | **clarified** — ambiguity is an identity question, not a truth question | unclarified but unchanged | unchanged |
| Amendment required | **yes**, interpretation-class (⟨r4⟩ precedent: *"no new invariant"*) | **no** | **no** |
| Determinism | unaffected | unaffected | unaffected |
| Anti-reasoner | **strengthened** — the row is citable against a boundary that compares candidates | unchanged | unchanged |

### ZERO TEST
- **What is not represented?** The state of *several viable meanings, each independently justified*. `UNKNOWN` covers *underdetermined* (obligation 3), not *plural-and-determinate*.
- **What distinction has silently collapsed?** Precisely this one. §15 forbids eleven collapses and not this.
- **What assumption is required but unstated?** That every expression yields **at most one** admissible meaning. Nothing states it, and F-3 (*natural language is one representable surface*) does not imply it.
- **What failure case passes undetected?** Two mechanisms submit two viable meanings of one expression. Without a row, an implementation may reasonably treat them as `CONFLICTED` — **which requires the core to judge them co-referential, breaching ⟨C-1⟩.** Only T-2 would catch it, and T-2 depends on C-2.
- **What would an implementation invent?** An `AMBIGUOUS` state — as **five** independent corpus artifacts did — which OBS-2 and §9 forbid.

### FALSIFICATION
**Against A-1:** *does the row create new law?* The ⟨r4⟩ precedent says rows of this kind are *"interpretations of existing invariants, not new law."* So no. *Does it settle F-CM-1b by implication?* **No — and this must be stated**, or recording the row will be read as ruling that selection is external. **A-1 survives, provided its ruling explicitly does not decide F-CM-1b.**
**Against A-2:** *do ⟨C-5⟩ + obligation 3 actually cover the case?* They cover **underdetermination** — the mechanism could not determine. They do **not** cover **plural determinacy** — the mechanism determined several, each justified. **A-2 is falsified on that gap**, unless the ruling also states that plural determinacy must be reduced to a single candidate or to `UNKNOWN` before submission, which is a substantive addition, not merely a reading.
**Against A-3:** *is deferral safe?* It leaves the highest-drift item in the set (F-CM-1, 🔴) unaddressed while C-4 and C-16 remain blocked behind F-CM-1b. **A-3 survives but has the worst blocking profile in Wave 1.**
- *Two interpretations, different outcomes?* Yes, and that is the finding: *ambiguity* reads as a truth question or an identity question depending on the reader.
- *Can it make the Kernel semantic?* A-2 can, indirectly, by leaving the conflation available. A-1 reduces that risk.
- *Unowned transition · impossible state · history · identity?* Unaffected by all three options.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **A-1** | §15 has twelve rows; the collapse becomes citable; F-CM-1b remains open | **CONSTITUTIONAL AMENDMENT** (interpretation-class) |
| **A-2** | the absence is deliberate and recorded; every later actor must re-derive that `UNKNOWN` is the whole answer | **NO LAW CHANGE** |
| **A-3** | nothing settled; F-CM-1b, C-4, C-16 stay blocked | **DEFERRED** |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **If recording:** `RECORD AS §15 ROW — "Ambiguity ≠ Contradiction": which meaning an expression carries is an identity question (Article 1.2, 1.4); which of two claims about one meaning holds is a truth question (Articles 6, 9). Interpretation of existing invariants, no new invariant (⟨r4⟩ precedent). This ruling does NOT decide where interpretation-selection sits; F-CM-1b remains open.`
> **If rejecting:** `REJECT — ⟨C-5⟩ and port obligation 3 are sufficient: a mechanism that cannot determine meaning declares insufficiency and the candidate is admitted as UNKNOWN. Plural determinate meanings must be reduced before submission. The absence of a §15 row is deliberate.`

### UNRESOLVED
1. **Plural determinacy** — several viable, independently justified meanings of one expression. Neither `UNKNOWN` nor `CONFLICTED` fits, and **no option in F-CM-1a resolves it**; it is the substance of F-CM-1b.
2. Whether plural meanings, if admitted separately, produce **distinct `KnowledgeId`s** — F-CM-2, and consistent with ⟨C-1⟩ either way.
3. Whether a §15 row would need a matching **fitness test**, and if so whether it can be written without C-2.

---

### ⚖️ HPA RULING — F-CM-1a (2026-08-25) · **RULED**

> **RULING: O3 — DEFER / NO NEW §15 ROW AT THIS TIME.**
>
> **Rationale (verbatim).** *The existing constitutional model already distinguishes: similarity from identity; contradiction as a state of an identified epistemic object; mechanism-side interpretation/ambiguity handling; mechanism non-determination; and the prohibition on introducing an eighth epistemic state.*
>
> *The analysis has not demonstrated that an additional constitutional non-collapse row is necessary to preserve an invariant or prevent an otherwise lawful but materially different architectural interpretation.*
>
> *In particular, recording "plurality of determinate readings ≠ contradiction" would risk introducing terminology and semantics whose representation is precisely the subject of F-CM-1b.*
>
> *The repeated appearance of AMBIGUOUS in prior brainstorming artifacts is retained as evidence of **interpretation/implementation drift risk**, but is not treated as evidence of a constitutional defect.*
>
> ***F-CM-1b remains independently OPEN.***
>
> **This ruling does NOT determine:** whether plurality maps to `UNKNOWN` · whether multiple candidates may be admitted separately · whether distinct `KnowledgeId`s may be assigned · where selection occurs · **or which actor establishes semantic distinctness**. *Those questions remain exclusively within F-CM-1b.*

**Effect on the option set.** **A-3 (defer) is taken.** A-1 (record the row) is not taken. **A-2 is not taken either** — and the distinction matters: the ruling does **not** assert that ⟨C-5⟩ + obligation 3 *cover* plural determinacy, which is the ground on which this analysis falsified A-2. It rules instead on **insufficient demonstration of necessity** and on **sequencing** (the terminology belongs to F-CM-1b). The A-2 falsification therefore stands unrebutted and is not contradicted by the ruling.

**Consequences recorded.**
1. **§15 remains at eleven rows.** No amendment. Register **25+4** unchanged · invariants **11→11** · states **7→7**. The `AMBIGUOUS`-state route is **doubly closed**: no §15 row, and §9/OBS-2's *"No eighth state is introduced."*
2. **F-CM-1b is OPEN and has been WIDENED by this ruling.** Four of the five reserved questions were already within its scope; **"which actor establishes semantic distinctness" is new** and is recorded as an addition to F-CM-1b's scope.
3. **C-4 and C-16 remain blocked** behind F-CM-1b. The deferral does not unblock them.
4. **The `AMBIGUOUS` finding is retained on the record as implementation-drift risk**, with the governing distinction stated by the HPA: **repeated confusion ≠ constitutional gap.**
5. ⚠️ **Mitigation status of the highest-drift item.** F-CM-1 carries 🔴 HIGH drift risk (an implementation that meets two viable interpretations may compare them, breaching ⟨C-1⟩). Its **constitutional** mitigation is now deferred. The remaining mitigation is **T-2 SEMANTIC-INVARIANCE**, which **depends on C-2 (determinism), which is blocked by C-18** — both unruled. **So at this moment the highest-drift item in the set has no active mitigation.** Recorded as a consequence, not as an objection; it bears directly on C-18 and C-2 when those are reached.

### 🔧 EVIDENCE CORRECTION to §4 — the `UNKNOWN` dual use *(added 2026-08-25, law-derived only)*

**Commissioned by the HPA as an annotation-only evidence correction. The F-CM-1a ruling is NOT reopened and NOT modified. This corrects the evidence base beneath it.**

#### C-1 · `UNKNOWN` carries two distinct uses in v1.1 — verified from law alone

| Use | Where | What it is |
|---|---|---|
| **(a) a STATE of an identified epistemic object** | §9 — *"**UNKNOWN is the initial state**, not a failure state"* · ⟨Z-1⟩ (r5) — *"`UNKNOWN` · `ABSENT` · `FALSE` · `REJECTED` are states **of an identified epistemic object**"* | presupposes an **admitted** object that already bears a `KnowledgeId` |
| **(b) the MAPPING TARGET for mechanism non-determination** | §14 ⟨C-5⟩ — *"a mechanism's inability to determine meaning is a first-class output that **maps to UNKNOWN**"* · INV-KOS-UNKNOWN-001 carries ⟨C-5⟩ · port obligation 3 | a **disposition of a candidate at the port**, before any object exists |

**These are not the same thing.** (a) is a value the domain assigns to something it has admitted. (b) is what the domain does with a candidate whose meaning a mechanism could not determine. The r5/⟨Z-1⟩ correction made (a) explicit — *states of an identified epistemic object* — which is precisely what sharpens the distinction.

#### C-2 · Correction to this analysis's §4

**What §4 did:** it cited use **(b)** — *"⟨C-5⟩ + obligation 3 give underdetermination a home: declared insufficiency → `UNKNOWN`"* — **without flagging that this is a different use from (a).**

**Why that is a defect and not a quibble:** an argument of the form *"`UNKNOWN` already covers ambiguity"* moves silently between the two uses. Under **(b)** it is a claim about a **port disposition**; under **(a)** it is a claim about an **admitted state**. Establishing the first does not establish the second.

**Consequence, stated as the HPA did:** **`UNKNOWN` cannot be used to answer F-CM-1a**, because which of its two uses is meant is exactly what **F-CM-1b** decides. Any F-CM-1a argument resting on `UNKNOWN` assumes F-CM-1b's answer.

#### C-3 · Mapping versus prohibition — the separation §4 under-drew

**F-CM-1a asks for a PROHIBITION**: should §15 record that a collapse is forbidden? **F-CM-1b asks for a MAPPING**: how is plurality represented, and by whom?

§4's option **A-2** (*"⟨C-5⟩ + obligation 3 already cover it"*) is phrased as a **coverage** claim — and **coverage is a mapping claim, not a prohibition claim.** So A-2 could never have been settled inside F-CM-1a: it required a mapping decision that belongs to 1b.

**This strengthens the A-2 falsification rather than weakening it.** §4 falsified A-2 on the narrower ground that ⟨C-5⟩ covers *underdetermination* but not *plural determinacy*. The correction adds a structural ground: **A-2 is category-mismatched to the question it was offered against.**

#### C-4 · Effect on the ruling: none

**The ruling stands unchanged, and is better supported after this correction.** Its own stated reason — that recording *"plurality of determinate readings ≠ contradiction"* would introduce terminology *"whose representation is precisely the subject of F-CM-1b"* — is exactly what C-1 through C-3 demonstrate from law. **A-3 (defer) remains correct; A-2 remains unavailable; A-1 remains undemonstrated.**

#### C-5 · What this correction does NOT contain, and why

The HPA's commission also listed items requiring the **second brainstorming corpus**: an Aug-25 `Interpretation "*" --> "*" Proposition` model · catuṣkoṭi-style challenges to `UNKNOWN`'s precision · a *sub-distinction-of-UNKNOWN* decision option · and "Findings A–E" from a review.

**None of those is included here.** Three reasons, recorded:

1. **The seal.** The HPA's research-sequencing notice (recorded at §7.3) directs that the second corpus **not be requested, inferred, reconstructed or incorporated during adjudication**, and that it enter only at the later falsification phase. Incorporating it now would breach that instruction.
2. **Provenance.** The artifact cited as *"the review"* was checked. `docs/knowledgeos/brainstorming/kernel/20260824-020611-wave-1-kernel-extent-versus-contents-adjudication-status.md` (58 lines, mtime 02:06) **opens with this session's own prior chat response verbatim** — the same text as the untracked stray `docs/knowledgeos/architecture/Stopped and waiting. Nothing in flight;` (02:05). **It is this session's output round-tripped into the corpus, not an independent review.** Building an evidence correction on it would create a provenance loop in which this session's own text re-enters as external evidence.
3. **Visibility.** The second corpus is **114 files / 135,682 lines** in `docs/knowledgeos/brainstorming/kernel/` on branch **`election-review`**. **Zero of those files exist in this worktree** (`kos-v11-ddd-refinement`), which is why no prior act of this session has seen or cited them.

**Scope decision reserved to the HPA:** whether to unseal the second corpus for the bounded purpose of correcting F-CM-1a/1b evidence, or to keep it sealed until the falsification phase. **Until that ruling, the corpus-derived items are recorded as pending, not as findings, and the `UNKNOWN` sub-distinction is NOT recorded as a proposed state** — per the HPA's instruction that a new distinction must not become an inevitable new state.

---
### 📜 RULING-DERIVED GOVERNANCE PRINCIPLE — the constitutional sufficiency test

The HPA established a principle of programme-wide effect alongside this ruling. Recorded here because it governs the assessment of every remaining item:

> **Record a new constitutional distinction only when the existing law is insufficient to prevent materially different architectural interpretations.**
>
> **The absence of an explicit distinction in the Constitution does not automatically justify adding one.**

```
Existing law
    ↓
Is the existing invariant sufficient?
    │
    ├── YES → clarify elsewhere / defer
    │
    └── NO  → constitutional amendment candidate
```

**Why it matters, in the HPA's own terms:** without it, every interesting distinction discovered by philosophical or epistemological analysis will attempt the escalation **Entity → VO → State → Event → Invariant → Constitutional Article** — *"and that would destroy the smallest possible Kernel objective."* The Constitution must not become a catalogue of every possible semantic confusion.

**Application to the remaining items.** The test now applies to every amendment-class option in the workbook — in Wave 1: **C-15** (R-1/R-2), **C-17** (I-1), **C-18** (V-1), **C-14** (N-2). For each, the burden is **demonstrated insufficiency of existing law**, not the attractiveness of the clarification. *This analysis does not apply the test on the HPA's behalf; it records that the burden now exists.*

---

# 5 · C-18 · CONSTITUTIONAL-VERSION BINDING

### QUESTION
Must an admitted state record **which version of the law admitted it** — and if so, is that binding a **domain fact** (a member / an `EvidenceLink` / `History`) or **infrastructure metadata** outside the boundary?

### OBJECT
**Trigger at O-3** (the §16 altitude — amendment is possible). **Remedy at O-2** (the KnowledgeAggregate). The two sit at different altitudes; this is the workbook's split-altitude item.

### ALTITUDE
**Constitution** if a new member; **aggregate/member** if via `EvidenceLinks` or `History`; **realization** if infrastructure metadata.

### EXISTING LAW
- **§16** — *"**Nothing moves between altitudes except by constitutional amendment.**"* The KERNEL altitude: *"may it change? **No** — amendment by the HPA only."* **So amendment is lawful, and r5 has demonstrated that v1.1 revises.**
- **INV-KOS-HISTORY-001** — *"Revision never deletes; supersession is forward-only."* It preserves **the transition**, not the governing law.
- **§6 EvidenceLinks** — *"evidence **references** + acquisition method + reliability conditions"*; ⟨C-3⟩ — the core owns the links, never the content.
- **§6 History** — *"the forward-only revision log — every prior state retained"*.
- **Appendix A** — the r3, r4 and **r5** ledgers exist; v1.1 is versioned in practice.
- **OQ-2 ruling** — SNF-equivalence may be recorded **as an EvidenceLink** supporting an identity-assignment act without becoming an identity mechanism. **Precedent: a fact about the basis of an act may lawfully live in `EvidenceLinks`.**

### EXISTING EVIDENCE
The critique recorded the gap; the ADR notes r5 made it concrete. Nothing in the model binds an admitted state to a law version. The workbook records that C-18 **blocks C-2**: determinism across an amendment is unverifiable without it.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
**Anticipated three times:** `104148` §2 — constitutional rules are *"Versioned (they evolve over time, like a real constitution)"* · `110248` §12 — *"the constitution is versioned. The Kernel applies the current version. If the constitution changes, the Kernel references the new version"*, **and warns that *KnowledgeOS history ≠ Kernel software history*, though they may be linked through provenance/evidence** · `110950` §3 asks the unanswered set: *"Who determines the authoritative constitutional version? Who certifies it? How is it identified? Can an arbitrary caller supply rules? Can rules contradict the Kernel's immutable invariants?"*

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity** | none — the Constitution is not a domain entity of KnowledgeCore |
| **Value object** | a *law-version reference* is VO-shaped: immutable, no identity of its own, meaningful only in context |
| **Event** | none new — the binding would be recorded at the existing admission event |
| **Relation** | *admitted-under* — a relation between a state and a law version. Relations to non-aggregates are references, per ⟨C-3⟩'s pattern |
| **Policy** | *which version applies* is a governance policy, **not** a domain rule |
| **Aggregate responsibility** | the KnowledgeAggregate would own the **reference**, never the law |
| **Invariant** | INV-KOS-VERIFICATION-001 — *"no entry into knowledge without a preserved justification path"*. **The sharpest question in this item: is the governing law part of the justification?** |
| **Consistency boundary** | **atomicity holds if it is part of justification**: a state admitted under law L, recorded without L, cannot have its justification reconstructed |

**Decisive DDD point:** the whole item turns on **whether the governing law version is part of the justification path**. If it is, atomicity requires it inside and `EvidenceLinks`/`History` are the natural homes. If it is not, it is infrastructure and belongs outside. **Nothing in law answers this, and it is the substantive question.**

### OPTIONS
- **V-1** — a new member (e.g. `GoverningLaw`).
- **V-2** — an `EvidenceLink` recording the law version, on the **OQ-2 precedent**.
- **V-3** — a `History` entry recording the governing version at each transition.
- **V-4** — infrastructure metadata, outside the boundary.
- **V-5** — rule out of scope: the domain does not record it.

### PER-OPTION ANALYSIS

| | **V-1** member | **V-2** EvidenceLink | **V-3** History | **V-4** infrastructure | **V-5** out of scope |
|---|---|---|---|---|---|
| What changes | members **12→13** | no count change | no count change | nothing in the domain | nothing |
| Invariant affected | DIMENSION-001 (a new dimension) | VERIFICATION-001 (justification) | HISTORY-001 | none | none |
| Aggregate owning it | KnowledgeAggregate | KnowledgeAggregate | KnowledgeAggregate | none | none |
| Consistency boundary | inside, atomic | inside, atomic | inside, atomic | outside | n/a |
| Semantic reasoning | no | no | no | no | no |
| Authority introduced | **risk** — a version reference can read as an authority claim | no, if a plain reference | no | no | no |
| Evidence custody | no — a reference | **no**, and consistent with ⟨C-3⟩ | no | no | no |
| Hidden state | no | no | **mild** — visible only in History | **yes, from the domain's view** | no |
| Identity semantics | unchanged | unchanged | unchanged | unchanged | unchanged |
| Amendment required | **yes** | **no** (uses an existing member) | **no** | **no** | **no** |
| Determinism | **enables** verification across amendments | **enables** | **enables** | does not enable in-domain | **prevents** |
| Anti-reasoner | unaffected | unaffected | unaffected | unaffected | unaffected |

### ZERO TEST
- **What is not represented?** The basis on which an admission was lawful. After an amendment, the model cannot say which rules were applied.
- **What distinction has silently collapsed?** *Admitted-under-law-L* vs *admitted*. Also — flagged by the corpus — **KnowledgeOS history vs Kernel software history**; V-4 risks conflating them.
- **What assumption is required but unstated?** **That law is effectively static.** r5 has already falsified it.
- **What failure case passes undetected?** An invariant is amended; a prior admission would not be admissible under the new law; nothing records the discrepancy, and **no replay can distinguish "was lawful then" from "is unlawful now."**
- **What would an implementation invent?** A schema version or a migration marker in infrastructure — i.e. **V-4 by default**, unruled.

### FALSIFICATION
**Against V-1:** *is a law version a new dimension of knowledge?* Weak. INV-KOS-DIMENSION-001 governs dimensions of the *knowledge*; the governing law is a property of the **act**, not the claim. **V-1 is falsified on the minimality test: no invariant requires atomic consistency between the claim and its governing law version at member level, once V-2/V-3 can carry it.**
**Against V-2:** *is the law version evidence?* It is not evidence *for the claim*; it is evidence *about the admission*. `EvidenceLinks` is described as *"the justification: evidence references…"* — so this stretches the member's meaning. **But OQ-2 set the precedent that a fact about the basis of an identity-assignment act may live in `EvidenceLinks`.** **V-2 survives on precedent, at the cost of widening the member's scope — which should be stated.**
**Against V-3:** *does History carry it?* `History` is *"the forward-only revision log — every prior state retained"* — a log of **states**, not of governing rules. Recording law versions there widens it similarly. **V-3 survives with the same caveat as V-2.** Between them, V-3 is arguably the better fit: the binding is per-transition, which is exactly History's granularity.
**Against V-4:** *can the domain function?* Yes, operationally. **But it makes reproducibility a non-domain property**, and the corpus's own warning applies — software history and knowledge history must not be conflated. **V-4 survives only if reproducibility is explicitly not a domain guarantee**, which then answers C-2 by implication.
**Against V-5:** identical to V-4 minus the infrastructure record. **Strictly weaker; nothing recommends it over V-4.**
- *Two interpretations, different outcomes?* Yes: *is governing law part of justification?* Yes → V-2/V-3; No → V-4/V-5.
- *Impossible aggregate state?* No.
- *History preserved · identity assigned?* Unaffected throughout.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **V-1** | members 12→13; fails minimality if V-2/V-3 suffice | **CONSTITUTIONAL AMENDMENT** |
| **V-2** | uses an existing member; widens `EvidenceLinks`'s scope; **unblocks C-2** | **MAPPING CHANGE** |
| **V-3** | uses an existing member at the right granularity; widens `History`'s scope; **unblocks C-2** | **MAPPING CHANGE** |
| **V-4** | reproducibility leaves the domain; **answers C-2 by implication** (determinism cannot be a domain property across amendments) | **IMPLEMENTATION-ONLY** |
| **V-5** | as V-4, without even an external record | **NO LAW CHANGE** |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **If binding inside:** `ACCEPT — the governing law version is recorded at each admitted transition via [History / EvidenceLinks], on the OQ-2 precedent that a fact about the basis of an act may be recorded as a reference. No new member. The member's scope is widened by this ruling and the widening is recorded. C-2 is thereby unblocked.`
> **If placing outside:** `REJECT — the governing law version is infrastructure metadata, not a domain fact. It is noted that this makes reproducibility across an amendment a non-domain property, which bears directly on C-2.`
> **If amending:** `AMENDMENT REQUIRED — new member; note that the minimality test is not satisfied if History or EvidenceLinks can carry the binding.`

### UNRESOLVED
1. **Is the governing law version part of the justification path?** The substantive question; **nothing in law answers it**.
2. **Who certifies the authoritative version** — `110950`'s unanswered question, and an **authority** matter (→ C-3).
3. Whether a **prior admission unlawful under new law** must be re-examined, marked, or left — no option addresses this, and it interacts with **C-6** (staleness) and **C-7**.
4. Whether the r3/r4/r5 revision series is itself the versioning scheme, or whether a domain-facing identifier is needed.

---

# 6 · C-14 · `NOT_ASSESSED ≠ LOW_CONFIDENCE`

### QUESTION
Does the existing **deferral** of UQ-4 to Logical Architecture stand — or is *`NOT_ASSESSED ≠ LOW_CONFIDENCE`* a **non-collapse requirement** (F-CM-1a's class) rather than a representation choice?

**This is a reclassification question, not the substantive representation question.**

### OBJECT
**O-5** if reclassified (a §15 row); **O-2** if a Confidence representation; **realization** if the deferral stands.

### ALTITUDE
**Constitution or Logical Architecture** — the item *is* the altitude question.

### EXISTING LAW
- **§14 ⟨C-5⟩** — *"a mechanism's inability to determine meaning is a first-class output that maps to **UNKNOWN** — never to ABSENT, never to FALSE, and **never to a low-confidence acceptance**."* **The prohibition is already law.**
- **INV-KOS-UNKNOWN-001** — carries ⟨C-5⟩; *"uncertainty is preserved, never flattened."*
- **§6 Confidence** — *"a **structured** epistemic attribute… never a scalar replacing epistemic structure (Article 2.3)… **⟨R-1⟩ assigned INSIDE the boundary**"*.
- **INV-KOS-DIMENSION-001** — *"no scalar surrogate for structure."*
- **Port obligation 5** — a mechanism *"never emits a scalar in place of epistemic structure."*
- **§20 OQ-5** — ruled **ACCEPT**: Confidence remains a member.

### EXISTING EVIDENCE
The mapping recorded it as **UQ-4**; the HPA **deferred it to Logical Architecture**. The critique argued it is law-adjacent, in F-CM-1a's class, because the *prohibition* is law while the *positive representation* is undefined — and deferring the representation to realization is precisely where the collapse would be implemented.

### BRAINSTORMING EVIDENCE *(evidence, not authority)*
`123630` lists **`NOT_ASSESSED ≠ LOW_CONFIDENCE`** among six ZERO non-collapse pairs, **beside `UNKNOWN ≠ ABSENT`, which *is* law** — so the corpus treated them as the same kind of thing. `20260822-161933` promotes **abstention quality (`A_abstain`)** and **false collapse (FCR)** to first-class metrics, i.e. treats the collapse as the dangerous failure mode.

### DDD ANALYSIS
| Construct | Assessment |
|---|---|
| **Entity / VO** | `Confidence` is already a governed VO; *not-assessed* would be one of its values or its absence |
| **Event** | none |
| **Relation** | none |
| **Policy** | ⟨C-5⟩ is the policy and it exists |
| **Aggregate responsibility** | the KnowledgeAggregate owns `Confidence`; nothing new |
| **Invariant** | **already covered** by INV-KOS-UNKNOWN-001 + INV-KOS-DIMENSION-001. A §15 row would be an interpretation, not an invariant |
| **Consistency boundary** | unchanged |

**Decisive DDD point:** this is **not a modelling gap** — it is an **asymmetry**: the model forbids a value it cannot express the absence of. `Confidence` has no defined *not-assessed* representation, while ⟨C-5⟩ forbids the only representation an implementer would reach for (a low scalar).

### OPTIONS
- **N-1** — the deferral stands: UQ-4 remains a Logical Architecture question.
- **N-2** — reclassify: record a §15 row.
- **N-3** — rule that ⟨C-5⟩ suffices and no positive representation is owed.

### PER-OPTION ANALYSIS

| | **N-1** deferral stands | **N-2** §15 row | **N-3** ⟨C-5⟩ suffices |
|---|---|---|---|
| What changes | nothing now | §15 gains a row | nothing; a ruling of record |
| Invariant affected | none | interpretation of UNKNOWN-001 + DIMENSION-001 | same, as read |
| Aggregate owning it | KnowledgeAggregate (later) | none | KnowledgeAggregate |
| Consistency boundary | unchanged | none | unchanged |
| Semantic reasoning | no | no | no |
| Authority introduced | no | no | no |
| Evidence custody | no | no | no |
| Hidden state | **risk** — an implementation picks a nullable scalar | no | **same risk**, now sanctioned |
| Identity semantics | unchanged | unchanged | unchanged |
| Amendment required | **no** | **yes**, interpretation-class | **no** |
| Determinism | unaffected | unaffected | unaffected |
| Anti-reasoner | **indirect risk**: a nullable scalar invites derivation | **reduced** | unchanged |

### ZERO TEST
- **What is not represented?** *Confidence has not been assessed*, as distinct from *confidence is low*.
- **What distinction has silently collapsed?** Exactly this one — and it is a special case of the ⟨C-5⟩ family (*inability ≠ low value*) already law for **meaning**, not yet stated for **confidence**.
- **What assumption is required but unstated?** That `Confidence` is always assessable at admission. **C-11 shows it may not be assessable at all**, which makes *not-assessed* the likely normal case rather than an edge case.
- **What failure case passes undetected?** A consumer reads *not assessed* as *low* and treats the claim as weakly supported. Nothing detects it; no test exists.
- **What would an implementation invent?** A nullable scalar or a zero — **the collapse ⟨C-5⟩ forbids for meaning and does not explicitly forbid for confidence.**

### FALSIFICATION
**Against N-1:** *is Logical Architecture the right home?* A representation choice normally is. **But the ZERO test shows the choice is where the prohibited collapse occurs** — so deferring it defers the prohibition's enforcement to the layer least able to see it. **N-1 is weakened, not falsified**, and it is defensible if paired with a fitness constraint — which would require **C-2**.
**Against N-2:** *does a row add anything ⟨C-5⟩ lacks?* ⟨C-5⟩ names *meaning*; the row would name *confidence*. **If the HPA reads ⟨C-5⟩ as already covering any epistemic attribute, N-2 is redundant.** That reading is available and nothing forecloses it. **N-2 survives only if ⟨C-5⟩ is read narrowly (meaning only).** This is the crux, and it is a reading of existing law — properly the HPA's.
**Against N-3:** *is a prohibition without a representation enforceable?* Only negatively: an implementation can be told what not to do while having nothing to do instead. **N-3 is falsified on enforceability** unless it also states that absence of `Confidence` is itself the lawful representation — which is a substantive addition.
- *Two interpretations, different outcomes?* Yes, and it is the whole item: **does ⟨C-5⟩ cover confidence or only meaning?**
- *Can it make the Kernel semantic?* Indirectly — a nullable scalar invites derivation, which is **C-11**'s hidden-reasoner risk.
- *Unowned transition · impossible state · history · identity?* Unaffected throughout.

### DECISION CONSEQUENCE
| Option | Consequence | Class |
|---|---|---|
| **N-1** | UQ-4 stays open at Logical Architecture; the collapse risk sits at realization | **DEFERRED** |
| **N-2** | §15 gains a row; the collapse becomes citable for confidence as it is for meaning | **CONSTITUTIONAL AMENDMENT** (interpretation-class) |
| **N-3** | the prohibition stands with no positive representation owed | **NO LAW CHANGE** |

### RECOMMENDED HPA RULING FORMULATION *(candidate wording — not a decision)*
> **If confirming the deferral:** `CONFIRM DEFERRAL — UQ-4 remains a Logical Architecture question. It is recorded that the representation choice is where the ⟨C-5⟩-class collapse would occur, and that a fitness constraint may be required at that layer (dependent on C-2).`
> **If reclassifying:** `RECLASSIFY — record as a §15 row: "Not-assessed ≠ low confidence." Interpretation of INV-KOS-UNKNOWN-001 and INV-KOS-DIMENSION-001; no new invariant. ⟨C-5⟩ is thereby read as narrow (meaning), with this row extending the same discipline to confidence.`
> **If ruling ⟨C-5⟩ sufficient:** `REJECT — ⟨C-5⟩ already forbids any inability-to-low-value collapse across epistemic attributes, including confidence. No row and no positive representation are owed.`

### UNRESOLVED
1. **The scope of ⟨C-5⟩** — meaning only, or any epistemic attribute? **This single reading decides the item, and it is a reading of existing law.**
2. The **substantive representation** of a not-assessed `Confidence` — unresolved under every option, and **dependent on C-11** (if the member has no lawful input, the representation question may dissolve).
3. Whether a fitness test can express the distinction **without** C-2's determinism ruling.

---

## 7 · Wave 1 summary

| ID | Options | No-law-change option available? | Blocks | Highest-risk failure if left unruled |
|---|---|---|---|---|
| **Wisdom** | W-1 · W-2 · W-3 | **yes (W-1, W-3)** | nothing | none — inaction is safe |
| **C-15** | R-1 · R-2 · R-3 · R-4 | **yes (R-4)**, and R-3 needs no amendment | C-7 | retraction collapses into `REJECTED`; hidden state |
| **C-17** | I-1 · I-2 · I-3 · I-4 | **yes (I-3)** | C-7 | **no operational symptom at all** |
| **F-CM-1a** | A-1 · A-2 · A-3 | **yes (A-2)** | F-CM-1b → C-4 · C-16 | an implementation invents `AMBIGUOUS`, or compares candidates and breaches ⟨C-1⟩ |
| **C-18** | V-1 · V-2 · V-3 · V-4 · V-5 | **yes (V-2, V-3, V-4, V-5)** | **C-2 → the fitness family** | prior admissions unreproducible after the first amendment |
| **C-14** | N-1 · N-2 · N-3 | **yes (N-1, N-3)** | nothing | a nullable scalar implements the forbidden collapse |

### 7.1 Cross-item interactions found during this analysis
1. **C-15 and C-17 both propose additions to the same closed seven-state set.** Neither alone is large; **together they would take it to nine and weaken the closed-set discipline that INV-KOS-UNKNOWN-001's *unknown ≠ absent ≠ false* relies on.** They should be read together even though they are independent.
2. **C-18's V-4/V-5 options answer C-2 by implication** — if reproducibility is not a domain property, determinism cannot be a boundary property. **A C-18 ruling may therefore pre-decide part of C-2.** Flagged because the commission forbids a lower-level decision silently deciding a higher one.
3. **C-14 is more dependent on C-11 than the workbook recorded.** If C-11 concludes `Confidence` has no lawful input, *not-assessed* becomes the **normal** case, not an edge case — which changes C-14 from a representation detail to the member's primary state.
4. **C-15 and C-17 both raise authority questions that land on C-3** (who may retract; whose incoherence declaration is trusted). Wave 1 cannot close either without Wave 2.
5. **F-CM-1a's ruling must explicitly not decide F-CM-1b**, or recording the row will be read as ruling selection external.

### 7.2 What Wave 1 cannot settle
- **F-CM-1b** (selection placement) — Wave 2.
- **C-3** (authority adequacy) — Wave 2; needed by C-15 and C-17.
- **C-11** (Confidence derivability) — Wave 3; needed by C-14's substance.
- **C-2** (determinism) — Wave 5; partially pre-decided by C-18's outcome.
- **C-7** (state/record cardinality) — Wave 4; receives C-15's and C-17's additions.

---

## 7.3 · RESEARCH-SEQUENCING NOTICE — phase separation preserved

**Recorded on the HPA's notice of 2026-08-24, issued during this act.** A **second, substantially expanded brainstorming corpus** exists — additional books, philosophical lenses, epistemological analysis, DDD reinterpretations, Zero-lens analysis and cross-lens synthesis — developed *after* the current formal analysis and **not yet incorporated**.

**Declaration for this act.** This Wave 1 analysis **did not request, infer, reconstruct or incorporate that corpus.** It operated only on: existing KnowledgeOS law (v1.1 **r5**) · the delivered formal architecture evidence · the boundary definition · the capability mapping · the independent DDD critique · the adjudication pack and referent map · the adjudication workbook · and the **first** brainstorming corpus, which was already admitted as **evidence, never authority**, by the pack and workbook. **No source outside that set was used.**

**The phase separation to be preserved — do not collapse these:**

```
FORMAL ARCHITECTURE
      ↓
HPA ADJUDICATION            ← we are here (Wave 1 delivered)
      ↓
ADJUDICATED KERNEL
      ↓
SECOND BRAINSTORMING CORPUS ← introduced only here
      ↓
INDEPENDENT MULTI-LENS FALSIFICATION
      ↓
FINAL ARCHITECTURAL CONSOLIDATION
```

**The second corpus's later purpose is to ATTACK the adjudicated Kernel, never to design or enlarge it** — asking whether any independent lens exposes a missing distinction · a missing invariant · an unjustified aggregate member · hidden semantic authority · an unrepresented failure state · an implicit assumption; and whether the boundary survives independent epistemological attack.

> **Future research is not present architectural authority.** An adjudication that anticipated the second corpus would let unadmitted material decide a live question — the precise failure this sequencing exists to prevent. Wave 1's options and falsifications therefore stand on the authorized set alone, and remain open to attack by the later phase.

---

## 8 · Stop

**Wave 1 analysis complete. STOPPING as instructed.** Wave 2 is not begun and will not be until the HPA has ruled.

**No ruling field is filled. No decision is marked accepted. No decision is committed on the HPA's behalf.** The recommended formulations in each item are **candidate wordings** for whichever answer the HPA chooses — including, in every case, wordings for options this analysis found weaker under falsification.

Nothing implemented · Constitution **not modified** · v1.1 not modified · no aggregate, member, event, state or context created · no research opened · no brainstorming reopened · **no philosophical lens used as architectural authority** · no implementation architecture. Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · **the Kernel is not built.**

---

## Traceability

- **Commission:** HPA, 2026-08-24 — continue from the workbook; use its dependency graph; begin with Wave 1 (Wisdom · C-15 · C-17 · F-CM-1a · C-18 · C-14); the full per-item structure; ZERO test; **falsification of every option without defending the current architecture**; decision consequence in seven classes; recommended ruling **formulation** only; unresolved list; **STOP after Wave 1**.
- **Authoritative law quoted or referenced:** v1.1 **r5** §4.1 · §6 (Confidence · EvidenceLinks · History · TemporalValidity) · §6.1 · §8 (the ten events · `KnowledgeRejected` post-r5 · ⟨A-3⟩ · the `WisdomDerived` guard) · §9 (the seven states · the post-r5 bullets · ⟨Z-1⟩ · *"No eighth state is introduced"*) · §14 ⟨C-5⟩ · §15 (the eleven rows · the ⟨r4⟩ precedent) · §16 (*"nothing moves between altitudes except by constitutional amendment"*) · §17 · §20 (OQ-2 · OQ-5) · Appendix A (r3/r4/r5 ledgers) · Appendix B (OBS-2) · INV-KOS-IDENTITY-001 · DIMENSION-001 · AUTHORITY-001 · VERIFICATION-001 · FAILURE-001 · CONTRADICTION-001 · UNKNOWN-001 · AGENCY-001 · HISTORY-001 · Port Contract r4 obligations 3 · 5 and r4-4 (contract ⬜ **PROPOSED**).
- **Governed evidence:** Boundary Definition + HPA ruling + r5 (`20260823-1306`) · Capability Mapping (`20260823-2103`) · Independent Critique (`20260823-2154`) incl. §25 · Scope ADR (`20260823-2229`) · Adjudication Pack + Referent Map (`20260823-2249`) · **Adjudication Workbook (`20260823-2334`)** · P5 acceptance (`20260822-2327`) · AH confirmation (`20260822-2346`).
- **Brainstorming cited as evidence only, never authority:** `103255` · `104148` · `104251` · `105200` · `kernel/110248` · `kernel/110950` · `kernel/111647` · `kernel/113410` · `kernel/123619` · `kernel/123630` · `20260822-161933`. **No observation was promoted to law; the corpus's own meta-rule (*convergence is not authority*) was applied.**
- **Status:** 📋 **DECISION SUPPORT ONLY · PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** 6 items · 22 options · 5 cross-item interactions found · **STOPPED after Wave 1.** The Kernel is not built.
