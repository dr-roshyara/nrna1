DERIVATION STEP 1 ANSWER A

*Reviewer A (kos-lane-reviewer, Opus). Blind step: I opened no 09.22 file, nothing the Professor wrote for this turn, and no other review folder. Independence here means the process was independent, not the person. Marks used: [I] my judgment · Ded deduction · F forced · C compatible · I independent · [OR] uses the origin rule (§9.1 L96), a premise that is not decided.*

## 0. Headline (3 lines)
1. None of the four candidates works as the unit on its own. The laws that can be derived come from one combination, U* = (𝒮 = the record over D's admitted kinds and fields, §9.20; Req v3's reference definitions and EF inputs; C1 = constitute), inside C6.
2. Everything U* forces is an observer law or a reachability law. The prohibitions (S over the record, T, H) do not follow from Req as C6 formalises it (result G below). They are premises: C6 itself says I_S, I_T and I_R are "given independently of (M, G)" (§9.1 L99).
3. One law does follow from U* end to end: law 1, in its record form, read as an observer law. It does not use the origin rule.

## 1. The unit of derivation

**Criteria [I].**
- K1 **defined:** the objects have a home and a form that a proof step can cite.
- K2 **expressive:** the terms of every §9.11 law can be stated over the unit.
- K3 **decided:** the unit uses only premises the brief allows.
- K4 **not behaviourally inert:** it is not fixed only up to choices that change no step, reply or answer.
- K5 **non-circular:** it does not already contain the laws it is meant to derive.
- K6 **discriminating:** it leaves some laws underived, so the result is not true of an empty Req or a one-point 𝒮.

| Candidate | K1 | K2 | K3 | K4 | K5 | K6 | Verdict |
|---|---|---|---|---|---|---|---|
| Primitive concepts (RESEARCH-PURPOSE L28–35) | fails: 2 of 12 ✓ (BOOK-OUTLINE §1); "authority" has three senses (L13); no independence model (TARGET-TOC Part II) | partly | fails: F13 and F37 are not usable | n/a | yes | n/a | **rejected as unit**; kept as an index |
| Entry kinds and fields (D, §9.13.2; §9.20.7) | yes | **fails:** no observer laws without references; kinds outside D (supersession, held acts, establishment, declassification) leave laws 6, 13, 18 and 26 unexpressed | yes | yes | partly: D's clauses (unique ids, earlier citations, tombstones) are themselves laws | yes | **necessary, not sufficient** |
| Mechanisms (§9.19.4) | form only; pool not fixed | — | **fails:** G is unfixed (U3 (b)) | **fails:** G is fixed only up to inert regrouping (§9.21.3 (1), L39–45) | **fails by the type's premise:** invariants are given independently of (M, G) (§9.1 L99) | — | **rejected** |
| Req (Q, O) | yes | **fails alone:** the references are defined over D (CE* is undefined outside D, §9.13 L135; Q2 is "the record itself, cut at s") | yes | yes | yes | yes, but it forces only O and R laws (G) | **necessary, not sufficient** |

**Choice:** U* = D-kinds/fields + Req references + C1. I add a separately named axiom set A for every law that U* does not force. [I] On the research question's either/or: for the observer laws the answer is "the record with Req's references"; for the safety laws it is **none of the four**, and that is a result.

**"Primitive" split into named senses, each with its test:**

| Sense | Home | Test |
|---|---|---|
| P-concept | RESEARCH-PURPOSE L28–35; BOOK-OUTLINE §1 | realised: it names a non-empty set of D's kinds or fields, or a Req item (as in the §9.19.5 map). Not a minimality test |
| P-field | §9.19.5 L203–226 | definitional removal model, plus R7 (ii) "read by a clause". **Degenerate under Question O (c)** (finding 4): restrict it to clauses of CE*, g1, the guards and the marks |
| P-mechanism | §9.19.4 L172–177 | not equal, as a function, to a one-step sequence of other pool members. Needs extending to case-splits (§9.21.5). Relative to a pool |
| P-axiom [I] | RESEARCH-PURPOSE L47, applied to laws | independence model: a system that meets Req and every other axiom and violates this one |
| P-requirement | §9.19.8 L364 | corrected F-min: removing it makes some non-default answer unreachable |

