C89 REVIEW REPORT

I am window reviewer W (Opus), doing a transition review of §8.82 (draft) and its index row. Before starting I re-read my saved step-1 inventory; its sha256 is 466d4b6e…fdb92 and matches SHA256-STEP1.txt. Independence here means process independence only: I am a subagent of the commissioning session.

## Summary (10 lines)
1. Verdicts: C1 and C3 are PROVISIONAL, C2 PROVISIONAL (the Ded is repairable), C4 PROVISIONAL (the quantifier is wrong in the plain language and the index row), C5 PROVISIONAL (the order of steps is distorted).
2. Largest risk: C3's "agreement with §8.81 C2" brings back a generalization that §8.79.5 and §8.81.5 withdrew (W-01).
3. C1 claims as AST-019's own something the book already has. §8.75 C1 recorded the commissioned design with an open `HumanDecisionPort`, and §8.77 C6 made "START executed by the capability" the norm. The new fact is that the port is filled by a CLI argument (W-02).
4. C2's Ded uses one premise that cannot be shown: that the bars list "does not contain" a label nobody knows. The conclusion follows without it (W-03).
5. The draft misses a stronger counterexample. As specified, AST-019's own eligibility check would admit AST-019's producer, which held no lane and was UNRESOLVED (W-04).
6. The draft also misses that AST-019 was built on this work item after STOP seq 9, with no CONTINUATION and no entry. The gate that C3 applies to the reviewer did not bind the producer (W-07).
7. C5 leaves out the sources' own order, "verify AST-019 → then the review" (S0353 L124–125; S0354 L25). The defect is S0355's sequence, which has no verification step for AST-019 (W-06).
8. Quote check, done with my own script: every source quote matches. The two misses are quotations of the book (§8.80 C2). R2: 20 tier-D candidates (run with --no-corpus), all ordinary English or the source's own sense.
9. Nothing in the draft is presented as decided when the decision is the Principal's. The lawfulness questions are correctly left to the PO/ARB.
10. Nothing here is critical. Seven findings are high.

## Findings

