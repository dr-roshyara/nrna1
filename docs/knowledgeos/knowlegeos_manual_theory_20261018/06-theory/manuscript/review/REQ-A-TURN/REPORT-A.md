REQ-A REVIEW REPORT A

*Reviewer A (kos-lane-reviewer, Opus). Topic review, steps 2 and 3. I re-read my saved step-1 answer first. Independence is process independence, not personal independence: I saw no other reviewer's answer or report. Labels: [V] verified at the line given; [D] my deduction; [I] my judgement; "probe" = a scratch computation, form only.*

## Summary (10 lines)
1. The mathematics in §9.19.6 is sound within its stated settings: T1, T3 (a)/(b), T4, T5 (as conditioned), T6 and T8 pass the proof-step check. My re-run of `req_a_model.py` gives output identical to the run file.
2. Task 1's headline, "five of six answered", fails as stated on §9.19's own class definitions:
   - §9.5 C5 depends on the open choice between EF and enabledness;
   - §9.4 C7 (a) maps to an O1 kind that no Req item requires and that D does not admit;
   - "how to establish a capability" rests on a homonym.
3. §9.19.1 and §9.19.8 state T5 more broadly than T5 proves ("coarser mechanisms **always** break uniqueness"; "any Req …"). My probes give counterexamples to the first and a gap in the second.
4. T7 and the exclusion of ∅ are stated "under every G", but they hold only under one boundary B: K1 with one designated record. The book also has a record set Ρ and owner-application records.
5. No "the minimal kernel", and no selection among minimal candidates, is made anywhere (F-str is not violated). The commission's limits are kept, apart from a partial misstatement that "Req does not need T9-02".
6. L179, TODO T9-01 and index row 9.19 carry the "five answered" overstatement. L44's change is accurate.

