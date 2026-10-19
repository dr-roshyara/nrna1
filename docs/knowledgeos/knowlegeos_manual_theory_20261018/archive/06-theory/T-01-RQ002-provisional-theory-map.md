# T-01 — RQ-002: provisional theory map, revision timeline and logical analysis

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Sources | the seven RQ-002 files in `docs/implementation/` (hashes in `05-validation/V2-REVIEWER-INSTRUCTION.md` §1), their git history, and my frozen extraction `05-validation/v2-professor/` |
| Standing | **background, not KnowledgeOS corpus.** RQ-002 (2026-07-11) predates the corpus. Its relation to the corpus theory is hypothesis H-B1″, untested |
| State | **PROVISIONAL.** V-2 adjudication done (`05-validation/V2-REPORT.md`): no misreading of mine affects this map. Additions from adjudication: (1) the Synthesis binds **every future implementation** to the nine invariants (F6 L106; missed by both readers). (2) Whether "( × AUTHORITY-SCOPE )" is a full fourth factor or optional is genuinely ambiguous (F2 L14, BOTH-DEFENSIBLE); C-1 holds either way. (3) F4's "first deliverable" (L100) does not match its deliverable order (L81). (4) The F1 L103 vocabulary issue (C-8) was independently noticed by the adjudicator |
| Labels | R-layer: **[EV]** stated (with anchor) · T-layer: **[I]** inference · **[H]** hypothesis · **[NEW]** my deduction. Anchors `Fn:Lx`: F1 Constitutional Model · F2 Meta-Model · F3 Taxonomy · F4 Charter · F5 Discovery Report · F6 Synthesis · F7 Raw Findings · `git:<hash>` |

---

## A. Concept map

```
 Knowledge Need ──(telos: knowledge exists only to satisfy)──► Knowledge          [EV F1:L66]
                                                                   │
 KNOWLEDGE SPACE = NATURE × REPRESENTATION × GOVERNANCE-STATUS (× AUTHORITY-SCOPE) [EV F1:L13]
   │ point = Knowledge Coordinate (independent of artifacts)                       [EV F1:L18]
   │ occupied by Knowledge Instance (single Claim or composite of Claims)          [EV F1:L20]
   │ atomic unit Knowledge Claim = content + Coordinate + Provenance + Status + Scent [EV F1:L19]
   │
   ├─ derived: lifecycle regime = f(Nature) · validation = f(Representation, Nature) [EV F1:L32-33, F2:L56-57]
   ├─ moves:   Flow = nature derivations + representation transitions, closed by consumption feedback [EV F1:L78]
   │           Transition = deliberate move of one coordinate                       [EV F1:L23]
   ├─ reads:   Query = region selected by coordinate predicates; Purpose is its selector [EV F1:L22, L41]
   │           → realized as Context Assembly in the Project Knowledge specialization [EV F1:L22]
   ├─ constraints: invariants I-1…I-11 on points; rules PK-P1…P10 on moves and queries [EV F1:L26]
   ├─ trajectory: lifecycle Creation → … → Staged Retirement, a cycle via use     [EV F1:L74]
   └─ Specialization = weights (dense cells, validation, canon scope, risk stages), never new rules [EV F1:L84]
         examples: Engineering · Project · Runtime (insufficient evidence) · Business [EV F1:L96-99]
         Engineering Platform = "first implemented bounded context"                  [EV F1:L105]
 Seven types (Executable, Decision, Definitional, Descriptive, Experiential, Qualification, Tacit)
   = archetypes, i.e. densely populated cells, not primitives                      [EV F2:L11]
```

