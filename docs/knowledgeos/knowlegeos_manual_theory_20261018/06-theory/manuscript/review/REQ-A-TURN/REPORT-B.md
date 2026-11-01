REQ-A REVIEW REPORT B

Reviewer B (kos-lane-reviewer, Sonnet), topic review. Marks: [V] verified in a cited line or by my own run; [I] my judgment. Independence is process independence only. One disclosure about step 1: I ran a grep on a path written `../TODO.md`. It matched nothing, and I did not open the file. The brief listed TODO.md as not allowed at that step. I record the grep and did not rely on it.

## 1. Assessment (3 lines)
§9.19's mathematics is sound where it is proved: T1, T3 (both directions), T5, T7 and T8 check step by step, and my own random probe found no counterexample to T3 (see 4.1). The largest risk is Task 1 and the candidate table: the class "answered" is defined so that any stated placement outside Req counts as an answer, and two rows (§6.5 C1, §9.3) rest on a placement the cited sections do not support. The "uniqueness criterion" (T3) and "necessity by kind" (T4) are close to restatements, and non-uniqueness is attributed to composites alone, whereas a pool with no composite also breaks it.

## 2. Findings (ranked)

| ID | Finding | Sev. | Evidence | Correction | R3 (citing lines) | Resolved when |
|---|---|---|---|---|---|---|
| F1 | **§6.5 C1 is called "answered" with "fold → part of CE".** §9.10.7 says the same fold "derives sessions, mutation owner and work-item state, not authority". That part has no Req item, and §9.19 does not place it in application, world or kit. By §9.19's own F-ins, the row is not "answered". | high | §9.19.2 row 2 against §9.10.7 row 6 [V] | Class it "in part", or state a reason that the non-authority fold is application. | §9.19.2 and the index row text "five of six"; BOOK-OUTLINE L179; TODO T9-01; 09.99 row 93 (from `inbound_citations.py 9.19`: 09.99 L93, TODO L11, BOOK-OUTLINE L44) | the row and every "five of six" line agree |
| F2 | **§9.3 "answered (Int)" cites §9.3 C4 (capabilities ≠ kernel operators) as the reason generation is application.** C4 is a statement about capabilities versus primitives, not about kernel versus application. No boundary test was run on extraction or improvement (§9.10.7 labels it Int). §9.3 C1 also records no canon-level generative run (n = 0). | high | §9.3 C4 [V] | "in part", or give a reason that does not rest on C4 | as F1 | as F1 |
| F3 | **The class "answered" is satisfied by any placement outside Req with a stated reason (§9.19.2 definition).** So "five of six" is partly a definitional output. §9.4 C7 (b) and §5.5 C3's delivery are placed by the stipulation "the system records and replies". The same stipulation decides §9.5 C5's ordering ("before the gate is enabled"), which the row omits. Class names should say "answered (kernel side)" and list the stipulations. | normal | §9.19.2 "Classes used" [V] | Add the stipulation list to §9.19.2's result. | §9.19.1 line 9 | the stipulations are listed beside the count |
| F4 | **Lifecycle "countermodel" rests on D's exclusion.** §9.19.5 says "its changes are O1 act kinds and no core reference reads a lifecycle state". But CE*'s domain D excludes retirement and supersession ("Not admitted", §9.13 L52). Q1 refuses such records (§9.16 R1). So the reference does not read lifecycle because it cannot. That shows an inconsistency between O1's kinds and Q1's domain, not that lifecycle is unnecessary. | high | §9.13 L52 [V]; §9.10 O1 kinds [V] | Label "holds on D only"; record the O1/D gap. | §9.19.5 table, result line | label and gap recorded |
| F5 | **"Uniqueness breaks with coarser mechanisms" omits alternative routes.** I added a distinct mechanism `a_evid2` (adds evid only when root is present; no composite) to FINE in a scratch copy. [V] It gives two minimal kernels, {a_act, a_evid, a_loss, a_root} and {a_act, a_evid2, a_loss, a_root}, and kernels are not closed under ∩. So non-uniqueness needs redundancy, not composites. §9.19.7's "fails as soon as one composite is admitted" is true but incomplete. §9.19 gives no positive, pool-level sufficient condition for uniqueness; T3 only restates "has a least element". | normal | my probe `alt.py`; T3 [V] | Add the alternative-route breaker; state a pool-level sufficient condition, such as one derivation per required kind and no composites, as my step 1 did | §9.19.7, §9.19.8 | |
| F6 | **"Agree" in the two-minimality relation (§9.19.4) holds in one direction only.** A mechanism-minimal kernel from a composite-free pool consists of underivable mechanisms. The converse fails. F5's pool {a_act, a_evid, a_evid2, a_loss, a_root} has no member derivable from the others yet is not minimal. "Derivable" and "generator" are undefined for mechanisms. Also, "no composite" is defined as "every mechanism underivable", so the one-direction result is nearly definitional. The concept-level "primitive" of §9.19.5 and RESEARCH-PURPOSE is a different object (homonym) and is not covered. | normal | §9.19.4 L118–119 [V]; R2 candidate `derivable` | State one direction; define derivable as a one-step sequence; qualify "primitive (mechanism)" against "primitive (concept)" | T9-03 row text in TODO | |
| F7 | **T4 "necessity by kind" is close to the definition of EF.** Req asks that a kind be reachable, so some transition adds it. Its strength depends on how kinds are individuated, which is a grain of Req's own items. O1 is one input over eight act kinds, so the grain changes how many appenders T4 demands. §9.19 does not state this third dimension. | normal | §9.10.3 D; T4 table [V] | State "by kind as Req individuates kinds"; cite T5 for the no-named-mechanism claim (T4 step 5 uses the model; T5 gives it by E ∩ {c_E} = ∅) | §9.19.1 line 13; TODO | |
| F8 | **§9.19.1 line 12: "Coarser mechanisms always break uniqueness."** T5 needs c_E to reach every requirement from s₀ in one step and to preserve I from every state. A partial composite, or one that breaks P, need not break uniqueness. | normal | T5 conditions [V] | Replace "always" with T5's conditions | none beyond §9.19.1 | |
| F9 | **"Req does not need T9-02" is conditional.** Req's observers are correct "on every valid state" of 𝒮 (§9.1 C6 result 7a), and §9.11.7 lists the content of 𝒮 beyond "contains the record" as open. So Req is independent of T9-02 only if Req is stated over the record (D). The brief's U1 recommendation (b) leans on this. | normal | §9.1 L116; §9.11.7 [V] | State the condition | §9.19.4 L114; U1 | |
| F10 | **N1 judges Zero "not necessary" relative to the central question only.** RESEARCH-PURPOSE L70 lists Zero among the direction's concepts, and §5.4 says RESEARCH-PURPOSE's Zero is Zero-all. §9.19 does not say that "not necessary" is relative to the central question, not to the direction. | normal | RESEARCH-PURPOSE L70; §5.4 L31 [V] | Add the scope | §9.19.3 N1 | |
| F11 | **History removal model is read as support.** §9.19.5 gives a removal model for history while the table's own row says it is "derived from record properties (§9.8), conditional". Necessity (cannot be removed) is not independence (not derivable). §9.19 disclaims the step to membership but not this one. | normal | §9.19.5 table row 142 against the removal list [V] | Add one sentence | | |
| F12 | **R2 judgments.** `appender`: defined at L109 inside a parenthesis, adequate. `kernel-read` (U4, L309): undefined; read it as "read by a clause of a kernel reference definition (R7 (ii))". `derivable`, `generator`: see F6. `granularity`, `uniqueness`: defined. Background items out of scope. | normal | `undefined_terms.py --scope 09.19` [V] | Define `kernel-read` | U4 | |
| F13 | **Source defects.** (a) "§9.6 L217–218" cites F22's decision; the parenthetical is at L217 and the decision text is in §9.16 R4a (L223). (b) §9.19.1 states "a minimal kernel exists whenever a kernel exists" without the finite-pool premise that T1 needs. (c) T6 says H-down and H-mono "both fail" under G-guard, which is one counterexample, not a general failure. | normal | direct reads [V] | correct each | | |

