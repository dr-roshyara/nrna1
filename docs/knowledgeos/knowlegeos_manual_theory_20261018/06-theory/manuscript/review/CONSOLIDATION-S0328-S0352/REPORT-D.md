CONSOLIDATION S0328–S0352: REPORT D

Reviewer D, consolidation review (Rulebook v1.4 §12) of the frozen copy `…/CONSOLIDATION-S0328-S0352/BOOK-UNDER-REVIEW/` only, per COMMISSION-D, REVIEWER-NOTE and SCOPE §2.6 (reviewer D).

## 1. Assessment
- **§8.73–§8.81 (C80–C88).** Within their stated scope these sections are reliable. The claims are labelled carefully, the undecided readings stay open, and every quotation I checked matches its source in meaning. I found no critical defect.
- **Largest risk: the bookkeeping around the sections, not the theory.**
  - **§9.19.9 records the Principal's U1 ruling as the opposite option to the one it quotes.**
  - Several summaries and pointers were not carried along with later corrections: the §8.74 C4 scoping, the §8.67.3 line pointers, the forward-use note in the Ch. 8 index, and the 9.19 index row.

## 2. Findings (ranked)

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| D-01 | `09.19` §9.19.9 L395 (U1) | Says "**Decided: (a) next, before reading resumes**", but the ruling quoted in the same sentence is "T9-02 is taken up **(b) later, after reading resumes**". The other passages that apply U1 also say (b): TODO T9-02 L12 ("taken up later, after reading resumes"), and reading has in fact resumed (S0328–S0352). This is a contradiction about a Principal's decision. | high | 09.19 L395; TODO L12; THEORY-MAP L431 | "Decided: (b) later, after reading resumes" | TODO L12: consistent, keep. THEORY-MAP L431 ("U1–U6 decided"): keep. 09.19 L72, L170, L411 ("escalated (U1)"): historical, keep. 09.00 L29: see D-02. | L395 names (b), as the quotation does |
| D-02 | `09.00` row 9.19 (L29) and §9.19.9 heading | The index row ends "U1–U6 **routed**", but all six are decided (§9.19.9 L392–397). The heading "Unresolved questions, routed to the Principal" and the copy-ready "(2026-11-0_)" placeholders are also stale. Pointer stale; no content missing. | normal | 09.00 L29; 09.19 L388–397 | "U1–U6 decided 2026-11-01 (U1 (b), U2 (a), U3 (b), U4 (d), U5 (a), U6 (a))"; retitle §9.19.9 "…routed and decided" | TODO L11 ("routed U1–U6"): same fix, out of scope | Row and heading say "decided" |
| D-03 | `08.74` C4 (L78 heading, L85 body, home table L21) | The scoping "on this work item's record" reached the title, plain language 5, the index row and the map line, but not the claim itself. C4's heading reads "which no process holds on the record" and L85 reads "no Governance lane exists". Change-log row 112 lists "C4 (R3)" as changed, but C4 has no R3 note and no scoping. Source: S0333 L38 says "on this work item"; S0336 L39 places a Governance lane on `KOS-OQ-001`. | normal | 08.74 L21, L78, L85; 08.99 L112; 08.75 L97 (W-07) | Add "on this work item" at L21, L78 and L85, or put an R3 note in C4 | Twelve claim-level inbound lines (R3 output). 08.75 L20, L68, L106 and 08.76 L68 already scope the claim: keep. 08.75 L3, 08.76 L3 and 08.77 L3/L20/L93 are dependency or home lists: keep. | C4 is scoped as its title is |
| D-04 | `08.68` L140; `08.71` L129; change-log row 114 | The W-15 pointer sweep is incomplete. 08.68 L140 still points to "§8.67.3 L118" for the monotone fail-safe, which is at L119 (L118 is V-4/V-7). 08.71 L129 points to "§8.67.3 L123" for the V-3 homonym, which is now at L124 (L123 is the "§18" slip). Row 114 says §8.68 and §8.71 were swept. Row 114 also lists §8.74, but §8.74 contains no line pointer into §8.67.3 (L20, L70); without diffs I cannot tell what changed there. | normal | 08.67 L118–L124; 08.68 L140; 08.71 L129 | L140 → "L119"; L129 → "L124" | No tool hits (R3 does not parse "§8.67.3"). By search: 08.68 L22, L78; 08.71 L22, L65, L114; 08.72 L17; 08.76 L67; 08.77 L62–63, L160 are all correct: keep. | Every §8.67.3 pointer lands on its target |
| D-05 | `08.00` forward-use note (L99) | SEQ: the note is extended only to "§8.62–§8.72" (F-08). §8.73–§8.81 also use Ch. 9 (§9.13; §9.15 K1, R4 and §9.15.8; §9.16 R6, law 20a; §9.17 R1, R4–R5; §9.19 T7), CON-0193, CON-0244 and CON-0265. Earlier sections carry R3 notes that cite later ones: §8.67 C3 → §8.77; §8.72 C4 → §8.73, §8.77, §8.79; §8.73 → §8.74, §8.77; §8.74 → §8.75, §8.77; §8.75 → §8.76; §8.71 C2 → §8.76; §8.76 → §8.78; §8.77 → §8.78, §8.79; §8.78 → §8.79, §8.80; §8.79 → §8.80; §8.80 → §8.81. | normal | 08.00 L99; section headers of §8.73–§8.81 | Extend the note to §8.73–§8.81 with these lists | none (index note) | Note covers §8.81 |
| D-06 | `08.78` C3–C4 | Wrong line pointers to S0344. The "Do NOT … self-register" quotation is cited L602–606, but "self-register;" is at **L608** (§8.80 C1 cites L608 correctly). The MISMATCH-only ban is cited "(L606)". "current role ↓ human approval ↓ next role" is cited L924–928; it is at **L926–930**. | normal | S0344 L603–608, L926–930 (sed/grep) | L603–608; L608; L926–930 | §8.78 C4 claim lines (11): 08.80 L46 already uses L608, keep; the rest cite C4 without line numbers, keep | Pointers match the source |
| D-07 | `08.75` C1 (L46) | "Thirteen of the fifteen minimum tests … are 'no transition' or 'no selection' properties". Only tests 1, 3–7 and 11 (seven) are no-transition or no-selection properties. Tests 8–10 and 13–15 are conditional or structural, and are satisfied vacuously by a refuse-all orchestrator. The conclusion (13 of 15 are met by the trivial orchestrator) stands; the description of the tests does not. The wording came from W's inventory. | normal | S0334 L443–459 | "…thirteen are met by an orchestrator that refuses everything (seven no-transition/no-selection, six vacuously)" | §8.75 C1: 10 claim-level lines (08.76 L21, L96; 08.77 L22, L117; 08.78 L13, L14, L63; headers). None uses the count: keep. | Restated |