## 2. Law 1 end to end (record form; an observer law; the origin rule is not used)

**Law 1 (§9.11 row 1):** I1, no authority without a recorded act.

**Premises:**
- P1: C1 = constitute (§9.13 L81).
- P2: the state is the record, and current authority is derived, not stored (§9.20 Th1, Th3; the row at L133).
- P3: Q1 is met iff obs = CE*(k,x,t;R≤s) on every valid state; outside D, Q1 refuses with a reason (§9.10 L37–39; C6 result 7 (a); §9.15 G1).
- P4: the operator Φ (§9.13 L118–123).
- P5: KK is the least fixed point from the bottom (roots T, everything else U), reached in at most n+1 steps; on D, KK = WF (L132, L135, R1).
- P6: current-authority is F when there is no adoption; otherwise it is a disjunction over the adoptions (L129).

| # | Claim | Justification | Objects defined in the book? |
|---|---|---|---|
| 0 | "authority" in law 1 means a T answer of Q1; "recorded act" means an act entry in R≤s | Int [I]; §9.10 L31 | yes; the homonym is stated as a premise |
| 1 | outside D, Q1 gives no T | P3 | yes |
| 2 | on D, Q1 = T ⇒ current-authority = T at KK of R≤s | P3, P5 | yes |
| 3 | ⇒ some adoption y of c is in R≤s with t_y ≤ t and a(y) = T | P6; under strong Kleene, ∨ is T only through a T disjunct and ∧ only if every conjunct is T | yes |
| 4 | Lemma: if a_k(x) = T, then x reaches a root in R≤s through cited entries, each T one stage earlier | induction on k: at stage 0 only roots are T (P5); a non-root becomes T only through basis(g,x) = T, which needs g to be in the cut, to be a root or a grant, and a(g) = T (P4) | yes |
| 5 | KK = a_k for some k ≤ n+1 | P5 | yes |
| 6 | y rests on a recorded chain of grant issuances that ends at a recorded root, all in R≤s | 3–5 | yes |
| 7 | **Law 1, record form:** Q1 gives no T without a recorded adoption and a recorded chain to a recorded root | 1, 6 | — |
| 8 | it holds in every state, for every (M, G) that meets Q1. So it is **F, an O law, not an S law** | P3: condition (a) does not mention M or G | yes |

- **Not used:** the origin rule, F13, B-07, any mechanism, D's citation rule (beyond CE* being defined on D).
- **What law 1 does not give:** that the acts were received from outside (law 2, which needs the origin rule); that they occurred (law 3, W); that the root is legitimate (L5).
- **Falsifier:** a record in D under Θ_f, and a call, where CE* = T and either (a) c has no adoption with t_y ≤ t in the cut, or (b) some T act has no chain of T citations to a root.
- **Scratch check (form only):** 3,000 records from `ce_model.random_record`, all in D. 2,096 T answers, 0 violations of (a). 5,795 T acts, 0 violations of (b).
- **Consequence:** this settles §9.20.8's open implication [I]. Under 𝒮 = R, law 1 becomes an observer law, and the origin rule is needed only for the stronger form (law 2).

**Result G (Ded sketch): C6's Req forces no law that excludes a step between two valid states.**
- Proof: take a system that meets Req and has no guards. Add m_σ, which goes from s₀ to σ, and m_L, which takes the step (σ, x, σ′) that L excludes.
- EF is monotone under adding mechanisms in the closed reading (C3 step 2).
- Condition (a) does not depend on M (result 7 (a)).
- P_S holds, because σ and σ′ are valid.
- So the extended system meets Req and violates L. The same construction covers H laws. ∎
- *Premises:* EF (U5 (a)); the closed reading; and the existence of at least one Req-meeting system without guards over D. That existence is **not shown** for KnowledgeOS (§9.20.8: "a consequence, not done here"). If no such system exists, every law is forced vacuously.

