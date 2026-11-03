CONSOLIDATION S0353–S0377: REPORT C

**Scope.** Ch. 9 §9.15, §9.16 and §9.17 in the frozen copy, read in full. Concept verdicts for CON-0193, 0194 and 0261–0265. Coverage of the pointers and of Ch. 9 TODO.md. R1, R2, R3 and the quote check.

## Summary (15 lines)
1. Within their stated scope, the three sections mostly hold. All 41 quotations check verbatim (quote check: 13, 21 and 7 found, 0 not found). The 35 source passages I spot-checked agree with the text, with two small exceptions (F11, F14). I re-ran the anchor model (§9.17 R4) and got the same counts exactly (384,000 worlds).
2. Largest risk (F2): §9.16 R2 (a) says "the merged record is in D unless the merge is refused". §9.17 R3 (c) has since added designation clauses, and it states that a merged record can leave D (a fork, or a foreign move) without the merge rule refusing it. Three rows of the R1 table depend on R2 (a): "mergeable" (L67), Lemma D across records (L69) and T1 on merges (L73).
3. Stale text and contradictions. "The recorder is read by no clause / drives no mark" (§9.15 L94, L138) is false since §9.13 v9. The guard rule "not F" (§9.16 L191) is superseded. Several R1 rows carry the wrong status, which meets the R1 table's own disproof criterion (§9.16 L42).
4. Ubiquitous language: "root designation" and "recorder" each have two meanings. Since the evidence kinds were adopted, "D" is unqualified next to D_S/D_W.
5. Verdicts: CON-0265 VALIDATED (a second, independent verdict). CON-0193, 0194, 0261 and 0264 PROVISIONAL. CON-0262 and 0263 are INVALID, and each core is restated correctly.
6. **Process independence:** this is process independence only. I saw no other reviewer's report, and I used the same corpus and tools as the Professor.
7. **Breach, disclosed:** two of my first tool calls ran on the working book, not the copy. Details are in the file list. I used none of their output.

