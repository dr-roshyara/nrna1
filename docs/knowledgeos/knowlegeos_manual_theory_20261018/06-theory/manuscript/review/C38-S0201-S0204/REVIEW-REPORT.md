C38 STEP 2-3: REVIEW REPORT

Window reviewer W, transition review of §8.31 (C1–C6) and the step-8 file. Before starting I checked my saved inventory against its recorded hash: sha256 `d80cdf8e…67ca`, which matches SHA256-STEP1.txt, and I re-read it.

## Summary
1. §8.31 is sound in direction: no claim is wrong at its core, and R3 finds no inbound citations to any of C1–C6, so nothing downstream rests on it. There are no critical findings.
2. The largest risk is in C6. Its "yes" rows for C-4 and C-10 give the book credit for things it holds only as a proposal (C-4) or only in part (C-10).
3. C-4 holds in D only because no assessment kind is admitted. That is the same "by absence" argument C1 criticises in the estate.
4. C2's mapping "I don't know ≠ false" → U ≠ F is incomplete. Depending on the cause, the estate's UNKNOWN lands in CE*'s U, F or refusal.
5. C3 and C4 misdescribe their own evidence. C3 overlooks that the book already holds 2/216 and 2/218. C4 treats two independence claims about *different pairs* as a claim and its withdrawal.
6. C5's homonym is real. The window contains a third sense ("mechanism vs policy").
7. The R2 run on §8.31 gives 11 tier-D words, all ordinary usage and none a defect. The step-8 record is accurate.

Independence is process independence only: I did not write §8.31 or the sources.

