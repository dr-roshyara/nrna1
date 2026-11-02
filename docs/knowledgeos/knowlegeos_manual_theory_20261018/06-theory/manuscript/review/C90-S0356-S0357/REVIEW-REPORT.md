C90 REVIEW REPORT

Window reviewer W, C90 step 2. Object: §8.83 (draft), its index row (08.00 L99) and STEP8-AND-CHECKS.md. I first checked the saved inventory against SHA256-STEP1.txt: both give 30fe3ac0…696f6a. Independence here means process independence, not personal independence.

## Summary
1. Every quotation of S0356 and S0357 in §8.83 matches the source after bold markup is removed (28 spans, checked by a script, record below), and so does the §9.15 K2 quote. Every source line reference I checked is correct.
2. The two main comparisons with the book each contain a material error. C1 merges two of the estate's vocabularies into one. C3 says S0357 agrees with CON-0240, but CON-0240 states the opposite direction, and §9.6.4 K2 and §9.16 R2 point the same way as CON-0240.
3. C3 describes the book's K1 and K4 wrongly. The book also has a second K-series of its own: the assumptions K1–K4 in §9.6.4.
4. C4's "no actor" is false against S0357 L123 and L400. Its contrast, "epistemic" against "a kernel of the record of acts", ignores §9.18: evidence kinds were adopted on 2026-10-31, and Q4's grade was assigned to the kernel (U2).
5. C5 holds, and S0357's own disagreeing lists strengthen it. Two of its supporting sentences overstate the book.
6. The draft presents nothing as decided that is the Principal's to decide.

## Findings

