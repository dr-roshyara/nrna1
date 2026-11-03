C107 REVIEW REPORT (B)

Window reviewer WB (Opus). Transition review of §8.101 C5–C8 against my saved inventory of S0387–S0391 (`STEP1-INVENTORY-B.md`, re-read whole first). I am independent of the draft's process, not as a person.

## Summary (10 lines)
1. All the quotes in C5–C8 match their sources at the cited lines (checked by hand, 24 of 24). The thread mapping in C8 is correct: S0321, S0356–S0373, S0383 and S0384 match S0388 L307 file by file against the allowed list (21 of 21).
2. **C5 maps the bypass onto K1, and the source does not support that.** Every hand-written entry went through the estate's single writer, AST-015, and passed its checks (S0387 L87). The thing bypassed was the appointment engine, which sits on a route above that writer. This is §8.29 C5's case of a gate that checks a route, not a failure of an exclusive write path.
3. **C6 says the guard on the estate's workflow record "caught" the defect. It did not.** It accepted the REGISTER entry with the wrong predecessor, which is the permanent orphan, and refused only the HANDOFF after it.
4. **C6's handling of the "corroboration" claim misreads ASD-001.** The draft says Governance derived the ownership facts wrongly. The facts Governance wrote by hand were correct (S0390 L307: "identical record"); only how they were obtained was defective. So the two cases are not two failures of one kind, which is a stronger answer than lineage alone.
5. A smaller point in C6: the test suite did exercise the write path, but only in the trivial case where there is no owner. An implementation that always writes "no owner" passes all 25 tests. That is the trivial-solution test, and "confirmed" overstates what the case shows.
6. C7 is right on the merits. Its attribution needs fixing: S0390 is inconsistent with itself (L235, L240, L355), and S0391 attributes the grouping to "the commission" (L34).
7. C8 is right as lineage. Three things are missing: a home it should cite (§8.27 C7), S0388's overstatement of S0370 against §8.94 C1, and S0389's report that the falsification was itself refuted.
8. Nothing in C5–C8 presents as decided something that belongs to the Principal.
9. R and N counts: 4 R and 9 N. There are three high findings and no critical one.
10. No proof (Ded) appears in C5–C8, so no proof-step table was needed.

## Findings

