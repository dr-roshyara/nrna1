REQ-A REVIEW REPORT C

## Summary (10 lines)
1. The mathematics in §9.19.6 is sound. T1, T3, T4, T7 and T8 are correct. T5 is correct within its stated setting. T6 is a valid counterexample. No "the minimal kernel" is claimed (F-str is clean), and no minimal candidate is selected.
2. Task 1 is defective as stated. "Five of six answered" fails §9.19's own class definitions in two places. §9.4 C7 maps to an O1 kind that D does not admit (§9.13 L52). §5.5 C3's law 31 is open, with prevention conditional on held acts becoming a D kind (§9.16 R1 L81, R3a). Both are **in part**, and so is the establishment candidate (S0164 E1–E10). L179, T9-01 and index row 9.19 inherit the overstatement.
3. T5 and the model's COARSE non-uniqueness hold for **state** invariants and input-free mechanisms only. Under C6's input-origin (one act per input), the composites c_root_act and c_all do not preserve the invariants, and uniqueness returns (my probe). §9.19.1's "Coarser mechanisms always break uniqueness" is unscoped.
4. Uniqueness also depends on how Req_O is cut, not only on G. At G-act with O1 read as "an act entry", there are two minimal kernels with no composite at all (my probe). §9.19.7's strongest-counterexample paragraph implies composites are the only thing that breaks uniqueness.
5. Two of my step-1 findings are missing, both high:
   - EF reachability is met by a one-witness system, and the enabledness rival is not addressed (it would void H-mono).
   - The F-min criterion ("leaves every judgement decided") fires on O1, O2 and O4. §9.19.8 reuses it for its requirement-level candidate.
6. §9.19 claims "Req does not need T9-02", but Q4's reference depends on F13 (G7: "the vocabulary against F13"). That bears on the commission's stop-and-escalate condition. U1's reason must change, and U1 and U2 are coupled.
7. Strong points: Q4/G7 is handled conditionally and correctly; N1 (Zero) is right; B is well grounded in K1; the observer axis is kept separate; §9.4 C7 (b)'s new reason (the kernel accepts inputs and does not produce them) is a valid deduction.
8. No critical finding: the defects do not touch the T-results' proofs. Task 1's headline and several scopes must be corrected before settlement.