| W-nn | Severity | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high | C1 "homonym": MATCH, MISMATCH, AMBIGUOUS and UNRESOLVED are called "the attribution check's values … comparing a declared label with a registered one" (§8.83 L35, L19) | Attribution's values are MATCH/MISMATCH/UNKNOWN (§8.71 L90, quoting S0324 L67). AMBIGUOUS and UNRESOLVED are the resolver's results when it maps a label to lanes: several lanes match, or none does (§8.71 L65; §8.77 L53, L58). S0356's set is the union of two mechanisms, and attribution's UNKNOWN is missing from it. "Reuse" also claims a direction of borrowing that nothing in the window shows. | Restate: "S0356's four words appear in the estate in two mechanisms: attribution (MATCH/MISMATCH, beside UNKNOWN) and lane resolution (AMBIGUOUS/UNRESOLVED). The resolver's sense (several candidates, or none) matches the shape of S0356's candidate set. That is an analogy, not a homonym. Direction of borrowing not shown." | §8.83 L11, L19, L27, L35; 08.00 L99. inbound_citations: 0 claim lines for §8.83 C1. |
| W-02 | high | C3 agreement by content: S0357 L68 "agrees with §8.18's distinction (byte equality is not identity, CON-0240, one direction)" (L49) | CON-0240 (register L241) says that, given one fixed decoder, byte equality **implies** equal content identity; only the converse fails. §9.6.4 K2 (content-derived identity) and §9.16 R2 (union by content identity) also make equal content mean the same act. S0357 says representation equality must "never automatically" become identity equality. That is the opposite policy, or else "identity" means entity identity in S0357 and act identity in the book. | Replace the claimed agreement with: "tension or homonym. The book derives act identity from content (§9.6.4 K2; §9.16 R2; CON-0240, one direction). S0357 forbids deriving entity identity from representation. Not mapped." | §8.83 L49 |
| W-03 | high | C3: "§9.15 (the book): K1 is what is detectable from the record … K3–K7 are the inputs, the per-kind clauses, the integrity check …" (L46) | §9.15 L74: K1 is the **exclusive write path**; the detectability text is a parenthetical note inside the K1 cell. §9.15 L77: K4 is **one serialization point per law** that merging can violate, split into K4-k and K4-o. "Per-kind" does not occur in §9.15 (grep). §9.15 L70 calls K1–K7 "kernel assumptions", not "laws". | Restate as: K1 exclusive write path; K2 single designated record; K3 the inputs; K4 one serialization point per law that merging can violate; K5 integrity check sound; K6 CE* correct and serialised; K7 the recorded evaluator version is the one that ran. Change "laws and assumptions" to "kernel assumptions" (also at L13 and L21). | §8.83 L13, L21, L46, L48 |
| W-04 | high | C4: "no holder, act or grant appears among its inputs, and its eight capacities name none" (L59) | S0357 L123 lists "actor/context" under K4, and context is an input at L44. L400 has "Provider P submitted candidate meaning X", which is an attributed act. In the same lineage, S0356 L76–90 and L16 put "Governance / authorization → authorized act" before the state transition. | INVALID as stated. Restate: "S0357's admissibility names a submitting provider and an actor only as part of context. No holder's grant enters it. Its lineage (S0356) places the authorized act in a governance layer upstream." Add this as the stronger rival in §8.83.4. The present rival, "the actor is inside constitutional rules", is the weaker one. | §8.83 L22, L59, L78–79; 08.00 L99 |
| W-05 | high | C4: "S0357's kernel is epistemic where the book's is a kernel of the record of acts" (L59) | §9.18 L24, L49, L67: displayed classes are read from evidence in the record, and the evidence kinds were adopted on 2026-10-31. §9.18 L82: Q4's grade is g1's class, assigned to the kernel (the Principal, 2026-11-01, U2). The book's kernel already has an epistemic part. | Restate: "The book's kernel derives standing from recorded acts and, since §9.18, grades from recorded evidence. S0357 has the second without the first's holder." | §8.83 L22, L59 |
| W-06 | normal | C3 homonym strength (L48, L76–77) | The book itself already uses K1–K4 for another object: the §9.6.4 assumptions (occurrence, identity mechanism, number of applications, uniqueness). "The book's K1–K7" presupposes a single list in the book. | Add: "the label K is already ambiguous inside the book (§9.6.4 K1–K4; §9.15 K1–K7); S0357 adds a third list." The challenge answer then holds more strongly. | §8.83 L48, L77 |
| W-07 | normal | C3: S0357's K8 (determinism) "agrees with the book's K7 and replay" (L49) | Same output for the same input and prior state is G1 (CE* is a function of the record and its inputs, §9.15 L64) together with K6 (serialised). K7 concerns the identity of the evaluator version, which replay checks. | Say "G1 with K6; K7 for the evaluator version". | §8.83 L49 |
| W-08 | normal | C1: agreement with the evaluator's shape; "an unresolved case stays U rather than being forced" (L34) | In §9.13.1, "forced" is a technical term: T and F hold in every fixed point. The draft uses the word in the everyday sense. The book's object closest to "admissible for the requested act" is G2, authorized(x), and the append guard, which accepts a use only if it is T at append (§9.15 G2, G4; §9.13). CE*'s standing is not that object. | Replace "forced" with "decided arbitrarily". Name G2 and the append guard as the home of admissibility, with CE* as the input it reads. | §8.83 L11, L34 |
| W-09 | normal | C5: "a minimal kernel is well posed only for a fixed pool (§9.19)" (L65) | §9.19.8 L381–384: it is well posed relative to Req, A = (B, G) **and** a finite pool, all fixed. | "Well posed only relative to a fixed Req, A and finite pool (§9.19.8)". | §8.83 L65 |
| W-10 | normal | C5: the capacity ceiling "states as a design rule what §9.6.4's relocation test checks per part: what the kernel need not do" (L65) | The relocation test decides whether the kernel **must** provide c (§9.6.4 L113). Not having to provide something is permission to omit it. S0357 L390, "incapable", is a prohibition. These are two different deontic statuses. | "The ceiling forbids what the relocation test only permits the kernel to omit; it is stronger than anything the book derives." | §8.83 L65 |
| W-11 | normal | Index row: "a standing rule of the HPA cited by an adviser" (08.00 L99) | The section treats the rule as a report only (L40). The row asserts that it is the HPA's. | "a rule an adviser attributes to the HPA". | 08.00 L99 |
| W-12 | normal | Lineage (L5) | The lineage says nothing about S0357 building on S0356: they are 3 min 11 s apart, and S0357 L296 refers back to "the Sanskrit discussion" and reuses its terms. C3 says "one lineage" only in passing. | State once: "S0357 continues S0356; one line of support." | §8.83 L5 |

