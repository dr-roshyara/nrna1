C109 REVIEW REPORT

Window C109, S0393–S0396, transition review of §8.103 (draft), its index row, and STEP8-AND-CHECKS. Reviewer W (Opus). Before comparing, I re-read my saved inventory. My independence is process independence, not personal independence.

**Defects in my own step 1, found now:**
- I missed S0394 L921 ("The Chinese lenses do not expand the Kernel — they confirm it."). My read stopped at L920. The file has 921 lines, and the last one has no final newline.
- I cited the SNF line in S0393 as l.61. It is L60.
- I gave S0394 as 920 lines. It has 921.

Neither defect changes any finding below.

## 1. Assessment
§8.103 is reliable on the one-voice and lineage points, on the authority boundary and on "admission not final". Its largest risk is in two trivial-case readings:
- C3 counts S0396's "preserve" verbs as the must-do half. They are must-nevers.
- C1 states full agreement on Wu (absence without invention). C107 had already limited the S0383 version of that claim to partial agreement.

Four of the book references are wrong or cite the wrong claim (CON-0105, §9.1 C2, §8.83 C3, §8.92 C1).

## 2. Findings

| W | Sev | R/N | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|---|
| W-01 | high | N | C3: the refusal-defined kernel's DOES list is "the must-do half, which keeps it from the trivial case" | S0396 L618–625: every item is preserve, enforce or maintain; no item accepts an input or answers a question. Preservation is a safety property, met by ∅ (§9.1 C1). T7 (§9.19.6): ∅, refuse-all and noop satisfy P and fail Req_O. Req = (Q, O): O1–O4 are inputs to accept (CON-0188). The book excludes trivial systems by O1–O4 and Q1 (§8.42 C1 L43). | "The list pairs refusals with preservations. Preservations are must-nevers (§9.1 C1), so the list is still met by refuse-all or noop (T7). It names no input to accept (O1–O4) and no question (Q1–Q4); admit and record are absent." | 08.103 L59 |
| W-02 | high | R | C1: "Wu's question agrees within D"; plain language: "agrees with the book" | Wu's absence list includes no evidence, no agency and no justification, each leading to NOT CREATED (S0394 L243–266). S0394 L237 quotes S0383 L125 word for word, and for that sentence §8.101 C1 found only *partial* agreement (WA W-12). Within D, an item with standing and no evidence exists, shown as "not attested" (§8.93 C6, Zero case 2; §8.87 C2). An item with entries and no adoption exists, with F (§8.92 C4; §9.13 L129). §8.91 C6 records "refusal = no claim" as the collapse the source warns against, not as a success. | "Partial agreement. For a refused input D creates nothing (§8.91 C6), and §8.91 C6 records that as the gate-refusal / NO CLAIM collapse. For no evidence or no adoption, D does record entries ('not attested'; F). L237 restates S0383 L125, which §8.101 C1 (WA W-12) reads the same way." | 08.103 L14, L20, L35; index L118 |
| W-03 | high | R | C2: "§9.1 C2; CON-0105: preservation does not imply the required capabilities" | CON-0105 is "portability of parts ≠ sufficiency of the whole", homed at §9.2 C1. §9.1 C2 carries CON-0100, which is INVALID as the kernel type. The must-do half is CON-0188 (Req = (Q, O)), inside the kernel type CON-0183 (§9.1 C6). | "§9.1 C1 (CON-0099): a must-do is needed beside the must-nevers. The must-do is Req = (Q, O) (CON-0188; §9.1 C6, CON-0183)." Drop CON-0105, and drop §9.1 C2 or cite it with its INVALID status. | 08.103 L15, L43; index L118 ("as §9.1 C1–C2 do") |
| W-04 | high | R | C2: the capacity ceiling "restates S0357's 'incapable of' (§8.83 C3)" | The ceiling is treated in §8.83 C5, not C3 (C3 is the K1–K8 lists). §8.83 C5 (W-10) says: "The ceiling forbids what the relocation test only permits the kernel to omit; nothing the book derives forbids it." S0395 L385–386 goes further: a realization with semantic authority is "architecturally invalid even if … technically correct". | "Restates S0357's ceiling (§8.83 C5), which the book holds is stronger than anything it derives. S0395 makes it a validity condition. Whether the kernel must be *incapable* is undecided in the book (see item 7)." | 08.103 L15, L45 |
| W-05 | normal | R | C1, C3 and the challenge cite "§8.92 C1, a formula of one origin counted once" | §8.92 C1 is the six-part aggregate claim. "Counted once" is in §8.92's lineage paragraph. | Cite "§8.92 (lineage)", or rely on §8.31 C4 and §8.33 C3 alone. | 08.103 L31, L58, L70 |
| W-06 | high | N | C3 and its challenge: "three answers to one line of prompts … prompts one adviser wrote" | No prompt to any provider is in the window, and Kimi's report is unread. The matrix is the adviser's own coding, and it contradicts the adviser's text: knowledge/action is marked ✓✓✓ (L498) although L152–208 reject it; the reasoner row is "strong" (L495) against "implied" (L501). | Mark it Int: "a shared prompt author is not shown here. The grades are one adviser's coding of unread reports, and they disagree with the adviser's own text. That makes it one line of support regardless of who wrote the prompts." Challenge adequacy: **inadequate, premise missing**. | 08.103 L22, L58, L70 |
| W-07 | normal | R | C2: the boundary principle "is the book's ownership test (§8.94 C1; §10.3)" | "Ownership" is a homonym here. In §10.3 (CON-0132; §6.5.2 L29–31), the owner is the holder of the act that may amend the invariant. §8.94 C1 calls the "change independently" question the estate's own, unanswered by its source; it says the book's boundaries join a check to its append (K4, K6). | "Agreement in form with K4 (a consistency boundary, §8.91 C1), not with ownership in §10.3's sense." | 08.103 L44 |
| W-08 | normal | N | C2 omits the ADR's empty must-do class | §8 class 1 has no member, and the command model is undecided (S0395 L439). "Smallest" (L553) is used without a fixed Req, A or pool (§9.19.8; §8.83 C5). "Justified by an existing domain invariant" (L350) merges necessity and placement, which RESEARCH-PURPOSE L50–52 keep as separate tests. | Add: "the ADR separates the classes but fills only the prohibitions. As written, refuse-all meets it (T7)." | 08.103 L43 |
| W-09 | normal | N | S0395 §2 and §7 and the drift in the kernel tree are not covered | The four senses of "Kernel" (L22–33). The Verification Port as "sole inbound admission gate", and ConflictRecord as a separate aggregate (L311–328). The tree changes across sources: S0383 L66–76 {KnowledgeAggregate, ConflictRecord}; S0393 L56–73 adds Lifecycle, SNF, JustificationPath and Context; S0395 drops Lifecycle and adds the port. | Coverage and change-over-time lines. Homes: §8.102 C1 (the gate as enforcement; K1 in form), §8.88 C2 (iii) (ConflictRecord has no counterpart within D), §10.1 L28 (kernel as a homonym). | 08.103 §8.103.3 |
| W-10 | normal | R | C1: SNF in the aggregate is "a contradiction inside the conversation", against S0395 L185 | S0393 itself lists the "SNF engine" as a mechanism (L84), so it separates the value from the engine. S0395 L185 excludes "SNF *implementations*", the engine. The contradiction holds against S0383 L831 and L1066 only. | Qualify the claim. Add that within D meaning-identity is an input of standing (§9.18.2), so a canonical meaning has no kernel field. | 08.103 L33 |
| W-11 | normal | R | C1: the adjudication layer is "restated" from S0383 | S0393's tier drops Negative Epistemology from S0383's five (S0383 L1054–1060 against S0393 L41–46). | Call it "restated with one change". | 08.103 L32 |
| W-12 | normal | R | C1: the naming test agrees "by exactly these marks"; the REJECTED example is unmapped | The book separates homonyms by meaning, case by case; the four marks are the source's own. The five senses of REJECTED (S0394 L109–117) have a home: §8.91 C6 "rejected: a homonym" (§1.6 L51; T0-07). L641 belongs to part (B), not (A). | Change to "agreement in form". Map REJECTED to §8.91 C6. Attribute L641 to (B). | 08.103 L34 |
| W-13 | normal | R | C1: Yin–Yang is mapped on one branch only | The four-way split (S0394 L163–171): CONFLICT maps to contradictory entries giving U with a reason (§9.15 G6; §8.102 C4); "different meanings" maps to separate items; UNKNOWN maps to U. (B) L653 ("separate identity") disagrees with (A) L170 ("relation"). | Map all four branches. Record the (A)/(B) inconsistency. | 08.103 L36 |
| W-14 | normal | N | C3: hidden reasoner and contract authority | S0396 L283–284 also asks about relevance and meaning; the draft answers sufficiency only. Relevance is the producer's recorded claim (§9.18 L111). Meaning is the identity link, an input (§9.18.2). "Who may establish or change" the contract is the owner/amend question (CON-0132; §10.3), not designation (§9.17) or rule version (U4). | Add both mappings, and re-home the authority question. | 08.103 L54–55 |
| W-15 | normal | N | Counts and fence (source and draft slips) | S0396 has 640 lines and ends with a newline. Draft and STEP8 say 641, and their per-file counts sum to 2,758, not 2,755. S0395's ````markdown fence opened at L7 closes at L128; L559–576 is a separate block. The draft says "fenced block (L7–559)". | Correct the numbers. Not relied on. | 08.103 L9, L8; STEP8 L7 |
| W-16 | normal | R | C3 omits S0396's identity split | L31–39: KnowledgeId ≠ meaning ≠ status ≠ relations. | Home: §8.102 C5, §8.93 C2, T9-14 (agreement in direction). | 08.103 §8.103.3 |
| W-17 | normal | R | Scope of T7 and missed homes | T7's own caveat is omitted: "not excluded beyond s₀: the one-witness system", scope B with K1 and one record. §8.42 is cited without a claim label (it is C1). §8.89 C3 ("a minimum that only forbids") is missed. | Add all three. | 08.103 L43 |

