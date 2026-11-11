DERIVATION REVIEW REPORT C

## Summary

1. The draft is right on two points. Mechanisms are rejected as the unit (§9.21 makes them inert for the laws). Law 1 does follow from CE*'s definition, without the origin premise.
2. The chosen unit (U-k, U-r) does not derive any law about steps. U-k is a state type, and Req is monotone in mechanisms and checks answers state by state. So all transition and history prohibitions (T/H laws) are axioms of P, not consequences of the unit. The draft's own K3 criterion fails for U-k on exactly these laws. **Critical.**
3. Rows 7, 8 and 10a are marked "forced by Req", but a system that meets Req and deletes entries silently breaks them (DC-5 met). The ground rests on §9.10 text that presupposes H1′; the quote at L48 elides "under H1′". **Critical.**
4. Law 1's statement (ii), "for each grant y cites … a chain to a root", is false. An exhibited record in D refutes it, and so do 119 of 2,464 T answers on random records (form only). The proof itself supports only "some cited basis". **High.**
5. Rows 21, 22 and 30 are mis-marked against decided theory. 21 is forced only within one designated record. 22's HP part is (a) self-composition by F36. 30 rests on T9-18 and B-07, and on a merge that lies outside B.
6. The F / C / I split is neither well defined nor exhaustive. "Req" is read sometimes as its formal form and sometimes as its item text. Placement (F36) is mixed with logical relation (rows 20a, 22). And "excluded by Req" has no mark.
7. The P-field test is non-discriminating under Question O (c): every field separates Q2. The P-concept test passes every candidate (§9.16 L383). P-axiom is needed, and the book already has instances of its form.