## Findings (ranked)

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C6 row C-4 "yes, for evidence" is premature, and a trivial case.** Evidence kinds are not admitted to D: PL-1 lists evidence as "a future kind (gap G1)", and §10.5.0 says the well-foundedness language does not exist yet. (c2) is "a **new clause** of D_S, proposed" (§9.18 L218). Check 33's equality is "empirical" for a sample (L224). In D, "assessment confers no authority" holds because D contains no assessment, which is C1's own "by absence". | high | §9.18 L217–225; §10.5 PL-1 L119, L28 | Row: "a requirement (§9.10 O2) and a proposed clause ((c2), gap G1 open). In D it holds vacuously: no assessment kind is admitted." Cross-reference C1. | Row restated; "five of eight" recounted (see W-02, W-09, W-11) |
| W-02 | **C6 row C-10 "yes, CE*'s U" merges three estate things.** These are: the EKS resolver's UNKNOWN, AMBIGUOUS and UNRESOLVABLE; PKS fail-closed; and AIP's U-register (inventory item 14). On D, CE*'s U arises only from an undefined covers pair, a negative cycle, or a tie (§9.13 R2 (d)). An unknown that comes from missing knowledge (open world) gives **F** under C1 = constitute (R2a). UNRESOLVABLE corresponds to refusal outside D. "Absence of evidence is never PASS" is closed-world F, or a consumer treating U as fail-closed (PL-6 display). Only EKS's "prose scope, unanswerable" (S0201 L292) plausibly maps to covers-undefined → U. | high | §9.13 R2 (d), R2a; §10.5 PL-6 L173–185; S0201 L292, L450 | Row: "partly: U for undecided structure (covers, cycle, tie); the estate's open-world unknown is F (C1); unresolvable is refusal." | Row restated |
| W-03 | **C2's Ded misses a premise.** Read as a proof-step table: step 1, U (R2), is defined; step 2, F (R2a), is defined; step 3, nothing recorded → F, is defined. Step 4, "the estate's 'I don't know' is CE*'s U", is **not stated and false in general**: by step 3, part of that class is F, and UNRESOLVABLE is refusal. So "maps onto CE* only as U ≠ F" (L60) does not follow. | normal | §8.31 L59–61; §9.13 R2a | "The estate's 'I don't know' splits by cause into U, F (closed world) or refusal. CE* keeps the estate's UNKNOWN ≠ FALSE only for undecided structure. The C1 decision collapses it for missing knowledge." | C2 restated |
| W-04 | **C2's "n = 1: a launch prompt states it" is wrong.** "An artificial UNKNOWN" is already in the book from S0198 L46 (§8.30 C1, C37 W-11). S0204 restates it inside the same EP-01 chain. The tie to CON-0212 and §8.30 C1 is right, and the point is **not new**. | normal | §8.30 L33, L39; S0204 L79 | "Two statements (S0198, S0204), one lineage. No case is shown." | Restated |
| W-05 | **C3's evidence account is wrong; its conclusion stands.** The book already holds 2 of 216 (S0142; §8.20 L113, §8.21 L167) and **2 of 218 from S0234 L283** (§9.06 L96). It also records the growth: 213→216 (§8.18 L113) and 216→218, where "the record grew, and S0199 L68 says so" (§8.30.3 L86). So "neither source states its date or scope … unshown" is false within the book. S0201's 2/218 relays the AIP baseline (S-04). Its EKS and AIP figures describe one shared engine (S0201 L442, "AIP same engine"; L249, "same as EKS"), so they are **one measurement, relayed**, not two counts. | normal | as cited | "The series 210/216/218 is the same record at later cuts. S0201 relays S0234. EKS and AIP are one count." | C3 restated |
| W-06 | **C4 treats claims about two different pairs as one claim and its withdrawal.** B's "two independent research streams" concerns the CAPPI discussion and review A (S0202 L756–772). D's "not two independent architectural authorities" concerns reviews A and C (L1691, L1710–1712). D never revisits B. So "corrects itself once" is Int at most, and plain-language item 4 ("two AI-produced reviews") is wrong for B. D's "not … yet" (L1691) is provisional. On S0203 L45, the stronger counter is S0201's own "same engine" (L442, L249), not only UNK-03/04. | normal | as cited | Retitle: "independence asserted for one pair, denied for another; neither is examined against the other". | C4 and item 4 restated |
| W-07 | **C1's grade comparison compares different rows.** A's L614 row is "Authority" as a concern (strength High, consensus *Moderate*), not "Authority ≠ Confidence". A's real internal tension is L293 ("Research supports this separation") against L634 ("authority modeling as a distinct primitive" listed as a weakness). | normal | S0202 L293, L614, L634, L1519, L1596 | Replace the comparison with this one. | Restated |
| W-08 | **C1's strength is overstated.** X-03's impact column says "**partly** by-absence" (S0201 L531). The positive evidence, AP-1 enforced by construction and machine-unemittable verdicts (L158, L292), shows *assessment confers no authority* directly, not by absence. Separately, citing "§9.18 G7 open" for "the book has no confidence" uses the wrong home: G7 maps g0's vocabulary, and g0 has four outputs, including U (§9.18 L81, L195–198). | normal | as cited | Title: "partly by absence". Give a basis for "no confidence in the book", or mark it as a search result. | Restated |
| W-09 | **C6 row C-2, and C-5's "no", ignore the tombstone.** S0201's C-2 includes "no delete path" and "no in-place rewrite" (L442). The book's tombstone "changes an earlier entry in place" and drops content (§9.13 L62; §9.15 G3). | normal | as cited | C-2: "the fold half: yes; delete-free: no (tombstone)". C-5: "supersession: no; no in-place revision: partly". | Rows restated |
| W-10 | **C6 row C-6 compares different objects.** S0201's C-6 is the identity of items and assets (registry ids, work-item ids). §9.16 R2 is the identity of *entries*. In the book, item k is "a governed identifier" taken as input (§9.10 L33), and its register is not admitted (T10-10 (1)). | normal | as cited | Keep "no", with this reason. | Restated |
| W-11 | **C6 row C-3 holds in form only.** S0201's invariant includes a human-only, machine-unemittable subset (L443), and PL-6 has no counterpart. The estate's vocabulary consists of assessment verdicts; PL-6's consists of authority answers. | normal | S0201 L443; PL-6 | "yes in form". | Restated |
| W-12 | **Homonyms the draft misses.** (a) A third sense of "mechanism": mechanism vs policy, "Kernel provides mechanism, not policy" (S0202 L1452–1456, L1602). (b) "Projection": a derived, non-authoritative display (§8.17 L73, C-7) vs "the record without W entries" (§9.18 (c5)). | normal | as cited | Add both to C5's homonym record. | Recorded |
| W-13 | **Lineage omission.** S0202 D L1772–1797 answers "your earlier complex-number question", which places it on S0200's chat line (§8.30 C5). The lineage note does not link the two. | normal | S0202 L1772 | Add to the lineage note. | Added |
| W-14 | **C6 omits S0201's own precondition.** S0201's EKS findings rest on a baseline it calls broken (L9, L140; R-1 at L667), and S0204 bars candidate work until that baseline is complete. | normal | S0201 L9, L667; S0204 L28 | Note in C6 that the candidates are conditional on R-1. | Added |

## Claim verdicts

| Claim | Verdict | Reason (scope) |
|---|---|---|
| C1 | PROVISIONAL | Right in direction. Strength and grade comparison need restating (W-07, W-08). |
| C2 | PROVISIONAL | The four answers are rightly kept apart. The mapping misses a premise (W-03). The novelty count is wrong (W-04). |
| C3 | VALIDATED (the property: act times are almost never recorded) | The evidence account needs restating (W-05). |
| C4 | PROVISIONAL | The rule is right. The case is misdescribed (W-06). |
| C5 | VALIDATED | The homonym is real; extend it per W-12. |
| C6 | PROVISIONAL | Rows C-1, C-5 (supersession) and C-7 are right. C-2, C-3 and C-10 hold only partly or in form. C-4 is proposed only. C-6's reason is wrong. |

Recorded challenges:
- **C1 rival:** adequate, with a limit: check 33 tests evidence entries on a proposed model, not a confidence value.
- **C6 circularity:** adequate as stated, but incomplete: W-01 and W-02 are also informative disagreements.
- **Challenges absent:** none is recorded for C2 (that the mapping is by decision, not by meaning), C3 (a relay counted as a measurement) or C4 (the pairs differ).