**Counts:** 17 findings: R 11, N 6. Critical 0, high 6, normal 11. Material omissions: W-08, W-09, W-14.

## 3. Verdicts
- **C1: PROVISIONAL.**
  - The one-voice point is VALIDATED. It restates §8.33 C3 and §8.31 C4.
  - The naming test is VALIDATED as agreement in form.
  - The Wu claim is INVALID as stated (W-02).
- **C2: PROVISIONAL.**
  - The separation of prohibitions from capabilities is VALIDATED in direction against §9.1 C1 and T7.
  - Its homes are wrong (W-03, W-04).
  - The ADR's own empty must-do class is unreported (W-08).
- **C3: PROVISIONAL.**
  - The authority boundary and "admission not final" are VALIDATED within D.
  - The hidden reasoner is PROVISIONAL (W-14).
  - The one-origin claim is valid in conclusion, but its premise is not shown (W-06).
  - The refusal-defined kernel is INVALID as stated (W-01).

## 4. Coverage

**Questions 1–7 of the brief:**
- **Quote check, by hand:** all 12 verbatim quotations match the sources (S0393 L75, L375; S0394 L133, L921; S0395 L99–101, L417, L749; S0396 L85, L136, L250, L371, L461). I also checked the in-meaning line references for C1–C3; they hold, apart from those in W-12 and W-15.
- **R2:** the tool reported tier A 0 and 34 tier-D candidates in scope. I judged each one: all are plain words or terms attributed to the source (for example "complementarity", "hidden-reasoner", "finality"). None needs a definition.
- **Lineage:** S0393 reads S0383. Its mechanism list (L81–88) matches S0383 L1062–1071, and "boring" restates S0383 L1085. S0394 (B) restates (A). S0395 is an ADR inside a reply. S0396 quotes an unread report. The draft's lineage note is correct on all four.
- **Changes over time the draft misses:** the kernel tree (W-09), and Wu, which S0393 reads as generative (L320–338) and S0394 as non-invention.
- **Scope labels** ("within the estate and D"): appropriate.
- **Item 7, presented as decided:** only the ceiling, read as agreement (W-04). Whether the kernel must be *incapable* is a normative question, and the book has not decided it. The ADR's "approved development sequence" (L456) is the estate's wording and the draft does not adopt it.
- **No new topic.**
- **Candidate fork for the map:** whether the kernel must be incapable, or may merely omit (W-04).