Process independence: my step-1 answer was saved with its sha256 before I opened the draft. I am independent of the Professor's process, not of the book.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| C-01 | The pair (U-k, U-r) does not derive the T/H laws. "U-k: a unit for S, T and H laws" (§9.22.3 L31) confuses "expressible over" with "derived from". **(a)** Two systems with the same record type can differ on every T law: [root, g1, y] → [root, g1] goes from D to D and breaks H1′. So U-k fails the draft's own K3 for T laws. **(b)** L-mono (Ded): Req_O is upward-closed in M under EF (§9.19.6 L236, H-mono), and observer correctness (a) does not mention M (§9.1 L116). So adding a step-making mechanism keeps Req, and Req forces no prohibition. This depends on U5 (a); under enabledness it would fail. **(c)** The Int at L118, "from the unit (U-k) they are definitional", is false for 4a, which is the origin rule itself (a premise), and for 9b, which is a guard decided by R3a and has no D counterpart (§9.13 L63). | critical | §9.22 L24, L31, L33, L118; §9.1 L96, L99, L116; §9.19 L236 | Result: U-k gives the S laws by typing (𝒮 := D); U-r gives the O and R laws; the T/H prohibitions form a **third component**, step axioms A_T (append-or-tombstone, origin, P-loss, the guard set), held as premises of P and not derived. Add L-mono with its U5 (a) dependence. Restate K2 as derivability, not expressibility. | §9.22.1 items 1, 4; §9.22.3 Result; §9.22.5 "What the marks show"; §9.22.6 bullet 1; 09.00 L32 | §9.22.3 and §9.22.6 restated; A_T named, with each member marked decided or premise |
| C-02 | Rows 7, 8 and 10a are marked F, but a system meeting Req breaks them (DC-5). Observers are correct state by state, and under (c) Q2 is "the record itself, cut at s" (§9.10 L50). After a silent drop, Q2 still equals its reference on the new state. §9.10 L48 conditions on "removed **under H1′**", and the draft's quote elides those words. 10a cites L37, whose "Hence the same call gets the same answer" presupposes that R≤s is stable across states, i.e. append-only: circular. Row 2 has the same defect beyond the cut axis: a rewrite of an earlier entry changes authority without a new act. | critical | §9.10 L37, L48, L50; §9.22 L84–88, L78 | Rows 7, 8 and 10a → C (A_T). Row 2: F only along the cut axis within one record, a definitional property of CE* on D (state the re-reading); C across steps. Quote L48 in full. Correct "Req forces … laws about what the record keeps" (L117). | §9.22.5 L117; count line L114; 09.00 L32 | rows re-marked; counts redone |
| C-03 | Law 1's statement (ii), "for each grant y cites, a chain … ending in a recorded root", is false. **Record:** r root (P); g1 grant by P citing r, holder A; g2 grant by B citing r, holder A; y adopt by A citing [g1, g2]. Result: in_D True; authorized g2 = F, y = T; CE* = T. Authorization is disjunctive (χ, §9.13 L83; L120), and currency needs cited grants **intact**, not authorized (L129). Random probe: 0 failures of the existential form, 119 failures of the universal form in 2,464 T answers on 4,000 records in D (form only, not proof). Step 8 does not follow from step 7, which gives one chain. | high | §9.22 L49, L59–60; probe below | (ii): "some basis cited by y has a chain …". Keep the falsifier, adding the exhibited record as the boundary case. | §9.22.1 item 3 | statement corrected |
| C-04 | Row 30 is marked F "for values and marks". Req is stated for B, one designated record (§9.19.4 L147), and says nothing about merging. A system meeting Req can have an order-dependent merge. The ground uses §9.16 R2 under an identity whose extension is "not separately checked: T9-18" (§9.16 L93), and B-07; neither may be used as decided. | high | §9.22 L111; §9.16 L93; RQ §2 | → C, conditional on T9-18 and B-07 | counts L114 | re-marked |
| C-05 | Row 22's "other channels I, App" contradicts F36 as decided: 22 → (a) self-composition (§9.11 L96; §9.16 L368). The HP part is a condition on the system, so it is C (outside C6, expressible over the product). DC-7 met. | high | §9.11 L96; §9.16 L368 | HP part → C (F36 (a)) | — | re-marked |
| C-06 | Row 21 is F without scope. §9.11 row 21's content is the composed system, and it says that with unequal act sets or positional order the answers differ. F holds only within one designated record at one state. "One requirement whoever asks" is itself a stipulation (§9.10 L36). | high | §9.11 L58; §9.10 L36 | F within B; across applications C (F26 (b), equal act sets at the cut) | L117 | split |
| C-07 | The trichotomy is not well defined. (i) "Req" is read in two ways: rows 7 and 10a use observable-consequence text (§9.10 L37, L48), while REPORT (19), 9a and 12 ignore O1's own text: "idempotently" (L109), "atomically", "the caller receives the refusal with its reason" (L110). (ii) It is not exhaustive. "C" needs "expressible over the unit", so AE (20a) and HP (22) have no logical mark and are pushed into I/App, which is placement (F36), a separate axis. HALT shows 20a is not forced, and a witness without a halting guard shows it is consistent, so 20a is C. (iii) No mark exists for a law Req excludes. | high | §9.22 L70–73, L99–100, L103 | Fix Req = (EF of O1–O4, observer correctness (a)), and list the item-text T conditions as A_T candidates. Add "excluded". Mark placement in a separate column. | L70–73 | definitions restated; rows re-checked |
| C-08 | The P-field test, "removal model … by a core reference", is non-discriminating under Question O (c): any difference in any field separates Q2 (§9.21 L111). Every field passes, including content beyond the required fields. The field-minimality consequence ("needed iff a removal model exists", §9.22.6) is then trivial. | high | §9.21 L111; §9.22 L40, L126 | Exclude Q2, or use R7 (ii) "read by a clause" for P-field | §9.22.6 | restated |
| C-09 | The P-concept test (R7 (ii) through a field) decides nothing: "Every candidate of RESEARCH-PURPOSE passes (ii)" (§9.16 L383). It drops RESEARCH-PURPOSE's own sense, "not derivable" (L47; R7 (i)). "A naming layer over P-field" (L44) pre-empts deliverable 1's form, which is the Principal's to decide. | normal | §9.16 L371–383; RESEARCH-PURPOSE L47 | Test = independence model (R7 (i)); mark the naming-layer reading as an option for the Principal | §9.22.6 bullet 1 | restated |
| C-10 | "P-axiom: new here; no instance given" understates the book. The form has instances: §9.8 M1–M3, independence models for H1′ and H2 relative to A1-as-fact (§9.8 L80–86), and §9.1 C6's deleting record and forge. | normal | §9.8 L80–109; §9.1 L89–91 | Cite them; name the candidate axioms (A_T) | — | added |
| C-11 | Row 23, "F if Q5 counts as core", confuses Req membership with core grounding. Q5 is an item of Req (§9.10.3 B; §9.19.3 L100), with a reference (§9.10 L89). | normal | as cited | → F | L114 | re-marked |
| C-12 | C/D is defined as "D's decided clauses", but 9b and 24 rest on guard decisions (R3a, R4a), and the guard has no static counterpart in D (§9.13 L63). | normal | §9.13 L63; §9.16 R3a, R4a | Redefine as "kept by the Principal's decisions (D clause or guard)" | — | restated |
| C-13 | Row 9b, "Req alone gives detection (CE* F for the later use)", holds for consumptions only. A second work-act use is T in Φ and is only marked (§9.16 L183), and the work act is the use (§9.13 L42). | normal | as cited | Detection by value for consumptions, by mark for work-act uses | — | restated |
| C-14 | Row 26: establishment is not admitted to D (§9.13 L52), so the row is "not yet expressible", like row 6. The "tension with g1" is an unlabelled Int about a different object (evidence class vs established assessment). L119 says "six" and lists five; 26 is the likely sixth. | normal | §9.13 L52; §9.22 L107, L119 | Mark it; label the Int | L119 | corrected |
| C-15 | Row 5's C/D cites §9.20.3 row 3 ("D requires the reason"). §9.13.2's admitted kinds (L28–50) list no reason or recording-time field, and neither does ce_model's FIELDS. A book inconsistency, unresolved. | normal | §9.13 L28–50; §9.20.3 L48 | Resolve which is right before marking row 5 C/D | §9.20.3 L48 | resolved |
| C-16 | Row 16 includes replay and the stability of a past cut, which need H2, H3, F26 and append-only. Only the value at a cut is F. | normal | §9.11 L52; §9.10 L186 | Split: value F; stability and replay C | — | split |
| C-17 | Small defects. The quote of §9.13 L129 alters the parenthesis (drops "; defect 3"). Row 11b cites "K6-type"; the assumption is §9.15 K7 (L80). Law 1 should be labelled Ded (definitional), as §9.20 Th1 is. Step 5's citation of R2 is unused. | normal | as cited | fix | — | fixed |

