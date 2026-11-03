CONSOLIDATION S0353–S0377: REPORT A

Reviewer A. Object: the frozen copy `06-theory/manuscript/review/CONSOLIDATION-S0353-S0377/BOOK-UNDER-REVIEW/`. Sections: Ch. 9 `09.00`–`09.09`, `09.99`, `TODO.md`. Instruction followed: REVIEWER-INSTRUCTIONS (consolidation review), under REVIEWER-NOTE and COMMISSION-A.

**Process deviation, disclosed first (REVIEWER-NOTE items 3, 4 and 7).** My first calls of `register_home_check.py`, `undefined_terms.py` and `inbound_citations.py` passed `--help` and no `--root`. The tools ignore `--help`, so they ran on their default root, which is the working lane book. That means the tools read the working chapter files, the working THEORY-MAP, CONCEPT-REGISTER and BOOK-OUTLINE (item-4 files). I did not open any of those files myself. What I saw from them: one R1 summary line, the first 25 lines of the whole-book R2 list, and an empty R3 result. I threw all three away and used none of them. From then on I passed `--root <copy>` on every call. The harness also saved one R3 output (on the copy) by itself to `/home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/bb56vhv9v.txt`, which is outside my scratch directory.

## Summary (15 lines)
1. **Mechanical checks (verified on the copy):** R1 found 241 homed rows and 0 flags. The quote check found 106 quotes in 09.02–09.09, all present at the cited lines. About 40 citations not in quotation marks, spot-checked against allowed sources, are correct; one table cites the wrong line (A-17).
2. **Toy models:** I re-ran `kernel_model.py` and `kernel_type_model.py` and every assertion passed. This reproduces §9.1 C4 and C6 results 1–6. It is still one toy model, so it shows form, not content.
3. **No critical finding.** There are 4 high and 13 normal findings.
4. **Main problem:** stale text. The decisions after turn 7 have not reached §9.6–§9.9 or the index. These are F26 (equal act times are unordered, answer U), F22 (C + O), §9.16 R7 (occurrence is a world assumption) and U5 (EF).
5. **High:** index rows 9.3, 9.4, 9.8 and 9.9 still state forms that were refuted or are INVALID (A-01).
6. **High:** §9.7.7 result 2 says Θ_f excludes F26 (b). §9.13 L270 says F26 (b) at ties is now in Θ_f (A-02).
7. **High:** "occurrence is a candidate axiom" is still a live result (§9.9.5, result 4, CON-0182, map L301). The index note calls it the INVALID form, and result 5 replaces it (A-03).
8. **High:** Lemma D (CON-0177, VALIDATED) omits a premise that proof step 1 uses: losses on record. It holds for values after tombstones only under §9.16 R5's returned reading (A-04).
9. **Logic gap in CON-0173 (§9.7 step 4):** the book shows that changing one parameter at a time changes answers. That does not show each parameter needs a common value unless the parameters are independent of each other; without that, only extensional equality follows (A-11).
10. **Coverage:** S0167 and S0357 have been read (§8.24 C8, §8.83 C3/C5). Ch. 9 still says they "enter when read". T9-01 does not record S0357. T9-14 receives routed items from C89–C105 that its row does not record (A-07, A-08).
11. **All forward pointers resolve.** All 21 TODO rows were checked: 13 still open, 8 resolved, and 5 have stale next-action or home wording.
12. **Concept verdicts:** VALIDATED: 0099, 0105, 0111, 0115, 0167, 0169, 0177 (with the scope correction in A-04). PROVISIONAL kept: 0117, 0138, 0143, 0149, 0160, 0170, 0171, 0173, 0182, 0183. INVALID cores correctly restated: 0100, 0110, 0114, 0116, 0118, 0135, 0136, 0137, 0142, 0148, 0161.
13. **R2:** every in-scope candidate judged. Two are defects: "axiom" (A-03) and "consume" (A-13). One homonym: "delegation" (A-10).
14. **Largest risk:** a reader of §9.6–§9.9 takes forks the Principal has decided as still open, and takes INVALID forms as current results.
15. **Not covered:** §10.3.6 and §10.4 as homes of T9-19 were not opened. The coverage pass and the concept verdicts used only targeted lines of Ch. 9 sections outside my scope.

