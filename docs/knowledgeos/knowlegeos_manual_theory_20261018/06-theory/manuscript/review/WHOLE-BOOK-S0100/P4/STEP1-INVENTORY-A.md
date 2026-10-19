*Whole-book review S0100, part P4, reviewer A (opus). Step 1, blind inventory, saved by the Professor from the hand-back, unedited, 2026-10-19T19:55:24Z.*

# P4 (Ch. 6 and Ch. 9) — Step 1: blind inventory of five sources

**Isolation statement.** I did not open the book (`R/BOOK-UNDER-REVIEW/`) at all. Of the brief I read only the headings list and the table "Your part's allowed references" (BRIEF.md L43–L122). I did not read the brief's other sections. All five sources were checked against `CORPUS-ALLOWED.tsv` before I read them.

Throughout, **[I]** marks my own reading. Everything else reports what the source says or what I counted myself.

---

## Source 1 — S0071 `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md`

**Read in full (379 lines). Commit 1c79b95fc, 2026-08-17.**

### What kind of document it is
- A tactical-DDD domain-model proposal for "BC-7 Governed Session Orchestration". It says itself that it "does not self-adopt" (L8–9). The work was done by an Architecture terminal that wrote its own seq-3 START into the record (L11).
- It sets itself a "discovery guard": do not assume WorkItem is the root; test four candidates (L13).

### Main claims
- **The subject already runs.** 15 records and 117 transitions existed beforehand, so the work is "reconstruction under a modelling lens, not invention" (L31).
- **Census (L37–48):**
  - transition types: HANDOFF 34 · REGISTER 33 · START 32 · COMPLETE 16 · STOP 1 · CANCEL 1;
  - CONTINUATION and FAIL never exercised;
  - `humanAct` present on START 32/32 and on COMPLETE 3/16;
  - `note` present on COMPLETE 16/16;
  - `recordedBy`: governance 83 · human 33 · architecture 1;
  - 41 grants, all AUTHORIZED;
  - `mutationOwner` stored 0 times, but 4 + 5 references in the mechanism.
  - **My arithmetic check:** the type sum is 117, and the recorder sum 83+33+1 = 117. Both are internally consistent. The human count of 33 = 32 STARTs + 1 STOP. That last split is confirmed only by S0078's cross-tabulation, not by S0071 itself.
- **The "single most consequential measurement"** (L50): mutation ownership exists only as a computation. It is "written nowhere and derived everywhere", and was seen derived live at seq 3.
- **Ubiquitous language with three classes**, `Observed` / `Inferred` / `Proposed` (L56–74):
  - Fold is "the only source of machine-truth" (L72).
  - Declared Attribution: `recordedBy` and `executionContext` are claims, not authenticated identities (L74).
  - Human Act exists outside the software; BC-7 holds only its registration (L70).
  - "Recorded Occurrence" is a `Proposed` term: what happened, in what order, with what declared attribution, "never whether it was right, authorized, or true" (L73).
- **Aggregate test** on identity, lifecycle, invariants, consistency boundary and ownership (L80–126). The decisive rule is that "an invariant that spans several instances of a candidate proves the candidate is not the root" (L80).
  - WorkItem passes (L92).
  - Assignment fails: I-1 spans the set of assignments, and HANDOFF is atomic across two (L100–104).
  - Workflow is a classifier, so a value object (L115).
  - Human Act has no identity or lifecycle in BC-7, and making it a root would duplicate a concept owned elsewhere (L126). L218 adds: "importance is not ownership".
- **One aggregate or two (L128–145).**
  - L134–135: START never consults grants, and `authorized` "never consults the session". So no positive invariant spans the two parts (L137), which "argues *for* splitting".
  - It keeps one root anyway, because I-4 ("never merged") is a negative spanning invariant that needs a holder, and the write boundary is one file (L141–145).
  - The split is recorded as RA-4, "the strongest rejected option" (L219).
- **Model (L163–208):**
  - derived projections (state, ownership) are "never stored, never settable";
  - T-1: "no derived projection may be stored, set, or accepted as input" (L208, L240, DP-6 at L279).
- **Invariants (L229–243):**
  - owns vs preserves, I-1 … I-10;
  - T-1 … T-4 are `Proposed`;
  - the I-5 row notes "one instance of a non-governance, non-human writer class in the wild" (L237).
