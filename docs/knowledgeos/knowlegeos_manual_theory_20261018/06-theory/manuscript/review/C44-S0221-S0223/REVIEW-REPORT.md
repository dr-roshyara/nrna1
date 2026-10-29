C44 STEP 2-3: REVIEW REPORT

This is window reviewer W's transition review of §8.37 (C1–C6), the step-8 file, the corrections made before review (`8864c11e7`), the C43 settlement (`cf10a2236`) and T9-16 (b) (`ed29e5957`). I re-read my saved inventory first; its sha256 `adf135f2…ffbe` matches SHA256-STEP1.txt.

## Summary
1. **No critical findings. Five are high.** §8.37 adds no concept and no register row, and nothing yet cites C1–C4 (R3). The defects stay inside §8.37, and in §8.38 L114 through C5.
2. **C1's split into "record the relation" and "status from the relation" is right, but its conclusion is not.**
   - Its two readings leave out a third: a **guard** on adoption. That reading conflicts only with the *proposed* clause (c2), not with A1.
   - Reading (ii) treats "no warrant" as if it were "no evidence". An item with live support and no warrant shows "supported", not "not attested".
3. **C2 repeats the merge that C43 W-05 corrected.** It equates UNKNOWN and CONFLICTED with the book's U. Also, S0223 does not "reverse its siblings": validation entered the kernel step by step, through S0220 L473, S0221 L498–503 and S0222 L397–416.
4. **C3's "relations are already in the record" holds for authority relations only.** "Link" is an input kind that Req lists but D does not admit (CON-0188; §9.13 L52). This contradicts §8.36.3 L123 and §8.38 L78.
5. **C4's "agreement" with S0223's Conflict Space missed the W-07 narrowing** that `cf10a2236` applied to §8.35 and §8.36 (an R3 gap in the settlement).
6. **The C43 settlement matches its text row by row**, except for that one R3 gap.