**Corollary C (conditional) [I].** Suppose Req's call identity is read literally as state-free: "same call iff (k,s,r) equal" (§9.10 L47), and "the same call gets the same answer" (L37). With Question O (c), every step σ→σ′ then keeps σ≤s, except for entries replaced by their recorded loss. That forces append-only modulo recorded removal: laws 7, 8 and 10a, and the edit ban in law 6. C6 result 7 (a) indexes answers by state and drops this content (finding 2).

## 3. The laws of §9.11

AR = application rule · WA = world assumption · **IA = implementation assumption, the K side of §9.15** (finding 3).

| # | Mark | Ground |
|---|---|---|
| 1 | **F** (as O) | §2 |
| 2 | C [OR] | the label follows from law 1; the forge meets Req (C6 result 1) |
| 3 | I-WA | L1a–L3, L4 |
| 4a | answer half F; entry half C [OR] | Q1 = CE*, nothing stored (Th3) |
| 4b | C | the guard set is policy (§9.10 L112); EF does not constrain refusals (T7: the one-witness system) |
| 5 | reason present: C (a D field read by no clause, §9.20.3); "visible": I-AR | §9.10.3 E |
| 6 | C (F under Corollary C) | supersession is outside D |
| 7, 8 | C (K1); F under Corollary C | — |
| 9a | C | stated in Req_T's O1 identity. Under Req_F a duplicate puts the record outside D, and Q1 then refuses correctly |
| 9b | entry: C (F36 (a), prevent); value: F (a second consumption is F, §9.13 L34) | — |
| 10a | C (F under Corollary C) | — |
| 10b | I-WA | L6 |
| 11a | C; vacuous under Θ_f | §9.13 I7 |
| 11b | **I-IA** | K7 |
| 12 | **I-IA** | K6; a refinement premise |
| 13 | C | Q6 is only a candidate; deny-all meets it |
| 14 | O3 part F; completeness I-WA (L4) | under C1 it holds by definition for recognized standing (§9.15 R5) |
| 15, 17 | F | they are Req. Q5 counts only if it is in Req: §9.10 L162 and §9.19 L167 disagree |
| 16 | F over 𝒮 = R; replay part C | replay needs H3 |
| 18 | C (F36 (c), reading (i)); the return to the author: I-AR | — |
| 19 | STOP: C; REPORT: C (Req_T, L110); ESCALATE: I-AR | — |
| 20a | I-AR by placement (R6 (b)); logically compatible (the HALT model) | — |
| 20b | I-WA (environment) | — |
| 21 | F for one designated record; C across applications | finding 5 |
| 22 | C | Q6 is not core; the HP part is decided (a) |
| 23 | F only if Q5 is in Req | — |
| 24 | C | (a) with coordination; K4 |
| 25, 28 | I-WA | — |
| 26 | C within one record; across writers I-AR (detect, R6 (b)) | — |
| 27 | I-WA while U4 (d) defers E1; delivery I-AR | — |
| 29 | C | D's identity rule; B-07 not used |
| 30 | set union: trivial. Answers: F for Q1's values and marks (no position is read, R2 (b)); **not for Q2's order** | finding 5 |
| 31 | recording: C; reply: C (Req_T); delivery: I-AR | — |

**Pattern:**
- Every F row is an O or R law.
- Every T or H law is C or I.
- No law contradicts Req; I searched and found none, for example the HALT and one-witness cases.

**Ranked findings:**

| Rank | Severity | Finding |
|---|---|---|
| 1 | high | Result G: a single derivation chain from Req is impossible for the prohibitions. They are axioms by the type's own premise (§9.1 L99) |
| 2 | high | "Same call" / "met" is a homonym. In Req_T (§9.10 L36–37, L47) it is state-free; in C6 result 7 (a) it is state-indexed. Whether Req includes stability decides whether laws 6, 7, 8 and 10a are forced |
| 3 | high (for this deliverable) | The brief's two classes (application rule or world assumption) leave out the K side of its own precedent. Laws 11b (K7) and 12 (K6) are neither |
| 4 | normal | Under Question O (c), Q2 returns every field verbatim, so every field passes §9.19.5's removal test. The P-field test then discriminates nothing |
| 5 | normal | Under (c), Q2's "in record order" exposes which linear extension a merge chose (§9.21 L77). Law 21 and law 30 across applications then fail for Q2, unless a canonical order is fixed. §9.21.5 does not draw this consequence |

