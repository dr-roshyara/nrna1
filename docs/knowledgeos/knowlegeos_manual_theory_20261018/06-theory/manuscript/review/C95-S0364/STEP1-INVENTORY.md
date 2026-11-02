C95 STEP 1: BLIND INVENTORY

Window C95, reviewer W, transition review (step 1 only). The independence here is independence of process, not of person.

## S0364: `docs/knowledgeos/brainstorming/kernel/20260823-111647-epistemic-lifecycle-domain-discovery-admission-as-first-transition.md` (1081 lines, read whole to line 1081)

**Depth: full.** This is analytic prose, not an operational log. It has two parts:
- **Part 1 (lines 1–600):** a lifecycle discovery. The second part says an AI model ("DeepSeek") wrote it.
- **Part 2 (lines 602–1081):** a second-round DDD assessment of Part 1, by an unnamed session. It starts after a separator at line 602.

### Ranked inventory

1. **Central claim (lines 106, 376, 389, 556).** Admission is the *first transition* of an epistemic lifecycle, not its endpoint. Part 1 says "the core domain act is maintaining the epistemic lifecycle, not just admitting claims".

2. **Status of that claim changes inside the source (lines 376, 613, 1064).**
   - Part 1 calls it "The Resolution" (line 376).
   - Part 2 first says "What I accept" (line 613), then grades it only "PROMISING" (line 1064).
   - Part 2 grades "Lifecycle exists" STRONG (line 1065). Exact states, the state/event distinction, the aggregate boundary, lifecycle ownership and the Kernel role are all OPEN (lines 1066–1070).

3. **Definition (lines 20–28).** The epistemic lifecycle is the "sequence of justified epistemic states" a claim can hold, plus the "constitutionally governed transitions" between them. It concerns the claim's **status, not its content** (line 28). Part 1 defines four terms: epistemic state, transition, justification and history (lines 22–26).

4. **Admission is distinguished three times (lines 378–381).** It is the *only* transition that:
   - crosses from outside to inside;
   - assigns identity;
   - creates a new claim.

   [I] This is in tension with "re-admission" being emitted as the same event `KnowledgeAdmitted` (lines 283, 303). It is also in tension with open Q10, re-admission after rejection and whether identity survives (lines 547–550).

5. **State sets (lines 38–102).**
   - Three perspective tables: epistemic (7 states), constitutional (6) and evidence (6).
   - They are synthesised into 8 states: PROPOSED, ADMITTED, CONTESTED, SUPERSEDED, RECONCILED, WITHDRAWN, REJECTED, INSUFFICIENT_EVIDENCE.
   - [I] Pending Review, Under Challenge, Unsupported and Contradicted are dropped or merged with no stated reason.
   - [I] The "evidence" table lists ADMITTED as an evidence state, which merges status with evidence assessment.

6. **Transition relation (lines 123–171).** 12 transitions, each with a trigger and a "constitutional precondition". The ownership table (lines 330–343) has the same 12 rows.
   - Terminal or absorbing states: REJECTED, WITHDRAWN, RECONCILED ("stable"). [I] RECONCILED is absorbing, so a reconciled claim can never be contested again.
   - [I] INSUFFICIENT_EVIDENCE has only one exit, back to ADMITTED. There is no path to WITHDRAWN or REJECTED, so a claim can be stuck there.
   - [I] CONTESTED has no withdrawal path.

7. **Internal contradictions in the state graph [I]:**
   - The synthesis diagram shows `RECONCILED ←→ SUPERSEDED` (line 81). The machine (lines 156–161) has only SUPERSEDED→RECONCILED, and RECONCILED has no exit.
   - Line 82 draws WITHDRAWN as following RECONCILED/SUPERSEDED. The table allows only ADMITTED→WITHDRAWN (line 129).

8. **Universal claim versus its own table (lines 347, 336).** Line 347 says "All transitions that change epistemic state require constitutional evaluation." Yet ADMITTED→WITHDRAWN is marked "MAYBE" (line 336). [I] The source contradicts itself here.

   Part 2 rejects the claim as not established (lines 784–788). It reframes the question: "which transitions require constitutional permission" is not the same as "does every transition require the Kernel" (lines 912–916).

9. **Ownership alternatives (lines 351–354 and 395–437).** Part 1 gives three options, then expands them into four scenarios:
   - **A:** the Kernel owns the whole lifecycle; the risk is a God Aggregate.
   - **B:** the Kernel owns admission only.
   - **C:** the Kernel evaluates and an Epistemic Context applies.
   - **D:** the Kernel is part of the KnowledgeAggregate.

   Each scenario comes with a risk and a benefit.

10. **Part 1's preferred hypothesis (lines 477–497, 556–576).** The Kernel is the "constitutional evaluator": "Is this transition admissible?" The Epistemic Context applies the transition. Decision is separated from application. Part 1 claims this is "DDD-clean" and "avoids the God Aggregate problem".
    - [I] The hypothesis also gives the Kernel identity preservation, **history recording** and contradiction detection (lines 481–484). Those are stateful responsibilities, which conflicts with "pure function (no state)" (line 459) and "pure constitutional evaluator" (line 573).
    - Assumption (line 459): constitutional evaluation is a pure function.

11. **Part 2's lifecycle versus state machine distinction (lines 644–680).** Part 2 argues that moving from "knowledge evolves" to "therefore these are the states" is too fast. Each candidate could instead be a state, event, condition, assessment, relationship between claims, lifecycle status or derived projection.
    - INSUFFICIENT_EVIDENCE could be `EvidenceAssessment = INSUFFICIENT` while the claim stays ADMITTED (lines 687–710).
    - RECONCILED could be a relationship between two claims, not a state (lines 715–720).
    - SUPERSEDED could involve two aggregates (line 812). Part 1 already implies two claims change at once (lines 219, 259). [I] That transition therefore crosses aggregates.

