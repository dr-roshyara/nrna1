# KnowledgeOS: research and implementation, one linked strategy (PROPOSED)

| Field | Value |
|---|---|
| Status | **Revision 3: PROPOSED, NOT APPROVED** (2026-10-29, on the Principal's "yes revision 3 of the strategy"; consistency corrections only, §8). **In force until approved: revision 2, APPROVED IN PRINCIPLE** by the Principal, 2026-10-29 (S0200 decisions D7 part A, D8 part B in principle only, D9 role names; decision record, addendum 2026-10-29T02:08:48Z). **Stage 1 is not authorised:** it returns as a separate decision with the implementation-candidates list. **Approval status, mandate status and the approved fingerprints are taken from the decision record, never from a file name.** Written by the Rule Reviewer (now the Governance Engineer), 2026-10-28, on the Principal's request ("we also need the implementation strategy"; scope chosen: "Both, linked") |
| Decides | the Principal |
| Changes | nothing in force; Rulebook v1.4 and the research continue as they are until the Principal decides |
| Revisions | see §8 |

*Labels: **[V]** verified in the lane's records · **[R]** reported by the Professor or reviewers, not reconstructed · **[I]** inferred · **[P]** proposed · **[O]** open.*

## 0. Roles (names approved, S0200 decision D9; Lead DDD Architect added 2026-10-29)

*Each role has three separate statuses — role defined (this table), mandate (approved revision), activation — kept in `GOVERNANCE-ENGINEER-STATE.md` from the decision record; none is inferred from another.*

| Role | Who | Does | Does not |
|---|---|---|---|
| **Principal (human ARB)** | the human | decides rules, normative questions, access, and every gate | — |
| **Governance Engineer** | an AI session (until 2026-10-28: "Rule Reviewer"; decision record, addendum of 2026-10-28T17:07:25Z) | keeps the rules and records, prepares planted-gap tests, evaluates the other roles, writes mandates and briefs, **communicates with the Principal and facilitates what the Principal wants**, records only what the Principal explicitly approves | writes theory or code; decides for the Principal |
| **Professor (Research Lead)** | an AI session | reads the corpus, builds the theory **and the strategic design derived from it** | writes code; reads the implementation |
| **Independent Reviewers** | fresh AI sessions per review | review the theory blind first, then challenge it | write theory |
| **Verification Auditor** | a separate AI session | audits the existing EKS implementation against the strategic design and the reference architecture; reports gaps with evidence | writes theory; writes or changes code; sends findings to the Professor |
| **Lead DDD Architect** | a separate AI session (added 2026-10-29, `MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md`) | reviews the settled strategic design as a DDD expert; derives the tactical design (with the stage-1 decision); reviews the Implementation Engineer's plans and slices; proposes architecture decisions | decides; writes theory or production code; reads the corpus; contacts the Professor directly |
| **Implementation Engineer** | a separate AI session (from stage 1) | builds the reference model and later the kernel, under the repository's engineering process | writes theory; audits its own work |

## 1. The idea in one paragraph

The research and the building run as **two tracks with gates between them**. The research track keeps reading the corpus and building the theory. The implementation track builds only what the theory has made **stable**, starting with an **executable reference model**, not a product. Every built rule cites the book claim it implements and that claim's status. What the building reveals goes back to the research as evidence. Neither track waits for the other to finish, but **no stage of building starts before the research gate that justifies it** [P].

## 2. Where we started (snapshot of 2026-10-28; the current state is in `GOVERNANCE-ENGINEER-STATE.md`)

| What | State | Label |
|---|---|---|
| Corpus read in order | about 190 of 2,734 sources (≈ 7 %) | [V] read log |
| Concepts | 47 VALIDATED, 122 PROVISIONAL, 96 INVALID as stated | [V] register |
| A formal core with test models | the evaluator CE\* (T/F/U with refusal reasons, total on a stated domain D), the kernel type (laws on states, steps, histories), the single-use guard (one owner per grant), designation of the record (a design sketch), the "pairs suffice" condition (Helly) | [V] book §9.10–§9.17; models `ce_model.py` v19, `helly_check.py` pass [R] |
| The trust boundary | kernel assumptions K1–K7 against world assumptions L1–L11 | [V] §9.15 |
| Not yet there | a fixed minimal primitive set; laws derived from it; parts of Req; whether a minimal kernel exists and is unique | [V] BOOK-OUTLINE |

**Consequence [I]:** the core is precise but **conditional and young**. It is enough to build an executable model that tests itself; it is not enough to build a product.

## 2a. The governing principle

**Let the theory be tested by executable models, but never let a model's successful execution become proof of the theory.** Three kinds of validation are kept apart, and none implies another:

| Kind | What it establishes | Established by |
|---|---|---|
| **implementation correctness** | the code follows its formal specification | tests |
| **formal-model adequacy** | the specification is coherent, and its assumptions and limits are explicit | independent review of the specification |
| **empirical adequacy** | the theory is consistent with the relevant real evidence, within that evidence's limits | the corpus, and stage-2 validation |

## 3. The stability scale: what may be built on what

| Level | Meaning | May be built as |
|---|---|---|
| **S0: unstable** | INVALID, or PROVISIONAL with open critical or high findings | nothing |
| **S1: experimental** | assumptions and limits explicit; reviewed; no open critical finding; a design sketch closed with its assumptions | the **reference model** only, marked experimental |
| **S2: reviewed core** | **VALIDATED by an independent reviewer**; its formal specification independently reviewed; required tests passed; no unresolved critical finding affecting the intended use | a **kernel prototype** |
| **S3: established** | derived from the fixed primitive set; survived a milestone (whole-book) review; scope and limits established | a **production kernel** |

**Tests never promote a claim.** Passing tests is evidence of implementation correctness only; promotion needs a reviewer's verdict and, from S2 on, a reviewed specification. Each implemented rule carries its book claim, CON-ID, level, assumptions, tests, dependencies and known limits (mandatory). A claim that drops a level **blocks** what was built on it until it is reassessed [P].

## 4. The stages and their gates

| Stage | What is built | Gate to start it | Gate to finish it | Who |
|---|---|---|---|---|
| **0. Now to S0200** | nothing; an **implementation-candidates list**: each kernel result with its claim, assumptions, level and open questions | — | the list confirmed by the Professor and approved by the Principal (the draft: `IMPLEMENTATION-CANDIDATES-S0200.md`) | Governance Engineer (list), Professor (claims) |
| **0b. Strategic design and independent verification** | nothing; (a) the **strategic design derived from the theory** (`MANDATE-STRATEGIC-DESIGN-PROPOSED.md`): subdomains, bounded contexts, context map, published language, ownership of invariants, each with evidence, stability level and open questions; (b) an **independent audit of the existing EKS** (`MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`): theory against reference architecture against code | the S0200 decision; (a) after the S0200 consolidation is settled, as a chain of theory turns with closure criteria and a budget; (b) the auditor may start preparing at once and audits once the strategic-design baseline is reviewed | (a) a reviewed strategic-design baseline with its open questions visible; (b) a gap report, every finding classified with evidence | (a) Professor and reviewers; (b) Verification Auditor; findings to the Principal and the Governance Engineer only |
| **1. Executable reference model** | one clean library from the existing models: the record and its D-check, CE\*, refusal reasons, the single-use guard, designation; **every law in scope with an explicit testing disposition** (a property test where justified; a model-based or indirect test where necessary; or a recorded limitation with what would resolve it — none silently omitted); no storage, no UI, no product | **in sequence, each a separate authorisation by the Principal:** (1) the stage-1 decision, with the candidates list approved; (2) the Lead DDD Architect's tactical design and ADR drafts; (3) the Principal's approval of that design and those ADRs; (4) the Implementation Engineer's readiness review and EP-01 plan, reviewed by the Architect; (5) the Principal's approval of the plan. **No code before (5).** Authorising a design is never authorising code | every law in scope has its testing disposition, and every executable test passes; every recorded limitation is visible and decided (a new one by the Principal); every assumption (K/L) is visible as a parameter or a stub | Lead DDD Architect (design), Implementation Engineer (build); never the Professor |
| **2. Validation against reality** | the reference model run on **real governance records** of the estate that are on the allowed list (grants, transitions, registrations, as the corpus contains them): does CE\* reproduce the estate's recorded decisions? Every disagreement is a finding | stage 1 done; **a named, versioned input list** of records and **a documented mapping** of each input into the model (the estate's record lies outside D, §9.15.6, so the mapping is itself reviewed), both approved by the Principal; any unlisted source stops the work until the Principal grants it | each disagreement classified, **with its evidence**: theory defect · estate defect · data gap · input-mapping error · outside the theory's scope · **unresolved**. The theory is never changed just to reproduce the estate's decisions; model results are implementation or estate evidence, never proof of the theory | Implementation Engineer; **findings to the Principal and the Governance Engineer**, who decide what reaches the Professor |
| **3. Kernel prototype** | the kernel as a component: append-only record with attestation, D-check at append, CE\* as a query, the owner rules, designation; a minimal application on top | the primitive set fixed (a theory turn), the core at **S2**, a milestone review passed | the prototype passes the stage-1 tests and the stage-2 cases | engineering |
| **4. Production kernel and applications** | hardened kernel; applications by the boundary test (what must the kernel provide, what can an application provide) | the laws **derived** from the primitive set (S3); the existence question answered **as it comes out**: unique (proof and assumptions documented), not unique (the alternatives and the criteria for choosing documented), or unresolved (then the architecture is not claimed to be theoretically established) | the product's own acceptance, security and operational reviews | engineering, with the Principal |

