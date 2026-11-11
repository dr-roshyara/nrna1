DERIVATION REVIEW REPORT B

Reviewer B (kos-lane-reviewer, Sonnet), topic review, step 2. Independence is process independence only. Line references are to the files as they stand on disk, with no git history. Facts, interpretations [I] and recommendations are kept apart.

## Summary
1. **Law 1's statement is too strong.** Clause (ii), "for each grant y cites", is false. A scratch run of `ce_model.py` gives CE* = T on a record in D where a cited grant has no chain to a root (F1).
2. **Rows 2, 7, 8 and 10a are marked F wrongly.** §9.1 L89–91 and L109 show that the deleting record and the forge meet P and Req. §9.22's own row 4a and its U-r row say the same. These laws hold only with the origin premise, or under K1 (§9.15 L74) (F2).
3. **The unit omits the transition map** that the step laws need, and the origin premise lives there (F3).
4. **"Forced" is applied by two standards.** Some Req-stated laws count as F (15, 17, 21) and others do not (9a, 12, 19 REPORT, 29) (F4).
5. **The unit choice is broadly sound.** (U-m) is rightly rejected on K3. (U-c) is rejected correctly in total, but K1 is the wrong ground for the decided concepts. The pair is right in content once the transition map is added.
6. **P-axiom is not needed** and adds a homonym. The P-concept and P-field tests are partly swapped or incomplete.
7. **Other row fixes:** 18, 22 (HP part), 23, 26 and 30, plus K7 as the exact home of row 11b.
8. **§9.22.6 states one point as settled that is the Principal's:** where primitive minimality sits (F11).