## Findings (ranked)

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C1: "only (ii) is consistent with the book" is too strong.** (a) A guard reading: an adoption must cite its warrant. Standing still comes from the act, so A1 holds, and only the proposed (c2) is broken. This is the reading accepted at C43 W-03. (b) A content reading: "hypothesis" is a content status, and the kernel has no content value (§8.36 C4 (iii)), so it is an application matter. (c) "Which the book forbids" (plain language 1) rests on A1 and §8.33 C2, not on (c1)/(c2). Those are premises of a proposed result. | high | S0223 L69–71; §9.18 L217–218; §8.36 C1 and its challenge | "Reading (i) as a value function breaks A1. As a guard it conflicts only with the proposed (c2): a choice for the Principal. As a content status it belongs to applications." Cite A1 and §8.33 C2. | C1 lists three readings, each with its home |
| W-02 | **C1 (ii) maps "no warrant" onto "not attested".** S0223 separates Evidence from Warrant (L27–63, "Why is smoke a legitimate indicator"). The book's evidence entry has **about**, **polarity** and the producer's grant (§9.18.4). It has no warrant field. Counterexample: an authorised producer's live "supports" entry with no warrant. g0 shows "supported", where the source says "hypothesis". "The book can hold it as an evidence entry that cites what it supports" also omits **as proposed** (the C43 W-06 defect again). | high | §9.18 L24, L66–68, L109–112, L193–198 | "(ii) needs a class that promise (b) lacks (supported, warrant not recorded), or a warrant field: G7 or the reasons gap (§8.36.3 L115). The evidence kinds are proposed." | C1 (ii) restated; home named |
| W-03 | **C3: relations between items are not in the record.** S0221's examples are item–item or item–context relations: supported-by, bounded-by, derived-through (L438–462). O1 lists **link**, which D does not admit (CON-0188; §9.13 L52). The challenge answer "the book requires no third kind" contradicts Req's own list. §8.36.3 L123 and §8.38 L19 and L78 already say links have no home. | high | CON-0188; §9.13 L52; §8.38 L78 | "Authority relations (cite, cover, target, holder) are the record. Evidence→item is proposed (evid 'about'). Item–item links are a required input that D does not admit: no home." Restate the title and plain language 3. | C3 and §8.38 agree |
| W-04 | **C2's Int merges axes again.** "VALIDATED/INVALID = a truth verdict; UNKNOWN/CONFLICTED = the book's undetermined" contradicts §8.36 C4's four-valued lens (settled). In S0223 (L136–149), VALIDATED and INVALID judge an *argument*, not truth. UNKNOWN is the evidence-side "not attested" (neither). CONFLICTED is T9-17's rival "contested" (both); g0 collapses it to "refuted". QUESTIONABLE is graded, and g0 has no strength. None of the five is U, an authority value with a cause. **Yes, C2 needs the five-valued reading.** | high | §8.36 C4 (iii) and the lens; §9.18 L66–68, L202 | Map each of the five values per axis, as above. | C2 Int restated |
| W-05 | **C4: "agreement" covers only non-deletion.** S0223's "Conflict Space → Resolution Process" (L259–274) is a *recorded* conflict. A conflict between non-rule items is "neither recorded nor reported" (§8.36 C4 (ii); §8.35 C4 (i-a), as narrowed at `cf10a2236`). §8.37 C4 cites the same C6 row and was not narrowed. S0221's Failure-001 asks to keep failed *reasoning*, which is not in the record (§8.36 C3, content). | high | §8.37 L77; §8.36 L79, L127 | "Agreement: nothing is deleted. The Conflict Space and the failure modes have no home (the §8.36.3 fork)." | C4 narrowed (R3 of C43 W-07) |
| W-06 | **C2: the lineage is wrong and a rival is missing.** (a) The "return" is gradual: S0220 L473 ("preserves … the validity of reasoning"), S0221 L498–503 ("Through: … Validation processes") and S0222 L397–416 (Reasoning Governance with Validation and Determination inside KnowledgeOS). (b) S0223 does name a determiner, the **Mediator** (L222–223). The engine fits §10.2 candidate 8: assessment methods "write evidence entries … decide no classes" (L169–171). Of its five outputs, only two map onto evidence polarity. | normal | sources as cited; §10.2 L169–171 | "Continues S0220–S0222." Add the rival "engine = candidate 8, Mediator = actor", with its answer. | C2 and its challenge restated |
| W-07 | **C4 satpratipakṣa.** Under the book's candidate g0, counterbalanced evidence shows **"refuted"**. That is the opposite of the classical sense (no conclusion either way). It fits "contested" only under the rival. S0221 Part B L653 is a third gloss (one reason, both conclusions). T9-16 (b) and T9-12 (c): a challenge from someone without a grant is shown, not counted. S0223's open agent opposition does not say so. | normal | §9.18 L193–202; S0221 L653 | Add these three. | C4 extended |
| W-08 | **C5: "citation" is a homonym.** S0223's "generated citation" is bibliographic, inside the evidence content. The record's citation is a reference to an entry. In the kernel, append refuses a citation of a non-entry. On a merged or bypassed record it puts the record outside D (§9.13 L206): detected, not prevented. Counterexample within scope: an authorised producer's entry whose content rests on a fabricated document is counted, and g0 shows "supported by recorded evidence". The name/body merge ("authenticity" vs "logical force") is not stated. | normal | §9.13 L206; §9.18.4; S0223 L342–344 | Qualify "citation (record)" and "citation (bibliographic)". Add the counterexample and the merge. R3: §8.38 L114. | C5 and §8.38 L114 restated |
| W-09 | **C3 delimiters.** S0221's avacchedaka delimits a proposition's *validity* (L191). The book's scope delimits *standing*, whole-or-nothing (Θ_f ω, §9.13 L84). Context-002 is a must-do that nothing enforces: the invariant has no owner. Plain language 3 says "agree". | normal | §8.37 L70; §9.13 L84 | "Scope agrees for standing. Validity delimiters are content, with no kernel owner." | Plain language 3 narrowed |
| W-10 | **Coverage.** (a) §8.37.3 records S0222's definitional precision as "homonyms". Over- and under-extension are faults of a definition's extension, not homonymy: DISTORTED. (b) S0222's ReasoningGate-001 (L357–359) is the produce-vs-test fork (§8.36.3 L120), relayed again and not carried. (c) S0223 L535, "truth emerges when … preserved together", is preservation presented as sufficient for truth, beside §9.10 L67 ("current authority is not correctness"). (d) Relations as primitive / means / invariant (S0221 L500 vs L556; S0222 L431). | normal | as cited | Add rows to §8.37.3 and C6. | rows added |