## 3. Verdicts

| Part | Verdict | Reason |
|---|---|---|
| §9.19.2 Task 1 | PROVISIONAL | §5.5 C3 and §9.5 C5 are right as "kernel side"; §9.5 C1 in part is right. F1, F2 and F3 apply to the other rows. The L179 change is "narrowed, not resolved", which follows from the evidence. |
| §9.19.3 Req v3 (Q4, E1, N1) | PROVISIONAL | The three boundary analyses are kept separate and mostly sound. Q4 is correctly conditional on G7 (§9.18 L82, still open). N1 needs F10. |
| §9.19.4 A = (B, G) | PROVISIONAL | B and G are separate and well stated. F9 and F6 apply. The observer-axis statement is right, and it matches my step 1: under choice (a) Q does not constrain which mechanisms are needed. §9.19 does not draw that consequence out. |
| §9.19.5 candidates | PROVISIONAL | The status column is not used as evidence of necessity [V]. F4 and F11 apply. "Countermodel" is an argument from non-use, not an exhibited model. |
| T1 | VALIDATED | Finite pool premise needed (F13b). |
| T2 | VALIDATED | Cited from §9.1 C3. |
| T3 (a) and (b) | VALIDATED | All steps check. Informative content is limited (F5). |
| T4 | PROVISIONAL | Valid, but near-definitional (F7). |
| T5 | VALIDATED | Valid under its stated conditions (F8 for the headline). |
| T6 | VALIDATED | A counterexample, as labelled. |
| T7 | VALIDATED | Premise stated. |
| T8 | VALIDATED | Trivial and correct. |
| §9.19.8 | PROVISIONAL | No unique minimal kernel is claimed and no selection is made [V]. F5 and F6 apply. |
| §9.19.9 U1–U4 | PROVISIONAL | The questions are well formed. U1's reason leans on F9. U2 mixes two questions (is Q4 = g1's class; where g1 sits) and omits an option that maps g1's classes onto §1.3's statuses. U3 repeats U1(c). U4 needs F12. |
| The model | VALIDATED (form only) | See 4. |
| Outline, TODO, index | PROVISIONAL | They carry "five of six" (F1, F2). L44's "uniqueness criterion" and "necessity by kind" are presented as progress; see F5 and F7. They are no stronger than §9.19. |