**Independence:** this is process independence only. I saw no other reviewer's report, no working file opened by me, no git history and no earlier review texts. I am the same kind of model as the author, so it is not personal independence.

## Findings
| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | 09.00 rows 9.3, 9.4, 9.8, 9.9 | Four index rows state refuted or wrongly-statused forms as the sections' subjects. **Row 9.8:** "history derived from three independent record properties (H1–H3)" contradicts §9.8.10 L131 and L133 (independence refuted; H1 replaced by H1′) and CON-0148 INVALID. **Row 9.4:** "a read invariant met by a total derivation is vacuous" is CON-0114's INVALID first statement (§9.4.4 L76); "closed gate inputs (provisional)" ignores that CON-0116 is INVALID as stated. **Row 9.3:** "capabilities are not kernel operators (provisional)" against CON-0111 VALIDATED (L31); "only preventive" against CON-0110 INVALID as "only". **Row 9.9:** "trust as assessment derivable" was refuted at §9.9.11 L139. | high | 09.00 L13, 14, 18, 19; register rows; §9.3 L13, L31 | Restate each row from its restated core: CON-0177, CON-0167 and CON-0116 (as a declared constraint), CON-0111 VALIDATED, CON-0110 scoped, and CON-0160 as a conditional reduction | CON-0111: 05.07 L15, 06.05 L91, 08.28 L19, BOOK-OUTLINE L53, map L277 (correct; no change). CON-0114: map L283, BOOK-OUTLINE L170 (no change). CON-0161: map L301 (change, see A-03) | the rows match the section status lines |
| A-02 | §9.7.7 result 2 (L131) | The restriction note lists "F26 (b)" among the values Θ_f excludes. §9.13 L26 and L270: "F26 (b) at ties is now in Θ_f, v8". The two sections contradict each other. | high | 09.07 L131; 09.13 L26, L270; map L145 | Write "F26 (b) beyond ties (absent or untrusted act times)", or cite §9.13 L270 | 09.08 L29 (same stale dependency; fix with A-09); 09.10 L61 (correct); 09.13 L270 (the authority; no change) | the note agrees with §9.13 L270 |
| A-03 | §9.9.5 L81; §9.9.9 result 4 L125; register CON-0182; map L301 | "Occurrence is a candidate axiom (Hyp)" is still presented as a live result. Result 5 (L126) and the index note (09.00 L19) say occurrence is a world assumption under §9.16 R7. §9.16 R7 was adopted provisionally as Int, and the line it draws is returned to the Principal. §9.9.11 L145 itself says "'axiom' added a criterion the definition lacks". The index note also attributes "candidate axiom" to CON-0161, but CON-0161's invalid text names only "does not reduce" and "dischargeable". | high | 09.09 L81, L125, L126, L145; 09.16 L370–384 | Mark "candidate axiom" in §9.9.5 and result 4 as superseded by §9.16 R7, with R7's rival kept. Align CON-0182's meaning and map L301. Correct the attribution in the index note. The sources (S0973) speak of roots of trust, not axioms, so this is book-internal. | 09.12 L68, 09.15 L55, L68 (use T2 points 1–3, which are unaffected; no change); register CON-0193 (no change); map L301 (change) | one statement in every home |
| A-04 | §9.8.4 Lemma D (CON-0177) | **The conclusion is missing a premise its proof uses.** H1′ (L36) says R≤s is recoverable "except for losses that are themselves on record", and proof step 1 (L57) says "apart from losses on record". Yet the conclusion Hist = CE(…; R≤s) is stated without that exception. §9.16 L71 and L79 say: after a tombstone, values are unchanged "under the Professor's reading … returned"; and for a loss that drops identities, a past cut is not a prefix. §9.13 L277 still says "Its other premises (H1′ …) are unchanged". **Proof-step table:** step 1, H1′: needs "no loss in R≤s" — **missing**. Step 2, H2 and F26: defined, ok. Step 3, H3a/H3b: ok, with the regress F31 stated. Step 4, CE* on D: premise, ok. **Judgement: inadequate, premise missing.** It is not critical: in D, only tombstones occur, and values survive under R5's reading. | high | 09.08 L36, L50–60; 09.16 L71, L79; 09.13 L277 | Add to the lemma: "for cuts not crossing a recorded loss that drops identities; after tombstones, for values only, under §9.16 R5's reading (returned)" | register CON-0177 (add); map L315 (add); 09.13 L274–277 (add); 09.10 L194, L249 (check Q1 at past cuts; same qualifier); 09.19 L197, 08.00 L116, 08.86 L51 (no change); 09.99 L97 (history) | the statement carries the exception |
| A-05 | §9.6.6a L222–224; §9.7.4 step 2 L80–84; §9.7.7 result 3 L132; §9.7.8 L143; §9.8.9 L126 | F26 is still presented as open with options (a)–(c), and "position breaks ties" is still stated. The Principal decided after turn 7: at the same act time the order is U (F26 (b) at ties; §9.13 L26; map L145). §1.14.1 L221 already carries this note; these passages do not. | normal | lines named | Add a one-line note "decided: (b) at ties, §9.13 v8" to each | map L329 still reads "three options" (out of scope; note) | notes present |
| A-06 | §9.6 title, L7, §9.6.8 L238 | The title says "prepared for the Principal's decision" and the status line says "open on K4". F22 has been decided (C + O, 2026-10-21), and K4 was decided per law: prevent single use and once-only, detect the rest (§9.6.6a L217; §9.16 R4). Only the index row notes this. | normal | 09.06 L1, L7, L217, L238 | Add a status note; in L238, point to §9.16 R4 | — | status line current |
| A-07 | 09.00 L3; §9.1.4 L141; §9.1 References L148; §9.9.8 L118 | Stale "when read" pointers (checked against the sources before calling them stale). S0167 was read at §8.24 C8, and its five verbs were tested at §9.10.5 R3 (incomplete as Req). S0357 was read at §8.83 C3/C5. S0630 is beyond the reading position and is still pending. The comparison §9.9.8 promises for S0167 and S0357 against RESEARCH-PURPOSE's candidates was **not done**, so content is missing, not only the pointer. | normal | 08.24 L91–96; 09.10 L206; 08.83 L43–76 | Point to §8.24 C8, §9.10.5 R3 and §8.83 C3/C5; keep the §9.9.8 comparison open for S0167 and S0357 | 09.10 L206 also says "not yet read in order" (reviewer B's section; one line) | pointers current |
| A-08 | TODO T9-01, T9-14 | **T9-01:** the next action still lists S0167 L1139 (done at §9.10.5 R3). S0357's eight capacities and its "minimum Kernel" (§8.83 C3, C5) are a Req-candidate source that the row does not record. **T9-14:** windows C89–C105 route items to it (supersession's kind, CON-0175; item identity through change: 08.00 L104–106; map L263, L265). The row's problem is the version and the bearer of evidence entries. Either the row widens or the routing is wrong. | normal | TODO L11, L24; 08.00 L104–106 | Record S0357 at T9-01; add the routed items to T9-14 or re-route them | — | rows updated |
| A-09 | §9.8.2 L29; §9.8.4 L67 | "[CE] is not yet defined … every result below that uses CE is conditional on that repair". This is stale: CE* on D for Θ_f has existed since turn 4, as L50 itself states. | normal | 09.08 L29, L50 | Add a note pointing to §9.13.6 | 09.13 L274 (correct) | — |
| A-10 | 09.00 L37 (glossary) | The glossary says "K1–K5 in §9.6–§9.7 are the questions of the boundary tests". But §9.6.4's K1 and K2 are assumptions and K5 is a stipulation (CON-0143). S0357's K1–K8, "a third list" (§8.83 C3), is missing, although Ch. 8 now uses "S0357's K5" (08.00 L100). There is also a homonym: "delegation" means relocating a capability (§9.6.4 L113) and also delegating authority (§9.7 L63, §1.13.3(d)). | normal | 09.06 L116–119; 09.07 L76 | Correct the glossary line; add S0357's K-list and "delegation" to it | — | — |
| A-11 | §9.7.4 step 4; CON-0173 | Step 3 shows disagreement by changing one parameter at a time (P, κ, σ, υ). From that, "each parameter needs a common value" follows only if the parameters' effects do not cancel each other out. Without that, only extensional equality of the evaluators on the records held follows. Countermodel: with θ = (a, b) and an answer that depends on a ⊕ b, the settings (0,0) and (1,1) agree everywhere. This step is a sketch, not a proof. | normal | 09.07 L85–86 | State the premise (each parameter's cases stay disagreements for every value of the others), or restate as "the same function on D". The premise can be checked in ce_model. | 13 inbound lines (09.00 L17, 09.06 L255, 09.07 L165, 09.18 L273, 09.20 L63, 10.02 L88, 10.04 L65, 10.05 L246, L318, L326, register 143, 174, map L330): wording "common, versioned value" is kept; only the derivation changes | premise stated or checked |
| A-12 | register CON-0099 | The meaning says "A1 is under-specified with ⊥". §9.1.1 L11 says that in this section "A1" always means the act axiom; the direction's axiom is called Pres there. | normal | 09.01 L11, L22 | Write "Pres is under-specified on ⊥" | 08.42 L43, map L180, L357, BOOK-OUTLINE L166 (no change) | — |
| A-13 | register CON-0183; §9.1 C6 | (a) In the register meaning, the A-01 insertion separates "by a history variable that is empty at s₀…" from "unless carried", so the sentence no longer parses. (b) "Consume" is undefined (L97, L112–113), but result 5's condition (iii) depends on it. In the model, grant and revoke both consume. | normal | register row; 09.01 L97, L112–113 | Reorder the register meaning; add a gloss for "consume" | 09.01 L25, L88; 09.03 L3; 09.11 L3; 09.20 L24; register 101, 184, 189, 190; map L180; BOOK-OUTLINE L167 (no change) | — |
| A-14 | §9.1 C1 L21, L23 | (a) Under the guard reading, "a single guard that refuses every transition satisfies P and Min" holds only if the refused transitions come from outside M. Otherwise ∅ also preserves, and {refuse} is not minimal. The premise is unstated. (b) Reading order: "P" is used at L21 and L23 before C2 defines it at L27. | normal | 09.01 L19–27 | Add the premise; write "Pres" in C1 | 09.02 L19, 09.10 L205, map L161, BOOK-OUTLINE L70 (no change) | — |
| A-15 | §9.5 C5 L44 | "Its must-do half has to exist first (§9.1 C2)". Since U5 (a), EF was decided on 2026-11-01. Under EF, a system that switches on a halting guard after one acceptance still satisfies Req (§9.19 L61), and "§9.5 C5's residue is closed at EF", with the estate halt placed in applications (§9.19 L394). §9.5 does not say this. | normal | 09.19 L61, L394 | Add a pointer to §9.19.9 U5 | 09.10 L132, L234; 09.19 L27, L61, L80, L394 (correct) | — |
| A-16 | §9.6.4 step 1 L122 | Set union with content-derived identity preserves every occurrence only if distinct acts get distinct identities (the identity is injective). Two distinct acts whose read fields are identical merge into one ("the union absorbs duplicates", §9.16 L456). The premise is unstated, and the "64 small cases" check is reported, not shown. | normal | 09.06 L117, L122; 09.16 L93, L456 | State injectivity, or the stipulation that acts with identical content are one act | — | — |
| A-17 | §9.5 References; 09.99; TODO | §9.5 C3 cites S0091 L181 (the I-4 row, which I verified), but References L66 lists L182. 09.99 rows L91–L92 have 3 cells instead of 4. TODO row T9-08 (L18) has 8 cells instead of 9. | normal | sed of S0091 L179–183; cell count | Fix the cells and the line number | — | — |