## Findings (ranked)

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | §9.5 C5 is classed "answered", but its mapping to O1–O4 holds only under the EF reading. The enabledness rival is open, which makes the class "in part" by §9.19's own definition | high | 09.10 L20 ("Rival, stronger (open)"); 09.11 §9.11.7; 09.16 R6, law 20a. **Probe P4:** a kernel that includes a guard `g_halt` (blocks every step once evidence exists) is in 𝒦, with Req_O = True and AG EF = False. This is S0113's "halts the estate on the day it is enabled" (09.05 L41) | Reclass to in part. Route EF vs enabledness to the Principal (new U5) | 09.19 L30, L43, L270; BOOK-OUTLINE L179; TODO T9-01; 09.00 row 9.19 | class changed, or the EF reading decided |
| A-02 | §9.4 C7 (a) "maps to O1 (establishment)": O1 is "one operation" under EF, so **no** Req item requires that an establishment be acceptable. Also, establishment is not admitted to D. The same gap affects every per-kind mapping (§9.3 adoption, §6.5 C1, recusal, held acts) | high | 09.10 L105 [V] ("one operation"); 09.13 L52 [V] ("Not admitted: … establishment"); §9.19.6 Req_O counts O1 as one kind. **Probe P3:** a pool with only grant and root appenders is a kernel when "act" is one kind, and has none when establishment is its own required kind | Class §9.4 C7 (a) "in part" (needs O1 split per kind, and a D clause). Route the per-kind question (U6) | 09.19 L28, L43; 09.10 L232 note; L179 | O1 split per kind decided, and a D clause written |
| A-03 | "How to establish a capability (S0164 D5 E1–E10)" is classed "answered" through O1's establishment act. S0164's E1–E10 are criteria for showing that a **capability** is realized: E6 repeatability; E10 "Same mechanism, multiple runs"; "= C-10 ESTABLISHED". They are not §9.4's establishment of an assessment. §5.6 keeps "establish" as a separate act | high | S0164 L2221–2267 [V, allowed list checked]; 05.06 L70 [V] ("Accept, approve, establish: three words, three acts") | Reclass. This is a method item (how a Req item is shown; §9.3 C3's shown / refuted / not exercised), not O1. Either give a reason for placing it outside Req, or mark it not addressed | 09.19 L40; 09.10 L200; L179 | reclassed with a reason |
| A-04 | T7 (∅, refuse-all and no-op are not kernels) and T4 need B = K1 over **one** designated record. Req is "of the system the kernel serves" (09.10 L5). The book also has a record set Ρ (09.17 L33, K2-d), owner-application records that carry uses (09.16 R4d), and several records as an open question (TODO T9-07 (c)). Where application records count toward Req_O, a kernel with no mechanisms meets it. This is an F-A scope gap | high | the lines cited | State T1, T4 and T7 "for B with K1 and one designated record". Add the Ρ boundary as an alternative B in §9.19.4 | 09.19 L98, L202, L262–264 | scope stated |
| A-05 | §9.19.1 "Coarser mechanisms **always** break uniqueness …" and §9.19.8 "any Req whose fine minimal kernel has ≥ 2 mechanisms" go beyond T5. T5 covers **augmenting** the pool with a c_E that reaches one state meeting every O_i | high | **Probe P1a/P1b:** replacement coarsening ({c_all}; {c_root_act, a_evid, a_loss}) gives a unique minimal kernel. **Probe P2:** with "evid and loss exclusive", 0 valid states meet every O_i, so c_E cannot exist. Uniqueness still breaks through a partial composite (**P2b**: two minimal kernels), so a general T5′ is plausible but not proved | "Adding a preserving composite under T5's conditions breaks uniqueness". Either prove T5′ (replace a sub-sequence E′ ⊆ E, \|E′\| ≥ 2) or keep the row scoped to T5 | 09.19 L12, L284; 09.00 row 9.19; TODO T9-04 | wording matches T5, or T5′ proved |
| A-06 | T5 step 3 uses T7, whose premise is "s₀ does not meet Req_O". T5 does not state that premise (inadequate: premise missing) | normal | 09.19 L191–194, L233 | Add the premise to T5 | 09.19 L191 | added |
| A-07 | §9.3 is "answered" with §9.3 C4 as the reason, but C4 says a capability may be a **composite of kernel mechanisms**, not that it is application. Also missing: no item requires an adoption to cite evidence, and §9.3 C3's "not exercised" outcome is not addressed | normal | 09.03 C3, C4 [V] | Cite §9.10.7's Int (proposals by applications). State the remainder | 09.19 L27 | restated |
| A-08 | N1 (ii): "inputs are accepted, not produced; it depends on the world". The same holds for O1–O4 under EF, which quantifies over inputs, so (ii) proves too much. Non-admission rests on (i) | normal | 09.19 L80 vs L176 | Drop or restate (ii) | 09.19 L80, L271 | restated |
| A-09 | "Req does not need T9-02": Q4's reference goes through G7, "the class vocabulary against F13 and Q4's grade" | normal | 09.18 L82 [V] | "Req's core, except Q4's reference". Adjust U1's reason | 09.19 L11, L41, L114, L306 | restated |
| A-10 | Homonym and overstatement. Three senses of "necessary": capability (§9.6.3), field (§9.19.5) and by kind (T4). "The notions agree when the pool has no composite" is shown in one direction only | normal | 09.19 L119, L147–168, L189 | Qualify each use; "implies" for "agree" | 09.19 L119; TODO T9-03 | qualified |
| A-11 | T6, "both fail", is shown by one counterexample, so it supports only "need not hold". T3 (b)'s prior-work note calls 𝒦 "up-closed", which is false under H-down (adding b_act breaks P) and not used by the proof | normal | 09.19 L196, L253 | Reword both | 09.19 L196, L253 | reworded |
| A-12 | The model's "every kernel realizes every kind" check re-tests Req itself (tautological), yet §9.19.7 cites it for T4 | normal | req_a_model.py L118 [V] | Drop it as support for T4 | 09.19 L267 | dropped |
| A-13 | E1 mixes boundary question 3 with R7 (ii). The field is accepted and returned by Q2 under either option | normal | 09.19 L72–74 | Keep delegability apart from "read by a kernel reference" | 09.19 L74 | separated |
| A-14 | §9.19.5 individuates candidates inconsistently: grant endings and revocations are lifecycle acts that CE* reads (authority: removal model), while "lifecycle" gets a countermodel | normal | 09.13 L27–35 | State the individuation; split the lifecycle of grants from the lifecycle of items | 09.19 L160 | stated |
| A-15 | §6.5 C1's remainder (the fold of work state) is not placed. A reason exists at 09.06 L191 (CR-w outside both options) but is not cited. §9.5 C1 (a): "latest" can be U under unordered ties (§9.13 v8), which is not stated | normal | lines cited | Cite and add | 09.19 L26, L29 | added |
| A-16 | Process: the disproof conditions were written after the model ran. F-vac's list omits near-trivial "accept once, then halt" systems, and the model's pools do not probe replacement coarsening or exclusive invariants, which is where A-01, A-02 and A-05 show. No result depends on the order | normal | RESEARCH-QUESTION L5 | Add probes like P1–P4 to the model | — | at settlement |
| A-17 | Bootstrap is placed outside Req with a reason (F14). Its falsifier is close to met: S0039 L210, as quoted at 09.02 L12, says "smallest coherent **KnowledgeOS** …" | normal | 09.02 L12 [V] | Note it beside the falsifier | 09.19 L37 | noted |