Independence: I am a separate process with no access to the other reviewers' answers or reports. This is process independence, not personal independence.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| C-01 | §9.4 C7 classed "answered", but (a) maps to O1's kind "establishment", which D does not admit. "A record holding one of them is outside D", so Q1 refuses. Establishment is also not among W's three adopted kinds. There is a category conflict too: establishment "confers no authority" (S0050 L120 via §9.4 C5), while O1 is for acts. | high | §9.13 L52; §9.10 L105; §9.18 L28–34; §9.19 L28 | Reclass as **in part**: a domain clause for establishment, or a W kind, is needed. Keep the new reason for (b). | §9.19 L28, L43; BOOK-OUTLINE L179; TODO L11 (T9-01); 09.00 row 9.19; 09.99 L93 | the class is restated, and establishment's kind (D or W) is named as an open item |
| C-02 | §5.5 C3 classed "answered" from §9.11 row 31 alone. §9.16 R1 lists law 31 as "open; classified". R3 says it is not merge-safe. R3a says **prevent**, and R4 needs a coordination point per held act "if held acts become a kernel kind". By §9.19's own definition ("mapping conditional on something undecided"), that is **in part**. The reply also goes to the caller, not the author. | high | §9.16 L81, L166, L184, L212; §9.19 L21, L25 | Reclass as **in part**, citing R3a. | as C-01 | restated |
| C-03 | The candidate "how to establish a capability" is classed "answered". The establishment kind is outside D (C-01). The judgement "established at level L" (§8.15 C5, CON-0224) has no Q item and no stated reason for being outside Req. §9.10.5 relies on "Option A is unsatisfiable", which §8.15 C5 restated as a fork. E1–E10 here (establishment criteria) is a homonym of Ch. 2's E1–E10 (kinds of nothing). | high | §9.19 L40; §8.15 L63–66; §9.13 L52 | **in part**; report the homonym | §9.19 L40; BOOK-OUTLINE L179 | restated |
| C-04 | T5 and the COARSE result are scoped to state invariants and input-free mechanisms (`req_a_model.py`: "input-free"; P is state-only, §9.19 L175). C6 adds P_T, including origin, under which one input carries one act. A composite that appends root and act in one step violates it. Probe: adding "at most one kind per step" to COARSE gives **1** minimal kernel, not 3. | high | §9.1 C6 L94, L96; probe output | Scope T5, §9.19.1 L12 ("always"), §9.19.7 and §9.19.8 to state invariants, or to inputs that may carry several entries (batching is undecided, §1.16.1). State which reading A takes. | §9.19 L12, L191, L268, L273, L284; 09.00 row 9.19; TODO T9-04 | the scope is stated, and batching is listed in A's G |
| C-05 | Uniqueness also depends on how Req_O is cut. G-act with O1 = "an act entry" gives 2 minimal kernels with no composite (probe). Cutting O1 per act kind gives 1. §9.19 names G-act but does not test it. | high | §9.19 L110, L176; probe | Add Req's item cut to "uniqueness depends on G"; stating Req_O per act kind becomes a choice | §9.19 L12, L284, L300 | stated |
| C-06 | Req_O is fixed as EF without the open rival (§9.10.2 enabledness, which "changes C3's form"). EF is met by a one-witness system: each kind is accepted once, at one state (probe: a kernel). Under enabledness, H-mono fails, so T2 and T3 (b) do not apply. | high | §9.10 L20; §9.19 L176; probe | Name the reading as part of Req, and scope T2, T3 (b) and T4 to EF. List the one-witness case beside T7. | §9.19 L176, L202, L262–264 | stated |
| C-07 | F-min, as fixed in RESEARCH-QUESTION ("removal leaves every judgement … decided"), fires on O1, O2 and O4: without them Q1 answers F or U everywhere and Q4 answers "not attested", which are decided answers. §9.19.8's requirement-level candidate ("no item is removable") and §9.19.5's Authority row both rest on §9.10.5's table, which silently uses another criterion ("Q1 never answers T"). | high | RESEARCH-QUESTION L23; §9.10 L13, L185–192; §9.19 L137, L289 | Restate the criterion as "some judgement can take a non-default value", and re-run F-min under it | §9.19 L137, L289; §9.10 L13 | restated, with the table re-run |
| C-08 | "Req does not need T9-02" (L41, L114, L306) is false for Q4. G7 maps Q4's grade "against F13" (§9.18 L82), and option (c) of U2 needs §1.3's statuses, which are F13. | high | §9.18 L82; §9.19 L69, L307 | Say Req's Q4 needs F13 (T9-02). Re-reason U1, and order U1 with U2. | §9.19 L41, L114, L306–307 | restated |
| C-09 | §6.5 C1, "the condition … is discharged". (C + O) moves BC-7's obligations into the delegated record's contract (CON-0171). The workflow fold is excluded by §9.6.6, not answered. Mapping `authorized` to Q3 is a homonym (§6.5 C3). | normal | §9.6 L191; §6.5 L68–72 | answered at system level, with these three notes | §9.19 L26 | noted |
| C-10 | §9.3's reason cites §9.3 C4 (CON-0111: a capability may be a composite of kernel mechanisms). CON-0111 does not place generation in applications; the placement is §9.10.7's Int. | normal | §9.3 L31–35 | cite §9.10.7 (Int) | §9.19 L27 | corrected |
| C-11 | §9.5 C5: the operative answer is that Req holds over **guarded** steps (Kernel′), and T6 shows a guard can break Req_O. The estate-halt is AE (law 20a), which R6 places in (b). "The inputs exist" is the weaker reason. | normal | §9.11 law 20a; §9.16 R6 | add both | §9.19 L30 | added |
| C-12 | E1's boundary analyses are separate in form, but analysis 2 is not RESEARCH-PURPOSE's question ("needed to keep the kernel's invariants"), and analysis 3 uses R7 (ii), not relocation. | normal | RESEARCH-PURPOSE L50–53; §9.19 L72–74 | apply the purpose's questions 2 and 3 | §9.19 L73–74 | restated |
| C-13 | §9.19.5's removal models are definitional: "read by a core reference" is R7 (ii). The label should say so (compare §9.6.3's "definitional necessity"). | normal | §9.19 L148, L166 | label them definitional | §9.19 L166 | labelled |
| C-14 | Missing results: (i) the mechanisms in every kernel are exactly ⋂𝒦. So RESEARCH-PURPOSE's per-capability necessity test finds **nothing** necessary when there are substitutes (COARSE: "in every kernel: []"), even though ∅ fails. This bears on §9.6.3. (ii) A pool-level structure condition: if 𝒦 is Horn-definable in membership variables (each requirement has a unique minimal enabling set), 𝒦 is closed under ∩. My random check found uniqueness in all 644 such cases (form only). | normal | step-1 §2; model COARSE | add as results, or route | — | added or routed |
| C-15 | Prior work, T3 (b): "an up-closed family … principal filter". Under H-down and H-mono, 𝒦 is convex, not up-closed. The proof does not need up-closure, so this concerns the wording, not the proof. Unmentioned: intersection-closed families are Moore families, and Horn theories have least models (the classical source of T3 (a)). | normal | §9.19 L253 | fix the wording; add the pointer | §9.19 L251–256 | corrected |
| C-16 | T6 "H-down and H-mono both fail" reads as universal; the witness shows they *need not hold*. | normal | §9.19 L196 | reword | §9.19 L196 | reworded |
| C-17 | Process order: the model ran before the question was committed, and its frame (state-only, input-free, EF by four kinds) became §9.19.6's setting unexamined (C-04, C-05, C-06). T1, T3, T7 and T8 are unaffected; T5's reach is affected. | normal | RESEARCH-QUESTION L5 | state the frame as a choice of A and Req | — | stated |

