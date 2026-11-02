CONSOLIDATION S0328–S0352: REPORT A

Reviewer A. Scope: Ch. 6 §6.00–§6.07, §6.99, TODO; Ch. 8 §8.00–§8.09, Ch. 8 TODO. Object: the frozen copy BOOK-UNDER-REVIEW only.

## 0. Method notes
- The copy's own `tmp/tools/` holds only `book_search.py`. R1, R2, R3 and the quote check were run from the working tool folder, each with `--root <copy>` from its first call. `undefined_terms.py` ignores `--help`, so that call produced the whole-book list.
- Quote check: 211 quotes in my sections, all found at the cited lines. It checks only quotes written as `"…" [Snnnn Lx]`; meaning-level claims were spot-checked by hand.
- R1: one flag (A-08).
- Reported results (tool output): every `§X.Y Cn` pointer in my sections resolves to an existing claim except one (A-02). Every `§X.Y.Z` heading resolves except one (A-07).
- Computed, not reported: the reference tables of §6.1–§6.7 cover every cited `Snnnn Lx`; the tables in §8.2, §8.3, §8.6–§8.9 do not (A-17).

## 1. Findings
Severity: critical / high / normal. I found no critical finding.

| ID | Section | Finding | Sev | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | §6.5 C0, C6 hold, §6.5.4 (F22, F4 bullets), §6.5.5 last bullet | The section says F22 is open and that "C6's kernel reading is on hold until F22 is settled". F4 is listed as an open question. The map and outline record both as decided: F4 "decided by the Principal at theory turn 4 (2026-10-20): constitute, within the kernel"; F22 "decided (C + O) (the Principal, 2026-10-21), §9.16 R4a". | high | THEORY-MAP L126, L141, L436; BOOK-OUTLINE L58; §9.19 L57 ("F22 is decided (C + O)"); §9.10 L230; §1.13.1-2 | Add the dated decisions and homes. Say what C6's Hyp now rests on. Drop "on hold" and "until F22 is settled". Keep the open K4 residue only if the map still holds it. | §6.5 C0: THEORY-MAP L141 and BOOK-OUTLINE L58 already state the decision (unchanged); §9.10 L230 and §9.19 L57 are already "conditional on F22 … decided" (unchanged); 85 section-only lines cite the section, not the hold. §6.5 C6: no claim or concept citation; 87 section-only lines. | No line in §6.5 reads F22 or F4 as open without a dated pointer |
| A-02 | §6.3 C5 (L51) | "Under the lattice of §6.1 C9". §6.1 has C1–C8 only. The lattice is §6.1 C3. The pointer is stale; no content is missing. | normal | The pointer script found no §6.1 C9. §6.1 C3 holds the lattice. | "§6.1 C9" → "§6.1 C3" | Only §6.3 L51 changes. §6.1 C3 citations are unaffected. | Pointer resolves |
| A-03 | §8.6 C2, trivial-case test | "If B(a) = ∅ … the comparison reports exactly B(b)". The proof two bullets above, and CON-0126 ("at least the symmetric difference"), say the reported set only contains B(a) Δ B(b). "Exactly" holds only when detections give identical output. Step table: (1) a detects, b misses → outputs differ (definition of miss) → d reported; (2) both miss → same output → not reported; (3) two detections with different outputs → also reported, so the reported set is a superset; this is the idealisation the section itself notes. | normal | §8.6 L43–46; register CON-0126 | "reports at least B(b), and exactly B(b) when detections give the same output" | BOOK-OUTLINE L41, L148 and the register already say "at least". §8.23 L26, L112 cite the concept only. No line quotes the "exactly" form. | Wording matches the proof |
| A-04 | §8.1 (reach register paragraph) | "Contamination reaches (#11, #12)". The source heading says "14 reaches, 3 of them contamination". #11, #12 and #13 are marked contamination in the register. #13 is the brief supplying the sequence, which the section's own lesson 1 treats as contamination. | normal | S0025 L62 and the register rows #11–#13 (L62–L80) | "#11–#13" (and say #13 is the brief's contamination) | §8.1 has no claim or concept citations. 13 section-only lines were not read for this figure. | Count and list agree |
| A-05 | §8.8 home table, C2 heading, §8.8.4 | The table and C2 bullet say "third and fourth instance". C2's own count is "Nine cases in one estate". §8.8.4 says "Re-tallied as ten cases (finding 8)". The text keeps a retracted framing. Count check: 6 (§8.5 C9) + F-1, F-2 + S0072's 13 vs 15 = 9; the "6 of 37" and "37 of 37" are two classes and are excluded. | normal | §8.8 L15, L46, L52, L98 | Rename the bullet "new instances". State "nine" in §8.8.4 or mark "ten" as superseded by I-13. | §8.5 C9 inbound: §8.8 L15, L45, L52; §8.99 L51 | One count, one wording |
| A-06 | §8.8.1 item 3 vs C3 | The plain-language text says the practice of who may correct "changed". C3's Int and §8.8.4 say the two cases "do not differ on the forward rule". They differ on a grant and on role against process. | normal | §8.8 L27, L58, L97 | Reword item 3 as "differs by grant and by role against process". | §8.8 C3: THEORY-MAP L144 (F25) already matches C3. 34 section-only lines. | Plain language matches C3 |
| A-07 | §8.7 C2 | "a map in §0.5.2 (S0164 L2603)". The book has no §0.5.2 (Ch. 0 §0.5 is the invariants). It is S0164's own section number, which reads as a book pointer. | normal | Heading script | "S0164's §0.5.2" | None cite this phrase. | Unambiguous |
| A-08 | §6.5 C1 tag | `[CON-0133 · merged into ARCH-INV-1]` has no status word. The register says INVALID. This is R1's H4 flag. | normal | R1 output | `[CON-0133 · invalid, merged into ARCH-INV-1]` | Register L134, L136 and THEORY-MAP L281 (consistent: "✗ as new"). The 11 claim lines (§9.10, §9.19, BOOK-OUTLINE) cite §6.5 C1, not the tag. | R1 flags 0 |
| A-09 | §8.5 header, C9 header | "provisional, not yet reviewed". C9 was corrected at the consolidation S0182–S0200 (I-16) and is read here. | normal | §8.5 L3, L62, L65 | Restate the status. | §8.5 C9: §8.8 L15, L45, L52; §8.99 L51 | Status current |
| A-10 | §8.3 C6 | "(Src; S0043, not yet in the book)". §8.3's own header says S0043 was "read before under Trial T1, checked here against the book". Ch. 3 §3.9 (L3) and Ch. 3 index L6, and Ch. 2 §2.10 L44, use S0043. | normal | Those lines | Drop the phrase. | §8.3 C6: 6 section-only lines | No contradiction |
| A-11 | §6.6 C6 | "S0913's distinct permissions (observe, assert, derive, approve, execute; §8.6)". §8.6 does not contain them. S0913 is not in §6.6's sources or references. The content exists in the source: S0913 L1351–1375, "Epistemic permissions" (CanObserve … CanExecute). The pointer is stale; content is not missing from the corpus. | normal | S0913 L1351–1375; §8.6 cites only L238, L274, L1485–1534 | Cite S0913 L1351–1375 in §6.6's references. Drop "§8.6". | §6.6 C6: 0 claim or concept lines; section-only lines are not affected | Pointer and reference agree |
| A-12 | SEQ: Ch. 6, §8.5–§8.9, Ch. 8 index | Ch. 6 §6.5 C0, §6.5 C2, §6.6 C2 and §6.6 C8 use K3, K4, the evaluator CE, F22 and "deliverable 3" (Ch. 9 constructs) with no gloss. §8.9 C7 uses K3 and §9.6.6a the same way. §8.5 C7, §8.6, §8.8 and §8.9 point forward to §9.4 and §9.6. The Ch. 8 index lists by-address Ch. 9 use only from §8.18 on. Ch. 6 has no such note. §8.6.2's nine-factor list takes two factors from §8.10 C6 and §8.1 (a later section). No circularity; the forward uses are not announced. | normal | §6.06 L47; §8.00 L99; §8.06 L36 | Extend the by-address note (Ch. 6 index; Ch. 8 index for §8.5–§8.9). | None (a note only) | Notes present |
| A-13 | Stale index text | Ch. 8 index row 8.12 says "before review"; §8.12 L3 says "version 2, settled". Ch. 6 index L3 says earlier material "stays where it is until consolidation". Ch. 6 index marks §6.7 "(provisional)", but §6.7 hosts the VALIDATED CON-0147 and CON-0176. Review-trail notes remain in the prose of both chapters ("corrected after review C4 F-10/F-11", "C N8–N11", "restated after both consolidation reviewers"), although §6.99 records that such notes were moved out. | normal | §8.00 L28; §6.00 L3, L17 | Update the rows and the notes. | None | No stale status |
| A-14 | R2/homonym | Consolidation finding IDs (I-02, I-06, I-09, C-11, C-14, B-04…) share the form of the book's I-0…I-12 invariants (I-09 ≈ I-9) and of source IDs. S0091's C-14 and C-5 appear in §8.4 C3 and §8.9 C4. The Ch. 8 index uses these IDs without a gloss (L30, L44, L99; §8.06 L34). §6.05 uses DP-3, BC-5 and G-1 without a gloss. | normal | R2 tier B | One gloss line in each index ("X-nn after a consolidation range is that consolidation's finding ID"). Gloss DP-3, BC-5, G-1 at first use. | None | Glossed |
| A-15 | Derivation labels | (a) §8.2 C3, last bullet: "a 'refinement' without a recorded supersession is therefore a second authoritative home". The premise that the refinement carries standing is unstated (CON-0164). (b) §6.1 C1 is labelled Ded but is a sketch. M and r are unconstrained, so non-identifiability is trivial; no model of the declared-interface case is given. It should be labelled Int or sketch. (c) §8.6 C4: "Another distribution gives 0.857" is shown nowhere, and "recomputed in this session" has no record. I verified the other figures: LR 1.5 each, product 2.25, joint 50. (d) §6.4 C7 rests its unverified step on "repository instructions, not a corpus source", with no citation. | normal | Texts as cited; own recomputation of the §8.6 C4 distribution | State the premise (a). Relabel (b). Give or drop the second distribution (c). Cite or drop the premise (d). | §8.2 C3: 12 lines (CON-0164: THEORY-MAP L183; §10.1, §10.2; register; none quotes the "therefore"). §6.4 C7: concept lines in §9.5 L35, register L109, THEORY-MAP L182, BOOK-OUTLINE L137 are unaffected. | Premises explicit |
| A-17 | Reference tables | Cited lines missing from the tables. §8.2: S0038 L222. §8.3: S0045 L87. §8.6: S0164 L1789, L1998–2005, L2129, L2420–2424; S0058 L77. §8.7: S0056 L60; S0138 and S0145 not listed. §8.8: S0073 L82. §8.9: S0090 L17–28; S0091 L82, L92, L101, L102, L169, L237, L404, L648, L715, L761–767; S0089 L13, L106; S0087 L12. §6.1–§6.7 are complete. | normal | Computed (script `cons4-a-refs.py`) | Add the lines. | None | Tables complete |

