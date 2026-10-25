C30 STEP 2-3: REVIEW REPORT

Reviewer W (Opus), transition review, Rulebook v1.4. I re-read my saved inventory before comparing; it is identical to my hand-back. In the table, **V** = verified by me, with the command listed at the end; **R** = reported or read in the text; **[I]** = my interpretation.

## Summary
1. **Critical: the committed rule `57785a947` orders designations by position, not by citation.** It says order across designations comes from citation, but both §9.13.2's "the entry immediately before it in the record's order" and check 28 use recording position (`rec.pos`).
   - V: the same entries in two merge orders give `in_D` True and False.
   - V: the members of the closed carrier are taken as a positional prefix, which includes an entry outside the citation down-closure.
   - This breaks the merge-order independence that §9.15 K2 and check 18b rely on.
2. The rest of the decision is applied as worded. Three overreaches remain:
   - an unauthorized designation is admitted to D, and nothing says it has no effect (W-02);
   - "the closing list is the same information" as a citation is not true (W-03);
   - "a resolver follows designation entries" contradicts R1 (W-04).
3. **Earlier outputs are unchanged (V).** Only check 28's line was added to the run, and my rerun reproduces it byte for byte. But no random record contains a designation, so this shows non-interference only on records without designations.
4. **C29 settlement:** all 13 rows match the text they claim to change (V, by diff).
5. **§8.23** is sound as recorded agreement, but four claims fail as stated:
   - C4 equivocates on "identity" (W-08);
   - C10 understates the S0150 duplicate and gives it the wrong home (W-09);
   - C12's headline overstates what L1636 decided, and C12 misses the source's quoted grant text (W-10);
   - C3 overreads S0159 (W-13).