## Concept verdicts
| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0099 | VALIDATED (second) | Under the closed reading, ∅ is a proper subset of every set and preserves vacuously, so only ∅ satisfies Pres ∧ Min. Re-run: with R = ∅ the minimal kernels are [[]]. Scope: the Pres ∧ Min fragment. Register wording: see A-12. |
| CON-0100 | INVALID, core restated correctly | The deleting-record and forge counter-models are kernels under C2 and not under C6 (re-run, line (1)). Its results for state invariants stand. |
| CON-0183 | PROVISIONAL | The type is coherent. Result 3 is the established argument. Result 4's countermodel and result 5's proof check out step by step, and condition (ii)'s necessity is reproduced (5a). Open: "consume" is undefined, the register text is garbled (A-13), and the content (F36, F13) remains to be built. |
| CON-0105 | VALIDATED (second) | A non-implication shown by counterexample ({record} against {record, grant}). The S0039 L234 quote is verified. |
| CON-0110 | INVALID, core restated correctly | S0041 L23 confirms "FORMAL n=1 at the INSTRUMENTATION level … canon-level … n=0". |
| CON-0111 | VALIDATED (second) | A definitional non-identity. The book's own model shows it independently of S2834: requirement R2 is met by the composite {record, grant}. The index status is stale (A-01). |
| CON-0114 | INVALID, core restated correctly | The default and functional-selector limits hold (S0049 L42–50 verified). |
| CON-0115 | VALIDATED (second) | A source report of a design rule, stated but not held. The quotes (S0050 L86–90) are verified. |
| CON-0116 | INVALID, core restated correctly | Declared, not enforced (S0049 L126). |
| CON-0167 | VALIDATED (second) | A read rule that is discharged everywhere constrains no state. The uniqueness a functional selector needs is C2's, not the read rule's. |
| CON-0117 | PROVISIONAL | One line of support, n = 2, on one day. "Must reach" is a normative design rule, and F18 is open. |
| CON-0118 | INVALID, core restated correctly | A three-valued rule acts where the condition cannot be decided (§2.8.3). |
| CON-0135 | INVALID, core restated correctly | The sources argue for one root (S0072 L32, S0078 L266). |
| CON-0169 | VALIDATED (second) | An analytical split stated as the book's own choice. A START belongs to both capabilities, which is consistent because the split is of capabilities, not of entries. |
| CON-0136 | INVALID, core restated correctly | KC is never met for A1-as-fact and is circular for ARCH-INV-1. |
| CON-0170 | PROVISIONAL | The exclusion clause "invariants that presuppose a placement" has no test. Nothing has used it since F22 was decided. |
| CON-0137 | INVALID, core restated correctly | The one-recorder law is neither sufficient (S0234 L282) nor necessary. |
| CON-0171 | PROVISIONAL | No item is shown necessary or sufficient. The register adds §9.16 R4a's owner rule, but the §9.6.5 list does not. The identity premise is missing (A-16). |
| CON-0138 | PROVISIONAL | It mixes the evaluator role (necessity follows by definition from R_s ∈ Req) with §1.14.1's function (superseded by CE*, CON-0191) and with CON-0173's unproven clause. Recommendation: split it. |
| CON-0142 | INVALID, core restated correctly | "The kernel must own" does not follow. |
| CON-0143 | PROVISIONAL | A normative stipulation that awaits the Principal (map L332). |
| CON-0173 | PROVISIONAL | The inference gap in A-11. |
| CON-0148 | INVALID, core restated correctly | — |
| CON-0149 | PROVISIONAL | Still no independence model for the record. §9.19.5 leaves the question open. |
| CON-0177 | VALIDATED (second), scope corrected | Holds on D for Θ_f for cuts that cross no recorded loss which drops identities. After tombstones it holds for values under §9.16 R5's reading (A-04). |
| CON-0160 | PROVISIONAL | Valid by definition, but its content is only the premise list. f is unconstrained, and L3–L5, which are bundled in, are Hyp. A constant f meets every constraint, so there is no must-do. |
| CON-0161 | INVALID, core restated correctly | The restatement attribution is off (A-03). |
| CON-0182 | PROVISIONAL | Points 1–3 hold under §8.6 C3 and a single operator. "Candidate axiom" is stale against §9.16 R7 (A-03). |