## Law 1, step by step (§9.22.4)

| Step | Judgement |
|---|---|
| 1 | adequate |
| 2 | adequate (the quote is altered) |
| 3 | adequate |
| 4 | adequate |
| 5 | adequate (R2 is superfluous) |
| 6 | adequate for T |
| 7 | adequate for one chain: delegated grants are covered by a(g) (L122, L125); annulled members are excluded, since T needs ¬⋁ a(e) = T; termination follows from earlier-only citations (L54, L63). A stage induction from ⊥ would drop the D premise. |
| 8 | **inadequate**: its conclusion (ii) is universal over the cited grants and false (C-03) |

**Is it forced by Req?** VALIDATED, given observer condition (a) and Q1's reference being CE* on D.

## Verdicts

- **§9.22.2:** VALIDATED.
- **§9.22.3:**
  - unit (U-k, U-r): **INVALID as stated** (C-01);
  - rejection of U-m: VALIDATED;
  - rejection of U-c: VALIDATED as a set. Authority ✓ and relationships ✓ pass K1 individually but add nothing beyond their fields.
- **Senses of "primitive":** PROVISIONAL (C-08–C-10).
- **§9.22.4:** **INVALID as stated** (statement (ii)). The existential restatement is VALIDATED within D, Θ_f and C1.
- **§9.22.5:** **INVALID as stated** (C-02, C-04–C-07); the other rows are PROVISIONAL.
- **§9.22.6:** PROVISIONAL. It is stated, not settled, but it inherits C-01 and C-08.
- **Index row 9.22 and the change-log row:** VALIDATED as records of the draft; both are to be updated.

## Completeness against my step-1 answer

- **Missing from the draft:**
  - L-mono, and its dependence on U5 (a);
  - the third component A_T;
  - the gap between Req's item text and its formal form;
  - that law 1 does not exclude a forged entry; that is law 2's origin part.
- **Where the draft is right and I was not:** my step 1 marked row 2's record part forced while silently assuming appends. It is forced only along the cut axis (C-02).
- **Kept:** 20a is C (C-07).

## Disproof conditions and limits

| Condition | Status |
|---|---|
| DC-4 | met: law 1 (ii) |
| DC-5 | met: rows 7, 8, 10a, 30, 21 across applications |
| DC-7 | met: row 22 |
| DC-6 | not met |
| DC-8 | handled |

- **Commission limits:** respected (no primitive fixed; minimality kept apart; T9-21 not touched). One exception: L118 treats the origin rule as definitional from the unit (C-01 (c)).

## Not done

- **R2 and R3 tools:** not run. Both scan every chapter file, including files outside my open list (A2). R2 was judged by hand: "record theory" and "core reference" are undefined in §9.22. R3 lines are from my reading only.
- **Not read:** §9.13 L263–374; 09.02–09.09; 09.12; 09.14; 09.17; 09.18 beyond the grep lines; THEORY-MAP; the register; TODO; the commissions file.

## Files opened

Brief STEP2; 09.22; RESEARCH-QUESTION; and in step 1 the files listed there. Grep or sed lines of 10.03 (L113), 09.18, 09.00, 09.99 and 09.13.

## Files copied to scratch

`tmp/tools/ce_model.py` and `tmp/tools/undefined_terms.py`, each by its own path. My own script is `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/dc-c/probe_law1.py`.

## Commands run

- `sed -n 113p` on 10.03.
- `grep "(c2)"` and `grep "T9-17"` on 09.18.
- `grep "9.22"` on 09.00.
- `tail -3` on 09.99.
- `grep "reason|recording time"` on 09.13.
- `cp` of the two tools.
- `grep "^def"` and `sed` reads of ce_model.
- `python3 -I probe_law1.py` (seed 7; output: in_D True, g2 F, y T, CE* T; 4,000 records, 2,464 T answers, existential 0 / universal 119 failures).
- `python3 -I undefined_terms.py --help`: it failed because the root is missing; not rerun.

No globs, listings or git commands were used.