6. **C6:** the Professor's reading holds. My inventory overcalled it as a contradiction; it is a homonym of "freeze".
7. **C10:** the change over time holds, but on S0150's internal order, not on filename times.
8. **Largest risk:** CON-0264's "decided" clause and §9.17 R3 (c) rest on an unstated total order.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **The designation rule is positional, not citation-based.** "closes = entry immediately before it in the record's order" plus "members = ρ's entries up to the cited entry" presuppose a total order. The book says ties are unordered and that the evaluator reads no position (§9.15 K2 cell). Missing premise, F1 and F4. | **critical** | **V** (probe P1): records [r,gD,g,**x2,x1**,d] and [r,gD,g,**x1,x2**,d] with x1 ∥ x2 and d citing and closing x1 give `in_D` True and False. Positional members: [r,gD,g,x2,x1]. Citation down-closure: {r,g,x1}. The model checks `rec.pos[closes] == k-1` (ce_model.py L208–210). | Either state the premise ("closes" is read in the kernel's serial record, K6; readers' merges must keep each designation's position), or make it citation-based: the designation cites every maximal entry of the old carrier, and membership is the citation down-closure. Add a merge-order check with designations. R3 (CON-0264 inbound): 08.19 L236; 08.23 L21, L61; 09.00 L27; 09.17 L128, L265; 09.99 L66; register rows 263, 265; map L362. | §9.13.2, §9.17 R3 (c) and CON-0264 state the order premise, or define closing by citation; a model check covers two merge orders |
| W-02 | **An unauthorized designation is admitted, and its effect is unstated.** "Authorization evaluated like any act's" does not say whether an F or U designation changes the carrier. Removal has a precedent: an unauthorized removal is refused (v9). No invariant owner. | high | **V** P2: a designation by an actor with no basis gives in_D True, value F. P4: a basis ending at its act time gives in_D True, value F. | Rule that the carrier changes only on T at append (refused otherwise), or that an F/U designation is marked and has no effect. Add a check. | text and model agree, with a check |
| W-03 | **"The closing list of (b) is the estate's form of the same information" (§9.17 R3 (c)) is false.** A citation of the last entry fixes an order point. It does not cover the members: a member lost from the carried-across copy and cited by nothing is not detected (K1 cell; CON-0265 needs an outside list). | high | **R**: §9.15 K1 cell; CON-0265; §9.17 R3 (c) | Restate: the citation fixes the boundary; detecting loss still needs CON-0265's outside list. | R3 (c) restated; R4 is not implied redundant |
| W-04 | **"Follow designation entries" contradicts R1.** Designation entries are entries, and a byte-identical copy carries them, so by R1 they cannot tell the designated carrier from a copy. Locating that carrier still needs the root (L11) plus a resolution outside the entries. Affects §8.22 C2 ("the enforcing operation now exists") and §8.23 C5 ("a resolver follows it"). S0160's "not a technical lookup" is an ownership claim that allows a resolver implementation (S0160 L346–352). It is compatible with R1, not the same claim. | high | **R**: §9.17 R1 proof steps 1–4; §8.22 C2 L57; §8.23 L65. **V**: R3 output: the only claim-level inbound line is 08.23 L65. | §8.22 C2: designation entries name the carrier, and K2-r is enforced only from the root plus an outside reference. §8.23 C5: "compatible with", not "the same claim". | both lines restated |
| W-05 | **Check 28 tests only part of the rule.** It tests the cite-required, last-entry and closes-nothing cases. It does not test "first append to the new carrier", which the model cannot express because entries have no carrier field. It also does not test two designations, F or U designations, or merge orders. | normal | **V**: diff of the v15 and v16 runs (only check 28's line added); rerun identical; P3: two designations naming the same carrier are accepted | Record the untested clauses as untested; add cases for W-01 and W-02 | checks added or scope stated |
| W-06 | **"Decided" covers the Professor's derivations.** "Immediately before", "closes-nothing refused" and "the window closes under K6" are not the Principal's words. They appear under "Decided" in CON-0264 and in R3 (c). | normal | **R**: the quote vs the CON-0264 text (`git show 57785a947`) | Label these as Ded (the Professor) | labels split |
| W-07 | **Stale lines after `57785a947`.** §8.19 L236 still names "closing list, trusted shared times, or a seal". K2-w (§9.15 cell; §9.17 L89) does not carry "takes effect at itself", which §9.17 R1 needs, because otherwise K2-w sends d to the old carrier. | normal | **V**: sed of §8.19 L236 and §9.17 | Update both | lines updated |
| W-08 | **C4 is invalid as stated.** (a) "Identity" is a homonym: record/carrier identity (R1) vs bounded-context identity (S0160 "same storage ≠ same bounded context"). (b) "Neither the store nor its content fixes what a thing is" conflicts with R2's unique identifiers and CON-0240 (content identity of fields). R1 says only that content does not fix the *carrier*. (c) The "converse" is S0160's assertion, not derived. | high | **R**: S0160 L59–63 (contexts); §9.17 R1 scope; R2 ("D's identifiers are unique") | Restrict C4 to "the carrier is not a property of the entries (R1); S0160 makes the analogous claim for contexts". Drop "in both directions". | C4 restated |
| W-09 | **C10: duplicate extent and home.** S0163 L656–765 also matches S0150 L493–603 (109 of 110 lines). So the deferral (L733) and the admission test (L743–752) are S0150's, already in §8.21 C15. There the six-question test is recorded as *differing* from CON-0085, not as agreeing with §1.9. The change over time holds: S0150 L490 (the proposal) comes before L571 (the deferral). Filename times cannot order parts inside a composite file. | high | **V**: script — S0163 L766–1241 == S0150 L17–492 True; tail match 109/110; grep of S0150 L490, L571 | Restate C10: duplicate ≈ 586 lines; home §8.21 C15; evidence = S0150's internal order. S0163's own content is L1–655. | C10 restated; measures updated |
| W-10 | **C12 overstated and an omission.** L1636 rules the *scope* (13.1); NEW-5, the open choice, was returned undecided (S0164 L1303, L1694), so "an open choice decided by an act" is wrong. Missed evidence: the quoted grant text "MUST NOT silently treat the current PHP implementation as the definition of correctness" (G-KOS-CONTRACT-EXP-AMD1, S0164 L1632; cf. L1285, L1293), an estate rule on T8-04 (pasted and reported; n = 1). The C23 label on L1636 is correct. T8-04 is rightly normative: it is a choice of the definition of conformance. | high | **R**: S0164 L1285, L1293, L1303, L1632, L1636, L1694 | Retitle C12 as "the scope was ruled; the open choice was named and returned, not defaulted". Add AMD1 as evidence for the fork. Keep the fork open. | C12 restated |
| W-11 | **C11 homes.** Establishment and the evidence dimensions sit in §8.15 C4–C5 and §8.17 C2, not THEORY-MAP. There the book rejects the "permanently unestablishable" inference (§8.15 W-1). The map's "Not yet in the book" line (L278–280) is stale. | normal | **V**: grep §8.15 L49–66; map L278 | Correct the homes; note §8.15's restriction; fix the map line | done |
| W-12 | **C2's "event-sourced" is a homonym** (S0159's domain events between contexts vs the book's recorded acts). It also needs the premise "authority is defined as CE*(record)". Under that premise "stored state is a projection" follows. | normal | **R**: S0159 L52–61, L338–386 | Say "the record is the source; state is a projection", or qualify "event sourcing (S0159)"; state the premise | restated |
| W-13 | **C3 overreads.** S0159 asks for "an idempotency boundary" (L462) and does not say the check and the effect are separate; S0158 did, with a receipt after processing. S0159 is underdetermined, not a second instance of the contradiction. The "one line" point stands (V: S0158 also mentions jMolecules). | normal | **R**: S0159 L454–477, L693–707 | "Compatible with either order; not a contradiction on its own" | restated |
| W-14 | **Omissions to record only:** authorization placed in Delivery (S0162 L56; enforcement boundary L310), a rival application placement to the kernel's CE*; EvidenceRecord/Reference/Bundle (S0162 L203–233), relating to CON-0238 and §1.16.6; S0160's workshop questions (L1151–1156: can Evidence or Governance exist without the other?), bearing on primitive minimality. | normal | **R** | Add to §8.23.3 "recorded only" | added |

## Claim verdicts (no new concept)

| Claim | Verdict | Reason |
|---|---|---|
| C1, C7, C9 | VALIDATED | agreement within lineage, correctly scoped |
| C6 | VALIDATED | working model vs final contexts holds; "freeze" is used in two senses (note it) |
| C2, C8, C11 | PROVISIONAL | W-12; C8 maps contexts to records without basis [I]; W-11 |
| C5 | PROVISIONAL | W-04 |
| C3, C4, C10 | INVALID as stated | W-13, W-08, W-09 |
| C12 | PROVISIONAL | W-10 |
| `57785a947` rule (CON-0264 decided clause) | PROVISIONAL, defect | W-01–W-03 |

## Coverage against my inventory (gaps only; everything else COVERED or JUSTIFIABLY EXCLUDED)

| Item | Status |
|---|---|
| S0160-2 (storage ≠ context) | DISTORTED (W-08) |
| S0160-8 (authority resolution not a lookup) | DISTORTED (W-04) |
| S0160-12 (workshop questions) | MISSING (W-14) |
| S0160-11 (freeze) | OPEN → resolved: not a contradiction |
| S0162-5, S0162-8, S0162-14 (authorization ownership) | MISSING (W-14) |
| S0163-5, S0163-6 | DISTORTED (W-09; S0150's content) |
| S0164-7, S0164-9 (scope by a defect is circular; AMD1) | MISSING (W-10) |
| S0164-11 | DISTORTED home (W-11) |
| S0159-6 | DISTORTED (W-13) |
| S0159-10, S0159-9; S0163-1, S0163-7, S0163-12; S0164-14 savings figures | JUSTIFIABLY EXCLUDED (testing, business or operational) |

**New fork:** none. **Source defect, not relied on:** S0164's "not enumerable" (L1360) is informal.

**Running measure:** 14 findings: 1 critical, 7 high, 6 normal.

Independence is process independence, not personal independence: same model family as the Professor, a different session, blind step 1.

## Files opened
- W/06-theory/manuscript/review/C30-S0159-S0164/BRIEF-W-STEP2.md, STEP1-INVENTORY.md, STEP8-AND-CHECKS.md
- W/06-theory/manuscript/chapter_08/08.23-…md (whole), 08.22-…md (whole)
- W/06-theory/manuscript/review/C29-S0151-S0158/SETTLEMENT.md, REVIEW-REPORT.md
- Excerpts by sed/grep: 09.17 (L55–80, L150–170), 09.15 (K6 grep), 08.19 L236, 08.15 / 08.17 / 08.06 / 08.04 / 08.09 (grep), THEORY-MAP L268–290, ce_model.py (helper and in_D lines), inbound_citations.py header
- docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (grep S0150, S0158, S0161; S0161 absent)
- Corpus, both on the allowed list, read by script only (content not displayed except grep lines with IP Governance): S0150 `brainstorming/_misc/20260819-225329-ip-protection-secrecy-strategy.md` (sha256 matches the list); S0158 `brainstorming/20260821-142748-event-driven-domain-loop-positioning.md` (keyword counts)
- The five window sources were not reopened apart from S0163, which the script read.

## Commands run
- `git show --stat` for 57785a947, 70e0c24f5, 7d5066676
- `git show 57785a947` (theory files and ce_model); `git show 70e0c24f5` (theory files, saved to scratchpad)
- `diff` of ce_model-run v15 vs v16
- `python3 -I ce_model.py` (rerun into the scratchpad; identical to the v16 run)
- `python3 -I c30_desig_probe.py` (P1–P4, scratchpad)
- `python3 -I c30_dup.py` and `c30_dup2.py` (S0163/S0150 comparison, scratchpad)
- `inbound_citations.py "§8.22 C2" "§9.17 R3" CON-0264 "§8.23 C4" "§8.23 C10"`
- greps and seds listed above

Nothing was edited in the repository. Scratch files are in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/.
