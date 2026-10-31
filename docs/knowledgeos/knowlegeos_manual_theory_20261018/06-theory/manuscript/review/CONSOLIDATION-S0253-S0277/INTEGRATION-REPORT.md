CONSOLIDATION S0253–S0277: INTEGRATION REPORT

Object: the frozen copy `W/06-theory/manuscript/review/CONSOLIDATION-S0253-S0277/BOOK-UNDER-REVIEW/` (W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`). Line numbers are the copy's. I edited nothing.
Labels used: **[V]** = I re-checked it on the copy or the source. **[R]** = I accepted it on the reviewer's cited lines without re-checking.

## Summary (10 lines)
1. **Count.** 59 findings were reported (A 17, B 18, C 16, D 8). Merged: 54. **Confirmed:** 44. **Modified:** 10 (B-01, B-03, B-15, B-16, C-02, C-08, C-12, D-06, A-17 locator, A-01 merged with B-11). **Rejected outright:** none. **Critical:** none at present.
2. **High, confirmed (6):** B-02 (§1.16.6's trichotomy, which conflicts with F18 and §6.6); C-01 (§8.31 L117); D-01 (Ch. 9 and Ch. 10 disagree on the domain); D-02 (the scope of the T9-19 act); D-03 (candidate 2 stated as pending at about 15 sites); D-04, the L3 part (§9.18's head says "Nothing here is adopted").
3. **Questions for the Principal.** D-01 and D-02 cannot be repaired by an edit alone. The decision record adopts the kinds but does not state whether W's acceptance rule (G8/(c3)), g1 or the two-level domain is adopted. Two questions follow, and one more for D-06(c) (section 8).
4. **Would become critical (F3):** D-02, if the Principal answers "the kinds only". Candidate 2's admission and §9.15 G1's "outside D_S" would then rest on a premise that has not been adopted. W's published language should not be built until this is answered.
5. **Downgraded to normal:** B-01 (the countermodel is valid, but it hits a model the copy itself marks as superseded history, and nothing cites it); B-03 (bookkeeping); C-02 (a map summary that already carries the three readings).
6. **Partly wrong corrections:** C-08's "L196 → L197" (the literal phrase is at S0269 L16). C-12 (the §8.48 row already carries "(filename kept)"). D-06(c) (the copy does say "Shown as"). B-16, S0024 half (L155 supports the claim in meaning).
7. **Rotated-chapter defects confirmed:** Ch. 0: 6. Ch. 1: 29 (A 11, B 18). No new defect in Ch. 0 or Ch. 1 beyond one merge (A-01 with B-11 at §0.1 L30). New outside them: 06.06 L22 (needs B-02's change) and map L399 (D-05, D-06(d)).
8. **Coverage gaps:** claims sourced from `knowledge_tranfer` (forbidden: nobody checked them); 22 Ch. 1 TODO rows; S0270 and S0271, behind §8.50; CON-0033, which is omitted from SCOPE §2.2.
9. **Access deviations:** none of A's sampled findings rests on a working-book read (8 re-checked, all hold). B, C and D's deviations do not affect any finding. My own deviation: one `inbound_citations.py --help` call without `--root` (zero lines returned; see the end).
10. **Process independence:** I am an integration reviewer in a fresh session. I wrote none of the reviewed work or reports, and I read the reports only as allowed. This is independence of process, not of person or of model family.

## 1. Findings
| ID(s) | Verdict | Sev. | Ch. | Evidence | Correction |
|---|---|---|---|---|---|
| D-04 (L3) + A out-of-scope + B out-of-scope | confirmed [V] | high | 9 | 09.18 L3 says "Nothing here is adopted … proposed kinds, for the Principal"; L45 and L295 say "decided, all three adopted" | Date L3 the way 09.00 L28 does ("at settlement nothing adopted; kinds adopted 2026-10-31, T9-19") |
| D-04 (rest) | confirmed [V] | normal | 9, 8, 10 | 09.18 L26, L28, L55, L105, L123, L132, L138, L179, L222, L269; Ch. 9 TODO L26; 08.37 L40; 10.02 L208; 10.05 L291 | Mark "proposed at settlement; adopted T9-19". For L179 and L222 (G8, (c3)), wait for Q-A (section 8) |
| C-01 = D-04 (08.31 site) | confirmed [V] | high | 8 | 08.31 L117: "not a kernel fact … evidence kinds are not admitted (gap G1). In D it holds vacuously". Against 10.02 L214 ("closed by adoption") and 09.18 L107. (c2) is a D_S clause kept by T9-20 | Status: "(c2), a clause of D_S since T9-19/T9-20; vacuous only in the pre-adoption D". Write "D_S", not "D_S ∪ D_W" (see D-08) |
| D-01 | confirmed [V] | high | 10 vs 9 | §10.3.6 (d3) at 10.03 L258; 10.04 L59 (R6), L108, L140, L164; 10.05 L143, L249; map L398. Against 09.15 L63 ("outside D_S") and 09.18 L240–243 ("This is a change of the kernel's domain statement now that the evidence kinds are adopted"). Note: L240 still heads this "What follows (Int, for review)" | **Hold the edit for Q-A.** If the answer is yes, restate (d3) in two levels and re-mark R6, PL-3 L143, PL-12 L249, 10.04 L108/L140/L164 and map L398. If no, revert 09.15 L63 and 09.18 L243 to "for review" |
| D-02 | confirmed [V] | high (critical under F3 if Q-A is answered "kinds only") | 9 | 09.18 L295 states no scope. G8 (L179) and (c3) (L222) are "proposed". L209: g1's "adoption with the evidence kinds stays open". 10.03 L289 treats "acceptance rule D_W, its own" as adopted | State the act's scope at §9.18.10 item 4 from the record and Q-A |
| D-03 | confirmed [V] | high | 10, map | Admitted at 10.03 L289, L319, 10.02 L5, 10.00 L8. Still "pending" or without record kinds at 10.04 L3, L16, L113, L126; 10.05 L253, L281, L291; 10.03 L79, L106, L108 ("B. … no record support (G1)"), L112, L167, L175, L188; map L397, L398 | Annotate "admitted (S0, for review) since T9-19". Row B's enforcer of O2 waits for Q-A |
| D-05 (+ new site) | confirmed [V] | normal | 10, map | "conditional on G1's adoption" at 10.02 L9 and map L396. **New:** map L399 "a joint answer conditional on G1 (PL-14)" | "G1 adopted (T9-19); conditional on G3, G7, G10" |
| D-06 (a) | confirmed [V] | normal | 10 | 10.05 L294: "the pin's owner is contested (§10.3.3 C)"; 10.03 L129 says "disputed" | "disputed" |
| D-06 (b) | confirmed [V] | normal | 9 | 09.18 L302 marks all the naming items "decided". The record and Ch. 10 TODO L26 keep (3), the area's name, open with T10-15 | "(1), (2), (4) decided; (3) with T10-15" |
| D-06 (c) | **modified** | normal | 10, 9, 8 | 10.05 L174 already says "**Shown as** 'not recognized as current authority', never 'refuted'", so the claim that "the book does not say so" is wrong. What remains: F's *definition* "refuted, or not recognized" (§9.13 R2a L208, and 08.31 L23, L59; 08.33 L19, L44; 08.36 L81; 08.37 L116; 08.40 L17, L39) keeps the evidence class's word | Question Q-C (section 8); no edit before the answer |
| D-06 (d) | confirmed [V] | normal | 10, map | "not attested" used for consumer reliance at 10.05 L360 and map L399: a third sense | "not evidenced" |
| D-07 + C-05 (same text) | confirmed [V] | normal | 8 | 08.38 L27 ("would be a choice for the Principal"); 08.00 L51, L52; 08.38 L61's note covers two forks, and the Q4/G7 fork is still open (map L419). "proposed (c2)" remains at 08.37 L43, 08.38 L55, L61, L156, 08.42 L51, map L418 | Annotate "decided: no guard (T9-20)". Scope L61's note to the guard fork; mark the Q4 fork open (G7). Drop "proposed" before (c2) |
| C-06 + 09.18 L221 | confirmed [V] | normal | 8, 9 | 08.35 L59 and 09.18 L221: "kept against a guard" (can be read as "kept with a guard") | "kept; no guard on adoption (T9-20)" |
| D-08 | confirmed [V] | normal | 9 | 09.15 L63: "on a record in D, CE* answers … outside D_S, Q1 refuses". 09.18 L107: "D_S together with D_W" (intersection or two domains?). Register CON-0193 | Name D_S throughout G1; define post-adoption "D" once. Follows Q-A |
| C-02 | **modified**: high → normal | normal | map | Map L222 states as fact "The admitting act is the commission". §8.47 C1 (L38–39) and §8.47.5 L109 say the records *name* it. "Two readings of Zero kept" misstates C2 (L49: a different *sense*). The map does carry "(three readings …)", and nothing depends on L222 | As C proposes |
| C-03 | confirmed [V] | normal | map | Map L223 attaches "(S0281)" to S0265's bounded context; 08.48 L8 and L116 tie S0281 to S0266. The title says "narrowed", not "withdrawn" | As C proposes |
| C-04 | confirmed, as Int [V] | normal | 8 | S0255 L85–87: the HPA's "This … keeps it proposed" follows "the §BE candidate refined"; S0254 L137 separates "Ch I text" from the "character expression" | Add reading (d), the referent; state (c)'s premise |
| C-07 | confirmed [V] | normal | 8 | 08.50 L27 counts "two exact, two by content, two wrong" against seven rows (L49–57, one "a loose label"). 08.00 L64 has the same count | "… one a loose label, two wrong" |
| C-08 | **modified** [V] | normal | 8 | S0269 L196 is blank. L197 reads "Adoption is NOT decided" (advisory in meaning only). The literal "advisory evidence" is at **L16** (also L27) | Cite "advisory evidence" at **L16**, not L197 (08.50 L63, L68; L20 as table) |
| C-09 | confirmed [V] | normal | 8 | S0257 L14 says "not as a research reopening"; S0261 L14 says "not a policy reopening"; 08.47 C3 quotes them as one | Quote each separately |
| C-10 | confirmed [V] | normal | 8 | 08.46 L54 cites "CON-0263's coverage"; the register has CON-0263 INVALID, restated as CON-0265 | Cite CON-0265 |
| C-11 | confirmed [V] | normal | 8 (SEQ) | The 08.00 L67 note stops at §8.45 | Extend it to §8.46–§8.51 |
| C-12 | **modified** [V] | normal | 8 | 08.00 L62 (§8.48) **already has** "(filename kept)". Only L65 (§8.51: filename "ends at root acts", title "a root by stipulation") lacks it | Add the mark at L65 only |
| C-13 | confirmed [V] | normal | 9 | 09.00 L28 lists T9-12, T9-16, T9-17, T9-19 but not T9-20 | Add T9-20 |
| C-14 | confirmed, as Int [R] | normal | 8 | admit/admission in four senses | Qualify each |
| C-15 | confirmed [V] | normal | outline | L188 is dated "Recorded 2026-10-23", but L190–194 read as current | Date the paragraph |
| C-16 | confirmed [V] | normal | 8 | 09.17 L276 cites S0253, and 08.46 L3 does not list it | Add L276 |
| C coverage: Reflection Principle | confirmed MISSING [V] | normal | 8 | S0275 L438–445: the principle "must represent knowledge about its own knowledge structures". S0276 L53 routes it to no row. No hit in the copy for "reflection principle" or "knowledge about its own" (a search miss, not proof of absence) | Treat it at §8.51 |
| C coverage: "every article prevents a collapse"; S0265's nine facets | accepted [R] | normal | 8 | C's inventory comparison | As C proposes |
| B-02 | confirmed [V] | high | 1 (and 6) | 01.16 L112: "without any act on c … the axiom forbids", but a change to d is an act. Map F18 L137 and 09.05 L18 say operating rules need resolution at use: a fourth form | Scope the trichotomy to meaning fixed at the act; name "live, resolved at use (F18)". Severity is high, not F3-critical: F18's decider is "definitional", not the Principal |
| B-01 | **modified**: high → normal [V] | normal | 1 | The countermodel is valid against L194–198 (condition 4 excludes only x's own endings; case 4 at L242). But 01.14 L204–208 marks this formal model as superseded by §9.13 and kept as history. R3: 0 claim-level inbound lines | In the history block, mark properties 2–3 "refuted as stated (premise missing)". Whether CE* has the analogue is **not checked** (open) |
| B-03 | **modified**: high → normal [V] | normal | 1 TODO | TODO L26, L35, L36, L40, L42, L43 are still Open or Awaiting, while §9.13 L26, L34–37 and C1 record the decisions within the kernel | Annotate "decided within the kernel (where, date)" |
| B-04 | confirmed [V] | normal | 1 | 01.14 L208 says "repaired … defect by defect". 09.13 L263–266 has defect 2 propagation open, 3 reasons open, 5 σ open. L510 says "tracked, not repaired" | Defect by defect |
| B-05, B-06, B-07, B-09, B-17, B-18 | accepted [R] (B-07 lines seen [V]) | normal | 1 | B's cited lines | As B proposes |
| B-08 | confirmed [V] | normal | 1 | 01.14 L361 says "partial order", L364 says "the only two-way edge" | "preorder", or restrict to standing flow |
| B-10 | confirmed [V] | normal | 1 | 01.17 L103 against L111 ("absent") | As B proposes |
| B-11 + A-01 (same site, 00.01 L30) | confirmed, merged [V] | normal | 0, 1 | 00.01 L30 uses "well-founded" for a claim and cites §1.17.2, where the word is act-level | One edit: "well-founded (of a claim: §9.18's classes, kinds adopted T9-19)"; keep the §1.17.2 pointer qualified "(of an act)"; add A-01's promise-(b) pointer |
| B-12 | confirmed [V] | normal | 1 | L-RT occurs outside review notes only in 01.16 and 01.99; "brief 4/6" at 01.14 L178, L180 | Replace with the reserved-decision names |
| B-13 | confirmed [V] | normal | 1 | κ at 01.14 L173 and at 02.08 L177 | Rename one |
| B-14 | confirmed [V]: **content missing**, not only a stale pointer | normal | 1 TODO, register | The CON-0014 basis cites "U-DM-1, T1-49". The TODO has T1-48 and T1-50, but no T1-49. U-DM-1 is open in the text (01.17 L22) and has no row. Whether a row once existed cannot be checked (git history forbidden) | Add the T1-49 row (U-DM-1) |
| B-15 | **modified** [V] | normal | 1 TODO | §1.14.5-14 (L502) already gives the two senses, as the register does. Only TODO T1-48 (L74) carries "INVALID (not verifiable)" | Fix the TODO heading only |
| B-16 | **modified** [V] | normal | 1 | S0016 L43 does not name "frozen" and L54 does: confirmed. S0024 L155 supports "architectural" in meaning ("no place in the architecture"); L36 is literal | Add S0016 L54. Add S0024 L36 beside L155; do not replace L155 |
| A-02, A-06, A-07, A-08, A-12, A-14 (+ B's T1-43 note), A-16 | confirmed [V] | normal | 0, 1 | Re-read on the copy: 00.02 L9, 00.07 L33; 01.01 L32–37 and map L132 ("four ways"); 01.02 L19, L49, L53; 01.06 L51 against S0013 L17 (DEFERRED refinement); S0005 L66, L70 against S0002 L36; 03.10 L6; TODO T0-14/T0-16 ("CLOSE" against Open); TARGET-TOC L3, L44 | As A proposes |
| A-15 | confirmed [V] | normal | 1 | 01.13 L58 (144 records = 142 grants + 2) against L79 "144 grants" | Fix L79. Leave 01.99 L9 as a history row; annotate at most |
| A-03, A-04, A-05, A-09, A-10, A-11, A-13 | confirmed (lines seen) [V]/[R] | normal | 0, 1 | 00.06 L5 against 01.09 L68; 00.06 L18; 00.08 L18; 01.07 title and L12 (the splice ". when" confirmed); 01.08 L20; 01.09 L55 | As A proposes |
| A-17 | confirmed; **locator modified** [V] | normal | 1 | The label list is at 01.00 **L25** (L26 is "References form") | Add N-n at L25 |

## 2. R3 (inbound lines per confirmed correction)
The tool resolved §8.31 C1, §8.46 C1, §8.47 C1, §8.38 C1, §8.50 C2 and 1.14.1-8. It did not resolve §1.16.6, (d3), R6 or PL-12, so I used grep on the copy (excluding `review/` and BASELINE).

| Correction | Inbound line | Disposition |
|---|---|---|
| B-02 (§1.16.6) | 06.06 L22 ("must be pinned, live with detection, or live with prohibition") | **needs the same change** (new; B missed it) |
| | 06.06 L43 | needs the same change; B's "affected" is confirmed |
| | 09.05 L18; map L137 (F18) | stay true (they state the fourth form) |
| | 06.06 L40, L82; 00.07 L20; 01.17 L97; 05.06 L31; 08.10 L31; 08.22 L136; 08.23 L135; 09.08 L40; 10.02 L120; 09.06 L26; outline L171; map L237; 01.14 L438 | not affected (uses of pinning only) |
| | 01.99 L17, L21 | history: keep |
| D-01 ((d3), R6) | 09.18 L240 | stays true (it is the target wording) |
| | 10.03 L266, L284; Ch. 10 TODO L18 (T10-13) | stay true |
| | 10.04 L59, L108, L119, L140, L158, L164; 10.05 L143, L249; map L398 | need a different change, depending on Q-A: re-mark them if yes; leave them and revert 09.15 L63 / 09.18 L243 if no |
| C-01 / D-04 (08.31 L117) | 08.32 L22, L66; 08.34 L18 (§8.31 C1, claim-level) | not affected (they concern confidence) |
| | 10.00 L11 "(gap G1)" | needs the same change (D-04 family) |
| D-03 | the lines in its evidence column | need the same annotation |
| | 10.03 L218, L221 | historical: keep |
| D-07 / C-05 (§8.38 C1) | 08.42 L20, L49, L51; Ch. 9 TODO L30; map L418 | stay true, apart from dropping "proposed" before (c2) at L51 and L418 |
| | map L419 | stays true (the Q4 fork stays open) |
| C-02 (§8.47 C1) | 08.47 L16; 08.48 L104; 08.49 L18, L19, L62, L69; 08.50 L19; 08.51 L19, L49 | stay true ("named", three readings) |
| C-04 (§8.46 C1) | 08.47 L16; 08.49 L18, L56, L80; 08.50 L102; 08.51 L42 | not affected |
| | index L60, map L221 "three readings" | need the same change if (d) is adopted ("four") |
| C-07 (§8.50 C2) | 08.51 L19 | not affected |
| | 08.00 L64 | needs the same change |
| | 08.50 L121, 08.99 L75 | history: keep |
| B-01 (1.14.1-8) | 27 section-only lines; none cites properties 2–3 | not affected |
| B-11 / A-01 | 09.07 L123 | needs a different change: qualify "valid (authorized)" |
| | 09.10 L67, L149 | qualifier only |
| | 10.03 L44 | stale locator (L22–25 should be L27); out of scope |
| | 00.06 L14 | not affected |
| A-, B- and C-corrections not listed above | as listed in each report | I did not re-run R3 for them; reviewers' dispositions accepted [R] |

## 3. Conflicts between reviewers
| Pair | Resolution | Reason |
|---|---|---|
| C-01 (high) vs D-04 (normal at 08.31 L117) | high | The cell gives C-4 the status "not a kernel fact" while (c2) is a kept D_S clause: a wrong status. One merged edit |
| C-01's "D_S ∪ D_W" vs D-08 (domain undefined) | write "D_S" | The union is the very ambiguity D-08 names; (c2) is a D_S clause either way |
| C-05 vs D-07 (08.38 L61) | one edit | Scope the note to the guard fork, keep Q4/G7 open, drop "proposed" |
| C-06 vs D (09.18 L221) | one wording at both sites | Same phrase |
| A-01 vs B-11 (00.01 L30) | one edit | A's pointer would otherwise import B's homonym |
| A-14 vs B (T1-43) | A-14, extended to the TODO row | Same missing §3.10 pointer |
| A-15 vs B (01.99 L9) | fix §1.13 L79; keep the history row | Change logs record history |
| D-01 vs D-02 (same Ch. 9 text) | D-01's edit depends on Q-A | The direction of repair depends on the act's scope |
| D-03 vs D-02 (row B of §10.3.3) | annotate admission now; hold row B's enforcer for Q-A | The admission is in the record; the enforcer (W's rule) is not |
| B-18 vs D-06(c) | compatible | Pointing to 10.05 L174's display rule is right under either answer to Q-C |

## 4. Defects in the rotated chapters (confirmed)
- **Ch. 0 (6):** A-01 (merged with B-11), A-02, A-03, A-04, A-05, A-16.
- **Ch. 1, A (11):** A-06 to A-15, A-17.
- **Ch. 1, B (18):** B-01 to B-18 (B-14 also in the register; B-15 only in the TODO).
- **New, found in integration:** no new defect inside Ch. 0 or Ch. 1. Outside them: 06.06 L22 (B-02's R3) and map L399 (D-05, D-06(d)).

## 5. Coverage gaps (reported, not filled)
- Ch. 0/1 claims resting on `docs/knowledge_tranfer/*` sources (V3, AB, IM, CF, AMS, RS, IA, PC, DE, SI, GKC, EP01, GA, TR, DR-AB, PKSM and others): no reviewer checked them, because SCOPE §2.4 forbids it. This touches most of §1.14–§1.17 and parts of §0.2–§0.8 and §1.1–§1.13. B-17's "IM C12" cannot be classed as stale pointer or missing content.
- Ch. 1 TODO rows T1-01, T1-06–T1-11, T1-16, T1-22–T1-30: searched by no one.
- §8.50's S0270 and S0271 citations: not opened by C.
- 10.99 L20–22: read only lightly (C) or by line (D).
- Commit hashes on the L3 lines: not verifiable (git history forbidden).
- Whether CE* has B-01's analogue: not checked.
- **Concepts:** every SCOPE §2.2 concept has a verdict:
  - VALIDATED: CON-0013 and CON-0014 (B), CON-0019 (A);
  - PROVISIONAL, all A: 0016, 0026, 0076, 0163, 0186;
  - INVALID restatements: A or B for all 14.

  **CON-0033** (INVALID, homed partly at §1.14.4 per the register) is not in §2.2. Its restatement has no check.

## 6. References to missing claims (REVIEWER-NOTE item 6)
| Finding | Missing content or stale pointer? | Evidence |
|---|---|---|
| B-14 (T1-49) | content missing | U-DM-1 is open at 01.17 L22, but no TODO row exists |
| A-16 (a) | stale pointer | The content exists at §0.5 |
| C-08 | wrong locator | The phrase is at S0269 L16 |
| 10.03 L44 | stale locator | Out of scope; the target is now 01.17 L27 |
| B-17 (C12) | undecidable | The source is forbidden |

## 7. Access deviations, weighed
- **A.** I re-checked A-02, A-06, A-07, A-08, A-12, A-14, A-15 and A-16 on the copy, plus the sources S0002, S0005 and S0013. All hold. One locator is off by one line (A-17). No finding rests on a working-book read. A's "no old texts" condition is still unmet as process, and A's disclosure is adequate.
- **B.** The discarded run without `--root` has no effect. B-12's "L-RT appears nowhere else" was partly supported by the item-4 lines B saw. I re-established it on the copy without them: outside `review/` and BASELINE, L-RT occurs only in 01.16 and 01.99.
- **C.** The overwritten scratch file was D's R2 output: C saw D's candidate words, not D's judgements. Minor; C's R2 stands. It shows C and D shared a scratch location.
- **D.** Five BASELINE lines were displayed, and nothing was used. No effect.

## 8. The scope of T9-19 and the related decisions (D-01, D-02, D-03, D-06 (c))
- **D-01.**
  - *Record:* the kinds are adopted. The record lists §9.15 G1 and §10.3.6 among the applied sites. It does not state a domain change or a (d3) restatement.
  - *Copy beyond the record:* the two-level domain is in force (09.18 L243; 09.15 L63 "outside D_S"), while Ch. 10 keeps the old (d3).
  - **Q-A:** "Does adopting the three evidence kinds also adopt W's acceptance rule G8 with (c3), and with it §9.18.6's two-level domain (a W-clause breach leaves standing in D_S)? Options: (i) yes, then Ch. 10's (d3), R6, PL-3 and PL-12 are restated; (ii) no, the kinds only, with the rule and the domain statement for review; (iii) the rule yes, the domain statement for review."
- **D-02.**
  - *Record:* the kinds, plus (c2) kept (T9-20). Open: T9-14, G7, the U-case statement.
  - *Copy beyond the record:* D_W as "its own" rule (10.03 L289); the domain restatement (L243). G8 and (c3) stay "proposed", and g1's adoption "stays open" (L209).
  - **Q-A** above covers this.
  - **Q-B:** "Is g1, the derivation rule, adopted with the kinds, or does it stay open? Options: adopted; open until G7."
- **D-03.**
  - *Record:* "Candidate 2 admitted for review (not yet reviewed)". The copy's T9-19 sites match this.
  - The stale sites are leftovers and need an edit, not a question. The one exception is row B's enforcer, which waits for Q-A.
- **D-06 (c).**
  - *Record:* F is "shown as 'not recognized as current authority', never 'refuted'", and the evidence classes keep "refuted". 10.05 L174 applies this faithfully; the copy treats nothing as decided beyond the record.
  - **Q-C:** "Does 'never refuted' govern only the shown name of F, or also its definition 'refuted, or not recognized' in §9.13 R2a and its eight restatements? Options: display only (the definition stays); or rename in the definition too, e.g. 'ended or not recognized'."
- **T10-21, item (3):** the record keeps "Standing" open with T10-15, so 09.18 L302 needs an edit (D-06(b)), not a question.

## Files opened, commands and searches
**Disclosure first.** One call, `python3 -I tmp/tools/inbound_citations.py --help`, ran **without `--root`**, so on the working book's default root. It returned "claim 0, concept 0, section-only 0" and displayed no book text. Every later tool call passed `--root <copy>`.

A `ls` of the copy's manuscript displayed the file names BASELINE-REVISIONS.md and TODO-TRIAGE-S0100.md. Two `grep -rl` runs for "L-RT" displayed file **names** (one truncated) under the copy's `chapter_*/review/`, plus a count of 2. I opened none of these files.

No working chapter, map, register or outline was opened. Nothing in `00-access/`, no git history, and no file in the review folder beyond those allowed.

**Files opened**
- **Review folder:** INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md, SCOPE.md, REPORT-A.md, REPORT-B.md, REPORT-C.md, REPORT-D.md.
- **Methodology:** `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- **Copy, by line (sed or grep):**
  - **Ch. 8:** 08.00 L51–52, L60–67; 08.31 L117; 08.35 L59; 08.36 L57; 08.38 L27, L55, L61; 08.46 L1, L3, L30–42, L54; 08.47 L1, L30–52, L78–82, L105–110; 08.48 L1, L8, L116; 08.49 L1; 08.50 L1, L20, L27, L49–57, L63, L68, L121; 08.51 L1, L36, L69–77, L112.
  - **Ch. 9:** 09.00 L28; 09.13 L26, L34–38, L208, L258–268, L327; 09.15 L63; 09.17 L276; 09.18 L3, L24, L26, L28, L45, L55, L76–77, L105, L107, L123, L132, L138, L179, L209, L221–222, L227–243, L269, L286, L295, L300–305; Ch. 9 TODO L26, L29, L30.
  - **Ch. 10:** 10.00 L8, L11; 10.02 L9, L120, L208, L214, L225; 10.03 L73–319 (selected lines); 10.04 L3, L6, L16, L59, L108, L113, L126, L140, L164; 10.05 L28, L33, L139, L143, L174, L249, L253, L281, L291, L294, L360; Ch. 10 TODO L13, L26.
  - **Ch. 0:** 00.01 L30; 00.02 L9; 00.06 L5, L18; 00.07 L20, L33; 00.08 L18; Ch. 0 TODO (grep).
  - **Ch. 1:** 01.00 L25–26; 01.01 L32–37; 01.02 L19, L49, L53; 01.06 L51; 01.07 L1, L12; 01.08 L20; 01.09 L55, L68, L87; 01.13 L56–60, L79; 01.14 L173, L178, L180, L190–214, L228–244, L303, L311–316, L361–365, L502, L510, L522; 01.16 L49, L104–118; 01.17 L9–14, L22, L27, L97, L103, L111, L115, L120; 01.99 L9; Ch. 1 TODO L24–26, L35–36, L40, L42–43, L69, L74, L76 and grep.
  - **Other chapters:** 03.10 (grep); 06.06 L22, L40, L43, L82; 08.10 L31; 08.22 L136; 08.23 L135; 09.05 L18; 09.08 L40; 05.06 L31.
  - **Book-level files:** TARGET-TOC L3, L44; BOOK-OUTLINE L188–206; THEORY-MAP L132, L137, L221–226, L396–402, L418–419; CONCEPT-REGISTER rows (awk) CON-0014, 0015, 0263, 0265, and every row homed in Ch. 0/1.
- **Corpus (allowed list):** CORPUS-ALLOWED.tsv (grep); S0002 L36; S0005 L66, L70; S0013 L17; S0016 L43, L54; S0024 L36, L155; S0254 L133, L137; S0255 L85–87; S0257 L14; S0261 L14; S0269 L16, L27, L196–197 (grep "advisory"); S0275 L438–454; S0276 L53.
- **Scratch:** the scratchpad file `integration-r3a.txt`, outside the repository.

**Commands**
- `ls` of the review folder, the copy and `W/tmp/tools`.
- `register_home_check.py --root <copy>` (1 flag, CON-0133, out of scope).
- `inbound_citations.py --help` (without root; see the disclosure above).
- `inbound_citations.py --root <copy>` on "§1.16.6", "1.14.1-8", "§8.31 C1", "§8.47 C1", "§8.46 C1", "§8.38 C1", "§8.50 C2".
- A shell loop checking the Ch. 0/1 index rows against their files.
- `awk` on the register.
- `python3 -I` one-liners to print the context around a phrase in a line.

**Grep searches on the copy**
- "(d3)"; "R6"; "1.16.6"; "conditional on G1"; "refuted, or not recognized" / "refuted or not recognized"; "T1-49"; "U-DM-1"; "L-RT" (names only); "brief 4/6"; "reserved decision".
- "single kernel predicate"; "N-n" and "label"; "knowledge states"; "four meanings now align"; "Where I disagree"; "Zero feedback"; "authority is part of the theory"; "L167–177" and "L231–243"; "T0-14" and "T0-16"; "T1-43" and "F8" in 03.10.
- "S0281" and "W-08" in 08.48; "two wrong" and "two exact"; "CON-0263"; "kept" and "W-09" in 08.51; "reflect", "reflection principle" and "knowledge about its own"; "prevents a collapse"; "κ" in 02.08; "CON-0015"; "monoton" and "consum" in 09.13; "R2a".