## 3. Concept verdicts
| ID | Verdict | Reason |
|---|---|---|
| — | none due | SCOPE §2.2: §8.73–§8.81 home no concept (confirmed: the register has no row homed in 08.73–08.81). All concepts in §2.2 are homed in the sections of reviewers A, B and C. CON-0125 and CON-0233 are used here only as agreement, with no verdict. |

## 4. Coverage against the inventories (C80–C88)
**Covered:**
- The core items of every window: self-appended lanes and `recordedBy: human`; V-1 PASS on the narrow reading; the relay that narrows; the completion known only from prose; UNRESOLVED with a lane on record; the senses of "Governance"; one "Yes", two acts; the trivial-solution checks; two records of one act; the subagent probe (n=1); init as a governed write; FU-1 and FU-2; the AMBIGUOUS case (safe, not right); STOP as a precondition; "canonical" before verified; never required to inspect, and not prompted; the STOP recorder tension; CONTINUATION; placeholder appointments; self-registration before the capability; "VERIFIED" against "failed to falsify"; the bar's unit; the misaligned (a)–(e) labels.
- Errors in W's own inventories were corrected, not distorted. Examples: §8.79.3 corrects the bars "growing with each role"; §8.80.5 corrects the self-chosen bars.
- No DISTORTED item found.

**MISSING (all low, ranked):**
1. "authority" as a homonym: the business authority act (S0334 L166) against AST-015 as "SINGLE WORKFLOW AUTHORITY". Only partly touched, at §8.77 C5 and §8.78.3.
2. The gate changing over time, from 7 to 8 conditions with condition 3 new (S0333).
3. Precedent used as binding (S0332 L77).
4. How a candidate checks that it is not the PO/ARB (S0339).
5. The non-identity chain is stated pairwise only, with no order (S0344 L481–494).
6. ".codex neutral" dropped in the S0330 relay.

**JUSTIFIABLY EXCLUDED:**
- The 49/50 test-count arithmetic (S0329; the inventory itself resolves it).
- The work-item name moving from provisional to confirmed (S0337 L15).
- The human repeating the direction after a refusal (S0352 L29). This is covered in substance by §8.81.3 "Liveness".