**Gates are conditions, not dates.** A stage starts when its conditions are met and the Principal approves, not when a source number is reached. *(For orientation only [I]: on the present pace the primitive set might be fixed around S0300–S0500; this is not a commitment.)* Reading continues throughout.

**Every stage has a stop condition:** when the model or the prototype exposes a critical theoretical gap, the affected work pauses, the finding goes to the research, and no undocumented rule is introduced to work around it.

## 5. How the two tracks feed each other

- **Theory → building:** only claims at the required level; each built rule cites its claim; a claim that changes triggers reassessment of what was built on it (the book's R3, applied to code).
- **Building → theory:** every disagreement found in stage 2 or 3 goes **to the Principal and the Governance Engineer**; the Principal decides, finding by finding, whether it reaches the Professor as **implementation evidence** (labelled Repo or Impl, never as corpus evidence): *the corpus intended X, the estate's records show Y*. It may open a fork; it never silently corrects the corpus (v2 proposal §4, the Principal's Q1 answer).
- **Independence:** the implementation session does not write the theory, and the Professor does not write the implementation. Otherwise the theory would quietly be shaped to fit the code (hindsight) [P].
- **Cost:** the implementation track runs in its own session and does not slow the reading. The Governance Engineer's added work: the candidates list, the level of each built rule, and the gate checks [I].

