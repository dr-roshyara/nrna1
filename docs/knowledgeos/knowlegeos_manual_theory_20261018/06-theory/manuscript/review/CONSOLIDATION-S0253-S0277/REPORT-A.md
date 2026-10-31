CONSOLIDATION S0253–S0277: REPORT A

**PROCESS DEFECT, STATED FIRST (REVIEWER-NOTE item 7).** In the middle of the review I ran some read commands from the WORKING book instead of the frozen copy. This was my error, because I did not put the copy's path into those `cd`s. What I reached in the working book:
- (a) `W/06-theory/CONCEPT-REGISTER.tsv` and `W/06-theory/THEORY-MAP.md`, once each.
- (b) `W/06-theory/manuscript/chapter_01/TODO.md`, `chapter_03/03.10`, `chapter_09/09.18`, `09.15`, `chapter_10/10.00`, `10.01`, `10.03`, `chapter_08/08.40`, `TARGET-TOC.md` and `BOOK-OUTLINE.md`.
- (c) A recursive grep over `W/06-theory/manuscript`. It printed lines from old review folders and the file names `BASELINE-REVISIONS.md` and `TODO-TRIAGE-S0100.md`. The old-review lines printed were from `review/WHOLE-BOOK-S0100/SETTLEMENT-STAGE1.md`, `P1/REPORT-B.md`, `PROFESSOR/checks/R2-DISPOSITIONS-working.md` and `review/CONSOLIDATION-S0131/S0151…/BOOK-UNDER-REVIEW/…`. No other old review text was read, and for the two files named above only the names were printed.
- Remedy: every fact in this report that came from those reads was re-read on the copy afterwards. I re-checked the register rows, the register tag check, THEORY-MAP F1, F11, F14 and F37, the Ch.1 TODO rows, §3.10, T1-43, §10.1, §9.18, §10.3 L319, §9.15 L63, §10.0 L8, TARGET-TOC L3 and L44, BOOK-OUTLINE L85, L95 and L119, and the grep results. All results were identical. The findings below rest on the copy. Because the working-book reads happened, treat the "no old texts opened" condition as not met.

*Verified facts, reported results, interpretations and recommendations are kept apart in each row. "Corpus-source check" means the S-IDs on CORPUS-ALLOWED.*

## 1. Findings

No critical and no high findings. Seventeen normal findings, all local to Ch.0 and Ch.1 sections or to the Ch.0 TODO. The section-level statements below were verified on the copy.