| # | Relationship | Standing |
|---|---|---|
| A-1 | Claim is the unit of governance; composites are governed claim-by-claim | **explicit** [EV F1:L20, F6:L26] |
| A-2 | Status must be visible wherever knowledge is retrieved | **explicit** [EV F6:L19, F1:L57] |
| A-3 | Representation correlates with default authority (executable highest, tacit lowest) | **explicit** as correlation, not a function [EV F2:L68] |
| A-4 | Purpose is not a dimension; it lives in the query | **explicit**, tested and disposed [EV F1:L41] |
| A-5 | Tacit knowledge cannot carry status, hence is not governable as artifact | **explicit**, labelled a theorem [EV F1:L74, F2:L70]; derivation examined in C-3 |
| A-6 | All other rules derive from the telos I-11 | **explicit claim**, derivation **missing** (C-6) |
| A-7 | The Engineering Platform is an instance of the model | **explicit** [EV F1:L105], evidence partly undermined (C-8) |
| A-8 | Context Assembly is the consumer-side realization of Knowledge Query | **explicit** [EV F1:L22]; the only "new capability" recommended [EV F5:L109, F6:L157] |
| A-9 | Specializations are density maps over one space | **explicit** [EV F1:L26] |
| A-10 | Retrieval = coordinate predicates, so storage and retrieval decouple | **explicit** [EV F1:L18, F2:L75] |

---

## B. Revision timeline (one day: 2026-07-11, git times +02:00)

| Time | Document · event | Evidence |
|---|---|---|
| 10:42 | **Charter** approved with amendments. Going-in seven-kind *topic* taxonomy; the granularity question is mandatory | git:1bfa4beab/3ab7fb72d · F4:L18, L33 |
| 10:55–11:16 | **Raw findings** from four executors (C, D, A, then B) | git:cf1aba40c · F7 |
| 11:16 | **Discovery Report**. Ontology with **6 nodes** (Binding, Decision, Definitional, Descriptive, Experiential incl. qualification evidence, Tacit), primary cut = validation mechanism × population. UL status vocabulary has **3 values** (Canonical / Historical / Deprecated) | git:a84e0ab6f · F5:L44, L51–75 |
| 11:36 | **Synthesis**. PK-P1…P10, **9 invariants**, lifecycle. P2 lists **4** status values (canonical / historical / deprecated / draft) | git:9e88397c2 · F6:L19, L108–116 |
| 11:43 | **Taxonomy (D9)**. **7 types**: Binding renamed Executable; Qualification split out of Experiential; "Historical" falsified as a *status* | git:709564fe8 · F3:L17–106, L115 |
| 11:49 | **Meta-Model (D10)**. Types become **archetypes** of **4 dimensions**; status has **5 values** (Draft/Candidate · Canonical · Superseded/Historical · Deprecated · Archived); still "9 invariants" | git:e514b265e · F2:L11, L47, L84 |
| 17:00 | **Constitutional Model v1**. *"All nine invariants … universal across knowledge domains"*; **one invariant added** (I-10, representation transitions) → "now ten" | git:d7e9763b0 (lines 25–27, 103 of that version) |
| 17:09 | **Refined "for epistemic honesty"**. "Universal" withdrawn → *"constitutional within the researched domain"*; **Knowledge Space** introduced; specializations demoted to an appendix; heading "(ten)" | git:0d31c403e · F1:L5, L13 |
| 17:20 | **Knowledge Claim promoted** from the Project Knowledge specialization to the constitution | git:26013b00b · F1:L19 |
| 17:34 | **I-11 telos added**; the heading still says "(ten)" | git:b07514e40 · F1:L54, L66 |
| 17:41 | I-11 wording strengthened (*"never to maximize documentation"* → *"only to satisfy … Documentation is merely one possible representation"*) | git:3dd455c00 · F1:L66 |
| 17:45 | **Research freeze**: *"complete enough to be falsified"* | git:ae84b5040 · F1:L4 |

### Assessment of the four reported changes

| Change | Verdict | Reason |
|---|---|---|
| **9 → 11 invariants** | **Deliberate revision, plus one editorial inconsistency** | I-10 was added explicitly at 17:00 ("one invariant is added"); I-11 explicitly at 17:34 with an ARB date. The heading "(ten)" was correct from 17:09 to 17:34 and **was not updated** when I-11 was added. Content revision: deliberate. Heading: stale. **No contradiction** [EV git history] |
| **3 → 5 status values** | **Deliberate elaboration** | Visible stepwise: 3 (UL, 11:16) → 4 (P2, 11:36) → 5 (D10, 11:49). Superseded merges with Historical; Archived comes from the ROT evidence. The earlier lists are subsets, not rivals [I] |
| **6 nodes → 7 types** | **Deliberate revision** | D9 states it validates or falsifies candidates [EV F3:L5]. Binding → Executable is a rename; Experiential/Qualification is a split, justified by different producer and authority [EV F3:L83–86] |
| **Types → archetypes / regions** | **Deliberate, explicitly argued** | D10 frames it as a tested hypothesis with three "smoking guns" [EV F2:L11, L19–23] |