## Coverage (TODO rows; forward pointers)
| Item | Status in copy | Home |
|---|---|---|
| T9-01 | open; next action stale in part | §9.10.5 R3, §9.19; S0357 not recorded (A-08) |
| T9-02 | open (partially resolved), consistent | §9.20; the Principal's ruling of 2026-11-02 |
| T9-03, T9-04 | open, consistent | §9.19.4, §9.19.6, §9.20.8 |
| T9-05 | open; none of my sections types its laws (headers say "informal") | §9.11.3 in part |
| T9-06, T9-07, T9-11, T9-13, T9-15, T9-21 | open, consistent | §9.6 L94; §9.16 R4a; §9.17 (c′); §9.18.10 L298, L300; §9.20.7, §9.20.9 |
| T9-08 | open ((b) and (c) decided); one table cell missing (A-17) | §9.17.5 |
| T9-14 | open; routed items not recorded (A-08) | §8.88–§8.90 |
| T9-18 | open, consistent | §9.16 L93, §9.19 L189, §9.20 L138 |
| T9-09, T9-10, T9-12, T9-16, T9-17, T9-19, T9-20 | resolved, consistent | §9.13.2, §9.17, §9.18; T9-19 also in §10.2.3 and §10.3.3 (§10.3.6 and §10.4 not opened) |
| Forward pointers from 9.1–9.9 to §9.10–§9.20 (§9.10.2, O1, §9.10.7; §9.11; §9.13.2, .5, .6; §9.15 R2, R4, R8; §9.16 R3, R4, R4a, R4 (i), R7; §9.17.5; §9.18.4, .5, .6, .10; §9.19.2, .3, .4, .6, .9; §9.20.4 Th2, .7, .8) | all resolve; content stale where A-02, A-05, A-06, A-09 and A-15 say | — |