| ID | Section | Finding (verified) | Sev | Evidence | Correction (recommendation) | R3 lines and disposition | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | 00.01 L30 | "Whether a single kernel predicate can compute well-founded is open." Overtaken in part. The Principal decided the promise (b): standing shown, well-foundedness shown by class, no guarantee (§10.1.3). §9.18 L24 and L174 give the classes refuted, not attested, supported, contested. §10.1 L14 treats §0.1 as the problem statement only. | normal | `10.01` L14, L43–46, L54; `09.18` L24, L174 | Keep "open" for adequacy of support. Add the pointer: under promise (b) the kernel shows classes from recorded evidence (§9.18) and does not guarantee well-foundedness. | Section-only inbound: `09.10` L67, L149; `10.01` L14, L43–45, L54. All consistent, so no change. | Pointer present |
| A-02 | 00.02 L9 (08-04 row), 00.07 L33 | "Zero feedback into governance" and "never closed in practice" are unscoped. §1.8 L29 and L67–76 (S0013 L101, S0014 L43) and §0.8 L27 say "never" is rejected as a generalization (informal back-edge n≈3, formal ≈2). §0.7 L33 does point to §1.8, which softens this. | normal | `01.08` L29, L63–76; S0014 L43 and S0013 L101 matched the book's quotations | Say "no formal feedback; informal n≈3 (§1.8)" in both places. | `00.08` L27 and `01.08` L29 are already scoped; no change. | Both sites scoped |
| A-03 | 00.06 L5 | "Compiled into code (`Verdict.php::emittable()`)." §1.9 L68 reads the same code as one runtime guard on one path that does not establish the invariant in general. Reported result, not re-run: the code was not opened by me. | normal | `01.09` L68 | Add "as one runtime guard on one code path (§1.9)". | `01.09` is consistent. The 10 section-only inbound lines on §0.6 do not restate the wording. | Qualifier added |
| A-04 | 00.06 L18, 00.08 L28, 00.09 Q4–Q5 | "Open: whether authority is part of the theory or surrounding governance," and Q4. Later chapters answer part of it: §10.1 row 1 (current standing is core), the promise decision, and §8.40 C3 (KnowledgeOS records authority and does not decide it). T0-02 already notes the partial decision. §0.9 answers only Q1 and Q3 inline. Q5 is answered in the book (§0.5 states (T); §0.8 L23 records it as assumed). | normal | `10.01` L54, L81; `08.40` L59; `00.05` L27; `00.08` L23 | Add pointers. Keep the mission question open as T0-02 says. Annotate Q2, Q4 and Q5. | `00.09` inbound: `03.06` L110, `03.TODO` SEQ-0015, `05.04` L51, OUTLINE L85. They cite Q2 and Q3 only, so no change. | Pointers added |
| A-05 | 00.08 L18 (ii) | "It never duplicates … the strongest single rule." CON-0139 is INVALID as stated (§6.6 L88: the working context, derived representations and pinned quotations are admissible copies; restricted form: no second authoritative home). The INVALID note sits only in the table row L11. §0.7 L20 itself keeps a pin. | normal | `06.06` L37, L88 | Restrict the sentence at (ii). | Uses of "never a copy": `01.07` L12 (loose), `01.13` L48 (AMS's "reference, never a copy", fine), `06.06` L88. | Restriction at (ii) |
| A-06 | 01.01 L32 | Heading "drawn three ways". The section lists three placements and §3.9 C2's convention at L37, and THEORY-MAP F11 says "four ways". | normal | `01.01` L32–37; MAP F11 | Change to "four". | Consistent after the change: `03.06` L67, `03.TODO` L29 and L31, `08.41` L20 and L73, OUTLINE L87, MAP L132. | Heading matches the map |
| A-07 | 01.02 L49 | "'No creation→authority edge' means the initial state is never authoritative." This contradicts L19 and L53 (a root adopted at creation does not start non-authoritative). The correction note at L53 covers only the "valid iff" sentence. | normal | `01.02` L19, L49, L53 | Add the exception for root acts (§1.14.1) at L49. | The text search found no other copy of this sentence. | Exception stated |
| A-08 | 01.06 L51 | "The source also separates knowledge states from decision outcomes." S0013 L17 is a DEFERRED refinement B (a reviewer's sketch, with a check-before note). It is not a separation the source makes. The overstatement is supplied as "evidence for T0-07". | normal | S0013 L17 | "records, as a deferred refinement, a proposal to separate…" | The only inbound is the T0-07 evidence mention in this line. | Wording matches the source |
| A-09 | 01.07 title, box, L12 | Title and box say "never widen". Consequence 3 adds "unless widened by a recorded act", and the sentence is spliced (". when a restricted claim…"). Downstream and the outline state the law with the exception. | normal | `09.10` L94, `09.11` L49, `10.03` L103, OUTLINE L95 | Put the qualifier in the title and box. Fix the splice. | `09.10` L94 and L238, `09.11` L49, `10.03` L103 already carry the exception, so no change. `01.17` L128, OUTLINE L31, MAP L40 quote the short form and should be aligned or accepted. | Box and title match consequence 3 |
| A-10 | 01.08 L20 | "§0.2's four meanings now align with tiers: theory and platform → T1/T2; product knowledge → T3; the product hypothesis → the gated vision." §0.2's table has four dated meanings, and its layered reading has three layers (theory, platform, product hypothesis). "Product knowledge" is not one of them. | normal | `00.02` L5–L19 | Restate against §0.2's three layers, and say T3 is the tier model's own. | `00.02` L25 cites §1.8 and is consistent. | Mapping matches §0.2 |
| A-11 | 01.08 L27 | CON-0076's three conditions are given without the register finding that (1) is implied by (3) and that (2) is unjustified. Logic check: ≥2 instances imply at least one exercise. §6.1 L34 grounds only (3). | normal | register CON-0076; `06.01` L34 | Add one sentence. | CON-0076 inbound: `05.03` L42, `06.01` L34, `06.04` L41, MAP L78, OUTLINE L119. None contradicts. | Note added |
| A-12 | 01.08 L67 (table) | S0002 and S0005 are grouped for "n≈3 informal / ≈2 formal". S0005 L66 and L70 give only "n≈3 informal"; "≈2 formal" is in S0002 L36. | normal | S0002 L36, S0005 L66, L70 | Split the row. | No inbound cites the row. | Row matches sources |
| A-13 | 01.09 L55 heading, L87 | "Where I disagree with the source" ends in agreement. "This ladder is the normative strand; the strands meet only at capability and work" uses "ladder" for two things. The representational strand shares no node in the table, so the "only" is trivially true and omits it. | normal | `01.09` L55–L56, L72–L87 | Rename the heading. Say "the first line of the chain". Note that the representational strand meets none. | Section-only inbound `01.14` L87, L436, L476; no change. | Wording fixed |
| A-14 | 01.11 L79–80 | "Classification undetermined (T1-43)" has no pointer to Ch.3 §3.10 (F8 open, narrowed by Trial T2). T1-43, T3-04 and `06.03` L23 also lack it. | normal | `03.10` L6, L120; MAP F8 | Add the pointer. | The Ch.1 TODO row T1-43 is B's. | Pointer present |
| A-15 | 01.13 L79, L56–60 | "107 spellings in 144 grants." The same table says 144 records = 142 grants + 2 ending records. The recount has no date in the section. The change log (B's) L9 says "144 grants". | normal | `01.13` L58, L79; `09.06` L105 uses "144 records" | Say "144 records" and add the recount date. | `09.06` L105 is correct. The `01.99` L9 mismatch is for B. | Count and date consistent |
| A-16 | Ch.0 `TODO.md` | (a) T0-01 evidence cites old monolith lines ("Ch. 0 L167–177", "about L231–243"); the invariant table is now §0.5 L5–17. (b) T0-14 and T0-16 carry triage "CLOSE → register", but status is Open with the resolution criteria unmet. (c) T0-05 has no pointer to CON-0010 PROVISIONAL (T3-10: distinctness supported). (d) T0-04 has no pointer to DB-7 (reading order adopted; order of work undecided, TARGET-TOC L44). | normal | `TODO.md` L24, L31, L29, L39, L41; `03.TODO` L20; TARGET-TOC L3, L44 | Fix locators. Reconcile status and triage. Add the two pointers. | T0 rows are referenced by `00.02`, `00.03`, `00.05`, `00.07`. None cites the stale locators. | Rows consistent |
| A-17 | 01.00 L26 | The label-qualification list omits N-n. EP01's N-1 and N-2 (§1.2), S0019/S0008's N-1…N-10 (§0.2 L67) and S0013's "N-10 bar" (§0.5 L57) are three sets. Each use is source-bracketed, so this is a gap in the list, not a wrong citation. | normal | R2 tier B | Add N-n to the list. | Inbound not run. | List complete |

**Out-of-scope observations (one line each).**
- `chapter_09/09.18` L3 and L26 still read "Nothing here is adopted … proposed kinds", but `10.03` L319 says the kinds are adopted (D).
- `01.99` L9 "144 grants" and Ch.1 TODO T1-43 lack the §3.10 pointer (B).
- R1 flags only CON-0133 H4 in chapter_06 (outside my sections).

## 2. Concept verdicts

Verdicts are given only for concepts whose home lies in my sections. CON-0012, 0013, 0014, 0015, 0023 and 0054 are homed in §1.14 or §1.17 (the register's book_location is §1.17.1, §1.14.4, §1.14.5 or "root acts"), so they belong to reviewer B. I give no second verdict on CON-0013 and CON-0014 and did not review them. The register-tag check on the copy found 0 mismatches.

| ID | Verdict | Reason |
|---|---|---|
| CON-0019 | VALIDATED (second, independent) | Scope: a governance-position change moved by a named role's decision, not effected by evidence alone, reversed only by a further act. Counterexample attempts: work an act authorizes produces evidence (a ban on decree, not on causing, §1.6 L46). Expiry is part of the original act. "Governance position" spans standing and lifecycle status, and the source's own example ("frozen") is status. |
| CON-0016 | PROVISIONAL | Pair reading is Int; "×" is undefined; both factors are INVALID as stated; independence is untested (guard S0018 L104). |
| CON-0017 | INVALID, as restated | §1.2 L75 states the non-partition and the missing value for original human work; the core is kept. |
| CON-0018 | INVALID, as restated | Three senses of "standing" in the source (§1.2 L76). |
| CON-0020 | INVALID, as restated | The reversibility clause is not kept (§1.6 L42). |
| CON-0163 | PROVISIONAL | "Cannot be decreed" is Src (S0018 L62, L68). "Falls by re-analysis or staleness" is Int. Counterexample not addressed: an act that redefines the evidential bar changes the grade without new evidence. |
| CON-0024, 0025, 0027, 0028, 0051 | INVALID, each as restated in §0.2 | The reasons are carried in the text (partly valid; premise withdrawn; unratified; abductive; basis undecided) and consistent with the register. |
| CON-0026 | PROVISIONAL | n = 1 organisation; subjects not disjoint (§1.11 L93). |
| CON-0044 | INVALID, as restated | A voluntarily adopted external standard breaks "imposed, non-amendable" (§1.11 L100). |
| CON-0076 | PROVISIONAL | Condition (1) is implied by (3); (2) is unjustified. See A-11. |
| CON-0084 | INVALID, as restated | Two senses of "adapter" (§1.5 L22–26); the portability claim is excluded. |
| CON-0186 | PROVISIONAL | A target (S0028 L32, L149, n = 0); a Src and Int mix. |

## 3. Coverage (pointers and TODO rows)

| Item | Status | Home or evidence |
|---|---|---|
| Pointers to Ch.2 §2.8.8 (E1–E10; "ten"; E3/E4/E5/E6(c)), §2.8.2, §2.8.4a, §2.8.7 | Resolved, consistent | `02.08` L245–L270 |
| Pointers to Ch.3 §3.1 (F37), §3.4, §3.6 (CON-0039), §3.8 C5, §3.9 C2 | Resolved. F11 is still open ("four ways"). | `03.08`, `03.09` L83–95; MAP F11 |
| Pointers to Ch.4 §4.1 (CON-0070), Ch.5 §5.1, §5.3 C2, §5.4 C4, Ch.6 §6.1 C1, §6.6 (CON-0139) | Resolved | named section lines |
| Pointers to Ch.8 §8.1, §8.4 (CON-0120), §8.9 C1 and C9, §8.10 C10 | Resolved | `08.04`, `08.09`, `08.10` |
| Pointers to Ch.9 §9.8, §9.9.7, §9.12, §9.13, §9.15, §9.16 | Existence not re-read beyond the inbound listings | Not read in full |
| Ch.1 pointers (§1.14.x, §1.16.x, §1.17.x) | Resolved | `01.14` L60, L290, L442; `01.16`; `01.17` |
| TODO T1-31…T1-53 (17 rows cited) | Statuses match the text. T1-43 lacks the §3.10 pointer (A-14). | Ch.1 TODO |
| Ch.0 T0-01 | Open; stale locators; partly mitigated by `01.00` L26 | A-16 |
| Ch.0 T0-02 | Partly decided (T10-01 (b)); stays open as a whole | `10.01` L1, L81 |
| Ch.0 T0-03, T0-03a, T0-03b | Open, current | `08.22`–`08.29` |
| Ch.0 T0-04 | Open; pointer missing | A-16 |
| Ch.0 T0-05 | Pointer to CON-0010 missing | A-16 |
| Ch.0 T0-06, T0-07, T0-08, T0-10, T0-11, T0-13 | Open, consistent | search found no contrary resolution |
| Ch.0 T0-09, T0-12 | Open (search miss, not proof of absence) | searched the copy |
| Ch.0 T0-14, T0-16 | Triage "CLOSE" versus status Open | A-16 |
| Ch.0 T0-15, T0-17, T0-18 | Open, consistent | `05.01` cross-ref |

## 4. R2 (undefined words, scope 00.00–01.13)

Candidates judged one by one:
- **Defined inline, accept:** KT-8 (§0.6 L14), LA-4 (§1.8 L27), KBI-1 (§0.9, "recorded absence"), the "authority-*" senses (§1.2 L83–87), H-3 (§1.11 L79), R-34 (§1.06 L48), PM-1 and PM-2 (§1.02 L67), P-7 (quoted), D-3 (quoted).
- **Plain words or compounds, accept:** unminted (the sense is explained by "mint" at §1.13.3-2), viewer, codified, blamed, misdescribing, linkable (§1.13.3 AM-5 gives the sense), model-to-platform, need-driven, throw-away, back-flow (§1.08 gives it), unfalsifiability, child (grant), unregistered (defined by contrast with "unrecorded").
- **Process labels, accept:** C-1 (qualified at §0.1 L32 and §1.10 L43), A-05, F-0x, H-0x, B-05, B-11, B-12, CV-1, N-n. Reader-opaque review IDs. A-17 covers N-n.
- Tier D generic words (small, edit, ever, etc.) are ordinary English and need no definition.

## 5. Coverage of sections

- Read in full: Ch.0 `00.00`–`00.09`, `TODO.md`; Ch.1 `01.00`–`01.13`.
- Not covered (reported, not skimmed): Ch.0/Ch.1 claims whose sources are not on the allowed list. This covers EP01, GA, TR, DR-AB, AMS, V3, PKSM, GKC, DevConst, Charter and IA, which are the `knowledge_tranfer` set and GKC/PKSM-class files. Those line cites could not be checked against their sources. I checked only the book's own wording, and where one source is cited from two sections I checked that the two do not conflict.
- Corpus checks done on S-IDs on the allowed list:
  - `corpus_quote_check.py` on 24 section files: 76 quotes found, 0 not found.
  - About 70 further lines read in S0001–S0034, S0089, S0090, S0095, S0097, S0129 (every in-meaning cite I could sample). Besides A-08 and A-12, no source mismatch found.
- Architecture view: premises (T), (T′), (R), A1 (DB-1, stipulation) and I-0 are stated as assumptions or stipulations, and nothing in Ch.0 or Ch.1 rests on an unstated premise that I found. SEQ order of Ch.0 and Ch.1: acceptable. The forward dependencies (§1.06 L27 uses P-E, P-R, INV-A3, ι and C1 by pointer to §1.13 and §1.14; §0.1 and §1.02 point to later chapters) are all flagged by pointer.

## Summary

1. No critical and no high findings. Seventeen normal findings, all local wording, scope or pointer defects. The Ch.0/Ch.1 theory I could check holds within its stated scope.
2. Largest risks are stale open items now overtaken by later chapters (A-01, A-04, A-16) and two unscoped "never/zero" statements (A-02).
3. Verdicts: CON-0019 VALIDATED (second verdict). Eight PROVISIONAL and INVALID verdicts are given for concepts homed in my sections, and none changes status.
4. Every cited S-source quotation I could test is faithful. The two source-attribution defects are A-08 and A-12.
5. Ch.0 `TODO.md`: 18 rows checked; five need locator, status or pointer fixes.
6. I could not check the `knowledge_tranfer`-sourced claims.
7. One process error is disclosed above.

**Process independence:** independence is process independence, not personal independence. I read no other reviewer's report.

## Files opened and commands run

**Copy files read in full** (BOOK-UNDER-REVIEW/06-theory/manuscript/):
- Commission, REVIEWER-NOTE, SCOPE, REVIEWER-INSTRUCTIONS.
- Ch.0: `chapter_00/00.00`–`00.09`, `chapter_00/TODO.md`.
- Ch.1: `chapter_01/01.00`–`01.13`.
- Parts read: §1.17.2–§1.17.5, `chapter_02/02.08`, §3.8–§3.10, §4.1, §5.x, §6.1/§6.6, §8.4/§8.9/§8.10/§8.40, §9.15/§9.18, §10.0–§10.3, `chapter_01/01.14`, `chapter_01/TODO.md`, `chapter_03/TODO.md`, `TARGET-TOC.md`, `BOOK-OUTLINE.md`, `THEORY-MAP.md`, `CONCEPT-REGISTER.tsv`.
- Corpus (allowed list): S0001–S0005, S0008–S0014, S0016–S0019, S0021, S0023, S0024, S0027, S0028, S0031, S0032, S0034, S0038, S0089, S0090, S0095, S0097, S0129 (selected lines).

**Working-book files reached in error** (item 4): `W/06-theory/CONCEPT-REGISTER.tsv`, `W/06-theory/THEORY-MAP.md`, `W/06-theory/manuscript/chapter_01/TODO.md`, `chapter_03/03.10`, `chapter_09/09.18`, `09.15`, `chapter_10/10.00`, `10.01`, `10.03`, `chapter_08/08.40`, `TARGET-TOC.md`, `BOOK-OUTLINE.md`; a recursive grep over `W/06-theory/manuscript` printed lines from old review folders (see the process note) and the file names `BASELINE-REVISIONS.md` and `TODO-TRIAGE-S0100.md`.

**Commands and searches:**
- `register_home_check.py --root <copy>`.
- `undefined_terms.py --root <copy> --scope 00.00…01.13`.
- `corpus_quote_check.py` on the copy's 24 section files.
- `inbound_citations.py --root <copy>` on §0.1, §0.2, §0.6, §0.7, §0.9, §1.1, §1.2, §1.6, §1.7, §1.8, §1.9, §1.11, 1.13.2-1 and CON-0076.
- `book_search.py --help` (run without `--root`, help only).
- Register CSV and tag-check scripts on the copy.
- `sed`, `grep` and `cat` over the corpus sources listed above and the copy chapters named in the findings.
