# Decision briefs for the Principal: whole-book review S0100

*2026-10-19. By the Professor. Each brief gives the question, evidence, alternatives, implications, recommendation, uncertainty, and what would change the recommendation. The briefs **prepare** decisions; they decide nothing. RULEBOOK-v1.3 governs. The rule changes have their own brief (`DECISION-BRIEF-RULES-S0100.md`).*

| ID | Question | Recommendation, in one line | Blocking? |
|---|---|---|---|
| DB-1 | Is A1 a norm the organisation adopts, or a claim about how standing changes? | A norm (a stipulated definition of recognized standing), with named readings; the empirical claim kept apart as a hypothesis | no: the theory proceeds with named readings |
| DB-2 | What independence can the book claim in a single-operator estate? | Graded, factor-relative independence; in-estate instances count as recurrence, not as independent confirmation | yes, for any promotion (n ≥ 2) and for F32 |
| DB-3 | K4: must the kernel prevent cross-application uniqueness violations, or only detect them? | Not decidable yet; decide after CE (M2) and the kernel type (M3). The brief prepares the options | not yet |
| DB-4 | Is Ch. 8 a validation chapter or an assurance-governance chapter? | Split: verification as a law family (Part III) and validation of the book (Part V) | no |
| DB-5 | The bearer of the dimensions (claim or carrier): a definitional fork | No decision needed: the kernel can carry both, with a bearer per dimension. Both meanings are kept under separate IDs | no |
| DB-6 | Chapter 7: write it or retire it? | Retire it as a separate chapter; home the loop material in Part V until the loop has closed once | no |
| DB-7 | Adopt TARGET-TOC's structure? | Adopt it as a reading order with stable section numbers (renumbering cost 0) | no |
| DB-8 | Which "inline review notes" are to be removed? (stage-2 blocker) | Choose the scope; recommended: (b), notes that only record review history, in the body text, book-wide | **yes, for that stage-2 item** |

---

## DB-1. A1: a norm or a claim?

- **Question.** A1, "standing changes only by recorded acts" (§1.6), is the book's one axiom. Is it a rule the organisation adopts (a norm), or a claim about how organisations in scope actually change standing?
- **Evidence.**
  - Both P1 reviewers find A1 definitional: its instances are "consequences, not predictions" (§1.6 box), and "a property that includes a recorded act is not a counterexample but the act itself" defines counterexamples away (P1-2).
  - Its support counts its own consequences (§1.14.4-1, "NEW EVIDENCE for §1.6").
  - The chapters use different readings. §9.8 M1 reads A1 "as a fact"; §9.9 M-T2 has "all primitives hold as forms"; §2.8 L31 adds scope and period conditions (I-04).
- **Alternatives.**
  - (a) **Norm:** a stipulated definition of *recognized* standing. True by definition in scope; its value is whether organisations can live by it.
  - (b) **Claim:** an empirical law. It needs a falsifier: a legitimate standing change with no act (desuetude; standing conferred by practice, cf. 1.14.5-13).
  - (c) **Both, kept apart:** the norm for the kernel; the claim as a separate hypothesis with its falsifier.
- **Implications.**
  - (a) and (c) make CE's job precise: CE evaluates A1-recognized from the record. A1-fact becomes the occurrence premise, the residual trust assumption of §1.12 (F23).
  - (b) alone leaves the kernel's evaluator without a defined target until the claim is tested.
- **Recommendation: (c).** Name the readings (A1-de-jure, A1-recognized, A1-fact; A1-as-form), state the norm in §1.6, and keep the empirical claim as a hypothesis with the practice rival.
- **Uncertainty.** Medium. Organisations that run on practice, such as rough consensus, may reject the norm as a description of themselves. That bears on adoption, not on the kernel.
- **What would change it.** A case in the estate where the organisation treats standing as changed without any recorded act, and the book would have to call that standing.

## DB-2. Independence in a single-operator estate

- **Question.** One Principal, one AI family, one instruction set. Can two instances ever count as independent recurrence? Can the book's own reviews count as independent confirmation?
- **Evidence.**
  - "Independent" has three incompatible senses: process-independent sessions (§1.14.3-3); no shared actor, instructions, reviewer or work item (§4.1); seven factors, where a session ID is not one (§8.6). Sources: I-03, P3-4, P5-3.
  - Under §4.1 and §8.6, no two in-estate instances are independent, so every promotion rule (n ≥ 2) is unsatisfiable by definition (P3-A).
  - The integration reviewer's counterexample: this review's own ten-reviewer design is independent under §1.14.3-3 and not independent under §4.1.