## Verdicts

| Part | Verdict | Reason |
|---|---|---|
| §9.19.2 (task 1) | **INVALID as stated** | "Five of six answered" fails §9.19's own class definitions (C-01, C-02). Narrowing L179 is right. |
| §9.19.3 (Req v3) | PROVISIONAL | Q4/G7 and N1 are sound. E1 uses the wrong tests (C-12). The O1-kinds-outside-D point is missing (C-01). The T9-02 claim is wrong for Q4 (C-08). |
| §9.19.4 (A) | PROVISIONAL | B is well grounded (K1, §9.17). G is incomplete: Req_O's reading and item cut (C-05, C-06) and batching (C-04) are missing. |
| §9.19.5 | PROVISIONAL | Status is not used as evidence. The models are definitional (C-13). The Authority row leans on F-min (C-07). |
| T1 | VALIDATED | finite-family minimality |
| T2 | VALIDATED (cited) | scope as §9.1 C3; EF reading (C-06) |
| T3 | VALIDATED | proof steps adequate; prior-work wording (C-15) |
| T4 | VALIDATED | within K1, s₀ without kind i, and EF |
| T5 | VALIDATED in its setting; **INVALID** as glossed in §9.19.1 L12 | state invariants only (C-04) |
| T6 | VALIDATED | a counterexample; wording (C-16) |
| T7 | VALIDATED | for the three listed systems; one-witness omitted (C-06) |
| T8 | VALIDATED | elementary |
| §9.19.8 | PROVISIONAL | no uniqueness or selection claimed (clean); requirement-level candidate (C-07); uniqueness scope (C-04, C-05) |
| §9.19.9 (U1–U4) | PROVISIONAL | U1's reason is wrong (C-08); U2 depends on U1; U3 and U4 are fair |
| Model | VALIDATED as a form check | re-run identical; frame-dependent (probes) |
| Outline / TODO / index | PROVISIONAL | inherit C-01 to C-04; L44 "form only" is accurate |