**Two genuine unresolved inconsistencies remain:**
- F3 says "five candidates are falsified or merged" (L11) and "4 falsified/merged" (L141); its own commit message says "four". The table has five plus one insufficient-evidence.
- F4 (charter) labels the literature scope "six disciplines" in its heading while the table lists seven (the seventh added at review) [reviewer report; heading L64 says "six disciplines" — EV F4:L64].

**One significant revision of standing:** the claim of *universality* across domains (17:00) was withdrawn within nine minutes (17:09) and replaced by domain-scoped constitutionality. This is the clearest example of deliberate epistemic narrowing in the record.

---

## C. Formal and logical analysis (T-layer)

| # | Statement | Kind | Assessment |
|---|---|---|---|
| C-1 | **Knowledge Space = N × R × G (× A)** [F1:L13] | definition (modelling choice) plus an empirical claim of orthogonality [F2:L63–65] | **[NEW] Not a full Cartesian product by the source's own statements.** Two constraints: R = Tacit ⇒ no G value (tacit "cannot carry explicit status", F2:L65), and N = Skill ⇒ R = Tacit (F1:L36). The admissible coordinates form a **constrained subset** S ⊂ N×R×G×A. Calling it a product overstates orthogonality; the theory remains coherent if stated as a subset. *Falsification condition for orthogonality:* a nature/representation pair that is impossible in principle, not just empty |
| C-2 | **regime = f(Nature)**; **validation = f(R, N)** [F1:L32–33] | empirical claim of functional dependency | Well-formed as functions. The Decision override [F2:L57] fits because f takes both arguments. *Falsification:* one single-nature claim with two regimes. Boundary artifacts (IDDs) are not counterexamples **if** composites are decomposed per claim [F1:L20]. The source leaves that open (F3:L137, F5:L94) |
| C-3 | **Theorem: tacit knowledge is ungovernable as artifact** [F1:L74, F2:L70] | derived proposition | Premises: (P1) I-2, status visible at discovery; (P2) tacit representation cannot carry explicit status; (P3, **unstated**) "governable as artifact" ≝ able to satisfy I-2. With P3 the derivation is **valid but nearly definitional**. Without P3 it does not follow. **Derivation valid with a missing definitional premise** |
| C-4 | **"Exactly two regimes"** (PK-P5) [F6:L40] vs the four regime values of f [F2:L56] and "at least four … lifecycles" [F5:L104] | contested | [I] Probably **terminology/scope**, not a contradiction. Measurements are immutable (regime 1); skill is tacit and outside artifact governance; "lifecycles" in F5 is a different notion from evolution "regimes". Classified UNDETERMINED until the corpus or ARB uses one term consistently |
| C-5 | **Canonical(Decision) = accepted ∧ unsuperseded ∧ owned ∧ bound** [F3:L34] | definition | Consequence [NEW]: an unbound but accepted ADR is not canonical. Given the evidence that binding is rare (one longitudinal success, F6:L15), **most real ADRs are non-canonical by definition**. Strong but consistent; worth knowing before applying it |
| C-6 | **I-11 telos; "every other rule derives from it"** [F1:L66] | axiom plus a derivability claim | The derivations named (P6, never-a-copy, ephemeral assembly, gap evidence, selective capture) are **plausible but not shown**. I-3 (mandatory provenance) and I-7 (supersession by link) do not obviously follow from need-satisfaction alone; they need an added premise ("a need is satisfied only by trustworthy knowledge"). **Derivation missing** |
| C-7 | **I-8 no second-population artifact class** [F1:L63], from PK-P1 | empirical generalization raised to an invariant | Counterevidence (Watson Discovery) is absorbed as "the exception proves the rule: binding created a same-population gesture" [F6:L15]. [I] This re-description risks making P1 **unfalsifiable** unless "same population" is defined independently of success. *Falsification condition to state:* a durable artifact class maintained by a population disjoint from its consumers, without a binding mechanism |
| C-8 | **"Platform decomposes with no residue and no new dimensions"** [F1:L103] | verification claim | **Partly unsupported by its own vocabulary** [NEW, also noted by the reviewer]: the decomposition uses "Runtime" as a scope (not among Context-local/Project/Platform/Constitutional), "composite" as a nature, and "Canonical-Stable", "Canonical-with-verdict-history" as statuses. Either the vocabularies are incomplete or the decomposition has residue |
| C-9 | **Confidence "High … triangulated across 7 disciplines, 4 independent executors"** [F1:L47] | evidential claim | [I] The four executors were AI sessions given the same standing mission [F4:L54–62]. Their convergence is **not independent confirmation** in the lineage sense; it shows persistence across prompts. Confidence should be read as "agreement among executors", not independent triangulation |
| C-10 | **Knowledge Claim definition** | definition, revised | Three versions: C's contextualized assertion (provenance, status, address) [F7:L21]; the composite (context-bound, provenance-carrying, status-bearing, **co-located**) [F5:L15]; the constitution's 5-tuple (content, **Coordinate**, Provenance, Status, **Scent**) [F1:L19]. "Co-located" is dropped and "Coordinate" and "Scent" are added. **Deliberate refinement [I]; not reconciled in text** |