- **Events (L249–262):** "recorded facts, not published events", because no subscriber mechanism exists. The asymmetry 33/32/16 (REGISTER/START/COMPLETE) is recorded but not judged (OQ-8).
- **Policies (L268–293):**
  - Domain policies answer "what must be true of the record"; governance policies answer "who may decide" (L268).
  - L282: all domain policies are record-consistency rules, and this is called "the tactical proof of the boundary".
  - L293: BC-7 "can refuse a *malformed* act. It can never refuse an *unauthorized* one."
- **Boundaries (L301–330):**
  - `GrantScope` is an opaque string compared by equality and never parsed (L307).
  - There must be no "may X do Y?" operation (L309).
  - Knowledge truth is *adjudicated*; BC-7 truth is *folded*; the two must not mix (L322).
  - L324: "That the two stores obey different rules is evidence the boundary is real."
  - L330: "BC-7 records the claim; the attribution model adjudicates it."
- **Open questions (L334–356):** OQ-4 to OQ-11, among them:
  - OQ-5: "a concept with no instance cannot be modelled as established" (L349);
  - OQ-6: is Grant revocable or immutable;
  - OQ-9: one aggregate or two;
  - OQ-10: is the recorder class an open set.
- **Confidence table (L362–369).**

### Concepts and distinctions worth keeping
- recorded vs authorized;
- stored vs derived (fold projection);
- owns vs preserves;
- declared vs attested attribution;
- malformed vs unauthorized;
- adjudicated vs folded truth;
- a declared vocabulary item vs an exercised concept (L154, L221);
- importance vs ownership (L218).

### What it leaves open
- OQ-1 to OQ-11.
- Whether the "invariants" are domain requirements or simply what the code happens to do.
- The denominator of "2 of 5 transition types" (L237). Eight types are declared at L251–260.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - current state as a fold over an append-only history;
  - authority held as a reference to an outside human act, never evaluated;
  - attribution recorded as a claim;
  - "a concept with no observed instance is a claim, not a fact" (L154/L349);
  - recording an alternative you rejected so it is not silently reinvented (L212–221).
- **Reject or downgrade:**
  - **[I]** L134–137 "never consults the session" is labelled `Observed` but is false (see S0073/S0078). A source-level `Observed` tag can be wrong.
  - **[I]** L282 "tactical proof of the boundary" and L324 "evidence the boundary is real" overreach. A context whose code decides only record consistency does not *prove* that no authority sits elsewhere in it, and different storage rules are consistent with many explanations.
  - **[I]** Several invariants are tagged `Observed` "in source" (L231–243). What is observed is mechanism behaviour. Treating that as a domain truth is the *implementation → architecture → theory* loop that L31 itself signals ("reconstruction"). A theory should cite these as **observed mechanism properties**, not as derived domain laws.
- **Keep open:** OQ-5, OQ-6, OQ-9, OQ-10. Also the 50% non-completion asymmetry: is it a bookkeeping gap or reality?

---

## Source 2 — S0066 `docs/knowledgeos/developer_guide/developer-guide-set-up-ai-agents-with-knowledgeos.md`

**Read in full (918 lines). Commit f24edf316, 2026-08-28. Undated in the text, with no epistemic tags.**

### What kind of document it is
- L1 is a chat-reply opener ("Yes. Based on the architecture we have established…"). **[I]** It is a pasted AI answer, prescriptive, and grounded only in "the architecture we have established". It cites no evidence.

### Main claims
- **Central rule:** "Configure the AI agent to navigate and consume KnowledgeOS; do not copy KnowledgeOS into the agent harness" (L5). Restated as "One project engineering truth. Multiple AI execution harnesses" (L49) and as the closing sentence (L916).
- **Two responsibilities** (L55–83): agent execution belongs to the harness; engineering knowledge belongs to KnowledgeOS. "Never reverse this relationship" (L99).
- **"Linked, not copied"** (L103–146). Copying "creates a competing source of truth" (L142).
- **Entry point** `docs/knowledge/portal/INDEX.md` (L157). Authority locations are listed (L207–215). "Do not guess which documentation is authoritative" (L162).
- **Contract contents:** identity, authority, navigation, operating behaviour, safety (L271–326).
- **DDD order:** problem → … → invariants → architecture → ADR → implementation → verification (L338–358). "Start with the responsibility and boundary being changed — not with the class to edit" (L362), so that existing code does not dictate the model (L364).
- **Authority hierarchy** (L417–433): KnowledgeOS/Governance → Architecture → DDD → ADRs → Design → Implementation → Verification. Its purpose is to stop "implementation → claimed architectural authority" (L435–453).
- Shared scripts stay agent-neutral, and duplicating them would duplicate a rule (L473–509).
- Peer harnesses must not modify each other (L555–572).
- **"Using KnowledgeOS ≠ Changing KnowledgeOS"** (L582–598).
- **Stop conditions** (L606–630).
- **Authorized / Implemented / Verified / Completed are separate states** (L646–671):
  - proposal ≠ approved;
  - code exists ≠ authorized;
  - tests pass ≠ completed.