## R2 (`--scope 09.00-09.09,09.99`; every in-scope candidate)
| Word | Judgement |
|---|---|
| mover | defined at 09.00 L39 (the tool misses that quoted-bold form) |
| antitone | glossed at 09.01 L117 |
| hyperproperty | appears first in index row 9.11 and is defined at §9.11 L23; a forward use in an index row is acceptable |
| unfaithful, unfaithfulness | negations of T-b's "faithfully" (09.09 L29); acceptable |
| truster | defined at 09.09 L26 |
| SE-3 | a journal volume in a citation, not a term |
| D-02, C-13 | consolidation finding IDs used as provenance; the book's usual practice; no action |
| axiom | **defect**: used without the §12b criterion, which §9.9.11 L145 itself admits (A-03) |
| consume | **defect**: undefined, and result 5's condition (iii) depends on it (A-13) |
| delegation | **homonym** (A-10) |
| adjudication | two local senses: "a fold is not an adjudication" (§9.6 L196, home §6.5 C7) and "adjudicated outcomes" (premise (vi) of §9.9); each is glossed where used; acceptable |
| recoverable | used in H1′ without a definition; acceptable, but tied to A-04 |
| context-ward, plane-ward, question-specific, serialise, single-write-path, trust-weighted, contract-time (§9.7 L86), mechanism-minimal (C2 L30), triple (L94), relocated, unattested, discharged, incomparable, authenticity (L68), granularity (L76), derivable, safety, reliability, recursive, reconstruct | defined or glossed in place; acceptable |
| decrease, despite, believed, span, population, implicit, determined, security, provided, querie(s), signal, receive, establishe(s), classifie(s), query, timestamp, implement, available, incomplete, warn, legitimate, empirical, weak, enumeration, compute, unique, claimed, deterministic, everything, matche(s), refute, material, occurred, together, asked, define, plus, built, qualified, beyond, adopt, challenge, beside, once, because, agree, keep, restriction | ordinary English, not used as terms |