12. **Five-way split of responsibility (lines 824–844).** Trigger, Proposal, Adjudication, Application and Recording are "different responsibilities". An evidence invalidation may be an event that starts in the Evidence Context (line 820).

13. **Boundary rule for bounded contexts (lines 767–776).** An Epistemic Context must not be introduced "merely because it sounds clean". Elegance is not evidence for a bounded context.
    - Part 2 offers a different diagram (lines 739–760): Governance → Kernel capability ("can this transition occur?") → Aggregate (apply, protect invariants, record history). It explicitly does not freeze it.
    - [I] This moves history recording from the Kernel (Part 1, line 483) to the aggregate. That is a silent change of owner.

14. **Distinctions that must not be merged (lines 896–902, 926–936).**
    - Claim, Evidence, EvidenceAssessment, Justification and EpistemicStatus.
    - Knowledge identity ≠ claim version ≠ evidence identity ≠ expression identity ≠ interpretation-candidate identity. Part 2 says this is crucial for supersession.
    - Open question (line 948): does supersession replace an object, or add a version that keeps identity and history? Only the existing EKS/PKS evidence can answer it (lines 950–952).

15. **Implementation prohibition (lines 958–983).** Do not create `enum EpistemicState` now. That would turn "a research hypothesis into implementation vocabulary" too early, and the ten open questions show discovery is unfinished.

16. **Open questions (lines 503–550, repeated at 582–600):**
    - Q1 transition preconditions, including the evidence threshold for supersession;
    - Q2 contradiction detection;
    - Q3 evidence sufficiency for transitions other than admission;
    - Q4 versioning;
    - Q5 Kernel and Epistemic Context;
    - Q6 Kernel and Aggregate (part of the aggregate, domain service, or application service);
    - Q7 ambiguous evaluations;
    - Q8 confidence over time;
    - Q9 is the state machine constitution, domain model or implementation;
    - Q10 re-admission after rejection.

17. **Next-round commission K-02 (lines 993–1037).** Classify 16 concepts (lines 1001–1018) as Domain State, Domain Event, Evidence Assessment, Relationship, Policy Decision or Derived Projection, through 10 lenses (lines 1022–1031). The critical rule: "Do not resolve ambiguity by inventing architecture … mark UNRESOLVED and state exactly what evidence is required" (line 1037). This includes the anti-God-Aggregate and deterministic-assurance lenses.

18. **Smaller points:**
    - The event is renamed `KnowledgeCreated` → `KnowledgeAdmitted` (line 193).
    - REJECTED claims "never enter KnowledgeOS" (line 199), yet REJECTED is one of the lifecycle's states. [I] So the domain of the state machine is wider than "knowledge".
    - A WITHDRAWN claim leaves KnowledgeOS, but its history remains (line 231).
    - "Evidence weight difference exceeds threshold" (line 131) is an undefined quantity. [I]
    - SUPERSEDED→ADMITTED because the superseder is itself superseded (line 133). [I] This is questionable: a stronger third claim does not restore a weaker original.
    - Confidence is part of an admitted claim (line 189), but appears in neither side of the Kernel/Context split (lines 479–490).

19. **Source defects:**
    - Empty citation markers where Part 2 quotes Part 1: blank lines and stripped references at lines 615, 666–667, 732, 774, 786 and 981.
    - Part 2's conceptual diagram (lines 621–638) leaves out REJECTED and INSUFFICIENT_EVIDENCE.

20. **External dependencies not opened (blind) and not used:** "F-1…F-5" single-gate admission (lines 362, 452), "P5 SNF research / SNF CLOSED" (lines 1048–1051), the reconstructed EKS/PKS architecture (line 944), and the "earlier lenses" (line 848).

### Cross-source table (one source; the two parts inside it)

| Topic | Part 1 (lines 1–600) | Part 2 (lines 602–1081) |
|---|---|---|
| Admission = first transition | Resolved (line 376) | Accepted (line 613), but graded PROMISING (line 1064) |
| Every state change needs constitutional evaluation | Asserted (line 347); its own table says MAYBE (line 336) | Not established (line 788) |
| The 8 states | Synthesised as states (lines 72–102) | May be events, assessments or relationships; do not freeze (lines 669–680, 958–979) |
| History recording | Kernel (line 483) | Aggregate (line 759) |
| Epistemic Context | Preferred split (lines 486–497) | Needs bounded-context evidence (lines 767–776) |
| Supersession | Transition of one claim's state (line 128) | Possibly a relation between two aggregates, or a version (lines 812, 948) |

### Lineage note
Part 2 directly quotes and assesses Part 1 in the same file. The two parts are one line of support, not independent confirmation. Part 2's grades (STRONG, PROMISING, OPEN) are a reviewer's judgement of Part 1, not new evidence. Both parts rest on the same unopened earlier work (F-1…F-5, SNF, the earlier Kernel discovery), so any agreement with those sources is shared origin.

### Files opened
1. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C95-S0364/BRIEF-W-STEP1.md`
2. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
3. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260823-111647-epistemic-lifecycle-domain-discovery-admission-as-first-transition.md` (three consecutive reads: lines 1–400, 400–749, 750–1081)

### Commands and searches run
- `grep -n "20260823-111647-epistemic-lifecycle-domain-discovery" .../corpus-access/CORPUS-ALLOWED.tsv`: a targeted check of this path against the allowed list. Result: line 1706, S0364, sha256 `748b2100…6513`, "match".
- `wc -l` on the S0364 file: 1081.

No git commands. No other searches or listings. No scratch files written. Nothing refused. No book or manuscript file opened.