## Coverage (inventory against §8.31)

| Status | Items |
|---|---|
| COVERED | S0201 1, 2, 3, 10, 11 (§8.31.3), 13c (lineage); S0202 3, 5, 7 (partly), 12 (L1408); S0203 2, 4, 9, 11; S0204 1, 4, 8 |
| DISTORTED | S0201 14 (C-10 merging, W-02; the shared engine, W-05); S0202 13–14 (W-06); S0202 grades (W-07) |
| MISSING (normal) | S0201 9 (H-08: events shown not necessary, ownership not shown sufficient); S0201 6 and S0202 4 (structural vs semantic validation; possible homes §9.14 and §8.22–8.26, which I found but did not read); S0201 12 (PKS four orthogonal axes); S0202 10 (A/C contradictions on DDD and event sourcing, the same kind of point as C1's grades); S0203 10, 12 (the YES/NO table flattened, all 8 called "strong", C-8 "Not kernel"); S0203 3 (UNKNOWN turned into a lean against probability); S0204 12 (W-14) |
| JUSTIFIABLY EXCLUDED | outside literature (T10-03); S0202 9 and 11 (above-core lists and the matrices: product framing); the S0204 method rules (homes in §8.28 C6 and §8.30) |
| Source defects (count only if relied on; none is) | S0201's "seven" vs eight kernel candidates (L33, L639); H-04's double status (L491); S0204's stale status lines (already recorded) |

New topics or forks: none required. W-02 and W-03 sharpen T10-21 (1): the display hazard of F extends to the estate's UNKNOWN.

## Limits
- I did not open Ch. 0–2. The brief bars "Ch. 0–2 basis files", and whether that covers the chapters themselves is unclear to me. So the citations to §0.6, §1.2, §1.5, §1.13 and §1.17 are unchecked.
- I did not open the SD1–SD5 or G1 review folders, S0198, S0234, S0128 or S0142. I used the book's statements about them.
- Search misses are not absence.

## Files opened
All under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/`:
- `06-theory/manuscript/review/C38-S0201-S0204/`: BRIEF-W-STEP2.md, STEP1-INVENTORY.md, STEP8-AND-CHECKS.md
- `06-theory/manuscript/chapter_08/`: 08.31-authority-is-not-confidence-by-absence-and-agreement-within-one-lineage-is-not-independence.md; 08.30-completeness-is-established-not-asserted-and-a-representation-does-not-make-a-concept.md (whole); 08.28-the-kernel-is-a-mechanism-not-a-meaning-and-nothing-in-the-estate-attests-identity.md (L1–60); 08.06-independence-of-a-check.md (L1–40)
- `06-theory/manuscript/chapter_09/09.13-the-evaluator.md` (L40–99, L185–209)
- `06-theory/manuscript/chapter_09/09.18-evidence-in-the-record.md` (L195–234)
- `06-theory/manuscript/chapter_10/10.05-published-language.md` (L18–187)

Read only through grep or sed excerpts: 08.17-adopted-is-not-verified-and-the-record-must-outlast-prose.md, 09.15-the-trust-boundary.md, 09.16-reassessment.md, 09.10-required-capabilities.md, 10.01-subdomains.md, 10.04-context-map.md, chapter_10/TODO.md, CONCEPT-REGISTER.tsv (rows CON-0195, CON-0212, CON-0221, CON-0243), and tmp/tools/undefined_terms.py and tmp/tools/inbound_citations.py (headers).

I reopened no source file; the source line numbers above come from my saved inventory and my step-1 reading.

## Commands run
- `ls` of the C38 folder and of 06-theory, the manuscript, and chapters 08–10 and 01. Chapter 01 was listed but not opened.
- `sha256sum` of STEP1-INVENTORY.md, and `cat` of SHA256-STEP1.txt.
- `wc -l` of the cited sections.
- grep in 09.13 for R2/R2a.
- grep in CONCEPT-REGISTER.tsv for CON-0212, 0221, 0195 and 0243.
- grep across Ch. 3–10, the map and the register for "2 of 210", "/218" and "216".
- grep in 09.18 for (c1)–(c5), G7 and check 33.
- grep in 10.01 and the Ch. 10 TODO for promise (b) and T10-21.
- grep in 10.05 for its outline.
- grep in 09.15 for G1/G3, in 09.16 for R2, and in 08.17 for "projection" and C8.
- `sed` excerpts of 08.28 and 08.06.
- grep for T10-10 and item identity.
- `ls tmp/tools`.
- `python3 -I tmp/tools/undefined_terms.py` (whole book, then `--scope 08.31`).
- `python3 -I tmp/tools/inbound_citations.py "§8.31 C1"` … `"§8.31 C6"`: 0 hits each.
- `python3 -I tmp/tools/book_search.py` for "structural validation semantic correctness" and "event-driven consistency invariant ownership": no hits.
- grep of Ch. 3–10 for structural/semantic and event-driven wording.

I edited nothing.