- **Project-state artifacts** (L683–704): ask what an artifact represents, who owns it, who writes it, and whether it is authoritative or derived. Do not migrate memory files before ownership is established.
- A read-only cold-boot qualification (L710–734), plus independent verification so that the creator is not the only reviewer (L740–763).
- What-not-to-do list (L833–855), including "Do not treat agent memory as authoritative engineering truth" (L853). Definition of Done (L861–882).

### Concepts and distinctions worth keeping
- harness vs knowledge;
- reference vs copy;
- use vs change;
- the four separate work states;
- authoritative vs derived state artifact;
- self-review vs independent review.

### What it leaves open
- What KnowledgeOS *is*. It is treated as a folder of authorities ("Project Engineering Truth", L894–899).
- How conflicts between levels of the hierarchy are resolved. It only says stop and ask.
- Why Architecture ranks above ADRs (L422–426), when ADRs are architecture decisions.
- How "linked, not copied" is checked, and what happens when links break.
- Whether the four states are exhaustive or ordered.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - single source of truth with references instead of copies (a non-duplication principle);
  - use ≠ change;
  - the separation of authorization, implementation, verification and completion;
  - the authoritative-vs-derived question for state.
  - **[I]** These line up with S0071's stored-vs-derived and recorded-vs-authorized distinctions.
- **Reject as evidence:** **[I]** The document carries no observation. It is normative guidance from one AI reply, and the paths it lists are claims about the repository that I did not verify. Its "never" rules are stipulations. It can serve as evidence of a *stated design intent*, not of what the system does or of a theory result.
- **Keep open:**
  - **[I]** whether the hierarchy is a total order;
  - **[I]** an internal tension: the guide restates rule content (the DDD sequence and stop lists) that it says belongs only in KnowledgeOS.

---

## Source 3 — S2834 `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-131918_what-knowledgeos-makes-possible-beyond-conventional-ai-rag.md`

**Read in full (2245 lines). Commit e3e47b139, 2026-09-12. A brainstorming transcript with two voices.**
- **Part A (L1–1881):** a capability essay listing 60 capabilities.
- **L1882:** the user's question, "Do you agree with the writing ?"
- **Part B (L1884–2245):** an assessment that corrects Part A.

### Part A main claims
- **Goal:** preserve the distinctions along acquired → represented → interpreted → supported → verified → revised → determined → acted upon (L15–33).
- **Key question** (L41): "what does the system know, why, under which contract, from which evidence, with what uncertainty, and what follows".
- **Chain of stages:** Reality → Observation → … → Action → Outcome (L59–85). Each step "has explicit semantics" (L89).
- **Capabilities, grouped:**
  - Status set (L104–111): supported / refuted / unknown / conflicted / inferred / determined / retracted / superseded.
  - Unknown ≠ False (L155–157). Contradiction is data, not system failure (L187–188).
  - Lineage and provenance as part of the knowledge state (L205–255).
  - Source ≠ claim (L265–281). Supports(E,p) ≠ True(p) (L297–301).
  - Determine(p|C) ≠ True(p) unless a soundness bridge is represented explicitly (L315–329).
  - Inquiry semantics, q = g∘T, as the adequacy test: "Lossless for which inquiry?" (L339–437). This relies on "Part LXXIX", which is not in this source.
  - Revision without losing history; "what did we know then / when did we learn" (L441–514).
  - Candidate → Supported → Determined → Committed, i.e. discovery ≠ acceptance (L518–540).
  - Hallucination as a diagnosable failure class (L580–602).
  - Contract-aware retrieval (L636–652). Gaps G = C∖satisfied (L686–704).
  - Unknown ≠ Uncertain (L866–878).
  - Determine ≠ Decide ≠ Authorize ≠ Act (L884–918).
  - Stale decisions and epistemic impact analysis (L950–1135).
  - Bounded-context meanings with explicit mappings φ (L1040–1055).
  - Ownership of propositions (L1084–1093).
  - Formal / computational / empirical test levels, so that "CodeWorks ≠ TheoryIsCorrect" (L1345–1363).
  - Stipulated vs derived vs observed vs hypothesized architecture (L1391–1409).
  - Necessity and sufficiency claimed separately for minimality (L1415–1439).
  - Theory → Specification → Implementation → Verification, so "architecture is downstream of verified semantics" (L1513–1521).
  - Proposition ≠ database record; data migration ≠ knowledge migration (L1529–1585).
