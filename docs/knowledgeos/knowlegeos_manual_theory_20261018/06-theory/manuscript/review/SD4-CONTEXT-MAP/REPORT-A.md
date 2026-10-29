SD4 REVIEW REPORT A

Reviewer A, strategic-design turn 4 (§10.3.6 and §10.4 draft). Scope as in `review/SD4-CONTEXT-MAP/BRIEF.md`. Every judgement below is mine (I). Verified facts and recommendations are kept in separate columns. I edited nothing in the repository.

## Summary (10 lines)
1. **§10.3.6, critical.** Rule (d2) needs "the context that appended it". In the book, the only appender that is recorded and checked is an *application*: the `app` field, L10, §9.16 R4a, §9.17 (c′). R6 and R4a have applications (WM, a grant's owner application) appending standing's kinds. So the one admitted context, S, fails (d2) as written, or else contexts and applications collapse into one thing.
2. **§10.3.6, high.** The claim that (d1)–(d3) have "the same form" as R4a and (c′) does not hold. Those rules give one owner application per instance and are model-checked. (d1)–(d3) give one owner context per kind and are not model-checked. Premise (p2) cannot be met today (§10.3.2 L58), so S's "admitted" rests on a premise that is not met. The partition controls who may append, not who may change: standing's `remove`, `annul` and `designate` reach any entry.
3. **K2 compatibility, normal.** It holds for K2-d and K2-w. For K2-r it rests on an unstated premise. K2 is not adjusted, but D and the append guard are.
4. **§10.4.3, high.** K3's inputs (covers; the actor–holder link) are not record entries (§9.15 G1 L63; "the content of covers" is open, §9.13 L327). So the draft's own falsifier is already met. The "narrowed law" changes the definition and has no falsifier.
5. **R8 and R9 (§10.4.2), high.** Both misread K3. Covers relates a *grant's* scope to an *act's* scope (§9.13 L98), and CON-0173 is on S's list. K3's identity link is the actor–holder link (I11), not item identity. Neither input is shown to come from N or C.
6. **Laws 24 and 27 (§10.4.3), high.** Law 24 as "against" is contradicted by §9.16 R4c: for single-use grants the kernel is the coordination point. Law 27 is an obligation on the application and says nothing about what the kernel depends on. Law 18 and K3 are treated differently although they have the same structure.
7. **Count, high.** The header says 9 conditional relationships; the table marks 8. R10 bundles two relationships. R13 depends on N and W, which are pending, and R11 partly on G1, yet both are marked unconditional.
8. **Missing relationships, high.** S → AU, W → AU, N → W/A, N → WM, C → W, and S ↔ C (for shared invariants) are absent. "W → S below" (§10.4.1) points to a relationship that is not in the map. This is lost content, not a stale pointer.
9. **Prior work, high.** S0069, an accepted context map of the estate with a relationship register, is omitted. So are §1.14.4-7's upward-edge test (which §6.6 C8 itself leaves open), CON-0147 (§6.7 C5), §8.28 C1 and §8.09 C7. S0196 is read selectively.
10. **Verdict.** §10.3.6: PROVISIONAL. §10.4.3's result "holds in a narrowed form": INVALID as stated. The map itself: PROVISIONAL and incomplete.

**Process independence:** this review is process-independent only: a separate commission and separate reads, with no access to the decision record or other reviews. It is not personal or model independence.

## Findings
| ID | Finding | Sev | Evidence (verified) | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | (d2)/(d3) "the context that appended it": the book records and checks only an appending **application** (`app`, L10). WM appends standing's kinds through S's contract (R6), and the owner application records uses (R4a). So for S, (d2) either fails, or WM must *be* S, which contradicts "WM not a context". The p1 analogy with "the moving application" carries the same category slip | critical | §9.15 L154 (L10); §9.16 L223 (R4a "recorded by its owner" application); §10.4 R6; §10.3.6 p1 | Define the appender (application, context, or a kernel step acting for X), and give a stated map from applications to contexts. Alternatively, restate (d2) as "kinds whose acceptance rule is X's", and drop "recorded by X". Then re-apply the rule to S | 0 citing §10.3.6; 10.02 L5 (status note); 10.00 L10; 10.04 L3, L11 | (d2) is defined over an appender that the book records, and S's admission is re-derived |
| A-02 | "Same form as R4a and (c′)" is an analogy, not a demonstration. R4a/(c′): one application per instance, transferable, decided, model-checked (v14, v20). (d1)–(d3): one context per kind, no model check | high | §9.16 L223; §9.17 L166, L199; §9.13 L48 | Say "analogous". Add a model check for the D clause in (d3) | as A-01 | stated as an analogy, or model-checked |
| A-03 | (p2) needs a recorded act fixing each kind's owner. §10.3.2 L58 says no recorded act names a context yet, and that R-a cannot be applied. So "S admitted" rests on p2, which is unmet. Also, "that act is the amending act's holder" makes an act a holder | high | §10.3.2 L58; §10.3.6 L268 | Write "admitted under p2 (no such act recorded)" and fix the wording | 10.02 L5; 10.04 L12 | statuses carry p2 |
| A-04 | The partition guards appends only. Standing's `remove`/`annul` reach any target and `designate` moves every kind's carrier, so a future W's state is not "independently owned" against S | high | §9.13 L45–48 (removal, annulment: target; designation) | Either state that "independently owned" means *exclusively appended*, or add a guard on non-append operations across kinds | — | stated |
| A-05 | Premises incomplete: (p4) no reader takes a kind-subset as "the record" (asserted in the K2-r line, not stated as a premise); (p5) every new kind (for example G1's evidence kinds) receives an owner; (p6) D gains a required context field. Also, ρ is "the one record", while §9.17 L33 has a record set Ρ and (d3) presupposes merges | normal | §10.3.6 L260, L271; §9.17 L33 | Add p4–p6, and quantify (d1) over Ρ | — | listed |
| A-06 | The direction law's falsifier ("does not arrive as a **recorded** input") is met by K3: CE* is a function of "the record **and the inputs**", and no kind of D records covers. The narrowed form ("accepted through its own steps") replaces the definition and has no falsifier. Every read is "through its steps" | high | §9.15 L63, L76; §9.13 L52, L98, L327 | Result: "falsified as stated for unrecorded inputs. A narrowed law needs an input-acceptance step that is specified and recorded (it depends on T10-10/G11)." Give the narrowed law its own falsifier | 0 (§10.4.3) | the narrowed law has a falsifier, and K3's status under it is stated |
| A-07 | R8/R9 misattribute K3. Covers relates grant and act scopes (S's terms; CON-0173 is on 1's list; "4's rule scope is a different scope", §10.3.3 A I-5). The "identity link" is the actor–holder link I11, not item identity. So the sentence "those come from areas outside it (N, C)" is unsupported | high | §9.13 L98, L108; §10.3.3 L81, L102 | R8/R9: INVALID as stated. Name the inputs' source as open | 10.04 L47–48, L73 | restated |
| A-08 | Law 24 "if an application provides it": §9.16 R4c and §9.15 K4 v10 make the kernel the coordination point for single-use grants, and K4-o "has no remaining domain". Law 27 is an obligation on the session (an application) and is not evidence about kernel dependence | high | §9.16 L251–256; §9.15 L77 | Remove both from "against". The remaining dependence is L9 (record before use), a world assumption | 10.04 L73, L80 | removed |
| A-09 | Law 18 is called a "candidate falsifier", while K3 (same structure: an outside fact needed at a kernel step) is called a "narrowing". Law 18 also rests on a Hyp from one case (CON-0168, provisional), and held acts are not a kind of D | normal | §5.6 L28–31; §9.11 L54, L96 | Treat both the same way. Scope law 18 to "if held acts enter D" | 10.04 L79 | consistent |
| A-10 | Evidence omitted from "against" (question 6): the owner checks read a **declared** application (L10; §9.17 limit (ii)); the policy values θ are set by acts not admitted in D, and CON-0173 requires a common, versioned covers, θ and root across applications (§9.7 L86) | high | §9.13 L52, L252; §9.07 L86; register CON-0173 | Add these rows | — | added |
| A-11 | Header: "9 of 13" conditional, but 8 rows are marked ◇. R10 bundles C→D and N→D. R13 depends on N and W (both pending) but is unconditional. R11's class half rests on G1 (§10.3.3 C) | high | 10.04 L5, L38–53, L91 | Split R10. Mark R13 (and R11 partly) ◇ with the five items. Recount (≥10 of 14) | 10.04 L5, L91 | the count matches the table |
| A-12 | Missing relationships: S → AU (actors read their standing; S0069 R-2 "writes in, queries out"); W → AU or N "informs, never binds" (S0196 L538, where AR-1 is "Normative Governance", L336); N → W/A, criteria (S0196 L537); N → WM, rule at START (law 27; S0069 R-1); C → W (G10); S ↔ C for I-3 and I-1 (shared, §10.3.3 E). §10.4.1's "W → S below" names an absent row: **lost content** (the draft never drew it), not a stale pointer | high | sources cited | Add the rows, or exclude each with a reason | 10.04 L29 | each one drawn or excluded |
| A-13 | Direction semantics are mixed. Arrows read "upstream → downstream" put AU, N and C upstream of S (R1, R8, R9), yet §10.4.5 says "S is upstream of every other area". "Upstream" is never defined (R2 below). CON-0147 (validated) requires relations whose dependencies can be identified | high | 10.04 L36, L96; §6.7 C5 | Type each arrow (depends / writes / authorizes / informs) per CON-0147. Then restate §10.4.5 | 06.07 L57; register CON-0147 | arrows typed |
| A-14 | Prior work omitted: S0069 (an accepted map; R-8 "platform reads/guards app/" means the platform reads the product's state, which counts as *depending* under the draft's own definition); §1.14.4-7 and the upward-edge test (L367), left open by §6.6 C8 itself; §8.28 C1 (criterion 6, S0187); §8.09 C7 ("a stated norm, not an observed property") | high | S0069 L50–54, L57–66; 01.14 L359, L367; 06.06 L74; 08.28 L36–48; 08.09 L69 | Add these and judge them | 06.06 L72–74 | judged |
| A-15 | S0196 read selectively. Its patterns are "recommended relationship classifications, not established relationships" (L598). AC-1 → AR-1 parallels assessment → *normative governance*, not W → S. The rows L537 and L540 are omitted | normal | S0196 L336, L537–541, L598 | Restate the parallel | 10.04 L29 | restated |
| A-16 | S0162's warning is only partly answered. Its arrows should mean events, query contracts, conforming, translation, projection or policy decision, "not merely 'data flows downward'". R5, R8, R9 and R12 are data flows, and "translated at entry" (R12) names no translator. No anti-corruption layer appears anywhere | normal | S0162 L104–113 | State a translator for R12. Classify the inputs by S0162's list | — | stated |
| A-17 | T10-10 is well posed. Its recommendation rests on "the Principal's example" (deference) and on "one mechanism". It leaves unstated a consequence: since `adopt` and covers belong to S, N may never acquire exclusive kinds, so "only entries" may exclude N permanently. The "content too" branch says it "needs a second argument" but gives none | normal | §10.3.6 L290–295 | State that consequence for the Principal | — | stated |
| A-18 | Citation slips: "ARCH-INV-1 (the recorder decides nothing)" is the roles law (§1.14.3), not ARCH-INV-1 (never-grant). "Statement under test" puts quotation marks around a non-verbatim §6.6 C8 ("kernel" for "knowledge system"). G11 is used for norms (§10.3.6) and for the catalogue (§10.4.4) | normal | §10.3.3 L86, L101; 06.06 L72 | Fix | 10.04 L58, L72, L89 | fixed |
| A-19 | Ch. 10 TODO lacks T10-10, G11 and the direction-law and law-18 items. The T10-05 row is stale | normal | TODO.md | Update | — | updated |
| A-20 | R11 has no change effect ("—"), which the mandate §2 row 3 requires. R13's "↔" contradicts "M conforms" and L54 ("no relationship has … M … upstream of S") | normal | 10.04 L51–54 | Fix | — | fixed |

## Verdicts
**§10.3.6:**
- Definition (d1)–(d3): PROVISIONAL (A-01, A-05).
- K2 compatibility: VALIDATED for K2-d and K2-w; PROVISIONAL for K2-r (p4).
- "Same form": INVALID as stated (A-02).
- Application: N pending is correct under (d1); S admitted is PROVISIONAL (A-01, A-03).
- Discrepancy: correctly flagged. The quotation of the decision record was not verifiable (not allowed).
- T10-10: well posed (A-17).

**Per relationship:**
- R1 PROVISIONAL (reverse S → AU missing).
- R2 PROVISIONAL.
- R3–R5 PROVISIONAL and conditional, correctly marked.
- R6 PROVISIONAL (A-01; its coordination wording, A-08).
- R7 PROVISIONAL, conditional.
- R8 INVALID as stated (A-07).
- R9 INVALID as stated (A-07).
- R10 PROVISIONAL (split it).
- R11 PROVISIONAL (A-11, A-20).
- R12 PROVISIONAL (A-16; S0069 R-8 is a rival direction).
- R13 PROVISIONAL, mis-marked as unconditional.

**§10.4.3:**
- The meaning is stated.
- The falsifier is observable but already met (A-06).
- The evidence is incomplete (A-10, A-14).
- "Holds in a narrowed form": INVALID as stated.
- "Law 18 a candidate falsifier": PROVISIONAL, and inconsistent with the K3 treatment (A-09).
- "Not established, S0": VALIDATED.

**Pattern names:** no pattern name is used as a reason. Each row gives a stated relation.

## R2 (`--scope 10.03,10.04`: 24 in-scope candidates, tier A 0)
- **upstream:** undefined and used in two senses (A-13).
- The other 23 are ordinary English, or defined where they are used, so there is no finding: single-owned, participant, discrepancy, frame, particular, around, warning, selected, procedure, eight, violation, refinement (10.03 L201: defined in place), falsifier, merged (§9.16), operation (10.03 L61), apply, agent, plus, remain, ground, exactly, applied, keep.
- Words the tool did not flag: "context" against "application" (A-01); "identity link" and "scope" are homonyms (A-07); G11 (A-18).

## Quote check
- `corpus_quote_check.py`: 0 bracketed quotes in either file.
- Checked by hand, correct: S0033 L171; S0162 L72, L107–108, L458; S0196 L538, L539, L541; S0036 L93 (in meaning); §9.15 L75 and L76 (K3); "Φ reads no carrier" (§9.13 L48).
- Altered: the §6.6 C8 statement line (A-18).

## Limits
- I did not open the decision record (forbidden), so I could not check the Principal's quoted words or the consequence line.
- I did not open S0150, S0187, S0052, S0066 or the outside literature.
- I did not run `ce_model.py`.
- I read §10.1 and most of §10.2 only through searches.

## Files opened
- `W/06-theory/manuscript/review/SD4-CONTEXT-MAP/BRIEF.md`
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`
- `W/03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`
- `W/03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md`: L50–60 (§3), plus a heading grep of the whole file
- `W/06-theory/manuscript/chapter_10/`:
  - `10.04-context-map.md` (whole)
  - `10.03-ownership-of-invariants.md` (whole)
  - `10.02-bounded-contexts.md`: L1–60, plus grep
  - `10.00-index.md`
  - `TODO.md`
  - `10.99-change-log.md`: grep only
- `W/06-theory/manuscript/chapter_09/`:
  - `09.15`: L60–95 and grep
  - `09.13`: L25–52, L98, grep
  - `09.16`: grep
  - `09.17`: grep
  - `09.06`: L219
  - `09.11`: grep
  - `09.07` and others: via grep
- `W/06-theory/manuscript/chapter_05/05.06-what-an-act-covers.md`: L28–32
- `W/06-theory/manuscript/chapter_06/`: `06.06` L70–80; `06.07` L55–66
- `W/06-theory/manuscript/chapter_08/`: `08.28` L36–58; `08.09` L66–72
- `W/06-theory/manuscript/chapter_01/`: `01.14` (grep, index lines); `01.17` L36
- `W/06-theory/manuscript/chapter_00/00.07`: L20
- `W/06-theory/CONCEPT-REGISTER.tsv`, `THEORY-MAP.md`, `manuscript/BOOK-OUTLINE.md`, `manuscript/TARGET-TOC.md`: grep only (BOOK-OUTLINE L56 read)
- `W/tmp/tools/`: heads of `undefined_terms.py`, `corpus_quote_check.py`, `quote_anchor_check.py` and `inbound_citations.py`
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`: head and grep
- Corpus sources, each confirmed in CORPUS-ALLOWED first:
  - S0033 `docs/knowledgeos/2026-08-02-knowledgeos-strategic-boundary-consolidation.md`: L165–200
  - S0162 `docs/knowledgeos/brainstorming/20260819-205757-ddd-correction-verification-verdict.md`: L72–112, L450–462, grep
  - S0149 `docs/knowledgeos/brainstorming/20260819-204431-kos-ddd-architecture-review-v3.md`: grep "context map" only
  - S0196 `docs/knowledgeos/architecture/20260821-2229-PKS-Current-Architecture-Baseline-Stage-2.md`: L525–545, L596–600, grep
  - S0036 `docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md`: L84–94, grep
  - S0069 `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-context-map-v2.md`: whole (84 lines, in parts)
- Scratch: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/sd4A/r2.txt`
- Large-output spill of 10.03: `/home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/b8q4vtha9.txt`

## Commands and searches run
1. `mkdir -p` scratch; `cat` BRIEF.
2. `cat` REVIEWER-INSTRUCTIONS; `wc -l` the mandate, the strategy and the chapter_10 files; `ls` the manuscript and `chapter_*`.
3. `cat -n` the mandate; `grep -n "^#"` the strategy.
4. `sed -n 50,60p` the strategy; `cat -n` 10.04.
5. `cat -n` 10.03 (spilled to the file above, then read).
6. `wc -l` the chapter_09 files; `sed -n 60,95p` 09.15.
7. `grep -n` in ch9: "R4a" in 09.16; "(c′)|c′|model check 32|R2|^**R" in 09.17; "L10" in 09.15.
8. `grep -n -i` in 09.13 for "kinds|ten kinds|endg|designate"; `grep -n` "reads no carrier" in chapter_09.
9. `sed -n 25,52p` 09.13.
10. `grep -n` 09.13 for "covers|identity link|pinned inputs|H3".
11. `grep -n` "direction law|never the reverse" in chapter_06; `sed -n 219p` 09.06; `sed -n 28,32p` 05.06; `grep` "law 18" in 09.11.
12. `sed` 06.06 L70–80 and 06.07 L55–66.
13. `grep -n -i "upward"` in chapter_01; `grep` headings of 01.14.
14. `head` and `grep` CORPUS-ALLOWED for S0033, S0162, S0149, S0196, S0036, S0091, S0052, S0066 and S0069.
15. `sed` S0033 L165–200.
16. `sed` S0162 L72–108 and L450–462.
17. `grep -n` in S0162 for the quote phrases, then `sed` L100–112; `grep -n -i "context map"` in S0149.
18. `sed` S0196 L525–545; `sed` S0036 L84–94.
19. `grep -P` CONCEPT-REGISTER for CON-0173, 0150, 0141, 0072, 0063, 0077, 0188, 0132, 0261, 0085, 0231 and 0143.
20. `grep` 09.15 for the L1–L11 rows; `sed` 01.17 L36 and 00.07 L20.
21. `undefined_terms.py --help`: the tool ignores the flag and printed the whole-book list.
22. `undefined_terms.py --scope 10.03,10.04`, saved with `tee` to r2.txt.
23. `head` of `corpus_quote_check.py`, `quote_anchor_check.py` and `inbound_citations.py`.
24. `grep -oP` bracketed corpus quotes in 10.03 and 10.04 (none).
25. `python3 -I corpus_quote_check.py` on 10.03 and 10.04; `grep` S0196 for "informs, never binds|Separate Ways|no return path"; `grep` S0036 for "owns no product|Never owns".
26. `sed` S0196 L596–600; `grep` the AC-1 and AR-1 rows in S0196.
27. `sed` 10.02 L1–60.
28. `grep` "G1[01]|**G[0-9]|T10-10|G4" in 10.02, TODO, 10.00 and 10.99.
29. `cat` ch10 TODO and index; `grep` "G11|T10-10" in THEORY-MAP, BOOK-OUTLINE and the register.
30. `wc` and `sed` S0069 L45–60, then L1–44 and L57–84.
31. `python3 -I inbound_citations.py` for "§10.3.6", "§10.4.3", "§10.4.4", "§6.6 C8" and "§10.3.4".
32. `sed` BOOK-OUTLINE L56, 08.28 L36–58 and 08.09 L66–72.
33. `grep` "10.3.6|10.4.3|T10-10|G11" across `manuscript/chapter_*/*.md`, THEORY-MAP, the register, BOOK-OUTLINE and TARGET-TOC.
34. `sed` 09.13 L98; `grep` "identity link" in chapter_09; `grep` "9.6.6a" in 09.06.

No search covered `06-theory/manuscript/` recursively, and no review folder other than SD4 was opened.