## 5. R2 (`undefined_terms.py --root <copy> --scope 08.73-08.81`)
| Word | Judgement |
|---|---|
| appender (tier A) | Self-explanatory from "append" (an estate term). No gloss needed. |
| AST-018 (tier B, first 08.77 L6) | An identifier, but its identity with §8.75's orchestrator is only implicit (plain language 5). Gloss it at first use as "the asset implementing §8.75 C1's orchestrator". Normal, low. |
| beneficiary | A term of art used from §8.73's title onward without a gloss. Add "the process the record entitles". Low. |
| witness | Defined in §8.73's head (L12). OK. |
| prose-against-store | Defined by use at §8.75 C5 and §8.76 C1. OK. |
| escalator | Glossed at §8.78 L7. OK. |
| self-nomination | Explained in §8.74 C5. OK. |
| direct-write | Explained in the same bullet (§8.80.3). OK. |
| mtime | Glossed as "file modification times" in §8.74 C5. OK. |
| commission | Three senses noted in §8.80.3. OK. |
| must-never | Established book term (§9.19 T7). OK. |
| lane-holding, near-homonymous, unglossed, codifying, refuser, doer | Plain derivations. OK. |
| capacity, appointment, appoint, activate, predecessor, snapshot, transcript, freedom (quoting the source) | Estate or plain terms, clear in context. OK. |
| ninth, eight, nine, plus, hand, created, create, started, asked, answered, quoted, once, exactly, because, keep, agree, recommend, recommended, weak, weaker, informative, reproduce, predate, resolve, refute, safety, recovery, strengthen, addition, specifie, generalize, progress, wait, touch, completion, restatement, explain, consume, expression, comparison, standard, asserted, correctly, nobody, escalate, processe | Ordinary English or plural/stem artefacts. No gloss needed. |

## 6. Limits
- `corpus_quote_check.py` checked **0 quotes** in §8.73–§8.81, because it matches only the `"…" [Snnnn Lx]` format. I ran my own heuristic check (scratch script). Result: 305 quotations found in meaning, 9 not matched, and 100 skipped by the script, so those 100 were not verified.
- Of the 9 not matched, 6 are formatting artefacts (`#` comments, ↓, line breaks) or ranges off by one or two lines (D-06; S0341 L62–64; S0329 L165–166). One is a paraphrase in quotation marks, marked "in meaning" (D-a1).
- R3 does not parse "§8.67.3" or "§9.19.9", so D-01 and D-04 were traced by search.
- Not checked: whether the Ch. 8 rotation span "§8.00–§8.30" equals the span reviewed at S0200 (BOOK-OUTLINE L207); this needs older versions.