| W | Sev | Claim | Evidence | Correction | R3 |
|---|---|---|---|---|---|
| W-01 | high | C3, "*Agreement* with §8.81 C2: every next role begins with a recorded continuation" | §8.81.5 L108 withdrew "every next role begins with a human-recorded continuation" as a generalization already withdrawn at §8.79.5 (W-02 there). C3 rests on one case. | Replace with: "the capability's stated boundary (S0353 L68) agrees with §8.81 C2's case: a STOPPED item exits only by a CONTINUATION recorded by governance or a human (S0351 L109). No rule is drawn." | §8.82 C3: 0 inbound (new). §8.81 C2: 5 claim-level citations (§8.80 L100; §8.82 L3, L13, L55, L77). None is changed. |
| W-02 | high | C1, "AST-019 makes that append the mechanism's own step" | §8.75 C1 L34–45: the orchestrator was commissioned to record the human START, and identity has a named source ("runtime mechanism") while the human decision has "only a `HumanDecisionPort`" (the open interface). §8.77 C6 L110–120 makes "START executed by the capability" the operating norm. Neither section is among C1's homes. | Add §8.75 C1 and §8.77 C6 as homes. Restate as a change over time: commissioned and not implemented (S0343 L9) → implemented by the producer's report (S0353 L11). The open port is filled by a CLI argument the invoking session supplies (S0353 L35). That is the new content. | §8.75 C1: 12 claim-level citations. Propose an R3 note on §8.75 C1 ("open interface"): AST-019 fills it with `--human-act` (S0353 L35, L94). |
| W-03 | high | C2 Ded, "the next bars list (S0355 L54) does not contain it". Plain language 2: "no record names", "nobody can identify". | Proof-step table: (1) producer bar (S0353 L124), Src ✓; (2) no label in the window (S0353 L6; S0354 L13; checked by reading) ✓; (3) "the list does not contain it": **not shown**, because the label is unknown and could be any of the nine (b51dba91 is UNRESOLVED and has no lane, so it is compatible with S0354 L13); (4) bars are checked by label (§8.79.3; CON-0125) ✓. The conclusion follows from 1, 2 and 4. | Drop step 3, or restate it as "no entry is identified as AST-019's producer". Scope the plain language to "no record in the window". | §8.82 C2: 0 inbound. |
| W-04 | high | C2/C4 omission | S0354 L13–15: the producer has "not a registered lane on this work item" and is UNRESOLVED. S0353 L72 and L78: ineligible means holding a lane or not being UNRESOLVED. So, as specified, the producer passes, against "producer ≠ … ≠ adoption reviewer" (L78). | Add to C4 as the sharpest case. It is stronger than the b51dba91 case because it breaks the very bar the capability claims to keep. Conditional on "as specified". | §8.82 C4: 0. |
| W-05 | high | C4 plain language 4 ("not eligible **only if** it holds a lane") and the index row ("ineligibility only for lane-holders; the correction-author bar **not enforced**") | S0353 L78 gives a sufficient condition, not a converse. L72 adds a second ground (RESOLVED/AMBIGUOUS). C4's own Int says only "stated only for" and "not in the window" (L62–63). | Plain language: "the only grounds the source states are a lane on this work item or a bootstrap that is not UNRESOLVED". Index: "the barred list not shown as checked". | Index row 8.98 (0 inbound). |
| W-06 | high | C5, "an unverified asset consumed as the mechanism of the review" | S0353 L124–125 and S0354 L25–26 put independent verification of AST-019 *before* the adoption review. S0355 L37–51 has no such step and keeps "or by Governance" (L46). | Restate: in the producer's order AST-019 is verified first. S0355's recorded sequence leaves that step out, so binding through AST-019 there would use an unverified asset (§8.77 C5). In both orders the asset is unadopted when it binds its adopter. How the verifier of AST-019 would itself be bound is not stated (open). | §8.82 C5: 0. |
| W-07 | high | Omission (C2 × C3) | S0355 L26 and L81: the record has seq 1–9, STOPPED, with no later entry. S0353 L129 cites the refusal …-b51dba91.md, which reports STOPPED at seq 9 (S0352 L21). So the amendment slice of this work item was produced while the item was STOPPED, with no CONTINUATION and no lane. | Add to C2's "outside the lane model": the stop gate binds lane holders and candidates, not commission-authorized production. Whether that is lawful is the PO/ARB's to say. | — |
| W-08 | normal | C3, "until a CONTINUATION is recorded" | S0353 L63–70: no case covers HUMAN_DECISION_REQUIRED with a non-empty session set and no DECIDE option. What `next-actor` returns after a CONTINUATION is not shown. | Use "at least until". Name the gap in the case table. Also say that the criterion's letter (no "no lane" refusal) is met while its purpose is not. | — |
| W-09 | normal | C1, the first-binding check | S0353 L66 is a word-boundary lexical match. "do not start verification" contains the role name, so the check is necessary but not sufficient. The branch also needs an empty session set, so it can never apply to KOS-OPERATING-MODEL-001 (lanes exist, S0355 L30). | Add both points. The agreement with §8.80 C2 stands. | — |
| W-10 | normal | Ubiquitous language: "fresh" | AST-019: fresh = UNRESOLVED (S0353 L72). S0352 L23 calls b51dba91, which is UNRESOLVED (L20), "neither fresh nor independent". | Qualify the term: "fresh (bootstrap)" vs "fresh (no prior participation)". This is the homonym behind C4. | — |
| W-11 | normal | Trivial-solution test (not applied) | Of the GO numbers S0353 names, about 20 are must-never (GO-02, 07, 08, 12, 13, 15–18, 20–25). Only GO-01 and GO-14 read as must-do. In its one recorded application (S0355 L28) AST-019 refuses. | Record it under §8.75 C1's trivial-solution precedent. The mapping is from S0353's prose; the tests themselves were not seen. | — |
| W-12 | normal | C1, "§8.29 C1 recorded processes refusing exactly this" | S0353 L35 calls `--human-act` "the recorded human business instruction". S0192 L64 refuses a chat instruction as a START. The two match only if the argument carries chat text. | Replace "exactly this" with "in direction". | — |
| W-13 | normal | Lineage, S0353 L6 truncated | L6 continues "disclosed capacity: ENGINEERING-IMPLEMENTATION + GOVERNANCE-RECORDING". That makes a third non-Governance claimant of the capacity (§8.79 C3, W-02 there), and the producer records its own capability. | Quote it in full and add the agreement with §8.79 C3. | — |

