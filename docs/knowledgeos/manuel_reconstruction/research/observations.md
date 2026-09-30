# Research Observations — pilot F0001–F0005

**Reading order:** F0001 → F0005, as commissioned.
**Stated dates:** F0001 and F0005 are dated 2026-08-02; F0002, F0003 and F0004 are dated 2026-08-03. The reading order is therefore **not** the date order. F0005 predates F0002–F0004, and this is noted wherever it matters.
**Levels:** OBS = observation · INT = interpretation · HYP = hypothesis. Each observation is also marked with the file in which it first appeared.
**Line references:** `L<n>` refers to the line in the source file.
**Corpus scope:** these five files are **not** the programme's origin. They cite work going back to Rounds 16–47 and July 2026. Nothing here dates the *origin* of any idea.

---

## After F0001 — initial observations

**O1: Every artifact declares its own epistemic status.** *(F0001)*
- Evidence: the header says "CANDIDATE — NOT ADOPTED" and "Generated — never authoritative without human review" (L6–7). The document ends with "Nothing in this document executes" (L267).
- Why it matters: status is treated as a property the artifact itself carries, not as metadata assigned from outside.
- INT: the corpus treats a document as a *claim with a status*, not as a neutral text.

**O2: The allowed status is gated by an evidence count.** *(F0001)*
- Evidence: "per ES-006.1, n=1 admits a CANDIDATE, never a rule" (L91). The source counts instances throughout, from n=0 to n≥2 (L25, L142, L152, L193).
- Why it matters: this is a quantitative admissibility rule.
- INT: evidence is a **necessary condition** for status. It may not be a sufficient one (see O14).

**O3: Authorship does not confer authority.** *(F0001)*
- Evidence: "A baseline becomes a baseline by an adoption event, not by being written" (L6, L267).
- INT: writing something down and *making it binding* are two separate acts.

**O4: The source proposes a three-layer decomposition by portability.** *(F0001)*
- Evidence: METHOD, BINDING and EVIDENCE/case law (L71–75). "Extraction readiness = f(domain-free, binding-free, evidence-free)" (L114).
- INT (logic): "ready" is defined as the conjunction of three negative properties, so readiness means the *absence* of three kinds of coupling.

**O5: The structural properties it cares about are not lexical.** *(F0001)*
- Evidence: "Neither Tier-2 nor Tier-3 blockage is detectable by searching for domain vocabulary … a three-question test is required, not a grep" (L137–138).
- INT (computer science): whether something is a *bound parameter* or a *hard-coded constant* is a semantic property, and token matching cannot see it (see P4).

**O6: Having a function is not the same as having a mechanism.** *(F0001)*
- Evidence: PKS generation is described as "The RESPONSIBILITY exists … The COMPONENT does not … the port exists; the adapter is a person" (L144–150). P3 sets the threshold for becoming a component: the function is exercised, it is exercised by someone other than the originator, and it has at least 2 instances (L142).
- INT (statistics): P3's condition (2) is an **independence** requirement and condition (3) is a **replication** requirement. It works like a replication criterion, in governance dress.

**O7: The lifecycle is meant to be a cycle, but its closing edge is empty.** *(F0001)*
- Evidence: L-1…L-6, where "L-6 evidence → improve KnowledgeOS — EMPTY" (L182). Earlier, "the empty one closes the loop" (L257).
- INT (control theory): as described, the system is **open-loop**. Its self-correction mechanism is designed but, formally, has not run.

**O8: Nothing governs genesis.** *(F0001)*
- Evidence: "the platform governs change; it does not govern genesis … the second box has no governing rule" (L184).
- INT (logic): an inductive rule system needs a base case (see P8).

**O9: A tool verdict of INCONCLUSIVE was treated as neither PASS nor FAIL, and it changed a decision.** *(F0001)*
- Evidence: *"absence of evidence is not PASS"*. KP-n was not minted after the check (L14, L224–225).
- INT (logic): the verdict logic has **three values**, and the corpus explicitly refuses the closed-world assumption.

**O10: The document corrects its own claim to novelty, on the same day.** *(F0001)*
- Evidence: an annotation reads "P1 WAS RE-DERIVED, NOT DISCOVERED … cognate, not duplicate" (L95–101). Yet L29 still says "the concept this baseline contributes".
- INT: the corpus corrects itself *by annotation*, and the claim it corrects is left standing. It keeps both the claim and its correction.

---

## After F0002 — compare, challenge, connect

**O11: Claim strength is bounded by source grade, and the same rule is applied to citations.** *(F0002)*
- Evidence: sources are graded [FETCHED] or [CANONICAL], and "No source is cited beyond what its grade supports" (L9).
- Connects to O2: the *same* ceiling rule now governs external citations as well as internal instance counts.
- INT: this is a general **evidence-ceiling principle** that shows up at two scales.

