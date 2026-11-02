CONSOLIDATION S0328–S0352: REPORT C

Reviewer C. Object: the frozen copy `…/CONSOLIDATION-S0328-S0352/BOOK-UNDER-REVIEW/` only. Scope: Ch. 8 §8.20–§8.30 and `08.99` in full, plus the Ch. 8 `TODO.md` rows and the pointers to later chapters (task 3).

## Summary (15 lines)
1. I read all twelve files in full. The quote checker found 75 quotes and 1 failure. That failure is real and serious: §8.23 C1 L44 quotes S0159 L297 the wrong way round. The source says "Consistency requirements are **domain** decisions, not technology decisions". The book quotes it as "technology decisions, not domain decisions". The heading and index row 8.23 match the source; the body does not (F-01, high).
2. §8.20 still states a concept the book has refuted. Its title says "a store cannot record its own close", and its C6 deduction (L96) says "a closing record must lie in another record". Both are CON-0252, which is INVALID as stated, and §9.17 L310 refuted this same sentence when it came back once before (F-02, high).
3. The rest are normal findings: a register follow-up still names CON-0256 (i) as a home; a count attributed to the wrong section; a "fork stays open" sentence left after the fork was decided; a model-version mismatch in the TODO file; gaps in the change log; incomplete reference tables; an incomplete forward-use note; and an overstated "no trend" verdict.
4. Concept verdicts (9 concepts): CON-0253 VALIDATED (second verdict). CON-0254 VALIDATED within its stated premises, with a proof-step table. CON-0250, 0251, 0257 and 0259 PROVISIONAL, each with a specific gap. The INVALID cores of CON-0252, 0255 and 0256 are confirmed as restated.
5. CON-0251's "only if" fails on a small countermodel unless "cites" is read as "carries information first produced at e". CON-0259's "compare" has to mean containment (R ⊆ survivor, as in CON-0265's gates); read as equality it fails in the normal case.
6. Source checks were sampled, not done for every claim: S0142, S0143, S0144, S0145, S0146, S0148, S0159, S0183, S0190 and S0199 at the cited lines, plus three duplicate-text claims checked by script (S0163/S0150, S0169/S0163). Apart from F-01 and F-04, the sampled claims match their sources.
7. TODO: T8-01, T8-02, T8-03 and T8-05 are still open, T8-04 is resolved, and SEQ-0013 is resolved. T8-05 has no pointer back to it from §8.22–§8.26.
8. R1: one flag, CON-0133 (Ch. 6), outside my sections. R2: all 3 tier-A, 4 tier-B and 4 tier-C candidates and the ~100 tier-D words judged; 1 real issue ("exactly" at §8.20 L114, F-05).
9. Largest risk: quotes changed in the text after review. F-01 inverts a source quote even though index row 8.23 states the source's sense. The quote checker catches this kind of change, so it should be run on every rotated chapter.

Process independence: I am independent of the producing process in process only. I did not see the other reviewers' reports. I share the lane's tools and the book's own lineage.

## Findings
Severity: critical / high / normal. The R3 lines and dispositions come from `inbound_citations.py --root <copy>`.

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| F-01 | §8.23 C1 L44 | The direct quote is inverted. The book writes "Consistency requirements are technology decisions, not domain decisions." [S0159 L297]; the source reads "**Consistency requirements are domain decisions, not technology decisions.**". The heading ("Consistency is a domain decision") and index row 8.23 agree with the source, so body and heading contradict each other. | high | quote check: `08.23 L44 NOT FOUND`; S0159 L297 read directly | Restore the source wording. | §8.26 L22, L56, L60 rely on the heading's sense (unaffected); 08.00 L39 (index, correct); THEORY-MAP L193 (unaffected) | L44 matches S0159 L297 and the quote check passes |
| F-02 | §8.20 title L1; C6 L96 | Stale text states an INVALID concept. The title says "a store cannot record its own close" (CON-0252). The C6 Int deduction "a closing record must lie in **another** record … the one form that both terminates and orders" is unmarked, although L98–101 (W-02) and §9.17 R3 (d) say a self-including closing entry is possible and the loop ends when the *evidence store* moves. §9.17 L310 calls the same sentence "reintroduced CON-0252 (INVALID)". | high | 08.20 L1, L96, L98–101; 09.17 L209, L310; register row CON-0252 | Restate the title ("…a hash cannot commit to itself…"; the filename can stay). Mark L96 as the superseded draft, or restate it as CON-0254 (ii). | CON-0252 inbound: 08.20 L58, L98, L246, L263; 09.17 L310; register L253, L255; 08.00 L36 (all correct, none change). §8.20 C6 inbound: 09.17 L209, L216 (correct) | The title and L96 no longer assert CON-0252 |
| F-03 | Register CON-0257 `follow_up` | Stale home: "the missing clause is CON-0256 (i)'s 'the reference is exactly the intended state' (§8.22 C7; cf. §9.17 R4 exactness)". §8.22 L109 (B-07) and L23 (C H4) withdrew CON-0256 (i) as a home and separated the two senses of "exact". | normal | register row 258; 08.22 L23, L109; TODO L14 | Replace with "follows from the definitions, no home (§8.22 C7)". | TODO L14 (correct); §8.22 L108–109 (correct); 09.17 L264–265 (unaffected); 08.99 L43 (history, keep) | The register row agrees with §8.22 C7 |
| F-04 | §8.20 C2 L55 | Wrong attribution: "only 2 of 216 transitions carry a time (§8.17 C6)". §8.17 C6 has 2 of **210** (S0128 L49); 216 is S0142 L242. §8.21 L167 states it correctly. | normal | 08.17 L79; S0142 L242; 08.21 L167 | Cite "(S0142 L242; §8.17 C6 had 2 of 210)". | §8.31 L75, §8.21 L167 (consistent with the fix) | L55 cites S0142 |
| F-05 | §8.20 C8 L114 | "This is CON-0251's form **exactly**" overstates. The enumeration names the state at the measurement step (3(i)), not at the switch, as L61 and L116 themselves say. | normal | 08.20 L61, L116; S0142 L246 | Drop "exactly", or write "CON-0251's form, for the named prefix". | none at claim level; CON-0251 concept lines unaffected | Wording scoped |
| F-06 | §8.23 C12 L127 | The bold "**The fork stays open (T8-04).**" stands in the settled body although T8-04 was decided on 2026-10-27. L122 annotates it, but the bold sentence contradicts §8.23.3 L131 and §8.22.3. | normal | 08.23 L122, L127, L131 | Mark it inline: "(open when written; decided 2026-10-27)". | TODO L14; 08.99 L51; THEORY-MAP L344 (unaffected) | No unqualified "stays open" remains |
| F-07 | TODO T8-04 | Version mismatch: TODO says "model v18 check 29 (6)", while §8.22 L111 and 08.99 L43 say v17. | normal | TODO L14; 08.22 L111; 08.99 L43 | Give one version, or both with a reason. | T8-04 lines: 08.22 L111, L141; 08.23 L131; 08.99 L43, L45 | One version stated consistently |
| F-08 | 08.99 | (a) There are no rows for the C27 and C28 settlements (§8.20, §8.21), and only one row (L37) for the consolidation S0131–S0150 and the turn-8 changes, although the prose cites A-02, A-05, A-15, A-16, B-01 and B-03. (b) There is no row at all for the consolidation S0151–S0181, although the prose cites A-01, A-05, A-07, A-13, B-06, B-07, B-08 and B-09 (§8.22 L57, L109; §8.23 L122; §8.24 L33; §8.26 L57, L63). (c) The "Now reads" of L37 is not what §8.20 L59 now reads ("(citations name earlier entries only, §9.13.2; attributed to K2 before theory turn 8 …)"). | normal | 08.99 L37–51 against the prose | Add the missing rows (dates and items from the settlements), or state where that history lives. Update L37. | 08.99 L37 only | The log covers every dated correction marked in §8.20–§8.26 |
| F-09 | References tables §8.20, §8.21, §8.24, §8.28, §8.29, §8.30 | Lines cited in the text are missing from the tables, e.g. S0142 L35, L93, L129–131, L183, L206–212, L511; S0145 L52–66, L100–153, L262–271; S0148 L6–159 (several); S0149 L161–170, L631–678; S0150 L470–529; S0166 L333, L524, L665–675; S0168 L165–182, L835–845, L898–921; S0193 L20, L33, L120, L124; S0196 L21–30; S0198 L50, L79; S0199 L4, L74, L82, L97; S0200 L77–103, L166–172, L313–414, L444. | normal | my script `cons4-c-refs.py` (it catches only citations that carry an S-ID; bare "(L86)" lines in §8.30 were counted by hand) | Complete the tables. | table-local, no inbound | Every cited line appears in its section's table |
| F-10 | SEQ, 08.00 forward-use note | The note lists Ch. 9 constructs used by §8.18–§8.38 (D, CE*, K1–K7, L1a–L10, R2, R3, Q1, K3, K6, the designation rule). My sections also use §9.15 G1–G6 (G3 at §8.20 C1), L11 (§8.22 C2; §8.23 C5; §8.24 C2, C7), §9.8 H2/H3b (§8.20 C20), §9.16 R2 (§8.22 C11), §9.05 (§8.24 C11), §9.2 C2 / §9.3 C4 (§8.28 C1), §9.6 (§8.30) and §9.17 R4/CON-0265 and (c′). None is introduced earlier in the reading order. | normal | 08.00 trailing note; the lines named | Extend the note. | 08.00 note only | Each forward use is covered by the note |
| F-11 | CON-0251 (§8.20 C2) | Countermodel for the "only if" half: entry e of A publishes a fresh nonce n; event x of B embeds n without any D-citation. There is no shared order source, yet x is shown after e. The "only if" holds only if "cites" means "carries information first produced at e" (§8.20 C8 already treats naming by content as a citation). | normal | small model (my construction); §8.20 L114 | Define "cites" in the register meaning as "names, or carries information first produced at, e or a successor". | 26 concept lines; 09.17 L176 and L218–220 and §8.20 L114 are consistent with the broadened reading; only the register meaning and §8.20 L56 change | Register meaning restated |
| F-12 | CON-0250 (§8.20 C9 L120) | Overgeneralised: "a hold inferred from a comparison can be released silently by moving the item". S0142 shows this for one comparison, which the held item fails. A gate that tests whether the held item is present in a retained store is not opened by a move. | normal | S0142 L327–329 | "a hold inferred from a comparison **that the held item fails** can be released by moving the item". | §8.20 L41, L118–122, L244; BOOK-OUTLINE L231; THEORY-MAP L191 (unaffected) | Register and L120 scoped |
| F-13 | CON-0257 | The boundary between "internal inconsistency" (detectable) and "divergence" (not detectable) is undefined when the intended state is intrinsic (e.g. "sorted", "schema-valid"). There a content-only reference detects divergence. The concept holds for an intended state fixed by the source (replacement gates). | normal | definition test | Add the scope "intended state fixed by an input other than the checked content". | as F-03 | Scope stated |
| F-14 | CON-0259 | "Compare the survivor against the reference" read as equality fails in the normal case: the survivor receives post-switch appends (S0183 L163). CON-0265's gates use R ⊆ NEW. | normal | S0183 L155–164; 09.17 L232–236 | "…the reference must be contained in the survivor (CON-0265 G1/G2)". | §8.21 L46, L182; §8.27 L22, L64; §8.28 L104; 09.17 L265; THEORY-MAP L191 (unaffected) | Register meaning restated |
| F-15 | §8.20 C19 L212 (and §8.21 C10 L120) | "No trend is established" is too strong. The recount 21–29% → 23–31% → 42% leaves the first step undecided but the last clearly higher. What fails is a monotone trend, and any inference from three purposive points. | normal | S0145 L45–49, L121; my recount (AMD5: 7 minus RD-3·b minus the RD-7·a/DI-5 duplicate = 5/12) | "no monotone trend is shown; three purposive points". | §8.21 L18, L118–120 (update in step); §8.07 L63 (unaffected) | Both lines restated |

Out of scope (one line each): R1 flags CON-0133 (H4, Ch. 6). §8.71 ("bars passed by a different label") may be a further T8-03 instance and is not marked in TODO; I did not review it.

## Concept verdicts
| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0253 | VALIDATED (second) | Reach versus detection is a sound distinction. The sources support it (S0143 L18–40, L83; S0144 L25, L34–45); the axes are stated as "at least three". Scope: validation of checkers. |
| CON-0254 | VALIDATED within its premises | Proof steps: P1, each mechanical write is evidenced in the store the writer resolves (S0142 L234; PREMISE 2). P2, before the switch the writer resolves the leader (S0142 L222). P3, the target includes the evidence entries (A-16). S1, writing to the follower appends e to the leader, so leader ≠ follower (P1, P2). S2, every repetition adds a new e, so there is no finite fixed point (induction on S1). S3, after the switch the evidence lands in the follower, the leader is frozen, and one step terminates (S0142 L229). Clause (i) rests on the standard assumption that hash fixed points are infeasible to find: an assumption, not a proof. |
| CON-0250 | PROVISIONAL | One chain (S0141 → S0142). The "explicit state" requirement is overgeneralised (F-12). No disposal act exists (S0142 L144), so the hold has no built release: a liveness gap. |
| CON-0251 | PROVISIONAL | Established prior work (happened-before), but the "only if" depends on how broadly "cites" is read (F-11). |
| CON-0257 | PROVISIONAL | Necessary, not sufficient (§8.22 C7). The divergence/inconsistency boundary is undefined for intrinsic intended states (F-13). Its register follow-up is stale (F-03). |
| CON-0259 | PROVISIONAL | Sound necessity (S0142 L279–281), but "compare" must mean containment (F-14). Once restated, it follows from CON-0265 (VALIDATED). |
| CON-0252 | INVALID core confirmed as restated | A self-including closing entry is possible; only a hash cannot cover its own bytes (§8.20 L98–101; §9.17 R3 (d)). Text residue: F-02. |
| CON-0255 | INVALID core confirmed as restated | "Cannot fail" lacked its premise; the falsifier was immune (§8.21 L54–58). |
| CON-0256 | INVALID core confirmed as restated | A reference stored in the survivor but derived from the source meets the falsifier (§8.21 L62). |

## Coverage (TODO rows and pointers)
| Item | Status | Home |
|---|---|---|
| T8-01 raw record | still open (book search: §8.01 only) | §8.1 |
| T8-02 factor → blind-set bridge | still open | §8.6.2 |
| T8-03 ρ / F35 | still open; instances at §8.27 C1 (grant 22); §8.28 C3 adds none | §8.9 C2, §8.27, §8.28 |
| T8-04 | resolved 2026-10-27; version mismatch (F-07) | §8.22 C7, §8.22.3, §8.23.3 |
| T8-05 (7 items) | open. Searches for "R-CONFLICT" ownership by Evidence, "never knowledge", ADR-KOS-002, "command loop", "authorize nothing" and "integration event" found no home in §8.22–§8.26 (a miss is not absence). No section points back to T8-05. | TODO only |
| SEQ-0013 | resolved | 08.00 header |
| §8.20.3 / §8.21.3 pending items → §8.60 C1 | correct: DV-1 repaired, not closed (§8.60 L42); compares against the closed enumeration (L43); DV-correction author and review (L44); OPEN-M6, OPEN-M7 and acceptance still pending (L47) | §8.60 C1 |
| §8.20.3 → §9.17 R3 (d), (e); §8.21.3 → §9.17 R4 (CON-0265) | correct | 09.17 L209–220, L264–265 |
| Quoted Ch. 9 / Ch. 1 rules (§9.15 G3, K2; §9.13 information order; §9.8 H3b; §1.14.5-1; §1.16.1; §8.19 L74) | all present in the copy | as named |
| §8.28 C2 → T9-11; §8.28/§8.29 → T0-03b; §8.24 C9 → T9-08 (f) | present | Ch. 9 and Ch. 0 TODO |

## R2 (`--scope 08.20-08.30,08.99`)
| Word | Judgement |
|---|---|
| A sequitur (08.30 L38) | "non sequitur", standard; no defect |
| A unforgeable (08.28 L20) | plain; it is the stated assumption of §9.17 (c′); no defect |
| A unhomed (08.20 L230) | book jargon for "has no home"; clear from "returned, not invented"; acceptable |
| B DV-7 (08.20 L135) | a source finding label; explained at L152 ("an undefined operator ID"); acceptable |
| B Track-2 (08.20 L139) | "Track 2" already appears at §8.09; explained at L203; acceptable |
| B C-4 (08.20 L54) | an item of the AMD6 commission, explained in place; acceptable |
| B C-12 (08.20 L131) | appears only inside an S0142 quote and is unexplained; low; a gloss is optional |
| C exactly (08.20, 3 uses) | L43 is a quote; L224 is defensible; L114 overstates (F-05) |
| C exactly (08.21, 4 uses) | L44 and L80 are quotes; L56 is a quote; L58 is CON-0256's clause (INVALID, homonym with §9.17 R4 noted at §8.22); no new defect |
| C commission (08.21 ×7, 08.29 ×3) | one sense, a registered charge of work; no homonym |
| D evidence-to-the-leader, self-including, test-oracle, role-and-cost, rewritable, neat, classing | compounds or plain words defined in context (test-oracle: EXT-094); no defect |
| D adviser (27 head uses) | the book's role term for unattributed AI texts; first use 08.00 L57, outside my sections; consistent here |
| D all other tier-D words (absorbing … keep, ~90) | ordinary English used in its ordinary sense in headings; no defect found |

## Coverage of sections
| Section | Read in full |
|---|---|
| 08.20, 08.21, 08.22, 08.23, 08.24, 08.25, 08.26, 08.27, 08.28, 08.29, 08.30 | yes |
| 08.99 | yes. I checked the rows that touch §8.20–§8.30 against the text. Rows L52–L118 (§8.31–§8.81) I did not check against their sections; L110–118 are reviewer D's. |
| Ch. 8 TODO.md | yes (task 3) |

Limits:
- Source checks were sampled (about 40 cited lines across 13 sources). I did not compare every "in meaning" paraphrase.
- The S0147 image was not opened.
- No outside works were opened.
- The forward-use and SEQ check covered only Ch. 9 references from my sections.

## Files opened, commands and searches
Files read (Read tool):
- `…/review/CONSOLIDATION-S0328-S0352/` COMMISSION-C.md, REVIEWER-NOTE.md, SCOPE.md
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- in the copy, `06-theory/manuscript/chapter_08/`: 08.20–08.30, 08.99-change-log.md, TODO.md

Read through Bash (copy unless noted):
- the CONCEPT-REGISTER.tsv header and grep; full rows for CON-0250–0257 and 0259
- `docs/knowledgeos/knowledges-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (header; rows S0141–S0200)
- 08.00-index.md L1–14, L36–48, L88–91 and the trailing notes
- THEORY-MAP L191
- grep/sed on 09.15 L65, L75, L157–158; 09.99 L61; 09.13; 09.08; 09.17 L126–240; 09.00-index list; chapter_09/TODO.md (T9-08–T9-11); chapter_00/TODO.md (T0-03b); 01.14; 01.16; 01.13 (142/144); 08.17 L79; 08.19 L74; 08.60 (C1 lines)
- the `book_search.py` header in the copy
- lane tools: header lines of `undefined_terms.py` and `corpus_quote_check.py` (the quote checker reads the lane-root `02-FILES.jsonl` for corpus paths)
- corpus, allowed list only:
  - S0142 L116, L129, L131, L144, L221–252, L268–282, L313, L327, L329
  - S0143 L18–40, L48, L64, L83
  - S0144 L5, L25, L34–45, L52–56
  - S0145 L44–50, L121
  - S0146 L54–55, L190–222
  - S0148 L131–132, L140
  - S0159 L290–300
  - S0183 L155–164
  - S0190 L25, L103
  - S0199 L60, L65, L68
  - S0150, S0163 and S0169 read whole by a Python comparison script

Commands (all tools with `--root <copy>` from the first call; none of them honours `--help`):
- R1: `register_home_check.py --root <copy> --help`, which ran in full: 1 flag, CON-0133
- R2: `undefined_terms.py --root <copy> --help`, which ran the whole book; then `--scope "<file list>"`, which matched the wrong scope; then `--scope 08.20-08.30,08.99`, saved to `cons4-c-r2.txt`
- R3: `inbound_citations.py --root <copy> "--help"`, then for "§8.23 C1", "CON-0257", "§8.20 C2", "§8.20 C8", "§8.23 C12", "T8-04", "CON-0251", "CON-0250", "§8.20 C19" (`cons4-c-r3.txt`), and for "CON-0259", "§8.20 C6" (`cons4-c-r3b.txt`) and "CON-0252"
- quote check: `corpus_quote_check.py --root <copy> --help` failed because it took "--root" for a file name; then `corpus_quote_check.py <copy 08.20–08.30, 08.99>` (`cons4-c-quote.txt`)
- book search (the copy's tool): "F35", "T8-0[1-5]", "T8-05" (`cons4-c-search1.txt`); "R-CONFLICT", "candidates, never knowledge|never knowledge", "ADR-KOS-002", "integration event", "command loop", "authori[sz]e nothing", "raw record", "blind.set" (`cons4-c-search2.txt`)
- my scripts: `cons4-c-refs.py` (citations against reference tables); Python and difflib checks of S0163/S0150/S0169 and S0163 L656–765
- greps (copy only) for "exactly", "Track 2", "cannot record its own", "OPEN-M6/M7", "of 21x", "142/144", "forward|reading order"
- `ls` and `wc` of the copy and of the lane `tmp/tools/`

Scratch files are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons4-c/` (cons4-c-*.txt, cons4-c-refs.py).

I opened no file listed in REVIEWER-NOTE item 4: no working chapters, working map, register or outline, no BASELINE-REVISIONS, nothing in `00-access/`, and no git commands. The session's starting git-status snapshot named files under `00-access/`; I did not use or open them. I edited nothing in the repository.