## 4. Specific checks

**4.1 Model and proofs.**
- I ran `req_a_model.py` on a scratch copy. [V] Its output matches the Professor's run file.
- I wrote a random probe (`probe.py`, scratch only): random invariants made of implications, random guarded appenders, 2 to 7 mechanisms, closed reading. [V] It found 0 violations of "unique minimal iff kernels closed under ∩" in 362 usable cases (273 with a single minimal kernel, 89 with several).
- The probe supports T3 within its bound. It is not a proof.
- GUARDS has one kernel in total, so the model's own `assert (len(minimal)==1) == cap` holds trivially there. T6 refutes the hypotheses, not T3 (b), as §9.19 says.

**4.2 Disproof conditions.** [I]
- F-ins is violated for §6.5 C1 and §9.3 (F1, F2).
- F-min is not violated: the "no mechanism necessary" claim is carried by T5.
- F-vac is not violated; T7 holds.
- F-A: every result is scoped to a pool. The scopes are right, though F5 and F8 show the scope conditions are incomplete.
- F-str: no "the minimal kernel" is claimed. T3 is a proved criterion, though thin.

**4.3 Process disclosure.** The model ran before the question was committed. This weakens only the claim that the disproof conditions were fixed blind. The deductive results T1, T3, T5, T7 and T8 do not depend on the model. Only T1 step 4, T4 step 5 and T6 do, and they are illustrations. [I]

**4.4 Commission limits.** [V] The four commits touch only §9.19, the outline, TODO, the index and the change log. RESEARCH-PURPOSE is unchanged, T9-02 is not absorbed, the primitive set is not finalised, and no selection criterion is adopted. EA-1 is labelled as an engineering assumption.

## 5. Completeness against my step 1
- **Agree:** none of the six claims needs a new kernel capability; §9.5 C1 in part; Zero tested and not admitted (Q7 a candidate); A = boundary plus granularity; the state space is not a capability.
- **I found and §9.19 omits:**
  - the non-authority fold (F1);
  - alternative-route non-uniqueness and a positive uniqueness condition (F5);
  - the grain of Req's own items (F7);
  - the Q-side vacuity under choice (a), stated as a result;
  - the START act, which E1 covers in part.
- **§9.19 adds that I did not have:**
  - g1 and G7 (a real improvement over my "Q4 absent");
  - E1;
  - T5's exact conditions and the T3 proof;
  - the observer axis.

## 6. Summary (10 lines)
1. T1, T3, T5, T7 and T8 are sound. T4 is valid but near-definitional. T6 is a labelled counterexample.
2. Task 1 is right for §5.5 C3, §9.5 C5 and §9.5 C1.
3. Task 1 is wrong or unsupported for §6.5 C1 (the non-authority fold) and §9.3 (the C4 reason). §9.4 C7 is acceptable.
4. L179 is correctly "narrowed, not resolved". "Five of six" should become "three answered, three in part" unless F1 and F2 are repaired by stated reasons.
5. Lifecycle's "countermodel" depends on D's exclusion (F4).
6. Non-uniqueness also arises from alternative routes, shown by my scratch model (F5).
7. The two-minimality "agree" holds one way (F6).
8. "Req does not need T9-02" is conditional on stating Req over the record (F9).
9. No unique minimal kernel and no selection are made. Commission limits are respected.
10. Nothing in this report is resolved by the model passing: the model supports form only.

## Files opened (absolute, under /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/)
- docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/REQ-A-TURN/BRIEF-STEP2.md
- docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/REQ-A-TURN/STEP1-ANSWER-B.md
- docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/REQ-A-TURN/RESEARCH-QUESTION.md
- docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md
- docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md
- Read in part: 09.18 (L80–84, L271–275, grep), 09.06 (L215–219, grep), 09.13 (grep and L57), RESEARCH-PURPOSE.md (grep for "zero").
- Tool and run files: docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/tools/req_a_model.py and req_a_model-run-20261101.txt (read). Also run: undefined_terms.py and inbound_citations.py.
- Re-read from step 1: 09.01, 09.10, 09.11 (via my saved answer, not reopened).

## Commands and searches run
- `cat req_a_model.py` and its run file; `ls tmp/tools | grep -i req_a`.
- `git show --stat` on 42d353c4e, c3c1b6b09, 6ae633b7d and c5fd16563. `git show` with the outline, TODO, index and change-log paths for the four commits.
- Scratch (…/scratchpad/rev-reqa-quill): copied the model, ran it, `probe.py` (random T3 probe), `alt.py` (FINE plus a_evid2).
- `python3 -I tmp/tools/undefined_terms.py --scope 09.19`; `python3 -I tmp/tools/inbound_citations.py 9.19` (and `--help`).
- Greps and seds on 09.18, 09.06, 09.13 and RESEARCH-PURPOSE as listed above.
- I did not re-run `corpus_quote_check.py`, `register_home_check.py` or `proof_step_check.py`. I relied on none of them.
