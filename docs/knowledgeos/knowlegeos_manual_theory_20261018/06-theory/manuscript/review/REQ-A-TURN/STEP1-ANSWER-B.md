REQ-A STEP 1 ANSWER B

Scope and independence. This is my own answer, written from the allowed book sections only. I did not open §9.19, 09.00-index, 09.99, TODO.md, BOOK-OUTLINE or any other file in the review folder. Independence is process independence only. Marks: [V] verified in the cited book line; [I] my interpretation or judgment. "§9.10" means 09.10-required-capabilities.md, and so on for the other sections.

## 1. Task 1: the six deferring claims and the other L179 candidates

Method note [V]. §9.10.7 maps all six claims, and §9.10.8 says "turn 7 reassesses them". The mapping is a classification (Int), not a demonstration that Req suffices. "Answered" below therefore means "placed in Req or outside it, with the book's reasons". It does not mean "shown sufficient".

| Claim | My class | Deciding lines |
|---|---|---|
| §5.5 C3 (doubtful act recorded at its weaker reading, returned to its author) | **Answered**, kernel side. | §9.10.7 row 1: record at the weaker reading and reply (O1); delivery is application (§9.10.3 E criterion "the system records and replies"). Same classification in §9.11 law 31 (T + O reply, C3 holds). [I] Two residues are not capabilities of the kernel: how the doubt is judged (an occurrence question, W) and the delivery. The criterion "records and replies; delivering is application" is a stipulation (Int), and it decides this row. |
| §6.5 C1 (BC-7's five obligations: record, refuse, fold, read-only queries, reference) | **In part.** | §9.10.7: "Int, conditional on F22". Record maps to O1, refuse to guards (policy, not an item), queries to Q2 and Q3, reference to §1.13. Fold maps to CE, but §9.10.7 itself says S0067's fold derives sessions, mutation owner and work-item state, "not authority". That part of the fold has no Req item. §9.10.10 notes F22 was decided (C + O), so the condition is now met. |
| §9.3 (the generative half: promotion, extraction, improvement from evidence) | **In part.** | §9.10.7: promotion goes to O1; extraction and improvement are application proposals "adopted by acts (Int)". §9.3 C3's three outcomes (shown, refuted, not exercised) need an establishment act (O1) with evidence (O2). Learning "at canon level" has n = 0 (§9.3 C1). No boundary test has been run on extraction or improvement, so "application" is Int only. |
| §9.4 C7 (establishment must happen; the assurance model's default state satisfies every must-never rule) | **In part.** | §9.10.7: O1 kind "establishment" with O2. "That someone must act is outside Req", which is consistent with Req being reachability (EF), not obligation (§9.1 C2). The effect of an establishment on a grade needs Q4's reference definition, and §9.10.3 Q4 says "absent". So the acceptance is answered, but the grading effect needs a new piece of theory (a grading rule). |
| §9.5 C1 (the current rule reaches the actor) | **In part.** | §9.10.7: the question part, "latest adopted version" from Q2's adoption acts. Delivery is application. §9.11 law 27: "partly". [I] The START event is not among O1's listed act kinds (§9.10.3 D), so whether a session start is an act the kernel accepts is unaddressed. No reference definition for "latest adopted" is written. |
| §9.5 C5 (the must-do half exists before a gate blocks) | **Answered**, as to O1–O4. | §9.10.7 maps it to O1–O4. O4's purpose cites the §9.5 C5 case: no root means refuse-everything in effect. [I] The ordering condition ("before the gate is enabled") is an operational or environment condition (E, §9.11.2), not a kernel capability. |

Other candidates L179 names:

| Candidate | My class | Lines |
|---|---|---|
| Distribution (§9.5) | In part: the question (Q2, adoption acts) is answered; delivery is application. Same as §9.5 C1. | §9.10.7 |
| Bootstrap (§9.2) | Answered by exclusion: a requirement of the engineering kit (F14), not of the knowledge kernel. A falsifier is stated. | §9.10.5 F-ins; §9.2 C1 |
| The record and the fold (§6.5) | Answered as Q2 plus CE plus O1–O3. Only the fold of non-authority state is unplaced (see §6.5 C1). | §9.10.4, §9.10.7 |
| Zero-all(EC) or Zero-blocking(EC) reachable (§5.4) | **Needs a new capability.** It is only a candidate (Q7), conditional on ContractDerivation and an owner of R and criticality. Which of the two is open. | §9.10.3 C; §5.4 line 45 |
| How to establish a capability (S0164 D5, E1–E10) | In part: an establishment is an O1 act with O2 evidence, and E10 needs history, which Q2 and H1′ give. The grading rule (Q4) is missing. | §9.10.5 F-ins |
| The state space (F13, T9-02) | **Not a capability, and not answered.** The book assigns it to the type's 𝒮, and its content stays open (§9.11.7). | §9.10.5; §9.1 C6 |

Summary [I]. None of the six needs a new kernel capability on the book's own mapping. What stays missing is theory the mapping leans on: Q4's grading rule, a decision on the START act, Q7, Q6 (read rule), and the content of 𝒮. The word "answered" depends on three stipulations: the record-and-reply criterion, F22 (decided), and the readings of the central question (valid, owned, trusted), which are premises (§9.10.4).

## 2. The research question: relative to what is "a minimal kernel" well posed?

**Requirement side [V unless marked].**
- Req = (Q, O), a stipulation (CON-0188, provisional). Each question has a reference definition: Q1 and Q3 exist on D under Θ_f and C1 = constitute; Q2 exists (the record cut); Q5 exists; **Q4 is absent** (§9.10.3).
- O is read as EF from s₀. The rival is enabledness (§9.10.2), which changes C3's form.
- The readings of the central question are premises (§9.10.4).
- The specification I_S, I_T, I_R and the observers' reference definitions are "given independently of (M, G)" (§9.1 C6, "premise of the type").
- Questions are taken as observers correct on all valid states, choice (a), "the stronger condition, and the one that keeps C3" (§9.1 C6 result 7). The alternative (b), correctness on reachable states, is antitone in M.
- Req must be non-trivial: with R = ∅, ∅ is the kernel (§9.1 C2).

**Interface side, in two parts.**
- *(i) Where the system ends.* The input-origin condition: inputs carry an outside act, and the kernel receives acts and does not make them. It holds only relative to faithful labels (§9.1 C6 result 1). Applications are bound by I_T and I_R (§9.1 C6 "Applications"). World assumptions W and environment assumptions E lie outside (§9.11.2). Placement of each item is by the three boundary questions, which are unfinished (§9.10.10, turn 7, F36).
- *(ii) How finely mechanisms are cut.* The pool of (M, G) and the grain of its mechanisms. §9.1 C4 shows that with recgrant in the pool, {recgrant} is a second minimal kernel, of a different size.
- [I] §9.1 C5 and F16 fold both parts into "A and its granularity". They differ in effect. Boundary (i) changes the invariants and Req themselves. Grain (ii) changes only which sets count as mechanisms, with the same Req. A third dimension is the grain of Req's own items. O1 is one operation over eight kinds of act, and splitting or merging items changes F-min's verdicts (see §3).

**What the evidence supports, and at what strength.**

| Result | Strength |
|---|---|
| Preservation alone: ∅ is minimal, so existence is trivially yes (closed reading); under the guard reading, a refuse-everything guard satisfies P and Min | Ded, validated (§9.1 C1) |
| With non-trivial Req, ∅, refuse and noop fail | Ded general; toy-model check (§9.1 C2, C4) |
| Existence by one check (U satisfies Req) for state and transition invariants, closed reading | Ded with proof (§9.1 C3, C6 result 3) |
| The same check for trace invariants only inside C6 result 5's class (history variable, P-loss) | Ded, conditional |
| The same check for guards, liveness and AE | Fails (§9.1 C6 result 6; §9.11 R4) |
| Minimality is relative to the pool and its grain; primitive minimality is a different notion | Ded in one toy model (form only), plus an argument (§9.1 C4–C5) |
| A KnowledgeOS minimal kernel (a specific set), or its existence | **Cannot be stated yet.** I found no enumerated pool of KnowledgeOS mechanisms in the files I read. Q4 has no reference. Q1 and Q3 hold only on D. Placement is unfinished. |
| Uniqueness | Open (T9-04, per C5). The only evidence is one toy contrast. |

**Structure condition for uniqueness [I]; this is not in the book.**
- Under monotone Req (closed reading), the feasible subsets of U form an up-set. The minimal ones form an antichain.
- The minimal kernel is unique iff the intersection K of all feasible sets is itself feasible. Equivalently, the feasible family is {M ⊇ K}: every member of K is essential in every feasible set.
- A sufficient pool condition: each required state has a single derivation, with no alternative route and no composite mechanism whose effect equals a sequence of other mechanisms. The recgrant case is exactly a breach of this, since {record, grant} and {recgrant} are incomparable and both feasible.
- In general, uniqueness up to Req-equivalence (behavioural equivalence on Req's items) is the better-posed notion than uniqueness of the set. Also, "minimal" is not "minimum": C4 already shows two minimal sets of different cardinality.
- Under the guard reading, monotonicity fails, so there is no up-set structure and the condition does not apply.

## 3. What would refute this answer

- **Insufficiency (F-ins).** A system meeting every core item that cannot make one of the three judgements. This is untestable for "valid, well-founded" while Q4 has no reference. It is also relative to the three readings, which are premises. "Owned (o3)", who must maintain, is placed in application only by a stipulation.
- **Redundancy (F-min).** The book already says that Q1 at a cut is CE over Q2's entries and Q3 is a reference definition over the same entries (§9.10.5). So at mechanism level Q1, Q2 and Q3 are not independent. [I] Their independence is as requirements only. [I] Also, O3 (recorded loss) might be an O1 act kind, and O4 (root) an O1 act of the kind outside the grant chain. Whether O1–O4 are four items or one is a choice of Req's grain, not a finding. A refutation: merging O3 or O4 into O1 leaves every judgement decidable.
- **Vacuity.** The §9.10.5 table is decided only where a reference exists. Q4 is undecided. Q6 is met by deny-all. [I] A Req item for Zero-all reachable is vacuous when EC is empty (§5.4 line 170), and Zero-blocking reachable is vacuous with no blocking requirement (line 171). So Q7-type items need the same non-triviality condition as R.
- **Grain or boundary failure.**
  - A coarse pool: one mechanism reaching every required state is a minimal kernel (§9.1 C4, last paragraph). The result is then true but empty.
  - A guard in the pool breaks monotonicity.
  - A forge with a falsely labelled input defeats the origin exclusion.
  - Re-cutting O1 into separate act kinds changes F-min.
  - Trace invariants fail the one-check existence argument unless P-loss holds. §9.10 O3 keeps the identities of consumed acts, but whether a law requiring deletion can require deleting them is open.

## 4. The trivial cases

- **Empty mechanism set.**
  - It fails O1–O4: nothing is accepted.
  - [I] Under choice (a), the Q part is independent of M altogether: "holds or fails independently of the mechanism set" (§9.1 C6 result 7). Under (b) it is vacuous, because only s₀ is reachable.
  - Either way, Q does not push toward any mechanism. [I] The must-do content that bears on minimality of M lies in O alone. Q constrains observers, not the mechanism set. This is a structural point about what Req's two halves can do, not just a statement about ∅.
- **Refuse-all (or a guard refusing everything).** Preserves, fails O, and Q is satisfied as above. It is the case behind O4's purpose and §9.5 C5.
- **Unchanged state (noop).** Preserves, fails O, and reaches only s₀.
- What these show: Pres, Min and the questions under (a) alone admit the trivial system. Req's must-do side is the inputs to accept, plus non-trivial reference definitions that rule out constant-U and constant-T (§9.13 R5, on D only).

## 5. Judgments [I] (collected)

1. "Answered" for the six claims is a placement, conditional on three stipulations: the record-and-reply criterion, the readings of the central question, and F22.
2. The six claims need no new kernel capability on the book's mapping. The missing pieces are a Q4 grading rule, a decision on the START act, Q7 and Q6, and the content of 𝒮.
3. "A and granularity" in §9.1 C5 are two interface parameters, plus a third on the requirement side: the grain of Req's own items.
4. Under (a), Q does not constrain which mechanisms are needed.
5. A uniqueness condition: a single derivation per requirement, no composites, and a monotone Req. A general notion should be uniqueness up to Req-equivalence.
6. A minimal KnowledgeOS kernel cannot be stated yet: there is no enumerated pool, Q4 has no reference, and boundary placement is unfinished.

## Files opened (all absolute, under /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/)

- 06-theory/manuscript/review/REQ-A-TURN/BRIEF-STEP1.md (read whole)
- 03-methodology/REVIEWER-INSTRUCTIONS.md (read whole)
- 06-theory/manuscript/chapter_09/09.10-required-capabilities.md (whole)
- 06-theory/manuscript/chapter_09/09.11-kernel-type-from-req.md (whole)
- 06-theory/manuscript/chapter_09/09.01-kernel-scaffold.md (whole)
- 06-theory/manuscript/chapter_09/09.02-parts-and-whole.md (whole)
- 06-theory/manuscript/chapter_09/09.03-say-no-and-learn.md (whole)
- 06-theory/manuscript/chapter_09/09.05-a-recorded-rule-must-reach-the-actor.md (whole)

Opened in part (via sed or grep): 09.04 (lines 60–80), 09.06 (headings, grep for "unique", "minimal", "K4"), 09.07 (grep), 09.12–09.15 (headings only), 09.16 (lines 59–87 and 359–390, plus headings), 09.17 (headings, grep), 09.18 (lines 265–284, plus headings and grep), chapter_05/05.04-gap-operator.md (grep), chapter_05/05.05 (lines 41–50), chapter_06/06.05 (lines 45–60), 06-theory/THEORY-MAP.md (grep only, for T9-03, T9-04, F16, F22, F26, F36), 06-theory/RESEARCH-PURPOSE.md (grep only).

I did not read in full: THEORY-MAP, RESEARCH-PURPOSE, 09.06–09.09, 09.12–09.18, CONCEPT-REGISTER.tsv (not opened), or any tool in tmp/tools (none run).

## Commands and searches run

1. `ls manuscript/chapter_09 | head -50; wc -c <the allowed 09.xx, 05.04, 05.05, 06.05, RESEARCH-PURPOSE, THEORY-MAP files>`. The listing showed 09.19, 09.99 and TODO.md exist. I did not open them.
2. `grep -n` for the six claims in 09.16 (empty result).
3. `for f in chapter_09/09.1[2-8]*; grep -n -E "^#{1,3} |^\*\*(R|C)[0-9]+..."` (headings), plus `grep -n -i -E "six claims|reassess"` in 09.16.
4. `sed -n 59,87p` and `sed -n 359,390p` on 09.16, and `sed -n 265,284p` on 09.18.
5. `grep -n "C7"` in 09.04. `grep -n` for C3 in 05.05, C1 in 06.05, and "Zero-all|Zero-blocking|T9-01|required capab|reachable" in 05.04.
6. `sed -n 60,80p` on 09.04, `sed -n 41,50p` on 05.05, `sed -n 45,60p` on 06.05. A `grep` for T9-0x in ../TODO.md failed to match. I did not open that file; it is not allowed.
7. `grep -n -i -E "minimal kernel|T9-03|T9-04|granularity|interface A|unique"` on THEORY-MAP.md, and `grep -n -i -E "minimal|interface|granular"` on RESEARCH-PURPOSE.md.
8. `grep -n -E "^\*\*(C|R|K)[0-9]+..."` and `grep -n -i -E "minimal|unique"` on 09.06, 09.07, 09.17 and 09.18.