---

## D. DDD interpretation (strategic, not design)

- **Ubiquitous language, candidate:** Knowledge Claim · Coordinate · Nature · Representation · Governance Status · Authority Scope · Transition · Query/Purpose · Context Assembly · Supersession · Staged Retirement · Same-Population · Scent · Born-Stale. All pass the source's own "Markdown-removal" litmus [F4:L8, F5:L46].
- **Core domain concern:** keeping knowledge *valid at the point of use*. Status, provenance, scope, freshness, with the consumer-side act (assembly) as the value point [F6:L51].
- **Domain invariants:** I-1…I-11 are candidate domain invariants. **Ownership of each is unresolved.** Five candidate contexts are offered [F6:L98–102] and explicitly marked *candidates*. The source itself flags their self-similarity to the platform as undecided [F6:L104].
- **Not authorized here:** turning these into bounded contexts, aggregates or APIs. RQ-002 forbids that step until the ARB rules [F6:L177]. The later 08-16 tier model (KT-5) belongs to a different stage and is not merged here.

---

## E. Unresolved questions that matter

| # | Question | Known | Missing | What would resolve it |
|---|---|---|---|---|
| E-1 | How do **machine consumers** resolve coexisting ranked contradictions? | I-4 says coexist at rest, resolve at consumption [F1:L59]; the AI literature has no adjudication [F6:L57] | any resolution rule for agents | a pilot with recorded agent decisions on conflicting claims (the source's E-2) |
| E-2 | **Mixed decision/description artifacts**: which regime applies? | flagged three times [F3:L137, F5:L94, F6:L43] | a decomposition rule | test C-2: decompose real IDDs per claim and check whether each claim has one regime |
| E-3 | **Cost of claim-level governance** | right in principle; overhead confined nanopublications to niches [F6:L29, F5:L95] | any cost measurement | measure bookkeeping time per claim on a sample |
| E-4 | Can **in-flow context assembly** satisfy the same-population principle? | named the biggest uncertainty [F6:L173] | a pilot | the recommended pilot on real tickets [F6:L173] |
| E-5 | **[NEW]** Is the space a product or a constrained subset (C-1), and is the vocabulary complete (C-8)? | two constraints stated; out-of-vocabulary values used | an explicit admissibility rule | state S formally; check every platform artifact against closed value sets |
| E-6 | **[NEW]** Does every rule derive from the telos (C-6)? | asserted | the derivations | write each derivation; record which need extra premises |

---

## What this establishes, and what it does not

- **Establishes [EV]:** RQ-002 is a coherent, dated, deliberately revised research model. Its revisions in one day are traceable commit by commit, and most apparent inconsistencies are **deliberate elaboration**. Two counting inconsistencies and one stale heading remain.
- **Does not establish:**
  - that the KnowledgeOS corpus theory continues RQ-002 (H-B1″ untested);
  - that RQ-002's empirical claims hold (executor convergence is not independent; C-9);
  - that the "theorems" are more than definitional (C-3).
