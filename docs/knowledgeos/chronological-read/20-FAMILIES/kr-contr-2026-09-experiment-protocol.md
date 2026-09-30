# kr-contr-2026-09-experiment-protocol

**Scope(s):** METHODOLOGICAL · **Row count:** 77 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Contr(p,K)=Pos(p,K)∧Neg(p,K)`, `KR-CONTR-2026-09`, `Sep_R(x,y)`, `{T,F,U,C}` · **Aliases:** `Contradiction, Evaluation Domain and Zero — Adversarial Semantic Separation Experiment`
**Candidate group membership (NOT an identity claim):**
- **G0585**: candidate group with `explicit-belief-b-operator-four-valued` — explicit agent-stated uncertainty: 'explicit-belief-b-operator-four-valued' POSSIBLY relates to 'kr-contr-2026-09-experiment-protocol' (batch B0061). Note: Tractable alternative to K using four-valued situations (true-only/false-only/both/neither) where beliefs are NOT closed under implication or logical consequence (avoiding logical omniscience), with complexity results (co-NP-complete propositional tautological entailment; polynomial CNF; undecidable/decidable first-order variants depending on existential generalization); directly parallels the project's own FDE (four-valued) evaluation work. (mechanical signal only; relationship not yet decided, P3).
- **G1782**: candidate group with `kr-contr-two-models-result` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).
- **G1786**: candidate group with `w-kr-contr-eval-structured-evaluation-result` — labels co-occur in the same contribution's labels[] 4 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0060, scope METHODOLOGICAL): The full commissioning prompt for the KR-CONTR-2026-09 experiment, designed to discover the minimum evaluation-domain machinery needed to represent contradiction without collapsing it into other epistemic-non-satisfaction forms; approved by HPA review in the same file; executed and evaluated in later B0060 files (V-contr-experiment, W-KR-CONTR-EVAL, X-KR-COMP, Y-KR-COMP-SEP).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2484] §"> **Can contradiction be represented without confusing it with uncertainty, absence, insufficient evidence, theory incompleteness, unobservability, or non-assessment?**"
- CANDIDATE-CONCEPTUAL-BIRTH: [S2489] §"$$
Extension(P)
$$

and

$$
AntiExtension(P)
$$

The same object can occur in both."
- CANDIDATE-FORMAL-BIRTH: [S2484] §"$$
Contr(p,K_t)
\iff
Pos(p,K_t)\land Neg(p,K_t).
$$"
- CANDIDATE-OPERATIONAL-BIRTH: [S2484] §"> **Can contradiction be represented without confusing it with uncertainty, absence, insufficient evidence, theory incompleteness, unobservability, or non-assessment?**"
- CANDIDATE-GOVERNANCE-BIRTH: [S2484] §"```text
A. THREE-VALUED DOMAIN SUFFICIENT
B. FOURTH VALUE REQUIRED
C. STRUCTURED EVALUATION REQUIRED
D. MORE THAN FOUR SEMANTIC VALUES REQUIRED
E. CONTRADICTION SHOULD BE REPRESENTED OUTSIDE Sat
F. QUESTION REMAINS UNDERDETERMINED
```"

## Lifecycle
last_seen: S2746. Candidate lifecycle: CONTESTED. Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S2484, S2490 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2484, S2489, S2501 |
| Type signature | PRESENT | S2484 |
| Invariants | PRESENT | S2484, S2501 |
| Dependencies | PRESENT | S2484, S2488, S2489, S2490, S2496, S2497, S2499, S2500, S2501 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2484, S2488, S2489, S2490, S2497, S2501 |
| Examples | PRESENT | S2490, S2501 |
| Warnings | PRESENT | S2484, S2488, S2489, S2490 |
| Experiments | PRESENT | S2484, S2489, S2501, S2746 |
| Open questions | PRESENT | S2484 |

## Rationale
Specifies the four candidate representations to test fairly: A) extend {T,F,U} to {T,F,U,C}, testing whether C is primitive/derivable/needs provenance or polarity/can coexist with U; B) keep Sat in {T,F,U} and represent Contr(p,K) as an independent relation outside Sat; C) exclusive-by-construction (bot ≡ not-p∈K ∧ p∉K), explicitly flagged as expected-vulnerable to silently converting contradictory into false/absent/invalid; D) a structured EVal=(value,reason,provenance,evidence,context,time,polarity) tuple whose exact fields must be discovered by reduction [S2484]. Analyzes Arjuna's viṣāda (Gita 1.28-47) as independently arriving, roughly 2000 years earlier, at exactly the KR-CONTR-2026-09 protocol's required-distinction list: his state is not uncertainty, not absence, not insufficient evidence, not unobservability, and not non-assessment -- corroboration of the distinctions, not evidence for any representation of them [S2490]. Tabulates a ten-row corroboration map against KR-CONTR-2026-09 findings, explicitly noting four rows (including 'minimum structure is a pair containing reason' and 'χ ranges 3-21', the two most technically load-bearing) get no textual support at all -- offered as evidence the study is reading the text honestly rather than reading itself into it [S2490]. States as an argued verdict that the Gita supplies zero kernel candidates: KR-CONTR-2026-09 §13 already classifies contradiction as K2 (derivable from existing candidate powers, needing new representation not new capability); C-1 (do not add mechanisms to the kernel merely because they can be used) applies since textual resonance is a weaker warrant than usability, which already fails; the constitutional ban forbids the required derivation chain; and across the whole corpus the Gita's productive contribution has consistently been questions and lenses, never entities -- even the one derivation it helped motivate (the Zero/Sunya chain) was earned by 1,620,000 assignments, not by the reading [S2490].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
### S2484 -- commissioning KR-CONTR-2026-09: the experiment protocol for representing contradiction

22 rows condensed into this theme (source_ids: S2484; full text in `03-CONTRIBUTIONS.jsonl`).

Commissions KR-CONTR-2026-09 to determine whether contradiction can be represented without collapsing into uncertainty/absence/insufficient-evidence/incompleteness/unobservability/non-assessment, whether the current 3-valued domain is expressive enough, and whether a 4th value is actually necessary [S2484]. Its single governing constraint is to discover the minimum required representation rather than confirm a predetermined design; it proposes an initial, non-final definition Contr(p,Kt) iff Pos(p,Kt) AND Neg(p,Kt), and requires preserving a seven-layer non-collapse chain (World!=Observation!=Evidence!=Interpretation!=EpistemicState!=Evaluation!=KnowledgeAttribution) without giving the simulated agent hidden ground truth [S2484]. It specifies four candidate representations to test fairly (extending {T,F,U} with a fourth value C; an independent Contr relation outside Sat; an exclusive-by-construction encoding flagged as expected-vulnerable; and a structured EVal tuple), a formal representation-adequacy criterion, a 15-case deterministic adversarial suite plus second-order combination tests, and poses the Zero-interaction question as genuinely open [S2484]. It requires testing whether ZeroLens can expose contradiction without collapsing into identifying contradiction with Zero itself, permits investigating Belnap/paraconsistent/Kleene/Priest/bilattice semantics only as tagged [EXT] references never automatically imported, and specifies a minimality procedure that tests removing each structured field individually [S2484]. It warns against vacuous success (everything collapsing to U, or everything becoming C), requires distinguishing Representation/Responsibility/Evaluation/Governance, poses the kernel-irreducibility question for contradiction, pre-registers eight candidate negative results and ten candidate invariants to test rather than assume, and poses a candidate separation between EvidenceConflict/ClaimContradiction/ModelConflict [S2484]. It mandates a six-way decision-gate verdict and a five-way kernel verdict as required outputs, states three governing boxed principles about not collapsing or over-promoting contradiction, and is finally approved by HPA Supervisory review as the most rigorously constructed experiment prompt in the programme, independently verified against three prior register corrections [S2484].

### S2488 -- identifying the research bottleneck and constraining how non-classical logic literature may be used

3 rows condensed into this theme (source_ids: S2488; full text in `03-CONTRIBUTIONS.jsonl`).

Identifies the current research bottleneck as Contradiction->Evaluation->Representation->Zero and recommends reading non-classical logic (Priest, Belnap/Dunn) to understand which distinctions different logical systems preserve or collapse -- not to find 'the KnowledgeOS logic' [S2488]. It warns against adopting Priest's dialetheist conclusion (some contradictions are true) as a KnowledgeOS assumption, since KnowledgeOS's contradiction problem is epistemic inconsistency, not ontological/semantic contradiction, and constrains the recommended literature to supplying candidate mathematical structures to test against, never answers to adopt [S2488].

### S2489 -- extracting Priest/FDE candidate structures, warning against overclaim, and recommending the next experiment

13 rows condensed into this theme (source_ids: S2489; full text in `03-CONTRIBUTIONS.jsonl`).

Extracts Priest's paraconsistency/non-explosion principle as a candidate for Contr (contradiction should produce a localized effect, not global inferential collapse) and Priest's gap/glut distinction as formal justification for the Zero Lens's discipline of not collapsing distinct non-determination categories into one value, both tagged [PROP] [S2489]. It extracts FDE's two-channel evaluation Eval(p)=(S+(p),S-(p)) as a candidate representation, explicitly warns that FDE's four truth-value states are not the same dimension as KnowledgeOS's Zero boundary-reason categories, and extracts Priest's relational-FDE extension/anti-extension machinery as a candidate ClaimStanding structure [S2489]. It proposes, from Priest's example of conflicting legal rules not invalidating the whole legal system, a candidate invariant that contradiction implies local conflict but not global invalidity; recommends rejecting the framing 'how many states do we need' in favor of 'what distinctions must be preserved'; and proposes updating the research interpretation of TODO Group B with the direct precursor to the later Contr(p,K)=Pos∧Neg definition [S2489]. It explicitly rejects eleven tempting overclaims from the Priest extraction (e.g. that KnowledgeOS should use FDE, or that Zero equals the fourth FDE value), proposes the strongest research candidate architecture (an Evidence->Assessment->Standing->Determination->Attribution pipeline with Standing kept separate from Boundary), reformulates the research question toward independent semantic dimensions, and recommends a specific next experiment (KR-CONTR-FDE-2026-09) comparing four models against fourteen test scenarios [S2489]. It closes by stating the book's principle 'do not force epistemic reality into a single classical truth axis' paired with 'a mathematically defined semantics still has to earn its meaning', with the final disposition that Theory v1.2 is unchanged and FDE becomes a major external candidate, not the answer [S2489].

### S2490 -- a Gita corroboration study: lenses, corroborations, explicit refusals, and the verdict of zero kernel candidates

14 rows condensed into this theme (source_ids: S2490; full text in `03-CONTRIBUTIONS.jsonl`).

Establishes the study's binding methodological constraint (from the v1.2 constitutional ban on Gita-concept-to-architecture derivation): the study may produce only questions, lenses, or corroborations, never an entity, with any apparent entity recorded as a refusal [S2490]. It analyzes Arjuna's visada as independently arriving at exactly the KR-CONTR-2026-09 required-distinction list, and names three lenses -- L-1 'informed paralysis' (a state complete in evidence yet non-actionable), L-2 'the frame, not the value' (dvandva-atita as changing the frame rather than picking a side), and L-3 'proportion, not selection' (the three gunas coexisting in proportion) [S2490]. It records three corroborations (C-1: confusion located in the normative model, not evidence, corroborating EvidenceConflict!=ClaimContradiction!=ModelConflict; C-2: an absent comparative ordering, corroborating that the ordering ⪰ is a separate hard problem, explicitly forbidden from being recruited for N_eff; C-3: samsaya versus visada as two distinct pathologies, corroborating Contr!=Unknown) and a ten-row corroboration map, noting four rows get no textual support at all as evidence the study reads the text honestly [S2490]. It states explicitly what the Gita does not supply (no definition of Contr, no content for the frame qualifier, no bearing on cardinality, no ordering, no kernel capability), registers six typed candidates while refusing five would-be entities as violations of the constitutional ban, and delivers the argued verdict that the Gita supplies zero kernel candidates -- contradiction is already classified K2 (derivable, not a new capability) and textual resonance is a weaker warrant than usability, which already fails [S2490]. It closes by warning against five conclusions the document must not be read as supporting, and restates the governing rule that the strongest statement made must never exceed the strength of available evidence, characterizing a philosophical text's proper epistemic role as corroboration at best, decoration at worst [S2490].

### S2496 -- the implementation boundary for the FDE sandbox experiment and the Standing/Boundary design

5 rows condensed into this theme (source_ids: S2496; full text in `03-CONTRIBUTIONS.jsonl`).

States the binding implementation boundary: implement the candidate evaluation mechanism (Standing(p)=(S+,S-)) as an experiment, never 'FDE as KnowledgeOS logic', with Theory v1.2 unchanged regardless of outcome [S2496]. It designs Standing(positive,negative) as strictly separate from a Boundary(facet,condition,context,provenance) record, explicitly rejecting a TRUE/FALSE/BOTH/NEITHER enum as a KnowledgeOS domain primitive since (0,0) alone could mean several different things FDE cannot distinguish on its own [S2496]. It proposes implementing four experimental adapters (Classical, K3, FDE, Structured) rather than four KnowledgeOS domain states, tested against 14 named scenarios measuring distinction preservation as the key metric, and recommends implementing KR-CONTR-FDE-2026-09 as a sandboxed research module with a specific test-directory layout, explicitly not touching the constitutional kernel or Theory v1.2 [S2496].

### S2497 -- further FDE-experiment specification and HPA approval

7 rows condensed into this theme (source_ids: S2497; full text in `03-CONTRIBUTIONS.jsonl`).

Reiterates that the four adapters must be implemented as competing representations under a shared EvaluationModel interface, never as competing KnowledgeOS ontologies, and specifies the distinction matrix should record preserved/collapsed/indeterminate/not-representable plus a collapse witness rather than a bare score [S2497]. It reiterates the detector-not-definition discipline (implement fdeConflict as a detector, then test its equivalence to Contr rather than assuming it), expands the design to require testing composition explicitly, and establishes an architectural boundary where Zero is a downstream consumer of Evaluation, never part of FDE itself [S2497]. It specifies an explicit 'NOT IMPLEMENTED' hard boundary list for the experiment README, and records the HPA Supervisory ruling APPROVING KR-CONTR-FDE-2026-09 for implementation with six non-negotiable rules, confirming Theory v1.2 and the kernel remain unchanged regardless of outcome [S2497].

### S2499/S2500 -- the pre-experiment expected research chain, later refuted by the actually-executed experiments

2 rows condensed into this theme (source_ids: S2499, S2500; full text in `03-CONTRIBUTIONS.jsonl`).

States the pre-experiment expected research chain (Factivity->Contr->⪰->≡sem->δ->Lifecycle->Composition->Reduction->Kernel) as Rule 9 of the next-session instructions [S2499], duplicated verbatim as a closing governance statement in a second document [S2500] -- notably, this linear chain is exactly what the subsequently executed experiments (KR-CONTR-MODELS, KR-CONTR-EVAL, KR-COMP) went on to refute or reorder, since Contr turned out not to be at the root and Composition/frame-qualification proved decisive before Contr itself.

### S2501 -- the FDE-sandbox experimental results, running in parallel with (and not reconciled against) the main KR-CONTR-EVAL lane

10 rows condensed into this theme (source_ids: S2501; full text in `03-CONTRIBUTIONS.jsonl`).

Reports headline collapse rates across four models over 14 scenarios (Classical 12/14, K3 8/14, FDE 6/14, Structured only 2/14), a numeric framing that contrasts with the main research lane's KR-CONTR-EVAL-2026-09 (S2495), which tested a differently-structured suite and reported different scores with extensive self-corrections -- the two same-day artifacts are not reconciled with each other in the corpus [S2501]. It details the Structured model's two acceptable collapses (distinguished by Reason/Provenance rather than by distinct S+/S- values), supplies five worked countermodels demonstrating specific information loss in the weaker models, and states a Boundary-Value Separation invariant that values and boundary metadata must be kept separate [S2501]. It proposes a minimum four-component boundary structure with an eight-row Reason taxonomy, states Contr(p) iff S+(p)=1 and S-(p)=1 as a settled definition -- which directly conflicts with the main lane's finding that Pos∧Neg is necessary but not sufficient for Contr (requiring an additional frame/currency qualifier phi that this document does not test) -- and proposes a candidate semantic-equivalence definition requiring complete structural identity across all six Standing/Boundary components [S2501]. It proposes the structured standing representation as a component of a non-standard Theory v1.3 formulation already flagged as inconsistent elsewhere in the batch, and recommends proceeding next to progress ordering ⪰ -- a different queue recommendation from both the main lane and from KR-CONTR-MODELS' composition-first recommendation -- marking TODO Group B (Contr) as [EXP] 'experiment complete', a stronger closure claim than the main lane ever makes [S2501].

### S2746 -- a later correction: no fourth flat value is needed; the failure is a boundary/unknown problem, not a contradiction problem

1 row condensed into this theme (source_ids: S2746; full text in `03-CONTRIBUTIONS.jsonl`).

Reports that tested contradiction requirements do not force a fourth flat value: flat candidates A/B/C fail the same seven required distinctions, whose common failure lies in the unknown/boundary family rather than in contradiction itself -- reframing 'the problem that looked like a contradiction problem' as a boundary/unknown problem [S2746].


## Notes for P3
Two parallel, apparently uncoordinated experimental tracks exist in this label's own rows: the FDE sandbox (S2496/S2497/S2501, this label) reports collapse rates and a settled-sounding Contr(p) iff S+(p)=1 and S-(p)=1 definition and marks TODO Group B 'experiment complete', while the main-lane KR-CONTR-EVAL-2026-09 (S2495, referenced but not itself a row in this label's family) found Pos∧Neg necessary-but-not-sufficient and keeps Contr explicitly OPEN. My own observation: these are not reconciled anywhere in the captured rows, and P3 should treat S2501's stronger closure claim with caution relative to the main lane. S2746 (the latest row by source_id) reframes the whole question as a boundary/unknown problem rather than a contradiction problem, which may itself dissolve the S2501/S2495 tension rather than resolve it in either direction.