**Proof-step tables.** All steps of T1, T3 (a) and (b), T4, T5 (under its conditions), T7 and T8 are **adequate**. Hypotheses: H-down and H-mono are stated where T3 (b) uses them. The s₀ premise is stated at T4 step 1 and is implicit at T5 step 3 via T7 (adequate). T5's conditions are sufficient for its conclusion as stated. T6 shows exactly what it claims and no more. Commission limits: EA-1 is labelled; RESEARCH-PURPOSE is unchanged; the primitive set is not finalised; no selection criterion is adopted. T9-02 is not absorbed, but its stated reason is wrong (C-08). Mechanical checks:
- quote check: 1 found, 0 not found;
- R1: 241 homes, 1 flag (CON-0133, inherited);
- R2 in scope: tier A is 0. The relevant tier D words are `appender` (defined) and `countermodel` (defined L149). `derivable` and `generator` (L118) rely on L111's "composite"; define them. `kernel-read` (L309) is undefined: tie it to R7 (ii). The rest are ordinary words or tokenisation artefacts (`minu`, `hypothese`).

## Limits
- I did not open any corpus source.
- I read 09.13, 09.15 and 09.17 only by grep.
- The probes are form only.
- I ran `corpus_quote_check.py` in place, because it locates W from its own path. It is read-only, and `git status` showed no change. I did not run it on a scratch copy.

## Files opened (under …/docs/knowledgeos/knowlegeos_manual_theory_20261018/)
- `06-theory/manuscript/review/REQ-A-TURN/BRIEF-STEP2.md`, `STEP1-ANSWER-C.md`, `RESEARCH-QUESTION.md`
- `03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md`
- `06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md`
- `tmp/tools/req_a_model.py`, `req_a_model-run-20261101.txt`
- grep only: `09.13-the-evaluator.md`, `09.15-the-trust-boundary.md`, `09.17-the-designated-record-and-its-anchors.md`; head of `undefined_terms.py`, `register_home_check.py`, `corpus_quote_check.py`, `inbound_citations.py`
- by `git show`: diffs of `BOOK-OUTLINE.md`, `chapter_09/TODO.md`, `09.00-index.md`, `09.99-change-log.md`

## Commands and searches run
1. `ls` of the review folder; `wc -l` of the step-2 files.
2. `cat` of the model and its run file.
3. grep `\bK1\b` in 09.15 and 09.17; grep `kinds? of D|establish|^- **kind` in 09.13.
4. Copied `req_a_model.py` to the scratch `tools/`, ran it with `python3 -I`, and diffed against the run file: IDENTICAL.
5. Wrote and ran `…/scratchpad/rev-reqa-lynx/probe.py` (`python3 -I`): COARSE with and without a one-kind-per-step I_T; G-act with two Req_O cuts; a one-witness chain.
6. `git show --stat` of 42d353c4e, 6ae633b7d, c5fd16563, c3c1b6b09; `git show 42d353c4e` for the outline, TODO, index and change-log files.
7. grep of the usage lines in four tools.
8. Copied the tools to scratch and ran `undefined_terms.py --root W --no-corpus --scope 09.19` (the first run without `--no-corpus` failed: CORPUS-ALLOWED path). Grepped the output for tier A and 09.19.
9. `register_home_check.py --root W`; `inbound_citations.py --root W` for "§9.10", "§9.5 C1", "§9.4 C7", "§5.5 C3", then "§9.19", "§9.4 C7", "§5.5 C3", "§9.5 C5".
10. `corpus_quote_check.py` on §9.19, run in place.
11. `git status --porcelain` on tmp/tools and 06-theory; grep of the imports and writes in `corpus_quote_check.py`.

Nothing in the repository was edited. No other reviewer's file was opened.
