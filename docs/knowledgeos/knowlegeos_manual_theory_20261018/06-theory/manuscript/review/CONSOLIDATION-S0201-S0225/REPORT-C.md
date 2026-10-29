CONSOLIDATION S0201–S0225: REPORT C

Reviewer C. Object: the frozen copy `06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/BOOK-UNDER-REVIEW/` only. Rulebook v1.4 §12. Consolidation-review mode.

## Summary (15 lines)

1. I read §8.31–§8.38 in full and checked them against S0201–S0225. I verified about 120 cited source lines one by one. A local script then compared 241 quoted strings with their cited lines: 210 match. The 31 misses are diagrams split across lines, paraphrases, and one wording slip (Appendix A1). I found no misattributed source and no invented quote.
2. The derivations I checked hold within their stated scope: §8.32 C5 (the order), §8.33 C1 and C4, §8.34 C1 (d)–(e), §8.35 C4 (ii), §8.36 C3 and §8.38 C1's proof table. They rest on the book's own decisions (C1 = constitute, unordered ties, citations of earlier entries only), and they mark proposed premises ((c1)/(c2), §9.18's kinds) as proposed.
3. No finding is critical. The largest risk is bookkeeping, not theory. Two choices for the Principal and two forks named in §8.36–§8.38 do not reach THEORY-MAP (F-01).
4. Other defects: §8.37 C2 says "three axes" where its own mapping gives two (F-02). Ch. 8's reading-order note is stale for §8.31–§8.38 (F-03). THEORY-MAP's unresolved count mismatch is explained by S0208, which was read in order at C40 (F-04). The rotation section has a stale claim about Ch. 1 quote failures and no row for Ch. 10 (F-05, F-06). The G7 row says an evidence entry has "polarity and producer only", which §9.18 contradicts, and §9.18's own G7 lines do not carry the row's extension (F-07).
5. Coverage: most inventory items are COVERED. Ranked misses: S0217's "authority … dependent on the validity of that chain" (L384), the first statement in this run that sets authority from content; S0202 D's method "let EKS/PKS/AIP evidence eliminate candidates, not merely confirm" (L1830), a validation method, which is Ch. 8's announced subject; and S0201/S0203's anti-fallback and fail-closed evidence (S0201 L27).
6. Concept verdicts: none. Per SCOPE §2.2, §8.31–§8.38 home no concept, and the G7 row homes none.
7. R1: 1 flag (CON-0133, H4, Ch. 6; out of scope). R2: 111 in-scope candidates judged. Two are defects: R-2 is unglossed, and "whole-or-nothing" is defined nowhere.
8. Mechanical quote check: `corpus_quote_check.py` finds 0 quotes in §8.31–§8.38, because these sections cite as "(Snnnn Lx)", not "[Snnnn Lx]". The tool therefore checks nothing in my scope (F-08). My own script is a substitute, not a replacement.
9. Process note: I ran `--help` on three tools before setting `--root`. They then ran against the working lane (see the end of the report). I opened no working file myself.

Process independence: I wrote neither the sources nor the book. I saw no other reviewer's report and no window settlement or review file except the eight inventories SCOPE §2.3 names. This independence is of process, not of person.

## Findings

| ID | Section | Finding | Severity | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| F-01 | THEORY-MAP (open forks, F-table) vs §8.36.3, §8.37 C1, §8.38 C1 | **Omission.** Forks and Principal choices named in the sections are not in the map: (a) "supported, warrant not recorded" as a class, or a warrant field (§8.37 C1); (b) a guard "adoption cites its warrant / an identified reliable source", called "a choice for the Principal" against the proposed (c2) (§8.37 C1 (ii); §8.38 C1 (ii)); (c) whether Q4's grading rule takes source kind or reliability as input (§8.38 C1); (d) produce vs test reasoning (§8.36.3); (e) a home for conflicts between non-rule items (§8.36.3; §8.37 C4). | high | `book_search` on the copy for "warrant field", "adoption cites", "produce vs test", "non-rule items": hits in §8.35–§8.38 only, "map lines []". The map's open-fork list stops at C19–C21 (L394–402). *A search miss is not absence; the TODO files were not searched separately.* | Add (a)–(e) as fork rows or open forks in THEORY-MAP, and mark (b) as needing a Principal decision. | §8.37 C1: no claim-level inbound; section-only lines in §8.38 L22, 115, 126, 142, 148; 08.99 L57–58; MAP L212. Disposition: unaffected; MAP L212 and L213 gain a pointer to the new rows. §8.38 C1: MAP L213, L388; 08.99 L57, 59; §8.37 L71. Unaffected. | The rows exist in the map, each with its home and its decision-maker. |
| F-02 | §8.37 C2 (heading, plain language 2) and its index row ("five values on three axes") | **Defect (internal).** The lens of §8.36 C4 has three axes: authority, evidence and content. §8.37 C2's own mapping puts VALIDATED and INVALID on the argument (content), mapped to evidence polarity; UNKNOWN and CONFLICTED on the evidence side; QUESTIONABLE as a grade; and says "None is U", the authority value. So the five values span at most two of the lens's three axes, unless "grade" is a third axis, which the section does not say. | normal | §8.37 L49 and L56–61; §8.36 C4 L80–84; index L51. | Restate as "five values on two axes (content and evidence) and a grade; none is U", or name the three axes explicitly. | §8.37 C2: claim-level 08.99 L57 (log, history: keep); section-only §8.38 L22, 115, 126, 142, 148 (not about the axes: unaffected); MAP L212 (does not state the count: unaffected). Index row 8.37: update the wording. | The heading, plain language and index row agree with the mapping. |
| F-03 | 08.00-index note (L54); SEQ of Ch. 8 | **SEQ, stale.** The note lists the Ch. 9 constructs used "§8.18–§8.30" and says to read them with §9.13 and §9.15–§9.17. §8.31–§8.38 also use §9.10 (Q2, Q4, Q5, O2), §9.18 (g0, (c1)–(c5), check 33, T9-12, T9-16, T9-17) and Ch. 10 (promise (b), PL-0–PL-14, §10.2 candidates, G1, G7, G10, T10-xx), all of which come after Ch. 8 in reading order. | normal | §8.31 L22–27; §8.36 L20–25; §8.38 L16–23. | Extend the note to "§8.18–§8.38" and add §9.10, §9.18, §10.1, §10.2 and §10.5 to "read with". | No claim cites the note. | The note names every later construct that §8.31–§8.38 use. |
| F-04 | THEORY-MAP L389 (counts) | **Stale list; the pointer is stale, no content is missing.** The note "reported as 12 … (13 names; the tool's count and list disagree, noted, not resolved)" has a plausible cause: S0208 is in the "consulted in branches only" list, but it was read in order in window C40 (§8.33 header: "S0208 had been consulted in a branch before"). So 12 is right and the list is stale by one name. *Interpretation; I did not run `close_source.py`.* | normal | MAP L389; §8.33 L3. Allowed list: 218 IDs in S0001–S0225, which matches "read in order 218". | Drop S0208 from the branches-only list, after confirming with `close_source.py --position`. | No claim cites L389. | The list and the count agree. |
| F-05 | BOOK-OUTLINE rotation section L204 | **Stale text.** "Ch. 1 has six [inherited quote failures] (in §1.2, §1.6, §1.8, §1.11)". Ch. 1's rotation is marked done (S0125). On the copy, `corpus_quote_check.py` reports §1.2 7/0, §1.6 3/0, §1.8 9/0 and §1.11 6/0 (found/not found). | normal | Command output (see the end of the report). | Mark the six as worked through at the Ch. 1 rotation, or name where they remain. | none | The sentence matches the tool's output. |
| F-06 | BOOK-OUTLINE rotation table | **Omission.** Ch. 10 (opened 2026-10-29 by the strategic-design turns) is not in the rotation, and there is no row after S0225. The cycle statement "each chapter … about once per 100–150 sources" has no next entry. | normal | Rotation table, L192–202. | Add Ch. 10, and the next consolidation's chapters, to the table. | none | The table names the next rotation. |
| F-07 | §10.02 G7 row (C43 W-08 extension); §8.38 C1 L52 | **Defect (scope of "only"); inconsistency with §9.18.** The G7 row says "an evidence entry has polarity and producer only", and §8.38 C1 says "about, polarity and producer only". §9.18 L110 gives the fields as actor (producer), cited bases, about, polarity, scope and act time. The point that **no source-kind field** exists stands. Separately, §9.18 L81 and L298 still give G7 as "the vocabulary against F13 and Q4's grade", without the source-kind extension. | normal | §9.18 L110; §10.02 L218; §8.38 L52; §9.18 L81, L298. | Replace "only" with "and no source-kind field" in both places. Carry the extension to §9.18's G7 lines, or point to §10.2. | "G7" in R3: 0 matches (the tool does not take gap IDs). Manual grep: §9.18 L81, L298 (update: the pointer is stale); §10.03 L115 (unaffected); §10.02 L114 (unaffected); §8.35 L71 (no "only": unaffected); §8.36 L96 (consistent); 10.99 L16 (log: keep). | The field list matches §9.18, and §9.18's G7 lines mention source kind. |
| F-08 | Tooling (quote check) | **Limit.** `corpus_quote_check.py` matches only "[Snnnn Lx]". Every citation in §8.31–§8.38 has the form "(Snnnn Lx)", so the tool reports "0 found, 0 not found" for all eight sections. Their quotes are not mechanically checked. | normal | Command output. | Extend the tool to the "(Snnnn Lx)" form, or state the limit in the consolidation record. | — | The tool checks §8.31–§8.38's quotes. |
| F-09 | §8.31.3 (S0203 "relabels C-8 'Not kernel' (L752)", listed as a flattening) | **Overstated (distortion charge).** S0201 itself classes C-8 as DOMAIN-CONTEXT-CANDIDATE, not a kernel candidate (L448, L455). S0203 L750–752 lists it under "Not kernel" as "currently PKS/domain concern". That agrees with S0201's classification. The other flattenings listed (all eight called "strong", L728; the UNK-02 lean, L175) are verified. | normal | S0201 L448, L454–455; S0203 L726–754. | Drop the C-8 item from the list, or restate it as "coarser, not contrary". | "§8.31.3" in R3: no inbound. | — |
| F-10 | §8.35 C3 / §8.37 C1 (relay) | **Omission (coverage, rank 1).** S0217's H-KOS-Inference-001 says "its authority is dependent on the validity of that chain" (S0217 L384). This is the first statement in C42–C45 that sets *authority* from content validity, the reading §8.37 C1 (i) and §8.38 C1 (i) show breaks A1. §8.35 C3 quotes L381 only. §8.37 C1's relay starts at S0220. | normal | S0217 L376–384; §8.35 L66–68; §8.37 L46. | Add to §8.35 C3 (or to §8.37 C1's relay) that S0217 L384 is reading (i), a value function, and is contrary to A1. | §8.37 C1: as in F-01 (unaffected). | The relay starts at S0217. |
| F-11 | §8.31 (coverage, ranks 2–3) | **Omission.** (a) S0202 D: "we should let EKS/PKS/AIP evidence eliminate candidates, not merely confirm the research" (L1830). This is a falsificationist validation method, Ch. 8's subject. (b) S0201's anti-fallback: "The anti-fallback design of the current systems CONTRADICTS CAPPI's fallback-chain idea", refusal of UNRESOLVABLE, fail-closed CAP-001 (L27), with S0203 L466–518. This is estate evidence for §8.34 C1 (b)/(c) (refusal rather than fallback), and it is cited nowhere. | normal | S0202 L1830; S0201 L23, L27; `book_search`: "eliminate candidates", "fallback chain", "numeric stack" give 0 hits. | Record (a) in §8.31.3 as a candidate method (one lineage). Add (b) as within-lineage agreement in §8.34 C1 or §8.31.3. | §8.31 C1: 3 claim-level inbound lines (§8.32 L22, L66; §8.34 L18), unaffected. §8.34 C1: no claim-level inbound. | — |

*Appendix (lower priority):*
- **A1.** §8.31 C3 quotes "UNKNOWN whether a richer temporal model is needed" with "S0201 L322". That wording is S0203's (L670). S0201 L322 reads "UNKNOWN whether KnowledgeOS needs a richer temporal model". Same meaning; fix the attribution of the wording.
- **A2.** §8.34 has "Not a new concept." twice (L110, L112).
- **A3.** The §8.38 filename keeps "four-axes" and "three-meanings", while the restated title says "several". The index row does not flag "filename kept", as the §8.25 row does.
- **A4.** §8.34 C4 cites S0212 L515 for "Publication"; it is L514.
- **A5.** §8.35 C4 quotes "no belief kept without a reason"; S0215 L40 has "no belief is kept without a reason".

## Concept verdicts

| ID | Verdict | Reason |
|---|---|---|
| — | none | SCOPE §2.2: every concept in scope is homed in Ch. 9 (reviewers A and B). §8.31–§8.38 and the G7 row home none. Concepts my sections cite (CON-0141, 0188, 0221, 0134, 0212, 0261) were not judged. |

## Coverage against the inventories (C38–C45)

| Item | Status | Home |
|---|---|---|
| C38: confidence ≠ authority partly by absence (X-03); A vs C grades | COVERED | §8.31 C1 |
| C38: four-way absence distinction; artificial UNKNOWN | COVERED | §8.31 C2 |
| C38: 2/218, shared engine; time semantics ≠ bitemporal storage | COVERED | §8.31 C3 |
| C38: B's "two independent streams" vs D's "one corpus"; S0203 "independently converge" | COVERED | §8.31 C4 |
| C38: mechanism ≠ invariant; mechanism vs policy; projection | COVERED | §8.31 C5 |
| C38: C-1..C-10, AMENDMENT 6, the R-1 precondition | COVERED | §8.31 C6 |
| C38: H-08; structural ≠ semantic; DDD and ES disagreements; source defects; S0204 status layers; non-collapse principle | COVERED | §8.31.3 |
| C38: S0203's flattening of C-8 | DISTORTED (overstated; F-09) | §8.31.3 |
| C38: D "evidence eliminates candidates" | MISSING (rank 2) | — |
| C38: anti-fallback, CAPPI numeric stack rejected | MISSING (rank 3) | — |
| C38: Claim stage absent (UNK-01); PKS four orthogonal axes; S0204 "no premature unification", "BCs never from folders" | MISSING (low) | — |
| C38: S0202's outside literature and conflict-preservation research | JUSTIFIABLY EXCLUDED (T10-03) | §8.31.3 |
| C39: two UNKNOWN AuthorizationFacts; three-vs-two slip | COVERED | §8.32 C1 |
| C39: transitions(evidence) ≠ grants; Inv G | COVERED | §8.32 C2 |
| C39: record refuses, world assumed; structural vs stated | COVERED | §8.32 C3 |
| C39: 210/216/218, E-1, unattestable | COVERED | §8.32 C4 |
| C39: method order; S_{t+1}; continuity; lattice slip; trivial K=(X,T,I); ranking change; sheaf | COVERED | §8.32 C5, §8.32.3 |
| C39: expression ≠ meaning; INV-KOS-002 undecidable; IRM homonym | COVERED | §8.32 C6 |
| C39: S0205's temporal model vs EKS untimed (inventory: "contradiction") | MISSING (rank 4; partly §8.31 C3) | — |
| C39: S0205 kernel-primitive list (8 items), category-theory lens; S0206 "no runtime, mechanisms" | MISSING (low) / lens JUSTIFIABLY EXCLUDED | — |
| C40: open-world UNKNOWN; H-ZERO-001; merged mapping 004 | COVERED | §8.33 C1 |
| C40: "kernel calculates authority" rejected | COVERED | §8.33 C2 |
| C40: prompt-prescribed outcomes; convergence of one line | COVERED | §8.33 C3 |
| C40: dimension independence, three readings | COVERED | §8.33 C4 |
| C40: invariant as protection; detection dual | COVERED | §8.33 C5 |
| C40: SHALL ladder, grade inconsistency | COVERED | §8.33 C6 |
| C40: collapses; origin grading inconsistency; provenance overclaim; f0106657 | COVERED | §8.33.3 |
| C40: S0209 promotion ladder; "kernel as consequence of invariants" | MISSING (low; near §8.30 C5) | — |
| C41: failure preserves last trusted state | COVERED | §8.34 C1 |
| C41: transitions ≠ grants → Pramāṇa ≠ Pramā relay | COVERED | §8.34 C2 |
| C41: meaning ≠ valid knowledge; pipeline tension | COVERED | §8.34 C3 |
| C41: canonical is historical; publication tension | COVERED | §8.34 C4 |
| C41: structural enforcement; "does not store answers" | COVERED | §8.34 C5 |
| C41: collection complete; INV numbering | COVERED | §8.34 C6 |
| C41: carry-forward sentence (promise (c)) | COVERED | §8.34 C7 |
| C41: kernel lists, pipeline variants, "adopted" contradiction, INV-KOS-007 | COVERED | §8.34.3 |
| C41: S0212 three boundaries; S0213 five/six lens count | MISSING (low) | — |
| C42: Revisability; terminal states | COVERED | §8.35 C1 |
| C42: Decision-001 | COVERED | §8.35 C2 |
| C42: Inference-001 "authority dependent on validity of the chain" (S0217 L384) | MISSING (rank 1; F-10) | — |
| C42: contradiction, acyclicity, cognitive worlds | COVERED | §8.35 C4 |
| C42: A→B position move; "KnowledgeOS" homonym | COVERED | §8.35 C5 |
| C42: three "sixes", grade drift, verbatim duplicate | COVERED | §8.35 C6 |
| C42: core-question change | COVERED | §8.35 C7 |
| C42: AGM gloss errors, link mismatches | JUSTIFIABLY EXCLUDED (outside literature) | §8.35.3 |
| C43: validation boundary; acceptor | COVERED | §8.36 C1 |
| C43: TARKA-005 non sequitur | COVERED | §8.36 C2 |
| C43: circularity, regress | COVERED | §8.36 C3 |
| C43: Unknown + CONFLICTED | COVERED | §8.36 C4 |
| C43: Agent-001 correction; source kinds | COVERED | §8.36 C5 |
| C43: "written in stone"; dropped candidates; CI 0.510 | COVERED | §8.36 C6 (CI recomputed: Clopper–Pearson 0.6915, Wilson 0.7225) |
| C43: produce vs test | COVERED (as a fork; not in the map, F-01) | §8.36.3 |
| C43: coexistence (S0218) vs elimination (S0219) not reconciled; S0219 fallacy-list conflation | MISSING (low) / source defect JUSTIFIABLY EXCLUDED | — |
| C44: Vyāpti status rule | COVERED | §8.37 C1 |
| C44: verdict engine, five values | COVERED (axis count wrong, F-02) | §8.37 C2 |
| C44: relations; avacchedaka | COVERED | §8.37 C3 |
| C44: conflict space; satpratipakṣa | COVERED | §8.37 C4 |
| C44: pseudo-evidence | COVERED | §8.37 C5 |
| C44: relays, technology, "truth emerges" | COVERED | §8.37 C6 |
| C44: abhāva, sign conditions defect, definitional precision, intent, tādātmya, source slips; order anomaly | COVERED | §8.37.3, lineage |
| C45: reliability; registry; EPI-002 | COVERED | §8.38 C1 |
| C45: confidence state machine | COVERED | §8.38 C2 |
| C45: trust by default; tension with items 3 and 7 | COVERED | §8.38 C3 |
| C45: commentary and meaning lineage | COVERED | §8.38 C4 |
| C45: "authority" homonym; three kernels | COVERED | §8.38 C5 |
| C45: context-bounded meaning | COVERED | §8.38 C6 |
| C45: analogy dropped; "first/second book" | COVERED | §8.38 C7, lineage |
| C45: meaning before knowledge; śabda in two layers | COVERED | §8.38 C8 |
| C45: two record shapes; pramāṇābhāsa; grammar engine | COVERED | §8.38.3 |

**Ranked misses:**
1. S0217 L384 (F-10).
2. S0202 L1830 (F-11a).
3. S0201 L27 / S0203 anti-fallback (F-11b).
4. S0205's temporal model against untimed EKS.
5. All others are low.

New fork or topic the map lacks: F-01 (a)–(e).

## R2 (`undefined_terms.py --root <copy> --scope 08.31-08.38`; 111 in scope)

| Word | Judgement |
|---|---|
| R-2 (B) | **Defect:** used at §8.32 L10 without a gloss (R-1 is glossed in §8.31). Gloss: "S0206's second completion pass, 2026-08-22 (S0206 L1595)". |
| whole-or-nothing (D) | **Defect:** 8 uses, defined nowhere; it carries two referents (a revocation's scope is not read, §8.35 C4; ω via covers, §8.37 C3). Define it once at §9.13 Θ_f ω and cite that. |
| X-2 (B) | Glossed in place ("the six-role against four-role contradiction"). OK. |
| venue, revocable (A/D) | Ordinary English; "revocable" from the act kind "revoke" (§9.13). OK. |
| expression (C/D) | The source's term, glossed by "expression ≠ meaning" (§8.32 C6). OK. |
| warrant | Glossed (§8.37 plain language 1; C1 quotes S0223's "warranting relation"). OK. |
| avacchedaka | Glossed "delimiting conditions" (§8.37 C3). OK. |
| produce-vs-test | Named fork, glossed in §8.36.3. OK (see F-01). |
| evidence-labelled | Glossed in context (§8.32 C5 (a)). OK. |
| collapse | Explicitly the estate's term: "the book has no definition" (§8.33.3). OK as flagged. |
| lense (lens) | Glossed: lenses are never a reason (§8.33 lineage). OK. |
| reliability, registry, synthesi(s), pipeline, compute, workflow | Source terms, glossed where used (§8.38 C1, C2; §8.34 C1; §8.33 C2). OK. |
| annulled, revoked, merged, child | Ch. 9 terms (act kinds; merged record §9.13.2; child grant, defect 2). Pointers present. OK. |
| agent, person, truth, honest, coupling | Homes in §10.2, §1.13.1, §9.10 Q1, §10.1 (promise (b)) and §9.13 Θ_f P respectively. OK. |
| seventh, eight, fourth | Counts verified: lifecycle vocabularies 4 + 2 + 1 = 7 (§8.36.3, §8.37.3, §8.38 C2); S0201 eight candidates (L454); S0206 fourth sense (L342). OK. |
| span | Correct as a word; the claim it heads is wrong (F-02). |
| sequitur, modal, circularity, circular, recovery | Ordinary or technical-ordinary (non sequitur; modal verbs; S0219's forms are listed). OK. |
| inquiry, motto, outside-research, calculated, degree, philosophy, investigate, investigation, knowing, collection, overwrite, mathematic, wait, weaken, continue, deleted, denied, plausible, traceability, restatement, saying, heading, reproduce, contradicted, measure, warn, accepting, narrower, completion, material, strongest, derive, recommend, drift, legitimate, onto, weaker, completed, opposite, verbatim, asked, challenge, contradict, narrow, stronger, drop, dropped, action, asserted, evaluate, admit, comparison, declare, violation, body, next, together, quoted, again, remain, beside, contradiction, separation, example, agree, once, above, rival, reviewer, start, because, keep | Ordinary English in context; none carries a technical sense the reader lacks. No defect. |
| Also used in scope, first used elsewhere (information only): annulled, annulling, sequitur, C-1, AP-1, H-08, C-2, H-02, H-04, R-1, C-3, C-10, C-4 | Glossed in §8.31 where used, except H-02, which is used as "the H-02 status" without a gloss in §8.31 C1. Minor. |

## Coverage of sections

| Section | Read in full? |
|---|---|
| 08.00-index.md (whole; rows 8.31–8.38 checked) | yes |
| 08.31–08.38 | yes, each whole |
| 10.02 G7 row (and the gaps table, L208–221) | yes; the rest of 10.02 not read |
| BOOK-OUTLINE.md, rotation section (L186–205) | yes |
| THEORY-MAP.md: C38–C45 lines (L206–213), Position and counts (L380–392), plus grep hits | yes; the rest not read |
| Ch. 9 / Ch. 1 / Ch. 3 / §10.5 | excerpts only, where my sections cite them (§9.10 L33–35, 41–72, 93; §9.13 L26, 52, 62, 85, 104–106, 166, 194, 206; §9.18 L106–131; §1.2 L17–19; §1.6 L13, 35–46; §3.1 L36–40; §10.5 PL-2) |

**Not covered:** I did not verify every source line. About 120 were checked by hand, and 241 quotes were checked by my script; unchecked lines are mostly coverage bullets. I did not re-run any model check (check 33, 15b, and so on). I did not run `close_source.py`. Limits: a script match is a string test, not proof of meaning, and a search miss is not absence.

## Files opened (all read-only)

- `…/06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/REVIEWER-NOTE.md`, `SCOPE.md`, `COMMISSION-C.md`
- `…/03-methodology/REVIEWER-INSTRUCTIONS.md`
- Copy (`BOOK-UNDER-REVIEW/06-theory/…`):
  - `manuscript/chapter_08/08.00-index.md`, `08.31`–`08.38` (full);
  - `manuscript/chapter_10/10.02-bounded-contexts.md` (L114, L208–221);
  - `manuscript/BOOK-OUTLINE.md` (L186–206);
  - `THEORY-MAP.md` (L378–402 and grep lines);
  - `CONCEPT-REGISTER.tsv` (status count by script);
  - excerpts of `chapter_09/09.10`, `09.13`, `09.18`, `chapter_01/01.02`, `01.06`, `chapter_03/03.01`, `chapter_10/10.05`;
  - tool output lines from `08.99-change-log.md` and `10.99-change-log.md`.
- Inventories: `…/06-theory/manuscript/review/C38-S0201-S0204/` … `C45-S0224-S0225/STEP1-INVENTORY.md` (8 files).
- Tools read, not run: the headers of `undefined_terms.py`, `register_home_check.py`, `inbound_citations.py` and `corpus_quote_check.py` in `…/tmp/tools/`.
- Corpus, all on `CORPUS-ALLOWED.tsv` (also read): S0201, S0202, S0203, S0204, S0205, S0206, S0207, S0208, S0209, S0210, S0211, S0212, S0213, S0214, S0215, S0216, S0217, S0218, S0219, S0220, S0221, S0222, S0223, S0224, S0225. These were cited lines and ranges only, plus the `diff` of S0216 L474–816 against S0217 L1–343 (identical), and the `diff` of S0213 L1–600 against S0214.
- None of S0155, S0156, S0157, S0161, S0170 or S0174 was opened. Nothing in `00-access/` was opened.

**REVIEWER-NOTE item 4 disclosure:** I opened none of the working files directly. But my first `--help` calls to `register_home_check.py`, `undefined_terms.py` and `inbound_citations.py` ran without `--root`, so those tools read the working lane's `06-theory/` (working register and chapters). I saw only their output: one CON-0133 line and a tier-A word list. I relied on none of it. `corpus_quote_check.py` reads `W/02-FILES.jsonl` internally. Out-of-scope note (one line): S0082 is also absent from the allowed list but is not named in MAP L388.

## Commands and searches run

- `ls` and `wc -l` on the consolidation folder and the copy's chapter_08; `find` on the copy (depth 4).
- `cat -n` on the 8 sections and the index; `cat` on the 8 inventories.
- `awk`/`grep`/`sed` on `CORPUS-ALLOWED.tsv` (paths for S0128, S0187, S0198, S0201–S0225, S0234; count of S0001–S0225 = 218; missing IDs).
- `awk`/`sed` line extraction from S0201–S0225 at the cited lines.
- `grep` in sources: "UNRESOLVABLE" (S0206); "richer" (S0201, S0203); "eliminat" (S0202); "fallback" (S0201); "Publication" (S0212); "strong" (S0207); "f0106657" (S0211).
- `diff` S0216 L474–816 vs S0217 L1–343 → IDENTICAL; `diff` S0213 L1–600 vs S0214 → 163 differing lines.
- Python: a Clopper–Pearson and Wilson lower bound for 10/10 (0.6915, 0.7225); a register status count (265: 52 / 117 / 96); my own quote-vs-line check over §8.31–§8.38 (241 checked, 210 matched, 31 listed and inspected).
- Tools:
  - `--help` on four tools (see the disclosure above);
  - `undefined_terms.py --root <copy> --scope 08.31-08.38,10.02` and `--scope 08.31-08.38` (output saved to the scratchpad);
  - `register_home_check.py --root <copy>`;
  - `corpus_quote_check.py` on the copy's 08.31–08.38 and on 01.02, 01.06, 01.08, 01.11;
  - `inbound_citations.py --root <copy>` for "§8.37 C2", "§8.37 C1", "§8.38 C1", "§8.36 C4", "§8.31 C1", "§8.34 C1", "G7", "§8.31.3";
  - `<copy>/tmp/tools/book_search.py`: "--help", "warrant field", "adoption cites", "produce vs test", "non-rule items", "source kind", "eliminate candidates", "fallback chain", "numeric stack", "scalar quality score", "premature unification".
- Copy greps:
  - "state machine|created to|direct edge" (§1.2);
  - "correctness|two keys" (§1.6);
  - "Tacit" (§3.1);
  - "same time|unordered|tie" (§9.13);
  - "PL-2" (§10.5);
  - "polarity…producer" (copy-wide);
  - "G7" (copy-wide);
  - "rotation" (outline);
  - position, count and window keywords (map);
  - "warrant|produce.vs.test|non-rule|source kind|grading rule|central question|supersession" (map);
  - keyword counts in 08.31–08.38 (eliminate, CAPPI, fallback, Bayesian, premature, KnowledgeObject, orthogonal, "effective time", "Claim"), plus one failed `grep -o` run (regex complexity error).
- A first attempt to write a helper script to the scratchpad was denied; nothing was written in the repository. No edits, no commits, no outside service.