Process independence only: I read the draft and the cited book lines myself. I did not see the other reviewers' work.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| F1 | Law 1 (ii) overclaims. Φ is disjunctive over cited bases (§9.13 L120), but current-authority requires only that every cited grant be *intact* (L129; L87 "conjunctive … while authorization is disjunctive"), not authorized. Counterexample, run on a scratch copy with `python3 -I`: root r; grant g1 (holder B, cites r); grant g2 (holder B, cites nothing); adoption y of c by B citing g1 and g2. Result: in_D True, CE* T, atoms r T, g1 T, g2 F, y T. Step 7 gives only *some* chain; step 8 does not yield (ii). | high | §9.13 L87, L120, L129; probe | State (ii) as "some cited basis g with basis(g,y) = T has a chain of recorded grants to a recorded root". Add "root T by stipulation, a non-root act citing nothing is F" to step 6. Falsifier (b) must test "some", not "each". | §9.22 L49, L58–60, L62–63 | statement, steps 7–8 and falsifier agree |
| F2 | Rows 2, 7, 8, 10a (and 16 in part) are marked F, but a Req system can break them. §9.1 L89–91: "In each, P and Req hold" for the deleting record and the forge. Row 4a (L80) says "Req's observers are met whatever the mechanism appends", so row 2 contradicts it. §9.22.3's U-r row (L30) says S and T are not stated in Req, so T-laws cannot be F. Q1 L37's "Hence the same call gets the same answer" presupposes a stable prefix, so row 10a is circular. Row 7's "appears as its recorded loss" covers removals that occurred, not silent ones. What forces the laws is the origin premise (or its observable twin: the reference record is a function of accepted inputs), or K1 (§9.15 L74). Row 2's F also reads "labelled by a recorded act" so weakly that every entry qualifies (DC-8). | high | §9.1 L89–91, L109; §9.10 L37, L48; §9.15 L74 | Re-mark 2, 7, 8, 10a (6 as well) as C, "on the origin premise", with K1 named. Keep F only for "answers are a function of the cut". Correct the closing paragraph "What the marks show". | §9.22 L78, L84–85, L88, L114, L117–118 | the marks match §9.1 C6 |
| F3 | The unit "(U-k) kinds, fields, clauses" has no transitions. Th1 step 7 (§9.20.4 L78) supplies the transition map, and it carries the origin premise. The pair should be (record type + transition map + Req's references). | high | §9.20.4 L78; §9.1 L96 | State the transition map in the unit and mark it as premise-dependent. | §9.22 L31–33 | unit statement names the premise |
| F4 | Inconsistent standard for F. 21 and 15/17 are F because Req states them. But O1 states idempotence (§9.10 L109), a content-derived identity (L109) and atomicity (L110), and a refusal reply with its reason (L110), yet rows 9a, 29, 12 and 19 REPORT are C, C/D, I-K and C. 15 and 17 are F by identity: they are Req's items. | normal | §9.10 L36, L109–110 | Use two marks, F-stated and F-derived. Count only F-derived as results; list the identity-F rows apart. | §9.22 L86, L92, L95–97, L99, L110, L114 | one standard stated |
| F5 | Row 30 is F, but the merge is not a Req item. What is forced: Q1 and Q2 answers over an entry set in D do not depend on the linear extension (CE* reads no position, §9.13 L26). The merge function's order-independence is C. | normal | §9.16 L99–101 | Split row 30. | §9.22 L111 | split |
| F6 | Row 22 puts "other channels" (HP) in I, App. F36 decided 22 → (a), self-composition, a kernel method (§9.11 L96). Not an application rule (DC-7). | normal | §9.11 L59, L96 | C. | §9.22 L103 | mark corrected |
| F7 | Row 18: reading (i) is T and expressible in C6 (§9.11 L54); what is missing is a kind in D (§9.16 L192, cited by the draft; not read by me). Reading (ii) is L+E, i.e. I, W (E). The draft drops the split. L119 says "Six laws" and lists five. | normal | §9.11 L54 | Split 18; fix the count. | §9.22 L98, L119 | corrected |
| F8 | Row 26's reason is misaligned. g1's "contested" is an evidence class, not two established assessments. The stronger grounds: establishment is not admitted to D (§9.19 L59) and F36 decided (b) detect (§9.11 L96; §10.3 L113). The mark C is fine. I did not read T9-17 (b). | normal | §9.19.2 L59; §10.3 L113 | Replace the ground. | §9.22 L107 | corrected |
| F9 | Row 11b cites "K6-type"; the exact home is K7 (§9.15 L80), and row 12 is K6 (L79). Row 23's "F if Q5 counts as core, else C" is not a well-defined mark. Q5 is a Req item (§9.19.3 L100), so mark F, and note the L167 list defect (already noted at §9.20.2). Row 16's replay half is C (θ-setting acts are outside D, §9.13 L52). Row 14 omits H4 (§9.11 row 14; §9.15 R2: entitlement is internal). | normal | as cited | Fix the cells. | §9.22 L91–92, L94, L96, L104 | corrected |
| F10 | Senses. P-axiom is not needed and adds a homonym: §9.1 L11 already uses "axiom" for Pres/Min. "No instance given", and no book use of "primitive" means a law. P-concept's test (R7 (ii)) is a field-necessity test. RESEARCH-PURPOSE L46 tests concepts by non-derivability from other primitives. P-field's test (removal model) has no "required by decision" outcome, though §9.20.3 row 3 and M5/M6 need one. "P-concept is a naming layer over P-field" fails for trust, policy and conflict, which have no field. | normal | §9.1 L11; §9.19.5 L207–221; §9.20.3 | Drop P-axiom (keep "independent law", "premise"). Give P-concept the non-derivability test. Give P-field three outcomes (read, required by decision, open). | §9.22 L35–44 | senses table revised |
| F11 | Consequences: "Primitive minimality in RESEARCH-PURPOSE's sense … is the P-axiom or P-field notion" decides where the Principal's notion sits. RESEARCH-PURPOSE applies it to concepts. Also law 1 is restated from a state invariant to an answer law, but §9.20.8 L156 says its burden moves to the origin condition. The draft says "no origin" and calls it Ded without the Th1 label "definitional". | normal | §9.20.8 L156; RESEARCH-PURPOSE L46 | Mark as "for the Principal"; label law 1 definitional; say that the S form is vacuous and the origin share is law 2. | §9.22 L62, L129 | corrected |
| F12 | Criteria K1–K4 lack a non-circularity test. D contains laws as clauses (tombstone, citations earlier, §9.13 L54–60). Req's items contain laws (§9.10 L37, L48, L109–110). Derivations from them are definitional. | normal | as cited | Add K5 (not defined by the laws it yields), or label such derivations. | §9.22 L24 | added |
| F13 | Quote check. L54 quotes "(C1 = constitute)"; the source reads "(C1 = constitute; defect 3)" (§9.13 L129). The step 7 citation L63 is the append-guard line; the rule is L54–57. A cut R≤s need not be in D (L135); say so (earlier positions suffice). | normal | §9.13 L54–57, L129, L135 | Fix the quote and citation. | §9.22 L54, L59 | corrected |
| F14 | The draft adds class K to the commission's AR/WA classes. It discloses this, and K6/K7 do fit §9.15's criterion (L44). E is folded into W. | normal | §9.15 L44 | Keep; name "WA (E)"; record the deviation for the Principal. | §9.22 L72 | noted |

**Checked and found right.**
- Row 9b (C/D), 20a (I, App), 24, 27, 31 and 13.
- Row 21 is F for one designated record; state that condition (K2-r, §9.15 L75).
- Counts: 36 rows; F 11, C 16, I 9 sum to 36 as listed.
- Index row (09.00 L32) and the change-log row are consistent with the draft. Both must change when F1–F3 are applied.

## Questions 3–5 specifically
- **K1–K4 applied.**
  - (U-c): rejected correctly as a set. Authority ✓ and relationships ✓ (§9.19.5 L192, L194) pass K1 alone. The fair ground is K2 and K4: laws need history, identity and evidence as well, and a derivation fails only through fields. "Most are ~ or —" is not a reason against the decided ones.
  - (U-m): K1 is the weaker ground (the pool is open by decision U3 (b), not undefined). K3 is sound: §9.21.3 (1) shows inertness up to case-split regrouping and guard placement, which is all the laws need.
  - (U-r): a unit for O and R laws is partly tautological (F4).
- **Pair, one candidate, or none.** One candidate does not suffice. The pair is right in content once the transition map is stated (F3).
- **Law 1 steps.**
  - Steps 1–6: adequate, but step 6 omits the root and no-basis cases (F1).
  - Step 5: adequate.
  - Steps 7–8: inadequate as written (F1). Step 7 under §9.13 L120–125 and R1–R2 is right for delegated grants and plural bases only in the "some" form. An annulment does not change existence of a chain; the cut is a prefix.
- **Forced by Req.** The answer-form law is forced via Q1's reference (§9.10 L39). It is definitional, as Th1. The falsifier is right for (i) and wrong for (ii); I ran the scratch model once on this single record, which shows the form only.

## Completeness against my step-1 answer
- **Draft misses:**
  - the observable twin of the origin rule (P2);
  - the §9.1 counter-models;
  - the F-stated / F-derived split;
  - law 5's presence/presentation split;
  - law 16's replay split;
  - law 14's H4.
- **Where we differ, my reading is right:** rows 2, 7, 8, 10a (F2); rows 9a, 12, 19 REPORT, 29 (F4); rows 22, 18, 30.
- **Where the draft is better or equal:**
  - row 2's note that authority lapses along valid time without an act;
  - classing rows 11b and 12 as K (defensible; I had law 12 as F-stated and vacuous);
  - row 23's mark was F in mine.

## Verdicts

| Part | Verdict |
|---|---|
| §9.22.2 | VALIDATED (three objects; 36 rows) |
| §9.22.3 unit | PROVISIONAL (F3, F12) |
| §9.22.3 senses | PROVISIONAL; P-axiom INVALID as needed (F10) |
| §9.22.4 law 1 | INVALID as stated (ii); PROVISIONAL with (ii) weakened (F1, F11) |
| §9.22.5 marks | PROVISIONAL; rows 2, 7, 8, 10a INVALID as marked; 18, 22, 23, 26, 30 to correct |
| §9.22.6 | PROVISIONAL (F11); no primitive fixed, deliverable 1's form left open |
| Index and change-log rows | PROVISIONAL (to update) |

**DC-1–DC-8 against the draft.**
- DC-1: not met after F7.
- DC-4: met against law 1 (ii) (F1).
- DC-5: met for rows 2, 7, 8, 10a.
- DC-6, DC-7: met for row 22 HP.
- DC-8: row 2's labelling reading, and law 1 being definitional.
- Limits: respected (no primitive fixed, no T9-21, origin only as a premise), except the F11 point and row 22.

## Not done
I did not run the other model checks, `undefined_terms.py` or the quote baseline. I read only §10.3 L108–117, §9.15 L70–109 and line-level greps of 09.00, 09.99, 09.04 and 09.18 beyond step 1. T9-17 (b) and §9.16 L192 were not read. The F-row verdicts rest on the cited book text and on one scratch run.

## Files opened (step 2, plus step 1's as listed in my first answer)
- `06-theory/manuscript/review/DERIVATION-CHAIN-TURN/BRIEF-STEP2.md`
- `06-theory/manuscript/chapter_09/09.22-the-derivation-chain-unit-of-derivation-and-the-laws.md`
- `06-theory/manuscript/review/DERIVATION-CHAIN-TURN/RESEARCH-QUESTION.md`
- `06-theory/manuscript/chapter_10/10.03-ownership-of-invariants.md` (L108–117)
- `06-theory/manuscript/chapter_09/09.15-the-trust-boundary.md` (L70–109)

## File copied
`tmp/tools/ce_model.py` to `…/scratchpad/dc-b/model/ce_model.py`. The probe script is `…/scratchpad/dc-b/probe.py`.

## Commands
- `grep -n "9\.22"` on `09.00-index.md`; `tail -n 3` on `09.99-change-log.md`; `grep -n "at most one"` on `09.04-invariants-that-cannot-fail.md`; `grep -n "(c2)"` on `09.18-evidence-in-the-record.md` (one Bash call).
- `wc -l` and `grep -n "^def …"` on `ce_model.py`.
- `cp` of `ce_model.py` to scratch, then `sed -n` on the scratch copy (lines 120–163, 194–240).
- `python3 -I probe.py`, run twice (the second after a one-line `sed -i` on the scratch probe).

No git command, no glob, and no corpus source was used.