## Verdicts

| Part | Verdict | Reason (scope) |
|---|---|---|
| §9.19.2 (task 1) | **INVALID as stated** ("five of six answered") | A-01, A-02, A-03. Valid core: §5.5 C3 answered; §9.5 C1 in part; N1 not admitted |
| §9.19.3 Req v3 | PROVISIONAL | Q4 conditional on G7 is right; A-08, A-09, A-13 |
| §9.19.4 A | PROVISIONAL | B and G are kept apart; B is ambiguous on Ρ (A-04); A-10 |
| §9.19.5 | PROVISIONAL | status is not used as evidence [V]; A-14; "history" as Ded is acceptable relative to Q2's "in record order" (09.10 L45) |
| T1 | VALIDATED | finite pool, fixed G |
| T2 | VALIDATED (cited) | §9.1 C3's scope |
| T3 | VALIDATED | steps (a) 1–4 and (b) 1–5 adequate; hypotheses stated where used |
| T4 | VALIDATED | under K1 and the premise on s₀ (step 2 labelled Int (B), correctly); thin content |
| T5 | VALIDATED under its conditions | A-06 (premise missing); the generalisations INVALID as stated (A-05) |
| T6 | VALIDATED as a counterexample | "need not hold" only (A-11) |
| T7 | PROVISIONAL | valid for B with K1; scope missing (A-04) |
| T8 | VALIDATED | elementary |
| §9.19.8 | PROVISIONAL | one row overstated (A-05); no uniqueness claim and no selection [V] |
| §9.19.9 U1–U4 | PROVISIONAL | options fair. Missing: EF vs enabledness, O1 per kind, admission of O1 kinds to D (A-01, A-02). U1's second reason is partly wrong (A-09) |
| Model | VALIDATED as a form check of its three pools | re-run identical; A-12, A-16 |
| L44 | VALIDATED | "form only"; the chain is still missing |
| L179; TODO T9-01; row 9.19 | INVALID as stated | they carry "five of six answered" |
| TODO T9-03, T9-04 | PROVISIONAL | A-10, A-05 wording |

## Completeness against my step-1 answer
- **COVERED:** Q4 conditional on G7; observers as a separate axis; uniqueness iff closed under ∩ (T3, same as my deduction); trivial cases; §9.5 C1 in part; the stale "turn 7 reassesses" pointer (R3 note); guards breaking monotonicity (T6).
- **DIFFER:**
  - §9.5 C5, §9.4 C7, §9.3, "establish a capability", bootstrap: on the evidence above, my classes hold (A-01 to A-03, A-07, A-17).
  - The O1-kinds-not-in-D gap (09.13 L52) is **MISSING** in §9.19.
  - The E criterion ("records and replies") is still unlabelled. §9.19 relies on it for §5.5 C3 and §9.5 C1 (c) without giving it a falsifier: normal.
  - Atomic granularity alone does not give uniqueness (step 1: 31 of 94 atomic pools non-unique). Not stated; it bears on U3.
  - Primitive minimality as a closure question (the anti-exchange Hyp) is not addressed. That is acceptable at this turn.

## Mechanical checks (my runs)
- R1: 241 rows with a home, 1 flag (CON-0133, inherited), the same as reported.
- R2 (`--scope 09.19`, `--no-corpus`): no tier-A candidate in scope. In-scope tier D candidates judged:
  - ordinary words: commonly, outright, waited, remainder, scanned, unfixed, expose, expect, outline, resume, possibility, refine, task, blocked, choose, replaced, routed, strongest, maps, well, return, otherwise, placed, something, left, next, output, plain, once, place, come, changed, until, agree, still;
  - defined in §9.19: appender, coarse, granularity, countermodel, hypotheses (H-down, H-mono), posed (T1);
  - **undefined:** "kernel-read" (U4: define as read by a kernel reference definition, R7 (ii)); "guarded" (L109: say it means partiality, not a C6 guard);
  - **homonym:** "necessity" (A-10); "derivable" is defined only for mechanisms.