- **Ultimate architecture:** a "closed epistemic feedback system" (L1617–1653).
- **Non-guarantees** (L1717–1739).
- **"Strongest achievable property"** (L1751–1757): make the epistemic status of claims explicit, traceable, revisable, contract-relative and machine-operable.
- **Own next step:** a capability-to-theory traceability audit (L1881).

### Part B main claims (the self-correction)
- The text mixes three claim levels: established, defined but not proved, and future capability (L1906–1912).
- "After implementation, KnowledgeOS can …" is too strong. It should read: "an implementation conforming to the theory/specification would be capable of X, provided…" (L1918–1950).
- The "closed feedback system" is a candidate model, not a proven one. Outcome ≠ knowledge and observation ≠ evidence; each transition needs semantics (L1984–2033).
- "Knowledge" is overloaded. Separate E_t (epistemic state), A_t (attributed state) and K_t, with factive Knows ⇒ True kept separate (L2039–2081).
- **"Capabilities ≠ Kernel Operators"** (L2085–2134). There are foundation capabilities and derived ones. For example, impact analysis is derivable from provenance + dependency + lifecycle + determination, so it is not a kernel primitive (L2152–2160).
- The audit should have two dimensions: semantic capability and kernel necessity (L2140–2148). A claim-classification set is proposed (L2190–2202).
- Verdict: implementation claims are "ahead of the evidence" (L2219).

### What it leaves open
- Every formal object is used but not defined here: Γ, warrant, determination, the dependency semantics, Cr(p).
- Part B cites "experiments" and "the factivity experiment" (L1940, L1999, L2049) and uses status tags such as [CORPUS] and [PROP] (L1910, L2176–2183) without giving any evidence in this source.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - **[I]** the distinction set (L1892–1902 summarises it);
  - Unknown ≠ Uncertain ≠ Conflicted;
  - evidence ≠ truth ≠ determination;
  - determination ≠ decision ≠ authorization;
  - adequacy relative to an inquiry;
  - capabilities ≠ kernel operators;
  - necessity and sufficiency proved separately;
  - "CodeWorks ≠ TheoryIsCorrect".
- **Reject:**
  - **[I]** Part A's capability claims as established. Part B already downgrades them.
  - The "closed feedback system".
  - **[I]** The source holds at least four different status ladders or sets:
    - L1249–1261: Observed / Derived / Accepted / Canonical;
    - L1391–1405: Stipulated / Derived / Observed / Hypothesized;
    - L1447–1465: Observed → Declared → Inferred → Hypothesized → Falsified/Validated → Derived → Architectural → Governed → Canonical — an ordering in which "Declared" follows "Observed" and "Derived" follows "Validated", which is incoherent as a ladder;
    - L2190–2202: Part B's claim-classification set.
  - A theory cannot adopt any one of these as *the* status model on this source's authority.
- **Keep open:**
  - the foundation-capability list (L2093–2101), which is asserted, not derived;
  - **[I]** a tension with S0071: S2834 L1521 puts architecture downstream of verified semantics, while S0071 L31 rebuilds the model upstream from a running mechanism. A theory must say which direction a given claim was derived in.

---

## Source 4 — S0073 `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-verification-2-report.md`

**Read in full (213 lines). Commit 5dbf14931, 2026-08-17. Independent verification of the *refinement* (S0078), not of S0071 directly.**

### Main claims
- **Recommendation: Option A (approve)**, subject to two mechanical corrections (L7–12).
- **Identity disclosure** (L14–44): independence is declared, not attestable. **Model separation from the refinement's author is "Unknown, and material"** (L34–40): the process may be the same model as the refinement's author, so a shared blind spot is possible.
- **Method** (L46): re-derive from source and from 15 live records *before* reading the refinement, so that agreement is "convergence, not deference". This is declared, and unverifiable (its own row 16, L89).
- An open item is carried rather than glossed: a grant amendment was approved but arrived with no content (L48).
- **Evidence table** (L72–90):
  - `authorized` requires a session and refuses unknown sessions, and its verdict uses grant status plus scope equality only (L74);
  - START has 0 grant references (L75);
  - `assertTransitionAllowed` folds the record first (L76);
  - there is no key whitelist (L77);
  - census: 130 transitions and 48 grants, all AUTHORIZED; 0/130 derived keys stored (L81–82);
  - cross-tab: REGISTER 37, START 37, COMPLETE 16, CANCEL 1, STOP 1, HANDOFF 36 + 2 (L83). **My arithmetic: the sum is 130, consistent.**
  - 20 stored keys are never read by the mechanism (L84);
  - `recordedBy` has two referents depending on transition type (L88).