| W | Sev | R/N | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|---|
| W-01 | high | R | C5: "Within D this is the shape of K1 … the writer that broke it is the estate's own Governance"; plain language 5, "the exclusive-write-path assumption broken" | S0387 L85–87: every AST-015 precondition passed, exit 0 three times. S0390 L139–149: AST-015 is the sole writer. K1 (§9.15 L74) says the record changes only through the kernel's steps, and it did. What was bypassed is AST-018, a route above the writer (S0387 L64). §8.29 C5's interpretation already states the distinction: "A tool-scoped gate checks a route; the book checks the record" (§8.29 L76). | Restate. This is not K1: the estate's write path stayed exclusive. It is §8.29 C5's case one level up. A guard on the appointment route infers a historical fact ("a lane exists, so the candidate took part") that holds only if lanes are created through that route alone, and the hand-written entries made it false. In D, the guards evaluate the record within the append step (§9.13 L37, L48; K6), so their verdict does not depend on which route produced an entry. The D part is then a contrast, not a match. Set C5's scope to "within the estate; D by contrast". | §8.101 L21, L32, L78 |
| W-02 | high | N | C6 title, plain language 6, Int bullet 2, and the index row: the guard on the workflow record "caught it" and "decided" | S0390 L116: "seq 9 REGISTER … predecessor: None ← orphan, wrong, permanent". S0390 L231: Inv B is "satisfied formally" while the chain is broken in substance. The guard refused only the HANDOFF (L111). S0390 L121 itself says only "prevented worse". | Restate. The guard refused the second write and accepted the first, which carries a wrong predecessor. It checks that a predecessor is present, not that it equals the owner. The book agrees in form only for the HANDOFF. In D, a value of that kind would be derived from the record rather than taken from the writer (§9.20 Th1, Th3); that is a contrast, not a match. | §8.101 L22, L33, L83, L88; 08.00-index L116 ("caught by the record-level guard") |
| W-03 | high | N | C6 Int bullet 4 and §8.101.4's challenge answer: "two instances of ownership facts derived wrongly, one by Governance by hand" | S0390 L307: using AST-019 "would have produced an identical record … concealed nothing". S0387 L93: "What IS defective is exactly one thing: the appointment's provenance." S0390 L305 says the facts were "hand-derived", not that they were wrong. | Restate. In ASD-001 the facts were correct and their derivation went unrecorded; in F-1 a mechanism derived wrong facts. They share a property (where ownership facts come from), not a defect. So they are not two occurrences of one defect at all, and lineage (§8.31 C4) is the second reason, not the only one. The challenge answer as written is inadequate: it refutes on the wrong ground. | §8.101 L90, L121–122 |
| W-04 | normal | N | C6 Int bullet 1: the property "was never exercised"; "§8.100 C5 and §8.15 C1 confirmed on a case" | S0390 L283: the write path is reached only where the owner is null, and there "predecessor = owner" holds trivially. An implementation that always writes null passes GO-01–GO-25. CON-0215 concerns whether an adopted invariant establishes existence; §8.100 C5 already uses it only in form. | Say "exercised only on its trivial case" and cite the trivial-solution form, §9.19.8 F-min (a non-default answer must stay reachable), as §8.86 C5 uses it. Replace "confirmed" with "an instance in form". A failure in an untested case fits an epistemic rule; it does not confirm it. | §8.101 L33, L87 |
| W-05 | normal | N | Homes. C5: "§8.86 C4's gap between check and act". C6: §9.13 L46–56 and §9.20 Th3 | §8.86 C4 is the time-of-check/time-of-use form, where state changes between check and act. In ASD-001 there was no check-then-act gap; the check's record was skipped (S0387 L68, L93). The check/act split is C6's case: `check` returns READY and `activate` fails (S0390 L105, L119), on the same state, with check and activate computing different functions. §9.13 L46–56 covers single-use, removal, designation and so on. The append-guard lines are L37 and L48 (also used by §8.86). Th3 is about stored copies; `check` is a second evaluation, not a store. | Move §8.86 C4 to C6, marked "not TOCTOU: two evaluations that differ". Cite §9.13 L37 and L48, and K6: in D, check and append are one serialised step, so no separate predictor exists. Keep Th3 only as a loose fit or drop it. In C5 say "the check's recording was skipped". | §8.101 L80, L88 |
| W-06 | normal | N | C5 leaves out the guard distinction and the unrecoverable record | S0387 L73–79 and S0390 L303: the guard that checks before appointment is not the substantive independence check, and the refusal "is NOT a finding that 84c0f6f6 lacks independence". After the bypass, the eligibility record cannot be added: appoint refuses (L365), V5a/GO-07 refuses (S0390 L307), and there is no un-REGISTER (S0387 L99). | Add two points. First, a refusal by a structural guard neither refutes nor supports independence (CON-0127 needs a recorded shared factor). Second, the lost item is a missing entry that no available kind can supply afterwards. That is §8.13 C7's form for a missing entry rather than a wrong one. | §8.101 L79–80 |
| W-07 | normal | N | C7: "S0391 corrects S0390's grouping (S0390 L240)" | S0391 L34 attributes the grouping to "the commission". S0390 §12 labels only F-2 "same root cause" (L235); F-3 and F-4 carry their own types (L242, L249). The grouping appears in S0390 L355 ("+F-2/F-3/F-4 same root"). | Cite S0390 L235, L240 and L355, and S0391 L34's attribution. State the logical content: fixing F-1 is necessary for F-2 but not sufficient. Add CON-0242 ("verified, not assumed", S0391 L58) as a home. Justify §8.100 C1 as a home or drop it; C7's text does not relate to it. | §8.101 L23, L96 |
| W-08 | normal | R | C8: the six-part aggregate "is §8.94 C1's object, which the book holds for the derived part within D only" | §8.94 C1 holds FALSIFIED for (i) the derived part, (ii) only where no clause joins the parts, and (iii) not assessable for justification and confidence. It also finds that S0370 analyses 7 of 15 pairs, with each cell asserted. S0388 L66 says it rates "every coupling". S0389 L285 reports that the unread critique refuted the falsification as a persistence-level test. | Give §8.94 C1's three parts. Record S0388 L66 as the secondary report overstating its primary source. Record S0389 L285 as a rival from an unread document, reported, which disagrees with S0388 L64–70. | §8.101 L103 |
| W-09 | normal | R | C8 renaming: a home is missed | §8.27 C7: "Timestamps are taken from the filesystem, never from a filename." S0389 L15–24 states the same rule. S0389 L25–26: the first sort (2026-08-21) did prepend YAML blocks; "content unchanged" holds for the later batches, including the thread (L262). | Cite §8.27 C7. Limit "content unchanged" to the batch that holds the thread. | §8.101 L24, L105 |
| W-10 | normal | R | C8: "checked against the book" | S0388 L58 attributes "'Kernel = pure function' → HYPOTHESIS, not decision" to 110950 (S0363). The book reads that sentence in S0361 (B) L797 (§8.86 C2). S0363 was not open in this commission. | Record it as a possible misattribution in the secondary report, or as a sentence the two files share, not checked. Either way it supports "S0388 is not an index of where content lies". | §8.101 L103 |
| W-11 | normal | N | Source defects (the theory relies on none in C8) | S0388 L110 "Six of the twelve" against a table showing 7 ✅ and 5 ⚠️. S0388 L230 "Five of the six" against three IDs, and S0389 L287 "three". S0389 L302 "Nine" followed by 14 IDs. S0389 L273 "6+" against L303 "20+". S0388 L3 "26 rows" against a 21-row table. The tiers listed in S0388 L199–203 do not count to 26 (a matter for commission A's C1). | List them under §8.101.3 as source defects. | §8.101 L113 |
| W-12 | normal | N | R2 and homonyms: "record" used without qualification in C5 and C6 | §8.100's lineage qualifies three senses of "record". §8.101 C5–C6 uses "record-level guard" (R2 tier D, L83, L88), "append-only record" and "the book's record" without saying which. | Qualify each use as the estate's workflow record (AST-015), a governance document, or D's record. | §8.101 L5, L32–33, L83, L88–89 |
| W-13 | normal | N | Coverage of S0390 and S0391 | S0390 O-2 (L284): GO-15 rests on source strings being absent, and GO-16/17 compare against HEAD, which passes trivially once a change is committed. These are weak fitness tests (CON-0242; §8.29 C4). S0390 L24 and L318 make VERIFIED three-valued (not verified / completed PASS / completed FAIL), next to §8.100 C1. S0390 L22: "Everything built on top of it is sound" overreaches. Path A was in effect enacted by conduct (84c0f6f6 went on to verify) while S0390 L309 and S0391 L125 still call the remedy open. That question belongs to the estate's PO/ARB, not the Principal. | Add these to §8.101.3 as recorded items. Map O-2 to CON-0242. | §8.101 L115 |

**Counts:** R 4 (W-01, W-08, W-09, W-10); N 9. High: W-01, W-02, W-03.

## Verdicts
- **C5:** INVALID as stated, because of the K1 mapping (W-01). Restated per W-01 and W-06, it is VALIDATED within the estate and stands as a contrast with D.
- **C6:** INVALID as stated: "caught" (W-02) and the misreading of ASD-001 (W-03). Restated, it is VALIDATED within the estate. The D agreement is PROVISIONAL, in form only (W-04, W-05).
- **C7:** VALIDATED within the estate. The correction in S0391 holds: the L620 warning survives a fix to F-1 alone. The attribution needs fixing (W-07).
- **C8:** VALIDATED as a lineage claim (mapping checked, 21 of 21). The book-comparison bullets need W-08 to W-10.

## Coverage against my inventory
- **COVERED:** S0387 1–3, 5, 7, 11. S0388 1–3, 14. S0389 2 and 9. S0390 1, 2, 4, 5, 11. S0391 3, 4.
- **MISSING:** S0387 4 (W-06). S0390 3, 9, 13–14 (W-13). S0389 10 and S0388/S0389 on the falsification (W-08). The §8.27 C7 home (W-09).
- **DISTORTED:** S0387 6 and S0390 5 (W-01, W-02). S0390 10–11 (W-03).
- **JUSTIFIABLY EXCLUDED:**
  - S0387 8–10 and 14 (the remedy belongs to the PO/ARB);
  - S0391 6–9 (the estate's process);
  - S0388 9 (drift points, per §8.84);
  - S0388 12: the non-collapse pairs are mapped already in §8.95 from S0371 L867–889, so §8.101.3 should point there.
- **For commission A (C3):** my sources repeat the absence claim. S0387 L89 and S0390 L43 say "no hits anywhere in the estate", and S0390 L300 says "re-established by me, not inherited" using the same four searches. That is one line of support, not a second check.
- **Lineage note and §8.101.3, as they concern my sources:** accurate, with W-11 and W-13 to add.

## Limits
- I did not open S0363, the critique, or any registration or PHP source.
- Claims about them are reported only (W-10, W-08).
- I did not run `corpus_quote_check.py`; the quotes were checked by hand with fixed-string searches.

## Files opened
- `.../06-theory/manuscript/review/C107-S0383-S0391/BRIEF-WB-STEP2.md`, `STEP1-INVENTORY-B.md`, `STEP8-AND-CHECKS.md`, `COPY-LIST-STEP2.txt` (via `cat` and `grep`)
- `.../06-theory/manuscript/chapter_08/08.101-…live-owner.md`, `08.00-index.md` (grep), `08.29-…`, `08.86-…`, `08.100-…`, `08.94-…` (C1 via sed), `08.83-…` (C3 and header), `08.96-…` (header), `08.31-…` (C4), `08.15-…` (C1), `08.13-…` (C7), `08.27-…` (grep C7), `08.95-…` (grep)
- `.../chapter_09/09.13-the-evaluator.md` (L1–60 and grep), `09.15-the-trust-boundary.md` (grep K1, L74), `09.20-the-state-space.md` (grep Th3)
- `.../06-theory/CONCEPT-REGISTER.tsv` (grep CON-0120, CON-0215, CON-0242)
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (fixed-string grep of 21 timestamps)
- S0387, S0388, S0389, S0390, S0391 (fixed-string quote checks)
- `undefined_terms.py` and `inbound_citations.py`, read only through their source and help on the scratch copy

All of these are under the repository root `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/` unless the path is given in full. I opened nothing in other review folders or `00-access/`, and no `.env*`.

## Commands and searches
1. `wc -l` on the step-2 files and the 13 home sections, named one by one.
2. `cat COPY-LIST-STEP2.txt`; then grep of the saved output for the chapter names needed.
3. Copied the 268 files on the copy list one by one (the brief's `while read` loop) to `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c107-wb/root/`.
4. sed, awk and grep on the named book files listed above.
5. A fixed-string grep loop over the allowed list for the 21 filename timestamps of the thread.
6. A fixed-string quote check over S0387, S0388, S0389, S0390 and S0391: 24 quotes, all found at the cited lines.
7. `undefined_terms.py --help`. It failed because the tool reads CORPUS-ALLOWED.tsv relative to the copy, and that file is not on the copy list.
8. `undefined_terms.py --no-corpus --scope 08.101`, exit 0. Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c107-wb/c107-wB-r2.txt`. In scope there was one tier A term (ASD-001, glossed at L8) and tier D words. I judged the tier D words relevant to C5–C8: "record-level" (W-12); "predictor", "orphan" and "mitigation" are glossed in context.
9. `inbound_citations.py --root <scratch lane> "§8.101 C5" … "§8.101 C8"`, exit 0, 0 inbound each. Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c107-wb/c107-wB-r3.txt`. The index row L116 cites these claims as "(C5)" and "(C6)"; I listed it by hand.

No git command, glob, recursive search or directory listing; no edits in the repository.