## 5. Summary
1. The book's lineage discipline holds across all four sources.
2. Two trivial-case readings fail against the book's own T7 and §9.1 C1: S0396's preservation list, and Wu as full agreement.
3. Four references are wrong: CON-0105 is §9.2's concept; §9.1 C2 is INVALID as the kernel type; the ceiling's home is §8.83 C5, and its verdict there is the reverse of agreement; §8.92 C1 is cited for its lineage paragraph.
4. The one-origin conclusion on the providers stands, but its stated premise is unshown.
5. The ADR's must-do class is empty, and its §2 and §7 (port, ConflictRecord) are uncovered.
6. Fix W-01 to W-04 and W-06 before settlement. The index row inherits W-02 and W-03.

## 6. Limits
- `corpus_quote_check.py` was not run: it needs `02-FILES.jsonl`, which is not on the copy list. I did the quote check by hand instead.
- R2 was run with `--no-corpus`.
- I did not open §8.88, §8.87, §9.18, §6.5 or §10.1. I relied on the book's citations of them as quoted in §8.91, §8.93, §8.101 and §8.102, so those mappings are reported, not shown.
- I did not open the providers' reports.
- Nothing was refused.

---

**Files opened.** All paths are under /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/.
- **Read whole:**
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C109-S0393-S0396/BRIEF-W-STEP2.md
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C109-S0393-S0396/STEP1-INVENTORY.md
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C109-S0393-S0396/STEP8-AND-CHECKS.md
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_08/08.103-…md
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_08/08.102-…md
  - docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_08/08.101-…md