- **F-1/C-1 supported** (L96–110): "never consults the session" is false. A precondition on who may answer is different from what the verdict is computed from, and "equality-matching is not interpretation" (L105). F-1 strengthens the one-root choice and weakens RA-4 (L108).
- **C-2 supported** (L112–124). T-1 holds by non-consultation, not by refusal. The original T-1 was "false as an absolute" (L124).
- **Falsified measurement** (L126–138): the refinement's "0 non-canonical keys" is false; the real count is 20. It "strengthens T-1b" and is "a false high-confidence `Observed` claim … a defect in its own right" (L10).
- **C-3 is classification (D), a mis-scoped invariant statement** (L140–151). I-5 governs authority writes only. Both `architecture` HANDOFFs are made by the required actor. A HANDOFF confers nothing.
- **Ubiquitous-language defect** (L153–157): `recordedBy` names the origin of the act on START and the acting role elsewhere. "6 of 37 STARTs … state Governance performed the registration", and so a reader "gets the wrong answer from the field on 37 of 37 STARTs".
- **Risks** (L178–186):
  - R-4: census drift (126/47 → 130/48), with the advice to date every census;
  - R-6: independence is only declared;
  - R-7: `identity` returns the last grantId regardless of session.

### Concepts and distinctions worth keeping
- answerability precondition vs verdict function vs consistency invariant;
- the enforcement modes: refusal / overwrite / non-consultation / writer obligation;
- re-derive first, compare second;
- convergence vs deference;
- declared vs attestable independence;
- process separation vs model separation;
- censuses are time-indexed.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - **[I]** Strong population-level evidence: real counts and denominators, re-measured, with internal sums that check.
  - Independent re-measurement found a false `Observed` cell, so an `Observed` tag is fallible and needs a date and a method.
  - "Prevented" vs "never happened" (writer obligation vs structural guarantee) is a needed epistemic distinction.
  - Reviewer independence is a claim with its own status.
  - **[I]** I checked S0073's line citations into S0071 (L45, 135, 137, 208, 237, 240, 279, 307, 354). Each points to the stated content, so its quotation discipline holds on this sample.
- **Downgrade:** **[I]** "37 of 37" at L155 generalises from 6 explicit cases. S0071 L11 also shows one START written by an Architecture terminal, not by Governance. The referent ambiguity is well supported; "Governance performed every START" is not.
- **Keep open:**
  - model separation (L34–40);
  - the missing grant amendment (L48);
  - OQ-13 / R-7.

---

## Source 5 (my choice) — S0078 `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-domain-model-refinement.md`

**Depth: skim.** I read L1–160 and L193–323 and skipped L160–192, the C-2 wording proposals. 323 lines; commits 04420fbb0, 58af43020, 48bc0dfc1, all 2026-08-17.

**Why this source:** it is the missing middle link. S0071 is the proposal; S0078 is the refinement; S0073 verifies S0078. Without it, S0073's verdicts cannot be read against what they verify, and the S0071 → S0078 → S0073 chain is the cleanest case of a claim being corrected inside the corpus.

### Main claims
- **Status headers** (L8–21): later annotations mark the document ACCEPTED and say three corrections were applied after S0073, by the same process that wrote S0073 (L21).
  - **[I]** So the S0078 text on disk is *not* the version S0073 verified. For example, L67 already holds the corrected "20 instances" cell. Quotes from S0078 must state which version they come from.
- **Exposure disclosure** (L38): the refiner saw the ARB's candidate framings before deriving its own.
- **Input-boundary extension** (L40): "primary source is not a position".
- **Census at seq 12** (L58–67): 126 transitions, 47 grants, and the new `recordedBy` × type cross-tab. **My arithmetic: 36+35+16+1+1+37 = 126, consistent.**
- **Drift:** 117 → 121 → 126 transitions; "only denominators moved" (L71).
- **F-1** (L79–100): the three-way table separating precondition, verdict and invariant (L92–96). §4.5 overreached by generalising from "no invariant spans them" to "never requires consistency" (L98). Three sites in S0071 are corrected (L102–128).
- **C-2** (L138–153):
  - refusal / overwrite / non-consultation;
  - "There is no key whitelist";
  - the structural point that the submitter never supplies the state its transition is judged against (L146);
  - T-1 as an absolute "is false" (L152).