R3: inbound_citations, run on a scratch copy with an explicit root, finds 0 claim, concept or section-only citations of §8.83 C1, C3, C4 or C5 outside the section. The only other site is the index row (08.00 L99), which the tool does not match because the row cites by file, not by "§8.83". None of my corrections touches §8.71 C4 or §8.77 C2.

## Verdicts
- **C1:** PROVISIONAL. The split and the correction are reported correctly. The homonym statement merges two vocabularies (W-01), and the home of admissibility is misplaced (W-08).
- **C2:** VALIDATED, scoped as a report, not a record. I found no record of the rule in the book; that is a search miss inside the book only. The index row needs the W-11 wording.
- **C3:** PROVISIONAL. The homonym is real, but the book's list is misdescribed (W-03), the book's own second K-series is missed (W-06), and the CON-0240 "agreement" is inverted (W-02).
- **C4:** INVALID as stated on "no actor" (W-04) and on the epistemic/acts contrast (W-05). The second-vocabulary part holds.
- **C5:** VALIDATED: the minimum is asserted, not derived. Two supporting sentences need fixing (W-09, W-10).

## Coverage against the inventory
- **COVERED:** S0356 items 1, 4, 7, 8 and 11, and items 2 and 3 in part. S0357 items 1–5, 8, 9 and 10.
- **DISTORTED:**
  - S0356 item 3 (W-01).
  - S0357 item 5, the ceiling (W-10).
  - S0357 K1's invariant (W-02).
- **MISSING, with my judgement of whether theory is lost:**
  - (a) S0357's lists do not agree with each other: nine items at L24–32, eight at K1–K8, a minimum list at L443–452 that adds Meaning candidate and Relations, and the one-sentence job at L473, which drops time. *This is a loss: it is the strongest internal evidence for C5.*
  - (b) The trivial case: K7 allows reject or abstain for every input. A kernel that always says NO meets every must-never S0357 states, and nothing says "must" except K8's "should" (L240). §9.19.8's corrected F-min (C-07: a non-default answer must stay reachable) is the book's answer to this. *This is a loss of a validity test the book can already state.*
  - (c) S0357 reuses state words across its own lists: SUPERSEDED appears in both K5 and K6, and conflicted in K5, K6 and K7. This bears on C4's second vocabulary.
  - (d) S0356 L92: the interpreter is not the authority on semantic identity. This bears on K3's identity link.
  - (e) The tension between S0357 K4 (the kernel "evaluates" context) and the ceiling at L390.
  - (f) S0356's adequacy test as a procedure: if the existing boundary is adequate, no research; if not, a bounded request to the HPA (L149–153). It also leaves no owner named for "ambiguity explicit". Normal.
  - (g) S0356's own lineage claim (L166) and its two senses of "interpretation" (L12, L14). Normal.
- **JUSTIFIABLY EXCLUDED:** Pāṇinian grammar and compiler theory as design input; the P5 result.
- **New fork:** "identity" (act, content-derived) against "identity" (entity, never from representation). The theory map should record it as a homonym to be qualified (W-02).

## Limits
- I did not run R2 in the form STEP8 used: the tool needs the allowed list in the repository layout. My `--no-corpus --scope 08.83` run lists 29 tier-D candidates; STEP8 lists 12. I judge all 29 to be ordinary English or the source's own sense. "Admissibility" is not defined in the book, and §8.83 uses it in S0357's sense. The difference in the number of candidates is reported, not explained.
- `corpus_quote_check.py` needs `02-FILES.jsonl`, which I am not allowed to open. I checked the quotes by hand instead, with my own script.
- I read §9.6, §9.13, §9.18 and §9.19 only in the parts cited. I did not open any review folder or the C89 settlement. I checked only that §8.82 is settled and that §8.75 L45 carries the R3 note.