- **Alternatives.**
  - (a) Process independence counts (the current §1.14.3-3).
  - (b) Strict: nothing in-estate is independent.
  - (c) **Graded and factor-relative:** each pair declares its shared factors. In-estate instances count as *recurrence* (the phenomenon occurs again), not as *independent confirmation*. Promotion needs either an instance outside the estate or an explicit, declared weight.
- **Implications.**
  - (a) over-credits the book's reviews and the corpus's counts.
  - (b) makes the method unable to promote anything.
  - (c) keeps counts usable and honest, and gives T4-03 and F32 a criterion that can be met.
- **Recommendation: (c).**
- **Uncertainty.** Medium. The bridge from shared factors to shared blind spots is a hypothesis (§8.6 C2). The planted-gap tests are the only evidence so far.
- **What would change it.** Planted-gap results showing that reviewers who share factors miss different plants about as often as reviewers who share none.

## DB-3. K4: prevention or detection

- **Question.** When several applications record acts, must the kernel *prevent* a cross-application uniqueness violation (one act recorded twice, or two conflicting acts both recorded)? Or is it enough to *detect* and reconcile it?
- **Evidence.**
  - §9.6.4 reads "U: a serialization point, or only detection is possible", but step 2 drops the detection clause.
  - "Global act identity" presupposes a cross-application uniqueness unless identity is derived from the act's content (P4-6).
  - BC-7 lost 23 of 30 concurrent writes with one recorder (S0234 L282).
- **Alternatives.**
  - (a) Prevention: a kernel serialization point. CR-a is then a kernel part.
  - (b) Detection: per-application stores with content-derived identity and reconciliation. CR-a is delegable.
  - (c) Per kind: prevention for acts that change standing irreversibly; detection elsewhere.