**Source defects** (listed separately; the theory does not rely on them):
- **S0223 L372–396:** condition 3 is stated as its converse ("no sign → no conclusion"), and the sign/signified roles are reversed.
- **S0222 L230 vs L242:** the conclusion is not the claim.
- **S0221 L207–220:** the table maps 12 of the 16 categories.
- **S0221 L651:** the savyabhicāra example illustrates a non-unique cause.
- **S0222 L94 vs L145:** inference is listed as a means of valid knowledge and also called "not a source".

## Claim verdicts

| Claim | Verdict | Reason (scope) |
|---|---|---|
| C1 | split **VALIDATED**; (i) as a value function **VALIDATED** (A1); "only (ii)" **INVALID**; (ii) = "not attested" **INVALID** as stated; "book holds the relation" **PROVISIONAL** | W-01, W-02 |
| C2 | the engine is an application, and CE* answers standing, not truth: **VALIDATED** (§9.10 L67; §10.2); "reverses siblings" and the UNKNOWN/CONFLICTED = U Int: **INVALID** | W-04, W-06 |
| C3 | no authority level stored (CON-0141): **VALIDATED**; relations in the record: **VALIDATED** for authority, **INVALID** for item relations; delimiter agreement: **PROVISIONAL** | W-03, W-09 |
| C4 | nothing deleted: **VALIDATED**; Conflict Space agreement: **INVALID** as stated; two glosses: **VALIDATED** (a third exists) | W-05, W-07 |
| C5 | the split: **VALIDATED**; "cannot enter": **PROVISIONAL** | W-08 |
| C6 | **VALIDATED** (Int) | appendix |

**Recorded challenges:**
- **C1 inadequate:** the guard and content readings are missing, as is the "supported, unwarranted" counterexample.
- **C2 adequate** for "kernel = whole", but it misses the Mediator / candidate 8 rival.
- **C3 inadequate:** "no third kind" is contradicted by CON-0188.
- **C4–C6 have no challenges.** The strongest are S0223's recorded Conflict Space (C4) and the fabricated document counted as "supported" (C5).

**Proof steps, C1:**

| Step | Justification | Status |
|---|---|---|
| 1. Sentence 1 asks to record | S0223 L71 | ok |
| 2. The book can hold it | §9.18.4 | warrant not defined; kinds proposed |
| 3. Sentence 2 sets a status | S0223 L71 | ok |
| 4. (i) breaks non-interference | (c1); proposed (c2) | premise missing: a value function, not a guard; A1 is the real home |
| 5. (ii) fits promise (b) | T10-01 | object mismatch: warrant ≠ evidence |
| 6. "Only (ii)" | — | does not follow |

C1's derivation is therefore "inadequate: premise missing".

## Coverage

My inventory against §8.37:

| Status | Items |
|---|---|
| **COVERED** | S0221 2, 3, 6, 7, 10, 12, 13 (finality); S0222 2, 4 (in part), 5, 8 (in part), 9; S0223 1, 2, 4, 5, 6, 8, 10, 12; lineage (one line) |
| **DISTORTED** | S0221 2 and 4 (W-03, W-09); S0223 2 (W-02); S0223 4 (W-04); S0223 6 (W-05); S0222 7 (W-10a) |
| **MISSING** | S0222 8 as the fork (W-10b); S0223 13 (W-10c); S0221 11 / S0222 11 (W-10d); S0223 5's Mediator (W-06); S0221 14's tādātmya and paramparā; S0222 14's H/INV prefixes; S0222 5's pramāṇa tension |
| **JUSTIFIABLY EXCLUDED** | the categories, fallacies and three conditions as reasons; the source defects |

My open item S0221-15 (order anomaly) is **resolved, Int**: "the previous Tarka extraction" matches S0220 L45 ("Reasoning is a governed transformation"). It is not a defect of §8.37.

**New fork:** "supported, warrant not recorded" as a display class, or a warrant field (with G7 and the reasons gap).