## 4. Consequences (stated, not settled)

**Deliverable 1:**
- Retype it into two lists:
  - (i) entry kinds and fields, tested by P-field restricted to non-Q2 clauses;
  - (ii) the axiom set A (the C rows above), tested by P-axiom.
- Concepts become an index over (i) and (ii).
- The ✓ marks for authority and relationships are P-concept marks, not P-axiom marks.

**T9-03:**
- State which minimality is meant: mechanism, state (now settled under (c), §9.21.5), or axiom.
- By result G, every T or H law is independent of Req alone. Whether these laws are independent of each other is untested. Candidate dependencies [I], none shown:
  - law 8 ≡ law 7, with "silently" read as "without a recorded act";
  - law 10a ⇐ law 7, in Th1's append/tombstone form;
  - 4a's entry half ≡ law 2.
- Law 1 is not an axiom: it is derived.

## 5. Disproof

| Answer | What would refute it |
|---|---|
| The unit is U* | a §9.11 law that needs facts about mechanisms beyond the step relation; or a T law derived from D's fields and Req's references alone, with no stipulated clause |
| The law 1 derivation | a counterexample to step 4 or step 6; any record in D that violates (a) or (b) |
| Result G | a law excluding some step between valid states that every Req-meeting system satisfies; or a proof that no guard-free Req-meeting system exists over D |
| Corollary C | a reading of §9.10's "same call" that makes Req's identity clauses state-indexed |
| The table | a Req-meeting system that violates an F row; or a derivation of a C row from Req |
| Finding 3 | a reading of "world" that covers implementation assumptions without loss |
| Finding 5 | a definition of Q2's order on merged records that does not depend on the linear extension |

## 6. Limits
- **Not opened:** THEORY-MAP and CONCEPT-REGISTER (line counts only, no content viewed); 09.02–09.09, 09.12, 09.14, 09.17, 09.18; and the listed Ch. 0, 1, 3 and 5 sections. I took the law statements from §9.11, not from their homes.
- **Read in part:** §9.16 (L59–193, L343–412) and §9.13 (L22–167, plus a heading search).
- Result G is a sketch.
- The scratch check tests form only, on the model's generator.

## Files opened
All under W:
- `06-theory/manuscript/review/DERIVATION-CHAIN-TURN/BRIEF-STEP1.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`
- `06-theory/RESEARCH-PURPOSE.md`
- `06-theory/manuscript/TARGET-TOC.md`
- `06-theory/manuscript/BOOK-OUTLINE.md`
- In `06-theory/manuscript/chapter_09/`:
  - 09.11, 09.19, 09.20, 09.21, 09.01, 09.10, 09.15 (whole);
  - 09.16 (L59–193, L343–412);
  - 09.13 (L22–167).
- Line counts only (`wc`): THEORY-MAP.md, CONCEPT-REGISTER.tsv, 09.16, and the files above.

## Copied
- `W/tmp/tools/ce_model.py` → `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/dc-a/ce_model.py`
- Scratch script I wrote: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/dc-a/chk/law1_check.py`

## Commands and searches
1. `wc -l` on 13 named files (listed above).
2. `grep -n` for headings, `R[0-9]` and `R7` in 09.16.
3. `grep -n` for headings and "current authority" in 09.13.
4. `mkdir -p` the scratch directory; `cp` ce_model.py; `wc` and `grep` for its defs.
5. `sed -n` on line ranges of the scratch copy (194–240, 392–450, 595–625, 906–950); `grep __main__`.
6. `python3 -I chk/law1_check.py <scratch>`. Output: "records 3000; in D 3000; CE*=T answers 2096; violations (a) 0; T acts 5795; violations (b) 0".

No git commands, globs or listings were used. Nothing in the repository was edited. No corpus source was consulted.