- **Read in part (awk, grep):** COPY-LIST-STEP2.txt. The four sources under docs/knowledgeos/brainstorming/kernel/ (single lines, tails, fence lines). S0383, docs/knowledgeos/brainstorming/20260823-205735-knowledgeos-lens-system-twenty-six-lenses-consolidated.md (L60–90, L972–1103, grep). In chapter_08: 08.33 L60–80; 08.31 L85–95; 08.92 L1–38; 08.93 L47–96; 08.91 C6; 08.94 C1; 08.83 C3, C5; 08.95 C4; 08.89 C4 and claim heads; 08.42 claim heads and grep; 08.00-index L117–118. In chapter_09: 09.01 L14–33, L88–100; 09.19 (grep); 09.13 (grep); 09.17 (headings); 09.20 (grep). In chapter_10: 10.03 (grep). CONCEPT-REGISTER.tsv (rows 0099, 0100, 0105, 0110, 0132, 0175, 0183, 0188, 0221). THEORY-MAP.md (grep). RESEARCH-PURPOSE.md (grep). CORPUS-ALLOWED.tsv (grep, S0383 row 1713: match).

**Commands:**
- `cat` and `wc -l` on the copy list.
- `grep -F` on the copy list for named sections.
- `wc -l` on 21 named book files.
- `awk 'NR==n'` and `tail -c | od -c` on the four sources.
- `grep -n` for fences in S0395.
- `grep` and `awk` on S0383.
- `awk` and `grep` on the named book files and the register.
- Copied the files of COPY-LIST-STEP2.txt one by one, with the brief's `while read` loop, to …/scratchpad/c109-w/copy/.
- `python3 -I inbound_citations.py --root <copy> CON-0105 "§8.83 C3" "§8.92 C1"`, output in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c109-w/c109-w-inbound.txt.
- `undefined_terms.py --help` (it crashed, because corpus mode needs the allowed list), then `undefined_terms.py --no-corpus --scope 08.103`, output in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c109-w/c109-w-r2.txt.
- `corpus_quote_check.py --help` (it crashed for lack of 02-FILES.jsonl; not run further).

No git command, glob, recursive search or directory listing was used. Nothing in the repository was edited, and no text went to an outside service.