**R2** (scope 08.37; tiers A–C 0; tier D judged):
- **"warrant"** needs qualifying: the inference relation vs the evidence entry (W-02).
- **"compute"** (L42): acceptable as "a function of".
- Acceptable as ordinary words: inquiry, reversed, deleted, annulled, revoked, return, sentence, again, beside, example, rival, keep.

**R3:** §8.37 C1–C4 have no claim-level citations. C5 is cited at §8.38 L114, and C6 at §8.38 L22, L107 and L116.

## C43 settlement (`cf10a2236`) and T9-16 (b)
- Rows W-01 to W-09 match §8.36 (title, plain language, C1–C6, the challenges, §8.36.5), §8.35 C4 (i-a) and plain language 4, §8.37 C2 L58, §8.38 L16 and L43, and the §10.2 G7 row.
- **One gap:** W-07's narrowing was not carried to §8.37 C4 (W-05).
- The §8.37 header ("C42 settlement, pending") is stale.
- **T9-16 (b)** bears on C4 only (W-07).

## Next steps (dependency order)
1. Restate C1 per W-01 and W-02.
2. Restate C3 per W-03 and W-09, and check §8.38 L78.
3. Restate C2 per W-04 and W-06.
4. Restate C4 per W-05 and W-07.
5. Restate C5 per W-08 (R3: §8.38 L114).
6. Add the coverage rows of W-10, and the fork.

## Limits
- I read §9.13, §9.10, §10.2 and §10.1 by grep and sed only. I read §9.18 L1–72, L100–113 and L186–222. I did not run ce_model or the quote tool. I did not open S0217, so C6's S0217 relays are unverified.
- **Process independence only.** I did not write §8.37. W-05 and W-04 check the propagation of corrections I proposed at C43 (W-07, W-05), so on those two my check is not independent.

## Appendix
- **C6:** "reverses a correction" overstates. S0221 L546–548 relays the pre-correction wording of the earlier "Gita/Vedanta lens", while S0222 L463 is neutral. The technology choice is hedged ("probably", S0221 L422).
- **§8.37 C1's relay** omits S0222 L188 and L235 ("Inference Rule", "General Rule"), which already carry a warrant in this window.

## Files opened
Under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/`:
- `manuscript/review/C44-S0221-S0223/`: BRIEF-W-STEP2.md, SHA256-STEP1.txt, STEP1-INVENTORY.md (whole), STEP8-AND-CHECKS.md
- `manuscript/review/C43-S0218-S0220/`: SETTLEMENT.md, REVIEW-REPORT.md
- `manuscript/chapter_08/`: 08.37 (whole) and 08.36 (whole)
- `manuscript/chapter_09/`: 09.18 (L1–48, L60–72, L100–113, L186–200, L209–222; grep), 09.13 (grep), 09.10 (grep), 09.17 (grep)
- `manuscript/chapter_10/`: 10.02 (L169–176; grep), 10.01 (grep), TODO.md (grep)
- `CONCEPT-REGISTER.tsv`: rows CON-0141, CON-0188 and CON-0221

Corpus files, each checked on CORPUS-ALLOWED.tsv first:
- S0220 (L9–12, L40–50, L286–291; grep)
- S0219 (L294–297)
- S0218 (L549–566, L762–816)

## Commands run
- `cat` of the brief; `sha256sum` and `cat` of SHA256-STEP1.txt; `wc -l`; `ls` of the C43, C44 and chapter 9/10 folders and of tmp/tools; `git log --oneline` (-1 on the brief; -8)
- `git show --stat` of `cf10a2236`, `8864c11e7` and `ed29e5957`; `git show 8864c11e7 -- 08.37`; `git show cf10a2236 --` on 08.00, 08.37, 08.99, chapter_10, 08.35 and 08.38
- `grep`/`sed -n` on 09.18, 09.13, 09.10, 09.17, 10.02, 10.01, chapter_10/TODO.md and CONCEPT-REGISTER.tsv
- `awk` on CORPUS-ALLOWED.tsv for S0217–S0220; `grep` and `sed -n` on S0218, S0219 and S0220 at the lines above
- `python3 -I tmp/tools/undefined_terms.py --scope 08.37`
- `python3 -I tmp/tools/inbound_citations.py "§8.37 C1"` … `"§8.37 C6"`, and `"§8.35 C4"`

Nothing was edited, and nothing was sent to an outside service.