## Coverage of sections
| Section | Read |
|---|---|
| 09.00, 09.01–09.09, 09.99, TODO.md | in full |
| Other Ch. 9 sections, Ch. 8, Ch. 1, the copy's map and register | only targeted lines, for pointers, R3 and verdicts (listed below) |

## Files opened
- **Read tool:** REVIEWER-NOTE.md; SCOPE.md; COMMISSION-A.md (in the consolidation folder); `03-methodology/REVIEWER-INSTRUCTIONS.md`. In the copy: `chapter_09/09.00-index.md`, `TODO.md`, `09.99-change-log.md`, `09.01`–`09.09` (each in full).
- **Copy, by sed, grep or Python on named lines:**
  - `chapter_09`: 09.10 L204–207 (plus a heading grep); 09.13 L26, 52, 68, 265, 270, 272–279; 09.16 L93, 319, 370–384 (plus greps for "tombstone", "identical", "R7"); 09.19 (greps for F-min/EF, U5); 09.20, 09.15, 09.17, 09.18 (heading greps); 09.11 (grep "hyperpropert").
  - `chapter_08`: 08.24 L89–97; 08.83 (wc, L1–8, L43–52, L68–78, a grep); 08.00 L98–114 (Python, T9 mentions).
  - `chapter_01`: 01.14 L221.
  - `THEORY-MAP.md`: grep F26/F27/K5; L141, 145, 259, 301; L257–273 (Python).
  - `CONCEPT-REGISTER.tsv`: rows of the commissioned concepts.