## Verdicts
- **C1 PROVISIONAL.** The source facts are right. The novelty is overstated and the homes are missing (W-02, W-09, W-12).
- **C2 PROVISIONAL.** The Ded is sound once step 3 is dropped (W-03). The draft omits W-04 and W-07.
- **C3 PROVISIONAL.** The Ded is valid within the window. The withdrawn generalization (W-01) and the implied success after a CONTINUATION (W-08) must go.
- **C4 PROVISIONAL.** The Int is correct "as specified". The converse in the plain language and the index is not (W-05). W-04 should be added.
- **C5 PROVISIONAL.** It distorts the sources' order of steps (W-06).

## Coverage against my inventory
- **COVERED:** the corrected invariant, the three-way declaration and the contract (§8.82.3 via §8.80 C2); prompt ≠ commission; the four states (§8.69 C5); registry-first; the sticky STOPPED; the placeholder appointment; the two binding paths; the lineage.
- **MISSING, a loss of theory:** W-04, W-07, W-08 (the case-table gap), W-10, W-11.
- **DISTORTED:** W-05, W-06.
- **Minor, appendix:** the homonym "authorized" with three senses (S0354 L14, L56, L74–75); commission authority "exhausted by the slice" (S0354 L75); RED-by-absence (S0354 L40); the next actor moving from governance to PO/ARB (S0354 L27 → S0355 L66), a change over time; the subagent claim (S0355 L54), which is §8.79.3/§8.80.3 again and should be listed in §8.82.3; the two commit references c9762a90 and 98575324, not reconciled.
- **Source defects:** S0354 L44 omits AST-016 (against L22 and L64); "§16"/"§38" may belong to two different numberings (S0353 L31, L76, L78, L129). Nothing in the theory depends on either.
- **No new topic.** One fork may be needed: is commission-authorized production on a STOPPED item (W-07) a second channel of authority beside lanes? It continues §8.75 C5's prose-against-store line.

## Limits
- I did not read the GO tests, the code, the commits or the session logs.
- I did not open S0349 (on the allowed list, checking only), because nothing I checked needed it.
- R2 was run with --no-corpus, so tier filtering differs from the Professor's run.
- Without git I could not check the consolidation's restatements of §8.73–§8.81. I read the current text only.
- I did not read THEORY-MAP or BOOK-OUTLINE for C89 entries.

## Files opened
- In the C89 folder: BRIEF-W-STEP2.md, STEP1-INVENTORY.md, SHA256-STEP1.txt (via cat), STEP8-AND-CHECKS.md.
- chapter_08: 08.82 (whole), 08.80 (whole), 08.81 (whole), 08.79 (whole). Parts of 08.27 (L44–54), 08.73 (L34–50), 08.77 (L98–128), 08.78 (L17–24, L38–44), 08.75 (L34–48), 08.29 (L39, by grep), 08.00-index (L98, by grep).
- CONCEPT-REGISTER.tsv (rows CON-0125 and CON-0127).
- S0352 (L18–24, L54–62); S0192 (L60–70, L76–79); S0353–S0355 (read by my script); tool sources undefined_terms.py and inbound_citations.py (argument lines).
- Scratch copy of the book under `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c89-wren/c89-w-root/`. Chapter top-level *.md files only, so no review folders were copied.

## Commands and searches
- `ls` of the C89 folder, chapter_08 (filtered to 08.00 and 08.73–08.82), tmp/tools, and chapter_01; `sha256sum` of the inventory; `wc -l`.
- Allowed-list check with awk, exact match on column 2 for S0192, S0349 and S0352: all "match".
- grep in chapter_08/*.md for "chat instruction", the claim headings, "prose-against-store" and "8.82". grep in CONCEPT-REGISTER for CON-0125 and CON-0127. grep in the tools for their arguments.
- Scratch-copy build with cp. Then, all on the scratch root: `undefined_terms.py --root R --help` (it ran the tool), `undefined_terms.py --root R --no-corpus --scope 08.82` → c89-w-r2.txt; `inbound_citations.py --root R --help`; `inbound_citations.py --root R` on §8.82 C1–C5, §8.77 C6, §8.80 C3, §8.81 C2 → c89-w-r3.txt, and on §8.75 C1, §8.78 C1, §8.81 C2 → c89-w-r3b.txt.
- My own script c89-w-qcheck.py checked 8.82's quotes against S0353–S0355, S0192 and S0352 → c89-w-quotes.txt.
- No git commands. Nothing in the repository edited.