**O12: `authority = provenance × standing`.** *(F0002)*
- Evidence: "derivation is an immutable provenance fact · lineage ≠ 'certified' flag — two independent fields" (L26).
- Connects to O3: "authorship ≠ authority" becomes explicit **orthogonality**. Provenance says *where something came from* and never changes. Standing says *what status it has been granted* and can change through acts.
- INT (mathematics): two axes, possibly a product order.

**O13: Derived projections are non-authoritative until attested.** *(F0002)*
- Evidence: the CQRS read-model analogy, and "derived statements become authoritative by audit + signature" (L27).
- INT: derivation, a *production* relation, never creates standing by itself. It takes an attestation act.

**O14: "Evidence EARNS · governance GRANTS".** *(F0002)*
- Evidence: TRL, GRADE and CMMI are cited as all separating evidence accumulation from adoption decisions (L28).
- Connects O2, O3, O12 and O13. INT: evidence is *necessary* and authority is *sufficient*. These are two different operators.

**O15: Even positive results are scoped to their level.** *(F0002)*
- Evidence: the source says the *problem* is validated, while the solution claims "remain ours to prove operationally" (L59–60).
- INT: the corpus keeps "is the need real?" separate from "does our design work?".

**O16: The admission rule is a value-of-information criterion.** *(F0002)*
- Evidence: "a source enters only if it materially changes a confidence or identifies a new gap" (L11).
- INT (statistics/decision theory): evidence is admitted only if it has **positive expected information value**. This is a stopping rule for literature search.

**O17: One label can carry two standings.** *(F0002)*
- Evidence: PKS-as-governed-context is TRIPLE-SUPPORTED, while "the governance-grade PKS framing itself" is a UNIQUE HYPOTHESIS. "Kernel needs a second product" is triple-supported, while "kernel set-sufficiency" is a hypothesis that is also described as "falsified at n=1" (L55–57). The totals, 11+3+5=19, exceed the 17 rows.
- INT: the unit that carries a standing is a *proposition about* a concept, not the concept. The count mismatch is the symptom (see C3).

**O18: An absolutist claim was withdrawn.** *(F0002)*
- Evidence: *"the track is done producing" was too absolute* (L10).
- INT: the corpus has a recurring **overclaim → correction** dynamic (O10, O36).

---

## After F0003 — refine the emerging understanding

**O19: Canonical status is reserved for human acts, and programme output is capped at Candidate.** *(F0003)*
- Evidence: the definitional guard (L22), and "every Canonical row below is human-made".
- Connects to O3 and O14. INT: machine-generated knowledge has an **authority ceiling**. In modal terms, the programme can raise the evidence value E(p) but can never assert the authority value A(p).

**O20: Canonical status can hold without evidence.** *(F0003)*
- Evidence: C-12 is "the frozen Phase-02 six-context map … canonical though never-consulted" (L43). C-9 "Governance precedes automation" is canonical (L41), even though F0002 found the literature gives it only partial support (F0002 L33).
- INT: this is the **converse** of O19. Authority does not imply evidence. Together, O19 and O20 show that the two axes are independent **in both directions** (a key point for H1).

**O21: Standing moves downwards as well as upwards.** *(F0003)*
- Evidence: "'the space map is the real ownership map' downgraded finding → HYPOTHESIS" (L24). There are 12 rejected items, each citing what falsified it (L89–104).
- INT: the state space allows demotion. It is not a monotone ratchet.

**O22: The corpus notices that its own status vocabulary mixes two dimensions.** *(F0003)*
- Evidence: Deferred refinement B says "Rejected is an outcome, not a state; Open Question is unresolved work, not knowledge". It also notes that the reviewer's sketch orders Candidate before Hypothesis, "the reverse of this corpus's usage" (L17).
- INT (mathematics): the five buckets are not one chain. They combine a lifecycle chain (Hypothesis < Candidate < Canonical), a terminal outcome (Rejected) and a work queue (Open).

**O23: A single hierarchy is replaced by four partial orders.** *(F0003)*
- Evidence: K-3 names "authority · production · realization · containment", and R-4 rejects "the single-stack hierarchy" (L51, L96).
- INT (order theory): replacing a single stack is what you do when the order has **dimension > 1**, meaning independent orderings cannot be embedded in one total order. The names line up suggestively with O12 (authority, production) and with P5 (realization). The definitions themselves are not in the pilot corpus.

**O24: The source claims "nothing is in two states".** *(F0003)*
- Evidence: "every row has exactly one bucket … Nothing is in two states; nothing is stateless" (L118).
- INT: the source claims that status is a **total function**. That is achievable only if propositions are split more finely than F0002's rows (O17). See C3.

**O25: Only evidence of a particular kind may reopen a question.** *(F0003)*
- Evidence: "EXERCISE the architecture; only operational evidence reopens discovery" (L18).
- INT: this restricts *which evidence type* may trigger *which transition*, so the transitions are typed.