- **C-3 is (D)** (L193–234), with evidence E-1 to E-8:
  - the D.1 conflation of I-4 and I-5;
  - D.3: "An instance that the model predicts is not a counter-example";
  - D.5: no context owns "the ability to produce a lifecycle fact"; it is "a property of access to the mechanism".
- **N-1:** `identity` reads both parts of the record (L266).
- **N-2:** date every census (L268).
- **OQ-12:** should invariants carry an enforcement class (L314)?
- **OQ-13:** `authorizationLinkage` (L315).

### What a theory must take, reject, or keep open
- **Take:** the enforcement-class idea (OQ-12) and the precondition / verdict / invariant split.
- **Reject or check:**
  - **[I]** The same process that verified the work (S0073) also applied the corrections (L21). Together with S0073's "model separation Unknown", the chain proposal → refinement → verification → correction has declared rather than shown independence.
  - **[I]** D.3 ("the model predicts it, so it is not a counter-example") is valid only if the prediction was made before the observation. T-4 was proposed in S0071 *after* the first `architecture` recorder had been observed (S0071 L45/L237).
- **Keep open:** OQ-10 (now with the referent question added), OQ-12, OQ-13.

---

## Cross-source points for step 2 (all [I])

1. **Recorded vs authorized vs determined.**
   - S0071 L293: can refuse malformed, never unauthorized.
   - S0066 L646–671: authorized ≠ implemented ≠ verified ≠ completed.
   - S2834 L884–918: determine ≠ decide ≠ authorize.
   - These are three statements, at different levels, of separating stages. Only S0071/S0073 rest on measurement; the other two are stipulated.
2. **State vs history.** S0071's fold (L72, L208) and S2834's current vs history (L441–475) are related. Only the BC-7 mechanism is observed; S2834's K_t is not implemented anywhere these sources show.
3. **Attribution and independence as claims.** S0071 T-4, S0073 L14–44 and S0078 L25–40 all say independence is declared. A theory that leans on "independent verification" (S0066 L740) must carry that limit.
4. **`Observed` is fallible and time-indexed.** Two `Observed` claims were falsified in this chain (S0071 L135 and S0078's first L67 cell), and the counts move between measurements.
5. **Direction of derivation.** S0071 rebuilds a model from implementation (L31). S2834 L1521 and S0066 L435–453 forbid implementation becoming authority. Any book claim that cites BC-7 as evidence for a governance law must show it did not run implementation → architecture → theory.
6. **Capabilities ≠ kernel** (S2834 L2128). Recurrence of a concept in a list is not evidence that it is a primitive.

---

## Files opened
1. `…/06-theory/manuscript/review/WHOLE-BOOK-S0100/P4/BRIEF.md`: headings list via grep, then L43–L123 only.
2. `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` (S0071): full.
3. `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-verification-2-report.md` (S0073): full.
4. `docs/knowledgeos/developer_guide/developer-guide-set-up-ai-agents-with-knowledgeos.md` (S0066): full.
5. `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-131918_what-knowledgeos-makes-possible-beyond-conventional-ai-rag.md` (S2834): full, in three reads.
6. `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-domain-model-refinement.md` (S0078): L1–160 and L193–323.
7. `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`: grep only, to check paths.

## Searches and commands run
- `grep -n "^#" BRIEF.md` and `wc -l BRIEF.md`, in `R/P4/`.
- `wc -l` on the four step-1 sources.
- `grep -c . CORPUS-ALLOWED.tsv`.
- `grep -n -e "bc7-domain-model-proposal" -e "set-up-ai-agents" -e "beyond-conventional-ai-rag" -e "verification-2-report" -e "BASELINE-003-domain-model-refinement" CORPUS-ALLOWED.tsv`.
- `wc -l` and `grep -n "^#"` on S0078.
- `git log --format='%h %ad %s' --date=short -- <file>` for each of the five allowed source files.
- Arithmetic checks were done by hand, with no tool.

**Isolation:** I did not open `R/BOOK-UNDER-REVIEW/`. I reached no file from the note's item 3, no sealed or hold-out file, and nothing else under `R/` apart from `P4/BRIEF.md`. I did not open `REVIEWER-NOTE.md` either; its text was given verbatim in the commission. I made no writes and ran no state-changing commands.