**Summary (≤15 lines):**
1. §8.73–§8.81 hold within their scope. Quotations match in meaning, labels are careful, and readings are left open where the sources do not decide.
2. No critical finding. One high (D-01): §9.19.9 records U1 as "(a) next" while quoting the Principal's "(b) later".
3. Five normal findings on stale or wrong pointers and summaries (D-02 to D-06), one normal mis-description (D-07), and four normal low-priority points (appendix).
4. No concept verdicts are due from D: no concept is homed in these sections.
5. Coverage of C80–C88 is good. Six low misses; nothing distorted.
6. The change log rows L110–118 match the text, except row 112 (§8.74 C4 not actually changed) and row 114 (sweep incomplete; §8.74 has no pointer to change).
7. THEORY-MAP L248–256 and L431–435 match the sections and counts (343 = 318 + 25; register 56/113/96 = 265, recounted from the copy's register).
8. BOOK-OUTLINE L186–209: the rotation order is consistent. Its heading still cites Rulebook v1.3 (D-a3).

**Process independence:** I am a separate subagent run with no access to the other reviewers' reports or to the Professor's drafts. This is process independence, not personal independence. Like the book's other reviewers, I run in the Professor's harness (§8.76 C4).

---
**Appendix (lower priority, normal):**
- **D-a1.** §8.80 C1 L47 puts a paraphrase in quotation marks: "registration has one writer, so self-registration would manufacture authority" (S0139 L99, "in meaning"). Drop the quotation marks.
- **D-a2.** §8.73's header lists "§8.71 C1, C3–C4 and §8.72 C2–C4", but the body also uses §8.71 C2 (§8.73.3) and §8.72.4 (L22, L100).
- **D-a3.** BOOK-OUTLINE L186 heading: "Rulebook v1.3 §12" against v1.4 §12 in force. Add "(v1.4 §12 since 2026-10-24)".
- **D-a4.** §8.80 C1's Ded rests on the absence of a commissioning act within the window. That is valid as scoped ("No commissioning … is in the window"), but it is a sketch, not a proof.
- Proof-step judgements: §8.78 C2 adequate (premise H stated); §8.79 C1 adequate (each step sourced); §8.77 C2 adequate. One imprecision in §8.77 C2: full-length commit hashes do not match `\b[a-f0-9]{6,16}\b`; only short forms do.

**Out-of-scope observations (one line each):**
- 09.19 L114 'g1 is "not placed" (§9.18 L273)' is stale since U2 (L273 now says placed in the kernel).
- R1 (`register_home_check`): 1 flag, CON-0133 (an H4 in Ch. 6, reviewer A's span).
- `close_source` branch list (THEORY-MAP L432): stale by three names, as the map itself says.

---
**Files opened (all read-only):**
- **Review folder:**
  - `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/CONSOLIDATION-S0328-S0352/COMMISSION-D.md`
  - `REVIEWER-NOTE.md`, `SCOPE.md` (same folder)
- **Method:** `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
- **Copy, read in full:** `06-theory/manuscript/chapter_08/08.73-…` to `08.81-…` (nine files).
- **Copy, by line:**
  - `08.00-index.md`: L1–12, L86–110, the L99 note
  - `08.99-change-log.md`: L1–5, L108–120
  - `08.67`: L60–72, L112–125
  - `08.68`: L3, L22, L78, L135–141
  - `08.71`: L22, L63, L65, L114, L129
  - `08.72`: L17, L95, L97, L125
  - `08.74`: grep for §8.67.3
  - `chapter_09/09.10`: L20, L75, L105
  - `09.18`: L82, L273
  - `chapter_09/TODO.md`: L1–4, L11, L12, L31
  - `09.00-index.md`: L29
  - `09.19`: L72, L114, L170, L385–398, L411
  - `manuscript/BOOK-OUTLINE.md`: L184–210
  - `06-theory/THEORY-MAP.md`: L246–257, L429–436
  - `06-theory/CONCEPT-REGISTER.tsv`: header and status counts
  - Copy-wide greps for "§8.67.3", "monotone fail-safe", "S0431", "U1"
- **Inventories:** `review/C80-S0328-S0330`, `C81-S0331-S0333`, `C82-S0334-S0336`, `C83-S0337-S0340`, `C84-S0341-S0343`, `C85-S0344`, `C86-S0345-S0348`, `C87-S0349-S0350`, `C88-S0351-S0352` `/STEP1-INVENTORY.md`.
- **Corpus:**
  - `CORPUS-ALLOWED.tsv` (grep/awk).
  - My quote script loaded, whole: S0139, S0192, S0320, S0323, S0324, S0327, S0328–S0352. All are on the allowed list.
  - Line reads: S0320 L76; S0323 L12–14; S0324 L67, L83–88, L106–109; S0327 L21, L118–121; S0329 L164–166; S0334 L229–233, L443–460; S0336 L48, L74, L86, L108–111; S0341 L62–65; S0344 L600–608, L922–930 (plus grep); S0345 L55; S0347 L20–31; S0348 L47; S0349 L236–239; S0351 L107–108; S0352 L110–112; S0139 L97–101; S0192 L64, L78.
- **Tool sources read:** `tmp/tools/corpus_quote_check.py` (L1–60), `inbound_citations.py` (L1–25), `undefined_terms.py` (usage lines).
- **Not opened:** nothing in `00-access/`, no working chapter, map, register or outline files, and not BASELINE-REVISIONS.md (including the copy's). No sealed or barred source. No git command of any kind.

**Commands run** (all with `--root <copy>` from the first call):
- `register_home_check.py --root <copy> --help` (it ran the check: 1 flag).
- `undefined_terms.py --root <copy> --help`.
- `inbound_citations.py --root <copy> --help`.
- `undefined_terms.py --root <copy> --scope 08.73 08.74 …` (only 08.73 taken), then `--scope 08.73-08.81`.
- `corpus_quote_check.py <copy 08.73–08.81>` (0 checked).
- `inbound_citations.py --root <copy>` for "§8.74 C4", "§8.78 C4", "§8.78 C3", "§8.75 C1", "§8.67.3", "§9.19.9".
- My script `cons4-d-qcheck.py` on 08.73–08.81, and a listing of the sources it loaded.
- `ls`/`find`/`wc` on the copy.

**Scratch files** (outside the repo), all in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons4-d/`:
- `cons4-d-r2.txt`, `cons4-d-quote.txt`, `cons4-d-qcheck.py`, `cons4-d-qcheck-out.txt`
- `cons4-d-r3-8.74C4.txt`, `cons4-d-r3-8.78C4.txt`, `cons4-d-r3-8.78C3.txt`, `cons4-d-r3-8.75C1.txt`, `cons4-d-r3-8.67.3.txt`, `cons4-d-r3-9.19.9.txt`

The harness also saved one long tool output under `~/.claude/projects/…/tool-results/`. I did not open it.

Nothing in the repository was edited.