## Findings
| ID | Section | Finding | Severity | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| F2 | §9.16 R2 (a), L104; R1 L67, L69, L73 | "The merged record is in D unless the merge is refused" was not re-checked against D's designation clauses (v16–v20). The book itself says this premise is false (F4 rule): two applications that designate one store give a merged record outside D. So does a single foreign move. | **critical** (F4: the book states the premise false, and L67/L69/L73 depend on it). The effect is a narrower scope, not a collapse. | §9.17 L166, L173 | Add to (a): "…unless the merge is refused **or the merged record fails D's designation clauses (§9.17 R3 (c))**". In L67, L69 and L73, replace "which R2 gives" with "when the merged record is in D". | Inbound "§9.16 R2" lines (§8.15 L22/L78, §8.17 L19/L59/L76/L104, §8.18 L16/L44/L49, §8.19 L76/L130, §8.39 L19/L72, §8.53 L17, §8.90 L15) cite the identity rule or the union, not (a). Disposition: unaffected. | the clause is added and the three rows are restated |
| F1 | §9.15 R3 L94; R6 L138 | "I15's recorder field is read by no clause"; "The recorder field drives no mark". Both are false. Since §9.13 v9 the mark "inconsistent recording status" compares recorder and actor, and §9.15 L141 itself says so. The owner mark reads the recording application (§9.16 L318), and since v20 so does a D clause (§9.17 L173). R3's conclusion survives, because all of these are recorded claims, not attribution. | high (a contradiction inside the section) | §9.13 L51; §9.19 L195 ("read (`app`)") | Write: "Φ and current-authority read no recorder. One mark (v9), the owner check and mark, and a D clause (v20) read recorded recorder or application **claims**. No clause reads attribution itself." | 0 inbound citations of R3/R6 on this point. §8.32 L75–76 and §10.5 L14/L373 cite R3's conclusion, which stands: no change. | the text is restated |
| F3 | §9.16 R1 L68, L74, L81, L82 | Rows with the wrong status. F36 says "recommended, for decision", but R6 (L359) and §9.11 L96 say it was adopted. Laws 9b–31 say "open", but R3a decided them. Result 5 says "a test is proposed", but R7 was adopted provisionally. The F22 row does not mention that (C + O) was decided (R4a). | high (meets the table's own disproof criterion, L42) | §9.16 L42, L359, L388; §9.11 L96 | Restate the four rows. | §9.11 L96 and §10.3 L113 already state F36 as adopted, so they agree with the correction. | the rows are restated |
| F4 | §9.16 R3a L191 | "refuses a consumption while another consumption of the grant is not F" is superseded by "every recorded use counts". §9.13 L37 carries a superseded note (B-F2a); L191 does not. | high (a contradiction with R1 L86, R4a L264 and §9.15 G4) | §9.16 L86, L264; §9.13 L37 | Add the same superseded note. | §9.13 L37 is already annotated; §8.32 L23/L74, §8.14 L15/L32/L36 and §10.3 L70 cite prevent-or-detect, not the guard: no change. | the note is added |
| F5 | §9.15 R7 L156–157; L154; §9.16 L93 | Homonyms. (a) "Root designation" is L5's name (the root authority entries) and also §9.17's initial carrier (L11). (b) "Recorder" means I15's recorder, and also the recording application (`app`): L10 calls `app` "the entry's recorder", yet §9.16 L93 leaves the recorder out of identity and puts the recording application in. | normal | as cited | Rename L5 to "root entries". Write "recording application" for `app` throughout, apart from the I15 recorder. | §8.17 L90, §8.24 L126, §8.22 L57: check which meaning each intends. | the terms are qualified |
| F6 | §9.15 L148, L197; §9.17 *passim* | After the two-level domain (2026-10-31), only G1 reads "D_S". Elsewhere "outside D" is unqualified. | normal | §9.18 L130, L243 | Qualify each use as D_S, D_W, or both (record-level, §9.18 (c4)). | — | each use is qualified |
| F7 | §9.15.7 L204 | "§9.13.7's world list maps to K1–K7 and L1–L10 (R7)" claims more than R7 maps: L171 gives K5, K1, L6, L5 and L1–L3 only. | normal | §9.15 L171 | State the mapping that R7 actually gives. | — | the line is restated |
| F8 | §9.15 R8 (c) L176; §9.16 R7 heading | "stays provisional until C19's review". C19 has taken place, and neither section says what it decided about R7. | normal | §9.15 L10 (C19 settled v6) | State R7's status after C19. | — | the status is stated |
| F9 | §9.17 R3 (c) L164, L175 | The "rival" (membership by citation down-closure) is the computation that L175 then uses. Membership by acceptance (§9.17.2, CON-0264) and down-closure agree only within one serialised kernel; they differ across merged applications, as L175's own correction shows. | normal (two meanings merged) | §9.17 L43, L164, L175 | Define membership by acceptance. Present down-closure as its characterisation within one kernel, and state where the two diverge. | CON-0264 register row ("membership across the change = the cited old-store entries…"): change with it. | the text is restated |
| F10 | §9.17 R4 | Omission. G0 and G2 block retirement for as long as any foreign entry, or any later member not on the list, remains in OLD. That liveness cost is not stated. The corpus has its analogue ("INDEFINITELY", S0183 L196, used only in §9.16 L336). | normal (does not defeat CON-0265) | §9.17 L237, L243 | State the cost, and name disposal as the condition for passing. | — | the cost is stated |
| F11 | §9.17.5 L302 | S1375 bounds authority by domain, proposition/decision type, **scope**, time and **context**. The book lists only three of the five. | normal | S1375 L971–991 | List all five. | — | corrected |
| F12 | Register CON-0193, CON-0194 | The CON-0193 core is stale: "K2 single view (… while ties are by position)", L1–L7, "D names no recorder… returned", with no restated K2, L11 or K1 coverage clause. In CON-0194, "with ties by position (now)" is stated as current. | normal | register rows 194–195; §9.15 v11 | Restate both cores to the current sections. | BOOK-OUTLINE L175 "L1–L7" (out of scope, one line) | the register is updated |
| F13 | §9.16 R4d/R4c; §9.15 K1/K2 | Reading order. R4d (L225) uses R4c (L249) before it appears; the reading note at L195 only compensates. §9.15 uses ρ (K1 cell) and D_S before §9.17.2 and §9.18 define them. | normal | as cited | Move R4c before R4d, and gloss ρ where it first appears. | — | reordered |
| F14 | §9.15 R4 L105 | "identities are 'self-declared and NOT attestable' (S0090 L11)": that line concerns one verifier. The general claim rests on INV-ATTR-2. | normal | S0090 L11 | Cite the invariant, not the line. | — | — |
| F15 | TODO T9-08 | The "resolution criteria" cell holds a history note, not a criterion. | normal | TODO L18 | State a criterion. | — | — |

## Concept verdicts
| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0193 | PROVISIONAL | R3's proof is valid but definitional, given G1. F1, F5, F7 and F12 remain to fix. R8 (c) rests on R7, which is Int and provisional. |
| CON-0194 | PROVISIONAL | R2 (b) holds for records without designations. (a) is false as stated (F2). The R1 statuses are wrong (F3). Order independence under the extended identity is unchecked (T9-18). |
| CON-0261 | PROVISIONAL | I checked M2 and the three countermodels showing K1, K2-w and K2-r independent: they are valid. The old K2 follows within one designation; across a carrier change it is only a sketch. K2-d is the Professor's presupposition, and K2-r's refutation is open (T9-08 (a)). |
| CON-0262 | INVALID (core checked) | Restated in §9.17.6 and the register as defeated ("iff appended while designated"; "only with a closing list"). This matches §9.17.2 and R3 (b). |
| CON-0263 | INVALID (core checked) | The restatement matches R4's paragraph on the independence condition (L262–265): the three conditions are aspects of one gate, not independent. |
| CON-0264 | PROVISIONAL | The definitions are ambiguous (F9). The rule depends on an attestation assumed unforgeable, while D and the model check only that one is present (L173, L178). |
| CON-0265 | VALIDATED | C1–C5 checked step by step; the model re-run reproduces every count. Scope: an exact list, not derived from NEW, no write between check and act. F10 (a cost) is an omission, not a defect. |

## Coverage
| Item | Status | Home |
|---|---|---|
| Pointers from §9.15–§9.17 to later chapters | none found (grep for §10–§19 and Ch. 1x) | — |
| §9.15 L63 → §9.18.6 (D_S, Q-A) | resolves | §9.18 L130 (definition), L243 |
| T9-07, T9-11, T9-18 | still open, and consistent with the sections | §9.16 R4d, L93/L319; §9.17 (c′) |
| T9-08 | open: (a), (d), (e), (f) still open; criteria cell stale (F15) | §9.17.5 |
| T9-09, T9-10 | resolved, consistent with §9.17 R3 (c), (c′) | §9.17 L159–205 |
| T9-01–06, 12–17, 19–21 | pointer targets exist (§9.19.4, §9.19.6, §9.19.9, §9.20.7–8, §9.18.4–6, §9.18.10, §9.13 L52, §9.6.3, §9.11.3); content **not checked** (outside my sections) | headings in the copy |

## R2 (every candidate in scope, judged)
| Words | Judgement |
|---|---|
| 09.15: band, duress, institutional, constitutive, verifie, select, honest, capacity, strongest, cost | plain English, or defined in place ("verifies", L53; "constitutive", §9.15.8). "Honest key" (L181) means L2 and L3 hold; acceptable. |
| 09.16: tombstoned, composed-system, crowded, once-only, weakened, linear, saying, annulled, addressed, strongest, unique, reconciled, dropped, violation, passed, merged, cost, define, once | defined in place (tombstone §9.16.2; "linear extension" L99; "crowded" L129; "once-only" laws 18/31; "composed-system" by its §9.11 R3 pointer) or plain English |
| 09.17: TURN8, survivor-derived, irreversible, vacuously, contribution, implicit, restatement, intact, message, presuppose, claimed, replaced, fourth, agree | "TURN8" is a read-log tag (L326), acceptable; "survivor-derived" is defined at L229; the rest are plain English |
| Used in scope, first used elsewhere: mover, serialised, unforgeable; review IDs (A-xx, B-xx, W-x) | "mover" is glossed at L166; the review IDs are labels |

## Coverage of sections
§9.15: read in full. §9.16: read in full (L1–405, L406–496). §9.17: read in full. TODO.md: read in full. Not checked: CORPUS-ALLOWED.tsv. I could not locate it without opening the forbidden folders, so I tested candidate paths with `test -f` and found none. All sources cited here have status CONTENT in 02-FILES.jsonl, and none is on SCOPE's never-list. I did not re-run ce_model.py.

## Files opened, commands and searches
- In the folder: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-C.md.
- Instruction: 03-methodology/REVIEWER-INSTRUCTIONS.md.
- Copy (BOOK-UNDER-REVIEW/06-theory/manuscript/), read in full: chapter_09/09.15-the-trust-boundary.md, 09.16-reassessment.md, 09.17-the-designated-record-and-its-anchors.md, TODO.md.
- Copy, read in part:
  - 09.13-the-evaluator.md: L37, L39, L46, L52, and the lines grep "recorder" returned;
  - 09.18-evidence-in-the-record.md: headings; grep for D_S/Q-A and T9 IDs;
  - 09.19-req-and-a-…md, 09.20-the-state-space.md, 09.06-boundary-test-authority-recording.md: headings; grep for T9 IDs;
  - chapter_08/08.32-…md: L74–76;
  - 06-theory/CONCEPT-REGISTER.tsv: grep for the CON-0193, 0194 and 0261–0265 rows;
  - copy tmp/tools/book_search.py: grep, and runs for CON-0193, CON-0194, CON-0261, CON-0265, §9.17, CON-0262, "recording application", §9.13.2, §9.18.5, §9.19.9, §9.20.7, §9.11.3, §9.6.3, "§9.1 C6", §9.19.4.
- Tools (tmp/tools/):
  - register_home_check.py --root copy (241 rows, 0 flags);
  - undefined_terms.py --root copy, with --scope 09.15 (one call that took only the first value), then 09.16 and 09.17 separately;
  - corpus_quote_check.py on the three section files of the copy;
  - inbound_citations.py --root copy, with "§9.15 R3" "§9.15 R6" "§9.16 R3a" "§9.16 R1" "§9.15 R7" "§9.15 R8" "§9.17 R3" "§9.17 R4" "§9.17 R2" CON-0193, then "§9.15" "§9.16" "§9.17", filtered with grep;
  - anchor_model.py, run once with output to scratch;
  - tool sources inspected with grep, sed or head: corpus_quote_check.py, inbound_citations.py L1–80, anchor_model.py.
- **Breach (REVIEWER-NOTE item 4):** two of my first calls reached the working book through the tools' default root:
  - `python3 -I tmp/tools/book_search.py --help` (0 hits);
  - `python3 -I tmp/tools/undefined_terms.py --help`, run without --root, which printed the start of the working book's whole-book list.

  I used neither output. No working file was opened directly.
- Corpus, via 02-FILES.jsonl and a local line printer (scratch show.py):
  - S0078 L163, 218, 248–260;
  - S0341 L18–26;
  - S0300 L422–425, 436–444, 452–455, 478–489, 511–514, 521–524, 576–579, 607–610, 799–830, 894–906, 939–948, 1007–1009, 1039–1042, 1202–1205, 1285–1287, 1313–1316;
  - S0206 L281–284, 393–398, 515–518;
  - S0253 L184–187, 250–259;
  - S0917 L218–221, 1320–1332;
  - S0904 L326–329;
  - S1375 L60–130, 961–993;
  - S0190 L23–26;
  - S0915 L812–828;
  - S0071 L72–75;
  - S1519 L233–235;
  - S0969 L1213–1264;
  - S0991 L1141–1146, 2056–2064;
  - S0103 L34–36, 74–78, 142–144;
  - S0431 L17–20;
  - S0091 L64–66, 120–122;
  - S0090 L10–12;
  - S0095 L215–222, plus a scan for "X-2";
  - S0336 L71–73;
  - S0947 L1218–1290;
  - S0993 L415–422.

  The quote checker also read the sources cited in the three sections.
- Other: `test -f` on candidate paths for CORPUS-ALLOWED.tsv and for the model tools; `wc -l` on the section files. No git command of any kind, no glob, no recursive search, no directory listing. Nothing in the repository was edited. Tool outputs are in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons-s0353-c.