- **Implications.** Prevention puts a write path in the kernel; detection keeps the kernel small but needs a reconciliation result that CE can evaluate.
- **Recommendation:** decide after M2 (CE as a function) and M3 (the kernel type). Until then (c) is the working hypothesis, labelled Hyp.
- **Uncertainty.** High. The decisive inputs (CE's definition; which acts are irreversible) do not exist yet.
- **What would change it.** A defined CE that can evaluate standing under reconciliation, which favours (b); or a standing-changing act whose duplicate cannot be undone, which favours (a) for that kind.

## DB-4. Ch. 8: validation or assurance governance?

- **Question.** Ch. 8 is announced as "methods for validating this book's theory". §8.6–§8.11 are a theory of verification acts in the estate: independence, eligibility, recusal, authorship.
- **Evidence.**
  - Both P5 reviewers ask this (P5-5).
  - Five sections converge on one unstated law: a party constrained by a control may not be the sole authority on its own compliance.
  - About 73% of Ch. 8 by word count is estate assurance theory (P5-B).
- **Alternatives.** (a) Keep Ch. 8 as it is, renaming its scope. (b) **Split:** the law family of verification acts goes to Part III (laws); validation of the book stays in Part V (TARGET-TOC). (c) Move it all into Ch. 1's authority laws.
- **Implications.** (b) makes the verification laws visible as kernel candidates (producer ≠ verifier, eligibility, recusal). (a) keeps them buried under "validation".
- **Recommendation: (b)**, within TARGET-TOC (DB-7). No renumbering is needed with stable numbers.
- **Uncertainty.** Low.
- **What would change it.** A finding that the verification laws are application-level only (no kernel candidate), which favours (a).

## DB-5. The bearer of the dimensions: a definitional fork, both meanings kept

- **Question.** Do the six dimensions (nature, representation, governance status, authority scope, evidence status, operational state) belong to the knowledge claim or to its carrier (the artifact or instance)?
- **Evidence.**
  - P2-1 (critical): the book puts them on knowledge, while one knowledge item has several representations at once (§1.5).
  - GKC makes the Knowledge Claim the atomic unit with a coordinate, and an instance an artifact at a coordinate (GKC L12–19).
  - Nature belongs to the claim, representation to the carrier, governance status to a document lifecycle (S0020 L27).
- **The two meanings, kept under separate IDs** (to be created with the stage-2 correction of §3.1, so each ID has a home in the text):
  - **reading K:** the dimensions are borne by the claim;
  - **reading A:** the dimensions are borne by the carrier or instance (GKC).
- **Can the kernel carry only one?** **No.** A per-dimension bearer is consistent:
  - nature and evidence status → the claim;
  - representation and operational state → the carrier, or the realization edge;
  - governance status → open (MM-4).

  Because the kernel can carry both, **this fork needs no Principal decision** (the instruction: bring a definitional fork to the Principal only if the kernel can carry only one meaning).
- **Recommendation:** record both readings and the per-dimension bearer table in §3.1, and re-run S0016's six-artifact test under it (M5).
- **Uncertainty.** Medium: governance status's bearer stays open.
- **What would change it.** A kernel law that needs all six dimensions on one bearer. Then the fork would come to the Principal.

## DB-6. Chapter 7: write it or retire it?

- **Question.** CHAPTER-PLAN lists Ch. 7 ("The KnowledgeOS loop and its evidence") as planned; it was never written. Five places point to it (I-17).
- **Evidence.**
  - The loop has never closed end to end (§1.8; T1-39, T1-45).
  - §1.8's identity claim depends on it (I-07).
  - The loop material is spread over §1.8, §4.1 C7, §6.4 and §8.3 C3 (I-16).
- **Alternatives.** (a) Write Ch. 7 now from the existing material. (b) **Retire it as a separate chapter:** home the loop material in Part V (validation), and write a chapter only when the loop has closed once on record. (c) Keep it planned.
- **Implications.** (a) writes a chapter about an event with n = 0. (c) keeps five dangling pointers. (b) removes the pointers and keeps the question open in one home.
- **Recommendation: (b).**
- **Uncertainty.** Low.
- **What would change it.** A recorded end-to-end pass of the loop (observation → recommendation → decision → outcome → assessment → change).

## DB-7. Adopt TARGET-TOC?

- **Question.** Reorganise the book by the research purpose's deliverables (TARGET-TOC.md)?
- **Evidence.** The integration review found no derivation chain and no primitive table (I-02). The current order is the reading order of the corpus, not the order of the theory.
- **Alternatives.** (a) Adopt with renumbering (2,364 citations to rewrite). (b) **Adopt as a reading order with stable numbers** (cost 0). (c) Keep CHAPTER-PLAN.
- **Implications.** (b) gives the theory's order without breaking any citation or review record.
- **Recommendation: (b).**
- **Uncertainty.** Low.
- **What would change it.** Readers finding the stable numbers more confusing than renumbering would be costly.

## DB-8. Scope of "the inline review notes" (blocker for one stage-2 item)

- **Question.** The stage-2 instruction says "remove the 23 inline review notes in 12 sections". The Professor cannot reproduce that count, so the scope must be fixed before anything is removed.
- **Evidence** (working book, body text, excluding "what does not hold" and references; read-only counts):
  - integration_check.py's revision-tag pattern (RC-n, R1-n, "pilot n, file n", "batch n", "added/revised/corrected … after review/settlement"): **257 in 60 sections**;
  - "… after / at review | settlement | theory turn": **112 in 36 sections**;
  - "(review …)", "(audit …)", "(RC-…)" parentheses: **60 in 32 sections**;
  - blockquote boxes ("> **Revised …", "> **Corrected …"): **9 in 7 sections**;
  - italic notes "*(corrected … review …)*": **29 in 17 sections**; "*Corrected after review …:*": **12 in 11 sections**;
  - the C17 settlement named the older sections §6.5–§6.7, §8.6–§8.8 and §9.6–§9.8 as carrying such notes (57 of the 112 are there).

  None of these gives 23 in 12.
- **Alternatives.**
  - (a) The blockquote boxes and italic notes only: about 50 in about 30 sections.
  - (b) **Every note that only records review history** (the "after review …" and "(review …)" forms), in the body text book-wide: about 170 in about 50 sections. Content changed at a review stays; only the history label moves to the chapter's change log.
  - (c) Only the nine older sections of the C17 note.
  - (d) The Principal's own list of 23 notes, if it exists elsewhere.
- **Implications.**
  - (b) applies entry 52a's rule (revision history only in the change log) to the whole book, as was done for §1.12–§1.14. It touches many sections and is a form change, not a content change; it needs care where a note carries meaning ("valid in the regrouped form below").
  - (a) and (c) are smaller and leave the rule half applied.
- **Recommendation: (b)**, done section by section with a change-log row per section, and an integration-check run (tags 0 in the body) as the completion condition. **Or (d)**, if the 23 notes were identified elsewhere.
- **Uncertainty.** None about the counts; the scope is the Principal's choice.
- **What would change it.** The source of the "23 in 12" figure.
