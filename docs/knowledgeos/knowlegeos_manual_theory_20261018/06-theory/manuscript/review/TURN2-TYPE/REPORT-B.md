# Turn 2 (kernel type from Req): reviewer B (Sonnet) report

*2026-10-20. Independent; REPORT-A not read. Read: BRIEF, §9.11, §9.1 C2/C3/C6, §9.10, §9.8 H1′–H5, §9.7 K5, §9.6 K1–K5 headings, §1.16.7, §1.7, §5.6, §8.10 C10, §2.3(e), THEORY-MAP F36 row, register row CON-0189, EXTERNAL-SOURCES EXT-050. No Ch. 0–2 basis file and no corpus file opened. No web search made (no search tool in this session): prior-work checks are from my own knowledge and are marked Ext/U.*

## 1. Verdict

| Item | Verdict |
|---|---|
| Overall | Classification scheme is sound and mostly standard; **three load-bearing claims fail or are under-specified** (R1's "record never loses a consumption", R2's "C6 cannot express L", law 20's AE counterexample). The list of 25 is not complete. |
| R1 (whole histories via record) | **Fails as stated** (critical-for-the-turn; H1′ allows removal, which breaks conditions (ii) of result 5 and the prefix claim). Holds if the record is strictly append-only, or if loss entries must keep the consumed-act identity. |
| R2 L counterexample | Valid for the "eventually" reading; **not "C6 cannot"**: I_R is not restricted to safety in C6. |
| R2 AE counterexample | **Not constructible in C6's own terms** until "a continuation inside the record" is defined; inputs already carry outside acts. |
| R2 HP counterexample (21) | Valid under a stated condition (tied/absent act times, §9.7 step 2); "cannot be expressed" is relative to the single-system boundary (product construction). |
| R4 | Overstated: holds only under the closed reading (no guards) and only for the T3 class, though the type includes G. |
| Prior work | Cited works exist and say what is claimed (Ext, from memory; bibliographic page check U). "Classification of these laws" is the book's own; related established hierarchies are not cited. |

Per question of BRIEF §2: Q1 laws 3,9(home),11,16,18,19,20,21,22,23 questionable, rest accepted (§3). Q2 R1 no (§4 F1–F3); the record is a valid history variable only if loss entries retain consumption. Q3 R2 counterexamples: L ok with F5, AE F6, HP ok with F8; list incomplete (F9). Q4 R4 F10. Q5 F11.

## 2. Claim inventory (the turn's own claims)

| ID | Claim | Evidence | Class (verified) | Conf. |
|---|---|---|---|---|
| B1 | Classes S,T,H,O,R,L,AE,HP,W as defined | standard definitions (Alpern-Schneider, Clarke-Emerson, Clarkson-Schneider) | Ext, verified from knowledge | high |
| B2 | "C6 expresses S,T,H,O,R by construction" | C6 type | S,T,O,R: DERIVED. H: DERIVED only if I_R is a safety property (see B3) | med |
| B3 | "C6 does not express L, AE, HP, W", "read off the type" | §9.1 C6 definition of I_R | **HYPOTHESIS/overread**: C6 states I_R over "runs", unrestricted; C6 itself says "whether every trace invariant ... is a safety property is open" (result 5). W: DERIVED. AE: DERIVED (branching property, not a set of runs). HP: DERIVED for single-system semantics | high |
| B4 | R1: laws 7,8,9,16 expressible if record is in 𝒮 | proof sketch | **CONTRADICTED in the presence of H1′ removals** (F1); otherwise DERIVED | high |
| B5 | Record satisfies result 5 (i)-(iii) | R1 | (ii) not entailed; (i) "or holding only the root act" conflicts with O4 being an input | high |
| B6 | Abadi-Lamport history variable is the established device | EXT-032 | Ext, correct (history variables are in Abadi-Lamport 1991) | high |
| B7 | R2 L: held-act counterexample | §5.6, 9.11 | DERIVED for the "eventually" reading only (F5) | high |
| B8 | R2 AE: F33 counterexample | §8.10 C10 | UNKNOWN until "inside the record" is formalised (F6) | med |
| B9 | R2 HP: law 21 counterexample, and "with one shared record, O" | §9.7 K5 steps 1-2 | DERIVED, with missing condition (F8); the second half DERIVED | high |
| B10 | R3 fork (a)/(b); rival "safety-plus-observer kernel" | R2 | rival's stated cost ("applications cannot keep K5 by themselves") is **overstated**: §9.7 step 6 says CE is delegable under a common versioned rule over a mergeable record (F12) | med |
| B11 | R4: C3 holds for S,T,H(record),O,R, fails for L,AE,HP | C6 results 3,5,6,7 | **overstated**: C6 result 6 says C3 does not extend to guards; result 3 is "closed reading"; result 5 is for the T3 class (F10). Failure for L, AE: DERIVED | high |
| B12 | 25 laws are "every law the book states for kernel candidates" | inputs list §0.5 ... §9.10 | **CONTRADICTED**: omitted laws exist (F9) | high |
| B13 | Law 9 home: U "no act consumed twice" in §1.16.7 | §1.16.7 text | **CONTRADICTED as a citation**: §1.16.7 is "a uniqueness needs a serialization point, or only detection" (F7) | high |
| B14 | Prior work table: all but the last row "established" | EXTERNAL-SOURCES | OBSERVED for existence (bibliographic data match what I know: Clarkson-Schneider JCS 18(6) 1157-1210; Alpern-Schneider IPL 21(4) 181-185; Abadi-Lamport TCS 82(2) 253-284) | med |
| B15 | Internal vs world assumptions kept apart (§9.11.6) | text | DERIVED, but "fairness is internal to the system but not to the type" mixes (F13) | low |

## 3. The 25 laws, reviewer classification

Agree: 1, 2, 4, 6, 10, 12, 13, 15, 17, 25. Disagree or qualify:

| # | 9.11 class | Reviewer | Reason / counterexample |
|---|---|---|---|
| 3 | W | W, agree | |
| 5 | T | T for presence only | adequacy of a reason is not expressible; C6 checks a field exists |
| 7, 8 | T over record, yes | T, **no if removal can erase a consumption** | see F1; also "silently" reading is a premise |
| 9 | H, via record | H, but **home cited is wrong**; also O1 "idempotence" and "no act consumed twice" are two laws bundled (idempotent re-presentation = no-op step; uniqueness = refusal/ignore rule) | F7 |
| 11 | T,S yes | T,S for recording a version id; **"the answer was computed by the pinned evaluator" is W** (binding of recorded version to executed code) or an HP across versions | an entry can hold a hash of CE; the type has no way to say the observer executed that CE |
| 14 | W (+R) | W; H4 (soundness) has the same class and is omitted from the table |
| 16 | O, yes if record in 𝒮 | O **only until a recorded loss**: after a loss, R≤s is not recoverable from R≤s′ (H1′ says so itself), so Q1 "as the record stood at s" is not an observer of the current state | F2 |
| 18 | L, no | **ambiguous L / T**: "applied once its object exists" is T if application is atomic with the object-creating step (law 12: atomic change); L only if deferred. §9.10 E already puts delivering and acting in the application ("system records and replies") | F5 |
| 19 | T/O plus L | ESCALATE is **L and W** ("reaches someone" is outside the record); recording the escalation is T | |
| 20 | AE, no | AE only after defining internal vs external inputs; as written **W or undefined** | F6 |
| 21, 24 | HP / O | agree; K4-d ("detected") is L, K4-p is a safety property needing a serialization point (§1.16.7) | F8 |
| 22 | HP, no | **O for the observers** (a ref_q that filters by reader makes non-disclosure a property of the observer, a 2-state property of the function, checkable); HP (2-safety) only for covert flows across runs | F8 |
| 23 | O | O in the type, but §2.3(e) calls it liveness and §9.10.2 admits E6 resource-bound answers: ref_q then depends on a resource parameter, not only on record and input | normal |

Laws 1, 2 (and all "yes" rows) also presuppose acts and standing are in 𝒮; the table flags this only for rows 7, 8, 16. 𝒮's content is open (F13/T9-02).

## 4. Findings

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| F1 | **R1's history variable can shrink.** Result 5 (ii) needs H never to shrink; R1 says the record "never loses a consumption" because a removal "keeps the act's earlier consumption visible". H1′ (§9.8) only requires "a recovery pointer or a recorded loss"; nothing requires the loss entry to carry the consumed act identity. Run: consume a (entry e1); remove e1 under retention with a loss marker without act id (satisfies H1′ and law 7); consume a again (consumed set derived from record no longer contains a). Uniqueness fails; every H1′ condition holds. This is the book's own clear_used counterexample. Also R1 calls the record "append-only" while law 7 allows removal. | critical (for this turn's R1) | §9.8 H1′; §9.1 C6 result 5 (ii) | Either add a premise "a loss entry retains the identity of every act consumed by the removed entry", stated as a new law with its class, or keep H separate from the record (H grows only) | R1's proof lists the added premise; a run like the above is shown excluded |
| F2 | R1 (i) "a past cut is a prefix of the current record" is false after a removal; H1′ itself concedes recoverability "except for losses that are on record". Q1 and replay at a past cut then differ from "as the record stood". | high | §9.8 H1′; §9.11 R1 (i), law 16 | restrict the claim to cuts before the first loss, or say which answer a cut after a loss must give | law 16 restated with its loss case |
| F3 | **"I_R is safety" is a narrowing of C6 not a reading of it.** §9.11.2 defines H as violated by a finite prefix; C6 defines I_R as a set of runs from s₀ and calls it open whether all needed trace invariants are safety. If runs are infinite and I_R unrestricted, liveness *is* statable as I_R, and "C6 cannot express L" is false; what fails is C3 and the result-5 reduction. | high | §9.1 C6 type and result 5 last sentence | either restrict C6 (state: I_R ⊆ safety, runs finite or prefix-closed) in §9.1, or change B3 to "cannot be carried by the history-variable reduction nor kept by the one-check existence" | the type and §9.11.2 define I_R the same way |
| F4 | `Req` is read as EF from s₀ (C2) in §9.11, but §9.10.2 says each o is accepted "from a valid state". The two differ (EF from s₀ vs acceptance in every valid or reachable state). The AE class arises only under the first reading; under the second part of it is already in Req. | high | §9.1 C2 vs §9.10.2 | fix one reading in the type and say what law 20 is under the other | single definition |
| F5 | Law 18 (held act) is classed L only. With atomic application at the creating step (law 12) it is T and C6 expresses it; §9.10 E's criterion places acting in the application. The L counterexample is therefore a counterexample to one reading. A bounded version ("within N steps / by deadline d", d in state) is safety. | high | §5.6 line 31; §9.10 E; law 12 | give both readings; classify each | table row 18 split |
| F6 | **Law 20 / F33 is not formalised.** The source says "no command can leave without a fact from outside the record", and the fact is a human act, which enters as an input (C6: every input carries an outside act). In C6, EF then holds. "Left only by a fact from outside the record" has no meaning in a model with no internal/external partition of X. The counterexample is verbal. A valid one needs: X = X_int ∪ X_ext, state w with no step on X_int. | high | §8.10 C10 (S0095 "ONE HUMAN ACT short"); §9.11 R2 | define X_int; build the 3-state model (s₀ → w, no exit on X_int) and test | model run attached |
| F7 | Law 9 cites §1.16.7 for "no act consumed twice"; §1.16.7 says a uniqueness needs a serialization point or only detection. The instance "no act consumed twice" is C6's own (from §1.13's single-use grants). The §1.16.7 point also bears on R1: the history-variable argument assumes one serialised state (atomic steps). Evidence in §9.6 (one recorder lost 23 of 30 concurrent writes) shows that is a premise about concurrency, a world/implementation assumption. | normal | §1.16.7; §9.6 line 244 | cite §1.13 for the instance; state atomicity of steps as an assumption in §9.11.6 | citation fixed; assumption listed |
| F8 | HP claims are relative to the system boundary. Self-composition (product of two systems, or of a system with itself) turns a 2-safety condition into a state/transition invariant of the product, which C6 expresses. The K5 counterexample also needs ties or absent act times (§9.7 step 2: order is by act time first, so differing arrival order gives different answers only then). | normal | §9.7 K5 steps 1-2; Ext: self-composition | say "not expressible as a condition on single runs of one system"; add the tie condition | row 21/22/24 texts amended |
| F9 | **List incomplete.** Not searched exhaustively; omitted laws found: §9.4 C2 "at most one established assessment per claim" (S, needs serialization); §9.5 C1 "session holds the CURRENT rule at START" (T if START carries the rule version, else W; the book calls it degenerate alone); §9.6 K1 (occurrence one fact: W), K2 (content-derived identity: independent recorders compute the same identity, a 2-state functional property), §9.6 merge by set union independent of order (commutativity of mechanisms: HP-like); §9.8 H4 (W); §1.16.6 "pinned or live with detection" (detection = L); §9.9 L5 (independence of evaluator, which §9.9 itself says cannot be met). Inputs list omits §9.4, §9.5, §9.6. | high | greps above; §9.11 §3 inputs | extend table; state the search protocol used | "complete" backed by a stated, repeatable search |
| F10 | R4 overstated: C3's one check fails with guards (result 6), requires the closed reading (result 3), and the H-case only for mechanisms satisfying T3 (result 5). Also O "under (a)" holds trivially since obs_q is a function of the state and not of M: it constrains the specification, not the mechanisms. | high | §9.1 C6 results 3,5,6,7 | state R4 per reading (closed / guards) and per class | R4 restated |
| F11 | Prior work: related established classifications are not cited: the Manna-Pnueli safety-progress hierarchy (response, recurrence, persistence), bounded-response as safety (timed systems), reversibility / home-state and deadlock-freedom (AG EF / AG EX true) in Petri nets and model checking, McLean and Mantel on noninterference, Clarkson-Schneider's own hypersafety/hyperliveness split. (Ext, from memory; I could not search.) Classification of knowledge-system laws into these classes: nothing known to me; the book's table says "no search" and that is accurate. | normal | §9.11.7 | add rows; keep the last row marked "not searched" | rows added |
| F12 | R3 rival's falsifier is not met: §9.7 step 6 / §9.6 option (C) say applications keep K5 under a common versioned rule over a mergeable record. What is not shown is whether the contract is a kernel mechanism or kernel data. | normal | §9.7 step 6; §9.6 table | state the cost as "needs a shared contract", not "cannot" | text changed |
| F13 | §9.11.6 calls the scheduler fairness "internal to the system but not to the type", an in-between category. It is an assumption on the environment (the scheduler) of the transition system. | low | §9.11.6 | classify as an assumption on the environment | one sentence |
| F14 | Law 15/23 "yes" for Q1, Q3, Q4 rests on reference definitions that do not exist (§9.10 says absent/partial). The entries are vacuous "yes", not demonstrations. | normal | §9.10 Q1,Q3,Q4 | mark "yes, vacuous until ref_q exists" | marked |
| F15 | Root act: R1 says H "empty at s₀ (or holding only the root act, O4)"; O4 is an input to accept, so the root act is not in s₀. | low | §9.10 O4 | drop the parenthesis or say O4 is an initialisation | edited |

## 5. Proof-step table (what I judge adequate)

| Step | Verdict |
|---|---|
| 9.11.2 definitions of S,T,O,R,AE,HP,W | adequate (standard) |
| R2 AG EF is neither safety nor liveness | adequate, trivially (not a set of runs) |
| R1 (ii) "uniqueness as transition rule" | adequate **given** H never shrinks; inadequate otherwise (F1) |
| R1 (i) cut = prefix | inadequate after a removal (F2) |
| R2 L counterexample | adequate for deferred application; see F3, F5 |
| R2 HP counterexample 21 | adequate with ties added (F8) |
| R4 | partly (F10) |

## 6. Coverage (not checked)

Not checked: the quoted source lines behind laws (the book's own text only; no corpus file opened); the 25 laws' home sections beyond the lines cited here (§0.5 I-1/I-2/I-7, §1.6, §1.14.2); THEORY-MAP F36 text beyond its row; Turn 1 settlement; web or publisher pages (no tool); whether kernel_type_model.py supports result 5 (not run). My search for laws was grep-based on a limited pattern set and is itself not exhaustive.