## Files opened
- Commission and method:
  - …/06-theory/manuscript/review/C90-S0356-S0357/BRIEF-W-STEP2.md
  - the same folder: STEP8-AND-CHECKS.md; SHA256-STEP1.txt; STEP1-INVENTORY.md (sha256 only)
  - …/03-methodology/REVIEWER-INSTRUCTIONS.md (step 1)
- The draft:
  - …/06-theory/manuscript/chapter_08/08.83-an-advisers-kernel-of-capacities-whose-k1-k8-are-not-the-books-k1-k7.md (whole)
- The book, at the lines cited:
  - chapter_09/09.15-the-trust-boundary.md (L1–20, L60–95)
  - chapter_09/09.13-the-evaluator.md (L1–45)
  - chapter_09/09.18-evidence-in-the-record.md (headings, greps)
  - chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md (L1–18, L348–387)
  - chapter_09/09.06-boundary-test-authority-recording.md (L111–148, greps)
  - chapter_09/TODO.md (T9-02, T9-03, T9-21 rows)
  - chapter_08/08.18-*.md, 08.45-*.md, 08.71-*.md (L60–70), 08.77-*.md (L55–66), 08.75-*.md and 08.82-*.md (greps and heads); 08.00-index.md (L98–99)
  - chapter_03/03.10-*.md (L49); chapter_01/01.06-*.md (L15–19)
- The register and map: 06-theory/CONCEPT-REGISTER.tsv (CON-0240 and greps); 06-theory/THEORY-MAP.md (L7 and greps); BOOK-OUTLINE.md (grep hits only)
- The window sources:
  - docs/knowledgeos/brainstorming/20260823-103255-candidate-set-architecture-ah6-interpretation-execution-separation.md
  - docs/knowledgeos/brainstorming/20260823-103606-kernel-eight-capacities-k1-k8-and-minimum-kernel.md
- Tools: the headers of undefined_terms.py and inbound_citations.py, read in the scratch copy.

## Commands and searches
- `ls` of the C90 review folder and of chapter_00–10; `sha256sum` on STEP1-INVENTORY.md; `wc -l` on the sections above; `ls tmp/tools`.
- `cat`, `sed` and `cut` over §9.15, §9.13, §9.19, §9.6, THEORY-MAP L7, §3.10 L49 and §1.6 L15–19.
- greps, each restricted to named files:
  - `^| K[0-9]`, `per-kind`, `kernel assumptions`, `legitimacy assumptions` in §9.15;
  - `CON-0240` in the register and §8.18;
  - `MATCH|AMBIGUOUS|UNRESOLVED` in §8.71 and §8.77;
  - `T9-21|T9-02` in THEORY-MAP, the register, BOOK-OUTLINE and chapter_*/*.md;
  - `initiate a research|research track|HPA standing` in chapter_*/*.md, THEORY-MAP and the register;
  - `C1\.` in §8.45;
  - `correctness|not truth|standing, not` in §9.13;
  - `displayed|contested|not attested` in §9.18;
  - `relocation` in §9.6;
  - `admissib` in chapter_09/*.md, chapter_01/*.md and the register;
  - `8.83|8.82` in 08.00;
  - `R3|§8.82` in §8.75;
  - `AMBIGUOUS` in THEORY-MAP and the register;
  - `MATCH/MISMATCH…` in 08.6*.md and 08.7*.md.
- `rsync` of 06-theory (review folders excluded) and of tmp/tools to the scratch copy.
- Tools, all `python3 -I` with `--root` on the scratch copy:
  - `--help` on undefined_terms, inbound_citations, corpus_quote_check and quote_anchor_check (two failed, needing files outside the copy);
  - `undefined_terms.py --no-corpus --scope 08.83`;
  - `inbound_citations.py "§8.83 C1" "§8.83 C3" "§8.83 C4" "§8.83 C5" "§8.71 C4" "§8.77 C2"`;
  - my scripts c90-w-qc.py (abandoned because of curly quotes) and c90-w-qc2.py (28 spans checked by line).
- Scratch output files, all in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c90-wren/: c90-w-r2.txt, c90-w-r3.txt, c90-w-quotes.txt.
- I ran no git commands and edited nothing in the repository.