What the evidence establishes: A-01 is a contradiction between §6.5 and the map, the outline and Ch. 9. The rest are local. What I did not establish: whether every meaning-level source claim is right. I spot-checked S0025 (L18, L22, L62–L80, L86, L108, L130, L169, L187), S0031 (L132–152), S0033 (L29–131), S0034 (L12–33, L111, L118, L196, L266), S0055 (L7, L57, L61–73) and S0913 (L1351). All matched except A-04.

## 2. Concept verdicts (SCOPE §2.2; the concepts homed in my sections)
**VALIDATED, second independent verdict**

| ID | Verdict | Reason |
|---|---|---|
| CON-0072 | VALIDATED | The definition matches S0028 L29. |
| CON-0075 | VALIDATED | The product order on {0,1}² (and [0,1]²) is a lattice. Tiers 2 and 3 are incomparable. The {0,1}³ and quotient caveats are stated. |
| CON-0087 | VALIDATED | A distinction. The limit is stated (canon's owner field covers the records only). The derived-pattern third kind is recorded as open. |
| CON-0089 | VALIDATED | Holds as a relation. Its counterfactual support is flagged as a limit. |
| CON-0102 | VALIDATED | The stated / held / enforced split is argued, with the "held" homonym and the I-3 case. |
| CON-0104 | VALIDATED | S0039 L133, with a labelled Int. |
| CON-0108 | VALIDATED (as the conditional) | Blocking enforcement needs a verdict set with no value covering both pass and block cases. The factual premise about S0036's placement check is unverified (A-15d). |
| CON-0120 | VALIDATED | An absence claim holds only within the stated search scope. §8.9 C3 shows the rule failing in practice, not refuted. |
| CON-0121 | VALIDATED (as a possibility claim) | The sufficiency case is confounded and the book says so. |
| CON-0126 | VALIDATED | Proof checked (A-03). Needs the A-03 wording fix; the statement itself is right. |
| CON-0127 | VALIDATED, scope R ⊆ F | The deduction is correct and short. The index of §8.67 says the premise fails there; the validated scope is the conditional only. |
| CON-0129 | VALIDATED (sufficient condition) | Both failures need an outside reference to i or j. |
| CON-0147 | VALIDATED | Needs a partition and dependency-identifiable relations. Mixed arrows block confirmation more than refutation. |
| CON-0176 | VALIDATED | Checked (a)–(c): the sound-rejection claim, the singleton-fibre confirmation, and the "constant on every fibre" criterion. |

**PROVISIONAL**

| ID | Verdict | Reason |
|---|---|---|
| CON-0058 | PROVISIONAL | The count (14, 3) is right. Self-review only; n = 1; the contamination list is incomplete (A-04). |
| CON-0059 | PROVISIONAL | Definitional via the lattice; the instance is self-judged. |
| CON-0060 | PROVISIONAL | One self-disclosed instance (#13). |
| CON-0076 | PROVISIONAL | §6.1 C1 grounds only condition 3. |
| CON-0103 | PROVISIONAL | One source. "Exercised as one programme" is history, not structure. |
| CON-0125 | PROVISIONAL | Nine factors. Model ≠ method is shown by a pair of countermodels. Orthogonality and completeness are not shown. |
| CON-0128 | PROVISIONAL | Product rule and copy case hold (figures recomputed). The rule itself is a heuristic. |
| CON-0130 | PROVISIONAL | The branching-process result is correct. The premise is unmet; no m is estimable. |
| CON-0132 | PROVISIONAL | "Reason lives in" is not operational. The two-owner I-4 case is unsettled. |
| CON-0140 | PROVISIONAL | One source; completeness is a hypothesis. |
| CON-0141 | PROVISIONAL | A lemma for the fold's output only (resolver and people are further readers; the interpreter can be swapped). |
| CON-0150 | PROVISIONAL | Limited to re-derivable claims; no test on self-declared content. |
| CON-0151 | PROVISIONAL | Monotonicity is near-definitional. Forks F32 and F35 are open. |
| CON-0152 | PROVISIONAL | A stated norm of one source; not observed to hold. |
| CON-0164 | PROVISIONAL | Premise missing (A-15a). |
| CON-0166 | PROVISIONAL | The circularity argument is sound as stated; not checked against S0045's full text. |
| CON-0172 | PROVISIONAL | Probably §1.10 + §1.16.6; the currency and implementation harms have no home yet. |

**INVALID cores, checked as restated (not re-litigated)**

| ID | Verdict | Reason |
|---|---|---|
| CON-0073 | INVALID | Not exhaustive; open as T6-01. |
| CON-0074 | INVALID | Restated by CON-0075. |
| CON-0085 | INVALID | A necessary list, not a decision rule. |
| CON-0086 | INVALID | Weakened by S0034 L33. |
| CON-0088 | INVALID | Non-equivalence holds; the gap is not the binding alone. |
| CON-0106 | INVALID | Items are classed twice; restated as CON-0164. |
| CON-0112 | INVALID | Restated as CON-0166. |
| CON-0119 | INVALID | Two axes (no ID for the valid core). |
| CON-0122 | INVALID | The binomial interval fails; the 13-row sample is purposive (S0055 L61–73). |
| CON-0133 | INVALID | Merged into ARCH-INV-1. The tag is repaired in A-08. |
| CON-0134 | INVALID | Restated as CON-0141. |
| CON-0139 | INVALID | Restated as CON-0172. |
| CON-0146 | INVALID | Restated as CON-0176. |

## 3. Coverage (pointers and TODO rows)
**Pointers.** Pointers from §6.1–§6.7 and §8.1–§8.9 to later chapters were checked mechanically for the claim and heading forms `§X.Y Cn` and `§X.Y.Z`: every one resolves except A-02 and A-07. Pointers of the form T/F/CON/DB IDs resolved (tested: T1-17/19/43/47/50, T2-05, T9-07/12/14–17/19/20, T10-01/21, N4-15, DB-2, DB-4, F10, F13, F13a, F14, F18, F22, F25, F32, F35, F4). I did not judge by hand whether each cited later claim says what the pointer says, except the ones listed in §1. The Ch. 9 and Ch. 10 sections named in the Ch. 8 index L99 exist. One out-of-scope pointer: THEORY-MAP L162 gives F10's home as §6.1 C3, but the SD-1 point is §6.1 C2.

| Item | Status | Home / evidence |
|---|---|---|
| T6-01 Ambiguous class | open | "Ambiguous" only in §6.1 and TODO |
| T6-02 evidence outside the platform | resolved in part | §6.3 C2 ("§6.1 C6's derived consequence, now stated by the corpus itself"); the TODO still asks for the check |
| T6-03 three evidence homes | resolved in part | §6.3 C2 reads them as a placement failure for records; derived patterns open |
| T6-04 trigger vs gate | open | no home found by search (a search miss is not absence) |
| T6-05, T6-11 (disposition CLOSE → source defect) | stale rows | still listed; close or move |
| T6-06 PKS → product arrow | open | §6.1 C8 holds the hypothesis; nothing found in Ch. 3 §3.8 by search |
| T6-07 counts of the self-error | open (unconfirmed) | not found in Ch. 4 §4.1 |
| T6-08 R-42 | open | Ch. 2 §2.8.8 uses R-42 as the registry's scope ruling (another source); the S0031 L125 membership criterion is not found |
| T6-09 three-level rule | partly recorded | §6.2 C3, §6.3 C4 |
| T6-10 two ownership schemes | open | §6.2 C2a records three standings only |
| T8-01 raw record | open | no later source locates it |
| T8-02 factor → blind-set bridge | open | §8.6.2 remains Hyp |
| T8-03 eligibility ρ | open | instances at §8.27, §8.28 |
| T8-04 | resolved 2026-10-27 | as recorded |
| T8-05 inventory items | partly stale | item 7: "integration event" at §1.16 L87 (a definition only); item 1: R-CONFLICT at §8.26 L48–50 and §9.8 L104, L139 (ownership by Evidence not found); no per-item status given |
| SEQ-0013 | resolved | as recorded |

**R2 (`--scope 06,08.00-08.09`). Every candidate judged.**

| Words | Judgement |
|---|---|
| adviser | Needs a gloss. First use is in the §8.41 index row (L57), 94 uses in 19 sections, and it is a role term (an assistant's unrecorded voice). Normal; add to the index or glossary. |
| Tier A, ordinary words (answerer, asker, swing, refuser, codifying, defaulted, misrouted, serially, attestor, rater, workbenche [from "workbenches", S0086's label], idealisation, undercounting, undeclarable) | Ordinary or defined by context. "eponymou" is a stem of "eponymous", which the index itself glosses (L5). |
| Tier B, source labels (AST-015, AMD3/4/5/6, CL-4, W-5, C-04, G-1, V-2, DP-3, DI-1, OQ-10, BC-5, C-3, LCOM4) | Defined or glossed inline only for V-2, DI-1, LCOM4, OQ-10. DP-3, BC-5, G-1 and AST-015 (first use in index) lack a gloss (A-14). The others are source-local labels, readable in context. |
| Tier B, consolidation IDs (I-02, I-06, I-09, C-11, C-14) | Homonym risk (A-14). |
| Tier D, coined compounds (dependency-identifiable, within-rater, counter-practice, class-wide, self-counted, write-capable, high-grade) | Self-explaining or defined in the line. |
| Tier D, technical or ordinary words (underdetermined, replication, regression, erratum, snapshot, calibrated, material, ready, falsified, likelihood, ratio, node, channel, tactical, equivalent) | Defined by context or standard. No defect. |
| The other ~90 tier D words | Ordinary English; the engine lists them only because they head a line. No defect. |

**Coverage of sections.** Read in full: §6.00–§6.07, §6.99, Ch. 6 TODO; §8.00 (all 115 lines), §8.01–§8.09, Ch. 8 TODO. Not read: any other section except by grep or sed for pointer checks, as listed below. Windows C80–C88's inventories were not opened (not needed for these sections).

## 4. R3 (summary)
Each correction's inbound lines are given in the table (section 1). Citations that name only a section ("section-only") were counted and listed by the tool; I did not read them individually. A-01: 85 and 87 section-only lines. A-03: 81. A-05: §8.5 C9 31 and §8.8 C2 25. A-06: 34. A-10: 6. A-15: §8.2 C3 12 lines. Beyond what the table states for each correction, no inbound line needs a change except the BOOK-OUTLINE L58 home pointer if A-01 adds a dated line.

## 5. Summary
1. Ch. 6 and §8.1–§8.9 are mostly reliable within their stated scope. All 211 quotes check, and every claim-level pointer resolves except two.
2. The main defect (A-01, high) is §6.5 treating F22 and F4 as open while the map, the outline and §9.10/§9.19 record both as decided on 2026-10-20 and 2026-10-21.
3. Eleven normal findings concern wording and labels: one stale pointer (A-02), one proof-wording slip (A-03), one omitted contamination reach (A-04), a retracted framing left in §8.8 (A-05, A-06), an ambiguous pointer (A-07), a tag without a status (A-08), and stale status lines (A-09, A-10, A-13).
4. §6.6 C6 cites a source not in its references, via the wrong section (A-11).
5. SEQ: Ch. 6 and §8.5–§8.9 use Ch. 9 constructs (K3, K4, CE, F22) without the by-address note that Ch. 8 carries from §8.18 on (A-12).
6. Reference tables in §8.2, §8.3, §8.6–§8.9 omit cited lines (A-17).
7. R2: one word needs a gloss ("adviser"); consolidation IDs collide in form with the book's I-n invariants and S0091's C-14 (A-14).
8. Verdicts: 14 VALIDATED confirmed (CON-0108 and CON-0127 only as conditionals); 17 PROVISIONAL kept with reasons; 13 INVALID cores checked as restated.
9. TODO: two Ch. 6 rows are partly resolved at §6.3 C2 (T6-02, T6-03); two rows (T6-05, T6-11) are closed in the triage but still listed; T8-05 is partly stale.
10. Not covered: whether every meaning-level source claim is right (spot checks only); the C80–C88 inventories.

Process independence: I had no access to other reviewers' reports or to the Professor's working files. I share the model family with the author, so this is process independence, not personal independence.

## 6. Everything opened and run
**Files opened (Read or cat):** `COMMISSION-A.md`, `REVIEWER-NOTE.md`, `SCOPE.md` (in `06-theory/manuscript/review/CONSOLIDATION-S0328-S0352/`); `03-methodology/REVIEWER-INSTRUCTIONS.md`.
In the copy, `06-theory/manuscript/chapter_06/`: 06.00, 06.01, 06.02, 06.03, 06.04, 06.05, 06.06, 06.07, 06.99, TODO.md.
In the copy, `06-theory/manuscript/chapter_08/`: 08.00, 08.01 to 08.09, TODO.md.
By sed or grep or the tools (not read in full): the copy's `THEORY-MAP.md` (L126, L141, L144, L162, L281, L413, L436), `CONCEPT-REGISTER.tsv`, `BOOK-OUTLINE.md` (L58, L41, L148), `chapter_09/09.10`, `09.16`, `09.19`, `chapter_08/08.10`, `08.12`, `08.23`, `08.26`, `chapter_05/05.07`, `chapter_02/02.08`, `02.10`, `chapter_03/03.00`, `03.09`, `03.10`, `chapter_01/01.13`, `01.16`, the TODO files of Ch. 1, 2, 9, 10, and the other chapter files that the grep and pointer scripts scanned.
Corpus (allowed list only): S0025, S0031 (L132–152), S0033, S0034, S0055, S0913 (L1351–1380); plus whatever `corpus_quote_check.py` and `undefined_terms.py` read themselves through `02-FILES.jsonl` and CORPUS-ALLOWED.tsv.
I did not open `00-access/`, sealed, hold-out or quarantine files, the working `chapter_*` files, working THEORY-MAP, CONCEPT-REGISTER, BOOK-OUTLINE, BASELINE-REVISIONS (the copy's own copies of these were read, not the working ones), or review folders other than the commission folder. I ran no git command.

**Commands and searches run:**
- `ls` and `find -maxdepth 4` of the copy, and `ls` of the working `tmp/tools` (names only).
- `wc -l` on my section files.
- Tools, each with `--root <copy>`: `register_home_check.py`; `undefined_terms.py` (once with `--help`, once with `--scope 06,08.00-08.09`); `corpus_quote_check.py` on §6.01–§6.07 and §8.01–§8.09; `inbound_citations.py` for §6.5 C0, §6.5 C1, §6.5 C6, §6.3 C5, §6.4 C7, §6.6 C2, §6.6 C6, §8.1, §8.2 C3, §8.3 C6, §8.5 C9, §8.6 C2, §8.6 C4, §8.7 C2, §8.8 C2, §8.8 C3, CON-0133. `book_search.py --help` was run (without `--root`, before the root rule was applied to it: it returned only its usage and "0 concepts"); no book search was made.
- Scratch scripts in `…/scratchpad/cons4-a/` (read-only on the copy): `cons4-a-ptr.py`, `cons4-a-ptr2.py`, `cons4-a-refs.py`, `cons4-a-head.py`; outputs `cons4-a-r1.txt`, `cons4-a-r2.txt`, `cons4-a-r2help.txt`, `cons4-a-quote.txt`, `cons4-a-r3-*.txt`; the text copies `cons4-a-ch8a.txt`, `cons4-a-ch8b.txt`, `cons4-a-ch8c.txt` are in the scratchpad root.
- Register script: concept IDs of SCOPE §2.2 filtered by `book_location`.
- Greps and seds on the copy for: TODO IDs (T6-xx, T8-xx), R-42, "Ambiguous", "trigger", F-IDs, T-IDs, S0043, S0577, S1524, S0913, "observe, assert, derive", R-CONFLICT, ADR-KOS-002, "integration event", "raw record", "scratchpad", "F22" in §9.16 and the map, "§6.5 C0" inbound lines.
- Seds on S0025, S0031, S0033, S0034, S0055, S0913.
- CORPUS-ALLOWED.tsv lookups for S0025, S0031, S0033, S0034, S0038, S0041, S0043, S0045, S0055, S0073, S0075, S0164, S0577, S0913, S1524.
- Two grep calls against `--include=*.md` passed a stray option to ugrep and printed a warning; the results were still read.

Saved scratch paths: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons4-a/` (and `…/scratchpad/cons4-a-ch8a.txt`, `cons4-a-ch8b.txt`, `cons4-a-ch8c.txt`).