## 6. Risks and how the strategy meets them

| Risk | Answer |
|---|---|
| Building on theory that later fails | the stability levels; blocking on a drop of level; stage 1 is a model, cheap to change |
| The code shapes the theory | separate sessions; implementation findings enter the book only as labelled evidence, through review |
| Engineering loops (as F22 and designation became in the theory) | closure criteria per component before it starts; a component is "done" when its tests pass, not when it is perfect |
| The implementation drains the research | its own session; the research priority order (v1.4 §1) unchanged |
| Hold-out exposure through implementation data | stage 2 uses only allowed-list records unless the Principal grants a file; never anything under `docs/knowledgeos/` outside the list |
| Building for one estate only | the boundary test decides what is kernel; estate-specific rules stay in an application |

## 7. Decisions: settled and pending

*The decision record is authoritative for every item; this list only points to it.*

**Settled (decision record, addenda of 2026-10-29):**
- The two-track strategy is **approved in principle**, with its stability levels and gates (D8).
- **Part A:** the strategy opens no new route by which excluded material could influence the theory or an independent test; approval grants **no access** beyond the allowed list (D7).
- The role names (D9) and the Lead DDD Architect as a seventh role; the mandates of the Governance Engineer, the Verification Auditor, the Lead DDD Architect and the Implementation Engineer, and the strategic-design mandate, as approved.
- **Stage 1 is not authorised.** No kernel prototype and no production work are authorised.
- The Professor does not build.

**Pending (each a separate decision by the Principal):**
1. **The stage-1 decision** (proposed after the S0225 consolidation, which rotates Ch. 9 in full), with the **implementation-candidates list** confirmed by the Professor.
2. The tactical design and ADRs (from the Lead DDD Architect), including **where the code lives** and in which language.
3. The Implementation Engineer's first plan.
4. **Stage 2 data:** the input list and mapping (recommended: allowed-list records first; named grants later if a gap appears).
5. Part B of the Verification Auditor's audit (after a reviewed strategic-design baseline).

## 8. Revisions

| Revision | Date | Content | Status |
|---|---|---|---|
| 0 | 2026-10-28 | first version, on the Principal's request | superseded |
| 1 | 2026-10-28 | an external review's refinements: three kinds of validation; tests never promote a claim; an *unresolved* class in stage 2; the production gate conditional on the existence and uniqueness result; gates are conditions, not dates; a stop condition per stage; the S0200 decision in two parts | superseded |
| 2 | 2026-10-28/29 | roles (§0); stage 0b, strategic design and independent verification | **approved in principle** (D7–D9) |
| 3 | 2026-10-29 | consistency corrections after an external review (findings 1–6) and the Governance Engineer's check (7); nothing new authorised: (1) stage 1's entry gate in five separate authorisations, tactical design before code; (2) a testing disposition per law instead of "every law a property test", as the Implementation Engineer mandate; (3) §7 split into settled and pending, pointing to the decision record; (4) "Rule Reviewer" → Governance Engineer; status from the decision record, not file names; three role statuses; (5) stage 2: a named, versioned input list and a reviewed mapping, unlisted sources stop the work; (6) this revision table; (7) stage-2 and stage-3 findings go to the Principal first, as every mandate now says, not directly to the Professor | **proposed** |