- Quote check: the tool fails on a scratch copy (it needs W's layout). I checked the single quote by hand: "subject to authorization" is at S0896 L815, inside L800–815. Found.
- R3: `inbound_citations.py` for "§9.19", "§9.4 C7", "§9.5 C5", "§9.10.7". The citing lines are listed in the R3 column.

## Limits
- I did not read §9.7, §9.12 or §9.14 in their bodies, nor S0039 or S0113 at source.
- The probes check form on four-kind toys; they are not proofs. T5′ is not proved.

## Appendix: probe outputs
- P1a: {c_all} pool → minimal 1.
- P1b: {c_root_act, a_evid, a_loss} → minimal 1.
- P2: exclusive invariant → 0 states meet every kind.
- P2b: + c_root_act → minimal 2.
- P3a: O1 as one kind → kernel {a_grant, a_root}.
- P3b: establishment as its own kind → 0 kernels.
- P4: kernels 2 (one contains g_halt); Req_O True, AG EF False.

## Files opened
- …/06-theory/manuscript/review/REQ-A-TURN/BRIEF-STEP2.md; STEP1-ANSWER-A.md; RESEARCH-QUESTION.md (the folder listing also showed BRIEF-STEP1, SHA256-STEP1, STEP1-ANSWER-B/C; those were not opened)
- …/03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md
- …/06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md (whole)
- …/chapter_09/09.15-the-trust-boundary.md (grep; sed L66–80, L74)
- …/chapter_09/09.17-*.md (grep)
- …/chapter_09/09.13-the-evaluator.md (sed L55–60; grep)
- …/chapter_09/09.10-required-capabilities.md (grep)
- …/chapter_10/10.02-bounded-contexts.md (grep; sed L104–114); chapter_10 listing; 10.04, 10.01 (grep hits)
- …/chapter_01/01.01-knowledge-does-not-execute.md (grep)
- …/chapter_05/05.06-what-an-act-covers.md (sed L70)
- …/06-theory/manuscript/BOOK-OUTLINE.md (sed L1–30)
- …/tmp/tools/req_a_model.py, req_a_model-run-20261101.txt, undefined_terms.py (head), corpus_quote_check.py (head), register_home_check.py, inbound_citations.py (head)
- docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (grep for S0896, S0164); …/02-FILES.jsonl (grep)
- Corpus (allowed list checked): docs/knowledgeos/brainstorming/phase_measure_theory/20260827-183142_step-025d-formal-zero-algebra.md (S0896, L800–815); docs/knowledgeos/brainstorming/20260821-120810-kos-governance-role-cost-optimization.md (S0164, grep; L2221–2300)
- Scratch: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-reqa-heron/ (tools/ copies, r2.txt, model-rerun.txt, probe.py, probe2.py)

(… = /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018)

## Commands and searches
1. `ls` of the review folder; `wc -l` on 09.19, the commission, the research question and the model files.
2. `cat -n` of req_a_model.py and its run file.
3. `git log --oneline -6` on the outline, TODO, index and change log; `git show --stat` for 42d353c4e, 6ae633b7d, c5fd16563, c3c1b6b09; `git show 42d353c4e` on the outline, TODO, index and change log.
4. `grep` for K1, K2-w and "exclusive" in 09.15 and 09.17; `grep` for agents and execution in chapter_10 and 01.01; `sed` of 09.13 L55–60; `grep` for "Kinds of act" and "Each o is" in 09.10.
5. `sed` of the usage lines of the tools; copied the tools to scratch.
6. `python3 -I undefined_terms.py --root W --scope 09.19`: failed on the corpus path. Re-run with `--no-corpus` (output in r2.txt), then `grep` of its tier A and 09.19 hits.
7. `python3 -I register_home_check.py --root W`.
8. `python3 -I corpus_quote_check.py 09.19`: failed (02-FILES path). Manual check: `grep` of CORPUS-ALLOWED and 02-FILES for S0896 and S0164; `sed -n 800,815p` of S0896 with `grep "subject to authorization"`; `grep -n "D5|E10"` and `sed -n 2221,2300p` of S0164.
9. `python3 -I req_a_model.py` on the scratch copy, and a `diff` against the run file: identical.
10. Wrote and ran probe.py (P1–P4) and probe2.py (P2b) in scratch.
11. `python3 -I inbound_citations.py --root W "§9.19" "§9.4 C7" "§9.5 C5" "§9.10.7"`.
12. `sed` of BOOK-OUTLINE L1–30; `sed` of 10.02 L104–114 and 05.06 L70.

Nothing in the repository was edited.