- **Corpus (all on the allowed list; checked):** `CORPUS-ALLOWED.tsv` (greps); S0091 L70, 179–183, 339; S0051 L64; S0041 L23; S0113 L44; S0071 L197, 202, 237; S0067 L9; S0073 L88, 155; S0234 L264–266, 330–340 (plus a grep for AST-015/016); S0078 L258; S1500 L124, 223, 225, 273–286; S0129 L4, 97, 158; S2775 L1650–1688 (grep).
- **Lane tool sources (read-only greps or heads):** register_home_check.py, undefined_terms.py (L1–16), inbound_citations.py (L1–16 plus a grep), corpus_quote_check.py (L1–12), kernel_model.py and kernel_type_model.py (grep for file I/O).

## Commands and searches run
1. `wc` on the Ch. 9 files (one first try at a wrong path, which failed).
2. The first tool calls with `--help`: `book_search.py` (on the copy), and `register_home_check`, `undefined_terms`, `inbound_citations` (**default root, the deviation above**). `corpus_quote_check --help` errored.
3. `register_home_check.py --root <copy>`; `undefined_terms.py --root <copy> --scope 09.00-09.09,09.99`; `corpus_quote_check.py` on the 12 named files of the copy.
4. `inbound_citations.py --root <copy>` with: "§9.7.7 result 2", "§9.9.9 result 4", "§9.9.5", CON-0182, "§9.8 Lemma D", CON-0177, "§9.1.4", "§9.7.8", F26; then "§9.7 result 2", "§9.9 T2", "§9.8 D", "Lemma D", "§9.9 result 4", "§9.9.5 What follows"; then §9.7, §9.8, §9.9, §9.1, CON-0111, CON-0114, CON-0161; then CON-0173, CON-0183, CON-0099, "§9.1 C1", "§9.5 C5".
5. `book_search.py` on the copy: F26, S0357, S0167, T9-19, T9-18, T9-21, T9-06, T9-13, T9-05, T9-15, T9-14, S0630, U5.
6. `kernel_model.py` and `kernel_type_model.py` run, with output to scratch.
7. Cell counts of the 09.99 and TODO tables; an allowed-list check of the forbidden IDs (S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249: none present).

All outputs are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons-s0353-a/` (r1.txt, r2.txt, qc.txt, r3.txt, r3b.txt, r3c.txt, r3d.txt, reg.txt, km.txt, ktm.txt). Nothing in the repository was edited. No git command was run. No glob, recursive search or directory listing was used. Item-4 files were reached only through the tool deviation disclosed at the top.