---

## After F0004 — test the emerging patterns

**O26: The dashboard applies the model to itself.** *(F0004)*
- Evidence: it calls itself a "PROJECTION … derived … non-authoritative until attested (corrected I-4)" (L5).
- Test of O13 (passed): the rule is used *reflexively*. The corpus runs on its own theory-in-use.

**O27: Only two event types may change state.** *(F0004)*
- Evidence: "rows change only when EVIDENCE lands or an AUTHORITY rules — never on a schedule, never by re-reasoning" (L7).
- INT (computer science/DDD): this is an **event-sourced state machine** with two domain-event types, EvidenceRecorded and AuthorityRuled. The documents are read models.

**O28: Evidence is stratified by the level of the thing it changed.** *(F0004)*
- Evidence: the back-edge is "n≈3 informal", "FORMAL n=1 at the INSTRUMENTATION level", and "canon-level formal traversal still n=0" (L23).
- Test of O7: the claim "L-6 empty" is refined, not refuted. The answer to "has the loop closed?" depends on the *level* at which you count.

**O29: "Evidenced, unmeasured (no unguided baseline)".** *(F0004)*
- Evidence: L24, for R-6 *guides product engineering*, which has n=3 formal instances.
- INT (statistics): the corpus recognizes that it lacks a **counterfactual control**, so it cannot attribute effects to guidance. There is also a confound: the constraints were obeyed by the same programme that wrote them, so the instances are not independent (compare O6, P3 condition 2).

**O30: Every open claim carries its own promotion condition and its own decider.** *(F0004)*
- Evidence: columns "What would move it" and "Decided by" (L14).
- INT: each hypothesis is stored together with its falsification/promotion test and the authority for it. It is close to a pre-registration format.

---

## After F0005 — new synthesis (F0005 is dated 2026-08-02)

**O31: Gaps are typed by what closes them.** *(F0005)*
- Evidence: architectural gaps close by *modelling*, governance gaps by *a ruling*, evidence gaps "only by doing it" (L18–22).
- Connects to O27: two of the three closers, ruling and doing, are exactly F0004's two admissible events. The third, modelling, is the kind of change F0004 *forbids* as a row trigger ("never by re-reasoning"). See C7.

**O32: Maturity is not enforcement, and enforcement sits in the wrong layer.** *(F0005)*
- Evidence: "enforcement lives in the RUNTIME ADAPTER; capabilities live in the PLATFORM. Enforcement exists exactly where governance does not" (L80). The platform is "adoptable by habit, never by mechanism" (L88).
- INT (computer science): policy and mechanism are separated, but nothing binds them together, so the platform's policies are **advisory by construction**.

**O33: There are three enforcement modalities.** *(F0005)*
- Evidence: BOUNDARY (deny ×19), TRIGGER (ask ×22), ADVISORY ×16 (L74–78).
- INT (deontic logic): these correspond to *forbidden*, *permitted subject to consent* and *permitted, with a notice*.

**O34: Classifying is allowed; materializing is barred.** *(F0005)*
- Evidence: "BRM-1 retired extraction-by-COPY … not extraction-by-DECOMPOSITION … decomposition followed by materialization is still barred" (L185–189).
- INT: a *judgement about* an artifact is kept separate from *producing a new artifact*. This parallels "evidence earns / governance grants": you may know something without being permitted to act on it.

**O35: The source proposes a base case for genesis.** *(F0005)*
- Evidence: "at day zero the stakeholder's stated need is the admissible evidence input, and the first decision is recorded Provisional/Proposed" (L162).
- INT (logic): this adds an **axiom** to an inductive system that had none.

**O36: Overclaims are corrected by distinguishing levels.** *(F0005)*
- Evidence, three corrections:
  - "Strategic Discovery is not n=1" (92 artifacts; "the n=1 belongs to bootstrap, not to the method") (L14, L49).
  - "0 of 11 enforcing is too strong" (L70–72).
  - "a specification is not an implementation" (L98).
- INT: each correction separates two **levels** that had been merged: method vs bootstrap, capability vs runtime, specification vs implementation. See P5.

**O37: A term is flagged for retirement because it names something that doesn't exist.** *(F0005)*
- Evidence: *"Capability Runtime" names a runtime that does not exist — there is one script* (L100).
- INT: the corpus polices names for *existential overclaim*. A name should not imply a realization level that has not been reached.

**O38: The self-description and the observed behaviour differ.** *(meta-observation across F0001–F0005)*
- Evidence: F0001 describes KnowledgeOS as a platform that *creates PKS* for products (L31). Across all five files, generation stays at n=0 (F0001 L25, F0002 L37, F0003 H-5, F0004 L22, F0005 L51). What every file actually *does* is track the evidence and authority status of claims.
- INT: this is the *espoused theory vs theory-in-use* distinction, which the corpus itself cites in F0002 L32. Here it applies to the corpus.
